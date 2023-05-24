<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');


function get_school_package_subscription_details(){
    $CI=& get_instance();
    $CI->load->database();
    
    $q2 = " select * from ".get_system_db().".package_subscription 
			where sys_school_id    = ".$_SESSION['sys_sch_id']." ";
		
    $query=$CI->db->query($q2);
    if($query->num_rows() > 0){
        return $query->row();
    }
    return NULL;
}


function get_user_assigned_rights($action_type = 0){

    $CI=& get_instance();
    $CI->load->database();
    
    if($action_type == 1 || $action_type == 2 ){
        $column = "ur.staff_id ";
        $user_id = $_SESSION['user_id'];
    }else if ($action_type == 3)
    {
        $column = "ur.student_id ";
        $user_id = $_SESSION['student_id'];
    }else{
        $column = "ur.parent_id ";
        $user_id = $_SESSION['user_id'];        
    }
    
    $q2 = " select GROUP_CONCAT(a.code) as ids from ".get_school_db().".user_rights ur
		    inner join ".get_school_db().".group_rights gr on gr.user_group_id = ur.user_group_id
		    inner join ".get_school_db().".user_group ug on ug.user_group_id   = gr.user_group_id
		    inner join ".get_system_db().".action a on a.action_id             = gr.action_id
			inner join ".get_system_db().".package_rights pr on pr.action_id   = a.action_id
			inner join ".get_system_db().".package p on p.package_id           = pr.package_id
			where
			$column           = ".$user_id."
			and p.package_id  = ".$_SESSION['package_id']."
			and p.status      = 1 
			and a.status      = 1
			and ug.status     = 1
			and a.action_type = $action_type
			and ug.school_id  = ".$_SESSION['school_id']." ";
		
    $query=$CI->db->query($q2);
    $row = $query->row();
    return $row->ids;
}


function exam_type_option_list($term_id=0,$selected=0)
{
    $statusStr='';
    $CI=& get_instance();
    $CI->load->database();
    if($term_id!=0){
        $termStr=' and y.yearly_terms_id ='.$term_id;
    }

    $q="select e.*,y.yearly_terms_id,y.title from ".get_school_db().".exam e inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id where e.school_id=".$_SESSION['school_id']." ".$termStr." and y.is_closed = 0";
    $query=$CI->db->query($q);
    $str= '<option value="">'.get_phrase('select_exam_type').'</option>';
    if($query->num_rows()>0){

        foreach($query->result() as $rows){
            $opt_selected='';
            if($rows->exam_id==$selected)
            {
                $opt_selected="selected";
            }

            $str.='<option value="'.$rows->exam_id.'" '.$opt_selected.'>'.$rows->name.' ( '.date('d-M-Y',strtotime($rows->start_date)).' to '.date('d-M-Y',strtotime($rows->end_date)).' ) '.'</option>';

        }


    }
    return $str;

}

function exam_type_option_list_marks($term_id=0,$selected=0)
{
    $statusStr='';
    $CI=& get_instance();
    $CI->load->database();
    if($term_id!=0){
        $termStr=' and y.yearly_terms_id ='.$term_id;
    }

    echo $q="select distinct e.*,y.yearly_terms_id,y.title 
	 	from ".get_school_db().".exam e 
	 	inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id 
	 	inner join ".get_school_db().".exam_routine er on er.exam_id=e.exam_id 
	 	where 
	 	e.school_id=".$_SESSION['school_id']." 
	 	".$termStr." 
	 	and y.status in (1,2) 
	 	and y.is_closed = 0";

    $query=$CI->db->query($q);
    $str= '<option value="">'.get_phrase('select_exam_type').'</option>';
    if($query->num_rows()>0)
    {
        foreach($query->result() as $rows){
            $opt_selected='';
            if($rows->exam_id==$selected)
            {
                $opt_selected="selected";
            }

            $str.='<option value="'.$rows->exam_id.'" '.$opt_selected.'>'.$rows->name.' ( '.date('d-M-Y',strtotime($rows->start_date)).' to '.date('d-M-Y',strtotime($rows->end_date)).' ) '.'</option>';

        }


    }
    return $str;

}

