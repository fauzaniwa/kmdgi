<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; color: #333333;">

    <!-- Wrapper utama menggunakan table untuk kompabilitas email klien -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f7f6; padding: 40px 0; width: 100%;">
        <tr>
            <td align="center">

                <!-- Kontainer Konten -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">

                    <!-- Header Logo -->
                    <tr>
                        <td style="background-color: #ffffff; padding: 30px; text-align: center; border-bottom: 2px solid #f1f5f9;">
                            <img src="https://kmdgi.id/images/logo-desktop.png" alt="Logo KMDGI" style="height: 50px; width: auto; display: inline-block;">
                        </td>
                    </tr>

                    <!-- Body Email -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="font-size: 20px; font-weight: 600; color: #0f172a; margin-bottom: 20px;">
                                Halo, {{ $user->name }}
                            </div>

                            <div style="font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 25px;">
                                {{ $messageContent }}
                            </div>

                            @if(!empty($url))
                            <div style="text-align: center; margin: 35px 0 10px 0;">
                                <a href="{{ $url }}" style="display: inline-block; background-color: #126CFD; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: 600; font-size: 15px;">
                                    Lihat Detail Disini
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 25px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="font-size: 13px; color: #94a3b8; margin: 5px 0;">
                                &copy; {{ date('Y') }} KMDGI. Semua hak cipta dilindungi.
                            </p>
                            <p style="font-size: 13px; color: #94a3b8; margin: 5px 0;">
                                Email ini dikirimkan secara otomatis dari sistem manajemen akun dan aktivitas KMDGI.
                            </p>
                            <p style="color: #ef4444; font-weight: 600; font-size: 12px; margin-top: 15px; margin-bottom: 0;">
                                PERHATIAN: Mohon tidak membalas email ini (Do Not Reply) karena alamat email ini tidak dipantau oleh tim dukungan kami.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>