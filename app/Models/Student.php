<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Balance;
use Carbon\Carbon;



class Student extends Model
{
    use HasFactory;

    protected $appends = ['age' => null, 'level_desc' => null, 'dept' => null, 'program_desc' => null, 'balance_amount' => null];

    public function subject_taken(){
        return $this->hasMany(SubjectTaken::class);
    } 

    public function setAgeAttribute($id)
    {
        $student = Student::find($id);    

        $this->attributes['age'] = Carbon::parse($student->dob)->age;

    }

    public function getAgeAttribute()
    {
        return $this->attributes['age'];
    }


    public function setProgramDescAttribute($id)
    {
        $program = Program::find($id);

        $this->attributes['program_desc'] = $program->desc;
    }

    public function getProgramDescAttribute()
    {
        return $this->attributes['program_desc'];
    }
    
    public function setBalanceAmountAttribute($id)
    {
        $balance = Balance::find($id);

        $this->attributes['balance_amount'] = $balance->amount;
    }

    public function getBalanceAmountAttribute()
    {
        return $this->attributes['balance_amount'];
    }

    public function setDeptAttribute($id)
    {
        if($id == 0)
            $this->attributes['dept'] = "SHS";
        else
            $this->attributes['dept'] = "College";
    }

    
    public function getDeptAttribute()
    {
        return $this->attributes['dept'];
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
                $level = "First Year";
            break;
            case 12:
                $level = "Second Year";
            break;

        }

        $this->attributes['level_desc'] = $level;
    }

    public function getLevelDescAttribute()
    {
        return $this->attributes['level_desc'];
    }

}
