<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Student;

class StudentDiscounts extends Model
{
    use HasFactory;
    
    public function student(){
        return $this->hasOne(Student::class, 'id', 'student_id');
    }

}