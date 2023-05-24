<style>
	.err_div{position: absolute;
        color: red;
        text-align: center;
	}
</style>
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('payroll_YTD_report'); ?>
        </h3>
    </div>
</div>
<form action="<?php echo base_url(); ?>payroll/payroll_ytd_report" method="post" class="validate" id="filter" data-step="2" data-position='top' data-intro="use this filter for specific staff salary record">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="staff_id"><b>Staff</b></label>
            <select id="staff_id" class="form-control select2" name="staff_id" required>
                <?php echo staff_list('',$staff_id); ?>
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="month"><b>Start Month</b></label>
            <input type="month" name="start_month" class="form-control" value="<?php echo $start_month_year ?>" required>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="month"><b>End Month</b></label>
            <input type="month" name="end_month" class="form-control" value="<?php echo $end_month_year ?>" required>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <input type="hidden" name="apply_filter" value="1" />
            <button type="submit" class="modal_save_btn" id="btn_submit"><?php echo get_phrase('filter'); ?></button>
            <?php if($apply_filter == 1){ ?>
                <a href="<?php echo base_url(); ?>payroll/view_staff_salary" class="modal_cancel_btn" id="btn_remove"> <i class="fa fa-remove"></i>
                    <?php echo get_phrase('remove_filter');?>
                </a>
            <?php } ?>
        </div>
    </div>
        
</form>

