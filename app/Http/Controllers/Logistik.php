<?php

namespace App\Http\Controllers;

use App\LogistikModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Logistik extends Controller
{
    public function getListLogistik(Request $request)
    {
        try {
            $id = $request->route("id");
            $tipe = $request->query("tipe");
            $where = [["pic", "=", $id]];
            if ($tipe == "aktif") {
                array_push($where, ["status", "=", 1]);
            } else {
                array_push($where, ["status", "!=", 1]);
            }
            $logistik = LogistikModel::where($where)
                ->orderBy("id", "DESC")
                ->get();
            return response()->json($logistik, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(), 500]);
        }
    }
    public function kirimLogistik(Request $request)
    {
        try {
            //code...
            $uploadFolder = "logistik";
            $id = $request->id;
            $image = $request->file("image");
            $image_uploaded_path = $image->store($uploadFolder, "public");

            $logistik = LogistikModel::find($id);
            $logistik->timestamps = false;
            $logistik->laporan = $request->laporan;
            $logistik->path =
                env("APP_URL") . "/public/storage/" . $image_uploaded_path;
            $logistik->latitude = $request->latitude;
            $logistik->longtitude = $request->longtitude;
            $logistik->status = 2;
            $logistik->save();

            return response()->json($logistik, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(), 500]);
        }
    }
}
