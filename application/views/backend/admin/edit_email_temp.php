    <style>
    .loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #63b7e7;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
    
    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    </style>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-6 myt topbar">
            <h3 class="system_name">
                <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                <img class="img-responsive mynavimg2" src="<?php echo base_url() ?>assets/images/school-setting-2.png">
                
                <?php echo get_phrase('Edit_Email_templates');?>
                 
            </h3>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-6 myt topbar">
            <a href="<?php echo base_url(); ?>templates/email_temp_listing" target="_blank" class="btn btn-primary" style="float:right;"><?php echo get_phrase('View_SMS_Template');?></a>
        </div>
    </div>
    <?php echo form_open(base_url()."templates/update_email_temp"); ?>
    <input  type="hidden" class="form-control" name="email_temp_id" value="<?php echo $email_edit_arr[0]['email_temp_id'];?>">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3 style="margin-left: 10px;"><?php echo get_phrase('Email_Template');?></h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('Email_title');?><span class="star">*</span></label>
                        <div class="">
                            <input maxlength="100" type="text" class="form-control" name="email_title" value="<?php echo $email_edit_arr[0]['email_title'];?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('email_subject');?><span class="star">*</span></label>
                        <div class="">
                            <input maxlength="100" type="text" class="form-control" name="email_subject" value="<?php echo $email_edit_arr[0]['email_subject'];?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">
                             <?php echo get_phrase('Email_content');?>
                        </label>
                        <div class="">
                            <textarea maxlength="160" id="email_content" oninput="count_value('email_content','sms_count','160')" class="form-control" name="email_content" rows="5"><?php echo $email_edit_arr[0]['email_content'];?></textarea>
                            <div id="sms_count" class=""></div>
                        </div>
                    </div>
                    <div class="form-group">
    					<label class="control-label">
    						<?php echo get_phrase('status');?>
    					</label>
    					<div class="">
    						<select name="sms_template_status" id="sms_template_status" class="form-control" required >
    						    <option value="1" <?php if($email_edit_arr[0]['email_template_status']==1) echo 'selected';?> ><?php echo get_phrase('active');?></option>
							    <option value="0" <?php if($email_edit_arr[0]['email_template_status']==0) echo 'selected';?> ><?php echo get_phrase('inactive');?></option>
    						</select>
    					</div>
    				</div>
                    <div class="form-group">
                        <div class="col-xs-offset-3">
                            <button type="submit" class="btn btn-info pull-right">
                                <?php echo get_phrase('update');?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="taglist">
                <div class="form-check form-check-inline">
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox1" value="student_name">
                <label class="form-check-label" for="inlineCheckbox1">student_name</label>
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox2" value="class_name">
                <label class="form-check-label" for="inlineCheckbox2">class_name</label>
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox3" value="section_name">
                <label class="form-check-label" for="inlineCheckbox3">section_name</label>
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox4" value="roll_num">
                <label class="form-check-label" for="inlineCheckbox4">roll_num</label>
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox5" value="department">
                <label class="form-check-label" for="inlineCheckbox5">department</label>
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox6" value="current_date">
                <label class="form-check-label" for="inlineCheckbox6">current_date</label>
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox7" value="school_name">
                <label class="form-check-label" for="inlineCheckbox7">school_name</label>
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox8" value="payment">
                <label class="form-check-label" for="inlineCheckbox8">payment</label>
                <input class="form-check-input chk" type="checkbox" id="inlineCheckbox9" value="due_date">
                <label class="form-check-label" for="inlineCheckbox9">due_date</label>
                
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(".chk").change(function() {
            if(this.checked) {
                var aa = this.value;
                 document.getElementById("email_content").value += aa;
                 document.getElementById("email_content").focus();
            }else{
                 var aa = this.value;
                
                val = $('#email_content').val();
                $('#email_content').val(val.replace(aa, ""));
                document.getElementById("email_content").focus();
            }
        });
    </script>
   