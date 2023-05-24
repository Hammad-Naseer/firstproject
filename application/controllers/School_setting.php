<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class School_setting extends CI_Controller
{
 function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('school_config');
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
    }
    /***default functin, redirects to login page if no admin logged in yet***/
    
    //  function test(){
    // //   email_send("No Reply", "Indici Edu", $email, $subject, $email_layout, 0,00);
    //       $responce = email_send("No Reply", "Indici Edu", "hammadnaseer766@gmail.com", "Asalam", '', 0,00);
    //      echo $responce;
    //     //  email_verfication_send($responce);
    // }
    public function index()
    {
        if ($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }

    function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        
        if ($param1 == 'do_update') {

                $data['name']=$this->input->post('system_name');
                $data['country_id']=$this->input->post('country_id');
                $data['province_id']=$this->input->post('province_id');
                $data['city_id']=$this->input->post('city_id');
                $data['location_id']=$this->input->post('location_id');
                
                $data['address']=$this->input->post('address');
                $data['phone']=$this->input->post('phone');
                $data['url']=$this->input->post('url');
                $school_id=$this->input->post('school_id');
                $data['email']=$this->input->post('school_email');
                $data['contact_person']=$this->input->post('contact_person');
                $data['designation']=$this->input->post('designation');
                $data['slogan']=$this->input->post('slogan');
                $data['detail']=$this->input->post('detail');
                $data['school_regist_no']=$this->input->post('school_regist_no');
                $data['attendance_method']=$this->input->post('attendance_method');
                $data['diary_approval']=$this->input->post('diary_approval');
                
               
                

                $filename=$_FILES['logo']['name'];
                $folder_name = $_SESSION['folder_name'];
                if($filename!=""){
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $data['logo']=file_upload_fun('logo','','setting');
                $old_logo = $this->input->post('logo_old');

               if($old_logo!=""){
                   $del_location=system_path($old_logo,'');
                   file_delete($del_location);
               }

           }
	
           if($school_id!=""){
                $this->db->where('school_id',$school_id);
                $this->db->update(get_school_db().'.school' , $data);
           }
           else{
            	$this->db->insert(get_school_db().'.school' , $data);
            	$school_id=$this->db->last_insert_id();
           }
           school_config_archive($school_id,1);	
           redirect(base_url() . 'school_setting/system_settings/');
        }
        if ($param1 == 'upload_logo') {
          
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
            $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
            redirect(base_url() . 'school_setting/system_settings/');
        }

        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->load->view('backend/index', $page_data);
    
    }
    
    function get_location(){
	
         	$loc_id=$this->input->post('loc_id');
         	$send_location=$this->input->post('send_location');
        	$selected=$this->input->post('selected');
            if($send_location=="province"){
            	echo province_option_list($loc_id,$selected);
            }
            elseif($send_location=="city"){
            	echo city_option_list($loc_id,$selected);
            }	
            elseif($send_location=="location"){
            	echo location_option_list($loc_id,$selected);
            }
	}
	
	function email_layout_form()
    {
        $qur = "select * from ".get_school_db().".email_layout_settings where school_id = ".$_SESSION['school_id']." ";
        $email_layout_arr = $this->db->query($qur)->row();
        
        $page_data['email_layout_arr'] = $email_layout_arr;
        $page_data['page_name'] ='email_layout_settings';
        $page_data['page_title'] = get_phrase('email_layout_settings');
        $this->load->view('backend/index', $page_data);
    } 
    function email_layout_insert()
    {   
        $data = array(
            'school_name' => $this->input->post('school_name'),
            'address' => $this->input->post('address'),
            'terms' => $this->input->post('terms'),
            'school_id' => $_SESSION['school_id'],
        );
        if (!empty( $_FILES['logo']['name'])){
            $filename = $_FILES['logo']['name'];
            if($filename!=""){
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $data['logo'] = file_upload_fun($_FILES['logo'], "email_layout" , "email");
            }
        }
        
        $this->db->insert( get_school_db().'.email_layout_settings', $data);
        $this->session->set_flashdata('club_updated',get_phrase('email_setting_added_successfully'));
        redirect(base_url() . 'school_setting/email_layout_form');
    }
    
    function email_layout_update()
    {
        $email_layout_id = $this->input->post('email_layout_id');
        
        $data = array(
            'school_name' => $this->input->post('school_name'),
            'address' => $this->input->post('address'),
            'terms' => $this->input->post('terms'),
            'school_id' => $_SESSION['school_id'],
        );
        if (!empty( $_FILES['logo']['name'])){
            $filename = $_FILES['logo']['name'];
            if($filename!=""){
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $data['logo'] = file_upload_fun($_FILES['logo'], "email_layout" , "email");
            }
        }
        
        $this->db->where('email_layout_id' , $email_layout_id);
        $this->db->update( get_school_db().'.email_layout_settings', $data);
        $this->session->set_flashdata('club_updated',get_phrase('email_setting_updated_successfully'));
        redirect(base_url() . 'school_setting/email_layout_form');
    }
	
}