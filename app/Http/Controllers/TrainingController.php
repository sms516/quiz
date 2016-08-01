<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\models\Club;
use App\models\ClubStream;
use App\models\Rank;
use App\models\UserRank;
use App\models\Training;
use App\models\ClubTrainingTime;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\User;
use Validator;
use Mail;


class TrainingController extends Controller
{
	public function viewTrainings($club)
	{
		$data=array();
		$data['title']='Trainings';
		$data['club']=Club::getByShortName($club);
		$data['members']=User::getClubMembers($data['club']->club_id);
		$data['ranks']=Rank::getRankGroup($data['club']->martial_art_id);
		$data['streams']=ClubStream::getStreams($data['club']->club_id);
		$data['excludeDays']=array();
		$max=Carbon::today();
		$min=Carbon::today()->endOfDay();
		$minAge=99;
		$maxAge=0;
		$minRank=-99;
		$maxRank=99;
		foreach($data['streams'] as $s)
		{
			if($minAge>$s->min_age)$minAge=$s->min_age;
			if($maxAge<$s->max_age)$maxAge=$s->max_age;
			if($s->max_age==NULL)$maxAge=99;
			if($minRank<$s->minRank->rank_num)$minRank=$s->minRank->rank_num;
			if($maxRank>$s->maxRank->rank_num)$maxRank=$s->maxRank->rank_num;
			foreach($s->trainingTimes as $t)
			{
				$start=Carbon::createFromFormat("H:i:s",$t->start_time);
				if($min>$start)$min=$start;
				$end=Carbon::createFromFormat("H:i:s",$t->end_time);
				if($max<$end)$max=$end;
				$trainingDays[]=$t->training_day;
			}
		}
		$data['minAge']=$minAge;
		$data['maxAge']=$maxAge;
		$data['minRank']=$minRank;
		$data['maxRank']=$maxRank;
		$data['minTime']=$min->format('H:i:s');
		$data['maxTime']=$max->format('H:i:s');
		for($i=0;$i<7;$i++)
		{
			if(!in_array($i,$trainingDays))
			{
				$data['excludeDays'][]=$i;
			}
		}
		return view('club.training.view',$data);
	}
	public function viewStreamTrainings($club,$stream)
	{
		$data=array();
		$data['stream']=ClubStream::find($stream);
		$data['title']='Trainings - '.$data['stream']->stream_name;
		$data['club']=Club::getByShortName($club);
		$data['members']=User::getClubMembers($data['club']->club_id);
		$data['ranks']=Rank::getRankGroup($data['club']->martial_art_id);
		$data['streams']=ClubStream::getStreams($data['club']->club_id);
		$data['excludeDays']=array();
		$max=Carbon::today();
		$min=Carbon::today()->endOfDay();
		$minAge=99;
		$maxAge=0;
		$minRank=-99;
		$maxRank=99;
		if($minAge>$data['stream']->min_age)$minAge=$data['stream']->min_age;
		if($maxAge<$data['stream']->max_age)$maxAge=$data['stream']->max_age;
		if($data['stream']->max_age==NULL)$maxAge=99;
		if($minRank<$data['stream']->minRank->rank_num)$minRank=$data['stream']->minRank->rank_num;
		if($maxRank>$data['stream']->maxRank->rank_num)$maxRank=$data['stream']->maxRank->rank_num;
		foreach($data['stream']->trainingTimes as $t)
		{
			$start=Carbon::createFromFormat("H:i:s",$t->start_time);
			if($min>$start)$min=$start;
			$end=Carbon::createFromFormat("H:i:s",$t->end_time);
			if($max<$end)$max=$end;
			$trainingDays[]=$t->training_day;
		}
		$data['minAge']=$minAge;
		$data['maxAge']=$maxAge;
		$data['minRank']=$minRank;
		$data['maxRank']=$maxRank;
		$data['minTime']=$min->format('H:i:s');
		$data['maxTime']=$max->format('H:i:s');
		$data['minHour']=$min->format('H');
		$data['maxHour']=$max->format('H');
		for($i=0;$i<7;$i++)
		{
			if(!in_array($i,$trainingDays))
			{
				$data['excludeDays'][]=$i;
			}
		}
		return view('club.training.streamview',$data);
	}
	public function addTraining($club)
	{
		$data=array();
		$data['title']='Add Training';
		/*-----*/
		$data['club']=Club::getByShortName($club);
		$data['streams']=ClubStream::getStreams($data['club']->club_id);
		$data['members']=User::getClubMembers($data['club']->club_id);
		$data['ranks']=Rank::getRankGroup($data['club']->martial_art_id);
		$data['defaultName']=Carbon::now()->toDayDateTimeString();
		$trainingTimes=ClubTrainingTime::getClasses($data['club']->club_id);
		
		return view('club.training.add',$data);
	}
}
