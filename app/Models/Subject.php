<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectTaken;
use App\Models\Subject;
use App\Models\Student;


class Subject extends Model
{
    use HasFactory;

    protected $appends = ['student_count' => null];
 
    public function pre_reqs(){
        return $this->belongsToMany(Subject::class, 'subjects_pre_req', 'subject_id', 'subject_pre_req_id');
    }

    public function setStudentCountAttribute($values = []){

        $subjectTakens = SubjectTaken::where('subject_id', $values['subject_id'])
                                     ->where('rating', 4.5)
                                     ->get();
        $count = 0;

        foreach($subjectTakens as $takenSubject){

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
       
        if($joinGenSubjs == true){
            $query =  'dept = ? ';
            if($values['department']  == 0 ){                
                $query.= ' and (level >= 1 and level <= ?)';
                $query.= ' and (program_id = ? or program_id = 3)';                                  
            } else {
                $query.= ' and (program_id = ? or program_id = 4)'; 
                $query.= ' and (level >= 11 and level <= ?)';
            }
            
            if($values['level'] == 1 || $values['level'] == 11 )
                $query.= ' and semester <= ?';           
            
                
            return Subject::whereRaw($query, 
                                    [$values['department'], $values['program'],$values['level'], $values['semester']])
                                    ->get();
            
        } else {
            $query =  'dept = ? ';
            $query.= ' and program_id = ?';
                if($values['department']  == 0 )
                    $query.= ' and (level >= 1 and level <= ?)';
                else 
                    $query.= ' and (level >= 11 and level <= ?)';
            $query.= ' and semester <= ?';
            
            return Subject::whereRaw($query, 
                                    [$values['department'], $values['program'],$values['level'], $values['semester']])
                                    ->get();
        }
                    
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
    public static function getPossiblePreReq($values){

        if( ($values['level'] == 1 || $values['level'] == 11) && $values['semester'] == 1  ){
            return new Subject;
        } else {

            $query = 'dept = ?';
            

            if($values['department']  == 0 ){

                $query.= ' and (program_id = ? or program_id = 3)';                                  

                
                if($values['level'] > 1){
                    

                    if($values['semester'] > 1){

                        $query.= ' and level <= ?';    

                    } else {

                        $query.= ' and level = 1';                        

                    }
                                    
                    
                }else {

                    $query.= ' and level = 1';
                    $query.= ' and semester = 1';

                }

            }else{

                $query.= ' and (program_id = ? or program_id = 4)';                      

                if($values['level'] > 11){

                    if($values['semester'] > 1){

                        $query.= ' and level <= ?';    

                    } else {

                        $query.= ' and level = 11';                        

                    }
                                       
                    
                }else {

                    $query.= ' and level = 11';
                    $query.= ' and semester = 1';

                }
                    
            }                                                      

            return Subject::whereRaw($query,
                                    [$values['department'], $values['program'],$values['level'], $values['semester']])
                                    ->get();

        }
        
    }

    public static function subjectsForClasses($values = []){

        $query =  'dept = ? ';
        if($values['department']  == 0 ){                            
            $query.= ' and (program_id = ? or program_id = 3)';
        } else {
            $query.= ' and (program_id = ? or program_id = 4)';             
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
