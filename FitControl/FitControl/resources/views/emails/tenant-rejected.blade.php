<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial; background:#f4f6f9; padding:20px;">

<div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:10px;">

    <h2 style="color:#dc2626;">❌ Solicitud no aprobada</h2>

    <p>Hola <strong>{{ $tenant->encargado_nombre }}</strong>,</p>

    <p>Lamentablemente tu solicitud no fue aprobada en este momento.</p>

    <p>Si crees que se trata de un error puedes volver a intentarlo.</p>

    <p style="font-size:12px; color:gray;">
        © {{ date('Y') }} FitControl SaaS
    </p>

</div>

</body>
</html>