<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Setting;
use App\Models\Program;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ActiveStudentsExport implements FromCollection , WithMapping, WithHeadings, WithStyles
{

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],           
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $fromyear = Setting::first()->from_year;        
        $sem = Setting::first()->semester;

        return Student::whereYear('updated_at', $fromyear)
                      ->where('semester', $sem)
                      ->get();                              
    }

    public function map($students): array
    {
        $dept = "";
        $semester = "";

        switch($students->level){
            case 1:
                $level = "Grade 11";
            break;
            case 2:
                $level = "Grade 12";
            break;
            case 11:
                $level = "First Year";
            break;
            case 12:
                $level = "Second Year";
            break;
        }

        if($students->department == 0)
            $dept = "SHS";
        else
            $dept = "College";


        return [
            $students->student_id,
            ucfirst($students->last_name),
            ucfirst($students->first_name),
            ucfirst($students->middle_name),           
            ucfirst($students->member->user->email),           
            Carbon::parse($students->dob)->isoFormat('MMMM Do, YYYY'),
            Carbon::parse($students->dob)->age . ' years old',
            $dept . ' - ' . Program::find($students->program_id)->desc,
            $level,            
        ];
    }

    public function headings(): array
    {
        return [
            'Student-ID',
            'Last Name',
            'First Name',
            'Middle Name',
            'Email',
            'Date of Birth',
            'Age',
            'Program',
            'Level',
        ];
    }

}
