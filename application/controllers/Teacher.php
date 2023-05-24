<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    //session_start();
    
ob_start();
class Teacher extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        if (get_login_type_name($_SESSION['login_type']) != 'teacher')
            redirect(base_url() . 'login');
            
        $this->load->database();
        $this->load->helper('teacher');
        $this->load->helper('notification');
        $this->load->model('Crud_model');
        
    }
   
    public function index()
    {
       
        if (get_login_type_name($_SESSION['login_type']) == 'teacher' )
            redirect(base_url() . 'teacher/dashboard');
            
        
    }
    

    function dashboard()
    {
      
        $page_data['total_present'] = count_monthly_staff_attendance($_SESSION['user_id'],date("m"),date("Y"),'P');
        $page_data['total_absent'] = count_monthly_staff_attendance($_SESSION['user_id'],date("m"),date("Y"),'A');
        $page_data['total_leave'] = count_monthly_staff_attendance($_SESSION['user_id'],date("m"),date("Y"),'L');
        
        $this->load->helper('teacher');
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        $academic_year_id = $_SESSION['academic_year_id'];
        $section_arr = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
        $section_arr_explode = explode(",",$section_arr);
        $page_data['std_birthday'] = get_students_birthday_count($_SESSION['school_id'],$section_arr_explode);
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('Dashboard');
        $q="SELECT * FROM ".get_school_db().".class_routine_settings WHERE school_id=".$_SESSION['school_id']."";
		$routine=$this->db->query($q)->result_array();
        
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE STUDENTS CLASSWISE*****/
    function student_add()
	{
		$page_data['page_name']  = 'student_add';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
	}
	
    //// Filter List Circular////
    function get_department_class()
    {
        echo class_option_list(intval($this->input->post('department_id')));
    }

    function get_class_section()
    {
        echo section_option_list(intval($this->input->post('class_id')));
    }

    function get_yearly_term()
    {
        $y_arr = array(1,3);      
        echo yearly_terms_option_list(intval($this->input->post('academic_year_id')),0,$y_arr); 
    }

    function month_year_option()
    {
        $academic_year_id= intval($this->input->post('yearly_term_id'));
        $qur_rr     = $this->db->query("select start_date , end_date from ".get_school_db().".yearly_terms where school_id=".$_SESSION['school_id']." and yearly_terms_id=$academic_year_id")->result_array();
        $start_date = $qur_rr[0]['start_date'];
        $end_date   = $qur_rr[0]['end_date'];
        echo month_year_option($start_date,$end_date);
    }

    function get_section_list()
    {
        $login_detail_id = intval($_SESSION['login_detail_id']);
        $yearly_terms_id = intval($this->input->post('yearly_term_id'));
      
        $time_table_arr = get_time_table_teacher_section($login_detail_id, $yearly_terms_id);
        $in_section = 0;
        if ( count($time_table_arr) > 0)
            $in_section = implode(',', $time_table_arr);

        $section = $this->db->query("select cs.section_id, cs.title as section, c.class_id, c.name as class, d.departments_id, d.title as department  
            from ".get_school_db().".class_section cs
            inner join  ".get_school_db().".class c on c.class_id = cs.class_id
            inner join  ".get_school_db().".departments d on d.departments_id = c.departments_id
            where cs.section_id IN ($in_section) 
            and cs.school_id=".$_SESSION['school_id']." 
            ")->result_array();
        echo '<option value="">'.get_phrase('select_section').'</option>';
        foreach ($section as $key => $value) 
        {
            echo "<option value='".$value['section_id']."' >".$value['department'].'->'.$value['class'].'->'.$value['section']."</option>";
        }
    }

    function get_time_table_subject_list($select_id= 0)// get time table subject teacher
    {
        echo teacher_subject_option_list($_SESSION['login_detail_id'], intval($this->input->post('section_id')), $select_id);
    }

    function get_subject_list($select_id= 0)
    {
        $section_id = intval($this->input->post('section_id'));
        $yearly_term = intval($this->input->post('yearly_term'));
        if ($yearly_term == 0)
            $yearly_term = $_SESSION['yearly_term_id'];
            $login_detail_id = $_SESSION['login_detail_id'];

        echo get_subject_teacher_option_list($login_detail_id, $select_id);
       
    }

	function student_information($class_id = '')
	{
		$page_data['page_name']  	= 'student_information'; 
        $page_data['page_title'] = get_phrase('student_information'). " - "
        .get_phrase('class')." : ".$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	
	function student_marksheet($class_id = '')
	{
		$page_data['page_name']  = 'student_marksheet';
        $page_data['page_title']=get_phrase('student_marksheet'). " - ".get_phrase('class')." : ".$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	
    function get_diary_subject_list($select_id= 0 )
    {
        $section_id = intval($this->input->post('section_id'));
        $yearly_term = $_SESSION['yearly_term_id'];
        $login_detail_id = $_SESSION['login_detail_id'];
        
        $sub_arr = get_teacher_diary_subject($login_detail_id, $section_id);
        $sub_id = $sub_arr['ids'];
        $sub_name = $sub_arr['names'];
        
        echo '<option value="">'.get_phrase('select_subject').'</option>';
        foreach ($sub_id as $key => $id) 
        {
            $selected = '';
            if ($id == $select_id)
                $selected = 'selected';
            echo "<option value='".$id."' $selected>".$sub_name[$key]."</option>";
        }
    } 
    
    function add_audio_in_diary()
    {
        $audia_voice = $_FILES['audio_data']['name'];
        if(!empty($audia_voice))
        {
            $path = 'uploads/' . $_SESSION['folder_name']."/diary_audios/";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            
            $data_audio['diary_id'] = $_POST['diary_id'];
            $data_audio['audio'] = $_FILES['audio_data']['name'].".wav";
            move_uploaded_file($_FILES['audio_data']['tmp_name'],$path.$data_audio['audio']);
            $this->db->insert(get_school_db().'.diary_audio', $data_audio);
            
            echo "Audio Added Succssfully";
        }
    }
    
   function diary($param1 = '', $param2 = '' , $param3 = '')
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        
        if ($param1 == 'add_diary') 
        {
           $this->db->trans_begin();
            
            $data['teacher_id']  = intval($_SESSION['user_id']);
            $data['subject_id']  = $this->input->post('subject_id');
            $subject_id          = $this->input->post('subject_id');
            $section_id          = $this->input->post('section_id');
            $data['section_id']  = $this->input->post('section_id');
            $data['task']        = $this->input->post('task');
            $data['title']       = $this->input->post('title');
            $data['assign_date'] = date('Y-m-d',strtotime($this->input->post('assign_date')));
            $data['due_date']    = date('Y-m-d',strtotime($this->input->post('due_date')));
            $data['school_id']   = $_SESSION['school_id'];
             if (get_diary_approval_method() == 1){
                 $data['admin_approvel']   = '1' ;
                }
            
            $filename=$_FILES['image1']['name'];
            $folder_name = $_SESSION['folder_name'];
            if($filename!="")
            {
                $folder_name = $_SESSION['folder_name'];
        
                $path = 'uploads/' . $_SESSION['folder_name']."/diary";
        
                if ($folder_name == "") {
                    $path = base_url() . 'uploads/' . $_SESSION['folder_name'];
                } 
                elseif ($is_root == 1) {
                    $path = base_url() . 'uploads/' . $folder_name;
                }
                
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
        
                $filename       =   $_FILES['image1']['name'];
                $ext            =   pathinfo($filename, PATHINFO_EXTENSION);
                $subject_name   =   get_subject_name($subject_id);
                $section_detils =   section_hierarchy($section_id);
                $class_name     =   $section_detils[d]."-".$section_detils[c]."-".$section_detils[s];
                $teacher_name   =   get_teacher_name($_SESSION['user_id']);
                $new_file       =   time()."_".$class_name."_".$subject_name."_".$teacher_name. '.' . $ext;
                copy($_FILES['image1']['tmp_name'], $path . '/' . $new_file);
                $data['attachment'] = $new_file;
                
            } 
            
            $this->db->insert(get_school_db().'.diary', $data);
            $last_id=$this->db->insert_id();
            
            $planner_check=array();
            $planner_check= $this->input->post('planner_check');
            
            if(isset($planner_check) && count($planner_check) > 0)
            {
                foreach($planner_check as $planner)
                {
                    $data2['diary_id'] = $last_id;
                    $data2['planner_id'] = $planner;
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.academic_planner_diary',$data2);
                    $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
                    
                }
            }
            
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('club_updated',get_phrase('diary_not_assigned'));
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('club_updated',get_phrase('diary_assigned_sucessfully'));
            }
            
           
            $sect_name = get_section_name($section_id);
            $tea_name   =   get_teacher_name($_SESSION['user_id']);
            $sub_name   =   get_subject_name($subject_id);
           
            
            $this->session->set_flashdata('club_updated', get_phrase('diary_added_successfully'));
            redirect(base_url() . 'teacher/diary');
        }
        
        elseif ($param1 == 'do_update') 
        {
            
            $data['teacher_id']      = intval($_SESSION['user_id']);
            $data['subject_id']      = $this->input->post('subject_id1');
            $data['section_id']      = $this->input->post('section_id1');
            $data['task']            = $this->input->post('task1');
            $data['title']           = $this->input->post('title1');
            $data['assign_date'] = date('Y-m-d',strtotime($this->input->post('assign_date1')));
            $data['due_date']    = date('Y-m-d',strtotime($this->input->post('due_date1')));
            
            $filename    = $_FILES['image2']['name'];
            $folder_name = $_SESSION['folder_name'];
            if($filename != "")
            {
                $data['attachment'] = file_upload_fun('image2','diary');
                $image_old          = $this->input->post('image_old');
                if($image_old != "")
                {
                    $del_location=system_path($image_old,'diary');
                    file_delete($del_location);
                }
            }               
            
            if (intval($this->input->post('is_submitted')) > 0)
            {
                $data['is_submitted'] = 1;
                $data['submission_date'] = date('Y-m-d h:i:s');
                $data['submitted_by'] = intval($_SESSION['login_detail_id']);
            }
            
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('diary_id', $param2);
            $this->db->update(get_school_db().'.diary', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updated'));
           
            //update in academic_planner_diary table
            $planner_check=array();
            $planner_check= $this->input->post('planner_check');
            
            if(isset($planner_check) && count($planner_check) > 0)
            {
                //delete entries then update
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('diary_id', $param2);
                $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
                $this->db->delete(get_school_db().'.academic_planner_diary');
                foreach($planner_check as $planner)
                {
                    $data2['planner_id']=$planner;
                    $data2['school_id']=$_SESSION['school_id'];
                    $data2['diary_id']= $param2;
                    $this->db->where('school_id',$_SESSION['school_id']);
                    $this->db->where('diary_id', $param2);
                    $this->db->insert(get_school_db().'.academic_planner_diary',$data2);
                
                    $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
                }
            }
           
            redirect(base_url() . 'teacher/diary/');
        }
        elseif ($param1 == 'delete') 
        {
            // $qur_1 = $this->db->query("SELECT a_p_d_id FROM ".get_school_db().".academic_planner_diary WHERE diary_id = $param3 AND school_id = ".$_SESSION['school_id'])->result_array();
            // if(count($qur_1)>0)
            // {
            //     $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use')); 
            //     redirect(base_url() . 'teacher/diary/');
            // }
            // Transection Start
            $this->db->trans_begin();
            
            // Get Diary Attachments
            $get_diary_attachemnet = $this->db->query("SELECT attachment FROM ".get_school_db().".diary WHERE diary_id = $param3 AND school_id = ".$_SESSION['school_id']." ")->result_array();
            $diary_attachemnet = $get_diary_attachemnet[0]['attachment'];
          
            // Delete Diary Attachment Files
            if($diary_attachemnet != ""){
                $del_location = system_path($diary_attachemnet,'diary');
                file_delete($del_location);
            }
            // Get Student Submitted Attachments
            $get_std_attachment = $this->db->query("SELECT da.answer_attachment,da.diary_student_id FROM ".get_school_db().".diary_student ds
    	                            INNER JOIN ".get_school_db().".diary_attachments da on da.diary_student_id  = ds.diary_student_id 
    	                            WHERE ds.diary_id = ".$param3." AND ds.school_id = ".$_SESSION['school_id']." ")->result_array();
    	   foreach($get_std_attachment as $row):
    	       //Delete Student Attachments
    	        $diary_std_attachemnet = $row['answer_attachment'];
    	        if($diary_std_attachemnet != ""){
        	        $del_location = system_path($diary_std_attachemnet,'diary');
                    file_delete($del_location);
    	        }
    	        // Delete Diary Student Attachment Table Entry                
                $this->db->where('diary_student_id', $row['diary_student_id']);
                $this->db->delete(get_school_db().'.diary_attachments');
    	   endforeach;
    	   
    	    // Get Diary Audio
            $get_diary_audio = $this->db->query("SELECT audio FROM ".get_school_db().".diary_audio WHERE diary_id = $param3 ")->result_array();
            $diary_audio = $get_diary_audio[0]['audio'];
            
            // Delete Diary Audio Files
            if($diary_audio != ""){
                $del_audio_location = system_path($diary_audio,'diary_audios');
                file_delete($del_audio_location);
                
                // Delete Diary Audio Table Entry  
                $this->db->where('diary_id', $param3);
                $this->db->delete(get_school_db().'.diary_audio');
            }
         
            // Delete Academic Planner Table Entry
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('diary_id', $param3);
            $this->db->delete(get_school_db().'.academic_planner_diary');
                
            // Delete Diary Student Table Entry
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('diary_id', $param3);
            $this->db->delete(get_school_db().'.diary_student');
            
            // Delete Diary Table Entry
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('diary_id', $param3);
            $this->db->delete(get_school_db().'.diary');
            
            // Transection End
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('club_updated',get_phrase('diary_not_deleted'));
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
            }
            
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'teacher/diary');
            
            //old code
            // $this->db->where('school_id',$_SESSION['school_id']);
            // $this->db->where('diary_id', $param2);
            // $this->db->delete(get_school_db().'.diary');
            // redirect(base_url() . 'teacher/diary/'.$param3);
        }
        elseif ($param1 == 'assign_subjects')
        {
            $this->db->trans_begin();
            
            $diary_id = intval($this->input->post('diary_id'));
            $student_ids = $this->input->post('student_id');
           
            
            $data_diary['is_assigned'] = 1;
            $this->db->where('diary_id',$diary_id);
            $this->db->update(get_school_db().'.diary',$data_diary);
            
            $this->db->query("delete from ".get_school_db().".diary_student where diary_id = $diary_id");
            //insert into student diary
            $student_diary['school_id'] = $_SESSION['school_id'];
            $student_diary['diary_id'] = $diary_id;
            
            $query = "select section_id, teacher_id , subject_id , assign_date , due_date ,  title from ".get_school_db().".diary where diary_id = ".$diary_id." and school_id = ".$_SESSION['school_id']." ";
            $diary_details = $this->db->query($query)->result_array();
            
            $teacher_name = get_teacher_name($diary_details[0]['teacher_id']);
            $subject_name = get_subject_name($diary_details[0]['subject_id']);
            $assign_date = $diary_details[0]['assign_date'];
            $due_date = $diary_details[0]['due_date'];
            $title = $diary_details[0]['title'];
            
            foreach ($student_ids as $key => $value) 
            {
                $student_diary['student_id'] = $value;
                $this->db->insert(get_school_db().'.diary_student', $student_diary);
                
                //_____________sms and email portion__________________________
                ///_________________sms start_________________________________
                $this->load->helper('message');
                $sms_ary = get_sms_detail($value);
                
                $mob_num = $sms_ary['mob_num'];
                $message = "A new assignment has been assigned to " . $sms_ary['student_name'] . " for ". $subject_name . " and is due by ".date_view($due_date).", please login to your account for more details.";
                 
                if (isset($_POST['send_message']) && $_POST['send_message'] != "") {
                   
                    send_sms($mob_num, 'Indici Edu', $message, $value,3);
                }
                
                if (isset($_POST['send_email']) && $_POST['send_email'] != "") {
                    
                    $message = "<b>Dear ".$sms_ary['student_name']." </b> <br><br> A new assignment has been assigned to <br> <strong> Student Name:</strong> " . $sms_ary['student_name'] . " <br> <strong>Subject</strong> : ". $subject_name . " <br> <strong> Due Date: </strong>".date_view($due_date)." <br> To view the assignment, you are requested to logon to ".base_url()."<br>
                    <br> In case of any query, you may please contact with ".$teacher_name." before 2 days of due date";
                    
                    $to_email = $sms_ary['email'];
                    $subject = "New Assignment";
                    $email_layout = get_email_layout($message);
                    email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, $value,00);
                }
                if(get_diary_approval_method() == 1){ //sent notification if approval method is teacher
                    $device_id  =   get_user_device_id(6 , $value , $_SESSION['school_id']);
                    $title      =   "Diary Assigned";
                    $message    =   "A New Diary has been Assigned.";
                    $link       =    base_url()."student_p/diary";
                    sendNotificationByUserId($device_id, $title, $message, $link , $value , 6);
                }

            }
            
            
    	    $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('club_updated',get_phrase('diary_not_assigned'));
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('club_updated',get_phrase('diary_assigned_sucessfully'));
            }
            
        
            
            // added by internees for mbl start
            $sect_name = get_section_name($diary_details[0]['section_id']);
           
            if(get_diary_approval_method() == 1){
                
                foreach ($student_ids as $key => $value) 
                {
                    $parent_idd = get_parent_idd($value);   
                    $stdname    =    get_student_info($value);
                    // print_r($stdname);
                    $std_name   = $stdname[0]['student_name'];
                    $class_idd = get_class_id($diary_details[0]['section_id']);
                    $class_name = get_class_name($class_idd);
                   
                    $d=array(
                    'title'=>$teacher_name,
                    'body'=>$subject_name.' diary added for '.$std_name.' '.$class_name,
                    );
                     $d2 = array(
                    'screen'=>'diary',
                    'student_id'=>$value,
                    'section_id'=>$diary_details[0]['section_id'],
                    'selectedSubject'=>'0',
                    'startDate'=>'',
                    'endDate'=>'',
                    );
                    if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
                    
                   
                } 

            }
            
             // added by internees for mbl end
            
    	    redirect(base_url().'teacher/diary');
           
        }

        if ($param1 == 'create') 
        {
            $data['school_id']=$_SESSION['school_id'];       	
            $data['subject_id']=$this->input->post('subject_id');
            $data['class_id']= $this->input->post('class_id');
            $data['teacher_id']=$this->input->post('teacher_id');
            $data['title']=$this->input->post('title');

            $filename=$_FILES['attach_file']['name'];
            if(!empty($filename))
            {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $data['attachment']=time().'.'.$ext;

                move_uploaded_file($_FILES['attach_file']['tmp_name'],'uploads/diary_image/'.$data['attachment']);
            }
            $c_time_display = new DateTime($this->input->post('due_date'));
            $data['diary_type']=1;
            $date_due=$c_time_display->format('Y-m-d').PHP_EOL;
    		$data['due_date']   	= $date_due;
            $data['task'] 			= $this->input->post('task');
    		$data['date'] 			= Date('Y-m-d');
            $this->db->insert(get_school_db().'.diary', $data);
            redirect(base_url() . 'teacher/diary/'.$data['class_id']);
        }

        if ($param1 == 'create_diary') 
        {
			$data['diary_type']=2;
			$data['school_id']     = $_SESSION['school_id'];
            $data['subject_id']     = $this->input->post('subject_id');
            $data['student_id']     = $this->input->post('student_id');
            $data['class_id']   	= $this->db->get_where(get_school_db().'.subject' , array('subject_id' => $data['subject_id']))->row()->class_id;
            $data['teacher_id'] 	= $this->input->post('teacher_id');
            
            $data['title'] 	= $this->input->post('title');
            $filename=$_FILES['attach_file']['name'];
			
			if(!empty($filename))
            {
    			$ext = pathinfo($filename, PATHINFO_EXTENSION);
    			$data['attachment']=time().'.'.$ext;
    			move_uploaded_file($_FILES['attach_file']['tmp_name'],'uploads/diary_image/'.$data['attachment']);
            }
			$c_time_display = new DateTime($this->input->post('due_date'));
			$date_due	=	$c_time_display->format('Y-m-d').PHP_EOL;
			$data['due_date']   	= $date_due;
            $data['task'] 			= $this->input->post('task');
			$data['date'] 			= Date('Y-m-d');
			
            $this->db->insert(get_school_db().'.diary', $data);
            redirect(base_url() . 'teacher/diary/'.$data['class_id']);
        }
        
        $verify_data	=	array(	'teacher_id' 	=> $_SESSION['user_id'], 'school_id' =>$_SESSION['school_id']);
		$class = $this->db->get_where(get_school_db().'.class' , $verify_data)->row();
		
		$page_data['class_id'] 	 =	$class_id	=	$class->class_id;
		
        $subject = array();
        $d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
        $filter_subject_id = intval($this->input->post('subject'));
        if($filter_subject_id=="" || $filter_subject_id==0)
        {
			$sub_arr = get_teacher_section_subjects($d_c_s_sec); //get section subjects
        	if ( count($sub_arr) > 0 )
        	{
            foreach ($sub_arr as $value) 
            {
                $subject[] = $value; 
            }
        	}
		}
        
        $time_table_t_sub = get_time_table_teacher_subject($login_detail_id);
       
        $sub_in = 0;
        if (count($time_table_t_sub) > 0)
        {
            $sub_in = implode(',', array_unique($time_table_t_sub));
        }
        
        $d_c_s_sec = array();//get_teacher_dep_class_section($login_detail_id);
        $time_table_t_sec = get_time_table_teacher_section($login_detail_id);
        $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        $page_data['teacher_section'] = $teacher_section;

        $sec_in = 0;
        if (count($teacher_section) > 0)
        {
            $sec_in = implode(',', array_unique($teacher_section));
        }
        //filter
        
        $dep_c_s_id = intval($this->input->post('dep_c_s_id'));
        $month_year = $this->input->post('month_year');
        $per_page = 10;
        $apply_filter = $this->input->post('apply_filter', TRUE);
		$std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'", "\""), "", $std_search));
        
        if (!isset($month_year) || $month_year == "") {
            $month_year = $this->uri->segment(3);
        }
        
        if (!isset($month_year) || $month_year == "") {
            $month_year = 0;
        }
                 
        if (!isset($dep_c_s_id) || $dep_c_s_id == "") {
            $dep_c_s_id = $this->uri->segment(4);
        }
        
        if (!isset($dep_c_s_id) || $dep_c_s_id == "") {
            $dep_c_s_id = 0;
        }  
                
        if (!isset($filter_subject_id) || $filter_subject_id == "") {
            $filter_subject_id = $this->uri->segment(5);
        }
        
        if (!isset($filter_subject_id) || $filter_subject_id == "") {
            $filter_subject_id = 0;
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
        } 
        else {
            $start_limit = ($page_num - 1) * $per_page;
        }           

        if ( $filter_subject_id > 0 ) 
        {
            $sub_in = $filter_subject_id;
            $page_data['subject_filter'] = $filter_subject_id;
            $page_data['filter'] = true;
        }
        
        if ($dep_c_s_id > 0)
        {
        	if($filter_subject_id=="" || $filter_subject_id==0)
        	{
    			$sub_arr = get_teacher_timetable_diary_subject($login_detail_id, $dep_c_s_id);
                $sub_id = $sub_arr['ids'];
                if (count($sub_id) > 0 )
                    $sub_in = implode(',', $sub_id);
			}
            
                $sec_in = $dep_c_s_id;
                $page_data['dep_c_s_filter'] = $dep_c_s_id;
                $page_data['filter'] = true;
        }
        
       
        if (($month_year != '') && ($month_year > 0))
        {
            $explode = explode('-', $month_year);
            $month_filter = " and YEAR(dr.assign_date)='".$explode[1]."' and MONTH(dr.assign_date)='".$explode[0]."' ";
            $page_data['month_year'] = $month_year;
            $page_data['subject_month_year'] = $explode[0].'-'.$explode[1];
            $page_data['filter'] = true;
        }
    
        $page_data['subject_id_selected'] = $filter_subject_id;
        $page_data['section'] = $dep_c_s_id;
        $page_data['teacher_section'] = $teacher_section;
    
        $std_query = "";
        if (isset($std_search) && !empty($std_search))
        {
        	$std_query = " AND (
                                    dr.task LIKE '%" . $std_search . "%' OR 
                                    dr.title LIKE '%" . $std_search . "%' 
                                )";
           $page_data['filter'] = true;
        }         
  
        $q = "select da.dairy_audio_id,da.audio, dr.*, sub.name as subject_name, sub.code as subject_code
            FROM ".get_school_db().".diary dr
            INNER JOIN ".get_school_db().".subject sub ON sub.subject_id = dr.subject_id
            INNER JOIN ".get_school_db().".staff staff ON staff.staff_id = dr.teacher_id
            LEFT JOIN ".get_school_db().".diary_audio da ON dr.diary_id = da.diary_id
            Where 
            dr.school_id=".$_SESSION['school_id']." 
            AND staff.user_login_detail_id = $login_detail_id
            AND dr.subject_id in ($sub_in)
            AND dr.section_id in ($sec_in)".
            $month_filter . $std_query."
            order by dr.diary_id desc "; 

        $diary_count = $this->db->query($q)->result_array();
        $total_records = count($diary_count);
        $quer_limit = $q . " limit " . $start_limit . ", " . $per_page . "";
        $diary = $this->db->query($quer_limit)->result_array();
        $config['base_url'] = base_url() . "teacher/diary/" . $month_year . "/". $dep_c_s_id . "/". $filter_subject_id . "/".$std_search;
        
        $page_data['diary']=  $diary_count;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $per_page;

        $config['uri_segment'] = 7;
        $config['num_links'] = 2;
        $config['use_page_numbers'] = TRUE;

        $this->pagination->initialize($config);
        
        $teacher_sections = get_teacher_section($_SESSION['login_detail_id']);
        $page_data['teacher_sections'] = $teacher_sections;

        // $pagination = $this->pagination->create_links();
        // $page_data['start_limit'] = $start_limit;
        // $page_data['total_records'] = $total_records;
        // $page_data['pagination'] = $pagination;
        
        $page_data['apply_filter'] = $apply_filter;
        $page_data['std_search']=$std_search;
        $page_data['page_name']  =	'diary';
        $page_data['page_title'] =	get_phrase('manage_diary');
        $this->load->view('backend/index', $page_data); 
		 
    }
    
    function add_edit_diary()
    {
        $page_data['page_name']  =	'modal_add_diary';
        $page_data['page_title'] =	get_phrase('modal_add_diary');
        $this->load->view('backend/index', $page_data);
    }
    
    function edit_diary()
    {
        $page_data['page_name']  =	'modal_edit_diary';
        $page_data['page_title'] =	get_phrase('modal_edit_diary');
        $this->load->view('backend/index', $page_data);
    }
    
    function assignment_details($diary_id=0)
	{
	    $q = "select ds.* , d.* from  ".get_school_db().".diary_student ds
	          inner join ".get_school_db().".diary d on d.diary_id = ds.diary_id
	          where ds.diary_id = ".$diary_id." and ds.school_id = ".$_SESSION['school_id']." ";
	    $stud_diary = $this->db->query($q)->result_array();
	    
	    $page_data['stud_diary'] = $stud_diary;
	    $page_data['page_name']  = 'assignment_details';
		$page_data['diary_id']   = $diary_id;
		$page_data['page_title'] = get_phrase('assignment_details');
		$this->load->view('backend/index', $page_data);
	}

    function student_list()
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        
        $sub_in = 0;
        /*$subject = array();
        $d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
        $sub_arr = get_teacher_section_subjects($d_c_s_sec); //get section subjects
        if ( count($sub_arr) > 0 )
        {
            foreach ($sub_arr as $value) 
            {
                $subject[] = $value['subject_id']; 
            }
        }

        $time_table_t_sub = get_time_table_teacher_subject($login_detail_id, $yearly_term_id);
       
        if (count($time_table_t_sub) > 0)
        {
            $sub_in = implode(',', array_unique(array_merge($time_table_t_sub, $subject)));
        }
        elseif (count($subject)>0)
        {
            $sub_in = implode(',',array_unique($subject));
        }*/

        //teacher sections
        $d_c_s_sec = array();//get_teacher_dep_class_section($login_detail_id);
        $time_table_t_sec = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
        $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        $page_data['teacher_section'] = $teacher_section;

        //filter
        $filter_subject_id = intval($this->input->post('subject'));
        $dep_c_s_id = intval($this->input->post('dep_c_s_id'));
        
        if ( $filter_subject_id > 0 ) 
        {
            $sub_in = $filter_subject_id;
            $page_data['subject_id_selected'] = $filter_subject_id;
            $page_data['filter'] = true;
        }
        elseif ($dep_c_s_id > 0)
        {
            $sub_arr = get_teacher_diary_subject($login_detail_id, $dep_c_s_id);
            $page_data['dep_c_s_filter'] = $dep_c_s_id;
            $page_data['filter'] = true;

            //subjects in
            $sub_arr = get_teacher_diary_subject($login_detail_id, $dep_c_s_id);
            if (count($sub_arr['ids']) > 0)
                $sub_in = implode(',', $sub_arr['ids']);
        }

        $page_data['student_list'] = $this->db->query("select s.student_id,s.image, s.name as student_name, sub.subject_id, sub.name as subject_name, sub.code as subject_code, sub.subject_id 
                        from ".get_school_db().".student s
                        inner join ".get_school_db().".class_section cs on s.section_id = cs.section_id
                        inner join ".get_school_db().".subject_section ss on ss.section_id = s.section_id 
                        inner join ".get_school_db().".subject sub on sub.subject_id = ss.subject_id 
                        where s.student_status in (".student_query_status().") and sub.subject_id in ($sub_in)
                        and ss.section_id = $dep_c_s_id and s.school_id=".$_SESSION['school_id']." order by s.name")->result_array();
                      //echo   $this->db->last_query();
        $page_data['section_id']  = $dep_c_s_id;
        $page_data['page_name']  = 'student_listing';
        $page_data['page_title'] = get_phrase('Messages');
        $this->load->view('backend/index', $page_data);
    }
    
    function update_chat_message(){
        $login_detail_id = $_SESSION['login_detail_id'];
        $teachers_id     = $_SESSION['user_id'];
        $student_id      = $this->input->post('student_id'); 
        $subject_id      = $this->input->post('subject_id');

        $this->db->query("update ".get_school_db().".messages set is_viewed=1 
                          WHERE teacher_id=$teachers_id and student_id=$student_id and subject_id = $subject_id and messages_type=1 and school_id=".$_SESSION['school_id']." ");

        $query=$this->db->query("select m.* from ".get_school_db().".messages m inner join ".get_school_db().".staff staff on staff.staff_id = m.teacher_id
                                 where staff.user_login_detail_id=$login_detail_id and m.student_id=$student_id and m.subject_id=$subject_id 
                                 and m.school_id=".$_SESSION['school_id']." ORDER BY message_time asc");
        $page_data['parent_message_id']=0;
        $page_data['previous_message_id']=0;
        
        $c=0;
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $rows)
            {
                $data[]=$rows;
                if($c==0)
                {
                    $c=1;
                    $page_data['parent_message_id']=$rows->parent_message_id;
                    if($page_data['parent_message_id']==0)
                    {
                        $page_data['parent_message_id']=$rows->messages_id; 
                    }
                    $page_data['previous_message_id']=$rows->messages_id;  
                }
            }
            $page_data['rows']=$data;
        }
        
        $page_data['student_id'] = $student_id;
        $page_data['page_name']  = 'messenger/ajax_message';
        $this->load->view('backend/teacher/messenger/ajax_message' , $page_data);

    }
    
    function message()
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $teachers_id = $_SESSION['user_id'];
        $student_id= $page_data['student_id'] = $this->uri->segment(3);
        $subject_id= $page_data['subject_id'] = $this->uri->segment(4);

        $this->db->query("update ".get_school_db().".messages set is_viewed=1 WHERE teacher_id=$teachers_id and student_id=$student_id 
                          and subject_id=$subject_id and messages_type=1 and school_id=".$_SESSION['school_id']." ");

        $query=$this->db->query("select m.* from ".get_school_db().".messages m inner join ".get_school_db().".staff staff on staff.staff_id = m.teacher_id
                                 where staff.user_login_detail_id=$login_detail_id and m.student_id=$student_id and m.subject_id=$subject_id and m.school_id=".$_SESSION['school_id']." 
                                 ORDER BY message_time asc");
                           // echo    $this->db->last_query();
                                 
        $page_data['parent_message_id']=0;
        $page_data['previous_message_id']=0;
        
        $c=0;
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $rows)
            {
                $data[]=$rows;
                if($c==0)
                {
                    $c=1;
                    $page_data['parent_message_id']=$rows->parent_message_id;
                    if($page_data['parent_message_id']==0)
                    {
                        $page_data['parent_message_id']=$rows->messages_id; 
                    }
                    $page_data['previous_message_id']=$rows->messages_id;  
                }
            }
            $page_data['rows']=$data;
        }
        
        $page_data['page_name']  = 'messenger/messege_view';
        
        $page_data['page_title']=get_phrase('message');
        $this->load->view('backend/index', $page_data);
    }

    function message_send()
    {
        $data['student_id'] = $student_id = intval($this->input->post('student_id'));
       
        $data['subject_id'] = $subject_id = intval($this->input->post('subject_id'));
        $data['messages']   = trim($this->input->post('message'));
        $msgtombl  = $data['messages'];
       
        
        $data['school_id'] = $_SESSION['school_id'];
       
   
       
        $msg               = $data['messages']; 
        $data['previous_message_id'] = $this->input->post('previous_message_id'); 
        $data['parent_message_id'] = $this->input->post('parent_message_id'); 
        $data['teacher_id']    = $_SESSION['user_id']; 
        $data['is_viewed']     = 0;   
        $data['messages_type'] = 0;//teacher
        $data['sent_by'] = intval($_SESSION['login_detail_id']);
        
        $data['message_time'] = date('Y-m-d H:i:s');
        
        $this->db->insert(get_school_db().'.messages',$data);
        $message_id = $this->db->insert_id();

        //back to listing
        $back_subject_id = intval($this->input->post('back_subject_id')); 
        $back_section_id = intval($this->input->post('back_section_id')); 
        
        /*
        
        //$to=$this->input->post('to');
        $to="kamran.javed@gminns.com";
        //$from=$_SESSION['email'];
        $from="GSIMS";

        $subject="New Message";
        $message="
            Dear Parents,<br/><br/>
            You have a new message from  $student_name <br />
            <br/>
            Message:
            <br/>
             $msg
            <br/>
            <br />
            Please <a href='".base_url() . 'parents/message/'.$_SESSION['user_id']."'>click here</a> to read the message.
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
        */
        
         //______________________________Send Notification__________________
        //_________________________________________________________________
            
        $device_id  =   get_user_device_id(6 , $student_id , $_SESSION['school_id']);
        $title      =   "New Message";
        $message    =   "A New Message Has been Sent To Your Parent By Teacher.";
        $link       =    base_url()."parents/teacher_list/".$_SESSION['user_id']."/".$message_id;
        sendNotificationByUserId($device_id, $title, $message, $link ,$_SESSION['user_id'], 4);
        
        $sect_id =get_student_info1($student_id);
        
        $teacher_namee = get_teacher_name($data['teacher_id']);
        $subject_namee = get_subject_name1($data['subject_id']);
        $subject_code = get_subject_code($data['subject_id']);
        $parent_idd = get_parent_idd($student_id);
       
        $stdname    =    get_student_info($student_id);
               
        $std_name   = $stdname[0]['student_name'];
        $class_idd = get_class_id($sect_id->section_id);
       
        $class_name = get_class_name($class_idd);
    //   print_r('detailed '.$_SESSION['login_detail_id']);exit;
    
    
    $newqry = "SELECT user_login_detail_id FROM ".get_school_db().".student WHERE student_id =".$student_id;
    $std_login_detail_id = $this->db->query($newqry)->row();
    $std_login_detail_id = $std_login_detail_id->user_login_detail_id ?? '0';

   
   
   if($std_login_detail_id)
   {
     
        $d=array(
        'title'=>$teacher_namee.' send a message to '.$std_name.' '.$class_name,
        'body'=>$msgtombl,
        );
        $d2 = array(
            'screen'=>'chat',
            'student_id'=>$student_id,
            'sent_by'=>$_SESSION['login_detail_id'],
            'student_login_detail_id'=>$std_login_detail_id,
            'type'=>'teacher',
            'subject_id'=>$subject_id,
            'subject_name'=>$subject_namee,
            'subject_code'=>$subject_code,
            'teacher_name'=>$teacher_namee,
            'section_id'=>$sect_id->section_id,
            'academic_year_id'=>$_SESSION['academic_year_id'],
            'teacher_id'=>$data['teacher_id'],
            
            );
           
       
        if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
        
   }
   else{
       echo '!0';
   }
   

        
        
        $this->session->set_flashdata('club_updated', get_phrase('message_sent_successfully'));
        redirect(base_url() . 'teacher/message/'.$student_id.'/'.$subject_id.'/'.$back_subject_id.'/'.$back_section_id); 
    }
    
  

    function get_section_student_subject()
    {
        $subject_list = teacher_subject_option_list($_SESSION['login_detail_id'],intval($this->input->post('section_id')));
        $list_array=array();
        $list_array['subject']=$subject_list;
        echo json_encode($list_array);
    }
    
    function get_subject_teacher()
    {
        echo subject_teacher_option_list($this->input->post('subject_id'));
    }
	
    /****MANAGE EXAM MARKS*****/
    function marks($exam_id = 0, $class_id = 0, $subject_id = 0)
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        $msg=$this->uri->segment('3');
        if(isset($msg))
		{
			
			$page_data['msg']=$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
			
			$exam_id    = $this->uri->segment('4');
            $section_id   = $this->uri->segment('5');
            $subject = $this->uri->segment('6');
            
			
		}
		
        if ($this->input->post('operation') == 'selection') 
        {
        	
            $page_data['exam_id']    = intval($this->input->post('exam_id'));
            $page_data['class_id']   = intval($this->input->post('class_id'));
            $page_data['subject_id'] = intval($this->input->post('subject_id'));
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0) {
                redirect(base_url() . 'teacher/marks/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['subject_id']);
            } else {
                $this->session->set_flashdata('mark_message', get_phrase('choose_exam_class_and_subject'));
                redirect(base_url() . 'teacher/marks/');
            }
        }
        
        if ($this->input->post('operation') == 'update') 
        {
			$student_id		=	$_REQUEST['student_id'];
			$mark_obtained	=	$_REQUEST['mark_obtained'];
			$comment		=	$_REQUEST['comment'];
			$mark_id		=	$_REQUEST['mark_id'];
			$exam_id		=	$_REQUEST['exam_id'];
			$class_id		=	$_REQUEST['class_id'];
			$subject_id		=	$_REQUEST['subject_id'];
			$attend			=	$_REQUEST['attend'];
			$i=0;
			foreach($student_id as $key) 
            {
				$i++;
			}

			for($j=0;$j<=$i-1;$j++)
			{
				$data['mark_obtained'] = $mark_obtained[$j];
				$data['attendance']    = $attend[$j];
				$data['comment']       = $comment[$j];
				
				$this->db->where('school_id',$_SESSION['school_id']);
				$this->db->where('mark_id', $mark_id[$j]);
				$this->db->update(get_school_db().'.mark', $data);
			}
		    redirect(base_url() . 'teacher/marks/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id') . '/' . $this->input->post('subject_id'));
        }

        //filter
        if(empty($section_id))
        {
			$section_id = intval($this->input->post('section_id'));
		}
		if(empty($subject))
        {
			$subject = intval($this->input->post('subject'));
		}
		if(empty($exam_id))
        {
			$exam_id = intval($this->input->post('exam_id'));
		}

        if ($exam_id > 0 || $section_id > 0 || $subject > 0)
        {
            $page_data['filter'] = true;
        }
		$page_data['subject_id']      = $this->input->post('subject'); //$subject;
        $page_data['exam_id']         = $exam_id;
        $page_data['section_id']      = $section_id;
        $page_data['teacher_section'] = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
        $page_data['page_info']       = get_phrase('exam_marks');        
        $page_data['page_name']       = 'marks';
        $page_data['page_title']      = get_phrase('manage_exam_marks');
        
        $this->load->view('backend/index', $page_data);
    }
    
    function marks_list_pdf()
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];

        $page_data['exam_id']    = intval($this->input->post('exam_id'));
        $page_data['section_id']   = intval($this->input->post('section_id'));
        $page_data['subject_id'] = intval($this->input->post('subject_id'));
        $page_data['page_info']       = get_phrase('marks_list_pdf');        
        $page_data['page_name']       = 'marks_list_pdf';
        $page_data['page_title']      = get_phrase('marks_list_pdf');
        $this->load->library('Pdf');
        $view = 'backend/teacher/marks_list_pdf';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->set_paper("A4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }

    function get_section_subjects_list($subject_id=0)
    {
        $section_id = intval($this->input->post('section_id'));
        $yearly_term = $_SESSION['yearly_term_id'];
        $login_detail_id = $_SESSION['login_detail_id'];
            
        $subject_qry = $this->db->query("select s.subject_id, s.name, s.code  
                    from ".get_school_db().".class_routine cr 
                    inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                    inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
                    inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                    inner join ".get_school_db().".subject s on st.subject_id=s.subject_id
                    inner join ".get_school_db().".exam_routine er on (er.subject_id=s.subject_id and er.section_id=$section_id)
                    inner join ".get_school_db().".staff staff on st.teacher_id=staff.staff_id
                    where 
                    staff.user_login_detail_id = $login_detail_id
                    and cr.school_id=".$_SESSION['school_id']."
                    and crs.section_id = $section_id
                    group by s.subject_id
                    ")->result_array();

        echo '<option value="">'.get_phrase('select_subject').'</option>';     
        foreach ($subject_qry as $key => $value) 
        {
            $selected = '';
            if ($subject_id==$value['subject_id'])
                $selected = 'selected';
            echo "<option value='".$value['subject_id']."' $selected>".$value['name'].' - '.$value['code']."</option>";
        }
    }
    function get_all_grades()
    {
        $q="select grade_point,mark_from,mark_upto from ".get_school_db().".grade where school_id=".$_SESSION['school_id']."";
        $resGrades=$this->db->query($q)->result_array();
        foreach($resGrades as $arr)
        {
            $gradeArr[$arr['grade_point']]['mark_from'][]=$arr['mark_from'];
            $gradeArr[$arr['grade_point']]['mark_upto'][]=$arr['mark_upto'];
        }
        echo json_encode($gradeArr); 
    }
    
    
    function check_status_attr($status){
        echo $status;
    }

    function save_exam_marks()
    {
        
        $data=array();
        $std_arr=array();
        $marks_arr=array();
        $exam_id=$this->input->post('exam_id');
        $subject_id=$this->input->post('subject_id');
        $section_id=$this->input->post('section_id');
       	
        $student_marks_array=json_decode($_POST['student_arr']);
        $exam_marks=$this->input->post('exam_marks');
        $comp_arr=json_decode($_POST['comp_arr']);
        $submit_val=$this->input->post('submit_val');
		$is_submitted=$this->input->post('is_submitted');
		$is_approve=$this->input->post('is_approve');
		if($submit_val=='save')
		{
			$q="SELECT exam_routine_id FROM ".get_school_db().".exam_routine WHERE exam_id=$exam_id AND section_id=$section_id AND subject_id=$subject_id AND school_id=".$_SESSION['school_id']." ";
			$query=$this->db->query($q)->result_array();
			foreach($query as $row)
			{
				$this->db->set('is_submitted', $is_submitted);
				$this->db->set('is_approved',0);
				$this->db->where('exam_routine_id', $row['exam_routine_id']);
				$this->db->update(get_school_db().'.exam_routine');
				//echo $this->db->last_query();
			} 		
		}
		elseif($submit_val=='submit')
		{
			$date_submitted=date('Y-m-d h:i:sa');
			$q="SELECT exam_routine_id FROM ".get_school_db().".exam_routine WHERE exam_id=$exam_id AND section_id=$section_id AND subject_id=$subject_id AND school_id=".$_SESSION['school_id']." ";
			$query=$this->db->query($q)->result_array();
			foreach($query as $row)
			{
				$this->db->set('is_approved',0);
				$this->db->set('is_submitted', $is_submitted);
				$this->db->set('date_submitted',$date_submitted);
				$this->db->set('submitted_by',$_SESSION['login_detail_id']);
				$this->db->where('exam_routine_id', $row['exam_routine_id']);
				$this->db->update(get_school_db().'.exam_routine');
				echo $this->db->last_query();
			} 		
		}
       	elseif($submit_val=='approve')
		{
			$date_approved=date('Y-m-d h:i:sa');
			$q="SELECT exam_routine_id FROM ".get_school_db().".exam_routine WHERE exam_id=$exam_id AND section_id=$section_id AND subject_id=$subject_id AND school_id=".$_SESSION['school_id']." ";
			$query=$this->db->query($q)->result_array();
			foreach($query as $row)
			{
				$this->db->set('is_approved', $is_approve);
				$this->db->set('date_approved',$date_approved);
				$this->db->set('approved_by',$_SESSION['login_detail_id']);
				$this->db->where('exam_routine_id', $row['exam_routine_id']);
				$this->db->update(get_school_db().'.exam_routine');
			} 		
		}
        
        if($exam_marks!='')
        { 
            foreach($student_marks_array as $arr){
            
                $std_arr[$arr->student_id]['marks']=$arr->exam_marks;
                $std_arr[$arr->student_id]['comment']=$arr->comment;
            }
        }
        else{
            foreach($student_marks_array as $arr){
                $std_arr[$arr->student_id]=$arr->comment;
            }
        }
        
        if($comp_arr){
            foreach($comp_arr as $arr){
                $marks_arr[$arr->student_id][$arr->subject_comp_id]['m']=$arr->marks;
            }
            //echo "<pre>";print_r($marks_arr);
        }
        
        
        if($exam_marks=='')
        {
            foreach($std_arr as $k=>$v)
            {
                $q="select m.* from ".get_school_db().".marks m 
                        where 
                        m.student_id =".$k." AND 
                        m.subject_id=".$subject_id." AND 
                        m.exam_id=".$exam_id." AND 
                        m.school_id=".$_SESSION['school_id']."
                    ";
                $resMarks=$this->db->query($q)->num_rows();
                $resMarksArr=$this->db->query($q)->result_array();
                
                $data['student_id']=$k;
                $data['subject_id']=$subject_id;
                $data['exam_id']=$exam_id;
                $data['comment']=$v;
                $data['school_id']=$_SESSION['school_id'];
        
    
                if($resMarks==0)
                {
                    if($this->db->insert(get_school_db().'.marks', $data)){
                        $marks_id=$this->db->insert_id();
                        if(sizeof($marks_arr)>0){
                            foreach($marks_arr[$k] as $key=>$val){
                                if($val['m']!='')
                                {
                                    $comp['subject_component_id']=$key;
                                    $comp['marks_obtained']=$val['m'];
                                    $comp['marks_id']=$marks_id;
                                    $comp['school_id']=$_SESSION['school_id'];  
                                    $this->db->insert(get_school_db().'.marks_components', $comp);  
                                    $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully._'));
                                    //echo "added1";
                                }

                            }
                        }
                    }
                }
                else
                {
                    foreach($resMarksArr as $mark){ 
                    //echo "<pre>";print_r($mark);
                    $marks_id=$mark['marks_id'];
                    $comp_id=$mark['subject_component_id'];
                    $q2="select marks_id from ".get_school_db().".marks_components where marks_id=".$marks_id."";
                    $resMarkcomp=$this->db->query($q2)->result_array();
                    $this->db->where('marks_id',$marks_id);
                    $this->db->update(get_school_db().'.marks',$data);
                    if(sizeof($resMarkcomp)>0){
                        foreach($marks_arr[$k] as $key=>$val){
                            
                                
                                $q="select * from ".get_school_db().".marks_components where subject_component_id=$key and marks_id=$marks_id";                         
                                $num=$this->db->query($q)->num_rows();
                                
                                if($num==0)
                                {
                                    echo $comp_add['marks_obtained']=$val['m'];
                                    $comp_add['subject_component_id']=$key;
                                    $comp_add['marks_id']=$marks_id;
                                    $comp_add['school_id']=$_SESSION['school_id'];
                                    $this->db->insert(get_school_db().'.marks_components',$comp_add);
                                    
                                }
                                else{
                                $comp['marks_obtained']=$val['m'];
                                $this->db->where('subject_component_id',$key);
                                $this->db->where('marks_id',$marks_id);
                                $this->db->where('school_id',$_SESSION['school_id']);
                                
                                $this->db->update(get_school_db().'.marks_components', $comp);
                                    
                                }
                        }   
                    }
                    else{
                        if(sizeof($marks_arr)>0){
                            foreach($marks_arr[$k] as $key=>$val){
                                if($val['m']!=''){
                                    $comp['subject_component_id']=$key;
                                    $comp['marks_obtained']=$val['m'];
                                    $comp['marks_id']=$marks_id;
                                    $comp['school_id']=$_SESSION['school_id'];  
                                    $this->db->insert(get_school_db().'.marks_components', $comp);  
                                }
                            }
                        }
                    }
                
                    }
                }
            
                
            }
            $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
        echo "added";exit;}
        else{
            foreach($std_arr as $k=>$v){
                
                $q="select m.* from ".get_school_db().".marks m  where m.student_id =".$k." AND m.subject_id=".$subject_id." AND m.exam_id=".$exam_id." AND m.school_id=".$_SESSION['school_id']."";
                $resMarks=$this->db->query($q)->num_rows();
                $resMarksArr=$this->db->query($q)->result_array();

                $data['student_id']=$k;
                $data['subject_id']=$subject_id;
                $data['exam_id']=$exam_id;
                $data['comment']=$v['comment'];
                $data['school_id']=$_SESSION['school_id'];
                if($resMarks==0)
                {
                    if($this->db->insert(get_school_db().'.marks', $data)){
                        $marks_id=$this->db->insert_id();
                        $comp['subject_component_id']=0;
                        $comp['marks_obtained']=$v['marks'];
                        $comp['marks_id']=$marks_id;
                        $comp['school_id']=$_SESSION['school_id'];  
            
                        $this->db->insert(get_school_db().'.marks_components', $comp);
                    }
                }
                else{
                    foreach($resMarksArr as $mark){ 
                        $marks_id2=$mark['marks_id'];
                        $this->db->where('marks_id',$marks_id2);
                        $this->db->update(get_school_db().'.marks',$data);
                        $comp['subject_component_id']=0;
                        $comp['marks_obtained']=$v['marks'];
                        $comp['marks_id']=$marks_id2;
                        $comp['school_id']=$_SESSION['school_id'];  
                
                        $this->db->where('marks_id',$marks_id2);
                        $this->db->where('school_id',$_SESSION['school_id']);
                        $this->db->update(get_school_db().'.marks_components', $comp);

                    }
                }
            }
        $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
        
        echo "updated";
        exit;}
    }

    function reset_exam_marks()
    {
    	
        $student_marks_array=json_decode($_POST['student_arr']);
        $exam_marks=$this->input->post('exam_marks');
        $exam_id=$this->input->post('exam_id');
        $subject_id=$this->input->post('subject_id');
        $section_id=$this->input->post('section_id');
        
        $array = array(
        'is_submitted' => 0,
        'date_submitted' => 0,
        'submitted_by' => 0,
        'is_approved'=>0,
        'approved_by'=>0,
        'date_approved'=>0
		);
        $this->db->set($array);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('exam_id',$exam_id);
        $this->db->where('subject_id',$subject_id);
        $this->db->where('section_id',$section_id);
        $this->db->update(get_school_db().'.exam_routine');
        //print_r($_POST);
        if($exam_marks!=''){ 
            foreach($student_marks_array as $arr){
            
                $std_arr[$arr->student_id]['marks']=$arr->exam_marks;
                $std_arr[$arr->student_id]['comment']=$arr->comment;
            }
        }
        else
        {
            foreach($student_marks_array as $arr){
                $std_arr[$arr->student_id]=$arr->comment;
            }
        }
        
        foreach($std_arr as $k=>$v)
        {
            $q="select marks_id from ".get_school_db().".marks where student_id=$k and subject_id = $subject_id and exam_id = $exam_id";
            $marks_id=$this->db->query($q)->result_array();
            if(sizeof($marks_id)>0)
            {
                foreach($marks_id as $i=>$j)
                {
                    $delMarksComponents="delete from ".get_school_db().".marks_components where marks_id=".$j['marks_id'];
                    $this->db->query($delMarksComponents);
                }
                $delMarks="delete from ".get_school_db().".marks where student_id=$k and subject_id = $subject_id and exam_id = $exam_id";
                $this->db->query($delMarks);
            }
        }
        redirect(base_url().'teacher/marks');
    }
    
    /**********Academic Planner******************/
    function academic_planner($classname='',$subjectname='') 
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        $academic_year_id = $_SESSION['academic_year_id'];

        $page_data['page_name']  = 'academic_planner';
        $page_data['page_title']  = get_phrase('academic_planner');
        
        // new procedure
        $subject = array();
        $d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
        $sub_arr = get_teacher_section_subjects($d_c_s_sec); //get section subjects
        if ( count($sub_arr) > 0 )
        {
            foreach ($sub_arr as $value) 
            {
                $subject[] = $value['subject_id']; 
            }
        }

        $time_table_t_sub = get_time_table_teacher_subject($login_detail_id);
        $sub_in = 0;
        if (count($time_table_t_sub) > 0)
        {
            $sub_in = implode(',', array_unique(array_merge($time_table_t_sub, $subject)));
        }
        elseif (count($subject)>0)
        {
            $sub_in = implode(',', array_unique($subject));
        }
        
        $d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
        $time_table_t_sec = get_time_table_teacher_section($login_detail_id);
        $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        $page_data['teacher_section'] = $teacher_section;
        $sec_in = 0;
        if (count($teacher_section) > 0)
        {
            $sec_in = implode(',', array_unique($teacher_section));
        }
        //filters
        $filter_subject_id = intval($this->input->post('subject'));
        $dep_c_s_id = intval($this->input->post('dep_c_s_id'));
        
        if ( $filter_subject_id > 0 ) 
        {
            $sub_in = $filter_subject_id;
            $page_data['subject_filter'] = $filter_subject_id;
            $page_data['filter'] = true;
        }
        if ($dep_c_s_id > 0)
        {
            $sec_in = $dep_c_s_id;
            $page_data['dep_c_s_filter'] = $dep_c_s_id;
            $page_data['filter'] = true;
        }
        

        $month_year = $this->input->post('month_year');
        if ($month_year != '')
        {
            $explode = explode('-', $month_year);
            $month_filter = " and YEAR(ap.start)='".$explode[1]."' and MONTH(ap.start)='".$explode[0]."' ";
            $page_data['month_year'] = $month_year;
            $page_data['subject_month_year'] = $explode[0].'-'.$explode[1];
            $page_data['filter'] = true;
        }

        $planner_arr = $this->db->query("select sub.name as subject,sub.code as subject_code, ap.* 
            from ".get_school_db().".academic_planner ap
            inner join ".get_school_db().".subject sub on sub.subject_id = ap.subject_id
            where ap.subject_id in ($sub_in) and ap.is_active = 1 and ap.school_id = ".$_SESSION['school_id']." $month_filter order by ap.start desc ")->result_array();
    
    
        $page_data['subss'] = $sub_in;
        $page_data['subject_id_selected'] = $filter_subject_id;
        $page_data['section'] = $dep_c_s_id;
        $page_data['planner_arr'] = $planner_arr;
        $this->load->view('backend/index', $page_data);
    }
    
    /****** DAILY ATTENDANCE *****************/
    function manage_attendance($date='',$month='',$year='',$class_id='')
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $page_data['date']  = $date;
        $page_data['month'] = $month;
        $page_data['year'] = $year;
        $page_data['class_id'] = $class_id;
        $page_data['page_name'] = 'manage_attendance';
        $page_data['page_title'] = get_phrase('daily_attendance');
        $this->load->view('backend/index', $page_data);
    }
    
    function manage_attendance_student($date='',$month='',$year='',$class_id='')
    {
        
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
	    $page_data['filter'] = true;

        $month_year = $this->input->post('month_year');
        if ($month_year != '' && $month_year != date('m-Y'))
        {
            $explode = explode('-', $month_year);
            $month_filter = " and YEAR(ap.start)='".$explode[1]."' and MONTH(ap.start)='".$explode[0]."' ";
            $page_data['month_year'] = $month_year;

            $month_year = '01-'.$month_year;
            $page_data['date'] = date('d', strtotime($month_year));
            $page_data['month'] = date('m',strtotime($month_year));
            $page_data['year'] = date('Y',strtotime($month_year));
            $page_data['month_filter'] = true;
        }

        $page_data['page_name'] = 'manage_attendence_student';
        $page_data['page_title'] = get_phrase('manage_daily_attendance');
        $this->load->view('backend/index', $page_data);
    }
    
    function manage_subjectwise_attendance()
    {
		$filter = 0;
		
		if($_POST)
		{
		  $filter     = 1;
		  $section_id = $this->input->post('section_id');
		  $subject_id = $this->input->post('subject_id');
		  $date_new	  =	explode("/",$_REQUEST['date']);
          $date       =	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
	 	  $date       = $date_new['0'];
		  $month      = $date_new['1'];
		  $year       = $date_new['2'];
		}
		
		$page_data['filter']        =   $filter;
	 	$page_data['date']		    =	$date;
		$page_data['month']		    =	$month;
	    $page_data['year']		    =	$year;
		$page_data['section_id']	=	$section_id;
		$page_data['subject_id']    =   $subject_id;
		$page_data['page_name']		=	'manage_subjectwise_attendance';
		$page_data['page_title']	=	get_phrase('manage_subjectwise_attendance');

		$this->load->view('backend/index', $page_data);
    }
    
    function apply_subjectwise_attendence($date='',$month='',$year='',$section_id='')
	{
	    $this->db->trans_begin();
	    
	    $send_sms = $this->input->post('send_sms');
	    
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
		$section_id  = $this->input->post('section_id');
		$subject_id  = $this->input->post('subjectId');
		$student_id  = $_POST['student_id'];
        $date_today  = $year.'-'.$month.'-'.$date;
		
		for($j=0;$j<=count($student_id)-1;$j++)
		{
            $verify_data	=	array(
    			 'student_id'=> $student_id[$j],
    			 'subject_id'=> $subject_id,
    			 'date'      => $date_today,
    			 'school_id' => $_SESSION['school_id']
			 );
			 
			$attendance_status = 0;
			if (isset($_POST["status-$j"])){
				$attendance_status = $_POST["status-$j"];
			}else{
				$attendance_status = 2;
			}
			
			$attendance = $this->db->get_where(get_school_db().'.subjectwise_attendance' , $verify_data);
			if($attendance->num_rows()>0)
			{
              	if($attendance->row()->status==3)
              	{
					
				}else{
					$attendance_id=$attendance->row()->attendance_id;
					$this->db->where('school_id',$_SESSION['school_id']);
					$this->db->where('attendance_id' , $attendance_id);
					$this->db->update(get_school_db().'.subjectwise_attendance' , array('subject_id' => $subject_id,'status' => $attendance_status,'user_id'=>$_SESSION['login_detail_id']));
           		}
            }else{
			    $this->db->insert(get_school_db().'.subjectwise_attendance' , array('subject_id' => $subject_id,'status' => $attendance_status,'user_id'=>$_SESSION['login_detail_id'],'student_id'=>$student_id[$j],'date'=>$date_today,'school_id' =>$_SESSION['school_id']));
            }
            
			if($attendance_status==2 && (int) $send_sms == 1)
			{
			    $this->load->helper('message');
			    
				$student_detail=  get_sms_detail($student_id[$j]);
				$numb          =  $student_detail['mob_num'];
				$student_name  =  $student_detail['student_name'];
				$class_name    =  $student_detail['class_name'];
				$section_name  =  $student_detail['section_name'];
				$to_email      =  $student_detail['email'];
				$message       =  "$student_name of $class_name - $section_name is absent on (".date('d-M-Y', mktime(0, 0, 0, $month, $date, $year)).")";

				send_sms($numb,'Indici Edu', $message, $student_id[$j],5);
			} 
			
		}
		
		$this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('club_updated',get_phrase('attendance_cannot_be_marked'));
            $this->db->trans_rollback();
        } else {
            $this->session->set_flashdata('club_updated',get_phrase('attendance_marked_successfully'));
            $this->db->trans_commit();
        }
		
		//redirect(base_url() . 'attendance/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$section_id.'/');
	
		$page_data['date']			=	$date;
		$page_data['month']	   	    =	$month;
		$page_data['year']			=	$year;
		$page_data['section_id']	=	$section_id;
		$page_data['subject_id']	=	$subject_id;
		$page_data['page_name']		=	'manage_subjectwise_attendance';
		$page_data['page_title']	=	get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
		
	}
    
    function view_subjectwise_attendance()
    {
        if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $section_id = $_POST['section_id'];
	    $apply_filter = $_POST['apply_filter'];
	    
	    
	    if(isset($apply_filter) && $apply_filter != ""){
            $query="SELECT * FROM ".get_school_db().".student WHERE section_id=$section_id AND school_id=".$_SESSION['school_id']." AND  student_status IN (".student_query_status().") ORDER BY roll desc";
        	$students=$this->db->query($query)->result_array();
    		$page_data['students']		    =	$students;
	    }else{
	        $page_data['students']		    =	array();
	    }
		
		$page_data['section_id']		    =	$section_id;
    	$page_data['apply_filter']		    =	$apply_filter;
		$page_data['page_name']		    =	'view_subjectwise_attendance';
		$page_data['page_title']		=	get_phrase('view_subjectwise_attendance');
		$this->load->view('backend/index', $page_data);
    }
    function student_subjectwise_attendance($student_id)
    {
    
        if(isset($_POST['month'])){
            $date_month=$_POST['month'];
            $date_month = date("m", strtotime($date_month));
            $date_year=$_POST['year'];
        }else{
            $date_month = date('m');
            $date_year = date('Y');
        }
        
        $page_data['stud_id']		    =	str_decode($student_id);
        $page_data['date_month']		    =	$date_month;
        $page_data['date_year']		    =	$date_year;
		$page_data['page_name']		    =	'student_subjectwise_attendance';
		$page_data['page_title']		=	get_phrase('student_subjectwise_attendance');
		$this->load->view('backend/index', $page_data);
    }
    //apply attendence
    function apply_attendence($section_id = 0)
    {
        $this->db->trans_begin();
        
        $date       = date('d');
        $month      = date('m');
        $year       = date('Y');
        $student_id = $this->input->post('student_id');
        $date_today = $year.'-'.$month.'-'.$date;
        
        $send_sms = $this->input->post('send_sms');
        
        for($j=0; $j<=count($student_id)-1; $j++)
        {
            
            $device_id   =   get_user_device_id(6 , $student_id[$j] , $_SESSION['school_id']);
            $title       =   "Attendance Marked";
            $message     =   "Your Attendance Has been Marked By The Teacher.";
            $link        =    base_url()."student_p/manage_attendance";
            sendNotificationByUserId($device_id , $title , $message , $link ,  $student_id[$j] , 6);
            
            $verify_data =  array('student_id'=> $student_id[$j], 'date'=> $date_today, 'school_id'=>$_SESSION['school_id'] );
            $attendance  = $this->db->get_where(get_school_db().'.attendance' , $verify_data);
            if($attendance->num_rows() != 0)
            {
                $attendance_id = $attendance->row()->attendance_id;
                $status_id     = $attendance->row()->status;
                
                // $attendance_status = $this->input->post("status-$j");
               
                
                
                if($status_id == 3){
                    $status_id1 =3;  
                }else{
                    $status_id1 =2;
                }

                $sts = '';
                if (intval( $this->input->post('status-'.$j)) > 0)
                {
                    $sts = $this->input->post('status-'.$j);
                }
                else
                    $sts = $status_id1;
                  //  print_r($sts);exit();

                $this->db->where('attendance_id' , $attendance_id);
                $this->db->where('school_id' , $_SESSION['school_id']);
                
                $this->db->update(get_school_db().'.attendance', array( 'status' => $sts, 'user_id'=> $_SESSION['login_detail_id'] ) );
                
                	if($status_id == 2 && $sts == 1){
					 
					     $this->db->where('attendance_id',$attendance_id);
					     $this->db->update(get_school_db().'.attendance_timing' , array('check_in'=> date("h:i:s a")));
					}
					
						   // 	Update Attendance Timing Table 
					if($sts ==2):
					    	
				        $this->db->where('attendance_id',$attendance_id)->update(get_school_db().'.attendance_timing' , array('check_in'=>''));
				    endif;
                
                	
                    
            }
            else
            {
                   $attendance_status = $this->input->post("status-$j");
                  
                   
                $this->db->insert(get_school_db().'.attendance', 
                array(
                    'status'     =>  ( intval($this->input->post("status-$j")) > 0 ? $this->input->post("status-$j") : 2 ),
                    'user_id'    =>  $_SESSION['login_detail_id'],
                    'student_id' =>  $student_id[$j],
                    'date'       =>  $date_today,
                    'school_id'  =>  $_SESSION['school_id']
                ));
             
                
                 $last_id = $this->db->insert_id();
                 $check_in = date("h:i:s a");
                
                 if($attendance_status == 1){
                                $data['check_in'] = $check_in;
                            }else{
                                $data['check_in']  = "";
                            }
                    $data['attendance_id'] = $last_id;
				    $this->db->insert(get_school_db().'.attendance_timing' , $data);
				    
            }
            
            
            $attendance_status = 0;
			if (isset($_POST["status-$j"])){
				$attendance_status = $_POST["status-$j"];
			}else{
				$attendance_status = 2;
			}
            if($attendance_status==2  && (int) $send_sms == 1){
			    $this->load->helper('message');
				$message_val   =  $this->db->query("select * from ".get_school_db().".sms_settings  where school_id=".$_SESSION['school_id'],"  and sms_type=1")->result_array();
				$student_detail=  get_sms_detail($student_id[$j]);
				$numb          =  $student_detail['mob_num'];
				$student_name  =  $student_detail['student_name'];
				$class_name    =  $student_detail['class_name'];
				$section_name  =  $student_detail['section_name'];
				$to_email      =  $student_detail['email'];
				$message       =  "$student_name of $class_name - $section_name is absent on (".date('d-M-Y', mktime(0, 0, 0, $month, $date, $year)).")";
				
				if($message_val[0]['status'] ==1){
					send_sms($numb,'Indici Edu', $message, $student_id[$j] , 00);
				}
			}
			
			 //added by interns start
           
            $parent_idd = get_parent_idd( $student_id[$j]);   
            $stdname    =    get_student_info( $student_id[$j]);
            $std_name   = $stdname[0]['student_name'];
            $student_info_for_callan = get_student_name_and_academic_year_id($student_id[$j], $_SESSION['school_id']);
           // $class_idd = get_class_id($data['section_id']);
           // $class_name = get_class_name($class_idd);
            
           $d=array(
            'title'=>'Attendance Marked For '.$std_name,
            'body'=>'Your Attendance Has been Marked By The Teacher.',
            );
             $d2 = array(
            'screen'=>'attendence',
            'student_id'=> $student_id[$j],
            'section_id'=> $student_info_for_callan['section_id'],
            'academic_year_id'=> $student_info_for_callan['academic_year_id'],
            
            );
            if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
          
            //added by interns end
        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('club_updated',get_phrase('students_attendance_not_marked'));
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('club_updated',get_phrase('students_attendance_marked_successfully'));
        }
        
        redirect(base_url() . 'teacher/manage_attendance_student/');
    }
    /**********MANAGE Leaves********************/
    function manage_leaves($param1 = '', $param2 = '')
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        if ($param1 == 'create') 
        {
            $start_date = $this->input->post('start_date');
            $start_date=explode('/',$start_date);
            
            $end_date = $this->input->post('end_date');
			$end_date=explode('/',$end_date);
            $proof      =   $_FILES['userfile']['name'];
            $proof_doc="";
            if($_FILES['userfile']['tmp_name'] != "")
            {
                $proof_doc  =   $_SESSION['user_id']."-".$proof;
            }
            if($proof!="")
            {
                $data['proof_doc']=file_upload_fun('userfile','leaves_staff','');
            }

            $data['staff_id']          = $_SESSION['user_id'];
            $data['requested_by']      = $login_detail_id;
            $data['school_id']         = $_SESSION['school_id'];
            $data['leave_category_id'] = intval($this->input->post('leave_id'));
            $data['request_date']      = date('Y-m-d');
            
            $data['start_date']        = date('Y-m-d', strtotime($this->input->post('start_date'))); 
            $data['end_date']          = date('Y-m-d', strtotime($this->input->post('end_date'))); 
            
            $data['reason']            = $this->input->post('reason');
            $data['status']            = 0;
            $data['yearly_terms_id']   = $_SESSION['yearly_term_id'];
            $this->db->insert(get_school_db().'.leave_staff', $data);
            
            $school_admins = get_school_admins();
            foreach($school_admins as $admin){
                $device_id  =   get_user_device_id(1 , $admin['user_login_detail_id'] , $_SESSION['school_id']);
                $title      =   "New Leave Request";
                $message    =   "A Leave Request Has been Submitted By Teacher.";
                $link       =    base_url()."leave_staff/manage_leaves_staff";
                sendNotificationByUserId($device_id, $title, $message, $link , $admin['user_login_detail_id'] , 1);
            }
            redirect(base_url() . 'teacher/manage_leaves');            
        }

        $page_data['leaves'] = $this->db->query("select sl.* from 
            ".get_school_db().".leave_staff sl
            inner join ".get_school_db().".yearly_terms yt on yt.yearly_terms_id=sl.yearly_terms_id 
            inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id=yt.academic_year_id 
            inner join ".get_school_db().".staff staff on staff.staff_id = sl.staff_id
            where 
            staff.user_login_detail_id=$login_detail_id
            and sl.school_id= ".$_SESSION['school_id']."
            and ay.academic_year_id=".$_SESSION['academic_year_id']."
            order by sl.leave_staff_id desc
            ")->result_array();
        
        $page_data['page_name']  = 'leave_request';
        $page_data['page_title'] = get_phrase('manage_leave_requests');
        $this->load->view('backend/index', $page_data);
    }

    function check_leave_dates()
    {
        $date = $this->input->post('date');
        if ($date != '')
        {
            $date = date('Y-m-d', strtotime($date));
                
            $q = $this->db->query("select * FROM ".get_school_db().".leave_staff ls
                inner join  ".get_school_db().".staff staff on staff.staff_id = ls.staff_id
                WHERE 
                ('".$date."' BETWEEN ls.start_date AND ls.end_date)
                AND staff.user_login_detail_id=".$_SESSION['login_detail_id']."
                AND ls.school_id=".$_SESSION['school_id']."
                AND (ls.status=0 OR ls.status=1 )")->result_array();
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
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        $d_c_s_sec = get_teacher_dep_class_section($login_detail_id);
        $time_table_t_sec = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
        $teacher_section = array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        $page_data['teacher_section'] = $teacher_section;

        //filter
        $dep_c_s_id = intval($this->input->post('dep_c_s_id'));
        if ($dep_c_s_id > 0)
        {
            $page_data['dep_c_s_filter'] = $dep_c_s_id;
            $page_data['filter'] = true;
        }
        
        $page_data['section_id']  = $dep_c_s_id;
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }
    
    function create_virtual_class($param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = '', $param6 = '', $param7 = ''){


        $vc_platform_id = get_school_virtual_platform();

        if($vc_platform_id == "")
        {
          $vc_platform_id = 1;  
        }



        $notification_subject = $param5 . "  " . $param6;
        $q_subject ="select subject_id , c_rout_sett_id FROM ".get_school_db().".class_routine where 
                     school_id=".$_SESSION['school_id']." AND class_routine_id = $param2  ";
        
        $result_subject = $this->db->query($q_subject)->row(); 
        
        $subject_id     = $result_subject->subject_id;
        $c_rout_sett_id = $result_subject->c_rout_sett_id;
        
        $q_section ="select section_id FROM ".get_school_db().".class_routine_settings where 
                     school_id=".$_SESSION['school_id']." AND c_rout_sett_id = $c_rout_sett_id  ";
        $result_section = $this->db->query($q_section)->row();
        $section_id = $result_section->section_id;

	    $q_students = "select student_id, name, roll  from ".get_school_db().".student 
	                   where section_id = ".$section_id."  and school_id=".$_SESSION['school_id']."  and student_status IN (".student_query_status().") ";
	    $students   = $this->db->query($q_students)->result_array();
	    
	    if(count($students) > 0){
	        
    	    foreach($students as $student)
    	    {

                $device_id  =   get_user_device_id(6 , $student['student_id'] , $_SESSION['school_id']);
                $title      =   "New Lecture Started";
                $message    =   "A New Lecture Has been Started By Teacher For " . $notification_subject;
                $link       =    base_url()."student_p/dashboard";
                sendNotificationByUserId($device_id, $title, $message, $link , $student['student_id'] , 6);
    	        
    	    }
	        
	    }
	    

	    if($vc_platform_id == 1)
        {
            
            /* big blue button configuration starts here */
         
            $rand                            =  rand(10000, 99999);
            $meetingId                       =  "Meeting-".$rand;
            $data['class_routine_id']        =  $param2;
            $data['virtual_class_name']      =  $param5." ".$param6;
            $data['virtual_class_id']        =  $meetingId;
            $data['Current_Days']            =  date('Y-m-d');
            $meetingName                     =  $param5." ".$param6;
            $logout_url                      =  base_url() . "virtualclass/class_end/".$rand;
            $creationUrl                     =  WEBRTC_LINK."api/create?";

            $total_participants = count($students)+5;
        
    		$params = 'name='.urlencode($meetingName).
    		'&meetingID='.urlencode($meetingId).
    		'&attendeePW='.urlencode('pw').
    		'&moderatorPW='.urlencode('mp').
    		'&dialNumber='.urlencode('').
    		'&voiceBridge='.urlencode('').
    		'&webVoice='.urlencode('').
    		'&logoutURL='.urlencode($logout_url).
    		'&maxParticipants='.urlencode($total_participants).
    		'&record='.urlencode('true').
    		'&autoStartRecording='.urlencode($param4).
    		'&lockSettingsDisablePublicChat=false'.
    		'&duration='.urlencode('90');
    		$welcomeMessage = 'Welcome to '.$meetingName;
    		if(trim($welcomeMessage)) 
    			$params .= '&welcome='.urlencode($welcomeMessage);
                $url = $creationUrl.$params.'&checksum='.sha1("create".$params.WEBRTC_SECRET);
                $ch = curl_init();
        		curl_setopt($ch, CURLOPT_URL, $url);
        		curl_setopt($ch, CURLOPT_HEADER, 0);
        		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        		$result = curl_exec($ch);
        		curl_close($ch);
        		
        	$name    = $_SESSION['name'];
        	$joinUrl = WEBRTC_LINK."api/join?";
    		$params  = '&meetingID='.urlencode($meetingId).
    		'&fullName='.urlencode($name).
    		'&password='.urlencode('mp').
    		'&userID='.urlencode($param7).
    		'&joinViaHtml5=true'.
    		'&webVoiceConf='.urlencode('');
            $url_join = $joinUrl.$params.'&checksum='.sha1("join".$params.WEBRTC_SECRET);
            
            $t                          =  time();
            $current_time               =  date("Y-m-d H:i:s",$t);
            $data['virtual_class_join'] =  $url_join;
            $data['vc_start_time']      =  $current_time;
            $data['Current_Days']       =  date('Y-m-d');
            $data['platform_id']        =  1; // big blue button
            
            $this->db->insert(get_school_db().'.virtual_class', $data);
            
            redirect($url_join);
            
            /* big blue button configuration end here */
        
        }
        else if($vc_platform_id == 2)
        {
            
            /* jitsi configuration starts here */
            
            $teacher_name                    =  $_SESSION['name'];   // teacher name
            $name                            =  $param5." ".$param6; // meeting name 
            $t                               =  time();
            $current_time                    =  date("Y-m-d H:i:s",$t);
            $rand                            =  rand(10000, 99999);
            $meetingId                       =  "Meeting-".$rand;
            $data['class_routine_id']        =  $param2;
            $data['virtual_class_name']      =  $param5." ".$param6;
            $data['virtual_class_id']        =  $meetingId;
            $data['Current_Days']            =  date('Y-m-d');
            $meetingName                     =  $param5." ".$param6;
            $logout_url                      =  base_url() . "virtualclass/class_end/".$rand;
            $creationUrl                     =  WEBRTC_LINK."api/create?";
            
            $data['virtual_class_join']      =  "";
            $data['vc_start_time']           =  $current_time;
            $data['Current_Days']            =  date('Y-m-d');
            $data['platform_id']             =  2; // jitsi
            
            $this->db->insert(get_school_db().'.virtual_class', $data);
            
            $page_data['teacher_name']       =  $teacher_name;
            $page_data['name']               =  $name;
            $page_data['logout_url']         =  $logout_url;
            $page_data['audio']              =  false;
            $page_data['video']              =  false;

            $this->load->view('backend/jitsi' , $page_data);
            
            /* jitsi configuration end here */
        }
        else if($vc_platform_id == 3)
        {
            
            /* big blue button configuration starts here */
         
            $rand                            =  rand(10000, 99999);
            $meetingId                       =  "Meeting-".$rand;
            $data['class_routine_id']        =  $param2;
            $data['virtual_class_name']      =  $param5." ".$param6;
            $data['virtual_class_id']        =  $meetingId;
            $data['Current_Days']            =  date('Y-m-d');
            $meetingName                     =  $param5." ".$param6;
            $logout_url                      =  base_url() . "virtualclass/class_end/".$rand;
            $creationUrl                     =  ICWEBRTC_LINK."api/create?";

            $total_participants = count($students)+5;
        
    		$params = 'name='.urlencode($meetingName).
    		'&meetingID='.urlencode($meetingId).
    		'&attendeePW='.urlencode('pw').
    		'&moderatorPW='.urlencode('mp').
    		'&dialNumber='.urlencode('').
    		'&voiceBridge='.urlencode('').
    		'&webVoice='.urlencode('').
    		'&logoutURL='.urlencode($logout_url).
    		'&maxParticipants='.urlencode($total_participants).
    		'&record='.urlencode('true').
    		'&autoStartRecording='.urlencode($param4).
    		'&lockSettingsDisablePublicChat=false'.
    		'&duration='.urlencode('90');
    		$welcomeMessage = 'Welcome to '.$meetingName;
    		if(trim($welcomeMessage)) 
    			$params .= '&welcome='.urlencode($welcomeMessage);
                $url = $creationUrl.$params.'&checksum='.sha1("create".$params.ICWEBRTC_SECRET);
                $ch = curl_init();
        		curl_setopt($ch, CURLOPT_URL, $url);
        		curl_setopt($ch, CURLOPT_HEADER, 0);
        		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        		$result = curl_exec($ch);
        		curl_close($ch);
        		
        	$name    = $_SESSION['name'];
        	$joinUrl = ICWEBRTC_LINK."api/join?";
    		$params  = '&meetingID='.urlencode($meetingId).
    		'&fullName='.urlencode($name).
    		'&password='.urlencode('mp').
    		'&userID='.urlencode($param7).
    		'&joinViaHtml5=true'.
    		'&webVoiceConf='.urlencode('');
            $url_join = $joinUrl.$params.'&checksum='.sha1("join".$params.ICWEBRTC_SECRET);
            
            $t                          =  time();
            $current_time               =  date("Y-m-d H:i:s",$t);
            $data['virtual_class_join'] =  $url_join;
            $data['vc_start_time']      =  $current_time;
            $data['Current_Days']       =  date('Y-m-d');
            $data['platform_id']        =  3; // indici connect big blue button
            
            $this->db->insert(get_school_db().'.virtual_class', $data);
            
            redirect($url_join);
            
            /* big blue button configuration end here */
        
        }
        

    }
	
    function my_class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        $page_data['page_name']  = 'my_class_routine';
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGING EXAM ROUTINE******************/
    function exam_routine($param1 = '', $param2 = '', $param3 = '')
    {
        
        $login_detail_id   =  $_SESSION['login_detail_id'];
        $yearly_term_id    =  $_SESSION['yearly_term_id'];
        $d_c_s_sec         =  get_teacher_dep_class_section($login_detail_id);
        $time_table_t_sec  =  get_time_table_teacher_section($login_detail_id, $yearly_term_id);
        $teacher_section   =  array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        
        //filter
        $dep_c_s_id        =  intval($this->input->post('dep_c_s_id'));
        if ($dep_c_s_id > 0)
        {
            $page_data['dep_c_s_filter'] = $dep_c_s_id;
            $page_data['filter']         = true;
        }

        $page_data['teacher_section'] =  $teacher_section;
        $page_data['teacher_subject'] =  get_time_table_teacher_subject($login_detail_id, $yearly_term_id);
        $page_data['section_id']      =  $dep_c_s_id;
        $page_data['page_name']       =  'exam_routine';
        $page_data['page_title']      =  get_phrase('manage_exam_routine');
        $this->load->view('backend/index', $page_data);
    }

	function attendance_selector()
	{
		$date_new	=	explode("/",$_REQUEST['date']);
		$date		=	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
		redirect(base_url() . 'teacher/manage_attendance/'.$date.'/'.
					//$this->input->post('month').'/'.
						//$this->input->post('year').'/'.
							$this->input->post('class_id') );
	}
   
    function attendance_selector_student()
	{
		$date_new	=	explode("/",$_REQUEST['date']);
		$date		=	$date_new['1']."/".$date_new['0']."/".$date_new['2'];
		redirect(base_url() . 'teacher/manage_attendance_student/'.$date.'/'.
					//$this->input->post('month').'/'.
						//$this->input->post('year').'/'.
							$this->input->post('class_id') );
	}
   
    
    /***MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($param1 == 'create') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->insert(get_school_db().'.noticeboard', $data);
            redirect(base_url() . 'teacher/noticeboard/');
        }
        else if ($param1 == 'do_update') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->where('notice_id', $param2);
            $this->db->update(get_school_db().'.noticeboard', $data);
            $this->session->set_flashdata('flash_message', get_phrase('notice_updated'));
            redirect(base_url() . 'teacher/noticeboard/');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where(get_school_db().'.noticeboard', array(
                'notice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('notice_id', $param2);
            $this->db->delete(get_school_db().'.noticeboard');
            redirect(base_url() . 'teacher/noticeboard/');
        }
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('manage_noticeboard');
        
        $start_date = $this->input->post('starting');
        $end_date   = $this->input->post('ending');
        
        if($start_date!='')
        {
        	$start_date_arr=explode("/",$start_date);
        	$start_date=$start_date_arr[2].'-'.$start_date_arr[1].'-'.$start_date_arr[0];
        }
        
        if($end_date!='')
        {
        	$end_date_arr = explode("/",$end_date);
        	$end_date     = $end_date_arr[2].'-'.$end_date_arr[1].'-'.$end_date_arr[0];
        }

        $per_page     =  10;
		$apply_filter =  $this->input->post('apply_filter', TRUE);
		$std_search   =  $this->input->post('std_search', TRUE);
        $std_search   =  trim(str_replace(array("'", "\""), "", $std_search));

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
        
        // $page_num = $this->uri->segment(6);
        // if (!isset($page_num) || $page_num == "") {
        //     $page_num = 0;
        //     $start_limit = 0;
        // } else {
        //     $start_limit = ($page_num - 1) * $per_page;
        // }                 

        if(($start_date!='') && ($start_date > 0))
        {
        	$date_query=" AND n.create_timestamp >= '".$start_date."'";
        	$page_data['start_date']=$start_date;
        	$page_data['filter'] = true;
        }
        if(($end_date!='') && ($end_date>0))
        {
        	$date_query=" AND n.create_timestamp <= '".$end_date."'";
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        }
        if(($start_date!='') && ($start_date > 0) && ($end_date!='') && ($end_date > 0))
        {
        	$date_query=" AND n.create_timestamp >= '".$start_date."' AND n.create_timestamp <= '".$end_date."' ";
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

		$p="select * from ".get_school_db().".noticeboard n where n.school_id=".$_SESSION['school_id']. $date_query. "
            and n.is_active = 1".$std_query." order by create_timestamp desc";
        $notice_count = $this->db->query($p)->result_array();

		$page_data['notices'] = $notice_count;
		$config['base_url']   = base_url() . "teacher/noticeboard/" . $start_date . "/". $end_date . "/". $std_search;
		$config['total_rows'] = $total_records;
        $config['per_page']   = $per_page;

        $config['uri_segment'] = 6;
        $config['num_links']   = 2;
        $config['use_page_numbers'] = TRUE;

        $this->pagination->initialize($config);

        $pagination                 = $this->pagination->create_links();
        $page_data['start_limit']   = $start_limit;
        $page_data['apply_filter']  = $apply_filter;
        $page_data['total_records'] = $total_records;
        $page_data['pagination']    = $pagination;
        $page_data['start_date']    = $start_date;
        $page_data['end_date']      = $end_date;
        $page_data['std_search']    = $std_search;

        $this->load->view('backend/index', $page_data);
    }
    /***MANAGE CIRCULARS, WILL BE SEEN BY Selected Corresponding DASHBOARDS**/
    function circulars($param1 = '', $param2 = '', $param3 = '')
    {
        $login_detail_id = $_SESSION['login_detail_id'];
        $yearly_term_id = $_SESSION['yearly_term_id'];
        if ($param1 == 'create') 
        {		
            $data['circular_title']=$this->input->post('circular_title');
            $data['circular']=$this->input->post('circular');
            $data['student_id']=$this->input->post('student_id');
            $data['class_id']= $this->input->post('class_id');
            $data['school_id']=$_SESSION['school_id'];
            $filename=$_FILES['attach_file']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $data['attachment']=time().'.'.$ext;

            move_uploaded_file($_FILES['attach_file']['tmp_name'],'uploads/circular_image/'.$data['attachment']);

            $start_date=explode('/',$this->input->post('create_timestamp'));
            $data['create_timestamp']=$start_date[2]."-".$start_date[0]."-".$start_date[1];
            $data['circular_status']=0;

			$this->db->insert('circular',$data);
			redirect(base_url() . 'teacher/circulars/');
        }
        elseif ($param1 == 'do_update') 
        {
            $data['circular_title']     = $this->input->post('circular_title');
            $data['circular']           = $this->input->post('circular');	
            $data['student_id']=$this->input->post('student_id');
            $start_date=explode('/',$this->input->post('create_timestamp'));
            $data['create_timestamp']=$start_date[2]."-".$start_date[0]."-".$start_date[1];
            $data['circular_status']	=  0;
            $attach_hidden= $this->input->post('attach_hidden');
            $user_file= $_FILES['attach_file']['name'];
          	if($user_file!="")
          	{
         		if($attach_hidden!="")
         		{
        			$del_location="uploads/cicular_image/$attach_hidden";
                    file_delete($del_location);
        		}
                $filename=$_FILES['attach_file']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $data['attachment']=time().'.'.$ext;
                move_uploaded_file($_FILES['attach_file']['tmp_name'],'uploads/circular_image/'.$data['attachment']);      
           }         
			
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('circular_id', $param2);
            $this->db->update(get_school_db().'.circular', $data);
            $this->session->set_flashdata('flash_message', get_phrase('circular_updated'));
            redirect(base_url() . 'teacher/circulars/');
        } 
        elseif ($param1 == 'edit') 
        {
            $page_data['edit_data'] = $this->db->get_where(get_school_db().'.circular', array(
            	'school_id' =>$_SESSION['school_id'],
                'circular_id' => $param2
            ))->result_array();
        }
        elseif ($param1 == 'delete') {
        	$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('circular_id', $param2);
            $this->db->delete(get_school_db().'.circular');
            redirect(base_url() . 'teacher/circulars/');
        }

        $page_data['page_name']       =  'circulars';
        $page_data['page_title']      =  get_phrase('manage_circulars');

        $d_c_s_sec                    =  get_teacher_dep_class_section($login_detail_id);
        $time_table_t_sec             =  get_time_table_teacher_section($login_detail_id, $yearly_term_id);
        $teacher_section              =  array_unique(array_merge($d_c_s_sec, $time_table_t_sec));
        $page_data['teacher_section'] =  $teacher_section;

        $in_section       =  0;
        $student_arr      =  "";
        $stud_sect_filter =  "";
        if (count($teacher_section) > 0)
        {
            $in_section = implode(',', $teacher_section);
        }

        $dep_c_s_id = intval($this->input->post('dep_c_s_id'));
        $student_select=$this->input->post('student_select');
        
		$start_date=$this->input->post('starting');
		$end_date=$this->input->post('ending');
        $filter = " and cs.section_id in ($in_section) ";
        
        if ( $dep_c_s_id > 0 ) 
        {
            $filter = " and cs.section_id = $dep_c_s_id ";
            $page_data['section_filter'] = $dep_c_s_id;
            $page_data['filter'] = true;
        }
        
		if($dep_c_s_id > 0 && $student_select > 0)
		{
			$page_data['filter'] = true;
			$page_data['student_id'] = $student_select;
			$stud_sect_filter= " and (cs.section_id = $dep_c_s_id and c.student_id = $student_select)";
		}
		
        if($start_date!='')
        {
        	$start_date_arr=explode("/",$start_date);
        	$start_date=$start_date_arr[2].'-'.$start_date_arr[1].'-'.$start_date_arr[0];
        }
        if($end_date!='')
        {
        	$end_date_arr=explode("/",$end_date);
        	$end_date=$end_date_arr[2].'-'.$end_date_arr[1].'-'.$end_date_arr[0];
        }

        $per_page     =  10;
		$apply_filter =  $this->input->post('apply_filter', TRUE);
		$std_search   =  $this->input->post('std_search', TRUE);
        $std_search   =  trim(str_replace(array("'", "\""), "", $std_search));
        
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
        
        if (!isset($student_select) || $student_select == "") {
            $student_select = $this->uri->segment(6);
        }

        if (!isset($student_select) || $student_select == "") {
            $student_select = 0;
        }
        
        $page_num = $this->uri->segment(7);
        if (!isset($page_num) || $page_num == "") {
            $page_num = 0;
            $start_limit = 0;
        } else {
            $start_limit = ($page_num - 1) * $per_page;
        }          

        if(($start_date!='') && ($start_date > 0))
        {
        	$date_query=" AND c.create_timestamp >= '".$start_date."'";
        	$page_data['start_date']=$start_date;
        	$page_data['filter'] = true;
        }
        if(($end_date!='') && ($end_date>0))
        {
        	$date_query=" AND c.create_timestamp <= '".$end_date."'";
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        	
        }
        
        if(($start_date!='') && ($start_date > 0) && ($end_date!='') && ($end_date > 0))
        {
        	$date_query=" AND c.create_timestamp >= '".$start_date."' AND c.create_timestamp <= '".$end_date."' ";
        	$page_data['start_date']=$start_date;
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        		
        }
        
        $std_query = "";
        if (isset($std_search) && !empty($std_search))
        {
        	$std_query = " AND (
                                    c.circular_title LIKE '%" . $std_search . "%' OR 
                                    c.circular LIKE '%" . $std_search . "%' 
                                )";
            $page_data['filter'] = true;
        }
        
        $q="select c.circular_id, c.circular_title, c.circular, c.section_id, c.student_id, c.create_timestamp, c.attachment, cs.title as class_section, d.title as department, class.name as class_name
            FROM ".get_school_db().".circular c 
            inner join ".get_school_db().".class_section cs on cs.section_id = c.section_id
            inner join ".get_school_db().".class on class.class_id = cs.class_id
            inner join ".get_school_db().".departments d on class.departments_id = d.departments_id
            where c.school_id=".$_SESSION['school_id']." and c.is_active = 1". $filter . $student_arr. $stud_sect_filter. $date_query .$std_query."
            group by c.circular_id order by create_timestamp desc";
            
        $circular_count         =  $this->db->query($q)->result_array();
        $total_records          =  count($circular_count);
        $quer_limit             =  $q . " limit " . $start_limit . ", " . $per_page . "";
        $query                  =  $this->db->query($quer_limit)->result_array(); 
        $page_data['circulars'] =  $query;
        $config['base_url']     =  base_url() . "teacher/circulars/" . $start_date . "/". $end_date . "/". $section_id."/".$student_select;
        $config['total_rows']   =  $total_records;
        $config['per_page']     =  $per_page;

        $config['uri_segment']      = 7;
        $config['num_links']        = 2;
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        $page_data['start_limit']   =  $start_limit;
        $page_data['apply_filter']  =  $apply_filter;
        $page_data['total_records'] =  $total_records;
        $page_data['pagination']    =  $pagination;
        $page_data['std_search']    =  $std_search;
        $page_data['start_date']    =  $start_date;
        $page_data['end_date']      =  $end_date;
        $page_data['section']       =  $dep_c_s_id;
        $page_data['student_id']    =  $student_select;
        
        $this->load->view('backend/index', $page_data);
    }

    function document($do = '', $document_id = '')
    {
        if ($do == 'upload') 
        {
        	move_uploaded_file($_FILES["userfile"]["tmp_name"],"uploads/document/" . $_FILES["userfile"]["name"]);
            $data['document_name']=$this->input->post('document_name');
            $data['file_name']=$_FILES["userfile"]["name"];
            $data['file_size']=$_FILES["userfile"]["size"];
            $this->db->insert(get_school_db().'.document', $data);
            redirect(base_url().'teacher/manage_document');
        }
        if ($do == 'delete') 
        {
            $this->db->where('document_id', $document_id);
            $this->db->delete(get_school_db().'.document');
            redirect(base_url().'teacher/manage_document');
        }
        $page_data['page_name']='manage_document';
        $page_data['page_title']=get_phrase('manage_documents');
        $page_data['documents']=$this->db->get(get_school_db().'.document')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    function send_mail($to,$subject,$from,$message)
    {
        $this->load->library('email'); // load email library
        $this->email->from($from, 'New Message'); // sending email 
        $this->email->to($to);      // sending email 
        $this->email->subject($subject); // sending email 
        $this->email->reply_to("no replay");// sending email 
        $this->email->message($message);// sending email 
        $this->email->set_mailtype("html");
        if ($this->email->send()){ // error and sending mail 
        $currentdate;
        // echo 'mail is send';
        }		
    }

    function get_student()
    {
        $class_id=$this->input->post('class_id');
        $q="select student_id,name from ".get_school_db().".student where class_id='$class_id' and school_id=".$_SESSION['school_id']." ";
        $query=$this->db->query($q);
        if($query->num_rows() > 0)
        {
            echo '<option value="">'.get_phrase('select_student').'</option>';
            foreach($query->result() as $rows)
            {
                echo '<option value="'.$rows->student_id.'">'.$rows->name.'</option>';
            }
        }
    }

    function get_subject_student()
    {
        $subject_id=$this->input->post('subject_id');
        $q="SELECT st.student_id, st.name
        FROM ".get_school_db().".student st
        INNER JOIN ".get_school_db().".subject sb
        ON st.class_id=sb.class_id
        where sb.subject_id=$subject_id
        AND st.school_id=".$_SESSION['school_id']."
        ";
        $query=$this->db->query($q);
        if($query->num_rows>0)
        {
            echo '<option value="">'.get_phrase('select_student').'</option>';
            foreach($query->result() as $rows)
            {
                echo '<option value="'.$rows->student_id.'">'.$rows->name.'</option>';
            }
        }
    }

    function get_subject()
    {
        $class_id=$this->input->post('class_id');
        $login_detail_id=$_SESSION['login_detail_id'];
        $q="select subject_id,name from ".get_school_db().".subject s inner join ".get_school_db().".staff staff on staff.staff_id = s.teacher_id
            where staff.user_login_detail_id=$login_detail_id  and s.school_id=".$_SESSION['school_id']." ";
        $query=$this->db->query($q);
        if($query->num_rows() >0)
        {
            echo '<option value="">'.get_phrase('select_subject').'</option>';
            foreach($query->result() as $rows)
            {
                echo '<option value="'.$rows->subject_id.'">'.$rows->name.'</option>';
            }
        }    
    }
    
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
    	if (get_login_type_name($_SESSION['login_type']) != 'teacher' )
			redirect(base_url() . 'login');
        
        if ($param1 == 'update_profile_info') {
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $this->db->where('school_id',$_SESSION['school_id']);            
            $this->db->where('admin_id',$_SESSION['user_login_id']);
            $this->db->update(get_school_db().'.admin', $data);
            $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
            redirect(base_url() . 'profile/manage_profile/');
        }
        if ($param1 == 'change_password') {
            $data['password']= passwordHash($this->input->post('password'));
            $data['new_password']= passwordHash($this->input->post('new_password'));
            $data['confirm_new_password'] = passwordHash($this->input->post('confirm_new_password'));
            $current_password = $this->db->get_where(get_system_db().'.user_login', array( 'user_login_id' => $_SESSION['user_login_id']))->row()->password;
            
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                
                $updated_data['password']  = $data['new_password'];
                $updated_data['is_password_updated']  = 1;
                
                $this->db->where('user_login_id',$_SESSION['user_login_id']);
                $this->db->update(get_system_db().'.user_login',$updated_data);
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } 
            else {
                  $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            } 
                 
            redirect(base_url().'teacher/manage_profile/');
        }
        
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where(get_system_db().'.user_login', array( 'user_login_id' => $_SESSION['user_login_id']))->result_array();
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
     	    $data['profile_pic']=file_upload_fun('userfile','profile_pic','profile',1);
     	    $data_staff['staff_image']=file_upload_fun('userfile','profile_pic','profile',1);
      
       } 
        // User Login Update
     	$this->db->where('user_login_id', $user_login_id);
    	$this->db->update(get_system_db().'.user_login', $data);
    	
        // Staff Update
    	$this->db->where('email', $_SESSION['user_email']);
    	$this->db->update(get_school_db().'.staff', $data_staff);
    	
    	$this->session->set_flashdata('flash_message', get_phrase('record_updated'));
        redirect(base_url() . 'teacher/manage_profile/');   
    }
  
	function policies_listing($action = "", $id = 0)
	{
		$page_data['policy_filter'] = '';
		if ($action == 'filter')
		{
		    $page_data['policy_filter'] = " policy_category_id = $id AND ";
			$page_data['filter'] = true;
		}
		
		$qry = "SELECT p.*, pc.title as category_title  FROM " . get_school_db() . ".policies p  INNER JOIN " . get_school_db() . ".policy_category pc ON p.policy_category_id = pc.policy_category_id WHERE  p.school_id=" . $_SESSION[ 'school_id' ] . " and p.is_active=1 and p.staff_p=1 ORDER BY p.policies_id DESC";
        $data = $this->db->query( $qry )->result_array();
        
        $page_data['data'] = $data;
		$page_data['page_name'] = 'policies_listing';
		$page_data['page_title'] = get_phrase('policies');
		$this->load->view('backend/index', $page_data);
	
	}
	
	function subjects()
	{
		$sectionIds = get_time_table_teacher_section($_SESSION['login_detail_id'], $_SESSION['yearly_term_id']);
	    $implode_sectionIds = implode("','",$sectionIds);
	    //echo $implode_sectionIds;exit;
		$query = "select s.* FROM 
		".get_school_db().".class_routine cr 
            inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
            inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            inner join ".get_school_db().".subject s on s.subject_id=st.subject_id
            inner join ".get_school_db().".staff staff on staff.staff_id=st.teacher_id
            inner join ".get_school_db().".subject_section SS on SS.subject_id = st.subject_id
            inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
            inner join ".get_school_db().".class on class.class_id = cs.class_id
            inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
            where 
            staff.user_login_detail_id = ".$_SESSION['login_detail_id']."
            and cr.school_id=".$_SESSION['school_id']."
            and crs.section_id IN('$implode_sectionIds')
            group by s.subject_id
            ";
        $data = $this->db->query( $query )->result_array();
        
        
        $page_data['data'] = $data;
		$page_data['page_name'] = 'subjects';
		$page_data['page_title'] = get_phrase('subjects');
		$this->load->view('backend/index', $page_data);
	
	}
	
	function staff_circular()
	{
		$circular_qur="";
		$circular_select = $this->input->post('circular_select');
        $circular_qur=" AND (cs.staff_id=".$_SESSION['user_id']." OR cs.staff_id=0)";
        
        if($circular_select=='all_circulars')
        {
			$page_data['filter'] = true;
			$page_data['circular_val'] = $circular_select;
		}
		if($circular_select=='my_circulars')
		{
			$circular_qur=" AND cs.staff_id=".$_SESSION['user_id']."";
			$page_data['filter'] = true;
			$page_data['circular_val'] = $circular_select;
			
		}
		if($circular_select=='general_circulars')
		{
			$circular_qur=" AND cs.staff_id=0";
			$page_data['filter'] = true;
			$page_data['circular_val'] = $circular_select;
		}
		
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
        	$date_query=" AND cs.circular_date >= '".$start_date."'";
        	$page_data['start_date']=$start_date;
        	$page_data['filter'] = true;
        }
        if($end_date!='')
        {
        	$date_query=" AND cs.circular_date <= '".$end_date."'";
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        }
        if($start_date!='' && $end_date!='')
        {
        	$date_query=" AND cs.circular_date >= '".$start_date."' AND cs.circular_date <= '".$end_date."' ";
        	$page_data['start_date']=$start_date;
        	$page_data['end_date']=$end_date;
        	$page_data['filter'] = true;
        }
		
		$q="SELECT cs.*,st.name as staff_name,st.employee_code as employee_code,d.title as designation FROM ".get_school_db().".circular_staff cs
            Left JOIN ".get_school_db().".staff st ON cs.staff_id=st.staff_id left JOIN ".get_school_db().".designation d On st.designation_id=d.designation_id
            WHERE cs.school_id=".$_SESSION['school_id']. $circular_qur . $date_query ." order by cs.circular_date desc ";
		
		$query=$this->db->query($q)->result_array();
		$page_data['query']=$query;
		$page_data['page_name'] = 'staff_circulars';
		$page_data['page_title'] = get_phrase('policies');
		$this->load->view('backend/index', $page_data);
	}

   function get_section_student($section_id = 0, $student_id=0)
   {
        $student_select=$this->input->post('$student_select');
        if($this->input->post('section_id') != "")
        {
            echo section_student($this->input->post('section_id') , $this->input->post('student_id'));
        }
  }
  
  function student_summary()
  {
  		$section_filter=$this->input->post("section_filter");
  		$student_filter=$this->input->post("student_select");
  		
  		if(isset($section_filter) && ($section_filter > 0) && isset($student_filter) && ($student_filter > 0))
  		{
			$page_data['section_filter']=$section_filter;
  			$page_data['student_filter']=$student_filter;
  			$acadmic_year_start = $this->db->query("select start_date,end_date  from ".get_school_db().".acadmic_year 
                            where  academic_year_id =".$_SESSION['academic_year_id']."  and school_id=".$_SESSION['school_id']." ")->result_array();

            $start_date=$acadmic_year_start[0]['start_date'];
            $end_date=$acadmic_year_start[0]['end_date'];     
                            
            $p="select status,count(status) as status_count, month(date) as month_val, year(date) as year_val,monthname(date) as month_name from ".get_school_db().".attendance where student_id=$student_filter and school_id=".$_SESSION['school_id']." GROUP BY month_val,year_val,status order by  month_name, year_val";
            $attendance=$this->db->query($p)->result_array();
            $page_data['attendance']=$attendance;
            $page_data['start_date']=$start_date;
            $page_data['end_date']=$end_date;
	
		}
  		
  		$page_data['page_name'] = 'student_attendance_summary';
		$page_data['page_title'] = get_phrase('student_attendance_summary');
		$this->load->view('backend/index', $page_data);
  }
  
    function teacher_summary()
    {
      	$page_data['page_name'] = 'teacher_attendance_summary';
    	$page_data['page_title'] = get_phrase('student_attendance_summary');
    	$this->load->view('backend/index', $page_data);
    }

    function students_birthday()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $apply_filter = $this->input->post('apply_filter');
        $condition = "";
        if($start_date != "" || $end_date != "")
        {
            $start_month_day = date("m-d",strtotime($start_date));
            $end_month_day = date("m-d",strtotime($end_date));
            
            $condition = "AND DATE_FORMAT(birthday, '%m-%d') BETWEEN '$start_month_day' AND '$end_month_day' ";
        } else
        {
            $condition = "AND DATE_FORMAT(birthday, '%m-%d') =  DATE_FORMAT(CURDATE(), '%m-%d')";
        }
        
        $qur = $this->db->query("select s.* , sec.title from ".get_school_db().".student s
        inner join ".get_school_db().".class_section sec on s.section_id = sec.section_id
        where s.school_id=".$_SESSION['school_id']." $condition ")->result();
    
          $page_data['start_date']= $start_date;
          $page_data['end_date']= $end_date;
          $page_data['apply_filter']= $apply_filter;
      	$page_data['student_data']= $qur;    
        $page_data['page_name']    = 'students_birthday';
        $page_data['page_title']   = get_phrase('students_birthday');
        $this->load->view('backend/index', $page_data);
    } 
    
    function get_section_subjects()
    { 
        $data = "";
        $section_id  = $this->input->post('section_id');
        	$query = "select s.* FROM 
    	".get_school_db().".class_routine cr 
            inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
            inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            inner join ".get_school_db().".subject s on s.subject_id=st.subject_id
            inner join ".get_school_db().".staff staff on staff.staff_id=st.teacher_id
            inner join ".get_school_db().".subject_section SS on SS.subject_id = st.subject_id
            inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
            inner join ".get_school_db().".class on class.class_id = cs.class_id
            inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
            where 
            staff.user_login_detail_id = ".$_SESSION['login_detail_id']."
            and cr.school_id=".$_SESSION['school_id']."
            and crs.section_id = $section_id
            group by s.subject_id
            ";
    $subject_arr = $this->db->query($query)->result_array();
    
    
        if(count($subject_arr) > 0)
        {
            foreach($subject_arr as $row)
            {
                $data .= '<li> <span onclick="getsubjectdiary('.$row['subject_id'].','.$section_id.')">
                <a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#subjects_diary'.$row['subject_id'].'" aria-expanded="true" aria-controls="subjects">
                <i class="collapsed"><i class="fas fa-folder"></i></i>
                <i class="far fa-file"></i> '.$row['name'].' </span></a> 
                    <div id="subjects_diary'.$row['subject_id'].'" class="collapse">
                    <input type="text" class="form-control" style="margin-top: 10px;" id="myInput" onkeyup="search_diary('.$row['subject_id'].')" placeholder="Search for diary..">
                        <ul class="subjects_diary'.$row['subject_id'].'">
                            
                        </ul>
                    </div>
                </li>';
            }
        }else
        {
            $data .= '<li> <span><i class="far fa-file"></i> No Subject found </span> </li>';
        }    
        echo $data;
    }
    
    function getsubjectdiary() { 
        $data = ""; 
        $subject_id  = $this->input->post('subject_id');
        $section_id  = $this->input->post('section_id');
        //working
        $academic_year_dates = get_academic_year_dates($_SESSION['academic_year_id']);
        $academic_year_start_date  = $academic_year_dates->start_date;
        $academic_year_end_date  = $academic_year_dates->end_date;
        
        $academic_year_filter = "and assign_date between '$academic_year_start_date' and '$academic_year_end_date' ";
        $qur = "SELECT * from ".get_school_db().".diary WHERE subject_id = $subject_id AND section_id = $section_id $academic_year_filter AND teacher_id = '".$_SESSION['user_id']."' "; 
        $arr = $this->db->query($qur)->result_array(); 
        if(count($arr) > 0)
        {
            foreach($arr as $row)
            {
                $data .= '<li onclick="get_diary_data('.$row['diary_id'].')"><span><i class="far fa-file"></i> '.$row['title'].'- ('.date_view($row['assign_date']).') </span> </li>';
            }
        }else
        {
            $data .= '<li> <span><i class="far fa-file"></i> No diary found </span> </li>';
        }    
        echo $data;
       
    }
    // Attendance View Specific Section Wise Report   ///
    	function view_stud_attendance()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $section_id = $_POST['section_id'];
	    $apply_filter = $_POST['apply_filter'];
	    
	    
	    if(isset($apply_filter) && $apply_filter != ""){
            $query="SELECT * FROM ".get_school_db().".student WHERE section_id=$section_id AND school_id=".$_SESSION['school_id']." AND  student_status IN (".student_query_status().") ORDER BY roll desc";
        	$students=$this->db->query($query)->result_array();
        
    		$page_data['students']		    =	$students;
	    }else{
	        $page_data['students']		    =	array();
	    }
		
		$page_data['section_id']		    =	$section_id;
    	$page_data['apply_filter']		    =	$apply_filter;
		$page_data['page_name']		    =	'view_student_attendance';
		$page_data['page_title']		=	get_phrase('view_student_attendance');
		$this->load->view('backend/index', $page_data);
		
	}
	//// Chat for teacher Side//
	  	function student_chat_list()
	{
	   // print_r($_SESSION);
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $section_id = $_POST['section_id'];
	    $apply_filter = $_POST['apply_filter'];
	    
	    if(isset($apply_filter) && $apply_filter != ""){
            $query="SELECT * FROM ".get_school_db().".student WHERE section_id=$section_id AND school_id=".$_SESSION['school_id']." AND  student_status IN (".student_query_status().") and user_login_detail_id > '0' ORDER BY roll desc";
        	$students=$this->db->query($query)->result_array();
        // 	echo $this->db->last_query();
    		$page_data['students']		    =	$students;
	    }else{
	        $page_data['students']		    =	array();
	      
	    }
	     $login_detail_id_teacher = $_SESSION['login_detail_id'];     //teacher_detail_id
	      // print_r($login_detail_id_teacher);
	    
		
		$page_data['section_id']		    =	$section_id;
    	$page_data['apply_filter']		    =	$apply_filter;
		$page_data['page_name']		    =	'student_listing_chat';
		$page_data['page_title']		=	get_phrase('student_listing_chat');
		$this->load->view('backend/index', $page_data);
		
	}
    
    function get_diary_data()
    {
        $data = "";
        
        $diary_id  = $this->input->post('diary_id');
        $qur = "SELECT d.*,aud.*, d.diary_id AS d_id,sub.name as subject_name, sub.code as subject_code from ".get_school_db().".diary d  
                INNER JOIN ".get_school_db().".subject sub ON sub.subject_id = d.subject_id
                LEFT JOIN ".get_school_db().".diary_audio aud ON aud.diary_id = d.diary_id
                WHERE d.diary_id =  $diary_id AND d.school_id =  ".$_SESSION['school_id']." ";
        
        $diary_arr = $this->db->query($qur); 
        $arr = $diary_arr->row(); 
        if($diary_arr->num_rows() > 0) {
        $data .= '<table class="table m-0">
                    <tbody>
                        <tr>
                          <td> 
                            <b>Task Title:</b>
                            <br> '.$arr->title.'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Task Detail:</b>
                            <br> '.$arr->task.'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Teacher Name:</b>
                            <br> '.get_teacher_name($arr->teacher_id).'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Assign Date:</b>
                            <br> '.date_view($arr->assign_date).'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Due Date:</b>
                            <br> '.date_view($arr->due_date).'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Submission Details:</b><br>';
                            if ($arr->is_assigned == 1)
                            {
                                $data .='<a href="'.base_url().'teacher/assignment_details/'.$arr->d_id.'" target="_blank">Click to see assignment details </a>';
                            }else{
                                $data .='Not Assigned'; 
                            }
                            $diary_assign_modal = "showAjaxModal('".base_url()."modal/popup/modal_diary_student/".$arr->section_id."/".$arr->d_id."/".$arr->subject_name."-".$arr->subject_code."')";
                            $diary_audio_modal = "showAjaxModal('".base_url()."modal/popup/modal_diary_audio/".$arr->section_id."/".$arr->d_id."')";
                            $diary_delete_modal = "confirm_modal('".base_url()."teacher/diary/delete/".$arr->section_id."/".$arr->d_id."')";
                        $data .= '</td> 
                        </tr>';
                        if ($arr->audio != ""){
                            $audio_path = base_url()."uploads/".$_SESSION['folder_name']."/diary_audios/".$arr->audio;
                            $data .= '<tr><td>
                                    <b>Audio</b><br>
                                    <audio controls>
                                      <source src="'.$audio_path.'" type="audio/ogg">
                                      <source src="'.$audio_path.'" type="audio/mpeg">
                                    </audio></td></tr>';
                        }
                        if ($arr->attachment != ""){
                            $attachment_path = base_url()."uploads/".$_SESSION['folder_name']."/diary/".$arr->attachment;
                            $data .= '<tr><td>
                                    <b>Diary Attachment</b><br>
                                    <a target="_blank" href="'.$attachment_path.'"><i class="fas fa-paperclip"></i> Click to open the link </a>
                                    </td></tr>';
                        }
                        $data .=  '</td> 
                        </tr>
                        <tr>
                            <td>
                                <a style="" class="btn btn-primary btn-sm" href="#" onclick="'.$diary_assign_modal.'">
                                    <i class="entypo-eye"></i>
                                    Assign Diary 
                                </a>&nbsp;&nbsp;';
                                $data .= '<a class="btn btn-primary btn-sm" href="'.base_url().'teacher/edit_diary/'.$arr->d_id.'/'.$_SESSION['school_id'].'">
                                    <i class="entypo-pencil"></i>
                                    Edit
                                </a>&nbsp;&nbsp;';
                                $data .= '&nbsp;&nbsp;
                                <a href="#" class="modal_cancel_btn" onclick="'.$diary_delete_modal.'">
                                    <i class="entypo-trash"></i>
                                    Delete
                                </a>&nbsp;&nbsp;';
                        if ($arr->is_assigned == 0) {        
                            
                            if ($arr->audio == ""){    
                            $data .= '<a style="" class="modal_save_btn" href="#" onclick="'.$diary_audio_modal.'">
                                    <i class="fas fa-microphone" style="font-size: 15px;"></i>
                                    Add Audio
                                </a>';
                            }
                        }else{
                            $data .= '<a class="btn btn-primary btn-sm" href="'.base_url().'teacher/edit_diary/'.$arr->diary_id.'/'.$_SESSION['school_id'].'">
                                    <i class="entypo-eye"></i>
                                    View
                                </a>';
                        }
                        if(get_diary_approval_method() == 2){ 
                            if ($arr->is_assigned == 1 && $arr->admin_approvel == 1) {
                                $data .= '<span class="badge badge-info">Admin Approved</span>';
                            }elseif($arr->is_assigned == 1 && $arr->admin_approvel == 0){
                                 $data .= '<span class="badge badge-info">Admin Not Approved Yet</span>';
                            }    
                        }
                        
                        
                            $data .= '</td>
                        </tr>
                    </tbody>
                    </table> '; 
                    

        }else
        {
            
           $data = '';
        }
        echo $data;
    }
    
     function teacher_chat_module(){
       $login_detail_id_teacher = $_SESSION['login_detail_id'];
       $selected_student=  $_POST['student_id'];    //click for teacher then get id 
      
       $student_detail_arr = $this->db->query(" select user_login_detail_id,name from ".get_school_db().".student 
    	                          where student_id = $selected_student
     	                          ")->row();
     	                          
     	 $student_user_detail_id = $student_detail_arr->user_login_detail_id;
     	 $student_name = $student_detail_arr->name;
         $get_parent_id = $this->db->query("SELECT parent_id FROM ".$_SESSION['school_db'].".student WHERE student_id =".$selected_student." and school_id = ".$_SESSION['school_id']." ")->row();
         
         if($get_parent_id)
            {
                $get_parent_login_detailed_id = $this->db->query("SELECT user_login_detail_id FROM ".$_SESSION['school_db'].".student_parent WHERE S_p_id =".$get_parent_id->parent_id." and school_id = ".$_SESSION['school_id']." ")->row();
                $parent_login_detailed_id = $get_parent_login_detailed_id->user_login_detail_id ?? '0';
             
            }
           
         if($student_user_detail_id != 0 && $get_parent_login_detailed_id != 0)
           {
                $chat_id = $login_detail_id_teacher.$parent_login_detailed_id.$student_user_detail_id;
               
               
                
                 $data = array(
                        'send_id'   =>  $login_detail_id_teacher,
                        'rec_id'    =>  $parent_login_detailed_id,
                        'chat_id'   =>  $chat_id,
                        'student_id'   => $student_user_detail_id,
                        
                    );
                       
                    
                                      $chat_json_data['name'] =  $student_name;
             	                      $chat_json_data['chat_id'] = $chat_id;
             	                      $chat_json_data['send_id'] = $login_detail_id_teacher;
             	                      $chat_json_data['student_id'] = $student_user_detail_id;
             	                      $chat_json_data['rec_id'] = $parent_login_detailed_id;
             	                      echo json_encode($chat_json_data);
                    
                    
                                //  $chat_array = $this->db->query(" select *,chat_id from ".get_school_db().".chat_relation 
    	                           //where send_id = ".$_SESSION['login_detail_id']."
    	                           //and student_id = ".$student_user_detail_id."
     	                          // and rec_id = ".$parent_login_detailed_id."
     	                          // OR send_id = ".$parent_login_detailed_id."
     	                          // OR rec_id = ".$_SESSION['login_detail_id']."
     	                          // ")->num_rows();
     	                          
     	                          //$chat_id = $_SESSION['login_detail_id'].$parent_login_detailed_id.$student_user_detail_id;
     	                          
     	                          
     	                          //   $chat_array = $this->db->query(" select chat_id from ".get_school_db().".chat_relation 
    	                           //where chat_id = ".$chat_id.")->num_rows();
     	                          // $chat_array = $this->db->query("select chat_id from ".get_school_db().".chat_relation where chat_id = ".$chat_id."")->num_rows();
     	                          
     	                          
     	                          
     	                             
     	                          // if($chat_array > 0){
         	                      //     $chat_data = $this->db->query(" select * from ".get_school_db().".chat_relation 
        	                       //   where send_id = ".$login_detail_id_teacher."
         	                      //     and rec_id = ".$parent_login_detailed_id."
         	                      //    and student_id = ".$student_user_detail_id."
         	                      //    OR send_id = ".$parent_login_detailed_id."
         	                      //    OR rec_id = ".$login_detail_id_teacher."
         	                      //    ")->result_array();
         	                         
             	                  //    $chat_json_data['name'] =  $student_name;
             	                  //    $chat_json_data['chat_id'] = $chat_data[0]['chat_id'];
             	                  //    $chat_json_data['send_id'] = $chat_data[0]['send_id'];
             	                  //    $chat_json_data['student_id'] = $chat_data[0]['student_id'];
             	                  //    $chat_json_data['rec_id'] = $chat_data[0]['rec_id'];
             	                  //    echo json_encode($chat_json_data);
     	                          //}
     	                          //else{
     	                          //     $this->db->insert(get_school_db().'.chat_relation',$data);
     	                          //    }
     	          
     	          //  echo json_encode($chat_json_data);
     	          
     	          
     	          
     	          
     	          
        }
        else{
             
             
            $struc = '<div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Modal body text goes here.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>';


        echo $struc;
        }
     
    }   
    
function getStudentIdForChat()
    {
        $student_id = $this->input->post('student_id');
        $teacher_id    = $_SESSION['user_id']; 
        $sent_by = intval($_SESSION['login_detail_id']);
        $sect_id =get_student_info1($student_id);
        $stdSectionId = $sect_id->section_id;
         
        $teacher_namee = get_teacher_name($teacher_id);
       
        $parent_idd = get_parent_idd($student_id);
       
        $stdname    =    get_student_info($student_id);
               
        $std_name   = $stdname[0]['student_name'];
        $class_idd = get_class_id($sect_id->section_id);
       
        $class_name = get_class_name($class_idd);
        
        
        
        
        
        $get_std_detailed_id = $this->db->query("SELECT user_login_detail_id FROM ".$_SESSION['school_db'].".student WHERE student_id =".$student_id." and school_id = ".$_SESSION['school_id']." ")->row();
    
    
    $get_parent_id = $this->db->query("SELECT parent_id FROM ".$_SESSION['school_db'].".student WHERE student_id =".$student_id." and school_id = ".$_SESSION['school_id']." ")->row();
    
    if($get_parent_id)
    {
        $get_parent_login_detailed_id = $this->db->query("SELECT user_login_detail_id FROM ".$_SESSION['school_db'].".student_parent WHERE S_p_id =".$get_parent_id->parent_id." and school_id = ".$_SESSION['school_id']." ")->row();
       
        $parent_login_detailed_id = $get_parent_login_detailed_id->user_login_detail_id ?? '0';
        // print_r($parent_login_detailed_id);
    }
    
    
    $std_login_detail_id = $get_std_detailed_id->user_login_detail_id ?? '0';
    $teacher_login_detail_id = $_SESSION['login_detail_id'];
        
    $academicYearId = $_SESSION['academic_year_id'];
        

        
        $chat_json_data['student_name'] =  $std_name;
        $chat_json_data['teacher_name'] =  $teacher_namee;
        $chat_json_data['class_name'] = $class_name;
        $chat_json_data['class_id'] = $class_idd;
        $chat_json_data['sent_by'] = $sent_by;
        $chat_json_data['student_detailed_id'] = $std_login_detail_id;
        $chat_json_data['student_id'] = $student_id;
        $chat_json_data['rec_id'] = $parent_login_detailed_id;
        $chat_json_data['parent_id'] = $parent_idd;
        $chat_json_data['stdSectionId'] = $stdSectionId;
        $chat_json_data['academic_year_id'] = $academicYearId;
        echo json_encode($chat_json_data);
        
       
    }
    
    
}