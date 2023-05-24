<div class="panel panel-light" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title black2">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('add_planner_task'); ?>
        </div>
    </div>    
    <div class="panel-body">
        <?php echo form_open(base_url() . 'academic_planner/save_planner', array('class' => 'form-horizontal form-groups-bordered validate', 'id' => 'disable_submit_btn', 'enctype' => 'multipart/form-data')); ?>
            <div class="form-group" id="class4">
                <label class="control-label"><?php echo get_phrase('subject'); ?><span class="red"> * </span></label>
                <label id="subject_id_add_selection"></label>
                <select id="subject_id_add" name="subject_id_add" class="form-control selectpicker" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
                    <?php echo subject_list(); ?>
                </select>
            </div>
            <div class="form-group">
                <label for="field-1" class="control-label"><?php echo get_phrase('title'); ?><span class="red"> * </span></label>
                <input class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>" maxlength="1000">
            </div>
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('detail'); ?></label>
                <textarea class="form-control" name="detail" id="detail" value="" rows="3" maxlength="3000" oninput="count_value('detail','area_count','3000')"></textarea>
                <div id="area_count" class="col-sm-12 "></div>
            </div>
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('objective'); ?></label>
                <textarea class="form-control" name="objective" id="objective" value="" rows="3" maxlength="3000" oninput="count_value('objective','area_count1','3000')"></textarea>
                <div id="area_count1" class="col-sm-12 "></div>
            </div>
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('assessment'); ?></label>
                <textarea class="form-control" name="assesment" id="assesment" value="" rows="3" maxlength="3000" oninput="count_value('assesment','area_count2','3000')"></textarea>
                <div id="area_count2" class="col-sm-12 "></div>
            </div>
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('requirements'); ?></label>
                <textarea class="form-control" name="requirements" id="requirements" value="" rows="3" maxlength="3000" oninput="count_value('requirements','area_count3','3000')"></textarea>
                <div id="area_count3" class="col-sm-12 "></div>
            </div>
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('required time'); ?></label>
                <input type="number" class="form-control" name="required_time" value="" maxlength="3" min="0">(<?php echo get_phrase('minutes'); ?>)
            </div>
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('date'); ?><span class="red"> * </span></label>
                <input id="datepic" type="date" class="form-control" name="date" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>" data-format="dd/mm/yyyy">
                <div id="error_end"></div>
            </div>
            <div class="form-group">
                <label for="field-1" class="control-label"><?php echo get_phrase('attachment'); ?></label>
                <input type="file" class="form-control" name="userfile" id="userfile" onchange="file_validate('userfile','doc','img_f_msg')" value="">
                <span style="color: green;"><?php echo get_phrase('allowed_file_size'); ?>
                    :  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types'); ?>
                    : png, jpeg, jpg, pdf, doc, docx</span>
                <br/>
                <span style="color: red;" id="img_f_msg"></span>
            </div>
            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('Is_active'); ?></label>
                <select name="is_active" id="is_active" class="form-control">
                	<option value="1">Yes</option>
                	<option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <div class="float-right">
					<button type="submit" class="modal_save_btn" id="btn_add">
						<?php echo get_phrase('save');?>
					</button>
					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
						<?php echo get_phrase('cancel');?>
					</button>
				</div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>    


<script>
    $(document).ready(function ()
    {
        //$('.selectpicker').selectpicker();
        $('.selectpicker').on('change', function () {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });  
    });
</script>