<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
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
        'nama_lengkap' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'no_ktp' => 'required|string|size:16',
        'pekerjaan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'email' => 'required|email',
        'no_telepon' => 'required|string',
        'no_darurat' => 'nullable|string',
        'hubungan_darurat' => 'nullable|string',
        'paket_id' => 'required|integer',
        'catatan' => 'nullable|string',
        'setuju' => 'required|accepted'
    ]);

    // Create jamaah profile
    $jamaahProfile = JamaahProfile::create([
        'user_id' => $user->id,
        'nama_lengkap' => $validated['nama_lengkap'],
        'jenis_kelamin' => $validated['jenis_kelamin'],
        'tempat_lahir' => $validated['tempat_lahir'],
        'tanggal_lahir' => $validated['tanggal_lahir'],
        'no_ktp' => $validated['no_ktp'],
        'pekerjaan' => $validated['pekerjaan'],
        'alamat' => $validated['alamat'],
        'no_telepon' => $validated['no_telepon'],
        'no_darurat' => $validated['no_darurat'],
        'hubungan_darurat' => $validated['hubungan_darurat'],
        'paket_id' => $validated['paket_id'],
        'catatan' => $validated['catatan'],
        'current_step' => 2, // Move to payment step
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
        'nama_lengkap' => $jamaahProfile->nama_lengkap,
        'email' => $user->email,
        'current_step' => $jamaahProfile->getCurrentStep(),
        'paket_id' => $jamaahProfile->paket_id,
        'status' => $jamaahProfile->status,
        'dp_paid' => $jamaahProfile->dp_paid,
        'created_at' => $jamaahProfile->created_at
    ];

    return Inertia::render('Jamaah/Dashboard', [
        'jamaah' => $jamaahData
    ]);
})->middleware(['auth', 'role:jamaah'])->name('jamaah.dashboard');

// Jamaah routes group - requires jamaah role
Route::prefix('jamaah')->name('jamaah.')->middleware(['auth', 'role:jamaah'])->group(function () {
    // Pembayaran routes
    Route::get('/pembayaran', function () {
        return Inertia::render('Jamaah/Pembayaran');
    })->name('pembayaran')->middleware('permission:make_payments');

    Route::post('/pembayaran/upload', function () {
        // Handle payment proof upload
        return response()->json(['message' => 'Bukti pembayaran berhasil diupload']);
    })->name('pembayaran.upload')->middleware('permission:make_payments');

    // Dokumen routes
    Route::get('/dokumen', function () {
        return Inertia::render('Jamaah/Dokumen');
    })->name('dokumen')->middleware('permission:upload_documents');

    Route::post('/dokumen/upload', function () {
        // Handle document upload
        return response()->json(['message' => 'Dokumen berhasil diupload']);
    })->name('dokumen.upload')->middleware('permission:upload_documents');

    // Manasik routes
    Route::get('/manasik', function () {
        return Inertia::render('Jamaah/Manasik');
    })->name('manasik')->middleware('permission:view_own_bookings');

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
        return Inertia::render('CS/Dashboard');
    })->name('dashboard');

    Route::get('/jamaah', function () {
        return Inertia::render('CS/Jamaah/Index');
    })->name('jamaah.index')->middleware('permission:view_jamaah_list');

    Route::get('/inquiries', function () {
        return Inertia::render('CS/Inquiries/Index');
    })->name('inquiries.index')->middleware('permission:respond_to_inquiries');

    Route::get('/complaints', function () {
        return Inertia::render('CS/Complaints/Index');
    })->name('complaints.index')->middleware('permission:manage_complaints');
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
