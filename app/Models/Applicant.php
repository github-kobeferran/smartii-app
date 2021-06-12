<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Applicant;

class Applicant extends Model
{
    use HasFactory;

    protected $appends = ['days_ago' => null, 'prog_desc'=> null , 'dept_desc' => null];

    public function setDaysAgoAttribute($id)
    {
        $applicant = Applicant::find($id);    

        $this->attributes['days_ago'] = Carbon::parse($applicant->created_at)->diffForHumans();

    }

    public function getDaysAgoAttribute()
    {
        return $this->attributes['days_ago'];
    }

    public function setProgDescAttribute($id)
    {
        $applicant = Applicant::find($id);                

        $this->attributes['prog_desc'] = Program::find($applicant->program)->desc;

    }

    public function getProgDescAttribute()
    {
        return $this->attributes['prog_desc'];
    }

    public function setDeptDescAttribute($id)
    {
        $dept = Applicant::find($id)->dept;   
        
        if($dept == 0)
            $this->attributes['dept_desc'] = 'Senior High School';
        else
            $this->attributes['dept_desc'] = 'College';

    }

    public function getDeptDescAttribute()
    {
        return $this->attributes['dept_desc'];
    }

}
