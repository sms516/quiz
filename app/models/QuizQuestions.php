<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\models\ClubMeta;
use App\models\Rank;
use DB;


class QuizQuestions extends Model
{
    use SoftDeletes;
	 protected $table = 'quiz_questions';
	protected $primaryKey ='quiz_question_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['rank_id','question','answer','option1','option2','option3','option4','compulsary','image'];

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
	public static function getByRank($rank)
	{
		$qry= QuizQuestions::where('rank_id','=',$rank);	
		$retval = $qry->get();	
		
		return sizeof($retval)=== 1 ? $retval->pop() : $retval; 
	}
	public static function getAll()
	{
		return QuizQuestions::join('rank','rank.rank_id','=','quiz_questions.rank_id')->get();	
	}
	public static function getLimited($minr,$maxr,$compulsary)
	{
		$query=QuizQuestions::join('rank','rank.rank_id','=','quiz_questions.rank_id');
		if($minr!='' && $maxr!='')
		{
		$minRank=Rank::find($minr);
		$maxRank=Rank::find($maxr);
		
		if($minRank->rank_num>$maxRank->rank_num)
		{
			$t=$maxRank;
			$maxRank=$minRank;
			$minRank=$t;
		}
		$query=$query->whereBetween('rank.rank_num',array($minRank->rank_num,$maxRank->rank_num));
		}
		if($compulsary==1)$query=$query->where('compulsary','=',1);
		return $query->orderBy(DB::raw('RAND()'))->get();
	}
}