function get_total_marks($exam_id,$section_id,$subject_id)
{
    $CI=& get_instance();
    $CI->load->database();

    $total_marks = 0;
    $q1 = "select sum(percentage) as percentage 
		from ".get_school_db().".marks m
		inner join ".get_school_db().".exam e on e.exam_id=m.exam_id
		inner join ".get_school_db().".subject_components sc on sc.subject_id=m.subject_id
		 where 
		 sc.subject_id=".$subject_id." and 
		 e.exam_id=".$exam_id." and 
		 sc.school_id=".$_SESSION['school_id']."
		 group by marks_id
         limit 1 ";
    $query = $CI->db->query($q1);

    if ($query->num_rows() > 0)
    {
        $result = $query->row();
        foreach ($result as $value)
        {
            $total_marks = $total_marks + $value;
        }
    }
    else
    {
        $q="select total_marks from ".get_school_db().".exam_routine where exam_id=".$exam_id." and subject_id=".$subject_id." and section_id=".$section_id." and school_id=".$_SESSION['school_id']."";
        $resMarks=$CI->db->query($q)->result_array();
        $total_marks = $resMarks[0]['total_marks'];
    }

    return $total_marks;

}

function get_grade($marks='')
{
    $CI=& get_instance();
    $CI->load->database();
    if($marks!='')
    {
        $q="select grade_point from ".get_school_db().".grade where ".$marks.">=mark_from and ".$marks."<=mark_upto AND school_id=".$_SESSION['school_id']."";
        $resMarks=$CI->db->query($q)->result_array();
        return $resMarks[0]['grade_point'];
    }
    else return false;
}

function academic_hierarchy($term_id)
{
    $CI=& get_instance();
    $CI->load->database();
    $q="select y.title as term,a.title as year,a.academic_year_id,y.yearly_terms_id from ".get_school_db().".yearly_terms y inner join ".get_school_db().".acadmic_year a on y.academic_year_id=a.academic_year_id where y.yearly_terms_id=".$term_id." and y.school_id=".$_SESSION['school_id']."";
    $resMarks=$CI->db->query($q)->result_array();
    $arr['t']=$resMarks[0]['term'];
    $arr['a']=$resMarks[0]['year'];
    $arr['t_id']=$resMarks[0]['yearly_terms_id'];
    $arr['a_id']=$resMarks[0]['academic_year_id'];
    return $arr;
}

function get_total_obtained($exam_id,$marks_id,$subject_id)
{
    $CI=& get_instance();
    $CI->load->database();
    $q="select sum(mc.marks_obtained) as obtained from ".get_school_db().".marks_components mc inner join ".get_school_db().".marks m on m.marks_id=mc.marks_id where mc.marks_id=".$marks_id." and m.subject_id=".$subject_id." and m.exam_id=".$exam_id." and m.school_id=".$_SESSION['school_id']."";
    $resMarks=$CI->db->query($q)->result_array();
    return $resMarks[0]['obtained'];
}

function module_option_list($selected=0,$mod=0)
{
    $statusStr='';
    $CI=& get_instance();
    $CI->load->database();

    if($mod!=0)
    {
        $modCheck=" and module_id <> $mod";
    }
    $q="select title,module_id,parent_module_id from ".get_system_db().".module where status = 1 $modCheck";
    $query=$CI->db->query($q);
    $str= '<option value="">'.get_phrase('select_parent_module').'</option>';
    if($query->num_rows()>0){

        foreach($query->result() as $rows){
            $opt_selected='';
            $space='';
            if($rows->module_id==$selected)
            {
                $opt_selected="selected";
            }
            if($rows->parent_module_id >0){
                $space="&nbsp;&nbsp;&nbsp;&nbsp;";
                $str.='<option value="'.$rows->module_id.'" '.$opt_selected.'>'.$space.$rows->title.'</option>';
            }
            else{
                $str.='<option style="font-weight:bold" value="'.$rows->module_id.'" '.$opt_selected.'>'.$rows->title.'</option>';
            }
        }

    }
    return $str;

}
function module_name($module_id)
{
    $CI=& get_instance();
    $CI->load->database();
    $q="select title from ".get_system_db().".module where module_id =$module_id";
    $query=$CI->db->query($q)->result_array();

    return $query[0]['title'];

}
function check_action_code($code, $action_id = 0)
{
    $where  = '';
    if (intval($action_id)>0)
    {
        $where = " and action_id <> $action_id";
    }
    $q="select code from ".get_system_db().".action where code='".$code."' $where";

    $CI=& get_instance();
    $CI->load->database();
    $codeArr=$CI->db->query($q)->result_array();
    //return $codeArr[0];
    if(in_array($code,$codeArr[0]))
    {
        return "exists";
    }
    else{
        return '';
    }
}

