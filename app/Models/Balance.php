<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    public static function init(){

        $balance = new Balance;
        $balance->save();

        return $balance->id;
    }
}
