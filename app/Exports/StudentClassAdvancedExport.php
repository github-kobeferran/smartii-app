<?php

namespace App\Exports;

use App\Models\StudentClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class StudentClassAdvancedExport implements  FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    public function __construct($from_year, $to_year, $dept, $prog, $level, $sem, $faculty, $subj, $ac)
    {
        $this->from_year = $from_year;
        $this->to_year = $to_year;
        $this->dept = $dept;
        $this->prog = $prog;
        $this->level = $level;
        $this->sem = $sem;
        $this->faculty = $faculty;
        $this->subj = $subj;        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $classes = StudentClass::all();

        $classes = $classes->filter(function($class) {
            return $class->subjectsTaken->first()->from_year >= $this->from_year && $class->subjectsTaken->first()->to_year <= $this->to_year;
        });

        $classes = $classes->filter(function($class){
            return $class->subjectsTaken->first()->student->program->department == $this->dept;
        });

        if(!empty($this->prog)){
            $classes = $classes->filter(function($class) {
                return $class->subjectsTaken->first()->student->program->id == $this->prog;
            });
        }

        if(!empty($this->level)){
            $classes = $classes->filter(function($class) {
                return $class->subjectsTaken->first()->student->level == $this->level;
            });
        }

        if(!empty($this->sem)){
            $classes = $classes->filter(function($class) {
                return $class->subjectsTaken->first()->semester == $this->sem;
            });
        }

        if(!empty($this->faculty)){
            $classes = $classes->filter(function($class) {
                return $class->faculty->id == $this->faculty;
            });
        }
        
        if(!empty($this->subj)){
            $classes = $classes->filter(function($class) use($subj){
                return $class->subjectsTaken()->first()->subject_id == $subj;
            });
        }

        if(!empty($this->ac)){
            $classes = $classes->filter(function($class){
                return $class->archive == 1;
            });
        }        

        return $classes;
    }

    public function headings(): array
    {
        return [
            'NAME',
            'A.Y/SEMESTER',
            'DEPARTMENT AND PROGRAM',
            'LEVEL',            
            'FACULTY',
            'SUBJECT',
            'STUDENTS',            
            'ARCHIVED',            
        ];
    }    
    
    public function map($classes): array
    {          
        return [     
            $classes->class_name,
            $classes->subjectsTaken->first()->from_year . '-' . $classes->subjectsTaken->first()->from_year . '/' . $classes->subjectsTaken->first()->semester > 1 ? '2nd Sem' : '1st Sem',
            $classes->subjectsTaken->first()->student->department ? 'SHS ':'COLLEGE ' . $classes->subjectsTaken->first()->student->program->abbrv . ' - ' . $classes->subjectsTaken->first()->student->program->desc,
            $classes->subjectsTaken->first()->student->level,
            'Intr ' . $classes->faculty->first_name . ' ' . $classes->faculty->last_name,
            $classes->subjectsTaken->first()->subject->code . ' - '. $classes->subjectsTaken->first()->subject->desc,
            $classes->students_list_string(),
            $classes->archived ? 'archived' : 'NOT archived',
        ];
    }
  
}
