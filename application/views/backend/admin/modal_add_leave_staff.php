
<div class="panel panel-primary" data-collapsed="0">
     <div class="panel-heading">
        <div class="panel-title black2">
        	<i class="entypo-plus-circled"></i>
            <?php echo get_phrase('add_staff_leaves');?>
        </div>
    </div>
    <div class="panel-body">
                	<?php echo form_open(base_url().'leave_staff/manage_leaves_staff/create' , array('id'=>'manage_leaves_staff_create_form','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('staff_name');?><span class="star">*</span></label>
                            <select id="staff_id_add" name="staff_id_add" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 							<?php echo staff_list();?>
 							</select>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('leave_type');?><span class="star">*</span></label>
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
                            <label for="field-2" class="control-label"><?php echo get_phrase('start_date');?><span class="star">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-format="dd/mm/yyyy" />
                            <div id="error_start" class=" col-sm-12eeeeee"></div>
                        </div>   
                        <div class="form-group">
                            <label for="field-2" class="control-label"><?php echo get_phrase('end_date');?><span class="star">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-format="dd/mm/yyyy" />
                            <div id="error_end" class=" col-sm-12"></div> 
                        </div>    
                        <div class="form-group">
						    <label for="field-1" class="control-label"><?php echo get_phrase('Attachment');?></label>
							<input type="file" class="form-control" name="userfile" id="userfile" onchange="file_validate('userfile','doc','img_f_msg')"  value="">
                            <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx </span>
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
                    </div>
               
<script>

	$(document).ready(function(){
//$('.selectpicker').selectpicker();
	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
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
						url: "<?php echo base_url(); ?>leave_staff/term_date_range",

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
						url: "<?php echo base_url(); ?>leave_staff/term_date_range",

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
				// document.getElementById("end_date").value = "";
        
        
			}
		});

	$("#start_date").change(function () {
			$('#btn_add').removeAttr('disabled','true');
			var startDate = document.getElementById("start_date").value;
			var endDate = document.getElementById("end_date").value;
 
			if ((Date.parse(endDate) < Date.parse(startDate))){
				$('#error_start').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
				$('#btn_add').attr('disabled','true');	
				//document.getElementById("start_date").value = "";

			}
		});

	

	
</script>




<style>
	.loader {
		border: 16px solid #f3f3f3; /* Light grey */
		border-top: 16px solid #63b7e7; /* Blue */
		border-radius: 50%;
		width: 120px;
		height: 120px;
		animation: spin 2s linear infinite;
		margin-right: auto;
		margin-left: auto;
	}

	.loader_small {
		border: 7px solid #f3f3f3;
		border-top: 7px solid #63b7e7;
		border-radius: 50%;
		width: 30px;
		height: 30px;
		animation: spin 1s linear infinite;
		margin-right: auto;
		margin-left: auto;
	}

	@keyframes spin {
		0% { 
		transform: rotate(0deg);}
	100%
	{ 
		transform: rotate(360deg);}
	}

</style>
 <script src="<?php echo base_url(); ?>assets/js/common.js"></script>



