@extends('layouts.module')


@section('content')


<div class="container">

    <script>
        var CLASS_ID = {!! json_encode($class->id) !!}    
        var FACULTY_ID = {!! json_encode(auth()->user()->member->member_id) !!}    
    </script>

    <div class="row ">
        <div class="col-sm">
            <a class="btn-back float-left mt-2" href="{{route('facultyClasses')}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> My Classes</a>    
        </div>
        <div class="col-sm">
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
                <div class="modal-body">
                  This will archive the class details and can be only accessed by the admin. 

                    <br>
                    <br>

                  {{$class->class_name}}
                  <br>
                  {{$class->topic}}
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                  {!! Form::open(['url' => '/archiveclass']) !!}
                  {{Form::hidden('class_id', $class->id)}}
                  {{Form::hidden('faculty_id', auth()->user()->member->member_id)}}
                  <button type="submit" class="btn btn-primary">Confirm</button>
                  {!! Form::close() !!}                  

                </div>
              </div>
            </div>
          </div>

        
    </div>

    <div class="row mt-2">
            
        <div class="col-sm mx-auto text-center">

            <h5 class="mb-2">{{$class->topic}} Class </h5>            
            <p class="mb-2"> of {{$class->class_name}}</p>  

            @foreach ($schedules as $sched)

                <div> 
                    {{$sched->day_name}}  {{$sched->formatted_start}} - {{$sched->formatted_until}} {{$sched->room_name}}

                </div>
                
            @endforeach

            
            
            <h4 class="mt-5">Students List </h4>   

          


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

            </div>            
            
            <div class="row no-gutters float-right mb-2">

                <div class="col">
                    <a href="/myclass/{{$class->id}}/export" class="btn btn-success rounded-0" type="button" data-toggle="tooltip" title="Export to Excel" aria-haspopup="true" aria-expanded="false">
                       Export
                    </a> 
                </div>

                {{-- <div class="col">
                    <button class="btn btn-primary rounded-0" type="button" data-toggle="tooltip" title="Import Excel File" aria-haspopup="true" aria-expanded="false">
                       Import
                    </button> 
                </div> --}}
                
            </div>           
            
            <table class="table">
                <tr>

                    <thead>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th class="border-left">Final Rating</th>
                    </thead>

                </tr>

                <tbody id="students-table">
                
                </tbody>


            </table>
                              
       
               
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

function selectTable(mode){    

    let btnArchive = document.getElementById('btn-archive');
    let availForArchive = true;
    let studentList = document.getElementById('students-table');

    let xhr = new XMLHttpRequest();
        xhr.open('GET', APP_URL + '/sortclass/'+ CLASS_ID +'/' +  FACULTY_ID + '/' + mode, true);

        xhr.onload = function() {
            if (this.status == 200) {

                students = JSON.parse(this.responseText);

                console.log(students);

                output = '<tbody id="students-table">';
                                
                for(let i in students){
                    
                    
                    output+= '<tr>';
                        output+= `<td><a href="{{url('studentprofile/` + students[i].student_id + `')}}"> ` + students[i].student_id + `</a></td>`;
                        output+= '<td>'+ students[i].first_name + ' ' + students[i].last_name +'</td>';

                    if(students[i].rating == 3.5){
                        output+= '<td id="rating-' + students[i].id + '" class="btn-input border-left"><button onclick="selectRating('+ students[i].id +')" class="btn btn-primary">Input Rating</button></td>';
                        availForArchive = false;
                    }else{                                                
                        
                        if(students[i].rating == 4)
                            output+= '<td id="rating-' + students[i].id + '" class="btn-input border-left rated"><span class="rating-text"> INC </span><a onclick="selectRating('+ students[i].id +', '+ students[i].rating +')" data-toggle"tooltip" data-placement="top" title="Edit Rating"><i class="fa fa-pencil-square edit-rating" aria-hidden="true"></i></a></td>';
                        else
                            output+= '<td id="rating-' + students[i].id + '" class="btn-input border-left rated"><span class="rating-text"> '+ students[i].rating +'</span>   <a onclick="selectRating('+ students[i].id +', '+ students[i].rating +')" data-toggle"tooltip" data-placement="top" title="Edit Rating"><i class="fa fa-pencil-square edit-rating" aria-hidden="true"></i></a></td>';

                    }
                        
                    output+= '</tr>';

                }

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

            let output = `<td id="rating-`+ studentID +`" class=" btn-input"><button onclick="selectRating(`+ studentID +`)" class="btn btn-primary">Input Rating</button></td>`;
            btnInputs[i].innerHTML = output;

        }

        
    }

    let studentID = inputTd.id.replace('rating-','');            

    let output = `{!! Form::open(['url' => '/faculty/updaterating/']) !!}

        <input type="hidden" name="stud_id" value = "`+ studentID +`"/>
        <input type="hidden" name="class_id" value = "`+ CLASS_ID +`"/>
        <div class="input-group ">
            <select name = "rating" class="custom-select " id="inputGroupSelect04">
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
        let output = `<td id="rating-`+ id +`" class=" btn-input"><button onclick="selectRating(`+ id +`)" class="btn btn-primary">Input Rating</button></td>`;

        inputTd.innerHTML = output;

    } else {

        let inputTd = document.getElementById('rating-' + id);
        let output = ``;

        if(rating == 4)
            output = `<td id="rating-`+ id +`" class=" btn-input"><span class="rating-text"> INC </span>   <a onclick="selectRating(`+ id +`, ` + rating +`)" data-toggle"tooltip" data-placement="top" title="Edit Rating"><i class="fa fa-pencil-square edit-rating" aria-hidden="true"></i></a></td>`;
        else
            output = `<td id="rating-`+ id +`" class=" btn-input"><span class="rating-text"> `+ rating +`</span>   <a onclick="selectRating(`+ id +`, ` + rating +`)" data-toggle"tooltip" data-placement="top" title="Edit Rating"><i class="fa fa-pencil-square edit-rating" aria-hidden="true"></i></a></td>`;


        inputTd.innerHTML = output;

    }

    

}

</script>


@endsection


