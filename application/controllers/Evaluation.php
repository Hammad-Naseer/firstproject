<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start();
class Evaluation extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if($_SESSION['user_login'] != 1)
		redirect('login');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	
	function teacher_rating($param1 = '', $param2 = '', $param3 = ''){
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        if ($param1 == 'create') {
            $data['teacher_id']= $this->input->post('teacher_id');
            $data['rating']=$this->input->post('rating');
            $data['school_id'] = $_SESSION['school_id'];
            $data['student_id'] = $_SESSION['student_id'];
            $this->db->insert(get_school_db().'.teacher_rating', $data);
            $this->session->set_flashdata('club_updated', get_phrase('rating_submitted_successfully'));
            redirect(base_url() . 'evaluation/teacher_rating');
        }
        $page_data['page_name']  = 'teacher_rating';
        $this->load->view('backend/index', $page_data);
        
    }
	function stud_evaluation($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        if ($param1 == 'create') {
            $data['title']= $this->input->post('name');
            $data['status']=$this->input->post('status');
            $data['type']=$this->input->post('eval_type');
            $data['factor']=$this->input->post('factor');
            $data['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db().'.student_evaluation_questions', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
            redirect(base_url() . 'evaluation/stud_evaluation');
        }
        else if ($param1 == 'do_update') 
        {
            $data['title']        = $this->input->post('name');
            $data['status']        = $this->input->post('status1');
            $data['type']=$this->input->post('eval_type');
            $data['factor']=$this->input->post('factor');
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('eval_id', $param2);
            $this->db->update(get_school_db().'.student_evaluation_questions', $data);
            
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'evaluation/stud_evaluation');
        } 
        
        
        
        else if ($param1 == 'delete') 
        {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('eval_id', $param2);
            $this->db->delete(get_school_db().'.student_evaluation_questions');
            
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'evaluation/stud_evaluation');
        }
        
        $this->db->where('school_id',$_SESSION['school_id']);
        $page_data['eval']      = $this->db->select("*")->from(get_school_db().'.student_evaluation_questions')->order_by('eval_id', 'asc')->get()->result_array();
        
        $page_data['page_title'] = get_phrase('student_evaluation');
        
        
         $q="SELECT * FROM ".get_school_db().".evaluation_ratings WHERE school_id=".$_SESSION['school_id']." AND type='stud_eval'";
        $misc=$this->db->query($q)->result_array();
        
        $page_data['misc']      = $misc;
        
        $page_data['page_name']  = 'student_evaluation';
        $this->load->view('backend/index', $page_data);
        
    }
    
    function evaluation_rating($param1 = '', $param2 = ''){
        if ($_SESSION['user_login'] != 1)
            redirect('login');
    	if ($param1 == 'create') {
    	    $data['type']= $this->input->post('type');      
    		$data['detail']= $this->input->post('detail');
    		$data['status']= $this->input->post('status');
    		$data['school_id'] = $_SESSION['school_id'];
    		$this->db->insert(get_school_db().'.evaluation_ratings', $data);
    		$query=$this->db->affected_rows();
            $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
            redirect(base_url() . 'evaluation/stud_evaluation');
    	}
    	else if ($param1 == 'do_update') {
    	    $data['type']= $this->input->post('type');      
    		$data['detail']= $this->input->post('detail');
    		$data['status']= $this->input->post('status');
    		$misc_id=$this->input->post('misc_id');
    		
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('misc_id', $param2);
            $this->db->update(get_school_db().'.evaluation_ratings', $data);
            
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'evaluation/stud_evaluation');
    	}
    	else if ($param1 == 'delete') {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('misc_id', $param2);
            $this->db->delete(get_school_db().'.evaluation_ratings');
            
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'evaluation/stud_evaluation');
        }
        
        
	}

    
    
}