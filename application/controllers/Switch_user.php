<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Switch_User extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->menu_ary=array();
	}

	function account_list()
	{
		$this->load->view('backend/account_list');
	}

	function switch_account($u_l_detail_id=0, $student_id = 0)
	{
		if (isset($_SESSION['user_login_id']) && $_SESSION['user_login_id']!= "")
		{
			if ($u_l_detail_id != 0 )
			{
			    
				$system_db          =   $_SESSION['system_db'];
        		$user_login_id      =   intval($_SESSION['user_login_id']);
				$sess_name          =   $_SESSION['name'];
				$profile_pic        =   $_SESSION['user_profile_pic'];
				$name_login         =   $_SESSION['user_profile_name'];
				$user_language      =   $_SESSION['user_language'];
				$user_login_name    =   $_SESSION['user_login_name'];
				$user_email         =   $_SESSION['user_email'];
				$sub_domain         =   $_SESSION['sub_domain'];
				$landing_page       =   $_SESSION['landing_page'];
				
				
				$_SESSION['system_db']           =    $system_db;
				$_SESSION['user_login_id']       =    $user_login_id;
				$_SESSION['name']                =    $sess_name;
				$_SESSION['user_login_name']     =    $user_login_name;
				$_SESSION['user_email']          =    $user_email;
				$_SESSION['user_language']       =    $user_language;
				$_SESSION['multiple_accounts']   =    1;
				$_SESSION['user_profile_pic']    =    $profile_pic;
				$_SESSION['user_profile_name']   =    $name_login;
				$_SESSION['sub_domain']          =    $sub_domain;
				$_SESSION['landing_page']        =    $landing_page;
				
				$login_detail_arr = $this->db->get_where($system_db.'.user_login_details' , array('user_login_detail_id' => $u_l_detail_id, 'status' => 1 ) )->result_array();
				
				if (count($login_detail_arr) > 0) 
				{
				      $system_school_arr = $this->db->query("select school_db , parent_sys_sch_id , package_id  FROM ".$system_db.".system_school WHERE sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." AND status=1 ")->result_array();
					//$system_school_arr = $this->db->query("select * FROM ".$system_db.".system_school WHERE sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." AND status=1 ")->result_array();
					if (count($system_school_arr) > 0)
					{	
						$school_arr = $this->db->query("select school_id from ".$system_school_arr[0]['school_db'].".school WHERE sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." ")->result_array();
						
						if(count($school_arr) > 0)
						{
							$_SESSION['user_login']        =    1;
							$_SESSION['sys_sch_id']        =    $login_detail_arr[0]['sys_sch_id'];
							$_SESSION['parent_sys_sch_id'] =    $system_school_arr[0]['parent_sys_sch_id'];
							$_SESSION['school_db']         =    $system_school_arr[0]['school_db'];
							$_SESSION['package_id']        =    $system_school_arr[0]['package_id'];
							$_SESSION['school_id']         =    $school_arr[0]['school_id'];

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
							$_SESSION['yearly_term_id']   = intval($yearly_term_arr[0]['yearly_terms_id']);
							$_SESSION['login_type']       = $login_detail_arr[0]['login_type'];
							$redirect_controller          = get_login_type_controller($login_detail_arr[0]['login_type']);
							$_SESSION['login_detail_id']  = $login_detail_arr[0]['user_login_detail_id'];
							$function_name                = get_login_type_function_name($login_detail_arr[0]['login_type']);
							$_SESSION['user_id']          = $function_name($_SESSION['login_detail_id']);
							
							if ( isset($student_id) && intval($student_id) != 0)
							{
								redirect(base_url().'parents/dashboard/'.$student_id);
								exit();
							}
							
							redirect(base_url() .''.$redirect_controller.'/dashboard');
						}
					}
				}
			}
			else
			{
				redirect(base_url().'switch_user/account_list');
			}
		}
		else
		{
			redirect(base_url().'login');
		}
	}

    
}