<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Student_p extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Crud_model');
		
		/*cache control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-swtore, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		if($_SESSION['user_login']!= 1)
			redirect(base_url() . 'login');
		if( $_SESSION['login_type'] != '6')
			redirect(base_url() . 'login');
	}

	/***default functin, redirects to login page if no admin logged in yet***/
	public function index()
	{
		if($_SESSION['login_type'] != '6')
			redirect(base_url() . 'login');
		
		//if(get_login_type_name($_SESSION['login_type']) != '6')
			//redirect(base_url() . ''.get_login_type_controller($_SESSION['login_type']).'/dashboard');
	}
	
	/***ADMIN DASHBOARD***/
	function dashboard($std_id = "")
	{
	 	// echo "STD";exit();   
		$NICNO = $_SESSION['NIC'];
		$q = "select s.*, c.title as class_name, clas.class_id, dep.title as department_name,dep.departments_id as department_id,
		c.title as section_name from ".get_school_db().".student s inner join ".get_school_db().".class_section c on s.section_id=c.section_id  
		inner join ".get_school_db().". class clas ON c.class_id=clas.class_id
		INNER JOIN ".get_school_db().".departments dep ON dep.departments_id=clas.departments_id 
		where s.school_id=".$_SESSION['school_id']." AND s.id_no = '$NICNO' ";
	
		$strd_rec=$this->db->query($q)->result_array();
		$_SESSION['class_id'] = $strd_rec[0]["class_id"];
		$_SESSION['section_id'] = $strd_rec[0]["section_id"];
		$_SESSION['student_id'] = $strd_rec[0]["student_id"];
		$std_id = $_SESSION['student_id'];
		
		$qq = "select s.*, c.title as class_name, clas.class_id, clas.name as cls_name, dep.title as department_name,dep.departments_id as department_id,  c.title as section_name 
        		FROM 
        		".get_school_db().".student s inner join ".get_school_db().".class_section c on s.section_id=c.section_id  
        		inner join ".get_school_db().". class clas ON c.class_id=clas.class_id
        		INNER JOIN ".get_school_db().".departments dep ON dep.departments_id=clas.departments_id 
        		where s.school_id=".$_SESSION['school_id']." AND s.id_no = '$NICNO' ";
		
		if($std_id != "")
		{ 
			if($_SESSION['student_id'] == "")
			{
				$qq .= " AND s.student_id=".$_SESSION['student_id']."";
				
				$qq = "SELECT sr.*, c.title as class_name, sp.*,s.*
				FROM ".get_school_db().".student_parent sp 
				INNER JOIN ".get_school_db().".student_relation sr ON sr.s_p_id = sp.s_p_id 
				inner join ".get_school_db().".student s on s.student_id=sr.student_id 
				inner join ".get_school_db().".class_section c on c.section_id=s.section_id

				WHERE sp.s_p_id =".$_SESSION['login_detail_id']."  
				and sp.school_id=".$_SESSION['school_id']." 
				and s.student_id=".$_SESSION['student_id']."  limit 1";

			}
		}
		else
		{
			//$qq .= " AND s.student_id=$std_id";
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
				$_SESSION['class_name']      =  $qrr->cls_name;
				$_SESSION['department_id']   =  $qrr->department_id;
				$_SESSION['department_name'] =  $qrr->department_name;
				
				$yearly_term_arr = $this->db->query( "select yt.* FROM ".get_school_db().".acadmic_year ay
					               INNER JOIN ".get_school_db().".yearly_terms yt ON ay.academic_year_id=yt.academic_year_id
					               WHERE ay.school_id = ".$_SESSION['school_id']." AND yt.status = 2 LIMIT 1 ")->result_array();

				$_SESSION['academic_year_id'] = $yearly_term_arr[0]['academic_year_id'] > 0 ? $yearly_term_arr[0]['academic_year_id']: 0;
				$_SESSION['yearly_term_id']   = $yearly_term_arr[0]['yearly_terms_id']  > 0 ? $yearly_term_arr[0]['yearly_terms_id']: 0;
			}
		}
		else
		{
			$_SESSION['student_name'] = "";
			$_SESSION['student_image'] = "";
			$_SESSION['student_id'] = 0;
			$_SESSION['section_id'] = 0;
			$_SESSION['section_name'] = "";
			$_SESSION['class_id'] = 0;
			$_SESSION['class_name'] = "";
			$_SESSION['department_id'] = 0;
			$_SESSION['department_name'] = "";
			$_SESSION['academic_year_id'] = 0;
			$_SESSION['yearly_term_id'] = 0;
		}
		$assessment_query = "select ass.* ,ass_aud.* , s.name as teacher_name from ".get_school_db().".assessments ass
	    inner join ".get_school_db().".assessment_audience ass_aud on ass.assessment_id = ass_aud.assessment_id
	    inner join ".get_school_db().".staff s on ass.teacher_id = s.staff_id
	    WHERE ass_aud.student_id = ".$_SESSION['student_id']." and ass_aud.is_submitted = 0 
	    and DATE_FORMAT(ass_aud.assessment_date, '%Y-%m-%d') = CURDATE() and ass.school_id = ".$_SESSION['school_id']."";
	    $assessments = $this->db->query($assessment_query)->result_array();
	    $page_data['assessment']		=	$assessments;
     
	    $attendance_qur = "SELECT IF(status = 1, count(status), 0) as present , IF(status = 2, count(status), 0) as absent ,
	        IF(status = 3, count(status), 0) as leaves FROM ".get_school_db().".attendance 
	        where student_id = ".$_SESSION['student_id']." and school_id = ".$_SESSION['school_id']." and MONTH(date)=MONTH(now()) and YEAR(date)=YEAR(now())";
	    $attendance_arr = $this->db->query($attendance_qur)->row();
	
	   
	    $page_data['attendance_arr']		=	$attendance_arr;
	    $sub_arr = $this->db->query(" select count(sub.subject_id) as total_subjects from ".get_school_db().".subject sub inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id where ss.section_id = ".$_SESSION['section_id']." and ss.school_id = ".$_SESSION['school_id']." ")->row();
     	$page_data['total_subjects']		=	$sub_arr->total_subjects;
     	
     	
        $count_events = $this->db->query("SELECT COUNT(ed.event_detail_id) AS TE FROM ".get_school_db().".events_annoucments_details ed LEFT JOIN ".get_school_db().".events_annoucments ea ON ea.event_id = ed.event_id WHERE ed.student_id = '".$_SESSION['student_id']."' AND ea.active_inactive = '1' AND ea.event_status = '1' AND CURDATE() <= ea.event_end_date")->row();
	    $page_data['count_events']		=	$count_events->TE;
	    
	    
        $current_date = date("Y-m-d");
        $count_dairy =  $this->db->query("SELECT COUNT(d.diary_id) AS diaries FROM ".get_school_db().".diary d 
                         JOIN ".get_school_db().".diary_student ds ON ds.diary_id = d.diary_id 
                         WHERE ds.student_id = '".$_SESSION['student_id']."' AND ds.school_id = '".$_SESSION['school_id']."' 
                         AND d.school_id = '".$_SESSION['school_id']."'
                         AND ds.is_submitted = 0
                         AND DATE_FORMAT(d.assign_date, '%Y-%m-%d') = '$current_date'")->row();
	    $page_data['count_dairy'] =	$count_dairy->diaries;;
	   
		$page_data['page_name'] = 'dashboard';
		$page_data['page_title'] = get_phrase('parent_dashboard');
		$this->load->view('backend/index', $page_data);
	}
	
	//////////////////////////////////////////////////////////////////////
	function updated_vc(){
	    $q = 'select * from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'.class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id WHERE vc_end_time IS NULL';
        $result = $this->db->query($q)->result_array();
        $arr = array();
        foreach($result as $r){
            $data = array();
            $data['class_routine_id'] = $r['class_routine_id'];
            $data['section_id'] = $r['section_id'];
            $data['subject_teacher_id'] = $r['subject_teacher_id'];
            $arr[] = $data;
        }
	    echo json_encode($arr);
	}
	
	function subjects()
	{
	    $subject_array = $this->db->query(" select sub.* from ".get_school_db().".subject sub
    	                          inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
    	                          where ss.section_id = ".$_SESSION['section_id']."
     	                          and ss.school_id = ".$_SESSION['school_id']." ")->result_array();
     	$page_data['data'] = $subject_array;                          
	    $page_data['page_name'] = 'subjects';
		$page_data['page_title'] = get_phrase('subjects');
		$this->load->view('backend/index', $page_data);
	}
	
	function join_virtual_class($param1 = '', $param2 = ''){    
	    $vc_platform_id = get_school_virtual_platform();
        if($vc_platform_id == "")
        {
          $vc_platform_id = 1;  
        }
	    
        $year        = date("Y");
        $month       = date("m");
        $day         = date("j");
        $parentId    = $_SESSION['user_login_id'];
        $studentName = $_SESSION['student_name'];
        $studentId   = $_SESSION['student_id'];
        
        $q           = 'select * from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id 
                        JOIN '.get_school_db().'.class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                        WHERE class_routine_settings.section_id = '.$param2.' And class_routine.class_routine_id = '.$param1
                        .' AND ((DATE_FORMAT(virtual_class.vc_start_time, "%Y") = '. $year .') AND (DATE_FORMAT(virtual_class.vc_start_time, "%c") = '. $month .' ) 
                        AND (DATE_FORMAT(virtual_class.vc_start_time, "%e") = '. $day .'))';
        $result     =  $this->db->query($q)->result_array();
       
       if( count($result) > 0 ){
            foreach($result as $r)
            {
               $meetingId    = $r['virtual_class_id'];
               $meetingName  = $r['virtual_class_name'];
               $rand_meeting = explode("-" , $meetingId);
            }
            
            if($vc_platform_id == 1)
            {
                /* big blue button configuration starts here */
             
                $getUrl = WEBRTC_LINK."api/getMeetingInfo?";
        		$params = '&meetingID='.urlencode($meetingId).
        		'&password='.urlencode('mp');
                $url_get = $getUrl.$params.'&checksum='.sha1("getMeetingInfo".$params.WEBRTC_SECRET);
                $xmldata = simplexml_load_file($url_get) or die("Failed to load");
                $returncode = $xmldata->returncode;
    
                if($returncode == 'SUCCESS'){
                    $maxUsers = $xmldata->maxUsers;
                    $participantCount = $xmldata->participantCount;
                    if($maxUsers == ($participantCount+1)){
                        $this->session->set_flashdata('club_updated', 'The number of participants allowed for this class has been reached.');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                    else{
                        $data['class_routine_id']        = $param1;
                        $data['virtual_class_name']      = $meetingName;
                        $data['virtual_class_id']        = $meetingId;
                        $data['student_id'] = $studentId;
                        $data['parent_id'] = $parentId;
                        $data['student_name'] = $studentName;
                        $t=time();
                        $current_time = date("Y-m-d H:i:s",$t);
                        $data['vc_start_time'] = $current_time;
                        
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
                
                /* big blue button configuration end here */
        
            }
            else if($vc_platform_id == 2)
            {
                
                $t                               = time();
                $current_time                    = date("Y-m-d H:i:s",$t);
                
                $data['class_routine_id']        = $param1;
                $data['virtual_class_name']      = $meetingName;
                $data['virtual_class_id']        = $meetingId;
                $data['student_id']              = $studentId;
                $data['parent_id']               = $parentId;
                $data['student_name']            = $studentName;
                $data['vc_start_time']           = $current_time;
                $data['virtual_class_join']      = "";
                $this->db->insert(get_school_db().'.virtual_class_student', $data);
                
                $page_data['teacher_name']       = $_SESSION['name'];
                $page_data['name']               = $meetingName; 
                $page_data['logout_url']         = base_url() . "virtualclass/class_end/".$rand_meeting[1];
                $page_data['audio']              = true;
                $page_data['video']              = true;
                $page_data['isStudent']          = true;
                
                $this->load->view('backend/jitsi' , $page_data);
                
            }
            else if($vc_platform_id == 3)
            {
            
                /* big blue button configuration starts here */
             
                $getUrl = ICWEBRTC_LINK."api/getMeetingInfo?";
        		$params = '&meetingID='.urlencode($meetingId).
        		'&password='.urlencode('mp');
                $url_get = $getUrl.$params.'&checksum='.sha1("getMeetingInfo".$params.ICWEBRTC_SECRET);
                $xmldata = simplexml_load_file($url_get) or die("Failed to load");
                $returncode = $xmldata->returncode;
    
                if($returncode == 'SUCCESS'){
                    $maxUsers = $xmldata->maxUsers;
                    $participantCount = $xmldata->participantCount;
                    if($maxUsers == ($participantCount+1)){
                        $this->session->set_flashdata('club_updated', 'The number of participants allowed for this class has been reached.');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                    else{
                        $data['class_routine_id']        = $param1;
                        $data['virtual_class_name']      = $meetingName;
                        $data['virtual_class_id']        = $meetingId;
                        $data['student_id']              = $studentId;
                        $data['parent_id']               = $parentId;
                        $data['student_name']            = $studentName;
                        $t                               = time();
                        $current_time                    = date("Y-m-d H:i:s",$t);
                        $data['vc_start_time']           = $current_time;
                        
                    	$joinUrl = ICWEBRTC_LINK."api/join?";
                		$params = '&meetingID='.urlencode($meetingId).
                		'&fullName='.urlencode($studentName).
                		'&password='.urlencode('pw').
                		'&userID='.urlencode($studentId).
                		'&joinViaHtml5=true'.
                		'&webVoiceConf='.urlencode('');
                        $url_join = $joinUrl.$params.'&checksum='.sha1("join".$params.ICWEBRTC_SECRET);
                        
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
                
                /* big blue button configuration end here */
        
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
			$page_data['personal_profile'] = true;
			$page_data['current_teacher_id'] = $param2;
		}
		$this->db->where('school_id',$_SESSION['school_id']);
		//$page_data['teachers'] = $this->db->get(get_school_db().'.teacher')->result_array();

		$sub_id = intval($this->input->post('subject_id'));
		$subject_filter = '';
		if( $sub_id >0 )
		{
			$subject_filter = " AND st.subject_id = $sub_id ";
			$page_data['sub_filter'] = $sub_id;
			$page_data['filter'] = true;
		}

		$student_id= $_SESSION['student_id'];
		$school_id=$_SESSION['school_id'];
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
	
	//_____________________Assignment Submission by student_______________
	//____________________________________________________________________
	function diary($param1 = '', $param2 = '')
	{
		$section_id   = $_SESSION['section_id'];
		$student_id   = $_SESSION['student_id'];
		//$this->db->order_by("due_date","desc");
		//$page_data['diary'] = $this->db->get_where(get_school_db().'.diary', array('class_id' => $class_id))->result_array();
		$sub_id = intval($this->input->post('subject_id'));
		$start_date=$this->input->post('starting');
		$end_date=$this->input->post('ending');
		$start_date=date_slash($this->input->post('starting'));
		$end_date=date_slash($this->input->post('ending'));
		$per_page = 10;
		$apply_filter = $this->input->post('apply_filter', TRUE);
		$std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'", "\""), "", $std_search));
        
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

	 $q = "select da.dairy_audio_id,da.audio,dr.diary_id as diary_id,dr.teacher_id as teacher_id,dr.subject_id as subject_id,dr.section_id as section_id,dr.assign_date
	 as assign_date,dr.due_date as due_date,dr.task as task,dr.title as title,dr.attachment as attachment,ds.*,
	 dr.school_id,d.title as department, c.name as class_name,cs.title as class_section
			FROM ".get_school_db().".diary dr
			INNER JOIN ".get_school_db().".diary_student ds
			ON ds.diary_id = dr.diary_id
			
			INNER JOIN ".get_school_db().".class_section cs
			ON cs.section_id = dr.section_id

			INNER JOIN ".get_school_db().".class c
			ON c.class_id = cs.class_id

			INNER JOIN ".get_school_db().".departments d
			ON d.departments_id = c.departments_id
			
			LEFT JOIN ".get_school_db().".diary_audio da 
			ON dr.diary_id = da.diary_id

			WHERE 

			((dr.section_id=$section_id) AND (ds.student_id=0 OR ds.student_id=$student_id OR ds.student_id='')) AND (dr.school_id=".$_SESSION['school_id'].")". $subject_filter . $date_query . $std_query. "ORDER BY dr.assign_date desc ";
        $diary_count = $this->db->query($q)->result_array();
        
        $total_records = count($diary_count);
        $quer_limit = $q . " limit " . $start_limit . ", " . $per_page . "";

        $diary = $this->db->query($quer_limit)->result_array();
        
        
        
		$page_data['diary'] =$diary;
		
		$sub_arr = $this->db->query(" select sub.* from ".get_school_db().".subject sub
    	                          inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
    	                          where ss.section_id = ".$_SESSION['section_id']."
     	                          and ss.school_id = ".$_SESSION['school_id']." ")->result_array();
        $page_data['subjects'] = $sub_arr; 
        
        $page_data['apply_filter'] = $apply_filter;
        
        $page_data['start_date']=$start_date;
        $page_data['end_date']=$end_date;
        $page_data['std_search']=$std_search;
		$page_data['page_name'] = 'diary';
		$page_data['page_title'] = get_phrase('daily_diary');
		
		 
		
		$this->load->view('backend/index', $page_data);
	}
	
	function solve_assignment($diary_id=0)
	{
	    $q = "select ds.* , d.* from  ".get_school_db().".diary_student ds
	    inner join ".get_school_db().".diary d on d.diary_id = ds.diary_id
	    where ds.diary_id = ".$diary_id." and ds.school_id = ".$_SESSION['school_id']." and ds.student_id = ".$_SESSION['student_id']." ";
	    $stud_diary = $this->db->query($q)->result_array();
	    $student_id =  $stud_diary[0]['student_id'];
	    // Is Viewed Status Update
	    $is_viewed_check = $stud_diary[0]['is_viewed'];
	    if($is_viewed_check == '0'){
    	    $this->db->where("student_id",$student_id)
    	             ->where("diary_id",$diary_id)
    	             ->update(get_school_db().".diary_student",array('is_viewed'=>'1'));
	    }
	    
	    $page_data['stud_diary'] = $stud_diary;
	    $page_data['page_name'] = 'solve_assignment';
		$page_data['diary_id'] = $diary_id;
		$page_data['page_title'] = get_phrase('solve_assig');
		$this->load->view('backend/index', $page_data);
	}
	
	function submit_assignment()
	{
	    $diary_id = $this->input->post('diary_id');
	    $editor = $this->input->post('editor'); 
	    $filename = $_FILES['image1']['name']; 
	    
        $folder_name = $_SESSION['folder_name'];
        $path = 'uploads/'.$folder_name.'/docs';
        // if ($folder_name == "") {
        //     $path = base_url() . 'uploads/' . $_SESSION['folder_name'];
        // } 
        // if ($is_root == 1) {
        //     $path = base_url() . 'uploads/' . $folder_name;
        // }
        
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    

        $qur = "select subject_id , section_id , teacher_id from ".get_school_db().".diary 
        where school_id = ".$_SESSION['school_id']." and diary_id = ".$diary_id." ";
        
        $diary_details = $this->db->query($qur)->result_array();

        $teacher_id      =   $diary_details[0]['teacher_id'];
        $subject_name    =   get_subject_name($diary_details[0]['subject_id']);
        $section_detils  =   section_hierarchy($diary_details[0]['section_id']);        
        $class_name      =   $section_detils[d]."-".$section_detils[c]."-".$section_detils[s];
        $student_details =   get_student_details($_SESSION['student_id']);        
        $diary_details   =   get_diary_title($diary_id);
        $school_id       =   $_SESSION['school_id'];
	    $student_id      =   $_SESSION['student_id'];
		

        $qur_diary_student_id = "select diary_student_id from ".get_school_db().".diary_student 
        where school_id = ".$_SESSION['school_id']." and diary_id = ".$diary_id." 
        and student_id = ".$student_id."  ";
        $qur_diary_student_id_result = $this->db->query($qur_diary_student_id)->row();
        
        $diary_student_id = $qur_diary_student_id_result->diary_student_id;
        
        
        $data_note_documents = array();
	

        if(count($_FILES["documents"]['name'])>0)
        { 
            for($j=0; $j < count($_FILES["documents"]['name']); $j++)
            { 
                 $file_name = $_FILES["documents"]['name']["$j"];
                 $temp      = $_FILES["documents"]['tmp_name']["$j"];
                 $ext       = pathinfo($file_name, PATHINFO_EXTENSION);
                 $date_file = date("Y-m-d");
                 if($file_name != ''){
                     
                    $new_file = $j .'_'.$student_details[0]['roll']."_".$student_details[0]['name']."_".$date_file."_".$class_name."_".$subject_name. '.' . $ext;
                    copy($temp, $path . '/' . $new_file);

 	
                    $data_doc = array(
                        'diary_student_id'   =>  $diary_student_id , 
                        'answer_attachment'  => $new_file
                    );
                    array_push($data_note_documents,$data_doc);                        

                    
                 }
            }

            if(!empty($data_note_documents)){
                $this->db->insert_batch(get_school_db().".diary_attachments" , $data_note_documents);
            }
            
        }
      
        
	    $data['is_submitted'] = 1; 
	    $data['submission_date']  = date("Y-m-d H:i:s");  
	    $data['submitted_by'] = $_SESSION['student_id'];
	    $data['answer_text'] = $editor;
	    
	    $school_id = $_SESSION['school_id'];
	    $student_id = $_SESSION['student_id'];
	    
	    $where = array('diary_id' => $diary_id ,'school_id' => $school_id  ,'student_id' => $student_id);
	    $this->db->where($where);
	    
        $this->db->update(get_school_db().'.diary_student',$data);
        
        
        
         //-----------Send Notification To Teacher-------------
        //-----------------------------------------------------
        $device_id      =   get_user_device_id(3 , $teacher_id , $_SESSION['school_id']);
        $title          =  "Diary Submitted";
        $message        =  "Diary Has Been Submitted By Student.";
        $link           =   base_url().'teacher/diary';
        sendNotificationByUserId($device_id, $title, $message, $link , $teacher_id , 3);
        
        
        $this->session->set_flashdata('solve_assign', get_phrase('assignment_submitted_successfully'));
		redirect(base_url() . 'student_p/diary');
	    
	}
	
	//____________________________________________________________________
	//____________________________________________________________________
	/****MANAGE EXAM MARKS*****/
	function marks($exam_id = '', $class_id = '', $subject_id = '')
	{
		
		$student_id = $_SESSION['student_id'];
		$section_id   = $_SESSION['section_id'];

		$page_data['student_id'] = $student_id;
		$page_data['section_id'] = $section_id;

        // 		if($this->input->post('operation') == 'selection')
        // 		{
        // 		    echo "dasfsaf";
        // 		    exit;
        // 			$page_data['exam_id'] = $this->input->post('exam_id');
        // 			//$page_data['class_id'] = $this->input->post('class_id');
        // 			//$page_data['subject_id'] = $this->input->post('subject_id');
        // 			//if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0) {
        // 			if($page_data['exam_id'] > 0)
        // 			{
        // 				redirect(base_url() . 'student_p/marks/' . $page_data['exam_id']);
        // 			}
        // 			else
        // 			{
        // 				$this->session->set_flashdata('mark_message', get_phrase('choose_ exam_,_class_and_subject'));
        // 				redirect(base_url() . 'student_p/marks/');
        // 			}
        // 		}
		$page_data['exam_id'] = $exam_id;
		$page_data['page_info'] = 'Exam marks';

		$page_data['page_name'] = 'marks';
		$page_data['page_title'] = get_phrase('examination_result');
		$this->load->view('backend/index', $page_data);
	}

	function get_exam_result()
	{
		$student_id=$this->input->post('student_id');	
		$exam_id=$this->input->post('exam_id');

		$q="select m.*,e.start_date, e.end_date, e.name as exam_name from ".get_school_db().".marks m 
			inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id 
			inner join ".get_school_db().".exam e on m.exam_id=e.exam_id 
			inner join ".get_school_db().".exam_routine er
			on e.exam_id=er.exam_id
			where 
			m.exam_id=".$exam_id." and 
			m.student_id=".$student_id." and 
			m.school_id=".$_SESSION['school_id']." 
			and er.is_approved=1
			group by m.subject_id";
		$result = $this->db->query($q)->result_array();
		
		$data['student_id']=$student_id;
		$data['exam_id']=$exam_id;
		$data['result']=$result;
		
		$this->load->view('backend/student/ajax/result', $data);
	}

	function get_exam_type(){
		$term_id=$this->input->post(yearly_term);
		echo exam_type_option_list($term_id);
	}
	
	function check_leave_dates()
	{
		$date = $this->input->post('date');
		if ($date != '')
		{
			$date = date('Y-m-d', strtotime($date));
				
			$q = $this->db->query("SELECT * FROM ".get_school_db().".leave_student 
				WHERE ('".$date."' BETWEEN start_date AND end_date)
				AND student_id=".$_SESSION['student_id']."
				AND school_id=".$_SESSION['school_id']."
				AND (status=0 OR status=1 )")->result_array();
			//echo $this->db->last_query();
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
		$q = "SELECT crs.* FROM ".get_school_db().".class_routine_settings crs
		WHERE 
		crs.school_id=".$_SESSION['school_id']."
		and crs.is_active = 1
		";


		$routine = $this->db->query($q)->result_array();
		$page_data['routine'] = $routine;
		$page_data['page_name'] = 'class_routine';
		$page_data['page_title'] = get_phrase('class_routine');
		$this->load->view('backend/index', $page_data);
	}


	/**********MANAGING EXAM ROUTINE******************/
	function exam_routine($param1 = '', $param2 = '', $param3 = '')
	{
		$student_id = $_SESSION['student_id'];
		$section_id   = $_SESSION['section_id'];
		$page_data['student_id'] = $student_id;
		$page_data['section_id'] = $section_id;
		$page_data['page_name'] = 'exam_routine';
		$page_data['page_title'] = get_phrase('examination_routine');
		$this->load->view('backend/index', $page_data);
	}

	
	/**********MANAGE Leaves********************/
	function manage_student_leaves(){
	    $school_id = $_SESSION['school_id'];
	    $std_id    = $_SESSION['student_id'];
	    $page_data['leaves'] = $this->db->query("select sl.* from  ".get_school_db().".leave_student sl where 
			                                     sl.student_id=$std_id and sl.school_id= $school_id 
			                                     order by sl.request_id desc ")->result_array();
		$page_data['page_name'] = 'leave_request';
		$page_data['page_title'] = get_phrase('leave_requests');
		$this->load->view('backend/index', $page_data);
	}

	/**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
	function transport($param1 = '', $param2 = '', $param3 = '')
	{
		
		$page_data['transports'] = $this->db->get(get_school_db().'.transport')->result_array();
		$page_data['page_name'] = 'transport';
		$page_data['page_title'] = get_phrase('manage_transport');
		$this->load->view('backend/index', $page_data);

	}
	/**********MANAGE DORMITORY / HOSTELS / ROOMS ********************/
	function dormitory($param1 = '', $param2 = '', $param3 = '')
	{
		

		$page_data['dormitories'] = $this->db->get(get_school_db().'.dormitory')->result_array();
		$page_data['page_name'] = 'dormitory';
		$page_data['page_title'] = get_phrase('manage_dormitory');
		$this->load->view('backend/index', $page_data);

	}

	/**********WATCH NOTICEBOARD AND EVENT ********************/
	function noticeboard($param1 = '', $param2 = '', $param3 = '')
	{
		$school_id = $_SESSION['school_id'];
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
        
        $per_page = 10;
        		$apply_filter = $this->input->post('apply_filter', TRUE);
        		$std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'", "\""), "", $std_search));
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
        	$date_query=" AND create_timestamp >= '".$start_date."' AND create_timestamp <= '".$end_date."' ";
        	$page_data['start_date']=$start_date;
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
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
		
		$q="select n.notice_id as notice_id,
		n.notice_title as notice_title,n.notice as notice,
		n.create_timestamp as create_timestamp
		FROM ".get_school_db().".noticeboard n
		WHERE n.school_id=".$school_id. $date_query.$std_query."
		";
		$notice_count = $this->db->query($q)->result_array();   	$total_records = count($notice_count);
        $quer_limit = $q . " limit " . $start_limit . ", " . $per_page . "";
        $notice = $this->db->query($quer_limit)->result_array();
        //echo $this->db->last_query();
		
		$page_data['notices'] =$notice;
		$config['base_url'] = base_url() . "student_p/noticeboard/" . $start_date . "/". $end_date . "/". $std_search;
		$config['total_rows'] = $total_records;
        $config['per_page'] = $per_page;

        $config['uri_segment'] = 6;
        $config['num_links'] = 2;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        $page_data['start_limit'] = $start_limit;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['total_records'] = $total_records;
        $page_data['pagination'] = $pagination;
        $page_data['start_date']=$start_date;
        $page_data['end_date']=$end_date;
        $page_data['std_search']=$std_search;
		$page_data['page_name'] = 'noticeboard';
		$page_data['page_title'] = get_phrase('noticeboard');
		$this->load->view('backend/index', $page_data);

	}
	/***MANAGE CIRCULARS, WILL BE SEEN BY Selected Corresponding DASHBOARDS**/
	function circulars($param1 = '', $param2 = '', $param3 = '')
	{
		$student_id = $_SESSION['student_id'];
		$section_id   = $_SESSION['section_id'];
		$school_id  = $_SESSION['school_id'];

		/*$q="select cl.circular_id as circular_id,cl.circular_title as circular_title,cl.circular as circular,cl.section_id as section_id,cl.student_id as student_id,cl.create_timestamp as create_timestamp,cl.attachment as attachment, y.academic_year_id as academic_year_id,y.title as terms_title,y.status as term_status,
			a.title as yearly_title ,d.title as department, c.name as class_name,
			cs.title as class_section
			FROM ".get_school_db().".circular cl
			INNER JOIN ".get_school_db().".yearly_terms y
			ON cl.yearly_terms_id = y.yearly_terms_id
			INNER JOIN ".get_school_db().".acadmic_year a
			ON a.academic_year_id = y.academic_year_id

			INNER JOIN ".get_school_db().".class_section cs
			ON cs.section_id = cl.section_id

			INNER JOIN ".get_school_db().".class c
			ON c.class_id = cs.class_id

			INNER JOIN ".get_school_db().".departments d
			ON d.departments_id = c.departments_id

		 	WHERE 
			((cl.student_id='' OR cl.student_id=0 OR cl.student_id=$student_id ) AND  cl.section_id=$section_id) AND
		 	cl.school_id=".$_SESSION['school_id']." 
		 	AND a.academic_year_id=".$_SESSION['academic_year_id']."
		 	ORDER BY a.status desc, y.status desc, cl.create_timestamp desc ";*/
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
	$date_query=" AND create_timestamp >= '".$start_date."'";
	$page_data['start_date']=$start_date;
	$page_data['filter'] = true;
}
if($end_date!='')
{
	$date_query=" AND create_timestamp <= '".$end_date."'";
	$page_data['end_date']=$end_date;
	$page_data['filter'] = true;
}
if($start_date!='' && $end_date!='')
{
	$date_query=" AND create_timestamp >= '".$start_date."' AND create_timestamp <= '".$end_date."' ";
	$page_data['start_date']=$start_date;
	$page_data['end_date']=$end_date;
	$page_data['filter'] = true;
}
		
		$q="select cl.circular_id as circular_id,cl.circular_title as circular_title,cl.circular as circular,cl.section_id as section_id,cl.student_id as student_id,cl.create_timestamp as create_timestamp,cl.attachment as attachment,d.title as department, c.name as class_name,cs.title as class_section
			FROM ".get_school_db().".circular cl
			
			INNER JOIN ".get_school_db().".class_section cs
			ON cs.section_id = cl.section_id

			INNER JOIN ".get_school_db().".class c
			ON c.class_id = cs.class_id

			INNER JOIN ".get_school_db().".departments d
			ON d.departments_id = c.departments_id

		 	WHERE 
			((cl.student_id='' OR cl.student_id=0 OR cl.student_id=$student_id ) AND  cl.section_id=$section_id) AND
		 	cl.school_id=".$_SESSION['school_id']. $date_query. " 
		 	ORDER BY  cl.create_timestamp desc ";

		$page_data['circulars'] = $this->db->query($q)->result_array();
		
		/*
		$page_data['circulars'] = $this->db->query("select * from ".get_school_db().".circular where (student_id=$student_id or section_id=$section_id) AND school_id=$school_id")->result_array();
		*/

		//echo $this->db->last_query();
		$page_data['page_name'] = 'circulars';
		$page_data['page_title'] = get_phrase('circulars');
		$this->load->view('backend/index', $page_data);
	}

	/**********MANAGE DOCUMENT / home work FOR A SPECIFIC CLASS or ALL*******************/
	function document($do = '', $document_id = '')
	{
		$page_data['page_name'] = 'manage_document';
		$page_data['page_title'] = get_phrase('manage_documents');
		$page_data['documents'] = $this->db->get(get_school_db().'.document')->result_array();
		$this->load->view('backend/index', $page_data);
	}


	function manage_attendance($date = '',$month = '',$year = '',$class_id = '')
	{
		$page_data['date'] = $date;
		$page_data['month'] = $month;
		$page_data['year'] = $year;
		$page_data['class_id'] = $class_id;
		$page_data['page_name'] = 'manage_attendance';
		$page_data['page_title'] = get_phrase('daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	
	function view_subjectwise_attendance($date = '',$month = '',$year = '',$class_id = '')
    {
        $student_id = $_SESSION['student_id'];

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

	function message()
	{
		$student_id = $_SESSION['student_id'];
		$school_id  = $_SESSION['school_id'];
		$teachers_id= $this->uri->segment(3);
		$subject_id = $this->uri->segment(4);
		$page_data['page_name'] = 'message';
		$page_data['page_title'] = get_phrase('message');

		$this->db->query("update ".get_school_db().".messages set is_viewed=1 WHERE teacher_id=$teachers_id and student_id=$student_id and subject_id=$subject_id and school_id=$school_id and messages_type=0");

		//echo $this->db->last_query();
		
		$qr    = "select m.* from ".get_school_db().".messages m
			inner join ".get_school_db().".student s on s.student_id=m.student_id
			where m.teacher_id=$teachers_id and m.student_id=$student_id and m.subject_id=$subject_id and s.section_id=".$_SESSION['section_id']." and m.school_id=$school_id 
			and 
			s.academic_year_id = ".$_SESSION['academic_year_id']."
			ORDER BY m.message_time desc
			";
		$query = $this->db->query($qr);

		$page_data['parent_message_id'] = 0;
		$page_data['previous_message_id'] = 0;
		$c = 0;
		if($query->num_rows > 0)
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
		//print_r($page_data['rows']);
		
		$page_data['subject_id'] =$subject_id ;
		$page_data['teachers_id'] =$teachers_id ;

		$this->load->view('backend/index', $page_data);
	}

	function message_send($teacher_id = "", $subject_id = "")
	{
		$data['messages'] = $this->input->post('message');
		$data['subject_id'] = $subject_id;
		$data['is_viewed'] = 0;
		$student_name = $_SESSION['student_name'];

		$msg          = $data['messages'];
		$data['previous_message_id'] = $this->input->post('previous_message_id');
		$data['parent_message_id'] = $this->input->post('parent_message_id');
		$student_id = $_SESSION['student_id'];
		$data['teacher_id'] = $teacher_id;
		$data['student_id'] = $student_id;
		$data['school_id'] = $_SESSION['school_id'];
		$data['messages_type'] = 1;//parent
		$data['sent_by'] = intval($_SESSION['login_detail_id']);
		$this->db->insert(get_school_db().'.messages',$data);
		//$to = $this->input->post('to');
		$to      = "sheraz.gminns@gmail.com";
		// $from = $_SESSION['email'];
		$from    = "GSIMS";
		$subject = "New Message";
		$message = "
		Dear teacher,<br/><br/>
		You have a new message from  $student_name's parents<br />
		<br/>
		Message:
		<br/>
		$msg
		<br/>
		<br />
		Please <a href='".base_url() . 'parents/message/'.$teaher_id."'>click here</a> to read the message.
		<br />
		<br />
		Regards,
		<br/>
		<br/>
		GSIMS
		<br />
		GMINNS IT Business Solution
		<br>
		<a href='http://www.gminns.com' target='_blank'>http://www.gminns.com</a>
		";
		$this->send_mail($to,$subject,$from,$message);

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
			// error and sending mail
			$currentdate;
			// echo 'mail is send';
		}
	}
	
	function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
    	if( get_login_type_name($_SESSION['login_type']) != 'student')
			redirect(base_url() . 'login');
        
        if ($param1 == 'update_profile_info') {
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');

            $this->db->where('school_id',$_SESSION['school_id']);            
            //$this->db->where('admin_id',$_SESSION['admin_id']);
            $this->db->where('admin_id',$_SESSION['user_login_id']);
            $this->db->update(get_school_db().'.admin', $data);
            $this->session->set_flashdata('flash_message', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'Student_p/manage_profile/');
        }
        
        if ($param1 == 'change_password') {
            
            $data['password']= passwordHash($this->input->post('password'));
            $data['new_password']= $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');

            $current_password = $this->db->get_where(get_system_db().'.user_login', array(
			'user_login_id' => $_SESSION['user_login_id']
			))->row()->password;
            
            
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                
                $updated_data['password'] = passwordHash($data['new_password']);
                $updated_data['is_password_updated'] = 1;
                
                $this->db->where('user_login_id',$_SESSION['user_login_id']);
                $this->db->update(get_system_db().'.user_login', $updated_data);
                $this->session->set_flashdata('flash_message', get_phrase('password_updated_successfully'));

            } else {
                $this->session->set_flashdata('club_message', get_phrase('password_mismatch'));
            } 
                 
            redirect(base_url().'Student_p/manage_profile/');
        }
        
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
       
        $page_data['edit_data']  = $this->db->get_where(get_system_db().'.user_login', array(
            'user_login_id' => $_SESSION['user_login_id']
        ))->result_array();
        
        $this->load->view('backend/index', $page_data);
    }
	
	function my_profile()
    {
    	$user_login_id=$_SESSION['user_login_id'];
    	$data['name']=$this->input->post('display_name');
    	$image_file= $this->input->post('image_file');
    	$user_file= $_FILES['userfile']['name'];
        if($user_file!="")
        {
            if($image_file!="")
            {
                $del_location="uploads/profile_pic/$image_file";
                file_delete($del_location);
            }
            
            $data['profile_pic'] = file_upload_fun('userfile','profile_pic','profile',1);
        } 
       
     	$this->db->where('user_login_id', $user_login_id);
    	$this->db->update(get_system_db().'.user_login', $data);
    	$this->session->set_flashdata('flash_message', get_phrase('record_updated_successfully'));
        redirect(base_url() . 'student_p/manage_profile/');   
    }

function invoice()
{
	$year_arr = $this->get_academic_year_dates($_SESSION['academic_year_id']);	
		$page_data['invoices'] = $this->db->query(
			"select scf.* 
			from ".get_school_db().".student_chalan_form scf
			inner join ".get_school_db().".class_chalan_form ccf 
			on ccf.c_c_f_id=scf.c_c_f_id 
			and ccf.section_id =".$_SESSION['section_id']."
			and ccf.type = 2
			where scf.student_id= ".$_SESSION['student_id']." 
			and scf.school_id=".$_SESSION['school_id']."
			and scf.status IN (4,5)
			and 
			(
				DATE(scf.issue_date)
				between 
				'".$year_arr[0]['start_date']."' 
				and 
				'".$year_arr[0]['end_date']."'
			)
			")->result_array();
		//echo $this->db->last_query();
		$page_data['page_name'] = 'invoice';
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
		//$page_data['data'] = $res_array;
		$page_data['page_name'] = 'policies_listing';
		$page_data['page_title'] = get_phrase('policies');
		$this->load->view('backend/index', $page_data);
	
	}

	function subject_recording()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
    
        $sdate       = $this->input->post('start_date_recording');
        $edate       = $this->input->post('end_date_recording');
        $subject_id = $this->input->post('student_subject_id', TRUE);
        $section_id   = $_SESSION['section_id'];
        $student_id   = $_SESSION['student_id'];
        $page_data['subject_id'] = $subject_id;
        $page_data['section_id'] = $section_id;
        $page_data['student_id'] = $student_id;
        $page_data['sdate']       = $sdate;
        $page_data['edate']       = $edate;
        
        $page_data['page_name'] = 'subjectwise_recording';
        $page_data['page_title'] = get_phrase('subjectwise_recording');
        
        $this->load->view('backend/index', $page_data);
    }
    function getsubdiary()
    { 
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
        // Diary Config by teacher select Direct diary assign to student 
        // $qur = "select * from ".get_school_db().".diary where subject_id = ".$sub_id." 
        // and section_id = ".$_SESSION['section_id']." and school_id = ".$_SESSION['school_id']." $where $academic_year_filter order by diary_id desc"; 
        // $arr = $this->db->query($qur)->result_array();
        // Correct version
         $qur = "SELECT * FROM ".get_school_db().".diary LEFT JOIN ".get_school_db().".diary_student on diary_student.diary_id = diary.diary_id
                WHERE diary.section_id = ".$_SESSION['section_id']."
                AND diary.subject_id = ".$sub_id."
                AND diary.school_id = ".$_SESSION['school_id']."
                And diary_student.student_id = ".$_SESSION['student_id']."
                $where $academic_year_filter
                order by diary.diary_id DESC";
             $arr = $this->db->query($qur)->result_array();

        if(count($arr) > 0)
        {
            foreach($arr as $row)
            {
                $data .= '<li onclick="getdiarycontent('.$row['diary_id'].')"> <span><i class="far fa-file"></i> '.$row['title'].' - ('.date_view($row['assign_date']).') </span> </li>';
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
        
        if($diary_arr->num_rows() > 0) {
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
    
    function books()
    {
        if($this->input->post("section_id") != "")
        {
            $sec_id = $this->input->post("section_id");
            $page_data['section_id'] = $sec_id;
            $book_query = $this->db->query("SELECT * FROM ".get_school_db().".books WHERE section_id = '$sec_id'")->result_array();
        }else{
            $book_query = $this->db->query("SELECT * FROM ".get_school_db().".books WHERE status = '1'")->result_array();    
        }
        
        $page_data['books'] = $book_query;
		$page_data['page_name']  = 'library/book';
        $page_data['page_title'] = get_phrase('book');
        $this->load->view('backend/index', $page_data);
    }
    
    function book_reserve()
    {
        $data = array(
            'book_id'                =>  $this->input->post('book_id'),
            'user_login_detail_id'   =>  $_SESSION['user_login_id'],
            'book_collect_date'      =>  $this->input->post('book_collect_date')
        );
        
        $book_query = $this->db->insert(get_school_db().".book_reserve_request",$data);
        if($book_query)
        {
            $this->session->set_flashdata('flash_message', get_phrase('book_reserve_request_send_successfully'));
            redirect(base_url() . 'student_p/books/');   
        }
    }
    
    
   
    
    
}
