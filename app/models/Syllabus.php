<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Syllabus extends Model
{
    use SoftDeletes;
	 protected $table = 'syllabus';
	protected $primaryKey ='syllabus_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['syllabus_name','club_id','min_rank_id'];

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
	public function rankSyllabus()
	{
		return $this->hasMany('App\models\RankSyllabus','syllabus_id','syllabus_id');
	}
	public function minRank()
	{
		return $this->hasOne('App\models\Rank','rank_id','min_rank_id');	
	}
	public function rankSyllabusCount($id)
	{
		
		return count($this->hasMany('App\models\RankSyllabus','syllabus_id','syllabus_id')->where('rank_syllabus.rank_id','=',$id)->get());
	}
	public function getSyllabusForRank($id)
	{
		return $this->hasMany('App\models\RankSyllabus','syllabus_id','syllabus_id')->where('rank_syllabus.rank_id','=',$id)->get();
	}
}
