<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\SubjectTaken;
use App\Models\Setting;
use App\Models\Schedule;

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

    public function schedules(){
        return $this->hasMany(Schedule::class, 'class_id', 'id');
    }    
    
}
