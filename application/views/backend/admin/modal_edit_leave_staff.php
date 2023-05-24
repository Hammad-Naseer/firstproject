<?php 
    $query="SELECT ls.* FROM ".get_school_db().".leave_staff ls INNER join ".get_school_db().".staff s ON ls.staff_id=s.staff_id WHERE ls.leave_staff_id=$param2 AND ls.school_id=".$_SESSION['school_id']."";
    $edit_data=$this->db->query($query)->result_array();
?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
		<div class="panel-title black2">
		    <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_staff_leaves');?>
	    </div>
	</div>
	<div class="panel-body">
        <?php foreach($edit_data as $row){ ?>
        <?php echo form_open_multipart(base_url().'leave_staff/manage_leaves_staff/do_update/'.$row['leave_staff_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
            <div class="form-group" id="students1" >
                <label class="control-label"><?php echo get_phrase('staff_name');?><span class="star">*</span></label>
                <select name="staff_id_add" id="staff_id_add" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <?php   
                        $staff_id =  $row['staff_id'];                                  
                        echo staff_list('',$staff_id);
                    ?>
                </select>
            </div>              
            <div class="form-group" >
                <label class="control-label"><?php echo get_phrase('leave_type');?><span class="star">*</span></label>
                <div class="box closable-chat-box">
                    <div class="box-content padded">
                        <div class="chat-message-box">            
                            <select name="leave_type_add" id="leave_type_add" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                <?php   
                                    $leave_categ_id =  $row['leave_category_id'];                                  
                                    echo get_leave_category($leave_categ_id);;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>         
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('description');?></label>
                <textarea type="text" class="form-control" name="reason_add" id="reason_add"  maxlength="1000" oninput="count_value('reason_add','area_count1','1000')"><?php echo $row['reason'];?></textarea>
                <div id="area_count1" class="col-sm-12 "></div>
            </div>   
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('start_date');?><span class="star">*</span></label>
                <input type="text" class="form-control datepicker" name="start_date_add" id="start_date_add" value="<?php  $start_date= $row['start_date']; $assign_array=explode('-',$start_date); echo $assign_array[2].'/'.$assign_array[1].'/'.$assign_array[0]; ?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');  ?>" data-format="dd/mm/yyyy"/>
                <div id="error_start1" class="col-sm-8 col-sm-offset-4"></div>
            </div> 
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('end_date');?><span class="star">*</span></label>
                <input type="text" class="form-control datepicker" name="end_date_add" id="end_date_add" value="<?php $end_date= $row['end_date']; $assign_array=explode('-',$end_date); echo $assign_array[2].'/'.$assign_array[1].'/'.$assign_array[0]; ?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-format="dd/mm/yyyy"/>
                <div id="error_end1" class="col-sm-8 col-sm-offset-4"></div>
            </div>    
            <div class="form-group">
                <label  class="control-label"><?php echo get_phrase('attachment');?></label>
                <input value="" type="file" class="form-control" name="image2" id="image2" onchange="file_validate('image2','doc','img_f_msg')">
                <span style="color: green;">
                <?php echo get_phrase('allowed_file_size');?>: 2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                <br />
                <span style="color: red;" id="img_f_msg"></span>	
                <span id="id_file">				
                <?php     
                    $val_im=display_link($row['proof_doc'],'leaves_staff',0,0); 
                    if($val_im!=""){
                ?>	
                <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment');?></a>
                <a onclick="delete_files('<?php echo $row['proof_doc']; ?>','leave_staff','leave_staff_id','<?php echo $row['leave_staff_id']; ?>','proof_doc','leaves_staff','id_file',2)" href="#" class="img-responsive img-thumbnail">
                    <?php echo get_phrase('delete_attachment');?>
                </a>
                <?php } ?>				
			    </span>		
            </div>	
            <input type="hidden" name="image_old" value="<?php echo $row['proof_doc']; ?>"/>
            <div class="form-group">
              <div class="float-right">
        			<button type="submit" class="modal_save_btn" id="btn_edit">
        				<?php echo get_phrase('edit_leave');?>
        			</button>
        			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        				<?php echo get_phrase('cancel');?>
        			</button>
        		</div>
            </div>
        <?php echo form_close(); } ?>
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
			$("#departments_id_add").change(function(){
					var dep_id=$(this).val();
					$("#icon").remove();
	
					$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
					$.ajax({
							type: 'POST',
							data: {department_id:dep_id},
							url: "<?php echo base_url();?>leave/get_class",
							dataType: "html",
							success: function(response) {
								//alert(response);
			
				
								$("#icon").remove();
			
								$("#class_id_add").html(response);
								$("#section_id_add").html('<select><option value=""><?php echo get_phrase('select_section'); ?></option></select>');
								$("#student_id_add").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
			
			
				
							}
						});
	
	
	
				});	
	
	
			$("#class_id_add").change(function(){
					var class_id=$(this).val();
					$("#icon").remove();
					$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
					$.ajax({
							type: 'POST',
							data: {class_id:class_id},
							url: "<?php echo base_url();?>leave/get_class_section",
							dataType: "html",
							success: function(response) {
								$("#icon").remove();
								$("#section_id_add").html(response);
								$("#student_id_add").html('<select><option value=""><?php echo get_phrase('select_section'); ?></option></select>');
			
				
							}
						});
	
	
	
				});		
	
			$("#section_id_add").change(function(){
					var section_id=$(this).val();
	
					$("#icon").remove();
					$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
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
	
			$("#subject_id1").change(function(){
					var subject_id=$(this).val();
	
					$("#icon").remove();
					$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
					$.ajax({
							type: 'POST',
							data: {subject_id:subject_id},
							url: "<?php echo base_url();?>diary/get_subject_teacher",
							dataType: "html",
							success: function(response) {
								$("#icon").remove();
			
								$("#teacher_id1").html(response);
			
				
							}
						});		
	
				});		
	

			$('#acad_year_add').change(function(){	
					var acad_year=$(this).val();
					get_year_term2(acad_year);
				});		
	
			function get_year_term2(){
				$('#acad_year_add').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

				var acad_year=$('#acad_year_add').val();


	
				$.ajax({
						type: 'POST',
						data: {acad_year:acad_year},
						url: "<?php echo base_url();?>leave/get_year_term",
						dataType: "html",
						success: function(response) { 
							//alert(response);  
    
							$('#message').remove();
							if($.trim(response)!="")
							{  
								$('#yearly_terms_add').html(response);
							}
							if($.trim(response)=="")
							{
								$('#yearly_terms_add').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
							}  
							//alert(response);
						}
					});	
	
	
	
	
			}


			$("#assign_date1").change(function(){
					var assign_date=$(this).val();
					var subject_id= $('#subject_id1').val();
					if(assign_date!="" && subject_id!="")
					{
	

						$.ajax({
								type: 'POST',
								data: {assign_date:assign_date,subject_id:subject_id},
								url: "<?php echo base_url();?>diary/get_acad_checkboxes",
								dataType: "html",
								success: function(response) {
									$('#item_list1').html(response);
								}
							});
					}
	
				});

			$("#subject_id1").change(function(){
					var subject_id=$(this).val();
					var assign_date= $('#assign_date1').val();
					if(assign_date!="" && subject_id!="")
					{
	

						$.ajax({
								type: 'POST',
								data: {assign_date:assign_date,subject_id:subject_id},
								url: "<?php echo base_url();?>diary/get_acad_checkboxes",
								dataType: "html",
								success: function(response) {
									$('#item_list1').html(response);

								}
							});
					}
	
				});	

			$("#yearly_terms_add").change(function(){
					var end_date=$("#end_date_add").val();
					var start_date=$("#start_date_add").val();
					var term_id=$(this).val();
					if(end_date!='')
					{
						$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>noticeboards/term_date_range",

								data: ({date1:end_date,term_id:term_id}),
								dataType : "html",
								success: function(html) {
									if(html==0){
										$('#error_end1').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');		
										$('#btn_edit').attr('disabled','true');	
									}
									else{
										$('#btn_edit').removeAttr('disabled');
										$('#error_end1').text('');	
										if ((Date.parse(end_date) < Date.parse(start_date))){
											$('#error_start1').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>");
											$('#btn_edit').attr('disabled','true');	
			
										}		
									}					
								}
							});
					}
					if(start_date!='')
					{
						$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>noticeboards/term_date_range",

								data: ({date1:start_date,term_id:term_id}),
								dataType : "html",
								success: function(html) {
									if(html==0){
										$('#error_start1').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');		
										$('#btn_edit').attr('disabled','true');	
									}
									else{
										$('#btn_edit').removeAttr('disabled');
										$('#error_start1').text('');	
										if ((Date.parse(start_date) > Date.parse(end_date))) {
											$('#error_start1').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
											$('#btn_edit').attr('disabled','true');	
				
        
										}		
									}					
								}
							});
					}
					if ((Date.parse(end_date) < Date.parse(start_date))){
						$('#error_start1').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
						$('#btn_add').attr('disabled','true');	
						//document.getElementById("start_date").value = "";

					}
					if ((Date.parse(start_date) > Date.parse(end_date))) {
						$('#error_end1').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>");
						$('#btn_add').attr('disabled','true');	
						// document.getElementById("end_date").value = "";
        
        
					}
				});	
	


			$("#end_date_add").change(function () {
					$('#btn_edit').removeAttr('disabled','true');
					var startDate = document.getElementById("start_date_add").value;
					var endDate = document.getElementById("end_date_add").value;
					$('#error_end1').text('');
					var end_date=$(this).val();
					var term_id=$('#yearly_terms_add').val();
					if(end_date!='')
					{
						$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>leave/term_date_range",

								data: ({end_date:end_date,term_id:term_id}),
								dataType : "html",
								success: function(html) {
									if(html==0){
										$('#error_end1').text('<?php echo get_phrase('end_date_should_be_between_term_dates');?>');		
										$('#btn_edit').attr('disabled','true');	
									}					
								}
							});
					}
					if ((Date.parse(startDate) > Date.parse(endDate))) {
						$('#error_end1').text("End date should be greater than Start date");
						$('#btn_edit').attr('disabled','true');	
      
        
					}
				});

			$("#start_date_add").change(function () {
					$('#btn_edit').removeAttr('disabled','true');
					$('#error_start1').text('');
					var start_date=$(this).val();
					var term_id=$('#yearly_terms_add').val();
					if(start_date!='')
					{
						$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>leave/term_date_range",

								data: ({start_date:start_date,term_id:term_id}),
								dataType : "html",
								success: function(html) {
									if(html==0){
										$('#error_start1').text('<?php echo get_phrase('start_date_should_be_between_term_dates');?>');
										$('#btn_edit').attr('disabled','true');			
									}					
								}
							});
					}
					var startDate = document.getElementById("start_date_add").value;
					var endDate = document.getElementById("end_date_add").value;
 
					if ((Date.parse(endDate) < Date.parse(startDate))){
						$('#error_start1').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
						$('#btn_edit').attr('disabled','true');	

					}
				});
		});
	
</script>