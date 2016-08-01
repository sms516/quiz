<?php

	// Loader - class and connection
	include('loader.php');
	
	if(isset($_GET['token']) && $_GET['token'] == $_SESSION['token'])
	{
		// Catch post data from edit modal form
		$event = array(
			'id' => $_POST['id'],
			'title' => $_POST['edit_title'],
			'description' => $_POST['edit_description'],
			'color' => $_POST['edit_color'],
			'start_date' => $_POST['edit_start_date'],
			'start_time' => $_POST['edit_start_time'],
			'end_date' => $_POST['edit_end_date'],
			'end_time' => $_POST['edit_end_time']
		);
		
		$event['url'] = 'false';	
		
		if(isset($_POST['rep_id']) && isset($_POST['method']) && $_POST['method'] == 'repetitive_event')
		{
			$event['rep_id'] = $_POST['rep_id'];	
		}
		
		if(isset($_POST['edit_categorie']))
		{
			$event['category'] = $_POST['edit_categorie'];
		} else {
			$event['category'] = '';	
		}
		
		if($event['start_time'] !== '00:00' || $event['end_time'] !== '00:00')
		{
			$event['allDay'] = 'false';	
		} else {
			$event['allDay'] = 'true';		
		}
		
		if(strtotime($event['end_date']) < strtotime($event['start_date']))
		{
			return false;	
		}
		
		if($calendar->updates($event) === true) {
			return true;	
		} else {
			return false;	
		}
	}

?>