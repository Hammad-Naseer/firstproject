<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start();
class Leave_staff extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if($_SESSION['user_login'] != 1)
		redirect('login');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	function manage_leaves_staff($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        $this->load->helper('message'); 
        
        if ($param1 == 'create') 
        {
        	$data['leave_category_id']=$this->input->post('leave_category_id');
        	$data['staff_id']=$this->input->post('staff_id_add');
        	$start_date=$this->input->post('start_date');
        	$data['start_date']= $start_date;
        	$end_date=$this->input->post('end_date');
        	$data['end_date']= $end_date;
        	
        	$data['reason']=$this->input->post('reason');
        	$data['request_date']=date("Y-m-d");
        	$data['status']=0;
        	$data['school_id']=$_SESSION['school_id'];
        	$data['requested_by']=$_SESSION['login_detail_id'];
        	$filename=$_FILES['userfile']['name'];
        	if($filename!="")
        	{
				$data['proof_doc']=file_upload_fun('userfile','leaves_staff','');
			}
			$this->db->insert(get_school_db().'.leave_staff',$data);
			redirect(base_url() . 'leave_staff/manage_leaves_staff/');
        }
        
        if ($param1 == 'do_update') 
        {
        	
        	$data['staff_id']=$this->input->post('staff_id_add');
        	$data['leave_category_id']=$this->input->post('leave_type_add');
        	$data['reason']=$this->input->post('reason_add');
        	$start_date=$this->input->post('start_date_add');
        	$data['start_date']= $start_date;
        	$end_date=$this->input->post('end_date_add');
        	$data['end_date']= $end_date;
        	$filename=$_FILES['image2']['name'];
        	
			 if($filename!="")
			 {
				$data['proof_doc']=file_upload_fun('image2','leaves_staff');
				$image_old = $this->input->post('image_old');
				if($image_old!="")
				{
			 		$del_location=system_path($image_old,'leaves_staff');
                    file_delete($del_location);
				}
			}
	
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('leave_staff_id', $param2);
            $this->db->update(get_school_db().'.leave_staff', $data);
            redirect(base_url() . 'leave_staff/manage_leaves_staff/');		
        	
        }
        
        if ($param1 == 'approve') {
            
            
			
			$query_id	=	$this->db->get_where(get_school_db().'.leave_staff' , array(
			   'leave_staff_id' => $param2,
			   'school_id'      => $_SESSION['school_id']
			));
			
			$res_id	=	$query_id->result_array();
			
			foreach($res_id as $row_id){
				$s	        =	$row_id['start_date'];
				$d	        =	$row_id['end_date'];
				$staff_id	=	$row_id['staff_id'];
			}
			$date_leave	=	$this->getDatesFromRange($s,$d);
			
            // Send SMS
			$staff_info =  get_staff_detail($staff_id);
            $mob_num      =  $staff_info[0]['mobile_no'];
            $message      =  "Your Leave Request Has been Approved By The School Admin.";
            $response     =  send_sms($mob_num, 'INDICI EDU', $message, $staff_id,13);
            
			foreach($date_leave as $row_date){
				
				
				$q="SELECT * FROM ".get_school_db().".attendance_staff WHERE date='".$row_date."' AND staff_id=$staff_id AND school_id=".$_SESSION['school_id']."";
				$query=$this->db->query($q)->result_array();
				
				if(count($query) > 0)
				{
					$data1['user_id'] =$_SESSION['login_detail_id'];
					$data1['status']      = 3;
					$this->db->where('date', $row_date);
					$this->db->where('school_id',$_SESSION['school_id']);
        			$this->db->where('staff_id', $staff_id);
                    $this->db->update(get_school_db().'.attendance_staff', $data1);
				}
				else
				{
    				$data_new['date']= $row_date;
    				$data_new['staff_id']= $staff_id;
    				$data_new['status']      = 3;
    				$data_new['school_id']=$_SESSION['school_id'];
    				$data_new['user_id'] =$_SESSION['login_detail_id'];
    				$this->db->insert(get_school_db().'.attendance_staff', $data_new);
				}
			}
			$data['status'] = 1;
			$data['process_date']      = date('Y-m-d');
			$data['process_by'] =$_SESSION['login_detail_id'];
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('leave_staff_id', $param2);
            $this->db->update(get_school_db().'.leave_staff', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'leave_staff/manage_leaves_staff');
        }
		
        if ($param1 == 'reject') {
            
            $query_id	=	$this->db->get_where(get_school_db().'.leave_staff' , array(
			   'leave_staff_id' => $param2,
			   'school_id'      => $_SESSION['school_id']
			));
			
			$res_id	=	$query_id->result_array();
			
			foreach($res_id as $row_id){
				$s	        =	$row_id['start_date'];
				$d	        =	$row_id['end_date'];
				$staff_id	=	$row_id['staff_id'];
			}
            
			$data['status'] = 2;
			$data['process_date']      = date('Y-m-d');
			$data['process_by'] = $_SESSION['login_detail_id'];
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('leave_staff_id', $param2);
            $this->db->update(get_school_db().'.leave_staff', $data);
            
            // Send SMS
			$staff_info =  get_staff_detail($staff_id);
            $mob_num      =  $staff_info[0]['mobile_no'];
            $message      =  "Your Leave Request Has been Rejected By The School Admin.";
            $response     =  send_sms($mob_num, 'INDICI EDU', $message, $staff_id,13);
            
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'leave_staff/manage_leaves_staff');
        }

        if ($param1 == 'delete') {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('leave_staff_id', $param2);
            $this->db->delete(get_school_db().'.leave_staff');
            $image_old = $param3;
 			$del_location=system_path($image_old,'leaves_staff');
            file_delete($del_location);
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'leave_staff/manage_leaves_staff/'.$param3);
        }
        
        $this->db->where('school_id',$_SESSION['school_id']);

        $section_query="";
        $class_query="";
        $where_query ="";
        $start_date='';
        $end_date='';
        $leave_categ_id     = $_POST['leave_category_id'];
        $staff_id           = $_POST['staff_select'];
        $start_date         = $_POST['start_date'];
        $end_date           = $_POST['end_date'];

        if($start_date!='')
        {
        	$start_date_arr=explode("/",$start_date);
        	$start_date = $start_date_arr[2].'-'.$start_date_arr[0].'-'.$start_date_arr[1];
        }
        if($end_date!='')
        {
        	$end_date_arr=explode("/",$end_date);
        	$end_date=$end_date_arr[2].'-'.$end_date_arr[0].'-'.$end_date_arr[1];
        }
        if($start_date!='')
        {
        	$where_query .=" AND ls.start_date >= '".$start_date."'";
        }
        if($end_date!='')
        {
        	$where_query .=" AND ls.end_date <= '".$end_date."'";
        }
        if($start_date!='' && $end_date!='')
        {
        	$where_query .=" AND ls.start_date >= '".$start_date."' AND ls.end_date <= '".$end_date."' ";
        }
        if(isset($leave_categ_id) && $leave_categ_id > 0)
        {
            $where_query .= " AND lc.leave_category_id = $leave_categ_id";   
        }
        if(isset($staff_id) && $staff_id > 0)
        {
            $where_query .= " AND s.staff_id = $staff_id";   
        }
        $q="SELECT ls.*,lc.name as leave_categ_name, s.name as staff_name,d.title as designation, s.employee_code as employee_code
            FROM ".get_school_db().".leave_staff ls
            INNER join ".get_school_db().".staff s
            ON ls.staff_id=s.staff_id
            INNER join ".get_school_db().".leave_category lc
            On ls.leave_category_id=lc.leave_category_id
            INNER JOIN ".get_school_db().".designation d 
            ON s.designation_id=d.designation_id
            WHERE ls.school_id=".$_SESSION['school_id']. $where_query. " 
            ORDER BY ls.leave_staff_id asc ";
        $leaves=$this->db->query($q)->result_array();
        
        $page_data['staff_id']            = $staff_id;
        $page_data['leave_category_id']   = $leave_categ_id;
        $page_data['start_date']          = $_POST['start_date']; 
        $page_data['end_date']            = $_POST['end_date'];

        $page_data['apply_filter'] = $this->input->post('apply_filter');
        $page_data['leaves']       = $leaves;
        $page_data['page_name']    = 'manage_leaves_staff';
        $page_data['page_title']   = get_phrase('manage_staff_leaves');
        $this->load->view('backend/index', $page_data);
        
    }
    
    function get_year_term()
    {
		if($this->input->post('acad_year')!="")
		{
			echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'),'',1);
		}
	}
	
	function getDatesFromRange($start, $end) 
	{
	    
		$interval = new DateInterval('P1D');
		$realEnd  = new DateTime($end);
		
		$realEnd->add($interval);
	
		$period = new DatePeriod( new DateTime($start) , $interval, $realEnd );
	
		foreach($period as $date) { 
			$array[] = $date->format('Y-m-d'); 
		}
		
		
		return $array;
	}
	
	function staff_leave_generator()
 	{
		$this->load->view('backend/admin/ajax/get_leave_staff.php');
	}
	
	function get_class()
    {
		if($this->input->post('department_id')!="" && $this->input->post('class_id')!="")
		{
			echo  class_option_list($this->input->post('department_id'),$this->input->post('class_id'));
		}
	}
	
    function get_class_section()
	{
		if($this->input->post('class_id')!="")
		{
			echo section_option_list($this->input->post('class_id'));
		}
	}
	function get_section_student()
	{
		if($this->input->post('section_id')!="")
		{
			echo section_student($this->input->post('section_id'));
		}
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