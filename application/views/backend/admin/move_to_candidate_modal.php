<?php
    // Get Admission Inquiry Record
    $adm_inq_id = $this->uri->segment(4);
    $get_inquiry_data = $this->db->query("SELECT class_id FROM ".get_school_db().".sch_admission_inquiries WHERE s_a_i_id = $adm_inq_id ")->row();
    $section_id = $get_inquiry_data->class_id;
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('move_to_candidate');?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'Inquiries/move_to_candidate' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('class_section');?><span class="red"> * </span>
                        </label>
                         <select id="section_id1" class="selectpicker form-control wizard_validate" name="section_id" required="required" class="form-control" required="">
                            <?php echo section_selector($section_id);?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="field-1" class="control-label"><?php echo get_phrase('form_number');?> <span class="red"> * </span></label>
                        <input maxlength="50" type="text" class="form-control" name="form_number" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('father_id_type');?><span class="red"> * </span>
                        </label>
                        <?php echo id_type_list('id_type_f','form-control wizard_validate');?>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Father ID No <span class="red"> * </span> </label>
                        <input minlength="3" maxlength="30" type="text" class="form-control fcnic" oninput="get_cnic('f_cnic','f','student_parent','rec_1')" id="f_cnic" name="f_cnic" required="" autofocus onkeyup="nospaces(this)">
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('student_id_type');?><span class="red"> * </span>
                        </label>
                        <?php echo id_type_list('student_id_type','form-control wizard_validate');?>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Student ID No <span class="red"> * </span> </label>
                        <input type="text" class="form-control" required id="s_cnic" name="form_b" placeholder="National Id Card No" onchange="get_cnic('s_cnic','s','student')" value=""  maxlength="30" onkeyup="nospaces(this)">
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label"><?php echo get_phrase('Gender');?><span class="red"> * </span></label>
                        <select name="sex" class="form-control wizard_validate" required="required">
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
                        <input type="hidden" name="adm_id" value="<?= $this->uri->segment(4); ?>">
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label"><?php echo get_phrase('select_group');?> <span class="red"> * </span></label>
                        <select name="user_group_id" class="form-control wizard_validate" required="">
                            <?php echo user_group_option_list();?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn"><?php echo get_phrase('submit');?></button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    function get_cnic(cnic_n, type_n, table_name, detail_field) {
        var cnic = $('#' + cnic_n).val();
		//
        //return false;
        $("#message_scnic").remove();
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
</script>