<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Preferences extends CI_Controller
{
	function __construct()
	{
	    // 1 = Chalan Recieve
	    // 2 = Assesment
	    // 3 = Diary
	    // 4 = Chalan Issue

		parent::__construct();
		$this->load->database();
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if($_SESSION['user_login']!= 1)
		    redirect(base_url() . 'login');
        }
        /***default functin, redirects to login page if no admin logged in yet***/
        
        public function index()
        {
            $page_data['page_name']  =  'preferences';
            $page_data['page_title'] =  get_phrase('preferences');
            $this->load->view('backend/index', $page_data);	
        }
        
        public function save_vc_preference()
        {
           $data                    =  array(); 
           $data['vc_platform_id']  =  $this->input->post("vc_platform_id");
           
           $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
           $this->db->update(get_system_db().'.system_school',$data);
           echo "Preferences Are Updated Successfully";
        }
        
        public function save_preferences()
        {
            $data                = array();
            $type                = $this->input->post("type");
            $data['sms_section'] = $this->input->post("section");
            $data['school_id']   = $_SESSION['school_id'];
            
            $check_exist = $this->db->query("SELECT sms_f_id FROM ".get_school_db().".sms_settings WHERE school_id = '".$data['school_id']."' AND sms_section = '".$data['sms_section']."'");
            if($check_exist->num_rows() > 0)
            {
                $data[$type] = $this->input->post("value");
                $get_id      = $check_exist->row();
                $this->db->where("sms_f_id", $get_id->sms_f_id)->update(get_school_db().'.sms_settings',$data);
                echo "Preferences Updated Successfully";
            }else{
                $data['status'] = $this->input->post("value");
                $data[$type]    = 1;
                $this->db->insert(get_school_db().'.sms_settings',$data);
                echo "Preferences Inserted Successfully";
            }
        }
}