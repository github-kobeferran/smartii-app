<div class="container ml-0">
    {!! Form::open(['url' => 'admin/create/student', 'id' => 'studentForm']) !!}

    <div class="row">
        <div class="col-lg text-center mb-3">
            <h4 class="formal-font smartii-text-dark">Student Registration Form</h4>
        </div>
    </div>

    <div class="row ">
        <div class="col-lg-3">
        </div>
        <div class="col-lg-6">
            <div class="">
                <div class="custom-control custom-switch float-left">
                    <input name="new_stud_switch"  type="checkbox" class="custom-control-input" id="newStudSwitch">
                    <label class="custom-control-label " for="newStudSwitch"><strong>Generate Student ID</strong></label>
                </div>
                <div id="new-stud-div" class="form-group" style="display:block;">         
                    <b>{{Form::label('studentId', 'Student ID', ['class' => 'float-right'])}}</b>
                    {{Form::text('student_id', old('student_id') , ['maxlength' => '8', 'class' => 'form-control material-input', 'id' => 'studentID', 'required' => 'required', 'placeholder' => 'ex. C18-2159'])}}
                </div> 
            </div>           
            
        </div>
        <div class="col-lg-3">
        </div>
    </div>

    <hr>

    <div class="row mt-3 ">
        <div class="col-lg ">

            <div class="row no-gutters">
                <div class="col-lg-6">
                    <b>{{Form::label('department', 'Department')}}</b>
                    {{Form::select('department', ['0' => 'Senior High School', '1' => 'College'], old('department'), ['class' => 'custom-select material-input', 'id' => 'selectDept'])}}                   
                    <b>{{Form::label('level', 'Student Level',  ['class' => 'mt-3'])}}</b>
                    {{Form::select('level', [], old('department'), ['class' => 'custom-select material-input', 'id' => 'selectLevel'])}}                   
                </div>
                <div class="col-lg-6">
                    <b>{{Form::label('program', 'Program')}}</b>
                    {{Form::select('program_id', [], old('department'), ['class' => 'custom-select material-input' , 'id' => 'selectProg'])}}                                                           

                    <b>{{Form::label('semester', 'Semester',  ['class' => 'mt-2'])}}</b>
                    {{Form::select('semester', [(string)\App\Models\Setting::first()->semester => \App\Models\Setting::first()->semester > 1 ? 'Second Semester' : 'First Semester'], null, ['class' => 'custom-select material-input mt-2 ', 'id' => 'selectSemester'])}}                   
                </div>
            </div>

        </div>
    </div>

    <hr>

    <div class="row mt-3 ">

        <div class="col-lg">
            <div class="row">
                <div class="col-lg-6">
                    <b>{{Form::label('', 'Email Address', ['class' => ''])}}</b>
                    {{-- {{Form::email('email', '', ['class' => 'form-control rounded-0 material-input', 'required' => 'required', 'placeholder' => 'Email here..'])}} --}}
                    <input id="email" type="email" value="{{old('email')}}" name="email" class="form-control material-input @error('email') is-invalid @enderror" placeholder="Email here.." required>
                    
                </div>
                <div class="col-lg-3">
                    <b>{{Form::label('theContact', 'Contact Number', ['class' => ''])}}</b>
                    {{-- {{Form::text('contact', '', ['minlength' => '11', 'maxlength' => '11','class' => 'form-control material-input rounded-0 ', 'placeholder' => 'Contact Number here..', 'id' => 'contactInput'])}} --}}
                    <input id="contactInput" type="text" value="{{old('contact')}}" name="contact" minlength="11" maxlength="11" class="form-control material-input @error('contact') is-invalid @enderror" placeholder="Student's Mobile Number here.." required>
                </div>
                <div class="col-lg-3">
                    <b>{{Form::label('program', 'Sex')}}</b>
                    {{Form::select('gender', [null => 'Choose Sex', 'male' => 'Male', 'female' => 'Female'], old('gender'), ['class' => 'custom-select rounded-0 material-input' , 'id' => 'selectProg', 'required' => 'required'])}}                   
                </div>
            </div>
        </div>

    </div>

    <hr>

    <div class="row mt-3">

        <div class="col-lg-4">
            <b>{{Form::label('lastName', 'Last Name', ['class' => ''])}}</b>
            {{-- {{Form::text('last_name', '', ['class' => 'form-control material-input', 'placeholder' => 'Last Name here..', 'required' => 'required'])}} --}}
            <input name="last_name" value="{{old('last_name')}}" id="last_name" type="text" class="form-control material-input @error('last_name') is-invalid @enderror" placeholder="Last Name here.." required>            
        </div>
        <div class="col-lg-4">
            <b>{{Form::label('firstName', 'First Name')}}</b>
            {{-- {{Form::text('first_name', '', ['class' => 'form-control material-input', 'placeholder' => 'First Name here..' , 'required' => 'required'])}} --}}
            <input name="first_name" value="{{old('first_name')}}" id="first_name" type="text" class="form-control material-input @error('first_name') is-invalid @enderror" placeholder="First Name here.." required>            
        </div>
        <div class="col-lg-4">
            <b>{{Form::label('middleName', 'Middle Name')}}</b>
            {{-- {{Form::text('middle_name', '', ['class' => 'form-control material-input', 'placeholder' => 'Middle Name here..', 'required' => 'required'])}} --}}
            <input name="middle_name" value="{{old('middle_name')}}" id="middle_name" type="text" class="form-control material-input @error('middle_name') is-invalid @enderror" placeholder="Middle Name here..">            
        </div>

    </div>

    <hr>

    <div class="row mt-3">

        <div class="col-lg-8">
            <b>{{Form::label('permanent_address', 'Permanent Address')}}</b>
            {{-- {{Form::text('permanent_address', '', ['class' => 'form-control rounded-0 material-input', 'placeholder' => 'Permanent Address'])}} --}}
            <input name="permanent_address" value="{{old('permanent_address')}}" id="permanent_address" type="text" class="form-control material-input @error('permanent_address') is-invalid @enderror" placeholder="Student's Permanent Address here.." required>            
        </div>
        <div class="col-lg-4">
            <b>{{Form::label('', 'Date of Birth')}}</b>            
            {{-- {{Form::date('dob', \Carbon\Carbon::now()->subYears(15), ['class' => 'form-control rounded-0 material-input', 'id' => 'dob', 'max' => \Carbon\Carbon::now()->subYears(15)->toDateString() ] )}} --}}
            <input  id="dob" name="dob" value="{{ old('dob') ? old('dob') : \Carbon\Carbon::now()->subYears(15)->toDateString()}}" min="1903-01-01" max="{{\Carbon\Carbon::now()->subYears(15)->toDateString()}}" type="date" class="form-control material-input @error('dob') is-invalid @enderror" placeholder="" required>            
        </div>

        <?php 
            $shs_date = \Carbon\Carbon::now()->subYears(15)->toDateString();
            $col_date = \Carbon\Carbon::now()->subYears(18)->toDateString();
        ?>
        <script>                              
            let shs_max_date = {!! json_encode($shs_date) !!}
            let col_max_date = {!! json_encode($col_date) !!}
            document.getElementById('selectDept').addEventListener('change', () => {
                if(document.getElementById('selectDept').value == '0') {
                    document.getElementById('dob').setAttribute('max', shs_max_date);
                    document.getElementById('dob').value = shs_max_date;
                }
                else{
                    document.getElementById('dob').setAttribute('max', col_max_date);
                    document.getElementById('dob').value = col_max_date;
                }
            });
        </script>  

    </div>

    <hr>    

    <div class="row mt-3">

        <div class="col-lg-8">
            <b>{{Form::label('present_address', 'Present Address')}}</b>
            {{-- {{Form::text('present_address', '', ['class' => 'form-control rounded-0 material-input', 'placeholder' => 'Present Address'])}} --}}
            <input name="present_address" value="{{old('present_address')}}" id="present_address" type="text" class="form-control material-input @error('present_address') is-invalid @enderror" placeholder="Student's Present Address here.." required>            
        </div>
        <div class="col-lg-4">
            <div class="mt-2">
                <b>Transferee ?</b>
                <div class="form-check form-check-inline">  
                    {{Form::label('', 'No')}}                 
                    {{ Form::radio('transferee', '0', old('transferee') ? old('transferee') : true, ['class' => 'mb-2 ml-2'] )}}
                    {{Form::label('', 'Yes', ['class' => 'mb-2 ml-2'])}}                 
                    {{ Form::radio('transferee', '1', old('transferee') ? old('transferee') : false, ['class' => 'mb-2 ml-2'])}}
                </div>  
            </div>
            <div class="mt-2">
                <b>Student Type ?</b>
                <div class="form-check form-check-inline">  
                    {{Form::label('', 'Regular')}}                 
                    {{ Form::radio('cur_status', '0', old('cur_status') ? old('cur_status') : true, ['class' => 'mb-2 ml-2'] )}}
                    {{Form::label('', 'Irregular', ['class' => 'mb-2 ml-2'])}}                 
                    {{ Form::radio('cur_status', '1', old('cur_status') ? old('cur_status') : false, ['class' => 'mb-2 ml-2'])}}
                </div>  
            </div>
        </div>

    </div>

    <hr>    

    <div class="row mt-3">
        <div class="col-lg-8">
            @if (\App\Models\Discount::count() > 0)

                <div class="row mb-2">
                    <div class="col-2 ">
                            <b><label class="align-middle" for="">Apply a Discount</label></b>
                    </div>
                    <div class="col">
                        <select name="discount[]" value="" id="select-discount" class="custom-select rounded-0 material-input" multiple>                    
                            @foreach (\App\Models\Discount::all() as $discount)
                                <option value="{{$discount->id}}">{{$discount->description}} ({{number_format($discount->percentage, 1)}} %)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                                    
            @endif
        </div>
    </div>
    
    <hr>

    <div class="row mt-3">
        <div class="col-lg">
            <h5 style="font-size: 2em;">Subjects</h5>
            <span class="lead"><em> leave empty for default values in settings</em></span>

            <div class="table-responsive">
                <table class="table table-striped h-50" id="subjects-table">
                    
                </table>
            </div>
        </div>
    </div>

    <hr>

    <div class="row mt-3">
        <div class="col-lg mb-3">
            {{Form::submit('REGISTER STUDENT',  ['class' => 'material-btn btn btn-block btn-success w-75 mx-auto mt-3'])}}
        </div>
    </div>
    
    {!! Form::close() !!}
