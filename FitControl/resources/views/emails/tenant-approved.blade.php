<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; padding: 20px;">

    <h2>¡Felicitaciones, {{ $tenant->encargado_nombre }}!</h2>

    <p>La solicitud de registro del club <strong>{{ $tenant->nombre }}</strong> ha sido <span style="color: green;"><strong>aprobada</strong></span>.</p>

    <p>Para acceder al sistema, primero debes crear tu cuenta de administrador haciendo clic en el siguiente enlace:</p>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}"
           style="background-color: #16a34a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-size: 16px;">
            Crear mi cuenta
        </a>
    </p>

    <p style="color: #888; font-size: 13px;">Este enlace es de un solo uso. Una vez que crees tu cuenta, dejará de funcionar.</p>

    <br>
    <p>Saludos,<br><strong>El equipo de FitControl</strong></p>

</body>
</html>