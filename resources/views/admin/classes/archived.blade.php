<div class="container">
    <div class="row">
        <div class="col text-center">
            <h5 >ARCHIVED CLASSES</h5>    
        </div>
    </div>

    
    <div class="row">        

        @if ($archivedClasses->count() > 0)

            <div class="input-group mb-3">
                <div class="input-group-prepend border">
                    <button class="input-group-text btn btn-lg btn-light" id="basic-addon1" disabled><i class="fa fa-search"></i></button>
                </div>
                <input value="" id="search-text" type="text" class="form-control" placeholder="Search an archived Class" aria-label="Username" aria-describedby="basic-addon1">
            </div>              

            <div class="table-responsive border-top shadow" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table class="table table-bordered">
                    <thead class="border border-success">
                        <tr >
                            <th class="bg-light">S.Y. & Sem</th>
                            <th class="bg-light">Department & Program</th>
                            <th style="background-color: #EAE0BD !important">Class Name/Section</th>
                            <th class="bg-light">Subject</th>
                            <th class="bg-light">Instructor</th>
                        </tr>    
                    </thead>    
        
        
                    <tbody id="archive-list">                        

                        @foreach ($archivedClasses as $class)
                            <?php 
                                $class->subjectsTaken->first()->student->program_desc = $class->subjectsTaken->first()->student->program_id; 
                                $class->faculty_name = $class->faculty_id;
                            ?>

                            <tr>
                                <td>{{$class->subjectsTaken->first()->sy_and_sem}}</td>
                                
                                <td>{{($class->subjectsTaken->first()->subject->dept ? 'COLLEGE' : 'SHS') . ' | ' . $class->subjectsTaken->first()->student->program_desc }}</td>
                                <td style="background-color: #EAE0BD !important" >
                                    {{ $class->class_name }}
                                    <br>
                                    <button type="button" data-toggle="modal" data-target="#class-{{$class->id}}" class="btn btn-light text-primary border-0 text-left pl-0" style="background-color: #EAE0BD !important" >View Class Details</button>

                                    <div class="modal fade" id="class-{{$class->id}}" tabindex="-1" role="dialog" aria-labelledby="class-{{$class->id}}Title" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLongTitle">{{$class->class_name. '-' . $class->faculty_name}}</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
        
                                            <div class="modal-body">

                                                <div class="text-center">
                                                    <h5>Schedule </h5>
                                                    @foreach ($class->schedules as $schedule)
                                                        <div class="m-2 w-50 mx-auto">
                                                            {{strtoupper($schedule->day)}}
                                                            {{\Carbon\Carbon::parse($schedule->start_time)->format('g:i A')}} -
                                                            {{\Carbon\Carbon::parse($schedule->until)->format('g:i A')}}
                                                        </div>                                                    
                                                    @endforeach
                                                </div>

                                                <hr class="bg-dark">

                                                <div class="text-center mt-2 border-info rounded">
                                                    <h5>Students </h5>
                                                    @foreach ($class->subjectsTaken as $subjectTaken)
                                                        

                                                        <div class="row border my-0 mx-auto py-2 ">

                                                            <div class="col border-right">

                                                                <span class="text-left " >
                                                                    <a href="{{url('/studentprofile/' . $subjectTaken->student->student_id)}}">{{$subjectTaken->student->student_id}}</a>
                                                                </span>
                                                                
                                                            </div>
                                                            <div class="col text-left border-right">
                                                                
                                                                <span >
                                                                    {{$subjectTaken->student->last_name . ', ' . $subjectTaken->student->first_name . ' ' . $subjectTaken->student->middle_name}}   
                                                                </span>

                                                            </div>
                                                            <div class="col text-center">
                                                                
                                                                <span>
                                                                    @if (\App\Models\RegistrarRequest::where('type', 'drop')->where('type_id', $subjectTaken->id)->exists())
                                                                        @if (!is_null($subjectTaken->student->drop_request))
                                                                            @if ($subjectTaken->student->drop_request->status == 1)
                                                                                DROPPED
                                                                            @else 
                                                                            {{number_format($subjectTaken->rating, 2)}}   
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                    {{number_format($subjectTaken->rating, 2)}}   
                                                                    @endif
                                                                </span>

                                                            </div>

                                                            
                                                        </div>  
                                                    
                                                    @endforeach
                                                </div>
                                              
                                            </div>
                                           
                                          </div>
                                        </div>
                                    </div>

                                </td>
                                <td>{{ $class->subjectsTaken->first()->subject->desc }}</td>                               
                                <td>{{ $class->faculty_name }}</td>
                            </tr>

                        @endforeach
                    </tbody>


                </table>                   

                

            </div>
        @else
            <div class="mx-auto">
                NO CLASSES HAS BEEN ARCHIVED YET
            </div>
        @endif
    
    </div>
    
    
