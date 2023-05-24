<?php 
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();
class Subject extends CI_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->database();
		
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
		
	}
	
	/***default functin, redirects to login page if no admin logged in yet***/
	public function index(){
 
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
		if($_SESSION['user_login'] == 1)
		redirect(base_url() . 'admin/dashboard');
	}
    
	/****MANAGE SUBJECTS*****/
	function subjects($param1 = '', $param2 = '' , $param3 = ''){
		if($_SESSION['user_login']!= 1)
		      redirect(base_url());
		      
		if($param1 == 'create'){

			$data['subj_categ_id']=$this->input->post('subj_categ');
			$data['name']       = $this->input->post('name');
			$data['code']       = trim($this->input->post('code'));
			$data['school_id'] = $_SESSION['school_id'];
			$this->db->insert(get_school_db().'.subject', $data);
			$this->session->set_flashdata('club_updated', get_phrase('recored_saved_successfully'));
			redirect(base_url() . 'subject/subjects/');
			
		}
		
		if($param1 == 'do_update'){
		    
			$data['subj_categ_id']= $this->input->post('subj_categ_id');
			$data['name']= $this->input->post('name_edit');
		    $data['code']=$this->input->post('code_edit');
			$this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('subject_id', $param2);
            $this->db->update(get_school_db().'.subject', $data);
            $this->session->set_flashdata('club_updated', get_phrase('recored_saved_successfully'));
			redirect(base_url() . 'subject/subjects/');

		} 
		
		if($param1 == 'delete'){
			
            $qur_1=$this->db->query("select planner_id from ".get_school_db().".academic_planner where subject_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_1)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
                redirect(base_url() . 'subject/subjects/');
            }

		    $qur_2=$this->db->query("select diary_id from ".get_school_db().".diary where subject_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_2)>0){
                 $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
                 redirect(base_url() . 'subject/subjects/');
                 exit();	
            }

		    $qur_3=$this->db->query("select exam_routine_id from ".get_school_db().".exam_routine where subject_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_3)>0){
                 $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
                 redirect(base_url() . 'subject/subjects/');
                 exit();	
            }
	
            $qur_4=$this->db->query("select marks_id from ".get_school_db().".marks where subject_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_4)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
                redirect(base_url() . 'subject/subjects/');
                exit();	
            }		
	
            $qur_5=$this->db->query("select messages_id from ".get_school_db().".messages where subject_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_5)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
                redirect(base_url() . 'subject/subjects/');
                exit();	
            }		
	
            $qur_6=$this->db->query("select subject_component_id from ".get_school_db().".subject_components  where subject_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_6)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
                redirect(base_url() . 'subject/subjects/');
                exit();	
            }		
	
            $qur_7=$this->db->query("select subject_section_id from ".get_school_db().".subject_section where subject_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_7)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
                redirect(base_url() . 'subject/subjects/');
                exit();	
            }		
			
            $qur_8=$this->db->query("select subject_teacher_id from ".get_school_db().".subject_teacher where subject_id=$param2 and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_8)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));	
                redirect(base_url() . 'subject/subjects/');
                exit();	
            }		
	
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('subject_id', $param2);
            $this->db->delete(get_school_db().'.subject');
            $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
            redirect(base_url() . 'subject/subjects/');
           
		}

		$page_data['class_id']   = $param1; 

		$page_data['page_name']  = 'subject';
		$page_data['page_title'] = get_phrase('manage_subject');
	
		$this->load->view('backend/index', $page_data);
	}
	
	function upload_sallybus($subject_id = '')
	{
	    $data['subject_id'] = $subject_id;
	    $data['school_id'] = $_SESSION['school_id'];
	    $data['academic_year_id'] = $this->input->post('academic_year_id');
	    $data['sallybus_type'] = $this->input->post('sallybus_type');
	    $data['status'] = $this->input->post('status');
	    if ($_FILES['document']['name'] != "") 
	    {
	        $image_url                     = file_upload_fun('document' , 'subject_sallybus', '');
	        $data['sallybus_data']  = $image_url;
	    }else{
	        $data['sallybus_data']  = $this->input->post('content');
	    }
	    if($this->db->insert(get_school_db().".subject_sallybus",$data)){
    	    $this->session->set_flashdata('club_updated', get_phrase('sallybus_upload_successfully'));
	    }else{
	        $this->session->set_flashdata('club_updated', get_phrase('sallybus_not_upload'));
	    }
	    
	    
	    //interns code starts 
	    
	    
	    $student_details = get_section_id_by_subject_id($subject_id);
	  //  echo '<pre>'; print_r($student_details);exit;
	   
	    for($i = 0; $i < count($student_details); $i++)
	    {
	        for($j = 0; $j < count($student_details[$i]); $j++)
    	    {
    	       
    	       $user_login_id = $student_details[$i][$j]['user_login_id'];
    	       if($user_login_id != 0)
    	       { 
    	           
    	         
    	         
        	        $d=array(
                    'title'=>'Syllabus has been added for '.$student_details[$i][$j]['student_name'],
                    'body'=>$student_details[$i][$j]['class_name'].' '.$student_details[$i][$j]['subject_name'],
                    );
                   
                    $d2 = array(
                    'screen'=>'subjects',
                    'student_id'=>$student_details[$i][$j]['student_id'],
                    'class_name'=>$student_details[$i][$j]['class_name'],
                    'subject_id'=>$student_details[$i][$j]['subject_id'],
                    'subject_name'=>$student_details[$i][$j]['subject_name'],
                    'section_id'=>$student_details[$i][$j]['section_id'],
                    'subject_name'=>$student_details[$i][$j]['subject_name'],
                    'academic_year_id'=>$_SESSION['academic_year_id'],
                    );
           
                    notify($d,$d2,$user_login_id);
                    
                    
                    if($user_login_id)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $user_login_id";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$user_login_id);
                        }
                       
                    }
            
                    
                    
                    
    	       }
    	       else{}
    	       
    	        
    	    }
    
	    }

	 
	     //interns code ends 
	    
	    
	    redirect(base_url() . 'subject/subjects');
	}
	
	function sallybus_delete($subject_sallybus_id = 0)
	{
	    $qur_1 = $this->db->where("subject_sallybus_id",$subject_sallybus_id)->delete(get_school_db().".subject_sallybus");
        $this->session->set_flashdata('club_updated',get_phrase('sallybus_delete_successfully'));	
        redirect(base_url() . 'subject/subjects/');
	}
	
	function get_subject_list(){
		$data['section_id'] = $this->input->post('section_id');
		$this->load->view("backend/admin/ajax/subject",$data);
	}
	
	function get_class(){
		echo  class_option_list($this->input->post('department_id'),$this->input->post('class_id'));
	}
	
	function get_class_section(){
		echo section_option_list($this->input->post('class_id'),$this->input->post('section_id'));
	}
	
	function get_term(){
		echo yearly_terms_option_list($this->input->post('academic_year'));
	}
	
	function assign_subject_teacher(){
			
		$subject_id  = $this->input->post('subject_id');
		$teacher_id = $this->input->post('teacher_id');

		$result_subject=$this->db->query($q="SELECT st.teacher_id FROM ".get_school_db().".`subject_teacher` st inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.subject_teacher_id=st.subject_teacher_id where st.subject_id=".$subject_id." and st.school_id=".$_SESSION['school_id']."")->result_array();
		
		foreach($result_subject as $t)
		{
			$teacher_arry[]=$t['teacher_id'];
		}
		$unused=array();
		if(sizeof($teacher_id)>0){
			
		
		foreach($teacher_id as $k=>$v)
		{	
			if(in_array($v['value'],$teacher_arry))
			{
				//$used[]=$v['value'];
			}		
			else
			{
				$unused[]=$v['value'];
			}
			
		}
		}
		
		if(sizeof($teacher_arry)>0)
		{
			$used_teacher_ids=implode(',',$teacher_arry);
			$this->db->query($q="delete from ".get_school_db().".subject_teacher where subject_id=".$subject_id." and teacher_id not in (".$used_teacher_ids.")");
		}
		else{
			$this->db->query($q="delete from ".get_school_db().".subject_teacher where subject_id=".$subject_id." ");
		}
		
		if(sizeof($unused)>0)
		{
			
		
		foreach($unused as $unused_id)
			{
				$data['school_id']=$_SESSION['school_id'];
				$data['teacher_id']=$unused_id;
				$data['subject_id']=$subject_id;
				$this->db->insert(get_school_db().'.subject_teacher', $data);
				
				
    			$device_id  =   get_user_device_id(3 , $unused_id , $_SESSION['school_id']);
                $title      =   "New Subject Assigned";
                $message    =   "A New Subject Has been Assigned To You By School Admin.";
                $link       =    base_url()."teacher/my_class_routine";
                sendNotificationByUserId($device_id, $title, $message, $link , $unused_id , 3);
				
			}
		}
		
	$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));		

}		
	function delete_subject_teacher(){
			
		$data['subject_teacher_id']   = $this->input->post('id');
			
		if($this->db->delete(get_school_db().'.subject_teacher', $data)){
			echo "Deleted";	
		}
 		
	}
	function check_delete_request(){
		$subject_id = $this->input->post('subject_id');
		$q="select * from ".get_school_db().".subject_teacher where subject_id=".$subject_id."";
		$resArr=$this->db->query($q)->result_array();
		if(sizeof($resArr)>0){
			echo "Exists";
		}
	}
	function assign_section_subject(){
		
		$section_id=$this->input->post('section_id');
		$subject_id=get_object_vars(json_decode($this->input->post('subArr')));
		$subjectIdArr=get_section_subject_ids($section_id);
		$data['section_id']=$section_id;
		$class_routine_arr=$this->db->query($q="select distinct cr.subject_id from ".get_school_db().".class_routine_settings cs  inner join ".get_school_db().".class_routine cr on cr.c_rout_sett_id=cs.c_rout_sett_id where cs.section_id=".$section_id."")->result_array();
		
		foreach($class_routine_arr as $cr)
		{
			$all_sub[]=$cr['subject_id'];
		}
		$unused=array();
		$used=array();
		foreach($subject_id as $key=>$value){
				if(!in_array($value->subject,$all_sub))
				{
					$unused[$value->subject]['per_week']=$value->per_week;
					$unused[$value->subject]['per_day']=$value->per_day;
				}
				else{
					$used[$value->subject]['per_week']=$value->per_week;
					$used[$value->subject]['per_day']=$value->per_day;
				}
			}
		if(sizeof($all_sub)>0)
		{ 
		$used_subject_ids=implode(',',$all_sub);
			 
		$this->db->query($q="delete from ".get_school_db().".subject_section where section_id=".$section_id."  and subject_id not in (".$used_subject_ids.")");
		if(sizeof($used)>0){
				foreach($used as $key=>$value){
				
				$data=array();
				$data['school_id']=$_SESSION['school_id'];
				$data['periods_per_day']=$value['per_day'];
				$data['periods_per_week']=$value['per_week'];
				
				$this->db->where('section_id',$section_id);
				$this->db->where('subject_id',$key);
				$this->db->update(get_school_db().'.subject_section', $data);
				
			}
		
		}
	}
		else{
		
			$this->db->query($q="delete from ".get_school_db().".subject_section where section_id=".$section_id." ");
		}
		
		
		if(sizeof($unused)>0){
				foreach($unused as $key=>$value){
					
				
				$data=array();
				$data['school_id']=$_SESSION['school_id'];
				$data['section_id']=$section_id;
				$data['subject_id']=$key;
				$data['periods_per_day']=$value['per_day'];
				$data['periods_per_week']=$value['per_week'];
				$this->db->insert(get_school_db().'.subject_section', $data);
				
				
				
			}
			
		}
	}
	
	function get_component_term(){
		if($this->input->post('status') != '')
		{
		   echo yearly_terms_option_list($this->input->post('academic_year'),'',$this->input->post('status'));	
		}
		else
		   echo yearly_terms_option_list($this->input->post('academic_year'),'',1);
	}
	
    function components($param1='',$param2='',$param3='')
    {
    	if($_SESSION['user_login']!= 1)
		redirect(base_url());
		if($param1 == 'create')
		{

			$data['title']       = $this->input->post('title');
			$data['percentage']  = $this->input->post('percentage');
			$data['subject_id']  = $this->input->post('subject_id');
			$data['school_id']   = $_SESSION['school_id'];

			if($data['title'] !='' && $data['percentage'] !='')
			{
				$this->db->insert(get_school_db().'.subject_components', $data);
				echo '<span class="green">'.get_phrase('components_added_successfully').'</span>';
				exit;
			}
		}
		if($param1 == 'do_update')
		{
			$data['title']       = $this->input->post('title');
			$data['percentage']  = $this->input->post('percentage');
			$data['subject_id']  = $this->input->post('subject_id');
			$data['school_id']   = $_SESSION['school_id'];
			
			if($data['title']!='' && $data['percentage']!='' )
			{
				$this->db->where('school_id',$_SESSION['school_id']); 
				$this->db->where('subject_component_id', intval($this->input->post('component_id')));
				$this->db->update(get_school_db().'.subject_components', $data);
				echo '<span class="green">'.get_phrase('components_updated_successfully').'</span>';	
				exit;
			}
			exit;
     
		} 
		if($param1 == 'delete')
		{
			$component_id = $this->input->post('comp_id');
			$qur_5=$this->db->query("select marks_components_id from ".get_school_db().".marks_components where subject_component_id=$component_id and school_id=".$_SESSION['school_id'])->result_array();

			if(count($qur_5)>0)
			{
				echo '<span class="red">'.get_phrase('deletion_failed_record_already_in_use').'</span>';
				exit();	
			}		

			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('subject_component_id', $component_id);
			if($this->db->delete(get_school_db().'.subject_components'))
			{
				echo '<span class="green">'.get_phrase('deleted_successfully').'</span>';	
			}
		}
		}
		
		function get_components()
		{
            // $data['subject_id'] = $this->uri->segment(3);
            
            $data['subject_id'] = $this->input->post('subject_id');
            
			$this->load->view("backend/admin/ajax/get_components",$data);
        }
        
		function check_code()
		{
			$code=$this->input->post('code');
			$list_array=array();
			if(check_subject_code($code)=='exists')
			{	
				$list_array['exists']=1;	
			}
			else
			{
				$list_array['exists']=0;
			}
			echo json_encode($list_array);
		}
		
		function edit_code()
		{
			$code_check=check_subject_code($this->input->post('code'),$this->input->post('subj_id'));
			if($code_check=='exists')
			{	
				$list_array['exists']=1;	
			}
			else
			{
				$list_array['exists']=0;
			}
			echo json_encode($list_array);
		}
		
		function assign_subject_section(){
			
    		$subject_id=$this->input->post('subject_id');
    		$section_id=get_object_vars(json_decode($this->input->post('secArr')));
    		$data['subject_id']=$subject_id;
    		$data['school_id']=$_SESSION['school_id'];
    		
    		$class_routine_arr=$this->db->query($q="select distinct cs.section_id from ".get_school_db().".class_routine_settings cs  inner join ".get_school_db().".class_routine cr on cr.c_rout_sett_id=cs.c_rout_sett_id where cr.subject_id=".$subject_id."")->result_array();
    		
    		
    		foreach($class_routine_arr as $cr)
    		{
    			$all_sub[]=$cr['section_id'];
    		}
    		
    		
    		$unused=array();
    		$used=array();
    		foreach($section_id as $key=>$value){
    				if(!in_array($value->section,$all_sub))
    				{ 
    					$unused[]=$value->section;
    					$unused[$value->section]['per_week']=$value->per_week;
    					$unused[$value->section]['per_day']=$value->per_day;
    					
    				}
    				else{
    					$used[]=$value->section;
    					$used[$value->section]['per_week']=$value->per_week;
    					$used[$value->section]['per_day']=$value->per_day;
    				}
    
    			}
    		if(sizeof($all_sub)>0)
    		{ 
    		$used_subject_ids=implode(',',$all_sub);
    			 
    		$this->db->query($q="delete from ".get_school_db().".subject_section where subject_id=".$subject_id."  and section_id not in (".$used_subject_ids.")");
    		if(sizeof($used)>0){
    			
    				foreach($used as $key=>$value){
    				
    				$data=array();
    				$data['school_id']=$_SESSION['school_id'];
    				$data['subject_id']=$subject_id;
    				$data['section_id']=$key;
    				$data['periods_per_day']=$value['per_day'];
    				$data['periods_per_week']=$value['per_week'];
    				$this->db->where('section_id',$key);
    				$this->db->where('subject_id',$subject_id);
    				$this->db->update(get_school_db().'.subject_section', $data);
    
    			}
    		
    		}
    	}
    		else{
    			$this->db->query($q="delete from ".get_school_db().".subject_section where subject_id=".$subject_id." ");
    		}
    		
    		
    		if(sizeof($unused)>0){
    			
    			foreach($unused as $key=>$value){
    			
    				$data=array();
    				$data['school_id']=$_SESSION['school_id'];
    				$data['subject_id']=$subject_id;
    				$data['section_id']=$key;
    				$data['periods_per_day']=$value['per_day'];
    				$data['periods_per_week']=$value['per_week'];
    				
    				$this->db->insert(get_school_db().'.subject_section', $data);
    			}
    			
    		}
		
		
		}
		
		function assign_teacher_subject(){
			
		$teacher_id=$this->input->post('teacher_id');
		$subject_id=get_object_vars(json_decode($this->input->post('subArr')));
		$data['school_id']=$_SESSION['school_id'];
		
		$result_subject=$this->db->query($q="SELECT distinct st.subject_id FROM ".get_school_db().".`subject_teacher` st inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.subject_teacher_id=st.subject_teacher_id where st.teacher_id=".$teacher_id." and st.school_id=".$_SESSION['school_id']."")->result_array();
		
		foreach($result_subject as $t)
		{
			$sub_arry[]=$t['subject_id'];
		}
		
		$unused=array();
		if(sizeof($subject_id)>0){
		
		foreach($subject_id as $k=>$v)
		{	
		
			if(in_array($v->subject,$sub_arry))
			{
				//$used[]=$v['value'];
			}		
			else
			{
				$unused[]=$v->subject;
			}
			
		}
		}
		
		if(sizeof($sub_arry)>0)
		{ 
			$used_subject_ids=implode(',',$sub_arry);
			$this->db->query($q="delete from ".get_school_db().".subject_teacher where teacher_id=".$teacher_id." and subject_id not in (".$used_subject_ids.")");
		}
		else{
			  $this->db->query($q="delete from ".get_school_db().".subject_teacher where teacher_id=".$teacher_id." ");
		}
		
		if(sizeof($unused)>0)
		{
    		foreach($unused as $unused_id)
    			{
    				$data['school_id']=$_SESSION['school_id'];
    				$data['teacher_id']=$teacher_id;
    				$data['subject_id']=$unused_id;
    				$this->db->insert(get_school_db().'.subject_teacher', $data);
    			}
    		}
		}
		
		function asign_teacher_generator($param1 = '', $param2 = '',$param3 = '')
		{
			$page_data['teacher_id']=$param1;
		    $this->load->view('backend/admin/ajax/get_asign_teacher.php',$page_data);
		} 
 
 }
