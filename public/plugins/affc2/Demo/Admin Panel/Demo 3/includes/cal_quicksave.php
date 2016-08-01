<?php

	// Loader - class and connection
	include('loader.php');

	if(isset($_GET['token']) && $_GET['token'] == $_SESSION['token'])
	{
		// Catch start, end and id from javascript
		$title = $_POST['title'];
		$description = $_POST['description'];
		$start_date = (strlen($_POST['start_date']) !== 0 ? $_POST['start_date'] : date('Y-m-d', time()));
		$start_time = (strlen($_POST['start_time']) !== 0 ? $_POST['start_time'] : '00:00:00');
		$end_date = (strlen($_POST['end_date']) !== 0 ? $_POST['end_date'] : date('Y-m-d', strtotime('+1 day', strtotime($start_date))));
		$end_time = (strlen($_POST['end_time']) !== 0 ? $_POST['end_time'] : '00:00:00');
		$color = $_POST['color'];
		$allDay = $_POST['all-day'];
		
		$extra = array('repeat_method' => $_POST['repeat_method'], 'repeat_times' => $_POST['repeat_times']);
		
		$extra['user_id'] = get_user("ID"); // [THIS DEMO EXCLUSIVE]
		
		// Category Handler - Core
		// If you want to have the categories in your creations use this code, some demos does not it because does not make use of category
		if(isset($_POST['categorie']) && strlen($_POST['categorie']) !== 0)
		{
			$extra['categorie'] = $_POST['categorie'];
		} else {
			$extra['categorie'] = '';	
		}
		
		if(strlen($title) == 0) 
		{
			echo 0;	
		} else {
			$add_event = $calendar->addEvent($title, $description, $start_date, $start_time, $end_date, $end_time, $color, $allDay, 'false', $extra);
			if($add_event == true)
			{
				echo 1;
			} else {
				echo 0;	
			}
		}
	}
	

?>