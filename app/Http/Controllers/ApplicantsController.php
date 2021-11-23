<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Mail\WelcomeApplicant;
use App\Mail\ApprovedApplicant;
use App\Mail\RejectApplicant;
use App\Mail\RestoreApplicant;
use App\Models\User;
use App\Models\Applicant;
use App\Models\Member;
use App\Models\Student;
use App\Models\Program;
use App\Models\Subject;
use App\Models\SubjectTaken;
use App\Models\Fee;
use App\Models\Balance;
use App\Models\Setting;
use Carbon\Carbon;



class ApplicantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->access_grant == 1){
            Auth::logout();
            return redirect()->back()->with('error', 'User Access not granted. Please contact the Site Administrator for more details.');
        }

        return view('applicant.index');
    }

    public function form()
    {
        if(auth()->user()->access_grant == 1){
            Auth::logout();
            return redirect()->back()->with('error', 'User Access not granted. Please contact the Site Administrator for more details.');
        }

        return view('applicant.admission');
    }

    public function status()
    {

        if(auth()->user()->access_grant == 1){
            Auth::logout();
            return redirect()->back()->with('error', 'User Access not granted. Please contact the Site Administrator for more details.');
        }
        return view('applicant.status');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){                 
        if($request->method() != 'POST'){
            return redirect()->back();
        }        

        if(Applicant::where('email', auth()->user()->email)->exists()) {
            return redirect()->route('appStatus');
        }


        $validator = Validator::make($request->all(), [
            
            'dept' => 'required',                                     

        ],
        [

            'dept.required' => 'Select a Department and Program First.',            
            'prog.required' => 'Select a Department and Program First.',            
                        
        ]);

        
        if ($validator->fails()) {
            return redirect()->route('admissionForm')
                         ->withErrors($validator)                        
                         ->with('active', 'dept');
        }

        $before_date = Carbon::now()->subYears(15);
        $req_age = 15;
        
        if($request->input('dept')){
            $before_date = Carbon::now()->subYears(18);
            $req_age = 18;
        }
        
        $after_date = new Carbon('1903-01-01');

        $validated = ['dept' => $request->input('dept'),
                      'prog' => $request->input('program_id'),
                      'prog_desc' => Program::find($request->input('program_id'))->desc,
                     ];        

        $validator = Validator::make($request->all(), [
            
            'l_name' => 'required|max:100|regex:/^[a-zA-Z Ññ-]*$/', 
            'f_name' => 'required|max:100|regex:/^[a-zA-Z Ññ-]*$/', 
            'm_name' => 'nullable|max:100|regex:/^[a-zA-Z Ññ-]*$/', 
            'present_address' => 'required|max:191', 
            'last_school' => 'required|max:191|regex:/^[a-zA-Z Ññ-]*$/',
            'dob' => 'required|date|before:'. $before_date->toDateString() . '|after:' . $after_date,            

        ],
        [

            'l_name.required' => 'Last Name is required.',
            'l_name.max' => 'Last Name must be less than a hundred characters.',
            'l_name.regex' => 'Some Last Name characters are invalid.',

            'f_name.required' => 'First Name is required.',
            'f_name.max' => 'First Name must be less than a hundred characters.',
            'f_name.regex' => 'Some First Name characters are invalid.',
            
            'm_name.max' => 'Middle Name must be less than a hundred characters.',
            'm_name.regex' => 'Some Middle Name characters are invalid.',

            'present_address.required' => 'Present Address is required.',

            'last_school.required' => 'Last School Attended is required.',

            'dob.required' => 'Date of Birth is required.',
            'dob.date' => 'Date of Birth is invalid.',
            'dob.before' => 'Must be ' .  $req_age . ' years or older. Date of Birth must be before ' . $before_date->isoFormat('MMMM DD, YYYY'),
            'dob.after' => 'Date of Birth must be after ' .  $after_date->isoFormat('MMMM DD, YYYY'),

        ]);        

       

        if ($validator->fails()) {         
            return redirect()->route('admissionForm')
                         ->withErrors($validator)                             
                         ->with('active', 'resubmit_personal')
                         ->with('dept', $validated['dept'])
                         ->with('prog', $validated['prog'])
                         ->with('prog_desc', $validated['prog_desc']);
        }

        $validated +=  ['l_name' => $request->input('l_name'),
                               'f_name' => $request->input('f_name'),
                               'm_name' => $request->input('m_name'),
                               'dob' => $request->input('dob'),
                               'gender' => $request->input('gender'),
                               'present_address' => $request->input('present_address'),
                               'last_school' => $request->input('last_school')];
        

        $validator = Validator::make($request->all(), [
            
            'id_pic' => 'required|file|mimes:jpeg|max:300', 
            'birth_cert' => 'required|file|mimes:jpeg|max:300', 
            'good_moral' => 'required|file|mimes:jpeg|max:300', 
            'report_card' => 'required|file|mimes:jpeg|max:300', 

        ],
        [
            'id_pic.required' => 'The 1x1 ID Picture is required.',
            'id_pic.max' => 'The 1x1 ID Picture must not be more than 300KB in size.',
            'id_pic.mimes' => 'The 1x1 ID Picture File must be in JPEG file format.',

            'birth_cert.required' => 'The Birth Certificate File is required.',
            'birth_cert.max' => 'The Birth Certificate must not be more than 300KB in size.',
            'birth_cert.mimes' => 'The Birth Certificate File must be in JPEG file format.',
            
            'good_moral.required' => 'The Good Moral Certificate File is required.',
            'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
            'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',

            'report_card.required' => 'The Form 138 File is required.',
            'report_card.max' => 'The Form 138 File must not be more than 300KB in size.',
            'report_card.mimes' => 'The Form 138 File must be in JPEG file format.',

        ]);

        

        if ($validator->fails()) {   
            return redirect()->route('admissionForm')
                         ->withErrors($validator)
                         ->with('active', 'resubmit_files')
                         ->with('dept', $validated['dept'])
                         ->with('prog', $validated['prog'])
                         ->with('prog_desc', $validated['prog_desc'])
                         ->with('l_name', $validated['l_name'])
                         ->with('f_name', $validated['f_name'])
                         ->with('m_name', $validated['m_name'])
                         ->with('dob', $validated['dob'])
                         ->with('gender', $validated['gender'])
                         ->with('present_address', $validated['present_address'])
                         ->with('last_school', $validated['last_school']);
        }

        if( $request->hasFile('id_pic')  && $request->hasFile('report_card') &&
            $request->hasFile('birth_cert') && $request->hasFile('good_moral')){

            // get filename with the extension
            $idPicwithExt = $request->file('id_pic')->getClientOriginalName();
            // get just filename
            $idPicName = pathinfo($idPicwithExt, PATHINFO_FILENAME);
            // get just ext
            $idPicExt = $request->file('id_pic')->getClientOriginalExtension();
            //Filename to store
            $idPicToStore = $idPicName.'_'.time().'.'.$idPicExt;
            // upload image
            $id_pic_path = $request->file('id_pic')->storeAs('public/images/applicants/id_pics', $idPicToStore);


            $birth_certwithExt = $request->file('birth_cert')->getClientOriginalName();
            $birth_certName = pathinfo($birth_certwithExt, PATHINFO_FILENAME);
            $birth_certExt = $request->file('birth_cert')->getClientOriginalExtension();
            $birth_certToStore = $birth_certName.'_'.time().'.'.$birth_certExt;
            $birth_cert_path = $request->file('birth_cert')->storeAs('public/images/applicants/birth_certs', $birth_certToStore);

            $good_moralwithExt = $request->file('good_moral')->getClientOriginalName();           
            $good_moralName = pathinfo($good_moralwithExt, PATHINFO_FILENAME);    
            $good_moralExt = $request->file('good_moral')->getClientOriginalExtension();
            $good_moralToStore = $good_moralName.'_'.time().'.'.$good_moralExt;
            $good_moral_path = $request->file('good_moral')->storeAs('public/images/applicants/good_morals', $good_moralToStore);
            
            $report_cardwithExt = $request->file('report_card')->getClientOriginalName();
            $report_cardName = pathinfo($report_cardwithExt, PATHINFO_FILENAME);            
            $report_cardExt = $request->file('report_card')->getClientOriginalExtension();
            $report_cardToStore = $report_cardName.'_'.time().'.'.$report_cardExt;
            $report_card_path = $request->file('report_card')->storeAs('public/images/applicants/report_cards', $report_cardToStore);            
            
        }

        $applicant = new Applicant;

        $applicant->dept = $request->input('dept');
        $applicant->program = $request->input('program_id');

        $applicant->last_name = $request->input('l_name');
        $applicant->first_name = $request->input('f_name');
        $applicant->middle_name = $request->input('m_name');

        $applicant->email = auth()->user()->email;
        $applicant->dob = $request->input('dob');
        $applicant->gender = $request->input('gender');
        $applicant->present_address = $request->input('present_address');
        $applicant->last_school = $request->input('last_school');

        $applicant->id_pic  = $idPicToStore;
        $applicant->birth_cert  =  $birth_certToStore;
        $applicant->good_moral  = $good_moralToStore;
        $applicant->report_card  = $report_cardToStore;

        $applicant->save();    

        $member = new Member;        

        $member->user_id = auth()->id();
        $member->member_type = auth()->user()->user_type;
        $member->member_id = $applicant->id;

        $member->save();

        $name =  ucfirst($applicant->first_name) . ' ' . ucfirst($applicant->last_name);
        $dept = '';

        if($applicant->dept == 0)
            $dept = "Senior High School";
        else
            $dept = "College";

        $program = Program::find($applicant->program)->desc;

        Mail::to(auth()->user())->send(new WelcomeApplicant($name, $dept, $program));

        return redirect()->route('appStatus');

               
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function show(Applicant $applicant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function edit(Applicant $applicant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Applicant $applicant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Applicant $applicant)
    {
        //
    }
    



    public function showPrograms($dept){
        
        return Program::where('department', $dept)
                            ->where('id', '!=', 3)
                            ->where('id', '!=', 4)
                            ->get()->toJson();

    }


    public function getProg($id){

        return Program::find('id')->first()->toJson();

    }

    public function resubmit(Request $request){

        if($request->method() != 'POST'){
            redirect()->back();
        }                  

        $applicant = Applicant::find($request->input('id'));
        $newResubmitted = '0000';     
        

        switch($request->input('status')){
            case '1000':                
                $validator = Validator::make($request->all(), [
                    
                    'id_pic' => 'required|file|mimes:jpeg|max:300',                   
                ],
                [
        
                    'id_pic.required' => 'The 1x1 ID Picture is required.',
                    'id_pic.max' => 'The 1x1 ID Picture must not be more than 300KB in size.',
                    'id_pic.mimes' => 'The 1x1 ID Picture File must be in JPEG file format.',                     
                ]);

            break;
            case '1100':      
                                
                
                $validator = Validator::make($request->all(), [
                    
                    'id_pic' => 'required|file|mimes:jpeg|max:300',
                    'birth_cert' => 'required|file|mimes:jpeg|max:300',                           
                

                ],
                [
                    'id_pic.required' => 'The 1x1 ID Picture is required.',
                    'id_pic.max' => 'The 1x1 ID Picture must not be more than 300KB in size.',
                    'id_pic.mimes' => 'The 1x1 ID Picture File must be in JPEG file format.',   
                    
                    'birth_cert.required' => 'The Birth Certificate File is required.',
                    'birth_cert.max' => 'The Birth Certificate must not be more than 300KB in size.',
                    'birth_cert.mimes' => 'The Birth Certificate File must be in JPEG file format.',  
                ]);

            break;
            case '1010':

                $validator = Validator::make($request->all(), [
                    
                    'id_pic' => 'required|file|mimes:jpeg|max:300',                                            
                    'good_moral' => 'required|file|mimes:jpeg|max:300',                           
                    

                ],
                [
        
                    'id_pic.required' => 'The 1x1 ID Picture is required.',
                    'id_pic.max' => 'The 1x1 ID Picture must not be more than 300KB in size.',
                    'id_pic.mimes' => 'The 1x1 ID Picture File must be in JPEG file format.',                                          

                    'good_moral.required' => 'The Good Moral Certificate File is required.',
                    'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
                    'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',    
            
                ]);

            break;
            case '1011':

                $validator = Validator::make($request->all(), [
                    
                    'id_pic' => 'required|file|mimes:jpeg|max:300',                                            
                    'good_moral' => 'required|file|mimes:jpeg|max:300',                           
                    'report_card' => 'required|file|mimes:jpeg|max:300', 

                ],
                [
        
                    'id_pic.required' => 'The 1x1 ID Picture is required.',
                    'id_pic.max' => 'The 1x1 ID Picture must not be more than 300KB in size.',
                    'id_pic.mimes' => 'The 1x1 ID Picture File must be in JPEG file format.',                                          

                    'good_moral.required' => 'The Good Moral Certificate File is required.',
                    'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
                    'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',    
                    
                    'report_card.required' => 'The Form 138 File is required.',
                    'report_card.max' => 'The Form 138 File must not be more than 300KB in size.',
                    'report_card.mimes' => 'The Form 138 File must be in JPEG file format.',
            
                ]);

            break;
            case '1001':

                $validator = Validator::make($request->all(), [
                    
                    'id_pic' => 'required|file|mimes:jpeg|max:300',                                              
                    'report_card' => 'required|file|mimes:jpeg|max:300', 

                ],
                [
        
                    'id_pic.required' => 'The 1x1 ID Picture is required.',
                    'id_pic.max' => 'The 1x1 ID Picture must not be more than 300KB in size.',
                    'id_pic.mimes' => 'The 1x1 ID Picture File must be in JPEG file format.',                                       

                    'report_card.required' => 'The Form 138 File is required.',
                    'report_card.max' => 'The Form 138 File must not be more than 300KB in size.',
                    'report_card.mimes' => 'The Form 138 File must be in JPEG file format.',
                ]);

            break;
            case '1110':

                $validator = Validator::make($request->all(), [
                    
                    'id_pic' => 'required|file|mimes:jpeg|max:300',
                    'birth_cert' => 'required|file|mimes:jpeg|max:300',                           
                    'good_moral' => 'required|file|mimes:jpeg|max:300',                                               

                ],
                [
        
                    'id_pic.required' => 'The 1x1 ID Picture is required.',
                    'id_pic.max' => 'The 1x1 ID Picture must not be more than 300KB in size.',
                    'id_pic.mimes' => 'The 1x1 ID Picture File must be in JPEG file format.',   
                    
                    'birth_cert.required' => 'The Birth Certificate File is required.',
                    'birth_cert.max' => 'The Birth Certificate must not be more than 300KB in size.',
                    'birth_cert.mimes' => 'The Birth Certificate File must be in JPEG file format.',  

                    'good_moral.required' => 'The Good Moral Certificate File is required.',
                    'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
                    'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',    
                  
                ]);

            break;
            case '1111':

                $validator = Validator::make($request->all(), [
                    
                    'id_pic' => 'required|file|mimes:jpeg|max:300',
                    'birth_cert' => 'required|file|mimes:jpeg|max:300',                           
                    'good_moral' => 'required|file|mimes:jpeg|max:300',                           
                    'report_card' => 'required|file|mimes:jpeg|max:300', 

                ],
                [
        
                    'id_pic.required' => 'The 1x1 ID Picture is required.',
                    'id_pic.max' => 'The 1x1 ID Picture must not be more than 300KB in size.',
                    'id_pic.mimes' => 'The 1x1 ID Picture File must be in JPEG file format.',   
                    
                    'birth_cert.required' => 'The Birth Certificate File is required.',
                    'birth_cert.max' => 'The Birth Certificate must not be more than 300KB in size.',
                    'birth_cert.mimes' => 'The Birth Certificate File must be in JPEG file format.',  

                    'good_moral.required' => 'The Good Moral Certificate File is required.',
                    'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
                    'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',    

                    'report_card.required' => 'The Form 138 File is required.',
                    'report_card.max' => 'The Form 138 File must not be more than 300KB in size.',
                    'report_card.mimes' => 'The Form 138 File must be in JPEG file format.',
                ]);

            break;
            case '0100':

                $validator = Validator::make($request->all(), [
                                        
                    'birth_cert' => 'required|file|mimes:jpeg|max:300',                                               

                ],
                [
                            
                    'birth_cert.required' => 'The Birth Certificate File is required.',
                    'birth_cert.max' => 'The Birth Certificate must not be more than 300KB in size.',
                    'birth_cert.mimes' => 'The Birth Certificate File must be in JPEG file format.',  
                 
                ]);

            break;
            case '0110':

                $validator = Validator::make($request->all(), [
                                        
                    'birth_cert' => 'required|file|mimes:jpeg|max:300',                           
                    'good_moral' => 'required|file|mimes:jpeg|max:300',                                               

                ],
                [                             
                    
                    'birth_cert.required' => 'The Birth Certificate File is required.',
                    'birth_cert.max' => 'The Birth Certificate must not be more than 300KB in size.',
                    'birth_cert.mimes' => 'The Birth Certificate File must be in JPEG file format.',  

                    'good_moral.required' => 'The Good Moral Certificate File is required.',
                    'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
                    'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',    
                 
                ]);

            break;
            case '0101':

                $validator = Validator::make($request->all(), [
                                        
                    'birth_cert' => 'required|file|mimes:jpeg|max:300',                                                                         
                    'report_card' => 'required|file|mimes:jpeg|max:300', 

                ],
                [                           
                    
                    'birth_cert.required' => 'The Birth Certificate File is required.',
                    'birth_cert.max' => 'The Birth Certificate must not be more than 300KB in size.',
                    'birth_cert.mimes' => 'The Birth Certificate File must be in JPEG file format.',  
                  
                    'report_card.required' => 'The Form 138 File is required.',
                    'report_card.max' => 'The Form 138 File must not be more than 300KB in size.',
                    'report_card.mimes' => 'The Form 138 File must be in JPEG file format.',
                ]);

            break;
            case '0111':

                $validator = Validator::make($request->all(), [
                                        
                    'birth_cert' => 'required|file|mimes:jpeg|max:300',                           
                    'good_moral' => 'required|file|mimes:jpeg|max:300',                           
                    'report_card' => 'required|file|mimes:jpeg|max:300', 

                ],
                [                    
                    
                    'birth_cert.required' => 'The Birth Certificate File is required.',
                    'birth_cert.max' => 'The Birth Certificate must not be more than 300KB in size.',
                    'birth_cert.mimes' => 'The Birth Certificate File must be in JPEG file format.',  

                    'good_moral.required' => 'The Good Moral Certificate File is required.',
                    'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
                    'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',    

                    'report_card.required' => 'The Form 138 File is required.',
                    'report_card.max' => 'The Form 138 File must not be more than 300KB in size.',
                    'report_card.mimes' => 'The Form 138 File must be in JPEG file format.',
                ]);

            break;
            case '0010':

                $validator = Validator::make($request->all(), [
                                                          
                    'good_moral' => 'required|file|mimes:jpeg|max:300',                                           

                ],
                [                         
                    'good_moral.required' => 'The Good Moral Certificate File is required.',
                    'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
                    'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',    
                
                ]);

            break;
            case '0011':

                $validator = Validator::make($request->all(), [
                                                                 
                    'good_moral' => 'required|file|mimes:jpeg|max:300',                           
                    'report_card' => 'required|file|mimes:jpeg|max:300', 

                ],
                [                           

                    'good_moral.required' => 'The Good Moral Certificate File is required.',
                    'good_moral.max' => 'The Good Moral Certificate File must not be more than 300KB in size.',
                    'good_moral.mimes' => 'The Good Moral Certificate File must be in JPEG file format.',    

                    'report_card.required' => 'The Form 138 File is required.',
                    'report_card.max' => 'The Form 138 File must not be more than 300KB in size.',
                    'report_card.mimes' => 'The Form 138 File must be in JPEG file format.',
                ]);

            break;
            case '0001':

                $validator = Validator::make($request->all(), [
                                                        
                    'report_card' => 'required|file|mimes:jpeg|max:300', 

                ],
                [                              

                    'report_card.required' => 'The Form 138 File is required.',
                    'report_card.max' => 'The Form 138 File must not be more than 300KB in size.',
                    'report_card.mimes' => 'The Form 138 File must be in JPEG file format.',
                ]);

            break;
        }
       

        if ($validator->fails()) {
            return redirect()->route('appStatus')->withErrors($validator);
        }


        
        if( $request->hasFile('id_pic') ){

            Storage::delete('/public/images/applicants/id_pics/' . $applicant->id_pic);
            
            $idPicwithExt = $request->file('id_pic')->getClientOriginalName();            
            $idPicName = pathinfo($idPicwithExt, PATHINFO_FILENAME);            
            $idPicExt = $request->file('id_pic')->getClientOriginalExtension();            
            $idPicToStore = $idPicName.'_'.time().'.'.$idPicExt;            
            $id_pic_path = $request->file('id_pic')->storeAs('public/images/applicants/id_pics', $idPicToStore);
 
            $applicant->id_pic  = $idPicToStore; 
            $newResubmitted[0] = '1';
                           

        }        
        
        if( $request->hasFile('birth_cert') ){

            Storage::delete('/public/images/applicants/birth_certs/' . $applicant->birth_cert);
            
            $birth_certwithExt = $request->file('birth_cert')->getClientOriginalName();
            $birth_certName = pathinfo($birth_certwithExt, PATHINFO_FILENAME);
            $birth_certExt = $request->file('birth_cert')->getClientOriginalExtension();
            $birth_certToStore = $birth_certName.'_'.time().'.'.$birth_certExt;
            $birth_cert_path = $request->file('birth_cert')->storeAs('public/images/applicants/birth_certs', $birth_certToStore);
 
            $applicant->birth_cert  =  $birth_certToStore;
            $newResubmitted[1] = '1';
        
            
        }

        if( $request->hasFile('good_moral') ){

            Storage::delete('/public/images/applicants/good_morals/' . $applicant->good_moral);
            
            $good_moralwithExt = $request->file('good_moral')->getClientOriginalName();           
            $good_moralName = pathinfo($good_moralwithExt, PATHINFO_FILENAME);    
            $good_moralExt = $request->file('good_moral')->getClientOriginalExtension();
            $good_moralToStore = $good_moralName.'_'.time().'.'.$good_moralExt;
            $good_moral_path = $request->file('good_moral')->storeAs('public/images/applicants/good_morals', $good_moralToStore);
 
            $applicant->good_moral  = $good_moralToStore;
            $newResubmitted[2] = '1';
          
            
        }
        
        if( $request->hasFile('report_card') ){

            Storage::delete('/public/images/applicants/report_cards/' . $applicant->report_card);
            
            $report_cardwithExt = $request->file('report_card')->getClientOriginalName();
            $report_cardName = pathinfo($report_cardwithExt, PATHINFO_FILENAME);            
            $report_cardExt = $request->file('report_card')->getClientOriginalExtension();
            $report_cardToStore = $report_cardName.'_'.time().'.'.$report_cardExt;
            $report_card_path = $request->file('report_card')->storeAs('public/images/applicants/report_cards', $report_cardToStore);            
 
            $applicant->report_card  = $report_cardToStore;
            $newResubmitted[3] = '1';
        }

        
        $applicant->resubmit_file = '0000';
        $applicant->resubmitted = $newResubmitted;
        $applicant->save();

        return redirect()->route('appStatus');
        
    }

    public function approve(Request $request){

        //delete member link        
        
        $app_id = $request->input('app_id');    

        $applicant = Applicant::find($app_id);

        /**
         * STUDENT CREATE
         */
        
        $student = new Student;      //studid

        $student->department = $applicant->dept;
        $student->program_id = $applicant->program;
        $student->semester = 1;

        $student->student_type = 0;
        $student->transferee = 0;
        $student->created_by_admin = 0;

        if($applicant->dept == 0)
            $student->level = 1;
        else
            $student->level = 11;

        $student->email = $applicant->email;

        $student->last_name = $applicant->last_name;
        $student->first_name = $applicant->first_name;
        $student->middle_name = $applicant->middle_name;

        $student->dob = $applicant->dob;
        $student->gender = $applicant->gender;
        
        $student->present_address = $applicant->present_address;
        $student->last_school = $applicant->last_school;

        $student->save();
    
            $balance = new Balance;             
            $balanceID = $balance->init();                

            $student->balance_id = $balanceID;
                    
            $year =  date("y");
            $prefix = "C";
            $prefixID = $prefix . $year . '-' . sprintf('%04d', $student->id);
        
            $student->student_id = $prefixID;

        $student->save();
        $student_id = $student->id;

        $applicant->approved = 1;
        $applicant->student_id = $student->id;
        $applicant->save();
        

        $member_old = Member::query()->where('member_type', 'applicant')->where('member_id', $app_id)->first();
        $user_id = $member_old->user_id;

        $user = User::find($member_old->user_id);
        $user->user_type = 'student';
        $user->save();

        $student->dept = $student->department;
        $student->program_desc = $student->program_id;

        Mail::to($user)->send(new ApprovedApplicant($student->first_name . ' ' . $student->last_name,
                                                    $student->student_id,
                                                    $student->dept,
                                                    $student->program_desc,
                                                    ));

        Member::where('member_type', $member_old->member_type)
              ->where('member_id', $member_old->member_id)
              ->where('user_id', $member_old->user_id)->delete();              

        $member_new = new Member;
        $member_new->user_id = $user_id;
        $member_new->member_type = 'student';
        $member_new->member_id = $student_id;
                
        $member_new->save();
                   
        $values = ['department' => $student->department, 
                    'program' => $student->program_id, 
                    'level' => $student->level, 
                    'semester' => $student->semester, 
                  ];
        
        $subjects = collect();
        
        if($student->program->is_tesda)
            $subjects = Subject::allWhere($values, false);
        else
            $subjects = Subject::allWhere($values, true);
        
        $total_balance = 0;
        

        $mergedFees = Fee::getMergedFees($student->department, $student->program_id, $student->level, $student->semester);

        $counter = 0; 
        foreach ($subjects as $subject) {

            $subject_to_take = new SubjectTaken;

            $subject = Subject::find($subject->id);  

            $subject_to_take->student_id = $student_id;
            $subject_to_take->subject_id = $subject->id;

            if(!$student->department)
                $total_balance += Setting::first()->shs_price_per_unit * $subject->units;
            else {
                if(!$student->program->is_tesda)
                    $total_balance += Setting::first()->college_price_per_unit * $subject->units; 
            }

            if($counter == ($subjects->count() - 1)) {  
            
                foreach($mergedFees as $fee){
                    $student->balance->amount+= $fee->amount;
                }
            
                $student->balance->amount+= $total_balance;
                $student->balance->save();
            }

            $subject_to_take->rating = 4.5;    
            $subject_to_take->from_year = Setting::first()->from_year;  
            $subject_to_take->to_year = Setting::first()->to_year; 
            $subject_to_take->semester = Setting::first()->semester;  
            
            $subject_to_take->save();

            $counter++;
        }
        
        return redirect()->route('adminView')->with('active', 'applicants');

    }      

    public function reject(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();

        $applicant = Applicant::find($request->input('applicant_id'));

        $applicant->prog_desc = $applicant->id;
        $applicant->dept_desc = $applicant->id;

        Mail::to($applicant->member->user)->send(new RejectApplicant(ucfirst($applicant->first_name) . ' ' . ucfirst($applicant->last_name), $applicant->prog_desc, $applicant->dept_desc, $request->input('reason')));

        $applicant->member->user->access_grant = 1;
        $applicant->member->user->save();

        $applicant->delete();

        return redirect()->route('adminView')->with('info', 'Applicant Rejected.');

    }

    public function restore(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();
    

        $applicant = Applicant::withTrashed()->find($request->input('id'));          

        Mail::to($applicant->member->user)->send(new RestoreApplicant(ucfirst($applicant->first_name) . ' ' . ucfirst($applicant->last_name)));

        $applicant->member->user->access_grant = 0;
        $applicant->member->user->save();

        $applicant->restore();

        return redirect()->route('adminView')->with('info', 'Applicant Restored.');

    }

}
