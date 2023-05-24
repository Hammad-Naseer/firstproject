<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();


class Branch_reporting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
         $this->load->helper('report');
    }
    function branch_reports_listing()
    {
        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();
        $page_data['branch_arr']= $branch_arr;
        

        $page_data['page_name']='branch_reports_listing';
        $page_data['page_title']=get_phrase('branch_reports_listing');
        $this->load->view('backend/index', $page_data);
    }
    /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|__________________Branch Counts______________________|*/
    /*|_____________________________________________________|*/
    /*|______________*********************__________________|*/
    /*|_____________________________________________________|*/
    function branches_count()
    {
        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $staff_count_qur = $this->get_staff_count_query();
        $staff_count_arr = $this->db->query($staff_count_qur)->result_array();

        $staff_count = array();
        foreach ($staff_count_arr as $key => $value)
        {
            $staff_count[$value['school_id']] = $value['total_staff'];
        }

        $student_count_qur = $this->get_student_count_query();
        $student_count_arr = $this->db->query($student_count_qur)->result_array();

        $student_count = array();
        foreach ($student_count_arr as $key => $value)
        {
            $student_count[$value['school_id']] = $value['total_students'];
        }

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $attend_date = date_dash($filter_arr['attend_date']);

        $page_data['apply_filter']=$apply_filter;
        $page_data['attend_date']=$attend_date;

        $page_data['branch_arr']=$branch_arr;
        $page_data['staff_count']=$staff_count;
        $page_data['student_count']=$student_count;
        $page_data['page_name']='branches_count_listing';
        $page_data['page_title']=get_phrase('branches_count_listing');
        $this->load->view('backend/index', $page_data);
    }
    function get_branches_count_query()
    {
        $branch_qur = "SELECT s.school_id , s.name , s.logo , s.address , s.folder_name FROM ".get_system_db().".system_school ss 
        INNER join ".get_school_db().".school s on ss.sys_sch_id = s.sys_sch_id 
        where parent_sys_sch_id = ".$_SESSION['school_id']." ";
        return $branch_qur;
    }
    function get_staff_count_query()
    {
        $filter_arr = $this->get_filters();
        $attend_date = date_dash($filter_arr['attend_date']);

        $attend_date = date_slash($attend_date);

        $attend_date_filter = "";
        if (isset($attend_date) && !empty($attend_date))
        {
            $attend_date_filter = " and joining_date <= '".$attend_date."' ";
        }

        $staff_count_qur = "SELECT count(staff_id) as total_staff , school_id FROM ".get_school_db().".staff where status = 1
        ".$attend_date_filter."  group by school_id";
        return $staff_count_qur;
    }
    function get_student_count_query()
    {
        $filter_arr = $this->get_filters();
        $attend_date = date_dash($filter_arr['attend_date']);

        $attend_date = date_slash($attend_date);

        $attend_date_filter = "";
        if (isset($attend_date) && !empty($attend_date))
        {
            $attend_date_filter = " and adm_date <= '".$attend_date."' ";
        }

        $student_count_qur = "SELECT count(student_id) as total_students , school_id FROM ".get_school_db().".student where student_status in (".student_query_status().") ".$attend_date_filter." group by school_id";
        return $student_count_qur;
    }
    function branches_count_pdf()
    {
        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $staff_count_qur = $this->get_staff_count_query();
        $staff_count_arr = $this->db->query($staff_count_qur)->result_array();

        $staff_count = array();
        foreach ($staff_count_arr as $key => $value)
        {
            $staff_count[$value['school_id']] = $value['total_staff'];
        }

        $student_count_qur = $this->get_student_count_query();
        $student_count_arr = $this->db->query($student_count_qur)->result_array();

        $student_count = array();
        foreach ($student_count_arr as $key => $value)
        {
            $student_count[$value['school_id']] = $value['total_students'];
        }

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $attend_date = date_dash($filter_arr['attend_date']);

        $page_data['apply_filter'] = $apply_filter;
        $page_data['attend_date'] = $attend_date;

        $page_data['branch_arr']=$branch_arr;
        $page_data['staff_count']=$staff_count;
        $page_data['student_count']=$student_count;
        $page_data['page_name']='branches_count_listing_print';
        $page_data['page_title']=get_phrase('branches_count_listing_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('pdf');
        $view = 'backend/admin/branches_count_listing_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
    function branches_count_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Branches Report');
        $this->load->database();

        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $staff_count_qur = $this->get_staff_count_query();
        $staff_count_arr = $this->db->query($staff_count_qur)->result_array();

        $staff_count = array();
        foreach ($staff_count_arr as $key => $value)
        {
            $staff_count[$value['school_id']] = $value['total_staff'];
        }

        $student_count_qur = $this->get_student_count_query();
        $student_count_arr = $this->db->query($student_count_qur)->result_array();

        $student_count = array();
        foreach ($student_count_arr as $key => $value)
        {
            $student_count[$value['school_id']] = $value['total_students'];
        }
        
        $count =0;
        $c = 0;
        $total_schools =0;
        $total_students =0;
        $total_staff =0;

        $titles_arr = array();
        $array_to_display = array();

        $array_to_display[] = system_path($_SESSION['school_logo']);
        $array_to_display[] = 'Branches Report';
        $array_to_display[] = "";

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $attend_date = date_dash($filter_arr['attend_date']);

        if (isset($attend_date) && !empty($attend_date))
        {
            $array_to_display[] = date_view($attend_date);
            $c++;
            $titles_arr[$c] = "Date: ".$attend_date;
        }
        
        $array_to_display[] = array('Sr.','Name', 'Address', 'Student Count', 'Staff Count');
        foreach ($branch_arr as $key => $value)
        {
            $count++;
            $total_schools++;
            $record_arr = array();
            $record_arr[] = $count;
            $record_arr[] = $value['name'];
            $record_arr[] = $value['address'];

            if (($student_count[$value['school_id']]) != 0)
            {
                $total_students += $student_count[$value['school_id']];
                $record_arr[] = $student_count[$value['school_id']];
            }
            else
            {
                $record_arr[] = "0";
            }

            if (($staff_count[$value['school_id']]) != 0)
            {
                $total_staff += $staff_count[$value['school_id']];
                $record_arr[] = $staff_count[$value['school_id']];
            }
            else
            {
                $record_arr[] ="0";
            }

            $array_to_display[] = $record_arr;
        }
        
        //______________set logo_______________
        $this->excel->getActiveSheet()->mergeCells('A1:B1');
        $path = system_path($_SESSION['school_logo']);
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
        $school_name = $_SESSION['school_name'];
        $this->excel->getActiveSheet()->mergeCells('C1:E1');
        $this->excel->getActiveSheet()->setCellValue('C1',$school_name);
        $this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________set Heading_______________

        $this->excel->getActiveSheet()->mergeCells('A2:E2');
        //$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        //$this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $this->excel->getActiveSheet()->setCellValue('A2', $array_to_display[1]);
        //$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setSize(20);
        //$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //____________heading end__________________

        //____________Date Start___________________
        $this->excel->getActiveSheet()->mergeCells('A3:E3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        //____________Date end_____________________

        // //____________set filter___________________
        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':E'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        // //____________End filter__________________

        //____________set total___________________
        $count = $count+5;
        $this->excel->getActiveSheet()->setCellValue('B'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('C'.$count.'',$total_schools);
        $this->excel->getActiveSheet()->setCellValue('D'.$count.'',$total_students);
        $this->excel->getActiveSheet()->setCellValue('E'.$count.'',$total_staff);
        //____________End total__________________
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($array_to_display);
        $filename='Branches Report.xls';
        //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|_______________Branch staff attend___________________|*/
    /*|_____________________________________________________|*/
    /*|______________*********************__________________|*/
    /*|_____________________________________________________|*/
    function br_staff_attendance()
    {
        $br_staff_attend_qur = $this->get_br_staff_attendance_query();
        $br_staff_attend_arr = $this->db->query($br_staff_attend_qur)->result_array();
        $br_staff_attend = array();
        foreach ($br_staff_attend_arr as $key => $value)
        {
            $br_staff_attend[$value['school_id']][$value['status']] = $value['total'];
        }

        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $staff_count_qur = $this->get_staff_count_query();
        $staff_count_arr = $this->db->query($staff_count_qur)->result_array();

        $staff_count = array();
        foreach ($staff_count_arr as $key => $value)
        {
            $staff_count[$value['school_id']] = $value['total_staff'];
        }

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $attend_date = date_dash($filter_arr['attend_date']);

        $page_data['apply_filter'] = $apply_filter;
        $page_data['attend_date'] = $attend_date;
        $page_data['br_staff_attend']=$br_staff_attend;
        $page_data['branch_arr']=$branch_arr;
        $page_data['staff_count']=$staff_count;
        $page_data['page_name']='br_staff_attendance';
        $page_data['page_title']=get_phrase('br_staff_attendance');
        $this->load->view('backend/index', $page_data);
    }
    function get_br_staff_attendance_query()
    {
        $filter_arr = $this->get_filters();
        $attend_date = $filter_arr['attend_date'];

        $attend_date_filter = "";
        if (isset($attend_date) && !empty($attend_date))
        {
            $attend_date_filter = " and attend.date = '".$attend_date."'";
        }

        $br_staff_attend_qur = "select count(s.staff_id) as total , attend.status , attend.school_id from ".get_school_db().".staff s
        INNER join ".get_school_db().".attendance_staff attend on s.staff_id = attend.staff_id
        where s.status = 1 ".$attend_date_filter."
         group by attend.status , attend.school_id";

        return $br_staff_attend_qur;
    }
    function br_staff_attendance_pdf()
    {
        $br_staff_attend_qur = $this->get_br_staff_attendance_query();
        $br_staff_attend_arr = $this->db->query($br_staff_attend_qur)->result_array();
        $br_staff_attend = array();
        foreach ($br_staff_attend_arr as $key => $value)
        {
            $br_staff_attend[$value['school_id']][$value['status']] = $value['total'];
        }

        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $staff_count_qur = $this->get_staff_count_query();
        $staff_count_arr = $this->db->query($staff_count_qur)->result_array();

        $staff_count = array();
        foreach ($staff_count_arr as $key => $value)
        {
            $staff_count[$value['school_id']] = $value['total_staff'];
        }

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $attend_date = date_dash($filter_arr['attend_date']);

        $page_data['apply_filter'] = $apply_filter;
        $page_data['attend_date'] = $attend_date;
        $page_data['br_staff_attend']=$br_staff_attend;
        $page_data['branch_arr']=$branch_arr;
        $page_data['staff_count']=$staff_count;

        $page_data['page_name']='br_staff_attendance_print';
        $page_data['page_title']=get_phrase('br_staff_attendance_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('pdf');
        $view = 'backend/admin/br_staff_attendance_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
    function br_staff_attendance_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Branches Staff Report');
        $this->load->database();

        $br_staff_attend_qur = $this->get_br_staff_attendance_query();
        $br_staff_attend_arr = $this->db->query($br_staff_attend_qur)->result_array();
        $br_staff_attend = array();
        foreach ($br_staff_attend_arr as $key => $value)
        {
            $br_staff_attend[$value['school_id']][$value['status']] = $value['total'];
        }

        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $staff_count_qur = $this->get_staff_count_query();
        $staff_count_arr = $this->db->query($staff_count_qur)->result_array();

        $staff_count = array();
        foreach ($staff_count_arr as $key => $value)
        {
            $staff_count[$value['school_id']] = $value['total_staff'];
        }

        $filter_arr = $this->get_filters();
        $attend_date = date_dash($filter_arr['attend_date']);

        $count =0;
        $c = 0;
        $total_schools =0;
        $total_staff =0;
        $total_present=0;
        $total_absent=0;
        $total_leave=0;

        $titles_arr = array();
        $array_to_display = array();

        $array_to_display[] = system_path($_SESSION['school_logo']);
        $array_to_display[] = 'Branches Report';
        $array_to_display[] = "";

        if (isset($attend_date) && !empty($attend_date))
        {
            $array_to_display[] = date_view($attend_date);
            $c++;
            $titles_arr[$c] = "Date: ".$attend_date;
        }

        
        $array_to_display[] = array('Sr.','Name', 'Address', 'Staff Count','Present','Absent','Leave');
        foreach ($branch_arr as $key => $value)
        {
            $count++;
            $total_schools++;
            $record_arr = array();
            $record_arr[] = $count;
            $record_arr[] = $value['name'];
            $record_arr[] = $value['address'];

            if (($staff_count[$value['school_id']]) != 0)
            {
                $total_staff += $staff_count[$value['school_id']];
                $record_arr[] = $staff_count[$value['school_id']];
            }
            else
            {
                $record_arr[] ="0";
            }

            $total_present += $br_staff_attend[$value['school_id']][1];
            $record_arr[] = $br_staff_attend[$value['school_id']][1];

            $total_absent += $br_staff_attend[$value['school_id']][2];
            $record_arr[] = $br_staff_attend[$value['school_id']][2];

            $total_leave += $br_staff_attend[$value['school_id']][3];
            $record_arr[] = $br_staff_attend[$value['school_id']][3];

            $array_to_display[] = $record_arr;
        }
        
        //______________set logo_______________
        $this->excel->getActiveSheet()->mergeCells('A1:B1');
        $path = system_path($_SESSION['school_logo']);
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
        $school_name = $_SESSION['school_name'];
        $this->excel->getActiveSheet()->mergeCells('C1:G1');
        $this->excel->getActiveSheet()->setCellValue('C1',$school_name);
        $this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________set Heading_______________

        $this->excel->getActiveSheet()->mergeCells('A2:G2');
        //$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        //$this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $this->excel->getActiveSheet()->setCellValue('A2', $array_to_display[1]);
        //$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setSize(20);
        //$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //____________heading end__________________

        //____________Date Start___________________
        $this->excel->getActiveSheet()->mergeCells('A3:G3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        //____________Date end_____________________

        // //____________set filter___________________
        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':G'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        // //____________End filter__________________

        //____________set total___________________
        $count = $count+5+$c;
        $this->excel->getActiveSheet()->setCellValue('B'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('C'.$count.'',$total_schools);
        $this->excel->getActiveSheet()->setCellValue('D'.$count.'',$total_staff);
        $this->excel->getActiveSheet()->setCellValue('E'.$count.'',$total_present);
        $this->excel->getActiveSheet()->setCellValue('F'.$count.'',$total_absent);
        $this->excel->getActiveSheet()->setCellValue('G'.$count.'',$total_leave);
        //____________End total__________________
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($array_to_display);
        $filename='Branches Staff Report.xls';
        //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|_______________Branch student attend_________________|*/
    /*|_____________________________________________________|*/
    /*|______________*********************__________________|*/
    /*|_____________________________________________________|*/
    function br_studnets_attendance()
    {
        $br_student_attend_qur = $this->get_br_students_attendance_query();
        $br_student_attend_arr = $this->db->query($br_student_attend_qur)->result_array();
        $br_students_attend = array();
        foreach ($br_student_attend_arr as $key => $value)
        {
            $br_students_attend[$value['school_id']][$value['status']] = $value['total'];
        }

        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $student_count_qur = $this->get_student_count_query();
        $student_count_arr = $this->db->query($student_count_qur)->result_array();

        $student_count = array();
        foreach ($student_count_arr as $key => $value)
        {
            $student_count[$value['school_id']] = $value['total_students'];
        }

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $attend_date = date_dash($filter_arr['attend_date']);

        $page_data['apply_filter'] = $apply_filter;
        $page_data['attend_date'] = $attend_date;
        $page_data['br_students_attend']=$br_students_attend;
        $page_data['branch_arr']=$branch_arr;
        $page_data['student_count']=$student_count;
        $page_data['page_name']='br_students_attendance';
        $page_data['page_title']=get_phrase('br_students_attendance');
        $this->load->view('backend/index', $page_data);
    }
    function get_br_students_attendance_query()
    {
        $filter_arr = $this->get_filters();
        $attend_date = $filter_arr['attend_date'];

        $attend_date_filter = "";
        if (isset($attend_date) && !empty($attend_date))
        {
            $attend_date_filter = " and attend.date = '".$attend_date."'";
        }

        $br_student_attend_qur = "select count(s.student_id) as total ,
         attend.status , attend.school_id from ".get_school_db().".student s 
         INNER join ".get_school_db().".attendance attend on
         s.student_id = attend.student_id 
         where s.student_status in (".student_query_status().")
         ".$attend_date_filter." group by attend.status , attend.school_id";

        return $br_student_attend_qur;
    }
    function br_student_attendance_pdf()
    {
        $br_student_attend_qur = $this->get_br_students_attendance_query();
        $br_student_attend_arr = $this->db->query($br_student_attend_qur)->result_array();
        $br_students_attend = array();
        foreach ($br_student_attend_arr as $key => $value)
        {
            $br_students_attend[$value['school_id']][$value['status']] = $value['total'];
        }

        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $student_count_qur = $this->get_student_count_query();
        $student_count_arr = $this->db->query($student_count_qur)->result_array();

        $student_count = array();
        foreach ($student_count_arr as $key => $value)
        {
            $student_count[$value['school_id']] = $value['total_students'];
        }

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $attend_date = date_dash($filter_arr['attend_date']);

        $page_data['apply_filter'] = $apply_filter;
        $page_data['attend_date'] = $attend_date;
        $page_data['br_students_attend']=$br_students_attend;
        $page_data['branch_arr']=$branch_arr;
        $page_data['student_count']=$student_count;

        $page_data['page_name']='br_students_attendance_print';
        $page_data['page_title']=get_phrase('br_students_attendance_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('pdf');
        $view = 'backend/admin/br_students_attendance_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
    function br_student_attendance_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Branches Students Report');
        $this->load->database();

        $br_student_attend_qur = $this->get_br_students_attendance_query();
        $br_student_attend_arr = $this->db->query($br_student_attend_qur)->result_array();
        $br_students_attend = array();
        foreach ($br_student_attend_arr as $key => $value)
        {
            $br_students_attend[$value['school_id']][$value['status']] = $value['total'];
        }

        $branch_qur = $this->get_branches_count_query();
        $branch_arr = $this->db->query($branch_qur)->result_array();

        $student_count_qur = $this->get_student_count_query();
        $student_count_arr = $this->db->query($student_count_qur)->result_array();

        $student_count = array();
        foreach ($student_count_arr as $key => $value)
        {
            $student_count[$value['school_id']] = $value['total_students'];
        }

        $filter_arr = $this->get_filters();
        $attend_date = date_dash($filter_arr['attend_date']);

        $count =0;
        $c = 0;
        $total_schools =0;
        $total_students =0;
        $total_present=0;
        $total_absent=0;
        $total_leave=0;

        $titles_arr = array();
        $array_to_display = array();

        $array_to_display[] = system_path($_SESSION['school_logo']);
        $array_to_display[] = 'Branches Students Report';
        $array_to_display[] = "";

        if (isset($attend_date) && !empty($attend_date))
        {
            $array_to_display[] = date_view($attend_date);
            $c++;
            $titles_arr[$c] = "Date: ".$attend_date;
        }

        $array_to_display[] = array('Sr.','Name', 'Address', 'Students Count','Present','Absent','Leave');
        foreach ($branch_arr as $key => $value)
        {
            $count++;
            $total_schools++;
            $record_arr = array();
            $record_arr[] = $count;
            $record_arr[] = $value['name'];
            $record_arr[] = $value['address'];

            if (($student_count[$value['school_id']]) != 0)
            {
                $total_students += $student_count[$value['school_id']];
                $record_arr[] = $student_count[$value['school_id']];
            }
            else
            {
                $record_arr[] ="0";
            }

            $total_present += $br_students_attend[$value['school_id']][1];
            $record_arr[] = $br_students_attend[$value['school_id']][1];

            $total_absent += $br_students_attend[$value['school_id']][2];
            $record_arr[] = $br_students_attend[$value['school_id']][2];

            $total_leave += $br_students_attend[$value['school_id']][3];
            $record_arr[] = $br_students_attend[$value['school_id']][3];

            $array_to_display[] = $record_arr;
        }
        
        //______________set logo_______________
        $this->excel->getActiveSheet()->mergeCells('A1:B1');
        $path = system_path($_SESSION['school_logo']);
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
        $school_name = $_SESSION['school_name'];
        $this->excel->getActiveSheet()->mergeCells('C1:G1');
        $this->excel->getActiveSheet()->setCellValue('C1',$school_name);
        $this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________set Heading_______________

        $this->excel->getActiveSheet()->mergeCells('A2:G2');
        //$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        //$this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $this->excel->getActiveSheet()->setCellValue('A2', $array_to_display[1]);
        //$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setSize(20);
        //$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //____________heading end__________________

        //____________Date Start___________________
        $this->excel->getActiveSheet()->mergeCells('A3:G3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        //____________Date end_____________________

        // //____________set filter___________________
        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':G'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        // //____________End filter__________________

        //____________set total___________________
        $count = $count+5+$c;
        $this->excel->getActiveSheet()->setCellValue('B'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('C'.$count.'',$total_schools);
        $this->excel->getActiveSheet()->setCellValue('D'.$count.'',$total_students);
        $this->excel->getActiveSheet()->setCellValue('E'.$count.'',$total_present);
        $this->excel->getActiveSheet()->setCellValue('F'.$count.'',$total_absent);
        $this->excel->getActiveSheet()->setCellValue('G'.$count.'',$total_leave);
        //____________End total__________________
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($array_to_display);
        $filename='Branches Students Report.xls';
        //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    /*___________function that returns filters array__________*/
    function get_filters()
    {
        $apply_filter = $this->input->post('apply_filter', TRUE);

        $attend_date = "";
        $temp_start = $this->input->post('attend_date' ,TRUE);
        if (empty($temp_start))
        {
            $attend_date = date("Y-m-d");
        }
        elseif (isset($temp_start ) && !empty($temp_start)) {
            $attend_date = date_slash($temp_start);
        }

        $filter_arr = array();
        $filter_arr['apply_filter'] = $apply_filter;
        $filter_arr['attend_date']  = $attend_date;
        $filter_arr['start_date_subjectwise'] = $this->input->post('start_date_subjectwise' ,TRUE);
        $filter_arr['end_date_subjectwise']   = $this->input->post('end_date_subjectwise' ,TRUE);
        $filter_arr['section_id_subjectwise'] = $this->input->post('section_id_subjectwise' ,TRUE);
        return $filter_arr;
    }
    
    
    /////////////////////////// 
    // Branch Student Fee Reports //
    /////////////////////////
    
    
    /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|_____________UnPaid Students Reports_________________|*/
    /*|_____________________________________________________|*/
    /*|______________*********************__________________|*/
    /*|_____________________________________________________|*/
    function unpaid_students()
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
        $unpaid_std_qur = $this->get_unpaid_student_query();
        $unpaid_std_arr = $this->get_query_result_in_array($unpaid_std_qur);
    
        $discount_amount_arr= $this->get_discount();

        $d_school_id = $this->input->post('d_school_id');
        $page_data['d_school_id'] = $d_school_id;
        $page_data['unpaid_std_arr'] = $unpaid_std_arr;
        $page_data['discount_amount_arr'] = $discount_amount_arr;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['page_name']  = 'unpaid_students';
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
        

        //_________________filter starts________________________

                //_______Search filter____________

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
                 where school_id=".$_POST['d_school_id']." and class_id=".$class_id."")->result_array();

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
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_POST['d_school_id']."";
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
        $d_school_id = $this->input->post('d_school_id');
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
        return $unpaid_std_qur ="select scf.s_c_f_id, scf.student_id, scf.student_name, scf.roll , scf.section_id , scf.image, scf.mob_num, scf.reg_num, scf.location_id, scf.system_id, scf.bar_code, scf.due_date, scf.issue_date, scf.fee_month_year,scf.chalan_form_number, scf.actual_amount, scf.father_name, scf.section, scf.class, scf.department 
            from ".get_school_db().".student_chalan_form scf
            where scf.is_processed=0 and scf.status=4 and
            scf.is_cancelled=0 and scf.school_id=".$d_school_id." ".$std_query." ".$section_filter." ".$student_filter." ".$date_filter." order by scf.actual_amount desc";
    }
    /*______________unpaid students pdf_____________________*/
    function print_pdf()
    {
        $unpaid_std_qur = $this->get_unpaid_student_query();
        $unpaid_std_arr = $this->get_query_result_in_array($unpaid_std_qur);
        $discount_amount_arr= $this->get_discount();
        $page_data['discount_amount_arr'] = $discount_amount_arr;
        $page_data['unpaid_std_arr'] = $unpaid_std_arr;

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
        $page_data['page_name']  = 'unpaid_students_print';
        $page_data['page_title'] = get_phrase('unpaid_students_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('pdf');
        $view = 'backend/admin/unpaid_students_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
        //$this->load->view('backend/index', $page_data);
    }
    /*_____________unpaid Students excel___________________*/
    function database_to_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Unpaid Student Report');
        $this->load->database();
        $unpaid_std_qur = $this->get_unpaid_student_query();
        $unpaid_std_arr=$this->db->query($unpaid_std_qur)->result_array();
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
            
            // $scl_name=$this->db->query("select * from ".get_school_db().".school where school_id=$d_school_id")->result_array();
            // if(count($scl_name)>0)
            // {
            //   $branch_name =  $scl_name[0]['name'];
            //   $branch_logo =  $scl_name[0]['logo'];
            //   $branch_folder =  $scl_name[0]['folder_name'];
            // }

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
        
        $unpaid_arr[] = array('Sr.','Student Name','Reg No', 'Class', 'Section', 'Chalan Form #', 'Issue Date', 'Due Date', 'Amount','Discount');
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
            $record_arr[] = date_view($value['issue_date']);
            $record_arr[] = date_view($value['due_date']);
            $record_arr[] = $value['actual_amount'];
            $record_arr[] = $discount_amount_arr[$value['s_c_f_id']];

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
        //____________set Heading_______________

        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        //$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        //$this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[1]);
        //$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setSize(20);
        //$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________heading end__________________

        //____________Date Start___________________
        $this->excel->getActiveSheet()->mergeCells('A3:J3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        //____________Date end_____________________

        //____________set filter___________________
        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':J'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        //____________End filter__________________

        //____________set total___________________
        $count = $count+$c+5;
        $this->excel->getActiveSheet()->setCellValue('H'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('I'.$count.'',$total);
        $this->excel->getActiveSheet()->setCellValue('J'.$count.'',$discount);
        //____________End total__________________
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='Unpaid Students.xls';
        //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    
        /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|_____________Paid Students Reports___________________|*/
    /*|_____________________________________________________|*/
    /*|_____________*********************___________________|*/
    /*|_____________________________________________________|*/

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

        $d_school_id = $this->input->post('d_school_id');

        $page_data['d_school_id'] = $d_school_id;
        $page_data['discount_amount_arr'] = $discount_amount_arr;
        $page_data['paid_std_arr'] = $paid_std_arr;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['page_name']  = 'paid_students';
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
        //_________________filter starts________________________
                //_______Search filter____________
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
                 where school_id=".$_POST['d_school_id']." and class_id=".$class_id."")->result_array();

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
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_POST['d_school_id']."";
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
        //__________________Filters End_________________________

        $d_school_id = $this->input->post('d_school_id');
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_POST['d_school_id'];
        }

        return $paid_std_qur ="select scf.s_c_f_id , scf.student_id, scf.student_name,scf.roll , scf.section_id , scf.image, scf.mob_num, scf.reg_num,scf.location_id, scf.system_id,
            scf.bar_code,scf.due_date,scf.received_date,
            scf.fee_month_year,scf.chalan_form_number,
            scf.received_amount, scf.father_name, scf.section, scf.class,
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
        $this->load->library('pdf');
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
            
            // $scl_name=$this->db->query("select * from ".get_school_db().".school where school_id=$d_school_id")->result_array();
            // if(count($scl_name)>0)
            // {
            //   $branch_name =  $scl_name[0]['name'];
            //   $branch_logo =  $scl_name[0]['logo'];
            //   $branch_folder =  $scl_name[0]['folder_name'];
            // }
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
        
        $paid_arr[] = array('Sr','Student Name','Reg No', 'Class', 'Section', 'Chalan Form #', 'Due Date','Received Date', 'Received Amount','Discount');


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
            $record_arr[] = date_view($value['due_date']);
            $record_arr[] = date_view($value['received_date']);
            $record_arr[] = $value['received_amount'];
            $record_arr[] = $discount_amount_arr[$value['s_c_f_id']];
            $paid_arr[] = $record_arr;
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
        $this->excel->getActiveSheet()->mergeCells('E1:J1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________set Heading_______________

        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        $this->excel->getActiveSheet()->setCellValue('A2', $paid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________heading end__________________

        //____________Date Start___________________
        $this->excel->getActiveSheet()->mergeCells('A3:J3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        //____________Date end_____________________

        //____________set filter___________________
        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':J'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        //____________End filter__________________

        //____________set total___________________
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


         /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|_________________Arrears Reporting___________________|*/
    /*|_____________________________________________________|*/
    /*|_________________*****************___________________|*/
    /*|_____________________________________________________|*/

    function students_arrears()
    {
        $std_arrears_qur = $this->get_students_arrears_query();
        $std_arrears_arr = $this->get_query_result_in_array($std_arrears_qur);

        $d_school_id = $this->input->post('d_school_id');
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
        if (empty($start_date)){
           $start_date =date('Y-m-01');
        }
        $end_date = $filter_arr['end_date'];
        if (empty($end_date)) {
            $end_date = date("Y-m-d");
        }
        //_________________filter starts________________________
                //_______Search filter____________
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

                $sec=$this->db->query("select section_id from " .get_school_db().".class_section
                 where school_id=".$_POST['d_school_id']." and class_id=".$class_id."")->result_array();

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
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_POST['d_school_id']."";
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

        $d_school_id = $this->input->post('d_school_id');
        $d_school_id_f = $filter_arr['d_school_id'];

        if(!empty($d_school_id))
        {
            $d_school_id = $d_school_id;
        }elseif(!empty($d_school_id_f))
        {
            $d_school_id = $d_school_id_f;
        }else
        {
            $d_school_id = $_POST['d_school_id'];
        }

        return $std_arrears_qur ="select scf.student_id, scf.student_name, scf.roll, scf.section_id, scf.image, scf.mob_num, scf.reg_num, scf.location_id, scf.system_id, scf.bar_code, scf.due_date, scf.issue_date, scf.fee_month_year, scf.chalan_form_number, scf.form_type, scf.actual_amount, scf.father_name, scf.section, scf.class, scf.department ,  scf.received_amount , scf.arrears
            from ".get_school_db().".student_chalan_form scf
            where scf.is_processed=0 and scf.status=5 
            and scf.school_id=".$d_school_id." 
            and scf.arrears>0 and scf.arrears_status = 1 
            ".$std_query." ".$section_filter." 
            ".$student_filter." ".$date_filter."
            order by scf.arrears DESC";
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
        $page_data['d_school_id'] = $d_school_id;

        $page_data['apply_filter'] = $apply_filter;
        $page_data['std_search'] = $std_search;
        $page_data['section_id'] = $section_id;
        $page_data['student_id'] = $student_id;
        $page_data['start_date'] = $start_date;
        $page_data['end_date'] = $end_date;

        $page_data['page_name']  = 'students_arrears_print';
        $page_data['page_title'] = get_phrase('students_arrears_print');
        $this->load->library('pdf');
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
            
            // $scl_name=$this->db->query("select * from ".get_school_db().".school where school_id=$d_school_id")->result_array();
            // if(count($scl_name)>0)
            // {
            //   $branch_name =  $scl_name[0]['name'];
            //   $branch_logo =  $scl_name[0]['logo'];
            //   $branch_folder =  $scl_name[0]['folder_name'];
            // }

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
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['student_name'];
            $record_arr[] = $value['reg_num'];
            $record_arr[] = $value['class'];
            $record_arr[] = $value['section'];
            $record_arr[] = $value['chalan_form_number'];
            $record_arr[] = display_class_chalan_type($value['form_type']);
            $record_arr[] = date_view($value['due_date']);
            $record_arr[] = date_view($value['received_date']);
            $record_arr[] = $value['actual_amount'];
            $record_arr[] = $value['received_amount'];
            $record_arr[] = $value['arrears'];

            $arrears_arr[] = $record_arr;
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
        //set width, height
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(60); 
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
        //____________logo end__________________
        $school_name = $school_name;
        $this->excel->getActiveSheet()->mergeCells('E1:L1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________set Heading_______________
        $this->excel->getActiveSheet()->mergeCells('A2:L2');
        $this->excel->getActiveSheet()->setCellValue('A2', $arrears_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________heading end__________________

        //____________Date Start__________________
        $this->excel->getActiveSheet()->mergeCells('A3:L3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        //____________Date end___________________

        //____________set filter___________________

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':I'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        //____________End filter__________________

        //____________set total___________________
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
    
    
        /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|_____________Section_wise_paid_fee___________________|*/
    /*|_____________________________________________________|*/
    /*|_________________*****************___________________|*/
    /*|_____________________________________________________|*/

    function section_wise_paid_fee()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $start_date = date_dash($filter_arr['start_date']);
        if (empty($start_date)){
           $start_date =date('01/m/Y');
        }
        $end_date = date_dash($filter_arr['end_date']);
        if (empty($end_date)) {
            $end_date = date("d/m/Y");
        }
        $section_id = $filter_arr['section_id'];

        $d_school_id = $this->input->post('d_school_id');

        //---------payment details__________________________
        $section_wise_paid_fee_qur = $this->get_section_wise_paid_fee_query();
        $section_wise_paid_fee_arr = $this->get_query_result_in_array($section_wise_paid_fee_qur);

        $payment_details_arr  = array();
        foreach ($section_wise_paid_fee_arr as $key => $value)
        {
           $payment_details_arr[$value['section_id']]['actual_amount']=$value['actual_amount'];
           $payment_details_arr[$value['section_id']]['received_amount']=$value['received_amount'];
           $payment_details_arr[$value['section_id']]['arrears']=$value['arrears'];
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
        $page_data['section_discount_arr'] = $section_discount_arr;

        //$page_data['section_wise_paid_fee_arr'] = $section_wise_paid_fee_arr;
        $page_data['total_details_arr'] = $total_details_arr;
        $page_data['payment_details_arr'] = $payment_details_arr;
        $page_data['arrears_details_arr'] = $arrears_details_arr;
        $page_data['unpaid_details_arr'] = $unpaid_details_arr;

        $page_data['d_school_id'] = $d_school_id;

        $page_data['apply_filter'] = $apply_filter;
        $page_data['start_date'] = $start_date;
        $page_data['end_date'] = $end_date;
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
        //_________________filter starts________________________
                    //_______Date filter____________
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
                 where school_id=".$_POST['d_school_id']." and class_id=".$class_id."")->result_array();

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
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_POST['d_school_id']."";
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
        $d_school_id = $this->input->post('d_school_id');
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
        //_________________filter starts________________________
                    //_______Date filter____________
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
                 where school_id=".$_POST['d_school_id']." and class_id=".$class_id."")->result_array();

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
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_POST['d_school_id']."";
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

        $d_school_id = $this->input->post('d_school_id');
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


        return $section_wise_paid_fee_qur = "select
        sum(scf.received_amount) as received_amount, 
        s.section_id
        from ".get_school_db().".student s 
        inner join ".get_school_db().".student_chalan_form scf on scf.student_id=s.student_id 
        where scf.is_processed=0 and scf.status=5 and scf.is_cancelled=0 and s.school_id=".$d_school_id." ".$date_filter." ".$section_filter."
        group by s.section_id";
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

        //_________________filter starts________________________
                    //_______Date filter____________
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
                 where school_id=".$_POST['d_school_id']." and class_id=".$class_id."")->result_array();

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
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_POST['d_school_id']."";
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

        $d_school_id = $this->input->post('d_school_id');
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

        return $section_wise_unpaid_fee_qur = "select
        sum(scf.actual_amount) as actual_amount, 
        s.section_id
        from ".get_school_db().".student s 
        inner join ".get_school_db().".student_chalan_form scf on scf.student_id=s.student_id 
        where scf.is_processed=0 and scf.status=4 and scf.is_cancelled=0 and s.school_id=".$d_school_id." ".$date_filter." ".$section_filter."
        group by s.section_id";
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
        //_________________filter starts________________________
                    //_______Date filter____________
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
                 where school_id=".$_POST['d_school_id']." and class_id=".$class_id."")->result_array();

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
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_POST['d_school_id']."";
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

        $d_school_id = $this->input->post('d_school_id');
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
        //_________________filter starts________________________
                    //_______Date filter____________
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
                 where school_id=".$_POST['d_school_id']." and class_id=".$class_id."")->result_array();

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
                ".get_school_db().".class_section cs on c.class_id = cs.class_id where c.departments_id=".$department_id." and c.school_id=".$_POST['d_school_id']."";
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

        $d_school_id = $this->input->post('d_school_id');
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
        $page_data['section_discount_arr'] = $section_discount_arr;

        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $start_date = $filter_arr['start_date'];
        $end_date = $filter_arr['end_date'];
        $section_id = $filter_arr['section_id'];

        $d_school_id = $filter_arr['d_school_id'];
        $page_data['d_school_id'] = $d_school_id;

        $page_data['total_details_arr'] = $total_details_arr;
        $page_data['payment_details_arr'] = $payment_details_arr;
        $page_data['arrears_details_arr'] = $arrears_details_arr;
        $page_data['unpaid_details_arr'] = $unpaid_details_arr;

        $page_data['apply_filter'] = $apply_filter;
        $page_data['start_date'] = $start_date;
        $page_data['end_date'] = $end_date;
        $page_data['section_id'] = $section_id;

        //$page_data['section_wise_paid_fee_arr'] = $section_wise_paid_fee_arr;
        $page_data['page_name']  = 'section_wise_paid_fee_print';
        $page_data['page_title'] = get_phrase('section_wise_paid_fee_print');
        //$this->load->view('backend/index', $page_data);
        $this->load->library('pdf');
        $view = 'backend/admin/section_wise_paid_fee_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*______________section wise paid excel__________________*/
    function section_wise_paid_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        // $this->excel->getActiveSheet()->setTitle('Section Wise Payment Details');
        $this->load->database();

        //---------payment details__________________________
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
            
            // $scl_name=$this->db->query("select * from ".get_school_db().".school where school_id=$d_school_id")->result_array();
            // if(count($scl_name)>0)
            // {
            //   $branch_name =  $scl_name[0]['name'];
            //   $branch_logo =  $scl_name[0]['logo'];
            //   $branch_folder =  $scl_name[0]['folder_name'];
            // }
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

        $section_paid_arr[] = array('Sr.','Department','Class','Section','Total Amount', 'Received Amount','Total Unpaid','Arrears','Discount');
        $sec_ary = department_class_section_hierarchy($section_id,$d_school_id);
        foreach ($sec_ary as $key => $value)
        {
            $count++;
            $total_actual = $total_actual+$total_details_arr[$value['s_id']]['actual_amount'];
            $total_received = $total_received + $payment_details_arr[$value['s_id']]['received_amount'];
            $total_unpaid = $total_unpaid + $unpaid_details_arr[$value['s_id']]['actual_amount'];
            $total_arrears = $total_arrears+$arrears_details_arr[$value['s_id']]['arrears'];
            $total_discount = $total_discount+array_sum($section_discount_arr[$value['s_id']]);

            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['d'];
            $record_arr[] = $value['c'];
            $record_arr[] = $value['s'];
            $record_arr[] = $total_details_arr[$value['s_id']]['actual_amount'];
            $record_arr[] = $payment_details_arr[$value['s_id']]['received_amount'];
            $record_arr[] = $unpaid_details_arr[$value['s_id']]['actual_amount'];
            $record_arr[] = $arrears_details_arr[$value['s_id']]['arrears'];
            $record_arr[] = array_sum($section_discount_arr[$value['s_id']]);

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
        //____________heading end__________________

        //____________Date Start__________________
        $this->excel->getActiveSheet()->mergeCells('A3:I3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        //____________Date end___________________

        //____________set filter___________________

        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':I'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
        }
        //____________End filter__________________

        //____________set total___________________
        $count = $count+$c+5;
        $this->excel->getActiveSheet()->setCellValue('D'.$count.'',"Total: ");
        $this->excel->getActiveSheet()->setCellValue('E'.$count.'',$total_actual);
        $this->excel->getActiveSheet()->setCellValue('F'.$count.'',$total_received);
        $this->excel->getActiveSheet()->setCellValue('G'.$count.'',$total_unpaid);
        $this->excel->getActiveSheet()->setCellValue('H'.$count.'',$total_arrears);
        $this->excel->getActiveSheet()->setCellValue('I'.$count.'',$total_discount);
        //____________End total__________________
        $this->excel->getActiveSheet()->fromArray($section_paid_arr);
        $filename='section wise paid fee.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"'); 
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    /////////////////////////// 
    // Branch Student Fee Reports //
    /////////////////////////
    
    
    ////////////////////////////
    //Branch Staff Attendance Report//
    ////////////////////////////
    
        /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|_____________Staff monthly attendence________________|*/
    /*|_____________________________________________________|*/
    /*|_________________*****************___________________|*/
    /*|_____________________________________________________|*/
    function staff_monthly_attendance()
    {
        $filter_arr = $this->get_filters();
       	$apply_filter = $filter_arr['apply_filter'];
        $month_year = $filter_arr['month_year'];
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }
        
        $staff_attend_details_arr=$this->get_staff_monthly_attendance_arr();

        $d_school_id = $this->input->post('d_school_id');
        $page_data['d_school_id'] = $d_school_id;

        $page_data['staff_attend_details_arr']=$staff_attend_details_arr;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['month_year'] = $month_year;
        $page_data['page_name']  = 'staff_monthly_attendance';
        $page_data['page_title'] =get_phrase('staff_monthly_attendance');
        $this->load->view('backend/index', $page_data);
    }
    /*__________student wise attendance array_______________*/
    function get_staff_monthly_attendance_arr()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $month_year = $filter_arr['month_year'];
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }

        $d_school_id = $this->input->post('d_school_id');
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

        $staff_attend_qur ="select s.staff_id ,s.name ,s.employee_code,
        attend.status ,attend.date
        from ".get_school_db().".staff s 
        LEFT join ".get_school_db().".attendance_staff attend on
        (s.staff_id = attend.staff_id and DATE_FORMAT(attend.date,'%m/%Y') = '".$month_year."')
        where s.school_id=".$d_school_id."
        order by s.name asc ,attend.date";

        $staff_attend_arr = $this->db->query($staff_attend_qur)->result_array();

        $staff_attend_details_arr = array();
        foreach ($staff_attend_arr as $key => $value)
        {
            $splited_date =explode('-', $value['date']);
            
            $staff_attend_details_arr[$value['staff_id']]['attend'][$splited_date[0]][intval($splited_date[1])][intval($splited_date[2])]= $value['status'];
            $staff_attend_details_arr[$value['staff_id']]['name']= $value['name'];
            $staff_attend_details_arr[$value['staff_id']]['employee_code']= $value['employee_code'];
        }
        return $staff_attend_details_arr;
    }
    /*__________student wise attendance pdf_________________*/
    function staff_monthly_attendance_pdf()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $month_year = $filter_arr['month_year'];
        $d_school_id = $filter_arr['d_school_id'];
        
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }
        $section_id = $filter_arr['section_id'];

        $staff_attend_details_arr=$this->get_staff_monthly_attendance_arr();

        $page_data['d_school_id'] = $d_school_id;
        $page_data['staff_attend_details_arr']=$staff_attend_details_arr;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['month_year'] = $month_year;
        $page_data['section_id'] = $section_id;
        $page_data['page_name']  = 'staff_monthly_attendance_print';
        $page_data['page_title'] = get_phrase('staff_monthly_attendance_print');
        // $this->load->view('backend/index', $page_data);
        $this->load->library('pdf');
        $view = 'backend/admin/staff_monthly_attendance_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->set_paper("A4", "landscape");

        // $font = $this->pdf->getFontMetrics()->get_font("helvetica");
        // $this->pdf->getCanvas()->page_text(5, 2, "Date: ".date("Y-m-j, g:i a")." - Generated By: ".$_SESSION['name'], $font, 8, array(0,0,0));
        // $this->pdf->getCanvas()->page_text(400, 2, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0,0,0));

        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*__________student wise attendance excel_______________*/
    function staff_monthly_attendance_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        //$this->excel->getActiveSheet()->setTitle('Staff Monthly Attendance Details');
        $this->load->database();

        $filter_arr = $this->get_filters();

        $month_year = $filter_arr['month_year'];
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }

        $staff_attend_details_arr=$this->get_staff_monthly_attendance_arr();

        $count =0;
        $c = 0;
        $titles_arr = array();
        $staff_attendance_arr = array();

        $d_school_id = $filter_arr['d_school_id'];
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $staff_attendance_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            
            // $scl_name=$this->db->query("select * from ".get_school_db().".school where school_id=$d_school_id")->result_array();
            // if(count($scl_name)>0)
            // {
            //   $branch_name =  $scl_name[0]['name'];
            //   $branch_logo =  $scl_name[0]['logo'];
            //   $branch_folder =  $scl_name[0]['folder_name'];
            // }
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];

            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $staff_attendance_arr[] = system_path($branch_logo,$branch_folder,1);
        }
        //$staff_attendance_arr[] = system_path($_SESSION['school_logo']);
 
        $staff_attendance_arr[] = "Staff Monthly Attendance Report";
        $staff_attendance_arr[] = "";

        $month_detail = explode('/', $month_year);
        $month = intval($month_detail[0]);
        $year = $month_detail[1];

        if (isset($month_year) && !empty($month_year))
        {
            $staff_attendance_arr[] = $month_year;
            $c++;
            $month_of_year = date("F-Y", mktime(0, 0, 0, $month+1, 0, $year));
            $titles_arr[$c] = "Month: ".$month_of_year;
        }
        $date_curr= date('t', mktime(0, 0, 0, $month, 1, $year));

        $days_arr = array('','','');
        for($i=1;$i<=$date_curr;$i++)
        {
            $days_arr[] = date("d-M-y", mktime(0, 0, 0, $month, $i, $year));
        }
        $staff_attendance_arr[]  = $days_arr;

        $week_arr = array('Sr.','Emp Code','Name');
        for($i=1;$i<=$date_curr;$i++)
        {
            //$a++;
            $s=mktime(0,0,0,$month, $i, $year);
            $today_date= date('Y-m-d',$s);
            $dw = date( "D", strtotime($today_date));
            $week_arr[] = $dw;
        }
        $week_arr[] = "Present";
        $week_arr[] = "Absent";
        $week_arr[] = "Leave";
        $staff_attendance_arr[]  = $week_arr;
        
        foreach ($staff_attend_details_arr as $key => $value)
        {
            $count++;
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['employee_code'];
            $record_arr[] = $value['name'];
            $present = 0;
            $absent = 0;
            $leave = 0;
            for($i=1;$i<=$date_curr;$i++)
            {
                $attend_status = $value['attend'][$year][$month][$i];
                switch ($attend_status)
                {
                    case 1:
                        $record_arr[] = "P";
                        $present++;
                        break;
                    case 2:
                        $record_arr[] = "A";
                        $absent++;
                        break;
                    case 3:
                        $record_arr[] = "L";
                        $leave++;
                        break;
                    
                    default:
                        $record_arr[] = "";
                        break;
                }
            }
                $record_arr[] = $present;
                $record_arr[] = $absent;
                $record_arr[] = $leave;
            $staff_attendance_arr[] = $record_arr;
        }
        //______________set logo_______________
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
        $this->excel->getActiveSheet()->mergeCells('E1:N1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________set Heading_______________
        $this->excel->getActiveSheet()->mergeCells('H2:N2');
        $this->excel->getActiveSheet()->setCellValue('H2', $staff_attendance_arr[1]);
        $this->excel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        //____________heading end__________________

        //____________Date Start__________________
        $this->excel->getActiveSheet()->mergeCells('H3:N3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('H3',$chk);
        $this->excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        //____________Date end___________________

        //____________set filter___________________
        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('H'.$row_no.':N'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('H'.$row_no, $titles_arr[$i]);
            $this->excel->getActiveSheet()->getStyle('H'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        }
        //____________End filter__________________

        $this->excel->getActiveSheet()->fromArray($staff_attendance_arr);
        $filename='staff monthly attendance.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');   
    }
    
    
        /*|-----------------------------------------------------|*/
    /*|_____________________________________________________|*/
    /*|_____________Staff timing details____________________|*/
    /*|_____________________________________________________|*/
    /*|_________________*****************___________________|*/
    /*|_____________________________________________________|*/
    function staff_timing_detail()
    {
        $apply_filter = $this->input->post('apply_filter');
        $month_year=$this->input->post('month_year');
        $staff_id=$this->input->post('staff_id');
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }

        $staff_timing_detail_arr = $this->staff_timing_detail_arr();

        $d_school_id = $this->input->post('d_school_id');
        $page_data['d_school_id'] = $d_school_id;

        $page_data['date_ary']=$staff_timing_detail_arr['date_ary'];
        $page_data['final_date']=$staff_timing_detail_arr['final_date'];
        $page_data['time_out']=$staff_timing_detail_arr['time_out'];
        $page_data['time_in']=$staff_timing_detail_arr['time_in'];

        $page_data['apply_filter']=$apply_filter;
        $page_data['month_year']=$month_year;
        $page_data['staff_id']=$staff_id;

        $page_data['page_name']  = 'staff_timing_detail';
        $page_data['page_title'] =get_phrase('staff_timing_detail');
        $this->load->view('backend/index', $page_data);
    }
    /*__________staff timing detial array____________________*/
    function staff_timing_detail_arr()
    {
        $apply_filter = $this->input->post('apply_filter');
        $month_year=$this->input->post('month_year');
        $staff_id=$this->input->post('staff_id');

        $staff_filter ="";
        if (isset($staff_id) && !empty($staff_id))
        {
            $staff_filter = "and staff_id = $staff_id";
        }

        $d_school_id = $this->input->post('d_school_id');
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
        
        $timing_qur=$this->db->query("select * from ".get_school_db().".staff_in_out  where DATE_FORMAT(io_date,'%m/%Y') = '".$month_year."' ".$staff_filter." and school_id=".$d_school_id." order by io_date asc , io_time asc")->result_array();
        // echo $this->db->last_query();
        $time_in=array();
        $time_out=array();
        $date_ary=array();
        $time=1;
        foreach($timing_qur as $row){
        $to_time = ($row['io_date']." ".$row['io_time']);

        $date_ary[$row['io_date']]=$row['io_date'];

        if($row['io_date']==$pre_date){

        }else{
        $pre_date=$row['io_date'];
        $time=1;
        }
        if($time==1)
        {
        $time_in[$row['io_date']][]=$to_time;
        $time++;
        }else
        {
        $time_out[$row['io_date']][]=$to_time;
        $time=1;
        }
        }
        $final_date=array();

        foreach($date_ary as $key=>$val){
        $i=0;
        $total_time=0;
        if(isset($time_out[$key][$i])){
        foreach($time_out[$key] as $key1=>$val1){
            $total_time+= strtotime($time_out[$key][$i])-strtotime($time_in[$key][$i]);
            $i++;
        }
        if(isset($time_in[$val][$i])){
            $final_date[$key]['extra_in']=$time_in[$val][$i];
        }
        //$final_date[$key]['time']=gmdate("H:i:s", $total_time);
        $final_date[$key]['time']=$total_time;
        }
        }

        $page_data['date_ary']=$date_ary;
        $page_data['final_date']=$final_date;
        $page_data['time_out']=$time_out;
        $page_data['time_in']=$time_in;

        return $page_data;   
    }
    /*__________staff timing detial pdf______________________*/
    function staff_timing_detail_pdf()
    {
        $apply_filter = $this->input->post('apply_filter');
        $month_year=$this->input->post('month_year');
        $staff_id=$this->input->post('staff_id');
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }

        $staff_timing_detail_arr = $this->staff_timing_detail_arr();


        $d_school_id = $this->input->post('d_school_id');
        $page_data['d_school_id'] = $d_school_id;

        $page_data['date_ary']=$staff_timing_detail_arr['date_ary'];
        $page_data['final_date']=$staff_timing_detail_arr['final_date'];
        $page_data['time_out']=$staff_timing_detail_arr['time_out'];
        $page_data['time_in']=$staff_timing_detail_arr['time_in'];

        $page_data['apply_filter']=$apply_filter;
        $page_data['month_year']=$month_year;
        $page_data['staff_id']=$staff_id;

        $page_data['page_name']  = 'staff_timing_detail_print';
        $page_data['page_title'] =get_phrase('staff_timing_detail_print');

        $this->load->library('pdf');
        $view = 'backend/admin/staff_timing_detail_print';
        $this->pdf->load_view($view,$page_data);
        //$this->pdf->set_paper("A4", "landscape");

        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*__________staff timing detial excel____________________*/
    function staff_timing_detail_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        //$this->excel->getActiveSheet()->setTitle('Staff Monthly Attendance Details');
        $this->load->database();
        $filter_arr = $this->get_filters();

        $month_year = $filter_arr['month_year'];
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }
        $staff_id = $filter_arr['staff_id'];

        $staff_timing_detail_arr = $this->staff_timing_detail_arr();
        $date_ary = $staff_timing_detail_arr['date_ary'];
        $final_date = $staff_timing_detail_arr['final_date'];
        $time_out = $staff_timing_detail_arr['time_out'];
        $time_in = $staff_timing_detail_arr['time_in'];

        $count =0;
        $c = 0;
        $titles_arr = array();
        $staff_timing_arr = array();
        //$staff_timing_arr[] = system_path($_SESSION['school_logo']);

        $d_school_id = $filter_arr['d_school_id'];
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $staff_timing_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {
            
            // $scl_name=$this->db->query("select * from ".get_school_db().".school where school_id=$d_school_id")->result_array();
            // if(count($scl_name)>0)
            // {
            //   $branch_name =  $scl_name[0]['name'];
            //   $branch_logo =  $scl_name[0]['logo'];
            //   $branch_folder =  $scl_name[0]['folder_name'];
            // }
            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];

            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $staff_timing_arr[] = system_path($branch_logo,$branch_folder,1);
        }
 
        $staff_timing_arr[] = "Staff Timing Report";
        $staff_timing_arr[] = "";

        $month_detail = explode('/', $month_year);
        $month = intval($month_detail[0]);
        $year = $month_detail[1];

        if (isset($month_year) && !empty($month_year))
        {
            $staff_timing_arr[] = $month_year;
            $c++;
            $month_of_year = date("F-Y", mktime(0, 0, 0, $month+1, 0, $year));
            $titles_arr[$c] = "Month: ".$month_of_year;
        }
        if (isset($staff_id) && !empty($staff_id))
        {
            $staff_details_arr = get_staff_detail($staff_id);

            $staff_timing_arr[] = $staff_details_arr[0]['name'];
            $c++;
            $titles_arr[$c] = "Name: ".$staff_details_arr[0]['name'];
        }

        $staff_timing_arr[]  = array('Date','Time In','Time Out','Time Count','Total Time');

        if(count($date_ary)>0)
        {
            $total_monthly_time =0;
            foreach($date_ary as $dis_key=>$dis_val)
            {
                $record_arr = array();
                $total_monthly_time += $final_date[$dis_key]['time'];
                $record_arr[] = convert_date($dis_key);
                $record_arr[] = "";
                $record_arr[] = "";
                $record_arr[] = "";
                $record_arr[] = gmdate("H:i:s", $final_date[$dis_key]['time']);
                $staff_timing_arr[] = $record_arr;

                $i=0;
                foreach($time_out[$dis_val] as $key1=>$val1)
                {
                    $new_arr = array();
                    $total_time_cur= strtotime($time_out[$dis_val][$i])-strtotime($time_in[$dis_val][$i]);
                    $new_arr[] = "";
                    $new_arr[] = date('H:i:s',strtotime($time_in[$dis_val][$i]));
                    $new_arr[] = date('H:i:s',strtotime($time_out[$dis_val][$i]));
                    $new_arr[] = gmdate("H:i:s", $total_time_cur);
                    $new_arr[] = "";
                    $staff_timing_arr[] = $new_arr;
                    $i++;
                }
                $extra_arr = array();
                $extra_arr[] = "";
                $extra_arr[] = date('H:i:s',strtotime($final_date[$dis_key]['extra_in']));
                $extra_arr[] = "Na";
                $extra_arr[] = "Na";
                $extra_arr[] = "";

                $staff_timing_arr[] = $extra_arr;
            }

            $total_arr = array();
            $total_arr[] = "";
            $total_arr[] = "";
            $total_arr[] = "";
            $total_arr[] = "Total Monthly Time";
            $monthly_total = seconds_to_hours($total_monthly_time);
            $total_arr[] = $monthly_total['h'].":".$monthly_total['m'].":".$monthly_total['s'];

            $staff_timing_arr[] = $total_arr;

        }
        //______________set logo_______________
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
        //____________set Heading_______________
        $this->excel->getActiveSheet()->mergeCells('A2:G2');
        $this->excel->getActiveSheet()->setCellValue('A2', $staff_timing_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________heading end__________________

        //____________Date Start__________________
        $this->excel->getActiveSheet()->mergeCells('A3:G3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________Date end___________________

        //____________set filter___________________
        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':G'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
            $this->excel->getActiveSheet()->getStyle('A'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //____________End filter__________________

        $this->excel->getActiveSheet()->fromArray($staff_timing_arr);
        $filename='staff monthly attendance.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output'); 
    }
    
    
    ////////////////////////
    // Student Admissions Report //
    //////////////////////////
    
    function section_wise_students_list(){
        $section_id = $this->input->post('section_id');
        $department = $this->input->post('department');
        $class = $this->input->post('class');
        $academic_year = $this->input->post('academic_year');
        
        $ssdate = $this->input->post('startdate');
        $eedate = $this->input->post('enddate');
        
        $startdate = date('Y-m-d',strtotime($ssdate));
        $enddate = date('Y-m-d',strtotime($eedate));
        
        $school_id = $this->input->post('d_school_id');
        
        if(isset($section_id) && $section_id != ""){
            $id_arr = remove_prefix($section_id);
            $prefix = $id_arr['prefix'];
            $value = $id_arr['value'];

            if ($prefix =='s')
            {
                $section_id = $value;
                $std_list = $this->db->query("SELECT a.*, b.*,c.*,d.*,a.name as std, b.title as section, c.name as class, d.title as depart FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id LEFT JOIN ".get_school_db().".class as c ON c.class_id = b.class_id LEFT JOIN ".get_school_db().".departments as d ON d.departments_id = c.departments_id WHERE a.section_id = '$section_id' AND a.school_id = '$school_id'");
                // print_r($std_list);
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
        $page_data['d_school_id'] = $school_id;
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
        
        //______________set logo_______________
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
        //____________set Heading_______________
        $this->excel->getActiveSheet()->mergeCells('A2:G2');
        $this->excel->getActiveSheet()->setCellValue('A2', $unpaid_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('H2:X2');
        //____________heading end__________________

        //____________Date Start__________________
        $this->excel->getActiveSheet()->mergeCells('A3:G3');
        $chk = "Date : ".date("D M Y G:i:s")." - Generated By: ".$_SESSION['name']."";
        $this->excel->getActiveSheet()->setCellValue('A3',$chk);
        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->mergeCells('H3:X3');
        //____________Date end___________________

        //____________set filter___________________
        $empty_rows = 3;
        for ($i=1; $i<=$c; $i++)
        {
            $row_no = ($empty_rows+$i);
            $this->excel->getActiveSheet()->mergeCells('A'.$row_no.':G'.$row_no);
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, "Section Wise Admission");
            $this->excel->getActiveSheet()->getStyle('A'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->mergeCells('H'.$row_no.':X'.$row_no);
        }
        //____________End filter__________________

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
        
        $page_data['d_school_id'] = $school_id;

        $page_data['student_listing_arr'] = $std_list_sectionwise_arr;

        $page_data['page_name']  = 'section_admission_report_print';
        $page_data['page_title'] =get_phrase('section_wise_students_report');

        $this->load->library('pdf');
        $view = 'backend/admin/section_admission_report_print';
        $this->pdf->load_view($view,$page_data);
        //$this->pdf->set_paper("A4", "landscape");

        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    
    /*_____________section_wise_students_list Students excel___________________*/
    
    ////////////////////
    // Branch Wise Std Attendance //
    /////////////////////////
    
     function subjectwise_attendance()
    {
        $filter_arr   = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $section_id   = $filter_arr['section_id_subjectwise'];
        $section_id   = $section_id > 0 ? $section_id : 0;
        

        $start_date_subjectwise = $filter_arr['start_date_subjectwise']; //date_dash($filter_arr['start_date_subjectwise']);
        if (empty($start_date_subjectwise)){
           $start_date_subjectwise =  date('Y-m-d');   //date("d/m/Y");
        }
        else
        {
            $start_date_subjectwise = date('Y-m-d' , strtotime($start_date_subjectwise));
        }
        
        $end_date_subjectwise = $filter_arr['end_date_subjectwise']; //date_dash($filter_arr['end_date_subjectwise']);
        if (empty($end_date_subjectwise)){
           $end_date_subjectwise =  date('Y-m-d');  //date("d/m/Y");
        }
        else
        {
            $end_date_subjectwise = date('Y-m-d' , strtotime($end_date_subjectwise));
        }
        
        
        $q_subjects ="SELECT * FROM ".get_school_db().".subject LEFT JOIN  ".get_school_db().".subject_section ON subject.subject_id = subject_section.subject_id
                      JOIN ".get_school_db().".class_routine ON subject.subject_id = class_routine.subject_id
                      JOIN ".get_school_db().".virtual_class ON virtual_class.class_routine_id = class_routine.class_routine_id
                      JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                      JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                      WHERE class_section.section_id ='".$section_id."' and 
                      DATE_FORMAT(virtual_class.vc_start_time, '%Y-%m-%d') >= '".$start_date_subjectwise."' and
                      DATE_FORMAT(virtual_class.vc_start_time, '%Y-%m-%d') <= '".$end_date_subjectwise."'
                      GROUP BY subject.name";
        $subjects=$this->db->query($q_subjects)->result_array();
        
        
        $query_students="select student.roll as roll_number , virtual_class_student.student_id , subject.name as subject_name , virtual_class_student.student_name , 
                         virtual_class_student.vc_start_time as time_started_at , virtual_class_student.vc_end_time as time_ended_at
                         from ".get_school_db().".class_routine 
                         JOIN ".get_school_db().".virtual_class_student ON 
                         class_routine.class_routine_id = virtual_class_student.class_routine_id 
                         JOIN ".get_school_db().".student ON student.student_id = virtual_class_student.student_id
                         JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id 
                         JOIN  ".get_school_db().".time_table_subject_teacher ON 
                         time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                         JOIN ".get_school_db().".subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id 
                         JOIN ".get_school_db().".staff ON subject_teacher.teacher_id = staff.staff_id 
                         JOIN ".get_school_db().".class_section ON class_routine_settings.section_id = class_section.section_id 
                         JOIN ".get_school_db().".class ON class.class_id = class_section.class_id 
                         JOIN ".get_school_db().".subject ON class_routine.subject_id = subject.subject_id
                         WHERE  
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%Y-%m-%d') >= '".$start_date_subjectwise."' AND
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%Y-%m-%d') <= '".$end_date_subjectwise."'   AND
                         class_section.section_id =" . $section_id;
        
        $students=$this->db->query($query_students)->result_array();


        $d_school_id = $this->input->post('d_school_id');
        $page_data['d_school_id']  = $d_school_id;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['start_date']   = $start_date_subjectwise;
        $page_data['end_date']     = $end_date_subjectwise;
        $page_data['section_id']   = $section_id;
        $page_data['subjects']     = $subjects;
        $page_data['students']     = $students;
        $page_data['page_name']    = 'subject_wise_attendance';
        $page_data['page_title']   = get_phrase('subjectwise_attendance');
        
        $this->load->view('backend/index', $page_data);
    }
    
    function subject_wise_attendance_pdf()
    {

        $filter_arr   = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $section_id   = $filter_arr['section_id_subjectwise'];

        $start_date_subjectwise = $filter_arr['start_date_subjectwise']; //date_dash($filter_arr['start_date_subjectwise']);
        if (empty($start_date_subjectwise)){
           $start_date_subjectwise =  date('Y-m-d');   //date("d/m/Y");
        }
        else
        {
            $start_date_subjectwise = date('Y-m-d' , strtotime($start_date_subjectwise));
        }
        
        $end_date_subjectwise = $filter_arr['end_date_subjectwise']; //date_dash($filter_arr['end_date_subjectwise']);
        if (empty($end_date_subjectwise)){
           $end_date_subjectwise =  date('Y-m-d');  //date("d/m/Y");
        }
        else
        {
            $end_date_subjectwise = date('Y-m-d' , strtotime($end_date_subjectwise));
        }
        
        
        $q_subjects ="SELECT * FROM ".get_school_db().".subject LEFT JOIN  ".get_school_db().".subject_section ON subject.subject_id = subject_section.subject_id
                      JOIN ".get_school_db().".class_routine ON subject.subject_id = class_routine.subject_id
                      JOIN ".get_school_db().".virtual_class ON virtual_class.class_routine_id = class_routine.class_routine_id
                      JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                      JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                      WHERE class_section.section_id ='".$section_id."' and 
                      DATE_FORMAT(virtual_class.vc_start_time, '%Y-%m-%d') >= '".$start_date_subjectwise."' and
                      DATE_FORMAT(virtual_class.vc_start_time, '%Y-%m-%d') <= '".$end_date_subjectwise."'
                      GROUP BY subject.name";
                      
                      
        $subjects=$this->db->query($q_subjects)->result_array();
        
        
        $query_students="select student.roll as roll_number , virtual_class_student.student_id , subject.name as subject_name , virtual_class_student.student_name , 
                         virtual_class_student.vc_start_time as time_started_at , virtual_class_student.vc_end_time as time_ended_at
                         from ".get_school_db().".class_routine 
                         JOIN ".get_school_db().".virtual_class_student ON 
                         class_routine.class_routine_id = virtual_class_student.class_routine_id 
                         JOIN ".get_school_db().".student ON student.student_id = virtual_class_student.student_id
                         JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id 
                         JOIN  ".get_school_db().".time_table_subject_teacher ON 
                         time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                         JOIN ".get_school_db().".subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id 
                         JOIN ".get_school_db().".staff ON subject_teacher.teacher_id = staff.staff_id 
                         JOIN ".get_school_db().".class_section ON class_routine_settings.section_id = class_section.section_id 
                         JOIN ".get_school_db().".class ON class.class_id = class_section.class_id 
                         JOIN ".get_school_db().".subject ON class_routine.subject_id = subject.subject_id
                         WHERE  
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%Y-%m-%d') >= '".$start_date_subjectwise."' AND
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%Y-%m-%d') <= '".$end_date_subjectwise."'   AND
                         class_section.section_id =" . $section_id;
                         
                    
        $students=$this->db->query($query_students)->result_array();


        $d_school_id = $this->uri->segment(3);
        $page_data['d_school_id']  = $d_school_id;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['start_date']   = $start_date_subjectwise;
        $page_data['end_date']     = $end_date_subjectwise;
        $page_data['section_id']   = $section_id;
        $page_data['subjects']     = $subjects;
        $page_data['students']     = $students;
        $page_data['page_name']    = 'subject_wise_attendance_print';
        $page_data['page_title']   = get_phrase('subjectwise_attendance_print');


        $this->load->library('pdf');
        $view = 'backend/admin/subject_wise_attendance_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    

    function subject_wise_attendance_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Subjectwise Attendance Report');
        $this->load->database();
        
        
        $filter_arr   = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $section_id   = $filter_arr['section_id_subjectwise'];
        $d_school_id  = $filter_arr['d_school_id'];

        $start_date_subjectwise = $filter_arr['start_date_subjectwise']; //date_dash($filter_arr['start_date_subjectwise']);
        if (empty($start_date_subjectwise)){
           $start_date_subjectwise =  date('Y-m-d');   //date("d/m/Y");
        }
        else
        {
            $start_date_subjectwise = date('Y-m-d' , strtotime($start_date_subjectwise));
        }
        
        $end_date_subjectwise = $filter_arr['end_date_subjectwise']; //date_dash($filter_arr['end_date_subjectwise']);
        if (empty($end_date_subjectwise)){
           $end_date_subjectwise =  date('Y-m-d');  //date("d/m/Y");
        }
        else
        {
            $end_date_subjectwise = date('Y-m-d' , strtotime($end_date_subjectwise));
        }
        
        
        $q_subjects ="SELECT * FROM ".get_school_db().".subject LEFT JOIN  ".get_school_db().".subject_section ON subject.subject_id = subject_section.subject_id
                      JOIN ".get_school_db().".class_routine ON subject.subject_id = class_routine.subject_id
                      JOIN ".get_school_db().".virtual_class ON virtual_class.class_routine_id = class_routine.class_routine_id
                      JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                      JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                      WHERE class_section.section_id ='".$section_id."' and 
                      DATE_FORMAT(virtual_class.vc_start_time, '%Y-%m-%d') >= '".$start_date_subjectwise."' and
                      DATE_FORMAT(virtual_class.vc_start_time, '%Y-%m-%d') <= '".$end_date_subjectwise."'
                      GROUP BY subject.name";
                      
                      
        $subjects=$this->db->query($q_subjects)->result_array();
        
        
        $query_students="select student.roll as roll_number , virtual_class_student.student_id , subject.name as subject_name , virtual_class_student.student_name , 
                         virtual_class_student.vc_start_time as time_started_at , virtual_class_student.vc_end_time as time_ended_at
                         from ".get_school_db().".class_routine 
                         JOIN ".get_school_db().".virtual_class_student ON 
                         class_routine.class_routine_id = virtual_class_student.class_routine_id 
                         JOIN ".get_school_db().".student ON student.student_id = virtual_class_student.student_id
                         JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id 
                         JOIN  ".get_school_db().".time_table_subject_teacher ON 
                         time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                         JOIN ".get_school_db().".subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id 
                         JOIN ".get_school_db().".staff ON subject_teacher.teacher_id = staff.staff_id 
                         JOIN ".get_school_db().".class_section ON class_routine_settings.section_id = class_section.section_id 
                         JOIN ".get_school_db().".class ON class.class_id = class_section.class_id 
                         JOIN ".get_school_db().".subject ON class_routine.subject_id = subject.subject_id
                         WHERE  
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%Y-%m-%d') >= '".$start_date_subjectwise."' AND
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%Y-%m-%d') <= '".$end_date_subjectwise."'   AND
                         class_section.section_id =" . $section_id;
                         
                    
        $students=$this->db->query($query_students)->result_array();
        



        $count =0;
        $c = 0;
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
        $unpaid_arr[] = 'Subjectwise Attendance';
        $unpaid_arr[] = "";

        if (isset($start_date_subjectwise) && !empty($start_date_subjectwise))
        {
            $unpaid_arr[] = $start_date_subjectwise;
            $c++;
            $titles_arr[$c] = "From Date: ". $start_date_subjectwise . '   End Date:  ' . $end_date_subjectwise;
        }
        
        $total_coumns = 2;
        $table_header_array = array('Roll No' , 'Student Name');
        
        foreach($subjects as $subj)
		{
			$total_coumns = $total_coumns + 1;
			array_push($table_header_array , $subj['name']);
        }
        
        $unpaid_arr[] = $table_header_array;
        
        
        $stud_ids_array =array();
        $stud_names_array =array();
                    
        $startedtime_array =array();
        $endedtime_array =array();
        $sub_names_array =array();
                    
        $stud_rollnumber_array = array();
        
        $stud_ids_unique = array();
        $stud_names_unique = array();
        $stud_rollnumber_unique = array(); 
        
        foreach($students as $row_stud)
        {
                // unique identifiers so should only be added in array once
                if (!in_array($row_stud['student_id'], $stud_ids_unique))
                {
                            array_push($stud_ids_unique,$row_stud['student_id']);
                            array_push($stud_names_unique,$row_stud['student_name']);
                            array_push($stud_rollnumber_unique,$row_stud['roll_number']);
                }
                array_push($stud_ids_array,$row_stud['student_id']);
                array_push($stud_names_array,$row_stud['student_name']);
                array_push($sub_names_array,$row_stud['subject_name']);
                array_push($startedtime_array,$row_stud['time_started_at']);
                array_push($endedtime_array,$row_stud['time_ended_at']);
                array_push($stud_rollnumber_array,$row_stud['roll_number']);
        }
            
        
        if(count($stud_ids_unique) > 0){
            for($stud_counter=0; $stud_counter < count($stud_ids_unique); $stud_counter++){

                    $count++;
                    $record_arr = array();

                    $record_arr[] = $stud_rollnumber_unique[$stud_counter];
                    $record_arr[] = $stud_names_unique[$stud_counter];

                    foreach($subjects as $subj_row){
                        
                           $cellString = '';
                           $start_time = '';
                           $end_time = '';
                           for($counter=0; $counter < count($stud_ids_array); $counter++){
                                if($stud_ids_array[$counter] == $stud_ids_unique[$stud_counter] && $sub_names_array[$counter] == $subj_row['name']){
                                       $cellString = $startedtime_array[$counter] . "<br>" . $endedtime_array[$counter];
                                       $start_time = $startedtime_array[$counter];
                                       $end_time   = $endedtime_array[$counter]; 
                                }
                           } 
                        
                            if(!empty($cellString)){
                               
                                 $record_arr[] = 'From : ' . date("h:i A" , strtotime($start_time)) . '  ' . '  To : ' .  date("h:i A" , strtotime($end_time));
                                
                            }
                            else
                            {
                               $record_arr[] = 'Absent';
                            }
                            
                        } // end of foreach
                        
                $unpaid_arr[] = $record_arr;        
  
            }
            
            
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



        $this->excel->getActiveSheet()->fromArray($unpaid_arr);
        $filename='Subjectwise Attendance.xls';

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

        $objWriter->save('php://output');
    }


    // Ajax Dropdown Data Fetch
    function get_depart_by_school(){
        $school_id = $this->input->post('school');
        echo department_list('',$school_id);
    }
    
    function get_class_by_school(){
        $school_id = $this->input->post('school');
        echo class_list('',$school_id);
    }
    
    function get_section_by_school(){
        $school_id = $this->input->post('school');
        echo section_list('',$school_id);
    }
    
    function get_year_by_school(){
        $school_id = $this->input->post('school');
        echo academic_year_option_list('',$school_id);
    }
    
    function get_student_by_section(){
        $school_id = $this->input->post('school');
        $section = $this->input->post('section');
        
        $id_arr = remove_prefix($section);
        $prefix = $id_arr['prefix'];
        $value = $id_arr['value'];

        if ($prefix =='s')
        {
            $section = $value;
        }
        
        $std = $this->db->query("SELECT a.name as std,a.student_id,a.section_id,b.title as section,b.section_id,a.school_id FROM ".get_school_db().".student as a LEFT JOIN ".get_school_db().".class_section as b ON b.section_id = a.section_id WHERE a.section_id = '$section' AND a.school_id = '$school_id'");
        if($std->num_rows() > 0){
            foreach($std->result() as $data):
                echo "<option value='".$data->student_id."'>".$data->std."
                </option>";
            endforeach;
        }
        
    }
    
    
    
    /*__________function that returns array_result()_________*/
    function get_query_result_in_array($query='')
    {
        return $this->db->query($query)->result_array();
    }
    /*__________function that returns student list___________*/
    function get_section_student()
    {
        $section_id=$this->input->post('section_id');
        $student_id = $this->input->post('student_id');
        $d_school_id = $this->input->post('d_school_id');
        echo section_student($section_id,$student_id,$d_school_id);
    }
    /*__________function that returns discounts______________*/
    function get_discount()
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
        //_________________filter starts________________________

                //_______Search filter____________

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
            from ".get_school_db().".student_chalan_form scf 
            INNER join ".get_school_db().".student_chalan_detail scd 
            on scf.s_c_f_id = scd.s_c_f_id
            where scf.is_processed=0 and scf.is_cancelled=0
            and scf.school_id=".$d_school_id." and scd.type in(1,2)
            ".$std_query." ".$section_filter." ".$student_filter."
            ".$date_filter."
            order by scf.s_c_f_id";

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
}

