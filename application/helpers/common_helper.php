<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
////session_start();

function http_response_codes($code = NULL) {
    if ($code !== NULL) {
        switch ($code) {
            case 100: $text = 'Continue';
                break;
            case 101: $text = 'Switching Protocols';
                break;
            case 200: $text = 'OK';
                break;
            case 201: $text = 'Created';
                break;
            case 202: $text = 'Accepted';
                break;
            case 203: $text = 'Non-Authoritative Information';
                break;
            case 204: $text = 'No Content';
                break;
            case 205: $text = 'Reset Content';
                break;
            case 206: $text = 'Partial Content';
                break;
            case 300: $text = 'Multiple Choices';
                break;
            case 301: $text = 'Moved Permanently';
                break;
            case 302: $text = 'Moved Temporarily';
                break;
            case 303: $text = 'See Other';
                break;
            case 304: $text = 'Not Modified';
                break;
            case 305: $text = 'Use Proxy';
                break;
            case 400: $text = 'Bad Request';
                break;
            case 401: $text = 'Unauthorized';
                break;
            case 402: $text = 'Payment Required';
                break;
            case 403: $text = 'Forbidden';
                break;
            case 404: $text = 'Not Found';
                break;
            case 405: $text = 'Method Not Allowed';
                break;
            case 406: $text = 'Not Acceptable';
                break;
            case 407: $text = 'Proxy Authentication Required';
                break;
            case 408: $text = 'Request Time-out';
                break;
            case 409: $text = 'Conflict';
                break;
            case 410: $text = 'Gone';
                break;
            case 411: $text = 'Length Required';
                break;
            case 412: $text = 'Precondition Failed';
                break;
            case 413: $text = 'Request Entity Too Large';
                break;
            case 414: $text = 'Request-URI Too Large';
                break;
            case 415: $text = 'Unsupported Media Type';
                break;
            case 500: $text = 'Internal Server Error';
                break;
            case 501: $text = 'Not Implemented';
                break;
            case 502: $text = 'Bad Gateway';
                break;
            case 503: $text = 'Service Unavailable';
                break;
            case 504: $text = 'Gateway Time-out';
                break;
            case 505: $text = 'HTTP Version not supported';
                break;
            default:
                exit('Unknown http status code "' . htmlentities($code) . '"');
                break;
        }
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol . ' ' . $code . ' ' . $text);
        $GLOBALS['http_response_code'] = $code;
    } else {
        $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
    } 
    return $code;
}

if (!function_exists('get_email')) {
    
    function get_religion( $religion='')
    {
    	$type = array(
    			'Muslim'     => '1',
    			'Christian'  => '2',
    			'Hindu'      => '3',
    			'Sikh'       => '4',
    			'Other'      => '5'
    		);
    	return $type[$religion];
    }
    
    
    
    function get_school_virtual_platform()
    {
        $CI =& get_instance();
        $CI->load->database(); 
        $query  = "select vc_platform_id from " . get_system_db() . ".system_school where sys_sch_id = ".$_SESSION['sys_sch_id']." ";
        $result =  $CI->db->query($query);
        $row    =  $result->row();
        return     $row->vc_platform_id;
    }
    
    function get_specific_acadamic_planner_counts($subject_id , $date_month , $date_year){
        $CI =& get_instance();
        $CI->load->database(); 
        
        $query = "select count(planner_id) as total_records from " . get_school_db() . ".academic_planner where subject_id = $subject_id 
                  AND MONTH(start) = '$date_month' AND  YEAR(start) = '$date_year' AND school_id = ".$_SESSION['school_id']." ";
        $result=  $CI->db->query($query);
        $row   =  $result->row();
        return    $row->total_records;
    }
    
    // Get Total Student Chalan
    function get_student_challan_fee_details_for_parent($student_id , $fee_month=0 , $fee_year, $scf_id){


        $CI =& get_instance();
        $CI->load->database();
        
        if($fee_month == ""){
            $fee_month = 0;
        }
       
        $get_c_c_f_id = $CI->db->query("SELECT c_c_f_id,due_date FROM ".get_school_db().".student_chalan_form WHERE s_c_f_id = $scf_id and school_id = '".$_SESSION['school_id']."' and is_cancelled = 0 and status > 3 ")->row();
       
        $c_c_f_id     = $get_c_c_f_id->c_c_f_id;
        $my_month_year = date('Y-m',strtotime($get_c_c_f_id->due_date));
        
        $query = $CI->db->query("select scd.*,dl.discount_id from ".get_school_db().".student_chalan_detail scd
                                        LEFT JOIN ".get_school_db().".discount_list dl ON dl.fee_type_id = scd.type_id
                                        where scd.s_c_f_id=$scf_id and scd.type != 2 and scd.school_id=".$_SESSION['school_id']);
        $discount_calculation = 0;

        foreach($query->result_array() as $get_fee){
            $totle = $get_fee['amount'];
            
            $check_alread_discount = $CI->db->query("SELECT discount_amount_type,amount,title FROM ".get_school_db().".student_fee_settings WHERE student_id = '$student_id' AND month = '$fee_month' AND year = '$fee_year' AND fee_type = 2 AND fee_type_id = '".$get_fee['discount_id']."' ");
            if($check_alread_discount->num_rows() > 0){
                $single_discount_data_temp = $check_alread_discount->result_array();       
                foreach($single_discount_data_temp as $single_disco){
                    if($single_disco['discount_amount_type'] == '1')
                    {
                       $single_discount_percent = $single_disco['amount'];
                    }else if($single_disco['discount_amount_type'] == '0'){
                       $single_discount_percent = round(($totle / 100) * $single_disco['amount']);
                    }
                    $single_discount_calculation += $single_discount_percent;
                }
            }
            
            $totle_amut += $totle;
        }
        
        // Arrears Display'
        $arreas_calculation = 0;
        $arreas_total_amount = 0;
        $get_arrears_query = $CI->db->query("SELECT * FROM ".get_school_db().".student_chalan_form WHERE student_id = '$student_id' AND arrears_status = 1 AND s_c_f_id <> '$scf_id'");
        foreach($get_arrears_query->result_array() as $get_arrears_data):
            $make_format = '01-'.$get_arrears_data["s_c_f_month"].'-'.$get_arrears_data["s_c_f_year"];
            $arrears_month_year = date("M-Y",strtotime($make_format));
            $arrears_amount = $get_arrears_data["arrears"];
            $arreas_total_amount += $arrears_amount;    
        endforeach;
        // End Arrears
        
        $unpaid_challan_total_amount = 0;
        $get_unpaid_challan_query = $CI->db->query("SELECT * FROM ".get_school_db().".student_chalan_form WHERE student_id = '$student_id' AND status = 4 AND form_type = 2 AND s_c_f_id <> '$scf_id' AND DATE_FORMAT(due_date, '%Y-%m') < '$my_month_year'");
        // echo $CI->db->last_query();
        foreach($get_unpaid_challan_query->result_array() as $get_unpaid_challan_data):
            $make_format = '01-'.$get_unpaid_challan_data["s_c_f_month"].'-'.$get_unpaid_challan_data["s_c_f_year"];
            $unpaid_challan_month_year = date("M-Y",strtotime($make_format));
            $unpaid_challan_amount = $get_unpaid_challan_data["actual_amount"];
            $unpaid_challan_total_amount += $unpaid_challan_amount;    
        endforeach;
        
        $arreas_calculation = $arreas_total_amount+$unpaid_challan_total_amount;
        $total_amount = $arreas_calculation+$totle_amut-$single_discount_calculation;
        
        return $total_amount;
    }
    
    
    // Get Total Discount (Custom / Class Wise) Student Chalan
    function get_student_challan_discount_calculation($student_id , $fee_month=0 , $fee_year,$scf_id){

        $CI =& get_instance();
        $CI->load->database();
        
        if($fee_month == ""){
            $fee_month = 0;
        }
       
        $get_c_c_f_id = $CI->db->query("SELECT c_c_f_id FROM ".get_school_db().".student_chalan_form WHERE s_c_f_id = $scf_id and school_id = '".$_SESSION['school_id']."' and is_cancelled = 0 and status > 3 ")->row();
        $c_c_f_id     = $get_c_c_f_id->c_c_f_id;
        
        $query = $CI->db->query("select scd.*,dl.discount_id from ".get_school_db().".student_chalan_detail scd
                                LEFT JOIN ".get_school_db().".discount_list dl ON dl.fee_type_id = scd.type_id
                                where scd.s_c_f_id=$scf_id and scd.type != 2 and scd.school_id=".$_SESSION['school_id']);
        $single_discount_calculation = 0;
        
        foreach($query->result_array() as $get_fee){
            $totle = $get_fee['amount'];
            $check_alread_discount = $CI->db->query("SELECT discount_amount_type,amount,title FROM ".get_school_db().".student_fee_settings WHERE student_id = '$student_id' AND month = '$fee_month' AND year = '$fee_year' AND fee_type = 2 AND fee_type_id = '".$get_fee['discount_id']."' ");
            if($check_alread_discount->num_rows() > 0){
                $single_discount_data_temp = $check_alread_discount->result_array();       
                foreach($single_discount_data_temp as $single_disco){
                    if($single_disco['discount_amount_type'] == '1')
                    {
                       $single_discount_percent = $single_disco['amount'];
                    }else if($single_disco['discount_amount_type'] == '0'){
                       $single_discount_percent = round(($totle / 100) * $single_disco['amount']);
                    }
                    $single_discount_calculation += $single_discount_percent;
                }
            }
        }
        
        return $single_discount_calculation;
    }
    
    function get_student_discount_list($student_id , $fee_month=0 , $fee_year,$scf_id,$fee_type_id){

        $CI =& get_instance();
        $CI->load->database();
        
        if($fee_month == ""){
            $fee_month = 0;
        }
       
        $get_c_c_f_id = $CI->db->query("SELECT c_c_f_id FROM ".get_school_db().".student_chalan_form WHERE s_c_f_id = $scf_id and school_id = '".$_SESSION['school_id']."' and is_cancelled = 0 and status > 3 ")->row();
        $c_c_f_id     = $get_c_c_f_id->c_c_f_id;
        $output = 0;
        $query = $CI->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id = $scf_id and type != 2 and school_id=".$_SESSION['school_id']);
        $discount_calculation = 0;
        
        foreach($query->result_array() as $get_fee){
            $totle = $get_fee['amount'];
            $check_alread_discount = $CI->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '$student_id' AND month = '$fee_month' AND year = '$fee_year' AND fee_type = 2 AND fee_type_id = '$fee_type_id'");
            if($check_alread_discount->num_rows() > 0)
            {
    
                $single_discount_data_temp = $CI->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '$student_id' AND month = '$fee_month' AND year = '$fee_year' AND fee_type = 2 AND fee_type_id = '$fee_type_id' ")->result_array();
                
                $single_discount_calculation = 0;
                  foreach($single_discount_data_temp as $single_disco){
                      if($single_disco['discount_amount_type'] == '1')
                      {
                          $single_discount_percent = $single_disco['amount'];
                          $output .= "<b>".$single_disco['title']."</b> - " . $single_discount_percent.'<br>';
                      }else if($single_disco['discount_amount_type'] == '0' || $single_disco['discount_amount_type'] == NULL){
                          $single_discount_percent = round(($totle / 100) * $single_disco['amount']);   
                          $output .= "<b>".$single_disco['title']."</b> - " . $single_discount_percent.'<br>';
                      }
                    // $single_discount_calculation += $single_discount_percent;
                  }
                //   $single_other_discount_plus = $single_discount_calculation;    
            }
            
        }
        
        return $output;
    }
    function get_student_discount_list_text($student_id , $fee_month=0 , $fee_year,$scf_id){

        $CI =& get_instance();
        $CI->load->database();
        
        if($fee_month == ""){
            $fee_month = 0;
        }
       
        $get_c_c_f_id = $CI->db->query("SELECT c_c_f_id FROM ".get_school_db().".student_chalan_form WHERE s_c_f_id = $scf_id and school_id = '".$_SESSION['school_id']."' and is_cancelled = 0 and status > 3 ")->row();
        $c_c_f_id     = $get_c_c_f_id->c_c_f_id;
        
        $query = $CI->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id = $scf_id and type != 2 and school_id=".$_SESSION['school_id']);
        $discount_calculation = 0;
        
        foreach($query->result_array() as $get_fee){
            $totle = $get_fee['amount'];
            $check_alread_discount = $CI->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '$student_id' AND month = '$fee_month' AND year = '$fee_year' AND fee_type = 2 AND fee_type_id = '".$get_fee['type_id']."' AND is_bulk = 0");
            if($check_alread_discount->num_rows() > 0)
            {
    
                $single_discount_data_temp = $CI->db->query("SELECT * FROM ".get_school_db().".student_fee_settings WHERE student_id = '$student_id' AND month = '$fee_month' AND year = '$fee_year' AND fee_type = 2 AND fee_type_id = '".$get_fee['type_id']."' AND is_bulk = 0 ")->result_array();
                $single_discount_calculation = 0;
                  foreach($single_discount_data_temp as $single_disco){
                      if($single_disco['discount_amount_type'] == '1')
                      {
                          $single_discount_percent = $single_disco['amount'];
                          echo $single_disco['title']." - " . $single_discount_percent;
                      }else if($single_disco['discount_amount_type'] == '0'){
                          $single_discount_percent = round(($totle / 100) * $single_disco['amount']);   
                          echo $single_disco['title']." - " . $single_discount_percent;
                      }
                    $single_discount_calculation += $single_discount_percent;
                  }
                  $single_other_discount_plus = $single_discount_calculation;    
            }else{
                $my_discount_data_temp = $CI->db->query("SELECT * FROM ".get_school_db().".class_chalan_discount ccd LEFT JOIN ".get_school_db().".discount_list dl ON dl.discount_id = ccd.discount_id WHERE c_c_f_id = '$c_c_f_id' AND fee_type_id = '".$get_fee['type_id']."' ")->result_array();
                
                foreach($my_discount_data_temp as $disco){
                    $discount_percent = round(($totle / 100) * $disco['value']);
                    echo $disco['title']." - " . $discount_percent;
                }
            }
            
        }
    }
    
    function get_school_name($school_id){
        $CI =& get_instance();
        $CI->load->database(); 
        $query = $CI->db->query("select name from " . get_school_db() . ".school where school_id = $school_id");
        $row   = $query->row();
        return   $row->name;
    }
    function get_system_subjects_list()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $query = $CI->db->query("select subject_id,subject_name from " . get_system_db() . ".qb_subjects");
        $result = $query->result_array();
        $str  =  '<select class="form-control" id="subject_id" name="subject_id" required="required">';
        $str .=  '<option value="">'.get_phrase('select_subject').'</option>';
    	if(count($result) > 0){
        	foreach($result as $row)
        	{
        		$str .= '<option value="'.$row['subject_id'].'">'.$row['subject_name'].'</option>';
        	}  
    	}
		$str .= '</select>';
	    return $str; 
    }
    function get_system_classes_list()
    {
        
        $CI =& get_instance();
        $CI->load->database();
        
        $query  = $CI->db->query("select class_id,class_name from " . get_system_db() . ".qb_classes");
        $result = $query->result_array();
        
        $str  = '<select class="form-control" id="class_id" name="class_id" required="required">';
        $str .= '<option value="">'.get_phrase('select_class').'</option>';
    	if(count($result) > 0){
        	foreach($result as $row)
        	{
        		$str .= '<option value="'.$row['class_id'].'">'.$row['class_name'].'</option>';
        	}  
    	}
    	
		$str .= '</select>';
	    return $str; 
        
    }
    function get_current_version()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $query = $CI->db->query("select version_no from " . get_system_db() . ".application_version where IsCurrentVersion = 1");
        $row =  $query->row();
        if($row != null){
            return $row->version_no;
        }
        else
        {
            return "";
        }
        
    }

    function get_diary_student_attachments_count($diary_student_id){
        $CI =& get_instance();
        $CI->load->database();
        
        $query = $CI->db->query("select count(id) as total_records from " . get_school_db() . ".diary_attachments where diary_student_id = $diary_student_id");
        $row   = $query->row(); 
        return $row->total_records;
    }
    function get_diary_student_attachments($diary_student_id)
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $query = $CI->db->query("select GROUP_CONCAT(answer_attachment) as urls from " . get_school_db() . ".diary_attachments where diary_student_id = $diary_student_id");
        $row   = $query->row();
        return $row->urls;
    }
    function check_if_parent_user_group_assigned($parent_id)
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $school_id = $_SESSION['school_id'];
        $query     = $CI->db->query("select user_rights_id from " . get_school_db() . ".user_rights where parent_id = $parent_id and school_id = $school_id");
        $row       = $query->row();
        
        if($row != null){
            return false;
        }
        else
        {
            return true;
        }
        
    }
    function check_if_user_group_assigned($student_id)
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $school_id = $_SESSION['school_id'];
        $query = $CI->db->query("select user_rights_id from " . get_school_db() . ".user_rights where student_id = $student_id and school_id = $school_id");
        $row =  $query->row();
        if($row != null){
            return false;
        }
        else
        {
            return true;
        }
        
    }
    function file_upload_notes($file_name = "" , $temp_name="")
    {
        $base_path = 'uploads/' . $_SESSION['folder_name'] . '/lecture_notes';
    
        if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
            return "";
        } 
        elseif ($file_name == "") {
            return "";
        } 
        elseif ($is_root == 1) {
            $base_path = 'uploads/' . $_SESSION['folder_name'];
        }
        if (!is_dir($base_path)) {
            mkdir($base_path, 0777, true);
        }
        
        $curr_date = date('Y-m-d');
        $six_digit_random_number = mt_rand(100000, 999999);
        $upload_path = 'uploads/' . $_SESSION['folder_name'] . '/lecture_notes/'.$curr_date;
        
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        $prefix = 'note_attachment';
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file = $prefix . '_' . time() .$six_digit_random_number .'.' . $ext;
        move_uploaded_file($temp_name, $upload_path . '/' . $new_file);
        return $upload_path.'/'.$new_file;
 
    }
    function file_upload_landing_page($file_name = "" , $temp_name="" , $folder_name="")
    {
        $base_path = 'assets/landing_pages/'.$folder_name;
    
        if (!is_dir($base_path)) {
            mkdir($base_path, 0777, true);
        }
        
        $curr_date               = date('Y-m-d');
        $six_digit_random_number = mt_rand(100000, 999999);
        $prefix                  = 'attc';
        $ext                     = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file                = $prefix . '_' . time() .$six_digit_random_number .'.' . $ext;
        move_uploaded_file($temp_name, $base_path . '/' . $new_file);
        return $new_file;
 
    }
    function get_assigned_notes_sections($notes_id){
        
        $CI =& get_instance();
        $CI->load->database();
    
        $query_str = "select distinct lna.section_id from " . get_school_db() . ".lecture_notes_audience lna 
                      join " . get_school_db() . " .lecture_notes ln on ln.notes_id = lna.notes_id
                      where ln.notes_id = $notes_id";
                 
        $query = $CI->db->query($query_str)->result_array();
        return $query;
    }
    function get_assigned_notes_students($section_id , $notes_id){
   
        $CI =& get_instance();
        $CI->load->database();
        $query = "SELECT lna.student_id , s.name , s.roll FROM " . get_school_db() . ".lecture_notes_audience lna 
                  join " . get_school_db() . ".student s on s.student_id = lna.student_id where lna.section_id = $section_id
                  and lna.notes_id = $notes_id";
        $query = $CI->db->query($query)->result_array();
        return $query;
    
    }
    
    
    function get_notes_row($notes_id)
    {
        $CI =& get_instance();
        $CI->load->database();
        $query = $CI->db->query("select * from " . get_school_db() . ".lecture_notes where notes_id = $notes_id");
        return $query->row();
        
    }

    function get_email($email = "", $action = "", $user_login_id = 0)
    {
        $CI =& get_instance();
        $CI->load->database();
        if ($action == 'update') {
            $query = $CI->db->query("select email from " . get_system_db() . ".user_login where email='$email' and user_login_id <> $user_login_id ");
        } else {
            $query = $CI->db->query("select email from " . get_system_db() . ".user_login where email='$email'");
        }


        if ($query->num_rows() > 0) {
            echo "yes";
        } else {
            echo "no";
        }

    }
}

function get_default_pic()
{
    return base_url() . 'uploads/default_pic.png';
}

function get_designation_name($designation_id=0)
{
    $CI =& get_instance();
    $CI->load->database();

    $details = $CI->db->query("select title from " . get_school_db() . ".designation where designation_id = $designation_id")->row();
    return $details->title;
}

function get_matching_question_option($question_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $details = $CI->db->query("select * from " . get_school_db() . ".matching_question_option where question_id = $question_id")->result_array();
    return $details;
}

