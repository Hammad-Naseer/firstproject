<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start(); 

class Signup extends CI_Controller
{
	private $system_db;
	private $school_db;
	
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('crud_model');
        $this->load->helper('sub_domain');
		if(isset($_SESSION['system_db']))
		{
			$this->system_db=$_SESSION['system_db'];
		}
		else
		{
			$this->system_db= $this->config->item('system_db_name');
		}
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");   
    }
    //Default function, redirects to logged in user area
    public function opensms()
    {
        $this->load->view('send_sms');
    }
    
    public function smstesting()
    {
        //echo "ss"; exit;
        $to = $this->input->post('phoneNumber');
        $message = $this->input->post('smsMessage');
        //$to=validate_phone_num($to);
        $CI=& get_instance();
        $CI->load->database();
        //api code
        $username = "f3tech";
        $password = "abcd@1234";
        $mobile = $to;
        $sender = "F3 Tech";
        //echo $mobile;exit;
        //$message = "Test SMS From SendPK.com";
        $url = "https://brandyourtext.com/sms/api/send?username=pispl&password=123456&mask=Indici Edu&mobile=".$to."&message=".$message."";
        // $url = "http://sendpk.com/api/sms.php?username=".$username."&password=".$password."&mobile=".$mobile."&sender=".urlencode($sender)."&message=".urlencode($message)."";
        //$url = "http://115.186.130.36:8001/scms/v3/api/mt.php?phoneno=".$mobile."&message=".urlencode($message)."&uname=".$username."&pwd=".$password."";
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $responce = curl_exec($ch);
       if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
           echo $error_msg;
        }
        
        //end api
        // $data['sys_sch_id']=$_SESSION['sys_sch_id'];
        // $data['recepient']=$to;
        // $data['sender']=$from;
        // $data['student_id']=$student_id;
        // $data['message']=$message;
        // $data['status']=$responce;//$response;
        // $data['is_send']=1;//$response;
        // $data['sms_type']=1;
        // $data['sms_service']="F3 SMS API";
        // $CI->db->insert(get_system_db().".sms_log",$data);
        
        echo $responce;
    }
    public function index($nicNumer)
    {
        //session_start();
        if (isset($_SESSION['school_id']) && $_SESSION['school_id'] > 0)
        {
		}
		else
		{
			$_SESSION['school_id']=0;
		}
		if ( get_login_type_controller($_SESSION['login_type']) != '' )
		{
            redirect(base_url().''.get_login_type_controller($_SESSION['login_type']).'/dashboard');
		}

		$url = $_SERVER['HTTP_HOST'];
		//$url = 'apsfwo.gsims.com.pk';
		// $url = 'demo.gsims.com.pk';
		$host = explode('.', $url);
		for ($i=0; $i < 3; $i++) {
			array_pop($host);
		}
		$subdomain = implode('.',$host);
		$sub_domain_details = sub_domain_details($subdomain);

		if(!empty($sub_domain_details) && is_array($sub_domain_details) && count($sub_domain_details)>0 )
		{
			$_SESSION['sys_sch_id'] = $sub_domain_details['sys_sch_id'];
			$_SESSION['parent_sys_sch_id'] = $sub_domain_details['parent_sys_sch_id'];
			$_SESSION['school_db']=$sub_domain_details['school_db'];
			$_SESSION['status']=$sub_domain_details['status'];
			$_SESSION['package_id']=$sub_domain_details['package_id'];
			$_SESSION['sub_domain']=$sub_domain_details['sub_domain'];
			$_SESSION['landing_page']=$sub_domain_details['landing_page'];

			//load custom login page here
			$this->load->view('backend/custom_login/'.$_SESSION['landing_page']);
		}else{
		    
			$this->load->view('backend/signup');
		}
    }
	function ajax_login()
	{
		$response = array();
		$email = $_POST["email"];
		$password = $_POST["password"];
		$response['submitted_data'] = $_POST;
		//Validating login
		$login_status = $this->validate_login($email,$password);
		$response['login_status'] = $login_status;

		if ($login_status == 'success') 
		{
			$response['redirect_url'] = '';
		}
		if($response['login_status'] != '' )
		{
			//session_start();
			$_SESSION['msg']=$response['login_status'];
			redirect(base_url() );
		}
	}
	function conform(){
	     //session_start();
        if (isset($_SESSION['school_id']) && $_SESSION['school_id'] > 0)
        {
		}
		else
		{
			$_SESSION['school_id']=0;
		}
		if ( get_login_type_controller($_SESSION['login_type']) != '' )
		{
            redirect(base_url().''.get_login_type_controller($_SESSION['login_type']).'/dashboard');
		}

		$url = $_SERVER['HTTP_HOST'];
		//$url = 'apsfwo.gsims.com.pk';
		// $url = 'demo.gsims.com.pk';
		$host = explode('.', $url);
		for ($i=0; $i < 3; $i++) {
			array_pop($host);
		}
		$subdomain = implode('.',$host);
		$sub_domain_details = sub_domain_details($subdomain);

		if(!empty($sub_domain_details) && is_array($sub_domain_details) && count($sub_domain_details)>0 )
		{
			$_SESSION['sys_sch_id'] = $sub_domain_details['sys_sch_id'];
			$_SESSION['parent_sys_sch_id'] = $sub_domain_details['parent_sys_sch_id'];
			$_SESSION['school_db']=$sub_domain_details['school_db'];
			$_SESSION['status']=$sub_domain_details['status'];
			$_SESSION['package_id']=$sub_domain_details['package_id'];
			$_SESSION['sub_domain']=$sub_domain_details['sub_domain'];
			$_SESSION['landing_page']=$sub_domain_details['landing_page'];

			//load custom login page here
			$this->load->view('backend/custom_login/'.$_SESSION['landing_page']);
		}else{
			//load default login page here
			$this->load->view('backend/conform');
		}
	}
	function manage_parent_conform(){
	    //echo "<pre>"; print_r($this->input->post());exit;
	    $this->load->helper('string');
        $codeRandom = rand(1000,9999);
        $parent_data['parent_code'] = $codeRandom;
	    $parent_data['id_no'] = $this->input->post('f_cnic');
        $parent_data['contact'] = $this->input->post('f_p_mobile');
	    $_SESSION['nicnumber'] = $this->input->post('f_cnic');
	    $parent_exist = $this->db->query("select * from ".gsimscom_gsims.". student_parent 
        where 
        id_no = '".$this->input->post('f_cnic')."'
        AND contact = '".$this->input->post('f_p_mobile')."'
        ")->result_array();
        //echo "<pre>"; print_r($parent_exist);exit;
        $parent_id= $parent_exist[0]['s_p_id'];
        $parent_mobile= $parent_exist[0]['contact'];
        $_SESSION['Sechool_ID'] = $parent_exist[0]['school_id'];
        $param1 = $this->input->post('f_cnic');
        if(count($parent_exist) > 0){ 
            $login_exist = $this->db->query("select * from ".gsimscom_gsims_system.". user_login 
        where 
        id_no = '".$this->input->post('f_cnic')."'
        ")->result_array();
        if(count($login_exist) > 0){
           $_SESSION['error_msg_exits'] = 1;
          redirect(base_url() . 'signup/conform');   
        }else{
             //sms and email portion
            ///sms start
            $this->load->helper('message'); 
            $message ="Your OTP for IndiciEdu is : " . $codeRandom;
           send_sms_code($parent_mobile, 'Indici Edu', $message, $parent_id);
      	   $this->db->query("update ".gsimscom_gsims.".student_parent set parent_code = '$codeRandom' WHERE s_p_id='$parent_id'");
}
    }else{
     $_SESSION['error_msg'] = 1;
      redirect(base_url() . 'signup/conform');  
    }
     $_SESSION['sussess_msg'] = 1;
    redirect(base_url() . 'signup/index/');
        //echo "<pre>"; print_r(count($parent_exist));exit;
	}
	function manage_parent_signup($nicnumber){
	    
	    $data['parent_code'] = $this->input->post('f_code');
	    $name = $this->input->post('f_p_name');
	    $f_cnic = $this->input->post('f_cnic1');
        $password = $this->input->post('password');
        $codeVerify = $this->input->post('f_cnic');
        $email = $this->input->post('email');
       // echo '<pre>'; print_r($this->input->post());exit;
        //parent NIC exist
        $parent_exist = $this->db->query("select id_no from ".gsimscom_gsims.". student_parent 
        where 
        id_no = '".$f_cnic."' AND parent_code = '".$codeVerify."'
        ")->result_array();
        
       // echo '<pre>'; print_r(count($parent_exist));exit;
        if(count($parent_exist) > 0){ 
        $parent_arr = $this->db->query("select id_no from ".gsimscom_gsims_system.". user_login 
        where 
        id_no = '".$f_cnic."'
        ")->result_array();
       
        if (count($parent_arr) == 0) {
            	  $this->db->query("insert into ".gsimscom_gsims_system.".user_login set 
            	  name = '".$name."', 
            	  email = '".$email."' , 
            	  status = 1, 
            	  password= '".$password."',
            	  id_no = ".$f_cnic
            	 );
                //$this->db->insert('.gsimscom_gsims_system.'.'.user_login', $data);

            $last_id = intval($this->db->insert_id());
            $update_system_id = get_system_id($last_id,$_SESSION['Sechool_ID'],'parent');
           $this->db->query("update ".gsimscom_gsims_system .". user_login 
                    set system_id = '$update_system_id' WHERE user_login_id = '$last_id'");
        
            $student_update['parent_id'] = $parent_id;
            //  $this->load->helper('message');
            //     //Email Setting her
            // if(isset($email) && $email!="") {
            //      $email1 = "imtiazork@gmail.com";
            //     $message="Parent Login Created Successfully ".$name."  ";
            //     $subject="Parent Login";
            //     email_send("No Reply","Indici Edu",$email1,$subject,$message, $student_id);
            // }
            //$this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['Sechool_ID']));

            $detail['user_login_id'] = $last_id;
            $detail['sys_sch_id'] = $_SESSION['Sechool_ID'];
            $detail['creation_date'] = date('Y-m-d h:i:s');
            $detail['created_by'] = $_SESSION['user_login_id'];
            $detail['status'] =1;
            $detail['login_type'] = 4;
            $schoolID = $_SESSION['Sechool_ID'];
            $is_exists =  $this->db->query("select user_login_detail_id from ".gsimscom_gsims_system.".user_login_details 
                where
                user_login_id = $last_id
            ")->result_array();
            if (count($is_exists) == 0)
                    {
                        $this->db->query("insert into ".gsimscom_gsims_system. ".user_login_details 
                        set user_login_id = '".$last_id."', 
                        status = '1' , 
                        sys_sch_id = $schoolID,
                        login_type= '4',
                        created_by = $schoolID,
                         creation_date = NOW()
                        ");
             $last_id_user_login_details = intval($this->db->insert_id());
            $this->db->query("insert into ".gsimscom_gsims_system. ".system_school 
                        set parent_sys_sch_id = '".$last_id."', 
                        status = '1' , 
                         date_added = NOW() 
                        ");
                        
                        
                    $this->db->query("update ".gsimscom_gsims.". student_parent 
                    set parent_code = '', user_login_detail_id = '$last_id_user_login_details' WHERE id_no = '$f_cnic'");
                    $_SESSION['success_Parent_account'] = 1;
                   redirect(base_url().'login/index');
                        //$this->session->set_flashdata('club_updated', get_phrase('parent_login_created_successfully'));
                    }
                }
                else
                {
                    $_SESSION['exist'] = 1;
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                    redirect(base_url().'signup/index');
                }
	   // echo "<pre>"; print_r(count($parent_arr));exit;
        }else{
             $this->session->set_flashdata('club_updated', get_phrase('parent_nic_number_not_exist'));
            redirect(base_url().'signup/index');
        }
        $_SESSION['success_Parent_account'] = 1;
         redirect(base_url().'login/index');
	}
    //Validating login from ajax request
    function validate_login($email='',$password	='')
    {
		$credential	=	array(	'email' => $email , 'password' => $password);
		
         $query = $this->db->get_where($this->system_db.'.user_login' , $credential);
		if ($query->num_rows() > 0)
		{	
			$row = $query->row();
			if ($row->status == 1)
			{	
				$_SESSION['system_db'] = $this->system_db;
				$_SESSION['user_login_id'] = $row->user_login_id;
				$_SESSION['user_login_name'] = $row->email;
				$_SESSION['user_email'] = $row->email;
				$_SESSION['name'] = $row->name;
				$_SESSION['user_profile_pic'] = $row->profile_pic;
				$_SESSION['user_profile_name'] = $row->name;
				$_SESSION['user_language'] = $row->language;
				$_SESSION['multiple_accounts'] = 0;
				
				$login_detail_arr = $this->db->get_where($this->system_db.'.user_login_details', array('user_login_id' => $row->user_login_id, 'status' => 1 ))->result_array();
				
				if (count($login_detail_arr) == 1) 
				{
					$school_db_where ="";
					if(!empty($_SESSION['school_db']))
					{
						//$school_db_where = " and school_db='".$_SESSION['school_db']."'";
					}

					$system_school_arr = $this->db->query("select * FROM ".$this->system_db.".system_school 
						WHERE 
						sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." AND 
						status=1 $school_db_where")->result_array();
					if (count($system_school_arr) > 0)
					{	
						$school_arr = $this->db->query("select * from ".$system_school_arr[0]['school_db'].".school WHERE sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." 
							")->result_array();
						if(count($school_arr) > 0)
						{
							
							if((empty($_SESSION['sub_domain'])) || 
								(($_SESSION['sub_domain']==$system_school_arr[0]['sub_domain'])
								&& ($_SESSION['school_db']==$system_school_arr[0]['school_db'])))
							{
								$_SESSION['user_login'] = 1;
								$_SESSION['sys_sch_id'] = $login_detail_arr[0]['sys_sch_id'];
								$_SESSION['parent_sys_sch_id'] = $system_school_arr[0]['parent_sys_sch_id'];
								$_SESSION['school_db']=$system_school_arr[0]['school_db'];
								$_SESSION['package_id']=$system_school_arr[0]['package_id'];
								$_SESSION['school_id']=$school_arr[0]['school_id'];
								$_SESSION['sub_domain']=$system_school_arr[0]['sub_domain'];
								$_SESSION['landing_page']=$system_school_arr[0]['landing_page'];

								$acad_year_arr = $this->db->query("select academic_year_id
									FROM ".get_school_db().".acadmic_year 
									WHERE 
									school_id = ".$_SESSION['school_id']." AND 
									is_closed = 0 and
									status = 2
									LIMIT 1 
									")->result_array();

								$yearly_term_arr = $this->db->query("select yearly_terms_id 
									FROM ".get_school_db().".yearly_terms 
									WHERE 
									school_id = ".$_SESSION['school_id']." AND 
									status = 2 and 
									is_closed = 0
									LIMIT 1 
									")->result_array();


								$_SESSION['academic_year_id'] = intval($acad_year_arr[0]['academic_year_id']);
								$_SESSION['yearly_term_id'] = intval($yearly_term_arr[0]['yearly_terms_id']);
								$_SESSION['login_type'] = $login_detail_arr[0]['login_type'];
								$redirect_controller = get_login_type_controller($login_detail_arr[0]['login_type']);

								$_SESSION['login_detail_id'] = $login_detail_arr[0]['user_login_detail_id'];
								$function_name = get_login_type_function_name($login_detail_arr[0]['login_type']);
								
								
								$_SESSION['user_id'] = $function_name($_SESSION['login_detail_id']);
								/*if (get_login_type_name($login_detail_arr[0]['login_type']) == 'teacher') // teacher
								{
									$_SESSION['login_detail_id'] = $login_detail_arr[0]['user_login_detail_id'];
								}
								elseif (get_login_type_name($login_detail_arr[0]['login_type']) == 'parent')// parent
								{
									$_SESSION['login_detail_id'] = $login_detail_arr[0]['user_login_detail_id'];
								}*/

								redirect(base_url() .''.$redirect_controller.'/dashboard');
							}
							else
							{
								return 'error';
							}
						}
						else
						{
							return 'error';
						}
					}
					else
					{
						return 'error';
					}
				}
				elseif (count($login_detail_arr) > 1) // if multiple account
				{
					$_SESSION['multiple_accounts'] = 1;
					redirect(base_url().'switch_user/account_list');
				}
				else // inactive record
				{
					return 'inactive';
				}
			}
			return 'inactive';
		}
		else
		{
			return 'invalid';
		}
    }
    function four_zero_four()
    {
        $this->load->view('four_zero_four');
    }
	function reset_password()
	{
		$account_type = $this->input->post('account_type');
		if ($account_type == "") {
			redirect(base_url());
		}
		$email  = $this->input->post('email');
		//$result = $this->email_model->password_reset_email($account_type, $email); //SEND EMAIL ACCOUNT OPENING EMAIL
		if ($result == true) {
			//session_start();
			$_SESSION['msg']='password_sent';
		} else if ($result == false) {
			//session_start();
			$_SESSION['msg']='account_not_found';
		}
		redirect(base_url());		
	}
    function logout()
    {
        //$this->load->helper('message');
        //send_sms("03315284304",'Indici Edu',"You are log out", 1);
    	//session_start();
		session_destroy();
        //session_start();
		$_SESSION['msg']='logged_out';
		redirect(base_url() );
    }
}