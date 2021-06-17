@extends('layouts.app')

@section('studentprofile')

<?php    $show = 0;     ?>

@if(auth()->user()->id == $userLink->id )

    <?php    $show = 3;     ?>

@elseif(auth()->user()->id == $userLink->id || auth()->user()->user_type == 'admin')

    <?php    $show = 2;     ?>

@elseif(auth()->user()->user_type == 'faculty') 

    <?php    $show = 1;     ?>

@endif

<div class="container mt-2">

    @if($show>0)

    <div class="row border-bottom ">
        

        <div class="col-sm d-flex justify-content-center">
            
                <img  class="profile-pic " src="{{url('/storage/images/applicants/id_pics/' . $appLink->id_pic)}}" alt="">    
                                         
        </div>


               
    </div>

    @endif

    <div class="row m-4">

        <div class="col-sm mx-auto text-center">
 
            <h5>{{ ucfirst($student->first_name) . ' ' .  ucfirst($student->last_name)}}</h5>
            <em>student</em>

        </div>

    </div>

    <div class="row m-3">

        <div class="col-sm mx-auto text-center">
 
            <table class="table table-striped border">

                <tr>

                    <td class="border-right">
                        Department
                    </td>

                    <td class="w-50">
                        {{ucfirst($student->dept)}}
                    </td>

                </tr>
                <tr>

                    <td class="border-right">
                        Program
                    </td>

                    <td class="w-50">
                        {{ucfirst($student->program_desc)}}
                    </td>

                </tr>
                <tr>

                    <td class="border-right">
                        Level
                    </td>

                    <td class="w-50">
                        {{ucfirst($student->level_desc)}}
                    </td>

                </tr>
                <tr class="other-detail d-none">

                    <td class="border-right">
                        Age
                    </td>

                    <td class="w-50">
                        {{ucfirst($student->age) . ' years'}}
                    </td>

                </tr>

                <tr class="other-detail d-none">

                    <td class="border-right">
                        Balance
                    </td>

                    <td class="w-50">
                        {{ 'Php '. ucfirst($student->balance_amount)}}
                    </td>

                </tr>

                @if($show > 1)

                <tr>

                    <td id="detail-button" role="button" onclick="toggleDetails()" class="text-center border border-info bg-info text-white" colspan="2">
                        Show other Details
                    </td>                   

                </tr>
           
                @endif

                @if($show > 2)

                <tr>

                    <td class="border border-info" colspan="2">
                       <a class=" " href="">Update Details</a>
                    </td>                   

                </tr>


                @endif
                
                

              

            </table>

        </div>

    </div>


    @if($show > 2)

    <div class="row m-4">

        <div class="col mx-auto d-flex justify-content-center">
 
            <a class="student-button">

                My Classes

            </a>

        </div>
        <div class="col mx-auto d-flex justify-content-center">
 
            <a class="student-button">

                Balance and Payments

            </a>

        </div>

    </div>

    @endif
    
</div>

<script>

let showdetails = false;    
let toggleDetailButton = document.getElementById('detail-button');

function toggleDetails(){
    

    if(!showdetails){
        otherDetails = document.getElementsByClassName('other-detail');    

        for(let i in otherDetails){
            otherDetails[i].className = "other-detail";
        }

        toggleDetailButton.textContent = "Hide other Details"
        showdetails = true;
    } else {

        otherDetails = document.getElementsByClassName('other-detail');    

        for(let i in otherDetails){
            otherDetails[i].className = "other-detail d-none";
        }

        toggleDetailButton.textContent = "Show other Details"
        showdetails = false;

    }
   

}

</script>


@endsection
