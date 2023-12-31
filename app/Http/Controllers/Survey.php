<?php

namespace App\Http\Controllers;
use App\TugasModel;
use App\SurveyModel;
use App\LogistikModel;
use App\PengaduanModel;
use App\SurveyAnswerModel;
use App\SurveyStatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use \Datetime;

class Survey extends Controller
{
    public function getAllSurvey(Request $request) {
        try {
            $role = $request->route('role');
            $id = $request->query('id_pengisi');
            if($role == 1) {
                $surveyStatus = SurveyStatusModel::with(['survey'])->where('id_pengisi', '=' , $id)->groupBy('id_survey')->selectRaw('count(*) as total, id_survey, id,id_pengisi,max(tanggal_pengisian) as tanggal_pengisian')->orderBy('tanggal_pengisian', 'DESC')->get();
                $survey = SurveyModel::with(['question', 'question.question_detail'])->where([['survey_to', '=', $role] , ['status', '=',1]])->orderBy('id', 'DESC')->get();
                return response()->json(['survey' => $survey, 'surveyStatus' => $surveyStatus], 200);
            } else {
                $surveyStatus = SurveyStatusModel::with(['survey'])->where('id_pengisi', '=' , $id)->groupBy('id_survey')->selectRaw('count(*) as total, id_survey, id, id_pengisi,max(tanggal_pengisian) as tanggal_pengisian')->orderBy('tanggal_pengisian', 'DESC')->get();
                $totalStatus = SurveyStatusModel::with(['survey'])->where('id_pengisi', '=' , $id)->count();
                $survey = SurveyModel::with(['question', 'question.question_detail'])->where([['status', '=',1]])->orderBy('id', 'DESC')->get();
                return response()->json(['survey' => $survey, 'surveyStatus' => $surveyStatus, 'total_status' => $totalStatus], 200);
            }
           
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    
    public function kirimSurvey(Request $request) {
        try {
            $temp = array();
            foreach($request->answer as $key) {
                array_push($temp, [
                    'id_survey' => $key['id_survey'],
                    'id_question' => $key['id_question'],
                    'answer' => $key['answer'],
                    'jawaban' => $key['jawaban'],
                    'id_user' =>number_format($request->id_pengisi)
                ]);
            }
            $today = new DateTime();
            $surveyAnswer = SurveyAnswerModel::insert($temp);
            $surveyStatus = SurveyStatusModel::insert([
                'id_pengisi' =>number_format($request->id_pengisi) ,
                'longtitude' => $request->longtitude,
                'latitude' => $request->latitude,
                'id_survey' => $request->id_survey,
                'tanggal_pengisian' => $today->format('Y-m-d H:i:s')
            ]);
            return response()->json($surveyStatus, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
    public function dashboard (Request $request) {
      try {
        $role = $request->route('role');
        $id = $request->query('id_pengisi');
        $survey;
        $surveyStatus = SurveyStatusModel::where('id_pengisi', '=' , $id)->count();
        $pengaduan = PengaduanModel::where([['id_pelapor', '=' , $id], ['status', '!=' , 3]])->count();
        if($role == 1) {
            $survey = SurveyModel::where([['survey_to', '=', $role], ['status', '=',1]])->count();
            return response()->json(['survey' =>  $survey - $surveyStatus, 'pengaduan' => $pengaduan], 200);
        } else {
            $survey = SurveyModel::where('status', '=',1)->count();
            $tugas = TugasModel::where([['status', '=',1], ['pic', '=',$id]])->count();
            $logistik = LogistikModel::where([['status', '=',1], ['pic', '=',$id]])->count();
            return response()->json([
                'survey' =>  $survey,
                'pengaduan' => $pengaduan,
                'tugas' => $tugas,
                'logistik' => $logistik],
                 200);
        }
       
       

        
      } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage(), 500]);
    }
  }
  public function kirimBanyakSurvey(Request $request) {
        try {
            $data = $request->data;
            DB::beginTransaction();
            try {
                foreach($data as $key) {
                    $temp = array();
                    foreach($key['answer'] as $val) {
                        array_push($temp, [
                            'id_survey' => $val['id_survey'],
                            'id_question' => $val['id_question'],
                            'answer' => $val['answer'],
                            'jawaban' => $val['jawaban'],
                            'id_user' =>number_format($key['id_pengisi'])
                        ]);
                    }
                    $surveyAnswer = SurveyAnswerModel::insert($temp);
                    $surveyStatus = SurveyStatusModel::insert([
                        'id_pengisi' =>number_format($key['id_pengisi']) ,
                        'longtitude' => $key['longtitude'],
                        'latitude' => $key['latitude'],
                        'id_survey' => $key['id_survey'],
                        'tanggal_pengisian' => now()
                    ]);
                }

                DB::commit();
                return response()->json(['message' => 'data berhasil di tambahkan'], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json($th, 500);
            }
        
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
