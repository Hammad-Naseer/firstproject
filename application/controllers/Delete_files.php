<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();


class Delete_files extends CI_Controller{
  
    function __construct()
    {
        parent::__construct();
        if($_SESSION['user_login'] != 1)
        redirect('login');
        
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->menu_ary=array();
        $this->load->helper("student");
    }

    //function delete_file($img_name,$table_name,$field_id,$id,$img_field,$folder_name,$db_name="")
    function delete_file()
    {
        $img_name=$this->input->post('img_name');
    
    	$table_name=$this->input->post('table_name');
    
    	$field_id=$this->input->post('field_id');
    
    	$id=$this->input->post('id');
    
    	$img_field=$this->input->post('img_field');
    
    	$folder_name=$this->input->post('folder_name');
    
    
    	$db_name=$this->input->post('db_name');
    	$school_id=$this->input->post('school_id');
    	$is_root=$this->input->post('is_root');
    	$data_base=get_school_db();
    	$sch_id=$_SESSION['school_id'];
    	$root_val=0;
    	if($is_root==1)
    	{
    		$root_val=1;
    	}
    	if($school_id!="" ||  $school_id==0)
    	{
    		$sch_id=$school_id;
    		
    	}
    	if($db_name!="")
    	{
    		$data_base=$db_name;
    		
    	}
    	
    	
    	$this->db->where($field_id,$id);
    	if($sch_id > 0)
    	{
    		$this->db->where('school_id',$sch_id);
    	}	
    		
    	$this->db->update($data_base.'.'.$table_name,array($img_field=>""));
    	
    	
        echo $path_std=system_path($img_name,$folder_name,$root_val);
    	echo "<br>";
    	file_delete($path_std);
    
    }

}
	
	
