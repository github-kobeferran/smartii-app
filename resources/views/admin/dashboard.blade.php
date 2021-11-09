@extends('layouts.module')

@section('page-title')
    Admin Dashboard
@endsection

<script>
    var applicantCount = {!! json_encode($applicantCount) !!}    
    var studentCount = {!! json_encode($studentCount) !!}    
    var classCount = {!! json_encode($classCount) !!}    
    var femaleCount = {!! json_encode($femaleCount) !!}    
    var maleCount = {!! json_encode($maleCount) !!}    
    var genderNullCount = {!! json_encode($genderNullCount) !!}    
    var passedStudents = {!! json_encode($passedStudents) !!}    
    var failedStudents = {!! json_encode($failedStudents) !!}    
    var defferedStudents = {!! json_encode($defferedStudents) !!}    
    var setting_obj = {!! json_encode(\App\Models\Setting::first()) !!}    
</script>

<?php 

    $shsPrograms = \App\Models\Program::where('department', 0)->where('id', '!=', 3)->orderBy('created_at', 'asc')->get();

    foreach ($shsPrograms as $prog) {
        $prog->student_count;
        $prog->append('student_count')->toArray();
    }    

    $colPrograms = \App\Models\Program::where('department', 1)->where('id', '!=', 4)->orderBy('created_at', 'asc')->get();

    foreach ($colPrograms as $prog) {
        $prog->student_count;
        $prog->append('student_count')->toArray();
    }    

    

?>



@section('charts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
    
    google.charts.load("current", {packages:["corechart", 'bar']});
    google.charts.setOnLoadCallback(drawDonut);

    function drawDonut() {
        
        let data = google.visualization.arrayToDataTable([
        ['Students Gender', 'Count'],
        ['Male', maleCount],
        ['Female', femaleCount],
        ['Not Set Yet', genderNullCount]
        ]);

        let options = {
        title: 'Students Gender Count',
        pieHole: 0.4,
        };

        let chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
    
    
    google.charts.setOnLoadCallback(drawPie);

    function drawPie() {
        
        let data = google.visualization.arrayToDataTable([
        ['Remarks', 'Count'],
        ['Passed',     passedStudents],
        ['Failed',      failedStudents],
        ['Deffered',  defferedStudents]        
        ]);

        let options = {
        title: 'Student Performance this Semester',        
        };

        let chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }

    
    google.charts.setOnLoadCallback(drawApplicants);
    

    function drawApplicants() {
        
        let data = google.visualization.arrayToDataTable([
        ["Type", "Count", { role: "style" } ],
        ['SHS',     {!! json_encode(\App\Models\Applicant::where('dept', 0)->whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek() )->count()) !!}, "#b87333" ],
        ['SHS Approved',      {!! json_encode(\App\Models\Applicant::where('dept', 0)->where('approved', 1)->whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek() )->count()) !!}, "#C8A583" ],             
        ['College',      {!! json_encode(\App\Models\Applicant::where('dept', 1)->whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek() )->count()) !!}, "#BB6ECC" ],             
        ['College Approved',      {!! json_encode(\App\Models\Applicant::where('dept', 1)->where('approved', 1)->whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek() )->count()) !!}, "#DBB7E2" ],             
        ]);

        let options = {
            title: 'Applicants this Semester S.Y.' + setting_obj.from_year + '-' + setting_obj.to_year +  (setting_obj.semester == 1 ? ' First ' : ' Second ') + 'Semester',        
        };

        let chart = new google.visualization.ColumnChart(document.getElementById('applicantsChart'));
        chart.draw(data, options);
    }

    google.charts.setOnLoadCallback(drawSHS);
    

    function drawSHS() {
        
        let data = google.visualization.arrayToDataTable([
        ["Type", "Count", { role: "style" } ],
        ['SHS All',     {!! json_encode(\App\Models\Student::where('department', 0)->count()) !!}, "#ebedaf" ],           
        ['SHS Grade 11',     {!! json_encode(\App\Models\Student::where('department', 0)->where('level', 1)->count()) !!}, "#ecf086" ],           
        ['SHS Grade 12',     {!! json_encode(\App\Models\Student::where('department', 0)->where('level', 2)->count()) !!}, "#e1e835" ],           
        
        ]);

        let options = {
            title: 'SHS Students Chart',        
        };

        let chart = new google.visualization.ColumnChart(document.getElementById('shsChart'));
        chart.draw(data, options);
    }

    google.charts.setOnLoadCallback(drawCol);

    function drawCol() {
        
        let data = google.visualization.arrayToDataTable([
        ["Type", "Count", { role: "style" } ],
        ['College All',     {!! json_encode(\App\Models\Student::where('department', 1)->count()) !!}, "#039c27" ],           
        ['College First Year',     {!! json_encode(\App\Models\Student::where('department', 1)->where('level', 11)->count()) !!}, "#75fa95" ],           
        ['College Second Year',     {!! json_encode(\App\Models\Student::where('department', 1)->where('level', 12)->count()) !!}, "#47b561" ],           
        
        ]);

        let options = {
            title: 'College Students Chart',        
        };

        let chart = new google.visualization.ColumnChart(document.getElementById('colChart'));
        chart.draw(data, options);
    }

    let shsPrograms = {!! json_encode($shsPrograms) !!};       

    let shsProgramsArray = [['Program', 'Count']];

    for(let i in shsPrograms){

        shsProgramsArray.push([shsPrograms[i].abbrv , shsPrograms[i].student_count]);

    }

    google.charts.setOnLoadCallback(drawSHSPrograms);

    
    function drawSHSPrograms() {
        
        let data = google.visualization.arrayToDataTable(shsProgramsArray);

        let options = {
        title: 'SHS Programs and number of Students',  
        colors: ['#fff305', '#f7f288', '#fffcc4', '#f5f3d0', '#f2f1df']      
        };

        let chart = new google.visualization.PieChart(document.getElementById('shsPrograms'));
        chart.draw(data, options);
    }

    let colPrograms = {!! json_encode($colPrograms) !!};       

    let colProgramsArray = [['Program', 'Count']];

    for(let i in colPrograms){

        colProgramsArray.push([colPrograms[i].abbrv , colPrograms[i].student_count]);

    }

    google.charts.setOnLoadCallback(drawColPrograms);

    
    function drawColPrograms() {
        
        let data = google.visualization.arrayToDataTable(colProgramsArray);

        let options = {
        title: 'College Programs and number of Students',  
        colors: ['#14ad00', '#1ade00', '#83f274', '#b3faaa', '#d6f7d2']      
        };

        let chart = new google.visualization.PieChart(document.getElementById('colPrograms'));
        chart.draw(data, options);
    }
    
   
       

    
