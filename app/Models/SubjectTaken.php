<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class SubjectTaken extends Model
{
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
