<?php 
if (right_granted('staff_manage'))
{
?>
<link rel="stylesheet" href="<?=base_url()?>assets/wizard/wizard.css">
<script src="<?=base_url()?>assets/wizard/wizard.js"></script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <a href="<?php echo base_url();?>user/staff_listing" class="btn btn-primary" style="float:right;"><?php echo get_phrase('back');?></a>
            <?php if(empty($param2))echo get_phrase('add_staff'); else echo get_phrase('edit_staff');?>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step col-xs-4">
                            <!--style="pointer-events: none !important;cursor: default !important;"-->
                            <a href="#step-1" type="button" class="btn-success btn-circle anchor_wizard">1</a>
                            <p><small><?php echo get_phrase('official_detail');?></small></p>
                        </div>
                        <div class="stepwizard-step col-xs-4">
                            <a href="#step-2" type="button" class="btn-default btn-circle anchor_wizard" disabled="disabled">2</a>
                            <p><small><?php echo get_phrase('add_more_information');?></small></p>
                        </div>
                        <div class="stepwizard-step col-xs-4">
                            <a href="#step-3" type="button" class="btn-default btn-circle anchor_wizard" disabled="disabled">3</a>
                            <p><small><?php echo get_phrase('salary_settings');?></small></p>
                        </div>
                    </div>
                </div>
            <form action="<?=base_url()?>user/staff_listing/add_edit/" method="post" id="staff_add_edit_form" role="form" enctype="multipart/form-data">
                <input type="hidden" class="form-control" name="staff_id" value="<?php echo $edit_data[0]['s_id'] ?>">
                <div class="panel panel-primary setup-content" id="step-1">
                    <div class="panel-heading">
                         <h3 class="panel-title"><b><?php echo get_phrase('official_details');?></b></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('id_type');?><span class="star">*</span></label>       
                                <?php echo id_type_list('id_type','form-control wizard_validate',$edit_data[0]['id_type']);?>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                            <?php echo get_phrase('id_no');?><span class="star">*</span></label>
                            <input maxlength="15" type="text" class="form-control cnic_mask" id="cnic" name="cnic" required="required" value="<?php echo $edit_data[0]['id_no'] ?>" autofocus>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label class="control-label">
                                <?php echo get_phrase('id_file');?>
                            </label>
                            <input value="" type="file" class="form-control" name="image2" id="image2" onchange="file_validate('image2','doc','img_f_msg')">
                            <span style="color: green;">
                            <?php echo get_phrase('allowed_file_size');?>
                            :2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx </span>
                            <br />
                            <span style="color: red;" id="img_f_msg"></span>
                            <span id="id_file">				
                                <?php
                                    $val_im=display_link($edit_data[0]['id_file'],'staff',0,0); 
                                    if($val_im!=""){
                                ?>	
                                <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment');?></a>
                                <a onclick="delete_files('<?php echo $edit_data[0]['id_file']; ?>','staff','staff_id','<?php echo $edit_data[0]['staff_id']; ?>','id_file','staff','id_file',2)" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment');?></a>
                                <?php } ?> 				
                                <input type="hidden" name="image_old" value="<?php echo $edit_data[0]['id_file']; ?>" />								
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                            <?php echo get_phrase('name');?><span class="star">*</span></label>
                            <input maxlength="100" type="text" class="form-control" name="name" required="required" value="<?php echo $edit_data[0]['name'] ?>" autofocus>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('designation');?>
                            </label>
                            <select name="designation_id" id="designation_id" class="form-control show_periords" data-message-required="<?php echo get_phrase('value_required');?>">
                                <option value=""><?php echo get_phrase('select_designation'); ?></option>
                                <?php  //designation_list(0,$edit_data[0]['designation_id']);
                                  echo designation_list_h($parent_id=0,$edit_data[0]['designation_id']);
                                ?>
                            </select>
                            <span class="text-danger" style="    position: absolute;">Designation is Mandatory If Teaching Staff</span>
                            <div id="teacher_err"></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group mt-4">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('Gender');?>
                            </label>
                            <select name="gender" class="form-control" data-message-required="<?php echo get_phrase('value_required');?>">
                                <option value="">
                                    <?php echo get_phrase('select');?>
                                </option>
                                <option value="male" <?php echo ($edit_data[0][ 'gender']=='male' )? 'selected': ''; ?>>
                                    <?php echo get_phrase('male');?>
                                </option>
                                <option value="female" <?php echo ($edit_data[0][ 'gender']=='female' )? 'selected': ''; ?>>
                                    <?php echo get_phrase('female');?>
                                </option>
                            </select>
                        </div>
                        <div id="teacher_period_count" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 row-m-0" style="display: none">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                                <label for="field-1" class="control-label">
                                    <?php echo get_phrase('periods_per_day');?> <span class="star">*</span>
                                </label>
                                <input maxlength="20" type="number" class="form-control" id="periods_per_day" name="periods_per_day" value="<?php echo $edit_data[0]['periods_per_day'] ?>">
                                <div id="teacher_period_assigned_day"></div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                                <label for="field-1" class="control-label">
                                    <?php echo get_phrase('periods_per_week');?> <span class="star">*</span>
                                </label>
                                <input maxlength="20" type="number" class="form-control" id="periods_per_week" name="periods_per_week" value="<?php echo $edit_data[0]['periods_per_week'] ?>">
                                <div id="teacher_period_assigned"></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label" required="">
                                <?php echo get_phrase('email');?> <span class="star">*</span>
                            </label>
                            <input maxlength="100" type="text" class="form-control" name="email" required="" value="<?php echo $edit_data[0]['email'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                            <?php echo get_phrase('mobile_no');?><span class="star">*</span></label>
                            <input maxlength="20" type="text" class="form-control" name="mobile_no" value="<?php echo $edit_data[0]['mobile_no'] ?>" required="required">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                            <?php echo get_phrase('status');?><span class="star">*</span></label>
                            <?php echo status('status','form-control wizard_validate',$edit_data[0]['status'],'status');?>
                        </div>
                        <div class="form-group mt-4" style="height:30px;">
                            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary setup-content" id="step-2">
                    <div class="panel-heading">
                         <h3 class="panel-title"><b><?php echo get_phrase('add_more_information');?></b></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('religion');?>
                            </label>
                            <?php echo religion_list("religion", "form-control", $edit_data[0]['religion']);?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('date_of_birth');?>
                            </label>
                            <input type="text" class="form-control datepicker" name="birthday" value="<?php echo date_dash($edit_data[0]['dob']); ?>" data-start-view="2" data-format="dd/mm/yyyy">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('nationality');?>
                            </label>
                           <select name="nationality" class="form-control">
                                	<?php echo country_option_list($edit_data[0]['nationality']);?>
                            </select>  
                        </div>
                  
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('select_country');?>
                            </label>
                                <select name="loc_add_country" id="loc_add_country" class="form-control">
                                    <?php
        							 $country_id = intval($edit_data[0]['country_id']);
        							 echo country_option_list($country_id);?>
                                </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('select_province')
        						?> /
                                <?php echo get_phrase('state');?></label>
                                <select name="loc_add_province" id="loc_add_province" class="form-control">
                                    <?php 
        							$province_id = intval($edit_data[0]['province_id']);
        							echo province_option_list($country_id,$province_id);?>
                                </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('select_city');?>
                            </label>
                            <select name="loc_add_city" id="loc_add_city" class="form-control">
                                <?php 
    						        $city_id = intval($edit_data[0]['city_id']);
    						        echo city_option_list($province_id,$city_id);
    						     ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('select_location');?>
                            </label>
                            <select name="loc_add" id="loc_add" class="form-control">
                                <?php
                                $location_id = intval($edit_data[0]['location_id']);
                              
    						 echo location_option_list($city_id,$location_id );
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('postal_address');?>
                            </label>
                            <input maxlength="1000" type="text" class="form-control" name="postal_address" value="<?php echo $edit_data[0]['postal_address'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('permanent_address');?>
                            </label>
                                <input maxlength="1000" type="text" class="form-control" name="permanent_address" value="<?php echo $edit_data[0]['permanent_address'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('residence');?>#</label>
                                <input maxlength="20" type="text" class="form-control" name="phone_no" value="<?php echo $edit_data[0]['phone_no'] ?>">
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('emergency');?>#</label>
                                <input maxlength="20" type="text" class="form-control" name="emergency_no" value="<?php echo $edit_data[0]['emergency_no'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('employee_code');?>
                            </label>
                                <input maxlength="25" type="text" class="form-control" name="employee_code" value="<?php echo $edit_data[0]['employee_code'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('blood_group');?>
                            </label>
                                <input maxlength="20" type="text" class="form-control" name="blood_group" value="<?php echo $edit_data[0]['blood_group'] ?>">
                        </div>
                    
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('joining_date');?>
                            </label>
                                <input  type="text" class="form-control datepicker" name="joining_date" value="<?php echo date_dash($edit_data[0]['joining_date']);?>" data-format="dd/mm/yyyy">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('experience_month');?>
                            </label>
                                <input  type="number" min="0" max="12" class="form-control" name="experience_month" value="<?php echo $edit_data[0]['experience_month'] ?>">
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('experience_year');?>
                            </label>
                            <input  type="number" min="0" max="50" class="form-control" name="experience_year" value="<?php echo $edit_data[0]['experience_year'] ?>">
                        </div>
                        <div class="row row-m-0 col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('staff_image');?>
                            </label>
                            <br>
                            <?php if ($edit_data[0]['staff_image']!= "") { ?>
                            <span id="staff_val" style="height: 100px; width: 100px;">
        						<img src="<?php echo base_url().system_path($edit_data[0]['staff_image'],'staff') ?>" width="100" height="100" style="max-width:100%;">
                                <a class="btn btn-primary" onclick="delete_files('<?php echo $edit_data[0]['staff_image']; ?>','staff','staff_id','<?php echo $param2; ?>','staff_image','staff','staff_val')"><?php echo get_phrase('delete_image');?></a>	
                            </span>
                            <br>
                            <strong><?php echo get_phrase('change');?>:
    						<?php } ?>
    						</strong>
                            <input type="file" name="staff_image" id="staff_image" onchange="file_validate('staff_image','img','img_f_msg')" accept="image/*">
                            <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>: 200kb, <?php echo get_phrase('allowed_file_types');?>: png, jpg, jpeg </span>
                            <br />
                            <span style="color: red;" id="img_f_msg"></span>
                            <input type="hidden" name="old_staff_image" value="<?php echo $edit_data[0]['staff_image']?>" />
                        </div>
                        <div class="form-group mt-4" style="height: 30px;">
                            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary setup-content" id="step-3">
                    <div class="panel-heading">
                         <h3 class="panel-title"><b><?php echo get_phrase('salary_settings');?></b></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row" style="padding-left: 15px;padding-right: 15px;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('gross_salary');?> <span class="star">*</span>
                                </label>
                                <input type="number" class="form-control" name="gross_salary" min="0" value="<?php echo $edit_data[0]['gross_salary'] ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('hours_per_day');?>
                            </label>
                            <input  type="number" class="form-control" name="hours_per_day" min="0" value="<?php echo $edit_data[0]['hours_per_day'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('hours_per_week');?>
                            </label>
                            <input  type="number" class="form-control" name="hours_per_week" min="0" value="<?php echo $edit_data[0]['hours_per_week'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('hours_per_month');?>
                            </label>
                            <input  type="number" class="form-control" name="hours_per_month" min="0" value="<?php echo $edit_data[0]['hours_per_month'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('regular_daily_rate');?> </label>
                                <input  type="number" class="form-control" name="regular_daily_rate" min="0" value="<?php echo $edit_data[0]['regular_daily_rate'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('regular_hourly_rate');?>
                            </label>
                                <input  type="number" class="form-control" name="regular_hourly_rate" min="0" value="<?php echo $edit_data[0]['regular_hourly_rate'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('overtime_daily_rate');?>
                            </label>
                                <input  type="number" class="form-control" name="overtime_daily_rate" min="0" value="<?php echo $edit_data[0]['overtime_daily_rate'] ?>">
                        </div>
                        <div class="row" style="padding-left: 15px;padding-right: 15px;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('overtime_hourly_rate');?>
                                </label>
                                <input  type="number" class="form-control" name="overtime_hourly_rate" min="0" value="<?php echo $edit_data[0]['overtime_hourly_rate'] ?>">
                            </div>
                        </div>
                        <div class="row" style="padding-left: 15px;padding-right: 15px;">
                            <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">-->
                            <!--    <h4>Allownces</h4>-->
                            <!--    <ol>-->
                                    <?php
                            // <!--            $allownce_ids = $edit_data[0]['allownce_ids'];-->
                            // <!--            $allownce_array = explode("," , $allownce_ids);-->
                                        
                            // <!--            foreach(get_allownces() as $allownce)-->
                            // <!--            {-->
                            // <!--                $checked = "";-->
                            // <!--                if(in_array($allownce['allownce_id'] , $allownce_array)){-->
                            // <!--                    $checked = "checked";-->
                            // <!--                }-->
                                    ?>
                            <!--        <li>-->
                            <!--            <input type="checkbox" name="allownce[]" id="allownce<?php //echo $allownce['allownce_id'] ?>" value="<?php //echo $allownce['allownce_id'] ?>"  <?php //echo $checked ?> >-->
                            <!--            <label for="allownce<?php //echo $allownce['allownce_id'] ?>"><?php //echo $allownce['allownce_title'] ?></label>-->
                            <!--        </li>-->
                                    <?php
                            // <!--            }-->
                                    ?>
                            <!--    </ol>-->
                            <!--</div>-->
                            <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">-->
                            <!--    <h4>Deductions</h4>-->
                            <!--    <ol>-->
                                    <?php 
                            // <!--            $deduction_ids = $edit_data[0]['deduction_ids'];-->
                            // <!--            $deduction_array = explode("," , $deduction_ids);-->
                                        
                            // <!--            foreach(get_deductions() as $deduction)-->
                            // <!--            {-->
                            // <!--                $checked = "";-->
                            // <!--                if(in_array($deduction['deduction_id'] , $deduction_array)){-->
                            // <!--                    $checked = "checked";-->
                            // <!--                }-->
                                    ?>
                            <!--        <li>-->
                            <!--            <input type="checkbox" name="deduction[]" id="deduction<?= $deduction['deduction_id'] ?>" value="<?= $deduction['deduction_id'] ?>" <?php echo $checked ?> >-->
                            <!--            <label for="deduction<?= $deduction['deduction_id'] ?>"><?= $deduction['deduction_title'] ?></label>-->
                            <!--        </li>-->
                                        <?php
                            // <!--            }-->
                                        ?>
                            <!--    </ol>-->
                            <!--</div>-->
                        </div>
                        <div class="form-group mt-4" style="height: 30px;">
                            <button class="btn btn-primary pull-right" id="btn1" type="submit">Finish!</button>
                        </div>
                    </div>
                </div>
                </form>
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

    $.ajax({
        url: "<?php echo base_url();?>location/get_location_list",
        type: 'post',
        data: {
            id: city_id
        },
        dataType: 'html',
        success: function(res) {
            $('#message').remove();
            $('#loc_add').html(res);
        }
    });
});


