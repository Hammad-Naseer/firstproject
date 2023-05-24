<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();


class Category extends CI_Controller{
  
    function __construct(){

        parent::__construct();

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->menu_ary=array();
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
	}

    public function index()
    {
    	    if ($_SESSION['user_login'] != 1)
                redirect(base_url() . 'login');
            if ($_SESSION['user_login'] == 1)
                redirect(base_url() . 'admin/dashboard');
    }

    function categories($param1 = '', $param2 = '' , $param3 = '')
    {
    	if($_SESSION['user_login']!= 1)
    		redirect(base_url());
    		 
			$data['title']       = $this->input->post('title');
			$data['school_id'] = $_SESSION['school_id'];
			$q=$this->db->insert(get_school_db().'.subject_category', $data);
			$query=$this->db->affected_rows();
			if($query > 0)
			{
				echo '<span class="green">'.get_phrase('record_saved_successfully').'</span>';
			}
			else
			{
				echo '<span class="red">'.get_phrase('record_not_saved').'</span>';
			}
    			
    }

    function category_generator()
    {
    	$this->load->view('backend/admin/ajax/get_subj_category.php',$page_data);
    }

    function edit_category()
    {
    	if($_SESSION['user_login']!= 1)
    		redirect(base_url());
    		
        	$id=$this->input->post('id');
        	$title=$this->input->post('title');
        	$data['title']=$title;
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('subj_categ_id', $id);
            $query=$this->db->update(get_school_db().'.subject_category', $data);
            
            if($query==1)
            {
        	   echo '<span class="green">'.get_phrase('record_updated').'</span>';
            }
            else
            {
        	   echo '<span class="red">'.get_phrase('record_not_updated').'</span>';
            }
    	
    }

    function delete_category()
    {
    	
    	if($_SESSION['user_login']!= 1)
    		redirect(base_url());
    		
        	$id=$this->input->post('id');
        	$q="SELECT subject_id FROM ".get_school_db().".subject WHERE subj_categ_id=".$id;
        	$res=$this->db->query($q);
        	if($res->num_rows > 0)
        	{
        		echo '<span class="red">'.get_phrase('record_cannot_be_deleted_because_this_record_is_in_use_of_system').'</span>';
        	}
        	else
        	{
        		$this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('subj_categ_id', $id);
                $this->db->delete(get_school_db().'.subject_category');
                $query=$this->db->affected_rows();
                if($query > 0)
            	{
            		echo '<span class="green">'.get_phrase('record_deleted').'</span>';
            	}
            	else
            	{
            		echo '<span class="red">'.get_phrase('record_not_deleted').'</span>';
            	}
        	}
    	
    }

}