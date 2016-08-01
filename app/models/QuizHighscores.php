<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\models\ClubMeta;
use App\models\Rank;
use DB;


class QuizHighscores extends Model
{
    use SoftDeletes;
	 protected $table = 'quiz_highscores';
	protected $primaryKey ='quiz_highscores_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','user_id','average_rank_id','score','number_correct','test_total','test_type'];

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
	public static function inTopTen($score,$testType)
	{
		$query= QuizHighscores::where('test_type','=',$testType)->where('score','>=',$score)->get();
		if(count($query)>=10)return false;
		return count($query)+1;
	}
	public static function inTopTenByRank($score,$testType,$avgLvl)
	{
		$query= QuizHighscores::where('test_type','=',$testType)->where('score','>=',$score)->where('average_rank_id','=',$avgLvl)->get();
		if(count($query)>=10)return false;
		return count($query)+1;
	}
	public static function getTopTen($testType)
	{
		$query= QuizHighscores::join('rank','rank.rank_id','=','quiz_highscores.average_rank_id')->where('test_type','=',$testType)
				->orderBy('score', 'DESC')->take(10)->get();
		return $query;
	}
	public static function getTopTenRank($testType,$avgLvl)
	{
		$query= QuizHighscores::join('rank','rank.rank_id','=','quiz_highscores.average_rank_id')->where('test_type','=',$testType)
		->where('average_rank_id','=',$avgLvl)
				->orderBy('score', 'DESC')->take(10)->get();
		return $query;
	}
	
}