function parent_module_option_list($selected=0){
    $q="select * from ".get_system_db().".module where status=1 and parent_module_id=0";
    $CI=& get_instance();
    $CI->load->database();
    $query=$CI->db->query($q);
    $str= '<option value="">'.get_phrase('select_parent_module').'</option>';
    if($query->num_rows()>0){

        foreach($query->result() as $rows){
            $opt_selected='';
            $opt='';
            if($rows->module_id==$selected)
            {
                $opt_selected="selected";
            }

            $str.='<option style="'.$style.'" value="'.$rows->module_id.'" '.$opt_selected.' '.$disabled.'>'.$rows->title.'</option>';

        }
    }
    return $str;

}
function module_actions($mod=0){
    $CI=& get_instance();
    $CI->load->database();
    $q2="select * from ".get_system_db().".action where module_id=".$mod."";
    $query=$CI->db->query($q2);
    $actions=$query->result_array();
    //$str='Actions <br/>';
    foreach($actions as $row){
        $str.='<input class="rights" type="checkbox" name="action" id="action_'.$row["action_id"].'" value="'.$row["action_id"].'">'.ucfirst($row["title"]).'</input>';
    }

    return $str;
}
function child_modules($mod)
{
    $CI=& get_instance();
    $CI->load->database();
    $q2="select * from ".get_system_db().".module where parent_module_id=".$mod."";
    $query=$CI->db->query($q2);
    $mods=$query->result_array();

    if(sizeof($mods)>0){
        $str='Modules <br/>';
        foreach($mods as $row){
            $str.='<input class="modules" type="checkbox" id="modules_'.$row["module_id"].'" name="module" value="'.$row["module_id"].'">'.ucfirst($row["title"]).'</input>';

            if(module_actions($row["module_id"]))
            {  $str.='Actions <br/>';
                $str.='<div class="'.$row["module_id"].'">'.module_actions($row["module_id"]).'</div>';
            }

        }
    }
    return $str;
}

function package_rights()
{
    $CI=& get_instance();
    $CI->load->database();
    $rights_result = array();
    $rights_arr = '';
    if(get_login_type_name($_SESSION['login_type']) == 'admin' || get_login_type_name($_SESSION['login_type']) == 'branch_admin')
    {
        $q2="select a.* from ".get_system_db().".package_rights pr 
            inner join ".get_system_db().".action a on a.action_id=pr.action_id 
            inner join ".get_system_db().".package p on p.package_id = pr.package_id
            where 
            p.package_id=".$_SESSION['package_id']."
            and p.status = 1
            and a.status = 1
            and a.action_type = 1
         ";
        $query=$CI->db->query($q2);
        $rights_result=$query->result_array();
    }
    elseif (get_login_type_name($_SESSION['login_type']) == 'staff')
    {
        /*
        $q2="select  g.*,a.* from ".get_school_db().".group_rights g inner join package_rights p on g.package_right_id=p.package_right_id inner join action a on a.action_id=p.action_id where g.user_group_id=".$_SESSION['user_group_id']."";
        */
        $q2 = "select a.* from ".get_school_db().".user_rights ur
                inner join ".get_school_db().".group_rights gr on gr.user_group_id = ur.user_group_id
                inner join ".get_school_db().".user_group ug on ug.user_group_id = gr.user_group_id
                inner join ".get_system_db().".action a on a.action_id = gr.action_id
                inner join ".get_system_db().".package_rights pr on pr.action_id = a.action_id
                inner join ".get_system_db().".package p on p.package_id = pr.package_id
                where
                ur.staff_id=".$_SESSION['user_id']."
                and p.package_id=".$_SESSION['package_id']."
                and p.status = 1 
                and a.status = 1
                and ug.status = 1
                and ug.school_id = ".$_SESSION['school_id']."
            ";

        $query=$CI->db->query($q2);
        $rights_result=$query->result_array();
    }

    $rights_arr = array();
    if(sizeof($rights_result)>0)
    {
        foreach($rights_result as $row)
        {
            // $rights_arr[$row['action_id']]=$row['code'];
            $rights_arr[] = $row['code'];
        }
    }
    
    return $rights_arr;
}

function right_granted($action_code = array())
{
    $rights_arr = package_rights();
    $proceed = "";
    //$rights_arr = $_SESSION['admin_rights'];
    
    if (gettype($action_code) == 'array')
    {
        foreach ($action_code as $key => $value)
        {
            if (in_array($value, $rights_arr)){
                $proceed = true;
            }
        }
    }
    else
    {
        if (in_array($action_code, $rights_arr)){
            $proceed = true;
        }
    }

    return $proceed;
}

