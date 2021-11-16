<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Program;
use App\Models\StudentClass;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty';
    protected $appends = ['age' => null, 'specialty' => null];

    public function classes(){
        return $this->hasMany(StudentClass::class);
    }

    public function program(){        
        return $this->hasOne(Program::class,'id', 'program_id');
    }

    public function setAgeAttribute($id)
    {
        $faculty = Faculty::find($id);    

        $this->attributes['age'] = Carbon::parse($faculty->dob)->age;

    }

    public function getAgeAttribute()
    {
        return $this->attributes['age'];
    }

    public function setSpecialtyAttribute($id){
        $faculty = Faculty::find($id);    

        if(!is_null($faculty->program_id))
            $this->attributes['specialty'] = Program::find($faculty->program_id)->desc;
        else
            $this->attributes['specialty'] = 'All Programs';

    }

    public function getSpecialtyAttribute(){
        return $this->attributes['specialty'];
    }



}
