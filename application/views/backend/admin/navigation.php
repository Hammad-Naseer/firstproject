  <?php
    if($_SESSION['login_type'] == 2)
    {
        $this->load->view('backend/admin/branch_navigation');
    }else{
  ?>
<div class="sidebar-menu-inner mainStats">
  <header class="logo-env">
    <div class="logo text-center py-4">
      <a href="<?php echo base_url()."admin/dashboard"?>">
        <?php $logo=system_path($_SESSION['school_logo']);if($_SESSION['school_logo']==""||!is_file($logo)){ ?>
        <div class="logoFancy"><img class="row__poster"src="<?php echo base_url(); ?>assets/images/gsims_logo.png"></div>
        <?php }else{$img_size=getimagesize("uploads/".$_SESSION['folder_name']."/".$_SESSION['school_logo']."");$img_width=$img_size[0];$img_height=$img_size[1]; ?>
        <div class="logoFancy"><img class="row__poster"src="<?php echo base_url(); ?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>"></div>
        <?php } ?>
      </a><br>
      <a href="#"class="user-link"> 
        <span style="font-size:17px;"> Welcome <?php echo $res[0]['name']; ?> </span>
      </a>
    </div>
  </header>

  <div class="sidebar-user-info">
    <div class="sui-normal">
      <a href="#"class="user-link">
        <?php if($res[0]['profile_pic']==""){ ?>
        <img class="img-circle notify_icon123"class="img-circle"src="<?php echo get_default_pic() ?>"alt=""alt=""width="55">
        <?php }else{ ?>
        <img class="img-circle notify_icon123"class="img-circle"src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1) ?>"alt=""alt=""width="55">
        <?php } ?>
        <span>Welcome,
        </span> 
        <strong>
          <?php echo $res[0]['name']; ?>
        </strong>
        <br>
        <strong>
          <?php echo $_SESSION['school_name']; ?>
        </strong>
      </a>
    </div>
    <div class="animate-in inline-links sui-hover">
      <a href="<?=base_url()?>profile/manage_profile">
        <i class="entypo-pencil">
        </i> Profile 
      </a>
      <?php if(isset($_SESSION['multiple_accounts'])&&$_SESSION['multiple_accounts']==1){ ?>
      <a href="<?php echo base_url(); ?>switch_user/account_list">
        <i class="entypo-chat">
        </i> Switch 
      </a>
      <?php } ?>
      <a href="<?php echo base_url(); ?>login/logout">
        <i class="entypo-lock">
        </i> Log Off 
      </a>
      <span class="close-sui-popup">×
      </span>
    </div>
  </div>
  <ul class="main-menu"id="main-menu">
    <li class="<?php if($page_name=='dashboard')echo 'active'; ?>">
      <a href="<?php echo base_url(); ?>admin/dashboard">
        <i class="fas fa-tachometer-alt">
        </i> 
        <span>
          <?php echo get_phrase('dashboard'); ?>
        </span>
      </a>
    </li>
    
    <?php if(right_granted('schoolconfiguration_manage')){ ?>
    <li class="<?php if($page_name=='import_list'||$page_name=='school_profiling'||$page_name=='system_settings'||$page_name=='policy_categories'||$page_name=='policies_listing'||$page_name=='event_annoucment'||$page_name=='acadmic_year_listing'||$page_name=='yearly_terms'||$page_name=='departments_listing'||$page_name=='class'||$page_name=='section_listing'||$page_name=='class_chalan'||$page_name=='student_pending'||$page_name=='policies_add_edit'||$page_name=='location'||$page_name=='subject'||$page_name=='class_routine'||$page_name=='time_table_list'||$page_name=='manage_leaves'||$page_name=='sms_setting'||$page_name=='student_evaluation'||$page_name=='manage_staff_evaluation'||$page_name=='vacation_settings'||$page_name=='add_teacher_swap'||$page_name=='manage_teacher_swap'||$page_name=='sms_templates'||$page_name=='email_templates'||$page_name=='sms_temp_listing'||$page_name=='email_temp_listing'||$page_name=='email_layout_setting' || $page_name=='preferences' || $page_name=='general_inquiry_view'||$page_name=='admission_inquiry_view'||$page_name=='landing_page' ||$page_name=='view_jobs' ||$page_name=='view_job_applications'||$page_name == "event_announcement_detail"||$page_name=='student_category_listing'||$page_name=='add_teacher_swap'||$page_name=='manage_teacher_swap'||$page_name=='view_substitute' || $page_name=='email_layout_settings'||$page_name == 'todo/todo_list')echo 'opened active has-sub'; ?>">
      <a href="#">
        <i class="fas fa-school">
        </i> 
        <span>
          <?php echo get_phrase('school_management'); ?>
        </span>
      </a>
      <ul>
        <?php if(right_granted('schoolconfiguration_manage')){ ?>
        <li class="<?php if($page_name=='system_settings')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>school_setting/system_settings"class="asterisk">
            <i class="fas fa-sliders-h">
            </i>
            <?php echo get_phrase('school_configuration'); ?>
          </a>
        </li>
        <?php } ?>
        
        <?php if(right_granted('landing_page')){ ?>
        <li class="<?php if($page_name=='landing_page')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>landing_page/" class="asterisk">
            <i class="fas fa-tv"></i>
            <?php echo get_phrase('landing_page'); ?>
          </a>
        </li>
        <?php }?>
        <?php if(right_granted('import_data')){ ?>
        <li class="<?php if($page_name=='import_list')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>sheets/import_list"class="asterisk">
            <i class="fas fa-file-import"></i>
            <?php echo get_phrase('import_data'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('locations_view','locations_manage','locations_delete'))){ ?>
        <li class="<?php if($page_name=='location')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>location/">
            <i class="fas fa-map-marker-alt">
            </i>
            <?php echo get_phrase('city_locations'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('schoolpolicies_view','schoolpolicies_manage','schoolpolicies_delete'))){ ?>
        <li class="<?php if($page_name=='policies_listing'||$page_name=='policies_add_edit')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>policies/policies_listing">
            <i class="fas fa-vote-yea">
            </i>
            <?php echo get_phrase('school_policies'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('events_annoucments'))){ ?>
        <li class="<?php if($page_name=='event_annoucment'||$page_name == "event_announcement_detail")echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>event_annoucments/events_program">
            <i class="fas fa-vote-yea">
            </i>
            <?php echo get_phrase('events_announcement'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('academicyearterms_view','academicyearterms_delete','academicyearterms_manage'))){ ?>
        <li class="<?php if($page_name=='acadmic_year_listing')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>academic_year/acadmic_year_listing"class="asterisk">
            <i class="far fa-calendar-alt">
            </i>
            <?php echo get_phrase('academic_year_&_terms'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('vacationsettings_view','vacationsettings_manage','vacationsettings_delete'))){ ?>
        <li class="<?php if($page_name=='vacation_settings')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>vacation/vacation_settings">
            <span>
              <i class="fas fa-plane">
              </i>
              <?php echo get_phrase('school_vacations'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('manageleavecategory_view','manageleavecategory_manage','manageleavecategory_delete'))){ ?>
        <li class="<?php if($page_name=='manage_leaves')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>leave/manage_leaves"class="asterisk">
            <i class="fas fa-cash-register">
            </i> 
            <span>
              <?php echo get_phrase('leave_categories'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('departments_view','departments_delete','departments_manage'))){ ?>
        <li class="<?php if($page_name=='departments_listing')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>departments/departments_listing"class="asterisk">
            <i class="fas fa-door-open">
            </i>
            <?php echo get_phrase('deprtments'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('classes_view','classes_delete','classes_manage'))){ ?>
        <li class="<?php if($page_name=='class')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>departments/classes"class="asterisk">
            <i class="fas fa-chalkboard-teacher">
            </i>
            <?php echo get_phrase('classes'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('sections_view','sections_delete','sections_manage'))){ ?>
        <li class="<?php if($page_name=='section_listing')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>departments/section_listing"class="asterisk">
            <i class="fas fa-boxes">
            </i>
            <?php echo get_phrase('sections'); ?>
          </a>
        </li>
        <?php }if(right_granted('student_category')){ ?>
        <li class="<?php if($page_name=='student_category_listing')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>student_category/manage_stud_category"class="asterisk">
            <i class="fas fa-user-graduate">
            </i>
            <?php echo get_phrase('student_category'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('subject_view','subject_delete','subject_manage'))){ ?>
        <li class="<?php if($page_name=='subject')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>subject/subjects/"class="asterisk">
            <i class="fas fa-book">
            </i> 
            <span>
              <?php echo get_phrase('subjects'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted('managetimetable_manage')){ ?>
        <li class="<?php if($page_name=='class_routine'||$page_name=='view_substitute'||$page_name=='add_teacher_swap'||$page_name=='manage_teacher_swap')echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-table">
            </i> 
            <span>
              <?php echo get_phrase('time_table'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted(array('managetimetable_view','managetimetable_manage','managetimetable_delete'))){ ?>
            <li class="<?php if($page_name=='class_routine')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>time_table/class_routine/">
                <i class="fas fa-user-clock">
                </i> 
                <span>
                  <?php echo get_phrase('manage_time_table'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('assign_substitute_teacher'))){ ?>
            <li class="<?php if($page_name=='view_substitute')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>asign_substitute/listing_asign">
                <i class="fas fa-id-card-alt">
                </i> 
                <span>
                  <?php echo get_phrase('assign_substitute_teacher'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('manage_teacher_swapping','view_teacher_swapping'))){ ?>
            <li class="<?php if($page_name=='add_teacher_swap'||$page_name=='manage_teacher_swap')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>swap/swapping">
                <i class="fas fa-chalkboard-teacher">
                </i> 
                <span>
                  <?php echo get_phrase('teacher_class_swapping'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php }if(right_granted(array('studentevaluationsettings_view','studentevaluationsettings_delete','studentevaluationsettings_manage'))){ ?>
        <li class="<?php if($page_name=='student_evaluation')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>evaluation/stud_evaluation">
            <i class="fas fa-user-cog">
            </i>
            <?php echo get_phrase('student_evaluation_settings'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('staffevaluationsettings_view','staffevaluationsettings_delete','staffevaluationsettings_manage'))){ ?>
        <li class="<?php if($page_name=='manage_staff_evaluation')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>staff_evaluation/staff_eval">
            <i class="fas fa-users-cog">
            </i>
            <?php echo get_phrase('staff_evaluation_settings'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('smssettings_manage'))){ ?>
        
        <!--<li class="<?php //if($page_name=='sms_setting')echo 'active'; ?>">-->
        <!--  <a href="<?php //echo base_url(); ?>sms_settings/sms_setting">-->
        <!--    <i class="fas fa-sms">-->
        <!--    </i>-->
        <!--    <?php //echo get_phrase('sms_setting'); ?>-->
        <!--  </a>-->
        <!--</li>-->
        <?php } ?>
        
        
        
        <?php if(right_granted('manage_preferences')){ ?>
        <li class="<?php if($page_name=='preferences'||$page_name=='email_layout_settings')echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-cog">
            </i> 
            <span>
              <?php echo get_phrase('settings'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted('a_preferences')){ ?>
            <li class="<?php if($page_name=='preferences') echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>preferences">
                <i class="fas fa-user-cog"></i>
                <?php echo get_phrase('preferences'); ?>
              </a>
            </li>
            <?php }if(right_granted('email_layout_settings')){ ?>
            <li class="<?php if($page_name=='email_layout_settings')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>school_setting/email_layout_form" class="asterisk">
                <i class="fas fa-tv"></i>
                <?php echo get_phrase('email_layout_settings'); ?>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        
        
        
        <?php if(right_granted('manage_inquiries')){ ?>
        <li class="<?php if($page_name=='general_inquiry_view'||$page_name=='admission_inquiry_view')echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-table">
            </i> 
            <span>
              <?php echo get_phrase('Inquiries'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted('general_inquiry_view')){ ?>
            <li class="<?php if($page_name=='general_inquiry_view')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>Inquiries/general_inquiry_view/">
                <i class="fas fa-user-clock">
                </i> 
                <span>
                  <?php echo get_phrase('general_inquiries'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted('admission_inquiry_view')){ ?>
            <li class="<?php if($page_name=='admission_inquiry_view')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>Inquiries/admission_inquiry_view">
                <i class="fas fa-id-card-alt">
                </i> 
                <span>
                  <?php echo get_phrase('admission_inquiries'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        
        <?php if(right_granted('manage_jobs')){ ?>
        <li class="<?php if($page_name=='view_jobs' ||$page_name=='view_job_applications' )echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-briefcase">
            </i> 
            <span>
              <?php echo get_phrase('manage_jobs'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted('view_jobs')){ ?>
            <li class="<?php if($page_name=='view_jobs')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>Jobs/view_jobs">
                <i class="fas fa-briefcase">
                </i> 
                <span>
                  <?php echo get_phrase('view_jobs'); ?>
                </span>
              </a>
            </li>
            <?php } if(right_granted('view_job_applications')){ ?>
            <li class="<?php if($page_name=='view_job_applications')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>Jobs/view_job_applications">
                <i class="fas fa-briefcase">
                </i> 
                <span>
                  <?php echo get_phrase('view_job_applications'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
          </li>
        <?php } ?>
          
        <?php if(right_granted('manage_utilities')){ ?>
        <li class="<?php if($page_name=='todo/todo_list' )echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-tools">
            </i> 
            <span>
              <?php echo get_phrase('manage_utilities'); ?>
            </span>
          </a>
        <ul>
            <?php if(right_granted('todo_list')){ ?>
            <li class="<?php if($page_name=='todo/todo_list')echo 'active'; ?>">
                <a href="<?php echo base_url(); ?>Todo/list">
                    <i class="fas fa-id-card-alt"></i> 
                    <span>
                      <?php echo get_phrase('todo_list'); ?>
                    </span>
                </a>
            </li>
            <?php } ?>
        </ul>
        </li>
        <?php } ?>
        
      </ul>
    </li>
    <?php } ?>
    <li class="<?php if( $page_name=='ledger' || $page_name=='account_transection'|| $page_name=='journal_entry'|| $page_name == 'student_fee_report_ledger' 
                        || $page_name=='trial_balance'||$page_name=='coa_list'||$page_name=='fee_types'||$page_name=='discount_list'||$page_name=='class_chalan_form'
                        ||$page_name=='chalan_settings'||$page_name=='balance_sheet'||$page_name=='depositor_listing'||$page_name=='depositor_add_edit'
                        ||$page_name=='supplier_listing'||$page_name=='supplier_add_edit'||$page_name=='deposit_listing'||$page_name=='bank_receipt_voucher_listing'
                        ||$page_name=='bank_receipt_voucher'||$page_name=='bank_receipt_voucher_edit'||$page_name=='bank_receipt_voucher_detail'
                        ||$page_name=='cash_receipt_voucher_listing'||$page_name=='cash_receipt_voucher'||$page_name=='cash_receipt_voucher_detail'
                        ||$page_name=='cash_receipt_voucher_edit'  || $page_name == 'bank_payment_voucher' || $page_name == 'bank_payment_voucher_edit' 
                        || $page_name == 'bank_payment_voucher_detail' ||$page_name=='cash_voucher_setting'||$page_name=='financial_report_settings'
                        ||$page_name=='income_statement'||$page_name=='miscellaneous_settings'||$page_name=='bank_detail_listing'||$page_name=='add_edit_bank' 
                        || $page_name == 'view_cheque_book_details'  || $page_name=='student_challan_summary'|| $page_name == 'bank_payment_voucher_listing' 
                        || $page_name == 'cash_payment_voucher' || $page_name == 'cash_payment_voucher_listing' || $page_name == 'cash_payment_voucher_edit' 
                        || $page_name=='cash_payment_voucher_detail' || $page_name=='purchase_voucher' || $page_name=='purchase_voucher_listing' 
                        || $page_name=='purchase_voucher_edit' || $page_name=='journal_voucher_listing' || $page_name=='journal_voucher_edit' 
                        || $page_name=='journal_voucher_detail' || $page_name=='journal_voucher' || $page_name=='student_ledger' 
                        || $page_name=='fee_recovery_report' || $page_name=='students_arrears'|| $page_name=='fee_concession_report' 
                        || $page_name=='breakeven_report' || $page_name=='section_wise_paid_fee' || $page_name=='class_wise_student_summary' 
                        || $page_name=='student_withdrawl_summary' || $page_name=='key_performance_indicators'|| $page_name=='bulk_chalan_listing'
                        ||$page_name=='chalan_detail_listing' ||$page_name=='payroll/salary_voucher_setting'||$page_name=='view_print_chalan'||$page_name=='student_chalan_edit')echo 'opened active has-sub'; ?>">
      <a href="#">
        <i class="fas fa-file-invoice-dollar">
        </i> 
        <span>
          <?php echo get_phrase('account_management'); ?>
        </span>
      </a>
      <ul>
        <?php if(right_granted(array('chartofaccount_view','chartofaccount_delete','chartofaccount_manage'))){ ?>
        <li class="<?php if($page_name=='coa_list')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>chart_of_account/coa_list"class="asterisk">
            <i class="far fa-chart-bar">
            </i>
            <?php echo get_phrase('chart_Of_accounts'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('chartofaccount_view','chartofaccount_delete','chartofaccount_manage'))){ ?>
        <li class="<?php if($page_name=='bank_detail_listing'||$page_name=='add_edit_bank' || $page_name == 'view_cheque_book_details')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>bank_detail/bank_detail_listing">
            <i class="fas fa-university">
            </i>
            <?php echo get_phrase('bank_accounts'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('depositor_manage','depositor_view','depositor_delete'))){ ?>
        <li class="<?php if($page_name=='depositor_listing'||$page_name=='depositor_add_edit')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>depositor/depositor_listing">
            <i class="las la-money-bill-wave">
            </i>
            <?php echo get_phrase('depositor'); ?>
          </a>
        </li>
        <?php }if(right_granted('manage_supplier')){ ?>
        <li class="<?php if($page_name=='supplier_listing'||$page_name=='supplier_add_edit')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>supplier/supplier_listing">
            <i class="las la-user-astronaut">
            </i>
            <?php echo get_phrase('supplier'); ?>
          </a>
        </li>
        <?php }if(right_granted('manage_vouchers_view')){ ?>
        <li class="<?php if($page_name=='bank_receipt_voucher'||$page_name=='bank_receipt_voucher_listing'||$page_name=='bank_receipt_voucher_edit'||$page_name=='bank_receipt_voucher_detail'||$page_name=='cash_receipt_voucher_listing'||$page_name=='cash_receipt_voucher'||$page_name=='cash_receipt_voucher_detail'||$page_name=='cash_receipt_voucher_edit' || $page_name == 'bank_payment_voucher' || $page_name == 'bank_payment_voucher_listing' || $page_name == 'bank_payment_voucher_edit' || $page_name == 'bank_payment_voucher_detail' || $page_name == 'cash_payment_voucher' || $page_name == 'cash_payment_voucher_listing' || $page_name == 'cash_payment_voucher_edit' || $page_name=='cash_payment_voucher_detail' || $page_name=='purchase_voucher' || $page_name=='purchase_voucher_listing' || $page_name=='purchase_voucher_edit' || $page_name=='journal_voucher_listing' || $page_name=='journal_voucher_edit' || $page_name=='journal_voucher_detail' || $page_name=='journal_voucher' )echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-money-bill-alt">
            </i> 
            <span>
              <?php echo get_phrase('vouchers'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted(array('bank_receipt_voucher'))){ ?>
            <li class="<?php if($page_name=='bank_receipt_voucher'||$page_name=='bank_receipt_voucher_listing'||$page_name=='bank_receipt_voucher_edit'||$page_name=='bank_receipt_voucher_detail')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>vouchers/bank_receipt_voucher_listing">
                <i class="fas fa-money-bill-alt">
                </i> 
                <span>
                  <?php echo get_phrase('bank_receipt_voucher'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('cash_receipt_voucher'))){ ?>
            <li class="<?php if($page_name=='cash_receipt_voucher_listing'||$page_name=='cash_receipt_voucher'||$page_name=='cash_receipt_voucher_detail'||$page_name=='cash_receipt_voucher_edit')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>vouchers/cash_receipt_voucher_listing">
                <i class="fas fa-money-bill-alt">
                </i> 
                <span>
                  <?php echo get_phrase('cash_receipt_voucher'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('bank_payment_voucher'))){ ?>
            <li class="<?php if($page_name=='bank_payment_voucher' || $page_name == 'bank_payment_voucher_listing' || $page_name == 'bank_payment_voucher_edit' || $page_name == 'bank_payment_voucher_detail')echo 'active'; ?>">
              <a href="<?=base_url()?>vouchers/bank_payment_voucher_listing">
                <i class="fas fa-money-bill-alt">
                </i> 
                <span>
                  <?php echo get_phrase('bank_payment_voucher'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('cash_payment_voucher'))){ ?>
            <li class="<?php if($page_name=='cash_payment_voucher'  || $page_name=='cash_payment_voucher_listing' || $page_name=='cash_payment_voucher_edit' || $page_name=='cash_payment_voucher_detail')echo 'active'; ?>">
              <a href="<?=base_url()?>vouchers/cash_payment_voucher_listing">
                <i class="fas fa-money-bill-alt">
                </i> 
                <span>
                  <?php echo get_phrase('cash_payment_voucher'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('journal_voucher'))){ ?>
            <li class="<?php if($page_name == 'journal_voucher_listing' || $page_name=='journal_voucher_edit' || $page_name=='journal_voucher_detail' || $page_name=='journal_voucher')echo 'active'; ?>">
              <a href="<?=base_url()?>vouchers/journal_voucher_listing">
                <i class="fas fa-money-bill-alt">
                </i> 
                <span>
                  <?php echo get_phrase('journal_voucher'); ?>
                </span>
              </a>
            </li>
            <?php } if(right_granted(array('purchase_voucher')))
            { ?>
            <li class="<?php if($page_name=='purchase_voucher' || $page_name=='purchase_voucher_listing' || $page_name=='purchase_voucher_edit')echo 'active'; ?>">
              <a href="<?=base_url()?>vouchers/purchase_voucher_listing">
                <i class="fas fa-money-bill-alt">
                </i> 
                <span>
                  <?php echo get_phrase('purchase_voucher'); ?>
                </span>
              </a>
            </li>
            <?php }
            ?>
          </ul>
        </li>
        <?php } ?>
        <?php if(right_granted(array('feetype_view','feetype_delete','feetype_manage'))){ ?>
        <li class="<?php if($page_name=='fee_types')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>fee_types/fee_types_c"class="asterisk">
            <i class="las la-comments-dollar">
            </i>
            <?php echo get_phrase('fee_types'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('discounttype_view','discounttype_delete','discounttype_manage'))){ ?>
        <li class="<?php if($page_name=='discount_list')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>discount/discount_list"class="asterisk">
            <i class="las la-percent">
            </i>
            <?php echo get_phrase('discount_types'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('managechallanform_layout'))){ ?>
        <li class="<?php if($page_name=='chalan_settings')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>class_chalan_form/chalan_settings"class="asterisk">
            <i class="las la-file-alt">
            </i>
            <?php echo get_phrase('challan_form_layout'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('managechallanform_view','managechallanform_delete','managechallanform_manage'))){ ?>
        <li class="<?php if($page_name=='class_chalan_form')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>class_chalan_form/class_chalan_f"class="asterisk">
            <i class="las la-file-alt">
            </i>
            <?php echo get_phrase('manage_Challan_forms'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('bulkmonthlychallan'))){ ?>
        <li class="<?php if($page_name=='bulk_chalan_listing'||$page_name=='chalan_detail_listing'||$page_name=='student_chalan_edit')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>monthly_fee/monthly_bulk_listing">
            <span>
              <i class="fas fa-print">
              </i>
              <?php echo get_phrase('monthly_challans'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
        <?php //if(right_granted('manage_vouchers_view')){ ?>
        <li class="<?php if($page_name=='journal_entry' || $page_name=='ledger' || $page_name=='trial_balance' || $page_name=='income_statement' || $page_name=='breakeven_report' || $page_name=='balance_sheet')echo 'opened active'; ?>">
            <a href="#">
                <i class="fas fa-money-bill-alt">
                </i> 
                <span>
                  <?php echo get_phrase('financial_reports'); ?>
                </span>
            </a>
            <ul>
                <?php if(right_granted('journal_entry_view')){ ?> 
                <li class="<?php if($page_name=='journal_entry')echo 'active'; ?>">
                  <a href="<?php echo base_url(); ?>transection_account/journal_entry">
                    <i class="las la-cash-register">
                    </i>
                    <?php echo get_phrase('journal_entry'); ?>
                  </a>
                </li>
                <?php }if(right_granted('ledger_view')){ ?> 
                <li class="<?php if($page_name=='ledger')echo 'active'; ?>">
                  <a href="<?php echo base_url(); ?>transection_account/ledger">
                    <i class="las la-cash-register">
                    </i>
                    <?php echo get_phrase('ledger'); ?>
                  </a>
                </li>
                <?php }if(right_granted('trialbalance_view')){ ?>
                <li class="<?php if($page_name=='trial_balance')echo 'active'; ?>">
                  <a href="<?php echo base_url(); ?>transection_account/trial_balance">
                    <i class="fas fa-balance-scale-right">
                    </i>
                    <?php echo get_phrase('trial_balance'); ?>
                  </a>
                </li>
                <?php }if(right_granted('income_statement_view')){ ?>
                <li class="<?php if($page_name=='income_statement')echo 'active'; ?>">
                  <a href="<?php echo base_url(); ?>transection_account/income_statement">
                    <i class="fas fa-dollar-sign">
                    </i>
                    <?php echo get_phrase('income_statement'); ?>
                  </a>
                </li>
                <?php } ?>
                
                <?php if(right_granted(array('breakeven_report')))
                { ?>
                <li class="<?php if($page_name=='breakeven_report')echo 'active'; ?>">
                  <a href="<?php echo base_url(); ?>reports/breakeven_report">
                    <i class="fas fa-money-bill-alt"></i> 
                    <span>
                      <?php echo get_phrase('breakeven_report'); ?>
                    </span>
                  </a>
                </li>
                <?php } ?>
                
                <?php if(right_granted('balance_sheet_view')){ ?>
                <li class="<?php if($page_name=='balance_sheet')echo 'active'; ?>">
                  <a href="<?php echo base_url(); ?>transection_account/balance_sheet">
                    <i class="las la-file-image">
                    </i>
                    <?php echo get_phrase('balance_sheet'); ?>
                  </a>
                </li>
                <?php } ?>        
            </ul>
        </li>
        <?php //if(right_granted('manage_vouchers_view')){ ?>
        <li class="<?php if($page_name=='student_fee_report_ledger' || $page_name=='student_challan_summary' || $page_name=='student_ledger' || $page_name=='fee_recovery_report' || $page_name=='students_arrears'|| $page_name=='fee_concession_report' || $page_name=='section_wise_paid_fee' || $page_name=='class_wise_student_summary' || $page_name=='student_withdrawl_summary' || $page_name=='key_performance_indicators')echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-money-bill-alt">
            </i> 
            <span>
              <?php echo get_phrase('billing_reports'); ?>
            </span>
          </a>
          <ul>
            <?php //if(right_granted(array('bank_receipt_voucher'))){ ?>
            <!--<li class="<?php if($page_name=='student_fee_report_ledger')echo 'active'; ?>">-->
            <!--  <a href="<?php echo base_url(); ?>transection_account/student_fee_report_ledger">-->
            <!--    <i class="fas fa-money-bill-alt"></i> -->
            <!--    <span>-->
            <!--      <?php echo get_phrase('student_fee_report'); ?>-->
            <!--    </span>-->
            <!--  </a>-->
            <!--</li>-->
            
            <?php if(right_granted(array('a_studentchallan_summary'))){ ?>
            <li class="<?php if($page_name=='student_challan_summary')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>transection_account/student_challan_summary">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('student_challan_summary'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            <?php if(right_granted(array('student_ledger_report'))){ ?>
            <li class="<?php if($page_name=='student_ledger')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>transection_account/student_ledger">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('student_ledger'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            <?php if(right_granted(array('fee_recovery_report'))){ ?>
            <li class="<?php if($page_name=='fee_recovery_report')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>reports/fee_recovery_report">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('fee_recovery_report'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            <?php if(right_granted(array('fee_concession_report'))){ ?>
            <li class="<?php if($page_name=='fee_concession_report')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>reports/fee_concession_report">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('fee_concession_report'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            <?php if(right_granted(array('students_arrears'))){ ?>
            <li class="<?php if($page_name=='students_arrears')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>reports/students_arrears">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('arrears_report'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            <?php if(right_granted(array('class_wise_fee_summary')))
            { ?>
            <li class="<?php if($page_name=='section_wise_paid_fee')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>reports/section_wise_paid_fee">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('class_wise_fee_summary'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            <?php if(right_granted(array('class_wise_student_summary')))
            { ?>
            <li class="<?php if($page_name=='class_wise_student_summary')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>reports/class_wise_student_summary">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('class_wise_student_summary'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            
            <?php if(right_granted(array('student_withdrawl_summary')))
            { ?>
            <li class="<?php if($page_name=='student_withdrawl_summary')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>reports/student_withdrawl_summary">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('student_withdrawl_summary'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            <?php if(right_granted(array('key_performance_indicators')))
            { ?>
            <li class="<?php if($page_name=='key_performance_indicators')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>reports/key_performance_indicators">
                <i class="fas fa-money-bill-alt"></i> 
                <span>
                  <?php echo get_phrase('key_performance_indicators'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php //} ?>
        <li class="<?php if($page_name == 'miscellaneous_settings' ||  $page_name == 'financial_report_settings' || $page_name == 'cash_voucher_setting' || $page_name=='payroll/salary_voucher_setting') echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-cog">
            </i> 
            <span>
              <?php echo get_phrase('settings'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted(array('miscellaneous_coa_settings'))){ ?>
            <li class="<?php if($page_name=='miscellaneous_settings')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>chart_of_account/miscellaneous_settings">
                <i class="fas fa-cog">
                </i>
                <?php echo get_phrase('miscellaneous_settings'); ?>
              </a>
            </li>
            <?php } if(right_granted(array('financial_report_settings_view'))){ ?>
            <li class="<?php if($page_name=='financial_report_settings')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>chart_of_account/financial_report_settings">
                <i class="fas fa-cog">
                </i>
                <?php echo get_phrase('financial_report_settings'); ?>
              </a>
            </li>
            <?php } if(right_granted(array('cash_voucher_setting'))){ ?>
            <li class="<?php if($page_name=='cash_voucher_setting')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>vouchers/cash_voucher_setting">
                <i class="fas fa-cog">
                </i> 
                <span>
                  <?php echo get_phrase('voucher_settings'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            
            <li class="<?php if($page_name=='`ll/salary_voucher_setting')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>payroll/salary_voucher_setting">
                <i class="fas fa-cog">
                </i> 
                <span>
                  <?php echo get_phrase('salary_settings'); ?>
                </span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <?php if(right_granted('manage_payroll')){ ?>
    <li class="<?php if($page_name=='payroll/allownces_listing' || $page_name=='payroll/deduction_listing' || $page_name=='payroll/staff_salary' || $page_name=='payroll/view_staff_salary' || $page_name=='payroll/salary_details' || $page_name=='payroll/payroll_sheet' || $page_name=='payroll/payroll_ytd_report' )echo 'opened active has-sub'; ?>">
      <a href="#">
        <i class="fas fa-users-cog">
        </i> 
        <span>
          <?php echo get_phrase('payroll_management'); ?>
        </span>
      </a>
        <ul>
            <?php if(right_granted('allownces')){ ?>
            
            <li class="<?php if($page_name=='payroll/allownces_listing')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>payroll/allownces_listing"class="asterisk">
                <i class="far fa-id-badge">
                </i>
                <?php echo get_phrase('allownces'); ?>
              </a>
            </li>
            <?php }if(right_granted('deduction')){ ?>
            
            <li class="<?php if($page_name=='payroll/deduction_listing')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>payroll/deduction_listing"class="asterisk">
                <i class="far fa-id-badge">
                </i>
                <?php echo get_phrase('deductions'); ?>
              </a>
            </li>
            
            <?php }if(right_granted('salary_annexure')){ ?>
            <li class="<?php if($page_name=='payroll/staff_salary')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>payroll/staff_salary"class="asterisk">
                <i class="far fa-id-badge">
                </i>
                <?php echo get_phrase('salary_annexure'); ?>
              </a>
            </li>
            
            <?php }if(right_granted('view_staff_salary')){ ?>
            <li class="<?php if($page_name=='payroll/view_staff_salary' || $page_name=='payroll/salary_details')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>payroll/view_staff_salary"class="asterisk">
                <i class="far fa-id-badge">
                </i>
                <?php echo get_phrase('view_staff_salary'); ?>
              </a>
            </li>
            
            <?php }if(right_granted('payroll_ytd_report')){ ?>
            <li class="<?php if($page_name=='payroll/payroll_ytd_report')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>payroll/payroll_ytd_report"class="asterisk">
                <i class="far fa-id-badge">
                </i>
                <?php echo get_phrase('payroll_YTD_report'); ?>
              </a>
            </li>
            
            <?php }if(right_granted('payroll_sheet')){ ?>
            <li class="<?php if($page_name=='payroll/payroll_sheet')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>payroll/payroll_sheet"class="asterisk">
                <i class="far fa-id-badge">
                </i>
                <?php echo get_phrase('payroll_sheet'); ?>
              </a>
            </li>
            <?php } ?>
            
        </ul>
    </li>
    <?php } ?>
    <?php if(right_granted('staff_manage')){ ?>
    <li class="<?php if($page_name=='user_type_list'||$page_name=='staff_listing'||$page_name=='staff_add_edit'||$page_name=='create_staff_card'
                        ||$page_name=='teacher' ||$page_name=='user_designation'||$page_name=='manage_staff_attendance'||$page_name=='view_staff_attendance'
                        ||$page_name=='manage_leaves_staff' || $page_name == 'staff_attendance_summary' ||$page_name=='manage_staff_eval'
                        ||$page_name=='add_staff_evaluation' ||$page_name=='details_staff_evaluation'||$page_name=='view_staff_attendance'
                        ||$page_name=='teacher_experience_certificate')echo 'opened active has-sub'; ?>">
      <a href="#">
        <i class="fas fa-users-cog">
        </i> 
        <span>
          <?php echo get_phrase('staff_management'); ?>
        </span>
      </a>
      <ul>
        <?php if(right_granted(array('designation_view','designation_manage','designation_delete'))){ ?>
        <li class="<?php if($page_name=='user_designation')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>user/user_designation"class="asterisk">
            <i class="far fa-id-badge">
            </i>
            <?php echo get_phrase('designations'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('staff_view','staff_delete','staff_manage'))){ ?>
        <li class="<?php if($page_name=='staff_listing'||$page_name=='staff_add_edit'||$page_name=='create_staff_card')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>user/staff_listing">
            <i class="las la-users">
            </i>
            <?php echo get_phrase('staff'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('teacher_view','teacher_manage'))){ ?>
        <li class="<?php if($page_name=='teacher')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>user/teacher"class="asterisk">
            <i class="las la-user-tie">
            </i> 
            <span>
              <?php echo get_phrase('teachers'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('staffattendance_manage','staffattendance_view'))){ ?>
        <li class="<?php if($page_name=='manage_attendance'||$page_name=='view_student_attendance'||$page_name=='view_staff_attendance'||$page_name=='manage_staff_attendance' || $page_name == 'staff_attendance_summary')echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-user-check">
            </i> 
            <span>
              <?php echo get_phrase('staff_attendance'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted(array('staffattendance_manage'))){ ?>
            <li class="<?php if($page_name=='manage_staff_attendance')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance_staff/manage_staff_attendance/<?php echo date('d/m/Y'); ?>">
                <i class="fas fa-file-signature">
                </i> 
                <span>
                  <?php echo get_phrase('mark_staff_attendance'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('staffattendance_view'))){ ?>
            <li class="<?php if($page_name=='view_staff_attendance')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance_staff/view_staff_attendance">
                <i class="fas fa-clipboard-list">
                </i> 
                <span>
                  <?php echo get_phrase('view_staff_attendance'); ?>
                </span>
              </a>
            </li>
              <li class="<?php if($page_name=='check_out_staff_attendance')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance_staff/check_out_staff_attendance/<?php echo date('d/m/Y'); ?>">
                <i class="far fa-eye">
                </i> 
                <span>
                  <?php echo get_phrase('check_out_staff_attendance'); ?>
                </span>
              </a>
            </li>
            <?php }{ ?>
            <li class="<?php if($page_name == 'staff_attendance_summary')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance_summary_staff/view_attendance_summary">
                <i class="fas fa-clipboard-check">
                </i> 
                <span>
                  <?php echo get_phrase('attendance_summary'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php }if(right_granted(array('manage_staff_certificates'))){ ?>
        <li class="<?php if($page_name=='teacher_experience_certificate')echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-file-alt"></i> 
            <span><?php echo get_phrase('teaching_certifications'); ?></span>
          </a>
          <ul>
            <?php if(right_granted(array('experience_certificate'))){ ?>
            <li class="<?php if($page_name=='teacher_experience_certificate') echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>certificate/teacher_experience_certificate">
                <i class="fas fa-file-signature">
                </i> 
                <span>
                  <?php echo get_phrase('experience_certificate'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php }if(right_granted(array('staffleave_manage','staffleave_view','staffleave_delete'))){ ?>
        <li class="<?php if($page_name=='manage_leaves_staff')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>leave_staff/manage_leaves_staff">
            <i class="fas fa-user-minus">
            </i> 
            <span>
              <?php echo get_phrase('staff_leaves'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('staffevaluationsettings_delete','staffevaluationsettings_manage','staffevaluationsettings_view'))){ ?>
        <li class="<?php if($page_name=='manage_staff_eval'||$page_name=='add_staff_evaluation'||$page_name=='details_staff_evaluation')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>staff_evaluation/evaluation">
            <i class="fas fa-file-alt">
            </i> 
            <span>
              <?php echo get_phrase('staff_evaluation'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>
    
    <?php if(right_granted('manage_students')){ ?>
    <li class="<?php if($page_name=='bulk_status_change'||$page_name=='student_add'||$page_name=='student_information' 
                        ||$page_name=='student_m_discount'||$page_name=='student_m_installment'||$page_name=='payment_listing'
                        ||$page_name=='student_information_pending'||$page_name=='update_parent'||$page_name=='student_detail'
                        ||$page_name=='student_marksheet'||$page_name=='assessment/view_assessment'||$page_name=='assessment/view_assessment_audience'
                        ||$page_name=='promotion_request'||$page_name=='promotion_listing'||$page_name=='promotion_chalan_listing'
                        ||$page_name=='transfer_information'||$page_name=='withdraw_listing'||$page_name=='manage_attendance'
                        ||$page_name=='view_student_attendance'||$page_name=='exam_routine'||$page_name=='Transfer_listing_req'
                        ||$page_name=='exam'||$page_name=='grade'||$page_name=='exam_weightage' || $page_name=='open_student_marksheet' 
                        ||$page_name=='marks'||$page_name=='diary'||$page_name=='manage_leaves_student'||$page_name=='view_student_attendance'
                        ||$page_name=='create_card'||$page_name=='stud_evaluation'||$page_name=='modal_view_stud_evaluation'
                        ||$page_name=='stud_evaluation_form'||$page_name=='receiving_transfer_list'||$page_name=='student_attendance_summary'
                        || $page_name=='student_status_report' || $page_name=='date_wise_attendance_summary' ||$page_name=='subjectwise_attendance' 
                        || $page_name=='student_credential_view' || $page_name=='student_credential_list' || $page_name=='leaving_certificate' 
                        || $page_name=='leaving_certificate_pdf'  || $page_name=='provisional_certificate' || $page_name=='character_certificate' 
                        || $page_name=='student_evaluation_results'|| $page_name == "update_student_detail" || $page_name=='manage_subjectwise_attendance' 
                        || $page_name=='view_subjectwise_attendance' || $page_name=='student_subjectwise_attendance' || $page_name == 'previous_attendance' || $page_name == 'diaries')echo 'opened active has-sub'; ?>">
      <a href="#">
        <i class="fas fa-user-cog">
        </i> 
        <span>
          <?php echo get_phrase('student_management'); ?>
        </span>
      </a>
      <ul>
        <?php if(right_granted('newadmission_manage')){ ?>
        <li class="<?php if($page_name=='student_add')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>c_student/student_add"class="asterisk">
            <span>
              <i class="fas fa-user-plus">
              </i>
              <?php echo get_phrase('new_admission'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('candidatelist_manage','candidatelist_view','candidatelist_delete'))){ ?>
        <li class="<?php if($page_name=='student_information_pending'||$page_name=='student_detail'||$page_name=='update_parent')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>c_student/student_pending">
            <span>
              <i class="fas fa-user-clock">
              </i> 
            </span>
            <?php echo get_phrase('candidates'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('students_view','students_manage','students_promote','students_delete'))){ ?>
        <li class="<?php if($page_name=='student_information'||$page_name=='create_card'||$page_name=='payment_listing'||$page_name=='student_chalan_edit'||$page_name=='view_print_chalan'||$page_name=='student_m_discount'||$page_name=='student_m_installment'||$page_name == "update_student_detail")echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>c_student/get_student_information">
            <span>
              <i class="fas fa-user-friends">
              </i>
              <?php echo get_phrase('students'); ?>
            </span>
          </a>
        </li>
        <?php }
        if(right_granted(array('students_promote'))){ ?>
        <li class="<?php if($page_name=='promotion_listing'||$page_name=='promotion_chalan_listing')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>promotion/promotion_listing">
            <span>
              <i class="fas fa-forward">
              </i>
              <?php echo get_phrase('class_promotions'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
        
        

        <?php
        if(right_granted(array('students_withdraw'))){ ?>
        <li class="<?php if($page_name=='withdraw_listing')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>c_student/withdraw_listing">
            <i class="las la-money-bill-wave-alt">
            </i>
            <?php echo get_phrase('withdrawals_list'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('transferrequest'))){ ?>
        <li class="<?php if($page_name=='transfer_information')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>transfer_student/transfer_information">
            <i class="fas fa-random">
            </i>
            <?php echo get_phrase('transfers_requests'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('admintransferapproval'))){ ?>
        <li class="<?php if($page_name=='Transfer_listing_req')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>transfer_student/transfer_listing_req">
            <i class="far fa-thumbs-up">
            </i>
            <?php echo get_phrase('admin_transfer Approvals'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('studenttransfer'))){ ?>
        <li class="<?php if($page_name=='receiving_transfer_list')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>transfer_student/receiving_transfer_list">
            <i class="fas fa-reply-all">
            </i>
            <?php echo get_phrase('receiving_transfer_list'); ?>
          </a>
        </li>
        <?php }if(right_granted(array('managestudentattendance_manage','viewstudentattendance_view'))){ ?>
        <li class="<?php if($page_name=='subjectwise_attendance'||$page_name=='manage_attendance'||$page_name=='view_student_attendance'||$page_name=='student_attendance_summary'||$page_name == "date_wise_attendance_summary" || $page_name=='manage_subjectwise_attendance' || $page_name=='view_subjectwise_attendance' || $page_name=='student_subjectwise_attendance' || $page_name == 'previous_attendance')echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-user-check">
            </i> 
            <span>
              <?php echo get_phrase('student attendance'); ?>
            </span>
          </a>
          <ul>
            <?php
                if(right_granted('managestudentattendance_manage')){
                    $attendance_method = get_attendance_method();
                    $attendance_url = "";
                    $text_att = "";
                    $report_view = "";
                    if($attendance_method == 1){
                        $attendance_url = "manage_attendance";
                        $text_att = "Mark Attendance";
                        $report_view = "Sectionwise Attendance";
                    }elseif($attendance_method == 2){
                        $attendance_url = "manage_subjectwise_attendance";
                        $text_att = "Mark Subjectwise Attendance";
                        $report_view = "View Subjectwise Attendance";
                    }else{
                        $attendance_url = "manage_attendance"; //default
                        $text_att = "Mark Attendance";
                    }
            ?>
            <li class="<?php if($page_name=='manage_attendance' || $page_name=='manage_subjectwise_attendance' )echo 'active'; ?>">
              <a href="<?php echo base_url()."attendance/".$attendance_url."/".date('d/m/Y'); ?>">
                <i class="fas fa-file-signature">
                </i> 
                <span>
                  <?php echo $text_att; ?>
                </span>
              </a>
            </li>
             <li class="<?php if($page_name=='view_subjectwise_attendance' || $page_name=='student_subjectwise_attendance')echo 'active'; ?>">
              <a href="<?php echo base_url()."attendance/view_subjectwise_attendance"; ?>">
                <i class="fas fa-file-signature">
                </i> 
                <span>
                  <?php echo $report_view; ?>
                </span>
              </a>
            </li>
            <li class="<?php if($page_name=='subjectwise_attendance')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance/subjectwise_attendance/<?php echo date('d/m/Y'); ?>">
                <i class="fas fa-clipboard-list">
                </i> 
                <span>
                  <?php echo get_phrase('vc_subjectwise_attendance'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted('viewstudentattendance_view')){ ?>
            <li class="<?php if($page_name=='view_student_attendance')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance/view_stud_attendance">
                <i class="far fa-eye">
                </i> 
                <span>
                  <?php echo get_phrase('daily_attenance_summary'); ?>
                </span>
              </a>
            </li>
            <?php }{ ?>
            <li class="<?php if($page_name=='student_attendance_summary')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance_summary_student/view_student_summary">
                <i class="fas fa-clipboard-check">
                </i> 
                <span>
                  <?php echo get_phrase('monthly_attendance_summary'); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            <li class="<?php if($page_name=='date_wise_attendance_summary')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance/date_wise_attendance_summary">
                <i class="fas fa-clipboard-check">
                </i> 
                <span>
                  <?php echo get_phrase('date_wise_attendance'); ?>
                </span>
              </a>
            </li>
            <li class="<?php if($page_name=='previous_attendance')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>attendance/previous_attendance">
                <i class="fas fa-clipboard-check">
                </i> 
                <span>
                  <?php echo get_phrase('previous_attendance'); ?>
                </span>
              </a>
            </li>
          </ul>
        </li>
        <?php }if(right_granted(array('studentleaves_manage'))){ ?>
        <li class="<?php if($page_name=='manage_leaves_student')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>leave/manage_leaves_student">
            <i class="fas fa-user-minus">
            </i> 
            <span>
              <?php echo get_phrase('manage_student_leaves'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('managediary_manage'))){ ?>
        <!--<li class="<?php //if($page_name=='diary')echo 'opened active'; ?>">-->
        <!--  <a href="<?php //echo base_url(); ?>diary/diarys/">-->
        <!--    <i class="fas fa-book">-->
        <!--    </i> -->
        <!--    <span>-->
        <!--      <?php // echo get_phrase('manage_diary'); ?>-->
        <!--    </span>-->
        <!--  </a>-->
        <!--</li>-->
        <?php } ?>
        <?php if(right_granted('examlist_view')){ ?>
        <li class="<?php if($page_name=='exam'||$page_name=='grade'||$page_name=='exam_weightage'||$page_name=='marks'||$page_name=='student_marksheet'||$page_name=='exam_routine' || $page_name=='open_student_marksheet')echo 'opened active'; ?>">
          <a href="#">
            <i class="fas fa-poll-h">
            </i> 
            <span>
              <?php echo get_phrase('exams'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted(array('examlist_manage'))){ ?>
            <li class="<?php if($page_name=='exam')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>exams/exam">
                <span>
                  <i class="far fa-file-alt">
                  </i>
                  <?php echo get_phrase('exams_list'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('managedatesheet_manage'))){ ?>
            <li class="<?php if($page_name=='exam_routine')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>exams/exam_routine">
                <span>
                  <i class="fas fa-receipt">
                  </i>
                  <?php echo get_phrase('manage_date_sheet'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('examgrades_manage'))){ ?>
            <li class="<?php if($page_name=='grade')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>exams/grade"class="asterisk">
                <span>
                  <i class="fas fa-graduation-cap">
                  </i>
                  <?php echo get_phrase('exam_grades'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('managemarks_save','examresult_viewmarksheet'))){ ?>
            <li class="<?php if($page_name=='marks')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>exams/marks">
                <span>
                  <i class="fas fa-poll">
                  </i>
                  <?php echo get_phrase('manage_marks'); ?>
                </span>
              </a>
            </li>
            <?php }if(right_granted(array('examresult_viewmarksheet'))){ ?>
            <li class="<?php if($page_name=='open_student_marksheet'||$page_name == "student_marksheet")echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>exams/student_marksheet">
                <i class="fas fa-clipboard">
                </i>
                <?php echo get_phrase('exam_results'); ?>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        
        <li class="<?php if($page_name == 'diaries' ) echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>c_student/diaries/">
            <i class="far fa-comment"></i>
            <span><?php echo get_phrase('diaries'); ?></span>
          </a>
        </li>
        
        <?php if(right_granted(array('create_assessment','view_assessment'))){ ?>
        <li class="<?php if($page_name=='assessment/view_assessment'||$page_name=='assessment/view_assessment_audience')echo 'opened active'; ?>">
          <a href="#">
            <i class="las la-tasks">
            </i> 
            <span>
              <?php echo get_phrase('online_assessments'); ?>
            </span>
          </a>
          <ul>
            <li class="<?php if($page_name=='assessment/view_assessment'||$page_name=='assessment/view_assessment_audience')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>adm_assessment/view_assessment">
                <span>
                  <i class="las la-clipboard-list">
                  </i>
                  <?php echo get_phrase('view_assessments'); ?>
                </span>
              </a>
            </li>
          </ul>
        </li>
        <?php }if(right_granted('exam_and_assessment_weightage')){ ?>
        <li class="<?php if($page_name=='exam_weightage')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>adm_assessment/exam_weightage">
            <span>
              <i class="fas fa-percentage">
              </i>
              <?php echo get_phrase('exam_&_assessment_weightage'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('studentevaluationsettings_manage','studentevaluationsettings_delete'))){ ?>
        <li class="<?php if($page_name=='stud_evaluation'||$page_name=='stud_evaluation_form'||$page_name=='modal_view_stud_evaluation')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>student_evaluation/stud_eval/">
            <i class="far fa-star">
            </i> 
            <span>
              <?php echo get_phrase('student_evaluation'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('student_evaluation_results'))){ ?>
        <li class="<?php if($page_name=='student_evaluation_results')echo 'opened active'; ?>">
          <a href="<?php echo base_url(); ?>student_evaluation/student_evaluation_results/">
            <i class="far fa-comment"></i>
            <span>
              <?php echo get_phrase('student_evaluation_results'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted('students_create_credentials')){ ?>
        <li class="<?php if($page_name=='student_credential_view' || $page_name=='student_credential_list')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>reports/student_credential_view">
            <span>
              <i class="fas fa-user-circle">
              </i>
              <?php echo get_phrase('students_credentials'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
        
        <?php if(right_granted('manage_student_certificates')){ ?>
        
        <li class="<?php if($page_name=='leaving_certificate' || $page_name=='leaving_certificate_pdf' || $page_name=='provisional_certificate' || $page_name=='character_certificate')echo 'opened active'; ?>">
          <a href="#">
            <i class="las la-tasks">
            </i> 
            <span>
              <?php echo get_phrase('student_certificates'); ?>
            </span>
          </a>
          <ul>
            <?php if(right_granted('leaving_certificate')){ ?>
            <li class="<?php if($page_name=='leaving_certificate' || $page_name=='leaving_certificate_pdf')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>certificate/leaving_certificate">
                <span>
                  <i class="las la-clipboard-list">
                  </i>
                  <?php echo get_phrase('leaving_certificate'); ?>
                </span>
              </a>
            </li>
            
            <?php } if(right_granted('character_certificate')){ ?>
            <li class="<?php if($page_name=='character_certificate')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>certificate/character_certificate">
                <span>
                  <i class="las la-clipboard-list">
                  </i>
                  <?php echo get_phrase('character_certificate'); ?>
                </span>
              </a>
            </li>
            
            <?php } if(right_granted('provisional_certificate')){ ?>
            <li class="<?php if($page_name=='provisional_certificate')echo 'active'; ?>">
              <a href="<?php echo base_url(); ?>certificate/provisional_certificate">
                <span>
                  <i class="las la-clipboard-list">
                  </i>
                  <?php echo get_phrase('provisional_certificate'); ?>
                </span>
              </a>
            </li>
            
            <?php } ?>
            
          </ul>
        </li>
        
        <?php } ?>
        
        
        <?php if(right_granted('student_status_report')){ ?>
        <li class="<?php if($page_name=='student_status_report')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>c_student/student_status_report">
            <span>
              <i class="fas fa-user-circle">
              </i>
              <?php echo get_phrase('student_status_report'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
        
        <?php if(right_granted('bulk_status_change')){ ?>
        <li class="<?php if($page_name=='bulk_status_change')echo 'active'; ?>">
            <a href="<?php echo base_url(); ?>c_student/bulk_status_change">
                <span>
                  <i class="fas fa-forward">
                  </i>
                  <?php echo get_phrase('bulk_status_change'); ?>
                </span>
            </a>
        </li>
        <?php } ?>
        
      </ul>
    </li>
    <?php } ?>
    <?php //if(right_granted('manage_payroll')){ Remaining ?>
    <li class="<?php if($page_name=='library/book' || $page_name=='library/book_issue' || $page_name == 'library/members' || $page_name == 'library/book_reserve_request')echo 'opened active'; ?>">
      <a href="#">
        <i class="fas fa-book">
        </i> 
        <span>
          <?php echo get_phrase('library_management'); ?>
        </span>
      </a>
        <ul>
            <?php //if(right_granted('add_book')) Remaining{ ?>
            <li class="<?php if($page_name=='library/book')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>library/books"class="asterisk">
                <i class="fas fa-book">
                </i>
                <?php echo get_phrase('manage_books'); ?>
              </a>
            </li>
            <?php //} ?>
            
             <?php //if(right_granted('add_book')) Remaining{ ?>
            <li class="<?php if($page_name=='library/book_issue')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>library/book_issue"class="asterisk">
                <i class="fas fa-book">
                </i>
                <?php echo get_phrase('book_issuance'); ?>
              </a>
            </li>
            <?php //} ?>
            
            <?php //if(right_granted('add_book')) Remaining{ ?>
            <li class="<?php if($page_name=='library/book_reserve_request')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>library/book_reserve_request"class="asterisk">
                <i class="fas fa-book">
                </i>
                <?php echo get_phrase('book_reserve_request'); ?>
              </a>
            </li>
            <?php //} ?>
            
            <?php //if(right_granted('add_book')) Remaining{ ?>
            <li class="<?php if($page_name=='library/members')echo 'opened active'; ?>">
              <a href="<?php echo base_url(); ?>library/members"class="asterisk">
                <i class="fas fa-user">
                </i>
                <?php echo get_phrase('manage_members'); ?>
              </a>
            </li>
            <?php //} ?>
        </ul>
    </li>
    <?php //} ?>
    <?php if(right_granted(array('academicplanner_manage','academicplanner_delete'))){ ?>
    <li class="<?php if($page_name=='academic_plan')echo 'active'; ?>">
      <a href="<?php echo base_url(); ?>academic_planner/acad_planner">
        <i class="far fa-calendar-alt">
        </i> 
        <span>
          <?php echo get_phrase('academic_planner'); ?>
        </span>
      </a>
    </li>
    <?php }if(right_granted(array('noticeboard_view','noticeboard_manage','noticeboard_delete','circulars_view','circulars_delete','circulars_manage'))){ ?>
    <li class="<?php if($page_name=='noticeboard'||$page_name=='circulars'||$page_name=='circulars_staff')echo 'opened active'; ?>">
      <a href="#">
        <i class="fas fa-bullhorn">
        </i> 
        <span>
          <?php echo get_phrase('notices_and_circulars'); ?>
        </span>
      </a>
      <ul>
        <?php if(right_granted(array('noticeboard_view','noticeboard_manage','noticeboard_delete'))){ ?>
        <li class="<?php if($page_name=='noticeboard')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>noticeboards/noticeboard">
            <i class="fas fa-chalkboard">
            </i> 
            <span>
              <?php echo get_phrase('noticeboard'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted(array('circulars_view','circulars_delete','circulars_manage'))){ ?>
        <li class="<?php if($page_name=='circulars')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>circular/circulars">
            <i class="fas fa-bullseye">
            </i> 
            <span>
              <?php echo get_phrase('circulars'); ?>
            </span>
          </a>
        </li>
        <?php }{ ?>
        <li class="<?php if($page_name=='circulars_staff')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>circular_staff/circulars_staff">
            <i class="fas fa-bullseye">
            </i> 
            <span>
              <?php echo get_phrase('staff_circulars'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
      </ul>
    </li>
    <?php }if(right_granted(array('myprofilesettings_profilesetting'))){ ?>
    <li class="<?php if($page_name=='manage_profile')echo 'active'; ?>">
      <a href="<?php echo base_url(); ?>profile/manage_profile">
        <i class="far fa-user-circle">
        </i> 
        <span>
          <?php echo get_phrase('my_profile'); ?>
        </span>
      </a>
    </li>
    <?php }if(get_login_type_name($_SESSION['login_type'])=='admin'){if(right_granted(array('branches_view','branches_manage','branches_delete'))){ ?>
    <li class="<?php if($page_name=='manage_branch')echo 'active'; ?>">
      <a href="<?php echo base_url(); ?>branch/branches">
        <i class="far fa-building">
        </i> 
        <span>
          <?php echo get_phrase('branches'); ?>
        </span>
      </a>
    </li>
    <?php }}if(right_granted(array('message_manage'))){ ?>
    <li class="<?php if($page_name=='messages_subject_list'||$page_name=='messages_student_list'||$page_name=='messages')echo 'active'; ?>">
      <a href="<?php echo base_url(); ?>message/messages_subject_list">
        <i class="far fa-comment-dots">
        </i> 
        <span>
          <?php echo get_phrase('messages'); ?>
        </span>
      </a>
    </li>
    <?php }if(right_granted(array('backup_manage'))){ ?>
    <li class="<?php if($page_name=='backup'||$page_name == "manage_backup")echo 'active'; ?>">
      <a href="<?php echo base_url(); ?>Backup/manage_backup">
        <i class="fas fa-download">
        </i> 
        <span>
          <?php echo get_phrase('backup'); ?>
        </span>
      </a>
    </li>
    <?php }if(right_granted(array('manage_system_support'))){ ?>
    <li class="<?php if($page_name=='system_support'||$page_name=='view_support_problems'||$page_name=='video_tutorials')echo 'opened active'; ?>">
      <a href="#">
        <i class="fas fa-headset">
        </i> 
        <span>
          <?php echo get_phrase('system_support'); ?>
        </span>
      </a>
      <ul>
        <li class="<?php if($page_name=='system_support')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>support/supports">
            <i class="fas fa-headset">
            </i> 
            <span>
              <?php echo get_phrase('system_support'); ?>
            </span>
          </a>
        </li>
        <li class="<?php if($page_name=='view_support_problems')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>support/view_support_problems">
            <i class="fas fa-headset">
            </i> 
            <span>
              <?php echo get_phrase('view_support'); ?>
            </span>
          </a>
        </li>
        <li class="<?php if($page_name=='video_tutorials')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>tutorials/video_tutorials">
            <span>
              <i class="fas fa-users">
              </i>
              <?php echo get_phrase('video_tutorials'); ?>
            </span>
          </a>
        </li>
      </ul>
    </li>
    <?php }if(right_granted(array('systemadministration_manage'))){ ?>
    <li class="<?php if($page_name=='user_group_list'||$page_name=='action'||$page_name=='assign_rights'||$page_name=='admin_staff_listing'|| $page_name == 'reset_users_password' || $page_name=='attenndance_app_creds')echo 'opened active'; ?>">
      <a href="#">
        <i class="fas fa-user-shield">
        </i> 
        <span>
          <?php echo get_phrase('user_role_management'); ?>
        </span>
      </a>
      <ul>
        <li class="<?php if($page_name=='user_group_list'||$page_name=='assign_rights')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>user/user_groups">
            <span>
              <i class="fas fa-house-user">
              </i>
              <?php echo get_phrase('user_groups'); ?>
            </span>
          </a>
        </li>
        <li class="<?php if($page_name=='admin_staff_listing')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>user/admin_staff_list">
            <span>
              <i class="fas fa-users">
              </i>
              <?php echo get_phrase('users'); ?>
            </span>
          </a>
        </li>
        <li class="<?php if($page_name=='reset_users_password')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>user/reset_users_password">
            <span>
              <i class="fas fa-users">
              </i>
              <?php echo get_phrase('reset_user_password'); ?>
            </span>
          </a>
        </li>
        
        <li class="<?php if($page_name=='attenndance_app_creds')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>user/attenndance_app_creds">
            <span>
              <i class="fas fa-users">
              </i>
              <?php echo get_phrase('attenndance_app_creds'); ?>
            </span>
          </a>
        </li>
        
      </ul>
    </li>
    <?php } ?>
    <?php if(right_granted('manage_reports')){ ?>
    <li class="<?php if($page_name=='reports_listing')echo 'active'; ?>">
      <a href="<?php echo base_url(); ?>reports/reports_listing">
        <i class="fas fa-file-invoice">
        </i> 
        <span>
          <?php echo get_phrase('reports'); ?>
        </span>
      </a>
    </li>
    <?php } ?>
    <?php if(right_granted('branch_reports')){ ?>
    <li class="<?php if($page_name=='branch_reports_listing')echo 'active'; ?>">
      <a href="<?php echo base_url(); ?>branch_reporting/branch_reports_listing">
        <i class="far fa-copy">
        </i> 
        <span>
          <?php echo get_phrase('branch_reports'); ?>
        </span>
      </a>
    </li>
    <?php } ?>
    <?php if(right_granted('manage_virtual_class')){ ?>
    <li class="<?php if($page_name=='current_list'||$page_name=='past_list'||$page_name=='view_detail'||$page_name=='virtual_class_recordings' || $page_name=='subjectwise_recording')echo 'opened active'; ?>">
      <a href="#">
        <i class="fas fa-vr-cardboard">
        </i> 
        <span>
          <?php echo get_phrase('virtual_classes'); ?>
        </span>
      </a>
      <ul>
        <?php if(right_granted('current_virtual_classes')){ ?>
        <li class="<?php if($page_name=='current_list'||$page_name=='view_detail')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>virtualclass/vc_current_list">
            <span>
              <i class="far fa-list-alt">
              </i>
              <?php echo get_phrase('current_classes'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted('completed_virtual_classes')){ ?>
        <li class="<?php if($page_name=='past_list')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>virtualclass/vc_complete_list">
            <span>
              <i class="far fa-list-alt">
              </i>
              <?php echo get_phrase('complete_classes'); ?>
            </span>
          </a>
        </li>
        <?php }if(right_granted('virtual_classes_recording')){ ?>
        <li class="<?php if($page_name=='subjectwise_recording')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>virtualclass/subject_recording">
            <i class="fas fa-video">
            </i> 
            <span>
              <?php echo get_phrase('virtual_class_recordings'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>
    <?php if(right_granted('manage_activity')){ ?>
    <li class="<?php if($page_name=='activity_logs/activity_logs'||$page_name=='activity_logs/teacher_activity_progress_report' || $page_name=='activity_logs/academic_acknowledge_report')echo 'opened active'; ?>">
      <a href="#">
        <i class="fas fa-chalkboard-teacher">
        </i> 
        <span>
          <?php echo get_phrase('activity_logs'); ?>
        </span>
      </a>
      <ul>
        <?php if(right_granted('a_activity_logs')){ ?>
        <li class="<?php if($page_name=='activity_logs/activity_logs')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>activitylog/filter_view">
            <i class="fas fa-chalkboard-teacher">
            </i> 
            <span>
              <?php echo get_phrase('activity_logs'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
        <?php if(right_granted('progress_activity')){ ?>
        <li class="<?php if($page_name=='activity_logs/teacher_activity_progress_report')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>activitylog/teacher_activity_progress_report">
            <i class="fas fa-chalkboard-teacher">
            </i> 
            <span>
              <?php echo get_phrase('activity_progress_report'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
        <?php if(right_granted('a_academic_acknowledge_report')){ ?>
        <li class="<?php if($page_name=='activity_logs/academic_acknowledge_report')echo 'active'; ?>">
          <a href="<?php echo base_url(); ?>activitylog/academic_acknowledge_report">
            <i class="fas fa-chalkboard-teacher">
            </i> 
            <span>
              <?php echo get_phrase('academic_acknowledge_report'); ?>
            </span>
          </a>
        </li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>