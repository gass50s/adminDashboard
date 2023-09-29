<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ClusterManagement extends Controller
{
    public function AddCluster(Request $request)
    {
        $form = json_decode($request->body, true);
        $validator = Validator::make(
            $form,
            [
                'cluster' => 'unique:clusters'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        }

        $country = $form['country'];
        $cluster = new Cluster;
        $cluster->country = $country['value'];
        $cluster->cluster = $form['cluster'];
        $cluster->save();
        return response()->json(['status' => 201, 'message' => 'Success']);
    }
    public function GetCluster()
    {
        $clusters = Cluster::all();
        return response()->json(["cluster_list" => $clusters]);
    }
    public function UpdateCluster(Request $request)
    {
        $form = json_decode($request->body, true);
        $validator = Validator::make(
            $form,
            [
                'cluster' => 'unique:clusters,cluster,'.$request->id,
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        }

         $cluster = Cluster::find($request->id);
         $country = $form['country'];
         $cluster->country = $country['value'];
         $cluster->cluster = $form['cluster'];
         $cluster->save();
        return response()->json(['status' => 201, 'message' => $country]);
    }
    public function GetOneCluster(Request $request)
    {
        $cluster = Cluster::find($request->id);
        return response()->json(['status' => 201, 'cluster' => $cluster]);
    }
    public function DeleteCluster($id)
    {
        $cluster = Cluster::find($id);
        $cluster->delete();
        return response()->json(['status' => 201, 'message' => 'Success']);
    }
}
