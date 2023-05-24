<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crud_model extends CI_Model {
	////////////////////

	function __construct()
    {
        parent::__construct();
    }
	/////////////////////
	function clear_cache()
	{
	    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
	}
	////////////////////////
	function get_type_name_by_id($type="",$type_id='',$field='name')
	{
		 return $this->db->select('name')->get_where(get_school_db().'.'.$type,array($type.'_id'=>$type_id,'school_id' =>$_SESSION['school_id']))->row();
	}
	
	////////STUDENT/////////////
	function get_students($class_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.student' , array('class_id' => $class_id,
		'school_id' =>$_SESSION['school_id']
		));
		return $query->result_array();
	}
	////////////////////
	
	function get_student_info($student_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.student' , array('student_id' => $student_id,'school_id' =>$_SESSION['school_id']));
		return $query->result_array();
	}
	////////////////////////
	function get_student_name($student_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.student' , array('student_id' => $student_id,'school_id' =>$_SESSION['school_id']));
		$res	=	$query->result_array();
		foreach($res as $row)
			return $row['name'];
	}
	/////////Driver/////////////
	function get_transport_driver()
	{
		$query	=	$this->db->get('transport_driver' );
		return $query->result_array();
	}
	//////////////////////////
	function get_driver_name($driver_id)
	{
		$query	=	$this->db->get_where('transport_driver' , array('driver_id' => $driver_id));
		$res	=	$query->result_array();
		foreach($res as $row)
			return $row['name'];
	}
	/////////////////////////////
	function get_driver_info($driver_id)
	{
		$query	=	$this->db->get_where('transport_driver' , array('driver_id' => $driver_id));
		return $query->result_array();
	}
	
	/////////TEACHER/////////////
	function get_teachers()
	{
		$query	=	$this->db->get(get_school_db().'.teacher' );
		return $query->result_array();
	}
	///////////////////////////////
	function get_teacher_name($teacher_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.teacher' , array('teacher_id' => $teacher_id,'school_id' =>$_SESSION['school_id']));
		$res	=	$query->result_array();
		foreach($res as $row)
			return $row['name'];
	}
	////////////////////////
	function get_teacher_info($teacher_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.teacher' , array('teacher_id' => $teacher_id));
		return $query->result_array();
	}
	////////////////////////////////////
	function get_subject_info_routine($subject_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.subject' , array('subject_id' => $subject_id,'school_id' =>$_SESSION['school_id']));
		return $query->result_array();
	}
	
	//////////SUBJECT/////////////
	function get_subjects()
	{
		$query	=	$this->db->get(get_school_db().'.subject' );
		return $query->result_array();
	}
	///////////////////////////
	function get_subject_info($subject_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.subject' , array('subject_id' => $subject_id));
		return $query->result_array();
	}
	///////////////////////
	function get_subjects_by_class($class_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.subject' , array('class_id' => $class_id,
		'school_id' =>$_SESSION['school_id']
		));
		return $query->result_array();
	}
	//////////////////////////////
	function get_subject_name_by_id($subject_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.subject' , array('subject_id' => $subject_id,'school_id' =>$_SESSION['school_id']))->row();
		return $query->name;
	}
	////////////CLASS///////////
	function get_class_name($class_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.class' , array('class_id' => $class_id,
		'school_id' =>$_SESSION['school_id']));
		$res	=	$query->result_array();
		foreach($res as $row)
			return $row['name'];
	}
	/////////////////////////////
	function get_class_name_numeric($class_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.class' , array('class_id' => $class_id));
		$res	=	$query->result_array();
		foreach($res as $row)
			return $row['name_numeric'];
	}
	///////////////////////////
	function get_classes()
	{
		$this->db->where('school_id',$_SESSION['school_id']);
		$query	=	$this->db->get(get_school_db().'.class');
		return $query->result_array();
	}
	////////////////////////////////	
	function get_class_info($class_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.class' , array('class_id' => $class_id));
		return $query->result_array();
	}
	
	//////////EXAMS/////////////
	function get_exams()
	{
		$this->db->where('school_id',$_SESSION['school_id']);
		$query	=	$this->db->get(get_school_db().'.exam' );
		return $query->result_array();
	}	
	///////////////////////////////////
	function get_exam_info($exam_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.exam' , array('exam_id' => $exam_id));
		return $query->result_array();
	}	
	//////////GRADES/////////////
	function get_grades()
	{
		$query	=	$this->db->get(get_school_db().'.grade' );
		return $query->result_array();
	}	
	////////////////////////////
	function get_grade_info($grade_id)
	{
		$query	=	$this->db->get_where(get_school_db().'.grade' , array('grade_id' => $grade_id));
		return $query->result_array();
	}	
	////////////////////////////////
	function get_grade($mark_obtained)
	{
		$this->db->where('school_id',$_SESSION['school_id']);
		$query	=	$this->db->get(get_school_db().'.grade' );
		$grades	=	$query->result_array();
		foreach($grades as $row)
		{
			if($mark_obtained >= $row['mark_from'] && $mark_obtained <= $row['mark_upto'])
				return $row;
		}
	}
