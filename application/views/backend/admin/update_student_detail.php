<?php
$dep_arr = $this->db->query( "SELECT 
	d.title as department 
	FROM " . get_school_db() . ".departments d
	INNER JOIN " . get_school_db() . ".class c 
	ON d.departments_id = c.departments_id
	WHERE c.school_id=" . $_SESSION[ 'school_id' ] . " 
	AND c.class_id =" . intval( $rows[ 0 ][ 'section_id' ] ) )->result_array();

$loc_arr = $this->db->query( "select 
	cl.title as location,c.title as city, p.title as province, cntry.title as country 
	FROM " . get_school_db() . ".city_location cl
	INNER JOIN " . get_system_db() . ".city c 
	ON cl.city_id = c.city_id
	INNER JOIN " . get_system_db() . ".province p 
	ON p.province_id = c.province_id
	INNER JOIN " . get_system_db() . ".country cntry 
	ON cntry.country_id = p.country_id
	WHERE cl.school_id=" . $_SESSION[ 'school_id' ] . " 
	AND cl.location_id =" . intval( $rows[0]['location_id'] ) )->result_array();

?>
<style>
    h3{
        font-size: 17px !important;
        color: #6d8bc6 !important;
        border-bottom: 1px solid rgba(204, 204, 204, 0.38);
        margin-bottom: 20px;
        margin-top: 11px;
        padding-bottom: 6px;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('student_profile_update');?>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
            if($this->session->flashdata('club_updated')){
            	echo '<div align="center">
            	        <div class="alert alert-success alert-dismissable">
            	            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                            '.$this->session->flashdata('club_updated').'
            	        </div> 
            	    </div>';
            }
        ?>
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body" style="padding:0px;">
                <?php echo form_open(base_url().'c_student/save_update_student_details/' , array('id'=>'student_add_edit','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
                    <div class="row" data-step="1" data-position='top' data-intro="Step 1: enter official information">
                    <div class="col-md-12">
                        <h3><?php echo get_phrase('official_detail');?> </h3>
                    </div>                    
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-1" class="control-label">
                        <?php echo get_phrase('form_number');?> <span class="red"> * </span></label>
                        <input maxlength="50" type="text" class="form-control" name="form_number" value="<?=$rows[0]['form_num']?>" autofocus readonly>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="field-1" class="control-label">
                            <?php echo get_phrase('GR_number');?> <span class="red"> * </span></label>
                            <?php
                                $get_max_gr_no = $this->db->query("SELECT MAX(gr_no) AS GR FROM ".get_school_db().".student WHERE school_id = '".$_SESSION['school_id']."'");
                                $get_gr_row = $get_max_gr_no->row();
                                $get_gr = $get_gr_row->GR;
                            ?>
                            <input type="number" class="form-control" name="gr_number" value="<?=$get_gr+1;?>" readonly="">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label class="control-label">
                            <?php echo get_phrase('class-Section');?><span class="red"> * </span></label>
                            <label id="section_id1_selection"></label>
                            <select id="section_id1" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required" class="form-control" disabled>
                                <?php echo section_selector($rows[0]['section_id']);?>
                            </select>
                            <script>
                            $(document).ready(function() {
                                $('.selectpicker').on('change', function() {
                                    var id = $(this).attr('id');
                                    var selected = $('#' + id + ' :selected');
                                    var group = selected.parent().attr('label');
                                    $('#' + id + '_selection').text(group);
                                });


                            });
                            </script>
                            <div id="section-err"></div>
                    </div>
                </div>
                    <div class="row" data-step="2" data-position='top' data-intro="Step 2: enter basic information">
                        <div class="col-md-12">
                            <h3><?php echo get_phrase('basic_information');?> </h3>
                            <input type="hidden" name="student_id" value="<?=$rows[0]['student_id']?>">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('full_name');?><span class="red"> * </span></label>
                                <input maxlength="100" type="text" class="form-control" name="name" data-message-required="<?php echo get_phrase('value_required');?>" value="<?=$rows[0]['name']?>" autofocus required="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('date_of_birth');?></label>
                                <input type="text" id="mydob" class="form-control datepicker" name="birthday" value="<?=date("d-m-Y",strtotime($rows[0]['birthday']))?>" data-start-view="2"  data-format="dd/mm/yyyy">
                                <!--onfocus="this.blur()"-->
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('gender');?><span class="red"> * </span></label>
                                <select name="sex" class="form-control sex" required>
                                    <option value="">
                                        <?php echo get_phrase('select');?>
                                    </option>
                                    <option value="male">
                                        <?php echo get_phrase('male');?>
                                    </option>
                                    <option value="female">
                                        <?php echo get_phrase('female');?>
                                    </option>
                                </select>
                                <script>
                                    $(".sex").val('<?=$rows[0]['gender']?>');
                                </script>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('religion');?><span class="red"> * </span></label>
                                <select name="religion" class="form-control religion" required>
                                    <option value="">
                                        <?php echo get_phrase('select');?>
                                    </option>
                                    <option value="1">
                                        <?php echo get_phrase('muslim');?>
                                    </option>
                                    <option value="2">
                                        <?php echo get_phrase('christian');?>
                                    </option>
                                    <option value="3">
                                        <?php echo get_phrase('Hindu');?>
                                    </option>
                                    <option value="4">
                                        <?php echo get_phrase('sikh');?>
                                    </option>
                                    <option value="5">
                                        <?php echo get_phrase('other');?>
                                    </option>
                                </select>
                                <script>
                                    $(".religion").val('<?=$rows[0]['religion']?>');
                                </script>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="field-2" class="control-label">
                            <?php echo get_phrase('mobile_no');?><span class="red"> * </span></label>
                            <input required="" maxlength="15" minlength="11" type="text" class="form-control" name="mob_num" value="<?=$rows[0]['mob_num']?>" placeholder="Minimum 11 digits without space or dashes ( - )">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('emergency_no');?><span class="red"> * </span></label>
                                <input maxlength="20" type="text" class="form-control" name="emg_num" value="<?=$rows[0]['emg_num']?>">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="field-1" class="control-label">
                            <?php echo get_phrase('previous_school');?> <span class="red"> * </span></label>
                            <input type="text" class="form-control" name="prev_school" value="<?=$rows[0]['previou_school']?>">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="field-1" class="control-label">
                            <?php echo get_phrase('student_intrested_in_other_/_extra_activities');?> <span class="red"> * </span></label>
                            <input type="text" class="form-control" name="activities" value="<?=$rows[0]['std_activities']?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('postal_address');?>
                            </label>
                                <textarea rows="4" id="address_cont" class="form-control" name="address" oninput="count_value('address_cont','address_count','200')" maxlength="200"><?=$rows[0]['address']?></textarea>
                                <div id="address_count"></div>
                        </div>
                    </div>
                    <div class="row" data-step="3" data-position='top' data-intro="Step 3: add student more infortmation">
                        <div class="col-md-12">
                            <hr>
                            <h3><?php echo get_phrase('add_more_information');?> </h3>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('student_category');?>
                                </label>
                                <select name="student_category" class="form-control">
                                	<?php echo student_category($rows[0]['student_category_id']);?>	
                                </select>	
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('nationality');?>
                                </label>
                                <select name="nationality" class="form-control">
                                	<?php echo country_option_list($rows[0]['nationality']);?>	
                                </select>	
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('id_type');?><span class="red"> * </span>
                                </label>
                                <?php echo id_type_list('student_id_type','form-control',$rows[0]['id_type'],'disabled');?>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                <?php echo get_phrase('b_form_id_no');?><span class="red"> * </span>
                                </label>
                                <input type="text" class="form-control" required id="s_cnic" name="form_b" value="<?=$rows[0]['id_no']?>" placeholder="National Id Card No" onchange="get_cnic('s_cnic','s','student')" disabled=""  maxlength="30" onkeyup="nospaces(this)">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-1" class="control-label">
                                    <?php echo get_phrase('email');?>
                                </label>
                                <input maxlength="50" id="email" type="text" class="form-control" name="email" value="<?= $rows[0]['email'] ?>">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('blood_group');?>
                                </label>
                                <input maxlength="10" type="text" class="form-control" name="bd_group" value="<?= $rows[0]['bd_group'] ?>">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('disability(if any)');?>
                                </label>
                                <input maxlength="100" type="text" class="form-control" name="disability" value="<?= $rows[0]['disability'] ?>">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('permanent_address');?>
                                </label>
                                <textarea rows="4" id="address_cont_p" class="form-control" name="p_address" oninput="count_value('address_cont_p','address_count_p','200')" maxlength="200"><?= $rows[0]['p_address'] ?></textarea>
                                <div id="address_count_p"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" data-step="5" data-position='top' data-intro="press this button & admission new student">
                        <div class="form-group float-right" style="margin-right: 30px;">
                            <input id="btn1" type="submit" value="<?php echo get_phrase('update_profile');?>" class="modal_save_btn">
                        </div>
                    </div>
            </div>
                <?php echo form_close();?>
        </div>
    </div>
</div>
</div>
    <script>
    $('#email').change(function() {
        get_email();
    });

    function get_email() {

        $('#email').after('<div id="icon" class="loader_small"></div>');

        var email = $('#email').val();
        $.ajax({
            type: 'POST',
            data: {
                email: email
            },
            url: "<?php echo base_url();?>admin/call_function",
            dataType: "html",
            success: function(response) {
                if ($.trim(response) == 'yes') {

                    $("#btn1").attr('disabled', 'true');
                    $("#email").css('border', '1px solid red');
                    $("#icon").remove();

                    if ($('#message').html() == undefined) {
                        $("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_already_exist'); ?></p>');
                    }
                } else {

                    $("#btn1").removeAttr('disabled');
                    $("#email").css('border', '1px solid green');
                    $("#icon").remove();
                    $("#message").remove();
                }
            }
        });
    }
    function get_cnic(cnic_n, type_n, table_name, detail_field) {
        var cnic = $('#' + cnic_n).val();
		//
        //return false;
        $("#message_scnic").remove();
		//alert(table_name);

        $('#' + cnic_n + type_n).remove();

        $('#' + cnic_n).after('<div id="' + cnic_n + type_n + '" class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            
            data: {
                cnic: cnic,
                type_n: type_n,
                table_name: table_name
            },
           url: "<?php echo base_url();?>admin/get_cnic_stu",
            dataType: "json",
            //dataType: "html",
            success: function(response) {
   //             console.log("response is : " +response);
   //          	alert(response);
			// alert(response.value);

                $('#' + cnic_n + type_n).remove();



                if (type_n == 's') {
                    if ($.trim(response.value) == 'no') {
                    	
                        $("#btn1").removeAttr('disabled');
                        $("#" + cnic_n).css('border', '1px solid green');
                        $("#message_scnic").remove();
                    } else {
                        $("#btn1").attr('disabled', 'true');
                        $("#" + cnic_n).css('border', '1px solid red');

                        if ($('#message_scnic').html() == undefined) {
                            $("#" + cnic_n).before('<p id="message_scnic" style="color:red;"><?php echo get_phrase('id_already_exist'); ?></p>');
                        }
                    }





                    $("#" + cnic_n + type_n).remove();
                } else if (type_n == 'f') {

                    if ($.trim(response.value) == 'no') {
                    	
                        $('#f_name').removeAttr("readonly").val('');
                        
                        //$('#f_cnic').removeAttr("readonly").val('');
                        $('#f_num').removeAttr("readonly").val('');
                        $('#f_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#f_name').val(response[0].p_name).attr("readonly", "true");
                        //$('#f_cnic').val(response[0].cnic);
                        $('#f_num').val(response[0].contact).attr("readonly", "true");
                        $('#f_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();

                } else if (type_n == 'm') {

                    if ($.trim(response.value) == 'no') {
                        $('#m_name').removeAttr("readonly").val('');
                        //$('#m_cnic').removeAttr("readonly").val('');
                        $('#m_num').removeAttr("readonly").val('');
                        $('#m_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#m_name').val(response[0].p_name).attr("readonly", "true");
                        //$('#f_cnic').val(response[0].cnic);
                        $('#m_num').val(response[0].contact).attr("readonly", "true");
                        $('#m_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();

                } else if (type_n == 'g') {

                    if ($.trim(response.value) == 'no') {
                        $('#g_name').removeAttr("readonly").val('');
                        //$('#m_cnic').removeAttr("readonly").val('');
                        $('#g_num').removeAttr("readonly").val('');
                        $('#g_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#g_name').val(response[0].p_name).attr("readonly", "true");
                        //$('#f_cnic').val(response[0].cnic);
                        $('#g_num').val(response[0].contact).attr("readonly", "true");
                        $('#g_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();
                }



                //get_student(cnic,detail_field);


            }

        });



    }


    function get_student(cnic, detail_field) {
        $('#' + detail_field).html(' ');

        $.ajax({
            type: 'POST',
            data: {
                cnic: cnic
            },
            url: "<?php echo base_url();?>admin/get_student_new",
            //dataType: "json",
            dataType: "html",
            success: function(response) {

                //var obj = jQuery.parseJSON(response);
                //var parsed = JSON.parse(response);


                $('#' + detail_field).html(response);


                //alert(response);
                //alert(detail_field);


            }
        });









    }





    $(document).ready(function() {
        $("#departments_id").change(function() {
            var dep_id = $(this).val();

            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    dep_id: dep_id
                },
                url: "<?php echo base_url();?>c_student/get_class",
                dataType: "html",
                success: function(response) {



                    $("#icon").remove();

                    $("#class_id").html(response);
                    $("#section_id").html("<option><?php echo get_phrase('select_section'); ?></option>");


                }
            });



        });

        $("#class_id").change(function() {
            var class_id = $(this).val();

            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    class_id: class_id
                },
                url: "<?php echo base_url();?>c_student/get_section",
                dataType: "html",
                success: function(response) {



                    $("#icon").remove();

                    $("#section_id").html(response);



                }
            });



        });

        $(".country").change(function() {
            var loc_id = $(this).val();
            var send_location = 'provience';


            if (loc_id == "") {

            } else {

                get_location(loc_id, send_location);
                $(".provience").html("<option><?php echo get_phrase('select_province'); ?></option>");
                $(".city").html("<option><?php echo get_phrase('select_city'); ?></option>");
                $(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
            }


        });


        $(".provience").change(function() {


            var loc_id = $(this).val();
            var send_location = 'city';

            if (loc_id == "") {

            } else {

                get_location(loc_id, send_location);
                $(".city").html("<option><?php echo get_phrase('select_city'); ?></option>");
                $(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
            }
        });




        $(".city").change(function() {

            var loc_id = $(this).val();
            var send_location = 'location';


            if (loc_id == "") {

            } else {

                get_location(loc_id, send_location);
                $(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
            }
        });





    });

    function get_location(loc_id, send_location) {

        $("#loading").remove();
        $('.' + send_location).after("<div id='loading' class='loader_small'></div>");



        $.ajax({
            type: 'POST',
            data: {
                loc_id: loc_id,
                send_location: send_location
            },
            url: "<?php echo base_url();?>c_student/get_location",
            dataType: "html",
            success: function(response) {
                $('.' + send_location).html(response);

                $("#loading").remove();


            }
        });



    }
    
/*function nospaces(t)
{
	if(t.value.match(/\s/g))
	{
		t.value=t.value.replace(/\s/g,'');
	}

}*/
    
    
    
    </script>
    
    
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
        margin-right: auto;
        margin-left: auto;
    }
    
    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin-right: auto;
        margin-left: auto;
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
