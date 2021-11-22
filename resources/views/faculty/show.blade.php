@extends('layouts.module')

@section('page-title')
    {{$faculty->first_name}} {{$faculty->last_name}}
@endsection

@section('content')

<div class="container">

    <div class="col-sm mx-auto text-center mt-2">

        <h5 >My Personal Details</h5>
        <button data-toggle="modal" data-target="#delete-account" class="badge badge-secondary float-left">Delete my SMARTII Account</button>

        <div class="modal fade" tabindex="-1" id="delete-account" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    @if ($faculty->classes->where('archive', 0)->count() < 1)
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title"><span class="text-white">DELETE YOUR ACCOUNT?</span></h5>
                            <button data-dismiss="modal" class="close">&times;</button>
                        </div>
                        {!!Form::open(['url' => '/deletefaculty'])!!}
                            <div class="modal-body text-justify">
                                <h5><span class="text-danger">WARNING!!!</span></h5>
                                <p>You must not delete your account unless you are leaving the organization, or the President/Principal of the Institution tells you to do so.</p>
                                <p>Enter your <b>password</b> to continue.</p>
                                {{Form::password('password', ['class' => 'form-control', 'required' => 'required'])}}
                                {{Form::hidden('id', $faculty->id)}}
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Yes, Delete my SMARTII Faculty Account</button>
                                <button type="button" data-dismiss="modal" class="btn btn-light">Cancel</button>
                            </div>
                        {!!Form::close()!!}
                    @else
                        <div class="modal-header bg-light">
                            <h5 class="modal-title"><span class="text-dark">CANT DELETE</span></h5>
                            <button data-dismiss="modal" class="close">&times;</button>
                        </div>
                        <div class="modal-body text-justify">
                            <p class="text-dark">You still have unarchived classes, please settle that first.</p>
                        </div>
                        <div class="modal-footer">                            
                            <button type="button" data-dismiss="modal" class="btn btn-light">Ok</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

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
                        <td id="contact"> -- </td>
                    @else 
                        <td id="contact"> {{$faculty->contact }}</td>
                    @endempty
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'contact')" class="btn-light border">Edit this</button></td>
            </tr>
            <tr>
                <td id="gender_name" class="faculty-col"> Sex </td>
                @empty($faculty->gender)
                    <td id="gender"> -- </td>
                @else 
                    <td id="gender"> {{$faculty->gender }}</td>
                @endempty
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'gender')" class="btn-light border">Edit this</button></td>
            </tr>
            <tr>
                <td  id="dob_name" class="faculty-col"> Age </td>
                <td id="age"> {{$faculty->age }} years</td>
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'dob')" class="btn-light border">Edit this</button></td>
            </tr>
            <tr >
                <td id="civil_status_name" class="faculty-col"> Civil Status </td>
                    @empty($faculty->civil_status)
                        <td id="civil_status"> -- </td>
                    @else 
                        <td id="civil_status"> {{$faculty->civil_status }}</td>
                    @endempty
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'civil_status')" class="btn-light border">Edit this</button></td>
            </tr>
            <tr>
                <td id="religion_name" class="faculty-col"> Religion </td>
                @empty($faculty->religion)
                    <td id="religion"> -- </td>
                @else 
                    <td id="religion"> {{$faculty->religion }}</td>
                @endempty
                <td class="faculty-col-action"><button onclick="edit({{$faculty->id}}, 'religion')" class="btn-light border">Edit this</button></td>
            </tr>
            <tr>
                <td id="college_alumni_name" class="faculty-col"> College Alumni </td>
                    @empty($faculty->college_alumni)
                        <td id="college_alumni"> -- </td>
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