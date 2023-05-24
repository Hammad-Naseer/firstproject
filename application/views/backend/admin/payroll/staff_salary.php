<style>
    .emp_data
    {
        font-size:18px !important;
    }
    td > b, p > b{
        color: black;
    }
    .salary_layout
    {
        border: 1px solid #ccc;
        background-color: #4aa7cdab;
        padding: 12px;
        height: auto;
        width: 95%;
        margin-left: 29px;
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
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('salary_annexure'); ?>
        </h3>
    </div>
</div>

<form action="<?php echo base_url(); ?>payroll/get_staff_salary" method="post" class="validate" id="filter" data-step="2" data-position='top' data-intro="use this filter for specific staff salary record">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="staff_id"><b>Staff</b></label>
            <select id="staff_id" class="form-control select2" name="staff_id">
                <?php echo staff_list('',$staff_id); ?>
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="month"><b>Select Month</b></label>
            <select id="month" name="month" class="form-control" data-validate="required" data-message-required="Value Required">
                <?php echo month_option_list($month); ?>
            </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label for="year"><b>Select Year</b></label>
            <select id="year" name="year" class="form-control" required>
                <?php echo year_option_list(date('Y')-1,date('Y')+1,$year); ?>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <input type="hidden" name="apply_filter" value="1" />
            <button type="submit" class="modal_save_btn" id="btn_submit"><?php echo get_phrase('filter'); ?></button>
            <?php if($apply_filter == 1){ ?>
                <a href="<?php echo base_url(); ?>payroll/staff_salary" class="modal_cancel_btn" id="btn_remove"> <i class="fa fa-remove"></i>
                    <?php echo get_phrase('remove_filter');?>
                </a>
            <?php } ?>
        </div>
    </div>
        
</form>

<?php
    if(isset($salary_data)) {
        $this->load->helper("num_word");
        $gross_salary = $salary_data[0]['gross_salary'];
?>

    <form action="<?php echo base_url(); ?>payroll/staff_salary_slip_save" method="post" data-step="2" data-position='top' data-intro="use this filter for specific staff salary record">
        <div class="row salary_layout">
        <div class="col-md-12 text-center">
            <table class="table table-bordered">
                <tr>
                    <th colspan="4" class="text-center">
                        <h4><b>Total Attendance (<?= $month ?>-<?= $year ?>)</b></h4>
                    </th>
                </tr>
                <tr>
                    <td class="text-center">
                        <b>Total Days </b>
                        <input type="number" name="total_days" class="form-control total_days" autofocus value="30">
                    </td>
                    <td class="text-center">
                        <b>Total Presents - </b>
                        <b><?= count_monthly_staff_attendance($staff_id,$month,$year,'P') ?></b>
                    </td>
                    <td class="text-center">
                        <b>Total Absents - </b>
                        <b><?= count_monthly_staff_attendance($staff_id,$month,$year,'A') ?></b>
                    </td>
                    <td class="text-center">
                        <b>Total Leaves - </b>
                        <b><?= count_monthly_staff_attendance($staff_id,$month,$year,'L') ?></b>
                    </td>
                </tr>
            </table>
            <div class="col-md-12">
                <p class="emp_data">
                    <input type="hidden" name="staff_id" value="<?= $salary_data[0]['s_id'] ?>">
                    <input type="hidden" name="month" value="<?= $month ?>">
                    <input type="hidden" name="year" value="<?= $year ?>">
                    <b>Employee Name: </b> 
                    <u> &nbsp;&nbsp;&nbsp; <?= $salary_data[0]['name']; ?> &nbsp;&nbsp;&nbsp;</u>
                </p>
            </div>
            <div class="col-md-12">
                <p class="emp_data">
                    <b>Designaton: </b> 
                    <u> &nbsp;&nbsp;&nbsp; <?= $salary_data[0]['designation']; ?> &nbsp;&nbsp;&nbsp;</u>
                </p>
            </div>    
            <div class="col-md-6 col-sm-6">
                <p class="emp_data">
                    <b>Month: </b> 
                    <u> &nbsp;&nbsp;&nbsp; <?= $month ?> &nbsp;&nbsp;&nbsp;</u>
                </p>
            </div>
            <div class="col-md-6 col-sm-6">
                <p class="emp_data">
                    <b>Year: </b> 
                    <u> &nbsp;&nbsp;&nbsp; <?= $year ?> &nbsp;&nbsp;&nbsp;</u>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                <tr>
                    <th class="text-center" colspan="2"><b>Earnings</b></th>
                </tr>
                <tr>
                    <td>Basic Salary</td>
                    <td align="right">
                        <?php
                            $basic_salary = $gross_salary*0.66;
                            $remaining_amount = $gross_salary-$basic_salary;
                        ?>
                        <input type="hidden" name="gross_salary" value="<?= $gross_salary ?>">
                        <input type="number" name="basic_salary" class="form-control earned_amount basic_salarys" value="<?php echo $basic_salary ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td ><b>Earned Salary</b></td>
                    <td align="right">
                        <input type="number" name="earned_salary" class="form-control earned_amount earned_salary" readonly>
                    </td>
                </tr>
                <tr>
                    <td>House Rent Allownce</td>
                    <td align="right">
                        <?php
                            $house_rent = $basic_salary*0.45;
                        ?>
                        <input type="number" name="house_rent_allownce" class="form-control earned_amount house_rent_allownce" value="<?php echo $house_rent ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Medical Allownce</td>
                    <td align="right">
                        <?php
                            $mdeical_allownce = $gross_salary-($house_rent+$basic_salary);
                        ?>
                        <input type="number" name="medical_allownce" class="form-control earned_amount medical_allownce" value="<?php echo $mdeical_allownce ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Total Gross Salary</td>
                    <td align="right">
                        <input type="number" class="form-control total_gross_salary" name="total_addition" readonly>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2"><b>Other Allownces</b></td>
                </tr>
                <!--Loop Start-->
                <?php
                    foreach(get_allownces() as $allownce)
                    {
                        echo '<tr>';
                        echo '<td> '. $allownce['allownce_title'] .' </td>';
                        echo '<td align="right"> 
                                <input type="hidden" name="allownce_ids[]" value="'.$allownce['allownce_id'].'">
                                <input type="number" class="form-control addition" name="allownce[]" value="'.$allownces.'">
                            </td>';
                        echo '</tr>';
                    } 
                ?>
                <!--Loop Start-->
                
                <!--Total Gross & Allownces-->
                <tr>
                    <td>Total Gross Salary & Allownces</td>
                    <td align="right">
                        <input type="number" class="form-control total_gross_salary_and_allownces" name="total_gross_salary_and_allownces" readonly>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="col-md-6">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                <tr>
                    <th class="text-center" colspan="2"><b>Deductions</b></th>
                </tr>
                <tr>
                    <td>Income Tax</td>
                    <td align="right">
                        <input type="number" name="income_tax_deduction" class="form-control income_tax_deduction deduction" value="" readonly>
                    </td>
                </tr>
                <!--Loop Start-->
                <?php
                    foreach(get_deductions() as $deduction)
                    {
                        echo '<tr>';
                        echo '<td> '. $deduction['deduction_title'] .' </td>';
                        echo '<td align="right">
                                <input type="hidden" name="deduction_ids[]" value="'.$deduction['deduction_id'].'">
                                <input type="number" class="form-control deduction" name="deductions[]" value="'.$deductions.'"> 
                            </td>';
                        echo '</tr>';
                    } 
                ?>
                <!--Loop Start-->
                <!--Total Deduction-->
                <tr>
                    <td>Total Deduction</td>
                    <td align="right">
                        <input type="number" class="form-control total_deduction" name="total_deduction" readonly>
                    </td>
                </tr>
                
                <!--Net Salary-->
                <tr>
                    <td><b>NET Salary</b></td>
                    <td align="right">
                        <input type="number" class="form-control net_salary" name="net_salary" readonly>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <button class="modal_save_btn" style="float:right;" type="submit">Save Salary Slip</button>
        </div>
    </div>
    </form>
    <?php }else{
            if(!empty($salary_exist)){
    ?>
    
        <script>
            Command: toastr["warning"]("<?php echo $salary_exist;?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
        </script>
            
        
    <?php } } ?>

<script>
    $(document).ready(function() {
        
        // Earned Salary
        var sum = 0;
        $('.earned_amount').each(function() {
            sum += Number($(this).val());
        });
        $('.earned_salary').val(sum);
        
        // Addition Sum Up
        var sum = 0;
        $('.addition').each(function() {
            sum += Number($(this).val());
        });
        $('.total_addition').val(sum);
        
        $('.addition').keyup(function () {
            var sum = 0;
            $('.addition').each(function() {
                sum += Number($(this).val());
            });
            var gs = $(".total_gross_salary").val();
            $('.total_gross_salary_and_allownces').val(Number(gs)+Number(sum)); 
            get_income_tax();
            net_salary();
        });
        
        // Total Days
        $(".total_days").on('keyup',function(){
            var days = $(this).val();
            var gross_salary = <?php echo $gross_salary; ?>;
            
            var basic_salary = (gross_salary*66)/100;
            var earned_salary = (basic_salary/30)*days;
            var new_gross_salary = (gross_salary/30)*days;
            var house_rent_allownce = earned_salary*45/100;
            var medical_allownce = new_gross_salary-(earned_salary+house_rent_allownce);
            var total_gross_salary = earned_salary+house_rent_allownce+medical_allownce;
            
            $(".basic_salarys").val(basic_salary);
            $(".earned_salary").val(earned_salary);
            $(".house_rent_allownce").val(house_rent_allownce.toFixed(0));
            $(".medical_allownce").val(medical_allownce.toFixed(0));
            $(".total_gross_salary").val(total_gross_salary.toFixed(0));
            $(".total_gross_salary_and_allownces").val(total_gross_salary.toFixed(0));
            $(".net_salary").val(total_gross_salary.toFixed(0));
            get_income_tax();
            
        });
        
        // Deduction Sum Up
        var sum = 0;
        $('.deduction').each(function() {
            sum += Number($(this).val());
        });
        $('.total_deduction').val(sum);
        
        $('.deduction').keyup(function () {
            // alert('Ok');
            var sum = 0;
            $('.deduction').each(function() {
                sum += Number($(this).val());
            });
            $('.total_deduction').val(sum);
            net_salary();
        });
        
        net_salary();
        // Net Salary
        function net_salary()
        {
            var td = Number($(".total_deduction").val());
            var ta = Number($(".total_gross_salary_and_allownces").val());
            $(".net_salary").val(ta-td);
        }
        
        // Get Income Tax
        function get_income_tax()
        {
            var total_gross = $(".total_gross_salary_and_allownces").val();
            
            $.ajax({
                url: '<?=base_url()?>payroll/get_income_tax',
                data: {total_gross:total_gross},
                method: 'POST',
                success:function(output)
                {
                    var income_tax = $(".income_tax_deduction").val(output);
                    $('.total_deduction').val(output);
                    net_salary();
                }
            });
        }
    });
</script>