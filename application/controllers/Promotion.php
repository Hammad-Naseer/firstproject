<?php
    if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    //session_start();

    class Promotion extends CI_Controller
    {

    function __construct()
    {
        parent::__construct();

        if ($_SESSION['user_login'] != 1)
            redirect('login');

        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->menu_ary = array();

    }

    function promotion_listing()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');

        $page_data['page_name'] = 'promotion_listing';
        $page_data['page_title'] = get_phrase('promotion_listing');
        $this->load->view('backend/index', $page_data);
    }

    function get_student_info()
    {
        $this->load->view("backend/admin/ajax/promotion_list_ajax");
    }

    function get_class()
    {
        echo class_option_list($this->input->post('dep_id'), $this->input->post('sel'));
    }

    function get_section()
    {
        echo section_option_list($this->input->post('class_id'), $this->input->post('sel'));
    }

    function save_promotion_req()
    {
        $section_id = $this->input->post('section_id');
        $school_id = $_SESSION['school_id'];
        $p = $this->db->query("SELECT section_id FROM " . get_school_db() . ".student where section_id=$section_id  and student_status IN (" . student_query_status() . ") and school_id=$school_id ")->result_array();

        if (count($p) == 0)
        {
            echo "<div align='center' class='alert alert-success'>" . get_phrase('no_student_found') . "</div>";
            exit;
        }

        $section_id_p = $this->input->post('section_id_p');
        $academic_year_id = $this->input->post('acad_year');
        $academic_year_id_p = $this->input->post('acad_year_p');

        $data_r['section_id'] = $section_id;
        $data_r['academic_year_id'] = $academic_year_id;
        $data_r['pro_section_id'] = $section_id_p;
        $data_r['pro_academic_year_id'] = $academic_year_id_p;
        $data_r['school_id'] = $school_id;
        $data_r['user_id'] = $_SESSION['login_detail_id'];
        $data_r['activity'] = 1;
        $data_r['status'] = 1;
        $date = new DateTime();
        $data_r['date_time'] = $date->format('Y-m-d H:i:s');

        $query_r333 = $this->db->query("SELECT ccf.c_c_f_id ,ccf.due_days
            FROM  " . get_school_db() . ".fee_types
            ft inner join " . get_school_db() . ".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id inner join " . get_school_db() . ".class_chalan_form ccf on
            ccf.c_c_f_id= ccfe.c_c_f_id
            where ccf.section_id=$section_id_p and ccf.type=3 and ccf.status=1 and ccf.school_id=$school_id   
            ORDER BY ccfe.order_num")->result_array();
        $data_r['c_c_f_id'] = $query_r333[0]['c_c_f_id'];
        $due_days = $query_r333[0]['due_days'];
        if ($data_r['c_c_f_id'] == "") {
            echo "<div align='center' class='alert alert-success'>" . get_phrase('chalan_form_not_found') . "</div>";
            exit;
        }
        $query_check = $this->db->query("SELECT * from " . get_school_db() . ".bulk_request where section_id=$section_id  and status=1  and school_id=$school_id")->result_array();

        if (count($query_check) == 0)
        {
            $this->db->insert(get_school_db() . ".bulk_request", $data_r);
            $bulk_req_id = $this->db->insert_id();
            $this->db->query("update " . get_school_db() . ".student set pro_section_id=$section_id_p , pro_academic_year_id=$academic_year_id_p, bulk_req_id=$bulk_req_id , student_status=11 where section_id=$section_id  and student_status IN (" . student_query_status() . ") and school_id=$school_id "); // (10,14,18)

            $this->load->helper("student");
            $query = $this->db->query("select * from " . get_school_db() . ".student where pro_section_id=$section_id_p and pro_academic_year_id=$academic_year_id_p and student_status=11 and school_id=$school_id and bulk_req_id=$bulk_req_id")->result_array();

            if (count($query) > 0)
            {
                foreach ($query as $row) {
                    student_archive($_SESSION['login_detail_id'], $row['student_id']);
                    $this->insert_chalan($row['student_id'], 3, $bulk_req_id);

                }
                echo "<div align='center' class='alert alert-success'>" . get_phrase('promotion_request_generated_successfully') . "</div>";
            } else
            {
                echo "<div align='center' class='alert alert-success'>" . get_phrase('no_student_found') . "</div>";
            }
        } else {
            echo "<div align='center' class='alert alert-success'>" . get_phrase('request_already_exists') . "</div>";
        }
    }

    //////////////////////////////////////////////////////////////////
    function insert_chalan($student_id, $form_type, $bulk_req_id = 0)
    {
        $school_id = $_SESSION['school_id'];
        $query_res = $this->db->query("SELECT 
                                        s.name as student_name,
                                        s.barcode_image,
                                        s.sex as gender,
                                        s.roll,
                                        s.section_id,
                                        s.pro_section_id,
                                        s.academic_year_id,
                                        s.pro_academic_year_id,
                                        cs.title as section_nme,
                                        cc.name as class_name,
                                        dd.title as department_name
                                        FROM " . get_school_db() . ".student s 
                                        inner join " . get_school_db() . ".class_section cs on s.section_id=cs.section_id  
                                        inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id
                                        inner join " . get_school_db() . ".departments dd on dd.departments_id=cc.departments_id
                                        where s.student_id=$student_id")->result_array();

        $query_res_p_name = $this->db->query("SELECT 
                                                sp.p_name as parent_name
                                                FROM " . get_school_db() . ".student s 
                                                inner join " . get_school_db() . ".student_relation sr on sr.student_id=s.student_id 
                                                inner join " . get_school_db() . ".student_parent sp on sp.s_p_id=sr.s_p_id
                                                where s.student_id=$student_id and sr.relation='f'")->result_array();

        $section_id = $query_res[0]['section_id'];
        if ($form_type == 3) {
            $section_id = $query_res[0]['pro_section_id'];
        }
        $query_re = $this->db->query("SELECT 
                                        $student_id as sid, 
                                        ft.fee_type_id, 
                                        ft.title, ccfe.order_num,
                                        ccfe.value,
                                        ccf.c_c_f_id,
                                        ccf.due_days
                                        FROM  " . get_school_db() . ".fee_types
                                        ft inner join " . get_school_db() . ".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id 
                                        inner join " . get_school_db() . ".class_chalan_form ccf on ccf.c_c_f_id= ccfe.c_c_f_id
                                        where ccf.section_id=$section_id 
                                        and ccf.type=$form_type 
                                        and ccf.status=1 
                                        and ccf.school_id=$school_id   
                                        ORDER BY ccfe.order_num")->result_array();

        if (count($query_re) == 0)
        {
            return 0;
        }
        else
        {
            $chalan_setting = $this->db->query("select * from " . get_school_db() . ".chalan_settings where school_id=$school_id")->result_array();
            $due_days = $query_re[0]['due_days'];
            $data = array();
            $data['c_c_f_id'] = $query_re[0]['c_c_f_id'];
            $data['school_id'] = $school_id;
            $data['status'] = 1;
            $data['fee_month_year'] = date("Y-m-d");
            $data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $data['generation_date'] = $date->format('Y-m-d H:i:s');
            $data['chalan_form_number'] = chalan_form_number();
            $data['student_id'] = $student_id;
            $data['student_name'] = $query_res[0]['student_name'];
            $data['roll'] = $query_res[0]['roll'];
            //$data['father_name']=$query_res_p_name[0]['parent_name'];
            $data['bar_code'] = $query_res[0]['barcode_image'];
            $data['school_name'] = $chalan_setting[0]['school_name'];
            $data['school_logo'] = $chalan_setting[0]['logo'];
            $data['school_address'] = $chalan_setting[0]['address'];
            $data['school_terms'] = $chalan_setting[0]['terms'];
            $data['school_bank_detail'] = $chalan_setting[0]['bank_details'];
            $data['section'] = $query_res[0]['section_nme'];
            $data['class'] = $query_res[0]['class_name'];
            $data['bulk_req_id'] = $bulk_req_id;
            $data['department'] = $query_res[0]['department_name'];
            $data['form_type'] = $form_type;
            $data['due_days'] = $due_days;
            $data['is_bulk'] = 1;

            $this->db->insert(get_school_db() . '.student_chalan_form', $data);
            $s_c_f_id = $this->db->insert_id();

            $totle = 0;

            foreach ($query_re as $rec_row1)
            {
                $data_detail1 = array();
                $data_detail1['s_c_f_id'] = $s_c_f_id;
                $data_detail1['fee_type_title'] = $rec_row1['title'];
                $data_detail1['school_id'] = $school_id;
                $data_detail1['amount'] = $rec_row1['value'];
                $data_detail1['type'] = 1;
                $data_detail1['fee_type_id'] = $rec_row1['fee_type_id'];
                $data_detail1['issue_dr_coa_id'] = $rec_row1['issue_dr_coa_id'];

                /* Get fees types id start */

                $query_fee_type = $this->db->query("SELECT * FROM " . get_school_db() . ".fee_types
                                                            where fee_type_id =" . $data_detail1['fee_type_id']."
                                                            AND school_id=$school_id")->row();

                $data_detail1['generate_dr_coa_id'] = $query_fee_type->generate_dr_coa_id;
                $data_detail1['generate_cr_coa_id'] = $query_fee_type->generate_cr_coa_id;

                $data_detail1['issue_dr_coa_id'] = $query_fee_type->issue_dr_coa_id;
                $data_detail1['issue_cr_coa_id'] = $query_fee_type->issue_cr_coa_id;

                $data_detail1['receive_dr_coa_id'] = $query_fee_type->receive_dr_coa_id;
                $data_detail1['receive_cr_coa_id'] = $query_fee_type->receive_cr_coa_id;

                $data_detail1['cancel_dr_coa_id'] = $query_fee_type->issue_cr_coa_id;
                $data_detail1['cancel_cr_coa_id'] = $query_fee_type->issue_dr_coa_id;
                /* Get fees types id End */

                $totle = $rec_row1['value'] + $totle;
                $this->db->insert(get_school_db() . ".student_chalan_detail", $data_detail1);

            }

            $query_rec = $this->db->query("SELECT dl.discount_id,dl.title,ccd.value,ccd.order_num
                                            FROM " . get_school_db() . ".discount_list dl 
                                            INNER JOIN " . get_school_db() . ".class_chalan_discount ccd 
                                                on ccd.discount_id=dl.discount_id
                                            INNER JOIN " . get_school_db() . ".class_chalan_form ccf 
                                                on ccf.c_c_f_id= ccd.c_c_f_id
                                             WHERE ccf.section_id=$section_id 
                                                AND ccf.type=$form_type 
                                                AND ccf.status=1 
                                                AND ccf.school_id=$school_id 
                                                ORDER BY ccd.order_num")->result_array();

            foreach ($query_rec as $rec_row)
            {
                $data_detail = array();
                $data_detail['s_c_f_id'] = $s_c_f_id;
                $data_detail['fee_type_title'] = $rec_row['title'];
                $data_detail['school_id'] = $school_id;
                $amount = $rec_row['value'];
                $data_detail['type'] = 2;
                $data_detail['fee_type_id'] = $rec_row['discount_id'];
                //is_percentage=$rec_row['is_percentage'];
                /*
                if($is_percentage==1){
               $data_detail['amount']=($amount/100)*$totle;
                }else{
                    }
                */
                $data_detail['amount'] = $rec_row['value'];

                $totle = $totle - $data_detail['amount'];
                $this->db->insert(get_school_db() . ".student_chalan_detail", $data_detail);
            }
            $update_amount['actual_amount'] = $totle;
            $this->db->where("s_c_f_id", $s_c_f_id);
            $this->db->update(get_school_db() . ".student_chalan_form", $update_amount);
            return $s_c_f_id;

        }

    }

    function bulk_condition()
    {

        $activity = $this->input->post('promotion_status');
        $due_days = $this->input->post('due_days');
        //$start_arry=explode('/',$due_days);
        //$data['create_timestamp']=$start_arry[2].'-'.$start_arry[1].'-'.$start_arry[0];
        $bulk_req_id = $this->input->post('bulk_req_id');

        $school_id = $_SESSION['school_id'];
        if ($activity == 1) {

            $s_c_f_data['status'] = $activity;

            $s_c_f_data['is_bulk'] = 1;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];

            $date = new DateTime();

            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');

            $this->db->where(array('bulk_req_id' => $bulk_req_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);

        } elseif ($activity == 2) {

            $s_c_f_data_s['status'] = $activity;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');
            $s_c_f_data_a['activity'] = $activity;
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_request", $s_c_f_data_a);
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data_s);

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);

        } elseif ($activity == 3) {

            $s_c_f_data_s['status'] = $activity;
            $s_c_f_data['is_bulk'] = 1;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data_s);

            $s_c_f_data_a['activity'] = $activity;
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_request", $s_c_f_data_a);

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);

            $date = new DateTime();
            $data_a['approval_date'] = $date->format('Y-m-d H:i:s');
            $data_a['approved_by'] = $_SESSION['login_detail_id'];

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'approved_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $data_a);

        } elseif ($activity == 4) {

            /* Entries in journal table Start*/
            $bulk_str = "SELECT * FROM " . get_school_db() . ".student_chalan_form WHERE bulk_req_id = " . $bulk_req_id . "
                        AND school_id = " . $_SESSION['school_id'] . " AND is_bulk = 1";

            $bulk__row = $this->db->query($bulk_str)->result();
            if (count($bulk__row) > 0) {
                foreach ($bulk__row as $blk_row) {

                    $s_c_f_id = $blk_row->s_c_f_id;

                    $date = new DateTime();
                    $entry_date = $date->format('Y-m-d H:i:s');
                    $challan_form_str = "SELECT scd.s_c_d_id, scf.student_id, scd.fee_type_title , scd.amount , scd.school_id, scf.chalan_form_number,
                                                    scd.issue_dr_coa_id ,scd.issue_cr_coa_id , scf.student_name , scd.type
                                                    FROM
                                                        " . get_school_db() . ".student_chalan_form as scf
                                                    INNER JOIN
                                                        " . get_school_db() . ".student_chalan_detail as scd
                                                    ON
                                                        scf.s_c_f_id = scd.s_c_f_id
                                                    WHERE
                                                        scd.s_c_f_id = " . $s_c_f_id . "
                                                    AND
                                                        scd.school_id = " . $_SESSION['school_id'] . "";


                    $query_challan_form = $this->db->query($challan_form_str)->result();
                    if (count($query_challan_form) > 0) {


                        foreach ($query_challan_form as $row) {
                            if ($row->amount > 0) {
                                $transaction_detail = student_name_section($row->student_id);

                                if ($row->type == 2) {
                                    $amount_issued = $row->amount;//(-1) * ($row->amount);
                                } else {
                                    $amount_issued = $row->amount;
                                }

                                $data_debit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
                                        . " - " . get_phrase('issued_challan_form') . "-" . $row->chalan_form_number . " - " . get_phrase('to') . " " . $transaction_detail,
                                    'debit' => $amount_issued,
                                    'entry_type' => 1,
                                    'type_id' => $s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->issue_dr_coa_id
                                );
                                //'entry_date' => CURDATE(),
                                $data_credit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
                                        . " - " . get_phrase('issued_challan_form') . " " . $row->chalan_form_number . " - " . get_phrase('student_name') . " " . $row->student_name . " " . get_phrase('to') . " " . $transaction_detail,
                                    'credit' => $amount_issued,
                                    'entry_type' => 1,
                                    'type_id' => $s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->issue_cr_coa_id
                                );

                                //s_c_f_id=$s_c_f_id
                                $this->db->insert(get_school_db() . ".journal_entry", $data_debit);
                                $this->db->insert(get_school_db() . ".journal_entry", $data_credit);
                            }


                        }

                    }


                }
            }


            /* Entries in journal table End*/


            $s_c_f_data_s['status'] = $activity;
            $s_c_f_data['is_bulk'] = 1;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data_s);

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);

            $date = new DateTime();
            $data_a['approval_date'] = $date->format('Y-m-d H:i:s');


            $data_a['approved_by'] = $_SESSION['login_detail_id'];

            $s_c_f_data_a['activity'] = $activity;


            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_request", $s_c_f_data_a);


            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'approved_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $data_a);

            $issue_date = $date->format('Y-m-d H:i:s');
            $issued_by = $_SESSION['login_detail_id'];

            $date = date('Y-m-d', strtotime('+' . $due_days . ' days'));


            $date1 = date_slash($this->input->post('due_date'));
            $this->db->query("UPDATE  " . get_school_db() . ".student_chalan_form SET  `issue_date` ='$issue_date',
                `issued_by` =$issued_by,
                `due_date` ='$date1' WHERE  `bulk_req_id` =  $bulk_req_id AND  `issued_by` =0 AND `status`<5 AND is_bulk=1 AND `school_id` =  $school_id");


        }
        elseif ($activity == 5)
        {

            /* Received bulk Entries  in journal table Start*/

            $bulk_str = "SELECT * FROM " . get_school_db() . ".student_chalan_form  WHERE bulk_req_id = " . $bulk_req_id . "
                         AND school_id = " . $_SESSION['school_id'] . " AND is_bulk = 1";
            $bulk__row = $this->db->query($bulk_str)->result();
            
            if (count($bulk__row) > 0) {

                foreach ($bulk__row as $blk_row) {

                    $s_c_f_id = $blk_row->s_c_f_id;

                    $date = new DateTime();
                    $entry_date = $date->format('Y-m-d H:i:s');
                    $challan_form_str = "SELECT scd.s_c_d_id, scf.student_id, scd.fee_type_title , scd.amount , scd.school_id, scf.chalan_form_number,
                                                    scd.receive_dr_coa_id ,scd.receive_cr_coa_id ,scd.type , scd.issue_dr_coa_id ,scd.issue_cr_coa_id , scf.student_name
                                                    FROM
                                                        " . get_school_db() . ".student_chalan_form as scf
                                                    INNER JOIN
                                                        " . get_school_db() . ".student_chalan_detail as scd
                                                    ON
                                                        scf.s_c_f_id = scd.s_c_f_id
                                                    WHERE
                                                        scd.s_c_f_id = " . $s_c_f_id . "
                                                    AND
                                                        scd.school_id = " . $_SESSION['school_id'] . "";


                    $query_challan_form = $this->db->query($challan_form_str)->result();
                    if (count($query_challan_form) > 0) {
                        foreach ($query_challan_form as $row)
                        {
                            if ($row->amount > 0)
                            {
                                $transaction_detail = student_name_section($row->student_id);

                                /* Cash balance start */
                                if ($row->type != 6) // no balance entry for other amount
                                {
                                    /* Cash balance start */

                                    if ($row->type == 2)
                                    {
                                        $amount = $row->amount;
                                    }
                                    elseif ($row->type == 3)
                                    {
                                        $amount = $row->amount;
                                    } else
                                    {
                                        $amount = $row->amount; //(-1) * ($row->amount);
                                    }

                                    /* $balance_data_debit = array(
                                         'entry_date' => $entry_date,
                                         'detail' => $row->fee_type_title
                                             ." - ".get_phrase('balanced_challan_form')." " . $row->chalan_form_number . " - ".get_phrase('student_name')." ".$row->student_name." ".get_phrase('from') ." " .$transaction_detail,
                                         'debit' => $amount,
                                         'entry_type' => 4,
                                         'type_id' => $s_c_f_id,
                                         'school_id' => $row->school_id,
                                         'coa_id' => $row->issue_dr_coa_id
                                     );
                                     //'entry_date' => CURDATE(),
                                     $balance_data_credit = array(
                                         'entry_date' => $entry_date,
                                         'detail' => $row->fee_type_title
                                             ." - ".get_phrase('balanced_challan_form')." " . $row->chalan_form_number . " - ".get_phrase('student_name')." ".$row->student_name." ".get_phrase('from') ." " .$transaction_detail,
                                         'credit' => $amount,
                                         'entry_type' => 4,
                                         'type_id' => $s_c_f_id,
                                         'school_id' => $row->school_id,
                                         'coa_id' => $row->issue_cr_coa_id
                                     );
                                     $this->db->insert(get_school_db() . ".journal_entry", $balance_data_debit);
                                     $this->db->insert(get_school_db() . ".journal_entry", $balance_data_credit);*/
                                }
                                /* Cash balance End */
                                if ($row->type == 2)
                                {
                                    $amount_recieved = $row->amount; //(-1) * ($row->amount);
                                } else
                                {
                                    $amount_recieved = $row->amount;
                                }

                                $data_debit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
                                        . " - " . get_phrase('received_challan_form') . " - " . $row->chalan_form_number . " - ". get_phrase('from') . " - " . $transaction_detail,
                                    'debit' => $amount_recieved,
                                    'entry_type' => 3,
                                    'type_id' => $s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->receive_dr_coa_id
                                );
                                //'entry_date' => CURDATE(),
                                $data_credit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
                                        . " - " . get_phrase('received_challan_form') . " - " . $row->chalan_form_number . " - ". get_phrase('from') . " - " . $transaction_detail,
                                    'credit' => $amount_recieved,
                                    'entry_type' => 3,
                                    'type_id' => $s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->receive_cr_coa_id
                                );

                                //s_c_f_id=$s_c_f_id
                                $this->db->insert(get_school_db() . ".journal_entry", $data_debit);
                                $this->db->insert(get_school_db() . ".journal_entry", $data_credit);
                            }


                        }

                    }


                }
            }

        /* Received bulk  Entries in journal table End*/

            $s_c_f_data_s['status'] = $activity;
            $s_c_f_data['is_bulk'] = 1;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);

            $date = new DateTime();
            $data_a['approval_date'] = $date->format('Y-m-d H:i:s');
            $data_a['approved_by'] = $_SESSION['login_detail_id'];

            $s_c_f_data_a['activity'] = $activity;
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_request", $s_c_f_data_a);

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'approved_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $data_a);

            $issue_date = $date->format('Y-m-d H:i:s');

            $issued_by = $_SESSION['login_detail_id'];

            $date = date('Y-m-d', strtotime('+' . $due_days . ' days'));

            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET  `issue_date` ='$issue_date',
                        `issued_by` =$issued_by,
                        `due_date` ='$date' WHERE  `bulk_req_id` =  $bulk_req_id AND  `issued_by` =0 AND status<5 and is_bulk=1 and `school_id` =  $school_id");


            $received_by = $_SESSION['login_detail_id'];


            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET status=5, `received_date` ='$issue_date', payment_date='$issue_date' ,received_amount=actual_amount,
                        `received_by` =$received_by WHERE  `bulk_req_id` =  $bulk_req_id AND  `received_by` =0 AND status<5 AND is_bulk=1 and `school_id` =  $school_id");
            /*
            $this->db->where(array("bulk_req_id"=>$bulk_req_id,'school_id'=>$school_id,'status <'=>5,'is_bulk'=>1));
            $this->db->update(get_school_db().".student_chalan_form",$s_c_f_data_s);
            echo $this->db->last_query();
            echo "<br>";
            */

        }
        elseif ($activity == 8)
        {

            /* Confirms bulk bulk Entries  in journal table Start*/
            //$bulk_req_id

            /*  $bulk_str =  "SELECT * FROM ".get_school_db().".student_chalan_form
                                              WHERE bulk_req_id = ".$bulk_req_id."
                                              AND school_id = " . $_SESSION['school_id']."
                                              AND is_bulk = 1";


              $bulk__row =  $this->db->query($bulk_str)->result();


              if(count($bulk__row)>0) {


                  foreach ($bulk__row as $blk_row) {


                      $s_c_f_id = $blk_row->s_c_f_id;

                      $date = new DateTime();
                      $entry_date = $date->format('Y-m-d H:i:s');
                      $challan_form_str = "SELECT scd.s_c_d_id, scd.fee_type_title , scd.amount , scd.school_id, scf.chalan_form_number,
                                                              scd.receive_dr_coa_id ,scd.receive_cr_coa_id , scd.issue_dr_coa_id ,scd.issue_cr_coa_id , scf.student_name
                                                              FROM
                                                                  " . get_school_db() . ".student_chalan_form as scf
                                                              INNER JOIN
                                                                  " . get_school_db() . ".student_chalan_detail as scd
                                                              ON
                                                                  scf.s_c_f_id = scd.s_c_f_id
                                                              WHERE
                                                                  scd.s_c_f_id = " . $s_c_f_id . "
                                                              AND
                                                                  scd.school_id = " . $_SESSION['school_id'] . "";


                      $query_challan_form = $this->db->query($challan_form_str)->result();
                      if(count($query_challan_form)>0) {


                          foreach ($query_challan_form as $row) {
                              if ($row->amount > 0) {

                                  $data_debit_issue = array(
                                      'entry_date' => $entry_date,
                                      'detail' => $row->fee_type_title
                                          . " - Issue Challan Form Student Name is ".$row->student_name." and form no. " . $row->chalan_form_number . " from " . $row->student_name,
                                      'debit' => $row->amount,
                                      'entry_type' => 1,
                                      'type_id' => $s_c_f_id,
                                      'school_id' => $row->school_id,
                                      'coa_id' => $row->issue_dr_coa_id
                                  );
                                  //'entry_date' => CURDATE(),
                                  $data_credit_issue = array(
                                      'entry_date' => $entry_date,
                                      'detail' => $row->fee_type_title
                                          . " - Issue Challan Form  Student Name is ".$row->student_name." and form no." . $row->chalan_form_number . " from " . $row->student_name,
                                      'credit' => $row->amount,
                                      'entry_type' => 1,
                                      'type_id' => $s_c_f_id,
                                      'school_id' => $row->school_id,
                                      'coa_id' => $row->issue_cr_coa_id
                                  );



                                  $data_debit_receive = array(
                                      'entry_date' => $entry_date,
                                      'detail' => $row->fee_type_title
                                          . " - Receive Challan Form Student Name is ".$row->student_name." and form no. " . $row->chalan_form_number . " from " . $row->student_name,
                                      'debit' => $row->amount,
                                      'entry_type' => 3,
                                      'type_id' => $s_c_f_id,
                                      'school_id' => $row->school_id,
                                      'coa_id' => $row->receive_dr_coa_id
                                  );
                                  //'entry_date' => CURDATE(),
                                  $data_credit_receive = array(
                                      'entry_date' => $entry_date,
                                      'detail' => $row->fee_type_title
                                          . " - Receive Challan Form  Student Name is ".$row->student_name." and form no." . $row->chalan_form_number . " from " . $row->student_name,
                                      'credit' => $row->amount,
                                      'entry_type' => 3,
                                      'type_id' => $s_c_f_id,
                                      'school_id' => $row->school_id,
                                      'coa_id' => $row->receive_cr_coa_id
                                  );

                                  //s_c_f_id=$s_c_f_id

                                  $this->db->insert(get_school_db() . ".journal_entry", $data_debit_issue);
                                  $this->db->insert(get_school_db() . ".journal_entry", $data_credit_issue);
                                  $this->db->insert(get_school_db() . ".journal_entry", $data_debit_receive);
                                  $this->db->insert(get_school_db() . ".journal_entry", $data_credit_receive);
                              }


                          }

                      }


                  }
              }*/


            /* Confirm bulk  Entries in journal table End*/

            $s_c_f_data_s['status'] = 5;
            $s_c_f_data['is_bulk'] = 1;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);

            $date = new DateTime();
            $data_a['approval_date'] = $date->format('Y-m-d H:i:s');
            $data_a['approved_by'] = $_SESSION['login_detail_id'];

            $s_c_f_data_a['activity'] = $activity;
            $s_c_f_data_a['status'] = 0;
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_request", $s_c_f_data_a);

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'approved_by' => 0, 'school_id' => $school_id, 'status <' => 5, 'is_bulk' => 1));
            $this->db->update(get_school_db() . ".student_chalan_form", $data_a);

            $issue_date = $date->format('Y-m-d H:i:s');

            $issued_by = $_SESSION['login_detail_id'];

            $date = date('Y-m-d', strtotime('+' . $due_days . ' days'));

            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET  `issue_date` ='$issue_date',
            `issued_by` =$issued_by,
            `due_date` ='$date' WHERE  `bulk_req_id` =  $bulk_req_id AND  `issued_by` =0 AND status<5 and is_bulk=1 and `school_id` =  $school_id");

            $received_by = $_SESSION['login_detail_id'];

            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET status=5,  `received_date` ='$issue_date', payment_date='$issue_date' ,received_amount=actual_amount,
            `received_by` =$received_by WHERE  `bulk_req_id` =  $bulk_req_id AND  `received_by` =0 AND status<5 and is_bulk=1 and `school_id` =  $school_id");

            $this->db->query("UPDATE " . get_school_db() . ".student SET   `section_id` =pro_section_id, academic_year_id=pro_academic_year_id ,`student_status` =14 WHERE 
             bulk_req_id=$bulk_req_id  AND school_id=$school_id");

            /*
            $std_rec=$this->db->query("select * from ".get_school_db().".student WHERE
             bulk_req_id=$bulk_req_id  AND school_id=$school_id")->result_array();


            //$this->load->helper("student");
            //$this->load->helper('sms');

            foreach($std_rec as $row121){

            student_archive($_SESSION['login_detail_id'],$row121['student_id']);
            $sms_ary=get_sms_detail($row121['student_id']);
            $message=$sms_ary['student_name']." is promoted to  ".$sms_ary['section_name']." of ".$sms_ary['class_name'];
            send_sms($sms_ary['mob_num'],'Indici Edu',$message);

            }
            */


        }

        redirect($_SERVER['HTTP_REFERER']);

    }

    function check_promotion_req()
    {

        $section_id = $this->input->post('section_id');
        $school_id = $_SESSION['school_id'];

        $p = $this->db->query("SELECT section_id FROM " . get_school_db() . ".student   where section_id=$section_id  and student_status IN (" . student_query_status() . ") and school_id=$school_id ")->result_array();
        if (count($p) == 0) {
            echo "no student";
            exit;
        }

        $query_v = $this->db->query("select section_id from " . get_school_db() . ".bulk_request WHERE section_id=$section_id and activity=1")->result_array();

        if (count($query_v) > 0) {
            echo trim("no");
        } else {
            echo trim("yes");
        }

    }

    function view_detail_listing($bulk_req_id)
    {

        $school_id = $_SESSION['school_id'];
        $page_data['query'] = $this->db->query("Select *,scf.status as student_challan_status from " . get_school_db() . ".bulk_request br
                                                inner join " . get_school_db() . ".student_chalan_form scf   on scf.bulk_req_id=br.bulk_req_id
                                                where scf.school_id=$school_id and scf.bulk_req_id=$bulk_req_id and scf.is_bulk=1 ")->result_array();
        

        $page_data['page_name'] = 'promotion_chalan_listing';
        $page_data['page_title'] = get_phrase('promotion_chalan_detail');
        $page_data['bulk_req_id'] = $bulk_req_id;
        $this->load->view('backend/index', $page_data);
    }


    function cancel_request($bulk_req_id)
    {
        $school_id = $_SESSION['school_id'];

        $bulk_str = "SELECT s_c_f_id FROM " . get_school_db() . ".student_chalan_form  WHERE bulk_req_id = " . $bulk_req_id . "
                     AND school_id = " . $_SESSION['school_id'] . " AND is_bulk = 1 AND status>=4";
        $bulk__row = $this->db->query($bulk_str)->result();

        if (count($bulk__row) > 0) {
            foreach ($bulk__row as $blk_row) {
                $s_c_f_id = $blk_row->s_c_f_id;
                $date = new DateTime();
                $entry_date = $date->format('Y-m-d H:i:s');
                $c_f_str = "SELECT scd.s_c_d_id, scf.s_c_f_id ,scf.student_id , scd.fee_type_title , scd.amount , scd.school_id, scf.chalan_form_number,
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
                                                        scd.school_id = " . $_SESSION['school_id'] . " ";

                $query_c_f = $this->db->query($c_f_str)->result();
                if (count($query_c_f) > 0) {
                    foreach ($query_c_f as $row) {

                        if ($row->amount > 0) {
                            $transaction_detail = student_name_section($row->student_id);
                            $journal_entry = 0;
                            if ($row->type == 2 || $row->type == 4) {
                                $journal_entry = 1;
                                $amount = $row->amount;
                            } elseif ($row->type == 1 || $row->type == 5) {
                                $journal_entry = 1;
                                $amount = $row->amount;//(-1) * ($row->amount);
                            } else if ($row->type == 3 || $row->type == 6) {
                                $journal_entry = 0;
                            }

                            if ($journal_entry == 1) {
                                $data_debit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
                                        . " - " . get_phrase('cancelled_challan_form') . " " . $row->chalan_form_number . " - " . get_phrase('student_name') . " - ". get_phrase('from') . " " . $transaction_detail,
                                    'debit' => $amount,
                                    'entry_type' => 2,
                                    'type_id' => $s_c_f_id,
                                    'school_id' => $row->school_id,
                                    'coa_id' => $row->cancel_dr_coa_id
                                );
                                //'entry_date' => CURDATE(),
                                $data_credit = array(
                                    'entry_date' => $entry_date,
                                    'detail' => $row->fee_type_title
                                        . " - " . get_phrase('cancelled_challan_form') . " - " . $row->chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                    'credit' => $amount,
                                    'entry_type' => 2,
                                    'type_id' => $s_c_f_id,
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

            $query = $this->db->query("select student_id from " . get_school_db() . ".student where school_id=$school_id and bulk_req_id=$bulk_req_id and student_status=11")->result_array();
            foreach ($query as $row) {

                $data['student_status'] = 10;
                $data['bulk_req_id'] = 0;
                $data['pro_academic_year_id'] = 0;
                $data['pro_section_id'] = 0;

                $this->load->helper('student');

                $this->db->where("student_id", $row['student_id']);
                $this->db->update(get_school_db() . ".student", $data);

                student_archive($_SESSION['login_detail_id'], $row['student_id']);

            }

            /*  $delete_s_c_d_str = "DELETE FROM ".get_school_db().".student_chalan_detail
                                                  where s_c_f_id in (select s_c_f_id from ".get_school_db().".student_chalan_form
                                                  where bulk_req_id=$bulk_req_id and school_id=$school_id and status<5 and is_bulk=1)
                                                  and school_id=$school_id";

              $this->db->query($delete_s_c_d_str);

              $delete_s_c_f_str = "DELETE FROM ".get_school_db().".student_chalan_form
                              where bulk_req_id=$bulk_req_id and school_id=$school_id
                              and status<5 and is_bulk=1";
              $this->db->query($delete_s_c_f_str);

              $delete_bulk_str = "DELETE FROM ".get_school_db().".bulk_request
                                  where bulk_req_id=$bulk_req_id
                                  and school_id=$school_id
                                  and activity<5";

              $this->db->query($delete_bulk_str);

              bulk_req_id=$b_m_c_id*/

            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form
                            SET is_cancelled = 1,
                            cancelled_by = " . $_SESSION['login_detail_id'] . ",
                            cancel_date = '" . $entry_date . "'
                            where
                            bulk_req_id=$bulk_req_id
                            AND
                            school_id=" . $_SESSION['school_id'] . "
                            AND
                            status<5 ");
            $delete_bulk_str = "DELETE FROM " . get_school_db() . ".bulk_request
                                  where bulk_req_id=$bulk_req_id
                                  and school_id=$school_id
                                  and activity<5";

            $this->db->query($delete_bulk_str);

            //$this->session->set_flashdata('journal_entry', 'Challan Form not cancelled. Incomplete Chart of Account Settings.');
        } else {
            $query = $this->db->query("select student_id from " . get_school_db() . ".student where school_id=$school_id and bulk_req_id=$bulk_req_id and student_status=11")->result_array();
            foreach ($query as $row) {

                $data['student_status'] = 10;
                $data['bulk_req_id'] = 0;
                $data['pro_academic_year_id'] = 0;
                $data['pro_section_id'] = 0;

                $this->load->helper('student');

                $this->db->where("student_id", $row['student_id']);
                $this->db->update(get_school_db() . ".student", $data);

                student_archive($_SESSION['login_detail_id'], $row['student_id']);

            }

            $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_detail where s_c_f_id in (select s_c_f_id from " . get_school_db() . ".student_chalan_form where bulk_req_id=$bulk_req_id and school_id=$school_id and status<5 and is_bulk=1) and school_id=$school_id");
            $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_form where bulk_req_id=$bulk_req_id and school_id=$school_id and status<5 and is_bulk=1");
            $this->db->query("DELETE FROM " . get_school_db() . ".bulk_request where bulk_req_id=$bulk_req_id and school_id=$school_id and activity<5");

        }

        redirect($_SERVER['HTTP_REFERER']);
        /* Cancel bulk  Entries in journal table End*/
    }


    function cancel_single_request1($s_c_f_id)
    {

        $school_id = $_SESSION['school_id'];
        $query = $this->db->query("select student_id from " . get_school_db() . ".student_chalan_form where s_c_f_id=$s_c_f_id and school_id=$school_id")->result_array();
        $this->load->helper('student');

        foreach ($query as $row) {

            $data['student_status'] = 10;
            $data['bulk_req_id'] = 0;
            $data['pro_academic_year_id'] = 0;
            $data['pro_section_id'] = 0;

            $this->db->where("student_id", $row['student_id']);
            $this->db->update(get_school_db() . ".student", $data);

            student_archive($_SESSION['login_detail_id'], $row['student_id']);

        }


        $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=$school_id");
        $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_form where s_c_f_id=$s_c_f_id and school_id=$school_id and status<5 and is_bulk=1");

        redirect($_SERVER['HTTP_REFERER']);

    }


    function cancel_monthly_chalan($s_c_f_id)
    {
        $school_id = $_SESSION['school_id'];

        $this->db->where('s_c_f_id', $s_c_f_id);
        $this->db->where('status < ', '5');
        $this->db->update(get_school_db() . ".student_chalan_form", array('status' => '1'));

    //$this->db->query("DELETE FROM ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=$school_id");
    //$this->db->query("DELETE FROM ".get_school_db().".student_chalan_form where s_c_f_id=$s_c_f_id and school_id=$school_id and status<5 and is_bulk=2");
        redirect($_SERVER['HTTP_REFERER']);
    }


    function delete_chalan($s_c_f_id, $url = 0, $form_type = 0)
    {
        // echo "form status--".$bulk_back_id."--Bulk_ID--".$bulk_id;
        $status = 0;
        $bulk_req_id = 0;
        $student_id = 0;
        $school_id = $_SESSION['school_id'];

        $status_row = $this->db->query("SELECT status , bulk_req_id , student_id FROM " . get_school_db() . ".student_chalan_form 
                                        where  s_c_f_id=" . $s_c_f_id . "
                                                AND school_id = $school_id")->row();

        if (count($status_row) > 0)
		{
            $status = $status_row->status;
            $bulk_req_id = $status_row->bulk_req_id;
            $student_id = $status_row->student_id;
        }

        if ($status < 4)
		{

		    $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_detail where  s_c_f_id=" . $s_c_f_id . "  AND  school_id=" . $_SESSION['school_id'] . " ");

            $is_cancelled__str = "UPDATE " . get_school_db() . ".student_chalan_form SET  is_cancelled = 1 WHERE s_c_f_id = " . $s_c_f_id . "";
            $this->db->query($is_cancelled__str);

        }
        else
        {
            $date = new DateTime();
            $entry_date= $date->format('Y-m-d H:i:s');
            $fee_amount = 0;
            $total_discount_amount = 0;

            $str_fee = "SELECT scf_fee.student_id,scf_fee.chalan_form_number,
                                    scf_fee.chalan_form_number, scfd_fee.* 
                                    FROM ".get_school_db().".student_chalan_detail as scfd_fee
                                    INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                        WHERE scfd_fee.s_c_f_id = $s_c_f_id
	                                    AND scfd_fee.type = 1
	                                    AND scfd_fee.school_id = ".$_SESSION['school_id']."";

            $query_fee = $this->db->query($str_fee)->result_array();
            //print_r($query_fee);

            if(count($query_fee)>0)
            {
                foreach ($query_fee as $key_fee => $value_fee)
                {
                    /* create array for add fee only start */
                    $fee_type_id_fee = $value_fee['type_id'];
                    $fee_type_title = $value_fee['fee_type_title'];
                    $fee_amount = $value_fee['amount'];
                    $fee_school_id = $value_fee['school_id'];
                    $fee_issue_dr_coa_id = $value_fee['issue_dr_coa_id'];
                    $fee_issue_cr_coa_id = $value_fee['issue_cr_coa_id'];
                    $fee_chalan_form_number = $value_fee['chalan_form_number'];
                    $transaction_detail = student_name_section($value_fee['student_id']);
                    // $s_c_f_id_fee =  $value['fee_type_id'];


                    $s_c_d_id = $value_fee['s_c_d_id'];
                    $discount_amt = $this->is_discount_fee($s_c_d_id, $s_c_f_id);

                    $fee_amount_temp = $fee_amount;

                    if($discount_amt>0)
                    {

                        $discount_amt = round((($discount_amt * $fee_amount) / 100));
                        $fee_amount_temp = $fee_amount_temp-$discount_amt;

                    }

                    $array_ledger_fee = array(
                        'entry_date' => $entry_date,
                        'detail' => $fee_type_title
                            . ' - ' . get_phrase('cancelled_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                        'credit' => $fee_amount_temp,
                        'entry_type' => 2,
                        'type_id' => $s_c_f_id,
                        'school_id' => $fee_school_id,
                        'coa_id' => $fee_issue_dr_coa_id
                    );

                    $this->db->insert(get_school_db().".journal_entry", $array_ledger_fee);

                    $str_discount = "SELECT scf_discount.student_id,
                                    scf_discount.chalan_form_number,
                                    scfd_discount.amount,
                                    scfd_discount.fee_type_title, 
                                    scfd_discount.type_id as scfd_fee_id,
                                    scfd_discount.issue_cr_coa_id,
                                    scfd_discount.issue_dr_coa_id,
                                    scfd_discount.school_id,
                                    d.discount_id,
                                    f.fee_type_id as fee_id, 
                                    d.title
                                    FROM " . get_school_db() . ".discount_list as d
                                    INNER join  " . get_school_db() . ".fee_types as f ON f.fee_type_id = d.fee_type_id
                                    INNER JOIN " . get_school_db() . ".student_chalan_detail as scfd_discount ON scfd_discount.type_id = d.discount_id
                                    INNER JOIN " . get_school_db() . ".student_chalan_form as scf_discount ON scf_discount.s_c_f_id = scfd_discount.s_c_f_id
                                    WHERE f.fee_type_id = $fee_type_id_fee
                                    AND scfd_discount.s_c_f_id =$s_c_f_id
                                    AND scfd_discount.school_id = " . $_SESSION['school_id'] . "
                                    AND scfd_discount.type = 2";
                    $query_discount = $this->db->query($str_discount)->result_array();

                    if (count($query_discount) > 0)
                    {

                        foreach ($query_discount as $key_discount => $value_discount)
                        {
                            /*create array for add fee only start*/
                            $discount_type_id_fee = $value_discount['type_id'];
                            $discount_type_title = $value_discount['fee_type_title'];
                            $discount_amount = round((($fee_amount * $value_discount['amount']) / 100));
                            $total_discount_amount = $total_discount_amount + $discount_amount;
                            $discount_school_id = $value_discount['school_id'];
                            $discount_issue_cr_coa_id = $value_discount['issue_cr_coa_id'];
                            $discount_issue_dr_coa_id = $value_discount['issue_dr_coa_id'];
                            $discount_chalan_form_number = $value_discount['chalan_form_number'];
                            $transaction_detail = student_name_section($value_discount['student_id']);
                            $percentage_amount = $value_discount['amount'];
                            // $s_c_f_id_fee =  $value['fee_type_id'];
                            $array_ledger_discount = array(
                                'entry_date' => $entry_date,
                                'detail' => $discount_type_title. ' ('.$percentage_amount.' %)'
                                    ." - ". get_phrase('cancelled_challan_form') . ' - '. $discount_chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                'credit' => $discount_amount,
                                'entry_type' => 2,
                                'type_id' => $s_c_f_id,
                                'school_id' => $discount_school_id,
                                'coa_id' => $discount_issue_dr_coa_id
                            );
                            $this->db->insert(get_school_db().".journal_entry", $array_ledger_discount);

                        }
                    }

                    /* change debit to credit */
                    $array_ledger_reamining = array(
                        'entry_date' => $entry_date,
                        'detail' => $fee_type_title
                            ." - ". get_phrase('cancelled_challan_form') . " - " . " - " . $discount_chalan_form_number ." - ". get_phrase('from') . " - " . $transaction_detail,
                        'debit' => $fee_amount,
                        'entry_type' => 2,
                        'type_id' => $s_c_f_id,
                        'school_id' => $_SESSION['school_id'],
                        'coa_id' => $fee_issue_cr_coa_id
                    );

                    $this->db->insert(get_school_db().".journal_entry", $array_ledger_reamining);

                    $total_discount_amount = 0;
                    $fee_amount = 0;

                    /* create remaining point End */
                }
            }

            $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_detail
                                                    where s_c_f_id=" . $s_c_f_id . " AND school_id=" . $_SESSION['school_id'] . " ");
            $is_cancelled__str = "UPDATE " . get_school_db() . ".student_chalan_form
                                                    SET is_cancelled = 1 , status = 1  WHERE s_c_f_id = " . $s_c_f_id . "";
            $this->db->query($is_cancelled__str);

        }
        
        if ($form_type == 3)
        {

            /* Update student table start */
            $student__str = "UPDATE " . get_school_db() . ".student
                                                    SET 
                                                    student_status = 10,
                                                    pro_academic_year_id = 0,
                                                    pro_section_id = 0
                                                    WHERE student_id = " . $student_id . "
                                                    AND school_id = $school_id";
            $this->db->query($student__str);
            /* Update student table End */
            $this->session->set_flashdata('delete_challan_form', get_phrase('challan_form_deleted_successfully'));
            redirect(base_url() . 'c_student/student_information/');
        } else if ($form_type == 1)
        {
            $this->session->set_flashdata('delete_challan_form', get_phrase('challan_form_deleted_successfully'));
            redirect(base_url() . 'c_student/student_pending/');
        } else if ($form_type == 10)
        {
            $this->session->set_flashdata('delete_challan_form', get_phrase('challan_form_deleted_successfully'));
            redirect(base_url() . 'c_student/student_information/');
        } else if ($form_type == 6)
        {
            // exit('exit');
            $this->session->set_flashdata('delete_challan_form', get_phrase('challan_form_deleted_successfully'));
            redirect(base_url() . 'c_student/withdraw_listing/');
        } else if ($form_type == 2)
        {

            $this->session->set_flashdata('delete_challan_form', get_phrase('challan_form_deleted_successfully'));
            redirect(base_url() . 'monthly_fee/view_detail_listing/' . $bulk_req_id);
        } else if ($form_type == 5)
        {

            $this->session->set_flashdata('delete_challan_form', get_phrase('challan_form_deleted_successfully'));
            redirect(base_url() . 'transfer_student/transfer_information');
        }

         else
         {

             $this->session->set_flashdata('delete_challan_form', get_phrase('challan_form_deleted_successfully'));
             redirect(base_url() . 'c_student/student_information/');
         }

    }
    function view_print_chalan_class($bulk_req_id)
    {
        if ($bulk_req_id == "")
        {
            $this->session->set_flashdata('club_updated', get_phrase('chalan_form_is_not_created'));
            redirect($_SERVER['HTTP_REFERER']);

        } else
        {
            $school_id = $_SESSION['school_id'];
            $page_data["query_ary"] = $this->db->query("select * from " . get_school_db() . ".student_chalan_form where status=4 and bulk_req_id= $bulk_req_id and school_id=$school_id  and is_bulk=1")->result_array();
            $page_data['page_name'] = 'view_print_chalan';
            $page_data['page_title'] = get_phrase('chalan_form');
            $this->load->view('backend/index', $page_data);
        }
    }


        function is_discount_fee($s_c_d_id, $s_c_f_id)
        {
            //working

            $school_id = $_SESSION['school_id'];
            $str_discount = "SELECT scfd_discount.amount
                                    FROM " . get_school_db() . ".student_chalan_detail as scfd_discount
                                    WHERE 
                                        scfd_discount.s_c_f_id =$s_c_f_id
                                    AND scfd_discount.related_s_c_d_id =$s_c_d_id
                                    AND scfd_discount.school_id = $school_id
                                    AND scfd_discount.type = 2";
            $query_discount = $this->db->query($str_discount)->result_array();
            if(count($query_discount)>0)
            {
                $discount_amount = $query_discount[0]['amount'];
                return $discount_amount;
            }
            else
            {
                return 0;
            }


        }
}