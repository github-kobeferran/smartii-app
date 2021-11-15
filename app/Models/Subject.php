<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectTaken;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Program;
use App\Models\Setting;


class Subject extends Model
{
    use HasFactory;

    protected $appends = ['student_count' => null, 'program_desc' => null, 'level_desc' => null, 'semester_desc' => null];
 
    public function pre_reqs(){
        return $this->belongsToMany(Subject::class, 'subjects_pre_req', 'subject_id', 'subject_pre_req_id');
    }

    public function program() {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function setSemesterDescAttribute($value)
    {

        if($value ==  1)
            $this->attributes['semester_desc'] = "First Semester";
        else
            $this->attributes['semester_desc'] = "Second Semester";

    }

    public function getSemesterDescAttribute()
    {
        return $this->attributes['semester_desc'];
    }


    public function setLevelDescAttribute($value)
    {
        $level = "Undefined";
        switch($value){
            case 1:
                $level = "Grade 11";
            break;
            case 2:
                $level = "Grade 12";
            break;
            case 11:
                $level = "Freshman";
            break;
            case 12:
                $level = "Sophomore";
            break;

        }

        $this->attributes['level_desc'] = $level;
    }

    public function getLevelDescAttribute()
    {
        return $this->attributes['level_desc'];
    }

    public function setProgramDescAttribute($id){

        $subject = Subject::find($id);

        $program = Program::find($subject->program_id);

        $this->attributes['program_desc'] = $program->desc;

    }

    public function getProgramDescAttribute(){

        return $this->attributes['program_desc'];

    }

    public function setStudentCountAttribute($values = []){
        $setting = Setting::first();

        $subjectTakens = SubjectTaken::where('from_year', $setting->from_year)
                                     ->where('to_year', $setting->to_year)
                                     ->where('semester', $setting->semester)
                                     ->where('subject_id', $values['subject_id'])
                                     ->where('rating', 4.5)
                                     ->get();
        $count = 0;

        foreach($subjectTakens as $takenSubject){

            if(Student::where('id', $takenSubject->student_id)->doesntExist())
                return;

            $student = Student::find($takenSubject->student_id);            

            if($values['program_id'] == $student->program_id){
                ++$count;
            }

        }

        $this->attributes['student_count'] = $count;        

    }

    public function getStudentCountAttribute(){

        return $this->attributes['student_count'];

    }



    /**
     *  returns subjects to be taken based on a students
     *      program, level and semester
     * 
     
     */
    public static function findSubjectSet($program, $level, $semester){
        return Subject::whereRaw('(program_id = ? or program_id is null) ' .
                                 ' and level = ?'.
                                 ' and semester = ?', 
                           [$program, $level, $semester])
                           ->get();
    }

    /**
     *  returns all subjects to be taken of  student
     *  based on students department, program
     *  level and semester
     * 
     */
    public static function allWhere($values = [], $joinGenSubjs = false){        
        $department = $values['department'];
        $program = $values['program'];
        $level = $values['level'];
        $semester = $values['semester'];

        $is_tesda = Program::find($program)->is_tesda;

        if($is_tesda)
            $joinGenSubjs = false;
        else
            $joinGenSubjs = true;
        
        if($joinGenSubjs == true){            
            
            $subjects = Subject::where('dept', $department)->get();

            if($department == 0){
                $subjects = $subjects->filter(function($subject) use($program) {
                    if($subject->program_id == $program || $subject->program_id == 3)
                        return $subject;
                });                              
            } else {
                $subjects = $subjects->filter(function($subject) use($program) {
                    if($subject->program_id == $program || $subject->program_id == 4)
                        return $subject;
                });                                              
            }                 
            
           
        } else {
            
            $subjects = Subject::where('dept', $department)->where('program_id', $program)->get();          
                     
        }

        $subjects = $subjects->filter(function($subject) use($level) {                    
            return $subject->level <= $level;
        });                                                
        $subjects = $subjects->filter(function($subject) use($level, $semester) { 
            $valid = true;
            if($subject->level == $level){
                if($semester > 1)
                    return $subject;
                else
                    return $subject->semester == 1;
            } else {
                return $subject;
            }                                                                
        });  
        
        foreach($subjects as $subject){
            $subject->program;
        }
        
        return $subjects;            
        
                    
    }
   
    /**
     *  returns an array of arrays that store the subject's 
     *     pre-requisite id and 0 => failed,
     *                          1 => passed,
     *                          2 => deffered,  
     *      if the student has a passed rating 
     *        in subjects taken table, false if failed and null if it does 
     *           not exist in the subjects taken table
     */
    public static function PreReqChecker($subjectid, $studentID){
        $result = [];        

        $subject = Subject::find($subjectid);

        if($subject->pre_req == 1){

            $count = 0;
                        
           foreach($subject->pre_reqs as $pre_req){

                $passed = SubjectTaken::where('student_id', $studentID)
                                     ->where('subject_id' , $pre_req->id)
                                     ->where('rating', '<=', 3)
                                     ->exists();

                $failed = SubjectTaken::where('student_id', $studentID)
                                     ->where('subject_id' , $pre_req->id)
                                     ->where('rating', '=', 5)
                                     ->exists();

                $deferred = SubjectTaken::where('student_id', $studentID)
                                     ->where('subject_id' , $pre_req->id)
                                     ->where('rating', '=', 4)
                                     ->exists();

                $notSet = SubjectTaken::where('student_id', $studentID)
                                     ->where('subject_id' , $pre_req->id)
                                     ->where('rating', '=', 4.5)
                                     ->exists();

                $pending = SubjectTaken::where('student_id', $studentID)
                                     ->where('subject_id' , $pre_req->id)
                                     ->where('rating', '=', 3.5)
                                     ->exists();

                if($failed){   
                    
                    array_push($result, [$pre_req, 0]);                    
                    
                } elseif($passed)  {
                    array_push($result, [$pre_req, 1]);
                    
                } elseif($deferred) {
                    array_push($result, [$pre_req, 2]);

                }elseif($pending) {
                    array_push($result, [$pre_req, 3]);         

                }elseif($notSet){
                    array_push($result, [$pre_req, 4]);
                }

                $count++;                
           }

        } else {
            $result = [null, null];
        }                 

        return $result;
       
    }

    //for choosing pre req in subject creation
    public static function getPossiblePreReq($values, $all = true){
        $department = $values['department'];
        $program = $values['program'];
        $level = $values['level'];
        $semester = $values['semester'];        

        if( ($level== 1 || $level == 11) && $semester == 1  ){
            return collect();
        } else {

            $subjects = Subject::where('dept', $values['department'])->get();

            if($department == 0){
                
                $subjects = $subjects->filter(function($subject) use($program) {                    
                    if($subject->program_id == $program || $subject->program_id == 3)
                        return $subject;
                });  

                $subjects = $subjects->filter(function($subject) use($level, $semester) {                    
                    if($level > 1){
                        if($semester > 1)
                            return $subject->level <= $level;
                        else
                            return $subject->level == 1;
                    } else {
                        return $subject->level == 1 && $subject->semester == 1;
                    }
                });  

            } else {

                $subjects = $subjects->filter(function($subject) use($program) {
                    if(!Program::find($program)->is_tesda){
                        if($subject->program_id == $program || $subject->program_id == 4)
                            return $subject;
                    }else{
                        if($subject->program_id == $program)
                            return $subject;
                    }
                });      
                
                $subjects = $subjects->filter(function($subject) use($level, $semester) {                    
                    if($level > 11){
                        if($semester > 1)
                            return $subject->level <= $level;
                        else
                            return $subject->level == 11;
                    } else {
                        return $subject->level == 11 && $subject->semester == 1;
                    }
                });  

            }        
            
            $subjects = $subjects->filter(function($subject) {
                return !is_null($subject);
            });  

            return $subjects;          
        }
    }

    public static function subjectsForClasses($values = []){

        $program = Program::find($values['program']); 
        
        $query =  'dept = ? ';

        if(!$program->is_tesda){
            if($values['department']  == 0 ){                            
                $query.= ' and (program_id = ? or program_id = 3)';
            } else {
                $query.= ' and (program_id = ? or program_id = 4)';             
            }
        } else {
                $query.= ' and program_id = ?';             
        }
                                    
        $subjects = Subject::whereRaw($query, 
                                [$values['department'], $values['program']])
                                ->get();  
                                
                                
        foreach($subjects as $subject){

            $args['subject_id'] = $subject->id;
            $args['program_id'] = $values['program'];

            $subject->student_count = $args;

        }

        return $subjects;

    }    


}