function right_granted_to_teacher($action_code = array())
{
    $CI=& get_instance();
    $CI->load->database();
    $rights_result = array();
    $rights_arr = '';
        $q2="select a.* from ".get_system_db().".package_rights pr 
		 	inner join action a on a.action_id=pr.action_id 
		 	inner join package p on p.package_id = pr.package_id
		 	where 
		 	p.package_id=".$_SESSION['package_id']."
		 	and p.status = 1
		 	and a.status = 1
		 	and a.action_type = 2
		 ";
        $query=$CI->db->query($q2);
        $rights_result=$query->result_array();
        
    if(sizeof($rights_result)>0)
    {
        foreach($rights_result as $row)
        {
            $rights_arr[$row['action_id']]=$row['code'];
        }
    }
    // $rights_arr = package_rights();
    if (gettype($action_code) == 'array')
    {
        foreach ($action_code as $key => $value)
        {
            if (in_array($value, $rights_arr))
            {
                return true;
            }
        }
    }
    else
    {
        if (in_array($action_code, $rights_arr))
        {
            return true;
        }
    }

    return false;
}

function check_subject_exam($exam_id,$section_id,$selected=0){
    $CI=& get_instance();
    $CI->load->database();

    $q="select distinct e.subject_id,s.name from ".get_school_db().".exam_routine e inner join ".get_school_db().".subject s on s.subject_id=e.subject_id where e.exam_id=$exam_id AND e.section_id=$section_id AND e.school_id=".$_SESSION['school_id']."";
    $query=$CI->db->query($q);
    $str= '<option value="">Select Subject</option>';
    if($query->num_rows()>0){

        foreach($query->result() as $rows){
            $opt_selected='';
            if($rows->subject_id==$selected)
            {
                $opt_selected="selected";
            }

            $str.='<option value="'.$rows->subject_id.'" '.$opt_selected.'>'.$rows->name.'</option>';

        }
    }
    return $str;

}

function term_date_range($term_id,$start_date='',$end_date=''){
    
    print_r($term_id);

    $CI=& get_instance();
    $CI->load->database();
    $s_date='';
    $e_date='';
    if($start_date!='')
    {
        $s_date=' and "'.date('Y-m-d',strtotime($start_date)).'" between `start_date` and `end_date`';
    }
    elseif($end_date!='' && $start_date!='')
    {
        $s_date=' and ("'.date('Y-m-d',strtotime($end_date)).'" between `start_date` and `end_date`) and ("'.$end_date.'")>= `start_date`';
    }
    elseif($end_date!='')
    {
        $s_date=' and ("'.date('Y-m-d',strtotime($end_date)).'" between `start_date` and `end_date`) ';
    }
    $q="select start_date,end_date from ".get_school_db().".yearly_terms where school_id=".$_SESSION['school_id']."".$s_date."".$e_date."and yearly_terms_id=$term_id";
    $query=$CI->db->query($q);
    print_r($query);
    return sizeof($query->result());

}

function exam_date_range($exam_id,$exam_date=''){
    $CI=& get_instance();
    $CI->load->database();

    $str=' and "'.date('Y-m-d',strtotime($exam_date)).'" between `start_date` and `end_date`';
    $q="select start_date,end_date from ".get_school_db().".exam where school_id=".$_SESSION['school_id']." ".$str." and exam_id=$exam_id";
    $query=$CI->db->query($q);
    return sizeof($query->result());
}

