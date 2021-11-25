<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RemindToSubmitAdmissionForm;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;

class UsersController extends Controller
{
    
    public function remindToSubmit(){

        $applicantUsersThisSem = User::whereDate('created_at', '>=', Carbon::parse(Setting::first()->semester_updated_at)->subWeek())->where('user_type', 'applicant')->get();                            
                                                    
        $still_no_app_form = $applicantUsersThisSem->filter(function ($applicant_user, $key) {
            return is_null($applicant_user->member);
        });

        if($still_no_app_form->count() > 0){
            foreach ($still_no_app_form as $user) {
                Mail::to($user)->send(new RemindToSubmitAdmissionForm($user));
            }
        }

        return redirect()->back()->with('success', 'Applicants Reminded to Submit Admission Form!');

    }

    public function deleteNoAdmissionForms(Request $request){
        if($request->method() != "POST")
            return redirect()->back();

        $applicantUsersThisSem = User::whereDate('created_at', '>=', Carbon::parse(Setting::first()->semester_updated_at)->subWeek())->where('user_type', 'applicant')->get();                            
                                                
        $still_no_app_form = $applicantUsersThisSem->filter(function ($applicant_user, $key) {
            return is_null($applicant_user->member);
        });

        if($still_no_app_form->count() > 0){
            foreach ($still_no_app_form as $user) {
                $user->delete();
            }
        }

        return redirect()->back()->with('info', 'User Accounts with no submission of Admission Forms are deleted.');
        
    }

    public function remindSpecific($id){
        $user = User::find($id);

        Mail::to($user)->send(new RemindToSubmitAdmissionForm($user));

        if(count(Mail::failures()) > 0)
            return 1;
        else 
            return 0;
    }

    public function deleteSpecific($id){
        
        $user = User::find($id);
        $user->delete();
        
        $count = $applicantUsersThisSem = User::whereDate('created_at', '>=', Carbon::parse(Setting::first()->semester_updated_at)->subWeek())->where('user_type', 'applicant')->get()->count();

        return $count;        
    }
}
