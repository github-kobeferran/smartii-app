@extends('layouts.module')

@section('page-title')
    {{$class->class_name}}
@endsection

@section('content')

<div class="container">

    <script>
        var CLASS_ID = {!! json_encode($class->id) !!}    
        var FACULTY_ID = {!! json_encode(auth()->user()->member->member_id) !!}    
        var FROM_CUR_SEM = {!! json_encode($class->from_cur_sem) !!}    
        var FROM_LAST_SEM = {!! json_encode($class->from_last_sem) !!}            
    </script>

    <div class="row ">
        <div class="col-sm" @if ($class->archive) style="display: none;" @endif>
            <a class="btn-back float-left mt-2" href="{{route('facultyClasses')}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> My Classes</a>    
        </div>

        <div class="col-sm" @if ($class->archive) style="display: none;" @endif>
            <button id="btn-archive" class="btn btn-secondary btn-sm float-right mt-2 d-none" data-toggle="modal" data-target="#confirmArchive"><i class="fa fa-archive" aria-hidden="true"></i> Archive this Class</button>
        </div>        
          
          <!-- Modal -->
          <div class="modal fade" id="confirmArchive" tabindex="-1" role="dialog" aria-labelledby="confirmArchiveLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Confirm Archive</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-justify">
                  Archive this class?
                  {{$class->class_name}}
                  {{$class->topic}}
                </div>
                <div class="modal-footer">
                    
                    {!! Form::open(['url' => '/archiveclass']) !!}
                    {{Form::hidden('class_id', $class->id)}}
                    {{Form::hidden('faculty_id', auth()->user()->member->member_id)}}
                    <button type="submit" class="btn btn-secondary">Confirm</button>
                    {!! Form::close() !!}                  
                    
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>

        
    </div>

    <div class="row mt-2">
            
        <div class="col-sm mx-auto text-center">

            <h5 class="mb-2">{{$class->topic}} Class </h5>            
            <p class="mb-2"> of {{$class->class_name}} </p>  

            @foreach ($schedules as $sched)

                <div> 
                    {{$sched->day_name}}  {{$sched->formatted_start}} - {{$sched->formatted_until}} {{$sched->room_name}}
                </div>
                
            @endforeach

            @if ($class->archive)
                A.Y of {{$class->subjectsTaken->first()->from_year}} - {{$class->subjectsTaken->first()->to_year}} / {{$class->subjectsTaken->first()->semester == 1 ? '1st semester' : '2nd semseter'}}
            @endif
                        
            <h4 class="mt-5">Students List <span style="font-size: .5em;" class="badge badge-light border">{{$class->student_count}}</span> </h4>   

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @include('inc.messages')


            <div class="dropdown float-left mb-2">

                <button class="btn btn-light border dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Sort By
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <button class="dropdown-item" onclick="selectTable('alpha')">Alphabetical</button>              
                  <button class="dropdown-item" onclick="selectTable('id')">Student ID</button>              
                  <button class="dropdown-item" onclick="selectTable('rating')">Rating</button>              
                </div>

                <span id="cur-sort" class="ml-2">                    
                </span>

            </div>            
            
            <div class="row no-gutters float-right mb-2">

                <div class="col">
                    <button class="badge badge-pill badge-{{$class->archive ? 'secondary text-white' : 'success text-dark'}} " type="button" data-toggle="modal" data-target="#class-actions">Actions</button>
                </div>                
            </div>           

            <div class="modal fade" id="class-actions" tabindex="-1" role="dialog" aria-labelledby="class-actions-title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    @if ($class->archive) 
                        <div class="modal-content">
                            <div class="modal-header bg-secondary">
                                <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">Class Actions</span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-left">
                                <h5><span class="text-dark">Export Archived Class Student List</span></h5>                  
                                <em>Downloads a Excel File of this archived class Student List</em>
                                <br>
                                <a href="/myarchivedclass/{{$class->id}}/export" class="btn btn-secondary rounded-0 mb-2 float-right" type="button" data-toggle="tooltip" title="Export to Excel" aria-haspopup="true" aria-expanded="false">
                                    Export Student List 
                                </a> 
                            </div>
                        </div>
                    @else
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">Class Actions</span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row border-bottom">
                                    <div class="col text-left">              
                                        <h5>Export</h5>                  
                                        <em>Downloads a Excel File of this class Student List</em>
                                        <br>
                                        <a href="/myclass/{{$class->id}}/export" class="btn btn-success rounded-0 mb-2 float-right" type="button" data-toggle="tooltip" title="Export to Excel" aria-haspopup="true" aria-expanded="false">
                                            Export Student List
                                        </a> 
                                    </div>                            
                                </div>
                                <div class="row">
                                    <div class="col text-left">
                                        <h5>Import</h5>   
                                        {!!Form::open(['url' => '/storeratings', 'files' => true])!!}
                                            <em>Inputting the Rating of Students by uploading an <b>excel file</b></em>
                                            <em>You must exactly have a '<b>Student ID</b>' and '<b>Rating</b>' column in your excel file.</em>
                                            <em><span class="text-danger">Rows with invalid rating values</span> such ratings <b>less than one</b>, <b>greater than five</b>, <b>3.1 - 3.9, 4.1-4.9</b> and <b>not divisible by .25</b>
                                            are going to be <u>ignored</u>.
                                            </em>
                                            <br>
                                            <br>
                                            <h5><span class="text-primary"><em>You are going to change the student ratings of <span class="text-dark">{{$class->class_name}} - {{$class->topic}}.</span></em></span></h5>
                                            <br>
                                            <div class="p-2 border border-primary">
                                                <span class="text-muted float-right">upload excel file</span>
                                                {{Form::file('ratings', ['class' => 'material-input form-control-file', 'required' => 'required'])}}
                                                {{Form::hidden('class_id', $class->id)}}
                                                <button type="submit" class="btn btn-primary mt-2">Import Ratings</button> 
                                            </div>
                                        {!!Form::close()!!}
                                    </div>
                                </div>
                            </div>                
                        </div>

                    @endif
                </div>
            </div>
            
           <div class="table-responsive">
                <table class="table table-bordered">
                    <thead @if ($class->archive) class="bg-secondary text-white" @else class="green-bg-light" @endif>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th class="border-left">Final Rating</th>
                        </tr>
                    </thead>
                    <tbody id="students-table">
                    
                    </tbody>
                </table>
           </div>

           <div id="modals">

           </div>
                              
        </div>

    </div>
                   
    

