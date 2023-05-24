<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
// session_start();


class Transection_account extends CI_Controller{

    function __construct(){

        parent::__construct();

        if($_SESSION['user_login'] != 1)
            redirect('login');

        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->coa_list = array();
        $this->load->helper('coa');
        $this->load->helper('voucher');

    }
    public function index(){

    }

    function account_transection($action="",$id="")
    {

        if($action=="add_edit"){
            $data['title']=$this->input->post('title');
            $data['isprocessed']=$this->input->post('isprocessed');
            $transection_id=$this->input->post('transection_id');
            $data['voucher_num']=$this->input->post('voucher_num');
            $data['method']=$this->input->post('method');
            $data['coa_id']=$this->input->post('coa_id');
            $data['amount']=$this->input->post('amount');
            $date =$this->input->post('date');
            $date_arry=explode('/',$date);
            $data['date']=$date_arry[2].'-'.$date_arry[1].'-'.$date_arry[0];
//$data['date']=date_slash($this->input->post('date'));
            $data['detail']=$this->input->post('detail');
            $data['type']=$this->input->post('type');
            $data['receipt_num']=$this->input->post('receipt_num');
            $school_id=$_SESSION['school_id'];

            if($transection_id!=""){
                $this->db->where('transection_id',$transection_id);
                $this->db->where('school_id',$school_id);
                $this->db->update(get_school_db().'.account_transection',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
            }

            else{
                $data['school_id']=$school_id;
                $this->db->insert(get_school_db().'.account_transection',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));
            }

            redirect(base_url() . 'transection_account/account_transection');

        }

        if($action=='delete'){

            $this->db->where('transection_id', $id);
            $this->db->delete(get_school_db().'.account_transection');
            $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));

