<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Circular_staff extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('zip');
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->school_db=$_SESSION['school_db'];
        if($_SESSION['user_login']!= 1)
            redirect(base_url() . 'login');
    }

    public function index()
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($_SESSION['user_login'] == 1)
            redirect(base_url() . 'admin/dashboard');
    }
    
    function circulars_staff($param1 = '', $param2 = '', $param3 = '')
    {

        if ($_SESSION['user_login'] != 1)
            redirect(base_url());

        if ($param1 == 'create') {
            $data['circular_title']=$this->input->post('circular_title');
            $data['circular']  = $this->input->post('circular');
            $data['staff_id'] = $this->input->post('staff_id');
            $data['school_id']= $_SESSION['school_id'];
            $data['circular_date']= date('Y-m-d', strtotime($this->input->post('circular_date')));
            $data['is_active']	=  intval($this->input->post('is_active'));

            $filename=$_FILES['image2']['name'];
            $folder_name = $_SESSION['folder_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($filename!=""){
                $data['attachment']=file_upload_fun('image2','circular_staff','');
            }

            $this->db->insert(get_school_db().'.circular_staff',$data);
            $last_is=$this->db->insert_id();
            $this->load->helper('message');
            $message="New Circular: ".$data['circular_title']."  ".$data['circular']. " ";
            
            if(isset($_POST['send_message']) && $_POST['send_message']!="") {

                if($data['staff_id']!="" || $data['staff_id']>0){
                    $sms_ary=get_sms_detail_staff($data['staff_id']);
                    send_sms($sms_ary['mobile_no'],'Indici Edu',$message,$data['staff_id']);
                }

                else{
                    sms_to_all_staff($message);
                    $data_status['sms_status']=1;
                    $this->db->where('notice_id',$param2);
                    $this->db->update(get_school_db().'.noticeboard',$data_status);
                }
                $data_status['sms_status']=1;
                $this->db->where('circular_staff_id',$last_is);
                $this->db->update(get_school_db().'.circular_staff',$data_status);
                
            }
                //Email Setting here
            if(isset($_POST['send_email']) && $_POST['send_email']!="")
            {
                if($data['staff_id']!="" || $data['staff_id']>0){
                    $sms_ary=get_sms_detail_staff($data['staff_id']);
                    $email_layout = get_email_layout($message);
                    email_send("No Reply","Indici-Edu",$sms_ary['email'],"Circular",$email_layout,$data['staff_id']);

                }else{
                    $email_layout = get_email_layout($message);
                    email_to_all_staff($email_layout,"Circular");
                    $data_status['sms_status']=1;
                    $this->db->where('notice_id',$param2);
                    $this->db->update(get_school_db().'.noticeboard',$data_status);
                }
                $data_status['sms_status']=1;
                $this->db->where('circular_staff_id',$last_is);
                $this->db->update(get_school_db().'.circular_staff',$data_status);
            }

            $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
            redirect(base_url() . 'circular_staff/circulars_staff/');
        }
        if ($param1 == 'edit') {

            $data['circular_title']=$this->input->post('circular_title');
            $data['circular']  = $this->input->post('circular');
            $data['staff_id'] = $this->input->post('staff_id');
            $data['school_id']= $_SESSION['school_id'];
            $data['circular_date']= date('Y-m-d', strtotime($this->input->post('circular_date')));
            $data['is_active']	=  intval($this->input->post('is_active'));
            
            $filename=$_FILES['image2']['name'];
            $folder_name = $_SESSION['folder_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($filename!=""){
                $data['attachment']=file_upload_fun('image2','circular_staff','');

                $image_old = $this->input->post('image_old');
                if($image_old!=""){
                    $del_location=system_path($image_old,'circular_staff');
                    file_delete($del_location);
                }
            }
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('circular_staff_id', str_decode($param2));
            $this->db->update(get_school_db().'.circular_staff', $data);
             //echo $this->db->last_query();
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            $this->load->helper('message');
            $message="Updated New Circular: ".$data['circular_title']."  ".$data['circular']. " ";

            if(isset($_POST['send_message']) && $_POST['send_message']!="") {

                if($data['staff_id']!="" || $data['staff_id']>0){
                    $sms_ary=get_sms_detail_staff($data['staff_id']);
                    send_sms($sms_ary['mobile_no'],'Indici Edu',$message,$data['staff_id']);
                }
                else{
                    sms_to_all_staff($message);
                    $data_status['sms_status']=1;
                    $this->db->where('notice_id',str_decode($param2));
                    $this->db->update(get_school_db().'.noticeboard',$data_status);
                }

                $data_status['sms_status']=1;
                $this->db->where('circular_staff_id',str_decode($param2));
                $this->db->update(get_school_db().'.circular_staff',$data_status);
            }

            if(isset($_POST['send_email']) && $_POST['send_email']!="")  {
                if($data['staff_id']!="" || $data['staff_id']>0){
                    $sms_ary=get_sms_detail_staff($data['staff_id']);
                    $email_layout = get_email_layout($message);
                    email_send("No Reply","Indici-Edu",$sms_ary['email'],"Circular",$email_layout,$data['staff_id']);
                }else{

                    $email_layout = get_email_layout($message);
                    email_to_all_staff($email_layout,"Circular");
                    $data_status['sms_status']=1;
                    $this->db->where('circular_id',$param2);
                    $this->db->update(get_school_db().'.circular',$data_status);

                }
                $data_status['sms_status']=1;
                $this->db->where('circular_staff_id',$last_is);
                $this->db->update(get_school_db().'.circular_staff',$data_status);
            }
            redirect(base_url() . 'circular_staff/circulars_staff/');

        }
        /*else if ($param1 == 'edit') {

            $page_data['edit_data'] = $this->db->get_where(get_school_db().'.circular', array(
                'school_id' =>$_SESSION['school_id'],
                'circular_id' => $param2
            ))->result_array();

        }*/
        if ($param1 == 'delete') {
            $this->db->where('school_id',$_SESSION['school_id']);
            $this->db->where('circular_staff_id', str_decode($param2));
            $this->db->delete(get_school_db().'.circular_staff');

            $image_old = $param3;
            $del_location=system_path($image_old,'circular_staff');
            file_delete($del_location);
        }

        $page_data['page_name']  = 'circulars_staff';
        $page_data['page_title'] = get_phrase('manage_circulars');
        //$page_data['package_rights'] = package_rights();
        $this->db->where('school_id',$_SESSION['school_id']);

        $this->load->view('backend/index', $page_data);
    }
    
    function get_class()
    {
        echo  class_option_list($this->input->post('department_id'),$this->input->post('class_id'));
    }

    function get_class_section()
    {
        echo section_option_list($this->input->post('class_id'));
    }
    
    function get_section_student($section_id , $student_id=0)
    {
        $student_select=$this->input->post('$student_select');
        if($this->input->post('section_id')!="")
        {
            echo section_student($this->input->post('section_id') , $this->input->post('student_id'));
        }
    }

    function get_year_term()
    {
        if($this->input->post('acad_year')!="")
        {
            echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'));
        }
    }
    
    function get_year_term2()
    {
        if($this->input->post('acad_year')!="")
        {
            echo $yearly_term=yearly_terms_option_list($this->input->post('acad_year'));
        }
    }
    
    function circular_generator()
    {
        $this->load->view('backend/admin/ajax/get_circular_staff.php',$page_data);
    }

    function term_date_range()
    {
        if(!empty($this->input->post('date1')))
        {
            echo term_date_range($this->input->post('term_id'),$this->input->post('date1'),'');
        }
    }


}
    