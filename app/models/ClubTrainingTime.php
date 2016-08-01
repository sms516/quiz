<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ClubTrainingTime extends Model
{
    use SoftDeletes;
	 protected $table = 'club_training_times';
	protected $primaryKey ='club_training_time_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['club_id','club_stream_id','training_day','start_time','end_time'];

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
	public static function getClasses($club)
	{
		return ClubTrainingTime::join('club_stream','club_stream.club_stream_id','=','club_training_times.club_stream_id')
								->where('club_training_times.club_id','=',$club)->orderBy('club_training_times.training_day')
								->orderBy('club_stream.stream_name')
								->orderBy('club_training_times.start_time')->get();	
	}
	public static function getTrainingTime($club,$stream,$dow)
	{
		$query=ClubTrainingTime::where('club_id','=',$club)
				->where('club_stream_id','=',$stream)
				->where('training_day','=',$dow)->orderBy('start_time')->get();
		return $query;
	}
	public function getTime($segment,$time)
	{
		if($time=='start_time')
		{
			$t=Carbon::createFromFormat('H:i:s', $this->start_time);
		}
		else
		{
			$t=Carbon::createFromFormat('H:i:s', $this->end_time);
		}
		if($segment=='hour')return $t->hour;
		else return $t->minute;
	}
}
