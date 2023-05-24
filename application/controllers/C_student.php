<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();


class C_student extends CI_Controller
{

    // Student Status (Withdraw = 21) - (Promotion Request = 11) - (Demtion Request = 15) - (Transfer  = 1) - (Cancel = 51) - (Leave = 52) - (Passout = 53) - (Promoted = 14)

    function __construct()
    {
        parent::__construct();

        if ($_SESSION['user_login'] != 1)
            redirect('login');

        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->menu_ary = array();
        $this->load->helper('student');
    }
    
    public function fetch_students(){  
       $controller   = 's';
        $this->load->model("Student_model");  
        
        $fetch_data = $this->Student_model->make_datatables();
        // echo '<pre>';print_r($fetch_data);exit;
       $data = array();  
       $i = 1;
       foreach($fetch_data as $row)  
       {  
           $section_id = $row->section_id;
            $img = "";
            if($row->image == '')
            { 
               $img = base_url().'/uploads/default.png';
            }else{ 
               $img = display_link($row->image,'student');
            }
            
            $action = '';
            $action .= '
                    <div>
                        <div class="btn-group" data-step="3" data-position=""left" data-intro="student options:  profile , student card , edit , update guardian details , manage parent login , manage student login , promote , withdraw , transfer student , student chalan detail , chalan form  , monthly fee setting ">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">'.get_phrase("action").'<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">';
                        if($row->is_transfered == 1){
            $action .= '<li style="color:white !important">
                            <i class="entypo-user"></i>
                            '.get_phrase("transfer_requested").'
                        </li>';
                        }else{
                            if (right_granted('students_view')){    
                            $action .= '<li>
                                        <a href="'.base_url().'c_student/student_detail/'.$controller.'/'.str_encode($row->student_id).'/'.str_encode($section_id).'">
                                            <i class="entypo-user"></i>
                                            '.get_phrase("profile").'
                                        </a>
                                    </li>
                                     <li>
                                        <a href="'.base_url().'c_student/update_student_detail/'.$controller.'/'.str_encode($row->student_id).'/'.str_encode($section_id).'">
                                            <i class="entypo-user"></i>
                                            '.get_phrase("update_profile").'
                                        </a>
                                    </li>';
                            }
                        }if (right_granted('students_manage')){
                    $action .= '<li>
                                    <a href="'.base_url().'c_student/create_card/student/'.str_encode($row->student_id).'/'.str_encode($section_id).'">
                                        <i class="fas fa-address-card"></i>
                                        '.get_phrase("student_card").'
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="showAjaxModal(\' '.base_url().'modal/popup/modal_manage_parent_account/'.$row->student_id.' \' ) ;">
                                        <i class="fas fa-users-cog"></i>
                                ';         
                                        if($row->parent_id > 0){
                                            $upl = get_phrase('update_parent_login');
                                        }
                                        else
                                        {
                                            $upl = get_phrase('create_parent_login');
                                        }
                        $action .= $upl . '  </a>
                                </li>
                                
                        <li>
                                        <a href="'.base_url().'admin/update_student/'.str_encode($row->student_id)."/".str_encode($row->section_id).'">
                                            <i class="fas fa-user-lock"></i>
                                        ';
                                        if($row->is_login_created == 1){
                                            $usl = get_phrase('update_student_login');
                                        }
                                        else
                                        {
                                            $usl = get_phrase('create_student_login');
                                        }
                                        
                                $action .=  $usl . ' </a>
                                    </li>';
                                }
                        $action .=  '<li class="divider"></li>
                                    <li>
                                        <a href="#" onclick="showAjaxModal(\' '.base_url().'modal/popup/admission_status_change/'.$row->student_id.' \');">
                                            <i class="fas fa-user"></i>
                                            '.get_phrase("change_status").'
                                        </a>
                                    </li>';
                        
                        if($row->student_status==11 || $row->student_status==15)
                        {}else{
                          if (right_granted('students_promote'))
                          {
                            if($row->student_status == 13)
                            {

                        $action .=  '<li>
                            <a href="#" onclick="showAjaxModal(\''.base_url().'modal/popup/promote_section/'.$row->student_id.'\');">
                                <i class="fas fa-users-cog"></i>
                                '.get_phrase("confirm_promotion").'
                            </a>
                        </li>
                        ';
                        }else{
                $action .=  '<li>
                                <a href="#" onclick="showAjaxModal(\''.base_url().'modal/popup/promote_section/'.$row->student_id.'\');">
                                    <i class="fas fa-hospital-user"></i>
                                    '.get_phrase("promote").'
                                </a>
                            </li>
                        ';    
                            }
                        }
                    }
                    
        $action .=  '<li>
                        <a href="#" onclick="showAjaxModal(\''.base_url().'modal/popup/modal_class_conversion/'.$row->student_id.'\');">
                        <i class="fas fa-hospital-user"></i>
                        '.get_phrase("Class Conversion").'
                        </a>
                    </li>';

                    if($row->student_status==11){
                      $stu=3;
                      $qur_val=$this->db->query("select * from ".get_school_db().".student_chalan_form where student_id=".$row->student_id." and is_cancelled = 0 and school_id=".$_SESSION['school_id']." and form_type=$stu")->result_array();
                        if (right_granted('students_promote'))
                        {
                    $action .=  '<li>
                                    <a href="'.base_url().'class_chalan_form/student_chalan_form/'.$row->student_id.'/'.$stu.'/2">
                                        <i class="fas fa-file-signature"></i>
                                        '.get_phrase("promotion_chalan_form").'
                                    </a>
                                </li>';
                        if($qur_val[0]['status']==4){                               
                        $action .=  '<li>
                                    <a href="'.base_url().'class_chalan_form/view_print_chalan/'.$qur_val[0]['s_c_f_id'].'/'.$stu.'">
                                        <i class="fas fa-print"></i>
                                        '.get_phrase("print_Promotion_chalan").'
                                    </a>
                                </li>';
                    }if(count($qur_val)>0){
                $action .=  '<li>
                                <a href="'.base_url().'c_student/cancel_promotion/'.$row->student_id.'">
                                    <i class="fas fa-ban"></i>
                                    '.get_phrase("cancel_promotion_request").'
                                </a>
                            </li>';
            }
        } 
    }elseif($row->student_status == 15){
        $stu=4;
        $qur_val=$this->db->query("select s_c_f_id from ".get_school_db().".student_chalan_form where student_id=".$row->student_id." and is_cancelled = 0 and form_type=$stu")->result_array();
    $action .=  '<li>
                    <a href="'.base_url().'class_chalan_form/student_chalan_form/'.$row->student_id.'/'.$stu.'">
                        <i class="fas fa-file-invoice"></i>
                        '.get_phrase("demotion_chalan_form").'
                    </a>
                </li>
                <li>
                    <a href="'.base_url().'class_chalan_form/view_print_chalan/'.$qur_val[0]['s_c_f_id'].'/'.$stu.'">
                        <i class="fas fa-file-invoice"></i>
                        '.get_phrase("print_demotion_chalan").'
                    </a>
                </li>
    ';    
    }//promote student right
    if (right_granted('students_delete')){
    $action .=  '    <li>
                        <a href="#" onclick="confirm_modal(\''.base_url().'c_student/withdraw/'.$row->student_id.'/'.$row->section_id.'\');">
                            <i class="las la-money-bill-wave"></i>
                            '.get_phrase("withdraw").'
                        </a>
                    </li>';
    }if (right_granted('students_manage')){
    $action .=  '<li>
                    <a href="#" onclick="confirm_modal(\''.base_url().'transfer_student/student_transfer_form/'.$row->student_id.'/'.$section_id.'\');">
                        <i class="fas fa-exchange-alt"></i>
                        '.get_phrase("Transfer_student").'
                    </a>
                </li>';
            }
$action .=  '<li>
                <a href="'.base_url().'payments/payment_listing/'.str_encode($row->student_id).'/'.str_encode($section_id).'">
                    <i class="fas fa-info"></i>
                    '.get_phrase("student_chalan_detail").'
                </a>
            </li>
            <li>
            <a href="'.base_url().'class_chalan_form/student_chalan_form/'.str_encode($row->student_id).'/10/2">
            <i class="far fa-file-alt"></i>
            ';
            $qur_val2=$this->db->query("SELECT status as challan_status from ".get_school_db().".student_chalan_form 
                                                                                            WHERE school_id = ".$_SESSION['school_id']."
                                                                                                AND is_cancelled = 0
                                                                                                AND student_id=".$row->student_id." 
                                                                                                AND form_type=10")->result_array();
        if($qur_val2[0]['status']==5){
                $cf =  get_phrase('receive_challan_form');
        }else{
            $cf = get_phrase('custom_challan_form');
        }
$action .=  $cf . '            
            </a>
        </li>

                <li>
                    <a href="'.base_url().'c_student/student_m_installment/'.$row->student_id.'/'.$section_id.'">
                        <i class="las la-file-invoice-dollar"></i>
                        '.get_phrase("monthly_fee_settings").'
                    </a>
                </li>
                ';
                // }
        $action .= '
                        </ul>
                        </div>
                    </div>
        ';
        
    // }
                        
            /*            
            <div>
                    <div class="btn-group" data-step="3" data-position='left' data-intro="student options:  profile , student card , edit , update guardian details , manage parent login , manage student login , promote , withdraw , transfer student , student chalan detail , chalan form  , monthly fee setting ">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <?php
                            if($row['is_transfered']==1){
                                ?>
                        <li style="color:white !important">
                            <i class="entypo-user"></i>
                            <?php echo get_phrase('transfer_requested');?>
                        </li>
                                <?php
                                }else{
                                    ?>
                                <?php
                                if (right_granted('students_view'))
                                {
                                	
                                ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>c_student/student_detail/<?php echo $controller;?>/<?php echo $row['student_id']; ?>/<?php echo $section_id;?>">
                                            <i class="entypo-user"></i>
                                            <?php echo get_phrase('profile');?>
                                        </a>
                                    </li>
                                     <li>
                                        <a href="<?php echo base_url(); ?>c_student/update_student_detail/<?php echo $controller;?>/<?php echo $row['student_id']; ?>/<?php echo $section_id;?>">
                                            <i class="entypo-user"></i>
                                            <?php echo get_phrase('update_profile');?>
                                        </a>
                                    </li>
                                <?php
                                }
                                if (right_granted('students_manage'))
                                {
                                ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>c_student/create_card/student/<?php echo $row['student_id']; ?>/<?php echo $section_id; ?>">
                                            <i class="fas fa-address-card"></i>
                                            <?php echo get_phrase('student_card');?>
                                        </a>
                                    </li>
                                    
                            ---        
                                    <!-- STUDENT EDITING LINK -->
                                    <!--<li>-->
                                    <!--    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_manage_parent_account/<?php echo $row['student_id'];?>');">-->
                                    <!--        <i class="entypo-pencil"></i>-->
                                    <!--        <?php echo get_phrase('edit');?>-->
                                    <!--    </a>-->
                                    <!--</li>-->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_manage_parent_account/<?php echo $row['student_id'];?>');">
                                            <i class="fas fa-users-cog"></i>
                                            <?php 
                                                if($row['parent_id'] > 0){
                                                    echo get_phrase('update_parent_login');
                                                }
                                                else
                                                {
                                                    echo get_phrase('create_parent_login');
                                                }
                                            ?>
                                        </a>
                                    </li>
                                    
                                    ----
                                    
                                     <li>
                                        <a  href="<?php echo base_url();?>admin/update_student/<?php echo $row['student_id']."/".$row['section_id'] ?>">
                                            <i class="fas fa-user-lock"></i>
                                            <?php 
                                                if($row['is_login_created'] == 1){
                                                    echo get_phrase('update_student_login');
                                                }
                                                else
                                                {
                                                    echo get_phrase('create_student_login');
                                                }
                                            ?>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/admission_status_change/<?php echo $row['student_id'];?>');">
                                            <i class="fas fa-user"></i>
                                            <?php echo get_phrase('change_status');?>
                                        </a>
                                    </li>
                                    <?php
                        if($row['student_status']==11 || $row['student_status']==15)
                        {
                        	
                        }
                        else
                        {
                            
                          if (right_granted('students_promote'))
                          {
                            if($row['student_status']==13)
                            {
                        ?>
                        <li>
                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/promote_section/<?php echo $row['student_id'];?>');">
                                <i class="fas fa-users-cog"></i>
                                <?php echo get_phrase('confirm_promotion');?>
                            </a>
                        </li>
                        <?php
                            }else{
                        ?>
                            <li>
                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/promote_section/<?php echo $row['student_id'];?>');">
                                    <i class="fas fa-hospital-user"></i>
                                    <?php echo get_phrase('promote');?>
                                </a>
                            </li>
                            <!-- STUDENT chalan  LINK -->
                                        <?php
                            }
                        }

}

?>
  <li>
    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_class_conversion/<?php echo $row['student_id'];?>');">
    <i class="fas fa-hospital-user"></i>
    <?php echo get_phrase('Class Conversion');?>
    </a>
  </li>
<?php

if($row['student_status']==11){
  $stu=3;
  $qur_val=$this->db->query("select * from ".get_school_db().".student_chalan_form where student_id=".$row['student_id']." and is_cancelled = 0 and school_id=".$_SESSION['school_id']." and form_type=$stu")->result_array();


  if (right_granted('students_promote'))
    {
        ?>
        <li>
            <a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo  $row['student_id']; ?>/<?php echo $stu; ?>/2">
                <i class="fas fa-file-signature"></i>
                <?php echo get_phrase('promotion_chalan_form'); ?>
            </a>
        </li>
      <?php
      if($qur_val[0]['status']==4)
      {?>                               
        <li>
            <a href="<?php echo base_url(); ?>class_chalan_form/view_print_chalan/<?php echo  $qur_val[0]['s_c_f_id']; ?>/<?php echo $stu;  ?>">
                <i class="fas fa-print"></i>
                <?php echo get_phrase('print_Promotion_chalan'); ?>
            </a>
        </li>
        <?php
        } 
        ?>
        <?php if(count($qur_val)>0)
        { ?>
        <li>

            <a href="<?php echo base_url(); ?>c_student/cancel_promotion/<?php echo $row['student_id']; ?>">
                <i class="fas fa-ban"></i>
                <?php echo get_phrase('cancel_promotion_request'); ?>
            </a>
        </li>
        <?php } ?>
 <?php	
} 
}

---Yaha Se

elseif($row['student_status']==15){
  $stu=4;
$qur_val=$this->db->query("select s_c_f_id from ".get_school_db().".student_chalan_form where student_id=".$row['student_id']." and is_cancelled = 0 and form_type=$stu")->result_array();
 ?>
    <li>
        <a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo  $row['student_id']; ?>/<?php echo $stu; ?>">
            <i class="fas fa-file-invoice"></i>
            <?php echo get_phrase('demotion_chalan_form'); ?>
        </a>
    </li>
                                                
    <li>
        <a href="<?php echo base_url(); ?>class_chalan_form/view_print_chalan/<?php echo  $qur_val[0]['s_c_f_id']; ?>/<?php echo $stu;  ?>">
            <i class="fas fa-file-invoice"></i>
            <?php echo get_phrase('print_demotion_chalan'); ?>
        </a>
    </li>
    <?php 
    }//promote student right
//} ?>
<?php
if (right_granted('students_delete'))
    {
        ?>
        <li>
            <a href="#" onclick="confirm_modal('<?php echo base_url();?>c_student/withdraw/<?php echo $row['student_id']; ?>/<?php echo $row['section_id'];?>');">
                <i class="las la-money-bill-wave"></i>
                <?php echo get_phrase('withdraw');?>
            </a>
        </li>
    <?php
    }
    if (right_granted('students_manage'))
    {
    ?>
            <li>
                <a href="#" onclick="confirm_modal('<?php echo base_url();?>transfer_student/student_transfer_form/<?php echo $row['student_id']; ?>/<?php echo $section_id;?>');">
                    <i class="fas fa-exchange-alt"></i>
                    <?php echo get_phrase('Transfer_student');?>
                </a>
            </li>
            <?php } ?>
            <li>
                <a href="<?php echo base_url(); ?>payments/payment_listing/<?php echo  $row['student_id']; ?>/<?php echo $section_id;?>">
                    <i class="fas fa-info"></i>
                    <?php echo get_phrase('student_chalan_detail'); ?>
                </a>
            </li>
            <li>
            <a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo  $row['student_id']; ?>/<?php echo 10; ?>/2">
            <i class="far fa-file-alt"></i>
            <?php
            $qur_val2=$this->db->query("SELECT status as challan_status from ".get_school_db().".student_chalan_form 
                                                                                            WHERE school_id = ".$_SESSION['school_id']."
                                                                                                AND is_cancelled = 0
                                                                                                AND student_id=".$row['student_id']." 
                                                                                                AND form_type=10")->result_array();
         if($qur_val2[0]['status']==5)
         {
                echo get_phrase('receive_challan_form');
         }
        else
        {
            echo get_phrase('custom_challan_form');
        }    ?>
            </a>
            </li>

                <li>
                    <a href="<?php echo base_url(); ?>c_student/student_m_installment/<?php echo  $row['student_id']; ?>/<?php echo $section_id;?>">
                        <i class="las la-file-invoice-dollar"></i>
                        <?php echo get_phrase('monthly_fee_settings'); ?>
                    </a>
                </li>
                <?php
                }
                ?>
                 </ul>
            </div>
                </div>
            <?php
                }
            ?>
            */
            $sub_array = array();  
            $sub_array[] = $i++;
            $sub_array[] = '<img src="'.$img.'" class="img-circle" width="30" />';
            $sub_array[] = '<div class="myttl">'.$row->student_name.'</div>
            <div><strong>'.get_phrase('roll_no').': </strong>'.$row->roll.'</div>
            <div><strong>'. get_phrase('department').'/'.get_phrase('class').'/'.get_phrase('section').': </strong>
                                <ul class="breadcrumb breadcrumb2" style="padding:2px;">
                                    <li>'.$row->department_name.' </li>
                                    <li>'.$row->class_name.' </li>
                                    <li>'.$row->section_name.' </li>
                                </ul></div>
                            <div>
            <div><strong>'.get_phrase('mobile_no').': </strong>'.$row->mob_num.'</div>
            <div><strong>'.get_phrase('parent_name').': </strong>'.$row->p_name.'</div>
            <div><strong>'.get_phrase('parent_contact').': </strong>'.$row->contact.'</div>';
            $sub_array[] = $action;
            $data[] = $sub_array;  
       }  
       $output = array(  
            "draw"                      =>      intval($_POST["draw"]),  
            "recordsTotal"              =>      $this->Student_model->get_all_data(),  
            "recordsFiltered"           =>      $this->Student_model->get_filtered_data(),  
            "data"                      =>      $data  
       );  
      echo json_encode($output);  
  }
    
    function bulk_status_change()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');

        $apply_filter = $this->input->post('apply_filter', TRUE);

        if($apply_filter != '')
        {
            
            $std_search   = $this->input->post('std_search', TRUE);
            $std_search   = trim(str_replace(array("'", "\""), "", $std_search));
            $section_id   = $this->input->post('section_id', TRUE);
            $dept_id      = $this->input->post('dept_id', TRUE);
            $class_id     = $this->input->post('class_id', TRUE);
    
            $std_query = "";
            if (isset($std_search) && $std_search != "") {
                $std_query = " AND (
                                s.name LIKE '%" . $std_search . "%' OR 
                                s.address LIKE '%" . $std_search . "%' OR 
                                s.phone LIKE '%" . $std_search . "%' OR 
                                s.email LIKE '%" . $std_search . "%' OR 
                                s.roll LIKE '%" . $std_search . "%' OR 
                                s.form_num LIKE '%" . $std_search . "%' OR 
                                s.adm_date LIKE '%" . $std_search . "%' OR 
                                s.id_no LIKE '%" . $std_search . "%' OR 
                                s.mob_num LIKE '%" . $std_search . "%' OR 
                                s.emg_num LIKE '%" . $std_search . "%' OR 
                                s.bd_group LIKE '%" . $std_search . "%' OR 
                                s.disability LIKE '%" . $std_search . "%' OR 
                                s.reg_num LIKE '%" . $std_search . "%' OR 
                                s.system_id LIKE '%" . $std_search . "%' OR 
                                s.p_address LIKE '%" . $std_search . "%'
                                )";
            }
    
            if (!isset($section_id) || $section_id == "") {
                $section_id = $this->uri->segment(3);
            }
    
            if (!isset($section_id) || $section_id == "") {
                $section_id = 0;
            }
        
            if (!isset($dept_id) || $dept_id == "") {
                $dept_id = 0;
            }
            
            if (!isset($class_id) || $class_id == "") {
                $class_id = 0;
            }
    
            $where = "";
            if ($section_id > 0) {
                $where  .= " AND cs.section_id=" . $section_id;
            }
        
            if ($class_id > 0) {
                $where  .= " AND cs.class_id=" . $class_id; 
            }
            
            if ($dept_id > 0) {
                $where  .= " AND d.departments_id=" . $dept_id;     
            }

            $quer = "select  s.*, cs.title as section_name, cc.name as class_name, d.title as department_name
                        from " . get_school_db() . ".student s 
                        inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
                        inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id 
                        inner join " . get_school_db() . ".departments d on d.departments_id=cc.departments_id 
                        where s.school_id=" . $_SESSION['school_id'] . " " . $where . " " . $std_query . " 
    					and s.student_status in (" . student_query_status() . ") ";
    
            $students_count = $this->db->query($quer)->result_array();

            $page_data['apply_filter'] = $apply_filter;
            $page_data['section_id']   = $section_id;
            $page_data['dept_id']      = $dept_id;
            $page_data['class_id']     = $class_id;
            $page_data['std_search']   = $std_search;
            $page_data['students']     = $students_count;
            $page_data['controller']   = 's';
            $page_data['page_name']    = 'bulk_status_change';
            $page_data['page_title']   = get_phrase('student_information');
            $this->load->view('backend/index', $page_data);
            
            
        }
        else
        {
            $page_data['page_title']   = get_phrase('student_information');
            $page_data['page_name']    = 'bulk_status_change';
            $this->load->view('backend/index', $page_data);
        }
        
            
    }
    
    
    function admission_status_bulk()
    {

        $students_ids           =  $this->input->post('student_ids_class_leave');
        $data['student_status'] =  $this->input->post('student_admission_status');
        $student_ids_arr        =  explode("," , $students_ids); 
        
        $this->db->where_in("student_id", $student_ids_arr);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->update(get_school_db() . ".student", $data);
        $this->session->set_flashdata('club_updated', get_phrase('student_status_updated_successfully!.'));
        redirect(base_url() . 'c_student/bulk_status_change');
    }
    

    function student_information()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
            
        $this->get_student_information();
        // $page_data['page_name'] = 'student_information';
        // $page_data['page_title'] = get_phrase('student_information');
        // $this->load->view('backend/index', $page_data);
    }
    
    function get_student_information()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');

        $apply_filter = $this->input->post('apply_filter', TRUE);
        $std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'", "\""), "", $std_search));
        $section_id = $this->input->post('section_id', TRUE);
        $dept_id    = $this->input->post('dept_id', TRUE);
        $class_id   = $this->input->post('class_id', TRUE);
        
        
        $std_query = "";
        if (isset($std_search) && $std_search != "") {
            $std_query = " AND (
                            s.name LIKE '%" . $std_search . "%' OR 
                            s.address LIKE '%" . $std_search . "%' OR 
                            s.phone LIKE '%" . $std_search . "%' OR 
                            s.email LIKE '%" . $std_search . "%' OR 
                            s.roll LIKE '%" . $std_search . "%' OR 
                            s.form_num LIKE '%" . $std_search . "%' OR 
                            s.adm_date LIKE '%" . $std_search . "%' OR 
                            s.id_no LIKE '%" . $std_search . "%' OR 
                            s.mob_num LIKE '%" . $std_search . "%' OR 
                            s.emg_num LIKE '%" . $std_search . "%' OR 
                            s.bd_group LIKE '%" . $std_search . "%' OR 
                            s.disability LIKE '%" . $std_search . "%' OR 
                            s.reg_num LIKE '%" . $std_search . "%' OR 
                            s.system_id LIKE '%" . $std_search . "%' OR 
                            s.p_address LIKE '%" . $std_search . "%'
                            )";
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(3);
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = 0;
        }
        
        if (!isset($dept_id) || $dept_id == "") {
            $dept_id = 0;
        }
        
        if (!isset($class_id) || $class_id == "") {
            $class_id = 0;
        }

        $where = "";
        if ($section_id > 0) {
            $where  .= " AND cs.section_id=" . $section_id;
        }
        
        if ($class_id > 0) {
            $where  .= " AND cs.class_id=" . $class_id; 
        }
        
        if ($dept_id > 0) {
            $where  .= " AND d.departments_id=" . $dept_id;     
        }

         $quer = "select  s.*, cs.title as section_name, cc.name as class_name, d.title as department_name , sp.p_name ,sp.contact
                    from " . get_school_db() . ".student s 
                    inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
                    inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id 
                    inner join " . get_school_db() . ".departments d on d.departments_id=cc.departments_id
                    Left join " . get_school_db() . ".student_parent sp on s.parent_id = sp.s_p_id
                    where s.school_id=" . $_SESSION['school_id'] . " " . $where . " " . $std_query . " 
					and s.student_status in (" . student_query_status() . ") ";

        $students_count = $this->db->query($quer)->result_array();
        // print_r($students_count);

        $page_data['apply_filter'] = $apply_filter;
        $page_data['section_id']   = $section_id;
        $page_data['dept_id']      = $dept_id;
        $page_data['class_id']     = $class_id;
        $page_data['std_search']   = $std_search;
        $page_data['students']     = $students_count;
        $page_data['controller']   = 's';
        $page_data['page_name'] = 'student_information';
        $page_data['page_title'] = get_phrase('student_information');
        $this->load->view('backend/index', $page_data);
    }
    
    function view_archive_students()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');

        $apply_filter = $this->input->post('apply_filter', TRUE);
        $std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'", "\""), "", $std_search));
        $section_id = $this->input->post('section_id', TRUE);
        $dept_id    = $this->input->post('dept_id', TRUE);
        $class_id   = $this->input->post('class_id', TRUE);
        
        
        $std_query = "";
        if (isset($std_search) && $std_search != "") {
            $std_query = " AND (
                            s.name LIKE '%" . $std_search . "%' OR 
                            s.address LIKE '%" . $std_search . "%' OR 
                            s.phone LIKE '%" . $std_search . "%' OR 
                            s.email LIKE '%" . $std_search . "%' OR 
                            s.roll LIKE '%" . $std_search . "%' OR 
                            s.form_num LIKE '%" . $std_search . "%' OR 
                            s.adm_date LIKE '%" . $std_search . "%' OR 
                            s.id_no LIKE '%" . $std_search . "%' OR 
                            s.mob_num LIKE '%" . $std_search . "%' OR 
                            s.emg_num LIKE '%" . $std_search . "%' OR 
                            s.bd_group LIKE '%" . $std_search . "%' OR 
                            s.disability LIKE '%" . $std_search . "%' OR 
                            s.reg_num LIKE '%" . $std_search . "%' OR 
                            s.system_id LIKE '%" . $std_search . "%' OR 
                            s.p_address LIKE '%" . $std_search . "%'
                            )";
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(3);
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = 0;
        }
        
        if (!isset($dept_id) || $dept_id == "") {
            $dept_id = 0;
        }
        
        if (!isset($class_id) || $class_id == "") {
            $class_id = 0;
        }

        $where = "";
        if ($section_id > 0) {
            $where  .= " AND cs.section_id=" . $section_id;
        }
        
        if ($class_id > 0) {
            $where  .= " AND cs.class_id=" . $class_id; 
        }
        
        if ($dept_id > 0) {
            $where  .= " AND d.departments_id=" . $dept_id;     
        }

         $quer = "select  s.*, cs.title as section_name, cc.name as class_name, d.title as department_name , sp.p_name ,sp.contact
                    from " . get_school_db() . ".student s 
                    inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
                    inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id 
                    inner join " . get_school_db() . ".departments d on d.departments_id=cc.departments_id
                    Left join " . get_school_db() . ".student_parent sp on s.parent_id = sp.s_p_id
                    where s.school_id=" . $_SESSION['school_id'] . " " . $where . " " . $std_query . " 
					and s.student_status not in (" . student_query_status() . ") ";
        $students_count = $this->db->query($quer)->result_array();
        //  echo "<pre>"; print_r($students_count);
      
        $page_data['apply_filter'] = $apply_filter;
        $page_data['section_id']   = $section_id;
        $page_data['dept_id']      = $dept_id;
        $page_data['class_id']     = $class_id;
        $page_data['std_search']   = $std_search;
        $page_data['students']     = $students_count;
        $page_data['controller']   = 's';
        $page_data['page_name'] = 'view_archive_students';
        $page_data['page_title'] = get_phrase('view_archive_students');
        $this->load->view('backend/index', $page_data);
    }


    function student_add()
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url());
        $page_data['page_name'] = 'student_add';
        $page_data['page_title'] = get_phrase('add_student');
        $this->load->view('backend/index', $page_data);
    }
    
    function save_update_student_details(){

            $data = array();
            $student_id          = $this->input->post('student_id');
            $data['name']        = $this->input->post('name');
            // $data['location_id'] = $this->input->post('location');
            $data['birthday']    = date_slash($this->input->post('birthday'));
            $data['gender']      = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['p_address']   = $this->input->post('p_address');
            $data['student_category_id'] = $this->input->post('student_category');
            // $data['id_no']       = $this->input->post('form_b');
            // $data['id_type']     = $this->input->post('student_id_type');
            $data['nationality'] = $this->input->post('nationality');
            $data['email']       = $this->input->post('email');
            $data['religion']    = $this->input->post('religion');
            $data['phone']       = $this->input->post('phone');
            $data['emg_num']     = $this->input->post('emg_num');
            $data['bd_group']    = $this->input->post('bd_group');
            $data['disability']  = $this->input->post('disability');
            $data['mob_num']     = $this->input->post('mob_num');
            $data['previou_school'] = $this->input->post('prev_school');
            $data['std_activities'] = $this->input->post('activities');

            // $image_file = $this->input->post('image_file');
            // $user_file = $_FILES['image']['name'];

            // if ($user_file != "") {
            //     if ($image_file != "") {
            //         $del_location = "uploads/student_image/$image_file";
            //         file_delete($del_location);
            //     }
            //     $data['image'] = file_upload_fun('image', 'student', 'profile');
            // }

            // $form_file = $_FILES['form_b_file']['name'];
            // $id_file_old = $this->input->post('id_file_old');

            // if ($form_file != "") {
            //     if ($id_file_old != "") {
            //         $del_location1 = system_path($id_file_old, 'student', $is_root = 0);
            //         file_delete($del_location1);
            //     }
            //     $data['id_file'] = file_upload_fun('form_b_file', 'student', 'id_no');
            // }

            $this->db->where('student_id', $student_id);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->update(get_school_db() . '.student', $data);

            // $data_father = array();
            // $data_mother = array();
            // $data_guardian = array();
            
            // $father_id     =  $this->input->post('f_id');
            // $mother_id     =  $this->input->post('m_id');
            // $guardian_id   =  $this->input->post('g_id');
            
            // father
            // if($father_id != '' && $father_id > 0){
                
            //     $data_father['nationality'] = $this->input->post('nationality_f');
            //     $data_father['p_name']      = $this->input->post('f_name');
                
            //     $f_id_file = $_FILES['f_cnic_attach']['name'];
    
            //     if ($f_id_file != "") {
            //         $data_father['id_file'] = file_upload_fun('f_cnic_attach', 'student', 'father');
            //     }
                
            //     $data_father['id_type']     = $this->input->post('id_type_f');
            //     $data_father['id_no']       = $this->input->post('f_cnic');
            //     $data_father['contact']     = $this->input->post('f_num');
            //     $data_father['occupation']  = $this->input->post('f_ocu');
            //     $data_father['school_id']   = $_SESSION['school_id'];
                
            //     $this->db->where('s_p_id', $father_id);
            //     $this->db->where('school_id', $_SESSION['school_id']);
            //     $this->db->update(get_school_db() . '.student_parent', $data_father);
                
            // }

            // mother
            // if($mother_id != '' && $mother_id > 0){
                
            //     $data_mother['nationality'] = $this->input->post('nationality_m');
            //     $data_mother['p_name']      = $this->input->post('m_name');
                
            //     $m_id_file = $_FILES['m_cnic_attach']['name'];
    
            //     if ($m_id_file != "") {
            //         $data_mother['id_file'] = file_upload_fun('m_cnic_attach', 'student', 'mother');
            //     }
                
            //     $data_mother['id_type']     = $this->input->post('id_type_m');
            //     $data_mother['id_no']       = $this->input->post('m_cnic');
            //     $data_mother['contact']     = $this->input->post('m_num');
            //     $data_mother['occupation']  = $this->input->post('m_ocu');
            //     $data_mother['school_id']   = $_SESSION['school_id'];
                
            //     $this->db->where('s_p_id', $mother_id);
            //     $this->db->where('school_id', $_SESSION['school_id']);
            //     $this->db->update(get_school_db() . '.student_parent', $data_mother);
                
            // }

            // guardian
            // if($guardian_id != '' && $guardian_id > 0){
              
            //     $data_guardian['nationality'] = $this->input->post('nationality_g');
            //     $data_guardian['p_name']      = $this->input->post('g_name');
                
            //     $g_id_file = $_FILES['g_cnic_attach']['name'];
    
            //     if ($g_id_file != "") {
            //         $data_guardian['id_file'] = file_upload_fun('g_cnic_attach', 'student', 'guardian');
            //     }
                
            //     $data_guardian['id_type']     = $this->input->post('id_type_g');
            //     $data_guardian['id_no']       = $this->input->post('g_cnic');
            //     $data_guardian['contact']     = $this->input->post('g_num');
            //     $data_guardian['occupation']  = $this->input->post('g_ocu');
            //     $data_guardian['school_id']   = $_SESSION['school_id'];
                
            //     $this->db->where('s_p_id', $guardian_id);
            //     $this->db->where('school_id', $_SESSION['school_id']);
            //     $this->db->update(get_school_db() . '.student_parent', $data_guardian);
   
            // }

            $this->session->set_flashdata('club_updated', get_phrase('student_profile_updated_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
    
    }
    
    function update_student_detail(){

        $student_id = str_decode($this->uri->segment(4));
        $page_data['rows'] = $this->db->query("SELECT s.*,s.email as student_email,sr.*,cs.*, c.name as class_name, sc.title as category_title
            FROM " . get_school_db() . ".student s
            left JOIN " . get_school_db() . ".student_relation sr 
            ON s.student_id = sr.student_id
            INNER JOIN " . get_school_db() . ".class_section cs
            ON cs.section_id=s.section_id
            INNER JOIN " . get_school_db() . ".class c 
            ON c.class_id=cs.class_id
            Left JOIN " . get_school_db() . ".student_category sc
            ON s.student_category_id=sc.student_category_id
            WHERE s.school_id=" . $_SESSION['school_id'] . " AND s.student_id =$student_id GROUP BY s.student_id ")->result_array();

        $page_data['controller'] = $this->uri->segment(3);
        $page_data['page_name'] = 'update_student_detail';
        $page_data['section_id'] = str_decode($this->uri->segment(5));
        $page_data['student_id'] = $student_id;
        $page_data['student_status'] = $this->uri->segment(6);
        $page_data['page_title'] = get_phrase('update_student_detail');
        
        
        $this->load->view('backend/index', $page_data);
    }

    function student_detail()
    {
        $student_id = str_decode($this->uri->segment(4));

        $page_data['rows'] = $this->db->query("SELECT s.*,s.email as student_email,sr.*,cs.*, c.name as class_name, sc.title as category_title
            FROM " . get_school_db() . ".student s
            left JOIN " . get_school_db() . ".student_relation sr 
            ON s.student_id = sr.student_id
            INNER JOIN " . get_school_db() . ".class_section cs
            ON cs.section_id=s.section_id
            INNER JOIN " . get_school_db() . ".class c 
            ON c.class_id=cs.class_id
            LEFT JOIN " . get_school_db() . ".student_category sc
            ON s.student_category_id=sc.student_category_id
            WHERE s.school_id=" . $_SESSION['school_id'] . " AND s.student_id =$student_id ")->result_array();

        $page_data['controller'] = $this->uri->segment(3);
        $page_data['page_name'] = 'student_detail';
        $page_data['section_id'] = str_decode($this->uri->segment(5));
        $page_data['page_title'] = get_phrase('add_student');
        $this->load->view('backend/index', $page_data);
    }

    function student($param1 = '', $param2 = '', $param3 = '')
    {
        
        if ($_SESSION['user_login'] != 1)
            redirect('login');

        if ($param1 == 'create') {
            $this->db->trans_begin();
            
            $acad_year_id = $this->input->post('academic_year_id');
            $roll = $this->input->post('roll');
            if(empty($acad_year_id) || $acad_year_id == "")
            {
                $acad_year_id = 0;    
            }
            
            if(empty($roll) || $roll == "")
            {
                $roll = 0;    
            }
            $data['name'] = $this->input->post('name');
            $data['gr_no'] = $this->input->post('gr_number');
            $data['birthday'] = date_slash($this->input->post('birthday'));
            $data['gender'] = $this->input->post('sex');
            $data['address'] = $this->input->post('address');
            $data['academic_year_id'] = $acad_year_id;
            $data['phone'] = $this->input->post('phone');
            $data['location_id'] = $this->input->post('location');
            $data['email'] = $this->input->post('email');
            $data['student_status'] = 1;
            $data['section_id'] = $this->input->post('section_id');
            $data['roll'] = $roll;
            $data['form_num'] = $this->input->post('form_number');
            $data['previou_school'] = $this->input->post('prev_school');
            $data['std_activities'] = $this->input->post('activities');
            $data['p_address'] = $this->input->post('p_address');
            $data['adm_date'] = date_slash($this->input->post('adm_date'));
            $data['religion'] = $this->input->post('religion');
            $data['nationality'] = $this->input->post('nationality');
            $data['student_category_id'] = $this->input->post('student_category');
            
            $data['id_type'] = $this->input->post('student_id_type');
            $data['mob_num'] = $this->input->post('mob_num');
            $data['emg_num'] = $this->input->post('emg_num');
            $data['bd_group'] = $this->input->post('bd_group');
            $data['disability'] = $this->input->post('disability');
            $data['id_file'] = file_upload_fun('id_file', 'student', 'id_no');
            $data['image'] = file_upload_fun('image', 'student', 'profile');
            $data['school_id'] = $_SESSION['school_id'];
            $data['date_added'] = date("Y-m-d h:i:sa");
            $data['added_by'] = $_SESSION['login_detail_id'];
            
            
            if($data['id_type'] == "4"){
                $data['id_no'] =system_generated_form_id();
            }
            else{
                $data['id_no'] = $this->input->post('form_b');
            }
            
            $this->db->insert(get_school_db() . '.student', $data);

            $student_id = $this->db->insert_id();
            $scl_id = $_SESSION['sys_sch_id'];
            $bar_code_type = 112;
            $school_id = sprintf("%'06d", $scl_id);
            $std_id = sprintf("%'07d", $student_id);
            $system_id = $bar_code_type . '' . $school_id . '' . $std_id;
            $path = 'uploads/' . $_SESSION['folder_name'] . '/student';

            $bar_cod['barcode_image'] = $this->set_barcode($system_id, $path);
            $bar_cod['system_id'] = $system_id;

            $this->db->where('student_id', $student_id);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->update(get_school_db() . '.student', $bar_cod);

            $data['image'] = file_upload_fun('image', 'student', 'profile');
            $fcnic = $this->input->post('f_cnic');
            $dataf['id_no'] = $fcnic;

            $query_f = $this->db->query("select * from " . get_school_db() . ".student_parent where school_id=" . $_SESSION['school_id'] . " AND id_no='" . $fcnic . "'")->result_array();
            if (count($query_f) > 0) {
                $data_f['s_p_id'] = $query_f[0]['s_p_id'];
            } else {
                $dataf['nationality'] = $this->input->post('nationality_f');
                $dataf['p_name'] = $this->input->post('f_name');
                $dataf['id_file'] = file_upload_fun('f_cnic_attach', 'student', 'father');
                $dataf['id_type'] = $this->input->post('id_type_f');
                $dataf['contact'] = $this->input->post('f_num');
                $dataf['occupation'] = $this->input->post('f_ocu');
                $dataf['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db() . '.student_parent', $dataf);
                $data_f['s_p_id'] = $this->db->insert_id();
            }

            $data_f['student_id'] = $student_id;
            $data_f['relation'] = 'f';
            $data_f['school_id'] = $_SESSION['school_id'];
            $this->db->insert(get_school_db() . '.student_relation', $data_f);

            $datam['id_no'] = $this->input->post('m_cnic');
            if ($datam['id_no'] != "") {
                $query_m = $this->db->query("select * from " . get_school_db() . ".student_parent where school_id=" . $_SESSION['school_id'] . " AND id_no='" . $datam['id_no'] . "'")->result_array();
                if (count($query_m) > 0) {
                    $data_m['s_p_id'] = $query_m[0]['s_p_id'];
                } else {
                    $datam['p_name'] = $this->input->post('m_name');
                    $datam['id_file'] = file_upload_fun('m_cnic_attach', 'student', 'mother');
                    $datam['id_type'] = $this->input->post('id_type_m');
                    $datam['contact'] = $this->input->post('m_num');
                    $datam['occupation'] = $this->input->post('m_ocu');
                    $datam['nationality'] = $this->input->post('nationality_m');
                    $datam['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db() . '.student_parent', $datam);
                    $data_m['s_p_id'] = $this->db->insert_id();
                }

                $data_m['student_id'] = $student_id;
                $data_m['relation'] = 'm';
                $data_m['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db() . '.student_relation', $data_m);

            }

            // Guardian //
            $datag['id_no'] = $this->input->post('g_cnic');
            if ($datag['id_no'] != "") {
                $query_g = $this->db->query("SELECT * FROM " . get_school_db() . ".student_parent WHERE school_id=" . $_SESSION['school_id'] . " AND id_no='" . $datag['id_no'] . "'")->result_array();
                if (count($query_g) > 0) {
                    $data_g['s_p_id'] = $query_g[0]['s_p_id'];
                } else {
                    $datag['p_name'] = $this->input->post('g_name');
                    $datag['id_file'] = file_upload_fun('g_cnic_attach', 'student', 'guardian');
                    $datag['id_type'] = $this->input->post('id_type_g');
                    $datag['contact'] = $this->input->post('g_num');
                    $datag['occupation'] = $this->input->post('g_ocu');
                    $datag['nationality'] = $this->input->post('nationality_g');
                    $datag['school_id'] = $_SESSION['school_id'];
                    $this->db->insert(get_school_db() . '.student_parent', $datag);
                    $data_g['s_p_id'] = $this->db->insert_id();
                }
                $data_g['student_id'] = $student_id;
                $data_g['relation'] = 'g';
                $data_g['school_id'] = $_SESSION['school_id'];
                $this->db->insert(get_school_db() . '.student_relation', $data_g);
            }
            $updated_by = $_SESSION['login_detail_id'];
            student_archive($updated_by, $student_id);
            
            $assign_group['user_group_id']  =  $this->input->post('user_group_id');
            $assign_group['school_id']      =  $_SESSION['school_id'];
            $assign_group['student_id']     =   $student_id;
            $this->db->insert(get_school_db() . '.user_rights', $assign_group);
            
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            
            redirect(base_url() . 'c_student/student_view');
        }
        if ($param2 == 'do_update') {   
            $this->db->trans_begin();

            $student_id = $this->uri->segment(5);
            $data['name'] = $this->input->post('name');
            $data['location_id'] = $this->input->post('location');
            $data['birthday'] = date_slash($this->input->post('birthday'));

            $data['gender'] = $this->input->post('sex');
            $data['address'] = $this->input->post('address');
            $data['p_address'] = $this->input->post('p_address');
            $data['student_category_id'] = $this->input->post('student_category_edit');
            $data['id_no'] = $this->input->post('form_b');
            $data['id_type'] = $this->input->post('id_type');
            $data['nationality'] = $this->input->post('nationality');
            $data['email'] = $this->input->post('email');
            $data['roll'] = $this->input->post('roll');
            $data['adm_date'] = date_slash($this->input->post('adm_date'));
            $data['religion'] = $this->input->post('religion');
            $data['phone'] = $this->input->post('phone');
            $data['emg_num'] = $this->input->post('emg_num');
            $data['bd_group'] = $this->input->post('bd_group');
            $data['disability'] = $this->input->post('disability');
            $data['mob_num'] = $this->input->post('mobile_num');
            $image_file = $this->input->post('image_file');
            $user_file = $_FILES['userfile']['name'];
            if ($user_file != "") {
                if ($image_file != "") {
                    $del_location = "uploads/student_image/$image_file";
                    file_delete($del_location);
                }
                $data['image'] = file_upload_fun('userfile', 'student', 'profile');
            }
            $form_file = $_FILES['form_b_file']['name'];
            $id_file_old = $this->input->post('id_file_old');
            if ($form_file != "") {
                if ($id_file_old != "") {
                    $del_location1 = system_path($id_file_old, 'student', $is_root = 0);
                    file_delete($del_location1);
                }
                $data['id_file'] = file_upload_fun('form_b_file', 'student', 'id_no');
            }
            $bar_code_type = 112;
            $scl_id = $_SESSION['sys_sch_id'];
            $school_id = sprintf("%'06d", $scl_id);
            $std_id = sprintf("%'07d", $student_id);
            $system_id = $bar_code_type . '' . $school_id . '' . $std_id;
            $path = 'uploads/' . $_SESSION['folder_name'] . '/student';
            $data['barcode_image'] = $this->set_barcode($system_id, $path);
            $data['system_id'] = $system_id;
            $this->db->where('student_id', $student_id);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->update(get_school_db() . '.student', $data);
            $this->crud_model->clear_cache();
            $updated_by = $_SESSION['login_detail_id'];
            student_archive($updated_by, $student_id);
            
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
            } else {
                $this->db->trans_commit();
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        if ($param2 == 'do_update_c') {
            
            $this->db->trans_begin();

            $section_id = $this->input->post('section_id');

            $student_id = $this->uri->segment(5);
            $data['name'] = $this->input->post('name');
            $data['location_id'] = $this->input->post('location');
            $data['form_num'] = $this->input->post('form_num');
            $data['section_id'] = $section_id;
            $data['birthday'] = date_slash($this->input->post('birthday'));
            $data['gender'] = $this->input->post('sex');
            $data['address'] = $this->input->post('address');
            $data['p_address'] = $this->input->post('p_address');
            $data['student_category_id'] = $this->input->post('student_category_edit');

            $data['phone'] = $this->input->post('phone');
            $data['email'] = $this->input->post('email');
            $data['adm_date'] = date_slash($this->input->post('adm_date'));
            $data['religion'] = $this->input->post('religion');
            $data['id_no'] = $this->input->post('form_b');
            $data['id_type'] = $this->input->post('id_type');
            $data['phone'] = $this->input->post('phone');
            $data['emg_num'] = $this->input->post('emg_num');
            $data['bd_group'] = $this->input->post('bd_group');
            $data['disability'] = $this->input->post('disability');
            $data['mob_num'] = $this->input->post('mobile_num');

            $image_file = $this->input->post('image_file');
            $user_file = $_FILES['userfile']['name'];
            $form_file = $_FILES['form_b_file']['name'];
            $id_file_old = $this->input->post('id_file_old');

            if ($user_file != "") {
                if ($image_file != "") {

                    $del_location = system_path($image_file, 'student', $is_root = 0);
                    file_delete($del_location);
                }
                $data['image'] = file_upload_fun('userfile', 'student', 'profile');
            }
            if ($form_file != "") {
                if ($id_file_old != "") {
                    $del_location1 = system_path($id_file_old, 'student', $is_root = 0);
                    file_delete($del_location1);
                }
                $data['id_file'] = file_upload_fun('form_b_file', 'student', 'id_no');
            }

            $bar_code_type = 112;
            $scl_id = $_SESSION['sys_sch_id'];
            $school_id = sprintf("%'06d", $scl_id);
            $std_id = sprintf("%'07d", $student_id);
            $system_id = $bar_code_type . '' . $school_id . '' . $std_id;
            $path = 'uploads/' . $_SESSION['folder_name'] . '/student';
            $data['barcode_image'] = $this->set_barcode($system_id, $path);
            $data['system_id'] = $system_id;
            $this->db->where('student_id', $student_id);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->update(get_school_db() . '.student', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
            } else {
                $this->db->trans_commit();
            }
            
            redirect($_SERVER['HTTP_REFERER']);
            exit;
        }

        if ($param2 == 'delete') {
            
            $this->db->trans_begin();

            $image_name = $this->uri->segment(6);
            $qur_rec = $this->db->query("select * from " . get_school_db() . ".student_relation sr inner join " . get_school_db() . ".student_parent sp on sp.s_p_id=sr.s_p_id where sp.school_id=" . $_SESSION['school_id'] . " AND student_id=$param3")->result_array();
            foreach ($qur_rec as $std_rec) {
                $sp_id = $std_rec['s_p_id'];
                $qur_r = $this->db->query("select * from " . get_school_db() . ".student_relation where school_id=" . $_SESSION['school_id'] . " AND  student_id!=$param3 and s_p_id=$sp_id")->result_array();
                if (count($qur_r) > 0) {
                } else {
                    $path_att = "uploads/student_image/" . $std_rec['attachment'];
                    if ($std_rec['attachment'] != "" && file_exists($path_att)) {
                        file_delete($path_att);
                    }
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->where('s_p_id', $sp_id);
                    $this->db->delete(get_school_db() . '.student_parent');
                }

            }
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->where('student_id', $param3);
            $this->db->delete(get_school_db() . '.student_relation');

            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->where('student_id', $param3);
            $this->db->delete(get_school_db() . '.student');

            $path_std = "uploads/student_image/$image_name";
            if ($image_name != "" && file_exists($path_std)) {
                file_delete($path_std);
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            redirect(base_url() . 'c_student/student_information/' . $param1);
        }
    }

    function set_barcode($code, $path)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcode = new Zend_Barcode();
        $file = $barcode->draw('Code128',
            'image',
            array('text' => $code, 'barHeight' => 20, 'drawText' => TRUE, 'withQuietZones' => FALSE, 'orientation' => 0),
            array());
        $file_name = $code . '.png';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $store_image = imagepng($file, $path . '/' . $file_name);
        return $file_name;
    }

    function get_class()
    {
        echo class_option_list($this->input->post('dep_id'), $this->input->post('sel'));
    }

    function get_section()
    {
        echo section_option_list($this->input->post('class_id'), $this->input->post('sel'));
    }

    function promotion_section($student_id="")
    {
        
        $this->db->trans_begin();
 
        $data['student_status'] = $this->input->post('student_status');
        if ($data['student_status'] == '14') {

            $data['section_id'] = $this->input->post('section_id');
            $data['academic_year_id'] = $this->input->post('academic_year_id');
            $data['pro_section_id'] = 0;
            $data['pro_academic_year_id'] = 0;
            $data['is_installment'] = 0;
        } else {
            $data['pro_section_id'] = $this->input->post('section_id');
            $data['pro_academic_year_id'] = $this->input->post('academic_year_id');
        }

        $data['roll'] = $this->input->post('roll');
        $this->db->where("student_id", $student_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->update(get_school_db() . ".student", $data);
        student_archive($_SESSION['login_detail_id'], $student_id);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }
        redirect(base_url() . 'c_student/student_information/');
    }
    function class_conversion($student_id="")
    {
        
        $this->db->trans_begin();
 
        $data['section_id'] = $this->input->post('section_id');
       

      
        $this->db->where("student_id", $student_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->update(get_school_db() . ".student", $data);
        student_archive($_SESSION['login_detail_id'], $student_id);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }
        redirect(base_url() . 'c_student/student_information/');
    }
    // Admission Status Changes (Zeeshan Arain)
    function admission_status($student_id)
    {
        $data['student_status'] = $this->input->post('student_admission_status');
        
        $this->db->where("student_id", $student_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->update(get_school_db() . ".student", $data);
        student_archive($_SESSION['login_detail_id'], $student_id);
        $this->session->set_flashdata('club_updated', get_phrase('admission_status_updated_successfully!.'));
        redirect(base_url() . 'c_student/student_information/');
    }

    function get_student_info()
    {
        $data['section_id'] = $this->input->post('section_id');
        $this->load->view("backend/admin/ajax/student_information", $data);
    }

    function get_location()
    {
        $send_location = $this->input->post('send_location');
        if ($send_location == "provience") {
            echo province_option_list($this->input->post('loc_id'), $this->input->post('selected'));
        } elseif ($send_location == "city") {
            echo city_option_list($this->input->post('loc_id'), $this->input->post('selected'));
        } elseif ($send_location == "location") {
            echo location_option_list($this->input->post('loc_id'), $this->input->post('selected'));
        }
    }

    function img_test()
    {
        $path = 'uploads/' . $_SESSION['folder_name'] . '/student';
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcode = new Zend_Barcode();
        $file = $barcode->draw('Code128', 'image', array('text' => $code, 'barHeight' => 20, 'drawText' => TRUE, 'withQuietZones' => FALSE, 'orientation' => 0), array());
        $store_image = imagepng($file, "$path" . '/' . "yes_yes.png");
        return $code . '.png';

    }

    function student_pending()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        
        $apply_filter = $this->input->post('apply_filter', TRUE);
        $std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'", "\""), "", $std_search));
        $section_id = $this->input->post('section_id', TRUE);
        $quer = "select s.*, scf.s_c_f_id,scf.form_type ,scf.status, cs.title as section_name, cc.name as class_name, d.title as department_name ,
        sp.p_name , sp.contact
        from " . get_school_db() . ".student s 
        inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
        inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id 
        inner join " . get_school_db() . ".departments d on d.departments_id=cc.departments_id 
        Left join " . get_school_db() . ".student_parent sp on s.parent_id = sp.s_p_id
        left join " . get_school_db() . ".student_chalan_form scf on (s.student_id=scf.student_id and scf.form_type = '$frm_tp' and scf.is_processed=0)
        left join " . get_school_db() . ".acadmic_year ay on ay.academic_year_id=s.academic_year_id 
        where  s.school_id=" . $_SESSION['school_id'] . " AND s.student_status < 10 ORDER BY s.student_id DESC";
        
        $students_count = $this->db->query($quer)->result_array();
        $page_data['students']  = $students_count;
        $page_data['page_name'] = 'student_information_pending';
        $page_data['page_title'] = get_phrase('student_information');
        $page_data['controller'] = 'c';
        $this->load->view('backend/index', $page_data);
    }
    
    function get_student_pending_report()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        $apply_filter = $this->input->post('apply_filter', TRUE);
        $std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'", "\""), "", $std_search));
        $section_id = $this->input->post('section_id', TRUE);
        $dept_id    = $this->input->post('dept_id', TRUE);
        $class_id   = $this->input->post('class_id', TRUE);
        $std_query = "";
        
        if (isset($std_search) && $std_search != "") {
            $std_query = " AND (
                            s.name LIKE '%" . $std_search . "%' OR 
                            s.address LIKE '%" . $std_search . "%' OR 
                            s.phone LIKE '%" . $std_search . "%' OR 
                            s.email LIKE '%" . $std_search . "%' OR 
                            s.roll LIKE '%" . $std_search . "%' OR 
                            s.form_num LIKE '%" . $std_search . "%' OR 
                            s.adm_date LIKE '%" . $std_search . "%' OR 
                            s.id_no LIKE '%" . $std_search . "%' OR 
                            s.mob_num LIKE '%" . $std_search . "%' OR 
                            s.emg_num LIKE '%" . $std_search . "%' OR 
                            s.bd_group LIKE '%" . $std_search . "%' OR 
                            s.disability LIKE '%" . $std_search . "%' OR 
                            s.reg_num LIKE '%" . $std_search . "%' OR 
                            s.system_id LIKE '%" . $std_search . "%' OR 
                            s.p_address LIKE '%" . $std_search . "%'
                            )";
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(3);
        }

        if (!isset($section_id) || $section_id == "") {
           $section_id = 0;
        }
        
        if (!isset($dept_id) || $dept_id == "") {
            $dept_id = 0;
        }
        
        if (!isset($class_id) || $class_id == "") {
            $class_id = 0;
        }

        $where_filter = "";
        if ($section_id > 0) {
            $where_filter .= " AND cs.section_id=" . $section_id;
        }
        
        if ($class_id > 0) {
            $where_filter .= " AND cs.class_id=" . $class_id;
        }
        
        if ($dept_id > 0) {
            $where_filter .= " AND d.departments_id=" . $dept_id;
        }

        $frm_tp = 1;
        $quer = "select s.*, scf.s_c_f_id,scf.form_type ,scf.status, cs.title as section_name, cc.name as class_name, d.title as department_name
        from " . get_school_db() . ".student s 
        inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
        inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id 
        inner join " . get_school_db() . ".departments d on d.departments_id=cc.departments_id 
        left join " . get_school_db() . ".student_chalan_form scf on (s.student_id=scf.student_id and scf.form_type=$frm_tp and scf.is_processed=0)
        left join " . get_school_db() . ".acadmic_year ay on ay.academic_year_id=s.academic_year_id 
        where  s.school_id=" . $_SESSION['school_id'] . " AND s.student_status < 10 " . $where_filter . " " . $std_query . " ORDER BY s.student_id DESC ";

        $students_count = $this->db->query($quer)->result_array();
       
        $page_data['apply_filter']  = $apply_filter;
        $page_data['section_id']    = $section_id;
        $page_data['dept_id']       = $dept_id;
        $page_data['class_id']      = $class_id;
        $page_data['std_search']    = $std_search;
        $page_data['students']      = $students_count;
        $page_data['controller']    = 's';
        $page_data['page_name'] = 'student_information_pending';
        $page_data['page_title'] = get_phrase('student_information');
        $page_data['controller'] = 'c';
        $this->load->view('backend/index', $page_data);

    }
    
    function student_view()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        $page_data['success'] = 'success';
        $page_data['page_name'] = 'student_view';
        $page_data['page_title'] = get_phrase('student_view');
        $this->load->view('backend/index', $page_data);
    }

    function get_student_pending()
    {

        $data['section_id'] = $this->input->post('section_id');
        $data['student_status'] = $this->input->post('student_status');
        $data['controller'] = 'c';
        $this->load->view("backend/admin/ajax/student_pending", $data);

    }

    function create_card($condition="", $section_id="", $student_id="")
    {
        if ($condition == 'student') {
            $page_data['student_id'] = $this->uri->segment(4);
            $page_data['section_id'] = $this->uri->segment(5);
            $page_data['section'] = $this->uri->segment(5);
        } elseif ($condition == 'section') {
            $page_data['section_id'] = $this->uri->segment(4);
            $page_data['section'] = $this->uri->segment(4);
        } else {

        }
        $page_data['page_name'] = 'create_card';
        $page_data['page_title'] = get_phrase('create_card');
        $this->load->view('backend/index', $page_data);

    }

    function get_year_term()
    {
        $acad_year = $this->input->post('acad_year');
        $term_id = $this->input->post('term_id');
        echo $yearly_term = yearly_terms_option_list($acad_year, $term_id, 1);
    }

    function promotion_class($section_id)
    {
        $school_id = $_SESSION['school_id'];
        $data['pro_section_id'] = $this->input->post('section_id');
        $data['pro_academic_year_id'] = $this->input->post('academic_year_id');
        $data['student_status'] = 11;
        $this->db->where("section_id", $section_id);
        $this->db->update(get_school_db() . ".student", $data);
        $qur_data = $this->db->query("select student_id from " . get_school_db() . ".student where section_id=$section_id and school_id=$school_id")->result_array();
        foreach ($qur_data as $row) {
            student_archive($_SESSION['login_detail_id'], $row['student_id']);
        }
        //redirect(base_url().'c_student/student_information/');
    }

    function cancel_chalan_request($student_id="")
    {
        
        $this->db->trans_begin();

        $status = 0;
        $bulk_req_id = 0;
        $s_c_f_id = 0;
        $status_row = $this->db->query("SELECT s_c_f_id , status , bulk_req_id  FROM " . get_school_db() . ".student_chalan_form 
                                        where student_id=" . $student_id . "  and is_cancelled = 0 and school_id = " . $_SESSION['school_id'] . "")->row();
        if (count($status_row) > 0) {
            $status = $status_row->status;
            $bulk_req_id = $status_row->bulk_req_id;
        }

        if ($status < 4) {
            $s_c_f_id = $status_row->s_c_f_id;
            $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_detail  where s_c_f_id=" . $s_c_f_id . "  AND  school_id=" . $_SESSION['school_id'] . "");
            $this->db->query("DELETE FROM " . get_school_db() . ".student_chalan_form  	where s_c_f_id=$s_c_f_id AND school_id=" . $_SESSION['school_id'] . "");
												 
            $this->session->set_flashdata('candidate_delete_4', get_phrase('candidate_deleted_successfully!.'));
            //redirect(base_url() . 'c_student/student_pending/');
            /* journal entry end */
            /* $school_id=$_SESSION['school_id'];
             $this->db->query("DELETE FROM ".get_school_db().".student_chalan_detail where s_c_f_id=(select s_c_f_id from ".get_school_db().".student_chalan_form where student_id=$student_id and school_id=$school_id and status<5 and is_bulk=0 ) and school_id=$school_id");
             $this->db->query("DELETE FROM ".get_school_db().".student_chalan_form where student_id=$student_id and school_id=$school_id and status<5 and is_bulk=0");
             $data['student_status']=1;
             $this->db->where('student_id',$student_id);
             $this->db->where('school_id',$_SESSION['school_id']);
             $this->db->update(get_school_db().'.student',$data);
             student_archive($_SESSION['login_detail_id'],$student_id);
             redirect($_SERVER['HTTP_REFERER']);
              */
        } else {
            /* journal entry start */
            $s_c_f_id = $status_row->s_c_f_id;
            $challan_form_id = $this->db->query("SELECT * FROM " . get_school_db() . ".student_chalan_form 
                                            where student_id=" . $student_id . " 
                                            and school_id = " . $_SESSION['school_id'] . " 
                                            and is_cancelled = 0")->row();
            $s_c_f_id = $challan_form_id->s_c_f_id;
            $str = "SELECT scd.fee_type_title , scd.amount , scd.school_id , scd.cancel_dr_coa_id ,scd.cancel_cr_coa_id
                                                        FROM " . get_school_db() . ".student_chalan_form as scf
                                                        INNER JOIN " . get_school_db() . ".student_chalan_detail as scd
                                                        ON scf.s_c_f_id = scd.s_c_f_id WHERE scd.s_c_f_id = " . $s_c_f_id . "
                                                        AND scd.school_id = " . $_SESSION['school_id'] . "
                                                        AND scf.is_cancelled = 0
                                                        AND (scd.cancel_dr_coa_id = 0 OR scd.cancel_cr_coa_id = 0)";
            $query = $this->db->query($str);

            if (count($query->result()) > 0) {
                $this->session->set_flashdata('journal_entry', get_phrase('challan_form_not_issued_incomplete_chart_of_account_settings.'));
            } else {

                $date = new DateTime();
                $entry_date = $date->format('Y-m-d H:i:s');
                $str1 = "SELECT scd.fee_type_title , scf.student_id, scd.amount , scd.school_id, scf.chalan_form_number,
                         scd.cancel_dr_coa_id ,scd.cancel_cr_coa_id , scf.student_name , scd.type FROM
                         " . get_school_db() . ".student_chalan_form as scf INNER JOIN " . get_school_db() . ".student_chalan_detail as scd
                         ON scf.s_c_f_id = scd.s_c_f_id WHERE scd.s_c_f_id = " . $s_c_f_id . " AND scf.is_cancelled = 0 AND scd.school_id = " . $_SESSION['school_id'] . "";
                $query1 = $this->db->query($str1);

                foreach ($query1->result() as $row) {
                    if ($row->amount > 0) {
                        $transaction_detail = student_name_section($row->student_id);

                        $journal_entry = 0;
                        if ($row->type == 2 || $row->type == 4) {
                            $journal_entry = 1;
                            $amount = $row->amount;
                        } elseif ($row->type == 1 || $row->type == 5) {
                            $journal_entry = 1;
                            $amount = $row->amount;//(-1) * ($row->amount);
                        } else if ($row->type == 3 || $row->type == 6) {
                            $journal_entry = 0;

                        }

                        if ($journal_entry == 1) {

                            $data_debit = array(
                                'entry_date' => $entry_date,
                                'detail' => $row->fee_type_title
                                    . ' - ' . get_phrase('cancelled_challan_form') . " - " . $row->chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                'debit' => $amount,
                                'entry_type' => 1,
                                'type_id' => $s_c_f_id,
                                'school_id' => $row->school_id,
                                'coa_id' => $row->cancel_dr_coa_id
                            );
                            $data_credit = array(
                                'entry_date' => $entry_date,
                                'detail' => $row->fee_type_title
                                    . ' - ' . get_phrase('cancelled_challan_form') . " - " . $row->chalan_form_number . " - " . get_phrase('from') . " - " . $transaction_detail,
                                'credit' => $amount,
                                'entry_type' => 1,
                                'type_id' => $s_c_f_id,
                                'school_id' => $row->school_id,
                                'coa_id' => $row->cancel_cr_coa_id
                            );

                            $this->db->insert(get_school_db() . ".journal_entry", $data_debit);
                            $this->db->insert(get_school_db() . ".journal_entry", $data_credit);
                        }

                    }
                }
                $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form
                                    SET is_cancelled = 1,
                                    cancelled_by = " . $_SESSION['login_detail_id'] . ",
                                    cancel_date = '" . $entry_date . "'
                                    where
                                    s_c_f_id=$s_c_f_id
                                    AND
                                    school_id=" . $_SESSION['school_id'] . "
                                    AND
                                    status<5 ");
                $this->session->set_flashdata('candidate_delete_3', get_phrase('Candidate Deleted successfully!.'));
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }
        redirect(base_url() . 'c_student/student_pending/');
    }

    function testing_sms()
    {
        $this->load->helper('sms');
        send_sms();
    }

    function delete_student($student_id, $is_readm="")
    {
        $this->db->trans_begin();
        $school_id = $_SESSION['school_id'];
        $data = array(
            'is_deleted' => 1,
            'date_deleted' => date("Y-m-d h:i:sa"),
            'deleted_by' => $_SESSION['login_detail_id']
        );

        $this->db->where('student_id', $student_id);
        $this->db->update(get_school_db() . ".student", $data);


        $updated_by = $_SESSION['login_detail_id'];
        student_archive($updated_by, $student_id);

        /*if($is_readm==0)
        {
            $this->db->query("DELETE FROM ".get_school_db().".student_chalan_detail where s_c_f_id=(select s_c_f_id from ".get_school_db().".student_chalan_form where student_id=$student_id and is_cancelled = 0 and school_id=$school_id and status<5 and is_bulk=0 ) and school_id=$school_id");
            $this->db->query("DELETE FROM ".get_school_db().".student_chalan_form where student_id=$student_id and is_cancelled = 0 and school_id=$school_id and status<5 and is_bulk=0");
            $query_rr =$this->db->query("select * from ".get_school_db().".student where student_id=$student_id and school_id=$school_id")->result_array();
            file_delete(system_path($query[0]['image'],'student'));
            file_delete(system_path($query[0]['id_file'],'student'));
            file_delete(system_path($query[0]['barcode_image'],'student'));
            $qur_rec=$this->db->query("select * from  ".get_school_db().".student_relation sr inner join  ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id where sp.school_id=".$_SESSION['school_id']." AND student_id=$student_id")->result_array();
            if(count($qur_rec)>0)
            {
                foreach($qur_rec as $std_rec)
                {
                    $sp_id=$std_rec['s_p_id'];
                    $qur_r=$this->db->query("select * from ".get_school_db().".student_relation where school_id=".$_SESSION['school_id']." AND  student_id!=$student_id and s_p_id=$sp_id")->result_array();
                    if(count($qur_r)>0)
                    {
                    }
                    else
                    {
                        file_delete(system_path($qur_rec[0]['attachment'],'student'));
                        file_delete($path4);
                        $this->db->where('school_id', $_SESSION['school_id']);
                        $this->db->where('s_p_id', $sp_id);
                        $this->db->delete(get_school_db().'.student_parent');
                    }
                }
            }
            $this->db->query("DELETE FROM ".get_school_db().".student where student_id=$student_id and school_id=$school_id");
        }
        else{
            $student['student_status']=25;
            $this->db->query("DELETE FROM ".get_school_db().".student_chalan_detail
                                        where s_c_f_id=(select s_c_f_id from ".get_school_db().".student_chalan_form
                                        where student_id=$student_id and is_cancelled = 0 and school_id=$school_id and status<5 and is_bulk=0  and form_type=1) and school_id=$school_id");
            $this->db->query("DELETE FROM ".get_school_db().".student_chalan_form
                                        where student_id=$student_id and is_cancelled = 0 and school_id=$school_id and status<5 and is_bulk=0 and form_type=1");
            $this->db->where(student_id,$student_id);
            $this->db->where(school_id,$_SESSION['school_id']);
            $this->db->update(get_school_db().".student",$student);
            $updated_by=$_SESSION['login_detail_id'];
            student_archive($updated_by,$student_id);
        }*/
        //---------------------Transection Ends---------------------------------
        //----------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }
        $this->session->set_flashdata('club_updated', get_phrase('student_record_deleted_succesuly'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    function withdraw($student_id="", $section_id="")
    {
        $school_id = $_SESSION['school_id'];
        $p = "SELECT * FROM " . get_school_db() . ".class_chalan_form WHERE school_id=$school_id and section_id=$section_id and type=6 and status=1";
        $query = $this->db->query($p)->result_array();
        if (count($query) == 0) {
            $this->session->set_flashdata('club_updated', get_phrase('chalan_form_not_found'));
            redirect(base_url() . "c_student/student_information");
        }else{
            $this->db->trans_begin();

            $std_chal_form = $this->student_chalan_form($student_id, 6);
            if ($std_chal_form != "") {
                $data['s_c_f_id'] = $std_chal_form;
                $data['student_id'] = $student_id;
                $data['requested_by'] = $_SESSION['login_detail_id'];
                $date = new DateTime();
                $data['request_date'] = $date->format('Y-m-d H:i:s');
                $data['school_id'] = $_SESSION['school_id'];
                $data['status'] = 21;
                $data['section_id'] = $section_id;
                $this->db->insert(get_school_db() . '.student_withdrawal', $data);
            }
            $stu_data['student_status'] = 21;
            $this->db->where("student_id", $student_id);
            $this->db->where("school_id", $school_id);
            $this->db->update(get_school_db() . ".student", $stu_data);
            $updated_by = $_SESSION['login_detail_id'];
            student_archive($updated_by, $student_id);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            redirect(base_url() . "c_student/withdraw_listing");
        }
    }

    function student_chalan_form($student_id="", $form_type="")
    {

        if ($student_id == "" || $form_type == "") {
            $this->session->set_flashdata('club_updated', get_phrase('chalan_form_not_created_yet'));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $school_id = $_SESSION['school_id'];
            $qur_check = $this->db->query("select * from " . get_school_db() . ".student_chalan_form 
            where school_id=$school_id and student_id=$student_id and is_cancelled=0 and form_type=$form_type and status<5")->result_array();
            if (count($qur_check) > 0) {
                //redirect(base_url() . "class_chalan_form/edit_chalan_form/".$qur_check[0]['s_c_f_id']);
            } /* yes here */
            else {

                $var_chalan = $this->insert_chalan($student_id, $form_type);
                if ($var_chalan == 0) {
                    $this->session->set_flashdata('club_updated', get_phrase('chalan_form_not_found'));
                    //	redirect($_SERVER['HTTP_REFERER']);
                } else {
                    if ($form_type == 6) {
                        return $var_chalan;
                    }
                    //redirect(base_url() . "class_chalan_form/edit_chalan_form/$var_chalan");
                }
            }
        }
    }

    function insert_chalan($student_id, $form_type="", $bulk_req_id = 0)
    {

        $school_id = $_SESSION['school_id'];
        $query_res = $this->db->query("SELECT
            s.name as student_name, s.barcode_image, s.gender as gender,s.section_id,s.roll,s.pro_section_id,s.academic_year_id,s.pro_academic_year_id,sp.p_name as parent_name,cs.title as section_nme,cc.name as class_name,dd.title as department_name FROM " . get_school_db() . ".student s inner join " . get_school_db() . ".student_relation sr on sr.student_id=s.student_id 
                inner join " . get_school_db() . ".student_parent sp on sp.s_p_id=sr.s_p_id 
                inner join " . get_school_db() . ".class_section cs on s.section_id=cs.section_id 
                inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id
                inner join " . get_school_db() . ".departments dd on dd.departments_id=cc.departments_id
                where s.student_id=$student_id and sr.relation='f'")->result_array();

        if (count($query_res) == 0) {
            return 0;
        } else {
            $section_id = $query_res[0]['section_id'];
        }

        if ($form_type == 3) {
            $section_id = $query_res[0]['pro_section_id'];
        }

        $query_re = $this->db->query("SELECT $student_id as sid, ft.fee_type_id, ft.title, ccfe.order_num,ccfe.value,ccf.c_c_f_id,ccf.due_days FROM  " . get_school_db() . ".fee_types ft
            inner join " . get_school_db() . ".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id 
            inner join " . get_school_db() . ".class_chalan_form ccf on ccf.c_c_f_id= ccfe.c_c_f_id 
            where ccf.section_id=$section_id and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id 
            ORDER BY ccfe.order_num")->result_array();

        if (count($query_re) == 0) {
            return 0;
        } else {

            $chalan_setting = $this->db->query("select * from " . get_school_db() . ".chalan_settings where school_id=$school_id")->result_array();
            $due_days = $query_re[0]['due_days'];
            $data = array();
            $data['c_c_f_id'] = $query_re[0]['c_c_f_id'];
            $data['school_id'] = $school_id;
            $data['status'] = 1;
            $data['fee_month_year'] = date("Y-m-d");
            $data['generated_by'] = $_SESSION['login_detail_id'];
            $date = new DateTime();
            $data['generation_date'] = $date->format('Y-m-d H:i:s');
            $data['chalan_form_number'] = chalan_form_number();
            $data['student_id'] = $student_id;
            $data['student_name'] = $query_res[0]['student_name'];
            $data['father_name'] = $query_res[0]['parent_name'];
            $data['roll'] = $query_res[0]['roll'];
            $data['bar_code'] = $query_res[0]['barcode_image'];
            $data['school_name'] = $chalan_setting[0]['school_name'];
            $data['school_logo'] = $chalan_setting[0]['logo'];
            $data['school_address'] = $chalan_setting[0]['address'];
            $data['school_terms'] = $chalan_setting[0]['terms'];
            $data['school_bank_detail'] = $chalan_setting[0]['bank_details'];
            $data['section'] = $query_res[0]['section_nme'];
            $data['class'] = $query_res[0]['class_name'];
            $data['bulk_req_id'] = $bulk_req_id;
            $data['department'] = $query_res[0]['department_name'];
            $data['form_type'] = $form_type;
            $data['due_days'] = $due_days;
            $data['is_cancelled'] = 0;
            $this->db->insert(get_school_db() . '.student_chalan_form', $data);
            $s_c_f_id = $this->db->insert_id();
            $totle = 0;

            foreach ($query_re as $rec_row1) {
                $data_detail1 = array();
                $data_detail1['s_c_f_id'] = $s_c_f_id;
                $data_detail1['fee_type_title'] = $rec_row1['title'];
                $data_detail1['school_id'] = $school_id;
                $data_detail1['amount'] = $rec_row1['value'];
                $data_detail1['type'] = 1;
                $data_detail1['fee_type_id'] = $rec_row1['fee_type_id'];
                $totle = $rec_row1['value'] + $totle;
                /* add fee id for withdraw student start */

                $query_fee_type = $this->db->query("SELECT * FROM " . get_school_db() . ".fee_types where fee_type_id =" . $data_detail1['fee_type_id'] . " AND school_id=$school_id")->row();

                $data_detail1['generate_dr_coa_id'] = $query_fee_type->generate_dr_coa_id;
                $data_detail1['generate_cr_coa_id'] = $query_fee_type->generate_cr_coa_id;

                $data_detail1['issue_dr_coa_id'] = $query_fee_type->issue_dr_coa_id;
                $data_detail1['issue_cr_coa_id'] = $query_fee_type->issue_cr_coa_id;

                $data_detail1['receive_dr_coa_id'] = $query_fee_type->receive_dr_coa_id;
                $data_detail1['receive_cr_coa_id'] = $query_fee_type->receive_cr_coa_id;

                $data_detail1['cancel_dr_coa_id'] = $query_fee_type->issue_cr_coa_id;
                $data_detail1['cancel_cr_coa_id'] = $query_fee_type->issue_dr_coa_id;
                /*  add fee id for withdraw student End */
                $this->db->insert(get_school_db() . ".student_chalan_detail", $data_detail1);
            }

            $get_val = $this->db->query("select * from " . get_school_db() . ".misc_challan_coa_settings 
                                        where school_id=" . $_SESSION['school_id'] . " 
                                        and type='arrears_coa'")->result_array();

            $arrears_ammount = 0;
            if (count($get_val) > 0) {
                /////arrears
                $qur_are = $this->db->query("select arrears,s_c_f_id from " . get_school_db() . ".student_chalan_form where school_id=$school_id and is_cancelled = 0 and status=5 and student_id=$student_id and is_processed=0 and arrears_status=1")->result_array();

                $data_arrears['s_c_f_id'] = $s_c_f_id;
                $data_arrears['fee_type_title'] = 'Arrears';
                $data_arrears['school_id'] = $school_id;
                $arrears_ammount = 0;
                foreach ($qur_are as $val_amu) {
                    $arrears_ammount += $val_amu['arrears'];
                    $this->db->where('s_c_f_id', $s_c_f_id);
                    $this->db->update(get_school_db() . ".student_chalan_form", array('arrears_status' => 0));
                }

                $data_arrears['amount'] = $arrears_ammount;
                $data_arrears['type'] = 3;

                $data_arrears['issue_dr_coa_id'] = $get_val[0]['issue_dr_coa_id'];
                $data_arrears['issue_cr_coa_id'] = $get_val[0]['issue_cr_coa_id'];
                $data_arrears['receive_dr_coa_id'] = $get_val[0]['receive_dr_coa_id'];
                $data_arrears['receive_cr_coa_id'] = $get_val[0]['receive_cr_coa_id'];
                $data_arrears['cancel_dr_coa_id'] = $get_val[0]['issue_cr_coa_id'];
                $data_arrears['cancel_cr_coa_id'] = $get_val[0]['issue_dr_coa_id'];
                $data_arrears['generate_dr_coa_id'] = $get_val[0]['generate_dr_coa_id'];
                $data_arrears['generate_cr_coa_id'] = $get_val[0]['generate_cr_coa_id'];

                $data_arrears['fee_type_id'] = 0;
                $this->db->insert(get_school_db() . ".student_chalan_detail", $data_arrears);

            }

            $query_rec_str = "SELECT  dl.discount_id,dl.title,ccd.value,ccd.order_num,issue_dr_coa_id,issue_cr_coa_id,receive_dr_coa_id,receive_cr_coa_id,cancel_dr_coa_id,cancel_cr_coa_id
                            FROM " . get_school_db() . ".discount_list dl inner join " . get_school_db() . ".class_chalan_discount ccd on ccd.discount_id=dl.discount_id
                            inner join " . get_school_db() . ".class_chalan_form ccf on
                            ccf.c_c_f_id= ccd.c_c_f_id
                            where ccf.section_id=$section_id and ccf.type=$form_type
                            and ccf.status=1 and ccf.school_id=$school_id 
                            ORDER BY ccd.order_num";

            $query_rec = $this->db->query($query_rec_str)->result_array();

            foreach ($query_rec as $rec_row) {
                $data_detail = array();
                $data_detail['s_c_f_id'] = $s_c_f_id;
                $data_detail['fee_type_title'] = $rec_row['title'];
                $data_detail['school_id'] = $school_id;
                $amount = $rec_row['value'];
                $data_detail['type'] = 2;
                $data_detail['fee_type_id'] = $rec_row['discount_id'];

                $data_detail['issue_dr_coa_id'] = $rec_row['issue_dr_coa_id'];
                $data_detail['issue_cr_coa_id'] = $rec_row['issue_cr_coa_id'];
                $data_detail['receive_dr_coa_id'] = $rec_row['receive_dr_coa_id'];
                $data_detail['receive_cr_coa_id'] = $rec_row['receive_cr_coa_id'];
                $data_detail['cancel_dr_coa_id'] = $rec_row['issue_cr_coa_id'];
                $data_detail['cancel_cr_coa_id'] = $rec_row['issue_dr_coa_id'];
                $data_detail['amount']           = $rec_row['value'];
                $totle = $totle - $data_detail['amount'];
                $this->db->insert(get_school_db() . ".student_chalan_detail", $data_detail);
            }
            $update_amount['actual_amount'] = $totle;
            $this->db->where("s_c_f_id", $s_c_f_id);
            $this->db->update(get_school_db() . ".student_chalan_form", $update_amount);
            return $s_c_f_id;
        }

    }

    function installment_activate()
    {
        $is_installmet = $this->input->post('is_installment');
        $student_id = $this->input->post('student_id');
        if ($is_installmet == 1) {
            $data['is_installment'] = 0;
        } else {
            $data['is_installment'] = 1;
        }

        $qur_check = $this->db->query("select * from " . get_school_db() . ".student_fee_settings where student_id=$student_id and school_id=" . $_SESSION['school_id'])->result_array();
        if (count($qur_check) > 0) {
            $this->db->where('student_id', $student_id);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->update(get_school_db() . '.student', $data);

            $this->session->set_flashdata('club_updated', get_phrase('Activated'));
        } else {
            $this->session->set_flashdata('club_updated', get_phrase());
            $data['is_installment'] = 0;
            $this->db->where('student_id', $student_id);
            $this->db->where('school_id', $_SESSION['school_id']);
            $this->db->update(get_school_db() . '.student', $data);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete_installment($s_m_i_id="", $student_id="", $month="", $year="", $fee_type_id="")
    {
        $fee_settings_id = $s_m_i_id;
        $school_id = $_SESSION['school_id'];

        $query_delete_student_fee_settings_str = "DELETE FROM " . get_school_db() . ".student_fee_settings  WHERE 
                                                  student_id=$student_id AND school_id=$school_id AND settings_type = 2
                                                  AND month = $month AND year = $year AND fee_settings_id = $fee_settings_id";
        $query_delete_student_fee_settings = $this->db->query($query_delete_student_fee_settings_str);

        if ($query_delete_student_fee_settings == 1) {
            $this->session->set_flashdata('club_updated', get_phrase('deleted_action_successfully'));
            redirect(base_url() . "c_student/student_m_installment/$student_id");
            exit;
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('deleted_action_failed'));
            redirect(base_url() . "c_student/student_m_installment/$student_id");
            exit;
        }

        /* $qur_check_str = "SELECT * FROM ".get_school_db().".student_chalan_form
                           WHERE
                                  student_id=$student_id
                                     AND s_c_f_month = $month
                                     AND s_c_f_year = $year
                                     AND form_type = 2
                                     AND school_id=$school_id";
         $qur_check=$this->db->query($qur_check_str)->result_array();
         if(count($qur_check)>0)
         {

                     $s_c_f_id = $qur_check[0]['s_c_f_id'];



                     $s_c_f_id = $qur_check[0]['s_c_f_id'];
                     $month_temp = $qur_check[0]['s_c_f_month'];
                     $year_temp = $qur_check[0]['s_c_f_year'];

                     $query_s_c_f_d_str = "select * from ".get_school_db().".student_chalan_detail
                                                 where school_id=$school_id
                                                 AND type = 4
                                                 AND type_id = $fee_type_id
                                                 AND s_c_f_id = $s_c_f_id";
                     $query_s_c_f_d = $this->db->query($query_s_c_f_d_str)->result_array();


                     if(count($query_s_c_f_d)>0)
                     {
                        $s_c_f_id = $s_c_f_id;
                        $s_c_f_d_id = $query_s_c_f_d[0]['s_c_d_id'];
                        $amount = $query_s_c_f_d[0]['amount'];
                        $this->update_chalan_form($s_c_f_id , $fee_type_id , $amount , $delete=1);

                      // $this->update_chalan_form($s_c_f_id , $fee_type_id , $amount);



                         $query_delete_chalan_detail_str = "DELETE from ".get_school_db().".student_chalan_detail
                                                 WHERE school_id=$school_id
                                                 AND type = 4
                                                 AND s_c_d_id = $s_c_f_d_id
                                                 AND s_c_f_id = $s_c_f_id
                                                 AND type_id = $fee_type_id";
                         $query_delete_chalan_detail = $this->db->query($query_delete_chalan_detail_str);

                        $query_delete_student_fee_settings_str = "DELETE FROM ".get_school_db().".student_fee_settings
                                 WHERE
                                  student_id=$student_id
                                    AND school_id=$school_id
                                    AND fee_type = 2
                                    AND settings_type = 2
                                    AND fee_settings_id = $fee_settings_id";

                         $query_delete_student_fee_settings =$this->db->query($query_delete_student_fee_settings_str);


                         $query_is_deleted_str = "SELECT * from ".get_school_db().".student_chalan_form as scf
                                               INNER JOIN ".get_school_db().".student_chalan_detail as scfd on scf.s_c_f_id = scfd.s_c_f_id
                                                 WHERE scf.school_id=$school_id
                                                 AND scfd.s_c_d_id = $s_c_f_d_id
                                                 AND scf.s_c_f_id = $s_c_f_id";
                         $query_is_deleted = $this->db->query($query_is_deleted_str)->result_array();

                         if(count($query_is_deleted)>0)
                         {

                         }
                         else
                         {

                             $this->session->set_flashdata('club_updated', get_phrase('student_installment_deleted_as_well_as_chalan_form_deleted'));
                             redirect(base_url() . "c_student/student_m_installment/$student_id");
                         }




                     }
         }
         else
         {



             $qur_check_str = "select * from " . get_school_db() . ".student_fee_settings
                            where
                            student_id=$student_id
                            and school_id=$school_id";
             $qur_check = $this->db->query($qur_check_str)->result_array();

             $this->db->query("delete from " . get_school_db() . ".student_fee_settings where fee_settings_id=$s_m_i_id and school_id=" . $_SESSION['school_id']);

             if (count($qur_check) > 0)
             {

             }
             else
             {
                 $this->session->set_flashdata('club_updated', get_phrase('error_you_dont_have_any_record_added'));
                 $data['is_installment'] = 0;
                 $this->db->where('student_id', $student_id);
                 $this->db->where('school_id', $_SESSION['school_id']);
                 $this->db->update(get_school_db() . '.student', $data);
             }
             $this->session->set_flashdata('club_updated', get_phrase('student_discount_setting_deleted'));
             redirect($_SERVER['HTTP_REFERER']);
         }*/
    }

    function monthly_delete_installment($student_id="", $section_id="", $month_year="")
    {

        $school_id = $_SESSION['school_id'];
        $month_year_temp = explode("_", $month_year);
        $data['month'] = $month_year_temp[0];
        $data['year'] = $month_year_temp[1];
        $month = $data['month'];
        $year = $data['year'];

        $query_select_msfs_str = "select * from " . get_school_db() . ".student_monthly_fee_settings where school_id=$school_id
                                   AND student_id= $student_id AND fee_month = $month AND fee_year = $year; ";
        $query_select_msfs = $this->db->query($query_select_msfs_str)->result_array();

        if (count($query_select_msfs) > 0) {

            $std_m_fee_settings_id = $query_select_msfs[0]['std_m_fee_settings_id'];

            $query_delete_sfs_fee_str = "delete from " . get_school_db() . ".student_fee_settings where school_id=$school_id
                                         AND student_id= $student_id AND month = $month AND year = $year AND academic_year_id = $section_id AND is_bulk = 1 AND std_m_fee_settings_id = $std_m_fee_settings_id";
            $query_delete_sfs_fee_str = $this->db->query($query_delete_sfs_fee_str);

            $query_delete_msfs_str = "delete from " . get_school_db() . ".student_monthly_fee_settings
                                   where school_id=$school_id
                                   AND student_id= $student_id
                                   AND fee_month = $month
                                   AND fee_year = $year
                                   AND std_m_fee_settings_id = $std_m_fee_settings_id";
            $query_delete_msfs = $this->db->query($query_delete_msfs_str);

            $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
            redirect(base_url() . 'c_student/student_monthly_fee_setting/' . $student_id);

        }

    }

    function student_fee_settings_delete($student_id="", $section_id="", $month_year="")
    {
        $this->db->trans_begin();

        $school_id = $_SESSION['school_id'];
        $month_year_temp = explode("_", $month_year);
        $data['month'] = $month_year_temp[0];
        $data['year'] = $month_year_temp[1];
        $month = $data['month'];
        $year = $data['year'];
        $std_m_fee_settings_id = 0;

        $query_select_msfs_str = "select * from " . get_school_db() . ".student_monthly_fee_settings where school_id=$school_id
                                   AND student_id= $student_id AND fee_month = $month AND fee_year = $year";
        $query_select_msfs = $this->db->query($query_select_msfs_str)->result_array();

        $delete_confirm = 0;
        if (count($query_select_msfs) > 0) {

            $std_m_fee_settings_id = $query_select_msfs[0]['std_m_fee_settings_id'];

            $query_delete_msfs_str = "DELETE FROM " . get_school_db() . ".student_monthly_fee_settings where school_id=$school_id
                                   AND student_id= $student_id AND fee_month = $month AND fee_year = $year AND std_m_fee_settings_id = $std_m_fee_settings_id";
            $query_delete_msfs = $this->db->query($query_delete_msfs_str);

            $query_delete_sfs_str = "delete from " . get_school_db() . ".student_fee_settings WHERE  month = " . $month . "
                                     AND year = " . $year . " AND student_id = $student_id AND school_id = $school_id
                                     AND is_bulk = 1 AND std_m_fee_settings_id = $std_m_fee_settings_id";
            $query_delete_sfs = $this->db->query($query_delete_sfs_str);

            $query_update_msfs_fee_str = "UPDATE " . get_school_db() . ".student_fee_settings set std_m_fee_settings_id = 0
                                          WHERE  month = " . $month . " AND year = " . $year . " AND student_id = $student_id AND school_id = $school_id
                                          AND is_bulk = 0 AND std_m_fee_settings_id = $std_m_fee_settings_id";
            $query_update_msfs_fee = $this->db->query($query_update_msfs_fee_str);

        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }

        $this->session->set_flashdata('club_updated', get_phrase('deleted_record_successfully.'));
        redirect(base_url() . "c_student/student_monthly_fee_setting/$student_id");

    }

    function delete_chalan_form($student_id="", $s_c_f_id="", $s_c_f_d_id="", $fee_settings_id="")
    {
        $this->db->trans_begin();

        $school_id = $_SESSION['school_id'];
        $query_delete_chalan_detail_str = "DELETE from " . get_school_db() . ".student_chalan_detail WHERE school_id=$school_id
                                           AND type = 4 AND s_c_d_id = $s_c_f_d_id AND s_c_f_id = $s_c_f_id";
        $query_delete_chalan_detail = $this->db->query($query_delete_chalan_detail_str);
        
        $query_delete_chalan_form_str = "DELETE FROM " . get_school_db() . ".student_chalan_form  WHERE  student_id=$student_id
                                         AND school_id=$school_id AND form_type = 2 AND s_c_f_id = $s_c_f_id";
        $query_delete_chalan_form = $this->db->query($query_delete_chalan_form_str);

        $query_delete_student_fee_settings_str = "DELETE FROM " . get_school_db() . ".student_fee_settings  WHERE  student_id=$student_id
                                                  AND school_id=$school_id AND fee_type = 2 AND settings_type = 2 AND fee_settings_id = $fee_settings_id";
        $query_delete_student_fee_settings = $this->db->query($query_delete_student_fee_settings_str);


        $query_is_deleted_str = "SELECT scf.s_c_f_id from " . get_school_db() . ".student_chalan_form as scf
                                 INNER JOIN " . get_school_db() . ".student_chalan_detail as scfd on scf.s_c_f_id = scfd.s_c_f_id
                                 WHERE scf.school_id=$school_id AND scfd.s_c_d_id = $s_c_f_d_id AND scf.s_c_f_id = $s_c_f_id";
        $query_is_deleted = $this->db->query($query_is_deleted_str)->result_array();

        if (count($query_is_deleted) < 1) {
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
            } else {
                $this->db->trans_commit();
            }
            $this->session->set_flashdata('club_updated', get_phrase('student_installment_deleted_as_well_as_chalan_form_deleted'));
            redirect(base_url() . "c_student/student_m_installment/$student_id");
        }
    }

    function delete_discount($id)
    {
        $this->db->trans_begin();

        $this->db->where('s_m_d_id', $id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->delete(get_school_db() . ".student_m_discount");
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        $this->session->set_flashdata('delete_dis', get_phrase('discount_deleted_successfully!'));
        redirect(base_url() . 'c_student/student_information/');
    }

    function withdraw_listing()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        $page_data['page_name'] = 'withdraw_listing';
        $page_data['page_title'] = get_phrase('withdraw_listing');
        $this->load->view('backend/index', $page_data);
    }

    function get_withdraw_student()
    {
        $data['section_id'] = $this->input->post('section_id');
        $data['start_date'] = date_slash($this->input->post('start_date'));
        $data['end_date'] = date_slash($this->input->post('end_date'));
        $this->load->view("backend/admin/ajax/withdraw_info", $data);
    }

    function confirm_withdraw($student_id)
    {
        $this->db->trans_begin();

        $s_c_f_id = $this->uri->segment(4);
        $std_withdraw_id = $this->uri->segment(5);
        $student_status = $this->input->post('student_status');
        $withdrawal_reason = $this->input->post('withdrawal_reason');
        
        $data['student_status'] = $this->input->post('student_status');
        $data['std_withdarwal_reason'] = $withdrawal_reason;
        $this->db->where("school_id", $_SESSION['school_id']);
        $this->db->where("student_id", $student_id);
        $this->db->update(get_school_db() . ".student", $data);
        $updated_by = $_SESSION['login_detail_id'];
        student_archive($updated_by, $student_id);

        if ($student_status == 25) {

            $arr['confirm_by'] = $updated_by;
            $date = new DateTime();
            $arr['confirm_date'] = $date->format('Y-m-d H:i:s');
            $arr['status'] = $this->input->post('student_status');
            $this->db->where("school_id", $_SESSION['school_id']);
            $this->db->where("std_withdraw_id", $std_withdraw_id);
            $this->db->update(get_school_db() . ".student_withdrawal", $arr);
        } else {
            $arr['status'] = $this->input->post('student_status');
            $this->db->where("school_id", $_SESSION['school_id']);
            $this->db->where("std_withdraw_id", $std_withdraw_id);
            $this->db->update(get_school_db() . ".student_withdrawal", $arr);
        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        
        redirect($_SERVER['HTTP_REFERER']);

    }

    function roll_check()
    {
        $roll = $this->input->post('roll');
        $student_id = $this->input->post('student_id');
        $qur_ = "SELECT student_id FROM " . get_school_db() . ".student  WHERE section_id 
                 IN (SELECT section_id FROM " . get_school_db() . ".student  WHERE student_id = 2) AND roll='$roll'";
        $query = $this->db->query($qur_)->result_array();

        if (count($query) > 0) {
            echo "no";
        } else {
            echo "yes";
        }
    }

    function withdraw_cancel($student_id, $chalan_id)
    {

        $s_c_f_id = $chalan_id; //$blk_row->s_c_f_id;
        $date = new DateTime();
        $entry_date = $date->format('Y-m-d H:i:s');
        $c_f_str = "SELECT scd.s_c_d_id, scf.s_c_f_id , scf.student_id , scd.fee_type_title , scd.amount , scd.school_id, scf.chalan_form_number,
                                                            scd.cancel_dr_coa_id ,scd.cancel_cr_coa_id , scf.student_name , scd.type
                                                            FROM " . get_school_db() . ".student_chalan_form as scf
                                                            INNER JOIN " . get_school_db() . ".student_chalan_detail as scd
                                                            ON scf.s_c_f_id = scd.s_c_f_id WHERE scd.s_c_f_id = " . $s_c_f_id . "
                                                            AND scd.school_id = " . $_SESSION['school_id'] . " AND scf.status>=4";
        $query_c_f = $this->db->query($c_f_str)->result();
        if (count($query_c_f) > 0) {
            foreach ($query_c_f as $row) {

                if ($row->amount > 0) {
                    $transaction_detail = student_name_section($row->student_id);

                    $journal_entry = 0;
                    if ($row->type == 2 || $row->type == 4) {
                        $journal_entry = 1;
                        $amount = $row->amount;
                    } elseif ($row->type == 1 || $row->type == 5) {
                        $journal_entry = 1;
                        $amount = $row->amount;//(-1) * ($row->amount);
                    } else if ($row->type == 3 || $row->type == 6) {
                        $journal_entry = 0;

                    }

                    if ($journal_entry == 1) {

                        $data_debit = array(
                            'entry_date' => $entry_date,
                            'detail' => $row->fee_type_title
                                . ' - ' . get_phrase('cancelled_challan_form') . " - " . $row->chalan_form_number . " - " . get_phrase('student_name') . " - " . get_phrase('from') . " - " . $transaction_detail,
                            'debit' => $amount,
                            'entry_type' => 2,
                            'type_id' => $s_c_f_id,
                            'school_id' => $row->school_id,
                            'coa_id' => $row->cancel_dr_coa_id
                        );
                        //'entry_date' => CURDATE(),
                        $data_credit = array(
                            'entry_date' => $entry_date,
                            'detail' => $row->fee_type_title
                                . ' - ' . get_phrase('cancelled_challan_form') . " - " . $row->chalan_form_number . " - " . get_phrase('student_name') . " - " . get_phrase('from') . " - " . $transaction_detail,
                            'credit' => $amount,
                            'entry_type' => 2,
                            'type_id' => $s_c_f_id,
                            'school_id' => $row->school_id,
                            'coa_id' => $row->cancel_cr_coa_id
                        );

                        $this->db->insert(get_school_db() . ".journal_entry", $data_debit);
                        $this->db->insert(get_school_db() . ".journal_entry", $data_credit);
                    }

                }

            }

            $std_withdraw_id = $this->uri->segment(5);

            if ($std_withdraw_id != "") {

                $student['student_status'] = 10;
                $this->db->where(student_id, $student_id);
                $this->db->update(get_school_db() . ".student", $student);

                if ($chalan_id != "") {

                    $this->db->query("UPDATE " . get_school_db() . ".student_chalan_form
                        SET is_cancelled = 1,
                        cancelled_by = " . $_SESSION['login_detail_id'] . ",
                        cancel_date = '" . $entry_date . "'
						where  s_c_f_id = $chalan_id  AND  school_id =" . $_SESSION['school_id'] . "  AND  status < 5 ");
                }

                $updated_by = $_SESSION['login_detail_id'];
                student_archive($updated_by, $student_id);
                $this->session->set_flashdata('club_updated', get_phrase('withdrawl_request_canceled_successfully'));
                redirect($_SERVER['HTTP_REFERER']);

            } else {
                $this->session->set_flashdata('club_updated', get_phrase('withdrawl_request_not_found'));
                exit;
            }


        } else {

            $std_withdraw_id = $this->uri->segment(5);

            if ($std_withdraw_id != "") {
                $student['student_status'] = 10;

                $this->db->where(student_id, $student_id);
                $this->db->update(get_school_db() . ".student", $student);

                if ($chalan_id != "") {
                    $this->db->query("delete from " . get_school_db() . ".student_chalan_detail where school_id=" . $_SESSION['school_id'] . " and s_c_f_id=$chalan_id");
                    $this->db->query("delete from " . get_school_db() . ".student_chalan_form where school_id=" . $_SESSION['school_id'] . " and s_c_f_id=$chalan_id");
                    $this->db->query("delete from " . get_school_db() . ".student_withdrawal where school_id=" . $_SESSION['school_id'] . " and s_c_f_id=$chalan_id and student_id=$student_id");
                }

                $updated_by = $_SESSION['login_detail_id'];
                student_archive($updated_by, $student_id);
                $this->session->set_flashdata('club_updated', get_phrase('withdrawl_request_canceled_successfully'));
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('club_updated', 'withdrawl_request_not_found');
            }

        }

    }

    function re_admission($student_id)
    {
        $this->db->trans_begin();

        $student['student_status'] = 1;
        $student['is_readmission'] = 1;
        $student_chalan['is_processed'] = 1;

        $this->db->where(student_id, $student_id);
        $this->db->update(get_school_db() . ".student", $student);

        $this->db->where(student_id, $student_id);
        $this->db->update(get_school_db() . ".student_chalan_form", $student_chalan);

        $this->insert_chalan($student_id, '1', 0);

        $updated_by = $_SESSION['login_detail_id'];
        student_archive($updated_by, $student_id);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        
        $this->session->set_flashdata('club_updated', get_phrase('re-admission_request_generated_successfully'));
        redirect(base_url() . 'c_student/student_pending/');
    }

    function student_m_discount($student_id, $param1 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');

        if ($param1 == 'do_insert') {
            
            $this->db->trans_begin();

            $data['title'] = $this->input->post('title');
            $data['student_id'] = $this->input->post('student_id');
            $data['amount'] = $this->input->post('amount');
            $data['discount_id'] = $this->input->post('discount_type');

            $data['academic_year_id'] = $this->input->post('academic_year_id');
            $data['school_id'] = $_SESSION['school_id'];

            $s_m_d_id = $this->input->post('s_m_d_id');

            /* Find the number of record of current student */
            $this->db->where('s_m_d_id', $s_m_d_id);
            $this->db->where('school_id', $data['school_id']);
            $query = $this->db->get(get_school_db() . '.student_m_discount');
            /* End of Find the number of record of current student */

            if ($query->num_rows() == 0) {
                $this->db->insert(get_school_db() . '.student_m_discount', $data);
                $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully.'));
            } else {
                $this->db->where('school_id', $_SESSION['school_id']);
                $this->db->where('s_m_d_id', $s_m_d_id);
                $this->db->update(get_school_db() . '.student_m_discount', $data);
                $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully.'));
            }
            
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            redirect(base_url() . 'c_student/student_m_discount/' . $data['student_id']);
        }

        $section_id = $this->uri->segment(4);
        $page_data['page_name'] = 'student_m_discount';
        $page_data['system_name'] = '';
        $page_data['student_id'] = $student_id;
        $page_data['section_id'] = $section_id;
        $page_data['page_title'] = get_phrase('student_monthly_discount');

        $this->load->view('backend/index', $page_data);
    }

    function student_m_installment($student_id, $s_m_i_id = 0, $param1 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');
        if ($param1 == 'do_insert') {
            
            $this->db->trans_begin();

            $data['title'] = $this->input->post('title');
            $data['student_id'] = $this->input->post('student_id');
            $data['amount'] = $this->input->post('amount');
            $data['fee_type_id'] = $this->input->post('fee_type');
            $month_datepiker = $this->input->post('month_datepiker');
            $month_datepiker_temp = explode("/", $month_datepiker);
            $data['month'] = $month_datepiker_temp[0];
            $data['year'] = $month_datepiker_temp[1];
            $data['school_id'] = $_SESSION['school_id'];
            $fee_settings_id = $this->input->post('fee_settings_id');
            if ($fee_settings_id == "") {
                $month_installment = array('month =' => $data['month'], 'year =' => $data['year'], 'student_id' => $data['student_id'], 'school_id' => $data['school_id']);
                $this->db->where($month_installment);
                $month_installment_qr = $this->db->get(get_school_db() . '.student_fee_settings');
                $month_installment_row = $month_installment_qr->row();

                    $this->db->insert(get_school_db() . '.student_fee_settings', $data);
                    $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));

            }else{
                $month_installment = array('month =' => $data['month'], 'year =' => $data['year'], 'student_id' => $data['student_id'], 'school_id' => $data['school_id'], 'fee_settings_id <> ' => $s_m_i_id);
                $this->db->where($month_installment);
                $month_installment_qr = $this->db->get(get_school_db() . '.student_fee_settings');
                $month_installment_row = $month_installment_qr->row();

                if (count($month_installment_row) > 0) {
                    $array = array( 'title' => $data['title'], 'amount' => $data['amount'], 'fee_type_id' => $data['fee_type_id']);

                    $this->db->set($array);
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->where('fee_settings_id', fee_settings_id);
                    $this->db->update(get_school_db() . '.student_fee_settings', $array);
                    $this->session->set_flashdata('installment_not_allowed', get_phrase('installment_already_exists_for_sprcified_month_and_year.'));
                } else {
                    $this->db->where('school_id', $_SESSION['school_id']);
                    $this->db->where('fee_settings_id', fee_settings_id);
                    $this->db->update(get_school_db() . '.student_fee_settings', $data);
                    $this->session->set_flashdata('club_updated', get_phrase('record_updeted_successfully'));
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            redirect(base_url() . 'c_student/student_m_installment/62');
        }

        $page_data['page_name'] = 'student_m_installment';
        $page_data['system_name'] = '';
        $page_data['student_id'] = str_decode($student_id);
        $page_data['section_id'] = str_decode($this->uri->segment(4));

        $page_data['s_m_i_id'] = $fee_settings_id;
        $page_data['page_title'] = get_phrase('student_m_installment');
        $this->db->where('school_id', $_SESSION['school_id']);
        $page_data['settings'] = $this->db->get(get_school_db() . '.chalan_settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function student_monthly_fee_setting($student_id, $s_m_i_id = 0, $param1 = '')
    {
        if ($_SESSION['user_login'] != 1)
            redirect(base_url() . 'login');

        $page_data['page_name'] = 'student_monthly_fee_setting';
        $page_data['system_name'] = '';
        $page_data['student_id'] = $student_id;
        $page_data['section_id'] = $this->uri->segment(4);
        $page_data['page_title'] = get_phrase('student_monthly_fee_setting');
        $this->db->where('school_id', $_SESSION['school_id']);
        $page_data['settings'] = $this->db->get(get_school_db() . '.chalan_settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function month_fee_add()
    {
        $this->db->trans_begin();

        $school_id = $_SESSION['school_id'];
        $data['school_id'] = $school_id;
        $data['title'] = $this->input->post('title');
        $data['student_id'] = $this->input->post('student_id');
        $data['amount'] = $this->input->post('amount');
        $data['fee_type'] = 1; //$this->input->post('fee_type');
        $data['fee_type_id'] = $this->input->post('fee_type_id_discount');
        $settings_type_arr = $this->input->post('settings_type_arr');
        $status_arr = $this->input->post('status_arr');
        $data['is_bulk'] = 0;
        $data['academic_year_id'] = $this->input->post('section_id');
        $section_id = $data['academic_year_id'];
        $month_year = $this->input->post('month_date');

        $amount = $this->input->post('amount');

        $student_id = $data['student_id'];
        $discount_id = $data['fee_type_id'];

        $data['title'] = $this->input->post('title');
        $title = $data['title'];
        if ($title == "")
        {
            $title_arr = get_title_discount($data['fee_type_id']);
            $data['title'] = $title_arr['fee_title'];
        }

        $query_save_month_str1 = "DELETE FROM " . get_school_db() . ".student_fee_settings WHERE school_id=$school_id
                                 AND student_id= $student_id AND fee_type_id = $discount_id AND fee_type = 1
                                 AND student_id = $student_id AND school_id = $school_id";
        $query_save_month1 = $this->db->query($query_save_month_str1);

        $month_year_temp = explode("_", $month_year);
        $data['month'] = $month_year_temp[0];
        $data['year'] = $month_year_temp[1];
        $month = $data['month'];
        $year = $data['year'];

        if (count(array_filter($amount)) > 0)
        {

            for ($i = 0; $i <= count($amount); $i++)
            {

                if ($amount[$i] != "" && $amount[$i] > 0)
                {
                    $amount_current = abs($amount[$i]);
                    $month_year_temp = explode("_", $month_year[$i]);
                    $data['month'] = $month_year_temp[0];
                    $data['year'] = $month_year_temp[1];
                    $year = $data['year'];
                    $data['std_m_fee_settings_id'] = $month_year_temp[2];
                    $month = $data['month'];
                    $data['settings_type'] = $settings_type_arr[$i];
                    $data['status'] = $status_arr[$i];

                    $pre_amount =  $month_year_temp[3];
                    $is_bulk = $month_year_temp[4];
                    $std_m_fee_settings_id = 0;

                    $query_select_msfs_str = "select * from " . get_school_db() . ".student_monthly_fee_settings where school_id=$school_id
                                              AND student_id= $student_id AND fee_month = $month AND fee_year = $year";
                    $query_select_msfs = $this->db->query($query_select_msfs_str)->result_array();

                    if (count($query_select_msfs) > 0) {

                        $query_update_msfs_fee_str = "UPDATE " . get_school_db() . ".student_monthly_fee_settings
                                   set
                                       status = 1, comments = 'add comments'
                                   WHERE 
                                       fee_month = " . $month . " AND fee_year = " . $year . " AND student_id = $student_id AND school_id = $school_id";
                        $query_update_msfs_fee = $this->db->query($query_update_msfs_fee_str);
                        $std_m_fee_settings_id = $query_select_msfs[0]['std_m_fee_settings_id'];//$this->db->insert_id();

                    } else {

                        $query_insert_msfs_fee_str = "INSERT INTO " . get_school_db() . ".student_monthly_fee_settings set
                                                      fee_month = " . $month . ", fee_year = " . $year . ", student_id = $student_id,
                                                      school_id = $school_id, status = 1, comments = 'add comments', generated_by = 'generated by', approved_by = 1, issued_by = 1";
                        $query_insert_msfs_fee = $this->db->query($query_insert_msfs_fee_str);
                        $std_m_fee_settings_id = $this->db->insert_id();
                    }

                    if($amount_current == $pre_amount)
                    {
                        $data['amount'] = $pre_amount;
                        $data['is_bulk'] = $is_bulk;
                    }
                    else
                    {
                        $data['amount'] = $amount_current;
                        $data['is_bulk'] = 0;
                        $data['std_m_fee_settings_id'] = $std_m_fee_settings_id;
                    }
                    $this->db->insert(get_school_db() . '.student_fee_settings', $data);
                }
            }

            $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));

        } else
        {
            $this->session->set_flashdata('club_updated', get_phrase('pleaese_select_at_least_0ne_academic_month'));
        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }
        redirect(base_url() . 'c_student/student_m_installment/' . str_encode($data['student_id']));
    }

    function single_month_fee_add()
    {
        $this->db->trans_begin();

        $school_id = $_SESSION['school_id'];
        $data['school_id'] = $school_id;
        $data['settings_type'] = 1;

        $data['academic_year_id'] = $this->input->post('section_id');
        $section_id = $data['academic_year_id'];

        $data['student_id'] = $this->input->post('student_id');
        $student_id = $data['student_id'];

        $data['status'] = $this->input->post('status');
        $status = $data['status'];

        $data['fee_settings_id'] = $this->input->post('fee_settings_id');
        $fee_settings_id = $data['fee_settings_id'];

        $data['is_bulk'] = 0;

        $month_year = $this->input->post('month_date');

        $data['comments'] = $this->input->post('comments');
        $comments = $data['comments'];

        $section_id = $data['academic_year_id'];
        $current_date = date('Y-m-d H:i:s');


        if ($status == 2) {
            $date_str = "approval_date = '" . $current_date . "'";
        } elseif ($status == 3) {
            $date_str = "issue_date = '" . $current_date . "'";
        } else {
            $date_str = "generation_date = '" . $current_date . "'";
        }

        $login_by = $_SESSION['login_detail_id'];
        $std_m_fee_settings_id = 0;

        if (!empty($month_year)) {

            $month_year_temp = explode("_", $month_year);
            $data['month'] = $month_year_temp[0];
            $data['year'] = $month_year_temp[1];
            $month = $data['month'];
            $year = $data['year'];

            $query_select_msfs_str = "select * from " . get_school_db() . ".student_monthly_fee_settings where school_id=$school_id
                                      AND student_id= $student_id AND fee_month = $month AND fee_year = $year";
            $query_select_msfs = $this->db->query($query_select_msfs_str)->result_array();

            if (count($query_select_msfs) > 0) {

                $query_update_msfs_fee_str = "UPDATE " . get_school_db() . ".student_monthly_fee_settings
                                       set
                                           status = $status, comments = '" . $comments . "'
                                       WHERE 
                                           fee_month = " . $month . " AND fee_year = " . $year . " AND student_id = $student_id AND school_id = $school_id";
                $query_update_msfs_fee = $this->db->query($query_update_msfs_fee_str);
                $std_m_fee_settings_id = $query_select_msfs[0]['std_m_fee_settings_id'];//$this->db->insert_id();

            } else {

                $query_insert_msfs_fee_str = "INSERT INTO " . get_school_db() . ".student_monthly_fee_settings
                                       set
                                           fee_month = " . $month . ", fee_year = " . $year . ",
                                           student_id = $student_id, school_id = $school_id,
                                           status = $status, comments = '" . $comments . "',
                                           generated_by = $login_by, approved_by = $login_by,
                                           issued_by = $login_by, $date_str";
                $query_insert_msfs_fee = $this->db->query($query_insert_msfs_fee_str);
                $std_m_fee_settings_id = $this->db->insert_id();
            }

            if (count($fee_settings_id) > 0) {
                echo "<br>";

                for ($k = 0; $k < count($fee_settings_id); $k++) {
                    $query_update_sfs_discount_str = "UPDATE " . get_school_db() . ".student_fee_settings set
                                                       std_m_fee_settings_id = $std_m_fee_settings_id
                                                      WHERE 
                                                       month = " . $month . " AND year = " . $year . " AND student_id = $student_id
                                                       AND school_id = $school_id AND fee_settings_id = $fee_settings_id[$k]";
                    $query_update_sfs_discount_str = $this->db->query($query_update_sfs_discount_str);
                }
            }

        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }

        $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
        redirect(base_url() . 'c_student/student_monthly_fee_setting/' . $student_id);

    }

    function bulk_month_fee_add()
    {
        
        $this->db->trans_begin();
        
        $school_id = $_SESSION['school_id'];
        $data['school_id'] = $school_id;
        $data['fee_title'] = $this->input->post('fee_title');
        $fee_title = $data['fee_title'];

        $data['status'] = $this->input->post('status');
        $status = $data['status'];

        $data['status'] = $this->input->post('comments');
        $comments = $data['status'];
        $data['student_id'] = $this->input->post('student_id');
        $data['amount'] = $this->input->post('fee_amount');
        $amount = $data['amount'];

        if (empty($amount))
        {
            $amount = array();
        }

        $data['fee_type'] = 1; //$this->input->post('fee_type');
        $data['fee_type_id'] = $this->input->post('fee_fee_type_id');
        $data['section_id'] = $this->input->post('section_id');
        $section_id = $data['section_id'];
        $fee_type_id_arr = $data['fee_type_id'];
        $month_year = $this->input->post('month_date');
        $student_id = $data['student_id'];

        $data_d['discount_title'] = $this->input->post('discount_title');
        $discount_title = $data_d['discount_title'];
        $data_d['discount_fee_type_id'] = $this->input->post('discount_fee_type_id');
        $discount_fee_type_id = $data_d['discount_fee_type_id'];
        $data_d['discount_amount'] = $this->input->post('discount_amount');
        $discount_amount = $data_d['discount_amount'];
        $login_by = $_SESSION['login_detail_id'];
        if (empty($discount_amount))
        {
            $discount_amount = array();
        }

        $assign_std_m_fee_settings_id = $this->input->post('assign_std_m_fee_settings_id');

        if ((empty($assign_std_m_fee_settings_id) > 0))
        {
            $assign_std_m_fee_settings_id = array();
        }
        $current_date = date('Y-m-d H:i:s');
        $date_str = "";
        if ($status == 1)
        {
            $date_str = "generation_date = '" . $current_date . "' , generated_by = '".$login_by."'";

        } elseif ($status == 2)
        {
            $date_str = "approval_date = '" . $current_date . "' , approved_by = '".$login_by."'";
        }
        $std_m_fee_settings_id = 0;

        if ($month_year != "")
        {

            $month_year_temp = explode("_", $month_year);
            $data['month'] = $month_year_temp[0];
            $data['year'] = $month_year_temp[1];
            $month = $data['month'];
            $year = $data['year'];

            $query_select_msfs_str = "select * from " . get_school_db() . ".student_monthly_fee_settings where school_id=$school_id AND student_id= $student_id
                                      AND fee_month = $month AND fee_year = $year";
            $query_select_msfs = $this->db->query($query_select_msfs_str)->result_array();

            if (count($query_select_msfs) > 0) {

                $query_update_msfs_fee_str = "UPDATE " . get_school_db() . ".student_monthly_fee_settings
                                       set
                                           status = $status, comments = '" . $comments . "'
                                       WHERE 
                                           fee_month = " . $month . " AND fee_year = " . $year . " AND student_id = $student_id AND school_id = $school_id";
                $query_update_msfs_fee = $this->db->query($query_update_msfs_fee_str);
                $std_m_fee_settings_id = $query_select_msfs[0]['std_m_fee_settings_id'];//$this->db->insert_id();

            }
            else
            {

                $query_insert_msfs_fee_str = "INSERT INTO " . get_school_db() . ".student_monthly_fee_settings
                                       set
                                           fee_month = " . $month . ", fee_year = " . $year . ",
                                           student_id = $student_id, school_id = $school_id,
                                           status = $status, comments = '" . $comments . "', $date_str";
                $query_insert_msfs_fee = $this->db->query($query_insert_msfs_fee_str);
                $std_m_fee_settings_id = $this->db->insert_id();
            }

            if (count($amount) > 0)
            {
                $query_delete_sfs_fee_str = "DELETE FROM " . get_school_db() . ".student_fee_settings where school_id=$school_id AND student_id= $student_id
                                             AND month = $month AND year = $year AND academic_year_id = $section_id AND fee_type = 1 AND is_bulk = 1";
                $query_delete_sfs_fee_str = $this->db->query($query_delete_sfs_fee_str);
                for ($j = 0; $j < count($amount); $j++)
                {
                    $query_insert_sfs_fee_str = "INSERT INTO " . get_school_db() . ".student_fee_settings
                                       set
                                           title = '" . $fee_title[$j] . "',
                                           amount = " . $amount[$j] . ",
                                           academic_year_id = " . $section_id . ",
                                           fee_type = 1,
                                           fee_type_id = $fee_type_id_arr[$j],
                                           settings_type = 2,
                                           is_bulk = 1,
                                           month = " . $month . ",
                                           year = " . $year . ",
                                           student_id = $student_id,
                                           school_id = $school_id,
                                           status = $status,
                                           std_m_fee_settings_id = $std_m_fee_settings_id";
                    $query_insert_sfs_fee = $this->db->query($query_insert_sfs_fee_str);

                }
            }

            if (count($discount_amount) > 0)
            {

                $query_delete_sfs_dis_str = "delete from " . get_school_db() . ".student_fee_settings where school_id=$school_id AND student_id= $student_id 
                                             AND month = $month AND year = $year AND academic_year_id = $section_id AND fee_type = 2 AND is_bulk = 1";
                $query_delete_sfs_dis = $this->db->query($query_delete_sfs_dis_str);

                for ($j = 0; $j < count($discount_amount); $j++)
                {
                    $query_insert_sfs_fee_str = "INSERT INTO " . get_school_db() . ".student_fee_settings
                                       set
                                           title = '" . $discount_title[$j] . "',
                                           amount = " . $discount_amount[$j] . ",
                                           academic_year_id = " . $section_id . ",
                                           fee_type = 2,
                                           fee_type_id = $discount_fee_type_id[$j],
                                           settings_type = 2,
                                           is_bulk = 1,
                                           month = " . $month . ",
                                           year = " . $year . ",
                                           student_id = $student_id,
                                           school_id = $school_id,
                                           status = $status,
                                           std_m_fee_settings_id = $std_m_fee_settings_id";
                    $query_insert_sfs_fee = $this->db->query($query_insert_sfs_fee_str);
                }

            }

            if (count($assign_std_m_fee_settings_id) > 0)
            {
                 for ($k = 0; $k < count($assign_std_m_fee_settings_id); $k++)
                 {
                    $query_update_sfs_discount_str = "UPDATE " . get_school_db() . ".student_fee_settings
                                                   set
                                                       std_m_fee_settings_id = $std_m_fee_settings_id
                                                      WHERE 
                                                       month = " . $month . " AND year = " . $year . " AND student_id = $student_id
                                                       AND school_id = $school_id AND fee_settings_id = $assign_std_m_fee_settings_id[$k]";
                    $query_update_sfs_discount_str = $this->db->query($query_update_sfs_discount_str);
                }
            }

        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }
        
        $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
        redirect(base_url() . 'c_student/student_monthly_fee_setting/' . $data['student_id']);
    }

    function bulk_month_fee_edit()
    {
        $this->db->trans_begin();

        $school_id = $_SESSION['school_id'];
        $data['school_id'] = $school_id;
        $data['status'] = $this->input->post('comments');
        $comments = $data['status'];
        $data['status'] = $this->input->post('status');
        $status = $data['status'];
        $data['student_id'] = $this->input->post('student_id');
        $student_id = $data['student_id'];
        $month_year = $this->input->post('month_date');
        $login_by = $_SESSION['login_detail_id'];
        $current_date = date('Y-m-d H:i:s');

        $date_str = "";
        if ($status == 1) {
            $date_str = "generation_date = '" . $current_date . "' , generated_by = '".$login_by."'";

        } elseif ($status == 2)
        {
            $date_str = "approval_date = '" . $current_date . "' , approved_by = '".$login_by."'";
        }

        if ($month_year != "") {

            $month_year_temp = explode("_", $month_year);
            $data['month'] = $month_year_temp[0];
            $data['year'] = $month_year_temp[1];
            $month = $data['month'];
            $year = $data['year'];
            
            $query_select_msfs_str = "select * from " . get_school_db() . ".student_monthly_fee_settings where school_id=$school_id
                                       AND student_id= $student_id AND fee_month = $month AND fee_year = $year";
            $query_select_msfs = $this->db->query($query_select_msfs_str)->result_array();

            if (count($query_select_msfs) > 0)
            {

                $query_update_msfs_fee_str = "UPDATE " . get_school_db() . ".student_monthly_fee_settings
                                       set
                                           status = $status,
                                           comments = '" . $comments . "',
                                           $date_str
                                       WHERE fee_month = " . $month . " AND fee_year = " . $year . " AND student_id = $student_id AND school_id = $school_id";
                $query_update_msfs_fee = $this->db->query($query_update_msfs_fee_str);
                $std_m_fee_settings_id = $query_select_msfs[0]['std_m_fee_settings_id']; //$this->db->insert_id();

            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }
        
        $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
        redirect(base_url() . 'c_student/student_monthly_fee_setting/' . $data['student_id']);
    }

    function month_fee_edit()
    {
        $this->db->trans_begin();

        $school_id = $_SESSION['school_id'];
        $data['student_id'] = $this->input->post('student_id');
        $student_id = $data['student_id'];
        $data['amount'] = $this->input->post('amount');
        $amount = $data['amount'];

        $data['fee_type'] = 1; //$this->input->post('fee_type');
        $data['fee_type_id'] = $this->input->post('fee_type_id_discount');
        $fee_type_id = $data['fee_type_id'];

        $data['school_id'] = $_SESSION['school_id'];
        $fee_settings_id = $this->input->post('fee_settings_id');

        $data['settings_type'] = $this->input->post('settings_type');
        $data['status'] = $this->input->post('status');

        $month_datepiker = $this->input->post('month_year');
        $month_datepiker_temp = explode("_", $month_datepiker);

        $data['month'] = $month_datepiker_temp[0];
        $data['year'] = $month_datepiker_temp[1];

        $month = $data['month'];
        $year = $data['year'];

        $data['title'] = $this->input->post('title');
        $title = $data['title'];
        if ($title == "") {
            $title_arr = get_title_discount($fee_type_id);
            $data['title'] = $title_arr['discount_title'];
        }

        $query_month_exist_str = "select * from " . get_school_db() . ".student_fee_settings where school_id=$school_id AND student_id= $student_id
                                  AND month = $month AND year = $year AND fee_type = 1 AND fee_type_id = $fee_type_id AND fee_settings_id <> $fee_settings_id";
        $query_month_exist = $this->db->query($query_month_exist_str)->result_array();

        if (count($query_month_exist) > 0) {
            $this->session->set_flashdata('club_updated', get_phrase('month_already_assigned'));
        } else {
            $this->db->where('school_id', $school_id);
            $this->db->where('fee_settings_id', $fee_settings_id);
            $this->db->update(get_school_db() . '.student_fee_settings', $data);
            $this->session->set_flashdata('club_updated', get_phrase('record_updeted_successfully'));
        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();
        }
        redirect(base_url() . 'c_student/student_m_installment/' . $data['student_id']);

    }
    
    function month_discount_add()
    {
        $school_id = $_SESSION['school_id'];
        $data['school_id'] = $school_id;
        $data['title'] = $this->input->post('title');
        $data['academic_year_id'] = $this->input->post('section_id');
        $section_id = $data['academic_year_id'];
        $data['student_id'] = $this->input->post('student_id');
        $data['amount'] = $this->input->post('amount');
        $data['fee_type'] = 2; //$this->input->post('fee_type');
        $data['fee_type_id'] = $this->input->post('fee_type_id_discount');
        $data['settings_type'] = 2; //$this->input->post('settings_type');
       // $data['status'] = $this->input->post('status');
        $data['is_bulk'] = 0;
        $data['discount_amount_type'] = $this->input->post("discount_status");
        
        $percentage = $this->input->post('percentage');
        $month_year = $this->input->post('month_date');
        
        if($percentage == "percentage"){
            $amount = $this->input->post('amount');
        }else{
            $amount = $this->input->post('amount_complete');
            $data['settings_type'] = 3; 
        }
        
        $status_arr = $this->input->post('status_arr');
        $student_id = $data['student_id'];
        $discount_id = $data['fee_type_id'];

        $fee_type_id = $data['fee_type_id'];

        $data['title'] = $this->input->post('title');
        $title = $data['title'];

        if ($title == "")
        {
            $title_arr = get_title_discount($data['fee_type_id']);
            $data['title'] = $title_arr['discount_title'];
        }


        $query_delete_sfs_str = "delete from " . get_school_db() . ".student_fee_settings where school_id=$school_id
                                 AND student_id= $student_id AND fee_type_id = $discount_id AND fee_type = 2";
        $query_delete_sfs = $this->db->query($query_delete_sfs_str);

        if (count(array_filter($amount)) > 0)
        {
            for ($i = 0; $i <= count($amount); $i++)
            {
                if($percentage == "percentage"){
                    if ($amount[$i] != "")
                    {
    
                        $amount_current = $amount[$i];
                        $month_year_temp = explode("_", $month_year[$i]);
                        $data['month'] = $month_year_temp[0];
                        $data['year'] = $month_year_temp[1];
                        $data['std_m_fee_settings_id'] = $month_year_temp[2];
                        //working
                        $pre_amount =  $month_year_temp[3];
                        $is_bulk = $month_year_temp[4];
                        $data['status'] = $status_arr[$i];
    
                        if($amount_current == $pre_amount)
                        {
                            $data['amount'] = $pre_amount;
                            $data['is_bulk'] = $is_bulk;
                        }
                        else
                        {
                            $data['amount'] = $amount_current;
                            $data['is_bulk'] = 0;
                        }
    
                        $month = $data['month'];
                        $year = $data['year'];
    
                        $is_fee_exist = 0;
                        $is_fee_exist = $this->is_fee_exist($month, $year,$student_id,$discount_id);
    
                        if($is_fee_exist>0)
                        {
                            $this->db->insert(get_school_db() . '.student_fee_settings', $data);
                        }
                    }
                }
                else{
                    if ($amount[$i] != ""){
                        $amount_current = $amount[$i];
                        $month_year_temp = explode("_", $month_year[$i]);
                        $data['month'] = $month_year_temp[0];
                        $data['year'] = $month_year_temp[1];
                        $data['std_m_fee_settings_id'] = $month_year_temp[2];
                        //working
                        $pre_amount =  $month_year_temp[3];
                        $is_bulk = $month_year_temp[4];
                        $data['status'] = $status_arr[$i];
    
                        if($amount_current == $pre_amount)
                        {
                            $data['amount'] = $pre_amount;
                            $data['is_bulk'] = $is_bulk;
                        }
                        else
                        {
                            $data['amount'] = $amount_current;
                            $data['is_bulk'] = 0;
                        }
    
                        $month = $data['month'];
                        $year = $data['year'];
    
                        $is_fee_exist = 0;
                        $is_fee_exist = $this->is_fee_exist($month, $year,$student_id,$discount_id);
    
                        if($is_fee_exist>0)
                        {
                            $this->db->insert(get_school_db() . '.student_fee_settings', $data);
                        }
                    }
                }
            }

            $this->session->set_flashdata('club_updated', get_phrase('record_saved_successfully'));
            redirect(base_url() . 'c_student/student_m_installment/' . str_encode($data['student_id']));
        } else {
            $this->session->set_flashdata('club_updated', get_phrase('pleaese_select_at_least_0ne_academic_month'));
            redirect(base_url() . 'c_student/student_m_installment/' . str_encode($data['student_id']));

        }
    }

    function is_fee_exist($month="", $year="",$student_id="",$discount_id="")
    {

        $section_id = get_student_section_id($student_id);
        $school_id = $_SESSION['school_id'];
        $query_delete_sfs_str = "select * from " . get_school_db() . ". discount_list where school_id=$school_id AND discount_id = $discount_id AND status = 1";
        $query_delete_sfs =  $this->db->query($query_delete_sfs_str)->result_array();
        $fees_assigned = 0;
        if(count($query_delete_sfs)>0)
        {

            $fee_type_id = $query_delete_sfs[0]['fee_type_id'];
            $query_fee_check_1_str = "select * from " . get_school_db() . ". student_fee_settings sfs where sfs.school_id=$school_id
                                      AND sfs.fee_type_id = $fee_type_id AND sfs.month = $month AND sfs.year = $year AND sfs.student_id = $student_id
                                      AND settings_type = 2 AND status = 1";
            $query_fee_check_1 =$this->db->query($query_fee_check_1_str)->result_array();

            $query_fee_check_2_str = "SELECT ccd.fee_type_id FROM " . get_school_db() . ".class_chalan_form as ccf inner JOIN
                        " . get_school_db() . ".class_chalan_fee as ccd on ((ccf.c_c_f_id = ccd.c_c_f_id) && (ccf.section_id = $section_id))
                        WHERE ccd.school_id = $school_id AND ccd.fee_type_id = $fee_type_id AND ccf.type = 2 ";
            $query_fee_check_2 =$this->db->query($query_fee_check_2_str)->result_array();

            if(count($query_fee_check_1)>0)
            {
                $fees_assigned = 1;
            }

            if(count($query_fee_check_2)>0)
            {
                $fees_assigned = 1;
            }
           return $fees_assigned;
        }

    }

    function insert_discount_amount($s_c_f_id, $fee_type_id="", $amount="")
    {
        $school_id = $_SESSION['school_id'];

        $query_chalan_form_str = "SELECT 
                                        scd. s_c_d_id,scd.type_id as scd_fee_id,scd.s_c_d_id,ft.fee_type_id as fee_type_id, 
                                        dl.discount_id as discount_id,dl.title as discount_title,dl.issue_dr_coa_id,
                                        dl.issue_cr_coa_id,dl.receive_dr_coa_id,dl.receive_cr_coa_id,dl.cancel_dr_coa_id,
                                        dl.cancel_cr_coa_id,dl.generate_dr_coa_id,dl.generate_cr_coa_id
                                   FROM " . get_school_db() . ".discount_list as dl
                                                INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = dl.fee_type_id
                                                INNER JOIN " . get_school_db() . ".student_chalan_detail as scd on scd.type_id = ft.fee_type_id
                                   WHERE dl.discount_id = $fee_type_id AND scd.s_c_f_id = $s_c_f_id AND dl.school_id = $school_id";

        $query_chalan_form = $this->db->query($query_chalan_form_str)->result_array();

        if (count($query_chalan_form) > 0) {
            $data['s_c_f_id'] = $s_c_f_id;//$query_chalan_form[0]['s_c_f_id'];
            $data['fee_type_title'] = $query_chalan_form[0]['discount_title'];
            $data['school_id'] = $school_id;//$query_chalan_form[0]['fee_type_title'];
            $data['amount'] = $amount;//$query_chalan_form[0][''];
            $data['type'] = 4; //$query_chalan_form[0][''];
            $data['type_id'] = $fee_type_id;//$query_chalan_form[0][''];
            $data['related_s_c_d_id'] = $query_chalan_form[0]['s_c_d_id'];
            $data['issue_dr_coa_id'] = 0;//$query_chalan_form[0][''];
            $data['issue_cr_coa_id'] = $query_chalan_form[0]['issue_cr_coa_id'];
            $data['receive_dr_coa_id'] = $query_chalan_form[0]['receive_dr_coa_id'];
            $data['receive_cr_coa_id'] = 0;//$query_chalan_form[0][''];
            $data['cancel_dr_coa_id'] = 0;//$query_chalan_form[0][''];
            $data['cancel_cr_coa_id'] = 0;//$query_chalan_form[0][''];
            $data['generate_dr_coa_id'] = 0;//$query_chalan_form[0][''];
            $data['generate_cr_coa_id'] = 0; //$query_chalan_form[0][''];
            $this->db->insert(get_school_db() . '.student_chalan_detail', $data);
        }
    }

    function get_selected_amount_discount()
    {
        $school_id = $_SESSION['school_id'];
        $discount_id = $this->input->post('discount_id');
        $fee_type = $this->input->post('fee_type');
        $student_id = $this->input->post('student_id');
        $academic_year = get_student_academic_year($student_id);
        $start_date = date("Y-m-d"); // 2017-04-01
        $month_list = get_month_list($start_date, $academic_year['end_date']);

        ?>
        <fieldset class="custom_legend text_month">
            <legend class="custom_legend"><?php echo get_phrase('academic_months'); ?>:</legend>
            
            <?php
            $i = 0;
            foreach ($month_list as $month_key => $month_value)
            {

                $month_list_temp = explode("_", $month_key);
                $month = $month_list_temp[0];
                $year = $month_list_temp[1];


                $query_save_month_str = "select * from " . get_school_db() . ".student_fee_settings
                                        where 
                                        school_id=$school_id
                                        AND student_id= $student_id
                                        AND month = $month
                                        AND year = $year
                                        AND fee_type_id = $discount_id
                                        AND fee_type = 2";

                $query_save_month = $this->db->query($query_save_month_str)->result_array();
                $amount = 0;
                $is_bulk = 0;
                $selected = "";
                $status = 0;
                $percentage_type = 0;
                if (count($query_save_month) > 0) {
                    $amount = $query_save_month[0]['amount']; //$month_key;
                    $is_bulk = $query_save_month[0]['is_bulk'];
                    $status = $query_save_month[0]['status'];
                    $percentage_type = $query_save_month[0]['settings_type'];
                }
                if($i == 0){
                    ?>
                    <div class="col-sm-4"><?php echo get_phrase('select_discount_type'); ?></div>
                    <div class="col-sm-4"><input type="radio" name="percentage" <?php if($percentage_type == 2 || $percentage_type == 0){echo "checked";} ?> id="percentage" value='percentage'> <strong><?php echo get_phrase('Percentage'); ?></strong></div>
                    <div class="col-sm-4"><input type="radio" name="percentage" <?php if($percentage_type == 3){echo "checked";} ?> id="complete" value="complete"> <strong><?php echo get_phrase('value'); ?></strong>
                    </div>
                    <div class="col-sm-4">&nbsp;</div>
                    <div class="col-sm-4" id="percentage_lable"><strong><?php if($percentage_type == 3){echo get_phrase('Value');} else {echo get_phrase('Percentage');} ?></strong></div>
                    <div class="col-sm-4"><strong><?php echo get_phrase('status'); ?></strong></div>
                    <?php 
                }

                if($status>0)
                {
                   $selected = "selected";
                }

               $assigned_months = assigned_month_year_add($student_id, $month, $year, $discount_id);
               $assigned = "";
               $required = "";
               $std_m_fee_settings_id = $this->get_monthly_fee_settings_id($month,$year,$student_id);

                if ($assigned_months == 1)
                {
                    $required = "required";
                }
                ?>

                <div id="icon" class="loader_small" style="text-align: center; display:none; position: relative;">
                    &nbsp;
                </div>
                <div class="row">
                <div class="col-sm-12 month_listing">
                    <label for="sort" class="col-sm-3 control-label" style="margin: 5px 0;"> <?php echo $month_value . $assigned; ?></label>
                    <div class="col-sm-5">
                        <?php /* <select class="form-control m_select"   name="amount[]" id="month_year_<?php echo $discount_id.'_'.$month.'_'.$year; ?>"> */ ?>
                        <select class="form-control m_select type_select" id="<?php echo $month_key; ?>"
                                name="amount[]" <?php echo $required;  if($percentage_type == 3){echo "style='display:none'";}else{ echo 'style="display:block;"';} ?>>
                            <?php echo get_percentage_range($amount); ?>
                        </select>
                        <?php $query_save_month[$i]['amount'];?>
                        <input maxlength="200" type="text" id="<?php echo $month_key; ?>" <?php echo $required; ?> class="<?php echo $month_key; ?>1 form-control m_select type_field" <?php if($percentage_type == 3){echo "style='display:block'";}else{ echo 'style="display:none;"';}?> name="amount_complete[]" value="<?php echo $query_save_month[$i]['amount'];?>">
                    </div>
                
                    <div class="col-sm-4">
                        <select class="form-control m_select <?php echo $month_key; ?>" name="status_arr[]">

                            <option value=""><?php echo get_phrase('select'); ?></option>
                            <option value="1" <?php echo $selected; ?>><?php echo get_phrase('active'); ?></option>
                            <option value="2"><?php echo get_phrase('inactive'); ?></option>
                        </select>
                    </div>
                     <?php 
                        if($i == 0){
                        echo '  <div class="row">
                                    <div class="black2 col-md-12" style="padding-left: 15px;margin-top: -14px;color: #036;font-weight: 600;margin-top: 6px;margin-bottom: 6px;margin-left: 15px;">
                                        <input type="checkbox" name="sameabove"  id="sameabove" value="'.$month_key.'">
                                        <em> Same as Above.</em>
                                    </div>
                                </div>';
                        }
                        $i++;
                    ?>
                    <input type="hidden" name="month_date[]" value="<?php echo $month_key."_".$std_m_fee_settings_id."_".$amount."_".$is_bulk; ?>">
                </div>
                </div>
                <?php
            }
            ?>
            <input type="hidden" name="month_count" id="month_count" value="<?php echo $i; ?>">
            <input type="hidden" class="discount_statut_amount_type" name="discount_status" value="0">
        </fieldset>
    <?php
    }
    
    function get_monthly_fee_settings_id($month , $year , $student_id)
    {
        $school_id =$_SESSION['school_id'];
        $query_msfs_str = "SELECT * FROM " . get_school_db() . ".student_monthly_fee_settings WHERE fee_month = $month AND fee_year = $year
                           AND student_id = $student_id AND school_id = $school_id";
        $query_msfs = $this->db->query($query_msfs_str)->result_array();

        $std_m_fee_settings_id = 0;
        if(count($query_msfs)>0)
        {
            $std_m_fee_settings_id = $query_msfs[0]['std_m_fee_settings_id'];
            return $std_m_fee_settings_id;
        }
        return $std_m_fee_settings_id;


    }
    function get_selected_amount_fee()
    {

        $school_id = $_SESSION['school_id'];
        $discount_id = $this->input->post('discount_id');
        $fee_type = $this->input->post('fee_type');

        $student_id = $this->input->post('student_id');
        $section_id = $this->input->post('section_id');
        $academic_year = get_student_academic_year($student_id);
        $start_date = date("Y-m-d"); // 2017-04-01
        $month_list = get_month_list($start_date, $academic_year['end_date']);

        ?>
        <fieldset class="custom_legend text_month">
            <legend class="custom_legend"><?php echo get_phrase('academic_months'); ?>:</legend>
            <div id="icon" class="loader_small" style="text-align: center; display:none; position: relative;">&nbsp;</div>

            <div class="col-sm-4"><?php echo get_phrase('months'); ?></div>
            <div class="col-sm-4"><strong><?php echo get_phrase('amount'); ?></strong></div>
            <!--<div class="col-sm-3"><strong><?php //echo get_phrase('settings_type'); ?></strong></div>-->
            <div class="col-sm-4"><strong><?php echo get_phrase('status'); ?></strong></div>


            <?php
            $month_counter = 0;
            foreach ($month_list as $month_key => $month_value) {
                $month_list_temp = explode("_", $month_key);
                $month = $month_list_temp[0];
                $year = $month_list_temp[1];

                $query_save_month_str = "select * from " . get_school_db() . ".student_fee_settings where school_id=$school_id AND student_id= $student_id
                                         AND month = $month AND year = $year AND fee_type_id = $discount_id AND fee_type = 1";
                $std_m_fee_settings_id = $this->get_monthly_fee_settings_id($month,$year,$student_id);

                $query_save_month = $this->db->query($query_save_month_str)->result_array();
                $amount = "";
                $assigned = "";
                $required = "";
                $is_bulk = 0;
                $selected_individual =  "";
                $selected_combined = "";
                $settings_type = "";
                if (count($query_save_month) > 0) {
                    $amount = $query_save_month[0]['amount']; //$month_key;
                    $required = "required";
                    $is_bulk = $query_save_month[0]['is_bulk'];
                }

                if($query_save_month[0]['status'] == 1)
                {
                    $selected = "selected";
                }

                if($query_save_month[0]['settings_type'] == 1)
                {
                    $selected_individual = "selected";
                }

                if($query_save_month[0]['settings_type'] == 2)
                {
                    $selected_combined = "selected";
                }
                
                $disabled_input = '';
                $disabled_class = '';
                $err_msg = "";
                $check_fee_type_exist_in_month = check_fee_type_exist_in_month($section_id,$discount_id);
                if($check_fee_type_exist_in_month == 1)
                {
                    $disabled_class = "disable_month_row";
                    $disabled_input = "disabled";
                    $err_msg = "<span class='text-danger'>This Type Of Fee Already Has Been Added In Class Chalan Form</span>";
                    echo "<style>
                        #btnSubmit
                        {
                            display:none;
                        }
                    </style>";
                }

                ?>
                <style>
                    .disable_month_row {
                        opacity: 0.2;
                        cursor: no-drop;
                        transition: all 0.50s ease-in-out;
                    }
                </style>
                <?= $err_msg ?>
                <div class="col-sm-12 month_listing <?= $disabled_class ?>">

                    <label for="sort" class="col-sm-4 control-label" style="margin: 5px 0;"> <?php echo $month_value . $assigned; ?></label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control m_select <?=$month_key?>" name="amount[]" min="-1" id="amount_<?php echo $month_key; ?>" value="<?php echo $amount; ?>" <?php echo $required; ?> <?= $disabled_input ?>>
                        <input type="hidden" value="2" name="settings_type_arr[]">
                    </div>

                    <!--<div class="col-sm-3">-->
                        <!--<select class="form-control m_select" name="settings_type_arr[]" <?= $disabled_input ?>>-->
                        <!--    <option value=""><?php echo get_phrase('select'); ?></option>-->
                        <!--    <option value="1" <?php echo $selected_individual; ?>><?php echo get_phrase('individual'); ?></option>-->
                        <!--    <option value="2"  <?php echo $selected_combined; ?>><?php echo get_phrase('combined'); ?></option>-->
                        <!--</select>-->
                    <!--</div>-->

                    <div class="col-sm-4">

                        <select class="form-control m_select status_<?=$month_key?>" name="status_arr[]" <?= $disabled_input ?>>
                            <option value=""><?php echo get_phrase('select'); ?></option>
                            <option value="1" <?php if($selected != ""){ ?> selected <?php } ?> >  <?php echo get_phrase('active'); ?></option>
                            <option value="2"><?php echo get_phrase('inactive'); ?></option>
                        </select>
                    </div>
                    <?php 
                        if($month_counter == 0){
                        echo '  <div class="row">
                                    <div class="black2 col-md-12" style="padding-left: 15px;margin-top: -14px;color: #036;font-weight: 600;margin-top: 6px;margin-bottom: 6px;margin-left: 15px;">
                                        <input type="checkbox" name="sameabove"  id="sameabove" value="'.$month_key.'">
                                        <em> Same as Above.</em>
                                    </div>
                                </div>';
                        }
                        $month_counter++;
                    ?>
                    <div class="clear-both" style="clear: both;"></div>
                    <hr style="border: 1px solid #ebebeb;">
                    <input type="hidden" name="month_date[]" value="<?php echo $month_key."_".$std_m_fee_settings_id."_".$amount."_".$is_bulk; ?>">
                </div>

                <script type="text/javascript">
                    $("#amount_<?php //echo $month_key; ?>").attr({
                        min: -1
                    });

                </script>
                <?php
            }
            ?>
            <input type="hidden" name="month_count" id="month_count" value="<?php echo $month_counter; ?>">
            </table>
        </fieldset>
        <?php
    }

    function month_discount_edit()
    {
        $school_id = $_SESSION['school_id'];
        $data['student_id'] = $this->input->post('student_id');
        $student_id = $data['student_id'];
        $data['amount'] = $this->input->post('amount');
        $amount = $data['amount'];
        $discount_amount_type = $this->input->post("choose_discount_type");
        $data['discount_amount_type'] = $discount_amount_type;
        $data['fee_type'] = 2; //$this->input->post('fee_type');
        $data['fee_type_id'] = $this->input->post('fee_type_id_discount');
        echo $fee_type_id = $data['fee_type_id'];
        $data['school_id'] = $_SESSION['school_id'];
        $fee_settings_id = $this->input->post('fee_settings_id');

        $data['settings_type'] = 2; //$this->input->post('settings_type');
        $data['status'] = $this->input->post('status');

        $month_datepiker = $this->input->post('month_year');

        $month_datepiker_temp = explode("_", $month_datepiker);

        $data['month'] = $month_datepiker_temp[0];
        $data['year'] = $month_datepiker_temp[1];

        $month = $data['month'];
        $year = $data['year'];

        $data['title'] = $this->input->post('title');
        $title = $data['title'];
        if ($title == "") {
            $title_arr = get_title_discount($fee_type_id);
            $data['title'] = $title_arr['discount_title'];
        }
        
        $query_month_exist_str = "select * from " . get_school_db() . ".student_fee_settings where school_id=$school_id
                                  AND student_id= $student_id AND month = $month AND year = $year AND fee_type = 2 AND fee_type_id = $fee_type_id AND fee_settings_id <> $fee_settings_id";

        $query_month_exist = $this->db->query($query_month_exist_str)->result_array();

        if (count($query_month_exist) > 0) {

            $this->session->set_flashdata('club_updated', get_phrase('month_already_assigned'));
            redirect(base_url() . 'c_student/student_m_installment/' . $data['student_id']);
        } else {

            $assigned_months = assigned_month_year_add($student_id, $month, $year, $fee_type_id);
            $this->db->where('school_id', $school_id);
            $this->db->where('fee_settings_id', $fee_settings_id);
            $this->db->update(get_school_db() . '.student_fee_settings', $data);
            
            $query_s_c_f_str = "select * from " . get_school_db() . ".student_chalan_form where school_id=$school_id
                                AND student_id= $student_id AND s_c_f_month = $month AND s_c_f_year = $year AND form_type = 2";
            $query_s_c_f = $this->db->query($query_s_c_f_str)->result_array();

            if (count($query_s_c_f) > 0) {

                $s_c_f_id = $query_s_c_f[0]['s_c_f_id'];
                $month_temp = $query_s_c_f[0]['s_c_f_month'];
                $year_temp = $query_s_c_f[0]['s_c_f_year'];
                $actual_amount = $query_s_c_f[0]['actual_amount'];

                $query_s_c_f_d_str = "select * from " . get_school_db() . ".student_chalan_detail
                                      where school_id=$school_id AND type = 4 AND type_id = $fee_type_id AND s_c_f_id = $s_c_f_id";
                $query_s_c_f_d = $this->db->query($query_s_c_f_d_str)->result_array();

                if (count($query_s_c_f_d) > 0) {

                    foreach ($query_s_c_f_d as $value_s_c_f_d) {

                        $s_c_d_id = $value_s_c_f_d['s_c_d_id'];
                        $query_update_s_c_f_d_str = "update " . get_school_db() . ".student_chalan_detail  set amount = $amount , type_id = $fee_type_id where school_id =$school_id and s_c_d_id= $s_c_d_id";
                        $query_update_s_c_f_d = $this->db->query($query_update_s_c_f_d_str);
                        $this->update_chalan_form($s_c_f_id, $fee_type_id, $amount, $delete = 0);
                    }
                }
            }
            $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
            redirect(base_url() . 'c_student/student_m_installment/' . $data['student_id']);
        }
    }

    function update_chalan_form($s_c_f_id, $fee_type_id, $amount, $is_delete)
    {

        $school_id = $_SESSION['school_id'];
        $query_discount_str = "SELECT scd.type_id as scd_fee_id , scd.s_c_d_id, scd.amount as scd_amount, ft.fee_type_id as fee_type_id  FROM " . get_school_db() . ".discount_list as dl 
                                INNER JOIN " . get_school_db() . ".fee_types as ft
                                on ft.fee_type_id = dl.fee_type_id
                                inner JOIN " . get_school_db() . ".student_chalan_detail as scd on scd.type_id = ft.fee_type_id
                                where dl.discount_id = $fee_type_id AND scd.s_c_f_id = $s_c_f_id AND scd.school_id = $school_id";
        $query_discount = $this->db->query($query_discount_str)->result_array();

        $discount_amount = 0;
        $total_amount = 0;

        if (count($query_discount) > 0) {
            $discount_amount = $query_discount[0]['scd_amount'];
            $discount_amount = round(($discount_amount) * ($amount / 100));
        }

        $query_get_amount_str = "SELECT sum(amount) as amount  FROM " . get_school_db() . ".student_chalan_detail
                        WHERE s_c_f_id = $s_c_f_id and type = 1 and school_id = $school_id and type_id != $fee_type_id";
        $query_get_amount = $this->db->query($query_get_amount_str)->result_array();

        if (count($query_get_amount) > 0) {
            $total_amount = $query_get_amount[0]['amount'];
            if ($is_delete == 0) {
                $total_amount = $total_amount - $discount_amount;
            } else {
                $total_amount = $total_amount + $discount_amount;
            }
        }

        $query_update_chalan_form_str = "update " . get_school_db() . ".student_chalan_form  set actual_amount = $total_amount where school_id =$school_id  and s_c_f_id= $s_c_f_id";
        $query_update_chalan_form = $this->db->query($query_update_chalan_form_str);
    
    }

    function individual_month_fee_edit()
    {
        $this->db->trans_begin();

        $school_id = $_SESSION['school_id'];
        $data['school_id'] = $school_id;
        $data['status'] = $this->input->post('comments');
        $comments = $data['status'];
        $data['status'] = $this->input->post('status');
        $status = $data['status'];
        $data['student_id'] = $this->input->post('student_id');
        $student_id = $data['student_id'];
        $month_year = $this->input->post('month_date');
        $section_id = $this->input->post('section_id');
        $current_date = date('Y-m-d H:i:s');
        $date_str = "";

        if ($status == 2) {
            $date_str = "approval_date = '" . $current_date . "'";
        } elseif ($status == 3) {
            $date_str = "issue_date = '" . $current_date . "'";
        } else {
            $date_str = "generation_date = '" . $current_date . "'";
        }
        $login_by = $_SESSION['login_detail_id'];

        if ($month_year != "") {

            $month_year_temp = explode("_", $month_year);
            $data['month'] = $month_year_temp[0];
            $data['year'] = $month_year_temp[1];
            $month = $data['month'];
            $year = $data['year'];
            $query_select_msfs_str = "select * from " . get_school_db() . ".student_monthly_fee_settings where school_id=$school_id AND student_id= $student_id AND fee_month = $month AND fee_year = $year";
            $query_select_msfs = $this->db->query($query_select_msfs_str)->result_array();

            if (count($query_select_msfs) > 0) {

                $std_m_fee_settings_id = $query_select_msfs[0]['std_m_fee_settings_id'];
                $query_update_msfs_fee_str = "UPDATE " . get_school_db() . ".student_monthly_fee_settings set status = $status, comments = '" . $comments . "'
                                              WHERE  fee_month = " . $month . "  AND fee_year = " . $year . "  AND student_id = $student_id AND school_id = $school_id AND std_m_fee_settings_id = $std_m_fee_settings_id";
                $query_update_msfs_fee = $this->db->query($query_update_msfs_fee_str);

            }
            
        }
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        $this->session->set_flashdata('club_updated', get_phrase('record_updated_successfully'));
        redirect(base_url() . 'c_student/student_monthly_fee_setting/' . $data['student_id']);
    }
    
    function student_status_report()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        $page_data['page_name'] = 'student_status_report';
        $page_data['page_title'] = get_phrase('student_status_report');
        $this->load->view('backend/index', $page_data);
    }
    
    function get_student_admission_status_report()
    {
        if ($_SESSION['user_login'] != 1)
            redirect('login');
        
        $adm_status = $this->input->post('status_id', TRUE);
        $apply_filter = $this->input->post('apply_filter', TRUE);
        $std_search = $this->input->post('std_search', TRUE);
        $std_search = trim(str_replace(array("'", "\""), "", $std_search));
        $section_id = $this->input->post('section_id', TRUE);
        $dept_id    = $this->input->post('dept_id', TRUE);
        $class_id   = $this->input->post('class_id', TRUE);

        $std_query = "";
        if (isset($std_search) && $std_search != "") {
            $std_query = " AND (
                            s.name LIKE '%" . $std_search . "%' OR 
                            s.address LIKE '%" . $std_search . "%' OR 
                            s.phone LIKE '%" . $std_search . "%' OR 
                            s.email LIKE '%" . $std_search . "%' OR 
                            s.roll LIKE '%" . $std_search . "%' OR 
                            s.form_num LIKE '%" . $std_search . "%' OR 
                            s.adm_date LIKE '%" . $std_search . "%' OR 
                            s.id_no LIKE '%" . $std_search . "%' OR 
                            s.mob_num LIKE '%" . $std_search . "%' OR 
                            s.emg_num LIKE '%" . $std_search . "%' OR 
                            s.bd_group LIKE '%" . $std_search . "%' OR 
                            s.disability LIKE '%" . $std_search . "%' OR 
                            s.reg_num LIKE '%" . $std_search . "%' OR 
                            s.system_id LIKE '%" . $std_search . "%' OR 
                            s.p_address LIKE '%" . $std_search . "%'
                            )";
        }

        if (!isset($section_id) || $section_id == "") {
            $section_id = $this->uri->segment(3);
        }
        if (!isset($section_id) || $section_id == "") {
            $section_id = 0;
        }
        if (!isset($dept_id) || $dept_id == "") {
            $dept_id = 0;
        }
        if (!isset($class_id) || $class_id == "") {
            $class_id = 0;
        }

        $where_filter = "";
        if ($section_id > 0) {
            $where_filter .= " AND cs.section_id=" . $section_id;
        }
        if ($class_id > 0) {
            $where_filter .= " AND cs.class_id=" . $class_id;
        }
        if ($dept_id > 0) {
            $where_filter .= " AND d.departments_id=" . $dept_id;
        }

        $quer = "select  s.*, cs.title as section_name, cc.name as class_name, d.title as department_name
                    from " . get_school_db() . ".student s 
                    inner join " . get_school_db() . ".class_section cs on cs.section_id=s.section_id
                    inner join " . get_school_db() . ".class cc on cc.class_id=cs.class_id 
                    inner join " . get_school_db() . ".departments d on d.departments_id=cc.departments_id 
                    where s.school_id=" . $_SESSION['school_id'] . " " . $where_filter . " " . $std_query . " 
					and s.student_status = '$adm_status' ";
        $students_count = $this->db->query($quer)->result_array();
        $page_data['apply_filter'] = $apply_filter;
        $page_data['section_id']   = $section_id;
        $page_data['dept_id']      = $dept_id;
        $page_data['class_id']     = $class_id;
        $page_data['std_search']   = $std_search;
        $page_data['students']     = $students_count;
        $page_data['controller']   = 's';
        $page_data['status']       = $adm_status;
        $page_data['page_name']    = 'student_status_report';
        $page_data['page_title']   = get_phrase('student_status_report');
        $this->load->view('backend/index', $page_data);
    }
    
    function move_to_student($std_id)
    {
        $data['student_status'] = 10;
        
        $this->db->where('student_id', $std_id);
        $this->db->where('school_id', $_SESSION['school_id']);
        $this->db->update(get_school_db() . '.student', $data);
        
        $this->session->set_flashdata('club_updated', get_phrase('candidate_moved_to_student_successfully'));
        redirect(base_url() . 'c_student/student_pending/');
    } 
    function students_birthday()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $apply_filter = $this->input->post('apply_filter');
        $condition = "";
        if($start_date != "" || $end_date != "")
        {
            $start_month_day = date("m-d",strtotime($start_date));
            $end_month_day = date("m-d",strtotime($end_date));
            
            $condition = "AND DATE_FORMAT(birthday, '%m-%d') BETWEEN '$start_month_day' AND '$end_month_day' ";
        } else
        {
            $condition = "AND DATE_FORMAT(birthday, '%m-%d') =  DATE_FORMAT(CURDATE(), '%m-%d')";
        }
        
        $qur = $this->db->query("select s.* , sec.title from ".get_school_db().".student s
        inner join ".get_school_db().".class_section sec on s.section_id = sec.section_id
        where s.school_id=".$_SESSION['school_id']." $condition ")->result();

  	    $page_data['start_date']= $start_date;
  	    $page_data['end_date']= $end_date;
  	    $page_data['apply_filter']= $apply_filter;
  		$page_data['student_data']= $qur;    
        $page_data['page_name']    = 'students_birthday';
        $page_data['page_title']   = get_phrase('students_birthday');
        $this->load->view('backend/index', $page_data);
    }
    function wish_birthday() 
    {
        $this->load->helper('message');
        $student_id = $this->input->post('student_id');

        for ($i = 0; $i < count($student_id); $i++ ) {
            $student_info =  get_student_info($student_id[$i]);
            $mob_num      =  $student_info[0]['mob_num'];
            $std_name      =  $student_info[0]['student_name'];
            $message      = "I wish nothing but good things on your birthday. May the shine bright for you. Wishing you a wonderful day and all the most amazing things on your Birthday! Happy Birthday " . $std_name;
            if($mob_num != "" || !empty($mob_num))
            {
                send_sms($mob_num, 'INDICI EDU', $message, $student_id[$i],00);
            }
        }
        
        $this->session->set_flashdata('club_updated', get_phrase('birthday_wishes_send_successfully'));
        redirect(base_url() . 'c_student/students_birthday/');
    }
    function staff_birthday()
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $apply_filter = $this->input->post('apply_filter');
        $condition = "";
        if($start_date != "" || $end_date != "")
        {
            $start_month_day = date("m-d",strtotime($start_date));
            $end_month_day = date("m-d",strtotime($end_date));
            
            $condition = "AND DATE_FORMAT(dob, '%m-%d') BETWEEN '$start_month_day' AND '$end_month_day' ";
        } else
        {
            $condition = "AND DATE_FORMAT(dob, '%m-%d') =  DATE_FORMAT(CURDATE(), '%m-%d')";
        }
        
        $qur = $this->db->query("select s.* from ".get_school_db().".staff s
        where s.school_id=".$_SESSION['school_id']." $condition ")->result();

  	    $page_data['start_date']= $start_date;
  	    $page_data['end_date']= $end_date;
  	    $page_data['apply_filter']= $apply_filter;
  		$page_data['staff_birthday']= $qur;    
        $page_data['page_name']    = 'staff_birthday';
        $page_data['page_title']   = get_phrase('staff_birthday');
        $this->load->view('backend/index', $page_data);
    }
    function wish_staff_birthday() 
    {
        $this->load->helper('message');
        $staff_id = $this->input->post('staff_id'); 
        for ($i = 0; $i < count($staff_id); $i++ ) {
            $staff_info =  get_staff_detail($staff_id[$i]);
            $mob_num      =  $staff_info[0]['mobile_no'];
            $staff_name      =  $staff_info[0]['name'];
            $message      = "I wish nothing but good things on your birthday. May the shine bright for you. Wishing you a wonderful day and all the most amazing things on your Birthday! Happy Birthday " . $staff_name;
            if($mob_num != "" || !empty($mob_num))
            {
                send_sms($mob_num, 'INDICI EDU', $message, $staff_id[$i],00);
            }
        } 
        $this->session->set_flashdata('club_updated', get_phrase('birthday_wishes_send_successfully'));
        redirect(base_url() . 'c_student/staff_birthday/');
    }
    
    
    function diaries()
    {
        $sections = $this->db->query("SELECT * FROM ".get_school_db().".class_section WHERE school_id = '".$_SESSION['school_id']."'")->result_array();
        $page_data['sections']    = $sections;
        $page_data['page_name']    = 'diaries';
        $page_data['page_title']   = get_phrase('diaries');
        $this->load->view('backend/index', $page_data);
    }
    
    function get_section_subjects()
    { 
        $data = "";
        $section_id  = $this->input->post('section_id');
        	$query = "select s.* FROM ".get_school_db().".subject s 
            inner join ".get_school_db().".subject_section SS on SS.subject_id = s.subject_id
            inner join ".get_school_db().".class_section cs on cs.section_id = SS.section_id
            inner join ".get_school_db().".class on class.class_id = cs.class_id
            inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
            where 
            s.school_id=".$_SESSION['school_id']."
            and SS.section_id = $section_id
            group by s.subject_id
            ";
    $subject_arr = $this->db->query($query)->result_array();
    
    
        if(count($subject_arr) > 0)
        {
            foreach($subject_arr as $row)
            {
                $data .= '<li> <span onclick="getsubjectdiary('.$row['subject_id'].','.$section_id.')">
                <a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#subjects_diary'.$row['subject_id'].'" aria-expanded="true" aria-controls="subjects">
                <i class="collapsed"><i class="fas fa-folder"></i></i>
                <i class="far fa-file"></i> '.$row['name'].' </span></a> 
                    <div id="subjects_diary'.$row['subject_id'].'" class="collapse">
                    <input type="text" class="form-control" style="margin-top: 10px;" id="myInput" onkeyup="search_diary('.$row['subject_id'].')" placeholder="Search for diary..">
                        <ul class="subjects_diary'.$row['subject_id'].'">
                            
                        </ul>
                    </div>
                </li>';
            }
        }else
        {
            $data .= '<li> <span><i class="far fa-file"></i> No Subject found </span> </li>';
        }    
        echo $data;
    }
    
    function getsubjectdiary() { 
        $data = ""; 
        $subject_id  = $this->input->post('subject_id');
        $section_id  = $this->input->post('section_id');
        $qur = "SELECT * from ".get_school_db().".diary WHERE subject_id = $subject_id AND section_id = $section_id "; 
        $arr = $this->db->query($qur)->result_array(); 

        if(count($arr) > 0)
        {
            foreach($arr as $row)
            {
                $data .= '<li onclick="get_diary_data('.$row['diary_id'].')"><span><i class="far fa-file"></i> '.$row['title'].'- ('.date_view($row['assign_date']).') </span> </li>';
            }
        }else
        {
            $data .= '<li> <span><i class="far fa-file"></i> No diary found </span> </li>';
        }    
        echo $data;
       
    }
    
    function get_diary_data()
    {
        $data = "";
        
        $diary_id  = $this->input->post('diary_id');
        $qur = "SELECT d.*,aud.*, d.diary_id AS d_id,sub.name as subject_name, sub.code as subject_code from ".get_school_db().".diary d  
                INNER JOIN ".get_school_db().".subject sub ON sub.subject_id = d.subject_id
                LEFT JOIN ".get_school_db().".diary_audio aud ON aud.diary_id = d.diary_id
                WHERE d.diary_id =  $diary_id AND d.school_id =  ".$_SESSION['school_id']." ";
        
        $diary_arr = $this->db->query($qur); 
        $arr = $diary_arr->row(); 
        if($diary_arr->num_rows() > 0) {
        $data .= '<table class="table m-0">
                    <tbody>
                        <tr>
                          <td> 
                            <b>Task Title:</b>
                            <br> '.$arr->title.'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Task Detail:</b>
                            <br> '.$arr->task.'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Teacher Name:</b>
                            <br> '.get_teacher_name($arr->teacher_id).'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Assign Date:</b>
                            <br> '.date_view($arr->assign_date).'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Due Date:</b>
                            <br> '.date_view($arr->due_date).'
                          </td> 
                        </tr>
                        <tr>
                          <td>
                            <b>Submission Details:</b><br>';
                            if ($arr->is_assigned == 1)
                            {
                                $data .='<a href="'.base_url().'teacher/assignment_details/'.$arr->d_id.'" target="_blank">Click to see assignment details </a>';
                            }else{
                                $data .='Not Assigned'; 
                            }
                            $diary_assign_modal = "showAjaxModal('".base_url()."modal/popup/modal_diary_student/".$arr->section_id."/".$arr->d_id."/".$arr->subject_name."-".$arr->subject_code."')";
                            $diary_audio_modal = "showAjaxModal('".base_url()."modal/popup/modal_diary_audio/".$arr->section_id."/".$arr->d_id."')";
                            $diary_delete_modal = "confirm_modal('".base_url()."teacher/diary/delete/".$arr->section_id."/".$arr->d_id."')";
                            $diary_approve = "confirm_modal('".base_url()."c_student/diary_approve/".$arr->d_id."')";
                        $data .= '</td> 
                        </tr>';
                        if ($arr->audio != ""){
                            $audio_path = base_url()."uploads/".$_SESSION['folder_name']."/diary_audios/".$arr->audio;
                            $data .= '<tr><td>
                                    <b>Audio</b><br>
                                    <audio controls>
                                      <source src="'.$audio_path.'" type="audio/ogg">
                                      <source src="'.$audio_path.'" type="audio/mpeg">
                                    </audio></td></tr>';
                        }
                        if ($arr->attachment != ""){
                            $attachment_path = base_url()."uploads/".$_SESSION['folder_name']."/diary/".$arr->attachment;
                            $data .= '<tr><td>
                                    <b>Diary Attachment</b><br>
                                    <a target="_blank" href="'.$attachment_path.'"><i class="fas fa-paperclip"></i> Click to open the link </a>
                                    </td></tr>';
                        }
                        
                        $data .=  '</td> 
                        </tr>
                        <tr><td>';
                            if (get_diary_approval_method() == 2){
                                if($arr->admin_approvel == '0'):
                                    $data .= '<a style="" class="btn btn-primary btn-sm" href="#" onclick="'.$diary_approve.'">
                                            <i class="entypo-eye"></i>
                                            Approve 
                                        </a>&nbsp;&nbsp;';
                                else:
                                    $data .= '<a style="" class="btn btn-success btn-sm" href="#">
                                        <i class="entypo-eye"></i>
                                        Approved
                                    </a>&nbsp;&nbsp;';
                                endif;    
                            }
                                
                                $data .= '<a class="btn btn-primary btn-sm" href="'.base_url().'teacher/edit_diary/'.$arr->d_id.'/'.$_SESSION['school_id'].'">
                                    <i class="entypo-pencil"></i>
                                    Edit
                                </a>&nbsp;&nbsp;';
                                $data .= '&nbsp;&nbsp;
                                <a href="#" class="modal_cancel_btn" onclick="'.$diary_delete_modal.'">
                                    <i class="entypo-trash"></i>
                                    Delete
                                </a>&nbsp;&nbsp;';
                        if ($arr->is_assigned == 0) {        
                            
                            if ($arr->audio == ""){    
                            $data .= '<a style="" class="modal_save_btn" href="#" onclick="'.$diary_audio_modal.'">
                                    <i class="fas fa-microphone" style="font-size: 15px;"></i>
                                    Add Audio
                                </a>';
                            }
                        }else{
                            $data .= '<a class="btn btn-primary btn-sm" href="'.base_url().'teacher/edit_diary/'.$arr->diary_id.'/'.$_SESSION['school_id'].'">
                                    <i class="entypo-eye"></i>
                                    View
                                </a>';
                        }
                        
                        
                            $data .= '</td>
                        </tr>
                    </tbody>
                    </table> '; 
                    

        }else
        {
            
           $data = '';
        }
        echo $data;
    }
    
    function diary_approve($diary_id = 0)
    {
        $q = $this->db->where("diary_id",$diary_id)->update(get_school_db().".diary",array("admin_approvel" => '1'));
        
        if($q)
        {
            $st = $this->db->select("student_id")->where("diary_id",$diary_id)->get(get_school_db().".diary_student")->result_array();
            $data_array = $this->db->select("teacher_id,section_id,subject_id")->where("diary_id",$diary_id)->get(get_school_db().".diary")->result_array();
           
            $teacher_id = $data_array[0]['teacher_id'];
            $section_id = $data_array[0]['section_id'];
            $subject_id = $data_array[0]['subject_id'];
            
            $teacher_name = get_teacher_name($teacher_id);
            $class_idd = get_class_id($section_id);
            $class_name = get_class_name($class_idd);
           
           $sect_name = get_section_name($section_id);
            
            echo $sect_name;
             foreach($st as $key => $value):
                 
                    // $sect_name = get_section_name($row['section_id']);
                    $parent_idd = get_parent_idd($value['student_id']);   
                    $stdname    =    get_student_info($value['student_id']);
                   
                    // // print_r($stdname);
                    $std_name   = $stdname[0]['student_name'];
                   
                    
                   
                    $d=array(
                    'title'=>$teacher_name,
                    'body'=>$subject_name.' diary added for '.$std_name.' '.$class_name,
                    );
                     $d2 = array(
                    'screen'=>'diary',
                    'student_id'=>$value['student_id'],
                    'section_id'=>$section_id,
                    'selectedSubject'=>'0',
                    'startDate'=>'',
                    'endDate'=>'',
                    );
                   
                       
                    if($parent_idd)
                    {
                        $get_isUserLogin = "SELECT islogin from indicied_indiciedu_production.mobile_device_id where user_login_id = $parent_idd";
                        $isUserLogin = $this->db->query($get_isUserLogin)->row();
                        if($isUserLogin->islogin == 1)
                        {
                             notify($d,$d2,$parent_idd);
                        }
                       
                    
                    }
                   
               
            endforeach;    
            
            
        }
        
        
        if($q):
            $std = $this->db->select("student_id")->where("diary_id",$diary_id)->get(get_school_db().".diary_student")->result();
            foreach($std as $row):
                $value = $row->student_id;
                $device_id  =   get_user_device_id(6 , $value , $_SESSION['school_id']);
                $title      =   "Diary Assigned";
                $message    =   "A New Diary has been Assigned.";
                $link       =    base_url()."student_p/diary";
                sendNotificationByUserId($device_id, $title, $message, $link , $value , 6);
            endforeach;    
            
            $this->session->set_flashdata('club_updated', get_phrase('diary_approved_successfully'));
            redirect(base_url() . 'c_student/diaries/');    
        endif;    
    }
    
}