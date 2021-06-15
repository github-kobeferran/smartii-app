@extends('layouts.module')

@section('status')
<div class="container-fluid">
   
    <div class="row justify-content-center">

        <?php 

            $applicant =  App\Models\Applicant::find(auth()->user()->member->member_id);            

        ?>

        @if($applicant->resubmit_file != null && $applicant->resubmit_file != '0000')
   
        {!! Form::open(['url' => 'applicant/resubmit/', 'files' => true, 'id' => 'resubmitForm']) !!}

            {{ Form::hidden('status', $applicant->resubmit_file) }}
            {{ Form::hidden('id', $applicant->id) }}

            <div class="justify-content-center p-2 mt-5">

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @include('inc.messages')

                <p class="text-center">The Admission Officer has requested you to resubmit the following files:</p>

                @switch($applicant->resubmit_file)
                    @case('1000')
                        <div class="border rounded border-secondary mb-2">

                            <div class="form-group m-3">

                                {{Form::label('idpic', '1x1 ID Picture')}}
                                {{Form::file('id_pic', ['class' => 'form-control-file'])}}
                
                            </div>

                        </div>
                       
                                                            
                        @break
                    @case('1100')
                    <div class="border rounded border-secondary mb-2">
                        
                        <div class="form-group m-3">
                            
                            {{Form::label('idpic', '1x1 ID Picture')}}
                            {{Form::file('id_pic', ['class' => 'form-control-file'])}}
                            
                        </div>
                        
                    </div>
                    
                    <div class=" border rounded border-secondary mb-2">
                        
                        <div class="form-group m-3">
                            
                            {{Form::label('birthcert', 'Birth Certificate')}}
                            {{Form::file('birth_cert', ['class' => 'form-control-file'])}}
                            
                        </div>
                        
                    </div>
                    
                    
                    @break
                    @case('1001')

                    <div class="border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('idpic', '1x1 ID Picture')}}
                            {{Form::file('id_pic', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>
                                  

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('reportcard', 'Report Card')}}
                            {{Form::file('report_card', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    @break
                    @case('1011')

                        <div class="border rounded border-secondary mb-2">

                            <div class="form-group m-3">

                                {{Form::label('idpic', '1x1 ID Picture')}}
                                {{Form::file('id_pic', ['class' => 'form-control-file'])}}
                
                            </div>

                        </div>                  

                        <div class=" border rounded border-secondary mb-2">

                            <div class="form-group m-3">

                                {{Form::label('goodmoral', 'Good Moral Certificate')}}
                                {{Form::file('good_moral', ['class' => 'form-control-file'])}}
                
                            </div>

                        </div>

                        <div class=" border rounded border-secondary mb-2">

                            <div class="form-group m-3">

                                {{Form::label('reportcard', 'Report Card')}}
                                {{Form::file('report_card', ['class' => 'form-control-file'])}}
                
                            </div>

                        </div>

                    @break
                    @case('1110')
                    <div class="border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('idpic', '1x1 ID Picture')}}
                            {{Form::file('id_pic', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('birthcert', 'Birth Certificate')}}
                            {{Form::file('birth_cert', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('goodmoral', 'Good Moral Certificate')}}
                            {{Form::file('good_moral', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>
              
                    @break
                    @case('1111')

                    <div class="border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('idpic', '1x1 ID Picture')}}
                            {{Form::file('id_pic', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('birthcert', 'Birth Certificate')}}
                            {{Form::file('birth_cert', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('goodmoral', 'Good Moral Certificate')}}
                            {{Form::file('good_moral', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('reportcard', 'Report Card')}}
                            {{Form::file('report_card', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>
                    @break
                    @case('0100')

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('birthcert', 'Birth Certificate')}}
                            {{Form::file('birth_cert', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>
               
                    @break
                    @case('0110')
          
                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('birthcert', 'Birth Certificate')}}
                            {{Form::file('birth_cert', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('goodmoral', 'Good Moral Certificate')}}
                            {{Form::file('good_moral', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    @break
                    @case('0101')
         

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('birthcert', 'Birth Certificate')}}
                            {{Form::file('birth_cert', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>
                    

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('reportcard', 'Report Card')}}
                            {{Form::file('report_card', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>
                    @break
                    @case('0111')
              

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('birthcert', 'Birth Certificate')}}
                            {{Form::file('birth_cert', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('goodmoral', 'Good Moral Certificate')}}
                            {{Form::file('good_moral', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('reportcard', 'Report Card')}}
                            {{Form::file('report_card', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    @break
                    @case('0010')                  

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('goodmoral', 'Good Moral Certificate')}}
                            {{Form::file('good_moral', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>
                  
                    @break
                    @case('0011')


                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('goodmoral', 'Good Moral Certificate')}}
                            {{Form::file('good_moral', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('reportcard', 'Report Card')}}
                            {{Form::file('report_card', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>
                    @break
                    @case('0001')
               

                    <div class=" border rounded border-secondary mb-2">

                        <div class="form-group m-3">

                            {{Form::label('reportcard', 'Report Card')}}
                            {{Form::file('report_card', ['class' => 'form-control-file'])}}
            
                        </div>

                    </div>

                    @break              
                    @default
                        
                @endswitch
                            
                <div class = "form-group mt-3">   
                         
                    {{Form::submit('Resubmit',  ['class' => 'btn btn-primary btn-block '])}}

                </div> 

            </div>

        {!! Form::close() !!}
                            

        @else 

            <div class="jumbo-icon">
                <div class="jumbo-text text-center mt-5">Application Submitted! <br> Please wait for the Admission Officer's validation of your application.</div>
                <img class="img-fluid mt-0" src="{{url('/storage/images/system/icons/hourglass-icon.png')}}" alt="">               
            </div>

        @endif

      
    </div>

</div>

@endsection