var staff_id = '<?php echo $param2?>';
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
                    $('#teacher_period_assigned').text(res.week + '<?php echo get_phrase('periods_are_already_assigned_per_week');?>');
                    $('#periods_per_week').attr('min', res.week);

                }
                if (res.day > 0) {
                    $('#teacher_period_count').show();
                    $('#teacher_period_assigned_day').text(res.day + '<?php echo get_phrase('periods_are_already_assigned_per_day');?>');
                    $('#periods_per_day').attr('min', res.day);

                } else {
                    $('#teacher_period_count').hide();
                }
            }


        }
    });
}



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

                    $('#teacher_err').text('<?php echo get_phrase('unable_to_update_staff_is_linked_with_subjects');?>');
                    $('#btn-dsb').prop('disabled', true);
                }
                if (res.count == 1) {
                    $('#teacher_period_count').show();
                    
                    $("#periods_per_day").attr("required","true");
                    $("#periods_per_week").attr("required","true");

                    $('#btn-dsb').prop('disabled', false);
                } else {
                    $('#teacher_period_count').hide();
                    
                    $("#periods_per_day").removeAttr("required");
                    $("#periods_per_week").removeAttr("required");
                    
                    
                }
            }
        });

    }


});
$('#cnic').on('change', function() {
    $('#loader').remove();
    var cnic = $(this).val();
    if(cnic.length >= 8){
        $(this).after('<div id="loader" class="loader_small"></div>')
        $.ajax({
            type: 'POST',
            data: {
                cnic: cnic
            },
            url: "<?php echo base_url();?>user/check_cnic",
            dataType: "html",
            success: function(response) {
                $('#loader').remove();
                if ($.trim(response) == "yes") {
                    $('#msg').remove();
                    $('#btn-dsb').removeAttr('disabled');
    				$('#loader').remove();
                }else{
                    $('#cnic').after('<span id="msg" style="color:red;" ><?php echo get_phrase("cnic_already_exist") ?></span>');
                    $('#btn-dsb').attr('disabled', 'true');
                }
            }
        });
    }else{
        $('#cnic').after('<span id="msg" style="color:red;" ><?php echo get_phrase("Should_at_least_contain_8_digits") ?></span>');
        $('#btn-dsb').attr('disabled', 'true');
        
        setTimeout(function(){ $("#msg").remove(); }, 2000);
    }
});


/*

Commented By JS Cheif

$(".show_periords").on("change",function(){
    var v = $(this).val();
    if(v != "")
    {
        $("#periods_per_day").attr("required","true");
        $("#periods_per_week").attr("required","true");
    }else{
        $("#periods_per_day").removeAttr("required");
        $("#periods_per_week").removeAttr("required");
    }
});
*/


</script>

<script>
// $(window).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
// });
</script>
<?php
}
?>

