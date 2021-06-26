<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty';
    protected $appends = ['age' => null];

    public function setAgeAttribute($id)
    {
        $faculty = Faculty::find($id);    

        $this->attributes['age'] = Carbon::parse($faculty->dob)->age;

    }

    public function getAgeAttribute()
    {
        return $this->attributes['age'];
    }


}
