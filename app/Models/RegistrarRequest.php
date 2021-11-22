<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Faculty;

class RegistrarRequest extends Model
{
    use HasFactory;

    public function requestor(){
        if($this->requestor_type == 'student')
            return $this->hasOne(Student::class, 'id', 'requestor_id');
        else if($this->requestor_type == 'faculty')
            return $this->hasOne(Faculty::class, 'id', 'requestor_id')->withTrashed();
    }

    public function admin(){
        return $this->hasOne(Admin::class, 'id', 'marked_by')->withTrashed();
    }

}
