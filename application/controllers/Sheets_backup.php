<?php

if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sheets extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->dbutil();
	}
	
	
	function import_list()
	{
		$page_data['page_name']  = 'import_list';
        $page_data['page_title'] = get_phrase('import_list');
        $this->load->view('backend/index', $page_data);
	}
	
    
    function save_departments()
    {
        $this->db->trans_begin();
        
        $school_id         = $_SESSION['school_id'];
 
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $sheetname = $worksheet->getTitle();
                if ($sheetname == "Department") 
                {
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $title         =  $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $short_name    =  $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $discription   =  $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        
                        if($title !== null && $title !== '' && !empty($title))
                        {
                            
                            $data['title']          =  $title;
                            $data['short_name']     =  $short_name;
                            $data['discription']    =  $discription;
                            $data['order_num']      =  0;
                            $data['school_id']      =  $_SESSION['school_id'];
                            
                            $this->db->insert(get_school_db().'.departments',$data);
                       
                        }
                        
                    }
                }
            }
            
            
            $update_data = array(
                 'department' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    function save_class()
    {
        $this->db->trans_begin();
        
        $school_id         = $_SESSION['school_id'];

        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $sheetname = $worksheet->getTitle();
                if ($sheetname == "Classes") 
                {
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $dept_title      =   $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $class_title     =   $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $short_name      =   $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $discription     =   $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        

                        $q1              =  "SELECT departments_id FROM ". get_school_db() .".departments WHERE school_id = $school_id and title='$dept_title'";
                        $row1            =  $this->db->query($q1)->row();
                        $dept_id         =  $row1->departments_id;

                        if($dept_id == ""){
                            $dept_id = 0;
                        }
                        
                        
                        if($class_title !== null && $class_title !== '' && !empty($class_title))
                        {
                            
                            $data['name']                =  $class_title;
                            $data['name_numeric']        =  $short_name;
                            $data['description']         =  $discription;
                            $data['school_id']           =  $_SESSION['school_id'];
                            $data['departments_id']      =  $dept_id;
                            
                            $this->db->insert(get_school_db().'.class',$data);
                       
                        }
                        
                    }
                }
            }
            
            
            $update_data = array(
                 'class' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    function save_class_section()
    {
        $this->db->trans_begin();
        
        $school_id         = $_SESSION['school_id'];
        
        
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow    =  $worksheet->getHighestRow();
                $sheetname     =  $worksheet->getTitle();
                if ($sheetname == "Class Section") 
                {
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $dept_title      =   $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $class_title     =   $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $section_title   =   $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $short_name      =   $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $detail          =   $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        
                        $q2              =  "SELECT class_id FROM ". get_school_db() .".class WHERE school_id = $school_id and name='$class_title'";
                        $row2            =  $this->db->query($q2)->row();
                        $class_id        =  $row2->class_id;
                        
                        if($class_id == ""){
                            $class_id = 0;
                        }
                        
                        
                        if($class_title !== null && $class_title !== '' && !empty($class_title))
                        {
                            
                            $data['title']            =  $section_title;
                            $data['short_name']       =  $short_name;
                            $data['status']           =  1;
                            $data['school_id']        =  $_SESSION['school_id'];
                            $data['class_id']         =  $class_id;
                            $data['discription']      =  $discription;
                            
                            $this->db->insert(get_school_db().'.class_section',$data);
                       
                        }
                        
                    }
                }
            }
            
            $update_data = array(
                 'class_section' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);            
            
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    function save_acadamic_year()
    {
        
        $this->db->trans_begin();
        
        $school_id         = $_SESSION['school_id'];
        
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 
            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $sheetname  = $worksheet->getTitle();
                
                if ($sheetname == "Academic Year") 
                {
                    
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $title         =  $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $start_date    =  date('Y-m-d' , strtotime($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
                        $end_date      =  date('Y-m-d' , strtotime($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
                        $detail        =  $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $status        =  $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        
                        if($title !== null && $title !== '' && !empty($title))
                        {
                            
                            $data['title']          =  $title;
                            $data['start_date']     =  $start_date;
                            $data['end_date']       =  $end_date;
                            $data['detail']         =  $detail;
                            $data['school_id']      =  $_SESSION['school_id'];
                            $data['status']         =  $status;
                            $data['is_closed']      =  0;
                            
                            $this->db->insert(get_school_db().'.acadmic_year',$data);
                       
                        }
                        
                    }
                }
            }
            
            $update_data = array(
                 'acad_year' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);    
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    
    
    function save_acadamic_term()
    {
        
        $this->db->trans_begin();

        $school_id         = $_SESSION['school_id'];
        
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $sheetname  = $worksheet->getTitle();
                if ($sheetname == "Academic Term") 
                {
                    
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $acd_year_title   =  $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $title            =  $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $start_date       =  date('Y-m-d' , strtotime($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
                        $end_date         =  date('Y-m-d' , strtotime($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
                        $detail           =  $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $status           =  $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        
                        $q2               =  "SELECT academic_year_id FROM ". get_school_db() .".acadmic_year WHERE school_id = $school_id and title='$acd_year_title'";
                        $row2             =  $this->db->query($q2)->row();
                        $academic_year_id =  $row2->academic_year_id;
                        
                        if($academic_year_id == ""){
                            $academic_year_id = 0;
                        }
                        
 
                        if($title !== null && $title !== '' && !empty($title))
                        {
                            
                            
                            $data['title']              =  $title;
                            $data['start_date']         =  $start_date;
                            $data['end_date']           =  $end_date;
                            $data['detail']             =  $detail;
                            $data['school_id']          =  $_SESSION['school_id'];
                            $data['academic_year_id']   =  $academic_year_id;
                            $data['status']             =  $status;
                            $data['is_closed']          =  0;
                            
                            $this->db->insert(get_school_db().'.yearly_terms',$data);
                       
                        }
                        
                    }
                }
            }
            
            $update_data = array(
                 'acad_terms' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    function save_student_category()
    {
        $this->db->trans_begin();
        
        $school_id         = $_SESSION['school_id'];
        
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow    =  $worksheet->getHighestRow();
                $sheetname     =  $worksheet->getTitle();
                if ($sheetname == "Student Category") 
                {
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $title  =  $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                        if($title !== null && $title !== '' && !empty($title))
                        {
                            
                            $data['title']              =  $title;
                            $data['school_id']          =  $_SESSION['school_id'];
                            
                            $this->db->insert(get_school_db().'.student_category',$data);
                       
                        }
                        
                    }
                }
            }
            
            
            $update_data = array(
                 'student_category' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    function save_subject_category()
    {
        $this->db->trans_begin();
        
        $school_id         = $_SESSION['school_id'];
        
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $sheetname = $worksheet->getTitle();
                if ($sheetname == "Subject Category") 
                {
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $title  =  $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                        if($title !== null && $title !== '' && !empty($title))
                        {
                            
                            $data['title']          =  $title;
                            $data['school_id']      =  $_SESSION['school_id'];
                            
                            $this->db->insert(get_school_db().'.subject_category',$data); 
                       
                        }
                        
                    }
                }
            }
            
            
            $update_data = array(
                 'subject_category' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);
            
            
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    function save_subject()
    {
        $this->db->trans_begin();
        
        $school_id         = $_SESSION['school_id'];
        
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $sheetname  = $worksheet->getTitle();
                if ($sheetname == "Subjects") 
                {
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $sub_cat_title    =  $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $title            =  $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $code             =  $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        
                        
                        $q2               =  "SELECT subj_categ_id FROM ". get_school_db() .".subject_category WHERE school_id = $school_id and title='$sub_cat_title'";
                        $row2             =  $this->db->query($q2)->row();
                        $subj_categ_id    =  $row2->subj_categ_id;
                        
                        if($subj_categ_id == ""){
                            $subj_categ_id = 0;
                        }
                        

                        if($title !== null && $title !== '' && !empty($title))
                        {
                            
                            $data['name']           =  $title;
                            $data['code']           =  $code;
                            $data['school_id']      =  $_SESSION['school_id'];
                            $data['subj_categ_id']  =  $subj_categ_id;
                            
                            $this->db->insert(get_school_db().'.subject',$data);
                       
                        }
                        
                    }
                }
            }
            
            
            $update_data = array(
                 'subjects' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);
            
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    function save_designation()
    {
        $this->db->trans_begin();
        
        $school_id         = $_SESSION['school_id'];
        
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $sheetname = $worksheet->getTitle();
                if ($sheetname == "Designation") 
                {
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        $title          =   $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $is_teacher     =   $worksheet->getCellByColumnAndRow(3, $row)->getValue();

                        if($title !== null && $title !== '' && !empty($title))
                        {
                            
                            $data['title']          =  $title;
                            $data['school_id']      =  $_SESSION['school_id'];
                            $data['status']         =  1;
                            $data['is_teacher']     =  $is_teacher;
                            
                            $this->db->insert(get_school_db().'.designation',$data);
                       
                        }
                        
                    }
                }
            }
            
            $update_data = array(
                 'designation' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);
            
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    function save_staff()
    {
        $this->db->trans_begin();

        $school_id         = $_SESSION['school_id'];
        
        $import            = $this->input->post('import');
        $tmp               = explode('.', $_FILES["excel"]["name"]);
        $extension         = end($tmp);
        $allowed_extension = array("xls", "xlsx", "csv");
        
        if (in_array($extension, $allowed_extension))
        {
            
            $file = $_FILES["excel"]["tmp_name"]; 
            $this->load->library('Excel');
            $objPHPExcel = PHPExcel_IOFactory::load($file); 

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $sheetname = $worksheet->getTitle();
                if ($sheetname == "Staff") 
                {
                    for ($row = 2; $row <= $highestRow; $row++)
                    {
                        
                        $id_no          =   $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                        if($id_no !== null && $id_no !== '' && !empty($id_no))
                        {
                    
                            $data['id_no']          = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                            $data['id_type']        = 1;
                            $data['name']           = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                            $data['employee_code']  = $worksheet->getCellByColumnAndRow(4, $row)->getValue(); 
                            $desg_title             = $worksheet->getCellByColumnAndRow(5, $row)->getValue();  
                            
                            $q2                     =  "SELECT designation_id , is_teacher FROM ". get_school_db() .".designation WHERE school_id = $school_id and title='$desg_title'";
                            $row2                   =  $this->db->query($q2)->row();
                            $designation_id         =  $row2->designation_id;
                            $is_teacher             =  $row2->is_teacher;
                            
                            if($designation_id == ""){
                                $designation_id = 0;
                            }
                            
                            $data['designation_id'] = $designation_id;
                            $data['dob']            = date('Y-m-d', strtotime($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
                            $data['gender']         = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                            $data['religion']       = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                            $data['postal_address'] = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $data['mobile_no']      = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $data['email']          = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                            
                            if($is_teacher == 1){
                               $data['periods_per_week'] = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                               $data['periods_per_day']  = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                            }
                            
                            
                            $data['school_id'] = $school_id;
                            $this->db->insert(get_school_db().'.staff',$data);
                            
                        }

                    }
                    
                }
            }
            
            
            $update_data = array(
                 'staff' =>  1
            );
            $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
            $this->db->update(get_system_db().'.school_configuration',$update_data);
            
            
        }
        else
        {
            $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
        }
        
        
     
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        redirect(base_url()."sheets/import_list");
        
    }
    
    
    function save_imported_students()
    {
        $this->db->trans_begin();
        
        $school_id  = $_SESSION['school_id'];

        $import     =  $this->input->post('import');
        
        //if ($import != "")
        {
            $tmp = explode('.', $_FILES["excel"]["name"]);
            $extension = end($tmp);
            $allowed_extension = array("xls", "xlsx", "csv"); 
            if (in_array($extension, $allowed_extension))
            {
                
                $file = $_FILES["excel"]["tmp_name"]; 
                
                $this->load->library('Excel');
                $objPHPExcel = PHPExcel_IOFactory::load($file); 

                
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
                {
                    $highestRow = $worksheet->getHighestRow();
                    $sheetname = $worksheet->getTitle();
                    if ($sheetname == "Student Details") 
                    {
                        for ($row = 2; $row <= $highestRow; $row++)
                        {
                            $form_num    = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            
                            if($form_num !== null && $form_num !== '' && !empty($form_num))
                            {
                        
                                $Registration = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                                $ClassName    = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                                $q1           = "SELECT * FROM ". get_school_db() .".class JOIN ". get_school_db() .".class_section ON class.class_id = class_section.class_id WHERE name = '" . $ClassName . "' ";
                                
                                $row1         = $this->db->query($q1);
                                $details_arr  = $row1->result_array();
                                
                                $section_id             = $details_arr[0]["section_id"];
                                $section_name           = $details_arr[0]["title"];
                                $section_departments_id = $details_arr[0]["departments_id"];
                                
                                
                                if($section_departments_id == 1){
                                    $departmentsname = 'Primary';
                                }
                                else if($section_departments_id == 2){
                                    $departmentsname = 'Secondary';  
                                }
                                

                                $Class     = $section_id; 
                                $adm_date  =  $worksheet->getCellByColumnAndRow(4, $row)->getFormattedValue();
                                $amndsDate = "";
                                
                                if($adm_date != ""){ 
                                    $amndsDate = date("Y-m-d", strtotime($adm_date));
                                }
                                
                                
                                $studentRollNumber   =  $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                                $Student_Name        =  $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                                $pre_date_of_birth   =  $worksheet->getCellByColumnAndRow(7, $row)->getFormattedValue();
                                $dob                 =  date('Y-m-d',strtotime($pre_date_of_birth));
                                $Gender              =  $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                                
                                if($Gender == 'Female'){
                                  $Gender ='female'; 
                                }
                                elseif($Gender == 'Male'){
                                  $Gender ='male';  
                                }
                                
                               
                                
                                $Religion_name        =  $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                                $Religion             =  get_religion(trim($Religion_name));
                                $NIC                  =  $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                                
                                $City                 =  $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                                $Address              =  $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                                $Mobile               =  $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                                
                                $emg_num               =  $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                                $email                 =  $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                                $bloodGroup            =  $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                                $Disability            =  $worksheet->getCellByColumnAndRow(19, $row)->getValue();

                                $FatherName            =  $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                                $FatherNIC             =  $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                                $FatherContectNumber   =  $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                                $FatherOccupation      =  $worksheet->getCellByColumnAndRow(23, $row)->getValue();

                                $MotherName            =  $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                                $MotherNIC             =  $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                                $MotherContectNumber   =  $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                                $MotherOccupation      =  $worksheet->getCellByColumnAndRow(27, $row)->getValue();

                                $GuardianName          =  $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                                $GuardianNIC           =  $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                                $GuardianContectNumber =  $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                                $GuardianOccupation    =  $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                                $UserGroupID           =  $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                                $AcademicYearID        =  $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                                $AcademicTermID        =  $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                                
                                $date_added            =   date('Y-m-d');
                                $added_by              =   $school_id;
                                $date_confirmed        =   date('Y-m-d');  
                                $confirmed_by          =   $school_id;
                                
                                $Mobile1               =   str_replace('-', '', $Mobile);  
                                $emg_num1              =   str_replace('-', '', $emg_num); 
                                
                                
                                
                                $data['reg_num']              =  $Registration;
                                $data['section_id']           =  $Class;
                                $data['student_category_id']  =  $section_departments_id;
                                $data['adm_date']             =  $amndsDate;
                                $data['roll']                 =  $studentRollNumber;
                                $data['name']                 =  $Student_Name;
                                $data['birthday']             =  $dob;
                                $data['gender']               =  $Gender;
                                $data['religion']             =  $Religion;
                                $data['id_no']                =  $NIC;
                                $data['form_num']             =  $form_num;
                                $data['address']              =  $Address;
                                $data['mob_num']              =  $Mobile1;
                                $data['emg_num']              =  $emg_num1;
                                $data['email']                =  $email;
                                $data['bd_group']             =  $bloodGroup;
                                $data['disability']           =  $Disability;
                                $data['student_status']       =  10;
                                $data['school_id']            =  $_SESSION['school_id'];
                                $data['date_added']           =  $date_added;
                                $data['added_by']             =  $added_by;
                                $data['date_confirmed']       =  $date_confirmed;
                                $data['confirmed_by']         =  $confirmed_by;
                                $data['adm_term_id']          =  $AcademicTermID;
                                $data['academic_year_id']     =  $AcademicYearID;
                                
                                $this->db->insert(get_school_db().'.student',$data);
                           
                                $student_Last_Id = $this->db->insert_id();
                                
                                $scl_id        = $school_id;
                                $bar_code_type = 112;
                                $school_id_bar = sprintf("%'06d", $scl_id);
                                $std_id        = sprintf("%'07d", $student_Last_Id);
                                $system_id     = $bar_code_type . '' . $school_id_bar . '' . $std_id;


                        
                                $updata = array(
                                     'system_id' =>  $system_id,
                                     'barcode_image' => $system_id.'png'
                                );
                                $this->db->where('student_id',$student_Last_Id);
                                $this->db->where('school_id',$scl_id);
                                $this->db->update(get_school_db().'.student',$updata);

                                $user_rights_data['user_group_id']      =   $UserGroupID;   
                                $user_rights_data['school_id']          =   $school_id;   
                                $user_rights_data['student_id']         =   $student_Last_Id;
                                
                                $this->db->insert(get_school_db().'.user_rights',$user_rights_data);
                                
                                
                                $parent_password      = passwordHash('12345');
                        
                                $FatherContectNumber1 = str_replace('-', '', $FatherContectNumber); 
                                if($FatherName != ""){
                                    
                                    $query_father_id  = "select s_p_id from ".get_school_db().".student_parent where id_no = '$FatherNIC'";
                                    $query_father_row = $this->db->query($query_father_id)->row();
                                    if($query_father_row != null){
                                        $father_Last_Id =  $query_father_row->s_p_id;
                                    }
                                    else
                                    {
                                        $email_parent = 'parent_f_'.$student_Last_Id.'_'.$scl_id.'@indiciedu.com';
                                        
                                        
                                        $dataf['p_name']       =  $FatherName;
                                        $dataf['id_no']        =  $FatherNIC;
                                        $dataf['contact']      =  $FatherContectNumber1;
                                        $dataf['occupation']   =  $FatherOccupation;
                                        $dataf['school_id']    =  $_SESSION['school_id'];
                                        $dataf['nationality']  =  1;
                                        $dataf['id_type']      =  1;
                                        
                                        $this->db->insert(get_school_db().'.student_parent',$dataf);
                                        $father_Last_Id =  $this->db->insert_id(); 
                                        
                                    }
      
                                }
                                
                                $MotherContectNumber1 = str_replace('-', '', $MotherContectNumber); 
                                if($MotherName != ""){
                                    
                                    $query_mother_id  = "select s_p_id from ".get_school_db().".student_parent where id_no = '$MotherNIC'";
                                    $query_mother_row = $this->db->query($query_mother_id)->row();
                                    if($query_mother_row != null){
                                        $mother_Last_Id =  $query_mother_row->s_p_id;
                                    }
                                    else
                                    {
                                        $email_parent = 'parent_m_'.$student_Last_Id.'_'.$scl_id.'@indiciedu.com';
                                        
                                        $datam['p_name']       =  $MotherName;
                                        $datam['id_no']        =  $MotherNIC;
                                        $datam['contact']      =  $MotherContectNumber1;
                                        $datam['occupation']   =  $MotherOccupation;
                                        $datam['school_id']    =  $scl_id;
                                        $datam['nationality']  =  1;
                                        $datam['id_type']      =  1;
                                        
                                        $this->db->insert(get_school_db().'.student_parent',$datam);
                                        $mother_Last_Id =  $this->db->insert_id(); 
    
                                    }

                                }
                                
                                $GuardianContectNumber1= str_replace('-', '', $GuardianContectNumber); 
                                if($GuardianName != ""){
                                    
                                    $query_guadian_id  = "select s_p_id from  ".get_school_db().".student_parent where id_no = '$GuardianNIC'";
                                    $query_guadian_row = $this->db->query($query_guadian_id)->row();
                                    if($query_guadian_row != null){
                                        $guadian_Last_Id =  $query_guadian_row->s_p_id;
                                    }
                                    else
                                    {
                                        
                                        $datag['p_name']       =  $GuardianName;
                                        $datag['id_no']        =  $GuardianNIC;
                                        $datag['contact']      =  $GuardianContectNumber1;
                                        $datag['occupation']   =  $GuardianOccupation;
                                        $datag['school_id']    =  $scl_id;
                                        $datag['nationality']  =  1;
                                        $datag['id_type']      =  1;
                                        
                                        $this->db->insert(get_school_db().'.student_parent',$datag);
                                        $guadian_Last_Id =  $this->db->insert_id(); 

                                    }
         
                                }
                                
                                if($FatherName != ""){
                                    
                                        $dataff['student_id']    =  $student_Last_Id;
                                        $dataff['s_p_id']        =  $father_Last_Id;
                                        $dataff['relation']      =  'f';
                                        $dataff['school_id']     =  $scl_id;
                                        
                                        $this->db->insert(get_school_db().'.student_relation',$dataff);

                                }
                                
                                if($MotherName != ""){
                                    
                                        $datamm['student_id']    =  $student_Last_Id;
                                        $datamm['s_p_id']        =  $mother_Last_Id;
                                        $datamm['relation']      =  'm';
                                        $datamm['school_id']     =  $scl_id;
                                        
                                        $this->db->insert(get_school_db().'.student_relation',$datamm);
                                              

                                }
                                
                                if($GuardianName != ""){
                                    
                                    
                                        $datagg['student_id']    =  $student_Last_Id;
                                        $datagg['s_p_id']        =  $guadian_Last_Id;
                                        $datagg['relation']      =  'g';
                                        $datagg['school_id']     =  $scl_id;
                                        
                                        $this->db->insert(get_school_db().'.student_relation',$datagg);
                                
                                    
                                }
                           
                            }
                            
                        }
                    }
                }
                
                
                 $update_data = array(
                 'student' =>  1
                 );
                 $this->db->where('sys_sch_id',$_SESSION['sys_sch_id']);
                 $this->db->update(get_system_db().'.school_configuration',$update_data);  
                
                
            }
            else
            {
                $this->session->set_flashdata('error_message', 'File Extention is not Allowed');
            }
            

        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'File cannot be imported!');
            
        } 
        else 
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('success_message', 'File Imported Successfully!');
        }
        
        redirect(base_url()."sheets/import_list");
        
    }
    
    
    
    
    

}




























