<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('student.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'department' => 'required', // 0 = shs, 1 = college
            'level' => 'required', // first_year, grade_11
            'program_id' => 'required',
            'semester' => 'required', // 1 = first sem .. 
            'email' => 'required',
            'contact' => '',
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => '',
            'dob' => 'required',
            'gender' => 'required',
            'nationality' => '',
            'civil_status' => 'required',
            'permanent_address' => 'required',
            'present_address' => 'required',
            'father_name' => '',
            'father_contact' => '',
            'father_occupation' => '',
            'mother_name' => '',
            'mother_contact' => '',
            'mother_occupation' => '',
            'guardian_name' => 'required',
            'guardian_contact' => 'required',
            'guardian_occupation' => 'required',
            'emergency_person_name' => 'required',
            'emergency_person_contact' => 'required',
            'emergency_person_address' => 'required',
            'elementary' => '',
            'elementary_year' => '',
            'junior_high' => '',
            'junior_high_year' => '',
            'senior_high' => '',
            'senior_high_year' => '',
            'last_school' => '',
            'last_school_year' => '',
        ]);
        
        $student = new Student;

        $student->last_name = $request->input('last_name');
        $student->first_name = $request->input('first_name');
        $student->middle_name = $request->input('middle_name');
        $student->gender = $request->input('gender');
        $student->dob = $request->input('dob');
        $student->nationality = $request->input('nationality');
        $student->civil_status = $request->input('civil_status');
        $student->religion = $request->input('religion');
        $student->email = $request->input('email');
        $student->contact = $request->input('contact');

        $student->level = $request->input('level');
        $student->department = $request->input('department');
        $student->program_id = $request->input('program_id');
        $student->semester = $request->input('semester');
        $student->permanent_address = $request->input('permanent_address');
        $student->present_address = $request->input('present_address');

        $student->father_name = $request->input('father_name');
        $student->father_contact = $request->input('father_contact');
        $student->father_occupation = $request->input('father_occupation');

        $student->mother_name = $request->input('mother_name');
        $student->mother_contact = $request->input('mother_contact');
        $student->mother_occupation = $request->input('mother_occupation');

        $student->guardian_name = $request->input('guardian_name');
        $student->guardian_contact = $request->input('guardian_contact');
        $student->guardian_occupation = $request->input('guardian_occupation');

        $student->emergency_person_name = $request->input('emergency_person_name');
        $student->emergency_person_contact = $request->input('emergency_person_contact');
        $student->emergency_person_address = $request->input('emergency_person_address');

        $student->elementary = $request->input('elementary');
        $student->elementary_year = $request->input('elementary_year');

        $student->junior_high = $request->input('junior_high');
        $student->junior_high_year = $request->input('junior_high_year');

        $student->senior_high = $request->input('senior_high');
        $student->senior_high_year = $request->input('senior_high_year');

        $student->last_school = $request->input('last_school');
        $student->last_school_year = $request->input('last_school_year');

        

        $student->section_id = '1';
        $student->created_by_admin = '1';
        $student->balance_id = '1';
        $student->cur_status = '0';
        $student->transferee = '0';

        Subject::findSubjectSet($request->input('program_id'), $request->input('level'), $request->input('semester'));

        if($request->input('student_id') != ''){
            $student->student_id = $request->input('student_id');
            $student->save();

            return redirect('admin/create/student')->with('success', 'Student Created Successfully');
        } else {
            $student->save();
            
            $id = $student->id;
            $year =  date("y");
            $prefix = "C";
            $user_id = $prefix . $year . '-' . sprintf('%04d', $id);

            $student->student_id = $user_id;
            $student->save();

            return redirect('admin/create')->with('success', 'Student Created Successfully');
        }

        
                
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
