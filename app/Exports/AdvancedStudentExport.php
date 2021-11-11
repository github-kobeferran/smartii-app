<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Program;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class AdvancedStudentExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;


    public function __construct($dept, $prog, $level)
    {
        $this->dept = $dept;
        $this->prog = $prog;
        $this->level = $level;
    }

    public function query()
    {
        $students = Student::query()->where('department', $this->dept);

        if(!empty($this->prog))
            $students->where('program_id', $this->prog);

        if(!empty($this->level))
            $students->where('level', $this->level);

        return $students;
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
            Carbon::parse($students->created_at)->isoFormat('MMMM Do, YYYY'),
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
            'Admitted in SMARTII.CC at',
        ];
    }

}
