<?php 
    $q="select * FROM ".get_school_db().".noticeboard
    WHERE notice_id= ".str_decode($param2)." and school_id=".$_SESSION['school_id']."";
    $edit_data=$this->db->query($q)->result_array();
?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title black2">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_notice');?>
        </div>
    </div>    
    <div class="panel-body">
    <?php foreach($edit_data as $row):?>
        <?php echo form_open(base_url().'noticeboards/noticeboard/do_update/'.$param2 , array('id'=>'noticeboards_edit','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('title');?><span class="red"> * </span></label>
                <input type="text" class="form-control" name="notice_title" value="<?php echo $row['notice_title'];?>" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
            </div>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('Detail');?><span class="red"> * </span></label>
                <textarea name="notice" id="notice" rows="5" maxlength="1000" class="form-control" oninput="count_value('notice','notice_count','1000')" placeholder="<?php echo get_phrase('add_notice');?>"><?php echo $row['notice'];?></textarea>
                <div id="notice_count" class="col-sm-12 " style="padding:0px; font-size:10px;"></div>
            </div>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('Type');?><span class="red"> * </span></label>
                <select id="type_edit" name="type_edit" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <option value=""><?php echo get_phrase('select_type');?></option>
                    <?php echo noticeboard_type($row['type']);?>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('is_active');?><span class="red"> * </span></label>
				<select name="is_active_edit" class="form-control">
		        	<option value="1" <?php if($row['is_active']==1){ echo "selected";} ?> ><?php echo get_phrase('yes');?></option>
		        	<option value="0" <?php if($row['is_active']==0){ echo "selected";} ?> ><?php echo get_phrase('no');?></option>
		        </select>
            </div>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('date');?><span class="red"> * </span></label>
                <input type="date" class="form-control" name="create_timestamp" id="date_info123" value="<?php echo $row['create_timestamp']; ?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                <div id="error_date1"></div>
            </div>
            <div class="form-group">
                <label class="control-label">
                <?php echo get_phrase('send_sms');?><span class="red"> * </span></label>
                <input  id="send_message" class=""  type="checkbox" name="send_message" />
                <div id="error_date1"></div>
            </div>
            <span style="color:red;">
	            <?php if($row['sms_status']){ echo "<strong>Note : </strong> Sms is already Send"; } ?>
            </span>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('send_email');?><span class="red"> * </span></label>
                    <input  id="" class=""  type="checkbox" name="send_email" />
                    <div id="error_date1"></div>
            </div>
            <div class="form-group">
                <div class="float-right">
					<button type="submit" class="modal_save_btn" id="btn_submit1">
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
<script type="text/javascript">
$(document).ready(function() {
    $('#is_active_edit').change(function() {
        this.value = this.checked ? 1 : 0;
    });
    //$('.selectpicker').selectpicker();
    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);
    });
    $('#acad_year1').change(function() {
        var acad_year = $(this).val();
        get_year_term(acad_year);
    });

    function get_year_term() {
        $('#acad_year').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
        var yearly_terms = "<?php echo $_POST['yearly_terms1'] ?>";
        var acad_year = $('#acad_year1').val();



        $.ajax({
            type: 'POST',
            data: {
                acad_year: acad_year,
                yearly_terms: yearly_terms
            },
            url: "<?php echo base_url();?>noticeboards/get_year_term2",
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


    $('#date_info').on('change', function() {
        $('#date_info').removeAttr('disabled', 'true');
        $('#error_date').text('');
        var date1 = $(this).val();
        var term_id = $('#yearly_terms1').val();
        if (date1 != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>noticeboards/term_date_range",

                data: ({
                    date1: date1,
                    term_id: term_id
                }),
                dataType: "html",
                success: function(html) {
                    //alert(html);
                    if (html == 0) {
                        $('#error_date1').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');
                        $('#btn_submit1').attr('disabled', 'true');
                    } else {
                        $('#error_date1').text('');
                        $('#btn_submit1').removeAttr('disabled');
                    }
                }
            });
        }

    });

    $("#yearly_terms1").change(function() {
        var date1 = $("#date_info").val();
        var term_id = $(this).val();
        if (date1 != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>noticeboards/term_date_range",

                data: ({
                    date1: date1,
                    term_id: term_id
                }),
                dataType: "html",
                success: function(html) {
                    //alert(html);
                    if (html == 0) {
                        $('#error_date1').text('<?php echo get_phrase('date_should_be_between_term_dates');?>');
                        $('#btn_submit1').attr('disabled', 'true');
                    } else {
                        $('#error_date1').text('');
                        $('#btn_submit1').removeAttr('disabled');
                    }
                }
            });
        }

    });

    $("#acad_year1").change(function() {
        $("#date_info").val('');
    });
});
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>