@extends('layouts.app')

@section('meta-content')
Admission guidelines page of St Mark Institute Integrated Information System, platform for handling services offered by St Mark Arts and Training Institute Incorporated
@endsection

@section('content')

@include('inc.homenav')
<div class="container">
    <div class="row mt-3">        
        <div class="col">
            <div class="row">
                <div class="col text-center">
                    <u><h3 class="formal-font">ADMISSION GUIDELINES</h3></u>
                </div>
            </div>

            <div class="row">
                <div class="col text-center">
                    <em>for</em>
                </div>
            </div>

            <div class="row">
                <div class="col text-center">
                    <div class="btn-group btn-group-toggle  border mb-3" >
                        <button id="shs-button" class="btn btn-warning active formal-font">
                            <h4>SENIOR HIGH SCHOOL</h4>
                        </button>        
                        <button id="col-button" class="btn btn-light formal-font text-muted">
                            <h4>COLLEGE</h4>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div>
            <h2 style="font-family: 'Cinzel', serif; background-color:#05551b; color: white;" id="reqs">FILE REQUIREMENTS GUIDELINES</h2>
        </div>               --}}
    </div>

    <div class="row" id="req-panel">
        <div class="col-lg text-left">

            <div>
                <u><h6 class="formal-font">for <span id="dept"></span> students : </h6></u>
            </div>    
            
            <div class="ml-2 roboto-font mb-2" >                
                <ul class="list-group ml-3 ">
                    <li class="list-group-item">
                        must be <span class="age"></span> years of age and older.
                    </li>                                
                </ul>              
                
            </div>

            <div class="row mb-1">
                <div class="col ml-2 roboto-font">
                    <div>
                        <i class="fa fa-caret-right"></i> <span style="font-size: 1.3em;">File Requirements</span>                        
                        <button type="button" data-toggle="modal" data-target="#idpicsample" class="badge badge-warning float-right">see file requirements detailed.</button> 
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col ml-2 roboto-font">
                    <ul class="list-group ml-3 ">
                        <li class="list-group-item smartii-bg-light">
                            1x1 ID Picture                         
                        </li>
                        <li class="list-group-item smartii-bg-light">
                            PSA Birth Certificate 
                        </li>
                        <li class="list-group-item smartii-bg-light">
                            Good Moral Certificate 
                        </li>
                        <li class="list-group-item smartii-bg-light">
                            Grade <span class="report-card"></span> Report Card
                        </li>
                    </ul>
                    <div class="text-right my-1" style="font-size: .8em;">
                        <span class="text-muted">all files must be in <b><em>JPEG</em></b> format <b> must not be more than <em>300 kilobytes </em></b> in size.</span>
                    </div>
                </div>
            </div>


            <div class="row roboto-font" id="steps">
                <div class="col">
                    <i class="fa fa-caret-right"></i> <span style="font-size: 1.5em;">SMARTII.CC Admission Steps</span>                                        
                    <ul class="list-group ml-3 ">
                        <li class="list-group-item blue-bg-light">
                            1. Register with an e-mail address and password <u><a href="{{url('/register')}}">here </a></u>
                        </li>
                        <li class="list-group-item blue-bg-light">
                            2. After email-verification, fill-up the SMARTII Admission Form <u><a href="{{url('/admissionform')}}">here</a></u>
                        </li>
                        <li class="list-group-item blue-bg-light">
                            3. If you successfully submitted your application, you will be redirected to applicant status page.
                        </li>
                        <li class="list-group-item blue-bg-light">
                            4. Kindly wait for the Admission Team to review your application. Regularly visit your status page, you will also be notified via e-mail.
                        </li>
                    </ul>
                </div>

            </div>

        </div>

        <div class="modal fade" id="idpicsample" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header smartii-bg-dark">
                        <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">ID Picture Guidelines</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row roboto-font">                            
                            <div class="col text-left">

                                <h4 class="">Required Document Guidelines</h4>

                                <ul class="list-group mb-2" style="font-size: .8em;">
                                    <li class="list-group-item smartii-bg-light">Must be scanned and clear.</li>
                                    <li class="list-group-item smartii-bg-light" >PSA Birth Certificate, Good Moral Certficate and Grade <span class="report-card"></span> Report Cards must be submitted once accepted into the institution.</li>
                                </ul>

                                <h4 class="">Required Photo Guidelines</h4>
                                
                                <ul class="list-group mb-2" style="font-size: .8em;">
                                    <li class="list-group-item green-bg-light" >1 x 1 inch, colored photo with white background</li>
                                    <li class="list-group-item green-bg-light">Formal pose with collar and no eyeglasses or any accessories that may cover the facial features</li>
                                    <li class="list-group-item green-bg-light">Taken in the past seven (7) days prior to filing of online application</li>
                                    <li class="list-group-item green-bg-light">With complete, readable name tag following this format: First Name, Middle Name, and Last Name (as indicated in your PSA-copy of Birth Certificate) positioned at the chest</li>
                                </ul>

                                <div class="row">                                    
                                    <div class="col text-center">
                                        <h5><span class="text-secondary">The following sample photos are <span class="text-success">ACCEPTABLE</span>:</span></h5>
                                        <div class="row">
                                            <div class="col">
                                                <img src="{{url('/storage/images/system/admission/proper-id.jpg')}}" alt="" class="img-thumbnail">                                                
                                            </div>
                                            <div class="col">
                                                <img src="{{url('/storage/images/system/admission/proper-id-female.jpg')}}" alt="" class="img-thumbnail">
                                                <span class="text-muted">If your complete name is too long to fit the name tag in 1 line, you can write it in 2 lines</span>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <h5 class=""><span class="text-dark">Required File Format</span></h5>
                                <ul class="list-group mb-2" style="font-size: .8em;">
                                    <li class="list-group-item green-bg-light">Your ID photo must be saved as a JPEG file format (.jpg or .jpeg)</li>
                                    <li class="list-group-item green-bg-light">File size must not be more than 300 kilobytes</li>
                                    <li class="list-group-item green-bg-light">You must save your photo in your computer, smartphone or a storage device/drive for backup.</li>
                                </ul>

                                <hr>
                                <div class="row">
                                    <div class="col text-center">
                                        <h5><span class="text-secondary">The following sample photos are <span class="text-danger">NOT ACCEPTABLE</span>:</span></h5>
                                        <div class="row">
                                            <div class="col d-flex flex-wrap justify-content-center">
                                                <div class="mx-auto">
                                                    <img src="{{url('/storage/images/system/admission/wrongpix01.jpg')}}" alt="" class="img-thumbnail">                                                
                                                    <p class="text-danger">Photos that have a background view/scene</p>
                                                </div>
                                                <div class="mx-auto">
                                                    <img src="{{url('/storage/images/system/admission/wrongpix02.jpg')}}" alt="" class="img-thumbnail">                                                
                                                    <p class="text-danger">Side shot, top shot (bird's-eye view)</p>
                                                </div>
                                                <div class="mx-auto">
                                                    <img src="{{url('/storage/images/system/admission/wrongpix03.jpg')}}" alt="" class="img-thumbnail">                                                
                                                    <p class="text-danger"> bottom shot (worm's-eye view)</p>
                                                </div>
                                                <div class="mx-auto">
                                                    <img src="{{url('/storage/images/system/admission/wrongpix04.jpg')}}" alt="" class="img-thumbnail">                                                
                                                    <p class="text-danger">Unreadable name tag</p>
                                                </div>
                                                <div class="mx-auto">
                                                    <img src="{{url('/storage/images/system/admission/wrongpix05.jpg')}}" alt="" class="img-thumbnail">                                                
                                                    <p class="text-danger">Name tag with abbreviated name or initials</p>
                                                </div>
                                                <div class="mx-auto">
                                                    <img src="{{url('/storage/images/system/admission/wrongpix06.jpg')}}" alt="" class="img-thumbnail">                                                
                                                    <p class="text-danger">Scanned whole page</p>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-left">Not acceptable:</p>
                                        <div class="row">
                                            <div class="col text-left">
                                                <ul class="list-group ml-3" style="font-size: .8em;">
                                                    <li class="list-group-item red-bg-light">Blurred photos</li>
                                                    <li class="list-group-item red-bg-light">Wacky/non-formal shot</li>
                                                    <li class="list-group-item red-bg-light">Group shot</li>
                                                    <li class="list-group-item red-bg-light">Landscape, sceneries, animals, material objects, body parts, memes</li>
                                                </ul>
                                            </div>
                                        </div>
                                     
                                    </div>                                    
                          
                                </div>
                                <div class="row">
                                    <div class="col text-left mt-2" style="font-size: .7em;">
                                        <span class="text-muted">Image sources: <a href="https://www.pup.edu.ph/iapply/photoguidelines?fbclid=IwAR0i_Qk3yKOkre7DAn121zG3VBhYUTbTkO_hJf-T5bHzdYPnzUqaaggpa3w">https://www.pup.edu.ph/iapply/photoguidelines?fbclid=IwAR0i_Qk3yKOkre7DAn121zG3VBhYUTbTkO_hJf-T5bHzdYPnzUqaaggpa3w</a></span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
   
</div>

<script>

window.onload = () => {
    showGuidelines();
}

let shs_button = document.getElementById('shs-button');
let col_button= document.getElementById('col-button');
let dept = document.getElementById('dept');
let report_card = document.getElementsByClassName('report-card');
let ages = document.getElementsByClassName('age');

shs_button.addEventListener('click', () =>  {
    shs_button.className = 'btn btn-warning active formal-font';
    col_button.className = 'btn btn-light formal-font text-muted';
    showGuidelines();
});

col_button.addEventListener('click', () =>  {
    col_button.className = 'btn btn-success active formal-font';
    shs_button.className = 'btn btn-light formal-font text-muted';
    showGuidelines();
});

function showGuidelines(){
    if(shs_button.classList.contains('active'))
        showSHS();
    else 
        showCollege();
}

function showSHS(){
    dept.textContent = 'Senior High School';
    for(let i in report_card){
        if(typeof report_card[i] == 'object'){
            report_card[i].textContent = '10';

        }
    }
    for(let i in ages){
        if(typeof ages[i] == 'object'){
            ages[i].textContent = '15';

        }
    }
}

function showCollege(){
    dept.textContent = 'College/TESDA';
    for(let i in report_card){
        if(typeof report_card[i] == 'object'){
            report_card[i].textContent = '12';

        }
    }
    for(let i in ages){
        if(typeof ages[i] == 'object'){
            ages[i].textContent = '18';

        }
    }
}



</script>

@endsection
