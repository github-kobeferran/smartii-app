<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\SubjectTaken;
use App\Models\Subject;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Schedule;
use Carbon\Carbon;

class StudentClass extends Model
{
    use HasFactory;
    public $timestamps = false;    
    
    protected $table = 'classes';
    
    protected $appends = [
                            'topic' => null, 
                            'prog'=> null, 
                            'faculty_name' => null,
                            'student_count' => null,
                            'dropped_count' => null,
                            'from_cur_sem' => null,
                            'from_last_sem' => null
                        ];

    
    public static function init(){
        
        $class = new StudentClass;
        $class->save();
        return $class->id;
        
    }

    public function subjectsTaken(){
        return $this->hasMany(SubjectTaken::class, 'class_id', 'id')->withTrashed();
    }
    
    public function schedules(){
        return $this->hasMany(Schedule::class, 'class_id', 'id');
    }

    public function faculty(){
        return $this->belongsTo(Faculty::class)->withTrashed();
    }    

    public function students_list_string(){
        $students = collect();
        foreach($this->subjectsTaken as $subject_taken){
            $students->push( '['. ($subject_taken->trashed() ? 'DROPPED ' : '') . $subject_taken->student->student_id . '-' . $subject_taken->student->first_name . ' ' . $subject_taken->student->last_name . ']');
        }

        $students = $students->toArray();
        $list = implode(', ', $students);        

        return $list;
    }    

    public function setFacultyNameAttribute($id){

        $faculty = Faculty::find($id);

        $this->attributes['faculty_name'] = ucfirst($faculty->first_name) . ' ' . ucfirst($faculty->last_name);

    }

    public function getFacultyNameAttribute(){

        return $this->attributes['faculty_name'];

    }

    public function setTopicAttribute($id){

        $subjecttaken = SubjectTaken::where('class_id', $id)->first();

        $subject = Subject::withTrashed()->find($subjecttaken->subject_id);

        $this->attributes['topic'] = $subject->desc;
        
    }
    
    public function getTopicAttribute(){
    
        return $this->attributes['topic'];

    }

  
    public function getStudentCountAttribute(){

        $this->attributes['student_count'] = $this->hasMany(SubjectTaken::class, 'class_id', 'id')->count();

        return $this->attributes['student_count'];

    }

    public function getDroppedCountAttribute(){

        $this->attributes['dropped_count'] = $this->hasMany(SubjectTaken::class, 'class_id', 'id')->onlyTrashed()->count();

        return $this->attributes['dropped_count'];

    }

    public function setProgAttribute($id){

        $subjecttaken = SubjectTaken::where('class_id', $id)->first();
        $student = Student::find($subjecttaken->student_id);
        $program = Program::find($student->program_id);

        $this->attributes['prog'] = $program->desc;

    }

    public function getProgAttribute(){
        
        return  $this->attributes['prog'];
    }

    public function getFromLastSemAttribute(){
        $this->attributes['from_last_sem'] = false;

        $setting = Setting::first();

        $last_sem = new Setting;
        
        if($setting->semester == 2){
            $last_sem->semester = 1;

            $last_sem->to_year = $setting->to_year;
            $last_sem->from_year = $setting->from_year;            
        } else {
            $last_sem->semester = 2;
            if($setting->updated_at->year == Carbon::now()->year){
                $last_sem->to_year = Carbon::now()->year;
                $last_sem->from_year = Carbon::now()->subYear()->year;
            }
        }    

        if(
            $last_sem->from_year == $this->subjectsTaken->first()->from_year &&
            $last_sem->to_year == $this->subjectsTaken->first()->to_year &&
            $last_sem->semester == $this->subjectsTaken->first()->semester
        )
            $this->attributes['from_last_sem'] = true;
        

        return $this->attributes['from_last_sem'];
    }

    public function getFromCurSemAttribute(){
        $this->attributes['from_cur_sem'] = false;

        $setting = Setting::first();
        
        if(
            $setting->from_year == $this->subjectsTaken->first()->from_year &&
            $setting->to_year == $this->subjectsTaken->first()->to_year &&
            $setting->semester == $this->subjectsTaken->first()->semester
        )
            $this->attributes['from_cur_sem'] = true;

        return $this->attributes['from_cur_sem'];
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
     *  and the class id that is in that program
     * 
     *  ex. [5 => [1, 2], 6 => [1]]
     */


    public static function getFacultyClassesByProgram($id, $archive = false){        

        $classesByProgram = [];   
              
        if(static::where('faculty_id', $id)->where('archive', $archive ? 1 : 0)->count() > 1){

            $classes = static::where('faculty_id', $id)->where('archive', $archive ? 1 : 0)->get();
                                    
            $length = $classes->count();
            $i=0;

            foreach($classes as $class){                

                $subjecttaken = SubjectTaken::where('class_id' , $class->id)->first();          
                                
                $student = Student::find($subjecttaken->student_id);                

                $program = Program::find($student->program_id);   
                                
                if(array_key_exists($program->id, $classesByProgram)){

                    array_push($classesByProgram[$program->id], $class->id); 

                } else{
                    $classesByProgram[$program->id] = [$class->id];
                }
                
                $i++;

            }

        } elseif (static::where('faculty_id', $id)->where('archive', $archive ? 1 : 0)->count() == 1) {

            $class = static::where('faculty_id', $id)->where('archive', $archive ? 1 : 0)->first();            

            $subjecttaken = SubjectTaken::where('class_id' , $class->id)->first();

            $student = Student::find($subjecttaken->student_id);

            $program = Program::find($student->program_id);

            $classesByProgram[$program->id] = [$class->id];
           
        } else {
            return null;
        }          
        
        return $classesByProgram;
        
    }

    public static function getStudentsbyClass($id, $withTrashed = false){

        if($withTrashed)
            $subtakens = SubjectTaken::where('class_id', $id)->withTrashed()->get();
        else
            $subtakens = SubjectTaken::where('class_id', $id)->get();

        $students = collect(new Student);


        foreach($subtakens as $subtaken){

            $student = Student::find($subtaken->student_id);

            $students->push($student);

        }

        return $students;


    }

    
}
