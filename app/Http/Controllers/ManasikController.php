<?php

namespace App\Http\Controllers;

use App\Models\ManasikSession;
use App\Models\ManasikAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ManasikController extends Controller
{
    public function adminIndex(): Response
    {
        $sessions = ManasikSession::with(['attendances' => function($query) {
                $query->whereIn('status', ['registered', 'attended']);
            }])
            ->withCount(['attendances as participant_count' => function($query) {
                $query->whereIn('status', ['registered', 'attended']);
            }])
            ->orderBy('session_date', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Manasik/Index', [
            'sessions' => $sessions
        ]);
    }

    public function adminCreate(): Response
    {
        return Inertia::render('Admin/Manasik/Create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:online,offline,hybrid',
            'session_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string',
            'online_link' => 'nullable|url',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_contact' => 'nullable|string|max:255',
            'materials' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'max_participants' => 'nullable|integer|min:1'
        ]);

        $data = $request->all();
        $data['materials'] = $request->materials ? explode(',', $request->materials) : null;
        $data['status'] = 'scheduled'; // Ensure status is set

        ManasikSession::create($data);

        return redirect()->route('admin.manasik.index')
            ->with('success', 'Sesi manasik berhasil dibuat');
    }

    public function adminShow($id): Response
    {
        $session = ManasikSession::with(['attendances.user'])
            ->findOrFail($id);

        return Inertia::render('Admin/Manasik/Show', [
            'session' => $session
        ]);
    }

    public function adminEdit($id): Response
    {
        $session = ManasikSession::findOrFail($id);

        return Inertia::render('Admin/Manasik/Edit', [
            'session' => $session
        ]);
    }

    public function adminUpdate(Request $request, $id)
    {
        $session = ManasikSession::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:online,offline,hybrid',
            'session_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string',
            'online_link' => 'nullable|url',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_contact' => 'nullable|string|max:255',
            'materials' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'max_participants' => 'nullable|integer|min:1'
        ]);

        $data = $request->all();
        $data['materials'] = $request->materials ? explode(',', $request->materials) : null;

        $session->update($data);

        return redirect()->route('admin.manasik.index')
            ->with('success', 'Sesi manasik berhasil diupdate');
    }

    public function adminDestroy($id)
    {
        $session = ManasikSession::findOrFail($id);
        $session->delete();

        return redirect()->route('admin.manasik.index')
            ->with('success', 'Sesi manasik berhasil dihapus');
    }

    public function jamaahIndex(): Response
    {
        $upcomingSessions = ManasikSession::where('session_date', '>=', now()->startOfDay())
            ->where('status', 'scheduled')
            ->with(['attendances' => function($query) {
                $query->where('user_id', Auth::id());
            }])
            ->withCount(['attendances as participant_count' => function($query) {
                $query->whereIn('status', ['registered', 'attended']);
            }])
            ->orderBy('session_date')
            ->get();

        $pastSessions = ManasikSession::where('session_date', '<', now())
            ->whereHas('attendances', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['attendances' => function($query) {
                $query->where('user_id', Auth::id());
            }])
            ->orderBy('session_date', 'desc')
            ->get();

        return Inertia::render('Jamaah/Manasik', [
            'upcomingSessions' => $upcomingSessions,
            'pastSessions' => $pastSessions,
            'jamaahData' => [
                'id' => Auth::user()->jamaahProfile->id ?? null,
                'nama_lengkap' => Auth::user()->jamaahProfile->nama_lengkap_bin_binti ?? '',
                'current_step' => Auth::user()->jamaahProfile->getCurrentStep() ?? 1,
                'step_name' => Auth::user()->jamaahProfile->getStepName() ?? '',
            ]
        ]);
    }

    public function registerForSession(Request $request, $sessionId)
    {
        $session = ManasikSession::findOrFail($sessionId);

        // Check if already registered
        if ($session->hasUserRegistered(Auth::id())) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar untuk sesi ini');
        }

        // Check max participants
        if ($session->max_participants && $session->participant_count >= $session->max_participants) {
            return redirect()->back()->with('error', 'Sesi manasik sudah penuh');
        }

        ManasikAttendance::create([
            'manasik_session_id' => $sessionId,
            'user_id' => Auth::id(),
            'status' => 'registered',
            'registered_at' => now()
        ]);

        return redirect()->back()->with('success', 'Berhasil mendaftar untuk sesi manasik');
    }

    public function unregisterFromSession($sessionId)
    {
        $attendance = ManasikAttendance::where('manasik_session_id', $sessionId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum terdaftar untuk sesi ini');
        }

        $attendance->delete();

        return redirect()->back()->with('success', 'Berhasil membatalkan pendaftaran');
    }

    public function markAttendance(Request $request, $sessionId)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'status' => 'required|in:attended,absent,excused'
        ]);

        foreach ($request->user_ids as $userId) {
            $attendance = ManasikAttendance::where('manasik_session_id', $sessionId)
                ->where('user_id', $userId)
                ->first();

            if ($attendance) {
                if ($request->status === 'attended') {
                    $attendance->markAttended();
                } elseif ($request->status === 'absent') {
                    $attendance->markAbsent();
                } elseif ($request->status === 'excused') {
                    $attendance->markExcused($request->notes);
                }
            }
        }

        return redirect()->back()->with('success', 'Absensi berhasil diperbarui');
    }
}