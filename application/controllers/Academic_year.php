<?php if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();

class Academic_year extends CI_Controller{
    function __construct()
    {
        
        parent::__construct();
        if($_SESSION['user_login'] != 1)
            redirect('login');
            
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->menu_ary=array();
    }

    function acadmic_year_listing($action="", $id="")
    {
        if($action=="add_edit")
        {
            $is_closed = $this->input->post('is_closed');
            if ($is_closed) 
            {
                $is_closed_val =  $is_closed;   
            }else{
                $is_closed_val = 0;
            }
            $data['title']      =  $this->input->post('title');
            $data['order_num']  =  0;
            $academic_year_id   =  $this->input->post('academic_year_id');
            $data['start_date'] =  $this->input->post('start_date'); 
            $data['end_date']   =  $this->input->post('end_date');  
            $data['detail']     =  $this->input->post('detail');
            $status             =  $this->input->post('status');
            $data['status']     =  $status;
            $data['is_closed']  =  $is_closed_val;
            $school_id          =  $_SESSION['school_id'];

            if($academic_year_id != "")
            {
                
                $updateTermStatus="update ".get_school_db().".yearly_terms set status=".$data['status']." where academic_year_id=".$academic_year_id." and school_id=".$_SESSION['school_id']."";
                $this->db->query($updateTermStatus);

                $this->db->where('academic_year_id',$academic_year_id);
                $this->db->where('school_id',$school_id);
                $this->db->update(get_school_db().'.acadmic_year',$data);
                $this->session->set_flashdata('club_updated',get_phrase('academic_year_updated_successfully'));
            }
            else
            {
                $data['school_id']=$school_id;
                $this->db->insert(get_school_db().'.acadmic_year',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));
            }
            redirect(base_url() . 'academic_year/acadmic_year_listing/');

        }

        if($action=='delete')
        {
            $school_id=$_SESSION['school_id'];
            $qur=$this->db->query("select yearly_terms_id from ".get_school_db().".yearly_terms where academic_year_id=$id ")->result_array();
            if(count($qur)>0)
            {
                $this->session->set_flashdata('club_updated',get_phrase('remove_related_terms_to_remove_acadmic_year'));
            }
            else
            {
                $delete_ary=array('school_id'=>$school_id,'academic_year_id'=>$id);

                $this->db->where($delete_ary);
                $this->db->delete(get_school_db().'.acadmic_year');
                $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
            }

            redirect(base_url() . 'academic_year/acadmic_year_listing/');
        }

        $page_data['page_name']  = 'acadmic_year_listing';
        $page_data['page_title'] = get_phrase('academic_year');
        $this->load->view('backend/index', $page_data);
    }

    function yearly_terms($action="", $id="")
    {

        if($action=="add_edit")
        {
            $order_num = $this->input->post('order_num');
            $is_closed = $this->input->post('is_closed');
            if ($order_num) 
            {
                $order_num_val =  $order_num;   
            }else{
                $order_num_val = 0;
            }
            if ($is_closed) 
            {
                $is_closed_val =  $is_closed;   
            }else{
                $is_closed_val = 0;
            }
            $acad_id                   = $this->input->post('academic_year_id');
            $data['academic_year_id']  = $acad_id;
            $data['title']             = $this->input->post('title');
            $data['order_num']         = $order_num_val;
            $yearly_terms_id           = $this->input->post('yearly_terms_id');
            $data['start_date']        = $this->input->post('start_date');
            $data['end_date']          = $this->input->post('end_date');
            $red_id                    = $this->input->post('red_id');
            $data['detail']            = $this->input->post('detail');
            $status                    = $this->input->post('status');
            $data['status']            = $status;
            $data['is_closed']         = $is_closed_val;
            $school_id                 = $_SESSION['school_id'];

            if($yearly_terms_id != "")
            {
                $this->db->where('yearly_terms_id',$yearly_terms_id);
                $this->db->where('school_id',$school_id);
                $this->db->update(get_school_db().'.yearly_terms',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
            }
            else{
                
                $data['school_id']=$school_id;
                $this->db->insert(get_school_db().'.yearly_terms',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));
                
            }
            redirect(base_url() . 'academic_year/acadmic_year_listing/');

        }

        if($action=='delete'){
            $red_p= $this->uri->segment(5);
            $school_id=$_SESSION['school_id'];

            /*
            $qur_1=$this->db->query("select * from ".get_school_db().".academic_planner where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_1)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }

            $qur_2=$this->db->query("select * from ".get_school_db().".attendance where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_2)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }


            $qur_3=$this->db->query("select * from ".get_school_db().".circular where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_3)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }



            $qur_4=$this->db->query("select * from ".get_school_db().".class_routine_settings where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_4)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }


            $qur_5=$this->db->query("select * from ".get_school_db().".diary where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_5)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }
            */

            $qur_6=$this->db->query("select exam_id from ".get_school_db().".exam where school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_6)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_term_being_used_in_exam_record'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }

            /*
            $qur_7=$this->db->query("select * from ".get_school_db().".exam_routine where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_7)>0)
            {
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }


            $qur_8=$this->db->query("select * from ".get_school_db().".holiday where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_8)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }

            $qur_9=$this->db->query("select * from ".get_school_db().".leave_student where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_9)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }

            $qur_10=$this->db->query("select * from ".get_school_db().".noticeboard where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_10)>0){
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }
            */
            /*
                $qur_11=$this->db->query("select * from ".get_school_db().".policies where  school_id=$school_id and yearly_terms_id=$id")->result_array();

                if(count($qur_11)>0){
                    $this->session->set_flashdata('club_updated','Deletion Failed. Record Already In Use');
                    redirect(base_url() . 'academic_year/acadmic_year_listing/');
                    exit();
                }

            */
            /*
            $qur_12=$this->db->query("select * from ".get_school_db().".subject_components 
									where school_id=$school_id 
									and yearly_terms_id=$id")->result_array();

            if(count($qur_12)>0)
            {
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                redirect(base_url() . 'academic_year/acadmic_year_listing/');
                exit();
            }

            
            $qur_13=$this->db->query("select * from ".get_school_db().".subject_teacher where  school_id=$school_id and yearly_terms_id=$id")->result_array();

            if(count($qur_13)>0){
                $this->session->set_flashdata('club_updated','Deletion Failed. Record Already In Use');
                        redirect(base_url() . 'academic_year/acadmic_year_listing/');
                        exit();
            }

            */
            
            
            $delete_ary=array('school_id'=>$school_id,'yearly_terms_id'=>$id);
            $this->db->where($delete_ary);
            $this->db->delete(get_school_db().'.yearly_terms');
            $this->session->set_flashdata('club_updated',get_phrase('term_deleted'));
            redirect(base_url() . 'academic_year/acadmic_year_listing/');

        }


        $page_data['page_name']  = 'yearly_terms';
        $page_data['page_title'] = get_phrase('yearly_terms');
        $this->load->view('backend/index', $page_data);

    }



}