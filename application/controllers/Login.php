<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	private $system_db;
	private $school_db;
	
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('sub_domain');
        $this->load->helper('captcha');
        //$this->load->database();
        $this->load->dbutil();
		
		if(isset($_SESSION['system_db']))
		{
			$this->system_db = $_SESSION['system_db'];
		}
		else
		{
			$this->system_db = $this->config->item('system_db_name');
		}
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");   
        
//         if ($_SESSION['user_login'] > 0){
//             $folder = get_login_type_name($_SESSION['login_type']);
//             redirect(base_url() . $folder.'/dashboard');
    // 		}else{
    //             redirect(base_url() . 'login');
    // 		}
    }
    
    public function institute_page($domain)
    {
        
        $this->load->helper('exams');
		$sub_domain_details = sub_domain_details($domain);

		//working
		if(!empty($sub_domain_details) && is_array($sub_domain_details) && count($sub_domain_details)>0 )
		{
		    $sch_db                         =  $sub_domain_details['school_db'];
		    $get_school_tbl                 =  $this->db->get($sch_db.".school")->row();
		    
		    $data['sub_domain']             =  $domain;
		    
		    $data['school_logo']        =  $get_school_tbl->logo;
		    $data['school_name']            =  $get_school_tbl->name;
			$data['sys_sch_id']         =  $sub_domain_details['sys_sch_id'];
			$data['parent_sys_sch_id']  =  $sub_domain_details['parent_sys_sch_id'];
			$data['school_id']          =  $sub_domain_details['parent_sys_sch_id'];
			$data['school_db']          =  $sub_domain_details['school_db'];
			$data['status']             =  $sub_domain_details['status'];
			$data['package_id']         =  $sub_domain_details['package_id'];
			$data['sub_domain']         =  $sub_domain_details['sub_domain'];
			$data['landing_page']       =  $sub_domain_details['landing_page'];
			$data['data_row']           =  $sub_domain_details;
			$data['gallery_images']     = $sub_domain_details['gallery_images'];
			
			$event_array=array();
			$eventquery                  = "select * from ".$sch_db.".events_annoucments";
            $event_array                 =  $this->db->query($eventquery)->result_array();
            $data['event_array']         =  $event_array ;
            $notice_array=array();
			$noticequery                 = "select * from ".$sch_db.".noticeboard";
            $notice_array                =  $this->db->query($noticequery)->result_array();
            $data['notice_array']        =  $notice_array ;
            
			$facilityquery               = "select * from ".get_system_db().".school_facilities where sys_sch_id = ". $data['sys_sch_id'] ."  order by id desc";
            $facility_rows               =  $this->db->query($facilityquery)->result_array();
            
            $galleryquery                = "select * from ".get_system_db().".school_gallery_images where school_id = ". $data['sys_sch_id'] ."";
            $gallery_rows                =  $this->db->query($galleryquery)->result_array();
            
            
            $query                       =  "select * from ".get_system_db().".system_school where sys_sch_id = ". $data['sys_sch_id'] ."";
            $row                         =  $this->db->query($query)->row();
            
            $q=  "select * from ".$data['school_db'].".jobs where school_id=".$data['school_id']." and job_status = 1 ";
    	    $jobs_details =   $this->db->query($q)->result_array();
    	    
    	    $data['jobs_details']  =	 $jobs_details;
            

            $data['school_facilities']  =  $facility_rows;
            $data['gallery_rows']       =  $gallery_rows;
            $data['landing_page_row']  =  $row;
		
			$this->load->view('backend/custom_login/'.$sub_domain_details['landing_page'] , $data);
			
		}
		else
		{
            $this->session->set_flashdata('environment_error','Sorry! Incorrect Environment');
            redirect(base_url()."login");
		}
        
    }
    function get_job_details()
    {
        $job_id = $this->input->post('job_id');
        $school_id = $this->input->post('school_id');
        $school_db = $this->input->post('school_db');
        
        $q=  "select * from ".$school_db.".jobs where school_id=".$school_id." and job_status = 1 and job_id = ".$job_id." ";
    	$jobs_details =   $this->db->query($q)->result_array();
        if(count($jobs_details)>0)
        {
            echo json_encode(($jobs_details));
        }
        else
        {
            $jobs_details=array();
            echo json_encode($jobs_details);
        }
    }
    
    function save_application()
    {
        $data['job_id']     =   $this->input->post('job_id');
        $data['name']       =   $this->input->post('name');
        $data['mob_num']    =   $this->input->post('mob_num');
        $data['email']      =   $this->input->post('email');
        $data['address']    =   $this->input->post('address');
        $data['school_id']  =   $_SESSION['school_id'];
        
        
        $user_file= $_FILES['attachment']['name'];

        if($user_file!="")
        {

            $filename=$_FILES['attachment']['name'];
            
            $upload_path = 'uploads/' . $_SESSION['folder_name'] . '/job_applications';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $tmp_name = $_FILES["attachment"]["tmp_name"];
            
            $prefix = 'CV';
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $new_file = $prefix . '_' . time() .'.' . $ext;
            move_uploaded_file($tmp_name, $upload_path . '/' . $new_file);
            $data['attachment']=$new_file;
        
            // move_uploaded_file($tmp_name, $upload_path . '/' . $filename);
        }
        
        $this->db->insert(get_school_db().'.job_applications',$data);
	    
        echo "your job application has been added successfully";
    }
    
    public function forgot_password(){
        $config = array(
            'img_url'     =>  base_url() . 'assets/captcha_images/',
            'img_path'    =>  'assets/captcha_images/',
            'img_height'  =>  45,
            'expiration'  =>  7200,
            'word_length' =>  5,
            'img_width'   =>  '200',
            'font_size'   =>  14
        );
        //$captcha = create_captcha($config);
        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        //$data['captchaImg'] = $captcha['image'];
        $this->load->view('backend/forgot-password');
    }
    public function refresh()
    {
        $config = array(
            'img_url'     =>  base_url() . 'assets/captcha_images/',
            'img_path'    =>  'assets/captcha_images/',
            'img_height'  =>  45,
            'expiration'  =>  7200,
            'word_length' =>  5,
            'img_width'   =>  '200',
            'font_size'   =>  14
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        echo $captcha['image'];
    }

    public function forgot_password_mobile(){
        $this->load->view('backend/forgot-password-mobile');
    }
    
    public function verify_email(){
      
        $email   = $this->input->post('email');
 
        $credential	=	array(	'email' => $email);
		$query = $this->db->get_where($this->system_db.'.user_login' , $credential);
        if ($query->num_rows() > 0){
            $this->load->helper('message_helper');
            $rand    = rand(10000, 99999);
            $message = "Email verification code: ".$rand;
            $subject = "Verification Code for Forgot Password";
            
            
            $user_login_row           =   $query->row();
            $where_cond	              =	  array('user_login_id' => $user_login_row->user_login_id);
            $user_login_detail_query  =   $this->db->get_where($this->system_db.'.user_login_details' , $where_cond);
            $user_login_detail_row    =   $user_login_detail_query->row();
            
            $response = email_verfication_send('Indici-Edu', $email, $subject, $message , $user_login_detail_row->sys_sch_id);
            if($response == true){
                $cred	=	array(	'email' => $email);
    		    $check_in_db = $this->db->get_where($this->system_db.'.forgot_verification_code' , $cred);
    		    if ($check_in_db->num_rows() > 0){
    		        $data                =  array();
    		        $data['email']       =  $email;
    		        $data['code']        =  $rand;
    		        $data['secret_key']  =  md5($email);
    		        $t                   =  time();
                    $current_time        =  date("Y-m-d H:i:s",$t);
    		        $data['inserted_at'] =  $current_time;
    		        $this->db->where('email', $email);
    		        $this->db->update($this->system_db.".forgot_verification_code", $data);
    		        echo json_encode(array('check'=>true, 'message'=> '5 digit code has been sent on your email, Please check email and verify email'));
    	
    		    }
    		    else{
    		        $data = array(
    		            'email'      => $email,
    		            'secret_key' => md5($email),
    		            'code'       => $rand   
    		        );
    		        $this->db->insert($this->system_db.".forgot_verification_code", $data);   
                    echo json_encode(array('check'=>true, 'message'=> '5 digit code has been sent on your email, Please check email and verify email'));
    		    }
            }
            else{
                echo json_encode(array('check'=>false, 'message'=> 'Email could not be sent'));
            }
        }
        else{
            echo json_encode(array('check'=>false, 'message'=> 'Email not exist'));
        }

    }
    
    /*
    public function verify_email(){
        
        $email   = $this->input->post('email');
        $captcha = $this->input->post('captcha');
        if ($this->input->post('captcha') != $this->session->userdata['valuecaptchaCode']) {
            echo json_encode(array('check'=>false, 'message'=> 'Captcha code not match.'));
        }
        else{
            $credential	=	array(	'email' => $email);
    		$query = $this->db->get_where($this->system_db.'.user_login' , $credential);
            if ($query->num_rows() > 0){
                $this->load->helper('message_helper');
                $rand    = rand(10000, 99999);
                $message = "Email verification code: ".$rand;
                $subject = "Verification Code for Forgot Password";
                
                
                $user_login_row           =   $query->row();
                $where_cond	              =	  array('user_login_id' => $user_login_row->user_login_id);
                $user_login_detail_query  =   $this->db->get_where($this->system_db.'.user_login_details' , $where_cond);
                $user_login_detail_row    =   $user_login_detail_query->row();
                
                $response = email_verfication_send('Indici-Edu', $email, $subject, $message , $user_login_detail_row->sys_sch_id);
                if($response == true){
                    $cred	=	array(	'email' => $email);
        		    $check_in_db = $this->db->get_where($this->system_db.'.forgot_verification_code' , $cred);
        		    if ($check_in_db->num_rows() > 0){
        		        $data                =  array();
        		        $data['email']       =  $email;
        		        $data['code']        =  $rand;
        		        $data['secret_key']  =  md5($email);
        		        $t                   =  time();
                        $current_time        =  date("Y-m-d H:i:s",$t);
        		        $data['inserted_at'] =  $current_time;
        		        $this->db->where('email', $email);
        		        $this->db->update($this->system_db.".forgot_verification_code", $data);
        		        echo json_encode(array('check'=>true, 'message'=> '5 digit code has been sent on your email, Please check email and verify email'));
        	
        		    }
        		    else{
        		        $data               = array();
        		        $data['email']      = $email;
        		        $data['secret_key'] = md5($email);
        		        $data['code']       = $rand;
        		        $this->db->insert($this->system_db.".forgot_verification_code", $data);   
                        echo json_encode(array('check'=>true, 'message'=> '5 digit code has been sent on your email, Please check email and verify email'));
        		    }
                }
                else{
                    echo json_encode(array('check'=>false, 'message'=> 'Email could not be sent'));
                }
            }
            else{
                echo json_encode(array('check'=>false, 'message'=> 'Email not exist'));
            }
        }
    }
    */
    
    public function verify_code(){
        $email = $this->input->post('email');
        $code = $this->input->post('code');
        $credential	=	array(	'email' => $email, 'code'=>$code);
		$query = $this->db->get_where($this->system_db.'.forgot_verification_code' , $credential);
        if ($query->num_rows() > 0){
            $query = $query->result_array();
            echo json_encode(array('check'=>true, 'message'=> 'Code match', 'secret_key'=>$query[0]['secret_key']));
        }
        else{
            echo json_encode(array('check'=>false, 'message'=> 'Code not match'));
        }
    }
    public function update_password(){
        $email      =   $this->input->post('email');
        $password   =   $this->input->post('password');
        $secret_key =   $this->input->post('secret_key');
        $credential	=	array(	'email' => $email, 'secret_key'=>$secret_key);
		$query      =   $this->db->get_where($this->system_db.'.forgot_verification_code' , $credential);
        if ($query->num_rows() > 0)
        {
            $data             = array();
            $data['password'] = passwordHash($password);
            $data['is_password_updated']  = 1;
            $this->db->where('email', $email);
			$this->db->update($this->system_db.'.user_login', $data);
			$this->session->set_flashdata('club_updated', get_phrase('password_updated_successfully'));
            redirect(base_url().'login');
        }
    }
    
    public function update_password_mobile(){
        
        $email      =   $this->input->post('email');
        $password   =   $this->input->post('password');
        $secret_key =   $this->input->post('secret_key');
        $credential	=	array(	'email' => $email, 'secret_key'=>$secret_key);
		$query      =   $this->db->get_where($this->system_db.'.forgot_verification_code' , $credential);
		
        if ($query->num_rows() > 0)
        {
            $data             = array();
            $data['password'] = $password;
            $this->db->where('email', $email);
			$this->db->update($this->system_db.'.user_login', $data);
            redirect(base_url().'/login/mobile');
        }
        
    }
    
    public function mobile()
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
		$subdomain          = implode('.',$host);
		$sub_domain_details = sub_domain_details($subdomain);

		if(!empty($sub_domain_details) && is_array($sub_domain_details) && count($sub_domain_details)>0 )
		{
		    
			$_SESSION['sys_sch_id']        =  $sub_domain_details['sys_sch_id'];
			$_SESSION['parent_sys_sch_id'] =  $sub_domain_details['parent_sys_sch_id'];
			$_SESSION['school_db']         =  $sub_domain_details['school_db'];
			$_SESSION['status']            =  $sub_domain_details['status'];
			$_SESSION['package_id']        =  $sub_domain_details['package_id'];
			$_SESSION['sub_domain']        =  $sub_domain_details['sub_domain'];
			$_SESSION['landing_page']      =  $sub_domain_details['landing_page'];

			$this->load->view('backend/custom_login/'.$_SESSION['landing_page']);
			
		}
		else
		{
			$this->load->view('backend/mobile-login');
		}
    }
    
    
    public function index()
    {
        // session_destroy();
        if (isset($_SESSION['school_id']) && $_SESSION['school_id'] > 0)
        {
		}
		else
		{
			$_SESSION['school_id']=0;
		}
		
		if(isset($_SESSION['login_type']) && get_login_type_controller($_SESSION['login_type']) != ''){
            redirect(base_url().''.get_login_type_controller($_SESSION['login_type']).'/dashboard');
		}

		$url = current_url(); //$_SERVER['HTTP_HOST'];
		
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
			$this->load->view('backend/login');
		}
    }
    

	function ajax_login()
	{
		$response                   =  array();
		$email                      =  $this->security->xss_clean($_POST["email"]);
		$password                   =  $this->security->xss_clean($_POST["password"]);
		$response['submitted_data'] =  $_POST;
		
		$login_status              = $this->validate_login($email,$password);
// 		$response['login_status'] = $login_status;
    
		if ($login_status == 'success') 
		{
			$response['redirect_url'] = '';
		}
		
		if ($login_status == 'invalid_envirnoment') 
		{
			$this->session->set_flashdata('environment_error','Sorry! Incorrect Environment');
			redirect(base_url()."login");
		}
		if ($login_status == 'inactive') 
		{
			redirect(base_url()."login" );
		}
		if($response['login_status'] != '' )
		{
			$_SESSION['msg']=$response['login_status'];
			redirect(base_url()."login" );
		}
		
	}
	
	function set_user_session_data($user_login_detail_id,$school_id)
	{
	    $form_data = array(
            'user_login_detail_id'       =>  $user_login_detail_id,
            'school_id'                  =>  $school_id,
            'device'                     =>  $this->agent->platform()
        );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => base_url().'api/api_set_session_data',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $form_data,
        ));
        
        $response = curl_exec($curl);
        $result = json_decode($response);
        
        if($result->code == '200')
        {
            $_SESSION['token'] = $result->data;
            $_SESSION['code'] = $result->code;
        }else{
            $_SESSION['code'] = $result->code;
        }
	}
    
    function validate_login($email='',$password	='')
    {
        $pass  = passwordHash($password);
        $query = $this->db->where('email',$email)->where('password',$pass)->get($this->system_db.'.user_login');
        
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			if ($row->status == 1)
			{
				$_SESSION['system_db']           =  $this->system_db;
				$_SESSION['user_login_id']       =  $row->user_login_id;
				$_SESSION['user_login_name']     =  $row->email;
				$_SESSION['user_email']          =  $row->email;
				$_SESSION['name']                =  $row->name;
				$_SESSION['user_profile_pic']    =  $row->profile_pic;
				$_SESSION['user_profile_name']   =  $row->name;
				$_SESSION['user_language']       =  $row->language;
				$_SESSION['multiple_accounts']   =  0;
				
				$login_detail_arr  =  $this->db->get_where($this->system_db.'.user_login_details', array('user_login_id' => $row->user_login_id, 'status' => 1 ))->result_array();
				// echo $this->db->last_query();exit;
				$sys_schid         =  $login_detail_arr[0]['sys_sch_id'];
			    $login_type        =  $login_detail_arr[0]['login_type'];
				$log_detail_id     =  $login_detail_arr[0]['user_login_detail_id'];
				
			//	***************** Check school Environment ******************** //
                $url = current_url();
                $host = explode('.', $url);
                for ($i=0; $i < 3; $i++) {
                    array_pop($host);
                }
                $environment = implode('.',$host);
                $environment = preg_replace("(^https?://)", "", $environment );
                $school_environment =  $this->db->get_where($this->system_db.'.system_school', array('sys_sch_id' => $sys_schid))->row();
                 if($environment  != $school_environment->school_environment){
                    return 'invalid_envirnoment';
                }
                //***************** Check school Environment ********************
                
				
				if (count($login_detail_arr) == 1) 
				{
					$this->single_accounts_login($sys_schid,$login_type,$log_detail_id);
				}
				elseif (count($login_detail_arr) > 1) // if multiple account
				{
				    $this->multiple_account_login($sys_schid,$login_type,$log_detail_id);    
				}
			}
		    $_SESSION['error'] = 1;
			return 'inactive';
		}
		else
		{   
		    $this->external_std_login($email,$pass);
		    $_SESSION['error'] = 1;
			return 'inactive';
		}
    }
    
    function single_accounts_login($sys_schid,$login_type,$log_detail_id)
    {
        $school_db_where   = "";    
		$system_school_arr = $this->db->query("select * FROM ".$this->system_db.".system_school WHERE sys_sch_id = '".$sys_schid."' AND status = 1")->result_array();
	    
		if (count($system_school_arr) > 0)
		{	

		    $restrict_login = $system_school_arr[0]['restrict_login'];
		    if($restrict_login == 1) {
		        return 'error_restrict_login';
		    }
		    else 
		    {
		       
		       if($this->dbutil->database_exists($system_school_arr[0]['school_db']))
		       {
    		        $school_arr = $this->db->query("select * from ".$system_school_arr[0]['school_db'].".school WHERE sys_sch_id= ".$sys_schid." ")->result_array();
    		        
    				if(count($school_arr) > 0)
    				{
    		
    					if((empty($_SESSION['sub_domain'])) || ($_SESSION['sub_domain']==$system_school_arr[0]['sub_domain']) && !empty($system_school_arr[0]['school_db']))
    					{
    						$_SESSION['user_login']        =  1;
    						$_SESSION['sys_sch_id']        =  $sys_schid;
    						$_SESSION['parent_sys_sch_id'] =  $system_school_arr[0]['parent_sys_sch_id'];
    						$_SESSION['school_db']         =  $system_school_arr[0]['school_db'];
    						$_SESSION['package_id']        =  $system_school_arr[0]['package_id'];
    						$_SESSION['school_id']         =  $school_arr[0]['school_id'];
    						$_SESSION['sub_domain']        =  $system_school_arr[0]['sub_domain'];
    						$_SESSION['landing_page']      =  $system_school_arr[0]['landing_page'];
    						$acad_year_arr                 =  $this->db->query("select academic_year_id FROM ".get_school_db().".acadmic_year WHERE school_id = ".$_SESSION['school_id']." AND is_closed = 0 and status = 2 LIMIT 1 ")->result_array();
    						$yearly_term_arr               =  $this->db->query("select yearly_terms_id FROM ".get_school_db().".yearly_terms WHERE school_id = ".$_SESSION['school_id']." AND status = 2 and is_closed = 0 LIMIT 1")->result_array();
    						$_SESSION['academic_year_id']  =  intval($acad_year_arr[0]['academic_year_id']);
    						$_SESSION['yearly_term_id']    =  intval($yearly_term_arr[0]['yearly_terms_id']);
    						$academic_year_details = get_academic_year_dates($_SESSION['academic_year_id']);
    						$_SESSION['session_start_date']    =  $academic_year_details->start_date;
						    $_SESSION['session_end_date']      =  $academic_year_details->end_date;
    						
    						$_SESSION['login_type']        =  $login_type;
    						$redirect_controller           =  get_login_type_controller($login_type);
    						$_SESSION['login_detail_id']   =  $log_detail_id;
    						$function_name                 =  get_login_type_function_name($login_type);							
    					    $_SESSION['user_id']           =  $function_name($_SESSION['login_detail_id']);			    
    						$_SESSION['admin_rights']      =  package_rights();
    						$this->set_user_session_data($_SESSION['login_detail_id'],$_SESSION['school_id']);
    						
            				if($_SESSION['code'] == '200'){
            					redirect(base_url() .''.$redirect_controller.'/dashboard');
    						}else
    						{
    						    session_unset();
    						    $this->session->set_flashdata('error_login', get_phrase('session_cannot_be_initiallized'));
    						    redirect(base_url().'login');
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
		       else{
		            return 'error';
		       }
		       
		    } //Restrict login end
		}
		else
		{
			return 'error';
		}
    }
    
    function multiple_account_login($sys_schid,$login_type,$log_detail_id)
    {
        $_SESSION['multiple_accounts'] = 1;
		$system_school_arr = $this->db->query("select * FROM ".$this->system_db.".system_school WHERE sys_sch_id=".$sys_schid." AND status = 1")->result_array();
	    
	    // 	Check School Exist Or Not
		if (count($system_school_arr) > 0)
		{	
		    //Restrict login start
		    $restrict_login = $system_school_arr[0]['restrict_login'];
		    if($restrict_login == 1) {
		        return 'error_restrict_login';
		    }else{
		        $school_arr = $this->db->query("select * from ".$system_school_arr[0]['school_db'].".school WHERE sys_sch_id=".$sys_schid." ")->result_array();

				if(count($school_arr) > 0)
				{
					if((empty($_SESSION['sub_domain'])) || (($_SESSION['sub_domain']==$system_school_arr[0]['sub_domain']) && ($_SESSION['school_db']==$system_school_arr[0]['school_db'])))
				    {
						$_SESSION['user_login']        =  1;
						$_SESSION['sys_sch_id']        =  $sys_schid;
						$_SESSION['parent_sys_sch_id'] =  $system_school_arr[0]['parent_sys_sch_id'];
						$_SESSION['school_db']         =  $system_school_arr[0]['school_db'];
						$_SESSION['package_id']        =  $system_school_arr[0]['package_id'];
						$_SESSION['school_id']         =  $school_arr[0]['school_id'];
						$_SESSION['sub_domain']        =  $system_school_arr[0]['sub_domain'];
						$_SESSION['landing_page']      =  $system_school_arr[0]['landing_page'];

						$acad_year_arr                 =  $this->db->query("select academic_year_id FROM ".get_school_db().".acadmic_year WHERE school_id = ".$_SESSION['school_id']." AND is_closed = 0 and status = 2 LIMIT 1")->result_array();
						$yearly_term_arr               =  $this->db->query("select yearly_terms_id FROM ".get_school_db().".yearly_terms WHERE school_id = ".$_SESSION['school_id']." AND status = 2 and is_closed = 0 LIMIT 1")->result_array();

						$_SESSION['academic_year_id']  =  intval($acad_year_arr[0]['academic_year_id']);
						$_SESSION['yearly_term_id']    =  intval($yearly_term_arr[0]['yearly_terms_id']);
						$academic_year_details = get_academic_year_dates($_SESSION['academic_year_id']);
						$_SESSION['session_start_date']    =  $academic_year_details->start_date;
						$_SESSION['session_end_date']      =  $academic_year_details->end_date;
						
						$_SESSION['login_type']        =  $login_type;
						$redirect_controller           =  get_login_type_controller($login_type);
                        
						$_SESSION['login_detail_id']   =  $log_detail_id;
						$function_name                 =  get_login_type_function_name($login_type);
						$_SESSION['admin_rights']      =  package_rights();
						
				        // Api Call To Check 
						$this->set_user_session_data($_SESSION['login_detail_id'],$_SESSION['school_id']);
						
						
						//comment by hammad
                        // 		if($login_detail_arr[0]['login_type'] == 1)
                        // 		{
                        // 		    $_SESSION['user_id'] = $log_detail_id;
                        // 		}
                        // 		else
                        // 		{
                        // 	        $_SESSION['user_id'] = $function_name($_SESSION['login_detail_id']);
                        // 		}
						if($_SESSION['code'] == '200'){
        					redirect(base_url().'switch_user/account_list');
						}else
						{
						    session_unset();
						    $this->session->set_flashdata('error_login', get_phrase('session_cannot_be_initiallized'));
						    redirect(base_url().'login');
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
		        
		    } //Restrict login end
		}
		else
		{
			return 'error';
		}
    }
    
    function external_std_login($email,$password)
    {
        
	    $credentialStudent	=	array(	'id_no' => $email , 'password' => $password);
	    $queryStudent       =   $this->db->get_where($this->system_db.'.user_login' , $credentialStudent);
	    
		if ($queryStudent->num_rows() > 0)
		{	
			$row = $queryStudent->row();
			$queryStudentcount = $this->db->get_where($this->system_db.'.user_login_details', array('user_login_id' => $row->user_login_id, 'status' => 1 , 'login_type' => 6 ))->result_array();

			if(count($queryStudentcount) == 0)
			{
			    $_SESSION['errors'] = 1;
			 	redirect(base_url()); 
			}
			
			if ($row->status == 1)
			{	
			    
				$_SESSION['system_db']         =  $this->system_db;
				$_SESSION['user_login_id']     =  $row->user_login_id;
				$_SESSION['user_login_name']   =  $row->email;
				$_SESSION['user_email']        =  $row->email;
				$_SESSION['name']              =  $row->name;
				$_SESSION['user_profile_pic']  =  $row->profile_pic;
				$_SESSION['user_profile_name'] =  $row->name;
				$_SESSION['user_language']     =  $row->language;
				$_SESSION['multiple_accounts'] =  0;
				$_SESSION['NIC']               =  $row->id_no;
				$login_detail_arr              =  $this->db->get_where($this->system_db.'.user_login_details', array('user_login_id' => $row->user_login_id, 'status' => 1 ))->result_array();

				if (count($login_detail_arr) == 1) 
				{
					$school_db_where   = "";
					$system_school_arr = $this->db->query("select * FROM ".$this->system_db.".system_school WHERE sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." AND status=1")->result_array();
					
					//	***************** Check school Environment ******************** //
                    $url = current_url();
                    $host = explode('.', $url);
                    for ($i=0; $i < 3; $i++) {
                        array_pop($host);
                    }
                    $environment = implode('.',$host);
                    $environment = preg_replace("(^https?://)", "", $environment );
                    $school_environment =  $this->db->get_where($this->system_db.'.system_school', array('sys_sch_id' => $login_detail_arr[0]['sys_sch_id']))->row();
                    
                     if($environment  != $school_environment->school_environment){
                        $this->session->set_flashdata('environment_error','Sorry! Incorrect Environment');
                        redirect(base_url()."login");
                    }
                    //***************** Check school Environment ********************
                    
					
					//Restrict login start case 2
					$restrict_login = $system_school_arr[0]['restrict_login'];
					if($restrict_login == 1) {
					   return 'error_restrict_login';
					}else{
					    if (count($system_school_arr) > 0)
    				    {	
    						$school_arr = $this->db->query("select * from ".$system_school_arr[0]['school_db'].".school WHERE sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." 
    							")->result_array();				
    						if(count($school_arr) > 0)
    						{
    							if((empty($_SESSION['sub_domain'])) || (($_SESSION['sub_domain']==$system_school_arr[0]['sub_domain']) && ($_SESSION['school_db']==$system_school_arr[0]['school_db'])))
    							{
    								$_SESSION['user_login']        =  1;
    								$_SESSION['sys_sch_id']        =  $login_detail_arr[0]['sys_sch_id'];
    								$_SESSION['parent_sys_sch_id'] =  $system_school_arr[0]['parent_sys_sch_id'];
    								$_SESSION['school_db']         =  $system_school_arr[0]['school_db'];
    								$_SESSION['package_id']        =  $system_school_arr[0]['package_id'];
    								$_SESSION['school_id']         =  $school_arr[0]['school_id'];
    								$_SESSION['sub_domain']        =  $system_school_arr[0]['sub_domain'];
    								$_SESSION['landing_page']      =  $system_school_arr[0]['landing_page'];
    
    								$acad_year_arr                 =  $this->db->query("select academic_year_id FROM ".get_school_db().".acadmic_year WHERE school_id = ".$_SESSION['school_id']." AND is_closed = 0 and status = 2 LIMIT 1")->result_array();
    								$yearly_term_arr               =  $this->db->query("select yearly_terms_id FROM ".get_school_db().".yearly_terms WHERE school_id = ".$_SESSION['school_id']." AND status = 2 and is_closed = 0 LIMIT 1 ")->result_array();
                                    
    								$_SESSION['academic_year_id']  =  intval($acad_year_arr[0]['academic_year_id']);
    								$_SESSION['yearly_term_id']    =  intval($yearly_term_arr[0]['yearly_terms_id']);
    								$_SESSION['login_type']        =  $login_detail_arr[0]['login_type'];
    								$_SESSION['login_detail_id']   =  $login_detail_arr[0]['user_login_detail_id'];
    								$function_name                 =  get_login_type_function_name($login_detail_arr[0]['login_type']);
    								$_SESSION['user_id']           =  6;
    								$this->set_user_session_data($_SESSION['login_detail_id'],$_SESSION['school_id']);

            						if($_SESSION['code'] == '200'){
                    					redirect(base_url('Student_p/dashboard'),"refresh");
            						}else
            						{
            						    session_unset();
            						    $this->session->set_flashdata('error_login', get_phrase('session_cannot_be_initiallized'));
            						    redirect(base_url().'login');
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
    					else
    					{
    						return 'error';
    					}
					
					}
					//Restrict login end case 2
					
				}
				elseif (count($login_detail_arr) > 1) // if multiple account
				{
					$_SESSION['multiple_accounts'] = 1;
					redirect(base_url().'switch_user/account_list');
				}
				else // inactive record
				{
				    $_SESSION['error'] = 1;
					return 'inactive';
				}
				
			}
			$_SESSION['error'] = 1;
			return 'inactive';
		}
		else
		{
		    $_SESSION['error'] = 1;
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
		if ($result == true) 
		{
			$_SESSION['msg'] = 'password_sent';
		} 
		else if ($result == false) 
		{
			$_SESSION['msg']='account_not_found';
		}
		redirect(base_url());
		
	}
	
    function logout()
    {
		$_SESSION['msg']='logged_out';
		if($_SESSION['landing_page'] == ""){
		    $target = "login";
		}else{
		    $target = $_SESSION['landing_page'];
		}
		session_destroy();
		redirect(base_url()."institute/".$target."/logout");
    }
    
    function asked_question()
    {
        $display = '';
        $search = $this->input->post("search");
        $find_faq = $this->db->query("SELECT * FROM ".get_system_db().".frequently_asked_question WHERE question LIKE '%$search%' and is_answered = '1' ");
        if($find_faq->num_rows() > 0)
        {
            $display .= '
                <div class="row">
        				<div class="col-md-12">
        					<div class="section-title text-center wow zoomIn">
        						<h1>Frequently Asked Questions</h1>
        						<span></span>
        						<p>Our Frequently Asked Questions here.</p>
        					</div>
        				</div>
        			</div>
        			<div class="row">				
        				<div class="col-md-12">
        					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
        	        foreach($find_faq->result() as $row):				
        			$display .= '<div class="panel panel-default">
        							<div class="panel-heading" style="background: transparent !important;" role="tab" id="heading'.$row->faq_id.'">
        								<h4 class="panel-title" style="background-color: white !important;">
        									<a role="button" data-toggle="collapse" data-parent="#accordion" class="collapsed" href="#collapse'.$row->faq_id.'" aria-expanded="false" aria-controls="collapse'.$row->faq_id.'">
        										'.ucfirst($row->question).' 
        									</a>
        								</h4>
        							</div>
        							<div id="collapse'.$row->faq_id.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'.$row->faq_id.'">
        								<div class="panel-body">
        									<p>'.$row->answer.'</p>
        									<img width="450px" src="'.base_url().'system_admin/uploads/faq_answers'.$row->image.'" />
        								</div>
        							</div>
        						</div>';
                    endforeach;
        					$display .= '</div>
        				</div>		
        			</div>
            ';
        }else{
            $display .= '
                <div class="text-center">
                    <i class="fas fa-question" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                    <h2><b>Question is not found in database</b></h2>
                    <button class="btn btn-primary" id="ask_question">Ask Question</button>
                    <script>
                        
                        $("#ask_question").on("click",function(){
                            var q = "'.$search.'";
                            $.ajax({
                				type: "POST",
                				data:{question:q},
                				url: "'.base_url().'login/ask_question",
                				dataType:"html",
                				success: function(response){
                				    Command: toastr["success"](response, "Alert");
                                    toastr.options.positionClass = "toast-bottom-right";
                                    $("#ask_question").hide();
                				}
                			});
                        });
                    </script>
                </div>
            ';
        }
        
        echo $display;
    }
    
    function ask_question()
    {
        $data = array(
            'question'      => $this->input->post("question"),
            'school_id'     => $_SESSION['school_id'],
            'user_id'       => $_SESSION['user_login_id']
        );
        
        $query = $this->db->insert(get_system_db().".frequently_asked_question",$data);
        if($query)
        {
            echo "Question Inserted Successfully";
        }else{
            echo "Question Does Not Insert";
        }
    }
}