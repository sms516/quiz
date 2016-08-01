<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use SoftDeletes;
    protected $table = 'training';
    protected $primaryKey ='training_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['club_id','club_stream_id','syllabus_id','start_time','end_time','instructor_id','training_notes','created_by','min_rank_id','max_rank_id','min_age','max_age'];

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
	public static function getByClub($club)
    {
        return Training::where('club_id','=',$club)->get();
    }
	public static function getByClubStream($clubStream)
    {
        return Training::where('club_stream_id','=',$clubStream)->get();
    }
	public function Syllabus()
	{
		return $this->hasOne('App\models\Syllabus','syllabus_id','syllabus_id');
	}
	public function Instructor()
	{
		return $this->hasOne('App\User','user_id','instructor_id');
	}
	public function MinRank()
	{
		return $this->hasOne('App\models\Rank','rank_id','min_rank_id');
	}
	public function MaxRank()
	{
		return $this->hasOne('App\models\Rank','rank_id','max_rank_id');
	}
	public function Stream()
	{
		return $this->hasOne('App\models\ClubStream','club_stream_id','club_stream_id');
	}
	public function CreatedBy()
    {
        return $this->hasOne('App\User', 'user_id','created_by');
    }
}
