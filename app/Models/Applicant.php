<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Applicant;
use App\Models\Member;

class Applicant extends Model
{
    use HasFactory; 
    use SoftDeletes;

    protected $appends = ['age' => null, 'days_ago' => null, 'prog_desc'=> null , 'dept_desc' => null];       

    public function student(){
        return $this->belongsTo(Student::class, 'app_id', 'id');
    }

    public function member(){
        return $this->hasOne(Member::class, 'member_id', 'id');
    }
    
    public function setAgeAttribute($id)
    {         

        $this->attributes['age'] = Carbon::parse($this->dob)->age;

    }

    public function getAgeAttribute()
    {
        return $this->attributes['age'];
    }

    public function setDaysAgoAttribute($id)
    {
        
        $this->attributes['days_ago'] = Carbon::parse($this->created_at)->diffForHumans();

    }

    public function getDaysAgoAttribute()
    {
        return $this->attributes['days_ago'];
    }

    public function setProgDescAttribute($id)
    {                     

        $this->attributes['prog_desc'] = $program_desc = Program::find($this->program)->desc;

    }

    public function getProgDescAttribute()
    {
        

        return $this->attributes['prog_desc'];
    }

    public function setDeptDescAttribute($id)
    {        

        if($this->dept == 0)
            $this->attributes['dept_desc'] = 'Senior High School';
        else
            $this->attributes['dept_desc'] = 'College';

    }

    public function getDeptDescAttribute()
    {
        return $this->attributes['dept_desc'];
    }

}