</div>

<script>

let selectDept = document.querySelector('#selectDept');
let selectProg = document.querySelector('#selectProg');   
let selectLevel = document.querySelector('#selectLevel');   
let selectSemester = document.querySelector('#selectSemester');   
let contactInput = document.querySelector('#contactInput');   

contactInput.addEventListener("keypress", function (evt) {
    if (evt.which < 48 || evt.which > 57)
    {
        evt.preventDefault();
    }
}); 

document.querySelector('#newStudSwitch').addEventListener('click', () => {        
    toggleStudentID();
});


function toggleStudentID(){
let idDiv = document.getElementById('new-stud-div');
    if(idDiv.style.display == 'none') {
        idDiv.style.display = 'block';
        document.querySelector('#studentID').required = true;
        // document.querySelector('#newStudSwitch').textContent = "Change to Existing Student Form";
    } else {
        idDiv.style.display =  'none';
        document.querySelector('#studentID').required = false;
        document.querySelector('#studentID').value = "";
    }
}
    

selectDept.addEventListener('change', () => {                    
    changeSelect();
    changeTable();
});

selectProg.addEventListener('change', () => {                        
    changeTable();    
});

selectLevel.addEventListener('change', () => {                        
    changeSelect(true);        
});

selectSemester.addEventListener('change', () => {                        
    changeTable();

});

