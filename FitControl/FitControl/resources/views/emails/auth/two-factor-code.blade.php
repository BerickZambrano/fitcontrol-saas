<x-mail::message>
# Hola,

Has solicitado iniciar sesión en **FitControl**. Por seguridad, necesitamos verificar tu identidad.

Tu código de acceso es:

<x-mail::panel>
# {{ $code }}
</x-mail::panel>

Este código expirará en 15 minutos. Si no has solicitado este código, por favor ignora este correo o contacta con soporte.

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
