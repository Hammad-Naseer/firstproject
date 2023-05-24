<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assessment extends CI_Controller
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
	function view_assessment()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $q = "select ass.* ,ass_aud.* , s.name as teacher_name from ".get_school_db().".assessments ass
	    inner join ".get_school_db().".assessment_audience ass_aud on ass.assessment_id = ass_aud.assessment_id
	    inner join ".get_school_db().".staff s on ass.teacher_id = s.staff_id
	    WHERE ass_aud.student_id = ".$_SESSION['student_id']." and ass_aud.is_submitted = 0 and ass.school_id = ".$_SESSION['school_id']."";
	    $assessments = $this->db->query($q)->result_array();
	    
	    $page_data['assessment']		=	$assessments;
		$page_data['page_name']		=	'assessment/view_assessment';
		$page_data['page_title']		=	get_phrase('view_assessment');
		$this->load->view('backend/index', $page_data);
	}
	
	function assessment_details($assessment_id)
	{
	   
	    $this->db->set('number_of_attempts', 'number_of_attempts+1', FALSE);
        $this->db->where('assessment_id', $assessment_id); 
        $this->db->where('student_id', $_SESSION['student_id']);
        $this->db->update(get_school_db().'.assessment_audience');
        

	    if($_SESSION['user_login']!=1)redirect('login' );

	    $q = "SELECT ass_que.* , que_typ.* from ".get_school_db().".assessment_questions ass_que
	          inner join ".get_school_db().".question_types que_typ on que_typ.question_type_id = ass_que.question_type_id
	          where ass_que.assessment_id = ".$assessment_id.""; 
	    $assessments = $this->db->query($q)->result_array();
	    
	    $assessment_time_date = get_assessment_time_date($assessment_id , $_SESSION['student_id']);
	    
	    $page_data['assessment_time_date']		=	$assessment_time_date;
	    $page_data['assessments']		        =	$assessments;
		$page_data['page_name']		            =	'assessment/solve_assessment';
		$page_data['page_title']		        =	get_phrase('solve_assessment');
		$this->load->view('backend/student/assessment/solve_assessment', $page_data);
	}
	
	function submit_assessment_old()
	{
	    $assessment_id = $this->input->post('assessment_id');
	    

	    
	    
	    foreach($this->input->post() as $key=>$value)
        {
              $answer_array = array();
              $answer = explode('_', $key);
              if($answer[0] == "answer")
              {
                  $question_id  = $answer[1];
                  if($question_id != "")
                  {
                        $answer_array['assessment_id'] = $assessment_id;
                        $answer_array['student_id'] = $_SESSION['student_id'];
                        $answer_array['question_id'] = $question_id;
                        $answer_array['answer'] = $value;
                        $this->db->insert(get_school_db().'.assessment_solution' , $answer_array);
                  }
              }
        }
        
        
        if(isset($matching_question) && $matching_question != "")
        {
            foreach($this->input->post() as $key=>$value)
            {
                  $answer_array = array();
                  $answer = explode('_', $key);
                  if($answer[0] == "answermatching")
                  {
                      $question_id  = $answer[1];
                      
                      
                      if($question_id != "")
                      {
                            $answer_array['assessment_id'] = $assessment_id;
                            $answer_array['student_id'] = $_SESSION['student_id'];
                            $answer_array['question_id'] = $question_id;
                            $answer_array['answer'] = "";
                            $this->db->insert(get_school_db().'.assessment_solution' , $answer_array);
                            $insert_id = $this->db->insert_id();
                            
                            $matching_question_option_id = $this->input->post('matching_question_option_id');
                            
                            
                            $index = 0;
                            foreach($value as $option_id)
                            {
                                $matching_solution_array['assessment_solution_id'] = $insert_id;
                                $matching_solution_array['matching_question_option_id'] = $matching_question_option_id[$index];
                                $matching_solution_array['option_number'] = $option_id;
                                $this->db->insert(get_school_db().'.assessment_matching_solution' , $matching_solution_array);
                                $index++;
                            }
                            
                      }
                      
                  }
            }
            
        }
        
        
         //-----------assessment_audience----------------
        //----------------------------------------------
        $data['is_submitted'] = 1;
        $this->db->where('assessment_id',$assessment_id);
        $this->db->where('student_id',$_SESSION['student_id']);
        $this->db->update(get_school_db().'.assessment_audience',$data);
        
         //-----------assessment_attendance----------------
        //-------------------------------------------------
        $attendence['assessment_id'] = $assessment_id;
        $attendence['student_id'] = $_SESSION['student_id'];
        $attendence['inserted_at'] = date('Y-m-d H:i:s');
        $attendence['remarks'] = $this->input->post('remarks');
        $attendence['status'] = "present";
        
        $this->db->insert(get_school_db().'.assessment_attendance' , $attendence);
        
        
         //-----------Send Notification To Teacher-------------
        //-----------------------------------------------------
        $assessment_row =  get_assessment_row($assessment_id);
        $teacher_id     =  $assessment_row->teacher_id;
        $device_id      =  get_user_device_id(3 , $teacher_id , $_SESSION['school_id']);
        $title          =  "Assessment Submitted";
        $message        =  "Assessment Has Been Submitted By Student.";
        $link           =  "<?php echo base_url();?>assessment/view_assessments_submitted/".$assessment_id;
        sendNotificationByUserId($device_id, $title, $message, $link , $teacher_id , 3);
        
        /*
        $data_not = array(
                'user_id'     => $teacher_id ,
                'user_type'   => "teacher",
                'url'         => $link ,
                'inserted_at' => date('Y-m-d'),
                'text'        => $message ,
                'is_viewed'   => 0
        ); 
	    $this->db->insert(get_school_db().".school_notifications" , $data_not);
        */
        
        $this->session->set_flashdata('club_updated',get_phrase('Your_assessment_has_been_submitted.'));
        redirect(base_url().'/assessment/view_assessment');
	
	    
	}
	
	
	
	
	function submit_assessment()
	{

	    $assessment_id  = $this->input->post('assessment_id');
	    $question_count = $this->input->post('question_count');
	    
	    for($index = 1; $index <= $question_count;  $index++){
	        
	        $question_answer_name_attr             = 'answer_'.$index;
	        $question_id_name_attr                 = 'question_ids_'.$index;
	        $question_type_name_attr               = 'question_type_ids_'.$index;
	        
	        $question_answer          = $this->input->post($question_answer_name_attr);
	        $question_id              = $this->input->post($question_id_name_attr);
	        $question_type            = $this->input->post($question_type_name_attr);
	        

	        if( $question_type == 1 || $question_type == 4  || $question_type == 5 || $question_type == 2  || $question_type == 3 || $question_type == 6 ){
	            
	            $answer_array['assessment_id'] = $assessment_id;
                $answer_array['student_id']    = $_SESSION['student_id'];
                $answer_array['question_id']   = $question_id;
                $answer_array['answer']        = $question_answer;
                
                $this->db->insert(get_school_db().'.assessment_solution' , $answer_array);
                
                
	        }
	        
	        
	        if($question_type == 7){
	            
	            $answer_array['assessment_id'] = $assessment_id;
                $answer_array['student_id'] = $_SESSION['student_id'];
                $answer_array['question_id'] = $question_id;
                $answer_array['answer'] = "";
                $this->db->insert(get_school_db().'.assessment_solution' , $answer_array);
                $assessment_solution_id = $this->db->insert_id();
	            

	            $matching_question_id_name_attr          = 'matching_question_option_id_'.$index;
        	    $matching_question_answer_name_attr      = 'answermatching_'.$index;
        	    
        	    $matching_question_ids       = $this->input->post($matching_question_id_name_attr);
        	    $matching_question_answers   = $this->input->post($matching_question_answer_name_attr);

        	    for($opt = 0; $opt < count($matching_question_ids); $opt++){

                        $matching_solution_array['assessment_solution_id']      = $assessment_solution_id;
                        $matching_solution_array['matching_question_option_id'] = $matching_question_ids[$opt];
                        $matching_solution_array['option_number']               = $matching_question_answers[$opt];
                        $this->db->insert(get_school_db().'.assessment_matching_solution' , $matching_solution_array);
  
                }
                
	        }
	        
	    }
	    
	    
	    
	    
	     //-----------assessment_audience----------------
        //----------------------------------------------
        $data['is_submitted'] = 1;
        $this->db->where('assessment_id',$assessment_id);
        $this->db->where('student_id',$_SESSION['student_id']);
        $this->db->update(get_school_db().'.assessment_audience',$data);
        
        //-----------assessment_attendance----------------
        //------------------------------------------------
        
        $attendence['assessment_id'] = $assessment_id;
        $attendence['student_id'] = $_SESSION['student_id'];
        $attendence['inserted_at'] = date('Y-m-d H:i:s');
        $attendence['remarks'] = $this->input->post('remarks');
        $attendence['status'] = "present";
        
        $this->db->insert(get_school_db().'.assessment_attendance' , $attendence);
        
        
        $this->session->set_flashdata('club_updated',get_phrase('Your_assessment_has_been_submitted.'));
        
        redirect(base_url().'/assessment/view_assessment');
	    
	}
	
	function save_drawing_image()
	{
	      $base64_file = $_POST['base_file_parameters'];

          $output_file_name = time();
          
          $path = 'uploads/'.$_SESSION['folder_name'].'/drawing';
          
          if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
          
          $output_file = $path.'/'.$output_file_name.'.png';
          
          $ifp = fopen( $output_file, 'wb' ); 
          $data = explode( ',', $base64_file );
          fwrite( $ifp, base64_decode( $data[ 1 ] ) );
          fclose( $ifp ); 
          
          //file_upload_fun(base64_decode( $data[ 1 ] ) , "student_drawing", "draw", "");
          
          
    
          if(file_exists($output_file)) {
              $data_solution['assessment_id'] =  $_POST['assessment_id'];
              $data_solution['student_id'] = $_SESSION['student_id'];
              $data_solution['question_id'] = $_POST['question_id'];
              $data_solution['answer'] = "";
              $data_solution['drawing_sheet_url'] = $output_file_name.'.png';
              $this->db->insert(get_school_db().".assessment_solution" , $data_solution);
              $result['status'] = 200;
              echo json_encode($result);

      }
	}
	
	function assessment_result()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $q = "select ass.* , r.* , r.remarks as result_remarks ,s.name as teacher_name ,
	    sub.name as subject_name ,sub.code as subject_code from ".get_school_db().".assessments ass
	    inner join ".get_school_db().".assessment_result r on ass.assessment_id = r.assessment_id
	    inner join ".get_school_db().".staff s on ass.teacher_id = s.staff_id
	    inner join ".get_school_db().".subject sub on sub.subject_id = r.subject_id
	    WHERE r.student_id = ".$_SESSION['student_id']." and ass.school_id = ".$_SESSION['school_id']."";
	    
	    $result_arr = $this->db->query($q)->result_array();
	    
	    $page_data['result_arr']		=	$result_arr;
		$page_data['page_name']		    =   'assessment/assessment_result';
		$page_data['page_title']		=	get_phrase('assessment_result');
		$this->load->view('backend/index', $page_data);
	}
	
	function result_details($assessment_id)
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $q = "select sol.* , q.question_type_id ,q.question_text ,q.question_total_marks ,q.right_answer_key 
	    from ".get_school_db().".assessment_solution sol 
	    inner join ".get_school_db().".assessment_questions q on sol.question_id = q.question_id
	    WHERE sol.assessment_id = ".$assessment_id." and sol.student_id = ".$_SESSION['student_id']." ";
	    
	    $result_details = $this->db->query($q)->result_array();
	    $page_data['result_details']		=	$result_details;
		$page_data['page_name']		=	'assessment/result_details';
		$page_data['page_title']		=	get_phrase('result_details');
		$this->load->view('backend/index', $page_data);
	}
}
?>