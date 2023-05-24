<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

class Mobile_Webservices extends CI_Controller {
    
	private $system_db;
	private $school_db;
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('common');
		
        $this->load->dbutil();
		
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");   
        $this->load->library('BlinqApi');
        
	}
	public function index(){
	    echo "2222";exit;
	}
    function get_academic_year_dates($academic_year_id = 0) {
        
        $q = "select * from ".$this->auth_array['school_db'].".acadmic_year where academic_year_id = ".$academic_year_id." and school_id=".$this->auth_array['school_id']." and status = 2";
        $arr = $this->db->query($q)->row();
        return $arr;
        
    }
    function get_login_parent_id($user_login_detail_id = 0){
        
        $login_detail_id = $this->db->get_where(
            $this->auth_array['school_db'] . ".student_parent",
            array('user_login_detail_id' => $user_login_detail_id)
        )->result_array();
    
        return intval($login_detail_id[0]['s_p_id']);
        
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
            
        
			
				
				
				//Parent Login Only Restriction
				if($login_type == '4') {
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
    						$this->auth_array['parent_sys_sch_id'] =  $system_school_arr[0]['parent_sys_sch_id'];
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
    
        function kids_list_per_parents()
		{
		    $response=array();
		   $query = '';
		   $kid_data = array();
		   $school_id =  $this->input->post('school_id');
		   $school_db =  $this->input->post('school_db');
		   $login_detail_id =  $this->input->post('login_detail_id');
		  // echo json_encode($school_id);
		  // echo json_encode($school_db); 
		  // echo json_encode($login_detail_id);
		   
    		 
                  $query=$this->db->query("SELECT sr.*, c.name as class_name, sp.*,s.*,sp.user_login_detail_id as uld_id,cs.title as section_name, dep.title as department_name\n
                              \tFROM ".$school_db.".student_parent sp\n
                              \tINNER JOIN ".$school_db.".student_relation sr ON sr.s_p_id = sp.s_p_id\n
                              \tINNER JOIN ".$school_db.".student s ON s.student_id=sr.student_id\n
                              \tINNER JOIN ".$school_db.".class_section cs ON cs.section_id=s.section_id\n
                              \tINNER JOIN ".$school_db.".class c ON c.class_id=cs.class_id\n
                              \tINNER JOIN ".$school_db.".departments dep ON dep.departments_id=c.departments_id \n
                              \tWHERE  \n                                        \ts.student_status IN (".student_query_status().")\n 
                              \tand sp.school_id=".$school_id." \n
                              \tAND sp.user_login_detail_id=".$login_detail_id);
            
                $i = 0;
                foreach($query->result_array() as $std_data)
                {
                    $kid_data[$i]['name'] = $std_data['name'];
                    $kid_data[$i]['class_name'] = $std_data['class_name'];
                    $kid_data[$i]['section_name'] = $std_data['section_name'];
                    $kid_data[$i]['student_id'] = $std_data['student_id'];
                    $kid_data[$i]['section_id'] = $std_data['section_id'];
                    $kid_data[$i]['academic_year_id'] = $std_data['academic_year_id'];
                    $i++;
                    
                }
                
                if($kid_data)
                {
                 
                    $response['code']= '200';
                    $response['children'] = $kid_data;
                    
                    echo json_encode($response);
                    
                }
                else{
                    $response['code']= '500';
                    $response['error_message']= 'No child found!';
                    
                }
               
            
        }
        
        	/**********WATCH NOTICEBOARD AND EVENT ********************/
        	
	function noticeboard()
	{
	   $response = array();
	     $school_id =  $this->input->post('school_id');
		 $school_db =  $this->input->post('school_db');
		 $limit =  $this->input->post('limit');

		
		$q="select n.notice_id as notice_id,n.notice_title as notice_title,n.notice as notice,n.create_timestamp as create_timestamp
		    FROM ".$school_db.".noticeboard n WHERE n.school_id=".$school_id." ";
		$notice_count = $this->db->query($q)->result_array();   
		  $quer_limit   = $q . " limit " . $limit . "";
		  $notice       = $this->db->query($quer_limit)->result_array();
		 
		  if($notice)
                {
                   
                 
                    $response['code']= '200';
                  
                    $response['notices'] = $notice;
                    
                    echo json_encode($response);
                    
                }
                else{
                    $response['code']= '500';
                    $response['error_message']= 'No notices found!';
                    
                }
	
		
// 		$total_records = count($notice_count);
//         $quer_limit   = $q . " limit " . $start_limit . ", " . $per_page . "";
//         $notice       = $this->db->query($quer_limit)->result_array();

// 		$page_data['notices'] = $notice;
// 	//	$config['base_url']   = base_url() . "parents/noticeboard/" . $start_date . "/". $end_date . "/". $std_search;
// 		$config['total_rows'] = $total_records;
//         $config['per_page']   = $per_page;

//         $config['uri_segment']      = 6;
//         $config['num_links']        = 2;
//         $config['use_page_numbers'] = TRUE;
//         $this->pagination->initialize($config);

//       //  $pagination                 =  $this->pagination->create_links();
//         $page_data['start_limit']   =  $start_limit;
//         $page_data['apply_filter']  =  $apply_filter;
//         $page_data['total_records'] =  $total_records;
//         $page_data['pagination']    =  $pagination;
//         $page_data['start_date']    =  $start_date;
//         $page_data['end_date']      =  $end_date;
//         $page_data['std_search']    =  $std_search;
// 		$page_data['page_name']     =  'noticeboard';
// 		$page_data['page_title']    =  get_phrase('noticeboard');

      //  echo json_encode($page_data);
        //print_r($page_data);

        

	}
	function noticeboard_all()
	{
	    
	    $response = array();
	     $school_id =  $this->input->post('school_id');
		 $school_db =  $this->input->post('school_db');
		 $limit =  $this->input->post('limit');
		 $page =  $this->input->post('page');
// echo $page;
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
                    
                    echo json_encode($response);
                    
                }
                else{
                    $response['code']= '500';
                    $response['error_message']= 'No notices found!';
                    
                }
	
		
// 		$total_records = count($notice_count);
//         $quer_limit   = $q . " limit " . $start_limit . ", " . $per_page . "";
//         $notice       = $this->db->query($quer_limit)->result_array();

// 		$page_data['notices'] = $notice;
// 	//	$config['base_url']   = base_url() . "parents/noticeboard/" . $start_date . "/". $end_date . "/". $std_search;
// 		$config['total_rows'] = $total_records;
//         $config['per_page']   = $per_page;

//         $config['uri_segment']      = 6;
//         $config['num_links']        = 2;
//         $config['use_page_numbers'] = TRUE;
//         $this->pagination->initialize($config);

//       //  $pagination                 =  $this->pagination->create_links();
//         $page_data['start_limit']   =  $start_limit;
//         $page_data['apply_filter']  =  $apply_filter;
//         $page_data['total_records'] =  $total_records;
//         $page_data['pagination']    =  $pagination;
//         $page_data['start_date']    =  $start_date;
//         $page_data['end_date']      =  $end_date;
//         $page_data['std_search']    =  $std_search;
// 		$page_data['page_name']     =  'noticeboard';
// 		$page_data['page_title']    =  get_phrase('noticeboard');

      //  echo json_encode($page_data);
        //print_r($page_data);

        
	}
	
	/***MANAGE CIRCULARS, WILL BE SEEN BY Selected Corresponding DASHBOARDS**/
	function circulars($param1 = '', $param2 = '', $param3 = '')
	{
	    $school_id =  $this->input->post('school_id');
		 $school_db =  $this->input->post('school_db');
		 $student_id =  $this->input->post('student_id');
		 $section_id =  $this->input->post('section_id');
		
		$start_date   =  $this->input->post('starting');
        $end_date     =  $this->input->post('ending');
        
        if($start_date!='')
        {
        	$start_date_arr = explode("/",$start_date);
        	$start_date     = $start_date_arr[2].'-'.$start_date_arr[1].'-'.		$start_date_arr[0];
        }
        if($end_date!='')
        {
        	$end_date_arr  = explode("/",$end_date);
        	$end_date      = $end_date_arr[2].'-'.$end_date_arr[1].'-'.$end_date_arr[0];
        }
        if($start_date!='')
        {
        	$date_query=" AND create_timestamp >= '".$start_date."'";
        	$page_data['start_date'] = $start_date;
        	$page_data['filter']     = true;
        }
        if($end_date!='')
        {
        	$date_query=" AND create_timestamp <= '".$end_date."'";
        	$page_data['end_date'] = $end_date;
        	$page_data['filter']   = true;
        }
        
        if($start_date!='' && $end_date!='')
        {
        	$date_query=" AND create_timestamp >= '".$start_date."' AND create_timestamp <= '".$end_date."' ";
        	$page_data['start_date'] = $start_date;
        	$page_data['end_date']   = $end_date;
        	$page_data['filter']     = true;
        }
		
		$q="select cl.circular_id as circular_id,cl.circular_title as circular_title,cl.circular as circular,cl.section_id as section_id,cl.student_id as student_id,cl.create_timestamp 
		as create_timestamp,cl.attachment as attachment,d.title as department, c.name as class_name,cs.title as class_section
			FROM ".$school_db.".circular cl
			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = cl.section_id
            INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
            INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
			((cl.student_id='' OR cl.student_id=0 OR cl.student_id= ".$student_id." ) AND  cl.section_id= ".$section_id.")  ";
// 			AND
// 		 	cl.school_id=".$school_id. $date_query. "  ";
		 //	ORDER BY  cl.create_timestamp desc ";

		$page_data['circulars']  = $this->db->query($q)->result_array();
		$page_data['page_name']  = 'circulars';
		$page_data['page_title'] = get_phrase('circulars');
		 if($page_data)
                {
                   
                 
                    $response['code']= '200';
                  
                    $response['circulars'] = 	$page_data['circulars'];
                    
                    echo json_encode($response);
                    
                }
                else{
                    $response['code']= '500';
                    $response['error_message']= 'No notices found!';
                    
                }
	//	print_r($page_data);exit;
	
	}
	
    	function convert_dat($date = '', $is_time = 0)
{
    //return $date.$is_time;
    if ($date != "" && $date != '0000-00-00' && $date != '0000-00-00 00:00:00') {
        if (isset($is_time) && $is_time == 1) {
            return date('d-M-Y h:i:s', strtotime($date));
        } else {
            return date('d-M-Y', strtotime($date));
        }
    } else
        return '';
}
    function section_hierarchyy($section, $d_school_id = 0,$school_db)
    {
        if ($section == '') {
            $section = 0;
        }
        $school_id = "";
        if (!empty($d_school_id)) {
            $school_id = $d_school_id;
        } else {
            $school_id = $d_school_id;
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
    function get_subject_namee($subject_id,$sch_db = "", $val = 0)
    {
        if($sch_db == ""):
            $sch_db ="indicied_indiciedu_production";
        endif;    
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
    function get_student_name($student_id, $school_id = 0,$school_db)
    {
    
    
        $q = "SELECT name FROM " . $school_db . ".student
    	WHERE student_id=$student_id AND school_id=" . $school_id . "";
        $student = $this->db->query($q)->result_array();
        $student_name = $student[0]['name'];
        return $student_name;
    }
    
    function get_teacher_name($teacher_id = 0,$sch_db = "",$sch_id = 0)
    {
      
        $q = "SELECT s.name FROM ".$sch_db.".staff s
    		INNER JOIN ".$sch_db.".designation d 
    		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
    		WHERE s.staff_id=$teacher_id AND s.school_id=" .$sch_id. " ";
        $teacher = $CI->db->query($q)->result_array();
        $teacher_name = $teacher[0]['name'];
        return $teacher_name;
    }
	
    	
    function subject_componentss($components=0)
    {
    
        $CI=& get_instance();
        $CI->load->database();
        $res=array();
        if($components!='')
        {
            $res=array();
            $names=$CI->db->query("select title from indicied_indiciedu_production.subject_components where subject_component_id in(".$components.")")->result_array();
    
            foreach($names as $name)
            {
                $res[]=$name['title'];
            }
    
        }
        // return implode('<br/>',$res); 
        return $res; 
    }
		/**********MANAGING CLASS ROUTINE******************/
	function class_routine($param1 = '', $param2 = '', $param3 = '')
	{
	    $time_table = array();
	    $subject = array();
	    $teacher = array();
	    $durtion = array();
	    $start_end = array();
	   
	    $school_db = $this->input->post('school_db');
	    $school_id = $this->input->post('school_id');
	    $student_id = $this->input->post('student_id');
	    $section_id = $this->input->post('section_id');
	
	  	$q = "SELECT crs.* FROM ".$school_db.".class_routine_settings crs WHERE 
		      crs.school_id=".$school_id." and crs.is_active = 1";
	
		$routine  =  $this->db->query($q)->result_array();
		
   

        $statuslist = array(
            1 => 'present',
            2 => 'absent',
            3 => 'leave',
            4 => 'weekend'
        );
        $current_date = date("l");

        $routine1 = array();
        
        $q2 = " SELECT cr.*,cs.*,date_format(cr.period_start_time,'%H:%i')as period_start_time,date_format(cr.period_end_time,'%H:%i') as period_end_time FROM " . $school_db . ".class_routine cr 
          		right join " . $school_db . ".class_routine_settings cs on (cs.c_rout_sett_id=cr.c_rout_sett_id and cs.is_active = 1) where cs.school_id=" . $school_id. " 
          		and cs.section_id=" . $section_id. " ";
        
        $result = $this->db->query($q2)->result_array();
       
        if (sizeof($result) > 0)
        {

            $get_phrase =  get_phrase('time_table'); 

            $section_id = $section_id;
            if (sizeof($result) > 0)
            {
    
                    foreach ($result as $row)
                    {
                        $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['subject_id']          =  $row['subject_id'];
                        $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['class_routine_id']    =  $row['class_routine_id'];
                        $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['duration']            =  $row['duration'];
                        $routine1[$row['c_rout_sett_id']][$row['section_id']]['default_period_duration']                             =  $row['period_duration'];
                        $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['period_start_time']   =  $row['period_start_time'];
                        $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['period_end_time']     =  $row['period_end_time'];
                        $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['period_after_period'] =  ($row['break_duration_after_every_period'] > 0) ? $row['break_duration_after_every_period'] : 0;
                    }
                  
                  
                    $settings    = " select cs.* from " . $school_db . ".class_routine_settings cs where cs.school_id=" . $school_id . " and cs.is_active = 1
                                     and cs.section_id=" . $section_id . "  ";
                    $settingsRes = $this->db->query($settings)->result_array();

                    $cnt = 0;
                    $number_of_classes = 0;
                    foreach ($settingsRes as $row)
                    {
                        $cnt++;
                        $no_of_periods      =   $row['no_of_periods'];
                        $number_of_classes  =   $row['no_of_periods'];
                        $period_duration    =   $row['period_duration'];
                        $start_time         =   $row['start_time'];
                        $end_time           =   $row['end_time'];
                        $assembly_duration  =   $row['assembly_duration'];
                        $break_duration     =   $row['break_duration'];
                        $break_after_period =   $row['break_after_period'];
                        $c_rout_setting_id  =   $row['c_rout_sett_id'];
                        
                        $period_array       =   array();
                        $time_table['hierarchy']          =   $this->section_hierarchyy($row['section_id'], $school_id,$school_db);
                        // $time_table['get_phrase1'] =  get_phrase('timetable'); 
                        // $time_table['converted_date'] =     convert_date($row['start_date']) . ' to ' . convert_date($row['end_date']) ; 
                        // $time_table['secid'] =   $cnt . '-' . $row['section_id']; 
                        // $time_table['get_phrase2'] =    get_phrase('period'); 
                                
                        for ($i = 1;$i <= $no_of_periods;$i++)
                        {
                                $time_table['loop_count'][$i] =   $i ;
                        }
     
                        $count = 1;
                        $nn=1;
                        for ($d = 1;$d <= 6;$d++)
                        {
                            
                                $current            = "";
                                $current1           = "";
                              
                                
                                if ($d == 1)       $day = 'monday';
                                else if ($d == 2)  $day = 'tuesday';
                                else if ($d == 3)  $day = 'wednesday';
                                else if ($d == 4)  $day = 'thursday';
                                else if ($d == 5)  $day = 'friday';
                                else if ($d == 6)  $day = 'saturday';
                                else if ($d == 7)  $day = 'sunday';
                                $cur_teach_name = "";
                                
                                if (ucfirst($day) == $current_date)
                                {
                                   
                                    $cur_teach_name = "active_row_teacher_name";
                                    
                                }
                                $statuslist_css = "";
                           
                                $dd    = date("Y-m-d", strtotime($day . ' this week'));
                                $q1    = "select * from " . $school_db . ".holiday where start_date<='$dd' and end_date>='$dd' AND school_id=" . $school_id . " ";
                                $qurrr = $this->db->query($q1)->result_array();
                                
                                // $time_table['grde'] ="gradeA' . ' ' . $current . ' ' . $current1 . ' ' . $statuslist_css . '";
                                // $time_table['uc_first'] = ucfirst($day);
                                // $time_table['convrtdate'] =  convert_date(date("Y-m-d", strtotime($day . ' this week')));
                          
                            
                                if ($assembly_duration >= 0)
                                {
                                    $period     = strtotime($start_time) + strtotime(minutes_to_hh_mm($assembly_duration)) - strtotime('00:00');
                                    $period_new = date('H:i', $period);
                                }
                           
                                $mm=1;
                                
                                for ($i = 1;$i <= $no_of_periods;$i++)
                                {
                                
                                    $start = 0;
                                    $end   = 0;
              
                                    $val        = $i;
                                    $day        = strtolower($day);
                                    
                                    
                                     $check_for_subject_assign_day = $this->db->query(" select cr.*,cs.school_id,cs.c_rout_sett_id FROM ".$school_db.".class_routine cr 
                                	                                    RIGHT JOIN ".$school_db.".class_routine_settings cs on cs.c_rout_sett_id=cr.c_rout_sett_id 
                                	                                    WHERE cs.school_id=".$school_id." AND cr.day = '$day' AND cr.period_no = ".$val."
                                                                        AND cr.c_rout_sett_id = ".$c_rout_setting_id." ")->result_array();
                                                                     
                                    
                                    if($check_for_subject_assign_day){
                                    
                                    
                                    $subject_id = $routine1[$c_rout_setting_id][$section_id][$day][$val]['subject_id'];
                
                                    if (isset($routine1[$c_rout_setting_id][$section_id][$day][$val]['duration']) && $routine1[$c_rout_setting_id][$section_id][$day][$val]['duration'] > 0)
                                    {
                
                                        $duration = $routine1[$c_rout_setting_id][$section_id][$day][$val]['duration'];
                                        $break_duration_after_every_period = $routine1[$c_rout_setting_id][$section_id][$day][$val]['period_after_period'];
                                        $start = $period_new;
                                        
                                        if( $break_duration_after_every_period > 0)
                                        {
                                            if($i > 1){
                                                $start = strtotime($start) + strtotime(minutes_to_hh_mm($break_duration_after_every_period)) - strtotime('00:00');
                                            }
                                            else
                                            {
                                              $start = $routine1[$c_rout_setting_id][$section_id][$day][$val]['period_start_time']; 
                                            }
                                        }
                                        else
                                        {
                                            $start = $routine1[$c_rout_setting_id][$section_id][$day][$val]['period_start_time'];
                                        }
                                        
                
                                        $end = $routine1[$c_rout_setting_id][$section_id][$day][$val]['period_end_time'];
                                        
                                        
                                        $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($duration)) - strtotime('00:00');
                                        $period_new = date('H:i', $period_new);
                
                                        $start = date('h:i', strtotime($start));
                                        $end   = date('h:i', strtotime($end));
                                    
                                    
                                        $start_end['start_end'][$nn][$mm] =  $start . ' - ' . $end;
                                        
                                        $durtion['duration'][$nn][$mm]  =  " (" . $duration . " min) ";
            
                                    
                                    }
                                    else
                                    {  
                                        $start      = $period_new;
                					    $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($routine1[$c_rout_setting_id][$section_id]['default_period_duration'])) - strtotime('00:00');
                					    $start_show = date('h:i', strtotime($start));
                					    $period_new = date('h:i', $period_new);
                					    $end        = $period_new;
                						$start_show = $start_show .' - '.$end;
                					
                					//	$time_table['routine'][$nn][$mm]  =  " (".$routine1[$c_rout_setting_id][$section_id]['default_period_duration']." min) ";
                					
                                    }
        
                                    if(isset($subject_id) && $subject_id > 0)
                                    {
                                
                                        $get_cr_id = $this->db->query(" select cr.*,cs.school_id,cs.c_rout_sett_id FROM ".$school_db.".class_routine cr 
                                	                                    RIGHT JOIN ".$school_db.".class_routine_settings cs on cs.c_rout_sett_id=cr.c_rout_sett_id 
                                	                                    WHERE cs.school_id=".$school_id." AND cr.day = '$day' AND cr.period_no = ".$val."
                                                                        AND cr.c_rout_sett_id = ".$c_rout_setting_id." ")->result_array();
                                                                        // print_r( $get_cr_id);exit;
                                    
                                        $class_routine_id =  $routine1[$c_rout_setting_id][$section_id][$day][$val]['class_routine_id'];
                                        $compQuery        =  " select subject_components from ".$school_db.".class_routine where class_routine_id=".$class_routine_id."";
                                        $compRes          =  $this->db->query($compQuery)->result_array();
                                        $comps            =  $compRes[0]['subject_components'];
                                        
                                        foreach($get_cr_id as $get_cr_id_result){
                                            
                                        $query3           =  " select ttst.subject_teacher_id as teacher_id, sta.name AS teacher_name from ".$school_db.".time_table_subject_teacher ttst inner join ".$school_db.".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join ".$school_db.".staff sta on sta.staff_id=st.teacher_id inner join ".$school_db.".subject s on s.subject_id=st.subject_id where ttst.school_id=".$school_id."  and st.subject_id=".$get_cr_id_result['subject_id']." and class_routine_id=".$get_cr_id_result['class_routine_id']."";
                                        $res              =  $this->db->query($query3)->result_array();
                            
                            
                            
                                        $class_routine_id =   $get_cr_id_result['class_routine_id'];
                                   
                                        $subject['sn'][$nn][$mm]  =  $this->get_subject_namee($get_cr_id_result['subject_id'],$school_db);
                                        $subject['subject_components'][$nn][$mm]  = $this->subject_componentss($comps);
                                   
                                      //  $time_table['cur_teach_name'][$nn][$mm]  = $cur_teach_name; //echo
                                       
                                        $teachers=array();
                                        if(sizeof($res)>0)
                                        {
                                            foreach($res as $rows)
                                            {
                                                $teachers[]=$rows['teacher_name'];
                                                $teacher_id = $rows['teacher_id'];
                                            }
                                                    // print_r($teachers);
                                            $teacher['impl_tch'][$nn][$mm]  =  $teachers;
                                            if($day == strtolower(date("l")))
                                            {
                                                $current_t = date("H:i", strtotime("now"));
                                                        
                                                if( $break_duration_after_every_period > 0)
                                                {
                                                    if($i > 1)
                                                    {
                                                        $start_t   = strtotime($start) + strtotime(minutes_to_hh_mm($break_duration_after_every_period)) - strtotime('00:05');
                                                        $start_t   = date('H:i', $start_t);
                                                    }
                                                    else
                                                    {
                                                        $start_t   = date("H:i", (strtotime($start) - (5 * 60)));
                                                    }
                                                }
                                                else
                                                {
                                                    $start_t  = date("H:i", (strtotime($start) - (5 * 60)));
                                                }
                                                        
                                                $end_t = date("H:i", (strtotime($end)-(5 * 60)));
                                                $start_t   = date("H:i", (strtotime($current_t)-(5 * 60)));
                                                $end_t     = date("H:i", (strtotime($end)-(5 * 60)));

                                            }   
                            
                                        }
                                        
                                    } 
                                    
                                }
        
                                    if (($break_after_period > 0) && ($break_after_period == $i) && ($break_duration > 0))
                                    {
                                        $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration)) - strtotime('00:00');
                                        $period_new = date('H:i', $period_new);
                                    }
                                    
                                }
                                else{
                                    $subject['sn'][$nn][$mm]  =  'No subject added!';
                                    $subject['subject_components'][$nn][$mm]  = 'No components';
                                    $teacher['impl_tch'][$nn][$mm]  =  'No Teacher!';
                                    $durtion['duration'][$nn][$mm]  =  " (" . $duration . " min) ";
                                    $start_end['start_end'][$nn][$mm] =  $start . ' - ' . $end;
                                }

                                $mm++;  
                                }   //for loop
            
                            $nn++;
                        }                                   

                    }
                    
                
               }
            else
            {}
  
            $time_table['start_time'] = $start_time; //echo
            $time_table['period_duration'] = $period_duration;//echo
            $time_table['assembly_duration'] = $assembly_duration; //echo
            $time_table['break_duration'] = $break_duration; //echo
        }
        else
        {
          //time table not created
        }
        // exit;
        $page_data['timetable'] = $time_table['hierarchy'];
        $page_data['teacher'] = $teacher['impl_tch'];
        $page_data['durtion'] = $durtion['duration'];
        $page_data['subject'] = $subject['sn'];
        $page_data['start_end'] = $start_end['start_end'];
        $page_data['code'] = '200';
        $page_data['number_of_classes'] = $number_of_classes;
    	echo json_encode($page_data);		
		

	}

    
    function invoice()
    {
        $page_data = array();
   
         $student_id =  $this->input->post('student_id');
		 $school_db =  $this->input->post('school_db');
		 $fee_month =  $this->input->post('s_c_f_month');
		 $scf_id =  $this->input->post('s_c_f_id');
		 $fee_year =  $this->input->post('s_c_f_year');
	     $school_id =  $this->input->post('school_id');
	     $section_id =  $this->input->post('section_id');
	     $academic_year_id = $this->input->post('academic_year_id');
	     $month = $this->input->post('month');
	     $year = $this->input->post('year');
	 
	   if($month != null && $year != null){
	        $page_data['invoices'] = $this->db->query( "select scf.* from ".$school_db.".student_chalan_form scf
    			inner join ".$school_db.".class_chalan_form ccf on ccf.c_c_f_id=scf.c_c_f_id and ccf.section_id =".$section_id."
    			and ccf.type = 2 where scf.student_id= ".$student_id." and scf.school_id=".$school_id." and scf.status IN (4,5)
    			and scf.s_c_f_month=".$month." and scf.s_c_f_year=".$year."")->result_array();
    
	   }
	   
	   
	     if($month != null && $year == null){
	          	$year_arr = $this->academic_year_dates($academic_year_id,$school_db,$school_id);
	        $page_data['invoices'] = $this->db->query( "select scf.* from ".$school_db.".student_chalan_form scf
    			inner join ".$school_db.".class_chalan_form ccf on ccf.c_c_f_id=scf.c_c_f_id and ccf.section_id =".$section_id."
    			and ccf.type = 2 where scf.student_id= ".$student_id." and scf.school_id=".$school_id." and scf.status IN (4,5)
    			and scf.s_c_f_month=".$month." 	and ( DATE(scf.issue_date) between '".$year_arr->start_date."' and '".$year_arr->end_date."' ) ")->result_array();
    
	   }
	   
	    if($month == null && $year != null){
	          	$year_arr = $this->academic_year_dates($academic_year_id,$school_db,$school_id);
	        $page_data['invoices'] = $this->db->query( "select scf.* from ".$school_db.".student_chalan_form scf
    			inner join ".$school_db.".class_chalan_form ccf on ccf.c_c_f_id=scf.c_c_f_id and ccf.section_id =".$section_id."
    			and ccf.type = 2 where scf.student_id= ".$student_id." and scf.school_id=".$school_id." and scf.status IN (4,5)
    			and scf.s_c_f_year=".$year." 	and ( DATE(scf.issue_date) between '".$year_arr->start_date."' and '".$year_arr->end_date."' ) ")->result_array();
    
	   }
	   
	   
	   if($month == null && $year == null){
	       	$year_arr = $this->academic_year_dates($academic_year_id,$school_db,$school_id);
                // print_r($year_arr);exit;
            $page_data['invoices'] = $this->db->query( "select scf.* from ".$school_db.".student_chalan_form scf
    			inner join ".$school_db.".class_chalan_form ccf on ccf.c_c_f_id=scf.c_c_f_id and ccf.section_id =".$section_id."
    			and ccf.type = 2 where scf.student_id= ".$student_id." and scf.school_id=".$school_id." and scf.status IN (4,5)
    			and ( DATE(scf.issue_date) between '".$year_arr->start_date."' and '".$year_arr->end_date."' ) ")->result_array();
	   }
    
    
            // print_r($page_data['invoices']);exit;
            $total_dues=array();
            $payment_pending=array();
            $di=0;
            $date = date("Y-m-d");
            foreach($page_data['invoices'] as $row){
	             $total_dues[$di] = $this->get_student_challan_fee_details_for_parent1($row['student_id'],$row['s_c_f_month'], $row['s_c_f_year'], $row['s_c_f_id'],$school_db,$school_id); 
	             $payment_status = $this->db->query("SELECT * FROM ".$school_db.".payment_consumer WHERE challan_id = '".$row['s_c_f_id']."' AND DATE_FORMAT(inserted_at, '%Y-%m-%d') = '$date' ")->result_array();
                            if(count($payment_status) > 0)
                            {
                               $payment_pending[$di]='pending';
                            }else{
                                 $payment_pending[$di]='not pending';
                            }
	            $di++;
            }
            
    	$page_data['page_name']  = 'invoice';
    	$page_data['page_title'] = get_phrase('invoice_payment');
    	if($page_data)
            {
               
             
                 $response['code']= '200';
              
                 $response['page_data'] = $page_data;
                 $response['total_dues'] = $total_dues;
                 $response['payment_pending'] = $payment_pending;
                
                 echo json_encode($response);
                
            }
            else{
                $response['code']= '500';
                $response['error_message']= 'No data found!';
                echo json_encode($response);
                    
            }

        
     //  print_r($page_data);
    }
    function get_student_challan_fee_details_for_parent1($student_id , $fee_month=0 , $fee_year,$scf_id,$school_db,$school_id){

        $CI =& get_instance();
        $CI->load->database();
        
        if($fee_month == ""){
            $fee_month = 0;
        }
       
        $get_c_c_f_id = $CI->db->query("SELECT c_c_f_id,due_date FROM ".$school_db.".student_chalan_form WHERE s_c_f_id = $scf_id and school_id = '".$school_id."' and is_cancelled = 0 and status > 3 ")->row();
        $c_c_f_id     = $get_c_c_f_id->c_c_f_id;
        $my_month_year = date('Y-m',strtotime($get_c_c_f_id->due_date));
        
        $query = $CI->db->query("select scd.*,dl.discount_id from ".$school_db.".student_chalan_detail scd
                                        LEFT JOIN ".$school_db.".discount_list dl ON dl.fee_type_id = scd.type_id
                                        where scd.s_c_f_id=$scf_id and scd.type != 2 and scd.school_id=".$school_id);
        $discount_calculation = 0;
         $totle_amut=0;
         $single_discount_calculation=0;
        foreach($query->result_array() as $get_fee){
            $totle = $get_fee['amount'];
            
            $check_alread_discount = $CI->db->query("SELECT discount_amount_type,amount,title FROM ".$school_db.".student_fee_settings WHERE student_id = '$student_id' AND month = '$fee_month' AND year = '$fee_year' AND fee_type = 2 AND fee_type_id = '".$get_fee['discount_id']."' ");
            if($check_alread_discount->num_rows() > 0){
                $single_discount_data_temp = $check_alread_discount->result_array();       
                foreach($single_discount_data_temp as $single_disco){
                    if($single_disco['discount_amount_type'] == '1')
                    {
                       $single_discount_percent = $single_disco['amount'];
                    }else if($single_disco['discount_amount_type'] == '0'){
                       $single_discount_percent = round(($totle / 100) * $single_disco['amount']);
                    }
                    $single_discount_calculation += $single_discount_percent;
                }
            }
            
            $totle_amut += $totle;
        }
        
        // Arrears Display'
        $arreas_calculation = 0;
        $arreas_total_amount = 0;
        $get_arrears_query = $CI->db->query("SELECT * FROM ".$school_db.".student_chalan_form WHERE student_id = '$student_id' AND arrears_status = 1 AND s_c_f_id <> '$scf_id'");
        foreach($get_arrears_query->result_array() as $get_arrears_data):
            $make_format = '01-'.$get_arrears_data["s_c_f_month"].'-'.$get_arrears_data["s_c_f_year"];
            $arrears_month_year = date("M-Y",strtotime($make_format));
            $arrears_amount = $get_arrears_data["arrears"];
            $arreas_total_amount += $arrears_amount;    
        endforeach;
        // End Arrears
        
        $unpaid_challan_total_amount = 0;
        $get_unpaid_challan_query = $CI->db->query("SELECT * FROM ".$school_db.".student_chalan_form WHERE student_id = '$student_id' AND status = 4 AND form_type = 2 AND s_c_f_id <> '$scf_id' AND DATE_FORMAT(due_date, '%Y-%m') < '$my_month_year'");
        // echo $CI->db->last_query();
        foreach($get_unpaid_challan_query->result_array() as $get_unpaid_challan_data):
            $make_format = '01-'.$get_unpaid_challan_data["s_c_f_month"].'-'.$get_unpaid_challan_data["s_c_f_year"];
            $unpaid_challan_month_year = date("M-Y",strtotime($make_format));
            $unpaid_challan_amount = $get_unpaid_challan_data["actual_amount"];
            $unpaid_challan_total_amount += $unpaid_challan_amount;    
        endforeach;
        
        $arreas_calculation = $arreas_total_amount+$unpaid_challan_total_amount;
        $total_amount = $arreas_calculation+$totle_amut-$single_discount_calculation;
        return $total_amount;
    }
    function academic_year_dates($academic_year_id = 0, $school_db, $school_id)
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $q = "select * from " . $school_db . ".acadmic_year where academic_year_id = ".$academic_year_id." and school_id=" . $school_id." and status = 2 ";
        
        $arr = $CI->db->query($q)->row();
        
        return $arr;
    
    }
    
    function invoice_cart_proceed()
    {
        //$this->test();exit;
        $school_id         = $this->input->post("school_id");
        $s_c_f_ids         = $this->input->post("s_c_f_id");
        $school_db = $this->input->post("school_db");
        $payment_method_id = $this->input->post("payment_method_id");
        $sys_sch_id = $this->input->post("sys_sch_id");
       
    //   echo ' '.$school_id;
      echo ' '.$s_c_f_ids;
    //   echo ' '.$school_db;
      echo ' '.$payment_method_id;
    //   echo ' '.$sys_sch_id;
    //  exit;
       
        if($s_c_f_ids == ""){
            
            $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_created_yet'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
         
            $data    =  array("ClientID"=>"GoSW2vUrdfQL5W5", "ClientSecret"=>"XH6VeInJEKx6td0");
            $resp    =  $this->blinqapi->getToken("api/auth", $data);
          
           // print_r($resp);exit;
            $token_0 =  $resp[0];
            $token_1 =  $resp[1];
 
            $school_id = $school_id;
            $query_ary = $this->db->query("select *  from $school_db.student_chalan_form 
            where s_c_f_id IN($s_c_f_ids) and school_id = $school_id and is_cancelled = 0 and status > 3")->result_array();
           //  print_r($query_ary);exit;
            $challan_ids_array = array();
            $total_amount      = 0;
           
            $counter  = 0;
           
            foreach($query_ary as $row_data){
           
               
               $total_amt = $this->student_challan_fee_details_for_parent($row_data['student_id'],$row_data['s_c_f_month'], $row_data['s_c_f_year'], $row_data['s_c_f_id'],$school_db, $school_id);
               //echo $total_amt; exit;
               $total_amount += $total_amt; 
               
               array_push($challan_ids_array , $row_data['s_c_f_id']);
            }
          
            $challans_id = implode("," , $challan_ids_array);
            
             $student_row                        =  $this->_student_info($query_ary[0]['student_id'], $school_id, $school_db);
            //print_r($student_row);exit;
            $student_name                       =  $student_row[0]['student_name']; 
            $student_number                     =  $student_row[0]['mob_num'];
            $invoice                            =  $this->createInvoice($query_ary[0]['student_id'] , $challans_id , $student_name , $student_number , $total_amount , $payment_method_id);
           // print_r($invoice);exit;
          
            $page_data['invoice_details']       =  $query_ary;
            $page_data['page_name']             =  'invoice_detail';
        	$page_data['page_title']            =  get_phrase('invioce_details');
        	$page_data['token_string']          =  $token_0;
        	$page_data['token_timestamp']       =  $token_1;
        	$page_data['InvoiceNumber']         =  $invoice['InvoiceNumber'];
        	$page_data['PaymentCode']           =  $invoice['PaymentCode'];
        	$page_data['encryptedFormData']     =  $invoice['encryptedFormData'];
        	$page_data['PaymentVia']            =  $invoice['PaymentVia'];
        	$page_data['ProductionDescription'] =  $payment_method_id;
        	$page_data['SelectedOption']        =  $payment_method_id;

        //	$this->load->view('backend/index', $page_data);
      //  print_r($page_data);exit;
        	
        	
        }
        
    }
     function view_challan(){
        //   $s_c_f_id=$this->input->post('s_c_f_id');
        //   $school_id=$this->input->post('school_id');
        //   $school_db=$this->input->post('school_db');
        //   $typp=$this->input->post('type');
        $s_c_f_id=$this->uri->segment(3);
        $school_id=$this->uri->segment(4);
        $school_db=$this->uri->segment(5);
        $folder_name=$this->uri->segment(6);
        
          
          $page_data["query_ary"]=$this->db->query("select * from ".$school_db.".student_chalan_form 
          where s_c_f_id=$s_c_f_id and school_id=$school_id and is_cancelled = 0 and status>3")->result_array();
          $page_data['page_name']  = 'mobie_chalan_view';
          $page_data['school_id']  = $school_id;
          $page_data['school_db']  = $school_db;
          $page_data['folder_name']  = $folder_name;
          $page_data['page_type'] = 'single';
          $page_data['page_title'] = get_phrase('chalan_form');
          $this->load->view('backend/parent/mobile_chalan_view', $page_data);
    }
    
    
    
     function view_print_chalan(){
          $s_c_f_id=$this->input->post('s_c_f_id');
          $school_id=$this->input->post('school_id');
          $school_db=$this->input->post('school_db');
          $typp=$this->input->post('type');
          $folder_name=$this->input->post('folder_name');
          
          $response=array();

        if($s_c_f_id=="" && $typp==""){
            $response['code']=500;
            $response['msg']='challan not created yet';
        }
        else
        {
             $response['code']=200;
             $response['file_path']="https://dev.indiciedu.com.pk/mobile_webservices/view_challan/$s_c_f_id/$school_id/$school_db/$folder_name";
        }
         echo json_encode($response);
    }
    
    
    function get_school_detailss($d_school_id,$school_db)
    {
       
        $CI =& get_instance();
        $CI->load->database();
    
        $scl_name = $CI->db->query("select * from " . $school_db . ".school where 	sys_sch_id=$d_school_id")->result_array();
    
        $school_details = array();
        if (count($scl_name) > 0) {
            $school_details['name'] = $scl_name[0]['name'];
            $school_details['address'] = $scl_name[0]['address'];
            $school_details['logo'] = $scl_name[0]['logo'];
            $school_details['folder_name'] = $scl_name[0]['folder_name'];
            $school_details['email'] = $scl_name[0]['email'];
            $school_details['phone'] = $scl_name[0]['phone'];
        }
        return $school_details;
    }
    
     function download_challan(){
        $s_c_f_id=$this->input->post('s_c_f_id');
        $school_id=$this->input->post('school_id');
        $school_db=$this->input->post('school_db');
        $folder_name=$this->input->post('folder_name');
         $response=array();
         
          $page_data["query_ary"]=$this->db->query("select * from ".$school_db.".student_chalan_form 
          where s_c_f_id=$s_c_f_id and school_id=$school_id and is_cancelled = 0 and status>3")->result_array();
          if( $page_data["query_ary"]){
                  $page_data['page_name']  = 'mobie_chalan_view';
                  $page_data['school_id']  = $school_id;
                  $page_data['school_db']  = $school_db;
                  $page_data['page_type'] = 'single';
                  $page_data['folder_name'] = $folder_name;
                  $page_data['page_title'] = get_phrase('chalan_form');
                  $html_content=$this->load->view('backend/parent/mobile_chalan_download', $page_data,true);
                  $response['code']=200;
                  $response['html_content']=$html_content;
          }else{
               $response['code']=500;
          }
           echo json_encode($response);
     }
    
    function download_challan1(){
        $response=array();
        $s_c_f_id=$this->input->post('s_c_f_id');
        $school_id=$this->input->post('school_id');
        $school_db=$this->input->post('school_db');
        $data  = $this->db->query("select * from ".$school_db.".student_chalan_form where s_c_f_id = $s_c_f_id and school_id = $school_id and is_cancelled = 0 and status>3")->result_array();
      
        foreach($data as $row_data)
        {
           // echo $row_data['issue_date'];
            
            $query_a = $this->db->query("select scd.*,dl.discount_id from ".$school_db.".student_chalan_detail scd
                        LEFT JOIN ".$school_db.".discount_list dl ON dl.fee_type_id = scd.type_id
                        WHERE scd.s_c_f_id = $s_c_f_id and scd.type != 2 and scd.school_id =  ".$school_id." ")->result_array();
            
            if($row_data['status']>=4)
                {
                    $copy = array( 1=>"School Copy",2=>"Student Copy",3=>"Bank Copy");
            
            $html_content="<table width='100%' border='0'> 
                                <tr>";
                                 $query_logo=$this->db->query("select * from ".$school_db.".chalan_settings where school_id=".$school_id)->result_array();
                                  for($i=1; $i<=3; $i++){
                                   
                                   $html_content.="<td style='padding-right:10px'>
                                                     <table width='100%' border='1'>
                                                           <div class='watermark'>";
                                                            if($row_data['status']==5){
                                                                 $html_content.= "<span>Paid</span>";
                                                            }else{
                                                                 $html_content.= "<span class='text-danger'>Unpaid</span>";
                                                            }
                                            $html_content.= "  </div>
                                                            <tbody>
                                                        <tr>
                                                            <td style='border-right:none !important; width: 60px;'>";
                                                            
                                                            $d_school_id = $school_id;
                                                            $school_details = $this->get_school_detailss($d_school_id,$school_db);
                                                            $branch_name =  $school_details['name'];
                                                            $branch_logo =  $school_details['logo'];
                                                            $branch_folder =  $school_details['folder_name'];
                                                            
                                                            
                                             $html_content.= " </td>
                                                                <td style='border-left:none !important; padding-top:10px;'>
                                                                    <h4 style='font-size:14px !important;'>".$school_details['name']." </h4></td>
                                                            </tr>";
                                                            
                                                            
                                            $html_content.= " <tr>
                                                                <td colspan='2' align='center'>
                                                                    <p style='margin-top: 8px;'>".$school_details['address']."</p>
                                                                </td>
                                                            </tr>
                                                            <tr align='center'>
                                                                <td colspan='2'>
                                                                    <b>".$query_logo[0]['bank_details']."</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'>".get_phrase('chalan_no');
                                                                  $html_content.= ":".
                                                                    $row_data['chalan_form_number'].
                                    
                                                                    "<span style='float:right;'>"
                                    						            .$copy[$i].
                                    					            "</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>".get_phrase('month_year')."</td>
                                                                <td>".
                                                                     month_of_year($row_data['s_c_f_month']);
                                                                          $html_content.=  "-".
                                                                         $row_data['s_c_f_year'].
                                                                "</td>
                                                            </tr>
                                                            <tr>
                                                                <td>".get_phrase('roll_no');
                                                                  $html_content.= "</td>
                                                                <td>";
                                                                    $student_roll = $row_data['roll'];
                                                                      $html_content.= $student_roll.
                                                                
                                                                "</td>
                                                            </tr>
                                                            <tr>
                                                                <td>".get_phrase('name');
                                                                  $html_content.= "</td>
                                                                <td>";
                                                                    $student_name = $row_data[ 'student_name' ];
                                                                      $html_content.=  $student_name.
                                                                    
                                                                "</td>
                                                            </tr>
                                                            <tr>
                                                                <td width='35%'>".get_phrase('class');
                                                                  $html_content.= "-".get_phrase('sec');
                                                                $html_content.= "</td>
                                                                <td>".
                                                                     $row_data['class']." - ".$row_data['section'].
                                                                "</td>
                                                            </tr>
                                                            <tr>
                                                                <td>".get_phrase('issue_date');
                                                                  $html_content.= ":</td>
                                                                <td>";
                                                                  
                                                                    $date1=explode(' ',$row_data['issue_date']);
                                                                      $html_content.=  convert_date($date1[0]).
                                                                    
                                                                "</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td>".get_phrase('due_date');
                                                                  $html_content.= ":</td>
                                                                <td>";
                                                                
                                                                    $query_class_challan = $this->db->query("select due_days from ".$school_db.".class_chalan_form where c_c_f_id = ".$row_data['c_c_f_id']." And school_id=".$school_id)->result_array();
                                                                    $due_days = $query_class_challan[0]['due_days'];
                                                                      $html_content.=  date_view($row_data['due_date']);
                                                                 $html_content.= " </td>
                                    
                                                            </tr>
                                                            <tr>
                                                                <td width='100%'>". get_phrase('particulars');
                                                                  $html_content.= "</td>
                                                                <td  style=' text-align:right;'>".get_phrase('amount');
                                                                  $html_content.= "</td>
                                                            </tr>";
                                    $count_num = 1;
                                    $s_c_f_id=$row_data['s_c_f_id'];
                                    $my_month = date('m',strtotime($row_data['due_date']));
    
                                    $chalan="";
                                    $discount="";
                                    $arrears="";
                                    $totle=0;
                                    $related_ids = array();
                                    $discount_calculation = 0;
                                    $single_discount_calculation = 0;
                                    
                                     foreach($query_a as $rec_row1)
                                    {
                                        if($rec_row1['type']==1 || $rec_row1['type']==5)
                                        {
                                            $related_ids[$rec_row1['s_c_d_id']]['amount']=$rec_row1['amount'];
                                            $related_ids[$rec_row1['s_c_d_id']]['title']=$rec_row1['fee_type_title'];
                                            $html_content.= "<tr><td>".$rec_row1['fee_type_title']."</td>
                                                  <td  style=' text-align:right;' >".$rec_row1['amount']."</td></tr>";
                                            $totle=$rec_row1['amount']+$totle;
                                            
                                            /******************************
                                            //   Single Chalan Discount Area
                                            ********************************/
                                            if($rec_row1['discount_id'] != "" || $rec_row1['discount_id'] != NULL){
                                                $check_alread_discount = $this->db->query("SELECT discount_amount_type,amount,title FROM ".$school_db.".student_fee_settings WHERE student_id = '".$row_data['student_id']."' AND month = '$my_month' AND year = '".$row_data['s_c_f_year']."' AND fee_type = 2 AND fee_type_id = '".$rec_row1['discount_id']."' ");
                                                
                                                if($check_alread_discount->num_rows() > 0){
                                                    $single_discount_data_temp = $check_alread_discount->result_array();       
                                                    foreach($single_discount_data_temp as $single_disco){
                                                       if($single_disco['discount_amount_type'] == '1')
                                                       {
                                                           $single_discount_percent = $single_disco['amount'];
                                                       }else if($single_disco['discount_amount_type'] == '0'){
                                                           $single_discount_percent = round(($rec_row1['amount'] / 100) * $single_disco['amount']);   
                                                       }
                                                        $html_content.= "<tr>
                                                            <td>".$single_disco['title']."</td>
                                                            <td  style=' text-align:right;' >(".$single_discount_percent.")</td>
                                                        <tr>";
                                                        $single_discount_calculation += $single_discount_percent;
                                                    }
                                                }
                                            }
                                            
                                        }
                                        elseif($rec_row1['type']==2 || $rec_row1['type']==4)
                                        {
                                            // <td >'.$count_num.'</td>
                                            $html_content.="
                                                <td>".$rec_row1['fee_type_title']."</td>
                                                <td  style=' text-align:right;' >(".$rec_row1['amount'].")</td>";
                                            $totle=$totle-$rec_row1['amount'];
                                        }elseif($rec_row1['type']==3){
                                            // <td >'.$count_num.'</td>
                                            $html_content.="
                                                <td>".$rec_row1['fee_type_title']."</td>
                                                <td  style=' text-align:right;' >".$rec_row1['amount']."</td>";
                                            $totle=$totle+$rec_row1['amount'];
                                        }
                                        $count_num++;
                                    }
                                    $discount_calculation_merge = $discount_calculation+$single_discount_calculation;
                                    $test_t = $totle - $discount_calculation_merge;
                                    
                                     $html_content.= " <tr>
                                        <td><strong>Total Amount</strong></td>
                                        <td  style=' text-align:right;' ><strong> ".$test_t."</strong></td> 
                                    </tr>";
                                     $this->load->helper("num_word");
                                     
                                      $html_content.= "<tr>
                                        <td style='border-bottom: none !important; text-transform:capitalize;font-size: 12px;' colspan='2'><strong>In Words: </strong>"
                                        .convert_number_to_words($test_t)." Rupees<br />
                                        <span class='term_condition'>".nl2br($query_logo[0]['terms'])."</span></td>
                                    </tr>";
                                    
                                    $html_content.= " <tr>
                                    <td colspan='2' style='border-top:none !important;   border-bottom:none !important; font-size: 12px; '>";
                                        $admin_req1=get_user_info($row_data['issued_by']);
                                        $html_content.= "<span>". get_phrase('issued_by');
                                        $html_content.= ":".$query_logo[0]['school_name']."</span>
                                       <br><br><br>
                                       <br><br><br>
                                        <center>";
                                            //<img src="uploads/<?php echo $_SESSION['folder_name'] /student/<?= $row_data['bar_code']; ">
                                        $html_content.= "</center>
                                        <br><br>
                                        <p style='font-size: 8px !important; font-weight:bold; border-top:1px solid #000; margin-top:10px;'>".get_phrase('note');
                                        $html_content.= ":". 
                                         get_phrase('this_challan_form_is_computer_generated_and_does_not_require_any_signature');
                                       $html_content.= "</p>
                                       <br>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                      </td>";
                                  }
            $html_content.= "</tr>
            </table>";
            $response['code']=200;
            $response['html_content']=$html_content;
                }
            else
            {
                $response['code']=500;
                $response['msg']="chalan is not approved";
            }
            
        }
        //print_r($data);
       
        echo json_encode($response);
          
    }
    
    
    function _student_info($student_id=0, $school_id, $school_db)
    {

    $CI =& get_instance();
    $CI->load->database();
    
    
    $q = "select s.name as student_name, s.mob_num , s.email,s.student_id from ".$school_db.".student s
    where s.student_id = ".$student_id." and s.school_id=".$school_id." ";
    //echo $q;
    $student = $CI->db->query($q)->result_array();
    //print_r($student); exit;
    return $student;
}

    public function student_challan_fee_details_for_parent($student_id , $fee_month=0 , $fee_year, $scf_id, $school_db, $school_id){
        $CI =& get_instance();
        $CI->load->database();
        $single_discount_calculation=0;
         $totle_amut=0;
        if($fee_month == ""){
            $fee_month = 0;
        }
       
        $get_c_c_f_id = $CI->db->query("SELECT c_c_f_id,due_date FROM ".$school_db.".student_chalan_form WHERE s_c_f_id = $scf_id and school_id = '".$school_id."' and is_cancelled = 0 and status > 3 ")->row();
       
        $c_c_f_id     = $get_c_c_f_id->c_c_f_id;
        $my_month_year = date('Y-m',strtotime($get_c_c_f_id->due_date));
        
        $query = $CI->db->query("select scd.*,dl.discount_id from ".$school_db.".student_chalan_detail scd
                                        LEFT JOIN ".$school_db.".discount_list dl ON dl.fee_type_id = scd.type_id
                                        where scd.s_c_f_id=$scf_id and scd.type != 2 and scd.school_id=".$school_id);
        $discount_calculation = 0;

        foreach($query->result_array() as $get_fee){
            $totle = $get_fee['amount'];
            
            $check_alread_discount = $CI->db->query("SELECT discount_amount_type,amount,title FROM ".$school_db.".student_fee_settings WHERE student_id = '$student_id' AND month = '$fee_month' AND year = '$fee_year' AND fee_type = 2 AND fee_type_id = '".$get_fee['discount_id']."' ");
            if($check_alread_discount->num_rows() > 0){
                $single_discount_data_temp = $check_alread_discount->result_array();       
                foreach($single_discount_data_temp as $single_disco){
                    if($single_disco['discount_amount_type'] == '1')
                    {
                      $single_discount_percent = $single_disco['amount'];
                    }else if($single_disco['discount_amount_type'] == '0'){
                      $single_discount_percent = round(($totle / 100) * $single_disco['amount']);
                    }
                    $single_discount_calculation += $single_discount_percent;
                }
            }
            
            $totle_amut += $totle;
        }
        
        // Arrears Display'
        $arreas_calculation = 0;
        $arreas_total_amount = 0;
        $get_arrears_query = $CI->db->query("SELECT * FROM ".$school_db.".student_chalan_form WHERE student_id = '$student_id' AND arrears_status = 1 AND s_c_f_id <> '$scf_id'");
        foreach($get_arrears_query->result_array() as $get_arrears_data):
            $make_format = '01-'.$get_arrears_data["s_c_f_month"].'-'.$get_arrears_data["s_c_f_year"];
            $arrears_month_year = date("M-Y",strtotime($make_format));
            $arrears_amount = $get_arrears_data["arrears"];
            $arreas_total_amount += $arrears_amount;    
        endforeach;
        // End Arrears
        
        $unpaid_challan_total_amount = 0;
        $get_unpaid_challan_query = $CI->db->query("SELECT * FROM ".$school_db.".student_chalan_form WHERE student_id = '$student_id' AND status = 4 AND form_type = 2 AND s_c_f_id <> '$scf_id' AND DATE_FORMAT(due_date, '%Y-%m') < '$my_month_year'");
        // echo $CI->db->last_query();
        foreach($get_unpaid_challan_query->result_array() as $get_unpaid_challan_data):
            $make_format = '01-'.$get_unpaid_challan_data["s_c_f_month"].'-'.$get_unpaid_challan_data["s_c_f_year"];
            $unpaid_challan_month_year = date("M-Y",strtotime($make_format));
            $unpaid_challan_amount = $get_unpaid_challan_data["actual_amount"];
            $unpaid_challan_total_amount += $unpaid_challan_amount;    
        endforeach;
        
        $arreas_calculation = $arreas_total_amount+$unpaid_challan_total_amount;
        $total_amount = $arreas_calculation+$totle_amut-$single_discount_calculation;
        
        return $total_amount;
    }
    
    public function createInvoice($student_id , $challans_id , $name , $phone , $amount , $payment_method_id){
        
        $pre_values     =  array('ClientID' => BLINQ_CLIENT, 'ClientSecret' => BLINQ_SECRET);
        //  print_r($pre_values);exit;
        $token_response =  $this->blinqapi->getToken('api/auth', $pre_values);
        // echo "here";  print_r($token_response);exit;
        $token_string   =  $pre_values[0];// print_r($token_string);exit;
        $token_string   =  explode(' ',$token_string);
        $token_string   =  trim($token_string[1]);
        $token_header   =  array( "Token: {$token_string}" , "Content-Type: application/json" );
        $date           =  date('d/m/Y');

        $post_values = array(
            "ConsumerId"                =>  "",
            "InvoiceNumber"             =>  time(),
            "InvoiceAmount"             =>  $amount,
            "InvoiceDueDate"            =>  "{$date}",
            "InvoiceType"               =>  "Service",
            "IssueDate"                 =>  "{$date}",
            "InvoiceExpireAfterSeconds" =>  "180",
            "CustomerName"              =>  $name,
            "CustomerMobile1"           =>  $phone,
            "CustomerMobile2"           =>  "",
            "CustomerMobile3"           =>  "",
            "CustomerEmail1"            =>  "",
            "CustomerEmail2"            =>  "",
            "CustomerEmail3"            =>  "",
            "CustomerAddress"           =>  ""
        );
        
        $post_values      = json_encode($post_values);
        $invoice_response = $this->blinqapi->createInvoice('invoice/create', $token_header, $post_values);
        
        if($invoice_response['Status'] == "00"){
            
            $response['status']        = $invoice_response['Status'];
            $ResponseDetail            = $invoice_response['ResponseDetail'][0];
            $response['InvoiceNumber'] = $ResponseDetail['InvoiceNumber'];
            $response['TranFee']       = $ResponseDetail['TranFee'];
            $response['PaymentCode']   = $ResponseDetail['PaymentCode'];
            $response['Description']   = $ResponseDetail['Description'];
            $response['PaymentVia']    = ($payment_method_id == "Credit/Debit Card") ? "PAY" : "ACC";
            
            $sha256_ency = hash('sha256', BLINQ_CLIENT.$ResponseDetail['PaymentCode'].$ResponseDetail['InvoiceNumber']."https://indiciedu.com.pk/parents/paymentcallback".BLINQ_SECRET);
            
            $response['encryptedFormData'] = md5($sha256_ency);
            $response['error']             = false;
            
            $data_consumer = array(
                'consumer_id'    =>  $student_id,
                'challan_id'     =>  $challans_id,
                'school_id'      =>  $_SESSION['school_id'],
                'InvoiceNumber'  =>  $ResponseDetail['InvoiceNumber'],
                'PaymentCode'    =>  $ResponseDetail['PaymentCode'],
                'TranFee'        =>  $ResponseDetail['TranFee'],
                'PaymentMethod'  =>  $payment_method_id,
                'InvoiceAmount'  =>  $amount,
                'Description'    =>  $ResponseDetail['Description'],
                'Inserted_at'    =>  date('Y-m-d H:i:s') 
            );
            $this->db->insert(get_school_db().'.payment_consumer', $data_consumer);
            
            $data_consumer['sys_sch_id'] = $_SESSION['sys_sch_id'];
            $this->db->insert(get_system_db().'.payment_consumer_system', $data_consumer);
            
            
        }
        else{
            $response['status']       =  $invoice_response['Status'];
            $ResponseDetail           =  $invoice_response['ResponseDetail'][0];
            $response['Description']  =  $ResponseDetail ['Description'];
            $response['error']        =  true;
        }
        return $response;
    }
    
    
	function diary()
	{
	    
	    $response = array();
		$student_id   =  $this->input->post('student_id');
		$section_id =  $this->input->post('section_id');
	    $school_db =  $this->input->post('school_db');
		$school_id   =  $this->input->post('school_id');
		$sub_id       =  intval($this->input->post('subject_id'));
		$start_date   =  $this->input->post('start_date');
		$end_date     =  $this->input->post('end_date');
		if($start_date==null && $end_date == null && $sub_id==null){
    		$q = "select dr.diary_id as diary_id,st.name as teacher_name,sb.name as subject_name ,dr.assign_date as assign_date,dr.due_date as due_date,
    		    dr.title as title,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
    			FROM ".$school_db.".diary dr
    			INNER JOIN ".$school_db.".diary_student ds ON ds.diary_id = dr.diary_id
    			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = dr.section_id
    			INNER JOIN ".$school_db.".staff st ON st.staff_id = dr.teacher_id
    			INNER JOIN ".$school_db.".subject sb ON sb.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
                INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
    			((dr.admin_approvel='1') AND (dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$school_id.") ORDER BY dr.assign_date desc ";
		}elseif($start_date!=null && $end_date != null && $sub_id==null){
    		$q = "select dr.diary_id as diary_id,st.name as teacher_name,sb.name as subject_name ,dr.assign_date as assign_date,dr.due_date as due_date,
    		    dr.title as title,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
    			FROM ".$school_db.".diary dr
    			INNER JOIN ".$school_db.".diary_student ds ON ds.diary_id = dr.diary_id
    			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = dr.section_id
    			INNER JOIN ".$school_db.".staff st ON st.staff_id = dr.teacher_id
    			INNER JOIN ".$school_db.".subject sb ON sb.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
                INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
    			((dr.admin_approvel='1') AND (dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$school_id.") AND
    			( DATE(assign_date) between '".$start_date."' and '".$end_date."' ) ORDER BY dr.assign_date desc ";
		}elseif($start_date!=null && $end_date == null && $sub_id==null){
    		$q = "select dr.diary_id as diary_id,st.name as teacher_name,sb.name as subject_name ,dr.assign_date as assign_date,dr.due_date as due_date,
    		    dr.title as title,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
    			FROM ".$school_db.".diary dr
    			INNER JOIN ".$school_db.".diary_student ds ON ds.diary_id = dr.diary_id
    			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = dr.section_id
    			INNER JOIN ".$school_db.".staff st ON st.staff_id = dr.teacher_id
    			INNER JOIN ".$school_db.".subject sb ON sb.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
                INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
    			((dr.admin_approvel='1') AND (dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$school_id.") AND
    			( DATE(assign_date) >= '".$start_date."')  ORDER BY dr.assign_date desc ";
		}elseif($start_date==null && $end_date != null && $sub_id==null){
    		$q = "select dr.diary_id as diary_id,st.name as teacher_name,sb.name as subject_name ,dr.assign_date as assign_date,dr.due_date as due_date,
    		    dr.title as title,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
    			FROM ".$school_db.".diary dr
    			INNER JOIN ".$school_db.".diary_student ds ON ds.diary_id = dr.diary_id
    			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = dr.section_id
    			INNER JOIN ".$school_db.".staff st ON st.staff_id = dr.teacher_id
    			INNER JOIN ".$school_db.".subject sb ON sb.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
                INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
    			((dr.admin_approvel='1') AND (dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$school_id.") AND
    			( DATE(assign_date) <= '".$end_date."') ORDER BY dr.assign_date desc ";
		}elseif($start_date==null && $end_date == null && $sub_id!=null){
    		$q = "select dr.diary_id as diary_id,st.name as teacher_name,sb.name as subject_name ,dr.assign_date as assign_date,dr.due_date as due_date,
    		    dr.title as title,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
    			FROM ".$school_db.".diary dr
    			INNER JOIN ".$school_db.".diary_student ds ON ds.diary_id = dr.diary_id
    			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = dr.section_id
    			INNER JOIN ".$school_db.".staff st ON st.staff_id = dr.teacher_id
    			INNER JOIN ".$school_db.".subject sb ON sb.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
                INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
    			((dr.admin_approvel='1') AND (dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$school_id.") AND
    			( dr.subject_id = '".$sub_id."') ORDER BY dr.assign_date desc ";
		}elseif($start_date!=null && $end_date != null && $sub_id!=null){
    		$q = "select dr.diary_id as diary_id,st.name as teacher_name,sb.name as subject_name ,dr.assign_date as assign_date,dr.due_date as due_date,
    		    dr.title as title,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
    			FROM ".$school_db.".diary dr
    			INNER JOIN ".$school_db.".diary_student ds ON ds.diary_id = dr.diary_id
    			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = dr.section_id
    			INNER JOIN ".$school_db.".staff st ON st.staff_id = dr.teacher_id
    			INNER JOIN ".$school_db.".subject sb ON sb.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
                INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
    			((dr.admin_approvel='1') AND (dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$school_id.") AND
    			( DATE(assign_date) between '".$start_date."' and '".$end_date."' ) AND
    			( dr.subject_id = '".$sub_id."') ORDER BY dr.assign_date desc ";
		}elseif($start_date!=null && $end_date == null && $sub_id!=null){
    		$q = "select dr.diary_id as diary_id,st.name as teacher_name,sb.name as subject_name ,dr.assign_date as assign_date,dr.due_date as due_date,
    		    dr.title as title,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
    			FROM ".$school_db.".diary dr
    			INNER JOIN ".$school_db.".diary_student ds ON ds.diary_id = dr.diary_id
    			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = dr.section_id
    			INNER JOIN ".$school_db.".staff st ON st.staff_id = dr.teacher_id
    			INNER JOIN ".$school_db.".subject sb ON sb.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
                INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
    			((dr.admin_approvel='1') AND (dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$school_id.") AND
    			( DATE(assign_date) >= '".$start_date."')  AND
    			( dr.subject_id = '".$sub_id."') ORDER BY dr.assign_date desc ";
		}elseif($start_date==null && $end_date != null && $sub_id!=null){
    		$q = "select dr.diary_id as diary_id,st.name as teacher_name,sb.name as subject_name ,dr.assign_date as assign_date,dr.due_date as due_date,
    		    dr.title as title,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
    			FROM ".$school_db.".diary dr
    			INNER JOIN ".$school_db.".diary_student ds ON ds.diary_id = dr.diary_id
    			INNER JOIN ".$school_db.".class_section cs ON cs.section_id = dr.section_id
    			INNER JOIN ".$school_db.".staff st ON st.staff_id = dr.teacher_id
    			INNER JOIN ".$school_db.".subject sb ON sb.subject_id = dr.subject_id
                INNER JOIN ".$school_db.".class c ON c.class_id = cs.class_id
                INNER JOIN ".$school_db.".departments d ON d.departments_id = c.departments_id WHERE 
    			((dr.admin_approvel='1') AND (dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$school_id.") AND
    			( DATE(assign_date) <= '".$end_date."') AND
    			( dr.subject_id = '".$sub_id."') ORDER BY dr.assign_date desc ";
		}
		else{}
       
        $diary_count = $this->db->query($q)->result_array();
        
		$page_data['diary'] =$diary_count;
		$diary_content = array();
		$t=0;
		foreach($page_data['diary'] as $diary)
		{
    		  $qur = "select d.* , d.diary_id as d_id , aud.* , ds.* from ".$school_db.".diary d  
            left join ".$school_db.".diary_audio aud on aud.diary_id = d.diary_id
            left join ".$school_db.".diary_student ds on d.diary_id = ds.diary_id
            where d.diary_id = ".$diary['diary_id']."  and d.school_id = ".$school_id." "; 
           
            $diary_arr = $this->db->query($qur); 
            $arr = $diary_arr->row();
          
            
            if(($diary_arr->num_rows()) > 0) {
           
          
            $diary_content[$t]['attachment'] = $arr->attachment;
    
            }else
            {
                
               $diary_content[$t]['attachment'] = 'no';
            }
            $t++;
		}
		
		$subject_array = $this->db->query(" select sub.* from ".$school_db.".subject sub
    	                          inner join ".$school_db.".subject_section ss on sub.subject_id=ss.subject_id 
    	                          where ss.section_id = ".$section_id."
     	                          and ss.school_id = ".$school_id." ")->result_array();
     	$page_data['subject_array'] = $subject_array; 
      
       
        if($page_data['subject_array'])
        {
         
            $response['code']= '200';
            $response['diary'] = $page_data['diary'];
            $response['diary'] = $page_data['diary'];
            $response['diary_content'] = $diary_content;
            $response['subject_array'] = $page_data['subject_array'];
            
           
            
        }
        echo json_encode($response);
	
	
	}
	function month_year($start_date, $end_date, $selected_date = '')
        {
            $start_date = date('1 M Y', strtotime($start_date));
            //$end_date = date('1 M Y', strtotime($end_date));
            $months = array();
        
            while (strtotime($start_date) <= strtotime($end_date)) {
                $months[] = array(
                    'year' => date('Y', strtotime($start_date)),
                    'month' => date('m', strtotime($start_date))
                );
        
                $start_date = date('d M Y', strtotime($start_date .
                    '+ 1 month'));
            }
        // print_r($start_date);
            // $arrlength = count($months);
            // $counter = 1;
            // $str = array();
        
            // $retr = "<option value=''>" . get_phrase('select_year_month') . "</option>";
            // $selected = '';
        
            // foreach ($months as $key => $value) {
            //     $month_year = date("F - Y", mktime(0, 0, 0, $value['month'], 1, $value['year']));
            //     if ($key == 0) {
            //         $month_first = $value['month'] . "," . $value['year'];
            //     }
            //     $dte = "'" . $value['month'] . "-" . $value['year'] . "'";
            //     if ($dte == "'" . $selected_date . "'")
            //         $selected = 'selected';
            //     else
            //         $selected = '';
            //     $retr .= "<option value='" . $value['month'] . "-" . $value['year'] . "' $selected>" . month_of_year($value['month']) . "-" . $value['year'] . "</option>";
            // }
            return $months;
            // return $retr;
}
    
function get_teacher($teacher_id = 0,$sch_db = "",$sch_id = 0)
{
    // $CI =& get_instance();
    // $CI->load->database();
    /*
    $q="SELECT name FROM ".get_school_db().".teacher
    WHERE teacher_id=$teacher_id AND school_id=".$_SESSION['school_id']."";
    */
    // if($sch_db == ""):
    //   // $sch_db = get_school_db();
    // endif;
    
    // if($sch_id == 0):
    //   //  $sch_id = $_SESSION['school_id'];
    // endif;
    
    $q = "SELECT s.name FROM ".$sch_db.".staff s
		INNER JOIN ".$sch_db.".designation d 
		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
		WHERE s.staff_id=$teacher_id AND s.school_id=" .$sch_id. " ";
    $teacher = $this->db->query($q)->result_array();
    $teacher_name = $teacher[0]['name'];
    return $teacher_name;
}



	function getdiarycontent() {
        
        $data = array();
        
        $diaryb_id = $this->input->post('diaryb_id');
        $school_id = $this->input->post('school_id');
        $school_db = $this->input->post('school_db');
        $qur = "select d.* , d.diary_id as d_id , aud.* , ds.* from ".$school_db.".diary d  
        left join ".$school_db.".diary_audio aud on aud.diary_id = d.diary_id
        left join ".$school_db.".diary_student ds on d.diary_id = ds.diary_id
        where d.diary_id =  $diaryb_id and d.school_id = ".$school_id." "; 
       
        $diary_arr = $this->db->query($qur); 
        $arr = $diary_arr->row();
         // print_r($arr);
        
        if(($diary_arr->num_rows()) > 0) {
        $data['title'] = $arr->title;
        $tt = explode("<p>", $arr->task);
        $ttt = explode("</p>", $tt[1]);
        // print_r($tt);
        // print_r($ttt);
        $data['task_detail'] = $ttt[0];
        $data['teacher'] = $this->get_teacher($arr->teacher_id, $school_db,$school_id);
        $data['assign_date'] = date_view($arr->assign_date);
        $data['due_date'] = date_view($arr->due_date);
        $data['attachment'] = $arr->attachment;
        $data['audio'] = $arr->audio;
        // print_r($data['assign_date']);
         //print_r($arr->attachment);exit;
        
         if($arr->is_submitted == 1){
                            $data['diary_status']['submission_date'] = date_vie($arr->submission_date);
                                   
                          
                        }elseif($arr->due_date < date('Y-m-d') ) {
                             $data['diary_status']['submission_date'] = 'Submission Time Expired';
                           
                        }else{
                               $data['diary_status']['submission_date'] = 'Not submitted yet';
                           
                        }
                 
                        // if ($arr->audio != ""){
                        //     $audio_path = base_url()."uploads/".$_SESSION['folder_name']."/diary_audios/".$arr->audio;
                        //     $data .= '<tr><td>
                        //             <strong>Audio : </strong> <br>
                        //             <audio controls>
                        //               <source src="'.$audio_path.'" type="audio/ogg">
                        //               <source src="'.$audio_path.'" type="audio/mpeg">
                        //             </audio>
                        //         </td></tr>';
                        // }
                        
                        // if ($arr->attachment != ""){
                        //     $attachment_path = base_url()."uploads/".$_SESSION['folder_name']."/diary/".$arr->attachment;
                        //     $data .= '<tr><td>
                        //             <b>Diary Attachment</b><br>
                        //             <a target="_blank" href="'.$attachment_path.'"><i class="fas fa-paperclip"></i> Click to open the Link</a>
                        //             </td></tr>';
                        // }
                        
                        // if($arr->is_submitted == 1){
                        //     $data .= '<tr>
                        //         <td class="">
                        //         <b>Diary Status</b><br>
                        //         <div style="color:green;">
                        //             <strong>Submission Date : </strong>
                        //             '.date_view($arr->submission_date).'
                        //         </div>
                        //     </td>';
                        // }elseif($arr->due_date < date('Y-m-d') ) {
                        //     $data .= '<td class="">
                        //                 <b>Diary Status</b><br> 
                        //                 <span style="color:red;">Submission Time Expired</span>
                        //             </td>';
                        // }else{
                        //     $data .= '<td class="">
                        //     <b>Diary Status</b><br><br>
                        //         <a href="'.base_url().'student_p/solve_assignment/'.$arr->d_id.'" class="modal_save_btn" target="_blank">Click here to solve assignment</a>
                        //     </td>';
                        // }
                        
                        
        
        }else
        {
            
           $data = '';
        }
        $response['code'] = '200';
        $response['diary_content'] =$data;
        echo json_encode($response);
        //print_r( $data);
    }
    
    function downloaddiarycontent() {
        
        $data = array();
        
        $diaryb_id = $this->input->post('diaryb_id');
        $school_id = $this->input->post('school_id');
        $school_db = $this->input->post('school_db');
        $qur = "select d.* , d.diary_id as d_id , aud.* , ds.* from ".$school_db.".diary d  
        left join ".$school_db.".diary_audio aud on aud.diary_id = d.diary_id
        left join ".$school_db.".diary_student ds on d.diary_id = ds.diary_id
        where d.diary_id =  $diaryb_id and d.school_id = ".$school_id." "; 
       
        $diary_arr = $this->db->query($qur); 
        $arr = $diary_arr->row();
         // print_r($arr);
        
        if(($diary_arr->num_rows()) > 0) 
        {
            $data['attachment'] = $arr->attachment;
            
            if($data['attachment'])
            {
                $response['code'] = '200';
                $response['diary_content'] =$data;
            }
            else
            {
                $response['code'] = '500';
                $response['msg'] = 'no attactment';
            }
        }else
        {
            $response['code'] = '500';
            $response['msg'] = 'no diary found';
           $data = '';
        }
       
        echo json_encode($response);
        //print_r( $data);
    }
	function manage_attendance($date = '',$month = '',$year = '',$class_id = '')
	{
	    $student_id = $this->input->post('student_id');
	    $school_id = $this->input->post('school_id');
	    $school_db = $this->input->post('school_db');
	    $section_id = $this->input->post('section_id');
	    $academic_year_id = $this->input->post('academicYear');
	    $presentDate = $this->input->post('presentDate');
	   
	    $get_month = date_parse_from_format("Y-m-d", $presentDate);
	
	  
	    $presentDate = $this->input->post('presentDate');

	    $date_sbubmitted = explode('-',$presentDate);
	    
	  
	   
	    
        $month_of_date = $get_month["month"];
        $year_of_date = $date_sbubmitted[0]; 
        
        $month=date('n');
        $year=date('Y');
        $y=date('Y');
        $monthName = date('F', mktime(0, 0, 0, $month_of_date, 10));
       
       
        $select_for_dropdown = array();
        $acadmic_year_start = $this->db->query("select start_date from ".$school_db.".acadmic_year where academic_year_id =".$academic_year_id." and school_id=".$school_id." ")->result_array();
        $selected = date('m',strtotime($monthName)).'-'.$year_of_date;
        $select_for_dropdown = $this->month_year($acadmic_year_start[0]['start_date'], date('Y-m-d'), $selected); 
       
       
        $a= month_option_list("month","form-control",$month); //list of months i.e january to december
        $b= year_option_list("year","form-control",($y-1),($y+1),$year);  //selected year
     
        
        $monthName = date('F', mktime(0, 0, 0, $month, 10));
           
        $show_year =   $year; 
	    
	    
	    // First day of the month.
        $start_date_of_month =  date('Y-m-01', strtotime($presentDate));
        // Last day of the month.
       
        $last_date_of_month = date('Y-m-t', strtotime($presentDate));
	    $acadmic_year_start = $this->db->query("select start_date from $school_db.acadmic_year where academic_year_id = $academic_year_id and school_id = $school_id ")->result_array();


        $date_month= $month;//date();// $_POST['month'];
        $date_year=$year;//$_POST['year'];
        $stud_id=$student_id;//$_POST['section_id'];
        $d=cal_days_in_month(CAL_GREGORIAN,$month_of_date,$year_of_date);
        
       
        
         
        $month_array = array();
        $k=1;
        for($i=1; $i<=$d; $i++)
        {
             if($i<=9)
            {
                $i = '0'.$i;
            }
           
            
            $month_array[$k]['status'] = '';
            $month_array[$k]['comments']['category'] = '';
            $month_array[$k]['comments']['status'] = '';
            $month_array[$k]['comments']['approval_date'] = '';
            $month_array[$k]['comments']['description'] = '';
            
           
            $month_array[$k]['date'] = $year_of_date.'-'.$month_of_date.'-'.$i;
            $month_array[$k]['formatdate'] = $i.'-'.date('M',  mktime(0, 0, 0, $month_of_date, 10)).'-'.$year_of_date;
            $dt = strtotime($month_array[$k]['date']);
            $dayy = strtolower(date("l", $dt));
            
            if($dayy == 'saturday' || $dayy == 'sunday')
            {
               
                $month_array[$k]['status'] = 4;
            }
            $month_array[$k]['day'] = $dayy;
            $k++;
        }
      
	 
       
        
        $q="select a.status,a.date FROM ".$school_db.".attendance a

        WHERE 
        a.student_id='$stud_id' 
        AND a.date>='$start_date_of_month' 
        AND a.date<='$last_date_of_month' 
        AND a.school_id='$school_id'
        ";
        
        $qur_red=$this->db->query($q)->result_array();
        $attendence_array = array();
        $z=1;
        for($i = 0; $i< count($qur_red); $i++)
        {
            $attendence_array[$z]['date'] = $qur_red[$i]['date'];
            $attendence_array[$z]['status'] = $qur_red[$i]['status'];
            $z++;
        }
        
        
        // echo '<pre>';
        // // print_r($attendence_array);
        // print_r($month_array);
       
        for($i = 1; $i<= count($attendence_array); $i++)
        {
            for($j=1; $j<=count($month_array); $j++)
            { 
                if($attendence_array[$i]['date'] == $month_array[$j]['date'])
                {
                   
                    $month_array[$j]['status'] = $attendence_array[$i]['status'];
                    if($month_array[$j]['status'] == 3)
                    {
                        
                      
                            $leave_date = date('Y-m-d', strtotime($attendence_array[$i]['date']));
                            $leave_qry = "select * from ".$school_db.".leave_student 
                                where student_id='$stud_id' 
                                and (DATE('".$leave_date."') between start_date and end_date)
                                and school_id=".$school_id." ";
                           
                            $leave_arr = $this->db->query($leave_qry)->result_array();
                             for($k=0; $k<count($leave_arr); $k++)
                             {
                                    $month_array[$j]['comments']['category'] = $this->leave_category($leave_arr[$k]['leave_category_id'],$school_db,$school_id);
                                  
                                    $show_status = get_phrase('status');
                                   
                                    if($leave_arr[$k]['status']==0){
                                      $month_array[$j]['comments']['status'] = get_phrase("pending");
                                   
                                    } 
                                    if($leave_arr[$k]['status']==1){
                                      $month_array[$j]['comments']['status'] = get_phrase("approved");
                                  
                                    }
                                    if($leave_arr[$k]['status']==2){
                                      $month_array[$j]['comments']['status'] = get_phrase("rejected");
                                  
                                    }
                    
                                    $month_array[$j]['comments']['approval_date'] = get_phrase('approval_date');
                                   
                                    $process_date = '';
                                    if($leave_arr[$k]['process_date'] != '0000-00-00')
                                        { 
                                            $month_array[$j]['comments']['approval_date'] =  convert_date($leave_arr[$k]['process_date']);
                                        }
                         
                                    $month_array[$j]['comments']['description'] = $leave_arr[$k]['reason']; 
                             }
                        
                            
                           
                        
                    }
                }
            }
        }
       
       
        if($month_array){
            $response['code'] = '200';
            $response['attendence'] =  $month_array;
            $response['months_dropdown'] =  $select_for_dropdown;
        }
        else{
              $response['code'] = '500';
              $response['msg'] = 'No Attendence Found!';
        }
        
        echo json_encode($response);
	
	
	}
	
	function leave_category($leave_categ_id,$school_db,$school_id)
    {
        $CI =& get_instance();
        $CI->load->database();
    
        $q = "SELECT name FROM " . $school_db . ".leave_category
    	                    WHERE leave_category_id=$leave_categ_id AND school_id=" . $school_id . "";
        $leave = $CI->db->query($q)->result_array();
        $leave_categ_name = $leave[0]['name'];
        return $leave_categ_name;
    }
	
	
    function attendence_summary()
    {
        $i = 1;
        $school_db = $this->input->post('school_db');
        $academic_year_id = $this->input->post('academicYear');
        $school_id = $this->input->post('school_id');
        $student_id = $this->input->post('student_id');
        
        $acadmic_year_start = $this->db->query("select start_date,end_date from ".$school_db.".acadmic_year where academic_year_id =".$academic_year_id." and school_id=".$school_id." ")->result_array();
        // print_r($acadmic_year_start);
        // exit;
        $start_date=$acadmic_year_start[0]['start_date'];
        $end_date=$acadmic_year_start[0]['end_date'];
        
        $array_attend=array();
        $q="select status,count(a.status) as status_count,month(a.date) as month, YEAR(a.date) as year,monthname(a.date) as month_name FROM ".$school_db.".attendance a 
        WHERE a.student_id=".$student_id."
        AND a.school_id=".$school_id."
        group by status, month, year
        order by  month, year";
        
        $qur_red=$this->db->query($q)->result_array();
      
        $qur_array=array();
        $data_array = array();
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
            echo json_encode($response);
        }
        else{
              $response['code'] = '500';
              $response['msg'] = 'No Attendence Found!';
              echo json_encode($response);
        }

	
    }
    
    function teacher_list()
    {
        $section_id = $this->input->post('section_id');
        $school_db= $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $student_id = $this->input->post('student_id');
        $yearly_term_id = $this->input->post('yearly_term_id'); //4
        
        
        
        $newqry = "SELECT user_login_detail_id FROM ".$school_db.".student WHERE student_id =".$student_id;
        $std_login_detail_id = $this->db->query($newqry)->row();
          $array_data = array();
       
        
    
        $sub_arr = $this->db->query("select sub.* from ".$school_db.".subject sub inner join ".$school_db.".subject_section ss on sub.subject_id=ss.subject_id where ss.section_id = ".$section_id." and ss.school_id = ".$school_id." ")->result_array();
        // print_r($sub_arr);exit;
        $teacher_arr1 = $this->db->query("select staff.staff_id as teacher_id, staff.user_login_detail_id as user_id,staff.name, staff.staff_image as teacher_image from " . $school_db . ".class_routine cr inner join " . $school_db . ".class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id and crs.section_id = $section_id inner join " . $school_db . ".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id inner join " . $school_db . ".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join " . $school_db . ".staff on staff.staff_id = st.teacher_id where staff.school_id = " . $school_id . " GROUP BY staff.name ")->result_array();
        // print_r($teacher_arr1);exit;
        $subject_arr = array();
       
        $i=1;
         foreach($teacher_arr1 as $teacher_arr){
              $teacher_id = $teacher_arr['teacher_id'];
              
              
              	$teacherid_array = "SELECT user_login_detail_id FROM $school_db.staff WHERE staff_id = ".$teacher_id;
        		$teacher_login_detailed_id = $this->db->query($teacherid_array)->row();
        		$array_data[$i]['teacher_login_detailed_id']  = $teacher_login_detailed_id->user_login_detail_id ?? '0' ;
        	    $array_data[$i]['student_login_detail_id'] = $std_login_detail_id->user_login_detail_id ?? '0' ;

                         if($teacher_arr['teacher_image']==""){ 
                            $show_default_msg =  get_default_pic();
                         }else{
                             $show_user_pic =  display_link($teacher_arr['teacher_image'],'staff');
                         }
                  
                       $array_data[$i]['teacher'] = $teacher_name =  $teacher_arr['name'];
                       $array_data[$i]['teacherid']  =  $teacher_arr['teacher_id'];
                        // print_r(' Teacher Name'.$teacher_name.' ');
                  
                        
                        // subjects start
                      
                                $user_login_detail_id = $teacher_arr['user_id'];
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
                     
                            $j=1;
                        	foreach($subject_arr as $row)
                        	{

                        	    // Unread Messages
                                $unread_arr = $this->db->query("select count(*) as unread
                                            from ".$school_db.".messages
                                            WHERE student_id = ".$student_id."
                                            and school_id=".$school_id."
                                            and messages_type = 0
                                            and is_viewed = 0 
                                            and teacher_id = '$teacher_id'
                                            and subject_id = ".$row['subject_id']."
                                            ")->row();
                                $unread_msgs = $unread_arr->unread;
                                //  print_r(' Unread_msgs '.$unread_msgs.' ');
                                $unread_counters = 0;
                                if($unread_msgs > 0)
                                {
                                    $unread_counters = $unread_msgs;
                                }
                                
                               
                        	     $subjecttt =  $row['name'].' - '.$row['code'].' '.$unread_counters;
                        	     $array_data[$i]['Subject'][$j]['subject_name'] = $row['name'];
                        	     $array_data[$i]['Subject'][$j]['subject_code'] = $row['code'];
                        	     $array_data[$i]['Subject'][$j]['unread_msgs'] = $unread_counters;
                        	     $array_data[$i]['Subject'][$j]['teacher_id'] = $teacher_arr['teacher_id'];
                        	     $array_data[$i]['Subject'][$j]['subject_id'] = $row['subject_id'];
                        	$j++;	 
                        	}
                $i++;
                }
        $messages = array();
        $messages['data'] = $array_data;
        $response = array();  
        if($array_data)
        {
            $response['code'] = '200';
            $response['messages'] =  $messages['data'];
        }
        else{
              $response['code'] = '500';
              $response['msg'] = 'No Messages Found!';
        }
        echo json_encode($response);
    }   
    
    function message()
	{
	    $page_data['school_db'] =   $school_db = $this->input->post('school_db');
	    $page_data['section_id'] =   $section_id = $this->input->post('section_id');
	    $page_data['school_id'] =  $school_id = $this->input->post('school_id');
	    $page_data['teacher_id'] =  $teachers_id = $this->input->post('teacher_id');
	    $page_data['subject_id'] =   $subject_id = $this->input->post('subject_id');
	    $page_data['academic_year_id'] =   $academic_year_id = $this->input->post('academic_year_id');
	    $page_data['student_id'] =   $student_id = $this->input->post('student_id');
	    


		$this->db->query("update ".$school_db.".messages set is_viewed=1 WHERE teacher_id=$teachers_id and student_id=$student_id and subject_id=$subject_id and school_id=$school_id and messages_type=0");

		$qr    = "select m.* from ".$school_db.".messages m inner join ".$school_db.".student s on s.student_id=m.student_id
			       where m.teacher_id=$teachers_id and m.student_id=$student_id and m.subject_id=$subject_id and s.section_id=".$section_id." 
			       and m.school_id=$school_id  and  s.academic_year_id = ".$academic_year_id." ORDER BY m.message_time ASC";
		$query = $this->db->query($qr);

        
		$page_data['parent_message_id']   = 0;
		$page_data['previous_message_id'] = 0;
		$c = 0;
		$rows_1 = array();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $rows)
			{
			   
				$data[] = $rows;
				if($c == 0){
					$c = 1;
					$page_data['parent_message_id'] = $rows->parent_message_id;

					if($page_data['parent_message_id'] == 0)
					{
						$page_data['parent_message_id'] = $rows->messages_id;
					}
					$page_data['previous_message_id'] = $rows->messages_id;
				}
			}
			$rows_1 = $data;

		}
		
		$page_data['subject_id']  = $subject_id ;
		
		$teacherid_array = "SELECT user_login_detail_id FROM $school_db.staff WHERE staff_id = ".$teachers_id;
		$teacher_login_detailed_id = $this->db->query($teacherid_array)->row();
		$page_data['teacher_login_detailed_id']  = $teacher_login_detailed_id->user_login_detail_id ?? '0' ;
		$page_data['teachers_id'] = $teachers_id ;
		
		
		$newqry = "SELECT user_login_detail_id FROM ".$school_db.".student WHERE student_id =".$student_id;
        $std_login_detail_id = $this->db->query($newqry)->row();
         
        $page_data['student_login_detail_id'] = $std_login_detail_id->user_login_detail_id ?? '0' ;
		

		$msg = array();
	    if(count($rows_1)>0)
		{
		    $i=1;
            foreach($rows_1 as $rr)
			{
				if($rr->messages_type==0)
                {
                    // $msg[$i]['teacher_name'] = get_teacher_name($teachers_id,$school_db,$school_id);
                    $msg[$i]['from'] = 'teacher';
                   
                	$msg[$i]['msg'] =	$rr->messages;
                	$msg[$i]['date'] =	date('d-m-y h:i A', strtotime($rr->message_time)); 
                	$msg[$i]['onlydate'] =$rr->message_time; 
				}
                else
				{
				    $msg[$i]['from'] = 'parent';
				   
                  	$msg[$i]['msg'] =	$rr->messages;
            	    $msg[$i]['date'] =	date('d-m-y h:i A', strtotime($rr->message_time)); 
            	    $msg[$i]['onlydate'] =	$rr->message_time; 
				}$i++;
			}
		}

        $response = array();
	    $page_data['messages'] = $msg;
	    if($page_data)
        {
            $response['code'] = '200';
            $response['data'] =  $page_data;
        }
        else{
              $response['code'] = '500';
              $response['msg'] = 'No Messages Found!';
        }
        echo json_encode($response);
	}
	
	function message_send()
    {
    		
    		$teacher_id             =   $this->input->post('teacher_id');
    		$subject_id             =   $this->input->post('subject_id');
    		$school_id              =   $this->input->post('school_id');
    		$school_db              =   $this->input->post('school_db');
    		$student_id             =   $this->input->post('student_id');
    		$login_detail_id        =   $this->input->post('login_detail_id');
    		$previous_message_id    =   $this->input->post('previous_message_id');
    		$parent_message_id      =   $this->input->post('parent_message_id');
    		$message                =   $this->input->post('message');
    // 		$yearly_term_id         =   $this->input->post('yearly_term_id');
    
    
    
    		$student_name                =  $this->get_student_name($student_id,$school_id,$school_db); 
    // 		$msg                         =  $data['messages'];
    // 		$student_id                  =  $school_db;
    		
    		$data['messages']            =  $message;
    		$data['subject_id']          =  $subject_id;
    		$data['is_viewed']           =  0;
    		$data['previous_message_id'] =  $previous_message_id;
    		$data['parent_message_id']   =  $parent_message_id;
    		$data['teacher_id']          =  $teacher_id;
    		$data['student_id']          =  $student_id;
    		$data['school_id']           =  $school_id;
    		$data['messages_type']       =  1;//parent
    		$data['sent_by']             =  intval($login_detail_id);
    		$data['message_time']        =  date('Y-m-d H:i:s');
    		
    		$qry = $this->db->insert($school_db.'.messages',$data);
    // 			print_r($this->db->last_query());exit;
    		$response = array();
    	    if($qry)
    	    {
    	        $response['code'] = '200';
    	    }
    	    else{
    	        $response['code'] = '500';
    	    }
    		
    		echo json_encode($response);
    // 		$device_id  =   $this->get_user_device_id(3 , $teacher_id , $school_id);
    	
    //         $title      =   "New Message";
    //         $message    =   "A New Message Has been Sent By Parent.";
    //         $link       =    base_url()."Mobile_APIs/Mobile_Webservices/student_list";
    //      $re =    $this->sendNotificationByUserId($device_id, $title, $message, $link , $teacher_id , 3,$school_db,$school_id);
    // 		print_r($re);exit;
    // 		$this->session->set_flashdata('club_updated', get_phrase('message_send_successfully '));
    // 		redirect(base_url() . 'parents/message/'.$teacher_id.'/'.$subject_id);
    }
    function sendNotificationByUserId($device_id, $title, $message, $link , $userId , $userTypeId,$school_db,$school_id){
       
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
        
        $school_id = $school_id;
        $data_not = array(
            'user_id'     => $userId ,
            'user_type'   => $type,
            'url'         => $link ,
            'inserted_at' => date('Y-m-d'),
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
    function get_user_device_id($user_type , $user_id , $school_id="")
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
           
              $device_id = $this->db->query("select device_id from " . get_system_db() . ".user_devices ".$filter." = $user_id AND school_id=" . $school_id . "")->row();
               
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
     function student_list()
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        
        $sub_in = 0;
        /*$subject = array();
        $d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
        $sub_arr = get_teacher_section_subjects($d_c_s_sec); //get section subjects
        if ( count($sub_arr) > 0 )
        {
            foreach ($sub_arr as $value) 
            {
                $subject[] = $value['subject_id']; 
            }
        }

        $time_table_t_sub = get_time_table_teacher_subject($login_detail_id, $yearly_term_id);
       
        if (count($time_table_t_sub) > 0)
        {
            $sub_in = implode(',', array_unique(array_merge($time_table_t_sub, $subject)));
        }
        elseif (count($subject)>0)
        {
            $sub_in = implode(',',array_unique($subject));
        }*/

        //teacher sections
        $d_c_s_sec = array();//get_teacher_dep_class_section($login_detail_id);
        $time_table_t_sec = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
        $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        $page_data['teacher_section'] = $teacher_section;

        //filter
        $filter_subject_id = intval($this->input->post('subject'));
        $dep_c_s_id = intval($this->input->post('dep_c_s_id'));
        
        if ( $filter_subject_id > 0 ) 
        {
            $sub_in = $filter_subject_id;
            $page_data['subject_id_selected'] = $filter_subject_id;
            $page_data['filter'] = true;
        }
        elseif ($dep_c_s_id > 0)
        {
            $sub_arr = get_teacher_diary_subject($login_detail_id, $dep_c_s_id);
            $page_data['dep_c_s_filter'] = $dep_c_s_id;
            $page_data['filter'] = true;

            //subjects in
            $sub_arr = get_teacher_diary_subject($login_detail_id, $dep_c_s_id);
            if (count($sub_arr['ids']) > 0)
                $sub_in = implode(',', $sub_arr['ids']);
        }

        $page_data['student_list'] = $this->db->query("select s.student_id,s.image, s.name as student_name, sub.subject_id, sub.name as subject_name, sub.code as subject_code, sub.subject_id 
                        from ".get_school_db().".student s
                        inner join ".get_school_db().".class_section cs on s.section_id = cs.section_id
                        inner join ".get_school_db().".subject_section ss on ss.section_id = s.section_id 
                        inner join ".get_school_db().".subject sub on sub.subject_id = ss.subject_id 
                        where s.student_status in (".student_query_status().") and sub.subject_id in ($sub_in)
                        and ss.section_id = $dep_c_s_id and s.school_id=".$_SESSION['school_id']." order by s.name")->result_array();
        $page_data['section_id']  = $dep_c_s_id;
        $page_data['page_name']  = 'student_listing';
        $page_data['page_title'] = get_phrase('Messages');
        $this->load->view('backend/index', $page_data);
    }
    
    
    // helper function for subjects
    function get_subject_sallybus($subject_id,$school_db,$school_id)
    {
  
        $display = array();
        $get_sallybus = $this->db->where("subject_id",$subject_id)->where("school_id",$school_id)->get($school_db.".subject_sallybus");
         $i=1;
        if($get_sallybus->num_rows() > 0) {
           
            foreach($get_sallybus->result() as $row):
                $year_title = $this->academic_year_title($row->academic_year_id,$school_db,$school_id);
                // $modal = "showAjaxModal('".base_url()."modal/popup/view_sallybus_modal/".$row->subject_sallybus_id."')";
                $display[$i]['syl'] = 'Syllabus - '.$year_title[0]['title'];    
                $display[$i]['subject_sallybus_id'] = $row->subject_sallybus_id;  
                
                // $tt = explode("<p>", $row->sallybus_data);
                // $ttt = explode("</p>", $tt);
                $display[$i]['sallybus_data'] = $row->sallybus_data; 
                $i++;
            endforeach;
        }
        else{
            $display[$i]['syl'] = 'No syllabus added yet!';
            $display[$i]['subject_sallybus_id'] = '';  
            $i++;
        }
        return $display;
}

    function academic_year_title($acad_year,$school_db,$school_id)
    {
    $CI =& get_instance();
    $query = $CI->db->query("SELECT title FROM " . $school_db . ".acadmic_year WHERE academic_year_id=" . $acad_year . " AND school_id=" . $school_id . " ")->result_array();
    return $query;
}
    
    function subjects()
	{
	    $school_db = $this->input->post('school_db');
	    $section_id = $this->input->post('section_id');
	    $folder_name = $this->input->post('folder_name');
	    $school_id = $this->input->post('school_id');
	    $subject_array = $this->db->query(" select sub.* from ".$school_db.".subject sub
    	                          inner join ".$school_db.".subject_section ss on sub.subject_id=ss.subject_id 
    	                          where ss.section_id = ".$section_id."
     	                          and ss.school_id = ".$school_id." ")->result_array();
     	                        
     	  $i=1;              
     	  foreach($subject_array as $row)
     	  {
     	      $page_data[$i]['subject_name'] = $row['name'] . '-' . $row['code'];
     	      $page_data[$i]['subject_id'] = $row['subject_id'];
     	      $page_data[$i]['syllabus'] = $this->get_subject_sallybus($row['subject_id'],$school_db,$school_id);
     	      $i++;
     	  }
     	   
     	  
		$response = array();
		if($page_data)
		{
		    $response['code'] = '200';
		    $response['subjects'] = $page_data;
		}
		else{
		     $response['code'] = '500';
		     $response['message'] = 'No Subject Found!';
		}
		
		echo json_encode($response);
	
	}
	public function get_single_subject_syllabus_in_webappview()
	{
	    
	    $school_db = $this->input->post('school_db');
	    $subject_syllabus_id = $this->input->post('subject_syllabus_id');
	    $subject_id = $this->input->post('subject_id');
	    $folder_name = $this->input->post('folder_name');
	    $academic_year_id = $this->input->post('academic_year_id');
	    $school_id = $this->input->post('school_id');
	    
	    
	    
	    $page_data['code'] = '200';
	    $page_data['link_path'] = "https://dev.indiciedu.com.pk/mobile_webservices/return_syllabus_view/$subject_syllabus_id/$subject_id/$school_id/$folder_name/$academic_year_id/$school_db";
	    echo  json_encode($page_data);
	    
	   
	}
	
	public function return_syllabus_view()
	{
	    $data_array= array();
	    $data_array ['subject_syllabus_id'] = $this->uri->segment(3);
	    $data_array ['subject_id']  = $this->uri->segment(4);
	    $data_array ['school_id']  = $this->uri->segment(5);
	    $data_array ['folder_name']  = $this->uri->segment(6);
	    $data_array ['academic_year_id']  = $this->uri->segment(7);
	    $data_array ['school_db']  = $this->uri->segment(8);
	      $data_array['page_name']  = 'view_syllabus_modal_mobile';
	      $data_array['page_type'] = 'single';
          $data_array['page_title'] = get_phrase('view_syllabus_modal_mobile');
        //   echo json_encode($data_array['school_db']);exit;
	   //  $this->load->view('backend/parent/view_syllabus_modal_mobile',$data_array);
	       $this->load->view('backend/admin/view_syllabus_modal_mobile', $data_array);
	}
	
// 	function get_subject_sallybuss()
//     {
//         $school_db = $this->input->post('school_db');
// 	    $subject_id = $this->input->post('subject_id');
// 	    $school_id = $this->input->post('school_id');
	   
//     $display = '';
  
//     $get_sallybus = $this->db->where("subject_id",$subject_id)->where("school_id",$school_id)->get($school_db.".subject_sallybus");
   
//     if($get_sallybus->num_rows() > 0) :
      
//         foreach($get_sallybus->result() as $row):
            
//             $year_title = $this->academic_year_titlee($row->academic_year_id,$school_db,$school_id);
//             // $modal = "showAjaxModal('".base_url()."modal/popup/view_sallybus_modal/".$row->subject_sallybus_id."')";
//             $this->popup("modal/popup/view_syllabus_modal_mobile/".$row->subject_sallybus_id);
//             $display .= $modal;    
//         endforeach;
//     endif;   
//     echo $display;
//     }

// function academic_year_titlee($acad_year,$school_db,$school_id)
// {
  
//     $query = $this->db->query("SELECT title FROM " . $school_db . ".acadmic_year WHERE academic_year_id=" . $acad_year . " AND school_id=" . $school_id . " ")->result_array();
//     return $query;
// }
	
	
	
// 	function popup($page_name = '' , $param2 = '' , $param3 = '',$param4 = '',$root = 0)
// 	{
// 		$account_type =	get_login_type_folder($_SESSION['login_type']);
// 		$page_data['param2']		=	$param2;
// 		$page_data['param3']		=	$param3;
//         $page_data['param4']		=	$param4;
// 		$exp_ary                    =   explode(':',$page_name);
        
//         if($root == 1)
//         {
//             $this->load->view( 'backend/'.$page_name.'.php' ,$page_data);	
//         }else{
//             if(isset($exp_ary[1]) && $exp_ary[1]!=""){
//                 $this->load->view( 'backend/'.$exp_ary[1].'/'.$exp_ary[0].'.php' ,$page_data);	
//             }
//             else{
//                 $this->load->view( 'backend/'.$account_type.'/'.$page_name.'.php' ,$page_data);	
//             }
//         }
// 		echo '<script src="'.base_url().'assets/js/neon-custom-ajax.js"></script>';
// 		echo '<script src="'.base_url().'assets/js/common.js"></script>';
// 		echo '<script src="'.base_url().'assets/js/common.js"></script>';
// 	}
	
    function exam_routine($param1 = '', $param2 = '', $param3 = '')
    {
    	    $school_db = $this->input->post('school_db');
            $section_id = $this->input->post('section_id');
            $school_id = $this->input->post('school_id');
            $student_id = $this->input->post('student_id');
            $academic_year_id = $this->input->post('academic_year_id');
    		$yearCheck='';
            $termCheck='';
    		
    		$q="select e.*, y.title as yearly_term
                from ".$school_db.".exam e 
                inner join ".$school_db.".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id 
                inner join ".$school_db.".acadmic_year ay on ay.academic_year_id=y.academic_year_id 
                where e.school_id=".$school_id."
                and ay.academic_year_id=".$academic_year_id."
                order by e.start_date DESC";
                $exams = $this->db->query($q)->result_array();
                // print_r($exams);exit;
                
            $f=1;  
            $Exams = array();
    	    foreach($exams as $row){
    	 
    	    $Exams[$f]['term_name'] = $row['yearly_term'].' - '.$row['name'];
    	    $Exams[$f]['date'] = '('.convert_date($row['start_date']).' to '.convert_date($row['end_date']).')';
    	    
    	    $Exams[$f]['current_date'] = date('d-M-Y'); 
    	    $date_from = strtotime($row['start_date']);
            $date_to = strtotime($row['end_date']);
            $oneDay = 60*60*24;
            for($i=$date_from; $i<=$date_to; $i=$i+$oneDay)
                {
                    $day= convert_date(date('Y-m-d',$i));
                    $date1= date('Y-m-d',$i);
                    $dd=date("l", $i);
                    $qurrr=$this->db->query("select * from ".$school_db.".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$school_id." ")->result_array();
                   
                    if(count($qurrr)>0){
                        // $current1=$custom_css[2];
                        }

                    $q="select er.* from ".$school_db.".exam_routine er 
                            where er.school_id=".$school_id." 
                            and er.exam_id=".$row['exam_id']." 
                            and section_id=".$section_id."";
                            $routines=$this->db->query($q)->result_array();
                            
                    $j = 1;
                    foreach($routines as $row2){
                                        
                        $Exams[$f][$j]['exam_routine_id'] = $row2['exam_routine_id'];
                        if(strtotime($row2['exam_date']) == $i){
                            
                            $Exams[$f][$j]['day']           =           $dd;
                            $Exams[$f][$j]['date']          =           $row2['exam_date'];
                            $Exams[$f][$j]['subject_name']  =           $this->get_subject_namee($row2['subject_id'],$school_db);
                            $Exams[$f][$j]['time']          =           '('.$row2['time_start'].'-'.$row2['time_end'].')'; 
                            
                        }
                    $j++ ;                 
                    }
               	
                }
    	    $f++;
    	}
   
    	$response = array();
	    if($Exams)
	    {
	       $response['code'] = '200';
	       $response['DateSheet'] = $Exams;
	    }
	    else{
	        $response['code'] = '500';
	        $response['msg'] = 'No DateSheet Found!';
	    }
        echo json_encode($response);
    
    }
        
    function marks($exam_id = '', $class_id = '', $subject_id = '')
	{
		
		
		$school_db = $this->input->post('school_db');
		$student_id =  $this->input->post('student_id');
		$section_id = $this->input->post('section_id');
		$academic_year_id = $this->input->post('academicYear');
		$school_id = $this->input->post('school_id');
	
		
		$option_list = $this->year_exam_option_list($academic_year_id, $school_id, $school_db);
// 
		$response = array();
		if($option_list)
		{
		    $response['code'] =  '200';
		  //  $response['term'] =  ;
		    $response['exam'] =  $option_list;
		    
		}
		else{
		  $response['code'] =  '500';
		  $response['exam'] =  'No exam Found!';
		}
	    echo json_encode($response);
		
	}
	function get_exam_result()
	{
		$student_id = $this->input->post('student_id');	
		$school_db = $this->input->post('school_db');
		$school_id = $this->input->post('school_id');
		$exam_id   = $this->input->post('exam_id');
		$academic_year_id = $this->input->post('academicYear');
		
		$q="select m.*,e.start_date, e.end_date, e.name as exam_name from ".$school_db.".marks m 
			inner join ".$school_db.".marks_components mc on mc.marks_id=m.marks_id 
			inner join ".$school_db.".exam e on m.exam_id=e.exam_id 
			inner join ".$school_db.".exam_routine er on e.exam_id=er.exam_id
			where  m.exam_id=".$exam_id." and m.student_id=".$student_id." and m.school_id=".$school_id." and er.is_approved=1 group by m.subject_id";
		$result = $this->db->query($q)->result_array();
// 		print_r($this->db->last_query());exit;
		
	
		
		$std_res_data = array();
		$i = 1;
		foreach($result as $row)
		{
		    $std_res_data[$i]['marks_id'] =$row['marks_id']; 
		    $std_res_data[$i]['student_id'] = $this->get_student_name ($row['student_id'],$school_id,$school_db); 
		    $std_res_data[$i]['subject_id'] = $this->get_subject_namee($row['subject_id'],$school_db); 
		    $std_res_data[$i]['exam_id'] =$row['exam_id']; 
		    $std_res_data[$i]['attendance'] =$row['attendance']; 
		    $std_res_data[$i]['comment'] =$row['comment']; 
		    $std_res_data[$i]['school_id'] =$row['school_id']; 
		    $std_res_data[$i]['start_date'] =$row['start_date']; 
		    $std_res_data[$i]['end_date'] =$row['end_date']; 
		    $std_res_data[$i]['exam_name'] =$row['exam_name']; 
		    $i++;
		}
		
		
		
		$student_data = $this->get_student_details($student_id,$school_db);
        $get_exam = $this->get_exam_term_name($exam_id,$school_db,$school_id,$academic_year_id); 
        $get_parent = $this->get_parent_details($student_data[0]['parent_id'],$school_id,$school_db);
        
        
	    $result_sheet = array();
		$result_sheet['roll_no'] =  $student_data[0]['roll'];
		$class_section = $this->section_hierarchy($student_data[0]['section_id'],$school_id,$school_db);
        $result_sheet['class_name'] = $class_section['c']. ' - ' . $class_section['s'];
        $result_sheet['student_name'] = ucfirst($student_data[0]['name']);
        $result_sheet['parent_name'] = ucfirst($get_parent->p_name);
        $result_sheet['exam'] = $get_exam[0]['exam_name'].' ('. $get_exam[0]['term'].')'; 


        $subject  = array();
        $j = 1;
        $obt = 0; 
        $total = 0;
        if($result){
        foreach($result as $arr)
        {
            $obt += $this->get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id'],$school_db,$school_id);
            $total += $this->get_total_marks($exam_id,$student_data[0]['section_id'],$arr['subject_id'],$school_db,$school_id);
            
            $subject[$j]['subject_name'] =   $this->get_subject_namee($arr['subject_id'],$school_db,0);
            $subject[$j]['total_marks'] =   $total_marks =  $this->get_total_marks($exam_id,$student_data[0]['section_id'],$arr['subject_id'],$school_db,$school_id);
            $subject[$j]['marks_obtained'] =       $marks_obtained = $this->get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id'],$school_db,$school_id);
            $percent_obtained = ($marks_obtained/$total_marks*100);
          
            // $subject[$j]['subject_percentage'] = round($percent_obtained,2);
            $subject[$j]['subject_percentage'] = number_format((float)$percent_obtained, 2, '.', '');
            $subject[$j]['grade'] =  $this->get_grade($percent_obtained,$school_db,$school_id);
        $j++;
        }
        $result_sheet['subjects'] = $subject;
        $result_sheet['grand_total_marks'] = $total;
        $result_sheet['grand_obtained_marks'] = $obt;
        $total_percentage = ($obt/$total*100);
        $result_sheet['grand_percentage'] = number_format((float)$total_percentage, 2, '.', '');
        $result_sheet['grand_grade'] = $this->get_grade($total_percentage,$school_db,$school_id);
        if($result_sheet)
		{
    		$data['code'] = '200';
    // 		$data['student_id'] = $student_id;
    // 		$data['exam_id']    = $exam_id;
    		$data['result']     = $result_sheet;
    		echo json_encode($data);
    	}
    	else
    	{
    	    $data['code'] = '500';
    	    $data['msg'] = 'No result found';
    	    echo json_encode($data);
    	    
    	}
        }
        else{
           $data['code'] = '500';
    	    $data['msg'] = 'No result found';
    	    	echo json_encode($data);
        }
        //	echo json_encode($result_sheet);exit;
		
	
// 		$this->load->view('backend/parent/ajax/result', $data);
	}
	
	function get_grade($marks='',$school_db,$school_id)
    {
      
        if($marks!='')
        {
            $q="select grade_point from ".$school_db.".grade where ".$marks.">=mark_from and ".$marks."<=mark_upto AND school_id=".$school_id."";
            $resMarks=$this->db->query($q)->result_array();
            return $resMarks[0]['grade_point'];
        }
        else return false;
    }
    function get_total_marks($exam_id,$section_id,$subject_id,$school_db,$school_id)
    {
       
        $total_marks = 0;
        $q1 = "select sum(percentage) as percentage 
    		from ".$school_db.".marks m
    		inner join ".$school_db.".exam e on e.exam_id=m.exam_id
    		inner join ".$school_db.".subject_components sc on sc.subject_id=m.subject_id
    		 where 
    		 sc.subject_id=".$subject_id." and 
    		 e.exam_id=".$exam_id." and 
    		 sc.school_id=".$school_id."
    		 group by marks_id
             limit 1 ";
        $query = $this->db->query($q1);
    
        if ($query->num_rows() > 0)
        {
            $result = $query->row();
            foreach ($result as $value)
            {
                $total_marks = $total_marks + $value;
            }
        }
        else
        {
            $q="select total_marks from ".$school_db.".exam_routine where exam_id=".$exam_id." and subject_id=".$subject_id." and section_id=".$section_id." and school_id=".$school_id."";
            $resMarks=$this->db->query($q)->result_array();
            $total_marks = $resMarks[0]['total_marks'];
        }
    
        return $total_marks;
    
    }
	
	function get_total_obtained($exam_id,$marks_id,$subject_id,$school_db,$school_id)
    {
        
        $q="select sum(mc.marks_obtained) as obtained from ".$school_db.".marks_components mc inner join ".$school_db.".marks m on m.marks_id=mc.marks_id where mc.marks_id=".$marks_id." and m.subject_id=".$subject_id." and m.exam_id=".$exam_id." and m.school_id=".$school_id."";
        $resMarks=$this->db->query($q)->result_array();
        return $resMarks[0]['obtained'];
    }
    function get_student_details($student_id,$school_db)
    {
        $q = "select * from " . $school_db . ".student where student_id=$student_id";
      
        $sectionArr = $this->db->query($q)->result_array();
    
        return $sectionArr;
    }
    
    function get_parent_details($parent_id=0,$school_id,$school_db)
    {
       
        $query  = "SELECT * FROM ".$school_db.".student_parent where s_p_id = ".$parent_id." AND school_id = ".$school_id." ";
        $result = $this->db->query($query)->row();
        return $result;
        
    }
    function get_exam_term_name($exam_id = 0,$school_db,$school_id,$academic_year_id)
    {
        $str = '';
        $str.='<option  value="">'.get_phrase('select_exam').'</option>';
        $result = $this->db->query("select distinct e.name as exam_name,e.exam_id as exam_id, e.start_date as exam_start_date,e.end_date as exam_end_date, t.start_date,t.end_date,t.title as term,t.yearly_terms_id 
    		from  ".$school_db.".yearly_terms t 
    		INNER JOIN ".$school_db.".exam e on t.yearly_terms_id=e.yearly_terms_id
    	 	WHERE 
    	 	t.school_id=".$school_id." AND 
    	 	t.academic_year_id=".$academic_year_id." AND 
    	 	t.status in (2,3) AND 
    	 	t.is_closed = 0 AND
    	 	e.exam_id = $exam_id
    	 	order by e.exam_id
    	 	")->result_array();
    	return $result; 	
    }
    function section_hierarchy($section, $d_school_id = 0,$school_db)
    {
        if ($section == '') {
            $section = 0;
        }
        $school_id = "";
        if (!empty($d_school_id)) {
            $school_id = $d_school_id;
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

    function year_exam_option_list($academic_year_id, $school_id, $school_db)
    {
        $str = '';
       
        $str.='<option  value="">'.get_phrase('select_exam').'</option>';
        $result = $this->db->query("select distinct e.name as exam_name,e.exam_id as exam_id, e.start_date as exam_start_date,e.end_date as exam_end_date, t.start_date,t.end_date,t.title as term,t.yearly_terms_id 
    		from  ".$school_db.".yearly_terms t 
    		INNER JOIN ".$school_db.".exam e on t.yearly_terms_id=e.yearly_terms_id
    	 	where 
    	 	t.school_id=".$school_id." and 
    	 	t.academic_year_id=".$academic_year_id." and 
    	 	t.status in (2,3) and 
    	 	t.is_closed = 0
    	 	order by e.exam_id
    	 	")->result_array();
 
        $term_arr = array();
        foreach($result as $res)
        {
            
            $term_arr['result'][] = array(
                'exam_id'  	 => $res['exam_id'],
                'exam_name'  => $res['exam_name'],
                'start_date' => date('d-M-Y',strtotime($res['exam_start_date'])),
                'end_date'   => date('d-M-Y',strtotime($res['exam_end_date'])),
                'term' => $res['term'],
            );
    
        }
    
        foreach ($term_arr as $outer_key => $outer)
        {
            $str.='<optgroup label="'.$outer_key.'">';
    
            foreach ($outer as $key => $value)
            {
                $str.='<option value="'.$value['exam_id'].'">'.$value['exam_name'].' ('.$value['start_date'].' to '.$value['end_date'].')'.'</option>';
            }
        }
    
        $str.= '</optgroup>';
    // $term_arr['term'] = $result[0]['term'];
        return $term_arr;
    } 
        
        
        
        
    function get_type_name_by_id($type="",$type_id='',$school_db,$school_id)
	{
		 return $this->db->select('name')->get_where($school_db.'.'.$type,array($type.'_id'=>$type_id,'school_id' =>$school_id))->row();
	}
	
	function file_upload_funt($main_folder_name="",$file_name = "", $folder_name = "", $prefix = "", $is_root = 0)
    {
        $path = 'uploads/' . $main_folder_name. '/' . $folder_name;
    
        if ($file_name == "") {
            return "";
        }elseif ($is_root == 1) {
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
	
    function manage_leaves($param1 = '', $param2 = '')
	{

	    $operation = $this->input->post('operation');
		$school_id =  $this->input->post('school_id');
		$school_db = $this->input->post('school_db');
		$student_id = $this->input->post('student_id');
		$folder_name = $this->input->post('folder_name');
		$proof='';
		if($operation == 'create')
		  {
		   if(!empty($_FILES['image'])){
			    $proof                     =  $_FILES['image']['name'];
		   }
			$proof_doc                 =  $student_id."-".$proof;
			$data['student_id']        =  $student_id;
			$data['school_id']         =  $school_id;
			$data['leave_category_id'] =  $this->input->post('leave_id');
			$data['request_date']      =  date('Y-m-d');
			$data['start_date']        =  date('Y-m-d',strtotime($this->input->post('start_date')));
			$data['end_date']          =  date('Y-m-d',strtotime($this->input->post('end_date')));
			$data['reason']            =  $this->input->post('reason');
			$data['proof_doc']         =  $proof_doc;
			$data['status']            =  0;
			
			if($proof!="")
        	{
        	    $ext = pathinfo($proof, PATHINFO_EXTENSION);
                $new_file = '' . '_' . time() . '.' . $ext;
        	    $data['proof_doc']         =  $new_file;
        	    $tempname                     =  $_FILES['image']['tmp_name'];
        	    $imagePath='uploads/'.$folder_name.'/leaves_student/'.$new_file;
        	    move_uploaded_file($tempname,$imagePath);
				//$data['proof_doc']=$this->file_upload_funt($folder_name,'userfile','leaves_student','');
			}
            //echo $imagePath; exit;
			$dddd = $this->db->insert($school_db.'.leave_student', $data);
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
		
		$leave_request_detail = $this->db->query("select sl.* from  ".$school_db.".leave_student sl where sl.student_id=$student_id and sl.school_id= $school_id order by sl.request_id desc ")->result_array();
	   // print_r($leave_request_detail);exit;
	   $leave_request_details = array();
	   $i = 1;
	   foreach($leave_request_detail as $row)
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
            if($row['approved_upto_date'] != "" && $row['final_end_date'] != ""){

                $start_date= $row['approved_upto_date'];
				$d=explode("-",$start_date);
				// $leave_request_details[$i]['approved_upto_date'] =  date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                $end_date=$row['final_end_date'];
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
	
	function leavereqst()
	{
	    $school_db = $this->input->post('school_db');
	    $school_id  = $this->input->post('school_id');
	    $student_id = $this->input->post('student_id');
	    $operation = $this->input->post('operation');
	    if($operation == 'create')
		  {
			$proof                     =  $_FILES['userfile']['name'];
		
			$proof_doc                 =  $student_id."-".$proof;
			$data['student_id']        =  $student_id;
			$data['school_id']         =  $school_id;
			$data['leave_category_id'] =  $this->input->post('leave_id');
			$data['request_date']      =  date('Y-m-d');
			$data['start_date']        =  date('Y-m-d',strtotime($this->input->post('start_date')));
			$data['end_date']          =  date('Y-m-d',strtotime($this->input->post('end_date')));
			$data['reason']            =  $this->input->post('reason');
			$data['proof_doc']         =  $proof_doc;
			$data['status']            =  0;
			
			if($proof!="")
        	{
			//	$data['proof_doc']=file_upload_fun('userfile','leaves_student','');
			}

            //print_r($proof);exit;
			$this->db->insert($school_db.'.leave_student', $data);

		}
	   // print_r($operation);
	}
	
	
	public function countNotifications()
	{
	    $student_id = $this->input->post('student_id');
	    $user_type = $this->input->post('user_type');
	    
        $school_id = $this->input->post('school_id');
        $school_db = $this->input->post('school_db');
        $unread = "select * from " . $school_db . ".school_notifications where user_id = $student_id and school_id = $school_id and user_type='$user_type' and is_viewed= 0 order by id desc";
        $nottification = $this->db->query($unread)->result_array();
        
      
        $response = array();
       if($nottification)
       {
           $response['code'] = '200';
           $response['count'] = count($nottification);
       }
       else{
            $response['code'] = '500';
       }
        echo json_encode($response);
        
	 
	}
	
	public function getNotifications()
	{
	    $student_id = $this->input->post('student_id');
	    $user_type = $this->input->post('user_type');
	    
        $school_id = $this->input->post('school_id');
        $school_db = $this->input->post('school_db');
        
        $limit =  $this->input->post('limit');
		$page =  $this->input->post('page');
		$offset = ($page - 1) * $limit;
       
        $unread = "select * from " . $school_db . ".school_notifications where user_id = $student_id and school_id = $school_id and user_type='$user_type' order by id desc  limit ".$offset."," . $limit . "";
       // $read   = "select * from " . $school_db . ".school_notifications where user_id = $student_id and school_id = $school_id and user_type='$user_type' and is_viewed = 1 order by id desc";
      
      	  
        $nottification = array();
        $nottification = $this->db->query($unread)->result_array();
       // $nottifications['read_nottifications'] = $this->db->query($read)->result_array();
       
       $response = array();
       if($nottification)
       {
           $response['code'] = '200';
           $response['notifications'] = $nottification;
       }
       else{
            $response['code'] = '500';
       }
        echo json_encode($response);
       
	}
	
	public function readNotifications()
	{
	    $student_id = $this->input->post('student_id');
	    $user_type = $this->input->post('user_type');
	    
        $school_id = $this->input->post('school_id');
        $school_db = $this->input->post('school_db');
        
        $id =  $this->input->post('notification_id');

        $this->db->set('is_viewed', 1);
        $this->db->where('id', $id);
        $this->db->where('school_id', $school_id);
        $this->db->where('user_type', $user_type);
        $this->db->update($school_db.'.school_notifications'); 
        
        $response['code']=200;
         echo json_encode($response);
	}
	
	
	
	public function school_policies()
	{
	       
        $school_id = $this->input->post('school_id');
        $school_db = $this->input->post('school_db');
	    $qry = "SELECT p.*, pc.title as category_title  FROM " . $school_db . ".policies p  INNER JOIN " . $school_db . ".policy_category pc ON p.policy_category_id = pc.policy_category_id WHERE  p.school_id=" . $school_id . " and p.is_active=1 and p.student_p=1 ORDER BY p.policies_id DESC";
        $data = $this->db->query( $qry )->result_array();
        $policies_data  = array();
        $i=1;
        foreach ( $data as $row )
		    {
                $policies_data[$i]['title'] = $row['title'];
                $policies_data[$i]['document_num'] = $row['document_num'];
                $policies_data[$i]['version_num'] = $row['version_num'];
 
                $policies_data[$i]['policies_id'] = $row['policies_id'];

                $policies_data[$i]['category_title'] = $row['category_title']; 

                $policies_data[$i]['author'] = $row['author'];
                       
                $policies_data[$i]['approved_by'] = $row['approved_by']; 
                       
                $policies_data[$i]['approval_date'] = convert_date($row['approval_date']); 
                      
                       
                $policies_data[$i]['last_update_date'] = convert_date($row['last_update_date']); 
                        
                if($row['attachment']!=""){ 
                    $policies_data[$i]['attachment'] = display_link($row['attachment'],'policies');
                } 
                   
                $policies_data[$i]['detail'] = $row['detail']; 
                $i++;
			}
			
			$response=array();
			if($policies_data)
			{
			    $response['code']  ='200';
			    $response['policies']  =$policies_data;
			  
			}
			else{
			     $response['code']  ='500';
			     $response['msg']  ='no policies found!';
			}
			echo json_encode($response);
	}

function Logout()
{
    $device_id = $this->input->post('device_id');
    $school_id = $this->input->post('school_id');
    $school_db = $this->input->post('school_db');
    
    $arr = $this->db->set('islogin', 0)->where('mobile_device',$device_id)->update($school_db.'.mobile_device_id');
    
    // print_r($this->db->last_query());
    if($arr)
    {
        $response['code'] = '200';
        
    }
    else{
        $response['code'] = '500';
    }
    
    echo json_encode($response);
    
}

function get_per_student_teacher()
{
        $section_id = $this->input->post('section_id');
        $school_db= $this->input->post('school_db');
        $school_id = $this->input->post('school_id');
        $student_id = $this->input->post('student_id');
        $yearly_term_id = $this->input->post('yearly_term_id'); //4
        
        
        
        $newqry = "SELECT user_login_detail_id FROM ".$school_db.".student WHERE student_id =".$student_id;
        $std_login_detail_id = $this->db->query($newqry)->row();
        $array_data = array();
       
        
    
       
        $teacher_arr1 = $this->db->query("select staff.staff_id as teacher_id, staff.user_login_detail_id as user_id,staff.name, staff.staff_image as teacher_image from " . $school_db . ".class_routine cr inner join " . $school_db . ".class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id and crs.section_id = $section_id inner join " . $school_db . ".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id inner join " . $school_db . ".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join " . $school_db . ".staff on staff.staff_id = st.teacher_id where staff.school_id = " . $school_id . " GROUP BY staff.name ")->result_array();
        
     
       
        $i=1;
        foreach($teacher_arr1 as $teacher_arr){
             
            $teacher_id = $teacher_arr['teacher_id'];
            $teacherid_array = "SELECT user_login_detail_id FROM $school_db.staff WHERE staff_id = ".$teacher_id;
        	$teacher_login_detailed_id = $this->db->query($teacherid_array)->row();
        	$array_data[$i]['teacher_login_detailed_id']  = $teacher_login_detailed_id->user_login_detail_id ?? '0' ;
        	$array_data[$i]['student_login_detail_id'] = $std_login_detail_id->user_login_detail_id ?? '0' ;
        $i++;	
        }
        $response = array();
        if($array_data)
        {
             $response['code'] = '200';
             $response['data'] = $array_data;
        }
        else{
            $response['code'] = '500';
            $response['msg'] = 'No data found!';
        }
        
    echo json_encode($response);
}



function getDataForNotification()
{
    
   
   $sender_id =  $this->input->post('sender_id');
   $rec_id =  $this->input->post('rec_id');
   $student_id =  $this->input->post('student_id');
   $chat_id =  $this->input->post('chat_id');
   $std_id =  $this->input->post('std_id');
   $message =  $this->input->post('message');
   $std_name =  $this->input->post('std_name');
   $class_name =  $this->input->post('class_name');
   $teacher_name =  $this->input->post('teacher_name');
   $stdSectionId =  $this->input->post('stdSectionId');
   $academicYearId =  $this->input->post('academicYearId');
   $parent_idd = get_parent_idd($std_id);
   
       
    if($student_id)
   {
     
        $d=array(
        'title'=>$teacher_name.' send a message to '.$std_name.' '.$class_name,
        'body'=>$message,
        );
        $d2 = array(
            'screen'=>'chat',
            'sent_by'=>$sender_id,
            'student_login_detail_id'=>$student_id,
           

            'chat_id'=>$chat_id,
            'std_id'=>$std_id,
            'teacher_name'=>$teacher_name,
            'section_id'=>$stdSectionId,
            'academic_year_id'=>$academicYearId,
            
            
            );
           
     
        if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                       
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
        
  }
  else{
      echo '!0';
  }
   
   
   
   
}




	
	function test()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $q = "select * from indicied_indiciedu_production.diary";
        
        $arr = $CI->db->query($q)->row();
        print_r($arr);
        return $arr;
    
    }





}