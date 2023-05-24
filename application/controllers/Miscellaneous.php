<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start();
class Miscellaneous extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if($_SESSION['user_login'] != 1)
		redirect('login');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	function misc_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        $page_data['page_name']  = 'miscellaneous_settings';
        
        $page_data['page_title'] = get_phrase('miscellaneous_settings');
       $q="SELECT * FROM ".get_school_db().".miscellaneous_settings WHERE school_id=".$_SESSION['school_id']." AND type='stud_eval'";
        $misc=$this->db->query($q)->result_array();
        
       $q1="SELECT * FROM ".get_school_db().".miscellaneous_settings WHERE school_id=".$_SESSION['school_id']." AND type='staff_eval'";
        $misc_staff=$this->db->query($q1)->result_array();
        
        $page_data['misc']      = $misc;
        $page_data['misc_staff']      = $misc_staff;
        $this->load->view('backend/index', $page_data);
        
    }
    
    function add_misc_settings()
    {
    	$list_array=array();
 
		$data['type']= $this->input->post('type');      
		$data['detail']= $this->input->post('detail');
		$data['status']= $this->input->post('status');
		$misc_id=$this->input->post('misc_id');
		$data['school_id'] = $_SESSION['school_id'];
		
		if($misc_id =="")
		{
		$this->db->insert(get_school_db().'.miscellaneous_settings', $data);
		$query=$this->db->affected_rows();
		$last_id = $this->db->insert_id();
		if($query > 0)
			{
				$q="SELECT * from ".get_school_db().".miscellaneous_settings WHERE school_id=".$_SESSION['school_id']." AND type='staff_eval' AND misc_id=".$last_id;
				$result=$this->db->query($q)->result_array();
				$list_array['type']=$result[0]['type'];
				$list_array['detail']=$result[0]['detail'];			
				$list_array['status']=$result[0]['status'];				
				
				
				$list_array['msg']='<div class="alert alert-success">'.get_phrase('record_saved_successfully').'</div>';
				$list_array['misc_id']=$last_id;
				echo json_encode($list_array);
				
				 ?>
				 
				
				
		<?php 
			}
			else
			{
				echo '<div class="alert alert-danger">'.get_phrase('record_not_saved').'</div>';
			}
			}
		else
		{
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('misc_id', $misc_id);
            $this->db->update(get_school_db().'.miscellaneous_settings', $data);
		}
        
	}
    
     
  function add_staff_misc_settings()
    {
    	$list_array=array();
 
		$data['type']= $this->input->post('type');      
		$data['detail']= $this->input->post('detail');
		$data['status']= $this->input->post('status');
		$misc_id=$this->input->post('misc_id');
		$data['school_id'] = $_SESSION['school_id'];
		
		if($misc_id =="")
		{
		$this->db->insert(get_school_db().'.miscellaneous_settings', $data);
		$query=$this->db->affected_rows();
		$last_id = $this->db->insert_id();
		if($query > 0)
			{
				$q="SELECT * from ".get_school_db().".miscellaneous_settings WHERE school_id=".$_SESSION['school_id']." AND type='stud_eval' AND misc_id=".$last_id;
				$result=$this->db->query($q)->result_array();
				
				$list_array['detail']=$result[0]['detail'];			
				$list_array['status']=$result[0]['status'];				
				
				
				$list_array['msg']='<div class="alert alert-success">'.get_phrase('record_saved_successfully').'</div>';
				$list_array['misc_id']=$last_id;
				echo json_encode($list_array);
				
				 ?>
				 
				
				
		<?php 
			}
			else
			{
				echo '<div class="alert alert-danger">'.get_phrase('record_not_saved').'</div>';
			}
			}
		else
		{
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('misc_id', $misc_id);
            $this->db->update(get_school_db().'.miscellaneous_settings', $data);
		}
        
	}
    
    
}