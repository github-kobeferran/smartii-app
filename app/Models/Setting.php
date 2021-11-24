<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Setting extends Model
{
    protected $appends = [
                            'sem_desc' => null,
                            'before_date' => null
                         ];

    use HasFactory;

    public function getSemDescAttribute()
    {
        return $this->attributes['sem_desc'];
    }

    public function setSemDescAttribute($value)
    {
        if($value)
            $this->attributes['sem_desc'] = '1st';
        else 
            $this->attributes['sem_desc'] = '2nd';
        
    }

    public function getBeforeDateAttribute($dept){
        if($dept)
            return $this->attributes['before_date'] = Carbon::now()->subYears(18)->toDateString();
        else
            return $this->attributes['before_date'] = Carbon::now()->subYears(15)->toDateString();
    }
    
    public static function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
