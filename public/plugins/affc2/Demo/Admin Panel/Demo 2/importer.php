<?php

	include('includes/loader.php');
	include('includes/ics.parser.class.php');
	
	error_reporting(0);
	
	if(strlen($_POST['import']) !== 0)
	{
		$file = 'includes/ics_template.ics';
		$save = file_put_contents($file, $_POST['import']);
		
		if($save)
		{
			$ical = new ical($file);
			
			$events = $ical->events();

			if(!empty($events))
			{ 
				$extra = array('repeat_method' => 'no', 'repeat_times' => 1);
				
				foreach($events as $ev)
				{
					$e_startD = date('Y-m-d', $ical->iCalDateToUnixTimestamp($ev['DTSTART']));
					$e_startT = date('H:i', $ical->iCalDateToUnixTimestamp($ev['DTSTART']));
					$e_endD = date('Y-m-d', $ical->iCalDateToUnixTimestamp($ev['DTEND']));
					$e_endT = date('H:i', $ical->iCalDateToUnixTimestamp($ev['DTEND']));
					$e_desc = $ev['DESCRIPTION'];
					$e_title = $ev['SUMMARY'];
					
					if(isset($ev['AFFC-COLOR'])) { $e_c = $ev['AFFC-COLOR']; } else { $e_c = '#587ca3'; }
					if(isset($ev['AFFC-ALLDAY'])) { $e_a = $ev['AFFC-ALLDAY']; } else { $e_a = 'false'; }
					if(isset($ev['AFFC-URL'])) { $e_u = $ev['AFFC-URL']; } else { $e_u = 'false'; }
					
					if(isset($ev['AFFC-UID'])) { $extra['user_id'] = $ev['AFFC-UID']; } else { $extra['user_id'] = 0; }
					if(isset($extra['categorie'])) { $extra['categorie'] = $ev['CATEGORIES'];} else { $extra['categorie'] = 'General'; }
					
					$calendar->addEvent($e_title, $e_desc, $e_startD, $e_startT, $e_endD, $e_endT, $e_c, $e_a, $e_u, $extra);
				}
				echo $ical->event_count.' Events were imported!';
			}
		}
	} else {
		echo 'Nothing to import';	
	}
	
?>