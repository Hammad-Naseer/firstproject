<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();
class Class_sms extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
    }
 
    function class_sms_templates()
    {
        $page_data['page_name'] ='class_sms_templates';
        $page_data['page_title'] = get_phrase('class_sms_templates');
        $this->load->view('backend/index', $page_data);	
    }
    function save_sms_template()
    {    
        $data['section_id'] = $this->input->post('section_id');
        $data['sms_title'] = $this->input->post('sms_title');
        $data['sms_content'] = $this->input->post('sms_content');
        $data['Current_dates'] = date('Y-m-d');
        
        $this->db->insert(get_school_db().'.Class_wise_sms_templates',$data);
        $this->session->set_flashdata('club_updated',get_phrase('Class_sms_template_added_successfully'));
        redirect(base_url() . 'class_sms/class_sms_temp_listing');
        
    }
    function class_sms_temp_listing()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
         
        $quer = "select  * from " . get_school_db() . ".Class_wise_sms_templates ";
        $students = $this->db->query($quer)->result_array();
        $page_data['SMS_CLASS'] = $students;
        $page_data['page_name'] = 'class_sms_temp_listing';
        $page_data['page_title'] = get_phrase('class_sms_temp_listing');
        $this->load->view('backend/index', $page_data);
        
    }
    function delete_sms_temp($sms_temp_id = 0)
    {
        $this->db->where('sms_temp_id', $sms_temp_id);
        $this->db->delete(get_school_db().'.sms_templates');
        $this->session->set_flashdata('info', 'Template Deleted succesfully!');
        redirect(base_url() . 'templates/sms_temp_listing');
    }
    function class_sms_templates_edit($sms_temp_id = 0)
    {
        $sms_edit_qur = "select * from " . get_school_db() . ".Class_wise_sms_templates where sms_temp_id = ".$sms_temp_id." ";
        $sms_edit_arr = $this->db->query($sms_edit_qur)->result_array();
        $page_data['sms_edit_arr'] = $sms_edit_arr;
        $page_data['page_name'] ='class_sms_templates_edit';
        $page_data['page_title'] = get_phrase('class_sms_templates_edit');
        $this->load->view('backend/index', $page_data);	
    }
    
    function email_templates()
    {
        $page_data['page_name'] ='email_templates';
        $page_data['page_title'] = get_phrase('email_templates');
        $this->load->view('backend/index', $page_data);	
    }
    function save_email_template()
    {
        $data1['email_title'] = $this->input->post('email_title');
        $data1['email_subject'] = $this->input->post('email_subject');
        $data1['email_content'] = $this->input->post('email_content');
        $data1['email_template_status'] = $this->input->post('email_template_status');
        
        $this->db->insert(get_school_db().'.email_templates',$data1);
        $this->session->set_flashdata('club_updated',get_phrase('Email_template_added_successfully'));
        redirect(base_url() . 'templates/email_temp_listing');
        
    }
    function email_temp_listing()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        // $per_page = 10;
        

        // $page_num = $this->uri->segment(3);

        // if (!isset($page_num) || $page_num == "") {
        //     $page_num = 0;
        //     $start_limit = 0;
        // } else {
        //     $start_limit = ($page_num - 1) * $per_page;
        // }
        
        
        $quer = "select * from " . get_school_db() . ".email_templates ";
        $email_count = $this->db->query($quer)->result_array();
        // $total_records = count($email_count);
        
        // $quer_limit = $quer . " limit " . $start_limit . ", " . $per_page . "";

        // $email_temp_arr = $this->db->query($quer_limit)->result_array();

        // $this->load->library('pagination');

        // //$config['base_url'] = base_url() . "c_student/student_information/" . $section_id . "/";
        // $config['total_rows'] = $total_records;
        // $config['per_page'] = $per_page;

        // $config['uri_segment'] = 3;
        // $config['num_links'] = 2;
        // $config['use_page_numbers'] = TRUE;

        // $this->pagination->initialize($config);

        // $pagination = $this->pagination->create_links();

        // $page_data['start_limit'] = $start_limit;
        // $page_data['total_records'] = $total_records;
       
        // $page_data['pagination'] = $pagination;
        $page_data['email_temp_arr'] = $email_count;

        $page_data['page_name'] = 'email_temp_listing';
        $page_data['page_title'] = get_phrase('email_temp_listing');
        $this->load->view('backend/index', $page_data);
    }
    function delete_email_temp($email_temp_id = 0)
    {
        $this->db->where('email_temp_id', $email_temp_id);
        $this->db->delete(get_school_db().'.email_templates');
        $this->session->set_flashdata('info', 'Template Deleted succesfully!');
        redirect(base_url() . 'templates/email_temp_listing');
    }
    function edit_email_temp($email_temp_id = 0)
    {
        $email_edit_qur = "select * from get_school_db().'.email_templates' where sms_temp_id = ".$sms_temp_id." ";
        $email_edit_arr = $this->db->query($email_edit_qur)->result_array();
        $page_data['sms_edit_arr'] = $email_edit_arr;
        $page_data['page_name'] ='edit_email_temp';
        $page_data['page_title'] = get_phrase('edit_email_temp');
        $this->load->view('backend/index', $page_data);	
    }
    
    function sms_testing()
    {
        $quer = "select * from " . get_school_db() . ".sms_templates";
        $sms_count = $this->db->query($quer)->result_array();
        
        //method 1
        $content = $sms_count[1]['sms_content'];
        $content = str_replace('student_name','hammad',$content);
        $content = str_replace('class_name','ONE',$content);
        echo $content;
        // $coords = str_replace('(',' ' , $newstr );
        echo "<br>";
        // //method 2 
        // $student_name = "ALI";
        // $class_name = "TWO";
        //  echo $content1 = $sms_count[2]['sms_content'];
         
    }

}