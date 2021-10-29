<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Balance;
use App\Models\Member;
use App\Models\User;
use App\Models\SubjectTaken;
use App\Models\Applicant;
use App\Models\StudentDiscounts;
use App\Models\Fee;
use App\Models\Discount;
use App\Models\Setting;
use Carbon\Carbon;



class Student extends Model
{
    use HasFactory;

    protected $appends = [
                          'age' => null,
                          'level_desc' => null, 
                          'dept' => null, 
                          'program_desc' => null, 
                          'balance_amount' => null, 
                          'rating' => null, 
                          'pronoun' => null,
                          'tuition_without_discount' => null,
                          'tuition_with_discount' => null
                        ];

    public function member(){
        return $this->hasOne(Member::class, 'member_id', 'id');
    }    

    public function subject_taken(){
        return $this->hasMany(SubjectTaken::class);
    } 

    public function applicant(){
        return $this->hasOne(Applicant::class);
    }  

    public function program(){
        return $this->belongsTo(Program::class);
    }      

    public function balance(){
        return $this->hasOne(Balance::class, 'id', 'balance_id');
    }  

    public function discounts(){
        return $this->hasMany(StudentDiscounts::class, 'student_id', 'id');
    }

    public function setAgeAttribute($id)
    {
        $student = Student::find($id);    

        $this->attributes['age'] = Carbon::parse($student->dob)->age;

    }

    public function getAgeAttribute()
    {
        return $this->attributes['age'];
    }


    public function setProgramDescAttribute($id)
    {
        $program = Program::find($id);

        $this->attributes['program_desc'] = $program->desc;
    }

    public function getProgramDescAttributes()
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

    public function setDeptAttribute($id)
    {
        if($id == 0)
            $this->attributes['dept'] = "SHS";
        else
            $this->attributes['dept'] = "College";
    }

    
    public function getDeptAttribute()
    {
        return $this->attributes['dept'];
    }

    public function setRatingAttribute($values = [])
    {
        

        $subjectTaken = SubjectTaken::where('student_id', $values['student_id'])
                                    ->where('class_id', $values['class_id'])->first();

        $this->attributes['rating'] = $subjectTaken->rating;
    }

    
    public function getRatingAttribute()
    {
        return $this->attributes['rating'];
    }


    public function setLevelDescAttribute($value)
    {
        $level = "Undefined";
        switch($value){
            case 1:
                $level = "Grade 11";
            break;
            case 2:
                $level = "Grade 12";
            break;
            case 11:
                $level = "Freshman";
            break;
            case 12:
                $level = "Sophomore";
            break;

        }

        $this->attributes['level_desc'] = $level;
    }

    public function getLevelDescAttribute()
    {
        return $this->attributes['level_desc'];
    }  

    public function getPronounAttribute(){
        if($this->gender == 'male')
            $this->attributes['pronoun'] = 'his';
        else if($this->gender == 'female')
            $this->attributes['pronoun'] = 'her';
        else 
            $this->attributes['pronoun'] = 'they';

        return $this->attributes['pronoun'];
    }

    public function getTuitionWithoutDiscountAttribute(){
        $merged_fees = Fee::getMergedFees($this->department, $this->program_id, $this->level, $this->semester);
        $setting = Setting::first();

        $total_unit_price_this_sem = 0;
        $total_fee_amount = 0;
        $tuition = 0;

        if(!$this->program->is_tesda){
            $subjects_set = SubjectTaken::enrolledSubjectsbyStudent($this->id);          
            foreach($subjects_set as $subject){
                $total_unit_price_this_sem+= $this->department == 0 ? $setting->shs_price_per_unit * $subject->units : $setting->college_price_per_unit * $subject->units;
            } 

            foreach($merged_fees as $fee){
                $total_fee_amount+= $fee->amount;
            }

            $tuition = $total_unit_price_this_sem + $total_fee_amount;

        } else {

            foreach($merged_fees as $fee){
                $total_fee_amount+= $fee->amount;
            }

            $tuition = $total_unit_price_this_sem + $total_fee_amount;

        }

        return $this->attributes['tuition_without_discount'] = $tuition;

    }

    public function getTuitionWithDiscountAttribute(){
        $total_discount_percentage = 0;
        $discounts = collect(new Discount);

        foreach($this->discounts as $discount_rel){
            $discounts->push(Discount::find($discount_rel->discount_id));
        }

        foreach($discounts as $discount){
            $total_discount_percentage += $discount->percentage;
        }        

        $tuition = $this->tuition_without_discount * ($total_discount_percentage / 100);

        return $this->attributes['tuition_with_discount'] = $tuition;

    }

}
