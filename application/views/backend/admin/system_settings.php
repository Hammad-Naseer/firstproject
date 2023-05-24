<style>
.pd0 {
    padding: 0px !important;
}

.system_name {
    margin: 0px !important;
}
</style>
<?php
$school_id=$_SESSION['school_id'];
$scr=$this->db->get_where(get_school_db().'.school' , array('school_id' =>$school_id))->result_array();


if (right_granted('schoolconfiguration_manage'))
{
	echo form_open_multipart(base_url().'school_setting/system_settings/do_update' , 
	array('class' => 'pd0 form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'disable_submit_btn')); ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name" data-step="1" data-position='bottom' data-intro="school configuration you can edit your school information">
                <img class="img-responsive mynavimg2" src="<?php echo base_url() ?>assets/images/school-setting-2.png">
                
                <?php echo get_phrase('school_configuration');?>
                 
            </h3>
        </div>
    </div>
    <div class="row">
        <!--<div class="col-md-12">-->
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" data-step="2" data-position='bottom' data-intro="enter your school name this field is required">
                        <!--<div class="form-group">-->
                            <label class="control-label">
                                <?php echo get_phrase('school_name');?><span class="star">*</span></label>
                                <input maxlength="100" type="text" class="form-control" name="system_name" value="<?php echo $scr[0]['name'];  ?>" required>
                        <!--</div>-->
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <!--<div class="form-group">-->
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('registration_no');?>
                            </label>
                            <input maxlength="50" type="text" class="form-control" name="school_regist_no" value="<?php echo $scr[0]['school_regist_no'];?>">
                                <!--<input type="text" class="form-control" name="detail" value="">-->
                        <!--</div>-->
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class=" control-label">
                            <?php echo get_phrase('slogan');?>
                        </label>
                        <input maxlength="300" type="text" class="form-control" name="slogan" value="<?php echo $scr[0]['slogan'];?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('country');?>
                        </label>
                        <select name="country_id" class="form-control country">
                            <?php echo country_option_list($scr[0]['country_id']); ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('province');?>
                        </label>
                        <div class="province_html">
                            <select name="province_id" class="form-control province">
                                <option><?php echo get_phrase('select'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('city');?>
                        </label>
                        <div class="city_html">
                            <select name="city_id" class="form-control city">
                                <option><?php echo get_phrase('select'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('location');?>
                        </label>
                        <div class="city_html">
                            <select name="location_id" class="form-control location">
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label class="control-label">
                            <?php echo get_phrase('phone');?>
                        </label>
                        <input maxlength="100" type="text" class="form-control" name="phone" value="<?php echo $scr[0]['phone']; ?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label class="control-label">
                            <?php echo get_phrase('url');?>
                        </label>
                        <input maxlength="300" type="text" class="form-control" name="url" value="<?php echo $scr[0]['url']; ?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('email');?>
                        </label>
                        <input maxlength="50" type="text" class="form-control" name="school_email" value="<?php echo $scr[0]['email']; ?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('contact_person');?>
                        </label>
                        <input maxlength="30" type="text" class="form-control" name="contact_person" value="<?php echo $scr[0]['contact_person'];?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('designation');?>
                        </label>
                        <input maxlength="30" type="text" class="form-control" name="designation" value="<?php echo $scr[0]['designation'];?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('attendance_method');?>
                        </label>
                        <select  class="form-control" name="attendance_method" required>
                            <option value="">select attendance method</option>
                            <option value="1" <?php if( $scr[0]['attendance_method'] == 1) echo "selected";?> >Daily</option>
                            <option value="2" <?php if( $scr[0]['attendance_method'] == 2) echo "selected";?>>Subject wise</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('diary_approval');?>
                        </label>
                        <select  class="form-control" name="diary_approval" required>
                            <option value="">Diary Approval By</option>
                            <option value="1" <?php if( $scr[0]['diary_approval'] == 1) echo "selected";?> >By Teacher</option>
                            <option value="2" <?php if( $scr[0]['diary_approval'] == 2) echo "selected";?>>By Admin</option>
                        </select>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            <?php echo get_phrase('address');?>
                        </label>
                       <textarea maxlength="100" id="address" oninput="count_value('address','address_count','100')" class="form-control" name="address" rows="5"><?php echo $scr[0]['address'];?></textarea>
                        <div id="address_count" class="col-sm-12 "></div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('detail');?>
                        </label>
                        <textarea maxlength="2000" id="detail" class="form-control" name="detail" oninput="count_value('detail','detail_count','2000')" rows="5"><?php echo $scr[0]['detail'];?></textarea>
                        <div id="detail_count"></div>
                    </div>
                    </div>
                    <?php /*?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            <?php echo get_phrase('Logo');?>
                        </label>
                        <div class="col-sm-5">
                            <input value="" type="file" class="form-control" name="logo">
                        </div>
                    </div>
                    <?php */?>
                    <!--file uploading frontend coding-->
                    <div class="row">
                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" data-step="3" data-position='bottom' data-intro="upload your school image this field is required">
                        <label class="control-label">
                            <?php echo get_phrase('logo');?><span class="star">*</span></label>
                        <!--<div class="col-sm-5">-->
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <input type="hidden" value="" name="...">
                                <div class="fileinput-new " style="max-width: 200px;  max-height: 150px;" data-trigger="">
                                    
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 6px;"></div>

                            <div style="    margin-left: -54px;">
                                <span class="btn btn-white btn-file">
                                    <span class="fileinput-new"><?php echo get_phrase('select_logo');?></span>
                                    <span class="fileinput-exists"><?php echo get_phrase('change');?></span>
                                    <input type="file" class="form-control" name="logo" value="<?php echo get_phrase('select_new_image_file');?>" >
                                    <input type="file" name="" accept="image/*" value="Select" id="sysem_file" onchange="file_validate('sysem_file','img','img_f_msg')">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove');?></a>
                            </div>
                    </div>
                    <br>
                    <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>: 200kb, <?php echo get_phrase('allowed_file_types');?>: png, jpg, jpeg </span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php
                                 		$logo=system_path($scr[0]['logo']);
                                 		if($scr[0]['logo']!="" && is_file($logo))
                                 		{
                                			?>
                            <img class="img-responsive" height="70" src="<?php 
                                   
                                   echo display_link($scr[0]['logo'],'');
                                   
                                  ?>" style="width:200px;display:block;text-align:center" />
                                    <?php } ?>
                                    <input type="hidden" name="logo_old" value="<?php echo $scr[0]['logo']; ?>" />
                            <input type="hidden" name="school_id" value="<?php echo $scr[0]['school_id']; ?>" />
                        </div>
                    </div>
<span style="color: red;" id="img_f_msg"></span>
                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" data-step="4" data-position='top' data-intro="press this button save this information">
                            <div class="float-right">
            					<button type="submit" class="modal_save_btn">
            						<?php echo get_phrase('save');?>
            					</button>
            					<button type="button" class="modal_cancel_btn" onclick="location.reload()">
            						<?php echo get_phrase('cancel');?>
            					</button>
            				</div>
                        </div>
                    </div>
                </div>
            </div>
        <!--</div>-->
    </div>
    <?php echo form_close(); ?>
    <script>
    $(document).ready(function() {
        $(".country").change(function() {
            var loc_id = $(this).val();
            var send_location = 'province';
            $('.city').html('<select><option><?php echo get_phrase('select_city'); ?></option></select>');

            $('.location').html('<select><option><?php echo get_phrase('select_location'); ?></option></select>');


            if (loc_id == "") {

            } else {

                get_location(loc_id, send_location);
            }


        });

        $(".province").change(function() {

            $('.location').html('<select><option><?php echo get_phrase('select_location'); ?></option></select>');
            var loc_id = $(this).val();
            var send_location = 'city';

            if (loc_id == "") {

            } else {

                get_location(loc_id, send_location);
            }
        });

        $(".city").change(function() {

            var loc_id = $(this).val();
            var send_location = 'location';


            if (loc_id == "") {

            } else {

                get_location(loc_id, send_location);
            }
        });


    });

    function get_location(loc_id, send_location, selected = 0) {

        $("#loading").remove();
        $('.' + send_location).after("<div id='loading' class='loader_small'></div>");

        $.ajax({
            type: 'POST',
            data: {
                loc_id: loc_id,
                send_location: send_location,
                selected: selected
            },
            url: "<?php echo base_url();?>school_setting/get_location",
            dataType: "html",
            success: function(response) {
                $('.' + send_location).html(response);

                $("#loading").remove();

            }
        });
    }



    <?php 

if($scr[0]['province_id'] > 0)
{
	?>

    get_location(<?php echo $scr[0]['country_id'];?>, 'province', <?php echo $scr[0]['province_id'];?>);
    <?php	
}
if($scr[0]['city_id'] > 0)
{
	?>

    get_location(<?php echo $scr[0]['province_id'];?>, 'city', <?php echo $scr[0]['city_id'];?>);
    <?php	
}
if($scr[0]['location_id'] > 0)
{
	?>

    get_location(<?php echo $scr[0]['city_id'];?>, 'location', <?php echo $scr[0]['location_id'];?>);
    <?php	
	
}

}//end of if
?>
    </script>
