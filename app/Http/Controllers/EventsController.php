<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Event;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function showEvents(){

        return view('events');

    }

    public function create(){

        return view('admin.events.create');

    }

    public function store(Request $request){        

        if($request->method() != 'POST')
            return redirect()->back();   
            
        $id = auth()->user()->member->member_id;

        $author_id = Admin::find($id)->admin_id;

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100', 
            'from' => 'date|before:' . Carbon::now()->addYear()->toDateString() . '|after:' . Carbon::now()->subDays(1)->toDateString(), 
            'until' => 'date|before:' . Carbon::now()->addYear()->toDateString() . '|after:' . Carbon::now()->subDays(1)->toDateString(), 
        ]);

        if ($validator->fails()) {
            return redirect()
                            ->route('createEvent')
                            ->withErrors($validator)
                            ->withInput();                            
        }      

        $event = new Event;

        $event->title = $request->input('title');
        $event->from = $request->input('from');
        $event->until = $request->input('until');
        $event->author = $author_id;
        
        $event->save();

        return redirect()->route('createEvent')->with('success', 'Event Created!');

    }

}
