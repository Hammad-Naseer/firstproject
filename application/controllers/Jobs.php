<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jobs extends CI_Controller
{
	
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('jobs');
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");   
    }
    function post_new_job()
    {
        $data['job_title']          =   $this->input->post('job_title');
        $data['carrer_level']       =   $this->input->post('carrer_level');
        $data['qualifications']     =   $this->input->post('qualifications');
        $data['experience']         =   $this->input->post('experience');
        $data['job_type']           =   $this->input->post('job_type');
        $data['job_location']       =   $this->input->post('job_location');
        $data['job_description']    =   $this->input->post('job_description');
        $data['job_posting_date']   =   $this->input->post('job_posting_date');
        $data['job_end_date']       =   $this->input->post('job_end_date');
        $data['job_status']         =   $this->input->post('job_status');
        $data['school_id']          =   $_SESSION['school_id'];
        
        $this->db->insert(get_school_db().'.jobs',$data);
        
        $this->session->set_flashdata('club_updated', get_phrase('new_job_has_been_added_successfully'));
	    redirect(base_url().'jobs/view_jobs');
    }
    function view_jobs()
    {
        $job_type = $this->input->post('job_type');
        $apply_filter = $this->input->post('apply_filter');
        
        $job_type_filter  = "";
        if(!empty($job_type) && $job_type != ""){
            $job_type_filter = "and job_type = ".$job_type." ";
        }
        
        $q=  "select * from ".get_school_db().".jobs where school_id=".$_SESSION['school_id']." $job_type_filter ";
	    $jobs_details =   $this->db->query($q)->result_array();
	    
	    $page_data['jobs_details']  =	 $jobs_details;
	    $page_data['job_type']  =	 $job_type;
	    $page_data['apply_filter']  =	 $apply_filter;
        $page_data['page_name'] = 'view_jobs';
        $page_data['page_title'] = get_phrase('view_jobs');
        $this->load->view('backend/index', $page_data);
        
    }
    function update_job()
    {
        $job_id                     =   $this->input->post('job_id');
        $data['job_title']          =   $this->input->post('job_title');
        $data['carrer_level']       =   $this->input->post('carrer_level');
        $data['qualifications']     =   $this->input->post('qualifications');
        $data['experience']         =   $this->input->post('experience');
        $data['job_type']           =   $this->input->post('job_type');
        $data['job_location']       =   $this->input->post('job_location');
        $data['job_description']    =   $this->input->post('job_description');
        $data['job_posting_date']   =   $this->input->post('job_posting_date');
        $data['job_end_date']       =   $this->input->post('job_end_date');
        $data['job_status']         =   $this->input->post('job_status');
        $data['school_id']          =   $_SESSION['school_id'];
        
		$this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('job_id', $job_id);
        $this->db->update(get_school_db().'.jobs', $data);
        $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
        redirect(base_url() . 'jobs/view_jobs');
    }
    function delete_job($job_id = 0)
    {
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('job_id', $job_id);
        $this->db->delete(get_school_db().'.jobs');
        
        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
        redirect(base_url() . 'jobs/view_jobs');
    }
    function view_job_applications()
    {
        $q=  "select ja.* , j.job_title ,j.job_type , j.job_location from ".get_school_db().".job_applications ja
        inner join ".get_school_db().".jobs j on ja.job_id = j.job_id
        where ja.school_id=".$_SESSION['school_id']."  ";
	    $application_details =   $this->db->query($q)->result_array();
	    
	    $page_data['application_details']  =	 $application_details;
        $page_data['page_name'] = 'view_job_applications';
        $page_data['page_title'] = get_phrase('view_job_applications');
        $this->load->view('backend/index', $page_data);
    }
    function job_application_response()
    {
        $this->load->helper('message');
        $job_application_id = $this->input->post('job_application_id');  
	    $response = $this->input->post('response');

	    // Get General Inquiry Record
	    $application_data = $this->db->query("SELECT job_application_id,email,mob_num,status FROM ".get_school_db().".job_applications WHERE job_application_id = $job_application_id ")->row();
	    
        // Email Sent
        if($this->input->post('email'))
        {
            $email_message = $response;
            $subject = "Job Application Resposne";
    		$email_layout = get_email_layout($email_message);
            email_send("No Reply",'Indici Edu',$application_data->email,$subject,$email_layout,0,7);
        }
        
        // SMS Sent    
        if($this->input->post('sms'))
        {
            $message = $response;
            send_sms($application_data->mob_num, 'Indici Edu', $message, 0,7);
        }    
        
        $this->db->where("job_application_id",$application_data->job_application_id)->update(get_school_db().".job_applications",array('status' => 1));
        
        
        $this->session->set_flashdata('club_updated', get_phrase('job_application_response_send_successfully'));
        redirect(base_url() . 'jobs/view_job_applications');
    }
    
}