<?php

namespace App\Http\Controllers;

use App\Models\UmrohPackage;
use App\Models\UmrohSchedule;
use Illuminate\Http\Request;

class UmrohPackageController extends Controller
{
    /**
     * Display a listing of packages for admin
     */
    public function index()
    {
        $packages = UmrohPackage::with(['jamaahProfiles' => function($query) {
            $query->select('id', 'paket_id');
        }])
            ->orderBy('nama_paket')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $packages->map(function ($package) {
                return [
                    'id' => $package->id,
                    'nama_paket' => $package->nama_paket,
                    'harga' => $package->harga,
                    'formatted_price' => $package->formatted_price,
                    'display_name' => $package->display_name,
                    'deskripsi' => $package->deskripsi,
                    'durasi_hari' => $package->durasi_hari,
                    'status' => $package->status,
                    'fasilitas' => $package->fasilitas,
                    'jadwal_keberangkatan' => $package->jadwal_keberangkatan,
                    'available_schedules' => $package->availableSchedules()->get(),
                    'jamaah_count' => $package->jamaahProfiles->count(),
                    'created_at' => $package->created_at,
                    'updated_at' => $package->updated_at
                ];
            })
        ]);
    }

    /**
     * Get package schedules for a specific package (for jamaah registration)
     */
    public function getSchedules($packageId)
    {
        $package = UmrohPackage::find($packageId);

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        }

        // Use new schedule system
        $schedules = $package->availableSchedules()->get();

        return response()->json([
            'success' => true,
            'data' => $schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'tanggal' => $schedule->tanggal_lengkap,
                    'nama_jadwal' => $schedule->nama_jadwal,
                    'kuota' => $schedule->kuota,
                    'booked' => $schedule->terisi,
                    'available' => $schedule->available_slot,
                    'status' => $schedule->status,
                    'formatted_date' => $schedule->formatted_date,
                    'total_price' => $schedule->total_price,
                    'biaya_tambahan' => $schedule->biaya_tambahan
                ];
            })->values()
        ]);
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi_hari' => 'required|integer|min:1',
            'fasilitas' => 'nullable|array',
            'jadwal_keberangkatan' => 'nullable|array',
            'jadwal_keberangkatan.*.tanggal' => 'required|date|after:today',
            'jadwal_keberangkatan.*.kuota' => 'required|integer|min:1',
            'jadwal_keberangkatan.*.status' => 'required|in:tersedia,penuh',
            'status' => 'required|in:aktif,non_aktif',
            // New schedule validation
            'schedules' => 'nullable|array',
            'schedules.*.tahun' => 'required|integer|min:2024|max:2030',
            'schedules.*.bulan' => 'required|integer|min:1|max:12',
            'schedules.*.tanggal_keberangkatan' => 'required|integer|min:1|max:31',
            'schedules.*.kuota' => 'required|integer|min:1|max:100',
            'schedules.*.biaya_tambahan' => 'nullable|numeric|min:0',
            'schedules.*.catatan' => 'nullable|string|max:500'
        ]);

        try {
            \DB::transaction(function () use ($validated, &$package) {
            // Create package
            $packageData = collect($validated)->except('schedules')->toArray();
            $package = UmrohPackage::create($packageData);

            // Create schedules if provided
            if (!empty($validated['schedules'])) {
                foreach ($validated['schedules'] as $scheduleData) {
                    try {
                        // Validate date
                        $tanggal_lengkap = \Carbon\Carbon::create(
                            $scheduleData['tahun'],
                            $scheduleData['bulan'],
                            $scheduleData['tanggal_keberangkatan']
                        );

                        if (!$tanggal_lengkap || $tanggal_lengkap < now()) {
                            throw new \Exception('Invalid or past date: ' .
                                $scheduleData['tahun'] . '-' .
                                $scheduleData['bulan'] . '-' .
                                $scheduleData['tanggal_keberangkatan']
                            );
                        }

                        // Check duplicate schedule
                        $existingSchedule = UmrohSchedule::where('umroh_package_id', $package->id)
                            ->where('tanggal_lengkap', $tanggal_lengkap->format('Y-m-d'))
                            ->first();

                        if ($existingSchedule) {
                            throw new \Exception('Duplicate schedule for date: ' . $tanggal_lengkap->format('Y-m-d'));
                        }

                        // Create schedule
                        UmrohSchedule::create([
                            'umroh_package_id' => $package->id,
                            'tahun' => $scheduleData['tahun'],
                            'bulan' => $scheduleData['bulan'],
                            'tanggal_keberangkatan' => $scheduleData['tanggal_keberangkatan'],
                            'kuota' => $scheduleData['kuota'],
                            'biaya_tambahan' => $scheduleData['biaya_tambahan'] ?? 0,
                            'catatan' => $scheduleData['catatan'] ?? ''
                        ]);

                    } catch (\Exception $e) {
                        throw new \Exception('Error creating schedule: ' . $e->getMessage());
                    }
                }
            }
            });

            return response()->json([
                'success' => true,
                'message' => 'Package and schedules created successfully',
                'data' => $package->load('schedules')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create package: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified package
     */
    public function show(UmrohPackage $umrohPackage)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $umrohPackage->id,
                'nama_paket' => $umrohPackage->nama_paket,
                'harga' => $umrohPackage->harga,
                'formatted_price' => $umrohPackage->formatted_price,
                'display_name' => $umrohPackage->display_name,
                'deskripsi' => $umrohPackage->deskripsi,
                'durasi_hari' => $umrohPackage->durasi_hari,
                'fasilitas' => $umrohPackage->fasilitas,
                'jadwal_keberangkatan' => $umrohPackage->jadwal_keberangkatan,
                'available_schedules' => $umrohPackage->availableSchedules()->get(),
                'status' => $umrohPackage->status
            ]
        ]);
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, UmrohPackage $umrohPackage)
    {
        // Debug logging
        \Log::info("=== DEBUG EDIT PACKAGE REQUEST ===");
        \Log::info("Method: " . $request->method());
        \Log::info("URL: " . $request->url());
        \Log::info("Package ID: " . $umrohPackage->id);
        \Log::info("Request Data: " . json_encode($request->all()));
        \Log::info("====================================");

        try {
            $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi_hari' => 'required|integer|min:1',
            'fasilitas' => 'nullable|array',
            'jadwal_keberangkatan' => 'nullable|array',
            'jadwal_keberangkatan.*.tanggal' => 'required|date',
            'jadwal_keberangkatan.*.kuota' => 'required|integer|min:1',
            'jadwal_keberangkatan.*.status' => 'required|in:tersedia,penuh',
            'status' => 'required|in:aktif,non_aktif',
            // Schedule validation
            'schedules' => 'nullable|array',
            'schedules.*.id' => 'nullable|integer|exists:umroh_schedules,id',
            'schedules.*.tahun' => 'required|integer|min:2024|max:2030',
            'schedules.*.bulan' => 'required|integer|min:1|max:12',
            'schedules.*.tanggal_keberangkatan' => 'required|integer|min:1|max:31',
            'schedules.*.kuota' => 'required|integer|min:1|max:100',
            'schedules.*.biaya_tambahan' => 'nullable|numeric|min:0',
            'schedules.*.catatan' => 'nullable|string|max:500'
            ]);

            \Log::info("Validation passed. Validated data: " . json_encode($validated));
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error("Validation failed: " . json_encode($e->errors()));
            throw $e;
        }

        try {
            \DB::transaction(function () use ($validated, $umrohPackage) {
            // Update package
            $packageData = collect($validated)->except('schedules')->toArray();
            $umrohPackage->update($packageData);

            // Handle schedules update - debug mode
            \Log::info("Checking schedules in validated data...");
            if (isset($validated['schedules']) && is_array($validated['schedules'])) {
                \Log::info("Found schedules: " . count($validated['schedules']) . " items");
                \Log::info("Schedules data: " . json_encode($validated['schedules']));
                $submittedScheduleIds = collect($validated['schedules'])
                    ->filter(fn($schedule) => isset($schedule['id']))
                    ->pluck('id')
                    ->toArray();

                // Delete schedules that are not in the submitted list
                UmrohSchedule::where('umroh_package_id', $umrohPackage->id)
                    ->when(!empty($submittedScheduleIds), function($query) use ($submittedScheduleIds) {
                        $query->whereNotIn('id', $submittedScheduleIds);
                    })
                    ->where('terisi', 0) // Only delete schedules with no bookings
                    ->delete();

                // Process each schedule
                \Log::info("Processing " . count($validated['schedules']) . " schedules...");
                foreach ($validated['schedules'] as $index => $scheduleData) {
                    \Log::info("Processing schedule $index: " . json_encode($scheduleData));
                    try {
                        if (isset($scheduleData['id']) && $scheduleData['id']) {
                            \Log::info("Updating existing schedule ID: " . $scheduleData['id']);
                            // Update existing schedule
                            $schedule = UmrohSchedule::find($scheduleData['id']);
                            if ($schedule && $schedule->umroh_package_id == $umrohPackage->id) {
                                $schedule->update([
                                    'tahun' => $scheduleData['tahun'],
                                    'bulan' => $scheduleData['bulan'],
                                    'tanggal_keberangkatan' => $scheduleData['tanggal_keberangkatan'],
                                    'kuota' => $scheduleData['kuota'],
                                    'biaya_tambahan' => $scheduleData['biaya_tambahan'] ?? 0,
                                    'catatan' => $scheduleData['catatan'] ?? ''
                                ]);
                            }
                        } else {
                            \Log::info("Creating new schedule");
                            // Validate date first
                            try {
                                $tanggal_lengkap = \Carbon\Carbon::create(
                                    $scheduleData['tahun'],
                                    $scheduleData['bulan'],
                                    $scheduleData['tanggal_keberangkatan']
                                );

                                if (!$tanggal_lengkap || $tanggal_lengkap < now()) {
                                    throw new \Exception('Invalid or past date');
                                }
                            } catch (\Exception $e) {
                                throw new \Exception('Invalid date components: ' . $e->getMessage());
                            }

                            // Create new schedule
                            UmrohSchedule::create([
                                'umroh_package_id' => $umrohPackage->id,
                                'tahun' => $scheduleData['tahun'],
                                'bulan' => $scheduleData['bulan'],
                                'tanggal_keberangkatan' => $scheduleData['tanggal_keberangkatan'],
                                'kuota' => $scheduleData['kuota'],
                                'terisi' => 0,
                                'status' => 'tersedia',
                                'biaya_tambahan' => $scheduleData['biaya_tambahan'] ?? 0,
                                'catatan' => $scheduleData['catatan'] ?? ''
                            ]);
                        }
                        \Log::info("Schedule $index processed successfully");

                    } catch (\Exception $e) {
                        \Log::error("Error processing schedule $index: " . $e->getMessage());
                        throw new \Exception('Error processing schedule: ' . $e->getMessage());
                    }
                }
            }
            });

            return response()->json([
                'success' => true,
                'message' => 'Package and schedules updated successfully',
                'data' => $umrohPackage->fresh()->load('schedules')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update package: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified package
     */
    public function destroy(UmrohPackage $umrohPackage)
    {
        // Check if package has jamaah profiles
        if ($umrohPackage->jamaahProfiles()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete package that has registered jamaah'
            ], 400);
        }

        // Check if any schedules have bookings
        $schedulesWithBookings = $umrohPackage->schedules()->where('terisi', '>', 0)->count();
        if ($schedulesWithBookings > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete package with schedules that have bookings'
            ], 400);
        }

        \DB::transaction(function () use ($umrohPackage) {
            // Delete all schedules first
            $umrohPackage->schedules()->delete();

            // Then delete the package
            $umrohPackage->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Package and all schedules deleted successfully'
        ]);
    }

    /**
     * Get packages for dropdown/select options
     */
    public function forDropdown()
    {
        $packages = UmrohPackage::active()
            ->select('id', 'nama_paket', 'harga')
            ->orderBy('nama_paket')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $packages->map(function ($package) {
                return [
                    'value' => $package->id,
                    'label' => $package->display_name
                ];
            })
        ]);
    }
}