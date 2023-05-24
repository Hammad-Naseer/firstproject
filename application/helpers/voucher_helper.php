<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//session_start();

function bank_receipt_number()
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $q = "select bank_receipt_number from " . get_school_db() . ".school_count where school_id =$school_id";
    $query_ary = $CI->db->query($q)->result_array();

    if (count($query_ary) > 0)
    {
    	$bank_receipt_number = $query_ary[0]['bank_receipt_number'];
    } else
    {
    	$bank_receipt_number = 0;
        $p = "INSERT INTO " . get_school_db() . ".school_count (`school_id`, `bank_receipt_number`) VALUES ('$school_id', '$bank_receipt_number')";
        $query = $CI->db->query($p);
    }
    $return_num = $bank_receipt_number + 1;
    $q_update = "update " . get_school_db() . ".school_count set  bank_receipt_number=$return_num  where school_id =$school_id and bank_receipt_number= $bank_receipt_number";
    $query_ary = $CI->db->query($q_update);
    return $return_num;
}


function cash_receipt_number()
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select cash_receipt_number from " . get_school_db() . ".school_count where school_id =$school_id";
    $query_ary = $CI->db->query($q)->result_array();
    //print_r($query_ary);

    if (count($query_ary) > 0) {
        $cash_receipt_number = $query_ary[0]['cash_receipt_number'];
    } else {

        $cash_receipt_number = 0;
        $p = "INSERT INTO " . get_school_db() . ".school_count (`school_id`, `cash_receipt_number`) VALUES ('$school_id', '$cash_receipt_number')";
        $query = $CI->db->query($p);

    }
    $return_num = $cash_receipt_number + 1;
    $q_update = "update " . get_school_db() . ".school_count set  cash_receipt_number=$return_num  where school_id =$school_id and cash_receipt_number= $cash_receipt_number";
    $query_ary = $CI->db->query($q_update);

    return $return_num;
}

function bank_payment_number()
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select bank_payment_number from " . get_school_db() . ".school_count where school_id =$school_id";
    $query_ary = $CI->db->query($q)->result_array();
    //print_r($query_ary);

    if (count($query_ary) > 0) {
        $bank_payment_number = $query_ary[0]['bank_payment_number'];
    } else {

        $bank_payment_number = 0;
        $p = "INSERT INTO " . get_school_db() . ".school_count (`school_id`, `bank_payment_number`) VALUES ('$school_id', '$bank_payment_number')";
        $query = $CI->db->query($p);

    }
    $return_num = $bank_payment_number + 1;
    $q_update = "update " . get_school_db() . ".school_count set  bank_payment_number=$return_num  where school_id =$school_id and bank_payment_number= $bank_payment_number";
    $query_ary = $CI->db->query($q_update);

    return $return_num;
}

function cash_payment_number()
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select cash_payment_number from " . get_school_db() . ".school_count where school_id =$school_id";
    $query_ary = $CI->db->query($q)->result_array();
    //print_r($query_ary);

    if (count($query_ary) > 0) {
        $cash_payment_number = $query_ary[0]['cash_payment_number'];
    } else {

        $cash_payment_number = 0;
        $p = "INSERT INTO " . get_school_db() . ".school_count (`school_id`, `cash_payment_number`) VALUES ('$school_id', '$cash_payment_number')";
        $query = $CI->db->query($p);

    }
    $return_num = $cash_payment_number + 1;
    $q_update = "update " . get_school_db() . ".school_count set  cash_payment_number=$return_num  where school_id =$school_id and cash_payment_number= $cash_payment_number";
    $query_ary = $CI->db->query($q_update);

    return $return_num;
}

function journal_voucher_number()
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select journal_voucher_number from " . get_school_db() . ".school_count where school_id =$school_id";
    $query_ary = $CI->db->query($q)->result_array();
    //print_r($query_ary);

    if (count($query_ary) > 0) {
        $journal_voucher_number = $query_ary[0]['journal_voucher_number'];
    } else {

        $journal_voucher_number = 0;
        $p = "INSERT INTO " . get_school_db() . ".school_count (`school_id`, `journal_voucher_number`) VALUES ('$school_id', '$journal_voucher_number')";
        $query = $CI->db->query($p);

    }
    $return_num = $journal_voucher_number + 1;
    $q_update = "update " . get_school_db() . ".school_count set  journal_voucher_number=$return_num  where school_id =$school_id and journal_voucher_number= $journal_voucher_number";
    $query_ary = $CI->db->query($q_update);

    return $return_num;
}

