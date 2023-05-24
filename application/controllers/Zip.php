<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Zip extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('zip');
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->school_db=$_SESSION['school_db'];
        if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
        $_SESSION['school_id']=101;
    }
      
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
    	
  
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
      public function set_up_image()
    {
    	$url = $_POST['link'];
        
        $img = 'assets/login/'.uniqid().".png";

    
        file_put_contents($img, file_get_contents($url));
  
    }
    
    public function circular_download()
	{
		
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/circular/';
		$acad_year=$this->uri->segment(3);
		$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];
	      
		$q="SELECT circular_title,create_timestamp,attachment FROM ".get_school_db().".circular WHERE school_id=".$_SESSION['school_id']." AND attachment!='' AND create_timestamp>='".$start_date."' AND create_timestamp<='".$end_date."' ";
		$query=$this->db->query($q)->result_array();
		if(count($query)==0)
		{
			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
		}
		foreach($query as $res)
		{
			$attachment=$res['attachment'];
			$title=$res['circular_title'];
			$date=$res['create_timestamp'];
			$file_name=$this->download($attachment,$title,$date);
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('Circular');	
	}
	
	public function acad_planner_download()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/academic_planner/';
		$q="SELECT title,start,attachment FROM ".get_school_db().".academic_planner WHERE school_id=".$_SESSION['school_id']." AND attachment!='' ";
		$query=$this->db->query($q)->result_array();
		foreach($query as $res)
		{
			$attachment=$res['attachment'];
			$title=$res['title'];
			$date=$res['start'];
			$file_name=$this->download($attachment,$title,$date);
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('academic_planner');	
	}
	
	public function diary_download()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/diary/';
		$acad_year=$this->uri->segment(3);
		$section_id=$this->uri->segment(4);	
		$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];  
		$q="SELECT title,assign_date,attachment FROM ".get_school_db().".diary WHERE school_id=".$_SESSION['school_id']." AND attachment!='' AND assign_date>='".$start_date."' AND assign_date<='".$end_date."' AND section_id=$section_id ";
		$query=$this->db->query($q)->result_array();
		if(count($query)==0)
		{
			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
		}
		foreach($query as $res)
		{
			$attachment=$res['attachment'];
			$title=$res['title'];
			$date=$res['assign_date'];
			$file_name=$this->download($attachment,$title,$date);
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('diary');	
	}
	
	public function student_leaves_download()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/leaves_student/';
		$acad_year=$this->uri->segment(3);
		$section_id=$this->uri->segment(4);
		$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];
		$q="SELECT ls.reason,ls.request_date,ls.proof_doc,cs.section_id FROM ".get_school_db().".leave_student ls
		INNER join ".get_school_db().".student s
 		ON ls.student_id=s.student_id
 		INNER join ".get_school_db().".class_section cs
 		ON s.section_id=cs.section_id
		 WHERE ls.school_id=".$_SESSION['school_id']." AND ls.proof_doc!='' AND ls.request_date>='".$start_date."' AND ls.request_date<='".$end_date."' AND cs.section_id=$section_id";
		
		$query1=$this->db->query($q)->result_array();
		if(count($query1)==0)
		{
			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
		} 
		foreach($query1 as $res)
		{
			$attachment=$res['proof_doc'];
			$title=$res['reason'];
			$date=$res['request_date'];
			$file_name=$this->download($attachment,$title,$date);
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('leaves_student');	
	}
	
	public function staff_leaves_download()
	{
		$academic_year=$this->uri->segment(3);
		$staff_id=$this->uri->segment(4);
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/leaves_staff/';
		$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$academic_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];      
		$q="SELECT reason,request_date,proof_doc FROM ".get_school_db().".leave_staff WHERE school_id=".$_SESSION['school_id']." AND proof_doc!='' AND request_date>='".$start_date."' AND request_date<='".$end_date."' AND staff_id=$staff_id ";
		$query=$this->db->query($q)->result_array();
		if(count($query) == 0)
		{
			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup');
		}
		foreach($query as $res)
		{
			$attachment=$res['proof_doc'];
			$title=$res['reason'];
			$date=$res['request_date'];
			$file_name=$this->download($attachment,$title,$date);
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('leaves_staff');	
	}
	
	public function staff_evaluation_download()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/staff_evaluation/';
		$acad_year=$this->uri->segment(3);
		$staff_id=$this->uri->segment(4);
   		
   		$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];
   	
		$q="SELECT se.evaluation_date,se.attachment as staff_attachment,s.name as staff_name FROM ".get_school_db().".staff_evaluation se
								INNER JOIN ".get_school_db().".staff s
								ON se.staff_id=s.staff_id
								 WHERE se.school_id=".$_SESSION['school_id']." 
								 AND se.attachment!='' 
								 AND se.evaluation_date >= '".$start_date."' 
								 AND se.evaluation_date <='".$end_date."' 
								 AND se.staff_id=$staff_id ";
 
 
		$query=$this->db->query($q)->result_array();
		if(count($query)==0)
 		{
 			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup');      
 		}
		foreach($query as $res)
		{
			$attachment=$res['staff_attachment'];
			$title=$res['staff_name'];
			$date=$res['evaluation_date'];
			$file_name=$this->download($attachment,$title,$date);
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('staff_evaluation');	
	}
	
	public function student_evaluation_download()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/student_evaluation/';
		$exam_id=$this->uri->segment(3);
		$section_id=$this->uri->segment(4);
   		
   		
   	
		$r="SELECT se.*,s.name as student_name,s.roll as roll_no 
					FROM  ".get_school_db().".student_evaluation se 
					INNER JOIN ".get_school_db().".student s 
					ON se.student_id=s.student_id
					INNER JOIN ".get_school_db().".exam e
					ON se.exam_id=e.exam_id
 						WHERE s.section_id=".$section_id." 
					AND se.school_id=".$_SESSION['school_id']." 
					AND se.exam_id=$exam_id  ";
 
 
		$query=$this->db->query($r)->result_array();
		if(count($query)==0)
 		{
 			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup');      
 		}
		foreach($query as $res)
		{
			$attachment=$res['attachment'];
			$title=$res['student_name'];
			
			$file_name=$this->download($attachment,$title,'');
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('student_evaluation');	
	}
	
	
	public function view_student_attendance()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/leaves_student/';
		$q="SELECT reason,date,proof_doc FROM ".get_school_db().".attendance WHERE school_id=".$_SESSION['school_id']." AND proof_doc!='' ";
		$query=$this->db->query($q)->result_array();
		foreach($query as $res)
		{
			$attachment=$res['proof_doc'];
			$title=$res['reason'];
			$date=$res['request_date'];
			$file_name=$this->download($attachment,$title,$date);
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('leaves_student');	
	}
	
	public function students_download()
	{
		
		$academic_year=$this->uri->segment(3);
		$section_id=$this->uri->segment(4);
		
		
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/student/';
		
		$q="SELECT image,student_id,name,id_no,id_file FROM ".get_school_db().".student  WHERE academic_year_id=$academic_year AND section_id=$section_id AND school_id=".$_SESSION['school_id']." and student_status in ('".student_query_status()."')";
		
		$query=$this->db->query($q)->result_array();
		if(count($query) == 0)
		{
			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup');
		}
		
		foreach($query as $res)
		{
			$file="";
			$form_b="";
			if($res['image']!='')
			{
				 $form_b=$res['id_no'];
				$student_image=$res['image'];
				$file_name=$this->download($student_image,$form_b."pic",'');
				$file=$p.$student_image;
				
				
			}
			if(file_exists($file))
				{
					$data=read_file($file,true);
					$this->zip->add_data($file_name, $data);
				}
				$file_name="";
			if($res['id_file']!='')
			{
				$form_b=$res['id_no'];
				$form_b_image=$res['id_file'];
				$file_name=$this->download($form_b_image,$form_b,'');
				$file=$p.$form_b_image;
				
			}
			
			if(file_exists($file))
				{
					$data=read_file($file,true);
					$this->zip->add_data($file_name, $data);
				}
			
			$query1=$this->db->query("SELECT sp.p_name,sp.id_no,sp.id_file,sr.relation FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$res['student_id']." AND sp.attachment!='' ")->result_array();
	  //echo $this->db->last_query();
	  		if(count($query1 > 0))
	  		{
	  			foreach($query1 as $result)
	  			{
	  				$file="";
	  				$file_name="";
					if($result['relation']=='f')
					{
						
						$father_attach=$result['id_file'];
						$file_name=$this->download($father_attach,$form_b."f",'');
						$file=$p.$father_attach;
						if(file_exists($file))
						{
							$data=read_file($file,true);
							$this->zip->add_data($file_name, $data);
						}			
					}
					elseif($result['relation']=='m')
					{
						$file="";
	  					$file_name="";
						$mother_attach=$result['id_file'];
						$file_name=$this->download($mother_attach,$form_b."m",'');
						$file=$p.$mother_attach;
						if(file_exists($file))
						{
							$data=read_file($file,true);
							$this->zip->add_data($file_name, $data);
						}		
					}
					elseif($result['relation']=='g')
					{
						$file="";
	  					$file_name="";
						$guardian_attach=$result['id_file'];
						$file_name=$this->download($guardian_attach,$form_b."g",'');
						$file=$p.$guardian_attach;
						if(file_exists($file))
						{
							$data=read_file($file,true);
							$this->zip->add_data($file_name, $data);
						}			
					}
				
				}
	  		}	
			
		}
		
		$this->zip->download('students');	
	}
	
	public function candidates_download()
	{
		$academic_year=$this->uri->segment(3);
		$section_id=$this->uri->segment(4);
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/student/';
		
		$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$academic_year."";
		
		
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];
		$q="SELECT image,student_id,name,id_no,id_file FROM ".get_school_db().".student 
											WHERE system_date >='".$start_date."' 
											AND system_date<='".$end_date."' 
											AND section_id=$section_id 
											AND school_id=".$_SESSION['school_id']." 
											AND student_status <= 9";
		
		$query=$this->db->query($q)->result_array();
		if(count($query) == 0)
		{
			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup');
		}
		foreach($query as $res)
		{
			$file="";
			$form_b="";
			if($res['image']!='')
			{
				$form_b=$res['id_no'];
				$student_image=$res['image'];
				$file_name=$this->download($student_image,$form_b."pic",'');
				$file=$p.$student_image;	
			}
			if(file_exists($file))
				{
					$data=read_file($file,true);
					$this->zip->add_data($file_name, $data);
				}
			$file_name="";
			if($res['id_file']!='')
			{
				$form_b=$res['id_no'];
				$form_b_image=$res['id_file'];
				$file_name=$this->download($form_b_image,$form_b,'');
				$file=$p.$form_b_image;
				
			}
			if(file_exists($file))
				{
					$data=read_file($file,true);
					$this->zip->add_data($file_name, $data);
				}
			
			$query1=$this->db->query("SELECT sp.p_name,sp.id_no,sp.id_file,sr.relation FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$res['student_id']." AND sp.id_file!='' ")->result_array();
	  //echo $this->db->last_query();
	  		if(count($query1 > 0))
	  		{
	  			foreach($query1 as $result)
	  			{
	  				$file="";
	  				
					if($result['relation']=='f')
					{
						
						$father_attach=$result['id_file'];
						$file_name=$this->download($father_attach,$form_b."f",'');
						$file=$p.$father_attach;
						
						
					}
					elseif($result['relation']=='m')
					{
						
						$mother_attach=$result['id_file'];
						$file_name=$this->download($mother_attach,$form_b."m",'');
						
						$file=$p.$mother_attach;
						
						
					}
					elseif($result['relation']=='g')
					{
						$guardian_attach=$result['id_file'];
						$file_name=$this->download($guardian_attach,$form_b."g",'');
						$file=$p.$guardian_attach;
						
						
						
					}
				if(file_exists($file))
						{
							$data=read_file($file,true);
							$this->zip->add_data($file_name, $data);
						}	
				}
	  		}
	  		
	  		
			
			
			
			
		}
		
		$this->zip->download('candidates');
	}
	
	public function staff_download()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		$p='./uploads/'.$_SESSION['folder_name'].'/staff/';
		$q="SELECT name,employee_code,staff_image FROM ".get_school_db().".staff WHERE school_id=".$_SESSION['school_id']."  AND staff_image!='' ";
		$query=$this->db->query($q)->result_array();
		foreach($query as $res)
		{
			$attachment=$res['staff_image'];
			$title=$res['name'];
			$emp_code=$res['employee_code'];
			$file_name=$this->download($attachment,$title,$emp_code);
			$file=$p.$attachment;
			if(file_exists($file))
			{
				$data=read_file($file,true);
				$this->zip->add_data($file_name, $data);
			}
			
		}
		$this->zip->download('staff');	
	}
	
	public function branch_download()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		$q="select sys_sch_id, school_db FROM ".get_system_db().".system_school 
            where parent_sys_sch_id=".$_SESSION['sys_sch_id']." AND sys_sch_id!=parent_sys_sch_id";
        $query=$this->db->query($q)->result_array();  
        //echo '<pre>';
        //print_r($query);      
        foreach($query as $query2)
        {
        $school_db = $query2['school_db'];
        $q2="select sch.school_id as school_id, sch.name, sch.address, sch.phone, sch.logo as logo, sch.url, sch.email as school_email,sch.folder_name as folder_name,sch.sys_sch_id as sys_sch_id
                FROM ".$school_db.".school sch
                WHERE 
                sch.sys_sch_id=".$query2['sys_sch_id']." 
                ";
            $result=$this->db->query($q2)->result_array();  
            //echo $this->db->last_query();

            foreach($result as $row)
            {
            	$folder_name=$row['folder_name'];
				$p='./uploads/'.$folder_name.'/';
				
				$q="SELECT name,slogan,logo FROM ".$school_db.".school WHERE sys_sch_id=".$query2['sys_sch_id']."  AND logo!='' ";
				$query=$this->db->query($q)->result_array();
				foreach($query as $res)
				{
					$attachment=$res['logo'];
					$title=$res['name'];
					$slogan=$res['slogan'];
					$file_name=$this->download($attachment,$title,$slogan);
					$file=$p.$attachment;
					if(file_exists($file))
					{
						
						$data=read_file($file,true);
						$this->zip->add_data($file_name, $data);
					}
			
					}
				
				}
			
		}
		
		$this->zip->download('branch');	
	}
	public function policies_download()
	{
		$this->load->library('zip');
		$this->load->helper('file');
		
		
		$p='./uploads/'.$_SESSION['folder_name'].'/policies/';
		$q="SELECT title,document_num,version_num,attachment FROM ".get_school_db().".policies WHERE school_id=".$_SESSION['school_id']."  AND attachment!='' ";
		$query=$this->db->query($q)->result_array();
		
		foreach($query as $res)
		{
			$attachment=$res['attachment'];
			$title=$res['title'];
			$doc_code="_".$res['document_num']."_".$res['version_num'];
			$file_name=$this->download($attachment,$title,$doc_code);
			
			$file=$p.$attachment;
			
			if(file_exists($file))
			{
				$file_data=read_file($file,true);
				$this->zip->add_data($file_name, $file_data);
				//$this->zip->read_file($file);
				/*
				$data = array(
                	$file_name => $file_data
            	);
				
				$this->zip->add_data($data);
				*/
			}
		}
		
		$this->zip->download('policies');
	}
	
	public function download($attachment="",$title="",$date="")
	{	
		
			$arr_val=array("!","#","$","%","&","'","(",")","*","+",",","-",".","/",":",";","<","=",">","?","@","[","\\","]","^","_","`","{","|","}","~","\"");
			$title_new=trim(str_replace($arr_val,"",$title));
			$title_new=str_replace(" ","_",$title_new);
			$title_new=str_replace(".","_",$title_new);
			
			$title_val=substr($title_new,0,15);
			
			$title_res=$title_val.'_'.$date;
			
			$title_res=trim(str_replace($arr_val,"",$title_res));
			$title_res=str_replace(" ","_",$title_res);
			$title_res=str_replace(".","_",$title_res);		
			
			$arr_explode=explode('.',$attachment);
			$ext=end($arr_explode);
			
			return $file_name=$title_res.".".$ext;
	}
}
?>