</script>

    
@endsection

@section('content')

<h5>Dashboard</h5>

<div class="row">

    <div class="col">

        {{-- ##########################  OVERVIEW  #########################--}}

        <div class="card dashboard-card">
            
            <div class="card-header "><div><h5>Overview</h5> <a href="/admin/settings">change settings here</a></div></div>

            <div class="card-body">
               
                <table class="table table-bordered bg-light">

                    <tr>
                        <td>
                            Enrollment Mode 
                        </td>
                        <td
                        style="{{$setting->enrollment_mode == 0 ? 'color: red' : 'color: green'}}"
                        >
                            @if ($setting->enrollment_mode == 0)
                    
                                Closed
                                
                            @else
                                
                                Open

                            @endif
                        </td>

                    </tr>
                    <tr>
                        <td>
                            Current A. Y.
                        </td>
                        <td>
                            {{$setting->from_year . '-' . $setting->to_year}}
                        </td>

                    </tr>
                    <tr>
                        <td>
                            Current Semester
                        </td>
                        <td>
                            @if ($setting->semester == 1)
                    
                                First Semester
                                
                            @else
                                
                                Second Semester

                            @endif
                        </td>

                    </tr>


                </table>
                
            </div>

        </div>

         {{--######################### OVERVIEW END #########################--}}

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        @include('inc.messages')
        

        {{--######################### COUNTS BADGES #########################--}}

        <div class="d-flex flex-wrap justify-content-center">

            <button data-toggle="modal" data-target="#applicantsCount" type="button" class="btn btn-primary btn-lg m-1">
                <i class="fa fa-list text-dark" aria-hidden="true"></i>
                Pending Applicants 
                <span class="badge badge-light">{{\App\Models\Applicant::where('approved', 0)->whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek())->count()}}</span>                 
              </button>
              <a href="#statistics" type="button" class="btn btn-primary btn-lg m-1">
                Students <span class="badge badge-light">{{$studentCount}}</span>
              </a>
              <a href={{url('/viewprogramsfromdashboard')}} class="btn btn-primary btn-lg m-1">
                Programs offerred <span class="badge badge-light">{{$programsOffered}}</span>
              </a>

        </div>

        <div class="modal fade bd-example-modal-lg" id="applicantsCount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header bg-info">
                  <h5 class="modal-title" id="exampleModalLongTitle">Applicant Data S.Y. {{\App\Models\Setting::first()->from_year . '-' . \App\Models\Setting::first()->to_year . (\App\Models\Setting::first()->semester == 1 ? ' First' : ' Second')}} SEMESTER</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">                    

                    @if (\App\Models\Applicant::whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek() )->count() > 0)


                        <div class="row text-left mx-auto">

                            <div class="col">

                                <b>Applicants this semester: <span class="ml-2">{{\App\Models\Applicant::whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek() )->count()}}</span></b>
                                <br>
                                <b>Pending applicants: <span class="ml-2">{{\App\Models\Applicant::where('approved', 0)->whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek() )->count()}}</span></b>
                                <br>
                                <b>Approved applicants: <span class="ml-2">{{\App\Models\Applicant::where('approved', 1)->whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek() )->count()}}</span></b>
                                                            
                            </div>


                        </div>

                        <div class="row">

                            <div id="applicantsChart" style="width: 100%; height: 100%;"></div>

                        </div>                            
                        
                    @else
                        <div class="row text-center border py-3">
                            There are no Applicant Data this semester
                        </div>
                    @endif
                    
                    <?php
                        $applicantUsersThisSem = \App\Models\User::whereDate('created_at', '>=', \Carbon\Carbon::parse(\App\Models\Setting::first()->semester_updated_at)->subWeek())->where('user_type', 'applicant')->get();                            
                                                    
                        $still_no_app_form = $applicantUsersThisSem->filter(function ($applicant_user, $key) {
                            return is_null($applicant_user->member);
                        });

                        
                    ?>                        

                    @if ($still_no_app_form->count() > 0)
                        
                        <div class="row justify-content-end">

                            <button role="button" data-toggle="modal" data-target="#noAppFormList" class="btn btn-info text-white float-right mr-2">
                                Still waiting for Admission Form Submission <span class="badge badge-light">{{$still_no_app_form->count()}}</span>                                                                                                               
                            </button>    
                            
                            <div class="modal fade" id="noAppFormList" tabindex="-1" role="dialog" aria-labelledby="noAppFormListTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" style="width: 100%;" role="document">
                                  <div class="modal-content" >
                                    <div class="modal-header bg-info text-dark">
                                      <h5 class="modal-title" id="exampleModalLongTitle">Still Waiting to pass their Admission Form</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-secondary text-white">
                                              <tr>
                                                  <th>Email</th>
                                                  <th>Name</th>
                                                  <th>Duration in the system</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                  @foreach ($still_no_app_form as $user_applicant)
                                                      <tr>
                                                          <td>{{$user_applicant->email}}</td>
                                                          <td>{{$user_applicant->name}}</td>
                                                          <td>{{\Carbon\Carbon::parse($user_applicant->created_at)->diffForHumans()}}</td>
                                                      </tr>
                                                  @endforeach
                                            </tbody>
                                          </table>
                                      </div>
                                    </div>                                   
                                  </div>
                                </div>
                            </div>                            
                        </div>
                    @endif
                    
                            
                </div>
              
              </div>
            </div>
          </div>
        
        {{--######################### END OF BADGES #########################--}}

        <div class="container border" style="min-height: 100px;">

            <h5 class="mt-2 text-center">ANNOUNCEMENTS</h5> <span data-toggle="modal" data-target="#announcementForm" class="float-right"><i class="fa fa-plus" aria-hidden="true"></i></span>

            <div class="modal fade" id="announcementForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Add Announcement</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    {!!Form::open(['url' => '/createannouncement'])!!}
                    <div class="modal-body">

                        <div class="form-group">
                            Title (What it is about)
                            {{Form::text('title', '', ['class' => 'form-control'])}}
                            
                        </div>
                        <div class="form-group">
                            Content (What do you want to say)
                            {{Form::textarea('content', '', ['class' => 'form-control'])}}

                        </div>
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>                        
                    </div>
                    {!!Form::close() !!}
                  </div>
                </div>
              </div>

            <div class="d-flex justify-content-center  flex-wrap">

                @foreach ($announcements as $announcement)

                <div class="card text-white bg-warning text-secondary mb-3 m-2" style="min-width: 18rem;">
                    <div class="card-header">{{\Carbon\Carbon::parse($announcement->created_at)->format('g:i A, D d F')}} <span><a class="float-right" href="/deleteannouncement/{{$announcement->id}}">X</a></span> </div>
                    <div class="card-body announcement-body">
                    <h5 class="card-title">{{$announcement->title}}</h5>
                    <p class="card-text">{{$announcement->content}}</p>
                    </div>
                </div>   
                    
                @endforeach                              
            
            </div>

        </div>

        <div class="btn-group dropleft float-right mt-2">
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Other Actions
            </button>
            <div class="dropdown-menu border-info">
                <ul class="list-group list-group-flush p-0 m-0">
                    <a href="/events/create" class="dropdown-item list-group-item">Create a School Event</a>
                    <br>
                    <a href="/events" class="dropdown-item list-group-item">View School Events</a>
                    <br>
                    <a role="button" class="dropdown-item list-group-item" data-toggle="modal" data-target="#showGallery">Homepage Images</a>
                    <br>
                    <a href="/createpost" class="dropdown-item list-group-item">Create a Blog/Article Post</a>
                </ul>                
            </div>
          </div>
        
        
        <div class="modal fade" id="showGallery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content"  style="min-height: 21rem;">

                    <div class="modal-header bg-info text-white">
                        <div class="modal-title" id="exampleModalLabel">Images in Homepage</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        {!!Form::open(['url' => '/homepageimage/store', 'files' => true])!!}
                    <div class="modal-body">
                        <div class="d-flex flex-wrap justify-content-center">

                            @empty(\Illuminate\Support\Facades\DB::select('select * from homepage_images order by created_at asc'))

                            @else         
                            
                                @foreach (\Illuminate\Support\Facades\DB::select('select * from homepage_images order by created_at asc') as $item)

                                    <a role="button"  data-toggle="modal" data-target="#showImage-{{$item->id}}">
                                        
                                        <img  src="{{url('/storage/images/system/homepage_images/' . $item->image)}}" alt="" class="border img-fluid my-2 w-50">

                                    </a>   
                                    
                                @endforeach

                                
                                
                            @endempty                        

                                                                                        

                        </div>

                        <div class="form-group border mt-3">

                            <p class="">Add New Image</p>                        
                            {{Form::file('image', ['class' => 'form-control-file', 'id' => 'file'])}}

                            <div class="p-2">

                                Note: The ideal height for <b>Homepage Images</b> is <b>350px</b>.

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                     
                        <button id="addNewButton" type="submit" class="btn btn-primary d-none">Add Image</button>                  
                    </div>
                        {!!Form::close()!!}                
                </div>
            </div>
        </div>

        @empty(\Illuminate\Support\Facades\DB::select('select * from homepage_images order by created_at asc'))
        
        @else         
        
            @foreach (\Illuminate\Support\Facades\DB::select('select * from homepage_images order by created_at asc') as $item)
                
                <div class="modal fade" id="showImage-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered " role="document">
                      <div class="modal-content ">
                        <div class="modal-header bg-warning">
                          <div class="modal-title " id="exampleModalLabel"><img  src="{{url('/storage/images/system/homepage_images/' . $item->image)}}" alt="" class="border img-fluid w-25"></div>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        {!!Form::open(['url' => '/homepageimage/update', 'files' => true])!!}                        
                        <div class="modal-body">
                            <p class="">Change this Image</p>
                            {{Form::hidden('id', $item->id)}}
                            {{Form::file('image', ['class' => 'form-control-file', 'id' => 'editfile'])}}
                            <div class="p-2">

                                Note: The ideal height for <b>Homepage Images</b> is <b>350px</b>.
    
                            </div>
                        </div>
                        <div class="modal-footer">
                          <a href="homepageimage/delete/{{$item->id}}" class="btn btn-danger" >Delete</a>
                          <button id="updateImage" type="submit" class="btn btn-info">Submit</button>
                        </div>
                        {!!Form::close()!!}
                      </div>
                    </div>
                  </div>

            @endforeach

                
            
        @endempty  

        
        <br>
        <br>
        <br>
        <hr>
        <h5 id="statistics">Statistics <i class="fa fa-bar-chart" aria-hidden="true"></i></h5>

        <div class="d-flex flex-wrap justify-content-center">
            
            <div class="mx-auto mb-2 px-auto w-100" id="donutchart" ></div>
            <div class="mx-auto mb-2 px-auto w-100" id="piechart" ></div>
            <div class="mx-auto mb-2 px-auto w-100" id="shsChart" ></div>
            <div class="mx-auto mb-2 px-auto w-100" id="shsPrograms" ></div>
            <div class="mx-auto mb-2 px-auto w-100" id="colChart" ></div>
            <div class="mx-auto mb-2 px-auto w-100" id="colPrograms" ></div>
            

        </div>
   




    </div>

</div>

<script>
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}


let addNewButton = document.getElementById('addNewButton');
let updateImage = document.getElementById('updateImage');


file.addEventListener('input', () => {

    if(file.value == ''){
        addNewButton.className = "btn btn-primary d-none";        
    } else {        
        addNewButton.className = "btn btn-primary";
    }

});





</script>

                     
            
@endsection
