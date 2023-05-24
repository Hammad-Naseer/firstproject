<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
//session_start();

class Message extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        if($_SESSION['user_login']!= 1)
           redirect(base_url() . 'login');

       $this->load->helper('message');
    }

    function messages_subject_list()
    {
        
        $teacher_id = intval($this->input->post('teacher_id'));
        $filter = '';
        if ($section_id > 0)
        {
            $page_data['filter'] = true;
            $filter .= " and cs.section_id=$section_id";
        }
        if ($teacher_id > 0)
        {
            $page_data['filter'] = true;
            $page_data['teacher_id'] = $teacher_id;
            $filter .= " and s.staff_id=$teacher_id";
        }
        
        $messages_arr = $this->db->query("select m.*, s.staff_id, s.name as teacher_name, s.staff_image, d.title as designation, sub.subject_id, sub.name as subject_name, sub.code as subject_code, cs.title as section, cs.section_id, c.name as class, dep.title as department 
            from ".get_school_db().".messages m
            inner join ".get_school_db().".staff s on m.teacher_id = s.staff_id
            inner join ".get_school_db().".designation d on d.designation_id = s.designation_id
            inner join ".get_school_db().".subject sub on sub.subject_id = m.subject_id
            inner join ".get_school_db().".class_routine cr on cr.subject_id = sub.subject_id
            inner join ".get_school_db().".class_routine_settings crs on crs.c_rout_sett_id = cr.c_rout_sett_id
            inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
            inner join ".get_school_db().".class c on c.class_id = cs.class_id
            inner join ".get_school_db().".departments dep on dep.departments_id = c.departments_id
            where m.school_id = ".$_SESSION['school_id']." $filter  group by m.teacher_id, m.subject_id  order by m.message_time desc ")->result_array();

        $page_data['messages_data'] = $messages_arr;
        $page_data['page_name'] = 'messages_subject_list';
        $page_data['page_title'] = get_phrase('messages_list');
		
        $this->load->view('backend/index', $page_data);
    }

    function messages_student_list($subject_id=0, $section_id=0, $teacher_id=0)
    {
        
        $messages_arr = $this->db->query("select m.*, s.staff_id, s.name as teacher_name, s.staff_image,s.staff_id, d.title as designation, st.name as student_name,st.student_id, st.roll, st.image as student_image , sub.subject_id, sub.name as subject_name, sub.code as subject_code, cs.section_id, cs.title as section, c.name as class, dep.title as department 
            from ".get_school_db().".messages m
            inner join ".get_school_db().".staff s on m.teacher_id = s.staff_id
            inner join ".get_school_db().".designation d on d.designation_id = s.designation_id
            inner join ".get_school_db().".subject sub on sub.subject_id = m.subject_id
            inner join ".get_school_db().".student st on st.student_id = m.student_id
            inner join ".get_school_db().".class_section cs on cs.section_id = st.section_id
            inner join ".get_school_db().".class c on c.class_id = cs.class_id
            inner join ".get_school_db().".departments dep on dep.departments_id = c.departments_id
            where m.school_id = ".$_SESSION['school_id']." and m.subject_id = ".intval(str_decode($subject_id))." and m.teacher_id = ".intval(str_decode($teacher_id))." 
            group by m.student_id order by m.message_time desc ")->result_array(); //and cs.section_id = ".intval(str_decode($section_id))."

        $page_data['messages_data'] = $messages_arr;
        $page_data['page_name'] = 'messages_student_list';
        $page_data['page_title'] = get_phrase('messages_list');

        $this->load->view('backend/index', $page_data);
    }

    function messages($subject_id=0, $student_id=0, $teacher_id = 0)
    {
        $messages_arr = $this->db->query("select m.* from ".get_school_db().".messages m where  m.school_id = ".$_SESSION['school_id']."  and m.subject_id = ".intval(str_decode($subject_id))." 
                                          and m.student_id = ".intval(str_decode($student_id))." and m.teacher_id = ".intval(str_decode($teacher_id))."  order by m.messages_id asc ")->result_array();
                                        //  print_r($messages_arr);
        $page_data['messages_data'] = $messages_arr;
        $page_data['page_name'] = 'messege_view';
        $page_data['page_title'] = get_phrase('messages_list');

        $this->load->view('backend/index', $page_data);
    }

    function message_send()
    {
        $data['subject_id'] = intval($this->input->post('subject_id'));
        $data['student_id'] = intval($this->input->post('student_id'));
        $data['teacher_id'] = intval($this->input->post('teacher_id'));
        $data['messages'] = trim($this->input->post('message'));
        $data['is_viewed'] = 0;
        $data['school_id'] = $_SESSION['school_id'];
        $data['messages_type'] = 0;//parent
        $data['sent_by'] = intval($_SESSION['login_detail_id']);
        $data['message_time'] = date('Y-m-d H:i:s');

        if ($data['messages'] != '')
        {
            $this->db->insert(get_school_db().'.messages',$data);
        }

        redirect(base_url().'message/messages/'.$data['subject_id'].'/'.$data['student_id'].'/'.$data['teacher_id']);
    }
    
    function update_chat_message(){

        $teachers_id     = $this->input->post('teacher_id'); 
        $student_id      = $this->input->post('student_id'); 
        $subject_id      = $this->input->post('subject_id');

        $this->db->query("update ".get_school_db().".messages  set is_viewed=1 
                          WHERE teacher_id=$teachers_id and student_id=$student_id and subject_id=$subject_id  and messages_type=1 and school_id=".$_SESSION['school_id']." ");

        $query = $this->db->query("select m.* from ".get_school_db().".messages m where   m.school_id = ".$_SESSION['school_id']." 
                                   and m.subject_id = ".intval($subject_id)." and m.student_id = ".intval($student_id)." and m.teacher_id = ".intval($teachers_id)." order by m.messages_id asc ")->result_array();

        $page_data['messages_data'] = $query;
        $page_data['page_name']     = 'ajax_message';
        $this->load->view('backend/admin/ajax_message' , $page_data);

    }
    
    
    
}