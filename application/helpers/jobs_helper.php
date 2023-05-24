<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function get_job_type($type=0)
{
    $reg_ary=array(
        1 => 'Full Time',
        2 => 'Part Time'
    );
    
    return $reg_ary[$type];
}

function jobs_option_list($selected=0)
{
   $reg_ary=array(
        1 => 'Full Time',
        2 => 'Part Time'
    );
    $str='<option value="">Select Job Type</option>';
    foreach($reg_ary as $key => $value){
        $opt_selected="";
        if($selected == $key){
            $opt_selected="selected";
        }
        $str .= '<option value="'.$key.'" '.$opt_selected.'>'.$value.'</option>';
    }
    return $str;
}