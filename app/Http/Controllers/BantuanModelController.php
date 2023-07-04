<?php

namespace App\Http\Controllers;

use App\BantuanModel;
use Illuminate\Http\Request;

class BantuanModelController extends Controller
{
    //

    public function getPusatBantuan(Request $request)
    {
        try {
            $bantuan = BantuanModel::get();
            return response()->json($bantuan, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage(), 500]);
        }
    }
}
