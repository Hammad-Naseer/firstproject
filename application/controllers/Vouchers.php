<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

ini_set('memory_limit', '-1');

class Vouchers extends CI_Controller
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
    //____________________________________________________
    //______________bank_receipt_voucher__________________
    //____________________________________________________
    function bank_receipt_voucher_listing()
    {
        //_____________filters work start_______________
        $apply_filter=$this->input->post('apply_filter');
        $search=$this->input->post('search');
        $depositor_id=$this->input->post('depositor_id');
        $status=$this->input->post('status');
        $start_date=$this->input->post('start_date');
        $end_date=$this->input->post('end_date');
        $method=$this->input->post('method');
        $bank_id=$this->input->post('bank_id');

        $search_filter="";
        if ( isset($search) && $search != "")
        {
            $search_filter =" AND (
            br.voucher_number LIKE '%".$search."%' OR
            brd.method LIKE '%".$search."%' OR
            brd.deposit_slip_number LIKE '%".$search."%' OR
            brd.description LIKE '%".$search."%' OR
            brd.amount LIKE '%".$search."%'
            )";
        }
        $depositor_filter="";
        if (isset($depositor_id) && $depositor_id != "")
        {
            $depositor_filter = " And br.depositor_id = ".$depositor_id."";
        }
        $status_filter="";
        if (isset($status) && $status != "")
        {
            $status_filter = " And br.status = ".$status."";
        }
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $start_date = date_slash($start_date);
            $end_date = date_slash($end_date);
            $date_filter = " And br.voucher_date BETWEEN '".$start_date."' And '".$end_date."'";
        }
        $method_filter="";
        if (isset($method) && $method != "")
        {
            $method_filter = " And brd.method = ".$method."";
        }
        $bank_filter="";
        if (isset($bank_id) && $bank_id != "")
        {
            $method_filter = " And brd.deposit_bank_id = ".$bank_id."";
        }
        //______________filters work end________________

        //_____________pagination work start____________
       $filter_query = $search_filter." ".$depositor_filter." ".$status_filter." ".$date_filter." ".$method_filter." ".$bank_filter;
        $per_page = 20;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 4;
        $config['num_links'] = 10;
        $config['use_page_numbers'] = TRUE;

        $page_num = $this->uri->segment(4);
        if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }
        
        $qry_count = "SELECT count(br.bank_receipt_id) as total_rows FROM ".get_school_db().".bank_receipt br 
        INNER join ".get_school_db().".bank_receipt_details brd
        on br.bank_receipt_id = brd.bank_receipt_id
        WHERE br.school_id = ".$_SESSION['school_id']."
        ".$filter_query." group by br.bank_receipt_id ORDER BY br.bank_receipt_id desc";
        

        $total_rows = $this->db->query($qry_count)->num_rows();
        $config['total_rows'] = $total_rows;  //$total_rows->total;

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $page_data['total_records'] = $total_rows;
        //_____________pagination work ends_______________

        $qry ="SELECT br.* , sum(brd.amount) as total FROM " . get_school_db() . ".bank_receipt br
        inner join " . get_school_db() . ".bank_receipt_details
        brd on br.bank_receipt_id = brd.bank_receipt_id 
        WHERE br.school_id = ".$_SESSION['school_id']." 
        ".$search_filter." ".$depositor_filter."
        ".$status_filter." ".$date_filter." ".$method_filter." ".$bank_filter."
        group by br.bank_receipt_id";

        $data = $this->db->query($qry)->result_array();

        $page_data['apply_filter'] =$apply_filter;
        $page_data['search'] =$search;
        $page_data['depositor_id'] =$depositor_id;
        $page_data['status'] =$status;
        $page_data['start_date'] =$start_date;
        $page_data['end_date'] =$end_date;
        $page_data['method'] =$method;
        $page_data['bank_id'] =$bank_id;

        $page_data['data'] = $data;
        $page_data['page_name'] = 'bank_receipt_voucher_listing';
        $page_data['page_title'] = get_phrase('bank_receipt_voucher_listing');
        $this->load->view('backend/index', $page_data);
    }
    function bank_receipt_voucher()
    {
        $page_data['page_name'] = 'bank_receipt_voucher';
        $page_data['page_title'] = get_phrase('bank_receipt_voucher');
        $this->load->view('backend/index', $page_data);
    }
    function add_bank_receipt_voucher()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $data['method'] = $this->input->post('method');
        $counter = count($data['method']);

        $data1['voucher_number']=bank_receipt_number();
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['depositor_id']=$this->input->post('depositor_id');
        $data1['voucher_type']=1;
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        $data1['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.bank_receipt',$data1);

        $insert_id = $this->db->insert_id();
        //_____________bank_receipt_details__________
        $method = $this->input->post('method');
        $deposit_slip_number = $this->input->post('deposit_slip_number');
        $description = $this->input->post('description');
        $deposit_bank_id = $this->input->post('deposit_bank_id');
        $amount = $this->input->post('amount');
        $total_amount = 0;

        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($method[$i]) && !empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['bank_receipt_id']=$insert_id;
                $data2['method']=$method[$i];
                $data2['deposit_slip_number']=$deposit_slip_number[$i];
                $data2['description']=$description[$i];
                $data2['deposit_bank_id']=$deposit_bank_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'bank_receipt', 'brv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.bank_receipt_details',$data2);
            }
        }
        //__________________________________________________
        //__________________________________________________

        //_____________journal_entry________________________
        if ($status==3)
        {
            $depositor_id = $this->input->post('depositor_id');
            $depositor_detail = get_depositor_details($depositor_id);
            $coa_bank_deposit = $depositor_detail[0]['coa_bank_deposit'];
            if ($coa_bank_deposit>0)
            {
                $coa_detail = array();
                for ($i=0; $i < $counter ; $i++)
                {
                    if (!empty($deposit_bank_id[$i]))
                    {
                        $bank_details = get_bank_details($deposit_bank_id[$i]);
                        if ($method[$i]==1)
                        {
                            $coa_detail[] = $bank_details[0]['coa_cash_receipt'];
                        }
                        elseif ($method[$i]==2)
                        {
                            $coa_detail[] = $bank_details[0]['coa_check_receipt'];
                        }else{
                            $coa_detail[] = 0;
                        }
                    }
                } 

                if (!in_array(0, $coa_detail))
                {
                    //_______details entries in ledger_____
                    for ($i=0; $i < $counter ; $i++)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $description[$i];
                        $data3['debit'] = $amount[$i];
                        $total_amount+=$amount[$i];
                        $data3['credit'] = 0;
                        $data3['entry_type'] = 11;
                        $data3['type_id'] = $insert_id;
                        if (!empty($deposit_bank_id[$i]))
                        {
                            $bank_details = get_bank_details($deposit_bank_id[$i]);
                        }
                        if ($method[$i]==1)
                        {
                            $data3['coa_id'] = $bank_details[0]['coa_cash_receipt'];
                        }
                        elseif ($method[$i]==2)
                        {
                             $data3['coa_id'] = $bank_details[0]['coa_check_receipt'];
                        }
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                    }
                    //_______main entry in ledger_____
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    //get_pharase();
                    $data4['detail'] = get_phrase('voucher_number').": ".$data1['voucher_number']." ".get_phrase('depositor_name')." : ".$depositor_detail[0]['name'];
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 11;
                    $data4['type_id'] = $insert_id;
                    $data4['coa_id'] = $coa_bank_deposit;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);

                    //________update bank_receipt________
                    $data1['status']= $status;
                    $data1['submitted_by']=$_SESSION['login_detail_id'];
                    $data1['date_submitted']=date('Y-m-d'); 
                    $data1['posted_by']=$_SESSION['login_detail_id'];
                    $data1['date_posted']=date('Y-m-d');
                    $this->db->where('bank_receipt_id', $insert_id);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.bank_receipt',$data1);
                }
                else
                {
                    $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_depositor_coa_is_missing'));
            }
        }
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }
        redirect(base_url().'vouchers/bank_receipt_voucher_listing');
    }
    function add_other_bank_receipt_voucher()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $data['method'] = $this->input->post('method');
        $counter = count($data['method']);

        $data1['voucher_number']=bank_receipt_number();
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['credit_coa_id']=$this->input->post('credit_coa_id');
        $data1['voucher_type']=2;
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        $data1['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.bank_receipt',$data1);

        $insert_id = $this->db->insert_id();
        //_____________bank_receipt_details__________
        $method = $this->input->post('method');
        $deposit_slip_number = $this->input->post('deposit_slip_number');
        $description = $this->input->post('description');
        $deposit_bank_id = $this->input->post('deposit_bank_id');
        $amount = $this->input->post('amount');
        $total_amount = 0;

        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($method[$i]) && !empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['bank_receipt_id']=$insert_id;
                $data2['method']=$method[$i];
                $data2['deposit_slip_number']=$deposit_slip_number[$i];
                $data2['description']=$description[$i];
                $data2['deposit_bank_id']=$deposit_bank_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'bank_receipt', 'brv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.bank_receipt_details',$data2);
            }
        }

        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }
        redirect(base_url().'vouchers/bank_receipt_voucher_listing');
    }
    function check_bank_receipt_voucher_coa()
    {
        $depositor_id = $this->input->post('depositor_id');
        $depositor_detail = get_depositor_details($depositor_id);
        $coa_bank_deposit = $depositor_detail[0]['coa_bank_deposit'];
        if ($coa_bank_deposit>0)
        {
            $data['method'] = $this->input->post('method');
            $counter = count($data['method']);

            $method = $this->input->post('method');
            $deposit_slip_number = $this->input->post('deposit_slip_number');
            $description = $this->input->post('description');
            $deposit_bank_id = $this->input->post('deposit_bank_id');
            $amount = $this->input->post('amount');

            $bank_coa_detail = array();
            for ($i=0; $i < $counter ; $i++)
            {
                if (!empty($method[$i]) && !empty($description[$i]) && !empty($amount[$i]))
                {
                    $bank_details = get_bank_details($deposit_bank_id[$i]);
                    if ($method[$i]==1)
                    {
                        $bank_coa_detail[] = $bank_details[0]['coa_cash_receipt'];
                    }
                    elseif ($method[$i]==2)
                    {
                        $bank_coa_detail[] = $bank_details[0]['coa_check_receipt'];
                    }
                }
            }
            if (!in_array('0', $bank_coa_detail))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    function bank_receipt_voucher_detail($bank_rec_id=0)
    {
        $bank_receipt_id = str_decode($bank_rec_id);
        
        //_____________pagination work start_______________
        $filter_query = "";
        $per_page = 15;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 4;
        $config['num_links'] = 10;
        $config['use_page_numbers'] = TRUE;

        $page_num = $this->uri->segment(4);
        if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }

        $qry_count = "SELECT count(*) as total_rows FROM " . get_school_db() . ".bank_receipt_details WHERE 
        bank_receipt_id =".$bank_receipt_id." and school_id = " .
        $_SESSION['school_id'] . $filter_query . "
        ORDER BY bank_receipt_details_id desc";

        $total_rows = $this->db->query($qry_count)->row();
        $total_rows = $total_rows->total_rows;  //$config['total_rows'];
        $config['total_rows'] = $total_rows;  //$total_rows->total;

        $qry = "SELECT * FROM " . get_school_db() . ".bank_receipt_details WHERE bank_receipt_id =".$bank_receipt_id." and school_id = " . $_SESSION['school_id'] .
        $filter_query . " ORDER BY bank_receipt_details_id desc  limit " .
        $start_limit . "," . $per_page . "";

        $res_array = $this->db->query($qry)->result_array();
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $page_data['total_records'] = $total_rows;
        //_____________pagination work ends_______________

        $bank_receipt_qur = "select * from ".get_school_db().".bank_receipt where bank_receipt_id=".$bank_receipt_id." and school_id = ".$_SESSION['school_id']." ";
        $bank_receipt_arr = $this->db->query($bank_receipt_qur)->result_array();


        $bank_receipt_detail_qur ="select * from ".get_school_db().".bank_receipt_details where bank_receipt_id=".$bank_receipt_id." and school_id = ".$_SESSION['school_id']." ";
        $bank_receipt_detail_arr = $this->db->query($bank_receipt_detail_qur)->result_array();

        $page_data['bank_receipt_arr'] = $bank_receipt_arr;

        $page_data['bank_receipt_detail_arr'] = $bank_receipt_detail_arr;
        $page_data['page_name'] = 'bank_receipt_voucher_detail';
        $page_data['page_title'] = get_phrase('bank_receipt_voucher_detail');
        $this->load->view('backend/index', $page_data);
    }
    function bank_receipt_voucher_print($bank_rec_id=0)
    {
        $bank_receipt_id = str_decode($bank_rec_id);
        $bank_receipt_detail_qur ="select br.* , brd.* from ".get_school_db().".bank_receipt br
        INNER join ".get_school_db().".bank_receipt_details brd on br.bank_receipt_id = brd.bank_receipt_id
        where br.bank_receipt_id=".$bank_receipt_id." and
        br.school_id = ".$_SESSION['school_id']." ";
        
        $bank_receipt_detail_arr = $this->db->query($bank_receipt_detail_qur)->result_array();
        
        $page_data['bank_receipt_detail_arr'] = $bank_receipt_detail_arr;
        $page_data['page_name']               = 'bank_receipt_voucher_print';
        $page_data['page_title']              = get_phrase('bank_receipt_voucher_print');

        $this->load->library('Pdf');
        
        $view = 'backend/admin/bank_receipt_voucher_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        /**/
        
    }
    function bank_receipt_voucher_delete($bank_rec_id=0)
    {
        $bank_receipt_id = str_decode($bank_rec_id);
        $bank_receipt_delete_qur ="select * from ".get_school_db().".bank_receipt_details WHERE bank_receipt_id=".$bank_receipt_id." and attachment <> '' and school_id = ".$_SESSION['school_id']." ";
        $row_delete = $this->db->query($bank_receipt_delete_qur)->result_array();
        foreach ($row_delete as $key => $value)
        {
            if (isset($value['attachment']))
            {
                $del_location = system_path($value['attachment'], 'bank_receipt');
                file_delete($del_location);
            }
        }
        
        $this->db->where('bank_receipt_id', $bank_receipt_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.bank_receipt_details');

        $this->db->where('bank_receipt_id', $bank_receipt_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.bank_receipt');

        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
        redirect(base_url() . 'vouchers/bank_receipt_voucher_listing');
        exit();
    }
    function bank_receipt_voucher_edit($bank_rec_id=0)
    {
        $bank_receipt_id = str_decode($bank_rec_id);
        $bank_receipt_edit_qur ="select br.* , brd.* from ".get_school_db().".bank_receipt br inner join ".get_school_db().".bank_receipt_details brd on br.bank_receipt_id = brd.bank_receipt_id WHERE br.bank_receipt_id=".$bank_receipt_id." and br.school_id = ".$_SESSION['school_id']." ";
        $row_edit = $this->db->query($bank_receipt_edit_qur)->result_array();
        $page_data['row_edit'] = $row_edit;

        $page_data['page_name'] = 'bank_receipt_voucher_edit';
        $page_data['page_title'] = get_phrase('bank_receipt_voucher_edit');
        $this->load->view('backend/index', $page_data);
    }
    function bank_receipt_voucher_update()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $voucher_number = $this->input->post('voucher_number');
        // $method = $this->input->post('method');
        // $data['method'] = array_filter($method, 'strlen');
        // $counter = count($data['method']);
        $data['method'] = $this->input->post('method');
        $counter = count($data['method']);
        
        $old_status = $this->input->post('old_status');

        //____get existing bank_receipt_details_id's before update_
        $bank_receipt_id = $this->input->post('bank_receipt_id');

        $qur = "select bank_receipt_details_id from ".get_school_db().".bank_receipt_details where bank_receipt_id = ".$bank_receipt_id." and school_id = ".$_SESSION['school_id']." ";

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['bank_receipt_details_id'];
        }

        //_______________bank_receipt_______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['depositor_id']=$this->input->post('depositor_id');
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        $school_id = $_SESSION['school_id'];
        $this->db->where('bank_receipt_id', $bank_receipt_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.bank_receipt',$data1);
        
        //_______________bank_receipt_details_______________
        $bank_receipt_details_id_arr = $this->input->post('bank_receipt_details_id');
        $method = $this->input->post('method');
        $deposit_slip_number = $this->input->post('deposit_slip_number');
        $description = $this->input->post('description');
        $deposit_bank_id = $this->input->post('deposit_bank_id');
        $amount = $this->input->post('amount');

        $filename = $_FILES['attachment'];

        $details_id_arr_second = array();
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($method[$i]) && !empty($description[$i]) && !empty($amount[$i]))
            {
                $bank_receipt_details_id = $bank_receipt_details_id_arr[$i];
                
                $data2['bank_receipt_id']=$bank_receipt_id;
                $data2['method']=$method[$i];
                $data2['deposit_slip_number']=$deposit_slip_number[$i];
                $data2['description']=$description[$i];
                $data2['deposit_bank_id']=$deposit_bank_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'bank_receipt', 'brv');
                }
                if (!empty($bank_receipt_details_id))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('bank_receipt_details_id', $bank_receipt_details_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.bank_receipt_details',$data2);
                    $details_id_arr_second[] = $bank_receipt_details_id;
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.bank_receipt_details',$data2);
                    $new_bank_receipt_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_bank_receipt_details_id; 
                }
            }
        }
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        foreach ($result as $key => $value)
        {
            $this->db->where('bank_receipt_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.bank_receipt_details');
        }
        //_____________journal_entry________________________
        if ($status==3)
        {
            $depositor_id = $this->input->post('depositor_id');
            $depositor_detail = get_depositor_details($depositor_id);
            $coa_bank_deposit = $depositor_detail[0]['coa_bank_deposit'];
            if ($coa_bank_deposit>0)
            {
                $coa_detail = array();
                for ($i=0; $i < $counter ; $i++)
                {
                    if (!empty($deposit_bank_id[$i]))
                    {
                        $bank_details = get_bank_details($deposit_bank_id[$i]);
                        if ($method[$i]==1)
                        {
                            $coa_detail[] = $bank_details[0]['coa_cash_receipt'];
                        }
                        elseif ($method[$i]==2)
                        {
                            $coa_detail[] = $bank_details[0]['coa_check_receipt'];
                        }else{
                            $coa_detail[] = 0;
                        }
                    
                    }
                }
                if (!in_array(0, $coa_detail))
                {
                    //_______details entries in ledger_____
                    for ($i=0; $i < $counter ; $i++)
                    {
                        if (!empty($deposit_bank_id[$i]))
                        {
                            $data3['school_id'] = $_SESSION['school_id'];
                            $data3['entry_date'] = date('Y-m-d H:i:s');
                            $data3['detail'] = $description[$i];
                            $data3['debit'] = $amount[$i];
                            $total_amount+=$amount[$i];
                            $data3['credit'] = 0;
                            $data3['entry_type'] = 12;
                            $data3['type_id'] = $bank_receipt_id;
                            $bank_details = get_bank_details($deposit_bank_id[$i]);
                            if ($method[$i]==1)
                            {
                                $data3['coa_id'] = $bank_details[0]['coa_cash_receipt'];
                            }
                            elseif ($method[$i]==2)
                            {
                                 $data3['coa_id'] = $bank_details[0]['coa_check_receipt'];
                            }
                            $this->db->insert(get_school_db().'.journal_entry',$data3);
                        }
                    }
                    //_______main entry in ledger_____
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    //get_pharase();
                    $data4['detail'] = get_phrase('voucher_number').": ".$voucher_number." ".get_phrase('depositor_name')." : ".$depositor_detail[0]['name'];
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 12;
                    //12 is for update in journal_entry
                    $data4['type_id'] = $bank_receipt_id;
                    $data4['coa_id'] = $coa_bank_deposit;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);

                    //________update bank_receipt________
                    if ($old_status=='1')
                    {
                        $data5['submitted_by']=$_SESSION['login_detail_id'];
                        $data5['date_submitted']=date('Y-m-d');
                    }
                    $data5['status']= $status;
                    $data5['posted_by']=$_SESSION['login_detail_id'];
                    $data5['date_posted']=date('Y-m-d');
                    $this->db->where('bank_receipt_id', $bank_receipt_id);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.bank_receipt',$data5);
                }
                else
                {
                    $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_depositor_coa_is_missing'));
            }
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }

        //$this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
        redirect(base_url() . 'vouchers/bank_receipt_voucher_listing');
    }
    function other_bank_receipt_voucher_update()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $voucher_number = $this->input->post('voucher_number');
        // $method = $this->input->post('method');
        // $data['method'] = array_filter($method, 'strlen');
        // $counter = count($data['method']);
        $data['method'] = $this->input->post('method');
        $counter = count($data['method']);
        
        $old_status = $this->input->post('old_status');

        //____get existing bank_receipt_details_id's before update_
        $bank_receipt_id = $this->input->post('bank_receipt_id');

        $qur = "select bank_receipt_details_id from ".get_school_db().".bank_receipt_details where bank_receipt_id = ".$bank_receipt_id." and school_id = ".$_SESSION['school_id']." ";

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['bank_receipt_details_id'];
        }

        //_______________bank_receipt_______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['credit_coa_id']=$this->input->post('credit_coa_id');
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        $school_id = $_SESSION['school_id'];
        $this->db->where('bank_receipt_id', $bank_receipt_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.bank_receipt',$data1);
        
        //_______________bank_receipt_details_______________
        $bank_receipt_details_id_arr = $this->input->post('bank_receipt_details_id');
        $method = $this->input->post('method');
        $deposit_slip_number = $this->input->post('deposit_slip_number');
        $description = $this->input->post('description');
        $deposit_bank_id = $this->input->post('deposit_bank_id');
        $amount = $this->input->post('amount');

        $filename = $_FILES['attachment'];

        $details_id_arr_second = array();
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($method[$i]) && !empty($description[$i]) && !empty($amount[$i]))
            {
                $bank_receipt_details_id = $bank_receipt_details_id_arr[$i];
                
                $data2['bank_receipt_id']=$bank_receipt_id;
                $data2['method']=$method[$i];
                $data2['deposit_slip_number']=$deposit_slip_number[$i];
                $data2['description']=$description[$i];
                $data2['deposit_bank_id']=$deposit_bank_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'bank_receipt', 'brv');
                }
                if (!empty($bank_receipt_details_id))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('bank_receipt_details_id', $bank_receipt_details_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.bank_receipt_details',$data2);
                    $details_id_arr_second[] = $bank_receipt_details_id;
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.bank_receipt_details',$data2);
                    $new_bank_receipt_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_bank_receipt_details_id; 
                }
            }
        }
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        foreach ($result as $key => $value)
        {
            $this->db->where('bank_receipt_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.bank_receipt_details');
        }
        //_____________journal_entry________________________
        if ($status==3)
        {
            $credit_coa_id = $this->input->post('credit_coa_id');
            // $depositor_detail = get_depositor_details($depositor_id);
            // $coa_bank_deposit = $depositor_detail[0]['coa_bank_deposit'];
            if ($credit_coa_id > 0)
            {
                $coa_detail = array();
                for ($i=0; $i < $counter ; $i++)
                {
                    if (!empty($deposit_bank_id[$i]))
                    {
                        $bank_details = get_bank_details($deposit_bank_id[$i]);
                        if ($method[$i]==1)
                        {
                            $coa_detail[] = $bank_details[0]['coa_cash_receipt'];
                        }
                        elseif ($method[$i]==2)
                        {
                            $coa_detail[] = $bank_details[0]['coa_check_receipt'];
                        }else{
                            $coa_detail[] = 0;
                        }
                    
                    }
                }
                if (!in_array(0, $coa_detail))
                {
                    //_______details entries in ledger_____
                    for ($i=0; $i < $counter ; $i++)
                    {
                        if (!empty($deposit_bank_id[$i]))
                        {
                            $data3['school_id'] = $_SESSION['school_id'];
                            $data3['entry_date'] = date('Y-m-d H:i:s');
                            $data3['detail'] = $description[$i];
                            $data3['debit'] = $amount[$i];
                            $total_amount+=$amount[$i];
                            $data3['credit'] = 0;
                            $data3['entry_type'] = 12;
                            $data3['type_id'] = $bank_receipt_id;
                            $bank_details = get_bank_details($deposit_bank_id[$i]);
                            if ($method[$i]==1)
                            {
                                $data3['coa_id'] = $bank_details[0]['coa_cash_receipt'];
                            }
                            elseif ($method[$i]==2)
                            {
                                 $data3['coa_id'] = $bank_details[0]['coa_check_receipt'];
                            }
                            $this->db->insert(get_school_db().'.journal_entry',$data3);
                        }
                    }
                    //_______main entry in ledger_____
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = get_phrase('voucher_number').": ".$voucher_number." - ".get_phrase('account')." : ".get_coa_name($credit_coa_id);
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 12;
                    $data4['type_id'] = $bank_receipt_id;
                    $data4['coa_id'] = $credit_coa_id;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);

                    //________update bank_receipt________
                    if ($old_status=='1')
                    {
                        $data5['submitted_by']=$_SESSION['login_detail_id'];
                        $data5['date_submitted']=date('Y-m-d');
                    }
                    $data5['status']= $status;
                    $data5['posted_by']=$_SESSION['login_detail_id'];
                    $data5['date_posted']=date('Y-m-d');
                    $this->db->where('bank_receipt_id', $bank_receipt_id);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.bank_receipt',$data5);
                }
                else
                {
                    $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_depositor_coa_is_missing'));
            }
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }

        //$this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
        redirect(base_url() . 'vouchers/bank_receipt_voucher_listing');
    }
    //____________________________________________________
    //____________________________________________________
    //______________cash_receipt_voucher__________________
    //____________________________________________________
    function cash_receipt_voucher_listing()
    {
        //_____________filters work start_______________
        $apply_filter=$this->input->post('apply_filter');
        $search=$this->input->post('search');
        $depositor_id=$this->input->post('depositor_id');
        $status=$this->input->post('status');
        $start_date=$this->input->post('start_date');
        $end_date=$this->input->post('end_date');

        $search_filter="";
        if ( isset($search) && $search != "")
        {
            $search_filter =" AND (
            cr.voucher_number LIKE '%".$search."%' OR
            crd.description LIKE '%".$search."%' OR
            crd.amount LIKE '%".$search."%'
            )";
        }
        $depositor_filter="";
        if (isset($depositor_id) && $depositor_id != "")
        {
            $depositor_filter = " And cr.depositor_id = ".$depositor_id."";
        }
        $status_filter="";
        if (isset($status) && $status != "")
        {
            $status_filter = " And cr.status = ".$status."";
        }
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $start_date = date_slash($start_date);
            $end_date = date_slash($end_date);
            $date_filter = " And cr.voucher_date BETWEEN '".$start_date."' And '".$end_date."'";
        }
        //______________filters work end________________

        $cash_receipt_qry ="SELECT cr.* , sum(crd.amount) as total FROM " . get_school_db() . ".cash_receipt cr
        inner join " . get_school_db() . ".cash_receipt_details
        crd on cr.cash_receipt_id = crd.cash_receipt_id 
        WHERE cr.school_id = ".$_SESSION['school_id']." 
        ".$search_filter." ".$depositor_filter."
        ".$status_filter." ".$date_filter."
        group by cr.cash_receipt_id";

        $data = $this->db->query($cash_receipt_qry)->result_array();

        $page_data['apply_filter'] =$apply_filter;
        $page_data['search'] =$search;
        $page_data['depositor_id'] =$depositor_id;
        $page_data['status'] =$status;
        $page_data['start_date'] =$start_date;
        $page_data['end_date'] =$end_date;

        $page_data['data'] = $data;
        $page_data['page_name'] = 'cash_receipt_voucher_listing';
        $page_data['page_title'] = get_phrase('cash_receipt_voucher_listing');
        $this->load->view('backend/index', $page_data);
    }
    function cash_receipt_voucher()
    {
        $page_data['page_name'] = 'cash_receipt_voucher';
        $page_data['page_title'] = get_phrase('cash_receipt_voucher');
        $this->load->view('backend/index', $page_data);
    }
    function add_cash_receipt_voucher()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $data['description'] = $this->input->post('description');
        $counter = count($data['description']);

        //_______________cash_receipt________________
        $data1['voucher_number']=cash_receipt_number();
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['depositor_id']=$this->input->post('depositor_id');
        $data1['voucher_type']=1;
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        // elseif ($status==3)
        // {
        //     //this will be changed.
        //     $result = $this->check_bank_receipt_voucher_coa();
        //     if ($result==true)
        //     {
        //         $data1['status']= $status;
        //         $data1['posted_by']=$_SESSION['login_detail_id'];
        //         $data1['date_posted']=date('Y-m-d');
        //     }
        // }
        $data1['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.cash_receipt',$data1);

        $insert_id = $this->db->insert_id();

        //_____________cash_receipt_details__________
        //$method = $this->input->post('method');
        //$deposit_slip_number = $this->input->post('deposit_slip_number');
        $description = $this->input->post('description');
        //$deposit_bank_id = $this->input->post('deposit_bank_id');
        $amount = $this->input->post('amount');

        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['cash_receipt_id']=$insert_id;
                //$data2['method']=$method[$i];
                //$data2['deposit_slip_number']=$deposit_slip_number[$i];
                $data2['description']=$description[$i];
                //$data2['deposit_bank_id']=$deposit_bank_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename != "")
                {
                    //$ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $data2['attachment'] = multi_file_upload('attachment', $i,'cash_receipt', 'crv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.cash_receipt_details',$data2);
            }
        }

        //_____________journal_entry________________________
        //working
        if ($status==3)
        {
            $depositor_id = $this->input->post('depositor_id');
            $depositor_detail = get_depositor_details($depositor_id);
            $coa_cash_deposit = $depositor_detail[0]['coa_cash_deposit'];
            //$error = 0;
            if ($coa_cash_deposit>0)
            {
                $detials_qur = "select brd.* ,bac.coa_cash_receipt , bac.coa_check_receipt  from ".get_school_db().".bank_receipt_details brd inner join ".get_school_db().".bank_account bac on brd.deposit_bank_id = bac.bank_account_id
                 where brd.bank_receipt_id=".$insert_id." and brd.school_id = ".$_SESSION['school_id']." ";
                $detials_arr = $this->db->query($detials_qur)->result_array();
                // echo "<pre>";
                // print_r($detials_arr);
                $coa_detail = array();
                foreach ($detials_arr as $key => $value)
                {
                    if ($value['method']==1)
                    {
                        $coa_detail[] = $value['coa_cash_receipt'];
                    }
                    elseif ($value['method']==2)
                    {
                        $coa_detail[] = $value['coa_check_receipt'];
                    }
                }
                if (!in_array('0', $coa_detail))
                {
                    // echo "<pre>";
                    // print_r($coa_detail);
                    //_______details entries in ledger_____
                    foreach ($detials_arr as $key1 => $value1)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $value1['description'];
                        $data3['debit'] = $value1['amount'];
                        $total_amount+=$value1['amount'];
                        $data3['credit'] = 0;
                        $data3['entry_type'] = 11;
                        $data3['type_id'] = $value1['bank_receipt_id'];
                        if ($value1['method']==1)
                        {
                            $data3['coa_id'] = $value1['coa_cash_receipt'];
                        }
                        elseif ($value1['method']==2)
                        {
                             $data3['coa_id'] = $value1['coa_check_receipt'];
                        }
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                    }
                    //_______main entry in ledger_____
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = "Voucher Number : ".$data1['voucher_number']." Depositor Name : ".$depositor_detail[0]['name'];
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 11;
                    $data4['type_id'] = $insert_id;
                    $data4['coa_id'] = $coa_cash_deposit;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
                }
                else
                {
                    $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_depositor_coa_is_missing'));
            }
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url().'vouchers/cash_receipt_voucher_listing');
    }
    
    function add_other_cash_receipt_voucher()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $data['description'] = $this->input->post('description');
        $counter = count($data['description']);

        //_______________cash_receipt________________
        $data1['voucher_number']=cash_receipt_number();
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['credit_coa_id']=$this->input->post('credit_coa_id');
        $data1['voucher_type']=2;
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }

        $data1['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.cash_receipt',$data1);

        $insert_id = $this->db->insert_id();

        //_____________cash_receipt_details__________
        //$method = $this->input->post('method');
        //$deposit_slip_number = $this->input->post('deposit_slip_number');
        $description = $this->input->post('description');
        //$deposit_bank_id = $this->input->post('deposit_bank_id');
        $amount = $this->input->post('amount');

        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['cash_receipt_id']=$insert_id;
                //$data2['method']=$method[$i];
                //$data2['deposit_slip_number']=$deposit_slip_number[$i];
                $data2['description']=$description[$i];
                //$data2['deposit_bank_id']=$deposit_bank_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename != "")
                {
                    //$ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $data2['attachment'] = multi_file_upload('attachment', $i,'cash_receipt', 'crv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.cash_receipt_details',$data2);
            }
        }

        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url().'vouchers/cash_receipt_voucher_listing');
    }
    
    function cash_receipt_voucher_detail($cash_rec_id=0)
    {
        $cash_receipt_id = str_decode($cash_rec_id);
        $cash_receipt_qur = "select * from ".get_school_db().".cash_receipt where cash_receipt_id=".$cash_receipt_id." and school_id = ".$_SESSION['school_id']." ";
        $cash_receipt_arr = $this->db->query($cash_receipt_qur)->result_array();

        $cash_receipt_detail_qur ="select * from ".get_school_db().".cash_receipt_details where cash_receipt_id=".$cash_receipt_id." and school_id = ".$_SESSION['school_id']." ";
        $cash_receipt_detail_arr = $this->db->query($cash_receipt_detail_qur)->result_array();

        $page_data['cash_receipt_arr'] = $cash_receipt_arr;

        $page_data['cash_receipt_detail_arr'] = $cash_receipt_detail_arr;
        $page_data['page_name'] = 'cash_receipt_voucher_detail';
        $page_data['page_title'] = get_phrase('cash_receipt_voucher_detail');
        $this->load->view('backend/index', $page_data);
    }
    function cash_receipt_voucher_print($cash_rec_id=0)
    {
        $cash_receipt_id = str_decode($cash_rec_id);
        $cash_receipt_detail_qur ="select cr.* , crd.* from ".get_school_db().".cash_receipt cr
        INNER join ".get_school_db().".cash_receipt_details crd on cr.cash_receipt_id = crd.cash_receipt_id
        where cr.cash_receipt_id=".$cash_receipt_id." and
        cr.school_id = ".$_SESSION['school_id']." ";
        $cash_receipt_detail_arr = $this->db->query($cash_receipt_detail_qur)->result_array();
        // echo "<pre>";
        // print_r($cash_receipt_detail_arr);

        $page_data['cash_receipt_detail_arr'] = $cash_receipt_detail_arr;
        $page_data['page_name']  = 'cash_receipt_voucher_print';
        $page_data['page_title'] = get_phrase('cash_receipt_voucher_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('Pdf');
        $view = 'backend/admin/cash_receipt_voucher_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
    function cash_receipt_voucher_delete($cash_rec_id=0)
    {
        $cash_receipt_id = str_decode($cash_rec_id);
        $cash_receipt_delete_qur ="select * from ".get_school_db().".cash_receipt_details WHERE cash_receipt_id=".$cash_receipt_id." and attachment <> '' and school_id = ".$_SESSION['school_id']." ";
        $row_delete = $this->db->query($cash_receipt_delete_qur)->result_array();
        foreach ($row_delete as $key => $value)
        {
            if (isset($value['attachment']))
            {
                $del_location = system_path($value['attachment'], 'cash_receipt');
                file_delete($del_location);
            }
        }
        
        $this->db->where('cash_receipt_id', $cash_receipt_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.cash_receipt_details');

        $this->db->where('cash_receipt_id', $cash_receipt_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.cash_receipt');

        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
        redirect(base_url() . 'vouchers/cash_receipt_voucher_listing');
        exit();
    }
    function cash_receipt_voucher_edit($cash_rec_id=0)
    {
        $cash_receipt_id = str_decode($cash_rec_id);
        
        $cash_receipt_edit_qur ="select cr.* , crd.* from ".get_school_db().".cash_receipt cr inner join ".get_school_db().".cash_receipt_details crd on cr.cash_receipt_id = crd.cash_receipt_id WHERE cr.cash_receipt_id=".$cash_receipt_id." and cr.school_id = ".$_SESSION['school_id']." ";
        $row_edit = $this->db->query($cash_receipt_edit_qur)->result_array();
        $page_data['row_edit'] = $row_edit;

        $page_data['page_name'] = 'cash_receipt_voucher_edit';
        $page_data['page_title'] = get_phrase('cash_receipt_voucher_edit');
        $this->load->view('backend/index', $page_data);
    }
    function cash_receipt_voucher_update()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $cash_receipt_id = $this->input->post('cash_receipt_id');
        $cash_detials_qur = "select * from ".get_school_db().".cash_receipt_details where cash_receipt_id=".$cash_receipt_id." and school_id = ".$_SESSION['school_id']." ";
        $cash_detials_arr = $this->db->query($cash_detials_qur)->result_array();
        
        $voucher_number = $this->input->post('voucher_number');
        $data['description'] = $this->input->post('description');
        $counter = count($data['description']);

        //____get existing bank_receipt_details_id's before update_
        
        $qur = "select cash_receipt_details_id from ".get_school_db().".cash_receipt_details where cash_receipt_id = ".$cash_receipt_id." and school_id = ".$_SESSION['school_id']." ";
        

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['cash_receipt_details_id'];
        }
        //_______________bank_receipt_______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['depositor_id']=$this->input->post('depositor_id');
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }

        $school_id = $_SESSION['school_id'];
        $this->db->where('cash_receipt_id', $cash_receipt_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.cash_receipt',$data1);
        
        //_______________bank_receipt_details_______________
        $cash_receipt_details_id_arr = $this->input->post('cash_receipt_details_id');
        $description = $this->input->post('description');
        $amount = $this->input->post('amount');
        $filename = $_FILES['attachment'];

        $details_id_arr_second = array();
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $cash_receipt_details_id = $cash_receipt_details_id_arr[$i];
                $data2['cash_receipt_id']=$cash_receipt_id;
                $data2['description']=$description[$i];
                $data2['amount']=$amount[$i];
                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'cash_receipt', 'crv');
                }
                if (!empty($cash_receipt_details_id))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('cash_receipt_details_id', $cash_receipt_details_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.cash_receipt_details',$data2);
                    $details_id_arr_second[] = $cash_receipt_details_id;
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.cash_receipt_details',$data2);
                    $new_cash_receipt_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_cash_receipt_details_id;
                }
            }
        }
        
        
        if ($status==3)
        {
            $get_cash_voucher_coa = get_cash_voucher_coa_settings('Receipt');
            
            $depositor_id = $this->input->post('depositor_id');
            $depositor_detail = get_depositor_details($depositor_id);
            $coa_cash_deposit = $depositor_detail[0]['coa_cash_deposit'];
            //$error = 0;
            if ($coa_cash_deposit>0)
            {
                
                // if (!in_array('0', $coa_detail))
                // {
                
                    //_______details entries in ledger_____
                    foreach ($cash_detials_arr as $key1 => $value1)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $value1['description'];
                        $data3['debit'] = $value1['amount'];
                        $total_amount+=$value1['amount'];
                        $data3['credit'] = 0;
                        $data3['entry_type'] = 16;
                        $data3['type_id'] = $value1['cash_receipt_id'];
                        $data3['coa_id'] = $get_cash_voucher_coa;
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                    }
                    //_______main entry in ledger_____
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = "Voucher Number : ".$data1['voucher_number']." Depositor Name : ".$depositor_detail[0]['name'];
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 16;
                    $data4['type_id'] = $cash_receipt_id;
                    $data4['coa_id'] = $coa_cash_deposit;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
                    
                    $data5['status']= $status;
                    $data5['posted_by']=$_SESSION['login_detail_id'];
                    $data5['date_posted']=date('Y-m-d');
                    $this->db->where('cash_receipt_id', $cash_receipt_id);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.cash_receipt',$data5);
                    
                // }
                // else
                // {
                //     $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                // }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_depositor_coa_is_missing'));
            }
        }
        
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        foreach ($result as $key => $value)
        {
            $this->db->where('cash_receipt_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.cash_receipt_details');
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url() . 'vouchers/cash_receipt_voucher_listing');
    }
    
    function other_cash_receipt_voucher_update()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $cash_receipt_id = $this->input->post('cash_receipt_id');
        $cash_detials_qur = "select * from ".get_school_db().".cash_receipt_details where cash_receipt_id=".$cash_receipt_id." and school_id = ".$_SESSION['school_id']." ";
        $cash_detials_arr = $this->db->query($cash_detials_qur)->result_array();
        
        $voucher_number = $this->input->post('voucher_number');
        $data['description'] = $this->input->post('description');
        $counter = count($data['description']);

        //____get existing bank_receipt_details_id's before update_
        
        $qur = "select cash_receipt_details_id from ".get_school_db().".cash_receipt_details where cash_receipt_id = ".$cash_receipt_id." and school_id = ".$_SESSION['school_id']." ";
        

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['cash_receipt_details_id'];
        }
        //_______________bank_receipt_______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['credit_coa_id']=$this->input->post('credit_coa_id');
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }

        $school_id = $_SESSION['school_id'];
        $this->db->where('cash_receipt_id', $cash_receipt_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.cash_receipt',$data1);
        
        //_______________bank_receipt_details_______________
        $cash_receipt_details_id_arr = $this->input->post('cash_receipt_details_id');
        $description = $this->input->post('description');
        $amount = $this->input->post('amount');
        $filename = $_FILES['attachment'];

        $details_id_arr_second = array();
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $cash_receipt_details_id = $cash_receipt_details_id_arr[$i];
                $data2['cash_receipt_id']=$cash_receipt_id;
                $data2['description']=$description[$i];
                $data2['amount']=$amount[$i];
                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'cash_receipt', 'crv');
                }
                if (!empty($cash_receipt_details_id))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('cash_receipt_details_id', $cash_receipt_details_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.cash_receipt_details',$data2);
                    $details_id_arr_second[] = $cash_receipt_details_id;
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.cash_receipt_details',$data2);
                    $new_cash_receipt_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_cash_receipt_details_id;
                }
            }
        }
        
        
        if ($status==3)
        {
            $get_cash_voucher_coa = get_cash_voucher_coa_settings('Receipt');
            
            $credit_coa_id = $this->input->post('credit_coa_id');
            // $depositor_detail = get_depositor_details($depositor_id);
            // $coa_cash_deposit = $depositor_detail[0]['coa_cash_deposit'];

            if ($credit_coa_id>0)
            {
                
                // if (!in_array('0', $coa_detail))
                // {
                
                    //_______details entries in ledger_____
                    foreach ($cash_detials_arr as $key1 => $value1)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $value1['description'];
                        $data3['debit'] = $value1['amount'];
                        $total_amount+=$value1['amount'];
                        $data3['credit'] = 0;
                        $data3['entry_type'] = 16;
                        $data3['type_id'] = $value1['cash_receipt_id'];
                        $data3['coa_id'] = $get_cash_voucher_coa;
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                    }
                    //_______main entry in ledger_____
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = "Voucher Number : ".$data1['voucher_number']." Depositor Name : ".$depositor_detail[0]['name'];
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 16;
                    $data4['type_id'] = $cash_receipt_id;
                    $data4['coa_id'] = $credit_coa_id;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
                    
                    $data5['status']= $status;
                    $data5['posted_by']=$_SESSION['login_detail_id'];
                    $data5['date_posted']=date('Y-m-d');
                    $this->db->where('cash_receipt_id', $cash_receipt_id);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.cash_receipt',$data5);
                    
                // }
                // else
                // {
                //     $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                // }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_depositor_coa_is_missing'));
            }
        }
        
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        foreach ($result as $key => $value)
        {
            $this->db->where('cash_receipt_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.cash_receipt_details');
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url() . 'vouchers/cash_receipt_voucher_listing');
    }
    //____________________________________________________
    //____________________________________________________
    //______________voucher_setting__________________
    //____________________________________________________
    function cash_voucher_setting()
    {
        $page_data['page_name'] = 'cash_voucher_setting';
        $page_data['page_title'] = get_phrase('cash_voucher_setting');
        $this->load->view('backend/index', $page_data);
    }
    function add_cash_receipt_voucher_setting()
    {
        $qur = "select * from ".get_school_db().".cash_voucher_settings where school_id=".$_SESSION['school_id']." and cash_voucher_type = 'Receipt'";
        $query = $this->db->query($qur);
        if ($query->num_rows() > 0)
        {
            $this->db->where('cash_voucher_type', 'Receipt');
            $this->db->where('school_id', $_SESSION['school_id']);
            $data['coa_id'] = $this->input->post('cash_receipt');
            $this->db->update(get_school_db().'.cash_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('cash_receipt_voucher_setting_updated'));
        }
        else
        {
            $data['cash_voucher_type'] = 'Receipt';
            $data['coa_id'] = $this->input->post('cash_receipt');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.cash_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('cash_receipt_voucher_setting_added'));

        }
        redirect(base_url() . 'vouchers/cash_voucher_setting');
    }
    function add_cash_payment_voucher_setting()
    {
        $qur = "select * from ".get_school_db().".cash_voucher_settings where school_id=".$_SESSION['school_id']." and cash_voucher_type = 'Payment'";

        $query = $this->db->query($qur);
        if ($query->num_rows() > 0)
        {
            $this->db->where('cash_voucher_type', 'Payment');
            $this->db->where('school_id', $_SESSION['school_id']);
            $data['coa_id'] = $this->input->post('cash_payment');
            $this->db->update(get_school_db().'.cash_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('cash_receipt_voucher_setting_updated'));

        }
        else
        {
            $data['cash_voucher_type'] = 'Payment';
            $data['coa_id'] = $this->input->post('cash_payment');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.cash_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('cash_payment_voucher_setting_added'));
        }
        redirect(base_url() . 'vouchers/cash_voucher_setting');
    }
    function add_tax_setting()
    {
        
        $qur = "select * from ".get_school_db().".cash_voucher_settings where school_id=".$_SESSION['school_id']." and cash_voucher_type = 'Tax'";
        $query = $this->db->query($qur);
        if ($query->num_rows() > 0)
        {
            $this->db->where('cash_voucher_type', 'Tax');
            $this->db->where('school_id', $_SESSION['school_id']);
            $data['coa_id'] = $this->input->post('tax_while_bank_payment');
            $this->db->update(get_school_db().'.cash_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('Tax_setting_updated'));
        }
        else
        {
            $data['cash_voucher_type'] = 'Tax';
            $data['coa_id'] = $this->input->post('tax_while_bank_payment');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.cash_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('Tax_setting_added'));

        }
        redirect(base_url() . 'vouchers/cash_voucher_setting');
    }
    
    //____________________________________________________
    //______________bank_payment_voucher__________________
    //____________________________________________________
    function bank_payment_voucher_listing()
    {
        //_____________filters work start_______________
        $apply_filter=$this->input->post('apply_filter');
        $search=$this->input->post('search');
        $supplier_id=$this->input->post('supplier_id');
        $status=$this->input->post('status');
        $start_date=$this->input->post('start_date');
        $end_date=$this->input->post('end_date');
        $bank_id=$this->input->post('bank_id');

        $search_filter="";
        if ( isset($search) && $search != "")
        {
            $search_filter =" AND (
            bp.voucher_number LIKE '%".$search."%' OR
            bpd.method LIKE '%".$search."%' OR
            bpd.deposit_slip_number LIKE '%".$search."%' OR
            bpd.description LIKE '%".$search."%' OR
            bpd.amount LIKE '%".$search."%'
            )";
        }
        $supplier_filter="";
        if (isset($supplier_id) && $supplier_id != "")
        {
            $supplier_filter = " And bp.$supplier_id = ".$supplier_id."";
        }
        $status_filter="";
        if (isset($status) && $status != "")
        {
            $status_filter = " And bp.status = ".$status."";
        }
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $start_date = date_slash($start_date);
            $end_date = date_slash($end_date);
            $date_filter = " And bp.voucher_date BETWEEN '".$start_date."' And '".$end_date."'";
        }

        $bank_filter="";
        if (isset($bank_id) && $bank_id != "")
        {
            $method_filter = " And bpd.bank_from_id = ".$bank_id."";
        }
        //______________filters work end________________


        $qry ="SELECT bp.* , sum(bpd.amount) as total FROM " . get_school_db() . ".bank_payment bp
        inner join " . get_school_db() . ".bank_payment_details
        bpd on bp.bank_payment_id = bpd.bank_payment_id 
        WHERE bp.school_id = ".$_SESSION['school_id']." 
        ".$search_filter." ".$supplier_filter."
        ".$status_filter." ".$date_filter." ".$bank_filter."
        group by bp.bank_payment_id";

        $data = $this->db->query($qry)->result_array();

        $page_data['apply_filter'] =$apply_filter;
        $page_data['search'] =$search;
        $page_data['$supplier_id'] =$supplier_id;
        $page_data['status'] =$status;
        $page_data['start_date'] =$start_date;
        $page_data['end_date'] =$end_date;
        $page_data['bank_id'] =$bank_id;

        $page_data['data'] = $data;
        $page_data['page_name'] = 'bank_payment_voucher_listing';
        $page_data['page_title'] = get_phrase('bank_payment_voucher_listing');
        $this->load->view('backend/index', $page_data);
    }
    function bank_payment_voucher()
    {
        $page_data['page_name'] = 'bank_payment_voucher';
        $page_data['page_title'] = get_phrase('bank_payment_voucher');
        $this->load->view('backend/index', $page_data);
    }
    function add_bank_payment_voucher()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $count_row = $this->input->post('amount');
        $counter = count($count_row);
        
        $data1['voucher_number'] = bank_payment_number();
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['supplier_id']=$this->input->post('supplier_id');
        $data1['voucher_type'] = 1; // Supplier Payment
        $status=$this->input->post('status');
        if ($status==1)
        {
          $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        $data1['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.bank_payment',$data1);

        $insert_id = $this->db->insert_id();
        //_____________bank_payment_details__________
        $purchase_voucher_id = $this->input->post('purchase_voucher_id');
        $cheque_number = $this->input->post('cheque_number');
        $description = $this->input->post('description');
        $bank_from_id = $this->input->post('bank_from_id');
        $amount = $this->input->post('amount');
        $total_amount = 0;

        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['bank_payment_id']=$insert_id;
                $data2['purchase_voucher_id']=$purchase_voucher_id[$i];
                $data2['cheque_number']=$cheque_number[$i];
                $data2['description']=$description[$i];
                $data2['bank_from_id']=$bank_from_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'bank_payment', 'bpv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.bank_payment_details',$data2);
                
                // Add Current Cheque Number
                $cheque_data = array('current_cheque_number' => $cheque_number[$i]);
                $this->db->where("bank_id",$bank_from_id[$i])->update(get_school_db().'.bank_cheque_books',$cheque_data);
            }
        }
        
        //__________________________________________________
        //__________________________________________________

        //_____________journal_entry________________________
        if ($status==3)
        {
            $supplier_id = $this->input->post('supplier_id');
            $coa_id = $this->input->post('coa_id');
            $purchase_voucher_no = $this->input->post('purchase_voucher_no');
            $supplier_detail = get_supplier_details($supplier_id);
            $coa_bank_supplier = $supplier_detail[0]['coa_bank_payment'];
            if ($coa_bank_supplier>0)
            {
                $coa_detail = array();
                for ($i=0; $i < $counter ; $i++)
                {
                    if (!empty($bank_from_id[$i]))
                    {
                        $bank_details = get_bank_details($bank_from_id[$i]);
                        $coa_detail[] = $bank_details[0]['coa_check_payment'];
                    }
                } 

                if (!in_array(0, $coa_detail))
                {
                    //_______details entries in ledger_____
                    for ($i=0; $i < $counter ; $i++)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $description[$i];
                        $data3['debit'] = $amount[$i];
                        $total_amount+=$amount[$i];
                        $data3['credit'] = 0;
                        $data3['entry_type'] = 13;
                        $data3['type_id'] = $insert_id;
                        if (!empty($bank_from_id[$i]))
                        {
                            $bank_details = get_bank_details($bank_from_id[$i]);
                        }
                        
                        $data3['coa_id'] = $bank_details[0]['coa_check_payment'];
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                        
                        //******** update purchase voucher table unpaid to paid ******
                        //************************************************************
                        $pv_data['payment_status'] = 1;
                        $this->db->where('voucher_number' , $purchase_voucher_no[$i]);
                        $this->db->update(get_school_db().'.purchase_voucher',$pv_data);
                        //************************************************************
                        //************************************************************
                    }
                    
                    // Supplier Journal Entry
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = get_phrase('voucher_number').": ".$data1['voucher_number']." ".get_phrase('supplier_name')." : ".$supplier_detail[0]['name'];
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 13;
                    $data4['type_id'] = $insert_id;
                    $data4['coa_id'] = $coa_bank_supplier;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
                    
                    // Tax Jourbal Entry
                    $filing_status = $this->input->post("filing_status");
                    $tax_percent = $this->input->post("supplier_percentage");
                    $get_tax_coa_id = get_cash_voucher_coa_settings('Tax');
                    if($filing_status == 1 || $filing_status == 2)
                    {
                        $get_tax_amount = ($total_amount*$tax_percent)/100;
                        $remaining_amount = $total_amount - $get_tax_amount;
                        
                        // Supplier Tax Journal Entry
                        $data4['school_id'] = $_SESSION['school_id'];
                        $data4['entry_date'] = date('Y-m-d H:i:s');
                        $data4['detail'] = get_phrase('voucher_number').": ".$data1['voucher_number']." ".get_phrase('supplier_name')." : ".$supplier_detail[0]['name'] . " Has Been Debited With Tax " . $tax_percent .'%';
                        $data4['debit'] = $remaining_amount;
                        $data4['credit'] = 0;
                        $data4['entry_type'] = 13;
                        $data4['type_id'] = $insert_id;
                        $data4['coa_id'] = $coa_bank_supplier;
    
                        $this->db->insert(get_school_db().'.journal_entry',$data4);


                        // Tax Journal Entry
                        $data4['school_id'] = $_SESSION['school_id'];
                        $data4['entry_date'] = date('Y-m-d H:i:s');
                        $data4['detail'] = "With Holding Tax Deducted From" . get_phrase('supplier_name')." : ".$supplier_detail[0]['name'];
                        $data4['debit'] = 0;
                        $data4['credit'] = $get_tax_amount;
                        $data4['entry_type'] = 13;
                        $data4['type_id'] = $insert_id;
                        $data4['coa_id'] = $get_tax_coa_id;
    
                        $this->db->insert(get_school_db().'.journal_entry',$data4);

                    }
                    

                    //________update bank_receipt________
                    $data1['status']= $status;
                    $data1['submitted_by']=$_SESSION['login_detail_id'];
                    $data1['date_submitted']=date('Y-m-d'); 
                    $data1['posted_by']=$_SESSION['login_detail_id'];
                    $data1['date_posted']=date('Y-m-d');
                    $this->db->where('bank_payment_id', $insert_id);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.bank_payment',$data1);
                    
            
                }
                else
                {
                    $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_supplier_coa_is_missing'));
            }
        }
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }

        redirect(base_url().'vouchers/bank_payment_voucher_listing');
    }
    
    function add_other_bank_payment_voucher()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $count_row = $this->input->post('amount');
        $counter = count($count_row);
        
        $data1['voucher_number'] = bank_payment_number();
        $data1['voucher_date'] = date_slash($this->input->post('voucher_date'));
        $data1['debit_coa_id'] = $this->input->post('coa_id');
        $data1['voucher_type'] = 2; // Other Payment
        $status=$this->input->post('status');
        if ($status==1)
        {
          $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        $data1['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.bank_payment',$data1);

        $insert_id = $this->db->insert_id();
        //_____________bank_payment_details__________
        $cheque_number = $this->input->post('cheque_number');
        $description = $this->input->post('description');
        $bank_from_id = $this->input->post('bank_from_id');
        $amount = $this->input->post('amount');
        $total_amount = 0;

        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['bank_payment_id']=$insert_id;
                $data2['cheque_number']=$cheque_number[$i];
                $data2['description']=$description[$i];
                $data2['bank_from_id']=$bank_from_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'bank_payment', 'bpv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.bank_payment_details',$data2);
                
                // Add Current Cheque Number
                $cheque_data = array('current_cheque_number' => $cheque_number[$i]);
                $this->db->where("bank_id",$bank_from_id[$i])->update(get_school_db().'.bank_cheque_books',$cheque_data);
            }
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }

        redirect(base_url().'vouchers/bank_payment_voucher_listing');
    }
    
    // function check_bank_receipt_voucher_coa()
    // {
    //     $depositor_id = $this->input->post('depositor_id');
    //     $depositor_detail = get_depositor_details($depositor_id);
    //     $coa_bank_deposit = $depositor_detail[0]['coa_bank_deposit'];
    //     if ($coa_bank_deposit>0)
    //     {
    //         $data['method'] = $this->input->post('method');
    //         $counter = count($data['method']);

    //         $method = $this->input->post('method');
    //         $deposit_slip_number = $this->input->post('deposit_slip_number');
    //         $description = $this->input->post('description');
    //         $deposit_bank_id = $this->input->post('deposit_bank_id');
    //         $amount = $this->input->post('amount');

    //         $bank_coa_detail = array();
    //         for ($i=0; $i < $counter ; $i++)
    //         {
    //             if (!empty($method[$i]) && !empty($description[$i]) && !empty($amount[$i]))
    //             {
    //                 $bank_details = get_bank_details($deposit_bank_id[$i]);
    //                 if ($method[$i]==1)
    //                 {
    //                     $bank_coa_detail[] = $bank_details[0]['coa_cash_receipt'];
    //                 }
    //                 elseif ($method[$i]==2)
    //                 {
    //                     $bank_coa_detail[] = $bank_details[0]['coa_check_receipt'];
    //                 }
    //             }
    //         }
    //         if (!in_array('0', $bank_coa_detail))
    //         {
    //             return true;
    //         }
    //         else
    //         {
    //             return false;
    //         }
    //     }
    //     else
    //     {
    //         return false;
    //     }
    // }
    function bank_payment_voucher_detail($bank_pay_id=0)
    {
        $bank_payment_id = str_decode($bank_pay_id);
        $bank_payment_qur = "select * from ".get_school_db().".bank_payment where bank_payment_id=".$bank_payment_id." and school_id = ".$_SESSION['school_id']." ";
        $bank_payment_arr = $this->db->query($bank_payment_qur)->result_array();


        $bank_payment_detail_qur ="select * from ".get_school_db().".bank_payment_details where bank_payment_id=".$bank_payment_id." and school_id = ".$_SESSION['school_id']." ";
        $bank_payment_detail_arr = $this->db->query($bank_payment_detail_qur)->result_array();

        $page_data['bank_payment_arr'] = $bank_payment_arr;

        $page_data['bank_payment_detail_arr'] = $bank_payment_detail_arr;
        $page_data['page_name'] = 'bank_payment_voucher_detail';
        $page_data['page_title'] = get_phrase('bank_payment_voucher_detail');
        $this->load->view('backend/index', $page_data);
    }
    function bank_payment_voucher_delete($bank_pay_id=0)
    {
        $bank_payment_id = str_decode($bank_pay_id);
        $bank_payment_delete_qur ="select * from ".get_school_db().".bank_payment_details WHERE bank_payment_id=".$bank_payment_id." and attachment <> '' and school_id = ".$_SESSION['school_id']." ";
        $row_delete = $this->db->query($bank_payment_delete_qur)->result_array();
        foreach ($row_delete as $key => $value)
        {
            if (isset($value['attachment']))
            {
                $del_location = system_path($value['attachment'], 'bank_payment');
                file_delete($del_location);
            }
        }
        
        $this->db->where('bank_payment_id', $bank_payment_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.bank_payment_details');

        $this->db->where('bank_payment_id', $bank_payment_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.bank_payment');

        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
        redirect(base_url() . 'vouchers/bank_payment_voucher_listing');
        exit();
    }
    function bank_payment_voucher_edit($bank_pay_id=0)
    {
        $bank_payment_id = str_decode($bank_pay_id);
        $bank_payment_edit_qur ="select bp.* , bpd.* from ".get_school_db().".bank_payment bp inner join ".get_school_db().".bank_payment_details bpd on bp.bank_payment_id = bpd.bank_payment_id WHERE bp.bank_payment_id=".$bank_payment_id." and bp.school_id = ".$_SESSION['school_id']." ";
        $row_edit = $this->db->query($bank_payment_edit_qur)->result_array();
        $page_data['row_edit'] = $row_edit;

        $page_data['page_name'] = 'bank_payment_voucher_edit';
        $page_data['page_title'] = get_phrase('bank_payment_voucher_edit');
        $this->load->view('backend/index', $page_data);
    }
    function bank_payment_voucher_update()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $voucher_number = $this->input->post('voucher_number');
        $count_row = $this->input->post('amount');
        $counter = count($count_row);
        $old_status = $this->input->post('old_status');

        //____get existing bank_payment_details_id's before update_
        $bank_payment_id = $this->input->post('bank_payment_id');

        $qur = "select bank_payment_details_id from ".get_school_db().".bank_payment_details where bank_payment_id = ".$bank_payment_id." and school_id = ".$_SESSION['school_id']." ";

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['bank_payment_details_id'];
        }

        //_______________bank_payment_______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['supplier_id']=$this->input->post('supplier_id');
        $status=$this->input->post('status');
        if ($status==1)
        {
          $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        $school_id = $_SESSION['school_id'];
        $this->db->where('bank_payment_id', $bank_payment_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.bank_payment',$data1);
        
        //_______________bank_payment_details_______________
        $bank_payment_details_id_arr = $this->input->post('bank_payment_details_id');
        $purchase_voucher_id = $this->input->post('purchase_voucher_id');
        $cheque_number = $this->input->post('cheque_number');
        $description = $this->input->post('description');
        $bank_from_id = $this->input->post('bank_from_id');
        $amount = $this->input->post('amount');
        
        $total_amount = 0;

        $filename = $_FILES['attachment'];

        $details_id_arr_second = array();
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $bank_payment_details_id = $bank_payment_details_id_arr[$i];
                
                $data2['bank_payment_id']=$bank_payment_id;
                $data2['purchase_voucher_id']=$purchase_voucher_id[$i];
                $data2['cheque_number']=$cheque_number[$i];
                $data2['description']=$description[$i];
                $data2['bank_from_id']=$bank_from_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'bank_payment', 'bpv');
                }
                if (!empty($bank_payment_details_id))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('bank_payment_details_id', $bank_payment_details_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.bank_payment_details',$data2);
                    $details_id_arr_second[] = $bank_payment_details_id;
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.bank_payment_details',$data2);
                    $new_bank_payment_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_bank_payment_details_id; 
                }
            }
        }
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        foreach ($result as $key => $value)
        {
            $this->db->where('bank_payment_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.bank_payment_details');
        }
        
        //_____________journal_entry________________________
        if ($status==3)
        {
            $supplier_id = $this->input->post('supplier_id');
            $coa_id = $this->input->post('coa_id');
            $purchase_voucher_no = $this->input->post('purchase_voucher_no');
            $supplier_detail = get_supplier_details($supplier_id);
            $coa_bank_supplier = $supplier_detail[0]['coa_bank_payment'];
            if ($coa_bank_supplier>0)
            {
                $coa_detail = array();
                for ($i=0; $i < $counter ; $i++)
                {
                    if (!empty($bank_from_id[$i]))
                    {
                        $bank_details = get_bank_details($bank_from_id[$i]);
                        $coa_detail[] = $bank_details[0]['coa_check_payment'];
                    }
                } 

                if (!in_array(0, $coa_detail))
                {
                    
                    // Get Tax Percent
                    $tax_percent = $this->input->post("supplier_percentage");
                    
                    //_______details entries in ledger_____
                    for ($i=0; $i < $counter ; $i++)
                    {
                        if($amount[$i] != "")
                        {
                            $data3['school_id'] = $_SESSION['school_id'];
                            $data3['entry_date'] = date('Y-m-d H:i:s');
                            $data3['detail'] = $description[$i] . "<br> Bank Has Been Credited Against Purchase Voucher No - " . get_pv_vouche_number($purchase_voucher_id[$i]);
                            $data3['debit'] = 0;
                            $total_amount+=$amount[$i];
                            $get_tax_amount = ($amount[$i]*$tax_percent)/100;
                            $remaining_amount = $amount[$i] - $get_tax_amount;
                            $data3['credit'] = $remaining_amount;
                            $data3['entry_type'] = 13;
                            $data3['type_id'] = $bank_payment_id;
                            if (!empty($bank_from_id[$i]))
                            {
                                $bank_details = get_bank_details($bank_from_id[$i]);
                            }
                            
                            $data3['coa_id'] = $bank_details[0]['coa_check_payment'];
                            $this->db->insert(get_school_db().'.journal_entry',$data3);
                            
                            //******** update purchase voucher table unpaid to paid ******
                            //************************************************************
                            $pv_data['payment_status'] = 1;
                            $this->db->where('voucher_number' , $purchase_voucher_no[$i]);
                            $this->db->update(get_school_db().'.purchase_voucher',$pv_data);
                            
                            
                            // Purchase Voucher Status Paid
                            $pv_data = array(
                                'payment_status' => 1    
                            );
                            $this->db->where('purchase_voucher_id', $purchase_voucher_id[$i]);
                            $this->db->where('school_id', $_SESSION['school_id']);
                            $this->db->update(get_school_db().'.purchase_voucher',$pv_data);
                        }

                    }
                    
                    $get_tax_amount = ($total_amount*$tax_percent)/100;
                    
                    // Supplier Journal Entry
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = get_phrase('voucher_number').": ".$data1['voucher_number']." ".get_phrase('(supplier_name)')." : ".$supplier_detail[0]['name'] . " Has Been Debited With Tax " . $tax_percent .'%';
                    $data4['debit'] = $get_tax_amount;
                    $data4['credit'] = 0;
                    $data4['entry_type'] = 13;
                    $data4['type_id'] = $bank_payment_id;
                    $data4['coa_id'] = $coa_bank_supplier;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
                    
                    // Tax Jourbal Entry
                    $filing_status = $this->input->post("filing_status");
                    $get_tax_coa_id = get_cash_voucher_coa_settings('Tax');
                    $remaining_amount = $total_amount - $get_tax_amount;
                    if($filing_status == 1 || $filing_status == 2)
                    {
                        // Supplier Tax Journal Entry
                        $data4['school_id'] = $_SESSION['school_id'];
                        $data4['entry_date'] = date('Y-m-d H:i:s');
                        $data4['detail'] = get_phrase('voucher_number').": ".$data1['voucher_number']." ".get_phrase('(supplier_name)')." : ".$supplier_detail[0]['name'] . " Has Been Debited";
                        $data4['debit'] = $remaining_amount;
                        $data4['credit'] = 0;
                        $data4['entry_type'] = 20;
                        $data4['type_id'] = $bank_payment_id;
                        $data4['coa_id'] = $coa_bank_supplier;
    
                        $this->db->insert(get_school_db().'.journal_entry',$data4);


                        // Tax Journal Entry
                        $data4['school_id'] = $_SESSION['school_id'];
                        $data4['entry_date'] = date('Y-m-d H:i:s');
                        $data4['detail'] = "With Holding Tax Deducted From " . get_phrase('(supplier_name)')." : ".$supplier_detail[0]['name'];
                        $data4['debit'] = 0;
                        $data4['credit'] = $get_tax_amount;
                        $data4['entry_type'] = 20;
                        $data4['type_id'] = $bank_payment_id;
                        $data4['coa_id'] = $get_tax_coa_id;
    
                        $this->db->insert(get_school_db().'.journal_entry',$data4);

                    }
                    

                    //________Bank Payment Voucher________//
                    $data1['status']= $status;
                    $data1['submitted_by']=$_SESSION['login_detail_id'];
                    $data1['date_submitted']=date('Y-m-d'); 
                    $data1['posted_by']=$_SESSION['login_detail_id'];
                    $data1['date_posted']=date('Y-m-d');
                    $this->db->where('bank_payment_id', $bank_payment_id);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.bank_payment',$data1);

                }
                else
                {
                    $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_supplier_coa_is_missing'));
            }
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url() . 'vouchers/bank_payment_voucher_listing');
    }
    function other_bank_payment_voucher_update()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $voucher_number = $this->input->post('voucher_number');
        $count_row = $this->input->post('amount');
        $counter = count($count_row);
        $old_status = $this->input->post('old_status');

        //____get existing bank_payment_details_id's before update_
        $bank_payment_id = $this->input->post('bank_payment_id');

        $qur = "select bank_payment_details_id from ".get_school_db().".bank_payment_details where bank_payment_id = ".$bank_payment_id." and school_id = ".$_SESSION['school_id']." ";

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['bank_payment_details_id'];
        }

        //_______________bank_payment_______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['debit_coa_id']=$this->input->post('coa_id');
        $status=$this->input->post('status');
        if ($status==1)
        {
          $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        $school_id = $_SESSION['school_id'];
        $this->db->where('bank_payment_id', $bank_payment_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.bank_payment',$data1);
        
        //_______________bank_payment_details_______________
        $bank_payment_details_id_arr = $this->input->post('bank_payment_details_id');
        $cheque_number = $this->input->post('cheque_number');
        $description = $this->input->post('description');
        $bank_from_id = $this->input->post('bank_from_id');
        $amount = $this->input->post('amount');
        
        $total_amount = 0;

        $filename = $_FILES['attachment'];

        $details_id_arr_second = array();
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $bank_payment_details_id = $bank_payment_details_id_arr[$i];
                
                $data2['bank_payment_id']=$bank_payment_id;
                $data2['cheque_number']=$cheque_number[$i];
                $data2['description']=$description[$i];
                $data2['bank_from_id']=$bank_from_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'bank_payment', 'bpv');
                }
                if (!empty($bank_payment_details_id))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('bank_payment_details_id', $bank_payment_details_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.bank_payment_details',$data2);
                    $details_id_arr_second[] = $bank_payment_details_id;
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.bank_payment_details',$data2);
                    $new_bank_payment_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_bank_payment_details_id; 
                }
            }
        }
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        foreach ($result as $key => $value)
        {
            $this->db->where('bank_payment_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.bank_payment_details');
        }
        
        //_____________journal_entry________________________
        if ($status==3)
        {
            $debit_coa_id = $this->input->post('coa_id');
            
            if ($debit_coa_id > 0)
            {
                $coa_detail = array();
                for ($i=0; $i < $counter ; $i++)
                {
                    if (!empty($bank_from_id[$i]))
                    {
                        $bank_details = get_bank_details($bank_from_id[$i]);
                        $coa_detail[] = $bank_details[0]['coa_check_payment'];
                    }
                } 

                if (!in_array(0, $coa_detail))
                {
                    //_______details entries in ledger_____
                    for ($i=0; $i < $counter ; $i++)
                    {
                        if($amount[$i] != "")
                        {
                            $data3['school_id'] = $_SESSION['school_id'];
                            $data3['entry_date'] = date('Y-m-d H:i:s');
                            $data3['detail'] = $description[$i] . "<br> Bank Has Been Credited ";
                            $data3['debit'] = 0;
                            $total_amount+=$amount[$i];
                            $data3['credit'] = $total_amount;
                            $data3['entry_type'] = 13;
                            $data3['type_id'] = $bank_payment_id;
                            if (!empty($bank_from_id[$i]))
                            {
                                $bank_details = get_bank_details($bank_from_id[$i]);
                            }
                            
                            $data3['coa_id'] = $bank_details[0]['coa_check_payment'];
                            $this->db->insert(get_school_db().'.journal_entry',$data3);
                            
                        }
                    }
                    

                    // Other COA Journal Entry
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = get_phrase('voucher_number').": ".$data1['voucher_number']. ' ' . get_coa_name($debit_coa_id) ." Has Been Debited";
                    $data4['debit'] = $total_amount;
                    $data4['credit'] = 0;
                    $data4['entry_type'] = 13;
                    $data4['type_id'] = $bank_payment_id;
                    $data4['coa_id'] = $debit_coa_id;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);

                    

                    //________Bank Payment Voucher________//
                    $data1['status']= $status;
                    $data1['submitted_by']=$_SESSION['login_detail_id'];
                    $data1['date_submitted']=date('Y-m-d'); 
                    $data1['posted_by']=$_SESSION['login_detail_id'];
                    $data1['date_posted']=date('Y-m-d');
                    $this->db->where('bank_payment_id', $bank_payment_id);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->update(get_school_db().'.bank_payment',$data1);

                }
                else
                {
                    $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_bank_coa_is_missing'));
                }
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_other_coa_is_missing'));
            }
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url() . 'vouchers/bank_payment_voucher_listing');
    }
    function fetch_cheque_number()
    {
        $json = array();
        $bank_id = $this->input->post('bank_id');
        $bank_chequebook_qur ="select * from ".get_school_db().".bank_cheque_books where bank_id=".$bank_id." AND status = 1";
        $bank_chequebook_detail_arr = $this->db->query($bank_chequebook_qur)->result_array();
        
        if($bank_chequebook_detail_arr[0]['current_cheque_number'] == 0)
        {
            $json['cheque_number'] = $bank_chequebook_detail_arr[0]['start_cheque_number'];
        }else{
            if($bank_chequebook_detail_arr[0]['end_cheque_number'] == $bank_chequebook_detail_arr[0]['current_cheque_number'])
            {
                $json['error'] = "Chequebook completed there is no any more cheque number please add another cheque book";
            }else{
                $next_cheque_number = intval($bank_chequebook_detail_arr[0]['current_cheque_number']) + 1;
                $json['cheque_number'] = $next_cheque_number;
            }
        }
        
        echo json_encode($json);
    }
    // function bank_payment_voucher_print($bank_payment_id=0)
    // {
    //     $bank_payment_detail_qur ="select bp.* , bpd.* from ".get_school_db().".bank_payment bp
    //     INNER join ".get_school_db().".bank_payment_details bpd on bp.bank_payment_id = bpd.bank_payment_id
    //     where bp.bank_payment_id=".$bank_payment_id." and
    //     bp.school_id = ".$_SESSION['school_id']." ";
    //     $bank_payment_detail_arr = $this->db->query($bank_payment_detail_qur)->result_array();
    //     // echo "<pre>";
    //     // print_r($bank_payment_detail_arr);

    //     $page_data['bank_payment_detail_arr'] = $bank_payment_detail_arr;
    //     $page_data['page_name']  = 'bank_payment_voucher_print';
    //     $page_data['page_title'] = get_phrase('bank_payment_voucher_print');
    //     //$this->load->view('backend/index', $page_data);
    //     $this->load->library('Pdf');
    //     $view = 'backend/admin/bank_payment_voucher_print';
    //     $this->pdf->load_view($view,$page_data);
    //     $this->pdf->render();
    //     $this->pdf->stream("".$page_data['page_title'].".pdf");
    //     //$this->load->view('backend/index', $page_data);
    // }
    
    
    //____________________________________________________
    //____________________________________________________
    //______________cash_payment_voucher__________________
    //____________________________________________________
    function cash_payment_voucher_listing()
    {
        //_____________filters work start_______________
        $apply_filter=$this->input->post('apply_filter');
        $search=$this->input->post('search');
        $supplier_id=$this->input->post('supplier_id');
        $status=$this->input->post('status');
        $start_date=$this->input->post('start_date');
        $end_date=$this->input->post('end_date');

        $search_filter="";
        if ( isset($search) && $search != "")
        {
            $search_filter =" AND (
            cp.voucher_number LIKE '%".$search."%' OR
            cpd.description LIKE '%".$search."%' OR
            cpd.amount LIKE '%".$search."%'
            )";
        }
        $supplier_filter="";
        if (isset($supplier_id) && $supplier_id != "")
        {
            $depositor_filter = " And cp.supplier_id = ".$supplier_id."";
        }
        $status_filter="";
        if (isset($status) && $status != "")
        {
            $status_filter = " And cp.status = ".$status."";
        }
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $start_date = date_slash($start_date);
            $end_date = date_slash($end_date);
            $date_filter = " And cp.voucher_date BETWEEN '".$start_date."' And '".$end_date."'";
        }
        //______________filters work end________________

        $cash_receipt_qry ="SELECT cp.* , sum(cpd.amount) as total FROM " . get_school_db() . ".cash_payment cp
        inner join " . get_school_db() . ".cash_payment_details
        cpd on cp.cash_payment_id = cpd.cash_payment_id 
        WHERE cp.school_id = ".$_SESSION['school_id']." 
        ".$search_filter." ".$supplier_filter."
        ".$status_filter." ".$date_filter."
        group by cp.cash_payment_id";

        $data = $this->db->query($cash_receipt_qry)->result_array();

        $page_data['apply_filter'] =$apply_filter;
        $page_data['search'] =$search;
        $page_data['supplier_id'] =$supplier_id;
        $page_data['status'] =$status;
        $page_data['start_date'] =$start_date;
        $page_data['end_date'] =$end_date;

        $page_data['data'] = $data;
        $page_data['page_name'] = 'cash_payment_voucher_listing';
        $page_data['page_title'] = get_phrase('cash_payment_voucher_listing');
        $this->load->view('backend/index', $page_data);
    }
    function cash_payment_voucher()
    {
        $page_data['page_name'] = 'cash_payment_voucher';
        $page_data['page_title'] = get_phrase('cash_payment_voucher');
        $this->load->view('backend/index', $page_data);
    }
    function add_cash_payment_voucher()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $data['description'] = $this->input->post('description');
        $counter = count($data['description']);

        //_______________cash_payment________________
        $data1['voucher_number']=cash_receipt_number();
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
       // $data1['supplier_id'] = $this->input->post('supplier_id');  remove this column from cash_payment table & add in cash_payment_details table
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        elseif ($status==3)
        {
            $data1['status']= $status;
            $data1['posted_by']=$_SESSION['login_detail_id'];
            $data1['date_posted']=date('Y-m-d');
        }
        $data1['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.cash_payment',$data1);

        $insert_id = $this->db->insert_id();

        //_____________cash_payment_details__________
        $expense_coa_id = $this->input->post('expense_coa_id');  // this can be supplier / any expense
        $description = $this->input->post('description');
        $amount = $this->input->post('amount');

        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['cash_payment_id']=$insert_id;
                //$data2['method']=$method[$i];
                //$data2['deposit_slip_number']=$deposit_slip_number[$i];
                $data2['description']=$description[$i];
                $data2['expense_coa_id']=$expense_coa_id[$i];
                $data2['amount']=$amount[$i];

                if ($filename != "")
                {
                    //$ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $data2['attachment'] = multi_file_upload('attachment', $i,'cash_payment', 'cpv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.cash_payment_details',$data2);
            }
        }

        //_____________journal_entry________________________
        if ($status==3)
        {
            $supplier_id = $this->input->post('supplier_id');
            // $supplier_detail = get_supplier_details($supplier_id);
            // $coa_cash_deposit = $depositor_detail[0]['coa_cash_deposit'];
            $coa_cash_deposit = $supplier_id;
            $get_cash_voucher_coa = get_cash_voucher_coa_settings('Payment');
            if ($coa_cash_deposit>0)
            {
                $detials_qur = "select cpd.*   from ".get_school_db().".cash_payment_details cpd 
                 where cpd.cash_payment_id=".$insert_id." and cpd.school_id = ".$_SESSION['school_id']." ";
                $detials_arr = $this->db->query($detials_qur)->result_array();


                    //---- Details Credit Entries in Ledger ----//
                    foreach ($detials_arr as $key1 => $value1)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $value1['description'];
                        $data3['debit'] = 0;
                        $total_amount+=$value1['amount'];
                        $data3['credit'] = $value1['amount'];
                        $data3['entry_type'] = 14;
                        $data3['type_id'] = $value1['cash_payment_id'];
                        $data3['coa_id'] = $get_cash_voucher_coa;
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                    }
                    
                    //---- Details Debit Entries in Ledger ----//
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = "Voucher Number : ".$data1['voucher_number']." Supplier / Expanse : ".$supplier_id;
                    $data4['debit'] = $total_amount;
                    $data4['credit'] = 0;
                    $data4['entry_type'] = 14;
                    $data4['type_id'] = $insert_id;
                    $data4['coa_id'] = $coa_cash_deposit;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_supplier_coa_is_missing'));
            }
        }
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url().'vouchers/cash_payment_voucher_listing');
    }
    function cash_payment_voucher_detail($cash_pay_id=0)
    {
        $cash_payment_id = str_decode($cash_pay_id);
        $cash_payment_qur = "select * from ".get_school_db().".cash_payment where cash_payment_id=".$cash_payment_id." and school_id = ".$_SESSION['school_id']." ";
        $cash_payment_arr = $this->db->query($cash_payment_qur)->result_array();

        $cash_payment_detail_qur ="select * from ".get_school_db().".cash_payment_details where cash_payment_id=".$cash_payment_id." and school_id = ".$_SESSION['school_id']." ";
        $cash_payment_detail_arr = $this->db->query($cash_payment_detail_qur)->result_array();

        $page_data['cash_payment_arr'] = $cash_payment_arr;

        $page_data['cash_payment_detail_arr'] = $cash_payment_detail_arr;
        $page_data['page_name'] = 'cash_payment_voucher_detail';
        $page_data['page_title'] = get_phrase('cash_payment_voucher_detail');
        $this->load->view('backend/index', $page_data);
    }
    function cash_payment_voucher_print($cash_pay_id=0)
    {
        $cash_payment_id = str_decode($cash_pay_id);
        $cash_receipt_detail_qur ="select cr.* , crd.* from ".get_school_db().".cash_receipt cr
        INNER join ".get_school_db().".cash_receipt_details crd on cr.cash_receipt_id = crd.cash_receipt_id
        where cr.cash_receipt_id=".$cash_payment_id." and
        cr.school_id = ".$_SESSION['school_id']." ";
        $cash_receipt_detail_arr = $this->db->query($cash_receipt_detail_qur)->result_array();
        

        $page_data['cash_receipt_detail_arr'] = $cash_receipt_detail_arr;
        $page_data['page_name']  = 'cash_receipt_voucher_print';
        $page_data['page_title'] = get_phrase('cash_receipt_voucher_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('Pdf');
        $view = 'backend/admin/cash_receipt_voucher_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
    function cash_payment_voucher_delete($cash_pay_id=0)
    {
        $cash_payment_id = str_decode($cash_pay_id);
        $cash_payment_delete_qur ="select * from ".get_school_db().".cash_payment_details WHERE cash_payment_id=".$cash_payment_id." and attachment <> '' and school_id = ".$_SESSION['school_id']." ";
        $row_delete = $this->db->query($cash_payment_delete_qur)->result_array();
        foreach ($row_delete as $key => $value)
        {
            if (isset($value['attachment']))
            {
                $del_location = system_path($value['attachment'], 'cash_payment');
                file_delete($del_location);
            }
        }
        
        $this->db->where('cash_payment_id', $cash_payment_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.cash_payment_details');

        $this->db->where('cash_payment_id', $cash_payment_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.cash_payment');

        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
        redirect(base_url() . 'vouchers/cash_payment_voucher_listing');
        exit();
    }
    function cash_payment_voucher_edit($cash_pay_id=0)
    {
        $cash_payment_id = str_decode($cash_pay_id);
        $cash_payment_edit_qur ="select cp.* , cpd.* from ".get_school_db().".cash_payment cp inner join ".get_school_db().".cash_payment_details cpd on cp.cash_payment_id = cpd.cash_payment_id WHERE cp.cash_payment_id=".$cash_payment_id." and cp.school_id = ".$_SESSION['school_id']." ";
        $row_edit = $this->db->query($cash_payment_edit_qur)->result_array();
        $page_data['row_edit'] = $row_edit;

        $page_data['page_name'] = 'cash_payment_voucher_edit';
        $page_data['page_title'] = get_phrase('cash_payment_voucher_edit');
        $this->load->view('backend/index', $page_data);
    }
    function cash_payment_voucher_update()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $voucher_number = $this->input->post('voucher_number');
        $data['description'] = $this->input->post('description');
        $counter = count($data['description']);

        //____get existing bank_receipt_details_id's before update_
        $cash_payment_id = $this->input->post('cash_payment_id');
        $qur = "select cash_payment_details_id from ".get_school_db().".cash_payment_details where cash_payment_id = ".$cash_payment_id." and school_id = ".$_SESSION['school_id']." ";

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['cash_payment_details_id'];
        }
        //_______________cash_payment _______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        // $data1['supplier_id'] = $this->input->post('supplier_id');  remove this column from cash_payment table & add in cash_payment_details table
        $status = $this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        elseif ($status==3)
        {
            $data1['status']= $status;
            $data1['posted_by']=$_SESSION['login_detail_id'];
            $data1['date_posted']=date('Y-m-d');
        }

        $school_id = $_SESSION['school_id'];
        $this->db->where('cash_payment_id', $cash_payment_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.cash_payment',$data1);
        
        //_______________cash_payment_details_______________
        $cash_payment_details_id_arr = $this->input->post('cash_payment_details_id');
        $expense_coa_id = $this->input->post('expense_coa_id');
        $description = $this->input->post('description');
        $amount = $this->input->post('amount');
        $filename = $_FILES['attachment'];

        $details_id_arr_second = array();
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $cash_payment_details_id = $cash_payment_details_id_arr[$i];
                $data2['cash_payment_id']=$cash_payment_id;
                $data2['description']=$description[$i];
                $data2['amount']=$amount[$i];
                $data2['expense_coa_id']=$expense_coa_id[$i];
                // print_r($expense_coa_id[$i]);exit;
                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'cash_payment', 'cpv');
                }
                if (!empty($cash_payment_details_id))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('cash_payment_details_id', $cash_payment_details_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.cash_payment_details',$data2);
                    $details_id_arr_second[] = $cash_payment_details_id;
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.cash_payment_details',$data2);
                    $new_cash_payment_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_cash_payment_details_id;
                }
            }
        }
        
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        foreach ($result as $key => $value)
        {
            $this->db->where('cash_payment_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.cash_payment_details');
        }
        
        if ($status==3)
        {
            $supplier_id = $this->input->post('supplier_id');
            $get_cash_voucher_coa = get_cash_voucher_coa_settings('Payment');
            if ($get_cash_voucher_coa > 0)
            {
                $detials_qur = "select cpd.*   from ".get_school_db().".cash_payment_details cpd 
                 where cpd.cash_payment_id=".$cash_payment_id." and cpd.school_id = ".$_SESSION['school_id']." ";
                $detials_arr = $this->db->query($detials_qur)->result_array();


                    //---- Details Credit Entries in Ledger ----//
                    foreach ($detials_arr as $key1 => $value1)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $value1['description'];
                        $data3['debit'] = $value1['amount'];
                        $total_amount+=$value1['amount'];
                        $data3['credit'] = 0;
                        $data3['entry_type'] = 17;
                        $data3['type_id'] = $value1['cash_payment_id'];
                        $data3['coa_id'] = $value1['expense_coa_id'];
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                    }
                    
                    //---- Details Debit Entries in Ledger ----//
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = "Cash Has Been Credited With Amount - " . $total_amount . ' ('.$value1['description'].')';
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 17;
                    $data4['type_id'] = $cash_payment_id;
                    $data4['coa_id'] = $get_cash_voucher_coa;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_cash_voucher_setting_is_missing'));
            }
        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url() . 'vouchers/cash_payment_voucher_listing');
    }
    
    //____________________________________________________
    //____________________________________________________
    //______________purchase_voucher______________________
    //____________________________________________________
    
    function purchase_voucher_listing()
    {
        //_____________filters work start_______________
        $apply_filter=$this->input->post('apply_filter');
        $search=$this->input->post('search');
        $supplier_id=$this->input->post('supplier_id');

        $search_filter="";
        if ( isset($search) && $search != "")
        {
            $search_filter =" AND (
            pv.voucher_number LIKE '%".$search."%' OR
            pvd.description LIKE '%".$search."%' OR
            pvd.amount LIKE '%".$search."%'
            )";
        }
        $supplier_filter="";
        if (isset($supplier_id) && $supplier_id != "")
        {
            $supplier_filter = " And pv.supplier_id = ".$supplier_id."";
        }
        
        //______________filters work end________________
        
        //query will be here
        $data = array();
        $purchase_voucher_qry ="SELECT pv.* , sum(pvd.amount) as total FROM " . get_school_db() . ".purchase_voucher pv
        inner join " . get_school_db() . ".purchase_voucher_details
        pvd on pv.purchase_voucher_id = pvd.purchase_voucher_id 
        WHERE pv.school_id = ".$_SESSION['school_id']." 
        ".$search_filter." ".$supplier_filter."
        group by pv.purchase_voucher_id";

        $data = $this->db->query($purchase_voucher_qry)->result_array();

        $page_data['apply_filter'] =$apply_filter;
        $page_data['search'] =$search;
        $page_data['supplier_id'] =$supplier_id;
        
        
        $page_data['data'] = $data;
        $page_data['page_name'] = 'purchase_voucher_listing';
        $page_data['page_title'] = get_phrase('purchase_voucher_listing');
        $this->load->view('backend/index', $page_data);
    }
    function purchase_voucher()
    {
        $page_data['page_name'] = 'purchase_voucher';
        $page_data['page_title'] = get_phrase('purchase_voucher');
        $this->load->view('backend/index', $page_data);
    }
    function add_purchase_voucher()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $data['description'] = $this->input->post('description');
        $counter = count($data['description']);
        
        //_______________purchase_voucher________________
        $data1['voucher_number'] = cash_receipt_number();
        $data1['voucher_date'] = date_slash($this->input->post('voucher_date'));
        $data1['supplier_id'] = $this->input->post('supplier_id');
        
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        elseif ($status==3)
        {
            $data1['status']= $status;
            $data1['posted_by']=$_SESSION['login_detail_id'];
            $data1['date_posted']=date('Y-m-d');
        }
        $data1['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.purchase_voucher',$data1);

        $insert_id = $this->db->insert_id();

        //_____________purchase_voucher_details__________

        $description = $this->input->post('description');
        $amount = $this->input->post('amount');
        $bill_number = $this->input->post('bill_number');
        $debit_coa_id = $this->input->post('debit_coa_id');
        $quantity = $this->input->post('quantity');
        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['purchase_voucher_id']=$insert_id;
                $data2['description']=$description[$i];
                $data2['amount']=$amount[$i];
                $data2['bill_number'] = $bill_number[$i];
                $data2['debit_coa_id'] = $debit_coa_id[$i];
                if ($filename != "")
                {
                    //$ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $data2['attachment'] = multi_file_upload('attachment', $i,'purchase_voucher', 'pv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.purchase_voucher_details',$data2);
                $pvd_insert_id = $this->db->insert_id();
                    
                // Add Stock Entry
                $stock_data['coa_id'] = $debit_coa_id[$i];
                $stock_data['purchase_voucher_details_id'] = $pvd_insert_id;
                $stock_data['qty'] = $quantity[$i];
                $stock_data['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.stock',$stock_data);
                
            }
        }
        //_____________journal_entry________________________
        if ($status==3)
        {
            
            $supplier_id = $this->input->post('supplier_id');
            $supplier_detail = get_supplier_details($supplier_id);
            $coa_cash_deposit = $supplier_detail[0]['coa_cash_deposit'];
            // $coa_cash_deposit = $supplier_id;
            $get_cash_voucher_coa = get_cash_voucher_coa_settings('Payment');
            if ($coa_cash_deposit>0)
            {
                $detials_qur = "select pvd.*   from ".get_school_db().".purchase_voucher_details pvd 
                 where pvd.purchase_voucher_id=".$insert_id." and pvd.school_id = ".$_SESSION['school_id']." ";
                $detials_arr = $this->db->query($detials_qur)->result_array();

                    //---- Details Credit Entries in Ledger ----//
                    foreach ($detials_arr as $key1 => $value1)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $value1['description'];
                        $data3['debit'] = 0;
                        $total_amount+=$value1['amount'];
                        $data3['credit'] = $value1['amount'];
                        $data3['entry_type'] = 18;
                        $data3['type_id'] = $value1['purchase_voucher_id'];
                        $data3['coa_id'] = $get_cash_voucher_coa;
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                    }
                    
                    //---- Details Debit Entries in Ledger ----//
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = "Voucher Number : ".$data1['voucher_number']." Supplier : ".$supplier_detail[0]['name'];
                    $data4['debit'] = $total_amount;
                    $data4['credit'] = 0;
                    $data4['entry_type'] = 18;
                    $data4['type_id'] = $insert_id;
                    $data4['coa_id'] = $coa_cash_deposit;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_supplier_coa_is_missing'));
            }
        }
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url().'vouchers/purchase_voucher_listing');
    }
    function purchase_voucher_edit($pur_voucher_id=0)
    {
        $purchase_voucher_id = str_decode($pur_voucher_id);
        
        $purchase_voucher_edit_qur ="select pv.* , pvd.* , st.* from ".get_school_db().".purchase_voucher pv inner join ".get_school_db().".purchase_voucher_details pvd on pv.purchase_voucher_id = pvd.purchase_voucher_id inner join ".get_school_db().".stock st on st.purchase_voucher_details_id = pvd.purchase_voucher_details_id WHERE pv.purchase_voucher_id=".$purchase_voucher_id." and pv.school_id = ".$_SESSION['school_id']." ";
        $row_edit = $this->db->query($purchase_voucher_edit_qur)->result_array();
        $page_data['row_edit'] = $row_edit;

        $page_data['page_name'] = 'purchase_voucher_edit';
        $page_data['page_title'] = get_phrase('purchase_voucher_edit');
        $this->load->view('backend/index', $page_data);
    }
    function purchase_voucher_update()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $voucher_number = $this->input->post('voucher_number');
        $data['description'] = $this->input->post('description');
        
        $bill_number = $this->input->post('bill_number');
        $counter = count($bill_number); 

        //____get existing bank_receipt_details_id's before update_
        $purchase_voucher_id = $this->input->post('purchase_voucher_id');
        $qur = "select purchase_voucher_details_id from ".get_school_db().".purchase_voucher_details where purchase_voucher_id = ".$purchase_voucher_id." and school_id = ".$_SESSION['school_id']." ";

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['purchase_voucher_details_id'];
        }
        //_______________purchase_voucher_______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $data1['supplier_id']=$this->input->post('supplier_id');
        $status = $this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        elseif ($status==3)
        {
            $data1['status']= $status;
            $data1['posted_by']=$_SESSION['login_detail_id'];
            $data1['date_posted']=date('Y-m-d');
        }

        $school_id = $_SESSION['school_id'];
        $this->db->where('purchase_voucher_id', $purchase_voucher_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.purchase_voucher',$data1);
        
        //_______________purchase_voucher_details_______________
        $purchase_voucher_details_id = $this->input->post('purchase_voucher_details_id');
        $description = $this->input->post('description');
        $amount = $this->input->post('amount');
        $filename = $_FILES['attachment'];
        $bill_number = $this->input->post('bill_number');
        $debit_coa_id = $this->input->post('debit_coa_id');
        $quantity = $this->input->post('quantity');
        
        $details_id_arr_second = array();
        
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $purchase_voucher_details_id = $purchase_voucher_details_id;
                $data2['purchase_voucher_id']=$purchase_voucher_id;
                $data2['description']=$description[$i];
                $data2['amount']=$amount[$i];
                $data2['bill_number'] = $bill_number[$i];
                $data2['debit_coa_id'] = $_POST['debit_coa_id'][$i];
                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'purchase_voucher', 'pv');
                }

                if (!empty($purchase_voucher_details_id[$i]))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('purchase_voucher_details_id', $purchase_voucher_details_id[$i]);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.purchase_voucher_details',$data2);
                    $details_id_arr_second[] = $purchase_voucher_details_id[$i];
                    
                    // Add Stock Entry
                    $stock_data['coa_id'] = $_POST['debit_coa_id'][$i];
                    $stock_data['qty'] = $quantity[$i];
                    $stock_data['school_id'] = $_SESSION['school_id'];
                    $this->db->where('purchase_voucher_details_id', $purchase_voucher_details_id[$i]);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.stock',$stock_data);
                    
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.purchase_voucher_details',$data2);
                    $new_purchase_voucher_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_purchase_voucher_details_id;
                    
                    // Add Stock Entry
                    $stock_data['coa_id'] = $_POST['debit_coa_id'][$i];
                    $stock_data['purchase_voucher_details_id'] = $new_purchase_voucher_details_id;
                    $stock_data['qty'] = $quantity[$i];
                    $stock_data['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.stock',$stock_data);
                }
                
            }
        }
        
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        // print_r($details_id_arr_first);
        // echo "<br>";
        // print_r($details_id_arr_second);
        // echo "<br>";
        // print_r($result);
        // exit;
        foreach ($result as $key => $value)
        {
            $this->db->where('purchase_voucher_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.purchase_voucher_details');
            
            $this->db->where('purchase_voucher_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.stock');
        }
        
        if ($status==3)
        {
            $supplier_id = $this->input->post('supplier_id');
            $supplier_detail = get_supplier_details($supplier_id);
            $coa_purchase_voucher = $supplier_detail[0]['coa_purchase_voucher'];
            
            if ($coa_purchase_voucher > 0)
            {
                $detials_qur = "select pvd.*   from ".get_school_db().".purchase_voucher_details pvd 
                 where pvd.purchase_voucher_id=".$purchase_voucher_id." and pvd.school_id = ".$_SESSION['school_id']." ";
                $detials_arr = $this->db->query($detials_qur)->result_array();
                
                    //---- Details Credit Entries in Ledger ----//
                    foreach ($detials_arr as $key1 => $value1)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $value1['description'];
                        $data3['debit'] = $value1['amount'];
                        $total_amount+=$value1['amount'];
                        $data3['credit'] = 0;
                        $data3['entry_type'] = 19;
                        $data3['type_id'] = $purchase_voucher_id;
                        $data3['coa_id'] = $value1['debit_coa_id'];
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                        
                    }
                    
                    
                    //---- Details Debit Entries in Ledger ----//
                    $data4['school_id'] = $_SESSION['school_id'];
                    $data4['entry_date'] = date('Y-m-d H:i:s');
                    $data4['detail'] = "Voucher Number : ".$data1['voucher_number']." Supplier / Expanse : ".$supplier_detail[0]['name'];
                    $data4['debit'] = 0;
                    $data4['credit'] = $total_amount;
                    $data4['entry_type'] = 19;
                    $data4['type_id'] = $purchase_voucher_id;
                    $data4['coa_id'] = $coa_purchase_voucher;

                    $this->db->insert(get_school_db().'.journal_entry',$data4);
            }
            else
            {
                $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_supplier_coa_is_missing'));
            }
        }
        
        
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url() . 'vouchers/purchase_voucher_listing');
    }
    function purchase_voucher_delete($pur_voucher_id=0)
    {
        $purchase_voucher_id = str_decode($pur_voucher_id);
        $purchase_voucher_delete_qur ="select * from ".get_school_db().".purchase_voucher_details WHERE purchase_voucher_id=".$purchase_voucher_id." and attachment <> '' and school_id = ".$_SESSION['school_id']." ";
        $row_delete = $this->db->query($purchase_voucher_delete_qur)->result_array();
        foreach ($row_delete as $key => $value)
        {
            if (isset($value['attachment']))
            {
                $del_location = system_path($value['attachment'], 'purchase_voucher');
                file_delete($del_location);
            }
        }
        
        $this->db->where('purchase_voucher_id', $purchase_voucher_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.purchase_voucher_details');

        $this->db->where('purchase_voucher_id', $purchase_voucher_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.purchase_voucher');

        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
        redirect(base_url() . 'vouchers/purchase_voucher_listing');
        exit();
    }
    function purchase_voucher_detail($purch_voucher_id=0)
    {
        $purchase_voucher_id = str_decode($purch_voucher_id);
        $purchase_voucher_qur = "select * from ".get_school_db().".purchase_voucher where purchase_voucher_id=".$purchase_voucher_id." and school_id = ".$_SESSION['school_id']." ";
        $purchase_voucher_arr = $this->db->query($purchase_voucher_qur)->result_array();

        $purchase_voucher_detail_qur ="select pvd.*,st.* from ".get_school_db().".purchase_voucher_details pvd inner join ".get_school_db().".stock st on st.purchase_voucher_details_id = pvd.purchase_voucher_details_id where pvd.purchase_voucher_id=".$purchase_voucher_id." and pvd.school_id = ".$_SESSION['school_id']." ";
        $purchase_voucher_detail_arr = $this->db->query($purchase_voucher_detail_qur)->result_array();

        $page_data['purchase_voucher_detail_arr'] = $purchase_voucher_detail_arr;
        $page_data['purchase_voucher_qur'] = $purchase_voucher_arr;
        $page_data['page_name'] = 'purchase_voucher_detail';
        $page_data['page_title'] = get_phrase('purchase_voucher_detail');
        $this->load->view('backend/index', $page_data);
    }
    function purchase_voucher_print($purch_voucher_id=0)
    {
        $purchase_voucher_id = str_decode($purch_voucher_id);
        $purchase_voucher_detail_qur ="select pv.* , pvd.*,st.* from ".get_school_db().".purchase_voucher pv
        INNER join ".get_school_db().".purchase_voucher_details pvd on pv.purchase_voucher_id = pvd.purchase_voucher_id
        inner join ".get_school_db().".stock st on st.purchase_voucher_details_id = pvd.purchase_voucher_details_id
        where pv.purchase_voucher_id=".$purchase_voucher_id." and
        pv.school_id = ".$_SESSION['school_id']." ";
        $purchase_voucher_detail_arr = $this->db->query($purchase_voucher_detail_qur)->result_array();
        $page_data['purchase_voucher_detail_arr'] = $purchase_voucher_detail_arr;
        $page_data['page_name']  = 'purchase_voucher_print';
        $page_data['page_title'] = get_phrase('purchase_voucher_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('Pdf');
        $view = 'backend/admin/purchase_voucher_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
    
    // Get Supplier Oustanding
    function fetch_supplier_outstanding()
    {
        $supplier_id = $this->input->post("supplier_id");
        $display = "";
        $purchase_voucher_detail_qur = "select pv.* from ".get_school_db().".purchase_voucher pv where pv.supplier_id=".$supplier_id." and pv.school_id = ".$_SESSION['school_id']." AND pv.payment_status = 0 ";
        $purchase_voucher_detail_arr = $this->db->query($purchase_voucher_detail_qur)->result_array();
        $display .= '<option value="">Select PV</option>';
        foreach($purchase_voucher_detail_arr as $data):
            $purchase_voucher_amount = "select SUM(pvd.amount) AS TotalAmount from ".get_school_db().".purchase_voucher_details pvd where pvd.purchase_voucher_id=".$data["purchase_voucher_id"]." ";
            $purchase_voucher_amount_arr = $this->db->query($purchase_voucher_amount)->row();
            $display .= '
                <option value="'.$data["purchase_voucher_id"].'"> Voucher No#: '.$data["voucher_number"].' - Voucher Date: '.date_view($data["voucher_date"]).' - Total Amount: '.$purchase_voucher_amount_arr->TotalAmount.'
                </option>
            ';
        endforeach;
        
        echo $display;
    }
    
     // Get Supplier Details
    function fetch_supplier_details()
    {
        $supplier_id = $this->input->post("supplier_id");
        $display = "";
        $fetch_supplier_details = "SELECT ntn_number,filing_status,supplier_type,supplier_percentage FROM " . get_school_db() . ".supplier WHERE school_id = " . $_SESSION['school_id'] . " AND supplier_id = " . $supplier_id . "";
        $fetch_supplier_details_arr = $this->db->query($fetch_supplier_details)->result_array();
        $display .= '
           <input type="hidden" name="ntn_number" value="'.$fetch_supplier_details_arr[0]["ntn_number"].'">
           <input type="hidden" name="filing_status" value="'.$fetch_supplier_details_arr[0]["filing_status"].'">
           <input type="hidden" name="supplier_type" value="'.$fetch_supplier_details_arr[0]["supplier_type"].'">
           <input type="hidden" name="supplier_percentage" value="'.$fetch_supplier_details_arr[0]["supplier_percentage"].'">
          <table class="table table-bordered">
            <tr style="background:#0992c9;">
                <th class="text_white">NTN Number</th>
                <th class="text_white">Filling Status</th>
                <th class="text_white">Supplier Status</th>
                <th class="text_white">Tax Percentage</th>
            </tr>
            <tr>
                <td>'.$fetch_supplier_details_arr[0]["ntn_number"].'</td>
                <td>'.filing_status($fetch_supplier_details_arr[0]["filing_status"],1).'</td>
                <td>'.supplier_type($fetch_supplier_details_arr[0]["supplier_type"],1).'</td>
                <td>'.$fetch_supplier_details_arr[0]["supplier_percentage"].'%</td>
            </tr>
          </table>  
        ';
        
        echo $display;
    }
    
    function fetch_pv_oustanding_amount()
    {
        $json = array();
        $pv_id = $this->input->post('pv_id');
        $purchase_voucher_amount = "select SUM(pvd.amount) AS TotalAmount from ".get_school_db().".purchase_voucher_details pvd where pvd.purchase_voucher_id=".$pv_id." ";
        $purchase_voucher_amount_arr = $this->db->query($purchase_voucher_amount)->row();
        
        if($purchase_voucher_amount_arr->TotalAmount == 0)
        {
            $json['error'] = "purchase voucher amount is missig";
            
        }else{
            $json['pv_amount'] = $purchase_voucher_amount_arr->TotalAmount;
        }
        
        echo json_encode($json);
    }
    
    
    //____________________________________________________
    //____________________________________________________
    //______________journal_voucher_______________________
    //____________________________________________________
    
    function journal_voucher_listing()
    {
        
        //_____________filters work start_______________
        $apply_filter = $this->input->post('apply_filter');
        $status = $this->input->post('status');
        
        $start_date=$this->input->post('start_date');
        $end_date=$this->input->post('end_date');
        
        $status_filter="";
        if ($status != "" )
        {
            $status_filter = " And status = ".$status." ";
        }
        
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $start_date = date_slash($start_date);
            $end_date = date_slash($end_date);
            $date_filter = " And voucher_date BETWEEN '".$start_date."' And '".$end_date."'";
        }
        $journal_voucher_arr = $this->db->query("SELECT * FROM " . get_school_db() . ".journal_voucher WHERE school_id = ".$_SESSION['school_id']."  ".$status_filter." ".$date_filter." ");

        $page_data['apply_filter'] = $apply_filter;
        $page_data['status'] = $status;
        $page_data['start_date'] = $start_date;
        $page_data['end_date'] = $end_date;
        
        $page_data['data'] = $journal_voucher_arr;
        $page_data['page_name'] = 'journal_voucher_listing';
        $page_data['page_title'] = get_phrase('journal_voucher_listing');
        
        $this->load->view('backend/index', $page_data);
    }
    
    function journal_voucher()
    {
        $page_data['page_name'] = 'journal_voucher';
        $page_data['page_title'] = get_phrase('journal_voucher');
        $this->load->view('backend/index', $page_data);
    }
    
    function add_journal_voucher()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $data1['credit_coa_id'] = $this->input->post('credit_coa_id');
        $data1['amount'] = $this->input->post('amount');
        $data1['description'] = $this->input->post('description');
        $counter = count($data1['description']);
        
        //_______________journal_voucher________________
        $data['voucher_number'] = journal_voucher_number();
        $data['voucher_date'] = date_slash($this->input->post('voucher_date'));
        
        $status=$this->input->post('status');
        if ($status==1)
        {
           $data['status']= $status;
        }elseif ($status==2)
        {
            $data['status']= $status;
            $data['submitted_by']=$_SESSION['login_detail_id'];
            $data['date_submitted']=date('Y-m-d'); 
        }
        elseif ($status==3)
        {
            $data['status']= $status;
            $data['posted_by']=$_SESSION['login_detail_id'];
            $data['date_posted']=date('Y-m-d');
        }
        $data['school_id'] = $_SESSION['school_id'];
        $this->db->insert(get_school_db().'.journal_voucher',$data);

        $insert_id = $this->db->insert_id();

        //_____________journal_voucher_details__________
        
        $debit_coa_id = $this->input->post('debit_coa_id');
        $credit_coa_id = $this->input->post('credit_coa_id');
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        
        $filename = $_FILES['attachment'];
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $data2['journal_voucher_id']=$insert_id;
                $data2['debit_coa_id'] = $debit_coa_id[$i];
                $data2['credit_coa_id'] = $credit_coa_id[$i];
                
                $data2['amount']=$amount[$i];
                $data2['description']=$description[$i];
                if ($filename != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'journal_voucher', 'jv');
                }
                $data2['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db().'.journal_voucher_details',$data2);
            }
        }
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_added_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url().'vouchers/journal_voucher_listing');
    }
    
    function journal_voucher_detail($journal_vou_id= 0)
    {
        $journal_voucher_id = str_decode($journal_vou_id);
        $journal_voucher_qur = "select * from ".get_school_db().".journal_voucher where journal_voucher_id=".$journal_voucher_id." and school_id = ".$_SESSION['school_id']." ";
        $journal_voucher_arr = $this->db->query($journal_voucher_qur)->result_array();


        $journal_voucher_detail_qur ="select * from ".get_school_db().".journal_voucher_details where journal_voucher_id=".$journal_voucher_id." and school_id = ".$_SESSION['school_id']." ";
        $journal_voucher_detail_arr = $this->db->query($journal_voucher_detail_qur)->result_array();

        $page_data['journal_voucher_arr'] = $journal_voucher_arr;

        $page_data['journal_voucher_detail_arr'] = $journal_voucher_detail_arr;
        $page_data['page_name'] = 'journal_voucher_detail';
        $page_data['page_title'] = get_phrase('journal_voucher_detail');
        $this->load->view('backend/index', $page_data);
    }
    
    function journal_voucher_edit($journal_vou_id= 0)
    {
        $journal_voucher_id = str_decode($journal_vou_id);
        
        $journal_voucher_edit_qur ="select jv.* , jvd.* from ".get_school_db().".journal_voucher jv inner join ".get_school_db().".journal_voucher_details jvd on jv.journal_voucher_id = jvd.journal_voucher_id WHERE jv.journal_voucher_id=".$journal_voucher_id." and jv.school_id = ".$_SESSION['school_id']." ";
        $row_edit = $this->db->query($journal_voucher_edit_qur)->result_array();
        $page_data['row_edit'] = $row_edit;

        $page_data['page_name'] = 'journal_voucher_edit';
        $page_data['page_title'] = get_phrase('journal_voucher_edit');
        $this->load->view('backend/index', $page_data);
        
    }
    
    function journal_voucher_update()
    {
        
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------
        
        $voucher_number = $this->input->post('voucher_number');
        
        $debit_coa_id = $this->input->post('debit_coa_id');
        
        $data['description'] = $this->input->post('description');
        
        $counter = count($debit_coa_id); 

        //____get existing journal_voucher_details_id's before update_
        $journal_voucher_id = $this->input->post('journal_voucher_id');
        $qur = "select journal_voucher_details_id from ".get_school_db().".journal_voucher_details where journal_voucher_id = ".$journal_voucher_id." and school_id = ".$_SESSION['school_id']." ";

        $arr = $this->db->query($qur)->result_array();
        $details_id_arr_first = array();
        foreach ($arr as $key => $value)
        {
            $details_id_arr_first[]= $value['journal_voucher_details_id'];
        }
        //_______________journal_voucher_______________
        $data1['voucher_number']=$voucher_number;
        $data1['voucher_date']=date_slash($this->input->post('voucher_date'));
        $status = $this->input->post('status');
        if ($status==1)
        {
           $data1['status']= $status;
        }elseif ($status==2)
        {
            $data1['status']= $status;
            $data1['submitted_by']=$_SESSION['login_detail_id'];
            $data1['date_submitted']=date('Y-m-d'); 
        }
        elseif ($status==3)
        {
            $data1['status']= $status;
            $data1['posted_by']=$_SESSION['login_detail_id'];
            $data1['date_posted']=date('Y-m-d');
        }

        $school_id = $_SESSION['school_id'];
        $this->db->where('journal_voucher_id', $journal_voucher_id);
        $this->db->where('school_id', $school_id); 
        $this->db->update(get_school_db().'.journal_voucher',$data1);
        
        //_______________journal_voucher_details_______________
        $journal_voucher_details_id_arr = $this->input->post('journal_voucher_details_id');
        
        $debit_coa_id = $this->input->post('debit_coa_id');
        $credit_coa_id = $this->input->post('credit_coa_id');
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        $filename = $_FILES['attachment'];
        
        $details_id_arr_second = array();
        
        for ($i=0; $i < $counter ; $i++)
        {
            if (!empty($description[$i]) && !empty($amount[$i]))
            {
                $journal_voucher_details_id = $journal_voucher_details_id_arr[$i];
                $data2['journal_voucher_id']=$journal_voucher_id;
                $data2['debit_coa_id'] = $_POST['debit_coa_id'][$i];
                $data2['credit_coa_id'] = $_POST['credit_coa_id'][$i];
                $data2['amount']=$amount[$i];
                $data2['description']=$description[$i];
                if ($filename['name'][$i] != "")
                {
                    $data2['attachment'] = multi_file_upload('attachment', $i,'journal_voucher', 'jv');
                }
                if (!empty($journal_voucher_details_id))
                {
                    $school_id = $_SESSION['school_id'];
                    $this->db->where('journal_voucher_details_id', $journal_voucher_details_id);
                    $this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db().'.journal_voucher_details',$data2);
                    $details_id_arr_second[] = $journal_voucher_details_id;
                }
                else
                {
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.journal_voucher_details',$data2);
                    $new_journal_voucher_details_id = $this->db->insert_id();
                    $details_id_arr_second[] =$new_journal_voucher_details_id;
                }
                
            }
        }
        
        $result=array_diff($details_id_arr_first,$details_id_arr_second);
        foreach ($result as $key => $value)
        {
            $this->db->where('journal_voucher_details_id', $value);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->delete(get_school_db().'.journal_voucher_details');
        }
        
        if ($status==3)
        {
            
                $detials_qur = "select * from ".get_school_db().".journal_voucher_details 
                 where journal_voucher_id=".$journal_voucher_id." and school_id = ".$_SESSION['school_id']." ";
                $detials_arr = $this->db->query($detials_qur)->result_array();
                
                    //---- Details Credit Entries in Ledger ----//
                    foreach ($detials_arr as $key1 => $value1)
                    {
                        $data3['school_id'] = $_SESSION['school_id'];
                        $data3['entry_date'] = date('Y-m-d H:i:s');
                        $data3['detail'] = $value1['description'];
                        $data3['debit'] = $value1['amount'];
                        $data3['credit'] = 0;
                        $data3['entry_type'] = 21;
                        $data3['type_id'] = $journal_voucher_id;
                        $data3['coa_id'] = $value1['debit_coa_id'];
                        $this->db->insert(get_school_db().'.journal_entry',$data3);
                        
                        //---- Details Debit Entries in Ledger ----//
                        $data4['school_id'] = $_SESSION['school_id'];
                        $data4['entry_date'] = date('Y-m-d H:i:s');
                        $data4['detail'] = $value1['description'];
                        $data4['debit'] = 0;
                        $data4['credit'] = $value1['amount'];;
                        $data4['entry_type'] = 21;
                        $data4['type_id'] = $journal_voucher_id;
                        $data4['coa_id'] = $value1['credit_coa_id'];
    
                        $this->db->insert(get_school_db().'.journal_entry',$data4);
                        
                    }
        }
        
        
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated', get_phrase('transection_failed'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));
            $this->db->trans_commit();
        }
        
        redirect(base_url() . 'vouchers/journal_voucher_listing');
    }
    
    function journal_voucher_delete($journal_vou_id= 0)
    {
        $journal_voucher_id = str_decode($journal_vou_id);
        $journal_voucher_delete_qur ="select * from ".get_school_db().".journal_voucher_details WHERE journal_voucher_id=".$journal_voucher_id." and attachment <> '' and school_id = ".$_SESSION['school_id']." ";
        $row_delete = $this->db->query($journal_voucher_delete_qur)->result_array();
        foreach ($row_delete as $key => $value)
        {
            if (isset($value['attachment']))
            {
                $del_location = system_path($value['attachment'], 'journal_voucher');
                file_delete($del_location);
            }
        }
        
        $this->db->where('journal_voucher_id', $journal_voucher_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.journal_voucher_details');

        $this->db->where('journal_voucher_id', $journal_voucher_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db().'.journal_voucher');

        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_sucessfully'));
        redirect(base_url() . 'vouchers/journal_voucher_listing');
        
    }
    
    function journal_voucher_print($journal_vou_id=0)
    {
        $journal_voucher_id = str_decode($journal_vou_id);
        $journal_voucher_detail_qur ="select jv.* , jvd.* from ".get_school_db().".journal_voucher jv
        INNER join ".get_school_db().".journal_voucher_details jvd on jv.journal_voucher_id = jvd.journal_voucher_id
        where jv.journal_voucher_id=".$journal_voucher_id." and
        jv.school_id = ".$_SESSION['school_id']." ";
        $journal_voucher_detail_arr = $this->db->query($journal_voucher_detail_qur)->result_array();
        $page_data['journal_voucher_detail_arr'] = $journal_voucher_detail_arr;
        $page_data['page_name']  = 'journal_voucher_print';
        $page_data['page_title'] = get_phrase('journal_voucher_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('Pdf');
        $view = 'backend/admin/journal_voucher_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
     
    
}

