@extends('layouts.module')

@section('content')

<div class="container">

    <div class="col-sm mx-auto text-center mt-2">

        <h5>My Personal Details</h5>

        <table class="table table-bordered table-striped">            
            
            <tr>

                <td  id="" class="faculty-col" > MEMBER ID </td>
                <td id=""> {{$faculty->faculty_id }}</td>
                <td > this can't be edited</td>

            </tr>
            <tr>

                <td  id="last_name_name" class="faculty-col" > Last Name </td>
                <td id="last_name"> {{$faculty->last_name }}</td>
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}} , 'last_name')" class="btn-light border">Edit this</button></td>

            </tr>

            <tr>

                <td id="first_name_name" class="faculty-col"> First Name </td>
                <td id="first_name"> {{$faculty->first_name }}</td>
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}} , 'first_name')" class="btn-light border">Edit this</button></td>
            </tr>
            <tr>

                <td id="middle_name_name" class="faculty-col"> Middle Name </td>
                <td id="middle_name"> {{$faculty->middle_name }}</td>
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'middle_name')" class="btn-light border">Edit this</button></td>
            </tr>

            <tr>

                <td class="faculty-col" > Email </td>
                <td > {{$faculty->email }}</td>
                <td > this can't be edited</td>
                

            </tr>
            <tr>

                <td id="contact_name" class="faculty-col" > Contact </td>
                    @empty($faculty->contact)
                        <td id="contact"> N\A</td>
                    @else 
                        <td id="contact"> {{$faculty->contact }}</td>
                    @endempty
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'contact')" class="btn-light border">Edit this</button></td>

            </tr>

            <tr>

                <td id="gender_name" class="faculty-col"> Sex </td>
                @empty($faculty->gender)
                    <td id="gender"> N\A</td>
                @else 
                    <td id="gender"> {{$faculty->gender }}</td>
                @endempty
                
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'gender')" class="btn-light border">Edit this</button></td>
            </tr>

            <tr >

                <td  id="dob_name" class="faculty-col"> Age </td>
             
                <td id="age"> {{$faculty->age }} years</td>
               
                
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'dob')" class="btn-light border">Edit this</button></td>
            </tr>

            <tr >

                <td id="civil_status_name" class="faculty-col"> Civil Status </td>
             
                @empty($faculty->civil_status)
                    <td id="civil_status"> N\A</td>
                @else 
                    <td id="civil_status"> {{$faculty->civil_status }}</td>
                @endempty
               
                
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'civil_status')" class="btn-light border">Edit this</button></td>
            </tr>

            <tr >

                <td id="religion_name" class="faculty-col"> Religion </td>
             
                @empty($faculty->religion)
                    <td id="religion"> N\A</td>
                @else 
                    <td id="religion"> {{$faculty->religion }}</td>
                @endempty
               
                
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'religion')" class="btn-light border">Edit this</button></td>
            </tr>

            <tr >

                <td id="college_alumni_name" class="faculty-col"> College Alumni </td>
             
                @empty($faculty->college_alumni)
                    <td id="college_alumni"> N\A</td>
                @else 
                    <td id="college_alumni"> {{$faculty->college_alumni }}</td>
                @endempty
               
                
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'college_alumni')" class="btn-light border">Edit this</button></td>
            </tr>

        </table>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @include('inc.messages')

        {!! Form::open(['url' => 'updatefaculty', 'id' => 'editform', 'class' => 'mt-2 d-none']) !!}
            <h5 id = "label">Edit Detail</h5>

            {{Form::hidden('faculty_id', $faculty->id, ['id' => 'hiddenid'])}}
            
            <div class="form-group">
                            
            {{Form::hidden('detail_name', null, ['id' => 'detail-name'])}}
            {{Form::text('detail', null, ['id' => 'detail', 'class'=> 'form-control text-center rounded-0'])}}
            {{Form::submit('Update', ['class' => 'btn btn-primary btn-block mt-1'])}}
            </div>


        {!! Form::close() !!}

    </div>

</div>

<script>
let editform = document.getElementById('editform');

function edit(id, detail){

    editform.classList.remove('d-none');

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/showfacultydetail/' + id + '/' + detail , true);

    xhr.onload = function() {

        if (this.status == 200) {

            let result = JSON.parse(this.responseText);                          

            let label = document.getElementById('label');
            let detailName = document.getElementById(detail + '_name');            
            let detailFormName = document.getElementById('detail-name');            

            label.textContent = 'Edit ' + detailName.textContent;
            
            detailVal = document.getElementById('detail');
            detailFormName.value = detail;

            detailVal.value = result.detail;
            

        }

    }


    xhr.send();

    
}

function canceledit(){

    editform.classList.add('d-none');

}

</script>
    
@endsection