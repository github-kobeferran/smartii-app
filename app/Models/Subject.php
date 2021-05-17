<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Subject extends Model
{
    use HasFactory;
 
    public function pre_reqs(){
        return $this->belongsToMany(Subject::class, 'subjects_pre_req', 'subject_id', 'subject_pre_req_id');
    }

    public static function findSubjectSet($program, $level, $semester){
        return Subject::whereRaw('(program_id = ? or program_id is null) ' .
                                 ' and level = ?'.
                                 ' and semester = ?', 
                           [$program, $level, $semester])
                           ->get();
    }

    // must compare subjects->pre_req->id from findSubjectSet 
    // to student id, subjects taken in subjects_taken

    public static function PreReqChecker($subjects){ 
        $subj_pre_reqs = [];
        $ids = [];
        
        $count = 0;

        foreach($subjects as $subject){            
            if($subject['pre_req'] == 1){
                $subj_pre_reqs[$count] = $subject->pre_reqs;            
                $count++;
            }            
        }
        $count = 0;
                
        // [[object], [object], [object1, object2]]
        foreach($subj_pre_reqs as $pre_reqs){ 
            $sub_count = 0;
            foreach($pre_reqs as $pre_req){
                $ids[$count][$sub_count] = $pre_req['id'];                                
                $sub_count++;
            }
            $ids[$count];
            $count++;          
        }

        return $ids; // multi-dimensional 
        // first subject => pre_subj #1
        // second subject => pre_subj #1
        //                => pre_subj #2
        
    }

    
}
