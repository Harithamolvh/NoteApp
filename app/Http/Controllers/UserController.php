<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserProfile;
use App\Note;
use Auth;
use DB;
use Hash;
use File;

class UserController extends Controller
{
    /*------------------------------ Registration start  ----------------------------------------------- */
    public function registration(Request $request){

        $request->validate([
            'first_name' => 'required',
            'second_name' => 'required',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'password' => 'required|min:8|same:password_confirmation'
        ]);

        $user = new User();
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->save();

        $profile = new UserProfile();
        $profile->user_id=$user->id;
        $profile->first_name=$request->first_name;
        $profile->last_name=$request->second_name;
        $profile->address=$request->address;
        $profile->save();
        
        return redirect()->route('login')->with('message','Resgistration successful , Please login to continue');
    }
    /*------------------------------ Registration End  ----------------------------------------------- */
    /*------------------------------ New Note start  ----------------------------------------------- */

    public function newnote(Request $request){

        $request->validate([
            'note' => 'required',
        ]);

        $user_id = Auth::user()->id;

        $profile_id = DB::connection('mysql_S')->table('user_profiles')->where('user_id',$user_id)->value('id');
        

        $note = new Note();
        $note->user_id=$user_id;
        $note->user_prpfile_id=$profile_id;
        $note->note_text=$request->note;
        if(!empty($request->upload)){

            if ($request->file('upload')->isValid()){ 
                
                 $fileName = 'Doc-'.date("d-m-Y-h-i-s").'-'.rand(10,100).'.'.$request->file('upload')->extension(); 
                 $request->upload->move(public_path('uploads'), $fileName);
                 $note->file_upload= $fileName;
             }else{
                 $note->file_upload= '0';
             }
        }
        $note->save();

        return redirect()->back()->with('message','Note Saved');
    }
    /*------------------------------ New Note End  ----------------------------------------------- */
    /*------------------------------ Delete Note start  ----------------------------------------------- */

    public function deletenote(Request $request){

        $request->validate([
            'note_id' => 'required',
        ]);

        $note_id =$request->note_id;
        $data = DB::connection('mysql_T')->table('notes')->where('id',$note_id)->first();

        if(File::exists(public_path('uploads/'.$data->file_upload))){
            File::delete(public_path('uploads/'.$data->file_upload));   
        }

        DB::connection('mysql_T')->table('notes')->where('id',$note_id)->delete();
        
        return redirect()->route('home')->with('message','Note Deleted');
    }
    /*------------------------------ Edit NOte start  ----------------------------------------------- */

    public function editnote(Request $request,$note_id){

        $request->validate([
            'note' => 'required',
        ]);

        $data = DB::connection('mysql_T')->table('notes')->where('id',$note_id)->first();

       
        $note_text=$request->note;

        if(!empty($request->upload)){

            if(File::exists(public_path('uploads/'.$data->file_upload))){
                File::delete(public_path('uploads/'.$data->file_upload));   
            }

            if ($request->file('upload')->isValid()){ 
                
                 $fileName = 'Doc-'.date("d-m-Y-h-i-s").'-'.rand(10,100).'.'.$request->file('upload')->extension(); 
                 $request->upload->move(public_path('uploads'), $fileName);
                 $file_upload= $fileName;

                 DB::connection('mysql_T')->table('notes')
                 ->where('id',$note_id)
                 ->update(['note_text'=>$note_text,'file_upload'=>$file_upload]);

             }
        }else{
                DB::connection('mysql_T')->table('notes')
                 ->where('id',$note_id)
                 ->update(['note_text'=>$note_text]);

        }
       

        return redirect()->route('note',['id'=>$note_id])->with('message','Note Edited');
    }
    /*------------------------------ Edit NOte End  ----------------------------------------------- */

}
