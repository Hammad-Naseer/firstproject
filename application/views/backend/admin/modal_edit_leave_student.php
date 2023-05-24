<?php 
    
    $query="SELECT ls.*,d.departments_id as departments_id,d.title as dept_name,c.class_id as class_id, cs.section_id as section_id
    FROM ".get_school_db().".leave_student ls
    INNER join ".get_school_db().".student s
    ON ls.student_id=s.student_id
    INNER join ".get_school_db().".class_section cs
    ON s.section_id=cs.section_id
    Inner JOIN ".get_school_db().".class c
    On cs.class_id=c.class_id
    Inner join ".get_school_db().".departments d
    On d.departments_id=c.departments_id
    WHERE ls.request_id=$param2 AND ls.school_id=".$_SESSION['school_id']."";
    $edit_data=$this->db->query($query)->result_array();

?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
            		<?php echo get_phrase('edit_student_leaves');?>
            	</div>
            </div>
            <div class="panel-body">
                <div class="box-content">
                    <?php foreach($edit_data as $row){ ?>
                    <?php echo form_open_multipart(base_url().'leave/manage_leaves_student/do_update/'.$row['request_id'] , array('id'=>'student_leaves_edit','class' => 'form-horizontal validate','target'=>'_top'));?>
                        <div class="padded">
            				<div class="form-group">
                                <label class="control-label"><?php echo get_phrase('class');?>
                                /
                                <?php echo get_phrase('section');?>
                                <span class="red"> * </span></label>
                                <select id="section_id_add" class="selectpicker form-control" name="section_id_add" data-validate="required" data-message-required="Value Required" >
                                    <?php echo section_selector($row['section_id']);?>
             					</select>
             					<div id="section-err"></div>
                            </div>
                            <div class="form-group" id="students1" >
                                <label class="control-label"><?php echo get_phrase('student_name');?><span class="red"> * </span></label>
                                <select name="student_id_add" id="student_id_add" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                <?php   
                                    $section_id =  $row['section_id'];                                  
                                    echo section_student($section_id,$row['student_id']);
                                ?>
                                </select>
                            </div>
                            <div class="form-group" >
                                <label class="control-label"><?php echo get_phrase('leave_type');?><span class="red"> * </span></label>
                                <select name="leave_type_add" id="leave_type_add" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                <?php   
                                    $leave_categ_id =  $row['leave_category_id'];                                  
                                    echo get_leave_category($leave_categ_id);;
                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('description');?></label>
                                <textarea type="text" class="form-control" name="reason_add" id="reason_add"  maxlength="1000" oninput="count_value('reason_add','area_count1','1000')"><?php echo $row['reason'];?></textarea>
                                <div id="area_count1" class="col-sm-12 "></div>
                            </div>   
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('start_date');?><span class="red"> * </span></label>
                                    <input type="date" class="form-control" name="start_date_add" id="start_date" value="<?php
                                    $start_date= $row['start_date'];
                                    echo $start_date;
                                      ?>" data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                <div id="error_start"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('end_date');?><span class="red"> * </span></label>
                                    <input type="date" class="form-control" name="end_date_add" id="end_date" value="<?php
                                    $end_date= $row['end_date'];echo $end_date;
                                      ?>" data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                <div id="error_end"></div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('actual_start_date');?><span class="red"> * </span></label>
                                    <input type="date" class="form-control" name="approved_upto_date" id="approved_upto_date" value="<?php
                                    $start_date= $row['start_date'];
                                    echo $start_date;
                                      ?>" data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                <div id="error_start"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('actual_end_date');?><span class="red"> * </span></label>
                                    <input type="date" class="form-control" name="final_end_date" id="final_end_date" value="<?php
                                    $end_date= $row['end_date'];echo $end_date;
                                      ?>" data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                <div id="error_end"></div>
                            </div>
                            
                            <!--<div class="form-group">-->
                            <!--    <label class="control-label"><?php echo get_phrase('status');?><span class="red"> * </span></label>-->
                            <!--    <select class="form-control" name="status">-->
                            <!--        <option value="1">Approve</option>-->
                            <!--        <option value="2">Reject</option>-->
                            <!--    </select>-->
                            <!--</div>-->
                            
                            <div class="form-group">
                                <label  class="control-label"><?php echo get_phrase('attachment');?></label>
                                <input value="" type="file" class="form-control" name="image2" id="image2" onchange="file_validate('image2','doc','img_f_msg')">
                                <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                                <br />
                                <span style="color: red;" id="img_f_msg"></span>
                                <br />
                                <span id="id_file">				
                            	    <?php
                            		
                                        $val_im=display_link($row['proof_doc'],'leaves_student',0,0); 
                                        if($val_im!=""){
                                    ?>	
                                    <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail">
                                        <?php echo get_phrase('form_attachment');?>
                                    </a>
                                    <a onclick="delete_files('<?php echo $row['proof_doc']; ?>','leave_student','request_id','<?php echo $row['request_id']; ?>','proof_doc','leaves_student','id_file',2)" href="#" class="img-responsive img-thumbnail">
                                        <?php echo get_phrase('delete_attachment');?>
                                    </a>
                                    <?php } ?> 				
            		            </span>	    
                              </div>
                        </div>	
                        <input type="hidden" name="image_old" value="<?php echo $row['proof_doc']; ?>"/>

                        <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn" id="btn_edit">
                                <?php echo get_phrase('Save');?>
                            </button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
                    </div>
                    <?php echo form_close(); } ?>
                </div>
            </div>
        </div>
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

$('#start_date').on('change',function(){
			$('#btn_add').removeAttr('disabled','true');
			$('#error_start').text('');
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
						 $('#error_start').text('<?php echo get_phrase('start_date_should_be_between_term_dates');?>');
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
						 $('#error_end').text('<?php echo get_phrase('end_date_should_be_between_term_dates');?>');		
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
        $('#error_end').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>");
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

$("#yearly_terms_add").change(function(){
var end_date=$("#end_date").val();
			var start_date=$("#start_date").val();
			var term_id=$(this).val();
			if(end_date!='')
			{
				
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>leave/term_date_range",

					data: ({end_date:end_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						console.log(html);
						if(html==0){
						 $('#error_end').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');		
						 $('#btn_add').attr('disabled','true');	
						}
						else{
						
						 if ((Date.parse(end_date) < Date.parse(start_date))){
						 $('#error_start').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
						 $('#btn_add').attr('disabled','true');	
			
						}
						else{
						 $('#btn_add').removeAttr('disabled');
						 $('#error_end').text('');
						}		
						}					
					}
				});
				}
				if(start_date!='')
			{
				
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>leave/term_date_range",

					data: ({start_date:start_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_start').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');		
						 $('#btn_add').attr('disabled','true');	
						}
						else{
						
						 if ((Date.parse(start_date) > Date.parse(end_date))) {
				$('#error_end').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>");
				$('#btn_add').attr('disabled','true');	
				
        
			}else{
				 $('#btn_add').removeAttr('disabled');
				 $('#error_start').text('');	
			}
						}					
					}
				});
				}
				
});	
});
</script>