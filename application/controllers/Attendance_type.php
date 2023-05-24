<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();

class Attendance_type extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if($_SESSION['user_login']!= 1)
		     redirect(base_url() . 'login');
	}

    public function index()
    {
 
    }


 
    function attendance_setting(){ 
  	
      $page_data['page_name']  =  'attendance_type';
      $page_data['page_title'] =  get_phrase('attendance_type');
      $this->load->view('backend/index', $page_data);	
    }

    function save_attendance_setting(){

        $attendanceType     =  $this->input->post('attendnce_type');
        $school_id          =  $this->input->post('school_id');
        $quer               =  "select  * from " . get_school_db() . ".attendance_type  where school_id=" . $_SESSION['school_id'] . "";
        
        $attendance_count   =  $this->db->query($quer)->result_array();
        $data['login_type'] =  $this->input->post('attendnce_type');
        $data['school_id']  =  $_SESSION['school_id'];
        
        if(count($attendance_count) == 0)
        {
            $this->db->insert(get_school_db().'.attendance_type',$data);
            $this->session->set_flashdata('club_updated',get_phrase('attendance_setting_added_successfully'));
        }
        else
        {
            $data1['login_type'] = $this->input->post('attendnce_type');
            $this->db->where('school_id',$_SESSION['school_id']);	
            $this->db->update(get_school_db().'.attendance_type',$data1);	
            $this->session->set_flashdata('club_updated',get_phrase('attendance_setting_updated_successfully'));
        }

        redirect(base_url() . 'attendance_type/attendance_setting');
	 	
    }


}