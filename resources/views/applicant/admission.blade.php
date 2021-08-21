@extends('layouts.module')

<style>

.multisteps-form__progress {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(0, 1fr));
}

.multisteps-form__progress-btn {
  transition-property: all;
  transition-duration: 0.15s;
  transition-timing-function: linear;
  transition-delay: 0s;
  position: relative;
  padding-top: 20px;
  color: rgba(108, 117, 125, 0.7);
  text-indent: -9999px;
  border: none;
  background-color: transparent;
  outline: none !important;
  cursor: pointer;
}

@media (min-width: 500px) {
  .multisteps-form__progress-btn {
    text-indent: 0;
  }
}

.multisteps-form__progress-btn:before {
  position: absolute;
  top: 0;
  left: 50%;
  display: block;
  width: 13px;
  height: 13px;
  content: '';
  -webkit-transform: translateX(-50%);
          transform: translateX(-50%);
  transition: all 0.15s linear 0s, -webkit-transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
  transition: all 0.15s linear 0s, transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
  transition: all 0.15s linear 0s, transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s, -webkit-transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
  border: 2px solid currentColor;
  border-radius: 50%;
  background-color: #fff;
  box-sizing: border-box;
  z-index: 3;
}

.multisteps-form__progress-btn:after {
  position: absolute;
  top: 5px;
  left: calc(-50% - 13px / 2);
  transition-property: all;
  transition-duration: 0.15s;
  transition-timing-function: linear;
  transition-delay: 0s;
  display: block;
  width: 100%;
  height: 2px;
  content: '';
  background-color: currentColor;
  z-index: 1;
}

.multisteps-form__progress-btn:first-child:after {
  display: none;
}

.multisteps-form__progress-btn.js-active {
  color: #007bff;
}

.multisteps-form__progress-btn.js-active:before {
  -webkit-transform: translateX(-50%) scale(1.2);
          transform: translateX(-50%) scale(1.2);
  background-color: currentColor;
}

.multisteps-form__form {
  position: relative;
}

.multisteps-form__panel {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 0;
  opacity: 0;
  visibility: hidden;
}

.multisteps-form__panel.js-active {
  height: auto;
  opacity: 1;
  visibility: visible;
}
Define your own CSS3 animations in the CSS.

.multisteps-form__panel[data-animation="scaleIn"] {
  -webkit-transform: scale(0.9);
          transform: scale(0.9);
}

.multisteps-form__panel[data-animation="scaleIn"].js-active {
  transition-property: all;
  transition-duration: 0.2s;
  transition-timing-function: linear;
  transition-delay: 0s;
  -webkit-transform: scale(1);
          transform: scale(1);
}
</style>
    