function yearly_term_selector($select=0,$status_year=0,$status_term=0){

    $statusStr='';
    $CI=& get_instance();
    $CI->load->database();
    if(is_array($status_year) && (count($status_year) > 0)){
        $status_year=implode(",",$status_year);
        $statusStr=' and status NOT in ('.$status_year.') ';
    }
    elseif($status_year!=0){
        $statusStr=' and status <> '.$status_year;
    }

    $year=$CI->db->query("select academic_year_id,title as year1,start_date,end_date,status,is_closed from ".get_school_db().".acadmic_year where school_id=".$_SESSION['school_id']." ".$statusStr."  order by status")->result_array();

    $str='';
    if(sizeof($year)>0)
    {$str.='<option  value="">Select Yearly Term</option>';
        foreach($year as $y)
        {
            $closed="";
            if($y['is_closed']==1)
            {
                $closed="(Closed)";
            }
            $status=$y['status'];
            $status_val="";
            if($status==1)
            {
                $status_val="(Completed)";
            }
            elseif($status==2)
            {
                $status_val="(Current)";
            }
            elseif($status==3)
            {
                $status_val="(Upcoming)";
            }
            $str.='<optgroup label="'.$y['year1']." (".date('d-M-Y',strtotime($y['start_date'])). " to ".date('d-M-Y',strtotime($y['end_date'])).") ".$status_val." ".$closed. '">';

            $statusStr2='';

            if(is_array($status_term) && (count($status_term) > 0)){
                $status_term_q=implode(",",$status_term);
                $statusStr2=' and t.status NOT in ('.$status_term_q.') ';
            }
            elseif($status_term!=0){

                $statusStr2=' and t.status <> '.$status_term;
            }

            $query= "select t.start_date,t.end_date,t.title as term,t.yearly_terms_id,t.status,t.is_closed from  ".get_school_db().".yearly_terms t  where t.school_id=".$_SESSION['school_id']." ".$statusStr2." and t.academic_year_id=".$y['academic_year_id']."  order by t.status";

            $result=$CI->db->query($query)->result_array();
            foreach($result as $res)
            {
                $closed2="";
                if($res['is_closed']==1)
                {
                    $closed2="(Closed)";
                }
                $status2=$res['status'];
                $status_val2="";
                if($status2==1)
                {
                    $status_val2="(Completed)";
                }
                elseif($status2==2)
                {
                    $status_val2="(Current)";
                }
                elseif($status2==3)
                {
                    $status_val2="(Upcoming)";
                }

                $selected='';
                if($select==$res['yearly_terms_id'])
                {
                    $selected="selected='selected'";
                }
                $str.='<option '.$selected.' value="'.$res['yearly_terms_id'].'">'.$res['term'].'('.date('d-M-Y',strtotime($res['start_date'])).' to '.date('d-M-Y',strtotime($res['end_date'])).') '.$status_val2." ".$closed2.'</option>';
            }
            $str.= '</optgroup>';
        }

    }
    return $str;
}

