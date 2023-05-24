<?php
    if($this->session->flashdata('club_updated')){
        echo '
        <div align="center">
            <div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              '.$this->session->flashdata('club_updated').'
            </div> 
        </div>';
    }
?>
    <script>
        $(window).on("load",function() {
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        });
    </script>

    <div class="row ">
       <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
           <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline capitalize"> 
                <?php echo get_phrase('academic_acknowledge_report'); ?>
            </h3>
        </div>
    </div>
    
    <div>
        <form id="filter" name="filter" method="post" action="<?php echo base_url();?>activitylog/filter_academic_acknowledge_report" class="form-horizontal form-groups-bordered validate " style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label for="teacher_id_filter">Select Teacher <span class="text-danger">*</span></label>
                    <select id="teacher_id_filter"  class="form-control" name="teacher_id" onchange="get_subjects()" required="required">
                        <?php echo teacher_option_list($teacher_id);?>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label id="subject_id_filter_selection">Select Subject<span class="text-danger">*</span></label>
                    <select name="subject_id" id="subject_id" class="form-control" required="required">
                        
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control start" value="<?php echo isset($start_date) && $start_date != '1970-01-01'  ? $start_date : ''; ?>">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control end" value="<?php echo isset($end_date) && $end_date != '1970-01-01' ? $end_date : ''; ?>">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <input type="hidden" name="apply_filter" value="1">
                    <input type="submit" value="<?php echo get_phrase('filter'); ?>" class="btn btn-primary">
                    <?php
                    if ($apply_filter == 1)
                    {
                    ?>
                    <a href="<?php echo base_url(); ?>activitylog/academic_acknowledge_report" class="modal_cancel_btn" id="btn_remove">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
                    </a>
                    <?php } ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-4" data-step="3" data-position="top">
                      <h3 class="text-danger text-center" id="teacherErrorMessage"></h3>
                </div>    
            </div>
        </form>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
<?php //if(isset($result) && count($result) > 0) { ?>
    <table class="table table-bordered table_export" style="width: 100% !important;">
            <thead>
                <td><?php echo get_phrase('#');?></td>
                <td><?php echo get_phrase('planner_title');?></td>
                <td><?php echo get_phrase('date');?></td>
                <td><?php echo get_phrase('planner_status');?></td>
                <td><?php echo get_phrase('assigned_date');?></td>
            </thead>
    		<tbody>
    		    <?php $i = 1; foreach($result as $data): ?>
        		    <tr>
            		    <td class="td_middle"><?= $i++; ?></td>
            		    <td><?= $data['title']; ?></td>
            		    <td><?= date_view($data['start']); ?></td>
            		    <td>
            		        <?php 
            		            if(!empty($data['status']))
            		            {
            		               echo $data['status']; 
            		            }else{
            		                echo "Not Assigned";
            		            }
            		        ?>
            		    </td>
            		    <td><?php echo !empty($data['inserted_at']) ? date_view($data['inserted_at']) : 'N/A'; ?></td>
        		    </tr>
    		    <?php endforeach; ?>
    		</tbody>
        </table>
<?php //} ?>        
</div>

<script>
    function get_subjects()
    {
        var teach_id = $("#teacher_id_filter").val();
        if(teach_id !="")
        {
            $('#teacher_id_filter').after('<div id="loader" class="loader_small"></div>');
            $.ajax({
                type: 'POST',
                data: {teach_id:teach_id},
                url: "<?php echo base_url();?>activitylog/get_subjects_for_teacher",
                dataType: "html",
                success: function(response)
                {
                     $("#subject_id").html(response);
                     $('#loader').remove();
                }
            });
        }else{
            $("#section_id").html("<option value=''><?php echo get_phrase('select'); ?></option>");
        }
    }
    // $('#btn_filter').click(function(){
    //      var teacher_id  = $('#teacher_id_filter').val();
    //      var activity_id = $('#activity_id').val();

    //      if(teacher_id != '' && activity_id != ''){

    //             if(parseInt(activity_id) == 1)
    //             {
                   
    //                 $.ajax({
    //                     type: 'POST',
    //                     data: { teacher_id: teacher_id },
    //                     url:  "<?php echo base_url();?>activitylog/check_if_class_teacher",
    //                     dataType: "html",
    //                     success: function(data) {
    //                         if(parseInt(data) > 0) {
    //                             $('#teacherErrorMessage').html(''); 
    //                             $('#btn_submit').click();
    //                         }
    //                         else
    //                         {
    //                             $('#teacherErrorMessage').html('This Teacher Is Not A Class Teacher');
    //                         }
    //                     }
    //                 });
                   
    //             }
    //             else
    //             {
    //               $('#btn_submit').click();
    //             }
               
    //      }
    
    // });
</script>

<?php if($this->uri->segment(2) == "filter_academic_acknowledge_report"){ ?>
<script>

    $(document).ready(function(){
        get_subjects();
    });
    
    setTimeout(function(){    
        $("#subject_id").val(<?php echo $subject_id; ?>);    
    }, 2000);
    
</script>
<?php } ?>