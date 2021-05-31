<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Setting;

class SubjectTaken extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'subjects_taken';
 
    public static function pendingClasses(){

        return static::where('from_year', Setting::first()->from_year)
                     ->where('to_year', Setting::first()->to_year)
                     ->where('semester', Setting::first()->semester)
                     ->where('rating', 4.5)
                     ->where('class_id', null)
                     ->get();
        
    }

    //show classes group by dept and program order by setting

    // public static function subjectsTakenThisSemester(){

    //     return static::selectRaw('year(created_at) year, monthname(created_at) month, count(*) published')
    //     ->groupBy('year', 'month')
    //     ->orderByRaw('min(created_at) desc')
    //     ->get()
    //     ->toArray();        
    // }
    

}
