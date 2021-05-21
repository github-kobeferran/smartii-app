<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentClass;
use App\Models\Balance;
use App\Models\SubjectTaken;
use Illuminate\Support\Facades\Validator;

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

        $validator = Validator::make($request->all(), [
            'department' => 'required', // 0 = shs, 1 = college
            'level' => 'required', // first_year, grade_11
            'program_id' => 'required', // 3 =>  shs, 4 => college
            'semester' => 'required', 
            'email' => 'required',
            'contact' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => '',
            'dob' => 'required',            
            'permanent_address' => '',
            'present_address' => '',      
        ]);
    
        if ($validator->fails()) {
            return redirect()
                            ->route('adminCreate')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('student', true);
        }      
        
        if($request->input('student_id') != ''){
            
            if(Student::where('student_id', $request->input('student_id'))->exists()){
                return redirect()
                            ->route('adminCreate')
                            ->with('error', 'Student ID Already Exist')
                            ->with('student', true);
            }
        }

        if($request->input('email')){
            
            if(Student::where('email', $request->input('email'))->exists()){
                return redirect()
                            ->route('adminCreate')
                            ->with('error', 'Email Already Exist')
                            ->with('student', true);
            }
        }
        
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

        $student->created_by_admin = '1';              
        $student->student_type = '0';
        $student->transferee = '0';  
        
        $status = '';
        $message = '';
        $id = 0;                  
                               
        if($request->input('student_id') != ''){
            $student->student_id = $request->input('student_id');                        

            if($student->save()){

                $status = 'success';
                $message.= 'Student Creation Successful';                

            } else {
                
                $status = 'error';
                $message.= 'Student Creation Failed';        

            }

            
        } else {               

            $student->save();
            
            $id = $student->id;

            $balance_id = new Balance;    

            $student->balance_id = $balance_id->init($student);
            
            $year =  date("y");
            $prefix = "C";
            $user_id = $prefix . $year . '-' . sprintf('%04d', $id);

            $student->student_id = $user_id;

            if($student->save()){

                $status = 'success';
                $message.= 'Student Creation Successful';                

            } else {
                
                $status = 'error';
                $message.= 'Student Creation Failed';        

            }
                      
        }

        $subjects = $request->input('subjects');
        $ratings = $request->input('ratings');
        $from_years = $request->input('from_years');
        $to_years = $request->input('to_years');        
        $semesters = $request->input('semesters');        


        for($i=0; $i<count($subjects); $i++){        

            $subjectToTake = new SubjectTaken;
            $subjectToTake->student_id = $id;
            $subjectToTake->subject_id = $subjects[$i];

            if($ratings[$i] != '')
                $subjectToTake->rating = $ratings[$i];
            else 
                $subjectToTake->rating = 4.5;

            if($from_years[$i] != '')
                $subjectToTake->from_year = $from_years[$i];
            else 
                $subjectToTake->from_year = config('settings.academic_year.from_year');

            if($to_years[$i] != '')
                $subjectToTake->to_year = $to_years[$i];
            else 
                $subjectToTake->to_year = config('settings.academic_year.to_year');

            if($semesters[$i] != '')
                $subjectToTake->semester = $semesters[$i];
            else 
                $subjectToTake->semester = config('settings.semester');                                    

            $subjectToTake->save();
        }                      
       
        return redirect()->route('adminCreate')->with($status, $message)->with('student', true); 
                
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
