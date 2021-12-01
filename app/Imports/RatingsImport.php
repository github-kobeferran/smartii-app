<?php

namespace App\Imports;

use App\Models\SubjectTaken;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class RatingsImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    private $class_id;
    private $subjects_taken;

    public function __construct($class_id){
        $this->class_id = $class_id;
        $this->subjects_taken = SubjectTaken::where('class_id', $class_id)->get();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if(!isset($row['rating']))
                break;

            $rating = floatval($row['rating']);

            if($rating > 5 || $rating < 1 || ($rating >= 3.1 && $rating <= 3.9) || ($rating >= 4.1 && $rating <= 4.9))
                continue;
            

            if(fmod($rating, .25) != 0.0)  
                continue;            


            $student = Student::where('student_id', $row['student_id'])->first();
            $subject_taken = $this->subjects_taken->where('student_id', $student->id)->first();                  

            // dd(gettype($subject_taken->rating));

            $subject_taken->rating = $rating;         
            $subject_taken->save();   
        }
    }    
    
}
