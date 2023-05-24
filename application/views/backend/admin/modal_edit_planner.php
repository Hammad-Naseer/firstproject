<style>
.fs10{font-size:10px;}	
</style>
<?php 
$query="select ap.* FROM ".get_school_db().".academic_planner ap 
WHERE ap.planner_id=$param2 AND ap.school_id=".$_SESSION['school_id']."";

/*$query="select ap.*,d.departments_id as departments_id,d.title as dept_name,c.class_id as class_id, cs.section_id as section_id
 FROM ".get_school_db().".academic_planner ap 
 INNER join ".get_school_db().".class_section cs
 ON ap.section_id=cs.section_id
Inner JOIN ".get_school_db().".class c
On cs.class_id=c.class_id
Inner join ".get_school_db().".departments d
On d.departments_id=c.departments_id
WHERE ap.planner_id=$param2 AND ap.school_id=".$_SESSION['school_id']."";*/
$edit_data=$this->db->query($query)->result_array();
  //echo "<pre>";  print_r($_SESSION);
?>
    <div class="tab-pane  active" id="edit" style="padding: 5px">
    <div class="box-content">
    <div class="panel-title black2">
	<p><i class="entypo-plus-circled"></i></p>
	<p><?php echo get_phrase('edit_planner_task');?></p>
    </div> 
        <?php foreach($edit_data as $row):
        ?>
        <?php echo form_open_multipart(base_url().'academic_planner/edit_planner' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'edit_form'));?>
            <div class="padded">
                            <div class="form-group">
                             </div>
                            <div class="form-group">
                             <label class="col-sm-4 control-label"><?php echo get_phrase('select_subject');?><span class="red"> * </span></label>
                            <div class="col-sm-8 col-lg-8 col-md-8"> 
                             <select id="subject_id_add" name="subject_id_add" class="form-control" data-validate="required" data-message-required="Value Required" >  
                             <?php
                             echo subject_list($row['subject_id']); 
                            //$section_id=$row['section_id'];
                            //echo subject_option_list($section_id,$row['subject_id']);
                            ?>	
                          </select>
                        </div>
                        </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('title');?><span class="red"> * </span></label>
                    <div class="col-sm-8 col-lg-8 col-md-8">
                    <input class="form-control" name="title" id="title1" value="<?php echo $row['title'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" maxlength="1000"/>
                    <input type="hidden" class="form-control" name="planner_id" id="planner_id1" value="<?php echo $row['planner_id'];?>"/>
                    <input type="hidden" class="form-control" name="attachment" id="attachment1" value="<?php echo $row['attachment'];?>"/>
                    </div>
                </div>
                    <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('detail');?></label>
                    <div class="col-sm-8 col-lg-8 col-md-8">
                    <textarea class="form-control" name="detail" id="detail1" rows="3" maxlength="3000" oninput="count_value('detail1','text_count','3000')"><?php echo $row['detail'];?></textarea>
                    <div id="text_count" class="col-sm-12 "></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('objective');?></label>
                    <div class="col-sm-8 col-lg-8 col-md-8">
                    <textarea class="form-control" name="objective" id="objective1" rows="3" maxlength="3000" oninput="count_value('objective1','text_count1','3000')"><?php echo $row['objective'];?></textarea>
                    <div id="text_count1" class="col-sm-12 "></div>
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('assesment');?></label>
                    <div class="col-sm-8 col-lg-8 col-md-8">
                    <textarea class="form-control" name="assesment" id="assesment1" rows="3" maxlength="3000" oninput="count_value('assesment1','text_count2','3000')"><?php echo $row['assesment'];?></textarea>
                    <div id="text_count2" class="col-sm-12 "></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('requirements');?></label>
                    <div class="col-sm-8 col-lg-8 col-md-8">
                    <textarea class="form-control" name="requirements" id="requirements1" rows="3" maxlength="3000" oninput="count_value('requirements1','text_count3','3000')"><?php echo $row['requirements'];?></textarea>
                    <div id="text_count3" class="col-sm-12 "></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('required time');?></label>
                    <div class="col-sm-8 col-lg-8 col-md-8">
                    <input type="number" class="form-control" name="required_time" id="required_time1" value="<?php echo $row['required_time'];?>" maxlength="3">
                </div>
                </div>
                <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo get_phrase('date');?><span class="red"> * </span></label>
                <div class="col-sm-8 col-lg-8 col-md-8">
                <input type="date" id="datepic1" class="form-control datepicker" name="start" value="<?php echo $row['start'];?>" data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('');?>" />
                <input type="hidden" class="form-control" name="term_id" id="term_id" value=" <?php echo $_SESSION['yearly_terms_id']; ?>">
               
                <div id="error_end1"></div>
                </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('attachment');?></label>
                    <div class="col-sm-8 col-lg-8 col-md-8">
                    <input type="file" name="userfile" id="userfile1" onchange="file_validate('userfile1','doc','img_g_msg')" />
                    <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                   <br />
                    <span style="color: red;" id="img_g_msg"></span>
                    <span id="id_file">				
		            <?php	
                     $val_im=display_link($row['attachment'],'academic_planner',0,0); 
                     if($val_im!=""){
                     ?>	
                    <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment');?></a>
                    <a onclick="delete_files('<?php echo $row['attachment']; ?>','academic_planner','planner_id','<?php echo $row['planner_id']; ?>','attachment','academic_planner','id_file',2)" href="#" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment');?></a>
                    <?php
                    }
                     ?> 				
			</span>	

