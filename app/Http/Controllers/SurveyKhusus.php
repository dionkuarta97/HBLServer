<?php

namespace App\Http\Controllers;

use App\UserModel;
use App\CalegModel;
use App\PartaiModel;
use App\SurveyKhusuModel;
use Illuminate\Http\Request;

class SurveyKhusus extends Controller
{
    public function getPartai(Request $request) {
        try {
            $partai = PartaiModel::get();
            return response()->json($partai, 200); 
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function getCaleg(Request $request){
        try {
            $where = array();
            $id_partai = $request->route('id_partai');
            $id_category = $request->route('id_category');
            $id_daerah = $request->query('id_daerah');

            array_push($where, ['id_partai', '=' , $id_partai],['id_category', '=' , $id_category]);
            if($id_daerah) {
                array_push($where, ['id_daerah','=', $id_daerah]);
            }

            $caleg = CalegModel::where($where)->get();
            return response()->json($caleg, 200); 

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function insertAnswerKhusus (Request $request) {
        try {
            $arr = $request->data;
            $user = UserModel::find($arr[0]['id_user']);
            $user->timestamps = false;
            $user->attemp_survey = 0;
            $user->save();
            $result = SurveyKhusuModel::insert($arr);
            return response()->json($result, 200); 
        }  catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
