<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Assessment extends CI_Controller
{
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('common');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
	}
    
    function edit($assessment_id)
    {
        
        if($_SESSION['user_login']!=1) redirect('login' );
	    
	    $q                  =  "select * from ".get_school_db().".assessments WHERE assessment_id = ".$assessment_id." and school_id=".$_SESSION['school_id']."";
	    $assessment_details =   $this->db->query($q)->row();
	    
	    $page_data['assessment_details']  =	 $assessment_details;
		$page_data['page_name']		      =	'assessment/edit';
		$page_data['page_title']		  =	 get_phrase('assessment_details');
		$page_data['assessment_id']       =  $assessment_id;
		$this->load->view('backend/index',   $page_data); 
		
    }
    
    function edit_assessment_details($assessment_id)
    {
        
        if($_SESSION['user_login']!=1) redirect('login' );
	    
	    $q = "SELECT ass_que.* , que_typ.* from ".get_school_db().".assessment_questions ass_que
	    inner join ".get_school_db().".question_types que_typ on que_typ.question_type_id = ass_que.question_type_id
	    where ass_que.assessment_id = ".$assessment_id."";
	    $assessments = $this->db->query($q)->result_array();
	    
	    $page_data['assessments']		  =	 $assessments;
		$page_data['page_name']		      =	'assessment/edit_assessment_details';
		$page_data['page_title']		  =	 get_phrase('edit_assessment_details');
		$page_data['assessment_id']       =  $assessment_id;
		$this->load->view('backend/index',   $page_data); 
		
    }
    
    function update_assessment()
	{
	    
	    $assessment_id                         =   $this->input->post('assessment_id'); 
	    $data['assessment_title']              =   $this->input->post('assessment_title');
	   // $data['system_class']                  =   $this->input->post('class_id');
	   // $data['system_subject']                =   $this->input->post('subject_id');
	    $data['yearly_term_id']                =   $this->input->post('yearly_term_id');
	    $data['total_marks']                   =   $this->input->post('total_marks');
	    $data['mcq_questions']                 =   $this->input->post('mcq_questions');
	    $data['true_false_questions']          =   $this->input->post('true_false_questions');
	    $data['fill_in_the_blanks_questions']  =   $this->input->post('fill_in_the_blanks_questions');
	    $data['short_questions']               =   $this->input->post('short_questions');
	    $data['long_questions']                =   $this->input->post('long_questions');
	    $data['pictorial_questions']           =   $this->input->post('pictorial_questions');
	    $data['match_questions']               =   $this->input->post('match_questions');
	    $data['drawing_questions']             =   $this->input->post('drawing_questions');
	    $data['remarks']                       =   $this->input->post('remarks');
	    $data['assessment_type']               =   1;
	    $data['teacher_id']                    =   $_SESSION['user_id'];
	    $data['school_id']                     =   $_SESSION['school_id'];
	    
	    $this->db->where('school_id' ,     $_SESSION['school_id']);
	    $this->db->where('assessment_id' , $assessment_id);
	    $this->db->update(get_school_db().".assessments" , $data);
	    
	    $this->session->set_flashdata('club_updated', get_phrase('assessment_details_are_updated_successfully'));
	    redirect(base_url().'assessment/view_assessment');
	    
	}
    
    function mark_assessment()
    {
	    
	    $assessment_id  = $this->input->post('assessment_id');
	    $question_count = $this->input->post('question_count');
	    $student_id     = $this->input->post('student_id');
	    $remarks        = $this->input->post('remarks');
	    $total_marks    = $this->input->post('total_marks');
	    
	    $section_id     = $this->input->post('section_id');
	    $subject_id     = $this->input->post('subject_id');
	    
	    $total_obtained_marks = 0;

	    for($index = 1; $index <= $question_count;  $index++){
	        
	        $question_obtained_marks_attr      = 'question_obtained_marks_'.$index;
	        $question_id_name_attr             = 'question_ids_'.$index;
	        $question_type_id_name_attr        = 'question_type_ids_'.$index;

	        $question_obtained_marks           = $this->input->post($question_obtained_marks_attr);
	        $question_id                       = $this->input->post($question_id_name_attr);
	        $question_type_id                  = $this->input->post($question_type_id_name_attr);         
	        
	        $total_obtained_marks += $question_obtained_marks;

            $data_update['obtained_marks'] = $question_obtained_marks;
	        $this->db->where('question_id',$question_id);
	        $this->db->where('assessment_id',$assessment_id);
	        $this->db->where('student_id',$student_id);
            $this->db->update(get_school_db().'.assessment_solution',$data_update); 
            
            
            if($question_type_id == 7){
                
                 $assessment_matching_solution_id_name_attr      = 'assessment_matching_solution_id_'.$index;
                 $option_marks_obtained_name_attr                = 'option_marks_obtained_'.$index;
                
                 $assessment_matching_solution_id     = $this->input->post($assessment_matching_solution_id_name_attr);
	             $option_marks_obtained               = $this->input->post($option_marks_obtained_name_attr);
	             
	             for($opt = 0; $opt < count($assessment_matching_solution_id); $opt++){
        	           $data_update_option['option_marks_obtained'] = $option_marks_obtained[$opt];
        	           $this->db->where('assessment_matching_solution_id',$assessment_matching_solution_id[$opt]);
                       $this->db->update(get_school_db().'.assessment_matching_solution',$data_update_option);
                 }

            }

	    }
	    
	    
	    $marksIntoHundred = $total_obtained_marks * 100;
	    $percentage       = $marksIntoHundred / $total_marks;
	    $int_percentage   = (int) $percentage;
	    
	    $grade_query =  "select grade_id from ".get_school_db().".grade
	                     WHERE mark_from <= $int_percentage and mark_upto >= $int_percentage and school_id = ".$_SESSION['school_id']."";
	    $grade_row   =   $this->db->query($grade_query)->row();
 
	    $data_result['assessment_id']       = $assessment_id;
	    $data_result['student_id']          = $student_id;
	    $data_result['section_id']          = $section_id;
	    $data_result['subject_id']          = $subject_id;
	    $data_result['total_marks']         = $total_marks;
	    $data_result['obtained_marks']      = $total_obtained_marks;
	    $data_result['grade_id']            = $grade_row->grade_id;
        $data_result['remarks']             = $remarks;
	    $this->db->insert(get_school_db().".assessment_result" , $data_result);
	    
        $device_id  =   get_user_device_id(6 , $student_id , $_SESSION['school_id']);
        $title      =   "Assessment Marked";
        $message    =   "An Assessment Has been Marked By The Teacher.";
        $link       =    base_url()."assessment_student/view_assessment";
        sendNotificationByUserId($device_id, $title, $message, $link ,  $student_id , 6);
	    
	    $this->session->set_flashdata('club_updated',get_phrase('assessment_marks_saved_successfully'));
	    redirect(base_url().'assessment/view_assessments_submitted/'.$assessment_id);
	    
	}
	
	function view_assessment_result()
	{
       	if($_SESSION['user_login']!=1)redirect('login' );
		$page_data['page_name']		=	'assessment/view_assessment_result';
		$page_data['page_title']	=	get_phrase('view_assessment_result');
		$this->load->view('backend/index', $page_data); 
    }
    
    function get_subject_assessments()
    {
        
        $teacher_id               =  $_SESSION['user_id'];
        $subject_id               =  intval($this->input->post('subject_id'));
        $assessments_list         =  teacher_subject_assessment_list($teacher_id,$subject_id);
        $list_array               =  array();
        $list_array['assessment'] =  $assessments_list;
        
        echo json_encode($list_array);
    }

    function filter_assessment_results()
    {
        
        if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $teacher_id  = $_SESSION['user_id'];
	    $section_id  = $this->input->post('section_id');
	    $subject_id  = $this->input->post('subject_id');
	    
        $where_filter = "where ass.teacher_id = '".$teacher_id."' ";

        if ($section_id != "")
        {
            $where_filter .= " and aud.section_id = '".$section_id."' ";
        }
        
        if ($subject_id != "")
        {
            $where_filter .= "and aud.subject_id= '".$subject_id."' ";
        }
        
        $where_filter .= "and ass.is_completed =1";
        

        $query  = "select GROUP_CONCAT(DISTINCT ass.assessment_id) as assessment_ids from ".get_school_db().".assessments ass
                   inner join ".get_school_db().".assessment_audience aud on ass.assessment_id = aud.assessment_id ".$where_filter." ";
                   
        $result         = $this->db->query($query)->result_array();
        $assessment_ids =  $result[0]['assessment_ids'];  
        
        $assessments = array();
        if($assessment_ids != "")
        {
            $q           = "select * from ".get_school_db().".assessments ass WHERE ass.school_id=".$_SESSION['school_id']." 
                            and ass.teacher_id=".$_SESSION['user_id']."  and ass.assessment_id in ($assessment_ids) 
                            order by ass.assessment_id desc";
            $assessments =  $this->db->query($q)->result_array();
        }
        
        $page_data['page_name']		  =	 'assessment/view_assessment_result';
		$page_data['page_title']      =	 get_phrase('view_assessment_result');
		$page_data['assessments']     =	 $assessments;
		$page_data['section_id']      =	$section_id;
		$page_data['subject_id']      =	$subject_id;
		$page_data['assessment_id']   =	$assessment_id;

		$this->load->view('backend/index', $page_data); 
        
        
    }
    
	function create_assessment()
	{
	    
		if($_SESSION['user_login']!=1)redirect('login' );
		
		$page_data['page_name']		    =	'assessment/create_assessment';
		$page_data['page_title']		=	get_phrase('create_assessment');
		
		$this->load->view('backend/index', $page_data);
	}
	
	function student_assessment_details($student_id="" , $assessment_id="")
	{
	   	if($_SESSION['user_login']!=1)  redirect('login' );
	   	
		$page_data['page_name']		    =	'assessment/submitted_assessment';
		$page_data['page_title']        =	 get_phrase('assessment_solution');
		$page_data['assessment_id']     =    $assessment_id;
		$page_data['student_id']        =    $student_id;
		
 		$page_data['assessment_result'] =    get_submitted_assessment_result($assessment_id , $student_id);
        $page_data['result_details'] =    get_submitted_assessment_result($assessment_id , $student_id);
		
		$this->load->view('backend/index', $page_data);  
	}
	
	function view_assessments_submitted($assessment_id)
	{
	   	if($_SESSION['user_login']!=1)  redirect('login' );
	   	
		$page_data['page_name']		 =	'assessment/view_submitted_assessments';
		$page_data['page_title']     =	 get_phrase('submitted_assessments');
		$page_data['assessment_id']  =   $assessment_id;
		$page_data['students']       =   get_submitted_assessment_students($assessment_id);
		
		$this->load->view('backend/index', $page_data); 
	}
	
	function assign_assessment($assessment_id)
	{
	    
		if($_SESSION['user_login']!=1)redirect('login' );
		$page_data['page_name']		=	'assessment/assign_assessment';
		$page_data['page_title']    =	get_phrase('assign_assessment');
		$page_data['assessment_id'] = $assessment_id;
		
		$this->load->view('backend/index', $page_data);
	}
	
	function check_assigned_assessment()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    $assessment_id  = $this->input->post('assessment_id');
	    $section_id     = $this->input->post('section_id');
	    
	    $query =  "select audience_id from ".get_school_db().".assessment_audience WHERE assessment_id = $assessment_id and section_id = $section_id";
	    $row   =  $this->db->query($query)->row();
	    
	    if($row != null)
	    {
	         echo 1; 
	    }
	    else
	    {
	        echo 0;
	    }
	    
	}
	
	function view_assessment()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $apply_filter    = $this->input->post('apply_filter');
	    $yearly_term_id  = $this->input->post('yearly_term_id');
	    $section_id      = $this->input->post('section_id');
	    $subject_id      = $this->input->post('subject_id');
	    
	    $search_filter = "";
	    
        if ($yearly_term_id != "")
        {
            $search_filter = "where ass.yearly_term_id = '".$yearly_term_id."' ";
        }
        
        if ($section_id != "")
        {
            $search_filter = " and aud.section_id = '".$section_id."' ";
        }
        
        if ($subject_id != "")
        {
            $search_filter = "and aud.subject_id= '".$subject_id."' ";
        }
        
        
        if ($apply_filter == "")
        {
              $q = "select * from ".get_school_db().".assessments ass WHERE ass.school_id=".$_SESSION['school_id']." 
	                and ass.teacher_id=".$_SESSION['user_id']."  order by ass.assessment_id desc";
	          $assessments = $this->db->query($q)->result_array();
            
        }
        else
        {
                $q1 = "select GROUP_CONCAT(DISTINCT ass.assessment_id) as assessment_ids from ".get_school_db().".assessments ass
                       inner join ".get_school_db().".assessment_audience aud on ass.assessment_id = aud.assessment_id ".$search_filter." ";
                $result = $this->db->query($q1)->result_array();
                $assessment_ids =  $result[0]['assessment_ids'];
                
                if($assessment_ids != "")
                {
                    $q = "select * from ".get_school_db().".assessments ass WHERE ass.school_id=".$_SESSION['school_id']." 
                    and ass.teacher_id=".$_SESSION['user_id']."  and ass.assessment_id in ($assessment_ids) 
                    order by ass.assessment_id desc";
                    $assessments = $this->db->query($q)->result_array();
                }
                else
                {
                    $assessments = array();
                      
                    
                }
        }
        
	    $page_data['assessments']		=	$assessments;
	    $page_data['apply_filter']		=	$apply_filter;
	    $page_data['yearly_term_id']	=	$yearly_term_id;
	    $page_data['section_id']		=	$section_id;
	    $page_data['subject_id']		=	$subject_id;
	   
		$page_data['page_name']		    =	'assessment/view_assessment';
		$page_data['page_title']		=	get_phrase('view_assessment');
		
		$this->load->view('backend/index', $page_data);
	}
	
	function delete_assessment($assessment_id)
	{
	    $this->db->where('assessment_id', $assessment_id);
        $this->db->delete(get_school_db().".assessments");
        $this->session->set_flashdata('club_updated', get_phrase('assessment_deleted_successfully'));
	    
	    redirect(base_url().'assessment/view_assessment');
	}
	
	function view_assessment_form($assessment_id)
	{
	  
	    if($_SESSION['user_login']!=1) redirect('login' );
	    
        $q = "SELECT ass_que.* , que_typ.* from ".get_school_db().".assessment_questions ass_que
	    inner join ".get_school_db().".question_types que_typ on que_typ.question_type_id = ass_que.question_type_id
	    where ass_que.assessment_id = ".$assessment_id."";
	    
	    $assessments                    =    $this->db->query($q)->result_array();
	    $page_data['assessments']		=	 $assessments;
	   
	   
		$page_data['page_name']		    =	'assessment/view_assessment_readonly_form';
		$page_data['page_title']		=	 get_phrase('view_assessment_details');
		$this->load->view('backend/index', $page_data);
	}
	
	function assessment_details($assessment_id)
	{
	    if($_SESSION['user_login']!=1)redirect('login');
	    
	    $q                  = "select * from ".get_school_db().".assessments WHERE assessment_id = ".$assessment_id." and school_id=".$_SESSION['school_id']."";
	    $assessment_details = $this->db->query($q)->result_array();
	    
	    $page_data['assessment_details']  =	$assessment_details;
		$page_data['page_name']		      =	'assessment/assessment_details';
		$page_data['page_title']		  =	get_phrase('assessment_details');
		$page_data['assessment_id']       = $assessment_id;
		$this->load->view('backend/index', $page_data);
	}
	
	function save_assessment($assessment_type = 0)
	{
	    $data['assessment_title']              =   $this->input->post('assessment_title');
	   // $data['system_class']                  =   $this->input->post('class_id');
	   // $data['system_subject']                =   $this->input->post('subject_id');
	    $data['yearly_term_id']                =   $this->input->post('yearly_term_id');
	    $data['total_marks']                   =   $this->input->post('total_marks');
	    $data['mcq_questions']                 =   $this->input->post('mcq_questions');
	    $data['true_false_questions']          =   $this->input->post('true_false_questions');
	    $data['fill_in_the_blanks_questions']  =   $this->input->post('fill_in_the_blanks_questions');
	    $data['short_questions']               =   $this->input->post('short_questions');
	    $data['long_questions']                =   $this->input->post('long_questions');
	    $data['pictorial_questions']           =   $this->input->post('pictorial_questions');
	    $data['match_questions']               =   $this->input->post('match_questions');
	    $data['drawing_questions']             =   $this->input->post('drawing_questions');
	    $data['total_attempts']                =   $this->input->post('total_attempts');
	    $data['remarks']                       =   $this->input->post('remarks');
	    $data['assessment_type']               =   1;
	    $data['teacher_id']                    =   $_SESSION['user_id'];
	    $data['school_id']                     =   $_SESSION['school_id'];
	    
	    $this->db->insert(get_school_db().".assessments" , $data);
	    $this->session->set_flashdata('club_updated', get_phrase('assessment_saved_successfully'));
	    
	    redirect(base_url().'assessment/view_assessment');
	}
	
	function update_assessment_details()
	{
	    
	    $assessment_id  = $this->input->post('assessment_id');
	    $question_count = $this->input->post('question_count');

	    for($index = 1; $index <= $question_count;  $index++){
	        
	        $question_statement_name_attr          = 'question_statement_'.$index;
	        $question_marks_name_attr              = 'question_marks_'.$index;
	        $question_correct_answer_name_attr     = 'question_correct_'.$index;
	        $question_type_name_attr               = 'question_type_'.$index;
	        $question_id_name_attr                 = 'question_id_'.$index;
	        $required_lines_attr                   = 'required_lines_'.$index;
	        
	        $question_statement                    =  $this->input->post($question_statement_name_attr);
	        $question_marks                        =  $this->input->post($question_marks_name_attr);
	        $question_correct_answer               =  $this->input->post($question_correct_answer_name_attr);
	        $question_type                         =  $this->input->post($question_type_name_attr);
	        $question_id                           =  $this->input->post($question_id_name_attr);
	        $required_lines                         = $this->input->post($required_lines_attr);
	        
	        if($question_type == 1){
	            
	            $question_option_name_attr                 = 'question_option_'.$index;
	            
        	    $data_question['assessment_id']            = $assessment_id;
        	    $data_question['question_text']            = $question_statement;
        	    $data_question['question_total_marks']     = $question_marks;
        	    $data_question['right_answer_key']         = $question_correct_answer;
                
                //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question);
        	    
        	    //delete old options
        	    $this->db->where('question_id', $question_id);
                $this->db->delete(get_school_db().".question_options");
        	    
        	    $option_list  = $this->input->post($question_option_name_attr);
        	    
        	    $data_options = array();
        	    
        	    for($opt = 0; $opt < count($option_list); $opt++){
        	        if(!empty($option_list[$opt])){
                        $data_option = array(
                            'question_id'    => $question_id , 
                            'option_number'  => $opt + 1,
                            'option_text'    => $option_list[$opt]
                        );
                        array_push($data_options,$data_option);
        	        }
                }
                
                //store new options
                $this->db->insert_batch(get_school_db().".question_options" , $data_options);
  
	        }
	        
	        if($question_type == 4  || $question_type == 5 || $question_type == 8){

        	    $data_question_1['question_text']            = $question_statement;
        	    $data_question_1['question_total_marks']     = $question_marks;
        	    $data_question_1['required_lines']           = $required_lines;
        	    
        	    //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question_1);

	        } 
	        
	        if($question_type == 2  || $question_type == 3){
	            
        	    $data_question_2['question_text']            = $question_statement;
        	    $data_question_2['question_total_marks']     = $question_marks;
        	    $data_question_2['right_answer_key']         = $question_correct_answer;

        	    //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question_2);
	            
	        }
	        
	        if($question_type == 6){
	            
	            $image_attachment_name_attr                  = 'question_attachment_'.$index;
	            
        	    $data_question_3['question_text']            = $question_statement;
        	    $data_question_3['question_total_marks']     = $question_marks;
        	    $data_question_3['right_answer_key']         = $question_correct_answer;
        	    $data_question_3['required_lines']           = $required_lines;
        	    
        	    if ($_FILES[$image_attachment_name_attr]['name'] != "") 
        	    {
        	        $image_url                     = file_upload_fun($image_attachment_name_attr , 'pictorial_question', '');
        	        $data_question_3['image_url']  = $image_url;
        	    }
        	    
        	    //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question_3);
 
	        }
	        
	       
	        if($question_type == 7){
	            
	            $match_question_left_option_name_attr    = 'left_option_'.$index;
        	    $match_question_right_option_name_attr   = 'right_option_'.$index;
        	    $match_question_right_answer_name_attr   = 'right_answer_'.$index;
        	    $match_question_answer_marks_name_attr   = 'marks_'.$index;
	            
        	    $data_question['question_text']          = $question_statement;
        	    
        	    //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question);
 
        	    $left_text    = $this->input->post($match_question_left_option_name_attr);
        	    $right_text   = $this->input->post($match_question_right_option_name_attr);
        	    $right_answer = $this->input->post($match_question_right_answer_name_attr);
        	    $option_marks = $this->input->post($match_question_answer_marks_name_attr);
        	    $data_options = array();
        	    
        	    $question_total_marks = 0;
        	    
        	    for($opt = 0; $opt < count($left_text); $opt++){
        	        if(!empty($left_text[$opt])){
                        $data_option = array(
                            'question_id'       => $question_id , 
                            'option_number'     => $opt + 1,
                            'left_side_text'    => $left_text[$opt] ,
                            'right_side_text'   => $right_text[$opt],
                            'right_answer'      => $right_answer[$opt],
                            'option_marks'      => $option_marks[$opt],
                        );
                        $question_total_marks += $option_marks[$opt];
                        array_push($data_options,$data_option);
        	        }
                }
                
                //delete old options
        	    $this->db->where('question_id', $question_id);
                $this->db->delete(get_school_db().".matching_question_option");
                
                //insert new options
                $this->db->insert_batch(get_school_db().".matching_question_option" , $data_options);
                
                $data_update_marks['question_total_marks'] = $question_total_marks;
	            $this->db->where('question_id',$question_id);
                $this->db->update(get_school_db().'.assessment_questions',$data_update_marks);
  
	        }
	        
  
	    }
	    
	    $data_update['is_completed'] = 1;
	    $this->db->where('assessment_id', $assessment_id);
        $this->db->update(get_school_db().'.assessments', $data_update);
	    
	    $this->session->set_flashdata('club_updated', get_phrase('assessment_details_are_updated_successfully'));
	    redirect(base_url().'assessment/view_assessment');
	    
	}
	
	function save_assessment_details()
	{
        $this->db->trans_begin();
	    $assessment_id  = $this->input->post('assessment_id');
	    $question_count = $this->input->post('question_count');
	    
	    for($index = 1; $index <= $question_count;  $index++){
	        
	        $question_statement_name_attr          = 'question_statement_'.$index;
	        $question_marks_name_attr              = 'question_marks_'.$index;
	        $question_correct_answer_name_attr     = 'question_correct_'.$index;
	        $question_type_name_attr               = 'question_type_'.$index;
	        $required_lines_attr                   = 'required_lines_'.$index;
	        
	        $question_statement                    = $this->input->post($question_statement_name_attr);
	        $question_marks                        = $this->input->post($question_marks_name_attr);
	        $question_correct_answer               = $this->input->post($question_correct_answer_name_attr);
	        $question_type                         = $this->input->post($question_type_name_attr);
	        $required_lines                         = $this->input->post($required_lines_attr);
	        
	        
	        if($question_type == 1){
	            
	            $question_option_name_attr                 = 'question_option_'.$index;
	            
        	    $data_question['question_type_id']         = $question_type;
        	    $data_question['assessment_id']            = $assessment_id;
        	    $data_question['question_text']            = $question_statement;
        	    $data_question['question_total_marks']     = $question_marks;
        	    $data_question['right_answer_key']         = $question_correct_answer;
                
        	    $this->db->insert(get_school_db().".assessment_questions" , $data_question);
        	    $question_id = $this->db->insert_id();
        	    
        	    $option_list  = $this->input->post($question_option_name_attr);
        	    
        	    $data_options = array();
        	    
        	    for($opt = 0; $opt < count($option_list); $opt++){
        	        if(!empty($option_list[$opt])){
                        $data_option = array(
                            'question_id'    => $question_id , 
                            'option_number'  => $opt + 1,
                            'option_text'    => $option_list[$opt]
                        );
                        array_push($data_options,$data_option);
        	        }
                }
                
                $this->db->insert_batch(get_school_db().".question_options" , $data_options);
  
	        }
	        
	        if($question_type == 4  || $question_type == 5 || $question_type == 8){
	            
	            $data_question_1['question_type_id']         = $question_type;
        	    $data_question_1['assessment_id']            = $assessment_id;
        	    $data_question_1['question_text']            = $question_statement;
        	    $data_question_1['question_total_marks']     = $question_marks;
        	    $data_question_1['required_lines']           = $required_lines;

        	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_1);
	            
	        } 
	        
	        if($question_type == 2  || $question_type == 3){
	            
	            $data_question_2['question_type_id']         = $question_type;
        	    $data_question_2['assessment_id']            = $assessment_id;
        	    $data_question_2['question_text']            = $question_statement;
        	    $data_question_2['question_total_marks']     = $question_marks;
        	    $data_question_2['right_answer_key']         = $question_correct_answer;
 
        	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_2);
	            
	        }
	        
	        if($question_type == 6){
	            
	            $image_attachment_name_attr                  = 'question_attachment_'.$index;
	            
	            $data_question_3['question_type_id']         = $question_type;
        	    $data_question_3['assessment_id']            = $assessment_id;
        	    $data_question_3['question_text']            = $question_statement;
        	    $data_question_3['question_total_marks']     = $question_marks;
        	    $data_question_3['right_answer_key']         = $question_correct_answer;
        	    $data_question_3['required_lines']           = $required_lines;
        	    
        	    $image_url   = file_upload_fun($image_attachment_name_attr , 'pictorial_question', '');
        	    $data_question_3['image_url']                = $image_url;
 
        	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_3);
	            
	        }
	        
	        if($question_type == 7){
	            
	            $match_question_left_option_name_attr    = 'left_option_'.$index;
        	    $match_question_right_option_name_attr   = 'right_option_'.$index;
        	    $match_question_right_answer_name_attr   = 'right_answer_'.$index;
        	    $match_question_answer_marks_name_attr   = 'marks_'.$index;
	            
        	    $data_question['question_type_id']       = $question_type;
        	    $data_question['assessment_id']          = $assessment_id;
        	    $data_question['question_text']          = $question_statement;
 
        	    $this->db->insert(get_school_db().".assessment_questions" , $data_question);
        	    
        	    $question_id  = $this->db->insert_id();
        	    $left_text    = $this->input->post($match_question_left_option_name_attr);
        	    $right_text   = $this->input->post($match_question_right_option_name_attr);
        	    $right_answer = $this->input->post($match_question_right_answer_name_attr);
        	    $option_marks = $this->input->post($match_question_answer_marks_name_attr);
        	    $data_options = array();
        	    
        	    $question_total_marks = 0;
        	    
        	    for($opt = 0; $opt < count($left_text); $opt++){
        	        if(!empty($left_text[$opt])){
                        $data_option = array(
                            'question_id'       => $question_id , 
                            'option_number'     => $opt + 1,
                            'left_side_text'    => $left_text[$opt] ,
                            'right_side_text'   => $right_text[$opt],
                            'right_answer'      => $right_answer[$opt],
                            'option_marks'      => $option_marks[$opt],
                        );
                        $question_total_marks += $option_marks[$opt];
                        array_push($data_options,$data_option);
        	        }
                }
                
                $this->db->insert_batch(get_school_db().".matching_question_option" , $data_options);
                
                $data_update_marks['question_total_marks'] = $question_total_marks;
	            $this->db->where('question_id',$question_id);
                $this->db->update(get_school_db().'.assessment_questions',$data_update_marks);
  
	            
	        }
	        
	    }
	    
	    $data_update['is_completed'] = 1;
	    $this->db->where('assessment_id',$assessment_id);
        $this->db->update(get_school_db().'.assessments',$data_update);
	    
	    $this->session->set_flashdata('club_updated',get_phrase('assessment_details_are_saved_successfully'));
	    $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
	    redirect(base_url().'assessment/view_assessment');
	    
	}
	
	function view_assessment_to_parent()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $q = "select ass.* ,ass_aud.* , s.name as teacher_name , stud.name as student_name from ".get_school_db().".assessments ass
	          inner join ".get_school_db().".assessment_audience ass_aud on ass.assessment_id = ass_aud.assessment_id
	          inner join ".get_school_db().".staff s on ass.teacher_id = s.staff_id
	          inner join ".get_school_db().".student stud on ass_aud.student_id = stud.student_id
	          WHERE ass_aud.student_id = ".$_SESSION['student_id']." and ass_aud.is_submitted = 0 and ass.school_id = ".$_SESSION['school_id']." order by ass.assessment_id DESC";
	    $assessments = $this->db->query($q)->result_array();
	    
	    $page_data['assessments']		=	$assessments;
		$page_data['page_name']		    =	'assessment/view_assessment';
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
	
	function save_assign_assessment()
	{
	    $assessment_id   = $this->input->post('assessment_id');
	    $assessment_date = date("Y-m-d H:i:s",strtotime($this->input->post('assessment_date')));
	    $start_time      = $this->input->post('start_time');
	    $end_time        = $this->input->post('end_time');
	    
	    //------------------ insert into assessment_audience---------------
	    //-----------------------------------------------------------------
	    $section_id      = $this->input->post('section_id');
	    $subject_id      = $this->input->post('subject_id');
	    $student_id      = $this->input->post('student_id');
	    
        $data_audience = array();
	    foreach($student_id as $student)
	    {

	       $data_audience_row = array(
                'assessment_id'   => $assessment_id , 
                'section_id'      => $section_id,
                'subject_id'      => $subject_id,
                'student_id'      => $student,
                'assessment_date' => $assessment_date,
                'start_time'      => $start_time,
                'end_time'        => $end_time
            );
            array_push($data_audience,$data_audience_row);
  
            $device_id  =   get_user_device_id(6 , $student , $_SESSION['school_id']);
            $title      =   "New Assessment";
            $message    =   "A New Assessment Has been Created By Teacher.";
            $link       =    base_url()."assessment_student/view_assessment";
            sendNotificationByUserId($device_id, $title, $message, $link , $student , 6);
	        
	    }
	    $this->db->insert_batch(get_school_db().".assessment_audience" , $data_audience);

	    $data_ass['is_assigned'] = 1;
	    $this->db->where('assessment_id', $assessment_id); 
        $this->db->update(get_school_db().'.assessments',$data_ass);

	    $send_message =  $this->input->post('send_message');
	    $send_email   =  $this->input->post('send_email');
	    
	    $this->load->helper('message');
             
        if(isset($send_message) && $send_message!="")
        {
           
            foreach($student_id as $row)
            {
                $student_info  =  get_student_info($row);
                $student_name  =  $student_info[0]['student_name'];
                $mob_num       =  $student_info[0]['mob_num'];
                $email         =  $student_info[0]['email'];
                $student_id    =  $student_info[0]['student_id'];
                $message       =  "A New Assessment has been assigned to ".$student_name." on ".$assessment_date." from ".$start_time." to ".$end_time." "; 
                $response      =  send_sms($mob_num, 'Indici Edu', $message, $student_id,2);
            }
            
        }
	    redirect(base_url() . 'assessment/view_assessment');
	}
	
	function assessment_result()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $q = "select ass.* , r.* , r.remarks as result_remarks ,stf.name as teacher_name , s.name as student_name ,
	    sub.name as subject_name ,sub.code as subject_code from ".get_school_db().".assessments ass
	    inner join ".get_school_db().".assessment_result r on ass.assessment_id = r.assessment_id
	    inner join ".get_school_db().".staff stf on ass.teacher_id = stf.staff_id
	    inner join ".get_school_db().".student s on s.student_id = r.student_id
	    inner join ".get_school_db().".subject sub on sub.subject_id = r.subject_id
	    WHERE r.student_id = ".$_SESSION['student_id']." and ass.school_id = ".$_SESSION['school_id']."";
	  
	    
	    $result_arr                     =   $this->db->query($q)->result_array();
	    $page_data['result_arr']		=	$result_arr;
		$page_data['page_name']		    =	'assessment/assessment_result';
		$page_data['page_title']		=	get_phrase('assessment_result');
		$this->load->view('backend/index',  $page_data);
	}
	
	
	function savie_assesment()
	{

	    $assessment_id  = $this->input->post('assessment_id');
	    $question_count = $this->input->post('question_count');
	    
	    for($index = 1; $index <= $question_count;  $index++){
	        
	        $question_statement_name_attr          = 'question_statement_'.$index;
	        $question_marks_name_attr              = 'question_marks_'.$index;
	        $question_correct_answer_name_attr     = 'question_correct_'.$index;
	        $question_type_name_attr               = 'question_type_'.$index;
	        $question_id_name_attr                 = 'question_id_'.$index;
	        
	        $question_statement                    =  $this->input->post($question_statement_name_attr);
	        $question_marks                        =  $this->input->post($question_marks_name_attr);
	        $question_correct_answer               =  $this->input->post($question_correct_answer_name_attr);
	        $question_type                         =  $this->input->post($question_type_name_attr);
	        $question_id                           =  $this->input->post($question_id_name_attr);
	        
	        if($question_type == 1){
	            
	            $question_option_name_attr                 = 'question_option_'.$index;
	            
        	    $data_question['assessment_id']            = $assessment_id;
        	    $data_question['question_text']            = $question_statement;
        	    $data_question['question_total_marks']     = $question_marks;
        	    $data_question['right_answer_key']         = $question_correct_answer;
                
                //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question);
        	    
        	    //delete old options
        	    $this->db->where('question_id', $question_id);
                $this->db->delete(get_school_db().".question_options");
        	    
        	    $option_list  = $this->input->post($question_option_name_attr);
        	    
        	    $data_options = array();
        	    
        	    for($opt = 0; $opt < count($option_list); $opt++){
        	        if(!empty($option_list[$opt])){
                        $data_option = array(
                            'question_id'    => $question_id , 
                            'option_number'  => $opt + 1,
                            'option_text'    => $option_list[$opt]
                        );
                        array_push($data_options,$data_option);
        	        }
                }
                
                //store new options
                $this->db->insert_batch(get_school_db().".question_options" , $data_options);
  
	        }
	        
	        if($question_type == 4  || $question_type == 5 || $question_type == 8){
	            
        	    $data_question_1['question_text']            = $question_statement;
        	    $data_question_1['question_total_marks']     = $question_marks;
        	    
        	    //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question_1);

	        } 
	        
	        if($question_type == 2  || $question_type == 3){
	            
        	    $data_question_2['question_text']            = $question_statement;
        	    $data_question_2['question_total_marks']     = $question_marks;
        	    $data_question_2['right_answer_key']         = $question_correct_answer;

        	    //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question_2);
	            
	        }
	        
	        if($question_type == 6){
	            
	            $image_attachment_name_attr                  = 'question_attachment_'.$index;
	            
        	    $data_question_3['question_text']            = $question_statement;
        	    $data_question_3['question_total_marks']     = $question_marks;
        	    $data_question_3['right_answer_key']         = $question_correct_answer;
        	    
        	    $image_url                                   = file_upload_fun($image_attachment_name_attr , 'pictorial_question', '');
        	    $data_question_3['image_url']                = $image_url;
        	    
        	    //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question_3);
 
	        }
	        
	        /*
	        if($question_type == 7){
	            
	            $match_question_left_option_name_attr    = 'left_option_'.$index;
        	    $match_question_right_option_name_attr   = 'right_option_'.$index;
        	    $match_question_right_answer_name_attr   = 'right_answer_'.$index;
        	    $match_question_answer_marks_name_attr   = 'marks_'.$index;
	            
        	    $data_question['question_text']          = $question_statement;
        	    
        	    //update question basic details
                $this->db->where('question_id' , $question_id);
        	    $this->db->update(get_school_db().".assessment_questions" , $data_question);
 
        	    $left_text    = $this->input->post($match_question_left_option_name_attr);
        	    $right_text   = $this->input->post($match_question_right_option_name_attr);
        	    $right_answer = $this->input->post($match_question_right_answer_name_attr);
        	    $option_marks = $this->input->post($match_question_answer_marks_name_attr);
        	    $data_options = array();
        	    
        	    $question_total_marks = 0;
        	    
        	    for($opt = 0; $opt < count($left_text); $opt++){
        	        if(!empty($left_text[$opt])){
                        $data_option = array(
                            'question_id'       => $question_id , 
                            'option_number'     => $opt + 1,
                            'left_side_text'    => $left_text[$opt] ,
                            'right_side_text'   => $right_text[$opt],
                            'right_answer'      => $right_answer[$opt],
                            'option_marks'      => $option_marks[$opt],
                        );
                        $question_total_marks += $option_marks[$opt];
                        array_push($data_options,$data_option);
        	        }
                }
                
                //delete old options
        	    $this->db->where('question_id', $question_id);
                $this->db->delete(get_school_db().".matching_question_option");
                
                //insert new options
                $this->db->insert_batch(get_school_db().".matching_question_option" , $data_options);
                
                $data_update_marks['question_total_marks'] = $question_total_marks;
	            $this->db->where('question_id',$question_id);
                $this->db->update(get_school_db().'.assessment_questions',$data_update_marks);
  
	        }
	        */
	        
	    }
	    
	    $data_update['is_completed'] = 1;
	    $this->db->where('assessment_id', $assessment_id);
        $this->db->update(get_school_db().'.assessments', $data_update);
	    
	    $this->session->set_flashdata('club_updated', get_phrase('assessment_details_are_updated_successfully'));
	    redirect(base_url().'assessment/view_assessment');
	    
	}
	
	function result_details($assessment_id)
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $q = "select sol.* , q.question_type_id ,q.question_text ,q.question_total_marks ,q.right_answer_key 
	    from ".get_school_db().".assessment_solution sol 
	    inner join ".get_school_db().".assessment_questions q on sol.question_id = q.question_id
	    WHERE sol.assessment_id = ".$assessment_id." and sol.student_id = ".$_SESSION['student_id']." ORDER BY q.question_type_id ASC ";
	    
	    $result_details         = $this->db->query($q)->result_array();

	    $page_data['result_details']		=	$result_details;
		$page_data['page_name']		        =	'assessment/result_details';
		$page_data['page_title']		    =	get_phrase('result_details');
		$this->load->view('backend/index', $page_data);
	}
	
	
	
	
}
?>