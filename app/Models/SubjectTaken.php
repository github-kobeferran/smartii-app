<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Setting;
use App\Models\Subject;

class SubjectTaken extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'subjects_taken';
    protected $appends = ['units'=> null, 'subj_desc' => null];
    // protected $primaryKey = ['student_id', 'subject_id', 'from_year', 'semester'];

    public function setUnitsAttribute($id)
    {
        $subject = Subject::find($id);

        $this->attributes['units'] = $subject->units;
    }

    public function getUnitsAttribute()
    {
        return $this->attributes['units'];
    }
    
    public function setSubjDescAttribute($id)
    {
        $subject = Subject::find($id);

        $this->attributes['subj_desc'] = $subject->desc;
    }

    public function getSubjDescAttribute()
    {
        return $this->attributes['subj_desc'];
    }
    
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

    public static function enrolledSubjectsbyStudent($id){

        return static::where('from_year', Setting::first()->from_year)
                     ->where('to_year', Setting::first()->to_year)
                     ->where('semester', Setting::first()->semester)
                     ->where('student_id',$id)                     
                     ->get();
        
    }

    public static function getAllSubjectsTakenByStudent($id){

        return static::where('student_id', $id)
                     ->orderBy('from_year', 'desc')
                     ->orderBy('semester', 'desc')     
                     ->get();                   

    }

    
    

}
