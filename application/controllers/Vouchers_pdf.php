<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

ini_set('memory_limit', '-1');

class Vouchers_pdf extends CI_Controller
{
    private $system_db;
    private $school_db;
    function __construct()
    {
        parent::__construct();
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        $this->load->helper('voucher');
    }
    function bank_payment_voucher_print($bank_pay_id=0)
    {
        $bank_payment_id = str_decode($bank_pay_id);
        
        $bank_payment_detail_qur ="select bp.* , bpd.* from ".get_school_db().".bank_payment bp
        INNER join ".get_school_db().".bank_payment_details bpd on bp.bank_payment_id = bpd.bank_payment_id
        where bp.bank_payment_id=".$bank_payment_id." and
        bp.school_id = ".$_SESSION['school_id']." ";
        
        $bank_payment_detail_arr = $this->db->query($bank_payment_detail_qur)->result_array();
        
        $page_data['bank_payment_detail_arr'] = $bank_payment_detail_arr;
        $page_data['page_name']               = 'bank_payment_voucher_print';
        $page_data['page_title']              = get_phrase('bank_payment_voucher_print');

        $this->load->library('Pdf');
        
        $view = 'backend/admin/bank_payment_voucher_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        /**/
        
    }
     function cash_payment_voucher_print($cash_pay_id=0)
    {
        $cash_payment_id = str_decode($cash_pay_id);
        $cash_payment_detail_qur ="select cp.* , cpd.* from ".get_school_db().".cash_payment cp
        INNER join ".get_school_db().".cash_payment_details cpd on cp.cash_payment_id = cpd.cash_payment_id
        where cp.cash_payment_id=".$cash_payment_id." and
        cp.school_id = ".$_SESSION['school_id']." ";
        $cash_payment_detail_arr = $this->db->query($cash_payment_detail_qur)->result_array();
        // echo "<pre>";
        // print_r($cash_receipt_detail_arr);

        $page_data['cash_payment_detail_arr'] = $cash_payment_detail_arr;
        $page_data['page_name']  = 'cash_payment_voucher_print';
        $page_data['page_title'] = get_phrase('cash_payment_voucher_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('Pdf');
        $view = 'backend/admin/cash_payment_voucher_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
}
