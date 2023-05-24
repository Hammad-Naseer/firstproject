<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Certificate extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
	}
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if($_SESSION['user_login']!= 1)
        redirect(base_url() . 'login');
                if ($_SESSION['user_login'] == 1)
        redirect(base_url() . 'admin/dashboard');
    }
	
    function leaving_certificate()
    {
    	$section_id =   $this->input->post("section_id");
      	$student_id =   $this->input->post("student_id");
      	$apply_filter   =   $this->input->post("apply_filter");
      	
        if(isset($apply_filter) && ($apply_filter > 0)){
            $qur = $this->db->query("select s.* , sec.title from ".get_school_db().".student s
            inner join ".get_school_db().".class_section sec on s.section_id = sec.section_id
            where  s.student_id = ".$student_id."  and s.school_id=".$_SESSION['school_id']."  ")->row();
  		    $page_data['student_data']= $qur;
		}else{
		    $page_data['student_data']= array();
		}
	    
		$page_data['section_id']        =   $section_id;
  		$page_data['student_id']        =   $student_id;
  		$page_data['apply_filter']      =   $apply_filter;
  			
    	$page_data['page_name']         =   'leaving_certificate';
    	$page_data['page_title']		=	get_phrase('leaving_certificate');
    	$this->load->view('backend/index', $page_data);
    }
    
    function get_section_student()
    {
        $section_id = $this->input->post('section_id');
        if($section_id != "")
        {
            echo section_student($section_id);
        }
    }	
    
    function leaving_certificate_pdf()
    {
        $student_id =   $this->input->post("student_id");
        
        $qur = $this->db->query("select s.* , sec.title from ".get_school_db().".student s inner join ".get_school_db().".class_section sec on s.section_id = sec.section_id where  s.student_id = ".$student_id."  and s.school_id=".$_SESSION['school_id']."  ")->row();
        
        $student_data   =   $qur;
      	$data['ribbon']      =   'Leaving-Certificate.png';
      	$data['certificate_data'] = 'It is certified that <span style="color:#21a9e1">'.$student_data->name.'</span> s/o 
                              	    <span style="color:#21a9e1">'.$student_data->father_name.'</span>
                                   is a bonafied student of this school.His/her date of birth per school record is 
                                   <span style="color:#21a9e1">'.$student_data->birthday.'</span>. We wish him/her a very bright future.
                                   This certificate is being issued at the request of the above-named student for whatever legal purpose it may serve.';
      	$data['page_name']      =   'leaving_certificate_pdf';
        $data['page_title']     =   'leaving_certificate_pdf';
        //$this->load->view('backend/index', $data);
        $this->load->library('Pdf');
        $view = 'backend/admin/certificate_pdf';
        $this->pdf->load_view($view,$data);
        $this->pdf->set_paper("A4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("".$data['page_title'].".pdf");
    }
    
    function character_certificate(){
        $section_id =   $this->input->post("section_id");
      	$student_id =   $this->input->post("student_id");
      	$apply_filter   =   $this->input->post("apply_filter");
      	
        if(isset($apply_filter) && ($apply_filter > 0)){
            $qur = $this->db->query("select s.* , sec.title from ".get_school_db().".student s
            inner join ".get_school_db().".class_section sec on s.section_id = sec.section_id
            where  s.student_id = ".$student_id."  and s.school_id=".$_SESSION['school_id']."  ")->row();
  		    $page_data['student_data']= $qur;
		}else{
		    $page_data['student_data']= array();
		}
	    
		$page_data['section_id']        =   $section_id;
  		$page_data['student_id']        =   $student_id;
  		$page_data['apply_filter']      =   $apply_filter;
        $page_data['page_name']         =   'character_certificate';
    	$page_data['page_title']		=	get_phrase('character_certificate');
    	$this->load->view('backend/index', $page_data);
    }
    
    function character_certificate_pdf()
    {
        $student_id =   $this->input->post("student_id");
        
        $qur = $this->db->query("select s.* , sec.title from ".get_school_db().".student s inner join ".get_school_db().".class_section sec on s.section_id = sec.section_id where  s.student_id = ".$student_id."  and s.school_id=".$_SESSION['school_id']."  ")->row();
        
        $student_data   =   $qur;
      	$data['ribbon']      =   'Character-Certificate.png';
      	$data['certificate_data'] = 'It is certified that <span style="color:#21a9e1">'.$student_data->name.'</span> s/o 
                              	    <span style="color:#21a9e1">'.$student_data->father_name.'</span>
                                   is a bonafied student of this school. To the best of my knowledge, he/she bears a good moral character. 
                                   his/her behaviour was good with teachers and students. This certificate is being issued upon the request of the above-named student for required purpose.';
      	$data['page_name']      =   'leaving_certificate_pdf';
        $data['page_title']     =   'leaving_certificate_pdf';
        //$this->load->view('backend/index', $data);
        $this->load->library('Pdf');
        $view = 'backend/admin/certificate_pdf';
        $this->pdf->load_view($view,$data);
        $this->pdf->set_paper("A4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("".$data['page_title'].".pdf");
    }
    
    function provisional_certificate(){
        $section_id =   $this->input->post("section_id");
      	$student_id =   $this->input->post("student_id");
      	$apply_filter   =   $this->input->post("apply_filter");
      	
        if(isset($apply_filter) && ($apply_filter > 0)){
            $qur = $this->db->query("select s.* , sec.title,c.name as class_name from ".get_school_db().".student s 
                                INNER JOIN ".get_school_db().".class_section sec on s.section_id = sec.section_id 
                                INNER JOIN ".get_school_db().".class c on c.class_id = sec.class_id 
                                WHERE s.student_id = ".$student_id."  and s.school_id=".$_SESSION['school_id']."  ")->row();
  		    $page_data['student_data']= $qur;
		}else{
		    $page_data['student_data']= array();
		}
	    
		$page_data['section_id']        =   $section_id;
  		$page_data['student_id']        =   $student_id;
  		$page_data['apply_filter']      =   $apply_filter;
        $page_data['page_name']         =   'provisional_certificate';
    	$page_data['page_title']		=	get_phrase('provisional_certificate');
    	$this->load->view('backend/index', $page_data);
    } 
    function provisional_certificate_pdf()
    {
        $student_id =   $this->input->post("student_id");
        
        $qur = $this->db->query("select s.* , sec.title,c.name as class_name from ".get_school_db().".student s 
                                INNER JOIN ".get_school_db().".class_section sec on s.section_id = sec.section_id 
                                INNER JOIN ".get_school_db().".class c on c.class_id = sec.class_id 
                                WHERE s.student_id = ".$student_id."  and s.school_id=".$_SESSION['school_id']."  ")->row();
        
        $student_data   =   $qur;
      	$data['ribbon']      =   'Provisional-Certificate.png';
      	$data['certificate_data'] = 'This is to certify that Mr./Ms. <span style="color:#21a9e1">'.$student_data->name.'</span> has qualified for the degree of <span style="color:#21a9e1">'.$student_data->class_name.'</span> from this College/University.';
      	$data['page_name']      =   'leaving_certificate_pdf';
        $data['page_title']     =   'leaving_certificate_pdf';
        //$this->load->view('backend/index', $data);
        $this->load->library('Pdf');
        $view = 'backend/admin/certificate_pdf';
        $this->pdf->load_view($view,$data);
        $this->pdf->set_paper("A4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("".$data['page_title'].".pdf");
    }
    function teacher_experience_certificate()
    {  
      	$staff_id       =   $this->input->post("staff_id");
      	$joining_date   =   $this->input->post("joining_date");
        $leaving_date   =   $this->input->post("leaving_date");

      	$apply_filter   =   $this->input->post("apply_filter");
      	
        if(isset($apply_filter) && ($apply_filter > 0)){
            $qur = $this->db->query("select s.* ,d.title from ".get_school_db().".staff s
            inner join ".get_school_db().".designation d on d.designation_id = s.designation_id
            where s.staff_id = ".$staff_id."  and s.school_id=".$_SESSION['school_id']."  ")->row();
  		    $page_data['staff_data']= $qur;
		}else{
		    $page_data['staff_data']= array();
		}
	    
		$page_data['staff_id']        =   $staff_id;
		$page_data['joining_date']    =   $joining_date;
		$page_data['leaving_date']   =   $leaving_date;
  		$page_data['apply_filter']      =   $apply_filter;
  			
    	$page_data['page_name']         =   'teacher_experience_certificate';
    	$page_data['page_title']		=	get_phrase('teacher_experience_certificate');
    	$this->load->view('backend/index', $page_data);
    }	
    
    function teacher_experience_certificate_pdf() 
    {
        
        $staff_id       =   $this->input->post("staff_id");
      	$joining_date   =   $this->input->post("joining_date");
        $leaving_date   =   $this->input->post("leaving_date");
        
        $qur = $this->db->query("select s.* ,d.title from ".get_school_db().".staff s
            inner join ".get_school_db().".designation d on d.designation_id = s.designation_id
            where s.staff_id = ".$staff_id."  and s.school_id=".$_SESSION['school_id']."  ")->row();
            
        $staff_data   =   $qur;
      	$data['ribbon']      =   'Experience-Certificate.png';
      	$data['certificate_data'] = 'It is to certify that Mr./Ms. <span class="underline" style="color:#21a9e1">'.$staff_data->name.'</span>  ID# <span class="underline" style="color:#21a9e1">'. $staff_data->id_no.'</span> was an employee of <span class="underline" style="color:#21a9e1">'.$_SESSION["school_name"].'</span> in capacity of <span class="underline" style="color:#21a9e1">'.$staff_data->title.'</span>.
                           He/She has been with us from <span class="underline" style="color:#21a9e1">'.date_view($joining_date).'</span> till <span class="underlinen" style="color:#21a9e1">'.date_view($leaving_date).'</span> .We wish him/her good luck in her future endeavours. ';
      	$data['page_name']      =   'leaving_certificate_pdf';
        $data['page_title']     =   'leaving_certificate_pdf';
        //$this->load->view('backend/index', $data);
        $this->load->library('Pdf');
        $view = 'backend/admin/certificate_pdf';
        $this->pdf->load_view($view,$data);
        $this->pdf->set_paper("A4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("".$data['page_title'].".pdf");
        
    }



}