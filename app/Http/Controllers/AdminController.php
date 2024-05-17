<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function post_page()
    {
        return view('admin.post_page');
    }

    public function addpost(Request $request)
    {

        $user=Auth()->user();
        $userid=$user->id;
        $name=$user->name;
        $usertype=$user->usertype;

        $post= new Post;          
            $post->title=$request->title;
            $post->description=$request->description;
            $post->post_status='active';
            $post->user_id=$userid;
            $post->name=$name;
            $post->usertype=$usertype;

            $image= $request->image;
            if($image){
            $imagename=time().'.'.$image->getClientOriginalExtension();
            $request->image->move('postimage',$imagename);
            $post->image=$imagename;
            }
            $post->save();
        
        return redirect()->back()->with('message','Post Added Successfully');
    }

    public function show_page()
    {
        //$userid= Auth()->user()->id;
        $post = post::all();
        return view('admin.show_page',compact('post'));

    }

    public function deletemenu($id)
    {
        $data = post::find($id);
        $data->delete();
        return redirect()->back();
    }


    public function edit_page($id)
    {
       $post=Post::find($id);
        return view('admin.edit_page',compact('post'));
    }

    public function update_post(Request $request,$id)
    {
       $data=Post::find($id);
       $data->title=$request->title;
       $data->description=$request->description;
       $image=$request->image;

       $oldImage = $data->image; // Get the old image file name
      



       if($image)
       {
        $imagename=time().'.'.$image->getClientOriginalExtension();
        $request->image->move('postimage',$imagename);
        $data->image=$imagename;
       

        if ($oldImage) {
            $oldImagePath = public_path('postimage/'.$oldImage);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

       }
       
$data->save();


return redirect()->back()->with('message','Post updated');


        
    }

    public function accept_post($id)
    {
       $data=Post::find($id);
       $data->post_status='active';
       $data->save();
       return redirect()->back()->with('message','Post active');    

    }

    public function reject_post($id)
    {
       $data=Post::find($id);
       $data->post_status='rejected';
       $data->save();
       return redirect()->back()->with('message','Post rejected');    

    }
    
}
