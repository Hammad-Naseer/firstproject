<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Principal extends CI_Controller
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
        if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['principal_login'] == 1)
            redirect(base_url() . 'principal/dashboard');
    }
    function f()
	{
		echo 'h';	
	}
    /***ADMIN DASHBOARD***/
    function dashboard()
    {
    	
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url());
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('principal_dashboard');
        $this->load->view('backend/index', $page_data);
    }
    
    
    /****MANAGE TEACHERS*****/
    function teacher_list($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url());
        if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        }
        $this->db->where('school_id',$_SESSION['school_id']);
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('teacher');
        $this->load->view('backend/index', $page_data);
    }
    
    
    /***********************************************************************************************************/
    
    
    
    /****MANAGE SUBJECTS*****/
    function subject($param1 = '', $param2 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url());
        
        $parent_profile         = $this->db->get_where('parent', array(
            'parent_id' => $_SESSION['user_login_id']
        ))->row();
        $parent_class_id        = $parent_profile->class_id;
        $page_data['subjects']   = $this->db->get_where('subject', array(
            'class_id' => $parent_class_id
        ))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('manage_subject');
        $this->load->view('backend/index', $page_data);
    }
    
    
    
    /****MANAGE EXAM MARKS*****/
   function marks($exam_id = '', $class_id = '', $subject_id = '')
    {
        if ($_SESSION['principal_login']!= 1)
            redirect(base_url());
        
        if ($this->input->post('operation') == 'selection') {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['subject_id'] = $this->input->post('subject_id');
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0) {
                redirect(base_url() . 'principal/marks/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['subject_id']);
            } else {
                $this->session->set_flashdata('mark_message', get_phrase('choose_exam_,_class_and_subject'));
                redirect(base_url() . 'principal/marks/');
            }
        }
        if ($this->input->post('operation') == 'update') {
            $data['mark_obtained'] = $this->input->post('mark_obtained');
            $data['attendance']    = $this->input->post('attendance');
            $data['comment']       = $this->input->post('comment');
            
            $this->db->where('mark_id', $this->input->post('mark_id'));
            $this->db->update('mark', $data);
            
            redirect(base_url() . 'principal/marks/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id') . '/' . $this->input->post('subject_id'));
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
        $page_data['subject_id'] = $subject_id;
        
        $page_data['page_info'] = 'Exam marks';
        
        $page_data['page_name']  = 'marks';
        $page_data['page_title'] = get_phrase('view_exam_marks');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url());
        if ($param1 == 'create') {
            $data['class_id']   = $this->input->post('class_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start');
            $data['time_end']   = $this->input->post('time_end');
            $data['day']        = $this->input->post('day');
            $this->db->insert('class_routine', $data);
            redirect(base_url() . 'admin/class_routine/');
        }
        if ($param1 == 'do_update') {
            $data['class_id']   = $this->input->post('class_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start');
            $data['time_end']   = $this->input->post('time_end');
            $data['day']        = $this->input->post('day');
            
            $this->db->where('class_routine_id', $param2);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->update('class_routine', $data);
            redirect(base_url() . 'admin/class_routine/');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('class_routine', array(
                'class_routine_id' => $param2,
                'school_id' =>$_SESSION['school_id']
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('class_routine_id', $param2);
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->delete('class_routine');
            redirect(base_url() . 'admin/class_routine/');
        }
        $q="SELECT * FROM class_routine_settings WHERE school_id=".$_SESSION['school_id']."";
		$routine=$this->db->query($q)->result_array();
        $page_data['routine']=$routine;
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }
    
        /**********Academic Planner******************/
    function academic_planner($classname='',$subjectname='') {
        if($_SESSION['principal_login']!=1)redirect('login' );
        $page_data['page_name']  = 'academic_planner';
        $page_data['page_title']  = get_phrase('academic_planner');
        if ($_POST['teacher']) {
            $subjects=  $this->db->get_where('subject',array('teacher_id'=>$_POST['teacher']))->result_array();
            $page_data['teacher_id']=$_POST['teacher'];
            foreach ($subjects as $subject) {
                $classes=  $this->db->get_where('class',array('teacher_id'=>$subject['class_id']))->result_array();
            }
        }
        elseif ( ($classname!='') && ($subjectname!='')) {
            $page_data['teacher_id']=$_POST['tid'];
            $page_data['class']=$classname;
            $page_data['subject']=$subjectname;
        $page_data['calendarTitle']=$classname.'/'.$subjectname;
    }
        $page_data['classes']=$classes;
        $page_data['subjects']=$subjects;
        $this->load->view('backend/index', $page_data);
    }
    
    
   
    
    function  edit_event(){
        $id = $_POST['id'];
        $title = $_POST['title'];
        $this->db->where('id', $id);
        $this->db->update('evenement',array('title'=>$title));       
    }

function  addevents(){
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $url = $_POST['url'];
    $teacher=$_POST['teacher'];
    $class=$_POST['class'];
    $subject=$_POST['subject'];
    $this->db->insert('evenement',array('title'=>$title,'start'=>$start,'end'=>$end,'teacher'=>$teacher,'class'=>$class,'subject'=>$subject));
    $id = mysql_insert_id();
    echo json_encode($id);
}

function updateevents(){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $this->db->where('id', $id);
    $this->db->update('evenement',array('title'=>$title,'start'=>$start,'end'=>$end));
}


function deleteevents(){
    $id = $_POST['id'];
    $this->db->delete('evenement', array('id'=>$id));
}

    
	 /**********MANAGING EXAM ROUTINE******************/
    function exam_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url());
        
        $page_data['page_name']  = 'exam_routine';
        $page_data['page_title'] = get_phrase('view_exam_routine');
        $this->load->view('backend/index', $page_data);
    }
    /****** DAILY ATTENDANCE *****************/
	function manage_attendance($date='',$month='',$year='',$class_id='')
	{
		if($_SESSION['principal_login']!=1)redirect('login' );
		
		/*if($_POST)
		{
			$verify_data	=	array(	'student_id' 		=> $this->input->post('student_id'),
										'date' 				=> $this->input->post('date'));
			$attendance = $this->db->get_where('attendance' , $verify_data)->row();
			$attendance_id		= $attendance->attendance_id;
			
			$this->db->where('attendance_id' , $attendance_id);
			$this->db->update('attendance' , array('status' => $this->input->post('status'),'marked_by'=>$_SESSION['name']));
			
			redirect(base_url() . 'principal/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$class_id );
		}*/
		$page_data['date']		=	$date;
		$page_data['month']		=	$month;
		$page_data['year']		=	$year;
		$page_data['class_id']	=	$class_id;
		
		$page_data['page_name']		=	'manage_attendance';
		$page_data['page_title']		=	get_phrase('daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	//apply attendence
	function apply_attendence($date='',$month='',$year='',$class_id='')
	{
		if($_SESSION['principal_login']!=1)redirect('login' );
		//print_r($_POST);
		$student_id=$_POST['student_id'];
                $date_today=$year.'-'.$month.'-'.$date;
		for($j=0;$j<=count($student_id)-1;$j++)
		{
                

                            $this->db->insert('attendance' , array('status' => (isset($_POST["status-$j"]))?$_POST["status-$j"]:0,'marked_by'=>$_SESSION['name'],'student_id'=>$student_id[$j],'date'=>$date_today));
                            
		}
		redirect(base_url() . 'principal/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$class_id );
	
		$page_data['date']			=	$date;
		$page_data['month']		=	$month;
		$page_data['year']			=	$year;
		$page_data['class_id']	=	$class_id;
		
		$page_data['page_name']		=	'manage_attendance';
		$page_data['page_title']		=	get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	function attendance_selector()
	{
		$date_new	=	explode("/",$_REQUEST['date']);
		$date		=	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
		redirect(base_url() . 'principal/manage_attendance/'.$date.'/'.
					//$this->input->post('month').'/'.
						//$this->input->post('year').'/'.
							$this->input->post('class_id') );
	}
	/****** DAILY ATTENDANCE TEACHER *****************/
	function manage_attendance_teacher($date='',$month='',$year='',$class_id='')
	{
		if($_SESSION['principal_login']!=1)redirect('login' );
		$page_data['date']		=	$date;
		$page_data['month']		=	$month;
		$page_data['year']		=	$year;
		//$page_data['class_id']	=	$class_id;
		
		$page_data['page_name']		=	'manage_attendance_teacher';
		$page_data['page_title']		=	get_phrase('daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	//apply attendence
	function apply_attendence_teacher($date='',$month='',$year='',$class_id='')
	{
		if($_SESSION['principal_login']!=1)redirect('login' );
		//print_r($_POST);
		$teacher_id=$_POST['teacher_id'];
                $date_today=$year.'-'.$month.'-'.$date;
		for($j=0;$j<=count($teacher_id)-1;$j++)
		{
                

                            $this->db->insert('attendance_teacher' , array('status' => (isset($_POST["status-$j"]))?$_POST["status-$j"]:0,'marked_by'=>$_SESSION['name'],'teacher_id'=>$teacher_id[$j],'date'=>$date_today));
                            
		}
			
		$page_data['date']		=	$date;
		$page_data['month']		=	$month;
		$page_data['year']		=	$year;
		
		$page_data['page_name']		=	'manage_attendance_teacher';
		$page_data['page_title']	=	get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	function attendance_selector_teacher()
	{
		$date_new	=	explode("/",$_REQUEST['date']);
		$date		=	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
		redirect(base_url() . 'principal/manage_attendance_teacher/'.$date.'/'.
					//$this->input->post('month').'/'.
						//$this->input->post('year').'/'.
							$this->input->post('class_id') );
	}
    /******MANAGE BILLING / INVOICES WITH STATUS*****/
     function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url());
        
        if ($param1 == 'create') {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            
            $this->db->insert('invoice', $data);
            redirect(base_url() . 'principal/invoice');
        }
        if ($param1 == 'do_update') {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            
            $this->db->where('invoice_id', $param2);
            $this->db->update('invoice', $data);
            redirect(base_url() . 'principal/invoice');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('invoice_id', $param2);
            $this->db->delete('invoice');
            redirect(base_url() . 'principal/invoice');
        }
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('manage_invoice/payment');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    /**********MANAGE LIBRARY / BOOKS********************/
    function book($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect('login');
        
        $page_data['books']      = $this->db->select("*")->from('book')->order_by('book_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE LIBRARY / BOOKS********************/
     function book_issue($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect('login');

		$principal_issue	=	$this->db->select("*")->from('book_request')->where(array('principal_id' =>$_SESSION['principal_id']))->order_by('request_id', 'desc')->get()->result_array();
		foreach($principal_issue as $row10){
			$principal_request	=	$this->db->select("*")->from('book_issue')->where(array('request_id' =>$row10['request_id']))->order_by('issue_id', 'desc')->get()->result_array();
			foreach($principal_request as $row11){
				if($row10['request_id']==$row11['request_id'])
					$request_id[]	=	$row10['request_id'];
			}
		}
		$page_data['request']    =	$request_id;
		$page_data['page_name']  = 'book_issue';
        $page_data['page_title'] = get_phrase('manage_issued_books');
        $this->load->view('backend/index', $page_data);
        
    }
	function book_request($param1 = '', $param2 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect('login');
       	
		if ($param1 == 'create') {
            $data['principal_id']      = $_SESSION['principal_id'];
            $data['book_id']           = $this->input->post('book_id');
            $data['request_date']      = date('Y-m-d');
            $data['status']            = 0;
            $this->db->insert('book_request', $data);
            redirect(base_url() . 'principal/book_request');
        }
		
		$page_data['book']       = $this->db->select("*")->from('book_request')->where(array('principal_id' =>$_SESSION['principal_id']))->order_by('request_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'book_request';
        $page_data['page_title'] = get_phrase('manage_book_requests');
        $this->load->view('backend/index', $page_data);
        
    }
    
	
    /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function transport($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect('login');
        
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'transport';
        $page_data['page_title'] = get_phrase('manage_transport');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE DORMITORY / HOSTELS / ROOMS ********************/
    function dormitory($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect('login');
        
        $page_data['dormitories'] = $this->db->get('dormitory')->result_array();
        $page_data['page_name']   = 'dormitory';
        $page_data['page_title']  = get_phrase('manage_dormitory');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /**********WATCH NOTICEBOARD AND EVENT ********************/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect('login');
        $this->db->where('school_id',$_SESSION['school_id']);
        $page_data['notices']    = $this->db->get('noticeboard')->result_array();
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('noticeboard');
        $this->load->view('backend/index', $page_data);
        
    }
	
	/***MANAGE CIRCULARS, WILL BE SEEN BY Selected Corresponding DASHBOARDS**/
    function circulars($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url());
        
        $page_data['page_name']  = 'circulars';
        $page_data['page_title'] = get_phrase('view_circulars');
        $this->db->where('school_id',$_SESSION['school_id']);
        $page_data['circulars']    = $this->db->get('circular')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    
    /**********MANAGE DOCUMENT / home work FOR A SPECIFIC CLASS or ALL*******************/
    function document($do = '', $document_id = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect('login');
        
        $page_data['page_name']  = 'manage_document';
        $page_data['page_title'] = get_phrase('manage_documents');
        $page_data['documents']  = $this->db->get('document')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['principal_login'] != 1)
            redirect(base_url() . 'login');
        if ($param1 == 'update_profile_info') {
            $data['name']        = $this->input->post('name');
            $data['email']       = $this->input->post('email');
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('principal_id', $_SESSION['principal_id']);
            $this->db->update('principal', $data);
            $this->session->set_flashdata('flash_message', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'principal/manage_profile/');
        }
        if ($param1 == 'change_password') {
            $data['password']             = $this->input->post('password');
            $data['new_password']         = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where('principal', array(
                'principal_id' => $_SESSION['principal_id'],
                'school_id' =>$_SESSION['school_id']
            ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
            	$this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('principal_id', $_SESSION['principal_id']);
                $this->db->update('principal', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('err_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('err_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'principal/manage_profile/');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('my_account');
        $page_data['edit_data']  = $this->db->get_where('principal', array(
            'principal_id' => $_SESSION['principal_id']
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }
}
