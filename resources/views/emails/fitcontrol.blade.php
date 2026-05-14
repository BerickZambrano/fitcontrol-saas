<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background-color:#1a56db; padding: 30px 40px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:24px; letter-spacing:1px;">FitControl</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px;">
                            <p style="font-size:18px; color:#333333; margin-bottom:8px;">
                                Hola, <strong>{{ $nombre }}</strong> 👋
                            </p>
                            <hr style="border:none; border-top:1px solid #eeeeee; margin: 20px 0;">
                            <p style="font-size:15px; color:#555555; line-height:1.7;">
                                {{ nl2br($body) }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f9f9f9; padding: 20px 40px; text-align:center; border-top:1px solid #eeeeee;">
                            <p style="font-size:12px; color:#999999; margin:0;">
                                &copy; 2026 FitControl &middot; Este correo fue enviado automaticamente.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
