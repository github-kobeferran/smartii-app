@extends('layouts.module')

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
</script>

@section('charts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
    
    google.charts.load("current", {packages:["corechart"]});
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
    
    google.charts.load("current", {packages:["corechart"]});
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


    
    
    </script>

    
@endsection

@section('content')

<h5>Dashboard</h5>

<div class="row">

    <div class="col">

        <div class="card dashboard-card">
            
            <div class="card-header "><div><h5>Overview</h5> <a href="/admin/settings">change settings here</a></div></div>

            <div class="card-body">
               
                <table class="table table-bordered bg-light">

                    <tr>
                        <td>
                            Enrollment Mode 
                        </td>
                        <td>
                            @if ($setting->enrollment_mode == 0)
                    
                                Close
                                
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

        <div class="d-flex">

            <button type="button" class="btn btn-primary btn-lg m-2">
                Applicants <span class="badge badge-light">{{$applicantCount}}</span>
              </button>
              <button type="button" class="btn btn-primary btn-lg m-2">
                Students <span class="badge badge-light">{{$studentCount}}</span>
              </button>
              <button type="button" class="btn btn-primary btn-lg m-2">
                Programs offerred <span class="badge badge-light">{{$programsOffered}}</span>
              </button>

        </div>

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

        <div class="d-flex flex-wrap">


            @foreach ($announcements as $announcement)

            <div class="card text-white bg-warning text-secondary mb-3" style="min-width: 18rem;">
                <div class="card-header">{{\Carbon\Carbon::parse($announcement->created_at)->format('g:i A, D d F')}} <span><a class="float-right" href="/deleteannouncement/{{$announcement->id}}">X</a></span> </div>
                <div class="card-body announcement-body">
                  <h5 class="card-title">{{$announcement->title}}</h5>
                  <p class="card-text">{{$announcement->content}}</p>
                </div>
              </div>   
                
            @endforeach


                              
           
        </div>

        </div>

        <div class="d-flex">

            
            <div id="donutchart" style="width: 900px; height: 500px;"></div>
            <div id="piechart" style="width: 900px; height: 500px;"></div>
            
            

        </div>




    </div>

</div>

<script>
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

</script>

                     
            
@endsection
