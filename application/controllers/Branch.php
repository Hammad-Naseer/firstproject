<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();


class Branch extends CI_Controller{
  
    function __construct()
    {
    	parent::__construct();
    	
        if ($_SESSION['user_login'] != 1)
	            redirect(base_url() . 'login');
	    
    	$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    	$this->output->set_header('Pragma: no-cache');
    	$this->menu_ary=array();
    }

	public function index()
	{
	    
		 if ($_SESSION['user_login'] != 1)
	            redirect(base_url() . 'login');
	     if ($_SESSION['user_login'] == 1)
	            redirect(base_url() . 'admin/dashboard');
	}
	
	
	function branches($param1 = '', $param2 = '', $param3 = '',$param4 = '')
	{
		$login_type_id = get_login_type_id('branch_admin');

		if ($param1 == 'create')
	    {
	        $branch_name        = $this->input->post('branch_name');
	        $system_school_data['landing_page'] =   str_replace(" ","_",strtolower($this->input->post('branch_name')));
	        $system_school_data['sub_domain']=   str_replace(" ","_",strtolower($this->input->post('branch_name')));
			$system_school_data['parent_sys_sch_id']   = $_SESSION['sys_sch_id'];
			$system_school_data['status']              = 1;
			$system_school_data['package_id']          = $_SESSION['package_id'];
			$system_school_data['school_db']           = $_SESSION['school_db']; 
			$this->db->insert(get_system_db().'.system_school', $system_school_data);
			$last_id                                   = $this->db->insert_id();
			
			$user_login_data['name']       = $this->input->post('admin_name');
			$user_login_data['email']      = $this->input->post('email');
			$user_login_data['password']   = passwordHash($this->input->post('password'));
			$user_login_data['id_no']      = microtime(true);
			$user_login_data['status']     = 1;

			$this->db->insert(get_system_db().'.user_login', $user_login_data);
			$user_login_last_id=$this->db->insert_id();
			$system_id = $user_login_data['system_id'] = get_system_id($user_login_last_id, $last_id,'branch');//$user_login_last_id.time().'|'.$last_id.'110';

			$q3="UPDATE ".get_system_db().".user_login set system_id='$system_id' WHERE user_login_id=$user_login_last_id";
			$this->db->query($q3);

			$detail['user_login_id']        = $user_login_last_id;
			$detail['sys_sch_id']           = $last_id;
			$detail['creation_date']        = date('Y-m-d h:i:s');
			$detail['created_by']           = intval($_SESSION['user_login_id']);
			$detail['status']               = intval($this->input->post('status'));
			$detail['login_type']           = $login_type_id;
			$detail['status']               = 1; // added by tm
			$this->db->insert(get_system_db().".user_login_details", $detail); //inserting branch admin detail
			$detail['user_login_id']        = intval($_SESSION['user_login_id']);
			$detail['creation_date']        = date('Y-m-d h:i:s');
			$detail['status']               = 1;
			$this->db->insert(get_system_db().".user_login_details", $detail);//inserting logged in user detail
    
            $package_data['package_id']    = $this->input->post('package_id');
            $package_data['sys_school_id'] = $last_id;
            $package_data['is_trial']      = $this->input->post('is_trial');
            $package_data['is_valid']      = $this->input->post('is_valid');
            $package_data['start_date']    = $this->input->post('trial_date_start');
            $package_data['end_date']      = $this->input->post('trial_date_end');
            $this->db->insert(get_system_db().'.package_subscription', $package_data);
            $detail_school_configuration['sys_sch_id'] = $last_id;
			$q="SELECT school_db FROM ".get_system_db().".system_school WHERE sys_sch_id=$last_id";
			
			$this->db->insert(get_system_db().".school_configuration", $detail_school_configuration);//inserting logged in user detail
			$result=$this->db->query($q)->result_array();
			$school_db=$result[0]['school_db'];

			$d4['name']             = $this->input->post('branch_name');
			$d4['address']          = $this->input->post('address');
			$d4['phone']            = $this->input->post('phone');
			$d4['url']              = $this->input->post('url');
			$d4['email']            = $this->input->post('school_email');
			$d4['contact_person']   = $this->input->post('contact_person');
			$d4['designation']      = $this->input->post('designation');
			$d4['slogan']           = $this->input->post('slogan');
			$d4['detail']           = $this->input->post('detail');
			$d4['school_regist_no'] = $this->input->post('school_regist_no');
			$d4['country_id']       = $this->input->post('country_id');
			$d4['province_id']      = $this->input->post('province_id');
			$d4['city_id']          = $this->input->post('city_id');
			$d4['location_id']      = $this->input->post('location_id');
			$d4['sys_sch_id']       = $last_id;
			
			$folder_name="sch".$last_id."-".$_SESSION['sys_sch_id'];
			
			$filename=$_FILES['userfile']['name'];
			
			if($filename!="")
			{
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
			 	$d4['logo']=file_upload_fun('userfile',$folder_name,'setting',1);	
			}

			$d4['folder_name']=$folder_name;
			$this->db->insert($school_db.'.school', $d4);

			redirect(base_url() . 'branch/branches/');
		}
		if ($param1 == 'do_update') 
		{
			$data['name']        = $this->input->post('branch_name');
			$data['address'] = $this->input->post('address');
			$data['phone'] = $this->input->post('phone');
			$data['url']         = $this->input->post('url');
			$data['email']         = $this->input->post('school_email');
			$data['contact_person']         = $this->input->post('contact_person');
			$data['designation']         = $this->input->post('designation');
			$data['slogan']         = $this->input->post('slogan');
			$data['detail']         = $this->input->post('detail');
			$data['country_id']=$this->input->post('country_id');
			$data['province_id']=$this->input->post('province_id');
			$data['city_id'] = $this->input->post('city_id');
			$data['location_id'] = $this->input->post('location_id');
			$data['school_regist_no']         = $this->input->post('school_regist_no');

			$data2['name']   = $this->input->post('admin_name');
			$data2['email']   = $this->input->post('admin_email');
			$password = passwordHash($this->input->post('password'));
			if($password!="") 
			{
				$data2['password'] = $password;
			}


			$folder_hidden=$this->input->post('folder_hidden');
			$filename=$_FILES['userfile']['name'];

			$old_image= $this->input->post('old_image');
			$filename=$_FILES['userfile']['name'];
			if($filename!="")
			{
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$data['logo']=file_upload_fun('userfile',$folder_hidden,'setting',1);
				if($old_image!="")
				{
					$del_location=system_path($old_image,$folder_hidden,1);
                    file_delete($del_location);
				}
			}


			$this->db->where('school_id',$param2);         
			$this->db->update(get_school_db().'.school', $data);
			$this->db->where('user_login_id',$param3);         
			$this->db->update(get_system_db().'.user_login', $data2);

			$login_detail['status']         = $this->input->post('status');
			$sys_sch_id   = intval($this->input->post('sys_sch_id'));
			$this->db->update(get_system_db().'.user_login_details', $login_detail,
					array( 	
							"user_login_id" => $param3,
							"sys_sch_id" => $sys_sch_id,
							"login_type" => $login_type_id, 
					)
				);

			$package_data['package_id'] = $this->input->post('package_id');
            $package_data['is_trial'] = $this->input->post('is_trial');
            $package_data['is_valid'] = $this->input->post('is_valid');
            $package_data['start_date'] = $this->input->post('trial_date_start');
            $package_data['end_date'] = $this->input->post('trial_date_end');
            $this->db->where('sys_school_id',$sys_sch_id);         
			$this->db->update(get_system_db().'.package_subscription', $package_data);

			redirect(base_url() . 'branch/branches/');
		}
	    else if ($param1 == 'personal_profile') 
		{
		     $page_data['personal_profile']   = true;
		     $page_data['current_principal_id'] = $param2;
		} 
		else if ($param1 == 'edit') 
		{
		      $page_data['edit_data'] = $query->result_array();
		}

		if ($param1 == 'delete') 
		{
			
			$folder=$param3;
			$file=$this->uri->segment(6);

			$arr_url=explode("-",$param2);
			$sys_sch_id=$arr_url[0];
			$school_id= $arr_url[1];

			$p="SELECT school_db FROM ".get_system_db().".system_school WHERE sys_sch_id=$sys_sch_id";
			$query=$this->db->query($p)->result_array();
			$school_db=$query[0]['school_db'];

			$qur_1=$this->db->query("select location_id from ".$school_db.".city_location where school_id=$school_id")->result_array();
			if(count($qur_1)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}

			$qur_2=$this->db->query("select policy_category_id from ".$school_db.".policy_category where school_id=$school_id")->result_array();
			if(count($qur_2)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}

			$qur_3=$this->db->query("select academic_year_id from ".$school_db.".acadmic_year where school_id=$school_id")->result_array();
			if(count($qur_3)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}

			$qur_4=$this->db->query("select holiday_id from ".$school_db.".holiday where school_id=$school_id")->result_array();
			if(count($qur_4)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}

			$qur_5=$this->db->query("select departments_id from ".$school_db.".departments where school_id=$school_id")->result_array();
			if(count($qur_5)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}

			$qur_6=$this->db->query("select subject_id from ".$school_db.".subject where school_id=$school_id")->result_array();
			if(count($qur_6)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}

			$qur_7=$this->db->query("select coa_id from ".$school_db.".chart_of_accounts where school_id=$school_id")->result_array();
			if(count($qur_7)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}    

			$qur_8=$this->db->query("select fee_type_id from ".$school_db.".fee_types where school_id=$school_id")->result_array();
			if(count($qur_8)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}  

			$qur_9=$this->db->query("select discount_id from ".$school_db.".discount_list where school_id=$school_id")->result_array();
			if(count($qur_9)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}   

			$qur_10=$this->db->query("select c_c_f_id from ".$school_db.".class_chalan_form where school_id=$school_id")->result_array();
			if(count($qur_10)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}     

			$qur_11=$this->db->query("select c_rout_sett_id from ".$school_db.".class_routine_settings where school_id=$school_id")->result_array();
			if(count($qur_11)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_12=$this->db->query("select class_routine_id from ".$school_db.".class_routine where school_id=$school_id")->result_array();
			if(count($qur_12)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}  

			$qur_13=$this->db->query("select designation_id from ".$school_db.".designation where school_id=$school_id")->result_array();
			if(count($qur_13)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}   

			$qur_14=$this->db->query("select student_id from ".$school_db.".student where school_id=$school_id")->result_array();
			if(count($qur_14)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_15=$this->db->query("select bulk_req_id from ".$school_db.".bulk_request where school_id=$school_id")->result_array();
			if(count($qur_15)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}

			$qur_16=$this->db->query("select b_m_c_id from ".$school_db.".bulk_monthly_chalan where school_id=$school_id")->result_array();
			if(count($qur_16)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			}  

			$qur_17=$this->db->query("select s_c_f_id from ".$school_db.".student_chalan_form where school_id=$school_id")->result_array();
			if(count($qur_17)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_18=$this->db->query("select attendance_id from ".$school_db.".attendance where school_id=$school_id")->result_array();
			if(count($qur_18)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_19=$this->db->query("select diary_id from ".$school_db.".diary where school_id=$school_id")->result_array();
			if(count($qur_19)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_20=$this->db->query("select exam_id from ".$school_db.".exam where school_id=$school_id")->result_array();
			if(count($qur_20)>0){
			     $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
			     redirect(base_url() . 'branch/branches/');
			     exit();	
			} 

			$qur_21=$this->db->query("select grade_id from ".$school_db.".grade where school_id=$school_id")->result_array();
			if(count($qur_21)>0){
			     $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
			     redirect(base_url() . 'branch/branches/');
			     exit();	
			} 

			$qur_22=$this->db->query("select subject_component_id from ".$school_db.".subject_components where school_id=$school_id")->result_array();
			if(count($qur_22)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 


			$qur_24=$this->db->query("select exam_routine_id from ".$school_db.".exam_routine where school_id=$school_id")->result_array();
			if(count($qur_24)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_25=$this->db->query("select planner_id from ".$school_db.".academic_planner where school_id=$school_id")->result_array();
			if(count($qur_25)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_26=$this->db->query("select leave_category_id from ".$school_db.".leave_category where school_id=$school_id")->result_array();
			if(count($qur_26)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_27=$this->db->query("select notice_id from ".$school_db.".noticeboard where school_id=$school_id")->result_array();
			if(count($qur_27)>0)
			{
				$this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 

			$qur_28=$this->db->query("select circular_id from ".$school_db.".circular where school_id=$school_id")->result_array();
			if(count($qur_28)>0)
			{
				$this->session->set_flashdata('club_updated','deletion_failed_record_already_in_use');	
				redirect(base_url() . 'branch/branches/');
				exit();	
			} 
	                                        
	        if($param3!="")
	        {     
	             	   
				$del_location=system_path($file,$folder,1);
                file_delete($del_location);
				rmdir("uploads/".$folder);
			}
	       
	        $this->db->where('sys_sch_id', $sys_sch_id);
	        $this->db->delete(get_school_db().'.school');
	        $user_login_ids = $this->db->query("select uld.user_login_id 
	        	from ".get_system_db().".user_login_details uld
	        	inner join ".get_system_db().".user_login ul on ul.user_login_id = uld.user_login_id
	        	where 
	        	uld.sys_sch_id = $sys_sch_id
	        	and ul.user_login_id <> ".$_SESSION['user_login_id']." 
	        	")->result_array();

	        $this->db->where('sys_sch_id', $sys_sch_id);
	        $this->db->delete(get_system_db().'.user_login_details');
	        foreach ($user_login_ids as $key => $value) 
	        {
	        	if ($value['user_login_id'] != $_SESSION['user_login_id'])
	        	{
					$this->db->where('user_login_id', $value['user_login_id']);
		        	$this->db->delete(get_system_db().'.user_login');        	
	        	}
	        }

	        $this->db->where('sys_sch_id', $sys_sch_id);
	        $this->db->delete(get_system_db().'.system_school');
	        $q="SELECT logo FROM ".get_school_db().".chalan_settings WHERE school_id=$school_id";
	        $challan=$this->db->query($q)->result_array();
	        $img=$challan[0]['logo'];
	        if($img!="")
	        {
				$del_location=system_path($img,'');
                file_delete($del_location);
			}
	     
	        $this->db->where('school_id', $school_id);
	        $this->db->delete(get_school_db().'.chalan_settings');
	        redirect(base_url() . 'branch/branches/');
		}

	    $page_data['page_name']  = 'manage_branch';
		$page_data['page_title']=get_phrase('manage_branch');
	    $this->load->view('backend/index', $page_data);
	}

    function branch_add(){
         $page_data['page_name']  = 'modal_branch_add';
		$page_data['page_title']=get_phrase('modal_branch_add');
	    $this->load->view('backend/index', $page_data);
    }
    
    function branch_edit(){
         $page_data['page_name']  = 'modal_branch_edit';
		$page_data['page_title']=get_phrase('modal_branch_edit');
	    $this->load->view('backend/index', $page_data);
    }

	function coa_assign()
    {

        $coa_ids =$this->input->post('coa_id');
        $brach_school_id=$this->input->post('brach_school_id');
        $data['school_id'] = $brach_school_id;

        $delete_chk_str = "SELECT school_id FROM ".get_school_db().".school_coa WHERE school_id = ".$brach_school_id."";
        $delete_chk_query =$this->db->query($delete_chk_str)->num_rows();

        if($delete_chk_query > 0)
        {
           $this->db->where('school_id', $brach_school_id);
           $this->db->delete(get_school_db().'.school_coa');
        }

        foreach ($coa_ids as $coa_id_key=>$coa_id_val)
        {
            $data['coa_id'] = $coa_id_val;
            $this->db->insert(get_school_db().'.school_coa', $data);
        }

        redirect(base_url() . 'branch/branches');
    }
	function call_function($action = "")
	{
		$email= $this->input->post('email');
		if ($action == 'update')
		{
			$user_login_id = intval($this->input->post('id'));
			get_email($email, 'update', $user_login_id);
		} 
		else
		{
			 get_email($email);
		}
	}
	function get_province()
	{
		echo province_option_list($this->input->post('country_id'));
	}

	function get_city()
	{
		echo city_option_list($this->input->post('province_id'));
	}

	function get_location()
	{
		echo location_option_list($this->input->post('city_id'));
	}

	function branch_details($param1 = '', $param2 = '', $param3 = '',$param4 = '')
	{
		
		$page_data['school_id']=$param1;
		$sys_sch_id=$param2;
		$p="SELECT school_db FROM ".get_system_db().".system_school WHERE sys_sch_id=$sys_sch_id";
		$query=$this->db->query($p)->result_array();
		$school_db=$query[0]['school_db'];
		$page_data['school_db']=$school_db;
		$page_data['page_name']  = 'branch_detail';
		$page_data['page_title']=get_phrase('view_details');
	    $this->load->view('backend/index', $page_data);	
	}
	
	function check_branch_assign($status)
	{
	    check_branch($status);
	}
	
	function fee_type_assign()
	{
		$id = $this->uri->segment('3');
		$fee_type_id = $this->input->post('fee_type_id');
        
        $data['school_id'] = $id;

        $delete_chk_str = "SELECT school_id FROM ".get_school_db().".school_fee_types WHERE school_id = ".$id."";
        $delete_chk_query =$this->db->query($delete_chk_str)->num_rows();

        if($delete_chk_query>0)
        {
           $this->db->where('school_id', $id);
           $this->db->delete(get_school_db().'.school_fee_types');
        }
        foreach ($fee_type_id as $fee_type_key=>$fee_type_val)
        {
            $data['fee_type_id'] = $fee_type_val;
            $this->db->insert(get_school_db().'.school_fee_types', $data);
        }
        redirect(base_url() . 'branch/branches');
	}
	function discount_list_assign()
	{
		$id = $this->uri->segment('3');
		$discount_id = $this->input->post('discount_id');
        
        $data['school_id'] = $id;

        $delete_chk_str = "SELECT school_id FROM ".get_school_db().".school_discount_list WHERE school_id = ".$id."";
        $delete_chk_query =$this->db->query($delete_chk_str)->num_rows();

        if($delete_chk_query>0)
        {
           $this->db->where('school_id', $id);
           $this->db->delete(get_school_db().'.school_discount_list');
        }

        foreach ($discount_id as $discount_key=>$discount_val)
        {
            $data['discount_id'] = $discount_val;
            $this->db->insert(get_school_db().'.school_discount_list', $data);
        }
        redirect(base_url() . 'branch/branches');
	}

}
