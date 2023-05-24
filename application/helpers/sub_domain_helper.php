<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('sub_domain_details')){
    
	function sub_domain_details($sub_domain=""){
		$CI=& get_instance();
		$CI->load->database();
		$CI->load->dbutil();

		$query = $CI->db->query("select * from indicied_indiciedu_gsimscom_gsims_system.system_school where sub_domain ='$sub_domain' and status = 1")->result_array();

		if(count($query)>0)
		{
			if(empty($query[0]['sub_domain']) || empty($query[0]['landing_page']) )
			{
				$query ="";
			}
			else
			{
				$landing_page = 'application/views/backend/custom_login/'.$query[0]['landing_page'].".php";
				if(!is_file($landing_page))
				{
					$query ="";
				}
				else
				{
					if($CI->dbutil->database_exists($query[0]['school_db']))
					{
						$query = $query[0];
							$query2 = $CI->db->query("select * from indicied_indiciedu_gsimscom_gsims_system.school_gallery_images where school_id=".$query['sys_sch_id'])->result_array();
						
						$query['gallery_images']=$query2;
					}
					else
					{
						$query ="";
					}
				}
			}
		}
		else
		{
			$query ="";
		}
		return $query;
	}
}

