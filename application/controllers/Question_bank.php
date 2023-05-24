<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Question_bank extends CI_Controller
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
    
    function create_question_bank()
	{   
		if($_SESSION['user_login']!=1)redirect('login' );
		$page_data['page_name']		=	'qb_assessment/qb_assessment_create';
		$page_data['page_title']	=	get_phrase('qb_assessment_create');
		$this->load->view('backend/index', $page_data);
	}
	
	function save_assessment()
	{
	    $data['assessment_title']              = $this->input->post('assessment_title');
	    $data['system_class']                  = $this->input->post('class_id');
	    $data['system_subject']                = $this->input->post('subject_id');
	    $data['system_chapter']                = $this->input->post('chapter_id');
	    $data['yearly_term_id']                = $this->input->post('yearly_term_id');
	    $data['total_marks']                   = $this->input->post('total_marks');
	   // $data['assessment_date']             = $this->input->post('assessment_date');
	    $data['mcq_questions']                 = $this->input->post('mcq_questions');
	    $data['true_false_questions']          = $this->input->post('true_false_questions');
	    $data['fill_in_the_blanks_questions']  = $this->input->post('fill_in_the_blanks_questions');
	    $data['short_questions']               = $this->input->post('short_questions');
	    $data['long_questions']                = $this->input->post('long_questions');
	    $data['pictorial_questions']           = $this->input->post('pictorial_questions');
	    $data['match_questions']               = $this->input->post('match_questions');
	    $data['drawing_questions']             = $this->input->post('drawing_questions');
	    $data['remarks']                       = $this->input->post('remarks');
	    $data['assessment_type']          = 2;
	    $data['teacher_id'] = $_SESSION['user_id'];
	    $data['school_id'] = $_SESSION['school_id'];
	   
	    $this->db->insert(get_school_db().".assessments" , $data);
	    redirect(base_url().'assessment/view_assessment');
	    //redirect(base_url().'question_bank/qb_view_assessment');
	}
	
	function qb_view_assessment()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $apply_filter    = $this->input->post('apply_filter');
	    $yearly_term_id  = $this->input->post('yearly_term_id');
	    $section_id      = $this->input->post('section_id');
	    $subject_id      = $this->input->post('subject_id');
	    
	    $search_filter=" where ass.assessment_type = 2 ";
        if ($yearly_term_id != "")
        {
            $search_filter .= " and ass.yearly_term_id = '".$yearly_term_id."' ";
        }
        
        if ($section_id != "")
        {
            $search_filter .= " and aud.section_id = '".$section_id."' ";
        }
        
        if ($subject_id != "")
        {
            $search_filter .= " and aud.subject_id= '".$subject_id."' ";
        }
        
        
        if ($apply_filter == "")
        {
              $q = "select * from ".get_school_db().".assessments ass WHERE ass.school_id=".$_SESSION['school_id']." 
	                and ass.teacher_id=".$_SESSION['user_id']." and ass.assessment_type = 2  order by ass.assessment_id desc";
	          $assessments = $this->db->query($q)->result_array();
            
        }
        else
        {
            $q1 = "select GROUP_CONCAT(DISTINCT ass.assessment_id) as assessment_ids from ".get_school_db().".assessments ass
                   left join ".get_school_db().".assessment_audience aud on ass.assessment_id = aud.assessment_id ".$search_filter." ";
                   
            
            $result = $this->db->query($q1)->result_array();
            $assessment_ids =  $result[0]['assessment_ids'];
            
            if($assessment_ids != ""){
                $q = "select * from ".get_school_db().".assessments ass WHERE ass.school_id=".$_SESSION['school_id']." 
                and ass.teacher_id=".$_SESSION['user_id']."  and ass.assessment_id in ($assessment_ids) 
                order by ass.assessment_id desc";
                $assessments = $this->db->query($q)->result_array();
            }
            else{
                $assessments = array();
            }
        }
        
	    $page_data['assessments']		=	$assessments;
	    $page_data['apply_filter']		=	$apply_filter;
	    $page_data['yearly_term_id']	=	$yearly_term_id;
	    $page_data['section_id']		=	$section_id;
	    $page_data['subject_id']		=	$subject_id;
	    $page_data['page_name']		    =	'assessment/view_assessment';
		//$page_data['page_name']		=	'qb_assessment/qb_view_assessment'; 
		$page_data['page_title']		=	get_phrase('qb_view_assessment');
		$this->load->view('backend/index', $page_data);
	}
	
	function assessment_details($assessment_id)
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $q = "select * from ".get_school_db().".assessments WHERE assessment_id = ".$assessment_id." and school_id=".$_SESSION['school_id']."";
	    $assessment_details = $this->db->query($q)->result_array();
	    
	    $page_data['assessment_details']  =	$assessment_details;
		$page_data['page_name']		      =	'qb_assessment/qb_assessment_details';
		$page_data['page_title']		  =	get_phrase('qb_assessment_details');
		$page_data['assessment_id']       = $assessment_id;
		$this->load->view('backend/index', $page_data);
	}
	
	
	
	
	function save_assessment_details()
	{

	    $assessment_id  = $this->input->post('assessment_id');
	    $question_count = $this->input->post('question_count');
	    
	    $assessment_row =  get_assessment_row($assessment_id);
	    
	    for($index = 1; $index <= $question_count;  $index++){
	        
	        $question_type_name_attr           = 'question_type_'.$index;
	        $question_selection_type_attr      = 'question_selection_type_'.$index;  // check what type of question is it bank or manual
	        
	        $question_type                     = $this->input->post($question_type_name_attr);
	        $question_selection_type           = $this->input->post($question_selection_type_attr);  // question type value
	        
	        if($question_selection_type == 1)
	        {
	            
        	    /*
                    1 MCQ Questions
                    2 True False Questions
                    3 Fill In The Blanks Questions
                    4 Short Questions
                    5 Long Questions
                    6 Pictorial Questions
                    7 Matching Questions
                    8 Drawing Questions
                */
                
                /*
            	    question_type_id
            	    subject_id
            	    class_id
            	    question_text
            	    right_answer_key
            	    image_url
            	*/
	            
	            $question_statement_name_attr          = 'question_statement_'.$index;
	            $question_marks_name_attr              = 'question_marks_'.$index;
	            $question_correct_answer_name_attr     = 'question_correct_'.$index;
	            
	            $question_statement                    = $this->input->post($question_statement_name_attr);
	            $question_marks                        = $this->input->post($question_marks_name_attr);
	            $question_correct_answer               = $this->input->post($question_correct_answer_name_attr);
	             
	            if($question_type == 1){
	            
    	            $question_option_name_attr                 = 'question_option_'.$index;
    	            
            	    $data_question['question_type_id']         = $question_type;
            	    $data_question['assessment_id']            = $assessment_id;
            	    $data_question['question_text']            = $question_statement;
            	    $data_question['question_total_marks']     = $question_marks;
            	    $data_question['right_answer_key']         = $question_correct_answer;
                    
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question);
            	    $question_id = $this->db->insert_id();
            	    
            	    $data_question_sys                          = array();
            	    $data_question_sys['question_type_id']      = $question_type;
    	            $data_question_sys['subject_id']            = $assessment_row->system_subject;
    	            $data_question_sys['class_id']              = $assessment_row->system_class;
            	    $data_question_sys['question_text']         = $question_statement;
            	    $data_question_sys['right_answer_key']      = $question_correct_answer;
    
            	    $this->db->insert(get_system_db().".qb_assessment_questions" , $data_question_sys);
            	    $system_question_id = $this->db->insert_id();
            	    
        	    
        	        $option_list = $this->input->post($question_option_name_attr);
        	    
            	    $data_options = array();
            	    $data_options_system = array();
            	    
            	    for($opt = 0; $opt < count($option_list); $opt++){
            	        if(!empty($option_list[$opt])){
            	            
                            $data_option = array(
                                'question_id'    => $question_id , 
                                'option_number'  => $opt + 1,
                                'option_text'    => $option_list[$opt]
                            );
                            array_push($data_options,$data_option);
                            
                            $data_option_system = array(
                                'question_id'    => $system_question_id, 
                                'option_number'  => $opt + 1,
                                'option_text'    => $option_list[$opt]
                            );
                            array_push($data_options_system,$data_option_system);
                            
            	        }
                    }
                
                    $this->db->insert_batch(get_school_db().".question_options" , $data_options);
            	    $this->db->insert_batch(get_system_db().".qb_question_options" , $data_options_system);
	            }
	        
    	        if($question_type == 4  || $question_type == 5 || $question_type == 8){
    	            
    	            $data_question_1['question_type_id']         = $question_type;
            	    $data_question_1['assessment_id']            = $assessment_id;
            	    $data_question_1['question_text']            = $question_statement;
            	    $data_question_1['question_total_marks']     = $question_marks;
    
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_1);
            	    
            	    $data_question_sys_1                             = array();
    	            $data_question_sys_1['question_type_id']         = $question_type;
    	            $data_question_sys_1['subject_id']               = $assessment_row->system_subject;
    	            $data_question_sys_1['class_id']                 = $assessment_row->system_class;
            	    $data_question_sys_1['question_text']            = $question_statement;
    
            	    $this->db->insert(get_system_db().".qb_assessment_questions" , $data_question_sys_1);
  
    	        } 
	        
    	        if($question_type == 2  || $question_type == 3){

    	            $data_question_2['question_type_id']         = $question_type;
            	    $data_question_2['assessment_id']            = $assessment_id;
            	    $data_question_2['question_text']            = $question_statement;
            	    $data_question_2['question_total_marks']     = $question_marks;
            	    $data_question_2['right_answer_key']         = $question_correct_answer;
     
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_2);
            	    
            	    $data_question_sys_2                             = array();
            	    $data_question_sys_2['question_type_id']         = $question_type;
    	            $data_question_sys_2['subject_id']               = $assessment_row->system_subject;
    	            $data_question_sys_2['class_id']                 = $assessment_row->system_class;
            	    $data_question_sys_2['question_text']            = $question_statement;
            	    $data_question_sys_2['right_answer_key']         = $question_correct_answer;
    
            	    $this->db->insert(get_system_db().".qb_assessment_questions" , $data_question_sys_2);
    	            
    	        }
	        
    	        if($question_type == 6){
    	            
    	            $image_attachment_name_attr                  = 'question_attachment_'.$index;
    	            
    	            $data_question_3['question_type_id']         = $question_type;
            	    $data_question_3['assessment_id']            = $assessment_id;
            	    $data_question_3['question_text']            = $question_statement;
            	    $data_question_3['question_total_marks']     = $question_marks;
            	    $data_question_3['right_answer_key']         = $question_correct_answer;
            	    
            	    
            	    $file_name        = $image_attachment_name_attr;
            	    $folder_name      = 'pictorial_question';
            	    $prefix           = "";
            	    $is_root          = 0;
            	    $full_image_path  = "";
            	    

                    $path = 'uploads/' . $_SESSION['folder_name'] . '/' . $folder_name;
                    
                    if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
                        $image_url   = "";
                    } 
                    elseif ($file_name == "") {
                        $image_url   = "";
                    } 
                    elseif ($folder_name == "") {
                        $path = 'uploads/' . $_SESSION['folder_name'];
                    } 
                    elseif ($is_root == 1) {
                        $path = 'uploads/' . $folder_name;
                    }
                    
                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
                    
                    if ($_FILES[$file_name]['name'] != "") {
                        
                        $filename = $_FILES[$file_name]['name'];
                        $ext      = pathinfo($filename, PATHINFO_EXTENSION);
                        $new_file = $prefix . '_' . time() . '.' . $ext;
                        move_uploaded_file($_FILES[$file_name]['tmp_name'], $path . '/' . $new_file);
                        $image_url   = $new_file;
                        $full_image_path = base_url().$path . '/' . $new_file;
                        
                    } 
                    else {
                        $image_url   = "";
                    }
                    

            	    $data_question_3['image_url']  = $image_url;
     
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_3);
            	    
                    $data_question_sys_3                          = array();
            	    $data_question_sys_3['question_type_id']      = $question_type;
    	            $data_question_sys_3['subject_id']            = $assessment_row->system_subject;
    	            $data_question_sys_3['class_id']              = $assessment_row->system_class;
            	    $data_question_sys_3['question_text']         = $question_statement;
            	    $data_question_sys_3['image_url']             = $full_image_path;

    
            	    $this->db->insert(get_system_db().".qb_assessment_questions" , $data_question_sys_3);
            	    
   
    	        }
	        
	            if($question_type == 7){
	            
    	            $match_question_left_option_name_attr    = 'left_option_'.$index;
            	    $match_question_right_option_name_attr   = 'right_option_'.$index;
            	    $match_question_right_answer_name_attr   = 'right_answer_'.$index;
            	    $match_question_answer_marks_name_attr   = 'marks_'.$index;
    	            
            	    $data_question['question_type_id']         = $question_type;
            	    $data_question['assessment_id']            = $assessment_id;
            	    $data_question['question_text']            = $question_statement;
 
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question);
            	    $question_id  = $this->db->insert_id();
            	    
            	    
            	    $data_question_sys_4                          = array();
            	    $data_question_sys_4['question_type_id']      = $question_type;
    	            $data_question_sys_4['subject_id']            = $assessment_row->system_subject;
    	            $data_question_sys_4['class_id']              = $assessment_row->system_class;
            	    $data_question_sys_4['question_text']         = $question_statement;
    
            	    $this->db->insert(get_system_db().".qb_assessment_questions" , $data_question_sys_4);
            	    $system_question_id = $this->db->insert_id();
            	    
            	    
            	    
            	    $left_text    = $this->input->post($match_question_left_option_name_attr);
            	    $right_text   = $this->input->post($match_question_right_option_name_attr);
            	    $right_answer = $this->input->post($match_question_right_answer_name_attr);
            	    $option_marks = $this->input->post($match_question_answer_marks_name_attr);
            	    
            	    $data_options = array();
            	    $data_options_system = array();
        	    
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
                            
                            $data_option_system     = array(
                                'question_id'       => $system_question_id , 
                                'option_number'     => $opt + 1,
                                'left_side_text'    => $left_text[$opt] ,
                                'right_side_text'   => $right_text[$opt],
                                'right_answer'      => $right_answer[$opt],
                            );
                            array_push($data_options_system , $data_option_system);
                            
            	        }
                    }
                
                    $this->db->insert_batch(get_school_db().".matching_question_option" , $data_options);
                    $this->db->insert_batch(get_system_db().".qb_matching_question_option" , $data_options_system);
                    
                
                    $data_update_marks['question_total_marks'] = $question_total_marks;
	                $this->db->where('question_id',$question_id);
                    $this->db->update(get_school_db().'.assessment_questions',$data_update_marks);
                    
	            
	        }
	            
	        }
	        else
	        {
	            
        	    /*
                    1 MCQ Questions
                    2 True False Questions
                    3 Fill In The Blanks Questions
                    4 Short Questions
                    5 Long Questions
                    6 Pictorial Questions
                    7 Matching Questions
                    8 Drawing Questions
                */
	            
	            $bank_question_id_name_attr = 'bank_question_'.$index;
	            $bank_question_id           = $this->input->post($bank_question_id_name_attr);
	            
	            
	            if($question_type == 1){
	            
            	    $question_query = "select * from ".get_system_db().".qb_assessment_questions
	                                   WHERE question_id = $bank_question_id limit 1";
	                $question_row   = $this->db->query($question_query)->row();
    	            
    	            $data_question_2['question_type_id']         =  $question_row->question_type_id;
            	    $data_question_2['assessment_id']            =  $assessment_id;
            	    $data_question_2['question_text']            =  $question_row->question_text;
            	    $data_question_2['question_total_marks']     =  $this->input->post('qb_question_marks_'.$index);
            	    $data_question_2['right_answer_key']         =  $question_row->right_answer_key;
                    $this->db->insert(get_school_db().".assessment_questions" , $data_question_2);
            	    $question_id = $this->db->insert_id();
            	    
            	    $question_options_query = "select * from ".get_system_db().".qb_question_options
	                                           WHERE question_id = $bank_question_id";
	                $options_arr            = $this->db->query($question_options_query)->result_array();
            	    $data_options           = array();
            	    
            	    foreach($options_arr as $option_row)
                    {
                        $data_option = array(
                                'question_id'    => $question_id , 
                                'option_number'  => $option_row['option_number'],
                                'option_text'    => $option_row['option_text']
                        );
                        array_push($data_options , $data_option);
                    }
                    $this->db->insert_batch(get_school_db().".question_options" , $data_options);
  
	            }

    	        if( $question_type == 4 || $question_type == 5 || $question_type == 8 ){
    	            
    	            $question_query = "select * from ".get_system_db().".qb_assessment_questions
	                                   WHERE question_id = $bank_question_id limit 1";
	                $question_row   = $this->db->query($question_query)->row();
    	            
    	            $data_question_1['question_type_id']         =  $question_row->question_type_id;
            	    $data_question_1['assessment_id']            =  $assessment_id;
            	    $data_question_1['question_text']            =  $question_row->question_text;
            	    $data_question_1['question_total_marks']     =  $this->input->post('qb_question_marks_'.$index);
    
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_1);
    	            
    	        } 
	        
    	        if( $question_type == 2 || $question_type == 3 ){
    	            
    	            $question_query = "select * from ".get_system_db().".qb_assessment_questions
	                                   WHERE question_id = $bank_question_id limit 1";
	                $question_row   = $this->db->query($question_query)->row();
    	            
    	            $data_question_2['question_type_id']         =  $question_row->question_type_id;
            	    $data_question_2['assessment_id']            =  $assessment_id;
            	    $data_question_2['question_text']            =  $question_row->question_text;
            	    $data_question_2['question_total_marks']     =  $this->input->post('qb_question_marks_'.$index);
            	    $data_question_2['right_answer_key']         =  $question_row->right_answer_key;
     
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_2);
    	            
    	        }
    	        
    	        if($question_type == 7){
    	            
    	            $question_query = "select * from ".get_system_db().".qb_assessment_questions
	                                   WHERE question_id = $bank_question_id limit 1";
	                $question_row   = $this->db->query($question_query)->row();
    	            
            	    $data_question['question_type_id']         = $question_row->question_type_id;
            	    $data_question['assessment_id']            = $assessment_id;
            	    $data_question['question_text']            = $question_row->question_text;
 
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question);
            	    $question_id  = $this->db->insert_id();
            	    
            	    $question_options_query = "select * from ".get_system_db().".qb_matching_question_option
	                                           WHERE question_id = $bank_question_id";
	                $options_arr            = $this->db->query($question_options_query)->result_array();
            	    $data_options           = array();
            	    
            	    $match_question_answer_marks_name_attr   = 'qb_question_marks_'.$index;
            	    $option_marks                            = $this->input->post($match_question_answer_marks_name_attr);
            	    
            	    $question_total_marks = 0;

                    $iterator = 0;
            	    foreach($options_arr as $option_row)
                    {
                        $data_option = array(
                                'question_id'       => $question_id , 
                                'option_number'     => $option_row['option_number'],
                                'left_side_text'    => $option_row['left_side_text'],
                                'right_side_text'   => $option_row['right_side_text'],
                                'right_answer'      => $option_row['right_answer'],
                                'option_marks'      => $option_marks[$iterator],
                            );
                            $question_total_marks += $option_marks[$opt];
                            array_push($data_options,$data_option);
                            $iterator = $iterator + 1;
                    }
                    $this->db->insert_batch(get_school_db().".matching_question_option" , $data_options);

                    $data_update_marks['question_total_marks'] = $question_total_marks;
	                $this->db->where('question_id',$question_id);
                    $this->db->update(get_school_db().'.assessment_questions',$data_update_marks);
  
	            
	        }
	        
    	        if($question_type == 6){
    	            
    	            $question_query = "select * from ".get_system_db().".qb_assessment_questions
	                                   WHERE question_id = $bank_question_id limit 1";
	                $question_row   = $this->db->query($question_query)->row();
    	            
    	            $data_question_3['question_type_id']         =  $question_row->question_type_id;
            	    $data_question_3['assessment_id']            =  $assessment_id;
            	    $data_question_3['question_text']            =  $question_row->question_text;
            	    $data_question_3['question_total_marks']     =  $this->input->post('qb_question_marks_'.$index);

            	    $image_url   = file_upload_qb($question_row->image_url , 'pictorial_question', '');
            	    $data_question_3['image_url']                = $image_url;
            	    $this->db->insert(get_school_db().".assessment_questions" , $data_question_3);
    	            
    	        }
    	        
	        
	        }

	    }
	    
	    
	    $data_update['is_completed'] = 1;
	    $this->db->where('assessment_id',$assessment_id);
        $this->db->update(get_school_db().'.assessments',$data_update);
	    
	    $this->session->set_flashdata('club_updated',get_phrase('assessment_details_have_been_saved_successfully'));
	    
	    redirect(base_url().'assessment/view_assessment');
	    //redirect(base_url().'question_bank/qb_view_assessment');
	    
	}
	
	public function get_chapters_list(){
	    $subject_id = $this->input->post("subject_id");
	    echo get_chapters_list($subject_id);
	}
	
}