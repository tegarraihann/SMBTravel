<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\JamaahProfileController;
use App\Models\JamaahProfile;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/jamaah/daftar', function () {
    return Inertia::render('Jamaah/Daftar');
})->name('jamaah.daftar');

Route::post('/jamaah/daftar', function (Request $request) {
    $user = auth()->user();

    // Validate if user is jamaah and hasn't completed registration
    if (!$user || !$user->hasRole('jamaah')) {
        abort(403, 'Only jamaah can access this form');
    }

    if ($user->hasCompletedRegistration()) {
        return redirect()->route('jamaah.dashboard');
    }

    // Validate the form data
    $validated = $request->validate([
        'nama_lengkap_bin_binti' => 'required|string|max:255',
        'nik' => 'required|string|size:16',
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'usia' => 'required|integer|min:1|max:120',
        'jenis_kelamin' => 'required|in:L,P',
        'alamat' => 'required|string',
        'no_telepon' => 'required|string',
        'pekerjaan' => 'required|string|max:255',
        'nama_marketing' => 'nullable|string|max:255',
        'paket_id' => 'required|integer',
        'rencana_keberangkatan' => 'nullable|date',
        'program_talangan' => 'boolean',
        'cicilan_data' => 'nullable|array|max:5',
        'cicilan_data.*.dp' => 'numeric|min:0',
        'cicilan_data.*.tenor' => 'integer|min:1',
        'cicilan_data.*.cicilan_perbulan' => 'numeric|min:0',
        'sistem_pembayaran' => 'string|max:255',
        'dp_paid' => 'nullable|numeric|min:0',
        'tgl_pelunasan' => 'nullable|date',
        'jamaah_rombongan' => 'nullable|array',
        'jamaah_rombongan.*.nama' => 'required|string|max:255',
        'jamaah_rombongan.*.nik' => 'required|string|max:16',
        'jamaah_rombongan.*.tempat_tanggal_lahir' => 'required|string|max:255',
        'jamaah_rombongan.*.jenis_kelamin' => 'required|in:L,P',
        'jamaah_rombongan.*.alamat' => 'required|string',
        'jamaah_rombongan.*.no_telp' => 'required|string|max:20',
        'sumber_info_mmb' => 'nullable|string|max:255',
        'kelengkapan_dokumen' => 'nullable|string',
        'tanggal_diterima_perlengkapan' => 'nullable|date',
        'detail_perlengkapan_diterima' => 'nullable|string',
        'pic_penerima' => 'nullable|string|max:255',
        'request_khusus' => 'nullable|string',
        'setuju' => 'required|accepted'
    ]);

    // Create jamaah profile
    $jamaahProfile = JamaahProfile::create([
        'user_id' => $user->id,
        'nama_lengkap_bin_binti' => $validated['nama_lengkap_bin_binti'],
        'nik' => $validated['nik'],
        'tempat_lahir' => $validated['tempat_lahir'],
        'tanggal_lahir' => $validated['tanggal_lahir'],
        'usia' => $validated['usia'],
        'jenis_kelamin' => $validated['jenis_kelamin'],
        'alamat' => $validated['alamat'],
        'no_telepon' => $validated['no_telepon'],
        'pekerjaan' => $validated['pekerjaan'],
        'nama_marketing' => $validated['nama_marketing'],
        'paket_id' => $validated['paket_id'],
        'rencana_keberangkatan' => $validated['rencana_keberangkatan'],
        'program_talangan' => $validated['program_talangan'] ?? false,
        'cicilan_data' => $validated['cicilan_data'] ?? null,
        'sistem_pembayaran' => $validated['sistem_pembayaran'] ?? 'Transfer',
        'dp_paid' => $validated['dp_paid'] ?? 0,
        'tgl_pelunasan' => $validated['tgl_pelunasan'],
        'jamaah_rombongan' => $validated['jamaah_rombongan'] ?? [],
        'sumber_info_mmb' => $validated['sumber_info_mmb'],
        'kelengkapan_dokumen' => $validated['kelengkapan_dokumen'],
        'tanggal_diterima_perlengkapan' => $validated['tanggal_diterima_perlengkapan'],
        'detail_perlengkapan_diterima' => $validated['detail_perlengkapan_diterima'],
        'pic_penerima' => $validated['pic_penerima'],
        'request_khusus' => $validated['request_khusus'],
        'current_step' => 2, // Otomatis ke step 2 (Pembayaran)
        'status' => 'processing',
        'data_approved_by_cs' => false,
        'payment_approved_by_admin' => false,
        'registration_completed_at' => now()
    ]);

    return redirect()->route('jamaah.dashboard');
})->middleware(['auth', 'role:jamaah']);

