<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Notes extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('common');
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
	}

	function create_notes()
	{
		if($_SESSION['user_login']!=1)redirect('login' );
		$page_data['page_name']		=	'notes/create_notes';
		$page_data['page_title']    =	get_phrase('create_notes');
		$this->load->view('backend/index', $page_data);
	}
	
	function assign_notes($notes_id)
	{
		if($_SESSION['user_login']!=1)redirect('login' );
		$page_data['page_name']		=	'notes/assign_notes';
		$page_data['page_title']    =	get_phrase('assign_notes');
		$page_data['notes_id'] =   $notes_id;
		$this->load->view('backend/index', $page_data);
	}
	
	
	function notes_details($notes_id)
	{
		if($_SESSION['user_login']!=1)redirect('login' );
		$page_data['page_name']		=	'notes/notes_details';
		$page_data['page_title']    =	get_phrase('notes_details');
		$page_data['notes_id'] =   $notes_id;
		$q = "select nt.* , (Select GROUP_CONCAT(document_url) from ".get_school_db().".lecture_notes_documents where notes_id = nt.notes_id) as urls from ".get_school_db().".lecture_notes nt WHERE nt.school_id=".$_SESSION['school_id']." 
	          and nt.teacher_id=".$_SESSION['user_id']." and nt.notes_id =$notes_id order by nt.notes_id desc";
	    $notes = $this->db->query($q)->result_array();
	    
	    $page_data['notes']		 =	 $notes;
		$this->load->view('backend/index', $page_data);
	}
	
	
    function check_assigned_notes()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    $notes_id  = $this->input->post('notes_id');
	    $section_id     = $this->input->post('section_id');
	    
	    $query = "select notes_id from ".get_school_db().".lecture_notes_audience
	                    WHERE notes_id = $notes_id and section_id = $section_id";
	    $row = $this->db->query($query)->row();
	    
	    if($row != null){
	         echo 1; 
	    }
	    else
	    {
	        echo 0;
	    }
	    
	}
	
	function view_notes()
	{
	   if($_SESSION['user_login']!=1) redirect('login'); 
	   $q = "select nt.* , (Select GROUP_CONCAT(document_url) from ".get_school_db().".lecture_notes_documents where notes_id = nt.notes_id) as urls from ".get_school_db().".lecture_notes nt WHERE nt.school_id=".$_SESSION['school_id']." 
	                and nt.teacher_id=".$_SESSION['user_id']."  order by nt.notes_id desc";
	   $notes = $this->db->query($q)->result_array();
	    
	    $page_data['notes']		 =	 $notes;
		$page_data['page_name']  =	'notes/view_notes';
		$page_data['page_title'] =	 get_phrase('view_notes');
		$this->load->view('backend/index', $page_data);
	}
	
	function save_notes()
	{
	    $data['teacher_id']    = $_SESSION['user_id'];
	    $data['school_id']     = $_SESSION['school_id'];
	    $data['notes_title']   = $this->input->post('notes_title');
	    $data['description']   = $this->input->post('notes_description');
	    $data['remarks']       = $this->input->post('notes_remarks');
	    
	    $this->db->insert(get_school_db().".lecture_notes" , $data);
	    
	    $notes_id =$this->db->insert_id();
	   
	    $data_note_documents = array();

        if(count($_FILES["documents"]['name'])>0)
        { 
            for($j=0; $j < count($_FILES["documents"]['name']); $j++)
            { 
                 $file_name = $_FILES["documents"]['name']["$j"];
                 $temp      = $_FILES["documents"]['tmp_name']["$j"];
                 if($file_name != ''){
                    $name = file_upload_notes($file_name,$temp);
                    if($name != ''){
                        	
                        $data_doc = array(
                            'notes_id'      => $notes_id , 
                            'document_url'  => $name
                        );
                        array_push($data_note_documents,$data_doc);                        
                    }
                 }
            }
            $this->db->insert_batch(get_school_db().".lecture_notes_documents" , $data_note_documents);
        }
	   // redirect(base_url().'notes/view_notes');
	   echo "Notes Upload Succsessfully";
	}
	
	function view_assessment_to_parent()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $q = "select ass.* ,ass_aud.* , s.name as teacher_name , stud.name as student_name from ".get_school_db().".assessments ass
	    inner join ".get_school_db().".assessment_audience ass_aud on ass.assessment_id = ass_aud.assessment_id
	    inner join ".get_school_db().".staff s on ass.teacher_id = s.staff_id
	    inner join ".get_school_db().".student stud on ass_aud.student_id = stud.student_id
	    WHERE ass_aud.student_id = ".$_SESSION['student_id']." and ass_aud.is_submitted = 0 and ass.school_id = ".$_SESSION['school_id']."";
	    $assessments = $this->db->query($q)->result_array();
	    
	    $page_data['assessments']		=	$assessments;
		$page_data['page_name']		=	'assessment/view_assessment';
		$page_data['page_title']		=	get_phrase('view_assessment');
		$this->load->view('backend/index', $page_data);
	}
	
	function get_section_studens()
	{
	    $section_id = $this->input->post('section_id');
	    
	    $q = "select student_id, name, roll  from ".get_school_db().".student 
	    where section_id = ".$section_id."  and school_id=".$_SESSION['school_id']."  and student_status IN (".student_query_status().") ";
	    $students = $this->db->query($q)->result_array();
	    
	    echo json_encode($students);
	}
	
	function save_assign_notes()
	{
	    $this->db->trans_begin();
	    
	    $notes_id   = $this->input->post('notes_id');
	    $date = date('Y-m-d');
	    $section_id = $this->input->post('section_id');
	    $subject_id = $this->input->post('subject_id');
	    $student_id = $this->input->post('student_id');
	    
        $data_audience = array();
	    foreach($student_id as $student)
	    {
	       $data_audience_row = array(
                'notes_id'        => $notes_id , 
                'section_id'      => $section_id,
                'subject_id'      => $subject_id,
                'student_id'      => $student
            );
            array_push($data_audience,$data_audience_row);
            
            $device_id  =   get_user_device_id(6 , $student , $_SESSION['school_id']);
            $title      =   "New Lecture Notes";
            $message    =   "Lecture Notes Has been Sent By The Teacher.";
            $link       =    base_url()."notes/view_notes";
            $resp = sendNotificationByUserId($device_id, $title, $message, $link , $student , 6);
            
	    }
	    $this->db->insert_batch(get_school_db().".lecture_notes_audience" , $data_audience);
	    
	    $data_note['is_assigned'] = 1;
	    $this->db->where('notes_id', $notes_id); 
        $this->db->update(get_school_db().'.lecture_notes',$data_note);
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('club_updated', get_phrase('notes_not_assigned'));
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('club_updated', get_phrase('notes_assigned_successfully'));
        }
        
	    redirect(base_url().'notes/view_notes');
	}
	
	function notes_delete($note_id,$urls)
	{
	    $this->db->trans_begin();
	    $filePath = str_decode($urls);
	  
	    $del_query = $this->db->where("notes_id",$note_id)->delete(get_school_db().".lecture_notes");
	    file_delete_path($note_id,$filePath);
	    $this->db->where("notes_id",$note_id)->delete(get_school_db().".lecture_notes_audience");
	    $this->db->where("notes_id",$note_id)->delete(get_school_db().".lecture_notes_documents");
	    
	    $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('club_updated', get_phrase('notes_not_deleted'));
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('club_updated', get_phrase('notes_deleted_successfully'));
        }
        redirect(base_url().'notes/view_notes');
	}

	
}
?>