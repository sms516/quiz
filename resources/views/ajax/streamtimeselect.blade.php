<div class="name-field">
<label>Training Date
<input type="text" class="form-control" id="training_date" name="training_date" value="{{isset($date)?date('l, j F Y',strtotime($date)):''}}">
<input type="hidden" class="form-control" id="training_date_alt" name="training_date_alt" value="{{isset($date)?$date:''}}" >
</label>
<script>
  $(function() {
    $( "#training_date" ).datepicker(
	{
		beforeShowDay: function(date) {
        var day = date.getDay();
        return [(day == {{implode(' || day == ',$days)}})];
    	},
		dateFormat:'DD, d MM yy',
		altField: "#training_date_alt",
      	altFormat: "yy-mm-dd",
		minDate:"-1Y",
		maxDate:"+14D",onSelect: function (dateText, inst) {
         /*loadClassList($('#training_date_alt').val(),'{{strtolower($club->club_short_name)}}');*/
		 loadTrainingTimes($('#training_date_alt').val(),'{{strtolower($club->club_short_name)}}');
      }
	});
  });
  </script>
</div>