            redirect(base_url() . 'transection_account/account_transection');

        }

        $page_data['page_name']='account_transection';
        $page_data['page_title']=get_phrase('account_transection');

        $this->load->view('backend/index', $page_data);

    }

    function journal_entry(){
        $page_data['page_name']='journal_entry';
        $page_data['page_title']=get_phrase('journal_entry');
        $page_data['start_date']= $this->input->post('start_date', TRUE);
        $page_data['end_date']= $this->input->post('end_date', TRUE);
        $page_data['coa_id']= $this->input->post('coa_id', TRUE);
        $this->load->view('backend/index', $page_data);
    }
    
    function student_ledger()
    {
        $filter = $this->input->post('filter');
        
        $debit = 0;
        $credit = 0;
    
        $i = 1;
        $school_id = $_SESSION['school_id'];
    
        $qu_check = "";
        $qu_check2 = "";
        $select_coa_str = "";
        $coa_id = $this->input->post('coa_id');
        $student_select = $this->input->post('student_select');
        $section_id = $this->input->post('section_id');
        
        $select_coa_id = $coa_id;
        
        $coa_str_id = "";
        
        //global $arr;
        $coa_id_search = "";
        $student_select_search = "";
        $section_id_search = "";
        
        if (!empty($section_id)  || $section_id != "")
        {
            $section_id_search = "INNER JOIN  ". get_school_db() . ".students as s ON s.section_id = '$section_id'";
        }
        
        if ($coa_id == "" || $coa_id == 0)
        {
            $coa_id = $this->uri->segment(3);
    
            if ($coa_id == "" || $coa_id == 0)
            {
                $coa_id = 0;
    
            }
            else
            {
                $coa_id_search = $this->get_child_coa($coa_id , $arr);
            }
        }else{
            $coa_id_search = $this->get_child_coa($coa_id , $arr);
        }
        
        if ($student_select == "" || $student_select == 0)
        {
            $student_select = $this->uri->segment(3);
    
            if ($student_select == "" || $student_select == 0)
            {
                $student_select = 0;
            }
            else
            {
                $student_select_search = " AND je.student_id = '".$student_select."'";
            }
        }else{
            $student_select_search = " AND je.student_id = '".$student_select."'";
        }
    
        $start_date_post = $this->input->post('start_date' , true);
    
        if ($start_date_post == "" || $start_date_post == 0)
        {
            $start_date_post = $this->uri->segment(4);
    
            if ($start_date_post == "" || $start_date_post == 0)
            {
                $start_date_post = 0;
            }
            else
            {
                $start_date_post = str_replace('_','/',$start_date_post);
            }
        }
    
        if ($start_date_post != 0)
        {
            $start_date=date_slash($start_date_post);
            $start_date_post = str_replace('/','_',$start_date_post);
        }
    
        $end_date_post = $this->input->post('end_date', true);
    
        if ($end_date_post == "" || $end_date_post == 0)
        {
            $end_date_post = $this->uri->segment(5);
    
            if ($end_date_post == "" || $end_date_post == 0)
            {
                $end_date_post = 0;
            }
            else
            {
                $end_date_post = str_replace('_','/',$end_date_post);
            }
        }
    
        if ($end_date_post != 0)
        {
            $end_date=date_slash($end_date_post);
            $end_date_post = str_replace('/','_',$end_date_post);
        }
    
        $total_rows = array();
        $coa_id_query = "";
        $start_date_query = "";
        $end_date_query = "";
    
        if(!empty($coa_id_search))
        {
            $coa_id_query = " AND je.coa_id in (".$coa_id_search.")";
        }
    
        if ($start_date != "" && $end_date != "")
        {
            if ($start_date == $end_date)
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") = '".$start_date."'";
            }
            else
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") between '".$start_date."' AND '".$end_date."'";
            }
        }
        else
        {
            if ($start_date != "")
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") >= '$start_date' ";
            }
    
            if ($end_date != "")
            {
                $end_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") <= '$end_date'";
            }
        }
        
        if($filter == 1)
        {
    
            $journal_str = "SELECT coa.account_head, coa.account_number, je.* FROM " . get_school_db() . ".journal_entry as je
                            LEFT JOIN ".get_school_db().".student as s ON s.student_id = je.student_id
                            INNER JOIN  ". get_school_db() . ".chart_of_accounts as coa ON je.coa_id = coa.coa_id
                            INNER JOIN ".get_school_db().".school_coa as s_coa ON (s_coa.coa_id = coa.coa_id AND s_coa.school_id = ".$school_id.")
                            WHERE s.section_id = $section_id AND je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query." " . $student_select_search ."
                            ORDER BY je.entry_date ASC, je.debit DESC";
            $journal_result = $this->db->query($journal_str)->result_array();
        }else{
            $journal_result = array();
        }
        
        $page_data['journal_result']=$journal_result;
        $page_data['page_name']='student_ledger';
        $page_data['page_title']=get_phrase('student_ledger');
        $page_data['start_date']= $start_date;
        $page_data['end_date']= $end_date;
        $page_data['coa_id']= $coa_id;
        $page_data['student_select']= $student_select;
        $page_data['section_id']= $section_id;
        $this->load->view('backend/index', $page_data);
    }
    
    function student_ledger_pdf()
    {
        $filter = $this->input->post('filter');
        
        $debit = 0;
        $credit = 0;
    
        $i = 1;
        $school_id = $_SESSION['school_id'];
    
        $qu_check = "";
        $qu_check2 = "";
        $select_coa_str = "";
        $coa_id = $this->input->post('coa_id');
        $student_select = $this->input->post('student_select');
        $section_id = $this->input->post('section_id');
        $select_coa_id = $coa_id;
        
        $coa_str_id = "";
        
        //global $arr;
        $coa_id_search = "";
        $student_select_search = "";
        if ($coa_id == "" || $coa_id == 0)
        {
            $coa_id = $this->uri->segment(3);
    
            if ($coa_id == "" || $coa_id == 0)
            {
                $coa_id = 0;
    
            }
            else
            {
                $coa_id_search = $this->get_child_coa($coa_id , $arr);
            }
        }else{
            $coa_id_search = $this->get_child_coa($coa_id , $arr);
        }
        
        if ($student_select == "" || $student_select == 0)
        {
            $student_select = $this->uri->segment(3);
    
            if ($student_select == "" || $student_select == 0)
            {
                $student_select = 0;
            }
            else
            {
                $student_select_search = " AND je.student_id = '".$student_select."'";
            }
        }else{
            $student_select_search = " AND je.student_id = '".$student_select."'";
        }
    
        $start_date_post = $this->input->post('start_date' , true);
    
        if ($start_date_post == "" || $start_date_post == 0)
        {
            $start_date_post = $this->uri->segment(4);
    
            if ($start_date_post == "" || $start_date_post == 0)
            {
                $start_date_post = 0;
            }
            else
            {
                $start_date_post = str_replace('_','/',$start_date_post);
            }
        }
    
        if ($start_date_post != 0)
        {
            $start_date=$start_date_post;
            $start_date_post = str_replace('/','_',$start_date_post);
        }
    
        $end_date_post = $this->input->post('end_date', true);
    
        if ($end_date_post == "" || $end_date_post == 0)
        {
            $end_date_post = $this->uri->segment(5);
    
            if ($end_date_post == "" || $end_date_post == 0)
            {
                $end_date_post = 0;
            }
            else
            {
                $end_date_post = str_replace('_','/',$end_date_post);
            }
        }
    
        if ($end_date_post != 0)
        {
            $end_date=$end_date_post;
            $end_date_post = str_replace('/','_',$end_date_post);
        }
    
        $total_rows = array();
        $coa_id_query = "";
        $start_date_query = "";
        $end_date_query = "";
    
        if(!empty($coa_id_search))
        {
            $coa_id_query = " AND je.coa_id in (".$coa_id_search.")";
        }
    
        if ($start_date != "" && $end_date != "")
        {
            if ($start_date == $end_date)
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") = '".$start_date."'";
            }
            else
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") between '".$start_date."' AND '".$end_date."'";
            }
        }
        else
        {
            if ($start_date != "")
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") >= '$start_date' ";
            }
    
            if ($end_date != "")
            {
                $end_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") <= '$end_date'";
            }
        }
        
            $journal_str = "SELECT coa.account_head, coa.account_number, je.* FROM " . get_school_db() . ".journal_entry as je
                            LEFT JOIN ".get_school_db().".student as s ON s.student_id = je.student_id
                            INNER JOIN  ". get_school_db() . ".chart_of_accounts as coa ON je.coa_id = coa.coa_id
                            INNER JOIN ".get_school_db().".school_coa as s_coa ON (s_coa.coa_id = coa.coa_id AND s_coa.school_id = ".$school_id.")
                            WHERE s.section_id = $section_id AND je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query." " . $student_select_search ."
                            ORDER BY je.entry_date ASC, je.debit DESC";
                            
            $journal_result = $this->db->query($journal_str)->result_array();
        
        $page_data['journal_result']=$journal_result;
        $page_data['page_name']='student_ledger_pdf_report';
        $page_data['page_title']=get_phrase('student_ledger_pdf');
        $page_data['start_date']= $start_date;
        $page_data['end_date']= $end_date;
        $page_data['coa_id']= $coa_id;
        $page_data['student_select']= $student_select;
        
        $this->load->library('Pdf');
        
        $view = 'backend/admin/student_ledger_pdf_report';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    
    function ledger()
    {
        $filter = $this->input->post('filter');
        
        $debit = 0;
        $credit = 0;
    
        $i = 1;
        $school_id = $_SESSION['school_id'];
    
        $qu_check = "";
        $qu_check2 = "";
        $select_coa_str = "";
        $coa_id = $this->input->post('coa_id');
        $select_coa_id = $coa_id;
        
        $coa_str_id = "";
        
        //global $arr;
        $coa_id_search = "";
        if ($coa_id == "" || $coa_id == 0)
        {
            $coa_id = $this->uri->segment(3);
    
            if ($coa_id == "" || $coa_id == 0)
            {
                $coa_id = 0;
    
            }
            else
            {
                $coa_id_search = $this->get_child_coa($coa_id , $arr);
            }
        }else{
            $coa_id_search = $this->get_child_coa($coa_id , $arr);
        }
    
        $start_date_post = $this->input->post('start_date' , true);
    
        if ($start_date_post == "" || $start_date_post == 0)
        {
            $start_date_post = $this->uri->segment(4);
    
            if ($start_date_post == "" || $start_date_post == 0)
            {
                $start_date_post = 0;
            }
            else
            {
                $start_date_post = str_replace('_','/',$start_date_post);
            }
        }
    
        if ($start_date_post != 0)
        {
            $start_date=date_slash($start_date_post);
            $start_date_post = str_replace('/','_',$start_date_post);
        }
    
        $end_date_post = $this->input->post('end_date', true);
    
        if ($end_date_post == "" || $end_date_post == 0)
        {
            $end_date_post = $this->uri->segment(5);
    
            if ($end_date_post == "" || $end_date_post == 0)
            {
                $end_date_post = 0;
            }
            else
            {
                $end_date_post = str_replace('_','/',$end_date_post);
            }
        }
    
        if ($end_date_post != 0)
        {
            $end_date=date_slash($end_date_post);
            $end_date_post = str_replace('/','_',$end_date_post);
        }
    
        $total_rows = array();
        $coa_id_query = "";
        $start_date_query = "";
        $end_date_query = "";
    
        if(!empty($coa_id_search))
        {
            $coa_id_query = " AND je.coa_id in (".$coa_id_search.")";
        }
    
        if ($start_date != "" && $end_date != "")
        {
            if ($start_date == $end_date)
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") = '".$start_date."'";
            }
            else
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") between '".$start_date."' AND '".$end_date."'";
            }
        }
        else
        {
            if ($start_date != "")
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") >= '$start_date' ";
            }
    
            if ($end_date != "")
            {
                $end_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") <= '$end_date'";
            }
        }
        
        if($filter == 1)
        {
    
            $journal_str = "SELECT coa.account_head, coa.account_number, je.* FROM " . get_school_db() . ".journal_entry as je
                                                    INNER JOIN  ". get_school_db() . ".chart_of_accounts as coa ON je.coa_id = coa.coa_id
                                                    INNER JOIN ".get_school_db().".school_coa as s_coa ON (s_coa.coa_id = coa.coa_id AND s_coa.school_id = ".$school_id.")
                                                    WHERE je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query."
                                                    ORDER BY je.entry_date ASC, je.debit DESC";
                                                    
        
            $journal_result = $this->db->query($journal_str)->result_array();
        }else{
            $journal_result = array();
        }
        
        $page_data['journal_result']=$journal_result;
        $page_data['page_name']='ledger';
        $page_data['page_title']=get_phrase('ledger');
        $page_data['start_date']= $start_date;
        $page_data['end_date']= $end_date;
        $page_data['coa_id']= $coa_id;
        $this->load->view('backend/index', $page_data);
    }
    
    function ledger_pdf()
    {
        $debit = 0;
        $credit = 0;
    
        $i = 1;
        $school_id = $_SESSION['school_id'];
    
        $qu_check = "";
        $qu_check2 = "";
        $select_coa_str = "";
        $coa_id = $this->input->post('coa_id');
        $select_coa_id = $coa_id;
        
        $coa_str_id = "";
        $coa_id_search = "";
        if ($coa_id == "" || $coa_id == 0)
        {
            $coa_id = $this->uri->segment(3);
    
            if ($coa_id == "" || $coa_id == 0)
            {
                $coa_id = 0;
    
            }
            else
            {
                $coa_id_search = $this->get_child_coa($coa_id , $arr);
            }
        }else{
            $coa_id_search = $this->get_child_coa($coa_id , $arr);
        }
    
        $start_date_post = $this->input->post('start_date' , true);
        if ($start_date_post == "" || $start_date_post == 0)
        {
            $start_date_post = $this->uri->segment(4);
    
            if ($start_date_post == "" || $start_date_post == 0)
            {
                $start_date_post = 0;
            }
            else
            {
                $start_date_post = str_replace('_','/',$start_date_post);
            }
        }
    
        if ($start_date_post != 0)
        {
            $start_date = $start_date_post;
            $start_date_post = str_replace('/','_',$start_date_post);
        }
    
        $end_date_post = $this->input->post('end_date', true);
    
        if ($end_date_post == "" || $end_date_post == 0)
        {
            $end_date_post = $this->uri->segment(5);
    
            if ($end_date_post == "" || $end_date_post == 0)
            {
                $end_date_post = 0;
            }
            else
            {
                $end_date_post = str_replace('_','/',$end_date_post);
            }
        }
    
        if ($end_date_post != 0)
        {
            $end_date = $end_date_post;
            $end_date_post = str_replace('/','_',$end_date_post);
        }
    
        $total_rows = array();
        $coa_id_query = "";
        $start_date_query = "";
        $end_date_query = "";
    
        if(!empty($coa_id_search))
        {
            $coa_id_query = " AND je.coa_id in (".$coa_id_search.")";
        }
    
        if ($start_date != "" && $end_date != "")
        {
            if ($start_date == $end_date)
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") = '".$start_date."'";
            }
            else
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") between '".$start_date."' AND '".$end_date."'";
            }
        }
        else
        {
            if ($start_date != "")
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") >= '$start_date' ";
            }
    
            if ($end_date != "")
            {
                $end_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") <= '$end_date'";
            }
        }
        
            $journal_str = "SELECT coa.account_head, coa.account_number, je.* FROM " . get_school_db() . ".journal_entry as je
                                                    INNER JOIN  ". get_school_db() . ".chart_of_accounts as coa ON je.coa_id = coa.coa_id
                                                    INNER JOIN ".get_school_db().".school_coa as s_coa ON (s_coa.coa_id = coa.coa_id AND s_coa.school_id = ".$school_id.")
                                                    WHERE je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query."
                                                    ORDER BY je.entry_date ASC, je.debit DESC";
                                                    
            $journal_result = $this->db->query($journal_str)->result_array();
        
        $page_data['journal_result']=$journal_result;
        $page_data['page_name']='ledger';
        $page_data['page_title']=get_phrase('ledger_pdf_report');
        $page_data['start_date']= $start_date;
        $page_data['end_date']= $end_date;
        $page_data['coa_id']= $coa_id;
        // $this->load->view('backend/index', $page_data);
        
        $this->load->library('Pdf');
        
        $view = 'backend/admin/ledger_pdf_report';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        
        
    }
    
    function ledger_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Ledger Report');
        $this->load->database();
        $unpaid_arr = array();
        $d_school_id = $_SESSION['school_id'];
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $unpaid_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $unpaid_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        
        $unpaid_arr[] = 'Ledger Report';
        $unpaid_arr[] = "";
        $i = 1;
        $school_id = $_SESSION['school_id'];
    
        $qu_check = "";
        $qu_check2 = "";
        $select_coa_str = "";
        $coa_id = $this->input->post('coa_id');
        $select_coa_id = $coa_id;
        
        $coa_str_id = "";
        $coa_id_search = "";
        if ($coa_id == "" || $coa_id == 0)
        {
            $coa_id = $this->uri->segment(3);
    
            if ($coa_id == "" || $coa_id == 0)
            {
                $coa_id = 0;
    
            }
            else
            {
                $coa_id_search = $this->get_child_coa($coa_id , $arr);
            }
        }else{
            $coa_id_search = $this->get_child_coa($coa_id , $arr);
        }
    
        $start_date_post = $this->input->post('start_date' , true);
        if ($start_date_post == "" || $start_date_post == 0)
        {
            $start_date_post = $this->uri->segment(4);
    
            if ($start_date_post == "" || $start_date_post == 0)
            {
                $start_date_post = 0;
            }
            else
            {
                $start_date_post = str_replace('_','/',$start_date_post);
            }
        }
    
        if ($start_date_post != 0)
        {
            $start_date = $start_date_post;
            $start_date_post = str_replace('/','_',$start_date_post);
        }
    
        $end_date_post = $this->input->post('end_date', true);
    
        if ($end_date_post == "" || $end_date_post == 0)
        {
            $end_date_post = $this->uri->segment(5);
    
            if ($end_date_post == "" || $end_date_post == 0)
            {
                $end_date_post = 0;
            }
            else
            {
                $end_date_post = str_replace('_','/',$end_date_post);
            }
        }
    
        if ($end_date_post != 0)
        {
            $end_date = $end_date_post;
            $end_date_post = str_replace('/','_',$end_date_post);
        }
    
        $total_rows = array();
        $coa_id_query = "";
        $start_date_query = "";
        $end_date_query = "";
    
        if(!empty($coa_id_search))
        {
            $coa_id_query = " AND je.coa_id in (".$coa_id_search.")";
        }
    
        if ($start_date != "" && $end_date != "")
        {
            if ($start_date == $end_date)
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") = '".$start_date."'";
            }
            else
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") between '".$start_date."' AND '".$end_date."'";
            }
        }
        else
        {
            if ($start_date != "")
            {
                $start_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") >= '$start_date' ";
            }
    
            if ($end_date != "")
            {
                $end_date_query = " AND DATE_FORMAT(je.entry_date, \"%Y-%m-%d\") <= '$end_date'";
            }
        }
        
        $journal_str = "SELECT coa.account_head, coa.account_number, je.* FROM " . get_school_db() . ".journal_entry as je
                        INNER JOIN  ". get_school_db() . ".chart_of_accounts as coa ON je.coa_id = coa.coa_id
                        INNER JOIN ".get_school_db().".school_coa as s_coa ON (s_coa.coa_id = coa.coa_id AND s_coa.school_id = ".$school_id.")
                        WHERE je.school_id = ".$school_id." " . $coa_id_query . " ". $start_date_query . " " . $end_date_query."
                        ORDER BY je.entry_date ASC, je.debit DESC";
                                                
        $journal_result = $this->db->query($journal_str)->result_array();
        $debit_data = 0;
        $credit_data = 0;
        $balance_data = 0;
        $unpaid_arr[] = array('Type','Date','Transection Detail', 'Debit', 'Credit', 'Balance');
        foreach ($journal_result as $key => $value)
        {
            $count++;
            $record_arr = array();
            
            if ($value['debit'] < 0)
            {
                $d = (-1) * ($value['debit']);
                $debit_data =  "(" . number_format($d) . ")";
            } else
            {
                $debit_data = number_format($value['debit']);
            }
            $debit += $value['debit'];
            
            if ($value['credit'] < 0)
            {
                $c = (-1) * ($value['credit']);
                $credit_data = "(" . number_format($c) . ")";
            } else
            {
                $credit_data = number_format($value['credit']);
            }
            $credit += $value['credit'];
            $balance = $debit - $credit;
            if ($balance < 0)
            {
                $b = number_format((-1) * ($balance));
                $balance_data =  "(" . $b . ")";
            } else
            {
                $balance_data =  number_format($balance);
            }
            
            $record_arr[] = $value['entry_type'];
            $record_arr[] = date("d/m/Y" , strtotime($value['entry_date']));
            $record_arr[] = $value['detail'];
            $record_arr[] = $debit_data;
            $record_arr[] = $credit_data;
            $record_arr[] = $balance_data;

            $unpaid_arr[] = $record_arr;
        }
        //______________set logo_______________
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //$path = system_path($_SESSION['school_logo']);
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
       // $objDrawing->setCoordinates('A1');
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   
        //setOffsetX works properly
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                
        //set width, height
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:J1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[2]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A3:J3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':J'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }

        // $count = $count+$c+5;
        // $this->excel->getActiveSheet()->setCellValue('H'.$count.'',"Total: ");
        // $this->excel->getActiveSheet()->setCellValue('I'.$count.'',$total);
        // $this->excel->getActiveSheet()->setCellValue('J'.$count.'',$discount);

        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='Leder Report.xls';
        //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    function get_child_coa($coa_id , $arr="")
    {

        global $arr;
        $str_ids = "";

        $CI=& get_instance();
        $CI->load->database();
        $coa_rs = "select coa.coa_id , coa.parent_id , coa.account_head from ".get_school_db().".chart_of_accounts as coa
        INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
         where coa.parent_id=$coa_id and s_coa.school_id = ".$_SESSION['school_id']."";
        $coa_query=$CI->db->query($coa_rs)->result_array();

        if(count($coa_query)>0)
        {
            foreach ($coa_query as $coa_key=>$coa_val)
            {
                $arr[] = $coa_val['coa_id'];
                get_child_coa($coa_val['coa_id'],$arr);
            }
            $str_ids = implode($arr , ",");

            return $str_ids;
        }
        else
        {
            return $coa_id;
        }
    }

    function tranaction_list()
    {

        $data['coa_id']=$this->input->post('coa_id');
        $data['start_date']=$this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $data['type_val']=$this->input->post('type_val');

        $this->load->view("backend/admin/ajax/account_tran_ajax",$data);

    }


    function get_ledger(){

        $data['coa_id']     = $this->input->post('coa_id');
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date']   = $this->input->post('end_date');
        $this->load->view("backend/admin/ajax/ledger_ajax",$data);
    }


    function trial_balance()
    {
        $page_data['page_name']  = 'trial_balance';
        $page_data['page_title'] = get_phrase('trial_balance');
        $this->load->view('backend/index', $page_data);
    }

    function income_statement()
    {
        $page_data['page_name']  = 'income_statement';
        $page_data['page_title'] = get_phrase('income_statement');
        $this->load->view('backend/index', $page_data);
    }

    function balance_sheet(){
        $page_data['page_name']='balance_sheet';
        $page_data['page_title']=get_phrase('balance_sheet');
        $this->load->view('backend/index', $page_data);
    }


    function get_trial_balance()
    {

        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        $start_date=date_slash( $data['start_date']);
        $end_date=date_slash( $data['end_date']);
        $data['start_date1'] = $start_date;
        $data['end_date1'] = $end_date;

        $parent_coa_list=array();
        $child_coa_list = array();
        $coa_rec_str = "select coa.* from ".get_school_db().".chart_of_accounts as coa INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                        where coa.parent_id=0 AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";
                        
        

        $coa_rec=$this->db->query($coa_rec_str)->result_array();
        foreach($coa_rec as $row)
        {
            $parent_coa_list[$row['coa_id']]['coa_id'] = $row['coa_id'];
            //$parent_coa_list[$row['parent_id']]['coa_id'] = $row['coa_id'];
            $parent_coa_list[$row['coa_id']]['account_head'] = $row['account_head'];
            $parent_coa_list[$row['coa_id']]['account_number'] = $row['account_number'];
            $parent_coa_list[$row['coa_id']]['type'] = $row['account_type'];

           $coa_rec1_str = "select coa.* from ".get_school_db().".chart_of_accounts as coa INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                            where coa.parent_id=".$row['coa_id']." AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();
            foreach($coa_rec1 as $row1)
            {
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['coa_id'] = $row1['coa_id'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['account_head'] = $row1['account_head'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['account_number'] = $row1['account_number'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['type'] = $row1['account_type'];

                $this->coa_list = array();
                $this->get_coa_list($row1['coa_id'], $start_date, $end_date);
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['child_coa'] = $this->coa_list;
            }
        }

        $data['child_coa_list'] = $child_coa_list;
        $data['parent_coa_list'] = $parent_coa_list;

        // if($data['start_date'] != "" && $data['end_date'] != "" )
        // {
        //     $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".convert_date(date_slash($data['end_date']));
        // }
        // else  if($data['start_date'] != "" || $data['end_date'] != "" )
        // {
        //     $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".date("d-M-Y");
        // }
        // else
        // {
        //     $data['from_to'] = date("d-M-Y");
        // }

        $apply_filter= $this->input->post('apply_filter', TRUE);
        $data['apply_filter']= $apply_filter;
        $this->load->view("backend/admin/ajax/trial_balance_ajax",$data);
    }
    
    function trial_balance_pdf()
    {

        $data['start_date']=$this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $start_date=date_slash( $data['start_date']);
        $end_date=date_slash( $data['end_date']);

        $parent_coa_list=array();
        $child_coa_list = array();
        $coa_rec_str = "select coa.* from ".get_school_db().".chart_of_accounts as coa INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                        where coa.parent_id=0 AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";

        $coa_rec=$this->db->query($coa_rec_str)->result_array();
        foreach($coa_rec as $row)
        {
            $parent_coa_list[$row['coa_id']]['coa_id'] = $row['coa_id'];
            //$parent_coa_list[$row['parent_id']]['coa_id'] = $row['coa_id'];
            $parent_coa_list[$row['coa_id']]['account_head'] = $row['account_head'];
            $parent_coa_list[$row['coa_id']]['account_number'] = $row['account_number'];
            $parent_coa_list[$row['coa_id']]['type'] = $row['account_type'];

           $coa_rec1_str = "select coa.* from ".get_school_db().".chart_of_accounts as coa INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                            where coa.parent_id=".$row['coa_id']." AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();
            foreach($coa_rec1 as $row1)
            {
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['coa_id'] = $row1['coa_id'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['account_head'] = $row1['account_head'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['account_number'] = $row1['account_number'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['type'] = $row1['account_type'];

                $this->coa_list = array();
                $this->get_coa_list($row1['coa_id'], $start_date, $end_date);
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['child_coa'] = $this->coa_list;
            }
        }

        $data['child_coa_list'] = $child_coa_list;
        $data['parent_coa_list'] = $parent_coa_list;

        if($data['start_date'] != "" && $data['end_date'] != "" )
        {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".convert_date(date_slash($data['end_date']));
        }
        else  if($data['start_date'] != "" || $data['end_date'] != "" )
        {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".date("d-M-Y");
        }
        else
        {
            $data['from_to'] = date("d-M-Y");
        }
        
        $this->load->library('Pdf');
        $data['page_title'] = "Trial Balance Report";
        $view = 'backend/admin/trial_balance_pdf';
        $this->pdf->load_view($view,$data);
        $this->pdf->render();
        $this->pdf->stream("".$data['page_title'].".pdf");
    }
    
    function trial_balance_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Trial Balance Report');
        $this->load->database();
        $unpaid_arr = array();
        $d_school_id = $_SESSION['school_id'];
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $unpaid_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $unpaid_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        
        $unpaid_arr[] = 'Trial Balance Report';
        $unpaid_arr[] = "";
        $i = 1;
        $school_id = $_SESSION['school_id'];
    
        $data['start_date']=$this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $start_date=date_slash( $data['start_date']);
        $end_date=date_slash( $data['end_date']);

        $parent_coa_list=array();
        $child_coa_list = array();
        $coa_rec_str = "select coa.* from ".get_school_db().".chart_of_accounts as coa INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                        where coa.parent_id=0 AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";

        $coa_rec=$this->db->query($coa_rec_str)->result_array();
        foreach($coa_rec as $row)
        {
            $parent_coa_list[$row['coa_id']]['coa_id'] = $row['coa_id'];
            //$parent_coa_list[$row['parent_id']]['coa_id'] = $row['coa_id'];
            $parent_coa_list[$row['coa_id']]['account_head'] = $row['account_head'];
            $parent_coa_list[$row['coa_id']]['account_number'] = $row['account_number'];
            $parent_coa_list[$row['coa_id']]['type'] = $row['account_type'];

           $coa_rec1_str = "select coa.* from ".get_school_db().".chart_of_accounts as coa INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                            where coa.parent_id=".$row['coa_id']." AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();
            foreach($coa_rec1 as $row1)
            {
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['coa_id'] = $row1['coa_id'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['account_head'] = $row1['account_head'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['account_number'] = $row1['account_number'];
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['type'] = $row1['account_type'];

                $this->coa_list = array();
                $this->get_coa_list($row1['coa_id'], $start_date, $end_date);
                $child_coa_list[$row['coa_id']][$row1['coa_id']]['child_coa'] = $this->coa_list;
            }
        }

        $data['child_coa_list'] = $child_coa_list;
        $data['parent_coa_list'] = $parent_coa_list;
        
        $unpaid_arr[] = array('Account Title','Chart Of Account', 'Debit', 'Credit');
        foreach ($parent_coa_list as $key => $value)
        {
            $count++;
            $record_arr = array();
            
            $record_arr[] = $value['account_number'];
            $record_arr[] = $value['account_head'];
            $amount1_total = 0;
            $amount2_total = 0;
            foreach($child_coa_list[$value['coa_id']] as $row1)
            {
                $child_total = array('debit'=>0, 'credit'=>0);
                $i = 1;
                foreach ($row1['child_coa'] as $child)
                {
                    $child_total['debit']=$child_total['debit']+$child['debit'];
                    $child_total['credit']=$child_total['credit']+$child['credit'];
                }
                
                $amount = 0;
                $amount = $child_total['debit'] - $child_total['credit'];
                $total_debit  = $total_debit  + $amount;
                if ($amount<0)
                {
                    $d = (-1)*($amount);
                    $amount1_total = "(". number_format($d).")";
                }
                else
                {
                    $amount1_total = number_format($amount);
                }
                
                $amount1 = 0;
                $amount1 = $child_total['credit'] - $child_total['debit'];
                $total_credit  = $total_credit + $amount1;

                if ($amount1<0){
                    $c = (-1)*($amount1);
                    $amount2_total = "(".number_format($c).")";
                }else{
                    $amount2_total = number_format($amount1);
                }

                
                $record_arr[] = $row1['account_number'];
                $record_arr[] = $row1['account_head'];
                $record_arr[] = $amount1_total;
                $record_arr[] = $amount2_total;
            }

            $unpaid_arr[] = $record_arr;
        }
        //______________set logo_______________
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //$path = system_path($_SESSION['school_logo']);
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
       // $objDrawing->setCoordinates('A1');
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   
        //setOffsetX works properly
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                
        //set width, height
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:J1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[2]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A3:J3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':J'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }

        // $count = $count+$c+5;
        // $this->excel->getActiveSheet()->setCellValue('H'.$count.'',"Total: ");
        // $this->excel->getActiveSheet()->setCellValue('I'.$count.'',$total);
        // $this->excel->getActiveSheet()->setCellValue('J'.$count.'',$discount);

        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='Trial Balance Report.xls';
        //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    function get_income_statement()
    {

        $data['start_date']=$this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $start_date=date_slash( $data['start_date']);
        $end_date=date_slash( $data['end_date']);

        $parent_coa_list=array();
        $child_coa_list = array();

        $data_frp_array = array();
        $income_statement_str = "SELECT frs.settings_type as settings_type , coa.* FROM  ".get_school_db().".financial_reports_settings AS frs 
                                INNER JOIN  ".get_school_db().".chart_of_accounts as coa ON frs.coa_id = coa.coa_id
                                INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                WHERE frs.settings_type in ('income_stmt_sales','income_stmt_expense')
                                AND s_coa.school_id = ".$_SESSION['school_id']."";
        $income_statement_qry=$this->db->query($income_statement_str)->result_array();

        foreach ($income_statement_qry as $key=>$val)
        {
            $data_frp_array[$val['settings_type']] = $val;
        }

        foreach($data_frp_array as $key1=>$val1)
        {

            $coa_rec1_str = "SELECT coa.* FROM ".get_school_db().".chart_of_accounts as coa
                                INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                WHERE coa.parent_id=".$val1['coa_id']."
                                AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();

            foreach($coa_rec1 as $row1)
            {
                $child_coa_list[$key1][$row1['coa_id']] = $row1;

                $this->coa_list = array();
                $this->get_coa_list($row1['coa_id'], $start_date, $end_date);
                $child_coa_list[$key1][$row1['coa_id']]['child_coa'] = $this->coa_list;
            }
        }


        $data['child_coa_list'] = $child_coa_list;
        $data['data_frp_array'] = $data_frp_array;


        if($data['start_date'] != "" && $data['end_date'] != "" )
        {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".convert_date(date_slash($data['end_date']));
        }
        else  if($data['start_date'] != "" || $data['end_date'] != "" )
        {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".date("d-M-Y");
        }
        else
        {
            $data['from_to'] = date("d-M-Y");
        }

        $apply_filter= $this->input->post('apply_filter', TRUE);
        $data['apply_filter']= $apply_filter;
        $this->load->view("backend/admin/ajax/income_statement_ajax",$data);

    }
    
    function income_statement_pdf()
    {

        $start_datee = $this->input->post('start_date');
        $end_datee =$this->input->post('end_date');
        $start_date=date_slash($start_datee);
        $end_date=date_slash( $end_datee);

        $parent_coa_list=array();
        $child_coa_list = array();

        $data_frp_array = array();
        $income_statement_str = "SELECT frs.settings_type as settings_type , coa.* FROM  ".get_school_db().".financial_reports_settings AS frs 
                                INNER JOIN  ".get_school_db().".chart_of_accounts as coa ON frs.coa_id = coa.coa_id
                                INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                WHERE frs.settings_type in ('income_stmt_sales','income_stmt_expense')
                                AND s_coa.school_id = ".$_SESSION['school_id']."";
        $income_statement_qry=$this->db->query($income_statement_str)->result_array();

        foreach ($income_statement_qry as $key=>$val)
        {
            $data_frp_array[$val['settings_type']] = $val;
        }

        foreach($data_frp_array as $key1=>$val1)
        {

            $coa_rec1_str = "SELECT coa.* FROM ".get_school_db().".chart_of_accounts as coa
                                INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                WHERE coa.parent_id=".$val1['coa_id']."
                                AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();

            foreach($coa_rec1 as $row1)
            {
                $child_coa_list[$key1][$row1['coa_id']] = $row1;

                $this->coa_list = array();
                $this->get_coa_list($row1['coa_id'], $start_date, $end_date);
                $child_coa_list[$key1][$row1['coa_id']]['child_coa'] = $this->coa_list;
            }
        }


        $data['child_coa_list'] = $child_coa_list;
        $data['data_frp_array'] = $data_frp_array;


        if($data['start_date'] != "" && $data['end_date'] != "" )
        {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".convert_date(date_slash($data['end_date']));
        }
        else  if($data['start_date'] != "" || $data['end_date'] != "" )
        {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".date("d-M-Y");
        }
        else
        {
            $data['from_to'] = date("d-M-Y");
        }

        $this->load->library('Pdf');
        $data['page_title'] = "Income Statement Report";
        $view = 'backend/admin/income_statement_pdf';
        $this->pdf->load_view($view,$data);
        $this->pdf->render();
        $this->pdf->stream("".$data['page_title'].".pdf");

    }

    function get_balance_sheet()
    {
        // exit('exit');
        $data['start_date']=$this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $start_date=date_slash( $data['start_date']);
        $end_date=date_slash( $data['end_date']);
        
        $start_date1 = date('Y-m-d',strtotime($start_date));
        $end_date1 = date('Y-m-d',strtotime($end_date));

        $parent_coa_list=array();
        $child_coa_list = array();

        $data_frp_array = array();
        $income_statement_str = "SELECT frs.settings_type as settings_type , coa.* FROM  ".get_school_db().".financial_reports_settings AS frs 
                                INNER JOIN  ".get_school_db().".chart_of_accounts as coa ON frs.coa_id = coa.coa_id
                                INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                WHERE frs.settings_type in ('balance_sheet_assets','balance_sheet_liabilities','balance_sheet_capital')
                                AND s_coa.school_id = ".$_SESSION['school_id']."";

        $income_statement_qry=$this->db->query($income_statement_str)->result_array();

        foreach ($income_statement_qry as $key=>$val)
        {
            $data_frp_array[$val['settings_type']] = $val;
        }

        foreach($data_frp_array as $key1=>$val1)
        {

            $coa_rec1_str = "SELECT coa.* FROM ".get_school_db().".chart_of_accounts as coa 
                                INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                WHERE coa.parent_id=".$val1['coa_id']."
                                            AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();

            foreach($coa_rec1 as $row1)
            {
                $child_coa_list[$key1][$row1['coa_id']] = $row1;

                $this->coa_list = array();
                $this->get_coa_list($row1['coa_id'], $start_date, $end_date);
                $child_coa_list[$key1][$row1['coa_id']]['child_coa'] = $this->coa_list;
            }
        }
        
        $revenue = $this->get_coa_ids(get_parent_head_coa_id('income_stmt_sales'));
        $revenue_query = $this->db->query("SELECT SUM(credit) AS TotalCVRevenue, SUM(debit) AS TotalDVRevenue FROM ".get_school_db().".journal_entry WHERE coa_id IN('$revenue') AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') BETWEEN '$start_date1' AND '$end_date1' ")->row();
        $total_credit_rev = intval($revenue_query->TotalCVRevenue);
        $total_debit_rev = intval($revenue_query->TotalDVRevenue);
        $data['revenue'] = $total_credit_rev-$total_debit_rev;
        
        $this->coa_ids = array();
        
        $exp_id = get_parent_head_coa_id('income_stmt_expense');
        $expanse = $this->get_coa_ids($exp_id);
        $expanse_query = $this->db->query("SELECT SUM(credit) AS TotalCExpanse, SUM(debit) AS TotalDExpanse FROM ".get_school_db().".journal_entry WHERE coa_id IN('$expanse') AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') BETWEEN '$start_date1' AND '$end_date1' ")->row();
        $total_credit_exp = intval($expanse_query->TotalCExpanse);
        $total_debit_exp = intval($expanse_query->TotalDExpanse);
        
        $data['expanse'] = $total_debit_exp-$total_credit_exp;

        $data['child_coa_list'] = $child_coa_list;
        $data['data_frp_array'] = $data_frp_array;


        if($data['start_date'] != "" && $data['end_date'] != "" ) {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".convert_date(date_slash($data['end_date']));
        }
        else  if($data['start_date'] != "" || $data['end_date'] != "" )
        {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".date("d-M-Y");
        }
        else
        {
            $data['from_to'] = date("d-M-Y");
        }

        $apply_filter= $this->input->post('apply_filter', TRUE);
        $data['apply_filter']= $apply_filter;
        $this->load->view("backend/admin/ajax/balance_sheet_ajax",$data);

    }
    
    function get_coa_ids($parent_id = 0)
    {
          $school_id=$_SESSION['school_id'];
    
          $coa_rec_str = "select sc.name as school_name , coa.* from ".get_school_db().".chart_of_accounts as coa
          Inner join ".get_school_db().".school_coa as s_coa  on s_coa.coa_id = coa.coa_id
          INNER JOIN ".get_school_db().".school as sc on sc.school_id = coa.school_id
          where coa.parent_id=$parent_id
          And s_coa.school_id = $school_id";
            
          $coa_rec=$this->db->query($coa_rec_str)->result_array();
          
    
          foreach($coa_rec as $coa)
          {
          
            $this->coa_ids[] = $coa['coa_id'];  
            $coa_rec1_str = "select coa.coa_id from ".get_school_db().".chart_of_accounts as coa Inner join ".get_school_db().".school_coa as s_coa on s_coa.coa_id = coa.coa_id
                             INNER JOIN ".get_school_db().".school as sc on sc.school_id = coa.school_id where s_coa.school_id=$school_id and coa.parent_id=".$coa['coa_id']."";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();
            if(count($coa_rec1)>0)
            {
                $this->get_coa_ids($coa['coa_id']);
            }
    
          }
          $data = implode("','",$this->coa_ids);
          return $data;
    }
    
    function balance_sheet_pdf()
    {
        // exit('exit');
        $data['start_date']=$this->input->post('start_date');
        $data['end_date']=$this->input->post('end_date');
        $start_date=date_slash( $data['start_date']);
        $end_date=date_slash( $data['end_date']);

        $parent_coa_list=array();
        $child_coa_list = array();

        $data_frp_array = array();
        $income_statement_str = "SELECT frs.settings_type as settings_type , coa.* FROM  ".get_school_db().".financial_reports_settings AS frs 
                                INNER JOIN  ".get_school_db().".chart_of_accounts as coa ON frs.coa_id = coa.coa_id
                                INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                WHERE frs.settings_type in ('balance_sheet_assets','balance_sheet_liabilities')
                                AND s_coa.school_id = ".$_SESSION['school_id']."";

        $income_statement_qry=$this->db->query($income_statement_str)->result_array();

        foreach ($income_statement_qry as $key=>$val)
        {
            $data_frp_array[$val['settings_type']] = $val;
        }

        foreach($data_frp_array as $key1=>$val1)
        {

            $coa_rec1_str = "SELECT coa.* FROM ".get_school_db().".chart_of_accounts as coa 
                                INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                WHERE coa.parent_id=".$val1['coa_id']."
                                            AND s_coa.school_id = ".$_SESSION['school_id']." AND coa.is_active = 1";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();

            foreach($coa_rec1 as $row1)
            {
                $child_coa_list[$key1][$row1['coa_id']] = $row1;

                $this->coa_list = array();
                $this->get_coa_list($row1['coa_id'], $start_date, $end_date);
                $child_coa_list[$key1][$row1['coa_id']]['child_coa'] = $this->coa_list;
            }
        }
        // echo "<pre>";
        //         print_r($child_coa_list);
        // exit;


        $data['child_coa_list'] = $child_coa_list;
        $data['data_frp_array'] = $data_frp_array;


        if($data['start_date'] != "" && $data['end_date'] != "" ) {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".convert_date(date_slash($data['end_date']));
        }
        else  if($data['start_date'] != "" || $data['end_date'] != "" )
        {
            $data['from_to'] = " From ".convert_date(date_slash($data['start_date']))." To ".date("d-M-Y");
        }
        else
        {
            $data['from_to'] = date("d-M-Y");
        }

        $this->load->library('Pdf');
        $data['page_title'] = "Balance Sheet Report";
        $view = 'backend/admin/balance_sheet_pdf';
        $this->pdf->load_view($view,$data);
        $this->pdf->render();
        $this->pdf->stream("".$data['page_title'].".pdf");

    }

    function get_coa_list($parent_id=0, $start_date=0, $end_date=0)
    {
        $school_id=$_SESSION['school_id'];

        $main_coa_rec_str = "SELECT coa.* FROM ".get_school_db().".chart_of_accounts as coa
                                    INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                    WHERE coa.coa_id=$parent_id AND s_coa.school_id=$school_id";
        $main_coa_rec=$this->db->query($main_coa_rec_str)->result_array();

        foreach($main_coa_rec as $main_coa)
        {
            $this->coa_list[$main_coa['coa_id']] = $main_coa;

            $debit_credit_total =  $this->debit_credit_total($main_coa['coa_id'], $start_date, $end_date);
            $this->coa_list[$main_coa['coa_id']]['debit'] = $debit_credit_total['debit'];
            $this->coa_list[$main_coa['coa_id']]['credit'] = $debit_credit_total['credit'];

            $coa_rec_str = "SELECT coa.* FROM " . get_school_db() . ".chart_of_accounts as coa
                            INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                            WHERE coa.parent_id=$parent_id AND s_coa.school_id=$school_id";

            $coa_rec = $this->db->query($coa_rec_str)->result_array();

            foreach ($coa_rec as $coa)
            {
                $this->coa_list[$coa['coa_id']] = $coa;

                $debit_credit_total =  $this->debit_credit_total($coa['coa_id'], $start_date, $end_date);
                $this->coa_list[$coa['coa_id']]['debit'] = $debit_credit_total['debit'];
                $this->coa_list[$coa['coa_id']]['credit'] = $debit_credit_total['credit'];

                $coa_rec1_str = "SELECT coa.* from " . get_school_db() . ".chart_of_accounts as coa
                                    INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                    WHERE s_coa.school_id=$school_id and coa.parent_id=" . $coa['coa_id'];
                $coa_rec1 = $this->db->query($coa_rec1_str)->result_array();
                if (count($coa_rec1) > 0)
                {
                    $this->get_coa_list($coa['coa_id']);
                }
            }
        }
    }

    function debit_credit_total($coa_id=0, $start_date=0, $end_date=0)
    {
        $qu_check = "";
    
        if ($start_date != "" && $end_date != "")
        {
            $start_date1 = $start_date;
            $end_date1 = $end_date;
            $qu_check = " AND (DATE_FORMAT(`entry_date`, '%Y-%m-%d') between '$start_date1' and '$end_date1')";
        }
        elseif ($start_date != "" && $end_date == "") {
            $start_date1 = $start_date;
            $qu_check = " AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') >= '$start_date1' ";
        }
        elseif ($end_date != "" && $start_date == "")
        {
            $end_date1 = $end_date;
            $qu_check = " AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') <= '$end_date1'";
        }
        
        $debit_credit_str =  "SELECT sum(debit) as debit, sum(credit) as credit FROM ".get_school_db().".journal_entry WHERE school_id = ".$_SESSION['school_id']." AND coa_id =$coa_id $qu_check";
        // recent working
        $query_total =$this->db->query($debit_credit_str)->row();
        
        $total = array('debit' => 0, 'credit' => 0);
        if(count($query_total)>0)
        {
            if ($query_total->debit != "")
            {
                $total['debit'] = $query_total->debit;
            }

            if ($query_total->credit != "")
            {
                $total['credit'] = $query_total->credit;
            }
        }
        return $total;
    }
    
    // Student Fee Report Ledger
    function student_fee_report_ledger(){
        $page_data['page_name'] = 'student_fee_report_ledger';
        $page_data['page_title'] = get_phrase('student_fee_report');
        $page_data['section_id']= $this->input->post('section_id', TRUE);
        $page_data['student_select']= $this->input->post('student_select', TRUE);
        $page_data['start_date']= $this->input->post('start_date', TRUE);
        $page_data['end_date']= $this->input->post('end_date', TRUE);
        $page_data['coa_id']= $this->input->post('coa_id', TRUE);
        $this->load->view('backend/index', $page_data);
    }
    
    function get_section_student()
    {
        
        if($this->input->post('section_id') != "")
        {
            echo section_student($this->input->post('section_id'));
        }
    }
    
    function student_challan_summary()
    {
        $page_data['page_name'] = 'student_challan_summary';
        $page_data['page_title'] = get_phrase('student_challan_summary');
        $page_data['section_id']= $this->input->post('section_id', TRUE);
        $page_data['student_select']= $this->input->post('student_select', TRUE);
        $page_data['year']= $this->input->post('year', TRUE);
        $this->load->view('backend/index', $page_data);
    }
    
}