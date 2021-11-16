<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RoomsController extends Controller
{
    
    public function store(Request $request){

        
        $validator = Validator::make($request->all(), [
            'room_name' => 'required|regex:/^[\s\w-]*$/', 
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminClasses')
                         ->withErrors($validator)
                         ->withInput()
                         ->with('active', 'rooms');
        }

        $room = new Room;

        foreach (Room::all() as $room) {
            if(str_replace(' ', '', strtolower($room->name)) == str_replace(' ', '', strtolower($request->input('room_name'))))
                return redirect()->route('adminClasses')->with('error', 'There is already a room with the same name')->with('active', 'rooms');            
        }


        $room->name = $request->input('room_name');        
        $room->save();

        return redirect()->route('adminClasses')->with('success', 'Room Saved!')->with('active', 'rooms');

    }


    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'room_update_name' => 'required|', 
        ],
        [
            'room_update_name.required|' => 'Room name is required in updating'
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminClasses')
                         ->withErrors($validator)
                         ->withInput()
                         ->with('active', 'rooms');
        }        

        $roomName = $request->input('room_update_name');
        $roomID = $request->input('room_id');

        if(!Room::where('name',  $roomName)->where('id', '!=', $roomID)->exists()){
            $room = Room::find($roomID);

            $oldname = $room->name;

            $room->name = $roomName;
            $room->save();

            return redirect()->route('adminClasses')
            ->with('success', 'Room ' . $oldname . ' changed to ' . $room->name)
            ->with('active', 'rooms');

        } else {
            return redirect()->route('adminClasses')
            ->with('error', 'Room update failed. There is already a room with the same name')
            ->with('active', 'rooms');
        }    

    }

    public function destroy($id){

        if(!Schedule::where('room_id',$id)->exists()){

            $room =Room::find($id);

            $name = $room->name;

            $room->delete();

            return redirect()->route('adminClasses')
            ->with('info', 'Room '. $name . ' is now deleted.')
            ->with('active', 'rooms');

        } else {

            return redirect()->route('adminClasses')
            ->with('warning', 'Room is currently used in a schedule. Change the schedule room first and try again.')
            ->with('active', 'rooms');

        }        

    }

    public function availableRooms($from, $until, $day = null){        
        
        if($day != null){            

            $scheds = Schedule::where('day', $day)
                               ->where('start_time','<', $until)
                               ->where('until','>', $from)                                                  
                               ->get();                                    

            $scheds = $scheds->filter(function ($sched, $key) {
                return $sched->studentClass->archive == 0;
            });

             if($scheds != null){

                $valids = collect();

                foreach(Room::all() as $room){

                    $bawal = false;

                    foreach($scheds as $sched){

                        if($room->id == $sched->room_id){
                            $bawal = true;
                        }
                    
                    }

                    if(!$bawal)
                        $valids->push($room);

                }
               
                return $valids;
                
             } else {
                return Room::all()->toJson();
                
             }
 
             
 
        }else{
            return Room::all()->toJson();
        }
 
 
     }

    public function availableRoomsExcept($from, $until, $day = null, $exceptid){
        if($day != null){
            $scheds = Schedule::where('day', $day)->where('start_time','<', $until)->where('until','>', $from)->get();                                    

            $rooms = Room::all()->filter(function($room) use($scheds, $exceptid){
                $valid = true;
                foreach($scheds as $sched){
                    if($room->id == $sched->room_id)
                        $valid = false;                        
                    
                    if($room->id == $exceptid)
                        $valid = true;                    
                }        
                if($valid && !is_null($room))
                    return $room;
            });
  
            // if($scheds != null){

            //    $valids = collect();

            //    foreach(Room::all() as $room){

            //        $valid = true;

            //        foreach($scheds as $sched){
            //             if($room->id == $sched->room_id)
            //                 $valid = false;
                    
            //             if($room->id == $exceptid)
            //                 $valid = true;
            //        }

            //        if($valid)
            //            $valids->push($room);

            //    }
              
            //    return $valids;
               
            // } else {
            //    return Room::all()->toJson();
            // }

            return $rooms;
       }else{
           return Room::all()->toJson();
       }
 
 
     }

}
