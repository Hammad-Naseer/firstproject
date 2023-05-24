
<style>
.toggle{position:relative;display:block;width:40px;height:20px;cursor:pointer;-webkit-tap-highlight-color:transparent;transform:translate3d(0,0,0)}.toggle:before{content:"";position:relative;top:3px;left:3px;width:34px;height:14px;display:block;background:#9a9999;border-radius:8px;transition:background .2s ease}.toggle span{position:absolute;top:0;left:0;width:20px;height:20px;display:block;background:#0992c9;border-radius:10px;box-shadow:0 3px 8px rgba(154,153,153,.5);transition:all .2s ease}.toggle span:before{content:"";position:absolute;display:block;margin:-18px;width:56px;height:56px;background:rgba(79,46,220,.5);border-radius:50%;transform:scale(0);opacity:1;pointer-events:none}.checkboxSms:checked+.toggle:before{background:#947ada}.checkboxSms:checked+.toggle span{background:#4f2edc;transform:translateX(20px);transition:all .2s cubic-bezier(.8,.4,.3,1.25),background .15s ease;box-shadow:0 3px 8px rgba(79,46,220,.2)}.checkboxSms:checked+.toggle span:before{transform:scale(1);opacity:0;transition:all .4s ease}.center{position:absolute;left:calc(20% - 20px)}
.sidebar-menu.desktop-sidebar
{
    display:none;
}
ul.dropdown-menu.profile_dropdown.show {
    display: none;
}
/*i.entypo-bell {*/
/*    display: none;*/
/*}*/
li.notifications {
    display: none;
}
button[disabled], html input[disabled] {
    cursor: no-drop;
    background: #0073b7a6 !important;
    border: 1px solid #59a4d0 !important;
}

.check_icon{
    font-size: 150px;
    color: green;
    text-shadow: 10px 10px 20px #ccc;
}
</style>

<?php  if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
    }
  ?>
