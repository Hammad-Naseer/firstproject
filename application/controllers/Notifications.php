<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();

class Notifications extends CI_Controller
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
		if($_SESSION['user_login']!= 1)
			redirect(base_url() . 'login');
	}
	
	public function set_firebase_data()
	{
	    $user_type      =   $this->input->post('user_type');
	    $user_id        =   $this->input->post('user_id');
	    $school_id      =   $_SESSION['school_id'];
	    $device_id      =   $this->input->post('device_id');
	    $admin_id       =   "0";
	    $teacher_id     =   "0";
	    $parent_id      =   "0";
	    $field          =   '';
	    
	    if($user_type == 1){
	        $admin_id = $user_id;
	        $field = 'admin_id';
	        
	    }elseif($user_type == 3){
	        $teacher_id = $user_id;
	        $field = 'teacher_id';
	        
	    }elseif($user_type == 4){
	        $parent_id = $user_id;
	        $field = 'parent_id';
	    } 
	    

	    $device_query = "select id from ".get_system_db().".user_devices 
                         where
                         admin_id   = ". $admin_id  ."   and
                         teacher_id = ". $teacher_id."   and
                         parent_id  = ". $parent_id ."   and
                         school_id  = ". $school_id ."   limit 1"; 
        $device_result = $this->db->query($device_query);
                	      
        if($device_result->num_rows() > 0) {
               $data_device['device_id'] =  $device_id;
               $data_device['updated_at'] = date('Y-m-d H:i:s');
               $this->db->where('admin_id'  , $admin_id);
               $this->db->where('teacher_id', $teacher_id);
               $this->db->where('parent_id' , $parent_id);
               $this->db->where('school_id' , $school_id);
			   $this->db->update(get_system_db().'.user_devices', $data_device);   
			   echo "updated";
        }
        else
        {
            $data_device = array(
    	       'admin_id'    => $admin_id,
    	       'teacher_id'  => $teacher_id, 
    	       'parent_id'   => $parent_id, 
    	       'student_id'  => 0, 
    	       'school_id'   => $school_id, 
    	       'device_id'   => $device_id, 
    	       'inserted_at' => date('Y-m-d H:i:s'), 
    	       'platform'    => $this->input->post('platform') 
    	    );
    	    if($this->db->insert(get_system_db() . ".user_devices", $data_device)){
    	        echo "Inserted";
    	    }else{
    	         echo "Not Inserted";
    	    }
    	    
        }
	   
	   
}

public function update_is_viewed(){
        $not_id        =   $this->input->post('not_id');
        $data_not['is_viewed'] =  1;
        $this->db->where('id' , $not_id);
        $this->db->update(get_school_db().'.school_notifications', $data_not);   
        echo "updated";
}



}