@component('mail::message')
Welcome {{ $name }} !

@component('mail::button', ['url' => url('/login')])
Login
@endcomponent

@component('mail::panel')
This is your password: {{ $password }}
@endcomponent

Thanks,<br>
St. Mark Arts and Training Institute Inc.
@endcomponent
