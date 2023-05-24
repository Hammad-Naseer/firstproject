<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();
class Exams extends CI_Controller{
    
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('exams');
		$this->load->helper('common');
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
		
	}
	public function index(){
 
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
		if($_SESSION['user_login'] == 1)
		redirect(base_url() . 'exams/exam');
	}
	
	
	public function open_student_marksheet($student_id){
	    if($_SESSION['user_login'] != 1)
		   redirect(base_url());
		   
		$page_data['student_id']=$student_id;   
		$page_data['page_name']  = 'open_student_marksheet';
		$page_data['page_title'] = get_phrase('student_marksheet');
		$this->load->view('backend/index', $page_data);	   
	}
    
	/****MANAGE EXAMS*****/
	function exam($param1 = '', $param2 = '' , $param3 = ''){
		if($_SESSION['user_login'] != 1)
		redirect(base_url());
		if($param1 == 'create'){
			$data['name'] = $this->input->post('name');
			$start_date =$this->input->post('start_date');
			$start_arry=explode('/',$start_date);
			$data['start_date'] = $start_date;
			$end_date=$this->input->post('end_date');
			$end_arry=explode('/',$end_date);
			$data['end_date'] = $end_date;
			$data['comment'] = $this->input->post('comment');
			$data['school_id'] = $_SESSION['school_id'];
			$data['yearly_terms_id'] = $this->input->post('term_id');
			
			$this->db->insert(get_school_db().'.exam', $data);
			
			$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
			redirect(base_url().'exams/exam/');
		}
		if($param1 == 'edit' && $param2 == 'do_update'){
			$data['name']    = $this->input->post('name');



			$start_date =$this->input->post('start_date');

			$start_arry=explode('/',$start_date);

			$data['start_date'] = $start_date;

			$end_date=$this->input->post('end_date');
			$end_arry=explode('/',$end_date);
			$data['end_date'] = $end_date;
			$data['comment'] = $this->input->post('comment');
			$data['yearly_terms_id'] = $this->input->post('term_id');
			$this->db->where('exam_id', $param3);
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->update(get_school_db().'.exam', $data);
			 $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
			redirect(base_url() . 'exams/exam/');
		}
		
		else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where(get_school_db().'.exam', array(
					'exam_id' => $param2,
					'school_id' =>$_SESSION['school_id']
				))->result_array();
		}
		if($param1 == 'delete'){
			 $q="select exam_id from ".get_school_db().".exam_routine  where exam_id=".$param2;
			$rows=$this->db->query($q)->num_rows();
			if($rows==0){
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('exam_id', $param2);
			$this->db->delete(get_school_db().'.exam');
			 $this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
			}
			else{
			 $this->session->set_flashdata('club_updated', get_phrase('failed_to_delete_record'));
			}
			
			redirect(base_url() . 'exams/exam/');
		}
		$this->db->where('school_id',$_SESSION['school_id']);
		$page_data['exams']      = $this->db->get(get_school_db().'.exam')->result_array();
		$page_data['page_name']  = 'exam';
		$page_data['page_title'] = get_phrase('manage_exam');
		$this->load->view('backend/index', $page_data);
	}
    
	function get_terms(){
		$academic_id=$this->input->post('academic_year');
		$status=$this->input->post('status');
		if($status!=''){
			echo yearly_terms_option_list($academic_id,'',$status);
		}
		else
		echo yearly_terms_option_list($academic_id);
		
	}
	
	function get_exam_list(){
	
		if($_POST){
			
			$term_id=$this->input->post('term_id');
		}
	
	
		$data['term_id']=$term_id;
		
		$this->load->view("backend/admin/ajax/exam_list",$data);
		
				
	}
    
    
	function grade($param1 = '', $param2 = ''){
    	
    	
		if($_SESSION['user_login'] != 1)
		redirect(base_url());
		if($param1 == 'create'){
			$data['name']        = $this->input->post('name');
			$data['grade_point'] = $this->input->post('grade_point');
			$data['mark_from']   = $this->input->post('mark_from');
			$data['mark_upto']   = $this->input->post('mark_upto');
			$data['comment']     = $this->input->post('comment');
			$data['order_by']     = $this->input->post('order_by');
			$data['school_id'] = $_SESSION['school_id'];
			$this->db->insert(get_school_db().'.grade', $data);
			//echo $this->db->last_query();
			$this->session->set_flashdata('club_updated', get_phrase('failed_to_delete_record'));
			redirect(base_url() . 'exams/grade/');
		}
		if($param1 == 'do_update'){
			$data['name']        = $this->input->post('name');
			$data['grade_point'] = $this->input->post('grade_point');
			$data['mark_from']   = $this->input->post('mark_from');
			$data['mark_upto']   = $this->input->post('mark_upto');
			$data['comment']     = $this->input->post('comment');
			$data['order_by']     = $this->input->post('order_by');
            
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('grade_id', $param2);
			$this->db->update(get_school_db().'.grade', $data);
			$this->session->set_flashdata('club_updated', get_phrase('record_saved'));
			redirect(base_url() . 'exams/grade/');
		} 
        
		else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where('grade', array(
					'grade_id' => $param2,
					'school_id' =>$_SESSION['school_id']
				))->result_array();
		}
       
		if($param1 == 'delete'){
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('grade_id', $param2);
			$this->db->delete(get_school_db().'.grade');
			$this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
			redirect(base_url() . 'exams/grade/');
		}
		$this->db->order_by("order_by", "asc");
		$this->db->where('school_id',$_SESSION['school_id']);
		$page_data['grades']=$this->db->get(get_school_db().'.grade')->result_array();
		$page_data['page_name']  = 'grade';
		$page_data['page_title'] = get_phrase('manage_grade');
		$this->load->view('backend/index', $page_data);
	}
    
    
    
	/**********MANAGING EXAM ROUTINE******************/
	function exam_routine($param1 = '', $param2 = '', $param3 = ''){
		$this->load->helper('exams');
		if($_SESSION['user_login'] != 1)
		redirect(base_url());
		if($param1 == 'create'){
			//print_R($_POST);exit;
			$data['exam_id']= $this->input->post('exam_id');
			$data['section_id']   = $this->input->post('section_id');
			$data['subject_id']=$this->input->post('subject_id');
			
			$data['time_start']=$this->input->post('time_start');
			$data['time_end']   = $this->input->post('time_end');
			
			
			//$expl  = explode('/',$this->input->post('exam_date'));
			//$expl[2].'/'.$expl[0].'/'.$expl[1];
			$exam_date=date('Y-m-d', strtotime($this->input->post('exam_date')));
			
			$data['exam_date']=$exam_date;
			//$data['exam_date']=$expl[2].'/'.$expl[0].'/'.$expl[1];
			$data['school_id'] = $_SESSION['school_id'];
			$data['total_marks']   = $this->input->post('total_marks');
			
			//print_r($data);exit;
			$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
			$this->db->insert(get_school_db().'.exam_routine', $data);
			
			//redirect(base_url() . 'exams/exam_routine/');
		}
		if($param1 == 'do_update'){
			
			$data['section_id']   = $this->input->post('section_id');
			$data['subject_id']=$this->input->post('subject_id');
			$data['exam_id']= $this->input->post('exam_id');
			$data['time_start']=$this->input->post('time_start');
			$data['time_end']   = $this->input->post('time_end');
			$data['yearly_terms_id']   = $this->input->post('yearly_term');
			$data['total_marks']   = $this->input->post('total_marks');			
			$expl  = explode('/',$this->input->post('exam_date'));
			$data['exam_date']=$expl[2].'-'.$expl[0].'-'.$expl[1];
          
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('exam_routine_id', $param2);
			$this->db->update(get_school_db().'.exam_routine', $data);
			//redirect(base_url() . 'exams/exam_routine/');
		} 
        
		else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where(get_school_db().'.exam_routine', array(
					'exam_routine_id' => $param2,
					'school_id' =>$_SESSION['school_id']
				))->result_array();
		}
		if($param1 == 'delete')
		{
			$q="select r.exam_id from ".get_school_db().".exam_routine r  inner join ".get_school_db().".marks m  on r.exam_id=m.exam_id where r.exam_routine_id=".$param2;
			$rows=$this->db->query($q)->num_rows();
			if($rows==0)
			{
    			$this->db->where('school_id',$_SESSION['school_id']);
    			$this->db->where('exam_routine_id', $param2);
    			$this->db->delete(get_school_db().'.exam_routine');
    			$this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
    			echo "deleted";exit; 
			}
			else{
    			$this->session->set_flashdata('club_updated', get_phrase('failed_to_deleted_record'));
    			echo "failed";exit; 	
			}
			
			//echo "Deleted";exit; 
			redirect(base_url() . 'exams/exam_routine/');
		}
		$page_data['page_name']  = 'exam_routine';
		$page_data['page_title'] = get_phrase('manage_exam_routine');
		$this->load->view('backend/index', $page_data);
	}
	/**********Edit EXAM ROUTINE******************/
	function edit_exam_routine($param1 = ''){
		if($_SESSION['user_login'] != 1)
		redirect(base_url());
			
		$data['exam_routine_id']   = $param1;
			
		$page_data['data']	=	$data;
		$page_data['page_name']  = 'edit_exam_routine';
		$page_data['page_title'] = get_phrase('manage_exam_routine');
		$this->load->view('backend/index', $page_data);
	}
    
	function get_exam_type(){
		$term_id=$this->input->post(yearly_term);
		echo exam_type_option_list($term_id);
	}
	function get_class(){
		$dept_id=$this->input->post('department_id');
		$class_id=$this->input->post('class_id');
		echo  class_option_list($dept_id,$class_id);

	}
	function get_class_section(){
		$class_id=$this->input->post('class_id');
		$section_id=$this->input->post('section_id');
		
		echo section_option_list($class_id,$section_id);

	}
	function get_section_subject(){
		
		$section_id=$this->input->post('section_id');
		
		echo subject_option_list($section_id);

	}
	function get_subject_exam(){
		
		$exam_id=$this->input->post('exam_id');
		$section_id=$this->input->post('section_id');
		$subject_id=$this->input->post('subject_id');
		$selected=0;
		if(isset($subject_id) && $subject_id >0)
		{
			$selected=$subject_id;
		}
		echo check_subject_exam($exam_id,$section_id,$selected);

	}
	function get_term(){
		
		$academic_year_id=$this->input->post('academic_year');
		
		echo yearly_terms_option_list($academic_year_id);

	}
	function get_routine(){
		$data = array();
		
		$data['section_id']=$section_id=$this->input->post('section_id');
		$data['exam_id']=$this->input->post('exam_id');
		
		
		//$acad=academic_hierarchy($yearly_term);
		//$data['academic_year']=$acad['a_id'];
		//$sec=section_hierarchy($section_id);
		//$data['class_id']=$sec['c_id'];
		//$data['department_id']=$sec['d_id'];
	
		$this->load->view("backend/admin/ajax/exam_routine",$data);	
	}    
	
	function datesheet_pdf_download()
    {
        
        $page_data['section_id']=$section_id=$this->input->post('section_id');
		$page_data['exam_id']=$this->input->post('exam_id');
        
        $page_data['page_name']    = 'datesheet_pdf_download';
        $page_data['page_title']   = get_phrase('datesheet_pdf_download');
        $this->load->library('Pdf');
        $view = 'backend/admin/datesheet_pdf_download';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->set_paper("A4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
	
	function marks($param1 = '', $param2 = '', $param3 = ''){
		if($_SESSION['user_login']!= 1)
		redirect(base_url());
		$page_data['page_name']  = 'marks';
		$page_data['page_title'] = get_phrase('manage_exam_marks');
		$msg=$this->uri->segment('3');
		
		
		if(isset($msg))
		{
			$page_data['msg']=$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
			$page_data['section_id']=$this->uri->segment('5');
			$page_data['subject_id']=$this->uri->segment('6');
			$page_data['exam_id']=$this->uri->segment('4');
		}
		$this->load->view('backend/index', $page_data);
	}
	
	function get_marks(){
	//	print_R($_POST);exit;
		
		
		$data['section_id']=$this->input->post('section_id');
		$data['subject_id']=$this->input->post('subject_id');
		$data['exam_id']=$this->input->post('exam_id');
		
		$this->load->view("backend/admin/ajax/marks",$data);	
	 
	}
	function save_exam_marks(){
		
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
				//echo $this->db->last_query();
			} 
			//exit();		
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
		/*echo "<pre>";
		print_r($comp_arr);*/
		if($comp_arr){
			foreach($comp_arr as $arr){
				$marks_arr[$arr->student_id][$arr->subject_comp_id]['m']=$arr->marks;
			}
			//echo "<pre>";print_r($marks_arr);
		}
		
		
		if($exam_marks==''){
			foreach($std_arr as $k=>$v){
				
				 $q="select m.* from ".get_school_db().".marks m  where m.student_id =".$k." AND m.subject_id=".$subject_id." AND m.exam_id=".$exam_id." AND m.school_id=".$_SESSION['school_id']."";
				$resMarks=$this->db->query($q)->num_rows();
				$resMarksArr=$this->db->query($q)->result_array();
				
				$data['student_id']=$k;
				$data['subject_id']=$subject_id;
				$data['exam_id']=$exam_id;
				$data['comment']=$v;
				$data['school_id']=$_SESSION['school_id'];
				
	
				if($resMarks==0){
		
					if($this->db->insert(get_school_db().'.marks', $data)){
						$marks_id=$this->db->insert_id();
						if(sizeof($marks_arr)>0){
							foreach($marks_arr[$k] as $key=>$val){
								if($val['m']!=''){
									$comp['subject_component_id']=$key;
									$comp['marks_obtained']=$val['m'];
									$comp['marks_id']=$marks_id;
									$comp['school_id']=$_SESSION['school_id'];	
									$this->db->insert(get_school_db().'.marks_components', $comp);	
									echo $this->db->last_query();
									$this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
									//echo "added1";
								}

							}
						}
					}
						
				}
		
				else{
		
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
									//echo $this->db->last_query();
									
								}
								else{
								$comp['marks_obtained']=$val['m'];
								$this->db->where('subject_component_id',$key);
								$this->db->where('marks_id',$marks_id);
								$this->db->where('school_id',$_SESSION['school_id']);
								
								$this->db->update(get_school_db().'.marks_components', $comp);
								//echo $this->db->last_query();
									
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
							//echo $this->db->last_query();
										
								}

							}
						}
				
					}
				
					}
						
				}
			
				
			}
			$this->session->set_flashdata('club_updated', get_phrase('record_saved'));
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
					if($this->db->insert(get_school_db().'.marks', $data))
					{
						$marks_id=$this->db->insert_id();
						$comp['subject_component_id']=0;
						$comp['marks_obtained']=$v['marks'];
						$comp['marks_id']=$marks_id;
						$comp['school_id']=$_SESSION['school_id'];	
						$this->db->insert(get_school_db().'.marks_components', $comp);
					}
				}
				else
				{
					foreach($resMarksArr as $mark)
					{	
						$marks_id2=$mark['marks_id'];
						$this->db->where('marks_id',$marks_id2);
						$this->db->update(get_school_db().'.marks',$data);
						$comp['subject_component_id']=0;
						$comp['marks_obtained']=$v['marks'];
						$comp['marks_id']=$marks_id2;
						$comp['school_id']=$_SESSION['school_id'];	
						
						$marks_comp_arr = $this->db->get_where(get_school_db().'.marks_components',array('marks_id' => $marks_id2))->result_array();
						if (count($marks_comp_arr) > 0)//update
						{
							$this->db->where('marks_id',$marks_id2);
							$this->db->where('school_id',$_SESSION['school_id']);
							$this->db->update(get_school_db().'.marks_components', $comp);
						}
						else //insert
						{
							$this->db->insert(get_school_db().'.marks_components', $comp);
						}
						//echo $this->db->last_query();
						//echo "updated";
					}
				}
			}
		$this->session->set_flashdata('club_updated', get_phrase('record_updated'));
		
		echo "updated";
		exit;}
	}
	
	function check_total_marks(){
		$subject_id=$this->input->post('subject_id');
		
		
		
		
		
		$q="select subject_component_id,title as component,percentage as marks from ".get_school_db().".subject_components where subject_id=".$subject_id." and school_id=".$_SESSION['school_id']." ";
		
		
		
		
		
		$resRowNum=$this->db->query($q)->num_rows();
		
		if($resRowNum>0){
			$compArr=array();
			$res=$this->db->query($q)->result_array();
			$str='';
			foreach($res as $row)
			
			{
				$str.=$row['component'].' <input max="100" class="comp form-control" type="number" name="comp" id="'.$row['subject_component_id'].'" value="'.$row['marks'].'"/><br/>';
				
			}
			//$str.="<button type='button'  id='submit-component'>Update</button>";
			echo $str;
		}
		
		
	}
	function get_all_grades(){
		$q="select grade_point,mark_from,mark_upto from ".get_school_db().".grade where school_id=".$_SESSION['school_id']."";
		$resGrades=$this->db->query($q)->result_array();
		
		foreach($resGrades as $arr){

			$gradeArr[$arr['grade_point']]['mark_from'][]=$arr['mark_from'];
			$gradeArr[$arr['grade_point']]['mark_upto'][]=$arr['mark_upto'];
						
		}
		echo json_encode($gradeArr); 
	}
	function student_marksheet($class_id = ''){
		if($_SESSION['user_login'] != 1)
		redirect('login');
		$page_data['page_name']  = 'student_marksheet';
		$page_data['page_title']=get_phrase('student_marksheet');
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	
	
	function get_marksheet(){
    	$data['section_id']=$this->input->post('section_id');	
    	$data['yearly_term_id']=$this->input->post('yearly_term_id');	
    	$data['exam_id']=$this->input->post('exam_id');
    	$this->load->view('backend/admin/ajax/marksheet', $data);
	}
	
	public function get_exams_by_yearly_term(){
	    
	    $yearly_term = $this->input->post('yearly_term_id');
	    echo yearly_term_selector_exam_result(0,0,0,$yearly_term);                        
	}
	
	function get_exam_result()
	{
		$student_id = $this->uri->segment(3);	
		$exam_id    = $this->uri->segment(5);
		$yearly_term_id = $this->uri->segment(4);
		
		if($student_id != "" && $exam_id !="" && $yearly_term_id!=""){
		    $q = "select * from ".get_school_db().".marks m inner 
    		    join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id inner
    		    join ".get_school_db().".student s on s.student_id=m.student_id
    		    inner join ".get_school_db().".exam_weightage ew on 
    		    (ew.yearly_term_id= $yearly_term_id and ew.subject_id= m.subject_id and ew.section_id= s.section_id) 
    		    where m.exam_id=".$exam_id." and m.student_id=".$student_id." and 
    		    m.school_id=".$_SESSION['school_id']." group by m.subject_id";
    		$result=$this->db->query($q)->result_array();
    	
    		$data['result']=$result;
    		$query_assm="select assm.assessment_title , assm.total_marks , assm_res.obtained_marks , g.grade_point as grade_name ,
    		             ew.assessment_percentage , sub.name as subject_name , sub.subject_id from ".get_school_db().".assessment_result assm_res 
    		             inner join ".get_school_db().".assessments assm on assm_res.assessment_id = assm.assessment_id
    		             inner join ".get_school_db().".grade g on g.grade_id = assm_res.grade_id
    		             inner join ".get_school_db().".subject sub on sub.subject_id = assm_res.subject_id
    		             left join ".get_school_db().".exam_weightage ew on 
    		             (ew.yearly_term_id= assm.yearly_term_id and ew.section_id= assm_res.section_id and ew.subject_id= assm_res.subject_id)  
    		             where assm_res.student_id=".$student_id." and assm.yearly_term_id=".$yearly_term_id." and 
    		             assm.school_id=".$_SESSION['school_id']." ";
    		$assm_result=$this->db->query($query_assm)->result_array();
    		$data['assm_result'] = $assm_result;

    		if(count($data['result']) > 0 && $data['assm_result'] > 0)
		    {
		        $data['student_id']=$student_id;
        		$data['exam_id']=$exam_id;
        		$data['yearly_term_id']=$yearly_term_id;
		        $data['page_name']='ajax/result';
                $data['page_title']=get_phrase('ajax/result');
                $this->load->view('backend/index', $data);
		    }else{
		        $this->session->set_flashdata('not_found', get_phrase('marksheet_record_not_found'));
		        redirect(base_url() . 'exams/student_marksheet');
		    }
		}else{
		    $this->session->set_flashdata('not_found', get_phrase('marksheet_record_not_found'));
		    redirect(base_url() . 'exams/student_marksheet');
		}
	}
	
	function exam_result_pdf()
	{
	    $student_id = $this->uri->segment(3);	
		$exam_id    = $this->uri->segment(5);
		$yearly_term_id = $this->uri->segment(4);
		
		if($student_id != "" && $exam_id !="" && $yearly_term_id!=""){
		    $q = "select * from ".get_school_db().".marks m inner 
    		    join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id inner
    		    join ".get_school_db().".student s on s.student_id=m.student_id
    		    inner join ".get_school_db().".exam_weightage ew on 
    		    (ew.yearly_term_id= $yearly_term_id and ew.subject_id= m.subject_id and ew.section_id= s.section_id) 
    		    where m.exam_id=".$exam_id." and m.student_id=".$student_id." and 
    		    m.school_id=".$_SESSION['school_id']." group by m.subject_id";
    		$result=$this->db->query($q)->result_array();
    		$data['result']=$result;
    		$query_assm="select assm.assessment_title , assm.total_marks , assm_res.obtained_marks , g.grade_point as grade_name ,
    		             ew.assessment_percentage , sub.name as subject_name , sub.subject_id from ".get_school_db().".assessment_result assm_res 
    		             inner join ".get_school_db().".assessments assm on assm_res.assessment_id = assm.assessment_id
    		             inner join ".get_school_db().".grade g on g.grade_id = assm_res.grade_id
    		             inner join ".get_school_db().".subject sub on sub.subject_id = assm_res.subject_id
    		             left join ".get_school_db().".exam_weightage ew on 
    		             (ew.yearly_term_id= assm.yearly_term_id and ew.section_id= assm_res.section_id and ew.subject_id= assm_res.subject_id)  
    		             where assm_res.student_id=".$student_id." and assm.yearly_term_id=".$yearly_term_id." and 
    		             assm.school_id=".$_SESSION['school_id']." ";
    		$assm_result=$this->db->query($query_assm)->result_array();
    		$data['assm_result'] = $assm_result;
    		if(count($data['result']) > 0 && $data['assm_result'] > 0)
		    {
		        $data['student_id']=$student_id;
        		$data['exam_id']=$exam_id;
        		$data['yearly_term_id']=$yearly_term_id;
		        $data['page_name']='exam_result_pdf';
                $data['page_title']=get_phrase('exam_result_pdf');
                $this->load->view('backend/index', $data);
                
                $this->load->library('Pdf');
                $view = 'backend/admin/exam_result_pdf';
                $this->pdf->load_view($view,$data);
                $this->pdf->set_paper("A4", "landscape");
                $this->pdf->render();
                $this->pdf->stream("".$data['page_title'].".pdf");
                
		    }else{
		        echo "Query False";
		    }
		}else{
		    echo "False";
		}
	}
	
	function term_date_range()
	{
	    $start_date=$this->input->post('start_date');
		$end_date=$this->input->post('end_date');
		$term_id=$this->input->post('term_id');
		if(!empty($start_date))
		{
		echo term_date_range($term_id,$start_date,'');
		}
		if(!empty($end_date))
		{
		echo term_date_range($term_id,'',$end_date);
		}
	}
	
	function exam_date_range()
	{
		echo $exam_date=$this->input->post('exam_date');
	 $exam_id=$this->input->post('exam_id');
		//if(!empty($exam_date))
		//{
			echo exam_date_range($exam_id,$exam_date);
		//}
		
	}
	function reset_exam_marks(){
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
		else{
			foreach($student_marks_array as $arr){
				$std_arr[$arr->student_id]=$arr->comment;
			}
		}
		
		foreach($std_arr as $k=>$v)
		{
		
			$q="select marks_id from ".get_school_db().".marks where student_id=$k
				and subject_id = $subject_id
                and exam_id = $exam_id";
			$marks_id=$this->db->query($q)->result_array();
			
			if(sizeof($marks_id)>0)
			{
				foreach($marks_id as $i=>$j)
				{
					$delMarksComponents="delete from ".get_school_db().".marks_components where marks_id=".$j['marks_id'];
					$this->db->query($delMarksComponents);
				}
				$delMarks="delete from ".get_school_db().".marks where student_id=$kand subject_id = $subject_id
                	and exam_id = $exam_id";
				$this->db->query($delMarks);
			}
			
		}
		
	}
	
	function update_components(){
		$subject_id=$this->input->post('subject_id');
		$subject_component_id=$this->input->post('subject_component_id');
		$marks=$this->input->post('marks');
		$data['percentage']=$marks;
		$this->db->where('subject_id',$subject_id);
		$this->db->where('school_id',$_SESSION['school_id']);
		$this->db->where('subject_component_id',$subject_component_id);
		if($this->db->update(get_school_db().".subject_components",$data))
		{	
			//echo $this->db->last_query();
			echo "updated";
		}
		
	} 
}
    
