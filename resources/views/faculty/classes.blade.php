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

    <h5>No Classes yet.</h5>
            
    @else

    <div class="row">
            
        <div class="col-sm mt-2">

            <h5 class="mb-2">My Classes</h5>                          

            <ul class="list-group">
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
    

</div>

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
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


</script>


@endsection


