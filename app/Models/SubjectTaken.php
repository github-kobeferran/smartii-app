<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Setting;

class SubjectTaken extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'subjects_taken';
    // protected $primaryKey = ['student_id', 'subject_id', 'from_year', 'semester'];
 
    public static function pendingClasses(){

        return static::where('from_year', Setting::first()->from_year)
                     ->where('to_year', Setting::first()->to_year)
                     ->where('semester', Setting::first()->semester)
                     ->where('rating', 4.5)
                     ->where('class_id', null)
                     ->get();
        
    }

    public static function getEnrolledTakenSubjects(){

        return static::where('from_year', Setting::first()->from_year)
                     ->where('to_year', Setting::first()->to_year)
                     ->where('semester', Setting::first()->semester)
                     ->where('rating', 3.5)
                     ->whereNotNull('class_id')
                     ->get();

    }

    
    

}
