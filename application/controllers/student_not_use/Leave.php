<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

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
	
	function manage_student_leaves(){
	    $school_id = $_SESSION['school_id'];
	    $std_id    = $_SESSION['student_id'];
	    $page_data['leaves'] = $this->db->query("select sl.* from  ".get_school_db().".leave_student sl where 
			                                     sl.student_id=$std_id and sl.school_id= $school_id 
			                                     order by sl.request_id desc ")->result_array();
		$page_data['page_name'] = 'leave_request';
		$page_data['page_title'] = get_phrase('leave_requests');
		$this->load->view('backend/index', $page_data);
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
					array(
						'leave_category_id' => $param2,
						'school_id' =>$_SESSION['school_id']
					 ))->result_array();

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
		//->order_by('leave_category_id', 'desc')
        $page_data['page_name'] = 'manage_leaves';
        $page_data['page_title'] = get_phrase('leave_categories');
        $this->load->view('backend/index', $page_data);
    }
    
    /****** Student Leave Management *****************/
	function manage_leaves_student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        if ($param1 == 'create') 
        {
        	$data['leave_category_id']=$this->input->post('leave_category_id');
        	$data['student_id']=$this->input->post('student_id_add');
        	$start_date=$this->input->post('start_date');
			$start_arry=explode('/',$start_date);
			$data['start_date']=$start_arry[2].'-'.$start_arry[1].'-'.$start_arry[0];
        	$end_date=$this->input->post('end_date');
        	$end_arry=explode('/',$end_date);
			$data['end_date']=$end_arry[2].'-'.$end_arry[1].'-'.$end_arry[0];
        	
        	
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
			//echo $this->db->last_query();
			//exit;
			redirect(base_url() . 'leave/manage_leaves_student/');
        }
        
         if ($param1 == 'do_update') 
        {
        	
        	$data['student_id']=$this->input->post('student_id_add');
        	$data['leave_category_id']=$this->input->post('leave_type_add');
        	$data['reason']=$this->input->post('reason_add');
        	$start_date=$this->input->post('start_date_add');
			$start_arry=explode('/',$start_date);
			$data['start_date']=$start_arry[2].'-'.$start_arry[1].'-'.$start_arry[0];
			
        	$end_date=$this->input->post('end_date_add');
        	$end_arry=explode('/',$end_date);
			$data['end_date']=$end_arry[2].'-'.$end_arry[1].'-'.$end_arry[0];
        	$filename=$_FILES['image2']['name'];
        	
			 if($filename!="")
			 {
				$data['proof_doc']=file_upload_fun('image2','leaves_student');
				$image_old = $this->input->post('image_old');
				if($image_old!="")
				{
			 		$del_location=system_path($image_old,'leaves_student');
			 		//exit;
                    file_delete($del_location);
				}
			}
	
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('request_id', $param2);
            $this->db->update(get_school_db().'.leave_student', $data);
            redirect(base_url() . 'leave/manage_leaves_student/');		
        	
        }
        
        
        if ($param1 == 'approve') {
			
			$query_id	=	$this->db->get_where(get_school_db().'.leave_student' , array(
			'request_id' =>$param2,
			'school_id' =>$_SESSION['school_id']
			));
			$res_id	=	$query_id->result_array();
			
			foreach($res_id as $row_id){
				$s	=	$row_id['start_date'];
				$d	=	$row_id['end_date'];
				$student_id	=	$row_id['student_id'];
				
			}
			$date_leave	=	$this->getDatesFromRange($s,$d);
			foreach($date_leave as $row_date){
				$q="SELECT * FROM ".get_school_db().".attendance WHERE date='".$row_date."' AND student_id=$student_id AND school_id=".$_SESSION['school_id']."";
				$query=$this->db->query($q)->result_array();
				
				if(count($query) > 0)
				{
					$data1['user_id'] =$_SESSION['login_detail_id'];
					$data1['status']      = 3;
					
					$this->db->where('date', $row_date);
					$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('student_id', $student_id);
            $this->db->update(get_school_db().'.attendance', $data1);
				}
				else
				{
				$data_new['date']= $row_date;
				$data_new['student_id']= $student_id;
				$data_new['status']      = 3;
				$data_new['school_id']=$_SESSION['school_id'];
				/*$data_new['yearly_terms_id']=$yearly_terms_id;*/
				$data_new['user_id'] =$_SESSION['login_detail_id'];
				
				$this->db->insert(get_school_db().'.attendance', $data_new);
				}
			}
			$data['status'] = 1;
			$data['process_date']      = date('Y-m-d');
			$data['process_by'] =$_SESSION['login_detail_id'];
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('request_id', $param2);
            $this->db->update(get_school_db().'.leave_student', $data);
            //echo $this->db->last_query();
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'leave/manage_leaves_student');
        }
		
        if ($param1 == 'reject') {
			$data['status'] = 2;
			$data['process_date']      = date('Y-m-d');
			$data['process_by'] = $_SESSION['login_detail_id'];
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
	    //echo $this->db->last_query();
        $page_data['page_name']  = 'manage_leaves_student';
        $page_data['page_title'] = get_phrase('manage_student_leaves');
        $this->load->view('backend/index', $page_data);
        
    }

function get_year_term()
    {
		$acad_year=$this->input->post('acad_year');
		if($acad_year!="")
		{
			echo $yearly_term=yearly_terms_option_list($acad_year,'',1);
		}
 		
	}
function get_class()
 {
	$dept_id=$this->input->post('department_id');
		$class_id=$this->input->post('class_id');
		echo  class_option_list($dept_id,$class_id);

	}
function get_class_section()
	{
		$class_id=$this->input->post('class_id');
		echo section_option_list($class_id);

	}
function get_section_student()
	{
		
		$section_id=$this->input->post('section_id');
		
		echo section_student($section_id);

	}
	
	
	function getDatesFromRange($start, $end) 
	{
		$interval = new DateInterval('P1D');
	
		$realEnd = new DateTime($end);
		$realEnd->add($interval);
	
		$period = new DatePeriod(
			 new DateTime($start),
			 $interval,
			 $realEnd
		);
	
		foreach($period as $date) { 
			$array[] = $date->format('Y-m-d'); 
		}
	
		return $array;
	}
	
	function get_year_term2()
    {
	$acad_year=$this->input->post('acad_year');
 		echo $yearly_term=yearly_terms_option_list($acad_year,'',1);
 	}
 	
 	function stud_leave_generator()
 	{
	$this->load->view('backend/admin/ajax/get_leave_stud.php');
	}
	
	function term_date_range()
	{
		$start_date=date('Y-m-d',strtotime($this->input->post('start_date')));
		$end_date=date('Y-m-d',strtotime($this->input->post('end_date')));
		$term_id=$this->input->post('term_id');
		if(!empty($start_date))
		{
			echo term_date_range($term_id,$start_date,'');
		}
		if(!empty($end_date))
		{
			echo term_date_range($term_id,'',$end_date);
		}
		
	}
	
	
	
	
}