function get_supplier_list($selected=0)
{
    $CI =& get_instance();
    $CI->load->database();

    $sep_qur = "select * FROM  " . get_school_db() . ".supplier ";
    $reg_array = $CI->db->query($sep_qur)->result_array();

    $str = '';
    foreach ($reg_array as $row)
    {
        $opt_selected = "";
        if ($selected == $row['supplier_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['supplier_id'] . '" ' . $opt_selected . '>' . $row['name'] . '</option>';
    }
    return $str;
}

function get_supplier_details($supplier_id=0)
{
    $CI =& get_instance();
    $CI->load->database();

    $sep_qur = "select * FROM  " . get_school_db() . ".supplier where supplier_id = ".$supplier_id." and school_id=".$_SESSION['school_id']." ";
    $supplier_detail = $CI->db->query($sep_qur)->result_array();

    return $supplier_detail;
}

function get_depositor_list($selected=0)
{
    $CI =& get_instance();
    $CI->load->database();

    $dep_qur = "select * FROM  " . get_school_db() . ".depositor ";
    $reg_array = $CI->db->query($dep_qur)->result_array();

    $str = '';
    foreach ($reg_array as $row)
    {
        $opt_selected = "";
        if ($selected == $row['depositor_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['depositor_id'] . '" ' . $opt_selected . '>' . $row['name'] . '</option>';
    }
    return $str;
}

function get_depositor_details($depositor_id=0)
{
    $CI =& get_instance();
    $CI->load->database();

    $dep_qur = "select * FROM  " . get_school_db() . ".depositor where depositor_id = ".$depositor_id." and school_id=".$_SESSION['school_id']." ";
    $depositor_detail = $CI->db->query($dep_qur)->result_array();

    return $depositor_detail;
}

function cash_or_check($selected = 0)
{
    $reg_ary = array(
        1 => get_phrase('cash'),
        2 => get_phrase('check'),
        3 => get_phrase('pay_order'),
        4 => get_phrase('online_payment')
    );

    $str = '';
    foreach ($reg_ary as $key => $value)
    {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}
function method_check($id=0)
{
    $method_ary = array(
        1 => get_phrase('cash'),
        2 => get_phrase('check'),
        3 => get_phrase('pay_order'),
        4 => get_phrase('online_payment')
    );

    return $method_ary[$id];
}

function get_bank_list($selected=0)
{
    $CI =& get_instance();
    $CI->load->database();

    $bank_qur = "select * FROM  " . get_school_db() . ".bank_account ";
    $bank_array = $CI->db->query($bank_qur)->result_array();

    $str = '';
    foreach ($bank_array as $row)
    {
        $opt_selected = "";
        if ($selected == $row['bank_account_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['bank_account_id'] . '" ' . $opt_selected . '>' . $row['bank_name'] . ' - Acc# ' .$row['account_number']. '</option>';
    }
    return $str;
}

function get_bank_details($bank_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $bank_qur = "select * FROM  " . get_school_db() . ".bank_account where bank_account_id = ".$bank_id." and school_id=".$_SESSION['school_id']." ";
    $bank_detail = $CI->db->query($bank_qur)->result_array();
    return $bank_detail;
}

function status_list($selected = 0, $mode = 0)
{
    if($mode == 1){ //Edit Mode
        $reg_ary = array(
            1 => get_phrase('save'),
            2 => get_phrase('submit'),
            3 => get_phrase('post')
        );
    }else if($mode == 0){ //Add Mode
        $reg_ary = array(
            1 => get_phrase('save'),
            2 => get_phrase('submit')
        );
    }

    $str = '';
    foreach ($reg_ary as $key => $value)
    {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}
function status_check($id="")
{
    $reg_ary = array(
        1 => get_phrase('saved'),
        2 => get_phrase('submited'),
        3 => get_phrase('posted')
    );

    return $reg_ary[$id];
}

function multi_file_upload($file_name = "",$index = "", $folder_name = "", $prefix = "", $is_root = 0)
{

    $path = 'uploads/' . $_SESSION['folder_name'] . '/' . $folder_name;

    if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
        return "";
    } elseif ($file_name == "") {
        return "";
    } elseif ($folder_name == "") {
        $path = 'uploads/' . $_SESSION['folder_name'];
    } elseif ($is_root == 1) {
        $path = 'uploads/' . $folder_name;
    }

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    if ($_FILES[$file_name]['name'][$index] != "") {
        $filename = $_FILES[$file_name]['name'][$index];
        //print_r($_FILES);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $new_file = $prefix . '_' . time().'_'.$index . '.' . $ext;

        move_uploaded_file($_FILES[$file_name]['tmp_name'][$index], $path . '/' . $new_file);

        return $new_file;
    } else {
        return "";
    }
}

function get_cash_voucher_coa_settings($cash_status)
{
    $CI =& get_instance();
    $CI->load->database();
    $cash_voucher_qur = "select coa_id FROM  " . get_school_db() . ".cash_voucher_settings where cash_voucher_type = '$cash_status' and school_id = ".$_SESSION['school_id']." ";
    $cash_voucher_qur_detail = $CI->db->query($cash_voucher_qur)->row();
    return $cash_voucher_qur_detail->coa_id;
}

function get_supplier_purchase_voucher($selected = 0,$supplier_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $purchase_voucher_qur = "select pv.* from ".get_school_db().".purchase_voucher pv where pv.supplier_id=".$supplier_id." and pv.school_id = ".$_SESSION['school_id']." AND pv.payment_status = 0 ";
    $purchase_voucher_array = $CI->db->query($purchase_voucher_qur)->result_array();

    $str = '';
    foreach ($purchase_voucher_array as $row)
    {
        $purchase_voucher_amount = "select SUM(pvd.amount) AS TotalAmount from ".get_school_db().".purchase_voucher_details pvd where pvd.purchase_voucher_id=".$row["purchase_voucher_id"]." ";
        $purchase_voucher_amount_arr = $CI->db->query($purchase_voucher_amount)->row();
        $opt_selected = "";
        if ($selected == $row['purchase_voucher_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option '.$opt_selected.' value="'.$row["purchase_voucher_id"].'"> Voucher No#: '.$row["voucher_number"].' - Voucher Date: '.date_view($row["voucher_date"]).' - Total Amount: '.$purchase_voucher_amount_arr->TotalAmount.'
                </option>';
    }
    return $str;
}

function get_coa_name($coa_id = 0)
{
    $CI=& get_instance();
    $CI->load->database();
    $coa_head = $CI->db->query("SELECT account_number , account_head FROM ".get_school_db().".chart_of_accounts WHERE school_id = ".$_SESSION['school_id']." AND coa_id = ".$coa_id." ")->row();
    return $coa_head->account_head.'-'.$coa_head->account_number;
}

function get_pv_vouche_number($pv_id = 0)
{
    $CI=& get_instance();
    $CI->load->database();
    $purchase_voucher_qur = "select voucher_number from ".get_school_db().".purchase_voucher pv where pv.purchase_voucher_id=".$pv_id." and pv.school_id = ".$_SESSION['school_id']." AND pv.payment_status = 0 ";
    $purchase_voucher_array = $CI->db->query($purchase_voucher_qur)->row();
    return $purchase_voucher_array->voucher_number;
}

function get_coa_cacl_balance($coa_id = 0)
{
    $CI=& get_instance();
    $CI->load->database();
    $coa_head = $CI->db->query("SELECT SUM(debit) AS TD , SUM(credit) AS TC FROM ".get_school_db().".journal_entry WHERE school_id = ".$_SESSION['school_id']." AND coa_id = ".$coa_id." ")->row();
    
    $balance = $coa_head->TD - $coa_head->TC;
    if($balance == "" || empty($balance))
    {
        $balance = 0;
    }
    return $balance;
}

function voucher_type_bpv($type = 0)
{
    $arr = array(
        '1' => "Supplier Payment",
        '2' => "Other Payment"
    );
    
    return $arr[$type];
}

function voucher_type_brv($type = 0)
{
    $arr = array(
        '1' => "Depositer Receipts",
        '2' => "Other Receipts"
    );
    
    return $arr[$type];
}

function journal_entry_type($id=0)
{
    $Arr = array(
        '1' =>  "Fee Issue",
        '2' =>  "Fee Cancel",
        '3' =>  "Fee Recieve",
        '4' =>  "Late Fee Fine",
        '11' =>  "Bank Receipt Voucher",
        '12' =>  "Bank Receipt Voucher",
        '13' =>  "Bank Payment Voucher",
        '14' =>  "Cash Payment Voucher",
        '15' =>  "Cash Receipt Voucher",
        '16' =>  "Cash Receipt Voucher",
        '17' =>  "Cash Payment Voucher",
        '18' =>  "Purchase Voucher",
        '19' =>  "Purchase Voucher",
        '20' =>  "Tax Entry",
        '21' =>  "Journal Entry",
        '22' =>  "Salary Posting Voucher",
    );
    
    return $Arr[$id];
}