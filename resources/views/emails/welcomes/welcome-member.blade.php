@component('mail::message')
Welcome {{ $name }} !

@component('mail::button', ['url' => url('/login')])
Login
@endcomponent

@component('mail::panel')
This is your password: {{ $password }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