@section('admission')
<div class="container">
   
    <div class="row justify-content-center">
        
        <div class="col-md-8">            

          <?php 

              if(auth()->user()->user_type != 'applicant'){           
        
                  return redirect()->back();
              }

              if(auth()->user()->member != null){           
        
                  return redirect()->back();
              }

              $dept = false;
              $req = false;
              $personal = false;     
              $resubmit_files = false;     
              $resubmit_personal = false;  
                           


          ?>

          @if ( session()->has('active') )
          <?php 

              $value = session('active');

              switch ($value) {
                  case 'dept':
                      $dept = true;
                      break;
                  case 'req':
                      $req = true;
                      break;
                  case 'personal':
                      $personal = true;
                      break;
                  case 'resubmit_personal':
                      $dept = true;
                      $personal = true;   
                      $resubmit_personal = true;                                      
                      break;                    
                  case 'resubmit_files':
                      $dept = true;
                      $personal = true;
                      $req = true;                     
                      $resubmit_files = true;                                                            
                      break;
                      
                  default:
                      $student = true;
                      break;

              }
          ?>

          @else     
          <?php
              

              $dept = true;
          ?>
              
          @endif      
          
         
        

            {!! Form::open(['url' => 'applicant/create/', 'files' => true, 'id' => 'applicantForm']) !!}

            <div class="multisteps-form ">
               

                <!--------------------------------progress bar-->
                <div class="row ">
                  <div class="col-12 col-lg-8 ml-auto  mr-auto mb-4 mt-3">
                    <div class="multisteps-form__progress">
                      <button id="step1" class="multisteps-form__progress-btn {{ $dept ? 'js-active' : '' }}" type="button" title="Department" >Department and Program</button>                                          
                      <button id="step2" class="multisteps-form__progress-btn {{ $personal ? 'js-active' : '' }}" type="button" title="Personal Data">Personal Details</button>
                      <button id="step3" class="multisteps-form__progress-btn {{ $req ? 'js-active' : '' }}" type="button" title="Admission Requirements">Admission Requirements</button>
                    </div>
                  </div>
                </div>



                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                    </div>
                @endif
            
                @include('inc.messages')


                <!-------------------------------------Monitor Inputs-->
                <p id="monitorInputs" class="d-none text-center"></p>

                
                <!--form panels-->

                <div class="row">
                  <div class="col-12 col-lg-8 m-auto">
                      
                    <form class="multisteps-form__form">


                      <!--------------------------------------- Department Selection --> 
                      <div class="multisteps-form__panel  p-4 rounded bg-white {{ $dept ? 'js-active' : '' }}" data-animation="scaleIn">
                        <h3 style="font-family: 'Raleway', sans-serif; font-weight: 900px; color: #044716;" id="selected-dept" class="multisteps-form__title text-center">Select Department</h3>
                        <div class="multisteps-form__content">

                            <div class="form-group text-center text-white">                                 
                                
                                <button id="btnShsSelect"  value="0" onclick="toggleDepartment(document.getElementById('btnShsSelect'))" style="height: 100px; width: 120px;" type="button" class="btn btn-primary m-1 " data-toggle="button" aria-pressed="false" autocomplete="off">
                                    <h4>Senior High School</h4>
                                </button>

                                <button id="btnCollegeSelect" value="1" onclick="toggleDepartment(document.getElementById('btnCollegeSelect'))" style="height: 100px;  width: 120px;" type="button" class="btn btn-primary m-1" data-toggle="button" aria-pressed="false" autocomplete="off">
                                    <h4>College</h4>
                                </button>

                                <input id="hiddenDept" type="hidden" name="dept">
                                
                            </div>

                            <div id="divProg" class="form-group text-center mt-3 d-none">
                                <h4 style="font-family: 'Raleway', sans-serif; font-weight: 900px; color: #044716;">Program</h4>
                                {{Form::select('program_id', [], null, ['placeholder' => 'Select a Program', 'class' => 'form-control' , 'id' => 'selectProg'])}}   
                            </div>                                                              

                        </div>
                      </div>

                      <!--------------------------------------- Personal Data --> 
                      <div class="multisteps-form__panel p-4 rounded bg-white {{ $personal ? 'js-active' : '' }}" data-animation="scaleIn">
                        
                        <script>
                          let val_dept = null;
                          let val_prog = null;

                        </script>

                        @if (session('dept'))                          

                          <script>
                              val_dept = {!! json_encode(session()->get('dept')) !!}
                          </script>

                          <script>
                            val_prog = {!! json_encode(session()->get('prog')) !!}
                          </script>

                          <script>
                            val_prog_desc = {!! json_encode(session()->get('prog_desc')) !!}
                          </script>

                        @endif                 

                        @if (session('l_name'))                          

                          <script>
                            val_l_name = {!! json_encode(session()->get('l_name')) !!}
                          </script>
                          <script>
                            val_m_name = {!! json_encode(session()->get('m_name')) !!}
                          </script>
                          <script>
                            val_f_name = {!! json_encode(session()->get('f_name')) !!}
                          </script>
                          <script>
                            val_dob = {!! json_encode(session()->get('dob')) !!}
                          </script>
                          <script>
                            val_gender = {!! json_encode(session()->get('gender')) !!}
                          </script>
                          <script>
                            val_present_address = {!! json_encode(session()->get('present_address')) !!}
                          </script>
                          <script>
                            val_last_school = {!! json_encode(session()->get('last_school')) !!}
                          </script>

                        @endif
                     

                        <h3 style="font-family: 'Raleway', sans-serif; font-weight: 900px; color: #044716;" class="multisteps-form__title text-center">Personal Information</h3>
                        <div class="multisteps-form__content">                            
                
                            <div class="border rounded border-secondary mb-2">
                
                                <div class="form-group m-3">
                                    {{Form::label('details', 'Please fill data needed ')}}
                                    {{ Form::text('l_name', '', [ 'id' => 'lName', 'maxLength' => '100', 'class' => 'form-control mb-2', 'placeholder' => 'Your Last Name Here..']) }}
                                    {{ Form::text('f_name', '', ['id' => 'fName','maxLength' => '100', 'class' => 'form-control mb-2', 'placeholder' => 'Your First Name Here..']) }}
                                    {{ Form::text('m_name', '', ['id' => 'mName','maxLength' => '100','class' => 'form-control mb-2', 'placeholder' => 'Your Middle Name Here..']) }}
                    
                                </div>

                                
                                <div class = "form-group text-center">     
                                    {{Form::label('dob', 'Date of Birth')}}                   
                                    {{Form::date('dob', \Carbon\Carbon::now()->subYears(15), [ 'class' => 'ml-2', 'id' => 'dob'] )}}
                                </div> 
                          
                                
                                <div class = "form-group text-center">     
                                    {{Form::label('gender', 'Sex',  ['class' => 'mt-2'])}}
                                    {{Form::select('gender', ['male' => 'Male',
                                                                'female' => 'Female',                                                              
                                                                ], null,
                                                                ['class' => 'custom-select w-50', 'id' => 'selectGender'])}}   
                                </div>

                                <div class = "form-group m-3">                                                          
                                    {{ Form::text('present_address', '', ['maxLength' => '100', 'id' => 'address', 'class' => 'form-control mb-2', 'placeholder' => 'Your Present Address..']) }}
                                </div> 
                                <div class = "form-group m-3">                                                          
                                    {{ Form::text('last_school', '', ['maxLength' => '100', 'id' => 'l_school', 'class' => 'form-control mb-2', 'placeholder' => 'Your Last School Attended..']) }}
                                </div> 
                
                            </div>

                        </div>
                      </div>


                      <!--------------------------------------- File Requirements --> 

                      <div class="multisteps-form__panel p-4 rounded bg-white {{ $req ? 'js-active' : '' }}" data-animation="scaleIn">
                        <h3 style="font-family: 'Raleway', sans-serif; font-weight: 900px; color: #044716;" class="multisteps-form__title text-center">Admission Requirements</h3>
                        <div class="multisteps-form__content">
                            
                            <div class=" border rounded border-secondary mb-2">

                                <div class="form-group m-3">
                
                                    {{Form::label('idpic', '1x1 ID Picture')}}
                                    {{Form::file('id_pic', ['class' => 'form-control-file'])}}
                    
                                </div>
                
                            </div>
                
                            <div class=" border rounded border-secondary mb-2">
                
                                <div class="form-group m-3">
                
                                    {{Form::label('birthcert', 'PSA Birth Certificate')}}
                                    {{Form::file('birth_cert', ['class' => 'form-control-file '])}}
                    
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
                
                                    {{Form::label('form10', 'Grade 10 Report Card | Form 138', ['id' => 'reportCardLabel'])}}
                                    {{Form::file('report_card', ['class' => 'form-control-file ', 'id' => 'reportCard'])}}
                    
                                </div>
                
                            </div> 

                            <div class="form-check mx-auto text-center">

                              <input id="agreeCheck" style="width: 15px; height: 15px;" type="checkbox" class="form-check-input" >
                              <span>I agree to <span type="button" data-toggle="modal" data-target="#exampleModalCenter" > <b>Terms</b></span> and <span type="button" data-toggle="modal" data-target="#exampleModalCenter"><b>Conditions</b></span></span>

                            </div>                                             
                            
                            <div class = "form-group mt-3">        
                              {{Form::submit('Submit',  ['id' => 'submitbutton', 'class' => 'btn btn-primary btn-block ', 'disabled' => 'disabled'])}}
                            </div>   
                            
                            <div class="card bg-light border-info mb-3 text-center" >                              
                              <div class="card-body">                                                               
                                <p class="card-text "><i class="fa fa-info-circle mr-2 text-primary" aria-hidden="true"></i><a href="/admissionhelp" target="_blank">See Admission Requirements Guidelines</a></p>
                              </div>
                            </div>

                            *Note: to reset from the start just refresh the page

                        </div>
                      </div>

                    </form>

                   </div>
                </div>
              </div>

            {!! Form::close() !!}
            
        </div>
        
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-3" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Welcome to SMARTII.CC!
        <br>
        <br>
        These terms and conditions outline the rules and regulations for the use of St Mark Arts and Training Institute's Website, located at https://smartii.cc/.
        <br>
        <br>
        By accessing this website we assume you accept these terms and conditions. Do not continue to use SMARTII.CC if you do not agree to take all of the terms and conditions stated on this page.
        <br>
        <br>
        The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: "Client", "You" and "Your" refers to you, the person log on this website and compliant to the Company’s terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client’s needs in respect of provision of the Company’s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.
        <br>
        <br>
        Cookies
        We employ the use of cookies. By accessing SMARTII.CC, you agreed to use cookies in agreement with the St Mark Arts and Training Institute's Privacy Policy.
        <br>
        <br>
        Most interactive websites use cookies to let us retrieve the user’s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.
        <br>
        <br>
        License
        Unless otherwise stated, St Mark Arts and Training Institute and/or its licensors own the intellectual property rights for all material on SMARTII.CC. All intellectual property rights are reserved. You may access this from SMARTII.CC for your own personal use subjected to restrictions set in these terms and conditions.
        <br>
        <br>
        You must not:
        <br>
        <br>
        Republish material from SMARTII.CC
        Sell, rent or sub-license material from SMARTII.CC
        Reproduce, duplicate or copy material from SMARTII.CC
        Redistribute content from SMARTII.CC
        This Agreement shall begin on the date hereof. Our Terms and Conditions were created with the help of the Terms And Conditions Generator.
        <br>
        <br>
        Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. St Mark Arts and Training Institute does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of St Mark Arts and Training Institute,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, St Mark Arts and Training Institute shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.
        <br>
        <br>
        St Mark Arts and Training Institute reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.
        <br>
        <br>
        You warrant and represent that:
        You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;
        <br>
        <br>
        The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;
        The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy
        The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.
        You hereby grant St Mark Arts and Training Institute a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.
        <br>
        <br>
        Hyperlinking to our Content
        The following organizations may link to our Website without prior written approval:
        <br>
        <br>
        Government agencies;
        Search engines;
        News organizations;
        Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and
        System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.
        These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party’s site.
        <br>
        <br>
        We may consider and approve other link requests from the following types of organizations:
        <br>
        <br>
        commonly-known consumer and/or business information sources;
        dot.com community sites;
        associations or other groups representing charities;
        online directory distributors;
        internet portals;
        accounting, law and consulting firms; and
        educational institutions and trade associations.
        We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of St Mark Arts and Training Institute; and (d) the link is in the context of general resource information.
        <br>
        <br>
        These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party’s site.
        <br>
        <br>
        If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to St Mark Arts and Training Institute. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.
        <br>
        <br>
        Approved organizations may hyperlink to our Website as follows:
        <br>
        <br>
        By use of our corporate name; or
        By use of the uniform resource locator being linked to; or
        By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party’s site.
        No use of St Mark Arts and Training Institute's logo or other artwork will be allowed for linking absent a trademark license agreement.
        <br>
        <br>
        iFrames
        Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.
        <br>
        <br>
        Content Liability
        We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.
        <br>
        <br>
        Your Privacy
        Please read Privacy Policy
        <br>
        <br>
        Reservation of Rights
        We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it’s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.
        <br>
        <br>
        Removal of links from our website
        If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.
        <br>
        <br>
        We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.
        <br>
        <br>
        Disclaimer
        To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:
        <br>
        <br>
        limit or exclude our or your liability for death or personal injury;
        limit or exclude our or your liability for fraud or fraudulent misrepresentation;
        limit any of our or your liabilities in any way that is not permitted under applicable law; or
        exclude any of our or your liabilities that may not be excluded under applicable law.
        The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.
        <br>
        <br>
        As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')

