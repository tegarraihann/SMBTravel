<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPayment;
use App\Models\JamaahProfile;
use App\Services\InstallmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class InstallmentController extends Controller
{
    protected InstallmentService $installmentService;

    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    /**
     * Display jamaah's installment payments
     */
    public function index(): Response
    {
        $jamaah = Auth::user()->jamaahProfile;

        if (!$jamaah) {
            abort(404, 'Jamaah profile not found');
        }

        $dashboardData = $this->installmentService->getJamaahPaymentDashboard($jamaah);

        if (!$dashboardData['success']) {
            return Inertia::render('Jamaah/InstallmentPayments', [
                'error' => $dashboardData['message']
            ]);
        }

        return Inertia::render('Jamaah/InstallmentPayments', [
            'paymentData' => $dashboardData['data'],
            'jamaahData' => [
                'id' => $jamaah->id,
                'nama_lengkap' => $jamaah->nama_lengkap_bin_binti,
                'program_talangan' => $jamaah->program_talangan,
                'current_step' => $jamaah->getCurrentStep(),
                'documents_verified' => $jamaah->documents_verified,
                'data_approved_by_cs' => $jamaah->data_approved_by_cs,
                'payment_approved_by_admin' => $jamaah->payment_approved_by_admin,
                'is_program_talangan' => $jamaah->program_talangan,
                'is_payment_complete' => $dashboardData['data']['is_payment_complete'] ?? false,
                'total_outstanding' => $dashboardData['data']['total_outstanding'] ?? 0
            ]
        ]);
    }

    /**
     * Get payment schedule for jamaah
     */
    public function schedule()
    {
        $jamaah = Auth::user()->jamaahProfile;

        if (!$jamaah) {
            return response()->json(['error' => 'Jamaah profile not found'], 404);
        }

        $dashboardData = $this->installmentService->getJamaahPaymentDashboard($jamaah);

        if (!$dashboardData['success']) {
            return response()->json(['error' => $dashboardData['message']], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $dashboardData['data']
        ]);
    }

    /**
     * Process installment payment
     */
    public function store(Request $request, $installmentId)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:500'
        ]);

        $installment = InstallmentPayment::findOrFail($installmentId);
        $jamaah = Auth::user()->jamaahProfile;

        // Check if installment belongs to current jamaah
        if ($installment->jamaah_profile_id !== $jamaah->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $result = $this->installmentService->processInstallmentPayment(
            $installment,
            $request->file('payment_proof'),
            $request->input('notes')
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Generate installment schedule (triggered when jamaah chooses program talangan)
     */
    public function generateSchedule(Request $request)
    {
        $jamaah = Auth::user()->jamaahProfile;

        if (!$jamaah) {
            return response()->json(['error' => 'Jamaah profile not found'], 404);
        }

        $result = $this->installmentService->generateScheduleFromJamaah($jamaah);

        return response()->json($result);
    }

    // ===== ADMIN METHODS =====

    /**
     * Admin: Display all installment payments
     */
    public function adminIndex(Request $request): Response
    {
        $query = InstallmentPayment::with(['jamaahProfile.user', 'approvedBy'])
            ->orderBy('due_date', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by jamaah
        if ($request->has('search') && $request->search) {
            $query->whereHas('jamaahProfile', function($q) use ($request) {
                $q->where('nama_lengkap_bin_binti', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by overdue
        if ($request->has('overdue') && $request->overdue === 'true') {
            $query->where(function($q) {
                $q->where('status', 'overdue')
                  ->orWhere(function($subQ) {
                      $subQ->where('status', 'pending')
                           ->where('due_date', '<', now());
                  });
            });
        }

        $installments = $query->paginate(20);

        // Get statistics
        $stats = $this->installmentService->getInstallmentStatistics();

        return Inertia::render('Admin/Installments/Index', [
            'installments' => $installments,
            'statistics' => $stats['success'] ? $stats['data'] : [],
            'filters' => $request->only(['status', 'search', 'overdue'])
        ]);
    }

    /**
     * Admin: Show specific jamaah installment details
     */
    public function adminShow($jamaahId): Response
    {
        $jamaah = JamaahProfile::with(['installmentPayments', 'user'])->findOrFail($jamaahId);

        $dashboardData = $this->installmentService->getJamaahPaymentDashboard($jamaah);

        return Inertia::render('Admin/Installments/Show', [
            'jamaah' => [
                'id' => $jamaah->id,
                'nama_lengkap' => $jamaah->nama_lengkap_bin_binti,
                'email' => $jamaah->user->email,
                'no_telepon' => $jamaah->no_telepon,
                'program_talangan' => $jamaah->program_talangan,
                'rencana_keberangkatan' => $jamaah->rencana_keberangkatan,
                'current_step' => $jamaah->getCurrentStep()
            ],
            'paymentData' => $dashboardData['success'] ? $dashboardData['data'] : null,
            'error' => !$dashboardData['success'] ? $dashboardData['message'] : null
        ]);
    }

    /**
     * Admin: Approve installment payment
     */
    public function approve(Request $request, $installmentId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $installment = InstallmentPayment::findOrFail($installmentId);

        $result = $this->installmentService->approveInstallmentPayment(
            $installment,
            Auth::id(),
            $request->input('notes')
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Admin: Reject installment payment
     */
    public function reject(Request $request, $installmentId)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $installment = InstallmentPayment::findOrFail($installmentId);

        try {
            $installment->update([
                'status' => 'pending',
                'paid_at' => null,
                'payment_proof' => null,
                'notes' => 'Ditolak: ' . $request->input('reason')
            ]);

            return redirect()->back()->with('success', 'Pembayaran cicilan ditolak');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak pembayaran cicilan');
        }
    }

    /**
     * Admin: Waive installment payment
     */
    public function waive(Request $request, $installmentId)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $installment = InstallmentPayment::findOrFail($installmentId);

        $result = $installment->waive(Auth::id(), $request->input('reason'));

        if ($result) {
            return redirect()->back()->with('success', 'Cicilan berhasil dibebaskan');
        }

        return redirect()->back()->with('error', 'Gagal membebaskan cicilan');
    }

    /**
     * Admin: Generate payment reminders
     */
    public function reminders()
    {
        $reminders = $this->installmentService->getPaymentReminders();

        return response()->json($reminders);
    }

    /**
     * Admin: Force generate schedule for jamaah
     */
    public function adminGenerateSchedule($jamaahId)
    {
        $jamaah = JamaahProfile::findOrFail($jamaahId);

        $result = $this->installmentService->generateScheduleFromJamaah($jamaah);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Download payment proof
     */
    public function downloadPaymentProof($installmentId)
    {
        $installment = InstallmentPayment::findOrFail($installmentId);

        // Check authorization
        if (Auth::user()->hasRole('jamaah')) {
            $jamaah = Auth::user()->jamaahProfile;
            if ($installment->jamaah_profile_id !== $jamaah->id) {
                abort(403, 'Unauthorized');
            }
        } elseif (!Auth::user()->hasAnyRole(['admin', 'cs'])) {
            abort(403, 'Unauthorized');
        }

        if (!$installment->payment_proof) {
            abort(404, 'Payment proof not found');
        }

        return response()->download(storage_path('app/public/' . $installment->payment_proof));
    }
}
