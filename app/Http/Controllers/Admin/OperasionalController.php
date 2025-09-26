<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamaahProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Services\WhatsAppService;

class OperasionalController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Dashboard operasional
     */
    public function dashboard()
    {
        // Statistics
        $stats = [
            'total_ready' => JamaahProfile::where('data_approved_by_cs', true)
                ->where('payment_approved_by_admin', true)->count(),
            'ticket_pending' => JamaahProfile::where('data_approved_by_cs', true)
                ->where('payment_approved_by_admin', true)
                ->where('ticket_status', 'pending')->count(),
            'visa_pending' => JamaahProfile::where('data_approved_by_cs', true)
                ->where('payment_approved_by_admin', true)
                ->where('visa_status', 'pending')->count(),
            'ticket_completed' => JamaahProfile::where('ticket_status', 'completed')
                ->whereDate('ticket_processed_at', today())->count(),
            'visa_completed' => JamaahProfile::where('visa_status', 'completed')
                ->whereDate('visa_processed_at', today())->count(),
        ];

        // Recent activities (last 10 activities today)
        $recentActivities = collect();

        // Get recent ticket activities
        $ticketActivities = JamaahProfile::where('ticket_processed_at', '>=', today())
            ->whereNotNull('ticket_processed_at')
            ->with('ticketProcessor')
            ->orderBy('ticket_processed_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($jamaah) {
                return [
                    'id' => 'ticket_' . $jamaah->id,
                    'type' => $jamaah->ticket_status === 'completed' ? 'completed' : 'processing',
                    'description' => "Ticket {$jamaah->ticket_status} for {$jamaah->nama_lengkap_bin_binti}",
                    'time' => $jamaah->ticket_processed_at->diffForHumans()
                ];
            });

        // Get recent visa activities
        $visaActivities = JamaahProfile::where('visa_processed_at', '>=', today())
            ->whereNotNull('visa_processed_at')
            ->with('visaProcessor')
            ->orderBy('visa_processed_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($jamaah) {
                return [
                    'id' => 'visa_' . $jamaah->id,
                    'type' => $jamaah->visa_status === 'completed' ? 'completed' : 'processing',
                    'description' => "Visa {$jamaah->visa_status} for {$jamaah->nama_lengkap_bin_binti}",
                    'time' => $jamaah->visa_processed_at->diffForHumans()
                ];
            });

        $recentActivities = $ticketActivities->concat($visaActivities)
            ->sortByDesc(function ($activity) {
                return $activity['time'];
            })
            ->take(10)
            ->values()
            ->toArray();

        return Inertia::render('Admin/Operasional/Dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities
        ]);
    }

    /**
     * Display list of jamaah ready for operational processing (CS approved)
     */
    public function index(Request $request)
    {
        $query = JamaahProfile::with(['user', 'umrohPackage', 'ticketProcessor', 'visaProcessor'])
            ->where('data_approved_by_cs', true)
            ->where('payment_approved_by_admin', true);

        // Filter by ticket status
        if ($request->has('ticket_status') && $request->ticket_status !== '') {
            $query->where('ticket_status', $request->ticket_status);
        }

        // Filter by visa status
        if ($request->has('visa_status') && $request->visa_status !== '') {
            $query->where('visa_status', $request->visa_status);
        }

        // Search by name
        if ($request->has('search') && $request->search !== '') {
            $query->where('nama_lengkap_bin_binti', 'like', '%' . $request->search . '%');
        }

        $jamaah = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total_ready' => JamaahProfile::where('data_approved_by_cs', true)
                ->where('payment_approved_by_admin', true)->count(),
            'ticket_pending' => JamaahProfile::where('data_approved_by_cs', true)
                ->where('payment_approved_by_admin', true)
                ->where('ticket_status', 'pending')->count(),
            'visa_pending' => JamaahProfile::where('data_approved_by_cs', true)
                ->where('payment_approved_by_admin', true)
                ->where('visa_status', 'pending')->count(),
            'ticket_completed' => JamaahProfile::where('ticket_status', 'completed')->count(),
            'visa_completed' => JamaahProfile::where('visa_status', 'completed')->count(),
        ];

        return Inertia::render('Admin/Operasional/Index', [
            'jamaah' => $jamaah,
            'stats' => $stats,
            'filters' => $request->only(['ticket_status', 'visa_status', 'search'])
        ]);
    }

    /**
     * Show jamaah detail for operational processing
     */
    public function show($id)
    {
        $jamaah = JamaahProfile::with(['user', 'umrohPackage', 'ticketProcessor', 'visaProcessor'])
            ->where('data_approved_by_cs', true)
            ->where('payment_approved_by_admin', true)
            ->findOrFail($id);

        return Inertia::render('Admin/Operasional/Show', [
            'jamaah' => $jamaah
        ]);
    }

    /**
     * Update ticket status
     */
    public function updateTicketStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed',
            'notes' => 'nullable|string|max:1000'
        ]);

        $jamaah = JamaahProfile::where('data_approved_by_cs', true)
            ->where('payment_approved_by_admin', true)
            ->findOrFail($id);

        $jamaah->update([
            'ticket_status' => $request->status,
            'ticket_notes' => $request->notes,
            'ticket_processed_by' => Auth::id(),
            'ticket_processed_at' => now()
        ]);

        // Send notification if completed
        if ($request->status === 'completed') {
            $this->sendTicketCompletedNotification($jamaah);
        }

        return response()->json([
            'message' => 'Status tiket berhasil diperbarui',
            'jamaah' => $jamaah->fresh(['ticketProcessor'])
        ]);
    }

    /**
     * Update visa status
     */
    public function updateVisaStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed',
            'notes' => 'nullable|string|max:1000'
        ]);

        $jamaah = JamaahProfile::where('data_approved_by_cs', true)
            ->where('payment_approved_by_admin', true)
            ->findOrFail($id);

        $jamaah->update([
            'visa_status' => $request->status,
            'visa_notes' => $request->notes,
            'visa_processed_by' => Auth::id(),
            'visa_processed_at' => now()
        ]);

        // Send notification if completed
        if ($request->status === 'completed') {
            $this->sendVisaCompletedNotification($jamaah);
        }

        return response()->json([
            'message' => 'Status visa berhasil diperbarui',
            'jamaah' => $jamaah->fresh(['visaProcessor'])
        ]);
    }

    /**
     * Upload ticket document
     */
    public function uploadTicketDocument(Request $request, $id)
    {
        $request->validate([
            'ticket_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB
        ]);

        $jamaah = JamaahProfile::where('data_approved_by_cs', true)
            ->where('payment_approved_by_admin', true)
            ->findOrFail($id);

        // Delete old file if exists
        if ($jamaah->ticket_file) {
            Storage::disk('public')->delete($jamaah->ticket_file);
        }

        // Store new file
        $file = $request->file('ticket_file');
        $filename = time() . '_ticket_' . $jamaah->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('operational/tickets', $filename, 'public');

        $jamaah->update([
            'ticket_file' => $path,
            'ticket_processed_by' => Auth::id(),
            'ticket_processed_at' => now()
        ]);

        return response()->json([
            'message' => 'Dokumen tiket berhasil diupload',
            'jamaah' => $jamaah->fresh()
        ]);
    }

    /**
     * Upload visa document
     */
    public function uploadVisaDocument(Request $request, $id)
    {
        $request->validate([
            'visa_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB
        ]);

        $jamaah = JamaahProfile::where('data_approved_by_cs', true)
            ->where('payment_approved_by_admin', true)
            ->findOrFail($id);

        // Delete old file if exists
        if ($jamaah->visa_file) {
            Storage::disk('public')->delete($jamaah->visa_file);
        }

        // Store new file
        $file = $request->file('visa_file');
        $filename = time() . '_visa_' . $jamaah->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('operational/visas', $filename, 'public');

        $jamaah->update([
            'visa_file' => $path,
            'visa_processed_by' => Auth::id(),
            'visa_processed_at' => now()
        ]);

        return response()->json([
            'message' => 'Dokumen visa berhasil diupload',
            'jamaah' => $jamaah->fresh()
        ]);
    }

    /**
     * Send WhatsApp notification when ticket is completed
     */
    private function sendTicketCompletedNotification($jamaah)
    {
        // Send to CS and Admin
        $message = "🎫 *TIKET SELESAI DIPROSES*\n\n";
        $message .= "Nama: {$jamaah->nama_lengkap_bin_binti}\n";
        $message .= "Paket: {$jamaah->umrohPackage->nama_paket}\n";
        $message .= "Processor: " . Auth::user()->name . "\n";
        $message .= "Waktu: " . now()->format('d/m/Y H:i') . "\n\n";
        $message .= "Tiket telah siap untuk jamaah.";

        // Get CS and Admin users with phone numbers
        $csUsers = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'cs');
        })->whereNotNull('phone')->get();

        $adminUsers = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->whereNotNull('phone')->get();

        // Send to CS users
        foreach ($csUsers as $user) {
            $this->whatsappService->sendMessage($user->phone, $message);
        }

        // Send to Admin users
        foreach ($adminUsers as $user) {
            $this->whatsappService->sendMessage($user->phone, $message);
        }
    }

    /**
     * Send WhatsApp notification when visa is completed
     */
    private function sendVisaCompletedNotification($jamaah)
    {
        // Send to CS and Admin
        $message = "🛂 *VISA SELESAI DIPROSES*\n\n";
        $message .= "Nama: {$jamaah->nama_lengkap_bin_binti}\n";
        $message .= "Paket: {$jamaah->umrohPackage->nama_paket}\n";
        $message .= "Processor: " . Auth::user()->name . "\n";
        $message .= "Waktu: " . now()->format('d/m/Y H:i') . "\n\n";
        $message .= "Visa telah siap untuk jamaah.";

        // Get CS and Admin users with phone numbers
        $csUsers = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'cs');
        })->whereNotNull('phone')->get();

        $adminUsers = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->whereNotNull('phone')->get();

        // Send to CS users
        foreach ($csUsers as $user) {
            $this->whatsappService->sendMessage($user->phone, $message);
        }

        // Send to Admin users
        foreach ($adminUsers as $user) {
            $this->whatsappService->sendMessage($user->phone, $message);
        }
    }
}