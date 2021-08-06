<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;


class PostsController extends Controller
{

    public function create(){

        return view('create_post');

    }
 
    public function show($id){

        $post = Post::find($id);

        $values = ['member_id' => $post->member_id, 'member_type' => $post->member_type];

        $post->author_name = $values;        
                

        return view('post')->with('post', $post);

    }

    public function showAll(){

        $all = Post::where('approved', 1)->get();
        $featured = Post::where('featured', 1)->oldest()->get();                   
        $posts = Post::where('approved', 1)->where('featured', 0)->latest()->get();                   

        return view('posts')->with('posts',  $posts)
                            ->with('featured', $featured)
                            ->with('all', $all);

    }

    public function store(Request $request){

        if($request->method() != 'POST')
            return redirect()->back(); 
            
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50', 
            'image' => 'image|max:1000', 
            'body' => 'required|min:25',                  
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('post.create')
                ->withErrors($validator)
                ->withInput();                         
        }
                
        $member_id = auth()->user()->member->member_id;
        $member_type = auth()->user()->member->member_type;

        $fileNameToStore = null;

        if($request->hasFile('image')){

            // get filename with the extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // get just ext
            $extension = $request->file('image')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // upload image
            $path = $request->file('image')->storeAs('public/images/posts/', $fileNameToStore);
        } 


        $post = new Post;

        $post->title = $request->input('title');
        $post->post_image = $fileNameToStore;
        $post->body = $request->input('body');
        $post->member_id = $member_id;
        $post->member_type = $member_type;
                    

        if($member_type != 'admin'){
            $msg = 'Post Uploaded, please wait for the Site Administrator\'s approval.';
        } else{
            
            $admin = \App\Models\Admin::find($member_id);

            if($admin->position  == 'superadmin'){                    
                $msg = 'Post Uploaded, feature the post to see it on Homepage';
                $post->approved = 1;            
            }

        }
              
        $post->save();

        return redirect()->route('post.create')->with('success', $msg);

    }


}
