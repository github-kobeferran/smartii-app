<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentClass;
use App\Models\Faculty;

class Schedule extends Model
{
    use HasFactory;

    public $timestamps = false;   
    protected $appends = ['faculty_name', 'room_name']; 

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'id');
    }

    public function setFacultyNameAttribute($id)
    {
        $faculty = Faculty::find(static::find($id)->studentClass->faculty_id);

        $this->attributes['faculty_name'] = strtoupper($faculty->first_name[0]) . ". " . $faculty->last_name; 
    }

    public function getFacultyNameAttribute()
    {
        return $this->attributes['faculty_name'];
    }

    public function setRoomNameAttribute($id)
    {
        $room = Room::find(static::find($id)->room_id);

        $this->attributes['room_name'] = $room->name; 
    }

    public function getRoomNameAttribute()
    {
        return $this->attributes['room_name'];
    }

}
