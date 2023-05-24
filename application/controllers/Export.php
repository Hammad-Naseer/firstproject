<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Export extends CI_Controller
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
        //$_SESSION['school_id']=101;
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
    	
  
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
     
     public function circular_csv()
   {
 	$file_name='circular_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$path='./uploads/'.$_SESSION['folder_name'].'/circular/';
	$acad_year=$this->input->post('academic_year11');
	$section_id=$this->input->post('section_id11');
	$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date']; 
 	$q="SELECT cl.circular_id as circular_id,cl.circular_title as circular_title,cl.circular as circular,cl.section_id as section_id,cl.student_id as student_id,cl.create_timestamp as create_timestamp,cl.attachment as attachment,st.name as student_name,st.roll as roll_num,sec.title as section_name,c.name as class_name,d.title as dept_name
 FROM ".get_school_db().".circular cl
LEFT JOIN ".get_school_db().".student st
ON cl.student_id=st.student_id
INNER JOIN ".get_school_db().".class_section sec
ON cl.section_id=sec.section_id
INNER join ".get_school_db().".class c
ON sec.class_id=c.class_id
INNER join ".get_school_db().".departments d
ON c.departments_id = d.departments_id
  WHERE cl.school_id=".$_SESSION['school_id']." AND cl.create_timestamp>='".$start_date."' AND cl.create_timestamp<='".$end_date."' AND cl.section_id=$section_id order by cl.create_timestamp desc ";
 	$query=$this->db->query($q)->result_array();
 	if(count($query)==0)
	{
		$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
	}
	$class_arr[]='Class-Section: '.$query[0]['dept_name'].' - '.$query[0]['class_name'].' - '.$query[0]['section_name'];
	fputcsv($file,$class_arr);
	
	$start_end_arr[]='Start date:'.convert_date($start_date).' End date:'.convert_date($end_date);
 	fputcsv($file,$start_end_arr); 
	$column_names=array(get_phrase('title'), get_phrase('detail'), get_phrase('student'),get_phrase('date'), get_phrase('status'),get_phrase('attachments'));
	fputcsv($file,$column_names);
 	$circular_array=array();
 	foreach($query as $row)
 	{
 		if($row['is_active']==0)
		{
			$row['status']= get_phrase('in_active');
		}
		else
		{
			$row['status']= get_phrase('active');
		}
		if($row['student_name']!='')
		{
			$stud_name=$row['student_name'].' ('.$row['roll_num'].')';
		}
		
		$attachment=$row['attachment'];
		$title=$row['circular_title'];
		$date=$row['create_timestamp'];
		$file_val=$path.$attachment;
		$circular_img="";
		if($attachment!="" && file_exists($file_val))
		{
			
			$circular_img=$this->attachment_name($attachment,$title,$date);
		}
		
		$circular_array=array(
		$row['circular_title'],
		$row['circular'],
		$stud_name,
		convert_date($row['create_timestamp']),
		$row['status'],
		$circular_img
		);
		fputcsv($file,$circular_array);
	}
	$file=$file_name;
	if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
    	header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 
   
   public function noticeboard_csv()
   {
   	$file_name='noticeboard_'.time().".csv";
 	$file = fopen($file_name, 'w');
   	$acad_year=$this->input->post('academic_year10');
   	$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date']; 
	$start_end_arr[]='Start date: '.convert_date($start_date).' End date: '.convert_date($end_date);
 	fputcsv($file,$start_end_arr);
	  
 	
 	$column_names=array(get_phrase('title'), get_phrase('detail'), get_phrase('date'), get_phrase('type'), get_phrase('status'));
	fputcsv($file,$column_names);
 	$q="SELECT *
		FROM ".get_school_db().".noticeboard
		WHERE school_id=".$_SESSION['school_id']. " AND create_timestamp>='".$start_date."' AND create_timestamp<='".$end_date."' ";
 	$query=$this->db->query($q)->result_array();
 	if(count($query)==0)
	{
		$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
	}
 	$result_array=array();
 	foreach($query as $row)
 	{
 		if($row['is_active']==0)
		{
			$row['status']= get_phrase('in_active');
		}
		else
		{
			$row['status']= get_phrase('active');
		}
		if($row['type']==1)
		{
			$row['type']=get_phrase('private');
		}
		elseif($row['type']==2)
		{
			$row['type']= get_phrase('public');
		}
		$result_array=array(
		$row['notice_title'],
		$row['notice'],
		convert_date($row['create_timestamp']),
		$row['type'],
		$row['status']
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
	if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
    	header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 
   
    public function diary_csv()
   {
 	$file_name='diary_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$path='./uploads/'.$_SESSION['folder_name'].'/diary/';
	$acad_year=$this->input->post('academic_year8');
	$section_id=$this->input->post('section_id8');
	$class_sect=section_hierarchy($section_id);
	$class_arr[]=get_phrase('class_section').': '.$class_sect['d'].' - '.$class_sect['c'].'-'.$class_sect['s'];
	fputcsv($file,$class_arr);
	$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date']; 
	$start_end_arr[]=get_phrase('start_date').': '.convert_date($start_date).' '.get_phrase('end_date').': '.convert_date($end_date);
 	fputcsv($file,$start_end_arr); 
	$column_names=array(get_phrase('teacher_name'), get_phrase('subject_name'),get_phrase('assign_date'), get_phrase('due_date'),get_phrase('task'),get_phrase('title'),get_phrase('submission_date'),get_phrase('submitted_by'),get_phrase('attachment'));
	fputcsv($file,$column_names);
	
 	$q="SELECT dr.diary_id as diary_id,dr.teacher_id as teacher_id,dr.subject_id as subject_id,dr.section_id as section_id,dr.assign_date as assign_date,dr.due_date as due_date,dr.task as task,dr.title as title,dr.attachment as attachment, dr.school_id,dr.is_submitted as is_submitted,dr.submission_date as submission_date,s.code as code, s.name as subject_name,d.title as dept_name,sec.title as section_name,c.name as class_name,
ul.name as user_name,s.name as subject_name
 FROM ".get_school_db().".diary dr
INNER JOIN ".get_school_db().".subject s
ON dr.subject_id = s.subject_id
INNER JOIN ".get_school_db().".class_section sec
ON dr.section_id=sec.section_id
INNER join ".get_school_db().".class c
ON sec.class_id=c.class_id
INNER join ".get_school_db().".departments d
ON c.departments_id = d.departments_id
LEFT join ".get_system_db().".user_login ul
ON ul.user_login_id=dr.submitted_by
  WHERE dr.school_id=".$_SESSION['school_id']." AND dr.assign_date>='".$start_date."' AND dr.assign_date<='".$end_date."' AND dr.section_id=$section_id order by dr.assign_date desc ";
 
  
 	$query=$this->db->query($q)->result_array();
 	if(count($query)==0)
	{
		$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
	}
 	$result_array=array();
 	foreach($query as $row)
 	{
 		$teacher_name=get_teacher_name($row['teacher_id']);
 		
 		$attachment=$row['attachment'];
		$title=$row['title'];
		$date=$row['assign_date'];
		$file_val=$path.$attachment;
		$diary_image="";
		if($attachment!="" && file_exists($file_val))
		{
			$diary_image=$this->attachment_name($attachment,$title,$date);
		}
		
		$result_array=array(
		$teacher_name,
		$row['subject_name'],
		convert_date($row['assign_date']),
		convert_date($row['due_date']),
		$row['task'],
		$row['title'],
		convert_date($row['submission_date']),
		$row['user_name'],
		$diary_image
		
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 
   
   public function manage_leaves_student_csv()
   {
 	$file_name='student_leaves'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$path='./uploads/'.$_SESSION['folder_name'].'/leaves_student/';
	$academic_year=$this->input->post('academic_year7');
	$section_id=$this->input->post('section_id7');
	
	$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$academic_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];
	$start_end_arr[]=get_phrase('start_date').': '.convert_date($start_date).' '.get_phrase('end_date').': '.convert_date($end_date);
 	fputcsv($file,$start_end_arr);
	
 	$q="SELECT ls.*,d.departments_id as departments_id,c.class_id as class_id, cs.section_id as section_id,lc.name as leave_categ_name, s.name as stud_name,d.title as dept_name,cs.title as section_name,c.name as class_name
 FROM ".get_school_db().".leave_student ls
 INNER join ".get_school_db().".student s
 ON ls.student_id=s.student_id
 INNER join ".get_school_db().".class_section cs
 ON s.section_id=cs.section_id
Inner JOIN ".get_school_db().".class c
On cs.class_id=c.class_id
Inner join ".get_school_db().".departments d
On d.departments_id=c.departments_id
INNER join ".get_school_db().".leave_category lc
On ls.leave_category_id=lc.leave_category_id
WHERE ls.school_id=".$_SESSION['school_id']." AND ls.request_date>='".$start_date."' AND ls.request_date<='".$end_date."' AND s.section_id=$section_id
 ORDER BY ls.request_id asc ";
 
 	$queryx=$this->db->query($q)->result_array();
 	if(count($queryx)==0)
 	{
		$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup');      
	}
	$class_sect[]='Class-Section: '.$queryx[0]['dept_name'].' - '.$queryx[0]['class_name'].' - '.$queryx[0]['section_name'];
	fputcsv($file,$class_sect);
	$column_names=array(get_phrase('leave_category'), get_phrase('student_name'), get_phrase('start_date'), get_phrase('end_date'), get_phrase('reason'),get_phrase('request_date'),get_phrase('process_date'),get_phrase('status'),get_phrase('attachment'));
	fputcsv($file,$column_names);
	
 	$result_array=array();
 	foreach($queryx as $row)
 	{
 		if($row['status']==1)
 		{
			$row['status_val']=get_phrase('approved');
		}
		elseif($row['status']==2)
		{
			$row['status_val']=get_phrase('denied');
		}
		
		$attachment=$row['proof_doc'];
		$leave_img_path=$path.$attachment;
		$title=$row['reason'];
		$date=$row['request_date'];
		
		$leave_image="";
		if($attachment!="" && file_exists($leave_img_path))
		{
			$leave_image=$this->attachment_name($attachment,$title,$date);
		}
		$result_array=array(
		$row['leave_categ_name'],
		$row['stud_name'],
		convert_date($row['start_date']),
		convert_date($row['end_date']),
		$row['reason'],
		convert_date($row['request_date']),
		convert_date($process_date=$row['process_date']),
		$row['status_val'],
		$leave_image
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 
   
   public function manage_leaves_staff_csv()
   {
 	$file_name='staff_leaves'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$path='./uploads/'.$_SESSION['folder_name'].'/leaves_staff/';
	$acad_year=$this->input->post('academic_year4');
	$staff_id=$this->input->post('staff_id4');
	$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date']; 
	$start_end_arr[]='Start date:'.convert_date($start_date).' End date:'.convert_date($end_date);
 	fputcsv($file,$start_end_arr);
	
 	$q="SELECT ls.*,lc.name as leave_categ_name, s.name as staff_name,d.title as designation, s.employee_code as employee_code
 FROM ".get_school_db().".leave_staff ls
 INNER join ".get_school_db().".staff s
 ON ls.staff_id=s.staff_id
INNER join ".get_school_db().".leave_category lc
On ls.leave_category_id=lc.leave_category_id
INNER JOIN ".get_school_db().".designation d 
ON s.designation_id=d.designation_id
WHERE ls.school_id=".$_SESSION['school_id']." 
AND ls.request_date>='".$start_date."' AND ls.request_date<='".$end_date."' AND ls.staff_id=$staff_id
 ORDER BY ls.leave_staff_id asc ";
 	$query=$this->db->query($q)->result_array();
$staff_arr[]='Staff: '.$query[0]['staff_name'].' ('.$query[0]['designation'].')';
fputcsv($file,$staff_arr);
if(count($query) == 0)
		{
			$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup');
		} 	
		
$column_names=array(get_phrase('leave_category'), get_phrase('start_date'), get_phrase('end_date'), get_phrase('reason'),get_phrase('request_date'),get_phrase('process_date'),get_phrase('status'),get_phrase('attachments'));
	fputcsv($file,$column_names);
	
 	$result_array=array();
 	foreach($query as $row)
 	{
 		if($row['status']==1)
 		{
			$row['status_val']=get_phrase('approve');
		}
		elseif($row['status']==2)
		{
			$row['status_val']=get_phrase('denied');
		}
		
		
		
		$attachment=$row['proof_doc'];
		$leave_img_path=$path.$attachment;
		$title=$row['reason'];
		$date=$row['request_date'];
		$leave_img="";
		if($attachment!="" && file_exists($leave_img_path))
		{
			$leave_img=$this->attachment_name($attachment,$title,$date);
		}
		
		$result_array=array(
		$row['leave_categ_name'],
		convert_date($row['start_date']),
		convert_date($row['end_date']),
		$row['reason'],
		convert_date($row['request_date']),
		convert_date($process_date=$row['process_date']),
		$row['status_val'],
		$leave_img
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 
   
   
    public function exams_list_csv()
   {
 	$file_name='exams_list'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('name'), get_phrase('start_date'), get_phrase('comment'), get_phrase('end_date'), get_phrase('term'));
	fputcsv($file,$column_names);
	$acad_year=$this->input->post('academic_year12');
 	$q = "select e.*,y.title as term,a.title as year, y.status from " . get_school_db() . ".exam e inner join " . get_school_db() . ".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id inner join " . get_school_db() . ".acadmic_year a on a.academic_year_id=y.academic_year_id where e.school_id=" . $_SESSION[ 'school_id' ] . " AND a.academic_year_id=$acad_year";

 	$query=$this->db->query($q)->result_array();
 	if(count($query)==0)
	{
		$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
	}
 	$result_array=array();
 	foreach($query as $row)
 	{
		$result_array=array(
		$row['name'],
		convert_date($row['start_date']),
		$row['comment'],
		convert_date($row['end_date']),
		$row['term']
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 
   
 
   public function exam_results_csv()
   {
   	$exam_id=$this->input->post('exams_id13');
	$section_id=$this->input->post('section_id13');
	
 	$file_name='exams_result'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$first_row=array();
 	$second_row=array();
 	$a="SELECT yt.*,e.*,yt.start_date as y_start_date,yt.end_date as y_end_date,e.start_date as e_start_date,e.end_date as e_end_date FROM ".get_school_db().".yearly_terms yt
 	INNER JOIN ".get_school_db().".exam e
 	ON yt.yearly_terms_id=e.yearly_terms_id
 	WHERE e.exam_id=$exam_id";
 	$query2=$this->db->query($a)->result_array();
 	foreach($query2 as $query3)
 	{
		$first_row[]=get_phrase('term')." :".$query3['title'].' ('.convert_date($query3['y_start_date']).' to '.convert_date($query3['y_end_date']).')';
		$second_row[]=get_phrase('exam')." :".$query3['name'].' ('.convert_date($query3['e_start_date']).' to '.convert_date($query3['e_end_date']).')';
	}
 	fputcsv($file,$first_row);
 	fputcsv($file,$second_row);
 	
 	$hirearchy=section_hierarchy($section_id);
	$dept[]=get_phrase('class_section')." :".$hirearchy['d'].'-'.$hirearchy['c'].'-'.$hirearchy['s'];
	
	fputcsv($file,$dept);
 	$r="SELECT distinct e.subject_id,s.name as subject_name,s.code FROM ".get_school_db().".exam_routine e
 	INNER JOIN ".get_school_db().".subject s
 	ON e.subject_id=s.subject_id
 	 WHERE e.exam_id=$exam_id AND e.section_id=$section_id AND e.school_id=".$_SESSION['school_id']." ";
 	 
 	$subject_list=$this->db->query($r)->result_array();
 	
 	
 	$column_names[]=get_phrase('student_name');
 	$column_names[]=get_phrase('roll_no');
 	$subject_arr=array();
 	$total_marks_array=array();
 	foreach($subject_list as $subject_list1)
 	{
 		
 		$t_marks=get_total_marks($exam_id,$section_id,$subject_list1['subject_id']);
 		$column_names[]=$subject_list1['subject_name'].'-'.$subject_list1['code'];
 		
 		$subject_arr[$subject_list1['subject_id']]="";
 		$t_marks_arr=$subject_list1['subject_name'].'-'.$subject_list1['code'].' '.' ('.get_phrase('total_marks').': '.$t_marks.')';
 		$G_total+= $t_marks;
 	fputcsv($file,array($t_marks_arr));
 	
 	$total_marks_array[$subject_list1['subject_id']]=$t_marks;
 	
	}
	fputcsv($file,array(get_phrase('grand_total').': '.$G_total));
 	
 	$column_names[]=get_phrase('marks_obtained');
 	$column_names[]=get_phrase('grade');

	fputcsv($file,$column_names);
 	
 	$q="select s.name,s.student_id,s.roll,s.section_id from ".get_school_db().".student s 
WHERE s.school_id =".$_SESSION['school_id']." and s.section_id=".$section_id." and student_status in (".student_query_status().")";

$query=$this->db->query($q)->result_array();
if(count($query)==0)
{
	$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
}

foreach($query as $row)
{
	$result_array=array();
	$stud_name=$row['name'];
	$roll_num=$row['roll'];
	$q="select * from ".get_school_db().".marks m inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id where m.exam_id=".$exam_id." and m.student_id=".$row['student_id']." and m.school_id=".$_SESSION['school_id']." group by m.subject_id";
		$result=$this->db->query($q)->result_array();
		$G_total=0;
		$obt=0;
		$subject_arr1=$subject_arr;
		if(count($result)>0)
		{
			
			foreach($result as $result1)
			{
				$total_marks="";
				if(isset($total_marks_array) && ($total_marks_array>0))
				{
					$total_marks=$total_marks_array[$result1['subject_id']];
					$total_obtained = get_total_obtained($exam_id,$result1['marks_id'],$result1['subject_id']);
               // $total_marks = get_total_marks($exam_id,$row['section_id'],$result1['subject_id']);     	
                $obt+=$total_obtained;
                $G_total+= $total_marks;	
				
				$subject_arr1[$result1['subject_id']]=$total_obtained;
				}
				
				
			}
		}
		$result_array[]=$stud_name;
		$result_array[]=$roll_num;
		foreach($subject_arr1 as $key=>$val)
				{
					
					$result_array[]=$val;
					
				}		
		$G_percent=($obt/$G_total *100);
		$G_grade=get_grade($G_percent).' ('.round($G_percent).'%)';					
		
		$result_array[]=$obt;
		$result_array[]=$G_grade;		
	fputcsv($file,$result_array);	
	
}

	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 

	 public function exam_grades_csv()
   {
 	$file_name='exam_grades'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('name'), get_phrase('grade_point'),get_phrase('mark_from'),get_phrase('mark_upto'), get_phrase('comment'));
	fputcsv($file,$column_names);
 	$this->db->order_by("order_by", "asc");
	$this->db->where('school_id',$_SESSION['school_id']);
	$grades=$this->db->get(get_school_db().'.grade')->result_array();
 	$result_array=array();
 	foreach($grades as $row)
 	{
		$result_array=array(
		$row['name'],
		$row['grade_point'],
		$row['mark_from'],
		$row['mark_upto'],
		$row['comment']
		
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
	
   } 

 public function student_information_csv()
   { 
   	$file_name='student_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$section_id=$this->input->post('section_id2');
   	$academic_year=$this->input->post('academic_year2');
   	$section_hr=section_hierarchy($section_id);
	$section_c_name[]=get_phrase('class_section').': '.$section_hr['d'].'-'.$section_hr['c'].'-'.$section_hr['s'];
	fputcsv($file,$section_c_name);
	
	$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$academic_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];    
	$start_end_arr[]=get_phrase('start_date').': '.convert_date($start_date).' '.get_phrase('end_date').': '.convert_date($end_date);
 	fputcsv($file,$start_end_arr); 
	
	
	
 $column_names=array(
 				get_phrase('name'), get_phrase('date_of_birth'),get_phrase('gender'),get_phrase('religion'),
 				get_phrase('location'),get_phrase('phone'), get_phrase('email'),get_phrase('roll_no'),get_phrase('form_no'),
				get_phrase('admission_date'),get_phrase('id_no'),get_phrase('mobile_no'),get_phrase('emergency_no'),
				get_phrase('alternate_no'),get_phrase('blood_group'),get_phrase('disability'),get_phrase('registration_number'),
				get_phrase('permanent_address'),get_phrase('student_image'),get_phrase('student_id_file'),
				get_phrase('father_name'),get_phrase('father_id_no'),get_phrase('father_contact'),get_phrase('father_occupation'),
				get_phrase('father_id_file'),get_phrase('mother_name'),get_phrase('mother_id_file'),get_phrase('mother_contact'),
				get_phrase('mother_occupation'),get_phrase('mother_id_file'),get_phrase('guardian_name'),
				get_phrase('guardian_id_file'),get_phrase('guardian_contact'),get_phrase('guardian_occupation'),
				get_phrase('guardian_id_file'));
				
fputcsv($file,$column_names);	
 $p="SELECT s.*,cl.title  FROM ".get_school_db().".student s left join ".get_school_db().".city_location cl on cl.location_id=s.location_id  WHERE s.academic_year_id=$academic_year AND s.section_id=$section_id AND s.school_id=".$_SESSION['school_id']." and s.student_status in ('".student_query_status()."')";
$query=$this->db->query($p)->result_array();

$student_array=array();
foreach($query as $row)
{	
$religion=	religion($row['religion']);
	$student_id=$row['student_id'];
	$student_array=array($row['name'],convert_date($row['birthday']),$row['sex'],$religion,$row['title'],$row['phone'],$row['email'],$row['roll'],$row['form_num'],convert_date($row['adm_date']),$row['id_no'],$row['mob_num'],$row['emg_num'],$row['alternative_num'],$row['bd_group'],$row['disability'],$row['reg_num'],$row['p_address']);
	
//attachment code
$path='./uploads/'.$_SESSION['folder_name'].'/student/';
$form_b=$row['id_no'];
$student_image=$row['image'];
$stud_img="";
$stud_img_path=$path.$student_image;
if($student_image!="" && file_exists($stud_img_path))
{
	$stud_img=$this->attachment_name($student_image,$form_b."pic",'');
		
}

$student_array[]=$stud_img;
$stud_cnic="";
$form_b_image=$row['id_file'];
$stud_cnic_path=$path.$form_b_image;
if($row['id_file']!='' && file_exists($stud_cnic_path))
{
	$form_b=$row['id_no'];
	$stud_cnic=$this->attachment_name($form_b_image,$form_b,'');
	$student_array[]=$stud_cnic;	
				
}


	$query1=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation,sp.id_file as father_attachment FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='f' ")->result_array();
//echo $this->db->last_query();	  	  
if(count($query1) > 0)
{
   $student_array[]=$query1[0]['p_name'];
	$student_array[]=$query1[0]['id_no'];
	$student_array[]=$query1[0]['contact'];
	$student_array[]=$query1[0]['occupation'];
	//attachment
	$father_attach=$query1[0]['father_attachment'];
	$father_cnic_path=$path.$father_attach;
	$father_img="";
	if($father_attach!="" && file_exists($father_cnic_path))
	{
		$father_img=$this->attachment_name($father_attach,$form_b."f",'');
	}
	$student_array[]=$father_img;
	
}
else
{
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" "; 

}

	$query2=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation,sp.id_file as mother_attachment FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='m' ")->result_array();
	  //echo $this->db->last_query();
if(count($query2) > 0)
{
			$student_array[]=$query2[0]['p_name'];
			$student_array[]=$query2[0]['id_file'];
			$student_array[]=$query2[0]['contact'];
			$student_array[]=$query2[0]['occupation'];
			//attachment
			$mother_attach=$query2[0]['mother_attachment'];
			$mother_cnic_path=$path.$mother_attach;
			$mother_img="";
			if($mother_attach!="" && file_exists($mother_cnic_path))
			{
				$mother_img=$this->attachment_name($mother_attach,$form_b."m",'');
			}
			$student_array[]=$mother_img;
}
else
{
	$student_array[]="";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
}


	$query3=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation,sp.id_file as guardian_attachment FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='g' ")->result_array();
	  //echo $this->db->last_query();
if(count($query3) > 0)
{
			 $student_array[]=$query3[0]['p_name'];		
			$student_array[]=$query3[0]['id_no'];
			$student_array[]=$query3[0]['contact'];
			$student_array[]=$query3[0]['occupation'];
			
			//attachment
			$guardian_attach=$query3[0]['guardian_attachment'];
			$guard_cnic_path=$path.$guardian_attach;
			$guardian_img="";
			if($guardian_attach!="" && file_exists($guard_cnic_path))
			{
				$guardian_img=$this->attachment_name($guardian_attach,$form_b."g",'');
			}
			$student_array[]=$guardian_img;
	
	
}
else
{
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";

}

	$result_array=$student_array;
	
fputcsv($file, $result_array);
} 


$file=$file_name;
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    file_delete($file);
}

}

   public function stud_attendance_csv()
   {
   	$acad_year=$this->input->post('academic_year9');
   	$section_id=$this->input->post('section_id9');
 	$file_name='student_attendance'.time().".csv";
 	$file = fopen($file_name, 'w');
 	
	$p="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($p)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];
	$start_end_arr[]=get_phrase('start_date').': '.convert_date($start_date).' '.get_phrase('end_date').': '.convert_date($end_date);
 	fputcsv($file,$start_end_arr);
	
	$column_names=array(get_phrase('student_name'),get_phrase('date'), get_phrase('day'), get_phrase('attendance'));
	fputcsv($file,$column_names);
	
 	$q="SELECT s.name as student_name,a.status as status,a.date as date FROM ".get_school_db().".attendance a
 	INNER JOIN ".get_school_db().".student s
 	ON a.student_id=s.student_id
 	INNER join ".get_school_db().".class_section cs
 	ON s.section_id=cs.section_id
 	 WHERE  a.date >='".$start_date."' AND a.date<='".$end_date."'  AND a.school_id=".$_SESSION['school_id']." AND cs.section_id=$section_id";
 	

$qur_red=$this->db->query($q)->result_array();
	if(count($qur_red)==0)
	{
		$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
	}
	
 	$result_array=array();
 	foreach($qur_red as $row)
 	{
 		if($row['status']==1)
 		{
			$row['status']=get_phrase('present');
		}
		elseif($row['status']==2)
		{
			$row['status']=get_phrase('absent');
		}
		elseif($row['status']==3)
		{
			$row['status']=get_phrase('leave');
		}
		$date=$row['date'];
		$day = date( "l", strtotime($date));
		$result_array=array(
		$row['student_name'],
		convert_date($row['date']),
		$day,
		$row['status']
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   }  
   
    public function staff_attendance_csv()
   {
   	$file_name='staff_attendance'.time().".csv";
 	$file = fopen($file_name, 'w');
   	$staff_id=$this->input->post('staff_id');
   	$academic_year=$this->input->post('academic_year');
   
   $p="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$academic_year." ";
   $acad_array=$this->db->query($p)->result_array();
   $start_date=$acad_array[0]['start_date'];
   $end_date=$acad_array[0]['end_date'];
   $start_end_arr[]=get_phrase('start_date').': '.convert_date($start_date).' '.get_phrase('end_date').': '.convert_date($end_date);
 	fputcsv($file,$start_end_arr);
	
	$q="SELECT s.name as staff_name,d.title as designation,ast.status,ast.date
	FROM ".get_school_db().".staff s
	INNER JOIN ".get_school_db().".designation d
	ON d.designation_id = s.designation_id
	INNER JOIN ".get_school_db().".attendance_staff ast
	ON s.staff_id=ast.staff_id
	 WHERE ast.staff_id=$staff_id AND ast.date>='".$start_date."' AND ast.date<='".$end_date."' AND ast.school_id=".$_SESSION['school_id']."";
$qur_red=$this->db->query($q)->result_array();

$staff_arr[]=get_phrase('staff').': '.$qur_red[0]['staff_name'].' ('.$qur_red[0]['designation'].')';
fputcsv($file,$staff_arr);
$column_names=array(get_phrase('date'), get_phrase('day'), get_phrase('attendance'));
	fputcsv($file,$column_names);
 	$result_array=array();
 	foreach($qur_red as $row)
 	{
 		if($row['status']==1)
 		{
			$row['status']=get_phrase('present');
		}
		elseif($row['status']==2)
		{
			$row['status']=get_phrase('absent');
		}
		elseif($row['status']==3)
		{
			$row['status']=get_phrase('leave');
		}
		$date=$row['date'];
		$day = date( "l", strtotime($date));
		$result_array=array(
		
		convert_date($row['date']),
		$day,
		$row['status']
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   }  
   
    public function staff_evaluation_csv()
   {  
   $quest=array();
 	$file_name='staff_evaluation'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$path='./uploads/'.$_SESSION['folder_name'].'/staff_evaluation/';
 	
	$staff_id=$this->input->post('staff_id5');
   	$acad_year=$this->input->post('academic_year5');
   $r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date']; 
	
	$staf_eval_form="SELECT * FROM ".get_school_db().".staff_evaluation_form WHERE status=1 AND school_id=".$_SESSION['school_id']."";
	//exit;
	
$questions=$this->db->query($staf_eval_form)->result_array();
$count_val=0;
foreach($questions as $quest_row)
{
	$count_val++;
	fputcsv($file,array(get_phrase('question').' '.$count_val.': '.$quest_row['title']));
	//$quest[$quest_row['staff_eval_form_id']]=$quest_row['title'];
}

$p="SELECT se.*,s.name as staff_name,se.attachment as staff_attachment FROM  ".get_school_db().".staff_evaluation se INNER JOIN ".get_school_db().".staff s 
 ON se.staff_id=s.staff_id
 WHERE se.staff_id=".$staff_id." AND se.school_id=".$_SESSION['school_id']." AND se.evaluation_date >= '".$start_date."' AND se.evaluation_date <='".$end_date."' ";
 $query=$this->db->query($p)->result_array();
 if(count($query)==0)
 {
 	$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup');      
 }
$staff_ans_query=$this->db->query($p)->result_array();
if(count($staff_ans_query) > 0)
{
	$staff_name[]=get_phrase('teacher_name').': '.$staff_ans_query[0]['staff_name'];
	fputcsv($file,$staff_name);
}

$column_names=array(get_phrase('evaluation_date'), get_phrase('ratings'), get_phrase('remarks'));
$count=count($questions);

for($i=1;$i<=$count;$i++)
{
	//$column_names[]='Question '.$i;
	$column_names[]=get_phrase('answer').' '.$i;
	$column_names[]=get_phrase('remarks').' '.$i;
}
	$column_names[]=get_phrase('attachments');
fputcsv($file,$column_names);
	
if(count($staff_ans_query))
{
	
foreach($staff_ans_query as $staf_ans)
{
	$question_array=array();
	$question_array[]=convert_date($staf_ans['evaluation_date']);
	$question_array[]=$staf_ans['answers'];
	$question_array[]=$staf_ans['remarks'];
	
	
$a="SELECT * FROM  ".get_school_db().".staff_evaluation_answers WHERE staff_eval_id=".$staf_ans['staff_eval_id']." AND school_id=".$_SESSION['school_id']." ";
$result=$this->db->query($a)->result_array();
if(count($result)>0)
{
foreach($result as $staf_ans1)	
{	
	//$question_array[]=$quest[$staf_ans1['staff_eval_form_id']];
	$question_array[]=$staf_ans1['answers'];
	$question_array[]=$staf_ans1['remarks'];	
	
	
}
}

/*for attachment*/
	$attachment=$staf_ans['staff_attachment'];
	$title=$staf_ans['staff_name'];
	$date=$staf_ans['evaluation_date'];
	$file_img="";
	$file_img=$path.$attachment;
	$attachment_name="";
if($attachment!="" && file_exists($file_img))
		{
			 $attachment_name=$this->attachment_name($attachment,$title,$date);
			$question_array[]=$attachment_name;
			
		}
//
//echo "<pre>";
//print_r($question_array);
fputcsv($file,$question_array);

//print_r($question_array);

}
}

	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 

public function student_evaluation_csv()
   {
   	$section_id=$this->input->post('section_id6');
   	$exam_id=$this->input->post('exam_id6');
   
   $quest=array();
 	$file_name='student_evaluation'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$path='./uploads/'.$_SESSION['folder_name'].'/student_evaluation/';
 	 $section_hr=section_hierarchy($section_id);
	$section_c_name=get_phrase('class_section').': '.array($section_hr['d'].'-'.$section_hr['c'].'-'.$section_hr['s']);
 	fputcsv($file,$section_c_name);
	
 $p="SELECT se.*,s.name as student_name,s.roll as roll_no,e.name as exam_name,e.start_date as exam_start_date, e.end_date as exam_end_date FROM  ".get_school_db().".student_evaluation se INNER JOIN ".get_school_db().".student s 
 ON se.student_id=s.student_id
 INNER JOIN ".get_school_db().".exam e
 ON se.exam_id=e.exam_id
 WHERE s.section_id=".$section_id." AND se.school_id=".$_SESSION['school_id']." AND se.exam_id=$exam_id  ";
 
 $query=$this->db->query($p)->result_array();
 if(count($query)==0)
 {
 	$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
	redirect(base_url().'backup/manage_backup');     
 }
$student_ans_query=$this->db->query($p)->result_array();
$exam_arr[]='Exam: '.$student_ans_query[0]['exam_name'].' ('.date('d-M-Y',strtotime($student_ans_query[0]['exam_start_date'])).' to '.date('d-M-Y',strtotime($student_ans_query[0]['exam_end_date'])).')';
fputcsv($file,$exam_arr);

	$stud_eval_form="SELECT * FROM ".get_school_db().".student_evaluation_form WHERE status=1 AND school_id=".$_SESSION['school_id']."";
	
$questions=$this->db->query($stud_eval_form)->result_array();
$count_val=0;
foreach($questions as $quest_row)
{
	$count_val++;
	//$quest[$quest_row['eval_id']]=$quest_row['title'];
	fputcsv($file,array(get_phrase('question').' '.$count_val.': '.$quest_row['title']));
}

$column_names=array(get_phrase('student_name'),get_phrase('roll_no'), get_phrase('ratings'), get_phrase('remarks'));

$count=count($questions);

for($i=1;$i<=$count;$i++)
{
	//$column_names[]='Question '.$i;
	$column_names[]=get_phrase('answer').' '.$i;
	$column_names[]=get_phrase('remarks').' '.$i;
}
	$column_names[]=get_phrase('attachments');
fputcsv($file,$column_names);


if(count($student_ans_query))
{
foreach($student_ans_query as $stud_ans)
{
	$question_array=array();
	//attachment
	$attachment=$stud_ans['attachment'];
	$title=$stud_ans['student_name'];
	$file_val=$path.$attachment;
	$stud_eval_image="";
	if($attachment!="" && file_exists($file_val))
	{
		$stud_eval_image=$this->attachment_name($attachment,$title,'');
	}
	
	
	$question_array[]=$stud_ans['student_name'];
	$question_array[]=$stud_ans['roll_no'];
	$question_array[]=$stud_ans['answers'];
	$question_array[]=$stud_ans['remarks'];
$a="SELECT * FROM  ".get_school_db().".student_evaluation_answers WHERE stud_eval_id=".$stud_ans['stud_eval_id']." AND school_id=".$_SESSION['school_id']." ";
$result=$this->db->query($a)->result_array();
if(count($result)>0)
{
foreach($result as $stud_ans1)	
{	
	$question_array[]=$stud_ans1['answers'];
	$question_array[]=$stud_ans1['remarks'];	
}
}
	
$question_array[]=$stud_eval_image;
fputcsv($file,$question_array);
}
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 


 public function candidate_information_csv()
   { 
   	$file_name='candidates_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$section_id=$this->input->post('section_id3');
 	$section_hr=section_hierarchy($section_id);
	$section_c_name[]=get_phrase('class_section').': '.$section_hr['d'].'-'.$section_hr['c'].'-'.$section_hr['s'];
	fputcsv($file,$section_c_name);
	
 	
 
	
$academic_year=$this->input->post('academic_year3');

$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$academic_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];    
	$start_end_arr[]='Start date:'.convert_date($start_date).' End date:'.convert_date($end_date);
 	fputcsv($file,$start_end_arr); 
$column_names=array(
				get_phrase('name'), get_phrase('date_of_birth'),get_phrase('gender'),get_phrase('religion'),
				get_phrase('location'),get_phrase('phone'), get_phrase('email'),get_phrase('roll_no'),
				get_phrase('form_no'),get_phrase('admission_date'),get_phrase('id_no'),get_phrase('mobile_no'),
				get_phrase('emergency_no'),get_phrase('alternate_no'),get_phrase('blood_group'),get_phrase('disability'),
				get_phrase('registration_number'),get_phrase('permanent_address'),get_phrase('student_image'),
				get_phrase('student_id_file'),get_phrase('father_name'),get_phrase('father_id_no'),get_phrase('father_contact'),
				get_phrase('father_occupation'),get_phrase('father_id_file'),get_phrase('mother_name'),
				get_phrase('mother_id_no'),get_phrase('mother_contact'),get_phrase('mother_occupation'),
				get_phrase('mother_id_file'),get_phrase('guardian_name'),get_phrase('guardian_id_no'),
				get_phrase('guardian_contact'),get_phrase('guardian_occupation'),get_phrase('guardian_id_file'));
				
fputcsv($file,$column_names);
  $p="SELECT s.*,cl.title  FROM ".get_school_db().".student s left join ".get_school_db().".city_location cl on cl.location_id=s.location_id  WHERE s.school_id=".$_SESSION['school_id']." and s.student_status<10 AND s.system_date>='".$start_date."' AND s.system_date<='".$end_date."' ";
$queryx=$this->db->query($p)->result_array();
$student_array=array();
foreach($queryx as $row)
{	

				
				
//echo $row['adm_date'];
$religion=	religion($row['religion']);
	$student_id=$row['student_id'];
	$birthday=convert_date($row['birthday']);
	$adm_date=convert_date($row['adm_date']);
	$student_array=array($row['name'],$birthday,$row['sex'],$religion,$row['title'],$row['phone'],$row['email'],$row['roll'],$row['form_num'],$adm_date,$row['id_no'],$row['mob_num'],$row['emg_num'],$row['alternative_num'],$row['bd_group'],$row['disability'],$row['reg_num'],$row['p_address']);
	
//attachment code
$path='./uploads/'.$_SESSION['folder_name'].'/student/';
$form_b=$row['id_no'];
$student_image=$row['image'];
$stud_img="";
$stud_img_path=$path.$student_image;
if($student_image!="" && file_exists($stud_img_path))
{
	$stud_img=$this->attachment_name($student_image,$form_b."pic",'');
		
}

$student_array[]=$stud_img;
$stud_cnic="";
$form_b_image=$row['id_file'];
$stud_cnic_path=$path.$form_b_image;
if($row['id_file']!='' && file_exists($stud_cnic_path))
{
	$form_b=$row['id_no'];
	$stud_cnic=$this->attachment_name($form_b_image,$form_b,'');
	$student_array[]=$stud_cnic;	
				
}
/*attachment ends*/

	$query1=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation,sp.id_file as father_attachment FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='f' ")->result_array();
	  

	  
if(count($query1) > 0)
{

   $student_array[]=$query1[0]['p_name'];
	$student_array[]=$query1[0]['id_no'];
	$student_array[]=$query1[0]['contact'];
	$student_array[]=$query1[0]['occupation'];
	//attachment
	$father_attach=$query1[0]['father_attachment'];
	$father_cnic_path=$path.$father_attach;
	$father_img="";
	if($father_attach!="" && file_exists($father_cnic_path))
	{
		$father_img=$this->attachment_name($father_attach,$form_b."f",'');
	}
	$student_array[]=$father_img;
	}
else
{
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" "; 

}

	$query2=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation,sp.id_file as mother_attachment FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='m' ")->result_array();
if(count($query2) > 0)
{
	
			$student_array[]=$query2[0]['p_name'];
			$student_array[]=$query2[0]['id_no'];
			$student_array[]=$query2[0]['contact'];
			$student_array[]=$query2[0]['occupation'];
	
			//attachment
			$mother_attach=$query2[0]['mother_attachment'];
			$mother_cnic_path=$path.$mother_attach;
			$mother_img="";
			if($mother_attach!="" && file_exists($mother_cnic_path))
			{
				$mother_img=$this->attachment_name($mother_attach,$form_b."m",'');
			}
			$student_array[]=$mother_img;
	
}
else
{
	$student_array[]="";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";

}


	$query3=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation,sp.id_file as guardian_attachment FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='g' ")->result_array();
if(count($query3) > 0)
{

			 $student_array[]=$query3[0]['p_name'];
			$student_array[]=$query3[0]['id_no'];
			$student_array[]=$query3[0]['contact'];
			$student_array[]=$query3[0]['occupation'];
			
			//attachment
			$guardian_attach=$query3[0]['guardian_attachment'];
			$guard_cnic_path=$path.$guardian_attach;
			$guardian_img="";
			if($guardian_attach!="" && file_exists($guard_cnic_path))
			{
				$guardian_img=$this->attachment_name($guardian_attach,$form_b."g",'');
			}
			$student_array[]=$guardian_img;
	
	
}
else
{
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";

}

	$result_array=$student_array;
	
fputcsv($file, $result_array);
} 


$file=$file_name;



if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);

    file_delete($file);
}

}

public function designation_csv()
   {
 	$file_name='designation'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('title'),get_phrase('parent'), get_phrase('is_teacher'));
	fputcsv($file,$column_names);
 	$qry = "SELECT d2.title as child,d1.title as parent,d2.is_teacher
		FROM ".get_school_db().".designation d1,".get_school_db().".designation d2
		WHERE d2.parent_id=d1.designation_id";
		
		$res_array = $this->db->query($qry)->result_array();
 	$result_array=array();
 	foreach($res_array as $row)
 	{
 		if($row['is_teacher']==1)
 		{
			$val=get_phrase('yes');
		}
		elseif($row['is_teacher']==0)
		{
			$val=get_phrase('no');
		}
		$result_array=array(
		$row['child'],
		$row['parent'],
		$val
		
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 


 public function staff_csv()
   {
 	$file_name='staff'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(
					get_phrase('staff_name'), get_phrase('designation'),get_phrase('is_teacher'), 
					get_phrase('employee_code'), get_phrase('id_no'), get_phrase('dob'),get_phrase('gender'),
					get_phrase('religion'),get_phrase('blood_group'),get_phrase('phone_no'),get_phrase('mobile_no'),
					get_phrase('emergency_no'),get_phrase('postal_address'),get_phrase('permanent_address'),
					get_phrase('attachments'));
	
	fputcsv($file,$column_names);
 	$qry = "SELECT s.*, d.title as designation, d.is_teacher
		FROM ".get_school_db().".staff s 
		INNER JOIN ".get_school_db().".designation d
		ON d.designation_id = s.designation_id
		WHERE 
		$policy_filter
		s.school_id=".$_SESSION['school_id']." 
		ORDER BY s.staff_id ASC";
		
		$res_array = $this->db->query($qry)->result_array();
		$res_array[0]['name'].' ('.$res_array[0]['designation'];
 	$result_array=array();
 	foreach($res_array as $row)
 	{
 		//attachment name
 		$attachment=$row['staff_image'];
		$title=$row['name'];
		$emp_code=$row['employee_code'];
		$attachment_name="";
		if($attachment!="")
		{
			$attachment_name=$this->attachment_name($attachment,$title,$emp_code);
		}
		
 		if($row['is_teacher']==1)
 		{
			$val=get_phrase('yes');
		}
		elseif($row['is_teacher']==0)
		{
			$val=get_phrase('no');
		}
		$result_array=array(
		$row['name'],
		$row['designation'],
		$val,
		$row['employee_code'],
		$row['id_no'],
		convert_date(date('d-m-Y', strtotime($row['dob']))),
		ucfirst($row['gender']),
		religion($row['religion']),
		$row['blood_group'],
		$row['phone_no'],
		$row['mobile_no'],
		$row['emergency_no'],
		$row['postal_address'],
		$row['permanent_address'],
		$attachment_name
		);
		fputcsv($file,$result_array);
	}
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 
   
   public function acad_planner_csv()
   {
   	$file_name='academic_planner'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$path='./uploads/'.$_SESSION['folder_name'].'/academic_planner/';
   	$subject_id=$this->input->post('subject_id1');
   	$subj_name[]='Subject: '.get_subject_name($subject_id);
   	fputcsv($file,$subj_name);
   	$academic_year=$this->input->post('academic_year1');
	if(isset($subject_id) && ($subject_id>0))
	{
	 $subj_query=" AND ap.subject_id=$subject_id";
	}
	
	$p="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$academic_year." ";
   $acad_array=$this->db->query($p)->result_array();
  
   $start_date=$acad_array[0]['start_date'];
   $end_date=$acad_array[0]['end_date'];
   $start_end_arr[]='Start date: '.convert_date($start_date).' End date: '.convert_date($end_date);
 	fputcsv($file,$start_end_arr);

$school_id=$_SESSION['school_id'];
$query="SELECT ap.planner_id as planner_id, ap.title as title, ap.start as start, ap.objective as objective,ap.assesment as assesment,ap.requirements as requirements,ap.required_time as required_time, ap.attachment as attachment, s.name as subject_name, s.code as code,s.subject_id as subject_id,ap.detail as detail
FROM ".get_school_db().".academic_planner ap
INNER JOIN ".get_school_db().".subject s 
ON ap.subject_id = s.subject_id
Where ap.school_id=$school_id AND ap.start>='".$start_date."' AND ap.start<='".$end_date."' ".$subj_query; 
 
$qur_red=$this->db->query($query)->result_array();

$plan=array();
foreach($qur_red as $red){
$plan[$red['planner_id']]=array('planner_id'=>$red['planner_id'], 'title'=>$red['title'],'detail'=>$red['detail'],'objective'=>$red['objective'],'assesment'=>$red['assesment'],'requirements'=>$red['requirements'],'required_time'=>$red['required_time'],'attachment'=>$red['attachment'], 'subject_name'=>$red['subject_name'],'subject_id'=>$red['subject_id'],'code'=>$red['code'],'start'=>$red['start']);
}

 	
 	$column_names=array(
					get_phrase('date'), get_phrase('title'), get_phrase('detail'), get_phrase('objective'),
					get_phrase('assesment'),get_phrase('requirements'),get_phrase('required_time_mins'),get_phrase('attachments'));
	fputcsv($file,$column_names);
	
		
		$result_array=array();
	
	foreach($plan as $key=>$val )
    { 
	$attachment=$val['attachment'];
    $title=$val['title'];
	$date=$val['start'];
	$file_val=$path.$attachment;
	$acad_image="";
	if($attachment!="" && file_exists($file_val))
	{
		$acad_image=$this->attachment_name($attachment,$title,$date);
	} 
    
    $result_array=array
    		(
    			convert_date($val['start']),
    			//$val['subject_name'].'-'.$val['code'],
    			$val['title'],
    			$val['detail'],
    			$val['objective'],
    			$val['assesment'],
    			$val['requirements'],
    			$val['required_time'],
    			$acad_image
    		);
    	fputcsv($file,$result_array);		
	}
	
	$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
   } 
   
public function time_table_csv()
{
	$file_name='time_table'.time().".csv";
 	$file = fopen($file_name, 'w');
	$section_id=$this->input->post('section_id');
   	$section_detail=section_hierarchy($section_id);
 	$dept=$section_detail['d'];
 	$class=$section_detail['c'];
 	$section=$section_detail['s'];
 	

	
 $settings = "select distinct cs.*
from ".get_school_db().".class_routine_settings cs   
where  
cs.school_id=".$_SESSION['school_id']." 
and cs.section_id = ".$section_id."
and cs.is_active = 1
order by cs.start_date desc
	";
$class_rout_set=$this->db->query($settings)->result_array();
$c_rout_sett_id=0;
if(count($class_rout_set) > 0)
{
	$c_rout_sett_id=$class_rout_set[0]['c_rout_sett_id'];
}

$CRS_arr = $this->db->query("select cr.* FROM ".get_school_db().".class_routine cr 
	WHERE 
	cr.school_id=".$_SESSION['school_id']." 
	AND cr.c_rout_sett_id=".$c_rout_sett_id."
		")->result_array();
$routine_arr = array();
$timing=array();

foreach($CRS_arr as $crs_row)
{
	$subj_display="";
	$subj_components="";
	$teach_array="";
	if(isset($crs_row['subject_id']) && ($crs_row['subject_id']>0))
	{
		$subj_display=get_subject_name($crs_row['subject_id']);
			$comps=$crs_row['subject_components'];
		 	$subj_components=subject_components($comps);
		 	if($subj_components!="")
		 	{
		 		$subj_components=' ('.str_replace('<br/>',',',$subj_components).')';
		 	
		 	}
		
		$query3=" select sta.name AS teacher_name 
		from ".get_school_db().".time_table_subject_teacher ttst 
		inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
		inner join ".get_school_db().".staff sta on sta.staff_id=st.teacher_id 
		where ttst.school_id=".$_SESSION['school_id']."  and st.subject_id=".$crs_row['subject_id']." and ttst.class_routine_id=".$crs_row['class_routine_id']."";
		
$res = $this->db->query($query3)->result_array();
$teachers=array();
	if(sizeof($res)>0)
	{									
		foreach($res as $rows)
		{
			$teachers[]=$link.$rows['teacher_name'];
		}

	$teach_array=' ['.implode(',',$teachers).']';
	}						 	
	}

	$routine_arr[$crs_row['day']][$crs_row['period_no']]['subject']=$subj_display;
	$routine_arr[$crs_row['day']][$crs_row['period_no']]['component']=$subj_components;
	$routine_arr[$crs_row['day']][$crs_row['period_no']]['teacher']=$teach_array;
}	

if (count($class_rout_set) > 0)
{
	
 	$column_names=array($dept.'/'.$class.'/'.$section);
	fputcsv($file,$column_names);
	
	foreach($class_rout_set as $row)
	{
		
		$no_of_periods= $row['no_of_periods'];
		$period_duration= $row['period_duration'];
		$start_time = strpos($row['start_time'],':')?$row['start_time']:($row['start_time'].':00');
		$end_time=strpos($row['end_time'],':')?$row['end_time']:($row['end_time'].':00');
		$assembly_duration=$row['assembly_duration'];
		$break_duration=$row['break_duration'];
		$break_after_period=$row['break_after_period'];
		$c_rout_setting_id=$row['c_rout_sett_id'];
		$start_date=$row['start_date'];
		$end_date=$row['end_date'];
		$is_active=$row['is_active'];
		$period_duration_type=$row['period_duration_type'];
		$period_duration_array=array();
		$period_array=array();
		if(($period_duration_type==1) || ($period_duration_type==0))
		{
			for($i=0;$i<=($no_of_periods-1);$i++)
			{
				$period_duration_array[$i]=$period_duration;
			}
		}
		elseif($period_duration_type==2)
		{
			$period_duration_array=explode(",",$period_duration);
		}
		
		        $row1=array();
				$row2=array();
				$row1[]=get_phrase('period');
				$row2[]=get_phrase('time');
		        if($assembly_duration > 0)
				{
					$row1[]=get_phrase('assembly');
					
					$s = $start_time;
					$period = strtotime($start_time) + strtotime('00:'.$assembly_duration) - strtotime('00:00');
					$period_new = date('H:i', $period);
					$e=$period_new;
					
					$row2[]=$s.'-'.$e;
				}
				
				
				for($i=1;$i<=$no_of_periods;$i++)
				{
					$row1[]=$i;
					$s=$period_new;
					$period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($period_duration_array[$i-1])) - strtotime('00:00');
					$period_new = date('H:i', $period_new);
					$e=$period_new;
					$row2[]=$s.'-'.$e;

if(($break_after_period > 0) && ($break_after_period==$i))
					{
						$row1[]='break';
						
						$s=$period_new;
						$period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration)) - strtotime('00:00');
						$period_new = date('H:i', $period_new);
						$e=$period_new;
						$row2[]=$s.'-'.$e;
					}
				}
				fputcsv($file,$row1);
				fputcsv($file,$row2);
				
				$row3=array();
				$day='sunday';
				if (isset($routine_arr[$day]) && count($routine_arr[$day])>0)
				{
					$row3[]=ucfirst($day);
					echo $assembly_duration;
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					
					for($i=0;$i<=$no_of_periods;$i++)
					{
						if (isset($routine_arr[$day][$i]) && count($routine_arr[$day][$i])>0)
						{
							$row3[]=$routine_arr[$day][$i]['subject'].$routine_arr[$day][$i]['component'].$routine_arr[$day][$i]['teacher'];
						}
						else
						{
							$row3[]='';
						}
						
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				else
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						$row3[]='';
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				
				
				$row3=array();
				$day='monday';
				if (isset($routine_arr[$day]) && count($routine_arr[$day])>0)
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						if (isset($routine_arr[$day][$i]) && count($routine_arr[$day][$i])>0)
						{
							$row3[]=$routine_arr[$day][$i]['subject'].$routine_arr[$day][$i]['component'].$routine_arr[$day][$i]['teacher'];
							
							
						}
						else
						{
							$row3[]='';
						}
						
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				else
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						$row3[]='';
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				
				fputcsv($file,$row3);
				
				$row3=array();
				$day='tuesday';
				if (isset($routine_arr[$day]) && count($routine_arr[$day])>0)
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						if (isset($routine_arr[$day][$i]) && count($routine_arr[$day][$i])>0)
						{
							$row3[]=$routine_arr[$day][$i]['subject'].$routine_arr[$day][$i]['component'].$routine_arr[$day][$i]['teacher'];
							
							
						}
						else
						{
							$row3[]='';
						}
						
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				else
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						$row3[]='';
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				
				fputcsv($file,$row3);
				
				
				$row3=array();
				$day='wednesday';
				if (isset($routine_arr[$day]) && count($routine_arr[$day])>0)
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						if (isset($routine_arr[$day][$i]) && count($routine_arr[$day][$i])>0)
						{
							$row3[]=$routine_arr[$day][$i]['subject'].$routine_arr[$day][$i]['component'].$routine_arr[$day][$i]['teacher'];
							
							
						}
						else
						{
							$row3[]='';
						}
						
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				else
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						$row3[]='';
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				
				fputcsv($file,$row3);
				
				$row3=array();
				$day='thursday';
				if (isset($routine_arr[$day]) && count($routine_arr[$day])>0)
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						if (isset($routine_arr[$day][$i]) && count($routine_arr[$day][$i])>0)
						{
							$row3[]=$routine_arr[$day][$i]['subject'].$routine_arr[$day][$i]['component'].$routine_arr[$day][$i]['teacher'];
							
							
						}
						else
						{
							$row3[]='';
						}
						
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				else
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						$row3[]='';
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				
				//fputcsv($file,$row3);
				
				$row3=array();
				$day='friday';
				if (isset($routine_arr[$day]) && count($routine_arr[$day])>0)
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						if (isset($routine_arr[$day][$i]) && count($routine_arr[$day][$i])>0)
						{
							$row3[]=$routine_arr[$day][$i]['subject'].$routine_arr[$day][$i]['component'].$routine_arr[$day][$i]['teacher'];
							
							
						}
						else
						{
							$row3[]='';
						}
						
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				else
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						$row3[]='';
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				fputcsv($file,$row3);
				
				
				
				$row3=array();
				$day='saturday';
				if (isset($routine_arr[$day]) && count($routine_arr[$day])>0)
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						if (isset($routine_arr[$day][$i]) && count($routine_arr[$day][$i])>0)
						{
							$row3[]=$routine_arr[$day][$i]['subject'].$routine_arr[$day][$i]['component'].$routine_arr[$day][$i]['teacher'];
							
							
						}
						else
						{
							$row3[]='';
						}
						
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				else
				{
					$row3[]=ucfirst($day);
					if($assembly_duration > 0)
					{
						$row3[]=get_phrase('assembly');
					}
					for($i=1;$i<=$no_of_periods;$i++)
					{
						$row3[]='';
						
						if(($break_after_period > 0) && ($break_after_period==$i))
						{
							$row3[]=get_phrase('break');
						}
					}
				}
				fputcsv($file,$row3);
				
	}
	
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
}
  

 } 
 
  
    public function subject_csv()
   {
   	$file_name='subject_'.time().".csv";
   	$file = fopen($file_name, 'w');
   	
 	$column_names=array(get_phrase('subject'), get_phrase('components'), get_phrase('teacher'),get_phrase('section'));
	fputcsv($file,$column_names);
   	
 	$q="SELECT s.*,sc.title as subj_categ_title FROM ".get_school_db().".subject s
		LEFT JOIN ".get_school_db().".subject_category sc
		ON s.subj_categ_id=sc.subj_categ_id
		 WHERE s.school_id=".$_SESSION['school_id']. $subj_query;
$subjects=$this->db->query($q)->result_array();	
$subj_array=array();	 
foreach($subjects as $row)
{
	if($row['code'])
	{
		$code=' ('.$row['code'].')';
	}
	if($row['subj_categ_title']!="")
	{
		$sub_cat_title='['.$row['subj_categ_title'].']';
	}
	$subj_array[$row['subject_id']]['subject']=array
	('subj_name'=>$row['name'],
	'code'=>$code
	);
	$subj_array[$row['subject_id']]['category']=$sub_cat_title;
 
 $compArr=$this->db->query("select sc.subject_component_id as subject_component_id, sc.title AS component, sc.percentage,sc.subject_id from ".get_school_db().".subject_components sc where sc.subject_id=".$row['subject_id']." AND sc.school_id=".$_SESSION['school_id']." 
                                                order by sc.subject_component_id desc
                                                ")->result_array();
                                               
                                            if (count($compArr) > 0)
                                            {
                                                foreach ($compArr as $key => $comp) 
                                                {
                                                    
  
          $subj_array[$row['subject_id']]['components'][$comp['subject_component_id']]=$comp['component'].' ('.$comp['percentage'].' %)';                     
                                                    
                                                }
                                            }                              
$teachers=subject_teacher($row['subject_id']);
if(sizeof($teachers)>0)
{
	foreach($teachers as $all){
										
    $t_name=$all['teacher_name']; 
  
                     $subj_array[$row['subject_id']]['teacher'][$all['teacher_id']]=$t_name.' ('.$all['designation'].')';                                                       
                                             }
}	
$ret_value= get_subject_section($row['subject_id']);  

$new = array();
foreach ($ret_value as $a){
    $new[$a['department_name']][$a['section_id']] = $a['class_name'].' - '.$a['section_name'];
}

foreach($new as $dep=>$secArry)
{
	foreach($secArry as $k=>$section)
	{
		$section;
		$subj_array[$row['subject_id']]['section'][$k]=$section;
	}
}


}

	foreach($subj_array as $val)
	{
		$subj_str="";
		if(isset($val['subject']) && count($val['subject']) > 0)
		{
			$subj_str=implode(' ',$val['subject']);
			
		}
		$categ_str="";
		if(isset($val['category']) && count($val['subject']) > 0)
		{
			$categ_str=$val['category'];
		}
		$comp_str="";
		if(isset($val['components']) && count($val['components']) > 0)
		{
			$comp_str=implode(' , ',$val['components']);
		}
		$teach_str="";
		
		if(isset($val['teacher']) && count($val['teacher']) > 0)
		{
			$teach_str=implode(' , ',$val['teacher']);
		}
		$sect_str="";
		if(isset($val['section']) && count($val['section']) > 0)
		{
			$sect_str=implode(' , ',$val['section']);
		}
		
		$result_array=array(
		$subj_str,
		$categ_str,
		$comp_str,
	 	$teach_str,
	 	$sect_str
		);
		
fputcsv($file,$result_array);
	}
	
	$file=$file_name;
	


if (file_exists($file))
{
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);

    file_delete($file);
}

   } 
   
 public function section_csv()
   {
   	
 	$file_name='section_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('section'), get_phrase('department'),get_phrase('class'), get_phrase('teacher'),
	get_phrase('description'));
	fputcsv($file,$column_names);
 	 $school_id=$_SESSION['school_id'];
        
    $que_listing =   "select cs.*,c.name as class_name, d.title as dpp_title , t.name as teacher_name,cs.discription,ds.title as designation,ds.is_teacher as is_teacher from
".get_school_db().".class_section cs  
inner join ".get_school_db().".class c on cs.class_id=c.class_id 
left join ".get_school_db().".staff t on t.staff_id=cs.teacher_id 
inner join ".get_school_db().".departments d on c.departments_id=d.departments_id
LEFT JOIN ".get_school_db().".designation ds ON t.designation_id=ds.designation_id
where cs.school_id=$school_id order by dpp_title ASC";
$students=$this->db->query($que_listing)->result_array();
foreach($students as $row)
{
	$title=$row['title'];
	$dept=$row['dpp_title'];
	$class=$row['class_name'];
	$teacher_name=$row['teacher_name'];
	if($row['designation']!="")
	{
  		$teacher_name.=" (".$row['designation'].") "; 
  		 
	}  
	if($row['is_teacher']==1)
	{
  		$teacher_name.=" (".get_phrase('teaching_staff').") ";
	} 
	$description=$row['discription'];
	$result_array=array
	(
		$title,
		$dept,
		$class,
		$teacher_name,
		$description
	);
	fputcsv($file,$result_array);
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
   
   
   public function class_csv()
   {
 	$file_name='class_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	
 	$column_names=array(get_phrase('class'), get_phrase('teacher'),get_phrase('department'),get_phrase('description'));
	fputcsv($file,$column_names);
 	 $school_id=$_SESSION['school_id'];
        
   $q="SELECT c.*,c.order_by as order_by,d.title as designation,d.is_teacher as is_teacher,s.name as staff_name FROM ".get_school_db().".class c 
LEFT JOIN ".get_school_db().".staff s ON c.teacher_id=s.staff_id
LEFT JOIN ".get_school_db().".designation d ON (s.designation_id=d.designation_id and d.is_teacher=1) 
where c.school_id=".$_SESSION['school_id']." $search_ary";
$class=$this->db->query($q)->result_array();

foreach($class as $row)
{
	$class_name=$row['name'];
	if($row['name_numeric']!="")
    {
      $class_name.= ' ('.$row['name_numeric'].')';
    }
    $teacher=$row['staff_name'];
    if($row['designation']!="")
    {
        $teacher.= " (".$row['designation'].") ";  
    }
   	if($row['is_teacher']==1)
    {
       $teacher.= " (Teaching staff) ";
    } 
    $ary_data=array('departments_id'=>$row['departments_id'],'school_id'=>$_SESSION['school_id']);

  $rec_data=$this->db->get_where(get_school_db().'.departments',$ary_data)->result_array();
  
  $department=$rec_data[0]['title']; 
  $description=$row['description'];
	
	$result_array=array
	(
		$class_name,
		$teacher,
		$department,
		$description
	);
	fputcsv($file,$result_array);
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
   
    
    public function department_csv()
   {
 	$file_name='department_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('department'), get_phrase('department_head'),get_phrase('description'));
	fputcsv($file,$column_names);
 	 $school_id=$_SESSION['school_id'];
        
        $data_=array('school_id'=>$school_id);
        $this->db->order_by('order_num');
$students=$this->db->get_where(get_school_db().'.departments',$data_)->result_array();

foreach($students as $row)
{
	$dept= $row['title'].' ('.$row['short_name'].')';
	$head_id= $row['department_head'];
	$rec_dep=$this->db->query("select s.*,d.title as designation,d.is_teacher as is_teacher from ".get_school_db().".staff s LEFT JOIN ".get_school_db().".designation d ON s.designation_id=d.designation_id where s.staff_id=$head_id and s.school_id=".$_SESSION['school_id']."")->result_array();
       
      $dept_head=$rec_dep[0]['name'];
      if($rec_dep[0]['designation']!="")
      {
      	$dept_head.= " (".$rec_dep[0]['designation'].") ";  
    	}
          
         if($rec_dep[0]['is_teacher']==1)
         {
      $dept_head.= " (".get_phrase('teaching_staff').") ";
     } 
     $description=$row['discription'];
	
	$result_array=array
	(
		$dept,
		$dept_head,
		$description
	);
	fputcsv($file,$result_array);
}
	$file=$file_name;
	if (file_exists($file)) 
		{
	    	header('Content-Description: File Transfer');
	   		header('Content-Type: application/octet-stream');
	   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
	    	header('Expires: 0');
	    	header('Cache-Control: must-revalidate');
	    	header('Pragma: public');
	    	header('Content-Length: ' . filesize($file));
	    	readfile($file);
            file_delete($file);
		}

   } 
   
     public function leave_category_csv()
   {
 	$file_name='leave_category_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('title'));
	fputcsv($file,$column_names);
 	 $leaves=$this->db->select("*")->from(get_school_db().'.leave_category')->order_by('leave_category_id', 'desc')->get()->result_array();

foreach($leaves as $row)
{
	$title=$row['name'];
	$result_array=array
	(
	$title
	);
	
	fputcsv($file,$result_array);
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
   
     public function vacation_csv()
   {
 	$file_name='vacation_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('title'),get_phrase('start_date'),get_phrase('end_date'));
	fputcsv($file,$column_names);
 	  $qur_val="select * from ".get_school_db().".holiday
    WHERE school_id=".$_SESSION['school_id']." $date_query";
  
  
  $qr_arry=$this->db->query($qur_val)->result_array();

foreach($qr_arry as $rr)
{
	$title=$rr['title'];
	$start_date=convert_date($rr['start_date']);
	$end_date=convert_date($rr['end_date']);
	$result_array=array
	(
	$title,
	$start_date,
	$end_date
	);
	
	fputcsv($file,$result_array);
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
   
    public function academic_year_csv()
   {
 	$file_name='academic_year_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array('',get_phrase('title'),get_phrase('start_date'),get_phrase('end_date') ,get_phrase('status'),
	get_phrase('is_closed'),get_phrase('detail'));
	fputcsv($file,$column_names);
	$school_id=$_SESSION['school_id'];
	
	$data_=array('school_id'=>$school_id);
	// $this->db->order_by('order_num',"asc");
	$this->db->order_by('status','desc');
	$students=$this->db->get_where(get_school_db().'.acadmic_year',$data_)->result_array();

	foreach($students as $row)
	{
		$acad=get_phrase('academic_year');
		$academic_title=$row['title'];
		$acad_start_date=convert_date($row['start_date']);
		$acad_end_date=convert_date($row['end_date']);
		$statsArr=year_term_status();
				 
				$status=$row['status'];
				$a_status = "";
				$type="";
				if($row['status']==1)
				{
					$a_status = get_phrase('completed');
				}
				elseif($row['status']==2)
				{
					$a_status = get_phrase('current');
				}
				elseif($row['status']==3)
				{
					$a_status = get_phrase('upcoming');
				}
				if($row['is_closed']==1)
				 {
				 	$type= get_phrase('Yes');
				 }
				elseif($row['is_closed']==0)
				{
					$type=get_phrase('no');
				}			
	$detail=$row['detail'];


$result_array=array
	(
	$acad,
	$academic_title,
	$acad_start_date,
	$acad_end_date,
	$a_status,
	$type,
	$detail
	);	
	fputcsv($file,$result_array);	

$q="select * from ".get_school_db().".yearly_terms where academic_year_id=".$row['academic_year_id']." order by status DESC";
	 $termArr=$this->db->query($q)->result_array(); 
	foreach($termArr as $term)
	{
		$term_title_val="";
		$term_title=$term['title'];
		$st_date=convert_date($term['start_date']);
		$en_date=convert_date($term['end_date']);
		$t_status = '';
				if($term['status']==1)
				{
					$t_status = get_phrase('completed');
				}
				elseif($term['status']==2)
				{
					$t_status = get_phrase('current');
				}
				elseif($term['status']==3)
				{
					$t_status = get_phrase('upcoming');
				}
				if($term['is_closed']==1)
					 {
					 	$type= get_phrase('yes');
					 }
				elseif($term['is_closed']==0)
				{
					$type= get_phrase('no');
				}
			
					
	$result_array=array
	(
	get_phrase('yearly_term'),
	$term_title,
	$st_date,
	$en_date,
	$t_status,
	$type
	
	);	
	fputcsv($file,$result_array);	
	}
	
	$result_array=array
	(
	'',
	'',
	'',
	'',
	'',
	'',
	''
	);	
	fputcsv($file,$result_array);		
	
   } 
   $file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
 } 
 
 
public function policies_csv()
{
 	$file_name='policies_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(
					get_phrase('category'),get_phrase('title'),get_phrase('no'),get_phrase('version'),
					get_phrase('author'),get_phrase('approved_by'),get_phrase('approval_date'),
					get_phrase('last_updated'),get_phrase('attachment'));
	fputcsv($file,$column_names);
 	 $qqur="select * from   ".get_school_db().".policy_category where school_id=".$_SESSION['school_id'];
 	  
  $query_val =$this->db->query($qqur)->result_array();
if(count($query_val)>0)
{
	foreach($query_val as $row_val)
	{
		$category=$row_val['title'];

$qry = "SELECT p.*, pc.title as category_title FROM " . get_school_db() . ".policies p INNER JOIN " . get_school_db() . ".policy_category pc ON p.policy_category_id = pc.policy_category_id WHERE p.school_id=" . $_SESSION[ 'school_id' ] . " and p.policy_category_id=" . $row_val[ 'policy_category_id' ] . "  ORDER BY p.policies_id ASC";
			$data = $this->db->query( $qry )->result_array();
	
			foreach ( $data as $row )
			{
				$title=$row['title'];
				$no=$row['document_num'];
				$version=$row['version_num'];
				
				$author=$row['author'];
				$approved_by=$row['approved_by'];
				$approval_date=convert_date($row['approval_date']);
				$last_updated=convert_date($row['last_update_date']);
				$detail=$row['detail'];	
				$attachment=$row['attachment'];
			$doc_code="_".$no."_".$version;
			$attachment_name="";
			if($attachment!='')
			{
				$attachment_name=$this->attachment_name($attachment,$title,$doc_code);
			}
			
			
	$result_array=array
	(
	$category,
	$title,
	$no,
	$version,
	$author,
	$approved_by,
	$approval_date,
	$last_updated,
	$attachment_name
	);
	fputcsv($file,$result_array);
				
			}  
			
 	} 
 	
 }	
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}
} 	
 	
 	
  public function location_csv()
   {
 	$file_name='location_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array('Title','City','Province','Country','Status');
	fputcsv($file,$column_names);
 	  $q="SELECT cl.location_id, cl.title,  cl.status, c.title as country, 
        	p.title as province, cty.title as city 
        	FROM ".get_school_db().".city_location cl 
        	INNER JOIN 
        	".get_system_db().".city cty
        	ON cl.city_id = cty.city_id
        	INNER JOIN ".get_system_db().".province p
        	ON cty.province_id = p.province_id
        	INNER JOIN ".get_system_db().".country c
        	ON p.country_id = c.country_id
        	WHERE cl.school_id=".$_SESSION['school_id'].$filter." 
        	ORDER BY cl.location_id DESC 
        ";
	$notices=$this->db->query($q)->result_array();

foreach($notices as $row)
{
	
	$status="";
	if ($row['status'] == 1)
	{
		$status=get_phrase('active');
	}
	else
	{
		$status=get_phrase('in_active');
	}
	$result_array=array
	(
	$row['title'],
	$row['city'],
	$row['province'],
	$row['country'],
	$status
	);
	
	fputcsv($file,$result_array);
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
    	

 public function stud_eval_settings_csv()
   {
   	
 	$file_name='stud_evaluation_settings_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('questions'),get_phrase('status'),get_phrase('answer'),get_phrase('status'));
	fputcsv($file,$column_names);
 	 $eval=$this->db->select("*")->from(get_school_db().'.student_evaluation_form')->order_by('eval_id', 'desc')->get()->result_array();
        
foreach($eval as $row)
{
	
	$status_arr="";
    if($row['status']==1)
    {
      $status_arr=get_phrase('active');
    }
  	if($row['status']==0)
    {
      $status_arr=get_phrase('in-active');
    }
    
	
$q="SELECT * FROM ".get_school_db().".miscellaneous_settings WHERE school_id=".$_SESSION['school_id']." AND type='stud_eval'";
        $misc=$this->db->query($q)->result_array();
      foreach($misc as $misc_row)
      {
	  	$answers=$misc_row['detail'];
	  	$status=$misc_row['status'];
             $status_val="";
             if($status==0)
             {
             	$status_val=get_phrase('in_active');
               	
             }
             if($status==1)
             {
                 $status_val=get_phrase('active');
              }
      
	  }
	 $result_array=array
	(
		$row['title'],
	$status_arr,
		$answers,
		$status_val
	 ); 
	fputcsv($file,$result_array);   
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
    	

public function staff_eval_settings_csv()
   {
 	$file_name='staff_eval_settings_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('questions'),get_phrase('status'),get_phrase('answer'),get_phrase('status'));
	fputcsv($file,$column_names);
 	 $eval=$this->db->select("*")->from(get_school_db().'.staff_evaluation_form')->order_by('staff_eval_form_id', 'desc')->get()->result_array();
        
foreach($eval as $row)
{
	$status_val="";
    if($row['status']==0)
    {
    	$status_val=get_phrase('in_active');
	}
	elseif($row['status']==1)
    {
		$status_val=get_phrase('active');
	}
    
	
 $q1="SELECT * FROM ".get_school_db().".miscellaneous_settings WHERE school_id=".$_SESSION['school_id']." AND type='staff_eval'";
        $misc_staff=$this->db->query($q1)->result_array();
      foreach($misc_staff as $misc_row)
      {
	  	$answers=$misc_row['detail'];
	  	$status_staff=$misc_row['status'];
             $status_val_staff="";
             
             if($status_staff==0)
             {
				$status_val_staff=get_phrase('in_active');
			}
			if($status_staff==1)
             {
				$status_val_staff=get_phrase('active');
			}
      
	  }
	 $result_array=array
	(
		$row['title'],
	$status_val,
		$answers,
		$status_val_staff
	 ); 
	fputcsv($file,$result_array);   
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
    	

public function fee_types_csv()
   {
 	$file_name='fee_types_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('title'),get_phrase('issue_debit_coa'),get_phrase('issue_credit_coa'),
					get_phrase('receive_debit_coa'),get_phrase('receive_credit_coa'),
					get_phrase('cancel_debit_coa'),get_phrase('cancel_credit_coa'),get_phrase('status'));
	fputcsv($file,$column_names);
 	  $school_id=$_SESSION['school_id'];  
        $data_=array('school_id'=>$school_id);
$students=$this->db->get_where(get_school_db().'.fee_types',$data_)->result_array();
        
foreach($students as $row)
{
	 $stclass = '' ;   
    if($row['status'] == 1)   
    {
    	$stclass = get_phrase('active');
     }   
    elseif($row['status'] == 0)   
    {
    	$stclass = get_phrase('in_active');
    } 
	$issue_debit=get_coa($row['issue_dr_coa_id']);
	$issue_debit['account_head'];
	$issue_debit['account_number'];
	
	$issue_credit=get_coa($row['issue_cr_coa_id']);
	$issue_credit['account_head'];
	$issue_credit['account_number'];
	
	$receive_debit=get_coa($row['receive_dr_coa_id']);
	$receive_debit['account_head'];
	$receive_debit['account_number'];
	
	$receive_credit=get_coa($row['receive_cr_coa_id']);
	$receive_credit['account_head'];
	$receive_credit['account_number'];
	
	$cancel_debit=get_coa($row['cancel_dr_coa_id']);
	$cancel_debit['account_head'];
	$cancel_debit['account_number'];
	
	$cancel_credit=get_coa($row['cancel_cr_coa_id']);
	$cancel_credit['account_head'];
	$cancel_credit['account_number'];
	
	
	if($issue_debit['account_head']!="")
	{
		$issue_debit_val=$issue_debit['account_head'].' - '.$issue_debit['account_number'];
	}
	if($issue_credit['account_head']!="")
	{
		$issue_credit_val=$issue_credit['account_head'].' - '.$issue_credit['account_number'];
	}
	if($receive_debit['account_head']!="")
	{
		$receive_debit_val=$receive_debit['account_head'].' - '.$receive_debit['account_number'];
	}
	if($receive_credit['account_head']!="")
	{
		$receive_credit_val=$receive_credit['account_head'].' - '.$receive_credit['account_number'];
	}
	if($cancel_debit['account_head']!="")
	{
		$cancel_debit_val=$cancel_debit['account_head'].' - '.$cancel_debit['account_number'];
	}
	if($cancel_credit['account_head']!="")
	{
		$cancel_credit_val=$cancel_credit['account_head'].' - '.$cancel_credit['account_number'];
	}
	$result_array=array(
	$row['title'],
	$issue_debit_val,
	$issue_credit_val,
	$receive_debit_val,
	$receive_credit_val,
	$cancel_debit_val,
	$cancel_credit_val,
	$stclass
	);
	 
	fputcsv($file,$result_array);   
	//echo "<pre>";
	//print_r($result_array);
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
    	

public function discount_types_csv()
   {
 	$file_name='discount_types_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('title'),get_phrase('issue_debit_coa'),get_phrase('issue_credit_coa'),
					get_phrase('receive_debit_coa'),get_phrase('receive_credit_coa'),
					get_phrase('cancel_debit_coa'),get_phrase('cancel_credit_coa'),get_phrase('status'));
	fputcsv($file,$column_names);
 	  $school_id=$_SESSION['school_id'];
        
        $data_=array('school_id'=>$school_id);
        
$students=$this->db->get_where(get_school_db().'.discount_list',$data_)->result_array();
        
foreach($students as $row)
{
	$status_class= '';
	 if($row['status']==0)
	 {
          $status_class = get_phrase('in_active');
     }
      elseif($row['status']==1)
      {  
        $status_class = get_phrase('active');
      }
      
      $issue_debit=get_coa($row['issue_dr_coa_id']);
	$issue_debit['account_head'];
	$issue_debit['account_number'];
	
	$issue_credit=get_coa($row['issue_cr_coa_id']);
	$issue_credit['account_head'];
	$issue_credit['account_number'];
	
	$receive_debit=get_coa($row['receive_dr_coa_id']);
	$receive_debit['account_head'];
	$receive_debit['account_number'];
	
	$receive_credit=get_coa($row['receive_cr_coa_id']);
	$receive_credit['account_head'];
	$receive_credit['account_number'];
	
	$cancel_debit=get_coa($row['cancel_dr_coa_id']);
	$cancel_debit['account_head'];
	$cancel_debit['account_number'];
	
	$cancel_credit=get_coa($row['cancel_cr_coa_id']);
	$cancel_credit['account_head'];
	$cancel_credit['account_number'];
	$issue_debit_val="";
	$issue_credit_val="";
	$receive_debit_val="";
	$receive_credit_val="";
	$cancel_debit_val="";
	$cancel_credit_val="";
	if($issue_debit['account_head']!="")
	{
		$issue_debit_val=$issue_debit['account_head'].' - '.$issue_debit['account_number'];
	}
	if($issue_credit['account_head']!="")
	{
		$issue_credit_val=$issue_credit['account_head'].' - '.$issue_credit['account_number'];
	}
	if($receive_debit['account_head']!="")
	{
		$receive_debit_val=$receive_debit['account_head'].' - '.$receive_debit['account_number'];
	}
	if($receive_credit['account_head']!="")
	{
		$receive_credit_val=$receive_credit['account_head'].' - '.$receive_credit['account_number'];
	}
	if($cancel_debit['account_head']!="")
	{
		$cancel_debit_val=$cancel_debit['account_head'].' - '.$cancel_debit['account_number'];
	}
	if($cancel_credit['account_head']!="")
	{
		$cancel_credit_val=$cancel_credit['account_head'].' - '.$cancel_credit['account_number'];
	}
	$result_array=array(
	$row['title'],
	$issue_debit_val,
	$issue_credit_val,
	$receive_debit_val,
	$receive_credit_val,
	$cancel_debit_val,
	$cancel_credit_val,
	$status_class
	);
    
	fputcsv($file,$result_array);   
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
    	

public function manage_transactions()
   {
 	$file_name='manage_transactions_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('title'),get_phrase('type'),get_phrase('chart_of_account'),get_phrase('account_no'),
				get_phrase('voucher_no'),get_phrase('method'),get_phrase('amount'),get_phrase('date'),
				get_phrase('detail'),get_phrase('receipt_num'));
	fputcsv($file,$column_names);
 	 $students=$this->db->get_where(get_school_db().'.account_transection',$data_)->result_array();

        
foreach($students as $row)
{
	$data_ary=get_coa($row['coa_id']);
	
	$result_array=array(
	$row['title'],
	type_display($row['type']),
	$data_ary['account_head'],
	$data_ary['account_number'],
	$row['voucher_num'],
	method_display($row['method']),
	$row['amount'],
	convert_date($row['date']),
	$row['detail'],
	$row['receipt_num'],
	);
	
	
 
      
	fputcsv($file,$result_array);   
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
    	

public function chart_of_account()
   {
 	$file_name='chart_of_account_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	$column_names=array(get_phrase('account_head'),get_phrase('account_number'),get_phrase('account_type'),
					get_phrase('status'),get_phrase('parent'),get_phrase('is_active'));
	fputcsv($file,$column_names);
	$school_id=$_SESSION['school_id'];
 	 $coa_rec=$this->db->query("select * from ".get_school_db().".chart_of_accounts where school_id=$school_id ORDER BY parent_id")->result_array();

 $coa_array=array();       
foreach($coa_rec as $coa)
{
	
	$type=$coa['account_type'];
	if($type==1)
	{
		$coa['account_type']=get_phrase('credit');
	}
	elseif($type==2)
	{
		$coa['account_type']=get_phrase('debit');
	}
	
	$status=$coa['status'];
	if($status==0)
	{
		$coa['status']=get_phrase('waiting_for_approval');
	}
	elseif($status==1)
	{
		$coa['status']=get_phrase('approved');
	}
	elseif($status==2)
	{
		$coa['status']=get_phrase('rejected');
	}
	elseif($status==3)
	{
		$coa['status']=get_phrase('archived');
	}
	
	$is_active=$coa['is_active'];
	if($is_active==0)
	{
		$coa['is_active']=get_phrase('in_active');
	}
	elseif($is_active==1)
	{
		$coa['is_active']=get_phrase('active');
	}
	
	
	$coa_array[$coa['coa_id']]=array(
	'account_number'=>$coa['account_number'],
	'account_head'=>$coa['account_head'],
	'account_type'=>$coa['account_type'],
	'parent_id'=>$coa['parent_id'],
	'status'=>$coa['status'],
	'is_active'=>$coa['is_active']
	);
}
//echo "<pre>";
//print_r($coa_array);
foreach($coa_array as $k=>$val)
{
	$parent_val="";
	if($coa_array[$val['parent_id']]['account_head']!="")
	{
		$parent_val=$coa_array[$val['parent_id']]['account_head'].' - '.$coa_array[$val['parent_id']]['account_number'];
	}
	
	$result_array=array(
	$val['account_head'],
	$val['account_number'],
	$val['account_type'],
	$val['status'],
	$parent_val,
	$val['is_active']
	);
	
	
fputcsv($file,$result_array);  	
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
   
public function messages_csv()
   {
 	$file_name='messages_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	
	$acad_year=$this->input->post('academic_year12');
	$teacher_id=$this->input->post('teacher_id12');
	$section_id=$this->input->post('section_id12');
	
	$r="SELECT start_date,end_date FROM ".get_school_db().".acadmic_year WHERE school_id=".$_SESSION['school_id']." AND academic_year_id=".$acad_year." ";
	$query=$this->db->query($r)->result_array();
	$start_date=$query[0]['start_date'];
	$end_date=$query[0]['end_date'];
	$start_end_arr[]=get_phrase('start_date').': '.convert_date($start_date).' '.get_phrase('end_date').': '.convert_date($end_date);
 	fputcsv($file,$start_end_arr);      
	
	
	$q="select m.*, s.staff_id, s.name as teacher_name, s.staff_image,s.staff_id, d.title as designation, st.name as student_name,st.student_id, st.roll, st.image as student_image , sub.subject_id, sub.name as subject_name, sub.code as subject_code, cs.section_id, cs.title as section, c.name as class, dep.title as department 
            from ".get_school_db().".messages m
            inner join ".get_school_db().".staff s on m.teacher_id = s.staff_id
            inner join ".get_school_db().".designation d on d.designation_id = s.designation_id
            inner join ".get_school_db().".subject sub on sub.subject_id = m.subject_id
            inner join ".get_school_db().".student st on st.student_id = m.student_id
            inner join ".get_school_db().".class_section cs on cs.section_id = st.section_id
            inner join ".get_school_db().".class c on c.class_id = cs.class_id
            inner join ".get_school_db().".departments dep on dep.departments_id = c.departments_id
            where 
            m.school_id = ".$_SESSION['school_id']." 
            and cs.section_id = ".intval($section_id)." 
            AND m.teacher_id=$teacher_id
            AND m.message_time>='".$start_date."' AND m.message_time<='".$end_date."'
            group by m.student_id 
            order by m.message_time";
            
$messages_arr = $this->db->query($q)->result_array();
	if(count($messages_arr)==0)
	{
		$this->session->set_flashdata('club_updated',get_phrase('no_record_found'));
			redirect(base_url().'backup/manage_backup'); 
	} 
$class_section[]='Class-Section : '.$messages_arr[0]['department'].' - '.$messages_arr[0]['class'].' - '.$messages_arr[0]['section'];
	fputcsv($file,$class_section);
	$teacher_arr[]='Teacher : '.$messages_arr[0]['teacher_name'].' ('.$messages_arr[0]['designation'].')';
	fputcsv($file,$teacher_arr);
	$subject_arr[]='Subject : '.$messages_arr[0]['subject_name'].' - '.$messages_arr[0]['subject_code'];
	fputcsv($file,$subject_arr); 
	
$column_names=array(get_phrase('student'),get_phrase('roll_no'),get_phrase('message_time'),get_phrase('message'),get_phrase('sent_by'));
	fputcsv($file,$column_names);	 
        
foreach($messages_arr as $row)
{
	
	$msg_type=$row['messages_type'];
	if($msg_type==0)
	{
		$row['messages_type']=get_phrase('teacher');
	}
	elseif($msg_type==1)
	{
		$row['messages_type']=get_phrase('parent');
	}
	$result_array=array(
	$row['student_name'],
	$row['roll'],
	$row['message_time'],
	$row['messages'],
	$row['messages_type'],
	);
	
	fputcsv($file,$result_array);   
}
/*echo "<pre>";
print_R($result_array);*/

$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 

public function branch_csv()
   {
 	$file_name='branch_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	
 	$column_names=array(get_phrase('branch_name'),get_phrase('country'),get_phrase('province'),get_phrase('city'),
					get_phrase('location'),get_phrase('address'),get_phrase('phone'),get_phrase('url'),
					get_phrase('school_email'),get_phrase('contact_person'),get_phrase('designation'),
					get_phrase('slogan'),get_phrase('detail'),get_phrase('registration_no'),get_phrase('attachment'));
	fputcsv($file,$column_names);
 	  $q="select sys_sch_id, school_db FROM ".get_system_db().".system_school 
            where parent_sys_sch_id=".$_SESSION['sys_sch_id']." AND sys_sch_id!=parent_sys_sch_id";
        $query=$this->db->query($q)->result_array();  
        //echo '<pre>';
        //print_r($query);      
foreach($query as $query2)
{
            $sys_sch_id=$query2['sys_sch_id'];
            $school_db = $query2['school_db'];
            if ($query2['school_db'] == "")
                $school_db = get_school_db();
           
            $q2="select *
                FROM ".$school_db.".school
                WHERE sys_sch_id=".$query2['sys_sch_id']." 
                ";
            $result=$this->db->query($q2)->result_array();  
//exit;
        
foreach($result as $row)
{
	$folder_name=$row['folder_name'];
	$path='./uploads/'.$folder_name.'/';
	 $loc_array=get_country_edit($row['location_id']);
	 $country=$loc_array[0]['country_title'];
	 $province=$loc_array[0]['province_title'];
	 $location=$loc_array[0]['location_title'];
	 $city=$loc_array[0]['city_title'];
	 //attachments
	$attachment=$row['logo'];
	$title=$row['name'];
	$slogan=$row['slogan'];
	$file_val=$path.$attachment;
	$branch_img="";
	if($attachment!="" && file_exists($file_val))
	{
		
		$branch_img=$this->attachment_name($attachment,$title,$slogan);
	}
	 
	 
	$result_array=array(
	$row['name'],
	$country,
	$province,
	$city,
	$location,
	$row['address'],
	$row['phone'],
	$row['url'],
	$row['email'],
	$row['contact_person'],
	$row['designation'],
	$row['slogan'],
	$row['detail'],
	$row['school_regist_no'],
	$branch_img
	);
	//echo "<pre>";
	//print_r($result_array);
 
      
	fputcsv($file,$result_array);   
}
}
$file=$file_name;
if (file_exists($file)) 
	{
    	header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
   		header('Content-Disposition: attachment; filename="'.basename($file).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($file));
    	readfile($file);
        file_delete($file);
	}

   } 
    	    	
public function attachment_name($attachment="",$title="",$date="")
	{	
		
			$arr_val=array("!","#","$","%","&","'","(",")","*","+",",","-",".","/",":",";","<","=",">","?","@","[","\\","]","^","_","`","{","|","}","~","\"");
			$title_new=trim(str_replace($arr_val,"",$title));
			$title_new=str_replace(" ","_",$title_new);
			$title_val=substr($title_new,0,15);
			$title_res=$title_val.'_'.$date;
			
			$arr_explode=explode('.',$attachment);
			$ext=end($arr_explode);
			return $file_name=$title_res.".".$ext;
		
	}
    	

}