function changeSelect(isSelectLevel = false){
    
    let dept = selectDept.value;
      

    if(!isSelectLevel){
        if(dept == 0){     
            selectLevel.options[0] = new Option('Grade 11', '1');
            selectLevel.options[1] = new Option('Grade 12', '2');
        } else {
            selectLevel.options[0] = new Option('First Year', '11');
            selectLevel.options[1] = new Option('Second Year', '12');                  
        } 
    } else {
        
    }

    
    if(isSelectLevel){
        changeTable();
    } else {

        var xhr = new XMLHttpRequest();
        
        xhr.open('GET', APP_URL + '/admin/view/programs/department/' + dept, true);

        xhr.onload = function() {
            if (this.status == 200) {
                
                for(i = 0; i < selectProg.length; i++){
                    selectProg.remove(i);
                }

                var programs = JSON.parse(this.responseText);                                

                    for (let i in programs) {                        
                        selectProg.options[i] = new Option(programs[i].abbrv + ' - ' + programs[i].desc, programs[i].id); 
                    }

                } else {
                
                }
                changeTable();

        }

        xhr.send(); 

    }
           
}

// table change per select
async function changeTable(){
    
    let dept = selectDept.value;
    let program = selectProg.value;
    let level = selectLevel.value;
    let semester = selectSemester.value;

    const res = await fetch(APP_URL + '/admin/view/programs/' + program);
    const selected_prog = await res.json();    

    var xhr = new XMLHttpRequest();   
    xhr.open('GET', APP_URL + '/admin/view/subjects'
                    +'/department/' + dept 
                    + '/program/' + program 
                    + '/level/' + level 
                    + '/semester/' + semester 
                    + (selected_prog.is_tesda ? '/1' : ''), true);
    
    xhr.onload = function() {
        if (this.status == 200) {
            let results = JSON.parse(this.responseText);

            let year =  new Date();
            year = year.getFullYear();        

            let output = `<table class="table table-striped h-50" id="subjects-table">
                            <thead class="bg-warning ">
                                <tr>
                                <th scope="col">Action</th>
                                <th scope="col" class="text-center bg-info border-left">Rating</th>
                                <th scope="col" class="text-center bg-info border-left">Academic Year</th>
                                <th scope="col" class="text-center bg-info border-left border-right">Sem</th>
                                <th scope="col">Code</th>
                                <th scope="col">Description</th>
                                <th scope="col">Program</th>
                                
                                </tr>
                            </thead>
                            <tbody>`;
                            
                            for(let i in results.subjects){
                                output+=`<tr id="tr-${results.subjects[i].id}"
                                            <td><input name="subjects[]" type="hidden" value="${results.subjects[i].id}"></td>                                            
                                            <td><button data-toggle="tooltip" data-placement="top" title="Toggle Subject" type="button" onclick="subjectToggle(this, document.getElementById('tr-${results.subjects[i].id}'))" class="btn btn-light border">Enabled</button></td>
                                            <td class="pl-1 pt-3 border-left border-light"><input name="ratings[]" class="form-control border-light text-center" type="number" min="1" max="5"  step="0.25" placeholder="grade" ></td>
                                            <td class="pl-1 pt-3 border-left border-light">                        
                                               <div class="input-group"> 
                                                    <input name="from_years[]" class="form-control border-light text-center" type="number" min="2010" max="${year}" placeholder="from" >
                                                    <span> - </span>
                                                    <input name="to_years[]" class="form-control border-light text-center" type="number" min="2010" max="${year + 1}" placeholder="to" >
                                               </div>
                                            </td>
                                            <td class="pl-1 pt-3 border-left border-right border-light"><input type="number" class="form-control border-light" min="1" max="2" name="semesters[]" placeholder="sem"></td>
                                            <th scope="row">${results.subjects[i].code}</th>
                                            <td>${results.subjects[i].desc}</td>
                                            <td>${results.subjects[i].program.abbrv}</td>
                                        </tr>`;
                            }                       

            output+=`</tbody>
            </table>`;
                                            
            document.getElementById('subjects-table').innerHTML = output;

        } else {
        
        }

    }

        xhr.send(); 
}

