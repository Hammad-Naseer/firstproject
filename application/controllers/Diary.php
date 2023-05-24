<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Diary extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
    }
    
    public function index()
    {
        if ($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    /** Student Diary**/
	function diarys($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($_SESSION['user_login']!= 1)
            redirect(base_url());
        if ($param1 == 'create') {
            
        	$subject_id=$this->input->post('subject_id');      	
        	$list=explode("-",$subject_id);
        	$subj_id=$list[0];
        	$teach_id=$list[1];
        	
            $data['teacher_id']=$teach_id;
            $data['subject_id']= $subj_id;
            
            $data['section_id']=$this->input->post('section_id');
            $data['task']= $this->input->post('task');
            $data['title'] = $this->input->post('title');
            
            $c_time_display =$this->input->post('due_date');
            $date_due=explode('/',$c_time_display);
            $data['due_date'] = $c_time_display;

            $create_timestamp= $this->input->post('assign_date');
            $assign_date=explode('/',$create_timestamp);
            $data['assign_date']= $create_timestamp;
            $data['school_id']= $_SESSION['school_id'];	
            $filename=$_FILES['image1']['name'];
            $folder_name = $_SESSION['folder_name'];
            if($filename!=""){
                $data['attachment']=file_upload_fun('image1','diary','');
            }
            $this->db->insert(get_school_db().'.diary', $data);
            $last_id=$this->db->insert_id();

            $this->session->set_flashdata('club_updated', get_phrase('record_saved_scuccessfully'));          
            
            $planner_check=array();
            $planner_check= $this->input->post('planner_check');
            if(isset($planner_check) && count($planner_check) > 0)
            {
                foreach($planner_check as $planner)
                {
                    $data2['diary_id']=$last_id;
                    $data2['planner_id']=$planner;
                    $data2['school_id']=$_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.academic_planner_diary',$data2);
                    $this->session->set_flashdata('club_updated', get_phrase('record_saved_scuccessfully'));
                }
            }
			
            $last_id;
            $p="SELECT section_id,diary_id,subject_id FROM ".get_school_db().".diary WHERE diary_id=".$last_id." AND school_id=".$_SESSION['school_id']."";
            $res=$this->db->query($p)->result_array();
            $list_array=array();
            $list_array['section_id']=$res[0]['section_id'];
            $list_array['diary_id']=$res[0]['diary_id'];
            $list_array['subject_id']=$res[0]['subject_id'];
            echo json_encode($list_array);
            return;
        }
        if ($param1 == 'do_update') {
        	$section_id=$this->uri->segment('5');
        	
        	if(($this->input->post('is_submitted1')) == 0)
        	{
			    $data['is_submitted']=0;
			}
			elseif(($this->input->post('is_submitted1')) > 0)
			{
        		$data['is_submitted']=1;
        		$data['submitted_by']=$_SESSION['login_detail_id'];
        		$data['submission_date']=date('Y-m-d H:i:s');
		
                $this->load->helper('message');
          
            }
            
            $subject_id=$this->input->post('subject_id1');
            $list=explode("-",$subject_id);
            $subj_id=$list[0];
            $teach_id=$list[1];
            $data['teacher_id'] = $teach_id;
            $data['subject_id'] = $subj_id;
            $data['section_id'] = $section_id;
            $data['task'] 		= $this->input->post('task1');
            $data['title'] =  $this->input->post('title1');

            $c_time_display =$this->input->post('due_date1');
            $date_due1=explode('/',$c_time_display);
            $data['due_date']=$date_due1[2].'-'.$date_due1[1].'-'.$date_due1[0];
            
            $create_timestamp= $this->input->post('assign_date1');
            $assign_date1=explode('/',$create_timestamp);
            $data['assign_date']=$assign_date1[2].'-'.$assign_date1[1].'-'.$assign_date1[0];

            $filename=$_FILES['image2']['name'];
            $folder_name = $_SESSION['folder_name'];
            if($filename!="")
            {
              $data['attachment']=file_upload_fun('image2','diary');
              $image_old = $this->input->post('image_old');
              if($image_old!=""){
                $del_location=system_path($image_old,'diary');
                file_delete($del_location);
              }
            }
            
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('diary_id', $param2);
            $this->db->update(get_school_db().'.diary', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_scuccessfully'));
            $planner_check=array();
            $planner_check= $this->input->post('planner_check');
            
            if(isset($planner_check) && ($planner_check!="") && count($planner_check) > 0)
            			{
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('diary_id', $param2);
                $this->session->set_flashdata('club_updated', get_phrase('record_deleted_scuccessfully'));
                $this->db->delete(get_school_db().'.academic_planner_diary');
                foreach($planner_check as $planner)
                {
                    $data2['planner_id']=$planner;
                    $data2['school_id']=$_SESSION['school_id'];
                    $data2['diary_id']= $param2;
                    $this->db->where('school_id',$_SESSION['school_id']);
                    $this->db->where('diary_id', $param2);
                    $this->db->insert(get_school_db().'.academic_planner_diary',$data2);
                    $this->session->set_flashdata('club_updated',get_phrase('record_saved_scuccessfully'));
                }
            }
        
         redirect(base_url() . 'diary/diarys/');
   }
   if ($param1 == 'assign_subjects')
   {
 	
            $diary_id = intval($this->input->post('diary_id'));
            $student_ids = $this->input->post('student_id');
            $this->db->query("delete from ".get_school_db().".diary_student where diary_id = $diary_id");
            $student_diary['school_id'] = $_SESSION['school_id'];
            $student_diary['diary_id'] = $diary_id;
            foreach ($student_ids as $key => $value) 
            {
                $student_diary['student_id'] = $value;
                $this->db->insert(get_school_db().'.diary_student', $student_diary);
            }
  			if(($this->input->post('is_submitted1')) == 0)
        	{
				$data['is_submitted']=0;
				$this->session->set_flashdata('club_updated', get_phrase('diary_assigned_sucessfully'));
			}
			elseif(($this->input->post('is_submitted1')) > 0)
			{
				$data['is_submitted']=1;
				$data['submitted_by']=$_SESSION['login_detail_id'];
				$data['submission_date']=date('Y-m-d H:i:s');
				$this->session->set_flashdata('club_updated', get_phrase('diary_assigned_and_submitted_sucessfully'));
			}        
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('diary_id', $diary_id);
            $this->db->update(get_school_db().'.diary', $data);
            redirect(base_url().'diary/diarys');
    }
   if ($param1 == 'delete') 
   {
        $qur_1=$this->db->query("select diary_id from ".get_school_db().".academic_planner_diary where diary_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
        if(count($qur_1)>0)
        {
            $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use')); 
            redirect(base_url() . 'diary/diarys/');
        }

        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('diary_id', $param2);
        $this->db->delete(get_school_db().'.diary');
        $image_old = $param3;
        $del_location=system_path($image_old,'diary');
        file_delete($del_location);
        
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('diary_id', $param2);
        $this->db->delete(get_school_db().'.academic_planner_diary');
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('diary_id', $param2);
        $this->db->delete(get_school_db().'.diary_student');
            
        $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
        redirect(base_url() . 'diary/diarys');

        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('diary_id', $param2);
        $this->db->delete(get_school_db().'.diary');
        
        redirect(base_url() . 'diary/diarys/'.$param3);
   
    }
        

    $per_page = 10;
    $apply_filter = $this->input->post('apply_filter', TRUE);
    $section_id = $this->input->post('section_id', TRUE);
    $date_query="";
    $start_date=date_slash($this->input->post('starting'));
    $end_date=date_slash($this->input->post('ending'));
    if($start_date!='')
    {
    	$date_query=" AND dr.assign_date >= '".$start_date."'";
    	$page_data['filter'] = true;
    }
    if($end_date!='')
    {
    	$date_query=" AND dr.assign_date <= '".$end_date."'";
    	$page_data['filter'] = true;
    }
    if($start_date!='' && $end_date!='')
    {
    	$date_query=" AND dr.assign_date between '".$start_date."' AND '".$end_date."' ";
    	$page_data['filter'] = true;
    }

    $subj_id= $this->input->post('subject_select');
    $teach_id=$this->input->post('teacher_select');
    $std_search = $this->input->post('std_search', TRUE);
    $std_search = trim(str_replace(array("'", "\""), "", $std_search));
    $section_query="";
    $class_query="";
    $subj_query="";
    $teach_query="";
    
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
    if (!isset($subj_id) || $subj_id == "") {
        $subj_id = $this->uri->segment(6);
    }
    if (!isset($subj_id) || $subj_id == "") {
        $subj_id = 0;
    }
    if (!isset($teach_id) || $teach_id == "") {
        $teach_id = $this->uri->segment(7);
    }
    if (!isset($teach_id) || $teach_id == "") {
        $teach_id = 0;
    }  
    if (!isset($std_search) || $std_search == "") {
        $std_search = $this->uri->segment(8);
    }
    if (!isset($std_search) || $std_search == "") {
        $std_search = 0;
    }               

    $page_num = $this->uri->segment(9);
    if (!isset($page_num) || $page_num == "") {
        $page_num = 0;
        $start_limit = 0;
    } else {
        $start_limit = ($page_num - 1) * $per_page;
    }
   
    if(isset($section_id) && ($section_id>0))
    {
    	$section_query = " AND dr.section_id=$section_id";
    	$page_data['filter'] = true;
    }
    
    if((isset($subj_id) && ($subj_id>0)) )
    {
    	$subj_query=" AND dr.subject_id=$subj_id";
    	$page_data['filter'] = true;
    }
    
    if(isset($teach_id) && $teach_id>0)
    {
    	$teach_query= " AND dr.teacher_id=$teach_id"; 
    	$page_data['filter'] = true;
    }
    
    $frm_tp    = 1;
    $std_query = "";
    if (isset($std_search) && !empty($std_search))
    {
    	$std_query = " AND ( dr.task LIKE '%" . $std_search . "%' OR dr.title LIKE '%" . $std_search . "%' )";
        $page_data['filter'] = true;
    }

    $q="SELECT dr.diary_id as diary_id,dr.teacher_id as teacher_id,dr.subject_id as subject_id,dr.section_id as section_id,dr.assign_date as assign_date,
        dr.due_date as due_date,dr.task as task,dr.title as title,dr.attachment as attachment, dr.school_id,dr.is_submitted as is_submitted,dr.submission_date as submission_date,
        s.code as code, s.name as subject_name,d.title as dept_name,sec.title as section_name,c.name as class_name,ul.name as user_name
        FROM ".get_school_db().".diary dr
        INNER JOIN ".get_school_db().".subject s ON dr.subject_id = s.subject_id
        INNER JOIN ".get_school_db().".class_section sec ON dr.section_id=sec.section_id
        INNER join ".get_school_db().".class c ON sec.class_id=c.class_id
        INNER join ".get_school_db().".departments d ON c.departments_id = d.departments_id
        LEFT join ".get_system_db().".user_login_details uld ON uld.user_login_detail_id	=dr.submitted_by
        LEFT join ".get_system_db().".user_login ul ON uld.user_login_id=ul.user_login_id
        WHERE dr.school_id=".$_SESSION['school_id'] . $date_query. $section_query .$subj_query.$teach_query.$std_query." order by dr.assign_date desc ";

        $diary_count = $this->db->query($q)->result_array();
        $total_records = count($diary_count);
        $quer_limit = $q . " limit " . $start_limit . ", " . $per_page . "";
        $diary1 = $this->db->query($quer_limit)->result_array();
        $this->load->library('pagination');
        $config['base_url'] = base_url() . "diary/diarys/" . $start_date . "/". $end_date . "/". $section_id . "/".$subj_id."/".$teach_id."/".$std_search;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 9;
        $config['num_links'] = 2;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $page_data['start_limit'] = $start_limit;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['total_records'] = $total_records;
        $page_data['section_id'] = $section_id;
        $page_data['pagination'] = $pagination;
        $page_data['diary1']   =$diary1;
        $page_data['start_date']=$start_date;
        $page_data['end_date']=$end_date;
        $page_data['subject_id']=$subj_id;
        $page_data['teacher_id']=$teach_id;
        $page_data['std_search']=$std_search;
        $page_data['diary']   =$diary;
        $page_data['page_name']  = 'diary';
        $page_data['page_title'] = get_phrase('manage_diary');
        
        $this->load->view('backend/index', $page_data);
    }
    
    function get_class()
    {
		echo  class_option_list($this->input->post('department_id'),$this->input->post('class_id'));
	}
	
	function get_class_section()
	{
		echo section_option_list($this->input->post('class_id'),$this->input->post('section_id'));
	}
	
	function get_section_student_subject()
	{
		echo $subject_list=subject_option_list($this->input->post('section_id'));
	}
	
	function get_subject_teacher()
	{
		echo subject_teacher_option_list($this->input->post('subject_id'));
	}
	
	function get_year_term()
    {
 		echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'));
	}
	function get_year_term2()
    {
		if($this->input->post('acad_year')!="")
		{
			echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'),'',1);
		}
 		
 	}
	function diary_generator()
	{
		$this->load->view('backend/admin/ajax/get_diary.php');
	}
	function get_acad_checkboxes()
	{
		$assign_date=$this->input->post('assign_date');
		$subject_id= $this->input->post('subject_id');
		$subject_array=explode("-",$subject_id);
		$subj_id=$subject_array[0];
		$q="SELECT planner_id,title FROM ".get_school_db().".academic_planner WHERE `start`='$assign_date' AND subject_id=$subj_id AND school_id=".$_SESSION['school_id']."";

		$query=$this->db->query($q)->result_array();
		if(count($query) > 0)
		{
			foreach($query as $row)
			{
				echo '<label><input type="checkbox" name="planner_check[]" value="'.$row["planner_id"].'">'.$row["title"].'</label>';
			    echo '<br/>';
			}
		}
		else
		{
			echo get_phrase('no_record_found');
		}
		
	}
	
	function term_date_range()
	{
		if(!empty($this->input->post('start_date')))
		{
			echo term_date_range($this->input->post('term_id'),$this->input->post('start_date'),'');
		}
		if(!empty($this->input->post('end_date')))
		{
			echo term_date_range($this->input->post('term_id'),'',$this->input->post('end_date'));
		}
		
	}

	function section_student_subject()
    {
    	$subject_list=array();
    	$student_list=section_student($this->input->post('section_id'));
		$list_array=array();
		$list_array['student']=$student_list;
    	$subject_list=teacher_subject_list($this->input->post('section_id'));
        $list_array['subject']=$subject_list;
        echo json_encode($list_array);
    }
    

	
}