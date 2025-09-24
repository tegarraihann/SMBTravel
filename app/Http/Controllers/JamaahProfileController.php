<?php

namespace App\Http\Controllers;

use App\Models\JamaahProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class JamaahProfileController extends Controller
{
    public function showDocuments(): Response
    {
        $profile = Auth::user()->jamaahProfile;

        if (!$profile) {
            return redirect()->route('jamaah.dashboard')->with('error', 'Profile tidak ditemukan');
        }

        return Inertia::render('Jamaah/Dokumen', [
            'jamaahData' => [
                'id' => $profile->id,
                'nama_lengkap' => $profile->nama_lengkap,
                'current_step' => $profile->getCurrentStep(),
                'step_name' => $profile->getStepName(),
                'can_upload_documents' => $profile->canUploadDocuments(),
                'documents' => $profile->getUploadedDocuments(),
                'documents_uploaded_at' => $profile->documents_uploaded_at,
                'documents_verified' => $profile->documents_verified,
                'required_documents' => $profile->getRequiredDocuments(),
                'optional_documents' => $profile->getOptionalDocuments()
            ]
        ]);
    }

    public function uploadDocument(Request $request)
    {
        $profile = Auth::user()->jamaahProfile;

        if (!$profile || !$profile->canUploadDocuments()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk upload dokumen');
        }

        $documentType = $request->input('document_type');
        $allowedTypes = array_keys(array_merge(
            $profile->getRequiredDocuments(),
            $profile->getOptionalDocuments()
        ));

        if (!in_array($documentType, $allowedTypes)) {
            return redirect()->back()->with('error', 'Tipe dokumen tidak valid');
        }

        $request->validate([
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        try {
            // Delete old file if exists
            if ($profile->$documentType) {
                Storage::disk('public')->delete($profile->$documentType);
            }

            // Store new file
            $file = $request->file('document_file');
            $filename = time() . '_' . $documentType . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/' . Auth::id(), $filename, 'public');

            // Update profile
            $profile->$documentType = $path;
            $profile->documents_uploaded_at = now();
            $profile->save();

            // Check if all required documents are uploaded
            if ($profile->hasAllRequiredDocuments() && $profile->current_step < 5) {
                // Auto advance to next step if all required documents uploaded
                $profile->advanceToNextStep();
            }

            return redirect()->back()->with('success', 'Dokumen berhasil diupload');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload dokumen: ' . $e->getMessage());
        }
    }

    public function deleteDocument(Request $request)
    {
        $profile = Auth::user()->jamaahProfile;

        if (!$profile || !$profile->canUploadDocuments()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus dokumen');
        }

        $documentType = $request->input('document_type');
        $allowedTypes = array_keys(array_merge(
            $profile->getRequiredDocuments(),
            $profile->getOptionalDocuments()
        ));

        if (!in_array($documentType, $allowedTypes)) {
            return redirect()->back()->with('error', 'Tipe dokumen tidak valid');
        }

        try {
            // Delete file
            if ($profile->$documentType) {
                Storage::disk('public')->delete($profile->$documentType);
                $profile->$documentType = null;
                $profile->save();
            }

            return redirect()->back()->with('success', 'Dokumen berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function downloadDocument(Request $request, $documentType)
    {
        $profile = Auth::user()->jamaahProfile;

        if (!$profile || !$profile->$documentType) {
            abort(404);
        }

        $path = storage_path('app/public/' . $profile->$documentType);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function showPayment(): Response
    {
        $profile = Auth::user()->jamaahProfile;

        if (!$profile) {
            return redirect()->route('jamaah.dashboard')->with('error', 'Profile tidak ditemukan');
        }

        return Inertia::render('Jamaah/Pembayaran', [
            'jamaahData' => [
                'id' => $profile->id,
                'nama_lengkap' => $profile->nama_lengkap,
                'email' => Auth::user()->email,
                'current_step' => $profile->getCurrentStep(),
                'paket_id' => $profile->paket_id,
                'paket_name' => 'Paket Umroh Premium', // TODO: get from packages table
                'dp_paid' => $profile->dp_paid,
                'bukti_transfer' => $profile->bukti_transfer,
                'status' => $profile->status,
                'created_at' => $profile->created_at,
                'can_upload_payment' => $profile->current_step >= 2
            ]
        ]);
    }

    public function uploadPaymentProof(Request $request)
    {
        $profile = Auth::user()->jamaahProfile;

        if (!$profile || $profile->current_step < 2) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk upload bukti pembayaran');
        }

        $request->validate([
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        try {
            // Delete old file if exists
            if ($profile->bukti_transfer) {
                Storage::disk('public')->delete($profile->bukti_transfer);
            }

            // Store new file
            $file = $request->file('bukti_transfer');
            $filename = time() . '_bukti_transfer_' . $file->getClientOriginalName();
            $path = $file->storeAs('payments/' . Auth::id(), $filename, 'public');

            // Update profile
            $profile->bukti_transfer = $path;
            $profile->save();

            // Auto advance to next step
            $canAdvance = $profile->canAdvanceToNextStep();
            \Log::info('Payment upload - Can advance to next step: ' . ($canAdvance ? 'Yes' : 'No'));
            \Log::info('Payment upload - Current step before advancement: ' . $profile->current_step);
            \Log::info('Payment upload - Bukti transfer set: ' . ($profile->bukti_transfer ? 'Yes' : 'No'));

            if ($canAdvance) {
                $advanced = $profile->advanceToNextStep();
                \Log::info('Payment upload - Step advancement result: ' . ($advanced ? 'Success' : 'Failed'));
            }

            // Check if step advanced
            $profile->refresh(); // Refresh to get updated current_step
            \Log::info('Payment upload - Current step after advancement: ' . $profile->current_step);

            if ($profile->current_step >= 4) {
                return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload! Anda dapat melanjutkan ke tahap upload dokumen.');
            } else {
                return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload bukti pembayaran: ' . $e->getMessage());
        }
    }

    public function completeDocumentUpload(Request $request)
    {
        $profile = Auth::user()->jamaahProfile;

        if (!$profile || !$profile->canUploadDocuments()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyelesaikan upload dokumen');
        }

        // Check if all required documents are uploaded
        if (!$profile->hasAllRequiredDocuments()) {
            return redirect()->back()->with('error', 'Harap upload semua dokumen wajib terlebih dahulu');
        }

        try {
            // Mark documents as uploaded and auto-verify
            $profile->documents_uploaded_at = now();
            $profile->documents_verified = true; // Auto-verify documents
            $profile->save();

            // Auto advance to next step (from step 4 to step 5)
            if ($profile->canAdvanceToNextStep()) {
                $profile->advanceToNextStep();
            }

            return redirect()->route('jamaah.dashboard')->with('success', 'Semua dokumen berhasil diupload dan diverifikasi! Anda dapat melanjutkan ke tahap Manasik & Keberangkatan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyelesaikan upload dokumen: ' . $e->getMessage());
        }
    }
}