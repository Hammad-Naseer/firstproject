<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();

function department_list($id='',$d_school_id)
{
    $school_id = ""; 
    if (!empty($d_school_id))
    {
        $school_id = $d_school_id;
    }
    else
    {
        $school_id = $_SESSION['school_id'];
    }

    $CI=& get_instance();
    $CI->load->database();

    $q = "select departments_id , title from ".get_school_db().".departments where school_id=".$school_id."";
    $dept=$CI->db->query($q)->result_array();

    //echo $CI->db->last_query();

    $str='';
    if(sizeof($dept)>0)
    {        $str.='<option value="">'.get_phrase('select_department').'</option>';

        foreach($dept as $d)
        {
            $selected='';
            if($id==('d'.$d['departments_id']))
            {
                $selected="selected='selected'";
            }
            $str.='<option '.$selected.' value="d'.$d['departments_id'].'">'.$d['title'].'</option>';
        }
    }
    return $str;
}

function class_list($id='',$d_school_id)
{
    $school_id = ""; 
    if (!empty($d_school_id))
    {
        $school_id = $d_school_id;
    }
    else
    {
        $school_id = $_SESSION['school_id'];
    }

    $CI=& get_instance();
    $CI->load->database();

    $dept=$CI->db->query("select departments_id,title from ".get_school_db().".departments where school_id=".$school_id."")->result_array();

    $str='';
    if(sizeof($dept)>0)
    {
        $str.='<option value="">'.get_phrase('select_class').'</option>';

        foreach($dept as $d)
        {
            $selected='';
            if($id==('d'.$d['departments_id']))
            {
                $selected="selected='selected'";
            }

            $str.='<optgroup label="'.$d['title'].'">';

            $class=$CI->db->query("select name,class_id from ".get_school_db().".class 
                 where school_id=".$school_id." and departments_id=".$d['departments_id']."")->result_array();

            foreach($class as $cls)
            {
                $selected='';
                if($id==('c'.$cls['class_id']))
                {
                    $selected="selected='selected'";
                }

                $str.='<option style="font-style: italic;" '.$selected.' value="c'.$cls['class_id'].'">&nbsp;&nbsp;'.$cls['name'].'</option>';
            }
            $str.= '</optgroup>';
        }
    }
    return $str;
}

function section_list($id='',$d_school_id)
{
    $school_id = ""; 
    if (!empty($d_school_id))
    {
        $school_id = $d_school_id;
    }
    else
    {
        $school_id = $_SESSION['school_id'];
    }

    $CI=& get_instance();
    $CI->load->database();

    $dept=$CI->db->query("select departments_id,title from ".get_school_db().".departments where school_id=".$school_id."")->result_array();

    $str='';
    if(sizeof($dept)>0)
    {
        $str.='<option  value="">'.get_phrase('select_section').'</option>';

        foreach($dept as $d)
        {
            $selected='';
            if($id==('d'.$d['departments_id']))
            {
                $selected="selected='selected'";
            }

            $str.='<optgroup label="'.$d['title'].'">';

            $class=$CI->db->query("select name,class_id from ".get_school_db().".class 
                 where school_id=".$school_id." and departments_id=".$d['departments_id']."")->result_array();

            foreach($class as $cls)
            {
                $selected='';
                if($id==('c'.$cls['class_id']))
                {
                    $selected="selected='selected'";
                }

                $str.='<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;'.$cls['name'].'">';
                

                $sec=$CI->db->query("select section_id,title from " .get_school_db().".class_section
                 where school_id=".$school_id." and class_id=".$cls['class_id']."")->result_array();

                foreach($sec as $s)
                {
                    $selected='';
                    if($id==('s'.$s['section_id']))
                    {
                        $selected="selected='selected'";
                    }

                    $str.='<option '.$selected.' value="s'.$s['section_id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$s['title'].'</option>';
                }
                $str.= '</optgroup>';
                $str.= '</optgroup>';
            }
        }
    }
    return $str;
}


function department_class_section($id=0,$d_school_id=""){
    $school_id = ""; 
    if (!empty($d_school_id))
    {
        $school_id = $d_school_id;
    }
    else
    {
        $school_id = $_SESSION['school_id'];
    }
    $CI=& get_instance();
    $CI->load->database();

    $dept=$CI->db->query("select departments_id,title from ".get_school_db().".departments where school_id=".$school_id." order by title")->result_array();

    $str="";
    $str_department = "";
    if(sizeof($dept)>0)
    {

        foreach($dept as $d)
        {
            $class=$CI->db->query("select name,class_id from ".get_school_db().".class 
                 where school_id=".$school_id." and departments_id=".$d['departments_id']." order by name")->result_array();
            
            if(sizeof($class)>0)
            {
                $selected='';
                if($id==('d'.$d['departments_id']))
                {
                    $selected="selected='selected'";
                }
                $str_department='<b><option style="font-weight: bold;" '.$selected.' value="d'.$d['departments_id'].'">'.$d['title'].'</option>';

                foreach($class as $cls)
                {
                    $sec=$CI->db->query("select section_id,title from " .get_school_db().".class_section
                     where school_id=".$school_id." and class_id=".$cls['class_id']." order by title")->result_array();
                    
                    if(sizeof($sec)>0)
                    {
                        $str.= $str_department;
                        $str_department = "";

                        $selected='';
                        if($id==('c'.$cls['class_id']))
                        {
                            $selected="selected='selected'";
                        }
                        
                        $str.='<option style="font-style: italic;" '.$selected.' value="c'.$cls['class_id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$cls['name'].'</option>';
                        
                        foreach($sec as $s)
                        {
                            $selected='';
                            if($id==('s'.$s['section_id']))
                            {
                                $selected="selected='selected'";
                            }
                            $str.='<option '.$selected.' value="s'.$s['section_id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$s['title'].'</option>';
                        }
                    }
                }
            }
        }
    } 
    return $str;
}

function remove_prefix($section_id)
{
    $id_arr = array();
    if (empty($section_id))
    {
        $id_arr['prefix'] = "";
        $id_arr['value'] =0;
    }
    else
    {
        $id_arr['prefix'] = substr($section_id, 0, 1);
        $length = strlen($section_id);
        $id_arr['value'] = substr($section_id, 1,$length);
    }
    return $id_arr;
}

function department_class_section_hierarchy($id,$d_school_id=0)
{
    if ($id == '') {
        $id = 0;
    }
    $school_id = "";
    if (!empty($d_school_id))
    {
        $school_id = $d_school_id;
    }
    else
    {
        $school_id = $_SESSION['school_id'];
    }
    $id_arr = remove_prefix($id);
    $prefix = $id_arr['prefix'];
    $value = $id_arr['value'];

    $filter ="";
    if ($prefix=='d') {
        $filter = "and d.departments_id = ".$value;
    }elseif ($prefix=='c') {
        $filter = "and c.class_id = ".$value;
    }elseif ($prefix=='s'){
         $filter = "and sec.section_id = ".$value;
    }
    $sec_ary=array();
    $CI=& get_instance();
    $CI->load->database();
    $query="select sec.class_id as class_id, c.name as class_name, sec.title as section_name, d.title as department_name,d.departments_id as departments_id,sec.section_id as section_id
    FROM ".get_school_db().".class_section sec
    INNER join ".get_school_db().".class c
    ON sec.class_id=c.class_id
    INNER join ".get_school_db().".departments d
    ON c.departments_id = d.departments_id
    WHERE sec.school_id = ".$school_id." $filter 
    order by department_name , class_name , section_name
    ";
    $classArr=$CI->db->query($query)->result_array();
    foreach ($classArr as $key => $value)
    {
    $sec_ary[$value['section_id']]['d']=$value['department_name'];
    $sec_ary[$value['section_id']]['c']=$value['class_name'];
    $sec_ary[$value['section_id']]['s']=$value['section_name'];
    $sec_ary[$value['section_id']]['d_id']=$value['departments_id'];
    $sec_ary[$value['section_id']]['c_id']=$value['class_id'];
    $sec_ary[$value['section_id']]['s_id']=$value['section_id'];
    }
    
    return $sec_ary;
}
function seconds_to_hours($seconds)
{
    $hours_minute = array();
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $seconds = $seconds % 60;
    if ($hours<10)
    {
        $hours = "0".$hours;
    }
    if ($minutes<10)
    {
        $minutes = "0".$minutes;
    }
    if ($seconds<10)
    {
        $seconds = "0".$seconds;
    }
    $hours_minute['h'] = $hours;
    $hours_minute['m'] = $minutes;
    $hours_minute['s'] = $seconds;

    return $hours_minute;
}