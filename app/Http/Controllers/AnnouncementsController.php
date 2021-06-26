<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementsController extends Controller
{
    public function store(Request  $request){

        if($request->method() != 'POST'){
            return redirect()->back();
        }   

        $announcement = new Announcement;
        
        $announcement->title = $request->input('title');
        $announcement->content = $request->input('content');

        $announcement->save();

        return redirect()->route('adminDashboard');

    }
    
    public function delete($id){

        if(!Announcement::find($id)->exists()){
            return redirect()->back();
        }   

        Announcement::find($id)->delete();
                
        return redirect()->route('adminDashboard');

    }
}