///////////////////////////////////
	function create_log($data)
	{
		$data['timestamp']	=	strtotime(date('Y-m-d').' '.date('H:i:s'));
		$data['ip']			=	$_SERVER["REMOTE_ADDR"];
		$location 			=	new SimpleXMLElement(file_get_contents('http://freegeoip.net/xml/'.$_SERVER["REMOTE_ADDR"]));
		$data['location']	=	$location->City.' , '.$location->CountryName;
		$this->db->insert(get_school_db().'.log' , $data);
	}
	////////////////////////////////////
	function get_system_settings()
	{
		$query	=	$this->db->get(get_school_db().'.settings' );
		return $query->result_array();
	}
	
		
	
	////////BACKUP RESTORE/////////
	function create_backup($type)
	{
		$this->load->dbutil();
		
		
		$options = array(
                'format'      => 'txt',             // gzip, zip, txt
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );
		
		 
		if($type == 'all')
		{
			$tables = array('');
			$file_name	=	'system_backup';
		}
		else 
		{
			$tables = array('tables'	=>	array($type));
			$file_name	=	'backup_'.$type;
		}

		$backup =& $this->dbutil->backup(array_merge($options , $tables)); 


		$this->load->helper('download');
		force_download($file_name.'.sql', $backup);
	}
	
	
	/////////RESTORE TOTAL DB/ DB TABLE FROM UPLOADED BACKUP SQL FILE//////////
	function restore_backup()
	{
		move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/backup.sql');
		$this->load->dbutil();
		
		
		$prefs = array(
            'filepath'						=> 'uploads/backup.sql',
			'delete_after_upload'			=> TRUE,
			'delimiter'						=> ';'
        );
		$restore =& $this->dbutil->restore($prefs); 
		unlink($prefs['filepath']);
	}
	
	/////////DELETE DATA FROM TABLES///////////////
	function truncate($type)
	{
		if($type == 'all')
		{
			$this->db->truncate('student');
			$this->db->truncate('mark');
			$this->db->truncate('teacher');
			$this->db->truncate('subject');
			$this->db->truncate('class');
			$this->db->truncate('exam');
			$this->db->truncate('grade');
		}
		else
		{	
			$this->db->truncate($type);
		}
	}
	
	
	////////IMAGE URL////////////////////////
	function get_image_url($type = '' , $id = '')
	{
		//echo $type;
		//echo $id;
	//echo base_url().'uploads/'.$type.'_image/'.$id.'.jpg';
	//exit;
		if(file_exists('uploads/'.$type.'_image/'.$id.'.jpg'))
		{
			$image_url	=	base_url().'uploads/'.$type.'_image/'.$id.'.jpg'; 
			//exit;
		}
			
		else
		{
			$image_url	=	base_url().'uploads/user.jpg';
			//exit;
		return $image_url;
		}
			
	}
	
	
	
        /////////////////////////////////////////
        function get_id_front_image_url($type = '' , $id = '')
	{
		if(file_exists('uploads/'.$type.'_image/id-card/'.$id.'-id-front.jpg'))
			$image_url	=	base_url().'uploads/'.$type.'_image/id-card/'.$id.'-id-front.jpg'; 
		else
			$image_url	=	base_url().'uploads/IDCard_PK.jpg';
		return $image_url;
	}
	//////////////////////////////////////////////
	
        function get_id_back_image_url($type = '' , $id = '')
	{
		if(file_exists('uploads/'.$type.'_image/id-card/'.$id.'-id-back.jpg'))
			$image_url	=	base_url().'uploads/'.$type.'_image/id-card/'.$id.'-id-back.jpg'; 
		else
			$image_url	=	base_url().'uploads/IDCard_PK.jpg';
		return $image_url;
	}
	
	function get_all_concession()
	{
		$query	=	$this->db->get(get_school_db().'.concession');
		$res	=	$query->result_array();
		foreach($res as $row)
			return $row['name'];
	}
	
	
	
}

