<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payments extends CI_Controller
{
    function payment_listing($student_id,$section_id)
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        $page_data['page_name']  = 'payment_listing';
        $page_data['page_title'] = get_phrase('withdraw_listing');
        // $page_data['student_id'] = str_decode($student_id);
        $page_data['student_id'] = str_decode($student_id);
        $page_data['section_id'] = str_decode($section_id);
        $this->load->view('backend/index', $page_data);
    }

    function get_chalan_listing($student_id)
    {
        $data['section_id']      = $this->input->post('section_id');
        $data['academic_year']   = $this->input->post('academic_year');
        $data['departments_id']  = $this->input->post('departments_id');
        $data['class_id']        = $this->input->post('class_id');
        $data['student_id']      = $student_id;
    
        $this->load->view("backend/admin/ajax/get_chalan_listing",$data);
    }
}
