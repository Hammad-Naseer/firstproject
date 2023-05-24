<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Attendance_summary_student extends CI_Controller
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
	
function view_student_summary()
{
	$section_filter=$this->input->post("section_id");
  	$student_filter=$this->input->post("student_select");
  	$apply_filter = $this->input->post("apply_filter");
  	
  	
  	if(isset($section_filter) && ($section_filter > 0) && isset($student_filter) && ($student_filter > 0))
  	{
		$page_data['section_filter']=$section_filter;
  		$page_data['student_filter']=$student_filter;
  		$acadmic_year_start = $this->db->query("select start_date,end_date 
                        from ".get_school_db().".acadmic_year 
                        where 
                        academic_year_id =".$_SESSION['academic_year_id']." 
                        and school_id=".$_SESSION['school_id']." 
                        ")->result_array();
                        //echo $this->db->last_query();
        $start_date=$acadmic_year_start[0]['start_date'];
        $end_date=$acadmic_year_start[0]['end_date'];     
                        
                        
        $p="select status,count(status) as status_count, month(date) as month_val, year(date) as year_val,monthname(date) as month_name from ".get_school_db().".attendance where student_id=$student_filter and school_id=".$_SESSION['school_id']." GROUP BY month_val,year_val,status order by  month_name, year_val";
        $attendance=$this->db->query($p)->result_array();
        $page_data['attendance']=$attendance;
        $page_data['start_date']=$start_date;
        $page_data['end_date']=$end_date;       
  		
	}
	
	
	$page_data['apply_filter']= $apply_filter;
	$page_data['page_name']='student_attendance_summary';
	$page_data['page_title']		=	get_phrase('staff_attendance_summary');
	$this->load->view('backend/index', $page_data);
}	
	
function get_section_student()
{
      $student_select=$this->input->post('$student_select');
      if($this->input->post('section_id')!="")
      {
            echo section_student($this->input->post('section_id') , $this->input->post('student_id'));
      }
}	
 	
 		
 	
 }	
   
?>