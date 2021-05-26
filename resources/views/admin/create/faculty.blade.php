{!! Form::open(['url' => 'admin/create/faculty', 'files' => true, 'id' => 'facultyForm']) !!}

    <div class="custom-control custom-switch">
        <input name="new_stud_switch" type="checkbox" class="custom-control-input" id="newStudSwitch">
        <label class="custom-control-label" for="newStudSwitch"><strong>Generate Student ID</strong></label>
    </div>

{!! Form::close() !!}

<script>

window.onbeforeunload = function(event)
{
    return '';
};

document.getElementById("facultyForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};

</script>