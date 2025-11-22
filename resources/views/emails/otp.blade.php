<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode Verifikasi RupaNusa</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f6f6f6; padding: 30px;">

    <div style="
        max-width: 520px;
        margin: auto;
        background: #ffffff;
        border-radius: 12px;
        padding: 10px 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1px solid #ececec;
    ">

        <!-- Header / Logo -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="https://via.placeholder.com/80x80?text=RN" alt="RupaNusa" style="width: 80px; height: 80px;">
            <h2 style="color: #ff6a00; margin-top: 10px; font-size: 22px;">
                Verifikasi Akun RupaNusa
            </h2>
        </div>

        <!-- Greeting -->
        <p style="font-size: 15px; color: #444; margin-bottom: 8px;">
            Halo,
        </p>

        <p style="font-size: 15px; color: #444; line-height: 1.6;">
            Berikut adalah kode verifikasi untuk akun RupaNusa Anda.
            Jangan bagikan kode ini kepada siapa pun demi keamanan akun Anda.
        </p>

        <!-- OTP Box -->
        <div style="
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #fff7ec;
            border-radius: 10px;
            border: 1px dashed #ff9f4a;
        ">
            <div style="font-size: 34px; font-weight: bold; letter-spacing: 8px; color: #ff6a00;">
                {{ $otp }}
            </div>
            <p style="margin-top: 8px; font-size: 14px; color: #777;">
                Berlaku selama 10 menit
            </p>
        </div>

        <!-- Footer Text -->
        <p style="font-size: 14px; color: #555; line-height: 1.6;">
            Jika Anda tidak meminta kode ini, Anda dapat mengabaikan pesan ini. 
            Tidak ada tindakan lain yang diperlukan.
        </p>

        <p style="margin-top: 25px; font-size: 13px; color: #999; text-align: center;">
            © {{ date('Y') }} RupaNusa • Marketplace Budaya Nusantara
        </p>
    </div>
</body>
</html>
