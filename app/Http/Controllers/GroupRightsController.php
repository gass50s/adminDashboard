<?php

namespace App\Http\Controllers;

use App\Models\Rights;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupRightsController extends Controller
{
    public function AddRights(Request $request)
    {
        $form = json_decode($request->body, true);
        $validator = Validator::make(
            $form,
            [
                'groupe' => 'unique:rights'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        }
        $right = new Rights;
        $right->groupe = $form['groupe'];
        $right->use_right = $form['use_right'];
        $right->save();
        return response()->json(['status'=> 201,'message'=> 'succes']);
    }
    public function GetRights(){
        $rights = Rights::all();
        return response()->json(['rights'=>$rights]);
    }
    public function getOneRight(Request $request){
        $right = Rights::find($request->id);
        return response()->json(["status"=>201,"message"=>$right]);
    }
    public function UpdateRight(Request $request)
    {
        $form = json_decode($request->body, true);
        $validator = Validator::make(
            $form,
            [
                'groupe' => 'unique:rights,groupe,'.$request->id,
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        }

         $right = Rights::find($request->id);
         $right->groupe = $form['groupe'];
         $right->use_right = $form['use_right'];
         $right->save();
        return response()->json(['status' => 201, 'message' => $right]);
    }
    public function DeleteRight($id){
        $right = Rights::find($id);
        $right->delete();
        return response()->json(['status' => 201, 'message' => 'Success']);
    }
}
