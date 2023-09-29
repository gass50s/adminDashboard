<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Avatar;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {


        $json_decoded = json_decode($request->body, true);

        $validator = Validator::make(
            $json_decoded,
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
        $user->surname =  $json_decoded['surname'];
        $user->last_name = $json_decoded['last_name'];
        $user->sexe = $json_decoded['sexe'];
        $user->email = $json_decoded['email'];
        $user->password = bcrypt($json_decoded['password']);
        $user->member = $json_decoded['member'];
        $user->country = $json_decoded['country'];
        $user->telephone = $json_decoded['telephone'];
        $user->is_banned_account = false;
        $user->save();


        $user->emailsender(env('APP_URL') . '/api/auth/verification-email/' . $user->id, $user->email);
        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken,
            'id' => $user->id
        ]);
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('8', 401);
        } else if (auth()->user()->email_verified_at == null) {
            return $this->error('9', 401);
        }
        $token = auth()->user()->createToken('API Token')->plainTextToken;
        $u = User::find(auth()->user()->id);
        $u->last_login = Carbon::now()->toDateTimeString();
        $u->save();
        $user[] = [
            'id' => auth()->user()->id,
            'surname' => auth()->user()->surname,
            'last_name' => auth()->user()->last_name,
            'email' => auth()->user()->email,
            
        ];
        
        return $this->success([
            'token' => $token,
            'user' => auth()->user(),
            'avatar' => auth()->user()->avatar
        ], response()->json($user));
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }

    public function checktoken()
    {
        if (auth()->user()->tokens()) {
            return true;
        } else {
            return
                false;
        }
    }

    public function verifemail($id)
    {
        $user = User::findOrFail($id);
        if ($user->email_verified_at == null) {
            $user->email_verified_at = Carbon::today();
            $user->save();
        }
        return redirect(env('FRONT_URL') . '/users-management/sign-in');
    }
    public function resend(Request $request)
    {

        $user =  User::find($request->id);
        $user->emailsender(env('APP_URL') . '/api/auth/verification-email/' . $user->id, $user->email);
        return response()->json(['status' => 201, 'message' => 'resend with succes']);
    }
}
