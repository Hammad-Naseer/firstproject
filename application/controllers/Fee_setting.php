<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();


class Fee_setting extends CI_Controller{
  
function __construct(){
parent::__construct();

if($_SESSION['accountant_login'] == 1 || $_SESSION['user_login']==1){
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->menu_ary=array();
		
		}else{
			

		redirect('login');
}	
	}


function fee_setting_c($action="", $id=""){
	
if($action=="add_edit"){	
$data['title']=$this->input->post('title');
$c_a_id=$this->input->post('c_a_id');
$data['order_num']=$this->input->post('order_num');
$data['status']=$this->input->post('status');
$data['coa_id']=$this->input->post('coa_id');
$school_id=$_SESSION['school_id'];



if($c_a_id!=""){
	
	$this->db->where('c_a_id',$c_a_id);
	$this->db->where('school_id',$school_id);
	$this->db->update(get_school_db().'.chalan_accounts',$data);
	$this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));	
}

else{
	$data['school_id']=$school_id;
	$this->db->insert(get_school_db().'.chalan_accounts',$data);
	


$this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));


	
}




redirect(base_url() . 'fee_setting/fee_setting_c/');





		
			
}

if($action=='delete'){
	
	

$this->db->where('c_a_id', $id);
$this->db->delete(get_school_db().'.chalan_accounts');
$this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));

redirect(base_url() . 'fee_setting/fee_setting_c/');	
	
}



		
$page_data['page_name']  = '../accountant/fee_setting';
$page_data['page_title'] = get_phrase('chalan_account_setting');
        
        
		$this->load->view('backend/index', $page_data);
}


}