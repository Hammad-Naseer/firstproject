<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
//session_start();
class Templates extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		if($_SESSION['user_login']!= 1)
		redirect(base_url() . 'login');
    }
 
    function sms_templates()
    {
        $page_data['page_name'] ='sms_templates';
        $page_data['page_title'] = get_phrase('sms_templates');
        $this->load->view('backend/index', $page_data);	
    }
    
    function save_sms_template()
    {
        $data['sms_title'] = $this->input->post('sms_title');
        $data['sms_content'] = $this->input->post('sms_content');
        $data['sms_template_status'] = $this->input->post('sms_template_status');
        
        $this->db->insert(get_school_db().'.sms_templates',$data);
        $this->session->set_flashdata('club_updated',get_phrase('sms_template_added_successfully'));
        redirect(base_url() . 'templates/sms_temp_listing');
        
    }
    
    function sms_temp_listing()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        
        $quer = "select * from " . get_school_db() . ".sms_templates ";
        $sms_count = $this->db->query($quer)->result_array();
        $page_data['sms_tem_arr'] = $sms_count;

        $page_data['page_name'] = 'sms_temp_listing';
        $page_data['page_title'] = get_phrase('sms_temp_listing');
        $this->load->view('backend/index', $page_data);
        
    }
    
    function delete_sms_temp($sms_temp_id = 0)
    {
        $this->db->where('sms_temp_id', $sms_temp_id);
        $this->db->delete(get_school_db().'.sms_templates');
        $this->session->set_flashdata('info', 'Template Deleted succesfully!');
        redirect(base_url() . 'templates/sms_temp_listing');
    }
    
    function edit_sms_temp($sms_temp_id = 0)
    {
        
        $sms_edit_qur = "select * from ".get_school_db().".sms_templates where sms_temp_id = ".$sms_temp_id." ";
        $sms_edit_arr = $this->db->query($sms_edit_qur)->result_array();
        $page_data['sms_edit_arr'] = $sms_edit_arr;
        $page_data['page_name'] ='edit_sms_temp';
        $page_data['page_title'] = get_phrase('edit_sms_temp');
        $this->load->view('backend/index', $page_data);	
    }
    
    function update_sms_temp()
    {
        $sms_temp_id = $this->input->post('sms_temp_id');
        
        $data['sms_title'] = $this->input->post('sms_title');
        $data['sms_content'] = $this->input->post('sms_content');
        $data['sms_template_status'] = $this->input->post('sms_template_status');
        
        $this->db->where('sms_temp_id', $sms_temp_id); 
        $this->db->update(get_school_db().'.sms_templates',$data);
        $this->session->set_flashdata('info', 'Record updated succesfully!');
        redirect(base_url() . 'templates/sms_temp_listing');
    }
        
    function email_templates()
    {
        $page_data['page_name'] ='email_templates';
        $page_data['page_title'] = get_phrase('email_templates');
        $this->load->view('backend/index', $page_data);	
    }
    
    function save_email_template()
    {
        $data1['email_title'] = $this->input->post('email_title');
        $data1['email_subject'] = $this->input->post('email_subject');
        $data1['email_content'] = $this->input->post('email_content');
        $data1['email_template_status'] = $this->input->post('email_template_status');
        
        $this->db->insert(get_school_db().'.email_templates',$data1);
        $this->session->set_flashdata('club_updated',get_phrase('Email_template_added_successfully'));
        redirect(base_url() . 'templates/email_temp_listing');
        
    }
    
    function email_temp_listing()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        $quer = "select * from " . get_school_db() . ".email_templates ";
        $email_count = $this->db->query($quer)->result_array();
        $page_data['email_temp_arr'] = $email_count;

        $page_data['page_name'] = 'email_temp_listing';
        $page_data['page_title'] = get_phrase('email_temp_listing');
      
        $this->load->view('backend/index', $page_data);
    }
    
    function delete_email_temp($email_temp_id = 0)
    {
        $this->db->where('email_temp_id', $email_temp_id);
        $this->db->delete(get_school_db().'.email_templates');
        $this->session->set_flashdata('info', 'Template Deleted succesfully!');
        redirect(base_url() . 'templates/email_temp_listing');
    }
    
    function edit_email_temp($email_temp_id = 0)
    {
        $email_edit_qur = "select * from ".get_school_db().".email_templates where email_temp_id = ".$email_temp_id." ";
        $email_edit_arr = $this->db->query($email_edit_qur)->result_array();
        $page_data['email_edit_arr'] = $email_edit_arr;
        
        $page_data['page_name'] ='edit_email_temp';
        $page_data['page_title'] = get_phrase('edit_email_temp');
        $this->load->view('backend/index', $page_data);	
    }
    
    function update_email_temp()
    {
        $email_temp_id = $this->input->post('email_temp_id');
        $data1['email_title'] = $this->input->post('email_title');
        $data1['email_subject'] = $this->input->post('email_subject');
        $data1['email_content'] = $this->input->post('email_content');
        $data1['email_template_status'] = $this->input->post('email_template_status');
        
        $this->db->where('email_temp_id', $email_temp_id); 
        $this->db->update(get_school_db().'.email_templates',$data1);
        $this->session->set_flashdata('info', 'Record updated succesfully!');
        redirect(base_url() . 'templates/email_temp_listing');
    }
    
    //_______________________________________________________________________
    //_______________________________________________________________________
    
    // function sms_testing()
    // {
    //     $quer = "select * from " . get_school_db() . ".sms_templates";
    //     $sms_count = $this->db->query($quer)->result_array();
        
    //     //method 1
    //     $content = $sms_count[1]['sms_content'];
    //     $content = str_replace('student_name','hammad',$content);
    //     $content = str_replace('class_name','ONE',$content);
    //     echo $content;
    //     // $coords = str_replace('(',' ' , $newstr );
    //     echo "<br>";
    //     // //method 2 
    //     // $student_name = "ALI";
    //     // $class_name = "TWO";
    //     //  echo $content1 = $sms_count[2]['sms_content'];
         
         
    // }
    //_______________________________________________________________________
    //_______________________________________________________________________
    
    function email_layout_setting($param1 = '')
    {
        if ($param1 == 'do_update')
        {
            $data['school_name']=$this->input->post('school_name');
            $data['address']=$this->input->post('address');
            $data['terms'] = $this->input->post('terms');
            $data['school_id']=$_SESSION['school_id'];
            $email_layout_id=$this->input->post('email_layout_id');
            $data['email_layout_id']=$email_layout_id;
            $del_logo = $this->input->post('del_logo');
            $logo=$_FILES['logo']['name'];

            if($logo==""){

                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('email_layout_id',$email_layout_id);
                $this->db->update(get_school_db().'.email_layout_settings',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
                redirect(base_url() . 'templates/email_layout_setting/');

            }

            else
            {

                if($del_logo!=""){
                    $del_location=system_path($del_logo,'');
                    file_delete($del_location);
                }
                $data['logo'] =file_upload_fun("logo","","c_setting");
                $this->db->where('school_id',$_SESSION['school_id']);
                $this->db->where('email_layout_id',$email_layout_id);
                $this->db->update(get_school_db().'.email_layout_settings',$data);
                $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
                redirect(base_url() . 'templates/email_layout_setting/');

            }
        }
        
        if ($param1 == 'do_insert')
        {
            $data['school_name']=$this->input->post('school_name');
            $data['address']=$this->input->post('address');
            $data['terms'] = $this->input->post('terms');
            $data['school_id']=$_SESSION['school_id'];
            $email_layout_id=$this->input->post('email_layout_id');
            $data['email_layout_id']=$email_layout_id;
            $del_logo = $this->input->post('del_logo');
            $logo=$_FILES['logo']['name'];

            if($del_logo!=""){

                $del_location=system_path($del_logo,'');
                file_delete($del_location);
            }

            if($logo!=""){
                $data['logo']=file_upload_fun("logo","","c_setting");
            }

            $data['school_id']=$_SESSION['school_id'];
            $this->db->insert(get_school_db().'.email_layout_settings',$data);
            $this->session->set_flashdata('club_updated',get_phrase('record_updated_successfully'));
            redirect(base_url().'templates/email_layout_setting/');
        }
        
        $page_data['page_name'] ='email_layout_setting';
        $page_data['page_title'] = get_phrase('email_layout_setting');
        $this->load->view('backend/index', $page_data);
    }
    
    function email_layout_view()
    {
        
        $qur = "select * from ".get_school_db().".email_layout_settings where school_id = ".$_SESSION['school_id']." ";
        $email_layout_arr = $this->db->query($qur)->result_array();
        
        $page_data['email_layout_arr'] =$email_layout_arr;
        
        $page_data['page_name'] ='email_layout_view';
        $page_data['page_title'] = get_phrase('email_layout_view');
        $this->load->view('backend/index', $page_data);
    }
    
    function email_test()
    {
        $qur = "select * from ".get_school_db().".email_layout_settings where school_id = ".$_SESSION['school_id']." ";
        $email_layout_arr = $this->db->query($qur)->result_array();
        
        $school_name = $_SESSION['school_name'];
        $address = "<a target='_blank' href='https://indiciedu.com.pk/'>indici-edu</a>";
        $logo = $_SESSION['school_logo'];
        
        $folder_name = $_SESSION['folder_name'];
        
         $logo_path = base_url()."uploads/".$folder_name."/".$_SESSION['school_logo'];
         $email_img = base_url().'assets/images/email_img.jpg';
         
         $message = "A new assignment has been assigned to Haris for English - 120-EN and is due by 17-Jun-2020, please login to your account for more details.";
        
        
        $aa = '<style type="text/css">
          #outlook a { padding:0; }
          .ReadMsgBody { width:100%; }
          .ExternalClass { width:100%; }
          .ExternalClass * { line-height:100%; }
          body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
          table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
          img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
          p { display:block;margin:13px 0; }
        </style>
        <!--[if !mso]><!-->
        <style type="text/css">
          @media only screen and (max-width:480px) {
            @-ms-viewport { width:320px; }
            @viewport { width:320px; }
          }
        </style>
        <!--<![endif]-->
        <!--[if mso]>
        <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
        <!--[if lte mso 11]>
        <style type="text/css">
          .outlook-group-fix { width:100% !important; }
        </style>
        <![endif]-->
        
      <!--[if !mso]><!-->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
        <style type="text/css">
          @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);
        </style>
      <!--<![endif]-->

    
        
    <style type="text/css">
      @media only screen and (min-width:480px) {
        .mj-column-per-66 { width:66.66666666666666% !important; max-width: 66.66666666666666%; }
.mj-column-per-33 { width:33.33333333333333% !important; max-width: 33.33333333333333%; }
.mj-column-per-100 { width:100% !important; max-width: 100%; }
      }
    </style>
    
  
        <style type="text/css">
        
        

    @media only screen and (max-width:480px) {
      table.full-width-mobile { width: 100% !important; }
      td.full-width-mobile { width: auto !important; }
    }
  
        </style>
        <style type="text/css">.hide_on_mobile { display: none !important;} 
        @media only screen and (min-width: 480px) { .hide_on_mobile { display: block !important;} }
        .hide_section_on_mobile { display: none !important;} 
        @media only screen and (min-width: 480px) { .hide_section_on_mobile { display: table !important;} }
        .hide_on_desktop { display: block !important;} 
        @media only screen and (min-width: 480px) { .hide_on_desktop { display: none !important;} }
        .hide_section_on_desktop { display: table !important;} 
        @media only screen and (min-width: 480px) { .hide_section_on_desktop { display: none !important;} }
        [owa] .mj-column-per-100 {
            width: 100%!important;
          }
          [owa] .mj-column-per-50 {
            width: 50%!important;
          }
          [owa] .mj-column-per-33 {
            width: 33.333333333333336%!important;
          }
          p {
              margin: 0px;
          }
          @media only print and (min-width:480px) {
            .mj-column-per-100 { width:100%!important; }
            .mj-column-per-40 { width:40%!important; }
            .mj-column-per-60 { width:60%!important; }
            .mj-column-per-50 { width: 50%!important; }
            mj-column-per-33 { width: 33.333333333333336%!important; }
            }</style><div style="background-color:#FDFDFD;">
        
      
      <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    
      
      <div style="background:#40b3f4;background-color:#40b3f4;Margin:0px auto;max-width:600px;">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#40b3f4;background-color:#40b3f4;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:0px 0px 0px 0px;text-align:center;vertical-align:top;">
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:middle;width:399.99999999999994px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-66 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:middle;" width="100%">
        
            <tbody><tr>
              <td align="left" style="font-size:0px;padding:0px 0px 0px 10px;word-break:break-word;">
                
      <div style="align-content: center;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size: 14px;line-height:1.5;text-align: center;color: white;margin-top: 10px;">
        <h2>'.$_SESSION['school_name'].'</h2>
      </div>
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
            <td
               class="" style="vertical-align:middle;width:199.99999999999997px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-33 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:middle;" width="100%">
        
            <tbody><tr>
              <td align="right" style="font-size:0px;padding:0px 10px 0px 0px;word-break:break-word;">
                
      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:1.5;text-align:center;color:#000000;">
       <img src="'.$logo_path.'" style="height: 60px;width: 120px;margin-top: 10px; border-radius:10px;">
      </div>
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
        
      </div>
    
      
      <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    
      
      <div style="background:#40b3f4;background-color:#40b3f4;Margin:0px auto;max-width:600px;">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#40b3f4;background-color:#40b3f4;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:0px 0px 0px 0px;text-align:center;vertical-align:middle;">
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:middle;width:600px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:middle;" width="100%">
        
            <tbody><tr>
              <td style="font-size:0px;word-break:break-word;">
                
      
    <!--[if mso | IE]>
    
        <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td height="10" style="vertical-align:top;height:10px;">
      
    <![endif]-->
  
      <div style="height:10px;">
        &#xA0;
      </div>
  
    
              </td>
            </tr>
          
            <tr>
              <td align="center" style="font-size:0px;padding:0px 0px 0px 0px;word-break:break-word;">

              <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
              <tbody>
              <tr>
              <td style="width:600px; height:20px; background:#3f4d61;">

              &nbsp;
              </td>
              </tr>
              </tbody>
              </table>

              </td>
            </tr>
          
            
          
            
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
        
      </div>
    
      
      <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    
      
      <div style="background:white;background-color:white;Margin:0px auto;max-width:600px;">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:white;background-color:white;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:0px 0px 0px 0px;text-align:center;vertical-align:top;">
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
        
            <tbody><tr>
              <td style="font-size:0px;word-break:break-word;">
                
      
    <!--[if mso | IE]>
    
        <table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td height="15" style="vertical-align:top;height:15px;">
      
    <![endif]-->
  
      <div style="height:15px;">
        &#xA0;
      </div>
      
    <!--[if mso | IE]>
    
        </td></tr></table>
      
    <![endif]-->
  
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
        
      </div>
    
      
      <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    
      
      <div style="background:white;background-color:white;Margin:0px auto;max-width:600px; min-height:150px;">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:white;background-color:white;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:9px 0px 9px 0px;text-align:center;vertical-align:top;">
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
        
            <tbody><tr>
              <td align="center" style="font-size:0px;padding:0px 0px 0px 0px;word-break:break-word;">
                
      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:1.5;text-align:center;color:#000000;">
       
        <p>
        <span style="font-size:14px;" id="email_content">
            '.$message.'
        </span>
        </p>


      </div>
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
        
      </div>
    
      
      <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    
      
      <div style="background:white;background-color:white;Margin:0px auto;max-width:600px;">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:white;background-color:white;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:0px 0px 0px 0px;text-align:center;vertical-align:top;">
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
        
            <tbody><tr>
              <td align="center" vertical-align="middle" style="font-size:0px;padding:0px 12px 12px 12px;word-break:break-word;">
                
      
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
        
      </div>
    
      
      <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    
      
      <div style="background:#40b3f4;background-color:#40b3f4;Margin:0px auto;max-width:600px;">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#3f4d61;background-color:#3f4d61;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:0px 0px 0px 0px;text-align:center;vertical-align:top;">
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
        
            <tbody><tr>
              <td align="center" style="font-size:0px;padding:0px 0px 0px 0px;word-break:break-word;">
                
      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:1.5;text-align:center;color:#000000;">
        <p><span style="color:#ffffff;"><span style="font-size:14px;"><strong>FOLLOW US</strong></span></span></p>
      </div>
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
        
      </div>
    
      
      <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    
      
      <div style="background:#40b3f4;background-color:#40b3f4;Margin:0px auto;max-width:600px;">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#40b3f4;background-color:#40b3f4;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:2px 0px 2px 0px;text-align:center;vertical-align:top;">
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
        
            <tbody><tr>
              <td align="center" style="font-size:0px;padding:10px 10px 10px 10px;word-break:break-word;">
                
      
     <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
      >
        <tr>
      
              <td>
            <![endif]-->
              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                
      <tbody><tr>
        <td style="padding:4px;">
          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:transparent;border-radius:3px;width:35px;">
            <tbody><tr>
              <td style="font-size:0;height:35px;vertical-align:middle;width:35px;">
                <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.facebook.com/PROFILE" target="_blank">
                    <img height="35" src="https://s3-eu-west-1.amazonaws.com/ecomail-assets/editor/social-icos/roundedwhite/facebook.png" style="border-radius:3px;display:block;" width="35">
                  </a>
                </td>
              </tr>
          </tbody></table>
        </td>
        
      </tr>
    
              </tbody></table>
            <!--[if mso | IE]>
              </td>
            
              <td>
            <![endif]-->
              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                
      <tbody><tr>
        <td style="padding:4px;">
          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:transparent;border-radius:3px;width:35px;">
            <tbody><tr>
              <td style="font-size:0;height:35px;vertical-align:middle;width:35px;">
                <a href="https://twitter.com/home?status=https://www.twitter.com/PROFILE" target="_blank">
                    <img height="35" src="https://s3-eu-west-1.amazonaws.com/ecomail-assets/editor/social-icos/roundedwhite/twitter.png" style="border-radius:3px;display:block;" width="35">
                  </a>
                </td>
              </tr>
          </tbody></table>
        </td>
        
      </tr>
    
              </tbody></table>
            <!--[if mso | IE]>
              </td>
            
              <td>
            <![endif]-->
              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                
      <tbody><tr>
        <td style="padding:4px;">
          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:transparent;border-radius:3px;width:35px;">
            <tbody><tr>
              <td style="font-size:0;height:35px;vertical-align:middle;width:35px;">
                <a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=[[SHORT_PERMALINK]]&amp;title=&amp;summary=&amp;source=" target="_blank">
                    <img height="35" src="https://s3-eu-west-1.amazonaws.com/ecomail-assets/editor/social-icos/roundedwhite/linkedin.png" style="border-radius:3px;display:block;" width="35">
                  </a>
                </td>
              </tr>
          </tbody></table>
        </td>
        
      </tr>
    
              </tbody></table>
            <!--[if mso | IE]>
              </td>
            
              <td>
            <![endif]-->
              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="float:none;display:inline-table;">
                
      <tbody><tr>
        <td style="padding:4px;">
          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:transparent;border-radius:3px;width:35px;">
            <tbody><tr>
              <td style="font-size:0;height:35px;vertical-align:middle;width:35px;">
                <a href="[[SHORT_PERMALINK]]" target="_blank">
                    <img height="35" src="https://s3-eu-west-1.amazonaws.com/ecomail-assets/editor/social-icos/roundedwhite/instagram.png" style="border-radius:3px;display:block;" width="35">
                  </a>
                </td>
              </tr>
          </tbody></table>
        </td>
        
      </tr>
    
              </tbody></table>
            <!--[if mso | IE]>
              </td>
            
          </tr>
        </table>
      <![endif]-->
    
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
        
      </div>
    
      
      <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
    
      
      <div style="background:#40b3f4;background-color:#40b3f4;Margin:0px auto;max-width:600px;">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#40b3f4;background-color:#40b3f4;width:100%;">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:0px 0px 0px 0px;text-align:center;vertical-align:top;">
                <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                
        <tr>
      
            <td
               class="" style="vertical-align:top;width:600px;"
            >
          <![endif]-->
            
      <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
        
            <tbody><tr>
              <td align="center" style="font-size:0px;padding:0px 0px 0px 0px;word-break:break-word;">
                
      <div style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:1.5;text-align:center;color:#000000;">
        <p style="font-size: 11px;"><span style="color:#ffffff;">'.$address.'</span></p>

      </div>
    
              </td>
            </tr>
          
            <tr>
              <td align="center" style="font-size:0px;padding:0px 0px 0px 0px;word-break:break-word;">
                
      
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          <!--[if mso | IE]>
            </td>
          
        </tr>
      
                  </table>
                <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>
        
      </div>
    
      
      <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <![endif]-->
    
    
      </div>';
      
    //   echo $aa;
    //   exit;
    
    $bb = '<div style="Margin:0px auto;max-width:700px;">
	<table  width="100%" style="border-collapse: collapse;border-spacing: 0;">
		<tbody>
			<tr style="height: 100px; width: 100%; background-color: #40b3f4">
				<td colspan="2">
                    <div style="align-content: center;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size: 14px;line-height:1.5;text-align: center;color: white;margin-top: 10px;">
                    <h2>'.$_SESSION['school_name'].'</h2>
                    </div>
				</td>
				<td><img src="'.$logo_path.'" style="height: 60px;width: 120px;margin-top: 10px; border-radius:10px;"></td>
			</tr>
			<tr style="height: 10px; width: 100%; background-color: #3f4d61;">
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr style="height: 150px; width: 100%;  background-color: white;">
				<td colspan="3">
					<html>
						<head>
							<title></title>
						</head>
						<body>
							<div>
							<br>
								<strong>Dear ,</strong>
								<div style="margin-left: 30px;">
								<p>'.$message.'</p>
								<br>
								    <a class="btn btn-primary" style="text-decoration: none; color: #fff;
								    background-color: #337ab7;border-color: #2e6da4; display: inline-block;
								    margin-bottom: 0;font-weight: 400;text-align: center;white-space: nowrap;
								    vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;
								    cursor: pointer;background-image: none;border: 1px solid transparent;
								    padding: 6px 12px;font-size: 14px;line-height: 1.42857143;border-radius: 4px;
								    -webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;
								    user-select: none;" target="_blank" href="'.base_url().'login">Login</a>
								    <br>
								    <br>
									<strong>Best Regards</strong>
								</div>
							</div>
							<br>
						</body>
					</html>
				</td>
			</tr>
			<tr style="height: 10px; width: 100%; background-color: #3f4d61;">
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr style="height: 50px; width: 100%; background-color: #40b3f4">
				<td colspan="3">
					<p style="color:white;">*** This is an automatically generated email, please do not reply ...</p>
				</td>
			</tr>
			<tr style="height: 20px; width: 100%; text-align:center; background-color: #40b3f4">
				<td colspan="3">
					<a style="text-decoration: none;" href="https://indiciedu.com.pk/" target="blank">Indici-edu</a>
				</td>
			</tr>
		</tbody>
	</table>
	</div>';
	
	echo $bb;
	exit;
      
    // //_________________________________________________________________
    // //__________________________Email Setting her______________________
    //  $this->load->helper('message');
    
    // $to_email = 'hammadraja2003@gmail.com';
    // $subject = "Test Email";
    // $value = 1;
    // email_send("No Reply", "Indici Edu", $to_email, $subject, $aa, $value);
    
    // //__________________________email Ends here________________________
      
     
    }
    
    function email_test_from_helper ()
    {
        $this->load->helper('message');
    
        $to_email = 'hammadraja2003@gmail.com';
        $subject = "Test Email";
        $value = 1;
        $message = "hello hammad";
        $email_layout = get_email_layout($message);
        email_send("No Reply", "Indici Edu", $to_email, $subject, $email_layout, $value);
        
    }
    

}