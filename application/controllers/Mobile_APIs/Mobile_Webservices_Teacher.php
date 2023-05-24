<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

class Mobile_Webservices_Teacher extends CI_Controller {
    
	private $system_db;
	private $school_db;
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('common');
		//$this->load->helper('mobile_web_services');
		
        $this->load->dbutil();
		
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");   
        $this->load->library('BlinqApi');
        
	}
	public function index(){
	    echo "Teacher portal web services";exit;
	}
    function get_academic_year_dates($academic_year_id = 0) {
        
        $q = "select * from ".$this->auth_array['school_db'].".acadmic_year where academic_year_id = ".$academic_year_id." and school_id=".$this->auth_array['school_id']." and status = 2";
        $arr = $this->db->query($q)->row();
        return $arr;
        
    }

    function get_login_teacher_id($user_login_detail_id = 0)
    {
        $login_detail_id = $this->db->get_where(
            $this->auth_array['school_db'] . ".staff",
            array('user_login_detail_id' => $user_login_detail_id)
        )->result_array();
    
        return intval($login_detail_id[0]['staff_id']);
    }
    
    function package_rights() {
        
        $rights_result = array();
        $rights_arr = '';
        if(get_login_type_name($this->auth_array['login_type']) == 'admin' || get_login_type_name($this->auth_array['login_type']) == 'branch_admin') {
            
            $q2="select a.* from ".$this->auth_array['school_db'].".package_rights pr 
                inner join ".$this->auth_array['school_db'].".action a on a.action_id=pr.action_id 
                inner join ".$this->auth_array['school_db'].".package p on p.package_id = pr.package_id
                where 
                p.package_id=".$this->auth_array['package_id']."
                and p.status = 1
                and a.status = 1
                and a.action_type = 1";
                
            $query = $this->db->query($q2);
            $rights_result = $query->result_array();
            
        } else
        if (get_login_type_name($this->auth_array['login_type']) == 'staff') {
            
            /*
            $q2="select  g.*,a.* from ".get_school_db().".group_rights g inner join package_rights p on g.package_right_id=p.package_right_id inner join action a on a.action_id=p.action_id where g.user_group_id=".$_SESSION['user_group_id']."";
            */
            $q2 = "select a.* from ".$this->auth_array['school_db'].".user_rights ur
                    inner join ".$this->auth_array['school_db'].".group_rights gr on gr.user_group_id = ur.user_group_id
                    inner join ".$this->auth_array['school_db'].".user_group ug on ug.user_group_id = gr.user_group_id
                    inner join ".$this->auth_array['school_db'].".action a on a.action_id = gr.action_id
                    inner join ".$this->auth_array['school_db'].".package_rights pr on pr.action_id = a.action_id
                    inner join ".$this->auth_array['school_db'].".package p on p.package_id = pr.package_id
                    where
                    ur.staff_id=".$this->auth_array['user_id']."
                    and p.package_id=".$this->auth_array['package_id']."
                    and p.status = 1 
                    and a.status = 1
                    and ug.status = 1
                    and ug.school_id = ".$this->auth_array['school_id']."
                ";
    
            $query = $this->db->query($q2);
            $rights_result = $query->result_array();
            
        }
    
        $rights_arr = array();
        if(sizeof($rights_result) > 0) {
            
            foreach($rights_result as $row) {
                // $rights_arr[$row['action_id']]=$row['code'];
                $rights_arr[] = $row['code'];
            }
            
        }
        
        return $rights_arr;
        
    }
    
  
	 //Mobile Application Functions Start
	function authenticate() {
	     $email =  $this->input->post('email');
		 $password =  $this->input->post('password');
		 $device_idd =  $this->input->post('device_idd');
		  
        $response = array();
        if($_POST) {
            
    		$email = $this->security->xss_clean($_POST["email"]);
    		$password =  $this->security->xss_clean($_POST["password"]);
    		
    		$login_status = $this->validate_login($email, $password,$device_idd);
            
            if($this->auth_array['code'] == 500){
                
                $response['code'] = $this->auth_array['code'];
                $response['error_message'] = $this->auth_array['error_message'];
                
            } else 
            if($this->auth_array['code'] == 200) {
                
                foreach ($this->auth_array as $key => $value) {
                    $response[$key] = $value;
                }
                
            }
               
        } else {
            
            $response['code'] = 500;
            $response['error_message'] = 'Direct Access Not Allowed!';
            
        }
        
        $response = json_encode($response);
        echo $response;
		
	}
	
	function validate_login($email='',$password	='',$device_idd) {
	    
        $pass  = passwordHash($password);
        $query = $this->db->where('email', $email)->where('password',$pass)->get($this->system_db.'.user_login');
        
		if ($query->num_rows() > 0) {
			$row = $query->row();
			if ($row->status == 1) {

				$this->auth_array['system_db']           =  $this->system_db;
				$this->auth_array['user_login_id']       =  $row->user_login_id;
				$this->auth_array['user_login_name']     =  $row->email;
				$this->auth_array['user_email']          =  $row->email;
				$this->auth_array['name']                =  $row->name;
				//$this->auth_array['staff_id']            =  $row->staff_id;
				$this->auth_array['user_profile_pic']    =  $row->profile_pic;
				$this->auth_array['user_profile_name']   =  $row->name;
				$this->auth_array['user_language']       =  $row->language;
				$this->auth_array['multiple_accounts']   =  0;
				
				$login_detail_arr = $this->db->get_where($this->system_db.'.user_login_details', array('user_login_id' => $row->user_login_id, 'status' => 1 ))->result_array();
				$sys_schid = $login_detail_arr[0]['sys_sch_id'];
			    $login_type = $login_detail_arr[0]['login_type'];
				$log_detail_id = $login_detail_arr[0]['user_login_detail_id'];
				
				
				
				$get_school_db_for_storing_device_id = $this->db->query("select school_db FROM ".$this->system_db.".system_school WHERE sys_sch_id = '".$sys_schid."' AND status = 1")->row();
			
				
				$school_db_for_storing_device_id = $get_school_db_for_storing_device_id->school_db;
				
				$data = array(
                'mobile_device' => $device_idd,
                'user_login_id' => $row->user_login_id,
                'islogin' => '1',
                
                );
                $query  = "SELECT mobile_device FROM $school_db_for_storing_device_id.mobile_device_id where user_login_id = ".$row->user_login_id." ";

                $result = $this->db->query($query)->row();
                if($result)
                {
                    if($result->mobile_device == $device_idd)
                    {
                        $this->db->set('islogin', 1);
                        $this->db->where('user_login_id', $row->user_login_id);
                        $this->db->update($school_db_for_storing_device_id.'.mobile_device_id'); 
                    }
                    else
                    {
                        $this->db->set('mobile_device', $device_idd);
                        $this->db->set('islogin', 1);
                        $this->db->where('user_login_id', $row->user_login_id);
                        $this->db->update($school_db_for_storing_device_id.'.mobile_device_id'); 
                    }
                }
                else{
                    $this->db->insert($school_db_for_storing_device_id.'.mobile_device_id', $data);
                }
            
        
			
				
				
				//Teacher Login Only Restriction
				if($login_type == '3') {
				    if (count($login_detail_arr) == 1) {
    					$this->single_accounts_login($sys_schid, $login_type, $log_detail_id);
    				} else
    				if (count($login_detail_arr) > 1) {
    				    // If multiple account
    				    $this->multiple_account_login($sys_schid, $login_type, $log_detail_id);
    				}
				    
				} else {
				    
				    $this->auth_array['code'] = 500;
			        $this->auth_array['error_message'] = 'invalid';
				        
				}
				
			} else {
			    
			    $this->auth_array['code'] = 500;
			    $this->auth_array['error_message'] = 'inactive';
			       
			}
			
		} else {   
		    
		    $this->external_std_login($email,$pass);
		    $this->auth_array['code'] = 500;
		    $this->auth_array['error_message'] = 'inactive';
			
		}
		
    }
	
	function single_accounts_login($sys_schid,$login_type,$log_detail_id) {
	    
        $school_db_where   = "";    
		$system_school_arr = $this->db->query("select * FROM ".$this->system_db.".system_school WHERE sys_sch_id = '".$sys_schid."' AND status = 1")->result_array();
	    
		if (count($system_school_arr) > 0) {

		    $restrict_login = $system_school_arr[0]['restrict_login'];
		    if($restrict_login == 1) {
		        
		        $this->auth_array['code'] = 500;
		        $this->auth_array['error_message'] = 'error_restrict_login';
		        
		    } else {
		       
		       if($this->dbutil->database_exists($system_school_arr[0]['school_db'])) {
		           
    		        $school_arr = $this->db->query("select * from ".$system_school_arr[0]['school_db'].".school WHERE sys_sch_id= ".$sys_schid." ")->result_array();
    		        
    				if(count($school_arr) > 0) {
    			
    					if((empty($this->auth_array['sub_domain'])) || ($this->auth_array['sub_domain'] == $system_school_arr[0]['sub_domain']) && !empty($system_school_arr[0]['school_db'])) {
    					    
    						$this->auth_array['user_login']        =  1;
    						$this->auth_array['sys_sch_id']        =  $sys_schid;
    					//	$this->auth_array['parent_sys_sch_id'] =  $system_school_arr[0]['parent_sys_sch_id'];
    						$this->auth_array['school_db']         =  $system_school_arr[0]['school_db'];
    						$this->auth_array['package_id']        =  $system_school_arr[0]['package_id'];
    						$this->auth_array['school_id']         =  $school_arr[0]['school_id'];
    						$this->auth_array['folder_name']         =  $school_arr[0]['folder_name'];
    						$this->auth_array['sub_domain']        =  $system_school_arr[0]['sub_domain'];
    						$this->auth_array['landing_page']      =  $system_school_arr[0]['landing_page'];
    						$acad_year_arr                 =  $this->db->query("select academic_year_id FROM ".$this->auth_array['school_db'].".acadmic_year WHERE school_id = ".$this->auth_array['school_id']." AND is_closed = 0 and status = 2 LIMIT 1 ")->result_array();
    						$yearly_term_arr               =  $this->db->query("select yearly_terms_id FROM ".$this->auth_array['school_db'].".yearly_terms WHERE school_id = ".$this->auth_array['school_id']." AND status = 2 and is_closed = 0 LIMIT 1")->result_array();
    						$this->auth_array['academic_year_id']  =  intval($acad_year_arr[0]['academic_year_id']);
    						$this->auth_array['yearly_term_id']    =  intval($yearly_term_arr[0]['yearly_terms_id']);
    						$this->auth_array['staff_id']          =  $this->get_login_teacher_id($log_detail_id);
    						$academic_year_details = $this->get_academic_year_dates($this->auth_array['academic_year_id']);
    						$this->auth_array['session_start_date']    =  $academic_year_details->start_date;
						    $this->auth_array['session_end_date']      =  $academic_year_details->end_date;
    						
    						$this->auth_array['login_type']        =  $login_type;
    						$redirect_controller           =  get_login_type_controller($login_type);
    						$this->auth_array['login_detail_id']   =  $log_detail_id;
    						$function_name                 =  get_login_type_function_name($login_type);
    					    $this->auth_array['user_id']           =  $this->$function_name($this->auth_array['login_detail_id']);
    						$this->auth_array['admin_rights']      =  $this->package_rights();
    						$this->set_user_session_data($this->auth_array['login_detail_id'],$this->auth_array['school_id']);
                            
				    
            				if($this->auth_array['code'] != '200') {
    						    
    						    $this->auth_array['code'] = 500;
    						    $this->auth_array['error_message'] = get_phrase('session_cannot_be_initiallized');
    						    
    						}
    						
    					} else {
    					    
    						$this->auth_array['code'] = 500;
    						$this->auth_array['error_message'] = 'inactive';
    						
    					}
    					
    				} else {
    				    
    				    $this->auth_array['code'] = 500;
    				    $this->auth_array['error_message'] = 'inactive';
    					
    				}
    				
		       } else {
		            
		            $this->auth_array['code'] = 500;
		            $this->auth_array['error_message'] = 'inactive';
		            
		       }
		       
		    } //Restrict login end
		    
		} else {
		    
		    $this->auth_array['code'] = 500;
		    $this->auth_array['error_message'] = 'inactive';
			
		}
		
    }
	
	function multiple_account_login($sys_schid,$login_type,$log_detail_id) {
	    
        $this->auth_array['multiple_accounts'] = 1;
		$system_school_arr = $this->db->query("select * FROM ".$this->system_db.".system_school WHERE sys_sch_id=".$sys_schid." AND status = 1")->result_array();
	    
	    // 	Check School Exist Or Not
		if (count($system_school_arr) > 0) {
		    
		    //Restrict login start
		    $restrict_login = $system_school_arr[0]['restrict_login'];
		    
		    if($restrict_login == 1) {
		        
		        $this->auth_array['code'] = 500;
		        $this->auth_array['error_message'] = 'error_restrict_login';
		        
		    } else {
		        
		        $school_arr = $this->db->query("select * from ".$system_school_arr[0]['school_db'].".school WHERE sys_sch_id=".$sys_schid." ")->result_array();

				if(count($school_arr) > 0) {
				    
					if((empty($this->auth_array['sub_domain'])) || (($this->auth_array['sub_domain'] == $system_school_arr[0]['sub_domain']) && ($this->auth_array['school_db'] == $system_school_arr[0]['school_db']))) {
					    
						$this->auth_array['user_login']        =  1;
						$this->auth_array['sys_sch_id']        =  $sys_schid;
						$this->auth_array['parent_sys_sch_id'] =  $system_school_arr[0]['parent_sys_sch_id'];
						$this->auth_array['school_db']         =  $system_school_arr[0]['school_db'];
						$this->auth_array['package_id']        =  $system_school_arr[0]['package_id'];
						$this->auth_array['school_id']         =  $school_arr[0]['school_id'];
						$this->auth_array['sub_domain']        =  $system_school_arr[0]['sub_domain'];
						$this->auth_array['landing_page']      =  $system_school_arr[0]['landing_page'];

						$acad_year_arr                 =  $this->db->query("select academic_year_id FROM ".$this->auth_array['school_db'].".acadmic_year WHERE school_id = ".$this->auth_array['school_id']." AND is_closed = 0 and status = 2 LIMIT 1")->result_array();
						$yearly_term_arr               =  $this->db->query("select yearly_terms_id FROM ".$this->auth_array['school_db'].".yearly_terms WHERE school_id = ".$this->auth_array['school_id']." AND status = 2 and is_closed = 0 LIMIT 1")->result_array();

						$this->auth_array['academic_year_id']  =  intval($acad_year_arr[0]['academic_year_id']);
						$this->auth_array['yearly_term_id']    =  intval($yearly_term_arr[0]['yearly_terms_id']);
						$academic_year_details = $this->get_academic_year_dates($this->auth_array['academic_year_id']);
						$this->auth_array['session_start_date']    =  $academic_year_details->start_date;
						$this->auth_array['session_end_date']      =  $academic_year_details->end_date;
						
						$this->auth_array['login_type']        =  $login_type;
						$redirect_controller           =  get_login_type_controller($login_type);
                        
						$this->auth_array['login_detail_id']   =  $log_detail_id;
						$function_name                 =  get_login_type_function_name($login_type);
						$this->auth_array['admin_rights']      =  $this->package_rights();
						
				        // Api Call To Check 
						$this->set_user_session_data($this->auth_array['login_detail_id'],$this->auth_array['school_id']);
						
						if($login_detail_arr[0]['login_type'] == 1) {
						    
						    $this->auth_array['user_id'] = $log_detail_id;
						    
						} else {
						    
					        $this->auth_array['user_id'] = $this->$function_name($this->auth_array['login_detail_id']);
					        
						}
						
						if($this->auth_array['code'] != '200'){
						    
						    $this->auth_array['code'] = 500;
    						$this->auth_array['error_message'] = get_phrase('session_cannot_be_initiallized');
    						$this->auth_array['test'] = "456";
						    
						}
						
					} else {  
					    
						$this->auth_array['code'] = 500;
						$this->auth_array['error_message'] = 'inactive';
						
					}
					
				} else {
				    
					$this->auth_array['code'] = 500;
					$this->auth_array['error_message'] = 'inactive';
					
				}
		        
		    } //Restrict login end
		    
		} else {
		    
			$this->auth_array['code'] = 500;
			$this->auth_array['error_message'] = 'inactive';
			
		}
		
    }
	
	function set_user_session_data($user_login_detail_id,$school_id) {
	    $device = "";
	    $token = md5("Y&*T&T&*FGF*&^THG*&".$user_login_detail_id.$school_id.rand(10,1000));
	    $last_login = date('Y-m-d h:i:s a');
        $check_record_exist = $this->db->where('user_login_detail_id',$user_login_detail_id)->where('school_id',$school_id)->get($this->system_db.'.user_session');
	    if($check_record_exist->num_rows() == 0){
	        $this->db->query("INSERT INTO ".$this->system_db.".user_session (`user_login_detail_id`, `school_id`, `token`, `last_login`, `device`) VALUES('$user_login_detail_id','$school_id','$token','$last_login','$device') ");
        	$this->auth_array['token'] = $token;
            $this->auth_array['code'] = 200;
	    }else{
	        $this->db->query("UPDATE ".$this->system_db.".user_session SET token = '$token',last_login = '$last_login', device = '$device' WHERE user_login_detail_id = '$user_login_detail_id' AND school_id = '$school_id' ");
	        $this->auth_array['token'] =  $token;
            $this->auth_array['code'] = 200;
	    }
    	    
    	    
        
	}
    
    function external_std_login($email,$password) {
        
	    $credentialStudent = array('id_no' => $email , 'password' => $password);
	    $queryStudent      = $this->db->get_where($this->system_db.'.user_login' , $credentialStudent);
	    
		if ($queryStudent->num_rows() > 0) {
		    
			$row = $queryStudent->row();
			$queryStudentcount = $this->db->get_where($this->system_db.'.user_login_details', array('user_login_id' => $row->user_login_id, 'status' => 1 , 'login_type' => 6 ))->result_array();

			if(count($queryStudentcount) == 0) {
			    $this->auth_array['code'] = 500;
			    $this->auth_array['error_message'] = 'inactive';
			    //redirect(base_url()); 
			}
			
			if ($row->status == 1) {	
			    
				$this->auth_array['system_db']         =  $this->system_db;
				$this->auth_array['user_login_id']     =  $row->user_login_id;
				$this->auth_array['user_login_name']   =  $row->email;
				$this->auth_array['user_email']        =  $row->email;
				$this->auth_array['name']              =  $row->name;
				$this->auth_array['user_profile_pic']  =  $row->profile_pic;
				$this->auth_array['user_profile_name'] =  $row->name;
				$this->auth_array['user_language']     =  $row->language;
				$this->auth_array['multiple_accounts'] =  0;
				$this->auth_array['NIC']               =  $row->id_no;
				$login_detail_arr              =  $this->db->get_where($this->system_db.'.user_login_details', array('user_login_id' => $row->user_login_id, 'status' => 1 ))->result_array();

				if (count($login_detail_arr) == 1) {
					$school_db_where   = "";
					$system_school_arr = $this->db->query("select * FROM ".$this->system_db.".system_school WHERE sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." AND status=1")->result_array();
					
					//Restrict login start case 2
					$restrict_login = $system_school_arr[0]['restrict_login'];
					if($restrict_login == 1) {
					   
                        $this->auth_array['code'] = 500;
                        $this->auth_array['error_message'] = 'error_restrict_login';
					   
					} else {
					    
					    if (count($system_school_arr) > 0) {
					        
    						$school_arr = $this->db->query("select * from ".$system_school_arr[0]['school_db'].".school WHERE sys_sch_id=".$login_detail_arr[0]['sys_sch_id']." 
    							")->result_array();				
    						if(count($school_arr) > 0) {
    						    
    							if((empty($this->auth_array['sub_domain'])) || (($this->auth_array['sub_domain'] == $system_school_arr[0]['sub_domain']) && ($this->auth_array['school_db']==$system_school_arr[0]['school_db']))) {
    							    
    								$this->auth_array['user_login']        =  1;
    								$this->auth_array['sys_sch_id']        =  $login_detail_arr[0]['sys_sch_id'];
    								$this->auth_array['parent_sys_sch_id'] =  $system_school_arr[0]['parent_sys_sch_id'];
    								$this->auth_array['school_db']         =  $system_school_arr[0]['school_db'];
    								$this->auth_array['package_id']        =  $system_school_arr[0]['package_id'];
    								$this->auth_array['school_id']         =  $school_arr[0]['school_id'];
    								$this->auth_array['sub_domain']        =  $system_school_arr[0]['sub_domain'];
    								$this->auth_array['landing_page']      =  $system_school_arr[0]['landing_page'];
    
    								$acad_year_arr                 =  $this->db->query("select academic_year_id FROM ".$this->auth_array['school_db'].".acadmic_year WHERE school_id = ".$this->auth_array['school_id']." AND is_closed = 0 and status = 2 LIMIT 1")->result_array();
    								$yearly_term_arr               =  $this->db->query("select yearly_terms_id FROM ".$this->auth_array['school_db'].".yearly_terms WHERE school_id = ".$this->auth_array['school_id']." AND status = 2 and is_closed = 0 LIMIT 1 ")->result_array();
                                    
    								$this->auth_array['academic_year_id']  =  intval($acad_year_arr[0]['academic_year_id']);
    								$this->auth_array['yearly_term_id']    =  intval($yearly_term_arr[0]['yearly_terms_id']);
    								$this->auth_array['login_type']        =  $login_detail_arr[0]['login_type'];
    								$this->auth_array['login_detail_id']   =  $login_detail_arr[0]['user_login_detail_id'];
    								$function_name                 =  get_login_type_function_name($login_detail_arr[0]['login_type']);
    								$this->auth_array['user_id']           =  6;
    								$this->set_user_session_data($this->auth_array['login_detail_id'],$this->auth_array['school_id']);

            						if($this->auth_array['code'] != '200'){
            						    
            						    $this->auth_array['code'] = 500;
		                                $this->auth_array['error_message'] = get_phrase('session_cannot_be_initiallized');
            						    
    						            $this->auth_array['test'] = "789";
            						}
                                    
    							} else {
    							    
    							    $this->auth_array['code'] = 500;
    								$this->auth_array['error_message'] = 'inactive';
    								
    							}
    							
    						} else {
    						    
    						    $this->auth_array['code'] = 500;
    							$this->auth_array['error_message'] = 'inactive';
    							
    						}
    						
    					} else {
    					    
    					    $this->auth_array['code'] = 500;
    						$this->auth_array['error_message'] = 'inactive';
    						
    					}
					
					}
					//Restrict login end case 2
					
				} else
				if (count($login_detail_arr) > 1) {
				    
				    // If multiple account 
					$this->auth_array['multiple_accounts'] = 1;
					
				} else {
				    
				    // inactive record
				    $this->auth_array['code'] = 500;
					$this->auth_array['error_message'] = 'inactive';
					
				}
				
			} else {
			 
			    $this->auth_array['code'] = 500;
			    $this->auth_array['error_message'] = 'inactive';
			   
			}
			
		} else {
		    
		    $this->auth_array['code'] = 500;
		    $this->auth_array['error_message'] = 'invalid';
			
		}
}

    //forgot password
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
    		        echo json_encode(array('code'=>'200', 'message'=> '5 digit code has been sent on your email, Please check email and verify email'));
    	
    		    }
    		    else{
    		        $data = array(
    		            'email'      => $email,
    		            'secret_key' => md5($email),
    		            'code'       => $rand   
    		        );
    		        $this->db->insert($this->system_db.".forgot_verification_code", $data);   
                    echo json_encode(array('code'=>'200', 'message'=> '5 digit code has been sent on your email, Please check email and verify email'));
    		    }
            }
            else{
                echo json_encode(array('code'=>'201', 'message'=> 'Email could not be sent'));
            }
        }
        else{
            echo json_encode(array('code'=>'500', 'message'=> 'Email not exist'));
        }

    }

    //verify OTP
    public function verify_code(){
        $email = $this->input->post('email');
        $code = $this->input->post('code');
        $credential	=	array(	'email' => $email, 'code'=>$code);
		$query = $this->db->get_where($this->system_db.'.forgot_verification_code' , $credential);
        if ($query->num_rows() > 0){
            $query = $query->result_array();
            echo json_encode(array('code'=>200, 'message'=> 'Code match', 'secret_key'=>$query[0]['secret_key']));
        }
        else{
            echo json_encode(array('code'=>500, 'message'=> 'Code not match'));
        }
    }
    
    //update password
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
			echo json_encode(array('code'=>200, 'message'=> 'Password Changed'));
