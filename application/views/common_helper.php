<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//session_start();


if (!function_exists('get_email')) {

    function get_email($email = "", $action = "", $user_login_id = 0)
    {
        $CI =& get_instance();
        $CI->load->database();
        if ($action == 'update') {
            $query = $CI->db->query("select email from " . get_system_db() . ".user_login where email='$email' and user_login_id <> $user_login_id ");
        } else {
            $query = $CI->db->query("select email from " . get_system_db() . ".user_login where email='$email'");
        }

        /*$query=$CI->db->query("select email from ".get_school_db().".admin where email='$email'
    union

    select email from ".get_school_db().".principal where email='$email' union

            select email from ".get_school_db().".student where email='$email' union

            select email from ".get_school_db().".teacher where email='$email' union

            select email from ".get_school_db().".library where email='$email' union

            select email from ".get_school_db().".transport_driver where email='$email'
            union

            select email from ".get_school_db().".student_parent where email='$email'
            ");*/

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

function get_subject_section($subject_id)
{
    $CI =& get_instance();
    $CI->load->database();

   echo $query_str = "select cs.section_id,d.title as department_name,c.name
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
        "1" => get_phrase('january'),
        "2" => get_phrase('february'),
        "3" => get_phrase('march'),
        "4" => get_phrase('april'),
        "5" => get_phrase('may'),
        "6" => get_phrase('june'),
        "7" => get_phrase('july'),
        "8" => get_phrase('august'),
        "9" => get_phrase('september'),
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
function system_path($file_name, $folder_name, $is_root = 0)
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
        return date("d-M-Y", mktime(0, 0, 0, $d[1], $d[2], $d[0]));
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

function designation_list_h($parent_id = 0, $selected = 0, $spaces = 0, $d_school_id)
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

    $coa__rs = $CI->db->query("select coa.* from " . get_school_db() . ".chart_of_accounts as coa Inner join " . get_school_db() . ".school_coa as s_coa ON s_coa.coa_id = coa.coa_id " . get_school_db() . ".school_coa as s_coa ON s_coa.coa_id = coa.coa_id where coa.coa_id=$id AND s_coa.school_id = " . $_SESSION['school_id'] . "")->result_array();
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
    if ($query->num_rows > 0) {

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
    if ($query->num_rows > 0) {
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
    if ($query->num_rows > 0) {

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
    if ($query->num_rows > 0) {
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

    $q = "select * from " . get_school_db() . ".subject s
	INNER join " . get_school_db() . ".subject_section ss on s.subject_id=ss.subject_id
	where ss.section_id='$section_id' and s.school_id=" . $_SESSION['school_id'] . " ";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_subject') . '</option>';
    if ($query->num_rows > 0) {
        foreach ($query->result() as $rows) {
        	//echo $rows->subject_id;
            $opt_selected = '';
            if ($rows->subject_id == $selected) {
                $opt_selected = "selected";
            }

            $str .= '<option value="' . $rows->subject_id . '" ' . $opt_selected . '>' . $rows->name . " - " . $rows->code . '</option>';
        }
    }
    return $str;
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
    if ($query->num_rows > 0) {
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
    if ($query->num_rows > 0) {

        foreach ($query->result() as $rows) {
            $opt_selected = '';
            if ($rows->academic_year_id == $selected) {
                $opt_selected = "selected";
            }
            $status_val = $rows->status;
            $status_value = "";
            if ($status_val == 1) {
                $status_value = "Completed";
            } elseif ($status_val == 2) {
                $status_value = "Current";
            } elseif ($status_val == 3) {
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
    if ($query->num_rows > 0) {

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

function staff_list($staff_id = 0, $selected = 0, $staff_type = 0, $designation_id = 0, $d_school_id)
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
    echo $q = "SELECT s.name as name,d.title as designation, d.is_teacher,s.staff_id as staff_id FROM " . get_school_db() . ".staff s LEFT JOIN " . get_school_db() . ".designation d ON s.designation_id=d.designation_id
			 WHERE s.school_id=" . $school_id . " " . $designation_type . " " . $is_teacher . " ";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_staff') . '</option>';
    if ($query->num_rows > 0) {
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

function get_subject_name($subject_id, $val = 0)
{

    $q = "select name,code from " . get_school_db() . ".subject where subject_id=" . $subject_id . "";
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
        ";
    $query = $CI->db->query($q);
    $str = '<option value="">' . get_phrase('select_student') . '</option>';
    if ($query->num_rows > 0) {
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
    if ($query->num_rows > 0) {
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
    $q="select t.teacher_id,t.name,s.name as subject from ".get_school_db().".teacher t inner join ".get_school_db().".subject_teacher sc on sc.teacher_id=t.teacher_id inner join ".get_school_db().".subject s on sc.subject_id=s.subject_id where t.school_id=".$_SESSION['school_id']." and t.teacher_id=".$teacher_id."";
    */
    $q = "SELECT s.name,s.staff_id as teacher_id, sub.name as subject, st.subject_teacher_id, sub.code 
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


function get_teacher_name($teacher_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();
    /*
    $q="SELECT name FROM ".get_school_db().".teacher
    WHERE teacher_id=$teacher_id AND school_id=".$_SESSION['school_id']."";
    */

    $q = "SELECT s.name FROM " . get_school_db() . ".staff s
		INNER JOIN " . get_school_db() . ".designation d 
		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
		WHERE s.staff_id=$teacher_id AND s.school_id=" . $_SESSION['school_id'] . " ";
    $teacher = $CI->db->query($q)->result_array();
    $teacher_name = $teacher[0]['name'];
    return $teacher_name;
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

    $admin_req = $CI->db->query("select * from " . get_system_db() . ".user_login_details uld inner join user_login ul on ul.user_login_id=uld.user_login_id where user_login_detail_id=$id")->result_array();

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
        2 => get_phrase('chalan_form_approval_requested'),
        3 => get_phrase('chalan_form_approved'),
        4 => get_phrase('chalan_form_issued')

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
        1 => get_phrase('chalan_generated'),
        2 => get_phrase('chalan_form_approval_requested'),
        3 => get_phrase('chalan_form_approved'),
        4 => get_phrase('chalan_form_issued'),
        5 => get_phrase('chalan_form_received'),
        6 => get_phrase('chalan_form_cancelled')
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
    return $_SESSION['system_db'];
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
        5 => 'admin'
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

    $str = '<option value="">' . get_phrase('select_group') . '</option>';

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
function student_name_section($student_id)
{

    $CI =& get_instance();
    /*$transaction_str ="SELECT * FROM ".get_school_db().".student
    WHERE school_id=".$_SESSION['school_id']."
    AND student_id = ".$student_id."";*/


    $quer = "SELECT DISTINCT s.*, cs.title as section_name, cc.name as class_name, d.title as department_name
                    from " . get_school_db() . ".student s 
                    inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
                    inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id 
                    inner join " . get_school_db() . ".departments d on d.departments_id=cc.departments_id
                    where s.school_id=" . $_SESSION['school_id'] . "
                    AND s.student_id = " . $student_id . "";

    $student_row = $CI->db->query($quer)->row();
    $str = $student_row->name . " - " . $student_row->roll . " - " . $student_row->department_name . " - " . $student_row->class_name . " - " . $student_row->section_name . "";
    //  echo $student_row->chalan_form_number;
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
    if ($status_id == 1) {
        $status_val = "New";
    } elseif ($status_id == 2) {
        $status_val = "Open";
    } elseif ($status_id == 3) {
        $status_val = "Closed";
    }
    return $status_val;
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

function designation_details($designation_id = 0, $d_school_id)
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

function id_type_list($sel_name = "", $sel_class = "", $selected = 0)
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

function get_percentage_range($selected = 0, $disable_input)
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
    if ($query->num_rows > 0) {

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

function fee_student_installment($selected_id = 0)
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
    // echo "selected_id".$selected_id;
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

    $scl_name = $CI->db->query("select * from " . get_school_db() . ".school where school_id=$d_school_id")->result_array();
    $school_details = array();
    if (count($scl_name) > 0) {
        $school_details['name'] = $scl_name[0]['name'];
        $school_details['logo'] = $scl_name[0]['logo'];
        $school_details['folder_name'] = $scl_name[0]['folder_name'];
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
