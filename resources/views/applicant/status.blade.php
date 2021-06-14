@extends('layouts.module')

@section('status')
<div class="container-fluid">
   
    <div class="row justify-content-center">

        <?php 

            $applicant =  App\Models\Applicant::find(auth()->user()->member->member_id)

        ?>

        @if($applicant->resubmit_file != null || $applicant->resubmit_file != '')

            <div class="justify-content-center p-2 mt-5">

                <p class="text-center">The Admission Officer has requested for the resubmission of the following files:</p>

                @if($applicant->resubmit_file[0] == '1')

                    <div class="border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('idpic', '1x1 ID Picture')}}
                            {{Form::file('id_pic', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                @endif

                @if($applicant->resubmit_file[1] == '1')

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('birthcert', 'Birth Certificate')}}
                            {{Form::file('birt_cert', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                @endif

                @if($applicant->resubmit_file[2] == '1')

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('goodmoral', 'Good Moral Certificate')}}
                            {{Form::file('good_moral', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                @endif

                @if($applicant->resubmit_file[3] == '1')'
                
                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('reportcard', 'Report Card')}}
                            {{Form::file('report_card', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                @endif

            </div>
                            

        @else 

        <div class="jumbo-icon">
            <div class="jumbo-text text-center mt-5">Application Submitted! <br> Please wait for the Admission Officer's validation of your application.            </div>
            <img class="img-fluid mt-0" src="{{url('/storage/images/system/icons/hourglass-icon.png')}}" alt="">               
        </div>

        @endif

      
    </div>

</div>

@endsection