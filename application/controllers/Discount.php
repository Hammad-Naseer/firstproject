<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();


class Discount extends CI_Controller{

    function __construct(){

        parent::__construct();

        if($_SESSION['accountant_login'] == 1 || $_SESSION['user_login']==1){
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');
            $this->menu_ary=array();

        }else{
            redirect('login');
        }
    }


    function discount_list($action="", $id="")
    {
        $school_id=$_SESSION['school_id'];
        $login_type = $_SESSION['login_type'];
        if($action=="add_edit")
        {
            $receive_dr_coa_id = $this->input->post('receive_dr_coa_id');
            if(empty($receive_dr_coa_id) || $receive_dr_coa_id == "")
            {
                $receive_dr_coa_id = 0;    
            }
            
            $data['title']=$this->input->post('title');
            $discount_id=$this->input->post('discount_id');
                //$data['percent']=$this->input->post('percent');
            $data['status']=$this->input->post('status');
            $data['fee_type_id']=$this->input->post('fee_type');
            $data['issue_dr_coa_id']= $this->input->post('issue_cr_coa_id');
            $data['receive_dr_coa_id']= $receive_dr_coa_id;

            if($discount_id!="")
            {

                $coa_exists = 1;

                if($login_type == 1)
                {

                    /* check branch coa_id and school id exist or not exist start */

                    $is_assign_str = "select school_id from ".get_school_db().".school_discount_list where discount_id =$discount_id and school_id != $school_id";
                    $is_assign_query = $this->db->query($is_assign_str)->result_array();

                    $assign_school_arr = array();

                    foreach ($is_assign_query as $assign_school)
                    {
                        $assign_school_arr[] = $assign_school['school_id'];
                    }

                    foreach ($assign_school_arr as $school_assign_id)
                    {
                        // issue_dr_coa_id , issue_cr_coa_id ,receive_dr_coa_id ,receive_cr_coa_id
                        $coa_issue_cr_coa_id_exist =  $this->get_coa_exists($school_assign_id,$data['issue_cr_coa_id']);

                        if(count($coa_issue_cr_coa_id_exist)==0)
                        {
                            $coa_exists = 0;
                        }
                        else
                        {
                            $coa_receive_dr_coa_id_exist =  $this->get_coa_exists($school_assign_id,$data['receive_dr_coa_id']);

                            if(count($coa_receive_dr_coa_id_exist)==0)
                            {
                                $coa_exists = 0;
                            }
                        }
                    }


                    /* check branch coa_id and school id exist or not exist end */

                   // exit("coa_exists - ".$coa_exists);
                }

                if($coa_exists == 1)
                {
                    $this->db->where('discount_id', $discount_id);
                    //$this->db->where('school_id', $school_id);
                    $this->db->update(get_school_db() . '.discount_list', $data);
                    $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
                }
                else
                {
                    $this->session->set_flashdata('club_updated', get_phrase('record_not_updated_becasue_of_invalid_coa_settings'));
                }
             }
            else
            {
                $data['school_id']=$school_id;
                $this->db->insert(get_school_db().'.discount_list',$data);

                /* Insertion in school_discount_list  start */
                $discount_id = $this->db->insert_id();
                $discount_id_temp = $discount_id;
                $data_discount['school_id'] =  $school_id;
                $data_discount['discount_id'] = $discount_id_temp;
                $this->db->insert(get_school_db().'.school_discount_list', $data_discount);

                $login_type = $_SESSION['login_type'];

                if($login_type == 2)
                {
                    $parent_sys_sch_id = $_SESSION['parent_sys_sch_id'];
                    $school_coa_str = "select school_id from ".get_school_db().".school where sys_sch_id =$parent_sys_sch_id";
                    $school_coa_query = $this->db->query($school_coa_str)->row();
                    $school_id_temp = $school_coa_query->school_id;
                    $data_discount['school_id'] =  $school_id_temp;
                    $this->db->insert(get_school_db().'.school_discount_list', $data_discount);
                }
                //____________________

                $this->session->set_flashdata('club_updated',get_phrase('record_saved_successfully'));
            }
            redirect(base_url() . 'discount/discount_list');

        }

        if($action=='delete')
        {
            $qur_1=$this->db->query("select * from ".get_school_db().".class_chalan_discount where discount_id=$id and school_id=".$_SESSION['school_id'])->result_array();
            if(count($qur_1)>0)
            {
                $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                // redirect(base_url() . 'discount/discount_list');
                // exit();
            }
            else
            {

                //$this->db->where('discount_id', $id);
                // $this->db->delete(get_school_db().'.discount_list');

                $school_id=$_SESSION['school_id'];

                $login_type = $_SESSION['login_type'];
                if($login_type == 2)
                {
                    $this->db->where('discount_id', $id);
                    $this->db->where('school_id', $school_id);
                    $this->db->delete(get_school_db().'.school_discount_list');
                    $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
                }
                elseif ($login_type == 1)
                {
                    $qur_1=$this->db->query("select * from ".get_school_db().".school_discount_list where discount_id=$id and school_id != ".$_SESSION['school_id'])->result_array();

                    if(count($qur_1)>0)
                    {
                        $this->session->set_flashdata('club_updated',get_phrase('deletion_failed_record_already_in_use'));
                        // redirect(base_url() . 'fee_types/fee_types_c/');
                        //exit();
                    }
                    else
                    {
                        $this->db->where('discount_id', $id);
                        $this->db->where('school_id', $school_id);
                        $this->db->delete(get_school_db().'.school_discount_list');
                        $this->session->set_flashdata('club_updated',get_phrase('record_deleted_successfully'));
                    }
                }

            }
            redirect(base_url() . 'discount/discount_list');

        }

        $page_data['page_name']  = 'discount_list';
        $page_data['page_title'] = get_phrase('discount_setting');

        $this->load->view('backend/index', $page_data);

    }


    function get_coa_exists($school_assign_id , $coa_id)
    {

        $coa_exists_str = "select * from ".get_school_db().".school_coa 
                        where school_id =$school_assign_id and coa_id = $coa_id";
        $coa_exists_query = $this->db->query($coa_exists_str)->result_array();
        return $coa_exists_query;

    }

}