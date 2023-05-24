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

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <!--<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">-->
        <!--    <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>-->
        <!--</a>-->
        <h3 class="system_name inline">
               <?php echo get_phrase('staff_salary_Annexure'); ?>
        </h3>
    </div>
</div>

<div id="salary_div" class="row salary_layout" style="border:1px solid black;padding:5px;margin:5px;">
    <div class="col-md-12 text-center">
        <div class="col-md-12">
            <p class="emp_data">
                <b>Employee Name: </b> 
                <u> &nbsp;&nbsp;&nbsp; <?= $salary_data[0]['name']; ?> &nbsp;&nbsp;&nbsp;</u>
            </p>
        </div>
        <div class="col-md-12">
            <p class="emp_data">
                <b>Designaton: </b> 
                <u> &nbsp;&nbsp;&nbsp; <?= get_designation_name($salary_data[0]['designation_id']); ?> &nbsp;&nbsp;&nbsp;</u>
            </p>
        </div>    
        <div class="col-md-6 col-sm-6">
            <p class="emp_data">
                <b>Month: </b> 
                <u> &nbsp;&nbsp;&nbsp; <?= $salary_data[0]['month']; ?> &nbsp;&nbsp;&nbsp;</u>
            </p>
        </div>
        <div class="col-md-6 col-sm-6">
            <p class="emp_data">
                <b>Year: </b> 
                <u> &nbsp;&nbsp;&nbsp; <?= $salary_data[0]['year']; ?> &nbsp;&nbsp;&nbsp;</u>
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
                    <?= number_format($salary_data[0]['basic_salary'],2) ?>
                </td>
            </tr>
            <tr>
                <td ><b>Earned Salary</b></td>
                <td align="right">
                    <?= number_format($salary_data[0]['earned_salary'],2) ?>
                </td>
            </tr>
            <tr>
                <td>House Rent Allownce</td>
                <td align="right">
                    <?= number_format($salary_data[0]['house_rent_allownce'],2) ?>
                </td>
            </tr>
            <tr>
                <td>Medical Allownce</td>
                <td align="right">
                    <?= number_format($salary_data[0]['medical_allownce'],2) ?>
                </td>
            </tr>
            <tr>
                <td>Gross Salary</td>
                <td align="right">
                    <?php 
                        $gross_salary = $salary_data[0]['earned_salary']+$salary_data[0]['house_rent_allownce']+$salary_data[0]['medical_allownce'];
                        echo number_format($gross_salary,2);
                    ?>
                </td>
            </tr>
            <tr>
                <td class="text-center" colspan="2"><b>Allownces</b></td>
            </tr>
            <!--Loop Start-->
            <?php
                $allownce_ids = $salary_data[0]['allownce_ids'];
                $allownce_array = explode("," , $allownce_ids);
                
                // $allownce = 0;
                $total_allownce = 0;
                $s_s_s_id = str_decode($this->uri->segment(3));
                foreach($allownce_array as $allownce)
                {
                    $details = get_staff_salary_allownces_details($allownce,$s_s_s_id);
                    
                    echo '<tr><td>'.$details[0]['allownce_title'] .' </td>';
                    echo '<td> '.$details[0]['allownce_amount'] .' </td>';
                    
                    $total_allownce += $details[0]['allownce_amount'];
                    echo '</tr>';
                } 
            ?>
            <!--Loop Start-->
            <!--Total Deduction-->
            <tr>
                <td>Total Gross Salary & Allownces</td>
                <td align="right">
                    <?php 
                        $total_addition = $gross_salary + $total_allownce;
                        echo $total_addition;
                    ?>
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
                <td>
                    <?php 
                        $income_tax = $salary_data[0]['income_tax_deduction'];
                        echo number_format($income_tax); 
                    ?>
                </td>
            </tr>
            <!--Loop Start-->
            <?php
                $deduction_ids = $salary_data[0]['deduction_ids'];
                $deduction_array = explode("," , $deduction_ids);
                $deductions = 0;
                $total_deductions = 0;
                foreach($deduction_array as $deduction)
                {
                    $details = get_staff_salary_deductions_details($deduction,$s_s_s_id);
                    
                    echo '<tr><td>'.$details[0]['deduction_title'] .' </td>';
                    echo '<td> '.$details[0]['deduction_amount'] .' </td>';
                    
                    $total_deductions += $details[0]['deduction_amount'];
                    echo '</tr>';
                } 
            ?>
            <!--Loop Start-->
            <!--Total Deduction-->
            <tr>
                <td>Total Deduction</td>
                <td align="right">
                    <?php echo $total_deductions+$income_tax; ?>
                </td>
            </tr>
            
            <!--Net Salary-->
            <tr>
                <td><b>NET Salary</b></td>
                <td align="right">
                    <?php 
                        $net_amount = $salary_data[0]['net_salary'];
                        echo $net_amount;
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-12">
        <p>
            <b>Ruppes: <?php 
                $this->load->helper("num_word");
                echo convert_number_to_words($net_amount); ?> Only</b>
        </p>
    
        <div class="col-md-12">
            <p>
                <b>Date: <u>&nbsp;&nbsp; <?=date('d-m-Y');?> &nbsp;&nbsp;</u></b>
            </p>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <p> 
                <b>Signature of the Employee: <u>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;</u></b>
            </p>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <p>
                <b>Director: <u>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;</u></b>
            </p>
        </div>
        
    </div>
</div>
<button class="modal_save_btn" style="float:right;" id="print" onclick="printContent('salary_div');" >Print</button>

<script>
function printContent(el){
var restorepage = $('body').html();
var printcontent = $('#' + el).clone();
$('body').empty().html(printcontent);
window.print();
$('body').html(restorepage);
}
</script>
