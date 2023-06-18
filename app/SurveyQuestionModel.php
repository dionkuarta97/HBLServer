<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionModel extends Model
{   
    protected $table = 'survey_question';
    public function question_detail()
    {
        return $this->hasMany(SurveyQuestionDetailModel::class, 'id_question')->orderBy('id', 'ASC');
    }
}
