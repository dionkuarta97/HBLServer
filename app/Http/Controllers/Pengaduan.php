<?php

namespace App\Http\Controllers;

use App\PengaduanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Pengaduan extends Controller
{
    public function kirimPengaduan(Request $request)
    {
        try {
            //code...
            $uploadFolder = "pengaduan";
            $image = $request->file("image");
            $image_uploaded_path = $image->store($uploadFolder, "public");

            $pengaduan = PengaduanModel::insert([
                "id_pelapor" => $request->id_pelapor,
                "judul" => $request->judul,
                "keterangan" => $request->keterangan,
                "path" =>
                    env("APP_URL") . "/public/storage/" . $image_uploaded_path,
                "waktu_pengaduan" => now(),
                "longtitude" => $request->longtitude,
                "latitude" => $request->latitude,
                "status" => 1,
            ]);

            return response()->json($pengaduan, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(), 500]);
        }
    }
    public function getPengaduan(Request $request)
    {
        try {
            $id_pelapor = $request->query("id_pelapor");
            $tipe = $request->query("tipe");
            $where = [];
            if ($tipe == "aktif") {
                $where = [
                    ["status", "!=", 3],
                    ["id_pelapor", "=", $id_pelapor],
                ];
            } else {
                $where = [["status", "=", 3], ["id_pelapor", "=", $id_pelapor]];
            }

            $pengaduan = PengaduanModel::where($where)
                ->orderBy("id", "DESC")
                ->get();
            return response()->json($pengaduan, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(), 500]);
        }
    }

    public function kirimBanyakPengaduan(Request $request)
    {
        try {
            //code...
            $arr = [];
            $index = 0;
            foreach ($request->file("image") as $key) {
                $uploadFolder = "pengaduan";
                $image = $key;
                $image_uploaded_path = $image->store($uploadFolder, "public");
                array_push($arr, [
                    "id_pelapor" => $request->id_pelapor[$index],
                    "judul" => $request->judul[$index],
                    "keterangan" => $request->keterangan[$index],
                    "path" =>
                        env("APP_URL") .
                        "/public/storage/" .
                        $image_uploaded_path,
                    "waktu_pengaduan" => now(),
                    "longtitude" => $request->longtitude[$index],
                    "latitude" => $request->latitude[$index],
                    "status" => 1,
                ]);
                $index++;
            }

            $pengaduan = PengaduanModel::insert($arr);

            return response()->json($pengaduan, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(), 500]);
        }
    }
}
