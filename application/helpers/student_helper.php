<?php 

function student_archive($updated_by,$student_id)
{
  /* echo $student_archive_str = "INSERT INTO ".get_school_db().".student_archive(student_id, name, birthday, gender,
                                        religion, address, phone, email, section_id, roll, student_status,
                                        parent_id, image,  form_num, adm_date, id_no, mob_num, emg_num, bd_group,
                                        disability, id_file, school_id, reg_num, system_id, location_id,
                                        barcode_image, p_address, system_date, updated_by,adm_term_id,academic_year_id,pro_academic_year_id,pro_section_id,bulk_req_id,is_readmission,is_transfered,is_installment,id_type)
                                        select student_id, name, birthday, sex, religion, address, phone,
                                        email, section_id, roll, student_status, parent_id, image,
                                        form_num, adm_date, id_no, mob_num, emg_num, bd_group, disability,
                                        id_file, school_id, reg_num, system_id, location_id, barcode_image,
                                        p_address, system_date, $updated_by,adm_term_id,
                                        academic_year_id,pro_academic_year_id,pro_section_id,bulk_req_id,is_readmission,is_transfered,is_installment,id_type from ".get_school_db().".student where student_id=$student_id";*/
	
    $CI=& get_instance();
    $CI->load->database();
    $CI->db->query("INSERT INTO ".get_school_db().".student_archive(
						student_id, name, birthday, gender, religion, 
						address, phone, email, section_id, roll, student_status,  
						parent_id, image,  form_num, adm_date, id_no, mob_num, emg_num, bd_group, 
						disability, id_file, school_id, reg_num, system_id, location_id, 
						barcode_image, p_address, system_date, updated_by, adm_term_id,
						academic_year_id, pro_academic_year_id, pro_section_id, bulk_req_id,
						is_readmission, is_transfered, is_installment, id_type, nationality,
						date_added, added_by, date_confirmed, confirmed_by,
						is_deleted, date_deleted, deleted_by,student_category_id)
						
						select 
						student_id, name, birthday, gender, religion, 
						address, phone, email, section_id, roll, student_status, 
						parent_id, image, form_num, adm_date, id_no, mob_num, emg_num, bd_group, 
						disability, id_file, school_id, reg_num, system_id, location_id, 
						barcode_image, p_address, system_date, $updated_by, adm_term_id,
						academic_year_id, pro_academic_year_id, pro_section_id, bulk_req_id,
						is_readmission, is_transfered, is_installment, id_type, nationality,
						date_added, added_by, date_confirmed, confirmed_by,
						is_deleted, date_deleted, deleted_by,student_category_id 
						from ".get_school_db().".student 
						where student_id=$student_id");
}

function is_chalan_exists($student_id,$form_type)
{
    $CI=& get_instance();
    $CI->load->database();
    $school_id=$_SESSION['school_id'];
    $qur_rub=$CI->db->query("SELECT * FROM ".get_school_db().".student_chalan_form 
								WHERE school_id=$school_id and student_id=$student_id 
								and form_type=$form_type and status<5 and is_processed=0 
								and is_cancelled =0")->result_array();

    if(count($qur_rub)>0){
        return 1;
    }else{
        return 0;
    }


}

function fee_type($name , $fee_id , $attributes) 
{
			
			$CI=& get_instance();
			$CI->load->database();
			$school_id= $_SESSION['school_id'];	
			$CI->db->where(array('school_id'=>$school_id , 'status'=>1));
			$CI->db->order_by("title", "asc"); 
			$query =  $CI->db->get(get_school_db().".fee_types");
			$row_result = array(""=>"Select");
			foreach ($query->result() as $row)
			{
				$row_result[$row->fee_type_id] = $row->title;
			}
			echo form_dropdown($name, $row_result , $fee_id , $attributes);
	}
	
function monthly_discount($name , $discount_id , $attributes) 
{
			
	$CI=& get_instance();
	$CI->load->database();
	$school_id= $_SESSION['school_id'];	
	$CI->db->where(array('school_id'=>$school_id, 'status'=>1));
	$CI->db->order_by("title", "asc"); 
	$query =  $CI->db->get(get_school_db().".discount_list");
	$row_result = array(""=>"Select");
	foreach ($query->result() as $row)
	{
		$row_result[$row->discount_id] = $row->title;
	}
	echo form_dropdown($name, $row_result , $discount_id , $attributes);
	
}


	
	

	
	
	
