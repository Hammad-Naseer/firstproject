<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function allownces_option_list($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $qur = "select * FROM  " . get_system_db() . ".allownces where status = 1 ";
    $reg_array = $CI->db->query($qur)->result_array();

    $str = '<option value="">' . get_phrase('select_allownce') . '</option>';

    foreach ($reg_array as $row) {
        $opt_selected = "";
        if ($selected == $row['allownce_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['allownce_id'] . '" ' . $opt_selected . '>' . $row['allownce_title'] . '</option>';
    }
    return $str;
}

function get_allownces()
{
    $CI =& get_instance();
    $CI->load->database(); 
    $allownces = $CI->db->query("select * from " . get_school_db() . ".allownces where school_id = ".$_SESSION['school_id']." and  status = 1")->result_array();
    
    return  $allownces;
}

function deductions_option_list($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $qur = "select * FROM  " . get_system_db() . ".deductions where status = 1 ";
    $reg_array = $CI->db->query($qur)->result_array();

    $str = '<option value="">' . get_phrase('select_deduction') . '</option>';

    foreach ($reg_array as $row) {
        $opt_selected = "";
        if ($selected == $row['allownce_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['deduction_id'] . '" ' . $opt_selected . '>' . $row['deduction_title'] . '</option>';
    }
    return $str;
}

function get_deductions()
{
    $CI =& get_instance();
    $CI->load->database(); 
    $deductions = $CI->db->query("select * from " . get_school_db() . ".deductions where school_id = ".$_SESSION['school_id']." and  status = 1")->result_array();
    return   $deductions;
}

function get_staff_salary_allownces_details($allownce_id = 0,$s_s_s_id = 0)
{
    
    $CI =& get_instance();
    $CI->load->database(); 
    $allownce = $CI->db->query("select ssa.* , a.* from " . get_school_db() . ".allownces a 
    inner join " . get_school_db() . ".staff_salary_allownces ssa on ssa.allownce_id = a.allownce_id
    where ssa.allownce_id = ".$allownce_id." AND ssa.s_s_s_id = ".$s_s_s_id." ")->result_array();
    
    return  $allownce;
}
function get_staff_salary_deductions_details($deduction_id = 0,$s_s_s_id = 0)
{
    
    $CI =& get_instance();
    $CI->load->database(); 
    $deduction = $CI->db->query("select ssd.* , d.* from " . get_school_db() . ".deductions d 
    inner join " . get_school_db() . ".staff_salary_deductions ssd on ssd.deduction_id = d.deduction_id
    where ssd.deduction_id = ".$deduction_id." AND ssd.s_s_s_id = ".$s_s_s_id." ")->result_array();
    
    return  $deduction;
}

function income_tax_deduction($salary)
{
    $yearly_income = $salary*12;
    $output = 0;
    if($yearly_income >= 600001 && $yearly_income <= 1200000)
    {
        $taxable_amount = $yearly_income-600000;
        $get_yearly_tax = $taxable_amount*5/100;
        $sum_fixed_amount = $get_yearly_tax;
        $get_monthly_tax = $sum_fixed_amount/12;
        $output = $get_monthly_tax;
    }else if($yearly_income >= 1200001 && $yearly_income <= 1800000)
    {
        $taxable_amount = $yearly_income-1200000;
        $get_yearly_tax = $taxable_amount*10/100;
        $sum_fixed_amount = $get_yearly_tax+30000;
        $get_monthly_tax = $sum_fixed_amount/12;
        $output = $get_monthly_tax;
    }else if($yearly_income >= 1800001 && $yearly_income <= 2500000)
    {
        $taxable_amount = $yearly_income-1800000;
        $get_yearly_tax = $taxable_amount*15/100;
        $sum_fixed_amount = $get_yearly_tax+90000;
        $get_monthly_tax = $sum_fixed_amount/12;
        $output = $get_monthly_tax;
    }
    
    return $output;
}

function get_salary_setting_coa($salary_type="")
{
    $CI =& get_instance();
    $CI->load->database(); 
    
    $qur = "select salary_type , coa_id from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type= '$salary_type' ";
    $query = $CI->db->query($qur);
    if ($query->num_rows() > 0)
    {
        return $query->row()->coa_id;
    }
    
}

function get_deduction_coa_id($deduction_id = 0)
{
    $CI =& get_instance();
    $CI->load->database(); 
    $deduction = $CI->db->query("select credit_coa_id from " . get_school_db() . ".deductions where school_id = ".$_SESSION['school_id']." and  deduction_id = $deduction_id")->row();
    return   $deduction->credit_coa_id;
}































