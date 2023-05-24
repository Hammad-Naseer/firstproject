<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Adm_assessment extends CI_Controller
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
    
	function view_assessment()
	{
	    if($_SESSION['user_login']!=1)redirect('login' );
	    
	    $apply_filter  = $this->input->post('apply_filter');
	    
	    $yearly_term_id  = $this->input->post('yearly_term_id');
	    $section_id  = $this->input->post('section_id');
	    $subject_id  = $this->input->post('subject_id');
	    $teacher_id  = $this->input->post('teacher_id');
	    
	    $where_filter=" where ass.is_completed = 1";
	    
        if ($yearly_term_id != "")
        {
            $where_filter .= " and ass.yearly_term_id = '".$yearly_term_id."' ";
        }
        
        if ($section_id != "")
        {
            $where_filter .= " and aud.section_id = '".$section_id."' ";
        }
        
        if ($subject_id != "")
        {
            $where_filter .= " and aud.subject_id= '".$subject_id."' ";
        }
        if ($teacher_id != "")
        {
            $where_filter .= " and ass.teacher_id = '".$teacher_id."' ";
        }
        
        
        if ($apply_filter == "")
        {
              $q = "select * from ".get_school_db().".assessments ass
              WHERE ass.is_completed = 1 and ass.school_id=".$_SESSION['school_id']."
              order by ass.assessment_id desc";
              
              $assessments = $this->db->query($q)->result_array();
            
        }else{
                $q1 = "select GROUP_CONCAT(DISTINCT ass.assessment_id) as assessment_ids from ".get_school_db().".assessments ass
                inner join ".get_school_db().".assessment_audience aud on ass.assessment_id = aud.assessment_id
                 ".$where_filter." ";
                
                $result = $this->db->query($q1)->result_array();
                $assessment_ids =  $result[0]['assessment_ids'];
                
                if($assessment_ids != ""){
                    $q = "select * from ".get_school_db().".assessments ass WHERE ass.is_completed = 1 and ass.school_id=".$_SESSION['school_id']." 
                      and ass.assessment_id in ($assessment_ids) 
                      order by ass.assessment_id desc";
                    $assessments = $this->db->query($q)->result_array();
                }else{
                    $assessments = array();
                }
        }
        
	    $page_data['assessments']		=	$assessments;
	    $page_data['apply_filter']		=	$apply_filter;
	    $page_data['yearly_term_id']	=	$yearly_term_id;
	    $page_data['teacher_id']		=	$teacher_id;
	    $page_data['section_id']		=	$section_id;
	    $page_data['subject_id']		=	$subject_id;
		$page_data['page_name']		    =	'assessment/view_assessment';
		$page_data['page_title']		=	get_phrase('view_assessment');
		$this->load->view('backend/index', $page_data);
	}
	
	function view_assessment_details($assessment_id)
	{
		if($_SESSION['user_login']!=1)  redirect('login' );
		$page_data['page_name']		=	'assessment/view_assessment_audience';
		$page_data['page_title']    =	get_phrase('view_assessment_audience');
		$page_data['assessment_id'] =   $assessment_id;
		$page_data['students'] =   get_submitted_assessment_students($assessment_id);
		$this->load->view('backend/index', $page_data); 
	}
	
	function exam_weightage($param1 = '', $param2 = ''){
		if($_SESSION['user_login']!= 1)
		redirect(base_url());

		if($param1 == 'create'){
		    $count = count($this->input->post('subject_id'));
		    for($i = 0; $i < $count; $i++)
		    {
    			$data['yearly_term_id']        = $this->input->post('yearly_term_id');
    			$data['section_id'] = $this->input->post('section_id');
    			$data['subject_id']   = $this->input->post('subject_id')[$i];
    			$data['exam_percentage']   = $this->input->post('exam_weightage_per')[$i];
    			$data['assessment_percentage']     = $this->input->post('assess_weightage_per')[$i];
    			$data['school_id'] = $_SESSION['school_id'];
    			$this->db->insert(get_school_db().'.exam_weightage', $data);
		    }			
			$this->session->set_flashdata('club_updated', get_phrase('exam_weightage_added_successfully'));
			redirect(base_url() . 'adm_assessment/exam_weightage/');
		}
		
		if($param1 == 'do_update'){
			$data['yearly_term_id']        = $this->input->post('yearly_term_id');
			$data['section_id'] = $this->input->post('section_id');
			$data['subject_id']   = $this->input->post('subject_id');
			$data['exam_percentage']   = $this->input->post('exam_weightage_per');
			$data['assessment_percentage']     = $this->input->post('assess_weightage_per');
            
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('weightage_id', $param2);
			$this->db->update(get_school_db().'.exam_weightage', $data);
			$this->session->set_flashdata('club_updated', get_phrase('record_saved'));
			redirect(base_url() . 'adm_assessment/exam_weightage/');
		} 
        
		else if($param1 == 'edit'){
			$page_data['edit_data'] = $this->db->get_where('exam_weightage', array(
					'weightage_id' => $param2,
					'school_id' =>$_SESSION['school_id']
				))->result_array();
		}
		
		if($param1 == 'delete'){
			$this->db->where('school_id',$_SESSION['school_id']);
			$this->db->where('weightage_id', $param2);
			$this->db->delete(get_school_db().'.exam_weightage');
			$this->session->set_flashdata('club_updated', get_phrase('record_deleted_successfully'));
			redirect(base_url() . 'adm_assessment/exam_weightage/');
		}
		$page_data['weightage']=$this->db->query("SELECT ".get_school_db().".exam_weightage.*,".get_school_db().".yearly_terms.title AS yt,".get_school_db().".yearly_terms.yearly_terms_id,".get_school_db().".subject.subject_id,".get_school_db().".subject.name AS sn,".get_school_db().".class_section.section_id,".get_school_db().".class_section.title AS st FROM ".get_school_db().".exam_weightage LEFT JOIN ".get_school_db().".yearly_terms ON ".get_school_db().".yearly_terms.yearly_terms_id = ".get_school_db().".exam_weightage.yearly_term_id LEFT JOIN ".get_school_db().".subject ON ".get_school_db().".subject.subject_id = ".get_school_db().".exam_weightage.subject_id LEFT JOIN ".get_school_db().".class_section ON ".get_school_db().".class_section.section_id = ".get_school_db().".exam_weightage.section_id")->result_array();
		$page_data['page_name']  = 'exam_weightage';
		$page_data['page_title'] = get_phrase('manage_exam_weightage');
		$this->load->view('backend/index', $page_data);
	}
	
	public function get_section_all_subject(){
	    $section_id = $this->input->post('section_id');
	    
	    $display = '';
	    $i = 1;
	    $display .= '<table class="table table-bordered"><tr><th>#</th><th>Subject</th><th>Exam Weightage</th><th>Assessments Weightage</th></tr>';
	    $q = $this->db->query("select s.subject_id,s.name from " . get_school_db() . ".subject s INNER join " . get_school_db() . ".subject_section ss on s.subject_id=ss.subject_id where ss.section_id='$section_id' and s.school_id=" . $_SESSION['school_id'] . " ");
	    if($q->num_rows() > 0){
    	    foreach($q->result() as $r){
    	        $display .= '<tr>
    	            <td>'.$i++.'</td>
    	            <td><input type="hidden" name="subject_id[]" value="'.$r->subject_id.'">'.$r->name.'</td>
    	            <td><input type="number" name="exam_weightage_per[]" class="form-control checkingone'.$i.'"></td>
    	            <td><input type="number" name="assess_weightage_per[]" class="form-control checkingtwo'.$i.'" readonly="">
    	                <script>
    	                    $(".checkingone'.$i.'").keyup(function() {
                                var percent = $(this).val();
                                if(percent < 0){
                                    alert("Value Not Accepted Less Than Zero");
                                    $(this).val(0);
                                }else if(percent > 100){
                                    alert("Value Not Accepted Greater Than 100");
                                    $(this).val(0);
                                    $(".checkingtwo'.$i.'").val(0);
                                }else{
                                    var second = 100-percent;
                                    $(".checkingtwo'.$i.'").val(second);
                                }
                            });
                            // $(".checkingone'.$i.'").keyup(function() {
                            //     var percent = $(this).val();
                            //     if(percent < 0){
                            //         alert("Counter Stop");
                            //         $(this).val(0);
                            //     }else{
                            //         var second = 100-percent;
                            //         $(".checkingtwo'.$i.'").val(second);
                            //     }
                            // });
    	                </script>
    	            </td>
    	        </tr>';
    	    }
	    }else{
	        $display .= '<tr><td colspan="4">Data Not Found</td></tr>';
	    }
	    $display .= '</table>';
	    echo $display;
	}
	
	
}
?>