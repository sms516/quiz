<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\models\Club;
use App\models\Rank;
use App\models\UserRank;
use App\models\QuizQuestions;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\User;
use Validator;
use Mail;


class QuizController extends Controller
{
	public function getQuiz()
	{
		$data=array();	
		$data['title']='Multi Choice Quiz';
		$data['ranks']=Rank::getRankGroup(1); // HARD CODING TKD RANKS
		return view('quiz.view',$data);
	}
	public function viewQuestions(Request $request)
	{
		$data=array();
		$data['title']='Multi Choice Quiz Questions';
		$data['questions']=QuizQuestions::getAll();
		return view('quiz.questions.view',$data);
		
	}  
	public function addQuestion()
	{
		$data=array();
		$data['title']='Add Question';
		$data['ranks']=Rank::getRankGroup(1); // HARD CODING TKD RANKS
		return view('quiz.questions.add',$data);
	}
	public function processAddQuestion(Request $request)
	{
		$request->flash();
		$validator = Validator::make($request->all(), [
			 'rank_id' => 'required',
			'question' => 'required|unique:quiz_questions,question',
			'answer' => 'required',
			'option1' => 'required']);

        if ($validator->fails()) {
             return redirect('admin/quiz/questions/add')->withErrors($validator);
        }
		else
		{
			$question=new QuizQuestions($request->all());
			if($request->option2=='')$question->option2=NULL;
			if($request->option3=='')$question->option3=NULL;
			if($request->option4=='')$question->option4=NULL;
			if($request->compulsary=='')$question->compulsary=0;
			$question->write();
			return redirect('admin/quiz/questions')->with('status', 'Question successfully added');
		}
	}
	public function editQuestion(Request $request)
	{
		$data=array();
		$data['title']='Add Question';
		$data['ranks']=Rank::getRankGroup(1); // HARD CODING TKD RANKS
		$data['question']=QuizQuestions::find($request->quiz_question_id);
		return view('quiz.questions.edit',$data);
	}
	public function processEditQuestion(Request $request)
	{
		$request->flash();
		$validator = Validator::make($request->all(), [
			 'rank_id' => 'required',
			'question' => 'required|unique:quiz_questions,question,'.$request->quiz_question_id.',quiz_question_id',
			'answer' => 'required',
			'option1' => 'required']);

        if ($validator->fails()) {
             return redirect('admin/quiz/question/'.$request->quiz_question_id)->withErrors($validator);
        }
		else
		{
			$question=QuizQuestions::find($request->quiz_question_id);
			$question->rank_id=$request->rank_id;
			$question->question=$request->question;
			$question->option1=$request->option1;
			if($request->option2=='')$question->option2=NULL;
			else $question->option2=$request->option2;
			if($request->option3=='')$question->option3=NULL;
			else $question->option3=$request->option3;
			if($request->option4=='')$question->option4=NULL;
			else $question->option4=$request->option4;
			if($request->compulsary=='')$question->compulsary=0;
			else $question->compulsary=$request->compulsary;
			$question->write();
			return redirect('admin/quiz/questions')->with('status', 'Question successfully updated');
		}
	}
}
