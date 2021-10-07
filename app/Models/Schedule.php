<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentClass;
use App\Models\Faculty;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    public $timestamps = false;   
    protected $appends = ['formatted_start' => null, 'formatted_until' => null, 'day_name' =>null, 'faculty_name' => null, 'room_name' => null]; 

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'id');
    }

    public function setFormattedStartAttribute($value){
        
        $this->attributes['formatted_start'] =  Carbon::parse($value)->format('h:i A');

    }
    
    public function getFormattedStartAttribute(){
        return $this->attributes['formatted_start'];
    }

    public function setFormattedUntilAttribute($value){
            $this->attributes['formatted_until'] =  Carbon::parse($value)->format('h:i A');
    }
    public function getFormattedUntilAttribute(){
        return $this->attributes['formatted_until'];
    }

    public function setDayNameAttribute($value)
    {
        switch($value){
            case 'mon':
                $this->attributes['day_name'] = "Monday";
            break;
            case 'tue':
                $this->attributes['day_name'] = "Tuesday";
            break;
            case 'wed':
                $this->attributes['day_name'] = "Wednesday";
            break;
            case 'thu':
                $this->attributes['day_name'] = "Thursday";
            break;
            case 'fri':
                $this->attributes['day_name'] = "Friday";
            break;
            case 'sat':
                $this->attributes['day_name'] = "Saturday";
            break;
        }

        
    }

    public function getDayNameAttribute()
    {
        return $this->attributes['day_name'];
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


    public static function getSchedulebyClass($id){

        return static::where('class_id', $id)->get();

    }  

}
