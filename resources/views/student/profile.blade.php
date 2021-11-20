@extends('layouts.app')

@section('page-title')
    {{$student->last_name}} ({{$student->student_id}})
@endsection

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

        @if ($show > 1)
            <div class="mx-auto text-center">

                <em >Students Created by Admin - Profile Upload upcoming soon</em>

            </div>
        @endif

    @endif

    <div class="row m-4">

        <div class="col-sm mx-auto text-center">
 
            <h5>{{ ucfirst($student->first_name) . ' ' .  ucfirst($student->last_name)}}</h5>
            <em>{{$student->student_id}} </em>
            <br>
            @if ($show == 2)
                <em>{{$student->member->user->email}}</em>
            @endif

        </div>

    </div>

    <div class="row m-3">

        <div class="col-sm mx-auto text-center">

            @if ($show == 3)                

                @if (\App\Models\Setting::first()->enrollment_mode)
                    @if ($student->subjectsTakenThisSemester()->count() == 0)                                     
                        <?php $valid = true;?>                        
                        @foreach ($student->subject_taken as $subject_taken)
                            @if ($subject_taken->from_year == \App\Models\Setting::first()->from_year &&
                                $subject_taken->to_year == \App\Models\Setting::first()->to_year &&
                                $subject_taken->semester == \App\Models\Setting::first()->semester)
                                <?php $valid = false;?>                        
                            @endif
                        @endforeach

                        @if ($valid)
                            <div class="row">
                                <div class="col my-auto text-right mr-1">
                                    <h5 class="">ENROLLMENT IS NOW OPEN !</h5> 
                                </div>
                                <div class="col text-left ml-1">
                                    <a href="/enroll" class="btn btn-outline-success m-2 neon neon-div"> <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>Proceed to Enrollment</a>                         
                                </div>
                            </div>  
                        @endif
                    @endif  
                @endif
            @endif

            @if (auth()->user()->isAdmin())
                
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
                        @if (\App\Models\Setting::first()->enrollment_mode)
                            @if ($show == 3 && $student->registrar_requests->where('type', 'shift')->where('status', 0)->count() < 1)
                                <span role="button" data-toggle="modal" data-target="#create-shift" class="float-right text-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                            @endif
                        @endif

                    </td>

                    <td class="w-50">
                        <a target="_blank" class="text-dark" href="{{url('viewprogramcourses/export/' . $student->program->abbrv)}}">{{ucfirst($student->program_desc)}}</a>          

                        @if ($show >= 2 && $student->registrar_requests->where('type', 'shift')->count() > 0)

                            <button type="button" data-target="#shift-history" data-toggle="modal" class="badge badge-light">See Shift Requests History</button>

                            <div id="shift-history" class="modal fade" aria-hidden="true" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" style="max-width: 80%;" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark">
                                            <h5 class="modal-title"><span class="text-white">{{$student->first_name}} {{$student->last_name}} Shifting Requests History <i class="fa fa-history"></i></span></h5>
                                            <button class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="bg-secondary text-white">
                                                        <tr>
                                                            <th>Made at</th>
                                                            <th>Shift to</th>
                                                            <th>Status</th>
                                                            <th>Marked by</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($student->registrar_requests->where('type', 'shift')->sortByDesc('created_at') as $request)
                                                            <tr>
                                                                <td>{{$request->created_at->isoFormat('MMM DD, YYYY hh:mm A')}}</td>
                                                                <td>{{\App\Models\Program::find($request->type_id)->desc}} ({{\App\Models\Program::find($request->type_id)->abbrv}})</td>
                                                                <td>
                                                                    @switch($request->status)
                                                                        @case(0)
                                                                            <span class="text-info">Pending</span>
                                                                            @break
                                                                        @case(1)
                                                                            <span class="text-success">Approved</span>
                                                                            @break
                                                                        @case(2)
                                                                            <span class="text-danger">Rejected</span>
                                                                            @break
                                                                        @default
                                                                            <span class="text-info">Pending</span>
                                                                    @endswitch
                                                                </td>
                                                                <td>
                                                                     @if (!is_null($request->marked_by))
                                                                        <b>{{$request->admin->name}} </b> <em>{{!is_null($request->reject_reason) ? $request->reject_reason : ''}}</em>
                                                                     @else
                                                                        -- 
                                                                     @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

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

                    <td style="font-family: 'Source Code Pro', monospace;" class="w-50">
                        &#8369; {{number_format($student->balance_amount, 2)}}
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

                    @if (auth()->user()->isAdmin())    

                    <?php
                        $admin = \App\Models\Admin::where('id', auth()->user()->member->member_id)->first();
                    ?>
            
                        @if ($admin->position == 'superadmin' || $admin->position == 'registrar')
                            <tr>
                                <td id="" role="button" data-toggle="modal" data-target="#subjects-valid-to-enroll" class="text-center bg-warning bg-info text-secondary" colspan="2">
                                    <span style="font-weight: bold !important;">Enroll to Subjects</span>
                                </td>                               
                            </tr>

                            <div class="modal fade" id="subjects-valid-to-enroll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">PICK SUBJECTS TO ENROLL</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        {!!Form::open(['url' => 'enrolltosubject'])!!}                                            
                                            <div class="modal-body">
                                                <select style="min-height: 400px;" name="subjects[]" value="" id="select-subjects" class="custom-select rounded-0" multiple required>                    
                                                    @foreach ($student->stillToBeTakenSubjects() as $subject)
                                                        <option value="{{$subject->id}}">{{$subject->code}} - {{$subject->desc}}</option>
                                                    @endforeach
                                                </select>
                                                {{Form::hidden('id', $student->id)}}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Enroll {{$student->first_name}} {{$student->last_name}} to selected Subjects</button>
                                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                            </div>
                                        {!!Form::close() !!}
                                    </div>
                                </div>
                            </div>

                        @endif

                    @endif                            
           
                @endif

                @if ($show >= 2) 
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @include('inc.messages')

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
                          <h5 class="modal-title" id="exampleModalLongTitle">Edit My Personal Profile</h5>
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
                            <button type="submit" class="btn btn-primary">Save changes</button>
                          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        </div>
                        {!!Form::close() !!}
                      </div>
                    </div>
                  </div>

                @endif

            </table>

        </div>

    </div>
  
    @if (auth()->user()->isAdmin())    

        <?php
            $admin = \App\Models\Admin::where('id', auth()->user()->member->member_id)->first();
        ?>
        <div class="row my-1">
            @if ($admin->position == 'superadmin' || $admin->position == 'accounting')
                @if ($student->subjectsTakenThisSemester()->count() > 0)
                    <div class="col text-left">                    
                        <h5>Discounts attached to {{ucfirst($student->first_name) . ' ' . ucfirst($student->last_name)}} </h5>                        
                        <ul class="list-group list-group-flush">
                            @if ($student->discounts->count() > 0)                    
                                @foreach ($student->discounts as $discount_rel)
                                    <?php $discount = \App\Models\Discount::find($discount_rel->discount_id); ?>
                                    <li class="list-group-item"> <button data-toggle="modal" data-target="#remove-{{$discount->id}}" class="btn btn-sm btn-danger mr-2">Remove</button> {{$discount->description}} - {{$discount->percentage}} %</li>                                
                                    <div class="modal fade" id="remove-{{$discount->id}}" tabindex="-1" role="dialog" aria-labelledby="remove-{{$discount->discount_id}}Title" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white" >
                                            <h6 class="modal-title" id="exampleModalLongTitle">Remove {{$discount->description}} from {{ucfirst($student->first_name) . ' ' . ucfirst($student->last_name)}} ?</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                            Removing {{$discount->description}} from {{ucfirst($student->first_name) . ' '  . ucfirst($student->last_name)}} 
                                            will add {{$discount->percentage}}% of {{$student->pronoun}} supposed total tuition this sem without discount (which is &#8369; {{number_format($student->tuition_without_discount, 2)}})
                                            </div>
                                            <div class="modal-footer">
                                                {{Form::open(['url' => 'detachdiscount'])}}
                                                    {{Form::hidden('stud_id', $student->id)}}
                                                    {{Form::hidden('disc_id', $discount->id)}}
                                                    <button type="submit" class="btn btn-danger">Remove</button>
                                                {{Form::close()}}
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                @endforeach                                                        

                            @else  

                            @endif
                            <li class="list-group-item text-right"><button data-toggle="modal" data-target="#add-discount-modal" type="button" class="btn btn-success">Attach a Discount <i class="fa fa-plus" aria-hidden="true"></i></button></li>

                            <div class="modal fade" id="add-discount-modal" tabindex="-1" role="dialog" aria-labelledby="add-discount-modalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white" >
                                    <h6 class="modal-title" id="exampleModalLongTitle">Attach a Discount to {{ucfirst($student->first_name) . ' '  . ucfirst($student->last_name)}}</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    {{Form::open(['url' => 'attachdiscount'])}}
                                        <div class="modal-body">
                                            <div class="text-center w-75 mx-auto">
                                                Attaching Discounts will update {{ucfirst($student->first_name) . ' '  . ucfirst($student->last_name)}}'s balance amount (&#8369; {{number_format($student->balance->amount, 2)}})
                                            </div>
                                            {{Form::hidden('stud_id', $student->id)}}
                                            <select name="discount[]" value="" id="select-discount" class="form-control " multiple required>                                            
                                                <?php 
                                                    $total_percentage = 0;
                                                    $all_discounts = \App\Models\Discount::all();
                                                    
                                                    if($student->discounts->count() > 0){          
                                                        
                                                        $already_attached = collect(new \App\Models\Discount);
                                                        
                                                        foreach ($student->discounts as $discount_rel) {
                                                            $already_attached->push(\App\Models\Discount::find($discount_rel->discount_id));                                                        
                                                            $total_percentage += \App\Models\Discount::find($discount_rel->discount_id)->percentage;
                                                        }
                                                        
                                                        $all_discounts = $all_discounts->diff($already_attached);
                                                    }                                              
                                                    ?>                                                                                              
                                                @foreach ($all_discounts as $discount)
                                                    <option value="{{$discount->id}}">{{$discount->description}} ({{number_format($discount->percentage, 1)}} %)</option>
                                                @endforeach
                                            </select>
                                            {{Form::hidden('total_percentage', $total_percentage)}}                                        
                                        </div>
                                        <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Attach</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    {{Form::close()}}
                                </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                @endif      
            @endif      
            @if($admin->position == 'superadmin' || $admin->position == 'registrar')
                    <div class="col text-right">
                        <div>
                            <a href="{{url('/cor/'. $student->student_id)}}" target="_blank">View Certificate of Registration</a>
                        </div>
                        
                        @if (!is_null($student->applicant))
                            <button type="button" data-toggle="modal" data-target="#viewimage" class="btn btn-sm btn-light border">View Images</button>

                            <div class="modal fade" id="viewimage" tabindex="-1" aria-labelledby="viewImage" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{$student->first_name}} {{$student->last_name}}'s Images</h5>
                                            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center">
                                                <h4>Click to download image</h4>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="text-center">
                                                        <label for="">ID Picture</label>
                                                    </div>
                                                    <div class="text-center">
                                                        <a href="{{url('/admin/download/idpic/'. $appLink->id_pic)}}">
                                                            <img class="img-fluid w-25" src="{{url('/storage/images/applicants/id_pics/' . $appLink->id_pic)}}" alt="Id image">    
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="text-center">
                                                        <label for="">Birth Certificate</label>
                                                    </div>
                                                    <div class="text-center">
                                                        <a href="{{url('/admin/download/birthcert/'. $appLink->birth_cert)}}">
                                                            <img class="img-fluid w-25" src="{{url('/storage/images/applicants/birth_certs/' . $appLink->birth_cert)}}" alt="Id image">    
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="text-center">
                                                        <label for="">Good Moral Certificate</label>
                                                    </div>
                                                    <div class="text-center">
                                                        <a href="{{url('/admin/download/goodmoral/'. $appLink->good_moral)}}">
                                                            <img class="img-fluid w-25" src="{{url('/storage/images/applicants/good_morals/' . $appLink->good_moral)}}" alt="Id image">    
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="text-center">
                                                        <label for="">Form 138</label>
                                                    </div>
                                                    <div class="text-center">
                                                        <a href="{{url('/admin/download/reportcard/'. $appLink->report_card)}}">
                                                            <img class="img-fluid w-25" src="{{url('/storage/images/applicants/report_cards/' . $appLink->report_card)}}" alt="Id image">    
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
            @endif
        </div>

    @endif

    <div class="modal fade" id="create-shift" aria-hidden="true" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title"><span class="text-white">Create a Shifting to Another Program Request</span></h5>
                    <button class="close text-white" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                {!! Form::open(['url' => '/storeshift'])!!}
                    <div class="modal-body">                        
                        <b><label for="">Change my program to: </label></b>
                        <select name="program" id="" class="form-control" required>                                        
                            <?php 
                                $programs = \App\Models\Program::where('department', $student->department)
                                                                ->where(function($query) use($student) {
                                                                    $query->where('id', '!=', $student->department ? 4 : 3)
                                                                        ->where('id', '!=', $student->program_id);
                                                                })
                                                                ->get();
                            ?>                                        
                            <option value="" selected>Select a Program</option>
                            @foreach ($programs as $program)
                                <option value="{{$program->id}}">{{$program->abbrv}} {{$program->desc}}</option>
                            @endforeach
                            {{Form::hidden('id', $student->id)}}
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark">Submit Request to change my Program</button>
                        <button type="button" data-dismiss="modal" class="btn btn-light">Cancel</button>
                    </div>
                {!! Form::close() !!}
            </div>
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

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}


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