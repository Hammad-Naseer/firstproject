<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    //session_start();
    
class Activitylog extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('common');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	
	function filter_view()
    {
	    $data['page_name']		= 'activity_logs/activity_logs';
		$data['page_title']		=  get_phrase('activity_logs');
        $this->load->view('backend/index', $data); 
	}
    
    function filter()
    {
	    
	    $teacher_id            = $this->input->post('teacher_id');
	    $activity_id           = $this->input->post('activity_id');
	    $start_date            = $this->input->post('start_date');
	    $end_date              = $this->input->post('end_date');
	    $school_id             = $_SESSION['school_id'];
	    
	    $condition             = "";
	    
	    /*
    	    "1" => 'attendance', --Done
            "2" => 'diary', --Done
            "3" => 'leave_request', --Done
            "4" => 'online_assessment', --Done
            "5" => 'lecture_notes_sharing', --Done
            "6" => 'virtual_class' --Done
	    */
	    
	    if($activity_id == 1){
	        
	        $actual_teacher_id =  get_user_id_by_teacher_id($teacher_id);
	        
	        $condition .= "att.school_id = $school_id AND att.user_id = $actual_teacher_id";
	        if($start_date != '1970-01-01' && $start_date != ''){
                 $condition .= " AND att.date >= '$start_date' ";
            }
            if($end_date != '1970-01-01' && $end_date != ''){
                $condition .= " AND att.date <= '$end_date' ";
            }
            
            $query="SELECT count(att.attendance_id) as total , att.date FROM ".get_school_db().".attendance att
                    WHERE $condition GROUP BY att.date";
            $result=$this->db->query($query)->result_array(); 
	        
	        
	    }
	    else if($activity_id == 2){
	        
	        $condition .= "d.school_id = $school_id AND d.teacher_id = $teacher_id";
	        if($start_date != '1970-01-01' && $start_date != ''){
                 $condition .= " AND d.assign_date >= '$start_date' ";
            }
            if($end_date != '1970-01-01' && $end_date != ''){
                $condition .= " AND d.assign_date <= '$end_date' ";
            }
	        
	        $query="SELECT d.task as diary_task , d.title as diary_title , c.name as class , dep.title as department,  cs.title section , d.assign_date FROM ".get_school_db().".diary d 
                    INNER join ".get_school_db().".class_section cs on d.section_id = cs.section_id
                    INNER join ".get_school_db().".class c on c.class_id = cs.class_id
                    INNER join ".get_school_db().".departments dep on c.departments_id = dep.departments_id
                    WHERE $condition ";
            $result=$this->db->query($query)->result_array();       
                   
	    }
	    else if($activity_id == 3){
	        
	        
	        $condition .= "ls.school_id = $school_id AND ls.staff_id = $teacher_id";
	        if($start_date != '1970-01-01' && $start_date != ''){
                 $condition .= " AND ls.request_date >= '$start_date' ";
            }
            if($end_date != '1970-01-01' && $end_date != ''){
                $condition .= " AND ls.request_date <= '$end_date' ";
            }
            
            $query="SELECT ls.request_date , ls.start_date , ls.end_date , ls.reason FROM ".get_school_db().".leave_staff ls
                    WHERE $condition";
            $result=$this->db->query($query)->result_array();
	        
	    }
	    else if($activity_id == 4){
	       
	        $condition .= "assm.school_id = $school_id AND assm.teacher_id = $teacher_id";
	        if($start_date != '1970-01-01' && $start_date != ''){
                 $condition .= " AND DATE_FORMAT(assm.inserted_at,'%Y-%m-%d') >= '$start_date' ";
            }
            if($end_date != '1970-01-01' && $end_date != ''){
                $condition .= " AND DATE_FORMAT(assm.inserted_at,'%Y-%m-%d') <= '$end_date' ";
            }
	       
	        $query="SELECT assm.assessment_id , assm.assessment_title , assm.is_assigned , DATE_FORMAT(assm.inserted_at,'%Y-%m-%d') AS inserted_at FROM  ".get_school_db().".assessments assm WHERE $condition"; 
            $result=$this->db->query($query)->result_array(); 
	        
	    }
	    else if($activity_id == 5){
	      
	        $condition .= "ln.school_id = $school_id AND ln.teacher_id = $teacher_id";
	        if($start_date != '1970-01-01' && $start_date != ''){
                 $condition .= " AND DATE_FORMAT(ln.inserted_at,'%Y-%m-%d') >= '$start_date' ";
            }
            if($end_date != '1970-01-01' && $end_date != ''){
                $condition .= " AND DATE_FORMAT(ln.inserted_at,'%Y-%m-%d') <= '$end_date' ";
            }
	       
	        $query="SELECT ln.notes_id , ln.notes_title , ln.is_assigned , DATE_FORMAT(ln.inserted_at,'%Y-%m-%d') AS inserted_at FROM  ".get_school_db().".lecture_notes ln WHERE $condition"; 
            $result=$this->db->query($query)->result_array();  
	        
	    }
	    else if($activity_id == 6){
	        
	        $condition .= "cr.school_id = $school_id AND st.teacher_id = $teacher_id";
	        if($start_date != '1970-01-01' && $start_date != ''){
                 $condition .= " AND vc.Current_Days >= '$start_date' ";
            }
            if($end_date != '1970-01-01' && $end_date != ''){
                $condition .= " AND vc.Current_Days <= '$end_date' ";
            }
	       
            $query="SELECT vc.virtual_class_name , vc.Current_Days as inserted_at, c.name as class , dep.title as department,  cs.title section FROM ".get_school_db().".virtual_class vc 
                    INNER join ".get_school_db().".class_routine cr on cr.class_routine_id = vc.class_routine_id
                    INNER join ".get_school_db().".subject_teacher st on st.subject_id = cr.subject_id
                    INNER join ".get_school_db().".class_routine_settings crs on crs.c_rout_sett_id = cr.c_rout_sett_id
                    INNER join ".get_school_db().".class_section cs on crs.section_id = cs.section_id
                    INNER join ".get_school_db().".class c on c.class_id = cs.class_id
                    INNER join ".get_school_db().".departments dep on c.departments_id = dep.departments_id
                    WHERE $condition ";
            $result=$this->db->query($query)->result_array();
	        
	    }
	    
        
        $data['apply_filter']  = $this->input->post('apply_filter');
	    $data['teacher_id']    = $teacher_id;
	    $data['activity_id']   = $activity_id;
	    $data['start_date']    = $start_date;
	    $data['end_date']      = $end_date;
	    $data['result']        = $result;
	    
	    $data['page_name']		= 'activity_logs/activity_logs';
		$data['page_title']		=  get_phrase('activity_logs');

        $this->load->view('backend/index', $data); 
	    
	}
	
    function teacher_activity_progress_report()
    {
	    $data['page_name']		= 'activity_logs/teacher_activity_progress_report';
		$data['page_title']		=  get_phrase('teacher_activity_progress_report');
        $this->load->view('backend/index', $data); 
	}
	
	function progress_report_filter()
	{
	    $teacher_id    =   $this->input->post('teacher_id');
	    $start_date    =   $this->input->post('start_date');
	    $end_date      =   $this->input->post('end_date');
	    $school_id     =   $_SESSION['school_id'];
	    
	    /*
           "1" => 'diary',                  --Done
           "2" => 'online_assessment',      --Done
           "3" => 'lecture_notes_sharing',  --Done
           "4" => 'virtual_class',          --Done
	    */
	    
	   
	   // <!---- DIARY --->
        $condition1 = "d.school_id = $school_id AND d.teacher_id = $teacher_id";
        if($start_date != '1970-01-01' && $start_date != ''){
             $condition1 .= " AND d.assign_date >= '$start_date' ";
        }
        if($end_date != '1970-01-01' && $end_date != ''){
            $condition1 .= " AND d.assign_date <= '$end_date' ";
        }
        
        $d_query = "SELECT d.task as diary_task , d.title as diary_title , c.name as class , dep.title as department,  cs.title section , d.assign_date FROM ".get_school_db().".diary d 
                INNER join ".get_school_db().".class_section cs on d.section_id = cs.section_id
                INNER join ".get_school_db().".class c on c.class_id = cs.class_id
                INNER join ".get_school_db().".departments dep on c.departments_id = dep.departments_id
                WHERE $condition1 ";
        $diary = $this->db->query($d_query)->result_array();       
       
       
       // <!---- ASSESSMENT --->        
        $condition2 = "assm.school_id = $school_id AND assm.teacher_id = $teacher_id";
        if($start_date != '1970-01-01' && $start_date != ''){
             $condition2 .= " AND DATE_FORMAT(assm.inserted_at,'%Y-%m-%d') >= '$start_date' ";
        }
        if($end_date != '1970-01-01' && $end_date != ''){
            $condition2 .= " AND DATE_FORMAT(assm.inserted_at,'%Y-%m-%d') <= '$end_date' ";
        }
       
        $ass_query = "SELECT assm.assessment_id , assm.assessment_title , assm.is_assigned , DATE_FORMAT(assm.inserted_at,'%Y-%m-%d') AS inserted_at FROM  ".get_school_db().".assessments assm WHERE $condition2"; 
        $assessment = $this->db->query($ass_query)->result_array();
    
        
        // <!---- LECTURE NOTES --->
        $condition3 = "ln.school_id = $school_id AND ln.teacher_id = $teacher_id";
        if($start_date != '1970-01-01' && $start_date != ''){
             $condition3 .= " AND DATE_FORMAT(ln.inserted_at,'%Y-%m-%d') >= '$start_date' ";
        }
        if($end_date != '1970-01-01' && $end_date != ''){
            $condition3 .= " AND DATE_FORMAT(ln.inserted_at,'%Y-%m-%d') <= '$end_date' ";
        }
       
        $ln_query = "SELECT ln.notes_id , ln.notes_title , ln.is_assigned , DATE_FORMAT(ln.inserted_at,'%Y-%m-%d') AS inserted_at FROM  ".get_school_db().".lecture_notes ln WHERE $condition3"; 
        $notes = $this->db->query($ln_query)->result_array();  
   
        $condition4 = "cr.school_id = $school_id AND st.teacher_id = $teacher_id";
        if($start_date != '1970-01-01' && $start_date != ''){
             $condition4 .= " AND vc.Current_Days >= '$start_date' ";
        }
        if($end_date != '1970-01-01' && $end_date != ''){
            $condition4 .= " AND vc.Current_Days <= '$end_date' ";
        }
       
        $vc_query = "SELECT vc.virtual_class_name , vc.Current_Days as inserted_at, c.name as class , dep.title as department,  cs.title section FROM ".get_school_db().".virtual_class vc 
                     INNER join ".get_school_db().".class_routine cr on cr.class_routine_id = vc.class_routine_id
                     INNER join ".get_school_db().".subject_teacher st on st.subject_id = cr.subject_id
                     INNER join ".get_school_db().".class_routine_settings crs on crs.c_rout_sett_id = cr.c_rout_sett_id
                     INNER join ".get_school_db().".class_section cs on crs.section_id = cs.section_id
                     INNER join ".get_school_db().".class c on c.class_id = cs.class_id
                     INNER join ".get_school_db().".departments dep on c.departments_id = dep.departments_id
                     WHERE $condition4 ";
        $vc       =  $this->db->query($vc_query)->result_array();
        
        
        
	    $condition_att  = "att.school_id = $school_id AND att.staff_id = $teacher_id";
        if($start_date != '1970-01-01' && $start_date != ''){
             $condition_att .= " AND att.date >= '$start_date' ";
        }
        if($end_date != '1970-01-01' && $end_date != ''){
            $condition_att .= " AND att.date <= '$end_date' ";
        }
            
        $query_att  = "SELECT count(att.attend_staff_id) as total_attandances , 
                       (SELECT count(att.attend_staff_id) from ".get_school_db().".attendance_staff att where $condition_att AND att.status = 1) as total_present , 
                       (SELECT count(att.attend_staff_id) from ".get_school_db().".attendance_staff att where $condition_att AND att.status = 2) as total_absents , 
                       (SELECT count(att.attend_staff_id) from ".get_school_db().".attendance_staff att where $condition_att AND att.status = 3) as total_leaves
                       FROM ".get_school_db().".attendance_staff att WHERE $condition_att";
        $result_att = $this->db->query($query_att)->row(); 
        
	    
        $data['apply_filter']  = $this->input->post('apply_filter');
	    $data['teacher_id']    = $teacher_id;
	    $data['start_date']    = $start_date;
	    $data['end_date']      = $end_date;
	    $data['attandance']    = $result_att;
	    $data['diary']         = $diary;
	    $data['assessment']    = $assessment;
	    $data['notes']         = $notes;
	    $data['vc']            = $vc;
	    $data['page_name']	   = 'activity_logs/teacher_activity_progress_report';
		$data['page_title']	   = get_phrase('teacher_activity_progress_report');
		
        $this->load->view('backend/index', $data); 
	}
	
	function check_if_class_teacher(){
	   
	    $teacher_id = get_user_id_by_teacher_id($this->input->post('teacher_id'));

	    $query = "select cs.section_id, cs.title as section, c.name as class, d.title as department from ".get_school_db().".class_section cs 
	              inner join ".get_school_db().".staff staff on staff.staff_id = cs.teacher_id inner join ".get_school_db().".class c on 
	              c.class_id = cs.class_id inner join ".get_school_db().".departments d on d.departments_id = c.departments_id 
	              where staff.user_login_detail_id = $teacher_id and cs.school_id = ".$_SESSION['school_id']." ";
                   
        $result = $this->db->query($query)->result_array();
        
        $check = count($result);
        echo json_encode($check);
	    
	    
	}
	
	function academic_acknowledge_report()
    {
	    $data['page_name']		= 'activity_logs/academic_acknowledge_report';
		$data['page_title']		=  get_phrase('academic_acknowledge_report');
        $this->load->view('backend/index', $data); 
	}
	
	function get_subjects_for_teacher()
    {
        $teacher_id = $this->input->post("teach_id");
	    $subject_data = get_teacher_subjects($teacher_id);
	    $output = '';
	    
	    $output .= '<option value="">Select Subject</option>';
	    foreach($subject_data as $data){
	        $output .= '<option value="' .$data['subject_id']. '"> ' .$data['subject']. ' </option>';
	    }
	    echo $output;
	}
	
	function filter_academic_acknowledge_report()
	{
	    $teacher_id    =   $this->input->post('teacher_id');
	    $subject_id    =   $this->input->post('subject_id');
	    $start_date    =   $this->input->post('start_date');
	    $end_date      =   $this->input->post('end_date');
	    $school_id     =   $_SESSION['school_id'];
	    
	    $where = " where ap.school_id = $school_id AND ap.subject_id = $subject_id";
        if($start_date != '1970-01-01' && $start_date != ''){
             $where .= " AND ap.start >= '$start_date' ";
        }
        if($end_date != '1970-01-01' && $end_date != ''){
            $where .= " AND ap.start <= '$end_date' ";
        }
	    
	    $query  = "select ap.title , ap.start , tpa.status , tpa.inserted_at from ".get_school_db().".academic_planner ap 
	               left join ".get_school_db().".teacher_planner_activity tpa on tpa.planner_id = ap.planner_id $where";
        $result = $this->db->query($query)->result_array();
	    
	    $data['apply_filter']  = $this->input->post('apply_filter');
	    $data['teacher_id']    = $teacher_id;
	    $data['subject_id']    = $subject_id;
	    $data['start_date']    = $start_date;
	    $data['end_date']      = $end_date;
	    $data['result']        = $result;
	    $data['page_name']	   = 'activity_logs/academic_acknowledge_report';
		$data['page_title']	   =  get_phrase('academic_acknowledge_report');
        $this->load->view('backend/index', $data);
	}
	
	function check_activity($status)
	{
	    check_activity($status);
	}
	
	
}
?>