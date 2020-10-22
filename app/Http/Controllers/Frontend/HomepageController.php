<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Customers;
use Carbon\Carbon;

use Mail, Session, Redirect, Validator, DB, Hash;

class HomepageController extends BaseController {

    public function getIndex() {
        $hormpage= Blog::get();

        return View('frontend.home.index', array('title' => 'Blog','v_meta_description'=> 'Blog Practical','v_meta_keywords'=> 'Blog Practical','homeFacility' => $hormpage));
    }
    public function CustomerComent(Request $request, $id){
        $input = $request->all();
        $blog =  Blog::find($id);
        $comment = Comment::with('User')->where('i_blog_id',$id)->get()->toArray();
        $customer_info = auth()->guard('customers')->user();

        if($input){
            if($customer_info){

                $record = new Comment;
                $record->v_comment = trim($input['v_comment']);
                $record->i_blog_id = trim($blog['id']);
                $record->i_user_id = trim($customer_info['id']);
                $record->created_at = Carbon::now();
                if ($record->save()) {
                    Session::flash('msg','Your Comment add successfully.');          
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => FRONTEND_URL.'blog/'.$blog['id'],
                    ]);
                }

            } else {

                return response()->json([
                    'status' => 'LOGIN_ACCOUNT',
                    'redirect_url' => FRONTEND_URL.'login',
                ]);
                
            }
                

            
        } else{

        $blog =  Blog::find($id);
        return View('frontend.home.blog', array('title' => $blog['v_title'],'blog'=>$blog,'comment'=>$comment));
        }

    }

    
   
} 
