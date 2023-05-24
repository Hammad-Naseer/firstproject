<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Attendance_summary_staff extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
	}
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if($_SESSION['user_login']!= 1)
        redirect(base_url() . 'login');
                if ($_SESSION['user_login'] == 1)
        redirect(base_url() . 'admin/dashboard');
    }
	
    function view_attendance_summary()
    {
    	$staff_id   =   $this->input->post('staff_id');
    	$apply_filter   =   $this->input->post('apply_filter');
    	
    	if(isset($staff_id))
    	{
            $page_data['staff_id']=$staff_id;
            $acadmic_year_start = $this->db->query("select start_date,end_date from ".get_school_db().".acadmic_year 
            where academic_year_id =".$_SESSION['academic_year_id']." and school_id=".$_SESSION['school_id']." ")->result_array();
            
            $start_date                 =       $acadmic_year_start[0]['start_date'];
            $end_date                   =       $acadmic_year_start[0]['end_date'];  
            $page_data['start_date']    =       $start_date;
            $page_data['end_date']      =       $end_date;
    	}
    	
    	$page_data['apply_filter']      =   $apply_filter;
    	$page_data['page_name']         =   'staff_attendance_summary';
    	$page_data['page_title']		=	get_phrase('staff_attendance_summary');
    	$this->load->view('backend/index', $page_data);
    }	
	
	
 	
 		
 	
 }	
   
?>