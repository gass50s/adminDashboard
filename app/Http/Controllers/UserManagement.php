<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserManagement extends Controller
{

    public function update(Request $request)
    {
        $user =  User::getOwerFromToken($request->bearerToken());
        $form = json_decode($request->body, true);
        $validator = Validator::make(
            $form,
            [
                'surname' => 'alpha|max:255|min:3',
                'last_name' => 'alpha|max:255|min:3',
                'email' => 'string|email|unique:users,email,' . $user->id,

            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        }

        $user->surname = $form['surname'];
        $user->last_name = $form['last_name'];
        $user->email = $form['email'];
        $user->sexe = $form['sexe'];
        if ($form['password'] !== null) {
            $user->password = bcrypt($form['password']);
        }
        if ($request->avatar !== '0') {
            if ($user->avatar) {

                Storage::delete('public/avatars_profile/' . $user->avatar->file_name);
                $user->avatar->delete();
                $avatar = $user->update_avatar($request->avatar);
                $user->avatar()->save($avatar);
            } else {
                $avatar = $user->update_avatar($request->avatar);
                $user->avatar()->save($avatar);
            }
        }
        $user->save();


        return response()->json(['status' => 201, 'message' => 'Success', 'file' => $user->avatar()->get()]);
    }
}
