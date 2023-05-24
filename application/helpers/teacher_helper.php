<?php

/////////// Teacher Panel ///////////////
function get_teacher_section($login_detail_id = 0)
{
	$CI=& get_instance();
	$CI->load->database();

	$class_section = $CI->db->query("select distinct cs.section_id FROM ".get_school_db().".class_section cs
        inner join ".get_school_db().".staff staff on staff.staff_id = cs.teacher_id 
		where  staff.user_login_detail_id=$login_detail_id 
       and cs.school_id=".$_SESSION['school_id']."")->result_array();

	$section = array();
	if (count($class_section) > 0)
    {
    	foreach ($class_section as $key => $value) 
    	{
    		$section[] = $value['section_id']; 
    	}
    }
    return $section;
}

function get_teacher_sections()
{
    $CI=& get_instance();
	$CI->load->database();
	
	$ids = 0;
	if (count($section_ids)>0)
		$ids = implode(',', array_unique($section_ids));
	
	$dept_arr = $CI->db->query("select d.departments_id, d.title as department,c.name as class_name, sec.title as section_name
				from ".get_school_db().".class_section sec 
				inner join ".get_school_db().".class c on c.class_id=sec.class_id 
				inner join ".get_school_db().".departments d on d.departments_id = c.departments_id 
				where sec.school_id=".$_SESSION['school_id']." 
				and sec.section_id IN ($ids) 
				group by d.departments_id
				")->result_array();
	foreach($dept_arr as $d)
	{
		$section = $CI->db->query("SELECT c.name as class,c.class_id ,sec.title as section,sec.section_id 
			FROM ".get_school_db().".class_section sec 
			INNER JOIN ".get_school_db().".class c on c.class_id=sec.class_id 
			WHERE c.school_id=".$_SESSION['school_id']." 
			AND sec.section_id IN ($ids) 
			AND c.departments_id=".$d['departments_id']."
			")->result_array();
		
		return $section;
	}
	
	return $str;
}

function get_teacher_class($login_detail_id = 0)
{
	$CI=& get_instance();
	$CI->load->database();
	$class_arr = $CI->db->query("select distinct c.class_id FROM ".get_school_db().".class c
        inner join ".get_school_db().".staff staff on staff.staff_id = c.teacher_id
    		where 
            staff.user_login_detail_id=$login_detail_id 
    		and c.school_id=".$_SESSION['school_id']."
            ")->result_array();

	$class = array();
	if (count($class_arr) > 0)
    {
    	foreach ($class_arr as $key => $value) 
    	{
    		$class[] = $value['class_id']; 
    	}
    }
    return $class;
}

function get_teacher_department($login_detail_id = 0)
{
	$CI=& get_instance();
	$CI->load->database();
	$department_arr = $CI->db->query("select distinct departments_id FROM ".get_school_db().".departments d
        inner join ".get_school_db().".staff staff on staff.staff_id = d.department_head
			where 
            staff.user_login_detail_id=$login_detail_id 
			and d.school_id=".$_SESSION['school_id']."
            ")->result_array();

	$department = array();
	if (count($department_arr) > 0)
    {
    	foreach ($department_arr as $key => $value) 
    	{
    		$department[] = $value['departments_id']; 
    	}
    }
    return $department;
}

function get_time_table_teacher_section($login_detail_id = 0)
{
	$CI=& get_instance();
	$CI->load->database();
   
	$teacher_arr = $CI->db->query("select section_id FROM 
		".get_school_db().".class_routine cr 
            inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
            inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            inner join ".get_school_db().".staff staff on st.teacher_id=staff.staff_id
            where 
            staff.user_login_detail_id = $login_detail_id
            and cr.school_id=".$_SESSION['school_id']."
            group by section_id
            ")->result_array();
	        //echo $CI->db->last_query();
	$section = array();

    if ( count($teacher_arr) > 0 )
    {
    	foreach ($teacher_arr as $value) 
    	{
    		$section[] = $value['section_id']; 
    	}
    }  
    return $section;  
}

function get_time_table_teacher_subject($login_detail_id = 0)
{
	$CI=& get_instance();
	$CI->load->database();

    $teacher_arr = $CI->db->query("select s.subject_id FROM 
		".get_school_db().".class_routine cr 
            inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
            inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            inner join ".get_school_db().".subject s on st.subject_id=s.subject_id
            inner join ".get_school_db().".staff staff on st.teacher_id=staff.staff_id
            where staff.user_login_detail_id = $login_detail_id
            and cr.school_id=".$_SESSION['school_id']."
            /*group by section_id*/
            ")->result_array();
	//echo $CI->db->last_query();
	$subject = array();

    if ( count($teacher_arr) > 0 )
    {
    	foreach ($teacher_arr as $value) 
    	{
    		$subject[] = $value['subject_id']; 
    	}
    }  
    return $subject;  
}

function get_teacher_diary_subject($login_detail_id = 0, $section_id=0)
{
	$CI=& get_instance();
	$CI->load->database();
	
	$section_id = intval($section_id);
    $login_detail_id = intval($login_detail_id);

	$subject_ids = array();
    $subject_names = array();

    $subject_qry = "";
    $dept_arr = $CI->db->query("select distinct cs.section_id 
            from ".get_school_db().".class_section cs 
            inner join ".get_school_db().".class c on cs.class_id = c.class_id
            inner join ".get_school_db().".departments d on d.departments_id = c.departments_id
            inner join ".get_school_db().".staff staff on staff.staff_id = d.department_head
            where 
            d.school_id=".$_SESSION['school_id']."
            and staff.user_login_detail_id = $login_detail_id 
            and cs.section_id = $section_id
            ")->result_array();

    $sec_arr= array();
    $sec_in = 0;
    
    if (count($dept_arr) > 0) // if department head
    {
        foreach ($dept_arr as $_sec) 
        {
             $sec_arr[] = $_sec['section_id'];
        }  
    }
    else
    {
        $class_arr = $CI->db->query("select distinct cs.section_id 
            from ".get_school_db().".class_section cs 
            inner join ".get_school_db().".class c on cs.class_id = c.class_id
            inner join ".get_school_db().".staff staff on staff.staff_id = c.teacher_id
            where 
            c.school_id=".$_SESSION['school_id']."
            and staff.user_login_detail_id = $login_detail_id 
            and cs.section_id = $section_id
            ")->result_array();

        if (count($class_arr) > 0) // if class teacher
        {
            foreach ($class_arr as $_sec) 
            {
                 $sec_arr[] = $_sec['section_id'];
            } 
        }
        else
        {
            $section_arr = $CI->db->query("select distinct cs.section_id 
                from ".get_school_db().".class_section cs 
                inner join ".get_school_db().".staff staff on staff.staff_id = cs.teacher_id
                where cs.school_id=".$_SESSION['school_id']."
                and staff.user_login_detail_id = $login_detail_id 
                and cs.section_id = $section_id
                    ")->result_array();
            if (count($section_arr) > 0)// if section teacher
            {
                foreach ($section_arr as $_sec) 
                {
                     $sec_arr[] = $_sec['section_id'];
                } 
            }
            else
            {
                $subject_qry = $CI->db->query("select s.subject_id, concat(s.name,' - ',s.code) as name  
                    from ".get_school_db().".class_routine cs 
                    inner join ".get_school_db().".class_routine_settings crs on (cs.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                    inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cs.class_routine_id
                    inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                    inner join ".get_school_db().".subject s on st.subject_id=s.subject_id
                    inner join ".get_school_db().".staff staff on st.teacher_id=staff.staff_id
                    where 
                    staff.user_login_detail_id = $login_detail_id
                    and cs.school_id=".$_SESSION['school_id']."
                    and crs.section_id = $section_id
                    group by s.subject_id
                    ")->result_array();
                
                foreach ($subject_qry as $key => $value) 
                {
                	$subject_ids[] = $value['subject_id'];
                	$subject_names[] = $value['name'];
                }

                return array('ids' => $subject_ids, 'names' => $subject_names);
            }
        }
    }
    
    if (count($sec_arr) > 0)
    {
        $sec_in = implode(',', $sec_arr);
	    $subject_arr = $CI->db->query("select s.subject_id, concat(s.name,' - ',s.code) as name  
	            from ".get_school_db().".subject s
	            inner join ".get_school_db().".subject_section ss on ss.subject_id=s.subject_id
	            where ss.section_id in ($sec_in)
	            and ss.school_id=".$_SESSION['school_id']." 
	            ")->result_array(); 
	    foreach ($subject_arr as $key => $value) 
	    {
	    	$subject_ids[] = $value['subject_id'];
	    	$subject_names[] = $value['name'];
	    }
	}
    return array('ids' => $subject_ids, 'names' => $subject_names);
}

function get_teacher_timetable_diary_subject($login_detail_id = 0, $section_id=0)
{
    $CI=& get_instance();
    $CI->load->database();
    
    $section_id = intval($section_id);
    $login_detail_id = intval($login_detail_id);

    $subject_ids = array();
    $subject_names = array();

    $subject_qry = $CI->db->query("select s.subject_id, concat(s.name,' - ',s.code) as name  
        from ".get_school_db().".class_routine cs 
        inner join ".get_school_db().".class_routine_settings crs on (cs.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
        inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cs.class_routine_id
        inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
        inner join ".get_school_db().".subject s on st.subject_id=s.subject_id
        inner join ".get_school_db().".staff staff on st.teacher_id=staff.staff_id
        where staff.user_login_detail_id = $login_detail_id
        and cs.school_id=".$_SESSION['school_id']."
        and crs.section_id = $section_id
        group by s.subject_id
        ")->result_array();
    
    foreach ($subject_qry as $key => $value) 
    {
        $subject_ids[] = $value['subject_id'];
        $subject_names[] = $value['name'];
    }

    return array('ids' => $subject_ids, 'names' => $subject_names);
}

function get_teacher_dep_class_section($login_detail_id=0)
{
	$CI=& get_instance();
	$CI->load->database();
	
	$section_ids = array();
	$dept_arr = $CI->db->query("select distinct cs.section_id 
				from ".get_school_db().".class_section cs 
				inner join ".get_school_db().".class c on cs.class_id = c.class_id
                inner join ".get_school_db().".departments d on d.departments_id = c.departments_id
				inner join ".get_school_db().".staff staff on staff.staff_id = d.department_head
				where d.school_id=".$_SESSION['school_id']."
				and staff.user_login_detail_id = $login_detail_id 
				")->result_array();
	foreach ($dept_arr as $value) 
	{
		$section_ids[] =  $value['section_id'];
	}
	$class_arr = $CI->db->query("select distinct cs.section_id 
				from ".get_school_db().".class_section cs 
                inner join ".get_school_db().".class c on cs.class_id = c.class_id
				inner join ".get_school_db().".staff staff on staff.staff_id = c.teacher_id
				where c.school_id=".$_SESSION['school_id']."
				and staff.user_login_detail_id = $login_detail_id 
				")->result_array();
	foreach ($class_arr as $value) 
	{
		$section_ids[] =  $value['section_id'];
	}

	$section_arr = $CI->db->query("select distinct cs.section_id 
				from ".get_school_db().".class_section cs 
                inner join ".get_school_db().".staff staff on staff.staff_id = cs.teacher_id
				where cs.school_id=".$_SESSION['school_id']."
				and staff.user_login_detail_id = $login_detail_id 
				")->result_array();
	foreach ($section_arr as $value) 
	{
		$section_ids[] =  $value['section_id'];
	}

	return array_unique($section_ids);
}


function get_teacher_dep_class_section_list($section_ids=array(), $selected_section=0)
{
	$CI=& get_instance();
	$CI->load->database();
	
	$ids = 0;
	if (count($section_ids)>0)
		$ids = implode(',', array_unique($section_ids));
	
	$dept_arr = $CI->db->query("select d.departments_id, d.title as department,c.name as class_name, sec.title as section_name
				from ".get_school_db().".class_section sec 
				inner join ".get_school_db().".class c on c.class_id=sec.class_id 
				inner join ".get_school_db().".departments d on d.departments_id = c.departments_id 
				where sec.school_id=".$_SESSION['school_id']." 
				and sec.section_id IN ($ids) 
				group by d.departments_id
				")->result_array();

	//return $CI->db->last_query();
	$str = '<option value="">'.get_phrase('select_section').'</option>';
	foreach($dept_arr as $d)
	{
		$str.='<optgroup label="'.$d['department'].'">';
		$section = $CI->db->query("SELECT c.name as class,c.class_id ,sec.title as section,sec.section_id 
			FROM ".get_school_db().".class_section sec 
			INNER JOIN ".get_school_db().".class c on c.class_id=sec.class_id 
			WHERE c.school_id=".$_SESSION['school_id']." 
			AND sec.section_id IN ($ids) 
			AND c.departments_id=".$d['departments_id']."
			")->result_array();
		
		foreach($section as $sec)
		{	
			$selected='';
			if($selected_section == $sec['section_id'])
			{
				$selected="selected='selected'";
			}
			$str.='<option '.$selected.' value="'.$sec['section_id'].'">'.$sec['class'].' - '.$sec['section'].'</option>';					  
		}
		$str.= '</optgroup>';
	}
	
	return $str;
}


function get_teacher_dep_class_section_list_name_pdf($section_ids=array(), $selected_section=0)
{
	$CI=& get_instance();
	$CI->load->database();
	
	$ids = 0;
	if (count($section_ids)>0)
		$ids = implode(',', array_unique($section_ids));
	
	$dept_arr = $CI->db->query("select d.departments_id, d.title as department,c.name as class_name, sec.title as section_name
				from ".get_school_db().".class_section sec 
				inner join ".get_school_db().".class c on c.class_id=sec.class_id 
				inner join ".get_school_db().".departments d on d.departments_id = c.departments_id 
				where sec.school_id=".$_SESSION['school_id']." 
				and sec.section_id IN ($ids) 
				group by d.departments_id
				")->result_array();

	//return $CI->db->last_query();
	$str = '';
	foreach($dept_arr as $d)
	{
		$str.='<optgroup label="'.$d['department'].'">';
		$section = $CI->db->query("SELECT c.name as class,c.class_id ,sec.title as section,sec.section_id 
			FROM ".get_school_db().".class_section sec 
			INNER JOIN ".get_school_db().".class c on c.class_id=sec.class_id 
			WHERE c.school_id=".$_SESSION['school_id']." 
			AND sec.section_id IN ($ids) 
			AND c.departments_id=".$d['departments_id']."
			")->result_array();
		
		foreach($section as $sec)
		{	
			$selected='';
			if($selected_section == $sec['section_id'])
			{
				$selected="selected='selected'";
			}
			$str.='<option '.$selected.' value="'.$sec['section_id'].'">'.$sec['class'].' - '.$sec['section'].'</option>';					  
		}
		$str.= '</optgroup>';
	}
	
	return $str;
}




function get_subject_teacher_option_list($user_login_detail_id = 0, $selected=0)
{
	$CI=& get_instance();
	$CI->load->database();

	    $subject_arr = $CI->db->query("SELECT s.* FROM 
		".get_school_db().".class_routine cr 
            inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
            inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
            inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
            inner join ".get_school_db().".subject s on s.subject_id=st.subject_id
            inner join ".get_school_db().".staff staff on staff.staff_id=st.teacher_id
            where 
            staff.user_login_detail_id = $user_login_detail_id
            and cr.school_id=".$_SESSION['school_id']."
            group by section_id
            ")->result_array();
	$str='<option value="">'.get_phrase('select_subject').'</option>';
	foreach($subject_arr as $row)
	{
		$opt_selected="";
		if($selected == $row['subject_id'])
			$opt_selected="selected";
		$str .= '<option value="'.$row['subject_id'].'" '.$opt_selected.'>'.$row['name'].'</option>';
	}	
	return $str; 
}


function get_teacher_section_subjects($sections=array())
{
	$CI=& get_instance();
	$CI->load->database();

	$sec_in = 0;
    if (count($sections) > 0)
    {
        $sec_in = implode(',', array_unique($sections));
    }
	$sub_res = $CI->db->query("select s.subject_id
            from ".get_school_db().".subject s
            inner join ".get_school_db().".subject_section ss on s.subject_id = ss.subject_id
            where 
            s.school_id=".$_SESSION['school_id']." 
            and ss.section_id in ($sec_in)
            ")->result_array();

	$sub_arr = array();
	foreach ($sub_res as $value) 
	{
		$sub_arr[] = $value['subject_id']; 
	}
    return array_unique($sub_arr);
}

function teacher_subject_option_list($user_login_detail_id=0,$section_id=0,$selected=0)
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
            staff.user_login_detail_id = $user_login_detail_id
            and cr.school_id=".$_SESSION['school_id']."
            and crs.section_id = $section_id
            group by s.subject_id
            ";


   // echo $query;
	$subject_arr = $CI->db->query($query)->result_array();
    
	$str='<option value="">'.get_phrase('select_subject').'</option>';
	foreach($subject_arr as $row)
	{
		$opt_selected="";
		if($selected == $row['subject_id'])
			$opt_selected="selected";
		$str .= '<option value="'.$row['subject_id'].'" '.$opt_selected.'>'.$row['name'].' - '.$row['code'].'</option>';
	}	
	return $str; 
}

function get_teacher_id($login_detail_id = 0 )
{
    $login_detail_id = $this->db->get_where(
            get_school_db().".staff", 
            array('user_login_detail_id' => $login_detail_id)
        )->result_array();

    return intval($login_detail_id[0]['staff_id']); 
}