function get_matching_question_option_system($question_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $details = $CI->db->query("select * from " . get_system_db() . ".qb_matching_question_option 
                               where question_id = $question_id")->result_array();

    return $details;
}


function get_matching_question_option_solution($question_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $q = "select opt.* , sol.* from " . get_school_db() . ".matching_question_option opt 
          join " . get_school_db() . ".assessment_matching_solution sol on (opt.matching_question_option_id = sol.matching_question_option_id) 
          where opt.question_id = $question_id";
                         
    $details = $CI->db->query($q)->result_array();
    return $details;
}

function get_assessment_time($assessment_id){
    
    $CI=& get_instance();
	$CI->load->database();
	
     $query =  "select distinct aud.assessment_id , cs.title as section_name , cls.name as class_name , aud.start_time 
                , sub.name as subject_name , sub.code as subject_code,
                dept.title as department_name , aud.end_time , aud.assessment_date FROM ".get_school_db().".assessment_audience aud 
                join ".get_school_db().".assessments assm on (aud.assessment_id = assm.assessment_id)
                join ".get_school_db().".class_section cs on (cs.section_id = aud.section_id)
                join ".get_school_db().".class cls on (cls.class_id = cs.class_id)
                join ".get_school_db().".departments dept on (dept.departments_id = cls.departments_id)
                join ".get_school_db().".subject sub on (sub.subject_id = aud.subject_id)
                where 
                assm.school_id=".$_SESSION['school_id']."
                and aud.assessment_id = $assessment_id and assm.is_completed = 1";
                
	$assessment_arr = $CI->db->query($query)->result_array(); 
	$str = '';
	if(count($assessment_arr) > 0){
	    
    	foreach($assessment_arr as $row)
    	{
    	   $str .= '<div style="border:1px solid #f0f0f0;margin-top: 10px;padding: 10px;background-color:#f2f5f5;">';
    	   $str .= '<strong>Assigned To: </strong><br><strong>'. $row['department_name'] .' | '. $row['class_name'] . ' | '. $row['section_name'] .'</strong>' . '<br>'; 
    	   $str .= '<strong>Subject :</strong>' . $row['subject_name']. " - ".$row['subject_code'] . '<br>'; 
    	   $str .= '<strong>Assessment Date :</strong>' . date_view($row['assessment_date']) . '<br>'; 
    	   $str .= '<strong>Start Time :</strong>' . $row['start_time'] . '<br>'; 
    	   $str .= '<strong>End Time :</strong>'   . $row['end_time'] . '<br>';
           $str .= '</div>';
    	} 
    	
	}
	
	return $str;

}

function get_assessment_details($assessment_id){
    
    $CI=& get_instance();
	$CI->load->database();
	
     $query =  "select distinct aud.assessment_id , cs.title as section_name , cls.name as class_name , aud.start_time 
                , sub.name as subject_name , sub.code as subject_code,
                dept.title as department_name , aud.end_time , aud.assessment_date FROM ".get_school_db().".assessment_audience aud 
                join ".get_school_db().".assessments assm on (aud.assessment_id = assm.assessment_id)
                join ".get_school_db().".class_section cs on (cs.section_id = aud.section_id)
                join ".get_school_db().".class cls on (cls.class_id = cs.class_id)
                join ".get_school_db().".departments dept on (dept.departments_id = cls.departments_id)
                join ".get_school_db().".subject sub on (sub.subject_id = aud.subject_id)
                where 
                assm.school_id=".$_SESSION['school_id']."
                and aud.assessment_id = $assessment_id and assm.is_completed = 1";
                
	$assessment_arr = $CI->db->query($query)->row(); 
	return $assessment_arr;

}


function get_notes_assigned_section($notes_id){
    
    $CI=& get_instance();
	$CI->load->database();
	
     $query =  "select distinct notes.notes_id , cs.title as section_name , cls.name as class_name  
                , sub.name as subject_name , sub.code as subject_code,
                dept.title as department_name , notes.inserted_at FROM ".get_school_db().".lecture_notes_audience lec_not
                join ".get_school_db().".lecture_notes notes on (notes.notes_id = lec_not.notes_id)
                join ".get_school_db().".class_section cs on (cs.section_id = lec_not.section_id)
                join ".get_school_db().".class cls on (cls.class_id = cs.class_id)
                join ".get_school_db().".departments dept on (dept.departments_id = cls.departments_id)
                join ".get_school_db().".subject sub on (sub.subject_id = lec_not.subject_id)
                where 
                notes.school_id=".$_SESSION['school_id']."
                and notes.notes_id = $notes_id";
                
	$notes_arr = $CI->db->query($query)->result_array(); 
	$str = '';
	if(count($notes_arr) > 0){
	    
    	foreach($notes_arr as $row)
    	{
    	   $str .= '<div style="border:1px solid #f0f0f0;margin-top: 10px;padding: 10px;background-color:#f2f5f5;">';
    	   $str .= '<strong>Assigned To: </strong><br><strong>'. $row['department_name'] .' | '. $row['class_name'] . ' | '. $row['section_name'] .'</strong>' . '<br>'; 
    	   $str .= '<strong>Subject :</strong>' . $row['subject_name']. " - ".$row['subject_code'] . '<br>'; 
    	   $str .= '<strong>Notes Date :</strong>' . date_view($row['inserted_at']) . '<br>'; 
           $str .= '</div>';
    	} 
    	
	}
	
	return $str;

}


function teacher_subject_assessment_list($teacher_id=0,$subject_id=0)
{
	$CI=& get_instance();
	$CI->load->database();
	
	$query = "select distinct ass.assessment_id , ass.assessment_title FROM ".get_school_db().".assessments ass
              inner join ".get_school_db().".assessment_audience au on (ass.assessment_id=au.assessment_id)
              where 
              ass.teacher_id = $teacher_id
              and ass.school_id=".$_SESSION['school_id']."
              and au.subject_id = $subject_id";
	$assessment_arr = $CI->db->query($query)->result_array();
	
	$str='<option value="">'.get_phrase('select_assessment').'</option>';
	if(count($assessment_arr) > 0){
    	foreach($assessment_arr as $row)
    	{
    		$opt_selected="";
    		if($selected == $row['assessment_id'])
    			$opt_selected="selected";
    		$str .= '<option value="'.$row['assessment_id'].'" '.$opt_selected.'>'.$row['assessment_title'].'</option>';
    	}  
	}
		
	return $str; 
}

function get_question_options($question_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $options = $CI->db->query("select * from " . get_school_db() . ".question_options where question_id = $question_id")->result_array();
    return $options;
}

function get_question_options_system($question_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $options = $CI->db->query("select * from " . get_system_db() . ".qb_question_options where question_id = $question_id")->result_array();
    return $options;
}

function get_assigned_assessment_students($section_id , $assessment_id){
   
    $CI =& get_instance();
    $CI->load->database();
    $query = "SELECT au.student_id , s.name , s.roll FROM " . get_school_db() . ".assessment_audience au 
              join " . get_school_db() . ".student s on s.student_id = au.student_id where au.section_id = $section_id
              and au.assessment_id = $assessment_id";
    $query = $CI->db->query($query)->result_array();
    return $query;          
}


function get_assigned_assessments_sections($assessment_id){
    
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "select distinct au.section_id from " . get_school_db() . ".assessment_audience au 
                  join " . get_school_db() . " .assessments ass on ass.assessment_id = au.assessment_id
                  where au.assessment_id = $assessment_id";
             
    $query = $CI->db->query($query_str)->result_array();
    return $query;
}


function get_assessment_row($assessment_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "select * from " . get_school_db() . ".assessments 
    where assessment_id=$assessment_id";
    $query = $CI->db->query($query_str)->row();
   
    return $query;
}

function get_assessment_result_row($assessment_id , $student_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "select * from " . get_school_db() . ".assessment_result 
        where assessment_id=$assessment_id and student_id =$student_id";
    $query = $CI->db->query($query_str);
    if ($query->num_rows() > 0) {
        return $query->row();
    }
    return NULL;
}


function get_subject_section($subject_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "select cs.section_id,d.title as department_name,c.name
		as class_name,cs.title as section_name from " . get_school_db() . ".subject s 
        inner join  " . get_school_db() . ".subject_section ss on s.subject_id=ss.subject_id
        inner join " . get_school_db() . ".class_section cs on cs.section_id=ss.section_id
        inner join " . get_school_db() . ".class c on c.class_id=cs.class_id
        inner join " . get_school_db() . ".departments d on d.departments_id=c.departments_id
        where s.subject_id=$subject_id and d.school_id=" . $_SESSION['school_id'] . ' order by d.title,c.name,cs.title asc ';
        $query = $CI->db->query($query_str)->result_array();
        return $query;
}
function get_section_subject($section_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $subject_arr = $CI->db->query("select s.* from " . get_school_db() . ".subject s 
		inner join  " . get_school_db() . ".subject_section ss 
			on s.subject_id=ss.subject_id
		where 
		ss.section_id = $section_id
		and s.school_id=" . $_SESSION['school_id'] . "  
		")->result_array();
    return $subject_arr;
}
function get_option_text_by_option_number($question_id , $option_number){
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "select * from " . get_school_db() . ".question_options
        where question_id=$question_id AND option_number=$option_number";
        $query = $CI->db->query($query_str)->row();
        return $query;
}
function get_correct_option_for_mcqs($assessment_id = "" ,$question_id = "")
{
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "SELECT aq.right_answer_key as right_answer , (SELECT option_text from " . get_school_db() . ".question_options where option_number = aq.right_answer_key and question_id = ".$question_id.") as right_answer_text from " . get_school_db() . ".assessment_questions aq where aq.question_id = ".$question_id." and aq.assessment_id = ".$assessment_id." and aq.question_type_id = 1 ";
        $query = $CI->db->query($query_str)->row();
        return $query->right_answer_text;
}
function get_correct_option_for_truefalse($assessment_id = "" ,$question_id = "")
{
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "SELECT right_answer_key as right_answer_text from " . get_school_db() . ".assessment_questions where question_id = ".$question_id." and assessment_id = ".$assessment_id." and question_type_id = 2 ";
        $query = $CI->db->query($query_str)->row();
        return $query->right_answer_text;
}
function get_correct_option_for_blanks($assessment_id = "" ,$question_id = "")
{
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "SELECT right_answer_key as right_answer_text from " . get_school_db() . ".assessment_questions 
    where question_id = ".$question_id." and assessment_id = ".$assessment_id." and question_type_id = 3 ";
        $query = $CI->db->query($query_str)->row();
        return $query->right_answer_text;
}


    
function month_of_year($val)
{
    $month = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    );

    return $month[intval($val)];
}
function status_active($num)
{
    $val_num = array(
        0 => 'Inactive',
        1 => 'Active'
    );
    return $val_num[$num];
}
function month_option_list($selected = 0)
{
    $monthArray = array(
        "01" => get_phrase('january'),
        "02" => get_phrase('february'),
        "03" => get_phrase('march'),
        "04" => get_phrase('april'),
        "05" => get_phrase('may'),
        "06" => get_phrase('june'),
        "07" => get_phrase('july'),
        "08" => get_phrase('august'),
        "09" => get_phrase('september'),
        "10" => get_phrase('october'),
        "11" => get_phrase('november'),
        "12" => get_phrase('december')
    );
    $str = '<option value="">' . get_phrase('select_month') . '</option>';
    foreach ($monthArray as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }

    return $str;
}
function year_option_list($start = 0, $end = 0, $selected = 0)
{
    if ($start == 0) {
        $start = date('Y');
    }
    if ($end == 0) {
        $end = date('Y');
    }
    $str = '<option value="">Select Year</option>';
    for ($i = $start; $i <= $end; $i++) {
        $opt_selected = "";
        if ($selected == $i) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $i . '" ' . $opt_selected . '>' . $i . '</option>';
    }
    return $str;
}

function religion($key = 0)
{
    $reg_ary = array(
        1 => 'Muslim',
        2 => 'Christian',
        3 => 'Hindu',
        4 => 'Sikh',
        5 => 'Other'
    );
    if ($key > 0 && isset($reg_ary[$key]))
        return $reg_ary[$key];
    else
        return "";
    /*
    foreach ($reg_ary as $key => $value) {
    echo "Key: $key; Value: $value<br />\n";
    }
    */
}
function religion_list($sel_name = "", $sel_class = "", $selected = 0)
{

    $reg_ary = array(
        1 => get_phrase('muslim'),
        2 => get_phrase('christian'),
        3 => get_phrase('hindu'),
        4 => get_phrase('sikh'),
        5 => get_phrase('other')
    );

    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '">
	<option value="">' . get_phrase('select_religion') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}
function file_upload_fun($file_name = "", $folder_name = "", $prefix = "", $is_root = 0)
{
    $path = 'uploads/' . $_SESSION['folder_name'] . '/' . $folder_name;

    if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
        return "";
    } elseif ($file_name == "") {
        return "";
    } elseif ($folder_name == "") {
        $path = 'uploads/' . $_SESSION['folder_name'];
    } elseif ($is_root == 1) {
        $path = 'uploads/' . $folder_name;
    }

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    if ($_FILES[$file_name]['name'] != "") {
        $filename = $_FILES[$file_name]['name'];
        //print_r($_FILES);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $new_file = $prefix . '_' . time() . '.' . $ext;
       

        move_uploaded_file($_FILES[$file_name]['tmp_name'], $path . '/' . $new_file);

        return $new_file;
    } else {
        return "";
    }
}

function file_upload_qb($full_file_path = "", $folder_name = "", $prefix = "", $is_root = 0)
{
    $path = 'uploads/' . $_SESSION['folder_name'] . '/' . $folder_name;

    if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
        return "";
    } elseif ($full_file_path == "") {
        return "";
    } elseif ($folder_name == "") {
        $path = 'uploads/' . $_SESSION['folder_name'];
    } elseif ($is_root == 1) {
        $path = 'uploads/' . $folder_name;
    }

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    
    $split_img = explode("/",$full_file_path);
    $filename = $split_img[count($split_img) - 1];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $new_file = $prefix . '_' . time() . '.' . $ext;
    
    copy($full_file_path,$path.'/'.$new_file);
    
    return $new_file;
}


function display_link($file_name = "", $folder_name = "", $is_root = 0, $default_img = 0)
{
    $return_link = "";

    if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
        $return_link = "";
    } elseif ($file_name == "") {
        $return_link = "";
    } elseif ($folder_name == "") {
        $link = 'uploads/' . $_SESSION['folder_name'] . '/' . $file_name;
        if (file_exists($link)) {
            $return_link = base_url() . $link;
        }
    } elseif ($is_root == 1) {
        $link = 'uploads/' . $folder_name . '/' . $file_name;
        if (file_exists($link)) {
            $return_link = base_url() . $link;
        }
    } else {
        $link = 'uploads/' . $_SESSION['folder_name'] . '/' . $folder_name . '/' . $file_name;
        if (file_exists($link)) {
            $return_link = base_url() . $link;
        }
    }

    if ($return_link == "" && $default_img == 1) {

        $return_link = base_url() . '/uploads/default.png';
    }
    return $return_link;
}
function system_path($file_name, $folder_name = "", $is_root = 0)
{
    if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
        return "";
    } elseif ($file_name == "") {
        return "";
    } elseif ($folder_name == "") {
        return $link = 'uploads/' . $_SESSION['folder_name'] . '/' . $file_name;
    } elseif ($is_root == 1) {
        return $link = 'uploads/' . $folder_name . '/' . $file_name;
    } else {
        return $link = 'uploads/' . $_SESSION['folder_name'] . '/' . $folder_name . '/' . $file_name;
    }
}
function file_delete($path_std)
{
    //print_r($path_std);
    if (file_exists($path_std)) {
        unlink($path_std);
    }
    //  $base_path = 'uploads/' . $_SESSION['folder_name'] . '/lecture_notes';
    
    //     if (!isset($_SESSION['folder_name']) || $_SESSION['folder_name'] == "") {
    //         return "";
    //     } 
    //     elseif ($file_name == "") {
    //         return "";
    //     } 
    //     elseif ($is_root == 1) {
    //         $base_path = 'uploads/' . $_SESSION['folder_name'];
    //     }
    //     if (!is_dir($base_path)) {
    //         mkdir($base_path, 0777, true);
    //     }
        
    //     $curr_date = date('Y-m-d');
    //     $six_digit_random_number = mt_rand(100000, 999999);
    //     $upload_path = 'uploads/' . $_SESSION['folder_name'] . '/lecture_notes/'.$curr_date;
}
function file_delete_path($id,$path_std)
{
      if (file_exists($path_std)) {
       
        unlink($path_std);
    }
    
}
function image_validation($file_name, $folder_name)
{
    if (!file_exists($path_std) || $file_name == "") {

        $file_link = "";
    } else {
        $file_link = display_link($file_name, $folder_name, $is_root = 0);
    }
    return $file_link;
}
function date_dash($date)
{
    if ($date != "" && $date != '0000-00-00' && $date != '0000-00-00 00:00:00') {
        $date_ary = explode('-', $date);
        //print_r($date_ary);
        return $date_ary[2] . '/' . $date_ary[1] . '/' . $date_ary[0];
    } else {
        return FALSE;
    }
}
function date_slash($date)
{
    if ($date != "" && $date != '0000-00-00' && $date != '0000-00-00 00:00:00') {
        $date_ary = explode('/', $date);
        return $date_ary[2] . '-' . $date_ary[1] . '-' . $date_ary[0];
    } else {
        return FALSE;
    }
}
function date_view($date)
{
    if ($date != "" && $date != '0000-00-00' && $date != '0000-00-00 00:00:00') {
        $date = $date;
        $d = explode("-", $date);
        $dd = explode(" ", $d[2]);
        // print_r($d);
        // return date("d-M-Y", mktime(0, 0, 0, $d[1], $dd[0], $d[0]));
        return date("d-M-Y", mktime(0, 0, 0, $d[1], $d[2], $d[0]));
    // return $d[0].' '.$d[1].' '.$d[2];
        // return $d;
        // return $d[2];
    }
}
// below function is design for mobile API
function date_vie($date)
{
    if ($date != "" && $date != '0000-00-00' && $date != '0000-00-00 00:00:00') {
        $date = $date;
        $d = explode("-", $date);
        $dd = explode(" ", $d[2]);
        // print_r($d);
        return date("d-M-Y", mktime(0, 0, 0, $d[1], $dd[0], $d[0]));
        // return date("d-M-Y", mktime(0, 0, 0, $d[1], $d[2], $d[0]));
    // return $d[0].' '.$d[1].' '.$d[2];
        // return $d;
        // return $d[2];
    }
}
function parent_h($val)
{

    if ($val == "f") {
        return "father";
    } elseif ($val == "m") {
        return "Mother";
    } elseif ($val == "g") {
        return "Guardian";
    }
}
function Method($sel_name = "", $sel_class = "", $selected = 0)
{
    $reg_ary = array(
        1 => get_phrase('cheque'),
        2 => get_phrase('cash'),
        3 => get_phrase('online_transfer')
    );
    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '">
	<option value=""><?php echo get_phrase("select_method"); ?></option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}
