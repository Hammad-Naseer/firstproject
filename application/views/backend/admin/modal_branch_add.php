<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('add_branch'); ?>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12 thisrow">
       <h3><?php echo get_phrase('admin details');?></h3>
                <?php echo form_open(base_url().'branch/branches/create/' , array('id'=>'branch_add', 'enctype' => 'multipart/form-data'));?>

                <div class="form-group col-md-6">
                    
                    <label for="field-2" class="control-label"><?php echo get_phrase('admin_name');?> <span class="red"> * </span></label>
                    <input type="text" class="form-control" name="admin_name" value="" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label"><?php echo get_phrase('email');?> <span class="red"> * </span></label>
                    <input type="text" class="form-control" name="email" id="email" value="" maxlength="100" onkeypress="get_email()" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('password');?>
                    </label>
                    <input type="password" class="form-control" name="password" value="" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-1" class="control-label"><?php echo get_phrase('branch_name');?><span class="red"> * </span></label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" autofocus="" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label"> Package <span class="red"> * </span></label>
                    <select name="package_id" class="form-control" required >
                        <?php echo package_option_list(intval($data[0]['package_id'])); ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">Is Trial <span class="red"> * </span></label>
                    <select class="form-control is_trials" name="is_trial" required="">
                        <option value="">Select Trial Status</option>
                        <option value="1">Trial Version</option>
                        <option value="2">Regular Package Issue</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Start Date</label>
                    <input type="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" name="trial_date_start">
                </div>
                <div class="form-group col-md-6">
                    <label>End Date</label>
                    <?php  
                        $DateS = date("Y-m-d");
                        $end_dates = date('Y-m-d', strtotime($DateS. ' + 30 days'));
                    ?>
                    <input type="date" class="form-control" value="<?php echo $end_dates; ?>" name="trial_date_end">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('country');?>
                    </label>
                    <select name="country_id" class="form-control country">
                        <?php echo country_option_list(); ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('province');?>
                    </label>
                    <select name="province_id" class="form-control province">
                        <option><?php echo get_phrase('select');?></option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('city');?>
                    </label>
                    <select name="city_id" class="form-control city">
                        <option><?php echo get_phrase('select_city');?></option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('location');?>
                    </label>
                    <select name="location_id" class="form-control location">
                        <option value=""><?php echo get_phrase('select_location');?></option>
                    </select>
                </div>
                <div class="row" style="padding-left: 15px;padding-right: 15px;">
                    <div class="form-group col-md-12">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('address');?>
                    </label>
                    <textarea class="form-control" name="address" id="address" rows="5" maxlength="500" oninput="count_value('address','area_count','1000')"></textarea>
                    <div id="area_count" class="col-sm-12 "></div>
                </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('phone');?>
                    </label>
                    <input type="text" class="form-control" name="phone" value="" maxlength="30">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('url');?>
                    </label>
                    <input type="text" class="form-control" name="url" value="" maxlength="50">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('school_email');?>
                    </label>
                    <input type="text" class="form-control" name="school_email" value="" maxlength="50">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('contact_person');?>
                    </label>
                    <input type="text" class="form-control" name="contact_person" value="" maxlength="30">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('designation');?>
                    </label>
                    <input type="text" class="form-control" name="designation" value="" maxlength="30">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('slogan');?>
                    </label>
                    <input type="text" class="form-control" name="slogan" value="" maxlength="100">
                </div>
                <div class="row" style="padding-left: 15px;padding-right: 15px;">
                 <div class="form-group col-md-12">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('detail');?>
                    </label>
                    <textarea class="form-control" name="detail" id="detail" rows="5" maxlength="2000" oninput="count_value('detail','area_count1','2000')"></textarea>
                    <div id="area_count1" class="col-sm-12 "></div>
                </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('school_registration_no');?>
                    </label>
                    <input type="text" class="form-control" name="school_regist_no" value="" maxlength="50">
                </div>
                <div class="form-group col-md-6">
                    <label for="field-2" class="control-label">
                        <?php echo get_phrase('status');?>
                    </label>
                    <?php echo status('status','form-control','');?>
                </div>
                <div class="form-group col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('logo');?>
                    </label><br>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                <img src="<?php echo base_url();?>uploads/default.png" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                            <br>
                            <div>
                                <span class="btn btn-white btn-file">
									<span class="fileinput-new"><?php echo get_phrase('select_image');?></span>
                                    <span class="fileinput-exists"><?php echo get_phrase('change');?></span>
                                    <input type="file" name="userfile" id="userfile" onchange="file_validate('userfile','img','img_f_msg')" accept="image/*">
                                </span>
                                <br/>                                        
                                <span style="color: green;">
                                <?php echo get_phrase('allowed_file)size');?>
                                : 200kb, <?php echo get_phrase('allowed_file_type');?>: png, jpg, jpeg </span>
                                <br />
                                <span style="color: red;" id="img_f_msg"></span>
                                <br />
                        
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove');?></a>
                          </div>
                        </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">Is Valid <span class="red"> * </span></label>
                    <br>
                    <input type="checkbox" name="is_valid" id="is_valid" value="1" required="">
                </div>    
                <div class="row" style="padding-left: 15px;padding-right: 15px;">
                    <div class="form-group col-md-12" style="margin-top:80px;">
                    <div class="float-right">
    					<button type="submit" id="btn1_submit" class="modal_save_btn">
    						<?php echo get_phrase('save');?>
    					</button>
    					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    						<?php echo get_phrase('cancel');?>
    					</button>
    				</div>
                </div>
               
                <?php echo form_close();?>
          </div>
        
