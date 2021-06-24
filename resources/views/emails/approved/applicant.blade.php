@component('mail::message')
Welcome {{ $name }}! :)

@component('mail::button', ['url' => url('/studentprofile')])
Smartii.cc
@endcomponent

@component('mail::panel')
Your have been admitted in {{$prog}} program of {{$dept}} Department.
You may now access the Student Module of Smartii.cc

Thanks,<br>
St. Mark Arts and Training Institute Inc.
@endcomponent