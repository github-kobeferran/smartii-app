@extends('layouts.module')

@section('page-title')
    My Classes
@endsection

@section('content')


<div class="container">


    <a href="createpost" class="btn btn-primary btn-block mt-3 mb-4">

        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Create Blog/Article
    
    </a>

    <hr>

    
    @if($classesThisSemester->count() < 1)
    
        <div class="row">   
            
            <div class="col-sm">

                <div class="row ">
                    <div class="col">
                        <div class="input-group mb-2">
                            <h5 id="panel-title" class="">Active Classes is empty. </h5>                                
                            <i id="icon" class="fa fa-archive text-secondary ml-2 mt-1 d-none" aria-hidden="true"></i>
                        </div>
                    </div>
                    @if ($archivedClasses->count() > 0)
                        <div class="col">
                            <div class="custom-control custom-switch float-right">
                                <input name="archive_switch" type="checkbox" class="custom-control-input" id="archive-switch">
                                <label class="custom-control-label " for="archive-switch"><strong>See Archived Classes</strong></label>
                            </div>
                        </div>
                    @endif
                </div>     

            </div>

                    
        </div>

    @else

        <div class="row">     
                
            <div class="col-sm mt-2">

                <div class="row ">
                    <div class="col ">
                        <div class="input-group mb-2">
                            <h5 id="panel-title" class="">My Classes </h5>                                
                            <i id="icon" class="fa fa-archive text-secondary ml-2 mt-1 d-none" aria-hidden="true"></i>
                        </div>
                    </div>
                    @if ($archivedClasses->count() > 0)
                        <div class="col">
                            <div class="custom-control custom-switch float-right">
                                <input name="new_stud_switch"  type="checkbox" class="custom-control-input" id="archive-switch">
                                <label class="custom-control-label " for="archive-switch"><strong>See Archived Classes</strong></label>
                            </div>
                        </div>
                    @endif
                </div>
                                        

                <ul class="list-group" id="classes-panel">
                    @foreach ($classesThisSemester as $classes_by_program)
                        <li role="button" id="btn-prog-{{$classes_by_program->first()->first()->subjectsTaken->first()->student->program->id}}" onclick="showProgramClasses({{$classes_by_program->first()->first()->subjectsTaken->first()->student->program->id}})"  class="list-group-item d-flex justify-content-between align-items-center mb-2 btn-progs">
                            <span class="text-dark" style="font-family: 'Raleway', sans-serif;">{{$classes_by_program->first()->first()->subjectsTaken->first()->student->program->desc}} </span>
                            <span class="badge badge-primary badge-pill">section count: {{$classes_by_program->count()}}</span>                        
                        </li>

                        <li id="section-list-{{$classes_by_program->first()->first()->subjectsTaken->first()->student->program->id}}" class="list-group-item section-list d-none ">

                            <ul id="" class="list-group list-group-flush"> 

                                @foreach ($classes_by_program as $classes)                                
                                    <li role="button" id="btn-section-{{$classes->first()->id}}" onclick="showSectionClasses({{$classes->first()->id}})"  class="list-group-item d-flex justify-content-between align-items-center mb-2 btn-sections">
                                        <span class="text-dark" style="font-family: 'Raleway', sans-serif; color: #363636 !important;"><i class="fa fa-caret-right" aria-hidden="true"></i> {{$classes->first()->class_name}} </span>
                                        <span class="badge badge-success badge-pill">classes: {{$classes->count()}}</span>                        
                                    </li>

                                    <li id="class-list-{{$classes->first()->id}}" class="list-group-item border border-success class-list d-none py-0 px-0">
                                        <ul class="list-group rounded-0"> 
                                            @foreach ($classes as $class)

                                                @if ($class->subjectsTaken->count() > 0)
                                                    <li class="list-group">
                                                        <a href="{{ url('/myclass/' . $class->id) }}" class="list-group-item" style="{{$class->subjectsTaken->count() == $class->subjectsTaken->where('rating', '!=', 3.5)->count() ? 'background: #d9d9d9 !important; color: black !important;': ''}}">
                                                            <i class="fa fa-caret-right ml-3" aria-hidden="true"></i> 
                                                            <em>{{$class->topic}}</em>
                                                            <?php 
                                                            
                                                            ?>
                                                        @if ($class->subjectsTaken->count() == $class->subjectsTaken->where('rating', '!=', 3.5)->count())                                                
                                                            <span class="float-right ">ready for archive</span>
                                                        @endif                                                    
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>   
                                        
                                @endforeach    
                            </ul>

                        </li>
                    @endforeach
                </ul>

            </div>   
            
        </div>
            
    @endif  
        
    @if ($archivedClasses->count() > 0)
       <div id="archive-panel" class="row d-none">
            <div class="col-lg mt-2">                    
                <div class="form-group has-search">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input  id="search-text" type="text" class="form-control" placeholder="Search Class">
                </div>                   
                
                <div id="archive-spinner" class="text-center d-none">
                    <div class="spinner-grow text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                
                <div class="table-responsive" style="max-height: 650px; overflow: auto; display:inline-block;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="bg-secondary text-white">S.Y. & Sem</th>
                                <th class="bg-secondary text-white">Class Name/Section</th>
                                <th class="bg-secondary text-white">Subject</th>
                            </tr>
                        </thead>
                        <tbody id="archive-rows">
                            @foreach ($archivedClasses as $class)
                                <tr role="button" onclick="goToArchiveClass({{$class->id}})" class="subject-taken-row">
                                    <td>{{$class->subjectsTaken->first()->from_year}} - {{$class->subjectsTaken->first()->to_year}} / {{$class->subjectsTaken->first()->semester == 1 ? '1st' : '2nd'}}</td>                                    
                                    <td>{{$class->class_name}} <span class="badge badge-light border">{{$class->student_count}} @if($class->dropped_count > 0) <span class="badge badge-secondary">{{$class->dropped_count}} @endif</span></td>
                                    <td>{{$class->subjectsTaken->first()->subject->desc}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
       </div>
    @endif
</div>

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

let classes_panel = document.getElementById('classes-panel');
let archive_panel = document.getElementById('archive-panel');
let panel_title = document.getElementById('panel-title');
let archive_switch = document.getElementById('archive-switch');
let icon = document.getElementById('icon');
let search_input = document.getElementById('search-text');
const FACULTY_ID = {!! json_encode(auth()->user()->member->member_id) !!}
let old_title_val = "";

if(search_input != null)
    search_input.addEventListener('keyup', searchArchivedClass);

if(archive_switch != null)
    archive_switch.addEventListener('change', toggleArchivePanels);

function toggleArchivePanels(){    

    if(archive_panel.classList.contains('d-none')) {
        archive_panel.classList.remove('d-none');
        icon.classList.remove('d-none');
        old_title_val = panel_title.textContent;
        panel_title.textContent = 'Archived Classes';
        classes_panel.classList.add('d-none');
    } else {
        archive_panel.classList.add('d-none');
        panel_title.textContent = old_title_val;
        icon.classList.add('d-none');
        classes_panel.classList.remove('d-none');
    }
}


function showProgramClasses(id){

    let programButtons = document.getElementsByClassName('btn-progs');
    let sectionLists = document.getElementsByClassName('section-list');

    let programButtonClicked = document.getElementById('btn-prog-'+ id);
    let sectionListToShow = document.getElementById('section-list-'+ id);    

    if(!sectionListToShow.classList.contains('d-none')){
        sectionListToShow.classList.add('d-none');

        programButtonClicked.classList.add('mb-2');
        programButtonClicked.classList.remove('active');
        programButtonClicked.childNodes[1].className = 'text-dark';
       
    } else {

        for(let i = 0; i<programButtons.length; i++){
            programButtons[i].classList.remove('active');
            programButtons[i].childNodes[1].className = 'text-dark';
            programButtons[i].classList.add('mb-2');        
        }

        for(let i = 0; i<sectionLists.length; i++){        
            sectionLists[i].classList.add('d-none');        
        }

        programButtonClicked.classList.remove('mb-2');
        programButtonClicked.classList.add('active');            
        programButtonClicked.childNodes[1].className = 'text-white';
        
        sectionListToShow.classList.remove('d-none');

    }

}



function showSectionClasses(id){
    let sectionButtons = document.getElementsByClassName('btn-sections');
    let classLists = document.getElementsByClassName('class-list');

    let sectionButtonClicked = document.getElementById('btn-section-'+ id);
    let classListToShow = document.getElementById('class-list-'+ id);    

    if(!classListToShow.classList.contains('d-none')){
        classListToShow.classList.add('d-none');

        sectionButtonClicked.classList.add('mb-2');
        sectionButtonClicked.classList.remove('active');
        sectionButtonClicked.classList.remove('bg-success');
        sectionButtonClicked.childNodes[1].className = 'text-dark';        
    } else {

        for(let i = 0; i<sectionButtons.length; i++){
            sectionButtons[i].classList.remove('active');
            sectionButtons[i].classList.remove('bg-success');
            sectionButtons[i].childNodes[1].className = 'text-dark';
            sectionButtons[i].classList.add('mb-2');        
        }

        for(let i = 0; i<classLists.length; i++){        
            classLists[i].classList.add('d-none');        
        }

        sectionButtonClicked.classList.remove('mb-2');
        sectionButtonClicked.classList.add('active');   
        sectionButtonClicked.classList.add('bg-success');                   
        
        classListToShow.classList.remove('d-none');

    }

}

async function searchArchivedClass(){
    let archive_spinner = document.getElementById('archive-spinner');
    let archive_rows = document.getElementById('archive-rows');

    if(search_input.value == '')
        txt = 'iamnotsearchingforanarchiveclass';
    else 
        txt = search_input.value;

    archive_spinner.classList.remove('d-none');

    const res = await fetch(`${APP_URL}/myarchived/${txt}/${FACULTY_ID}`);
    const classes = await res.json();

    archive_spinner.classList.add('d-none');

    console.log(archive_rows);

    
    

    let output = `<tbody id="archive-rows">`;

    for(let i in classes){
        output+=`<tr role="button" onclick="goToArchiveClass(${classes[i].id})" class="subject-taken-row">
            <td>${classes[i].subjectsTaken[0].from_year}-${classes[i].subjectsTaken[0].to_year}/${classes[i].subjectsTaken[0].semester == 1 ? `1st` : `2nd`}</td>\
            <td>${classes[i].class_name}</td>
            <td>${classes[i].subjectsTaken[0].subject.desc}</td>
        </tr>`;
    }

    output += `</tbody>`;

    archive_rows.innerHTML = output;
}

function goToArchiveClass(id){
    window.open(`${APP_URL}/myclass/${id}`,'_blank');
}

</script>
@endsection


