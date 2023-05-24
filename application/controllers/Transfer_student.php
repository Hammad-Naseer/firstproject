<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
    //session_start();

class Transfer_student extends CI_Controller{

    function __construct(){
        
        parent::__construct();

        if($_SESSION['user_login'] != 1)
            redirect('login');

        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->menu_ary=array();

        $this->load->helper("student");
        $this->load->helper("branch");
        $this->load->helper('exams');
        
    }

    function transfer_information()
    {
        $page_data['page_name']  = 'transfer_information';
        $page_data['page_title'] = get_phrase('transfer_information');
        $this->load->view('backend/index', $page_data);
    }

    function confirm_transfer_rec($student_id,$transfer_id){

        $school_id=$_SESSION['school_id'];
        $qquuee=$this->db->query(" select ts.to_branch, ts.from_branch, s.image as student_image, s.id_file from ".get_school_db().".student s 
                                   inner join ".get_school_db().".transfer_student  ts on s.student_id=ts.student_id
                                   where ts.status<8 and s.student_id=$student_id ")->result_array();

        $quuus=$this->db->query("select *  from ".get_school_db().".school where school_id=".$qquuee[0]['to_branch'])->result_array();

        $path='uploads/'.$_SESSION['folder_name'].'/student/'.$qquuee[0]['student_image'];
        $spath='uploads/'.$quuus[0]['folder_name'].'/student/'.$qquuee[0]['student_image'];
        $path1='uploads/'.$_SESSION['folder_name'].'/student/'.$qquuee[0]['id_file'];
        $spath1='uploads/'.$quuus[0]['folder_name'].'/student/'.$qquuee[0]['id_file'];

        $this->copy_file_to($path,$spath);
        $this->copy_file_to($path1,$spath1);


        $qurr_r=$this->db->query("  select sp.id_no, sp.id_file as cnic_attachment,sr.s_p_id, sr.relation_id from  ".get_school_db().".student_relation sr
                                    inner join ".get_school_db().".student_parent  sp on sp.s_p_id=sr.s_p_id where sr.student_id=$student_id 
                                    and sr.school_id=".$qquuee[0]['from_branch'])->result_array();

        foreach($qurr_r as $qrow){

            $send_qur=$this->db->query("select id_no,id_file as cnic_attachment from  ".get_school_db().".student_parent  where id_no='".$qrow['id_no']."' and school_id=".$school_id)->result_array();
            if(count($send_qur)>0){
                $s_p_id_in=$send_qur['s_p_id'];
            }
            else
            {
                $this->db->query("INSERT INTO ".get_school_db().".student_parent(`p_name`, `id_no`, `contact`, `occupation`, `id_file`, `school_id`, `account_status`) select `p_name`, `id_no`, `contact`, `occupation`, `id_file`, `school_id`, `account_status` from ".get_school_db().".student_parent where s_p_id=".$send_qur['s_p_id']);
                $s_p_id_in=$this->db->insert_id();

                $path='uploads/'.$_SESSION['folder_name'].'/student/'.$qrow['cnic_attachment'];
                $spath='uploads/'.$quuus[0]['folder_name'].'/student/'.$qrow['cnic_attachment'];
                $this->copy_file_to($path,$spath);
            }

            $this->db->where('relation_id',$qrow['relation_id']);
            $this->db->update(get_school_db().'.student_relation',array('s_p_id'=>$s_p_id_in,'school_id'=>$school_id ));

        }

//parent section end  here

        $this->db->where('transfer_id',$transfer_id);
        $this->db->update(get_school_db().'.transfer_student',array('status'=>6));

        $qqur=$this->db->query("select * from ".get_school_db().".transfer_student where transfer_id=$transfer_id")->result_array();

        $this->db->where('student_id',$student_id);
        $this->db->update(get_school_db().'.student',array('section_id'=>0,'school_id'=>$qqur[0]['to_branch'],'is_transfered'=>2));


        $this->db->where('student_id',$student_id);
        $this->db->update(get_school_db().'.student_chalan_form',array('is_processed'=>1));


        $this->session->set_flashdata('club_updated',get_phrase('transfer_process_completed'));

        redirect(base_url() . "transfer_student/transfer_information");


    }

    function confirm_transfer($student_id,$transfer_id){

        $qqur=$this->db->query("select * from ".get_school_db().".transfer_student where transfer_id=$transfer_id and from_branch=".$_SESSION['school_id']."")->result_array();


        $this->db->where('student_id',$student_id);
        $this->db->update(get_school_db().'.student_chalan_form',array('is_processed'=>1));

        $school_id=$_SESSION['school_id'];
        
        $qquuee=$this->db->query("   select ts.to_branch, ts.from_branch, s.image as student_image, s.id_file from ".get_school_db().".student s 
                                     inner join ".get_school_db().".transfer_student  ts on s.student_id=ts.student_id
                                     where ts.status<8 and s.student_id=$student_id  ")->result_array();

        $quuus=$this->db->query("select *  from ".get_school_db().".school where school_id=".$qquuee[0]['to_branch'])->result_array();

        $path='uploads/'.$_SESSION['folder_name'].'/student/'.$qquuee[0]['student_image'];
        $spath='uploads/'.$quuus[0]['folder_name'].'/student/'.$qquuee[0]['student_image'];
        $path1='uploads/'.$_SESSION['folder_name'].'/student/'.$qquuee[0]['id_file'];
        $spath1='uploads/'.$quuus[0]['folder_name'].'/student/'.$qquuee[0]['id_file'];

        $this->copy_file_to($path,$spath);
        $this->copy_file_to($path1,$spath1);


        $qurr_r=$this->db->query("   select sp.id_no, sp.id_file as cnic_attachment,sr.s_p_id, sr.relation_id from  ".get_school_db().".student_relation sr
                                     inner join ".get_school_db().".student_parent  sp on sp.s_p_id=sr.s_p_id where sr.student_id=$student_id 
                                     and sr.school_id=".$qquuee[0]['from_branch'])->result_array();

        foreach($qurr_r as $qrow){

            $send_qur=$this->db->query("select s_p_id,id_no,id_file as cnic_attachment from  ".get_school_db().".student_parent  where id_no='".$qrow['id_no']."' and school_id=".$qquuee[0]['to_branch'])->result_array();

            if(count($send_qur)>0){
                $s_p_id_in=$send_qur[0]['s_p_id'];
            }
            else
            {

                $this->db->query("INSERT INTO ".get_school_db().".student_parent(`p_name`, `id_no`, `contact`, `occupation`, `id_file`, `school_id`) select `p_name`, `id_no`, `contact`, `occupation`, `id_file`, ".$qquuee[0]['to_branch']." from ".get_school_db().".student_parent where s_p_id=".$qrow['s_p_id']);

                $s_p_id_in=$this->db->insert_id();

                $path='uploads/'.$_SESSION['folder_name'].'/student/'.$qrow['cnic_attachment'];
                $spath='uploads/'.$quuus[0]['folder_name'].'/student/'.$qrow['cnic_attachment'];
                $this->copy_file_to($path,$spath);
            }

            $this->db->where('relation_id',$qrow['relation_id']);
            $this->db->update(get_school_db().'.student_relation',array('s_p_id'=>$s_p_id_in,'school_id'=>$qquuee[0]['to_branch'] ));

        }

//parent section end  here
        $date = new DateTime();
        $transfer_date=$date->format('Y-m-d H:i:s');
        $this->db->where('transfer_id',$transfer_id);
        $this->db->update(get_school_db().'.transfer_student',array(
            'status'=>7,
            'transfered_by'=>$_SESSION['login_detail_id'],
            'transfer_date'=>$transfer_date
        ));

//$qqur=$this->db->query("select * from ".get_school_db().".transfer_student where transfer_id=$transfer_id")->result_array();

        $this->db->where('student_id',$student_id);
        $this->db->update(get_school_db().'.student',array('section_id'=>0,'school_id'=>$qqur[0]['to_branch'],'is_transfered'=>2));


        //////////// transfer files ends


        $this->session->set_flashdata('club_updated',get_phrase('transfer_process_completed'));

        redirect(base_url() . "transfer_student/transfer_information");
    }


    function get_class(){

        $dep_id=$this->input->post('dep_id');
        $sel=$this->input->post('sel');

        echo class_option_list($dep_id,$sel);


    }

    function get_section(){
        $class_id=$this->input->post('class_id');
        $sel=$this->input->post('sel');

        echo section_option_list($class_id,$sel);

    }
    
    
    function copy_file_to($path,$spath){

        if(file_exists($path)){

            if (!copy($path, $spath)) {
                echo "1";
            }
        }
    }
    
    
    function get_student_info(){


        $data['section_id']=$this->input->post('section_id');

        //$data['departments_id']=$this->input->post('departments_id');
//$data['academic_year']=$this->input->post('academic_year');
// $data['departments_id']=$this->input->post('departments_id');

        //$data['class_id']=$this->input->post('class_id');
        $this->load->view("backend/admin/ajax/transfer_ajax",$data);

    }


    function get_rec_info(){
        $data['section_id']=$this->input->post('section_id');
        $data['departments_id']=$this->input->post('departments_id');
        $data['departments_id']=$this->input->post('departments_id');
        $data['class_id']=$this->input->post('class_id');
        $this->load->view("backend/admin/ajax/receive_student_ajax",$data);
    }


    function Transfer_listing_req()
    {
        $page_data['page_name']  	= 'Transfer_listing_req';
        $page_data['page_title']=get_phrase('transfer_listing_req');
        $this->load->view('backend/index', $page_data);
    }

    function get_student_req(){
        $data['from_school']=$this->input->post('from_school');
        $data['to_school']=$this->input->post('to_school');
        $this->load->view("backend/admin/ajax/transfer_ajax_req",$data);
    }


    function student_transfer_form($student_id)
    {
        $page_data['student_id']=$student_id;
        $page_data['page_name']='student_transfer_form';
        $page_data['page_title']=get_phrase('student_transfer_form');
        $this->load->view('backend/index', $page_data);

    }

    function transfer_req_save(){
        $student_id=$this->input->post('student_id');
        $strd_rec=$this->db->query("select is_transfered from ".get_school_db().".student    
where school_id=".$_SESSION['school_id']." AND student_id=$student_id")->result_array();
        $is_transfered=$strd_rec[0]['is_transfered'];
        if($is_transfered==1)
        {
            $this->session->set_flashdata('club_updated',get_phrase('request_already_submitted'));
            redirect(base_url().'c_student/student_information/');
        }
        $section_id=$this->uri->segment(3);
        $data['from_section']=$section_id;
        $data['to_branch']=$this->input->post('school_id');
        $data['from_branch']=$_SESSION['school_id'];
        $date = new DateTime();
        $data['request_date']=$date->format('Y-m-d H:i:s');
        //$data['submit_date']=date('Y-m-d');
        $data['reason']=$this->input->post('reason');
        //$data['submit_by']=$_SESSION['login_detail_id'];
        $data['requested_by']=$_SESSION['login_detail_id'];
        $data['student_id']=$this->input->post('student_id');
        $data['status']=1;
        $this->db->insert(get_school_db().".transfer_student",$data);
        $this->db->where('school_id',$_SESSION['school_id']);
        $this->db->where('student_id',$data['student_id']);
        $this->db->update(get_school_db().'.student',array('is_transfered'=>1));

        $updated_by=$_SESSION['login_detail_id'];
        student_archive($updated_by,$data['student_id']);

        $this->session->set_flashdata('club_updated',get_phrase('request_submitted'));

        redirect(base_url() . "transfer_student/transfer_information/");
    }


    function farward_admin($transfer_id){

        $this->db->where('transfer_id',$transfer_id);
        $this->db->update(get_school_db().'.transfer_student',array('status'=>2));

        $this->session->set_flashdata('club_updated',get_phrase('transfer_request_forwarded_to_admin'));
        redirect(base_url() . "transfer_student/transfer_listing_req");
 
        //redirect(base_url() . "transfer_student/transfer_information");

    }


    function assign_section_receive($student_id){


        $section_id=$this->input->post('section_id');
        $data['section_id']=$section_id;
        $term_id=$this->input->post('term_id');
        $q="SELECT academic_year_id FROM ".get_school_db().".yearly_terms WHERE school_id=".$_SESSION['school_id']." AND yearly_terms_id=$term_id";
        $query=$this->db->query($q)->result_array();

        $data['academic_year_id']  =  $query[0]['academic_year_id'];
        $data['adm_term_id']       =  $term_id;
        $data['reg_num']           =  $this->input->post('regist_num');
        $data['adm_date']          =  $this->input->post('admission_date');
        $data1['status']           =  8;
        $data1['to_section']       =  $section_id;
        $data['roll']              =  $this->input->post('roll');


//section barcode
//$scl_id=$_SESSION['school_id'];
        $bar_code_type = 112;
        $scl_id = $_SESSION['sys_sch_id'];
        $school_id = sprintf("%'06d",$scl_id);
        $std_id = sprintf("%'07d",$student_id);

        $system_id = $bar_code_type.''.$school_id.''.$std_id;
        //$system_id = $student_id.time().'|'.$scl_id.'112';
        $path='uploads/'.$_SESSION['folder_name'].'/student/';

        $data['barcode_image']=$this->set_barcode($system_id,$path);
        $data['system_id']=$system_id;


//section barcode ends here



        $this->db->where(array("student_id"=>$student_id,"school_id"=>$_SESSION['school_id']));

        $this->db->update(get_school_db().".student",$data);


        $this->db->where(array("student_id"=>$student_id,'status'=>7));

        $this->db->update(get_school_db().".transfer_student",$data1);


        student_archive($_SESSION['login_detail_id'],$student_id);
        $this->session->set_flashdata('club_updated', get_phrase('transfer_process_completed'));
        redirect(base_url().'transfer_student/Receiving_transfer_list/');
    }

    private function set_barcode($code,$path)
    {
        if (!is_dir($path)){
            mkdir($path,0777,true);
        }

        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcode = new Zend_Barcode();
        $file = $barcode->draw('Code128', 'image', array('text' => $code,'barHeight'=>20,'drawText'=>TRUE,'withQuietZones'=>FALSE,'orientation'=>0), array());
        $file_name=str_replace('|','_',$code).'.png';
        $store_image = imagepng($file,"$path".$file_name);
        return $file_name;
    }



    function promotion_section($transfer_id){

        $student_status=$this->input->post('student_status');

        $this->db->where('transfer_id',$transfer_id);
        $this->db->update(get_school_db().'.transfer_student',array('status'=>$student_status));


        $this->session->set_flashdata('club_updated',get_phrase('transfer_request_approved'));

        redirect(base_url() . "transfer_student/transfer_information");


    }


    function complete_transfer($student_id){


        $this->db->where('student_id',$student_id);
        $this->db->update(get_school_db().'.student',array('is_transfered'=>3,'student_status'=>10));

        $date = new DateTime();
        $completed_date=$date->format('Y-m-d H:i:s');

        $this->db->where(array('student_id'=>$student_id ,'status'=>10));
        $this->db->update(get_school_db().'.transfer_student',array(
            'status'=>11,
            'completed_by'=>$_SESSION['login_detail_id'],
            'completed_date'=>$completed_date
        ));


        $updated_by=$_SESSION['login_detail_id'];


        student_archive($updated_by,$student_id);


        $this->session->set_flashdata('club_updated',get_phrase('student_transfered_sussessfullty'));

        redirect(base_url().'transfer_student/Receiving_transfer_list');



    }






    function cancel_transfer($student_id=0,$transfer_id=0 , $s_c_f_id=0)
    {
        /* Transfer cancel start */
        $school_id = $_SESSION['school_id'];


        $transfer_student_str = "SELECT s_c_f.s_c_f_id, s_c_f.status FROM ".get_school_db().".student_chalan_form as s_c_f
                                            INNER JOIN ".get_school_db().".transfer_student as s_t
                                            on s_c_f.s_c_f_id = s_t.s_c_f_id
                                            WHERE
                                            s_t.transfer_id = ".$transfer_id." AND
                                                                        s_c_f.school_id = " . $_SESSION['school_id'] . "";

        $transfer_student_query = $this->db->query($transfer_student_str)->row();

        //exit;
        if( count($transfer_student_query)>0)
        {
            $s_c_f_id = $transfer_student_query->s_c_f_id;
            if ($transfer_student_query->status == 4)
            {
                /*   $student_c_f_str =  "SELECT * FROM ".get_school_db().".transfer_student as s_t
                             INNER JOIN ".get_school_db().".student_chalan_form as s_c_f
                             on
                             s_t.student_id = s_c_f.student_id
                             INNER JOIN ".get_school_db().".student_chalan_detail as s_c_f_d
                             ON s_c_f.s_c_f_id = s_c_f_d.s_c_f_id
                             WHERE
                             s_t.student_id = ".$student_id."
                             AND
                             s_t.transfer_id = ".$transfer_id."
                             AND
                             s_c_f.form_type = 5";*/

                $student_c_f_str = "SELECT scd.s_c_d_id, scf.student_id , scf.s_c_f_id , scd.fee_type_title , scd.amount , scd.school_id, scf.chalan_form_number,
                                                                    scd.cancel_dr_coa_id ,scd.cancel_cr_coa_id , scf.student_name ,scd.type
                                                                    FROM
                                                                        " . get_school_db() . ".student_chalan_form as scf
                                                                    INNER JOIN
                                                                        " . get_school_db() . ".student_chalan_detail as scd
                                                                    ON
                                                                        scf.s_c_f_id = scd.s_c_f_id
                                                                    WHERE
                                                                        scd.s_c_f_id = " . $s_c_f_id . "
                                                                    AND
                                                                        scd.school_id = " . $_SESSION['school_id'] . "
                                                                   ";

                $student_c_f_query = $this->db->query($student_c_f_str)->result();
                // echo "<pre>";
                //print_r($student_c_f_query);

                if (count($student_c_f_query) > 0) {
                    $date = new DateTime();
                    $entry_date = $date->format('Y-m-d H:i:s');
                    foreach ($student_c_f_query as $row)
                    {
                        if ($row->amount > 0)
                        {

                            $transaction_detail = student_name_section($row->student_id);
                            $journal_entry = 0;
                            if ($row->type == 2 || $row->type == 4)
                            {
                                $journal_entry = 1;
                                $amount = $row->amount;
                            }
                            elseif ($row->type == 1 || $row->type == 5)
                            {
                                $journal_entry = 1;
                                $amount = (-1) * ($row->amount);
                            }
                            else if($row->type == 3 || $row->type == 6)
                            {
                                $journal_entry = 0;

                            }

                            if($journal_entry == 1)
                            {
                                $data_debit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
                                        . " - ".get_phrase('cancelled_challan_form ')." - " . $row->chalan_form_number . " - " .get_phrase('for')." - " . $transaction_detail,
                                    'debit' => $amount,
                                    'entry_type' => 2,
                                    'type_id' => $row->s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->cancel_dr_coa_id
                                );
                                //'entry_date' => CURDATE(),
                                $data_credit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
                                        . " - ".get_phrase('cancelled_challan_form ')." - " . $row->chalan_form_number . " - ".get_phrase('for')." " . $transaction_detail,
                                    'credit' => $amount,
                                    'entry_type' => 2,
                                    'type_id' => $row->s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->cancel_cr_coa_id
                                );

                                //s_c_f_id=$s_c_f_id
                                $this->db->insert(get_school_db() . ".journal_entry", $data_debit);
                                $this->db->insert(get_school_db() . ".journal_entry", $data_credit);
                            }
                        }
                    }
                }
            }

            /* $delete_s_c_d_str = "DELETE FROM " . get_school_db() . ".student_chalan_detail
                                                        where s_c_f_id = " . $s_c_f_id . "
                                                        AND school_id=$school_id";
             $this->db->query($delete_s_c_d_str);

             $delete_s_c_f_str = "DELETE FROM " . get_school_db() . ".student_chalan_form
                                                WHERE school_id=$school_id
                                                AND s_c_f_id = " . $s_c_f_id . "
                                                AND status<5";
             $this->db->query($delete_s_c_f_str); no change*/


            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form
                                    SET is_cancelled = 1,
                                    cancelled_by = ".$_SESSION['login_detail_id'].",
                                    cancel_date = '".$entry_date."'
                                    where
                                    s_c_f_id=$s_c_f_id
                                    AND
                                    school_id=" . $_SESSION['school_id'] . "
                                    AND
                                    status<5 ");
            // exit('it is transofered');



        }

        $this->db->where('student_id',$student_id);
        $this->db->update(get_school_db().'.student',array('is_transfered'=>0));

        $updated_by=$_SESSION['login_detail_id'];
        student_archive($updated_by,$student_id);

        $this->db->query("delete from ".get_school_db().".transfer_student where transfer_id=$transfer_id");

        $this->session->set_flashdata('club_updated',get_phrase('student_transfer_request_canceled_sussessfullty'));

        /* Transfer cancel start */
        redirect(base_url().'transfer_student/transfer_information');
    }



    function cancel_recieve_transfer($student_id,$transfer_id)
    {


        /* recieving Cancel cancel start */



        $school_id = $_SESSION['school_id'];
        $transfer_student_str = "SELECT s_c_f.s_c_f_id, s_c_f.status FROM ".get_school_db().".student_chalan_form as s_c_f
                                            INNER JOIN ".get_school_db().".transfer_student as s_t
                                            on s_c_f.s_c_f_id = s_t.r_s_c_f_id
                                            WHERE
                                            s_t.transfer_id = ".$transfer_id." AND
                                                                        s_c_f.school_id = " . $_SESSION['school_id'] . "";

        $transfer_student_query = $this->db->query($transfer_student_str)->row();


        if( count($transfer_student_query)>0)
        {

            $s_c_f_id = $transfer_student_query->s_c_f_id;
            if ($transfer_student_query->status == 4)
            {

                $student_c_f_str = "SELECT scd.s_c_d_id, scf.s_c_f_id , scd.fee_type_title , scd.amount , scd.school_id, scf.chalan_form_number,
                                                                    scd.cancel_dr_coa_id ,scd.cancel_cr_coa_id , scf.student_name , scd.type
                                                                    FROM
                                                                        " . get_school_db() . ".student_chalan_form as scf
                                                                    INNER JOIN
                                                                        " . get_school_db() . ".student_chalan_detail as scd
                                                                    ON
                                                                        scf.s_c_f_id = scd.s_c_f_id
                                                                    WHERE
                                                                        scd.s_c_f_id = " . $s_c_f_id . "
                                                                    AND
                                                                        scd.school_id = " . $_SESSION['school_id'] . "
                                                                   ";

                $student_c_f_query = $this->db->query($student_c_f_str)->result();


                if (count($student_c_f_query) > 0) {
                    $date = new DateTime();
                    $entry_date = $date->format('Y-m-d H:i:s');
                    foreach ($student_c_f_query as $row) {
                        if ($row->amount > 0) {
                            $transaction_detail = student_name_section($row->student_id);
                            $journal_entry = 0;
                            if ($row->type == 2 || $row->type == 4)
                            {
                                $journal_entry = 1;
                                $amount = $row->amount;
                            }
                            elseif ($row->type == 1 || $row->type == 5)
                            {
                                $journal_entry = 1;
                                $amount = (-1) * ($row->amount);
                            }
                            else if($row->type == 3 || $row->type == 6)
                            {
                                $journal_entry = 0;

                            }

                            if($journal_entry == 1)
                            {
                                $data_debit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
										. " - ".get_phrase('cancelled_challan_form ')." - " . $row->chalan_form_number . " - ".get_phrase('for')." - " . $transaction_detail,
                                    'debit' => $amount,
                                    'entry_type' => 2,
                                    'type_id' => $row->s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->cancel_dr_coa_id
                                );
                                //'entry_date' => CURDATE(),
                                $data_credit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
										. " - ".get_phrase('cancelled_challan_form ')." - " . $row->chalan_form_number . " - ".get_phrase('for')." - " . $transaction_detail,
                                    'credit' => $amount,
                                    'entry_type' => 2,
                                    'type_id' => $row->s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->cancel_cr_coa_id
                                );

                                //s_c_f_id=$s_c_f_id
                                $this->db->insert(get_school_db() . ".journal_entry", $data_debit);
                                $this->db->insert(get_school_db() . ".journal_entry", $data_credit);
                            }

                        }
                    }
                }
            }

            $delete_s_c_d_str = "DELETE FROM " . get_school_db() . ".student_chalan_detail
                                                                where s_c_f_id = " . $s_c_f_id . "
                                                                AND school_id=$school_id";
            $this->db->query($delete_s_c_d_str);

            $delete_s_c_f_str = "DELETE FROM " . get_school_db() . ".student_chalan_form
                                                        WHERE school_id=$school_id
                                                        AND s_c_f_id = " . $s_c_f_id . "
                                                        AND status<5";
            $this->db->query($delete_s_c_f_str);

        }



        $this->db->where('transfer_id',$transfer_id);
        $this->db->update(get_school_db().'.transfer_student',array('status'=>7 , 'r_s_c_f_id'=>0));
        $this->session->set_flashdata('club_updated',get_phrase('student_recieve_request_canceled_sussessfullty'));

        /* Transfer cancel start */
        redirect(base_url().'transfer_student/receiving_transfer_list');
    }




    function check_chalan($student_id,$return_link,$form_type,$transfer_id){
        
        $student_id = str_decode($student_id);
        $school_id=$_SESSION['school_id'];
        $qur_check=$this->db->query("select * from ".get_school_db().".student_chalan_form 
                                     where school_id=$school_id and student_id=$student_id and form_type=$form_type 
                                     and status<5 and is_processed=0")->result_array();

        if(count($qur_check)>0){
            redirect(base_url() . "class_chalan_form/edit_chalan_form/".$qur_check[0]['s_c_f_id']."/".$return_link);
        }
        else{
            redirect(base_url()."class_chalan_form/student_chalan_form/".str_encode($student_id).'/'.$form_type.'/'.$return_link.'/'.$transfer_id);
        }


    }



    function check_chalan_rec($student_id,$form_type){



        $school_id=$_SESSION['school_id'];


        $qur_check=$this->db->query("select * from ".get_school_db().".student_chalan_form where school_id=$school_id and student_id=$student_id and form_type=$form_type and status=7 and is_processed=0")->result_array();


        if(count($qur_check)>0){


            redirect(base_url() . "class_chalan_form/edit_chalan_form/".$qur_check[0]['s_c_f_id']);

        }

        else{


            redirect(base_url() . "class_chalan_form/student_chalan_form/".$student_id.'/'.$form_type);

        }







    }



    function receiving_transfer_list(){

        //exit('exit');
        $page_data['page_name']  	= 'receiving_transfer_list';
        $page_data['page_title']=get_phrase('receiving_transfer_list');

        $this->load->view('backend/index', $page_data);

    }

}	