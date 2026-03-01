{{-- resources/views/emails/kontak-balasan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan Pesan - {{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; color: #1f2937; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
        .card { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 32px 32px 28px; text-align: center; }
        .header-icon { width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .header h1 { color: #fff; font-size: 22px; font-weight: 700; }
        .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin-top: 4px; }
        .body { padding: 28px 32px; }
        .greeting { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 8px; }
        .info-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 20px 0; }
        .info-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 4px; }
        .info-value { font-size: 14px; color: #374151; font-weight: 500; }
        .pesan-original { background: #f3f4f6; border-left: 4px solid #d1d5db; border-radius: 0 8px 8px 0; padding: 14px 16px; margin: 20px 0; }
        .pesan-original-label { font-size: 11px; color: #9ca3af; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; }
        .pesan-original-text { font-size: 13px; color: #4b5563; line-height: 1.6; }
        .balasan-box { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .balasan-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #065f46; margin-bottom: 10px; }
        .balasan-text { font-size: 14px; color: #064e3b; line-height: 1.7; white-space: pre-wrap; }
        .footer-note { font-size: 13px; color: #6b7280; line-height: 1.6; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .footer { padding: 20px 32px; background: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            {{-- Header --}}
            <div class="header">
                <div>
                    <div class="header-icon" style="display:inline-block; line-height:56px;">
                        <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <h1>Balasan Pesan Anda</h1>
                <p>{{ config('app.name') }}</p>
            </div>

            {{-- Body --}}
            <div class="body">
                <p class="greeting">Halo, {{ $kontak->nama }}!</p>
                <p style="font-size:14px; color:#4b5563; margin-top:8px; line-height:1.6;">
                    Terima kasih telah menghubungi kami. Kami telah membaca pesan Anda dan berikut adalah balasan dari tim kami:
                </p>

                {{-- Subjek --}}
                <div class="info-box">
                    <p class="info-label">Subjek</p>
                    <p class="info-value">{{ $kontak->subjek }}</p>
                </div>

                {{-- Pesan Original --}}
                <div class="pesan-original">
                    <p class="pesan-original-label">Pesan Anda</p>
                    <p class="pesan-original-text">{{ $kontak->pesan }}</p>
                </div>

                {{-- Balasan --}}
                <div class="balasan-box">
                    <p class="balasan-label">✉️ Balasan dari Tim Kami</p>
                    <p class="balasan-text">{{ $balasan }}</p>
                </div>

                <hr class="divider">

                <p class="footer-note">
                    Jika Anda memiliki pertanyaan lanjutan, silakan kunjungi halaman kontak kami atau balas email ini.
                    Kami senang dapat membantu Anda.
                </p>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p>
                    Email ini dikirim secara otomatis oleh sistem <strong>{{ config('app.name') }}</strong>.<br>
                    Dikirim pada {{ now()->translatedFormat('d F Y, H:i') }} WIB.
                </p>
                <p style="margin-top:8px;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>