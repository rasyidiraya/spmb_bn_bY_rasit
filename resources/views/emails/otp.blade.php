<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP Registrasi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #28a745; margin: 0;">SPMB System</h1>
            <p style="color: #666; margin: 5px 0;">Sistem Penerimaan Mahasiswa Baru</p>
        </div>
        
        <h2 style="color: #333; margin-bottom: 20px;">Halo, {{ $nama }}!</h2>
        
        <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">
            Terima kasih telah mendaftar di sistem SPMB kami. Untuk menyelesaikan proses registrasi, silakan gunakan kode OTP berikut:
        </p>
        
        <div style="text-align: center; margin: 30px 0;">
            <div style="background-color: #f8f9fa; border: 2px dashed #28a745; padding: 20px; border-radius: 10px; display: inline-block;">
                <h1 style="color: #28a745; margin: 0; font-size: 36px; letter-spacing: 5px;">{{ $otp }}</h1>
            </div>
        </div>
        
        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0; color: #856404;">
                <strong>⚠️ Penting:</strong> Kode OTP ini berlaku selama <strong>5 menit</strong> dan hanya dapat digunakan sekali.
            </p>
        </div>
        
        <p style="color: #555; line-height: 1.6;">
            Jika Anda tidak melakukan registrasi ini, silakan abaikan email ini.
        </p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        
        <div style="text-align: center; color: #999; font-size: 12px;">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} SPMB System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>