Route::get('/jamaah/dashboard', function () {
    $user = auth()->user();

    // Check if user is jamaah
    if (!$user || !$user->hasRole('jamaah')) {
        abort(403, 'Only jamaah can access this dashboard');
    }

    // Check if registration is completed
    if (!$user->hasCompletedRegistration()) {
        return redirect()->route('jamaah.daftar');
    }

    $jamaahProfile = $user->jamaahProfile;

    $jamaahData = [
        'id' => $jamaahProfile->id,
        'nama_lengkap' => $jamaahProfile->nama_lengkap_bin_binti,
        'email' => $user->email,
        'current_step' => $jamaahProfile->getCurrentStep(),
        'paket_id' => $jamaahProfile->paket_id,
        'status' => $jamaahProfile->status,
        'dp_paid' => $jamaahProfile->dp_paid,
        'created_at' => $jamaahProfile->created_at,
        'foto_ktp' => $jamaahProfile->foto_ktp,
        'foto_kk' => $jamaahProfile->foto_kk,
        'foto_paspor' => $jamaahProfile->foto_paspor,
        'foto_diri' => $jamaahProfile->foto_diri,
        'bukti_transfer' => $jamaahProfile->bukti_transfer,
        'documents_uploaded_at' => $jamaahProfile->documents_uploaded_at,
        'documents_verified' => $jamaahProfile->documents_verified
    ];

    return Inertia::render('Jamaah/Dashboard', [
        'jamaah' => $jamaahData
    ]);
})->middleware(['auth', 'role:jamaah'])->name('jamaah.dashboard');