<script>
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                <?php echo get_phrase('sms_and_email_verification');?>
            </h3>
        </div>
    </div>
    
    <div class="">
        <div class="row">
            <?php 
           
                $d_school_id = $_SESSION['sys_sch_id'];
                $school_details = get_school_details($d_school_id);
                $check_email_verification = $this->db->query("SELECT is_sms_verify,is_email_verify FROM ".get_system_db().".system_school WHERE sys_sch_id = '$d_school_id'")->row();
                if($check_email_verification->is_sms_verify == '1' && $check_email_verification->is_email_verify == '1'){ redirect(base_url().'admin'); }
            ?>  
            <div class="col-sm-12" >
                <!--EMAIL AREA-->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4> <span>Step 1</span> <i class="fa fa-arrow-right"></i> <b><?php echo get_phrase('email_verification');?></b></h4>
                        <?php
                            if($check_email_verification->is_email_verify == '0'){
                        ?>
                        <form id="verify_email" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" enctype="multipart/form-data">
                            <div class="row" style="margin-left: 38px;">
                                <div class="col-lg-12 col-sm-12 email_success_msg"></div>
                                <div class="col-sm-12">
                                    <label>Enter You Email</label>
                                    <input type="email" class="form-control" name="email" value="<?=$_SESSION['user_email']?>" readonly required>
                                    <input type="hidden" name="sch_name" value="<?=$school_details['name']?>">
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <button type="submit" id="main_btn" name="submit" class="modal_save_btn verify_email_btn"><?php echo get_phrase('verify_email');?></button>
                                </div>
                            </div>
                        </form>
                        <!--Code Verify-->
                        <form id="verify_email_code" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" enctype="multipart/form-data">
                            <div class="row email_code_verify" style="margin-left: 38px;display:none;">
                                <div class="col-sm-12">
                                    <label>Enter Email Code Here</label>
                                    <input type="text" class="form-control" name="email_code" required>
                                    <input type="hidden" name="sch_name" value="<?=$school_details['name']?>">
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <button type="submit" id="main_btn" name="submit" class="modal_save_btn verify_email_code_btn"><?php echo get_phrase('email_code_verify');?></button>
                                </div>
                            </div> 
                        </form>
                        <?php }else{ ?>
                            <div class="text-center">
                                <i class="far fa-check-circle check_icon"></i>
                                <p><b style="font-size: 20px;">Email Verified</b></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                    
                <!--SMS AREA-->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4> <span>Step 2</span> <i class="fa fa-arrow-right"></i> <b><?php echo get_phrase('mobile_no_verification');?></b></h4>
                        <?php
                            if($check_email_verification->is_sms_verify == '0'){
                        ?>
                        <form id="verify_sms" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" enctype="multipart/form-data">
                            <div class="row" style="margin-left: 38px;">
                                <div class="col-lg-12 col-sm-12 sms_success_msg"></div>
                                <div class="col-sm-12">
                                    <label>Enter You Mobile No</label>
                                    <input type="number" class="form-control" name="phone_no" value="<?=$school_details['phone']?>" required>
                                    <input type="hidden" name="sch_name" value="<?=$school_details['name']?>">
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <button type="submit" id="main_btn" name="submit" class="modal_save_btn verify_sms_btn"><?php echo get_phrase('verify_mobile_no');?></button>
                                </div>
                            </div>
                        </form>
                        <!--Code Verify-->
                        <form id="verify_sms_code" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" enctype="multipart/form-data">
                            <div class="row sms_code_verify" style="margin-left: 38px;display:none;">
                                <div class="col-sm-12">
                                    <label>Enter SMS OTP Here</label>
                                    <input type="password" class="form-control" name="sms_code" required>
                                    <input type="hidden" name="sch_name" value="<?=$school_details['name']?>">
                                    <input type="hidden" name="phone_no" value="<?=$school_details['phone']?>">
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <button type="submit" id="main_btn" name="submit" class="modal_save_btn verify_sms_code_btn"><?php echo get_phrase('OTP_code_verify');?></button>
                                </div>
                            </div> 
                        </form>
                        <?php }else{ ?>
                            <div class="text-center">
                                <i class="far fa-check-circle check_icon"></i>
                                <p><b style="font-size: 20px;">Mobile No Verified</b></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script>
    $("#verify_email").on("submit",function(e){
        e.preventDefault();
        $.ajax({    
            url: '<?= base_url() ?>admin/email_sent_for_verification',
            method: 'POST',
            data:new FormData(this),  
            contentType: false,  
            cache: false,  
            processData:false,
            beforeSend:function(){
                $('.verify_email_btn').attr('disabled','disabled');
            },
            success:function(msg)
            {
                $('.email_success_msg').html(msg);
                $('.verify_email_btn').attr('disabled','disabled');
                $('.verify_email_btn').val('Email Sent');
                setTimeout(function(){ $(".email_code_verify").css("display","block"); }, 2000);
            }
        });
    });
    
    $("#verify_email_code").on("submit",function(e){
        e.preventDefault();
        $.ajax({    
            url: '<?= base_url() ?>admin/email_code_check_for_verification',
            method: 'POST',
            data:new FormData(this),  
            contentType: false,  
            cache: false,  
            processData:false,
            beforeSend:function(){
                $('.verify_email_code_btn').attr('disabled','disabled');
            },
            success:function(msg)
            {
                trim_msg = $.trim(msg);
                // alert(trim_msg);
                if(trim_msg == "1")
                {
                    $('.email_success_msg').html("<div class='alert alert-success'>Email Verification Successfully Completed</div>");
                    setTimeout(function(){ window.location.href = '<?=base_url()?>admin'; }, 2000);
                }else
                {
                    $('.email_success_msg').html("<div class='alert alert-danger'>Email Verification Code Not Correct</div>");
                }
                
                // $('.email_success_msg').html(msg);
                $('.verify_email_code_btn').attr('enabled','enabled');
                // setTimeout(function(){ location.relaod(); }, 3000);
            }
        });
    });
    
    $("#verify_sms").on("submit",function(e){
        e.preventDefault();
        $.ajax({    
            url: '<?= base_url() ?>admin/sms_sent_for_verification',
            method: 'POST',
            data:new FormData(this),  
            contentType: false,  
            cache: false,  
            processData:false,
            beforeSend:function(){
                $('.verify_sms_btn').attr('disabled','disabled');
            },
            success:function(msg)
            {
                $('.sms_success_msg').html(msg);
                $('.verify_sms_btn').attr('disabled','disabled');
                $('.verify_sms_btn').val('SMS Sent');
                setTimeout(function(){ $(".sms_code_verify").css("display","block"); }, 2000);
            }
        });
    });
    
    $("#verify_sms_code").on("submit",function(e){
        e.preventDefault();
        $.ajax({    
            url: '<?= base_url() ?>admin/sms_code_check_for_verification',
            method: 'POST',
            data:new FormData(this),  
            contentType: false,  
            cache: false,  
            processData:false,
            beforeSend:function(){
                $('.verify_sms_code_btn').attr('disabled','disabled');
            },
            success:function(msg)
            {
                trim_msg = $.trim(msg);
                // alert(trim_msg);
                if(trim_msg == 1)
                {
                    $('.sms_success_msg').html("<div class='alert alert-success'>SMS Verification Successfully Completed</div>");
                    setTimeout(function(){ window.location.href = '<?=base_url()?>admin'; }, 2000);
                }else
                {
                    $('.sms_success_msg').html("<div class='alert alert-danger'>SMS Verification Code Not Correct</div>");
                }
                
                $('.verify_sms_code_btn').removeAttr('disabled','disabled');
            }
        });
    });
    
</script>

