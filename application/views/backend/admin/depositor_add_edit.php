<?php 
if (right_granted('staff_manage'))
{
    $school_id=$_SESSION['school_id'];
    $param2=str_decode($this->uri->segment(3));
    $edit_data=$this->db->get_where(get_school_db().'.depositor' , array('depositor_id' => $param2,'school_id'=>$school_id))->result_array();

    $account_type = 0;
    $country_id = 0;
    $province_id = 0;
    $city_id = 0;

    if(count($edit_data)>0) {
        $location_id = $edit_data[0]['location_id'];
        $array_address = get_country_edit($location_id);
        $country_id = $array_address[0]['country_id'];
        $province_id = $array_address[0]['province_id'];
        $city_id = $array_address[0]['city_id'];
    }
?>


<style>
    .panel-body {
        padding: 0px;
    }
    .back_space{
        margin-top: 10px !important;
        margin-bottom: 10px !important;
    }
</style>



<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h3 class="system_name inline back_space">
                <?php if(empty($param2))echo get_phrase('Add_Depositor'); else echo get_phrase('Edit_Depositor');?>
            </h3>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
                <?php echo form_open(base_url().'depositor/depositor_listing/add_edit/' , array('id'=>'depositor_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                
                <input type="hidden" class="form-control" name="depositor_id" value="<?php echo $edit_data[0]['depositor_id'] ?>">
                
                <div class="col-md-6">
                    <label for="field-1" class="control-label"><?php echo get_phrase('Name');?><span class="star">*</span></label>
                    <input maxlength="15" type="text" class="form-control" id="name" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['name'] ?>">
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                    <?php echo get_phrase('Email');?><span class="star">*</span></label>
                    <input maxlength="100" type="text" class="form-control" name="email" id="email" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['email'] ?>" >
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                    <?php echo get_phrase('Contact Number');?><span class="star">*</span></label>
                    <input maxlength="100" type="text" class="form-control" name="contact_no" id="contact_no" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['contact_no'] ?>" >
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label"><?php echo get_phrase('Address');?><span class="star">*</span></label>
                    <input maxlength="100" type="text" class="form-control" name="address" id="address" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['address'] ?>" >
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('select_country');?>
                    </label>
                    <select name="loc_add_country" id="loc_add_country" class="form-control">
                        <?php echo country_option_list($country_id); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('select_province / state');?>
                    </label>
                    <select name="loc_add_province" id="loc_add_province" class="form-control">
                        <?php echo province_option_list($country_id,$province_id);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('select_city');?>
                    </label>
                    <select name="loc_add_city" id="loc_add_city" class="form-control">
                        <?php echo city_option_list($province_id,$city_id);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('select_location');?>
                    </label>
                    <select name="location_id" id="location_id" class="form-control">
                        <?php echo location_option_list($city_id,$location_id );?> ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('description');?>   
                    </label>
                    <textarea cols="20" rows="7" class="form-control" name="description" id="description"><?php echo $edit_data[0]['description'] ?></textarea>
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                    <?php echo get_phrase('nationality');?></label>
                    <select name="nationality" class="form-control">
                        <?php echo country_option_list($edit_data[0]['nationality']);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                    <?php echo get_phrase('id_type');?></label>
                    <?php echo id_type_list('id_type','form-control',$edit_data[0]['id_type']);?>
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('id_number');?>
                    </label>
                    <input maxlength="100" type="text" class="form-control" name="id_no" id="id_no" value="<?php echo $edit_data[0]['id_no'] ?>" >
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label">
                    <?php echo get_phrase('status');?><span class="star">*</span></label>
                    <?php echo status('status','form-control',$edit_data[0]['status'],'status');?>
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label">
                    <?php echo get_phrase('coa_cash_deposit');?><span class="star">*</span></label>
                    <select name="coa_cash_deposit" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php coa_list_h(0,$edit_data[0]['coa_cash_deposit']);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label"><?php echo get_phrase('coa_bank_deposit');?><span class="star">*</span></label>
                    <select name="coa_bank_deposit" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php coa_list_h(0,$edit_data[0]['coa_bank_deposit']);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-2" class="control-label"><?php echo get_phrase('coa_capital_asset');?><span class="star">*</span></label>
                    <select name="coa_capital_asset" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select_chart_of_account');?></option>
                        <?php coa_list_h(0,$edit_data[0]['coa_capital_asset']);?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('profile_picture');?>
                    </label>
                        <?php
						if ($edit_data[0]['attachment']!= "")
						{
                        ?>
                            <span id="staff_val" style="height: 100px; width: 100px;">
                            <img src="<?php echo base_url().system_path($edit_data[0]['attachment'],'depositor') ?>" width="100" height="100" style="max-width:100%;">


                            <a class="btn btn-primary" onclick="delete_files('<?php echo $edit_data[0]['attachment']; ?>','depositor','depositor_id','<?php echo $edit_data[0]['depositor_id']; ?>','attachment','depositor','staff_val')"><?php echo get_phrase('delete_image');?>
                            </a>
                            </span>
                            <br>
                            <strong>
                            <?php echo get_phrase('change');?>:
						<?php
						}
                        ?>

						</strong>
                        <input type="file" name="attachment" id="staff_image" onchange="file_validate('staff_image','img','img_f_msg')" accept="image/*">
                        <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>e: 200kb, <?php echo get_phrase('allowed_file_type');?>: png, jpg, jpeg </span>
                   <br />
                   <span style="color: red;" id="img_f_msg"></span>

                    <input type="hidden" name="old_attachment" value="<?php echo $edit_data[0]['attachment']?>" />
                </div>
                <div class="col-md-12">
                    <div class="float-right">
    					<button type="submit" class="modal_save_btn">
    						<?php echo get_phrase('save');?>
    					</button>
    					<button type="button" class="modal_cancel_btn" onclick="location.reload()">
    						<?php echo get_phrase('cancel');?>
    					</button>
    					<br><br>
    				</div>
                </div>
                
                
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<script>
$('#loc_add_country').on('change', function() {
    var loc_id = $('#loc_add_country').val();
    console.log(loc_id);
    $('#loc_add_country').after('<span id="message" class="loader_small"></span>');

    $.ajax({

        url: "<?php echo base_url();?>location/get_province_list",
        type: 'post',
        data: {
            id: loc_id
        },
        dataType: 'html',
        success: function(res) {
            $('#message').remove();
            $('#loc_add_province').html(res);
            $('#loc_add_city').html("<option><?php echo get_phrase('no_record_found'); ?></option>");
            $('#location_id').html("<option><?php echo get_phrase('no_record_found'); ?></option>");


        }
    });
});

$('#loc_add_province').change(function() {
    var prov_id = $('#loc_add_province').val();
    $('#loc_add_province').after('<div id="message" class="loader_small"></div>');

    $.ajax({
        url: "<?php echo base_url();?>location/get_city_list",
        type: 'post',
        data: {
            id: prov_id
        },
        dataType: 'html',
        success: function(res) {
            $('#message').remove();
            $('#loc_add_city').html(res);
        }
    });
});

$('#loc_add_city').change(function() {
    var city_id = $('#loc_add_city').val();
    $('#loc_add_city').after('<div id="message" class="loader_small"></div>');
    //$('#location_id').html("");

    $('#location_id').empty();
   // alert('hi');
    $.ajax({
        url: "<?php echo base_url();?>location/get_location_list",
        type: 'post',
        data: {
            id: city_id
        },
        dataType: 'html',
        success: function(res) {
            $('#message').remove();
            $('#location_id').html(res);
        }
    });
});


/*var staff_id = '<?php echo $param2?>';
designation_id = $('#designation_id').val();
if (staff_id != '') {
    $.ajax({
        url: "<?php echo base_url();?>user/check_is_teacher",
        type: 'post',
        data: {
            designation_id: designation_id,
            staff_id: '<?php echo $param2?>'
        },
        dataType: 'json',
        success: function(res) {
            console.log(res);
            if (res.count > 0) {
                $('#teacher_period_count').show();
            } else {
                if (res.week > 0) {

                    $('#teacher_period_count').show();
                    $('#teacher_period_assigned').text(res.week + '<?php echo get_phrase('periods_are_already_assigned_per_week'); ?>');
                    $('#periods_per_week').attr('min', res.week);

                }
                if (res.day > 0) {
                    $('#teacher_period_count').show();
                    $('#teacher_period_assigned_day').text(res.day + '<?php echo get_phrase('periods_are_already_assigned_per_day'); ?>');
                    $('#periods_per_day').attr('min', res.day);

                } else {
                    $('#teacher_period_count').hide();
                }
            }


        }
    });
}*/



$('#designation_id').change(function() {

    $('#btn-dsb').prop('disabled', false);
    $('#teacher_err').text('');
    $('#teacher_period_count').hide();
    var designation_id = $(this).val();

    if (designation_id != '') {

        $.ajax({
            url: "<?php echo base_url();?>user/check_is_teacher",
            type: 'post',
            data: {
                designation_id: designation_id,
                staff_id: '<?php echo $param2?>'
            },
            dataType: 'json',
            success: function(res) {
                //console.log(res);
                //alert(res);
                if (res.subject_teacher == 'true') {

                    $('#teacher_err').text('Unable to update,staff is linked with subjects');
                    $('#btn-dsb').prop('disabled', true);
                }
                if (res.count == 1) {
                    $('#teacher_period_count').show();

                    $('#btn-dsb').prop('disabled', false);
                } else {
                    $('#teacher_period_count').hide();
                }
            }
        });

    }


});
$('#cnic').on('change', function() {
    $('#loader').remove();
    var cnic = $(this).val();
    $(this).after('<div id="loader" class="loader_small"></div>')




    $.ajax({
        type: 'POST',
        data: {
            cnic: cnic
        },
        url: "<?php echo base_url(); ?>user/check_cnic",
        dataType: "html",
        success: function(response) {
            $('#loader').remove();
            if ($.trim(response) == "yes") {

                $('#msg').remove();
                $('#btn-dsb').removeAttr('disabled');


            } else {
                $('#cnic').after('<span id="msg" style="color:red;" ><?php echo get_phrase('id_already_exist'); ?></span>');
                $('#btn-dsb').attr('disabled', 'true');






            }



        }
    });


});
</script>

<script>
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<?php
}
?>