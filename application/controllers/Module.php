<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();

class Module extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		//$this->load->database();
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
			redirect(base_url() . 'login');
		
    }
     /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
 		if ($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
      
		$page_data['module']  = $this->db->order_by('module_id', 'desc')->get(get_system_db().'.module')->result_array();
		$page_data['page_name']  = 'module';
		$page_data['page_title'] = get_phrase('manage_modules');
		$this->load->view('backend/index', $page_data);
    }

	public function save($param1='',$param2='',$param3='')
	{
		if($param1=='create')
		{
			$data['title']=$title=$this->input->post('title');
			$data['status']=$status=$this->input->post('status');
			$data['parent_module_id']=$parent=$this->input->post('parent_module');
			$this->db->insert(get_system_db().'.module',$data);

			$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));

			redirect(base_url().'module/index/');
			
		}
		if($param1=='edit')
		{
			$data['title']=$title=$this->input->post('title');
			$data['status']=$status=$this->input->post('status');
			$data['parent_module_id']=$parent=$this->input->post('parent_module');

			$this->db->where('module_id',$param2);
			$this->db->update(get_system_db().'.module',$data);

			$this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
			redirect(base_url().'module/index/');
			
		}
		if($param1=='delete')
		{
			if ($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
			$data['module_id']=$param2;
			$this->db->delete(get_system_db().'.module',$data);

			$this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));

			redirect(base_url().'module/index/');
		}
	}
	
	
	public function action()
	{
		
		if ($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
		$page_data['action']      = $this->db->order_by('action_id', 'desc')->get(get_system_db().'.action')->result_array();
		$page_data['page_name']  = 'action';
		$page_data['page_title'] = get_phrase('manage_actions');
		$this->load->view('backend/index', $page_data);
	}

	function validate_action_code()
	{
		$code = trim($this->input->post('code'));
		$action_id = intval($this->input->post('action_id'));

		if (check_action_code($code, $action_id) == '')
		{
			echo 'success';
		}
		else
			echo 'failure';
		exit();
	}
	public function saveaction($param1='',$param2='',$param3='')
	{
		if($param1=='create')
		{
			$data['title']= trim($title=$this->input->post('title'));
			$data['code']= trim($title=$this->input->post('code'));
			$data['status'] = trim($status=$this->input->post('status'));
			$data['module_id']= $parent = $this->input->post('module_id');
			
			if( $data['module_id'] !='' &&  $data['status'] !='' && $data['title']!='' && $data['code']!='' && $data['code']!='')
			{
				$this->db->insert(get_system_db().'.action', $data);
				$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
			}
			redirect(base_url().'module/action/');
		}
		elseif($param1=='edit')
		{
			$data['title']=$title=trim($title=$this->input->post('title'));
			$data['code'] = trim($title=$this->input->post('code'));
			$data['status']=$status=trim($this->input->post('status'));
			$data['module_id']=$parent=trim($this->input->post('module_id'));

			$resCode=$this->db->query("select code from ".get_system_db().".action where code='".$data['code']."' and action_id=".$param2."")->result_array();

			if($data['module_id']!='' &&  $data['status']!='' && $data['title']!='' && $data['code']!='' && ($data['code']!='' || $resCode[0]['code']==$data['code']))
			{
				$this->db->where('action_id',$param2);
				$this->db->update(get_system_db().'.action',$data);
				$this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
			}

			redirect(base_url().'module/action/');
		}
		if($param1=='delete')
		{
			$data['action_id']= intval($param2);
			$this->db->delete(get_system_db().'.action',$data);
			$this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
			redirect(base_url().'module/action/');
		}
	}
	
    
}