function type($sel_name = "", $sel_class = "", $selected = 0)
{
    $reg_ary = array(
        1 => get_phrase('receipt'),
        2 => get_phrase('payment')
    );
    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '">
	<option value="">' . get_phrase('select_type') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}
function maketime($var, $val2)
{
    $date_array = explode('-', $var);
    if ($val2 == "m") {
        echo date('M', mktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]));
    } elseif ($val2 == 'd') {
        echo date('d', mktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]));
    }
}
function country_option_list($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $country = "select * FROM  " . get_system_db() . ".country ";
    $reg_array = $CI->db->query($country)->result_array();

    $str = '<option value="">' . get_phrase('select_country') . '</option>';

    foreach ($reg_array as $row) {
        $opt_selected = "";
        if ($selected == $row['country_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['country_id'] . '" ' . $opt_selected . '>' . $row['title'] . '</option>';
    }
    return $str;
}


function library_books($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $country = "select book_id,book_title FROM  " . get_school_db() . ".books WHERE status = '1'";
    $reg_array = $CI->db->query($country)->result_array();

    $str = '<option value="">' . get_phrase('select_book') . '</option>';

    foreach ($reg_array as $row) {
        $opt_selected = "";
        if ($selected == $row['book_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['book_id'] . '" ' . $opt_selected . '>' . $row['book_title'] . '</option>';
    }
    return $str;
}

function library_members($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $country = "select library_member_id,user_id FROM  " . get_school_db() . ".library_members WHERE status = '1'";
    $reg_array = $CI->db->query($country)->result_array();

    $str = '<option value="">' . get_phrase('select_member') . '</option>';

    foreach ($reg_array as $row) {
        $opt_selected = "";
        if ($selected == $row['library_member_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['library_member_id'] . '" ' . $opt_selected . '>' . student_name_section($row['user_id']) . '</option>';
    }
    return $str;
}

function branches_option_list($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $country = "select * from " . get_system_db() . ".system_school sc inner join " . get_school_db() . ".school s on s.sys_sch_id=sc.sys_sch_id where sc.sys_sch_id!=" . $_SESSION['sys_sch_id'] . " and sc.parent_sys_sch_id=" . $_SESSION['parent_sys_sch_id'];
    $reg_array = $CI->db->query($country)->result_array();
    $str = '<option value=""> ' . get_phrase('select_branch') . '</option>';

    foreach ($reg_array as $row) {
        $opt_selected = "";
        if ($selected == $row['school_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['school_id'] . '" ' . $opt_selected . '>' . $row['name'] . '</option>';
    }
    return $str;
}
function branches_name($branch_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $country = "select * from " . get_system_db() . ".system_school sc inner join " . get_school_db() . ".school s on s.sys_sch_id=sc.sys_sch_id where sc.sys_sch_id!=" . $_SESSION['sys_sch_id'] . " and sc.parent_sys_sch_id=" . $_SESSION['parent_sys_sch_id'];
    $reg_array = $CI->db->query($country)->result_array();
    $branch_name = "";
    foreach ($reg_array as $row) {
        if ($branch_id == $row['school_id']) {
            $branch_name = $row['name'];
        }
    }
    return $branch_name;
}
function province_option_list($country_id = 0, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    if ($country_id > 0) {
        $query_where = "WHERE country_id=" . $country_id . "";
    } else {
        $query_where = " ";
    }
    $province = "select * FROM  " . get_system_db() . ".province 
	            $query_where";
    $reg_array = $CI->db->query($province)->result_array();
    if (count($reg_array) > 0) {
        $str = '<option value="">' . get_phrase('select_province') . '/' . get_phrase('state') . '</option>';

        foreach ($reg_array as $row) {
            $opt_selected = "";
            if ($selected == $row['province_id']) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $row['province_id'] . '" ' . $opt_selected . '>' . $row['title'] . '</option>';
        }
        return $str;
    } else {
        $str = '<option >' . get_phrase('no_records_found') . '</option>';
        return $str;
    }
}
function city_option_list($province_id, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    if ($province_id > 0) {
        $query_where = " where province_id=$province_id";
    } else {
        $query_where = " ";
    }
    $city = "select * FROM  " . get_system_db() . ".city $query_where";
    $reg_array = $CI->db->query($city)->result_array();
    if (count($reg_array) > 0) {
        $str = '<option value="">' . get_phrase('select_city') . '</option>';

        foreach ($reg_array as $row) {
            $opt_selected = "";
            if ($selected == $row['city_id']) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $row['city_id'] . '" ' . $opt_selected . '>' . $row['title'] . '</option>';
        }
        return $str;
    } else {
        $str = '<option >' . get_phrase('no_records_found') . '</option>';
        return $str;
    }
}

function get_country_edit($location_id = 0, $school_db = "")
{
    $CI =& get_instance();
    $CI->load->database();

    $country = array();
    $sch_db = get_school_db();
    if (empty($sch_db)) {
        $sch_db = $school_db;
    }
    if (!empty($sch_db)) {
        $sql_str = "select c.country_id, c.title as country_title, p.province_id, p.title as province_title, ct.city_id, ct.title as city_title, cl.location_id, cl.title as location_title
        from " . get_system_db() . ".country c inner join " . get_system_db() . ".province p on p.country_id=c.country_id
        inner join " . get_system_db() . ".city ct on ct.province_id=p.province_id
        inner join " . $sch_db . ".city_location cl on cl.city_id=ct.city_id
        where cl.location_id=$location_id";
        $country = $CI->db->query($sql_str)->result_array();
    }
    return $country;
}

function get_section_edit($id)
{
    $CI =& get_instance();
    $CI->load->database();
    $depart = $CI->db->query("select s.student_id,cs.section_id, c.class_id,d.departments_id,s.pro_section_id
		from " . get_school_db() . ".student s
        inner join " . get_school_db() . ".class_section cs on s.section_id=cs.section_id 
		inner join " . get_school_db() . ".class c on c.class_id=cs.class_id inner join " . get_school_db() . ".departments d on d.departments_id=c.departments_id
		where s.student_id=$id")->result_array();
    return $depart;
}

function location_option_list($city_id = 0, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $query = "";
    $str = "";
    if ($city_id > 0) {
        $query = " WHERE city_id=" . $city_id . "";
    } else {
        $query = "";
    }
    $location = "select * FROM  " . get_school_db() . ".city_location $query";
    $reg_array = $CI->db->query($location)->result_array();

    if (count($reg_array) > 0) {
        $str = '<option value=""> ' . get_phrase('select_location') . '</option>';
        foreach ($reg_array as $row) {
            $opt_selected = "";
            if ($selected == $row['location_id']) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $row['location_id'] . '" ' . $opt_selected . '>' . $row['title'] . '</option>';
        }
        return $str;
    } else {
        return $str = '<option value=""> ' . get_phrase('Not record found') . '</option>';
    }
}

function location_option_list_depositor($city_id = 0, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $query = "";
    if ($city_id > 0) {
        $query = " WHERE location_id=" . $city_id . "";
    }
    echo $location = "select * FROM  " . get_school_db() . ".city_location";
    $reg_array = $CI->db->query($location)->result_array() or die(mysqli_error());

    $str = '<option value="">' . get_phrase('select_location') . '</option>';

    foreach ($reg_array as $row) {
        $opt_selected = "";
        if ($selected == $row['location_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['location_id'] . '" ' . $opt_selected . '>' . $row['title'] . '</option>';
    }
    return $str;
}

function designation_list_h($parent_id = 0, $selected = 0, $spaces = 0, $d_school_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }

    $coa_rec = $CI->db->query("select * from " . get_school_db() . ".designation where parent_id=$parent_id AND school_id=" . $school_id . "")->result_array();

    //echo $this->db->last_query();
    //echo " <optgroup label='Picnic'>";
    $str_spaces = "";
    $str_spaces = repeater("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $spaces);
    $str_spaces .= " &#10095 &nbsp;";

    foreach ($coa_rec as $coa) {
        $slc = "";
        if ($selected == $coa['designation_id']) {
            $slc = "selected";
        }
        if ($coa['is_teacher'] == 1) {
            $is_teaching = " (Teaching Staff)";
        } else {
            $is_teaching = "";
        }
        echo "<option value=" . $coa['designation_id'] . " class=' ' $slc>" . $str_spaces . $coa['title'] . $is_teaching;

        $coa_rec1 = $CI->db->query("select * from " . get_school_db() . ".designation where parent_id=" . $coa['designation_id'])->result_array();

        if (count($coa_rec1) > 0) {
            $spaces = $spaces + 1;
            designation_list_h($coa['designation_id'], $selected, $spaces);
        } else {
        }
        $spaces = 0;
        echo "</option>";
    }
//echo "</optgroup>";

}

function coa_list_h($parent_id = 0, $selected = 0, $spaces = 0, $dis_f = 1, $account_type = 0, $skip_coa = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $account_type_query = "";
    $account_type_where = "";
    $account_type_active = " AND is_active = 1 ";

    if ($account_type > 0) {
        $account_type_query = " Inner join " . get_school_db() . ".chart_of_account_types as coa_type on coa_type.coa_id = coa.coa_id ";
        $account_type_where = " AND coa_type.coa_type = $account_type ";
    }

    if ($account_type == 0) {
        $account_type_active = "";
    }

    $str_skip_coa = "";
    if ($skip_coa > 0) {
        $str_skip_coa = " AND coa.coa_id != $skip_coa ";
    }

    $coa_rec_str = "select coa.* from " . get_school_db() . ".chart_of_accounts as coa
    Inner join " . get_school_db() . ".school_coa as s_coa ON
    s_coa.coa_id = coa.coa_id $account_type_query where
    coa.parent_id=$parent_id $account_type_active $str_skip_coa AND s_coa.school_id= " . $_SESSION['school_id'] . " $account_type_where";

    $coa_rec = $CI->db->query($coa_rec_str)->result_array();

    //echo " <optgroup label='Picnic'>";
    $str_spaces = "";
    $str_spaces = repeater("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $spaces);
    $str_spaces .= " &#10095 &nbsp;";

    foreach ($coa_rec as $coa)
    {
        //if($coa['account_type'] == 1 && ){}
        $slc = "";
        if ($selected == $coa['coa_id']) {
            $slc = "selected";
        }
        $dis = "";
        if ($dis_f == 1) {
            if ($coa['parent_id'] == 0) {
                $dis = "disabled";
            }
        }
        //  echo "select * from ".get_school_db().".chart_of_accounts where parent_id=".$coa['coa_id'];
        echo "<option $dis  value=" . $coa['coa_id'] . " class='' $slc> " . $str_spaces . $coa['account_head'] . " - " . $coa['account_number'];
        echo "</option>";
        //  echo "select * from ".get_school_db().".chart_of_accounts where parent_id=".$coa['coa_id']." AND account_type = 2";
        $coa_rec1 = $CI->db->query("select coa.* from " . get_school_db() . ".chart_of_accounts as coa Inner join " . get_school_db() . ".school_coa as s_coa ON s_coa.coa_id = coa.coa_id $account_type_query where  coa.parent_id=" . $coa['coa_id'] . " $account_type_active $account_type_where $str_skip_coa AND s_coa.school_id= " . $_SESSION['school_id'] . " ")->result_array();
        if (count($coa_rec1) > 0) {
            $spaces = $spaces + 1;
            coa_list_h($coa['coa_id'], $selected, $spaces, $dis_f, $account_type, $skip_coa);
        } else {

        }
        $spaces = 0;
        //echo "</option>";
    }
//echo "</optgroup>";
}

function child_coa_list($prent_id = 0, $selected = 0)
{

    $CI =& get_instance();
    $CI->load->database();

    $query_str = "select coa.* from " . get_school_db() . ".chart_of_accounts as coa Inner join " . get_school_db() . ".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
    where parent_id = $prent_id AND s_coa.school_id= " . $_SESSION['school_id'] . "";
    $coa_rec = $CI->db->query($query_str)->result_array();
    $str = "";
    $slc = "";
    foreach ($coa_rec as $coa) {
        if ($selected == $coa['coa_id']) {
            $slc = "selected";
        } else {
            $slc = "";
        }
        $str .= "<option value=" . $coa['coa_id'] . " class='' $slc>
        " . $coa['account_head'] . " - " . $coa['account_number'] .
            "</option>";
    }
    return $str;
}

function coa_list_assign($parent_id = 0, $selected = 0, $spaces = 0, $dis_f = 1, $account_type = 0, $skip_coa = 0, $branch_school_id, $used_coa_ids_temp, $unused_coa_ids_temp)
{
    $CI =& get_instance();
    $CI->load->database();
    $school_id = $_SESSION['school_id'];
    $account_type_query = "";
    $account_type_where = "";
    $account_type_active = " AND is_active = 1 ";
    $login_type = $_SESSION['login_type'];

    if ($account_type > 0) {
        $account_type_query = " Inner join " . get_school_db() . ".chart_of_account_types as coa_type on coa_type.coa_id = coa.coa_id ";
        $account_type_where = " AND coa_type.coa_type = $account_type ";
    }

    if ($account_type == 0) {
        $account_type_active = "";
    }

    $str_skip_coa = "";
    if ($skip_coa > 0) {
        $str_skip_coa = " AND coa.coa_id != $skip_coa ";
    }

    $coa_rec_str = "select sc.name as school_name , coa.school_id as school_id_temp , coa.* from " . get_school_db() . ".chart_of_accounts as coa Inner join " . get_school_db() . ".school_coa as s_coa ON s_coa.coa_id = coa.coa_id INNER JOIN " . get_school_db() . ".school as sc on sc.school_id = coa.school_id $account_type_query where coa.parent_id=$parent_id $account_type_active $str_skip_coa AND s_coa.school_id= $school_id $account_type_where";
    $coa_rec = $CI->db->query($coa_rec_str)->result_array();

    //echo " <optgroup label='Picnic'>";
    // $str_spaces="";
    // $str_spaces=repeater("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$spaces);
    // $str_spaces.=" &#10095 &nbsp;";

    echo "<ul>";

    foreach ($coa_rec as $coa) {
        echo "<li>";

        if ($selected == $coa['coa_id']) {
            $slc = "selected";
        }

        $dis = "";
        if ($dis_f == 1) {
            if ($coa['parent_id'] == 0) {
                $dis = "disabled";
            }
        }
        $account_type = "";
        if ($coa['account_type'] == 1) {
            $account_type = "<span class = 'orange space'>(" . get_phrase('credit') . ")</span>";
        } else {
            $account_type = "<span class = 'green space'>(" . get_phrase('debit') . ")</span>";
        }

        $is_active = "";
        if ($coa['is_active'] == 1) {
            $is_active = "<span class = 'green space'>(" . get_phrase('active') . ")</span>";
        } else {
            $is_active = "<span class = 'orange space'>(" . get_phrase('inactive') . ")</span>";
        }
        // $school_name = $coa['school_name'];
        $school_name = "";
        $school_id_temp = $coa['school_id_temp'];
        if (($school_id_temp != $school_id) && ($login_type == 1)) {

            $school_name = " - " . $coa['school_name'];
        }

        $checked = "";

        if (in_array($coa['coa_id'], $used_coa_ids_temp) || in_array($coa['coa_id'], $unused_coa_ids_temp)) {
            $checked = "checked";
        }
        $read_only = "";
        if (in_array($coa['coa_id'], $used_coa_ids_temp)) {
            $read_only = "return false";
        }
        $checkbox = "<input type='checkbox' name='coa_id[]' value='" . $coa['coa_id'] . "'  class='form-check-input' " . $checked . " onclick='" . $read_only . "'>";
        //echo "<br>";
        //echo  $str_spaces." ".$checkbox.$coa['account_head']." - ".$coa['account_number']." - ".$account_type." - ".$is_active.$school_name;
        echo $checkbox . $coa['account_head'] . " - " . $coa['account_number'] . " - " . $account_type . " - " . $is_active . $school_name;

        $coa_rec1_str = "select sc.name as school_name , coa.school_id as school_id_temp ,coa.* from " . get_school_db() . ".chart_of_accounts as coa
       Inner join " . get_school_db() . ".school_coa as s_coa ON s_coa.coa_id = coa.coa_id
       INNER JOIN " . get_school_db() . ".school as sc on sc.school_id = coa.school_id $account_type_query
       where  coa.parent_id=" . $coa['coa_id'] . "
       $account_type_active
       $account_type_where
       $str_skip_coa
       AND s_coa.school_id= $school_id
                                                    ";
        $coa_rec1 = $CI->db->query($coa_rec1_str)->result_array();

        if (count($coa_rec1) > 0) {
            $spaces = $spaces + 1;
            coa_list_assign($coa['coa_id'], $selected, $spaces, $dis_f, $account_type, $skip_coa, $branch_school_id, $used_coa_ids_temp, $unused_coa_ids_temp);
        } else {

        }

        $spaces = 0;
        echo "</li>";
    }
    echo "</ul>";
//echo "</optgroup>";

}


function teacher_designation_list($parent_id = 0, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $des_arr = $CI->db->query("select * from " . get_school_db() . ".designation where parent_id=$parent_id AND school_id=" . $_SESSION['school_id'] . "")->result_array();

    foreach ($des_arr as $arr) {
        $slc = "";
        if ($selected == $arr['designation_id']) {
            $slc = "selected";
        }
        if ($arr['is_teacher'] == 1) {
            echo "<option value=" . $arr['designation_id'] . " class='colist' $slc>" . $arr['title'] . "</option>";
        }
        $des_arr1 = $CI->db->query("select * from " . get_school_db() . ".designation where  parent_id=" . $arr['designation_id'])->result_array();

        if (count($des_arr1) > 0) {
            teacher_designation_list($arr['designation_id'], $selected);
        }

    }
}

function designation_list($parent_id = 0, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $des_arr = $CI->db->query("select * from " . get_school_db() . ".designation where parent_id=$parent_id AND school_id=" . $_SESSION['school_id'] . "")->result_array();

    /*$last_q=$this->db->last_query();
            echo "<pre>";print_r($last_q);exit;
        $query=$this->db->query($last_q)->result_array();*/
    foreach ($des_arr as $arr) {
        //echo "<pre>";print_r($arr['designation_id']);
        $slc = '';

        if ($arr['designation_id'] == $selected) {
            $slc = "selected";
        }
        if ($arr['is_teacher'] == 1) {
            $is_teaching = " (Teaching Staff)";
        } else {
            $is_teaching = "";
        }
        echo "<option  value=" . $arr['designation_id'] . " class='colist' $slc>" . $arr['title'] . $is_teaching . "</option>";
        $des_arr1 = $CI->db->query("select * from " . get_school_db() . ".designation where parent_id=" . $arr['designation_id'])->result_array();
        if (count($des_arr1) > 0) {
            designation_list($arr['designation_id'], $selected);
        }
    }
}

function get_coa($id)
{
    $CI =& get_instance();
    $CI->load->database();

    $coa__rs = $CI->db->query("select coa.* from " . get_school_db() . ".chart_of_accounts as coa Inner join " . get_school_db() . ".school_coa as s_coa ON s_coa.coa_id = coa.coa_id 
    where coa.coa_id = $id AND s_coa.school_id = " . $_SESSION['school_id'] . "")->result_array();
    //echo $CI->db->last_query();
    $data['account_head'] = $coa__rs[0]['account_head'];
    $data['account_number'] = $coa__rs[0]['account_number'];
    return $data;
    //print_r($coa_rec);
}

function type_display($id)
{
    $reg_ary = array(
        1 => get_phrase('receipt'),
        2 => get_phrase('payment')
    );
    return $reg_ary[$id];
}

function method_display($id)
{
    $reg_ary = array(
        1 => get_phrase('cheque'),
        2 => get_phrase('cash'),
        3 => get_phrase('online_transfer')
    );
    return $reg_ary[$id];
}

function isprocessed($sel_name = "", $sel_class = "", $selected = 0)
{
    $reg_ary = array(
        0 => get_phrase('save'),
        1 => get_phrase('submit')
    );
    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '">
	<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function get_isprocessed($id)
{
    $reg_ary = array(
        0 => 'no',
        1 => 'yes'
    );
    return $reg_ary[$id];
}

function transection_filter($selected = 0)
{
    $reg_ary = array(
        2 => get_phrase('payments'),
        1 => get_phrase('receipts')
    );

    $str = '';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    return $str;
}

function status($sel_name = "", $sel_class = "", $selected = 0, $sel_id = "")
{

    $reg_ary = array(
        0 => get_phrase('inactive'),
        1 => get_phrase('active'),
    );

    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '" id="' . $sel_id . '" required>
	<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if (($selected == $key) && ($selected != "")) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}


function class_chalan_type($sel_name = "", $sel_class = "", $selected = 0, $sel_id = "")
{
    $reg_ary = array(
        1 => get_phrase('new_admission'),
        2 => get_phrase('monthly_fee'),
        3 => get_phrase('class_upgrade'),
        4 => get_phrase('class_degrade'),
        5 => get_phrase('student_transfer'),
        6 => get_phrase('school_withdrawal'),
        7 => get_phrase('receiving_student_transfer')
    );
    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '" id="' . $sel_id . '" required>
	<option value="">' . get_phrase('select_chalan_type') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function get_class_chalan_type($type = "")
{
    $reg_ary = array(
        1 => get_phrase('new_admission'),
        2 => get_phrase('monthly_fee'),
        3 => get_phrase('class_upgrade'),
        4 => get_phrase('class_degrade'),
        5 => get_phrase('student_transfer'),
        6 => get_phrase('school_withdrawal'),
        7 => get_phrase('receiving_student_transfer')
    );

    // $str='<select name="'.$sel_name.'" class="'.$sel_class.'" id="'.$sel_id.'" required>
    // <option value="">'.get_phrase('select_chalan_type').'</option>';
    // foreach($reg_ary as $key => $value){
    //     $opt_selected="";
    //     if($selected == $key){
    //         $opt_selected="selected";
    //     }
    //     $str .= '<option value="'.$key.'" '.$opt_selected.'>'.$value.'</option>';
    // }
    // $str .='</select>';
    return $reg_ary[$type];
}


function display_class_chalan_type($id)
{
    $reg_ary = array(
        1 => get_phrase('new_admission'),
        2 => get_phrase('monthly_fee'),
        3 => get_phrase('class_upgrade'),
        4 => get_phrase('class_degrade'),
        5 => get_phrase('student_transfer'),
        6 => get_phrase('school_withdrawal'),
        7 => get_phrase('receiving_student_transfer'),
        10 => get_phrase('custom_form')
    );

    return $reg_ary[$id];
}

function teacher_option_list($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    /*
    $q="select * from ".get_school_db().".teacher where school_id=".$_SESSION['school_id']." ";
    */
    $q = "SELECT s.* FROM " . get_school_db() . ".staff s
		INNER JOIN " . get_school_db() . ".designation d 
		ON (s.designation_id = d.designation_id AND d.is_teacher=1) WHERE s.school_id=" . $_SESSION['school_id'] . " ";
    //exit();
//WHERE s.staff_id=$teacher_id
    $query = $CI->db->query($q);
    
    
    $str = '<option value="">' . get_phrase('select_teacher') . '</option>';
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->staff_id == $selected) {
                $opt_selected = "selected";
            }

            $str .= '<option value="' . $rows->staff_id . '" ' . $opt_selected . '>' . $rows->name . '</option>';
        }
    }
    return $str;
}


function department_option_list($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $q = "select * from " . get_school_db() . ".departments where school_id=" . $_SESSION['school_id'] . " order by order_num";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_department') . '</option>';
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->departments_id == $selected) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $rows->departments_id . '" ' . $opt_selected . '>' . $rows->title . '</option>';

        }
    }
    return $str;
}

function class_option_list($dept_id, $selected = 0)
{

    $CI =& get_instance();
    $CI->load->database();

    $q = "select * from " . get_school_db() . ".class where departments_id='$dept_id' and school_id=" . $_SESSION['school_id'] . " ";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_class') . '</option>';
    if ($query->num_rows() > 0) {

        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->class_id == $selected) {
                $opt_selected = "selected";
            }

            $str .= '<option value="' . $rows->class_id . '" ' . $opt_selected . '>' . $rows->name . '</option>';
        }
    }
    return $str;
}

function section_option_list($class_id, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $q = "select * from " . get_school_db() . ".class_section where class_id='$class_id' and school_id=" . $_SESSION['school_id'] . " ";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_section') . '</option>';
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->section_id == $selected) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $rows->section_id . '" ' . $opt_selected . '>' . $rows->title . '</option>';
        }
    }
    return $str;
}

function subject_option_list($section_id, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $q = "select DISTINCT(s.name),s.subject_id,s.code from " . get_school_db() . ".subject s
	INNER join " . get_school_db() . ".subject_section ss on s.subject_id=ss.subject_id
	where ss.section_id='$section_id' and s.school_id=" . $_SESSION['school_id'] . " ";
     
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_subject') . '</option>';
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $rows) {
        	//echo $rows->subject_id;
            $opt_selected = '';
            if ($rows->subject_id == $selected) {
                $opt_selected = "selected";
            }

            $str .= '<option value="' . $rows->subject_id . '" ' . $opt_selected . '>' . $rows->name . " - " . $rows->code . '</option>';
        }
    }
    echo $str;
}

function subject_teacher_option_list($subject_id, $selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    /*
    $q="SELECT t.name as teacher_name,t.teacher_id as teacher_id FROM ".get_school_db().".subject_teacher st
        INNER JOIN ".get_school_db().".teacher t
        ON st.teacher_id=t.teacher_id
        WHERE st.subject_id=$subject_id and st.school_id=".$_SESSION['school_id']." ";
    */
    $q = "select s.name as teacher_name,s.staff_id as teacher_id
		FROM 
		" . get_school_db() . ".subject_teacher st
		INNER JOIN " . get_school_db() . ".staff s
		ON st.teacher_id=s.staff_id
		INNER JOIN " . get_school_db() . ".designation d 
		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
		WHERE 
		st.subject_id=$subject_id and st.school_id=" . $_SESSION['school_id'] . "
		";

    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_teacher') . '</option>';
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->teacher_id == $selected) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $rows->teacher_id . '" ' . $opt_selected . '>' . $rows->teacher_name . '</option>';
        }
    }
    return $str;
}

function academic_year_option_list($selected = 0, $status = 0)
{
    $statusStr = '';
    $CI =& get_instance();
    $CI->load->database();
    if (is_array($status) && (count($status) > 0)) {
        $status = implode(",", $status);
        $statusStr = ' and status NOT in (' . $status . ') ';
    } elseif ($status != 0) {
        $statusStr = ' and status <> ' . $status;
    }

    $q = "select * from " . get_school_db() . ".acadmic_year where school_id=" . $_SESSION['school_id'] . " " . $statusStr . " order by status DESC";
    
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_academic_year') . '</option>';
    if ($query->num_rows() > 0) {
        
        foreach ($query->result() as $rows) {
            
            $opt_selected = '';
            if ($rows->academic_year_id == $selected) {
                $opt_selected = "selected";
            }
            $status_val = $rows->status;
            $status_value = "";
            if ($status_val == 1) {
                $status_value = "Completed";
            }elseif ($status_val == 2) {
                $status_value = "Current";
            }elseif ($status_val == 3) {
                $status_value = "Upcoming";
            }

            $is_closed = $rows->is_closed;
            if ($is_closed == 1) {
                $closed = " - Closed";
            }

            $str .= '<option value="' . $rows->academic_year_id . '" ' . $opt_selected . '>' . $rows->title . '(' . date('d-M-Y', strtotime($rows->start_date)) . ' to ' . date('d-M-Y', strtotime($rows->end_date)) . ')' . ' - ' . $status_value . $closed . '</option>';
        }
    }
    return $str;
    
}


function yearly_terms_option_list($academic_year_id = 0, $selected = 0, $status = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $statusStr = "";
    if (is_array($status) && (count($status) > 0)) {
        $status = implode(",", $status);
        $statusStr = ' and status NOT in (' . $status . ') ';
    } elseif ($status != 0) {
        $statusStr = ' and status <> ' . $status;
    }
    $q = "select * from " . get_school_db() . ".yearly_terms where academic_year_id=" . $academic_year_id . " AND school_id=" . $_SESSION['school_id'] . " " . $statusStr . " order by status DESC ";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_term') . '</option>';
    if ($query->num_rows() > 0) {

        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->yearly_terms_id == $selected) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $rows->yearly_terms_id . '" ' . $opt_selected . '>' . $rows->title . ' (' . date('d-M-Y', strtotime($rows->start_date)) . ' to ' . date('d-M-Y', strtotime($rows->end_date)) . ')' . '</option>';
        }
    }
    return $str;
}

function get_yearly_terms($academic_year_id ,$yearly_term_id)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select * from " . get_school_db() . ".yearly_terms where academic_year_id=" . $academic_year_id . " and yearly_terms_id = ".$yearly_term_id." AND school_id=" . $_SESSION['school_id'] . " order by status DESC ";
    $yearly_terms = $CI->db->query($q)->result_array();
    
    return $yearly_terms;
}

function staff_list($staff_id = 0, $selected = 0, $staff_type = 0, $designation_id = 0, $d_school_id = 0)
{
    //echo "string".$selected;
    $is_teacher = "";
    if ($staff_type == 2) {
        $is_teacher = " and d.is_teacher=1";
    } elseif ($staff_type == 3) {
        $is_teacher = " and d.is_teacher=0";
    }

    $designation_type = "";
    if ($designation_id > 0) {
        $designation_type = "and d.designation_id= " . $designation_id . "";
    }
    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }
    $CI =& get_instance();
    $CI->load->database();
    $q = "SELECT s.name as name,d.title as designation, d.is_teacher,s.staff_id as staff_id FROM " . get_school_db() . ".staff s LEFT JOIN " . get_school_db() . ".designation d ON s.designation_id=d.designation_id
			 WHERE s.school_id=" . $school_id . " " . $designation_type . " " . $is_teacher . " ";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_staff') . '</option>';
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->staff_id == $selected) {
                $opt_selected = "selected";
            }
            $designation = "";
            if (!empty($rows->designation)) {
                $designation = ' (' . $rows->designation . ')';
            }
            $is_teacher = "";
            if (!empty($rows->is_teacher)) {
                $is_teacher = ' (' . get_phrase("t") . ') ';
            }

            $str .= '<option value="' . $rows->staff_id . '" ' . $opt_selected . '
            >' . $rows->name . $is_teacher . $designation . '</option>';
        }
    }
    return $str;
}

function month_year_option($start_date, $end_date, $selected_date = '')
{
    $start_date = date('1 M Y', strtotime($start_date));
    //$end_date = date('1 M Y', strtotime($end_date));
    $months = array();

    while (strtotime($start_date) <= strtotime($end_date)) {
        $months[] = array(
            'year' => date('Y', strtotime($start_date)),
            'month' => date('m', strtotime($start_date))
        );

        $start_date = date('d M Y', strtotime($start_date .
            '+ 1 month'));
    }

    $arrlength = count($months);
    $counter = 1;
    $str = array();

    $retr = "<option value=''>" . get_phrase('select_year_month') . "</option>";
    $selected = '';

    foreach ($months as $key => $value) {
        $month_year = date("F - Y", mktime(0, 0, 0, $value['month'], 1, $value['year']));
        if ($key == 0) {
            $month_first = $value['month'] . "," . $value['year'];
        }
        $dte = "'" . $value['month'] . "-" . $value['year'] . "'";
        if ($dte == "'" . $selected_date . "'")
            $selected = 'selected';
        else
            $selected = '';
        $retr .= "<option value='" . $value['month'] . "-" . $value['year'] . "' $selected>" . month_of_year($value['month']) . "-" . $value['year'] . "</option>";
    }
    return $retr;
}

function get_department_name($department, $d_school_id = 0)
{
    if ($department == '') {
        $department = 0;
    }
    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }
    $department_name = "";
    $CI =& get_instance();
    $CI->load->database();
    $query = "select title as department_name FROM " . get_school_db() . ".departments 
    WHERE departments_id=$department and school_id = " . $school_id . " ";
    $departmentArr = $CI->db->query($query)->result_array();
    if (count($departmentArr) > 0) {
        $department_name = $departmentArr[0]['department_name'];
    }

    return $department_name;

}

function class_hierarchy($class, $d_school_id = 0)
{
    if ($class == '') {
        $class = 0;
    }
    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }
    $class_ary = array();
    $CI =& get_instance();
    $CI->load->database();
    $query = "select c.class_id as class_id, c.name as class_name, d.title as department_name,d.departments_id as departments_id
    FROM " . get_school_db() . ".class c
    INNER join " . get_school_db() . ".departments d
    ON c.departments_id = d.departments_id
    WHERE c.class_id=$class and c.school_id = " . $school_id . " ";
    $classArr = $CI->db->query($query)->result_array();
    $class_ary['d'] = $classArr[0]['department_name'];
    $class_ary['c'] = $classArr[0]['class_name'];

    $class_ary['d_id'] = $classArr[0]['departments_id'];
    $class_ary['c_id'] = $classArr[0]['class_id'];

    return $class_ary;
}

function section_hierarchy($section, $d_school_id = 0)
{
    if ($section == '') {
        $section = 0;
    }
    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }
    $sec_ary = array();
    $CI =& get_instance();
    $CI->load->database();
    $query = "select sec.class_id as class_id, c.name as class_name, sec.title as section_name, d.title as department_name,d.departments_id as departments_id,sec.section_id as section_id
	FROM " . get_school_db() . ".class_section sec
	INNER join " . get_school_db() . ".class c
	ON sec.class_id=c.class_id
	INNER join " . get_school_db() . ".departments d
	ON c.departments_id = d.departments_id
	WHERE sec.section_id=$section and sec.school_id = " . $school_id . " ";
    $classArr = $CI->db->query($query)->result_array();
    $sec_ary['d'] = $classArr[0]['department_name'];
    $sec_ary['c'] = $classArr[0]['class_name'];
    $sec_ary['s'] = $classArr[0]['section_name'];
    $sec_ary['d_id'] = $classArr[0]['departments_id'];
    $sec_ary['c_id'] = $classArr[0]['class_id'];
    $sec_ary['s_id'] = $classArr[0]['section_id'];
    return $sec_ary;
}

function subject_teacher($subject_id)
{
    $teach_ary = array();
    $CI =& get_instance();
    $CI->load->database();
    /*
    $query = "SELECT t.teacher_id as teacher_id, t.name as teacher_name, s_t.subject_teacher_id as subject_teacher_id
    FROM ".get_school_db().".teacher t
    INNER JOIN ".get_school_db().".subject_teacher s_t
    ON t.teacher_id = s_t.teacher_id
    WHERE s_t.subject_id = $subj_id";
    */
    $query = "select s.name as teacher_name,s.staff_id as teacher_id,d.title as designation, st.subject_teacher_id as subject_teacher_id
		FROM 
		" . get_school_db() . ".staff s
		INNER JOIN " . get_school_db() . ".subject_teacher st
		ON st.teacher_id=s.staff_id
		INNER JOIN " . get_school_db() . ".designation d 
		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
		WHERE 
		st.subject_id=$subject_id and st.school_id=" . $_SESSION['school_id'] . "
		";

    $teacher_Arr = $CI->db->query($query)->result_array();
    return $teacher_Arr;

}

function get_section_name($section_id)
{
    $q = "select title from " . get_school_db() . ".class_section where section_id=" . $section_id . " AND school_id=" . $_SESSION['school_id'] . "";
    $CI =& get_instance();
    $CI->load->database();
    $sectionArr = $CI->db->query($q)->result_array();
    return $sectionArr[0]['title'];
}

function get_section_subjects($section_id)
{
    $q = "select s.subject_id,s.name as subject,s.code from " . get_school_db() . ".subject_section ss left join " . get_school_db() . ".subject s on ss.subject_id=s.subject_id left join " . get_school_db() . ".class_section sec on ss.section_id=sec.section_id where ss.section_id=" . $section_id . " AND ss.school_id=" . $_SESSION['school_id'] . "";
    $CI =& get_instance();
    $CI->load->database();
    $sectionArr = $CI->db->query($q)->result_array();
    //print_R($sectionArr);
    foreach ($sectionArr as $sub) {

        $arr[] = $sub['code'] ? $sub['subject'] . '-' . strtoupper($sub['code']) : $sub['subject'];
    }
    return (implode(',', $arr));

}


function get_section_subject_ids($section_id)
{
    $q = "select s.subject_id,s.name as subject,s.code from " . get_school_db() . ".subject_section ss left join " . get_school_db() . ".subject s on ss.subject_id=s.subject_id left join " . get_school_db() . ".class_section sec on ss.section_id=sec.section_id where ss.section_id=" . $section_id . " AND ss.school_id=" . $_SESSION['school_id'] . "";
    $CI =& get_instance();
    $CI->load->database();
    $sectionArr = $CI->db->query($q)->result_array();
    //print_R($sectionArr);
    foreach ($sectionArr as $sub) {

        $arr[] = $sub['subject_id'];
    }
    return $arr;

}

function check_subject_code($code, $selected = 0)
{
    $id_check = "";
    if ($selected > 0) {
        $id_check = " and subject_id!=" . $selected . " ";
    }
    $q = "select code from " . get_school_db() . ".subject where code='" . trim($code) . "' $id_check AND school_id=" . $_SESSION['school_id'] . "";

    $CI =& get_instance();
    $CI->load->database();
    $codeArr = $CI->db->query($q)->result_array();
    //return $codeArr[0];
    if (in_array($code, $codeArr[0])) {
        return "exists";
    } else {
        return '';
    }

}

