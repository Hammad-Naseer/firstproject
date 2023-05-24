<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

ob_start();
class VirtualClass extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if($_SESSION['user_login'] != 1)
		redirect('login');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	
    
    function subject_recording()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        
        $date                    = $this->input->post('date_recording', TRUE);
        $section_id              = $this->input->post('section_id', TRUE);

        $page_data['section_id'] = $section_id;
        $page_data['date']       = $date;
        $page_data['page_name']  = 'subjectwise_recording';
        $page_data['page_title'] = get_phrase('subjectwise_recording');

        $this->load->view('backend/index', $page_data);
    }	
	
	
	function vc_current_list(){
	    if ($_SESSION['user_login'] != 1)
            redirect('login');
        $per_page = 10;

        $apply_filter = $this->input->post('apply_filter', TRUE);
        $std_search   = $this->input->post('std_search', TRUE);
        $std_search   = trim(str_replace(array("'", "\""), "", $std_search));
        $section_id   = $this->input->post('section_id', TRUE);

        $std_query = "";
        if (isset($std_search) && $std_search != "") {
            $std_query = " AND (
                staff.name LIKE '%" . $std_search . "%' OR 
                virtual_class.virtual_class_name LIKE '%" . $std_search . "%' OR 
                staff.employee_code LIKE '%" . $std_search . "%'
            )";
        }
        if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(3);
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = 0;
        }
        $section_filter = "";
        if ($section_id > 0) {
            $section_filter = " AND class_routine_settings.section_id=" . $section_id;
        }
        $page_num = $this->uri->segment(4);

        if (!isset($page_num) || $page_num == "") {
            $page_num    = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }

        $q = 'select staff.name, virtual_class.virtual_class_name, staff.employee_code, virtual_class.virtual_class_id, class_routine_settings.section_id, class_section.title, class.name as class_name from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
        .     class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
              JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
              JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
              WHERE vc_end_time IS NULL '.$std_query . $section_filter;
        $class_count   = $this->db->query($q)->result_array();
        $total_records = count($class_count);
        $quer_limit    = $q . " limit " . $start_limit . ", " . $per_page . "";

        $classes                    =  $this->db->query($quer_limit)->result_array();
        
        $config['base_url']         =  base_url() . "virtualclass/vc_current_list/" . $section_id . "/";
        $config['total_rows']       =  $total_records;
        $config['per_page']         =  $per_page;
        $config['uri_segment']      =  4;
        $config['num_links']        =  2;
        $config['use_page_numbers'] =  TRUE;

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->load->library('pagination');
        
        $page_data['start_limit']   = $start_limit;
        $page_data['apply_filter']  = $apply_filter;
        $page_data['total_records'] = $total_records;
        $page_data['section_id']    = $section_id;
        $page_data['std_search']    = $std_search;
        $page_data['pagination']    = $pagination;
        $page_data['students']      = $classes;
        $page_data['controller']    = 's';
	    $page_data['page_name']     = 'current_list';
        $page_data['page_title']    = get_phrase('virtual_classes_current_list');
        
        $this->load->view('backend/index', $page_data);
	}
	
	
	function vc_complete_list(){
	    
	    if ($_SESSION['user_login'] != 1)
            redirect('login');
        
        $per_page     =  10;
        $apply_filter =  $this->input->post('apply_filter', TRUE);
        $std_search   =  $this->input->post('std_search', TRUE);
        $std_search   =  trim(str_replace(array("'", "\""), "", $std_search));
        $section_id   =  $this->input->post('section_id', TRUE);
        $std_query    =  "";
        
        if (isset($std_search) && $std_search != "") {
            $std_query = " AND (
                staff.name LIKE '%" . $std_search . "%' OR 
                virtual_class.virtual_class_name LIKE '%" . $std_search . "%' OR 
                staff.employee_code LIKE '%" . $std_search . "%'
            )";
        }
        if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(3);
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = 0;
        }
        $section_filter = "";
        if ($section_id > 0) {
            $section_filter = " AND class_routine_settings.section_id=" . $section_id;
        }
        $page_num = $this->uri->segment(4);

        if (!isset($page_num) || $page_num == "") {
            $page_num    = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }

        $q = 'select staff.name, virtual_class.virtual_class_name, staff.employee_code, virtual_class.virtual_class_id, class_routine_settings.section_id, class_section.title, class.name as class_name from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
        .     class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
              JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
              JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
              WHERE vc_end_time IS NOT NULL '.$std_query . $section_filter;
        $class_count   = $this->db->query($q)->result_array();
        $total_records = count($class_count);
        $quer_limit    = $q . " limit " . $start_limit . ", " . $per_page . "";

        $classes       = $this->db->query($quer_limit)->result_array();
        
        $config['base_url']         = base_url() . "virtualclass/vc_complete_list/" . $section_id . "/";
        $config['total_rows']       = $total_records;
        $config['per_page']         = $per_page;
        $config['uri_segment']      = 4;
        $config['num_links']        = 2;
        $config['use_page_numbers'] = TRUE;

        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        $this->load->library('pagination');
        
        $page_data['start_limit']   = $start_limit;
        $page_data['apply_filter']  = $apply_filter;
        $page_data['total_records'] = $total_records;
        $page_data['section_id']    = $section_id;
        $page_data['std_search']    = $std_search;
        $page_data['pagination']    = $pagination;
        $page_data['students']      = $classes;
        $page_data['controller']    = 's';
	    $page_data['page_name']     = 'past_list';
        $page_data['page_title']    = get_phrase('virtual_classes_current_list');
        
        $this->load->view('backend/index', $page_data);
	}
	
	
	function vc_attendance_list($date='',$month='',$year='',$section_id=''){
	    
	    if($_SESSION['user_login']!=1)redirect('login' );
		$filter = 0;
		if($_POST)
		{
    		$filter     =   1;
    		$section_id =   $this->input->post('section_id');
    		$date_new	=	explode("/",$_REQUEST['date']);
            $date       =	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
    	 	$date       =   $date_new['0'];
    		$month      =   $date_new['1'];
    		$year       =   $date_new['2'];
		}
		$page_data['filter']        =   $filter;
	 	$page_data['date']		    =	$date;
		$page_data['month']		    =	$month;
	    $page_data['year']		    =	$year;
		$page_data['section_id']	=	$section_id;
	    $page_data['page_name']     =   'manage_attendance1';
        $page_data['page_title']    =   get_phrase('virtual_classes_current_list');
        
        $this->load->view('backend/index', $page_data);
	}
	
	
	function class_attendance_list($parma = '', $parma1=''){
	    if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        $per_page     = 10;
        $sectionV_id  = $this->uri->segment(3);
        $classV_id    = $this->uri->segment(4);
        $apply_filter = $this->input->post('apply_filter', TRUE);
        $std_search   = $this->input->post('std_search', TRUE);
        $std_search   = trim(str_replace(array("'", "\""), "", $std_search));
        $section_id   = $this->input->post('section_id', TRUE);
        $std_query    = "";
        
        if (isset($std_search) && $std_search != "") {
            $std_query = " AND (
                staff.name LIKE '%" . $std_search . "%' OR 
                virtual_class.virtual_class_name LIKE '%" . $std_search . "%' OR 
                staff.employee_code LIKE '%" . $std_search . "%'
            )";
        }
        if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(3);
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = 0;
        }
        $section_filter = "";
        if ($section_id > 0) {
            $section_filter = " AND class_routine_settings.section_id=" . $section_id;
        }
        $page_num = $this->uri->segment(5);

        if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }

         $q = 'SELECT * FROM '.get_school_db().'. subject JOIN subject_section on subject.subject_id = subject_section.subject_id JOIN '.get_school_db().'. class_routine cr ON cr.subject_id = subject_section.subject_id 
               WHERE subject_section.section_id =' .$sectionV_id;
         $q = 'SELECT subject.subject_id, subject.name, subject.code, subject_section.subject_section_id, subject_section.subject_id, subject_section.section_id
               FROM '.get_school_db().'.`subject` 
               LEFT JOIN '.get_school_db().'. subject_section ON subject.subject_id = subject_section.subject_id
               JOIN '.get_school_db().'.class_routine cr ON cr.subject_id = subject_section.subject_id
               WHERE subject_section.section_id =' .$sectionV_id . ' GROUP BY subject.subject_id';

        $class_count   =  $this->db->query($q)->result_array();
        $total_records =  count($class_count);
        $quer_limit    =  $q . " limit " . $start_limit . ", " . $per_page . "";
        $classes       =  $this->db->query($quer_limit)->result_array();
        
        $config['base_url']         =  base_url() . "virtualclass/vc_complete_list/" . $section_id . "/";
        $config['total_rows']       =  $total_records;
        $config['per_page']         =  $per_page;
        $config['uri_segment']      =  4;
        $config['num_links']        =  2;
        $config['use_page_numbers'] =  TRUE;

        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();

        $this->load->library('pagination');
        
        $page_data['start_limit']   = $start_limit;
        $page_data['apply_filter']  = $apply_filter;
        $page_data['total_records'] = $total_records;
        $page_data['section_id']    = $section_id;
        $page_data['std_search']    = $std_search;
        $page_data['pagination']    = $pagination;
        $page_data['students']      = $classes;
        $page_data['controller']    = 's';
	    $page_data['page_name']     = 'class_vc_attendence';
        $page_data['page_title']    = get_phrase('virtual_classes_current_list');
        
        $this->load->view('backend/index', $page_data);
	}
	
	
	function view_detail($parma = '', $parma1='', $parma2=''){
	    
    	$meetingId = "Meeting-".$parma1;
	    if($parma == 'active'){
    	    $getUrl = WEBRTC_LINK."api/getMeetingInfo?";
    		$params = '&meetingID='.urlencode($meetingId).
    		'&password='.urlencode('mp');
            $url_get = $getUrl.$params.'&checksum='.sha1("getMeetingInfo".$params.WEBRTC_SECRET);
            $xmldata = simplexml_load_file($url_get) or die("Failed to load");
            
            $page_data['all_data']   = $xmldata;
            $page_data['status']     = 'active';
            $page_data['class_name'] = $parma2;
            $page_data['page_name']  = 'view_detail';
            $page_data['page_title'] = get_phrase('meeting_detail');
            $this->load->view('backend/index', $page_data);
	    }
	    else{
	         $page_data['status']    = 'complete';
            $page_data['class_name'] = $parma2;
            $page_data['class_id']   = $meetingId;
            $page_data['page_name']  = 'view_detail';
            $page_data['page_title'] = get_phrase('meeting_detail');
            $this->load->view('backend/index', $page_data);
	    }
	}
	
	
	function view_details($parma = ''){
	   
         $page_data['page_name']  = 'view_details';
         $page_data['page_title'] = get_phrase('meeting_detail');
         $this->load->view('backend/index', $page_data);
            
	}
	
	
	function join($parma1='', $parma2='', $parma3=''){
	    $meetingId = "Meeting-".$parma1;
	    $userName  = $parma2;
	    $userId    = $parma3;
	    $joinUrl   = WEBRTC_LINK."api/join?";
		$params    = '&meetingID='.urlencode($meetingId).
		'&fullName='.urlencode($userName).
		'&password='.urlencode('pw').
		'&userID='.urlencode($userId).
		'&joinViaHtml5=true'.
		'&webVoiceConf='.urlencode('');
        $url_join = $joinUrl.$params.'&checksum='.sha1("join".$params.WEBRTC_SECRET);

        redirect($url_join);
	}
	
	
	function view_recording($parma1='', $parma2=''){
	    $meetingId     = "Meeting-".$parma1;
	    $recordingUrl  = WEBRTC_LINK."api/getRecordings?";
		$params        = '&meetingID='.urlencode($meetingId);
        $url_recording = $recordingUrl.$params.'&checksum='.sha1("getRecordings".$params.WEBRTC_SECRET);
        $xmldata       = simplexml_load_file($url_recording) or die("Failed to load");
        if($xmldata->returncode == 'SUCCESS' And $xmldata->messageKey != 'null'){
            if($xmldata->recordings->recording->playback->format->type == 'presentation'){
                $url = $xmldata->recordings->recording->playback->format->url;
                redirect($url);
            }
        }
        else{
            $this->session->set_flashdata('club_updated', 'No recording found of this class.');
            redirect($_SERVER['HTTP_REFERER']);
        }
	}
	
	
	function add_attendance($parma1='', $parma2=''){
	    
	    $sectionV_id = $this->uri->segment(3);
        $classV_id   = $this->uri->segment(4);
	    $currentDay  = date("Y-m-d");
	    $q = 'select staff.name,class.class_id, virtual_class.virtual_class_name, staff.employee_code, virtual_class.Current_Days,
              virtual_class.virtual_class_id,virtual_class.vc_start_time, class_routine_settings.section_id, class_section.title, class.name as class_name 
              from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
              .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
              JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
              JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
               WHERE Current_Days='. "' $currentDay '" .' AND class_section.section_id = '. "' $sectionV_id '" .' GROUP BY class_section.title '.$std_query . $section_filter;
	    
	    $class_count             = $this->db->query($q)->result_array();
	    $page_data['page_name']  = 'attendance_add';
        $page_data['page_title'] = get_phrase('meeting_detail');
        $this->load->view('backend/index', $page_data);
	}
	
	
	function view_classes_list($parma1='', $parma2='', $parma3=''){
	    
	    $c_day   = date('l');
	    $c_time  = date('H:i:s');
	    $sect_id = $parma2;
	    $c_id    = $parma3;
	    
	    if($parma1=='complete'){
	        $q = 'select class_routine.period_start_time, class_routine.period_end_time, staff.name, staff.employee_code, class_routine_settings.section_id, class_section.title, class.name as class_name from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
                  .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                  JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
                  JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
                  WHERE  period_end_time < "'.$c_time.'" AND  day = "'.$c_day.'" AND end_meeting <> "0000-00-00 00:00:00" AND class_section.section_id = '.$sect_id .' AND class.class_id = '.$c_id;
	    }
	    else if($parma1=='not_complete'){
	        $q = 'select subject.name as subject, subject.code, class_routine.period_start_time, class_routine.period_end_time, staff.name, staff.employee_code, class_routine_settings.section_id, class_section.title, class.name as class_name  from '.get_school_db().'.class_routine JOIN '.get_school_db().'
                  .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                  JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
                  JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
                  JOIN '.get_school_db().'.subject ON class_routine.subject_id = subject.subject_id
                  WHERE period_end_time < "'.$c_time.'" AND day = "'.$c_day.'" AND  class_section.section_id = '.$sect_id.' AND class.class_id = '.$c_id.' AND class_routine.class_routine_id NOT IN (select virtual_class.class_routine_id from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
                 .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                 JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
                 WHERE  period_end_time < "'.$c_time.'" AND  day = "'.$c_day.'" AND end_meeting <> "0000-00-00 00:00:00" AND class_section.section_id = '.$sect_id .' AND class.class_id = '.$c_id.')';
	    }
	    else if($parma1=='active'){
	        echo "3";exit;
	        
	    }
	    else{
	        echo "4";exit;
	        
	    }
	    
	    $classes                 = $this->db->query($q)->result_array();
        $page_data['students']   = $classes;
        $page_data['type']       = $parma1;
	    $page_data['page_name']  = 'vc_classes_list';
        $page_data['page_title'] = get_phrase('virtual_classes_list');
        $this->load->view('backend/index', $page_data);
	}
	
	
	function class_end($parma1='')
	{
	    $class_id     =  "Meeting-".$parma1;
	    $user_id      =  $_SESSION['user_login_id'];
	    $login_type   =  $_SESSION['login_type'];
	    $t            =  time();
        $current_time =  date("Y-m-d H:i:s",$t);
        
	    if($login_type == 3)
	    {
	        $this->db->query("update ".get_school_db().".virtual_class set vc_end_time = '$current_time' WHERE virtual_class_id='$class_id'");
	        $this->db->query("update ".get_school_db().".virtual_class_student set vc_end_time = '$current_time' WHERE virtual_class_id='$class_id' AND vc_end_time IS NULL");
	        redirect("teacher/dashboard");
	    }
	    else if($login_type == 4)
	    {
            $studentId = $_SESSION['student_id'];
            $this->db->query("update ".get_school_db().".virtual_class_student set vc_end_time = '$current_time' WHERE student_id=$studentId and virtual_class_id='$class_id'");
            redirect("parents/dashboard");
	    }
	    else if($login_type == 6)
	    {
            $studentId = $_SESSION['student_id'];
            $this->db->query("update ".get_school_db().".virtual_class_student set vc_end_time = '$current_time' WHERE student_id=$studentId and virtual_class_id='$class_id'");
            redirect("student_p/dashboard");
	    }
	    //echo '<script type="text/javascript">window.close();</script>';
	}
   
}