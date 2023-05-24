<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//session_start();
class Student_evaluation extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if($_SESSION['user_login'] != 1)
            redirect('login');

        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
    function my_evaulation_results(){
        $page_data['page_title'] = get_phrase('my_evaulation_results');
        $page_data['page_name']  = 'my_evaulation_results';
        $this->load->view('backend/index', $page_data);
    }
    
    
    function get_my_eval_results(){
        $data['staff_id']=$this->input->post('staff_id');
        $data['evaluation_types']=$this->input->post('evaluation_types');
        $this->load->view("backend/student/ajax/get_my_eval_results",$data);
    }
    
    function get_stud_eval_result(){
        $data['section_id']=$this->input->post('section_id');
        $data['student_id']=$this->input->post('student_id');
        $data['evaluation_types']=$this->input->post('evaluation_types');
        $this->load->view("backend/admin/ajax/get_stud_eval_result",$data);
    }
    function get_selected_section_student(){
        $section_id = $this->input->post('section_id');
        $q = "SELECT student_id, name FROM " . get_school_db() . ".student
            WHERE 
            section_id=$section_id 
            AND school_id=" . $_SESSION['school_id'] . "
            AND student_status IN (" . student_query_status() . ")
            ORDER BY name";
        $query = $this->db->query($q);
        $str = '<option value="">' . get_phrase('all_students') . '</option>';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $rows) {
                $opt_selected = '';
                if ($rows->student_id == $selected) {
                    $opt_selected = "selected";
                }
                $str .= '<option value="' . $rows->student_id . '" ' . $opt_selected . '>' . $rows->name . '</option>';
            }
        }
        // $student_list = section_student($section_id);
        echo $str; 
    }
    
    
    function student_evaluation_results(){
        $page_data['page_title'] = get_phrase('student_evaluation_results');
        $page_data['page_name']  = 'student_evaluation_results';
        $this->load->view('backend/index', $page_data);
    }
    function stud_eval($param1 = '', $param2 = '', $param3 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');

        if ($param1 == 'create')
        {
            $exam_id=$this->uri->segment(5);
            $section_id=$this->uri->segment(6);

            $data['remarks']= $this->input->post('remarks');
            $data['school_id'] = $_SESSION['school_id'];
            $data['student_id']=$param2;
            $data['exam_id']=$param3;
            $data['answers']=$this->input->post('teacher_answers');
            $data['evaluated_by'] = $this->input->post('evaluated_by');
            if($this->input->post('subject_id'))
                $data['subject_id'] = $this->input->post('subject_id');
            else
                $data['subject_id'] = 0;
            // $data['evaluated_for'] = $evaluated_for;
            $data['who_evaluated'] = $_SESSION['user_id'];
            $filename=$_FILES['image1']['name'];
            $folder_name = $_SESSION['folder_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($filename!="")
            {
                $data['attachment']=file_upload_fun('image1','student_evaluation','');
            }
            $this->db->insert(get_school_db().'.student_evaluation', $data);

            $evaluation=$this->input->post('evaluation');
            if(count($evaluation) > 0)
            {
                $stud_eval_id = $this->db->insert_id();
                foreach($evaluation as $key=>$val)
                {
                    $data2['stud_eval_id']=$stud_eval_id;
                    $data2['eval_id']=$evaluation[$key]['eval_id'];
                    $data2['answers']=$evaluation[$key]['answers_select'];
                    $data2['remarks']=$evaluation[$key]['response'];
                    $data2['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db().'.student_evaluation_answers', $data2);
                }
            }

            $this->session->set_flashdata('club_updated', get_phrase('recored_saved_successfully'));
            redirect(base_url() . 'student_evaluation/stud_eval/'.$exam_id.'/'.$section_id);
        }
        else if ($param1 == 'do_update')
        {

            $std_eval_ans_array=$this->input->post('std_eval_array');
            $evaluation=$this->input->post('evaluation');
            $stud_eval_id=$this->input->post('stud_eval_id');

            if(count($evaluation) > 0)
            {
                foreach($evaluation as $key=>$val)
                {
                    $evaluation[$key]['answers_select'];

                    $std_eval_ans_id=$std_eval_ans_array[$key]['std_eval_id'];
                    $data2['eval_id']=$evaluation[$key]['eval_id'];
                    $data2['answers']=$evaluation[$key]['answers_select'];
                    $data2['remarks']=$evaluation[$key]['response'];
                    $this->db->where('school_id',$_SESSION['school_id']);
                    $this->db->where('std_eval_ans_id', $std_eval_ans_id);
                    if(!empty($std_eval_ans_id)) 
                        $this->db->update(get_school_db().'.student_evaluation_answers', $data2);
                    else{
                        $data2['stud_eval_id']=$stud_eval_id;
                        $data2['school_id'] = $_SESSION['school_id'];
                        $data['student_id']=$param2;
                        $this->db->insert(get_school_db().'.student_evaluation_answers', $data2);
                    }

                }
            }

            $data['remarks']= $this->input->post('remarks');
            $data['answers']=$this->input->post('teacher_answers');
            $filename=$_FILES['image1']['name'];
            $folder_name = $_SESSION['folder_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($filename!=""){
                $data['attachment']=file_upload_fun('image1','student_evaluation','');
            }

            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('stud_eval_id', $stud_eval_id);
            $this->db->update(get_school_db().'.student_evaluation', $data);

            $this->session->set_flashdata('club_updated', get_phrase('recored_updated_successfully'));
            $exam_id=$this->uri->segment(5);
            $section_id=$this->uri->segment(6);
            redirect(base_url() . 'student_evaluation/stud_eval/'.$exam_id.'/'.$section_id);
        }

        else if ($param1 == 'delete')
        {
            $exam_id=$this->uri->segment(5);
            $section_id=$this->uri->segment(6);

            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('stud_eval_id', $param2);
            $this->db->delete(get_school_db().'.student_evaluation');

            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('stud_eval_id', $param2);
            $this->db->delete(get_school_db().'.student_evaluation_answers');

            $image_old = $this->uri->segment(7);
            $del_location=system_path($image_old,'student_evaluation');
            file_delete($del_location);

            $this->session->set_flashdata('club_updated', get_phrase('recored_deleted_successfully'));
            redirect(base_url() . 'student_evaluation/stud_eval/'.$exam_id.'/'.$section_id);
        }
        $page_data['exam_id']=$exam_id;
        $page_data['section_id']=$section_id;
        $page_data['page_title'] = get_phrase('student_evaluation');
        $page_data['page_name']  = 'stud_evaluation';
        $this->load->view('backend/index', $page_data);

    }

    function get_year_term()
    {
        if($this->input->post('status')!=''){
            echo yearly_terms_option_list($this->input->post('academic_year'),'',$this->input->post('status'));
        }
        else
            echo yearly_terms_option_list($this->input->post('academic_year'));
    }

    function get_class(){
        echo class_option_list($this->input->post('department_id'),$this->input->post('class_id'));
    }

    function get_class_section(){
        echo section_option_list($this->input->post('class_id'),$this->input->post('section_id'));
    }

    function get_exam_type(){
        echo exam_type_option_list($this->input->post(yearly_term));
    }

    function get_stud_eval(){
        $data['section_id']=$this->input->post('section_id');
        $data['exam_id']=($this->input->post('type') == '1')?$this->input->post('exam_id'):0;
        $data['type']=$this->input->post('type');
        $data['subject_id']=(!empty($this->input->post('subject_id')))?$this->input->post('subject_id'):'0';
        $data['evaluated_by']=$this->input->post('evaluated_by');
        $this->load->view("backend/admin/ajax/get_stud_eval",$data);
    }

    function stud_evaluation_form($std_id=0, $section_id = 0, $type = 0, $exam_id=0, $subject_id=0)
    {
        $page_data['student_id'] = intval($std_id);
        $page_data['exam_id'] = intval($exam_id);
        $page_data['type'] = intval($type);
        $page_data['section_id'] = intval($section_id);
        $page_data['subject_id'] = intval($subject_id);
        $page_data['page_title'] = get_phrase('student_evaluation_form');
        $page_data['page_name']  = 'stud_evaluation_form';
        $this->load->view('backend/index', $page_data);
    }

    function view_evaluation_answers()
    {
        $page_data['page_name']  = 'view_student_evaluation';
        $this->load->view('backend/index', $page_data);
    }
    function modal_view_student_evaluation()
    {
        $page_data['page_name']  = 'modal_view_stud_evaluation';
        $page_data['student_id']=$this->uri->segment(3);
        $page_data['section_id']=$this->uri->segment(4);
        $page_data['type']=$this->uri->segment(5);
        $page_data['exam_id']=$this->uri->segment(6);
        $page_data['subject_id']=$this->uri->segment(7);

        $this->load->view('backend/index', $page_data);
    }


}