<?php 
$edit_data		=	$this->db->get_where('transport_driver' , array('driver_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">

		<div class="panel-body">
                    <?php echo form_open(base_url().'admin/transport_driver/do_update/'.$row['driver_id'] , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                        		
                                <div class="form-group">
                                <label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('photo');?></label>
                                
                                <div class="col-sm-8"id="photoError">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                            <img src="<?php echo $this->crud_model->get_image_url('driver' , $row['driver_id']);?>" alt="...">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                        <div>
                                            <span class="btn btn-white btn-file">
                                                <span class="fileinput-new"><?php echo get_phrase('select_image'); ?></span>
                                                <span class="fileinput-exists"><?php echo get_phrase('change'); ?></span>
                                                <input id="photo" type="file" name="userfile" accept="image/*">
                                            </span>
                                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                    <div class="form-group">
                                <label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('I.D. card front');?></label>
                                
                                <div class="col-sm-8"id="idFrontError">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                            <img src="<?php echo $this->crud_model->get_id_front_image_url('driver' , $row['driver_id']);?>" alt="...">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                        <div>
                                            <span class="btn btn-white btn-file">
                                                <span class="fileinput-new">
                                                
                                                <?php echo get_phrase('select_image');?>
                                                </span>
                                                <span class="fileinput-exists"><?php echo get_phrase('change');?></span>
                                                <input id="idFront" type="file" name="id-front" accept="image/*">
                                            </span>
                                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove');?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                    <div class="form-group">
                                <label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('iD');?>.
               <?php echo get_phrase('car_back');?></label>
                                
                                <div class="col-sm-8"id="idBackError">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                            <img src="<?php echo $this->crud_model->get_id_back_image_url('driver' , $row['driver_id']);?>" alt="...">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                        <div>
                                            <span class="btn btn-white btn-file">
                                                <span class="fileinput-new">
                                                <?php echo get_phrase('select_image');?>
                                                </span>
                                                <span class="fileinput-exists"><?php echo get_phrase('change');?></span>
                                                <input id="idBack" type="file" name="id-back" accept="image/*">
                                            </span>
                                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove');?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-8">
                                    <input data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" type="text" class="form-control" name="name" value="<?php echo $row['name'];?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('birthday');?></label>
                                <div class="col-sm-8">
                                    <input data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" type="text" class="datepicker form-control" name="birthday" value="<?php echo $row['birthday'];?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('sex');?></label>
                                <div class="col-sm-8">
                                    <select data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" name="sex" class="form-control">
                                    	<option value="male" <?php if($row['sex'] == 'male')echo 'selected';?>><?php echo get_phrase('male');?></option>
                                    	<option value="female" <?php if($row['sex'] == 'female')echo 'selected';?>><?php echo get_phrase('female');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('address');?></label>
                                <div class="col-sm-8">
                                    <input data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" type="text" class="form-control" name="address" value="<?php echo $row['address'];?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('phone');?></label>
                                <div class="col-sm-8">
                                    <input data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" type="text" class="form-control" name="phone" value="<?php echo $row['phone'];?>"/>
                                </div>
                            </div>
                            <div class="form-group">
<label class="col-sm-4 control-label"><?php echo get_phrase('email');?></label>
<div class="col-sm-8">

<input id="email1" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" type="email" class="form-control" name="email" value="<?php echo $row['email'];?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('password');?></label>
                                <div class="col-sm-8">
                                    <input data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" type="text" class="form-control" name="password" value="<?php echo $row['password'];?>"/>
                                </div>
                            </div>
                            
                            
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                            
<button id="btn2" type="submit" class="btn btn-info"><?php echo get_phrase('edit_driver');?></button>

                            </div>
                        </div>
                <?php echo form_close();?>
            </div>
    </div>
</div>

<?php
endforeach;
?>

<script>

$('#email1').change(function(){
	
	get_email1();
	
});


function get_email1(){
	
	$('#email1').after('<span id="icon1" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	var email=$('#email1').val();
		$.ajax({
      type: 'POST',
       data: {email:email},
      url: "<?php echo base_url();?>?admin/call_function",
      dataType: "html",
      success: function(response) { 
if($.trim(response)=='yes'){

$("#btn2").attr('disabled','true');
$("#email1").css('border','1px solid red');
$("#icon1").remove();

if($('#message1').html()==undefined){
$("#email1").before('<p id="message1" style="color:red;"><?php echo get_phrase('email_address_is_already_exist'); ?></p>');	
}
}else{
	
$("#btn2").removeAttr('disabled');
$("#email1").css('border','1px solid green');
$("#icon1").remove();	
$("#message1").remove();	
}      
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