<script>  

let step1 = document.getElementById('step1');
let step2 = document.getElementById('step2');
let step3 = document.getElementById('step3');

let selectProg = document.getElementById('selectProg');
let divProg = document.getElementById('divProg');
let hiddenDept = document.getElementById('hiddenDept');

let btnSHS = document.getElementById('btnShsSelect');
let btnColl = document.getElementById('btnCollegeSelect');
let deptOutput = document.getElementById('selected-dept');
let monitorInputs = document.getElementById('monitorInputs');   

let reportCard = document.getElementById('reportCard');
let reportCardLabel = document.getElementById('reportCardLabel');

let lName = document.getElementById('lName');
let fName = document.getElementById('fName');
let mName = document.getElementById('mName');
let dob = document.getElementById('dob');
let selectGender = document.getElementById('selectGender');
let address = document.getElementById('address');
let l_school = document.getElementById('l_school');


let department = null;
let program = null;
let theDeptOutput = '';
let theProgramOutput = '';
let agreeCheck = document.getElementById('agreeCheck');
let submitbutton = document.getElementById('submitbutton');

window.addEventListener('load', (event) => {      

  if(typeof val_dept !== 'undefined' && typeof val_prog_desc != 'undefined' && typeof val_l_name === 'undefined' ){    

    if(val_dept == 0){
      monitorInputs.className = "text-center";
      hiddenDept.value = "0";  
      step1.disabled = true;  
      selectProg.options[0].value = val_prog;

      monitorInputs.textContent = "SHS | " + val_prog_desc;

    } else {

      monitorInputs.className = "text-center";
      hiddenDept.value = "1";
      step1.disabled = true;
      selectProg.options[0].value = val_prog;

      monitorInputs.textContent = "College | " + val_prog_desc;            

    }

  } else if(typeof val_l_name !== 'undefined'){    

    step1.disabled = true;  
    step2.disabled = true;  

    if(val_dept == 0){
      monitorInputs.className = "text-center";
      hiddenDept.value = "0";  
      step1.disabled = true;  
      selectProg.options[0].value = val_prog;

      monitorInputs.textContent = "SHS | " + val_prog_desc;

    } else {

      monitorInputs.className = "text-center";
      hiddenDept.value = "1";
      step1.disabled = true;
      selectProg.options[0].value = val_prog;

      monitorInputs.textContent = "College | " + val_prog_desc;            

    }

    lName.value = val_l_name;
    fName.value = val_f_name;
    mName.value = val_m_name;
    dob.value = val_dob;    
    selectGender.options[0].value = val_gender;
    address.value = val_present_address;
    l_school.value = val_last_school;
   
  } else {

  }

}); 