// Jamaah routes group - requires jamaah role
Route::prefix('jamaah')->name('jamaah.')->middleware(['auth', 'role:jamaah'])->group(function () {

    // Dokumen routes
    Route::get('/dokumen', [JamaahProfileController::class, 'showDocuments'])
        ->name('dokumen')->middleware('permission:upload_documents');

    Route::post('/dokumen/upload', [JamaahProfileController::class, 'uploadDocument'])
        ->name('dokumen.upload')->middleware('permission:upload_documents');

    Route::delete('/dokumen/delete', [JamaahProfileController::class, 'deleteDocument'])
        ->name('dokumen.delete')->middleware('permission:upload_documents');

    Route::get('/dokumen/download/{type}', [JamaahProfileController::class, 'downloadDocument'])
        ->name('dokumen.download')->middleware('permission:upload_documents');

    Route::post('/dokumen/complete', [JamaahProfileController::class, 'completeDocumentUpload'])
        ->name('dokumen.complete')->middleware('permission:upload_documents');

    // Pembayaran routes (update existing)
    Route::get('/pembayaran', [JamaahProfileController::class, 'showPayment'])
        ->name('pembayaran')->middleware('permission:make_payments');

    Route::post('/pembayaran/upload', [JamaahProfileController::class, 'uploadPaymentProof'])
        ->name('pembayaran.upload')->middleware('permission:make_payments');

    // Manasik routes
    Route::get('/manasik', [\App\Http\Controllers\ManasikController::class, 'jamaahIndex'])
        ->name('manasik')->middleware('permission:view_own_bookings');

    Route::post('/manasik/{sessionId}/register', [\App\Http\Controllers\ManasikController::class, 'registerForSession'])
        ->name('manasik.register')->middleware('permission:view_own_bookings');

    Route::delete('/manasik/{sessionId}/unregister', [\App\Http\Controllers\ManasikController::class, 'unregisterFromSession'])
        ->name('manasik.unregister')->middleware('permission:view_own_bookings');

    // Installment Payment routes
    Route::get('/installments', [\App\Http\Controllers\InstallmentController::class, 'index'])
        ->name('installments')->middleware('permission:make_payments');

    Route::get('/installments/schedule', [\App\Http\Controllers\InstallmentController::class, 'schedule'])
        ->name('installments.schedule')->middleware('permission:make_payments');

    Route::post('/installments/generate', [\App\Http\Controllers\InstallmentController::class, 'generateSchedule'])
        ->name('installments.generate')->middleware('permission:make_payments');

    Route::post('/installments/{installment}/pay', [\App\Http\Controllers\InstallmentController::class, 'store'])
        ->name('installments.pay')->middleware('permission:make_payments');

    Route::get('/installments/{installment}/download', [\App\Http\Controllers\InstallmentController::class, 'downloadPaymentProof'])
        ->name('installments.download')->middleware('permission:make_payments');

    // Status/tracking routes
    Route::get('/status', function () {
        return Inertia::render('Jamaah/Status');
    })->name('status')->middleware('permission:view_own_bookings');

    // Profile jamaah
    Route::get('/profile', function () {
        return Inertia::render('Jamaah/Profile');
    })->name('profile')->middleware('permission:view_own_profile');

    Route::patch('/profile', function () {
        // Handle profile update
        return response()->json(['message' => 'Profile berhasil diupdate']);
    })->name('profile.update')->middleware('permission:update_own_profile');

    // Logout jamaah
    Route::post('/logout', function () {
        // Handle jamaah logout
        return redirect()->route('landing');
    })->name('logout');
});

