<?php 
    $query="select dr.*,d.departments_id as departments_id,d.title as dept_name,c.class_id as class_id, cs.section_id as section_id
 	from ".get_school_db().".diary dr 
 	inner join ".get_school_db().".class_section cs ON dr.section_id=cs.section_id
	inner join ".get_school_db().".class c On cs.class_id=c.class_id
	inner join ".get_school_db().".departments d On d.departments_id=c.departments_id
	WHERE 
	dr.diary_id=$param2 
	AND dr.school_id=".$_SESSION['school_id']."";

    $edit_data=$this->db->query($query)->result_array();
?>
<div class="panel panel-primary" data-collapsed="0">
     <div class="panel-title black2">
	    <i class="entypo-plus-circled"></i>
	    <?php echo get_phrase('edit_diary');?>
	</div>
    <?php foreach($edit_data as $row){ ?>
    <div class="panel-body">
        <?php echo form_open_multipart(base_url().'diary/diarys/do_update/'.$row['diary_id'].'/'.$row['section_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'id'=>'diary_edit'));?>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('Class/Section');?><span class="red"> * </span></label>
                <label id="section_id1_selection" style="display: block; font-size:11px;"><?php echo $row['dept_name'];?></label>
                <select id="section_id1" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required"  disabled>
                    <?php echo section_selector($row['section_id']);?>
                </select>
 			    <div id="section-err"></div>
            </div>
            <div class="form-group" id="subjects1" >
                <label class="control-label"><?php echo get_phrase('select_subject');?><span class="red"> * </span></label>
                <select name="subject_id1" id="subject_id1" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <option value=""><?php echo get_phrase('select_subject');?></option>                                                
                    <?php  
                     $subject_id=$row['subject_id'];
                     $staff_id=$row['teacher_id'];
                     $section_id=$row['section_id'];
                     echo teacher_subject_list($section_id,$subject_id,$staff_id);
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('assign_date');?></label>
                <input type="date" class="form-control" name="assign_date1" id="assign_date1" value="<?php echo $assign_date= $row['assign_date']; ?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-format="dd/mm/yyyy"/>
                <div id="error_start1" ></div>  
            </div>  
            <div class="form-group">       
                <label class="control-label"><?php echo get_phrase('academic_planner_task');?></label>
                <div id="item_list1">
                     <?php 
                        $query2="SELECT planner_id FROM ".get_school_db().".academic_planner_diary 
                        WHERE diary_id=".$row['diary_id']." ";
                        $selected=$this->db->query($query2)->result_array();
                        $res_array=array();
                        foreach($selected as $res)
                        {
            				$res_array[]=$res['planner_id'];
            			}     
                        $assign_date=$row['assign_date'];
                        $query1="SELECT planner_id,title FROM ".get_school_db().".academic_planner WHERE `start`='$assign_date' AND subject_id=".$row['subject_id']." AND school_id=".$_SESSION['school_id']."";
                        $result=$this->db->query($query1)->result_array();
                        foreach($result as $planner)
    		            {
                			$checked = "";
                			if (in_array($planner["planner_id"],$res_array))
                			{
                				$checked = "checked";
                			}
    			            echo '<label><input type="checkbox" name="planner_check[]" value="'.$planner["planner_id"].'" '.$checked.'>'.$planner["title"].'</label>';
    			            echo '<br/>';
    		            }
                     ?>  
                </div>  
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('title');?></label>
                <input type="text" class="form-control" name="title1" value="<?php echo $row['title'];?>" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
            </div>        
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('task');?></label>
                <textarea name="task1" id="task1" rows="5" class="form-control" placeholder="<?php echo get_phrase('add_task');?>" maxlength="1000" oninput="count_value('task1','area_count1','1000')"><?php echo $row['task'];?></textarea>
                <div id="area_count1" class="col-sm-12 "></div>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('due_date');?><span class="red"> * </span></label>
                <input type="date" class="form-control" name="due_date1" id="due_date1" value="<?php echo $due_date= $row['due_date']; ?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  data-format="dd/mm/yyyy"/>
            </div>
            <div id="error_end1" class="col-sm-8 col-sm-offset-4"></div>    
            <div class="form-group">
        <label class="control-label"><?php echo get_phrase('attachment');?></label>
        <input value="" type="file" class="form-control" name="image2" id="image2" onchange="file_validate('image2','doc','img_g_msg')">
        <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
        <br />
        <span style="color: red;" id="img_g_msg"></span>                          
        <span id="id_file">				
		<?php
            $val_im=display_link($row['attachment'],'diary',0,0); 
            if($val_im!=""){
        ?>	
        <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment');?></a>
        <a onclick="delete_files('<?php echo $row['attachment']; ?>','diary','diary_id','<?php echo $row['diary_id']; ?>','attachment','diary','id_file',2)" href="#" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment');?></a>
        <?php } ?>						
		</span>	    
    </div>
            <input type="hidden" name="image_old" value="<?php echo $row['attachment']; ?>"/>
            <div class="form-group">
        <input type="hidden" name="is_submitted1" id="is_submitted1" value="0" >
        <div class="float-right">
			<button type="submit" class="modal_save_btn" id="btn_edit">
				<?php echo get_phrase('save');?>
			</button>
			<button type="submit" class="modal_save_btn" id="submit_btn1" onclick="return submit_func();">
				<?php echo get_phrase('submit');?>
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
 $('#submit_btn1').click(function() 
    {
        if (confirm('If diary is submitted then it cannot be changed. Are you sure to submit?'))
        {
            $('#is_submitted1').val(1);
           // $("#submit_btn1").attr('disabled','disabled');
            $('#diary_edit_form').submit();

        }
        else
        {
            return false;
        }
    });
//$('.selectpicker').selectpicker();
	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });

 $("#section_id1").change(function() 
    {
    	$('#item_list1').html('');
        var section_id = $(this).val();

        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id
            },
            url: "<?php echo base_url();?>diary/section_student_subject",
            dataType: "html",
            success: function(response) {
            	//alert(response);
            	
                var obj = jQuery.parseJSON(response);
                //alert(obj.student);
                //alert(obj.subject);
                $("#icon").remove();
                $("#subject_id1").html(obj.subject);
                //$("#student_id1").html(obj.student);

            }
        });

    });
		

