<script>
$(document).ready(function(){
	 $("#confime_password").blur(function () {
var new_password = $("#new_password").val();
            var confime_password = $("#confime_password").val();
            if (new_password != confime_password) {
            	
            	$('#new_password').val('');
    			$('#confime_password').val('');
    			//$("#btn_submit").hide();
    			$("#msg_div").show();
                return false;
            }
            if (new_password == confime_password) {
            	$("#btn_submit").show();
            	$("#btn_submit").removeAttr('disabled');
            }
            
        });
       $("#new_password").click(function () 
       {  
       	$("#msg_div").hide();
       });
  
});
</script>

<div class="col-md-12 alert">
	 <div class="alert alert-success" id="msg_div" style="display: none">
	 	Password does not match
	 </div>
	
</div>

<div class="col-md-12 alert">

             <?php
                   if($this->session->flashdata('err_message')){
                ?>
                        <div class="
                        <?php if($this->session->flashdata('err_message')=='Password Updated'){ 
                        
                       echo 'alert alert-success';  }else{
                        	echo 'alert  alert-danger';
                        	
                        } ?>"><center><?php echo $this->session->flashdata('err_message'); ?><center></div>
            
            </div>
            <?php 
             } ?>
<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<!--<ul class="nav nav-tabs bordered">

			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-user"></i> 
					<?php echo get_phrase('manage_profile');?>
                    	</a></li>
		</ul>-->
    	<!------CONTROL TABS END------->
        
	<?php
			/*
		<div class="tab-content">
        	<!----EDITING FORM STARTS---->
			<div class="tab-pane box active" id="list" style="padding: 5px">
			
			
                <div class="box-content">
					<?php 
                    foreach($edit_data as $row):
                        ?>
                        <?php echo form_open(base_url().'principal/manage_profile/update_profile_info' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('email');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="email" value="<?php echo $row['email'];?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('update_profile');?></button>
                              </div>
								</div>
                        </form>
						<?php
                    endforeach;
                    ?>
                </div>
                
			</div>
            <!----EDITING FORM ENDS--->
            
		</div>
	</div>
</div>
*/
                ?>

<!--password-->
<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs bordered">

			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-lock"></i> 
					<?php echo get_phrase('change_password');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------->
        
	
		<div class="tab-content">
        	<!----EDITING FORM STARTS---->
			<div class="tab-pane box active" id="list" style="padding: 5px">
                <div class="box-content padded">
					<?php 
                    foreach($edit_data as $row):
                        ?>
                        <?php echo form_open(base_url().'principal/manage_profile/change_password' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('current_password');?></label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" name="password" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('new_password');?></label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" id="new_password" name="new_password" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('confirm_new_password');?></label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" name="confirm_new_password" id="confime_password" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info" id="btn_submit" disabled><?php echo get_phrase('update_profile');?></button>
                              </div>
								</div>
                        </form>
						<?php
                    endforeach;
                    ?>
                </div>
			</div>
            <!----EDITING FORM ENDS--->
            
		</div>
	</div>
</div>