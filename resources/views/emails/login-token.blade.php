@component('mail::message')
# Link de acceso para {{ $user->name }}

Da click en el siguiente botón para acceder a la aplicación.

@component('mail::button', ['url' => $link])
Acceder
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
