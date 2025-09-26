<?php

require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JamaahProfile;
use App\Models\InstallmentPayment;

echo "=== JAMAAH PROFILES ===\n";
$jamaahProfiles = JamaahProfile::with('user')->get();
foreach($jamaahProfiles as $jamaah) {
    echo "ID: {$jamaah->id} | Nama: {$jamaah->nama_lengkap_bin_binti} | Program Talangan: " .
         ($jamaah->program_talangan ? 'Ya' : 'Tidak') .
         " | Email: {$jamaah->user->email} | Step: {$jamaah->current_step}\n";
}

echo "\n=== INSTALLMENT PAYMENTS ===\n";
$installments = InstallmentPayment::with('jamaahProfile.user')->get();
foreach($installments as $installment) {
    $jamaahName = $installment->jamaahProfile ? $installment->jamaahProfile->nama_lengkap_bin_binti : 'N/A';
    echo "ID: {$installment->id} | Jamaah ID: {$installment->jamaah_profile_id} | " .
         "Jamaah: {$jamaahName} | Cicilan: {$installment->installment_number} | " .
         "Status: {$installment->status} | Amount: " . number_format($installment->amount) . "\n";
}

echo "\n=== JAMAAH DENGAN PROGRAM TALANGAN ===\n";
$jamaahTalangan = JamaahProfile::where('program_talangan', true)->with('installmentPayments')->get();
foreach($jamaahTalangan as $jamaah) {
    echo "Jamaah: {$jamaah->nama_lengkap_bin_binti} | " .
         "Installments: {$jamaah->installmentPayments->count()} | " .
         "Keberangkatan: {$jamaah->rencana_keberangkatan}\n";
}

echo "\n=== JAMAAH TANPA INSTALLMENTS ===\n";
$jamaahTanpaInstallments = JamaahProfile::where('program_talangan', true)
    ->whereDoesntHave('installmentPayments')->get();
foreach($jamaahTanpaInstallments as $jamaah) {
    echo "Jamaah: {$jamaah->nama_lengkap_bin_binti} | " .
         "Program Talangan: Ya | " .
         "Keberangkatan: {$jamaah->rencana_keberangkatan} | " .
         "Step: {$jamaah->current_step}\n";
}