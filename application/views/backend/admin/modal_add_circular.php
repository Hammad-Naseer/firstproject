<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title black2">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('add_cricular');?>
        </div>
    </div>
    <div class="panel-body">
        <?php echo form_open_multipart(base_url().'circular/circulars/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'circular_add'));?>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('department');?><span class="red"> * </span></label>
                <label id="section_id_selection"></label>
                <select id="section_id" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">
                    <?php echo section_selector();?>
                </select>
                <div id="section-err"></div>
            </div>
            <div class="form-group" id="students">
                <label class="control-label">
                    <?php echo get_phrase('select_student');?>
                </label>
                <select name="student_id" id="student_id" class="form-control">
                    <option value="">
        			<?php echo get_phrase('');?>
        			<?php echo get_phrase('select_student');?></option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('title');?><span class="red"> * </span></label>
                    <input type="text" class="form-control" name="circular_title" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('date');?><span class="red"> * </span></label>
                <input type="date" class="form-control" name="create_timestamp" id="date_add1234" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                <div id="error_end"></div>
            </div>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('detail');?> <span class="red"> * </span> </label>
                <textarea name="circular" id="circular" rows="5" placeholder="<?php echo get_phrase('add_circular');?>" class="form-control" maxlength="1000" oninput="count_value('circular','area_count','1000')" required></textarea>
                <div id="area_count" class="col-sm-12 "></div>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('is_active');?><span class="red"> * </span></label>
            	<select name="is_active" class="form-control">
            		<option value="1"><?php echo get_phrase('yes');?></option>
            		<option value="0"><?php echo get_phrase('no');?></option>
            	</select>
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('attachment');?>
                </label>
                <input value="" type="file" class="form-control" name="image1" id="image1" onchange="file_validate('image1','doc','img_g_msg')">
                <span style="color: green;">
                <?php echo get_phrase('allowed_file_size');?>
                :  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                <br />
                <span style="color: red;" id="img_g_msg"></span>
            </div>
            <div class="form-group">
                <div <?= check_sms_preference(10,"style","sms") ?>>
                    <label class="control-label">
                    <?php echo get_phrase('send_sms');?><span class="red"> * </span></label>
                    <input  id="send_message" class=""  type="checkbox" name="send_message" />
                    <div id="error_date1"></div>
                </div>    
            </div>
            <div class="form-group">
                <div <?= check_sms_preference(10,"style","email") ?>>
                    <label class="control-label">
                    <?php echo get_phrase('send_email');?><span class="red"> * </span></label>
                    <input  id="" class=""  type="checkbox" name="send_email" />
                    <div id="error_date1"></div>
                </div>
            <div class="form-group">
                <div class="float-right">
        			<button type="submit" class="modal_save_btn" id="btn_add">
        				<?php echo get_phrase('add_circular');?>
        			</button>
        			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        				<?php echo get_phrase('cancel');?>
        			</button>
        		</div>
            </div>
        </form>
    </div>    
</div>
<script>
$(document).ready(function() {
    ////$('.selectpicker').selectpicker();


    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);
    });
    $("#class_id").html('<select><option><?php echo get_phrase('select_class'); ?></option></select>');
    $('#acad_year1').change(function() {

        var acad_year = $(this).val();
        get_year_term1(acad_year);

        function get_year_term1() {
            $('#acad_year1').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            var acad_year = $('#acad_year1').val();



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
                        $('#yearly_terms1').html(response);
                    }
                    if ($.trim(response) == "") {
                        $('#yearly_terms1').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
                    }

                }
            });




        }
    });

    $("#departments_id").change(function() {
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
                $("#class_id").html(response);
                $("#section_id").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');
            }
        });
    });

    $("#class_id").change(function() {
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
                $("#section_id").html(response);
            }
        });



    });

    $("#section_id").change(function() {
        // $('#get_planner').html('');
        var section_id = $(this).val();
        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id
            },
            url: "<?php echo base_url();?>circular/get_section_student",
            dataType: "html",
            success: function(response) {
                $("#icon").remove();
                $("#student_id").html(response);
            }
        });
    });

    $('#date_add').on('change', function() {
        $('#btn_add').removeAttr('disabled', 'true');
        $('#error_end').text('');
        var date1 = $(this).val();
        var term_id = $('#yearly_terms1').val();
        if (date1 != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>circular/term_date_range",

                data: ({
                    date1: date1,
                    term_id: term_id
                }),
                dataType: "html",
                success: function(html) {
                    if (html == 0) {
                        $('#error_end').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');
                        $('#btn_add').attr('disabled', 'true');
                    }
                }
            });
        }

    });

    $("#yearly_terms1").change(function() {

        var date1 = $("#date_add").val();

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
                        $('#error_end').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');
                        $('#btn_add').attr('disabled', 'true');
                    } else {
                        $('#btn_add').removeAttr('disabled');
                        $('#error_end').text('');
                    }
                }
            });
        }
    });

    $("#acad_year1").change(function() {
        $("#date_add").val('');
    });
});
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>