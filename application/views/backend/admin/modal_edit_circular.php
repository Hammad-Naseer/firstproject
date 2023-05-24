<?php 
    $query="select cr.*,d.departments_id as departments_id,d.title as dept_name,c.class_id as class_id, cs.section_id as section_id FROM ".get_school_db().".circular cr INNER join ".get_school_db().".class_section cs ON cr.section_id=cs.section_id Inner JOIN ".get_school_db().".class c On cs.class_id=c.class_id Inner join ".get_school_db().".departments d On d.departments_id=c.departments_id WHERE cr.circular_id= ".str_decode($param2)." AND cr.school_id=".$_SESSION['school_id']."";
    $edit_data=$this->db->query($query)->result_array();	
?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title black2">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_circular');?>
        </div>
    </div>
    <div class="panel-body">
        <?php foreach($edit_data as $row):?>
        <?php echo form_open_multipart(base_url().'circular/circulars/do_update/'.$row['circular_id'] , array('id'=>'circular_edit','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('department');?><span class="red"> * </span></label>
                <label id="section_id1_selection">
                    <?php echo $row['dept_name'];?>
                </label>
                <select id="section_id1" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">
                    <?php echo section_selector($row['section_id']);?>
                </select>
                <div id="section-err"></div>
            </div>
            <div class="form-group" id="students1">
                <label class="control-label">
                    <?php echo get_phrase('select_student');?>
                </label>
                <select name="student_id" id="student_id1" class="form-control">
                    <?php   
                        $section_id =  $row['section_id'];                                       
                        echo section_student($section_id,$row['student_id']);?>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('title');?><span class="red"> * </span></label>
                    <input type="text" class="form-control" name="circular_title" value="<?php echo $row['circular_title'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" maxlength="100" />
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('date');?><span class="red"> * </span></label>
                    <input type="date" class="form-control" name="create_timestamp" id="date_edit1234" value="<?php echo $row['create_timestamp']; ?>"
                           data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                    <div id="error_date"></div>
            </div>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('detail');?> <span class="red"> * </span></label>
                <textarea name="circular" id="circular_edit" rows="5" class="form-control" maxlength="1000" oninput="count_value('circular_edit','area_count1','1000')" placeholder="<?php echo get_phrase('add_circular');?>"><?php echo $row['circular'];?></textarea>
                <div id="area_count1" class="col-sm-12 "></div>
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('attachment');?>
                </label>
                <input value="" type="file" class="form-control" name="image2" id="image2" onchange="file_validate('image2','doc','img_f_msg')">
                <span style="color: green;">
                <?php echo get_phrase('allowed_file_size');?>
                :  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                 <br />
                <span style="color: red;" id="img_f_msg"></span>
                <span id="id_file">				
    		        <?php
                        $val_im=display_link($row['attachment'],'circular',0,0); 
                        if($val_im!=""){
                    ?>	
                    <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment');?></a>
                    <a onclick="delete_files('<?php echo $row['attachment']; ?>','circular','circular_id','<?php echo $row['circular_id']; ?>','attachment','circular','id_file',2)" href="#" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment');?></a>
                    <?php } ?>				
    			</span>
            </div>
            <input type="hidden" name="image_old" value="<?php echo $row['attachment']; ?>" />
        	<div class="form-group">
		        <label class="control-label"><?php echo get_phrase('is_active');?><span class="red"> * </span></label>
		        	<select name="is_active" class="form-control">
		        		<option value="1" <?php if($row['is_active']==1){ echo "selected";} ?> ><?php echo get_phrase('yes');?></option>
		        		<option value="0" <?php if($row['is_active']==0){ echo "selected";} ?> ><?php echo get_phrase('no');?></option>
		        	</select>
		    </div>
            <div class="form-group">
                <div <?= check_sms_preference(10,"style","sms") ?>>
                    <label class="control-label">
                    <?php echo get_phrase('send_sms');?><span class="red"> * </span></label>
                    <input  id="send_message" class=""  type="checkbox" name="send_message" />
                    <div id="error_date1"></div>
                </div>    
            </div>
            <span style="color:red;">
	            <?php if($row['sms_status']){ ?>
                <strong> <?php echo get_phrase('Note');?></strong>:
                <?php echo get_phrase('sms_already_send');?>
		        <?php } ?>
            </span>
            <div class="form-group">
                <div <?= check_sms_preference(10,"style","email") ?>>
                    <label class="control-label"><?php echo get_phrase('send_email');?><span class="red"> * </span></label>
                    <input id="" class=""  type="checkbox" name="send_email" />
                    <div id="error_date1"></div>
                </div>    
            </div>
            <div class="form-group">
                <div class="float-right">
					<button type="submit" class="modal_save_btn" id="btn_edit">
						<?php echo get_phrase('update');?>
					</button>
					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
						<?php echo get_phrase('cancel');?>
					</button>
				</div>
            </div>
        </form>
        <?php endforeach;?>
    </div>
</div>
<script>
$(document).ready(function() {
    //$('.selectpicker').selectpicker();
    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);
    });
    $("#departments_id1").change(function() {
        $('#get_planner').html('');
        var dep_id = $(this).val();
        $("#icon").remove();

        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            data: {
                department_id: dep_id
            },
            url: "<?php echo base_url();?>circular/get_class",
            dataType: "html",
            success: function(response) {



                $("#icon").remove();

                $("#class_id1").html(response);
                $("#section_id1").html('<select><option value=""><?php echo get_phrase('select_section'); ?></option></select>');


            }
        });



    });


    $("#class_id1").change(function() {
        $('#get_planner').html('');
        var class_id = $(this).val();
        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            data: {
                class_id: class_id
            },
            url: "<?php echo base_url();?>circular/get_class_section",
            dataType: "html",
            success: function(response) {
                $("#icon").remove();

                $("#section_id1").html(response);


            }
        });



    });

    $("#section_id1").change(function() {
        $('#get_planner').html('');
        var section_id = $(this).val();

        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id
            },
            url: "<?php echo base_url();?>admin/get_section_student",
            dataType: "html",
            success: function(response) {
                $("#icon").remove();

                $("#student_id1").html(response);


            }
        });
    });

    $('#acad_year2').change(function() {
        var acad_year = $(this).val();
        get_year_term2(acad_year);
    });

    function get_year_term2() {
        $('#acad_year2').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        var acad_year = $('#acad_year2').val();



        $.ajax({
            type: 'POST',
            data: {
                acad_year: acad_year
            },
            url: "<?php echo base_url();?>circular/get_year_term2",
            dataType: "html",
            success: function(response) {
                $('#message').remove();
                if ($.trim(response) != "") {
                    $('#yearly_terms2').html(response);
                }
                if ($.trim(response) == "") {
                    $('#yearly_terms2').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
                }
            }
        });




    }

    $('#date_edit').on('change', function() {
        $('#btn_edit').removeAttr('disabled', 'true');
        $('#error_date').text('');
        var date1 = $(this).val();
        var term_id = $('#yearly_terms2').val();
        if (date1 != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>circular/term_date_range",

                data: ({
                    date1: date1,
                    term_id: term_id
                }),
                dataType: "html",
                success: function(html) {
                    if (html == 0) {
                        $('#error_date').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');
                        $('#btn_edit').attr('disabled', 'true');
                    }
                }
            });
        }

    });

    $("#yearly_terms2").change(function() {
        var date1 = $("#date_edit").val();

        var term_id = $(this).val();
        if (date1 != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>circular/term_date_range",

                data: ({
                    date1: date1,
                    term_id: term_id
                }),
                dataType: "html",
                success: function(html) {
                    if (html == 0) {
                        $('#error_date').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');
                        $('#btn_edit').attr('disabled', 'true');
                    } else {
                        $('#btn_edit').removeAttr('disabled');
                        $('#error_date').text('');
                    }
                }
            });
        }
    });












});
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>
