<style>
    .main-content{
        overflow:scroll;
    }
    .no-print{position:relative;top:5px;left:15px;z-index:99999;border:2px solid #fff;border-radius:20px 20px 20px 0;box-shadow:1px 0 5px 1px #ccc;outline:0}
    /*#DataTables_Table_0_wrapper*/
    /*{*/
    /*    width:150.5% !important;*/
    /*}*/
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
<script type="text/javascript">

$(window).on("load" , function() {
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
            <?php echo get_phrase('payroll_sheet'); ?>
        </h3>
    </div>
</div>
<form action="<?php echo base_url(); ?>payroll/payroll_sheet" method="post" class="validate" id="filter" data-step="2" data-position='top' data-intro="use this filter for specific staff payroll">
    <div class="row filterContainer">
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <label for="month"><b>Select Month</b></label>
            <select id="month" name="month" class="form-control" >
                <?php echo month_option_list($month); ?>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <label for="year"><b>Select Year</b></label>
            <select id="year" name="year" class="form-control">
                <?php echo year_option_list(date('Y')-1,date('Y')+1,$year); ?>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <input type="hidden" name="apply_filter" value="1" />
            <button type="submit" class="modal_save_btn" id="btn_submit"><?php echo get_phrase('filter'); ?></button>
            <?php if($apply_filter == 1){ ?>
                <a href="<?php echo base_url(); ?>payroll/payroll_sheet" class="modal_cancel_btn" id="btn_remove"> <i class="fa fa-remove"></i>
                    <?php echo get_phrase('remove_filter');?>
                </a>
                &nbsp;
                <button type="submit" class="modal_save_btn payroll_letter"> <i class="fa fa-print"></i>
                    <?php echo get_phrase('print_payroll_letter');?>
                </button>
            <?php } ?>
        </div>
    </div>
        
</form>
<?php if($apply_filter == 1){ ?>
    <!--<button type="button" id="btnExport" class="modal_save_btn no-print" style="background:#008000e3 !important;">Generate Excel Report</button>-->
<?php } ?>    
<div class="col-lg-12 col-md-12">
    <form id="frmText" action="<?php echo base_urL().'payroll/post_salary_letter'?>" method="POST">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-responsive " id="table_export_exp" >
        	<thead>
        		<tr>
            		<th style="width:34px !important;"><?php echo get_phrase('s_no');?></th>
            		<th><?php echo get_phrase('emp_name');?></th>
            		<th><?php echo get_phrase('emp_code');?></th>
            		<th><?php echo get_phrase('emp_cnic');?></th>
            		<th><?php echo get_phrase('basic_salary');?></th>
            		<th><?php echo get_phrase('earned_salary');?></th>
            		<th><?php echo get_phrase('house_allownce');?></th>
            		<th><?php echo get_phrase('medical_allownce');?></th>
            		<th width="15%"><?php echo get_phrase('other_allownces');?></th>
            		<th><?php echo get_phrase('total_gross_salary');?></th>
            		<th><?php echo get_phrase('income_tax');?></th>
            		<th width="15%"><?php echo get_phrase('other_deductions');?></th>
            		<th><?php echo get_phrase('net_salary');?></th>
            		<th><?php echo get_phrase('action');?></th>
        		</tr>
        	</thead>
        	<tbody>
        		<?php 
            	    $count = 1;
            	    $total_basic_salary = 0;
            	    $total_earned_salary = 0;
            	    $total_house_rent = 0;
            	    $total_medical = 0;
            	    $total_allownce_amount_sum = 0;
            	    $total_deduction_amount_sum = 0;
            	    $total_income_tax = 0;
            	    $total_gross_salary = 0;
            	    $total_net_salary = 0;
            	    
            	    foreach($payroll_details as $row){
            	        $s_s_s_id = $row['s_s_s_id'];
        			    $medical_allownce = $row['medical_allownce'];
        			    $house_rent_allownce = $row['house_rent_allownce'];
        			    
        			    $total_house_rent += $house_rent_allownce;
        			    $total_medical += $medical_allownce;
            		?>
                		<tr>
                			<td class="td_middle"><?php echo $count++;?></td>
                			<td class="td_middle">
                			    <?php echo $row['name']; ?>
                			</td>
                			<td class="td_middle">
                			    <?php echo $row['employee_code']; ?>
                			</td>
                			<td class="td_middle">
                			    <?php echo $row['id_no']; ?>
                			</td>
                			<td class="td_middle">
                			    <?php echo $row['basic_salary']; $total_basic_salary += $row['basic_salary']; ?>
                			</td>
                			<td class="td_middle">
                			    <?php echo $row['earned_salary']; $total_earned_salary += $row['earned_salary']; ?>
                			</td>
                			<td class="td_middle">
                			    <?= $house_rent_allownce; ?>
                			</td>
                			<td class="td_middle">
                			    <?= $medical_allownce; ?>
                			</td>
                			<td class="td_middle">
                			    <table class="table table-bordered">
                			    <?php 
                                    $allownce_ids = $row['allownce_ids'];
                                    $allownce_array = explode("," , $allownce_ids);
                                    $allownce = 0;
                                    $total_allownce = 0;
                                    echo "<thead>";
                                    foreach($allownce_array as $allownce)
                                    {
                                        $details = get_staff_salary_allownces_details($allownce,$s_s_s_id);
                                        echo"
                                            <th style='background: transparent !important;color: black !important;'>
                                                <strong style='font-size:11px;'>".$details[0]['allownce_title']."</strong>
                                            </th>";
                                            
                                    }
                                    echo "</thead><tbody><tr>";
                                    foreach($allownce_array as $allownce)
                                    {
                                        $details = get_staff_salary_allownces_details($allownce,$s_s_s_id);
                                        if($details[0]['allownce_amount'] > 0){
                                            echo "<td>".$details[0]['allownce_amount']."</td>";
                                            $total_allownce += $details[0]['allownce_amount'];
                                        }else{
                                            echo "<td>0</td>";
                                        }
                                    }
                                    echo "</tr></tbody>";
                                    $totals_allwonce_amounts = $total_allownce+$medical_allownce+$house_rent_allownce;
                                    $totals_allwonce_amount = $total_allownce;
                                    $total_allownce_amount_sum += $totals_allwonce_amount;
                			    ?>
                			    </table>
                			</td>
                			<td class="td_middle">
                			    <?php
                			        $total_gross = $row['earned_salary'] +  $totals_allwonce_amounts;
                			        echo $total_gross;
                			        $total_gross_salary += $total_gross;
                			    ?>
                			</td>
                			<td class="td_middle">
                			    <?php
                			        $income_tax = $row['income_tax_deduction'];
                			        echo $income_tax; 
                			        $total_income_tax += $income_tax;
                			    ?>
                			</td>
                			<td class="td_middle">
                			    <table class="table table-bordered">
                			    <?php 
                			        
                			        $deduction_ids = $row['deductions_ids'];
                                    $deduction_array = explode("," , $deduction_ids);
                                    $deductions = 0;
                                    $total_deductions = 0;
                                    echo "<thead>";
                                    foreach($deduction_array as $deduction)
                                    {
                                        $details = get_staff_salary_deductions_details($deduction,$s_s_s_id);
                                        echo"
                                            <th style='background: transparent !important;color: black !important;'>
                                                <strong style='font-size:11px;'>".$details[0]['deduction_title']."</strong>
                                            </th>";
                                            
                                            
                                    }
                                    echo "</thead><tbody><tr>";
                                    foreach($deduction_array as $deduction)
                                    {
                                        $details = get_staff_salary_deductions_details($deduction,$s_s_s_id);
                                        if($details[0]['deduction_amount'] > 0){
                                            echo "<td>".$details[0]['deduction_amount']."</td>";
                                            $total_deductions += $details[0]['deduction_amount'];
                                        }else{
                                            echo "<td>0</td>";
                                        }
                                    } 
                                    echo "</tr></tbody>";
                                    $total_deuction_amount = $total_deductions;
                                    $total_deduction_amount_sum += $total_deuction_amount;
                			    ?>
                			    </table>
                			</td>
                			<td class="td_middle"><?php echo $row['net_salary']; $total_net_salary += $row['net_salary']; ?></td>
                			<td class="td_middle">
                			<?php 
                				if ($row['is_posted'] == 1){
                					echo "<span style='color:green'>Posted</span>";
                				}else{
                				// 	echo "<span style='color:red'>UnPosted</span>";
                				    echo '<input type="checkbox" name="s_s_s_id[]" value=" '."s_s_s_id:".$row['s_s_s_id'].'-'."allowances:".$total_allownce.'-'."total_gross:".$total_gross.'-'."income_tax:".$income_tax.'-'."net_salary:".$row['net_salary'].'-'."total_deduction:".$deduction_ids.'    " />';
                				}
                			?>
                			
                			
                			</td>
                		</tr>
            		<?php
            	    }
        	    
        		?>
        	</tbody>
        	<tfoot>
        	    <td colspan="4" class="td_middle"><h4>TOTAL</h4></td>
        	    <td class="text-dark"><b><?= $total_basic_salary; ?></b></td>
        	    <td class="text-dark"><b><?= $total_earned_salary; ?></b> </td>
        	    <td class="text-dark"><b><?= $total_house_rent; ?></b> </td>
        	    <td class="text-dark"><b><?= $total_medical; ?></b> </td>
        	    <td class="text-dark"><b><?= $total_allownce_amount_sum; ?> </b> </td>
        	    <td class="text-dark"><b><?= $total_gross_salary; ?></b> </td>
        	    <td class="text-dark"><b><?= $total_income_tax; ?></b> </td>
        	    <td class="text-dark"><b><?= $total_deduction_amount_sum; ?></b> </td>
        	    <td class="text-dark" colspan="2"><b><?= $total_net_salary; ?></b> </td>
        	</tfoot>
        </table>
        
            <input type="hidden" name="month" value="<?php echo $month ?>" />
            <input type="hidden" name="year" value="<?php echo $year ?>" />
        <?php if($apply_filter == 1 && $count > 1){ ?>
        <div class="col-md-12 text-right">
            <button type="submit" class="modal_save_btn" onclick="return check_salaries()" name="submit" value="submit">Post Salries</button>
        </div>
        <?php } ?>
    </form>
</div>

<script src="<?=base_url()?>assets/table2excel.js"></script>
<script>

    function check_salaries(){
        var checked = $("#frmText input:checked").length > 0;
        if (!checked){
            Swal.fire({
                      position: 'top-end',
                      icon: 'error',
                      title: "Please check at least one checkbox",
                      showConfirmButton: false,
                      timer: 3000
                    });
            return false;
        }else{
            return true;
        }
    }
    $(function () {
        $("#btnExport").click(function () {
            $("#table_export_exp").table2excel({
                filename: "Payroll Sheet Report <?= $month ?>-<?= $year ?>.xls"
            });
        });
    });
    $(".payroll_letter").on('click',function(){
        $("#filter").attr("action","<?php echo base_url(); ?>payroll/print_payroll_letter");    
    });
    
    $(".page-container").addClass("sidebar-collapsed");
</script>