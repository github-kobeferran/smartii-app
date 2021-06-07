<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Balance;



class Student extends Model
{
    use HasFactory;

    protected $appends = ['program_desc' => null, 'balance_amount' => null];

    public function subject_taken(){
        return $this->hasMany(SubjectTaken::class);
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

}
