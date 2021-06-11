@component('mail::message')
Greetings, {{ $name }}

{{-- @component('mail::button', ['url' => url('/login')])
Login
@endcomponent --}}

@component('mail::panel')
Your Application Form for {{ $dept . '-' . $prog }} has been submitted. And ready to be validated by an Admission Officer.
Kindly wait for the Admission's officer's respond to your application.
@endcomponent

Thanks,<br>
St. Mark Arts and Training Institute Inc.
@endcomponent
