<?php

namespace App\Http\Controllers;

use App\UserModel;
use Illuminate\Http\Request;

class User extends Controller
{
    public function inputReferal(Request $request)
    {
        try {
            $code = $request->code;
            $id = $request->id;



            $user = UserModel::where("reference_code", "=", $code)->first();
            if (!$user) {
                return response()->json([
                    "message" => "referal code yang anda masukan salah",

                ], 400);
            }
            $update = UserModel::find($id);
            if ($update["referrer_to"] != "") {
                return response()->json([
                    "message" => "anda sudah pernah memasukan referal code",

                ], 400);
            }

            if ($update["reference_code"] == $code) {
                return response()->json([
                    "message" => "anda memasukan referal code sendiri",

                ], 400);
            }

            if ($user["role"] == 1) {
                return response()->json([
                    "message" =>
                    "referal code yang anda masukan bukan punya relawan / koordinator",

                ], 400);
            }

            if ($request->file("image")) {
                $uploadFolder = "avatar";
                $id = $request->id;
                $image = $request->file("image");
                $image_uploaded_path = $image->store($uploadFolder, "public");
                $update->timestamps = false;
                $update->referrer_to = $code;
                $update->latitude = $request->latitude;
                $update->longtitude = $request->longtitude;
                $update->role = 2;
                $update->path =
                    env("APP_URL") . "/storage/" . $image_uploaded_path;
                $update->save();
                return response()->json([
                    "message" => "berhasil memasukan referal code",

                ], 200);
            } else {
                $update->timestamps = false;
                $update->referrer_to = $code;
                $update->latitude = $request->latitude;
                $update->longtitude = $request->longtitude;
                $update->save();
                return response()->json([
                    "message" => "berhasil memasukan referal code",

                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }


    public function checkReferal(Request $request)
    {
        try {
            $code = $request->code;
            $id = $request->id;

            $user = UserModel::where("reference_code", "=", $code)->first();
            if (!$user) {
                return response()->json([
                    "message" => "referal code yang anda masukan salah",

                ], 400);
            }
            $update = UserModel::find($id);
            if ($update["referrer_to"] != "") {
                return response()->json([
                    "message" => "anda sudah pernah memasukan referal code",

                ], 400);
            }

            if ($update["reference_code"] == $code) {
                return response()->json([
                    "message" => "anda memasukan referal code sendiri",

                ], 400);
            }

            if ($user["role"] == 1) {
                return response()->json([
                    "message" =>
                    "referal code yang anda masukan bukan punya relawan / koordinator",

                ], 400);
            } else if ($user["role"] == 2) {
                return response()->json([
                    "message" => "relawan",

                ], 200);
            } else if ($user["role"] == 3) {
                return response()->json([
                    "message" => "koordinator",
                ], 200);
            } else {
                return response()->json([
                    "message" =>
                    "referal code yang anda masukan bukan punya relawan / koordinator",

                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function getDataReferal(Request $request)
    {
        try {
            $code = $request->query("code");
            $user = UserModel::where("reference_code", "=", $code)->first();

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(),], 500);
        }
    }

    public function getJumlahPemilih(Request $request)
    {
        try {
            $code = $request->route("code");
            $user = UserModel::where("referrer_to", "=", $code)->get();
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(),], 500);
        }
    }

    public function updateLastLogin(Request $request)
    {
        try {
            $id = $request->id;
            $user = UserModel::find($id);
            $user->timestamps = false;
            $user->last_login = now();
            $user->save();
            return response()->json([
                "message" => "berhasil memperbarui",

            ], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(),], 500);
        }
    }
}
