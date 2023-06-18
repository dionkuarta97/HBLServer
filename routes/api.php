<?php

use Illuminate\Http\Request;
use App\Http\Controllers\User;
use App\Http\Controllers\Tugas;
use App\Http\Controllers\Survey;
use App\Http\Controllers\Logistik;
use App\Http\Controllers\Pengaduan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyKhusus;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/survey/{role}', [Survey::class, 'getAllSurvey']);
Route::post('/survey/kirim', [Survey::class, 'kirimSurvey']);
Route::post('/survey/banyak', [Survey::class, 'kirimBanyakSurvey']);
Route::get('/dashboard/{role}', [Survey::class, 'dashboard']);
Route::post('/pengaduan', [Pengaduan::class, 'kirimPengaduan']);
Route::Get('/pengaduan', [Pengaduan::class, 'getPengaduan']);
Route::post('/pengaduan/banyak', [Pengaduan::class, 'kirimBanyakPengaduan']);
Route::post('/user/referal', [User::class, 'inputReferal']);
Route::get('/user/referal', [User::class, 'getDataReferal']);
Route::get('/user/{code}', [User::class, 'getJumlahPemilih']);
Route::get('/partai', [SurveyKhusus::class, 'getPartai']);
Route::get('/caleg/{id_partai}/{id_category}', [SurveyKhusus::class, 'getCaleg']);
Route::post('/survey/khusus', [SurveyKhusus::class, 'insertAnswerKhusus']);
Route::get('/tugas/{id}', [Tugas::class, 'getListTugas']);
Route::post('/tugas', [Tugas::class, 'kirimTugas']);
Route::get('/logistik/{id}', [Logistik::class, 'getListLogistik']);
Route::post('/logistik', [Logistik::class, 'kirimLogistik']);