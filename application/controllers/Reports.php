<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Reports extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        $this->load->helper('report');
        $this->coa_list = array();
        $this->load->helper('coa');
        $this->load->helper('voucher');
    }
    
    
    
    function reports_listing()
    {
        $page_data['page_name']='reports_listing';
        $page_data['page_title']=get_phrase('reports_listing');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********************/
    // Fee Concession Reports
    /**********************/
    function fee_concession_report()
    {
        $apply_filter = $this->input->post("apply_filter");
        if($apply_filter == 1)
        {
            $filter_arr   = $this->get_filters();
            $page_data['std_search']   = $filter_arr['std_search'];
            $page_data['section_id']   = $filter_arr['section_id'];
            $page_data['student_id']   = $filter_arr['student_id'];
            $page_data['start_date']   = date_dash($filter_arr['start_date']);
            $page_data['end_date']   = date_dash($filter_arr['end_date']);
            
            $std_fee_concession_qur = $this->get_students_fee_concession_query();
            $std_fee_concession_arr = $this->get_query_result_in_array($std_fee_concession_qur);
        }else{
            $std_fee_concession_arr = array();
        }

        $d_school_id = $this->uri->segment(3);
        $page_data['d_school_id'] = $d_school_id;
        $page_data['fee_concession_arr'] = $std_fee_concession_arr;
        $page_data['apply_filter']  = $apply_filter;
        $page_data['page_name']  = 'fee_concession_report';
        $page_data['page_title'] = get_phrase('fee_concession_report');
        $this->load->view('backend/index', $page_data);
    }
    
    function fee_concession_pdf()
    {
        $apply_filter = $this->input->post("apply_filter");
        if($apply_filter == 1)
        {
            $std_fee_concession_qur = $this->get_students_fee_concession_query();
            $std_fee_concession_arr = $this->get_query_result_in_array($std_fee_concession_qur);
        }else{
            $std_fee_concession_arr = array();
        }
        
        $page_data['fee_concession_arr'] = $std_fee_concession_arr;
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = $filter_arr['start_date'];
        $end_date = $filter_arr['end_date'];

        $d_school_id = $filter_arr['d_school_id'];
        $page_data['d_school_id']  = $d_school_id;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['std_search']   = $std_search;
        $page_data['section_id']   = $section_id;
        $page_data['student_id']   = $student_id;
        $page_data['start_date']   = $start_date;
        $page_data['end_date']     = $end_date;
        $page_data['page_name']    = 'fee_concession_print';
        $page_data['page_title']   = get_phrase('fee_concession_print');
        $this->load->library('Pdf');
        $view = 'backend/admin/fee_concession_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->set_paper("A4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*______________students arresrs pdf_____________________*/
    
    function get_students_fee_concession_query()
    {
        $filter_arr = $this->get_filters();
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];

        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }
        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }

        $std_query="";
        if ( isset($std_search) && $std_search != "")
        {
            $std_query =" AND (

            scf.student_id LIKE '%".$std_search."%' OR
            scf.student_name LIKE '%".$std_search."%' OR 
            scf.roll LIKE '%".$std_search."%' OR
            scf.mob_num LIKE '%".$std_search."%' OR 
            scf.reg_num LIKE '%".$std_search."%' OR 
            scf.mob_num LIKE '%".$std_search."%' OR
            scf.system_id LIKE '%".$std_search."%' OR
            scf.chalan_form_number LIKE '%".$std_search."%' OR
            scf.father_name LIKE '%".$std_search."%' OR
            scf.section LIKE '%".$std_search."%' OR 
            scf.class LIKE '%".$std_search."%' OR 
            scf.department LIKE '%".$std_search."%' OR 
            scf.chalan_form_number LIKE '%".$std_search."%' OR 
            scf.arrears LIKE '%".$std_search."%'

            )";
        }
                    //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }

        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND scf.section_id in(".$section_id.")";
        }
                //student filter
        $student_filter ="";
        if ($student_id > 0)
        {
            $student_filter = " AND scf.student_id=".$student_id;
        }
                    //_______Date filter____________
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.received_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }

        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        return $std_arrears_qur ="select scf.s_c_f_id,scf.student_id, scf.student_name, scf.roll, scf.section_id, scf.image, scf.mob_num, scf.reg_num, scf.location_id, scf.system_id, scf.bar_code, scf.due_date, scf.payment_date, scf.fee_month_year, scf.chalan_form_number, scf.form_type, scf.actual_amount, scf.father_name, scf.section, scf.class, scf.department ,  scf.received_amount , scf.arrears, scf.s_c_f_month , scf.s_c_f_year
            from ".get_school_db().".student_chalan_form scf where scf.is_processed=0 and scf.school_id=".$d_school_id." 
            ".$std_query." ".$section_filter."  ".$student_filter." ".$date_filter." order by scf.arrears DESC";
    }
    
    
    /*______________fee_concession excel_____________________*/
    function fee_concession_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Fee Concession Report');
        $this->load->database();
        $std_arrears_qur = $this->get_students_fee_concession_query();
        $std_arrears_arr = $this->get_query_result_in_array($std_arrears_qur);
        
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = date_view($filter_arr['start_date']);
        $end_date = date_view($filter_arr['end_date']);

        $d_school_id = $filter_arr['d_school_id'];

        $count =0;
        $total_amount = 0;
        $total_received = 0;
        $total_arrears = 0;
        $c = 0;
        $titles_arr = array();
        $arrears_arr = array();
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $arrears_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $arrears_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        $arrears_arr[] = 'Fee Conseesion Report';
        $arrears_arr[] = "";
        if (isset($std_search) && !empty($std_search))
        {
            $arrears_arr[] = $std_search;
            $c++;
            $titles_arr[$c] = "Keywords: ".$std_search;
        }
        if (isset($section_id) && !empty($section_id))
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            $dep_cls_sec ="";
            if ($prefix=='d') {
                $label = "Department";
                $dep_cls_sec = get_department_name($value,$d_school_id);
            }elseif ($prefix=='c') {
                $label = "Class";
                $dep_cls_arr = class_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_arr['d']." / ".$dep_cls_arr['c'];
            }elseif ($prefix=='s') {
                $label = "Section";
                $dep_cls_sec_arr = section_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_sec_arr['d']." / ".$dep_cls_sec_arr['c']." / ".$dep_cls_sec_arr['s'];
            }
            $arrears_arr[] = $dep_cls_sec;
            $c++;
            $titles_arr[$c] = $label." : ".$dep_cls_sec;
        }
        if (isset($student_id) && !empty($student_id))
        {
            
            $arrears_arr[] = get_student_name($student_id,$d_school_id);
            $c++;
            $titles_arr[$c] = "Student: ".get_student_name($student_id,$d_school_id);
        }
        if (isset($start_date) && !empty($start_date))
        {
            $arrears_arr[] = $start_date;
            $c++;
            $titles_arr[$c] = "Start Date: ". $start_date;
        }
        if (isset($end_date) && !empty($end_date))
        {
            $arrears_arr[] = $end_date;
            $c++;
            $titles_arr[$c] = "End Date: ". $end_date;
        }
        
        $arrears_arr[] = array('Sr.','Student Name','Reg No', 'Class', 'Section', 'Chalan Form #', 'Chalan Type' , 'Discount Details');
        // echo "<pre>";print_r(get_discount_listing());exit;
        foreach ($std_arrears_arr as $key => $value)
        {
            $discounts_list = '';   
            foreach(get_discount_listing() as $discount_title) {
                
                $get_c_c_f_id = $this->db->query("SELECT c_c_f_id FROM ".get_school_db().".student_chalan_form WHERE s_c_f_id = '".$value['s_c_f_id']."' and school_id = '".$_SESSION['school_id']."' and is_cancelled = 0 and status > 3 ")->row();
                $c_c_f_id     = $get_c_c_f_id->c_c_f_id;
                $output = 0;
                $query = $this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id = '".$value['s_c_f_id']."' and type != 2 and type_id = '".$discount_title['fee_type_id']."' and school_id=".$_SESSION['school_id']);
                $get_fee = $query->result_array();
                $discount_calculation = 0;
                $totle = $get_fee[0]['amount'];
                $check_alread_discount = $this->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '".$value['student_id']."' AND month = '".$value['s_c_f_month']."' AND year = '".$value['s_c_f_year']."' AND fee_type = 2 AND fee_type_id = '".$discount_title['discount_id']."'");

                if($check_alread_discount->num_rows() > 0)
                {
                    $single_disco = $check_alread_discount->result_array();
                    $single_discount_calculation = 0;
                    if($single_disco[0]['discount_amount_type'] == '1')
                    {
                        $single_discount_percent = $single_disco[0]['amount'];
                        $discounts_list .= $discount_title['title'].' - ';
                        $discounts_list .= number_format($single_discount_percent).' -- ';
                    }else if($single_disco[0]['discount_amount_type'] == '0' || $single_disco[0]['discount_amount_type'] == NULL){
                        $single_discount_percent = round(($totle / 100) * $single_disco[0]['amount']);   
                        $discounts_list .= $discount_title['title'].' - ';
                        $discounts_list .= number_format($single_discount_percent).' -- ';
                    }
                }
            }
            
            $count++;
            $record_arr   = array();
            $record_arr[] = $count;
            $record_arr[] = $value['student_name'];
            $record_arr[] = $value['reg_num'];
            $record_arr[] = $value['class'];
            $record_arr[] = $value['section'];
            $record_arr[] = $value['chalan_form_number'];
            $record_arr[] = display_class_chalan_type($value['form_type']);
            $record_arr[] = $discounts_list;
            
            // print_r($record_arr);exit;

            $arrears_arr[] = $record_arr;
        }

        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                
        //set width, height
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:L1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A2:L2');
        $this->excel->getActiveSheet()->setCellValue('A2', $arrears_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A3:L3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':I'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }

        // $count = $count+$c+5;
        // $this->excel->getActiveSheet()->setCellValue('I'.$count.'',"Total: ");
        // $this->excel->getActiveSheet()->setCellValue('J'.$count.'',$total_amount);
        // $this->excel->getActiveSheet()->setCellValue('K'.$count.'',$total_received);
        // $this->excel->getActiveSheet()->setCellValue('L'.$count.'',$total_arrears);
        //____________End total__________________
        $this->excel->getActiveSheet()->fromArray($arrears_arr);
        $filename='Fee Concession Report.xls';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    /**********************/
    // Fee Recovery Reports
    /**********************/
    function fee_recovery_report()
    {
        $page_data['page_name']='fee_recovery_report';
        $page_data['page_title']=get_phrase('fee_recovery_report');
        $this->load->view('backend/index', $page_data);
    }
    
    function unpaid_students()
    {
        $filter_arr   = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search   = $filter_arr['std_search'];
        $section_id   = $filter_arr['section_id'];
        $student_id   = $filter_arr['student_id'];
        $start_date   = date_dash($filter_arr['start_date']);
        if (empty($start_date)){
           $start_date =date('01/m/Y');
        }

        $end_date     = date_dash($filter_arr['end_date']);
        if (empty($end_date)) {
            $end_date = date("d/m/Y");
        }

        $page_data['std_search'] = $std_search;
        $page_data['section_id'] = $section_id;
        $page_data['student_id'] = $student_id;
        $page_data['start_date'] = $start_date;
        $page_data['end_date'] = $end_date;
        $unpaid_std_qur = $this->get_unpaid_student_query();
        $unpaid_std_arr = $this->get_query_result_in_array($unpaid_std_qur);

        $discount_amount_arr= $this->get_discount();

        $d_school_id = $this->uri->segment(3);
        $page_data['d_school_id'] = $d_school_id;
        $page_data['unpaid_std_arr'] = $unpaid_std_arr;
        $page_data['discount_amount_arr'] = $discount_amount_arr;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['page_name']  = 'fee_recovery_report';
        $page_data['page_title'] = get_phrase('unpaid_students');
        $this->load->view('backend/index', $page_data);
    }
    function get_unpaid_student_query()
    {
        $filter_arr = $this->get_filters();
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = $filter_arr['start_date'];
        $end_date = $filter_arr['end_date'];

        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }

        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }
        
        $std_query="";
        if ( isset($std_search) && $std_search != "")
        {
            $std_query =" AND (

            scf.student_id LIKE '%".$std_search."%' OR
            scf.student_name LIKE '%".$std_search."%' OR 
            scf.roll LIKE '%".$std_search."%' OR
            scf.mob_num LIKE '%".$std_search."%' OR 
            scf.reg_num LIKE '%".$std_search."%' OR 
            scf.mob_num LIKE '%".$std_search."%' OR
            scf.system_id LIKE '%".$std_search."%' OR
            scf.chalan_form_number LIKE '%".$std_search."%' OR
            scf.father_name LIKE '%".$std_search."%' OR
            scf.section LIKE '%".$std_search."%' OR 
            scf.class LIKE '%".$std_search."%' OR 
            scf.department LIKE '%".$std_search."%' OR 
            scf.chalan_form_number LIKE '%".$std_search."%'

            )";
        }
                    //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else{
                $section_id = "";
            }

        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND scf.section_id in(".$section_id.")";
        }
                //student filter
        $student_filter ="";
        if ($student_id > 0)
        {
            $student_filter = " AND scf.student_id=".$student_id;
        }
                    //_______Date filter____________
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.issue_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }
        //__________________Filters End_________________________
        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }
        return $unpaid_std_qur ="select scf.s_c_f_id, scf.student_id,scf.email, scf.student_name, scf.roll , scf.section_id , scf.image, scf.mob_num, scf.reg_num, scf.location_id, scf.system_id, scf.bar_code, scf.due_date, scf.issue_date, scf.fee_month_year,scf.chalan_form_number, scf.actual_amount, scf.father_name, scf.section, scf.class, scf.department 
            from ".get_school_db().".student_chalan_form scf where scf.is_processed=0 and scf.status=4 and
            scf.is_cancelled=0 and scf.school_id=".$d_school_id." ".$std_query." ".$section_filter." ".$student_filter." ".$date_filter." order by scf.actual_amount desc";
    }
    
    function fee_reminder()
    {
        $this->load->helper('message');
        
        $student_ids = $this->input->post('student_id');
        
        $reminder_status = $this->input->post('reminder_status');
        for($i=0; $i < count($student_ids);$i++)
        {
            $student_data = $_POST['student_id'][$i];
            $explode_array = explode("--",$student_data);
            if(isset($student_ids[$i])){
                $student_id = $explode_array[0];
                $student_name = $explode_array[1];
                $email = $explode_array[2];
                $mob_num = $explode_array[3];
                $net_amount = $explode_array[4];
            
                $message = "Dear Parent, your Child ".$student_name."'s fee amount Rs ".number_format($net_amount)." is due for more details please login to ".base_url()."login .";
                $email_layout = get_email_layout($message);
                if($reminder_status == "sms")
                {
                    if(!empty($mob_num)){
                        send_sms($mob_num, 'INDICI EDU',$message ,$student_id,8);
                        $this->session->set_flashdata('club_updated', get_phrase('message_sent_successfully'));
                    }
                }
                
                if($reminder_status == "email")
                {
                    if(!empty($email)){
                        email_send("No Reply", "Indici Edu", $email, "Due Fee Reminder", $email_layout,$student_id, 8);
                        $this->session->set_flashdata('club_updated', get_phrase('email_sent_successfully'));
                    }
                }
                
                if($reminder_status == "notification")
                {
                    $device_id  =   get_user_device_id(6 , $student_id , $_SESSION['school_id']);
                    $title      =   "Due Fee Reminder";
                    $link       =    base_url()."parents/invoice";
                    sendNotificationByUserId($device_id, $title, $message, $link , $student_id , 6);
                    $this->session->set_flashdata('club_updated', get_phrase('notification_sent_successfully'));
                }
            }
        }
        
        redirect(base_url() . 'reports/unpaid_students');
    }
    
    /*______________unpaid students pdf_____________________*/
    function print_pdf()
    {
        $unpaid_std_qur                   = $this->get_unpaid_student_query();
        $unpaid_std_arr                   = $this->get_query_result_in_array($unpaid_std_qur);
        $discount_amount_arr              = $this->get_discount();
        $page_data['discount_amount_arr'] = $discount_amount_arr;
        $page_data['unpaid_std_arr']      = $unpaid_std_arr;

        $filter_arr   = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search   = $filter_arr['std_search'];
        $section_id   = $filter_arr['section_id'];
        $student_id   = $filter_arr['student_id'];
        $start_date   = $filter_arr['start_date'];
        $end_date     = $filter_arr['end_date'];

        $d_school_id               = $filter_arr['d_school_id'];
        $page_data['d_school_id']  = $d_school_id;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['std_search']   = $std_search;
        $page_data['section_id']   = $section_id;
        $page_data['student_id']   = $student_id;
        $page_data['start_date']   = $start_date;
        $page_data['end_date']     = $end_date;
        $page_data['page_name']    = 'unpaid_students_print';
        $page_data['page_title']   = get_phrase('unpaid_students_print');

        $this->load->library('Pdf');
        $view = 'backend/admin/unpaid_students_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");

    }
    /*_____________unpaid Students excel___________________*/
    function database_to_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Unpaid Student Report');
        $this->load->database();
        
        $unpaid_std_qur        =  $this->get_unpaid_student_query();
        $unpaid_std_arr        =  $this->db->query($unpaid_std_qur)->result_array();
        $discount_amount_arr   =  $this->get_discount();

        $filter_arr   = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search   = $filter_arr['std_search'];
        $section_id   = $filter_arr['section_id'];
        $student_id   = $filter_arr['student_id'];
        $start_date   = date_view($filter_arr['start_date']);
        $end_date     = date_view($filter_arr['end_date']);
        $d_school_id  = $filter_arr['d_school_id'];
    
        $count      = 0;
        $total      = 0;
        $discount   = 0;
        $c          = 0;
        $titles_arr = array();
        $unpaid_arr = array();

        $d_school_id = $filter_arr['d_school_id'];
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $unpaid_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $unpaid_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        $unpaid_arr[] = 'Unpaid Students';
        $unpaid_arr[] = "";

        if (isset($std_search) && !empty($std_search))
        {
            $unpaid_arr[] = $std_search;
            $c++;
            $titles_arr[$c] = "Keywords: ".$std_search;
        }
        if (isset($section_id) && !empty($section_id))
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            $dep_cls_sec ="";
            if ($prefix=='d') {
                $label = "Department";
                $dep_cls_sec = get_department_name($value,$d_school_id);
            }elseif ($prefix=='c') {
                $label = "Class";
                $dep_cls_arr = class_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_arr['d']." / ".$dep_cls_arr['c'];
            }elseif ($prefix=='s') {
                $label = "Section";
                $dep_cls_sec_arr = section_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_sec_arr['d']." / ".$dep_cls_sec_arr['c']." / ".$dep_cls_sec_arr['s'];
            }
            $unpaid_arr[] = $dep_cls_sec;
            $c++;
            $titles_arr[$c] = $label." : ".$dep_cls_sec;
        }
        if (isset($student_id) && !empty($student_id))
        {
            
            $unpaid_arr[] = get_student_name($student_id,$d_school_id);
            $c++;
            $titles_arr[$c] = "Student: ".get_student_name($student_id,$d_school_id);
        }
        if (isset($start_date) && !empty($start_date))
        {
            $unpaid_arr[] = $start_date;
            $c++;
            $titles_arr[$c] = "Start Date: ". $start_date;
        }
        if (isset($end_date) && !empty($end_date))
        {
            $unpaid_arr[] = $end_date;
            $c++;
            $titles_arr[$c] = "End Date: ". $end_date;
        }
        $get_month = date("m",strtotime($value['due_date']));
        $get_year = date("Y",strtotime($value['due_date']));
        $total_discuont = get_student_challan_discount_calculation($value['student_id'] , $get_month, $get_year,$value['s_c_f_id']);
        $unpaid_arr[] = array('Sr.','Student Name','Reg No', 'Class', 'Section', 'Chalan Form #', 'Issue Date', 'Due Date', 'Amount','Discount','Arrears');
        foreach ($unpaid_std_arr as $key => $value)
        {
            $count++;
            $total = $total+$value['actual_amount'];
            $discount = $discount+$discount_amount_arr[$value['s_c_f_id']];
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['student_name'];
            $record_arr[] = $value['reg_num'];
            $record_arr[] = $value['class'];
            $record_arr[] = $value['section'];
            $record_arr[] = $value['chalan_form_number'];
            $record_arr[] = date_slash($value['issue_date']);
            $record_arr[] = date_slash($value['due_date']);
            $record_arr[] = $value['actual_amount'];
            $record_arr[] = $total_discuont;
            $record_arr[] = $value['arrears'];

            $unpaid_arr[] = $record_arr;
        }
        //______________set logo_______________
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //$path = system_path($_SESSION['school_logo']);
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
       // $objDrawing->setCoordinates('A1');
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   
        //setOffsetX works properly
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                
        //set width, height
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:J1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A3:J3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':J'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }

        $count = $count+$c+5;
        $this->excel->getActiveSheet()->setCellValue('H'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('I'.$count.'',$total);
        $this->excel->getActiveSheet()->setCellValue('J'.$count.'',$discount);

        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='Unpaid Students.xls';
        //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    function paid_students()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        
        $start_date = date_dash($filter_arr['start_date']);
        if (empty($start_date)){
           $start_date =date('01/m/Y');
        }
        $end_date = date_dash($filter_arr['end_date']);
        if (empty($end_date)) {
            $end_date = date("d/m/Y");
        }
        $page_data['std_search'] = $std_search;
        $page_data['section_id'] = $section_id;
        $page_data['student_id'] = $student_id;
        $page_data['start_date'] = $start_date;
        $page_data['end_date'] = $end_date;

        $paid_std_qur = $this->get_paid_student_query();
        $paid_std_arr = $this->get_query_result_in_array($paid_std_qur);

        $discount_amount_arr= $this->get_discount();

        $d_school_id = $this->uri->segment(3);

        $page_data['d_school_id'] = $d_school_id;
        $page_data['discount_amount_arr'] = $discount_amount_arr;
        $page_data['paid_std_arr'] = $paid_std_arr;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['page_name']  = 'fee_recovery_report';
        $page_data['page_title'] = get_phrase('paid_students');
        
        $this->load->view('backend/index', $page_data);
    }
    
    function get_paid_student_query()
    {
        $filter_arr = $this->get_filters();
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        
        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }
        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }

        $std_query="";
        if ( isset($std_search) && $std_search != "")
        {
            $std_query =" AND (

            scf.student_id LIKE '%".$std_search."%' OR
            scf.student_name LIKE '%".$std_search."%' OR 
            scf.roll LIKE '%".$std_search."%' OR
            scf.mob_num LIKE '%".$std_search."%' OR 
            scf.reg_num LIKE '%".$std_search."%' OR 
            scf.mob_num LIKE '%".$std_search."%' OR
            scf.system_id LIKE '%".$std_search."%' OR
            scf.chalan_form_number LIKE '%".$std_search."%' OR
            scf.father_name LIKE '%".$std_search."%' OR
            scf.section LIKE '%".$std_search."%' OR 
            scf.class LIKE '%".$std_search."%' OR 
            scf.department LIKE '%".$std_search."%' OR 
            scf.chalan_form_number LIKE '%".$std_search."%'

            )";
        }
                    //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }
                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }
        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND scf.section_id in(".$section_id.")";
        }
                //student filter
        $student_filter ="";
        if ($student_id > 0)
        {
            $student_filter = " AND scf.student_id=".$student_id;
        }

        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.received_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }

        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        return $paid_std_qur ="select scf.s_c_f_id , scf.student_id, scf.student_name,scf.roll , scf.section_id , scf.image, scf.mob_num, scf.reg_num,scf.location_id, scf.system_id,
            scf.bar_code,scf.due_date,scf.received_date,
            scf.fee_month_year,scf.chalan_form_number,
            scf.received_amount, scf.father_name, scf.section, scf.class,scf.arrears,scf.actual_amount,
            scf.department from ".get_school_db().".student_chalan_form scf
            where scf.is_processed=0 and scf.status=5 and scf.is_cancelled=0
            and scf.school_id=".$d_school_id." ".$std_query."
            ".$section_filter." ".$student_filter." ".$date_filter."
            order by scf.received_amount desc";
    }
    /*______________paid students pdf_____________________*/
    function paid_students_pdf()
    {
        $paid_std_qur = $this->get_paid_student_query();
        $paid_std_arr = $this->get_query_result_in_array($paid_std_qur);
        $discount_amount_arr= $this->get_discount();

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = $filter_arr['start_date'];
        $end_date = $filter_arr['end_date'];

        $d_school_id = $filter_arr['d_school_id'];
        $page_data['d_school_id'] = $d_school_id;

        $page_data['apply_filter'] = $apply_filter;
        $page_data['std_search'] = $std_search;
        $page_data['section_id'] = $section_id;
        $page_data['student_id'] = $student_id;
        $page_data['start_date'] = $start_date;
        $page_data['end_date'] = $end_date;

        $page_data['paid_std_arr'] = $paid_std_arr;
        $page_data['discount_amount_arr'] = $discount_amount_arr;
        $page_data['page_name']  = 'paid_students_print';
        $page_data['page_title'] = get_phrase('paid_students_print');
        $this->load->library('Pdf');
        $view = 'backend/admin/paid_students_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*______________paid students excel_____________________*/
    function paid_students_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Paid Student Report');
        $this->load->database();
        $paid_std_qur = $this->get_paid_student_query();
        $paid_std_arr=$this->db->query($paid_std_qur)->result_array();
        $discount_amount_arr= $this->get_discount();

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = date_view($filter_arr['start_date']);
        $end_date = date_view($filter_arr['end_date']);

        $d_school_id = $filter_arr['d_school_id'];
    
        $count =0;
        $total = 0;
        $discount = 0;
        $c = 0;
        $titles_arr = array();
        $paid_arr = array();
        
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $paid_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];

            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $paid_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        $paid_arr[] = 'Paid Students';
        $paid_arr[] = "";
        if (isset($std_search) && !empty($std_search))
        {
            $paid_arr[] = $std_search;
            $c++;
            $titles_arr[$c] = "Keywords: ".$std_search;
        }
        if (isset($section_id) && !empty($section_id))
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            $dep_cls_sec ="";
            if ($prefix=='d') {
                $label = "Department";
                $dep_cls_sec = get_department_name($value,$d_school_id);
            }elseif ($prefix=='c') {
                $label = "Class";
                $dep_cls_arr = class_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_arr['d']." / ".$dep_cls_arr['c'];
            }elseif ($prefix=='s') {
                $label = "Section";
                $dep_cls_sec_arr = section_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_sec_arr['d']." / ".$dep_cls_sec_arr['c']." / ".$dep_cls_sec_arr['s'];
            }
            $paid_arr[] = $dep_cls_sec;
            $c++;
            $titles_arr[$c] = $label." : ".$dep_cls_sec;
        }
        if (isset($student_id) && !empty($student_id))
        {
            
            $paid_arr[] = get_student_name($student_id,$d_school_id);
            $c++;
            $titles_arr[$c] = "Student: ".get_student_name($student_id,$d_school_id);
        }
        if (isset($start_date) && !empty($start_date))
        {
            $paid_arr[] = $start_date;
            $c++;
            $titles_arr[$c] = "Start Date: ". $start_date;
        }
        if (isset($end_date) && !empty($end_date))
        {
            $paid_arr[] = $end_date;
            $c++;
            $titles_arr[$c] = "End Date: ". $end_date;
        }
        
        $paid_arr[] = array('Sr','Student Name','Reg No', 'Class', 'Section', 'Chalan Form #', 'Due Date','Received Date', 'Received Amount','Discount','Arrears');

        $get_month = date("m",strtotime($value['due_date']));
        $get_year = date("Y",strtotime($value['due_date']));
        $total_discuont = get_student_challan_discount_calculation($value['student_id'] , $get_month, $get_year,$value['s_c_f_id']);
        foreach ($paid_std_arr as $key => $value)
        {
            $count++;
            $total = $total+$value['received_amount'];
            $discount = $discount+$discount_amount_arr[$value['s_c_f_id']];
            $record_arr = array();
            $record_arr[] = $count;
            $record_arr[] = $value['student_name'];
            $record_arr[] = $value['reg_num'];
            $record_arr[] = $value['class'];
            $record_arr[] = $value['section'];
            $record_arr[] = $value['chalan_form_number'];
            $record_arr[] = date_slash($value['due_date']);
            $record_arr[] = date_slash($value['received_date']);
            $record_arr[] = $value['received_amount'];
            $record_arr[] = $total_discuont;
            $record_arr[] = $value['arrears'];
            $paid_arr[] = $record_arr;
        }

        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:J1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        $this->excel->getActiveSheet()->setCellValue('A2', $paid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A3:J3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':J'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }

        $count = $count+$c+5;
        $this->excel->getActiveSheet()->setCellValue('H'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('I'.$count.'',$total);
        $this->excel->getActiveSheet()->setCellValue('J'.$count.'',$discount);
        //____________End total__________________
        $this->excel->getActiveSheet()->fromArray($paid_arr);
        $filename='paid Students.xls';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }

    function students_arrears()
    {
        $apply_filter = $this->input->post("apply_filter");
        if($apply_filter == 1)
        {
            $std_arrears_qur = $this->get_students_arrears_query();
            $std_arrears_arr = $this->get_query_result_in_array($std_arrears_qur);
            $filter_arr   = $this->get_filters();
            $page_data['std_search']   = $filter_arr['std_search'];
            $page_data['section_id']   = $filter_arr['section_id'];
            $page_data['student_id']   = $filter_arr['student_id'];
            $page_data['start_date']   = date_dash($filter_arr['start_date']);
            $page_data['end_date']   = date_dash($filter_arr['end_date']);
        }else{
            $std_arrears_arr = array();
        }

        $d_school_id = $this->uri->segment(3);
        $page_data['d_school_id'] = $d_school_id;
        $page_data['std_arrears_arr'] = $std_arrears_arr;
        $page_data['apply_filter']  = $apply_filter;
        $page_data['page_name']  = 'students_arrears';
        $page_data['page_title'] = get_phrase('students_arrears');
        $this->load->view('backend/index', $page_data);
    }
    function get_students_arrears_query()
    {
        $filter_arr = $this->get_filters();
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = $filter_arr['start_date'];
        $end_date = $filter_arr['end_date'];

        $std_query="";
        if ( isset($std_search) && $std_search != "")
        {
            $std_query =" AND (

            scf.student_id LIKE '%".$std_search."%' OR
            scf.student_name LIKE '%".$std_search."%' OR 
            scf.roll LIKE '%".$std_search."%' OR
            scf.mob_num LIKE '%".$std_search."%' OR 
            scf.reg_num LIKE '%".$std_search."%' OR 
            scf.mob_num LIKE '%".$std_search."%' OR
            scf.system_id LIKE '%".$std_search."%' OR
            scf.chalan_form_number LIKE '%".$std_search."%' OR
            scf.father_name LIKE '%".$std_search."%' OR
            scf.section LIKE '%".$std_search."%' OR 
            scf.class LIKE '%".$std_search."%' OR 
            scf.department LIKE '%".$std_search."%' OR 
            scf.chalan_form_number LIKE '%".$std_search."%' OR 
            scf.arrears LIKE '%".$std_search."%'

            )";
        }
                    //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }

        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND scf.section_id in(".$section_id.")";
        }
                //student filter
        $student_filter ="";
        if ($student_id > 0)
        {
            $student_filter = " AND scf.student_id=".$student_id;
        }
                    //_______Date filter____________
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.received_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }

        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }
        
        return $std_arrears_qur ="select scf.student_id, scf.student_name, scf.roll, scf.section_id, scf.image, scf.mob_num, scf.reg_num, scf.location_id, scf.system_id, scf.bar_code, scf.due_date, scf.payment_date, scf.fee_month_year, scf.chalan_form_number, scf.form_type, scf.actual_amount, scf.father_name, scf.section, scf.class, scf.department ,  scf.received_amount , scf.arrears 
            from ".get_school_db().".student_chalan_form scf where scf.is_processed=0 and scf.status=5 and scf.school_id=".$d_school_id." and scf.arrears>0 and scf.arrears_status = 1 
            ".$std_query." ".$section_filter."  ".$student_filter." ".$date_filter." order by scf.arrears DESC";
    }
    /*______________students arresrs pdf_____________________*/
    function arrears_pdf()
    {
        $std_arrears_qur = $this->get_students_arrears_query();
        $std_arrears_arr = $this->get_query_result_in_array($std_arrears_qur);
        $page_data['std_arrears_arr'] = $std_arrears_arr;
        
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = $filter_arr['start_date'];
        $end_date = $filter_arr['end_date'];

        $d_school_id = $filter_arr['d_school_id'];
        $page_data['d_school_id']  = $d_school_id;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['std_search']   = $std_search;
        $page_data['section_id']   = $section_id;
        $page_data['student_id']   = $student_id;
        $page_data['start_date']   = $start_date;
        $page_data['end_date']     = $end_date;
        $page_data['page_name']    = 'students_arrears_print';
        $page_data['page_title']   = get_phrase('students_arrears_print');
        $this->load->library('Pdf');
        $view = 'backend/admin/students_arrears_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->set_paper("A4", "landscape");
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*______________students arresrs excel_____________________*/
    function arrears_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Students Arrears Report');
        $this->load->database();
        $std_arrears_qur = $this->get_students_arrears_query();
        $std_arrears_arr = $this->get_query_result_in_array($std_arrears_qur);
        
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = date_view($filter_arr['start_date']);
        $end_date = date_view($filter_arr['end_date']);

        $d_school_id = $filter_arr['d_school_id'];

        $count =0;
        $total_amount = 0;
        $total_received = 0;
        $total_arrears = 0;
        $c = 0;
        $titles_arr = array();
        $arrears_arr = array();
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $arrears_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $arrears_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        $arrears_arr[] = 'Student Arrears Report';
        $arrears_arr[] = "";
        if (isset($std_search) && !empty($std_search))
        {
            $arrears_arr[] = $std_search;
            $c++;
            $titles_arr[$c] = "Keywords: ".$std_search;
        }
        if (isset($section_id) && !empty($section_id))
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            $dep_cls_sec ="";
            if ($prefix=='d') {
                $label = "Department";
                $dep_cls_sec = get_department_name($value,$d_school_id);
            }elseif ($prefix=='c') {
                $label = "Class";
                $dep_cls_arr = class_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_arr['d']." / ".$dep_cls_arr['c'];
            }elseif ($prefix=='s') {
                $label = "Section";
                $dep_cls_sec_arr = section_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_sec_arr['d']." / ".$dep_cls_sec_arr['c']." / ".$dep_cls_sec_arr['s'];
            }
            $arrears_arr[] = $dep_cls_sec;
            $c++;
            $titles_arr[$c] = $label." : ".$dep_cls_sec;
        }
        if (isset($student_id) && !empty($student_id))
        {
            
            $arrears_arr[] = get_student_name($student_id,$d_school_id);
            $c++;
            $titles_arr[$c] = "Student: ".get_student_name($student_id,$d_school_id);
        }
        if (isset($start_date) && !empty($start_date))
        {
            $arrears_arr[] = $start_date;
            $c++;
            $titles_arr[$c] = "Start Date: ". $start_date;
        }
        if (isset($end_date) && !empty($end_date))
        {
            $arrears_arr[] = $end_date;
            $c++;
            $titles_arr[$c] = "End Date: ". $end_date;
        }

        $arrears_arr[] = array('Sr.','Student Name','Reg No', 'Class', 'Section', 'Chalan Form #', 'chalan Type' , 'Due Date', 'Payment Date','Total Amount', 'Received Amount','Arrears');
        foreach ($std_arrears_arr as $key => $value)
        {
            $count++;
            $total_amount = $total_amount+$value['actual_amount'];
            $total_received = $total_received + $value['received_amount'];
            $total_arrears = $total_arrears+$value['arrears'];
            $record_arr   = array();
            $record_arr[] = $count;
            $record_arr[] = $value['student_name'];
            $record_arr[] = $value['reg_num'];
            $record_arr[] = $value['class'];
            $record_arr[] = $value['section'];
            $record_arr[] = $value['chalan_form_number'];
            $record_arr[] = display_class_chalan_type($value['form_type']);
            $record_arr[] = date_slash($value['due_date']);
            $record_arr[] = date_slash($value['received_date']);
            $record_arr[] = $value['actual_amount'];
            $record_arr[] = $value['received_amount'];
            $record_arr[] = $value['arrears'];

            $arrears_arr[] = $record_arr;
        }

        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                
        //set width, height
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:L1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A2:L2');
        $this->excel->getActiveSheet()->setCellValue('A2', $arrears_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A3:L3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':I'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }

        $count = $count+$c+5;
        $this->excel->getActiveSheet()->setCellValue('I'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('J'.$count.'',$total_amount);
        $this->excel->getActiveSheet()->setCellValue('K'.$count.'',$total_received);
        $this->excel->getActiveSheet()->setCellValue('L'.$count.'',$total_arrears);
        //____________End total__________________
        $this->excel->getActiveSheet()->fromArray($arrears_arr);
        $filename='students arrears.xls';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }

    function section_wise_paid_fee()
    {
        $filter_arr = $this->get_filters();
        
        $apply_filter = $filter_arr['apply_filter'];
        $start_date = $filter_arr['start_date'];
        $end_date = $filter_arr['end_date'];
        $section_id = $filter_arr['section_id'];
        $d_school_id = $this->uri->segment(3);
        
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.issue_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }

        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }elseif ($prefix =='c'){
                $class_id = $value;
                $sec=$this->db->query("select section_id from " .get_school_db().".class_section where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }else{
                    $section_id = "";
                }
            }elseif ($prefix =='d'){
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }else{
                    $section_id = "";
                }
            }else{
                $section_id = "";
            }
        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND s.section_id in(".$section_id.")";
        }
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        $section_wise_paid_fee_qur = "select sum(scf.received_amount) as received_amount, s.section_id from ".get_school_db().".student s 
        inner join ".get_school_db().".student_chalan_form scf on scf.student_id=s.student_id 
        where scf.is_processed=0 and scf.status=5 and scf.is_cancelled=0 and s.school_id=".$d_school_id." ".$date_filter." ".$section_filter." group by s.section_id";
        
        $section_wise_paid_fee_arr = $this->get_query_result_in_array($section_wise_paid_fee_qur);

        $payment_details_arr  = array();
        foreach ($section_wise_paid_fee_arr as $key => $value)
        {
           $payment_details_arr[$value['section_id']]['actual_amount']=$value['actual_amount'];
           $payment_details_arr[$value['section_id']]['received_amount']=$value['received_amount'];
           $payment_details_arr[$value['section_id']]['arrears']=$value['arrears'];
        }

        $section_wise_total_fee_qur = "select
        sum(scf.actual_amount) as actual_amount, s.section_id
        from ".get_school_db().".student s 
        inner join ".get_school_db().".student_chalan_form scf on scf.student_id=s.student_id 
        where scf.is_processed=0 and scf.status in(4,5) and scf.is_cancelled=0 and s.school_id=".$d_school_id." ".$date_filter." ".$section_filter."
        group by s.section_id";
        $section_wise_total_fee_arr = $this->get_query_result_in_array($section_wise_total_fee_qur);

        $total_details_arr  = array();
        foreach ($section_wise_total_fee_arr as $key => $value)
        {
           $total_details_arr[$value['section_id']]['actual_amount']=$value['actual_amount'];
        }

        if($apply_filter == ""){
            $section_discount_arr       =   array();
            $total_details_arr          =   array();
            $payment_details_arr        =   array();
        }

        $page_data['total_details_arr'] = $total_details_arr;
        $page_data['payment_details_arr'] = $payment_details_arr;
        $page_data['d_school_id'] = $d_school_id;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['start_date'] = date_dash($start_date);
        $page_data['end_date'] = date_dash($end_date);
        $page_data['section_id'] = $section_id;
        $page_data['page_name']  = 'section_wise_paid_fee';
        $page_data['page_title'] = get_phrase('section_wise_paid_fee');
        $this->load->view('backend/index', $page_data);
    }
    /*___________section wise Total fee query_______________*/
    function get_section_wise_total_fee_query()
    {
        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];
        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }
        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }

        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.issue_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }
                //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section
                 where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }

        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND s.section_id in(".$section_id.")";
        }
        //__________________Filters End_________________________
        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        return $section_wise_total_fee_qur = "select
        sum(scf.actual_amount) as actual_amount, 
        s.section_id , s.barcode_image
        from ".get_school_db().".student s 
        inner join ".get_school_db().".student_chalan_form scf on scf.student_id=s.student_id 
        where scf.is_processed=0 and scf.status in(4,5) and scf.is_cancelled=0 and s.school_id=".$d_school_id." ".$date_filter." ".$section_filter."
        group by s.section_id";
    }
    /*___________section wise Paid fee query_______________*/
    function get_section_wise_paid_fee_query()
    {
        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];

        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
          $start_date =date('Y-m-01');
        }
        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }

        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.issue_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }
                //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }

        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND s.section_id in(".$section_id.")";
        }

        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        return $section_wise_paid_fee_qur = "select sum(scf.received_amount) as received_amount, s.section_id from ".get_school_db().".student s 
        inner join ".get_school_db().".student_chalan_form scf on scf.student_id=s.student_id 
        where scf.is_processed=0 and scf.status=5 and scf.is_cancelled=0 and s.school_id=".$d_school_id." ".$date_filter." ".$section_filter." group by s.section_id";
    }
    /*___________section wise Unpadi fee query_______________*/
    function get_section_wise_unpaid_fee_query()
    {
        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];

        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }
        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }

        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.issue_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }
                //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }

        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND s.section_id in(".$section_id.")";
        }

        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        return $section_wise_unpaid_fee_qur = "select sum(scf.actual_amount) as actual_amount,  s.section_id
        from ".get_school_db().".student s  inner join ".get_school_db().".student_chalan_form scf on scf.student_id=s.student_id 
        where scf.is_processed=0 and scf.status=4 and scf.is_cancelled=0 and s.school_id=".$d_school_id." ".$date_filter." ".$section_filter." group by s.section_id";
    }
    /*___________section wise Arrears fee query_______________*/
    function get_section_wise_arrears_fee_query()
    {
        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];

        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }
        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }

        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.issue_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }
                //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section
                 where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }

        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND s.section_id in(".$section_id.")";
        }

        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        return $section_wise_arrears_fee_qur = "select
        sum(scf.arrears) as arrears,
        s.section_id
        from ".get_school_db().".student s 
        inner join ".get_school_db().".student_chalan_form scf on scf.student_id=s.student_id 
        where scf.is_processed=0 and scf.status=5 and s.school_id=".$d_school_id." ".$date_filter." ".$section_filter."
        group by s.section_id";
    }
    /*___________section wise discount_______________________*/
    function get_section_wise_discount()
    {
        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];

        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }
        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }

        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.issue_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }
                //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section
                 where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }
                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }
        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND scf.section_id in(".$section_id.")";
        }

        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        $discount_qur = "select scf.section_id, scd.s_c_f_id, scd.s_c_d_id, scd.type, scd.amount, scd.type_id,scd.related_s_c_d_id
            from ".get_school_db().".student_chalan_form scf 
            INNER join ".get_school_db().".student_chalan_detail scd 
            on scf.s_c_f_id = scd.s_c_f_id
            where scf.is_processed=0 and scf.is_cancelled=0
            and scf.school_id=".$d_school_id." and scd.type in(1,2) ".$section_filter." ".$date_filter."
            order by scf.s_c_f_id";
        $discount_arr = $this->get_query_result_in_array($discount_qur);

        $discount_details_arr = array();
        foreach ($discount_arr as $key => $value)
        {
            $discount_details_arr[$value['s_c_f_id']][$value['type']][$value['s_c_d_id']]['amount']= $value['amount'];
            $discount_details_arr[$value['s_c_f_id']][$value['type']][$value['s_c_d_id']]['type_id']= $value['type_id'];
            $discount_details_arr[$value['s_c_f_id']][$value['type']][$value['s_c_d_id']]['related_s_c_d_id']= $value['related_s_c_d_id'];
            $discount_details_arr[$value['s_c_f_id']][$value['type']][$value['s_c_d_id']]['section_id']= $value['section_id'];
        }
        $section_discount_arr = array();
        foreach ($discount_details_arr as $key => $value)
        {
            foreach ($value[2] as $key1 => $value1)
            {
                $discount_percent = $value1['amount'];
                $related_id = $value1['related_s_c_d_id'];
                $discount = 0;
                if ($related_id>0)
                {
                    $fee_amount = $value[1][$related_id]['amount'];
                    $discount = ($fee_amount)*($discount_percent/100);
                    $section_discount_arr[$value1['section_id']][] = $discount;
                }
            }
        }
        return $section_discount_arr;
    }
    /*______________section wise paid pdf____________________*/
    function section_wise_paid_pdf()
    {   
        //---------payment details__________________________
        $section_wise_paid_fee_qur = $this->get_section_wise_paid_fee_query();
        $section_wise_paid_fee_arr = $this->get_query_result_in_array($section_wise_paid_fee_qur);
        
        $payment_details_arr  = array();
        foreach ($section_wise_paid_fee_arr as $key => $value)
        {
           $payment_details_arr[$value['section_id']]['received_amount']=$value['received_amount'];
        }

        $section_wise_total_fee_qur =  $this->get_section_wise_total_fee_query();
        $section_wise_total_fee_arr = $this->get_query_result_in_array($section_wise_total_fee_qur);

        $total_details_arr  = array();
        foreach ($section_wise_total_fee_arr as $key => $value)
        {
           $total_details_arr[$value['section_id']]['actual_amount']=$value['actual_amount'];
        }

        $section_wise_unpaid_fee_qur = $this->get_section_wise_unpaid_fee_query();
        $section_wise_unpaid_fee_arr = $this->get_query_result_in_array($section_wise_unpaid_fee_qur);

        $unpaid_details_arr  = array();
        foreach ($section_wise_unpaid_fee_arr as $key => $value)
        {
           $unpaid_details_arr[$value['section_id']]['actual_amount']=$value['actual_amount'];
        }

        $section_wise_arrears_fee_qur = $this->get_section_wise_arrears_fee_query();
        $section_wise_arrears_fee_arr = $this->get_query_result_in_array($section_wise_arrears_fee_qur);

        $arrears_details_arr  = array();
        foreach ($section_wise_arrears_fee_arr as $key => $value)
        {
           $arrears_details_arr[$value['section_id']]['arrears']=$value['arrears'];
        }

        $section_discount_arr = $this->get_section_wise_discount();
        $page_data['section_discount_arr'] = $section_discount_arr;

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $start_date = $filter_arr['start_date'];
        $end_date = $filter_arr['end_date'];
        $section_id = $filter_arr['section_id'];

        $d_school_id = $filter_arr['d_school_id'];
        
        $page_data['d_school_id']         = $d_school_id;
        $page_data['total_details_arr']   = $total_details_arr;
        $page_data['payment_details_arr'] = $payment_details_arr;
        $page_data['arrears_details_arr'] = $arrears_details_arr;
        $page_data['unpaid_details_arr'] = $unpaid_details_arr;
        $page_data['apply_filter']       = $apply_filter;
        $page_data['start_date']         = $start_date;
        $page_data['end_date']           = $end_date;
        $page_data['section_id']         = $section_id;
        $page_data['page_name']          = 'section_wise_paid_fee_print';
        $page_data['page_title']         = get_phrase('section_wise_paid_fee_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('Pdf');
        $view = 'backend/admin/section_wise_paid_fee_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        
    }
    
    /* _____________key_performance_indicators_pdf ________________*/
    function key_performance_indicators_pdf(){
        $page_data['page_name']             = 'key_performance_indicators';
        $page_data['page_title']            = 'key_performance_indicators';
        
        $total_std=total_school_studnets();
        $total_addmission=total_school_new_admission();
        $total_withdrawal=total_school_withdrawal();
        $total_current_strength = $total_std+$total_addmission-$total_withdrawal;
        $net_gain = $total_current_strength-$total_std;
        $page_data['school_capacity'] = school_capacity($_SESSION['school_id']);
        $page_data['total_std']   = $total_std;
        $page_data['total_addmission'] = $total_addmission;
        $page_data['total_withdrawal'] = $total_withdrawal;
        $page_data['total_current_strength']       = $total_current_strength;
        $page_data['net_gain']         = $net_gain;
        $page_data['page_name']          = 'key_performance_indicators_print';
        $page_data['page_title']         = get_phrase('key_performance_indicators_print');
        $this->load->library('Pdf');
        $view = 'backend/admin/key_performance_indicators_pdf_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        
    }
    
    /* _____________key_performance_indicators_pdf ________________*/
    function breakeven_report_pdf(){
        $filter = $this->input->post("apply_filter");
        if($filter == 1){
            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");
            
            $start_date1 = date('Y-m-d',strtotime($start_date));
            $end_date1 = date('Y-m-d',strtotime($end_date));
            
            $revenue = $this->get_coa_ids(get_parent_head_coa_id('income_stmt_sales'));
            $revenue_query = $this->db->query("SELECT SUM(credit) AS TotalCVRevenue, SUM(debit) AS TotalDVRevenue FROM ".get_school_db().".journal_entry WHERE coa_id IN('$revenue') AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') BETWEEN '$start_date1' AND '$end_date1' ")->row();
            $total_credit_rev = intval($revenue_query->TotalCVRevenue);
            $total_debit_rev = intval($revenue_query->TotalDVRevenue);
            $data['revenue'] = $total_credit_rev-$total_debit_rev;
            $school_capacity = school_capacity($_SESSION['school_id']);
            $this->coa_ids = array();
            $total_std = total_school_studnets();
            $exp_id = get_parent_head_coa_id('income_stmt_expense');
            $expanse = $this->get_coa_ids($exp_id);
            $expanse_query = $this->db->query("SELECT SUM(credit) AS TotalCExpanse, SUM(debit) AS TotalDExpanse FROM ".get_school_db().".journal_entry WHERE coa_id IN('$expanse') AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') BETWEEN '$start_date1' AND '$end_date1' ")->row();
            $total_credit_exp = intval($expanse_query->TotalCExpanse);
            $total_debit_exp = intval($expanse_query->TotalDExpanse);
            $data['expanse'] = $total_debit_exp-$total_credit_exp;
            $data['net_profit'] = $data['revenue']-$data['expanse'];
            $data['per_student_expense'] = number_format($data['expanse']/$total_std,2);
            $data['break_even_strngth'] = $data['net_profit'] / $data['per_student_expense'] ;
            
            $data['capacity_utilization'] = number_format($total_std / $school_capacity, 2);
            
            $data['start_date'] = $start_date;
            $data['apply_filter'] = $filter;
            $data['end_date'] = $end_date;
            // print_r($data);exit;
        }
        
        $data['page_name']             = 'breakeven_report';
        $data['page_title']            = 'breakeven_report';
        $this->load->library('Pdf');
        $view = 'backend/admin/breakeven_report_pdf_print';
        $this->pdf->load_view($view,$data);
        $this->pdf->render();
        $this->pdf->stream("".$data['page_title'].".pdf");
        
    }
    
    /*______________section wise paid excel__________________*/
    function section_wise_paid_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Section Wise Payment Details');
        $this->load->database();

        $section_wise_paid_fee_qur = $this->get_section_wise_paid_fee_query();
        $section_wise_paid_fee_arr = $this->get_query_result_in_array($section_wise_paid_fee_qur);

        $payment_details_arr  = array();
        foreach ($section_wise_paid_fee_arr as $key => $value)
        {
         
           $payment_details_arr[$value['section_id']]['received_amount']=$value['received_amount'];
        }
        //---------total details__________________________
        $section_wise_total_fee_qur =  $this->get_section_wise_total_fee_query();
        $section_wise_total_fee_arr = $this->get_query_result_in_array($section_wise_total_fee_qur);

        $total_details_arr  = array();
        foreach ($section_wise_total_fee_arr as $key => $value)
        {
           $total_details_arr[$value['section_id']]['actual_amount']=$value['actual_amount'];
        }
        //---------unpaid details__________________________
        $section_wise_unpaid_fee_qur = $this->get_section_wise_unpaid_fee_query();
        $section_wise_unpaid_fee_arr = $this->get_query_result_in_array($section_wise_unpaid_fee_qur);

        $unpaid_details_arr  = array();
        foreach ($section_wise_unpaid_fee_arr as $key => $value)
        {
           $unpaid_details_arr[$value['section_id']]['actual_amount']=$value['actual_amount'];
        }
        //---------arrears details__________________________
        $section_wise_arrears_fee_qur = $this->get_section_wise_arrears_fee_query();
        $section_wise_arrears_fee_arr = $this->get_query_result_in_array($section_wise_arrears_fee_qur);

        $arrears_details_arr  = array();
        foreach ($section_wise_arrears_fee_arr as $key => $value)
        {
           $arrears_details_arr[$value['section_id']]['arrears']=$value['arrears'];
        }
        //---------discount details__________________________
        $section_discount_arr = $this->get_section_wise_discount();

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $start_date = date_view($filter_arr['start_date']);
        $end_date = date_view($filter_arr['end_date']);
        $section_id = $filter_arr['section_id'];

        $d_school_id = $filter_arr['d_school_id'];

        $count =0;
        $total_actual = 0;
        $total_received = 0;
        $total_unpaid = 0;
        $total_arrears = 0;
        $total_discount = 0;
        $c = 0;

        $titles_arr = array();
        $section_paid_arr = array();

        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $section_paid_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];

            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $section_paid_arr[] = system_path($branch_logo,$branch_folder,1);
        }

        $id_arr = remove_prefix($section_id);
        $prefix = $id_arr['prefix'];
        $value = $id_arr['value'];

        $heading = get_phrase("payment_details");
        if ($prefix=='d') {
            $heading = get_phrase("department_wise_payments_report");
        }elseif ($prefix=='c') {
            $heading = get_phrase("class_wise_payments_report");
        }elseif ($prefix=='s'){
            $heading = get_phrase("section_wise_payments_report");
        }
        $section_paid_arr[] = $heading;
        $section_paid_arr[] = "";

        if (isset($section_id) && !empty($section_id))
        {
            $dep_cls_sec ="";
            if ($prefix=='d') {
                $label = "Department";
                $dep_cls_sec = get_department_name($value,$d_school_id);
            }elseif ($prefix=='c') {
                $label = "Class";
                $dep_cls_arr = class_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_arr['d']." / ".$dep_cls_arr['c'];
            }elseif ($prefix=='s') {
                $label = "Section";
                $dep_cls_sec_arr = section_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_sec_arr['d']." / ".$dep_cls_sec_arr['c']." / ".$dep_cls_sec_arr['s'];
            }
            $section_paid_arr[] = $dep_cls_sec;
            $c++;
            $titles_arr[$c] = $label." : ".$dep_cls_sec;
        }

        if (isset($start_date) && !empty($start_date))
        {
            $section_paid_arr[] = $start_date;
            $c++;
            $titles_arr[$c] = "Start Date: ". $start_date;
        }
        if (isset($end_date) && !empty($end_date))
        {
            $section_paid_arr[] = $end_date;
            $c++;
            $titles_arr[$c] = "End Date: ". $end_date;
        }
        
        $section_wise_total_fee_qur =  $this->get_section_wise_total_fee_query();
        $section_wise_total_fee_arr = $this->get_query_result_in_array($section_wise_total_fee_qur);

        // $total_details_arr  = array();
        // foreach ($section_wise_total_fee_arr as $key => $value)
        // {
        //   $total_details_arr[$value['section_id']]['actual_amount']=$value['actual_amount'];
        // }

        $section_paid_arr[] = array('Sr.','Department','Class','Section','Strength', 'Fee','Recovery','Outstanding Fee','Recovery');
        $sec_ary = department_class_section_hierarchy($section_id,$d_school_id);
        foreach ($sec_ary as $key => $value)
        {
            $count++;
            $total_actual = $total_actual+$total_details_arr[$value['s_id']]['actual_amount'];
            $total_received = $total_received + $payment_details_arr[$value['s_id']]['received_amount'];
            $total_unpaid = $total_unpaid + $unpaid_details_arr[$value['s_id']]['actual_amount'];
            $total_arrears = $total_arrears+$arrears_details_arr[$value['s_id']]['arrears'];
            $total_discount = $total_discount+array_sum($section_discount_arr[$value['s_id']]);
            $rec_amount = $payment_details_arr[$value['s_id']]['received_amount'];
            if($rec_amount > 0){
                $percentage = (($total_details_arr[$value['s_id']]['actual_amount']) / ($rec_amount) * 100) ;    
                if(is_nan($percentage) == 1){
                   echo "";
                }else{
                   echo $percentage."%";
                }
            }

            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['d'];
            $record_arr[] = $value['c'];
            $record_arr[] = $value['s'];
            $record_arr[] = section_student_count($value['s_id']);
            $record_arr[] = $total_actual;
            $record_arr[] = $total_received;
            $record_arr[] = $total_unpaid;
            $record_arr[] = $percentage;

            $section_paid_arr[] = $record_arr;
        }
        //______________set logo_______________

        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:I1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________set Heading_______________
        $this->excel->getActiveSheet()->mergeCells('A2:I2');
        $this->excel->getActiveSheet()->setCellValue('A2', $section_paid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A3:I3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':I'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }

        $count = $count+$c+5;
        $this->excel->getActiveSheet()->setCellValue('D'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('E'.$count.'',$total_actual);
        $this->excel->getActiveSheet()->setCellValue('F'.$count.'',$total_received);
        $this->excel->getActiveSheet()->setCellValue('G'.$count.'',$total_unpaid);
        $this->excel->getActiveSheet()->setCellValue('H'.$count.'',$total_arrears);
        $this->excel->getActiveSheet()->setCellValue('I'.$count.'',$total_discount);
        //____________End total__________________
        $this->excel->getActiveSheet()->fromArray($section_paid_arr);
        $filename='Section Wise Fee Summary.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"'); 
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }

    function get_filters()
    {
        $apply_filter = $this->input->post('apply_filter', TRUE);
        $std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'","\""),"",$std_search));
        $section_id = $this->input->post('section_id', TRUE);
        $student_id = $this->input->post('student_id', TRUE);

        $start_date = "";
        $temp_start = $this->input->post('startdate' ,TRUE);
        if (isset($temp_start ) && !empty($temp_start)) {
            $start_date = date_slash($temp_start);
        }
        $end_date = "";
        $temp_end = $this->input->post('enddate' ,TRUE);
        if (isset($temp_end ) && !empty($temp_end )){
           $end_date = date_slash($temp_end);
        }
        
        $select_date ="";
        $temp_select = $this->input->post('select_date' ,TRUE);
        if (isset($temp_select ) && !empty($temp_select)) {
            $select_date = date_slash($temp_select);
        }

        $d_school_id = $this->input->post('d_school_id' ,TRUE);
        
        $filter_arr = array();
        $filter_arr['apply_filter'] = $apply_filter;
        $filter_arr['std_search'] = $std_search;
        $filter_arr['section_id'] = $section_id;
        $filter_arr['student_id'] = $student_id;
        $filter_arr['start_date'] = $start_date;
        $filter_arr['end_date'] = $end_date;
        $filter_arr['select_date'] = $select_date;
        $filter_arr['d_school_id'] = $d_school_id;
        return $filter_arr;
    }
    /*__________function that returns array_result()_________*/
    function get_query_result_in_array($query='')
    {
        return $this->db->query($query)->result_array();
    }
    /*__________function that returns student list___________*/
    function get_section_student()
    {
        echo section_student($this->input->post('section_id'),$this->input->post('student_id'),$this->input->post('d_school_id'));
    }
    /*__________function that returns discounts______________*/
    function get_discount()
    {
        $filter_arr = $this->get_filters();
        $std_search = $filter_arr['std_search'];
        $section_id = $filter_arr['section_id'];
        $student_id = $filter_arr['student_id'];
        $start_date = $filter_arr['start_date'];
        $end_date   = $filter_arr['end_date'];

        $start_date = $filter_arr['start_date'];
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }

        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }

        $std_query="";
        if ( isset($std_search) && $std_search != "")
        {
            $std_query =" AND (
            scf.student_id LIKE '%".$std_search."%' OR
            scf.student_name LIKE '%".$std_search."%' OR 
            scf.roll LIKE '%".$std_search."%' OR
            scf.mob_num LIKE '%".$std_search."%' OR 
            scf.reg_num LIKE '%".$std_search."%' OR 
            scf.mob_num LIKE '%".$std_search."%' OR
            scf.system_id LIKE '%".$std_search."%' OR
            scf.chalan_form_number LIKE '%".$std_search."%' OR
            scf.father_name LIKE '%".$std_search."%' OR
            scf.section LIKE '%".$std_search."%' OR 
            scf.class LIKE '%".$std_search."%' OR 
            scf.department LIKE '%".$std_search."%' OR 
            scf.chalan_form_number LIKE '%".$std_search."%'
            )";
        }
                    //_______Section filter____________
        if ( !isset($section_id) || $section_id == "")
        {
            $section_id = "";
        }
        else
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
            }
            elseif ($prefix =='c')
            {
                $class_id = $value;

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section
                 where school_id=".$_SESSION['school_id']." and class_id=".$class_id."")->result_array();

                $section = array();
                if (count($sec)>0) {
                    foreach($sec as $s)
                    {
                        $section[] = $s['section_id'];
                    }
                    $section_id = implode(",",$section);
                }
                else
                {
                    $section_id = "";
                }
            }
            elseif ($prefix =='d')
            {
                $department_id = $value;
                $query = "select cs.section_id from ".get_school_db().".class c inner join 
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_SESSION['school_id']."";
                $query_result =$this->db->query($query)->result_array();
                $sections = array();
                if (count($query_result)>0)
                {
                    foreach ($query_result as $value)
                    {
                        $sections[]=$value['section_id'];
                    }

                    $section_id = implode(",",$sections);
                }

                else
                {
                    $section_id = "";
                }
            }
            else
            {
                $section_id = "";
            }

        }
        $section_filter = "";
        if ($section_id != "")
        {
            $section_filter = " AND scf.section_id in(".$section_id.")";
        }
                //student filter
        $student_filter ="";
        if ($student_id > 0)
        {
            $student_filter = " AND scf.student_id=".$student_id;
        }
                    //_______Date filter____________
        $date_filter="";
        if (($start_date != "")&&($end_date != "" ))
        {
            $date_filter = " And DATE_FORMAT(scf.issue_date, '%Y-%m-%d') BETWEEN '".$start_date."' And '".$end_date."'";
        }

        $d_school_id = $this->uri->segment(3);
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_SESSION['school_id'];
        }

        $discount_qur = "select scf.section_id, scd.s_c_f_id, scd.s_c_d_id, scd.type, scd.amount, scd.type_id,scd.related_s_c_d_id
                         from ".get_school_db().".student_chalan_form scf INNER join ".get_school_db().".student_chalan_detail scd 
                         on scf.s_c_f_id = scd.s_c_f_id where scf.is_processed=0 and scf.is_cancelled=0 and scf.school_id=".$d_school_id." 
                         and scd.type in(1,2) ".$std_query." ".$section_filter." ".$student_filter." ".$date_filter." order by scf.s_c_f_id";
        $discount_arr = $this->get_query_result_in_array($discount_qur);

        $discount_details_arr = array();
        foreach ($discount_arr as $key => $value)
        {
            $discount_details_arr[$value['s_c_f_id']][$value['type']][$value['s_c_d_id']]['amount']= $value['amount'];
            $discount_details_arr[$value['s_c_f_id']][$value['type']][$value['s_c_d_id']]['type_id']= $value['type_id'];
            $discount_details_arr[$value['s_c_f_id']][$value['type']][$value['s_c_d_id']]['related_s_c_d_id']= $value['related_s_c_d_id'];
        }
        $discount_amount_arr = array();
        foreach ($discount_details_arr as $key => $value)
        {
            $discount_amount =0;
            foreach ($value[2] as $key1 => $value1)
            {
                $discount_percent = $value1['amount'];
                $related_id = $value1['related_s_c_d_id'];
                $discount = 0;
                if ($related_id>0)
                {
                    $fee_amount = $value[1][$related_id]['amount'];
                    $discount = ($fee_amount)*($discount_percent/100);
                    $discount_amount += $discount;
                }
            }
            $discount_amount_arr[$key] = $discount_amount;
        }

        return $discount_amount_arr;
    }
    
    function section_wise_students_list(){
        $section_id = $this->input->post('section_id');
        $department = $this->input->post('department');
        $class = $this->input->post('class');
        $academic_year = $this->input->post('academic_year');
        
        $ssdate = $this->input->post('startdate');
        $eedate = $this->input->post('enddate');
        
        $startdate = date('Y-m-d',strtotime($ssdate));
        $enddate = date('Y-m-d',strtotime($eedate));
        
        $school_id = $this->uri->segment(3);
        
        if(isset($section_id) && $section_id != ""){
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
                $std_list = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.section_id = '$section_id' AND a.school_id = '$school_id'");
            }
            $page_title = get_phrase('section_wise_students_report');
            $h_name = "section";
        }
        
        if(isset($department) && $department != ""){
            $id_arr = remove_prefix($department);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='d')
            {
                $section_id = $value;
                $std_list = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE d.departments_id = '$section_id' AND d.school_id = '$school_id'");
            }
            $page_title = get_phrase('department_wise_students_report');
            $h_name = "department";
        }
        
        if(isset($academic_year) && $academic_year != ""){
            $section_id = $academic_year;
            $std_list = $this->db->query("SELECT a.*, b.*,c.*,d.*,e.academic_year_id,e.title as year_title,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id LEFT JOIN ".get_school_db().".acadmic_year as e ON e.academic_year_id = a.academic_year_id WHERE a.academic_year_id = '$section_id' AND d.school_id = '$school_id'");
            $page_title = get_phrase('department_wise_students_report');
            $h_name = "academic_year";
        }
        
         if(isset($class) && $class != ""){
            $id_arr = remove_prefix($class);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='c')
            {
                $section_id = $value;
                $std_list = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.adm_date BETWEEN '$startdate' AND '$enddate' AND c.class_id = '$section_id' AND a.school_id = '$school_id' ");
            }
            $page_title = get_phrase('datewise_students_report');
            $h_name = "datewise";
        }
        $page_data['sdate'] = $startdate;
        $page_data['edate'] = $enddate;
        $page_data['sectionwise_list_std'] = $std_list;
        $page_data['section_id'] = $section_id;
        $page_data['hidden_name'] = $h_name;
        $page_data['page_name']  = 'section_wise_students_report';
        $page_data['page_title'] = $page_title;
        $this->load->view('backend/index', $page_data);
    }

    function section_wise_students_list_to_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        // $this->excel->getActiveSheet()->setTitle('Section Wise Student Admission Report');
        $this->load->database();
        $unpaid_arr = array();
        
        $section = $this->input->post('section');
        $school_id = $this->input->post('d_school_id');
        
        $department = $this->input->post('department');
        $class = $this->input->post('class');
        $academic_year = $this->input->post('academic_year');
        $startdate = date('Y-m-d',strtotime($this->input->post('startdate')));
        $enddate = date('Y-m-d',strtotime($this->input->post('enddate')));
        $section_id = $section;
        if($_POST['check_report'] == "section"){
            $get_title = 'Section Wise Student Admission Report';
            $std_list_sectionwise_arr = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.section_id = '$section' AND a.school_id = '$school_id'")->result_array();
        }
        
        if($_POST['check_report'] == "department"){
            $get_title = 'Department Wise Student Admission Report';
            $std_list_sectionwise_arr = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE d.departments_id = '$section_id' AND d.school_id = '$school_id'")->result_array();
        }
        
        if($_POST['check_report'] == "academic_year"){
            $get_title = 'Academic Year Wise Student Admission Report';
            $std_list_sectionwise_arr = $this->db->query("SELECT a.*, b.*,c.*,d.*,e.academic_year_id,e.title as year_title,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id LEFT JOIN ".get_school_db().".acadmic_year as e ON e.academic_year_id = a.academic_year_id WHERE a.academic_year_id = '$section_id' AND d.school_id = '$school_id'")->result_array();
        }
        
         if($_POST['check_report'] == "datewise"){
            $get_title = 'Date Wise Student Admission Report';
            $std_list_sectionwise_arr = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.adm_date BETWEEN '".$_POST['startdate']."' AND '".$_POST['enddate']."' AND c.class_id = '$section_id' AND a.school_id = '$school_id'")->result_array();
        }
        
        $logo_path="";
        $school_name = "";
        if (empty($school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $unpaid_arr[] = system_path($_SESSION['school_logo']);
        }else{
            $school_details = get_school_details($school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $unpaid_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        $unpaid_arr[] = $get_title;
        $unpaid_arr[] = "";

        $unpaid_arr[] = array('Sr.','Student Name With Father Name','Class / Section','DOB', 'Gender', 'Religion', 'Adress','Nationality','Date of Admission','Mobile No');
        foreach ($std_list_sectionwise_arr as $key => $value)
        {
            $count++;
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['std'];
            $record_arr[] = $value['class']. ' ' . $value['section'];
            $record_arr[] = $value['birthday'];
            $record_arr[] = $value['gender'];
            $record_arr[] = $value['religion'];
            $record_arr[] = $value['address'];
            $record_arr[] = $value['nationality'];
            $record_arr[] = $value['adm_date'];
            $record_arr[] = $value['phone'] . ' ' . $value['mob_num']; 

            $unpaid_arr[] = $record_arr;
        }
        
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
       // $objDrawing->setCoordinates('A1');
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   
        //setOffsetX works properly
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                
        //set width, height
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:G1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('H1:X1');
        $this->excel->getActiveSheet()->mergeCells('A2:G2');
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('H2:X2');
        $this->excel->getActiveSheet()->mergeCells('A3:G3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('H3:X3');

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':G'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, "Section Wise Admission");
            $this->excel->getActiveSheet()->getStyle('A'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->mergeCells('H'.$row_no.':X'.$row_no);
        }

        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='section wise admission report.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');   
    }

    function section_wise_students_list_to_pdf()
    {
        $section   = $this->input->post('section');
        $school_id = $this->input->post('d_school_id');
        
        $department = $this->input->post('department');
        $class      = $this->input->post('class');
        $academic_year = $this->input->post('academic_year');
        $startdate     = date('Y-m-d',strtotime($this->input->post('startdate')));
        $enddate       = date('Y-m-d',strtotime($this->input->post('enddate')));
        $section_id    = $section;
        
        if($_POST['check_report'] == "section"){
            $get_title = 'Section Wise Student Admission Report';
            $std_list_sectionwise_arr = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.section_id = '$section' AND a.school_id = '$school_id'")->result_array();
        }
        
        if($_POST['check_report'] == "department"){
            $get_title = 'Department Wise Student Admission Report';
            $std_list_sectionwise_arr = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE d.departments_id = '$section_id' AND d.school_id = '$school_id'")->result_array();
        }
        
        if($_POST['check_report'] == "academic_year"){
            $get_title = 'Academic Year Wise Student Admission Report';
            $std_list_sectionwise_arr = $this->db->query("SELECT a.*, b.*,c.*,d.*,e.academic_year_id,e.title as year_title,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id LEFT JOIN ".get_school_db().".acadmic_year as e ON e.academic_year_id = a.academic_year_id WHERE a.academic_year_id = '$section_id' AND d.school_id = '$school_id'")->result_array();
        }
        
        if($_POST['check_report'] == "datewise"){
            $get_title = 'Date Wise Student Admission Report';
            $std_list_sectionwise_arr = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.adm_date BETWEEN '".$_POST['startdate']."' AND '".$_POST['enddate']."' AND c.class_id = '$section_id' AND a.school_id = '$school_id'")->result_array();
        }
        
        $page_data['d_school_id']         = $school_id;
        $page_data['student_listing_arr'] = $std_list_sectionwise_arr;
        $page_data['page_name']           = 'section_admission_report_print';
        $page_data['page_title']          = get_phrase('section_wise_students_report');

        $this->load->library('Pdf');
        $view = 'backend/admin/section_admission_report_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    
    function student_credential_view()
    {
        $page_data['page_name']  = 'student_credential_view';
        $page_data['page_title'] = 'student_credential_view';
        $this->load->view('backend/index', $page_data);
    }
    
    function student_credential_list()
    {
        $section_id = $this->input->post('section_id');
       
        if(isset($section_id) && $section_id != "")
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];
            
            if ($prefix =='d')
            {
                $section_id = $value;
                
                $department_section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                inner join ".get_school_db().".class c on cs.class_id = c.class_id
                inner join ".get_school_db().".departments d on d.departments_id = c.departments_id
                where d.school_id= ".$_SESSION['school_id']." and d.departments_id = ".$section_id." ";
                $sections = $this->db->query($department_section_query)->result_array();
            }
            
            if ($prefix =='c')
            {
                $section_id = $value;
                $class_section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                inner join ".get_school_db().".class c on cs.class_id = c.class_id
                where c.school_id= ".$_SESSION['school_id']." and c.class_id = ".$section_id." ";
                $sections = $this->db->query($class_section_query)->result_array();
            }

            if ($prefix =='s')
            {
                $section_id = $value;
                $section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                where school_id= ".$_SESSION['school_id']." and section_id = ".$section_id." ";
                $sections = $this->db->query($section_query)->result_array();
            }
        }
        
        $filter = "";
        if(isset($sections[0]['sections']) && $sections[0]['sections'] != "")
        {
           $filter   = " s.section_id in (".$sections[0]['sections'].") and ";
        }
        
        $found_query = "SELECT u.* , ud.* , s.* FROM ".get_school_db().".student s
                        join ".get_system_db().".user_login_details ud on (ud.user_login_detail_id = s.user_login_detail_id)
                        join ".get_system_db().".user_login u on (u.user_login_id = ud.user_login_id)
                        where ".$filter." s.school_id = ".$_SESSION['school_id']." and s.student_status in (".student_query_status().") and ud.login_type = 6 and s.is_login_created = 1 
                        and s.id_no <> '' and ud.sys_sch_id = ".$_SESSION['school_id']." ";
        $found_credentials = $this->db->query($found_query)->result_array();
        
        $not_found_query = "SELECT s.* FROM ".get_school_db().".student s 
                            where ".$filter." s.school_id = ".$_SESSION['school_id']." and s.student_status in (".student_query_status().") and s.is_login_created = 0";
        $not_found_credentials = $this->db->query($not_found_query)->result_array();
        
        
        /*
        
        $found_query = "SELECT u.* , ud.* , s.* FROM ".get_school_db().".student s join
        ".get_system_db().".user_login u on u.id_no = s.id_no
        join ".get_system_db().".user_login_details ud on (u.user_login_id = ud.user_login_id)
        where ".$filter." s.school_id = ".$_SESSION['school_id']." and ud.login_type = 6 and ud.sys_sch_id = ".$_SESSION['school_id']." ";
        $found_credentials = $this->db->query($found_query)->result_array();
        
        
        $not_found_query = "SELECT s.* FROM ".get_school_db().".student s 
        where ".$filter." s.school_id = ".$_SESSION['school_id']." and s.id_no not in 
        (select u.id_no from ".get_system_db().".user_login u join 
        ".get_system_db().".user_login_details ud on (u.user_login_id = ud.user_login_id) 
        where ud.login_type = 6 and ud.sys_sch_id = ".$_SESSION['school_id'].")";
        $not_found_credentials = $this->db->query($not_found_query)->result_array();
        
        */
        
        $page_data['found_credentials']     = $found_credentials;
        $page_data['not_found_credentials'] = $not_found_credentials;
        $page_data['section_id']            = $this->input->post('section_id');
        $page_data['page_name']             = 'student_credential_list';
        $page_data['page_title']            = 'student_credential_list';
        $this->load->view('backend/index', $page_data);
    }
    
    function generate_student_credentials()
    {

        $this->db->trans_begin();

        $user_gorup_id          = $this->input->post('user_gorup_id');
        $parent_user_gorup_id   = $this->input->post('parent_user_gorup_id');
        $student_ids_arr        = $this->input->post('student_ids');
        $parent_ids_arr         = $this->input->post('parent_ids');
        $student_ids            = explode(',' , $student_ids_arr[0]);
        $parent_ids             = explode(',' , $parent_ids_arr[0]); 
        $creds_genration        = $this->input->post('creds_genration');

        if(isset($creds_genration) && $creds_genration == 1)
        {
            $student_counter = 0;
            foreach($student_ids as $std_id)
            {
                
                $student_details  = student_details($std_id);
                $login_type_id    = 6;
                
                $data['name']     = $student_details[0]['name'];
                $password         = "123456";
                $data['password'] = passwordHash($password);
                $id_no            = $student_details[0]['id_no'];;
                $data['id_no']    = $id_no;
                $parent_id        = $student_details[0]['parent_id'];
                $student_id       = $student_details[0]['student_id'];
                $data['status']   = 1;
                $parent_arr       = $this->db->query("select id_no from ".get_system_db().".user_login where id_no = '".$student_details[0]['id_no']."' ")->result_array();
                
                if (count($parent_arr) == 0)
                {
                    $this->db->insert(get_system_db().'.user_login', $data);

                    $last_id             = intval($this->db->insert_id());
                    $update['system_id'] = get_system_id($last_id,$_SESSION['school_id'],'student');
                    $this->db->update(get_system_db().'.user_login', $update, array("user_login_id" => $last_id));
                    
                    // Insert Student Right
                    if( check_if_user_group_assigned($student_id) ){
                        $data_rights                  = array();
                        $data_rights['user_group_id'] = $user_gorup_id;
                        $data_rights['student_id']    = $student_id;
                        $data_rights['school_id']     = $_SESSION['school_id'];
                        $this->db->insert(get_school_db().'.user_rights', $data_rights);
                    }
                    
                    $student_update['parent_id']      = $parent_id;
                    
                    $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));

                    $detail['user_login_id'] = $last_id;
                    $detail['sys_sch_id']    = $_SESSION['sys_sch_id'];
                    $detail['creation_date'] = date('Y-m-d h:i:s');
                    $detail['created_by']    = $_SESSION['user_login_id'];
                    $detail['status']        = 1;
                    $detail['login_type']    = $login_type_id;

                    $is_exists =  $this->db->query(" select user_login_detail_id from ".get_system_db().".user_login_details where user_login_id = $last_id and sys_sch_id = ".$_SESSION['sys_sch_id']." and login_type = $login_type_id ")->result_array();

                    if (count($is_exists) == 0)
                    {
                        $this->db->insert(get_system_db().'.user_login_details', $detail);
                        
                        $user_login_detail_id_for_student    = $this->db->insert_id();
                        $parent_data['user_login_detail_id'] = $user_login_detail_id_for_student;
                        
                        $this->db->update(get_school_db().'.student_parent', $parent_data, array( "s_p_id" => $parent_id) );
                        
                        $student_user_login_detail_id['is_login_created']     = 1;
                        $student_user_login_detail_id['user_login_detail_id'] = $user_login_detail_id_for_student;
                        
                        $this->db->update(get_school_db().'.student', $student_user_login_detail_id , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                        
                        if($parent_id > 0){
                        }
                        else
                        {
                            if( isset($parent_ids[$student_counter]) && $parent_ids[$student_counter] > 0){
                                $this->create_parent_login($student_id , $parent_user_gorup_id);
                            }
                        }
                        
                        $this->session->set_flashdata('club_updated', get_phrase('logins_created_successfully'));
                    }
                    else
                    {
                        $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                    }
                    
                }
                elseif(count($parent_arr) == 1)
                {
                    
                   if( isset($password) && $password != '')
                   {
                        $data['password'] = passwordHash($password);
                   }
                    
                   // Insert Student Right
                   if( check_if_user_group_assigned($student_id) ){
                        $data_rights                  = array();
                        $data_rights['user_group_id'] = $user_gorup_id;
                        $data_rights['student_id']    = $student_id;
                        $data_rights['school_id']     = $_SESSION['school_id'];
                        $this->db->insert(get_school_db().'.user_rights', $data_rights);
                    }
                    else
                    {
                          $user_group_update['user_group_id'] = $user_gorup_id;
                          $this->db->update(get_school_db().'.user_rights', $user_group_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
                    }
                    
                    $this->db->update(get_system_db().'.user_login', $data, array("id_no" => $id_no));
                    $this->session->set_flashdata('club_updated', get_phrase('logins_updated_successfully'));
        
                }
                else
                {
                    $this->session->set_flashdata('error_msg', get_phrase('system_error_duplicate_entries'));
                }
                
                ++$student_counter;
            }
        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        redirect(base_url().'reports/student_credential_view');
    }
    
    function create_parent_login($student_id , $parent_user_group)
    {
        
        $school_id      = $_SESSION['school_id'];
        $parent_details = $this->db->query("select sp.s_p_id, sr.relation, sp.id_no, sp.p_name from ".get_school_db().".student s
                                            inner join ".get_school_db().".student_relation sr on s.student_id = sr.student_id
                                            inner join ".get_school_db().".student_parent sp on sp.s_p_id = sr.s_p_id
                                            where s.student_id = $student_id  and s.school_id = $school_id   
                                            and s.student_status in (".student_query_status().") and sr.relation = 'f' ")->row();

        if($parent_details != null){
            
            $parent_id         = $parent_details->s_p_id;
            
            $parent_arr        = $this->db->query("select * from ".get_school_db().".student_parent where s_p_id = $parent_id ")->result_array();
            $password          = "123456";
            $login_type_id     = get_login_type_id('parent'); 
            
            $data['id_no']     = $parent_arr[0]['id_no'];
            $data['name']      = $parent_arr[0]["p_name"];
            $data['email']     = $parent_arr[0]['id_no']; //$this->input->post('email');
            $data['password']  = passwordHash($password);
            $user_group_id     = $parent_user_group;
            $data['status']    = 1;
            
            $parent_arr        = $this->db->query(" select id_no from ".get_system_db().".user_login  where id_no = '".$data['id_no']."' ")->result_array();
            
            if (count($parent_arr) == 0)
            {
                $this->db->trans_begin();
                
                $this->db->insert(get_system_db().'.user_login', $data);
    
                $last_id             = intval($this->db->insert_id());
                $update['system_id'] = get_system_id($last_id,$_SESSION['school_id'],'parent');
                $this->db->update(get_system_db().'.user_login', $update, array("user_login_id" => $last_id));
    
                $student_update['parent_id'] = $parent_id;
                $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
    
                $detail['user_login_id'] = $last_id;
                $detail['sys_sch_id']    = $_SESSION['sys_sch_id'];
                $detail['creation_date'] = date('Y-m-d h:i:s');
                $detail['created_by']    = $_SESSION['user_login_id'];
                $detail['status']        = 1;
                $detail['login_type']    = $login_type_id;
    
                $is_exists =  $this->db->query(" select user_login_detail_id from ".get_system_db().".user_login_details 
                                                 where user_login_id = $last_id and sys_sch_id = ".$_SESSION['sys_sch_id']."
                                                 and login_type = $login_type_id ")->result_array();
    
                if (count($is_exists) == 0)
                {
                    $this->db->insert(get_system_db().'.user_login_details', $detail);
                    $parent_data['user_login_detail_id'] = $this->db->insert_id();
                    $this->db->update(get_school_db().'.student_parent', $parent_data, array( "s_p_id" => $parent_id) );
                    
                    if( check_if_parent_user_group_assigned($parent_id) ){
                        $assign_group['user_group_id']  =  $user_group_id;
                        $assign_group['school_id']      =  $_SESSION['school_id'];
                        $assign_group['parent_id']      =  $parent_id;
                        $this->db->insert(get_school_db() . '.user_rights', $assign_group);
                    }    

                }
               
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } 
                else {
                    $this->db->trans_commit();
                }
                
            }
            else
            {
                $student_update['parent_id'] = $parent_id;
                $this->db->update(get_school_db().'.student', $student_update , array("student_id" => $student_id, 'school_id'=> $_SESSION['school_id']));
            }
            
        }                                             
        
            
    }
    
    function student_parent_credentials_pdf()
    {
        
        $section_id = $this->input->post('section_id');
        if(isset($section_id) && $section_id != "")
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];
            
            if ($prefix =='d')
            {
                $section_id               = $value;
                $department_section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                inner join ".get_school_db().".class c on cs.class_id = c.class_id
                inner join ".get_school_db().".departments d on d.departments_id = c.departments_id
                where d.school_id= ".$_SESSION['school_id']." and d.departments_id = ".$section_id." ";
                $sections = $this->db->query($department_section_query)->result_array();
            }
            
            if ($prefix =='c')
            {
                $section_id = $value;
                $class_section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                inner join ".get_school_db().".class c on cs.class_id = c.class_id
                where c.school_id= ".$_SESSION['school_id']." and c.class_id = ".$section_id." ";
                $sections = $this->db->query($class_section_query)->result_array();
            }

            if ($prefix =='s')
            {
                $section_id    = $value;
                $section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                where school_id= ".$_SESSION['school_id']." and section_id = ".$section_id." ";
                $sections = $this->db->query($section_query)->result_array();
            }
        }
        
        $filter = "";
        if(isset($sections[0]['sections']) && $sections[0]['sections'] != "")
        {
           $filter   = " s.section_id in (".$sections[0]['sections'].") and ";
        }
        
        $found_query = "SELECT u.* , ud.* , s.* , pul.email as parent_email , pul.password as parent_password , pul.is_password_updated as is_parent_password_updated FROM ".get_school_db().".student s
        
                        join ".get_system_db().".user_login_details ud on (ud.user_login_detail_id = s.user_login_detail_id)
                        join ".get_system_db().".user_login u on (u.user_login_id = ud.user_login_id)
                        left join ".get_school_db().".student_parent sp on (sp.s_p_id = s.parent_id)
                        left join ".get_system_db().".user_login_details spuld on (spuld.user_login_detail_id = sp.	user_login_detail_id)
                        left join ".get_system_db().".user_login pul on (pul.user_login_id = spuld.user_login_id)
                        where ".$filter." s.school_id = ".$_SESSION['school_id']." and s.student_status in (".student_query_status().") 
                        and ud.login_type = 6 and s.is_login_created = 1  and s.id_no <> '' and ud.sys_sch_id = ".$_SESSION['school_id']." ";
        $found_credentials         =    $this->db->query($found_query)->result_array();
        
		$page_data['section_id']    =   $section_id;
        $page_data['result']		=	$found_credentials;
        $page_data['page_title'] = get_phrase('student_credentials_pdf_report');

        $this->load->library('Pdf');
        $view = 'backend/admin/student_credential_list_pdf';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        
    }
    
    function student_parent_credentials_send()
    {
        $this->load->helper('message');
        $school_id = $_SESSION['school_id'];
        // Parents Data Get
        $std_ids = $_POST['student_ids_credentials'];
        
        // Query Get Records Student & Parent
        $found_query = "SELECT u.id_no,u.is_password_updated as is_student_password_updated,u_parent.is_password_updated as is_parent_password_updated,u_parent.email as parent_email,sp.user_login_detail_id as parent_user_login_detail_id, s.name,s.mob_num FROM ".get_school_db().".student s
                        join ".get_system_db().".user_login_details ud on (ud.user_login_detail_id = s.user_login_detail_id)
                        join ".get_system_db().".user_login u on (u.user_login_id = ud.user_login_id)
                        left join ".get_school_db().".student_parent sp on (sp.s_p_id = s.parent_id)
                        left join ".get_system_db().".user_login_details ud_parent on (ud_parent.user_login_detail_id = sp.user_login_detail_id)
                        left join ".get_system_db().".user_login u_parent on (u_parent.user_login_id = ud_parent.user_login_id)
                        where s.school_id = '$school_id' and s.student_status in (".student_query_status().") and ud.login_type = 6 and s.is_login_created = 1
                        and s.id_no <> '' and ud.sys_sch_id = '$school_id' and s.student_id IN($std_ids)";
        $query = $this->db->query($found_query)->result_array();
        
        foreach($query as $data):
            
            $message1 = '';
            $password = 123456;
            
            if($data['is_parent_password_updated'] == 0 && $data['parent_email'] != "" )
            {
                // Sms Message For Parent
                $message1 .= "Parent Credential: \n Email: ".$data['parent_email']." \n Password: ".$password." \n please login using this link: https://indiciedu.com.pk/login Kind Regards, indici-edu \r\n \r\n"; 
            }
            
            if($data['is_student_password_updated'] == 0 && $data['id_no'] != "")
            {
                // Sms Message For Studnet 
                $message1 .= "Student Credential: \n ID NO: ".$data['id_no']." \n Password: ".$password." \n please login using this link: https://indiciedu.com.pk/login Kind Regards, indici-edu"; 
            }
            
            // Sms Helper    
            send_sms($data['mob_num'], 'INDICI EDU',$message1 ,0,1);
        endforeach;
        
        $this->session->set_flashdata('club_updated', get_phrase('message_sent_successfully'));
        redirect(base_url() . 'reports/student_credential_list');
    }
    
    function student_parent_credentials_excel()
    {
        
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Date Wise Attendance Summary');
        $this->load->database();
        
        $section_id = $this->input->post('section_id');
       
        if(isset($section_id) && $section_id != "")
        {
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];
            
            if ($prefix =='d')
            {
                $section_id               = $value;
                $department_section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                inner join ".get_school_db().".class c on cs.class_id = c.class_id
                inner join ".get_school_db().".departments d on d.departments_id = c.departments_id
                where d.school_id= ".$_SESSION['school_id']." and d.departments_id = ".$section_id." ";
                $sections = $this->db->query($department_section_query)->result_array();
            }
            
            if ($prefix =='c')
            {
                $section_id = $value;
                $class_section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                inner join ".get_school_db().".class c on cs.class_id = c.class_id
                where c.school_id= ".$_SESSION['school_id']." and c.class_id = ".$section_id." ";
                $sections = $this->db->query($class_section_query)->result_array();
            }

            if ($prefix =='s')
            {
                $section_id    = $value;
                $section_query = "SELECT GROUP_CONCAT(DISTINCT cs.section_id) as sections FROM ".get_school_db().".class_section cs
                where school_id= ".$_SESSION['school_id']." and section_id = ".$section_id." ";
                $sections = $this->db->query($section_query)->result_array();
            }
        }
        
        $filter = "";
        if(isset($sections[0]['sections']) && $sections[0]['sections'] != "")
        {
           $filter   = " s.section_id in (".$sections[0]['sections'].") and ";
        }
        
        $found_query = "SELECT u.* , ud.* , s.* , pul.email as parent_email , pul.password as parent_password , pul.is_password_updated as is_parent_password_updated FROM ".get_school_db().".student s
        
                        join ".get_system_db().".user_login_details ud on (ud.user_login_detail_id = s.user_login_detail_id)
                        join ".get_system_db().".user_login u on (u.user_login_id = ud.user_login_id)
                        left join ".get_school_db().".student_parent sp on (sp.s_p_id = s.parent_id)
                        left join ".get_system_db().".user_login_details spuld on (spuld.user_login_detail_id = sp.	user_login_detail_id)
                        left join ".get_system_db().".user_login pul on (pul.user_login_id = spuld.user_login_id)
                        where ".$filter." s.school_id = ".$_SESSION['school_id']." and s.student_status in (".student_query_status().") and ud.login_type = 6 and s.is_login_created = 1 
                        and s.id_no <> '' and ud.sys_sch_id = ".$_SESSION['school_id']." ";
        $result = $this->db->query($found_query)->result_array();
        
        $titles_arr = array();
        $unpaid_arr = array();

        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $unpaid_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $unpaid_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        $unpaid_arr[] = 'Students/Parents Credentials Report';
        $unpaid_arr[] = "";
        
        $unpaid_arr[] = array('Sr.','Student Name','Class-section #','Std Username','Std Password','Parent Username','Parent Password');
        foreach ($result as $key => $value)
        {
            $section_details = section_hierarchy($value['section_id'] , $value['school_id']);
            $count++;
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['name'];
            $record_arr[] = $section_details['d']." - ".$section_details['c']." - ".$section_details['s'];
            $record_arr[] = $value['id_no'];
            $record_arr[] = ($value['is_password_updated'] == 1) ? "Password Updated" : "123456" ;
            $record_arr[] = ($value['parent_id'] > 0) ? $value['parent_email'] : "";
            $record_arr[] = ($value['parent_id'] > 0 && $value['is_parent_password_updated'] == 1) ? "Password Updated" : "123456" ; 
            
            $unpaid_arr[] = $record_arr;
        }
        
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());

        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:J1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $empty_rows = 5;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':J'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        
        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='Student/Parent Credentials Report.xls';
        header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"'); 
        header('Cache-Control: max-age=0'); 

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        $objWriter->save('php://output');

    }
    
    function class_wise_student_summary()
    {
        $studuent_str = "select count(s.student_id) as section_count, d.departments_id , d.title as department_name , cs.title as section_name, cs.section_id ,c.name 
        as class_name, c.class_id,d.departments_id from ".get_school_db().".departments d 
        inner join ".get_school_db().".class c on c.departments_id=d.departments_id
        inner join ".get_school_db().".class_section  cs on cs.class_id=c.class_id
        inner join ".get_school_db().".student s on s.section_id=cs.section_id
        inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id=s.academic_year_id 
        where s.student_status in (".student_query_status().") and s.school_id= ".$_SESSION['school_id']." group by cs.section_id";
        
        $student_count=$this->db->query($studuent_str)->result_array();
        
        $page_data['student_count']         = $student_count;
        $page_data['page_name']             = 'class_wise_student_summary';
        $page_data['page_title']            = 'class_wise_student_summary';
        $this->load->view('backend/index', $page_data);
    }
    
    function student_withdrawl_summary()
    {
        $start_date = date_slash($this->input->post('start_date'));
        $end_date = date_slash($this->input->post('end_date'));
        $apply_filter = $this->input->post('apply_filter');
        $start_date1 = date('Y-m-d',strtotime($start_date));
        $end_date1 = date('Y-m-d',strtotime($end_date));

        $studuent_str = "SELECT s.name ,s.address ,s.section_id , s.roll , s.student_status , s.adm_date ,s.std_withdarwal_reason, s.mob_num , sw.request_date , sw.confirm_date ,sw.status as sw_status FROM ".get_school_db().".student s 
        inner join ".get_school_db().".student_withdrawal sw on s.student_id = sw.student_id WHERE s.school_id = ".$_SESSION['school_id']." and s.student_status = 25 and DATE_FORMAT(sw.confirm_date, '%Y-%m-%d') BETWEEN '$start_date1' AND '$end_date1'";
        $student_withdrawl  =   $this->db->query($studuent_str)->result_array();
        
        $page_data['apply_filter'] = $apply_filter;
        $page_data['start_date'] = $this->input->post('start_date');
        $page_data['end_date'] = $this->input->post('end_date');
        $page_data['student_withdrawl']         = $student_withdrawl;
        $page_data['page_name']             = 'student_withdrawl_summary';
        $page_data['page_title']            = 'student_withdrawl_summary';
        $this->load->view('backend/index', $page_data);
    }
    
    function student_withdrawl_pdf()
    {
        $apply_filter = $this->input->post('apply_filter');
        $start_date1 = date('Y-m-d',strtotime($this->input->post('start_date')));
        $end_date1 = date('Y-m-d',strtotime($this->input->post('end_date')));
        
        $studuent_str = "SELECT s.name ,s.address ,s.section_id , s.roll , s.student_status , s.adm_date , s.mob_num , sw.request_date , sw.confirm_date ,sw.status as sw_status FROM ".get_school_db().".student s 
        inner join ".get_school_db().".student_withdrawal sw on s.student_id = sw.student_id WHERE s.school_id = ".$_SESSION['school_id']." and s.student_status = 21 and sw.confirm_date BETWEEN '$start_date1' AND '$end_date1'";
        $student_withdrawl  =   $this->db->query($studuent_str)->result_array();
        
        $page_data['start_date'] = $this->input->post('start_date');
        $page_data['end_date'] = $this->input->post('end_date');
        $page_data['student_withdrawl'] = $student_withdrawl;
        $page_data['page_name']           = 'student_withdrawl_pdf';
        $page_data['page_title']          = get_phrase('student_withdrawl_pdf');

        $this->load->library('Pdf');
        $view = 'backend/admin/student_withdrawl_pdf';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    
    function student_withdrawl_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Student Withdrawl Report');
        $this->load->database();
        $unpaid_arr = array();
        
        $apply_filter = $this->input->post('apply_filter');
        $start_date1 = date('Y-m-d',strtotime($this->input->post('start_date')));
        $end_date1 = date('Y-m-d',strtotime($this->input->post('end_date')));
        
        $studuent_str = "SELECT s.name ,s.address ,s.section_id , s.roll , s.student_status , s.adm_date , s.mob_num , sw.request_date , sw.confirm_date ,sw.status as sw_status FROM ".get_school_db().".student s 
        inner join ".get_school_db().".student_withdrawal sw on s.student_id = sw.student_id WHERE s.school_id = ".$_SESSION['school_id']." and s.student_status = 21 and sw.confirm_date BETWEEN '$start_date1' AND '$end_date1'";
        $student_withdrawl  =   $this->db->query($studuent_str)->result_array();
        
        $logo_path="";
        $school_name = "";
        if (empty($school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $unpaid_arr[] = system_path($_SESSION['school_logo']);
        }else{
            $school_details = get_school_details($school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $unpaid_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        $unpaid_arr[] = $get_title;
        $unpaid_arr[] = "";

        $unpaid_arr[] = array('Sr.','Student Name','Class / Section','Admission Date', 'Request Date', 'Withdraw Date', 'Reason');
        foreach ($student_withdrawl as $key => $value)
        {
            $count++;
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['name'];
            $record_arr[] = $value['class']. ' ' . $value['section'];
            $record_arr[] = date_slash($value['adm_date']);
            $record_arr[] = date_slash($value['request_date']);
            $record_arr[] = date_slash($value['confirm_date']);
            $record_arr[] = '';

            $unpaid_arr[] = $record_arr;
        }
        
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $path = $logo_path;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($path);
       // $objDrawing->setCoordinates('A1');
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(60);   
        //setOffsetX works properly
        $objDrawing->setOffsetX(5); 
        $objDrawing->setOffsetY(5);                
        //set width, height
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:G1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('H1:X1');
        $this->excel->getActiveSheet()->mergeCells('A2:G2');
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('H2:X2');
        $this->excel->getActiveSheet()->mergeCells('A3:G3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('H3:X3');

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':G'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, "Section Wise Admission");
            $this->excel->getActiveSheet()->getStyle('A'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->mergeCells('H'.$row_no.':X'.$row_no);
        }

        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='Student Withdrawl Report.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');   
    }
    
    function breakeven_report()
    {
        $filter = $this->input->post("apply_filter");
        if($filter == 1){
            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");
            
            $revenue = $this->get_coa_ids(get_parent_head_coa_id('income_stmt_sales'));
            
            
            $revenue_query = $this->db->query("SELECT SUM(credit) AS TotalCVRevenue, SUM(debit) AS TotalDVRevenue FROM ".get_school_db().".journal_entry WHERE coa_id IN('$revenue') AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' ")->row();
            $total_credit_rev = intval($revenue_query->TotalCVRevenue);
            $total_debit_rev = intval($revenue_query->TotalDVRevenue);
            $data['revenue'] = $total_credit_rev-$total_debit_rev;
            
            $this->coa_ids = array();
            
            $exp_id = get_parent_head_coa_id('income_stmt_expense');
            $expanse = $this->get_coa_ids($exp_id);
            $expanse_query = $this->db->query("SELECT SUM(credit) AS TotalCExpanse, SUM(debit) AS TotalDExpanse FROM ".get_school_db().".journal_entry WHERE coa_id IN('$expanse') AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') BETWEEN '$start_date1' AND '$end_date1' ")->row();
            $total_credit_exp = intval($expanse_query->TotalCExpanse);
            $total_debit_exp = intval($expanse_query->TotalDExpanse);
            
            $data['expanse'] = $total_debit_exp-$total_credit_exp;
            $data['start_date'] = $start_date;
            $data['apply_filter'] = $filter;
            $data['end_date'] = $end_date;
        }
        
        
        $data['page_name']             = 'breakeven_report';
        $data['page_title']            = 'breakeven_report';
        $this->load->view('backend/index', $data);
    }
    
    function get_coa_ids($parent_id = 0)
    {
          $school_id=$_SESSION['school_id'];
    
          $coa_rec_str = "select sc.name as school_name , coa.* from ".get_school_db().".chart_of_accounts as coa
          Inner join ".get_school_db().".school_coa as s_coa  on s_coa.coa_id = coa.coa_id
          INNER JOIN ".get_school_db().".school as sc on sc.school_id = coa.school_id
          where coa.parent_id=$parent_id
          And s_coa.school_id = $school_id";
            
          $coa_rec=$this->db->query($coa_rec_str)->result_array();
          
    
          foreach($coa_rec as $coa)
          {
          
            $this->coa_ids[] = $coa['coa_id'];  
            $coa_rec1_str = "select coa.coa_id from ".get_school_db().".chart_of_accounts as coa Inner join ".get_school_db().".school_coa as s_coa on s_coa.coa_id = coa.coa_id
                             INNER JOIN ".get_school_db().".school as sc on sc.school_id = coa.school_id where s_coa.school_id=$school_id and coa.parent_id=".$coa['coa_id']."";
            $coa_rec1=$this->db->query($coa_rec1_str)->result_array();
            if(count($coa_rec1)>0)
            {
                $this->get_coa_ids($coa['coa_id']);
            }
    
          }
          $data = implode("','",$this->coa_ids);
          return $data;
    }
    
    function get_coa_list($parent_id=0, $start_date=0, $end_date=0)
    {
        $school_id=$_SESSION['school_id'];

        $main_coa_rec_str = "SELECT coa.* FROM ".get_school_db().".chart_of_accounts as coa
                                    INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                    WHERE coa.coa_id=$parent_id AND s_coa.school_id=$school_id";
        $main_coa_rec=$this->db->query($main_coa_rec_str)->result_array();

        foreach($main_coa_rec as $main_coa)
        {
            $this->coa_list[$main_coa['coa_id']] = $main_coa;

            $debit_credit_total =  $this->debit_credit_total($main_coa['coa_id'], $start_date, $end_date);
            $this->coa_list[$main_coa['coa_id']]['debit'] = $debit_credit_total['debit'];
            $this->coa_list[$main_coa['coa_id']]['credit'] = $debit_credit_total['credit'];

            $coa_rec_str = "SELECT coa.* FROM " . get_school_db() . ".chart_of_accounts as coa
                            INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                            WHERE coa.parent_id=$parent_id AND s_coa.school_id=$school_id";

            $coa_rec = $this->db->query($coa_rec_str)->result_array();

            foreach ($coa_rec as $coa)
            {
                $this->coa_list[$coa['coa_id']] = $coa;

                $debit_credit_total =  $this->debit_credit_total($coa['coa_id'], $start_date, $end_date);
                $this->coa_list[$coa['coa_id']]['debit'] = $debit_credit_total['debit'];
                $this->coa_list[$coa['coa_id']]['credit'] = $debit_credit_total['credit'];

                $coa_rec1_str = "SELECT coa.* from " . get_school_db() . ".chart_of_accounts as coa
                                    INNER JOIN ".get_school_db().".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
                                    WHERE s_coa.school_id=$school_id and coa.parent_id=" . $coa['coa_id'];
                $coa_rec1 = $this->db->query($coa_rec1_str)->result_array();
                if (count($coa_rec1) > 0)
                {
                    $this->get_coa_list($coa['coa_id']);
                }
            }
        }
    }
    
    function key_performance_indicators()
    {
        $page_data['page_name']             = 'key_performance_indicators';
        $page_data['page_title']            = 'key_performance_indicators';
        $this->load->view('backend/index', $page_data);
    }
    
    
   
}
