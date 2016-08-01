<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\models\ClubMeta;
use App\models\Rank;
use DB;


class QuizQuestionsAsked extends Model
{
	 protected $table = 'quiz_questions_asked';
	protected $primaryKey ='quiz_question_asked_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','quiz_id','quiz_question_id','your_answer'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	protected $dates= ['deleted_at'];
	public function write()
	{
		$this->save();
	}
	public static function getByQuiz($quiz)
	{
		$qry= QuizQuestionsAsked::join('quiz_questions','quiz_questions.quiz_question_id','=','quiz_questions_asked.quiz_question_id')
		->join('rank','rank.rank_id','=','quiz_questions.rank_id')
		->where('quiz_id','=',$quiz);	
		$retval = $qry->get();	
		
		return sizeof($retval)=== 1 ? $retval->pop() : $retval; 
	}
}
