<?php



    function school_option_list($selected=0)
    {

    $CI=& get_instance();
    $CI->load->database();


    $option=' <option value="">'.get_phrase('select_school').'</option>';
    $classes=$CI->db->query("select sc.name as school_name, sc.school_id from 
    ".get_system_db().".system_school ss 
    inner join ".get_school_db().".school sc on sc.sys_sch_id=ss.sys_sch_id 
    where ss.parent_sys_sch_id=".$_SESSION['parent_sys_sch_id'])->result_array();

    foreach($classes as $row):
        if($selected==$row['school_id'])
        {
            $select="selected";
        }else
        {
            $select="";
        }

    $option.="<option $select value=".$row['school_id'].">".$row['school_name']."</option>";
    endforeach;
    return $option;
    }
    ?>