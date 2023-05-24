<?php //session_start(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_principal');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url().'admin/principal/create/' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
	
		<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label">
                        <?php echo get_phrase('account_type');?>
                        </label>
                        
						<div class="col-sm-8">
							<select class="form-control"  name="account_type" data-validate="required" autofocus="" required="">
<option><?php echo get_phrase('select');?><?php echo get_phrase('account_type');?></option>

<option value="3"><?php echo get_phrase('principal');?></option>
<option value="10"><?php echo get_phrase('school_admin');?></option>
							
								
								
								
							</select>
						</div>
					</div>
	
	
	
	
	<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('cnic');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control" id="cnic" name="cnic" data-validate="required" data-message-required="Value Required" value="" autofocus="">
						</div>
					</div>
	
	
					<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('name');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('birthday');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control datepicker" name="birthday" value="" data-start-view="2">
						</div> 
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('gender');?></label>
                        
						<div class="col-sm-8">
							<select name="sex" class="form-control">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <option value="male"><?php echo get_phrase('male');?></option>
                              <option value="female"><?php echo get_phrase('female');?></option>
                          </select>
						</div> 
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('address');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control" name="address" value="" >
						</div> 
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('phone');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control" name="phone" value="" >
						</div> 
					</div>
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('email');?></label>
						<div class="col-sm-8">
							<input id="email" type="text" class="form-control" name="email" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('password');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control" name="password" value="" >
						</div> 
					</div>
	
					<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('photo');?></label>
                        
						<div class="col-sm-8">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="http://placehold.it/200x200" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new"><?php echo get_phrase('select_image'); ?></span>
										<span class="fileinput-exists"><?php echo get_phrase('change'); ?></span>
										<input type="file" name="userfile" accept="image/*">
									</span>
									<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove'); ?></a>
								</div>
							</div>
						</div>
					</div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button id="btn1" type="submit" class="btn btn-info"><?php echo get_phrase('add_principal');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<script>
var flag_email=0;
var flag_cnic=0; 


$('#email').change(function(){
	
	get_email();
	
});

$('#cnic').change(function(){
	
get_cnic();

	
});
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

$("#btn1").attr('disabled','true');
$("#email").css('border','1px solid red');
$("#icon").remove();
 flag_email=0;

if($('#message').html()==undefined){
$("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_is_already_exist'); ?></p>');	
}
}

else{
	
$("#btn1").removeAttr('disabled');
$("#email").css('border','1px solid green');
$("#icon").remove();	
$("#message").remove();	
 flag_email=1;
}   

 flag_check();  
}
});
}

function get_cnic(){
	
	$('#cnic').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	$('#message').remove();
	
	var cnic=$('#cnic').val();
		$.ajax({
      type: 'POST',
       data: {cnic:cnic},
      url: "<?php echo base_url();?>admin/get_cnic_princ",
      dataType: "html",
      success: function(response) { 
if($.trim(response)=='yes'){
$("#cnic").before('<p id="message" style="color:red;"><?php echo get_phrase('cnic_address_is_already_exist'); ?></p>');
$("#cnic").css('border','1px solid red');
 flag_cnic=0; 
}
else

{
	 flag_cnic=1;
// alert(flag_cnic);
$("#cnic").css('border','2px solid green');
$("#message").remove();	
	
	}
	$("#icon").remove();

flag_check();
}
});
}

function flag_check(){
if(flag_cnic==1 && flag_email==1){
	$("#btn1").removeAttr('disabled');
	//alert("i am in if--"+flag_cnic+'--'+flag_email);
}else{
	$("#btn1").attr('disabled','true');	
	//alert("i am in else--"+flag_cnic+'--'+flag_email);
}
	
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