<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $appends = ['stud_name'=> null, 'formatted_date'=>null];

    public function student(){
        return $this->hasOne(Student::class, 'id', 'student_id');
    }    

    public function admin(){
        return $this->hasOne(Admin::class, 'id',  'admin_id');
    }    

    public function setStudNameAttribute($stud_id){
        
        $student = Student::find($stud_id);

        $this->attributes['stud_name'] = $student->first_name . ' ' . $student->last_name;

    }

    public function getStudNameAttribute(){
        return $this->attributes['stud_name'];
    }

    public function setFormattedDateAttribute($value){

        $this->attributes['formatted_date'] = Carbon::parse($value)->format('M d Y h:i A');
        
    }

    public function getFormattedDateAttribute($value){

        return $this->attributes['formatted_date'];

    }



}
