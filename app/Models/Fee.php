<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Fee extends Model
{
    use HasFactory;

    public static function getMergedFees($dept, $prog, $level, $sem){

        //fee every student, every sem
        $mergedFees =  self::where('dept', 2)->whereNull('program_id')->whereNull('level')->whereNull('sem')->get();
        //fee every student, depends on sem the student is in
        $mergedFees =  $mergedFees->merge(self::where('dept', 2)->whereNull('program_id')->whereNull('level')->where('sem', $sem)->get());        
        
        // fee based on the student's dept, every sem
        $mergedFees =  $mergedFees->merge(self::where('dept', $dept)->whereNull('program_id')->whereNull('sem')->get());
        // fee based on the student's dept, based on the stud's sem
        $mergedFees =  $mergedFees->merge(self::where('dept', $dept)->whereNull('program_id')->where('sem', $sem)->get());
        
        // fee for every student in that program, every sem
        $mergedFees =  $mergedFees->merge(self::where('dept', $dept)->where('program_id', $prog)->whereNull('level')->whereNull('sem')->get());
        // fee for every student in that program, based on students level, every sem
        $mergedFees =  $mergedFees->merge(self::where('dept', $dept)->where('program_id', $prog)->where('level', $level)->whereNull('sem')->get());
        // fee for every student in that program, based on students level, based on stud's sem
        $mergedFees =  $mergedFees->merge(self::where('dept', $dept)->where('program_id', $prog)->where('level', $level)->where('sem', $sem)->get());
                
        return $mergedFees;

    }

    public function getMatchingStudents(){                

        if($this->dept != 2){

            $mergedStudents = Student::where('department', $this->dept);

            if($this->program_id != null)
                $mergedStudents->where('program_id', $this->program_id);
                
            if($this->level != null)
                $mergedStudents->where('level', $this->level);

            if($this->sem != null)
                $mergedStudents->where('level', $this->sem);

            return $mergedStudents->get();
            
        } else {

            if($this->level == null && $this->sem == null){
                return Student::all();
            } else if($this->level != null && $this->sem == null){
                return Student::where('level', $this->level)->get();
            }else if($this->level == null && $this->sem != null){
                return Student::where('semester', $this->sem)->get();
            }else{
                return Student::where('level', $this->level)->where('semester', $this->sem)->get();
            }
           

        }



    }

}
