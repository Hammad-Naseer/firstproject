<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('driver_list');?>
                    	</a></li>
		    <li>
            	<a href="#add_driver" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_driver');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------->
        
	
		<div class="tab-content">
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane box active" id="list">
                <table class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('name');?></div></th>
                    		<th><div><?php echo get_phrase('email');?></div></th>
                    		<th><div><?php echo get_phrase('phone');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($transports_driver as $row):?>
                        <tr>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['email'];?></td>
                            <td><?php echo $row['phone'];?></td>
                            <td>
							<div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?php echo get_phrase('action');?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- PROFILE LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_driver_profile/<?php echo $row['driver_id'];?>');">
                                            <i class="entypo-camera"></i>
                                                <?php echo get_phrase('driver_profile');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_driver/<?php echo $row['driver_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/transport_driver/delete/<?php echo $row['driver_id'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                                    </li>
                                </ul>
                            </div>
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            <!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add_driver" style="padding: 5px">
            <div class="panel-body">
							<script>
				function validate(){
				var check=0;
				var photo=$("#photo").val();
                                var idFront=$("#idFront").val();
                                var idBack=$("#idBack").val();
				var Extension = photo.substring(photo.lastIndexOf('.') + 1).toLowerCase();
                                var Extension1 = idFront.substring(idFront.lastIndexOf('.') + 1).toLowerCase();
                                var Extension2 = idBack.substring(idBack.lastIndexOf('.') + 1).toLowerCase();
				if (Extension == "gif" || Extension == "png" || Extension == "bmp"
                    			|| Extension == "jpeg" || Extension == "jpg") {
						$('#photoError p').css('display','none');
					}

				else{
					$('#photoError p').css('display','block');
					var node4=document.createElement("P");
					var fileerror=document.createTextNode("Please Select Your Picture / Photo (must be a valid image file).");
					node4.appendChild(fileerror);
					if($('#photoError p').empty()){
					document.getElementById('photoError').appendChild(node4);
				}
					check++;
				}
                                if (Extension1 == "gif" || Extension1 == "png" || Extension1 == "bmp"
                    			|| Extension1 == "jpeg" || Extension1 == "jpg") {
						$('#idFrontError p').css('display','none');
					}

				else{
					$('#idFrontError p').css('display','block');
					var node4=document.createElement("P");
					var fileerror=document.createTextNode("Please Select Your I.D. Card front Photo (must be a valid image file).");
					node4.appendChild(fileerror);
					if($('#idFrontError p').empty()){
					document.getElementById('idFrontError').appendChild(node4);
				}
					check++;
				}
                                if (Extension2 == "gif" || Extension2 == "png" || Extension2 == "bmp"
                    			|| Extension2 == "jpeg" || Extension2 == "jpg") {
						$('#idBackError p').css('display','none');
					}

				else{
					$('#idBackError p').css('display','block');
					var node4=document.createElement("P");
					var fileerror=document.createTextNode("Please Select Your I.D. Card back Photo (must be a valid image file).");
					node4.appendChild(fileerror);
					if($('#idBackError p').empty()){
					document.getElementById('idBackError').appendChild(node4);
				}
					check++;
				}
				if(check>0){
					return false;
				}	
					}
	</script>	
                <?php echo form_open(base_url().'admin/transport_driver/create/' , array('id'=>'disable_submit_btn',
'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data','onSubmit'=>'return validate();'));?>
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('field_required');?>" value="" autofocus>
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('birthday');?></label>
                        
						<div class="col-sm-5">
                                                    <input type="text" class="form-control datepicker" data-validate="required" data-message-required="<?php echo get_phrase('field_required');?>" name="birthday" value="" readonly data-start-view="2">
						</div> 
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('gender');?></label>
                        
						<div class="col-sm-5">
							<select name="sex" data-validate="required" data-message-required="<?php echo get_phrase('field_required');?>" class="form-control">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <option value="male"><?php echo get_phrase('male');?></option>
                              <option value="female"><?php echo get_phrase('female');?></option>
                          </select>
						</div> 
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('address');?></label>
                        
						<div class="col-sm-5">
							<input type="text"  data-validate="required" data-message-required="<?php echo get_phrase('field_required');?>" class="form-control" name="address" value="" >
						</div> 
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>
                        
						<div class="col-sm-5">
							<input type="text"  data-validate="required" data-message-required="<?php echo get_phrase('field_required');?>" class="form-control" name="phone" value="" >
						</div> 
					</div>
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('email');?></label>
						<div class="col-sm-5">
							<input id="email" type="email" data-validate="required" data-message-required="<?php echo get_phrase('field_required');?>" class="form-control" name="email" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('password');?></label>
                        
						<div class="col-sm-5">
							<input type="password" data-validate="required" data-message-required="<?php echo get_phrase('field_required');?>" class="form-control" name="password" value="" >
						</div> 
					</div>
	
					<div class="form-group" >
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>
                        
						<div class="col-sm-5" id="photoError">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="http://placehold.it/200x200" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new"> <?php echo get_phrase('select_image');?></span>
										<span class="fileinput-exists"> <?php echo get_phrase('change');?></span>
										<input id="photo" type="file" accept="image/*" name="userfile">
									</span>
									<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"> <?php echo get_phrase('remove');?></a>
								</div>
							</div>

                                                </div>
                                                
					</div>
                
                <div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('ID_card_front');?></label>
                        
						<div class="col-sm-5" id="idFrontError">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="http://placehold.it/200x200" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new"> <?php echo get_phrase('select_image');?></span>
										<span class="fileinput-exists"> <?php echo get_phrase('change');?></span>
										<input  id="idFront" type="file" name="id-front" accept="image/*">
									</span>
									<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"> <?php echo get_phrase('remove');?></a>
								</div>
							</div>
						</div>
					</div>
                
                <div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('I.D. Card back');?></label>
                        
						<div class="col-sm-5" id="idBackError">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="http://placehold.it/200x200" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new"> <?php echo get_phrase('select_image');?></span>
										<span class="fileinput-exists"> <?php echo get_phrase('change');?></span>
										<input id="idBack" type="file" name="id-back" accept="image/*">
									</span>
									<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"> <?php echo get_phrase('remove');?></a>
								</div>
							</div>

						</div>
					</div>
                
                
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button id="btn1" type="submit" class="btn btn-info"><?php echo get_phrase('add_driver');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
            </div>
			<!----CREATION FORM ENDS--->

		</div>
	</div>
</div>

<script>

$('#email').change(function(){
	
	get_email();
	
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
if(response=='yes'){

$("#btn1").attr('disabled','true');
$("#email").css('border','1px solid red');
$("#icon").remove();

if($('#message').html()==undefined){
$("#email").before('<p id="message" style="color:red;"><?php echo get_phrase(''); ?></p>');	
}
}else{
	
$("#btn1").removeAttr('disabled');
$("#email").css('border','1px solid green');
$("#icon").remove();	
$("#message").remove();	
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