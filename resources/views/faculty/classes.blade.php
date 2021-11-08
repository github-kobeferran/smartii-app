@extends('layouts.module')


@section('content')


<div class="container">


    <a href="createpost" class="btn btn-primary btn-block mt-3 mb-4">

        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Create Blog/Article
    
    </a>

    <hr>

    @if($classesByProgram->count() < 1)

    <h5>No Classes yet.</h5>
            
    @else

    <div class="row">
            
        <div class="col-sm mt-2">

            <h5 class="mb-2">My Classes</h5>                      
                              
            <ul class="list-group ">

                @for ($i = 0; $i < $classesByProgram->count(); $i++)

                    <li role="button" id="btn-{{$programs[$i]->id}}" onclick="showClasses({{$programs[$i]->id}})" class="list-group-item d-flex justify-content-between align-items-center mb-2 btn-progs">
                        <span class="text-dark" style="font-family: 'Raleway', sans-serif; color: #363636 !important;">{{$programs[$i]->desc}} </span>
                        <span class="badge badge-primary badge-pill">{{count($classesByProgram[$programs[$i]->id])}}</span>
                    </li>    
                    
                    <li id="classlist-{{$programs[$i]->id}}" class="list-group-item border border-primary class-list d-none pt-1 px-0 pb-0">                                                

                        <ul id="" class="list-group border border-info"> 

                            @for ($j = 0; $j < count($classesByProgram[$programs[$i]->id]); $j++)                            

                                <li class="list-group-item">                                    
                                    <a href="{{ url('/myclass/' . $classesArray[$i][$j]->id) }}" class="list-group-item "><b class="text-dark">{{$classesArray[$i][$j]->class_name}} - </b> <em>{{$classesArray[$i][$j]->topic}}</em></a>
                                </li>
    
                            @endfor   
    
                        </ul>

                    </li>                                  
                
                @endfor 

                           
              </ul>              
               
        </div>

    </div>


        
    @endif            
    

</div>

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}


function showClasses(id){
    btns = document.getElementsByClassName('btn-progs');
    classlists = document.getElementsByClassName('class-list');

    btn = document.getElementById('btn-'+ id);
    classlist = document.getElementById('classlist-'+ id);

    for(let i = 0; i<btns.length; i++){
        btns[i].classList.remove('active');
        btns[i].classList.add('mb-2');        
    }

    console.log(classlists);
    console.log(classlist);

    for(let i = 0; i<classlists.length; i++){        
        classlists[i].classList.add('d-none');        
    }

    
    btn.classList.remove('mb-2');
    btn.classList.add('active');
    
    classlist.classList.remove('d-none');


}






</script>


@endsection


