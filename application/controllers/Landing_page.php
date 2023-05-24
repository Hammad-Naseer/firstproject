<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Landing_page extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
		$this->load->database();
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
    }
    
    function index()
    {
       
        $query                          = "select * from ".get_system_db().".system_school where sys_sch_id = ". $_SESSION['sys_sch_id'] ."";
        $row                            =  $this->db->query($query)->row();

        $galleryquery                   = "select * from ".get_system_db().".school_gallery_images where school_id = ". $_SESSION['sys_sch_id'] ."";
        $gallery_rows                   =  $this->db->query($galleryquery)->result_array();
        
        $facilityquery                  = "select * from ".get_system_db().".school_facilities where sys_sch_id = ". $_SESSION['sys_sch_id'] ."  order by id desc";
        $facility_rows                  =  $this->db->query($facilityquery)->result_array();
        
        $page_data['page_name']         =  'landing_page';
        $page_data['page_name']         =  'landing_page';
        $page_data['page_title']        =  get_phrase('landing_page');
        $page_data['landing_page_row']  =  $row;
        $page_data['gallery_rows']      =  $gallery_rows;
        $page_data['school_facilities'] =  $facility_rows;
        
        $this->load->view('backend/index', $page_data);   
    } 
    
    function save_facility()
    {
       $data_array = array(
            'sys_sch_id' =>  $_SESSION['sys_sch_id'],
            'title'      =>  $this->input->post('title'),
            'url'        =>  $this->input->post('url'),
            'InsertedAt' =>  date('Y-m-d H:i:s')
        );
        
        $this->db->insert(get_system_db().'.school_facilities', $data_array);
            
        $this->session->set_flashdata('club_updated', get_phrase('facility_record_is_saved_successfully'));
        redirect(base_url() . 'landing_page');   
    }
    
    function edit_facility($id)
    {
        
        $data_array = array(
            'title'     =>  $this->input->post('title'),
            'url'       =>  $this->input->post('url'),
            'UpdatedAt' =>  date('Y-m-d H:i:s')
        );
        

        $this->db->where('id', $id);
        $this->db->update(get_system_db().'.school_facilities', $data_array);
            
        $this->session->set_flashdata('club_updated', get_phrase('facility_record_is_updated_successfully'));
        redirect(base_url() . 'landing_page');  
    }
    
    function delete_school_facility($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(get_system_db().'.school_facilities'); 
        
        $this->session->set_flashdata('club_updated', get_phrase('facility_record_is_deleted_successfully'));
        redirect(base_url() . 'landing_page');
    }
    
    
    function gallery_images_action($param='')
    {
        
        $query  = "select * from ".get_system_db().".system_school where sys_sch_id = ". $_SESSION['sys_sch_id'] ."";
        $row    =  $this->db->query($query)->row();
        
        if($param=='create')
        {
           
            $data          = array();
            $data['image'] = "";
            $path          = 'assets/landing_pages/'.$row->sub_domain;
        
            $file_name_logo = $_FILES["image"]['name'];
            $temp_logo      = $_FILES["image"]['tmp_name'];
            
            if($file_name_logo != '')
            {
                $data['image']      = file_upload_landing_page($file_name_logo , $temp_logo , $row->sub_domain);
                $data['school_id']  = $_SESSION['sys_sch_id'];
                $this->db->insert(get_system_db().'.school_gallery_images', $data);
            }
        
        }
        
        if($param=='edit')
        {
            
            $id             = $this->input->post('img_id');
            $data           = array();
            $data['image']  = "";
            $path           = 'assets/landing_pages/'.$row->sub_domain;
            $oldimage       = $this->input->post('old_image');
   
            $file_name_logo = $_FILES["image"]['name'];
            $temp_logo      = $_FILES["image"]['tmp_name'];
            
            if($file_name_logo != '')
            {
                 $data['image']     = file_upload_landing_page($file_name_logo , $temp_logo , $row->sub_domain);
                 $data['school_id'] = $_SESSION['sys_sch_id'];
                 $this->db->where(array('img_id'=>$id))->update(get_system_db().'.school_gallery_images',$data);
                 if($row->logo != '')
                 {
                     unlink($path.'/'.$oldimage);
                 }
            }
          
        }
        echo"<script>window.history.back()</script>";
        
    }
    
    function delete_gallery_image($param)
    {
        $query2  = "select * from ".get_system_db().".school_gallery_images where img_id = ". $param."";
        $row2    =  $this->db->query($query2)->row();
        
        $this->db->query("delete from ".get_system_db().".school_gallery_images where img_id = ".$param." ");
        
        $query  = "select * from ".get_system_db().".system_school where sys_sch_id = ". $_SESSION['sys_sch_id'] ."";
        $row    =  $this->db->query($query)->row();
        $path   =  'assets/landing_pages/'.$row->sub_domain;
          
        unlink($path.'/'.$row2->image);
        echo"<script>window.history.back()</script>";
    }
    
    function update_info()
    {
        
        $query  = "select * from ".get_system_db().".system_school where sys_sch_id = ". $_SESSION['sys_sch_id'] ."";
        $row    =  $this->db->query($query)->row();
        
        $logo   =  $row->logo;
        $banner =  $row->banner_image;
        
        $path   =  'assets/landing_pages/'.$row->sub_domain;
        
        $file_name_logo = $_FILES["logo"]['name'];
        $temp_logo      = $_FILES["logo"]['tmp_name'];
        
        if($file_name_logo != '')
        {
             $logo   = file_upload_landing_page($file_name_logo , $temp_logo , $row->sub_domain);
             if($row->logo != '')
             {
                 unlink($path.'/'.$row->logo);
             }
        }
        
        
        $file_name_banner = $_FILES["banner_image"]['name'];
        $temp_banner      = $_FILES["banner_image"]['tmp_name'];
        if($file_name_banner != '')
        {
             $banner  = file_upload_landing_page($file_name_banner , $temp_banner , $row->sub_domain);
             if($row->banner_image != '')
             {
                 unlink($path.'/'.$row->banner_image);
             }
        }

        $data_update = array(
               'mobile_num'   => $this->input->post('mobile_num'),
               'whatsapp_num' => $this->input->post('whatsapp'),
               'email'        => $this->input->post('email'),
               'facebook'     => $this->input->post('facebook'),
               'twitter'      => $this->input->post('twitter'),
               'linkedin'     => $this->input->post('linkedin'),
               'logo'         => $logo,
               'about_us'     => $this->input->post('about_us'),
               'banner_image' => $banner,
               'latitude'     => $this->input->post('latitude'),
               'longitude'    => $this->input->post('longitude'),
        );
       
       $this->db->where('sys_sch_id', $_SESSION['sys_sch_id']);
       $this->db->update(get_system_db().'.system_school',$data_update); 
       
       $this->session->set_flashdata('club_updated',get_phrase('landing_page_details_are_updated_successfully'));
       redirect(base_url().'landing_page');
      
    }
    
    
}