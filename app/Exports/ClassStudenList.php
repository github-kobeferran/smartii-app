<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ClassStudenList implements FromCollection, WithMapping, WithHeadings, WithStyles
{

    protected $students;
    

    public function __construct($students)
    {
        $this->students = $students;        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->students;
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

    public function map($students): array
    {       
        
        return [
            $students->student_id,
            ucfirst($students->last_name),
            ucfirst($students->first_name),
            $students->middle_name ?? '',                                           
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