/*$("#section_id1").change(function(){
	var section_id=$(this).val();
	
	$("#icon").remove();
	$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	$.ajax({
			type: 'POST',
			data: {section_id:section_id},
	url: "<?php echo base_url(); ?>diary/get_section_student_subject",
			dataType: "html",
			success: function(response) {
				var obj = jQuery.parseJSON(response);
				$("#icon").remove();
			
			$("#student_id1").html(obj.student);
			$("#subject_id1").html(obj.subject);	
			$("#teacher_id1").html('<select><option>Select Section</option></select>');
				
				 }
		});
	
	
	
});	*/
	
	
	

$('#acad_year2').change(function(){	
var acad_year=$(this).val();
get_year_term2(acad_year);
});		
	
function get_year_term2(){
	$('#acad_year2').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

var acad_year=$('#acad_year2').val();


	
$.ajax({
      type: 'POST',
       data: {acad_year:acad_year},
url: "<?php echo base_url();?>diary/get_year_term2",
 dataType: "html",
 success: function(response) 
{ 
$('#message').remove();
	if($.trim(response)!="")
	{
		$('#yearly_terms2').html(response);
	}
	if($.trim(response)=="")
	{
		$('#yearly_terms2').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
	}
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
 	//alert(response);
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
 	//alert(response);
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

$('#assign_date1').on('change',function(){
			$('#btn_edit').removeAttr('disabled','true');
			$('#error_start1').text('');
			var start_date=$(this).val();
			var term_id=$('#yearly_terms2').val();
			if(start_date!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>diary/term_date_range",

					data: ({start_date:start_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_start1').text('<?php echo get_phrase('assign_date_should_be_between_term_dates'); ?>');
						 $('#btn_edit').attr('disabled','true');			
						}

                    }
				});
				}
		
	});

$('#due_date1').on('change',function(){
		$('#btn_edit').removeAttr('disabled','true');
			 $('#error_end1').text('');
			var end_date=$(this).val();
			var term_id=$('#yearly_terms2').val();
			if(end_date!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>diary/term_date_range",

					data: ({end_date:end_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end1').text('<?php echo get_phrase('due_date_should_be_between_term_dates'); ?>');		
						 $('#btn_edit').attr('disabled','true');	
						}					
					}
				});
				}
		
	});

$("#assign_date1").change(function () {
	$('#btn_edit').removeAttr('disabled','true');
    var startDate = document.getElementById("assign_date1").value;
    var endDate = document.getElementById("due_date1").value;
 
    if ((Date.parse(endDate) < Date.parse(startDate))){
         $('#error_start1').text("<?php echo get_phrase('assign_date_should_be_less_then_due_date');?>");
       $('#btn_edit').attr('disabled','true');	
        //document.getElementById("start_date").value = "";

    }
});
	
$("#due_date1").change(function () {
    	$('#btn_edit').removeAttr('disabled','true');
    var startDate = document.getElementById("assign_date1").value;
    var endDate = document.getElementById("due_date1").value;
 
    if ((Date.parse(startDate) > Date.parse(endDate))) {
        $('#error_end1').text("<?php echo get_phrase('due_date_should_be_greater_than_assign_date'); ?>");
        $('#btn_edit').attr('disabled','true');	
       // document.getElementById("end_date").value = "";
        
        
    }
});

$("#yearly_terms2").change(function(){
var date2=$("#assign_date1").val();

//$("#due_date1").val('');	
var date1=$("#due_date1").val();

var term_id=$(this).val();
			if(date1!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>noticeboards/term_date_range",

					data: ({date1:date1,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_start1').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');		
						 $('#btn_edit').attr('disabled','true');	
						}
						else{
						 $('#btn_edit').removeAttr('disabled');
						 $('#error_start1').text('');		
						}					
					}
				});
				}
				if(date2!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>noticeboards/term_date_range",

					data: ({date1:date2,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end1').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');		
						 $('#btn_edit').attr('disabled','true');	
						}
						else{
						 $('#btn_edit').removeAttr('disabled');
						 $('#error_end1').text('');		
						}					
					}
				});
				}
});	

$("#acad_year2").change(function(){
$("#assign_date1").val('');

$("#due_date1").val('');	
});
});
	
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>