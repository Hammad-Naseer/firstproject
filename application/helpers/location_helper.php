<?php 
//session_start();
function school_location_archive($location_id,$action,$school_db,$user_login_detail_id)
{
    $CI=& get_instance();
    $CI->load->database();
    $archive_by = $user_login_detail_id;
    $CI->db->query("INSERT INTO ".$school_db.".city_location_archive( location_id, title, city_id, school_id, status, archive_by,action ) select location_id, title, city_id, school_id, status, $archive_by,$action from ".$school_db.".city_location where location_id=$location_id");

}
