<style>
.orange {
    color: #ea960b;
    font-weight: bold;
}

.blue {
    color: #2e0de8;
    font-weight: bold;
}

.green {
    color: #208e1a !important;
    font-weight: bold;
}

.red {
    color: #fb090f;
    font-weight: bold;
}

.fa-download {
    color: #4a8cbb;
    font-size: 16px;
    padding-left: 4px;
}

.mytt {
    font-size: 15px;
    color: #0A73B7;
    font-weight: bold;
}

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('leave_request');?>  
        </h3>
        <!--                   
                    
                      <a href="javascript:;" id="add-link" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/pagename');" 
                class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('request');?>
                </a> 
                    
                    
                    -->
    </div>
    <div class="col-md-12">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable table-responsive" id="table_export">
            <thead>
                <tr>
                    <th style="       width: 34px;!important;">
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
                    <td>
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
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo get_phrase('request_a_leave');?></h4>
            </div>
            <div class="modal-body">
                <div class="box-content">
                    <?php echo form_open(base_url().'parents/manage_leaves/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            <?php echo get_phrase('leave_type');?>
                        </label>
                        <div class="col-sm-5">
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
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label">
                            <?php echo get_phrase('start_date');?>
                        </label>
                        <div class="col-sm-5" id="start_date_message">
                            <input type="text" class="form-control datepicker" name="start_date" id="start_date" data-format="dd/mm/yyyy" onselect="check_leave('start_date')" data-start-view="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label">
                            <?php echo get_phrase('end_date');?>
                        </label>
                        <div class="col-sm-5" id="end_date_message">
                            <input type="text" class="form-control datepicker" name="end_date" id="end_date" data-format="dd/mm/yyyy" onselect="check_leave('end_date')" data-start-view="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label">
                            <?php echo get_phrase('description');?>
                        </label>
                        <div class="col-sm-7">
                            <textarea class="form-control" name="reason" id="reason" rows="5" maxlength="1000" oninput="count_value('reason','area_count1','1000')" required ></textarea>
                            <div id="area_count1" class="col-sm-12" ></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label">
                            <?php echo get_phrase('file_upload');?>
                        </label>
                        <div class="col-sm-5">
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
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit_leave" class="btn btn-info">
                                <?php echo get_phrase('request_leave');?>
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
</script>
