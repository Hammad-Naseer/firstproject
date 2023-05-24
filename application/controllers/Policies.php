<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();

class policies extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		if($_SESSION['user_login'] != 1)
		redirect('login');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->menu_ary = array();
	}
	
	function policy_categories($action = "", $id = "")
	{
		$school_id=$_SESSION['school_id'];
		
		if ($action == "add")
		{
			$data['title']     = $this->input->post('title');
			$data['school_id'] = $_SESSION['school_id'];
			$this->db->insert(get_school_db().'.policy_category', $data);
			
			$school_teachers = get_school_teachers();
            foreach($school_teachers as $teacher){
                $device_id  =   get_user_device_id(3 , $teacher['user_login_detail_id'] , $_SESSION['school_id']);
                $title      =   "New School Policy";
                $message    =   "A New School Policy Has Been Created By The Admin.";
                $link       =    base_url()."teacher/policies_listing";
                sendNotificationByUserId($device_id, $title, $message, $link , $teacher['user_login_detail_id'] , 3);
            }
			
            $this->session->set_flashdata('club_updated',get_phrase('policy_category_is_created'));		
	        redirect(base_url().'policies/policies_listing');
		}
		
		else if($action == "edit")
		{
			$data['title'] = $this->input->post('title');
			$policies_cat_id = $this->input->post('pol_cat_id');
			$this->db->where('policy_category_id',$policies_cat_id);
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->update(get_school_db().'.policy_category',$data);
            $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
	        redirect(base_url() . 'policies/policies_listing/');
		}

		else if($action == 'delete')
		{
			
    		$rec=$this->db->query("select policy_category_id from ".get_school_db().".policies where school_id=$school_id and policy_category_id=$id ")->result_array();
    		if(count($rec)>0){
    			$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
    		}
    		else{
    			$this->db->where('policy_category_id',$id);
                $this->db->where('school_id',$school_id);
                $this->db->delete(get_school_db().'.policy_category');
                $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
    		}	
			redirect(base_url() . 'policies/policies_listing/');

		}
		
		$school_id = $_SESSION['school_id'];
		$page_data['policy_categories'] = $this->db->query("SELECT * FROM ".get_school_db().".policy_category WHERE school_id=$school_id ORDER BY policy_category_id DESC")->result_array();
		$page_data['page_name'] = 'policy_categories';
		$page_data['page_title'] = get_phrase('policy_categories');
		
		$this->load->view('backend/index', $page_data);
		
	}
	
	function policies_listing($action = "", $id = 0)
	{
		if($action == "add_edit")
		{
			$data['title'] = $this->input->post('title');
			$policies_id = $this->input->post('policies_id');
			$data['document_num'] = $this->input->post('document_num');
			$data['approval_date'] = date_slash($this->input->post('approval_date'));
			$data['version_num'] = $this->input->post('version_num');
			$data['policy_category_id'] = $this->input->post('pol_cat');
			$data['author'] = $this->input->post('author');
			$data['approved_by'] = $this->input->post('approved_by');
			$data['detail'] = $this->input->post('detail');
			$data['is_active']=0;
			$data['staff_p']=0;
			$data['student_p']=0;
			
			if($this->input->post('is_active')=="on"){
				$data['is_active']=1;
			}
			
			if($this->input->post('staff_p')=="on"){
				$data['staff_p']=1;
			}
			
			if($this->input->post('student_p')=="on"){
				$data['student_p']=1;
			}
			$school_id = $_SESSION['school_id'];
			$filename  = $_FILES['attachment']['name'];
			if($filename != "")
			{
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$data['attachment'] = file_upload_fun('attachment','policies','policies');
				$old_attachment = $this->input->post('old_attachment');
				if($old_attachment != "")
				{
					$del_location = system_path($old_attachment,'policies');
                    file_delete($del_location);
				}
			}
			if($policies_id != "")
			{
				$this->db->where('policies_id',$policies_id);
				$this->db->where('school_id',$school_id);
				$this->db->update(get_school_db().'.policies',$data);
				$this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
			}
			else
			{
				$data['school_id'] = $school_id;
				$this->db->insert(get_school_db().'.policies',$data);
				$this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));

			}
			redirect(base_url() . 'policies/policies_listing/');

		}

		else if($action == 'delete')
		{
			$school_id      = $_SESSION['school_id'];
			$old_attachment = $this->uri->segment(5);

			if($old_attachment != "")
			{
				$del_location = "uploads/student_image/$old_attachment";
                file_delete($del_location);
			}
			$delete_ary = array('school_id'  =>$school_id,'policies_id'=>$id);
			$this->db->where($delete_ary);
			$this->db->delete(get_school_db().'.policies');
			$this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));

			redirect(base_url() . 'policies/policies_listing');
		}
		
		$page_data['policy_filter'] = '';
		if ($action == 'filter')
		{
			$page_data['policy_filter'] = " policy_category_id = $id AND ";
			$page_data['filter'] = true;
		}
		
		$qqur="select * from   ".get_school_db().".policy_category where $policy_filter school_id=".$_SESSION['school_id'];
        $query_val =$this->db->query($qqur)->result_array();
		
		$page_data['query_val'] =  $query_val;
		$page_data['page_name'] = 'policies_listing';
		$page_data['page_title'] = get_phrase('policies');
		$this->load->view('backend/index', $page_data);
	}

	function add_edit_policies()
	{
		$page_data['page_name'] = 'policies_add_edit';
		$page_data['page_title'] = get_phrase('policies_add_edit');
		$this->load->view('backend/index', $page_data);
	}

}