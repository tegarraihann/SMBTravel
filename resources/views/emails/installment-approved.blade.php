<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Cicilan Disetujui</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            margin: -20px -20px 30px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .status-badge {
            background-color: #10B981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }
        .info-card {
            background-color: #f8f9fa;
            border-left: 4px solid #10B981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .info-value {
            color: #212529;
            font-weight: 600;
        }
        .next-steps {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .next-steps h3 {
            color: #1976d2;
            margin-top: 0;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
            color: #424242;
        }
        .cta-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            display: inline-block;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .contact-info {
            background-color: #fff8e1;
            border: 1px solid #ffcc02;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-label, .info-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Pembayaran Cicilan Disetujui!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Travel Umroh SMB</p>
        </div>

        <div style="padding: 0 20px;">
            <p>Assalamualaikum <strong>{{ $jamaah->nama_lengkap_bin_binti }}</strong>,</p>

            <div class="status-badge">
                ✅ PEMBAYARAN DISETUJUI
            </div>

            <p>Alhamdulillah, kami dengan senang hati memberitahukan bahwa pembayaran cicilan Anda telah <strong>disetujui</strong> oleh tim kami.</p>

            <div class="info-card">
                <h3 style="margin-top: 0; color: #10B981;">📋 Detail Pembayaran</h3>
                <div class="info-row">
                    <span class="info-label">Cicilan ke:</span>
                    <span class="info-value">{{ $installment->installment_number }} dari 5</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jumlah Dibayar:</span>
                    <span class="info-value">Rp {{ number_format($installment->amount, 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Jatuh Tempo:</span>
                    <span class="info-value">{{ $installment->due_date->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Disetujui:</span>
                    <span class="info-value">{{ $installment->approved_at->format('d F Y, H:i') }} WIB</span>
                </div>
                @if($installment->notes)
                <div class="info-row">
                    <span class="info-label">Catatan:</span>
                    <span class="info-value">{{ $installment->notes }}</span>
                </div>
                @endif
            </div>

            @php
                $remainingInstallments = 5 - $installment->installment_number;
                $totalPaid = $installment->installment_number;
                $progressPercentage = ($totalPaid / 5) * 100;
            @endphp

            <div class="next-steps">
                <h3>📊 Status Progress Pembayaran</h3>
                <div style="background-color: #e9ecef; border-radius: 10px; height: 20px; margin: 15px 0;">
                    <div style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); height: 20px; border-radius: 10px; width: {{ $progressPercentage }}%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">
                        {{ number_format($progressPercentage, 0) }}%
                    </div>
                </div>
                <p style="text-align: center; margin: 5px 0; color: #6b7280;">
                    <strong>{{ $totalPaid }} dari 5 cicilan</strong> telah dibayar
                </p>
            </div>

            @if($remainingInstallments > 0)
            <div class="next-steps">
                <h3>🔄 Langkah Selanjutnya</h3>
                <ul>
                    <li>Sisa <strong>{{ $remainingInstallments }} cicilan</strong> lagi untuk melunasi program talangan</li>
                    <li>Pantau jadwal pembayaran cicilan selanjutnya di dashboard Anda</li>
                    <li>Siapkan pembayaran untuk cicilan berikutnya sebelum jatuh tempo</li>
                    <li>Hubungi CS kami jika ada pertanyaan mengenai pembayaran</li>
                </ul>
            </div>
            @else
            <div class="next-steps" style="background-color: #f0fff4; border-color: #10B981;">
                <h3 style="color: #10B981;">🎊 Selamat! Pembayaran Cicilan Telah Lunas</h3>
                <p>Alhamdulillah, Anda telah menyelesaikan seluruh pembayaran cicilan program talangan. Tim kami akan segera memproses persiapan keberangkatan Anda.</p>
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ url('/jamaah/dashboard') }}" class="cta-button">
                    🏠 Lihat Dashboard Saya
                </a>
            </div>

            <div class="contact-info">
                <h4 style="margin-top: 0; color: #f57c00;">📞 Butuh Bantuan?</h4>
                <p style="margin: 5px 0;">Customer Service Travel Umroh SMB</p>
                <p style="margin: 5px 0;"><strong>WhatsApp:</strong> +62 812-3456-7890</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> cs@travelumrohsmb.com</p>
            </div>

            <p>Barakallahu fiikum, semoga perjalanan ibadah Anda dimudahkan oleh Allah SWT.</p>

            <p style="margin-bottom: 0;">Wassalamualaikum warahmatullahi wabarakatuh,<br>
            <strong>Tim Travel Umroh SMB</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Travel Umroh SMB. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>