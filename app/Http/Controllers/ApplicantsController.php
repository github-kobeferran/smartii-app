<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Mail\WelcomeApplicant;
use App\Models\Applicant;
use App\Models\Program;
use App\Models\Member;
use App\Models\User;



class ApplicantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('applicant.index');
    }

    public function form()
    {
        return view('applicant.admission');
    }

    public function status()
    {
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
    public function store(Request $request)    
    {                 

        $validator = Validator::make($request->all(), [
            
            'dept' => 'required',                                     

        ],
        [

            'dept.required' => 'Select a Department and Program First.',            
                        
        ]);

        
        if ($validator->fails()) {
            return redirect()->route('admissionForm')
                         ->withErrors($validator)
                         ->with('active', 'dept');
        }

        $validated = ['dept' => $request->input('dept'),
                      'prog' => $request->input('program_id'),
                      'prog_desc' => Program::find($request->input('program_id'))->desc,
                    ];                    

        $validator = Validator::make($request->all(), [
            
            'l_name' => 'required', 
            'f_name' => 'required', 
            'm_name' => 'required', 
            'present_address' => 'required', 
            'last_school' => 'required',
            'dob' => 'required|date',

        ],
        [

            'l_name.required' => 'Last Name is required.',

            'f_name.required' => 'First Name is required.',

            'm_name.required' => 'Middle Name is required.',

            'present_address.required' => 'Present Address is required.',

            'last_school.required' => 'Last School Attended is required.',

            'dob.required' => 'Date of Birth is required.',

            'dob.date' => 'Date of Birth is invalid bruh.',

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

}
