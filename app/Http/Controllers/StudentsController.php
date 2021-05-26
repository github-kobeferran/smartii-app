<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentClass;
use App\Models\Balance;
use App\Models\SubjectTaken;
use App\Models\Setting;
use App\Models\User;

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
            'contact' => 'required|numeric',
            'last_name' => 'required|regex:/^[\s\w-]*$/|max:100',
            'first_name' => 'required|regex:/^[\s\w-]*$/|max:100',
            'middle_name' => 'regex:/^[\s\w-]*$/|max:100',
            'dob' => 'required|date',            
            'permanent_address' => 'max:191',
            'present_address' => 'max:191',      
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

            if(User::where('email', $request->input('email'))->exists()){
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
        $balanceID = 0;                 
        $department = $request->input('department');
                               
        if($request->input('student_id') != ''){
            $student->student_id = $request->input('student_id');                         

            $balance_id = new Balance;             
            $balanceID = $balance_id->init($student);   

            $student->balance_id = $balanceID;

            if($student->save()){
                $id = $student->id;

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
            $balanceID = $balance_id->init($student);

            $student->balance_id = $balanceID;
            
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

        $totalBalance = 0;

        $studentBalance = Balance::find($balanceID);

        $subjectsToBeTakenLength = count($subjects);
        $valid = true;
        
        
        if(is_countable($subjects) > 0 ){

            for($i=0; $i < $subjectsToBeTakenLength; $i++){

                $subjectToTake = new SubjectTaken;
    
                $subjectToTake->student_id = $id;
                $subjectToTake->subject_id = $subjects[$i]; 
                
                
                $subject = Subject::find($subjects[$i]);   


                if( ($ratings[$i] != '' && $from_years[$i] == '' && $to_years[$i] == '' && $semesters[$i] == '') ||
                    ($ratings[$i] == '' && $from_years[$i] != '' && $to_years[$i] == '' && $semesters[$i] == '') ||
                    ($ratings[$i] == '' && $from_years[$i] == '' && $to_years[$i] != '' && $semesters[$i] == '') ||
                    ($ratings[$i] == '' && $from_years[$i] == '' && $to_years[$i] != '' && $semesters[$i] != '')
                  ){
                    $valid = false;
                }
                                     
                   
                if($valid){

                    if($ratings[$i] == '' && $from_years[$i] == '' && $to_years[$i] == '' && $semesters[$i] == ''){

                        if($department == 0)
                            $totalBalance+= Setting::first()->shs_price_per_unit * $subject->units;
                        else 
                            $totalBalance+= Setting::first()->college_price_per_unit * $subject->units; 
                               
                    } else {
    
                        $totalBalance+= 0;
    
                    }
    
                    if($i == $subjectsToBeTakenLength - 1 ) {                
                        $studentBalance->amount = $totalBalance;
                        $studentBalance->save();
                    }
                    
        
                    if($ratings[$i] != '')
                        $subjectToTake->rating = $ratings[$i];
                    else 
                        $subjectToTake->rating = 4.5;
        
                    if($from_years[$i] != '')
                        $subjectToTake->from_year = $from_years[$i];
                    else 
                        $subjectToTake->from_year = Setting::first()->from_year;
        
                    if($to_years[$i] != '')
                        $subjectToTake->to_year = $to_years[$i];
                    else 
                        $subjectToTake->to_year = Setting::first()->to_year;
        
                    if($semesters[$i] != '')
                        $subjectToTake->semester = $semesters[$i];
                    else 
                        $subjectToTake->semester = Setting::first()->semester;                                  
        
                    $subjectToTake->save();

                } else {
                    $status = 'warning';
                    $message = 'Subjects not added due to missing data. Please use Add Subjects to Students to add it again.';

                    return redirect()->route('adminCreate')->with($status, $message)->with('student', true);
                }
                                                                                                  
            }                      
           

        } else {
            $status = 'warning';
            $message = 'Student Created with no Subjects taken';
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
