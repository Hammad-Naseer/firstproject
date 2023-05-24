<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Payroll extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->helper('payroll');
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
	}
    /*********************** Allownces *********************
    *******************************************************/
	function allownces_listing()
	{
	    $q = "select * from ".get_school_db().".allownces where school_id = ".$_SESSION['school_id']."";
	    $allownces = $this->db->query($q)->result_array();
	    
	    $page_data['allownces']		=	$allownces;
		$page_data['page_name']		=	'payroll/allownces_listing';
		$page_data['page_title']    =	get_phrase('allownces_listing');
		
		
		$this->load->view('backend/index', $page_data);
	}
	
	function add_allownces()
	{
		$data['allownce_title'] = $this->input->post('allownce_title');
        //$data['allownce_percentage'] = $this->input->post('allownce_percentage');
		$data['school_id'] =  $_SESSION['school_id'];
		$data['status'] = $this->input->post('status');
		$this->db->insert(get_school_db().'.allownces', $data);
		
		$this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));
		redirect(base_url().'payroll/allownces_listing');
	}
	
	function delete_allownces($allownce_id=0) 
	{
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('allownce_id', $allownce_id);
        $this->db->delete(get_school_db().'.allownces');
        $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
        redirect(base_url() . 'payroll/allownces_listing/');
    }
    
    function update_allownce()
    {
        $allownce_id = $this->input->post('allownce_id');
        $data['allownce_title'] = $this->input->post('allownce_title');
// 		$data['allownce_percentage'] = $this->input->post('allownce_percentage');
		$data['school_id'] =  $_SESSION['school_id'];
		$data['status'] = $this->input->post('status');
		
		$this->db->where('allownce_id' , $allownce_id);
		$this->db->update(get_school_db().'.allownces', $data);
		$this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
		redirect(base_url().'payroll/allownces_listing');
    }
    
    
    /*********************** Deductions *******************
    *******************************************************/
    function deduction_listing()
	{
	    $q = "select * from ".get_school_db().".deductions where school_id = ".$_SESSION['school_id']."";
	    $deductions = $this->db->query($q)->result_array();
	    
	    $page_data['deductions']		=	$deductions;
		$page_data['page_name']		=	'payroll/deduction_listing';
		$page_data['page_title']    =	get_phrase('deduction_listing');
		
		$this->load->view('backend/index', $page_data);
	}
	
	function add_deductions()
	{
		$data['deduction_title'] = $this->input->post('deduction_title');
// 		$data['deduction_percentage'] = $this->input->post('deduction_percentage');
        $data['credit_coa_id'] =  $this->input->post('credit_coa_id');
		$data['school_id'] =  $_SESSION['school_id'];
		$data['status'] = $this->input->post('status');
		
		$this->db->insert(get_school_db().'.deductions', $data);
		$this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));
		redirect(base_url().'payroll/deduction_listing');
	}
	
	function delete_deduction($deduction_id=0) 
	{
        // $qur_r=$this->db->query("select deduction_id from ".get_school_db().".staff_payroll_deductions where deduction_id=$deduction_id")->result_array();	
        // if(count($qur_r)>0){
        // $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
        // redirect(base_url() . 'payroll/deduction_listing/');	
        //  exit();
        // }
    	
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('deduction_id', $deduction_id);
        $this->db->delete(get_school_db().'.deductions');
        $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
        redirect(base_url() . 'payroll/deduction_listing/');
   
    }
    
    function update_deductions()
    {
        $deduction_id = $this->input->post('deduction_id');
        $data['deduction_title'] = $this->input->post('deduction_title');
        //$data['deduction_percentage'] = $this->input->post('deduction_percentage');
        $data['credit_coa_id'] =  $this->input->post('credit_coa_id');
		$data['school_id'] =  $_SESSION['school_id'];
		$data['status'] = $this->input->post('status');
		
		$this->db->where('deduction_id' , $deduction_id);
		$this->db->update(get_school_db().'.deductions', $data);
		$this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
		redirect(base_url().'payroll/deduction_listing');
    }
    
    
    /*********************** Staff Salary *****************
    *******************************************************/
    
    function staff_salary()
	{
		$page_data['page_name']		=	'payroll/staff_salary';
		$page_data['page_title']    =	get_phrase('staff_salary');
		
		$this->load->view('backend/index', $page_data);
	}
	
	function get_staff_salary()
	{
	    $staff_id       = $this->input->post('staff_id');
	    $month          = $this->input->post('month');
	    $year           = $this->input->post('year');
	    $apply_filter   = $this->input->post('apply_filter');
	    
	    // Check If Salary Already Exist
        $this->db->where("staff_id",$staff_id);
        $this->db->where("month",$month);
        $this->db->where("year",$year);
        $salary_query = $this->db->get(get_school_db().".staff_salary_slip");
	    if($salary_query->num_rows() > 0)
	    {
	        $page_data['salary_exist']  =   'Salary already has been created';
	    }else{
    	    $qry = "SELECT s.staff_id as s_id , s.*, d.title as designation, d.is_teacher , 
                    (select gross_salary from ".get_school_db().".staff_payroll_settings where staff_id = s.staff_id ) as gross_salary
                    FROM ".get_school_db().".staff s left JOIN ".get_school_db().".designation d ON d.designation_id = s.designation_id WHERE s.school_id=".$_SESSION['school_id']." and s.staff_id = ".$staff_id." ";
            $salary_data = $this->db->query($qry)->result_array();
	    }
            
            $page_data['salary_data']   = $salary_data;
            $page_data['staff_id']         = $staff_id;
            $page_data['month']         = $month;
            $page_data['year']          = $year;
            $page_data['apply_filter']  = $apply_filter;
            
            $page_data['page_name']		=	'payroll/staff_salary';
    		$page_data['page_title']    =	get_phrase('staff_salary');
    		
    		$this->load->view('backend/index', $page_data);
	    
	}
	
	function staff_salary_slip_save()
	{
	    $this->db->trans_begin();
	   
	   // Salary Slip Data
	    $data['staff_id'] = $this->input->post('staff_id');
	    $data['month'] = $this->input->post('month');
	    $data['year'] = $this->input->post('year');
	    $data['basic_salary'] = $this->input->post('basic_salary');
	    $data['earned_salary'] = $this->input->post('earned_salary');
	    $data['house_rent_allownce'] = $this->input->post('house_rent_allownce');
	    $data['medical_allownce'] = $this->input->post('medical_allownce');
	    $data['income_tax_deduction'] = $this->input->post('income_tax_deduction');
	    $data['total_days'] = $this->input->post('total_days');
	    $data['net_salary'] = $this->input->post('net_salary');
	    $data['date_generated'] = date('Y-m-d');
	    $data['school_id'] = $_SESSION['school_id'];
	    $data['generatd_by'] = $_SESSION['user_login_id'];
	    
	    $this->db->insert(get_school_db().".staff_salary_slip",$data);
	    $last_insert_id = $this->db->insert_id();
	    
	   // Allownces Data
	   $allownce_ids = $_POST['allownce_ids'];
	   $count_allownce = count($this->input->post('allownce_ids'));
	   for($i=0; $i < $count_allownce; $i++)
	   {
	       $allownce_data['s_s_s_id'] = $last_insert_id;
	       $allownce_data['allownce_id'] = $allownce_ids[$i];
	       $allownce_data['allownce_amount'] = $_POST['allownce'][$i];
	       
	       $this->db->insert(get_school_db().".staff_salary_allownces",$allownce_data);
	   }
	   
	   
	   //Deduction Data
	   $deduct_ids = $_POST['deduction_ids'];
	   $count_deduction = count($this->input->post('deduction_ids'));
	   for($i=0; $i < $count_deduction; $i++)
	   {
	       $deduction_data['s_s_s_id'] = $last_insert_id;
	       $deduction_data['deduction_id'] = $deduct_ids[$i];
	       $deduction_data['deduction_amount'] = $_POST['deductions'][$i];
	       
	       $this->db->insert(get_school_db().".staff_salary_deductions",$deduction_data);
	   }
	   
	    $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
	   
	    $this->session->set_flashdata('club_updated',get_phrase('salary_saved_successfully'));
	    redirect(base_url().'payroll/staff_salary');
	    
	}
	
	function view_staff_salary()
	{
	    $staff_id       = $this->input->post('staff_id');
	    $month          = $this->input->post('month');
	    $year           = $this->input->post('year');
	    $apply_filter   = $this->input->post('apply_filter');
	    
	    $staff_filter = "";
	    if(isset($staff_id) && $staff_id !=""){
	        $staff_filter = "and ss.staff_id = ".$staff_id." ";
	    }
	    $month_filter = "";
	    if(isset($month) && $month !=""){
	        $month_filter = "and ss.month = ".$month." ";
	    }
	    $year_filter = "";
	    if(isset($year) && $year !=""){
	        $year_filter = "and ss.year = ".$year." ";
	    }
	    $q = "select ss.* , s.name from ".get_school_db().".staff_salary_slip ss
	    inner join ".get_school_db().".staff s on ss.staff_id = s.staff_id
	    where ss.school_id = ".$_SESSION['school_id']." $staff_filter $month_filter $year_filter";
	    $staff_salary = $this->db->query($q)->result_array();
	    
	    
	    
	    $page_data['staff_salary']		=	$staff_salary;
        $page_data['staff_id']         = $staff_id;
        $page_data['month']         = $month;
        $page_data['year']          = $year;
        $page_data['apply_filter']  = $apply_filter;
		$page_data['page_name']		=	'payroll/view_staff_salary';
		$page_data['page_title']    =	get_phrase('view_staff_salary');
		
		$this->load->view('backend/index', $page_data);
	}
	
	function salary_details($s_id = 0)
	{
	    $s_s_s_id = str_decode($s_id);
        $q = "select ss.* , s.name ,s.designation_id, 
                (select GROUP_CONCAT(allownce_id) from ".get_school_db().".staff_salary_allownces where s_s_s_id = ".$s_s_s_id." ) as allownce_ids ,
                (select GROUP_CONCAT(deduction_id) from ".get_school_db().".staff_salary_deductions where s_s_s_id = ".$s_s_s_id." ) as deduction_ids 
                FROM ".get_school_db().".staff_salary_slip ss
                inner join ".get_school_db().".staff s on s.staff_id = ss.staff_id
                WHERE s.school_id=".$_SESSION['school_id']." and ss.s_s_s_id = ".$s_s_s_id."
                
                ";
        
        $salary_data = $this->db->query($q)->result_array();
        
        $page_data['salary_data']		=	$salary_data;
        $page_data['page_name']		=	'payroll/salary_details';
    	$page_data['page_title']    =	get_phrase('salary_details');
    	$this->load->view('backend/index', $page_data);
	}
	
	function print_salary_slip($s_s_s_id = 0)
	{
	    echo $s_s_s_id;
	}
	
	function payroll_sheet()
	{
	    $month          = $this->input->post('month');
	    $year           = $this->input->post('year');
	    $apply_filter   = $this->input->post('apply_filter');
	    
	    $month_filter = "";
	    if(isset($month) && $month !=""){
	        $month_filter = "and ss.month = ".$month." ";
	    }
	    $year_filter = "";
	    if(isset($year) && $year !=""){
	        $year_filter = "and ss.year = ".$year." ";
	    }
	    if(isset($apply_filter) && $apply_filter == 1){
    	    $q = "select ss.* , ss.s_s_s_id, s.name , s.employee_code , id_no ,
    	           (select GROUP_CONCAT(allownce_id)  from ".get_school_db().".staff_salary_allownces where s_s_s_id = ss.s_s_s_id) as allownce_ids ,
    	           (select GROUP_CONCAT(deduction_id)  from ".get_school_db().".staff_salary_deductions where s_s_s_id = ss.s_s_s_id) as deductions_ids
    	           from ".get_school_db().".staff_salary_slip ss 
    	           inner join ".get_school_db().".staff s on ss.staff_id = s.staff_id where ss.school_id = ".$_SESSION['school_id']." $month_filter $year_filter";
    	    $payroll_details = $this->db->query($q)->result_array();
	    }else{
	        $payroll_details = array();
	    }
	    
	    $page_data['payroll_details']   =	$payroll_details;
        
        $page_data['month']   =	$month;
        $page_data['year']   =	$year;
        $page_data['apply_filter']   =	$apply_filter;
	    $page_data['page_name']		    =	'payroll/payroll_sheet';
    	$page_data['page_title']        =	get_phrase('payroll_sheet');
    	$this->load->view('backend/index', $page_data);
	}
	
	function payroll_ytd_report()
	{
	    $staff_id          = $this->input->post('staff_id');
	    $start_month_year          = $this->input->post('start_month');
	    $end_month_year          = $this->input->post('end_month');
	    $apply_filter   = $this->input->post('apply_filter');
	    
	    $pieces = explode("-", $start_month_year);
        $start_month = $pieces[1];
        $start_year  = $pieces[0];
        
        $pieces1 = explode("-", $end_month_year);
        $end_month = $pieces1[1];
        $end_year  = $pieces1[0];
    
        $staff_filter = "";
	    if(isset($staff_id) && $staff_id !=""){
	        $staff_filter = "and ss.staff_id = ".$staff_id." ";
	    }
	    $month_filter = "";
	    if(isset($start_month) && $end_month !=""){
	        $month_filter = "and ss.month between ".$start_month." and  ".$end_month."";
	    }
	    $year_filter = "";
	    if(isset($start_year) && $end_year !=""){
	        $year_filter = "and ss.year between ".$start_year." and  ".$end_year."";
	    }
	    
	    if(isset($apply_filter) && $apply_filter != ""){
	        $q = "select ss.s_s_s_id , ss.staff_id ,  sum(ss.basic_salary) as basic_salary ,  basic_salary as monthly_basic_salary , sum(ss.earned_salary) as earned_salary , ss.earned_salary as monthly_earned_salary ,  sum(ss.net_salary) as net_salary, ss.net_salary as monthly_net_salary,
        	        sum(ss.house_rent_allownce) as house_rent_allownce, ss.house_rent_allownce as monthly_house_rent_allownce, sum(ss.medical_allownce) as medical_allownce,ss.medical_allownce as monthly_medical_allownce,sum(ss.income_tax_deduction) as income_tax_deduction,ss.income_tax_deduction as monthly_income_tax_deduction,
        	        (select GROUP_CONCAT(s_s_s_id)  from ".get_school_db().".staff_salary_slip where staff_id = ".$staff_id." ) as slips_ids,
        	        s.name from ".get_school_db().".staff_salary_slip ss
        	        inner join ".get_school_db().".staff s on ss.staff_id = s.staff_id
        	        where ss.school_id = ".$_SESSION['school_id']." $staff_filter $month_filter $year_filter ORDER BY ss.s_s_s_id DESC ";
	    
	        $salary_data = $this->db->query($q)->row();
	    }else{
	        $salary_data = array();
	    }
	    
	    $page_data['salary_data']		=	$salary_data;
	    $page_data['staff_id']		    =	$staff_id;
	    $page_data['start_month']		=	$start_month;
	    $page_data['end_month']		    =	$end_month;
	    $page_data['start_month_year']  =	$start_month_year;
	    $page_data['end_month_year']	=	$end_month_year;
	    $page_data['apply_filter']	    =	$apply_filter;
	    
	    $page_data['page_name']		    =	'payroll/payroll_ytd_report';
    	$page_data['page_title']        =	get_phrase('payroll_ytd_report');
    	$this->load->view('backend/index', $page_data);
	}
	
	function get_income_tax()
	{
	    $total_gross = $this->input->post("total_gross");
	    echo income_tax_deduction($total_gross);
	}
	
	function post_salary_letter()
	{
	   $this->db->trans_begin();
	   $gross_salary_coa_id         = get_salary_setting_coa('gross_salary');
	   $other_allownces_coa_id      = get_salary_setting_coa('other_allownces');
	   $income_tax_payable_coa_id   = get_salary_setting_coa('income_tax_payable');
	   $salaries_payable_coa_id     = get_salary_setting_coa('salaries_payable');
	   
	   if($gross_salary_coa_id > 0 && $other_allownces_coa_id > 0 && $income_tax_payable_coa_id > 0 && $salaries_payable_coa_id > 0){
	        $s_s_s_id = $this->input->post('s_s_s_id');
	        $month = $this->input->post('month');
	        $year = $this->input->post('year');
	        
	        $j_total_allowance = 0;
	        $j_total_gross = 0;
	        $j_total_income_tax = 0;
	        $j_total_net_salary = 0;
	        $other_deductions = array();
	        
	        $allowance = 0;
            for($i = 0 ; $i < count($s_s_s_id) ; $i++){
                
                // explode the parameters and get gross salary , allownces , income tax
                $a = explode('-' , $s_s_s_id[$i]);
                $aa = explode(":",$a[0]);
                $current_s_s_s_id = $aa[1];
                
                $bb = explode(":",$a[1]);
                $current_allownce = $bb[1];
                $j_total_allowance += $current_allownce;
                
                $cc = explode(":",$a[2]);
                $current_total_gross = $cc[1];
                $j_total_gross += $current_total_gross;
                
                $dd = explode(":",$a[3]);
                $current_income_tax = $dd[1];
                $j_total_income_tax += $current_income_tax;
                
                $ee = explode(":",$a[4]);
                $current_net_salary = $ee[1];
                $j_total_net_salary += $current_net_salary;
                
                //get all deductions made in single salary slip and sum up the amount of deduction
                $deductions_aar = $this->db->query("select deduction_id, deduction_amount from " . get_school_db() . ".staff_salary_deductions where s_s_s_id =".$current_s_s_s_id." ")->result_array();
                foreach($deductions_aar as $row){
                    $other_deductions[$row['deduction_id']] += $row['deduction_amount'];
                }
                
                // ********* update staff_salary_slip table *******
                $data1['is_posted']     =   1;
                $data1['posted_by']     =   $_SESSION['login_detail_id'];
                $data1['date_posted']   =   date('Y-m-d');
            
                $this->db->where('s_s_s_id', $current_s_s_s_id);
                $this->db->where('school_id', $_SESSION['school_id']);
                $this->db->update(get_school_db().'.staff_salary_slip',$data1);
                //********* update staff_salary_slip table *******
                
            }
            
            //******************* Debit Entries ********************************
            //******************************************************************
            $data2['school_id'] = $_SESSION['school_id'];
            $data2['entry_date'] = date('Y-m-d H:i:s');
            $data2['detail'] = "Total gross salary has been debited with amount of ".$j_total_gross." against month of ".$month." / ".$year;
            $data2['debit'] = $j_total_gross-$j_total_allowance;
            $data2['credit'] = 0;
            $data2['entry_type'] = 22;
            $data2['type_id'] = 0; //should be changed
            $data2['coa_id'] = $gross_salary_coa_id;
            $this->db->insert(get_school_db().'.journal_entry',$data2);
            
            if($j_total_allowance > 0){
                $data3['school_id'] = $_SESSION['school_id'];
                $data3['entry_date'] = date('Y-m-d H:i:s');
                $data3['detail'] = "Total allownces has been debited with amount of ".$j_total_allowance." against month of ".$month." / ".$year;
                $data3['debit'] = $j_total_allowance;
                $data3['credit'] = 0;
                $data3['entry_type'] = 22;
                $data3['type_id'] = 0; //should be changed
                $data3['coa_id'] = $other_allownces_coa_id;
                $this->db->insert(get_school_db().'.journal_entry',$data3);
            }
            
            //******************* Credit Entries ********************************
            //******************************************************************
            $data4['school_id'] = $_SESSION['school_id'];
            $data4['entry_date'] = date('Y-m-d H:i:s');
            $data4['detail'] = "Salaries Payable has been credited with amount of ".$j_total_net_salary." against month of ".$month." / ".$year;
            $data4['debit'] = 0;
            $data4['credit'] = $j_total_net_salary;
            $data4['entry_type'] = 22;
            $data4['type_id'] = 0; //should be changed
            $data4['coa_id'] = $salaries_payable_coa_id;
            $this->db->insert(get_school_db().'.journal_entry',$data4);
            
            if($j_total_income_tax > 0){
                $data5['school_id'] = $_SESSION['school_id'];
                $data5['entry_date'] = date('Y-m-d H:i:s');
                $data5['detail'] = "Withholing Income tax Payable has been credited with amount of ".$j_total_income_tax." against month of ".$month." / ".$year;
                $data5['debit'] = 0;
                $data5['credit'] = $j_total_income_tax;
                $data5['entry_type'] = 22;
                $data5['type_id'] = 0; //should be changed
                $data5['coa_id'] = $income_tax_payable_coa_id;
                $this->db->insert(get_school_db().'.journal_entry',$data5);
            }
            
            //other deductions remaining
            foreach($other_deductions as $key => $value){ // key is deduction_id and value is deduction_amount
                $credit_coa_id = get_deduction_coa_id($key);
                if($credit_coa_id > 0){
                    if($value > 0){
                        $data6['school_id'] = $_SESSION['school_id'];
                        $data6['entry_date'] = date('Y-m-d H:i:s');
                        $data6['detail'] = "deductions has been credited with amount ".$value." of against month of ".$month." / ".$year;
                        $data6['debit'] = 0;
                        $data6['credit'] = $value;
                        $data6['entry_type'] = 22;
                        $data6['type_id'] = 0; //should be changed
                        $data6['coa_id'] = $credit_coa_id;  // should be get from helper from deduction able
                        $this->db->insert(get_school_db().'.journal_entry',$data6);
                    } 
                }else{
                    $this->session->set_flashdata('club_updated',get_phrase('deduction_coa_id_is_not_set'));
                    redirect(base_url().'payroll/payroll_sheet'); 
                }
            }
            $this->session->set_flashdata('club_updated',get_phrase('salary_has_been_posted_successfully'));
	   }else{
	       $this->session->set_flashdata('club_updated',get_phrase('cannot_be_posted_salaray_settings_coa_are_missing'));
	   }
	   
	    $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
	    $page_data['page_name']		    =	'payroll/payroll_sheet';
    	$page_data['page_title']        =	get_phrase('payroll_sheet');
    	$page_data['month']             =	$this->input->post('month');
    	$page_data['year']              =	$this->input->post('year');
    	$this->load->view('backend/index', $page_data);
	}
	
	/************ Salary Voucher Setting start ************
    *******************************************************/
	
	function salary_voucher_setting()
	{
	    $page_data['page_name'] = 'payroll/salary_voucher_setting';
        $page_data['page_title'] = get_phrase('salary_voucher_setting');
        $this->load->view('backend/index', $page_data);
	}
	
	function add_gross_salary_setting()
	{
	    $qur = "select * from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type='gross_salary'";
        $query = $this->db->query($qur);
        if ($query->num_rows() > 0)
        {
            $this->db->where('salary_type', 'gross_salary');
            $this->db->where('school_id', $_SESSION['school_id']);
            $data['coa_id'] = $this->input->post('gross_salary_coa');
            $this->db->update(get_school_db().'.salary_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('gross_salary_coa_setting_updated'));
        }
        else
        {
            $data['salary_type'] = 'gross_salary';
            $data['coa_id'] = $this->input->post('gross_salary_coa');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.salary_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('gross_salary_coa_setting_added'));

        }
        redirect(base_url() . 'payroll/salary_voucher_setting');
	}
	
	function add_other_allownces_setting()
	{
	    $qur = "select * from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type='other_allownces'";
        $query = $this->db->query($qur);
        if ($query->num_rows() > 0)
        {
            $this->db->where('salary_type', 'other_allownces');
            $this->db->where('school_id', $_SESSION['school_id']);
            $data['coa_id'] = $this->input->post('other_allownces_coa');
            $this->db->update(get_school_db().'.salary_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('other_allownces_coa_setting_updated'));
        }
        else
        {
            $data['salary_type'] = 'other_allownces';
            $data['coa_id'] = $this->input->post('other_allownces_coa');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.salary_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('other_allownces_coa_setting_added'));

        }
        redirect(base_url() . 'payroll/salary_voucher_setting');
	}
	
	function add_income_tax_payable_setting()
	{
	    $qur = "select * from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type='income_tax_payable'";
        $query = $this->db->query($qur);
        if ($query->num_rows() > 0)
        {
            $this->db->where('salary_type', 'income_tax_payable');
            $this->db->where('school_id', $_SESSION['school_id']);
            $data['coa_id'] = $this->input->post('income_tax_payable_coa');
            $this->db->update(get_school_db().'.salary_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('income_tax_payable_coa_setting_updated'));
        }
        else
        {
            $data['salary_type'] = 'income_tax_payable';
            $data['coa_id'] = $this->input->post('income_tax_payable_coa');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.salary_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('income_tax_payable_coa_setting_added'));

        }
        redirect(base_url() . 'payroll/salary_voucher_setting');
	}
	
	function add_salaries_payable_setting()
	{
	    $qur = "select * from ".get_school_db().".salary_voucher_settings where school_id=".$_SESSION['school_id']." and salary_type='salaries_payable'";
        $query = $this->db->query($qur);
        if ($query->num_rows() > 0)
        {
            $this->db->where('salary_type', 'salaries_payable');
            $this->db->where('school_id', $_SESSION['school_id']);
            $data['coa_id'] = $this->input->post('salaries_payable_coa');
            $this->db->update(get_school_db().'.salary_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('salaries_payable_coa_setting_updated'));
        }
        else
        {
            $data['salary_type'] = 'salaries_payable';
            $data['coa_id'] = $this->input->post('salaries_payable_coa');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.salary_voucher_settings',$data);
            $this->session->set_flashdata('club_updated', get_phrase('salaries_payable_coa_setting_added'));

        }
        redirect(base_url() . 'payroll/salary_voucher_setting');
	}
	
	/************ Salary Voucher Setting ends  *************
    *******************************************************/
    
    function print_payroll_letter()
    {
        $month = $this->input->post('month');
	    $year = $this->input->post('year');
	    $qur = "select ss.*,s.name from ".get_school_db().".staff_salary_slip ss 
	    INNER JOIN ".get_school_db().".staff s on ss.staff_id = s.staff_id 
	    where ss.school_id = ".$_SESSION['school_id']." and ss.month = '$month' AND ss.year = '$year' AND ss.is_posted = 1";
        $query = $this->db->query($qur);
        if ($query->num_rows() > 0)
        {
            $page_data['salary_data'] = $query->result_array();
            $page_data['page_name'] = 'payroll/salaries_payable_letter';
            $page_data['page_title'] = get_phrase('salaries_payable_letter');
            $this->load->view('backend/index', $page_data);
        }else
        {
            $this->session->set_flashdata('club_updated', get_phrase('salary_data_not_found'));
            redirect(base_url() . 'payroll/payroll_sheet');
        }
	    
    }
	
}