<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Program;
use App\Models\StudentClass;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'faculty';
    protected $appends = [
                            'age' => null, 
                            'specialty' => null,
                            'is_trashed' => null
                        ];

    public function classes(){
       
        return $this->hasMany(StudentClass::class);
    }

    public function active_classes(){
        return $this->hasMany(StudentClass::class)->where('archive', 0);
    }

    public function archived_classes(){
        return $this->hasMany(StudentClass::class)->where('archive', 1);
    }

    public function program(){        
        return $this->hasOne(Program::class,'id', 'program_id');
    }

    public function registrar_requests(){
        return $this->hasMany(RegistrarRequest::class, 'requestor_id', 'id');
    }    

    public function member(){
        return $this->hasOne(Member::class, 'member_id', 'id');
    }

    public function setAgeAttribute($id)
    {
        $faculty = Faculty::withTrashed()->find($id);    

        $this->attributes['age'] = Carbon::parse($faculty->dob)->age;

    }

    public function getAgeAttribute()
    {
        return $this->attributes['age'];
    }

    public function setSpecialtyAttribute($id){
        $faculty = Faculty::withTrashed()->find($id);    

        if(!is_null($faculty->program_id))
            $this->attributes['specialty'] = Program::find($faculty->program_id)->desc;
        else
            $this->attributes['specialty'] = 'All Programs';

    }

    public function getSpecialtyAttribute(){
        return $this->attributes['specialty'];
    }

    public function getIsTrashedAttribute(){
        if($this->trashed())
            return $this->attributes['is_trashed'] = true;
        else    
            return $this->attributes['is_trashed'] = false;
    }

}
