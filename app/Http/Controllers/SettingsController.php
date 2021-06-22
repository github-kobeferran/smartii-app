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
        
        $dateTimeNow = Carbon::now();
        $yearNow = $dateTimeNow->year;
        $min = $dateTimeNow->subYears(1)->year;
        $maxFrom = $dateTimeNow->addYear(1)->year;
        $maxTo = $dateTimeNow->addYear(2)->year;                    

        $validator = Validator::make($request->all(), [
            'from' => 'required|numeric|min:' . $min . '|max:' . $maxFrom, 
            'to' => 'required|numeric|min:' . $min . '|max:' . $maxTo, 
            'sem' => 'required|in:1,2', 
            'shs_price' => 'required|min:0|max:1000',
            'col_price' => 'required|min:0|max:1000',             
            'class_quantity' => 'required|min:1|max:50',             
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminSettings')
                         ->withErrors($validator)
                         ->withInput();                    
        }

        $setting = Setting::first();

        $setting->from_year = $request->input('from');
        $setting->to_year = $request->input('to');
        $setting->semester = $request->input('sem');
        $setting->shs_price_per_unit = $request->input('shs_price');
        $setting->college_price_per_unit = $request->input('col_price');
        $setting->class_quantity = $request->input('class_quantity');

        $mode = $request->input('mode');

        if($mode == 1){

            $setting->enrollment_mode = $request->input('mode');            
            DB::table('users')->update(['access_grant' => '0']);

        }

        $setting->enrollment_mode = $request->input('mode');      
                
        if($setting->save()){
            return redirect()->route('adminSettings')
                         ->with('success', 'Settings Updated');                         
        } else {
            return redirect()->route('adminSettings')
                         ->with('warning', 'There\'s a problem saving settings, please try again');
        }

    }
}
