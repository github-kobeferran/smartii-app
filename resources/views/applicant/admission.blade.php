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
                                    {{ Form::text('l_name', '', ['id' => 'lName', 'maxLength' => '100', 'class' => 'form-control mb-2', 'placeholder' => 'Your Last Name Here..']) }}
                                    {{ Form::text('f_name', '', ['id' => 'fName','maxLength' => '100', 'class' => 'form-control mb-2', 'placeholder' => 'Your First Name Here..']) }}
                                    {{ Form::text('m_name', '', ['id' => 'mName','maxLength' => '100','class' => 'form-control mb-2', 'placeholder' => 'Your Middle Name Here..']) }}
                    
                                </div>

                                
                                <div class = "form-group text-center">     
                                    {{Form::label('dob', 'Date of Birth')}}                   
                                    {{Form::date('dob', \Carbon\Carbon::now()->subYears(15), [ 'class' => 'ml-2', 'id' => 'dob'] )}}
                                </div> 
                          
                                
                                <div class = "form-group text-center">     
                                    {{Form::label('gender', 'Gender',  ['class' => 'mt-2'])}}
                                    {{Form::select('gender', ['male' => 'Male',
                                                                'female' => 'Female',
                                                                'gay' => 'Gay',
                                                                'lesbian' => 'Lesbian',
                                                                ], null,
                                                                ['class' => 'custom-select w-50', 'id' => 'selectGender'])}}   
                                </div>

                                <div class = "form-group m-3">                                                          
                                    {{ Form::text('present_address', '', ['id' => 'address', 'class' => 'form-control mb-2', 'placeholder' => 'Your Present Address..']) }}
                                </div> 
                                <div class = "form-group m-3">                                                          
                                    {{ Form::text('last_school', '', ['id' => 'l_school', 'class' => 'form-control mb-2', 'placeholder' => 'Your Last School Attended..']) }}
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

                            <div class = "form-group mt-3">        
                              {{Form::submit('Submit',  ['class' => 'btn btn-primary btn-block '])}}
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
            
            for(i = 0; i < selectProg.length; i++){
                selectProg.remove(i);
            }

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