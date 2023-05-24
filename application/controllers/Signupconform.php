<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Signupconform extends CI_Controller
{
    
    function get_cnic_prnt()
	{
	    
		$cnic= $this->input->post('cnic');
		$table_name=$this->input->post('table_name');
	 	$type_n= $this->input->post('type_n');
	 	$nicNumer = $this->input->post('nicNumer');
	//	echo '<pre>'; print_r($this->input->post());exit;
		$qr="select parent_code from  ".gsimscom_gsims.".student_parent  where parent_code='$cnic' AND id_no = '$nicNumer'";
		$query=$this->db->query($qr);
		if($query->num_rows()>0)
		{ 
			$data =$query->row();
			echo json_encode($data);
		}
		else
		{ 
			$data=array("value"=>"no");
			echo json_encode($data);
			//print_r($data);
		}
	} 
	//email check
	 function get_email_check()
	{
	    
		$email= $this->input->post('email');
		$table_name=$this->input->post('table_name');
		$qr="select email from  ".gsimscom_gsims_system.".user_login  where email='$email'";
		$query=$this->db->query($qr);
		if($query->num_rows()>0)
		{ 
			$data =$query->row();
			echo json_encode($data);
		}
		else
		{ 
			$data=array("value"=>"no");
			echo json_encode($data);
			//print_r($data);
		}
	}
	function get_cnic_check()
	{
	    
		$cnic= $this->input->post('cnic');
		$table_name=$this->input->post('table_name');
		$qr="select id_no from  ".gsimscom_gsims_system.".user_login  where id_no='$cnic'";
		$query=$this->db->query($qr);
		if($query->num_rows()>0)
		{ 
			$data =$query->row();
			echo json_encode($data);
		}
		else
		{ 
			$data=array("value"=>"no");
			echo json_encode($data);
			//print_r($data);
		}
	} 
	
}
?>