<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');


function get_evaluation_factors($selected=0){
    $CI=& get_instance();
    $CI->load->database();
    
    $q2 = " select * from ".get_school_db().".evaluation_factors ";
		
    $query=$CI->db->query($q2);
    $str= '<option value="">'.get_phrase('select_evaluation_factor').'</option>';   
    if($query->num_rows() > 0){
        foreach($query->result() as $rows){
            $opt_selected='';
            if($rows->id==$selected)
            {
                $opt_selected="selected";
            }
            $str.='<option value="'.$rows->id.'" '.$opt_selected.'>'.$rows->title.'</option>';

        }
    }
    return $str;
}

