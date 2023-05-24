<?php 
//session_start();
function school_config_archive($school_id,$action)
{
    $CI=& get_instance();
    $CI->load->database();
    $archive_by=$_SESSION['login_detail_id'];

    $CI->db->query("INSERT INTO ".get_school_db().".school_archive(
						school_id, name, address, phone, logo, 
						url, folder_name, email, contact_person, designation, slogan,  
						detail, country_id,  province_id, location_id, city_id, school_regist_no, sys_sch_id, archive_by,action
						)
						
						select 
						school_id, name, address, phone, logo, 
						url, folder_name, email, contact_person, designation, slogan,  
						detail, country_id,  province_id, location_id, city_id, school_regist_no, sys_sch_id, $archive_by,$action
						from ".get_school_db().".school 
						where school_id=$school_id");
}
