<?php //session_start(); ?>
<div class="row">
<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_planner');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url().'academic_planner/save_planner/create/' , array('class' => 'form-horizontal form-groups-bordered validate','id'=>'disable_submit_btn', 'enctype' => 'multipart/form-data'));?>
	
	
	<div class="form-group" id="class" >
                                <label class="col-sm-4 control-label"><?php echo get_phrase('academic_year');?></label>
                                <div class="col-sm-8">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                              <select id="acad_year1" class="form-control" name="acad_year1">

<?php echo academic_year_option_list('',1);?>
</select>
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>


<div class="form-group" id="class" >
                                <label class="col-sm-4 control-label"><?php echo get_phrase('yearly_terms');?></label>
                                <div class="col-sm-8">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                               <select id="yearly_terms1" class="form-control" name="yearly_terms1">
<option value="">
<?php echo get_phrase('select_yearly_terms');?>
</option>

		
</select>  
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

<div class="form-group" id="class" >
                                <label class="col-sm-4 control-label"><?php echo get_phrase('select_department');?></label>
                                <div class="col-sm-8">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                               <select id="departments_id" class="form-control" name="departments_id">
<?php 
echo department_option_list();
?>		
</select>
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
 <div class="form-group" id="class" >
                                <label class="col-sm-4 control-label"><?php echo get_phrase('select_class');?></label>
                                <div class="col-sm-8">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                             <select id="class_id" class="form-control" name="class_id">
<?php 
echo class_option_list();
?>		
</select>		
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
 <div class="form-group" id="class" >
                                <label class="col-sm-4 control-label"><?php echo get_phrase('select_section');?></label>
                                <div class="col-sm-8">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                            <select id="section_id" name="section_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                         
                          </select>
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>	
	
					<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('title');?></label>
                
                        
<div class="col-sm-8">
<input class="form-control" name="title" autofocus required="">

<input type="hidden"  name="subject_id" value="<?php echo $param2 ; ?>">

<input type="hidden"  name="section_id" value="<?php echo $param3 ; ?>">




</div>
</div>

<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('detail');?></label>
<div class="col-sm-8">
<textarea class="form-control" name="detail" value="" rows="3"></textarea>
</div> 
</div>

<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('objective');?></label>
<div class="col-sm-8">
<textarea class="form-control" name="objective" value="" rows="3"></textarea>
</div> 
</div>

<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('assesment');?></label>
<div class="col-sm-8">
<textarea class="form-control" name="assesment" value="" rows="3"></textarea>
</div> 
</div>

<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('requirements');?></label>
<div class="col-sm-8">
<textarea class="form-control" name="requirements" value="" rows="3"></textarea>
</div> 
</div>

<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('required_time');?></label>
<div class="col-sm-8">
<input type="number" class="form-control" name="required_time" value="">(<?php echo get_phrase('minutes');?>)
</div> 
</div>
					
<div class="form-group">
<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('date');?></label>
<div class="col-sm-8">
<input id="datepic" type="text" class="form-control datepicker" name="date" value="">
</div> 
</div>
					
				
					<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('attachment');?></label>
                
                        
<div class="col-sm-8">

<input type="file" class="form-control" name="userfile"  value="" autofocus>

</div>
</div>	
				
						<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"></label>
                        
						<div class="col-sm-8">

<div onclick="save_data()"><?php echo get_phrase('save');?></div>
</div> 
</div>
<?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<script>
var month_cur=$('.month').val();	
var year_cur=$('.year').val();

 var pass_date=month_cur+'/01/'+year_cur;
	
	//$('#datepic').val(pass_date);
	
$('#acad_year1').change(function(){
	
var acad_year=$(this).val();
get_year_term1(acad_year);
function get_year_term1(){
	$('#acad_year1').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
var acad_year=$('#acad_year1').val();


	
$.ajax({
      type: 'POST',
       data: {acad_year:acad_year},
url: "<?php echo base_url();?>academic_planner/get_year_term2",
 dataType: "html",
 success: function(response) { 
    //alert(response);  
    
$('#message').remove();
$('#yearly_terms1').html(response);
//alert(response);
}
});	
	
	
	
	
}
});		

$("#departments_id").change(function(){
	$('#get_planner').html('');
	var dep_id=$(this).val();
	$("#icon").remove();
	
	$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	$.ajax({
			type: 'POST',
			data: {department_id:dep_id},
	url: "<?php echo base_url();?>academic_planner/get_class",
			dataType: "html",
			success: function(response) {			
				$("#icon").remove();	
			$("#class_id").html(response);
			$("#section_id").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');		
				 }
		});
});	

$("#class_id").change(function(){
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
				$("#icon").remove();
			$("#section_id").html(response);
			 }
		});
	
	
	
});		
</script>