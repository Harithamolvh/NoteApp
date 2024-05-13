<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserProfile;
use App\Note;
use Auth;
use DB;
use Hash;
use File;

class ReceiverController extends Controller
{
/*------------------------------ Registration start  ----------------------------------------------- */

    public function newregistration(Request $request){

        $rules=array(
            'first_name' => 'required',
            'second_name' => 'required',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'password' => 'required|min:8' 
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
                return $validator->errors();
        }else{
            
            
           
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
            
        
            return response()->json([         
                'user_id'=>$user->id,
            ]);
                
        }

    }
/*------------------------------ Registration End  ------------------------------------------------*/


/*------------------------------ all Data start  ----------------------------------------------- */

    public function getallnote(Request $request){

        $rules=array(
            'user_id' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
                return $validator->errors();
        }else{
            
            $user_id =$request->user_id;
            $data = DB::connection('mysql_T')->table('notes')->where('user_id',$user_id)->get();

            return response()->json([         
                'data'=>$data,
            ]);
                
        }

    }
/*------------------------------ All data End  ------------------------------------------------*/

/*------------------------------ all Data start  ----------------------------------------------- */

    public function noteview(Request $request){

        $rules=array(
            'note_id' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
                return $validator->errors();
        }else{
            
            $note_id =$request->note_id;
            $data = DB::connection('mysql_T')->table('notes')->where('id',$note_id)->first();

            return response()->json([         
                'data'=>$data,
            ]);
                
        }

    }
/*------------------------------ All data End  ------------------------------------------------*/

/*------------------------------ newnote start  ----------------------------------------------- */

    public function newnote(Request $request){

        $rules=array(
            'note' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
                return $validator->errors();
        }else{
            
            
        
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
            
        
            return response()->json([         
                'comment'=>'Saved',
            ]);
                
        }

    }
/*------------------------------ newnote End  ------------------------------------------------*/
/*------------------------------ deletenote start  ----------------------------------------------- */

    public function deletenote(Request $request){

        $rules=array(
            'note_id' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
                return $validator->errors();
        }else{
            
            $note_id =$request->note_id;
            $data = DB::connection('mysql_T')->table('notes')->where('id',$note_id)->first();

            if(File::exists(public_path('uploads/'.$data->file_upload))){
                File::delete(public_path('uploads/'.$data->file_upload));   
            }

            DB::connection('mysql_T')->table('notes')->where('id',$note_id)->delete();
        
            return response()->json([         
                'comment'=>'Deleted',
            ]);
                
        }

    }
/*------------------------------ deletenote End  ------------------------------------------------*/
/*------------------------------ editnote start  ----------------------------------------------- */

    public function editnote(Request $request){

        $rules=array(
            'note_id' => 'required',
            'note' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
                return $validator->errors();
        }else{
            
            $note_id =$request->note_id;
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
            
        
            return response()->json([         
                'comment'=>'Edited',
            ]);
                
        }

    }
/*------------------------------ editnote End  ------------------------------------------------*/

}
