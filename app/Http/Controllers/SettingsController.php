<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Setting;

class SettingsController extends Controller
{    

    public function update(Request $request){  
        
        $now = Carbon::now()->year;
        $min = Carbon::now()->subYears(1)->year;
        $maxFrom = Carbon::now()->addYear()->year;
        $maxTo = Carbon::now()->addYear(2)->year;                  

        $validator = Validator::make($request->all(), [
            'from' => 'required|numeric|min:' . $min . '|max:' . $maxFrom, 
            'to' => 'required|numeric|min:' . $min . '|max:' . $maxTo, 
            'sem' => 'required|in:1,2', 
            'shs_price' => 'required|min:0|max:1000',
            'col_price' => 'required|min:0|max:1000',             
            'class_quantity' => 'required|min:1|max:50',             
            'gcash' => 'required|digits:11',             
            'bank_name' => 'required|regex:/^[\.\s\w-]*$/|max:191',             
            'bank_number' => 'required|regex:/^[0-9\w\s]+$/|max:191',                         
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminSettings')
                         ->withErrors($validator);                    
        }

        $setting = Setting::first();

        $setting->from_year = $request->input('from');
        $setting->to_year = $request->input('to');
        $setting->semester = $request->input('sem');
        $setting->shs_price_per_unit = $request->input('shs_price');
        $setting->college_price_per_unit = $request->input('col_price');
        $setting->class_quantity = $request->input('class_quantity');
        $setting->gcash_number = $request->input('gcash');
        $setting->bank_number = $request->input('bank_number');
        $setting->bank_name = $request->input('bank_name');        

        $mode = $request->input('mode');

        if($mode == 1){

            $setting->enrollment_mode = $request->input('mode');            
            DB::table('users')->update(['access_grant' => '0']);

        }

        $setting->enrollment_mode = $request->input('mode');      

        if($setting->isDirty('semester'))
            $setting->semester_updated_at = Carbon::now();

        if($setting->isDirty('enrollment_mode'))
            $setting->enrollment_mode_updated_at = Carbon::now();
                
        if($setting->save()){
            return redirect()->route('adminSettings')
                         ->with('success', 'Settings Updated');                         
        } else {
            return redirect()->route('adminSettings')
                         ->with('warning', 'There\'s a problem saving settings, please try again');
        }

    }
}
