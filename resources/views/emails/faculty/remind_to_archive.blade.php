@component('mail::message')
Our Dear Instructor, {{$faculty->first_name}} {{$faculty->last_name}} 

This is a reminder that you still have {{$faculty->active_classes->count()}} {{$faculty->active_classes->count() > 1 ? 'classes' : 'class'}} needed to be archived. 

@component('mail::panel')

    @component('mail::table')
    | CLASS NAME| PROGRAM             |
    | ----------| :------------------:| 
    @foreach($faculty->active_classes as $class)
    |{{$class->class_name}}  | {{$class->subjectsTaken()->first()->student->program->abbrv}}
    @endforeach
    @endcomponent

    for questions please contact the registrar.

@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
