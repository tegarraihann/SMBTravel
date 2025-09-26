<?php

namespace App\Http\Controllers;

use App\Models\UmrohSchedule;
use App\Models\UmrohPackage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UmrohScheduleController extends Controller
{
    /**
     * Get schedules for a specific package
     */
    public function getPackageSchedules(UmrohPackage $package)
    {
        $schedules = UmrohSchedule::where('umroh_package_id', $package->id)
            ->with('jamaahProfiles:id,umroh_schedule_id')
            ->orderBy('tanggal_lengkap')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'nama_jadwal' => $schedule->nama_jadwal,
                    'tahun' => $schedule->tahun,
                    'bulan' => $schedule->bulan,
                    'tanggal_keberangkatan' => $schedule->tanggal_keberangkatan,
                    'tanggal_lengkap' => $schedule->tanggal_lengkap,
                    'kuota' => $schedule->kuota,
                    'terisi' => $schedule->terisi,
                    'available_slot' => $schedule->available_slot,
                    'status' => $schedule->status,
                    'catatan' => $schedule->catatan,
                    'biaya_tambahan' => $schedule->biaya_tambahan,
                    'total_price' => $schedule->total_price,
                    'formatted_date' => $schedule->formatted_date,
                    'month_year' => $schedule->month_year,
                    'is_available' => $schedule->isAvailable(),
                    'is_past' => $schedule->isPast(),
                    'created_at' => $schedule->created_at,
                    'updated_at' => $schedule->updated_at
                ];
            })
        ]);
    }

    /**
     * Store a new schedule for a package
     */
    public function store(Request $request, UmrohPackage $package)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2024|max:2030',
            'bulan' => 'required|integer|min:1|max:12',
            'tanggal_keberangkatan' => 'required|integer|min:1|max:31',
            'kuota' => 'required|integer|min:1|max:100',
            'biaya_tambahan' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:500'
        ]);

        try {
            // Validate date
            $tanggal_lengkap = Carbon::create(
                $validated['tahun'],
                $validated['bulan'],
                $validated['tanggal_keberangkatan']
            );

            if (!$tanggal_lengkap || $tanggal_lengkap < now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal keberangkatan tidak valid atau sudah lewat'
                ], 400);
            }

            // Check duplicate schedule
            $existingSchedule = UmrohSchedule::where('umroh_package_id', $package->id)
                ->where('tanggal_lengkap', $tanggal_lengkap->format('Y-m-d'))
                ->first();

            if ($existingSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal untuk tanggal tersebut sudah ada'
                ], 400);
            }

            $schedule = UmrohSchedule::create([
                'umroh_package_id' => $package->id,
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan'],
                'tanggal_keberangkatan' => $validated['tanggal_keberangkatan'],
                'kuota' => $validated['kuota'],
                'biaya_tambahan' => $validated['biaya_tambahan'] ?? 0,
                'catatan' => $validated['catatan']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil ditambahkan',
                'data' => [
                    'id' => $schedule->id,
                    'nama_jadwal' => $schedule->nama_jadwal,
                    'tahun' => $schedule->tahun,
                    'bulan' => $schedule->bulan,
                    'tanggal_keberangkatan' => $schedule->tanggal_keberangkatan,
                    'tanggal_lengkap' => $schedule->tanggal_lengkap,
                    'kuota' => $schedule->kuota,
                    'terisi' => $schedule->terisi,
                    'available_slot' => $schedule->available_slot,
                    'status' => $schedule->status,
                    'catatan' => $schedule->catatan,
                    'biaya_tambahan' => $schedule->biaya_tambahan,
                    'total_price' => $schedule->total_price,
                    'formatted_date' => $schedule->formatted_date,
                    'is_available' => $schedule->isAvailable()
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update schedule
     */
    public function update(Request $request, UmrohSchedule $schedule)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2024|max:2030',
            'bulan' => 'required|integer|min:1|max:12',
            'tanggal_keberangkatan' => 'required|integer|min:1|max:31',
            'kuota' => 'required|integer|min:' . $schedule->terisi . '|max:100',
            'biaya_tambahan' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
            'status' => 'required|in:tersedia,penuh,ditutup'
        ]);

        try {
            // Validate date
            $tanggal_lengkap = Carbon::create(
                $validated['tahun'],
                $validated['bulan'],
                $validated['tanggal_keberangkatan']
            );

            if (!$tanggal_lengkap) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal keberangkatan tidak valid'
                ], 400);
            }

            // Check duplicate schedule (excluding current)
            $existingSchedule = UmrohSchedule::where('umroh_package_id', $schedule->umroh_package_id)
                ->where('tanggal_lengkap', $tanggal_lengkap->format('Y-m-d'))
                ->where('id', '!=', $schedule->id)
                ->first();

            if ($existingSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal untuk tanggal tersebut sudah ada'
                ], 400);
            }

            $schedule->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diupdate',
                'data' => $schedule->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete schedule
     */
    public function destroy(UmrohSchedule $schedule)
    {
        if ($schedule->terisi > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus jadwal yang sudah memiliki jamaah terdaftar'
            ], 400);
        }

        try {
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available schedules for jamaah registration
     */
    public function getAvailableSchedules(UmrohPackage $package)
    {
        $schedules = UmrohSchedule::where('umroh_package_id', $package->id)
            ->available()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'nama_jadwal' => $schedule->nama_jadwal,
                    'tanggal_lengkap' => $schedule->tanggal_lengkap,
                    'formatted_date' => $schedule->formatted_date,
                    'month_year' => $schedule->month_year,
                    'available_slot' => $schedule->available_slot,
                    'total_price' => $schedule->total_price,
                    'biaya_tambahan' => $schedule->biaya_tambahan,
                    'catatan' => $schedule->catatan
                ];
            })
        ]);
    }
}