</div>

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

window.onload = function() {

    selectTable('alpha');

}

let current_sort = document.getElementById('cur-sort')

function selectTable(mode){    

    let btnArchive = document.getElementById('btn-archive');
    let availForArchive = true;
    let studentList = document.getElementById('students-table');
    let modal_div = document.getElementById('modals');

    switch(mode){
        case 'alpha':
            current_sort.textContent = 'Alphabetical';
        break;
        case 'id':
            current_sort.textContent = 'Student ID';
        break;
        case 'rating':
            current_sort.textContent = 'Rating';
        break;
    }

    let xhr = new XMLHttpRequest();
        xhr.open('GET', APP_URL + '/sortclass/'+ CLASS_ID +'/' +  FACULTY_ID + '/' + mode, true);

        xhr.onload = function() {
            if (this.status == 200) {

                students = JSON.parse(this.responseText);                

                output = '<tbody id="students-table">';
                                
                for(let i in students){
                                                           
                    output+= `<tr>
                        <td>`;
                    if(typeof students[i].drop_request != null && typeof students[i].drop_request != 'undefined')   {
                        switch(students[i].drop_request.status){
                            case 0:
                                output+= `<span class="float-left text-secondary">drop request pending</span>`;
                                break;
                            // case 1:
                            //     output+= `<span class="float-left text-secondary">drop request pending</span>`;
                                break;
                            case 2:
                                output+= `<span class="float-left text-danger">drop request denied ${students[i].drop_request.reject_reason != null ? `(` +students[i].drop_request.reject_reason  +`)` : ``}</span>`;                                
                                break;
                            default: 
                            
                        }                        
                    } else {
                        if(students[i].rating == 3.5){
                            output+= `<span role="button" data-toggle="modal" data-target="#drop-${students[i].id}" class="badge badge-danger float-left" >DROP</span>`;
                        }
                    }                

                    output+=`<a href="{{url('studentprofile/${students[i].student_id}')}}">${students[i].student_id}</a>
                        </td>
                        <td>${students[i].first_name} ${students[i].last_name}</td>`;

                    if(students[i].rating == 3.5){
                        output+= '<td id="rating-' + students[i].id + '" class="btn-input border-left"><button onclick="selectRating('+ students[i].id +')" class="btn btn-primary">Input Rating</button></td>';
                        availForArchive = false;
                    }else{                                                                        
                        if(students[i].rating == 4){                            
                            output+= `<td id="rating-${students[i].id}" class="btn-input border-left rated"><span class="rating-text"> INC </span>`;

                                let is_archived = false;                                
                                let sub_taken_id = 0;
                                let request = null;
                                students[i].subject_taken.forEach(subject_taken => {                        
                                    if(subject_taken.class_id == CLASS_ID){
                                        if(subject_taken.class.archive == 1){
                                            is_archived = true;
                                            sub_taken_id = subject_taken.id;
                                            if(typeof subject_taken.request_rating_update != null && typeof subject_taken.request_rating_update != 'undefined')
                                            request = subject_taken.request_rating_update;
                                        }                                        
                                    }
                                });
                                
                            if(is_archived){
                                if(request != null){
                                    switch(request.status){
                                        case 0:
                                            output+=`<span class="text-primary">Request Pending</span></td>`;
                                        break; 
                                        case 1:
                                            output+=`<span class="text-success">Request approved by Admin ${request.admin.name}</span></td>`;
                                        break; 
                                        case 2:
                                            output+=`<span class="text-danger">Request rejected by Admin ${request.admin.name} ${request.reject_reason != null ? `"${request.reject_reason}"` : ``}</span></td>`;
                                        break; 
                                    }                                
                                } else {
                                    if(FROM_CUR_SEM == true || FROM_LAST_SEM == true)
                                        output+=`<button class="badge badge-pill badge-primary d-inline" type="button" data-toggle="modal" data-target="#request-rating-edit-${sub_taken_id}">Request to Edit</button></td>`;
                                }
                            } else {
                                output+=`<a onclick="selectRating(${students[i].id}, ${students[i].rating})" data-toggle"tooltip" data-placement="top" title="Edit Rating"><i class="fa fa-pencil-square edit-rating" aria-hidden="true"></i></a></td>`;
                            }

                        } else {

                            output+= `<td id="rating-${students[i].id}" class="btn-input border-left rated"><span class="rating-text"> ${students[i].rating} </span>`;
                            let is_archived = false;
                            let sub_taken_id = 0;
                            let request = null;
                            students[i].subject_taken.forEach(subject_taken => {                        
                                if(subject_taken.class_id == CLASS_ID){
                                    if(subject_taken.class.archive == 1){
                                        is_archived = true;
                                        sub_taken_id = subject_taken.id;
                                        if(typeof subject_taken.request_rating_update != null && typeof subject_taken.request_rating_update != 'undefined')
                                            request = subject_taken.request_rating_update;
                                    }
                                }
                            });

                            if(is_archived){
                                if(request != null){
                                    switch(request.status){
                                        case 0:
                                            output+=`<span class="text-primary">Request pending</span></td>`;
                                        break; 
                                        case 1:
                                            output+=`<span class="text-success">Request approved by Admin ${request.admin.name}</span></td>`;
                                        break; 
                                        case 2:
                                            output+=`<span class="text-danger">Request rejected by Admin ${request.admin.name} ${request.reject_reason != null ? `"${request.reject_reason}"` : ``}</span></td>`;
                                        break; 
                                    }                                
                                } else {
                                    if(FROM_CUR_SEM == true || FROM_LAST_SEM == true)
                                        output+=`<button class="badge badge-pill badge-primary d-inline" type="button" data-toggle="modal" data-target="#request-rating-edit-${sub_taken_id}">Request to Edit</button></td>`;
                                }
                            } else {
                                output+=`<a onclick="selectRating(${students[i].id}, ${students[i].rating})" data-toggle"tooltip" data-placement="top" title="Edit Rating"><i class="fa fa-pencil-square edit-rating" aria-hidden="true"></i></a></td>`;
                            }
                        }
                    }
                    output+= '</tr>';

                }

                modal_output = `<div id="modals">`;

                for(let i in students){
                    modal_output += `
                    <div class="modal fade" id="drop-${students[i].id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">REQUEST DROP ${students[i].first_name} ${students[i].last_name}</span></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                {!! Form::open(['url' => '/requestdrop']) !!}
                                    <div class="modal-body text-justify">
                                        <p>Request Drop for <b>${students[i].first_name} ${students[i].last_name}</b> [${students[i].student_id}]?</p>                                        
                                        <input type="hidden" name="student_id" value="${students[i].id}">
                                        <input type="hidden" name="class_id" value="${CLASS_ID}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Request Drop for ${students[i].last_name}</button>
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                    </div>
                                {!!Form::close()!!}
                            </div>
                        </div>
                    </div>
                    `;

                    students[i].subject_taken.forEach(subject_taken => {                        
                        if(subject_taken.class_id == CLASS_ID){

                            cur_rating = '';

                            switch(subject_taken.rating){
                                case 4: 
                                    cur_rating = 'INC'; 
                                break;
                                case 5: 
                                    cur_rating = 'Failed'; 
                                break;
                                default:
                                    cur_rating = subject_taken.rating;
                                break;
                            }

                            modal_output+=`<div class="modal fade" id="request-rating-edit-${subject_taken.id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-secondary">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle"><span class="text-white">Send Request for Rating Modification for ${students[i].first_name} ${students[i].last_name}</span></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        {!!Form::open(['url' => 'requestratingupdate'])!!}
                                                            <div class="modal-body text-justify">
                                                                Request to change rating of  ${students[i].student_id} - ${students[i].first_name} ${students[i].last_name}
                                                                <p><b>Current rating: ${cur_rating} </b></p>
                                                                <p><b>Change to: </b></p>
                                                                <input type="hidden" name="id" value="${subject_taken.id}">
                                                                <select name="rating" class="custom-select" required>                                                                
                                                                <option value="" selected>Choose...</option>`;
                                                                if(subject_taken.rating != 1)
                                                                    modal_output+=`<option value="1">1</option>`;
                                                                if(subject_taken.rating != 1.25)
                                                                    modal_output += `<option value="1.25">1.25</option>`;
                                                                if(subject_taken.rating != 1.50)    
                                                                    modal_output +=`<option value="1.50">1.50</option>`;                                                                    
                                                                if(subject_taken.rating != 1.75)    
                                                                    modal_output += `<option value="1.75">1.75</option>`;
                                                                if(subject_taken.rating != 2)    
                                                                    modal_output += `<option value="2">2</option>`;
                                                                if(subject_taken.rating != 2.25)    
                                                                    modal_output += `<option value="2.25">2.25</option>`;                                                                    
                                                                if(subject_taken.rating != 2.50)
                                                                    modal_output+= `<option value="2.50">2.50</option>`;
                                                                if(subject_taken.rating != 2.75)
                                                                    modal_output+= `<option value="2.75">2.75</option>`;
                                                                if(subject_taken.rating != 3)
                                                                    modal_output += `<option value="3">3</option>`;                                                                    
                                                                if(subject_taken.rating != 4)
                                                                    modal_output += `<option value="4">INC/Deferred</option>`;
                                                                if(subject_taken.rating != 5)
                                                                    modal_output += `<option value="5">5/Failed</option>`;
                                                modal_output+=`</select>
                                                            </div>
                                                            <div class="modal-footer py-0">
                                                                <button type="submit" class="btn btn-primary">Send Request to Registrar</button>
                                                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        {!!Form::close()!!}
                                                    </div>
                                                </div>
                                            </div>`;
                        }
                    });

                }

                modal_output += `</div>`;
                modal_div.innerHTML = modal_output;

                if(availForArchive){
                    btnArchive.classList.remove('d-none');
                }else{
                    btnArchive.classList.add('d-none');
                }

                output +='</tbody>';

                studentList.innerHTML = output;
                
            }else {

                studentList.innerHTML = 'No Data';

            }


        }

        xhr.send(); 

       


}

