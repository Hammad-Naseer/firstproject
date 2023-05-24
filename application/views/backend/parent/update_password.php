<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('Update_password'); ?>
        </h3>
    </div>
</div>
<?php
    if($this->session->flashdata('flash_message'))
    {
        echo "<script>sweet_message('".$this->session->flashdata('flash_message')."','success')</script>";
    }
    
    if($this->session->flashdata('club_message'))
    {
        echo "<script>sweet_message('".$this->session->flashdata('club_message')."','error')</script>";
    }
?>

<div class="col-lg-12 col-md-12 col-sm-12">
	    <div class="box-content change-password">
                    <?php echo form_open_multipart(base_url().'parents/change_password' , array('class' => 'form-horizontal','target'=>'_top'));?>
                        <?php if($_SESSION['mobile_code'] > 0){ ?>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                            	<div class="form-group">
                                    <label class="col-sm-12 control-label"><?php echo get_phrase('OTP code');?><small> (sent on your mobile)</small></small><span class="red"> * </span></label>
                                    <span id="message" style="color: red;"></span>             
                                    <input type="text" id="otp" class="form-control" autocomplete="off" name="otp" required/>
                                </div>
                            </div>       
                        </div>
                        <?php } ?>
                           <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                        	<div class="form-group">
                                <label class="col-sm-12 control-label"><?php echo get_phrase('new_password');?><span class="red"> * </span></label>
                                   
                      <span id="message" style="color: red;"></span>             
                                    <input type="password" id="new_password" autocomplete="off" class="form-control" name="new_password" required/>
                            </div>
                        </div>       </div>
                        
                        
                           <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"><?php echo get_phrase('confirm_new_password');?><span class="red"> * </span></label>
                                    <input type="password" class="form-control" autocomplete="off" id="confime_password" name="confirm_new_password" required/>
                            </div>
                        </div>    </div>
                        <?php 
                            $parent_exist = $this->db->query("select * from ".get_system_db().". user_login 
                            where  id_no = '".$_SESSION[$_SESSION['student_id']]."' ")->result_array();
                        ?>
                        <input type="hidden" name="student_login_id" value="<?php echo $parent_exist[0]['user_login_id'];?>">
                        
                        <div class="row">
                             <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                  <div >
                                    <button id="btn_submit" type="submit" class="btn btn-info"><?php echo get_phrase('update_password');?></button>
                                  </div>
        						</div>
                             </div>
                        </div>     
                        
                        
    </div>
                    </form>
					<?php
                //endforeach;
                ?>
            </div>
	
</div>



<script>
$(document).ready(function(){
	 $("#confime_password").on(function () {
	  var check_pass= $("#new_password").val();
var confime_password_ = $("#confime_password").val();
	 
	 if(check_pass=="" || confime_password_==""){
	 	$("#btn_submit").attr('disabled','true');
	 }
	 
	 else{
	 	
	 
	 
	 pass_validation();
     }
        });
        
              
        
      $("#new_password").on(function () {
	 
	  var check_pass= $("#new_password").val();
var confime_password_ = $("#confime_password").val();
	 
	 if(check_pass=="" || confime_password_==""){
	 $("#btn_submit").attr('disabled','true');
	 }
	 
	 else{
	 	
	 
	 
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
  $('#message').html('<?php echo get_phrase('password_mismatch'); ?>');
    	
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