<div class="col-lg-12 col-md-12">
    <?php
        if(isset($salary_data)){
    ?>
    <div id="salary_div" class="row salary_layout">
        <div class="col-md-12 text-center">
            <div class="col-md-12">
                <p class="emp_data">
                    
                    <b>Employee Name: </b> 
                    <u> &nbsp;&nbsp;&nbsp; <?= $salary_data->name; ?> &nbsp;&nbsp;&nbsp;</u>
                </p>
            </div>
            <div class="col-md-6 col-sm-6">
                <p class="emp_data">
                    <b>Start Month Year: </b> 
                    <u> &nbsp;&nbsp;&nbsp; <?= $start_month_year; ?> &nbsp;&nbsp;&nbsp;</u>
                </p>
            </div>
            <div class="col-md-6 col-sm-6">
                <p class="emp_data">
                    <b>End Month Year: </b> 
                    <u> &nbsp;&nbsp;&nbsp; <?= $end_month_year; ?> &nbsp;&nbsp;&nbsp;</u>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <h4><b>MTD Report</b></h4>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                <tr>
                    <th class="text-center" colspan="2"><b>Earnings</b></th>
                </tr>
                <tr>
                    <td>Basic Salary</td>
                    <td align="right">
                        <?= number_format($salary_data->monthly_basic_salary,2) ?>
                    </td>
                </tr>
                <tr>
                    <td ><b>Earned Salary</b></td>
                    <td align="right">
                        <?= number_format($salary_data->monthly_earned_salary,2) ?>
                    </td>
                </tr>
                <tr>
                    <td>House Rent Allownce</td>
                    <td align="right">
                        <?= number_format($salary_data->monthly_house_rent_allownce,2) ?>
                    </td>
                </tr>
                <tr>
                    <td>Medical Allownce</td>
                    <td align="right">
                        <?= number_format($salary_data->monthly_medical_allownce,2) ?>
                    </td>
                </tr>
                <tr>
                    <td>Gross Salary</td>
                    <td align="right">
                        <?php 
                            $gross_salary = $salary_data->monthly_earned_salary+$salary_data->monthly_house_rent_allownce+$salary_data->monthly_medical_allownce;
                            echo number_format($gross_salary,2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2"><b>Allownces</b></td>
                </tr>
                <!--Total Gross Salary-->
                <tr>
                    <td><b>Total Gross Salary & Allownces</b></td>
                    <td align="right">
                        <?php 
                            $total_addition = $gross_salary + $total_allownce;
                            echo $total_addition;
                        ?>
                    </td>
                </tr>
            </table>
            
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                <tr>
                    <th class="text-center" colspan="2"><b>Deductions</b></th>
                </tr>
                <!--Loop Start-->
                <tr>
                    <td>Income Tax</td>
                    <td><?= number_format($salary_data->monthly_income_tax_deduction,2) ?></td>
                </tr>
                <!--Loop Start-->
                <!--Total Deduction-->
                <tr>
                    <td>Total Deduction</td>
                    <td align="right">
                        <?php echo $total_deductions = $salary_data->monthly_income_tax_deduction; ?>
                    </td>
                </tr>
                
                <!--Net Salary-->
                <tr>
                    <td><b>NET Salary</b></td>
                    <td align="right">
                        <?php 
                            $net_amount = $total_addition-$total_deductions;
                            echo $net_amount;
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h4><b>YTD Report</b></h4>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                <tr>
                    <th class="text-center" colspan="2"><b>Earnings</b></th>
                </tr>
                <tr>
                    <td>Basic Salary</td>
                    <td align="right">
                        <?= number_format($salary_data->basic_salary,2) ?>
                    </td>
                </tr>
                <tr>
                    <td ><b>Earned Salary</b></td>
                    <td align="right">
                        <?= number_format($salary_data->earned_salary,2) ?>
                    </td>
                </tr>
                <tr>
                    <td>House Rent Allownce</td>
                    <td align="right">
                        <?= number_format($salary_data->house_rent_allownce,2) ?>
                    </td>
                </tr>
                <tr>
                    <td>Medical Allownce</td>
                    <td align="right">
                        <?= number_format($salary_data->medical_allownce,2) ?>
                    </td>
                </tr>
                <tr>
                    <td>Gross Salary</td>
                    <td align="right">
                        <?php 
                            $gross_salary = $salary_data->earned_salary+$salary_data->house_rent_allownce+$salary_data->medical_allownce;
                            echo number_format($gross_salary,2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2"><b>Allownces</b></td>
                </tr>
                <!--Loop Start-->
                <?php
                    $slip_ids = $salary_data->slips_ids;
                    $slip_ids_array = explode("," , $slip_ids);
                    $slip_ids_implode = implode("','",$slip_ids_array);
                    $get_allownces_amount = $this->db->query("SELECT SUM(allownce_amount) as allownce_amount,allownce_id,s_s_s_id FROM ".get_school_db().".staff_salary_allownces where s_s_s_id IN('$slip_ids_implode') GROUP BY allownce_id ")->result_array();
                    $total_allownce = 0;
                    foreach($get_allownces_amount as $allownce)
                    {
                        $details = get_staff_salary_allownces_details($allownce['allownce_id'],$allownce['s_s_s_id']);
                        echo '<tr><td>'.$details[0]['allownce_title'] .' </td>';
                        echo '<td> '.$allownce['allownce_amount'] .' </td>';
                        
                        $total_allownce += $allownce['allownce_amount'];
                        echo '</tr>';
                    } 
                ?>
                <!--Loop Start-->
                <!--Total Deduction-->
                <tr>
                    <td><b>Total Gross Salary & Allownces</b></td>
                    <td align="right">
                        <?php 
                            $total_addition = $gross_salary + $total_allownce;
                            echo $total_addition;
                        ?>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                <tr>
                    <th class="text-center" colspan="2"><b>Deductions</b></th>
                </tr>
                <tr>
                    <td>Income Tax</td>
                    <td><?= number_format($salary_data->income_tax_deduction,2) ?></td>
                </tr>
                <!--Loop Start-->
                
                <?php
                    $get_deduction_amount = $this->db->query("SELECT SUM(deduction_amount) as deduction_amount,deduction_id,s_s_s_id FROM ".get_school_db().".staff_salary_deductions where s_s_s_id IN('$slip_ids_implode') GROUP BY deduction_id ")->result_array();
                    // $allownce = 0;
                    $total_deductions = 0;
                    foreach($get_deduction_amount as $deduction)
                    {
                        $details = get_staff_salary_deductions_details($deduction['deduction_id'],$deduction['s_s_s_id']);
                        echo '<tr><td>'.$details[0]['deduction_title'] .' </td>';
                        echo '<td> '.$deduction['deduction_amount'] .' </td>';
                        
                        $total_deductions += $deduction['deduction_amount'];
                        echo '</tr>';
                    } 
                ?>
                <!--Loop Start-->
                <!--Total Deduction-->
                <tr>
                    <td>Total Deduction</td>
                    <td align="right">
                        <?php echo $total_deductions+$salary_data->income_tax_deduction; ?>
                    </td>
                </tr>
                
                <!--Net Salary-->
                <tr>
                    <td><b>NET Salary</b></td>
                    <td align="right">
                        <?php 
                            $net_amount = $total_addition-$total_deductions;
                            echo $net_amount;
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
    </div>
    <?php } ?>
</div>

<script type="text/javascript">

$(window).on("load" , function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});

</script>

<!--Datatables Add Button Script-->
