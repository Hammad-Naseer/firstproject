<?php
if($this->session->flashdata('club_updated')){
   echo '<div align="center">
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
                <?php echo get_phrase('activity_logs_report'); ?>
            </h3>
        </div>
    </div>
    
    <div>
        <form id="filter" name="filter" method="post" action="<?php echo base_url();?>activitylog/filter" class="form-horizontal form-groups-bordered validate " style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label for="teacher_id_filter">Select Teacher <span class="text-danger">*</span>
                    <span class="text-danger text-center" id="t_error"></span></label>
                    <select id="teacher_id_filter"  class="form-control" name="teacher_id" required="required">
                        <?php echo teacher_option_list($teacher_id);?>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label id="section_id_filter_selection">Select Activity <span class="text-danger">*</span>
                    <span class="text-danger text-center" id="a_error"></span></label>
                    <select name="activity_id" id="activity_id" class="form-control" required="required">
                        <?php echo activities_option_list($activity_id); ?>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label>Start Date</label>
                    <input type="date" name="start_date" id="start_date" autocomplete="off" data-format="dd/mm/yyyy" class="form-control start" value="<?php echo isset($start_date) && $start_date != '1970-01-01'  ? $start_date : ''; ?>">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label>End Date</label>
                    <input type="date" name="end_date" id="end_date" autocomplete="off" data-format="dd/mm/yyyy" class="form-control end" value="<?php echo isset($end_date) && $end_date != '1970-01-01' ? $end_date : ''; ?>">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <input type="hidden" name="apply_filter" value="1">
                    <input type="button" value="<?php echo get_phrase('filter'); ?>" class="modal_save_btn" id="btn_filter">
                    <!--<input type="submit" value="<?php echo get_phrase('filter'); ?>" class="btn btn-primary" style="display:none" id="btn_submit">-->
                    <button type="submit" class="btn btn-primary" style="display:none" id="btn_submit">filter</button>
                    <input type="hidden" id="section_name" name="section_name" value="<?php echo isset($section_name) ? $section_name : '';  ?>" />
                    <?php
                    if ($apply_filter == 1)
                    {
                    ?>
                    <a href="<?php echo base_url(); ?>activitylog/filter" class="modal_cancel_btn" id="btn_remove">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
<div class="col-lg-12 col-md-12 col-sm-12">
    <div data-step="3" data-position="top">
          <h3 class="text-danger text-center" id="teacherErrorMessage"></h3>
    </div>
<?php 
    if(isset($result) && count($result) > 0) {
        /*
            "1" => 'attendance' ,
            "2" => 'diary',
            "3" => 'leave_request',
            "4" => 'online_assessment',
            "5" => 'lecture_notes_sharing',
            "6" => 'virtual_class'
	    */
?>
    <?php if($activity_id == 1) { ?>
        <table class="table table-bordered table_export" style="width: 100% !important;">
            <thead>
                <td><?php echo get_phrase('#');?></td>
                <td><?php echo get_phrase('date');?></td>
                <td><?php echo get_phrase('Attendance Status');?></td>
            </thead>
    		<tbody>
    		    <?php $i = 1; foreach($result as $data): ?>
        		    <tr>
            		    <td><?= $i++; ?></td>
            		    <td><?= date_view($data['date']); ?></td>
            		    <td><?= $data['total'] > 0 ? "Marked" : "Not Marked" ?></td>
        		    </tr>
    		    <?php endforeach; ?>
    		</tbody>
        </table>
        
    <?php }else if($activity_id == 2) { ?>
    
        <table class="table table-bordered table_export" style="width: 100% !important;">
            <thead>
                <td><?php echo get_phrase('#');?></td>
                <td><?php echo get_phrase('date');?></td>
                <td><?php echo get_phrase('title');?></td>
                <td><?php echo get_phrase('assigned_to');?></td>
            </thead>
    		<tbody>
    		    <?php $i = 1; foreach($result as $data): ?>
        		    <tr>
            		    <td><?= $i++; ?></td>
            		    <td><?= date_view($data['assign_date']); ?></td>
            		    <td><?= $data['diary_title'] ?></td>
            		    <td><?= $data['department'] ?> / <?= $data['class'] ?> / <?= $data['section'] ?></td>
        		    </tr>
    		    <?php endforeach; ?>
    		</tbody>
        </table>
        
    <?php }else if($activity_id == 3) { ?>
        
        <table class="table table-bordered table_export" style="width: 100% !important;">
            <thead>
                <td><?php echo get_phrase('#');?></td>
                <td><?php echo get_phrase('request_date');?></td>
                <td><?php echo get_phrase('leave_from');?></td>
                <td><?php echo get_phrase('leave_till');?></td>
                <td><?php echo get_phrase('reason');?></td>
            </thead>
    		<tbody>
    		    <?php $i = 1; foreach($result as $data): ?>
        		    <tr>
            		    <td><?= $i++; ?></td>
            		    <td><?= date_view($data['request_date']); ?></td>
            		    <td><?= date_view($data['start_date']) ?></td>
            		    <td><?= date_view($data['end_date']) ?></td>
            		    <td><?= $data['reason'] ?></td>
        		    </tr>
    		    <?php endforeach; ?>
    		</tbody>
        </table>
    
    <?php }else if($activity_id == 4) { ?>
       
        <table class="table table-bordered table_export" style="width: 100% !important;">
            <thead>
                <td><?php echo get_phrase('#');?></td>
                <td><?php echo get_phrase('date');?></td>
                <td><?php echo get_phrase('title');?></td>
                <td><?php echo get_phrase('is_assigned');?></td>
            </thead>
    		<tbody>
    		    <?php $i = 1; foreach($result as $data): ?>
        		    <tr>
            		    <td><?= $i++; ?></td>
            		    <td><?= date_view($data['inserted_at']); ?></td>
            		    <td>
                		   <?= $data['assessment_title'] ?>
            		    </td>
            		     <td>
            		        <?php if($data['is_assigned'] == 1){ ?>
            		            <?php echo get_assessment_time($data['assessment_id']) ?>
            		        <?php }else{ ?>
                		        Not Assigned
            		        <?php } ?>
            		    </td>
        		    </tr>
    		    <?php endforeach; ?>
    		</tbody>
        </table>
    
    <?php }else if($activity_id == 5) { ?>
        
        <table class="table table-bordered table_export" style="width: 100% !important;">
            <thead>
                <td><?php echo get_phrase('#');?></td>
                <td><?php echo get_phrase('date');?></td>
                <td><?php echo get_phrase('title');?></td>
                <td><?php echo get_phrase('is_assigned');?></td>
            </thead>
    		<tbody>
    		    <?php $i = 1; foreach($result as $data): ?>
        		    <tr>
            		    <td><?= $i++; ?></td>
            		    <td><?= date_view($data['inserted_at']); ?></td>
            		    <td>
            		        <?= $data['notes_title'] ?>
            		    </td>
            		    <td>
            		        <?php if($data['is_assigned'] == 1){ ?>
            		            <?php echo get_notes_assigned_section($data['notes_id']) ?>
            		        <?php }else{ ?>
                		        Not Assigned
            		        <?php } ?>
            		    </td>
        		    </tr>
    		    <?php endforeach; ?>
    		</tbody>
        </table>
   
    <?php }else if($activity_id == 6) { ?>
    
        <table class="table table-bordered table_export" style="width: 100% !important;">
            <thead>
                <td><?php echo get_phrase('#');?></td>
                <td><?php echo get_phrase('date');?></td>
                <td><?php echo get_phrase('class-section');?></td>
                <td><?php echo get_phrase('virtual_class_name');?></td>
            </thead>
    		<tbody>
    		    <?php $i = 1; foreach($result as $data): ?>
        		    <tr>
            		    <td><?= $i++; ?></td>
            		    <td><?= date_view($data['inserted_at']); ?></td>
            		    <td><?= $data['department'] ?> / <?= $data['class'] ?> / <?= $data['section'] ?></td>
            		    <td><?= $data['virtual_class_name'] ?></td>
        		    </tr>
    		    <?php endforeach; ?>
    		</tbody>
        </table>
    <?php } ?>
<?php }else if(isset($result) && count($result) == 0) { ?>

    <table class="table table-bordered table_export" style="width: 100% !important;">
		<thead>
		    <th></th>
		</thead>
		<tbody align="center">
		    <tr>
    		    <td>Data Not Found</td>
		    </tr>
		</tbody>
    </table>
    
<?php } ?>
</div>

<script>
    
    $('#btn_filter').click(function(){
         $('#t_error').text('');
         $('#a_error').text('');
         var teacher_id  = $('#teacher_id_filter').val();
         var activity_id = $('#activity_id').val();
         
         if(teacher_id == ''){
             $('#t_error').text('values required');
         }
         if(activity_id == ''){
             $('#a_error').text('values required');
         }
         if(teacher_id != '' && activity_id != ''){

                if(parseInt(activity_id) == 1)
                {
                   
                    $.ajax({
                        type: 'POST',
                        data: { teacher_id: teacher_id },
                        url:  "<?php echo base_url();?>activitylog/check_if_class_teacher",
                        dataType: "html",
                        success: function(data) {
                            if(parseInt(data) > 0) {
                                $('#teacherErrorMessage').html(''); 
                                $('#btn_submit').click();
                            }
                            else
                            {
                                $('#teacherErrorMessage').html('This Teacher Is Not A Class Teacher');
                            }
                        }
                    });
                   
                }
                else
                {
                   $('#btn_submit').click();
                }
               
         }
    
    });
    
</script>
<!--//***********************Date filter validation***********************-->
<script>
    $("#start_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("start_date").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#end_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("end_date").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->