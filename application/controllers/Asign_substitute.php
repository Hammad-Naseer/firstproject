<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Asign_substitute extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
		$this->load->database();
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->school_db=$_SESSION['school_db'];
        if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
        //$_SESSION['school_id']=101;
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    function listing_asign($param1="",$param2="",$param3="",$param4="")
    {

        if($param1!="" && $param2!="" && $param3!="" && $param4!="") 
		{
			$page_data['attend_id1']=$param2;
			$page_data['staff_id1']=$param3;
			$page_data['day1']=$param4;
		}

    	$current_date=$param1;
    	if(isset($current_date) && ($current_date > 0))
    	{
    		$page_data['current_date']=$current_date;
    	}
    	
        $page_data['page_name']  = 'view_substitute';
        $page_data['page_title'] = get_phrase('manage_substitute');
        $this->load->view('backend/index', $page_data);

	}

    function present_absent($param1="",$param2="",$param3="",$param4="",$param5="",$param6="")
    {
        if($param1!="" && $param2!="" && $param3!="")
        {
           $date=$param1."/".$param2."/".$param3;
           $page_data['date']=$date;
        }
        
        if($param1!="" && $param2!="" && $param3!="" && $param4!="" && $param5!="" && $param6!="")
        {
        	$date=$param3.'-'.$param2.'-'.$param1;
        	$attend_id1=$param4;
        	$staff_id1=$param5;
        	$day1=$param6;
        	$page_data['date1']=$date;
        	$page_data['attend_id1']=$attend_id1;
        	$page_data['staff_id1']=$staff_id1;
        	$page_data['day1']=$day1;
        }
        else
        {
        	$date=$this->input->post('date');
        	if(isset($date) && ($date!=""))
        	{
        		$page_data['date']=$date;
        	}
        }
		
		$this->load->view('backend/admin/ajax/present_absent_list.php',$page_data);
	}
	
	function present_absent_generator()
	{
		$page_data['attend_id']= $this->input->post('attend_id');
		$page_data['staff_id'] = $this->input->post('staff_id');
		$page_data['day']      = $this->input->post('day');
		$page_data['date']     = $this->input->post('date');
		$this->load->view('backend/admin/ajax/present_absent_detail.php',$page_data);
	}
	
	function present_absent_url($param1="",$param2="",$param3="")
	{
		$page_data['staff_id']= $param1;
		$page_data['day']     = $param2;
		$page_data['date']    = $param3;
		$this->load->view('backend/admin/ajax/present_absent_detail.php',$page_data);
	}
	
    function asign_save()
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url());
		
 		$staff_array=$this->input->post('staff_id');
		$data['section_id']  = $this->input->post('section_id');
		$data['subject_id']=$this->input->post('subject_id');
		$data['date']=$this->input->post('date');
		$data['period_no'] = $this->input->post('period_no');
        $data['substitute_of'] = $this->input->post('substitute_of');
		$data['school_id']= $_SESSION['school_id'];

        $this->db->delete(get_school_db().'.substitute_teacher', $data); 
        
		if(sizeof($staff_array)>0)
		{
			foreach($staff_array as $row)
			{
				$data['staff_id'] = $row['value'];
				$this->db->insert(get_school_db().'.substitute_teacher',$data);
			}
		}
		
  
    }
   
   
	
	
	
    
    

	
	
   
    
}
    