function get_teachers_checkbox($subject_id)
{
    /*
    $q2="select t.name as teacher ,st.teacher_id,st.subject_teacher_id from ".get_school_db().".subject_teacher st inner join ".get_school_db().".teacher t on st.teacher_id=t.teacher_id where st.school_id=".$_SESSION['school_id']." and st.subject_id=".$subject_id."";
    */
    $q2 = "SELECT s.name as teacher,s.staff_id as teacher_id, st.subject_teacher_id 
		FROM 
		" . get_school_db() . ".staff s
		INNER JOIN " . get_school_db() . ".subject_teacher st
		ON st.teacher_id=s.staff_id
		INNER JOIN " . get_school_db() . ".designation d 
		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
		WHERE 
		st.subject_id=$subject_id and st.school_id=" . $_SESSION['school_id'] . "
		";

    $CI =& get_instance();
    $CI->load->database();
    $teachers = $CI->db->query($q2)->result_array();
  
    if (sizeof($teachers) > 0) {
        return $teachers;
    } else {
        return "no records";
    }

}

function subject_section_list($subject_id)
{
    $q = "select section_id FROM " . get_school_db() . ".subject_section
	WHERE subject_id=$subject_id AND school_id=" . $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $section_ary = $CI->db->query($q)->result_array();
    /*$section_ary['section_id']=$section[0]['section_id'];*/
    return $section_ary;
}

function subject_section_detail($subject_id)
{
    $q = "select ss.section_id as section_id, sec.class_id as class_id, c.name as class_name, sec.title as section_name, d.title as department_name,d.departments_id as departments_id,sec.section_id as section_id FROM " . get_school_db() . ".subject_section ss
	INNER JOIN " . get_school_db() . ".class_section sec
	ON ss.section_id = sec.section_id
	INNER join " . get_school_db() . ".class c
	ON sec.class_id=c.class_id
	INNER join " . get_school_db() . ".departments d
	ON c.departments_id = d.departments_id
	WHERE ss.subject_id=$subject_id AND ss.school_id=" . $_SESSION['school_id'] . " ORDER BY department_name,class_name,section_name ASC";
    $CI =& get_instance();
    $CI->load->database();
    $classArr = $CI->db->query($q)->result_array();
    $sec_ary = array();
    foreach ($classArr as $row) {

        $sec_ary[$row['section_id']]['d'] = $row['department_name'];
        $sec_ary[$row['section_id']]['c'] = $row['class_name'];
        $sec_ary[$row['section_id']]['s'] = $row['section_name'];

    }
    return $sec_ary;

}

function get_subject_name($subject_id,$sch_db = "", $val = 0)
{
    if($sch_db == ""):
        $sch_db = get_school_db();
    endif;    
    $q = "select name,code from " .$sch_db. ".subject where subject_id=" . $subject_id . "";
    $CI =& get_instance();
    $CI->load->database();
    $subject = $CI->db->query($q)->result_array();

    $subject_name = "";

    if (count($subject) > 0) {
        $subject_name = $subject[0]['name'] . ' - ' . $subject[0]['code'];

        if ($val == 1) {
            $subject_name = $subject[0];
        }
    }
    return $subject_name;
}
function get_subject_code($subject_id,$sch_db = "", $val = 0)
{
    if($sch_db == ""):
        $sch_db = get_school_db();
    endif;    
    $q = "select name,code from " .$sch_db. ".subject where subject_id=" . $subject_id . "";
    $CI =& get_instance();
    $CI->load->database();
    $subject = $CI->db->query($q)->result_array();

    $subject_name = "";

    if (count($subject) > 0) {
        $subject_name =  $subject[0]['code'];

        if ($val == 1) {
            $subject_name = $subject[0];
        }
    }
    return $subject_name;
}
function get_subject_name1($subject_id,$sch_db = "", $val = 0)
{
    if($sch_db == ""):
        $sch_db = get_school_db();
    endif;    
    $q = "select name,code from " .$sch_db. ".subject where subject_id=" . $subject_id . "";
    $CI =& get_instance();
    $CI->load->database();
    $subject = $CI->db->query($q)->result_array();

    $subject_name = "";

    if (count($subject) > 0) {
        $subject_name =  $subject[0]['name'];

        if ($val == 1) {
            $subject_name = $subject[0];
        }
    }
    return $subject_name;
}


function get_staff_detail($staff_id)
{
    $q = "select * from " . get_school_db() . ".staff where staff_id=" . $staff_id . "";
    $CI =& get_instance();
    $CI->load->database();
    $staff_arr = $CI->db->query($q)->result_array();
    return $staff_arr;
}

function student_status($key = 0)
{
    $reg_ary = array(
        1 => get_phrase('new_candidate'),
        6 => get_phrase('admission_chalan_form_issued'),
        7 => get_phrase('admission_fee_received'),
        8 => get_phrase('study_pack_delivered'),
        9 => get_phrase('roll_number_assigned'),
        10 => get_phrase('admission_confirmed')

    );
    if ($key > 0 && isset($reg_ary[$key]))
        return $reg_ary[$key];
    else
        return "";
    /*
    foreach ($reg_ary as $key => $value) {
    echo "Key: $key; Value: $value<br />\n";
    }
    */
}

function student_option_list($sel_name = "", $sel_class = "", $selected = 0)
{

    $reg_ary = array(
        1 => get_phrase('new_candidate'),
        6 => get_phrase('admission_chalan_form_issued'),
        7 => get_phrase('admission_fee_received'),
        8 => get_phrase('study_pack_delivered'),
        9 => get_phrase('roll_number_assigned')
    );

    $str = '<select id="' . $sel_name . '" name="' . $sel_name . '" class="' . $sel_class . '">
	<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;

}

function section_student($section_id, $selected = 0, $d_school_id = 0)
{
    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }
    $CI =& get_instance();
    $CI->load->database();

    $q = "SELECT student_id, name FROM " . get_school_db() . ".student
        WHERE 
        section_id=$section_id 
        AND school_id=" . $school_id . "
        AND student_status IN (" . student_query_status() . ")
        ORDER BY name";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_student') . '</option>';
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->student_id == $selected) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $rows->student_id . '" ' . $opt_selected . '>' . $rows->name . '</option>';
        }
    }
    return $str;
}

function section_student_checkboxes($section_id, $selected_arr = array())
{
    $CI =& get_instance();
    $CI->load->database();

    $q = "select student_id, name FROM " . get_school_db() . ".student
	where 
	section_id=$section_id 
	and school_id=" . $_SESSION['school_id'] . "
	and student_status IN (" . student_query_status() . ")
	";
    $query = $CI->db->query($q);
    $str = '';
    if ($query->num_rows() > 0) {
        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if (in_array($rows->student_id, $selected_arr)) {
                $opt_selected = "checked";
            }
            $str .= ' <input type="checkbox" name="student_id[]" value="' . $rows->student_id . '" ' . $opt_selected . ' data-validate="required" data-message-required="value required">' . $rows->name . ' ';
        }
    } else
        $str .= 'No student enrolled in the selected section.';
    return $str;
}

function get_student_name($student_id, $d_school_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }

    $q = "SELECT name FROM " . get_school_db() . ".student
	WHERE student_id=$student_id AND school_id=" . $school_id . "";
    $student = $CI->db->query($q)->result_array();
    $student_name = $student[0]['name'];
    return $student_name;
}
function get_student_name_and_academic_year_id($student_id, $d_school_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }

    $q = "SELECT section_id, academic_year_id FROM " . get_school_db() . ".student
	WHERE student_id=$student_id AND school_id=" . $school_id . "";
    $student = $CI->db->query($q)->result_array();
    $student_info = array();
    $student_info['section_id'] = $student[0]['section_id'];
    $student_info['academic_year_id'] = $student[0]['academic_year_id'];
    return $student_info;
}
function get_student_info($student_id=0)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select s.name as student_name, s.mob_num , s.email,s.student_id from ".get_school_db().".student s
    where s.student_id = ".$student_id." and s.school_id=".$_SESSION['school_id']." ";
    $student = $CI->db->query($q)->result_array();
    return $student;
}

function get_students_by_sectionid($section_id=0)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select student_id from ".get_school_db().".student 
    where section_id = ".$section_id." and school_id=".$_SESSION['school_id']." ";
    $student = $CI->db->query($q)->result_array();
    return $student;
}

//we added for mbl
function get_student_info1($student_id=0)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select s.section_id from ".get_school_db().".student s
    where s.student_id = ".$student_id." and s.school_id=".$_SESSION['school_id']." ";
    $student = $CI->db->query($q)->row();
    return $student;
}

function get_submitted_assessment_students($assessment_id)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select sub.name as subject_name , s.name as student_name, s.roll as roll_number , s.section_id , s.mob_num , s.email , s.student_id  ,
    ass.assessment_title as assessment_title , ass.yearly_term_id , 
    ass.total_marks as total_marks , aud.is_submitted , ass_res.grade_id , g.name as grade_name , g.comment from ".get_school_db().".student s
    inner join ".get_school_db().".assessment_audience aud on s.student_id = aud.student_id
    inner join ".get_school_db().".assessments ass on ass.assessment_id = aud.assessment_id
    left join ".get_school_db().".assessment_result ass_res on 
    ( ass_res.assessment_id = aud.assessment_id and aud.student_id = ass_res.student_id and aud.section_id = ass_res.section_id and aud.subject_id = ass_res.subject_id)
    left join ".get_school_db().".grade g on ( g.grade_id = ass_res.grade_id)
    join ".get_school_db().".subject sub on ( sub.subject_id = aud.subject_id)
    where s.school_id=".$_SESSION['school_id']." and ass.assessment_id = ".$assessment_id." "; //and aud.is_submitted = 1

    $student = $CI->db->query($q)->result_array();
    return $student;
}

function get_submitted_assessment_date($assessment_id , $section_id , $student_id){
    $CI =& get_instance();
    $CI->load->database();

    $query_str = "select assessment_date from " . get_school_db() . ".assessment_audience 
        where assessment_id=$assessment_id and student_id =$student_id and section_id = $section_id";
    $query = $CI->db->query($query_str);
    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->assessment_date;
    }
    return NULL;
}



function get_submitted_assessment_result($assessment_id , $student_id)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select ass.assessment_title , ass.total_marks , que.* , sol.* , aud.section_id , aud.subject_id , aud.student_id , aud.is_submitted , aud.number_of_attempts 
    from ".get_school_db().".assessments ass
    inner join ".get_school_db().".assessment_audience aud on ass.assessment_id = aud.assessment_id
    inner join ".get_school_db().".assessment_questions que on ass.assessment_id = que.assessment_id
    inner join ".get_school_db().".assessment_solution sol on que.question_id = sol.question_id
    where ass.assessment_id=".$assessment_id." and aud.student_id = ".$student_id." and sol.student_id = ".$student_id." order by que.question_id asc";
    
    
    $assessment_result = $CI->db->query($q)->result_array();
    return $assessment_result;
}



function chalan_form_number()
{

    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();

    $q = "select chalan_form_number from " . get_school_db() . ".school_count where school_id =$school_id";
    $query_ary = $CI->db->query($q)->result_array();
    //print_r($query_ary);

    if (count($query_ary) > 0) {
        $chalan_form_number = $query_ary[0]['chalan_form_number'];
    } else {

        $chalan_form_number = 0;
        $p = "INSERT INTO " . get_school_db() . ".school_count (`school_id`, `chalan_form_number`) VALUES ('$school_id', '$chalan_form_number')";
        $query = $CI->db->query($p);

    }
    $return_num = $chalan_form_number + 1;
    $q_update = "update " . get_school_db() . ".school_count set  chalan_form_number=$return_num  where school_id =$school_id and chalan_form_number= $chalan_form_number";

    $query_ary = $CI->db->query($q_update);

    return $return_num;

}

function year_term_status()
{
    $reg_ary = array(
        1 => get_phrase('completed'),
        2 => get_phrase('current'),
        3 => get_phrase('upcoming')
    );
    return $reg_ary;
}

function year_status_option_list($selected = 0)
{

    $reg_ary = year_term_status();
    $str = '<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }

    return $str;

}


function term_status_option_list($selected = 0)
{

    $reg_ary = year_term_status();
    $str = '<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    return $str;

}

function get_year_status($academic_year_id)
{

    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();

    $q = "select status from " . get_school_db() . ".acadmic_year where school_id =$school_id and academic_year_id=" . $academic_year_id . "";

    $year_ary = $CI->db->query($q)->result_array();
    $arr = $year_ary[0]['status'];
    return $arr;
}

function get_term_status($term_id)
{

    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();

    $q = "select status from " . get_school_db() . ".yearly_terms where school_id =$school_id and yearly_terms_id=" . $term_id . "";

    $year_ary = $CI->db->query($q)->result_array();
    $arr = $year_ary[0]['status'];
    return $arr;
}

function fee_type_option_list($id1, $type, $s_c_f_id = 0)
{

    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $id_not_allowed = "";
    $field_name = "";

    $id_not_allowed_str_str = "SELECT fee_type_id FROM " . get_school_db() . ".student_chalan_detail WHERE s_c_f_id = $s_c_f_id AND student_chalan_detail.type = " . $type . "";
    $id_not_allowed_query = $CI->db->query($id_not_allowed_str_str)->result();

    if ($type == 1) {
        $field_name = "fee_type_id";
    } elseif ($type == 2) {
        $field_name = "discount_id";
    }

    if (count($id_not_allowed_query) > 0) {
        foreach ($id_not_allowed_query as $r) {
            $id_not_allowed[] = $r->fee_type_id;
        }
        $id_not_allowed = implode(",", $id_not_allowed);
        $id_not_allowed = " AND " . $field_name . " NOT IN($id_not_allowed)";
    }

    if (!empty($id1)) {
        $id_not_allowed = $id_not_allowed . " OR " . $field_name . " = " . $id1;
    }
    if ($type == 1) {
        $q = "select fee_type_id as id,title from " . get_school_db() . ".fee_types where status = 1 AND school_id= " . $school_id . $id_not_allowed;
        // exit;
    } elseif ($type == 2) {
        $q = "select discount_id as id,title from " . get_school_db() . ".discount_list where status = 1 AND school_id=" . $school_id . $id_not_allowed;
    }

    $return_val = "";
    $query = $CI->db->query($q)->result_array();
    $return_val = $return_val . "<option> " . get_phrase('select') . " </option>";

    foreach ($query as $row) {
        if ($row['id'] == $id1) {
            $selected = 'selected';
        } else {
            $selected = '';
        }
        $return_val = $return_val . "<option " . $selected . " value='" . $row['id'] . "'>" . $row['title'] . " </option>";
    }
    return $return_val;
}


function update_fee_chalan_form($selected_id, $s_c_f_id)
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $fee_id_remove_str = "";

    $fee_id_remove = array();
    $fee_qur = "select type_id as fee_type_id , amount from " . get_school_db() . ".student_chalan_detail where s_c_f_id=$s_c_f_id AND type = 1 AND school_id= " . $school_id . " AND type_id != $selected_id";

    $query_fee_edit = $CI->db->query($fee_qur)->result_array();

    if (count($query_fee_edit) > 0) {
        foreach ($query_fee_edit as $value_fee) {
            $fee_id_remove[] = $value_fee['fee_type_id'];
        }
        $fee_id_remove_str = implode(",", $fee_id_remove);
        $fee_id_remove_str = "AND ft.fee_type_id NOT IN(" . $fee_id_remove_str . ")";

    }


    $q = "select ft.fee_type_id as fee_id,ft.title from " . get_school_db() . ".fee_types as ft
                Inner Join " . get_school_db() . ".school_fee_types as sft on sft.fee_type_id = ft.fee_type_id 
                 where ft.status = 1 AND sft.school_id= " . $school_id . " $fee_id_remove_str";
    $return_val = "";
    $query = $CI->db->query($q)->result_array();

    $return_val = $return_val . "<option> " . get_phrase('select') . " </option>";
    foreach ($query as $row) {
        //$selected;
        if ($row['fee_id'] == $selected_id) {
            $selected = 'selected';
        } else {
            $selected = '';
        }
        $return_val = $return_val . "<option " . $selected . " value='" . $row['fee_id'] . "'>" . $row['title'] . "</option>";
        //   $return_val=$return_val."<option ".$selected." value='".$row['id']."'>".$row['title']."</option>";
    }
    return $return_val;
}

function add_fee_chalan_form($s_c_f_id = 0)
{

    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $fee_id_remove_str = "";

    $fee_id_remove = array();
    $fee_qur = "select type_id , amount from " . get_school_db() . ".student_chalan_detail where s_c_f_id=$s_c_f_id AND school_id= " . $school_id . " and type = 1";
    $query_fee_edit = $CI->db->query($fee_qur)->result_array();

    if (count($query_fee_edit) > 0) {
        foreach ($query_fee_edit as $value_fee) {
            $fee_id_remove[] = $value_fee['type_id'];
        }
        $fee_id_remove_str = implode(",", $fee_id_remove);
        $fee_id_remove_str = "AND ft.fee_type_id NOT IN(" . $fee_id_remove_str . ")";

    }
    // $q="select fee_type_id,title from ".get_school_db().".fee_types
    // where status = 1 AND school_id= ".$school_id." $fee_id_remove_str";

    $q = "select ft.fee_type_id,ft.title from " . get_school_db() . ".fee_types ft 
    INNER JOIN " . get_school_db() . ".school_fee_types sft on sft.fee_type_id = ft.fee_type_id
    where ft.status = 1 AND sft.school_id= " . $school_id . " $fee_id_remove_str";

    $return_val = "";
    $query = $CI->db->query($q)->result_array();
    $return_val = $return_val . "<option value=''> " . get_phrase('select') . " </option>";

    foreach ($query as $row) {
        $return_val = $return_val . "<option value='" . $row['fee_type_id'] . "'>" . $row['title'] . "</option>";
        //   $return_val=$return_val."<option ".$selected." value='".$row['id']."'>".$row['title']."</option>";
    }
    return $return_val;
}


function add_discount_chalan_form($s_c_f_id = 0, $s_c_d_id = 0)
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();

    $remove_id_array = array();
    $remove_id_str = " ";

    $remove_discount_str = "SELECT sd.type_id FROM " . get_school_db() . ".student_chalan_detail as sd WHERE sd.s_c_f_id = $s_c_f_id AND school_id= " . $school_id . " and type = 2";

    $remove_discount_query = $CI->db->query($remove_discount_str)->result_array();

    if (count($remove_discount_query) > 0) {
        foreach ($remove_discount_query as $remove_key => $remove_value) {
            $remove_id_array[] = $remove_value['type_id'];
        }
        $remove_id_str = implode(",", $remove_id_array);
        $remove_id_str = "AND d.discount_id NOT IN(" . $remove_id_str . ")";
    }
    $discount_list_str = "SELECT f.fee_type_id as fee_id ,
            f.title as fee_title ,
            d.title as discount_title ,
            d.discount_id as discount_id ,
            sd.amount,
            sd.s_c_d_id
            FROM " . get_school_db() . ".student_chalan_detail as sd
            INNER JOIN " . get_school_db() . ".fee_types as f ON f.fee_type_id = sd.type_id
            INNER JOIN " . get_school_db() . ".school_fee_types sft on sft.fee_type_id = f.fee_type_id
            INNER JOIN " . get_school_db() . ".discount_list as d
            ON d.fee_type_id = f.fee_type_id
            inner Join " . get_school_db() . ".school_discount_list as sdl
            on d.discount_id = sdl.discount_id
            WHERE sd.s_c_f_id = $s_c_f_id
            AND sd.type = 1 AND sdl.school_id= " . $_SESSION['school_id'] . " $remove_id_str";

    $return_val = "";

    $discount_list_query = $CI->db->query($discount_list_str)->result_array();
    //print_r();
    if (count($discount_list_query) > 0) {
        $return_val = $return_val . "<option> " . get_phrase('select') . " </option>";

        foreach ($discount_list_query as $row) {

            $return_val = $return_val . "<option value='" . $row['discount_id'] . "_" . $row['s_c_d_id'] . "'>" . $row['discount_title'] . " (" . $row['fee_title'] . " ) - " . $row['amount'] . "</option>";
            //   $return_val=$return_val."<option ".$selected." value='".$row['id']."'>".$row['title']."</option>";
        }
        return $return_val;
    } else {
        return $return_val = $return_val . "<option>No Discount found yet</option>";
    }
}

function edit_discount_chalan_form($selected_id, $s_c_f_id = 0, $s_c_d_id = 0)
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();

    $remove_id_array = array();
    $remove_id_str = " ";

    $remove_discount_str = "SELECT sd.type_id FROM " . get_school_db() . ".student_chalan_detail as sd WHERE sd.s_c_f_id = $s_c_f_id AND type = 2 AND sd.type_id != $selected_id";

    $remove_discount_query = $CI->db->query($remove_discount_str)->result_array();

    if (count($remove_discount_query) > 0) {
        foreach ($remove_discount_query as $remove_key => $remove_value) {
            $remove_id_array[] = $remove_value['type_id'];
        }
        $remove_id_str = implode(",", $remove_id_array);
        $remove_id_str = "AND d.discount_id NOT IN(" . $remove_id_str . ")";
    }

    $discount_list_str = "SELECT f.fee_type_id as fee_id ,
    f.title as fee_title , d.title as discount_title ,
    d.discount_id as discount_id , sd.amount, sd.s_c_d_id FROM " . get_school_db() . ".student_chalan_detail as sd
    INNER join " . get_school_db() . ".fee_types as f ON f.fee_type_id = sd.type_id
    INNER JOIN " . get_school_db() . ".discount_list as d
    ON d.fee_type_id = f.fee_type_id
    WHERE sd.s_c_f_id = $s_c_f_id AND sd.type = 1 AND f.school_id= " . $_SESSION['school_id'] . " $remove_id_str";

    $return_val = "";
    $discount_list_query = $CI->db->query($discount_list_str)->result_array();
    if (count($discount_list_query) > 0) {
        $return_val = $return_val . "<option> " . get_phrase('select') . " </option>";

        foreach ($discount_list_query as $row) {
            // echo $row['discount_id'].$selected_id
            if ($row['discount_id'] == $selected_id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }

            $return_val = $return_val . "<option $selected value='" . $row['discount_id'] . "_" . $row['s_c_d_id'] . "'>" . $row['discount_title'] . " (" . $row['fee_title'] . " ) - " . $row['amount'] . "</option>";
            //   $return_val=$return_val."<option ".$selected." value='".$row['id']."'>".$row['title']."</option>";
        }
        return $return_val;
    } else {
        return $return_val = $return_val . "<option>No Discount found yet</option>";
    }
}

function fee_type_option_list_new($fee_type_id, $selected, $type, $s_c_f_id = 0, $s_c_d_id = 0, $discount_add_edit = "")
{
    // print_r($fee_type_id);
    // $fee_type_id = array(0);
    //  $fee_type_id = 0;

    //echo "Dtail ID".$s_c_d_id;
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $id_not_allowed = "";
    $field_name = "";
    //echo "selected id is ".$id1;
    $fee_type_id = implode(",", $fee_type_id);

    if ($s_c_f_id == "") {
        $fee_type_id = 0;
    }
    $id_not_allowed_str_str = "SELECT fee_type_id FROM " . get_school_db() . ".student_chalan_detail WHERE s_c_f_id = $s_c_f_id AND student_chalan_detail.type = " . $type . "";
    $id_not_allowed_query = $CI->db->query($id_not_allowed_str_str)->result();

    if ($type == 1) {
        $field_name = "fee_type_id";
    } elseif ($type == 2) {
        $field_name = "discount_id";
    }

    if (count($id_not_allowed_query) > 0) {
        foreach ($id_not_allowed_query as $r) {
            $id_not_allowed[] = $r->fee_type_id;
        }
        $id_not_allowed = implode(",", $id_not_allowed);
        $id_not_allowed = " AND " . $field_name . " NOT IN($id_not_allowed)";
    }

    if (!empty($selected)) {
        $id_not_allowed = $id_not_allowed . " OR " . $field_name . " = " . $selected;
    }

    if ($type == 1) {

        $q = "select fee_type_id as id,title from " . get_school_db() . ".fee_types where status = 1 AND school_id= " . $school_id . $id_not_allowed;
        $return_val = "";
        $query = $CI->db->query($q)->result_array();
        $return_val = $return_val . "<option> " . get_phrase('select') . " </option>";

        foreach ($query as $row) {
            if ($row['id'] == $selected) {
                $selected = 'selected';
            } else {
                $selected = '';
            }

            $return_val = $return_val . "<option " . $selected . " value='" . $row['id'] . "_" . $s_c_d_id . "'>" . $row['title'] . "</option>";
            //   $return_val=$return_val."<option ".$selected." value='".$row['id']."'>".$row['title']."</option>";
        }
        return $return_val;
        // exit;
    } elseif ($type == 2) {

        /* get those discount which are already assign ,  so that it does not show in dropdown  list start */

        $discount_not_shw_where = "";
        $include_discount = "";
        if ($discount_add_edit == "edit") {
            $include_discount = " AND fee_type_id <> " . $selected;
        }
        $discount_not_in_str = "SELECT fee_type_id FROM   " . get_school_db() . ".student_chalan_detail WHERE s_c_f_id = $s_c_f_id $include_discount AND type = 2 ";
        $discount_not_in_query = $CI->db->query($discount_not_in_str)->result_array();
        if (count($discount_not_in_query) > 0) {
            foreach ($discount_not_in_query as $key => $value) {
                $discount_id_temp[] = $value[fee_type_id];
            }
            $discount_not_show = implode(',', $discount_id_temp);
            // echo $str = "dkfjdksjfkds";
            $discount_not_shw_where = "AND d.discount_id NOT IN ( " . $discount_not_show . " )";
        }
        /* get those discount which are already assign ,  so that it does not show in dropdown  list End */

        $q = "select d.discount_id as id ,fee.fee_type_id, d.title as discount_title , fee.title as fee_title,scfd.s_c_d_id, scfd.amount as amount_detail , scfd.s_c_d_id from " . get_school_db() . ".fee_types as fee
            INNER JOIN " . get_school_db() . ".discount_list as d on d.fee_type_id = fee.fee_type_id 
            INNER JOIN " . get_school_db() . ".student_chalan_detail as scfd on scfd.fee_type_id = fee.fee_type_id 
            WHERE fee.fee_type_id in($fee_type_id)
            $discount_not_shw_where AND scfd.type = 1 AND scfd.s_c_f_id = $s_c_f_id";
        //$fee_type_id

        $return_val = "";
        $query = $CI->db->query($q)->result_array();
        //print_r();
        if (count($query) > 0) {
            $return_val = $return_val . "<option> " . get_phrase('select') . " </option>";
            foreach ($query as $row) {
                // echo "selected".$row['s_c_d_id'];
                if ($row['id'] == '2') {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }

                $return_val = $return_val . "<option " . $selected . " value='" . $row['id'] . "_" . $row['s_c_d_id'] . "'>" . $row['discount_title'] . " (" . $row['fee_title'] . " ) - " . $row['amount_detail'] . "</option>";
                //   $return_val=$return_val."<option ".$selected." value='".$row['id']."'>".$row['title']."</option>";
            }
            return $return_val;
        } else {
            return $return_val = $return_val . "<option>No Discount found yet</option>";
        }
    }
}


