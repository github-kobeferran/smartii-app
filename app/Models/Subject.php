<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectTaken;


class Subject extends Model
{
    use HasFactory;
 
    public function pre_reqs(){
        return $this->belongsToMany(Subject::class, 'subjects_pre_req', 'subject_id', 'subject_pre_req_id');
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
     *  returns an array of arrays that store the subject's 
     *     pre-requisite id and true if the student has a passed rating 
     *        in subjects taken table, false if failed and null if it does 
     *           not exist in the subjects taken table
     */
    public static function PreReqChecker($subject, $studentID){ 
        $result = [];
        $message = '';
        if($subject['pre_req'] == 1){

            $count = 0;
           foreach($subject->pre_reqs as $pre_req){

                $passed = SubjectTaken::where('student_id', $studentID)
                                     ->where('subject_id' , $pre_req->id)
                                     ->where('rating', '<', 4)
                                     ->exists();

                $failed = SubjectTaken::where('student_id', $studentID)
                                     ->where('subject_id' , $pre_req->id)
                                     ->where('rating', '>', 4)
                                     ->exists();

                if($passed){                    
                    $result[$count] = [$pre_req, true]; 
                    
                } elseif($failed)  {
                    $result[$count] = [$pre_req, false]; 
                    
                } else {
                    $result[$count] = [$pre_req, null];                    
                }

                $count++;                
           }

        } else {
            $result = ['', true];
        }                 

        return $result;
       
    }


    

    
}
