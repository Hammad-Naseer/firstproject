<?php
    if(!defined('BASEPATH'))
        exit('No direct script access allowed');
    //session_start();

    class User extends CI_Controller
    {
        private $system_db;
        private $school_db;

        function __construct()
        {
            parent::__construct();

            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');
            if (isset($_SESSION['system_db'])){
                $this->system_db = $_SESSION['system_db'];
            }
            if (isset($_SESSION['school_db'])){
                $this->school_db = $_SESSION['school_db'];
            }
            
            $this->designation_arr = array();
            if(isset($_SESSION['user_login']) && $_SESSION['user_login']!= 1)
                redirect(base_url() . 'login');

        }
        
        /***default functin, redirects to login page if no admin logged in yet***/
        public function index()
        {
            if($_SESSION['user_login']!= 1)
            {
                redirect(base_url() . 'login');
            }
            redirect(base_url() . 'user/user_type');
        }

        /*public function user_type(){
        $page_data['user_type'] = $this->db->order_by('user_group_id', 'desc')->get(get_system_db().'.user_group')->result_array();
        $page_data['page_name']  = 'user_type_list';
        $page_data['page_title'] = get_phrase('manage_user_types');
        $this->load->view('backend/index', $page_data);
        }*/

        public function user_groups()
        {
            $page_data['user_group'] = $this->db->order_by('user_group_id', 'desc')->get(get_school_db().'.user_group')->result_array();
            $page_data['page_name']  = 'user_group_list';
            $page_data['page_title'] = get_phrase('manage_user_groups');
            $this->load->view('backend/index', $page_data);
        }
        
        public function view_group_users($user_group_id)
        {
            $u_g_id = str_decode($user_group_id);
            $staff   = $this->db->query("select s.staff_id , s.name , d.title from ".get_school_db().".user_rights ur join 
                                         ".get_school_db().".staff s on s.staff_id = ur.staff_id join
                                         ".get_school_db().".designation d on d.designation_id = s.designation_id where
                                         ur.user_group_id = $u_g_id and
                                         ur.school_id=".$_SESSION['school_id'])->result_array();
                                         
            $parents  = $this->db->query("select s.s_p_id , s.p_name as name from ".get_school_db().".user_rights ur join 
                                         ".get_school_db().".student_parent s on s.s_p_id = ur.parent_id where
                                         ur.user_group_id = $u_g_id and
                                         ur.school_id=".$_SESSION['school_id'])->result_array();
                                         
            $students = $this->db->query("select s.student_id , s.name from ".get_school_db().".user_rights ur join 
                                         ".get_school_db().".student s on s.student_id = ur.student_id where
                                         ur.user_group_id = $u_g_id and
                                         ur.school_id=".$_SESSION['school_id'])->result_array();
            
            $page_data['staff']      = $staff;
            $page_data['parents']    = $parents;
            $page_data['students']   = $students;
            
            
            $page_data['page_name']  = 'view_group_users';
            $page_data['page_title'] = get_phrase('view_group_users');
            $this->load->view('backend/index', $page_data);
        }
        
        public function update_user_group()
        {
            $user_type      = $this->input->post('user_type');
            $user_id        = $this->input->post('user_id');
            $user_group_id  = $this->input->post('user_group_id');
            
            $data['user_group_id'] = $user_group_id;
            if($user_type == "staff"){
                //update against staff_id
                $this->db->where('staff_id' , $user_id);
                $this->db->update(get_school_db().'.user_rights',$data);
            }elseif($user_type == "student"){
                //update against student_id
                $this->db->where('student_id' , $user_id);
                $this->db->update(get_school_db().'.user_rights',$data);
            }elseif($user_type == "parent"){
                //update against parent_id
                $this->db->where('parent_id' , $user_id);
                $this->db->update(get_school_db().'.user_rights',$data);
            }
            $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
            redirect(base_url() . 'user/user_groups');
        }


        private function set_barcode($code,$path)
        {

            $this->load->library('Zend');
            $this->zend->load('Zend/Barcode');
            $barcode = new Zend_Barcode();
            $file = $barcode->draw('Code128', 'image', array('text' => $code,'barHeight'=>20,'drawText'=>TRUE,'withQuietZones'=>FALSE,'orientation'=>0), array());

            $file_name=$code.'.png';
            if (!is_dir($path)){
                mkdir($path,0777,true);
            }
            $store_image = imagepng($file,"$path".'/'.$file_name);
            return $file_name;
        }



        public function staff_listing($action = "", $id = 0)
        {
            if($action == "add_edit")
            {
                $data['id_no'] = $this->input->post('cnic');
                $data['id_type']=$this->input->post('id_type');
                $data['name'] = $this->input->post('name');
                $data['designation_id'] = $this->input->post('designation_id');
                $data['email']=$this->input->post('email');
                $data['religion'] = $this->input->post('religion');
                $data['dob'] = date_slash($this->input->post('birthday'));
                $data['gender'] = $this->input->post('gender');
                $data['nationality']=$this->input->post('nationality');
                $data['country_id'] = $this->input->post('loc_add_country');
                $data['province_id'] = $this->input->post('loc_add_province');
                $data['city_id'] = $this->input->post('loc_add_city');
                $data['location_id'] = $this->input->post('loc_add');
                $data['postal_address'] = $this->input->post('postal_address');
                $data['permanent_address'] = $this->input->post('permanent_address');
                $data['phone_no'] = $this->input->post('phone_no');
                $data['mobile_no'] = $this->input->post('mobile_no');
                $data['status'] = $this->input->post('status');
                $data['emergency_no'] = $this->input->post('emergency_no');
                $data['employee_code'] = $this->input->post('employee_code');
                $data['blood_group'] = $this->input->post('blood_group');
                $joining_date=$this->input->post('joining_date');
                $joining_arr=explode('/',$joining_date);
               
                $data['joining_date']=$joining_arr[2].'-'.$joining_arr[1].'-'.$joining_arr[0];
                $data['experience_month']=$this->input->post('experience_month');
                $data['experience_year']=$this->input->post('experience_year');
                $data['periods_per_day'] = $this->input->post('periods_per_day');
                $data['periods_per_week'] = $this->input->post('periods_per_week');
                $data['hours_per_day']=$this->input->post('hours_per_day');
				$data['hours_per_week']=$this->input->post('hours_per_week');
				$data['hours_per_month']=$this->input->post('hours_per_month');
				$data['regular_daily_rate']=$this->input->post('regular_daily_rate');
				$data['regular_hourly_rate']=$this->input->post('regular_hourly_rate');
				$data['overtime_daily_rate']=$this->input->post('overtime_daily_rate');
				$data['overtime_hourly_rate']=$this->input->post('overtime_hourly_rate');
                $school_id = $_SESSION['school_id'];
                $filename  = $_FILES['staff_image']['name'];
                

                if($filename != "")
                {
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $data['staff_image'] = file_upload_fun('staff_image','staff','pic');
                    $old_attachment=$this->input->post('old_staff_image');
                    
                    if($old_attachment != "")
                    {
                        $del_location = system_path($old_attachment,'staff');
                        file_delete($del_location);
                    }
                }
                
				$id_file=$this->input->post('image_old');
                $staff_id = $this->input->post('staff_id');
                $designation_id = $this->input->post('designation_id');

                if($staff_id != ""){
                	$filename = $_FILES['image2']['name'];
                	$folder_name = $_SESSION['folder_name'];
            		$ext = pathinfo($filename, PATHINFO_EXTENSION);
            		if($filename!="")
            		{
                		$data['id_file']=file_upload_fun('image2','staff','');
                		$image_old = $this->input->post('image_old');
                		if($image_old!="")
                		{
                    		$del_location=system_path($image_old,'staff');
                    		file_delete($del_location);
                		}
                    }
                	
                    $this->db->where('staff_id',$staff_id);
                    $this->db->where('school_id',$school_id);
                    $this->db->update(get_school_db().'.staff',$data);
                    
                    /****************** Salary , Allownce , Deduction update ************/
                    /*******************************************************************/
                    $check_salary_exist = $this->db->query("SELECT staff_id FROM ".get_school_db().".staff_payroll_settings WHERE staff_id = '$staff_id'");
                    if($check_salary_exist->num_rows() > 0)
                    {
                        $salary_settings['gross_salary']    =   $this->input->post('gross_salary');
                        $this->db->where("staff_id",$staff_id);
                        $this->db->update(get_school_db().'.staff_payroll_settings',$salary_settings);    
                    }else
                    {
                        $salary_settings['staff_id']        =   $staff_id;
                        $salary_settings['gross_salary']    =   $this->input->post('gross_salary');
                        $this->db->insert(get_school_db().'.staff_payroll_settings',$salary_settings);
                    }
                    

                    // NOT USED                    
                    // $this->db->where('staff_id' , $staff_id)->delete(get_school_db().'.staff_payroll_allowances');
                    // $allownce    =   $this->input->post('allownce');
                    // for($i = 0 ; $i < count($allownce) ; $i++)
                    // {
                    //     $salary_allownce['staff_id']        =   $staff_id;
                    //     $salary_allownce['allownce_id']     =   $allownce[$i];
                    //     $this->db->insert(get_school_db().'.staff_payroll_allowances',$salary_allownce);
                    // }
                    
                    // $this->db->where('staff_id' , $staff_id)->delete(get_school_db().'.staff_payroll_deductions');
                    // $deduction    =   $this->input->post('deduction');
                    // for($i = 0 ; $i < count($deduction) ; $i++)
                    // {
                    //     $salary_deduction['staff_id']        =   $staff_id;
                    //     $salary_deduction['deduction_id']     =   $deduction[$i];
                    //     $this->db->insert(get_school_db().'.staff_payroll_deductions',$salary_deduction);
                    // }
                    /*******************************************************************/
                    /*******************************************************************/
                    
                
                    $this->session->set_flashdata('club_updated',get_phrase('staff_record_was_updated_successfully'));
                    
                }else{
                    
                	$filename=$_FILES['image2']['name'];
                	$folder_name = $_SESSION['folder_name'];
            		$ext = pathinfo($filename, PATHINFO_EXTENSION);
            		if($filename!="")
            		{
                		$data['id_file']=file_upload_fun('image2','staff','cnic_');
            		}
                    $data['school_id'] = $school_id;
                    $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
                    $this->db->insert(get_school_db().'.staff',$data);
                    $staff_id=$this->db->insert_id();
                    
                    /****************** Salary , Allownce , Deduction insertion *********/
                    /*******************************************************************/
                    
                    
                    // $allownce    =   $this->input->post('allownce');
                    // for($i = 0 ; $i < count($allownce) ; $i++)
                    // {
                    //     $salary_allownce['staff_id']        =   $staff_id;
                    //     $salary_allownce['allownce_id']     =   $allownce[$i];
                    //     $this->db->insert(get_school_db().'.staff_payroll_allowances',$salary_allownce);
                    // }
                    
                    // $deduction    =   $this->input->post('deduction');
                    // for($i = 0 ; $i < count($deduction) ; $i++)
                    // {
                    //     $salary_deduction['staff_id']        =   $staff_id;
                    //     $salary_deduction['deduction_id']     =   $deduction[$i];
                    //     $this->db->insert(get_school_db().'.staff_payroll_deductions',$salary_deduction);
                    // }
                    /*******************************************************************/
                    /*******************************************************************/
                }

                $scl_id=$_SESSION['sys_sch_id'];

                $bar_code_type = 113;
                $scl_id = sprintf("%'06d",$scl_id);
                $stf_id = sprintf("%'07d",$staff_id);

                $system_id = $bar_code_type.''.$scl_id.''.$stf_id;

                $path='uploads/'.$_SESSION['folder_name'].'/staff';

                $bar_cod['barcode_image']=$this->set_barcode($system_id,$path);
                $bar_cod['system_id']=$system_id;

                $this->db->where('staff_id',$staff_id);
                $this->db->where('school_id', $_SESSION['school_id']);
                $this->db->update(get_school_db().'.staff',$bar_cod);
                //section barcode ends here

                redirect(base_url() . 'user/staff_listing/');
            }

            else if($action == 'delete'){
                $school_id      = $_SESSION['school_id'];
                $old_attachment = $this->uri->segment(5);
                $old_id_file= $this->uri->segment(6);

                $qur_1=$this->db->query("select class_id from ".get_school_db().".class where teacher_id=$id and school_id=".$_SESSION['school_id'])->result_array();

                if(count($qur_1) > 0){

                    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                    redirect(base_url().'user/staff_listing');
                    exit();

                }

                $qur_2=$this->db->query("select diary_id from ".get_school_db().".diary where teacher_id=$id and school_id=".$_SESSION['school_id'])->result_array();

                if(count($qur_2) > 0){

                    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                    redirect(base_url().'user/staff_listing');
                    exit();

                }

                $qur_3=$this->db->query("select messages_id from ".get_school_db().".messages where teacher_id=$id and school_id=".$_SESSION['school_id'])->result_array();

                if(count($qur_3) > 0){

                    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                    redirect(base_url().'user/staff_listing');
                    exit();

                }

                $qur_4=$this->db->query("select subject_teacher_id from ".get_school_db().".subject_teacher where teacher_id=$id and school_id=".$_SESSION['school_id'])->result_array();

                if(count($qur_4) > 0){

                    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                    redirect(base_url().'user/staff_listing');
                    exit();
                }


                $qur_5=$this->db->query("select departments_id from ".get_school_db().".departments where department_head=$id and school_id=".$_SESSION['school_id'])->result_array();

                if(count($qur_5) > 0){

                    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                    redirect(base_url().'user/staff_listing');
                    exit();

                }

                if($old_attachment != ""){

                    $del_location = system_path($old_attachment,'staff');
                    file_delete($del_location);
                }
                if($old_id_file != "")
                {
					$del_location = system_path($old_id_file,'staff');
                    file_delete($del_location);
				}
                

                $delete_ary=array('school_id'=>$school_id,'staff_id'=>$id);

                $this->db->where($delete_ary);
                $this->db->delete(get_school_db().'.staff');
                $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));

                redirect(base_url().'user/staff_listing');
            }

            
            $apply_filter = $this->input->post('apply_filter');
            
            $designation_id = $this->input->post('designation_id');
            $designation_filter = '';
            if($designation_id != ''){
                $designation_filter = "and s.designation_id = $designation_id";
            }
            
            $qry = "SELECT s.*, d.title as designation, d.is_teacher FROM ".get_school_db().".staff s 
            left JOIN ".get_school_db().".designation d ON d.designation_id = s.designation_id
            WHERE s.school_id=".$_SESSION['school_id']." ".$designation_filter."
            ORDER BY s.staff_id ";//DESC
           
            $res_array = $this->db->query($qry)->result_array();
            
            
            $page_data['data'] = $res_array;
            $page_data['apply_filter'] = $apply_filter;
            $page_data['designation_id'] = $designation_id;
            $page_data['page_name'] = 'staff_listing';
            $page_data['page_title'] = get_phrase('staff');
            $this->load->view('backend/index', $page_data);
        }

        function admin_staff_list($action = '', $id=0)
        {
            $designation_filter = '';
            if($action == 'filter')
            {
                $designation_filter = " s.designation_id = $id AND";
                $page_data['filter'] = true;
            }

            $qry = "SELECT s.*, d.title as designation, d.is_teacher
            FROM ".get_school_db().".staff s 
            INNER JOIN ".get_school_db().".designation d
            ON d.designation_id = s.designation_id
            WHERE 
            $designation_filter
            s.school_id=".$_SESSION['school_id']." 
            ORDER BY s.staff_id DESC";

            $res_array = $this->db->query($qry)->result_array();

            $page_data['data'] = $res_array;
            $page_data['page_name'] = 'admin_staff_listing';
            $page_data['page_title'] = get_phrase('staff');
            $this->load->view('backend/index', $page_data);
        }

        function manage_user_right($action = '')
        {
            if ($action == 'create_new')
            {
                $data['user_group_id'] = intval($this->input->post('user_group_id'));
                $data['schoool_id'] = $_SESSION['school_id'];
                $data['staff_id'] = intval($this->input->post('staff_id'));
                $this->db->insert(get_school_db().'.user_rights', $data);
                $this->session->set_flashdata('club_updated', get_phrase("rights_assigned_successfully"));
            }
            elseif($action == 'edit')
            {
                $data['user_group_id'] = intval($this->input->post('user_group_id'));
                $data['schoool_id'] = $_SESSION['school_id'];
                $data['staff_id'] = intval($this->input->post('staff_id'));
                $this->db->query("delete from ".get_school_db().".user_rights where staff_id = ".$data['staff_id']." ");

                $this->db->insert(get_school_db().'.user_rights', $data);
                $this->session->set_flashdata('club_updated', get_phrase("rights_assigned_successfully"));
            }

            redirect(base_url().'user/admin_staff_list');
        }

        function manage_staff_login($action='')
        {
            $login_type_id = get_login_type_id('staff');
            if ($action == 'create_new' )
            {
                $data['id_no'] = $this->input->post('cnic');
                $data['name'] = $this->input->post('display_name');
                //$data['school_id'] = $_SESSION['school_id'];
                $data['email'] = $this->input->post('email');
                $data['password'] = passwordHash($this->input->post('password'));
                $staff_id = $this->input->post('staff_id');
                $data['status'] = 1;

                $staff_arr = $this->db->query("select id_no from ".get_system_db().".user_login 
                where 
                id_no = '".$data['id_no']."'
                ")->result_array();

                if (count($staff_arr) == 0)
                {
                    $this->db->insert(get_system_db().'.user_login', $data);

                    $last_id = intval($this->db->insert_id());
                    $update['system_id'] = get_system_id($last_id,$_SESSION['sys_sch_id'],'teacher');
                    $this->db->update(get_system_db().'.user_login', $update, array("user_login_id" => $last_id));

                    $detail['user_login_id'] = $last_id;
                    $detail['sys_sch_id'] = $_SESSION['sys_sch_id'];
                    //$detail['school_id'] = $_SESSION['school_id'];
                    $detail['creation_date'] = date('Y-m-d h:i:s');
                    $detail['created_by'] = $_SESSION['user_login_id'];
                    $detail['status'] = intval($this->input->post('status'));
                    $detail['login_type'] = $login_type_id;

                    $is_exists =  $this->db->query("select user_login_detail_id from ".get_system_db().".user_login_details where user_login_id = $last_id
                                                    and sys_sch_id = ".$_SESSION['sys_sch_id']." and login_type = $login_type_id")->result_array();

                    if (count($is_exists) == 0)
                    {
                        $this->db->insert(get_system_db().'.user_login_details', $detail);
                        $staff_data['staff_login_detail_id'] = $this->db->insert_id();
                        $this->db->update(get_school_db().'.staff', $staff_data, array( "staff_id" => $staff_id));

                        //assign rights
                        $this->db->query("delete from ".get_school_db().".user_rights where staff_id = $staff_id ");
                        $user_rights_data['user_group_id'] = intval($this->input->post('user_group_id'));
                        $user_rights_data['staff_id'] = intval($staff_id);
                        $user_rights_data['school_id'] = intval($_SESSION['school_id']);
                        $this->db->insert(get_school_db().'.user_rights', $user_rights_data);

                        $this->session->set_flashdata('club_updated', get_phrase('staff_login_created_successfully'));
                    }
                    else
                    {
                        $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                    }
                }
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                redirect(base_url().'user/admin_staff_list');
            }
            elseif ($action == 'link_account')
            {
                $data['user_login_id'] = intval($this->input->post('user_login_id'));
                $staff_id = intval($this->input->post('staff_id'));
                $data['sys_sch_id'] = $_SESSION['sys_sch_id'];
                //$data['school_id'] = $_SESSION['school_id'];
                $data['creation_date'] = date('Y-m-d h:i:s');
                $data['created_by'] = $_SESSION['user_login_id'];
                $data['status'] = intval($this->input->post('status'));
                $data['login_type'] = $login_type_id;

                $is_exists =  $this->db->query("select user_login_detail_id from ".get_system_db().".user_login_details  where
                                                user_login_id = ".$data['user_login_id']." and sys_sch_id = ".$_SESSION['sys_sch_id']." and login_type = $login_type_id")->result_array();
                if (count($is_exists) == 0)
                {
                    $this->db->insert(get_system_db().'.user_login_details', $data);
                    $staff_data['staff_login_detail_id'] = $this->db->insert_id();
                    $this->db->update(get_school_db().'.staff', $staff_data, array( "staff_id" => $staff_id));

                    //assign rights
                    $this->db->query("delete from ".get_school_db().".user_rights where staff_id = $staff_id ");
                    $user_rights_data['user_group_id'] = intval($this->input->post('user_group_id'));
                    $user_rights_data['staff_id'] = intval($staff_id);
                    $user_rights_data['school_id'] = intval($_SESSION['school_id']);
                    $this->db->insert(get_school_db().'.user_rights', $user_rights_data);

                    $this->session->set_flashdata('club_updated', get_phrase('staff_login_account_linked_successfully'));
                }
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                redirect(base_url().'user/admin_staff_list');
            }
            elseif ($action == 'update')
            {
                $user_login_id = $this->input->post('user_login_id');
                $data['name'] = $this->input->post('display_name');
                /*$data['email'] = $this->input->post('email');*/
                $data['is_password_updated'] = 1;
                $password = passwordHash($this->input->post('password'));
                if( isset($password) && $password != '')
                {
                    $data['password'] = $password;
                }

                $this->db->update(get_system_db().'.user_login', $data, array("user_login_id" => $user_login_id));

                $data_detail['status'] = intval($this->input->post('status'));

                $this->db->update(get_system_db().'.user_login_details', $data_detail, array("user_login_id" => $user_login_id,
                        "sys_sch_id" => $_SESSION['sys_sch_id'],
                        "login_type" => $login_type_id,
                    )
                );

                //assign rights
                $staff_id = intval($this->input->post('staff_id'));
                $this->db->query("delete from ".get_school_db().".user_rights where staff_id = $staff_id ");
                $user_rights_data['user_group_id'] = intval($this->input->post('user_group_id'));
                $user_rights_data['staff_id'] = intval($staff_id);
                $user_rights_data['school_id'] = intval($_SESSION['school_id']);
                $this->db->insert(get_school_db().'.user_rights', $user_rights_data);

                $this->session->set_flashdata('club_updated', 'staff login updated successfully');
                redirect(base_url().'user/admin_staff_list');
            }
        }

        function manage_teacher_login($action='')
        {
            $login_type_id = get_login_type_id('teacher');
            if ($action == 'create_new' )
            {
                $data['id_no'] = $this->input->post('cnic');
                $data['name'] = $this->input->post('display_name');
                $data['email'] = $this->input->post('email');
                $data['password'] = passwordHash($this->input->post('password'));
                $teacher_id = $this->input->post('teacher_id');
                $data['status'] = 1;
                $user_group_id  =   $this->input->post('user_group_id');

                $staff_arr = $this->db->query("select id_no from ".get_system_db().".user_login where id_no = '".$data['id_no']."'")->result_array();

                if (count($staff_arr) == 0)
                {
                    $this->db->insert(get_system_db().'.user_login', $data);

                    $last_id = intval($this->db->insert_id());
                    $update['system_id'] = get_system_id($last_id,$_SESSION['sys_sch_id'],'teacher');
                    $this->db->update(get_system_db().'.user_login', $update, array("user_login_id" => $last_id));

                    $detail['user_login_id'] = $last_id;
                    $detail['sys_sch_id']    = $_SESSION['sys_sch_id'];
                    $detail['creation_date'] = date('Y-m-d h:i:s');
                    $detail['created_by']    = $_SESSION['user_login_id'];
                    $detail['status']        = intval($this->input->post('status'));
                    $detail['login_type']    = $login_type_id;

                    $is_exists =  $this->db->query("select user_login_detail_id from ".get_system_db().".user_login_details 
                                  where user_login_id = $last_id and sys_sch_id = ".$_SESSION['sys_sch_id']." and login_type = $login_type_id")->result_array();

                    if (count($is_exists) == 0)
                    {
                        $this->db->insert(get_system_db().'.user_login_details', $detail);
                        $staff_data['user_login_detail_id'] = $this->db->insert_id();
                        $this->db->update(get_school_db().'.staff', $staff_data, array( "staff_id" => $teacher_id));
                        
                        $user_rights_data['user_group_id'] = $user_group_id;
                        $user_rights_data['staff_id']      = intval($teacher_id);
                        $user_rights_data['school_id']     = intval($_SESSION['school_id']);
                        $this->db->insert(get_school_db().'.user_rights', $user_rights_data);
                        
                        $this->session->set_flashdata('club_updated', get_phrase('teacher_login_created_successfully'));
                    }
                    else
                    {
                        $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                    }
                }
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                redirect(base_url().'user/admin_staff_list');
            }
            elseif ($action == 'link_account')
            {
                $data['user_login_id'] = intval($this->input->post('user_login_id'));
                $teacher_id = intval($this->input->post('teacher_id'));
                $data['sys_sch_id'] = $_SESSION['sys_sch_id'];
                $data['creation_date'] = date('Y-m-d h:i:s');
                $data['created_by'] = $_SESSION['user_login_id'];
                $data['status'] = intval($this->input->post('status'));
                $data['login_type'] = $login_type_id;

                $is_exists =  $this->db->query("select user_login_detail_id from ".get_system_db().".user_login_details 
                                               where user_login_id = ".$data['user_login_id']." and sys_sch_id = ".$_SESSION['sys_sch_id']." and login_type = $login_type_id")->result_array();
                if (count($is_exists) == 0)
                {
                    $this->db->insert(get_system_db().'.user_login_details', $data);
                    $staff_data['user_login_detail_id'] = $this->db->insert_id();
                    $this->db->update(get_school_db().'.staff', $staff_data, array( "staff_id" => $teacher_id));
                    $this->session->set_flashdata('club_updated', get_phrase('teacher_login_account_linked_successfully'));
                }
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                redirect(base_url().'user/admin_staff_list');
            }
            elseif ($action == 'update')
            {
                $user_login_id = $this->input->post('user_login_id');
                $data['name'] = $this->input->post('display_name');
                /*$data['email'] = $this->input->post('email');*/
                $data['is_password_updated'] = 1;
                $password = $this->input->post('password');
                if( isset($password) && $password != '')
                {
                    $data['password'] = passwordHash($password);
                }
                //working
                // echo $this->input->post('password');
                // echo "<pre>";
                // print_r($data);
                // exit;

                $this->db->update(get_system_db().'.user_login', $data, array("user_login_id" => $user_login_id));

                $data_detail['status'] = intval($this->input->post('status'));

                $this->db->update(get_system_db().'.user_login_details', $data_detail, array("user_login_id" => $user_login_id,
                        "sys_sch_id" => $_SESSION['sys_sch_id'],
                        "login_type" => $login_type_id,
                    )
                );
                
                $teacher_id                       = $this->input->post('teacher_id');
                $this->db->query("delete from ".get_school_db().".user_rights where staff_id = $teacher_id ");
                $user_group_id                     =   $this->input->post('user_group_id');
                $user_rights_data['user_group_id'] = $user_group_id;
                $user_rights_data['staff_id']      = intval($teacher_id);
                $user_rights_data['school_id']     = intval($_SESSION['school_id']);
                $this->db->insert(get_school_db().'.user_rights', $user_rights_data);


                $this->session->set_flashdata('club_updated',get_phrase('teacher_login_updated_successfully!'));
                redirect(base_url().'user/admin_staff_list');
            }
        }///student login
        
        
        function  manage_student_login($studentId = '', $sectionId = '')
        {
            
                $login_type_id = 6; //get_login_type_id('parent'); 
            
                $data['name']     = $this->input->post('f_p_name');
                $data['password'] = passwordHash($this->input->post('password'));
                $data['id_no']    = $this->input->post('f_cnic');
                $parent_id        = $this->input->post('parent_id');
                $student_id       = $this->input->post('student_id');
                $user_group_id    = $this->input->post('user_group_id');
                $data['status']   = 1;
                
                $parent_arr = $this->db->query(" select id_no from ".get_system_db().".user_login 
                                                 where id_no = '".$this->input->post('f_cnic')."' ")->result_array();
                                                 
                if (count($parent_arr) == 0)
                {
                    $this->db->trans_begin();
                    
                    $this->db->insert(get_system_db().'.user_login', $data);

                    $last_id = intval($this->db->insert_id());
                    $update['system_id'] = get_system_id($last_id,$_SESSION['school_id'],'student');
                    $this->db->update(get_system_db().'.user_login', $update, array("user_login_id" => $last_id));
                    
                    // Insert Student Right
                    if( check_if_user_group_assigned($student_id) ){
                        $data_rights = array();
                        $data_rights['user_group_id'] = $user_group_id;
                        $data_rights['student_id']    = $student_id;
                        $data_rights['school_id']     = $_SESSION['school_id'];
                        $this->db->insert(get_school_db().'.user_rights', $data_rights); 
                    }


                    $student_update['parent_id'] = $parent_id;
                    $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));

                    $detail['user_login_id'] = $last_id;
                    $detail['sys_sch_id'] = $_SESSION['sys_sch_id'];
                    $detail['creation_date'] = date('Y-m-d h:i:s');
                    $detail['created_by'] = $_SESSION['user_login_id'];
                    $detail['status'] = intval($this->input->post('status'));
                    $detail['login_type'] = $login_type_id;

                    $is_exists =  $this->db->query(" select user_login_detail_id from ".get_system_db().".user_login_details where
                                                     user_login_id = $last_id and sys_sch_id = ".$_SESSION['sys_sch_id']." and login_type = $login_type_id ")->result_array();

                    if (count($is_exists) == 0)
                    {
                        
                        $this->db->insert(get_system_db().'.user_login_details', $detail);
                        $user_login_detail_id_for_student =  $this->db->insert_id();
                        $parent_data['user_login_detail_id'] = $this->db->insert_id();
                        $this->db->update(get_school_db().'.student_parent', $parent_data, array( "s_p_id" => $parent_id) );
                        
                        $student_user_login_detail_id['is_login_created']     = 1;
                        $student_user_login_detail_id['user_login_detail_id'] = $user_login_detail_id_for_student;
                        $this->db->update(get_school_db().'.student', $student_user_login_detail_id , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                        
                        $this->session->set_flashdata('club_updated', get_phrase('student_login_created_successfully'));
                        
                        
                    }
                    else
                    {
                        $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                    }
                    
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }
                
                    
                }
                
                elseif(count($parent_arr) == 1)
                {
                    $this->db->trans_begin();
                    
                    $password = $this->input->post('password');
                    $id_no = $this->input->post('f_cnic');
                    if( isset($password) && $password != '')
                    {
                        $data['password'] = $password;
                    }

                    $this->db->update(get_system_db().'.user_login', $data, array("id_no" => $id_no));
                    
                    // Insert Student Right
                    if( check_if_user_group_assigned($student_id) ){
                        $data_rights = array();
                        $data_rights['user_group_id'] = $user_group_id;
                        $data_rights['student_id']    = $student_id;
                        $data_rights['school_id']     = $_SESSION['school_id'];
                        $this->db->insert(get_school_db().'.user_rights', $data_rights); 
                    }
                    else
                    {
                          $user_group_update['user_group_id'] = $user_group_id;
                          $this->db->update(get_school_db().'.user_rights', $user_group_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                    }

                    $this->session->set_flashdata('club_updated', get_phrase('student_login_updated_successfully'));
                    
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }

                    redirect(base_url().'c_student/student_information');
            
                }
                
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                
                redirect(base_url().'c_student/student_information');
                 

                if ($action == 'create_new' )
                {
                   
                }
                
                elseif ($action == 'link_account')
                {
                    $this->db->trans_begin();
                    
                    $student_id = intval($this->uri->segment(4));
                    $parent_id = intval($this->uri->segment(5));
                    $student_update['parent_id'] = $parent_id;
    
                    $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                    $this->session->set_flashdata('club_updated', get_phrase('teacher_login_account_linked_successfully'));
                    
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } 
                    else {
                        $this->db->trans_commit();
                    }

                    redirect(base_url().'c_student/student_information');
                    
                }
                
                elseif ($action == 'create_link_account')
                {
                    
                    $student_id = intval($this->uri->segment(4));
                    $parent_id = intval($this->uri->segment(5));
    
                    $data['user_login_id'] = intval($this->uri->segment(6));
                    $data['sys_sch_id'] = $_SESSION['sys_sch_id'];
                    $data['creation_date'] = date('Y-m-d h:i:s');
                    $data['created_by'] = $_SESSION['user_login_id'];
                    $data['status'] = 1;
                    //$data['school_id'] = $_SESSION['school_id'];
                    $data['login_type'] = $login_type_id;
    
                    $is_exists =  $this->db->query(" select user_login_detail_id from ".get_system_db().".user_login_details 
                                                     where user_login_id = ".$data['user_login_id']." and sys_sch_id = ".$_SESSION['sys_sch_id']." 
                                                     and login_type = $login_type_id ")->result_array();
    
                    if (count($is_exists) == 0)
                    {
                        
                        $this->db->trans_begin();
                        
                        $this->db->insert(get_system_db().'.user_login_details', $data);
    
                        $parent_data['user_login_detail_id'] = $this->db->insert_id();
                        $this->db->update(get_school_db().'.student_parent', $parent_data,
                            array( "s_p_id" => $parent_id)
                        );
    
                        $student_update['parent_id'] = $parent_id;
                        $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
    
                        $this->session->set_flashdata('club_updated', get_phrase('parent_login_account_created_and_linked_successfully'));
                        
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                        } 
                        else {
                            $this->db->trans_commit();
                        }
                        
                    }
                    
                    elseif (count($is_exists) == 1)
                    {
                        $student_update['parent_id'] = $parent_id;
                        $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
    
                        $this->session->set_flashdata('club_updated', get_phrase('parent_login_account_created_and_linked_successfully'));
                    }
                    
                    else
                    {
                        $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                    }
                    
                    redirect(base_url().'c_student/student_information');
                    
                }
            
                elseif ($action == 'update')
                {
                    $user_login_id = $this->input->post('user_login_id');
                    $data['name']  = $this->input->post('display_name');
                    $password      = passwordHash($this->input->post('password'));
                    
                    if( isset($password) && $password != '')
                    {
                        $data['password'] = passwordHash($password);
                    }
    
                    $this->db->update(get_system_db().'.user_login', $data, array("user_login_id" => $user_login_id));
    
                    $data_detail['status'] = intval($this->input->post('status'));
                    $this->db->update(get_system_db().'.user_login_details', $data_detail, array( "user_login_id" => $user_login_id,
                            "sys_sch_id" => $_SESSION['sys_sch_id'],
                            "login_type" => $login_type_id,
                        )
                    );
                    
    
                    $this->session->set_flashdata('club_updated', get_phrase('parent_login_updated_successfully'));
                    redirect(base_url().'c_student/student_information');
                }
           
        }
        
        
        function manage_parent_login($action='')
        {
            
            //print_r($this->input->post());exit;
            
            $login_type_id = get_login_type_id('parent'); //print_r($login_type_id);exit;
            if ($action == 'create_new' )
            {
                $data['id_no']     = $this->input->post('cnic');
                $data['name']      = $this->input->post('display_name');
                $data['email']     = $this->input->post('email');
                $data['password']  = passwordHash($this->input->post('password'));
                $parent_id         = $this->input->post('parent_id');
                $student_id        = $this->input->post('student_id');
                $user_group_id     = $this->input->post('user_group_id');
                $data['status']    = 1;

                $parent_arr = $this->db->query(" select id_no from ".get_system_db().".user_login 
                                                 where id_no = '".$data['id_no']."' ")->result_array();

                if (count($parent_arr) == 0)
                {
                    $this->db->trans_begin();
                    
                    $this->db->insert(get_system_db().'.user_login', $data);

                    $last_id = intval($this->db->insert_id());
                    $update['system_id'] = get_system_id($last_id,$_SESSION['school_id'],'parent');
                    $this->db->update(get_system_db().'.user_login', $update, array("user_login_id" => $last_id));

                    $student_update['parent_id'] = $parent_id;
                    $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));

                    $detail['user_login_id'] = $last_id;
                    $detail['sys_sch_id'] = $_SESSION['sys_sch_id'];
                    $detail['creation_date'] = date('Y-m-d h:i:s');
                    $detail['created_by'] = $_SESSION['user_login_id'];
                    $detail['status'] = intval($this->input->post('status'));
                    $detail['login_type'] = $login_type_id;

                    $is_exists =  $this->db->query(" select user_login_detail_id from ".get_system_db().".user_login_details 
                                                     where user_login_id = $last_id and sys_sch_id = ".$_SESSION['sys_sch_id']."
                                                     and login_type = $login_type_id ")->result_array();

                    if (count($is_exists) == 0)
                    {
                        $this->db->insert(get_system_db().'.user_login_details', $detail);
                        $parent_data['user_login_detail_id'] = $this->db->insert_id();
                        $this->db->update(get_school_db().'.student_parent', $parent_data,
                            array( "s_p_id" => $parent_id)
                        );
                        
                          // **********************Assign Group to Parent Starts************************
                         //  ***************************************************************************
                        //   Insert Parent Rights
                        if( check_if_parent_user_group_assigned($parent_id) ){
                            $assign_group['user_group_id']  =  $user_group_id;
                            $assign_group['school_id']      =  $_SESSION['school_id'];
                            $assign_group['parent_id']      =  $parent_id;
                            $this->db->insert(get_school_db() . '.user_rights', $assign_group);
                        }    
                         // **********************Assign Group to Parent Ends**************************
                        //  ***************************************************************************
                        $this->session->set_flashdata('club_updated', get_phrase('parent_login_created_successfully'));
                    }
                    else
                    {
                        $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                    }
                    
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } 
                    else {
                        $this->db->trans_commit();
                    }
                    
                }
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                redirect(base_url().'c_student/student_information');
            }
            elseif ($action == 'link_account')
            {
                
                $this->db->trans_begin();
                
                $student_id = intval($this->uri->segment(4));
                $parent_id  = intval($this->uri->segment(5));
                
                $student_update['parent_id'] = $parent_id;
                $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                $this->session->set_flashdata('club_updated', get_phrase('parent_login_account_linked_successfully'));
                redirect(base_url().'c_student/student_information');
                
            }
            elseif ($action == 'create_link_account')
            {
                
                $this->db->trans_begin();
                
                
                $student_id = intval($this->uri->segment(4));
                $parent_id = intval($this->uri->segment(5));

                $data['user_login_id'] = intval($this->uri->segment(6));
                $data['sys_sch_id'] = $_SESSION['sys_sch_id'];
                $data['creation_date'] = date('Y-m-d h:i:s');
                $data['created_by'] = $_SESSION['user_login_id'];
                $data['status'] = 1;
                //$data['school_id'] = $_SESSION['school_id'];
                $data['login_type'] = $login_type_id;

                $is_exists =  $this->db->query("  select user_login_detail_id from ".get_system_db().".user_login_details 
                                                  where user_login_id = ".$data['user_login_id']." and sys_sch_id = ".$_SESSION['sys_sch_id']."
                                                  and login_type = $login_type_id ")->result_array();

                if (count($is_exists) == 0)
                {

                    $this->db->insert(get_system_db().'.user_login_details', $data);

                    $parent_data['user_login_detail_id'] = $this->db->insert_id();
                    $this->db->update(get_school_db().'.student_parent', $parent_data, array( "s_p_id" => $parent_id));

                    $student_update['parent_id'] = $parent_id;
                    $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                    $this->session->set_flashdata('club_updated', get_phrase('parent_login_account_created_and_linked_successfully'));
                }
                elseif (count($is_exists) == 1)
                {
                    $student_update['parent_id'] = $parent_id;
                    $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                    $this->session->set_flashdata('club_updated', get_phrase('parent_login_account_created_and_linked_successfully'));
                }
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
                
                redirect(base_url().'c_student/student_information');
            }
            elseif ($action == 'update')
            {
                
                $this->db->trans_begin();
                
                $user_login_id = $this->input->post('user_login_id');
                $data['name']  = $this->input->post('display_name');
                $data['email']  = $this->input->post('email');
                $password      = passwordHash($this->input->post('password'));
                if( isset($password) && $password != '')
                {
                    $data['password'] = $password;
                }

                $this->db->update(get_system_db().'.user_login', $data, array("user_login_id" => $user_login_id));

                $data_detail['status'] = intval($this->input->post('status'));
                $this->db->update(get_system_db().'.user_login_details', $data_detail, array( "user_login_id" => $user_login_id,
                        "sys_sch_id" => $_SESSION['sys_sch_id'],
                        "login_type" => $login_type_id,
                    )
                );
                
                $parent_id      =  $this->input->post('parent_id');
                $user_group_id  =  $this->input->post('user_group_id');
                
                //   Insert Parent Rights
                if( check_if_parent_user_group_assigned($parent_id) ){
                    $assign_group['user_group_id']  =  $user_group_id;
                    $assign_group['school_id']      =  $_SESSION['school_id'];
                    $assign_group['parent_id']      =  $parent_id;
                    $this->db->insert(get_school_db() . '.user_rights', $assign_group);
                }
                else
                {
                    $user_group_update['user_group_id'] = $user_group_id;
                    $this->db->update(get_school_db().'.user_rights', $user_group_update , array("parent_id" => $parent_id, 'school_id'=> $_SESSION['school_id']));
                }
                
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                $this->session->set_flashdata('club_updated', get_phrase('parent_login_updated_successfully'));
                redirect(base_url().'c_student/student_information');
            }
            
            
        }

        function validate_login_email($action = "")
        {
            $email = $this->input->post('email');
            $arr = $this->db->get_where(get_system_db().'.user_login', array('email' => $email))->result_array();
            $response = array('status' => 'false');
            if (count($arr) > 0)
            {
                $response['status'] = 'false';
                $response['message'] = get_phrase('this_email_is_not_available');
            }
            else
            {
                $response['status'] = 'success';
            }

            echo json_encode($response);
        }

        function staff_timing()
        {
            $start_date= $this->input->post('start_date');
            $end_date= $this->input->post('end_date');
            $staff_id=$this->input->post('staff_id');
            $timing_qur=$this->db->query("select * from ".get_school_db().".staff_in_out  where (io_date between '$start_date' and  '$end_date') and staff_id = $staff_id and school_id=".$_SESSION['school_id'])->result_array();
            $time_in=array();
            $time_out=array();
            $date_ary=array();
            $time=1;
            foreach($timing_qur as $row){
                $to_time = ($row['io_date']." ".$row['io_time']);

                $date_ary[$row['io_date']]=$row['io_date'];

                if($row['io_date']==$pre_date){

                }else{
                    $pre_date=$row['io_date'];
                    $time=1;
                }
                if($time==1)
                {
                    $time_in[$row['io_date']][]=$to_time;
                    $time++;
                }else
                {
                    $time_out[$row['io_date']][]=$to_time;
                    $time=1;
                }
            }

            $final_date=array();

            foreach($date_ary as $key=>$val){
                $i=0;
                $total_time=0;
                if(isset($time_out[$key][$i])){
                    foreach($time_out[$key] as $key1=>$val1){
                        $total_time+= strtotime($time_out[$key][$i])-strtotime($time_in[$key][$i]);
                        $i++;
                    }
                    if(isset($time_in[$val][$i])){
                        $final_date[$key]['extra_in']=$time_in[$val][$i];
                    }
                    $final_date[$key]['time']=gmdate("H:i:s", $total_time);
                }
            }

            $data_pass['date_ary']=$date_ary;
            $data_pass['final_date']=$final_date;
            $data_pass['time_out']=$time_out;
            $data_pass['time_in']=$time_in;

            $this->load->view('backend/admin/ajax/staff_timing_ajax', $data_pass);
    
        }
        
        function create_staff_card($condition,$staff_id)
        {
            if($condition=='staff'){
                $page_data['staff_id']  	= str_decode($staff_id);
            }else{
                $page_data['section_id']  	= $staff_id;
            }
            $page_data['page_name']  	= 'create_staff_card';
            $page_data['page_title']=get_phrase('create_card');
            $this->load->view('backend/index', $page_data);

        }

        function add_parent_account($student_id = 0)
        {
            $page_data['page_name'] = 'add_parent_login_account';
            $page_data['page_title'] = get_phrase('create_parent_account');
            $page_data['student_id'] = $student_id;
            $this->load->view('backend/index', $page_data);
        }

        function add_edit_staff()
        {
            $school_id=$_SESSION['school_id'];
            $param2=str_decode($this->uri->segment(3));
            if($param2 == "")
            {
                $param2 = 0;
            }
            $qry = "SELECT s.staff_id as s_id , s.*, d.title as designation, d.is_teacher , 
                        (select gross_salary from ".get_school_db().".staff_payroll_settings where staff_id = s.staff_id ) as gross_salary
                        FROM ".get_school_db().".staff s left JOIN ".get_school_db().".designation d ON d.designation_id = s.designation_id WHERE s.school_id=".$_SESSION['school_id']." and s.staff_id = ".$param2." ";//DESC
            $edit_data = $this->db->query($qry)->result_array();
            $page_data['edit_data'] = $edit_data;
            $page_data['page_name'] = 'staff_add_edit';
            $page_data['page_title'] = get_phrase('staff_add_edit');
            $this->load->view('backend/index', $page_data);
        }

        public function user_designation($param1 = '',$param2 = '', $param3 = '')
        {

            if($param1 == 'add'){
                $data['title']     = $this->input->post('title');
                $data['parent_id']  = intval($this->input->post('parent_id'));
                $data['status']  = $this->input->post('status');
                $data['is_teacher']  = $this->input->post('is_teacher');
                $data['school_id']  = $_SESSION['school_id'];

                $this->session->set_flashdata('club_updated', get_phrase('record_saved_sucessfully'));
                $this->db->insert(get_school_db().'.designation', $data);

                redirect(base_url() . 'user/user_designation');
            }

            else if($param1 == 'update'){

                $data['is_teacher']  = $this->input->post('is_teacher');
                $data['title']     = $this->input->post('title');
                $data['parent_id']  = $this->input->post('parent_id');
                $data['status']  = $this->input->post('status');
                $data['school_id']  = $_SESSION['school_id'];

                $des_id = $this->input->post('designation_id');
                $this->session->set_flashdata('club_updated', get_phrase('record_saved_sucessfully'));

                $this->db->where('designation_id',$des_id);
                $this->db->update(get_school_db().'.designation', $data);

                redirect(base_url() . 'user/user_designation');
            }

            else if($param1 == 'delete'){

                $qur_1=$this->db->query("select staff_id from ".get_school_db().".staff where designation_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();

                if(count($qur_1)>0){
                    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));

                    redirect(base_url() . 'user/user_designation');
                    exit();
                }

                $qur_2=$this->db->query("select designation_id from ".get_school_db().".designation where parent_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
              
                if(count($qur_2)>0){
                    $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));

                    redirect(base_url() . 'user/user_designation');
                    exit();
                }

                $this->db->where('designation_id', $param2);
                $this->db->delete(get_school_db().'.designation');
                $this->session->set_flashdata('club_updated', get_phrase('record_saved_sucessfully'));

                redirect(base_url() . 'user/user_designation');
            }

            $this->get_designation_child();

            $page_data['designation_data'] = $this->designation_arr;
            $page_data['page_name']  = 'user_designation';
            $page_data['page_title'] = get_phrase('manage_designation');
            $this->load->view('backend/index', $page_data);

        }

        function get_designation_child($parent_id=0)
        {
            $school_id=$_SESSION['school_id'];
            $des_arr = $this->db->get_where(
                get_school_db().'.designation',
                array(
                    'parent_id'=>$parent_id,
                    'school_id'=>$school_id,
                )
            )->result_array();

            $this->designation_arr[]= "<ul id='tree3' class='col-md-12 col-lg-12 nav-list'>";

            foreach($des_arr as $arr){
                $url='';
                $delete='';
                if (right_granted('designation_manage'))
                {
                    $url='<a class="fle" href="#" onclick="showAjaxModal(\''.base_url().'modal/popup/modal_add_edit_designation/'.$arr['designation_id'].'\')" ><i class="" style="display:inline !important;"></i> '.get_phrase('edit').'</a>';
                }
                if (right_granted('designation_delete'))
                {
                    $delete='<a class="fld" href="#" onclick="confirm_modal(\''.base_url().'user/user_designation/delete/'.$arr['designation_id'].'\')"><i class="entypo-trash"  style="display:inline !important;"></i> '.get_phrase('delete').'</a>';
                }

                $is_teacher="";

                if($arr['is_teacher']==1){
                    $is_teacher=" (".get_phrase('teaching_staff').")";
                }
                $is_active="";

                if($arr['status']==1){
                    $is_active="<span class='green'>(".get_phrase('active').")</span>";
                }else{
                    $is_active="<span class='orange'>(".get_phrase('inactive').")</span>";
                }

                $click='onclick="active(\''.$arr['designation_id'].'\')"';
                $this->designation_arr[]="<li $click class='act".$arr['designation_id']."'>".$arr['title']." ".$is_active."".$is_teacher."$url  $delete";
                $des_arr1 = $this->db->get_where(
                    get_school_db().'.designation',
                    array(
                        'parent_id'=>$arr['designation_id'],
                        'school_id'=>$school_id
                    )
                )->result_array();

                if(count($des_arr1)>0){
                    $this->get_designation_child($arr['designation_id']);
                }
                $this->designation_arr[]= "</li>";
            }
            $this->designation_arr[]= "</ul>";
            
            

        }

        public function save_group($param1='',$param2='',$param3='')
        {
            if($param1=='create')
            {
                $data['title']=trim($this->input->post('title'));
                $data['status']=trim($this->input->post('status'));
                $data['type']=trim($this->input->post('type'));
                $data['description']=trim($this->input->post('description'));
                $data['school_id'] = $_SESSION['school_id'];

                $this->session->set_flashdata('club_updated', get_phrase('record_saved_sucessfully'));
                $this->db->insert(get_school_db().'.user_group',$data);
                redirect(base_url().'user/user_groups/');
            }
            elseif($param1=='edit')
            {
                $data['title']=$this->input->post('title');
                $data['status']=$this->input->post('status');
                $data['type']=trim($this->input->post('type'));
                $data['description']=$this->input->post('description');
                $data['school_id']=$_SESSION['school_id'];

                $this->db->where('user_group_id',str_decode($param2));
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->update(get_school_db().'.user_group',$data);
                $this->session->set_flashdata('club_updated', get_phrase('record_updated_sucessfully'));

                redirect(base_url().'user/user_groups/');

            }
            elseif($param1=='delete')
            {
                $data['user_group_id']=str_decode($param2);
                $this->db->delete(get_school_db().'.user_group',$data);
                $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));

                redirect(base_url().'user/user_groups/');
            }
        }

        public function assign_rights($param1='' , $param2='')
        {
            $page_data['user_group_id'] = str_decode($param1);
            $user_group_type = str_decode($param2);
            $page_data['user_group_type'] = $user_group_type;
            $user_group_id = str_decode($param1);
            $page_data['page_name'] = 'assign_rights';
            
            if($user_group_type == 1){
                $query=$this->db->query("select distinct m.module_id,m.title as module, m.parent_module_id 
                from ".get_system_db().".package_rights p 
                inner join ".get_system_db().".action a on a.action_id=p.action_id 
                inner join ".get_system_db().".module m on m.module_id=a.module_id 
                where 
                m.module_type = 1 and
                package_id=".$_SESSION['package_id']." order by module asc");
            	
            	$modules=$query->result_array();
                $package_rights = array();
                foreach ($modules as $mod) 
                {
                    $action_arr = $this->db->query("select p.*, m.module_id, m.title as module_title, m.parent_module_id, a.title as action 
                                        from ".get_system_db().".package_rights  p 
                                        inner join ".get_system_db().".action a on (a.action_id=p.action_id and a.status = 1)
                                        inner join ".get_system_db().".module m on m.module_id=a.module_id 
                                        where 
                                        a.action_type = 1 and
                                        p.package_id=".$_SESSION['package_id']." 
                                        and a.module_id=".$mod['module_id'])->result_array();
                    foreach ($action_arr as $act_key => $act_value) 
                    {
                        $parent_module_arr = $this->db->query("select title,module_id,parent_module_id from ".get_system_db().".module 
                                where 
                                module_type = 1 and
                                module_id =".intval($act_value['parent_module_id'])." 
                                ")->result_array();
            
                        if (count($parent_module_arr)  > 0)
                        {
                            $package_rights[$parent_module_arr[0]['title']][$act_value['module_title']][] = array(
                                    'action_id' => $act_value['action_id'], 
                                    'action_title' => $act_value['action'], 
                                    'module_id' => $act_value['module_id'] 
                                    );
                        }
                        else
                        {
                            $package_rights['NA'][$act_value['module_title']][] = array(
                                    'action_id' => $act_value['action_id'], 
                                    'action_title' => $act_value['action'], 
                                    'module_id' => $act_value['module_id'] 
                                    );
                        }
                    }
            
                    $selectedAct = $this->db->query("select action_id from ".get_school_db().".group_rights where user_group_id=". $user_group_id)->result_array();
                    
                    foreach($selectedAct as $sel)
                    {
                        $sel2[]=$sel['action_id'];
                    }
                }
                
                // admin rights
                $page_data['package_rights'] = $package_rights;
                $page_data['sel2'] = $sel2;
            }else{
                $page_data['package_rights'] = array();
                $page_data['sel2'] = array();
            }
            
            
            //*****************************************************************
            //*********************Teacher**************************************
            
            if($user_group_type == 3){
                $query=$this->db->query("select distinct m.module_id,m.title as module, m.parent_module_id 
                from ".get_system_db().".package_rights p 
                inner join ".get_system_db().".action a on a.action_id=p.action_id 
                inner join ".get_system_db().".module m on m.module_id=a.module_id 
                where 
                m.module_type = 2 and
                package_id=".$_SESSION['package_id']." order by module asc");
            	
            	$modules=$query->result_array();
                $teacher_package_rights = array();
                foreach ($modules as $mod) 
                {
                    $action_arr = $this->db->query("select p.*, m.module_id, m.title as module_title, m.parent_module_id, a.title as action 
                                        from ".get_system_db().".package_rights  p 
                                        inner join ".get_system_db().".action a on (a.action_id=p.action_id and a.status = 1)
                                        inner join ".get_system_db().".module m on m.module_id=a.module_id 
                                        where 
                                        a.action_type = 2 and
                                        p.package_id=".$_SESSION['package_id']." 
                                        and a.module_id=".$mod['module_id'])->result_array();
                    foreach ($action_arr as $act_key => $act_value) 
                    {
                        $parent_module_arr = $this->db->query("select title,module_id,parent_module_id from ".get_system_db().".module 
                                where 
                                module_type = 2 and
                                module_id =".intval($act_value['parent_module_id'])." 
                                ")->result_array();
            
                        if (count($parent_module_arr)  > 0)
                        {
                            $teacher_package_rights[$parent_module_arr[0]['title']][$act_value['module_title']][] = array(
                                    'action_id' => $act_value['action_id'], 
                                    'action_title' => $act_value['action'], 
                                    'module_id' => $act_value['module_id'] 
                                    );
                        }
                        else
                        {
                            $teacher_package_rights['NA'][$act_value['module_title']][] = array(
                                    'action_id' => $act_value['action_id'], 
                                    'action_title' => $act_value['action'], 
                                    'module_id' => $act_value['module_id'] 
                                    );
                        }
                    }
            
                    $selectedAct = $this->db->query("select action_id from ".get_school_db().".group_rights where user_group_id=". $user_group_id)->result_array();
                    
                    foreach($selectedAct as $sel)
                    {
                        $sel3[]=$sel['action_id'];
                    }
                }
                
                // teacher rights
                $page_data['teacher_package_rights'] = $teacher_package_rights;
                $page_data['sel3'] = $sel3;
            }else{
                $page_data['teacher_package_rights'] = array();
                $page_data['sel3'] = array();
            }
            
            //*****************************************************************
            //**************************Student*********************************
            
            if($user_group_type == 6){
                $query=$this->db->query("select distinct m.module_id,m.title as module, m.parent_module_id 
                from ".get_system_db().".package_rights p 
                inner join ".get_system_db().".action a on a.action_id=p.action_id 
                inner join ".get_system_db().".module m on m.module_id=a.module_id 
                where 
                m.module_type = 3 and
                p.package_id=".$_SESSION['package_id']." order by module asc");
            	
            	$modules=$query->result_array();
                $student_package_rights = array();
                foreach ($modules as $mod) 
                {
                    $action_arr = $this->db->query("select p.*, m.module_id, m.title as module_title, m.parent_module_id, a.title as action 
                                        from ".get_system_db().".package_rights  p 
                                        inner join ".get_system_db().".action a on (a.action_id=p.action_id and a.status = 1)
                                        inner join ".get_system_db().".module m on m.module_id=a.module_id 
                                        where 
                                        a.action_type = 3 and
                                        p.package_id=".$_SESSION['package_id']." 
                                        and a.module_id=".$mod['module_id'])->result_array();
                    foreach ($action_arr as $act_key => $act_value) 
                    {
                        $parent_module_arr = $this->db->query("select title,module_id,parent_module_id from ".get_system_db().".module 
                                                               where module_type = 3 and module_id =".intval($act_value['parent_module_id'])." ")->result_array();
            
                        if (count($parent_module_arr)  > 0)
                        {
                            $student_package_rights[$parent_module_arr[0]['title']][$act_value['module_title']][] = array(
                                    'action_id' => $act_value['action_id'], 
                                    'action_title' => $act_value['action'], 
                                    'module_id' => $act_value['module_id'] 
                                    );
                        }
                        else
                        {
                            $student_package_rights['NA'][$act_value['module_title']][] = array(
                                    'action_id' => $act_value['action_id'], 
                                    'action_title' => $act_value['action'], 
                                    'module_id' => $act_value['module_id'] 
                                    );
                        }
                    }
            
                    $selectedAct = $this->db->query("select action_id from ".get_school_db().".group_rights where user_group_id=". $user_group_id)->result_array();
                    
                    foreach($selectedAct as $sel)
                    {
                        $sel4[]=$sel['action_id'];
                    }
                }
                
                // student rights
                $page_data['student_package_rights'] = $student_package_rights;
                $page_data['sel4'] = $sel4;
            }else{
                $page_data['student_package_rights'] = array();
                $page_data['sel4'] = array();
            }
            
            //*****************************************************************
            //**************************Parent*********************************
            
            if($user_group_type == 4){
                $query=$this->db->query("select distinct m.module_id,m.title as module, m.parent_module_id 
                from ".get_system_db().".package_rights p 
                inner join ".get_system_db().".action a on a.action_id=p.action_id 
                inner join ".get_system_db().".module m on m.module_id=a.module_id 
                where 
                m.module_type = 4 and
                package_id=".$_SESSION['package_id']." order by module asc");
            	
            	$modules=$query->result_array();
                $parent_package_rights = array();
                foreach ($modules as $mod) 
                {
                    $action_arr = $this->db->query("select p.*, m.module_id, m.title as module_title, m.parent_module_id, a.title as action 
                                        from ".get_system_db().".package_rights  p 
                                        inner join ".get_system_db().".action a on (a.action_id=p.action_id and a.status = 1)
                                        inner join ".get_system_db().".module m on m.module_id=a.module_id 
                                        where 
                                        a.action_type = 4 and
                                        p.package_id=".$_SESSION['package_id']." 
                                        and a.module_id=".$mod['module_id'])->result_array();
                    foreach ($action_arr as $act_key => $act_value) 
                    {
                        $parent_module_arr = $this->db->query("select title,module_id,parent_module_id from ".get_system_db().".module 
                                where 
                                module_type = 4 and
                                module_id =".intval($act_value['parent_module_id'])." 
                                ")->result_array();
            
                        if (count($parent_module_arr)  > 0)
                        {
                            $parent_package_rights[$parent_module_arr[0]['title']][$act_value['module_title']][] = array(
                                    'action_id' => $act_value['action_id'], 
                                    'action_title' => $act_value['action'], 
                                    'module_id' => $act_value['module_id'] 
                                    );
                        }
                        else
                        {
                            $parent_package_rights['NA'][$act_value['module_title']][] = array(
                                    'action_id' => $act_value['action_id'], 
                                    'action_title' => $act_value['action'], 
                                    'module_id' => $act_value['module_id'] 
                                    );
                        }
                    }
            
                    $selectedAct = $this->db->query("select action_id from ".get_school_db().".group_rights where user_group_id=". $user_group_id)->result_array();
                    
                    foreach($selectedAct as $sel)
                    {
                        $sel5[]=$sel['action_id'];
                    }
                }
                
                
                // parent rights
                $page_data['parent_package_rights']  = $parent_package_rights;
                $page_data['sel5'] = $sel5;
            }
            else{
                $page_data['parent_package_rights'] = array();
                $page_data['sel5'] = array();
            }
            
            $page_data['page_title'] = get_phrase('assign_rights');
            $this->load->view('backend/index', $page_data);

        }

        function assign_right()
        {
            $data['user_group_id']=$this->input->post('user_type_id');
            $this->db->delete(get_school_db().'.group_rights',$data);
            //echo $this->db->last_query();exit;
            $module_id=$this->input->post('module_id');
            $action_id=$this->input->post('action_id');
            $user_type_id=$this->input->post('user_type_id');
            $data['user_group_id']=$user_type_id;
            $data['school_id'] = intval($_SESSION['school_id']);

            //die();
            foreach($action_id as $row)
            {
                $data['action_id'] = intval(trim($row['value']));
                $this->session->set_flashdata('club_updated', get_phrase('record_saved_sucessfully'));
                $this->db->insert(get_school_db().'.group_rights', $data);
                //echo $this->db->last_query();
            }
            echo "added";
            exit;
        }

        function get_actions($parent_id=0)
        {

            //$school_id=$_SESSION['school_id'];

            $modules=$this->db->query("select * from ".get_school_db().".modules where parent_id=$parent_id ")->result_array();

            $this->menu_ary[]= " <ul  id= 'tree3' class='nav-list'>";

            foreach($modules as $mod){

                $url='<a class="fle" href="#" onclick="showAjaxModal(\''.base_url().'modal/popup/modal_coa_edit:accountant/'.$mod['module_id'].'\')" >'.get_phrase('edit').' |</a>  ';
                $delete='<a class="fld" href="#" onclick="confirm_modal(\''.base_url().'chart_of_account/coa/delete/'.$mod['module_id'].'\')">'.get_phrase('delete').'</a>'	;
                $click='onclick="active(\''.$mod['module_id'].'\')"';

                $this->menu_ary[]="<li $click class='act".$mod['module_id']."'>".$utype['account_head']."<i class='fa myarrow fa-arrows-h' aria-hidden='true'></i>".$mod['title']."$url  $delete";

                $action=$this->db->query("select * from ".get_school_db().".action module_id=".$mod['module_id'])->result_array();

                if(count($action)>0){
                    $this->get_actions($mod['module_id']);
                }
                $this->menu_ary[]= "</li>";
            }

            $this->menu_ary[]= "</ul>";

        }
        function get_module_hierarchy()
        {

            $module_id=$this->input->post('module_id');
            $mod="select m.* from ".get_school_db().".module m where m.status=1 and m.module_id=$module_id";
            $res=$this->db->query($mod)->result_array();
            $data['res']=$res;
            $data['user_type_id']=$this->input->post('user_type_id');

            $this->load->view('backend/admin/ajax/rights', $data);

        }

        function teacher($action = '', $id = 0)
        {
            
            $designation_id =   $this->input->post('designation_id');
            $apply_filter   =   $this->input->post('apply_filter');
            
            $designation_filter = '';
            if($designation_id != '')
            {
                $designation_filter = "and s.designation_id = $designation_id";
                $page_data['filter'] = true;
                $page_data['filter_id'] = $id;
            }

            $qry = "SELECT s.*, d.title as designation FROM ".get_school_db().".staff s 
            INNER JOIN ".get_school_db().".designation d ON d.designation_id = s.designation_id
            WHERE d.is_teacher=1  and s.school_id=".$_SESSION['school_id']." $designation_filter ORDER BY s.staff_id ";//DESC
            $res_array = $this->db->query($qry)->result_array();

            $page_data['data'] = $res_array;
            $page_data['designation_id'] = $designation_id;
            $page_data['apply_filter'] = $apply_filter;
            $page_data['page_name'] = 'teacher';
            $page_data['page_title'] = get_phrase('staff');
            $this->load->view('backend/index', $page_data);
        }

        function check_cnic()
        {
            $cnic=$this->input->post('cnic');
            $qur=$this->db->query($qure="select * from ".get_school_db().".staff where id_no='$cnic' and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur)>0) { echo 'no'; }
            else{ echo 'yes'; }
        }

        function check_is_teacher()
        {
            $designation_id=$this->input->post('designation_id');
            $staff_id=$this->input->post('staff_id');

            $qur=$this->db->query($qure="select is_teacher from ".get_school_db().".designation where designation_id=".$designation_id." and is_teacher=1 and school_id=".$_SESSION['school_id'])->result_array();

            $res['count']= $qur[0]['is_teacher'];
            if($qur[0]['is_teacher']==1){

                if($staff_id!=''){

                    $week="SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
                           inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
                           inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
                           where st.teacher_id=".$staff_id." and st.school_id=".$_SESSION['school_id']."";

                    $perids_per_week=$this->db->query($week)->result_array();
                    if($perids_per_week[0]['count']>0){
                        $res['week']=$perids_per_week[0]['count'];
                    }

                    $day1="SELECT (count(st.teacher_id)) as period_count FROM ".get_school_db().".time_table_subject_teacher ttst 
                           inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
                           inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
                           where st.teacher_id=".$staff_id." and st.school_id=".$_SESSION['school_id']."  Group by cr.day order by period_count desc limit 1";

                    $perids_per_day=$this->db->query($day1)->result_array();
                    if($perids_per_day[0]['period_count']>0){
                        $res['day']=$perids_per_day[0]['period_count'];
                    }

                }

            }
            else if($qur[0]['is_teacher']==0){
                if($staff_id!=''){
                    $subject_teacher=$this->db->query($q="select teacher_id from ".get_school_db().".subject_teacher where teacher_id=".$staff_id." and school_id=".$_SESSION['school_id']."")->num_rows();
                    if($subject_teacher>0)
                    {
                        $res['subject_teacher']='true';
                    }
                }

            }
            echo json_encode($res);
        }
        
        function leave_settings($param1 = '', $param2 = '', $param3 = '')
        {
			if ($param1 == 'create') 
			{
				$staff_id=$this->uri->segment('4');
				$monthly_limit=$this->input->post('monthly_limit');
				$yearly_limit=$this->input->post('yearly_limit');
				
				$leave_category_id=$this->input->post('leave_category_id');
				$data['staff_id']=$staff_id;
				$data['school_id']=$_SESSION['school_id'];
				
				foreach($leave_category_id as $key=>$value)
				{
					$data['leave_category_id']=$value;
					$monthly_limit_arr=0;
					$yearly_limit_arr=0;
					
					if(isset($monthly_limit[$value]) && ($monthly_limit[$value])!= "")
					{
						$monthly_limit_arr=$monthly_limit[$value];
					}
					if(isset($yearly_limit[$value]) && ($yearly_limit[$value])!= "")
					{
						$yearly_limit_arr=$yearly_limit[$value];
						
					}
					if(($monthly_limit_arr > 0) || ($yearly_limit_arr > 0))
					{
						$data['monthly_limit']=$monthly_limit_arr;
						
						$data['yearly_limit']=$yearly_limit_arr;
						$this->db->where('leave_category_id',$value);		
				        $this->db->where('staff_id',$staff_id);
				        $this->db->where('school_id',$_SESSION['school_id']);
				        $this->db->delete(get_school_db().'.staff_leave_settings');
						$this->db->insert(get_school_db().'.staff_leave_settings',$data);
					}	
				}
				
				$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
                redirect(base_url() . 'user/staff_listing/');
				
			}
			
		}
		
		function reset_users_password()
		{
		    $page_data['page_name'] = 'reset_users_password';
            $page_data['page_title'] = get_phrase('reset_users_password');
            $this->load->view('backend/index', $page_data);
		}
		
		function get_user_login_details()
		{
		    $user_id = $this->input->post("student_id");
		    $get_data = "SELECT s.name,ul.id_no,ul.user_login_id FROM ".get_school_db().".student s 
                   INNER JOIN ".get_system_db().".user_login_details uld on uld.user_login_detail_id = s.user_login_detail_id 
                   INNER JOIN ".get_system_db().".user_login ul on ul.user_login_id = uld.user_login_id 
                   WHERE s.student_id=".$user_id." and s.school_id=".$_SESSION['school_id']." and uld.login_type = 6 ";
            $user_detail_data = $this->db->query($get_data)->row();
            if(count($user_detail_data) > 0)
            {
            $display_data = "
                <form id='reset_password' method='post'>
                    <table class='table table-bordered'>
                        <thead>
                            <th>Name</th>
                            <th>Username</th>
                            <th>New Password</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>".$user_detail_data->name."</td>
                                <td>".$user_detail_data->id_no."</td>
                                <td>
                                    <input type='password' class='form-control edu_password_validation' name='new_password' required>
                                    <span class='text-danger edu_password_validation_msg'></span><br>
                                    <input type='hidden' name='ul_id' value='".$user_detail_data->user_login_id."'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='3' align='right'>
                                    <button class='modal_save_btn' type='submit'>Reset Password</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <script>
                    $('#reset_password').on('submit',function(e){
                       e.preventDefault();
                       $.ajax({
                            type: 'POST',
                            url: '".base_url()."user/update_reset_password/',
                            dataType: 'html',
                            data:new FormData(this),
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(response) {
                                if (response != '') {
                                    Command: toastr['success'](response, 'Alert')
                                    toastr.options.positionClass = 'toast-bottom-right';
                                    $('#reset_password')[0].reset();
                                }
                            }
                        });
                    });
                    $('.edu_password_validation').on('keyup',function(){
                        var inputtxt = $(this).val();
                        var decimal=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
                    
                        if(inputtxt.match(decimal)) 
                        { 
                            $('input[type=\"submit\"]').removeAttr('disabled');
                            $('button[type=\"submit\"]').removeAttr('disabled');
                            $('.edu_password_validation_msg').text('');

                        }else{
                            $('.edu_password_validation_msg').text('Input Password and Submit [8 to 15 characters which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character]');
                            $('input[type=\"submit\"]').attr('disabled',true);
                            $('button[type=\"submit\"]').attr('disabled',true);

                        }
                    });
                </script>
            ";
            }else{
                $display_data = "<div class='alert alert-danger'><b>Credential Not Created</b></div>";
            }
            echo $display_data;
		}
		
		function get_staff_user_login_details()
		{
		    $user_id = $this->input->post("staff_id");
		    $get_staff_email = get_staff_detail($user_id);
		    $email = $get_staff_email[0]['email'];
		    $name = $get_staff_email[0]['name'];
		    
		    $check_email_exist = $this->db->query("SELECT ul.email FROM ".get_system_db().".user_login ul INNER JOIN user_login_details uld ON uld.user_login_id = ul.user_login_id WHERE ul.email = '$email' AND ul.email <> '' AND uld.sys_sch_id = ".$_SESSION['school_id']." ");
            if($check_email_exist->num_rows() > 0)
            {
            $display_data = "
                <form id='reset_password' method='post'>
                    <table class='table table-bordered'>
                        <thead>
                            <th>Name</th>
                            <th>Username</th>
                            <th>New Password</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>".$name."</td>
                                <td>".$email."</td>
                                <td>
                                    <input type='password' class='form-control edu_password_validation' name='new_password' required>
                                    <span class='text-danger edu_password_validation_msg'></span><br>
                                    <input type='hidden' name='email' value='".$email."'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='3' align='right'>
                                    <button class='modal_save_btn' type='submit'>Reset Password</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <script>
                    $('#reset_password').on('submit',function(e){
                      e.preventDefault();
                      $.ajax({
                            type: 'POST',
                            url: '".base_url()."user/staff_update_reset_password/',
                            dataType: 'html',
                            data:new FormData(this),
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(response) {
                                if (response != '') {
                                    Command: toastr['success'](response, 'Alert')
                                    toastr.options.positionClass = 'toast-bottom-right';
                                    $('#reset_password')[0].reset();
                                }
                            }
                        });
                    });
                    $('.edu_password_validation').on('keyup',function(){
                        var inputtxt = $(this).val();
                        var decimal=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
                    
                        if(inputtxt.match(decimal)) 
                        { 
                            $('input[type=\"submit\"]').removeAttr('disabled');
                            $('button[type=\"submit\"]').removeAttr('disabled');
                            $('.edu_password_validation_msg').text('');

                        }else{
                            $('.edu_password_validation_msg').text('Input Password and Submit [8 to 15 characters which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character]');
                            $('input[type=\"submit\"]').attr('disabled',true);
                            $('button[type=\"submit\"]').attr('disabled',true);

                        }
                    });
                </script>
            ";
            }else{
                $display_data = "<div class='alert alert-danger'><b>Credential Not Created</b></div>";
            }
            echo $display_data;
		}
		
		function update_reset_password()
		{
		    $ul_id = $this->input->post('ul_id');
		    $new_password = passwordHash($this->input->post('new_password'));
		    
		    $q = $this->db->query("UPDATE ".get_system_db().".user_login SET password = '$new_password', is_password_updated = 1 WHERE user_login_id = '$ul_id'");
		    if($q)
		    {
		        echo "Password Updated Successfully";
		    }
		}
		
		function staff_update_reset_password()
		{
		    $email = $this->input->post('email');
		    $new_password = passwordHash($this->input->post('new_password'));
		    
		    $q = $this->db->query("UPDATE ".get_system_db().".user_login SET password = '$new_password', is_password_updated = 1 WHERE email = '$email'");
		    if($q)
		    {
		        echo "Password Updated Successfully";
		    }
		}
		
		function attenndance_app_creds($param="")
		{
		    if($param == "save"){
		        
		        $data['username']   = $this->input->post('username');
		        $data['password']   = passwordHash($this->input->post('password'));
		        $data['school_id']  = $_SESSION['school_id'];
		        $data['school_db']  = $_SESSION['school_db'];  
		        $data['status']     = $this->input->post('status');
		        
		        $this->db->insert(get_system_db().'.attendance_app_login',$data);
		        $this->session->set_flashdata('club_updated',get_phrase('record_added_successfully'));
		        
		    }else if($param == "update"){
		        
		        $id   = $this->input->post('id');
		        
		        $data['username']   = $this->input->post('username');
		        $data['password']   = passwordHash($this->input->post('password'));
		        $data['school_id']  = $_SESSION['school_id'];
		        $data['school_db']  = $_SESSION['school_db'];  
		        $data['status']     = $this->input->post('status');
		        
		        $this->db->where('id' , $id);
                $this->db->update(get_system_db().'.attendance_app_login',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
		    }
		    
		    $attendance_creds = $this->db->query("select * from ".get_system_db().".attendance_app_login WHERE school_id = ".$_SESSION['school_id']." ")->row();
		    $page_data['attendance_creds'] = $attendance_creds;
		    $page_data['page_name'] = 'attenndance_app_creds';
            $page_data['page_title'] = get_phrase('attenndance_app_creds');
            $this->load->view('backend/index', $page_data);
		}
		
		function error404() {
          $this->load->view('errors/html/error404'); 
        }
    }