</div>

<script>

    let searchText = document.getElementById('search-text');
    let archiveList = document.getElementById('archive-list');

    async function searchArchived(){        
        const res = await fetch(APP_URL + '/admin/searcharchived/' + searchText.value);
        const archives = await res.json();

        let output = `<tbody id="archive-list">`;

        for(let i in archives){       

            output += `<tr>
                            <td>${archives[i].subjectsTaken[Object.keys(archives[i].subjectsTaken)[0]].sy_and_sem}</td>
                            <td>${archives[i].subjectsTaken[Object.keys(archives[i].subjectsTaken)[0]].student.program.abbrv} | ${archives[i].subjectsTaken[Object.keys(archives[i].subjectsTaken)[0]].student.program_desc}</td>
                            <td  style="background-color: #EAE0BD !important">
                                ${archives[i].class_name}
                                <br>
                                <button type="button" data-toggle="modal" data-target="#class-${archives[i].id}" class="btn btn-light text-primary border-0 text-left pl-0" style="background-color: #EAE0BD !important" >View Class Details</button>

                                <div class="modal fade" id="class-${archives[i].id}" tabindex="-1" role="dialog" aria-labelledby="class-${archives[i].id}Title" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLongTitle">${archives[i].class_name} - ${archives[i].faculty_name}</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
        
                                            <div class="modal-body">

                                                <div class="text-center">
                                                    <h5>Schedule </h5>`;

                                                    for(let j in archives[i].schedules){
                                                        output += `<div class="m-2 w-50 mx-auto">
                                                            ${archives[i].schedules[j].day.toUpperCase()}
                                                            ${archives[i].schedules[j].start_time} -
                                                            ${archives[i].schedules[j].until}
                                                        </div>`;
                                                    }                                                    
                                    output += `</div>

                                                <hr class="bg-dark">

                                                <div class="text-center mt-2 border-info rounded">
                                                    <h5>Students </h5>`;

                                                    for(let j in archives[i].subjectsTaken){
                                            output += `<div class="row border my-0 mx-auto py-2 ">
                                                            <div class="col border-right">
                                                                <span class="text-left " >
                                                                    <a href="${APP_URL}/studentprofile/${archives[i].subjectsTaken[j].student.student_id}">${archives[i].subjectsTaken[j].student.student_id}</a>                                                                
                                                                </span>
                                                            </div>
                                                            
                                                            <div class="col border-right ">
                                                                <span class="text-left " >
                                                                    ${archives[i].subjectsTaken[j].student.last_name} , ${archives[i].subjectsTaken[j].student.first_name}  ${archives[i].subjectsTaken[j].student.middle_name}
                                                                </span>
                                                            </div>

                                                            <div class="col text-center ">
                                                                    <span>
                                                                        ${archives[i].subjectsTaken[j].rating.toFixed(2)}
                                                                    </span>
                                                            </div>

                                                        </div>`;

                                                    }                                                     
                                    output += `</div>
                                              
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>


                            </td>
                            <td>${archives[i].subjectsTaken[Object.keys(archives[i].subjectsTaken)[0]].subject.desc}</td>
                            <td>${archives[i].faculty_name}</td>
                        </tr>`; 
        }            
                          
        output += `</tbody>`;

        archiveList.innerHTML = output;
    }

    searchText.addEventListener('keyup', searchArchived);

</script>