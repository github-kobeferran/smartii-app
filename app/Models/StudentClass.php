<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\SubjectTaken;
use App\Models\Setting;

class StudentClass extends Model
{
    use HasFactory;
    public $timestamps = false;


    protected $table = 'classes';

    public static function init(){

        $class = new StudentClass;
        $class->save();
        return $class->id;

    }




    

    
    
}
