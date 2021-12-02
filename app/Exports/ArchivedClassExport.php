<?php

namespace App\Exports;

use App\Models\StudentClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArchivedClassExport implements FromCollection, WithMapping, WithStyles, WithHeadings
{

    private $class;

    public function __construct($class){
        $this->class = $class;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->class->subjectsTaken;        
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Last Name',         
            'First Name',         
            'Middle Name',                           
            'Rating',                           
        ];
    }

    public function map($subject_taken): array
    {       
        
        return [
            $subject_taken->student->student_id,
            ucfirst($subject_taken->student->last_name),
            ucfirst($subject_taken->student->first_name),
            $subject_taken->students->middle_name ?? '',
            $subject_taken->rating,                                           
        ];
    }



    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],           
        ];
    }


}
