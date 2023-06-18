<?php

namespace App\Http\Controllers;

use App\TugasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Tugas extends Controller
{
    public function getListTugas (Request $request) {
        try {
            $id = $request->route('id');
            $tipe = $request->query('tipe');
            $where = [['pic','=', $id]];
            if($tipe == 'aktif') {
                array_push($where, ['status', '=', 1]);
            } else {
                array_push($where, ['status', '!=', 1]);
            }
            $tugas = TugasModel::where($where)->orderBy('id', 'DESC')->get();
            return response()->json($tugas, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function kirimTugas (Request $request) {
        try {
            //code...
            $uploadFolder = 'tugas';
            $id = $request->id;
            $image = $request->file('image');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
 
            $tugas = TugasModel::find($id);
            $tugas->timestamps = false;
            $tugas->laporan = $request->laporan;
            $tugas->path = env("APP_URL").'/public/storage/'.$image_uploaded_path;
            $tugas->status = 2;
            $tugas->save();

           
        
            return response()->json($tugas, 200);
        } catch (\Exception $e) {
         return response()->json(['message' => $e->getMessage(), 500]);
     }
    }
}
