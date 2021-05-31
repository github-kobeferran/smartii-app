<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;

class Student extends Model
{
    use HasFactory;

    public function subject_taken(){
        return $this->hasMany(SubjectTaken::class);
    }

    public function getProgramDescAttribute()
    {
        return $this->attributes['program_desc'];
    }
    public function getBalanceAmountAttribute()
    {
        return $this->attributes['balance_amount'];
    }

    
    public function setProgramDescAttribute($value)
    {
        $this->attributes['program_desc'] = $value;
    }
    public function setBalanceAmountAttribute($value)
    {
        $this->attributes['balance_amount'] = $value;
    }

}