agreeCheck.addEventListener('change', () => {

  if(agreeCheck.checked)
    submitbutton.disabled = false;
  else 
    submitbutton.disabled = true;

});




selectProg.addEventListener('change', () => {
    theProgramOutput = "| " + selectProg.options[selectProg.selectedIndex].text;
    updateMonitorOutput();
});

function updateMonitorOutput(){                                    
    monitorInputs.textContent = theDeptOutput + theProgramOutput;
}

function toggleDepartment(btn){
    btnSHS.className = "btn btn-primary m-1";
    btnColl.className = "btn btn-primary m-1";
    btn.className = "d-none";

    department = btn.value;

    hiddenDept.value = department;

    if(btn.value == 0){                                        
        btn.className = "btn btn-success m-1 active";
        monitorInputs.className = "text-center";
        divProg.className = "form-group text-center mt-3";
        theDeptOutput = "SHS";
        
        deptOutput.textContent = btn.textContent + " Department";

        reportCardLabel.textContent = "Grade 10 Report Card | Form 138";
    } else {                              
        monitorInputs.className = "text-center";
        
        theDeptOutput = "College";
        divProg.className = "form-group text-center mt-3";
        btn.className = "btn btn-success m-1 active";
        deptOutput.textContent = btn.textContent + " Department";

        reportCardLabel.textContent = "Grade 12 Report Card | Form 138";
    }

    monitorInputs.textContent = theDeptOutput;

    fillPrograms(department);

    updateMonitorOutput();
    
    
}                           


