<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\StudentClass;

class SubjectTaken extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'subjects_taken';

    protected $appends = ['units'=> null, 'subj_desc' => null, 'sy_and_sem' => null];

    // protected $primaryKey = ['student_id', 'subject_id', 'from_year', 'semester'];

    public function class(){
        return $this->belongsTo(StudentClass::class);
    }

    public function student(){
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }    

    public function subject(){
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    // public function setUnitsAttribute($id)
    // {
    //     $subject = Subject::find($id);

    //     $this->attributes['units'] = $subject->units;
    // }

    // public function getUnitsAttribute()
    // {
    //     return $this->attributes['units'];
    // }
    
    public function setSubjDescAttribute($id)
    {
        $subject = Subject::find($id);

        $this->attributes['subj_desc'] = $subject->desc;
    }

    public function getSubjDescAttribute()
    {
        return $this->attributes['subj_desc'];
    }
    
    // public function setSyAndSemAttribute()
    // {                
    // }
    
    public function getSyAndSemAttribute()
    {
        $this->attributes['sy_and_sem'] = $this->from_year . '-' . $this->to_year . ' ' . ($this->semester == 1? '1st sem' : '2nd sem');
        return $this->attributes['sy_and_sem'];
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
                     ->where(function($query) {
                        $query->where('rating', '=', 4.5)
                              ->orWhere('rating', '=', 3.5);
                     })
                     ->where('student_id',$id)                     
                     ->get();
        
    }

    public static function getAllSubjectsTakenByStudent($id){

        return static::where('student_id', $id)
                     ->orderBy('from_year', 'desc')
                     ->orderBy('semester', 'desc')     
                     ->get();                   

    }


    public static function subjectsToBeTaken(Student $student){
        $level = '';
        $semester = '';
        $graduate = false;
        

        if($student->level == 11 && $student->semester == 1){
            $level = 11;
            $semester = 2;
        } elseif($student->level == 11 && $student->semester == 2){
            $level = 12;
            $semester = 1;
        } elseif($student->level == 12 && $student->semester == 1){

            $level = 12;
            $semester = 2;

        } elseif($student->level == 1 && $student->semester == 1){
            $level = 1;
            $semester = 2;
        } elseif($student->level == 1 && $student->semester == 2){
            $level = 2;
            $semester = 1;
        } elseif($student->level == 2 && $student->semester == 1){
            $level = 2;
            $semester = 2;
        } else {
            $graduate = true;
        }

        if($graduate == true){
            return 'graduated';
        }


        $subjects = collect(new Subject);
        

        if($level == 1 || $level == 2){

            $subjects = Subject::where('level', $level)
                               ->where('semester', $semester)
                               ->where(function($query) use($student) {
                                    $query->where('program_id', $student->program_id)
                                        ->orWhere('program_id', 3);
                                })
                              ->get();
                            
        }else{

            $subjects = Subject::where('level', '=', $level)
                               ->where('semester', '=', $semester)
                               ->where(function($query) use($student){
                                    $query->where('program_id', '=',$student->program_id)
                                          ->orWhere('program_id', '=', 4);
                                })
                               ->get();

        }           

        if($graduate){
            $subjects = null;
        }

        return $subjects;

    }

    
    

}
