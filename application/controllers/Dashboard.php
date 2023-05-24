<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Dashboard extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->load->database();
            // $this->output->enable_profiler(TRUE);
            $prefs = array (
                'show_next_prev'  => TRUE,
                'next_prev_url'   =>  base_url().'dashboard/calendar_view',
                'start_day'    => 'saturday',
                'month_type'   => 'long',
                'day_type'     => 'short'
            );

            $prefs['template'] = '
                            {table_open}<table cellpadding="1" cellspacing="2">{/table_open}
                            {heading_row_start}<tr>{/heading_row_start}
                            {heading_previous_cell}<th class="prev_sign"><a href="#" class="btn btn-primary text-white" style="font-size: 16px;position: relative;top: 8px;" onclick="next_prev(\'{previous_url}\');"><i class="fas fa-angle-double-left text-white"></i> <i class="fas fa-angle-double-left text-white" style="position:relative;left:-10px"></i></a></th>{/heading_previous_cell}
                            {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
                            {heading_next_cell}<th><a href="#" class="btn btn-primary text-white" style="font-size: 16px;position: relative;top: 8px;" onclick="next_prev(\'{next_url}\');"><i class="fas fa-angle-double-right text-white"></i> <i class="fas fa-angle-double-right text-white" style="position:relative;left:-10px"></i></a></th>{/heading_next_cell}
                            
                            {heading_row_end}</tr>{/heading_row_end}
                            
                            //Deciding where to week row start
                            {week_row_start}<tr class="week_name">{/week_row_start}
                            //Deciding  week day cell and  week days
                            {week_day_cell}<td>{week_day}</td>{/week_day_cell}
                            //week row end
                            {week_row_end}</tr>{/week_row_end}
                            
                            {cal_row_start}<tr>{/cal_row_start}
                            {cal_cell_start}<td>{/cal_cell_start}
                            
                            {cal_cell_content}<div class="set-evnt-ltst"><div class="set-evnt ">{day}<ul class="latest_event" title="sdkfjskdfkskj">{content}</ul></div></div{/cal_cell_content}
                            {cal_cell_content_today}<div class="highlight_day set-evnt-hilgt"><div class="set-evnt">{day}<ul class="latest_event" title="sdkfjskdfkskj">{content}</ul></div></div>{/cal_cell_content_today}
                            
                            {cal_cell_no_content}{day}{/cal_cell_no_content}
                            {cal_cell_no_content_today}<div class="highlight_day">{day}</div>{/cal_cell_no_content_today}
                            
                            {cal_cell_blank}&nbsp;{/cal_cell_blank}
                            
                            {cal_cell_end}</td>{/cal_cell_end}
                            {cal_row_end}</tr>{/cal_row_end}
                            
                            {table_close}</table>{/table_close}
                            ';
            $this->load->library('calendar', $prefs);
            
            /*cache control*/
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');
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
        
      
        
        

        function home()
        {
            if (!$_SESSION['user_login'])
                redirect(base_url());
                
            $school_id = $this->uri->segment(3);
              
            //count teachers for dashboard
            $page_data['teac_count']  = get_school_teacher_count($school_id);
                  
            //count staff for dashboard
            $page_data['staff_count']  = get_school_staff_count($school_id);
            
            //count students for dashboard
            $page_data['student_count']  = get_school_students_count($school_id);
            
            //count candidates for dashboard
            $page_data['candidate_count']  = get_school_candidate_count($school_id);
            

            $page_data['page_name']  = 'dashboard';
            $page_data['page_title'] = get_phrase('admin_dashboard');

            $this->load->view('backend/index', $page_data);
        }


        function get_staff_count($d_school_id)
        {
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/dash_staff_count',$data);
        }


        function dash_tech_attn_count($date_value,$d_school_id)
        {

            $data['date_value']=$date_value;
            $data['d_school_id']=$d_school_id;

            $this->load->view('backend/admin/ajax/dash_tech_attn_count',$data);

        }


        function dash_stf_daily_chart($d_school_id)
        {
            $data['d_school_id']=$d_school_id;

            $this->load->view('backend/admin/ajax/dash_stf_daily_chart',$data);
        }


        function dash_std_monthly_count($section_id,$d_school_id)
        {

            $data['section_id']=$this->uri->segment(3);
            $data['d_school_id']=$this->uri->segment(4);
            $this->load->view('backend/admin/ajax/dash_std_monthly_count',$data);

        }

        function dash_std_daily_attan($class_id,$date_val,$d_school_id)
        {

            $data['class_id']=$class_id;
            $data['date_val']=$date_val;
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/dash_std_daily_attan',$data);

        }

        function staff_name_chart($date_val,$d_school_id)
        {
            $data['date_val']=$date_val;
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/staff_name_chart',$data);
        }


        function branch_staff_name_chart($date_val,$d_school_id)
        {
            if($date_val!="" && $d_school_id!="")
            {
                $data['date_val']=$date_val;
                $data['d_school_id']=$d_school_id;
                $this->load->view('backend/admin/ajax/branch_staff_daily_home',$data);}

            else
            {
                echo "Please Select Branch And Date";
            }

        }

        function staff_daily_home($d_school_id)
        {
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/staff_daily_home',$data);
        }

        function student_daily_home($d_school_id)
        {
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/student_daily_home',$data);
        }


        function dash_single_staff_monthly($staff_id,$d_school_id)
        {

            $data['staff_id']=$staff_id;
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/dash_single_staff_monthly',$data);

        }


        function monthly_attandance_chart($staff_id,$d_school_id)
        {

            $data['status_val']=$staff_id;
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/monthly_attandance_chart',$data);

        }

        function student_monthly_status($section_id,$status_student,$d_school_id)
        {

            $data['status_val']=$status_student;
            $data['section_id']=$section_id;
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/student_monthly_status',$data);

        }

        function student_exam_result($section_id,$d_school_id)
        {

            $data['section_id']=$section_id;
            $data['d_school_id']=$d_school_id;

            if($data['section_id']=="")
            {
                echo "Please Select Any Section";
                exit();
            }

            $this->load->view('backend/admin/ajax/student_exam_result',$data);

        }

        function monthly_fee($section_id,$d_school_id)
        {
            $data['section_id']=$section_id;
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/monthly_fee',$data);

        }


        function monthly_fee_rev($section_id,$chalan_type,$d_school_id)
        {
            $data['section_id']=$section_id;
            $data['chalan_type']=$chalan_type;
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/monthly_fee_rev',$data);
        }


        function branch_monthly_fee_rev($chalan_type,$d_school_id){
            
            if($d_school_id!="")
            {
                $data['d_school_id']=$d_school_id;
                $this->load->view('backend/admin/ajax/branch_monthly_fee_rev',$data);
            }
            else
            {
                echo get_phrase('please_select_school');
            }
        }

        function class_section_count($d_school_id)
        {
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/class_section_count',$data);
        }


        function teacher_exam_progress($teacher_id_v,$d_school_id)
        {
            $data['teacher_id']=$teacher_id_v;
            $data['d_school_id']=$d_school_id;
            $this->load->view('backend/admin/ajax/teacher_exam_progress',$data);

        }
        function calendar_view($year=0,$month=0)
        {
            $date = array();
            $query_academic_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as date, ay.* FROM ".get_school_db().".acadmic_year ay
                    where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month ))
                    AND School_id = ".$_SESSION['school_id']."
                    UNION
                    SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as date, ay.* FROM ".get_school_db().".acadmic_year ay
                    where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month ))
                    AND School_id = ".$_SESSION['school_id']."";
            $query_academic_result = $this->db->query($query_academic_str)->result_array();
            if(count($query_academic_result)>0)
            {
                foreach ($query_academic_result as $key => $value)
                {
                    $d = $value['date'];
                    $date[$d] .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                }
            }

            /* Academic year End */

            /* Academic Terms  start */

            $query_academic_terms_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as term_date, ay.* FROM ".get_school_db().".yearly_terms ay
                WHERE ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month ))
                AND School_id = ".$_SESSION['school_id']."
                UNION
                SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as term_date, ay.* FROM ".get_school_db().".yearly_terms ay
                where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month ))
                AND School_id = ".$_SESSION['school_id']."";
            $query_academic_terms_result = $this->db->query($query_academic_terms_str)->result_array();
            //print_r($query_academic_result);
            if(count($query_academic_terms_result)>0)
            {
                foreach ($query_academic_terms_result as $key => $value) {
                    $d = $value['term_date'];
                    $date[$d] .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                }
            }
            /* Academic Terms End */

            /* Vacations start */
            $query_vacation_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as vacation_date, ay.* FROM ".get_school_db().".holiday ay
                    where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month ))
                    AND School_id = ".$_SESSION['school_id']."
                    UNION
                    SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as vacation_date, ay.* FROM ".get_school_db().".holiday ay
                    where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month ))
                    AND School_id = ".$_SESSION['school_id']."";
            $query_vacation_result = $this->db->query($query_vacation_str)->result_array();
            //print_r($query_academic_result);
            
            if(count($query_vacation_result)>0)
            {
                foreach ($query_vacation_result as $key => $value) {
                    $d = $value['vacation_date'];
                    $date[$d] .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                }
            }
            /* Vacations End */


            /* Exams Start */
            $query_exam_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as exam_date, ay.* FROM ".get_school_db().".exam ay
                               WHERE ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month ))
                               AND School_id = ".$_SESSION['school_id']."
                               UNION
                               SELECT 'End' as type, DATE_FORMAT(ay.start_date, '%e') as exam_date, ay.* FROM ".get_school_db().".exam ay
                               WHERE ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month ))
                               AND School_id = ".$_SESSION['school_id']."";
            $query_exam_result = $this->db->query($query_exam_str)->result_array();
            if(count($query_exam_result)>0) {
                foreach ($query_exam_result as $key => $value)
                {
                    $d = $value['exam_date'];
                    $exam_date = '';
                    if($value['type'] == 'Start')
                    {
                        $exam_date = date_view($value['start_date']);
                    }
                    if($value['type'] == 'End')
                    {
                        $exam_date = date_view($value['end_date']);
                    }
                    $date[$d] .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['name'].'">'.substr($value['name'],0,20).'<span style="color:white !important;font-size:11px;">('.$value['type'].'-' . $exam_date . ')</span></li>';
                }
            }
            /* Exams End */


            /* Notices year start */
            $query_notices_str = "SELECT DATE_FORMAT(create_timestamp, '%e') as notices_date , notice_title FROM ".get_school_db().".noticeboard
                                  WHERE ((DATE_FORMAT(create_timestamp, '%Y') = $year) AND (DATE_FORMAT(create_timestamp, '%c') = $month ))
                                  AND School_id = ".$_SESSION['school_id']."";

            $query_notices_result = $this->db->query($query_notices_str)->result_array();

            if(count($query_notices_result)>0) {
                foreach ($query_notices_result as $key => $value)
                {
                    $d = $value['notices_date'];
                    $date[$d] .='<li id = "example_'.$key .'" type="button" data-toggle="tooltip" data-placement="top" title="'.$value['notice_title'].'">'.substr($value['notice_title'],0,20).'</li>';
                }
            }
            
            /* Event Announcment Start*/
            $query_event_announce_str = "SELECT DATE_FORMAT(event_start_date, '%e') as eve_ann_date , event_title , event_start_date , event_end_date FROM ".get_school_db().".events_annoucments
                                         where ((DATE_FORMAT(event_start_date, '%Y') = $year) AND (DATE_FORMAT(event_start_date, '%c') = $month ))
                                         AND school_id = ".$_SESSION['school_id']." ";
            $query_event_announce_result = $this->db->query($query_event_announce_str)->result_array();
            // print_r($query_event_announce_result);
            if(count($query_event_announce_result)>0) {
                foreach ($query_event_announce_result as $key => $value)
                {
                    $d = $value['eve_ann_date'];
                    $date[$d] .='<li type="button" data-toggle="tooltip" data-placement="top" style="font-size: 11px;" title="Event Announcement"> <a href="'.base_url().'event_annoucments/events_program"> '.substr($value['event_title'],0,20).' <br> Start: '.$value['event_start_date'].' <br> End: '.$value['event_end_date'].' </a><span></span></li>';
                }
            }
            /* Event Announcment End */
        
            $current_day = date("j");
            $date[$current_day] .= '<a type="button" style="color: #00bee7;" href="'.base_url().'dashboard/view_today_schedule" data-toggle="tooltip" data-placement="top" title="View Today Schedule">View Schedule </a>';
            
            echo $this->calendar->generate($year,$month,$date);
            $this->load->view('backend/admin/ajax/calendar_view',$date);
        }
        
        function view_today_schedule(){
            if (!$_SESSION['user_login'])
                redirect(base_url());
            $page_data['page_name']  = 'today_schedule';
            $page_data['page_title'] = get_phrase('today_schedule');

            $this->load->view('backend/index', $page_data);
        }
        function events_list(){
            // echo "123";exit;
            $year = date("Y");
            $month = date("m");
            $day = date("d");
          $date = "";
          $query_academic_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as date, ay.* FROM ".get_school_db().".acadmic_year ay
                    where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                    AND School_id = ".$_SESSION['school_id']."
                    UNION
                    SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as date, ay.* FROM ".get_school_db().".acadmic_year ay
                    where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                    AND School_id = ".$_SESSION['school_id']."";
          $query_academic_result = $this->db->query($query_academic_str)->result_array();
          // print_r($query_academic_result);
            if(count($query_academic_result)>0)
            {
                foreach ($query_academic_result as $key => $value)
                {
                    $date .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                }

            }

            /* Academic year End */

            /* Academic Terms  start */

          $query_academic_terms_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as term_date, ay.* FROM ".get_school_db().".yearly_terms ay
                    WHERE ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                    AND School_id = ".$_SESSION['school_id']."
                    UNION
                    SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as term_date, ay.* FROM ".get_school_db().".yearly_terms ay
                    where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                    AND School_id = ".$_SESSION['school_id']."";

            $query_academic_terms_result = $this->db->query($query_academic_terms_str)->result_array();
            //print_r($query_academic_result);
            if(count($query_academic_terms_result)>0)
            {
                foreach ($query_academic_terms_result as $key => $value) {
                    $date .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                }

            }
            /* Academic Terms End */

            /* Vacations  start */

            $query_vacation_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as vacation_date, ay.* FROM ".get_school_db().".holiday ay
                    where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                    AND School_id = ".$_SESSION['school_id']."
                    UNION
                    SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as vacation_date, ay.* FROM ".get_school_db().".holiday ay
                    where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                    AND School_id = ".$_SESSION['school_id']."";

            $query_vacation_result = $this->db->query($query_vacation_str)->result_array();
            //print_r($query_academic_result);
            if(count($query_vacation_result)>0)
            {
                foreach ($query_vacation_result as $key => $value) {
                    $date .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';

                }

            }
            /* Vacations End */
            /* exam event start */

            $query_exam_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as exam_date, ay.* FROM ".get_school_db().".exam ay
                    where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                    AND School_id = ".$_SESSION['school_id']."
                    UNION
                    SELECT 'End' as type, DATE_FORMAT(ay.start_date, '%e') as exam_date, ay.* FROM ".get_school_db().".exam ay
                    where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                    AND School_id = ".$_SESSION['school_id']."";

            $query_exam_result = $this->db->query($query_exam_str)->result_array();
            //print_r($query_academic_result);
            if(count($query_exam_result)>0) {
                foreach ($query_exam_result as $key => $value)
                {
                    $date .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['name'].'">'.substr($value['name'],0,20).'<span>(' . $value['type'] . ')</span></li>';


                }
            }
            /* exam event end End */
            /* Notices year start */
            $query_notices_str = "SELECT DATE_FORMAT(create_timestamp, '%e') as notices_date , notice_title FROM ".get_school_db().".noticeboard
                                         where ((DATE_FORMAT(create_timestamp, '%Y') = $year) AND (DATE_FORMAT(create_timestamp, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                                         AND School_id = ".$_SESSION['school_id']."";

            $query_notices_result = $this->db->query($query_notices_str)->result_array();

            if(count($query_notices_result)>0) {
                foreach ($query_notices_result as $key => $value)
                {
                    $date .='<li id = "example_'.$key .'" type="button" data-toggle="tooltip" data-placement="top" title="'.$value['notice_title'].'">'.substr($value['notice_title'],0,20).'</li>';
                }
            }
            echo $date;
            
        }
        
        function staff_birthday()
        {
            $qur = $this->db->query("select name , dob from ".get_school_db().".staff 
            where school_id=".$_SESSION['school_id']." AND DATE_FORMAT(dob, '%m-%d') =  DATE_FORMAT(CURDATE(), '%m-%d') ")->result();
      	    $page_data['staff_birthday']= $qur;
      		    
            $page_data['page_name']    = 'staff_birthday';
            $page_data['page_title']   = get_phrase('staff_birthday');
            $this->load->view('backend/index', $page_data);
        }
        
        function search_student()
    	{
    		$phrase = $this->input->post('phrase');
    		if(is_numeric($phrase))
    		{
    		    $condition = "roll LIKE '$phrase%' ";
    		}else
    		{
    		    $condition = "name LIKE '$phrase%' ";
    		}
    		$teacher_section_query ="";
    		$ids = "";
    		if($_SESSION['login_type'] == 3){
    		    $this->load->helper('teacher');
    		    $section_ids = get_teacher_section($_SESSION['login_detail_id']);
    		    
    		    $ids = implode(',', array_unique($section_ids));
    		    $teacher_section_query = "and section_id in (".$ids.")";
    		}
    		
    		$qur = "select student_id,image, name FROM ".get_school_db().".student WHERE $condition and school_id=".$_SESSION['school_id']." $teacher_section_query ";
    		$results = $this->db->query($qur)->result_array();
            $std_data = '';
    		$std_data = '<h4 class="notify_heading">Search Result</h4>';
    		if(count($results) > 0){
    		    foreach ($results as $key => $value) {
        		    $img = '';
        		    if($value['image'] == '')
        		    { 
        		        $img = base_url().'/uploads/default.png';  
        		    }else{ 
        		        $img = display_link($value['image'],'student');
        		    }
        		    
        			$std_data .= "
        			        <li class='notification-success unread'>
                                <a href='".base_url()."dashboard/student_details_view/".str_encode($value['student_id'])."' target='_blank'>
                                  <span class='pull-left image'>
                                    <img alt='' class='img-circle search_dropdown_image' src='".$img."' width='44'> 
                                  </span>
                                  <span class='line'>
                                    <strong>
                                      ".$value['name']."
                                    </strong>
                                  </span>
                                </a>
                            </li>
        			        ";
    		    }
    		}else
    		{
    		    $std_data .= "
    			        <li class='notification-success unread'>
                            <a href='#'>
                              <span class='line'>
                                <strong>
                                  Record Not Found
                                </strong>
                              </span>
                            </a>
                        </li>
    			        ";
    		}
    		echo $std_data;
    	}
    	
    	function student_details_view($stud_id)
    	{
    	    $student_id = str_decode($stud_id);
    		$qur = "select * FROM ".get_school_db().".student WHERE student_id = ".$student_id." ";
    		$results = $this->db->query($qur)->row();
    		
    		$page_data['date']      = $this->input->post('date');
    	    $page_data['month']     = $this->input->post('month');;
    	    $page_data['year']      = $this->input->post('year');;
    	    $page_data['class_id']  = $this->input->post('class_id');;
    		
    		$page_data['data']    = $results;
    		$page_data['page_name']    = 'student_details_view';
            $page_data['page_title']   = get_phrase('student_details_view');
            $this->load->view('backend/index', $page_data);
    	}
    	
    	function get_student_attendance()
    	{
    	    $this->load->view('backend/admin/ajax/get_stud_attendance.php');
    	}
    	
    	function get_student_chalan_details()
        {
            $student_id  = $this->input->post('student_id');
            $data['student_id']      = $student_id;
            $this->load->view("backend/admin/ajax/get_chalan_listing",$data);
        }
        
    	function get_student_subjects()
    	{
    	    $student_id = $this->input->post('student_id');
    	    $sect_id = get_student_section_id($student_id);
            $sub_arr = $this->db->query(" select sub.* , sc.title as subj_categ from ".get_school_db().".subject sub inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
            inner join ".get_school_db().".subject_category sc on sub.subj_categ_id = sc.subj_categ_id
            where ss.section_id = ".$sect_id." and ss.school_id = ".$_SESSION['school_id']." ")->result_array();
            
           
            $result ="";
            if(count($sub_arr) > 0){
                foreach($sub_arr as $sub)
                {
                    $result .= ' 
                      <div class="flex-column" style="padding: 15px;border: 1px solid bisque; font-size: 18px;font-weight: bold;color: black;">Subject : '.$sub['name'].'
                        <p>Code : <small>'.$sub['code'].'</small></p>
                        <span class="badge badge-danger badge-pill">Category : '.$sub['subj_categ'].'</span>
                      </div>';
                }
            }else{
                $result = '<li>No Record Found</li>';;
            }
            echo $result;
    	}
    	
    	function get_student_subjectsdiary()
    	{
    	    $student_id = $this->input->post('student_id');
    	    $sect_id = get_student_section_id($student_id);
            $sub_arr = $this->db->query(" select sub.*,ss.section_id from ".get_school_db().".subject sub inner join ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
            where ss.section_id = ".$sect_id." and ss.school_id = ".$_SESSION['school_id']." ")->result_array();
            $result ="";
            if(count($sub_arr) > 0){
                foreach($sub_arr as $sub)
                {
                    $row=$sub;
                    ?>
                <div class="diary-tree"> 
                    <ul class="diary-ul"> 
                        <li ><span onclick="getsubdiary(<?php echo $row['subject_id'];?>,<?php echo $row['section_id'];?>)"><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#subjects<?php echo $row['subject_id'];?>" aria-expanded="true" aria-controls="subjects"><i class="collapsed"><i class="fas fa-folder"></i></i>
                        <i class="expanded"><i class="far fa-folder-open"></i></i><?php echo $row['name']; ?> (<?php echo $row['code'];?>) </a></span> 
                        <div id="subjects<?php echo $row['subject_id'];?>" class="collapse">
                            <ul class="subjects<?php echo $row['subject_id'];?>"> 
                            </ul>
                        </div> 
                        </li> 
                    </ul> 
                </div> 
                    <?php
                }
                }else{
                    $result = '<li>No Record Found</li>';;
                }
            echo $result;
    	} 
        	
        function getsubdiary()
        { 
            $data = "";
            $stu_id  = $this->input->post('student_id');
            $subject_id  = $this->input->post('subject_id');
            $section_id  = $this->input->post('section_id');
            
            $qur = "select * from ".get_school_db().".diary d 
            INNER JOIN  ".get_school_db().".diary_student ds ON ds.diary_id = d.diary_id 
            where d.subject_id = ".$subject_id." and d.section_id = ".$section_id." and d.school_id = ".$_SESSION['school_id']." and ds.student_id = $stu_id "; 
            
            $arr = $this->db->query($qur)->result_array();
    
            if(count($arr) > 0)
            {
                foreach($arr as $row)
                {
                    $data .= '<li onclick="getdiarycontent('.$row['diary_id'].')"> <span><i class="far fa-file"></i> '.$row['title'].' </span> </li>';
                }
            }else
            {
                $data .= '<li> <span><i class="far fa-file"></i> No Diary </span> </li>';
            }    
            echo $data;
        }
        
        function getdiarycontent()
        {
            $diary_id  = $this->input->post('diaryb_id');
            $qur = "select d.* , d.diary_id as d_id , aud.* , ds.* from ".get_school_db().".diary d  
            left join ".get_school_db().".diary_audio aud on aud.diary_id = d.diary_id
            left join ".get_school_db().".diary_student ds on d.diary_id = ds.diary_id
            where d.diary_id =  $diary_id and d.school_id = ".$_SESSION['school_id']." "; 
            $arr = $this->db->query($qur)->row();
            
            $page_data['arr'] = $arr;
            
            $this->load->view('backend/admin/ajax/profile_diary_view' , $page_data);
            
        }
        	
    	function get_student_assessments()
    	{
    	    $student_id = $this->input->post('student_id');
    	    
    	    $assessment_arr = $this->db->query("select a.* , au.* , res.* , s.name , stf.name as teacher_name ,s.name as subject_name , s.code as subject_code  FROM ".get_school_db().".assessments a 
    	    inner join ".get_school_db().".assessment_audience au on a.assessment_id = au.assessment_id 
    	    left join ".get_school_db().".assessment_result res on res.assessment_id = a.assessment_id
    	    inner join ".get_school_db().".subject s on s.subject_id = au.subject_id
    	    left join ".get_school_db().".staff stf on stf.staff_id = a.teacher_id
    	    where a.school_id = ".$_SESSION['school_id']." and au.student_id = ".$student_id." order by a.assessment_id desc ")->result_array();
    	    
            $page_data['assessment_arr'] = $assessment_arr;
            
            $this->load->view('backend/admin/ajax/profile_assessment_view' , $page_data);
    	    
    	}
    	
    	function get_exam_marksheet()
    	{
    	    $student_id = $this->input->post('student_id');
    	    $exam_id = $this->input->post('exam_id');
    	    
    	    
    	   // query that gives examwise result of a student
    	   // $qur1 = "select sum(`marks_obtained`) as total_marks,s.student_id, m.exam_id ,e.name from ".get_school_db().".marks m 
    	   // inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id 
    	   // inner join ".get_school_db().".student s on m.student_id=s.student_id 
    	   // inner join ".get_school_db().".exam e on m.exam_id=e.exam_id 
    	   // inner join ".get_school_db().".yearly_terms yt on e.yearly_terms_id = yt.yearly_terms_id 
    	   // inner join ".get_school_db().".acadmic_year a on a.academic_year_id = yt.academic_year_id where m.student_id= ".$student_id." and m.school_id= ".$_SESSION['school_id']." group by exam_id";
    	    
    	   // $exam_arr = $this->db->query($qur1)->result_array();
    	    
    	    
    	    
    	    $exam_arr = $this->db->query("SELECT m.* , mc.* , s.name as subject_name , s.code as subject_code, e.name as exam_name , sc.title as subject_component FROM ".get_school_db().".marks m 
    	    left join ".get_school_db().".marks_components mc on m.marks_id = mc.marks_id
    	    left join ".get_school_db().".subject s on s.subject_id = m.subject_id
    	    left join ".get_school_db().".exam e on e.exam_id = m.exam_id 
    	    left join ".get_school_db().".subject_components sc on mc.subject_component_id = sc.subject_component_id
    	    where m.school_id = ".$_SESSION['school_id']." and m.student_id = ".$student_id." and e.exam_id = ".$exam_id." ")->result_array();
    	    
    	   // echo "<pre>";
    	   // print_r($exam_arr);
    	    
            $result ="";
            if(count($exam_arr) > 0){
               $result .= '<table>';
                foreach($exam_arr as $exm)
                {
                    $result .= '<tr><td class="list-group-item">'.$exm["exam_name"].$exm["subject_name"].'</td> </tr>';
                
                }
                $result .= '</table>';
            }else{
                    $result = '<span>No Record Found</span>';
            }
            echo $result; 
    	    
    	}
    	
    	
    	
    	
        
    }