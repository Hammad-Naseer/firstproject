
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('student_withdrawl_summary'); ?>
        </h3>
    </div>
</div>
<form action="<?php echo base_url().'reports/student_withdrawl_summary/'?>" method="post" name="student_withdrawl_summary" id="student_withdrawl_summary" data-step="1" data-position="top" data-intro="use this filter to get specific Fee Summary records">
    <div class="row filterContainer"> 
        <div class="col-md-6 col-lg-6 col-sm-6">
            <label><?php echo get_phrase('start_date');?> </label>
            <input class="form-control datepicker" type="text" autocomplete="off"  name="start_date" required="" id="start_date" value="<?php echo $start_date ?>" placeholder="Start Date" style="background-color:#FFF !important;" data-format="dd/mm/yyyy">
            <div id="d3"></div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
            <label><?php echo get_phrase('end_date');?></label>
            <input class="form-control datepicker" type="text" autocomplete="off"  name="end_date" required="" id="end_date" value="<?php echo $end_date ?>" placeholder="End Date" style="background-color:#FFF !important;"  data-format="dd/mm/yyyy">
            <div id="d3"></div>
        </div> 
        <div class="col-md-12 col-lg-12 col-sm-12 pt-3"> 
            <input type="hidden" name="apply_filter" value="1">
            <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="modal_save_btn"> 
            <?php if ($apply_filter == 1) { ?>
                <a id="btn_show" href="<?php echo base_url().'reports/student_withdrawl_summary/'?>" class="modal_cancel_btn" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                <input class="modal_save_btn" type="submit" id="withdrawl_pdf" value="<?php echo get_phrase('get_pdf');?>">
                <input class="modal_save_btn" type="submit" id="withdrawl_excel" value="<?php echo get_phrase('get_excel');?>">
            <?php } ?>
        </div>
    </div>
</form> 

<div class="col-lg-12 col-sm-12">
    <table class="table table-bordered datatable table-hover cursor table_export" data-step="1" data-position='top' data-intro="student withdrawl summary ">
        <thead>
            <tr>
                <th style="width:20px;"><?php echo get_phrase('#');?></th>
                <th><?php echo get_phrase('student_details');?></th>
                <th style="width:100px;"><?php echo get_phrase('Admission_date');?></th>
                <th style="width:100px;"><?php echo get_phrase('request_date');?></th>
                <th style="width:100px;"><?php echo get_phrase('withdraw_date');?></th>
                <th style="width:100px;"><?php echo get_phrase('Reason');?></th>
            </tr>
        </thead>
        <tbody>
        <?php
    		$j=0;
            foreach($student_withdrawl as $row)
    		{
    		    
                $j++;
    		?>
    		<tr>
    		    <td class="td_middle"><?php echo $j; ?></td>
    		    <td>
    		        <strong>Name : </strong><?php echo $row['name']; ?><br>
    		        <strong>Address : </strong><?php echo $row['address']; ?><br>
    		        <strong>Roll # : </strong><?php echo $row['roll']; ?><br>
    		        <strong>Mobile # : </strong><?php echo $row['mob_num']; ?><br>
    		        
    		    </td>
    		    <td class="td_middle"><?php echo date("Y-m-d",strtotime($row['adm_date'])); ?></td>
    		    <td class="td_middle"><?php echo date("Y-m-d",strtotime($row['request_date'])); ?></td>
    		    <td class="td_middle"><?php echo date("Y-m-d",strtotime($row['confirm_date'])); ?></td>
    		    <td class="td_middle"><?php echo $row['std_withdarwal_reason']; ?></td>
    		</tr>
    		
            <?php
            }
            ?>
        </tbody>
    </table>    
</div>

<script>
    $('#withdrawl_pdf').click(function(){
        $('#student_withdrawl_summary').attr('action', '<?php echo base_url(); ?>reports/student_withdrawl_pdf');
        $('#student_withdrawl_summary').submit();
    });
    $('#withdrawl_excel').click(function(){
        $('#student_withdrawl_summary').attr('action', '<?php echo base_url(); ?>reports/student_withdrawl_excel');
        $('#student_withdrawl_summary').submit();
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