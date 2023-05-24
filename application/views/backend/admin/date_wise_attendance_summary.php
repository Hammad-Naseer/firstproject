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
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline capitalize"> 
                <?php echo get_phrase('date_wise_attendance_summary'); ?>
            </h3>
        </div>
    </div>
    
    <div>
        <form id="filter" name="filter" method="post" action="<?php echo base_url();?>attendance/get_date_wise_attendance_summary" class="form-horizontal form-groups-bordered validate " style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer">
                <div class="col-lg-4 col-md-4 col-sm-4" data-step="1" data-position="top" data-intro="Step 1: select class - section">
                    <label id="section_id_filter_selection">Section</label>
                    <select id="section_id_filter"  class="selectpicker form-control" name="section_id" required="required">
                        <?php echo section_selector($section_id);?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4" data-step="2" data-position="top" data-intro="Step 2: select start date">
                    <label>Start Date</label>
                    <input type="date" id="start_date" data-format="dd/mm/yyyy" name="start" class="form-control start" value="<?php echo isset($start_date) && $start_date != '1970-01-01'  ? $start_date : ''; ?>">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4" data-step="3" data-position="top" data-intro="Step 3: select end date">
                    <label>End Date</label>
                    <input type="date" id="end_date" data-format="dd/mm/yyyy" name="end" class="form-control end" value="<?php echo isset($end_date) && $end_date != '1970-01-01' ? $end_date : ''; ?>">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3" data-step="4" data-position="top" data-intro="press filter button to get student attendance summary report">
                    <input type="hidden" name="apply_filter" value="1">
                    <input type="submit" value="<?php echo get_phrase('filter'); ?>" class="modal_save_btn" id="btn_submit">
                    <input type="hidden" id="section_name" name="section_name" value="<?php echo isset($section_name) ? $section_name : '';  ?>" />
                    <?php
                    if ($apply_filter == 1)
                    {
                    ?>
                    <a href="<?php echo base_url(); ?>attendance/date_wise_attendance_summary" class="modal_cancel_btn" id="btn_remove">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
                    </a>
                    <input class="modal_save_btn" type="submit" id="weekly_attendance_pdf" value="<?php echo get_phrase('get_pdf');?>">
                    <input class="modal_save_btn" type="submit" id="weekly_attendance_excel" value="<?php echo get_phrase('get_excel');?>">
                    <?php } ?>
                </div>
            </div>
        </form>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
<?php if(count($result) > 0) { ?>
    <table class="table table-bordered table_export" style="width: 100% !important;">
        <thead>
            <td class="td_middle"><?php echo get_phrase('#');?></td>
            <td><?php echo get_phrase('student');?></td>
            <td><?php echo get_phrase('present');?></td>
            <td><?php echo get_phrase('absent');?></td>
            <td><?php echo get_phrase('leave');?></td>    
        </thead>
		<tbody>
		    <?php $i = 1; foreach($result as $data): ?>
		    <tr>
    		    <td class="td_middle" ><?= $i++; ?></td>
    		    <td><?= $data['name']; ?></td>
    		    <td><?= $data['total_present']; ?></td>
    		    <td><?= $data['total_absent']; ?></td>
    		    <td><?= $data['total_leaves']; ?></td>
		    </tr>
		    <?php endforeach; ?>
		</tbody>
    </table>
<?php } ?>
</div>

<script>
    $('#weekly_attendance_pdf').click(function(){
        $('#filter').attr('action', '<?php echo base_url(); ?>attendance/weekly_attendance_pdf');
        $('#filter').submit();
        setTimeout(function(){ location.reload(); }, 5000);
    });

    $('#weekly_attendance_excel').click(function(){
        $('#filter').attr('action', '<?php echo base_url(); ?>attendance/weekly_attendance_excel');
        $('#filter').submit();
        setTimeout(function(){ location.reload(); }, 5000);
    });
    
    
    $('#section_id_filter').change(function(){
       var text = $("#section_id_filter option:selected").text();    
       $('#section_name').val(text); 
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