<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\SubjectTaken;
use App\Models\Subject;
use App\Models\Setting;
use App\Models\Schedule;

class StudentClass extends Model
{
    use HasFactory;
    public $timestamps = false;    
    
    protected $table = 'classes';
    
    protected $appends = ['topic' => null];

    public static function init(){

        $class = new StudentClass;
        $class->save();
        return $class->id;

    }

    public function schedules(){
        return $this->hasMany(Schedule::class, 'class_id', 'id');
    }

    public function setTopicAttribute($id){

        $subjecttaken = SubjectTaken::where('class_id', $id)->first();

        $subject = Subject::find($subjecttaken->subject_id);

        $this->attributes['topic'] = $subject->desc;
        
    }
    
    public function getTopicAttribute(){
    
        return $this->attributes['topic'];

    }


    public static function getFacultyClasses($id){
      
        if(static::where('faculty_id', $id)->where('archive', 0)->count() > 1){

            $classes = collect([new StudentClass]);

            $classes = static::where('faculty_id', $id)->where('archive', 0)->get();

        } else {

            return static::where('faculty_id', $id)->where('archive', 0)->first();

        }
        
        return $classes;
        
    }


    /**
     *  will return a 2-dimensional array which have the program id as the key
     *  and the classes that is in that program
     * 
     *  ex. [5 => [1, 2], 6 => [1]]
     */


    public static function getFacultyClassesByProgram($id){        

        $classesByProgram = [];   
              
        if(static::where('faculty_id', $id)->where('archive', 0)->count() > 1){

            $classes = static::where('faculty_id', $id)->where('archive', 0)->get();
            

            foreach($classes as $class){                

                $subjecttaken = SubjectTaken::where('class_id' , $class->id)->first();                                

                $student = Student::find($subjecttaken->student_id);                

                $program = Program::find($student->program_id);   
                                
                if(array_key_exists($program->id, $classesByProgram)){

                    array_push($classesByProgram[$program->id], $class->id); 

                } else{
                    $classesByProgram[$program->id] = [$class->id];
                }     

            }

        } elseif (static::where('faculty_id', $id)->where('archive', 0)->count() == 1) {

            $class = static::where('faculty_id', $id)->where('archive', 0)->first();            

            $subjecttaken = SubjectTaken::where('class_id' , $class->id)->first();

            $student = Student::find($subjecttaken->student_id);

            $program = Program::find($student->program_id);

            $classesByProgram[$program->id] = [$class->id];
           
        } else {
            return null;
        }          
        
        return $classesByProgram;
        
    }
    
}
