<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

ob_start();
class Parents extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$prefs = array (
            'show_next_prev'    => TRUE,
            'next_prev_url'     =>  base_url().'parents/calendar_view',
            'start_day'         => 'saturday',
            'month_type'        => 'long',
            'day_type'          => 'short'
        );

        $prefs['template'] = '
            {table_open}<table cellpadding="1" cellspacing="2">{/table_open}
            {heading_row_start}<tr>{/heading_row_start}
            {heading_previous_cell}<th class="prev_sign"><a href="#" class="btn btn-primary text-white" style="font-size: 16px;position: relative;bottom: 10px;" onclick="next_prev(\'{previous_url}\');"><i class="fas fa-angle-double-left text-white"></i> <i class="fas fa-angle-double-left text-white" style="position:relative;left:-10px"></i></a></th>{/heading_previous_cell}
            {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
            {heading_next_cell}<th><a href="#" class="btn btn-primary text-white" style="font-size: 16px;position: relative;bottom: 10px;" onclick="next_prev(\'{next_url}\');"><i class="fas fa-angle-double-right text-white"></i> <i class="fas fa-angle-double-right text-white" style="position:relative;left:-10px"></i></a></th>{/heading_next_cell}
            
            {heading_row_end}</tr>{/heading_row_end}
            
            //Deciding where to week row start
            {week_row_start}<tr class="week_name">{/week_row_start}
            //Deciding  week day cell and  week days
            {week_day_cell}<td>{week_day}</td>{/week_day_cell}
            //week row end
            {week_row_end}</tr>{/week_row_end}
            
            {cal_row_start}<tr>{/cal_row_start}
            {cal_cell_start}<td>{/cal_cell_start}
            
            {cal_cell_content}{day}<ul class="latest_event" style="display: block;width: 100%;" title="sdkfjskdfkskj">{content}</ul>{/cal_cell_content}
            {cal_cell_content_today}<div class="highlight_day">{day}<ul class="latest_event" title="sdkfjskdfkskj">{content}</ul></div>{/cal_cell_content_today}
            
            {cal_cell_no_content}{day}{/cal_cell_no_content}
            {cal_cell_no_content_today}<div class="highlight_day">{day}</div>{/cal_cell_no_content_today}
            
            {cal_cell_blank}&nbsp;{/cal_cell_blank}
            
            {cal_cell_end}</td>{/cal_cell_end}
            {cal_row_end}</tr>{/cal_row_end}
            
            {table_close}</table>{/table_close}
        ';
        $this->load->library('calendar', $prefs);
		
		/*cache control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        
		if($_SESSION['user_login']!= 1)
			redirect(base_url() . 'login');

		if( get_login_type_name($_SESSION['login_type']) != 'parent')
			redirect(base_url() . 'login');

		$this->load->library('BlinqApi');
		$this->load->model('Crud_model');
		
		
	}

	/***default functin, redirects to login page if no admin logged in yet***/
	public function index()
	{
		if( get_login_type_name($_SESSION['login_type']) != 'parent')
			redirect(base_url() . 'login');
		
		if(get_login_type_name($_SESSION['login_type']) != 'parent')
			redirect(base_url() . ''.get_login_type_controller($_SESSION['login_type']).'/dashboard');
		
	}
	 
    function get_child_evaluation_results(){
        $data['staff_id']=$this->input->post('staff_id');
        $data['evaluation_types']=$this->input->post('evaluation_types');
        $this->load->view("backend/parent/ajax/get_child_evaluation_results",$data);
    }
    
    
    function child_evaluation_results(){
        $page_data['page_title'] = get_phrase('child_evaluation_results');
        $page_data['page_name']  = 'child_evaluation_results';
        $this->load->view('backend/index', $page_data);
    }
    
	public function change_password(){
	    
	    if($_SESSION['mobile_code'] == $this->input->post('otp'))
	    {
            $data['new_password']         = passwordHash($this->input->post('new_password'));
            $student_login_id             = passwordHash($this->input->post('student_login_id'));
            $new_pass                     = passwordHash($this->input->post('new_password'));
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            if($new_pass != ""){
                if ($data['new_password'] == $data['confirm_new_password']) {
                    $this->db->where('user_login_id',$student_login_id);
                    $this->db->update(get_system_db().'.user_login', array('password' => $data['new_password']));
                    $this->session->set_flashdata('flash_message', get_phrase('Password Updated'));
                
                } 
                else {
                    $this->session->set_flashdata('club_message', get_phrase('password_mismatch'));
                } 
            }
            else{
                $this->session->set_flashdata('club_message', get_phrase('new_password_is_required'));
            }       
            $_SESSION['mobile_code'] = 0;  
        }
        else{
                $this->session->set_flashdata('club_message', get_phrase('otp_code_is_not_correct'));
        }
		$page_data['page_name']  = 'update_password';
		$page_data['page_title'] = get_phrase('update_password');
		$this->load->view('backend/index', $page_data);
	}
    public function update_password()
	{
	    $this->load->helper('message');
        
        $query    =  $this->db->get_where(get_school_db().'.student', array('student_id' => $_SESSION['student_id']))->result_array();
        //$number   =  $query[0]['mob_num'];
        $number   = "03145345534";
        
        
        $rand     =  rand(10000, 99999);
        $message  =  "Mobile verification code: ".$rand;
        $response =  send_sms_code($number, 'Indici-Edu', $message, $_SESSION['student_id']);
        
        $obj      =  json_decode($response, true);
        
        if($obj['MsgStatus'] == 0){
            $_SESSION['mobile_code'] = $rand;
            $this->session->set_flashdata('flash_message', get_phrase('OTP SENT SUCCESSFULLY'));
            
    		$page_data['page_name']  = 'update_password';
    		$page_data['page_title'] = get_phrase('update_password');
    		$this->load->view('backend/index', $page_data);
        }
        else{
            $_SESSION['mobile_code'] = 0;
            $this->session->set_flashdata('club_message', get_phrase('OTP CANNOT BE SENT'));
    		$page_data['page_name']   = 'update_password';
    		$page_data['page_title']  = get_phrase('update_password');
    		$this->load->view('backend/index', $page_data);
        }
	}
	/***ADMIN DASHBOARD***/
	function dashboard($std_id = "")
	{
		  $qq = "select sr.*, clas.name as class_name, clas.class_id, dep.title as department_name,dep.departments_id as department_id,  c.title as section_name, sp.*,s.*
				FROM ".get_school_db().".student_parent sp
				INNER JOIN ".get_school_db().".student_relation sr ON sr.s_p_id = sp.s_p_id
				INNER JOIN ".get_school_db().".student s ON s.student_id=sr.student_id
				INNER JOIN ".get_school_db().".class_section c ON c.section_id=s.section_id
				INNER JOIN ".get_school_db().".class clas ON c.class_id=clas.class_id
				INNER JOIN ".get_school_db().".departments dep ON dep.departments_id=clas.departments_id 
				WHERE  sp.user_login_detail_id =".$_SESSION['login_detail_id']." AND s.student_status IN (".student_query_status().") AND sp.school_id=".$_SESSION['school_id']." ";

		if($std_id == "")
		{
			
			if($_SESSION['student_id'] != "")
			{
				$qq .= " AND s.student_id=".$_SESSION['student_id']."";
			}
		}
		else
		{
			$qq .= " AND s.student_id=$std_id";
		}

		$qq .= " limit 1";
		$qr = $this->db->query($qq);
		if($qr->num_rows() > 0)
		{
			foreach($qr->result() as $qrr)
			{
				$_SESSION['student_name']    =  $qrr->name;
				$_SESSION['student_image']   =  $qrr->image;
				$_SESSION['student_id']      =  $qrr->student_id;
				$_SESSION['section_id']      =  $qrr->section_id;
				$_SESSION['section_name']    =  $qrr->section_name;
				$_SESSION['class_id']        =  $qrr->class_id;
				$_SESSION['class_name']      =  $qrr->class_name;
				$_SESSION['department_id']   =  $qrr->department_id;
				$_SESSION['department_name'] =  $qrr->department_name;

				$yearly_term_arr = $this->db->query("select yt.* FROM  ".get_school_db().".acadmic_year ay INNER JOIN ".get_school_db().".yearly_terms yt ON ay.academic_year_id=yt.academic_year_id
					                                 WHERE  ay.school_id = ".$_SESSION['school_id']." AND yt.status = 2 LIMIT 1 ")->result_array();

				$_SESSION['academic_year_id'] = $yearly_term_arr[0]['academic_year_id'];
				$_SESSION['yearly_term_id']   = $yearly_term_arr[0]['yearly_terms_id'];
			}
		}
		else
		{
			$_SESSION['student_name']     = "";
			$_SESSION['student_image']    = "";
			$_SESSION['student_id']       = 0;
			$_SESSION['section_id']       = 0;
			$_SESSION['section_name']     = "";
			$_SESSION['class_id']         = 0;
			$_SESSION['class_name']       = "";
			$_SESSION['department_id']    = 0;
			$_SESSION['department_name']  = "";
			$_SESSION['academic_year_id'] = 0;
			$_SESSION['yearly_term_id']   = 0;
		}
		
		$assessment_query = "select ass.* ,ass_aud.* , s.name as teacher_name from ".get_school_db().".assessments ass
	    inner join ".get_school_db().".assessment_audience ass_aud on ass.assessment_id = ass_aud.assessment_id
	    inner join ".get_school_db().".staff s on ass.teacher_id = s.staff_id
	    WHERE ass_aud.student_id = ".$_SESSION['student_id']." and ass_aud.is_submitted = 0 and DATE_FORMAT(ass_aud.assessment_date, '%Y-%m-%d') = CURDATE() and ass.school_id = ".$_SESSION['school_id']."";
	    $assessments = $this->db->query($assessment_query)->result_array();
	    
	   // $attendance_qur = "SELECT COUNT(attendance_id) as attendance , status FROM ".get_school_db().".attendance where student_id = ".$_SESSION['student_id']." and school_id = ".$_SESSION['school_id']." and MONTH(date)=MONTH(now()) and YEAR(date)=YEAR(now()) GROUP by status ORDER by status ASC";
	   // $attendance_arr_count = $this->db->query($attendance_qur)->result_array();
	    
	   // $attendance_arr = array();
	   // foreach($attendance_arr_count as $row){
	   //     $attendance_arr[$row['status']] = $row['attendance'];
	   // }
	   
	    $attendance_qur = "SELECT IF(status = 1, count(status), 0) as present , IF(status = 2, count(status), 0) as absent ,
	        IF(status = 3, count(status), 0) as leaves FROM ".get_school_db().".attendance 
	        where student_id = ".$_SESSION['student_id']." and school_id = ".$_SESSION['school_id']." and MONTH(date)=MONTH(now()) and YEAR(date)=YEAR(now())";
	    $attendance_arr = $this->db->query($attendance_qur)->row();
	    
	    $page_data['attendance_arr']		=	$attendance_arr;
	    
	    $sub_arr = $this->db->query(" select count(sub.subject_id) as total_subjects from ".get_school_db().".subject sub
    	                          inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
    	                          where ss.section_id = ".$_SESSION['section_id']."
     	                          and ss.school_id = ".$_SESSION['school_id']." ")->row();
     	                          
     	$page_data['total_subjects']		=	$sub_arr->total_subjects;
	    
	    $page_data['assessments']		=	$assessments;
		$page_data['page_name']         =   'dashboard';
		$page_data['page_title']        =   get_phrase('parent_dashboard');
		$this->load->view('backend/index', $page_data);
		
	}
	
	/////Student login panel create
	function update_student_panel()
	{
		$page_data['control']        =  $this->uri->segment(3);
		$page_data['std_id']         =  $this->uri->segment(4);
		$page_data['section_id']     =  $this->uri->segment(5);
		$page_data['student_status'] =  $this->uri->segment(6);
		$page_data['page_name']      =  'update_student_create';
		$page_data['page_title']     =  get_phrase('update_student_create');

		$this->load->view('backend/index', $page_data);	
	}
	
	function subjects()
	{
	    $subject_array = $this->db->query(" select sub.* from ".get_school_db().".subject sub
    	                          inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
    	                          where ss.section_id = ".$_SESSION['section_id']."
     	                          and ss.school_id = ".$_SESSION['school_id']." ")->result_array();
     	                          
		$page_data['data']           =  $subject_array;
		$page_data['page_name']      =  'subjects';
		$page_data['page_title']     =  get_phrase('subjects');
		$this->load->view('backend/index', $page_data);	
	}
	
	////student login panel update and create
	function  manage_student_login($studentId = '', $sectionId = '')
    {
            
            $login_type_id    = 6;  
            $pargm            = $studentId.'/'.$sectionId; 
            $data['name']     = $this->input->post('f_p_name');
            $data['password'] = $this->input->post('password');
            $data['id_no']    = $this->input->post('f_cnic');
            $parent_id        = $this->input->post('parent_id');
            $student_id       = $this->input->post('student_id');
            $data['status']   = 1;
            $parent_arr       = $this->db->query("select id_no from ".get_system_db().".user_login where id_no = '".$this->input->post('f_cnic')."' ")->result_array();
                 
            if (count($parent_arr) == 0)
            {
                $this->db->insert(get_system_db().'.user_login', $data);

                $last_id = intval($this->db->insert_id());
                $update['system_id'] = get_system_id($last_id,$_SESSION['school_id'],'student');
                $this->db->update(get_system_db().'.user_login', $update, array("user_login_id" => $last_id));

                $student_update['parent_id'] = $parent_id;
                $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));

                $detail['user_login_id'] = $last_id;
                $detail['sys_sch_id']    = $_SESSION['sys_sch_id'];
                $detail['creation_date'] = date('Y-m-d h:i:s');
                $detail['created_by']    = $_SESSION['user_login_id'];
                $detail['status']        = 1;
                $detail['login_type']    = $login_type_id;

                $is_exists =  $this->db->query("select user_login_detail_id from ".get_system_db().".user_login_details  where user_login_id = $last_id
                    and sys_sch_id = ".$_SESSION['sys_sch_id']." and login_type = $login_type_id ")->result_array();

                if (count($is_exists) == 0)
                {
                    $this->db->insert(get_system_db().'.user_login_details', $detail);
                    $parent_data['user_login_detail_id'] = $this->db->insert_id();
                    $this->db->update(get_school_db().'.student_parent', $parent_data,
                        array( "s_p_id" => $parent_id)
                    );
                    $this->session->set_flashdata('club_updated', get_phrase('student_login_created_successfully'));
                }
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                $_SESSION['std_create'] =1;
                redirect(base_url().'parents/update_student_panel/'.$pargm);
            }
            elseif(count($parent_arr) == 1)
            {
                $password = $this->input->post('password');
                $id_no    = $this->input->post('f_cnic');
                if( isset($password) && $password != '')
                {
                    $data['password'] = $password;
                }
    
                $this->db->update(get_system_db().'.user_login', $data, array("id_no" => $id_no));
                $this->session->set_flashdata('club_updated', get_phrase('student_login_updated_successfully'));
                $_SESSION['std_update'] = 1;
                redirect(base_url().'parents/update_student_panel/'.$pargm);
            }
            else
            {
                $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
            }
            redirect(base_url().'parents/update_student_panel/'.$pargm);
                 

            if ($action == 'create_new' )
            {
               
            }
            elseif ($action == 'link_account')
            {
                $student_id = intval($this->uri->segment(4));
                $parent_id  = intval($this->uri->segment(5));
                $student_update['parent_id'] = $parent_id;

                $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                $this->session->set_flashdata('club_updated', get_phrase('teacher_login_account_linked_successfully'));
                redirect(base_url().'parents/update_student_panel/'.$pargm);
            }
            elseif ($action == 'create_link_account')
            {
                $student_id            =  intval($this->uri->segment(4));
                $parent_id             =  intval($this->uri->segment(5));

                $data['user_login_id'] =  intval($this->uri->segment(6));
                $data['sys_sch_id']    =  $_SESSION['sys_sch_id'];
                $data['creation_date'] =  date('Y-m-d h:i:s');
                $data['created_by']    =  $_SESSION['user_login_id'];
                $data['status']        =  1;
                $data['login_type']    =  $login_type_id;

                $is_exists =  $this->db->query("select user_login_detail_id from ".get_system_db().".user_login_details where
                                                user_login_id = ".$data['user_login_id']." and sys_sch_id = ".$_SESSION['sys_sch_id']." and login_type = $login_type_id ")->result_array();

                if (count($is_exists) == 0)
                {

                    $this->db->insert(get_system_db().'.user_login_details', $data);

                    $parent_data['user_login_detail_id'] = $this->db->insert_id();
                    $this->db->update(get_school_db().'.student_parent', $parent_data,
                        array( "s_p_id" => $parent_id)
                    );

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
                redirect(base_url().'parents/update_student_panel/'.$pargm);
            }
            elseif ($action == 'update')
            {
                
                $user_login_id =  $this->input->post('user_login_id');
                $data['name']  =  $this->input->post('display_name');
                $password      =  $this->input->post('password');
                
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

                $this->session->set_flashdata('club_updated', get_phrase('student_login_updated_successfully'));
                redirect(base_url().'parents/update_student_panel/'.$pargm);
            }
           
        }
	//////////////////////////////////////////////////////////////////////
	function updated_vc(){
	    $q      = 'select * from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'.class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id WHERE vc_end_time IS NULL';
        $result = $this->db->query($q)->result_array();
        $arr    = array();
        foreach($result as $r){
            $data                       = array();
            $data['class_routine_id']   = $r['class_routine_id'];
            $data['section_id']         = $r['section_id'];
            $data['subject_teacher_id'] = $r['subject_teacher_id'];
            $arr[]                      = $data;
        }
	    echo json_encode($arr);
	}
	
	function join_virtual_class($param1 = '', $param2 = ''){
	    
        $year        =  date("Y");
        $month       =  date("m");
        $day         =  date("j");
        $parentId    =  $_SESSION['user_login_id'];
        $studentName =  $_SESSION['student_name'];
        $studentId   =  $_SESSION['student_id'];
        
        $q = 'select virtual_class_id,virtual_class_name from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'.class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id WHERE class_routine_settings.section_id = '.$param2.' And class_routine.class_routine_id = '.$param1
        .' AND ((DATE_FORMAT(virtual_class.vc_start_time, "%Y") = '. $year .') AND (DATE_FORMAT(virtual_class.vc_start_time, "%c") = '. $month .' ) AND (DATE_FORMAT(virtual_class.vc_start_time, "%e") = '. $day .'))';
        $result = $this->db->query($q)->result_array();

       if(count($result)>0){
            foreach($result as $r){
               $meetingId   = $r['virtual_class_id'];
               $meetingName = $r['virtual_class_name'];
            }
            $getUrl = WEBRTC_LINK."api/getMeetingInfo?";
    		$params = '&meetingID='.urlencode($meetingId).
    		'&password='.urlencode('mp');
            $url_get = $getUrl.$params.'&checksum='.sha1("getMeetingInfo".$params.WEBRTC_SECRET);
            $xmldata = simplexml_load_file($url_get) or die("Failed to load");
            $returncode = $xmldata->returncode;
            //echo $returncode;exit;
            if($returncode == 'SUCCESS'){
                $maxUsers = $xmldata->maxUsers;
                $participantCount = $xmldata->participantCount;
                if($maxUsers == ($participantCount+1)){
                    $this->session->set_flashdata('club_updated', 'The number of participants allowed for this class has been reached.');
                    redirect($_SERVER['HTTP_REFERER']);
                }
                else{
                    
                    $t                               = time();
                    $current_time                    = date("Y-m-d H:i:s",$t);
                    
                    $data['class_routine_id']        = $param1;
                    $data['virtual_class_name']      = $meetingName;
                    $data['virtual_class_id']        = $meetingId;
                    $data['student_id']              = $studentId;
                    $data['parent_id']               = $parentId;
                    $data['student_name']            = $studentName;
                    $data['vc_start_time']           = $current_time;
                    
                	$joinUrl = WEBRTC_LINK."api/join?";
            		$params = '&meetingID='.urlencode($meetingId).
            		'&fullName='.urlencode($studentName).
            		'&password='.urlencode('pw').
            		'&userID='.urlencode($studentId).
            		'&joinViaHtml5=true'.
            		'&webVoiceConf='.urlencode('');
                    $url_join = $joinUrl.$params.'&checksum='.sha1("join".$params.WEBRTC_SECRET);
                    
                    $data['virtual_class_join'] = $url_join;
                    $this->db->insert(get_school_db().'.virtual_class_student', $data);
                    redirect($url_join);
                }
            }
            else{
                if($xmldata->messageKey == 'notFound'){
                    $this->session->set_flashdata('club_updated', 'Class you are trying to join is ended.');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
       }
       else{
            $this->session->set_flashdata('club_updated', 'Class you are trying to join is not created yet.');
            redirect($_SERVER['HTTP_REFERER']);
       }
        
    }

	/****MANAGE TEACHERS*****/
	function teacher_list($param1 = '', $param2 = '', $param3 = '')
	{
		
		if($param1 == 'personal_profile')
		{
			$page_data['personal_profile']   = true;
			$page_data['current_teacher_id'] = $param2;
		}
		$this->db->where('school_id',$_SESSION['school_id']);
		$sub_id         = intval($this->input->post('subject_id'));
		$subject_filter = '';
		if( $sub_id >0 )
		{
			$subject_filter = " AND st.subject_id = $sub_id ";
			$page_data['sub_filter'] = $sub_id;
			$page_data['filter']     = true;
		}

		$student_id = $_SESSION['student_id'];
		$school_id  = $_SESSION['school_id'];
		/*$teachers   =   $this->db->query("select  staff.staff_id as teacher_id,staff.name, staff.staff_image as teacher_image,s.student_id,s.school_id, st.name as subject_name ,st.subject_id, st.code as subject_code
		    from ".get_school_db().".student s 

		    inner join ".get_school_db().".subject_section sub_s on s.section_id=sub_s.section_id 

		    inner join ".get_school_db().".subject st on st.subject_id=sub_s.subject_id 

		    inner join ".get_school_db().".subject_teacher sub_teacher on sub_teacher.subject_id=st.subject_id

		    inner join ".get_school_db().".staff on 
		        staff.staff_id = sub_teacher.teacher_id

		    inner join ".get_school_db().".designation D 
		        ON (staff.designation_id = D.designation_id AND D.is_teacher=1)    
		 
		    where s.student_id=$student_id AND s.school_id=$school_id $subject_filter")->result_array();*/

		//echo $this->db->last_query();
		$page_data['page_name'] = 'teacher';
		//$page_data['teachers'] = $teachers;
		$page_data['page_title'] = get_phrase('teacher_list');
		$this->load->view('backend/index', $page_data);
	}
	
		function parent_chat_list($param1 = '', $param2 = '', $param3 = '')
	{
	   
		if($param1 == 'personal_profile')
		{
			$page_data['personal_profile']   = true;
			$page_data['current_teacher_id'] = $param2;
		}
		$this->db->where('school_id',$_SESSION['school_id']);
		$sub_id         = intval($this->input->post('subject_id'));
		$subject_filter = '';
		if( $sub_id >0 )
		{
			$subject_filter = " AND st.subject_id = $sub_id ";
			$page_data['sub_filter'] = $sub_id;
			$page_data['filter']     = true;
		}
		$student_id = $_SESSION['student_id'];
		$school_id  = $_SESSION['school_id'];
		 $parent_id = $_SESSION['login_detail_id'];
		/*$teachers   =   $this->db->query("select  staff.staff_id as teacher_id,staff.name, staff.staff_image as teacher_image,s.student_id,s.school_id, st.name as subject_name ,st.subject_id, st.code as subject_code
		    from ".get_school_db().".student s 
		    inner join ".get_school_db().".subject_section sub_s on s.section_id=sub_s.section_id 
		    inner join ".get_school_db().".subject st on st.subject_id=sub_s.subject_id 
		    inner join ".get_school_db().".subject_teacher sub_teacher on sub_teacher.subject_id=st.subject_id
		    inner join ".get_school_db().".staff on 
		    staff.staff_id = sub_teacher.teacher_id
		    inner join ".get_school_db().".designation D 
		    ON (staff.designation_id = D.designation_id AND D.is_teacher=1)    
		    where s.student_id=$student_id AND s.school_id=$school_id $subject_filter")->result_array();*/
		    
		    /////*******//////////
		    $staff = $this->db->query("select  staff.user_login_detail_id  from indicied_indiciedu_production.class_routine cr inner join indicied_indiciedu_production.class_routine_settings crs on crs.c_rout_sett_id=cr.c_rout_sett_id and crs.section_id = 3 inner join indicied_indiciedu_production.time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id inner join indicied_indiciedu_production.subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join indicied_indiciedu_production.staff on staff.staff_id = st.teacher_id
		    where staff.school_id = 214 and staff.user_login_detail_id > '0' GROUP BY staff.name")->result_array();
		    
	       
		   // foreach($staff as $key=>$chat_id){
		        //$data = $key+1;
		       // print_r($chat_id);
		         
		      //  for($i=0; $i < $data.length ;$i++){
		            
		      //        $data = $i['user_login_detail_id'];
		      //    }
		  //  }
		     /////*******//////////
		   
	    //echo $this->db->last_query();
		$page_data['page_name'] = 'parent_chat';
		//$page_data['teachers'] = $teachers;
		$page_data['page_title'] = get_phrase('teacher_list');
		$this->load->view('backend/index', $page_data);
	}
	
	function diary($param1 = '', $param2 = '')
	{
		$section_id   =  $_SESSION['section_id'];
		$student_id   =  $_SESSION['student_id'];
		$sub_id       =  intval($this->input->post('subject_id'));
		$start_date   =  $this->input->post('starting');
		$end_date     =  $this->input->post('ending');
		$start_date   =  date_slash($this->input->post('starting'));
		$end_date     =  date_slash($this->input->post('ending'));
		$per_page     =  10;
		$apply_filter =  $this->input->post('apply_filter', TRUE);
		$std_search   =  $this->input->post('std_search', TRUE);
		$std_search   =  trim(str_replace(array("'", "\""), "", $std_search));

        if (!isset($sub_id) || $sub_id == "") {
                    $sub_id = $this->uri->segment(3);
                }
        
                if (!isset($sub_id) || $sub_id == "") {
                    $sub_id = 0;
                }  
                
        if (!isset($start_date) || $start_date == "") {
                    $start_date = $this->uri->segment(4);
                }
        
                if (!isset($start_date) || $start_date == "") {
                    $start_date = 0;
                }    
                
        if (!isset($end_date) || $end_date == "") {
                    $end_date = $this->uri->segment(5);
                }
        
                if (!isset($end_date) || $end_date == "") {
                    $end_date = 0;
                }              
        		
        if (!isset($std_search) || $std_search == "") {
                    $std_search = $this->uri->segment(6);
                }
        
                if (!isset($std_search) || $std_search == "") {
                    $std_search = 0;
                }  
                
        $page_num = $this->uri->segment(7);
        if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }         
		
		$subject_filter = '';
		if( $sub_id >0 )
		{
			$subject_filter = " AND dr.subject_id = $sub_id ";
			$page_data['sub_filter'] = $sub_id;
			$page_data['filter'] = true;
		}

        if(($start_date!='') && ($start_date > 0))
        {
        	$date_query=" AND assign_date >= '".$start_date."'";
        	$page_data['start_date']=$start_date;
        	$page_data['filter'] = true;
        }
        if(($end_date!='') && ($end_date > 0))
        {
        	$date_query=" AND assign_date <= '".$end_date."'";
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        }
        if(($start_date!='') && ($start_date > 0) && ($end_date!='') && ($end_date > 0))
        {
        	$date_query=" AND assign_date >= '".$start_date."' AND assign_date <= '".$end_date."' ";
        	$page_data['start_date']=$start_date;
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        }
        
        $std_query = "";
        if (isset($std_search) && !empty($std_search))
        {
        	$std_query = " AND (
                                    dr.task LIKE '%" . $std_search . "%' OR 
                                    dr.title LIKE '%" . $std_search . "%' 
                                )";
            $page_data['filter'] = true;
        }

		$q = "select dr.diary_id as diary_id,dr.teacher_id as teacher_id,dr.subject_id as subject_id,dr.section_id as section_id,dr.assign_date as assign_date,dr.due_date as due_date,dr.task as task,dr.title as title,dr.attachment as attachment,ds.student_id, dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
			FROM ".get_school_db().".diary dr
			INNER JOIN ".get_school_db().".diary_student ds ON ds.diary_id = dr.diary_id
			INNER JOIN ".get_school_db().".class_section cs ON cs.section_id = dr.section_id
            INNER JOIN ".get_school_db().".class c ON c.class_id = cs.class_id
            INNER JOIN ".get_school_db().".departments d ON d.departments_id = c.departments_id WHERE 
			((dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$_SESSION['school_id'].")". $subject_filter . $date_query . $std_query. "ORDER BY dr.assign_date desc ";
        $diary_count = $this->db->query($q)->result_array();
        // $total_records = count($diary_count);
        // $quer_limit = $q . " limit " . $start_limit . ", " . $per_page . "";
        // $diary = $this->db->query($quer_limit)->result_array();
		$page_data['diary'] =$diary_count;
		$config['base_url'] = base_url() . "parents/diary/" . $sub_id . "/". $start_date . "/". $end_date . "/".$std_search;
		//$page_data['diary'] = $this->db->query($q)->result_array();

		/*
		$student_id = $_SESSION['student_id'];
		$where      = "((section_id=$section_id) AND (student_id=0 OR student_id=$student_id OR student_id='') AND (school_id=".$_SESSION['school_id']."))";
		$this->db->where($where);
		$page_data['diary'] = $this->db->get(get_school_db().'.diary')->result_array();
		*/
		
		$subject_array = $this->db->query(" select sub.* from ".get_school_db().".subject sub
    	                          inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
    	                          where ss.section_id = ".$_SESSION['section_id']."
     	                          and ss.school_id = ".$_SESSION['school_id']." ")->result_array();
     	$page_data['subject_array'] = $subject_array; 
		
		$config['total_rows']       = $total_records;
        $config['per_page']         = $per_page;
        $config['uri_segment']      = 7;
        $config['num_links']        = 2;
        $config['use_page_numbers'] = TRUE;

        $this->pagination->initialize($config);

        $pagination                 =  $this->pagination->create_links();
        $page_data['start_limit']   =  $start_limit;
        $page_data['apply_filter']  =  $apply_filter;
        $page_data['total_records'] =  $total_records;
        $page_data['pagination']    =  $pagination;
        $page_data['start_date']    =  $start_date;
        $page_data['end_date']      =  $end_date;
        $page_data['std_search']    =  $std_search;
		$page_data['page_name']     =  'diary';
		$page_data['page_title']    =  get_phrase('daily_diary');
		$this->load->view('backend/index', $page_data);
	}
	/****MANAGE EXAM MARKS*****/
	function marks($exam_id = '', $class_id = '', $subject_id = '')
	{
		$student_id              = $_SESSION['student_id'];
		$section_id              = $_SESSION['section_id'];
		$page_data['student_id'] = $student_id;
		$page_data['section_id'] = $section_id;

		if($this->input->post('operation') == 'selection')
		{
			$page_data['exam_id'] = $this->input->post('exam_id');
			if($page_data['exam_id'] > 0)
			{
				redirect(base_url() . 'parents/marks/' . $page_data['exam_id']);
			}
			else
			{
				$this->session->set_flashdata('mark_message', get_phrase('choose_ exam_,_class_and_subject'));
				redirect(base_url() . 'parents/marks/');
			}
		}
		
		$page_data['exam_id']    = $exam_id;
		$page_data['apply_filter']    = $apply_filter;
		
		$page_data['page_info']  = 'Exam marks';
		$page_data['page_name']  = 'marks';
		$page_data['page_title'] = get_phrase('examination_result');
		
		$this->load->view('backend/index', $page_data);
	}

	function get_exam_result()
	{
		$student_id = $this->input->post('student_id');	
		$exam_id    = $this->input->post('exam_id');
		
		$q="select m.*,e.start_date, e.end_date, e.name as exam_name from ".get_school_db().".marks m 
			inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id 
			inner join ".get_school_db().".exam e on m.exam_id=e.exam_id 
			inner join ".get_school_db().".exam_routine er on e.exam_id=er.exam_id
			where  m.exam_id=".$exam_id." and m.student_id=".$student_id." and m.school_id=".$_SESSION['school_id']." and er.is_approved=1 group by m.subject_id";
		$result = $this->db->query($q)->result_array();
		
		$data['student_id'] = $student_id;
		$data['exam_id']    = $exam_id;
		$data['result']     = $result;
		
		$this->load->view('backend/parent/ajax/result', $data);
	}

	function get_exam_type(){
		echo exam_type_option_list($this->input->post('yearly_term'));
	}
	
	function check_leave_dates()
	{
		$date = $this->input->post('date');
		if ($date != '')
		{
			$date = date('Y-m-d', strtotime($date));
			$q = $this->db->query("SELECT request_id FROM ".get_school_db().".leave_student WHERE ('".$date."' BETWEEN start_date AND end_date)
				                   AND student_id=".$_SESSION['student_id']." AND school_id=".$_SESSION['school_id']." AND (status=0 OR status=1 )")->result_array();
			if ( count($q) > 0 )
				echo 'failure';
			else
				echo 'success';
		}
		else
		{
			echo 'failure';		
		}
	}

	/**********MANAGING CLASS ROUTINE******************/
	function class_routine($param1 = '', $param2 = '', $param3 = '')
	{
		$page_data['student_id'] = $student_id;
		$page_data['section_id'] = $_SESSION['section_id'];
		$q = "SELECT crs.* FROM ".get_school_db().".class_routine_settings crs WHERE 
		      crs.school_id=".$_SESSION['school_id']." and crs.is_active = 1";
	
		$routine                 =  $this->db->query($q)->result_array();
		$page_data['routine']    =  $routine;
		$page_data['page_name']  =  'class_routine';
		$page_data['page_title'] =  get_phrase('class_routine');
		
		$this->load->view('backend/index', $page_data);
	}

	/**********MANAGING EXAM ROUTINE******************/
	function exam_routine($param1 = '', $param2 = '', $param3 = '')
	{
		
		$student_id                =  $_SESSION['student_id'];
		$section_id                =  $_SESSION['section_id'];
		$page_data['student_id']   =  $student_id;
		$page_data['section_id']   =  $section_id;
		$page_data['page_name']    =  'exam_routine';
		$page_data['page_title']   =  get_phrase('examination_routine');
		$this->load->view('backend/index', $page_data);
	}

	
	/**********MANAGE Leaves********************/
	function manage_leaves($param1 = '', $param2 = '')
	{
	   $this->load->model('Crud_model');
		
		$std_id = $_SESSION['student_id'];
		if($param1 == 'create')
		{
		    
			$proof                     =  $_FILES['userfile']['name'];
		    if($proof!="")
        	{
        	    $proof_doc                 =  $std_id."-".$proof;
        	}else{
        	    $proof_doc = "";
        	}
			$data['student_id']        =  $std_id;
			$data['school_id']         =  $_SESSION['school_id'];
			$data['leave_category_id'] =  $this->input->post('leave_id');
			$data['request_date']      =  date('Y-m-d');
			$data['start_date']        =  date('Y-m-d',strtotime($this->input->post('start_date')));
			$data['end_date']          =  date('Y-m-d',strtotime($this->input->post('end_date')));
			$data['reason']            =  $this->input->post('reason');
			$data['proof_doc']         =  $proof_doc;
			$data['status']            =  0;
			
			if($proof!="")
        	{
				$data['proof_doc']=file_upload_fun('userfile','leaves_student','');
			}

			$this->db->insert(get_school_db().'.leave_student', $data);

			$school_admins = get_school_admins();
            foreach($school_admins as $admin){
                $device_id  =   get_user_device_id(1 , $admin['user_login_detail_id'] , $_SESSION['school_id']);
                $title      =   "New Leave Request";
                $message    =   "A Leave Request Has been Submitted By Parent.";
                $link       =    base_url()."leave/manage_leaves_student";
                sendNotificationByUserId($device_id, $title, $message, $link , $admin['user_login_detail_id'] , 1);
            }

			redirect(base_url() . 'parents/manage_leaves');
		}
		$school_id = $_SESSION['school_id'];
		
		$page_data['leaves']     = $this->db->query("select sl.* from  ".get_school_db().".leave_student sl where sl.student_id=$std_id and sl.school_id= $school_id order by sl.request_id desc ")->result_array();
		$page_data['page_name']  = 'leave_request';
		$page_data['page_title'] = get_phrase('leave_requests');
		$this->load->view('backend/index', $page_data);

	}
	/**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
	function transport($param1 = '', $param2 = '', $param3 = '')
	{
		
		$page_data['transports'] = $this->db->get(get_school_db().'.transport')->result_array();
		$page_data['page_name']  = 'transport';
		$page_data['page_title'] = get_phrase('manage_transport');
		$this->load->view('backend/index', $page_data);

	}
	

	/**********WATCH NOTICEBOARD AND EVENT ********************/
	function noticeboard($param1 = '', $param2 = '', $param3 = '')
	{
		$school_id   = $_SESSION['school_id'];
		$start_date  = $this->input->post('starting');
        $end_date    = $this->input->post('ending');
        if($start_date!='')
        {
        	$start_date_arr = explode("/",$start_date);
        	$start_date     = $start_date_arr[2].'-'.$start_date_arr[1].'-'.		$start_date_arr[0];
        }
        if($end_date!='')
        {
        	$end_date_arr   = explode("/",$end_date);
        	$end_date       = $end_date_arr[2].'-'.$end_date_arr[1].'-'.$end_date_arr[0];
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
        if (!isset($std_search) || $std_search == "") {
                    $std_search = $this->uri->segment(5);
        }
        if (!isset($std_search) || $std_search == "") {
            $std_search = 0;
        } 
        $page_num = $this->uri->segment(6);
        if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }          
        if(($start_date!='') && ($start_date > 0))
        {
        	$date_query=" AND create_timestamp >= '".$start_date."'";
        	$page_data['start_date']=$start_date;
        	$page_data['filter'] = true;
        }
        if(($end_date!='') && ($end_date>0))
        {
        	$date_query=" AND create_timestamp <= '".$end_date."'";
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        }
        if(($start_date!='') && ($start_date > 0) && ($end_date!='') && ($end_date > 0))
        {
        	$date_query              = " AND create_timestamp >= '".$start_date."' AND create_timestamp <= '".$end_date."' ";
        	$page_data['start_date'] = $start_date;
        	$page_data['end_date']   = $end_date;
        	$page_data['filter']     = true;
        }
        
        $std_query = "";
        if (isset($std_search) && !empty($std_search))
        {
        	$std_query = " AND (
                                    n.notice_title LIKE '%" . $std_search . "%' OR 
                                    n.notice LIKE '%" . $std_search . "%' 
                                )";
            $page_data['filter'] = true;
        }
		
		$q="select n.notice_id as notice_id,n.notice_title as notice_title,n.notice as notice,n.create_timestamp as create_timestamp
		    FROM ".get_school_db().".noticeboard n WHERE n.school_id=".$school_id. $date_query.$std_query." ";
		$notice_count = $this->db->query($q)->result_array();   	$total_records = count($notice_count);
        $quer_limit   = $q . " limit " . $start_limit . ", " . $per_page . "";
        $notice       = $this->db->query($quer_limit)->result_array();

		$page_data['notices'] = $notice;
		$config['base_url']   = base_url() . "parents/noticeboard/" . $start_date . "/". $end_date . "/". $std_search;
		$config['total_rows'] = $total_records;
        $config['per_page']   = $per_page;

        $config['uri_segment']      = 6;
        $config['num_links']        = 2;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        $pagination                 =  $this->pagination->create_links();
        $page_data['start_limit']   =  $start_limit;
        $page_data['apply_filter']  =  $apply_filter;
        $page_data['total_records'] =  $total_records;
        $page_data['pagination']    =  $pagination;
        $page_data['start_date']    =  $start_date;
        $page_data['end_date']      =  $end_date;
        $page_data['std_search']    =  $std_search;
		$page_data['page_name']     =  'noticeboard';
		$page_data['page_title']    =  get_phrase('noticeboard');
		$this->load->view('backend/index', $page_data);

	}
	/***MANAGE CIRCULARS, WILL BE SEEN BY Selected Corresponding DASHBOARDS**/
	function circulars($param1 = '', $param2 = '', $param3 = '')
	{
		$student_id   =  $_SESSION['student_id'];
		$section_id   =  $_SESSION['section_id'];
		$school_id    =  $_SESSION['school_id'];
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
		
		$q="select cl.circular_id as circular_id,cl.circular_title as circular_title,cl.circular as circular,cl.section_id as section_id,cl.student_id as student_id,cl.create_timestamp as create_timestamp,cl.attachment as attachment,d.title as department, c.name as class_name,cs.title as class_section
			FROM ".get_school_db().".circular cl
			INNER JOIN ".get_school_db().".class_section cs ON cs.section_id = cl.section_id
            INNER JOIN ".get_school_db().".class c ON c.class_id = cs.class_id
            INNER JOIN ".get_school_db().".departments d ON d.departments_id = c.departments_id WHERE 
			((cl.student_id='' OR cl.student_id=0 OR cl.student_id=$student_id ) AND  cl.section_id=$section_id) AND
		 	cl.school_id=".$_SESSION['school_id']. $date_query. " 
		 	ORDER BY  cl.create_timestamp desc ";

		$page_data['circulars']  = $this->db->query($q)->result_array();
		$page_data['page_name']  = 'circulars';
		$page_data['page_title'] = get_phrase('circulars');
		$this->load->view('backend/index', $page_data);
	}

	/**********MANAGE DOCUMENT / home work FOR A SPECIFIC CLASS or ALL*******************/
	function document($do = '', $document_id = '')
	{
		$page_data['page_name']  = 'manage_document';
		$page_data['page_title'] = get_phrase('manage_documents');
		$page_data['documents']  = $this->db->get(get_school_db().'.document')->result_array();
		$this->load->view('backend/index', $page_data);
	}

	function manage_attendance($date = '',$month = '',$year = '',$class_id = '')
	{
	    
		$page_data['date']       =  $date;
		$page_data['month']      =  $month;
		$page_data['year']       =  $year;
		$page_data['class_id']   =  $class_id;
		$page_data['page_name']  =  'manage_attendance';
		$page_data['page_title'] =  get_phrase('daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	
	function view_subjectwise_attendance($date = '',$month = '',$year = '',$class_id = '')
    {
        $student_id = $_SESSION['student_id'];
        // if(isset($_POST['month'])){
        //     $date_month=$_POST['month'];
        //     $date_month = date("m", strtotime($date_month));
        //     $date_year=$_POST['year'];
        // }else{
        //     $date_month = date('m');
        //     $date_year = date('Y');
        // }
        
        
        if(isset($_POST['submit']))
        {
            $date_submitted = explode('-',$this->input->post('month'));
            $year = intval($date_submitted[1]);
            $month= intval($date_submitted[0]);
            
            $date_month = intval($date_submitted[1]);
            $date_year=intval($date_submitted[0]);
        }else{
            $month=date('n');
            $year=date('Y');
        
            $date_month = date('m');
            $date_year = date('Y');
        }
        
        $page_data['date']       =  $date;
		$page_data['month']      =  $month;
		$page_data['year']       =  $year;
		$page_data['class_id']   =  $class_id;
		
        $page_data['stud_id']		    =	$student_id;
        $page_data['date_month']		=	$date_month;
        $page_data['date_year']		    =	$date_year;
		$page_data['page_name']		    =	'view_subjectwise_attendance';
		$page_data['page_title']		=	get_phrase('view_subjectwise_attendance');
		$this->load->view('backend/index', $page_data);
    }
	
	function summary()
	{
		$page_data['page_name'] = 'summary';
		$page_data['page_title'] = get_phrase('summary');
		$this->load->view('backend/index', $page_data);
	}

	function get_academic_year_dates($academic_year_id = 0)
	{
		$year_arr = $this->db->query("select start_date,end_date from ".get_school_db().".acadmic_year where academic_year_id = $academic_year_id ")->result_array();
		return $year_arr;
	}
	
	function update_chat_message()
	{
		$student_id  = $_SESSION['student_id'];
		$school_id   = $_SESSION['school_id'];
		$teachers_id = $this->input->post('teacher_id'); 
		$subject_id  = $this->input->post('subject_id');

        $page_data['page_name']  = 'messenger/messege_view';
		$page_data['page_title'] = get_phrase('message');

		$this->db->query("update ".get_school_db().".messages set is_viewed=1 WHERE teacher_id=$teachers_id and student_id=$student_id and subject_id=$subject_id and school_id=$school_id and messages_type=0");

		$qr = " select m.* from ".get_school_db().".messages m
			    inner join ".get_school_db().".student s on s.student_id=m.student_id
			    where m.teacher_id=$teachers_id and m.student_id=$student_id and m.subject_id=$subject_id and s.section_id=".$_SESSION['section_id']." 
			    and m.school_id=$school_id  and  s.academic_year_id = ".$_SESSION['academic_year_id']." ORDER BY m.message_time ASC ";
		$query = $this->db->query($qr);

		$page_data['parent_message_id']   = 0;
		$page_data['previous_message_id'] = 0;
		$c = 0;
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
			$page_data['rows_1'] = $data;

		}
		
		$page_data['subject_id']  = $subject_id ;
		$page_data['teachers_id'] = $teachers_id ;

		$this->load->view('backend/parent/messenger/ajax_message' , $page_data);
	}

	function message()
	{
		$student_id              =  $_SESSION['student_id'];
		$school_id               =  $_SESSION['school_id'];
		$teachers_id             =  $this->uri->segment(3);
		$subject_id              =  $this->uri->segment(4);
        $page_data['page_name']  =  'messenger/messege_view';
		$page_data['page_title'] =  get_phrase('message');

		$this->db->query("update ".get_school_db().".messages set is_viewed=1 WHERE teacher_id=$teachers_id and student_id=$student_id and subject_id=$subject_id and school_id=$school_id and messages_type=0");

		$qr    = "select m.* from ".get_school_db().".messages m inner join ".get_school_db().".student s on s.student_id=m.student_id
			       where m.teacher_id=$teachers_id and m.student_id=$student_id and m.subject_id=$subject_id and s.section_id=".$_SESSION['section_id']." 
			       and m.school_id=$school_id  and  s.academic_year_id = ".$_SESSION['academic_year_id']." ORDER BY m.message_time ASC";
		$query = $this->db->query($qr);
	//	echo $this->db->last_query();

		$page_data['parent_message_id']   = 0;
		$page_data['previous_message_id'] = 0;
		$c = 0;
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
			$page_data['rows_1'] = $data;

		}
		
		$page_data['subject_id']  = $subject_id ;
		$page_data['teachers_id'] = $teachers_id ;

		$this->load->view('backend/index', $page_data);
	}


	function message_send()
	{
		
		$teacher_id                  =  $this->input->post('teacher_id');
		$subject_id                  =  $this->input->post('subject_id');
		$student_name                =  $_SESSION['student_name'];
		$msg                         =  $data['messages'];
		$student_id                  =  $_SESSION['student_id'];
		
		$data['messages']            =  $this->input->post('message');
		$data['subject_id']          =  $this->input->post('subject_id');
		$data['is_viewed']           =  0;
		$data['previous_message_id'] =  $this->input->post('previous_message_id');
		$data['parent_message_id']   =  $this->input->post('parent_message_id');
		$data['teacher_id']          =  $this->input->post('teacher_id');
		$data['student_id']          =  $student_id;
		$data['school_id']           =  $_SESSION['school_id'];
		$data['messages_type']       =  1;//parent
		$data['sent_by']             =  intval($_SESSION['login_detail_id']);
		$data['message_time']        =  date('Y-m-d H:i:s');
		
		
		
		
		$this->db->insert(get_school_db().'.messages',$data);
		
		$device_id  =   get_user_device_id(3 , $teacher_id , $_SESSION['school_id']);
        $title      =   "New Message";
        $message    =   "A New Message Has been Sent By Parent.";
        $link       =    base_url()."teacher/student_list";
        sendNotificationByUserId($device_id, $title, $message, $link , $teacher_id , 3);
		
		$this->session->set_flashdata('club_updated', get_phrase('message_send_successfully '));
		redirect(base_url() . 'parents/message/'.$teacher_id.'/'.$subject_id);
	}


	function send_mail($to,$subject,$from,$message)
	{

		$this->load->library('email'); // load email library
		$this->email->from($from, 'New Message'); // sending email
		$this->email->to($to);      // sending email
		$this->email->reply_to("no replay");      // sending email
		$this->email->subject($subject); // sending email
		$this->email->message($message); // sending email

		$this->email->set_mailtype("html");
		if($this->email->send())
		{
			$currentdate;
		}
	}
	
	function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
    	if( get_login_type_name($_SESSION['login_type']) != 'parent')
			redirect(base_url() . 'login');
        
        if ($param1 == 'update_profile_info') {
            
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            
            $this->db->where('school_id',$_SESSION['school_id']);            
            $this->db->where('admin_id',$_SESSION['user_login_id']);
            $this->db->update(get_school_db().'.admin', $data);
            $this->session->set_flashdata('flash_message', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'parents/manage_profile/');
        }
        if ($param1 == 'change_password') {
            $data['password']             =  $this->input->post('password');
            $data['new_password']         =  $this->input->post('new_password');
            $data['confirm_new_password'] =  $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where(get_system_db().'.user_login', array('user_login_id' => $_SESSION['user_login_id']))->row()->password;
                
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('user_login_id',$_SESSION['user_login_id']);
                $this->db->update(get_system_db().'.user_login', array('password' => $data['new_password']));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated_successfully'));
            } 
            else{
               $this->session->set_flashdata('club_message', get_phrase('password_mismatch'));
            } 
            redirect(base_url().'parents/manage_profile/');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where(get_system_db().'.user_login', array('user_login_id' => $_SESSION['user_login_id']))->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
    function my_profile()
    {
    	$user_login_id  =  $_SESSION['user_login_id'];
    	$data['name']   =  $this->input->post('display_name');
    	$image_file     =  $this->input->post('image_file');
    	$user_file      =  $_FILES['userfile']['name'];
    	if($user_file!="")
    	{
         	if($image_file!="")
         	{
        		$del_location="uploads/profile_pic/$image_file";
                file_delete($del_location);
        	}
     	    $data['profile_pic']=file_upload_fun('userfile','profile_pic','profile',1);
       } 
       
     	$this->db->where('user_login_id', $user_login_id);
    	$this->db->update(get_system_db().'.user_login', $data);
    	$this->session->set_flashdata('flash_message', get_phrase('record_updated_successfully'));
        redirect(base_url() . 'parents/manage_profile/');   
    }

    function invoice()
    {
        
    	$year_arr = $this->get_academic_year_dates($_SESSION['academic_year_id']);
    

    	$page_data['invoices'] = $this->db->query( "select scf.* from ".get_school_db().".student_chalan_form scf
    			inner join ".get_school_db().".class_chalan_form ccf on ccf.c_c_f_id=scf.c_c_f_id and ccf.section_id =".$_SESSION['section_id']."
    			and ccf.type = 2 where scf.student_id= ".$_SESSION['student_id']." and scf.school_id=".$_SESSION['school_id']." and scf.status IN (4,5)
    			and ( DATE(scf.issue_date) between '".$year_arr[0]['start_date']."' and '".$year_arr[0]['end_date']."' ) ")->result_array();
	
    	$page_data['page_name']  = 'invoice';
    	$page_data['page_title'] = get_phrase('invoice_payment');
    	$this->load->view('backend/index', $page_data);
    }
    
    function policies_listing($action = "", $id = 0)
    {
    	$page_data['policy_filter'] = '';
    	if ($action == 'filter')
    	{
            $page_data['policy_filter'] = " policy_category_id = $id AND ";
    		$page_data['filter'] = true;
    	}
    	$page_data['page_name']  = 'policies_listing';
    	$page_data['page_title'] = get_phrase('policies');
    	$this->load->view('backend/index', $page_data);
    
    }
    
    function download_chalan_pdf($s_c_f_id,$typp=1 ){

        if($s_c_f_id=="" || $typp=="")
        {
            $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_created_yet'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            $school_id               = $_SESSION['school_id'];
            $page_data["query_ary"]  = $this->db->query("select * from ".get_school_db().".student_chalan_form where s_c_f_id = $s_c_f_id and school_id = $school_id and is_cancelled = 0 and status>3")->result_array();
            $page_data['page_name']  = 'download_chalan_pdf';
            $page_data['page_type']  = 'single';
            $page_data['page_title'] =  get_phrase('chalan_form');

            $this->load->library('pdf');
            $view = 'backend/parent/download_chalan_pdf';
            
            // $this->load->view('backend/index', $page_data);
            $this->pdf->load_view($view,$page_data);
            $this->pdf->set_paper("A4", "landscape");
            $this->pdf->render();
            $this->pdf->stream($page_data['page_title'].".pdf");
        }
        
    }
    
    function invoice_cart_proceed()
    {
        
        $s_c_f_ids         = $this->input->post("selectedChallanIds");
        $payment_method_id = $this->input->post("payment_method_id");
        
        if($s_c_f_ids == ""){
            $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_created_yet'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            
            $data    =  array("ClientID"=>"GoSW2vUrdfQL5W5", "ClientSecret"=>"XH6VeInJEKx6td0");
            $resp    =  $this->blinqapi->getToken("api/auth", $data);
            $token_0 =  $resp[0];
            $token_1 =  $resp[1];
         
 
            $school_id = $_SESSION['school_id'];
            $query_ary = $this->db->query("select *  from ".get_school_db().".student_chalan_form 
            where s_c_f_id IN($s_c_f_ids) and school_id = $school_id and is_cancelled = 0 and status > 3")->result_array();
            
            $challan_ids_array = array();
            $total_amount      = 0;
            foreach($query_ary as $row_data){
               $total_amt = get_student_challan_fee_details_for_parent($row_data['student_id'],$row_data['s_c_f_month'], $row_data['s_c_f_year'], $row_data['s_c_f_id']);
               $total_amount += $total_amt; 
               array_push($challan_ids_array , $row_data['s_c_f_id']);
            }
            
            $challans_id = implode("," , $challan_ids_array);
            
            $student_row                        =  get_student_info($query_ary[0]['student_id']);
            $student_name                       =  $student_row[0]['student_name']; 
            $student_number                     =  $student_row[0]['mob_num'];
            $invoice                            =  $this->createInvoice($query_ary[0]['student_id'] , $challans_id , $student_name , $student_number , $total_amount , $payment_method_id);
            
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

        	$this->load->view('backend/index', $page_data);
        	
        	
        }
    }
    
    
    public function paymentcallback(){
        //Array ( [message] => Payment has been received [ordId] => 1619075073 [paymentCode] => 00162111200009 [refNumber] => 756 [status] => success )
        
        if($_POST) {
            
            $this->db->trans_begin();
            
            $status  = $_POST['status'];
            $status  = trim($status);
            $orderId = $_POST['ordId'];
            $pBank   = trim($_POST['pBank']);
            
            
            if($status == 'success')
            {
                
                $msg = $_POST['message'];
                
                $data_consumer = array(
                    'IsPaid' => 1,
                    'Updated_at' => date('Y-m-d H:i:s')
                );
                
                $this->db->where('InvoiceNumber', $orderId);
                $this->db->where('school_id', $_SESSION['school_id']);
                $this->db->update(get_school_db().'.payment_consumer', $data_consumer);
                
                $invoice_row = $this->db->get_where(get_school_db().'.payment_consumer', array('InvoiceNumber' => $orderId))->row();
                
                if($invoice_row != null)
                {
                    $student_id         =   $invoice_row->consumer_id;
                    $fee_school_id = $_SESSION['school_id'];
                    $receieved_amount_in_cash = $invoice_row->InvoiceAmount;
                    $challans_ids  =   $invoice_row->challan_id;
                    $challans      =   explode("," , $challans_ids);
                    $s_c_f_id      =   $challans;
                    if(!empty($challans))
                    {
                        
                        for($i=0; $i<count($challans); $i++)
                        {
                            
                            $entry_date = date("Y-m-d");

                            $fee_amount = 0;
                            $total_discount_amount = 0;
                
                            $str_fee = "SELECT scf_fee.student_id,scf_fee.chalan_form_number, scf_fee.chalan_form_number, scfd_fee.* FROM ".get_school_db().".student_chalan_detail as scfd_fee 
                                        INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                        WHERE scfd_fee.s_c_f_id = $challans[$i] AND scfd_fee.type = 1 AND scfd_fee.school_id = ".$_SESSION['school_id']."";
                            $query_fee = $this->db->query($str_fee)->result_array();
                            
                            $sum_total_amount = "SELECT SUM(scfd_fee.amount) AS total_amount FROM ".get_school_db().".student_chalan_detail as scfd_fee 
                                                INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                                WHERE scfd_fee.s_c_f_id = $challans[$i] AND scfd_fee.type = 1 AND scfd_fee.school_id = ".$_SESSION['school_id']."";
                            $query_sum_total_amount = $this->db->query($sum_total_amount)->row();
                            $grand_total_amount = $query_sum_total_amount->total_amount;
                            
                            if(count($query_fee)>0)
                            {
                                foreach ($query_fee as $key_fee => $value_fee)
                                {
                                    /* create array for add fee only start */
                                    $fee_type_id_fee = $value_fee['type_id'];
                                    $fee_type_title = $value_fee['fee_type_title'];
                                    $fee_amount = $value_fee['amount'];
                                    $fee_school_id = $value_fee['school_id'];
                                    $fee_receive_dr_coa_id = $value_fee['receive_dr_coa_id'];
                                    $fee_receive_cr_coa_id = $value_fee['receive_cr_coa_id'];
                                    $fee_chalan_form_number = $value_fee['chalan_form_number'];
                                    $transaction_detail = student_name_section($value_fee['student_id']);
                
                                    /* latest change in issues 10-03-2018 start */
                                    $s_c_d_id = $value_fee['s_c_d_id'];
                                    $discount_amt = $this->is_discount_fee($s_c_d_id,$challans[$i]);
                
                                    $fee_amount_temp = $fee_amount;
                
                                    if($discount_amt>0)
                                    {
                                        $discount_amt = round((($discount_amt * $fee_amount) / 100));
                                        $fee_amount_temp = $fee_amount_temp-$discount_amt;
                                    }
                
                                    /* latest change in issues 10-03-2018 end */
                                    
                                    
                                    //   Discount Journal Entry
                                    $str_discount = "SELECT scf_discount.student_id,
                                                    scf_discount.chalan_form_number,
                                                    scfd_discount.amount,
                                                    scfd_discount.fee_type_title, 
                                                    scfd_discount.type_id as scfd_fee_id,
                                                    scfd_discount.issue_cr_coa_id,
                                                    scfd_discount.receive_dr_coa_id,
                                                    scfd_discount.issue_dr_coa_id,
                                                    scfd_discount.school_id,
                                                    d.discount_id,
                                                    f.fee_type_id as fee_id, 
                                                    d.title
                                                    FROM " . get_school_db() . ".discount_list as d
                                                    INNER join  " . get_school_db() . ".fee_types as f ON f.fee_type_id = d.fee_type_id
                                                    INNER JOIN " . get_school_db() . ".student_chalan_detail as scfd_discount ON scfd_discount.type_id = d.discount_id
                                                    INNER JOIN " . get_school_db() . ".student_chalan_form as scf_discount ON scf_discount.s_c_f_id = scfd_discount.s_c_f_id
                                                    WHERE f.fee_type_id = $fee_type_id_fee
                                                    AND scfd_discount.s_c_f_id = $challans[$i]
                                                    AND scfd_discount.school_id = " . $_SESSION['school_id'] . "
                                                    AND scfd_discount.type = 2";
                                    $query_discount = $this->db->query($str_discount)->result_array();
                
                                    // Multiple Fee Entry If Discount Value is 0
                                    if(count($query_discount) == 0)
                                    {
                                       if($fee_amount>0)
                                       {
                                           // Credit Journal Entry Challan Recieved (Zeeshan)
                                           $array_ledger_fee = array(
                                               'entry_date' => $entry_date,
                                               'detail' => $fee_type_title
                                                   . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                               'credit' => $fee_amount,
                                               'entry_type' => 3,
                                               'type_id' => $challans[$i],
                                               'student_id' => $value_fee['student_id'],
                                               'school_id' => $fee_school_id,
                                               'coa_id' => $fee_receive_cr_coa_id
                                           );
                                        //   $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_fee);
                                       }
                                   }
                                    
                                    // Discount Entry  if There is Some Discount  
                                    if (count($query_discount) > 0)
                                    {
                                        foreach ($query_discount as $key_discount => $value_discount)
                                        {
                                            $discount_type_id_fee = $value_discount['type_id'];
                                            $discount_type_title = $value_discount['fee_type_title'];
                                            $discount_amount = round((($fee_amount * $value_discount['amount']) / 100));
                                            $total_discount_amount = $total_discount_amount + $discount_amount;
                                            $discount_school_id = $value_discount['school_id'];
                                           // $discount_issue_cr_coa_id = $value_discount['issue_cr_coa_id'];
                                            $discount_receive_dr_coa_id = $value_discount['issue_dr_coa_id'];
                                            $discount_chalan_form_number = $value_discount['chalan_form_number'];
                                            $transaction_detail = student_name_section($value_discount['student_id']);
                                            $percentage_amount = $value_discount['amount'];
                
                                            // $array_ledger_discount = array(
                                            //     'entry_date'    => $entry_date,
                                            //     'detail'        => $discount_type_title. ' ('.$percentage_amount.' %)'
                                            //         .' - ' . get_phrase('discount_chalan_form') .  ' - '. $discount_chalan_form_number ." - " . get_phrase('to') . " - " . $transaction_detail,
                                            //     'debit'         => $discount_amount,
                                            //     'entry_type'    => 3,
                                            //     'type_id'       => $s_c_f_id,
                                            //     'school_id'     => $discount_school_id,
                                            //     'coa_id'        => $discount_receive_dr_coa_id
                                            // );
                
                                            // $this->db->insert(get_school_db().".journal_entry", $array_ledger_discount);
                                            
                                            // Credit Journal Entry Challan Recieved (Zeeshan)
                                                $array_ledger_fee = array(
                                                   'entry_date' => $entry_date,
                                                   'detail' => $fee_type_title
                                                       . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                                   'credit' => $fee_amount - $discount_amount,
                                                   'entry_type' => 3,
                                                   'type_id' => $challans[$i],
                                                   'student_id' => $value_fee['student_id'],
                                                   'school_id' => $fee_school_id,
                                                   'coa_id' => $fee_receive_cr_coa_id
                                                );
                                            //   $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_fee);   
                
                                        }
                
                                    }
                                    /*recent change discount end*/
                
                                    $remanining_amount = $grand_total_amount-$total_discount_amount;
                                    // $total_fee_amount_recieved += $remanining_amount;
                                    
                                    // $total_discount_amount = 0;
                                    // $fee_amount = 0;
                                } //end main loop
                                
                                // Credit Journal Entry Challan Recieved (Zeeshan)
                                $array_ledger_fee_debit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => "Total Fee "
                                        . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                    'credit' => $receieved_amount_in_cash,
                                    'entry_type' => 3,
                                    'type_id' => $challans[$i],
                                    'student_id' => $student_id,
                                    'school_id' => $fee_school_id,
                                    'coa_id' => $fee_receive_cr_coa_id
                                );
                                $this->db->insert(get_school_db().".journal_entry", $array_ledger_fee_debit);
                                // End Total Credit JE
                                
                                // Debit Journal Entry Challan Recieved (Zeeshan)
                                $array_ledger_fee_debit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => "Total Fee "
                                        . ' - ' . get_phrase('receive_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                    'debit' => $receieved_amount_in_cash,
                                    'entry_type' => 3,
                                    'type_id' => $challans[$i],
                                    'student_id' => $student_id,
                                    'school_id' => $fee_school_id,
                                    'coa_id' => $fee_receive_dr_coa_id
                                );
                                $this->db->insert(get_school_db().".journal_entry", $array_ledger_fee_debit);
                                // End Total Debit JE
                                
                                ///////////////////////////
                                //Late Fee Fine Journal Entry
                                ///////////////////////////
                                
                            }
                
                            $data_fees['received_amount'] = $receieved_amount_in_cash;
                            $data_fees['arrears_status'] = 0;
                            $data_fees['received_by'] = $_SESSION['login_detail_id'];
                            $data_fees['received_date'] = date('Y-m-d h:i:s');
                            $payment_date = date("Y-m-d");
                            $data_fees['payment_date'] = date_slash($payment_date);
                            $data_fees['status'] = 5;
                            
                            // Check Previous Challan Query
                            $check_previous_chalan = $this->db->query("SELECT * FROM ".get_school_db().".student_chalan_form WHERE student_id = $student_id AND s_c_f_id <> $challans[$i] AND is_cancelled = 0 AND school_id = '".$_SESSION['school_id']."'");
                            if($check_previous_chalan->num_rows() > 0)
                            {
                                // Previous Challan Paid Query
                                $this->db->where("s_c_f_id <>", $challans[$i]);
                                $this->db->where("student_id", $student_id);
                                $this->db->where('school_id', $_SESSION['school_id']);
                                $this->db->where('is_cancelled', 0);
                                $this->db->update(get_school_db() . ".student_chalan_form", $data_fees);
                            }
                        
                            $data_challan = array(
                               'status' => 5
                            );
                            
                            $this->db->where('s_c_f_id', $challans[$i]);
                            $this->db->where('school_id', $_SESSION['school_id']);
                            $this->db->update(get_school_db().'.student_chalan_form', $data_challan);
                            
                        }
                        
                        if($pBank != '' && trim($invoice_row) == 'Credit/Debit Card')
                        {
                            
                           $data_invoice = array(
                               'PaymentMethod' => $pBank,
                               'IsPaid'        => 1
                           ); 
                           $this->db->where('InvoiceNumber', $orderId);
                           $this->db->where('school_id', $_SESSION['school_id']);
                           $this->db->update(get_school_db().'.payment_consumer', $data_invoice);
                           
                        }
                        else
                        {
                            $data_invoice = array(
                                'IsPaid' => 1
                            );
                            
                            $this->db->where('InvoiceNumber', $orderId);
                            $this->db->where('school_id', $_SESSION['school_id']);
                            $this->db->update(get_school_db().'.payment_consumer', $data_invoice); 
                        }
 
 
                        /*
                        $challans_amount    =   $invoice_row->InvoiceAmount;
                        $student_id         =   $invoice_row->consumer_id;
                        
                        $this->load->helper('message');
                        $sms_ary = get_sms_detail($student_id);
                        $message = "Amount of Rs. " . $challans_amount . " received from " . $sms_ary['student_name'] . ".";
                        send_sms($sms_ary['mob_num'], 'Indici Edu', $message, $student_id,1);

                        $to_email = $sms_ary['email'];
                        $subject = "Fee Received";
                        $email_layout = get_email_layout($message);
                        email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, $student_id,1);   
                        */ 
                        
                        
                                            
                        $this->session->set_flashdata('club_updated', 'Challan(s) Are Paid Successfully');
                        redirect(base_url().'parents/invoice');
                        
                    }
                    
                    $this->session->set_flashdata('club_updated', 'Challan(s) Are Paid Successfully');
                    redirect(base_url().'parents/invoice');
                    
                    
                }
                else
                {
                    
                    $this->session->set_flashdata('club_updated', 'Challan(s) Are Paid Successfully');
                    redirect(base_url().'parents/invoice');
                  
                }
   
            } 
            else 
            {
                
                $msg = $_POST['message'];
                $this->session->set_flashdata('club_updated', $msg);
                $page_data['invoices'] = $this->db->query( "select scf.* from ".get_school_db().".student_chalan_form scf
                			inner join ".get_school_db().".class_chalan_form ccf on ccf.c_c_f_id=scf.c_c_f_id and ccf.section_id =".$_SESSION['section_id']."
                			and ccf.type = 2 where scf.student_id= ".$_SESSION['student_id']." and scf.school_id=".$_SESSION['school_id']." and scf.status IN (4,5)
                			and ( DATE(scf.issue_date) between '".$year_arr[0]['start_date']."' and '".$year_arr[0]['end_date']."' ) ")->result_array();
            		
                $page_data['page_name'] = 'invoice';
                $page_data['page_title'] = get_phrase('invoice_payment');
                $this->load->view('backend/index', $page_data);
                //redirect(base_url().'parents/invoice');
                
            }
                
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            
        } else {
            
            print_r('No Input Detected!');
            
        }
        
        
    }
    
    
    public function createInvoice($student_id , $challans_id , $name , $phone , $amount , $payment_method_id){
        
        $pre_values     =  array('ClientID' => BLINQ_CLIENT, 'ClientSecret' => BLINQ_SECRET);
        $token_response =  $this->blinqapi->getToken('api/auth', $pre_values);
        $token_string   =  $token_response[0];
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
    
    function challan_recieve_reciept($s_c_f_id,$typp )
    {
        if($s_c_f_id=="" || $typp=="")
        {
            $this->session->set_flashdata('club_updated',get_phrase('chalan_form_not_created_yet'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            $school_id = $_SESSION['school_id'];
            $query_ary = $this->db->query("select * from ".get_school_db().".student_chalan_form where s_c_f_id = $s_c_f_id and school_id = $school_id and is_cancelled = 0 and status>3")->result_array();
            
            $page_data['invoice_details']   = $query_ary;
            $page_data['page_name']         = 'challan_recieve_reciept';
        	$page_data['page_title']        = get_phrase('challan_receive_receipt');
        	$this->load->view('backend/index', $page_data);
        }
    }
    
    
    function calendar_view($year=0,$month=0)
    {
            $date = array();
            /* Academic year Start */
            $query_academic_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as date, ay.* FROM ".get_school_db().".acadmic_year ay
                    where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month ))
                    AND School_id = ".$_SESSION['school_id']."
                    UNION
                    SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as date, ay.* FROM ".get_school_db().".acadmic_year ay
                    where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month ))
                    AND School_id = ".$_SESSION['school_id']."";
            $query_academic_result = $this->db->query($query_academic_str)->result_array();
            if(count($query_academic_result)>0)
            {
                foreach ($query_academic_result as $key => $value)
                {
                    $d = $value['date'];
                    $date[$d] .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                }
            }
            /* Academic year End */

            /* Academic Terms  start */
            $query_academic_terms_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as term_date, ay.* FROM ".get_school_db().".yearly_terms ay
                WHERE ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month ))
                AND School_id = ".$_SESSION['school_id']."
                UNION
                SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as term_date, ay.* FROM ".get_school_db().".yearly_terms ay
                where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month ))
                AND School_id = ".$_SESSION['school_id']."";
            $query_academic_terms_result = $this->db->query($query_academic_terms_str)->result_array();
            //print_r($query_academic_result);
            if(count($query_academic_terms_result)>0)
            {
                foreach ($query_academic_terms_result as $key => $value) {
                    $d = $value['term_date'];
                    $date[$d] .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                }
            }
            /* Academic Terms End */
            
            
            /* Assessment Start*/
            $query_assessment_str = "select ass.* ,ass_aud.* ,DATE_FORMAT(ass_aud.assessment_date, '%e') as ass_date, s.name as teacher_name from ".get_school_db().".assessments ass
            	    inner join ".get_school_db().".assessment_audience ass_aud on ass.assessment_id = ass_aud.assessment_id
            	    inner join ".get_school_db().".staff s on ass.teacher_id = s.staff_id
            	    WHERE ass_aud.student_id = ".$_SESSION['student_id']." and ass_aud.is_submitted = 0 and ass.school_id = ".$_SESSION['school_id']." 
            	    and ((DATE_FORMAT(assessment_date, '%Y') = $year) AND (DATE_FORMAT(assessment_date, '%c') = $month ))
            	    ORDER BY ass.assessment_id DESC";

            $query_assessment_result = $this->db->query($query_assessment_str)->result_array();
            
            if(count($query_assessment_result)>0) {
                foreach ($query_assessment_result as $key => $value)
                {
                    $d = $value['ass_date'];
                    $date[$d] .='<li type="button" data-toggle="tooltip" data-placement="top" style="font-size: 11px;" title="Assessments"> <a href="'.base_url().'assessment_student/view_assessment"> '.substr($value['assessment_title'],0,20).' <br> Teacher: '.$value['teacher_name'].' <br> Subject: '.get_subject_name($value['subject_id']).' </a><span></span></li>';
                }
                
            }
            /* Assessment End */
        
            echo $this->calendar->generate($year,$month,$date);
        }
       
     
        
     function getsubdiary()  { 
        
        // $data = "";
        // $sub_id  = $this->input->post('sub_id');
        // if (get_diary_approval_method() == 2){
        //     $this->db->where("admin_approvel",'1');
        // }
        // $this->db->where("subject_id",$sub_id);
        // $this->db->where("section_id",$_SESSION['section_id']);
        // $this->db->where("school_id",$_SESSION['school_id']);
        // $arr = $this->db->get(get_school_db().".diary")->result_array();
        $data = "";
        $sub_id  = $this->input->post('sub_id');
        $where = "";
        if (get_diary_approval_method() == 2){
            $where = "and admin_approvel = '1'";
        }
        //working
        $academic_year_dates = get_academic_year_dates($_SESSION['academic_year_id']);
        $academic_year_start_date  = $academic_year_dates->start_date;
        $academic_year_end_date  = $academic_year_dates->end_date;
        
        $academic_year_filter = "and assign_date between '$academic_year_start_date' and '$academic_year_end_date' ";
        
        $qur = "select * from ".get_school_db().".diary where subject_id = ".$sub_id." 
        and section_id = ".$_SESSION['section_id']." and school_id = ".$_SESSION['school_id']." $where $academic_year_filter order by diary_id desc"; 
        $arr = $this->db->query($qur)->result_array();
        
        if(count($arr) > 0)
        { 
            foreach($arr as $row)
            {
                $data .= '<li onclick="getdiarycontent('.$row['diary_id'].')"> <span><i class="far fa-file"></i> '.$row['title'].' </span> </li>'; 
            }
        }else
        {
            $data .= '<li> <span><i class="far fa-file"></i> No Diary </span> </li>';
        }    
        echo $data;
    }
    
    function getdiarycontent() {
        
        $data = "";
        
        $diaryb_id = $this->input->post('diaryb_id');
        $qur = "select d.* , d.diary_id as d_id , aud.* , ds.* from ".get_school_db().".diary d  
        left join ".get_school_db().".diary_audio aud on aud.diary_id = d.diary_id
        left join ".get_school_db().".diary_student ds on d.diary_id = ds.diary_id
        where d.diary_id =  $diaryb_id and d.school_id = ".$_SESSION['school_id']." "; 
        $diary_arr = $this->db->query($qur); 
        $arr = $diary_arr->row(); 
        
        if(count($diary_arr->num_rows()) > 0) {
        $data .= '<table class="table m-0">
                    <tbody>
                        <tr>
                            <td>
                                <b>Title</b><br>
                                '.$arr->title.'
                            </td> 
                        </tr>
                        <tr>
                            <td>
                                <b>Task Details</b><br>
                                '.$arr->task.'
                            </td> 
                        </tr>
                        <tr>
                          	<td>
                          	    <b>Teacher</b><br>
                          	    '.get_teacher_name($arr->teacher_id).'
                          	</td> 
                        </tr>
                        <tr>
                            <td>
                                <b>Assign Date</b><br>
                                '.date_view($arr->assign_date).'
                            </td> 
                        </tr>
                        <tr>
                          	<td>
                          	    <b>Due Date</b><br>
                          	    '.date_view($arr->due_date).'
                          	</td> 
                        </tr> ';
                        if ($arr->audio != ""){
                            $audio_path = base_url()."uploads/".$_SESSION['folder_name']."/diary_audios/".$arr->audio;
                            $data .= '<tr><td>
                                    <strong>Audio : </strong> <br>
                                    <audio controls>
                                      <source src="'.$audio_path.'" type="audio/ogg">
                                      <source src="'.$audio_path.'" type="audio/mpeg">
                                    </audio>
                                </td></tr>';
                        }
                        
                        if ($arr->attachment != ""){
                            $attachment_path = base_url()."uploads/".$_SESSION['folder_name']."/diary/".$arr->attachment;
                            $data .= '<tr><td>
                                    <b>Diary Attachment</b><br>
                                    <a target="_blank" href="'.$attachment_path.'"><i class="fas fa-paperclip"></i> Click to open the Link</a>
                                    </td></tr>';
                        }
                        
                        if($arr->is_submitted == 1){
                            $data .= '<tr>
                                <td class="">
                                <b>Diary Status</b><br>
                                <div style="color:green;">
                                    <strong>Submission Date : </strong>
                                    '.date_view($arr->submission_date).'
                                </div>
                            </td>';
                        }elseif($arr->due_date < date('Y-m-d') ) {
                            $data .= '<td class="">
                                        <b>Diary Status</b><br>
                                        <span style="color:red;">Submission Time Expired</span>
                                    </td>';
                        }else{
                            $data .= '<td class="">
                            <b>Diary Status</b><br><br>
                                <a href="'.base_url().'student_p/solve_assignment/'.$arr->d_id.'" class="modal_save_btn" target="_blank">Click here to solve assignment</a>
                            </td>';
                        }
                        $data .= '</tr>';
                        
        $data .= '</tbody>
                    </table> '; 
        }else
        {
            
           $data = '';
        }
        echo $data;
    }
    
    function chat_module(){
       
      
       $login_detail_id = $_SESSION['login_detail_id'];   //parent_login_detail_id
       $student_id = $_SESSION['student_id'];
       
       $selected_teacher=  $_POST['teacher_id'];    //click for teacher then get id 
       
       $student_detail_arr = $this->db->query(" select user_login_detail_id from ".get_school_db().".student 
    	                          where student_id = $student_id
     	                          ")->row();
     	 $student_user_detail_id = $student_detail_arr->user_login_detail_id;
   
         $data = get_teacher_subjects($selected_teacher);
         $teacher_name = $data[0]['name'];
         //  print_r($teacher_name);exit;
       
         $teacher_login_detal_id = $data[0]['user_login_detail_id'];
    //   print_r($teacher_login_detal_id);exit;
        
        if($student_user_detail_id != 0 && $teacher_login_detal_id != 0)
        {
           
           $chat_id = $teacher_login_detal_id.$login_detail_id.$student_user_detail_id;
           
             $data = array(
                    'send_id'   => $_SESSION['login_detail_id'],
                    'rec_id'    =>  $teacher_login_detal_id,
                    'chat_id'   => $chat_id,
                    'student_id'   => $student_user_detail_id,
                );
                
                                      $chat_json_data['name'] =  $teacher_name;
             	                      $chat_json_data['chat_id'] = $chat_id;
             	                      $chat_json_data['send_id'] = $login_detail_id;
             	                      $chat_json_data['student_id'] = $student_user_detail_id;
             	                      $chat_json_data['rec_id'] = $teacher_login_detal_id;
             	                      echo json_encode($chat_json_data);
                
                // $chat_array = $this->db->query(" select *,chat_id from ".get_school_db().".chat_relation 
    	           //               where send_id = ".$_SESSION['login_detail_id']."
    	           //               and student_id = ".$student_user_detail_id."
     	          //                and rec_id = ".$teacher_login_detal_id."
     	          //                OR send_id = ".$teacher_login_detal_id."
     	          //                OR rec_id = ".$_SESSION['login_detail_id']."
     	          //                ")->num_rows();
     	                          
     	                          //if($chat_array > 0){
     	                             
         	                      //    $chat_data = $this->db->query(" select * from ".get_school_db().".chat_relation 
        	                       //   where send_id = ".$_SESSION['login_detail_id']."
         	                      //    and rec_id = ".$teacher_login_detal_id."
         	                      //    and student_id = ".$student_user_detail_id."
         	                      //    OR send_id = ".$teacher_login_detal_id."
         	                      //    OR rec_id = ".$_SESSION['login_detail_id']."
         	                      //    ")->result_array();
     	                          
             	                  //    $chat_json_data['name'] =  $teacher_name;
             	                  //    $chat_json_data['chat_id'] = $chat_data[0]['chat_id'];
             	                  //    $chat_json_data['send_id'] = $chat_data[0]['send_id'];
             	                  //    $chat_json_data['student_id'] = $chat_data[0]['student_id'];
             	                  //    $chat_json_data['rec_id'] = $chat_data[0]['rec_id'];
             	                  //    echo json_encode($chat_json_data);
     	                          //}
     	                          //else{
     	                          //     $this->db->insert(get_school_db().'.chat_relation',$data);
     	                          //    }
        }
        else{
           $output = '';
            $output .='
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                  Launch demo modal
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                         <p>Not created User login chat.</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                      </div>
                    </div>
                  </div>
                </div>';
        echo $output;
        }
       
    }   


}
