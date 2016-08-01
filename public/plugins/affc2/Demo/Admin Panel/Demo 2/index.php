<?php 
	include('includes/loader.php'); 
	$_SESSION['token'] = time();
	include('tpl/head.php'); 
?>

<body>

  	<?php include('tpl/header.php'); ?>
    
    <!---------------------------------------------- CALENDAR MODALs ---------------------------------------------->
    
    <!-- Calendar Modal -->
    <div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          	<h4 id="details-body-title"></h4>
          </div>
          <div class="modal-body">
            
            <div class="loadingDiv"></div>
            
            <!-- Modal Details -->
            <div id="details-body">
                <div id="details-body-content"></div>
            </div>

          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal Delete Prompt -->
    <div id="cal_prompt" class="modal fade">
    	<div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
        	<a href="#" class="btn btn-danger" data-option="remove-this">Delete this</a>
            <a href="#" class="btn btn-danger" data-option="remove-repetitives">Delete all</a>
        	<a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
        </div>
        </div>
    </div>
    
    <!-- Modal Edit Prompt -->
    <div id="cal_edit_prompt_save" class="modal fade">
    	<div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body-custom"></div>
        <div class="modal-footer">
        	<a href="#" class="btn btn-info" data-option="save-this">Save this</a>
            <a href="#" class="btn btn-info" data-option="save-repetitives">Save all</a>
        	<a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
        </div>
        </div>
    </div>
    
    <input type="hidden" name="cal_token" id="cal_token" value="<?php echo $_SESSION['token']; ?>" />
    
    <!---------------------------------------------- THEME ---------------------------------------------->
    
	<div class="container">
		
      <a href="login.php" class="btn btn-default pull-right" style="margin-right: 10px;">Login</a>
       
      <div class="clearfix"></div>
      
      <!-- Filter by Category (required if you want to have categories filtering) -->
      <?php if($calendar->getCategories() !== false) { ?>
      <div id="cat-holder">
      <form id="filter-category">
          <select class="form-control input-sm" style="width: auto;">
          	<?php
				$selected = (isset($_SESSION['filter']) && $_SESSION['filter'] == 'all-fields' ? 'selected' : '');
				echo '<option '.$selected.' value="all-fields">All</option>';
				foreach($calendar->getCategories() as $categorie) 
				{
					$selectedLoop = (isset($_SESSION['filter']) && $_SESSION['filter'] == $categorie ? 'selected' : '');
					echo '<option '.$selectedLoop.' value="'.$categorie.'">'.$categorie.'</option>';	
				}
			?>
          </select>
      </form>
      </div>
      <?php } ?>
        
      <div class="box">
        <div class="header"><h4>Calendar</h4></div>
        <div class="content"> 
            <div id="calendar"></div>
        </div> 
    </div>

    </div> <!-- /container -->
    
    <?php include('tpl/scripts.php'); ?>
    
    <!-- call calendar plugin -->
    <script type="text/javascript">
		$().FullCalendarExt({
			calendarSelector: '#calendar',
			quickSave: false,
			editable: false,
			lang: 'en',
		});
	</script>
    
</body>
</html>