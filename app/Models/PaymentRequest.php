<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Program;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $appends = [
                            'stud_id' => null,
                            'stud_name' => null,
                            'stud_dept' => null,
                            'stud_prog' => null,
                            'stud_address' => null,                            
                            'time_ago' => null,                            
                         ];

    public static function pendingRequestCount(){

        return static::whereNull('admin_id')->count();
                
    }

    public function setStudIdAttribute($id){

        $this->attributes['stud_id'] = Student::find($id)->student_id;

    }

    public function getStudIdAttribute(){
        return  $this->attributes['stud_id'];
    }


    public function setStudNameAttribute($id){

        $student = Student::find($id);

        $this->attributes['stud_name'] = ucfirst($student->first_name) . ' ' . ucfirst($student->last_name);

    }

    public function getStudNameAttribute(){
        return  $this->attributes['stud_name'];
    }


    public function setStudDeptAttribute($id){

        $dept = Student::find($id)->department;

        if($dept == 0)
            $this->attributes['stud_dept'] = "SHS";
        else 
            $this->attributes['stud_dept'] = "College";
        
    }

    public function getStudDeptAttribute(){
        
        return  $this->attributes['stud_dept'];

    }

    public function setStudProgAttribute($id){

        $student = Student::find($id);
        $program = Program::find($student->program_id);

        
        $this->attributes['stud_prog'] = $program->desc;
        
    }

    public function getStudProgAttribute(){
        
        return  $this->attributes['stud_prog'];

    }

    public function setStudAddressAttribute($id){

        $student = Student::find($id);        

        $this->attributes['stud_address'] = $student->present_address;
        
    }

    public function getStudAddressAttribute(){
        
        return  $this->attributes['stud_address'];

    }

    public function setTimeAgoAttribute($value){
        
        $this->attributes['time_ago'] = \Carbon\Carbon::parse($value)->diffForHumans();
        
    }

    public function getTimeAgoAttribute(){
        
        return  $this->attributes['time_ago'];

    }

    


}