// Admin routes - requires admin role
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::get('/jamaah', function () {
        return Inertia::render('Admin/Jamaah/Index');
    })->name('jamaah.index')->middleware('permission:manage_jamaah');

    Route::get('/jamaah/{id}', function ($id) {
        return Inertia::render('Admin/Jamaah/Show', ['jamaah_id' => $id]);
    })->name('jamaah.show')->middleware('permission:manage_jamaah');

    Route::patch('/jamaah/{id}/approve', function ($id) {
        // Approve jamaah payment/documents
        return response()->json(['message' => 'Jamaah berhasil disetujui']);
    })->name('jamaah.approve')->middleware('permission:manage_jamaah');

    Route::patch('/jamaah/{id}/reject', function ($id) {
        // Reject jamaah payment/documents
        return response()->json(['message' => 'Jamaah ditolak']);
    })->name('jamaah.reject')->middleware('permission:manage_jamaah');

    // Admin Payment Approval routes
    Route::patch('/jamaah/{id}/approve-payment', function ($id) {
        $jamaahProfile = \App\Models\JamaahProfile::findOrFail($id);

        if (!$jamaahProfile->canAdminApprove()) {
            return response()->json(['error' => 'Tidak dapat approve pembayaran saat ini'], 400);
        }

        $jamaahProfile->update([
            'payment_approved_by_admin' => true,
            'admin_approval_at' => now()
        ]);

        // Check if both CS and Admin have approved, then advance step
        if ($jamaahProfile->data_approved_by_cs && $jamaahProfile->payment_approved_by_admin) {
            $jamaahProfile->advanceToNextStep();
        }

        return response()->json(['message' => 'Pembayaran berhasil disetujui oleh Admin']);
    })->name('jamaah.approve.payment')->middleware('permission:manage_jamaah');

    Route::patch('/jamaah/{id}/reject-payment', function ($id) {
        $jamaahProfile = \App\Models\JamaahProfile::findOrFail($id);

        $jamaahProfile->update([
            'payment_approved_by_admin' => false,
            'bukti_transfer' => null,
            'dp_paid' => 0,
            'current_step' => 2 // Kembali ke step 2 untuk upload ulang bukti
        ]);

        return response()->json(['message' => 'Pembayaran ditolak. Jamaah diminta upload ulang bukti transfer.']);
    })->name('jamaah.reject.payment')->middleware('permission:manage_jamaah');

    // User management
    Route::get('/users', function () {
        return Inertia::render('Admin/Users/Index');
    })->name('users.index')->middleware('permission:manage_users');

    // Package management
    Route::get('/packages', function () {
        return Inertia::render('Admin/Packages/Index');
    })->name('packages.index')->middleware('permission:manage_packages');

    // Reports
    Route::get('/reports', function () {
        return Inertia::render('Admin/Reports/Index');
    })->name('reports.index')->middleware('permission:view_all_reports');

    // Payment Review
    Route::get('/payment-review', function () {
        // Get jamaah profiles that need payment review or have been reviewed
        $jamaahProfiles = \App\Models\JamaahProfile::with(['user'])
            ->where('current_step', '>=', 3)
            ->whereNotNull('bukti_transfer')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($profile) {
                return [
                    'id' => $profile->id,
                    'nama_lengkap' => $profile->nama_lengkap_bin_binti,
                    'email' => $profile->user->email,
                    'jenis_kelamin' => $profile->jenis_kelamin,
                    'no_telepon' => $profile->no_telepon,
                    'paket_id' => $profile->paket_id,
                    'status' => $profile->status ?? 'processing',
                    'current_step' => $profile->getCurrentStep(),
                    'step_name' => $profile->getStepName(),
                    'status_display' => $profile->getStatusDisplay(),
                    'dp_paid' => $profile->dp_paid,
                    'bukti_transfer' => $profile->bukti_transfer,
                    'data_approved_by_cs' => $profile->data_approved_by_cs,
                    'payment_approved_by_admin' => $profile->payment_approved_by_admin,
                    'can_cs_approve' => $profile->canCSApprove(),
                    'can_admin_approve' => $profile->canAdminApprove(),
                    'created_at' => $profile->created_at,
                ];
            });

        return Inertia::render('Admin/PaymentReview', [
            'jamaahList' => $jamaahProfiles
        ]);
    })->name('payment.review')->middleware('permission:manage_jamaah');

    // Payment Review Detail
    Route::get('/payment-review/{id}', function ($id) {
        $jamaahProfile = \App\Models\JamaahProfile::with(['user'])
            ->findOrFail($id);

        return Inertia::render('Admin/PaymentReviewDetail', [
            'jamaahData' => [
                'id' => $jamaahProfile->id,
                'nama_lengkap' => $jamaahProfile->nama_lengkap_bin_binti,
                'email' => $jamaahProfile->user->email,
                'nik' => $jamaahProfile->nik,
                'jenis_kelamin' => $jamaahProfile->jenis_kelamin,
                'tempat_lahir' => $jamaahProfile->tempat_lahir,
                'tanggal_lahir' => $jamaahProfile->tanggal_lahir ? $jamaahProfile->tanggal_lahir->format('d/m/Y') : null,
                'usia' => $jamaahProfile->usia,
                'alamat' => $jamaahProfile->alamat,
                'no_telepon' => $jamaahProfile->no_telepon,
                'pekerjaan' => $jamaahProfile->pekerjaan,
                'nama_marketing' => $jamaahProfile->nama_marketing,
                'paket_id' => $jamaahProfile->paket_id,
                'rencana_keberangkatan' => $jamaahProfile->rencana_keberangkatan ? $jamaahProfile->rencana_keberangkatan->format('d/m/Y') : null,
                'program_talangan' => $jamaahProfile->program_talangan,
                'cicilan_data' => $jamaahProfile->cicilan_data,
                'sistem_pembayaran' => $jamaahProfile->sistem_pembayaran,
                'dp_paid' => $jamaahProfile->dp_paid,
                'tgl_pelunasan' => $jamaahProfile->tgl_pelunasan ? $jamaahProfile->tgl_pelunasan->format('d/m/Y') : null,
                'bukti_transfer' => $jamaahProfile->bukti_transfer,
                'jamaah_rombongan' => $jamaahProfile->jamaah_rombongan,
                'sumber_info_mmb' => $jamaahProfile->sumber_info_mmb,
                'kelengkapan_dokumen' => $jamaahProfile->kelengkapan_dokumen,
                'request_khusus' => $jamaahProfile->request_khusus,
                'status' => $jamaahProfile->status ?? 'processing',
                'current_step' => $jamaahProfile->getCurrentStep(),
                'step_name' => $jamaahProfile->getStepName(),
                'status_display' => $jamaahProfile->getStatusDisplay(),
                'data_approved_by_cs' => $jamaahProfile->data_approved_by_cs,
                'payment_approved_by_admin' => $jamaahProfile->payment_approved_by_admin,
                'cs_approval_at' => $jamaahProfile->cs_approval_at?->format('d/m/Y H:i'),
                'admin_approval_at' => $jamaahProfile->admin_approval_at?->format('d/m/Y H:i'),
                'can_cs_approve' => $jamaahProfile->canCSApprove(),
                'can_admin_approve' => $jamaahProfile->canAdminApprove(),
                'registration_completed_at' => $jamaahProfile->registration_completed_at?->format('d/m/Y H:i'),
                'created_at' => $jamaahProfile->created_at->format('d/m/Y H:i'),
                'updated_at' => $jamaahProfile->updated_at->format('d/m/Y H:i'),
            ]
        ]);
    })->name('payment.review.show')->middleware('permission:manage_jamaah');

    // Manasik Management
    Route::prefix('manasik')->name('manasik.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ManasikController::class, 'adminIndex'])
            ->name('index')->middleware('permission:manage_jamaah');

        Route::get('/create', [\App\Http\Controllers\ManasikController::class, 'adminCreate'])
            ->name('create')->middleware('permission:manage_jamaah');

        Route::post('/', [\App\Http\Controllers\ManasikController::class, 'adminStore'])
            ->name('store')->middleware('permission:manage_jamaah');

        Route::get('/{id}', [\App\Http\Controllers\ManasikController::class, 'adminShow'])
            ->name('show')->middleware('permission:manage_jamaah');

        Route::get('/{id}/edit', [\App\Http\Controllers\ManasikController::class, 'adminEdit'])
            ->name('edit')->middleware('permission:manage_jamaah');

        Route::put('/{id}', [\App\Http\Controllers\ManasikController::class, 'adminUpdate'])
            ->name('update')->middleware('permission:manage_jamaah');

        Route::delete('/{id}', [\App\Http\Controllers\ManasikController::class, 'adminDestroy'])
            ->name('destroy')->middleware('permission:manage_jamaah');

        Route::post('/{sessionId}/attendance', [\App\Http\Controllers\ManasikController::class, 'markAttendance'])
            ->name('attendance')->middleware('permission:manage_jamaah');
    });

    // Installment Management
    Route::prefix('installments')->name('installments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\InstallmentController::class, 'adminIndex'])
            ->name('index')->middleware('permission:manage_jamaah');

        Route::get('/{jamaah}/show', [\App\Http\Controllers\InstallmentController::class, 'adminShow'])
            ->name('show')->middleware('permission:manage_jamaah');

        Route::post('/{jamaah}/generate', [\App\Http\Controllers\InstallmentController::class, 'adminGenerateSchedule'])
            ->name('generate')->middleware('permission:manage_jamaah');

        Route::post('/{installment}/approve', [\App\Http\Controllers\InstallmentController::class, 'approve'])
            ->name('approve')->middleware('permission:manage_jamaah');

        Route::post('/{installment}/reject', [\App\Http\Controllers\InstallmentController::class, 'reject'])
            ->name('reject')->middleware('permission:manage_jamaah');

        Route::post('/{installment}/waive', [\App\Http\Controllers\InstallmentController::class, 'waive'])
            ->name('waive')->middleware('permission:manage_jamaah');

        Route::get('/reminders', [\App\Http\Controllers\InstallmentController::class, 'reminders'])
            ->name('reminders')->middleware('permission:manage_jamaah');

        Route::get('/{installment}/download', [\App\Http\Controllers\InstallmentController::class, 'downloadPaymentProof'])
            ->name('download')->middleware('permission:manage_jamaah');
    });
});

