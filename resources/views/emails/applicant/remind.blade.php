@component('mail::message')
Hi {{$user->name}} !

It's been {{\Carbon\Carbon::parse($user->created_at)->diffForHumans()}} since you registered into smartii.cc
However, you still haven't submitted your Application Form.
Please submit it, take this as a notice before we delete your account in the system. Thank you.

<?php 
    $url = url('/admissionform');
    $help = url('/admissionhelp');
?>

@component('mail::button', ['url' => $url])
Admission Form
@endcomponent
@component('mail::button', ['url' => $help])
Read Guidelines
@endcomponent

Thanks,<br>
SMARTII Admission Office
@endcomponent