function selectRating(id, rating = null){    

    let btnInputs = document.getElementsByClassName('btn-input');
    let inputTd = document.getElementById('rating-' + id);

    for(let i=0; i<btnInputs.length; i++){    
        let studentID = btnInputs[i].id.replace('rating-','');
        
        if(!btnInputs[i].classList.contains('rated')){

            let output = `<td id="rating-`+ studentID +`" class=" btn-input"><button onclick="selectRating(${studentID})" class="btn btn-primary">Input Rating</button></td>`;
            btnInputs[i].innerHTML = output;

        }

        
    }

    let studentID = inputTd.id.replace('rating-','');            

    let output = `{!! Form::open(['url' => '/faculty/updaterating/']) !!}

        <input type="hidden" name="stud_id" value = "`+ studentID +`"/>
        <input type="hidden" name="class_id" value = "`+ CLASS_ID +`"/>
        <div class="input-group">
            <select name = "rating" class="custom-select" required>
            <option  value="" selected>Choose...</option>
            <option value="1">1</option>
            <option value="1.25">1.25</option>
            <option value="1.50">1.50</option>
            <option value="1.75">1.75</option>
            <option value="2">2</option>
            <option value="2.25">2.25</option>
            <option value="2.50">2.50</option>
            <option value="2.75">2.75</option>
            <option value="3">3</option>
            <option value="4">INC/Deferred</option>
            <option value="5">5/Failed</option>
            </select>
            <div class="input-group-append">
            <button class="btn btn-outline-success" type="submit"><i class="fa fa-check" aria-hidden="true"></i></button>`;

            if(rating == null)
    output+=`<button onclick="cancelRating(`+ studentID +`)" class="btn btn-outline-danger" type="button">Cancel</button>`;
            else
    output+=`<button onclick="cancelRating(`+ studentID +`,`+ rating +`)" class="btn btn-outline-danger" type="button">Cancel</button>`;


    output+=`</div>
        </div>

        {!! Form::close() !!}`;

    inputTd.innerHTML = output;
    

}

function cancelRating(id, rating = null){

    if(rating ==  null){

        let inputTd = document.getElementById('rating-' + id);
        let output = `<td id="rating-`+ id +`" class=" btn-input"><button onclick="selectRating(${id})" class="btn btn-primary">Input Rating</button></td>`;

        inputTd.innerHTML = output;

    } else {

        let inputTd = document.getElementById('rating-' + id);
        let output = ``;

        if(rating == 4)
            output = `<td id="rating-`+ id +`" class=" btn-input"><span class="rating-text"> INC </span>   <a onclick="selectRating(${id}, ${rating})" data-toggle"tooltip" data-placement="top" title="Edit Rating"><i class="fa fa-pencil-square edit-rating" aria-hidden="true"></i></a></td>`;
        else
            output = `<td id="rating-`+ id +`" class=" btn-input"><span class="rating-text"> `+ rating +`</span>   <a onclick="selectRating(${id}, ${rating})" data-toggle"tooltip" data-placement="top" title="Edit Rating"><i class="fa fa-pencil-square edit-rating" aria-hidden="true"></i></a></td>`;


        inputTd.innerHTML = output;

    }

    

}

</script>


@endsection


