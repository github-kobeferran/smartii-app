<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Program;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class StudentsExport implements FromCollection, WithMapping, WithHeadings, WithMultipleSheets
{

    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $student = Student::orderBy('created_at' , 'asc')->first();
        $start_year = Carbon::parse($student->created_at)->year;

        $student = Student::orderBy('created_at' , 'desc')->first();
        $end_year = Carbon::parse($student->created_at)->year;
        

        for ($year = $start_year; $year <= $end_year; $year++) {
            
            $sheets[] = new StudentsPerYearSheet($year);            

        }       

        return $sheets;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::all();                         
    }

    public function map($students): array
    {
        return [
            $students->student_id,
            ucfirst($students->last_name),
            ucfirst($students->first_name),
            ucfirst($students->middle_name),   
            $students->member->user->email,              
            Carbon::parse($students->dob)->isoFormat('MMMM Do, YYYY'),
            Carbon::parse($students->dob)->age . ' years old',
            Program::find($students->program_id)->desc,
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
        ];
    }

}
