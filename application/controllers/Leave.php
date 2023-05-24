<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start();
class Leave extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if($_SESSION['user_login'] != 1)
		redirect('login');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	
	function manage_leaves($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        if ($param1 == 'create') 
        {
			$data['name']= $this->input->post('name');
			$data['school_id'] = $_SESSION['school_id'];
			$this->db->insert(get_school_db().'.leave_category', $data);
			$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
			redirect(base_url() . 'leave/manage_leaves');
        }
        elseif ($param1 == 'do_update') 
        {
            $data['name']        = $this->input->post('name');
  			$data['leave_category_id']      = $this->input->post('leave_category_id');
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('leave_category_id', $param2);
            $this->db->update(get_school_db().'.leave_category', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'leave/manage_leaves');
        } 
        
        else if ($param1 == 'edit') 
        {
			$page_data['edit_data'] = $this->db->get_where(get_school_db().'.leave_category', 
					array( 'leave_category_id' => $param2, 'school_id' =>$_SESSION['school_id'] ))->result_array();
        }
        
        else if ($param1 == 'delete') 
        {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('leave_category_id', $param2);
            $this->db->delete(get_school_db().'.leave_category');
            
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'leave/manage_leaves');
        }
        
        $this->db->where('school_id',$_SESSION['school_id']);
        $page_data['leaves'] = $this->db->select("*")->from(get_school_db().'.leave_category')->get()->result_array();
        $page_data['page_name'] = 'manage_leaves';
        $page_data['page_title'] = get_phrase('leave_categories');
        $this->load->view('backend/index', $page_data);
    }
    
    /****** Student Leave Management *****************/
	function manage_leaves_student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        $this->load->helper('message');    
        if ($param1 == 'create') 
        {
        	$data['leave_category_id']=$this->input->post('leave_category_id');
        	$data['student_id']=$this->input->post('student_id_add');
        	$start_date=$this->input->post('start_date');
			$start_arry=explode('/',$start_date);
			$data['start_date'] = $start_date;
        	$end_date=$this->input->post('end_date');
        	$end_arry=explode('/',$end_date);
			$data['end_date'] = $end_date;
        	
        	$data['reason']=$this->input->post('reason');
        	$data['request_date']=date("Y-m-d");
        	$data['status']=0;
        	$data['school_id']=$_SESSION['school_id'];
        	$data['requested_by']=$_SESSION['login_detail_id'];
        	
        	$filename=$_FILES['userfile']['name'];
        	if($filename!="")
        	{
				$data['proof_doc']=file_upload_fun('userfile','leaves_student','');
			}
			$this->db->insert(get_school_db().'.leave_student',$data);
			redirect(base_url() . 'leave/manage_leaves_student/');
        }
        
         if ($param1 == 'do_update') 
        {
        	
        	$data['student_id']=$this->input->post('student_id_add');
        	$data['leave_category_id']=$this->input->post('leave_type_add');
        	$data['reason']=$this->input->post('reason_add');
        	
        	$start_date=$this->input->post('start_date_add');
			$start_arry=explode('/',$start_date);
			$data['start_date'] = $start_date;
			
        	$end_date=$this->input->post('end_date_add');
        	$end_arry=explode('/',$end_date);
			$data['end_date'] = $end_date;
			
			$approve_start_date=$this->input->post('approved_upto_date');
			$data['approved_upto_date'] = $approve_start_date;
			
        	$approve_end_date=$this->input->post('final_end_date');
			$data['final_end_date'] = $approve_end_date;
			
        	$filename=$_FILES['image2']['name'];
        	
			 if($filename!="")
			 {
				$data['proof_doc']=file_upload_fun('image2','leaves_student');
				$image_old = $this->input->post('image_old');
				if($image_old!="")
				{
			 		$del_location = system_path($image_old,'leaves_student');
                    file_delete($del_location);
				}
			}
	
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('request_id', $param2);
            $this->db->update(get_school_db().'.leave_student', $data);
            redirect(base_url() . 'leave/manage_leaves_student/');		
        	
        }
        
        
        if ($param1 == 'approve') {
			
			$query_id	    =	$this->db->get_where(get_school_db().'.leave_student' , array( 'request_id' => $param2, 'school_id' => $_SESSION['school_id']));
			$res_id	        =	$query_id->result_array();
			
			foreach($res_id as $row_id){
			    if($row_id['approved_upto_date'] != "")
			    {
			      $start_date = $row_id['approved_upto_date'];  
			    }else{
			      $start_date = $row_id['start_date'];  
			    }
			    
			    if($row_id['final_end_date'] != "")
			    {
			      $end_date = $row_id['final_end_date'];  
			    }else{
			      $end_date = $row_id['end_date'];  
			    }
			    
				$s	        =	$start_date;
				$d	        =	$end_date;
				$student_id	=	$row_id['student_id'];

			}
			
			$device_id   =   get_user_device_id(6 , $student_id , $_SESSION['school_id']);
            $title       =   "Leave Request Approved";
            $message     =   "Your Leave Request Has been Approved By The School Admin.";
            $link        =    base_url()."student/leave/manage_student_leaves";
            sendNotificationByUserId($device_id , $title , $message , $link ,  $student_id , 6);
            
            $student_info =  get_student_info($student_id);
            $mob_num      =  $student_info[0]['mob_num'];
            $message      = "Your Leave Request Has been Approved By The School Admin.";
            $response     = send_sms($mob_num, 'INDICI EDU', $message, $student_id,12);
            
			$date_leave	 =	 $this->getDatesFromRange($s,$d);
			foreach($date_leave as $row_date){
				$q     = "SELECT * FROM ".get_school_db().".attendance WHERE date='".$row_date."' AND student_id=$student_id 
				          AND school_id=".$_SESSION['school_id']."";
				$query = $this->db->query($q)->result_array();
				
				if(count($query) > 0)
				{
					$data1['user_id'] = $_SESSION['login_detail_id'];
					$data1['status']  = 3;
					$this->db->where('date', $row_date);
					$this->db->where('school_id',$_SESSION['school_id']);
			        $this->db->where('student_id', $student_id);
                    $this->db->update(get_school_db().'.attendance', $data1);
				}
				else
				{
    				$data_new['date']        = $row_date;
    				$data_new['student_id']  = $student_id;
    				$data_new['status']      = 3;
    				$data_new['school_id']   = $_SESSION['school_id'];
    				$data_new['user_id']     = $_SESSION['login_detail_id'];
    				$this->db->insert(get_school_db().'.attendance', $data_new);
				}
			}
			
			//added by interns starts
			$parent_idd = get_parent_idd($student_id);
  
            $stdname    =    get_student_info($student_id);
            $std_name   = $stdname[0]['student_name'];
            $d=array(
            'title'=> $std_name,
            'body'=>'Your Leave Request Has been Approved By The School Admin.',
            );
            $d2 = array(
            'screen'=>'leave_request',
            'student_id'=> $student_id,
            );
                   
            
        if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
            
            //added by interns ends  
        
			$data['status']         = 1;
			$data['process_date']   = date('Y-m-d');
			$data['process_by']     = $_SESSION['login_detail_id'];
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('request_id', $param2);
            $this->db->update(get_school_db().'.leave_student', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'leave/manage_leaves_student');
            
        }
		
        if ($param1 == 'reject') {
            
            $query_id	=	$this->db->get_where(get_school_db().'.leave_student' , array( 'request_id' => $param2 , 'school_id' => $_SESSION['school_id']));
           
			$res_id	    =	$query_id->result_array();
		
			foreach($res_id as $row_id){
				$student_id	 =	$row_id['student_id'];
			
			}
			
			$device_id   =    get_user_device_id(6 , $student_id , $_SESSION['school_id']);
			
            $title       =   "Leave Request Rejected";
            $message     =   "Your Leave Request Has been Rejected By The School Admin.";
            $link        =    base_url()."student/leave/manage_student_leaves";
             // print_r("gshfh");exit;
            sendNotificationByUserId($device_id , $title , $message , $link ,  $student_id ,6);
            
            //added by interns starts
			$parent_idd = get_parent_idd($student_id);
  
            $stdname    =    get_student_info($student_id);
            $std_name   = $stdname[0]['student_name'];
            $d=array(
            'title'=> $std_name,
            'body'=>'Your Leave Request Has been Rejected By The School Admin.',
            );
            $d2 = array(
            'screen'=>'leave_request',
            'student_id'=> $student_id,
            );
                   
           
            
                    if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
            
            //added by interns ends  
          
            
            $student_info =  get_student_info($student_id);
            
            $mob_num      =  $student_info[0]['mob_num'];
            $message      =  "Your Leave Request Has been Rejected By The School Admin.";
            $response     =  send_sms($mob_num, 'INDICI EDU', $message, $student_id);
            
            
			$data['status']         =  2;
			$data['process_date']   =  date('Y-m-d');
			$data['process_by']     =  $_SESSION['login_detail_id'];
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('request_id', $param2);
            $this->db->update(get_school_db().'.leave_student', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'leave/manage_leaves_student');
            
        }

        if ($param1 == 'delete') {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('request_id', $param2);
            $this->db->delete(get_school_db().'.leave_student');
            $image_old = $param3;
 			$del_location=system_path($image_old,'leaves_student');
            file_delete($del_location);
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'leave/manage_leaves_student/'.$param3);
        }
        
        $this->db->where('school_id',$_SESSION['school_id']);
	    $page_data['leaves']     = $this->db->select("*")->from(get_school_db().'.leave_student')->order_by('request_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'manage_leaves_student';
        $page_data['page_title'] = get_phrase('manage_student_leaves');
        $this->load->view('backend/index', $page_data);
        
    }

    function get_year_term()
    {
		if($this->input->post('acad_year')!="")
		{
			echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'),'',1);
		}
	}
	
    function get_class()
    {
		echo  class_option_list($this->input->post('department_id'),$this->input->post('class_id'));
	}
	
    function get_class_section()
	{		
	    echo section_option_list($this->input->post('class_id'));
	}
	
    function get_section_student()
	{
		echo section_student($this->input->post('section_id'));
	}
	
	function getDatesFromRange($start, $end) 
	{
		$interval = new DateInterval('P1D');
		$realEnd  = new DateTime($end);
		$realEnd->add($interval);
	
		$period = new DatePeriod( new DateTime($start), $interval, $realEnd );
	
		foreach($period as $date) { 
			$array[] = $date->format('Y-m-d'); 
		}
	
		return $array;
	}
	
	function get_year_term2()
    {
 		echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'),'',1);
 	}
 	
 	function stud_leave_generator()
 	{
	    $this->load->view('backend/admin/ajax/get_leave_stud.php');
	}
	
	function term_date_range()
	{
		if(!empty($this->input->post('start_date')))
		{
			echo term_date_range($this->input->post('term_id'),$this->input->post('start_date'),'');
		}
		if(!empty($this->input->post('end_date')))
		{
			echo term_date_range($this->input->post('term_id'),'',$this->input->post('end_date'));
		}
		
	}
	
	
	
	
}