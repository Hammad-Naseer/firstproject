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
               <?php echo get_phrase('breakeven_report'); ?>
        </h3>
    </div>
</div>

<div class="filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
    <form action="<?php echo base_url().'reports/breakeven_report'?>" method="POST" name="breakeven_report_form" id="breakeven_report_form">
        <div class="col-md-6">
           <div class="form-group">
                  <label for="start_date">Start Date</label>
                  <input type="date" id="start_date" class="form-control" name="start_date" value="<?php echo $start_date;?>" placeholder="Select Start Date" required data-format="dd/mm/yyyy" />
                  <span style="color: red;" id="sd"></span>
            </div>
        </div>
    
        <div class="col-md-6">
           <div class="form-group">
                  <label for="end_date">End Date</label>
                  <input type="date" id="end_date" class="form-control" name="end_date" value="<?php echo $end_date;?>" placeholder="Select End Date"  required data-format="dd/mm/yyyy" />
                  <span style="color: red;" id="ed"></span>
            </div>
        </div>
    
        <!--<div class="col-md-6">-->
            <input type="hidden" id="apply_filter" name="apply_filter" value="1">
            <button type="submit" id="select" class="modal_save_btn" style="margin-left: 15px;"> <?php echo get_phrase('filter');?></button>
            <?php if($apply_filter == 1) {?>
                <a id="btn_show" href="<?php echo base_url(); ?>reports/breakeven_report" class="modal_cancel_btn">
                    <i class="fa fa-remove"></i> <?php echo get_phrase('remove_filter');?> 
                </a>
                &nbsp;
                <button type="button" id="btnExport" class="modal_save_btn no-print" style="background:#008000e3 !important;">Generate Excel Report</button>
                  <!--<form action="<?php echo base_url().'reports/breakeven_report/'?>" method="post" name="breakeven_report_form" id="breakeven_report_form" data-step="1" data-position="top" data-intro="use this filter to get specific Fee Summary records">-->
                 <input class="modal_save_btn" type="submit" id="breakeven_report_pdf" value="<?php echo get_phrase('get_pdf');?>">
            <?php }?>
        <!--</div>-->
    </form>
</div>
<div class="col-lg-12 col-sm-12 topbar">
    <table class="table table-bordered table-hover" id="table_export_exp" data-step="2" data-position='top' data-intro="Breakeven Report ">
        <thead>
            <tr>
                <th><?php echo get_phrase('Discription');?></th>
                <th><?php echo get_phrase('amount');?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Total Revenue</b></td>
                <td><?php echo number_format($revenue); ?></td>
            </tr>
            
            <tr>
                <td><b>Total Expenses</b></td>
                <td><?php echo number_format($expanse); ?></td>
            </tr>
            
            <tr>
                <td><b>Net Profit</b></td>
                <td>
                    <?php
                        $net_profit = $revenue-$expanse;
                        echo number_format($net_profit);
                    ?>
                </td>
            </tr>
            
            <tr>
                <td><b>Current Student Strength</b></td>
                <td>
                    <?php   
                        echo $total_std = total_school_studnets(); 
                    ?>
                </td>
            </tr>
            
            <tr>
                <td><b>Per Student Expenses</b></td>
                <td><?php echo $per_student_expense = number_format($expanse/$total_std,2); ?></td>
            </tr>
            
            <tr>
                <td><b>Breakeven Strength</b></td>
                <td>
                <?php 
                    if($per_student_expense > 0){
                        $break_even_strngth = $net_profit / $per_student_expense;
                        if(is_nan($break_even_strngth)){
                            echo "";
                        }else{
                             echo number_format($break_even_strngth);
                        }
                    }
                ?>
                </td>
            </tr>
            
            <tr>
                <td><b>Capacity</b></td>
                <td>
                    <?php
                        $school_capacity = school_capacity($_SESSION['school_id']);
                        echo number_format($school_capacity);
                    ?>
                </td>
            </tr>
            
            <tr>
                <td><b>Capacity utilization</b></td>
                <td><?php echo $total_std /$school_capacity; ?></td>
            </tr>

        </tbody>
    </table>    
</div>


<script>
    //  $("#start_date").change(function () {
    //     document.getElementById("sd").innerHTML = "";
    //     var startDate = s_d($("#start_date").val());
    //     var endDate = s_d($("#end_date").val());
       
    //     if ((Date.parse(endDate) < Date.parse(startDate)))
    //     {
    //         document.getElementById("sd").innerHTML = "<?php echo get_phrase('start_date_should_be_less_then_end_date');?>";
    //         document.getElementById("start_date").value = "";
    //     }
    //     else if ((Date.parse(startDate) < Date.parse("<?php echo $start_date; ?>"))) 
    //     {
    //         document.getElementById("sd").innerHTML = "<?php echo get_phrase('please_select_start_date_within_academic_session');?>";
    //         document.getElementById("start_date").value = "";      
    //     }
    // });
    
    // $("#end_date").change(function () {
    //     debugger;
    //     document.getElementById("ed").innerHTML = "";
    //     var startDate = s_d($("#start_date").val());
    //     var endDate = s_d($("#end_date").val());
    //     if ((Date.parse(startDate) > Date.parse(endDate))) {
    //         debugger;
    //         document.getElementById("ed").innerHTML = "<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>";
    //         document.getElementById("end_date").value = "";      
    //     }
    //     else if ((Date.parse(endDate) > Date.parse("<?php echo $end_date; ?>"))) {
    //         debugger;
    //         document.getElementById("ed").innerHTML = "<?php echo get_phrase('please_select_end_date_within_academic_session');?>";
    //         document.getElementById("end_date").value = "";    
    //     }
    // });

    // function s_d(date){
    //   var date_ary=date.split("/");
    //   return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    // }
    
</script>

<script src="<?=base_url()?>assets/table2excel.js"></script>
<script>
    $(function () {
        $("#btnExport").click(function () {
            $("#table_export_exp").table2excel({
                filename: "Breakeven From <?= $start_date ?> To <?= $end_date ?>.xls"
            });
        });
    });
</script>
<script>
$(document).ready(function(){
        $('#breakeven_report_pdf').click(function(){
            $('#breakeven_report_form').attr('action', '<?php echo base_url(); ?>reports/breakeven_report_pdf');
            $('#breakeven_report_form').submit();
        });
        
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