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
            'contact' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => '',
            'dob' => 'required',            
            'permanent_address' => 'required',
            'present_address' => 'required',           
        ]);
        
        $student = new Student;

        $student->last_name = $request->input('last_name');
        $student->first_name = $request->input('first_name');
        $student->middle_name = $request->input('middle_name');
        $student->gender = $request->input('gender');
        $student->dob = $request->input('dob');
      
        $student->email = $request->input('email');
        $student->contact = $request->input('contact');

        $student->level = $request->input('level');
        $student->department = $request->input('department');
        $student->program_id = $request->input('program_id');
        $student->semester = $request->input('semester');
        $student->permanent_address = $request->input('permanent_address');
        $student->present_address = $request->input('present_address');                  

        $student->section_id = '1';
        $student->created_by_admin = '1';
        $student->balance_id = '1';
        $student->cur_status = '0';
        $student->transferee = '0';

        $subjectSet = Subject::findSubjectSet($request->input('program_id'), $request->input('level'), $request->input('semester'));
        
        foreach($subjectSet as $subject){
            Subject::PreReqChecker($subject, )
        }
       
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