// Marketing routes - requires marketing role
Route::prefix('marketing')->name('marketing.')->middleware(['auth', 'role:marketing'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Marketing/Dashboard');
    })->name('dashboard');

    Route::get('/leads', function () {
        return Inertia::render('Marketing/Leads/Index');
    })->name('leads.index')->middleware('permission:manage_leads');

    Route::get('/promotions', function () {
        return Inertia::render('Marketing/Promotions/Index');
    })->name('promotions.index')->middleware('permission:create_promotions');

    Route::get('/reports', function () {
        return Inertia::render('Marketing/Reports/Index');
    })->name('reports.index')->middleware('permission:view_reports');
});

// Pimpinan routes - requires pimpinan role
Route::prefix('pimpinan')->name('pimpinan.')->middleware(['auth', 'role:pimpinan'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Pimpinan/Dashboard');
    })->name('dashboard')->middleware('permission:view_executive_dashboard');

    Route::get('/financial-reports', function () {
        return Inertia::render('Pimpinan/FinancialReports');
    })->name('financial.reports')->middleware('permission:view_financial_reports');

    Route::get('/performance-reports', function () {
        return Inertia::render('Pimpinan/PerformanceReports');
    })->name('performance.reports')->middleware('permission:view_performance_reports');
});

// Customer Service routes - requires cs role
Route::prefix('cs')->name('cs.')->middleware(['auth', 'role:cs'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Cs/Dashboard');
    })->name('dashboard');

    Route::get('/jamaah', function () {
        // Get all jamaah profiles with their user data
        $jamaahProfiles = \App\Models\JamaahProfile::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($profile) {
                return [
                    'id' => $profile->id,
                    'nama_lengkap' => $profile->nama_lengkap_bin_binti,
                    'email' => $profile->user->email,
                    'jenis_kelamin' => $profile->jenis_kelamin,
                    'no_telepon' => $profile->no_telepon,
                    'paket_id' => $profile->paket_id,
                    'status' => $profile->status ?? 'processing',
                    'current_step' => $profile->getCurrentStep(),
                    'step_name' => $profile->getStepName(),
                    'status_display' => $profile->getStatusDisplay(),
                    'dp_paid' => $profile->dp_paid,
                    'bukti_transfer' => $profile->bukti_transfer,
                    'data_approved_by_cs' => $profile->data_approved_by_cs,
                    'payment_approved_by_admin' => $profile->payment_approved_by_admin,
                    'can_cs_approve' => $profile->canCSApprove(),
                    'can_admin_approve' => $profile->canAdminApprove(),
                    'is_awaiting_cs' => $profile->isAwaitingCSApproval(),
                    'is_awaiting_admin' => $profile->isAwaitingAdminApproval(),
                    'created_at' => $profile->created_at,
                    'registration_completed_at' => $profile->registration_completed_at,
                    'is_complete' => $profile->isRegistrationComplete(),
                ];
            });

        return Inertia::render('Cs/Jamaah/Index', [
            'jamaahList' => $jamaahProfiles
        ]);
    })->name('jamaah.index')->middleware('permission:view_jamaah_list');

    // CS Jamaah Detail
    Route::get('/jamaah/{id}', function ($id) {
        $jamaahProfile = \App\Models\JamaahProfile::with(['user'])
            ->findOrFail($id);

        return Inertia::render('Cs/Jamaah/Show', [
            'jamaahData' => [
                'id' => $jamaahProfile->id,
                'nama_lengkap' => $jamaahProfile->nama_lengkap_bin_binti,
                'email' => $jamaahProfile->user->email,
                'nik' => $jamaahProfile->nik,
                'jenis_kelamin' => $jamaahProfile->jenis_kelamin,
                'tempat_lahir' => $jamaahProfile->tempat_lahir,
                'tanggal_lahir' => $jamaahProfile->tanggal_lahir ? $jamaahProfile->tanggal_lahir->format('d/m/Y') : null,
                'usia' => $jamaahProfile->usia,
                'alamat' => $jamaahProfile->alamat,
                'no_telepon' => $jamaahProfile->no_telepon,
                'pekerjaan' => $jamaahProfile->pekerjaan,
                'nama_marketing' => $jamaahProfile->nama_marketing,
                'paket_id' => $jamaahProfile->paket_id,
                'rencana_keberangkatan' => $jamaahProfile->rencana_keberangkatan ? $jamaahProfile->rencana_keberangkatan->format('d/m/Y') : null,
                'program_talangan' => $jamaahProfile->program_talangan,
                'cicilan_data' => $jamaahProfile->cicilan_data,
                'sistem_pembayaran' => $jamaahProfile->sistem_pembayaran,
                'dp_paid' => $jamaahProfile->dp_paid,
                'tgl_pelunasan' => $jamaahProfile->tgl_pelunasan ? $jamaahProfile->tgl_pelunasan->format('d/m/Y') : null,
                'bukti_transfer' => $jamaahProfile->bukti_transfer,
                'jamaah_rombongan' => $jamaahProfile->jamaah_rombongan,
                'sumber_info_mmb' => $jamaahProfile->sumber_info_mmb,
                'kelengkapan_dokumen' => $jamaahProfile->kelengkapan_dokumen,
                'tanggal_diterima_perlengkapan' => $jamaahProfile->tanggal_diterima_perlengkapan ? $jamaahProfile->tanggal_diterima_perlengkapan->format('d/m/Y') : null,
                'detail_perlengkapan_diterima' => $jamaahProfile->detail_perlengkapan_diterima,
                'pic_penerima' => $jamaahProfile->pic_penerima,
                'request_khusus' => $jamaahProfile->request_khusus,
                'status' => $jamaahProfile->status ?? 'processing',
                'current_step' => $jamaahProfile->getCurrentStep(),
                'step_name' => $jamaahProfile->getStepName(),
                'status_display' => $jamaahProfile->getStatusDisplay(),
                'data_approved_by_cs' => $jamaahProfile->data_approved_by_cs,
                'payment_approved_by_admin' => $jamaahProfile->payment_approved_by_admin,
                'cs_approval_at' => $jamaahProfile->cs_approval_at?->format('d/m/Y H:i'),
                'admin_approval_at' => $jamaahProfile->admin_approval_at?->format('d/m/Y H:i'),
                'can_cs_approve' => $jamaahProfile->canCSApprove(),
                'can_admin_approve' => $jamaahProfile->canAdminApprove(),
                'registration_completed_at' => $jamaahProfile->registration_completed_at?->format('d/m/Y H:i'),
                'is_complete' => $jamaahProfile->isRegistrationComplete(),
                'created_at' => $jamaahProfile->created_at->format('d/m/Y H:i'),
                'updated_at' => $jamaahProfile->updated_at->format('d/m/Y H:i'),
            ]
        ]);
    })->name('jamaah.show')->middleware('permission:view_jamaah_list');

    Route::get('/inquiries', function () {
        return Inertia::render('Cs/Inquiries/Index');
    })->name('inquiries.index')->middleware('permission:respond_to_inquiries');

    Route::get('/complaints', function () {
        return Inertia::render('Cs/Complaints/Index');
    })->name('complaints.index')->middleware('permission:manage_complaints');

    // CS Data Approval routes
    Route::patch('/jamaah/{id}/approve-data', function ($id) {
        $jamaahProfile = \App\Models\JamaahProfile::findOrFail($id);

        if (!$jamaahProfile->canCSApprove()) {
            return response()->json(['error' => 'Tidak dapat approve data saat ini'], 400);
        }

        $jamaahProfile->update([
            'data_approved_by_cs' => true,
            'cs_approval_at' => now()
        ]);

        // Check if both CS and Admin have approved, then advance step
        if ($jamaahProfile->data_approved_by_cs && $jamaahProfile->payment_approved_by_admin) {
            $jamaahProfile->advanceToNextStep();
        }

        return response()->json(['message' => 'Data jamaah berhasil disetujui oleh CS']);
    })->name('jamaah.approve.data')->middleware('permission:view_jamaah_list');

    Route::patch('/jamaah/{id}/reject-data', function ($id) {
        $jamaahProfile = \App\Models\JamaahProfile::findOrFail($id);

        $jamaahProfile->update([
            'data_approved_by_cs' => false,
            'status' => 'rejected',
            'current_step' => 1 // Kembali ke step 1 untuk perbaikan data
        ]);

        return response()->json(['message' => 'Data jamaah ditolak. Jamaah diminta memperbaiki data.']);
    })->name('jamaah.reject.data')->middleware('permission:view_jamaah_list');
});

