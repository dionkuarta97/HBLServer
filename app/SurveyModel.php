<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SurveyModel extends Model
{   
    protected $table = 'survey';
    public function question()
    {
        return $this->hasMany(SurveyQuestionModel::class, 'id_survey')->orderBy('id', 'ASC');
    }
}