function fillPrograms(dept){

    let xhr = new XMLHttpRequest();
    
    xhr.open('GET', APP_URL + '/applicant/view/programs/' + dept, true);

    xhr.onload = function() {
        if (this.status == 200) {
            

            removeOptions(selectProg);
            // for(i = 0; i < selectProg.length; i++){
            //     selectProg.remove(i);
            // }

            let programs = JSON.parse(this.responseText);                                

                for (let i in programs) {                        
                    selectProg.options[i] = new Option(programs[i].desc, programs[i].id); 
                }

            } else {
            
            }   
            
            theProgramOutput = " | " + selectProg.options[selectProg.selectedIndex].text;
            updateMonitorOutput();                              

    }

    xhr.send(); 

}     


//second tab


window.onbeforeunload = function(event)
{
    return '';
};


document.getElementById("applicantForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};




const DOMstrings = {
    stepsBtnClass: 'multisteps-form__progress-btn',
    stepsBtns: document.querySelectorAll(`.multisteps-form__progress-btn`),
    stepsBar: document.querySelector('.multisteps-form__progress'),
    stepsForm: document.querySelector('.multisteps-form__form'),
    stepsFormTextareas: document.querySelectorAll('.multisteps-form__textarea'),
    stepFormPanelClass: 'multisteps-form__panel',
    stepFormPanels: document.querySelectorAll('.multisteps-form__panel'),
    stepPrevBtnClass: 'js-btn-prev',
    stepNextBtnClass: 'js-btn-next' };


//remove class from a set of items
    const removeClasses = (elemSet, className) => {

        elemSet.forEach(elem => {

            elem.classList.remove(className);

        }); 

};

//return exect parent node of the element
const findParent = (elem, parentClass) => {

  let currentNode = elem;

  while (!currentNode.classList.contains(parentClass)) {
    currentNode = currentNode.parentNode;
  }

  return currentNode;

};

//get active button step number
const getActiveStep = elem => {
  return Array.from(DOMstrings.stepsBtns).indexOf(elem);
};

//set all steps before clicked (and clicked too) to active
const setActiveStep = activeStepNum => {

  //remove active state from all the state
  removeClasses(DOMstrings.stepsBtns, 'js-active');

  //set picked items to active
  DOMstrings.stepsBtns.forEach((elem, index) => {

    if (index <= activeStepNum) {
      elem.classList.add('js-active');
    }

  });
};

//get active panel
const getActivePanel = () => {

  let activePanel;

  DOMstrings.stepFormPanels.forEach(elem => {

    if (elem.classList.contains('js-active')) {

      activePanel = elem;

    }

  });

  return activePanel;

};

//open active panel (and close unactive panels)
const setActivePanel = activePanelNum => {

  //remove active class from all the panels
  removeClasses(DOMstrings.stepFormPanels, 'js-active');

  //show active panel
  DOMstrings.stepFormPanels.forEach((elem, index) => {
    if (index === activePanelNum) {

      elem.classList.add('js-active');

      setFormHeight(elem);

    }
  });

};

//set form height equal to current panel height
const formHeight = activePanel => {

  const activePanelHeight = activePanel.offsetHeight;

  DOMstrings.stepsForm.style.height = `${activePanelHeight}px`;

};

const setFormHeight = () => {
  const activePanel = getActivePanel();

  formHeight(activePanel);
};

//STEPS BAR CLICK FUNCTION
DOMstrings.stepsBar.addEventListener('click', e => {

  //check if click target is a step button
  const eventTarget = e.target;

  if (!eventTarget.classList.contains(`${DOMstrings.stepsBtnClass}`)) {
    return;
  }

  //get active button step number
  const activeStep = getActiveStep(eventTarget);

  //set all steps before clicked (and clicked too) to active
  setActiveStep(activeStep);

  //open active panel
  setActivePanel(activeStep);
});

//PREV/NEXT BTNS CLICK
DOMstrings.stepsForm.addEventListener('click', e => {

  const eventTarget = e.target;

  //check if we clicked on `PREV` or NEXT` buttons
  if (!(eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`) || eventTarget.classList.contains(`${DOMstrings.stepNextBtnClass}`)))
  {
    return;
  }

  //find active panel
  const activePanel = findParent(eventTarget, `${DOMstrings.stepFormPanelClass}`);

  let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel);

  //set active step and active panel onclick
  if (eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`)) {
    activePanelNum--;

  } else {

    activePanelNum++;

  }

  setActiveStep(activePanelNum);
  setActivePanel(activePanelNum);

});

//SETTING PROPER FORM HEIGHT ONLOAD
window.addEventListener('load', setFormHeight, false);

//SETTING PROPER FORM HEIGHT ONRESIZE
window.addEventListener('resize', setFormHeight, false);

// function setValidated(dept, prog, personal_info = null){

//   if(dept == 0)
//     toggleDepartment(btnSHS);
//   else
//     toggleDepartment(btnColl);
    
// }



</script>

    
@endsection 