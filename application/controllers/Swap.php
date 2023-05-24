<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Swap extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if($_SESSION['user_login']!= 1)
		   redirect(base_url() . 'login');

    }

    public function index()
    {
        if ($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    function teacher_swapping($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url());
            
        if ($param1 == 'create') 
        {
        	$data['title']=$this->input->post('title');
        	$swap_date=$this->input->post('swap_date');
        
        	$swap_arry=explode('/',$swap_date);
			$data['swap_date']=$swap_arry[2].'-'.$swap_arry[1].'-'.$swap_arry[0];
			$swap_arry[2].'-'.$swap_arry[1].'-'.$swap_arry[0];
			
        	$data['comments']=$this->input->post('comments');
        	$data['school_id']=$_SESSION['school_id'];
        	$this->db->insert(get_school_db().'.swap', $data);
        	$frm_class_rout_id=$this->input->post('frm_class_rout_id');
        	$swap_id=$this->db->insert_id();
        	$data1['swap_id']=$swap_id;
        	$data1['teacher_id']=$this->input->post('from_teacher_id');
        	$data1['swap_type']=1;
        	$data1['c_rout_id']=$frm_class_rout_id;
        	$q="SELECT cr.*,crs.section_id FROM ".get_school_db().".class_routine_settings crs INNER JOIN ".get_school_db().".class_routine cr  ON crs.c_rout_sett_id=cr.c_rout_sett_id WHERE cr.school_id=".$_SESSION['school_id']." AND cr.class_routine_id=$frm_class_rout_id";
        	$frm_query=$this->db->query($q)->result_array();
        	$data1['subject_id']=$frm_query[0]['subject_id'];
        	$data1['section_id']=$frm_query[0]['section_id'];
        	$data1['day']=$frm_query[0]['day'];
        	$data1['period_no']=$frm_query[0]['period_no'];
        	$data1['start_time']=$frm_query[0]['period_start_time'];
        	$data1['end_time']=$frm_query[0]['period_end_time'];
        	$data1['duration']=$frm_query[0]['duration'];
        	$data1['school_id']=$_SESSION['school_id'];
        	$this->db->insert(get_school_db().'.swap_detail', $data1);

        	$to_class_rout_arr=$this->input->post('to_class_rout_id');
        	$to_class_rout_arr=explode("/",$to_class_rout_arr);
        	$to_class_rout_id=$to_class_rout_arr[0];
        	$to_teacher_id=$to_class_rout_arr[1];
        	
        	$p="SELECT cr.*,crs.section_id FROM ".get_school_db().".class_routine_settings crs INNER JOIN ".get_school_db().".class_routine cr  ON crs.c_rout_sett_id=cr.c_rout_sett_id WHERE cr.school_id=".$_SESSION['school_id']." AND cr.class_routine_id=$to_class_rout_id";
        	$to_query=$this->db->query($p)->result_array();	
        	$data2['swap_id']=$swap_id;
        	$data2['teacher_id']=$to_teacher_id;
        	$data2['swap_type']=2;
        	$data2['c_rout_id']=$to_class_rout_id;
	        $data2['subject_id']=$to_query[0]['subject_id'];
        	$data2['section_id']=$to_query[0]['section_id'];
        	$data2['day']=$to_query[0]['day'];
        	$data2['period_no']=$to_query[0]['period_no'];
        	$data2['start_time']=$to_query[0]['period_start_time'];
        	$data2['end_time']=$to_query[0]['period_end_time'];
        	$data2['duration']=$to_query[0]['duration'];
        	$data2['school_id']=$_SESSION['school_id'];				
			$this->db->insert(get_school_db().'.swap_detail', $data2);
	
			$this->session->set_flashdata('club_updated', get_phrase('record_added_successfully.'));
            redirect(base_url() . 'swap/swapping/');	
		}
		if ($param1 == 'edit') 
		{
			$p="SELECT * FROM ".get_school_db().".swap sw INNER JOIN  ".get_school_db().".swap_detail swd
			    ON sw.swap_id=swd.swap_id WHERE sw.swap_id=$param2 AND sw.school_id=".$_SESSION['school_id']." ";
			$edit_arr=$this->db->query($p)->result_array();
			$page_data['edit_arr']=$edit_arr;
			$page_data['edit']='edit';
		}
		if ($param1 == 'delete') {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('swap_id', $param2);
            $this->db->delete(get_school_db().'.swap');
            
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('swap_id', $param2);
            $this->db->delete(get_school_db().'.swap_detail');
            
			$this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'swap/swapping/');
        }
         if ($param1 == 'do_update') 
         {
         	$swap_id=$this->uri->segment('4');
         	$data['title']=$this->input->post('title');
        	$swap_date=$this->input->post('swap_date');
        
        	$swap_arry=explode('/',$swap_date);
			$data['swap_date']=$swap_arry[2].'-'.$swap_arry[1].'-'.$swap_arry[0];
			$swap_arry[2].'-'.$swap_arry[1].'-'.$swap_arry[0];
			
        	$data['comments']=$this->input->post('comments');
        	$data['school_id']=$_SESSION['school_id'];
        	$this->db->where('swap_id',$swap_id);
        	$this->db->update(get_school_db().'.swap', $data);
        	
        	$frm_class_rout_id=$this->input->post('frm_class_rout_id');
        	$data1['swap_id']=$swap_id;
        	$data1['teacher_id']=$this->input->post('from_teacher_id');
        	
        	$data1['c_rout_id']=$frm_class_rout_id;
        	$q="SELECT cr.*,crs.section_id FROM ".get_school_db().".class_routine_settings crs INNER JOIN ".get_school_db().".class_routine cr  ON crs.c_rout_sett_id=cr.c_rout_sett_id WHERE cr.school_id=".$_SESSION['school_id']." AND cr.class_routine_id=$frm_class_rout_id";
        	$frm_query=$this->db->query($q)->result_array();
        	$data1['subject_id']=$frm_query[0]['subject_id'];
        	$data1['section_id']=$frm_query[0]['section_id'];
        	$data1['day']=$frm_query[0]['day'];
        	$data1['period_no']=$frm_query[0]['period_no'];
        	$data1['start_time']=$frm_query[0]['period_start_time'];
        	$data1['end_time']=$frm_query[0]['period_end_time'];
        	$data1['duration']=$frm_query[0]['duration'];
        	$data1['school_id']=$_SESSION['school_id'];
        	$this->db->where('school_id',$_SESSION['school_id']);
        	$this->db->where('swap_type',1);
        	$this->db->where('swap_id',$swap_id);
        	$this->db->update(get_school_db().'.swap_detail',$data1);
        	//echo $this->db->last_query();
        	$to_class_rout_arr=$this->input->post('to_class_rout_id');
        	$to_class_rout_arr=explode("/",$to_class_rout_arr);
        	$to_class_rout_id=$to_class_rout_arr[0];
        	$to_teacher_id=$to_class_rout_arr[1];
        	
        	$p="SELECT cr.*,crs.section_id FROM ".get_school_db().".class_routine_settings crs INNER JOIN ".get_school_db().".class_routine cr  ON crs.c_rout_sett_id=cr.c_rout_sett_id WHERE cr.school_id=".$_SESSION['school_id']." AND cr.class_routine_id=$to_class_rout_id";
        	$to_query=$this->db->query($p)->result_array();	
        	$data2['swap_id']=$swap_id;
        	$data2['teacher_id']=$to_teacher_id;
        	$data2['swap_type']=2;
        	$data2['c_rout_id']=$to_class_rout_id;
	        $data2['subject_id']=$to_query[0]['subject_id'];
        	$data2['section_id']=$to_query[0]['section_id'];
        	$data2['day']=$to_query[0]['day'];
        	$data2['period_no']=$to_query[0]['period_no'];
        	$data2['start_time']=$to_query[0]['period_start_time'];
        	$data2['end_time']=$to_query[0]['period_end_time'];
        	$data2['duration']=$to_query[0]['duration'];
        	$data2['school_id']=$_SESSION['school_id'];	
        	$this->db->where('school_id',$_SESSION['school_id']);
        	$this->db->where('swap_type',2);
        	$this->db->where('swap_id',$swap_id);
        	$this->db->update(get_school_db().'.swap_detail',$data2);
        	
        	$this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'swap/swapping/');

		 }

        $teacher_id=$this->uri->segment(3);
        if(isset($teacher_id) && ($teacher_id >0))
        {
			$page_data['teacher_id']=$teacher_id;
		} 
        
        $page_data['page_name']  = 'add_teacher_swap';
        $page_data['page_title'] = get_phrase('add_teacher_swap');
        $this->load->view('backend/index', $page_data);
    }

    function from_teacher_result()
    {
    	$swap_date=$this->input->post('date');
    	$swap_arry=explode('/',$swap_date);
    	$swap_date=$swap_arry[2].'-'.$swap_arry[1].'-'.$swap_arry[0];
    	$swap_date=strtotime($swap_date);
    	$day=date('l',$swap_date);
    	$day=strtolower($day);
    	$period_no=$this->input->post('period_no');
    	$teacher_id=$this->input->post('teacher_id');
    	$to_c_rout_id=$this->input->post('to_c_rout_id');
    	$title=$this->input->post('title');
    	$comments=$this->input->post('comments');
    	$to_teacher_id=$this->input->post('to_teacher_id');
    	
    	$page_data['to_c_rout_id']=$to_c_rout_id;
    	$page_data['day']=$day;
    	$page_data['period_no']=$period_no;
    	$page_data['teacher_id']=$teacher_id;
    	$page_data['title']=$title;
    	$page_data['comments']=$comments;
    	$page_data['to_teacher_id']=$to_teacher_id;
    	$this->load->view('backend/admin/ajax/swap_ajax.php',$page_data);
    
    }

    function swapping()
    {
    	if ($_SESSION['user_login'] != 1)
                redirect(base_url());
                
        $per_page = 10;
        $page_num = $this->uri->segment(6);
    
        if ( !isset($page_num) || $page_num == "")
        {
            $page_num = 0;
            $start_limit = 0;
        }
        else
        {
            $start_limit = ($page_num-1)*$per_page;
        }
            
        $start_date=date_slash($this->input->post('starting'));
    	$end_date=date_slash($this->input->post('ending'));
    	$teacher_id=$this->input->post('teacher_select', TRUE);
    	if ( !isset($start_date) || $start_date == "")
        {
            $start_date=$this->uri->segment(4);
        }
    
        if ( !isset($start_date) || $start_date == "")
        {
            $start_date = 0;
        }
            
        $date_query="";
    	if($start_date!='' && $start_date!=0)
    	{
    		$date_query=" AND sw.swap_date >= '".$start_date."'";
    	}
    	
    	if ( !isset($end_date) || $end_date == "")
        {
            $end_date=$this->uri->segment(5);
        }
    
        if ( !isset($end_date) || $end_date == "")
        {
            $end_date = 0;
        }
        
    	if($end_date!='' && $end_date!=0)
    	{
    		$date_query=" AND sw.swap_date <= '".$end_date."'";
    	}
    		
    	if($start_date!='' && $end_date!='' && $start_date!=0 && $end_date!=0)
    	{
    		$date_query=" AND sw.swap_date >= '".$start_date."' AND swap_date <= '".$end_date."' ";
    	}
    	
    	
    	if ( !isset($teacher_id) || $teacher_id == "")
        {
            $teacher_id=$this->uri->segment(3);
        }
    
        if ( !isset($teacher_id) || $teacher_id == "")
        {
            $teacher_id = 0;
        }
            
        $teacher_query="";
    	if(isset($teacher_id) && ($teacher_id >0))
     	{
     	    $teacher_query .= "INNER JOIN ".get_school_db().".swap_detail swd ON sw.swap_id=swd.swap_id";
     	    $teacher_query .= " AND swd.teacher_id=$teacher_id";
     	}else{
     	    $teacher_query .= "INNER JOIN ".get_school_db().".swap_detail swd ON sw.swap_id=swd.swap_id";
     	}
     	
    	$q = "SELECT * FROM ".get_school_db().".swap sw ". $teacher_query." WHERE sw.school_id=".$_SESSION['school_id']." ". $date_query."  GROUP BY swd.swap_id ORDER BY sw.swap_date desc";
    	$swap_quer = $this->db->query($q)->result_array();
    	$config['base_url'] = base_url()."swap/swapping/".$teacher_id."/".$start_date."/".$end_date;

        $page_data['apply_filter']=$apply_filter;
    	$page_data['swap_quer']= $swap_quer; 
        $page_data['start_date']=$start_date;
        $page_data['end_date']=$end_date;
        $page_data['teacher_id']=$teacher_id;
        $page_data['page_title'] = get_phrase('add_teacher_swap');
        $page_data['page_name']  = 'manage_teacher_swap';
        $this->load->view('backend/index', $page_data);
    }



}
	