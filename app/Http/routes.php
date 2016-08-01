<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('quiz', 'QuizController@getQuiz');
Route::post('ajax/quiz/getQuestions', 'AjaxController@getQuizQuestions');
Route::post('ajax/quiz/avgLvl', 'AjaxController@getAverageLevel');
Route::post('ajax/quiz/recordAnswer', 'AjaxController@recordAnswer');
Route::post('ajax/quiz/questionReview', 'AjaxController@questionReview');
Route::post('ajax/quiz/checkHS', 'AjaxController@checkHS');
Route::post('ajax/quiz/saveHS', 'AjaxController@saveHS');

        Route::get('admin/quiz/questions', 'QuizController@viewQuestions');
        Route::get('admin/quiz/question/add', 'QuizController@addQuestion');
        Route::post('admin/quiz/question/add', 'QuizController@processAddQuestion');
        Route::get('admin/quiz/question/{quiz_question_id}', 'QuizController@editQuestion');
        Route::post('admin/quiz/question/{quiz_question_id}', 'QuizController@processEditQuestion');

		Route::get('{club}/training', 'TrainingController@viewTrainings');
		Route::get('{club}/training/{stream}', 'TrainingController@viewStreamTrainings');
		Route::post('ajax/{club}/training', 'AjaxController@getStreamCalendar');
		Route::post('ajax/{club}/training/save', 'AjaxController@saveSession');
		Route::post('ajax/{club}/training/update', 'AjaxController@updateSession');
		Route::post('ajax/{club}/training/updatetimes', 'AjaxController@updateSessionTime');
		Route::post('ajax/{club}/training/delete', 'AjaxController@deleteSession');
Route::get('/', 'QuizController@getQuiz');
