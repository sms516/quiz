<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\models\MembershipType;
use App\models\ClubMembership;
use App\models\ClubMembershipStream;

class ClubStream extends Model
{
    use SoftDeletes;
	 protected $table = 'club_stream';
	protected $primaryKey ='club_stream_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['club_id','stream_name','min_age','max_age','min_rank_id','max_rank_id','stream_details'];

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
	public static function getStreams($club)
	{
		return ClubStream::select('club_stream.*','minRank.rank_name as minRankName','minRank.rank_num as minRankNum',
		'maxRank.rank_name as maxRankName','maxRank.rank_num as maxRankNum')
						->join('rank AS minRank','minRank.rank_id','=','club_stream.min_rank_id')
						->join('rank AS maxRank','maxRank.rank_id','=','club_stream.max_rank_id')
						->where('club_id','=',$club)->get();	
	}
	public function trainingTimes()
	{
		return $this->hasMany('App\models\ClubTrainingTime')->orderBy('training_day')->orderBy('start_time');	
	}
	public function getNextTrainingTime($now=NULL)
	{
		$times=$this->hasMany('App\models\ClubTrainingTime')->orderBy('club_training_times.training_day')->get();
		if($now==NULL)$now = new Carbon(); 
		$next=$now->copy();
		$next->addWeek();
		foreach($times as $t)
		{
			$day=$t->training_day;
			if($now->dayOfWeek==$day)
			{
				$train=Carbon::today();				
				$times=explode(':',$t->start_time);
				$train->addHours($times[0]);
				$train->addMinutes($times[1]);
				if($now>$train)
				{
					$train->addWeek();
				}
			}
			else 
			{
				$train=new Carbon('next '.date('l', strtotime("Sunday +".$t->training_day." days")));
				$times=explode(':',$t->start_time);
				$train->addHours($times[0]);
				$train->addMinutes($times[1]);
			}
			if($train<$next)
			{
				$next=$train;
			}
		}
		return $next->format('Y/m/d H:i:s');
	}
	public function isTrainingDay($now=NULL)
	{
		//$now=Carbon::createFromDate(2015,8,23);	
		$todaysSessions=array();
		$times=$this->hasMany('App\models\ClubTrainingTime')->orderBy('club_training_times.training_day')->get();
		if($now==NULL)$now = new Carbon(); 
		foreach($times as $t)
		{
			$day=$t->training_day;
			if($now->dayOfWeek==$day)
			{
				$todaysSessions[]=$t;
			}
		}
		return $todaysSessions;
	}
	public function memberships()
	{
		return MembershipType::join('club_membership','club_membership.membership_type_id','=','membership_type.membership_type_id')
						->join('club_membership_stream','club_membership_stream.club_membership_id','=','club_membership.club_membership_id')
						->where('club_membership_stream.club_stream_id','=',$this->club_stream_id)->get();	
		return $this->hasManyThrough('App\models\ClubMembership','App\models\ClubMembershipStream');	
	}
	public function minRank()
    {
        return $this->hasOne('App\models\Rank', 'rank_id', 'min_rank_id');
    }
	public function maxRank()
    {
        return $this->hasOne('App\models\Rank', 'rank_id', 'max_rank_id');
    }
}
