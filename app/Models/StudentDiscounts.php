<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Student;
use App\Models\Discount;

class StudentDiscounts extends Model
{
    use HasFactory;
    
    public function student(){
        return $this->hasOne(Student::class, 'id', 'student_id');
    }

    public function discount(){
        return $this->hasOne(Discount::class, 'id', 'discount_id');
    }

}
