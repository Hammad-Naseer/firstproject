
<style>
.toggle{position:relative;display:block;width:40px;height:20px;cursor:pointer;-webkit-tap-highlight-color:transparent;transform:translate3d(0,0,0)}.toggle:before{content:"";position:relative;top:3px;left:3px;width:34px;height:14px;display:block;background:#9a9999;border-radius:8px;transition:background .2s ease}.toggle span{position:absolute;top:0;left:0;width:20px;height:20px;display:block;background:#0992c9;border-radius:10px;box-shadow:0 3px 8px rgba(154,153,153,.5);transition:all .2s ease}.toggle span:before{content:"";position:absolute;display:block;margin:-18px;width:56px;height:56px;background:rgba(79,46,220,.5);border-radius:50%;transform:scale(0);opacity:1;pointer-events:none}.checkboxSms:checked+.toggle:before{background:#947ada}.checkboxSms:checked+.toggle span{background:#4f2edc;transform:translateX(20px);transition:all .2s cubic-bezier(.8,.4,.3,1.25),background .15s ease;box-shadow:0 3px 8px rgba(79,46,220,.2)}.checkboxSms:checked+.toggle span:before{transform:scale(1);opacity:0;transition:all .4s ease}.center{position:absolute;left:calc(20% - 20px)}.col-set-width .col-sm-6{width:50%;}</style>

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
<?php
    $query=$this->db->get_where(get_school_db().'.sms_settings' , array('school_id' =>$_SESSION['school_id']))->result_array();
    $val_ary=array();
    foreach($query as $rows){
        $val_ary[$rows['sms_status']]=array('sms_f_id'=>$rows['sms_f_id'],'sms_details'=>$rows['sms_details'],'email_status'=>$rows['email_status'],'status'=>$rows['status']);
    }
    {
?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <!--<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">-->
            <!--    <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>-->
            <!--</a>-->
            <h3 class="system_name inline">
                <?php echo get_phrase('preferences');?>
            </h3>
        </div>
    </div>
    
    <!--    <form action="<?php // echo base_url(); ?>preferences/save_preferences" id="disable_submit_btn" 
            method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" enctype="multipart/form-data">  -->
        <div class="">
            <div class="row">  
                <div class="col-sm-12" >
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4><b><?php echo get_phrase('SMS_settings');?></b></h4>
                            <div class="row col-set-width">
                                <!--Challan Issue-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_challan_issue');?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_4" name="challanIds" data-section="4" data-type="sms_status" value="<?= check_sms_preference(4,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(4,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_4" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Challan Recieve-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_challan_recieve'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_1" name="challanIds" data-section="1" data-type="sms_status" value="<?= check_sms_preference(1,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(1,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_1" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                
                                <?php if(right_granted(array('create_assessment','view_assessment'))){ ?>
                                <!--Assesment Assign-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_assessment_assign');?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_2" name="challanIds" data-section="2" data-type="sms_status" value="<?= check_sms_preference(2,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(2,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_2" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                
                                <?php } if(right_granted(array('managediary_manage'))){ ?>
                                <!--Diary Assign-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_diary_assign');?></label>
                                </div> 
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_3" name="challanIds" data-section="3" data-type="sms_status" value="<?= check_sms_preference(3,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(3,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_3" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <?php }?>
                                <!--Student Attendance Mark-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_student_attendance_mark');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_6" name="challanIds" data-section="5" data-type="sms_status" value="<?= check_sms_preference(5,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(5,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_6" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--General Inqyuiry-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_general_inquiry');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_7" name="challanIds" data-section="6" data-type="sms_status" value="<?= check_sms_preference(6,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(6,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_7" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Job Application Resposne-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_job_resposne');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_10" name="challanIds" data-section="7" data-type="sms_status" value="<?= check_sms_preference(7,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(7,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_10" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Fee Recovery Reminder-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_fee_recovery_reminder');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_11" name="challanIds" data-section="8" data-type="sms_status" value="<?= check_sms_preference(8,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(8,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_11" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Students Credentials-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_students_credentials_send');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_13" name="challanIds" data-section="9" data-type="sms_status" value="<?= check_sms_preference(9,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(9,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_13" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Manage Students Leave-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_manage_students_leave');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_14" name="challanIds" data-section="12" data-type="sms_status" value="<?= check_sms_preference(12,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(12,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_14" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Manage Staff Leave-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_manage_staff_leave');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_15" name="challanIds" data-section="13" data-type="sms_status" value="<?= check_sms_preference(13,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(13,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_15" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Circulars-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_circulars');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_16" name="challanIds" data-section="10" data-type="sms_status" value="<?= check_sms_preference(10,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(10,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_16" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Staff Circulars-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_staff_circulars');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_17" name="challanIds" data-section="11" data-type="sms_status" value="<?= check_sms_preference(11,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(11,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_17" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                
                                <!--Staff Attendance-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_staff_attendance_mark');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_20" name="challanIds" data-section="14" data-type="sms_status" value="<?= check_sms_preference(14,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(14,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_20" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                
                                <!--School Vacation-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_school_vacation');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_23" name="challanIds" data-section="15" data-type="sms_status" value="<?= check_sms_preference(15,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(15,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_23" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                
                                <!--Birtthday Wishes-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('sms_on_school_vacation');?></label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_25" name="challanIds" data-section="16" data-type="sms_status" value="<?= check_sms_preference(16,"value","sms") ?>" class="checkboxSms" <?= check_sms_preference(16,"check","sms") ?> style="display:none"/>
                                      <label for="cbx_25" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                
                                

                            </div>
                            <!--/******************-->
                            <!--EMAIL AREA -->
                            <!--******************/-->
                            <h4><b><?php echo get_phrase('email_settings');?></b></h4>
                            <div class="row col-set-width">
                                <!--Challan Issue-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_challan_issue');?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_5" name="challanIds" data-section="4" data-type="email_status" value="<?= check_sms_preference(4,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(4,"check","email") ?> style="display:none"/>
                                      <label for="cbx_5" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Challan Recieve-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_challan_recieve'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_11" name="challanIds" data-section="1" data-type="email_status" value="<?= check_sms_preference(1,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(1,"check","email") ?> style="display:none"/>
                                      <label for="cbx_11" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Diary Assign-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_diary_assign'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_21" name="challanIds" data-section="3" data-type="email_status" value="<?= check_sms_preference(3,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(3,"check","email") ?> style="display:none"/>
                                      <label for="cbx_21" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--General Inquiry-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_general_inquiry'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_8" name="challanIds" data-section="6" data-type="email_status" value="<?= check_sms_preference(6,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(6,"check","email") ?> style="display:none"/>
                                      <label for="cbx_8" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Job Application Resposne-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_job_resposne'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_9" name="challanIds" data-section="7" data-type="email_status" value="<?= check_sms_preference(7,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(7,"check","email") ?> style="display:none"/>
                                      <label for="cbx_9" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Fee Recovery Reminder-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_fee_recovery_reminder'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_12" name="challanIds" data-section="8" data-type="email_status" value="<?= check_sms_preference(8,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(8,"check","email") ?> style="display:none"/>
                                      <label for="cbx_12" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Circulars-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_circulars'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_18" name="challanIds" data-section="10" data-type="email_status" value="<?= check_sms_preference(10,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(10,"check","email") ?> style="display:none"/>
                                      <label for="cbx_18" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--Staff Circulars-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_staff_circulars'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_19" name="challanIds" data-section="11" data-type="email_status" value="<?= check_sms_preference(11,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(11,"check","email") ?> style="display:none"/>
                                      <label for="cbx_19" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                <!--School Vacation-->
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('email_on_school_vacation'); ?></label>
                                </div>        
                                <div class="col-sm-6">
                                    <div class="center">
                                      <input type="checkbox" id="cbx_24" name="challanIds" data-section="15" data-type="email_status" value="<?= check_sms_preference(15,"value","email") ?>" class="checkboxSms" <?= check_sms_preference(15,"check","email") ?> style="display:none"/>
                                      <label for="cbx_24" class="toggle"><span></span></label>    
                                    </div>
                                </div>
                                
                            </div>
                            
                            <br>
                            <h4><b><?php echo get_phrase('virtual_class_settings');?></b></h4>
                            <div class="row">
                                <div class="col-sm-12 col-sm-12 successs_msg"></div>
                                <?php
                                   $vc_platform_id = get_school_virtual_platform();
                                ?>
                                <div class="col-sm-6">
                                    <label for="concept" class="control-label"><?php echo get_phrase('select_platform_for_online_classes'); ?></label>
                                    <select class="form-control" id="vc_platform_id" name="vc_platform_id" required>
                                         <option value="">Select Platform</option>
                                         <option value="1" <?php echo $vc_platform_id == 1 ? "selected" : "" ?>>Big Blue Button (Mynaparrot)</option>
                                         <option value="2" <?php echo $vc_platform_id == 2 ? "selected" : "" ?>>Jitsi</option>
                                         <option value="2" <?php echo $vc_platform_id == 3 ? "selected" : "" ?>>Big Blue Button (Indici)</option>
                                    </select>
                                    <button id="vc_button" name="vc_button" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                                </div>        

                            </div>
                            
                        </div>
                        <div class="col-sm-12 ml-4 mb-4">
                            <!--<button id="main_btn" name="submit" class="modal_save_btn"><?php echo get_phrase('save_preferences');?></button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    <!-- </form> -->
<?php } ?>
<script>


   
   
   $('#vc_button').click(function()
   {
       if(confirm("Are you sure to change the virtual class platform?"))
       {
           var vc_platform_id = $("#vc_platform_id").val();
           if(vc_platform_id != '')
           {
                $.ajax({
                    type: 'POST',
                    data: {
                        vc_platform_id:vc_platform_id
                    },
                    url: "<?php echo base_url();?>preferences/save_vc_preference",
                    dataType: "html",
                    success: function(response) {
                        Command: toastr["success"](response, "Alert")
                        toastr.options.positionClass = 'toast-bottom-right';
                    }
                });
           }
       }
   });       



    $('.checkboxSms').change(function(){    
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to change the setting!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                var section = $(this).attr('data-section');
                var value   = $(this).val();
                var type    = $(this).attr('data-type');
    
                $.ajax({
                    type: 'POST',
                    data: {
                        type:type,
                        section:section,
                        value:value,
                    },
                    url: "<?php echo base_url();?>preferences/save_preferences",
                    dataType: "html",
                    success: function(response) {
                        Command: toastr["success"](response, "Alert")
                        toastr.options.positionClass = 'toast-bottom-right';
                        setTimeout(function(){ location.reload();}, 2000);
                    }
                });
            }else{
                setTimeout(function(){ location.reload();}, 1000);
            }
          
        })
    });
</script>

