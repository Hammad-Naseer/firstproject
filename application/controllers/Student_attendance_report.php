<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();
class Student_attendance_report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        $this->load->helper('report');
    }
    
    function subjectwise_attendance()
    {
        $filter_arr   = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $section_id   = $filter_arr['section_id_subjectwise'];
        $section_id   = $section_id > 0 ? $section_id : 0;

        $start_date_subjectwise = date_dash($filter_arr['start_date_subjectwise']);
        if (empty($start_date_subjectwise)){
           $start_date_subjectwise =date("d/m/Y");
        }
        
        $end_date_subjectwise = date_dash($filter_arr['end_date_subjectwise']);
        if (empty($end_date_subjectwise)){
           $end_date_subjectwise =date("d/m/Y");
        }
        
        $q_subjects ="SELECT * FROM ".get_school_db().".subject LEFT JOIN  ".get_school_db().".subject_section ON subject.subject_id = subject_section.subject_id
                      JOIN ".get_school_db().".class_routine ON subject.subject_id = class_routine.subject_id
                      JOIN ".get_school_db().".virtual_class ON virtual_class.class_routine_id = class_routine.class_routine_id
                      JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                      JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                      WHERE class_section.section_id ='".$section_id."' and 
                      DATE_FORMAT(virtual_class.vc_start_time, '%d/%m/%Y') >= '".$start_date_subjectwise."' and
                      DATE_FORMAT(virtual_class.vc_start_time, '%d/%m/%Y') <= '".$end_date_subjectwise."'
                      GROUP BY subject.name";
        $subjects=$this->db->query($q_subjects)->result_array();
        
        
        $query_students="select student.roll as roll_number , virtual_class_student.student_id , subject.name as subject_name , 
                         virtual_class_student.student_name , virtual_class_student.vc_start_time as time_started_at , 
                         virtual_class_student.vc_end_time as time_ended_at
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
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') >= '".$start_date_subjectwise."' AND
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') <= '".$end_date_subjectwise."'   AND
                         class_section.section_id =" . $section_id;
                         
        $students=$this->db->query($query_students)->result_array();

        $d_school_id              = $this->uri->segment(3);
        
        $page_data['d_school_id'] = $d_school_id;
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

        $start_date_subjectwise = date_dash($filter_arr['start_date_subjectwise']);
        if (empty($start_date_subjectwise)){
           $start_date_subjectwise =date("d/m/Y");
        }
        
        $end_date_subjectwise = date_dash($filter_arr['end_date_subjectwise']);
        if (empty($end_date_subjectwise)){
           $end_date_subjectwise =date("d/m/Y");
        }
        
        
        $q_subjects ="SELECT * FROM ".get_school_db().".subject LEFT JOIN  ".get_school_db().".subject_section ON subject.subject_id = subject_section.subject_id
                      JOIN ".get_school_db().".class_routine ON subject.subject_id = class_routine.subject_id
                      JOIN ".get_school_db().".virtual_class ON virtual_class.class_routine_id = class_routine.class_routine_id
                      JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                      JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                      WHERE class_section.section_id ='".$section_id."' and 
                      DATE_FORMAT(virtual_class.vc_start_time, '%d/%m/%Y') >= '".$start_date_subjectwise."' and
                      DATE_FORMAT(virtual_class.vc_start_time, '%d/%m/%Y') <= '".$end_date_subjectwise."'
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
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') >= '".$start_date_subjectwise."' AND
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') <= '".$end_date_subjectwise."'   AND
                         class_section.section_id =" . $section_id;
        $students=$this->db->query($query_students)->result_array();

        $d_school_id = $this->uri->segment(3);
        $page_data['d_school_id'] = $d_school_id;
        
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

        $start_date_subjectwise = date_dash($filter_arr['start_date_subjectwise']);
        if (empty($start_date_subjectwise)){
           $start_date_subjectwise =date("d/m/Y");
        }
        
        $end_date_subjectwise = date_dash($filter_arr['end_date_subjectwise']);
        if (empty($end_date_subjectwise)){
           $end_date_subjectwise =date("d/m/Y");
        }
        
        $q_subjects ="SELECT * FROM ".get_school_db().".subject LEFT JOIN  ".get_school_db().".subject_section ON subject.subject_id = subject_section.subject_id
                      JOIN ".get_school_db().".class_routine ON subject.subject_id = class_routine.subject_id
                      JOIN ".get_school_db().".virtual_class ON virtual_class.class_routine_id = class_routine.class_routine_id
                      JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                      JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                      WHERE class_section.section_id ='".$section_id."' and 
                      DATE_FORMAT(virtual_class.vc_start_time, '%d/%m/%Y') >= '".$start_date_subjectwise."' and
                      DATE_FORMAT(virtual_class.vc_start_time, '%d/%m/%Y') <= '".$end_date_subjectwise."'
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
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') >= '".$start_date_subjectwise."' AND
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') <= '".$end_date_subjectwise."'   AND
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
    
    function section_wise_attendance()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $section_id = $filter_arr['section_id'];

        $select_date = date_dash($filter_arr['select_date']);
        if (empty($select_date)){
           $select_date =date("d/m/Y");
        }

        //---------Total Students_______________________
        $section_wise_total_stud_qur = $this->get_section_wise_total_students_query();
        $section_wise_total_stud_arr =$this->get_query_result_in_array($section_wise_total_stud_qur);

        $total_students_arr  = array();
        foreach ($section_wise_total_stud_arr as $key => $value)
        {
           $total_students_arr[$value['section_id']]['total_students'] = $value['total_students'];
        }

        //---------attendance status_______________________

        $section_wise_attendance_status_qur = $this->get_section_wise_attendance_status_query();
        $section_wise_attendance_status_arr =$this->get_query_result_in_array($section_wise_attendance_status_qur);

        $attendance_status_arr  = array();
        foreach ($section_wise_attendance_status_arr as $key => $value)
        {
           $attendance_status_arr[$value['section_id']][$value['status']] = $value['count'];
        }

        $d_school_id = $this->uri->segment(3);
        $page_data['d_school_id'] = $d_school_id;

        $page_data['apply_filter'] = $apply_filter;
        $page_data['select_date'] = $select_date;
        $page_data['section_id']  = $section_id;
        $page_data['total_students_arr']  = $total_students_arr;
        $page_data['attendance_status_arr']  = $attendance_status_arr;

        $page_data['page_name']  = 'section_wise_attendance';
        $page_data['page_title'] = get_phrase('section_wise_attendance');
        $this->load->view('backend/index', $page_data);
    }
    /*__________section wise Total Students query___________*/
    function get_section_wise_total_students_query()
    {
        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];

        $select_date = $filter_arr['select_date'];
        if (empty($select_date)){
           $select_date =date("Y-m-d");
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
            else{
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

        return $section_wise_total_stud_qur ="SELECT s.section_id ,COUNT(s.student_id) as total_students FROM ".get_school_db().".student s
        inner join ".get_school_db().".class_section cs on s.section_id = cs.section_id
        where s.school_id=".$d_school_id." and s.student_status in(".student_query_status().") ".$section_filter."
        group by s.section_id";
    }
    /*__________section wise absent Students query___________*/
    function get_section_wise_attendance_status_query()
    {
        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];

        $select_date = $filter_arr['select_date'];
        if (empty($select_date)){
           $select_date = date("Y-m-d");
        }
        //_________________filter starts________________________
                    //_______Date filter____________
        $date_filter="";
        if ($select_date != "")
        {
            $date_filter = " And attend.date = '".$select_date."'";
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
            else{
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

        return $section_wise_attendance_status_qur = "SELECT s.section_id , attend.date , COUNT(s.student_id) as count , attend.status FROM ".get_school_db().".student s 
        inner join ".get_school_db().".class_section cs on s.section_id = cs.section_id 
        inner join ".get_school_db().".attendance attend on s.student_id = attend.student_id
        where s.school_id=".$d_school_id." and s.student_status in(".student_query_status().") ".$section_filter." ".$date_filter."
        group by s.section_id, attend.status";
    }
    /*____________section wise attendance pdf________________*/
    function section_wise_attendance_pdf()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $section_id = $filter_arr['section_id'];
        $select_date = date_dash($filter_arr['select_date']);
        if (empty($select_date)){
           $select_date =date("d/m/Y");
        }

        //---------Total Students_______________________
        $section_wise_total_stud_qur = $this->get_section_wise_total_students_query();
        $section_wise_total_stud_arr =$this->get_query_result_in_array($section_wise_total_stud_qur);

        $total_students_arr  = array();
        foreach ($section_wise_total_stud_arr as $key => $value)
        {
           $total_students_arr[$value['section_id']]['total_students'] = $value['total_students'];
        }

        //---------attendance status_______________________

        $section_wise_attendance_status_qur = $this->get_section_wise_attendance_status_query();
        $section_wise_attendance_status_arr =$this->get_query_result_in_array($section_wise_attendance_status_qur);

        $attendance_status_arr  = array();
        foreach ($section_wise_attendance_status_arr as $key => $value)
        {
           $attendance_status_arr[$value['section_id']][$value['status']] = $value['count'];
        }

        $d_school_id = $filter_arr['d_school_id'];
        $page_data['d_school_id'] = $d_school_id;

        $page_data['select_date'] = $select_date;
        $page_data['section_id']  = $section_id;
        $page_data['apply_filter']  = $apply_filter;
        $page_data['total_students_arr']  = $total_students_arr;
        $page_data['attendance_status_arr']  = $attendance_status_arr;

        $page_data['page_name']  = 'section_wise_attendance_print';
        $page_data['page_title'] = get_phrase('section_wise_attendance_print');

        $this->load->library('pdf');
        $view = 'backend/admin/section_wise_attendance_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*___________section wise attendance excel_______________*/
    function section_wise_attendance_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Section Wise Attendance Details');
        $this->load->database();

        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];

        $select_date = date_dash($filter_arr['select_date']);
        if (empty($select_date)){
           $select_date =date("d/m/Y");
        }

        $section_wise_total_stud_qur = $this->get_section_wise_total_students_query();
        $section_wise_total_stud_arr =$this->get_query_result_in_array($section_wise_total_stud_qur);

        $total_students_arr  = array();
        foreach ($section_wise_total_stud_arr as $key => $value)
        {
           $total_students_arr[$value['section_id']]['total_students'] = $value['total_students'];
        }
   
        $section_wise_attendance_status_qur = $this->get_section_wise_attendance_status_query();
        $section_wise_attendance_status_arr =$this->get_query_result_in_array($section_wise_attendance_status_qur);

        $attendance_status_arr  = array();
        foreach ($section_wise_attendance_status_arr as $key => $value)
        {
           $attendance_status_arr[$value['section_id']][$value['status']] = $value['count'];
        }

        $count =0;
        $total = 0;
        $present = 0;
        $absent = 0;
        $leave = 0;
        $c = 0;
        $titles_arr = array();
        $section_attendance_arr = array();
        // $section_attendance_arr[] = system_path($_SESSION['school_logo']);
        $d_school_id = $filter_arr['d_school_id'];
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $section_attendance_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {

            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];

            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $section_attendance_arr[] = system_path($branch_logo,$branch_folder,1);
        }
 
        $id_arr = remove_prefix($section_id);
        $prefix = $id_arr['prefix'];
        $value = $id_arr['value'];

        $heading = get_phrase("attendance_details");
        if ($prefix=='d') {
            $heading = get_phrase("department_wise_attendance_report");
        }elseif ($prefix=='c') {
            $heading = get_phrase("class_wise_attendance_report");
        }elseif ($prefix=='s'){
            $heading = get_phrase("section_wise_attendance_report");
        }
        $section_attendance_arr[] = $heading;
        $section_attendance_arr[] = "";

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
            $section_attendance_arr[] = $dep_cls_sec;
            $c++;
            $titles_arr[$c] = $label." : ".$dep_cls_sec;
        }

        if (isset($select_date) && !empty($select_date))
        {
            $section_attendance_arr[] = $select_date;
            $c++;
            $titles_arr[$c] = "Date: ".$select_date;
        }

        $section_attendance_arr[] = array('Sr.','Department','Class','Section','Total Students', 'Present','Absent','Leave');

        $sec_ary = department_class_section_hierarchy($section_id,$d_school_id);
        
        foreach ($sec_ary as $key => $value)
        {
            $count++;
            $total += $total_students_arr[$value['s_id']]['total_students'];
            $present += $attendance_status_arr[$value['s_id']][1];
            $absent += $attendance_status_arr[$value['s_id']][2];
            $leave += $attendance_status_arr[$value['s_id']][3];
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['d'];
            $record_arr[] = $value['c'];
            $record_arr[] = $value['s'];
 
            $record_arr[] = $total_students_arr[$value['s_id']]['total_students'];
            $record_arr[] = $attendance_status_arr[$value['s_id']][1];
            $record_arr[] = $attendance_status_arr[$value['s_id']][2];
            $record_arr[] = $attendance_status_arr[$value['s_id']][3];

            $section_attendance_arr[] = $record_arr;
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
        $this->excel->getActiveSheet()->mergeCells('E1:H1');
        $this->excel->getActiveSheet()->setCellValue('E1',$school_name);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________set Heading_______________
        $this->excel->getActiveSheet()->mergeCells('A2:H2');
        $this->excel->getActiveSheet()->setCellValue('A2', $section_attendance_arr[1]);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //____________heading end__________________

        //____________Date Start__________________
        $this->excel->getActiveSheet()->mergeCells('A3:H3');
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
        $this->excel->getActiveSheet()->setCellValue('E'.$count.'',$total);
        $this->excel->getActiveSheet()->setCellValue('F'.$count.'',$present);
        $this->excel->getActiveSheet()->setCellValue('G'.$count.'',$absent);
        $this->excel->getActiveSheet()->setCellValue('H'.$count.'',$leave);
        //____________End total__________________
        $this->excel->getActiveSheet()->fromArray($section_attendance_arr);
        $filename='section wise attendance.xls';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache          
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    function student_wise_attendance()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $month_year = $filter_arr['month_year'];
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }
        $section_id = $filter_arr['section_id'];

        $attend_details_arr = $this->get_student_wise_attendance_arr();

        $d_school_id = $this->uri->segment(3);
        $page_data['d_school_id'] = $d_school_id;
    
        $page_data['attend_details_arr'] = $attend_details_arr;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['month_year'] = $month_year;
        $page_data['section_id'] = $section_id;
        $page_data['page_name']  = 'student_wise_attendance';
        $page_data['page_title'] = get_phrase('student_wise_attendance');
        $this->load->view('backend/index', $page_data);
    }
    /*__________student wise attendance array_______________*/
    function get_student_wise_attendance_arr()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $month_year = $filter_arr['month_year'];
        $section_id = $filter_arr['section_id'];

        $id_arr = remove_prefix($section_id);
        $prefix = $id_arr['prefix'];
        $value = $id_arr['value'];

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

        $student_attend_qur ="select s.student_id ,s.name ,s.roll,
        attend.status ,attend.date,s.section_id
        from ".get_school_db().".student s 
        LEFT join ".get_school_db().".attendance attend on
        (s.student_id = attend.student_id and DATE_FORMAT(attend.date,'%m/%Y') = '".$month_year."')
        where s.section_id=".$value." and s.school_id=".$d_school_id." and student_status IN (".student_query_status().") order by attend.date";

        $student_attend_arr = $this->db->query($student_attend_qur)->result_array();

        $attend_details_arr = array();
        foreach ($student_attend_arr as $key => $value)
        {
            $splited_date =explode('-', $value['date']);
            
            $attend_details_arr[$value['student_id']]['attend'][$splited_date[0]][intval($splited_date[1])][intval($splited_date[2])]= $value['status'];
            $attend_details_arr[$value['student_id']]['name']= $value['name'];
            $attend_details_arr[$value['student_id']]['roll']= $value['roll'];
        }
        return $attend_details_arr;
    }
    /*__________student wise attendance pdf_________________*/
    function student_wise_attendance_pdf()
    {
        $filter_arr = $this->get_filters();
        $apply_filter = $filter_arr['apply_filter'];
        $month_year = $filter_arr['month_year'];
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }
        $section_id = $filter_arr['section_id'];

        $attend_details_arr = $this->get_student_wise_attendance_arr();


        $d_school_id = $filter_arr['d_school_id'];
        $page_data['d_school_id'] = $d_school_id;
    
        $page_data['attend_details_arr'] = $attend_details_arr;
        $page_data['apply_filter'] = $apply_filter;
        $page_data['month_year'] = $month_year;
        $page_data['section_id'] = $section_id;
        $page_data['page_name']  = 'student_wise_attendance_print';
        $page_data['page_title'] = get_phrase('student_wise_attendance_print');
        // $this->load->view('backend/index', $page_data);
        $this->load->library('pdf');
        $view = 'backend/admin/student_wise_attendance_print';
        $this->pdf->load_view($view,$page_data);
        $this->pdf->set_paper("A4", "landscape");

        $this->pdf->render();
        $this->pdf->stream("".$page_data['page_title'].".pdf");
    }
    /*__________student wise attendance excel_______________*/
    function student_wise_attendance_excel()
    {
        $this->load->library('excel');
        $this->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Student Wise Attendance Details');
        $this->load->database();

        $filter_arr = $this->get_filters();
        $section_id = $filter_arr['section_id'];

        $month_year = $filter_arr['month_year'];
        if ($month_year=="")
        {
            $month_year=date("m/Y");
        }

        $attend_details_arr = $this->get_student_wise_attendance_arr();


        $count =0;
        $c = 0;
        $titles_arr = array();
        $section_attendance_arr = array();
        //$section_attendance_arr[] = system_path($_SESSION['school_logo']);

        $d_school_id = $filter_arr['d_school_id'];
        $logo_path="";
        $school_name = "";
        if (empty($d_school_id))
        {
            $logo_path = system_path($_SESSION['school_logo']);
            $school_name = $_SESSION['school_name'];
            $section_attendance_arr[] = system_path($_SESSION['school_logo']);
        }
        else
        {

            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            
            $logo_path = system_path($branch_logo,$branch_folder,1);
            $school_name = $branch_name;
            $section_attendance_arr[] = system_path($branch_logo,$branch_folder,1);
        }
 
        $id_arr = remove_prefix($section_id);
        $prefix = $id_arr['prefix'];
        $value = $id_arr['value'];

        $heading = get_phrase("attendance_details");
        if ($prefix=='d') {
            $heading = get_phrase("department_wise_attendance_report");
        }elseif ($prefix=='c') {
            $heading = get_phrase("class_wise_attendance_report");
        }elseif ($prefix=='s'){
            $heading = get_phrase("student_wise_attendance_report");
        }
        $section_attendance_arr[] = $heading;
        $section_attendance_arr[] = "";

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
            $section_attendance_arr[] = $dep_cls_sec;
            $c++;
            $titles_arr[$c] = $label." : ".$dep_cls_sec;
        }

        $month_detail = split('/', $month_year);
        $month = intval($month_detail[0]);
        $year = $month_detail[1];

        if (isset($month_year) && !empty($month_year))
        {
            $section_attendance_arr[] = $month_year;
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
        $section_attendance_arr[]  = $days_arr;

        $week_arr = array('Sr.','Roll','Name');
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
        $section_attendance_arr[]  = $week_arr;
        
        foreach ($attend_details_arr as $key => $value)
        {
            $count++;
            $record_arr = array();

            $record_arr[] = $count;
            $record_arr[] = $value['roll'];
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
            $section_attendance_arr[] = $record_arr;
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
        $this->excel->getActiveSheet()->setCellValue('H2', $section_attendance_arr[1]);
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

        $this->excel->getActiveSheet()->fromArray($section_attendance_arr);
        $filename='student wise attendance.xls';
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
        
        $section_id_subjectwise = $this->input->post('section_id_subjectwise', TRUE);
        
        $start_date_subjectwise = "";
        $temp_start_date_subjectwise = $this->input->post('start_date_subjectwise' ,TRUE);
        if (isset($temp_start_date_subjectwise) && !empty($temp_start_date_subjectwise)) {
            $start_date_subjectwise = date_slash($temp_start_date_subjectwise);
        }
        
        $end_date_subjectwise = "";
        $temp_end_date_subjectwise = $this->input->post('end_date_subjectwise' ,TRUE);
        if (isset($temp_end_date_subjectwise) && !empty($temp_end_date_subjectwise)){
           $end_date_subjectwise = date_slash($temp_end_date_subjectwise);
        }
        
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

        $month_year = $this->input->post('month_year', TRUE);
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
        $filter_arr['d_school_id'] = $d_school_id;
        $filter_arr['section_id_subjectwise'] = $section_id_subjectwise;
        $filter_arr['start_date_subjectwise'] = $start_date_subjectwise;
        $filter_arr['end_date_subjectwise']   = $end_date_subjectwise;
        
        return $filter_arr;
    }
    function get_query_result_in_array($query='')
    {
        return $this->db->query($query)->result_array();
    }
}