<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class sms_settings extends CI_Controller
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

 
    function sms_setting(){
        $page_data['page_name'] ='sms_setting';
        $page_data['page_title'] = get_phrase('sms_setting');
        $this->load->view('backend/index', $page_data);	
    }
    
    function save_sms_setting(){
        //vacation
        //$data_vac['sms_details']=$this->input->post('sms_details_vac');
        
        
        if(isset($_POST['status_vac']) && $_POST['status_vac']!=""){
            $data_vac['status']=1;	
        }else{
            $data_vac['status']=0;		
        }
        
        if(isset($_POST['email_status_vac']) && $_POST['email_status_vac']!=""){
            $data_vac['email_status']=1;	
        }else{
            $data_vac['email_status']=0;		
        }
        
        $data_vac['sms_type']=1;
        $data_vac['school_id']=$_SESSION['school_id'];
        
        $dsms_f_id=$this->input->post('sms_f_id_vac');
        if($dsms_f_id!=""){
            $this->db->where('sms_f_id',$dsms_f_id);	
            $this->db->update(get_school_db().'.sms_settings',$data_vac);	
            $this->session->set_flashdata('club_updated',get_phrase('sms_setting_updated_successfully'));
        
        }else{
            $this->db->insert(get_school_db().'.sms_settings',$data_vac);
            $this->session->set_flashdata('club_updated',get_phrase('sms_setting_added_successfully'));
        }
    }
    
}