<?php
    $std_id = $this->uri->segment(4);
    $std_data = $this->db->get_where(get_school_db().'.student' , array('student_id' => $std_id,'school_id' =>$_SESSION['school_id']) )->result_array();
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('move_to_student');?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'c_student/move_to_student/'.$std_id , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top')); ?>
                    <div class="form-group">
                        <label class="control-label">Roll Number <span class="red"> * </span> </label>
                        <input type="number" class="form-control" name="roll_number" required="">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Admission Date <span class="red"> * </span> </label>
                        <input type="date" class="form-control" name="adm_date" required="">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Select Status <span class="red"> * </span> </label>
                        <select class="form-control" required="">
                            <option value="">Select Status</option>
                            <option value="10">Move To Student</option>
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