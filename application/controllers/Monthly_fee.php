<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);
    ini_set('memory_limit', '-1');
    //ini_set('max_execution_time', 3600);

    ob_start();
class Monthly_fee extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if ($_SESSION['user_login'] != 1)
            redirect('login');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->menu_ary = array();
    }
    
    function challans_pdf()
    {   
        $section_id =  $this->input->post('section_id');
        $month      =  $this->input->post('month');
        $year       =  $this->input->post('year');
       
        if ($section_id == "") {
            $this->session->set_flashdata('club_updated', get_phrase('chalan_form_is_not_created'));
            redirect($_SERVER['HTTP_REFERER']);
        } 
        else 
        {
            $school_id   = $_SESSION['school_id'];
            $qur         = $this->db->query("select * from " . get_school_db() . ".bulk_monthly_chalan where section_id=$section_id and status=1 and activity in (4) and fee_month=$month and fee_year=$year")->result_array();
            $bulk_req_id = 0;
            
            if (count($qur) == 0) 
            {
                $this->session->set_flashdata('club_updated', get_phrase('Chalan Form is not yet Issued'));
                redirect($_SERVER['HTTP_REFERER']);
                exit;
            } 
            else 
            {
                $bulk_req_id = $qur[0]['b_m_c_id'];
            }

            $page_data["query_ary"]     = $this->db->query("select * from " . get_school_db() . ".student_chalan_form where status=4 and bulk_req_id= $bulk_req_id and school_id=$school_id  and is_bulk=2 limit 1")->result_array();
            $page_data['page_title']    =   get_phrase('challans_list_pdf');
            

            $this->load->library('Pdf');

            $view = 'backend/admin/challans_list_pdf';
            $this->pdf->load_view($view,$page_data);
            $this->pdf->render();
            $this->pdf->stream("".$page_data['page_title'].".pdf");
        
        }
        
    }
    
    

    function monthly_bulk_listing()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');

        $page_data['page_name']  = 'bulk_chalan_listing';
        $page_data['page_title'] = get_phrase('monthly_chalan_listing');
        $this->load->view('backend/index', $page_data);

    }

    function get_student_info()
    {

        $section_id     =  $this->input->post('section_id');
        $yearly_term_id =  $this->input->post('yearly_term_id');
        $month_year     =  $this->input->post('month_year');
        $school_id      =  $_SESSION['school_id'];
        $sear           =  "";
    
        if (isset($section_id) && $section_id != "") {
            $sear = " and cs.section_id=$section_id ";
        }
        
        if (isset($yearly_term_id) && $yearly_term_id != "") {

            $qq_rr     =  $this->db->query("select * from " . get_school_db() . ".yearly_terms where yearly_terms_id=$yearly_term_id and school_id=" . $_SESSION['school_id'])->result_array();
            $val_val   =  $this->month_year($qq_rr[0]['start_date'], $qq_rr[0]['end_date']);
            $month_str =  "";
            
            foreach ($val_val as $r_val) {
                $month_str .= "'" . $r_val['month'] . "-" . $r_val['year'] . "',";
            }

            $month_str = rtrim($month_str, ',');
            $month_str;
            $sear = " and concat(br.fee_month,'-',br.fee_year) in ($month_str)";

        }
        
        if ($month_year != "") {
            $month_year = ltrim($month_year,"0");
            $sear = " and concat(br.fee_month,'-',br.fee_year) in ('$month_year')";
        }

        $qqrr_r = "select 
                br.b_m_c_id,
                br.date_time,
                br.activity,
                br.fee_month,
                br.fee_year,
                br.section_id,
                cs.title as section_name,
                c.name as class_name,
                d.title as department_name
                from " . get_school_db() . ".bulk_monthly_chalan br 
                inner join " . get_school_db() . ".class_section cs on cs.section_id=br.section_id 
                inner join " . get_school_db() . ".class c on c.class_id=cs.class_id
                inner join " . get_school_db() . ".departments d on d.departments_id=c.departments_id
                where br.school_id=$school_id and br.status=1 $sear order by br.date_time";
             
        $check_row = $this->db->query($qqrr_r)->result_array();
        
        if(count($check_row) > 0){
            $data['query_promotion'] = $check_row;
            $this->load->view("backend/admin/ajax/bulk_chalan_list_ajax", $data);
        }else{
            echo "0";
        }

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
        $this->db->trans_begin();

        $section_id  = $this->input->post('section_id');
        $school_id   = $_SESSION['school_id'];
        $query = $this->db->query("select student_id from " . get_school_db() . ".student s where s.section_id=$section_id and s.school_id=$school_id and s.student_status in (" . student_query_status() . ")")->result_array();
        if (count($query) == 0) {
            echo get_phrase('no_student_found');
            exit;
        }

        $year                   = $this->input->post('year');
        $month                  = $this->input->post('month');
        $data_r['section_id']   = $section_id;
        $data_r['school_id']    = $school_id;
        $data_r['fee_year']     = $year;
        $data_r['fee_month']    = $month;
        $data_r['user_id']      = $_SESSION['login_detail_id'];
        $data_r['activity']     = 1;
        $data_r['status']       = 1;
        $date_month             = $year . '-' . $month . '-01';
        
        $query_r333_str         = "SELECT ccf.c_c_f_id ,ccf.due_days FROM  " . get_school_db() . ".fee_types ft
								   inner join " . get_school_db() . ".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id 
								   inner join " . get_school_db() . ".class_chalan_form ccf on ccf.c_c_f_id= ccfe.c_c_f_id
								   where ccf.section_id=$section_id and ccf.type=2 and ccf.status=1 and ccf.school_id=$school_id ORDER BY ccfe.order_num";
        $query_r333 = $this->db->query($query_r333_str)->result_array($query_r333_str);

        if (count($query_r333) == 0) {

            echo get_phrase('no_chalan_form_created_yet');
            exit;

        } else {

            $data_r['c_c_f_id']  =  $query_r333[0]['c_c_f_id'];
            $c_c_f_id            =  $data_r['c_c_f_id'];
            // $c_c_f_id            =  $data_r['c_c_f_id'];
            $date                =  new DateTime();
            $data_r['date_time'] =  $date->format('Y-m-d H:i:s');
            $due_days            =  $query_r333[0]['due_days'];
            $query_check         =  $this->db->query("select * from " . get_school_db() . ".bulk_monthly_chalan where section_id=$section_id  and status=1  and school_id=$school_id and fee_month=$month and fee_year=$year")->result_array();
            
            if (count($query_check) == 0) {
                
                $this->db->trans_begin();

                $this->db->insert(get_school_db() . ".bulk_monthly_chalan", $data_r);
                $bulk_req_id = $this->db->insert_id();

                $student_ids = $this->remove_individual_student($query, $date_month, $bulk_req_id);
                
                foreach ($student_ids['default_st'] as $row) {
                    $this->student_fee_settings($row, $date_month, $bulk_req_id);
                }
                
                foreach ($student_ids['default_st'] as $student_id_fee_settings) {
                    $this->insert_chalan_fee_settings($student_id_fee_settings, $date_month, $bulk_req_id, $c_c_f_id);
                }
                
                foreach ($student_ids['individual_st'] as $row1) {
                    $this->student_fee_settings_individual($row1, $date_month, $bulk_req_id);
                }
                
                foreach ($student_ids['individual_st'] as $invidual_student_id) {
                    $this->insert_chalan_individual($invidual_student_id, $date_month, $bulk_req_id, $c_c_f_id);
                }
                
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                echo get_phrase('challan_generated');
            } else {
                /*
                $bulk_req_id =	$query_check[0]['bulk_req_id'];
                $this->db->where("bulk_req_id",$bulk_req_id);
                $this->db->update("bulk_request",$data_r);
                $this->bulk_condition($bulk_req_id,$data_r['activity'],$due_days);
                */
                echo get_phrase('request_already_exists');
            }
        }
        
    }

    function remove_individual_student($query, $date_month, $bulk_req_id)
    {

        $school_id       =  $_SESSION['school_id'];
        $date_month_temp =  explode("-", $date_month);
        $year            =  $date_month_temp[0];
        $month           =  $date_month_temp[1];
        $student_ids     =  array();

        if (count($query) > 0) {
            foreach ($query as $row) {

                $student_id = $row['student_id'];

                $quuery_individual_str = "select student_id from " . get_school_db() . ".student_fee_settings 
                                          WHERE  month=$month 
                                          AND    year = $year 
                                          AND    student_id = $student_id  
                                          AND    school_id = $school_id
                                          AND    fee_type = 1
                                          AND    settings_type = 1
                                          AND    is_bulk = 0
                                          AND    std_m_fee_settings_id = 0";
                                    
                $quuery_individual = $this->db->query($quuery_individual_str)->result_array();
                $student_id        = $row['student_id'];
                if (count($quuery_individual) > 0) {

                    $student_ids['individual_st'][] = $student_id;
                } else {
                    $student_ids['default_st'][]    = $student_id; 
                }

            }
        }

        return $student_ids;

    }
    
    
    
    function student_fee_settings($student_id, $date_month, $bulk_req_id)
    {
        
        $school_id        =  $_SESSION['school_id'];
        $date_month_temp  =  explode("-", $date_month);
        $year             =  $date_month_temp[0];
        $month            =  $date_month_temp[1];
        $current_date     =  date('Y-m-d H:i:s');
        $login_by         =  $_SESSION['login_detail_id'];
        $section_id       =  get_student_section_id($student_id);
        $quuery_check_str = "select * from " . get_school_db() . ".student_monthly_fee_settings 
                                    WHERE  fee_month=$month 
                                    AND fee_year = $year 
                                    AND student_id = $student_id  
                                    AND school_id=$school_id
                                    AND b_m_c_id = 0
                                    AND status = 1";
        $smfs_query_exist =  $this->db->query($quuery_check_str);
        $query_check      =  $this->db->query($quuery_check_str)->result_array();
        $date_str         =  "generation_date = '" . $current_date . "' , generated_by = '" . $login_by . "'";
        $is_individual    =  $this->get_individual_student_settings($student_id, $date_month, $bulk_req_id);

        $query_c_c_f_fee_str = "SELECT ft.fee_type_id as fee_fee_type_id, ft.title as fee_title , ccf_fee.value as amount  
                                FROM " . get_school_db() . ".class_chalan_form as ccf_form
                                INNER JOIN " . get_school_db() . ".class_chalan_fee as ccf_fee on ccf_fee.c_c_f_id = ccf_form.c_c_f_id
                                INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = ccf_fee.fee_type_id
                                WHERE ccf_form.status = 1
                                AND ccf_form.school_id = $school_id
                                AND ccf_form.section_id = $section_id
                                AND ccf_form.type = 2
                                ";

            $query_c_c_f_fee       = $this->db->query($query_c_c_f_fee_str)->result_array();
            $std_m_fee_settings_id = 0;
            if ((count($query_c_c_f_fee) > 0)) {
                $date_str = "generation_date = '" . $current_date . "' , generated_by = '" . $login_by . "'";
                $check_smfs_exist = $this->db->query("SELECT * FROM " . get_school_db() . ".student_monthly_fee_settings WHERE fee_month = '$month' AND fee_year = '$year' AND student_id = '$student_id' AND school_id = '$school_id' AND status = 1 ");
                if (($check_smfs_exist->num_rows() > 0)){
                    $query_update_bulk_id_str = "update " . get_school_db() . ".student_monthly_fee_settings
                                                set
                                                b_m_c_id = $bulk_req_id,
                                                $date_str
                                                where 
                                                fee_month = " . $month . "
                                                AND fee_year = " . $year . "
                                                AND student_id = $student_id
                                                AND  school_id = $school_id
                                                AND  status = 1";
                    $query_update_bulk_id = $this->db->query($query_update_bulk_id_str);
                }
                $query_insert_msfs_fee_str = "INSERT INTO " . get_school_db() . ".student_monthly_fee_settings
                                          set
                                          fee_month = " . $month . ",
                                          fee_year = " . $year . ",
                                          student_id = $student_id,
                                          school_id = $school_id,
                                          b_m_c_id = $bulk_req_id,
                                          status = 1, $date_str";
                        
                $query_insert_msfs_fee = $this->db->query($query_insert_msfs_fee_str);
                $std_m_fee_settings_id = $this->db->insert_id();

                foreach ($query_c_c_f_fee as $key_fee => $val_fee) 
                {
                    $title            =  $val_fee['fee_title'];
                    $amount           =  $val_fee['amount'];
                    $fee_type         =  1;
                    $fee_type_id      =  $val_fee['fee_fee_type_id'];
                    $settings_type    =  2;
                    $is_bulk          =  1;
                    $custom_fee_exist =  $this->custom_fee_exist($student_id, $month, $year, $fee_type, $fee_type_id, $std_m_fee_settings_id);

                    if ($custom_fee_exist == 1) {

                        $query_insert_sfs_fee_str = "INSERT INTO " . get_school_db() . ".student_fee_settings
                                           set
                                           title = '" . $title . "',
                                           amount = $amount,
                                           fee_type = $fee_type,
                                           fee_type_id = $fee_type_id,
                                           settings_type = $settings_type,
                                           is_bulk = $is_bulk,
                                           month = '".$month."',
                                           year = " . $year . ",
                                           student_id = $student_id,
                                           school_id = $school_id,
                                           status = 1,
                                           std_m_fee_settings_id = $std_m_fee_settings_id
                                           "; 
                        $query_insert_sfs_fee = $this->db->query($query_insert_sfs_fee_str);
                        
                    }
                    //   else
                    //   {

                    //       echo "add fee in student fee settings";
                    //       $query_insert_sfs_fee_str = "update " . get_school_db() . ".student_fee_settings
                    //                  set = is_bulk = 1
                    //                  where
                    //                      title = '" . $title . "'
                    //                      AND amount = $amount
                    //                      AND fee_type = $fee_type
                    //                      AND fee_type_id = $fee_type_id
                    //                      AND settings_type = $settings_type
                    //                      AND is_bulk = $is_bulk
                    //                      AND month = " . $month . "
                    //                      AND year = " . $year . "
                    //                      AND student_id = $student_id
                    //                      AND school_id = $school_id
                    //                      std_m_fee_settings_id = $std_m_fee_settings_id
                    //                      ";
                    //       echo "<br>";
                    //       $query_insert_sfs_fee = $this->db->query($query_insert_sfs_fee_str);

                    //   } 
                }
            }
            /* Add Fee End */

            /* Add Discount Strat */
            
            $query_c_c_f_discount_str = "SELECT dl.title as discount_title , dl.discount_id as dl_discount_id , ccd.value as ccd_amount FROM " . get_school_db() . ".class_chalan_form as ccf
                                        INNER JOIN " . get_school_db() . ".class_chalan_discount as ccd on ccd.c_c_f_id =  ccf.c_c_f_id
                                        INNER JOIN " . get_school_db() . ".discount_list as dl on dl.discount_id = ccd.discount_id
                                        WHERE dl.school_id = $school_id
                                        AND ccf.section_id = $section_id
                                        AND ccf.type = 2";

            $query_c_c_f_discount = $this->db->query($query_c_c_f_discount_str)->result_array();

            if (count($query_c_c_f_discount) > 0) 
            {
                foreach ($query_c_c_f_discount as $key_discount => $val_discount) 
                {
                    
                    $title         =  $val_discount['discount_title'];
                    $amount        =  $val_discount['ccd_amount'];
                    $fee_type      =  2;
                    $fee_type_id   =  $val_discount['dl_discount_id'];
                    $settings_type =  2;
                    $is_bulk       =  1;
                    
                    $custom_discount_exist = $this->custom_fee_exist($student_id, $month, $year, $fee_type, $fee_type_id, $std_m_fee_settings_id);
                    
                    if ($custom_discount_exist == 1) {
                        // $verify_discount_add_or_not = $this->db->query("select student_id from " . get_school_db() . ".student_fee_settings WHERE  month = $month AND year = $year AND student_id = $student_id AND school_id = $school_id");    
                        // echo $this->db->last_query();
                        // exit;
                        // if($verify_discount_add_or_not->num_rows() == 0)
                        // {
                          
                            $query_insert_sfs_fee_str = "INSERT INTO " . get_school_db() . ".student_fee_settings
                                       set
                                           title = '" . $title . "',
                                           amount = $amount,
                                           discount_amount_type = '0',
                                           fee_type = $fee_type,
                                           fee_type_id = $fee_type_id,
                                           settings_type = $settings_type,
                                           is_bulk = $is_bulk,
                                           month = '".$month."',
                                           year = " . $year . ",
                                           student_id = $student_id,
                                           school_id = $school_id,
                                           status = 1,
                                           std_m_fee_settings_id = $std_m_fee_settings_id";

                           $query_insert_sfs_fee = $this->db->query($query_insert_sfs_fee_str);
                           
                        // }
                    }
                }

            }
            /* Add Discount End  */
        
        // } 
        
    }
    
    

    // function student_fee_settings_old($student_id, $date_month, $bulk_req_id)
    // {

    //     $school_id        =  $_SESSION['school_id'];
    //     $date_month_temp  =  explode("-", $date_month);
    //     $year             =  $date_month_temp[0];
    //     $month            =  $date_month_temp[1];
    //     $current_date     =  date('Y-m-d H:i:s');
    //     $login_by         =  $_SESSION['login_detail_id'];
    //     $section_id       =  get_student_section_id($student_id);
    //     $quuery_check_str = "select * from " . get_school_db() . ".student_monthly_fee_settings 
    //                                 WHERE  fee_month=$month 
    //                                 AND fee_year = $year 
    //                                 AND student_id = $student_id  
    //                                 AND school_id=$school_id";
    //     $query_check      =  $this->db->query($quuery_check_str)->result_array();
    //     $date_str         =  "generation_date = '" . $current_date . "' , generated_by = '" . $login_by . "'";
    //     $is_individual    =  $this->get_individual_student_settings($student_id, $date_month, $bulk_req_id);


    //     if ((count($query_check) == 0)) {


    //         echo $query_c_c_f_fee_str = "SELECT ft.fee_type_id as fee_fee_type_id, ft.title as fee_title , ccf_fee.value as amount  
    //                                     FROM " . get_school_db() . ".class_chalan_form as ccf_form
    //                                         INNER JOIN " . get_school_db() . ".class_chalan_fee as ccf_fee on ccf_fee.c_c_f_id = ccf_form.c_c_f_id
    //                                         INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = ccf_fee.fee_type_id
    //                                         WHERE ccf_form.status = 1
    //                                         AND ccf_form.school_id = $school_id
    //                                         AND ccf_form.section_id = $section_id
    //                                         AND ccf_form.type = 2
    //                                         ";

    //         $query_c_c_f_fee       = $this->db->query($query_c_c_f_fee_str)->result_array();
    //         $std_m_fee_settings_id = 0;
    //         if ((count($query_c_c_f_fee) > 0)) {
    //             $date_str = "generation_date = '" . $current_date . "' , generated_by = '" . $login_by . "'";

    //             echo $query_insert_msfs_fee_str = "INSERT INTO " . get_school_db() . ".student_monthly_fee_settings
    //                                           set
    //                                           fee_month = " . $month . ",
    //                                           fee_year = " . $year . ",
    //                                           student_id = $student_id,
    //                                           school_id = $school_id,
    //                                           b_m_c_id = $bulk_req_id,
    //                                           status = 1, $date_str";
                            
    //             echo "<br>";
    //             //$query_insert_msfs_fee = $this->db->query($query_insert_msfs_fee_str);
    //             //$std_m_fee_settings_id = $this->db->insert_id();

    //             $std_m_fee_settings_id  = 0;

    //             foreach ($query_c_c_f_fee as $key_fee => $val_fee) 
    //             {
                    
    //                 $title            =  $val_fee['fee_title'];
    //                 $amount           =  $val_fee['amount'];
    //                 $fee_type         =  1;
    //                 $fee_type_id      =  $val_fee['fee_fee_type_id'];
    //                 $settings_type    =  2;
    //                 $is_bulk          =  1;
    //                 $custom_fee_exist =  $this->custom_fee_exist($student_id, $month, $year, $fee_type, $fee_type_id, $std_m_fee_settings_id);

    //                 if ($custom_fee_exist == 1) {

    //                     echo $query_insert_sfs_fee_str = "INSERT INTO " . get_school_db() . ".student_fee_settings
    //                                       set
    //                                       title = '" . $title . "',
    //                                       amount = $amount,
    //                                       fee_type = $fee_type,
    //                                       fee_type_id = $fee_type_id,
    //                                       settings_type = $settings_type,
    //                                       is_bulk = $is_bulk,
    //                                       month = '".$month."',
    //                                       year = " . $year . ",
    //                                       student_id = $student_id,
    //                                       school_id = $school_id,
    //                                       status = 1,
    //                                       std_m_fee_settings_id = $std_m_fee_settings_id
    //                                       "; 
    //                     echo "<br>";                   
    //                     //$query_insert_sfs_fee = $this->db->query($query_insert_sfs_fee_str);
    //                 }
    //                 //   else
    //                 //   {

    //                 //       echo "add fee in student fee settings";
    //                 //       $query_insert_sfs_fee_str = "update " . get_school_db() . ".student_fee_settings
    //                 //                  set = is_bulk = 1
    //                 //                  where
    //                 //                      title = '" . $title . "'
    //                 //                      AND amount = $amount
    //                 //                      AND fee_type = $fee_type
    //                 //                      AND fee_type_id = $fee_type_id
    //                 //                      AND settings_type = $settings_type
    //                 //                      AND is_bulk = $is_bulk
    //                 //                      AND month = " . $month . "
    //                 //                      AND year = " . $year . "
    //                 //                      AND student_id = $student_id
    //                 //                      AND school_id = $school_id
    //                 //                      std_m_fee_settings_id = $std_m_fee_settings_id
    //                 //                      ";
    //                 //       echo "<br>";
    //                 //       $query_insert_sfs_fee = $this->db->query($query_insert_sfs_fee_str);

    //                 //   } 
    //             }
                
                
                
                
    //         }
    //         /* add fee end */

    //         /* add discount strat */


    //         $query_c_c_f_discount_str = "SELECT dl.title as discount_title , dl.discount_id as dl_discount_id , ccd.value as ccd_amount FROM " . get_school_db() . ".class_chalan_form as ccf
    //                                     INNER JOIN " . get_school_db() . ".class_chalan_discount as ccd on ccd.c_c_f_id =  ccf.c_c_f_id
    //                                     INNER JOIN " . get_school_db() . ".discount_list as dl on dl.discount_id = ccd.discount_id
    //                                     WHERE dl.school_id = $school_id
    //                                     AND ccf.section_id = $section_id
    //                                     AND ccf.type = 2";

    //         $query_c_c_f_discount = $this->db->query($query_c_c_f_discount_str)->result_array();

    //         if (count($query_c_c_f_discount) > 0) 
    //         {
    //             foreach ($query_c_c_f_discount as $key_discount => $val_discount) 
    //             {
                    
    //                 $title         =  $val_discount['discount_title'];
    //                 $amount        =  $val_discount['ccd_amount'];
    //                 $fee_type      =  2;
    //                 $fee_type_id   =  $val_discount['dl_discount_id'];
    //                 $settings_type =  2;
    //                 $is_bulk       =  1;
                    
    //                 $custom_discount_exist = $this->custom_fee_exist($student_id, $month, $year, $fee_type, $fee_type_id, $std_m_fee_settings_id);
    //                 if ($custom_discount_exist == 1) {
    //                     $verify_discount_add_or_not = $this->db->query("select student_id from " . get_school_db() . ".student_fee_settings WHERE  month = $month AND year = $year AND student_id = $student_id AND school_id = $school_id");    
    //                     if($verify_discount_add_or_not->num_rows() == 0)
    //                     {
    //                         echo $query_insert_sfs_fee_str = "INSERT INTO " . get_school_db() . ".student_fee_settings
    //                                   set
    //                                       title = '" . $title . "',
    //                                       amount = $amount,
    //                                       fee_type = $fee_type,
    //                                       fee_type_id = $fee_type_id,
    //                                       settings_type = $settings_type,
    //                                       is_bulk = $is_bulk,
    //                                       month = '".$month."',
    //                                       year = " . $year . ",
    //                                       student_id = $student_id,
    //                                       school_id = $school_id,
    //                                       status = 1,
    //                                       std_m_fee_settings_id = $std_m_fee_settings_id";
    //                         echo "<br>";               

    //                     //$query_insert_sfs_fee = $this->db->query($query_insert_sfs_fee_str);
    //                     }
    //                 }
    //             }

    //         }
    //         /* add discount end  */
        
            
            
    //     } 
        
        
    //     else {

    //         $query_update_bulk_id_str = "update " . get_school_db() . ".student_monthly_fee_settings
    //                                   set
    //                                       b_m_c_id = $bulk_req_id,
    //                                       $date_str
    //                                      where 
    //                                      fee_month = " . $month . "
    //                                      AND fee_year = " . $year . "
    //                                      AND student_id = $student_id
    //                                      AND  school_id = $school_id
    //                                      AND  status = 1";
    //         $query_update_bulk_id = $this->db->query($query_update_bulk_id_str);

    //     }
    // }

    function relink_custom_fee_bulk_id($month, $year, $student_id, $bulk_req_id, $std_m_fee_settings_id)
    {

        $school_id = $_SESSION['school_id'];

        $query_update_bulk_id_str = "update " . get_school_db() . ".student_fee_settings
                                       set
                                         std_m_fee_settings_id = $std_m_fee_settings_id,
                                         is_bulk = 1
                                         where 
                                         month = " . $month . "
                                         AND year = " . $year . "
                                         AND student_id = $student_id
                                         AND  school_id = $school_id
                                         AND  status = 1";
        $query_update_bulk_id = $this->db->query($query_update_bulk_id_str);

    }

    function student_fee_settings_individual($student_id, $date_month, $bulk_req_id)
    {

        $school_id       = $_SESSION['school_id'];
        $date_month_temp = explode("-", $date_month);
        $year            = $date_month_temp[0];
        $month           = $date_month_temp[1];
        $current_date    = date('Y-m-d H:i:s');
        $login_by        = $_SESSION['login_detail_id'];

        $quuery_check_str = "select * from " . get_school_db() . ".student_monthly_fee_settings 
                                WHERE  fee_month=$month 
                                    AND fee_year = $year 
                                    AND student_id = $student_id  
                                    AND school_id=$school_id";
        $query_check = $this->db->query($quuery_check_str)->result_array();

        $date_str    = "generation_date = '" . $current_date . "' , generated_by = '" . $login_by . "'";

        if ((count($query_check) > 0)) {

            $std_m_fee_settings_id = $query_check[0]['std_m_fee_settings_id'];
            $query_update_bulk_id_str = "update " . get_school_db() . ".student_monthly_fee_settings
                                           set
                                           b_m_c_id = $bulk_req_id,
                                           $date_str
                                           where
                                               fee_month = " . $month . "
                                               AND fee_year = " . $year . "
                                               AND student_id = $student_id
                                               AND school_id = $school_id
                                               AND status = 1
                                               ";

            $query_update_bulk_id = $this->db->query($query_update_bulk_id_str);

            $this->relink_custom_fee_bulk_id($month, $year, $student_id, $bulk_req_id, $std_m_fee_settings_id);

            /* fee already added than update with bulk id End */

        } else {


            $query_insert_msfs_fee_str = "INSERT INTO " . get_school_db() . ".student_monthly_fee_settings
                                       set
                                           fee_month = " . $month . ",
                                           fee_year = " . $year . ",
                                           student_id = $student_id,
                                           school_id = $school_id,
                                           b_m_c_id = $bulk_req_id,
                                           status = 1,
                                           $date_str";

            $query_insert_msfs_fee = $this->db->query($query_insert_msfs_fee_str);
            $std_m_fee_settings_id = $this->db->insert_id();
            $this->relink_custom_fee_bulk_id($month, $year, $student_id, $bulk_req_id, $std_m_fee_settings_id);

        }
    }

    function get_individual_student_settings($student_id, $date_month, $bulk_req_id)
    {

        $school_id       = $_SESSION['school_id'];
        $date_month_temp = explode("-", $date_month);
        $year            = $date_month_temp[0];
        $month           = $date_month_temp[1];

        $query_individual_str = "SELECT student_id
                                FROM " . get_school_db() . ".student_fee_settings 
                                where
                                student_id= " . $student_id . "
                                AND month = " . $month . "
                                AND year = " . $year . "
                                AND fee_type = 1
                                AND settings_type = 1
                                AND status = 1
                                AND school_id=$school_id";
        $query_individual = $this->db->query($query_individual_str)->result_array();

        if (count($query_individual) > 0) {
            return 1;
        } else {
            return 0;
        }

    }

    function insert_chalan_fee_settings($student_id, $date_month, $bulk_req_id, $c_c_f_id)
    {
        
        $school_id = $_SESSION['school_id'];
        $get_student_query_str = "SELECT s.name as student_name,
                                s.is_installment,
                                s.barcode_image,
                                s.roll,
                                s.gender,
                                s.section_id,
                                s.pro_section_id,
                                s.academic_year_id,
                                s.pro_academic_year_id,
                                s.section_id,
                                s.reg_num,
                                s.mob_num,
                                s.image,
                                s.system_id,
                                s.academic_year_id,
                                s.location_id,
                                s.student_status,
                                s.id_no,
                                s.email,
                                cs.title as section_nme,
                                cc.name as class_name,
                                dd.title as department_name
                                FROM " . get_school_db() . ".student s 
                                inner join " . get_school_db() . ".class_section cs on s.section_id=cs.section_id  
                                inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id
                                inner join " . get_school_db() . ".departments dd on dd.departments_id=cc.departments_id
                                where s.student_id=$student_id
                                AND s.school_id = $school_id";
        $get_student_query = $this->db->query($get_student_query_str)->result_array();

        $query_res_p_name_str = "SELECT 
                                sp.p_name as parent_name
                                FROM " . get_school_db() . ".student s 
                                inner join " . get_school_db() . ".student_relation sr on sr.student_id=s.student_id 
                                inner join " . get_school_db() . ".student_parent sp on sp.s_p_id=sr.s_p_id
                                where
                                s.student_id= " . $student_id . "
                                AND s.school_id=$school_id 
                                and sr.relation='f'";
        $query_res_p_name = $this->db->query($query_res_p_name_str)->result_array(); //school id
        
        $date_month_temp = explode("-", $date_month);
        $year             = $date_month_temp[0];
        $month            = $date_month_temp[1];
        $current_date     = date('Y-m-d H:i:s');
        $login_by         = $_SESSION['login_detail_id'];

        $student_name     = $get_student_query[0]['student_name'];
        $section_id       = $get_student_query[0]['section_id'];
        $academic_year_id = $get_student_query[0]['academic_year_id'];
        $section          = $get_student_query[0]['section_nme'];
        $class            = $get_student_query[0]['class_name'];
        $department       = $get_student_query[0]['department_name'];
        $roll             = $get_student_query[0]['roll'];
        $image            = $get_student_query[0]['image'];
        $system_id = $get_student_query[0]['system_id'];
        $location_id = $get_student_query[0]['location_id'];
        $student_status = $get_student_query[0]['student_status'];
        $id_no = $get_student_query[0]['id_no'];
        $email = $get_student_query[0]['email'];
        $father_name = $query_res_p_name[0]['parent_name'];
        $chalan_form_number = chalan_form_number();

        $barcode_image = $get_student_query[0]['barcode_image'];
        $roll = $get_student_query[0]['roll'];
        $gender = $get_student_query[0]['gender'];
        $reg_num = $get_student_query[0]['reg_num'];
        $mob_num = $get_student_query[0]['mob_num'];
        $location_id = $get_student_query[0]['location_id'];
        $image = $get_student_query[0]['image'];
        $system_id = $get_student_query[0]['system_id'];

        // $chalan_query_str = "SELECT sfs.* FROM " . get_school_db() . ".student_monthly_fee_settings smfs
        //         INNER join " . get_school_db() . ".student_fee_settings as sfs
        //         on ((smfs.std_m_fee_settings_id = sfs.std_m_fee_settings_id))
        //         WHERE
        //         smfs.school_id = $school_id
        //         AND smfs.student_id = $student_id
        //         AND (smfs.fee_month = $month AND smfs.fee_year = $year)
        //         AND smfs.b_m_c_id = $bulk_req_id
        //         ORDER BY sfs.fee_type";
        $chalan_query_str = "SELECT sfs.* FROM " . get_school_db() . ".student_fee_settings as sfs
                WHERE
                sfs.school_id = $school_id
                AND sfs.student_id = $student_id
                AND sfs.month = $month 
                AND sfs.year = $year
                ORDER BY sfs.fee_type";

        $chalan_query = $this->db->query($chalan_query_str)->result_array();
        /* add fee start */
        $related_s_c_d_id = array();
        $rep_comma_f_name = str_replace("'"," ",$father_name);
        if ((count($chalan_query) > 0)) {
            $date_str = "generation_date = '" . $current_date . "' , generated_by = '" . $login_by . "'";
            $query_insert_chalan_form_str = "INSERT INTO " . get_school_db() . ".student_chalan_form
                                       set
                                           s_c_f_month = '" . $month . "',
                                           c_c_f_id = $c_c_f_id,
                                           s_c_f_year = '" . $year . "',
                                           student_id = $student_id,
                                           student_name = '" . $student_name . "',
                                           father_name = '" . $rep_comma_f_name . "',
                                           section_id = $section_id,
                                           academic_year_id = $academic_year_id,
                                           section = '" . $section . "',
                                           class = '" . $class . "',
                                           department = '" . $department . "',
                                           chalan_form_number = $chalan_form_number,
                                           roll =  '$roll',
                                           image = '" . $image . "',
                                           system_id = $system_id,
                                           location_id = '$location_id',
                                           student_status = $student_status,
                                           id_no = '" . $id_no . "',
                                           email = '" . $email . "',
                                           school_id = $school_id,
                                           bulk_req_id = $bulk_req_id,
                                           form_type = 2,
                                           status = 1,
                                           is_bulk = 2,
                                           generation_date = '" . $current_date . "',
                                           bar_code =  '" . $barcode_image . "',
                                           reg_num =  '" . $reg_num . "',
                                           mob_num =  '" . $mob_num . "',
                                           generated_by = " . $login_by . "
                                            ";

            $query_insert_chalan_form = $this->db->query($query_insert_chalan_form_str);
            $s_c_f_id = $this->db->insert_id();
            $fee_amount_arr = array();
            $discount_amount_arr = array();
            
            /*************************
            // Check Custom Fee Exists
            *************************/
            // $custom_fee_query_check = $this->db->query("SELECT sfs.*,sfs.title as custom_fee_title,ft.*,ft.title as fee_title FROM " . get_school_db() . ".student_fee_settings sfs 
            // INNER JOIN " . get_school_db() . ".fee_types ft ON ft.fee_type_id = sfs.fee_type_id
            // WHERE sfs.month = '$month' AND sfs.year = $year AND sfs.student_id = $student_id AND sfs.fee_type = 1 AND sfs.is_bulk = 0");
            // if($custom_fee_query_check->num_rows() > 0)
            // {
            //     foreach($custom_fee_query_check->result() as $custom_fee_query_array):
            //         $check_student_chalan_detail_exist = $this->db->query("SELECT scd.s_c_f_id FROM " . get_school_db() . ".student_chalan_detail scd
            //         WHERE scd.s_c_f_id = '$s_c_f_id' AND scd.type_id = '".$custom_fee_query_array->fee_type_id."' AND scd.type = 1 AND scd.fee_type_title = '".$custom_fee_query_array->custom_fee_title."' ");
            //         // echo $this->db->last_query();
            //         // Insert Custom Fee Query
            //         $query_insert_custom_fee_detail_str = "INSERT INTO " . get_school_db() . ".student_chalan_detail
            //                     set
            //                     s_c_f_id = " . $s_c_f_id . ",
            //                     fee_type_title = '" . $custom_fee_query_array->custom_fee_title . "',
            //                     school_id = $school_id,
            //                     amount = '" .$custom_fee_query_array->amount. "',
            //                     type = '" .$custom_fee_query_array->fee_type. "',
            //                     type_id = '" .$custom_fee_query_array->fee_type_id. "',
            //                     related_s_c_d_id = 0,
            //                     issue_dr_coa_id = '" .$custom_fee_query_array->issue_dr_coa_id. "',
            //                     issue_cr_coa_id = '" .$custom_fee_query_array->issue_cr_coa_id. "',
            //                     receive_dr_coa_id = '" .$custom_fee_query_array->receive_dr_coa_id. "',
            //                     receive_cr_coa_id = '" .$custom_fee_query_array->receive_cr_coa_id. "',
            //                     cancel_dr_coa_id = '" .$custom_fee_query_array->cancel_dr_coa_id. "',
            //                     cancel_cr_coa_id = '" .$custom_fee_query_array->cancel_cr_coa_id. "',
            //                     generate_dr_coa_id = '" .$custom_fee_query_array->generate_dr_coa_id. "',
            //                     generate_cr_coa_id = '" .$custom_fee_query_array->generate_cr_coa_id. "' 
            //                 ";
            //         echo $query_insert_custom_fee_detail_str;
            //         if($check_student_chalan_detail_exist->num_rows() == 0)
            //         {
            //             $this->db->query($query_insert_custom_fee_detail_str);   
            //         }
            //     endforeach;
            // }

            /*******************************
            // Check Custom Discounts Exists
            *******************************/
            // $custom_discount_query_check = $this->db->query("SELECT sfs.*,sfs.title as custom_discount,dl.*,dl.title as discount_title FROM " . get_school_db() . ".student_fee_settings sfs 
            // INNER JOIN " . get_school_db() . ".discount_list dl ON dl.discount_id = sfs.fee_type_id
            // WHERE sfs.month = '$month' AND sfs.year = $year AND sfs.student_id = $student_id AND sfs.fee_type = 2 AND sfs.is_bulk = 0");
            // if($custom_discount_query_check->num_rows() > 0)
            // {
            //     foreach($custom_discount_query_check->result() as $custom_discount_query_array):
            //         $check_student_chalan_detail_exist = $this->db->query("SELECT scd.s_c_f_id FROM " . get_school_db() . ".student_chalan_detail scd
            //         WHERE scd.s_c_f_id = '$s_c_f_id' AND scd.type_id = '".$custom_discount_query_array->fee_type_id."' AND scd.type = 2 AND scd.fee_type_title = '".$custom_discount_query_array->custom_discount."' ");
            //         // Insert Custom Discounts Query
            //         $query_insert_custom_discount_detail_str = "INSERT INTO " . get_school_db() . ".student_chalan_detail
            //                     set
            //                     s_c_f_id = " . $s_c_f_id . ",
            //                     fee_type_title = '" . $custom_discount_query_array->custom_discount . "',
            //                     school_id = $school_id,
            //                     amount = '" .$custom_discount_query_array->amount. "',
            //                     type = '" .$custom_discount_query_array->fee_type. "',
            //                     type_id = '" .$custom_discount_query_array->fee_type_id. "',
            //                     related_s_c_d_id = 0,
            //                     issue_dr_coa_id = '" .$custom_discount_query_array->issue_dr_coa_id. "',
            //                     issue_cr_coa_id = '" .$custom_discount_query_array->issue_cr_coa_id. "',
            //                     receive_dr_coa_id = '" .$custom_discount_query_array->receive_dr_coa_id. "',
            //                     receive_cr_coa_id = '" .$custom_discount_query_array->receive_cr_coa_id. "',
            //                     cancel_dr_coa_id = '" .$custom_discount_query_array->cancel_dr_coa_id. "',
            //                     cancel_cr_coa_id = '" .$custom_discount_query_array->cancel_cr_coa_id. "',
            //                     generate_dr_coa_id = '" .$custom_discount_query_array->generate_dr_coa_id. "',
            //                     generate_cr_coa_id = '" .$custom_discount_query_array->generate_cr_coa_id. "' 
            //                 ";
            //         if($check_student_chalan_detail_exist->num_rows() == 0)
            //         {
            //             $this->db->query($query_insert_custom_discount_detail_str);   
            //         }
            //     endforeach;
            // }
            
            foreach ($chalan_query as $key_fee => $val_fee) {
                
                $amount = $val_fee['amount'];
                $fee_type = $val_fee['fee_type'];
                $fee_type_id = $val_fee['fee_type_id'];
                $fee_title = "";
                $issue_dr_coa_id = 0;
                $issue_cr_coa_id = 0;

                $receive_dr_coa_id = 0;
                $receive_cr_coa_id = 0;
                $cancel_dr_coa_id = 0;
                $cancel_cr_coa_id = 0;
                $generate_dr_coa_id = 0;
                $generate_cr_coa_id = 0;
                $fee_type_id_temp = 0;
                $fee_title = $val_fee['title'];

                if ($fee_type == 1) {

                    $fee_query_str = "SELECT ft.fee_type_id as fee_type_fee_type_id,
                                        ft.title as fee_types_title,
                                        ft.issue_dr_coa_id,
                                        ft.issue_cr_coa_id,
                                        ft.receive_dr_coa_id,
                                        ft.receive_cr_coa_id,
                                        ft.cancel_dr_coa_id,
                                        ft.cancel_cr_coa_id
                                        FROM " . get_school_db() . ".fee_types as ft
                                        INNER JOIN " . get_school_db() . ".school_fee_types sft on sft.fee_type_id = ft.fee_type_id
                                        WHERE ft.fee_type_id = $fee_type_id
                                        AND sft.school_id = $school_id";

                    $fee_query = $this->db->query($fee_query_str)->result_array();
                    if ((count($fee_query) > 0)) {
                        foreach ($fee_query as $f_key => $f_val) {
                            
                            $issue_dr_coa_id   = $f_val['issue_dr_coa_id'];
                            $issue_cr_coa_id   = $f_val['issue_cr_coa_id'];
                            $receive_dr_coa_id = $f_val['receive_dr_coa_id'];
                            $receive_cr_coa_id = $f_val['receive_cr_coa_id'];
                            $cancel_dr_coa_id  = $f_val['cancel_dr_coa_id'];
                            $cancel_cr_coa_id  = $f_val['cancel_cr_coa_id'];
                            $fee_type_id_temp  = $f_val['fee_type_fee_type_id'];

                            $fee_amount_arr[$student_id][$fee_type_id] = $amount;

                        }


                        $query_insert_chalan_form_detail_str = "INSERT INTO " . get_school_db() . ".student_chalan_detail set
                                           s_c_f_id = " . $s_c_f_id . ",
                                           fee_type_title = '" . $fee_title . "',
                                           school_id = $school_id,
                                           amount = $amount,
                                           type = $fee_type,
                                           type_id = $fee_type_id,
                                           issue_dr_coa_id = $issue_dr_coa_id,
                                           issue_cr_coa_id = $issue_cr_coa_id,
                                           receive_dr_coa_id = $receive_dr_coa_id,
                                           receive_cr_coa_id = $receive_cr_coa_id,
                                           cancel_dr_coa_id = $cancel_dr_coa_id,
                                           cancel_cr_coa_id = $cancel_cr_coa_id,
                                           generate_dr_coa_id = $generate_dr_coa_id,
                                           generate_cr_coa_id = $generate_cr_coa_id 
                                           ";
                        $query_insert_chalan_form_detail = $this->db->query($query_insert_chalan_form_detail_str);
                        $s_c_d_id = $this->db->insert_id();
                        $related_s_c_d_id[$fee_type_id] = $s_c_d_id;
                    }

                }else{

                    $discount_query_str = "SELECT ds.discount_id as discount_list_discount_id,
                                            ds.title as discount_title,
                                            ds.issue_dr_coa_id,
                                            ds.issue_cr_coa_id,
                                            ds.receive_dr_coa_id,
                                            ds.receive_cr_coa_id,
                                            ds.cancel_dr_coa_id,
                                            ds.cancel_cr_coa_id,
                                            ds.fee_type_id as discount_list_fee_type_id
                                            FROM " . get_school_db() . ".discount_list as ds
                                            INNER JOIN " . get_school_db() . ".school_discount_list sds on sds.discount_id = ds.discount_id
                                            WHERE ds.discount_id = $fee_type_id
                                            AND sds.school_id = $school_id";

                    $discount_query     = $this->db->query($discount_query_str)->result_array();
                    $related_s_c_d_id_s = 0;

                    if ((count($discount_query) > 0)) {
                        $actual_fee_id = 0;
                        foreach ($discount_query as $d_key => $d_val) {
                            
                            $issue_dr_coa_id   = $d_val['issue_dr_coa_id'];
                            $issue_cr_coa_id   = $d_val['issue_cr_coa_id'];
                            $receive_dr_coa_id = $d_val['receive_dr_coa_id'];
                            $receive_cr_coa_id = $d_val['receive_cr_coa_id'];
                            $cancel_dr_coa_id  = $d_val['cancel_dr_coa_id'];
                            $cancel_cr_coa_id  = $d_val['cancel_cr_coa_id'];
                            $fee_type_id_temp  = $d_val['discount_list_discount_id'];
                            $actual_fee_id     = $d_val['discount_list_fee_type_id'];
                            
                            $discount_amount_arr[$student_id][$actual_fee_id] = $amount;
                            
                            if (count($related_s_c_d_id) > 0) {
                                $related_s_c_d_id_s = $related_s_c_d_id[$actual_fee_id];
                            }else{
                                $related_s_c_d_id_s = 0;
                            }
                            
                            $related_s_c_d_id_s = 0; // set it to zero for any error prevention
                        }
                        
                        $chalan_type_query = "SELECT sfs.* FROM " . get_school_db() . ".student_monthly_fee_settings smfs
                        INNER join " . get_school_db() . ".student_fee_settings as sfs
                        on ((smfs.std_m_fee_settings_id = sfs.std_m_fee_settings_id))
                        WHERE
                        smfs.school_id = $school_id
                        AND smfs.student_id = $student_id
                        AND (smfs.fee_month = $month AND smfs.fee_year = $year)
                        AND smfs.b_m_c_id = $bulk_req_id 
                        AND sfs.settings_type = 3
                        ORDER BY sfs.fee_type";
                        
                        $chalan_type = $this->db->query($chalan_type_query)->result_array();
                        $settings_type_id = 0;
                        
                        if(count($chalan_type) > 0){
                            $settings_type_id = 3;
                        }
                        
                        // $check_student_custom_discount_exist = $this->db->query("SELECT scd.s_c_f_id FROM " . get_school_db() . ".student_chalan_detail scd
                        //  WHERE scd.s_c_f_id = '$s_c_f_id' AND scd.type_id = '$fee_type_id' AND scd.type = 2 AND scd.fee_type_title = '".$custom_fee_query_array->custom_fee_title."' ");
                        // if($val_fee['is_bulk'] == 1)
                        // {
                            
                            $query_insert_chalan_form_detail_str = "INSERT INTO " . get_school_db() . ".student_chalan_detail
                                        set
                                        s_c_f_id = " . $s_c_f_id . ",
                                        fee_type_title = '" . $fee_title . "',
                                        school_id = $school_id,
                                        amount = $amount,
                                        type = $fee_type,
                                        type_id = $fee_type_id,
                                        related_s_c_d_id = $related_s_c_d_id_s,
                                        issue_dr_coa_id = $issue_dr_coa_id,
                                        issue_cr_coa_id = $issue_cr_coa_id,
                                        receive_dr_coa_id = $receive_dr_coa_id,
                                        receive_cr_coa_id = $receive_cr_coa_id,
                                        cancel_dr_coa_id = $cancel_dr_coa_id,
                                        cancel_cr_coa_id = $cancel_cr_coa_id,
                                        generate_dr_coa_id = $generate_dr_coa_id,
                                        generate_cr_coa_id = $generate_cr_coa_id 
                                    ";
                              
                            $query_insert_chalan_form_detail = $this->db->query($query_insert_chalan_form_detail_str);
                        // }

                    }

                }

            }
            
            /* update student chalan form for actual amount start */
            $total = 0;
            foreach ($fee_amount_arr[$student_id] as $fr_key => $fr_val) {
                    
                $fee_amount = $fee_amount_arr[$student_id][$fr_key];
                $total += $fee_amount;
                // Class Wise Discounts 
                $discount_percent = $discount_amount_arr[$student_id][$fr_key];
                
                // Get Custom Discount
                $custom_discount = get_std_custom_discounts($student_id,$fr_key,$month,$year);
                $custom_discount_amount = $custom_discount->amount;
                $custom_discount_type = $custom_discount->discount_amount_type;
                if($custom_discount_amount > 0 || $custom_discount_amount != "")
                {    
                    if($custom_discount_type == '1')
                    {
                        $custom_discount_val = $fee_amount - $custom_discount_amount;
                        $total -= $custom_discount_val;
                    }else if($custom_discount_type == '0')
                    {
                        $custom_discount_val = round(($fee_amount * ($custom_discount_amount / 100)));
                        $total -= $custom_discount_val;
                    }
                }else{
                    if (isset($discount_percent) && $discount_percent > 0) {
                        $chalan_type_query = "SELECT sfs.* FROM " . get_school_db() . ".student_monthly_fee_settings smfs
                                            INNER join " . get_school_db() . ".student_fee_settings as sfs 
                                            on ((smfs.std_m_fee_settings_id = sfs.std_m_fee_settings_id))
                                            WHERE
                                            smfs.school_id = $school_id
                                            AND smfs.student_id = $student_id
                                            AND (smfs.fee_month = $month AND smfs.fee_year = $year)
                                            AND smfs.b_m_c_id = $bulk_req_id 
                                            AND sfs.settings_type = 3
                                            ORDER BY sfs.fee_type";
                        
                        $chalan_type = $this->db->query($chalan_type_query)->result_array();
                        //echo $chalan_type[0]['settings_type'];
                        if(count($chalan_type) > 0){
                            $total -= $discount_percent;
                        }else{
                            $discount = round(($fee_amount * ($discount_percent / 100)));
                            $total -= $discount;
                        }
                    }
                }
                
                
            }
        
            $query_update_s_c_f_str =   "UPDATE " . get_school_db() . ".student_chalan_form
                                        SET
                                        actual_amount = $total
                                        WHERE 
                                        school_id = $school_id
                                        AND student_id = $student_id
                                        AND s_c_f_id = $s_c_f_id";

            $query_update_s_c_f = $this->db->query($query_update_s_c_f_str);
            /* update student chalan form for actual amount End */

            /* actual amount End */

        }
    }

    function insert_chalan_individual($student_id, $date_month, $bulk_req_id, $c_c_f_id)
    {


        $school_id = $_SESSION['school_id'];
        $get_student_query_str = "SELECT s.name as student_name,
                                s.is_installment,
                                s.barcode_image,
                                s.roll,
                                s.gender,
                                s.section_id,
                                s.pro_section_id,
                                s.academic_year_id,
                                s.pro_academic_year_id,
                                s.section_id,
                                s.reg_num,
                                s.mob_num,
                                s.image,
                                s.system_id,
                                s.academic_year_id,
                                s.location_id,
                                s.student_status,
                                s.id_no,
                                s.email,
                                cs.title as section_nme,
                                cc.name as class_name,
                                dd.title as department_name
                                FROM " . get_school_db() . ".student s 
                                inner join " . get_school_db() . ".class_section cs on s.section_id=cs.section_id  
                                inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id
                                inner join " . get_school_db() . ".departments dd on dd.departments_id=cc.departments_id
                                where s.student_id=$student_id";
        $get_student_query = $this->db->query($get_student_query_str)->result_array();


        $query_res_p_name_str = "SELECT 
                                sp.p_name as parent_name
                                FROM " . get_school_db() . ".student s 
                                inner join " . get_school_db() . ".student_relation sr on sr.student_id=s.student_id 
                                inner join " . get_school_db() . ".student_parent sp on sp.s_p_id=sr.s_p_id
                                where
                                s.student_id= " . $student_id . "
                                AND s.school_id=$school_id 
                                and sr.relation='f'";

        $query_res_p_name = $this->db->query($query_res_p_name_str)->result_array(); //school id


        $date_month_temp = explode("-", $date_month);
        $year = $date_month_temp[0];
        $month = $date_month_temp[1];
        $current_date = date('Y-m-d H:i:s');
        $login_by = $_SESSION['login_detail_id'];

        $student_name = $get_student_query[0]['student_name'];
        $section_id = $get_student_query[0]['section_id'];
        $academic_year_id = $get_student_query[0]['academic_year_id'];
        $section = $get_student_query[0]['section_nme'];
        $class = $get_student_query[0]['class_name'];
        $department = $get_student_query[0]['department_name'];
        $roll = $get_student_query[0]['roll'];
        $image = $get_student_query[0]['image'];
        $system_id = $get_student_query[0]['system_id'];
        $location_id = $get_student_query[0]['location_id'];
        $student_status = $get_student_query[0]['student_status'];
        $id_no = $get_student_query[0]['id_no'];
        $email = $get_student_query[0]['email'];
        $father_name = $query_res_p_name[0]['parent_name'];
        $chalan_form_number = chalan_form_number();

        $barcode_image = $get_student_query[0]['barcode_image'];
        $roll = $get_student_query[0]['roll'];
        $gender = $get_student_query[0]['gender'];
        $reg_num = $get_student_query[0]['reg_num'];
        $mob_num = $get_student_query[0]['mob_num'];
        $location_id = $get_student_query[0]['location_id'];
        $image = $get_student_query[0]['image'];
        $system_id = $get_student_query[0]['system_id'];

        $chalan_query_str = "SELECT sfs.* FROM " . get_school_db() . ".student_monthly_fee_settings smfs
                INNER join " . get_school_db() . ".student_fee_settings as sfs
                on ((smfs.std_m_fee_settings_id = sfs.std_m_fee_settings_id))
                WHERE
                smfs.school_id = $school_id
                AND smfs.student_id = $student_id
                AND sfs.settings_type = 1
                AND (smfs.fee_month = $month AND smfs.fee_year = $year)
                AND smfs.b_m_c_id = $bulk_req_id";

        $chalan_query = $this->db->query($chalan_query_str)->result_array();
        /* add fee start */
        $related_s_c_d_id = array();
        if ((count($chalan_query) > 0)) {
            $date_str = "generation_date = '" . $current_date . "' , generated_by = '" . $login_by . "'";

            $query_insert_chalan_form_str = "INSERT INTO " . get_school_db() . ".student_chalan_form
                                       set
                                           s_c_f_month = '" . $month . "',
                                           c_c_f_id = $c_c_f_id,
                                           s_c_f_year = '" . $year . "',
                                           student_id = $student_id,
                                           student_name = '" . $student_name . "',
                                           father_name = '" . $father_name . "',
                                           section_id = $section_id,
                                           academic_year_id = $academic_year_id,
                                           section = '" . $section . "',
                                           class = '" . $class . "',
                                           department = '" . $department . "',
                                           chalan_form_number = $chalan_form_number,
                                           roll =  $roll,
                                           image = '" . $image . "',
                                           location_id = $location_id,
                                           student_status = $student_status,
                                           id_no = '" . $id_no . "',
                                           email = '" . $email . "',
                                           school_id = $school_id,
                                           bulk_req_id = $bulk_req_id,
                                           status = 1,
                                           is_bulk = 2,
                                           generation_date = '" . $current_date . "',
                                           bar_code =  '" . $barcode_image . "',
                                           reg_num =  '" . $reg_num . "',
                                           mob_num =  '" . $mob_num . "',
                                           system_id =  '" . $system_id . "',
                                           generated_by = " . $login_by . "
                                            ";
                                            
                                            //                                           generation_date = '" . $current_date . "',

            $query_insert_chalan_form = $this->db->query($query_insert_chalan_form_str);
            $s_c_f_id = $this->db->insert_id();

            foreach ($chalan_query as $key_fee => $val_fee) {

                $amount = $val_fee['amount'];
                $fee_type = $val_fee['fee_type'];
                $fee_type_id = $val_fee['fee_type_id'];
                $fee_title = "";
                $issue_dr_coa_id = 0;
                $issue_cr_coa_id = 0;

                $receive_dr_coa_id = 0;
                $receive_cr_coa_id = 0;
                $cancel_dr_coa_id = 0;
                $cancel_cr_coa_id = 0;
                $generate_dr_coa_id = 0;
                $generate_cr_coa_id = 0;
                $fee_type_id_temp = 0;
                $fee_title = "";

                if ($fee_type == 1) {

                    $fee_query_str = "SELECT * from " . get_school_db() . ".fee_types as ft
                                            INNER JOIN " . get_school_db() . ".school_fee_types sft on sft.fee_type_id = ft.fee_type_id
                                            WHERE ft.fee_type_id = $fee_type_id
                                            AND sft.school_id = $school_id";

                    $fee_query = $this->db->query($fee_query_str)->result_array();
                    /* add fee start */
                    if ((count($fee_query) > 0)) {
                        foreach ($fee_query as $f_key => $f_val) {
                            $issue_dr_coa_id = $f_val['issue_dr_coa_id'];
                            $issue_cr_coa_id = $f_val['issue_cr_coa_id'];
                            $receive_dr_coa_id = $f_val['receive_dr_coa_id'];
                            $receive_cr_coa_id = $f_val['receive_cr_coa_id'];
                            $cancel_dr_coa_id = $f_val['cancel_dr_coa_id'];
                            $cancel_cr_coa_id = $f_val['cancel_cr_coa_id'];

                            $fee_type_id_temp = $f_val['fee_type_id'];
                            $fee_title = $f_val['title'];

                        }


                        $query_insert_chalan_form_detail_str = "INSERT INTO " . get_school_db() . ".student_chalan_detail
                                       set
                                           s_c_f_id = " . $s_c_f_id . ",
                                           fee_type_title = '" . $fee_title . "',
                                           school_id = $school_id,
                                           amount = $amount,
                                           type = $fee_type,
                                           type_id = $fee_type_id,
                                           issue_dr_coa_id = $issue_dr_coa_id,
                                           issue_cr_coa_id = $issue_cr_coa_id,
                                           receive_dr_coa_id = $receive_dr_coa_id,
                                           receive_cr_coa_id = $receive_cr_coa_id,
                                           cancel_dr_coa_id = $cancel_dr_coa_id,
                                           cancel_cr_coa_id = $cancel_cr_coa_id,
                                           generate_dr_coa_id = $generate_dr_coa_id,
                                           generate_cr_coa_id = $generate_cr_coa_id 
                                           ";
                        $query_insert_chalan_form_detail = $this->db->query($query_insert_chalan_form_detail_str);

                        $s_c_d_id = $this->db->insert_id();
                        $related_s_c_d_id[$fee_type_id] = $this->db->insert_id();


                    }

                }
            }

        }
    }

    function disocunt_reference_id($student_chalan_detail_id, $fee_type_id)
    {
        $school_id = $_SESSION['school_id'];
        $query_update_sfs_fee_str = "UPDATE " . get_school_db() . ".student_chalan_detail
                                                   set
                                                       related_s_c_d_id = $student_chalan_detail_id
                                                      WHERE 
                                                       	s_c_d_id = " . $student_chalan_detail_id . "
                                                       AND school_id = $school_id
                                                     ";
        $query_update_sfs_fee_str = $this->db->query($query_update_sfs_fee_str);
    }

    function custom_fee_exist($student_id, $month, $year, $fee_type, $fee_type_id, $std_m_fee_settings_id)
    {

        $school_id = $_SESSION['school_id'];

        $quuery_check_str = "select * from " . get_school_db() . ".student_fee_settings 
                                    WHERE  month         = $month 
                                    AND    year          = $year 
                                    AND    student_id    = $student_id  
                                    AND    school_id     = $school_id
                                    AND    fee_type      = $fee_type
                                    AND    settings_type = 2
                                    AND    status        = 1
                                    AND    fee_type_id   = $fee_type_id";
        
        $query_check = $this->db->query($quuery_check_str)->result_array();

        if (count($query_check) > 0) 
        {
            return 0;
        } 
        else 
        {
            return 1;
        }

    }

    function bulk_condition()
    {
        $this->db->trans_begin();
        //---------------------Transection starts-------------------------------
        //----------------------------------------------------------------------

        $activity = $this->input->post('promotion_status');
        $due_days = $this->input->post('due_days');
        $bulk_req_id = $this->input->post('b_m_c_id');
        $school_id = $_SESSION['school_id'];

        if ($activity == 1) {
            $s_c_f_data['status'] = $activity;
            $s_c_f_data['is_bulk'] = 2;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);
        } elseif ($activity == 2) {
            $s_c_f_data_s['status'] = $activity;
            $s_c_f_data['is_bulk'] = 2;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');
            $s_c_f_data_a['activity'] = $activity;
            $this->db->where(array("b_m_c_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_monthly_chalan", $s_c_f_data_a);
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data_s);
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);
        } elseif ($activity == 3) {
            $s_c_f_data_s['status'] = $activity;
            $s_c_f_data['is_bulk'] = 2;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data_s);
            $s_c_f_data_a['activity'] = $activity;
            $this->db->where(array("b_m_c_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_monthly_chalan", $s_c_f_data_a);
            //echo $this->db->last_query();
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);
            $date = new DateTime();
            $data_a['approval_date'] = $date->format('Y-m-d H:i:s');
            $data_a['approved_by'] = $_SESSION['login_detail_id'];
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'approved_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $data_a);
        } elseif ($activity == 4) {
            $this->load->helper('message');
            
            $b_m_c_id = $this->input->post('b_m_c_id');
            $bulk_srt = "SELECT scf.s_c_f_id , bmc.activity,scf.status,scf.bulk_req_id, bmc.fee_month, bmc.fee_year
                    FROM " . get_school_db() . ".bulk_monthly_chalan bmc
                    INNER JOIN " . get_school_db() . ".student_chalan_form scf
                    ON scf.bulk_req_id=bmc.b_m_c_id
                    WHERE bmc.b_m_c_id = " . $b_m_c_id . "";
            $s_c_f_id = 0;
            
            $bulk_query = $this->db->query($bulk_srt);
            if (count($result = $bulk_query->result()) > 0) {
                /* Insert value in journal table start */ 
                
                foreach ($result as $rs) {
                    $s_c_f_id = $rs->s_c_f_id;

                    $date = new DateTime();
                    $dues_date = date("Y-m-d",strtotime($this->input->post("due_date")));
                    $entry_date = $date->format('Y-m-d H:i:s');
                    $fee_amount = 0;
                    $total_discount_amount = 0;
                    $fee_month = $rs->fee_month;
                    $fee_year = $rs->fee_year;
                    $make_entry_date = '01-'.$fee_month.'-'.$fee_year;
                    $make_next_month_entry_date = date("Y-m-d",strtotime($make_entry_date));
                    $total_fee_amt = 0;
                    $student_id = "";

                    $str_fee = "SELECT scf_fee.student_id,scf_fee.chalan_form_number,scf_fee.chalan_form_number, scfd_fee.* 
                                FROM " . get_school_db() . ".student_chalan_detail as scfd_fee
                                INNER JOIN " . get_school_db() . ".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                WHERE scfd_fee.s_c_f_id = $s_c_f_id
	                            AND scfd_fee.type = 1
	                            AND scfd_fee.school_id = " . $_SESSION['school_id'] . "";
	                $query_fee = $this->db->query($str_fee)->result_array();
	                
	                $sum_total_amount = "SELECT SUM(scfd_fee.amount) AS total_amount FROM ".get_school_db().".student_chalan_detail as scfd_fee 
                                    INNER JOIN ".get_school_db().".student_chalan_form as scf_fee ON scf_fee.s_c_f_id = scfd_fee.s_c_f_id 
                                    WHERE scfd_fee.s_c_f_id = $s_c_f_id AND scfd_fee.type = 1 AND 
                                    scfd_fee.school_id = ".$_SESSION['school_id']."";
                    $query_sum_total_amount = $this->db->query($sum_total_amount)->row();
                    $grand_total_amount = $query_sum_total_amount->total_amount;
                    
                    
                    if (count($query_fee) > 0) {
                        
                        foreach ($query_fee as $key_fee => $value_fee) {
                            /* create array for add fee only start */
                            $fee_type_id_fee = $value_fee['type_id'];
                            $fee_type_title = $value_fee['fee_type_title'];
                            $fee_amount = $value_fee['amount'];
                            $total_fee_amt += $fee_amount;
                            $fee_school_id = $value_fee['school_id'];
                            $fee_issue_dr_coa_id = $value_fee['issue_dr_coa_id'];
                            $fee_issue_cr_coa_id = $value_fee['issue_cr_coa_id'];
                            $fee_chalan_form_number = $value_fee['chalan_form_number'];
                            $transaction_detail = student_name_section($value_fee['student_id']);
                            // $s_c_f_id_fee =  $value['fee_type_id'];
                            
                            $student_id   = $value_fee['student_id'];

                            $s_c_d_id = $value_fee['s_c_d_id'];
                            $discount_amt = $this->is_discount_fee($s_c_d_id, $s_c_f_id);
                        
                            $fee_amount_temp = $fee_amount;

                            if ($discount_amt > 0) {

                                $discount_amt = round((($discount_amt * $fee_amount) / 100));
                                $fee_amount_temp = $fee_amount_temp - $discount_amt;

                            }
                            
                            
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
                                    WHERE f.fee_type_id = $fee_type_id_fee AND scfd_discount.s_c_f_id =$s_c_f_id
                                    AND scfd_discount.school_id = " . $_SESSION['school_id'] . "
                                    AND scfd_discount.type = 2";
   
                            $query_discount = $this->db->query($str_discount)->result_array();
                            
                            // Credit Journal Entry CHallan Issued (Zeeshan)
                            if (count($query_discount) == 0) 
                            {
                                $array_ledger_reamining = array(
                                    'entry_date' => $make_next_month_entry_date,
                                    'detail' => $fee_type_title
                                        . " - " . get_phrase('issued_challan_form') . " - " . $discount_chalan_form_number . " - " . get_phrase(' to ') . " - " . $transaction_detail,
                                    'credit' => $fee_amount,
                                    'entry_type' => 1,
                                    'type_id' => $s_c_f_id,
                                    'school_id' => $_SESSION['school_id'],
                                    'student_id' => $value_fee['student_id'],
                                    'coa_id' => $fee_issue_cr_coa_id
                                );    
                                $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_reamining);
                            }
                                
                            if (count($query_discount) > 0) {
                                foreach ($query_discount as $key_discount => $value_discount) {
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
                                        'entry_date' => $make_next_month_entry_date,
                                        'detail' => $discount_type_title . ' (' . $percentage_amount . ' %)'
                                            . " - " . get_phrase('Discount_chalan_form') . ' - ' . $discount_chalan_form_number . " - " . get_phrase('to') . " - " . $transaction_detail,
                                        'debit' => $discount_amount,
                                        'entry_type' => 1,
                                        'type_id' => $s_c_f_id,
                                        'student_id' => $value_fee['student_id'],
                                        'school_id' => $discount_school_id,
                                        'coa_id' => $discount_issue_dr_coa_id
                                    );
                                    $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_discount);
                                    
                                    // Fee Type JE    
                                    $array_ledger_reamining = array(
                                        'entry_date' => $make_next_month_entry_date,
                                        'detail' => $fee_type_title
                                            . " - " . get_phrase('issued_challan_form') . " - " . $discount_chalan_form_number . " - " . get_phrase(' to ') . " - " . $transaction_detail,
                                        'credit' => $fee_amount,
                                        'entry_type' => 1,
                                        'type_id' => $s_c_f_id,
                                        'school_id' => $_SESSION['school_id'],
                                        'student_id' => $value_fee['student_id'],
                                        'coa_id' => $fee_issue_cr_coa_id
                                    );    
                                    $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_reamining);

                                }
                            }
                            // $total_discount_amount = 0;
                            // $fee_amount = 0;
                            /* create remaining point End */

                            $remanining_amount = $grand_total_amount - $total_discount_amount;
                            
                        }//End Loop
                        
                        // Debit Journal Entry CHallan Issued (Zeeshan)
                        $array_ledger_fee = array(
                            'entry_date' => $make_next_month_entry_date,
                            'detail' => "Total Fee "
                                . ' - ' . get_phrase('issued_challan_form') . " - " . $fee_chalan_form_number . " - " . get_phrase('to') . " - " . $transaction_detail,
                            'debit' => $remanining_amount,
                            'entry_type' => 1,
                            'type_id' => $s_c_f_id,
                            'school_id' => $fee_school_id,
                            'student_id' => $value_fee['student_id'],
                            'coa_id' => $fee_issue_dr_coa_id
                        );
                        $this->db->insert(get_school_db() . ".journal_entry", $array_ledger_fee);
                        // Debit Journal Entry CHallan Issued (Zeeshan)
                        
                    }
                    
                    // echo "total_discount = ".$total_discount_amount;
                    
                    // echo "total_fee_amt = ".$total_fee_amt;
                    
                    // exit;
                    
                    
                    
                    //--------------Send SMS On Challan Issue-------------------
                    //----------------------------------------------------------
                    
                    $student_info = get_student_info($student_id);
                    $student_name = $student_info[0]['student_name'];
                    $mob_num = $student_info[0]['mob_num'];
                    $email = $student_info[0]['email'];
                    $student_id = $student_info[0]['student_id'];
                    $message = "Dear Parent, your Child ".$student_name."'s fee is due by ".date_view($dues_date).", for more details please login to ".base_url()."login .";
                    $response = send_sms($mob_num, 'Indici_edu', $message, $student_id,4);
                
                    //--------------Send SMS On Challan End-------------------
                    //----------------------------------------------------------
                    
                    // Send Email Challan Issue
                    $email_message = "<b>Dear Parent</b> <br><br> Most respectfully, it is stated that an amount of ".$remanining_amount.", tuition fee for the month of ".date_view($dues_date)." is pending against school fee of your son/daughter 
                                     ".$student_info[0] ['student_name']." We earnestly request you to kindly settle the payment as early as possible so that ".$student_info[0] ['student_name']." can smoothly continue his studies at our 
                                      prestigious institute. Your prompt attention in this matter will be highly appreciated. <br> To view the monthly challan, you are requested to logon to ".base_url()."<br>
                                      <br> In case of any query, you may please contact with school adminitrator";
                    
                    // $to_email = $email;
                    // $to_email = $student_info[0]['email'];
                    $to_email = "zeeshanarain4455@gmail.com";
                    
                    $subject = "Monthly Challan Issued";
                    $email_layout = get_email_layout($email_message);
                    email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, 4);
                    
                    $device_id  =   get_user_device_id(6 , $student_id , $_SESSION['school_id']);
                    $title      =   "Monthly Challan Issued";
                    $message    =   "A New Challan Form has been issued.";
                    $link       =    base_url()."parents/invoice";
                    sendNotificationByUserId($device_id, $title, $message, $link , $student_id , 6);
                    // Send Email Challan Issue
                    
                                        
                //     //--------------Send Notification To Mobile Application Start by interns-------------------
                //      //----------------------------------------------------------
                
                    $student_info_for_callan = get_student_name_and_academic_year_id($student_id, $_SESSION['school_id']);
                    $parent_idd = get_parent_idd($student_id);
                    $sect_id    =    get_student_info1($student_id);
                   
                //   echo $parent_idd;
                     $stdname    =    get_student_info($student_id);
                     $std_name   = $stdname[0]['student_name'];
                  // $class_idd = get_class_id($sect_id->section_id);
                     //$class_name = get_class_name($class_idd);
                   
                   
                    $d=array(
                    'title'=> 'Challan Issued',
                    'body'=>'A New Challan Form has been issued for '.$std_name.'.',
                    );
                    $d2 = array(
                            'screen'=>'payment',
                            'student_id'=> $student_id,
                            'section_id'=> $student_info_for_callan['section_id'],
                            'academic_year_id'=> $student_info_for_callan['academic_year_id'],
                        );
                   
                  
                if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    }
        
            
                    
                    //--------------Send Notification To Mobile Application End by interns-------------------
                    
                }
    
                /* {
                     $s_c_f_id = $rs->s_c_f_id;


                     $str = "SELECT scd.fee_type_title , scd.amount , scd.school_id ,
                                         scd.issue_dr_coa_id ,scd.issue_cr_coa_id
                                         FROM
                                         " . get_school_db() . ".student_chalan_form as scf
                                         INNER JOIN
                                         " . get_school_db() . ".student_chalan_detail as scd
                                         ON
                                         scf.s_c_f_id = scd.s_c_f_id
                                         WHERE
                                         scd.s_c_f_id = " . $s_c_f_id . "
                                         AND scd.school_id = " . $_SESSION['school_id'] . "
                                         AND scd.type !=3
                                         AND (scd.issue_cr_coa_id = 0)
                                         AND scd.amount>0";
                                         //scd.issue_dr_coa_id = 0 OR scd.issue_cr_coa_id = 0


                     $query = $this->db->query($str);

                     if (count($query->result()) > 0)
                     {

                         //return "Incomplete Chart of Account Settings.";
                         $this->session->set_flashdata('journal_entry', get_phrase('challan_form_not_issued_._incomplete_chart_of_account_settings_.'));
                         redirect(base_url() . 'monthly_fee/monthly_bulk_listing/');

                     }
                     else
                     {
                         $date = new DateTime();
                         $entry_date = $date->format('Y-m-d H:i:s');
                         $str1 = "SELECT scd.s_c_d_id, scf.student_id, scd.fee_type_title , scd.amount , scd.school_id, scf.chalan_form_number,
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


                         $query1 = $this->db->query($str1);


                         foreach ($query1->result() as $row)
                         {

                             if ($row->amount > 0)
                             {

                                 $transaction_detail = student_name_section($row->student_id);
                                 if ($row->type == 2 || $row->type == 4)
                                 {
                                     $amount_issued = $row->amount;//(-1) * ($row->amount);
                                 }
                                 else if($row->type == 1 || $row->type == 5)
                                 {
                                     $amount_issued = $row->amount;
                                 }
                                 $data_debit = array(
                                     'entry_date' => $entry_date,
                                     'detail' => $row->fee_type_title
                                         . "-". get_phrase('issued_challan_form_no'). "-" . $row->chalan_form_number ."-".get_phrase('to'). "-" . $transaction_detail,
                                     'debit' => $amount_issued,
                                     'entry_type' => 1,
                                     'type_id' => $s_c_f_id,
                                     'school_id' => $row->school_id,
                                     'coa_id' => $row->issue_dr_coa_id
                                 );

                                 $data_credit = array(
                                     'entry_date' => $entry_date,
                                     'detail' => $row->fee_type_title
                                          ."-". get_phrase('issued_challan_form_no'). "-" . $row->chalan_form_number ."-".get_phrase('to')."-" . $transaction_detail,
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
                 } */ //
            }

            /*Insert value in journal table End */

            /* Journal Entry End */
            $s_c_f_data_s['status'] = $activity;
            //$s_c_f_data['is_bulk']=2;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data_s);

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);

            $date = new DateTime();
            $data_a['approval_date'] = $date->format('Y-m-d H:i:s');
            $data_a['approved_by'] = $_SESSION['login_detail_id'];
            $s_c_f_data_a['activity'] = $activity;
            $this->db->where(array("b_m_c_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_monthly_chalan", $s_c_f_data_a);

            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'approved_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $data_a);

            $issue_date = $date->format('Y-m-d H:i:s');
            $issued_by = $_SESSION['login_detail_id'];
            $date = date_slash($this->input->post('due_date'));
            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET  `issue_date` ='$issue_date',
                     `issued_by` =$issued_by,
                     `due_date` ='$dues_date' WHERE  `bulk_req_id` =  $bulk_req_id AND  `issued_by` =0 AND  `school_id` =  $school_id and is_bulk=2 and status<5");

        } elseif ($activity == 5) {
            $s_c_f_data_s['status'] = $activity;
            $s_c_f_data['is_bulk'] = 2;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data_s);
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);
            $date = new DateTime();
            $data_a['approval_date'] = $date->format('Y-m-d H:i:s');
            $data_a['approved_by'] = $_SESSION['login_detail_id'];
            $s_c_f_data_a['activity'] = $activity;
            $this->db->where(array("b_m_c_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_monthly_chalan", $s_c_f_data_a);
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'approved_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $data_a);
            $issue_date = $date->format('Y-m-d H:i:s');
            $issued_by = $_SESSION['login_detail_id'];
            $date = date('Y-m-d', strtotime('+' . $due_days . ' days'));
            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET  `issue_date` ='$issue_date',
                `issued_by` =$issued_by,
                `due_date` ='$date' WHERE  `bulk_req_id` =  $bulk_req_id AND  `issued_by` =0 AND  `school_id` =  $school_id and is_bulk=2 and status<5");
            $received_by = $_SESSION['login_detail_id'];
            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET  `received_date` ='$issue_date', payment_date='$issue_date' ,received_amount=actual_amount,
                `received_by` =$received_by WHERE  `bulk_req_id` =  $bulk_req_id AND  `received_by` =0 AND  `school_id` =  $school_id and is_bulk=2 and status<5");
        } elseif ($activity == 8) {

            $s_c_f_data_s['status'] = 5;
            $s_c_f_data['is_bulk'] = 2;
            $s_c_f_data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $s_c_f_data['generation_date'] = $date->format('Y-m-d H:i:s');
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data_s);
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'generated_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $s_c_f_data);
            $date = new DateTime();
            $data_a['approval_date'] = $date->format('Y-m-d H:i:s');
            $data_a['approved_by'] = $_SESSION['login_detail_id'];
            $s_c_f_data_a['activity'] = $activity;
            $s_c_f_data_a['status'] = 0;
            $this->db->where(array("b_m_c_id" => $bulk_req_id, 'school_id' => $school_id));
            $this->db->update(get_school_db() . ".bulk_monthly_chalan", $s_c_f_data_a);
            $this->db->where(array("bulk_req_id" => $bulk_req_id, 'approved_by' => 0, 'school_id' => $school_id, 'is_bulk' => 2, 'status <' => 5));
            $this->db->update(get_school_db() . ".student_chalan_form", $data_a);
            $issue_date = $date->format('Y-m-d H:i:s');
            $issued_by = $_SESSION['login_detail_id'];
            $date = date('Y-m-d', strtotime('+' . $due_days . ' days'));
            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET  `issue_date` ='$issue_date',
                        `issued_by` =$issued_by,
                        `due_date` ='$date' WHERE  `bulk_req_id` =  $bulk_req_id AND  `issued_by` =0 AND  `school_id` =  $school_id and is_bulk=2 and status<5");
            $received_by = $_SESSION['login_detail_id'];
            $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form SET  `received_date` ='$issue_date', payment_date='$issue_date' ,received_amount=actual_amount,
                        `received_by` =$received_by WHERE  `bulk_req_id` =  $bulk_req_id AND  `received_by` =0 AND  `school_id` =  $school_id and ,is_bulk=2 and status<5");


        }
        
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        redirect($_SERVER['HTTP_REFERER']);


    }

    function insert_chalan($student_id, $form_type, $bulk_req_id = 0, $date_month)
    {


        $school_id = $_SESSION['school_id'];
        $s_c_f_id = 0;
        $totle = 0;
        $discount_amount = 0;

        $query_res = $this->db->query("SELECT 
                                        s.name as student_name,
                                        s.is_installment,
                                        s.barcode_image,
                                        s.roll,
                                        s.gender,
                                        s.section_id,
                                        s.pro_section_id,
                                        s.academic_year_id,
                                        s.pro_academic_year_id,
                                        s.section_id,
                                        s.reg_num,
                                        s.mob_num,
                                        s.image,
                                        s.system_id,
                                        s.academic_year_id,
                                        s.location_id,
                                        s.student_status,
                                        s.id_no,
                                        s.email,
                                        cs.title as section_nme,
                                        cc.name as class_name,
                                        dd.title as department_name
                                        FROM " . get_school_db() . ".student s 
                                        inner join " . get_school_db() . ".class_section cs on s.section_id=cs.section_id  
                                        inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id
                                        inner join " . get_school_db() . ".departments dd on dd.departments_id=cc.departments_id
                                        where s.student_id=$student_id")->result_array();

        $st_parent = $this->db->query("select sp.p_name as parent_name from  
                                        " . get_school_db() . ".student s inner join  " . get_school_db() . ".student_relation sr on sr.student_id=s.student_id 
                                        inner join " . get_school_db() . ".student_parent sp on sp.s_p_id=sr.s_p_id
                                        where s.student_id=$student_id and sr.relation='f'"
        )->result_array();

        $section_id = $query_res[0]['section_id'];

        if ($form_type == 3) {
            $section_id = $query_res[0]['pro_section_id'];
        }

        $query_re = $this->db->query("SELECT $student_id as sid, ft.fee_type_id, ft.title, ccfe.order_num,ccfe.value,ccf.c_c_f_id,ccf.due_days , 
                                        ft.generate_dr_coa_id,
                                        ft.generate_cr_coa_id,
                                        ft.issue_dr_coa_id,
                                        ft.issue_cr_coa_id,
                                        ft.receive_dr_coa_id,
                                        ft.receive_cr_coa_id,
                                        ft.cancel_dr_coa_id,
                                        ft.cancel_cr_coa_id
                                        FROM  " . get_school_db() . ".fee_types
                                         ft inner join " . get_school_db() . ".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id 
                                         inner join " . get_school_db() . ".class_chalan_form ccf on
                                         ccf.c_c_f_id= ccfe.c_c_f_id
                                         where ccf.section_id=$section_id and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id   
                                           ORDER BY ccfe.order_num")->result_array();

        if (count($query_re) == 0) {
            $s_c_f_id = 0;
        } else {
            $chalan_setting = $this->db->query("select * from " . get_school_db() . ".chalan_settings where school_id=$school_id")->result_array();

            $due_days = $query_re[0]['due_days'];
            $data = array();
            $related_ids = array();
            $c_c_f_id = $query_re[0]['c_c_f_id'];

            $data['c_c_f_id'] = $c_c_f_id;
            $data['school_id'] = $school_id;
            $data['status'] = 1;
            $data['fee_month_year'] = $date_month;
            $data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $st_parent_v = "";

            if (count($st_parent) > 0) {
                $st_parent_v = $st_parent[0]['parent_name'];
            }
            $data['generation_date'] = $date->format('Y-m-d H:i:s');
            $data['chalan_form_number'] = chalan_form_number();
            $data['student_id'] = $student_id;
            $data['student_name'] = $query_res[0]['student_name'];
            $data['roll'] = $query_res[0]['roll'];
            $data['father_name'] = $st_parent_v;
            $data['bar_code'] = $query_res[0]['barcode_image'];
            if (count($chalan_setting) > 0) {
                $data['school_name'] = $chalan_setting[0]['school_name'];
                $data['school_logo'] = $chalan_setting[0]['logo'];
                $data['school_address'] = $chalan_setting[0]['address'];
                $data['school_terms'] = $chalan_setting[0]['terms'];
                $data['school_bank_detail'] = $chalan_setting[0]['bank_details'];

            } else {
                $data['school_name'] = "";
                $data['school_logo'] = "";
                $data['school_address'] = "";
                $data['school_terms'] = "";
                $data['school_bank_detail'] = "";
            }
            $data['section'] = $query_res[0]['section_nme'];
            $data['class'] = $query_res[0]['class_name'];
            $data['is_bulk'] = 2;
            $data['bulk_req_id'] = $bulk_req_id;
            $data['department'] = $query_res[0]['department_name'];
            $data['form_type'] = $form_type;
            $data['due_days'] = $due_days;

            $data['section_id'] = $query_res[0]['section_id'];
            $data['reg_num'] = $query_res[0]['reg_num'];
            $data['mob_num'] = $query_res[0]['mob_num'];
            $data['image'] = $query_res[0]['image'];
            $data['system_id'] = $query_res[0]['system_id'];
            $data['academic_year_id'] = $query_res[0]['academic_year_id'];
            $data['location_id'] = $query_res[0]['location_id'];
            $data['student_status'] = $query_res[0]['student_status'];
            $data['id_no'] = $query_res[0]['id_no'];
            $data['email'] = $query_res[0]['email'];


            $date_month_arr = explode("-", $date_month);
            $data['s_c_f_year'] = $date_month_arr[0];
            $data['s_c_f_month'] = $date_month_arr[1];

            $this->db->insert(get_school_db() . '.student_chalan_form', $data);

            $s_c_f_id = $this->db->insert_id();

            /*if($query_res[0]['is_installment']==1)
            {
                $check_installment=$this->db->query("select * from ".get_school_db().".student_m_installment where month=month('$date_month') and year=year('$date_month') and student_id = ".$student_id." and school_id=".$_SESSION['school_id'])->result_array();

                if(count($check_installment)>0)
                {
                    //$totle+=$this->arears($student_id,$s_c_f_id);
                    $totle+=$this->student_installments($student_id,$s_c_f_id,$check_installment);
                }
                /*
                else
                {
                    return $s_c_f_id;
                }

            }
            else
                */
            // {
            //$this->db->insert(get_school_db().'.student_chalan_form',$data);
            //$s_c_f_id=$this->db->insert_id();
            //$totle=0;

            $added_fee_types_arr = array();
            $added_fee_types = 0;
            foreach ($query_re as $rec_row1) {
                $data_detail1 = array();
                $data_detail1['s_c_f_id'] = $s_c_f_id;
                $data_detail1['fee_type_title'] = $rec_row1['title'];
                $data_detail1['school_id'] = $school_id;
                $data_detail1['amount'] = $rec_row1['value'];
                $data_detail1['type'] = 1;
                $data_detail1['type_id'] = $rec_row1['fee_type_id'];
                $added_fee_types_arr[] = $rec_row1['fee_type_id'];
                /* add six field customize */

                /* Generate setting start */
                $data_detail1['generate_dr_coa_id'] = $rec_row1['generate_dr_coa_id'];//$row->generate_dr_coa_id;
                $data_detail1['generate_cr_coa_id'] = $rec_row1['generate_cr_coa_id']; //$row->generate_dr_coa_id;
                /* Generate setting End */

                $data_detail1['issue_dr_coa_id'] = $rec_row1['issue_dr_coa_id'];
                $data_detail1['issue_cr_coa_id'] = $rec_row1['issue_cr_coa_id'];

                $data_detail1['receive_dr_coa_id'] = $rec_row1['receive_dr_coa_id'];
                $data_detail1['receive_cr_coa_id'] = $rec_row1['receive_cr_coa_id'];

                $data_detail1['cancel_dr_coa_id'] = $rec_row1['issue_cr_coa_id'];
                $data_detail1['cancel_cr_coa_id'] = $rec_row1['issue_dr_coa_id'];
                /* End of add six field customize */

                $totle = $rec_row1['value'] + $totle;

                $this->db->insert(get_school_db() . ".student_chalan_detail", $data_detail1);

                $s_c_d_id_temp = $this->db->insert_id();

                $related_ids[$rec_row1['fee_type_id']]['related_s_c_d_id'] = $s_c_d_id_temp;
                $related_ids[$rec_row1['fee_type_id']]['fee_value'] = $rec_row1['value'];
            }
            //}
            if (count($added_fee_types_arr) > 0) {
                $added_fee_types = implode(',', $added_fee_types_arr);
            }

            $query_rec_str = "SELECT dl.discount_id,dl.title, dl.fee_type_id,
                                            ccd.value,ccd.order_num,
                                            dl.generate_dr_coa_id,dl.generate_cr_coa_id,
                                            dl.issue_dr_coa_id,dl.issue_cr_coa_id,
                                            dl.receive_dr_coa_id, dl.receive_cr_coa_id,
                                            dl.cancel_dr_coa_id, dl.cancel_cr_coa_id
                                                FROM " . get_school_db() . ".discount_list dl 
                                                    inner join " . get_school_db() . ".class_chalan_discount ccd 
                                                    on 
                                                    ccd.discount_id=dl.discount_id
                                                    inner join " . get_school_db() . ".class_chalan_form ccf on ccf.c_c_f_id= ccd.c_c_f_id 
                                                    where ccf.section_id=$section_id and ccf.type=$form_type 
                                                    and ccf.status=1 and dl.fee_type_id in ($added_fee_types)
                                                    and ccf.school_id=" . $_SESSION['school_id'] . " 
                                                    ORDER BY 
                                                    ccd.order_num";

            $query_rec = $this->db->query($query_rec_str)->result_array();

            // exit('exit33333333');
            foreach ($query_rec as $rec_row) {
                $data_detail = array();
                $data_detail['s_c_f_id'] = $s_c_f_id;

                $data_detail['fee_type_title'] = $rec_row['title'];

                $data_detail['school_id'] = $school_id;
                $amount = $rec_row['value'];
                $data_detail['type'] = 2;
                //$data_detail['coa_id']=$rec_row['coa_id'];
                $data_detail['type_id'] = $rec_row['discount_id'];
                $data_detail['related_s_c_d_id'] = $related_ids[$rec_row['fee_type_id']]['related_s_c_d_id'];
                $data_detail['amount'] = $rec_row['value'];
                // $data_detail['amount']= $rec_row['value'];

                $fee_value = $related_ids[$rec_row['fee_type_id']]['fee_value'];

                $discount_value = round(($fee_value) * ($data_detail['amount'] / 100));

                $totle = $totle - $discount_value;

                /* add six field customize from discount list */

                /* Generate setting start */
                $data_detail['generate_dr_coa_id'] = $rec_row['generate_dr_coa_id'];//$row->generate_dr_coa_id;
                $data_detail['generate_cr_coa_id'] = $rec_row['generate_cr_coa_id']; //$row->generate_cr_coa_id;
                /* Generate setting End */

                $data_detail['issue_dr_coa_id'] = $rec_row['issue_dr_coa_id'];
                $data_detail['issue_cr_coa_id'] = $rec_row['issue_cr_coa_id'];

                $data_detail['receive_dr_coa_id'] = $rec_row['receive_dr_coa_id'];
                $data_detail['receive_cr_coa_id'] = $rec_row['receive_cr_coa_id'];

                $data_detail['cancel_dr_coa_id'] = $data_detail['issue_cr_coa_id'];//$rec_row['cancel_dr_coa_id'];
                $data_detail['cancel_cr_coa_id'] = $data_detail['issue_dr_coa_id']; //$rec_row['cancel_cr_coa_id'];
                /* End of add six field customize from discount list */

                $this->db->insert(get_school_db() . ".student_chalan_detail", $data_detail);

                // exit('exit');
            }

            $totle += $this->arears($student_id, $s_c_f_id);

            $discount_amount = $this->monthly_discounts($date_month, $student_id, $s_c_f_id, $added_fee_types, $related_ids);
            $update_amount['actual_amount'] = $totle - $discount_amount;

            $combined_fee = $this->monthly_combined_fee($date_month, $student_id, $s_c_f_id, $added_fee_types, $related_ids);
            $update_amount['actual_amount'] = $totle + $combined_fee;
            //$update_amount['actual_amount'] = $totle;
            // exit('exit1');
            $this->db->where("s_c_f_id", $s_c_f_id);
            $this->db->update(get_school_db() . ".student_chalan_form", $update_amount);

            //   echo   $this->db->last_query();

        }
        //exit;
        return $s_c_f_id;
    }

    function monthly_discounts($date_month, $student_id, $s_c_f_id, $added_fee_types, $related_ids)
    {
        $amount = 0;
        $school_id = $_SESSION['school_id'];
        $date_month = explode('-', $date_month);
        $year = $date_month[0];
        $month = $date_month[1];

        $str = "SELECT sfs.title as setting_title,sfs.amount as fee_setting_amount,sfs.fee_type_id as setting_fee_type_id,
                    dl.title as discount_title, dl.fee_type_id as discount_fee_type_id,dl.issue_cr_coa_id,dl.receive_dr_coa_id
                  FROM " . get_school_db() . ".student_fee_settings sfs
                    inner join  " . get_school_db() . ".discount_list as dl 
                    on (sfs.fee_type_id = dl.discount_id and dl.fee_type_id in($added_fee_types)  
                    AND dl.status = 1 
                    AND((dl.issue_cr_coa_id>0) AND (dl.receive_dr_coa_id>0)))
                    where sfs.student_id = $student_id
                        AND sfs.school_id = $school_id
                        AND sfs.month = $month
                        AND sfs.year = $year
                        AND sfs.fee_type = 2
                        AND sfs.settings_type = 2
                        ";

        $discount_row = $this->db->query($str)->result_array();

        if (count($discount_row) > 0) {
            $related_ids_str = 0;
            foreach ($discount_row as $row) {
                $data_detail_dis['s_c_f_id'] = $s_c_f_id;

                $data_detail_dis['fee_type_title'] = $row['setting_title'];

                if ($data_detail_dis['fee_type_title'] == "") {
                    $data_detail_dis['fee_type_title'] = $row['discount_title'];
                }

                $discount_id = $row['setting_fee_type_id'];

                $data_detail_dis['issue_cr_coa_id'] = $row['issue_cr_coa_id'];
                $data_detail_dis['receive_dr_coa_id'] = $row['receive_dr_coa_id'];


                $data_detail_dis['issue_dr_coa_id'] = 0;
                $data_detail_dis['receive_cr_coa_id'] = 0;
                $data_detail_dis['cancel_dr_coa_id'] = 0;
                $data_detail_dis['cancel_cr_coa_id'] = 0;
                $data_detail_dis['generate_dr_coa_id'] = 0;
                $data_detail_dis['generate_cr_coa_id'] = 0;


                $data_detail_dis['school_id'] = $_SESSION['school_id'];
                $data_detail_dis['amount'] = $row['fee_setting_amount'];
                $fee_setting_amount = $data_detail_dis['amount'];

                $related_ids_str = $related_ids[$row['discount_fee_type_id']]['related_s_c_d_id'];
                $fee_value = $related_ids[$row['discount_fee_type_id']]['fee_value'];


                if (($related_ids_str > 0) && ($fee_value > 0)) {
                    $data_detail_dis['related_s_c_d_id'] = $related_ids_str;
                    $discount_value = round(($fee_value) * ($fee_setting_amount / 100));
                    $amount += $discount_value;
                    $data_detail_dis['type'] = 4;
                    $data_detail_dis['type_id'] = $discount_id;

                    $this->db->insert(get_school_db() . ".student_chalan_detail", $data_detail_dis);
                }

            }
        }
        return $amount;
    }

    function monthly_combined_fee($date_month, $student_id, $s_c_f_id, $added_fee_types, $related_ids)
    {
        $amount = 0;
        $school_id = $_SESSION['school_id'];
        $date_month = explode('-', $date_month);
        $year = $date_month[0];
        $month = $date_month[1];

        /* $str = "SELECT sfs.title as setting_title,
                        sfs.amount as fee_setting_amount,
                        sfs.fee_type_id as setting_fee_type_id,
                        ft.title as discount_title,
                        ft.fee_type_id as discount_fee_type_id,
                        ft.issue_cr_coa_id,
                        ft.issue_dr_coa_id,
                        ft.receive_dr_coa_id,
                        ft.receive_cr_coa_id
                                          FROM ".get_school_db().".student_fee_settings sfs
                                          inner join  ".get_school_db().".fee_types as ft
                                          on (sfs.fee_type_id = ft.fee_type_id and ft.fee_type_id in($added_fee_types)
                                                      AND ft.status = 1
                                                      AND((ft.issue_cr_coa_id>0)
                                                      AND(ft.issue_dr_coa_id>0)
                                                      AND(ft.receive_dr_coa_id>0)
                                                      AND(ft.receive_cr_coa_id>0)))
                                          where sfs.student_id = $student_id
                                              AND sfs.school_id = $school_id
                                              AND sfs.month = $month
                                              AND sfs.year = $year
                                              AND sfs.fee_type = 1
                                              AND sfs.settings_type = 2
                                              ";*/
        $str = "SELECT 
                   sfs.title AS setting_title,
                   sfs.amount AS fee_setting_amount,
                   sfs.fee_type_id AS setting_fee_type_id,
                   ft.title AS discount_title,
                   ft.fee_type_id AS discount_fee_type_id,
                   ft.issue_cr_coa_id,
                   ft.issue_dr_coa_id,
                   ft.receive_dr_coa_id,
                   ft.receive_cr_coa_id
                FROM   " . get_school_db() . ".student_fee_settings sfs
                       INNER JOIN " . get_school_db() . ".fee_types AS ft
                               ON ( sfs.fee_type_id = ft.fee_type_id
                                    AND ft.fee_type_id IN( $added_fee_types )
                                    AND ft.status = 1
                                    AND ( ( ft.issue_cr_coa_id > 0 )
                                          AND ( ft.issue_dr_coa_id > 0 )
                                          AND ( ft.receive_dr_coa_id > 0 )
                                          AND ( ft.receive_cr_coa_id > 0 ) ) )
                WHERE  sfs.student_id = $student_id
                       AND sfs.school_id = $school_id
                       AND sfs.month = $month
                       AND sfs.year = $year
                       AND sfs.fee_type = 1
                       AND sfs.settings_type = 2 ";


        $combined_fee_row = $this->db->query($str)->result_array();

        if (count($combined_fee_row) > 0) {
            $related_ids_str = 0;
            foreach ($combined_fee_row as $row) {
                $data_detail_dis['s_c_f_id'] = $s_c_f_id;

                $data_detail_dis['fee_type_title'] = $row['setting_title'] . " - Combined fee";

                if ($data_detail_dis['fee_type_title'] == "") {
                    $data_detail_dis['fee_type_title'] = $row['discount_title'];
                }

                $discount_id = $row['setting_fee_type_id'];

                $data_detail_dis['issue_cr_coa_id'] = $row['issue_cr_coa_id'];
                $data_detail_dis['issue_dr_coa_id'] = $row['issue_dr_coa_id'];;
                $data_detail_dis['receive_dr_coa_id'] = $row['receive_dr_coa_id'];
                $data_detail_dis['receive_cr_coa_id'] = $row['receive_cr_coa_id'];


                $data_detail_dis['cancel_dr_coa_id'] = 0;
                $data_detail_dis['cancel_cr_coa_id'] = 0;
                $data_detail_dis['generate_dr_coa_id'] = 0;
                $data_detail_dis['generate_cr_coa_id'] = 0;


                $data_detail_dis['school_id'] = $_SESSION['school_id'];
                $data_detail_dis['amount'] = $row['fee_setting_amount'];
                $fee_setting_amount = $data_detail_dis['amount'];

                // $related_ids_str = $related_ids[$row['discount_fee_type_id']]['related_s_c_d_id'];
                //  $fee_value = $related_ids[$row['discount_fee_type_id']]['fee_value'];


                //  if(($related_ids_str>0) && ($fee_value>0))
                {

                    $data_detail_dis['type'] = 5;
                    $data_detail_dis['type_id'] = $discount_id;

                    $this->db->insert(get_school_db() . ".student_chalan_detail", $data_detail_dis);
                }

            }
        }
        return $amount;
    }

    /* */
    function arears($student_id, $s_c_f_id)
    {
/////////////////////////////////////////


        $get_val = $this->db->query("select * from " . get_school_db() . ".misc_challan_coa_settings where school_id=" . $_SESSION['school_id'] . " and type='arrears_coa'")->result_array();

        $arrears_ammount = 0;
        if (count($get_val) > 0) {
            $qur_are = $this->db->query("select arrears,s_c_f_id from " . get_school_db() . ".student_chalan_form where school_id=" . $_SESSION['school_id'] . " 
            and status=5 and student_id=" . $student_id . " and is_processed=0 and arrears_status=1")->result_array();


            $data_arrears['s_c_f_id'] = $s_c_f_id;
            $data_arrears['fee_type_title'] = 'Arrears';
            //$data_arrears['coa_id']=$get_val[0]['detail'];
            $data_arrears['school_id'] = $_SESSION['school_id'];

            /*$data_arrears['coa_id']=$get_val[0]['detail'];*/

            /* generate setting start */
            $data_arrears['generate_dr_coa_id'] = $get_val[0]['generate_dr_coa_id'];;//$row->generate_dr_coa_id;
            $data_arrears['generate_cr_coa_id'] = $get_val[0]['generate_cr_coa_id'];; //$row->generate_cr_coa_id;
            /* generate setting start */

            $data_arrears['issue_dr_coa_id'] = $get_val[0]['issue_dr_coa_id'];
            $data_arrears['issue_cr_coa_id'] = $get_val[0]['issue_cr_coa_id'];

            $data_arrears['receive_dr_coa_id'] = $get_val[0]['receive_dr_coa_id'];
            $data_arrears['receive_cr_coa_id'] = $get_val[0]['receive_cr_coa_id'];

            $data_arrears['cancel_dr_coa_id'] = $get_val[0]['cancel_dr_coa_id'];
            $data_arrears['cancel_cr_coa_id'] = $get_val[0]['cancel_cr_coa_id'];
            /*$data_arrears['coa_id']=$get_val[0]['detail'];*/

            foreach ($qur_are as $val_amu) {
                $arrears_ammount = $val_amu['arrears'];

                $data_arrears['amount'] = $arrears_ammount;
                $data_arrears['type'] = 3;
                $data_arrears['type_id'] = 0;
                $this->db->insert(get_school_db() . ".student_chalan_detail", $data_arrears);

                $this->db->where('s_c_f_id', $s_c_f_id);
                $this->db->update(get_school_db() . ".student_chalan_form", array('arrears_status' => 0,));
            }
        }

        return $arrears_ammount;
/////////////////////////////////////////////////////////////////
    }

    function student_installments($student_id, $s_c_f_id, $check_installment)
    {


/////////////////////////////////////////

//$qur_are=$this->db->query("select arrears,s_c_f_id from ".get_school_db().".student_chalan_form where school_id=".$_SESSION['school_id']." and status=5 and student_id=$student_id and is_processed=0 and arrears_status=1")->result_array();


        $data_installment['s_c_f_id'] = $s_c_f_id;
        $data_installment['fee_type_title'] = $check_installment[0]['title'];   //'Installment';
        $data_installment['school_id'] = $_SESSION['school_id'];
        $fee_type_id = $check_installment[0]['fee_type_id'];

        //$data_arrears['coa_id']=$check_installment[0]['coa_id'];

        /* */
        $fee_str = "SELECT * FROM " . get_school_db() . ".fee_types WHERE fee_type_id = " . $fee_type_id . "
																			and school_id=" . $_SESSION['school_id'] . "";
        $query_fee = $this->db->query($fee_str);

        $row_fee = $query_fee->row();

        if (count($row_fee)) {

            $data_installment['generate_dr_coa_id'] = $row_fee->generate_dr_coa_id;
            $data_installment['generate_cr_coa_id'] = $row_fee->generate_cr_coa_id;


            $data_installment['issue_dr_coa_id'] = $row_fee->issue_dr_coa_id;
            $data_installment['issue_cr_coa_id'] = $row_fee->issue_cr_coa_id;

            $data_installment['receive_dr_coa_id'] = $row_fee->receive_dr_coa_id;
            $data_installment['receive_cr_coa_id'] = $row_fee->receive_cr_coa_id;

            $data_installment['cancel_dr_coa_id'] = $row_fee->issue_cr_coa_id;
            $data_installment['cancel_cr_coa_id'] = $row_fee->issue_dr_coa_id;
        }
        /* */
        //$arrears_ammount=0;

        //foreach($check_installment as $val_amu){
        //$arrears_ammount+=$check_installment[0]['amount'];

        //$this->db->where('s_c_f_id',$s_c_f_id);
        //$this->db->update(get_school_db().".student_chalan_form",array('arrears_status'=>0));
//}

        //$data_arrears['	issue_dr_coa_id'] = 333;
        $data_installment['amount'] = $check_installment[0]['amount'];
        $data_installment['type'] = 5;
        $data_installment['fee_type_id'] = 0;
        $this->db->insert(get_school_db() . ".student_chalan_detail", $data_installment);
        return $check_installment[0]['amount'];
        /////////////////////////////////////////////////////////////////
    }

    function check_promotion_req()
    {
        $section_id = $this->input->post('section_id');
        $month = $this->input->post('month');
        $year = $this->input->post('year');

        $school_id = $_SESSION['school_id'];
        // echo "select * from ".get_school_db().".bulk_monthly_chalan WHERE section_id=$section_id and fee_month=$month and fee_year=$year and school_id=$school_id";
        $query_v = $this->db->query("select section_id from " . get_school_db() . ".bulk_monthly_chalan 
                                    WHERE section_id=$section_id and fee_month=$month and fee_year=$year and school_id=$school_id")->result_array();

        if (count($query_v) > 0) {
            echo "no";
        } else {
            echo "yes";
        }
    }

    function view_print_chalan_class($section_id, $month, $year)
    {
        if ($section_id == "") {
            $this->session->set_flashdata('club_updated', get_phrase('chalan_form_is_not_created'));

            //	redirect(base_url() . 'c_student/student_pending');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $school_id = $_SESSION['school_id'];

            //and activity in (3,4,5);
            $qur = $this->db->query("select * from " . get_school_db() . ".bulk_monthly_chalan where section_id=$section_id and status=1 and activity in (4) and fee_month=$month and fee_year=$year")->result_array();

            //$c_c_f_id=$qur[0]['c_c_f_id'];
            $bulk_req_id = 0;
            if (count($qur) == 0) {
                $this->session->set_flashdata('club_updated', get_phrase('Chalan Form is not yet Issued'));
                redirect($_SERVER['HTTP_REFERER']);
                exit;
            } else {
                $bulk_req_id = $qur[0]['b_m_c_id'];
            }

            $page_data["query_ary"] = $this->db->query("select * from " . get_school_db() . ".student_chalan_form where status=4 and bulk_req_id= $bulk_req_id and school_id=$school_id  and is_bulk=2")->result_array();


            $page_data['section_id'] = $section_id;
            $page_data['month']      = $month;
            $page_data['year']       = $year;

            $page_data['page_name'] = 'view_print_chalan';
            $page_data['page_title'] = get_phrase('chalan_form');
            $this->load->view('backend/index', $page_data);

        }
    }

    function view_print_chalan_class_2($section_id, $month, $year)
    {
        if ($section_id == "") {
            $this->session->set_flashdata('club_updated', get_phrase('chalan_form_is_not_created'));

            //	redirect(base_url() . 'c_student/student_pending');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $school_id = $_SESSION['school_id'];

            //and activity in (3,4,5);
            $qur = $this->db->query("select * from " . get_school_db() . ".bulk_monthly_chalan where section_id=$section_id and status=1 and activity in (4) and fee_month=$month and fee_year=$year")->result_array();

            //$c_c_f_id=$qur[0]['c_c_f_id'];
            $bulk_req_id = 0;
            if (count($qur) == 0) {
                $this->session->set_flashdata('club_updated', get_phrase('chalan_form_is_not_yet_issued'));
                redirect($_SERVER['HTTP_REFERER']);
                exit;
            } else {
                $bulk_req_id = $qur[0]['b_m_c_id'];
            }

            $page_data["query_ary"] = $this->db->query("select * from " . get_school_db() . ".student_chalan_form where status=4 and bulk_req_id= $bulk_req_id and school_id=$school_id  and is_bulk=2")->result_array();
            $page_data['page_name'] = 'view_print_chalan_2';
            $page_data['page_title'] = get_phrase('chalan_form');
            $this->load->view('backend/index', $page_data);
        }
    }

    function view_detail_listing($b_m_c_id)
    {
        $school_id = $_SESSION['school_id'];
        $paid_unpaid_records = $this->input->post("paid_unpaid_records");
        $page_data['check_fee_status'] = $paid_unpaid_records; 
        $page_data['query'] = $this->db->query("SELECT * from " . get_school_db() . ".bulk_monthly_chalan bmc INNER JOIN " . get_school_db() . ".student_chalan_form scf ON scf.bulk_req_id=bmc.b_m_c_id WHERE scf.school_id=$school_id AND scf.bulk_req_id=$b_m_c_id AND scf.is_cancelled = 0 AND is_bulk=2 ")->result_array();
        $page_data['page_name'] = 'chalan_detail_listing';
        $page_data['page_title'] = get_phrase('chalan_detail_listing');
        $page_data['b_m_c_id'] = $b_m_c_id;
        $this->load->view('backend/index', $page_data);
    }

    function cancel_request($b_m_c_id)
    {
        $school_id = $_SESSION['school_id'];
        /* check is that any form isssued to student start */
        $is_issue_chalan_form_str = "SELECT bmc.* FROM " . get_school_db() . ".bulk_monthly_chalan bmc
                                     INNER JOIN " . get_school_db() . ".student_chalan_form as scf on scf.bulk_req_id = bmc.b_m_c_id
                                     WHERE bmc.b_m_c_id = $b_m_c_id AND scf.status>=5 AND bmc.school_id = $school_id";
        //  OR scf.status >= 4 Status Revert When Reversal Functionality Add

        $is_issue_chalan_form = $this->db->query($is_issue_chalan_form_str)->result_array();
            if(count($is_issue_chalan_form)>0)
            {
                $this->session->set_flashdata('club_updated', get_phrase('monthly_chalan_not_deleted_becouse_someone_student_paid.'));
                // $this->session->set_flashdata('club_updated', get_phrase('monthly_chalan_not_deleted_becouse_it_is_issue_to_someone_student!.'));
                redirect(base_url() . 'monthly_fee/monthly_bulk_listing');
                exit('Record_deleted_successfully');
            }

        /* check is that any form isssued to student end */

        $bulk_query_srt = "SELECT bmc.* FROM " . get_school_db() . ".bulk_monthly_chalan bmc WHERE bmc.b_m_c_id = $b_m_c_id AND bmc.school_id = $school_id";
        $bulk_query     = $this->db->query($bulk_query_srt)->result_array();
        if (count($bulk_query) > 0)
        {
            /* Insert value in journal table start */
            $month      = $bulk_query[0]['fee_month'];
            $year       = $bulk_query[0]['fee_year'];
            $section_id = $bulk_query[0]['section_id'];

            $query_student_str = "SELECT student_id FROM " . get_school_db() . ".student WHERE  section_id = $section_id AND school_id = $school_id AND student_status IN (" . student_query_status() . ")";
            $query_student     = $this->db->query($query_student_str)->result_array();
            
            // Get S C F ID From Student Chalan Form
            $query_s_c_f_id_str = "SELECT s_c_f_id,generation_date,status FROM " . get_school_db() . ".student_chalan_form WHERE  section_id = $section_id AND school_id = $school_id AND 
                                    s_c_f_month = $month AND s_c_f_year = $year";                   
            $query_s_c_f_id     = $this->db->query($query_s_c_f_id_str)->result_array();
            if (count($query_s_c_f_id) > 0) {

                foreach ($query_s_c_f_id as $query_s_c_f_id_key => $query_s_c_f_id_value) {
                    $scf_id = $query_s_c_f_id_value['s_c_f_id'];
                    
                    // Delete Student Challan Details
                    $delete_chalan_detail_str = "DELETE FROM " . get_school_db() . ".student_chalan_detail WHERE school_id = $school_id AND  s_c_f_id = $scf_id";
                    $this->db->query($delete_chalan_detail_str);
                    
                    // Delete Student Challan Forms
                    $delete_chalan_form_str = "DELETE FROM " . get_school_db() . ".student_chalan_form WHERE school_id = $school_id AND  s_c_f_id = $scf_id";
                    $this->db->query($delete_chalan_form_str);
                }
            }

            if (count($query_student) > 0) {

                // Delete Student Fee Settings
                foreach ($query_student as $query_student_key => $query_student_value) {
                    $student_id = $query_student_value['student_id'];
                    $delete_fee_settings_str = "DELETE FROM " . get_school_db() . ".student_fee_settings WHERE school_id = $school_id AND  month = $month
                                                AND year = $year AND student_id = $student_id AND is_bulk = 1";
                    $delete_fee_settings = $this->db->query($delete_fee_settings_str);
                }

            }
            /* Delete record from monthly fee settings start */
            
            $query_monthly_fee_str = "SELECT * FROM " . get_school_db() . ".student_monthly_fee_settings WHERE school_id = $school_id
                                      AND  fee_month = $month AND fee_year = $year AND b_m_c_id = $b_m_c_id";
            $query_monthly_fee = $this->db->query($query_monthly_fee_str)->result_array();

            if (count($query_monthly_fee) > 0) {
                $delete_monthly_fee_settings_str = "DELETE FROM " . get_school_db() . ".student_monthly_fee_settings WHERE school_id = $school_id
                                                    AND  fee_month = $month AND fee_year = $year AND b_m_c_id = $b_m_c_id";
                $delete_monthly_fee_settings = $this->db->query($delete_monthly_fee_settings_str);
            }

            $delete_bulk_monthly_chalan_str = "DELETE FROM " . get_school_db() . ".bulk_monthly_chalan WHERE school_id = $school_id
                                               AND  fee_month = $month AND fee_year = $year AND b_m_c_id = $b_m_c_id";
            $delete_bulk_monthly_chalan = $this->db->query($delete_bulk_monthly_chalan_str);
            
            
            // Chalan Archive Entry
            $archive_data = array(
                'school_id'             =>      $school_id,
                'section_id'            =>      $section_id,
                'month_year'            =>      $month.'-'.$year,
                'chalan_status'         =>      $query_s_c_f_id[0]['status'],
                'chalan_generated_date' =>      $query_s_c_f_id[0]['generation_date'],
                'chalan_deleted_by'     =>      $_SESSION['login_detail_id'],
                'chalan_deleted_type'   =>      1
            );
            $chalan_archive_entry = $this->db->insert(get_school_db() . ".chalan_archive",$archive_data);

            $this->session->set_flashdata('club_updated', get_phrase('monthly_chalan_deleted_successfull.'));
            redirect(base_url() . 'monthly_fee/monthly_bulk_listing');
            exit('Record_deleted_successfully');

        }
    }

    function cancel_single_request($s_c_f_id)
    {
        $school_id = $_SESSION['school_id'];
        $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=$school_id");
        $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_form where s_c_f_id=$s_c_f_id and school_id=$school_id and status<5 and is_bulk=2");
        redirect($_SERVER['HTTP_REFERER']);

    }

    function get_yearly_term()
    {
        echo yearly_terms_option_list($this->input->post('academic_year_id'));
    }

    function month_year_option()
    {
        $academic_year_id = $this->input->post('academic_year_id');
        $qur_rr = $this->db->query("select start_date,end_date from " . get_school_db() . ".yearly_terms where school_id=" . $_SESSION['school_id'] . " and yearly_terms_id=$academic_year_id")->result_array();
        $start_date = $qur_rr[0]['start_date'];
        $end_date = $qur_rr[0]['end_date'];
        echo month_year_option($start_date, $end_date);
    }

    function abc()
    {
        echo((0.1 + 0.7) * 10);
    }

    function month_year($start_date, $end_date)
    {
        $months = array();

        while (strtotime($start_date) <= strtotime($end_date)) {

            $months[] = array(
                'year' => date('Y', strtotime($start_date)),
                'month' => date('m', strtotime($start_date)),

            );

            $start_date = date('d M Y', strtotime($start_date .
                '+ 1 month'));
        }

// $arrlength = count($months);
//$counter=1;
        $str = array();

        foreach ($months as $key => $value) {

            //$month_year=date("F - Y", mktime(0, 0, 0,  $value['month'], 1, $value['year']));

            $str[$key]['month'] = ereg_replace('^0', '', $value['month']);
            $str[$key]['year'] = $value['year'];
        }

        return $str;

    }

    function re_generate_chalan_form($student_id, $s_c_f_id, $bulk_req_id, $c_c_f_id, $date_month, $student, $form_type)
    {

        $school_id = $_SESSION['school_id'];
        $date_month_temp = explode("-", $date_month);
        $year = $date_month_temp[0];
        $month = $date_month_temp[1];
        $current_date = date('Y-m-d H:i:s');
        $login_by = $_SESSION['login_detail_id'];
        $std_m_fee_settings_id = 0;
        $issue_dr_coa_id = 0;
        $issue_cr_coa_id = 0;
        $receive_dr_coa_id = 0;
        $receive_cr_coa_id = 0;
        $cancel_dr_coa_id = 0;
        $cancel_cr_coa_id = 0;
        $generate_dr_coa_id = 0;
        $generate_cr_coa_id = 0;
        //$settings_type = 1;
        /* identify , either this student is individual or combined start  */
        $settings_type = "AND sfs.settings_type = 2";
        $chalan_query_settings_type_str = "SELECT sfs.*  FROM " . get_school_db() . ".student_fee_settings sfs WHERE
                sfs.school_id = $school_id AND sfs.student_id = $student_id AND sfs.settings_type = 1 AND (sfs.month = $month AND sfs.year = $year)";
        $chalan_query_settings_type = $this->db->query($chalan_query_settings_type_str)->result_array();

        $related_s_c_d_id = array();
        if ((count($chalan_query_settings_type) > 0)) {
            $settings_type = "AND sfs.settings_type = 1";
        }

        /* identify , either this student is individual or combined end   */
        /* add fee in chalan detail forms start */
        $chalan_query_fee_str = "SELECT 
                             ft.issue_dr_coa_id,
                             ft.issue_cr_coa_id,
                             ft.receive_dr_coa_id,
                             ft.receive_cr_coa_id,
                             ft.cancel_dr_coa_id,
                             ft.cancel_cr_coa_id,
                             ft.generate_dr_coa_id,
                             ft.generate_cr_coa_id, 
                             sfs.* 
                             FROM " . get_school_db() . ".student_monthly_fee_settings smfs
                INNER JOIN " . get_school_db() . ".student_fee_settings as sfs on ((smfs.std_m_fee_settings_id = sfs.std_m_fee_settings_id))
                INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = sfs.fee_type_id
                WHERE
                smfs.school_id = $school_id
                AND smfs.student_id = $student_id
                AND sfs.fee_type = 1
                AND (smfs.fee_month = $month AND smfs.fee_year = $year)
                $settings_type
                AND smfs.b_m_c_id = $bulk_req_id";

        $chalan_query_fee = $this->db->query($chalan_query_fee_str)->result_array();

        $related_s_c_d_id = array();
        if ((count($chalan_query_fee) > 0)) {

            $std_m_fee_settings_id = $chalan_query_fee[0]['std_m_fee_settings_id'];
            foreach ($chalan_query_fee as $fee_key => $fee_val) {
                $issue_dr_coa_id = $fee_val['issue_dr_coa_id'];
                $issue_cr_coa_id = $fee_val['issue_cr_coa_id'];
                $receive_dr_coa_id = $fee_val['receive_dr_coa_id'];
                $receive_cr_coa_id = $fee_val['receive_cr_coa_id'];
                $cancel_dr_coa_id = $fee_val['cancel_dr_coa_id'];
                $cancel_cr_coa_id = $fee_val['cancel_cr_coa_id'];

                $fee_type_id_temp = $fee_val['fee_type_id'];
                $fee_title = $fee_val['title'];
                $amount = $fee_val['amount'];
                $fee_type = $fee_val['fee_type'];
                $fee_type_id = $fee_val['fee_type_id'];

                $query_insert_chalan_form_detail_str = "INSERT INTO " . get_school_db() . ".student_chalan_detail
                                       set
                                           s_c_f_id = " . $s_c_f_id . ",
                                           fee_type_title = '" . $fee_title . "',
                                           school_id = $school_id,
                                           amount = $amount,
                                           type = $fee_type,
                                           type_id = $fee_type_id,
                                           issue_dr_coa_id = $issue_dr_coa_id,
                                           issue_cr_coa_id = $issue_cr_coa_id,
                                           receive_dr_coa_id = $receive_dr_coa_id,
                                           receive_cr_coa_id = $receive_cr_coa_id,
                                           cancel_dr_coa_id = $cancel_dr_coa_id,
                                           cancel_cr_coa_id = $cancel_cr_coa_id,
                                           generate_dr_coa_id = $generate_dr_coa_id,
                                           generate_cr_coa_id = $generate_cr_coa_id "; 
                                           
                $query_insert_chalan_form_detail = $this->db->query($query_insert_chalan_form_detail_str);

                $s_c_d_id = $this->db->insert_id();
                $related_s_c_d_id[$fee_type_id] = $this->db->insert_id();

            }
        }

        /* add discount in chalan detail forms start */
        $chalan_query_discount_str = "SELECT
                             ds.fee_type_id as discount_list_fee_type_id, 
                             ds.issue_dr_coa_id,
                             ds.issue_cr_coa_id,
                             ds.receive_dr_coa_id,
                             ds.receive_cr_coa_id,
                             ds.cancel_dr_coa_id,
                             ds.cancel_cr_coa_id,
                             ds.generate_dr_coa_id,
                             ds.generate_cr_coa_id,
                             sfs.* 
                             FROM " . get_school_db() . ".student_monthly_fee_settings smfs
                INNER JOIN " . get_school_db() . ".student_fee_settings as sfs on ((smfs.std_m_fee_settings_id = sfs.std_m_fee_settings_id))
                inner join " . get_school_db() . ".discount_list as ds on ds.discount_id = sfs.fee_type_id
                WHERE smfs.school_id = $school_id AND smfs.student_id = $student_id AND sfs.fee_type = 2
                AND (smfs.fee_month = $month AND smfs.fee_year = $year) $settings_type AND smfs.b_m_c_id = $bulk_req_id";

        $chalan_query_discount = $this->db->query($chalan_query_discount_str)->result_array();
        /* add fee start */

        if ((count($chalan_query_discount) > 0)) {

            $related_s_c_d_id_s = 0;
            $std_m_fee_settings_id = $chalan_query_fee[0]['std_m_fee_settings_id'];

            foreach ($chalan_query_discount as $discount_key => $discount_val) {

                $issue_dr_coa_id = $discount_val['issue_dr_coa_id'];
                $issue_cr_coa_id = $discount_val['issue_cr_coa_id'];
                $receive_dr_coa_id = $discount_val['receive_dr_coa_id'];
                $receive_cr_coa_id = $discount_val['receive_cr_coa_id'];
                $cancel_dr_coa_id = $discount_val['cancel_dr_coa_id'];
                $cancel_cr_coa_id = $discount_val['cancel_cr_coa_id'];

                $fee_type_id_temp = $discount_val['fee_type_id'];
                $discount_title = $discount_val['title'];

                $amount = $discount_val['amount'];
                $fee_type = $discount_val['fee_type'];
                $fee_type_id = $discount_val['fee_type_id'];
                $actual_fee_id = $discount_val['discount_list_fee_type_id'];

                if (count($related_s_c_d_id) > 0) {
                    $related_s_c_d_id_s = $related_s_c_d_id[$actual_fee_id];
                }

                $query_insert_chalan_discount_str = "INSERT INTO " . get_school_db() . ".student_chalan_detail
                                       set
                                           s_c_f_id = " . $s_c_f_id . ",
                                           fee_type_title = '" . $discount_title . "',
                                           school_id = $school_id,
                                           amount = $amount,
                                           type = 2,
                                           type_id = $fee_type_id,
                                           related_s_c_d_id = $related_s_c_d_id_s,
                                           issue_dr_coa_id = $issue_dr_coa_id,
                                           issue_cr_coa_id = $issue_cr_coa_id,
                                           receive_dr_coa_id = $receive_dr_coa_id,
                                           receive_cr_coa_id = $receive_cr_coa_id,
                                           cancel_dr_coa_id = $cancel_dr_coa_id,
                                           cancel_cr_coa_id = $cancel_cr_coa_id,
                                           generate_dr_coa_id = $generate_dr_coa_id,
                                           generate_cr_coa_id = $generate_cr_coa_id  ";
                $query_insert_chalan_discount = $this->db->query($query_insert_chalan_discount_str);
            }

            /* add discount in chalan detail form end */
        }
        /* update student chalan form start */
        $query_update_s_c_f_str = "UPDATE " . get_school_db() . ".student_chalan_form set is_cancelled = 0 WHERE school_id = $school_id
                                   AND student_id = $student_id AND s_c_f_month = $month AND s_c_f_year = $year AND bulk_req_id = $bulk_req_id
                                   AND s_c_f_id = $s_c_f_id";
        $query_update_s_c_f = $this->db->query($query_update_s_c_f_str);

        if ($query_update_s_c_f) {
            $this->relink_custom_fee_bulk_id($month, $year, $student_id, $bulk_req_id, $std_m_fee_settings_id);
            $this->session->set_flashdata('club_updated', get_phrase('student_regenerated_successfully!'));
            redirect(base_url() . 'monthly_fee/view_detail_listing/' . $bulk_req_id);
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('student_regenerated_failed!'));
            redirect(base_url() . 'monthly_fee/view_detail_listing/' . $bulk_req_id);
        }
        /* update student chalan form start */

    }

    function is_discount_fee($s_c_d_id, $s_c_f_id)
    {
        $school_id = $_SESSION['school_id'];
        $str_discount = "SELECT scfd_discount.amount FROM " . get_school_db() . ".student_chalan_detail as scfd_discount WHERE 
                         scfd_discount.s_c_f_id =$s_c_f_id AND scfd_discount.related_s_c_d_id =$s_c_d_id AND scfd_discount.school_id = $school_id
                         AND scfd_discount.type = 2";
                      
        $query_discount = $this->db->query($str_discount)->result_array();
        if (count($query_discount) > 0) {
            $discount_amount = $query_discount[0]['amount'];
            return $discount_amount;
        } else {
            return 0;
        }
    }
}