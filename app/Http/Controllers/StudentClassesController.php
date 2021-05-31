<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentClass;

class StudentClassesController extends Controller
{


    public function store(Request $request){        

        $noOfSched = $request->input('multi_sched');
        $days;
        $froms;
        $untils;
        $room_ids;
        $instructor_ids;
        $counter = 1;

        if($noOfSched > 0){
                
           for($i=0; $i<$noOfSched; $i++){ 

                if($i < 1){
                    $days[$i] = $request->input('day');
                    $froms[$i] = $request->input('from');
                    $untils[$i] = $request->input('until');
                    $room_ids[$i] = $request->input('room_id');
                    $instructor_ids[$i] = $request->input('instructor_id');
                } else {                   

                    $days[$i] = $request->input('day_' . $counter );
                    $froms[$i] = $request->input('from_' .$counter );
                    $untils[$i] = $request->input('until_' . $counter );
                    $room_ids[$i] = $request->input('room_id_' . $counter );
                    $instructor_ids[$i] = $request->input('instructor_id_' . $counter );
                    ++$counter;
                }
                                
           }

           return print_r($days) . print_r($froms) . print_r($untils) . print_r($room_ids) . print_r($instructor_ids);

            // $days;

            // for($i=0; $i)
            
            // return $request->input('day') . ' ' . $request->input('day_1') . ' ' . $request->input('day_2') . ' ';
        } else {
            return "false";
        }

    }
    
}