window.onbeforeunload = function(event)
{
    return '';
};

document.getElementById("studentForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};

function subjectToggle(el, row){
    
    let status = el.textContent;

    if(status == 'Enabled'){
        el.className = "btn btn-danger border";
        el.textContent = "Disabled";
       
        let rowElements =row.attributes[0].ownerElement.children;

        for(let i=0; i<rowElements.length; i++){            
            if(rowElements[i].children.length > 0){                                            

                let children = rowElements[i].children;
                for(let j=0; j<children.length; j++){
                
                    children[j].value = '';
                    
                    if(children[j].type == 'number')
                        children[j].disabled = true;   

                    if(children[j].children.length > 0){
                        
                        for(let i in children[j].children){
                            children[j].children[i].disabled = true;
                        }
                    }
                }
                
            } else {
                rowElements[i].style.color = '#c2c2c2';
                if(i == 0){
                    rowElements[i].name = '';
                }                
            }
                
                
            
        }
    } else {
        el.className = "btn btn-light border";
        el.textContent = "Enabled";

        let rowElements =row.attributes[0].ownerElement.children;

        for(let i=0; i<rowElements.length; i++){
            if(rowElements[i].children.length > 0){
                
                let children = rowElements[i].children;
                for(let j=0; j<children.length; j++){
                    
                    if(children[j].type == 'number'){
                        children[j].disabled = false;
                        
                    }

                    if(children[j].children.length > 0){
                        for(let i in children[j].children){
                            children[j].children[i].disabled = false;
                        }
                    }
                }
            } else {
                rowElements[i].style.color = '#474644';
                if(i == 0){
                    rowElements[i].name = 'subjects[]';
                }
            }
        }
    
    }
}



</script>