<?php
/*
 if($row['attachment']!=""){ ?>
<a href="<?php echo display_link($row['attachment'],'academic_planner'); ?>">Old Attachment</a>
<?php } */?>
</div>
</div>
<div class="form-group">
            <label class="col-sm-4 control-label"><?php echo get_phrase('is_active');?></label>
            <div class="col-sm-8 col-lg-8 col-md-8">
            <select name="is_active" class="form-control">
        	<option value="1" <?php if($row['is_active']==1){ echo "selected";} ?> ><?php echo get_phrase('yes');?></option>
      		<option value="0" <?php if($row['is_active']==0){ echo "selected";} ?> ><?php echo get_phrase('no');?></option>
            </select>
            </div>
            </div>
             </div>
        <button type="submit" class="btn btn-info" id="btn_add1"><?php echo get_phrase('edit_planner');?></button>
    <?php endforeach;?>
    </div>
</div>
<div id="result"></div>
<script>
$(document).ready(function(){
	//$('.selectpicker').selectpicker();
	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });
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
       url: "<?php echo base_url();?>academic_planner/get_year_term_add",
       dataType: "html",
       success: function(response) { 
    //alert(response);  
    
$('#message').remove();
if($.trim(response)!="")
{
	$('#yearly_terms2').html(response);
}
if($.trim(response)=="")
{
	//alert("here");
	$('#yearly_terms2').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
}
//alert(response);
}
});	
}

$("#departments_id1").change(function(){
	$('#get_planner').html('');
	var dep_id=$(this).val();
	$("#icon").remove();
	$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	$.ajax({
			type: 'POST',
			data: {department_id:dep_id},
	        url: "<?php echo base_url();?>circular/get_class",
			dataType: "html",
			success: function(response) {
			$("#icon").remove();
			$("#class_id1").html(response);
			$("#section_id1").html('<select><option value=""><?php echo get_phrase('select_section'); ?></option></select>');
			$("#subject_id_add").html('<select><option value=""><?php echo get_phrase('select_subject'); ?></option></select>');
				 }
		});
});	

$("#class_id1").change(function(){
	$('#get_planner').html('');
	var class_id=$(this).val();
	$("#icon").remove();
	$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	$.ajax({
			type: 'POST',
			data: {class_id:class_id},
	        url: "<?php echo base_url();?>academic_planner/get_class_section",
			dataType: "html",
			success: function(response) {
				$("#subject_id_add").html('<select><option value=""><?php echo get_phrase('select_subject'); ?></option></select>');
				$("#icon").remove();
		    	$("#section_id1").html(response);
				 }
		});
});	

$("#section_id1").change(function(){
	$('#get_planner').html('');
	var section_id=$(this).val();
	$("#icon").remove();
	$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	$.ajax({
			type: 'POST',
			data: {section_id:section_id},
	        url: "<?php echo base_url();?>academic_planner/get_section_subject",
			dataType: "html",
			success: function(response) {
				$("#icon").remove();
			$("#subject_id_add").html(response);
				 }
		});
});	

$('#datepic1').on('change',function(){
		$('#btn_add1').removeAttr('disabled','true');
			 $('#error_end1').text('');
			var date1=$(this).val();
			var term_id=$('#term_id').val();
			alert(term_id);
			if(date1!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>academic_planner/term_date_range",
					data: ({date1:date1,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end1').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');		
						 $('#btn_add1').attr('disabled','true');	
						}					
					}
				});
				}
		
	});

$("#acad_year2").change(function(){
$("#datepic1").val('');
});

$("#yearly_terms2").change(function(){
$('#btn_add').removeAttr('disabled','true');
			 $('#error_end').text('');
			var date1=$('#datepic1').val();
			var term_id=$(this).val();
			alert(term_id);
			if(date1!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>academic_planner/term_date_range",

					data: ({date1:date1,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');		
						 $('#btn_add').attr('disabled','true');	
						}
						else{
							('#error_end').text('');		
						 $('#btn_add').removeAttr('disabled');	
						}					
					}
				});
				}
});	

// $('#btn_add1').click(function(){
  
// var yearly_terms2=$('#yearly_terms2').val();
// var section_id=$('#section_id1').val();
// var subject_id_add=$('#subject_id_add').val();
// var title=$('#title1').val();
// var detail=$('#detail1').val();
// var objective=$('#objective1').val();
// var assesment=$('#assesment1').val();
// var requirements=$('#requirements1').val();

// var required_time=$('#required_time1').val();
// var start=$('#datepic1').val();
// alert(start);
// var planner_id=$('#planner_id1').val();
// var attachment=$('#attachment1').val();
// var userfile=$('#userfile1').val();
// //alert(userfile);

// $.ajax({
//       type: 'POST',
//       data: {yearly_terms2:yearly_terms2,section_id:section_id,subject_id_add:subject_id_add,title:title,detail:detail,objective:objective,assesment:assesment,requirements:requirements,required_time:required_time,start:start,planner_id:planner_id,attachment:attachment,userfile:userfile},
//       url: "<?php echo base_url();?>academic_planner/edit_planner",
//      dataType: "html",
//      success: function(response) { 
//      //alert(response);
//      $('#result').html(response);
// }
// });	
// });

});	
</script>