<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentDiscounts;

class Discount extends Model
{
    use HasFactory;

    public function students(){
        return $this->hasMany(StudentDiscounts::class, 'discount_id', 'id');
    }
        
}