// Agent routes - requires agent role
Route::prefix('agent')->name('agent.')->middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Agent/Dashboard');
    })->name('dashboard');

    Route::get('/jamaah', function () {
        return Inertia::render('Agent/Jamaah/Index');
    })->name('jamaah.index')->middleware('permission:view_assigned_jamaah');

    Route::get('/register-jamaah', function () {
        return Inertia::render('Agent/RegisterJamaah');
    })->name('register.jamaah')->middleware('permission:register_jamaah');

    Route::get('/commission', function () {
        return Inertia::render('Agent/Commission');
    })->name('commission')->middleware('permission:earn_commission');
});

// Public routes untuk informasi
Route::get('/paket-umroh', function () {
    return Inertia::render('PaketUmroh');
})->name('paket.index');

Route::get('/paket-umroh/{id}', function ($id) {
    return Inertia::render('PaketUmroh/Detail', ['paket_id' => $id]);
})->name('paket.show');

Route::get('/gallery', function () {
    return Inertia::render('Gallery');
})->name('gallery');

Route::get('/tentang', function () {
    return Inertia::render('Tentang');
})->name('tentang');

Route::get('/kontak', function () {
    return Inertia::render('Kontak');
})->name('kontak');

// API routes untuk AJAX calls
Route::prefix('api')->group(function () {
    Route::get('/packages', function () {
        // Return packages data
        return response()->json([]);
    });

    Route::get('/jamaah/{id}/status', function ($id) {
        // Return jamaah status
        return response()->json([]);
    });

    Route::post('/jamaah/{id}/next-step', function ($id) {
        // Advance jamaah to next step
        return response()->json([]);
    });
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Redirect based on user role
    if ($user->hasRole('jamaah')) {
        // Check if jamaah has completed registration
        if (!$user->hasCompletedRegistration()) {
            return redirect()->route('jamaah.daftar');
        }
        return redirect()->route('jamaah.dashboard');
    }

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('marketing')) {
        return redirect()->route('marketing.dashboard');
    }

    if ($user->hasRole('pimpinan')) {
        return redirect()->route('pimpinan.dashboard');
    }

    if ($user->hasRole('cs')) {
        return redirect()->route('cs.dashboard');
    }

    if ($user->hasRole('agent')) {
        return redirect()->route('agent.dashboard');
    }

    // Default dashboard for users without specific roles
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
