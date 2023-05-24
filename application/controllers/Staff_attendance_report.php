<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Staff_attendance_report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        $this->load->helper('report');
    }

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

        $d_school_id = $this->uri->segment(3);
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

        $staff_attend_qur ="select s.staff_id ,s.name ,s.employee_code, attend.status ,attend.date from ".get_school_db().".staff s 
                            LEFT join ".get_school_db().".attendance_staff attend on (s.staff_id = attend.staff_id and DATE_FORMAT(attend.date,'%m/%Y') = '".$month_year."')
                            where s.school_id=".$d_school_id." order by s.name asc ,attend.date";

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

        $month_detail = split('/', $month_year);
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

        $d_school_id = $this->uri->segment(3);
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

        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*__________staff timing detial excel____________________*/
    function staff_timing_detail_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
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

        $month_detail = split('/', $month_year);
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

    function staff_monthly_details()
    {
        $page_data['page_name']  = 'staff_monthly_details';
        $page_data['page_title'] =get_phrase('staff_monthly_details');
        $this->load->view('backend/index', $page_data);
    }

    function staff_listing()
    {
        $filter_arr = $this->get_filters();

        $apply_filter = $filter_arr['apply_filter'];
        $staff_search = $filter_arr['staff_search'];
        $designation_id = $filter_arr['designation_id'];
        $staff_type = $filter_arr['staff_type'];
        $staff_id = $filter_arr['staff_id'];

        $staff_listing_qur = $this->get_staff_listing_query();
        $staff_listing_arr = $this->db->query($staff_listing_qur)->result_array();

        $d_school_id = $this->uri->segment(3);
        $page_data['d_school_id'] = $d_school_id;

        $page_data['apply_filter'] = $apply_filter;
        $page_data['staff_search'] = $staff_search;
        $page_data['designation_id'] = $designation_id;
        $page_data['staff_type'] = $staff_type;
        $page_data['staff_id'] = $staff_id;

        $page_data['staff_listing_arr'] = $staff_listing_arr;
        $page_data['page_name']  = 'staff_listing_report';
        $page_data['page_title'] =get_phrase('staff_listing_report');
        $this->load->view('backend/index', $page_data);
    }
    function get_staff_listing_query()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $staff_search = $filter_arr['staff_search'];
        $designation_id = $filter_arr['designation_id'];
        $staff_type = $filter_arr['staff_type'];
        $staff_id = $filter_arr['staff_id'];

        $staff_search_filter = "";
        if ( isset($staff_search) && $staff_search != "")
        {
            $staff_search_filter =" AND (
            s.name LIKE '%".$staff_search."%' OR  
            s.id_no LIKE '%".$staff_search."%' OR
            s.permanent_address LIKE '%".$staff_search."%' OR  
            s.postal_address LIKE '%".$staff_search."%' OR 
            s.phone_no LIKE '%".$staff_search."%' OR
            s.email LIKE '%".$staff_search."%' OR
            s.blood_group LIKE '%".$staff_search."%' OR
            s.employee_code LIKE '%".$staff_search."%' OR
            s.mobile_no LIKE '%".$staff_search."%' OR 
            s.system_id LIKE '%".$staff_search."%' OR
            s.emergency_no LIKE '%".$staff_search."%' OR
            d.title LIKE '%".$staff_search."%'

            )";
        }

        $designation_filter = "";
        if (!empty($designation_id))
        {
            $designation_filter = " And d.designation_id = ".$designation_id." ";
        }

        $staff_type_filter = "";
        if ( isset($staff_type) && $staff_type != "")
        {
            if ($staff_type==1)
            {
                //all
                $staff_type_filter = "";

            }elseif($staff_type==2)
            {
                //teaching staff
                $staff_type_filter = " and d.is_teacher = 1 ";
            }elseif ($staff_type==3)
            {
                //non teaching staff
                $staff_type_filter = " and d.is_teacher = 0 ";
            }
            
        }

        $staff_id_filter ="";
        if ( isset($staff_id) && $staff_id != "")
        {
            $staff_id_filter =" And s.staff_id = ".$staff_id." ";
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

        $staff_listing_qur = "SELECT s.*, d.title as designation,
        d.is_teacher FROM ".get_school_db().".staff s 
        left JOIN ".get_school_db().".designation d ON d.designation_id = s.designation_id 
        WHERE s.school_id=".$d_school_id."
        ".$staff_search_filter." ".$designation_filter." ".$staff_id_filter." ".$staff_type_filter."
        ORDER BY s.staff_id";

        return $staff_listing_qur;
    }
    function staff_listing_pdf()
    {
        $filter_arr = $this->get_filters();

        $apply_filter = $filter_arr['apply_filter'];
        $staff_search = $filter_arr['staff_search'];
        $designation_id = $filter_arr['designation_id'];
        $staff_type = $filter_arr['staff_type'];
        $staff_id = $filter_arr['staff_id'];

        $staff_listing_qur = $this->get_staff_listing_query();
        $staff_listing_arr = $this->db->query($staff_listing_qur)->result_array();

        $page_data['apply_filter'] = $apply_filter;
        $page_data['staff_search'] = $staff_search;
        $page_data['designation_id'] = $designation_id;
        $page_data['staff_type'] = $staff_type;
        $page_data['staff_id'] = $staff_id;

        $d_school_id = $this->input->post('d_school_id');
        $page_data['d_school_id'] = $d_school_id;

        $page_data['staff_listing_arr'] = $staff_listing_arr;

        $page_data['page_name']  = 'staff_listing_report_print';
        $page_data['page_title'] =get_phrase('staff_listing_report_print');

        $this->load->library('pdf');
        $view = 'backend/admin/staff_listing_report_print';
        $this->pdf->load_view($view,$page_data);
        //$this->pdf->set_paper("A4", "landscape");

        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    function staff_listing_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        //$this->excel->getActiveSheet()->setTitle('Staff Monthly Attendance Details');
        $this->load->database();
        $filter_arr = $this->get_filters();

        $apply_filter = $filter_arr['apply_filter'];
        $staff_search = $filter_arr['staff_search'];
        $designation_id = $filter_arr['designation_id'];
        $staff_type = $filter_arr['staff_type'];
        $staff_id = $filter_arr['staff_id'];

        $staff_listing_qur = $this->get_staff_listing_query();
        $staff_listing_arr = $this->db->query($staff_listing_qur)->result_array();

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

            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $staff_timing_arr[] = system_path($branch_logo,$branch_folder,1);
        }
 
        $staff_timing_arr[] = "Staff List";
        $staff_timing_arr[] = "";

        if (isset($staff_search) && !empty($staff_search))
        {
            $staff_timing_arr[] = $staff_search;
            $c++;
            $titles_arr[$c] = "Kewyword: ".$staff_search;
        }
        if (isset($designation_id) && !empty($designation_id))
        {
            $staff_timing_arr[] = $designation_id;
            $c++;
            $designation_details = designation_details($designation_id,$d_school_id);
            $titles_arr[$c] = "Designation: ".$designation_details[0]['title'];
        }

        if (isset($staff_type) && !empty($staff_type))
        {
            $staff_timing_arr[] = $staff_type;
            $c++;
            $titles_arr[$c] = "Staff Type: ".get_staff_type($staff_type);
        }
        if (isset($staff_id) && !empty($staff_id))
        {
            $staff_details_arr = get_staff_detail($staff_id);

            $staff_timing_arr[] = $staff_details_arr[0]['name'];
            $c++;
            $titles_arr[$c] = "Name: ".$staff_details_arr[0]['name'];
        }

        $staff_timing_arr[]  = array('sr','Emp Code','Name','Designation','ID NO','ID Type','DOB','Gender','Religion','Blood Group','Postal Address','Permanent Address','Country','Province','City','Location','Ph #','Mobile #','Emergency #','Status','Periods Per Day','Periods Per Week','E-mail','Nationality','Is Teacher');

            foreach($staff_listing_arr as $key=>$val)
            {
                $count++;
                $record_arr = array();
                $record_arr[] = $count;
                $record_arr[] = $val['employee_code'];
                $record_arr[] = $val['name'];
                $record_arr[] = $val['designation'];
                $record_arr[] = $val['id_no'];
                $record_arr[] = get_id_type($val['id_type']);
                if (!empty($val['dob']))
                {
                    $record_arr[] = date_view($val['dob']);
                }
                $record_arr[] = $val['gender'];
                $record_arr[] = religion($val['religion']);
                $record_arr[] = $val['blood_group'];
                $record_arr[] = $val['postal_address'];
                $record_arr[] = $val['permanent_address'];
                $country_detail = get_country_detail($val['country_id']);
                $record_arr[] = $country_detail[0]['title'];

                $provience_detail = get_provience_detail($val['province_id']);
                $record_arr[] = $provience_detail[0]['title'];

                $city_detail = get_city_detail($val['city_id']);
                $record_arr[] =$city_detail['title'];

                $location_detail = get_location_detail($val['location_id']);
                $record_arr[] = $location_detail[0]['title'];

                $record_arr[] = $val['phone_no'];
                $record_arr[] = $val['mobile_no'];
                $record_arr[] = $val['emergency_no'];
                if ($val['status']==1)
                {
                    $record_arr[] = "".get_phrase('active')."";
                }
                else
                {
                    $record_arr[] = "".get_phrase('in-active')."";
                }
                $record_arr[] = $val['periods_per_day'];
                $record_arr[] = $val['periods_per_week'];
                $record_arr[] = $val['email'];

                $country_detail = get_country_detail($val['nationality']);
                $record_arr[] = $country_detail[0]['title'];

                if ($val['is_teacher']==1)
                {
                    $record_arr[] = "".get_phrase('yes')."";
                }
                else
                {
                    $record_arr[] = "".get_phrase('no')."";
                }

                $staff_timing_arr[] = $record_arr;
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
        $this->excel->getActiveSheet()->setCellValue('A2', $staff_timing_arr[1]);
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
            $this->excel->getActiveSheet()->setCellValue('A'.$row_no, $titles_arr[$i]);
            $this->excel->getActiveSheet()->getStyle('A'.$row_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->mergeCells('H'.$row_no.':X'.$row_no);
        }
        //____________End filter__________________

        $this->excel->getActiveSheet()->fromArray($staff_timing_arr);
        $filename='staff list.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output'); 
    }
    function get_staff_type()
    {
        $staff_type=$this->input->post('staff_type');
        $designation_id = $this->input->post('designation_id');
        $staff_id = $this->input->post('staff_id');
        $d_school_id = $this->input->post('d_school_id');
        echo staff_list(0,$staff_id,$staff_type,$designation_id,$d_school_id);
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

        $month_year = $this->input->post('month_year' ,TRUE);
        $staff_id = $this->input->post('staff_id' ,TRUE);

        $staff_search = $this->input->post('staff_search' ,TRUE);
        $designation_id = $this->input->post('designation_id' ,TRUE);
        $staff_type = $this->input->post('staff_type' ,TRUE);
        $d_school_id = $this->input->post('d_school_id' ,TRUE);

        $filter_arr = array();
        $filter_arr['apply_filter'] = $apply_filter;
        $filter_arr['std_search'] = $std_search;
        $filter_arr['section_id'] = $section_id;
        $filter_arr['student_id'] = $student_id;
        $filter_arr['start_date'] = $start_date;
        $filter_arr['end_date'] = $end_date;
        $filter_arr['select_date'] = $select_date;
        $filter_arr['month_year'] = $month_year;
        $filter_arr['staff_id'] = $staff_id;
        $filter_arr['staff_search'] = $staff_search;
        $filter_arr['designation_id'] = $designation_id;
        $filter_arr['staff_type'] = $staff_type;
        $filter_arr['d_school_id'] = $d_school_id;

        return $filter_arr;
    }
}