<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\models\Club;
use App\models\Rank;
use App\models\ClubStream;
use App\models\QuizQuestions;
use App\models\QuizHighscores;
use App\models\QuizQuestionsAsked;
use App\models\Training;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\User;
use Validator;
use Mail;


class AjaxController extends Controller
{
	public function getQuizQuestions(Request $request)
	{
		$data=array();
		$questions=QuizQuestions::getLimited($request->minRank,$request->maxRank,$request->compulsary);
		foreach($questions as $q)
		{
			$arr=array('quiz_question_id'=>$q->quiz_question_id,
						'rank_id'=>$q->rank_id,
						'rank_name'=>$q->rank_name,
						'rank_img'=>$q->rank_image_url,
						'rank_num'=>$q->rank_num,
						'question'=>$q->question,
						'answer'=>$q->answer,
						'option1'=>$q->option1,
						'option2'=>$q->option2,
						'option3'=>$q->option3,
						'option4'=>$q->option4,
						'compulsary'=>$q->compulsary,
						'image'=>$q->image);
			$data[]=$arr;
		}
		return json_encode($data);
	}
	public function getAverageLevel(Request $request)
	{
		$lvl=$request->lvl;
		if($lvl==0)$lvl=-1;
		$rank=Rank::getByNum(1,$lvl);
		
		return $rank->rank_image_url;
	}
	public function recordAnswer(Request $request)
	{
		$user = Auth::user();
		if($user==NULL)$uID=NULL;
		else $uID=$user->user_id;
		$id=$request->question_id;
		$quiz_id=$request->quiz_id;
		$selectedAnswer=$request->selectedAnswer;
		$record=array('user_id'=>$uID,'quiz_id'=>$quiz_id,'quiz_question_id'=>$id,'your_answer'=>$selectedAnswer);
		$q= new QuizQuestionsAsked($record);
		$q->write();
		//'user_id','quiz_id','quiz_question_id','answer'
	}
	public function questionReview(Request $request)
	{
		$data['quizQuestions']=QuizQuestionsAsked::getByQuiz($request->quiz_id);
		return view('ajax.quizreview',$data);
	}
	public function checkHS(Request $request)
	{
		$data['topTen']=0;
		$data['topTenRank']=0;
		$data['testType']=$request->testType;
		$user = Auth::user();
		$rank=Rank::getByNum(1,$request->average_rank_num);
		if($user!=NULL)$data['user']=$user;
		$topTen=QuizHighscores::inTopTen($request->score,$request->testType);
		$topTenRank=QuizHighscores::inTopTenByRank($request->score,$request->testType,$rank->rank_id);
		if($topTen!=false)$data['topTen']=$data['topTenRank']=$this->ordinal_suffix($topTen,true);
		if($topTenRank!=false)
		{
			$data['topTenRank']=$this->ordinal_suffix($topTenRank,true);
			$data['rank']=$rank;
		}
		if($topTen==false && $topTenRank==false)
		{
			$data['topTenTable']=QuizHighscores::getTopTen($request->testType);
			$data['topTenTableRank']=QuizHighscores::getTopTenRank($request->testType,$rank->rank_id);
		}
		return view('ajax.newhs',$data);
	}
	public function saveHS(Request $request)
	{
		$data['testType']=$request->test_type;
		$user = Auth::user();
		$rank=Rank::getByNum(1,$request->average_rank_num);
		$arr=array('name'=>$request->hs_name,'user_id'=>($user==NULL?NULL:$user->user_id),'average_rank_id'=>$rank->rank_id,
					'score'=>$request->score,'number_correct'=>$request->number_correct,'test_total'=>$request->test_total,
					'test_type'=>$request->test_type);
		$hs=new QuizHighscores($arr);
		$hs->write();
		$data['rank']=$rank;
		$data['message']='High score successfully saved';
		$data['topTenTable']=QuizHighscores::getTopTen($request->test_type);
		$data['topTenTableRank']=QuizHighscores::getTopTenRank($request->test_type,$rank->rank_id);

		return view('ajax.savehs',$data);
	}
	public static function ordinal_suffix($n, $return_n = true) {
	  $n_last = $n % 100;
	  if (($n_last > 10 && $n_last << 14) || $n == 0) {
		$suffix = "th";
	  } else {
		switch(substr($n, -1)) {
		  case '1':    $suffix = "st"; break;
		  case '2':    $suffix = "nd"; break;
		  case '3':    $suffix = "rd"; break;
		  default:     $suffix = "th"; break;
		}
	  }
	  return $return_n ? $n . $suffix : $suffix;
	}
	public function getStreamCalendar(Request $request,$club)
	{
		$stream=$request->club_stream;
		$data['club']=Club::getByShortName($club);
		$data['stream']=ClubStream::find($stream);
		$minDay=Carbon::createFromFormat("Y-m-d",$request->start)->startOfDay();
		$maxDay=Carbon::createFromFormat("Y-m-d",$request->end)->startOfDay();
		$trainingDays=array();
		if($stream==NULL || $stream=='')$trainings=Training::getByClub($data['club']->club_id);
		else $trainings=Training::getByClubStream($stream);
		$events=array();
		$colors=array(
		array('color'=>'#fff','text'=>'#000'),
		array('color'=>'#ffff33','text'=>'#000'),
		array('color'=>'#00b300','text'=>'#fff'),
		array('color'=>'#0040ff','text'=>'#fff'),
		array('color'=>'#e60000','text'=>'#fff'),
		array('color'=>'#000','text'=>'#fff'),
		array('color'=>'#990099','text'=>'#fff'),
		array('color'=>'#ff3300','text'=>'#000')
		);
		
		if(count($trainings)>0)
		{
			foreach($trainings as $t)
			{
				$event=array();
				$startTime=Carbon::createFromFormat("Y-m-d H:i:s",$t->start_time);
				if($startTime->between($minDay,$maxDay))
				{
					$event['title']=($t->Syllabus!=NULL?$t->Syllabus->syllabus_name:$t->training_notes);
					if($t->min_rank_id==NULL && $t->max_rank_id==NULL)
					{
						$event['title'].=' Whole Class';
					}
					else if($t->min_rank_id==NULL)
					{
						$event['title'].=' Below '.$t->MaxRank->rank_name;
					}
					else if($t->max_rank_id==NULL)
					{
						$event['title'].=' Above '.$t->MinRank->rank_name;
					}
					else
					{
						$event['title'].=' '.$t->MinRank->rank_name.'-'.$t->MaxRank->rank_name;
					}
					if($t->instructor_id!=NULL)$event['title'].=' '.$t->Instructor->getDisplayName();
					$event['start']=str_replace(' ','T',$t->start_time);
					$event['end']=str_replace(' ','T',$t->end_time);
					$event['borderColor']='#333';
					$event['syllabus_id']=$t->syllabus_id;
					$event['min_rank_id']=$t->min_rank_id;
					$event['max_rank_id']=$t->max_rank_id;
					$event['min_age']=$t->min_age;
					$event['max_age']=$t->max_age;
					if($t->min_rank_id==NULL && $t->max_rank_id==NULL)
					{
						$event['min_color']='#ff3300';
						$event['max_color']='#ff3300';
					}
					else
					{
						if($t->min_rank_id!=NULL)$event['min_color']=$t->MinRank->rank_colour;
						else $event['min_color']='#222';
						if($t->max_rank_id!=NULL)$event['max_color']=$t->MaxRank->rank_colour;
						else $event['max_color']='#eee';
					}
					if($event['min_color']=='#fff')$event['min_color']='#ccc';
					if($event['max_color']=='#fff')$event['max_color']='#ccc';
					$event['textColor']='#000';
					$event['instructor_id']=$t->instructor_id;
					$event['training_notes']=$t->training_notes;
					$event['training_id']=$t->training_id;
					$events[]=$event;
				}
			}
		}

		return json_encode($events);
	}
	public function saveSession(Request $request,$club)
	{
		$data['club']=Club::getByShortName($club);
		$post=$request->all();
		if(isset($post['min_age']) && $post['min_age']==0)$post['min_age']=NULL;
		if(isset($post['max_age']) && $post['max_age']==0)$post['max_age']=NULL;
		if(isset($post['min_rank_id']) && $post['min_rank_id']==0)$post['min_rank_id']=NULL;
		if(isset($post['max_rank_id']) && $post['max_rank_id']==0)$post['max_rank_id']=NULL;
		if(isset($post['instructor_id']) && $post['instructor_id']==0)$post['instructor_id']=NULL;
		if($post['syllabus_id']==0)$post['syllabus_id']=NULL;
		$session=new Training($post);
		$session->save();
	}
	public function updateSession(Request $request,$club)
	{
		$data['club']=Club::getByShortName($club);
		$post=$request->all();
		if(isset($post['training_id']))
		{
			$training=Training::find($post['training_id']);
			$training->instructor_id=$post['instructor_id']==0?NULL:$post['instructor_id'];
			$training->start_time=$post['start_time'];
			$training->end_time=$post['end_time'];
			$training->min_age=$post['min_age']==0?NULL:$post['min_age'];
			$training->max_age=$post['max_age']==0?NULL:$post['max_age'];
			$training->min_rank_id=$post['min_rank_id']==0?NULL:$post['min_rank_id'];
			$training->max_rank_id=$post['max_rank_id']==0?NULL:$post['max_rank_id'];
			$training->training_notes=$post['training_notes'];
			$training->save();
		}
	}
	public function updateSessionTime(Request $request,$club)
	{
		$data['club']=Club::getByShortName($club);
		$post=$request->all();
		if(isset($post['training_id']))
		{
			$training=Training::find($post['training_id']);
			$training->start_time=$post['start_time'];
			$training->end_time=$post['end_time'];
			$training->save();
		}
	}
	public function deleteSession(Request $request,$club)
	{
		if($request->training_id!='')
		{
			$training=Training::find($request->training_id);
			$training->delete();
		}
	}
    
}
