<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Program;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class StudentsPerYearSheet implements FromCollection, WithTitle,  WithMapping, WithHeadings, WithStyles
{
    private $month;
    private $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

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
        
        return Student::whereYear('created_at', $this->year)->get();

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
            'Date of Birth',
            'Age',
            'Department and Program',
            'Level',            
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return 'Year ' . $this->year;
    }
}