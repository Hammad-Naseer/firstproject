<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Bank_detail extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
            
    }

    function bank_detail_listing()
    {
    	$bank_qur = "SELECT * FROM ".get_school_db().".bank_account where school_id = ".$_SESSION['school_id']." ";
    	$bank_arr = $this->db->query($bank_qur)->result_array();
    	$page_data['bank_arr'] = $bank_arr;
    	$page_data['page_name'] = 'bank_detail_listing';
        $page_data['page_title'] = get_phrase('bank_detail_listing');
        $this->load->view('backend/index', $page_data);
        $this->load->helper('voucher');
    }
    
    function add_edit_bank()
    {
    	$page_data['page_name'] = 'add_edit_bank';
        $page_data['page_title'] = get_phrase('add_edit_bank');
        $this->load->view('backend/index', $page_data);
    }
    
    function add_edit($param="",$id = 0)
    {
        if ($param == 'add')
        {
            $data['bank_name'] = $this->input->post('bank_name');
            $data['bank_address']=$this->input->post('bank_address');
            $data['branch_name'] = $this->input->post('branch_name');
            $data['branch_code'] = $this->input->post('branch_code');
            $data['account_title'] = $this->input->post('account_title');
            $data['account_number'] = $this->input->post('account_number');
            $data['account_type'] = $this->input->post('account_type');
            $data['iban_number'] = $this->input->post('i_ban');
            $data['status'] = $this->input->post('status');


            $data['coa_fee_cash_receipt'] = $this->input->post('coa_fee_cash_receipt');
            $data['coa_fee_check_receipt'] = $this->input->post('coa_fee_check_receipt');
            $data['coa_cash_receipt'] = $this->input->post('coa_cash_receipt');
            $data['coa_check_receipt'] = $this->input->post('coa_check_receipt');
            $data['coa_cash_payment'] = $this->input->post('coa_cash_payment');
            $data['coa_check_payment'] = $this->input->post('coa_check_payment');


            $data['description'] = $this->input->post('description');
            $data['school_id'] = $_SESSION['school_id'];

            $this->db->insert(get_school_db().'.bank_account',$data);

            $this->session->set_flashdata('club_updated',get_phrase('record_added_successfully'));
            redirect(base_url().'bank_detail/bank_detail_listing');
        }
        elseif($param == 'edit')
        {
            $data['bank_name'] = $this->input->post('bank_name');
            $data['bank_address']=$this->input->post('bank_address');
            $data['branch_name'] = $this->input->post('branch_name');
            $data['branch_code'] = $this->input->post('branch_code');
            $data['account_title'] = $this->input->post('account_title');
            $data['account_number'] = $this->input->post('account_number');
            $data['account_type'] = $this->input->post('account_type');
            $data['iban_number'] = $this->input->post('i_ban');
            $data['status'] = $this->input->post('status');


            $data['coa_fee_cash_receipt'] = $this->input->post('coa_fee_cash_receipt');
            $data['coa_fee_check_receipt'] = $this->input->post('coa_fee_check_receipt');
            $data['coa_cash_receipt'] = $this->input->post('coa_cash_receipt');
            $data['coa_check_receipt'] = $this->input->post('coa_check_receipt');
            $data['coa_cash_payment'] = $this->input->post('coa_cash_payment');
            $data['coa_check_payment'] = $this->input->post('coa_check_payment');


            $data['description'] = $this->input->post('description');
            $data['school_id'] = $_SESSION['school_id'];

            $school_id = $_SESSION['school_id'];
            $bank_account_id = str_decode($id);
            $edit_ary=array('school_id'=>$school_id,'bank_account_id'=>$bank_account_id);
            $this->db->where($edit_ary);
            $this->db->update(get_school_db().'.bank_account',$data);
            $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
            redirect(base_url().'bank_detail/bank_detail_listing');
            
        }
        elseif($param == 'delete')
        {
            $school_id = $_SESSION['school_id'];
            $bank_account_id = str_decode($id);
            $delete_ary=array('school_id'=>$school_id,'bank_account_id'=>$bank_account_id);
            $this->db->where($delete_ary);
            $this->db->delete(get_school_db().'.bank_account');
            $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));

            redirect(base_url().'bank_detail/bank_detail_listing');
        }
    }
    
    function add_cheque_book()
    {
        $data['bank_id'] = $this->input->post('bank_id');
        $data['batch_number'] = $this->input->post('batch_number');
        $data['start_cheque_number'] = $this->input->post('cheque_start_number');
        $data['end_cheque_number'] = $this->input->post('cheque_end_number');
        $data['status'] = $this->input->post('status');
        
        $this->db->insert(get_school_db().'.bank_cheque_books',$data);

        $this->session->set_flashdata('club_updated',get_phrase('cheque_book_added_successfully'));
        redirect(base_url().'bank_detail/bank_detail_listing');
    }
    
    function view_cheque_book_details($bnk_id = 0)
    {
        $bank_id = str_decode($bnk_id);
        $bank_qur = "select * from ".get_school_db().".bank_account where bank_account_id=".$bank_id." and school_id = ".$_SESSION['school_id']." ";
        $bank_arr = $this->db->query($bank_qur)->result_array();


        $bank_chequebook_qur ="select * from ".get_school_db().".bank_cheque_books where bank_id=".$bank_id." ";
        $bank_chequebook_detail_arr = $this->db->query($bank_chequebook_qur)->result_array();

        $page_data['bank_arr'] = $bank_arr;

        $page_data['bank_chequebook_detail_arr'] = $bank_chequebook_detail_arr;
        $page_data['page_name'] = 'view_cheque_book_details';
        $page_data['page_title'] = get_phrase('view_cheque_book_details');
        $this->load->view('backend/index', $page_data);
    }
    
    function edit_cheque_book()
    {
        $b_c_b_id = $this->input->post('b_c_b_id');
        
        $data['batch_number'] = $this->input->post('batch_number');
        $data['start_cheque_number'] = $this->input->post('cheque_start_number');
        $data['end_cheque_number'] = $this->input->post('cheque_end_number');
        $data['status'] = $this->input->post('status');
        
        $this->db->where('b_c_b_id' , $b_c_b_id);
        $this->db->update(get_school_db().'.bank_cheque_books',$data);

        $this->session->set_flashdata('club_updated',get_phrase('cheque_book_updated_successfully'));
        redirect(base_url().'bank_detail/bank_detail_listing');
    }
}