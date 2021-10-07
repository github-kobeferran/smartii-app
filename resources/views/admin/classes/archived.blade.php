<div class="container">
    <div class="row">

        <div class="col text-center">
    
            <h5 >ARCHIVED CLASSES</h5>    
    
        </div>
    
    </div>

    
    <div class="row">

        @if ($archivedClasses->count() > 0)

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Search.." aria-label="Username" aria-describedby="basic-addon1">
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="border border-success">
                        <tr>
                            <th>S.Y. & Sem</th>
                            <th>Department</th>
                            <th style="background-color: #EAE0BD !important">Class Name/Section</th>
                            <th>Subject</th>
                            <th>Instructor</th>
                        </tr>    
                    </thead>    
        
        
                    <tbody>
                        @foreach ($archivedClasses as $class)
                            <tr>
                                <?php
                                    $class->subjectsTaken->first()->student->program_desc = $class->subjectsTaken->first()->student->program_id;
                                
                                ?>
                                <td>{{$class->subjectsTaken->first()->sy_and_sem}}</td>
                                
                                <td>{{ $class->subjectsTaken->first()->student->program->abbrv . ' | ' . $class->subjectsTaken->first()->student->program_desc }}</td>
                                <td style="background-color: #EAE0BD !important" >
                                    {{ $class->class_name }}
                                    <br>
                                    <button type="button" data-toggle="modal" data-target="#class-{{$class->id}}" class="btn btn-light text-primary border-0 text-left pl-0" style="background-color: #EAE0BD !important" >view details</button>

                                    <div class="modal fade" id="class-{{$class->id}}" tabindex="-1" role="dialog" aria-labelledby="class-{{$class->id}}Title" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLongTitle">{{$class->class_name}}</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
        
                                            <div class="modal-body">

                                                <div class="text-center">
                                                    <h5>Schedule </h5>
                                                    @foreach ($class->schedules as $schedule)
                                                        <div class="border m-2 w-50 mx-auto">
                                                            {{$schedule->day}}
                                                            {{$schedule->start_time}}
                                                            {{$schedule->until}}
                                                        </div>                                                    
                                                    @endforeach
                                                </div>

                                                <hr class="bg-dark">

                                                <div class="text-center mt-2 border-info rounded">
                                                    <h5>Students </h5>
                                                    @foreach ($class->subjectsTaken as $subjectTaken)
                                                        

                                                        <div class="row border my-2 mx-auto py-2 ">

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
                                                                
                                                                <span >
                                                                    {{number_format($subjectTaken->rating, 2)}}   
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
                                <?php
                                    $class->faculty_name = $class->faculty_id;
                                ?>
                                <td>{{ $class->faculty_name }}</td>
                            </tr>

                        @endforeach
                    </tbody>


                </table>   

                {{$archivedClasses->links()}}

            </div>
        @else
            <div class="mx-auto">
                NO CLASSES HAS BEEN ARCHIVED YET
            </div>
        @endif
    
    </div>
    
    
</div>