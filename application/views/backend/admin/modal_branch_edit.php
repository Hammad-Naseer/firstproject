<?php 

$login_type_id = get_login_type_id('branch_admin');


if($param2 == ""){
    $param2=$this->uri->segment(3);
}
if($param3 == ""){
    $param3=$this->uri->segment(4);
}

$query="select sch.school_id as school_id, sch.name, sch.address, sch.phone, sch.logo as logo,ps.package_id,ps.is_trial,ps.is_valid,ps.start_date,ps.end_date, sch.url, sch.email as school_email,sch.contact_person, sch.designation, sch.slogan, sch.detail, sch.city_id as city_id, sch.school_regist_no,sch.folder_name,sch.country_id as country_id,sch.province_id as province_id,sch.location_id as location_id, ul.user_login_id as user_login_id, ul.name as admin_name, ul.email as admin_email, ul.password, uld.status as status, uld.sys_sch_id
        FROM ".get_school_db().".school sch
        INNER JOIN ".get_system_db().".user_login_details uld ON uld.sys_sch_id = sch.sys_sch_id
        INNER JOIN ".get_system_db().".user_login ul ON ul.user_login_id = uld.user_login_id
        LEFT JOIN ".get_system_db().".package_subscription ps ON ps.sys_school_id = sch.sys_sch_id
        where 
        uld.sys_sch_id = $param3
        and uld.login_type = $login_type_id
        and sch.school_id=".$param2." 
        and uld.user_login_id != ".$_SESSION['user_login_id']."
        ";
        
$edit_data = $this->db->query($query)->result_array();
// echo $this->db->last_query();
/*echo "<pre>";
print_r($edit_data);    */

