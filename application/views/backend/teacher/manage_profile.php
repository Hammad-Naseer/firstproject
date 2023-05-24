<script>
	$( window ).on("load",function() {
        setTimeout(function() {
            $('#hidealert').fadeOut();
        }, 3000);
    });
</script>
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
					    <?php if($edit_data[0]['profile_pic']=="") { ?>
    					    <img src="<?php echo get_default_pic()?>" class="img-responsive img-circle" /> 
    					<?php } else {?>
        					<img src="<?php echo display_link($edit_data[0]['profile_pic'],'profile_pic',1)?>" class="img-responsive img-circle" />
    					<?php } ?>
					</a>
				</div>
				<div class="col-sm-6">
					<ul class="profile-info-sections">
						<li>
							<div class="profile-name">
								<strong>
									<a href="#"><?php echo $edit_data[0]['name'];?></a>
									<a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Online"></a>
									<!-- User statuses available classes "is-online", "is-offline", "is-idle", "is-busy" -->						</strong>
									 <?php
                                            $my_rating = $this->db->query("select AVG(rating) as average_rating from " . get_school_db() . ".teacher_rating WHERE teacher_id = ".$_SESSION['user_id']." AND school_id =" . $_SESSION['school_id']."")->row();
                                            if(count($my_rating)>0){
                                            ?>
                                                <br>
                                                <div class="avg">
                                                    <div class='rating-stars' style="font-size: 7px;">
                                                        <?php
                                                            $style_avg = 0;
                                                            if($my_rating->average_rating > 0 And $my_rating->average_rating < 1)
                                                                $style_avg = $my_rating->average_rating*100;
                                                            if($my_rating->average_rating > 1 And $my_rating->average_rating < 2)
                                                                $style_avg = ($my_rating->average_rating - 1)*100;
                                                            if($my_rating->average_rating > 2 And $my_rating->average_rating < 3)
                                                                $style_avg = ($my_rating->average_rating - 2)*100;
                                                            if($my_rating->average_rating > 3 And $my_rating->average_rating < 4)
                                                                $style_avg = ($my_rating->average_rating - 3)*100;
                                                            if($my_rating->average_rating > 4 And $my_rating->average_rating < 5)
                                                                $style_avg = ($my_rating->average_rating - 4)*100;
                                                            $style_avg = 100 - $style_avg;
                                                            $style = 'background: -webkit-linear-gradient(180deg, #ccc '.$style_avg.'%, #FF912C 0%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;';
                                                        ?>
                                                        <ul id='stars'>
                                                            <li class='star-item <?php echo ($my_rating->average_rating>0)?'selected':'';?>' title='Poor'>
                                                                <i class='fa fa-star fa-fw' style="<?php echo ($my_rating->average_rating>0 And $my_rating->average_rating < 1)?$style:'';?>padding-right:0px;"></i>
                                                            </li>
                                                            <li class='star-item <?php echo ($my_rating->average_rating>1)?'selected':'';?>' title='Fair'>
                                                                <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>1 And $my_rating->average_rating < 2)?$style:'';?> padding-right:0px;"></i>
                                                            </li>
                                                            <li class='star-item <?php echo ($my_rating->average_rating>2)?'selected':'';?>' title='Good'>
                                                                <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>2 And $my_rating->average_rating < 3)?$style:'';?>padding-right:0px;"></i>
                                                            </li>
                                                            <li class='star-item <?php echo ($my_rating->average_rating>3)?'selected':'';?>' title='Excellent'>
                                                                <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>3 And $my_rating->average_rating < 4)?$style:'';?>padding-right:0px;"></i>
                                                            </li>
                                                            <li class='star-item <?php echo ($my_rating->average_rating>4)?'selected':'';?>' title='Awesome'>
                                                                <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>4 And $my_rating->average_rating < 5)?$style:'';?>padding-right:0px;"></i>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <p>Average Rating:  <?php echo round($my_rating->average_rating, 2)?></p>
                                                </div>
                                        <?php }?>
									
									
							</div>
						</li>
						<!--<li>-->
						<!--	<div class="profile-stat">-->
						<!--		<h3> ----- </h3>-->
						<!--		<span><a href="#">Class - Section</a></span>-->
						<!--	</div>-->
						<!--</li>-->
						
						<li>
							<div class="profile-stat">
								<h3><?= ucfirst(get_login_type_name($_SESSION["login_type"])); ?></h3>
								<span><a href="#">Account Type</a></span>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-sm-4">
					<div class="profile-buttons">
						<a href="<?php echo base_url();?>login/logout" class="btn btn-default">
							<i class="entypo-lock text-white" style="color:white !important;"></i>
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
            <div data-step="1" data-position='top' data-intro="Change your name and upload a new image and press Update Profile button to change username and image">
                <h3 class="system_name inline capitalize mt-4" ><?php echo get_phrase('change_username_&_profile_image');?></h3>   
            </div>
		     <div class="col-lg-12 col-md-12 col-sm-12">
    		<?php 
            foreach($edit_data as $row){
    			//data-message-required="Value Required"
                ?>
                <?php echo form_open_multipart(base_url().'teacher/my_profile' , array('class' => 'form-horizontal','target'=>'_top'));?>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label class="control-label" style="float:left;"><?php echo get_phrase('display_name');?><span class="red"> * </span></label>
                            <input type="text" class="form-control" name="display_name" value="<?php echo $row['name'];?>" required />
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                    <label  class="control-label" style="visibility:hidden; float:left;"><?php echo get_phrase('display_picture');?></label>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                        <input type="hidden" name="image_file" value="<?php echo  $row['profile_pic']; ?>"/>
                        <div class="fileinput-new thumbnail" style="height: 130px;width: 130px;border-radius: 50%;" data-trigger="fileinput" id="student_image">
                            <img src="<?php if($row['profile_pic']==''){ echo  base_url().'/uploads/default_pic.png'; }else{echo  display_link($row['profile_pic'],'profile_pic',1);}?>" >
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
                   <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <button id="btn_sub" type="submit" class="btn btn-info"><?php echo get_phrase('update_profile');?></button>
    				</div>
    				</div>
                <?php echo form_close(); } ?>
		</div>	
	    </div>
	</div>
	<div class="row">
        <div class="col-lg-12 col-sm-12 b-s mt-4 p-3" data-step="2" data-position='top' data-intro="Enter your current password,new password and confirm new password and press Change Password to change your password">
            <h3 class="system_name inline capitalize" ><?php echo get_phrase('change_password');?></h3>	    
        	<div class="box-content change-password">
    			<?php foreach($edit_data as $row): ?>
                    <?php echo form_open_multipart(base_url().'teacher/manage_profile/change_password' , array('class' => 'form-horizontal','target'=>'_top'));?>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 form-group" style="margin-left: -0px;">
                                <label class="control-label"><?php echo get_phrase('current_password');?><span class="red"> * </span></label>
                                <input type="password" class="form-control" name="password" required/>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 form-group" style="margin-left: -0px;">
                                <label class="control-label"><?php echo get_phrase('new_password');?><span class="red"> * </span></label>
                                <span id="message" style="color: red;"></span>             
                                <input type="password" id="new_password" class="form-control edu_password_validation" name="new_password" required/>
                                <span class="text-danger edu_password_validation_msg"></span><br><br>
                            </div> 
                            <div class="col-lg-4 col-md-4 col-sm-4 form-group" style="margin-left: -0px;">
                                <label class="control-label"><?php echo get_phrase('confirm_new_password');?><span class="red"> * </span></label>
                                <input type="password" class="form-control" id="confime_password" name="confirm_new_password" required/>
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
	 $("#confime_password").on("keyup" , function () {
        var check_pass= $("#new_password").val();
        var confime_password_ = $("#confime_password").val();
        
        if(check_pass=="" || confime_password_==""){
            $("#btn_submit").attr('disabled','true');
        }else{
            pass_validation();
        }
    });
        
    $("#new_password").on("keyup" ,function () {
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