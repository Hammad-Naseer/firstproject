<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();


class Fee_types extends CI_Controller{
  
    function __construct(){
        parent::__construct();
        if($_SESSION['accountant_login'] == 1 || $_SESSION['user_login']==1){
    		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    		$this->output->set_header('Pragma: no-cache');
    		$this->menu_ary=array();
		}
		else
		{
		   redirect('login');
		}	
    }

function fee_types_c($action="", $id=""){

    $school_id=$_SESSION['school_id'];
    $login_type = $_SESSION['login_type'];
    if($action=="add_edit")
    {
        $order_num = $this->input->post('order_num');
        if(empty($order_num) || $order_num == "")
        {
            $order_num = 0;    
        }
        
    	$data['title']=$this->input->post('title');
    	$fee_type_id=$this->input->post('fee_type_id');
    
    	$data['order_num']=$order_num;
    	$data['issue_dr_coa_id']= $this->input->post('issue_dr_coa_id');
    	$data['issue_cr_coa_id']= $this->input->post('issue_cr_coa_id');
    	$data['receive_dr_coa_id']= $this->input->post('receive_dr_coa_id');
    	$data['receive_cr_coa_id']= $this->input->post('receive_cr_coa_id');
    	$data['cancel_dr_coa_id']=  $data['issue_cr_coa_id'];//$this->input->post('cancel_dr_coa_id');
    	$data['cancel_cr_coa_id']= $data['issue_dr_coa_id']; //$this->input->post('cancel_cr_coa_id');

	$data['status']=$this->input->post('status');
	if($fee_type_id!="")
	{
        $coa_exists = 1;
        if($login_type == 1)
        {
            $is_assign_str = "select school_id from ".get_school_db().".school_fee_types where fee_type_id =$fee_type_id and school_id != $school_id";
            $is_assign_query = $this->db->query($is_assign_str)->result_array();

            $assign_school_arr = array();

            foreach ($is_assign_query as $assign_school)
            {
                $assign_school_arr[] = $assign_school['school_id'];
            }

            foreach ($assign_school_arr as $school_assign_id)
            {
                $coa_issue_dr_coa_id_exist =  $this->get_coa_exists($school_assign_id,$data['issue_dr_coa_id']);

                if(count($coa_issue_dr_coa_id_exist)==0)
                {
                    $coa_exists = 0;
                }
                else
                {
                    $coa_issue_cr_coa_id_exist =  $this->get_coa_exists($school_assign_id,$data['issue_cr_coa_id']);

                    if(count($coa_issue_cr_coa_id_exist)==0)
                    {
                        $coa_exists = 0;
                    }
                    else
                    {
                        $coa_receive_dr_coa_id_exist =  $this->get_coa_exists($school_assign_id,$data['receive_dr_coa_id']);

                        if(count($coa_receive_dr_coa_id_exist)==0)
                        {
                            $coa_exists = 0;
                        }
                        else
                        {
                            $coa_receive_cr_coa_id_exist =  $this->get_coa_exists($school_assign_id,$data['receive_cr_coa_id']);
                            if(count($coa_receive_cr_coa_id_exist)==0)
                            {
                                $coa_exists = 0;
                            }
                        }
                    }
                }
            }
        }

        if($coa_exists == 1)
        {
            $this->db->where('fee_type_id', $fee_type_id);
            $this->db->update(get_school_db() . '.fee_types', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
        }
        else
        {
            $this->session->set_flashdata('club_updated', get_phrase('record_not_updated_becasue_of_invalid_coa_settings'));
        }
	}
	else
	{
		$data['school_id']=$school_id;
		$this->db->insert(get_school_db().'.fee_types',$data);

		/* Insertion in school_fee_types  start */
		$fee_type_id = $this->db->insert_id();
		$fee_type_id_temp = $fee_type_id;
		$data_fee_type['school_id'] =  $school_id;
		$data_fee_type['fee_type_id'] = $fee_type_id_temp;
		$this->db->insert(get_school_db().'.school_fee_types', $data_fee_type);

		if($login_type == 2)
		{
		  $parent_sys_sch_id = $_SESSION['parent_sys_sch_id'];
		  $school_coa_str = "select school_id from ".get_school_db().".school where sys_sch_id =$parent_sys_sch_id";
		  $school_coa_query = $this->db->query($school_coa_str)->row();
		  $school_id_temp = $school_coa_query->school_id;
		  $data_fee_type['school_id'] =  $school_id_temp;
		  $this->db->insert(get_school_db().'.school_fee_types', $data_fee_type);
		}
		//____________________
		$this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));
	}
	redirect(base_url() . 'fee_types/fee_types_c/');		
}

    if($action=='delete')
    {
    
    	$qur_1=$this->db->query("select fee_type_id from ".get_school_db().".class_chalan_fee where fee_type_id=$id and school_id=".$_SESSION['school_id'])->result_array();
    
    	if(count($qur_1)>0)
    	{
    		$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
    	}
    	else
    	{
    		$school_id=$_SESSION['school_id'];
    
    		$login_type = $_SESSION['login_type'];
    		if($login_type == 2)
    		{
    			$this->db->where('fee_type_id', $id);
    			$this->db->where('school_id', $school_id);
    			$this->db->delete(get_school_db().'.school_fee_types');
    			$this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
    		}
    		elseif ($login_type == 1)
    		{
    			$qur_1=$this->db->query("select fee_type_id from ".get_school_db().".school_fee_types where fee_type_id=$id and school_id != ".$_SESSION['school_id'])->result_array();
    
    			if(count($qur_1)>0)
    			{
    				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
    			}
    			else
    			{
    				$this->db->where('fee_type_id', $id);
    				$this->db->where('school_id', $school_id);
    				$this->db->delete(get_school_db().'.school_fee_types');
    				$this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
    			}
    		}
    	}
    
    	redirect(base_url() . 'fee_types/fee_types_c/');
    }
    
    $fee_qur   = "SELECT ft.*, s.name as school_name FROM ".get_school_db().".fee_types ft INNER JOIN ".get_school_db().".school_fee_types sft 
                      on sft.fee_type_id = ft.fee_type_id INNER join ".get_school_db().".school s on ft.school_id = s.school_id WHERE 
                      sft.school_id = ".$school_id." ORDER BY ft.status desc, ft.title ASC";
    $fee_query = $this->db->query($fee_qur)->result_array();
    
    $page_data['fee_query']  = $fee_query;
		
    $page_data['page_name']  = 'fee_types';
    
    $page_data['page_title'] = get_phrase('fee_types');
          
    $this->load->view('backend/index', $page_data);

}
function get_coa_exists($school_assign_id , $coa_id)
{

    $coa_exists_str = "select * from ".get_school_db().".school_coa 
                        where school_id =$school_assign_id and coa_id = $coa_id";
    $coa_exists_query = $this->db->query($coa_exists_str)->result_array();
    return $coa_exists_query;

}


}