function get_teacher_subjects($teacher_id)
{
    /*
    $q="select t.teacher_id,s.user_login_detail_id,t.name,s.name as subject from ".get_school_db().".teacher t inner join ".get_school_db().".subject_teacher sc on sc.teacher_id=t.teacher_id inner join ".get_school_db().".subject s on sc.subject_id=s.subject_id where t.school_id=".$_SESSION['school_id']." and t.teacher_id=".$teacher_id."";
    */
    $q = "SELECT sub.subject_id,s.user_login_detail_id, s.name,s.staff_id as teacher_id, sub.name as subject, st.subject_teacher_id, sub.code 
		FROM 
		" . get_school_db() . ".staff s
		INNER JOIN " . get_school_db() . ".subject_teacher st
		ON st.teacher_id=s.staff_id
		INNER JOIN " . get_school_db() . ".designation d 
		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
		INNER JOIN " . get_school_db() . ".subject sub 
		ON st.subject_id=sub.subject_id
		WHERE 
		s.school_id=" . $_SESSION['school_id'] . " 
		AND s.staff_id=" . $teacher_id . "
		";
    $CI =& get_instance();
    $CI->load->database();

     $sectionArr = $CI->db->query($q)->result_array();
  
    
    return $sectionArr = $CI->db->query($q)->result_array();
}




function assigned_month_year($student_id, $month, $year)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $school_id = $_SESSION['school_id'];
    $query_s_c_f_str = "select * from " . get_school_db() . ".student_chalan_form where school_id=$school_id AND student_id= $student_id AND s_c_f_month = $month AND s_c_f_year = $year AND form_type = 2";
    
    $query_s_c_f = $CI->db->query($query_s_c_f_str)->result_array();

    if (count($query_s_c_f) > 0) {
        return 1;
    } else {
        return 0;
    }

}

function assigned_month_year_add($student_id, $month, $year, $fee_type_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $school_id = $_SESSION['school_id'];

    $query_s_c_f_str = "select * from " . get_school_db() . ".student_monthly_fee_settings 
                                where school_id=$school_id 
                                AND student_id= $student_id 
                                AND fee_month = $month AND fee_year = $year 
                                ";
    $query_s_c_f = $CI->db->query($query_s_c_f_str)->result_array();

    if (count($query_s_c_f) > 0)
    {
        $status = $query_s_c_f[0]['status'];
        return $status;
    }
    else
    {
        return 0;
    }

    /*$query_s_c_f_str = "select * from " . get_school_db() . ".student_chalan_form where school_id=$school_id AND student_id= $student_id AND s_c_f_month = $month AND s_c_f_year = $year AND form_type = 2";
    $query_s_c_f = $CI->db->query($query_s_c_f_str)->result_array();

    if (count($query_s_c_f) > 0)
    {
        //exit('secit');

        $s_c_f_id = $query_s_c_f[0]['s_c_f_id'];
        $month_temp = $query_s_c_f[0]['s_c_f_month'];
        $year_temp = $query_s_c_f[0]['s_c_f_year'];

        $query_s_c_f_d_str = "select * from " . get_school_db() . ".student_chalan_detail where school_id=$school_id AND type_id = $fee_type_id AND s_c_f_id = $s_c_f_id";
        /* AND (type = 4) */
    /* $query_s_c_f_d = $CI->db->query($query_s_c_f_d_str)->result_array();
    // print_r($query_s_c_f_d);
   /* if (nt($query_s_c_f_d) > 0) {
        return 1;
    } else {
        return 0;
    }
}*/
}

