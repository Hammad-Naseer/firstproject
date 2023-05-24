
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('leave_request'); ?>
        </h3>
    </div>
    
</div>

<div class="col-md-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export"  data-step="2" data-position='top' data-intro="all leaves records">
        <thead>
            <tr>
                <th style="width: 34px;!important;">
                    <div> <?php echo get_phrase('s_no');?></div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('leave_request_details');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1;foreach($leaves as $row):?>
            <tr>
                <td class="td_middle">
                    <?php echo $count++;?>
                </td>
                <td>
                    <div class="reason mytt">
                        <?php echo $row['reason']; ?>
                        <?php if($row['proof_doc']!=""){ ?> <a href=" <?php echo display_link($row['proof_doc'],'leaves_student'); ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                        <?php }else{ 
                                //echo "No File Found"; 
                            } ?>
                    </div>
                    <div>
                        <strong><?php echo get_phrase('category');?>:</strong>
                        <?php  $leaves_cat = $this->Crud_model->get_type_name_by_id('leave_category',$row['leave_category_id']); echo $leaves_cat->name; ?>
                    </div>
                    <div class="dd"> <strong><?php echo get_phrase('from');?></strong>
                        <?php echo convert_date($row['start_date']); ?> <strong><?php echo get_phrase('to');?></strong>
                        <?php echo convert_date($row['end_date']); ?>
                    </div>
                    <?php if($row['approved_upto_date'] != "" && $row['final_end_date'] != ""): ?>
                        <div>
                            <strong class="text-danger">
                                <?php echo get_phrase('actual_start_date');?> / <?php echo get_phrase('actual_end_date');?>:
                            </strong> 
                        <?php 
                            $start_date= $row['approved_upto_date'];
						    $d=explode("-",$start_date);
						    echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
						 ?> 
						  /  
						 <?php 
                            $end_date=$row['final_end_date'];
                            $d=explode("-",$end_date);
						    echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                         ?>
                        </div>
                    <?php endif; ?>
                    <div class="re_date">
                        <strong><?php echo get_phrase('request_date');?> :</strong>
                        <?php echo convert_date($row['request_date']); ?>
                    </div>
                    <div class="app_date">
                        <strong><?php echo get_phrase('approval_date');?>:</strong>
                        <?php 
                   if($row['process_date'] != '0000-00-00')
                        { echo convert_date($row['process_date']); }?>
                    </div>
                    <div class="status">
                        <strong><?php echo get_phrase('status');?>: </strong>
                        <?php
                        if($row['status']==0){
                            echo '<span class="orange">'.get_phrase("pending").'</span>';
                        } 
                        if($row['status']==1){
                            echo '<span  class="green">'.get_phrase("approved").'</span>';
                        }
                        if($row['status']==2){
                            echo '<span class="red">'.get_phrase("rejected").'</span>';
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>
function check_leave(id) {
    $('#' + id + '_error').remove();

    var date = $('#' + id).val();
    $('#' + id + '_message').append('<span id="' + id + 'loader" class="glyphicon glyphicon-repeat fast-right-spinner"><span>');
    $('#submit_leave').prop('disabled', true);
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url();?>parents/check_leave_dates",
        data: {
            date: date
        },
        success: function(result) {
            $('#' + id + 'loader').remove();
            if (result == 'failure') {
                $('#' + id + '_message').append('<p id="' + id + '_error" style="color:red">You have already applied leave for this date.</p>');
            } else {
                $('#submit_leave').prop('disabled', false);
                $('#' + id + '_error').remove();
            }
        }
    });
}
</script>

<script>
$(function() { /* to make sure the script runs after page load */

    $('.item').each(function(event) { /* select all divs with the item class */

        var max_length = 50; /* set the max content length before a read more link will be added */

        if ($(this).html().length > max_length) { /* check for content length */

            var short_content = $(this).html().substr(0, max_length); /* split the content in two parts */
            var long_content = $(this).html().substr(max_length);

            $(this).html(short_content +
                '<a href="#" class="read_more mycolor"><br/>Read More</a>' +
                '<span class="more_text" style="display:none;">' + long_content + '</span>'); /* Alter the html to allow the read more functionality */

            $(this).find('a.read_more').click(function(event) { /* find the a.read_more element within the new html and bind the following code to it */

                event.preventDefault(); /* prevent the a from changing the url */
                $(this).hide(); /* hide the read more button */
                $(this).parents('.item').find('.more_text').show(); /* show the .more_text span */

            });

        }

    });


});

    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/add_leave_modal/")';
    var datatable_btn     = "<a href='javascript:;' data-toggle='modal' onclick="+datatable_btn_url+" data-step='1' href='#' data-position='left' data-intro='Press this button to request for leave' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('request_a_leave');?></a>";    


</script>








<!-- Modal -->
<div id="myModal" class="modal fade in" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo get_phrase('request_a_leave');?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="box-content" style="padding:15px">
                    <?php echo form_open(base_url().'parents/manage_leaves/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('leave_type');?>
                        </label>
                            <select name="leave_id" class="form-control" style="width:100%;" required>
                                <option value=""><?php echo get_phrase('please_select_leave_type');?></option>
                                <?php 
                                        $this->db->where('school_id',$_SESSION['school_id']);
                                        $book = $this->db->get(get_school_db().'.leave_category')->result_array();
                                        foreach($book as $row):
                                        ?>
                                <option value="<?php echo $row['leave_category_id'];?>">
                                    <?php echo $row['name'];?>
                                </option>
                                <?php
                                        endforeach;
                                        ?>
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('start_date');?>
                        </label>
                        <div id="start_date_message">
                            <input type="date" class="form-control" name="start_date" id="start_date" onselect="check_leave('start_date')" data-start-view="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('end_date');?>
                        </label>
                        <div id="end_date_message">
                            <input type="date" class="form-control" name="end_date" id="end_date" onselect="check_leave('end_date')" data-start-view="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('description');?>
                        </label>
                            <textarea class="form-control" name="reason" id="reason" rows="5" maxlength="1000" oninput="count_value('reason','area_count1','1000')" required ></textarea>
                            <div id="area_count1" class="col-sm-12" ></div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="control-label">
                            <?php echo get_phrase('file_upload');?>
                        </label>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new"><?php echo get_phrase('choose_file'); ?></span>
                                    <span class="fileinput-exists"><?php echo get_phrase('change'); ?></span>
                                    <input type="file" name="userfile" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove'); ?></a>
                                </div>
                            </div>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn" id="submit_leave">
        						<?php echo get_phrase('request_leave');?>
        					</button>
        					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        						<?php echo get_phrase('cancel');?>
        					</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