function yearly_term_selector_exam($select=0,$status_year=0,$status_term=0){
    
    $statusStr='';
    $CI=& get_instance();
    $CI->load->database();
    if(is_array($status_year) && (count($status_year) > 0)){
        $status_year=implode(",",$status_year);
        $statusStr=' and status NOT in ('.$status_year.') ';
    }
    elseif($status_year!=0){
        $statusStr=' and status <> '.$status_year;
    }
    $year=$CI->db->query("select academic_year_id,title as year1,start_date,end_date,status,is_closed from ".get_school_db().".acadmic_year where school_id=".$_SESSION['school_id']." ".$statusStr."  order by status")->result_array();
    $str='';
    if(sizeof($year)>0)
    {$str.='<option  value="">Select Exam</option>';
        foreach($year as $y)
        {
            $status_val=$y['status'];
            $status_value="";
            if($status_val==1)
            {
                $status_value="Completed";
            }
            elseif($status_val==2)
            {
                $status_value="Current";
            }
            elseif($status_val==3)
            {
                $status_value="Upcoming";
            }
            $is_closed=$y['is_closed'];
            if($is_closed==1)
            {
                $closed=" - Closed";
            }
            $str.='<optgroup label="'.$y['year1']." (".date('d-M-Y',strtotime($y['start_date'])). " to ".date('d-M-Y',strtotime($y['end_date'])).") - ". $status_value.$closed. '">';
            $statusStr2='';
            if(is_array($status_term) && (count($status_term) > 0)){
                $status_term_q=implode(",",$status_term);
                $statusStr2=' and t.status NOT in ('.$status_term_q.') ';
            }
            elseif($status_term!=0){

                $statusStr2=' and t.status <> '.$status_term;
            }

            $result=$CI->db->query("select distinct e.name as exam_name,e.exam_id as exam_id, e.start_date as exam_start_date,
                                    e.end_date as exam_end_date, t.start_date,t.end_date,t.title as term,t.yearly_terms_id,t.status,
                                    t.is_closed from  ".get_school_db().".yearly_terms t INNER JOIN ".get_school_db().".exam e
	                                on t.yearly_terms_id=e.yearly_terms_id
	                                where t.school_id=".$_SESSION['school_id']." ".$statusStr2." and t.academic_year_id=".$y['academic_year_id']."   
	                                and t.is_closed = 0 order by t.status")->result_array();
            foreach($result as $res)
            {	$selected='';
                if($select==$res['exam_id'])
                {
                    $selected="selected='selected'";
                }
                $status_val2=$res['status'];
                $status_value2="";
                if($status_val2==1)
                {
                    $status_value2="Completed";
                }
                elseif($status_val2==2)
                {
                    $status_value2="Current";
                }
                elseif($status_val2==3)
                {
                    $status_value2="Upcoming";
                }
                $is_closed2=$res['is_closed'];
                if($is_closed2==1)
                {
                    $closed2=" - Closed";
                }

                $str.='<option '.$selected.' value="'.$res['exam_id'].'">'.$res['term'].' - '.$res['exam_name'].' ('.date('d-M-Y',strtotime($res['exam_start_date'])).' to '.date('d-M-Y',strtotime($res['exam_end_date'])).') - '.$status_value2.'</option>';
            }
            $str.= '</optgroup>';
        }

    }
    return $str;
}

function yearly_term_selector_exam_result($select=0,$status_year=0,$status_term=0,$terms_id=0){
    
    $statusStr='';
    $CI=& get_instance();
    $CI->load->database();
    if(is_array($status_year) && (count($status_year) > 0)){
        $status_year=implode(",",$status_year);
        $statusStr=' and status NOT in ('.$status_year.') ';
    }
    elseif($status_year!=0){
        $statusStr=' and status <> '.$status_year;
    }
    $year=$CI->db->query("select academic_year_id,title as year1,start_date,end_date,status,is_closed from ".get_school_db().".acadmic_year where school_id=".$_SESSION['school_id']." ".$statusStr."  order by status")->result_array();
    $str='';
    if(sizeof($year)>0)
    {$str.='<option  value="">Select Exam</option>';
        foreach($year as $y)
        {
            $status_val=$y['status'];
            $status_value="";
            if($status_val==1)
            {
                $status_value="Completed";
            }
            elseif($status_val==2)
            {
                $status_value="Current";
            }
            elseif($status_val==3)
            {
                $status_value="Upcoming";
            }
            $is_closed=$y['is_closed'];
            if($is_closed==1)
            {
                $closed=" - Closed";
            }
            $str.='<optgroup label="'.$y['year1']." (".date('d-M-Y',strtotime($y['start_date'])). " to ".date('d-M-Y',strtotime($y['end_date'])).") - ". $status_value.$closed. '">';
            $statusStr2='';
            if(is_array($status_term) && (count($status_term) > 0)){
                $status_term_q=implode(",",$status_term);
                $statusStr2=' and t.status NOT in ('.$status_term_q.') ';
            }
            elseif($status_term!=0){

                $statusStr2=' and t.status <> '.$status_term;
            }

            $result=$CI->db->query("select distinct e.name as exam_name,e.exam_id as exam_id, e.start_date as exam_start_date,
                                    e.end_date as exam_end_date, t.start_date,t.end_date,t.title as term,t.yearly_terms_id,t.status,
                                    t.is_closed from  ".get_school_db().".yearly_terms t 
                                    INNER JOIN ".get_school_db().".exam e
	                                on t.yearly_terms_id=e.yearly_terms_id
	                                where t.school_id=".$_SESSION['school_id']." ".$statusStr2." and t.academic_year_id=".$y['academic_year_id']."   
	                                and t.is_closed = 0 and e.yearly_terms_id = ".$terms_id." order by t.status")->result_array();
            foreach($result as $res)
            {	$selected='';
                if($select==$res['exam_id'])
                {
                    $selected="selected='selected'";
                }
                $status_val2=$res['status'];
                $status_value2="";
                if($status_val2==1)
                {
                    $status_value2="Completed";
                }
                elseif($status_val2==2)
                {
                    $status_value2="Current";
                }
                elseif($status_val2==3)
                {
                    $status_value2="Upcoming";
                }
                $is_closed2=$res['is_closed'];
                if($is_closed2==1)
                {
                    $closed2=" - Closed";
                }

                $str.='<option '.$selected.' value="'.$res['exam_id'].'">'.$res['term'].' - '.$res['exam_name'].' ('.date('d-M-Y',strtotime($res['exam_start_date'])).' to '.date('d-M-Y',strtotime($res['exam_end_date'])).') - '.$status_value2.'</option>';
            }
            $str.= '</optgroup>';
        }

    }
    return $str;
}

function subject_list($subject_id=0,$selected=0){
    $CI=& get_instance();
    $CI->load->database();
    $subj_categ=$CI->db->query("select subj_categ_id,title from ".get_school_db().".subject_category where school_id=".$_SESSION['school_id']." ")->result_array();

    $str='';
    if(sizeof($subj_categ)>0)
    {$str.='<option  value="">'.get_phrase('select_subject').'</option>';
        foreach($subj_categ as $y)
        {
            $str.='<optgroup label="'.$y['title'].'">';

            $result=$CI->db->query("select * FROM ".get_school_db().".subject WHERE school_id=".$_SESSION['school_id']." and subj_categ_id=".$y['subj_categ_id']." ")->result_array();

            foreach($result as $res)
            {	$selected='';
                if($subject_id==$res['subject_id'])
                {
                    $selected="selected='selected'";
                }
                $str.='<option '.$selected.' value="'.$res['subject_id'].'">'.$res['name'].' - '.$res['code'].'</option>';
            }
            $str.= '</optgroup>';
        }

    }
    return $str;
}

function year_exam_option_list($term=0,$selected=0)
{
    $CI=& get_instance();
    $CI->load->database();

    $str.='<option  value="">'.get_phrase('select_exam').'</option>';
    $result = $CI->db->query("select distinct e.name as exam_name,e.exam_id as exam_id, e.start_date as exam_start_date,e.end_date as exam_end_date, t.start_date,t.end_date,t.title as term,t.yearly_terms_id 
		from  ".get_school_db().".yearly_terms t 
		INNER JOIN ".get_school_db().".exam e on t.yearly_terms_id=e.yearly_terms_id
	 	where 
	 	t.school_id=".$_SESSION['school_id']." and 
	 	t.academic_year_id=".$_SESSION['academic_year_id']." and 
	 	t.status in (2,3) and 
	 	t.is_closed = 0
	 	order by e.exam_id
	 	")->result_array();

    $term_arr = array();
    foreach($result as $res)
    {
        $term_arr[$res['term']][] = array(
            'exam_id'  	 => $res['exam_id'],
            'exam_name'  => $res['exam_name'],
            'start_date' => date('d-M-Y',strtotime($res['exam_start_date'])),
            'end_date'   => date('d-M-Y',strtotime($res['exam_end_date'])),
        );

    }

    foreach ($term_arr as $outer_key => $outer)
    {
        $str.='<optgroup label="'.$outer_key.'">';

        foreach ($outer as $key => $value)
        {
            $str.='<option value="'.$value['exam_id'].'">'.$value['exam_name'].' ('.$value['start_date'].' to '.$value['end_date'].')'.'</option>';
        }
    }

    $str.= '</optgroup>';

    return $str;
}

function get_exam_term_name($exam_id = 0)
{
    $CI=& get_instance();
    $CI->load->database();

    $str.='<option  value="">'.get_phrase('select_exam').'</option>';
    $result = $CI->db->query("select distinct e.name as exam_name,e.exam_id as exam_id, e.start_date as exam_start_date,e.end_date as exam_end_date, t.start_date,t.end_date,t.title as term,t.yearly_terms_id 
		from  ".get_school_db().".yearly_terms t 
		INNER JOIN ".get_school_db().".exam e on t.yearly_terms_id=e.yearly_terms_id
	 	WHERE 
	 	t.school_id=".$_SESSION['school_id']." AND 
	 	t.academic_year_id=".$_SESSION['academic_year_id']." AND 
	 	t.status in (2,3) AND 
	 	t.is_closed = 0 AND
	 	e.exam_id = $exam_id
	 	order by e.exam_id
	 	")->result_array();
	return $result; 	
}
function section_selector($section_id=0){

    $CI=& get_instance();
    $CI->load->database();

    $dept=$CI->db->query("select departments_id,title as dept from ".get_school_db().".departments where school_id=".$_SESSION['school_id']."")->result_array();

    $str='';
    if(sizeof($dept)>0)
    {$str.='<option  value="">Select Department > Class > Section</option>';
        foreach($dept as $d)
        {

            $str.='<optgroup label="'.$d['dept'].'">';

            $section=$CI->db->query("select d.title as dept_name,c.name as class,c.class_id ,sec.title as section,sec.section_id from ".get_school_db().".class_section sec inner join ".get_school_db().".class c on c.class_id=sec.class_id inner join ".get_school_db().".departments d on c.departments_id=d.departments_id
				
				 where c.school_id=".$_SESSION['school_id']." and c.departments_id=".$d['departments_id']."")->result_array();

            foreach($section as $sec)
            {
                $selected='';
                if($section_id==$sec['section_id'])
                {
                    $selected="selected='selected'";
                }

                $str.='<option '.$selected.' value="'.$sec['section_id'].'">'.$sec['class'].' - '.$sec['section'].'</option>';
            }

            $str.= '</optgroup>';
        }

    }
    return $str;
}

function section_selector_landing_page($school_db = "" ,$school_id = ""){

    $CI=& get_instance();
    $CI->load->database();

    $dept=$CI->db->query("select departments_id,title as dept from ".$school_db.".departments where school_id=".$school_id."")->result_array();

    $str='';
    if(sizeof($dept)>0)
    {$str.='<option  value="">Select Class </option>';
        foreach($dept as $d)
        {

            $str.='';

            $section=$CI->db->query("select d.title as dept_name,c.name as class,c.class_id,c.strength ,sec.title as section,sec.section_id from ".$school_db.".class_section sec inner join ".$school_db.".class c on c.class_id=sec.class_id inner join ".$school_db.".departments d on c.departments_id=d.departments_id where c.school_id=".$school_id." and c.departments_id=".$d['departments_id']." ORDER BY c.class_id ASC")->result_array();

            foreach($section as $sec)
            {
                $current_class_std = count_class_student($sec['class_id'],$school_db,$school_id);
                if($current_class_std < $sec['strength'] || $sec['strength'] == "")
                {
                    $str.='<option value="'.$sec['section_id'].'">'.$sec['class'].' - '.$sec['section'].'</option>';
                }
            }

            $str.= '';
        }

    }
    return $str;
}

function count_class_student($class = "",$school_db = "",$school_id = "")
{
    $CI=& get_instance();
    $CI->load->database();
    $sections = get_class_sections($class,$school_db,$school_id);
    $count_std = $CI->db->query("SELECT COUNT(student_id) AS total_std FROM ".$school_db.".student WHERE section_id IN($sections) AND school_id = $school_id")->row();
    // echo $CI->db->last_query();
    return $count_std->total_std;
}

function get_class_sections($class = "",$school_db = "",$school_id = "")
{
    $CI=& get_instance();
    $CI->load->database();
    $sec_ids = $CI->db->query("SELECT GROUP_CONCAT(section_id) AS secs FROM ".$school_db.".class_section WHERE class_id = $class AND school_id = $school_id")->row();
    return $sec_ids->secs;
}

function subject_count_class_routine_week($subject_id,$section_id){
    $CI=& get_instance();
    $CI->load->database();

    $count=$CI->db->query("select count(cr.subject_id) as count from ".get_school_db().".class_routine_settings cs 
    inner join ".get_school_db().".class_routine cr on cs.c_rout_sett_id=cr.c_rout_sett_id 
    where cs.section_id=".$section_id." and cr.subject_id=".$subject_id." and cs.school_id=".$_SESSION['school_id']."")->result_array();
    

    return $count[0]['count'];

}
function subject_count_class_routine_day($subject_id,$section_id){
    $CI=& get_instance();
    $CI->load->database();

    $count=$CI->db->query("select count(cr.subject_id) as count from ".get_school_db().".class_routine_settings cs inner join ".get_school_db().".class_routine cr on cs.c_rout_sett_id=cr.c_rout_sett_id where cs.section_id=".$section_id." and cr.subject_id=".$subject_id." and cs.school_id=".$_SESSION['school_id']." group by cr.day limit 1")->result_array();

    return $count[0]['count'];

}
function subject_components($components=0)
{

    $CI=& get_instance();
    $CI->load->database();
    $res=array();
    if($components!='')
    {
        $res=array();
        $names=$CI->db->query("select title from ".get_school_db().".subject_components where subject_component_id in(".$components.")")->result_array();

        foreach($names as $name)
        {
            $res[]=$name['title'];
        }

    }
    return implode('<br/>',$res); 
}


function grade_indicators() {
    
    $CI=& get_instance();
    $CI->load->database();
    $output = "";
    $query = $CI->db->query('select grade_point,mark_from,mark_upto from '.get_school_db().'.grade WHERE school_id = '.$_SESSION["school_id"].' ' )->result_array();
    foreach($query as $row){
        $output .= $row['grade_point'] . ' = ' .$row['mark_from'] . ' - ' . $row['mark_upto'].' ';
    }
    
    return $output;
}