@component('mail::message')
# ¡Hola!

Has recibido este correo porque hemos recibido una solicitud para restablecer la contraseña de tu cuenta.

@component('mail::button', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent

Este enlace de restablecimiento de contraseña expirará en {{ $expireMinutes }} minutos.

Si no solicitaste restablecer tu contraseña, puedes ignorar este correo.

Saludos,<br>
{{ $appName }}

@component('mail::subcopy')
Si tienes problemas haciendo clic en el botón "{{ $actionText }}", copia y pega la siguiente URL en tu navegador web:
[{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endcomponent
