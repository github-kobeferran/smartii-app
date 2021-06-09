<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Schedule;
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
                         ->with('active', 'view');
        }

        $room = new Room;

        if(!Room::where('name', $request->input('room_name'))->exists()){

            $room->name = $request->input('room_name');        
            $room->save();

            return redirect()->route('adminClasses')
            ->with('success', 'Room Saved!')
            ->with('active', 'view');

        } else {
            return redirect()->route('adminClasses')
            ->with('error', 'There is already a room with the same name')
            ->with('active', 'view');
        }        

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
                         ->with('active', 'view');
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
            ->with('active', 'view');

        } else {
            return redirect()->route('adminClasses')
            ->with('error', 'Room update failed. There is already a room with the same name')
            ->with('active', 'view');
        }    

    }

    public function destroy($id){

        if(!Schedule::where('room_id',$id)->exists()){

            $room =Room::find($id);

            $name = $room->name;

            $room->delete();

            return redirect()->route('adminClasses')
            ->with('info', 'Room '. $name . ' is now deleted.')
            ->with('active', 'view');

        } else {

            return redirect()->route('adminClasses')
            ->with('warning', 'Room is currently used in a schedule. Change the schedule room first and try again.')
            ->with('active', 'view');

        }        

    }

    public function availableRooms($from, $until, $day = null){

        
        if($day != null){
             $sched = Schedule::select('room_id')
             ->where('day', $day)
             ->where('start_time','<', $until)
             ->where('until','>', $from)                                                  
             ->first();
             
             if($sched != null){
               
                return Room::where('id', '!=', $sched->room_id)->get()->toJson();
                
             } else {
                return Room::all()->toJson();
                
             }
 
             
 
        }else{
            return Room::all()->toJson();
        }
 
 
     }

}
