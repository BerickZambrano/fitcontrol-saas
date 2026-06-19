<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Arial, sans-serif; color: #1f2937;">

    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%); padding: 40px 32px; text-align: center;">
                            <h1 style="margin: 0; font-size: 32px; font-weight: 900; color: #ffffff; text-transform: uppercase; letter-spacing: -0.5px; font-style: italic;">
                                FitControl
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 32px;">
                            <h2 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800; color: #1f2937; text-transform: uppercase; letter-spacing: -0.3px;">
                                Solicitud no aprobada
                            </h2>

                            <p style="margin: 0 0 24px 0; font-size: 15px; color: #6b7280; line-height: 1.6;">
                                Hola <strong style="color: #1f2937;">{{ $tenant->encargado_nombre }}</strong>,
                            </p>

                            <!-- Club info card -->
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 4px 0; font-size: 11px; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: 1px;">
                                            Tu club
                                        </p>
                                        <p style="margin: 0; font-size: 18px; font-weight: 800; color: #991b1b;">
                                            {{ $tenant->nombre }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 24px 0; font-size: 15px; color: #4b5563; line-height: 1.6;">
                                Lamentablemente, tu solicitud no fue aprobada en esta ocasión.
                                {{ !empty($tenant->rejection_reason) ? 'Motivo: ' . $tenant->rejection_reason : '' }}
                            </p>


                            <!-- Info note -->
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <p style="margin: 0; font-size: 13px; color: #6b7280; line-height: 1.5;">
                                            💡 Si crees que se trata de un error, puedes contactarnos o corregir la información y volver a intentarlo.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 24px 32px; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 800; color: #1e3a8a; text-transform: uppercase; letter-spacing: -0.3px; font-style: italic;">
                                FitControl
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.5;">
                                Plataforma líder en gestión deportiva<br>
                                &copy; {{ date('Y') }} Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