</div>
<style>
.glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -webkit-animation: spin2 .7s infinite linear;
}

@-webkit-keyframes spin2 {
    from {
        -webkit-transform: rotate(0deg);
    }
    to {
        -webkit-transform: rotate(360deg);
    }
}

@keyframes spin {
    from {
        transform: scale(1) rotate(0deg);
    }
    to {
        transform: scale(1) rotate(360deg);
    }
}
</style>
<script>
var flag_email = 0;
$(document).ready(function() {
    $(".country").change(function() {
        var country_id = $(this).val();
        province(country_id);
    });

    $(".province").change(function() {
        var province_id = $(this).val();
        city(province_id);
    });

    $(".city").change(function() {
        var city_id = $(this).val();
        city_location(city_id);
    });

    $('#email').change(function() {
        get_email();

    });
});

function province(country_id) {
    $.ajax({
        type: 'POST',
        data: {
            country_id: country_id
        },
        url: "<?php echo base_url();?>branch/get_province",
        dataType: "html",
        success: function(response) {
            $(".city").html('<select><option><?php echo get_phrase('select_city'); ?></option></select>');
            $(".location").html('<select><option><?php echo get_phrase('select_location'); ?></option></select>');

            //alert(response);
            $('.province').html(response);

            $("#loading").remove();
            //alert(response);

        }
    });
}

function city(province_id) {
    $.ajax({
        type: 'POST',
        data: {
            province_id: province_id
        },
        url: "<?php echo base_url();?>branch/get_city",
        dataType: "html",
        success: function(response) {
            $(".location").html('<select><option><?php echo get_phrase('select_location'); ?></option></select>');
            //alert(response);
            $('.city').html(response);

            $("#loading").remove();
            //alert(response);

        }
    });
}

function city_location(city_id) {
    $.ajax({
        type: 'POST',
        data: {
            city_id: city_id
        },
        url: "<?php echo base_url();?>branch/get_location",
        dataType: "html",
        success: function(response) {
            //alert(response);
            $('.location').html(response);

            $("#loading").remove();
            //alert(response);

        }
    });
}

function get_email() {

    $('#email').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

    var email = $('#email').val();
    $.ajax({
        type: 'POST',
        data: {
            email: email
        },
        url: "<?php echo base_url();?>branch/call_function",
        dataType: "html",
        success: function(response) {
            if ($.trim(response) == 'yes') {

                $("#btn1_submit").attr('disabled', 'true');
                $("#email").css('border', '1px solid red');
                $("#icon").remove();
                flag_email = 0;

                if ($('#message').html() == undefined) {
                    $("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_is_already_exist'); ?></p>');
                }
            } else {

                $("#btn1_submit").removeAttr('disabled');
                $("#email").css('border', '1px solid green');
                $("#icon").remove();
                $("#message").remove();
                flag_email = 1;
            }

            flag_check();
        }
    });
}

function flag_check() {
    if (flag_email == 1) {
        $("#btn1_submit").removeAttr('disabled');
        //alert("i am in if--"+flag_cnic+'--'+flag_email);
    } else {
        $("#btn1_submit").attr('disabled', 'true');
        //alert("i am in else--"+flag_cnic+'--'+flag_email);
    }

}
</script>