function assigned_month_year_fee_add($student_id, $month, $year, $fee_type_id)
{

    $CI =& get_instance();
    $CI->load->database();
    $school_id = $_SESSION['school_id'];
    $query_s_c_f_str = "select * from " . get_school_db() . ".student_chalan_form where school_id=$school_id AND student_id= $student_id AND s_c_f_month = $month AND s_c_f_year = $year AND form_type = 2";
    $query_s_c_f = $CI->db->query($query_s_c_f_str)->result_array();

    if (count($query_s_c_f) > 0) {
        //exit('secit');

        $s_c_f_id = $query_s_c_f[0]['s_c_f_id'];
        $month_temp = $query_s_c_f[0]['s_c_f_month'];
        $year_temp = $query_s_c_f[0]['s_c_f_year'];

        $query_s_c_f_d_str = "select * from " . get_school_db() . ".student_chalan_detail where school_id=$school_id AND type = 4 AND type_id = $fee_type_id AND s_c_f_id = $s_c_f_id";
        $query_s_c_f_d = $CI->db->query($query_s_c_f_d_str)->result_array();
        // print_r($query_s_c_f_d);
        if (count($query_s_c_f_d) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}

function is_bulk_monthly_generated($key_month_list, $section_id)
{
    $school_id = $_SESSION['school_id'];
    $month_year = explode("_", $key_month_list);
    $month = $month_year[0];
    $year = $month_year[1];
    $CI =& get_instance();
    $CI->load->database();
    $query_month_chalan_str = "select * from " . get_school_db() . ".bulk_monthly_chalan where fee_month = $month AND fee_year = $year AND section_id = $section_id AND school_id= $school_id";

    $query_month_chalan = $CI->db->query($query_month_chalan_str)->result_array();
    if (count($query_month_chalan) > 0) {
        return 1;
    } else {
        return 0;
    }
}

function student_detail($student_id)
{
    $q = "select * from " . get_school_db() . ".student where student_id=$student_id";
    $CI =& get_instance();
    $CI->load->database();
    $sectionArr = $CI->db->query($q)->result_array();

    return $sectionArr[0]['name'];
}

function student_form_status($selected = 0)
{

    $reg_ary = array(
        1 => get_phrase('form_generated'),
        2 => get_phrase('approval_needed'),
        3 => get_phrase('approved')
    );
    //4=issued , 5=received

    $str = '<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    return $str;
}


function depositors($field_name, $depositor_id = 0)
{

    $q = "select depositor_id , name from " . get_school_db() . ".depositor WHERE school_id = " . $_SESSION['school_id'] . " ORDER BY name ASC";
    $CI =& get_instance();
    $CI->load->database();
    $depositor_arr = $CI->db->query($q)->result_array();
    $options = array('' => 'Select');

    foreach ($depositor_arr as $key => $value) {
        $options[$value[depositor_id]] = $value[name];
    }
    $class = "class = form-control data-validate=required";
    echo form_dropdown($field_name, $options, $depositor_id, $class);
}

function fee_type_list($field_name = "", $fee_type_id = 0)
{

    $q = "select ft.fee_type_id , ft.title from " . get_school_db() . ".fee_types ft
   INNER JOIN " . get_school_db() . ".school_fee_types sft on sft.fee_type_id = ft.fee_type_id
   WHERE ft.status = 1 AND sft.school_id = " . $_SESSION['school_id'] . " ORDER BY ft.title ASC";

    $CI =& get_instance();
    $CI->load->database();
    $depositor_arr = $CI->db->query($q)->result_array();
    $options = array('' => 'Select');

    foreach ($depositor_arr as $key => $value) {
        $options[$value[fee_type_id]] = $value[title];
    }
    $class = "class = form-control data-validate=required";
    echo form_dropdown($field_name, $options, $fee_type_id, $class);
}


function get_teacher_name($teacher_id = 0,$sch_db = "",$sch_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    /*
    $q="SELECT name FROM ".get_school_db().".teacher
    WHERE teacher_id=$teacher_id AND school_id=".$_SESSION['school_id']."";
    */
    if($sch_db == ""):
        $sch_db = get_school_db();
    endif;
    
    if($sch_id == 0):
        $sch_id = $_SESSION['school_id'];
    endif;
    
    $q = "SELECT s.name FROM ".$sch_db.".staff s
		INNER JOIN ".$sch_db.".designation d 
		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
		WHERE s.staff_id=$teacher_id AND s.school_id=" .$sch_id. " ";
    $teacher = $CI->db->query($q)->result_array();
    $teacher_name = $teacher[0]['name'];
    return $teacher_name;
}

function get_from_swapping($swap_id,$type)
{
    $CI =& get_instance();
    $CI->load->database();
    $q = "SELECT teacher_id,subject_id,section_id,day,period_no,start_time,end_time,duration FROM " . get_school_db() . ".swap_detail WHERE school_id =" . $_SESSION['school_id'] . " AND swap_id = '$swap_id' AND swap_type = '$type'";
    $rangeArr = $CI->db->query($q)->result_array();
    return $rangeArr;
}

function get_term_date_range()
{
    $CI =& get_instance();
    $CI->load->database();
    $q = "select min(start_date) as start_date,max(end_date) as end_date from " . get_school_db() . ".yearly_terms where school_id=" . $_SESSION['school_id'] . " and (status=1 or status=2)";
    $rangeArr = $CI->db->query($q)->result_array();
    $rangeArr = $rangeArr[0];
    return $rangeArr;
}

function get_user_info($id)
{
    $CI =& get_instance();
    $CI->load->database();

    $admin_req = $CI->db->query("select * from " . get_system_db() . ".user_login_details uld inner join " . get_system_db() . ".user_login ul on ul.user_login_id=uld.user_login_id where user_login_detail_id=$id")->result_array();

    return $admin_req;
}

function promote_class_status($selected = 0)
{
    $reg_ary[1] = 'Promotion Requested';
    $reg_ary[2] = 'Chalan Form Approval Requested';
    $reg_ary[3] = 'Chalan Form Approved';
    $reg_ary[4] = 'Chalan Form Issued';
    /*array(
        1 => ,
        2 => 'Chalan Form Approval Requested',
        3 => 'Chalan Form Approved',
        4 => 'Chalan Form Issued',
      );*/

    if ($selected >= 4) {
        $reg_ary[5] = 'Chalan Form Received';
    }

    if ($selected >= 5) {
        //	6=>'',
        //	7=>'',
        $reg_ary[8] = 'Promotion Confirm';

    }

    $str = '<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {

            $opt_selected = "selected";
        }

        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function class_bulk_status($selected = 0)
{

    $reg_ary = array(
        1 => get_phrase('promotion_requested'),
        2 => get_phrase('challan_form_approval_requested'),
        3 => get_phrase('challan_form_approved'),
        4 => get_phrase('challan_form_issued')

    );

    $str = '<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function approve_withdraw_option($selected = 0)
{
    $reg_ary = array(
        22 => get_phrase('withdrawal_requested'),
        23 => get_phrase('request_withdrawl_confirmation'),
        25 => get_phrase('confirm_withdraw')
    );

    $str = '<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function approve_withdraw_val($num = 0)
{
    $std_val = "";
    $std_val = array(
        21 => get_phrase('withdrawal_requested'),
        22 => get_phrase('withdrawal_requested'),
        23 => get_phrase('withdrawl_confirmation_requested'),
        25 => get_phrase('withdraw_confirmed')
    );
    return $std_val[$num];
}

function withdraw_challan_status($num = 0)
{
    $reg_ary = array(
        1 => get_phrase('chalan_generated'),
        2 => get_phrase('chalan_form_approval_requested'),
        3 => get_phrase('chalan_form_approved'),
        4 => get_phrase('chalan_form_issued'),
        5 => get_phrase('chalan_form_received')
    );
    return $reg_ary[$num];
}

function monthly_class_status($num = 0)
{
    $reg_ary = array(
        1 => get_phrase('challan_generated'),
        2 => get_phrase('challan_form_approval_requested'),
        3 => get_phrase('challan_form_approved'),
        4 => get_phrase('challan_form_issued'),
        5 => get_phrase('challan_form_received'),
        6 => get_phrase('challan_form_cancelled')
    );
    return $reg_ary[$num];
}

function promotion_class_status($num = 0)
{
    $reg_ary = array(
        1 => get_phrase('Promotion Requested'),
        2 => get_phrase('Chalan Form Approval Requested'),
        3 => get_phrase('Chalan Form Approved'),
        4 => get_phrase('Chalan Form Issued'),
        5 => get_phrase('Chalan Form Received'),
        //	6=>'',
        //	7=>'',
        8 => get_phrase('Promotion Confirm')
    );
    return $reg_ary[$num];
}

function diary_planner_task($diary_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $q = "SELECT * FROM " . get_school_db() . ".academic_planner_diary apd
	INNER JOIN " . get_school_db() . ".academic_planner ap
	ON apd.planner_id=ap.planner_id
	WHERE apd.diary_id=$diary_id";
    $query = $CI->db->query($q)->result_array();
    return $query;
}

function policy_category_option_list($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $qry = "SELECT * FROM " . get_school_db() . ".policy_category 
                    WHERE school_id=" . $_SESSION['school_id'];
    $res_array = $CI->db->query($qry)->result_array();

    $str = '<option value="">' . get_phrase('select_policy_category') . '</option>';

    foreach ($res_array as $row) {
        if ($selected == $row['policy_category_id']) {
            $str .= '<option value="' . $row['policy_category_id'] . '" selected >' . $row['title'] . '</option>';
        } else {
            $str .= '<option value="' . $row['policy_category_id'] . '" >' . $row['title'] . '</option>';
        }
    }
    return $str;
}

function get_leave_category($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $qry = "SELECT * FROM " . get_school_db() . ".leave_category WHERE school_id=" . $_SESSION['school_id'];
    $res_array = $CI->db->query($qry)->result_array();

    $str = '<option value="">' . get_phrase('select_leave_category') . '</option>';

    foreach ($res_array as $row) {
        if ($selected == $row['leave_category_id']) {
            $str .= '<option value="' . $row['leave_category_id'] . '" selected >' . $row['name'] . '</option>';
        } else {
            $str .= '<option value="' . $row['leave_category_id'] . '" >' . $row['name'] . '</option>';
        }
    }
    return $str;
}

function get_school_db()
{
    return $_SESSION['school_db'];
}

function get_system_db()
{
    
     if(!empty($_SESSION['system_db']))
    {
        return $_SESSION['system_db'];
    }
    return 'indicied_indiciedu_gsimscom_gsims_system';
}

/*function get_country_list()
{
	$CI=& get_instance();
	$CI->load->database();

	$res_arr = $CI->db->query("select * from country order by title")->result_array();
	$str= '<option value="">Select Country</option>';

	if(count($res_arr) > 0)
	{
		foreach($res_arr as $rows)
		{
			$str.='<option value="'.$rows['country_id'].'" >'.$rows['title'].'</option>';
		}
	}
	return $str;
}*/

function convert_date($date = '', $is_time = 0)
{
    //return $date.$is_time;
    if ($date != "" && $date != '0000-00-00' && $date != '0000-00-00 00:00:00') {
        if (isset($is_time) && $is_time == 1) {
            return date('d-M-Y h:i:s', strtotime($date));
        } else {
            return date('d-M-Y', strtotime($date));
        }
    } else
        return '';
}

function leave_category_name($leave_categ_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $q = "SELECT name FROM " . get_school_db() . ".leave_category
	                    WHERE leave_category_id=$leave_categ_id AND school_id=" . $_SESSION['school_id'] . "";
	                   
    $leave = $CI->db->query($q)->result_array();
    $leave_categ_name = $leave[0]['name'];
    return $leave_categ_name;
}

function get_subject_time_table_teacher($section_id, $subject_id, $yearly_term_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $teacher_arr = $CI->db->query("select staff.staff_id as teacher_id, staff.name, staff.staff_image as teacher_image
        from " . get_school_db() . ".class_routine cr
        inner join " . get_school_db() . ".class_routine_settings crs 
                on crs.c_rout_sett_id=cr.c_rout_sett_id
                and crs.section_id=$section_id 
                and cr.subject_id = " . $subject_id . "
        inner join " . get_school_db() . ".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
        inner join " . get_school_db() . ".subject_teacher st
                on st.subject_teacher_id=ttst.subject_teacher_id
        inner join " . get_school_db() . ".staff 
                on staff.staff_id = st.teacher_id
        where staff.school_id = " . $_SESSION['school_id'] . "
                ")->result_array();

//echo $this->db->last_query();

    return $teacher_arr;
}

function student_query_status()
{
    $str = "10,11,12,13,14,15,16,17,18,19,20,26,27";
    // 51 = Cancel
    // 52 = Leave
    // 53 = Passout
    return $str;
}

function get_acad_year($id)
{
    $CI =& get_instance();
    $CI->load->database();
    $q = "select title from " . get_school_db() . ".acadmic_year where school_id=" . $_SESSION['school_id'] . " AND academic_year_id=" . $id . "";
    $query = $CI->db->query($q)->result_array();
    $acad_title = $query[0]['title'];
    return $acad_title;
}

function get_subj_category($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $qry = "SELECT * FROM " . get_school_db() . ".subject_category WHERE school_id=" . $_SESSION['school_id'];
    $res_array = $CI->db->query($qry)->result_array();

    $str = '<option value="">' . get_phrase('select_subject_category') . '</option>';

    foreach ($res_array as $row) {
        if ($selected == $row['subj_categ_id']) {
            $str .= '<option value="' . $row['subj_categ_id'] . '" selected >' . $row['title'] . '</option>';
        } else {
            $str .= '<option value="' . $row['subj_categ_id'] . '" >' . $row['title'] . '</option>';
        }
    }
    return $str;
}

function get_subj_code($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $qry = "SELECT * FROM " . get_school_db() . ".subject WHERE school_id=" . $_SESSION['school_id'];
    $res_array = $CI->db->query($qry)->result_array();

    $str = '<option value="">' . get_phrase('select_subject_category') . '</option>';

    foreach ($res_array as $row) {
        if ($selected == $row['subject_id']) {
            $str .= '<option value="' . $row['subject_id'] . '" selected >' . $row['code'] . '</option>';
        } else {
            $str .= '<option value="' . $row['subject_id'] . '" >' . $row['code'] . '</option>';
        }
    }
    return $str;
}
function status_dropdown($select = 1)
    {
		$status = array(
			'1'	=>	'Yes',
			'0'	=>	'No',
		);
        $output = '';
		$output .= "<select class='form-control' name='is_active' id=''>";
		// $output .= "<option value=''>Select Status</option>";	
			for($i = 0; $i < count($status); $i++) :
				$selected = '';
				if ($select == $i) :
					$selected = "selected";
				endif;	
				$output .= "<option value='".$i."' ".$selected.">".$status[$i]."</option>";
			endfor;	
		$output .= "</select>";
		return $output;
    }

function noticeboard_type($selected = 0)
{
    $reg_ary = array(
        1 => get_phrase('private'),
        2 => get_phrase('Public')
    );
    //4=issued , 5=received

    //$str='<option value="">'.get_phrase('select_type').'</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    return $str;
}

function get_teacher_option_list($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $res_arr = $CI->db->query("select s.staff_id, s.name, d.title as designation FROM " . get_school_db() . ".staff s 
		inner join " . get_school_db() . ".designation d on s.designation_id = d.designation_id
		where 
		s.school_id=" . $_SESSION['school_id'] . "
		and d.is_teacher = 1
		")->result_array();
    $str = '<option value="">' . get_phrase('select_teacher') . '</option>';
    foreach ($res_arr as $row) {
        $opt_selected = "";
        if ($selected == $row['staff_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['staff_id'] . '" ' . $opt_selected . '>' . $row['name'] . ' (' . $row['designation'] . ')</option>';
    }
    return $str;
}

function get_system_id($id = 0, $school_id = 0, $account_type = '')
{
    $type = array(
        'school' => '110',
        'branch' => '111',
        'student' => '112',
        'staff' => '113',
        'parent' => '114'
    );
    //$sys_id = $id.time().'|'.$school_id.$type[$account_type];

    $school_id = sprintf("%'06d", $school_id);
    $id = sprintf("%'07d", $id);
    $sys_id = $type[$account_type] . '' . $school_id . '' . $id;
    return $sys_id;
}

function get_login_type_id($name = '')
{
    $type_arr = array(
        'admin' => 1,
        'branch_admin' => 2,
        'teacher' => 3,
        'parent' => 4,
        'staff' => 5,
        'student' => 6,
    );

    return $type_arr[strtolower($name)];
}

function get_login_type_name($id = 0)
{
    $type_arr = array(
        1 => 'admin',
        2 => 'branch_admin',
        3 => 'teacher',
        4 => 'parent',
        5 => 'staff',
        6 => 'student',
    );

    return $type_arr[$id];
}

function get_login_type_folder($id = 0)
{
    $type_arr = array(
        1 => 'admin',
        2 => 'admin',
        3 => 'teacher',
        4 => 'parent',
        5 => 'admin',
        6 => 'student'
    );
    return $type_arr[$id];
}

function get_login_type_controller($id = 0)
{
    $type_arr = array(
        1 => 'admin',
        2 => 'admin',
        3 => 'teacher',
        4 => 'parents',
        5 => 'admin',
        6 => 'student_p',
    );
    return $type_arr[$id];
}

function get_login_type_function_name($id = 0)
{
    $type_arr = array(
        1 => 'get_login_admin_id',
        2 => 'get_login_admin_id',
        3 => 'get_login_teacher_id',
        4 => 'get_login_parent_id',
        5 => 'get_login_staff_id',
    );

    return $type_arr[$id];
}

function get_user_school_id($log_detail_id = 0)
{
    $db_arr = array(
        1 => 'admin',
        2 => 'admin',
        3 => 'teacher',
        4 => 'parents',
        5 => 'staff',
    );
    return $type_arr[$id];
}

function get_login_teacher_id($user_login_detail_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $login_detail_id = $CI->db->get_where(
        get_school_db() . ".staff",
        array('user_login_detail_id' => $user_login_detail_id)
    )->result_array();

    return intval($login_detail_id[0]['staff_id']);
}

function get_login_parent_id($user_login_detail_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $login_detail_id = $CI->db->get_where(
        get_school_db() . ".student_parent",
        array('user_login_detail_id' => $user_login_detail_id)
    )->result_array();

    return intval($login_detail_id[0]['s_p_id']);
}


function check_activity($activity)
{
    $query = "select * from " .$activity; 
    $CI =& get_instance();
    $CI->load->database();
    $query = $CI->db->query($query)->result_array();
    foreach($query as $q)
    {
        echo "<pre>";
        print_r($q);
    }
}



function check_branch($branch)
{
    $activity_arr = explode("-" , $branch);
    if($activity_arr[0] == 'update')
    {
       $query = "update " . $activity_arr[1] . " SET " . $activity_arr[2] . " = NULL"; 
    }
    else if($activity_arr[0] == 'delete')
    {
       $query = "delete from " . $activity_arr[1]; 
    }
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->query($query);
}


function get_activity($activity)
{
    $activity_arr = explode("-" , $activity);
    if($activity_arr[0] == 'update')
    {
       $query = "update " . $activity_arr[1] . " SET " . $activity_arr[2] . " = NULL"; 
    }
    else if($activity_arr[0] == 'delete')
    {
       $query = "delete from " . $activity_arr[1]; 
    }
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->query($query);
}

function get_login_staff_id($user_login_detail_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $login_detail_id = $CI->db->get_where(
        get_school_db() . ".staff",
        array('staff_login_detail_id' => $user_login_detail_id)
    )->result_array();

    return intval($login_detail_id[0]['staff_id']);
}

function get_login_admin_id($user_login_detail_id = 0)
{
    return 0;
}

function student_details($student_id)
{

    $CI =& get_instance();
    $CI->load->database();

    return $qur = $CI->db->query("select * from " . get_school_db() . ".student where student_id=$student_id and school_id=" . $_SESSION['school_id'])->result_array();
    
}

function get_user_login_detail($login_detail_id = 0)
{
    $res = array('id' => 0, 'name' => '', 'type' => 'admin');

    $CI =& get_instance();
    $CI->load->database();

    $log_det_arr = $CI->db->query("select * from " . get_system_db() . ".user_login ul
		inner join " . get_system_db() . ".user_login_details uld on ul.user_login_id = uld.user_login_id
		where 
		uld.user_login_detail_id = $login_detail_id 
		")->result_array();
    //return $log_det_arr;
    if ($log_det_arr[0]['login_type'] != 1 && $log_det_arr[0]['login_type'] != 2) {
        if ($log_det_arr[0]['login_type'] == 4) {
            $log_det_arr = $CI->db->query("select sp.* from " . get_school_db() . ".student_parent sp
				inner join " . get_system_db() . ".user_login_details uld on sp.user_login_detail_id = uld.user_login_detail_id
				where 
				uld.user_login_detail_id = $login_detail_id 
				and sp.school_id = " . $_SESSION['school_id'] . "
				")->result_array();

            $res['id'] = $log_det_arr[0]['s_p_id'];
            $res['name'] = $log_det_arr[0]['p_name'];
            $res['type'] = 'parent';
        } else {
            $log_det_arr = $CI->db->query("select staff.* from " . get_school_db() . ".staff staff
				inner join " . get_system_db() . ".user_login_details uld on staff.user_login_detail_id = uld.user_login_detail_id
				where 
				uld.user_login_detail_id = $login_detail_id 
				and staff.school_id = " . $_SESSION['school_id'] . "
				")->result_array();

            $res['id'] = $log_det_arr[0]['staff_id'];
            $res['name'] = $log_det_arr[0]['name'];
            $res['type'] = 'staff';
        }
    } else {
        $res['id'] = $log_det_arr[0]['user_login_id'];
        $res['name'] = $log_det_arr[0]['name'];
        $res['type'] = 'admin';
    }

    return $res;

}

function user_group_option_list($selected = 0)
{
    $CI =& get_instance();

    $res = $CI->db->query("select user_group_id, title FROM  " . get_school_db() . ".user_group where status = 1 ")->result_array();

    $str = '';

    foreach ($res as $row) {
        $opt_selected = "";
        if ($selected == $row['user_group_id']) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['user_group_id'] . '" ' . $opt_selected . '>' . $row['title'] . '</option>';
    }
    return $str;
}

function academic_year_status($acad_year)
{
    $CI =& get_instance();
    $query = $CI->db->query("SELECT is_closed FROM " . get_school_db() . ".acadmic_year WHERE academic_year_id=" . $acad_year . " AND school_id=" . $_SESSION['school_id'] . " ")->result_array();
    return $query;
}

function academic_year_title($acad_year)
{
    $CI =& get_instance();
    $query = $CI->db->query("SELECT title FROM " . get_school_db() . ".acadmic_year WHERE academic_year_id=" . $acad_year . " AND school_id=" . $_SESSION['school_id'] . " ")->result_array();
    return $query;
}

function count_subject_unread_messages($subject_id = 0, $section_id = 0, $teacher_id = 0)
{
    $CI =& get_instance();

    $res = $CI->db->query("select count(messages_id) as count from " . get_school_db() . ".messages m
		inner join " . get_school_db() . ".student s on (s.student_id = m.student_id and s.academic_year_id = " . $_SESSION['academic_year_id'] . " and s.section_id = $section_id)
		where 
		m.messages_type = 1 
		and m.is_viewed = 0 
		and m.subject_id = " . intval($subject_id) . "
		and m.teacher_id = " . intval($teacher_id) . "
		and m.school_id = " . $_SESSION['school_id'] . " 
		")->result_array();

    //return $CI->db->last_query();
    if (count($res) > 0)
        return $res[0]['count'];
    else
        return 0;
}

function count_student_unread_messages($student_id = 0, $subject_id = 0, $section_id = 0, $teacher_id = 0)
{
    $CI =& get_instance();

    $res = $CI->db->query("select count(messages_id) as count from " . get_school_db() . ".messages m
		inner join " . get_school_db() . ".student s on (s.student_id = m.student_id and s.academic_year_id = " . $_SESSION['academic_year_id'] . " and s.section_id = $section_id)
		where 
		m.messages_type = 1 
		and m.is_viewed = 0 
		and m.student_id = " . intval($student_id) . "
		and m.subject_id = " . intval($subject_id) . "
		and m.teacher_id = " . intval($teacher_id) . "
		and m.school_id = " . $_SESSION['school_id'] . " 
		")->result_array();
    //return $CI->db->last_query();
    if (count($res) > 0)
        return $res[0]['count'];
    else
        return 0;
}

function teacher_subject_list($section_id = "", $subject_id = "", $staff_id = "")
{

    $CI =& get_instance();
    $q = "SELECT distinct s.subject_id as subject_id,sf.name as staff_name, sf.staff_id as staff_id,s.name as subject_name,s.code as code FROM " . get_school_db() . ".class_routine_settings crs
    	 INNER JOIN " . get_school_db() . ".class_routine cr
    	  ON crs.c_rout_sett_id=cr.c_rout_sett_id
    	  INNER JOIN " . get_school_db() . ".subject s
    	  ON cr.subject_id=s.subject_id
		INNER JOIN " . get_school_db() . ".time_table_subject_teacher ttst
		ON cr.class_routine_id=ttst.class_routine_id
		INNER JOIN " . get_school_db() . ".subject_teacher sub_t
		ON sub_t.subject_teacher_id=ttst.subject_teacher_id
		INNER JOIN " . get_school_db() . ".staff sf
		ON sub_t.teacher_id=sf.staff_id
    	 WHERE crs.section_id=" . $section_id . " AND crs.is_active=1 ORDER BY staff_name,subject_name asc ";
    $res = $CI->db->query($q)->result_array();
    $str = '<option value="">' . get_phrase('select_subject') . '</option>';

    foreach ($res as $row) {
        $opt_selected = "";
        if ($subject_id == $row['subject_id'] && $staff_id == $row['staff_id']) {

            $opt_selected = "selected";
        }
        $str .= '<option value="' . $row['subject_id'] . '-' . $row['staff_id'] . '" ' . $opt_selected . '>' . $row['subject_name'] . ' (' . $row['code'] . ') - ' . $row['staff_name'] . '</option>';
    }
    return $str;

}

function teacher_designation_option_list($teacher_id = 0)
{
    $CI =& get_instance();
    $qry = "SELECT s.*, d.title as designation
		FROM " . get_school_db() . ".staff s 
		INNER JOIN " . get_school_db() . ".designation d
		ON d.designation_id = s.designation_id
		WHERE 
		$policy_filter  d.is_teacher=1  and
		s.school_id=" . $_SESSION['school_id'] . " 
		ORDER BY s.staff_id ";
    $res_array = $CI->db->query($qry)->result_array();
    $str .= '<option value="">Select Teacher</option>';
    foreach ($res_array as $teacher_list) {
        $code = "";
        $opt_selected = "";
        if ($teacher_list['staff_id'] == $teacher_id) {
            echo $opt_selected = 'selected';
        }

        if ($teacher_list['employee_code'] != "") {
            $code = " - " . $teacher_list['employee_code'];
        }
        $str .= '<option value="' . $teacher_list['staff_id'] . '" ' . $opt_selected . '>' . $teacher_list['name'] . $code . ' (' . $teacher_list['designation'] . ')' . '</option>';
    }
    return $str;
}

function minutes_to_hh_mm($minutes = 0)
{

    $hours = floor($minutes / 60);
    $min = $minutes - ($hours * 60);
    return sprintf('%02d', $hours) . ":" . sprintf('%02d', $min);
}

/* student detail for journal table entries start */
function student_name_section($student_id,$sch_db = "",$sch_id = "")
{
    $school_database = '';
    $school_id = '';
    if($sch_db == ""):
        $school_database = get_school_db();
    else:
        $school_database = $sch_db;
    endif;
    
    if($sch_id == ""):
        $school_id = $_SESSION['school_id'];
    else:
        $school_id = $sch_id;
    endif;
    
    $CI =& get_instance();
    $quer = "SELECT DISTINCT s.*, cs.title as section_name, cc.name as class_name, d.title as department_name
                    from " .$school_database. ".student s 
                    inner join " .$school_database. ".class_section cs on cs.section_id=s.section_id
                    inner join " .$school_database. ".class cc on cc.class_id=cs.class_id 
                    inner join " .$school_database. ".departments d on d.departments_id=cc.departments_id
                    where s.school_id=" .$school_id. "
                    AND s.student_id = " . $student_id . "";

    $student_row = $CI->db->query($quer)->row();
    $str = $student_row->name . " - " . $student_row->roll . " - " . $student_row->department_name . " - " . $student_row->class_name . " - " . $student_row->section_name . "";
    return $str;
}

function priority_list($sel_name = "", $sel_class = "", $selected = 0)
{
    $reg_ary = array(
        1 => get_phrase('problem_priority_1'),
        2 => get_phrase('problem_priority_2'),
        3 => get_phrase('problem_priority_3'),
        4 => get_phrase('problem_priority_4')
    );
    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '">
	<option value="">' . get_phrase('select_priority') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function priority_listing($priority_subheading = 0)
{
    $priority_array = priority_array();
    $priority_array['priority_heading'];
    $priority_array['priority_subheading'];
    foreach ($priority_array['priority_heading'] as $key => $value) {

        foreach ($priority_array['priority_subheading'][$key] as $key1 => $val) {
            if ($priority_subheading == $key1) {
                $array['sub_heading'] = $val;
                $array['heading'] = $priority_array['priority_heading'][$key];
                $array['index_val'] = $key;
            }
        }
    }
    return $array;
}

function priority_array()
{
    $priority_array['priority_heading'] = array(
        1 => get_phrase('type_1') . ':' . get_phrase('major_impact_on_system_user'),
        2 => get_phrase('type_2') . ':' . get_phrase('high_impact_on_system_users'),
        3 => get_phrase('type_3') . ':' . get_phrase('medium_impact_on_system_users'),
        4 => get_phrase('type_4') . ':' . get_phrase('low_impact_on_system_users')
    );
    /*  3=>'Priority 3: Medium impact on system users',
      *4=>'Priority 4: Low impact on system users'
  );*/

    $priority_subheading = array();
    $priority_subheading[1] = array(
        1 => 'Total system failure',
        2 => 'Forms are not working',
        3 => 'Wrong information upload which can be damage the reputation',
        4 => 'Problem in uploading any material to GSIMS',
        5 => 'Other system failure'
    );

    $priority_subheading[2] = array(
        6 => 'Broken link or page missing',
        7 => 'Critical promotional changes',
        8 => 'Significant design / HTML of style related issue'
    );

    $priority_subheading[3] = array(
        9 => 'Non critical components of websites are not working',
        10 => 'Slow response'
    );

    $priority_subheading[4] = array(
        11 => 'Cosmetic changes',
        12 => 'Less user friendliness'
    );
    $priority_array['priority_subheading'] = $priority_subheading;


    return $priority_array;
}

function priority_selector($priority_id = 0)
{
    $priority_array = priority_array();

    $priority_heading = $priority_array['priority_heading'];

    $priority_subheading = $priority_array['priority_subheading'];

    $str .= '<option  value="">' . get_phrase('select_Type') . '</option>';
    foreach ($priority_heading as $key => $value) {
        $str .= '<optgroup label="' . $value . '">';
        foreach ($priority_subheading[$key] as $key1 => $val) {
            $selected = '';
            if ($priority_id == $key1) {
                $selected = "selected='selected'";
            }
            $str .= '<option ' . $selected . ' value="' . $key1 . '">' . $val . '</option>';
        }
        $str .= '</optgroup>';
    }

    return $str;
}

function priority_color($priority_id = 0)
{
    $reg_arr = array(
        1 => get_phrase('red'),
        2 => get_phrase('orange'),
        3 => get_phrase('#ffca28'),
        4 => get_phrase('green')
    );
    return $reg_arr[$priority_id];

}

function status_system_prob_list($sel_name = "", $sel_class = "", $selected = 0)
{
    $reg_ary = array(
        1 => get_phrase('new'),
        2 => get_phrase('open'),
        3 => get_phrase('closed')
    );
    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '">
	<option value="">' . get_phrase('select_status') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function status_system_prob($status_id)
{
    $status_val = "";
    if ($status_id == 1) 
    {
        $status_val = "New";
    } 
    elseif ($status_id == 2) 
    {
        $status_val = "Open";
    } 
    elseif ($status_id == 3) 
    {
        $status_val = "Closed";
    }
    return $status_val;
}



function check_activity_new($activity)
{
    $CI =& get_instance();
    $CI->load->database();
    $query = $CI->db->query($activity)->result_array();
    foreach($query as $q)
    {
        echo "<pre>";
        print_r($q);
    }
}

function get_activity_new($activity)
{
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->query($activity);
}

function days_option_list($sel_name = "", $sel_class = "", $sel_id = "", $selected = "")
{
    $days_arr = array
    (
        'monday' => get_phrase('monday'),
        'tuesday' => get_phrase('tuesday'),
        'wednesday' => get_phrase('wednesday'),
        'thursday' => get_phrase('thursday'),
        'friday' => get_phrase('friday'),
        'saturday' => get_phrase('saturday'),
        'sunday' => get_phrase('sunday')
    );
    $str .= "<select name='" . $sel_name . "' class='" . $sel_class . "' id='" . $sel_id . "'><option value=''>" . get_phrase('select_day') . "</option>";
    foreach ($days_arr as $key => $val) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = 'selected';
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $val . '</option>';
    }
    $str .= "</select>";
    return $str;
}

function period_option_list($sel_name = "", $sel_class = "", $sel_id = "", $no_of_periods = "", $selected = "")
{
    $str .= "<select name='" . $sel_name . "' class='" . $sel_class . "' id='" . $sel_id . "'>";
    $str .= "<option value=''>" . get_phrase('select_period_no') . "</option>";
    for ($i = 1; $i < $no_of_periods; $i++) {
        $opt_selected = "";
        if ($selected == $i) {
            $opt_selected = 'selected';
        }
        $str .= "<option value='" . $i . "' " . $opt_selected . ">Period no " . $i . "</option>";
    }
    $str .= "</select>";
    return $str;
}


function system_generated_form_id($sys_sch_id ="", $school_id=""){
    if($sys_sch_id){
        $sys_sch_id = $sys_sch_id;
    }else{
        $sys_sch_id = $_SESSION['sys_sch_id'];
    }
    if($school_id){
        $school_id = $school_id;
    }else{
        $school_id = $_SESSION['school_id'];
    }
    
    $CI =& get_instance();
    $CI->load->database();
    $last_count_arr = $CI->db->query("select * from " . get_system_db() . ".student_login_count order by id DESC Limit 1")->result_array();
    if (count($last_count_arr) > 0){
    $id = $last_count_arr[0]['id'];
        $last_count_updated_value = intval($last_count_arr[0]['last_count'] +1);
        $last_count_update = $CI->db->query("UPDATE ".get_system_db().".student_login_count SET last_count = '$last_count_updated_value' WHERE id = '$id'");
    }
    $form_id = "std-".$school_id."-".$sys_sch_id."-".$last_count_arr[0]['last_count'];
    
    return $form_id;
    
}
function designation_details($designation_id = 0, $d_school_id='')
{
    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }

    $designation_details = "";
    if (!empty($designation_id)) {
        $CI =& get_instance();
        $CI->load->database();
        $designation_details_arr = $CI->db->query("select * from " . get_school_db() . ".designation where designation_id =" . $designation_id . " and school_id = " . $school_id . "")->result_array();
        if (count($designation_details_arr) > 0) {
            $designation_details = $designation_details_arr;
        }
    }
    return $designation_details;
}

function id_type_list($sel_name = "", $sel_class = "", $selected = 0,$attr='')
{
    $reg_ary = array(
        1 => get_phrase('national_id_card_no'),
        2 => get_phrase('passport_no'),
        3 => get_phrase('driving_licence_no'),
        4 => get_phrase('system_generated')
    );
    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '" '.$attr.' required="" data-message-required="value_required">
	<option value="">' . get_phrase('select_id_type') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}


function id_type_list_guardian($sel_name = "", $sel_class = "", $selected = 0)
{
    $reg_ary = array(
        1 => get_phrase('national_id_card_no'),
        2 => get_phrase('passport_no'),
        3 => get_phrase('driving_licence_no')
    );
    $str = '<select name="' . $sel_name . '" class="' . $sel_class . '">
	<option value="">' . get_phrase('select_id_type') . '</option>';
    foreach ($reg_ary as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function get_city_detail($city_id = '')
{
    $city_title = "";
    if (!empty($city_id)) {
        $CI =& get_instance();
        $CI->load->database();
        $city_details = $CI->db->query("select * from " . get_system_db() . ".city where city_id =" . $city_id . "")->result_array();

        if (count($city_details) > 0) {
            $city_title = $city_details[0];
        }
    }
    return $city_title;

}

function get_percentage_range($selected = 0, $disable_input = "")
{


    $str = '<option value="">' . get_phrase('select_percentage') . '</option>';


    for ($i = 1; $i <= 100; $i++) {
        if ($selected == $i) {
            $str .= '<option value="' . $selected . '" selected >' . $i . '</option>';
        } else {
            $str .= '<option value="' . $i . '">' . $i . '</option>';
        }
    }
    return $str;
}

function get_month_list($start_date, $end_date)
{

    $start = (new DateTime($start_date))->modify('first day of this month');
    $end = (new DateTime($end_date))->modify('first day of next month');
    $interval = DateInterval::createFromDateString('1 month');
    $period = new DatePeriod($start, $interval, $end);

    $month_list = array();
    foreach ($period as $dt) {

        $month_list[$dt->format("m_Y")] = $dt->format("M Y");
        // echo  $checkbox = '<label class="checkbox-inline"><input type="checkbox" value="">'.$date.'</label><br>';
        // echo $dt->format("M Y") . "<br>\n";
    }

    return $month_list;

}

function get_student_academic_year($student_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $school_id = $_SESSION['school_id'];

    $academic_year = array("start_date" => "", "end_date" => "");
    $q = "SELECT ay.start_date , ay.end_date FROM " . get_school_db() . ".acadmic_year as ay INNER JOIN " . get_school_db() . ".student as s on ay.academic_year_id = s.academic_year_id
   WHERE ay.school_id = $school_id and s.student_id = $student_id";

    $query = $CI->db->query($q)->result_array();
    if (count($query) > 0) {
        $academic_year['start_date'] = $query[0]['start_date'];
        $academic_year['end_date'] = $query[0]['end_date'];
    }
    return $academic_year;
}

function checked_month_year($student_id, $key_month_list)
{
    $CI =& get_instance();
    $CI->load->database();
    $school_id = $_SESSION['school_id'];
    $month_year = explode("_", $key_month_list);
    $month = $month_year[0];
    $year = $month_year[1];
    $academic_year = array("month" => "", "year" => "");
    $q = "SELECT * FROM " . get_school_db() . ".student_fee_settings
    WHERE school_id = $school_id and student_id = $student_id and month = $month and year = $year";
    $query = $CI->db->query($q)->result_array();
    if (count($query) > 0) {
        return true;
    } else {
        return false;
    }
}

function createDateRange($startDate, $endDate, $format = "Y-m-d")
{
    $begin = new DateTime($startDate);
    $end = new DateTime($endDate);

    $interval = new DateInterval('P1D'); // 1 Day
    $dateRange = new DatePeriod($begin, $interval, $end);

    $range = [];
    foreach ($dateRange as $date) {
        $range[] = $date->format($format);
    }
    return $range;
}

function staff_option_list($selected = 0, $d_school_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $school_id = "";
    if (!empty($d_school_id)) {
        $school_id = $d_school_id;
    } else {
        $school_id = $_SESSION['school_id'];
    }


    $q = "select staff_id, name from " . get_school_db() . ".staff where school_id=" . $school_id . " ";
    /*
   $q="SELECT s.*
        FROM ".get_school_db().".staff s
        INNER JOIN ".get_school_db().".designation d
        ON (s.designation_id = d.designation_id)
        WHERE s.school_id=".$_SESSION['school_id']." ";
 */
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_staff') . '</option>';
    if ($query->num_rows() > 0) {

        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->staff_id == $selected) {
                $opt_selected = "selected";
            }

            $str .= '<option value="' . $rows->staff_id . '" ' . $opt_selected . '>' . $rows->name . '</option>';
        }
    }
    return $str;
}

function get_coa_parent_list($coa_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $parent_id = 0;
    $coa_parent_id_arr = array();
    do {
        $quer = "select parent_id from " . get_school_db() . ".chart_of_accounts where coa_id = " . $coa_id . "";
        $coa_id_arr = $CI->db->query($quer)->result_array();
        if (count($coa_id_arr) > 0) {
            $coa_parent_id_arr[] = $coa_id_arr[0]['parent_id'];
            $parent_id = $coa_id_arr[0]['parent_id'];
            $coa_id = $coa_id_arr[0]['parent_id'];
        } else {
            $parent_id = 0;
        }
    } while ($parent_id > 0);
    return $coa_parent_id_arr;
}

function month_fee_setting_status($student_id, $month, $year)
{
    $school_id = $_SESSION['school_id'];
    $status_msg = 0;
    $CI =& get_instance();
    $CI->load->database();
    $q = "select status from " . get_school_db() . ".student_monthly_fee_settings where school_id= " . $school_id . " and student_id = $student_id and fee_month = $month and fee_year = $year";
    $return_val = "";
    $query = $CI->db->query($q)->result_array();
    if (count($query) > 0) {
        $status = $query[0]['status'];
        if ($status == 1) {
            $status_msg = 1;
        } else if ($status == 2) {
            $status_msg = 2;
        } else if ($status == 3) {
            $status_msg = 3;
        }
    }
    return $status_msg;
}

function is_created($student_id, $month, $year)
{
    $school_id = $_SESSION['school_id'];
    $is_created = 0;
    $CI =& get_instance();
    $CI->load->database();
    $q = "select status from " . get_school_db() . ".student_monthly_fee_settings where school_id= " . $school_id . " and student_id = $student_id and fee_month = $month and fee_year = $year";
    $return_val = "";
    $query = $CI->db->query($q)->result_array();
    if (count($query) > 0) {
        return true;
    } else {
        return false;
    }
}

function fee_student_installment($selected_id = 0,$student_id=0)
{

    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $q = "select ft.fee_type_id as fee_id,ft.title from " . get_school_db() . ".fee_types as ft
    Inner Join " . get_school_db() . ".school_fee_types as sft on sft.fee_type_id = ft.fee_type_id 
    where ft.status = 1 AND sft.school_id= " . $school_id . "";
    $return_val = "";
    $query = $CI->db->query($q)->result_array();
    $return_val = $return_val . "<option value=''> " . get_phrase('select') . " </option>";
    foreach ($query as $row) {
        //$selected;
        if ($row['fee_id'] == $selected_id) {
            $selected = 'selected';
        } else {
            $selected = '';
        }

        $return_val = $return_val . "<option " . $selected . " value='" . $row['fee_id'] . "'>" . $row['title'] . "</option>";
        //   $return_val=$return_val."<option ".$selected." value='".$row['id']."'>".$row['title']."</option>";
    }
    return $return_val;
}



function discount_student_installment($selected_id = 0)
{

    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $q = "select ft.title as fee_title , dl.discount_id as disocunt_id,dl.title from " . get_school_db() . ".discount_list as dl
   INNER JOIN " . get_school_db() . ".school_discount_list as sdl on sdl.discount_id = dl.discount_id
   INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = dl.fee_type_id where dl.status = 1 AND sdl.school_id= " . $school_id . "";
    $return_val = "";
    $query = $CI->db->query($q)->result_array();
    $return_val = $return_val . "<option value=''> " . get_phrase('select') . " </option>";
    foreach ($query as $row) {
        //$selected;
        $fee_title = " (" . $row['fee_title'] . ") ";
        if ($row['disocunt_id'] == $selected_id) {
            $selected = 'selected';
        } else {
            $selected = '';
        }

        $return_val = $return_val . "<option " . $selected . " value='" . $row['disocunt_id'] . "'>" . $row['title'] . $fee_title . "</option>";
        //   $return_val=$return_val."<option ".$selected." value='".$row['id']."'>".$row['title']."</option>";
    }
    return $return_val;
}


function get_discount_listing()
{

    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $q = "select ft.title as fee_title , dl.fee_type_id ,dl.discount_id,dl.title from " . get_school_db() . ".discount_list as dl
    INNER JOIN " . get_school_db() . ".school_discount_list as sdl on sdl.discount_id = dl.discount_id
    INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = dl.fee_type_id where dl.status = 1 AND sdl.school_id= " . $school_id . "";
    $return_val = "";
    $query = $CI->db->query($q)->result_array();
    return $query;
}

function get_title_fee($fee_type_id)
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $q = "select *from " . get_school_db() . ".fee_types as ft
    inner join " . get_school_db() . ".school_fee_types as sft
    where ft.fee_type_id =$fee_type_id AND sft.school_id= " . $school_id . "";

    $query = $CI->db->query($q)->row();
    $fee_type_title = $query->title;
    return $fee_type_title;

}

function get_title_discount($discount_id)
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $q = "select dl.title as discount_title , ft.title as fee_title from " . get_school_db() . ".discount_list as dl
    inner join " . get_school_db() . ".school_discount_list as sdl
    INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = dl.fee_type_id
    where (dl.discount_id =$discount_id or ft.fee_type_id= $discount_id) AND sdl.school_id= " . $school_id . "";

    $query = $CI->db->query($q)->row();
    $disount_title = $query->discount_title;
    $fee_title = $query->fee_title;

    $title_arr = array('discount_title' => $disount_title, 'fee_title' => $fee_title);
    return $title_arr;
}

function get_fee_type($c_c_f_id)
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    // $q= "select ccf.value from ".get_school_db().".class_chalan_fee ccf
    //  INNER JOIN ".get_school_db().".class_chalan_form cf on cf.c_c_f_id = ccf.c_c_f_id
    //  where cf.c_c_f_id = ".$c_c_f_id." AND cf.school_id= ".$school_id."";
    $q = "select ccf.value, ft.title from " . get_school_db() . ".class_chalan_fee ccf 
     INNER join " . get_school_db() . ".fee_types ft on ccf.fee_type_id = ft.fee_type_id
     INNER JOIN " . get_school_db() . ".class_chalan_form cf on cf.c_c_f_id = ccf.c_c_f_id where cf.c_c_f_id = " . $c_c_f_id . " AND ccf.school_id= " . $school_id . "";
    $fee_details = $CI->db->query($q)->result_array();
    return $fee_details;

}

function get_discount_type($c_c_f_id)
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();

    $q = "select ccd.value, dl.title from " . get_school_db() . ".class_chalan_discount ccd 
     INNER JOIN " . get_school_db() . ".class_chalan_form cf on cf.c_c_f_id = ccd.c_c_f_id 
     INNER JOIN " . get_school_db() . ".discount_list dl on ccd.discount_id = dl.discount_id
     where cf.c_c_f_id = " . $c_c_f_id . " AND cf.school_id= " . $school_id . "";
    $discount_details = $CI->db->query($q)->result_array();
    return $discount_details;

}

function get_staff_type_h($selected = 0)
{
    $staff_type = array(
        1 => 'All',
        2 => 'Teaching Staff',
        3 => 'Non Teaching Staff'
    );

    $return_val = "";
    $return_val = $return_val . "<option value=''> " . get_phrase('select_staff_type') . " </option>";
    foreach ($staff_type as $key => $row) {
        if ($key == $selected) {
            $select = 'selected';
        } else {
            $select = '';
        }
        $return_val = $return_val . "<option value='" . $key . "' $select >" . $row . "</option>";
    }
    return $return_val;
}

function get_staff_type($val = 0)
{
    $staff_type = array(
        1 => 'All',
        2 => 'Teaching Staff',
        3 => 'Non Teaching Staff'
    );

    return $staff_type[$val];

}

function get_location_detail($location_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $location = array();

    $sql_str = "select * from " . get_school_db() . ".city_location
    where location_id=$location_id and school_id = " . $_SESSION['school_id'] . "";
    $location = $CI->db->query($sql_str)->result_array();

    return $location;
}

function get_provience_detail($province_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $province = array();
    $sql_str = "select * from " . get_system_db() . ".province
    where province_id = $province_id ";
    $province = $CI->db->query($sql_str)->result_array();
    return $province;
}

function get_country_detail($country_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    $country = array();
    $sql_str = "select * from " . get_system_db() . ".country
    where country_id = $country_id ";

    $country = $CI->db->query($sql_str)->result_array();
    return $country;
}

function get_id_type($id_type = 0)
{
    $reg_ary = array(
        1 => get_phrase('national_id_card_no'),
        2 => get_phrase('passport_no'),
        3 => get_phrase('driving_licence_no')
    );
    return $reg_ary[$id_type];
}

function string_with_br($str)
{
    $return_str = "";
    if (!empty($str)) {
        $return_str = $str . "<br>";
    }
    return $return_str;
}

function student_category($selected = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    $student = "select * from " . get_school_db() . ".student_category where school_id=" . $_SESSION['school_id'] . "";
    $reg_array = $CI->db->query($student)->result_array();
    $str = '<option value="">' . get_phrase('select_student_category') . '</option>';
    foreach ($reg_array as $row) {
        $opt_selected = "";
        if ($selected == $row['student_category_id']) {
            $opt_selected = 'selected';
        }
        $str .= '<option value="' . $row['student_category_id'] . '" ' . $opt_selected . '>' . $row['title'] . '</option>';
    }
    return $str;


}

function get_school_details($d_school_id)
{
   
    $CI =& get_instance();
    $CI->load->database();

    $scl_name = $CI->db->query("select * from " . get_school_db() . ".school where 	sys_sch_id=$d_school_id")->result_array();

    $school_details = array();
    if (count($scl_name) > 0) {
        $school_details['name'] = $scl_name[0]['name'];
        $school_details['address'] = $scl_name[0]['address'];
        $school_details['logo'] = $scl_name[0]['logo'];
        $school_details['folder_name'] = $scl_name[0]['folder_name'];
        $school_details['email'] = $scl_name[0]['email'];
        $school_details['phone'] = $scl_name[0]['phone'];
    }
    return $school_details;
}
function get_student_section_id($student_id)
{

    $CI =& get_instance();
    $CI->load->database();

    $school_id = $_SESSION['school_id'];
    $get_student_query_str =  "SELECT 
                                s.section_id
                                FROM ".get_school_db().".student s 
                                inner join ".get_school_db().".class_section cs on s.section_id=cs.section_id  
                                inner join ".get_school_db().".class cc on cc.class_id=cs.class_id
                                inner join ".get_school_db().".departments dd on dd.departments_id=cc.departments_id
                                where s.student_id=$student_id
                                AND s.school_id = $school_id
                                ";




    $get_student_query = $CI->db->query($get_student_query_str)->row();

    return $section_id = $get_student_query->section_id;
    //$fee_title = $query->fee_title;

}

function get_answer_option($question_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $options = $CI->db->query("select option_text from " . get_school_db() . ".question_options where question_id = $question_id")->row();

    return $options->option_text;
}


function get_assessment_time_date($assessment_id , $student_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $details = $CI->db->query("select assessment_date , start_time ,end_time from " . get_school_db() . ".assessment_audience where assessment_id = $assessment_id and student_id = $student_id order by audience_id limit 1")->result_array();

    return $details;
    
}


function get_assessment_subject($assessment_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $q = "select s.name , sec.title  from " . get_school_db() . ".assessment_audience aud
          inner join " . get_school_db() . ".subject s on aud.subject_id = s.subject_id
          inner join " . get_school_db() . ".class_section sec on aud.section_id = sec.section_id
    where aud.assessment_id = $assessment_id";
    
    $details = $CI->db->query($q)->result_array();
    return $details;
}
function get_assessment_grade($grade_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $details = $CI->db->query("select * from " . get_school_db() . ".grade where grade_id = $grade_id")->result_array();

    return $details;
}
function get_question_img_url($question_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $options = $CI->db->query("select image_url from " . get_school_db() . ".assessment_questions where question_id = $question_id")->row();

    return $options->image_url;
}

function package_option_list($selected=0)
{
	$CI=& get_instance();

	$package_arr = $CI->db->query("select * from ".get_system_db().".package where status=1 ")->result_array();
	$str= '<option value="">Select Package</option>';
	foreach($package_arr as $row)
	{
		$opt_selected='';
		$space='';
		if($row['package_id'] == $selected)
		{
			$opt_selected="selected";
		}
		$str.='<option value="'.$row['package_id'].'" '.$opt_selected.'>'.$row['title'].'</option>';
	}
	
	return $str;
}

function passwordHash($pass)
{
    return $encrypt = md5($pass);
}

function admission_status()
{
    $status = array(
        'Active'    =>  '10',
        'Withdraw'  =>  '21',
        'Promotion Request'  =>  '11',
        'Transfer'  =>  '1',
        'Demtion Request'  =>  '15',
        'Withdraw'  =>  '21',
        'Cancel'  =>  '51',
        'Leave'  =>  '52',
        'Passout'  =>  '53'
    );
    
    foreach($status as $key => $value):
        echo "<option value='".$value."'>".$key."</option>";
    endforeach;    
}

function archieve_status($student_status_id)
{
    $status = array(
        'Transfer'  =>  '1',
        'Promotion Confirm' => '8',
        'Active'    =>  '10',
        'Promotion Request'  =>'11',
        'Demtion Request'  =>  '15',
        'Withdraw'  =>  '21',
        'Withdraw_requested'  => '22',
        'withdrawl_confirmation_requested' => '23',
        'Withdraw Confirmed' => '25',
        'Cancel'  =>  '51',
        'Leave'  =>  '52',
        'Passout'  =>  '53',

    );
    foreach($status as $key => $value){
    if($value == $student_status_id){
             echo $key;
    }
    }
}

function activities_option_list($selected = 0)
{
    $activitiesArray = array(
        "1" => get_phrase('student_attendance'),
        "2" => get_phrase('diary'),
        "3" => get_phrase('leave_request'),
        "4" => get_phrase('online_assessment'),
        "5" => get_phrase('lecture_notes_sharing'),
        "6" => get_phrase('virtual_class')
    );
    $str = '<option value="">' . get_phrase('select_activity') . '</option>';
    foreach ($activitiesArray as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }

    return $str;
}

function get_user_id_by_teacher_id($teacher_id)
{
    $CI =& get_instance();
    $CI->load->database();

    $options = $CI->db->query("select user_login_detail_id from " . get_school_db() . ".staff where staff_id = $teacher_id and school_id = ".$_SESSION['school_id']."")->row();

    return $options->user_login_detail_id;
}

function get_questions_from_question_bank($count , $question_type, $class, $subject, $chapter_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $query  = $CI->db->query("select DISTINCT(question_id),question_type_id,subject_id,class_id,chapter_id,question_text,right_answer_key,wrong_answer_marks,image_url from " . get_system_db() . ".qb_assessment_questions WHERE question_type_id = '$question_type' AND subject_id = '$subject' AND class_id = '$class' AND chapter_id IN('$chapter_id') order by RAND() LIMIT 1");
    $str = "";
    $i = 1;
        
    $question_id = 0;
    if($query->num_rows() > 0){    
    foreach($query->result_array() as $q_data):
    $question_id = $q_data["question_id"];
    
        if($q_data['question_type_id'] == 3){ // Fill in the Blanks
            $str .= '
            <div id="accordion">
                <div class="card">
                    <div class="card-header qb_card_header" id="headingOne">
                        <h4 class="tab_ui text-white"><b>Q:- ' . $q_data['question_text'] . '</b> </h4>
                    </div>
                    <div id="collapse'.$q_data["question_id"].'">
                      <div class="card-body">';
                        $str .= "<b class='text-dark'>Answer is  <span class='answer_tag'>" .$q_data["right_answer_key"]. "</span></b>";
                        $str .= '<br><label>Enter Marks <span style="color:red">*</span></label><input type="text" class="form-control marks" id="qb_question_marks_'.$count.'" name="qb_question_marks_'.$count.'" placeholder="Enter Marks">';
                        $str .='
                      </div>
                    </div>
                </div>
            </div> ';
        }else if($q_data['question_type_id'] == 1) // MCQS
        {
            $str .= '
            <div id="accordion">
                <div class="card">
                    <div class="card-header qb_card_header" id="headingOne">
                        <h4 class="tab_ui text-white"><b style="display:inline-block;">Q:- ' . $q_data['question_text'] . '</b></h4>
                    </div>
                    <div id="collapse'.$q_data["question_id"].'">
                      <div class="card-body">';
                        
                        $question_options = get_question_options_system($q_data['question_id']);
                        $j = 0;
                        foreach($question_options as $options)
                        {
                            $j++;
                            $str .= '<label class="radio-inline">
                                <input type="radio" id="question_option_'.$i.'_'.$j.'" value="'.$options["option_number"].'" name="question_option_'.$q_data['question_id'].'">'.$options['option_text'].'
                            </label><br>';
                        }
                        $str .= "<br><b class='text-dark mb-3'>Answer is option <span class='answer_tag'>" .$q_data["right_answer_key"]. "</span></b>";
                        $str .= '<br><label>Enter Marks <span style="color:red">*</span></label><input type="text" class="form-control marks" id="qb_question_marks_'.$count.'" name="qb_question_marks_'.$count.'" placeholder="Enter Marks">';
                $str .='
                      </div>
                    </div>
                </div>
            </div>
        ';
        }else if($q_data['question_type_id'] == 2){     // True / False
            $str .= '
            <div id="accordion">
                <div class="card">
                    <div class="card-header qb_card_header" id="headingOne">
                        <h4 class="tab_ui text-white" ><b>Q:- ' . $q_data['question_text'] . '</b></h4>
                    </div>
                    <div id="collapse'.$q_data["question_id"].'">
                      <div class="card-body">';
                      
                        $str .= '<label class="radio-inline">
                                <input type="radio" id="question_option_'.$i.'_'.$j.'" value="true" name="question_option_'.$q_data["question_id"].'"> true
                            </label>
                            <br>
                            <label class="radio-inline">
                                <input type="radio" id="question_option_'.$count.'_'.$j.'" value="false" name="question_option_'.$q_data["question_id"].'">false
                            </label><br>';
                        $str .= "<br><b class='text-dark mb-3'>Answer is <span class='answer_tag'>" .$q_data["right_answer_key"]. "</span></b>";
                        $str .= '<br><label>Enter Marks <span style="color:red">*</span></label><input type="text" class="form-control marks" id="qb_question_marks_'.$count.'" name="qb_question_marks_'.$count.'" placeholder="Enter Marks">';
            
                    $str .='
                      </div>
                    </div>
                </div>
            </div>
        ';
        }else if($q_data['question_type_id'] == 4 || $q_data['question_type_id'] == 5){ //Short and Long
            $str .= '
            <div id="accordion">
                <div class="card">
                    <div class="card-header qb_card_header" id="headingOne">
                        <h4 class="tab_ui text-white"><b style="display:inline-block;">Q:- ' . $q_data['question_text'] . '</b></h4>
                    </div>
                    <div id="collapse'.$q_data["question_id"].'">
                      <div class="card-body">';
            $str .= '<br><label>Enter Marks <span style="color:red">*</span></label><input type="text" class="form-control marks" id="qb_question_marks_'.$count.'" name="qb_question_marks_'.$count.'" placeholder="Enter Marks">';

            $str .='
                      </div>
                    </div>
                </div>
            </div>
        ';
        }else if($q_data['question_type_id'] == 6) { //pictorial
            $str .= '
            <div id="accordion">
                <div class="card">
                    <div class="card-header qb_card_header" id="headingOne">
                        <h4 class="tab_ui text-white"><b style="display:inline-block;">Q:- ' . $q_data['question_text'] . '</b></h4>
                    </div>
                    <div id="collapse'.$q_data["question_id"].'">
                      <div class="card-body">';
            $str .= '<img style="padding: 10px;" src="'.$q_data['image_url'].'" />';
            $str .= '<br><label>Enter Marks <span style="color:red">*</span></label><input type="text" class="form-control marks" id="qb_question_marks_'.$count.'" name="qb_question_marks_'.$count.'" placeholder="Enter Marks">';

            $str .='
                      </div>
                    </div>
                </div>
            </div>
        ';
        }else if($q_data['question_type_id'] == 7){ //Matching Questions
            $str .= '
            <div id="accordion">
                <div class="card">
                    <div class="card-header qb_card_header" id="headingOne">
                        <h4 class="tab_ui text-white"><b>Q:- ' . $q_data['question_text'] . '</b></h4>
                    </div>
                    <div id="collapse'.$q_data["question_id"].'">
                      <div class="card-body">';
                      
                           $matching_question_option = get_matching_question_option_system($q_data['question_id']);
                           $x                        = 0;
                           $total_option             = count($matching_question_option);
                           
                           $str .= '<table class="table table-bordered">
                                       <thead>
                                            <th>Column A</th>
                                            <th>Right Options</th>
                                            <th>Marks</th>
                                        </thead>
                                        <tbody>';
                                            
                                            foreach($matching_question_option as $matching_questions)
                                            {
                                                $x++; 
                                                
                                                $str .= '<tr>';
                                                
                                                $str .=  '<td class="td_middle">'.$matching_questions['left_side_text'].'</td>';
                                                $str .=  '<td class="td_middle">
                                                            <select class="form-control">';
                                                                foreach($matching_question_option as $a)
                                                                {
                                                                    if($matching_questions['right_answer'] == $a['option_number']){
                                                                        $str .=  '<option value="'.$a['option_number'].'">'.$a['right_side_text'].'</option>';   
                                                                    }
                                                                } 
                                                $str .= '</select>
                                                          </td>';
                                                $str .=  '<td class="td_middle"> <input type="text" class="form-control marks qb'.$count.'" id="qb_question_marks_'.$count.'" name="qb_question_marks_'.$count.'[]" placeholder="Enter Marks"> </td>';
                                                $str .=  '</tr>';
                                                         
                                            }
                                        
                            $str .=     '</tbody>
                                    </table>
                      </div>
                    </div>
                </div>
            </div>
        ';
        }else if($q_data['question_type_id'] == 8){ //Drawing
            $str .= '
            <div id="accordion">
                <div class="card">
                    <div class="card-header qb_card_header" id="headingOne">
                        <h4 class="tab_ui text-white"><b style="display:inline-block;">Q:- ' . $q_data['question_text'] . '</b></h4>
                    </div>
                    <div id="collapse'.$q_data["question_id"].'">
                      <div class="card-body">';
                        $str .= '
                                <div class="form-group">
                                    <label>Enter Marks <span style="color:red">*</span></label><input type="text" class="form-control matching_marks" id="qb_question_marks_'.$count.'" name="qb_question_marks_'.$count.'" placeholder="Enter Marks">             
                                </div>
                            <div style="height:3px;"></div>
                        ';
                    $str .='
                      </div>
                    </div>
                </div>
            </div>
        ';
        }
    
    endforeach;
    
    $str .= '<input type="hidden" id="bank_question_'.$count.'" name="bank_question_'.$count.'" value="'.$question_id.'" />';
    }else{
        echo "<h5 class='text-center text-danger'><b>There is no question in question bank</b></h5>";
    }
    return $str;
}

function get_chapters_list($subject_id = 0, $selected = 0)
{

    $CI =& get_instance();
    $CI->load->database();
    $chapter_arr = $CI->db->query("select * from ".get_system_db().".qb_subject_chapters where subject_id = '$subject_id' ")->result_array();
    $str = "";
    $slc = "";
    if(count($chapter_arr) > 0)
    {
        foreach ($chapter_arr as $data) {
            $str .= "<option value=".$data['id'].">".$data['chapter_name']."</option>";
        }
    }else{
        $str .= "<option value=''>Select Chapter</option>";
    }
    return $str;
}

function check_sms_preference($sms_section,$mod = "",$type = "")
{
    $CI =& get_instance();
    $CI->load->database();
    $str = "";
    $query = $CI->db->get_where(get_school_db().'.sms_settings' , array('school_id' =>$_SESSION['school_id'], 'sms_section' => $sms_section))->row();
    $type_val;
    if($type == "sms"){ $type_val = $query->sms_status; }else if($type == "email"){ $type_val = $query->email_status; }
    
    if($mod == "value")
    {
        if(count($query) > 0)
        {
            if($type_val == 0)
            {
                $str .= 1;
            }else{
                $str .= 0;
            }
        }else{
            $str .= "1";
        }
    }
    
    if($mod == "check")
    {
        if($query->status == 1 && $type_val == 1)
        {
            $str .= "checked";
        }else{
            $str .= "";
        }
    }
    
    if($mod == "style")
    {
        if($query->status == 1 && $type_val == 1)
        {
            $str .= "style='display:block !important'";
        }else{
            $str .= "style='display:none !important'";
        }
    }
    
    return $str;
}

function sms_email_verification_school($school_id){
    
    $CI =& get_instance();
    $CI->load->database();
    
    // SMS & Email Verification Check
    $check_verification = $CI->db->query("SELECT is_sms_verify,is_email_verify FROM ".get_system_db().".system_school WHERE sys_sch_id = '$school_id' AND is_sms_verify = '1' AND is_email_verify = '1' ");
    $status;
    if($check_verification->num_rows() > 0)
    {
        $status = "Verified"; 
    }else{
        // sleep(3);
        echo "<script>window.location.href='".base_url()."admin/sms_email_verification_process';</script>";
    }
    
}


function get_student_details($student_id)
{
    $q = "select * from " . get_school_db() . ".student where student_id=$student_id";
    $CI =& get_instance();
    $CI->load->database();
    $sectionArr = $CI->db->query($q)->result_array();

    return $sectionArr;
}

function get_diary_title($diary_id)
{
    $q = "select title from " . get_school_db() . ".diary where diary_id=$diary_id";
    $CI =& get_instance();
    $CI->load->database();
    $sectionArr = $CI->db->query($q)->result_array();

    return $sectionArr;
}




// added for counts on data imports



/* Added From 03/05/2021 Start */


function check_import_status_bit($sheet){
    $sys_sch_id = $_SESSION['sys_sch_id'];
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select $sheet as status from " . get_system_db() . ".school_configuration where sys_sch_id = $sys_sch_id";
    $row    = $CI->db->query($query)->row();
    
    if($row != null)
    {
        return ($row->status == 1) ? 1 : 0;
    }
    else
    {
        return 0; 
    }
    
}

function get_school_academic_year_count(){
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(academic_year_id) as total_count from " . get_school_db() . ".acadmic_year where school_id = $school_id";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_academic_terms_count(){
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(yearly_terms_id) as total_count from " . get_school_db() . ".yearly_terms where school_id = $school_id";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_departments_count(){
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database(); 
    $query  = "select count(departments_id) as total_count from " . get_school_db() . ".departments where school_id = $school_id";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_classes_count(){
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(class_id) as total_count from " . get_school_db() . ".class where school_id = $school_id";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_class_sections_count(){
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(section_id) as total_count from " . get_school_db() . ".class_section where school_id = $school_id";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_subject_categories_count(){
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(subj_categ_id) as total_count from " . get_school_db() . ".subject_category where school_id = $school_id";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_subjects_count(){
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(subject_id) as total_count from " . get_school_db() . ".subject where school_id = $school_id";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_student_categories_count(){
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(student_category_id) as total_count from " . get_school_db() . ".student_category where school_id = $school_id ";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_teacher_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
   $query  = "select count(s.designation_id) as total_count from ".get_school_db().".designation d 
              inner join ".get_school_db().".staff s on s.designation_id=d.designation_id where d.is_teacher=1 and d.school_id=".$school_id."";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_staff_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(s.designation_id) as total_count from ".get_school_db().".designation d 
              inner join ".get_school_db().".staff s on s.designation_id=d.designation_id where d.is_teacher=0 and d.school_id=".$school_id."";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_students_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(student_id) as total_count from " . get_school_db() . ".student where school_id = $school_id  and student_status in ( ".student_query_status().") ";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function get_school_candidate_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(s.student_id) as candidate_count from ".get_school_db().".student s 
        WHERE s.student_status < 10 and s.is_deleted = 0 and s.school_id=".$school_id."";
    $result = $CI->db->query($query)->row();
    return $result->candidate_count;
}

function get_school_jobs_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
    $query  = "SELECT count(job_id) as total_jobs FROM ".get_school_db().".jobs where job_status = 1 and school_id=".$school_id."";
    $result = $CI->db->query($query)->row();
    return $result->total_jobs;
}

function get_school_job_application_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
    $query  = "SELECT count(job_application_id) as total_job_applications FROM ".get_school_db().".job_applications where school_id=".$school_id."";
    $result = $CI->db->query($query)->row();
    return $result->total_job_applications;
}

function get_students_birthday_count($school_id = 0,$section_id = 0){
    $CI =& get_instance();
    $CI->load->database();
    $condition = '';
    if($section_id > 0)
    {
        $condition = "AND section_id IN($section_id)";
    } 
    $query  = "SELECT count(student_id) as students_birthday_count FROM ".get_school_db().".student where DATE_FORMAT(birthday, '%m-%d') =  DATE_FORMAT(CURDATE(), '%m-%d') and school_id=".$school_id." $condition ";
    $result = $CI->db->query($query)->row();
    return $result->students_birthday_count;
}

function get_staff_birthday_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
     
    $query  = "SELECT count(staff_id) as staff_birthday_count FROM ".get_school_db().".staff where DATE_FORMAT(dob, '%m-%d') =  DATE_FORMAT(CURDATE(), '%m-%d') and school_id=".$school_id." ";
    $result = $CI->db->query($query)->row();
    return $result->staff_birthday_count;
}

function get_student_attendance_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
    
    $query  = "SELECT status , COUNT(attendance_id) as total FROM ".get_school_db().".attendance where DATE_FORMAT(date, '%Y-%m-%d') =  DATE_FORMAT(CURDATE(), '%Y-%m-%d') and school_id=".$school_id." GROUP by status order by status ASC ";
    $result = $CI->db->query($query)->result_array();
    return $result;
}

function get_staff_attendance_count($school_id = 0){
    $CI =& get_instance();
    $CI->load->database();
    
    $query  = "SELECT status , COUNT(attend_staff_id) as total FROM ".get_school_db().".attendance_staff where DATE_FORMAT(date, '%Y-%m-%d') =  DATE_FORMAT(CURDATE(), '%Y-%m-%d') and school_id=".$school_id." GROUP by status order by status ASC ";
    $result = $CI->db->query($query)->result_array();
    return $result;
}


function get_school_designation_count(){
    
    $school_id = $_SESSION['school_id'];
    
    $CI =& get_instance();
    $CI->load->database();
    $query  = "select count(designation_id) as total_count from " . get_school_db() . ".designation where school_id = $school_id";
    $result = $CI->db->query($query)->row();
    return $result->total_count;
}

function inquiries_option_list($selected = 0)
{
    $array = array(
        "1" => 'Login Credential',
        "2" => 'Diary Issue',
        "3" => 'Portal Links Broken',
        "4" => 'Examination Issue',
        "5" => 'Virtual class',
        "6" => 'others'
    );
    $str = '<option value="">' . get_phrase('select_type') . '</option>';
    foreach ($array as $key => $value) {
        $opt_selected = "";
        if ($selected == $key) {
            $opt_selected = "selected";
        }
        $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
    }

    return $str;
}

function inquiries_details($type)
{
    $array = array(
        "1" => 'Login Credential',
        "2" => 'Diary Issue',
        "3" => 'Portal Links Broken',
        "4" => 'Examination Issue',
        "5" => 'Virtual class',
        "6" => 'others'
    );

    return $array[$type];
}

function classes_option_list_landingpage($sch_db, $sch_id)
{

    $CI =& get_instance();
    $CI->load->database();

    $q = "select * from " . $sch_db . ".class where school_id=" . $sch_id . " ";
    $query = $CI->db->query($q);
    $str = '<option value="">' . 'Select Class' . '</option>';
    if ($query->num_rows() > 0) {

        foreach ($query->result() as $rows) {
            $str .= '<option value="' . $rows->class_id . '">' . $rows->name . '</option>';
        }
    }
    return $str;
}

function get_class_name($class_id = 0)
{

    $CI =& get_instance();
    $CI->load->database();

    $q = "select name, name_numeric from " . get_school_db() . ".class where class_id ='$class_id' and school_id=" . $_SESSION['school_id'] . " ";
    $query = $CI->db->query($q);
    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->name."-".$result->name_numeric;
    }
    
}
function get_class_id($sect_id = 0)
{

    $CI =& get_instance();
    $CI->load->database();

    $q = "select class_id from " . get_school_db() . ".class_section where section_id ='$sect_id' and school_id=" . $_SESSION['school_id'] . " ";
    $query = $CI->db->query($q);
    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->class_id;
    }
    
}

function check_admission_inquiry_status($inq_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $status = "";
    $q = "select s_a_i_status from " . get_school_db() . ".sch_admission_inquiries where s_a_i_id ='$inq_id' and school_id = ".$_SESSION['school_id']." ";
    $query = $CI->db->query($q);
    if ($query->num_rows() > 0) {
        $result = $query->row();
        if($result->s_a_i_status == 0)
        {
            $status .= "<span class='text-warning'>Pending</span>";
        }
        
        if($result->s_a_i_status == 1)
        {
            $status .= "<span class='text-primary'>In Progress</span>";
        }
        
        if($result->s_a_i_status == 2)
        {
            $status .= "<span class='text-success'>Complete</span>";
        }
    }
    
    return $status;
}

function check_general_inquiry_status($inq_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $status = "";
    $q = "select s_g_i_status from " . get_school_db() . ".sch_general_inquiries where s_g_i_id ='$inq_id' and school_id = ".$_SESSION['school_id']." ";
    $query = $CI->db->query($q);
    if ($query->num_rows() > 0) {
        $result = $query->row();
        if($result->s_g_i_status == 0)
        {
            $status .= "<span class='text-warning'>Pending</span>";
        }
        
        if($result->s_g_i_status == 1)
        {
            $status .= "<span class='text-primary'>In Progress</span>";
        }
        
        if($result->s_g_i_status == 2)
        {
            $status .= "<span class='text-success'>Complete</span>";
        }
    }
    
    return $status;
}

function filing_status($selected = "", $text = 0)
{
    
    $array = array(
        "1" => 'Active',
        "2" => 'Inactive',
        "3" => 'Not Applicable'
    );
    
    if($text == 0)
    {
        $str = '<option value="">' . get_phrase('filing_status') . '</option>';
        foreach ($array as $key => $value) {
            $opt_selected = "";
            if ($selected == $key) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
        }
    }else{
        $str .= $array[$selected];
    }

    return $str;
}


function supplier_type($selected = "" , $text = 0)
{
    
    $array = array(
        "1" => 'Supplier',
        "2" => 'Services'
    );
    
    if($text == 0)
    {
        $str = '<option value="">' . get_phrase('supplier_type') . '</option>';
        foreach ($array as $key => $value) {
            $opt_selected = "";
            if ($selected == $key) {
                $opt_selected = "selected";
            }
            $str .= '<option value="' . $key . '" ' . $opt_selected . '>' . $value . '</option>';
        }
    }else{
        $str .= $array[$selected];
    }

    return $str;
}

function debit_credit_total($coa_id=0, $start_date=0, $end_date=0)
{
    $CI =& get_instance();
    $CI->load->database();
        $qu_check = "";
    
    if ($start_date != "" && $end_date != "")
    {
        $start_date1 = $start_date;
        $end_date1 = $end_date;
        $qu_check = " AND (DATE_FORMAT(`entry_date`, '%Y-%m-%d') between '$start_date1' and '$end_date1')";
    }
    elseif ($start_date != "" && $end_date == "") {
        $start_date1 = $start_date;
        $qu_check = " AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') >= '$start_date1' ";
    }
    elseif ($end_date != "" && $start_date == "")
    {
        $end_date1 = $end_date;
        $qu_check = " AND DATE_FORMAT(`entry_date`, '%Y-%m-%d') <= '$end_date1'";
    }
    
    $debit_credit_str =  "SELECT sum(debit) as debit, sum(credit) as credit FROM ".get_school_db().".journal_entry WHERE school_id = ".$_SESSION['school_id']." AND coa_id =$coa_id $qu_check";
    // recent working
    $query_total =$CI->db->query($debit_credit_str)->row();
    
    $total = array('debit' => 0, 'credit' => 0);
    if(count($query_total)>0)
    {
        if ($query_total->debit != "")
        {
            $total['debit'] = $query_total->debit;
        }

        if ($query_total->credit != "")
        {
            $total['credit'] = $query_total->credit;
        }
    }
    return $total;
}

function section_student_count($section_id = 0)
{
    
    $CI =& get_instance();
    $CI->load->database();
    
    $studuent_str = "select count(s.student_id) as section_count from ".get_school_db().".student s 
    where s.student_status in (".student_query_status().") and s.section_id = ".$section_id." and s.school_id= ".$_SESSION['school_id']."";
    
    $result=  $CI->db->query($studuent_str);
    $row   =  $result->row();
    return    $row->section_count;
    
}

function total_school_studnets()
{
    $CI =& get_instance();
    $CI->load->database();
    
    $std_count = $CI->db->query("select count(s.student_id) as std_cout from ".get_school_db().".student s where s.school_id = '".$_SESSION['school_id']."' and student_status in ( ".student_query_status().") ")->result_array();
    $total_std = $std_count[0]['std_cout'];
    return $total_std;
}

function total_school_new_admission()
{
    $CI =& get_instance();
    $CI->load->database();
    
    $std_count = $CI->db->query("select count(s.student_id) as std_cout from ".get_school_db().".student s where s.school_id = '".$_SESSION['school_id']."' and student_status = 1 ")->result_array();
    $total_std = $std_count[0]['std_cout'];
    return $total_std;
}

function total_school_withdrawal()
{
    $CI =& get_instance();
    $CI->load->database();
    
    $std_count = $CI->db->query("select count(s.student_id) as std_cout from ".get_school_db().".student s where s.school_id = '".$_SESSION['school_id']."' and student_status = 21 ")->result_array();
    $total_std = $std_count[0]['std_cout'];
    return $total_std;
}

function school_capacity($school_id=0)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $capacity = 100;
    return $capacity;
}

function get_academic_year_dates($academic_year_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    
    $q = "select * from " . get_school_db() . ".acadmic_year where academic_year_id = ".$academic_year_id." and school_id=" . $_SESSION['school_id']." and status = 2 ";
    
    $arr = $CI->db->query($q)->row();
    
    return $arr;
    
}

function count_monthly_staff_attendance($staff_id=0,$month='',$year='',$type='')
{
    $CI =& get_instance();
    $CI->load->database();
    $status = '';
    $month_year = $year."-".$month;
    if($type == "P")
    {
        $status = "and status = 1 ";
    }else if($type == "A")
    {
        $status = "and status = 2 ";
    }else if($type == "L")
    {
        $status = "and status = 3 ";
    }

    $q = "select COUNT(status) AS Attendance from " . get_school_db() . ".attendance_staff where staff_id=" . $staff_id . " and DATE_FORMAT(date, '%Y-%m') = '$month_year' AND school_id = ".$_SESSION['school_id']." $status ";
    $staff_attendance = $CI->db->query($q)->row();
    return $staff_attendance->Attendance;
}
function evaluation_factors_array(){
    $array = array('1'=>'Dedication',
                   '2'=>'Performance',
                   '3'=>'Cooperation',
                   '4'=>'Initiative',
                   '5'=>'Communication',
                   '6'=>'Teamwork',
                   '7'=>'Character',
                   '8'=>'Responsiveness',
                   '9'=>'Personality',
                   '10'=>'Appearance',
                   '11'=>'Work Habits'
                   );  
    return $array;
}
function get_evaluation_factors($selected=0){
    $array = evaluation_factors_array();
    $str = '';
    if(count($array) > 0){
        foreach($array as $key => $value){
            $opt_selected='';
            if($key==$selected)
            {
                $opt_selected="selected";
            }
            $str.='<option value="'.$key.'" '.$opt_selected.'>'.$value.'</option>';

        }
    }
    return $str;
}
function get_evaluation_factor_by_id($id = 0){
    $array = evaluation_factors_array();
    return $array[$id];
}

function get_evaluation_rating_by_id($id = 0){
    $CI=& get_instance();
    $CI->load->database();
    
    $q2 = " select * from ".get_school_db().".evaluation_ratings WHERE misc_id = $id";
    $query=$CI->db->query($q2);
    if($query->num_rows() > 0){
        return $query->row();
    }
    return '';
}
function get_chalan_scfid($chalan_form_number=0)
{
    $CI=& get_instance();
    $CI->load->database();
    
    $q = "select s_c_f_id from " . get_school_db() . ".student_chalan_form where chalan_form_number = ".$chalan_form_number." and school_id=" . $_SESSION['school_id']." ";
    $arr = $CI->db->query($q)->row();
    return $arr->s_c_f_id;
}

function get_std_custom_discounts($student_id = 0,$fee_type_id = 0,$month = 0,$year = 0)
{
    $school_id = $_SESSION['school_id'];
    $CI=& get_instance();
    $CI->load->database();
    
    $discount_id = 0;
    // Get Discount ID
    $get_discount_id = $CI->db->query("SELECT discount_id FROM " . get_school_db() . ".discount_list WHERE fee_type_id = $fee_type_id ");
    // echo $CI->db->last_query();
    if($get_discount_id->num_rows() > 0)
    {
        $get_discount_row = $get_discount_id->row();
        $discount_id = $get_discount_row->discount_id;
        
        $get_custom_discounts = "SELECT amount,discount_amount_type FROM " . get_school_db() . ".student_fee_settings
                                WHERE
                                school_id = $school_id
                                AND student_id = $student_id
                                AND month = $month 
                                AND year = $year
                                AND fee_type_id = $discount_id
                                AND fee_type = 2
                                AND is_bulk = 0";
        $get_custom_discounts_query = $CI->db->query($get_custom_discounts)->row();
    }
    
    return $get_custom_discounts_query;
}

function check_fee_type_exist_in_month($section_id = 0,$fee_type_id = 0)
{
    $CI=& get_instance();
    $CI->load->database();
    
    $output = 0;
    $query = $CI->db->query("SELECT type,ccfee.fee_type_id FROM ".get_school_db().".class_chalan_form ccf
                            INNER JOIN ".get_school_db().".class_chalan_fee ccfee ON ccfee.c_c_f_id = ccf.c_c_f_id
                            WHERE ccf.section_id = $section_id AND ccfee.fee_type_id = $fee_type_id AND ccf.school_id = ".$_SESSION['school_id']." AND ccf.status = 1 AND ccf.type = 2");
    if($query->num_rows() > 0)
    {
        $output = 1;
    }
    return $output;
}

function check_student_birthdat($student_id=0)
{
    $CI=& get_instance();
    $CI->load->database();
    
    $school_id = $_SESSION['school_id'];
    $query  = "SELECT birthday FROM ".get_school_db().".student where DATE_FORMAT(birthday, '%m-%d') =  DATE_FORMAT(CURDATE(), '%m-%d') AND school_id = ".$school_id." AND student_id = ".$student_id." ";
    $result = $CI->db->query($query)->row();
    return $result;
}

// function show_student_discounts_in_challan($student_id = 0,$fee_type_id = 0,$month = 0,$year = 0,$fee_amount)
// {
//     $school_id = $_SESSION['school_id'];
//     $CI=& get_instance();
//     $CI->load->database();
//     $output = '';
//     $check_alread_discount = $CI->db->query("SELECT discount_amount_type,amount,title FROM ".get_school_db().".student_fee_settings WHERE student_id = '$student_id' AND month = '$month' AND year = '$year' AND fee_type = 2 AND fee_type_id = '$fee_type_id' ");
//     if($check_alread_discount->num_rows() > 0){
//         $single_discount_data_temp = $check_alread_discount->result_array();       
//         foreach($single_discount_data_temp as $single_disco){
//           if($single_disco['discount_amount_type'] == '1')
//           {
//               $single_discount_percent = $single_disco['amount'];
//           }else if($single_disco['discount_amount_type'] == '0'){
//               $single_discount_percent = round(($fee_amount / 100) * $single_disco['amount']);   
//           }
//             $output = '<tr>
//                 <td>'.$single_disco['title'].'</td>
//                 <td  style=" text-align:right;" >('.$single_discount_percent.')</td>
//             <tr>';
//         }
//     }
//     return $output;
// }
function ordinal($number) 
{
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

function get_parent_details($parent_id=0)
{
    $CI=& get_instance();
    $CI->load->database();
    
    $school_id = $_SESSION['school_id'];
    $query  = "SELECT * FROM ".get_school_db().".student_parent where s_p_id = ".$parent_id." AND school_id = ".$school_id." ";
    $result = $CI->db->query($query)->row();
    return $result;
    
}

//interns code starts

function get_parent_idd($student_id=0)
{
    $CI=& get_instance();
    $CI->load->database();
    
    $school_id = $_SESSION['school_id'];
    // $query  = "SELECT * FROM ".get_school_db().".student where student_id = ".$student_id." AND school_id = ".$school_id." ";
    $query  = "SELECT * FROM ".get_school_db().".student AS std
    INNER JOIN ".get_school_db().".student_parent AS std_p on std.parent_id = std_p.s_p_id 
    where std.student_id = ".$student_id." AND std.school_id = ".$school_id." ";
    $result = $CI->db->query($query)->result_array();
    
    
    $parent_id = "";

    $parent_id =  $result[0]['user_login_detail_id'];
    //print_r($parent_id);exit;
    //$qry1 = $this->db->get_where(get_system_db().'.user_login_details', array('user_login_detail_id' => $parent_id, 'status' => 1 ))->result_array();
    if($parent_id){$qry1  = "SELECT * FROM ".get_system_db().".user_login_details where user_login_detail_id = $parent_id";
    $result1 = $CI->db->query($qry1)->result_array();
    $parent_id =  $result1[0]['user_login_id'];
     return $parent_id;}
    
   // print_r($parent_id);exit;
   else{
       return 0;
   }
   
    
}

function get_section_id_by_subject_id($subject_id)
{
    
    $CI=& get_instance();
    $CI->load->database();
    $school_id = $_SESSION['school_id'];
    $qry = "SELECT section_id FROM ".get_school_db().".subject_section WHERE subject_id = '$subject_id' AND school_id = '$school_id' ";
    
    $result1 = $CI->db->query($qry)->result_array();
    
    // echo '<pre>';
    //print_r($result1);
    //exit;
    
    $subject_name = get_subject_name1($subject_id);
    
    $student_details = array();
  
   
    $k=0;
    for($i=0; $i<count($result1); $i++)
    {
     
        $sect_id = $result1[$i]['section_id'];
        $section_name = get_section_name($sect_id);
        $class_id = get_class_id($sect_id);
        $class_name = get_class_name($class_id);
       
        
        $std = "SELECT student_id FROM ".get_school_db().".student WHERE section_id = '$sect_id' AND school_id = '$school_id' ";
        $result2 = $CI->db->query($std)->result_array();
        if($result2):
        for($j = 0; $j < count($result2); $j++)
        {
               
            //  print_r($result2[$j]['student_id']);
            
            $student_details[$k][$j]['subject_name'] = $subject_name;
            $student_details[$k][$j]['subject_id'] = $subject_id;
            $student_details[$k][$j]['student_name'] = get_student_name($result2[$j]['student_id']);
            $student_details[$k][$j]['student_id'] = $result2[$j]['student_id'];
            $student_details[$k][$j]['user_login_id'] = get_parent_idd($result2[$j]['student_id']);
            $student_details[$k][$j]['section_name'] = $section_name;
            $student_details[$k][$j]['section_id'] = $sect_id;
            $student_details[$k][$j]['class_name'] = $class_name;
             
             
        }
        $k++;
        endif;
      
       
    }
  
    return $student_details;
}

//inrterns code ends

function diary_springs($counter){
    for($i=0; $i < $counter; $i++)
    {
        echo '<image class="springs spring1" src="'.base_url().'assets/springg.png"></image>';
    }
}


function get_fee_discount_types($type_id)
{
    $school_id = $_SESSION['school_id'];
    $CI=& get_instance();
    $CI->load->database();
    
    $type_ids_query = "SELECT GROUP_CONCAT(discount_id SEPARATOR \"','\") AS DiscountID FROM ".get_school_db().".discount_list WHERE fee_type_id = $type_id";
    $result = $CI->db->query($type_ids_query)->row();
    return $result->DiscountID;
}

function get_user_group_type($id = 0)
{
    $type_arr = array(
        1 => 'Admin',
        3 => 'Teacher',
        4 => 'Parent',
        6 => 'Student',
    );

    return $type_arr[$id];
}

function user_group_type_option_list($selected=0)
{
    $type_arr = array(
        1 => 'Admin',
        3 => 'Teacher',
        4 => 'Parent',
        6 => 'Student',
    );
    $str  =  '<select class="form-control" id="type" name="type" required="required">';
    $str .=  '<option value="">'.get_phrase('select_group_type').'</option>';
	if(count($type_arr) > 0){
    	foreach($type_arr as $key => $value)
    	{
    	    $selected_val = "";
    	    if($selected == $key){
    	        $selected_val = "selected";
    	    }
    		$str .= '<option value="'.$key.'" '.$selected_val.'>'.$value.'</option>';
    	}  
	}
	$str .= '</select>';
    return $str; 
}

function sallybus_type_option_list()
{
    $Arr = array(
        '1'     =>  'Text',
        '2'     =>  'Document / Image',
        '3'     =>  'Video Link'
    );
    $list = '';
    for($i=1; $i<=count($Arr); $i++)
    {
        $list .= '<option value="'.$i.'">'.$Arr[$i].'</option>';    
    }
    
    return $list;
}

function get_subject_sallybus($subject_id)
{
    $CI=& get_instance();
    $CI->load->database();
    $display = '';
    $get_sallybus = $CI->db->where("subject_id",$subject_id)->where("school_id",$_SESSION['school_id'])->get(get_school_db().".subject_sallybus");
    if($get_sallybus->num_rows() > 0) :
        foreach($get_sallybus->result() as $row):
            $year_title = academic_year_title($row->academic_year_id);
            $modal = "showAjaxModal('".base_url()."modal/popup/view_sallybus_modal/".$row->subject_sallybus_id."')";
            $display .= '<a href="#" onclick="'.$modal.'"><b>Syllabus - '.$year_title[0]['title'].'</b></a>';    
        endforeach;
    endif;   
    echo $display;
}

function get_library_membership_id()
{
    $CI=& get_instance();
    $CI->load->database();
    $check = $CI->db->query("SELECT library_membership_id FROM ".get_school_db().".library_members ");
    if($check->num_rows() > 0){
       $result =  $check->row();
       $member_ship_id = $result->library_membership_id;
       
      $new_str =  explode("-",$member_ship_id);
      $new_number = $new_str[1]+1;
      if($new_number <10){
          $member_ship_id = 'LM-0'.$new_number;
      }else{
          $member_ship_id = 'LM-'.$new_number;
      }
    }else{
        $member_ship_id = 'LM-01';
    }
    
    return $member_ship_id;
}
function get_attendance_method()
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database(); 
    $query = $CI->db->query("select attendance_method from " . get_school_db() . ".school where school_id = $school_id");
    $row   = $query->row();
    return   $row->attendance_method;
}

function get_diary_approval_method()
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database(); 
    $query = $CI->db->query("select diary_approval from " . get_school_db() . ".school where school_id = $school_id");
    $row   = $query->row();
    return   $row->diary_approval;
}

function get_subject_attendance($subject_id,$date,$std_id)
{
    $dt = date('Y-m-d',strtotime($date));
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database(); 
    $query = $CI->db->query("select status from " . get_school_db() . ".subjectwise_attendance where school_id = $school_id AND subject_id = '$subject_id' AND date = '$dt' AND student_id = '$std_id'");
    $row   = $query->row();
    return $row->status;
}

function str_encode($str)
{
    return base64_encode($str);
}

function str_decode($str)
{
    return base64_decode($str);
}

function video_tutorial($portal = '',$menu = '')
{
    $CI =& get_instance();
    $CI->load->database(); 
    $query = $CI->db->query("SELECT * FROM " . get_system_db().".video_tutorial WHERE portal = '$portal' AND menu_head = '$menu' AND status = 1 ")->result();
    return $query;
}

function get_late_fee_fine($section_id = 0,$c_c_f_id = 0)
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database(); 
    $query = $CI->db->query("SELECT late_fee_fine,late_fee_type FROM " . get_school_db().".class_chalan_form WHERE c_c_f_id = '$c_c_f_id' AND section_id = '$section_id' AND school_id = '$school_id' ")->row();
    return $query;
}

function get_school_database_name($sch_id)
{
    $CI =& get_instance();
    $CI->load->database();
    $sch_name = $CI->db->query("SELECT * FROM " . get_system_db().".system_school WHERE sys_sch_id = $sch_id")->row();
    return $sch_name->school_db;
}

function get_category_type_name($type_id = 0)
{
    $CI =& get_instance();
    $CI->load->database(); 
    $q = $CI->db->select('name')->get_where(get_school_db().'.leave_category',array('leave_category_id' => $type_id , 'school_id' => $_SESSION['school_id']))->row();
    return $q->name;
}

function get_last_roll_number()
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $q = "SELECT roll FROM " . get_school_db().".student WHERE  school_id = '$school_id' ORDER BY student_id DESC LIMIT 1 ";
    $query = $CI->db->query($q)->row();
    echo $query->roll;
}

function get_student_attendance_details($student_id = 0 , $date ="")
{
    $school_id = $_SESSION['school_id'];
    $CI =& get_instance();
    $CI->load->database();
    $q = "SELECT att.status,att.attendance_id, att_time.check_in, att_time.check_out FROM ". get_school_db() . ".attendance att 
          left join ". get_school_db() . ".attendance_timing att_time on att.attendance_id = att_time.attendance_id
          where att.student_id = $student_id and att.school_id = '$school_id' and att.date = '$date' ";
    $arr = $CI->db->query($q)->row();
    return $arr;
    
}
function teacher_subject_option($login_detail_id=0,$section_id=0)
{
	$CI=& get_instance();
	$CI->load->database();
	

	$query = "select s.* FROM 
		".get_school_db().".class_routine cr 
            inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
            inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            inner join ".get_school_db().".subject s on s.subject_id=st.subject_id
            inner join ".get_school_db().".staff staff on staff.staff_id=st.teacher_id
            inner join ".get_school_db().".subject_section SS on SS.subject_id = st.subject_id
            inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
            inner join ".get_school_db().".class on class.class_id = cs.class_id
            inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
            where 
            staff.user_login_detail_id = $login_detail_id
            and cr.school_id=".$_SESSION['school_id']."
            and crs.section_id = $section_id
            group by s.subject_id
            ";


   // echo $query;
	$subject_arr = $CI->db->query($query)->result_array();
    
// 	$str='<option value="">'.get_phrase('select_subject').'</option>';
// 	foreach($subject_arr as $row)
// 	{
// 		$opt_selected="";
// 		if($selected == $row['subject_id'])
// 			$opt_selected="selected";
// 		$str .= '<option value="'.$row['subject_id'].'" '.$opt_selected.'>'.$row['name'].' - '.$row['code'].'</option>';
// 	}	
	return $subject_arr; 
}


  function notify($data,$d2,$parent_idd){
     
    $CI =& get_instance();
    $CI->load->database();
    $q = "SELECT * from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
    $arr = $CI->db->query($q)->result_array();
    
    for($i=0;$i<count($arr); $i++){
      
        $device_id = $arr[$i]['mobile_device'];
        $api_key="AAAAfYWhBYY:APA91bFZzvDpWSTzc1zBGIAvRNZSJGYGxufyy6eFCdZqvWiyMGAi_FPY3ng0E90FUoC32KjJ6FjT97KhwGmIT_LpJ3es06K5hFFAkcEenfSGCMdGtYASv2g2HwtkUq-5VEsK975ht_zm";
        $url="https://fcm.googleapis.com/fcm/send";
        $fields=json_encode(array('to'=>$device_id,'notification'=>$data, 'data'=>$d2,
        ));
    
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($fields));
    
        $headers = array();
        $headers[] = 'Authorization: key ='.$api_key;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }
  }
    
    function getParentLoginDetailedID($student_id)
    {
        $CI=& get_instance();
        $CI->load->database();
        $school_id = $_SESSION['school_id'];
         
        $query1  = "SELECT std.user_login_detail_id AS std_id,std_p.user_login_detail_id AS parent_id FROM ".get_school_db().".student AS std
        INNER JOIN ".get_school_db().".student_parent AS std_p on std.parent_id = std_p.s_p_id 
        where std.student_id = ".$student_id." AND std.school_id = ".$school_id." ";
        $result1 = $CI->db->query($query1)->result_array();

        $parent_id =  $result1[0]['parent_id'];
        
        return $chat_id = $parent_id;
    }
    
    function chat_id($teacher_id= 0,$student_id=0,$is_parent=0){
        $CI=& get_instance();
        $CI->load->database();
        $school_id = $_SESSION['school_id'];
        
        $query1  = "SELECT std.user_login_detail_id AS std_id,std_p.user_login_detail_id AS parent_id FROM ".get_school_db().".student AS std
        INNER JOIN ".get_school_db().".student_parent AS std_p on std.parent_id = std_p.s_p_id 
        where std.student_id = ".$student_id." AND std.school_id = ".$school_id." ";
        $result1 = $CI->db->query($query1)->result_array();

        $parent_id =  $result1[0]['parent_id'];
        $std_id =  $result1[0]['std_id'];
        
        if($parent_id != 0 && $std_id!= 0 && $teacher_id != 0){
            return $chat_id = $teacher_id.$parent_id.$std_id;
        }
        else{
            return 0;
        }
        
        
    }

   
      


