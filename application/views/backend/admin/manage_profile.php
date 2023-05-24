<script>
	$( window ).on("load",function() {
        setTimeout(function() {
            $('#hidealert').fadeOut();
        }, 3000);
    });
</script>
<style>
    .pp{
    list-style: none;
    padding: 47px;

    }
</style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="profile_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('my_profile'); ?>
            </h3>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="profile-env">
			<header class="row">
				<div class="col-sm-2">
					<a href="#" class="profile-picture">
					    <?php if($res[0]['profile_pic']=="") { ?>
    					    <img src="<?php echo get_default_pic()?>" class="img-responsive img-circle" /> 
    					<?php } else {?>
        					<img src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1)?>" class="img-responsive img-circle" />
    					<?php } ?>
					</a>
				</div>
				<div class="col-sm-7">
					<ul class="pp">
						<li>
							<div>
								<strong>
									<h3>Display Name : <?php echo $res[0]['name'];?></h3>
								</strong>
							</div>
						</li>
						<li>
						    <div>
						    	<strong>
								<h3>Status : Online </h3> <!--<a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Online"></a>-->
								</strong>
							</div>
						</li>
						<li>
							<div>
							    <strong>
								<h3>Account Type : <?= ucfirst(get_login_type_name($_SESSION["login_type"])); ?></h3>
								</strong>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-sm-3">
					<div class="profile-buttons">
						<a href="<?php echo base_url();?>login/logout" class="btn btn-default">
							<i class="entypo-user-add text-white" style="color:white !important;"></i>
							Logout
						</a>
					</div>
				</div>
			</header>
			
		</div>
	</div>
    <div id="hidealert">
         <?php if($this->session->flashdata('flash_message')){?>
            <div class="
            <?php if($this->session->flashdata('flash_message')=='
            <?php echo get_phrase("password_updated");?>')
            {
                echo 'alert alert-success';
            }
            elseif($this->session->flashdata('flash_message')=='<?php echo get_phrase("record_updated");?>')
            {
                echo 'alert alert-success';
            }else{
                echo 'alert  alert-danger';
            } ?>"><center><?php echo $this->session->flashdata('flash_message'); ?></center></div>
     <?php } ?>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 b-s mt-4 p-3">
            <div data-step="1" data-position='top' data-intro="change your name & profile image">
                <h3 class="system_name inline capitalize mt-4" ><?php echo get_phrase('change_display_name_&_profile_image');?></h3>   
            </div>
		     <div class="col-lg-12 col-md-12 col-sm-12">
    		<?php 
            foreach($edit_data as $row){
    			//data-message-required="Value Required"
                ?>
                <?php echo form_open_multipart(base_url().'profile/my_profile' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="control-label" style="float:left;"><?php echo get_phrase('display_name');?><span class="red"> * </span></label>
                            <input type="text" class="form-control" name="display_name" value="<?php echo $row['name'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                    <label  class="control-label" style="visibility:hidden; float:left;"><?php echo get_phrase('display_picture');?></label>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                        <input type="hidden" name="image_file" value="<?php echo  $row['profile_pic']; ?>"/>
                        <div class="fileinput-new thumbnail" style="height: 130px;width: 130px;border-radius: 50% !important;" data-trigger="fileinput" id="student_image">
                            <img class="img-responsive img-circle" src="<?php if($row['profile_pic']==''){ echo  base_url().'/uploads/default.png'; }else{echo  display_link($row['profile_pic'],'profile_pic',1);}?>" >
                        </div>
                        <br>
                    	<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                        	<div>
            					<span class="btn btn-white btn-file" style="margin-top: 0px !important;margin-left:25px !important">
            						<span class="fileinput-new"><?php echo get_phrase('select_image');?></span>
            						<span class="fileinput-exists"><?php echo get_phrase('change');?></span>
            						<input type="file" name="userfile" accept="image/*">
            					</span>
            					<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove');?></a>
            				</div>
                    	</div>
                    </div>
    		        </div>
                   <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <button id="btn_sub" type="submit" class="btn btn-info"><?php echo get_phrase('update_profile');?></button>
    				</div>
    				</div>
                <?php echo form_close(); } ?>
		</div>	
	    </div>
	</div>
	<div class="row">
        <div class="col-lg-12 col-sm-12 b-s mt-4 p-3" data-step="2" data-position='top' data-intro="change your password">
            <h3 class="system_name inline capitalize" ><?php echo get_phrase('change_password');?></h3>	    
        	<div class="box-content change-password">
    			<?php foreach($edit_data as $row): ?>
                    <?php echo form_open_multipart(base_url().'profile/manage_profile/change_password' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 form-group p-4 border-0" style="margin-left: -0px;">
                                <label class="control-label"><?php echo get_phrase('current_password');?><span class="red"> * </span></label>
                                <input type="password" class="form-control" name="password" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 form-group p-4 border-0" style="margin-left: -0px;">
                                <label class="control-label"><?php echo get_phrase('new_password');?><span class="red"> * </span></label>
                                <span id="message" style="color: red;"></span>             
                                <input type="password" id="new_password" class="form-control edu_password_validation" name="new_password" value="" data-validate="required" data-message-required="Value Required"/>
                                <span class="text-danger edu_password_validation_msg"></span><br><br>
                            </div> 
                            <div class="col-lg-4 col-md-4 col-sm-4 form-group p-4 border-0" style="margin-left: -0px;">
                                <label class="control-label"><?php echo get_phrase('confirm_new_password');?><span class="red"> * </span></label>
                                <input type="password" class="form-control " id="confime_password" name="confirm_new_password" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <button id="btn_submit" type="submit" class="btn btn-info"><?php echo get_phrase('change_password');?></button>
                            </div>
                        </div>    
                    </form>
    			<?php endforeach;?>
            </div>
        </div>
    </div>
        
<script>
$(document).ready(function(){
	 $("#confime_password").on("keyup",function () {
        var check_pass= $("#new_password").val();
        var confime_password_ = $("#confime_password").val();
        
        if(check_pass=="" || confime_password_==""){
            $('button[type="submit"]').attr("disabled");
        }else{
            pass_validation();
        }
    });
        
    $("#new_password").on(function () {
        var check_pass= $("#new_password").val();
        var confime_password_ = $("#confime_password").val();
        
        if(check_pass=="" || confime_password_==""){
            $("#btn_submit").attr('disabled','true');
        }else{
            pass_validation();
        }
    });
});

function pass_validation(){
    var new_password = $("#new_password").val();
    var confime_password = $("#confime_password").val();
    if (new_password != confime_password) {
        $('#new_password').css('border','1px solid red');
        $('#confime_password').css('border','1px solid red');
        $('#message').html('<?php echo get_phrase("password_is_mismatch");?>');
        
        $("#btn_submit").attr('disabled','true');
        return false;
    }
    if (new_password == confime_password) {
        $("#btn_submit").show();
        $("#btn_submit").removeAttr('disabled');
        $('#new_password').css('border','1px solid green');
        $('#confime_password').css('border','1px solid green');
        $('#message').html('');
    }
}
</script>