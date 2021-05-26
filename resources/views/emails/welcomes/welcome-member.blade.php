@component('mail::message')
Welcome {{ $name }} !

@component('mail::button', ['url' => 'http://smartii-app.test/login'])
Login
@endcomponent

@component('mail::panel')
This is your password: {{ $password }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
