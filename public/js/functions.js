var base_dir='http://localhost/quiz/public/ajax/';
function getTrainingTimeSelect(el,clubName)
{
	var stream_id=jQuery(el).val();
	$.ajax({
			   type: "GET",
			   url: base_dir+clubName+'/streamTime/'+stream_id,
			   success: function(msg){
				jQuery("#training_time_wrapper").html(msg);
			   }
			 });
}
function loadClassList(el,id,day,clubName)
{
	$("#club_training_time_id").val(id);
	$('.classStream').removeClass('alert-box success');
	if(el!=null)$(el).addClass('alert-box success');
	var stream_id=jQuery('#club_stream_id').val();
	$.ajax({
			   type: "GET",
			   url: base_dir+clubName+'/attendance/'+stream_id+'/'+day+'/'+id,
			   success: function(msg){
				jQuery("#club_member_wraper").html(msg);
				if(msg.length>0)
				{
					$("#saveAttendance").removeClass('hide');
				}
				else $("#saveAttendance").addClass('hide');
			   }
			 });
}
function loadTrainingTimes(day,clubName)
{
	var stream_id=jQuery('#club_stream_id').val();
	$.ajax({
			   type: "GET",
			   url: base_dir+clubName+'/attendance/'+stream_id+'/'+day,
			   success: function(msg){
				jQuery("#training_session_wrapper").html(msg);
			   }
			 });
}
function searchStudent(clubName)
{
	var searchStr=jQuery("#studentSearch").val();	
	var stream_id=jQuery("#club_stream_id").val();
	var trainTime=$("#club_training_time_id").val();
	var token=jQuery("#token").val();	
	$.ajax({
	   type: "POST",
	   url: base_dir+clubName+'/attendance/self/search',
	   data:'searchStr='+searchStr+'&club_stream_id='+stream_id+'&club_training_time_id='+trainTime+'&_token='+token,
	   success: function(msg){
		$("#searchResult").html(msg);
	   }
	 });
}
function selectStudent(clubName,searchStr)
{
	var stream_id=jQuery("#club_stream_id").val();
	var token=jQuery("#token").val();	
	$.ajax({
	   type: "POST",
	   url: base_dir+clubName+'/attendance/self/search',
	   data:'searchStr='+searchStr+'&club_stream_id='+stream_id+'&_token='+token,
	   success: function(msg){
		$("#searchResult").html(msg);
	   }
	 });
}
function selectStream(el,stream,train)
{
	$("#club_stream_id").val(stream);
	$("#club_training_time_id").val(train);
	$('.classStream').removeClass('alert-box success');
	$(el).addClass('alert-box success');
	$("#searchResult").html('');
	jQuery("#studentSearch").val('');
}
var clock;
function startQuiz()
{
	sessionStorage.clear();
	score=0;
	jQuery("#scoreSummary").html('');
	jQuery('#questionReview').html('');
	var rndString=randomString(60,'#aA');
	sessionStorage.setItem('quizID',rndString);
	var token=jQuery("#token").val();	
	var minRank=jQuery('#rank_min_id').val();
	var maxRank=jQuery('#rank_max_id').val();
	/*minRank=1;
	maxRank=3;*/
	var compulsary=jQuery('#compulsary').val();
	var showLevel=jQuery('#q_level').val();
	var testType=jQuery("#testType").val();
	$.ajax({
	   type: "POST",
	   url: base_dir+'quiz/getQuestions',
	   data:'minRank='+minRank+'&maxRank='+maxRank+'&compulsary='+compulsary+'&showLevel='+showLevel+'&testType='+testType+'&_token='+token,
	   success: function(msg)
	   {
			var qArray=msg;
			var questions=JSON.parse(qArray);
			jQuery.each(questions, function(index, question) {
				//console.log(question.question);
			   sessionStorage.setItem(index,JSON.stringify(question));
		   	});
			sessionStorage.setItem('totalQuestions',questions.length);
			sessionStorage.setItem('correct',0);
			sessionStorage.setItem('answered',0);
			 getNextQuestion();
			if(testType=='2min')
			{
				clock = $('#clock').FlipClock(120, {
					clockFace: 'MinuteCounter',
					countdown: true,
					callbacks: {
							stop: function () {
								if(clock.getTime().time==0)completeQuiz();
							}
						}
				});
				jQuery("#clock").removeClass('hide');
			}
			else
			{
				if(clock!=undefined)
				{
					clock.stop();	
					jQuery("#clock").addClass('hide');
				}
			}
			 jQuery("#quizContent").removeClass('hide');
			 jQuery('#quizOptions').addClass('hide');
			 jQuery('#hideButton').removeClass('hide');
			 jQuery('#optionsButton').removeClass('hide');
	   }
	 });
	
}
var startTime;
var endTime;
var score=0;
function getNextQuestion()
{
	
	var askedArray=JSON.parse(sessionStorage.getItem('asked'));
	var maxID=sessionStorage.getItem('totalQuestions');
	var answered=parseInt(sessionStorage.getItem('answered'));
	var testType=jQuery("#testType").val();
	if(testType !='endless' && maxID==answered)
	{
		completeQuiz();
		
	}
	else
	{
		if(askedArray)
		{
			if(testType=='endless' && askedArray.length==maxID)
			{
				askedArray=[];	
			}
			if(askedArray.length<maxID)
			{
				var rndID=getRandomNumber(maxID,askedArray);
				askedArray.push(rndID);
				sessionStorage.setItem('asked',JSON.stringify(askedArray));
			}
			
		}
		else 
		{
			var rndID=getRandomNumber(maxID,askedArray);
			var asked=[rndID];
			sessionStorage.setItem('asked',JSON.stringify(asked));
		}
		var q=JSON.parse(sessionStorage.getItem(rndID));
		if(answered>0)
		{
			getAvgLvlImg(Math.round(parseInt(sessionStorage.getItem('avgLvl'))/answered));
		}
		var avgLvl=sessionStorage.getItem('avgLvl');
		/*if(avgLvl)
		{
			sessionStorage.setItem('avgLvl',parseInt(avgLvl)+parseInt(q.rank_num));
		}
		else
		{
			sessionStorage.setItem('avgLvl',parseInt(q.rank_num));	
		}*/
	
		 jQuery('#question h3').html(q.question);
		 if(jQuery("#q_level").val()!=0)
		 {
		 	jQuery('#qLevel').html('<div><strong>Question Level:</strong></div><img src="'+q.rank_img+'" />');
		 }
		 else
		 {
			 jQuery('#qLevel').html('');
		 }
		 var qOptions;
		 if(q.option4!=null && q.option4!='')
		 {
			 qOptions=[q.answer,q.option1,q.option2,q.option3,q.option4];
		 }
		 else if(q.option3!=null && q.option3!='')
		 {
			 qOptions=[q.answer,q.option1,q.option2,q.option3];
		 }
		 else if(q.option2!=null && q.option2!='')
		 {
			 qOptions=[q.answer,q.option1,q.option2];
		 }
		 else qOptions=[q.answer,q.option1];
		 shuffle(qOptions);
		 var optionhtml='<div class="row">';
		 for(i=0;i<qOptions.length;i++)
		 {
			 optionhtml+='<div class="small-12">';
			optionhtml+='<button class="button expand secondary" id="option'+i+'" value="'+qOptions[i]+'" onclick="checkAnswer('+rndID+',this)">'+qOptions[i]+'</button>';
			optionhtml+='</div>';
		 }
		 optionhtml+='</div>';
		 jQuery('#options').html(optionhtml);
		 var d =new Date();
		 startTime=d.getTime();
	}
}
function getRandomNumber(maxID,asked)
{
	var rndID=Math.floor(Math.random() * maxID);
	if(asked!=null && asked.indexOf(rndID)!=-1)
	{
		return getRandomNumber(maxID,asked);
	}
	return rndID;
}
function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex ;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}
function checkAnswer(qID,el)
{
	var q=JSON.parse(sessionStorage.getItem(qID));
	recordAnswer(q.quiz_question_id,el);
	var d =new Date();
	endTime=d.getTime();
	//console.log(parseInt(endTime)-parseInt(startTime));
	var tDiff=(parseInt(endTime)-parseInt(startTime));
	if(tDiff<250)timeToAnswer=1;
	else if(tDiff<1000)timeToAnswer=200;
	else if(tDiff<2000)timeToAnswer=100;
	else if(tDiff<3000)timeToAnswer=50;
	else timeToAnswer=10;
	var selected=jQuery(el).val();
	var result='';
	var avgLvl=sessionStorage.getItem('avgLvl');
	if(avgLvl)
	{
		sessionStorage.setItem('avgLvl',parseInt(avgLvl)+parseInt(q.rank_num));
	}
	else
	{
		sessionStorage.setItem('avgLvl',parseInt(q.rank_num));	
	}
	sessionStorage.setItem('answered',(parseInt(sessionStorage.getItem('answered'))+1));
	if(selected==q.answer)
	{
		result+='<div class="alert-box success radius">';
		result+="'"+selected+"' is Correct";
		sessionStorage.setItem('correct',(parseInt(sessionStorage.getItem('correct'))+1));
		var multiplier= 11-parseInt(q.rank_num);
		if(multiplier<1)multiplier=1;
		score=Math.round(parseInt(score)+((parseInt(timeToAnswer))*multiplier));
	}
	else
	{
		result+='<div class="alert-box alert radius">';
		result+="'"+selected+"' is Incorrect. The correct answer was '"+q.answer+"'";
	}
	result+='<br>You have answered '+sessionStorage.getItem('correct')+' out of '+sessionStorage.getItem('answered')+' correctly.<br>Your score is '+(Math.round(parseInt(score)*(sessionStorage.getItem('correct')/sessionStorage.getItem('answered'))))+'.<br>You have answered '+
	((sessionStorage.getItem('correct')/sessionStorage.getItem('answered'))*100).toFixed(2)+'% of the questions correctly';
	result+='</div>';
	jQuery("#prevResult").html(result);
	getNextQuestion()
}
function getAvgLvlImg(lvl)
{
	var token=jQuery("#token").val();	
	$.ajax({
	   type: "POST",
	   url: base_dir+'quiz/avgLvl',
	   data:'lvl='+lvl,
	   success: function(msg){
			  jQuery('#averageLevel').html('<div>Average Question Level:</div><img src="'+msg+'" />');
	   }
	 });
}
function recordAnswer(qID,el)
{
	var selected=jQuery(el).val();
	var token=jQuery("#token").val();
	console.log(token);
	var quizID=sessionStorage.getItem('quizID')	
	$.ajax({
	   type: "POST",
	   url: base_dir+'quiz/recordAnswer',
	   data:'quiz_id='+quizID+'&question_id='+qID+'&selectedAnswer='+selected,
	   success: function(msg){
			  
	   }
	 });
}
function randomString(length, chars) {
    var mask = '';
    if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
    if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (chars.indexOf('#') > -1) mask += '0123456789';
    if (chars.indexOf('!') > -1) mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
    var result = '';
    for (var i = length; i > 0; --i) result += mask[Math.floor(Math.random() * mask.length)];
    return result;
}
function questionReview()
{
	var token=jQuery("#token").val();
	var quizID=sessionStorage.getItem('quizID')	
	$.ajax({
	   type: "POST",
	   url: base_dir+'quiz/questionReview',
	   data:'quiz_id='+quizID,
	   success: function(msg){
			jQuery('#questionReview').html(msg);
	   }
	 });
}
function checkHS()
{
	var finalScore=(Math.round(parseInt(score)*(sessionStorage.getItem('correct')/sessionStorage.getItem('answered'))));
	var token=jQuery("#token").val();
	var testType=jQuery("#testType").val();
	var quizID=sessionStorage.getItem('quizID')	
	var avgLvl=Math.round(sessionStorage.getItem('avgLvl')/sessionStorage.getItem('answered'));
	$.ajax({
	   type: "POST",
	   url: base_dir+'quiz/checkHS',
	   data:'average_rank_num='+avgLvl+'&score='+finalScore+'&testType='+testType,
	   success: function(msg){
			jQuery("#hsModalContent").html(msg);
			$('#hsModal').foundation('reveal', 'open');
	   }
	 });
}
function saveHS()
{
	var finalScore=(Math.round(parseInt(score)*(sessionStorage.getItem('correct')/sessionStorage.getItem('answered'))));
	var token=jQuery("#token").val();
	var testType=jQuery("#testType").val();
	var avgLvl=Math.round(sessionStorage.getItem('avgLvl')/sessionStorage.getItem('answered'));
	var hsName=jQuery("#hs_name").val();
	$.ajax({
	   type: "POST",
	   url: base_dir+'quiz/saveHS',
	   data:'hs_name='+hsName+'&average_rank_num='+avgLvl+'&score='+finalScore+'&test_type='+testType+
	   '&number_correct='+sessionStorage.getItem('correct')+'&test_total='+sessionStorage.getItem('answered'),
	   success: function(msg){
			jQuery("#hsModalContent").html(msg);
	   }
	 });
}
function completeQuiz()
{
jQuery("#quizContent").addClass('hide');
jQuery("#question h3").html('');
jQuery("#options").html('');
questionReview();
checkHS();
console.log('pre clock check');
if(clock!=undefined)
{
	clock.stop();	
	jQuery("#clock").addClass('hide');
}
console.log('post clock check');
		var msg='<div class="alert-box success radius"><h4>Quiz Complete</h4>';
		var finalscore=(Math.round(parseInt(score)*(sessionStorage.getItem('correct')/sessionStorage.getItem('answered'))));
		var finalpct=((sessionStorage.getItem('correct')/sessionStorage.getItem('answered'))*100).toFixed(2);
		msg+='<div>You have answered '+sessionStorage.getItem('correct')+' out of '+sessionStorage.getItem('answered')+' correctly.</div><div>Your score is '+finalscore+'.</div><div>You have answered '+finalpct+'% of the questions correctly';
	msg+='</div></div>';
	jQuery("#scoreSummary").html(msg);	
}
function loadMonth(direction,currentDate,club)
{
	var token=jQuery("#token").val();
	$.ajax({
	   type: "POST",
	   url: base_dir+club+'/payment',
	   data:'direction='+direction+'&currentDate='+currentDate+'&_token='+token,
	   success: function(msg){
			jQuery("#monthPayments").html(msg);
	   }
	 });
}
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}
function setPayment(user_id,amount,el)
{
	$("#on-off").prop("checked",false);
	$("#payment_user_id").val(user_id);
	$("#paymentAmount").val(amount);
	if($("#paymentDate"+user_id).val()!='No')
	{
		var payDate=$("#paymentDate"+user_id).val();
		$("#modalPaymentDate").val(formatDate(payDate));
		$("#modalPaymentDateAlt").val(payDate);
		$("#on-off").prop("checked",true);
	}
}
function setFamilyPayment(family_id,amount,el)
{
	$("#on-off").prop("checked",false);
	$("#payment_user_id").val('');
	$("#payment_family_id").val(family_id);
	$("#paymentAmount").val(amount);
	if($("#paymentFamilyDate"+family_id).val()!='No')
	{
		var payDate=$("#paymentFamilyDate"+family_id).val();
		$("#modalPaymentDate").val(formatDate(payDate));
		$("#modalPaymentDateAlt").val(payDate);
		$("#on-off").prop("checked",true);
	}
}
function savePayment()
{
	var token=jQuery("#token").val();
	var user=jQuery("#payment_user_id").val();
	var family=jQuery("#payment_family_id").val();
	var club=jQuery("#paymentClub").val();
	var paymentMethod=jQuery("#paymentMethod").val();
	var paymentMonth=jQuery("#paymentMonth").val();
	var paymentAmount=jQuery("#paymentAmount").val();
	var paymentReceived=$("#modalPaymentDateAlt").val();
	var action='del';
	if($('#on-off:checked').length>0)action='save'
	$.ajax({
	   type: "POST",
	   url: base_dir+club+'/savePayment',
	   data:'user_id='+user
	   +'&family_id='+family
	   +'&payment_method='+paymentMethod
	   +'&payment_month='+paymentMonth
	   +'&amount='+paymentAmount
	   +'&date_received='+paymentReceived
	   +'&action='+action
	   +'&_token='+token,
	   success: function(msg){
			if($('#on-off:checked').length>0) {
				if(user!='')
				{
					$("#paymentWrapper"+$("#payment_user_id").val()).html(paymentReceived);
					$("#paymentDate"+$("#payment_user_id").val()).val(paymentReceived);
					$("#paymentWrapper"+$("#payment_user_id").val()).parent().removeClass('alert');
					$("#paymentWrapper"+$("#payment_user_id").val()).parent().addClass('success');
				}
				else
				{
					$("#paymentFamilyWrapper"+$("#payment_family_id").val()).html(paymentReceived);
					$("#paymentFamilyDate"+$("#payment_family_id").val()).val(paymentReceived);
					$("#paymentFamilyWrapper"+$("#payment_family_id").val()).parent().removeClass('alert');
					$("#paymentFamilyWrapper"+$("#payment_family_id").val()).parent().addClass('success');
				}
			} else {
				if(user!='')
				{
					$("#paymentWrapper"+$("#payment_user_id").val()).html('No');
					$("#paymentDate"+$("#payment_user_id").val()).val('No');
					$("#paymentWrapper"+$("#payment_user_id").val()).parent().removeClass('success');
					$("#paymentWrapper"+$("#payment_user_id").val()).parent().addClass('alert');
				}
				else
				{
					$("#paymentFamilyWrapper"+$("#payment_family_id").val()).html('No');
					$("#paymentFamilyDate"+$("#payment_family_id").val()).val('No');
					$("#paymentFamilyWrapper"+$("#payment_family_id").val()).parent().removeClass('success');
					$("#paymentFamilyWrapper"+$("#payment_family_id").val()).parent().addClass('alert');
				}
			}	
			$('#myModal').foundation('reveal', 'close');
	   }
	 });
}
function paymentSearch(clubName)
{
	var paymentSearch=jQuery("#paymentSearch").val();
	$("#paymentName").html('');
	if(paymentSearch=='')
	{
		$("#searchResult").html('');	
	}
	else
	{
		$("#paymentInfo").addClass('hide');
		var token=jQuery("#token").val();	
		$.ajax({
		   type: "POST",
		   url: base_dir+clubName+'/payment/search',
		   data:'searchStr='+paymentSearch+'&_token='+token,
		   success: function(msg){
			$("#searchResult").html(msg);
		   }
		 });
	}
}
function familyPaymentSelect(family_id,el)
{
	$("#oneoff_family_id").val(family_id);
	$(".paymentInfo").removeClass('hide');
	var name=$(el).children('.family_name').html();
	$("#paymentName").html('<h4>Record Payment for '+name+' family</h4>');
	$("#searchResult").html('');
}
function paymentSelect(user_id,el)
{
	$("#oneoff_user_id").val(user_id);
	$(".paymentInfo").removeClass('hide');
	var name=$(el).children('.f_name').html()+' '+$(el).children('.l_name').html();
	$("#paymentName").html('<h4>Record payment for '+name+'</h4>');
	$("#searchResult").html('');
}
function removeSelection(elementID)
{
	$('#'+elementID+' option[selected="selected"]').each(
    function() {
        $(this).removeAttr('selected');
    }
);
// mark the first option as selected
$('#'+elementID+' option:first').attr('selected','selected');
}
function addSession(syllabus_id)
{
	$("#modalTitle").html('Add Session');
	$("#syllabus_id").val(syllabus_id);
	$("#deleteButton").addClass('hide');
	$("#updateButton").addClass('hide');
	$("#saveButton").removeClass('hide');
	removeSelection('instructor_id');
	removeSelection('min_rank_id');
	removeSelection('max_rank_id');
	removeSelection('min_age');
	removeSelection('max_age');
	removeSelection('start_hour');
	removeSelection('start_min');
	removeSelection('end_hour');
	removeSelection('end_min');
	$("#training_notes").val('');
	$("#syllabus_id").val('');
	$("#training_id").val('');	
	$('#calendarModal').foundation('reveal', 'open');	
	$('#calendarModal').foundation('reveal', 'open');
}
function saveSession(clubName,club_id)
{
	$("#saveButton").attr('disabled', 'disabled');
	var token=jQuery("#token").val();
	var sessionDate=$("#calendar").fullCalendar( 'getDate' );
	var club_stream_id=$("#club_stream").val();
	var instructor_id=$("#instructor_id").val();
	var syllabus_id=$("#syllabus_id").val();
	var min_rank_id=$("#min_rank_id").val();
	var max_rank_id=$("#max_rank_id").val();
	var min_age=$("#min_age").val();
	var max_age=$("#max_age").val();
	var training_notes=$("#training_notes").val();
	var start_time=sessionDate.format('YYYY-MM-DD')+' '+$("#start_hour").val()+':'+$("#start_min").val();
	var end_time=sessionDate.format('YYYY-MM-DD')+' '+$("#end_hour").val()+':'+$("#end_min").val();
	$.ajax({
	   type: "POST",
	   url: base_dir+clubName+'/training/save',
	   data:{
				_token:token,
				club_id:club_id,
				club_stream_id:club_stream_id,
				instructor_id:instructor_id,
				syllabus_id:syllabus_id,
				min_rank_id:min_rank_id,
				max_rank_id:max_rank_id,
				min_age:min_age,
				max_age:max_age,
				training_notes:training_notes,
				start_time:start_time,
				end_time:end_time
			},
	   success: function(msg){
		
		 $("#saveButton").removeAttr('disabled');
		 $('#calendarModal').foundation('reveal', 'close');
		 $('#calendar').fullCalendar('refetchEvents');
	   }
	 });
}
function saveDroppedEvent(event,clubName,club_id)
{
	var token=jQuery("#token").val();
	var sessionDate=$("#calendar").fullCalendar( 'getDate' );
	var club_stream_id=$("#club_stream").val();
	var syllabus_id=event.syllabus_id;
	var start_time=event.start.format('YYYY-MM-DD HH:mm:ss');
	var end_time=event.end.format('YYYY-MM-DD HH:mm:ss');
	$.ajax({
	   type: "POST",
	   url: base_dir+clubName+'/training/save',
	   data:{
				_token:token,
				club_id:club_id,
				club_stream_id:club_stream_id,
				syllabus_id:syllabus_id,
				start_time:start_time,
				end_time:end_time
			},
	   success: function(msg){
		
		 $("#saveButton").removeAttr('disabled');
		 $('#calendarModal').foundation('reveal', 'close');
		 $('#calendar').fullCalendar('refetchEvents');
	   }
	 });
}
function updateSession(clubName,club_id)
{
	$("#updateButton").attr('disabled', 'disabled');
	var token=jQuery("#token").val();
	var training_id=$("#training_id").val();
	var sessionDate=$("#calendar").fullCalendar( 'getDate' );
	var club_stream_id=$("#club_stream").val();
	var instructor_id=$("#instructor_id").val();
	var syllabus_id=$("#syllabus_id").val();
	var min_rank_id=$("#min_rank_id").val();
	var max_rank_id=$("#max_rank_id").val();
	var min_age=$("#min_age").val();
	var max_age=$("#max_age").val();
	var training_notes=$("#training_notes").val();
	var start_time=sessionDate.format('YYYY-MM-DD')+' '+$("#start_hour").val()+':'+$("#start_min").val();
	var end_time=sessionDate.format('YYYY-MM-DD')+' '+$("#end_hour").val()+':'+$("#end_min").val();
	$.ajax({
	   type: "POST",
	   url: base_dir+clubName+'/training/update',
	   data:{
				_token:token,
				training_id:training_id,
				club_id:club_id,
				club_stream_id:club_stream_id,
				instructor_id:instructor_id,
				syllabus_id:syllabus_id,
				min_rank_id:min_rank_id,
				max_rank_id:max_rank_id,
				min_age:min_age,
				max_age:max_age,
				training_notes:training_notes,
				start_time:start_time,
				end_time:end_time
			},
	   success: function(msg){
		
		 $("#updateButton").removeAttr('disabled');
		 $('#calendarModal').foundation('reveal', 'close');
		 $('#calendar').fullCalendar('refetchEvents');
	   }
	 });
}
function deleteSession(clubName,club_id)
{
	$.ajax({
		type: "POST",
		url: base_dir+clubName+'/training/delete',
		data:{
				_token:token,
				training_id:$("#training_id").val()
			},
		success: function(msg){
		 $('#calendarModal').foundation('reveal', 'close');
		 $('#calendar').fullCalendar('refetchEvents');
		}
	});	
}
function updateTimes(event,clubName)
{
	$.ajax({
		type: "POST",
		url: base_dir+clubName+'/training/updatetimes',
		data:{
				_token:token,
				training_id:event.training_id,
				start_time:event.start.format('YYYY-MM-DD HH:mm:ss'),
				end_time:event.end.format('YYYY-MM-DD HH:mm:ss')
			},
		success: function(msg){
		 //$('#calendar').fullCalendar('refetchEvents');
		}
	});	
}