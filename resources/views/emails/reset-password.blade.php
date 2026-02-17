<!DOCTYPE html>
<html>
<head>
    <style>
        /* Email styling */
    </style>
</head>
<body>
    <h2>Reset Password - Niat Zakat</h2>
    <p>Halo {{ $nama }},</p>
    <p>Kami menerima permintaan untuk reset password akun Anda.</p>
    <p>Klik tombol di bawah untuk membuat password baru:</p>
    <a href="{{ $resetUrl }}" style="background: #2d6936; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; display: inline-block;">
        Reset Password
    </a>
    <p>Link ini akan kedaluwarsa dalam {{ $expiresInMinutes }} menit.</p>
    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
</body>
</html>