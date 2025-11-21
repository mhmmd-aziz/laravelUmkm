<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pesan Baru - RupaNusa</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f7;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 100%;
            padding: 40px 0;
            background: #f4f4f7;
        }

        .content {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .header {
            background: #ff7a00;
            padding: 30px 40px;
            text-align: center;
            color: white;
        }

        .logo {
            width: 70px;
            margin-bottom: 15px;
        }

        .header-title {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .body {
            padding: 35px 40px;
            color: #333;
        }

        .body h2 {
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 700;
            color: #222;
        }

        .info-card {
            background: #fafafa;
            border: 1px solid #e6e6e6;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .label {
            font-weight: 700;
            color: #444;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            padding: 25px;
            background: #f9f9f9;
            color: #777;
            border-top: 1px solid #eee;
        }

        .footer a {
            color: #ff7a00;
            text-decoration: none;
        }

        .message-box {
            white-space: pre-line;
            background: #fff7ef;
            border-left: 4px solid #ff7a00;
            padding: 15px 18px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="content">

            <!-- Header -->
            <div class="header">
                <!-- <img src="{{ asset('images/logo.png') }}" alt="RupaNusa Logo" class="logo"> -->
                <h1 class="header-title">Pesan Baru dari Pengunjung</h1>
            </div>

            <!-- Body -->
            <div class="body">
                <h2>Ada pesan baru dari Form Kontak RupaNusa!</h2>

                <div class="info-card">
                    <p><span class="label">Nama:</span> {{ $nama }}</p>
                    <p><span class="label">Email:</span> {{ $email }}</p>
                </div>

                <h3 style="margin-bottom:10px; font-size:16px; font-weight:700;">Isi Pesan:</h3>

                <div class="message-box">
                    {{ $pesan }}
                </div>

                <p style="font-size:14px; color:#555;">
                    Email ini dikirim otomatis oleh sistem RupaNusa.  
                    Jika Anda ingin merespons pengirim, cukup reply email ini.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                © {{ date('Y') }} RupaNusa — Platform E-Commerce Budaya Nusantara<br>
                <a href="https://rupanusa.id">www.rupanusa.id</a>
            </div>

        </div>
    </div>

</body>
</html>
