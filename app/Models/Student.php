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
use App\Models\RegistrarRequest;
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
                          'tuition_with_discount' => null,
                          'level_desc_better' => null,
                          'drop_status' => null,
                          'drop_request' => null,
                          'request_rating_update' => null
                        ];

    public function member(){
        return $this->hasOne(Member::class, 'member_id', 'id');
    }    

    public function subject_taken(){
        return $this->hasMany(SubjectTaken::class)->withTrashed();
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

    public function registrar_requests(){
        return $this->hasMany(RegistrarRequest::class, 'requestor_id', 'id');
    }  
    
    public function hasUnarchivedClass(){
        foreach($this->subject_taken as $subject_taken){
            if(!is_null($subject_taken->class)){
                if($subject_taken->class->archive == 0)
                    return true;                
            }
        }

        return false;
    }

    public function setDropStatusAttribute($status){
        $this->attributes['drop_status'] = $status;
    }

    public function getDropStatusAttribute(){
        return $this->attributes['drop_status'];
    }
    
    public function setDropRequestAttribute($request){
        $this->attributes['drop_request'] = $request;
    }

    public function getDropRequestAttribute(){
        return $this->attributes['drop_request'];
    }

    public function setRequestRatingUpdateAttribute($request){
        $this->attributes['request_rating_update'] = $request;
    }

    public function getRequestRatingUpdateAttribute(){
        return $this->attributes['request_rating_update'];
    }

    public function stillToBeTakenSubjects(){
        $values = [
                    'department' => $this->department,
                    'program' => $this->program_id,
                    'level' => ($this->department? 12 : 2),
                    'semester' => 2,
                  ];

        $subjects = Subject::allWhere($values);

        $subjects = $subjects->filter(function($subject) {
            $valid = true;
            if($this->subject_taken->where('subject_id', $subject->id)){

                foreach($this->subject_taken->where('subject_id', $subject->id) as $subject_taken){
                    if($subject_taken->rating <= 3)
                        $valid = false;
                    if($subject_taken->rating == 3.5)
                        $valid = false;
                    if($subject_taken->rating == 4.5)
                        $valid = false;
                    if($subject_taken->rating == 4)
                        $valid = false;
                    if($subject_taken->rating == 5)
                        $valid = false;
                }             
            }

            if($valid)
                return $subject;
        });

        $subjects = $subjects->filter(function($subject) {
            $valid = false;
            if($subject->hasPreReqs()){                
                foreach($subject->pre_reqs as $pre_req){
                    foreach($this->subject_taken as $subject_taken){
                        if($subject_taken->subject_id == $pre_req->id){
                            if($subject_taken->rating <= 3){
                                $valid = true;
                            } 
                        }
                    }                   
                }
            } else {
                $valid = true;
            }
            if($valid)  
                return $subject;

        });

        return $subjects->values();

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

    public function get_level_description()
    {
        
        switch($this->level){
            case 1:
                return "Grade 11";
            break;
            case 2:
                return "Grade 12";
            break;
            case 11:
                return "Freshman";
            break;
            case 12:
                return "Sophomore";
            break;

        }        
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

    public function subjectsTakenThisSemester(){
        $subjects_taken = SubjectTaken::enrolledSubjectsbyStudent($this->id);

        if($subjects_taken->count() > 0){
            foreach ($subjects_taken as $subject_taken) {
                if(!is_null($subject_taken->class)){
                    $subject_taken->class;            
                    $subject_taken->class->faculty;            
        
                    $schedules = $subject_taken->class->schedules->sortBy('created_at');
                    $subject_taken->class->schedules = $schedules->values();
        
                    foreach ($subject_taken->class->schedules as $sched) {
                        $sched->formatted_start = $sched->start_time;
                        $sched->formatted_until = $sched->until;
                        $sched->day_name = $sched->day;
                        $sched->room_name = $sched->id;
                    }
                }

            }
        }

        return $subjects_taken;
    }

    public function subjectsTakenThisSemesterWithRatings(){
        $subjects_taken = SubjectTaken::subjectsTakenThisSemester($this->id);
        
        foreach ($subjects_taken as $subject_taken) {
            $subject_taken->class;            
            $subject_taken->class->faculty;            

            // $schedules = $subject_taken->class->schedules->sortBy('created_at');
            // $subject_taken->class->schedules = $schedules->values();

            // foreach ($subject_taken->class->schedules as $sched) {
            //     $sched->formatted_start = $sched->start_time;
            //     $sched->formatted_until = $sched->until;
            //     $sched->day_name = $sched->day;
            //     $sched->room_name = $sched->id;
            // }
        }

        return $subjects_taken;
    }

}
