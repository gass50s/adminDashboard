<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    public function getListContacts(){
        $contacts = User::all();
        return response()->json(["contacts"=>$contacts]);
    }
    public function getOneContact($id){
        $contact = User::find($id);
        return response()->json(["contact"=>$contact]);
    }
    public function addContact(Request $request){
        $form = json_decode($request->body, true);
        $validator = Validator::make(
            $form,
            [
                'surname' => 'sometimes|alpha|max:255',
                'last_name' => 'sometimes|alpha|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'sometimes|string|',
                'sexe' => 'sometimes',
                'member' => 'sometimes'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        }
        $user= new User;
        $user->surname =  $form['surname'];
        $user->last_name = $form['last_name'];
        $user->sexe = $form['sexe'];
        $user->email = $form['email'];
        $user->password = bcrypt($form['password']);
        $user->member = $form['member'];
        $user->country = $form['country'];
        $user->telephone = $form['telephone'];
        $user->email_verified_at = Carbon::today();
        $user->is_banned_account = false;
        $user->save();
        return response()->json(["status"=>201,"messge"=>"succés"]);
    }

    public function deleteContact($id){
        $contact = User::find($id);
        $contact->delete();
        return response()->json(["status"=>201,"message"=>"succés"]);
    }
    
    public function UpdateContact(Request $request){
        $form = json_decode($request->body, true);
        $validator = Validator::make(
            $form,
            [
                'surname' => 'sometimes|alpha|max:255',
                'last_name' => 'sometimes|alpha|max:255',
                'email' => 'string|email|unique:users,email,' . $request->id,
                'sexe' => 'sometimes',
                'member' => 'sometimes'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        }
        $contact = User::find($request->id);
        $contact->surname = $form['surname'];
        $contact->last_name = $form['last_name'];
        $contact->email = $form['email'];
        if($form['password']!=null)
            $contact->password =  bcrypt($form['password']);
            $contact->sexe = $form['sexe'];
            $contact->member = $form['member'];
            $contact->country = $form['country'];
            $contact->telephone = $form['telephone'];
            $contact->save();
            return response()->json(["status"=>201,"message"=>$contact]);
    }
}
