@component('mail::message')
Dear {{$name}},


@component('mail::panel')
The Admission Committee has carefully considered your application and we regret to inform you that we will not be able to offer you admission to {{$dept}} - {{$prog}}. Due to {{$reason}}.

@endcomponent

Thank you for your application. 

Thanks,<br>
St. Mark Arts and Training Institute Admission Commitee
@endcomponent