//print_r($edit_data);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('edit_branch'); ?>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12 thisrow">
        <!--<div class="panel panel-primary" data-collapsed="0">-->
        <?php foreach ($edit_data as $row): ?>
     <!--   <style>-->
    	<!--  .form-group{margin:5px;}-->
    	<!--  .form-control{    -->
    	<!--        margin-bottom: 16px;-->
     <!--           margin-top: -6px;-->
    	<!--  }-->
	    <!--</style>-->
        <!--<div class="panel-heading">-->
        <!--    <div class="panel-title">-->
        <!--        <i class="entypo-plus-circled"></i>-->
        <!--        <?php echo get_phrase('edit_branch');?>-->
        <!--    </div>-->
        <!--</div>    -->
        <!--<div class="panel-body">-->
        <h3> <?php echo get_phrase('admin_details');?> </h3>
            <?php echo form_open(base_url().'branch/branches/do_update/'.$row['school_id'].'/'.$row['user_login_id'] , array('id'=>'branch_edit','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                <div class="form-group col-md-6">
                    <label class="control-label">
                    <?php echo get_phrase('admin_name');?> <span class="red"> * </span></label>
                    <input type="text" class="form-control" readonly name="admin_name" maxlength="100" value="<?php echo $row['admin_name'];?>" />
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                    <?php echo get_phrase('admin_email');?> <span class="red"> * </span></label>
                    <input type="text" class="form-control" readonly name="admin_email" id="email" maxlength="100" value="<?php echo $row['admin_email'];?>" onkeypress="get_email()" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('password');?>
                    </label>
                    <input type="text" class="form-control" readonly name="password" value="" maxlength="100" />
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label"><?php echo get_phrase('branch_name');?> <span class="red"> * </span></label>
                    <input type="text" class="form-control" name="branch_name" value="<?php echo $row['name'];?>" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label"> Package <span class="red"> * </span></label>
                    <select name="package_id" class="form-control" required >
                        <?php echo package_option_list(intval($row['package_id'])); ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        Is Trial <span class="red"> * </span>
                    </label>
                    <select class="form-control is_trials" name="is_trial" required="">
                        <option value="">Select Trial Status</option>
                        <option value="1">Trial Version</option>
                        <option value="2">Regular Package Issue</option>
                    </select>
                </div>    
                    <script>
                        $(".is_trials").val('<?=$row['is_trial']?>');
                    </script>
                <div class="form-group col-md-6">
                    <label>Start Date</label>
                    <input type="date" class="form-control" value="<?php if($row['start_date'] != ""){ echo date("Y-m-d",strtotime($row['start_date']));}else{echo date("Y-m-d");} ?>" name="trial_date_start">
                </div>
                <div class="form-group col-md-6">
                    <label>End Date</label>
                    <?php  
                        // Trial End Date Default 30 Days Set
                        $DateS = date("Y-m-d");
                        $end_dates = date('Y-m-d', strtotime($DateS. ' + 30 days'));
                    ?>
                    <input type="date" class="form-control" value="<?php if($row['end_date'] != ""){ echo date("Y-m-d",strtotime($row['end_date']));}else{echo $end_dates;} ?>" name="trial_date_end">
                </div>
                
                <input type="hidden" class="form-control" name="old_image" value="<?php echo $row['logo']; ?>">
                <input type="hidden" class="form-control" name="folder_hidden" value="<?php echo $row['folder_name']; ?>">
               
                   
                
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('country');?>
                    </label>
                    <select name="country_id" class="form-control country_id">
                        <?php echo country_option_list($row['country_id']); ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('province');?>
                    </label>
                    <select name="province_id" class="form-control province_id">
                        <?php echo province_option_list($row['country_id'],$row['province_id']);?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('city');?>
                    </label>
                    <select name="city_id" class="form-control city_id">
                        <?php echo city_option_list($row['province_id'],$row['city_id']); ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('location');?>
                    </label>
                    <select name="location_id" class="form-control location_id">
                        <?php echo location_option_list($row['city_id'],$row['location_id']); ?>
                    </select>
                </div>
                <div class="row" style="padding-left: 15px;padding-right: 15px;">
                    <div class="form-group col-md-12">
                        <label class="control-label">
                            <?php echo get_phrase('address');?>
                        </label>
                        <textarea class="form-control" name="address" id="address1" rows="5" maxlength="500" oninput="count_value('address1','text_count','1000')"><?php echo $row['address'];?></textarea>
                        <div id="text_count" class="col-sm-12 "></div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('phone');?>
                    </label>
                    <input type="text" class="form-control" name="phone" value="<?php echo $row['phone'];?>" maxlength="30" />
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('url');?>
                    </label>
                    <input type="text" class="form-control" name="url" value="<?php echo $row['url'];?>" maxlength="50" />
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('school_email');?>
                    </label>
                    <input type="text" class="form-control" name="school_email" value="<?php echo $row['school_email'];?>" maxlength="50" />
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('contact_person');?>
                    </label>
                    <input type="text" class="form-control" name="contact_person" value="<?php echo $row['contact_person'];?>" maxlength="30" />
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('designation');?>
                    </label>
                    <input type="text" class="form-control" name="designation" value="<?php echo $row['designation'];?>" maxlength="30" />
                </div>
                
                
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('slogan');?>
                    </label>
                    <input type="text" class="form-control" name="slogan" value="<?php echo $row['slogan'];?>" maxlength="100" />
                </div>
                <div class="row" style="padding-left: 15px;padding-right: 15px;">
                    <div class="form-group col-md-12">
                        <label class="control-label">
                            <?php echo get_phrase('detail');?>
                        </label>
                        <textarea class="form-control" name="detail" id="detail1" rows="5" maxlength="2000" oninput="count_value('detail1','text_count1','2000')"><?php echo $row['detail'];?></textarea>
                        <div id="text_count1" class="col-sm-12 "></div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('school_registration_no');?>
                    </label>
                    <input type="text" class="form-control" name="school_regist_no" value="<?php echo $row['school_regist_no'];?>" maxlength="50">
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">
                        <?php echo get_phrase('status');?>
                    </label>
                    <?php echo status('status','form-control',$row['status']);?>
                </div>
                
                <!--logo-->
                <div class="form-group col-md-6">
                    <label for="field-1" class="control-label">
                        <?php echo get_phrase('logo');?>
                    </label>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                <div id="student_image">                    
                <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput" >
                <img src='<?php
                if($row["logo"]=="")
                {
                echo  base_url().'/uploads/default.png';    
                }
                else
                {
                 echo  display_link($row['logo'],$row['folder_name'],1);    
                }
                ?>' alt="...">

                </div>
                <?php 
                    if($row['logo']!="")
                {?>
                <a class="btn btn-primary" onclick="delete_files('<?php echo $row['logo']; ?>','school','school_id','<?php echo $row['school_id']; ?>','logo','<?php echo $row['folder_name'];?>','student_image','1','','0','1')">
                    <?php echo get_phrase('delete_image');?>
                </a>
                <?php
                }
                ?>
                </div>
                
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px">
                </div>
                <div>
                    <span class="btn btn-white btn-file">
                        <span class="fileinput-new"><?php echo get_phrase('select_image');?></span>
                        <span class="fileinput-exists"><?php echo get_phrase('change');?></span>
                        <input type="file" name="userfile" id="userfile1" onchange="file_validate('userfile1','img','img_g_msg')" accept="image/*">
                    </span>
                <br/>                                        
                <span style="color: green;">
                <?php echo get_phrase('allowed_file_size');?>
                : 200kb, <?php echo get_phrase('allowed_file_types');?>: png, jpg, jpeg </span>
                <br />
                <span style="color: red;" id="img_g_msg"></span>
                <br />
                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove');?></a>
                </div>
                </div>
                </div>
                <!--logo close-->
                <div class="form-group col-md-6">
                    <label class="control-label">
                        Is Valid <span class="red"> * </span>
                    </label>
                    <?php if($row['is_valid'] == 1){ ?>
                    <input type="checkbox" name="is_valid" id="is_valid" value="<?=$row['is_valid']?>" checked="">
                    <?php }else{ ?>
                    <input type="checkbox" name="is_valid" id="is_valid" value="0">
                    <?php } ?>
                </div>
              </div>
              </div>
                <div class="form-group">
                    <input type="hidden" id="user_login_id" value="<?php echo $row['user_login_id']; ?>" >
                    <input type="hidden" name="sys_sch_id" value="<?php echo $row['sys_sch_id']; ?>" >
                    <div class="float-right">
    					<button type="submit" id="btn1" class="modal_save_btn">
    						<?php echo get_phrase('submit');?>
    					</button>
    					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    						<?php echo get_phrase('cancel');?>
    					</button>
    				</div>
                <!--</div>-->
                <?php echo form_close();?>
                <!--</div>        -->
            
            
        <!--    </div>-->
        <!--</div>-->
    </div>
</div>
<?php
endforeach;
?>
</div>
</div>
    <script>
    var flag_email = 0;
    $(document).ready(function() {

        $(".country_id").change(function() {
            var country_id = $(this).val();
            province(country_id);
        });

        $(".province_id").change(function() {
            var province_id = $(this).val();
            city(province_id);
        });

        $(".city_id").change(function() {
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
                $(".city_id").html('<select><option><?php echo get_phrase('select_city'); ?></option></select>');
                $(".location_id").html('<select><option><?php echo get_phrase('select_locatioin'); ?></option></select>');

                //alert(response);
                $('.province_id').html(response);

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
                $(".location_id").html('<select><option><?php echo get_phrase('select_location'); ?></option></select>');
                //alert(response);
                $('.city_id').html(response);

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
                $('.location_id').html(response);

                $("#loading").remove();
                //alert(response);

            }
        });
    }

    function get_email() {

        $('#email').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        var email = $('#email').val();
        var user_login_id = $('#user_login_id').val();
        $.ajax({
            type: 'POST',
            data: {
                email: email, id:user_login_id
            },
            url: "<?php echo base_url();?>branch/call_function/update",
            dataType: "html",
            success: function(response) {
                if ($.trim(response) == 'yes') {

                   // $("#btn1").attr('disabled', 'true');
                    $("#email").css('border', '1px solid red');
                    $("#icon").remove();
                    flag_email = 0;

                    if ($('#message').html() == undefined) {
                        $("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_is_already_exist'); ?></p>');
                    }
                } else {

                    $("#btn1").removeAttr('disabled');
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
        if (flag_cnic == 1 && flag_email == 1) {
            $("#btn1").removeAttr('disabled');
            //alert("i am in if--"+flag_cnic+'--'+flag_email);
        } else {
            $("#btn1").attr('disabled', 'true');
            //alert("i am in else--"+flag_cnic+'--'+flag_email);
        }

    }
    </script>
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
    .form-horizontal .form-group {
        margin-left: 0px;
        margin-right: 0px;
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
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>