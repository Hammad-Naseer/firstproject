<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start();
class Staff_evaluation extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if($_SESSION['user_login'] != 1)
		redirect('login');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	function staff_eval($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        if ($param1 == 'create') 
        {
        	
            $data['title']= $this->input->post('name');
            $data['status']=$this->input->post('status');
            $data['school_id'] = $_SESSION['school_id'];
            $data['factor']=$this->input->post('factor');
            $this->db->insert(get_school_db().'.staff_evaluation_questions', $data);
            $this->session->set_flashdata('club_updated', get_phrase('recored_saved_successfully'));
            redirect(base_url() . 'staff_evaluation/staff_eval');
        }
        else if ($param1 == 'do_update') 
        {
            $data['title'] = $this->input->post('name');
            $data['status']=$this->input->post('status1');
            $data['factor']=$this->input->post('factor');
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('staff_eval_form_id', $param2);
            $this->db->update(get_school_db().'.staff_evaluation_questions', $data);
            $this->session->set_flashdata('club_updated', get_phrase('recored_updated_successfully'));
            redirect(base_url() . 'staff_evaluation/staff_eval');
        } 
        else if ($param1 == 'delete') 
        {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('staff_eval_form_id', $param2);
            $this->db->delete(get_school_db().'.staff_evaluation_questions');
            $this->session->set_flashdata('club_updated', get_phrase('recored_deleted_successfully'));
            redirect(base_url() . 'staff_evaluation/staff_eval');
        }
        
        $this->db->where('school_id',$_SESSION['school_id']);
        $page_data['eval']  = $this->db->select("*")->from(get_school_db().'.staff_evaluation_questions')->order_by('staff_eval_form_id', 'asc')->get()->result_array();
        
        
        $q1="SELECT * FROM ".get_school_db().".evaluation_ratings WHERE school_id=".$_SESSION['school_id']." AND type='staff_eval'";
        $misc_staff=$this->db->query($q1)->result_array();
        $page_data['misc_staff']      = $misc_staff;
        $page_data['page_name']  = 'manage_staff_evaluation';
        $page_data['page_title'] = get_phrase('staff_evaluation');
        
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
            redirect(base_url() . 'staff_evaluation/staff_eval');
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
            redirect(base_url() . 'staff_evaluation/staff_eval');
    	}
    	else if ($param1 == 'delete') {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('misc_id', $param2);
            $this->db->delete(get_school_db().'.evaluation_ratings');
            
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'staff_evaluation/staff_eval');
        }
	}


	function staff_eval_generator()
	{		
		$page_data['staff_id']=$this->input->post('staff_id');
		$page_data['start_date']=$this->input->post('start_date');
		$page_data['end_date']=$this->input->post('end_date');
		$this->load->view('backend/admin/ajax/get_staff_eval.php',$page_data);
	}
	
	function staff_eval_generator2()
	{
		$page_data['staff_id']=$this->uri->segment(3);
		$page_data['start_date']=$this->uri->segment(4);
		$page_data['end_date']=$this->uri->segment(5);
		$this->load->view('backend/admin/ajax/get_staff_eval.php',$page_data);
	}

    function evaluation($param1 = '', $param2 = '', $param3 = '')
    {
    	
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        if ($param1 == 'create') 
        {    
      	
            $data['remarks']= $this->input->post('remarks');
            $data['school_id'] = $_SESSION['school_id'];
            $data['staff_id']=$this->input->post('staff_id_select');
            $evaluation_date =$this->input->post('evaluation_date');
            $eval_arry=explode('/',$evaluation_date);
            $data['evaluation_date']=$eval_arry[2].'-'.$eval_arry[1].'-'.$eval_arry[0];
            $data['answers']=$this->input->post('ratings');
            $filename=$_FILES['image1']['name'];
            $folder_name = $_SESSION['folder_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($filename!="")
            {
             $data['attachment']=file_upload_fun('image1','staff_evaluation','');
            }
            $this->db->insert(get_school_db().'.staff_evaluation', $data);

            $staff_eval_array=$this->input->post('staff_eval_array');


            if(count($staff_eval_array) > 0)
            {
                $staff_eval_id = $this->db->insert_id();
                foreach($staff_eval_array as $key=>$val)
                {
                    $data2['staff_eval_id']=$staff_eval_id;
                    $data2['staff_eval_form_id']=$staff_eval_array[$key]['staff_eval_form_id'];
                    $data2['answers']=$staff_eval_array[$key]['answers_select'];
                    $data2['remarks']=$staff_eval_array[$key]['response'];
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.staff_evaluation_answers', $data2);
                }

            }

            $this->session->set_flashdata('club_updated', get_phrase('recored_saved_successfully'));
            redirect(base_url() . 'staff_evaluation/evaluation');
        }
        else if ($param1 == 'do_update') 
        {

            $staff_eval_array=$this->input->post('staff_eval_array');
        	if(count($staff_eval_array) > 0)
			{
				foreach($staff_eval_array as $key=>$val)
				{
					$staff_eval_id = $this->input->post('staff_eval_id');
					$staff_eval_form_id=$staff_eval_array[$key]['staff_eval_form_id'];
					$data2['answers']=$staff_eval_array[$key]['answers_select'];
					$data2['remarks']=$staff_eval_array[$key]['response'];
					$staf_eval_ans_id=$staff_eval_array[$key]['staf_eval_ans_id'];
					if(isset($staf_eval_ans_id) && ($staf_eval_ans_id > 0))
					{
    					$this->db->where('school_id',$_SESSION['school_id']);
    					$this->db->where('staff_eval_form_id', $staff_eval_form_id);
                		$this->db->where('staff_eval_id', $staff_eval_id);
                		$this->db->update(get_school_db().'.staff_evaluation_answers', $data2);
					}
					else
					{
    					$data2['staff_eval_id']	=$staff_eval_id;
    					$data2['staff_eval_form_id']=$staff_eval_form_id;
    					$data2['school_id']=$_SESSION['school_id'];	
    					$this->db->insert(get_school_db().'.staff_evaluation_answers', $data2);
					}
					//echo $this->db->last_query()."<br>";
				}
		
			}
			
			$staff_id = $this->input->post('staff_id_select');
			$evaluation_date=$this->input->post('evaluation_date');
			
			$eval_arry=explode('/',$evaluation_date);
			$data['evaluation_date']=$eval_arry[2].'-'.$eval_arry[1].'-'.$eval_arry[0];
        	$data['staff_id']= $staff_id;
			$data['answers']=$this->input->post('ratings');
			$data['remarks']=$this->input->post('remarks');
			
			$staff_eval_id=$this->input->post('staff_eval_id');
            $filename=$_FILES['image1']['name'];
            $folder_name = $_SESSION['folder_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($filename!="")
            {
             $data['attachment']=file_upload_fun('image1','staff_evaluation','');
            }
			
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('staff_eval_id', $staff_eval_id);
            $this->db->update(get_school_db().'.staff_evaluation', $data);
            $this->session->set_flashdata('club_updated', get_phrase('recored_updated_successfully'));
            redirect(base_url() . 'staff_evaluation/evaluation/'.$staff_id.'/'.$start_date.'/'.$end_date);
        } 
        else if ($param1 == 'delete') 
        {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('staff_eval_id', $param2);
            $this->db->delete(get_school_db().'.staff_evaluation');
            
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('staff_eval_id', $param2);
            $this->db->delete(get_school_db().'.staff_evaluation_answers');
            
            $image_old = $param3;
 			$del_location=system_path($image_old,'staff_evaluation');
            file_delete($del_location);
            $this->session->set_flashdata('club_updated', get_phrase('recored_deleted_successfully'));
            redirect(base_url() . 'staff_evaluation/evaluation');
        }
        
        $staff_id   = $this->input->post('staff_id');
		$start_date = $this->input->post('start_date');
		$end_date   = $this->input->post('end_date');
        
        $staff_query="";
        $date_query="";
        if(isset($staff_id)&& ($staff_id) > 0)
        {
            $staff_query=" AND se.staff_id=".$staff_id;
        }
        if($start_date!='')
        {
        	$date_query=" AND evaluation_date >= '".date("Y-m-d", strtotime(str_replace('/', '-', $start_date)))."'";
        }
        if($end_date!='')
        {
        	$date_query=" AND evaluation_date <= '".date("Y-m-d", strtotime(str_replace('/', '-', $end_date)))."'";
        }
        if($start_date!='' && $end_date!='')
        {
        	$date_query=" AND evaluation_date >= '".date("Y-m-d", strtotime(str_replace('/', '-', $start_date)))."' AND evaluation_date <= '".date("Y-m-d", strtotime(str_replace('/', '-', $end_date)))."' ";
        }
        $q="SELECT se.*,s.name as staff_name, designation_id FROM ".get_school_db().".staff_evaluation se
        INNER JOIN ".get_school_db().".staff s
        ON se.staff_id=s.staff_id
         WHERE se.school_id=".$_SESSION['school_id']." ".$staff_query . $date_query."";
         
        $query=$this->db->query($q)->result_array();
        
        $page_data['staff_id']   = $staff_id;   
        $page_data['start_date'] = $start_date; 
        $page_data['end_date']   = $end_date;
        $page_data['staff_evaluations']      = $query;

        $page_data['apply_filter'] = $this->input->post('apply_filter');
        $page_data['page_title'] = get_phrase('manage_staff_evaluation');
        $page_data['page_name']  = 'manage_staff_eval';
        $this->load->view('backend/index', $page_data);
        
    }
    
    function view_staff_add($param1 = '', $param2 = '', $param3 = '')
    {
    	$start_date=  str_decode($this->uri->segment(7));
    	$end_date= str_decode($this->uri->segment(8));
    	$page_data['staff_id']= str_decode($this->uri->segment(6));
    	$page_data['start_date']=$start_date;
    	$page_data['end_date']=$end_date;
    	
		$page_data['page_name']  = 'add_staff_evaluation';
        $this->load->view('backend/index', $page_data);
	}
	
	function view_evaluation_answers()
	{
		$page_data['page_name']  = 'details_staff_evaluation';
        $this->load->view('backend/index', $page_data);
	}
    
}