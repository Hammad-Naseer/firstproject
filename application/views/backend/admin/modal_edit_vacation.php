<?php 
    $query="select * from ".get_school_db().".holiday WHERE school_id=".$_SESSION['school_id']." and holiday_id=".$param2."";
    $edit_data=$this->db->query($query)->result_array();
?>
    <div class="panel panel-primary" data-collapsed="0">
        <div class="panel-heading">
        	<div class="panel-title black2">
        		<i class="entypo-plus-circled"></i><?php echo get_phrase('edit_vacation');?>
            </div>
        </div>
        <div class="panel-body">
            <?php foreach($edit_data as $row):?>
            <?php echo form_open(base_url().'vacation/add_vacation' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>     
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('title');?><span class="star">*</span></label>
                    <input maxlength="100" class="form-control" type="text" id="val0" value="<?php echo $row['title'];  ?>" name="vacation-name" required/>
                    <input value="<?php echo $row['holiday_id'];  ?>" type="hidden" id="holiday_id" name="holiday_id"/>
                </div>
                <div class="form-group">
                   <label class="control-label"><?php echo get_phrase('start_date');?>
                   	<span class="star">*</span></label>
                    <input class="form-control readonly" value="<?php echo $row['start_date']; ?>"  data-format="dd/mm/yyyy" id="start_date1" type="date" name="start-date" required/>
                    <div id="error_start"></div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('end_date');?>
                   	<span class="star">*</span></label>
                    <input id="end_date1" class="form-control readonly" value="<?php echo $row['end_date'];?>" data-format="dd/mm/yyyy"  type="date" name="end-date" required/>
                    <div id="error_end"></div>
                </div>
                <div class="form-group" <?= check_sms_preference(15,"style","sms") ?>>
					<label class="control-label"><?php echo get_phrase('send_sms');?><span class="star">*</span></label>
				    <input id="" class="" type="checkbox" name="send_message" />
					<div id="error_end1"></div>
				</div>
             	<div class="form-group" <?= check_sms_preference(15,"style","email") ?>>
					<label class="control-label"><?php echo get_phrase('send_email');?><span class="star">*</span></label>
					<input id="" class=""  type="checkbox" name="send_email" />
					<div id="error_end1"></div>
				</div>
            	<span style="color:red;">
                	<?php
                	    if($row['sms_status']==1){
    				        echo "<strong>Note : </strong>SMS has already been sent";
                	    }
    			
    			    ?>
			    </span>	 
                <div class="form-group">
                    <div class="float-right"> 
                        <button type="submit" id="btnn_edit" class="modal_save_btn"><?php echo get_phrase('update');?></button>
                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                            <?php echo get_phrase('cancel');?>
                        </button>
                    </div>
                </div>
            </form>
            <?php endforeach;?>
        </div>
    </div>

<script>
$(document).ready(function(){
	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });

    $('#academic_id1').on('change',function(){
		var academic_year=$(this).val();
		if(academic_year=='')
		{
			$('#term_id1').html('<select><option><?php echo get_phrase('select_term'); ?></option></select>');
		}
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>vacation/get_terms",
			data: ({academic_year:academic_year,status:1}),
			dataType : "html",
			success: function(html) {
				console.log(html);
				if(html !=''){
					$('#term_id1').html(html);
			    }
		    }
		});
		
	});	
	
	$('#start_date1 , #yearly_terms2 ').on('change',function(){
			$('#btnn_edit').removeAttr('disabled','true');
			var start_date=$(this).val();
			var term_id=$('#yearly_terms2').val();
			if(start_date!='')
			{
	    		$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>exams/term_date_range",
					data: ({start_date:start_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_start').text('<?php echo get_phrase('');?><?php echo get_phrase('start_date_should_be_between_term_dates');?>');
						 $('#btnn_edit').attr('disabled','true');			
						}					
					}
				});
			}
	});
	
	$('#end_date1 , #yearly_terms2').on('change',function(){				
		$('#btnn_edit').removeAttr('disabled','true');
			 $('#error_end').text('');
			var end_date=$(this).val();
			var term_id=$('#yearly_terms2').val();
			if(end_date!='')
			{
			    $.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>exams/term_date_range",

					data: ({end_date:end_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end').text('<?php echo get_phrase('end_date_should_be_between_term_dates');?>');		
						 $('#btnn_edit').attr('disabled','true');	
						}					
					}
				});
		    }
	});
	$("#end_date1").change(function () {
    	$('#btnn_edit').removeAttr('disabled','true');
        var startDate = document.getElementById("start_date1").value;
        var endDate = document.getElementById("end_date1").value;
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            $('#error_end').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>");
            $('#btnn_edit').attr('disabled','true');	
        }
    });
    $("#start_date1").change(function () {
	    $('#btnn_edit').removeAttr('disabled','true');
        var startDate = document.getElementById("start_date1").value;
        var endDate = document.getElementById("end_date1").value;
        if ((Date.parse(endDate) < Date.parse(startDate))){
            $('#error_start').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
            $('#btnn_edit').attr('disabled','true');	
        }
    });

    $("#yearly_terms2").change(function(){
        var date1=$("#start_date1").val();
        var date2=$("#end_date1").val();
        var term_id=$(this).val();
		if(date1!='')
		{
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>vacation/term_date_range",
				data: ({date1:date1,term_id:term_id}),
				dataType : "html",
				success: function(html) {
				    if(html==0){
						 $('#error_start').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');		
						 $('#btnn_edit').attr('disabled','true');	
					}else{
				        $('#btnn_edit').removeAttr('disabled');
					    $('#error_end').text('');		
					}					
				}
			});
		}
		if(date2!='')
		{
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>vacation/term_date_range",
				data: ({date2:date2,term_id:term_id}),
				dataType : "html",
				success: function(html) {
					if(html==0){
						 $('#error_start').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');		
						 $('#btnn_edit').attr('disabled','true');	
					}else{
						 $('#btnn_edit').removeAttr('disabled');
						 $('#error_end').text('');		
					}					
				}
			});
		}
});		
	
});
</script>