// 			$this->session->set_flashdata('club_updated', get_phrase('password_updated_successfully'));
//             redirect(base_url().'login');
        }
        else
        {
             echo json_encode(array('code'=>500, 'message'=> 'Failed to change password'));
        }
    }





    		/**********MANAGING CLASS ROUTINE******************/

    function my_class_routine()
    {
        $response=array();
        $subject=array();
        $section=array();
        $start_end=array();
        $class_time=array();
        $duration=array();
        
        
        $school_db = $this->input->post('school_db');
	    $school_id = $this->input->post('school_id');
	    $login_detail_id = $this->input->post('login_detail_id');
	    
	    $routine1=array();
        $result = $this->db->query("select s.subject_id, s.name, s.code, cr.class_routine_id, cr.day, cr.period_no, d.title as department, class.name as class, cs.title as class_section,cr.duration, crs.period_duration,cr.period_start_time,cr.period_end_time
    	from ".$school_db.".subject s
    	inner join ". $school_db.".subject_teacher st on s.subject_id =  st.subject_id
    	inner join ". $school_db.".staff staff on staff.staff_id =  st.teacher_id
    	inner join ". $school_db.".time_table_subject_teacher ttst on st.subject_teacher_id =  ttst.subject_teacher_id
    	inner join ". $school_db.".class_routine cr on cr.class_routine_id =  ttst.class_routine_id
    	inner join ". $school_db.".class_routine_settings crs on (crs.c_rout_sett_id =  cr.c_rout_sett_id and crs.is_active = 1)
    
    	inner join ". $school_db.".subject_section ss on ss.subject_id = st.subject_id
    	inner join ". $school_db.".class_section cs on cs.section_id = crs.section_id
    	inner join ". $school_db.".class on class.class_id = cs.class_id
    	inner join ". $school_db.".departments d on d.departments_id = class.departments_id
    
    	where 
    	staff.user_login_detail_id = $login_detail_id
    	and crs.school_id = ".$school_id." 
    	group by day, period_no
    	order by day, period_no
    	")->result_array();
    	
    	
    	$pp="SELECT st.*,st.date as date, s.name as teacher_name,s.staff_id as teacher_id,subj.name as subject_name,subj.code as code,cs.class_id as class_id, cs.title as section_name,class.name as class_name,d.title as dept_name 
    	FROM ".$school_db.".substitute_teacher st 
    	INNER JOIN ".$school_db.".staff s
    	ON st.staff_id=s.staff_id
    	INNER JOIN ".$school_db.".subject subj
    	ON st.subject_id=subj.subject_id
    	INNER JOIN ".$school_db.".class_section cs
    	ON st.section_id=cs.section_id
    	INNER JOIN ".$school_db.".class 
    	ON class.class_id = cs.class_id
    	INNER JOIN ".$school_db.".departments d on d.departments_id = class.departments_id
    	 WHERE st.school_id=".$school_id."  AND s.user_login_detail_id	=".$login_detail_id." "; 
        $subs_array=$this->db->query($pp)->result_array();
        $current_date=date("l");
        $asign_array=array();
        $period_no_asign=array(0);
        if(count($subs_array) > 0)
        {
        	foreach($subs_array as $asign)
        	{
        		$asign_array[$asign['date']][$asign['period_no']]=array(
        		'subject_name'=>$asign['subject_name'].' - '.$asign['code'],
        		'class'=>$asign['class_name'].' - '.$asign['section_name']
        		);
        	
        		$period_no_asign[$asign['period_no']] =$asign['period_no'];
        	}
        }

       				
        $period_no = array();
        $duration_arr=array();
        $period_max_new=array();
        $days=array();
        
        $per_day=1;
        //print_r($result);
        if(sizeof($result)>0)
        {
            $temp='';
            $tt=1;
        	foreach ($result as $key => $row) 
        	{
        	    $ctemp=$row['day'];
        	    if($temp==''){
        	        $temp=$row['day'];
        	    }
        	    if($ctemp!=$temp){
        	        $tt=1;
        	        $temp=$row['day'];
        	    }
        	   
        	    $virtual_id[$row['day']][$row['period_no']] = $row['subject_id'].'/'.$row['class_routine_id'];
        		$timetable_arr[$row['day']][$tt] = $row['name'].' - '.$row['code'];	
        		$timetable_arr[$row['day']]['day'] = $row['day'];
        		$dcs_arr[$row['day']][$tt] =$row['class'].' - '.$row['class_section'];	
        		$period_no[$tt] =$row['period_no'];
        		$duration_arr[$row['day']][$tt]['duration']=$row['duration'];
        		$duration_arr[$row['day']][$tt]['default_period_duration']=$row['period_duration'];
        		$period_start_time=$row['period_start_time'];
        		$period_start_time= substr($period_start_time, 0, -3);
        		$period_end_time=$row['period_end_time'];
        		$period_end_time= substr($period_end_time, 0, -3);
        		$duration_arr[$row['day']][$tt]['period_start_time']=$period_start_time;
        		$duration_arr[$row['day']][$tt]['period_end_time']=$period_end_time;
        	    $tt++;
        	}
        	                         
            
    // 		print_r($timetable_arr);exit;
            $period_max = max($period_no);	
            $period_max_asign = max($period_no_asign);
            if($period_max > $period_max_asign)
            {
				$period_max_new=$period_max;
			}
			else
			{
				$period_max_new=$period_max_asign;
			}
        
		
			for ($i = 1; $i<=$period_max_new; $i++ )
    		{
    		  //  echo $i; 
    		  //  echo "<br>here<br>"; 
    		}
   
            foreach ($timetable_arr as $key => $value) 
            {
            	
            	$weekend = "";
            
				
				if($key == 'saturday' || $key ==  'sunday')
				{
				    $weekend ="weekend";
				}
				
				$dd=date("Y-m-d",strtotime($key.' this week'));
				$q1="select * from ".$school_db.".holiday where start_date<='$dd' and end_date>='$dd' AND school_id=".$school_id." ";    
				$qurrr=$this->db->query($q1)->result_array();
			

			
        		 $kk=1;
        		for ($i = 1; $i<=$period_max_new; $i++ )
        		{
            		if(isset($value[$i])){
            		     $subject[$per_day][$kk]=$value[$i]; 
            		     $subject[$per_day]['day']= $value['day']; 
            		}
            		
            		if(isset($dcs_arr[$key][$i])){
            		     $section[$per_day][$kk]=$dcs_arr[$key][$i];
            		}
    	                    		
    	            if(isset($duration_arr[$key][$i]['period_start_time']) && ($duration_arr[$key][$i]['period_start_time'])>0)
    				{
    					$start_end[$per_day][$kk]= date("H:i", (strtotime($duration_arr[$key][$i]['period_start_time'])))." - ". date("H:i", (strtotime($duration_arr[$key][$i]['period_end_time'])));
    					$class_time[$per_day][$kk]= date("h:i", (strtotime($duration_arr[$key][$i]['period_start_time'])))." - ". date("h:i", (strtotime($duration_arr[$key][$i]['period_end_time'])));
    				}      		
    				if(isset($duration_arr[$key][$i]['duration']) && ($duration_arr[$key][$i]['duration'])>0)
    				{
    					$duration[$per_day][$kk]= " (".$duration_arr[$key][$i]['duration']." ".get_phrase('mins').")";
    				}
    				elseif(isset($duration_arr[$key][$i]['default_period_duration']) && ($duration_arr[$key][$i]['default_period_duration'])>0)
    				{
    					$duration[$per_day][$kk]= " (".$duration_arr[$key][$i]['duration']." ".get_phrase('mins').")";					
    				} 
    				
    		
    				if(isset($duration_arr[$key][$i]["period_start_time"])){
    				    $start_t_1 = $duration_arr[$key][$i]["period_start_time"];
    				   
    				}
    				
    				if(isset($duration_arr[$key][$i]["period_end_time"])){
    				    $end_t_1 = $duration_arr[$key][$i]["period_end_time"];
    				}
    				
    				if(isset($duration_arr[$key][$i]['default_period_duration'])){
    				    	$duration_1 = $duration_arr[$key][$i]['default_period_duration'];
    				}
    				
    				$current_t_1 = date("H:i", strtotime("now")); //13:00
    				
    				
    				
    		
            		if(isset($asign_array[$dd][$i]))
            		{
            			
                    //  echo get_phrase("assigned_subject");
                     //	echo "<br>";
    				// 	echo $asign_array[$dd][$i]['subject_name'];
    				// 	echo "<br>";
    				// 	echo $asign_array[$dd][$i]['class'];
    				
    					
    				}
    				$kk++;
    	          
        		}
        		$per_day++;
        	
        		
            }
            
         $response['code'] = '200';
		 $response['subject'] = $subject;
		 $response['section'] = $section;
		 $response['start_end'] = $start_end;
		 $response['class_start_end'] = $class_time;
		 $response['duration'] = $duration;
        		
         }else{
             $response['code'] = '201';
             $response['message'] = 'Time Table is Not Created Yet';
             
         } 
        echo json_encode($response);
    }

    /**********MANAGING NOTICEBOARD******************/
    function noticeboard()
	{
	    $response = array();
        $school_id =  $this->input->post('school_id');
    	$school_db =  $this->input->post('school_db');
    	$limit =  $this->input->post('limit');
    	$page =  $this->input->post('page');
        $offset = ($page - 1) * $limit;
		
		$q="select n.notice_id as notice_id,n.notice_title as notice_title,n.notice as notice,n.create_timestamp as create_timestamp
		    FROM ".$school_db.".noticeboard n WHERE n.school_id=".$school_id." ";
		$notice_count = $this->db->query($q)->result_array();   
		$quer_limit   = $q . " limit ".$offset."," . $limit . "";
        $notice       = $this->db->query($quer_limit)->result_array();
		 
		 if($notice)
        {
            $response['code']= '200';
            $response['notices'] = $notice;
        }
        else{
            $response['code']= '500';
            $response['error_message']= 'No notices found!';
        }
        echo json_encode($response);
	}
	
    /**********MANAGING STAFF CIRCULARS******************/
    function staff_circular()
	{
	    $response=array();
		$circular_qur="";
		$date_query="";
		
		$school_db = $this->input->post('school_db');
	    $school_id = $this->input->post('school_id');
	    $limit =  $this->input->post('limit');
		$page =  $this->input->post('page');
		$offset = ($page - 1) * $limit;
	    
		$circular_select = $this->input->post('circular_select');
        $user_id= $this->input->post('staff_id');
        $circular_qur=" AND (cs.staff_id=".$user_id." OR cs.staff_id=0)";
        if($circular_select=='all_circulars')
        {
			$page_data['filter'] = true;
			$page_data['circular_val'] = $circular_select;
		}
		if($circular_select=='my_circulars')
		{
			$circular_qur=" AND cs.staff_id=".$user_id."";
			$page_data['filter'] = true;
			$page_data['circular_val'] = $circular_select;
			
		}
		if($circular_select=='general_circulars')
		{
			$circular_qur=" AND cs.staff_id=0";
			$page_data['filter'] = true;
			$page_data['circular_val'] = $circular_select;
		}
		
        $start_date=$this->input->post('starting');
        $end_date=$this->input->post('ending');
        if($start_date!='')
        {
        	$start_date_arr=explode("/",$start_date);
        	$start_date=$start_date_arr[2].'-'.$start_date_arr[1].'-'.		$start_date_arr[0];
        }
        if($end_date!='')
        {
        	$end_date_arr=explode("/",$end_date);
        	$end_date=$end_date_arr[2].'-'.$end_date_arr[1].'-'.$end_date_arr[0];
        }
        if($start_date!='')
        {
        	$date_query=" AND cs.circular_date >= '".$start_date."'";
        	$page_data['start_date']=$start_date;
        	$page_data['filter'] = true;
        }
        if($end_date!='')
        {
        	$date_query=" AND cs.circular_date <= '".$end_date."'";
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        }
        if($start_date!='' && $end_date!='')
        {
        	$date_query=" AND cs.circular_date >= '".$start_date."' AND cs.circular_date <= '".$end_date."' ";
        	$page_data['start_date']=$start_date;
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        }
		
		$q="SELECT cs.*,st.name as staff_name,st.employee_code as employee_code,d.title as designation FROM ".$school_db.".circular_staff cs
            Left JOIN ".$school_db.".staff st ON cs.staff_id=st.staff_id left JOIN ".$school_db.".designation d On st.designation_id=d.designation_id
            WHERE cs.school_id=".$school_id. $circular_qur . $date_query ." order by cs.circular_date desc limit ".$offset."," . $limit . " ";
		
		$query=$this->db->query($q)->result_array();
		if(count($query)>0){
		    $response['code']='200';
		    $response['staff_circulars']=$query;
		}else{
		    $response['code']='201';
		    $response['message']='no staf circulars found';
		}
		echo json_encode($response);
// 		print_r($query);exit;
	
	}
	
	    /***********MANAGE CIRCULARS***********/
    function circulars($param1 = '', $param2 = '', $param3 = '')
    {
        $response = array();
        $login_detail_id = $this->input->post('login_detail_id');
        $yearly_term_id = $this->input->post('yearly_term_id');
        
		$school_db = $this->input->post('school_db');
	    $school_id = $this->input->post('school_id');
	    

        $d_c_s_sec                    =  $this->mobile_get_teacher_dep_class_section($login_detail_id,$school_db,$school_id);
        $time_table_t_sec             =  $this->mobile_get_time_table_teacher_section($login_detail_id,$school_db,$school_id);
       
        $teacher_section              =  array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        
        $page_data['teacher_section'] =  $teacher_section;

        $in_section       =  0;
        $student_arr      =  "";
        $stud_sect_filter =  "";
        if (count($teacher_section) > 0)
        {
            $in_section = implode(',', $teacher_section);
        }

        $dep_c_s_id = intval($this->input->post('dep_c_s_id'));
        $student_select=$this->input->post('student_select');
        
		$start_date=$this->input->post('starting');
		$end_date=$this->input->post('ending');
        $filter = " and cs.section_id in ($in_section) ";
        
        if ( $dep_c_s_id > 0 ) 
        {
            $filter = " and cs.section_id = $dep_c_s_id ";
            $page_data['section_filter'] = $dep_c_s_id;
            $page_data['filter'] = true;
        }
        
		if($dep_c_s_id > 0 && $student_select > 0)
		{
			$page_data['filter'] = true;
			$page_data['student_id'] = $student_select;
			$stud_sect_filter= " and (cs.section_id = $dep_c_s_id and c.student_id = $student_select)";
		}
		
        if($start_date!='')
        {
        	$start_date_arr=explode("/",$start_date);
        	$start_date=$start_date_arr[2].'-'.$start_date_arr[1].'-'.$start_date_arr[0];
        }
        if($end_date!='')
        {
        	$end_date_arr=explode("/",$end_date);
        	$end_date=$end_date_arr[2].'-'.$end_date_arr[1].'-'.$end_date_arr[0];
        }

        $per_page     =  10;
		$apply_filter =  $this->input->post('apply_filter', TRUE);
		$std_search   =  $this->input->post('std_search', TRUE);
        $std_search   =  trim(str_replace(array("'", "\""), "", $std_search));
        
        if (!isset($start_date) || $start_date == "") {
            $start_date = $this->uri->segment(3);
        }

        if (!isset($start_date) || $start_date == "") {
            $start_date = 0;
        }
        
        if (!isset($end_date) || $end_date == "") {
            $end_date = $this->uri->segment(4);
        }

        if (!isset($end_date) || $end_date == "") {
            $end_date = 0;
        } 
        
        if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(5);
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = 0;
        } 
        
        if (!isset($student_select) || $student_select == "") {
            $student_select = $this->uri->segment(6);
        }

        if (!isset($student_select) || $student_select == "") {
            $student_select = 0;
        }
        
        $page_num = $this->uri->segment(7);
        if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }          

        if(($start_date!='') && ($start_date > 0))
        {
        	$date_query=" AND c.create_timestamp >= '".$start_date."'";
        	$page_data['start_date']=$start_date;
        	$page_data['filter'] = true;
        }
        if(($end_date!='') && ($end_date>0))
        {
        	$date_query=" AND c.create_timestamp <= '".$end_date."'";
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        	
        }
        $date_query="";
        if(($start_date!='') && ($start_date > 0) && ($end_date!='') && ($end_date > 0))
        {
        	$date_query=" AND c.create_timestamp >= '".$start_date."' AND c.create_timestamp <= '".$end_date."' ";
        	$page_data['start_date']=$start_date;
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        		
        }
        
        $std_query = "";
        if (isset($std_search) && !empty($std_search))
        {
        	$std_query = " AND (
                                    c.circular_title LIKE '%" . $std_search . "%' OR 
                                    c.circular LIKE '%" . $std_search . "%' 
                                )";
            $page_data['filter'] = true;
        }
        
        $q="select c.circular_id, c.circular_title, c.circular, c.section_id, c.student_id, c.create_timestamp, c.attachment, cs.title as class_section, d.title as department, class.name as class_name
            FROM ".$school_db.".circular c 
            inner join ".$school_db.".class_section cs on cs.section_id = c.section_id
            inner join ".$school_db.".class on class.class_id = cs.class_id
            inner join ".$school_db.".departments d on class.departments_id = d.departments_id
            where c.school_id=".$school_id." and c.is_active = 1". $filter . $student_arr. $stud_sect_filter. $date_query .$std_query."
            group by c.circular_id order by create_timestamp desc";
           
        $circular_count         =  $this->db->query($q)->result_array();
        $total_records          =  count($circular_count);
        $quer_limit             =  $q . " limit " . $start_limit . ", " . $per_page . "";
        $query                  =  $this->db->query($quer_limit)->result_array(); 
        
        $circulars=array();
        $student_id;
        foreach($circular_count as $key => $row)
        {
            $circulars[$key]['circular_title']=$row['circular_title'];
            $circulars[$key]['create_timestamp']=$row['create_timestamp'];
            $circulars[$key]['class_section']=$row['class_section'];
            $circulars[$key]['department']=$row['department'];
            $circulars[$key]['class_name']=$row['class_name'];
            $circulars[$key]['circular']=$row['circular'];
            
            $circulars[$key]['student_name']= $this->get_student_name($row['student_id'], $school_id,$school_db);
        }
        if(count($circulars)>0)
        {
            $response['code']='200';
            $response['circulars']=$circulars;
        }else{
            $response['code']='201';
            $response['message']='no circulars found';
        }
        
        echo json_encode($response);
    }


    function mobile_get_teacher_dep_class_section($login_detail_id=0,$school_db,$school_id)
    {
    
    	$section_ids = array();
    	$dept_arr = $this->db->query("select distinct cs.section_id 
    				from ".$school_db.".class_section cs 
    				inner join ".$school_db.".class c on cs.class_id = c.class_id
                    inner join ".$school_db.".departments d on d.departments_id = c.departments_id
    				inner join ".$school_db.".staff staff on staff.staff_id = d.department_head
    				where d.school_id=".$school_id."
    				and staff.user_login_detail_id = $login_detail_id 
    				")->result_array();
    	foreach ($dept_arr as $value) 
    	{
    		$section_ids[] =  $value['section_id'];
    	}
    	$class_arr = $this->db->query("select distinct cs.section_id 
    				from ".$school_db.".class_section cs 
                    inner join ".$school_db.".class c on cs.class_id = c.class_id
    				inner join ".$school_db.".staff staff on staff.staff_id = c.teacher_id
    				where c.school_id=".$school_id."
    				and staff.user_login_detail_id = $login_detail_id 
    				")->result_array();
    	foreach ($class_arr as $value) 
    	{
    		$section_ids[] =  $value['section_id'];
    	}
    
    	$section_arr = $this->db->query("select distinct cs.section_id 
    				from ".$school_db.".class_section cs 
                    inner join ".$school_db.".staff staff on staff.staff_id = cs.teacher_id
    				where cs.school_id=".$school_id."
    				and staff.user_login_detail_id = $login_detail_id 
    				")->result_array();
    	foreach ($section_arr as $value) 
    	{
    		$section_ids[] =  $value['section_id'];
    	}
    
    	return array_unique($section_ids);
    }
    
    function mobile_get_time_table_teacher_section($login_detail_id = 0,$school_db,$school_id)
    {
    	$teacher_arr = $this->db->query("select section_id FROM 
    		".$school_db.".class_routine cr 
                inner join ".$school_db.".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                inner join ".$school_db.".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
                inner join ".$school_db.".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                inner join ".$school_db.".staff staff on st.teacher_id=staff.staff_id
                where 
                staff.user_login_detail_id = $login_detail_id
                and cr.school_id=".$school_id."
                group by section_id
                ")->result_array();
    	        //echo $CI->db->last_query();
    	$section = array();
    
        if ( count($teacher_arr) > 0 )
        {
        	foreach ($teacher_arr as $value) 
        	{
        		$section[] = $value['section_id']; 
        	}
        }  
        return $section;  
    }
    
    function get_student_name($student_id, $school_id = 0,$school_db)
    {
        $q = "SELECT name FROM " . $school_db . ".student
    	WHERE student_id=$student_id AND school_id=" . $school_id . "";
        $student = $this->db->query($q)->result_array();
        $student_name="";
        if($student)
        {
            $student_name = $student[0]['name'];
        }
        return $student_name;
    }
    

    function get_all_sections()
    {
        $response=array();
        $login_detail_id = $this->input->post('login_detail_id');
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        
        $d_c_s_sec = $this->mobile_get_teacher_dep_class_section($login_detail_id,$school_db,$school_id);
        $time_table_t_sec = $this->mobile_get_time_table_teacher_section($login_detail_id,$school_db,$school_id);
        $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        $class_sections=$this->get_teacher_dep_class_section_list($teacher_section, $school_db,$school_id);
        if($class_sections){
            $response['code']='200';
            $response['class_sections']=$class_sections;
        }else{
            $response['code']='201';
            $response['msg']='no class sections found';
        }
        echo json_encode($response);
    }
    /***********MANAGE ALL CLASSES ROUTINE***********/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        $response=array();
        $login_detail_id = $this->input->post('login_detail_id');
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
   
        $section_id = intval($this->input->post('dep_c_s_id'));

        $custom_color=array(1=>'#29638d',2=>'#6eb6ea');
        $routine1=array();
        $q2 = "select cr.*,cs.*,d.title,d.departments_id,cls.name,cls.class_id,sec.title,sec.section_id FROM ".$school_db.".class_routine cr 
            RIGHT JOIN ".$school_db.".class_routine_settings cs on (cs.c_rout_sett_id=cr.c_rout_sett_id and cs.is_active = 1)
            INNER JOIN ".$school_db.".class_section sec on sec.section_id=cs.section_id 
            INNER JOIN ".$school_db.".class  cls on sec.class_id=cls.class_id 
            INNER JOIN ".$school_db.".departments  d on cls.departments_id=d.departments_id 
            WHERE 
            cs.school_id=".$school_id." 
            AND sec.section_id in (".$section_id.")
            ";

        $result=$this->db->query($q2)->result_array();
        if(sizeof($result)>0)
        {
                $temp='';
                $kk=1;
                $day=1;
    			foreach($result as $row)
    			{
        			$ctemp=$row['day'];
            	    if($temp==''){
            	        $temp=$row['day'];
            	    }
            	    if($ctemp!=$temp){
            	        $kk=1;
            	        $temp=$row['day'];
            	        $day++;
            	    }
            	    if($row['class_routine_id']>0){
            	        $compQuery=" select subject_components from ".$school_db.".class_routine where class_routine_id=".$row['class_routine_id']."";
    			        $compRes = $this->db->query($compQuery)->result_array();       
    			        $comps=$compRes[0]['subject_components'];
            		   $query3=" select sta.name AS teacher_name from ".$school_db.".time_table_subject_teacher ttst inner join ".$school_db.".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
                	    inner join ".$school_db.".staff sta on sta.staff_id=st.teacher_id inner join ".$school_db.".subject s on s.subject_id=st.subject_id where ttst.school_id=".$school_id."  
                	    and st.subject_id=".$row['subject_id']." and class_routine_id=".$row['class_routine_id']."";
        				$res = $this->db->query($query3)->result_array();
        				$subject=$this->get_subject_name($row['subject_id'],$school_db, $val = 0);
        				$scomp=$this->subject_components($comps,$school_db);
            	    }
            	    		
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['subject_id']                  =$row['subject_id'];
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['subject_name']                =$subject;
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['subject_comp']                =$scomp;
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['class_routine_id']            =$row['class_routine_id'];
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['duration']                    =$row['duration'];
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['period_start_time']           =date("h:i", (strtotime($row['period_start_time'])));
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['period_end_time']             =date("h:i", (strtotime($row['period_end_time'])));
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['start_end']                   =date("H:i", (strtotime($row['period_start_time'])))." - ".date("H:i", (strtotime($row['period_end_time'])));
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['teacher_name']                =$res[0]['teacher_name'];
    				$routine1[$row['c_rout_sett_id']][$row['section_id']][$day][$kk]['day']                         =$row['day'];
                    //$routine1[$row['c_rout_sett_id']][$row['section_id']]['default_period_duration']                =$row['period_duration'];
    			    $kk++;
    			}
    			
    
    			$toggle = true;
    			$settingsRes=$this->db->query(" select cs.*,d.title as department_name,cls.name as class_name, sec.title as section_name
    		 		from ".$school_db.".class_routine_settings cs 
    		 		inner join ".$school_db.".class_section  sec on sec.section_id=cs.section_id 
    		 		inner join ".$school_db.".class  cls on sec.class_id=cls.class_id 
    		 		inner join ".$school_db.".departments  d on cls.departments_id=d.departments_id 
    		 		WHERE cs.school_id=".$school_id." 
    		 		and sec.section_id in (".$section_id.")
    		 		and cs.is_active = 1
    		 		ORDER BY department_name,class_name,section_name ")->result_array();
    
    			$cnt = 0;
    
    		    foreach($settingsRes as $row)
    			{
                    $response['code']='200';
                    $response['class_timetable']=$routine1[$row['c_rout_sett_id']][$row['section_id']];
    			}
    			
    
        				
    	 }
    	else
    	{
    		$response['code']='201';
            $response['msg']='no timetable found';
    	}  
    	
    	 echo json_encode($response);
    }
    
    function get_subject_name($subject_id,$sch_db = "", $val = 0)
    {
          
        $q = "select name,code from " .$sch_db. ".subject where subject_id=" . $subject_id . "";
       
        $subject = $this->db->query($q)->result_array();
    
        $subject_name = "";
    
        if (count($subject) > 0) {
            $subject_name = $subject[0]['name'] . ' - ' . $subject[0]['code'];
    
            if ($val == 1) {
                $subject_name = $subject[0];
            }
        }
        return $subject_name;
    }
    
    function subject_components($components=0,$school_db)
    {
        $res=array();
        if($components!='')
        {
            $res=array();
            $names=$this->db->query("select title from ".$school_db.".subject_components where subject_component_id in(".$components.")")->result_array();
    
            foreach($names as $name)
            {
                $res[]=$name['title'];
            }
    
        }
        return implode('<br/>',$res); 
    }

    function get_teacher_dep_class_section_list($section_ids=array(),$school_db,$school_id)
    {
    
    	$ids = 0;
    	if (count($section_ids)>0)
    		$ids = implode(',', array_unique($section_ids));
    	
    	$dept_arr = $this->db->query("select d.departments_id, d.title as department,c.name as class_name, sec.title as section_name
    				from ".$school_db.".class_section sec 
    				inner join ".$school_db.".class c on c.class_id=sec.class_id 
    				inner join ".$school_db.".departments d on d.departments_id = c.departments_id 
    				where sec.school_id=".$school_id." 
    				and sec.section_id IN ($ids) 
    				group by d.departments_id
    				")->result_array();
    	$clss_section=array();
    	foreach($dept_arr as $d)
    	{
    	
    		$section = $this->db->query("SELECT c.name as class,c.class_id ,sec.title as section,sec.section_id 
    			FROM ".$school_db.".class_section sec 
    			INNER JOIN ".$school_db.".class c on c.class_id=sec.class_id 
    			WHERE c.school_id=".$school_id." 
    			AND sec.section_id IN ($ids) 
    			AND c.departments_id=".$d['departments_id']."
    			")->result_array();
    		
    		foreach($section as $key =>$sec)
    		{	
    		    $clss_section[$key]['section_id']=$sec['section_id'];
    		    $clss_section[$key]['class_section']=$sec['class'].' - '.$sec['section'];				  
    		}
    	}
    	
    	return $clss_section;
    }
    
     /***********MANAGE ALL DAILY ATTENDANCE***********/
    function manage_attendance_student($date='',$month='',$year='',$class_id='')
    {
        $response=array();
        $login_detail_id = $this->input->post('login_detail_id');
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $academic_year_id = $this->input->post('academic_year_id');
        
        $acadmic_year_start = $this->db->query("select start_date  from ".$school_db.".acadmic_year  where  academic_year_id =".$academic_year_id."  and school_id=".$school_id."  ")->result_array();
        $month_year_data= $this->month_year_option($acadmic_year_start[0]['start_date'], date('Y-m-d'));
        
        
        $section_arr = $this->db->query("select cs.section_id, cs.title as section, c.name as class, d.title as department  
                from ".$school_db.".class_section cs
                inner join ".$school_db.".staff staff on staff.staff_id = cs.teacher_id
                inner join ".$school_db.".class c on c.class_id = cs.class_id
                inner join ".$school_db.".departments d on d.departments_id = c.departments_id
                where 
                staff.user_login_detail_id = $login_detail_id
                and cs.school_id=".$school_id."
                order by d.title, c.name, cs.title
                ")->result_array();             
        
        if(count($month_year_data)>0)
        {
            $response['code']='200';
            $response['month_year']=$month_year_data;
            $response['sections']=$section_arr;
        }
        else{
            $response['code']='201';
            $response['msg']='Only class teacher can manage student attendance';
        }
        
        echo json_encode($response);
    }
    
    function get_students_for_attendance(){
        $response=array();
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $section_id = $this->input->post('section_id');
        $month_year = $this->input->post('month_year');
        
        $month_year = '01-'.$month_year;
        $date = date('d', strtotime($month_year));
        $month = date('m',strtotime($month_year));
        $year = date('Y',strtotime($month_year));
   
        $current_date=date('Y-m-d');                                       
        $date_curr= date("t", strtotime("$year-$month-01"));
        $date_day= date("d");
        

		$students	=	$this->db->query("select * 
                from ".$school_db.".student
                where 
                section_id=$section_id
                and student_status in (".student_query_status().") 
                and school_id=".$school_id."
                ")->result_array();
                
        $j=0;	
        $students_array=array();
		foreach($students as $row)
		{
			 $students_array[$j]['student_name']=$row['name'];
			 $students_array[$j]['student_id']=$row['student_id'];
                $i=date('d');
               
                 if($i<10)	
                        {$dayt	=	$year.'-'.$month.'-'."0".$i;}
                    else	
                        {$dayt	=	$year.'-'.$month.'-'.$i;}
                                                        
                	$verify_data =	array('student_id' => $row['student_id'], 'date' => $dayt);
                    $attendance = $this->db->get_where($school_db.'.attendance' , $verify_data);
                    
                    if($attendance->num_rows() != 0)
                    {
                        $a=$attendance->row()->status;
                        $status= $a;
                    }
                    else
                    {
                        $status="4";
                    }
                    $students_array[$j]['attendance_status']=$status;
                $j++;
        }
        if(count($students_array)>0)
        {
            $response['code']='200';
            $response['students']=$students_array;
        }
        else{
            $response['code']='201';
            $response['msg']='No student found';
        }
        
        echo json_encode($response);
    }
    
    //apply attendence
    function apply_attendence($section_id = 0)
    {
        $response=array();
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $student_id = json_decode($this->input->post('student_id'));
        $login_detail_id = $this->input->post('login_detail_id');
        $section_id = $this->input->post('section_id');
       
        $students_array=array();
        foreach($student_id as $key=>$row){
            foreach($row as $innerkey=>$innerrow)
            {
                $students_array[$key][$innerkey]=$innerrow;
            }
        }
        $this->db->trans_begin();
        
        $date       = date('d');
        $month      = date('m');
        $year       = date('Y');
        
        $date_today = $year.'-'.$month.'-'.$date;
        
        $send_sms = $this->input->post('send_sms');
        $response['code']='201';
        for($j=0; $j<count($students_array); $j++)
        {
            $device_id   =   $this->get_user_device_id(6 , $students_array[$j]['student_id'] , $school_id);
            //echo $device_id;
            $title       =   "Attendance Marked";
            $message     =   "Your Attendance Has been Marked By The Teacher.";
            $link        =    base_url()."student_p/manage_attendance";
            $this->sendNotificationByUserId($device_id , $title , $message , $link ,  $students_array[$j]['student_id'] , 6,$school_id, $school_db);
            
            $verify_data =  array('student_id'=> $students_array[$j]['student_id'], 'date'=> $date_today, 'school_id'=>$school_id );
            $attendance  = $this->db->get_where($school_db.'.attendance' , $verify_data);
            if($attendance->num_rows() != 0)
            {
                $attendance_id = $attendance->row()->attendance_id;
                $status_id     = $attendance->row()->status;

                $sts=$students_array[$j]['attendance_status'];
                $this->db->where('attendance_id' , $attendance_id);
                $this->db->where('school_id' , $school_id);
                
                $this->db->update($school_db.'.attendance', array( 'status' => $sts, 'user_id'=> $login_detail_id ) );
                
                	if($status_id == 2 && $sts == 1){
					 
					     $this->db->where('attendance_id',$attendance_id);
					     $this->db->update($school_db.'.attendance_timing' , array('check_in'=> date("h:i:s a")));
					}
					
					if($sts ==2):
				        $this->db->where('attendance_id',$attendance_id)->update($school_db.'.attendance_timing' , array('check_in'=>''));
				    endif;
				    $response['code']='200';
				    $response['msg']='Attendance marked successfully';
            }
            else
            {
                $attendance_status = $students_array[$j]['attendance_status'];
                   
                $this->db->insert($school_db.'.attendance', 
                array(
                    'status'     =>  ( intval($students_array[$j]['attendance_status']) > 0 ? $students_array[$j]['attendance_status'] : 2 ),
                    'user_id'    =>  $login_detail_id,
                    'student_id' =>  $students_array[$j]['student_id'],
                    'date'       =>  $date_today,
                    'school_id'  =>  $school_id
                ));
             
                
                 $last_id = $this->db->insert_id();
                 $check_in = date("h:i:s a");
                
                 if($attendance_status == 1){
                                $data['check_in'] = $check_in;
                            }else{
                                $data['check_in']  = "";
                            }
                $data['attendance_id'] = $last_id;
			    $this->db->insert($school_db.'.attendance_timing' , $data);
				$response['code']='200';
				$response['msg']='Attendance marked successfully';
            }
            
            
            $attendance_status = $students_array[$j]['attendance_status'];
            if($attendance_status==2  && (int) $send_sms == 1){
			   // $this->load->helper('message');
				$message_val   =  $this->db->query("select * from ".$school_db.".sms_settings  where school_id=".$school_id,"  and sms_type=1")->result_array();
				$student_detail=  $this->get_sms_detail( $students_array[$j]['student_id'],$school_db);
				$numb          =  $student_detail['mob_num'];
				$student_name  =  $student_detail['student_name'];
				$class_name    =  $student_detail['class_name'];
				$section_name  =  $student_detail['section_name'];
				$to_email      =  $student_detail['email'];
				$message       =  "$student_name of $class_name - $section_name is absent on (".date('d-M-Y', mktime(0, 0, 0, $month, $date, $year)).")";
				
				if($message_val[0]['status'] ==1){
					$this->send_sms($numb,'Indici Edu', $message, $students_array[$j]['student_id'] , 00, $school_id, $school_db);
				}
			}
			
			 //mobile notification
           
            $parent_idd             = $this->get_parent_id( $students_array[$j]['student_id'],$school_id,$school_db);   
            $stdname                = $this->get_student_info($students_array[$j]['student_id'],$school_id,$school_db);
            $std_name               = $stdname[0]['student_name'];
            $academic_year_id       = $stdname[0]['academic_year_id'];
           
           // $student_info_for_callan = get_student_name_and_academic_year_id($students_array[$j]['student_id'], $_SESSION['school_id']);
        //   $class_idd = $this->get_class_id($section_id);
        //   $class_name = $this->get_class_name($class_idd);
            
          $d=array(
            'title'=>'Attendance Marked For '.$std_name,
            'body'=>'Your Attendance Has been Marked By The Teacher.',
            );
             $d2 = array(
            'screen'=>'attendence',
            'student_id'=> $students_array[$j]['student_id'],
            'section_id'=> $section_id,
            'academic_year_id'=> $academic_year_id,
            
            );
            if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from ".$school_db.".mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        $islogin=0;
                        if($isUserLogin){
                            foreach($isUserLogin as $row)
                            {
                                $islogin=$row;
                            }
                        }
                        if($islogin == 1)
                        {
                             $this->notify($d,$d2,$parent_idd,$school_db);
                        }
                       
                    }

        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo json_encode($response);
        

    }
    
    //mobile notification
    function get_parent_id($student_id=0,$school_id,$school_db)
    {
        $query  = "SELECT * FROM ".$school_db.".student AS std
        INNER JOIN ".$school_db.".student_parent AS std_p on std.parent_id = std_p.s_p_id 
        where std.student_id = ".$student_id." AND std.school_id = ".$school_id." ";
        $result = $this->db->query($query)->result_array();
        
        
        $parent_id = "";
       if(count($result)>0){
            $parent_id =  $result[0]['user_login_detail_id'];
            $qry1  = "SELECT * FROM indicied_indiciedu_gsimscom_gsims_system.user_login_details where user_login_detail_id = $parent_id";
            $result1 = $this->db->query($qry1)->result_array();
            if(count($result1)>0){
                //print_r($result);
                $parent_id =  $result1[0]['user_login_id'];
                return $parent_id;
            }
           else{
               return 0;
           }
       }
       else{
           return 0;
       }
    }
    function get_student_info($student_id=0,$school_id,$school_db)
    {
        $q = "select s.name as student_name, s.section_id , s.academic_year_id,s.student_id from ".$school_db.".student s
        where s.student_id = ".$student_id." and s.school_id=".$school_id." ";
        $student = $this->db->query($q)->result_array();
        return $student;
    }
    function get_class_id($sect_id = 0,$school_id,$school_db)
    {
        $q = "select class_id from " . $school_db . ".class_section where section_id ='$sect_id' and school_id=" . $school_id . " ";
        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->class_id;
        }
        
    }
    function get_class_name($class_id = 0 , $school_id , $school_db)
    {
        $q = "select name, name_numeric from " . $school_db . ".class where class_id ='$class_id' and school_id=" . $school_id . " ";
        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->name."-".$result->name_numeric;
        }
        
    }
    
   function notify($data,$d2,$parent_idd,$school_db)
   {
        $q = "SELECT * from ".$school_db.".mobile_device_id where user_login_id = $parent_idd";
        $arr = $this->db->query($q)->result_array();
        
        for($i=0;$i<count($arr); $i++)
        {
            $device_id = $arr[$i]['mobile_device'];
            $api_key="AAAAfYWhBYY:APA91bFZzvDpWSTzc1zBGIAvRNZSJGYGxufyy6eFCdZqvWiyMGAi_FPY3ng0E90FUoC32KjJ6FjT97KhwGmIT_LpJ3es06K5hFFAkcEenfSGCMdGtYASv2g2HwtkUq-5VEsK975ht_zm";
            $url="https://fcm.googleapis.com/fcm/send";
            $fields=json_encode(array('to'=>$device_id,'notification'=>$data, 'data'=>$d2,
            ));
        
            // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
            $ch = curl_init();
        
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ($fields));
        
            $headers = array();
            $headers[] = 'Authorization: key ='.$api_key;
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
    }
    
    
    //notifications 
    function get_user_device_id($user_type , $user_id , $school_id)
    {
        $filter = "";
        if($user_type == 1){
            $filter =   "where admin_id  ";
        }elseif($user_type == 3){
            $filter =   "where teacher_id  ";
        }elseif($user_type == 4){
            $filter =   "where parent_id  ";
        }elseif($user_type == 6){
            $filter =   "where student_id  ";
        }
        
        if($filter != ""){
              $device_id = $this->db->query("select device_id from indicied_indiciedu_gsimscom_gsims_system.user_devices ".$filter." = $user_id AND school_id=" . $school_id . "")->row();
         
              if($device_id != null){
                  return $device_id->device_id;
              }
              else
              {
                  return "";
              }     
        }
        else
        {
              return "";
        }

    }
    
    function sendNotificationByUserId($device_id, $title, $message, $link , $userId , $userTypeId, $school_id, $school_db){
        $type="";
        if($userTypeId == 1){
            $type = "admin";
        }
        else if($userTypeId == 3){
            $type = "teacher";
        }
        else if($userTypeId == 4){
            $type = "parent";
        }
        else if($userTypeId == 6){
            $type = "student";
        }
        
        $data_not = array(
            'user_id'     => $userId ,
            'user_type'   => $type,
            'url'         => $link ,
            'inserted_at' => date('Y-m-d, h:i:s'),
            'text'        => $message ,
            'is_viewed'   => 0,
            'school_id'   => $school_id
        ); 
	    $this->db->insert($school_db.".school_notifications" , $data_not);
        
        
        if(!empty($device_id)){
            
            $app_id = 'c0c462a2-13b1-4dc9-a93c-d45b65c10b55';
            $content = array( "en" => $message );
            $hases_array = array();
            array_push($hases_array , array(
                "id"    =>  "like-button-2",
                "text"  =>  "INDICI EDU",
                "icon"  =>  "//lh3.googleusercontent.com/a-/AAuE7mDiEeRlIcdqvPPR5xRIGMgnh7Z1RIz0O0xhWWeoyw=s88",
                "url"   =>  $link
            ));
            $headings = array( 'en' => $title );
            $fields = array(
                'app_id' => $app_id,
                'include_player_ids' => array($device_id),
                'data' => array("foo" => "bar"),
                'contents' => $content,
                'headings' => $headings,
                'web_buttons'=>$hases_array,
                'url' => $link
            );
    
            $fields = json_encode($fields);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
            
        }        


        
    }
    
    function get_sms_detail($student_id = 0,$school_db)
    {
        $ur=$this->db->query("select s.student_id, s.email,s.mob_num,s.name, c.name as class_name, cs.title as section_name from ".$school_db.".student s 
                                inner join ".$school_db.".class_section cs on cs.section_id=s.section_id
                                inner join ".$school_db.".class c on c.class_id=cs.class_id
                                where s.student_id=$student_id ")->result_array();
    
        $ary['mob_num']=$ur[0]['mob_num'];
        $ary['student_name']=$ur[0]['name'];
        $ary['class_name']=$ur[0]['class_name'];
        $ary['section_name']=$ur[0]['section_name'];
        $ary['email']=$ur[0]['email'];
        $ary['student_id']=$ur[0]['student_id'];
        return $ary;
    }
    
    function month_year_option($start_date, $end_date)
    {
        $start_date = date('1 M Y', strtotime($start_date));
        $months = array();
    
        while (strtotime($start_date) <= strtotime($end_date)) {
            $months[] = array(
                'year' => date('Y', strtotime($start_date)),
                'month' => date('m', strtotime($start_date))
            );
    
            $start_date = date('d M Y', strtotime($start_date .
                '+ 1 month'));
        }
       
        $data_array=array();
        foreach ($months as $key => $value) {
            $data_array[$key]['month_year_value']=$value['month'] . "-" . $value['year'];
            $data_array[$key]['month_year_name']=month_of_year($value['month']) . "-" . $value['year'] ;
        }
        return $data_array;
    }
    
    function send_sms($to="",$from="Indici Edu",$message="",$student_id,$sms_section = 0, $school_id, $school_db)
    {
        //$to=$this->validate_phone_num($to);
        
        $access="";
        if($sms_section > 0)
        {
            $permission = $this->db->where("sms_section",$sms_section)->where("school_id",$school_id)->where("sms_status",1)->get($school_db.".sms_settings");
            if($permission->num_rows() > 0){
                $access = "grant";
            }else{
                $access = "denied";
            }
        }
        
        if($access == "grant" || $sms_section == 00){
        
            // start api
            $username = "923088805106";     ///API Username
            $password = "Indici@f3tech@@";    ///
            $mobile   = $to;                  ///Recepient Mobile Number
            $sender   = $from;
            
            if($mobile != ""){
                //sending sms
                // $post = "sender=".urlencode($sender)."&mobile=".urlencode($mobile)."&message=".urlencode($message)."";
                $post = "sender=".urlencode($sender)."&mobile=".urlencode($mobile)."&message=".urlencode($message)."&type=unicode";
                $url  = "https://sendpk.com/api/sms.php?username=$username&password=$password";
                $ch   = curl_init();
                $timeout = 30; // set to zero for no timeout
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $result = curl_exec($ch); 
                
                $message_length  = strlen($message);
                $message_count   = $message_length/160;
                $total_sms_count = ceil($message_count);
                
                
                $is_send        =  0;
                $r              =  explode(":" , $result);
                $response_text  =  trim($r[0]);
                $status_text    =  "";
                
                
                if($response_text == "OK ID")
                {
                    $status_text =  "Message Was Successfully Accepted For Delivery"; 
                    $is_send     =  1;
                }
                else if($response_text == 1){  $status_text = "Username Or Password Is Either Invalid Or Disabled";  }
                else if($response_text == 2){  $status_text = "Username Is Empty";   }
                else if($response_text == 3){  $status_text = "Password Is Empty";   }
                else if($response_text == 4){  $status_text = "Sender ID Is Empty";  }
                else if($response_text == 5){  $status_text = "Recepient Is Empty";  }
                else if($response_text == 6){  $status_text = "Message Is Empty";    }
                else if($response_text == 7){  $status_text = "Invalid Recepient";   }
                else if($response_text == 8){  $status_text = "Insufficient Credit"; }
                else if($response_text == 9){  $status_text = "SMS Rejected";        }
            }else{
                $status_text = "Mobile number is empty";
                $is_send = 0;
                $total_sms_count = 0;
            }
            
            $data['sys_sch_id']                 =   $school_id;
            $data['recepient']                  =   $to;
            $data['sender']                     =   $from;
            $data['student_id']                 =   $student_id;
            $data['message']                    =   $message;
            $data['status']                     =   $status_text;
            $data['is_send']                    =   $is_send; 
            $data['sms_type']                   =   1;
            $data['date_time']                  =   date('Y-m-d H:i:s'); 
            $data['sms_service']                =   "sendsms.pk";
            $data['total_sms_count']            =   $total_sms_count;
            
            $this->db->insert("indicied_indiciedu_gsimscom_gsims_system.sms_log",$data);
        
            return $result;
        
        }   
    }
    
    function validate_phone_num($string){
        //$string= preg_replace("/[^0-9]/", "", $string);
        $string=preg_replace('~\D~', '', $string);
        $to_count=strlen($string);
        $to_r="";
        for($i=11; $i>0;$i--)
        {
            $to_r=$string[$to_count].$to_r;
            $to_count--;
        }
        return "92".$to_r;
    }

    
     /***********MANAGE TEACHER ATTENDANCE SUMMARY  ***********/
     function teacher_attendance_summary(){
        $response = array();
        $login_detail_id = $this->input->post('login_detail_id');
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $academic_year_id = $this->input->post('academic_year_id');
        
        $acadmic_year_start = $this->db->query("select start_date,end_date from ".$school_db.".acadmic_year where 
                                         academic_year_id =".$academic_year_id."  and school_id=".$school_id." ")->result_array();
        $start_date=$acadmic_year_start[0]['start_date'];
        $end_date=$acadmic_year_start[0]['end_date'];   
        $array_attend=array();
        
         $q="select a.status,count(a.status) as status_count,month(a.date) as month, YEAR(a.date) as year,monthname(a.date) as month_name FROM ".$school_db.".attendance_staff a
               INNER JOIN  ".$school_db.".staff staff ON staff.staff_id = a.staff_id
               WHERE 
               staff.user_login_detail_id =$login_detail_id 
               AND a.school_id=".$school_id."
               group by status, month, year
               order by  month, year";
        $qur_red=$this->db->query($q)->result_array();
        $qur_array=array();
        $data_array = array();
        $i = 1;
        foreach($qur_red as $row)
        {
        	$qur_array[$row['year']][$row['month_name']][$row['status']]=$row['status_count'];
        	$data_array[$i]['year'] = $row['year'];
        	$data_array[$i]['month_name'] = $row['month_name'];
        	$data_array[$i]['status'] = $row['status'];
        	$data_array[$i]['status_count'] = $row['status_count'];
        	$i++;
        }
       
        $total_present=0;
        $total_absent=0;
        $total_leave=0;
        
        $i = 1;
        $year_month_array = array();
        while (strtotime($start_date) <= strtotime($end_date)) 
        {
            $year_month_array[$i]['month'] =  $show_month =  $month=date('F', strtotime($start_date));
            $show_nbsp =  "&nbsp";
            $year_month_array[$i]['year'] =	$show_year =  $year=date('Y', strtotime($start_date));
            $year_month_array[$i]['present'] = '';
            $year_month_array[$i]['absent'] = '';
            $year_month_array[$i]['leave'] = '';
        
        	$show_start_date = $start_date = date('d M Y', strtotime($start_date.'+ 1 month'));
        	$i++;
        }
        
        for($j=1; $j<=count($data_array); $j++)
        {
            for($k = 1; $k<=count($year_month_array); $k++)
            {
                if($year_month_array[$k]['year'] == $data_array[$j]['year'] && $year_month_array[$k]['month'] == $data_array[$j]['month_name'] )
                {
                    if($data_array[$j]['status'] ==1)
                    {
                        
                      $year_month_array[$k]['present'] =  $present = $data_array[$j]['status_count'];
                      $total_present = $present + $total_present;
                    }
                    if($data_array[$j]['status'] ==2)
                    {
                        
                       $year_month_array[$k]['absent'] = $absent = $data_array[$j]['status_count'];
                       $total_absent = $absent + $total_absent;
                    }
                    if($data_array[$j]['status'] ==3)
                    {
                       
                       $year_month_array[$k]['leave'] = $leave = $data_array[$j]['status_count'];
                       $total_leave = $leave + $total_leave;
                    }
                    
            
                
                }
            }
        }
        if($year_month_array)
        {
            $response['code'] = '200';
            $response['year_attendence'] =  $year_month_array;
            $response['present'] =  $total_present;
            $response['absent'] =  $total_absent;
            $response['leave'] =  $total_leave;
        }
        else{
              $response['code'] = '201';
              $response['msg'] = 'No Attendence Found!';
        }
        echo json_encode($response);
 
     }

  // Attendance View Specific Section Wise Report   ///
    function get_my_classes_sections(){
        $response = array();
        $login_detail_id = $this->input->post('login_detail_id');
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        
        $time_table_t_sec             =  $this->mobile_get_time_table_teacher_section($login_detail_id,$school_db,$school_id);
		$class_section                =  $this->get_teacher_dep_class_section_list($time_table_t_sec, $school_db,$school_id);
		if(count($class_section)>0){
		    $response['code']='200';
		    $response['class_section']=$class_section;
		}else{
		     $response['code']='201';
		     $response['message']='no class section found';
		}
        echo json_encode($response);
    }
    function view_stud_attendance()
	{
	    $response = array();
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $section_id = $this->input->post('section_id');
	    
	    $query="SELECT * FROM ".$school_db.".student WHERE section_id=$section_id AND school_id=".$school_id." AND  student_status IN (".student_query_status().") ORDER BY roll desc";
        $students=$this->db->query($query)->result_array();
        $students_array=array();
        if(count($students)>0)
        {
            foreach($students as $key=>$row)
            {
                $students_array[$key]['roll']               =$row['roll'];
                $students_array[$key]['student_id']         =$row['student_id'];
                $students_array[$key]['name']               =$row['name'];
                $students_array[$key]['image']              =$row['image'];
            }
            $response['code']='200';
		    $response['students']=$students_array;
		    $response['academic_year']=$this->academic_year_option_list('',3, $school_db, $school_id);
        }
        else{
		     $response['code']='201';
		     $response['message']='no student found';
		}
        echo json_encode($response);
	}
	
	function academic_year_option_list($selected = 0, $status = 0, $school_db, $school_id)
    {
        $statusStr = '';
        if (is_array($status) && (count($status) > 0)) {
            $status = implode(",", $status);
            $statusStr = ' and status NOT in (' . $status . ') ';
        } elseif ($status != 0) {
            $statusStr = ' and status <> ' . $status;
        }
    
        $q = "select * from " . $school_db. ".acadmic_year where school_id=" . $school_id . " " . $statusStr . " order by status DESC";
        
        $query = $this->db->query($q);
        $closed='';
        $data=array();
        if ($query->num_rows() > 0) {
            
            foreach ($query->result() as $key=>$rows) {
                $status_val = $rows->status;
                $status_value = "";
                if ($status_val == 1) {
                    $status_value = "Completed";
                }elseif ($status_val == 2) {
                    $status_value = "Current";
                }elseif ($status_val == 3) {
                    $status_value = "Upcoming";
                }
    
                $is_closed = $rows->is_closed;
                if ($is_closed == 1) {
                    $closed = " - Closed";
                }
                
                $data[$key]['academic_year_id'] = $rows->academic_year_id;
                $data[$key]['academic_year'] = $rows->title . '(' . date('d-M-Y', strtotime($rows->start_date)) . ' to ' . date('d-M-Y', strtotime($rows->end_date)) . ')' . ' - ' . $status_value . $closed;
               }
        }
        return $data;
    }
    
    function get_section_student($section_id = 0, $student_id=0)
    {
        $response=array();
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $section_id = $this->input->post('section_id');
        if($section_id != "")
        {
            $students= $this->section_student($section_id , $school_db, $school_id);
            if(count($students)>0)
            {
              $response['code']='200';
		      $response['students']=$students; 
            }else{
                $response['code']='201';
                $response['message']='no student found';
            }
        }
        else
        {
            $response['code']='201';
            $response['message']='invalid section id';
        }
        echo json_encode($response);
    }
  
    function section_student($section_id, $school_db, $school_id)
    {
        $q = "SELECT student_id, name FROM " .$school_db . ".student
            WHERE 
            section_id=$section_id 
            AND school_id=" . $school_id . "
            AND student_status IN (" . student_query_status() . ")
            ORDER BY name";
        $query = $this->db->query($q);
        $student_arrray=array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key=>$rows) {
                $student_arrray[$key]['student_id']=$rows->student_id;
                $student_arrray[$key]['name']=$rows->name;
               
            }
        }
        return $student_arrray;
    }

    function student_attendance_summary()
    {
        $response = array();
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
  		$section_id=$this->input->post("section_id");
  		$student_id=$this->input->post("student_id");
  		$academic_year_id=$this->input->post("academic_year_id");
  		
  		if(isset($section_id) && ($section_id > 0) && isset($student_id) && ($student_id > 0))
  		{
  			$acadmic_year_start = $this->db->query("select start_date,end_date  from ".$school_db.".acadmic_year 
                            where  academic_year_id =".$academic_year_id."  and school_id=".$school_id." ")->result_array();

            $start_date=$acadmic_year_start[0]['start_date'];
            $end_date=$acadmic_year_start[0]['end_date'];     
                            
            $p="select status,count(status) as status_count, month(date) as month_val, year(date) as year_val,monthname(date) as month_name from ".$school_db.".attendance 
            where student_id=$student_id and school_id=".$school_id." GROUP BY month_val,year_val,status order by  month_name, year_val";
            $qur_red=$this->db->query($p)->result_array();
         
            $qur_array=array();
            $data_array = array();
            $i = 1;
            
            foreach($qur_red as $row)
            {
            	$qur_array[$row['year_val']][$row['month_name']][$row['status']]=$row['status_count'];
            	$data_array[$i]['year'] = $row['year_val'];
            	$data_array[$i]['month_name'] = $row['month_name'];
            	$data_array[$i]['status'] = $row['status'];
            	$data_array[$i]['status_count'] = $row['status_count'];
            	$i++;
            }
           
            $total_present=0;
            $total_absent=0;
            $total_leave=0;
            
            $i = 1;
            $year_month_array = array();
            while (strtotime($start_date) <= strtotime($end_date)) 
            {
                $year_month_array[$i]['month'] =  $show_month =  $month=date('F', strtotime($start_date));
                $show_nbsp =  "&nbsp";
                $year_month_array[$i]['year'] =	$show_year =  $year=date('Y', strtotime($start_date));
                $year_month_array[$i]['present'] = '';
                $year_month_array[$i]['absent'] = '';
                $year_month_array[$i]['leave'] = '';
            
            	$show_start_date = $start_date = date('d M Y', strtotime($start_date.'+ 1 month'));
            	$i++;
            }
            
            for($j=1; $j<=count($data_array); $j++)
            {
                for($k = 1; $k<=count($year_month_array); $k++)
                {
                    if($year_month_array[$k]['year'] == $data_array[$j]['year'] && $year_month_array[$k]['month'] == $data_array[$j]['month_name'] )
                    {
                        if($data_array[$j]['status'] ==1)
                        {
                            
                          $year_month_array[$k]['present'] =  $present = $data_array[$j]['status_count'];
                          $total_present = $present + $total_present;
                        }
                        if($data_array[$j]['status'] ==2)
                        {
                            
                           $year_month_array[$k]['absent'] = $absent = $data_array[$j]['status_count'];
                           $total_absent = $absent + $total_absent;
                        }
                        if($data_array[$j]['status'] ==3)
                        {
                           
                           $year_month_array[$k]['leave'] = $leave = $data_array[$j]['status_count'];
                           $total_leave = $leave + $total_leave;
                        }
                    }
                }
            }
            if($year_month_array)
            {
                $response['code'] = '200';
                $response['year_attendence'] =  $year_month_array;
                $response['present'] =  $total_present;
                $response['absent'] =  $total_absent;
                $response['leave'] =  $total_leave;
            }
            else{
                  $response['code'] = '201';
                  $response['msg'] = 'No Attendence Found!';
            }
		}
		 echo json_encode($response);
    }
    
    //manage leaves
	function manage_leaves()
    {
        $operation = $this->input->post('operation');
		$school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		$folder_name = $this->input->post('folder_name');
        $login_detail_id = $this->input->post('login_detail_id');
        $yearly_term_id = $this->input->post('yearly_term_id');
        $academic_year_id = $this->input->post('academic_year_id');
        //$staff_id =  $this->input->post('staff_id');
        $staff_id = $this->get_login_teacher_idd($login_detail_id,$school_db);
        if($operation == 'create')
		{
		   if(!empty($_FILES['image'])){
			    $proof                     =  $_FILES['image']['name'];
		   }
			$proof_doc                 =  $staff_id."-".$proof;
			$data['staff_id']          =  $staff_id;
			$data['requested_by']      =  $login_detail_id;
			$data['school_id']         =  $school_id;
			$data['leave_category_id'] =  $this->input->post('leave_id');
			$data['request_date']      =  date('Y-m-d');
			$data['start_date']        =  date('Y-m-d',strtotime($this->input->post('start_date')));
			$data['end_date']          =  date('Y-m-d',strtotime($this->input->post('end_date')));
			$data['reason']            =  $this->input->post('reason');
			$data['proof_doc']         =  $proof_doc;
			$data['status']            =  0;
			$data['yearly_terms_id']   = $yearly_term_id;
			
			if($proof!="")
        	{
        	    $ext = pathinfo($proof, PATHINFO_EXTENSION);
                $new_file = '' . '_' . time() . '.' . $ext;
        	    $data['proof_doc']         =  $new_file;
        	    $tempname                     =  $_FILES['image']['tmp_name'];
        	    $imagePath='uploads/'.$folder_name.'/leaves_staff/'.$new_file;
        	    move_uploaded_file($tempname,$imagePath);
				//$data['proof_doc']=$this->file_upload_funt($folder_name,'userfile','leaves_student','');
			}
            //echo $imagePath; exit;
			$dddd =  $this->db->insert($school_db.'.leave_staff', $data);
			
			$school_admins = $this->get_school_admins($school_id);
            foreach($school_admins as $admin){
                $device_id  =   $this->get_user_device_id(1 , $admin['user_login_detail_id'] , $school_id);
                $title      =   "New Leave Request";
                $message    =   "A Leave Request Has been Submitted By Teacher.";
                $link       =    base_url()."leave_staff/manage_leaves_staff";
                $this->sendNotificationByUserId($device_id, $title, $message, $link , $admin['user_login_detail_id'] , 1, $school_id, $school_db);
            }
			
			$response = array();
			if($dddd){
			   $response['code'] = '200';
    	       $response['msg'] = 'Request Submitted';
    	     
			}
			else{
			    $response['code'] = '500';
	            $response['msg'] = 'Request not added!';
			}
		
            echo json_encode($response);

		}

        else{
            $leaves = $this->db->query("select sl.* from 
                ".$school_db.".leave_staff sl
                inner join ".$school_db.".yearly_terms yt on yt.yearly_terms_id=sl.yearly_terms_id 
                inner join ".$school_db.".acadmic_year ay on ay.academic_year_id=yt.academic_year_id 
                inner join ".$school_db.".staff staff on staff.staff_id = sl.staff_id
                where 
                staff.user_login_detail_id=$login_detail_id
                and sl.school_id= ".$school_id."
                and ay.academic_year_id=".$academic_year_id."
                order by sl.leave_staff_id desc
                ")->result_array();
            
            $leave_request_details = array();
    	   $i = 1;
    	   foreach($leaves as $row)
    	   {
    	       $leave_request_details[$i]['reason'] = $row['reason'];
    	       if($row['proof_doc']!="")
    	       {
    	          $leave_request_details[$i]['link'] = display_link($row['proof_doc'],'leaves_student');
                }
                else{ 
                       $leave_request_details[$i]['link'] =  "No File attached"; 
                    }
                    
                $leaves_cat = $this->get_type_name_by_id('leave_category',$row['leave_category_id'],$school_db,$school_id);
                $leave_request_details[$i]['category'] = $leaves_cat->name;
                $leave_request_details[$i]['duration'] = 'From '.convert_date($row['start_date']).' to '. convert_date($row['end_date']);
                if($row['start_date'] != "" && $row['end_date'] != ""){
    
                    $start_date= $row['start_date'];
    				$d=explode("-",$start_date);
    				// $leave_request_details[$i]['approved_upto_date'] =  date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                    $end_date=$row['end_date'];
                    $dd=explode("-",$end_date);
    				$leave_request_details[$i]['Actual Start Date / Actual End Date'] = date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0])).' / '.date("d-M-Y",mktime(0,0,0,$dd[1],$dd[2],$dd[0]));
                           
                 }
                 
                $leave_request_details[$i]['request_date'] = convert_date($row['request_date']);
                 if($row['process_date'] != '0000-00-00')
                    {
                     $leave_request_details[$i]['approval_date'] =  convert_date($row['process_date']);
                    }
                if($row['status']==0){
                                 $leave_request_details[$i]['status'] = get_phrase("pending");
                            } 
                if($row['status']==1){
                                 $leave_request_details[$i]['status'] = get_phrase("approved");
                            }
                if($row['status']==2){
                                 $leave_request_details[$i]['status'] = get_phrase("rejected");
                            }
            
            $i++;
    	   }
    	    $this->db->where('school_id',$school_id);
            $book = $this->db->get($school_db.'.leave_category')->result_array();
            
            $leave_category = array();
            $j = 1;
            foreach($book as $row)
            {
                $leave_category[$j]['leave_category_id'] =  $row['leave_category_id'];
                $leave_category[$j]['category_name'] = $row['name'];
                $j++;
            }
            
            $response = array();
    	    if($leave_category)
    	    {
    	       $response['code'] = '200';
    	       $response['category'] = $leave_category;
    	       $response['leave_request_details'] = $leave_request_details;
    	    }
    	    else{
    	         $response['code'] = '500';
    	         $response['msg'] = 'No Leaves Found!';
    	    }
            echo json_encode($response);
        }
    }

	function get_school_admins($school_id){
        $query= "select user_login_detail_id from indicied_indiciedu_gsimscom_gsims_system.user_login_details where login_type = 1 and sys_sch_id=" . $school_id . "";
        $users = $this->db->query($query)->result_array();
        return $users;
    }
    function get_type_name_by_id($type="",$type_id='',$school_db,$school_id)
	{
		 return $this->db->select('name')->get_where($school_db.'.'.$type,array($type.'_id'=>$type_id,'school_id' =>$school_id))->row();
	}
	
	function get_login_teacher_idd($user_login_detail_id = 0,$school_db)
    {
        $login_detail_id = $this->db->get_where(
           $school_db. ".staff",
            array('user_login_detail_id' => $user_login_detail_id)
        )->result_array();
    
        return intval($login_detail_id[0]['staff_id']);
    }
    function get_academic_year_datess($academic_year_id = 0,$school_id,$school_db) {
        
        $q = "select * from ".$school_db.".acadmic_year where academic_year_id = ".$academic_year_id." and school_id=".$school_id." and status = 2";
        $arr = $this->db->query($q)->row();
        return $arr;
        
    }
    
    function diary()
    {
        $response=array();
        $operation = $this->input->post('operation');
		$school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		$folder_name = $this->input->post('folder_name');
        $login_detail_id = $this->input->post('login_detail_id');
        $yearly_term_id = $this->input->post('yearly_term_id');
        $academic_year_id = $this->input->post('academic_year_id');
        $staff_id = $this->get_login_teacher_idd($login_detail_id,$school_db);
        $operation = $this->input->post('operation');
        
        
        if ($operation == 'add_diary') 
        {
          $this->db->trans_begin();
            
            $data['teacher_id']  = $staff_id;
            $subject_id          = $this->input->post('subject_id');
            $section_id          = $this->input->post('section_id');
            $data['subject_id']  = $subject_id;
            $data['section_id']  = $section_id;
            $data['task']        = $this->input->post('task');
            $data['title']       = $this->input->post('title');
            $data['assign_date'] = date('Y-m-d',strtotime($this->input->post('assign_date')));
            $data['due_date']    = date('Y-m-d',strtotime($this->input->post('due_date')));
            $data['school_id']   = $school_id;
             if ($this-> get_diary_approval_method($school_id,$school_db) == 1){
                 $data['admin_approvel']   = '1' ;
                }
            if(!empty($_FILES['image1'])){
                $filename=$_FILES['image1']['name'];
                if($filename!="")
                {
            
                    $path = 'uploads/' . $folder_name."/diary";
                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
            
                    $filename       =   $_FILES['image1']['name'];
                    $ext            =   pathinfo($filename, PATHINFO_EXTENSION);
                    $subject_name   =   $this->get_subject_name($subject_id,$school_db, $val = 0);
                    $section_detils =   $this->section_hierarchy($section_id, $school_id,$school_db);
                    $class_name     =   $section_detils['d']."-".$section_detils['c']."-".$section_detils['s'];
                 
                    $teacher_name   =   $this->get_teacher_name($staff_id,$school_db,$school_id);
                    $new_file       =   time()."_".$class_name."_".$subject_name."_".$teacher_name. '.' . $ext;
                    copy($_FILES['image1']['tmp_name'], $path . '/' . $new_file);
                    $data['attachment'] = $new_file;
                }    
            } 
            $this->db->insert($school_db.'.diary', $data);
            $last_id=$this->db->insert_id();
            
            $planner_check=array();
            $planner_check= json_decode($this->input->post('planner_check'));
            
            if(isset($planner_check) && count($planner_check) > 0)
            {
                foreach($planner_check as $planner)
                {
                    $data2['diary_id'] = $last_id;
                    $data2['planner_id'] = $planner;
                    $data2['school_id'] = $school_id;
                    $this->db->insert($school_db.'.academic_planner_diary',$data2);
                }
            }
            
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                 $response['code']='201';
            } else {
                $this->db->trans_commit();
                $response['code']='200';
            }
            echo json_encode($response);
        }
        
        elseif ($operation == 'do_update') 
        {
            $diary_id                = $this->input->post('diary_id');
            $subject_id          = $this->input->post('subject_id');
            $section_id          = $this->input->post('section_id');
            $data['teacher_id']      = $staff_id;
            $data['subject_id']      = $this->input->post('subject_id');
            $data['section_id']      = $this->input->post('section_id');
            $data['task']            = $this->input->post('task');
            $data['title']           = $this->input->post('title');
            $data['assign_date'] = date('Y-m-d',strtotime($this->input->post('assign_date')));
            $data['due_date']    = date('Y-m-d',strtotime($this->input->post('due_date')));
            
            
            if(!empty($_FILES['image1'])){
                $filename    = $_FILES['image1']['name'];
                $folder_name = $folder_name;
                if($filename != "")
                {
                    $path = 'uploads/' . $folder_name."/diary";
                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
            
                    //$filename       =   $_FILES['image1']['name'];
                    $ext            =   pathinfo($filename, PATHINFO_EXTENSION);
                    $subject_name   =   $this->get_subject_name($subject_id,$school_db, $val = 0);
                    $section_detils =   $this->section_hierarchy($section_id, $school_id,$school_db);
                    $class_name     =   $section_detils['d']."-".$section_detils['c']."-".$section_detils['s'];
                 
                    $teacher_name   =   $this->get_teacher_name($staff_id,$school_db,$school_id);
                    $new_file       =   time()."_".$class_name."_".$subject_name."_".$teacher_name. '.' . $ext;
                    copy($_FILES['image1']['tmp_name'], $path . '/' . $new_file);
                    $data['attachment'] = $new_file;
                    // $data['attachment'] = file_upload_fun('image2','diary');
                    $image_old          = $this->input->post('image_old');
                    if($image_old != "")
                    {
                        // $del_location=system_path($image_old,'diary');
                        $del_location='uploads/' . $folder_name.'/diary/'.$image_old;
                        file_delete($del_location);
                    }
                }               
            }
            if (intval($this->input->post('is_submitted')) > 0)
            {
                $data['is_submitted'] = 1;
                $data['submission_date'] = date('Y-m-d h:i:s');
                $data['submitted_by'] = intval($login_detail_id);
            }
            
            $this->db->where('school_id',$school_id);
            $this->db->where('diary_id', $diary_id);
            $this->db->update($school_db.'.diary', $data);
           
            //update in academic_planner_diary table
            $planner_check=array();
            $planner_check=  json_decode($this->input->post('planner_check'));
        
            if(isset($planner_check) && count($planner_check) > 0)
            {
                //delete entries then update
                $this->db->where('school_id',$school_id);
                $this->db->where('diary_id', $diary_id);
                
                $this->db->delete($school_db.'.academic_planner_diary');
                foreach($planner_check as $planner)
                {
                    $data2['planner_id']=$planner;
                    $data2['school_id']=$school_id;
                    $data2['diary_id']= $diary_id;
                    $this->db->where('school_id',$school_id);
                    $this->db->where('diary_id', $diary_id);
                    $this->db->insert($school_db.'.academic_planner_diary',$data2);
                }
            }
            $response['code']='200';
            echo json_encode($response);
        }
        elseif ($operation == 'delete') 
        {
            $diary_id                = $this->input->post('diary_id');
            // Transection Start
            $this->db->trans_begin();
            
            // Get Diary Attachments
            $get_diary_attachemnet = $this->db->query("SELECT attachment FROM ".$school_db.".diary WHERE diary_id = $diary_id AND school_id = ".$school_id." ")->result_array();
            $diary_attachemnet = $get_diary_attachemnet[0]['attachment'];
          
            // Delete Diary Attachment Files
            if($diary_attachemnet != ""){
               // $del_location = system_path($diary_attachemnet,'diary');
                $del_location='uploads/' . $folder_name.'/diary/'.$diary_attachemnet;
                file_delete($del_location);
            }
            // Get Student Submitted Attachments
            $get_std_attachment = $this->db->query("SELECT da.answer_attachment,da.diary_student_id FROM ".$school_db.".diary_student ds
    	                            INNER JOIN ".$school_db.".diary_attachments da on da.diary_student_id  = ds.diary_student_id 
    	                            WHERE ds.diary_id = ".$diary_id." AND ds.school_id = ".$school_id." ")->result_array();
    	   foreach($get_std_attachment as $row):
    	       //Delete Student Attachments
    	        $diary_std_attachemnet = $row['answer_attachment'];
    	        if($diary_std_attachemnet != ""){
        	       // $del_location = system_path($diary_std_attachemnet,'diary');
        	        $del_location='uploads/' . $folder_name.'/diary/'.$diary_std_attachemnet;
                    file_delete($del_location);
    	        }
    	        // Delete Diary Student Attachment Table Entry                
                $this->db->where('diary_student_id', $row['diary_student_id']);
                $this->db->delete($school_db.'.diary_attachments');
    	   endforeach;
    	   
    	    // Get Diary Audio
            $get_diary_audio = $this->db->query("SELECT audio FROM ".$school_db.".diary_audio WHERE diary_id = $diary_id ")->result_array();
            $diary_audio="";
            if($get_diary_audio){
                $diary_audio = $get_diary_audio[0]['audio'];
            }
            // Delete Diary Audio Files
            if($diary_audio != ""){
               // $del_audio_location = system_path($diary_audio,'diary_audios');
                $del_audio_location = 'uploads/' . $folder_name.'/diary/'.$diary_audio;
                file_delete($del_audio_location);
                
                // Delete Diary Audio Table Entry  
                $this->db->where('diary_id', $diary_id);
                $this->db->delete($school_db.'.diary_audio');
            }
         
            // Delete Academic Planner Table Entry
            $this->db->where('school_id',$school_id);
            $this->db->where('diary_id', $diary_id);
            $this->db->delete($school_db.'.academic_planner_diary');
                
            // Delete Diary Student Table Entry
            $this->db->where('school_id',$school_id);
            $this->db->where('diary_id', $diary_id);
            $this->db->delete($school_db.'.diary_student');
            
            // Delete Diary Table Entry
            $this->db->where('school_id',$school_id);
            $this->db->where('diary_id', $diary_id);
            $this->db->delete($school_db.'.diary');
            
            // Transection End
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
               // $this->session->set_flashdata('club_updated',get_phrase('diary_not_deleted'));
                $response['code']='201';
                $response['msg']=get_phrase('diary_not_deleted');
            } else {
                $this->db->trans_commit();
                //$this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
                $response['code']='200';
                $response['msg']=get_phrase('record_deleted_successfully');
            }
           echo json_encode($response);
           // $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            //redirect(base_url() . 'teacher/diary');
            
            //old code
            // $this->db->where('school_id',$_SESSION['school_id']);
            // $this->db->where('diary_id', $param2);
            // $this->db->delete(get_school_db().'.diary');
            // redirect(base_url() . 'teacher/diary/'.$param3);
        }
        else
        if ($operation == 'assign_subjects')
        {
            $this->db->trans_begin();
            
            $diary_id = intval($this->input->post('diary_id'));
            $student_ids = json_decode($this->input->post('student_id'));
            // print_r($student_ids);exit;
            // $students_array=array();
            // foreach($student_ids as $key=>$row){
            //     foreach($row as $innerkey=>$innerrow)
            //     {
            //         $students_array[$key]=$innerrow;
            //     }
            // }
            //print_r(count($students_array));exit;
            
            $data_diary['is_assigned'] = 1;
            $this->db->where('diary_id',$diary_id);
            $this->db->update($school_db.'.diary',$data_diary);
            
            $this->db->query("delete from ".$school_db.".diary_student where diary_id = $diary_id");
            //insert into student diary
            $student_diary['school_id'] = $school_id;
            $student_diary['diary_id'] = $diary_id;
            
            $query = "select section_id, teacher_id , subject_id , assign_date , due_date ,  title from ".$school_db.".diary where diary_id = ".$diary_id." and school_id = ".$school_id." ";
            $diary_details = $this->db->query($query)->result_array();
            
            $teacher_name = $this->get_teacher_name($diary_details[0]['teacher_id'],$school_db,$school_id);
            $subject_name = $this->get_subject_name($diary_details[0]['subject_id'],$school_db, $val = 0);
            $assign_date = $diary_details[0]['assign_date'];
            $due_date = $diary_details[0]['due_date'];
            $title = $diary_details[0]['title'];
            $sect_name = $this->get_section_name($diary_details[0]['section_id'],$school_db,$school_id);
            $class_idd = $this->get_class_id($diary_details[0]['section_id'],$school_id,$school_db);
            $class_name = $this->get_class_name($class_idd,$school_id,$school_db);
           
            foreach ($student_ids as $key => $value) 
            {
               
                $student_diary['student_id'] = $value;
                $this->db->insert($school_db.'.diary_student', $student_diary);
                
                //_____________sms and email portion__________________________
                ///_________________sms start_________________________________
                // $this->load->helper('message');
                // $sms_ary = get_sms_detail($value);
                
                // $mob_num = $sms_ary['mob_num'];
                // $message = "A new assignment has been assigned to " . $sms_ary['student_name'] . " for ". $subject_name . " and is due by ".date_view($due_date).", please login to your account for more details.";
                 
                // if (isset($_POST['send_message']) && $_POST['send_message'] != "") {
                   
                //     send_sms($mob_num, 'Indici Edu', $message, $value,3);
                // }
                
                // if (isset($_POST['send_email']) && $_POST['send_email'] != "") {
                    
                //     $message = "<b>Dear ".$sms_ary['student_name']." </b> <br><br> A new assignment has been assigned to <br> <strong> Student Name:</strong> " . $sms_ary['student_name'] . " <br> <strong>Subject</strong> : ". $subject_name . " <br> <strong> Due Date: </strong>".date_view($due_date)." <br> To view the assignment, you are requested to logon to ".base_url()."<br>
                //     <br> In case of any query, you may please contact with ".$teacher_name." before 2 days of due date";
                    
                //     $to_email = $sms_ary['email'];
                //     $subject = "New Assignment";
                //     $email_layout = get_email_layout($message);
                //     email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, $value,00);
                // }
                if($this-> get_diary_approval_method($school_id,$school_db) == 1){ //sent notification if approval method is teacher
                
                    $device_id  =   $this-> get_user_device_id(6 , $value  , $school_id);
                    $title      =   "Diary Assigned";
                    $message    =   "A New Diary has been Assigned.";
                    $link       =    base_url()."student_p/diary";
                    $this-> sendNotificationByUserId($device_id, $title, $message, $link , $value , 6, $school_id, $school_db);
                    
                    
                    $parent_idd = $this->get_parent_id($value,$school_id,$school_db);     
                    $stdname    =    $this->get_student_info($value,$school_id,$school_db);
                    $std_name   = $stdname[0]['student_name'];
                    $d=array(
                    'title'=>$teacher_name,
                    'body'=>$subject_name.' diary added for '.$std_name.' '.$class_name .'-'.$sect_name,
                    );
                     $d2 = array(
                    'screen'=>'diary',
                    'student_id'=>$value,
                    'section_id'=>$diary_details[0]['section_id'],
                    'selectedSubject'=>'0',
                    'startDate'=>'',
                    'endDate'=>'',
                    );
                    if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from ".$school_db.".mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        $islogin=0;
                        if($isUserLogin){
                            foreach($isUserLogin as $row)
                            {
                                $islogin=$row;
                            }
                        }
                        if($islogin == 1)
                        {
                             $this->notify($d,$d2,$parent_idd,$school_db);
                        }
                     
                    }
                    
                }
            }
            
            
    	    $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
           
        }

//         if ($param1 == 'create') 
//         {
//             $data['school_id']=$_SESSION['school_id'];       	
//             $data['subject_id']=$this->input->post('subject_id');
//             $data['class_id']= $this->input->post('class_id');
//             $data['teacher_id']=$this->input->post('teacher_id');
//             $data['title']=$this->input->post('title');

//             $filename=$_FILES['attach_file']['name'];
//             if(!empty($filename))
//             {
//                 $ext = pathinfo($filename, PATHINFO_EXTENSION);
//                 $data['attachment']=time().'.'.$ext;

//                 move_uploaded_file($_FILES['attach_file']['tmp_name'],'uploads/diary_image/'.$data['attachment']);
//             }
//             $c_time_display = new DateTime($this->input->post('due_date'));
//             $data['diary_type']=1;
//             $date_due=$c_time_display->format('Y-m-d').PHP_EOL;
//     		$data['due_date']   	= $date_due;
//             $data['task'] 			= $this->input->post('task');
//     		$data['date'] 			= Date('Y-m-d');
//             $this->db->insert(get_school_db().'.diary', $data);
//             redirect(base_url() . 'teacher/diary/'.$data['class_id']);
//         }

//         if ($param1 == 'create_diary') 
//         {
// 			$data['diary_type']=2;
// 			$data['school_id']     = $_SESSION['school_id'];
//             $data['subject_id']     = $this->input->post('subject_id');
//             $data['student_id']     = $this->input->post('student_id');
//             $data['class_id']   	= $this->db->get_where(get_school_db().'.subject' , array('subject_id' => $data['subject_id']))->row()->class_id;
//             $data['teacher_id'] 	= $this->input->post('teacher_id');
            
//             $data['title'] 	= $this->input->post('title');
//             $filename=$_FILES['attach_file']['name'];
			
// 			if(!empty($filename))
//             {
//     			$ext = pathinfo($filename, PATHINFO_EXTENSION);
//     			$data['attachment']=time().'.'.$ext;
//     			move_uploaded_file($_FILES['attach_file']['tmp_name'],'uploads/diary_image/'.$data['attachment']);
//             }
// 			$c_time_display = new DateTime($this->input->post('due_date'));
// 			$date_due	=	$c_time_display->format('Y-m-d').PHP_EOL;
// 			$data['due_date']   	= $date_due;
//             $data['task'] 			= $this->input->post('task');
// 			$data['date'] 			= Date('Y-m-d');
			
//             $this->db->insert(get_school_db().'.diary', $data);
//             redirect(base_url() . 'teacher/diary/'.$data['class_id']);
//         }
        else{
             $verify_data	=	array(	'teacher_id' 	=> $staff_id, 'school_id' =>$school_id);
    		$class = $this->db->get_where($school_db.'.class' , $verify_data)->row();
    		
    		//$page_data['class_id'] 	 =	
    		$class_id	=	$class->class_id;
    		
            $subject = array();
            $d_c_s_sec = $this->mobile_get_teacher_dep_class_section($login_detail_id,$school_db,$school_id);
            	
            
            $filter_subject_id = intval($this->input->post('subject'));
            if($filter_subject_id=="" || $filter_subject_id==0)
            {
    			$sub_arr = $this->get_teacher_section_subjects($d_c_s_sec,$school_id,$school_db); //get section subjects
            	if ( count($sub_arr) > 0 )
            	{
                foreach ($sub_arr as $value) 
                {
                    $subject[] = $value; 
                }
            	}
    		}
        	//print_r($d_c_s_sec);print_r($subject);exit;
            $time_table_t_sub = $this->get_time_table_teacher_subject($login_detail_id,$school_id,$school_db);
            
            //$sub_in = 0;
            $sub_in = array();
            if (count($time_table_t_sub) > 0)
            {
                $sub_in = implode(',', array_unique($time_table_t_sub));
            }
            //print_r($sub_in);exit;
            $d_c_s_sec = array();//get_teacher_dep_class_section($login_detail_id);
            $time_table_t_sec = $this->mobile_get_time_table_teacher_section($login_detail_id,$school_db,$school_id);
            $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
            $page_data['teacher_section'] = $teacher_section;
    
            $sec_in = 0;
            if (count($teacher_section) > 0)
            {
                $sec_in = implode(',', array_unique($teacher_section));
            }
            
            
            //filter
            
            $dep_c_s_id = intval($this->input->post('dep_c_s_id'));
            $month_year = $this->input->post('month_year');
            $per_page = 10;
            $apply_filter = $this->input->post('apply_filter', TRUE);
    		$std_search = $this->input->post('std_search', TRUE);
            $std_search = trim(str_replace(array("'", "\""), "", $std_search));
            
      
            $q = "select da.dairy_audio_id,da.audio, dr.*, sub.name as subject_name, sub.code as subject_code
                FROM ".$school_db.".diary dr
                INNER JOIN ".$school_db.".subject sub ON sub.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".staff staff ON staff.staff_id = dr.teacher_id
                LEFT JOIN ".$school_db.".diary_audio da ON dr.diary_id = da.diary_id
                Where 
                dr.school_id=".$school_id." 
                AND staff.user_login_detail_id = $login_detail_id
                AND dr.subject_id in ($sub_in)
                AND dr.section_id in ($sec_in)
                order by dr.diary_id desc "; 
    
            $diary_count = $this->db->query($q)->result_array();
            $total_records = count($diary_count);
           // $quer_limit = $q . " limit " . $start_limit . ", " . $per_page . "";
            $diary = $this->db->query($q)->result_array();
    
            
            $teacher_sections = $this->get_teacher_section($login_detail_id,$school_id,$school_db);
            $page_data['teacher_sections'] = $teacher_sections;
            
            $diary_data=array();
            foreach ($teacher_section as $key=>$row){
                $section_detail = $this->section_hierarchy($row, $school_id,$school_db);
                 $diary_data[$key]['class_sections']['cs_id']=$row;
                $diary_data[$key]['class_sections']['cs_name']= $section_detail['d']." / ".$section_detail['c']." / ".$section_detail['s'];
                $subjects=$this->get_section_subjects($row,$login_detail_id, $school_id, $school_db);
                $diary_data[$key]['class_sections_subjects']=$subjects;
            }
            if($diary_data){
                $response['code']='200';
                $response['diary']=$diary_data;
            }else{
                 $response['code']='201';
                 $response['msg']='no diary found';
            }
            
           echo json_encode($response);
        }
       
		 
    }
    
    function system_path($file_name, $folder_name = "", $is_root = 0)
    {
        if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
            return "";
        } elseif ($file_name == "") {
            return "";
        } elseif ($folder_name == "") {
            return $link = 'uploads/' . $_SESSION['folder_name'] . '/' . $file_name;
        } elseif ($is_root == 1) {
            return $link = 'uploads/' . $folder_name . '/' . $file_name;
        } else {
            return $link = 'uploads/' . $_SESSION['folder_name'] . '/' . $folder_name . '/' . $file_name;
        }
    }
    
    function get_acad_checkboxes()
	{
		$school_db=$this->input->post('school_db');
		$school_id=$this->input->post('school_id');
		$assign_date=$this->input->post('assign_date');
		$subject_id= $this->input->post('subject_id');
		$subject_array=explode("-",$subject_id);
		$subj_id=$subject_array[0];
		$q="SELECT planner_id,title FROM ".$school_db.".academic_planner WHERE `start`='$assign_date' AND subject_id=$subj_id AND school_id=".$school_id."";

		$query=$this->db->query($q)->result_array();
		$response=array();
		$data=array();
		if(count($query) > 0)
		{
			foreach($query as $key=>$row)
			{
			    $data[$key]['planner_id']=$row["planner_id"];
		        $data[$key]['title']=$row["title"];
			}
		    $response['code']='200';
		    $response['planner']=$data;
		}
		else
		{
		     $response['code']='201';
		     $response['msg']=get_phrase('no_record_found');
		}
		echo json_encode($response);
		
	}
	
    
    function file_upload_fun($file_name = "", $folder_name = "", $prefix = "", $is_root = 0)
    {
        $path = 'uploads/' . $_SESSION['folder_name'] . '/' . $folder_name;
    
        if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
            return "";
        } elseif ($file_name == "") {
            return "";
        } elseif ($folder_name == "") {
            $path = 'uploads/' . $_SESSION['folder_name'];
        } elseif ($is_root == 1) {
            $path = 'uploads/' . $folder_name;
        }
    
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    
        if ($_FILES[$file_name]['name'] != "") {
            $filename = $_FILES[$file_name]['name'];
            //print_r($_FILES);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
            $new_file = $prefix . '_' . time() . '.' . $ext;
           
    
            move_uploaded_file($_FILES[$file_name]['tmp_name'], $path . '/' . $new_file);
    
            return $new_file;
        } else {
            return "";
        }
    }
    
    function get_teacher_section_subjects($sections=array(),$school_id,$school_db)
    {
    	$sec_in = 0;
        if (count($sections) > 0)
        {
            $sec_in = implode(',', array_unique($sections));
        }
    	$sub_res = $this->db->query("select s.subject_id
                from ".$school_db.".subject s
                inner join ".$school_db.".subject_section ss on s.subject_id = ss.subject_id
                where 
                s.school_id=".$school_id." 
                and ss.section_id in ($sec_in)
                ")->result_array();
    
    	$sub_arr = array();
    	foreach ($sub_res as $value) 
    	{
    		$sub_arr[] = $value['subject_id']; 
    	}
        return array_unique($sub_arr);
    }
    
    function get_time_table_teacher_subject($login_detail_id = 0,$school_id,$school_db)
    {
        $teacher_arr = $this->db->query("select s.subject_id FROM 
    		".$school_db.".class_routine cr 
                inner join ".$school_db.".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                inner join ".$school_db.".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
                inner join ".$school_db.".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                inner join ".$school_db.".subject s on st.subject_id=s.subject_id
                inner join ".$school_db.".staff staff on st.teacher_id=staff.staff_id
                where staff.user_login_detail_id = $login_detail_id
                and cr.school_id=".$school_id."
                /*group by section_id*/
                ")->result_array();
    	$subject = array();
    
        if ( count($teacher_arr) > 0 )
        {
        	foreach ($teacher_arr as $value) 
        	{
        		$subject[] = $value['subject_id']; 
        	}
        }  
        return $subject;  
    }
    
    function get_teacher_section($login_detail_id = 0,$school_id,$school_db)
    {
        $class_section = $this->db->query("select distinct cs.section_id FROM ".$school_db.".class_section cs
            inner join ".$school_db.".staff staff on staff.staff_id = cs.teacher_id 
    		where  staff.user_login_detail_id=$login_detail_id 
           and cs.school_id=".$school_id."")->result_array();
    
    	$section = array();
    	if (count($class_section) > 0)
        {
        	foreach ($class_section as $key => $value) 
        	{
        		$section[] = $value['section_id']; 
        	}
        }
        return $section;
    }
    
    function section_hierarchy($section, $school_id,$school_db)
    {
        if ($section == '') {
            $section = 0;
        }
      
        $sec_ary = array();
        $query = "select sec.class_id as class_id, c.name as class_name, sec.title as section_name, d.title as department_name,d.departments_id as departments_id,sec.section_id as section_id
    	FROM " . $school_db . ".class_section sec
    	INNER join " . $school_db . ".class c
    	ON sec.class_id=c.class_id
    	INNER join " . $school_db . ".departments d
    	ON c.departments_id = d.departments_id
    	WHERE sec.section_id=$section and sec.school_id = " . $school_id . " ";
        $classArr = $this->db->query($query)->result_array();
        $sec_ary['d'] = $classArr[0]['department_name'];
        $sec_ary['c'] = $classArr[0]['class_name'];
        $sec_ary['s'] = $classArr[0]['section_name'];
        $sec_ary['d_id'] = $classArr[0]['departments_id'];
        $sec_ary['c_id'] = $classArr[0]['class_id'];
        $sec_ary['s_id'] = $classArr[0]['section_id'];
        return $sec_ary;
    }
    
    function get_section_subjects($section_id, $login_detail_id, $school_id, $school_db)
    { 
        $data = "";
        $query = "select s.* FROM 
    	".$school_db.".class_routine cr 
            inner join ".$school_db.".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
            inner join ".$school_db.".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            inner join ".$school_db.".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            inner join ".$school_db.".subject s on s.subject_id=st.subject_id
            inner join ".$school_db.".staff staff on staff.staff_id=st.teacher_id
            inner join ".$school_db.".subject_section SS on SS.subject_id = st.subject_id
            inner join ".$school_db.".class_section cs on cs.section_id = crs.section_id
            inner join ".$school_db.".class on class.class_id = cs.class_id
            inner join ".$school_db.".departments d on d.departments_id = class.departments_id
            where 
            staff.user_login_detail_id = ".$login_detail_id."
            and cr.school_id=".$school_id."
            and crs.section_id = $section_id
            group by s.subject_id
            ";
        $subject_arr = $this->db->query($query)->result_array();
        return $subject_arr;
       
    }
    
    function getsubjectdiary() { 
        $response=array();
        $subject_id  = $this->input->post('subject_id');
        $section_id  = $this->input->post('section_id');
        $school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		$academic_year_id = $this->input->post('academic_year_id');
		$login_detail_id = $this->input->post('login_detail_id');
		$user_id = $this->get_login_teacher_idd($login_detail_id,$school_db);
		
        $academic_year_dates = $this->get_academic_year_datess($academic_year_id,$school_id,$school_db);
        $academic_year_start_date  = $academic_year_dates->start_date;
        $academic_year_end_date  = $academic_year_dates->end_date;
        
        $academic_year_filter = "and assign_date between '$academic_year_start_date' and '$academic_year_end_date' ";
        $qur = "SELECT * from ".$school_db.".diary WHERE subject_id = $subject_id AND section_id = $section_id $academic_year_filter AND teacher_id = '".$user_id."' "; 
        $arr = $this->db->query($qur)->result_array();
        $diary=array();
        if(count($arr) > 0)
        {
            foreach($arr as $key=>$row)
            {
                $diary[$key]['diary_id']=$row['diary_id'];
                $diary[$key]['title']=$row['title'];
                $diary[$key]['assign_date']=$row['assign_date'];
            }
        }   
        if($diary){
            $response['code']='200';
            $response['diary']=$diary;
        }else{
             $response['code']='201';
             $response['msg']='no diary found';
        }
        
       echo json_encode($response);
    }
    
    function get_diary_data()
    {
        $response=array();
        $diary_id  = $this->input->post('diary_id');
        $school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		$folder_name = $this->input->post('folder_name');
		
        $qur = "SELECT d.*,aud.*, d.diary_id AS d_id,sub.name as subject_name, sub.code as subject_code from ".$school_db.".diary d  
                INNER JOIN ".$school_db.".subject sub ON sub.subject_id = d.subject_id
                LEFT JOIN ".$school_db.".diary_audio aud ON aud.diary_id = d.diary_id
                WHERE d.diary_id =  $diary_id AND d.school_id =  ".$school_id." ";
        
        $diary_arr = $this->db->query($qur); 
        $arr = $diary_arr->row(); 
        $diary=array();
        if($diary_arr->num_rows() > 0) {
            $diary['title']=$arr->title;
            $diary['task']=$arr->task;
            $diary['teacher_name']=$this->get_teacher_name($arr->teacher_id,$school_db,$school_id);
            $diary['assign_date']=date_view($arr->assign_date);
            $diary['due_date']=date_view($arr->due_date);
            $diary['is_assigned']=$arr->is_assigned;
            $diary['audio']=$arr->audio;
            $diary['attachment']=$arr->attachment;
            $diary['approval_method']=$this-> get_diary_approval_method($school_id,$school_db);
            $diary['admin_approvel']=$arr->admin_approvel;
            $response['code']='200';
            $response['diary_data']=$diary;
        }else
        {
             $response['code']='201';
             $response['msg']='no diary found';
        }
       echo json_encode($response);
    }
    
    function get_teacher_name($teacher_id = 0,$sch_db = "",$sch_id = 0)
    {
        $q = "SELECT s.name FROM ".$sch_db.".staff s
    		INNER JOIN ".$sch_db.".designation d 
    		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
    		WHERE s.staff_id=$teacher_id AND s.school_id=" .$sch_id. " ";
        $teacher = $this->db->query($q)->result_array();
        $teacher_name = $teacher[0]['name'];
        return $teacher_name;
    }
    
    function get_diary_approval_method($school_id,$school_db)
    {
        
        $query = $this->db->query("select diary_approval from " . $school_db . ".school where school_id = $school_id");
        $row   = $query->row();
        return   $row->diary_approval;
    }
    
    function view_students_diary_assigned()
	{
	    $response = array();
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $section_id = $this->input->post('section_id');
        $diary_id = $this->input->post('diary_id');
	    
	    $selected_qry = $this->db->query("select ds.student_id from 
            ".$school_db.".diary_student ds
            inner join ".$school_db.".student s on s.student_id = ds.student_id
            where
            ds.diary_id = $diary_id
            and s.student_status in (".student_query_status().")
            and s.section_id = $section_id
            and s.school_id = ".$school_id."
            ")->result_array();

        $selected_student = array();
        foreach ($selected_qry as $key => $value) 
        {
            $selected_student[] = $value['student_id'];
        }
    
        $is_submitted = $this->db->query("select * from ".$school_db.".diary
                    where
                    diary_id = $diary_id
                    and school_id = ".$school_id."
                    "
                    )->result_array();
                      
        $query = $this->db->query("select student_id, name, roll 
            from ".$school_db.".student
            where 
            section_id=$section_id 
            and school_id=".$school_id."
            and student_status IN (".student_query_status().")
        ")->result_array();
        
        $students=array();
       
        if(count($query) > 0)
        {
            foreach($query as $key=>$rows)
            {
                $isAssigned=0;
                if ($is_submitted[0]['is_submitted'] == 0)
                {  
                    if (is_array($selected_student)) {
                        if( in_array($rows['student_id'], $selected_student , true))
                        {
                            $isAssigned=1;
                        }
                    }
                    $students[$key]['student_id']=$rows['student_id'];
                    $students[$key]['roll']=$rows['roll'];
                    $students[$key]['name']=$rows['name'];
                    $students[$key]['isAssigned']=$isAssigned;
                }
                else
                {
                    if( in_array($rows['student_id'], $selected_student))
                    {
                        $isAssigned=1;
                        $students[$key]['student_id']=$rows['student_id'];
                        $students[$key]['roll']=$rows['roll'];
                        $students[$key]['name']=$rows['name'];
                        $students[$key]['isAssigned']=$isAssigned;
                    }
                }
            }
            
            $response['code']='200';
            $response['is_submitted']=$is_submitted[0]['is_submitted'];
		    $response['students']=$students;
            
        }
        else
        {
		     $response['code']='201';
		     $response['message']='no student found';
		}
        echo json_encode($response);
	}
    
    function get_section_name($section_id,$school_db,$school_id)
    {
        $q = "select title from " . $school_db . ".class_section where section_id=" . $section_id . " AND school_id=" . $school_id . "";
        $sectionArr = $this->db->query($q)->result_array();
        return $sectionArr[0]['title'];
    }
    
    
    function get_section_student_subject()
    {
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $subject_list = $this->teacher_subject_option_list(intval($this->input->post('login_detail_id')),intval($this->input->post('section_id')),$school_db,$school_id);
        $response=array();
        $response['code']='200';
        $response['subject']=$subject_list;
        echo json_encode($response);
    }
    
    
    function get_teacher_sections()
    {
        $school_db = $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $login_detail_id=$this->input->post('login_detail_id');
        $section_arr                = $this->mobile_get_time_table_teacher_section($login_detail_id,$school_db,$school_id);
        $class_section              = $this->get_teacher_dep_class_section_list($section_arr, $school_db,$school_id);
        $response=array();
        $response['code']='200';
        $response['sections']=$class_section;
        echo json_encode($response);
    }
    
    function teacher_subject_option_list($user_login_detail_id=0,$section_id=0,$school_db,$school_id)
    {
    	$query = "select s.* FROM 
    		".$school_db.".class_routine cr 
                inner join ".$school_db.".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                inner join ".$school_db.".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
                inner join ".$school_db.".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                inner join ".$school_db.".subject s on s.subject_id=st.subject_id
                inner join ".$school_db.".staff staff on staff.staff_id=st.teacher_id
                inner join ".$school_db.".subject_section SS on SS.subject_id = st.subject_id
                inner join ".$school_db.".class_section cs on cs.section_id = crs.section_id
                inner join ".$school_db.".class on class.class_id = cs.class_id
                inner join ".$school_db.".departments d on d.departments_id = class.departments_id
                where 
                staff.user_login_detail_id = $user_login_detail_id
                and cr.school_id=".$school_id."
                and crs.section_id = $section_id
                group by s.subject_id
                ";
    
    
     
    	$subject_arr = $this->db->query($query)->result_array();
        $subjects=array();
    	foreach($subject_arr as $key=>$row)
    	{
    		$subjects[$key]['subject_id']=$row['subject_id'];  
    		$subjects[$key]['name']=$row['name'].' - '.$row['code'];  
    	}	
    	return $subjects; 
    }
    
    function edit_diary()
    {
        $response=array();
        $diary_id = intval($this->input->post('diary_id'));
        $school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		
		$query="select dr.*,d.departments_id as departments_id,c.class_id as class_id, cs.section_id as section_id
            FROM ".$school_db.".diary dr 
            INNER join ".$school_db.".class_section cs
            ON dr.section_id=cs.section_id
            Inner JOIN ".$school_db.".class c
            On cs.class_id=c.class_id
            Inner join ".$school_db.".departments d
            On d.departments_id=c.departments_id
            WHERE dr.diary_id=$diary_id AND dr.school_id=".$school_id."";
        
        $edit_data=$this->db->query($query)->result_array();
        $data=array();
        
        foreach($edit_data as $row)
        {
            $data['section_id']=$row['section_id'];
            $data['subject_id']=$row['subject_id'];
            $data['title']=$row['title'];
            $data['task']=strip_tags($row['task']);
            $data['assign_date']=$row['assign_date'];
            $data['due_date']=$row['due_date'];
            $data['attachment']=$row['attachment'];
            $data['is_assigned']=$row['is_assigned'];
            $data['is_submitted']=$row['is_submitted'];
        }
        
      
        
        if($edit_data){
            $response['code']='200';
            $response['diary']=$data;
        }
        else
        {
              $response['code']='201';
              $response['msg']='no diary found';
        }
        echo json_encode($response);
    }
    
    function check_planner_diary_edit()
    {
        $response=array();
        $diary_id = intval($this->input->post('diary_id'));
        $school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		$assign_date = $this->input->post('assign_date');
		$subject_id = $this->input->post('subject_id');
		
        $query2="select planner_id FROM ".$school_db.".academic_planner_diary 
                WHERE diary_id=".$diary_id." ";
        $selected=$this->db->query($query2)->result_array();
        $res_array=array();
        foreach($selected as $res)
        {
            $res_array[]=$res['planner_id'];
        }
      
        $query1="select planner_id,title FROM ".$school_db.".academic_planner WHERE `start`='$assign_date' AND subject_id=".$subject_id." AND school_id=".$school_id."";
        $result=$this->db->query($query1)->result_array();
        $planner_array=array();
        foreach($result as $key=>$planner)
        {
            $checked = false;
            if (in_array($planner["planner_id"],$res_array))
            {
                $checked = true;
            }
            $planner_array[$key]['planner_id']=$planner["planner_id"];
            $planner_array[$key]['title']=$planner["title"];
            $planner_array[$key]['check']=$checked;
        }
        
        $response['code']='200';
        $response['planner']=$planner_array;
        echo json_encode($response);
    }
    
    function assignment_details($diary_id=0)
	{
	    $response=array();
	    $diary_id = intval($this->input->post('diary_id'));
        $school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		$folder_name = $this->input->post('folder_name');
		
	    $q = "select ds.* , d.* from  ".$school_db.".diary_student ds
	          inner join ".$school_db.".diary d on d.diary_id = ds.diary_id
	          where ds.diary_id = ".$diary_id." and ds.school_id = ".$school_id." ";
	    $stud_diary = $this->db->query($q)->result_array();
	    $assign_date=convert_date($stud_diary[0]['assign_date']);
	    $title=$stud_diary[0]['title'];
	    $subject_name=$this->get_subject_name($stud_diary[0]['subject_id'],$school_db, $val = 0);
        if($stud_diary[0]['attachment']!="") {
            $due_date=convert_date($stud_diary[0]['due_date']);   
        }
        
        $section_detils =   $this->section_hierarchy($stud_diary[0]['section_id'], $school_id,$school_db);
        $class_name     =   $section_detils['d']."-".$section_detils['c']."-".$section_detils['s'];
        
        $sub_date="";
        $sub_detail="";
        $sub_planner=array();
        if ($stud_diary[0]['submission_date'] != '0000-00-00 00:00:00')
        {
            $sub_date=convert_date($stud_diary[0]['submission_date']).' '.date('h:i:s A', strtotime($row['submission_date']));
            $sub_detail=$stud_diary[0]['task'];
        }
        
        $planner_arr = $this->db->query("select ap.* 
            from ".$school_db.".academic_planner_diary apd
            inner join ".$school_db.".academic_planner ap
                on ap.planner_id = apd.planner_id
            where apd.diary_id = ".$stud_diary[0]['diary_id']." 
            and apd.school_id = ".$school_id."
            ")->result_array();
        if (count($planner_arr)>0)
        {
            $p_count=1;
            foreach ($planner_arr as $key => $value) 
            {
                $sub_planner[$key]['title']=$planner_arr[0]['title'];
            }
        } 
      
        
        $j=0;
    	$total_records = 0;
    	$total_submitted = 0;
    	$total_notsubmitted = 0;
    	$total_viewed = 0;
    	$total_notviewd = 0;
    	
    	$assessment=array();
    	foreach($stud_diary as $row)
    	{
        	$j++;
        	$total_records++;
        
        	$assessment[$j]['student_name']= $this->get_student_name($row['student_id'], $school_id,$school_db);
            if($row['is_submitted'] == 1)
        	{
    			$total_submitted++;
    			$assessment[$j]['submission_status']=get_phrase('submitted');
    		}
    		else
    		{
    			$total_notsubmitted++;
    			$assessment[$j]['submission_status']=get_phrase('not_submitted');
    		}
    		if($row['is_viewed'] == '1')
    		{
    			$total_viewed++;
    			$assessment[$j]['view_status']=get_phrase('viewed');
    		}
    		else
    		{
    			$total_notviewd++;
    			$assessment[$j]['view_status']=get_phrase('not_view');
    		}
    		if($this->get_diary_student_attachments_count($row['diary_student_id'],$school_db) > 0)
    		{
    			$attachments = $this->get_diary_student_attachments($row['diary_student_id'],$school_db);
    			$assessment[$j]['attachments']=$attachments; 
    		}
    		else{
    			$assessment[$j]['attachments']="No Attachments"; 
    		}
    		$aa="";
    		if($row['answer_text'] != "")
    		{
    			$aa = $row['answer_text'];       
            }
            $assessment[$j]['answer_text']=strip_tags($aa);
    	}
    	$response['code']='200';
    	$response['subject_name']=$subject_name;
        $response['assign_date']=$assign_date;
        $response['title']=strip_tags($title);
        $response['class_name']=$class_name;
        $response['sub_date']=$sub_date;
        $response['sub_detail']=$sub_detail;
    	$response['sub_planner']=$sub_planner;
    	$response['assessment']=$assessment;
    	$response['total_records']=$total_records;
    	$response['total_submitted']=$total_submitted;
    	$response['total_viewed']=$total_viewed;
    	$response['total_notsubmitted']=$total_notsubmitted;
    	
    	echo json_encode($response);
	}
    function get_diary_student_attachments_count($diary_student_id,$school_db){
        $query = $this->db->query("select count(id) as total_records from " . $school_db. ".diary_attachments where diary_student_id = $diary_student_id");
        $row   = $query->row(); 
        return $row->total_records;
    }
    function get_diary_student_attachments($diary_student_id,$school_db)
    {
        $query = $this->db->query("select GROUP_CONCAT(answer_attachment) as urls from " . $school_db . ".diary_attachments where diary_student_id = $diary_student_id");
        $row   = $query->row();
        return $row->urls;
    }
    
    function view_notes()
	{
	    $school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		$folder_name = $this->input->post('folder_name');
		$login_detail_id = $this->input->post('login_detail_id');
		$user_id = $this->get_login_teacher_idd($login_detail_id,$school_db);
		
	   $q = "select nt.* , (Select GROUP_CONCAT(document_url) from ".$school_db.".lecture_notes_documents where notes_id = nt.notes_id) as urls from ".$school_db.".lecture_notes nt WHERE nt.school_id=".$school_id." 
	                and nt.teacher_id=".$user_id."  order by nt.notes_id desc";
	   $notes = $this->db->query($q)->result_array();
	    
	    print_r($notes);exit;
	    $page_data['notes']		 =	 $notes;
		$page_data['page_name']  =	'notes/view_notes';
		$page_data['page_title'] =	 get_phrase('view_notes');
		$this->load->view('backend/index', $page_data);
	}
  

}