<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

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
    public function index()
    {
    }
    
    
    function view_notes(){
        if($_SESSION['user_login']!=1)redirect('login' );
        
        /*
        
        
       	$q = "select nt.* , (Select GROUP_CONCAT(document_url) from ".get_school_db().".lecture_notes_documents where notes_id = nt.notes_id) 
       	      as urls from ".get_school_db().".lecture_notes nt WHERE nt.school_id=".$_SESSION['school_id']." 
	                and nt.teacher_id=".$_SESSION['user_id']."  order by nt.notes_id desc";    
        */
        
        
        $q = "select ln.* ,lnt.* , s.name as teacher_name , sbj.name as subject_name,
        (Select GROUP_CONCAT(document_url) from ".get_school_db().".lecture_notes_documents where notes_id = ln.notes_id) 
       	      as urls from ".get_school_db().".lecture_notes ln
	    inner join ".get_school_db().".lecture_notes_audience lnt on lnt.notes_id = ln.notes_id
	    inner join ".get_school_db().".staff s on ln.teacher_id = s.staff_id
	    inner join ".get_school_db().".subject sbj on sbj.subject_id = lnt.subject_id
	    WHERE lnt.student_id = ".$_SESSION['student_id']." and ln.school_id = ".$_SESSION['school_id']." order by ln.notes_id desc";
	    $notes = $this->db->query($q)->result_array();
	    
	    $page_data['notes']		    =  $notes;
		$page_data['page_name']		=  'notes/view_notes';
		$page_data['page_title']	=  get_phrase('view_notes');
		$this->load->view('backend/index', $page_data);
        
    }
    
	
}
?>