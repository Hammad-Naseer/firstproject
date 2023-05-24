<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
	    <div class="panel-title black2">
			<i class="entypo-plus-circled"></i>    
            <?php echo get_phrase('add_student_leaves');?>
		</div>
	</div>
	<div class="panel-body">
        <?php echo form_open(base_url().'leave/manage_leaves_student/create' , array('id'=>'student_leaves_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
           <div class="form-group">
                <label class="control-label"><?php echo get_phrase('class');?> / <?php echo get_phrase('section');?>
			    <span class="red"> * </span></label>
                <label id="section_id_add_selection"></label>
                <select id="section_id_add" class="selectpicker form-control" name="section_id_add" data-validate="required" data-message-required="Value Required">               
                    <?php echo section_selector();?>
                </select>
 				<div id="section-err"></div>
 			</div>  
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('student_name');?><span class="red"> * </span></label>
                <select id="student_id_add" name="student_id_add" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 				    <option value=""><?php echo get_phrase('select_student');?></option>
 				</select>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('leave_type');?><span class="red"> * </span></label>
                <select id="leave_category_id" name="leave_category_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <?php echo get_leave_category(); ?>  
 				</select>
            </div>
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('description');?></label>
                <textarea class="form-control" name="reason" id="reason" class="form-control" maxlength="1000" oninput="count_value('reason','area_count','1000')"></textarea>
                <div id="area_count" class="col-sm-12 "></div> 
            </div>         
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('start_date');?><span class="red"> * </span></label>   
                <input type="date" name="start_date" id="start_date" class="form-control" data-validate="required" data-format="dd/mm/yyyy" data-message-required="<?php echo get_phrase('value_required');?>"/>
                <div id="error_start"></div> 
            </div>      
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('end_date');?><span class="red"> * </span></label>   
                <input type="date" name="end_date" id="end_date" data-format="dd/mm/yyyy" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                <div id="error_end"></div>
            </div>            
            <div class="form-group">
			    <label for="field-1" class="control-label"><?php echo get_phrase('attachment');?></label>
				<input type="file" class="form-control" name="userfile" id="userfile" onchange="file_validate('userfile','doc','img_f_msg')"  value="">	
                <span style="color: green;"> <?php echo get_phrase('allowed_file_size');?> :  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                <br />
                <span style="color: red;" id="img_f_msg"></span>								
			</div>
            <div class="form-group">
                <div class="float-right">
					<button type="submit" id="btn_add" class="modal_save_btn">
						<?php echo get_phrase('save');?>
					</button>
					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
						<?php echo get_phrase('cancel');?>
					</button>
				</div>
			</div> 
        <?php echo form_close();?>	
    </div>
    
<script>
$(document).ready(function(){
	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });

$("#departments_id_add").change(function(){
	var dep_id=$(this).val();
	$("#icon").remove();
	$(this).after('<div id="icon" class="loader_small"></div>');
	$.ajax({
			type: 'POST',
			data: {department_id:dep_id},
	        url: "<?php echo base_url();?>?leave/get_class",
			dataType: "html",
			success: function(response) {			
			$("#icon").remove();	
			$("#class_id_add").html(response);
			$("#section_id_add").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>'); 
		}
	});
});	

$("#class_id_add").change(function(){
	var class_id=$(this).val();
	$("#icon").remove();
	$(this).after('<div id="icon" class="loader_small"></div>');
	$.ajax({
			type: 'POST',
			data: {class_id:class_id},
	url: "<?php echo base_url();?>leave/get_class_section",
			dataType: "html",
			success: function(response) {
				$("#icon").remove();
			$("#section_id_add").html(response);
			 }
		});
	
	
	
});	

$("#section_id_add").change(function(){
	var section_id=$(this).val();
	
	$("#icon").remove();
	$(this).after('<div id="icon" class="loader_small"></div>');
	
	$.ajax({
			type: 'POST',
			data: {section_id:section_id},
	url: "<?php echo base_url();?>leave/get_section_student",
			dataType: "html",
			success: function(response) {
				$("#icon").remove();
			
			$("#student_id_add").html(response);
			
				
				 }
		});
	
	
	
});	
});	


	$('#start_date').on('change',function(){
			$('#btn_add').removeAttr('disabled','true');
			$('#error_start').text('');
			var start_date=$(this).val();
			var term_id=$('#yearly_id_add').val();
			if(start_date!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>leave/term_date_range",

					data: ({start_date:start_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_start').text('<?php echo get_phrase('start_date_should_be_between_term_dates'); ?>');
						 $('#btn_add').attr('disabled','true');			
						}					
					}
				});
				}
		
	});

    $('#end_date').on('change',function(){
		$('#btn_add').removeAttr('disabled','true');
		$('#error_end').text('');
		var end_date=$(this).val();
		var term_id=$('#yearly_id_add').val();
		if(end_date!='')
		{
		    $.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>leave/term_date_range",
				data: ({end_date:end_date,term_id:term_id}),
				dataType : "html",
				success: function(html) {
					if(html==0){
					 $('#error_end').text('<?php echo get_phrase('end_date_should_be_between_term_dates'); ?>');		
					 $('#btn_add').attr('disabled','true');	
					}					
				}
			});
	    }
    });

    $("#end_date").change(function () {
    	$('#btn_add').removeAttr('disabled','true');
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            $('#error_end').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date'); ?>");
            $('#btn_add').attr('disabled','true');	
        }
    });

    $("#start_date").change(function () {
    	$('#btn_add').removeAttr('disabled','true');
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;
     
        if ((Date.parse(endDate) < Date.parse(startDate))){
             $('#error_start').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
           $('#btn_add').attr('disabled','true');	
        }
    });
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>




