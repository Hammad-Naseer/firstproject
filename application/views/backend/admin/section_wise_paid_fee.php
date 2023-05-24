<style>
.tile-stats{min-height:140px!important}.system_name.inline{display:inline-block;margin:0;padding:20px 0 5px;width:100%}.img-res{float:none;height:50px;width:auto}.col-mh{color:#4a8cbb;font-size:16px;font-weight:700;padding-top:20px;text-align:right;text-transform:uppercase}.blocks{margin:0 auto;text-align:right}
.view_details{
    background: none;
    border: none;
    color: #2196f3;
    font-weight: 600;
    padding-left: 0px;
}
</style>

<?php
    $d_school_id = $d_school_id;
    $branch_name = "";
    if($d_school_id=="")
    {
        $d_school_id = $_SESSION['school_id'];
    }else{
        $school_details = get_school_details($d_school_id);
        $branch_name =  $school_details['name'];
        $branch_logo =  $school_details['logo'];
        $branch_folder =  $school_details['folder_name'];
    }
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="section_wise_summary" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('section_wise_fee_summary'); ?>
        </h3>
    </div>
</div>

<form action="<?php echo base_url().'reports/section_wise_paid_fee/'.$d_school_id?>" method="post" name="section_wise_paid_form" id="section_wise_paid_form" data-step="1" data-position="top" data-intro="use this filter to get specific Fee Summary records">
    <div class="row filterContainer"> 
        <div class="col-md-4 col-lg-4 col-sm-4">
            <label><?php echo get_phrase('start_date');?></label>
            <input class="form-control datepicker" type="text" name="startdate" autocomplete="off"  id="start_date" value="<?php echo $start_date; ?>" placeholder="Start Date" style="background-color:#FFF !important;" required="" data-format="dd/mm/yyyy">
            <span id="sd"></span>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <label><?php echo get_phrase('end_date');?></label>
             <input class="form-control datepicker" type="text" name="enddate" autocomplete="off"  id="end_date" value="<?php echo $end_date;  ?>" placeholder="End Date" style="background-color:#FFF !important;" required="" data-format="dd/mm/yyyy">
             <span id="ed"></span>
        </div> 
        <div class="col-md-4 col-lg-4 col-sm-4"> 
        <label id="section_id_filter_selection"><?php echo get_phrase('select_department')." / ".get_phrase('class')." / ".get_phrase('section');?></label>
            <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                <option value="">
                    <?php echo get_phrase('select_any_option');?>  
                </option>
                <?php echo department_class_section($section_id,$d_school_id);?>
            </select> 
        </div> 
        <div class="col-md-8 col-lg-8 col-sm-8 pt-3"> 
            <input type="hidden" name="apply_filter" value="1">
            <input type="submit" id="filter" value="<?php echo get_phrase('filter');?>" class="modal_save_btn"> 
            <?php if ($apply_filter == 1) { ?>
                <a id="btn_show" href="<?php echo base_url().'reports/section_wise_paid_fee/'.$d_school_id?>" class="modal_cancel_btn" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                <input class="modal_save_btn" type="submit" id="section_wise_paid_pdf" value="<?php echo get_phrase('get_pdf');?>">
                <input class="modal_save_btn" type="submit" id="section_wise_paid_excel" value="<?php echo get_phrase('get_excel');?>">
            <?php } ?>
        </div>
    </div>
    <input type="hidden" name="d_school_id" value="<?php echo $d_school_id;?>">
</form> 
<?php
if (isset($section_id) && !empty($section_id)) {
   $id_arr = remove_prefix($section_id);
    $prefix = $id_arr['prefix'];
    $value = $id_arr['value'];
}
?>
 
<div class="col-md-12" data-step="2" data-position="top" data-intro="Section wise fee summary record">
    <div class="table-responsive">
        <table class="table table_export table-bordered table-hov er table-striped table-condensed table-responsive table-sm" role="grid">
            <thead>
                <tr>
                    <th><?php echo get_phrase('sr');?>.</th> 
                    <th><?php echo get_phrase('department');?></th>
                    <th><?php echo get_phrase('class');?></th>
                    <th><?php echo get_phrase('section');?></th>
                    <th><?php echo get_phrase('strength');?></th>
                    <th><?php echo get_phrase('Fee');?></th>
                    <th><?php echo get_phrase('Reciept');?></th>
                    <th><?php echo get_phrase('Outstanding Fee');?></th>
                    <th><?php echo get_phrase('% Recovery');?></th>
                </tr>
            </thead>
            <tbody style="width: 250px;">
                <?php
                    $count = 0;
                    $sec_ary = department_class_section_hierarchy($section_id,$d_school_id);
                    foreach ($sec_ary as $key => $value)
                    {
                        $count++;
                    ?>
                    <tr>
                        <td><?php echo $count;?></td>
                        <td><?php echo $value['d'];?></td>
                        <td><?php echo $value['c'];?></td>
                        <td><?php echo $value['s'];?></td>
                        
                        <td><?php echo section_student_count($value['s_id']);?></td>
                        <td>
                            <!--$total_details_arr[$value['s_id']]['actual_amount'];-->
                            <?php $fee = sectionFeeSumUp($value['s_id'],$start_date,$end_date); ?>
                            <?php 
                                if ($apply_filter == 1) { 
                                $date_slash = date_slash($start_date);
                                $chalan_month = date("m",strtotime($date_slash));
                                $chalan_year = date("Y",strtotime($start_date));
                            ?>
                                <?php if($fee > 0){ ?>
                                    <a target="_blank" class="view_details" href="<?=base_url()?>monthly_fee/view_print_chalan_class/<?=$value['s_id']?>/<?=$chalan_month?>/<?=$chalan_year?>">
                                        <?php echo number_format($fee); ?>
                                        <b><i class="fas fa-info-circle"></i></b>
                                    </a>

                                <?php } ?> 
                        <?php } ?>
                        </td>
                        <td>
                            <!--$payment_details_arr[$value['s_id']]['received_amount'];-->
                            <?php $recovery = sectionFeeRecovery($value['s_id'],$start_date,$end_date); ?>
                            <?php if ($apply_filter == 1) { ?>
                                <form action="<?=base_url()?>reports/paid_students/<?=$_SESSION['school_id']?>" method="post">
                                    <input type="hidden" name="section_id" value="s<?=$value['s_id']?>">
                                    <input type="hidden" name="startdate" value="<?=$start_date?>">
                                    <input type="hidden" name="enddate" value="<?=$end_date?>">
                                    <?php if($recovery > 0){ ?>
                                        <button type="submit" class="view_details" title="view recieved breakdown">
                                            <?php echo number_format($recovery); ?>
                                            <b><i class="fas fa-info-circle"></i></b>
                                        </button>
                                    <?php } ?>    
                                </form>
                            <?php } ?>
                        </td>
                        <td>
                            <?php $outstanding = $fee-$recovery; ?>
                            <?php if ($apply_filter == 1) { ?>
                                <form action="<?=base_url()?>reports/unpaid_students/<?=$_SESSION['school_id']?>" method="post">
                                    <input type="hidden" name="section_id" value="s<?=$value['s_id']?>">
                                    <input type="hidden" name="startdate" value="<?=$start_date?>">
                                    <input type="hidden" name="enddate" value="<?=$end_date?>">
                                    <?php if($outstanding > 0){ ?>
                                        <button type="submit" class="view_details" title="view outstanding breakdown">
                                            <?= number_format($outstanding); ?>
                                            <b><i class="fas fa-info-circle"></i></b>
                                        </button>
                                    <?php } ?>  
                                </form>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                                $rec_amount = $payment_details_arr[$value['s_id']]['received_amount'];
                                if($rec_amount > 0){
                                    $total_class_amount = $total_details_arr[$value['s_id']]['actual_amount'];
                                    $percentage = ($rec_amount / $total_class_amount) * 100;
                                    if(is_nan($percentage) == 1){
                                       echo "";
                                    }else{
                                       echo number_format($percentage,2)."%";
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    <?php } 
                ?>
            </tbody>
        </table>
    </div>
</div> 



<script>
    $(document).ready(function(){
        $('#section_wise_paid_pdf').click(function(){
            $('#section_wise_paid_form').attr('action', '<?php echo base_url(); ?>reports/section_wise_paid_pdf');
            $('#section_wise_paid_form').submit();
        });

        $('#section_wise_paid_excel').click(function(){
            $('#section_wise_paid_form').attr('action', '<?php echo base_url(); ?>reports/section_wise_paid_excel');
            $('#section_wise_paid_form').submit();
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