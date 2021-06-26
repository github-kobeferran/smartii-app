@extends('layouts.app')

@section('studentprofile')

<?php    $show = 0;  ?>

@if(auth()->user()->id == $userLink->id )

    <?php    $show = 3;     ?>

@elseif(auth()->user()->id == $userLink->id || auth()->user()->user_type == 'admin')

    <?php    $show = 2;     ?>

@elseif(auth()->user()->user_type == 'faculty') 

    <?php    $show = 1;     ?>

@endif


<div class="container mt-2">

    @if($show > 0 && $student->created_by_admin == 0)

        <div class="row border-bottom ">
            
            <div class="col-sm d-flex justify-content-center"> 
                
                @if (is_object($appLink) )

                    <img  class="profile-pic " src="{{url('/storage/images/applicants/id_pics/' . $appLink->id_pic)}}" alt="Id image">    
                    
                @else
                                
                @endif
                                        
            </div>
                
        </div>

    @else

        <div class="mx-auto text-center">

            <em >Students Created by Admin - Profile Upload upcoming soon</em>

        </div>

    @endif

    <div class="row m-4">

        <div class="col-sm mx-auto text-center">
 
            <h5>{{ ucfirst($student->first_name) . ' ' .  ucfirst($student->last_name)}}</h5>
            <em>{{$student->student_id}} </em>
            

        </div>

    </div>

    <div class="row m-3">

        <div class="col-sm mx-auto text-center">

            @if ($show == 3)                

                @if (\App\Models\Setting::first()->enrollment_mode == 1 && auth()->user()->access_grant == 0)
                    <div class="">
                        <h5>Enrollment is now Open! </h5>   
                    </div> 
                    <a href="/enroll/{{$student->id}}" class="btn btn-outline-success m-2">Proceed to Enrollment</a>                                        
                @endif

                
            @endif

            
 
            <table class="table table-striped border">

                <tr>

                    <td class="border-right">
                        Department
                    </td>

                    <td class="w-50">
                        {{ucfirst($student->dept)}}
                    </td>

                </tr>
                <tr>

                    <td class="border-right">
                        Program
                    </td>

                    <td class="w-50">
                        {{ucfirst($student->program_desc)}}
                    </td>

                </tr>
                <tr>

                    <td class="border-right">
                        Level
                    </td>

                    <td class="w-50">
                        {{ucfirst($student->level_desc)}}
                    </td>

                </tr>
                <tr>

                    <td class="border-right">
                        Semester
                    </td>

                    <td class="w-50">
                        
                       @if ($student->semester == 1)
                            Enrolled in First Semester
                       @else
                            Enrolled in Second Semester
                       @endif
                    </td>

                </tr>
                <tr class="other-detail d-none">

                    <td class="border-right">
                        Age
                    </td>

                    <td class="w-50">
                        {{ucfirst($student->age) . ' years'}}
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Balance
                    </td>

                    <td class="w-50">
                        {{  ucfirst($student->balance_amount)}}
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Nationality
                    </td>

                    <td class="w-50">
                        @empty($student->nationality)
                        
                            N\A

                        @else

                        {{  ucfirst($student->nationality)}}

                        @endempty
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Civil Status
                    </td>

                    <td class="w-50">
                        @empty($student->nationality)
                        
                            N\A

                        @else

                        {{  ucfirst($student->civil_status)}}

                        @endempty
                    </td>

                </tr>
                
                <tr class="other-detail d-none">

                    <td class="border-right">
                        Religion
                    </td>

                    <td class="w-50">
                        @empty($student->religion)
                        
                            N\A

                        @else

                        {{ ucfirst($student->religion)}}

                        @endempty
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Contact Number
                    </td>

                    <td class="w-50">
                        @empty($student->contact)
                        
                            N\A

                        @else

                        {{ ucfirst($student->contact)}}

                        @endempty
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Father's Name
                    </td>

                    <td class="w-50">
                        @empty($student->father_name)
                        
                            N\A

                        @else

                        {{  ucfirst($student->father_name)}}

                        @endempty
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Mother's Name
                    </td>

                    <td class="w-50">
                        @empty($student->mother_name)
                        
                            N\A

                        @else

                        {{ ucfirst($student->mother_name)}}

                        @endempty
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Guardian's Name
                    </td>

                    <td class="w-50">
                        @empty($student->guardian_name)
                        
                            N\A

                        @else

                        {{ ucfirst($student->guardian_name)}}

                        @endempty
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Contact in case of Emergency
                    </td>

                    <td class="w-50">
                        @empty($student->emergency_person_contact)
                        
                            N\A

                        @else

                        {{ ucfirst($student->emergency_person_contact)}}

                        @endempty
                    </td>

                </tr>

                @if($show > 1)

                <tr>

                    <td id="detail-button" role="button" onclick="toggleDetails()" class="text-center border border-info bg-info text-white" colspan="2">
                        Show other Details
                    </td>                   

                </tr>

                @endif

                @if($show == 2)

                <tr>

                    <td id="" role="button" data-toggle="modal" data-target="#subjects" class="text-center bg-warning bg-info text-secondary" colspan="2">
                        Enroll to a Subject
                    </td>                   

                </tr>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @include('inc.messages')

                <!-- Modal -->
                <div class="modal fade" id="subjects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Input Subject Code</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        {!!Form::open(['url' => 'enrolltosubject'])!!}

                        {{Form::hidden('student_id', $student->id)}}
                        {{Form::text('subject_code', '', ['class' => 'form-control', 'required'=>'required'])}}                        

                       
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        {!!Form::close() !!}
                        </div>
                    </div>
                    </div>
                </div>
           
                @endif

                @if($show > 2)

                <tr>

                    <td class="border border-info" colspan="2">
                       <button data-toggle="modal" data-target="#editForm" class="btn btn-light border-0 text-info" >Update Details</button>
                    </td>                   

                </tr>

                <div class="modal fade" id="editForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Edit Form</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        {!! Form::open(['url' => '/updatestudent'])!!}
                        <div class="modal-body">                            
                            Nationality
                            {{Form::text('nationality', $student->nationality, ['class' => 'form-control'])}}
                            Civil Status
                            {{Form::text('civil_status', $student->civil_status, ['class' => 'form-control'])}}                            
                            Religion
                            {{Form::text('religion', $student->religion, ['class' => 'form-control'])}}                            
                            Contact Number
                            {{Form::text('contact', $student->contact, ['class' => 'form-control'])}}
                            Father's Name
                            {{Form::text('father_name', $student->father_name, ['class' => 'form-control'])}}
                            Mother's Name
                            {{Form::text('mother_name', $student->mother_name, ['class' => 'form-control'])}}
                            Guardian's Name
                            {{Form::text('guardian_name', $student->guardian_name, ['class' => 'form-control'])}}
                            Contact in case of Emergency
                            {{Form::text('emergency_person_contact', $student->emergency_person_contact, ['class' => 'form-control'])}}

                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        {!!Form::close() !!}
                      </div>
                    </div>
                  </div>


                @endif
                
                

              

            </table>

        </div>

    </div>


    @if($show > 2)

    <div class="row m-4">

        <div class="col mx-auto d-flex justify-content-center">
 
            <a href="{{route('studentClasses')}}" class="student-button">

                My Classes

            </a>

        </div>
        <div class="col mx-auto d-flex justify-content-center">
 
            <a href="/student/balance/" class="student-button">

                Balance and Payments

            </a>

        </div>

    </div>

    @endif  
    
</div>

<script>

let showdetails = false;    
let toggleDetailButton = document.getElementById('detail-button');

function toggleDetails(){
    

    if(!showdetails){
        otherDetails = document.getElementsByClassName('other-detail');    

        for(let i in otherDetails){
            otherDetails[i].className = "other-detail";
        }

        toggleDetailButton.textContent = "Hide other Details"
        showdetails = true;
    } else {

        otherDetails = document.getElementsByClassName('other-detail');    

        for(let i in otherDetails){
            otherDetails[i].className = "other-detail d-none";
        }

        toggleDetailButton.textContent = "Show other Details"
        showdetails = false;

    }
   
}

</script>


@endsection
