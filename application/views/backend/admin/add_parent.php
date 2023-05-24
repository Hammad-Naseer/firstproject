<?php //session_start(); ?>
<div class="row">
<div class="col-md-12">
<?php 
$student_id=$this->uri->segment(3);	
 $s_row=$this->db->query("select s.*,c.title as class_name from ".get_school_db().".student s inner join ".get_school_db().".class_section c on c.section_id=s.section_id where  s.student_id=$student_id")->result_array();
foreach($s_row as $ro){ ?>
	<span><img height="100" width="100" src="<?php
	echo display_link($ro['image'],'student');
	?>"/></span>
	<span><?php echo get_phrase('name'); ?></span>
	<span><?php echo $ro['name'];   ?></span>
	<span><?php echo get_phrase('Class'); ?></span>
	<span><?php echo $ro['class_name'];   ?></span>
	<span><?php echo get_phrase('roll'); ?></span>
	<span><?php echo $ro['roll'];   ?></span>
	<?php
}
 ?>	
	
	
	
	
</div>




	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_form');?>
            	</div>
            </div>














	<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?php 
  echo get_phrase('select_account_to_link_student'); 
  ?>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<?php 
	
	//print_r($p_row);
	
	
	foreach($p_row as $rr){
	$relation =	$rr['relation'];
		
	?>
	<li><a href="#" onclick="parent_data(<?php echo $rr['s_p_id'];  ?>,'<?php echo $relation  ?>')"><?php echo $rr['p_name']; ?></a></li>
	
<?php } ?>
	
	    </ul>
</div>        
               
               
  <div id="student_detail" class="col-md-12">
	
	
	
</div>             
               
               
               
                  
<div class="panel-body" id="panel_hide" style="display:none;" >
				
<?php echo form_open(base_url().'admin/attach_parent/'.$this->uri->segment(3).'/'.$this->uri->segment(4), array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data' , 'id'=>'disable_submit_btn'));?>

            <!-- CNIC   -->      
<div class="form-group">
<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Name');?></label>
                        
						<div class="col-sm-5">
<input id="p_name" type="text" class="form-control" name="p_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
                            	value="">
						</div>
					</div>
                   
       <!-- end CNIC   -->                
          
<div class="form-group">
<label for="field-1" class="col-sm-3 control-label">
<?php echo get_phrase('id_no');?>	
</label>
                        
						<div class="col-sm-5">
<input id="cnic" type="text" class="form-control" name="cnic" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"autofocus value="" >
 
 </div>

</div>
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('occupation');?></label>
						<div class="col-sm-5">
			<span style="color: red;" id="message"></span>			
<input   type="text" class="form-control" name="occupation" id="occupation" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('contact');?></label>
                        
						<div class="col-sm-5">
<input type="text" id="contact" class="form-control" name="contact" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('email');?></label>
                        
						<div class="col-sm-5">
<input onchange="get_email()" id="email" type="email" class="form-control" name="email" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('password');?></label>
                        
<div class="col-sm-5">

<input id="password" type="password" class="form-control" name="password" value="">

						</div>
					</div>


	<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Attachment');?></label>
                        
<div class="col-sm-5">

<input id="img_file" type="file" class="form-control" name="userfile" value="">
<input id="s_p_id" type="hidden" class="form-control" name="s_p_id" value="">
<input id="image_file" type="hidden" class="form-control" name="hidden" value="">
<input id="relation" type="hidden" class="form-control" name="relation" value="">






						</div>
					</div>
				
                    
  <div class="form-group">
<div class="col-sm-offset-3 col-sm-5">
							<button id="btn1" type="submit" class="btn btn-default"><?php echo get_phrase('Attach_student');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>









</div>
<script>
	function parent_data(s_p_id,relation){
		
		$('#panel_hide').show();
		$('#image').remove();
		get_parent(s_p_id)
		
	$.ajax({
      type: 'POST',
       data: {s_p_id:s_p_id},
url: "<?php echo base_url();?>admin/get_parent",
 dataType: "json",
 //dataType: "html",
 success: function(response) { 
   
//var obj = jQuery.parseJSON(response);
//var parsed = JSON.parse(response);
 
 if($.trim(response.value)=='no')
{
$('#p_name').removeAttr("readonly").val('');

$('#f_num').val('');
$('#cnic').val('');
$('#occupation').val('');
$('#contact').val('');
$('#email').val('');
$('#image_file').val('');
$('#s_p_id').val('');
$('#relation').val('');
//$('#password').val('');

$('#image').remove();

}
else
{	
$('#p_name').val(response[0].p_name);
$('#cnic').val(response[0].cnic).attr("readonly","true");;
$('#s_p_id').val(response[0].s_p_id);
$('#occupation').val(response[0].occupation);
$('#contact').val(response[0].contact);
$('#image_file').val(response[0].attachment);
$('#relation').val(relation);

$('#contact').val(response[0].contact);
//$('#password').val(response[0].password);
$('#img_file').after('<a id="image" href="<?php echo base_url(); ?>uploads/student_image/'+response[0].attachment+'">attachment</a>');
} 
 
 
 
 
 
 
 
 
 
 
 
}
});	
		
	}
	
	
	
	
	function get_email(){
	
	$('#email').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	var email=$('#email').val();
		$.ajax({
      type: 'POST',
       data: {email:email},
      url: "<?php echo base_url();?>admin/call_function",
      dataType: "html",
      success: function(response) { 
if($.trim(response)=='yes'){

//$("#btn1").attr('disabled','true');
$("#email").css('border','1px solid red');
$("#icon").remove();

if($('#message').html()==undefined){
$("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_is_already_exist'); ?></p>');	
}
}else{
	
//$("#btn1").removeAttr('disabled');
$("#email").css('border','1px solid green');
$("#icon").remove();	
$("#message").remove();	
}      
}
});
}
	
	
	function get_parent(s_p_id){
	$('#student_detail').html(" ");
		$.ajax({
      type: 'POST',
       data: {s_p_id:s_p_id},
      url: "<?php echo base_url();?>admin/get_parent_detail",
      dataType: "html",
      success: function(response) { 


$('#student_detail').html(response);

}
});




}
	
	
	
</script>

<style>
	.glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -webkit-animation: spin2 .7s infinite linear;
}

@-webkit-keyframes spin2 {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
</style>