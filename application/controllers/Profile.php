<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();


//exit;
class Profile extends CI_Controller
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
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
     function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
              redirect(base_url().'login');
        if ($param1 == 'update_profile_info') {
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $this->db->where('school_id',$_SESSION['school_id']);            
            $this->db->where('admin_id',$_SESSION['user_login_id']);
            $this->db->update(get_school_db().'.admin', $data);
            $this->session->set_flashdata('flash_message', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'profile/manage_profile/');
        }
        
        if ($param1 == 'change_password') {
            $data['password']= passwordHash($this->input->post('password'));
            $data['new_password']= passwordHash($this->input->post('new_password'));
            $data['confirm_new_password'] = passwordHash($this->input->post('confirm_new_password'));
        
            $current_password = $this->db->get_where(get_system_db().'.user_login', array('user_login_id' => $_SESSION['user_login_id']))->row()->password;
                    
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('user_login_id',$_SESSION['user_login_id']);
                $this->db->update(get_system_db().'.user_login', array('password' => $data['new_password']));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            }else{
                $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            } 
            redirect(base_url().'profile/manage_profile/');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');

        $page_data['edit_data']  = $this->db->get_where(get_system_db().'.user_login', array( 'user_login_id' => $_SESSION['user_login_id']))->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    function my_profile()
    {
    	$user_login_id=$_SESSION['user_login_id'];
    	$data['name']=$this->input->post('display_name');
    	$image_file= $this->input->post('image_file');
    	$user_file= $_FILES['userfile']['name'];
    	if($user_file!="")
    	{
         	if($image_file!="")
         	{
        		$del_location="uploads/profile_pic/$image_file";
                file_delete($del_location);
        	}
         	$data['profile_pic']=file_upload_fun('userfile','profile_pic','profile',1);
       } 
       
     	$this->db->where('user_login_id', $user_login_id);
    	$this->db->update(get_system_db().'.user_login', $data);
    	$this->session->set_flashdata('flash_message', get_phrase('record_updated_successfully'));  
        redirect(base_url() . 'profile/manage_profile/');   
    }
  
}   