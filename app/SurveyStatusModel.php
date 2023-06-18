<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SurveyStatusModel extends Model
{   
    protected $table = 'survey_status';
    protected $fillable = ['id_pengisi'];
    public function survey()
    {
        return $this->belongsTo(SurveyModel::class, 'id_survey', 'id');
      
    }
}
