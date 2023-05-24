<?php
//session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student extends CI_Controller
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
        if ($_SESSION['student_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['student_login'] == 1)
            redirect(base_url() . 'student/dashboard');
    }
    
    /***ADMIN DASHBOARD***/
    function dashboard()
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url());
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('student_dashboard');
        $this->load->view('backend/index', $page_data);
    }
    
    function teacher_list($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url());
        if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        }
        $page_data['teachers']   = $this->db->get(get_school_db().'.teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('manage_teacher');
        $this->load->view('backend/index', $page_data);
    }
    
    function subject($param1 = '', $param2 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url());
        
        $student_profile = $this->db->query("select class_id from ".get_school_db().".student where student_id=".$_SESSION['student_id']." and school_id=".$_SESSION['school_id']." ")->row();
        $page_data['subjects']   = $this->db->get_where(get_school_db().'.subject', array(
            'class_id' => $student_profile->class_id,
            'school_id'=>$_SESSION['school_id']
        ))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('manage_subject');
        $this->load->view('backend/index', $page_data);
    }
	
	 /****MANAGE DIARY*****/
    function diary($param1 = '', $param2 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url());
        
        $student_profile         = $this->db->query("select class_id from ".get_school_db().".student where student_id=".$_SESSION['student_id']." and school_id=".$_SESSION['school_id']." ")->row();
        $student_class_id        = $student_profile->class_id;
        $page_data['diary']      = $this->db->get_where(get_school_db().'.diary', array('class_id' => $student_class_id,'school_id'=>$_SESSION['school_id'] ))->result_array();
        $page_data['page_name']  = 'diary';
        $page_data['page_title'] = get_phrase('daily_diary');
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE EXAM MARKS*****/
    function marks($exam_id = '', $class_id = '', $subject_id = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url());
            
        $student_profile       = $this->db->query("select class_id from ".get_school_db().".student where student_id=".$_SESSION['student_id']." ")->row();
		$page_data['class_id'] = $student_profile->class_id;
		$page_data['student_id'] = $this->db->get_where(get_school_db().'.student', array( 'student_id' => $_SESSION['student_id'] ))->row()->student_id;
        
        if ($this->input->post('operation') == 'selection')
        {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['subject_id'] = $this->input->post('subject_id');
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0) {
                redirect(base_url() . 'student/marks/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['subject_id']);
            } else {
                $this->session->set_flashdata('mark_message', get_phrase('choose_exam_class_and_subject'));
                redirect(base_url() . 'student/marks/');
            }
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['page_info'] = 'Exam marks';
        $page_data['page_name']  = 'marks';
        $page_data['page_title'] = get_phrase('manage_exam_marks');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url());
        
        $student_profile         = $this->db->query("select class_id from ".get_school_db().".student where student_id=".$_SESSION['student_id']." and school_id=".$_SESSION['school_id']." ")->row();
        $page_data['class_id']   = $student_profile->class_id;
        $q="SELECT * FROM ".get_school_db().".class_routine_settings WHERE school_id=".$_SESSION['school_id']."";
        $routine=$this->db->query($q)->result_array();
        $page_data['routine']=$routine;
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('manage_class_routine');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********Academic Planner******************/
    function academic_planner($subjectname='') {
        if($_SESSION['student_login']!=1){redirect('login' );}
        
        $page_data['page_name']  = 'academic_planner';
        $page_data['page_title']  = get_phrase('academic_planner');

        if ( ($subjectname=='')) {
            $student = $this->db->query("select class_id,name from ".get_school_db().".student where student_id=".$_SESSION['student_id']." ")->row();

            $subjects=  $this->db->get_where(get_school_db().'.subject',array('class_id'=>($student->num_rows() != 0)?$student->row()->class_id : ''))->result_array();
            $page_data['student']=($student->num_rows() != 0)?$student->row()->name : '';
            foreach ($subjects as $subject) {
                $classes=  $this->db->get_where(get_school_db().'.class',array('teacher_id'=>$subject['teacher_id']))->result_array();
            }
        }
        elseif (($subjectname!='')) {
            $student = $this->db->query("select class_id,name from ".get_school_db().".student where student_id=".$_SESSION['student_id']." ")->row();
            $class=$this->db->get_where(get_school_db().'.class',array('class_id'=>($student->num_rows() != 0)?$student->row()->class_id : ''));
            $page_data['classTmp']=($class->num_rows() != 0)?$class->row()->name:'';
            $page_data['student']=($student->num_rows() != 0)?$student->row()->name : '';
            $page_data['class']=$page_data['classTmp'];
            $page_data['subject']=$subjectname;
            $page_data['calendarTitle']=$page_data['classTmp'].'/'.$subjectname;
    }
        $page_data['classes']=$classes;
        $page_data['subjects']=$subjects;
        $this->load->view('backend/index', $page_data);
    }
    
	/**********MANAGING EXAM ROUTINE******************/
    function exam_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url());
            
        $student_profile = $this->db->query("select class_id from ".get_school_db().".student where student_id=".$_SESSION['student_id']." and school_id=".$_SESSION['school_id']." ")->row();
        $page_data['class_id']   = $student_profile->class_id;
        $page_data['page_name']  = 'exam_routine';
        $page_data['page_title'] = get_phrase('manage_exam_routine');
        $this->load->view('backend/index', $page_data);
    }
    
    /******MANAGE BILLING / INVOICES WITH STATUS*****/
    function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        //if($this->session->userdata('student_login')!=1)redirect(base_url() );
        if ($param1 == 'make_payment') {
            $invoice_id      = $this->input->post('invoice_id');
            $system_settings = $this->db->get_where(get_school_db().'.settings', array('type' => 'paypal_email' ))->row();
            $invoice_details = $this->db->get_where(get_school_db().'.invoice', array('invoice_id' => $invoice_id ))->row();
            
            /****TRANSFERRING USER TO PAYPAL TERMINAL****/
            $this->paypal->add_field('rm', 2);
            $this->paypal->add_field('no_note', 0);
            $this->paypal->add_field('item_name', $invoice_details->title);
            $this->paypal->add_field('amount', $invoice_details->amount);
            $this->paypal->add_field('custom', $invoice_details->invoice_id);
            $this->paypal->add_field('business', $system_settings->description);
            $this->paypal->add_field('notify_url', base_url() . 'student/invoice/paypal_ipn');
            $this->paypal->add_field('cancel_return', base_url() . 'student/invoice/paypal_cancel');
            $this->paypal->add_field('return', base_url() . 'student/invoice/paypal_success');
            
            $this->paypal->submit_paypal_post();
            // submit the fields to paypal
        }
        if ($param1 == 'paypal_ipn') {
            if ($this->paypal->validate_ipn() == true) {
                $ipn_response = '';
                foreach ($_POST as $key => $value) {
                    $value = urlencode(stripslashes($value));
                    $ipn_response .= "\n$key=$value";
                }
                $data['payment_details']   = $ipn_response;
                $data['payment_timestamp'] = strtotime(date("m/d/Y"));
                $data['payment_method']    = 'paypal';
                $data['status']            = 'paid';
                $invoice_id                = $_POST['custom'];
                $this->db->where('invoice_id', $invoice_id);
                $this->db->update(get_school_db().'.invoice', $data);
            }
        }
        if ($param1 == 'paypal_cancel') {
            $this->session->set_flashdata('flash_message', get_phrase('payment_cancelled'));
            redirect(base_url() . 'student/invoice/');
        }
        if ($param1 == 'paypal_success') {
            $this->session->set_flashdata('flash_message', get_phrase('payment_successfull'));
            redirect(base_url() . 'student/invoice/');
        }
        $student_profile         = $this->db->query("select student_id from ".get_school_db().".student where student_id=".$_SESSION['student_id']." ")->row();
        $student_id              = $student_profile->student_id;
        $page_data['invoices']   = $this->db->get_where(get_school_db().'.invoice', array('student_id' => $student_id ))->result_array();
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('manage_invoice_payment');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGE LIBRARY / BOOKS********************/
   function book($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect('login');
        
        $page_data['books']      = $this->db->select("*")->from(get_school_db().'.book')->order_by('book_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }

	 /**********MANAGE Leaves ********************/
	function manage_leaves($param1 = '', $param2 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect('login');
       	
		if ($param1 == 'create') {

			$c_time_display = new DateTime($_REQUEST['start_date']);
			$start_date	=	$c_time_display->format('Y-m-d').PHP_EOL;
			
			$c_time_display = new DateTime($_REQUEST['end_date']);
			$end_date	=	$c_time_display->format('Y-m-d').PHP_EOL;
			$proof		=	$_FILES['userfile']['name'];
			$proof_doc	=	$_SESSION['student_id']."-".$proof;
			
			$data['student_id']        = $_SESSION['student_id'];
			$data['school_id']        = $_SESSION['school_id'];
            $data['leave_id']          = $this->input->post('leave_id');
            $data['request_date']      = date('Y-m-d');
			$data['start_date']        = $start_date;
			$data['end_date']          = $end_date;
			$data['reason']            = $this->input->post('reason');
			$data['proof_doc']         = $proof_doc;
            $data['status']            = 0;
            $this->db->insert(get_school_db().'.leave_student', $data);
			
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $proof_doc);
			redirect(base_url() . 'student/manage_leaves');
        }
									
		$page_data['leaves']       = $this->db->select("*")->from(get_school_db().'.leave_student')->where(array('student_id' =>$_SESSION['student_id'],'school_id' =>$_SESSION['school_id']))->order_by('request_id', 'desc')->get()->result_array();
        $page_data['page_name']  = 'leave_request';
        $page_data['page_title'] = get_phrase('manage_leave_requests');
        $this->load->view('backend/index', $page_data);
        
    }

    /**********WATCH NOTICEBOARD AND EVENT ********************/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect('login');
        $this->db->where('school_id',$_SESSION['school_id']);
        $page_data['notices']    = $this->db->get(get_school_db().'.noticeboard')->result_array();
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('noticeboard');
        $this->load->view('backend/index', $page_data);
        
    }
     /***MANAGE CIRCULARS, WILL BE SEEN BY Selected Corresponding DASHBOARDS**/
    function circulars($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url());
        
        $page_data['page_name']  = 'circulars';
        $student_id     =$_SESSION['student_id'];
        $class_id=$_SESSION['class_id'];
        $school_id=$_SESSION['school_id'];
        $page_data['page_title'] = get_phrase('manage_circulars');
        $page_data['circulars']   = $this->db->query("select * from ".get_school_db().".circular where (student_id=$student_id or class_id=$class_id) AND school_id=$school_id")->result_array();
        $page_data['page_name']  = 'circulars';
        $page_data['page_title'] = get_phrase('circulars');
		$this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGE DOCUMENT / home work FOR A SPECIFIC CLASS or ALL*******************/
    function document($do = '', $document_id = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect('login');
        
        $page_data['page_name']  = 'manage_document';
        $page_data['page_title'] = get_phrase('manage_documents');
        $page_data['documents']  = $this->db->get(get_school_db().'.document')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['student_login'] != 1)
            redirect(base_url() . 'login');
        if ($param1 == 'update_profile_info') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['religion']    = $this->input->post('religion');
            $data['blood_group'] = $this->input->post('blood_group');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('student_id', $_SESSION['student_id']);
            $this->db->update(get_school_db().'.student', $data);
            $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
            redirect(base_url() . 'student/manage_profile/');
        }
        if ($param1 == 'change_password') {
            $data['password']             = $this->input->post('password');
            $data['new_password']         = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where(get_school_db().'.student', array('student_id' => $_SESSION['student_id'] ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
            	$this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('student_id', $_SESSION['student_id']);
                $this->db->update(get_school_db().'.student', array( 'password' => $data['new_password'] ));
                $this->session->set_flashdata('err_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('err_message', get_phrase('wrong_password'));
            }
            redirect(base_url() . 'student/manage_profile/');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where(get_school_db().'.student', array(
            'student_id' => $_SESSION['student_id'],
             'school_id' =>$_SESSION['school_id']
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }

}
