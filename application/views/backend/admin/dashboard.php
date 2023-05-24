<?php
    //echo phpversion();
    // echo $subscription_message;exit;
    $this->benchmark->mark('code_start');
    // $this->load->helper("message");
    // send_sms("03415627372","Indici Edu","Testing",1,00);
?>
<script src="<?php echo base_url(); ?>assets/js/highcharts.js">
</script>
<script src="<?php echo base_url(); ?>assets/js/data.js">
</script>
<script src="<?php echo base_url(); ?>assets/js/drilldown.js">
</script>
<script src="<?php echo base_url(); ?>assets/js/exporting.js">
</script>

<?php

      
       
    
    $d_school_id = $this->uri->segment(3);
    $branch_name="";
    if($d_school_id==""){
        $d_school_id=$_SESSION['school_id'];
    }else{
        $scl_name=$this->db->query("select name , logo ,folder_name  from ".get_school_db().".school where school_id=$d_school_id")->result_array();
        if(count($scl_name)>0){
            $branch_name=$scl_name[0]['name'];
            $branch_logo=$scl_name[0]['logo'];
            $branch_folder=$scl_name[0]['folder_name'];
        }
    }
?>
<!--<style>-->
<!--    .highlight_day{font-size:15px;font-weight:500;font-family:ABeeZee;color:#fff;height:95px;padding:20px 0 0 0;text-align:center;}.w100{width:70%}.nav li a{text-transform:capitalize}.tile-stats.tile-red{background:#f56954}.tile-stats.tile1{background:#73d7d7}.tile-stats.tile2{background:#25a505}.tile-stats.tile3{background:#85c5a5}.tile-stats.tile4{background:#83cbff!important}.white{color:#fff}.white2{color:#fff!important}.hgh{min-height:340px;border:1px solid #cccCCC4D;margin:13px;box-shadow:0 0 7px -3px;padding-top:3px}.myhhh{font-size:12px!important;font-weight:700;text-align:center;color:#8d0303}.tile-stats{min-height:120px!important ; border-radius:5px!important;}.panel-options .bg{display:none!important}.week_name{background:#e5e5e8;text-align:center;color:#000;font-weight:600;letter-spacing:2px;font-family:ABeeZee}.week_name td{padding:10px!important;height:40px!important}.latest_event>a{color:#fff!important}#calendar_view_container table th,td{padding:0;text-align:center}#class_chart,#dash_single_staff_monthly,#dash_std_daily_attan,#dash_std_monthly_count,#dash_tech_attn_count,#monthly_fee,#monthly_fee_rev,#staff_count,#staff_name_chart,#student_exam_result,#teacher_exam_progress{overflow-y:auto;width:100%!important}.system_name.inline{display:inline-block;margin:0;padding:20px 0 5px;width:100%}.img-res{float:none;height:50px;width:auto}.col-mh{color:#4a8cbb;font-size:16px;font-weight:700;padding-top:20px;text-align:right;text-transform:uppercase}.top{padding-top:0}.blocks{margin:0 auto;text-align:right}#calendar_view_container table{width:100%;height:#100%}#calendar_view_container table td{border:1px solid #ebebeb;height:95px;min-width:130px;font-size:14px}#calendar_view_container table td ul.latest_event{color:rgba(255,25,30,.8);width:120px;padding:0 0 0 15px;list-style:decimal;cursor:pointer}#calendar_view_container table th{font-size:20px;text-align:center;min-width:130px}#calendar_view_container table td li span{color:rgba(56,113,255,.8)}.nav-tabs{background:0 0!important;padding:0;margin-top:0;margin-bottom:0}.nav-tabs>li>a{margin-right:4px;line-height:1.42857143;border-radius:3px 3px 0 0;background-color:#ebebeb!important}.nav.nav-tabs a{padding:5px 10px 5px 10px!important;margin-bottom:6px;}.highlight_day ul > li a {color: white !important;}-->
<!--    .container-fluid.foter-top {-->
<!--        display: none;-->
<!--    }-->
<!--</style>-->
    <?php if($theme_val == "4"): ?>
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-md-12 topbar">
            <?php if($branch_name!=""){  ?>
            <div class="row">
                <div class="blocks col-md-8">
                  <h3 class="col-mh" style="margin-top: -10px;margin-left: -20px;text-align: left;">
                    <?php echo $branch_name  ?>
                  </h3>
                </div>
                <div class="col-md-4 top">
                  <a href="<?php echo base_url(); ?>branch_reporting/branch_reports_listing"class="btn btn-primary pull-right">
                    <?php echo get_phrase('back_to_branches_list'); ?>
                  </a>
                </div>
            </div>
            <?php } ?>
            <?php if(!$action_blocked){ ?> 
            <div class="row p-0">
            <?php } ?>
                <div class="col-sm-12 col-md-4">
                    <div style="display:flex;">
                        <?php
                            $apiKey = "58af093099db9411473ea6e825ded90f";
                            $cityId = "1162015";
                            $googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;
                            
                            $ch = curl_init();
                            
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                            curl_setopt($ch, CURLOPT_VERBOSE, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            $response = curl_exec($ch);
                            
                            curl_close($ch);
                            $data = json_decode($response);
                            $currentTime = time();
                        ?>
                        <?php if($res[0]['profile_pic']==""){ ?>
                        <img alt=""alt=""  src="<?php echo get_default_pic() ?>" style="height: 50px;border-radius: 8px;">
                        <?php }else{ ?>
                        <img alt=""alt=""alt=""  src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1) ?>" style="height: 50px;border-radius: 8px;">
                        <?php } ?>
                        <span style="margin-top: -10px;margin-left: 5px;">
                            <h3 style="margin-top: 10px;">Welcome, <?php echo $res[0]['name']; ?> 	
                                <p><?= date("l")." ". ordinal(date("d"))." ".date("M, Y")  ?>
                                    <!--<div class="weather-forecast">-->
                                        <!--<img src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png" class="weather-icon" /> -->
                                        <br>
                                        <b>Weather </b>
                                        <?php echo ucwords($data->weather[0]->description); ?>
                                        <?php echo $data->main->temp_max; ?>°C
                                    <!--</div>-->
                                </p>
                            </h3>
                        </span>
                    </div>		
                </div>
                <div class="col-sm-12 col-md-8 d-lg-flex mainStats justify-content-lg-end" style="align-items: flex-end;">
                  <ul class="nav nav-tabs">
                    <?php if(right_granted('teacher_view')){ ?>
                    <li>
                      <a href="#home"class="active"data-toggle="tab">
                        <?php echo get_phrase('home'); ?>
                      </a>
                    </li>
                    <?php }if(right_granted('staff_view')){ ?>
                    <li>
                      <a href="#staff"data-toggle="tab"id="staff_tab">
                        <?php echo get_phrase('staff'); ?>
                      </a>
                    </li>
                    <?php }if(right_granted(array('candidatelist_view','students_view'))){ ?>
                    <li>
                      <a href="#student"data-toggle="tab"id="panel_student">
                        <?php echo get_phrase('student'); ?>
                      </a>
                    </li>
                    <?php }if(right_granted('feetype_view')){ ?>
                    <li>
                      <a href="#fees"data-toggle="tab"id="fee_student">
                        <?php echo get_phrase('fee_details'); ?>
                      </a>
                    </li>
                    <?php }if(right_granted('staff_view')){ ?>
                    <li>
                      <a href="#exame"data-toggle="tab"id="teacher_exam">
                        <?php echo get_phrase('teacher_performance'); ?>
                      </a>
                    </li>
                    <?php } ?>
                    <?php if(right_granted('calender_view')){ ?>
                    <li>
                      <a href="#calendar_view_container"data-toggle="tab"id="calendar_view">
                        <?php echo get_phrase('Calendar'); ?>
                      </a>
                    </li>
                    <?php } ?>
                    <li>
                      <a href="#branches"data-toggle="tab"id="branch_view">
                        <?php echo get_phrase('Branch'); ?>
                      </a>
                    </li>
                  </ul> 
                </div>
            </div>
        </div>
    </div>    
    <?php else: ?>
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-md-12 topbar">
            <?php if($branch_name!=""){  ?>
            <div class="row">
                <div class="blocks col-md-8">
                  <h3 class="col-mh" style="margin-top: -10px;margin-left: -20px;text-align: left;">
                    <?php echo $branch_name  ?>
                  </h3>
                </div>
                <div class="col-md-4 top">
                  <a href="<?php echo base_url(); ?>branch_reporting/branch_reports_listing"class="btn btn-primary pull-right">
                    <?php echo get_phrase('back_to_branches_list'); ?>
                  </a>
                </div>
            </div>
        
            <?php } ?>
            <?php if(!$action_blocked){ ?> 
            <div class="row p-0">
            <?php } ?>
                <div class="col-sm-12 col-md-4">
                <!--<h3 class="system_name"style="margin:0 0 5px 0!important">-->
                <!--  Dashboard-->
                <!--</h3>-->
                <div style="display:flex;">
                    <?php
                        $apiKey = "58af093099db9411473ea6e825ded90f";
                        $cityId = "1162015";
                        $googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;
                        
                        $ch = curl_init();
                        
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_VERBOSE, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);
                        
                        curl_close($ch);
                        $data = json_decode($response);
                        $currentTime = time();
                    ?>
                    <?php 
                        $link = display_link($res[0]['profile_pic'],'profile_pic',1);
                        if (file_exists($link)) {
                            echo '<img alt=""alt=""alt=""class="img-circle"src="<?php echo  $link;?>"width="60">';
                        }else{
                            echo '<img alt=""alt=""class="img-circle"src="'. get_default_pic().'"width="60">';
                        }
                    ?>
                    <span style="margin-top: -10px;margin-left: 5px;">
                        <h3 style="margin-top: 10px;">Welcome, <?php echo $res[0]['name']; ?> 	
                            <p><?= date("l")." ". ordinal(date("d"))." ".date("M, Y")  ?>
                                <!--<div class="weather-forecast">-->
                                    <!--<img src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png" class="weather-icon" /> -->
                                    <br>
                                    <b>Weather </b>
                                    <?php echo ucwords($data->weather[0]->description); ?>
                                    <?php echo $data->main->temp_max; ?>°C
                                <!--</div>-->
                            </p>
                        </h3>
                    </span>
                </div>		
            </div>
            <div class="col-sm-12 col-md-8 d-lg-flex justify-content-lg-end" style="align-items: flex-end;">
              <ul class="nav nav-tabs">
                <?php if(right_granted('teacher_view')){ ?>
                <li>
                  <a href="#home"class="active"data-toggle="tab">
                    <?php echo get_phrase('home'); ?>
                  </a>
                </li>
                <?php }if(right_granted('staff_view')){ ?>
                <li>
                  <a href="#staff"data-toggle="tab"id="staff_tab">
                    <?php echo get_phrase('staff'); ?>
                  </a>
                </li>
                <?php }if(right_granted(array('candidatelist_view','students_view'))){ ?>
                <li>
                  <a href="#student"data-toggle="tab"id="panel_student">
                    <?php echo get_phrase('student'); ?>
                  </a>
                </li>
                <?php }if(right_granted('feetype_view')){ ?>
                <li>
                  <a href="#fees"data-toggle="tab"id="fee_student">
                    <?php echo get_phrase('fee_details'); ?>
                  </a>
                </li>
                <?php }if(right_granted('staff_view')){ ?>
                <li>
                  <a href="#exame"data-toggle="tab"id="teacher_exam">
                    <?php echo get_phrase('teacher_performance'); ?>
                  </a>
                </li>
                <?php } ?>
                <?php if(right_granted('calender_view')){ ?>
                <li>
                  <a href="#calendar_view_container"data-toggle="tab"id="calendar_view">
                    <?php echo get_phrase('Calendar'); ?>
                  </a>
                </li>
                <?php } ?>
                <li>
                  <a href="#branches"data-toggle="tab"id="branch_view">
                    <?php echo get_phrase('Branch'); ?>
                  </a>
                </li>
              </ul> 
            </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    

    <?php
        
        $acad_year_excel        =  check_import_status_bit("acad_year");
        $acad_terms_excel       =  check_import_status_bit("acad_terms");
        $department_excel       =  check_import_status_bit("department");
        $class_excel            =  check_import_status_bit("class");
        $class_section_excel    =  check_import_status_bit("class_section");
        $subject_category_excel =  check_import_status_bit("subject_category");
        $subjects_excel         =  check_import_status_bit("subjects");
        $student_category_excel =  check_import_status_bit("student_category");
        $student_excel          =  check_import_status_bit("student");
        $designation_excel      =  check_import_status_bit("designation");
        $staff_excel            =  check_import_status_bit("staff");
        if($acad_year_excel == 0 && $acad_terms_excel == 0 && $department_excel == 0 && $class_excel == 0 && $class_section_excel == 0 && $subject_category_excel == 0 && $subjects_excel == 0 && $student_category_excel == 0 && $student_excel == 0 && $designation_excel == 0 && $staff_excel == 0)
        {
            
    ?>
         <div class="col-lg-12 text-center">
                <h4 class="text-danger"><b>Please Import School Data Using Excel Sheets</b></h4>
                <img src="<?=base_url()?>assets/scanningwoohoo.gif" width="100"><br><br>
                <a href="<?=base_url()?>sheets/import_list" class="modal_save_btn">Go to Page</a>
                <hr>
            </div>
    <?php } ?>
        
    
<div class="tab-content">
  <div class="tab-pane active"id="home">
    <br>
    
    <?php if($theme_val == "4"): ?>
        <div class="row stats">
        <div class="col-sm-12 col-md-5 col-lg-5">
            <div class="mainStats" style="width:100%; height:auto;">
                <div class="row">
                    <h4 class="col-md-12 Headings">Quick Actions</h4>
                    <ul class="list-group list-group-flush col-md-6 col-sm-12">
                        <a href="<?=base_url()?>departments/departments_listing">
                          <li class="list-group-item"> <i class="fas fa-building"></i>  Departments</li>
                        </a>  
                        <a href="<?=base_url()?>departments/classes">
                            <li class="list-group-item"> <i class="fas fa-users"></i>  Classes</li>
                        </a>
                        <a href="<?=base_url()?>departments/section_listing">
                            <li class="list-group-item"> <i class="fas fa-chalkboard-teacher"></i> Sections</li>
                        </a>
                        <a href="<?=base_url()?>time_table/class_routine">
                            <li class="list-group-item"> <i class="fas fa-table"></i> Timetable</li>
                        </a>
                        <a href="<?=base_url()?>class_chalan_form/class_chalan_f">
                            <li class="list-group-item"> <i class="fas fa-print"> </i>  Challan Forms</li>
                        </a>
                    </ul>
                    <ul class="list-group list-group-flush col-md-6 col-sm-12">
                        <a href="<?=base_url()?>c_student/student_add">    
                            <li class="list-group-item"> <i class="fas fa-user-plus"></i>  New Admission</li>
                        </a>
                        <a href="<?=base_url()?>noticeboards/noticeboard">
                            <li class="list-group-item"> <i class="fas fa-clipboard-list"></i>  Noticeboard</li>
                        </a>
                        <a href="<?=base_url()?>virtualclass/vc_current_list">
                            <li class="list-group-item"> <i class="fas fa-vr-cardboard"></i>  Virtual Class</li>
                        </a>
                        <a href="<?=base_url()?>c_student/get_student_information">
                            <li class="list-group-item"> <i class="fas fa-user"></i> Students</li>
                        </a>
                        <a href="<?=base_url()?>attendance_staff/manage_staff_attendance/<?php echo date('d/m/Y'); ?>">
                            <li class="list-group-item"> <i class="fas fa-print"> </i> Mark Staff Attendnace</li>
                        </a>
                    </ul>
                </div>    
            </div>
        </div>
        <div class="col-sm-12 col-md-7 col-lg-7 mainStats mb_margin">
            
            <style>
                .media {
                    display: -webkit-box;
                }
                .mr-2, [dir="ltr"] .mx-2 {
                  margin-right: 1.5rem !important;
                }
                .media-aside {
                  display: -webkit-box;
                  display: -ms-flexbox;
                  display: flex;
                }
                .align-self-start {
                  -ms-flex-item-align: start !important;
                  align-self: flex-start !important;
                }
                
                .b-avatar.badge-light-primary {
                  background-color: rgba(115,103,240,.12);
                }
                .b-avatar.badge-light-primary {
                  color: #7367f0;
                }
                .b-avatar.badge-light-info {
                  background-color: rgba(0,207,232,.12);
                }
                .b-avatar.badge-light-info {
                  color: #00cfe8;
                }
                
                .b-avatar.badge-light-danger {
                  background-color: rgba(234,84,85,.12);
                }
                .b-avatar.badge-light-danger {
                  color: #ea5455;
                }
                
                .b-avatar.badge-light-success  {
                  background-color: rgba(40,199,111,.12);
                }
                .b-avatar.badge-light-success  {
                  color: #28c76f;
                }
                
                .b-avatar {
                  text-align: center;
                }
                .rounded-circle {
                  border-radius: 50% !important;
                }
                .b-avatar {
                  font-size: .857rem;
                  font-weight: 600;
                  color: #fff;
                }
                .b-avatar {
                  display: -webkit-inline-box;
                  display: -ms-inline-flexbox;
                  display: inline-flex;
                  -webkit-box-align: center;
                  -ms-flex-align: center;
                  align-items: center;
                  -webkit-box-pack: center;
                  -ms-flex-pack: center;
                  justify-content: center;
                  vertical-align: middle;
                  -ms-flex-negative: 0;
                  flex-shrink: 0;
                  width: 2.5rem;
                  height: 2.5rem;
                  font-size: inherit;
                  font-weight: 400;
                  line-height: 1;
                  max-width: 100%;
                  max-height: auto;
                  overflow: visible;
                  position: relative;
                  -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
                  transition: color .15s ease-in-out,background-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
                  transition: color .15s ease-in-out,background-color .15s ease-in-out,box-shadow .15s ease-in-out;
                  transition: color .15s ease-in-out,background-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
                }
                .b-avatar .b-avatar-custom, [dir] .b-avatar .b-avatar-img, [dir] .b-avatar .b-avatar-text {
                  border-radius: inherit;
                }
                .b-avatar .b-avatar-custom, .b-avatar .b-avatar-img, .b-avatar .b-avatar-text {
                  width: 100%;
                  height: 100%;
                  overflow: hidden;
                  display: -webkit-box;
                  display: -ms-flexbox;
                  display: flex;
                  -webkit-box-pack: center;
                  -ms-flex-pack: center;
                  justify-content: center;
                  -webkit-box-align: center;
                  -ms-flex-align: center;
                  align-items: center;
                  -webkit-mask-image: radial-gradient(#fff,#000);
                  mask-image: radial-gradient(#fff,#000);
                }
                *, ::after, ::before {
                  -webkit-box-sizing: border-box;
                  box-sizing: border-box;
                }
                .b-avatar.badge-light-primary {
                  color: #7367f0;
                }
               .b-avatar {
                  text-align: center;
                }
                .b-avatar {
                  font-size: .857rem;
                  font-weight: 600;
                  color: #fff;
                }
                .b-avatar {
                  font-size: inherit;
                  font-weight: 400;
                  line-height: 1;
                }
            </style>
            
            <div class="row">
                <h4 class="col-md-12 Headings">Stats</h4>
                <?php if(right_granted('teacher_view')){ ?>
                <div class="col-sm-6 col-xl-4 col-xs-6 mb-3">
                    <a href="<?php echo base_url(); ?>user/teacher" target="_blank">
                    <div class="media">
                        <div class="media-aside mr-2 align-self-start">
                            <span class="b-avatar badge-light-primary rounded-circle" data-toggle="tooltip" data-placement="top" title="Total Teachers in School" style="width: 40px; height: 40px;">
                                <span class="b-avatar-custom" >
                                    <i class="fas main_counter_icon fa-user-tie"></i>
                                </span>
                            </span>    
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"><?php echo $teac_count; ?></h4>
                            <p class="card-text font-small-3 mb-0 font-weight-bold"> Teachers</p>
                        </div>
                    </div>
                    </a>
                </div>
                <?php }if(right_granted('staff_view')){ ?>
                <div class="col-sm-6 col-xl-4 col-xs-6 mb-3">
                    <a href="<?php echo base_url(); ?>user/staff_listing" target="_blank">
                    <div class="media">
                        <div class="media-aside mr-2 align-self-start">
                            <span class="b-avatar badge-light-info rounded-circle" data-toggle="tooltip" data-placement="top" title="Total Staff in School" style="width: 40px; height: 40px;">
                                <span class="b-avatar-custom">
                                    <i class="fas main_counter_icon fa-user-friends"></i>
                                 </span>
                            </span>      
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"><?php echo $staff_count; ?></h4>
                            <p class="card-text font-small-3 mb-0 font-weight-bold"> Staff </p>
                        </div>
                    </div>
                    </a>
                </div>
                <?php }if(right_granted('students_view')){ ?>
                <div class="col-sm-6 col-xl-4 col-xs-6 mb-3">
                    <a href="<?php echo base_url(); ?>c_student/get_student_information" target="_blank">
                    <div class="media">
                        <div class="media-aside mr-2 align-self-start">
                            <span class="b-avatar badge-light-danger rounded-circle" data-toggle="tooltip" data-placement="top" title="Total Students in School" style="width: 40px; height: 40px;">
                                <span class="b-avatar-custom">
                                    <i class="fas main_counter_icon fa-male"></i>
                                 </span>
                            </span>  
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"><?php echo $student_count; ?></h4>
                            <p class="card-text font-small-3 mb-0 font-weight-bold">  Students </p>
                        </div>
                    </div>
                    </a>
                </div>
                <?php }if(right_granted('candidatelist_view')){ ?>
                <div class="col-sm-6 col-xl-4 col-xs-6 mb-3">
                    <a href="<?php echo base_url(); ?>c_student/student_pending" target="_blank">
                    <div class="media">
                        <div class="media-aside mr-2 align-self-start">
                            <span class="b-avatar badge-light-success rounded-circle" data-toggle="tooltip" data-placement="top" title="Total Candidates in School" style="width: 40px; height: 40px;">
                                <span class="b-avatar-custom">
                                    <i class="fas main_counter_icon fa-user-clock"></i>
                                <!--<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>-->
                                 </span>
                            </span>  
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"><?php echo $candidate_count; ?></h4>
                            <p class="card-text font-small-3 mb-0 font-weight-bold"> Candidates </p>
                        </div>
                    </div>
                    </a>
                </div>
               <?php } ?>

               <?php if(right_granted('teacher_view')){ ?>
                <div class="col-sm-6 col-xl-4 col-xs-6 mb-3">
                    <a href="<?php echo base_url(); ?>Inquiries/admission_inquiry_view" target="_blank">
                    <div class="media">
                        <div class="media-aside mr-2 align-self-start">
                            <span class="b-avatar badge-light-success rounded-circle" data-toggle="tooltip" data-placement="top" title="New Admission Inquiries" style="width: 40px; height: 40px;">
                                <span class="b-avatar-custom">
                                    <i class="fas main_counter_icon fa-user-tie"></i>
                                </span>
                            </span>    
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0">
                                <?php $qur = "select count(s_a_i_id) as admiss_inquiry_count from ".get_school_db().".sch_admission_inquiries where s_a_i_status=0 and school_id=$d_school_id"; $admiss_inquiry_count = $this->db->query($qur)->row(); echo $admiss_inquiry_count->admiss_inquiry_count; ?>
                            </h4>
                            <p class="card-text font-small-3 mb-0 font-weight-bold"><?php echo get_phrase('admission_inquiries'); ?></p>
                        </div>
                    </div>
                    </a>
                </div>
                <?php }if(right_granted('staff_view')){ ?>
                <div class="col-sm-6 col-xl-4 col-xs-6 mb-3">
                    <a href="<?php echo base_url(); ?>Inquiries/general_inquiry_view/" target="_blank">
                    <div class="media">
                        <div class="media-aside mr-2 align-self-start">
                            <span class="b-avatar badge-light-danger rounded-circle" data-toggle="tooltip" data-placement="top" title="New General Inquiries" style="width: 40px; height: 40px;">
                                <span class="b-avatar-custom">
                                    <i class="fas main_counter_icon fa-user-friends"></i>
                                 </span>
                            </span>      
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0">
                                <?php $qur1 = "select count(s_g_i_id) as general_inquiry_count  from ".get_school_db().".sch_general_inquiries where s_g_i_status=0 and school_id=$d_school_id"; $general_inquiry_count = $this->db->query($qur1)->row(); echo $general_inquiry_count->general_inquiry_count; ?>
                            </h4>
                            <p class="card-text font-small-3 mb-0 font-weight-bold">General Inquiries </p>
                        </div>
                    </div>
                    </a>
                </div>
                <?php }if(right_granted('students_view')){ ?>
                <div class="col-sm-6 col-xl-4 col-xs-6 mb-3">
                    <a href="<?php echo base_url(); ?>Jobs/view_jobs" target="_blank">
                    <div class="media">
                        <div class="media-aside mr-2 align-self-start">
                            <span class="b-avatar badge-light-info rounded-circle" data-toggle="tooltip" data-placement="top" title="New Jobs in School" style="width: 40px; height: 40px;">
                                <span class="b-avatar-custom">
                                    <i class="fas main_counter_icon fa-male"></i>
                                 </span>
                            </span>  
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"><?= $jobs_count ?></h4>
                            <p class="card-text font-small-3 mb-0 font-weight-bold">  Jobs </p>
                        </div>
                    </div>
                    </a>
                </div>
                <?php }if(right_granted('candidatelist_view')){ ?>
                <div class="col-sm-6 col-xl-4 col-xs-6 mb-3">
                    <a href="<?php echo base_url(); ?>Jobs/view_job_applications" target="_blank">
                    <div class="media">
                        <div class="media-aside mr-2 align-self-start">
                            <span class="b-avatar badge-light-primary rounded-circle" data-toggle="tooltip" data-placement="top" title="New Job Applications" style="width: 40px; height: 40px;">
                                <span class="b-avatar-custom">
                                    <i class="fas main_counter_icon fa-user-clock"></i>
                                </span>
                            </span>  
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"><?= $total_job_applications ?></h4>
                            <p class="card-text font-small-3 mb-0 font-weight-bold">  Jobs Applications</p>
                        </div>
                    </div>
                    </a>
                </div>
               <?php } ?>
           </div>
           
           <?php /* 
            <?php if(right_granted('teacher_view')){ ?>
                <div class="col-sm-12 col-md-3">
                  <a href="<?php echo base_url(); ?>user/teacher" target="_blank">
                    <div class="tile-stats tile-green tile_custom">
                      <div class="icon border_green">
                        <i class="fas main_counter_icon fa-user-tie">
                        </i>
                      </div>
                      <div class="num"data-delay="0"data-duration="800"data-end="<?php echo $teac_count; ?>"data-postfix=""data-start="0">
                        <?php echo $teac_count; ?>
                      </div>
                      <h3 class="white2">
                        <?php echo get_phrase('teacher'); ?>
                      </h3>
                      <p>
                        <?php echo get_phrase('total_teachers'); ?>
                      </p>
                    </div>
                  </a>
                </div>
            <?php }if(right_granted('staff_view')){ ?>
                <div class="col-sm-12 col-md-3">
                  <a href="<?php echo base_url(); ?>user/staff_listing" target="_blank">
                    <div class="tile-stats tile-blue tile_custom">
                      <div class="icon border_blue">
                        <i class="fas main_counter_icon fa-user-friends">
                        </i>
                      </div>
                      <div class="num"data-delay="0"data-duration="800"data-end="<?php echo $staff_count; ?>"
                      data-postfix=""data-start="0">
                        <?php echo $staff_count; ?>
                      </div>
                      <h3 class="white2">
                        <?php echo get_phrase('staff'); ?>
                      </h3>
                      <p>
                        <?php echo get_phrase('other_staff'); ?>
                      </p>
                    </div>
                  </a>
                </div>
            <?php }if(right_granted('students_view')){ ?>
                <div class="col-sm-12 col-md-3">
                  <a href="<?php echo base_url(); ?>c_student/student_information" target="_blank">
                    <div class="tile-stats tile-red tile_custom">
                      <div class="icon border_orange">
                        <i class="fas main_counter_icon fa-male">
                        </i>
                      </div>
                      <div class="num" data-delay="<?php echo $student_count; ?>" data-duration="800"data-end="<?php echo $student_count; ?>"  data-postfix=""  data-start="0">
                        <?php echo $student_count; ?>
                      </div>
                      <h3 class="white2">
                        <?php echo get_phrase('Student'); ?>
                      </h3>
                      <p>
                        <?php echo get_phrase('total_student'); ?>
                      </p>
                    </div>
                  </a>
                </div>
            <?php }if(right_granted('candidatelist_view')){ ?>
                <div class="col-sm-12 col-md-3">
                  <a href="<?php echo base_url(); ?>c_student/student_pending" target="_blank">
                    <div class="tile-stats tile-purple tile_custom">
                      <div class="icon border_purple">
                        <i class="fas main_counter_icon fa-user-clock">
                        </i>
                      </div>
                      <div class="num"data-delay="<?php echo $candidate_count; ?>"data-duration="800" data-end="<?php echo $candidate_count; ?>"data-postfix=""data-start="0">
                        <?php 
                          echo $candidate_count;
                        ?>
                    </div>
                    <h3 class="white2">
                      <?php echo get_phrase('Candidate'); ?>
                    </h3>
                    <p>
                      <?php echo get_phrase('total_candidate'); ?>
                    </p>
                    </div>
                  </a>
              </div>
            <?php } ?>
             */ ?>
            
            <!--End Panel Body Stats-->
        </div>
    </div>
    <?php else: ?>
        <!--First Row-->
        <div class="row stats">
          <!--Panel body Start Stats-->
          <?php if(right_granted('teacher_view')){ ?>
            <div class="col-sm-12 col-md-3" data-toggle="tooltip" data-placement="top" title="Total Teachers in School">
              <a href="<?php echo base_url(); ?>user/teacher" target="_blank">
                <div class="tile-stats tile-green tile_custom">
                  <div class="icon border_green">
                    <i class="fas main_counter_icon fa-user-tie">
                    </i>
                  </div>
                  <div class="num"data-delay="0"data-duration="800"data-end="<?php echo $teac_count; ?>"data-postfix=""data-start="0">
                    <?php echo $teac_count; ?>
                  </div>
                  <h3 class="white2">
                    <?php echo get_phrase('teachers'); ?>
                  </h3>
                  <p>
                    <?php echo get_phrase('total_teachers'); ?>
                  </p>
                </div>
              </a>
            </div>
            <?php }if(right_granted('staff_view')){ ?>
            <div class="col-sm-12 col-md-3" data-toggle="tooltip" data-placement="top" title="Total Staff in School">
              <a href="<?php echo base_url(); ?>user/staff_listing" target="_blank">
                <div class="tile-stats tile-blue tile_custom">
                  <div class="icon border_blue">
                    <i class="fas main_counter_icon fa-user-friends">
                    </i>
                  </div>
                  <div class="num"data-delay="0"data-duration="800"data-end="<?php echo $staff_count; ?>"
                  data-postfix=""data-start="0">
                    <?php echo $staff_count; ?>
                  </div>
                  <h3 class="white2">
                    <?php echo get_phrase('staff'); ?>
                  </h3>
                  <p>
                    <?php echo get_phrase('other_staff'); ?>
                  </p>
                </div>
              </a>
            </div>
            <?php }if(right_granted('students_view')){ ?>
            <div class="col-sm-12 col-md-3" data-toggle="tooltip" data-placement="top" title="Total Studens in School">
              <a href="<?php echo base_url(); ?>c_student/student_information" target="_blank">
                <div class="tile-stats tile-red tile_custom">
                  <div class="icon border_orange">
                    <i class="fas main_counter_icon fa-male">
                    </i>
                  </div>
                  <div class="num" data-delay="<?php echo $student_count; ?>" data-duration="800"data-end="<?php echo $student_count; ?>"  data-postfix=""  data-start="0">
                    <?php echo $student_count; ?>
                  </div>
                  <h3 class="white2">
                    <?php echo get_phrase('Students'); ?>
                  </h3>
                  <p>
                    <?php echo get_phrase('total_students'); ?>
                  </p>
                </div>
              </a>
            </div>
            <?php }if(right_granted('candidatelist_view')){ ?>
            <div class="col-sm-12 col-md-3" data-toggle="tooltip" data-placement="top" title="Total Candidates in School">
              <a href="<?php echo base_url(); ?>c_student/student_pending" target="_blank">
                <div class="tile-stats tile-purple tile_custom">
                  <div class="icon border_purple">
                    <i class="fas main_counter_icon fa-user-clock">
                    </i>
                  </div>
                  <div class="num"data-delay="<?php echo $candidate_count; ?>"data-duration="800" data-end="<?php echo $candidate_count; ?>"data-postfix=""data-start="0">
                    <?php 
                      echo $candidate_count;
                    ?>
                </div>
                <h3 class="white2">
                  <?php echo get_phrase('Candidates'); ?>
                </h3>
                <p>
                  <?php echo get_phrase('total_candidates'); ?>
                </p>
                </div>
              </a>
          </div>
          <?php } ?>
    
          <?php if($_SESSION['login_type']==1 || $_SESSION['login_type']==2){ ?> 
            <!--End Panel Body Stats-->
        </div>
        
        <!--Second Row-->
        <div class="row">
            <!--Panel Body Quick Menu--> 
            <?php if(right_granted('departments_view')){ ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
                  <a href="<?=base_url()?>departments/departments_listing">
                    <div class="text-center quick_links gradientone">
                      <i class="fas fa-building"></i> 
                      <span class="quick_link_txt">Departments
                      </span>
                    </div>
                  </a>
                </div>
            <?php } ?>
            <?php if(right_granted('classes_view')){ ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
                  <a href="<?=base_url()?>departments/classes">
                    <div class="text-center quick_links gradienttwo">
                      <i class="fas fa-users">
                      </i> 
                      <span class="quick_link_txt">Classes
                      </span>
                    </div>
                  </a>
                </div>
            <?php } ?>
            <?php if(right_granted('sections_view')){ ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
                  <a href="<?=base_url()?>departments/section_listing">
                    <div class="text-center quick_links gradientone">
                      <i class="fas fa-chalkboard-teacher">
                      </i> 
                      <span class="quick_link_txt">Sections
                      </span>
                    </div>
                  </a>
                </div>
            <?php } ?>
            <?php if(right_granted('managetimetable_view')){ ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
                  <a href="<?=base_url()?>time_table/class_routine">
                    <div class="text-center quick_links gradienttwo">
                      <i class="fas fa-table">
                      </i> 
                      <span class="quick_link_txt">Timetables
                      </span>
                    </div>
                  </a>
                </div>
            <?php } ?>
            <?php if(right_granted('managechallanform_view')){ ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
                  <a href="<?=base_url()?>class_chalan_form/class_chalan_f">
                    <div class="text-center quick_links gradienttwo">
                      <i class="fas fa-print">
                      </i> 
                      <span class="quick_link_txt">Challan Forms
                      </span>
                    </div>
                  </a>
                </div>
            <?php } ?>
            <?php if(right_granted('newadmission_manage')){ ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
                  <a href="<?=base_url()?>c_student/student_add">
                    <div class="text-center quick_links gradientone">
                      <i class="fas fa-user-plus">
                      </i> 
                      <span class="quick_link_txt">New Admissions
                      </span>
                    </div>
                  </a>
                </div>
            <?php } ?>    
            <?php if(right_granted('noticeboard_view')){ ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
                  <a href="<?=base_url()?>noticeboards/noticeboard">
                    <div class="text-center quick_links gradienttwo">
                      <i class="fas fa-clipboard-list">
                      </i> 
                      <span class="quick_link_txt">Noticeboard
                      </span>
                    </div>
                  </a>
                </div>
            <?php } ?>  
            <?php if(right_granted('manage_virtual_class')){ ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
                  <a href="<?=base_url()?>virtualclass/vc_current_list">
                    <div class="text-center quick_links gradientone">
                      <i class="fas fa-vr-cardboard">
                      </i> 
                      <span class="quick_link_txt">Virtual Classes
                      </span>
                    </div>
                  </a>
                </div>
            <?php } ?>    
            <!--End Panel Body Quick Menu-->
        </div>
        <?php } ?>
        
        <br>
        <!--Third Row-->
        <div class="row stats">
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Total Admission Inquiries">
                <a href="<?php echo base_url(); ?>Inquiries/admission_inquiry_view" target="_blank"> 
                <div class="tile-stats tile-purple tile_custom" > 
                    <div class="icon border_purple"><i class="fas main_counter_icon fa-question-circle"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?php $qur = "select count(s_a_i_id) as admiss_inquiry_count from ".get_school_db().".sch_admission_inquiries where s_a_i_status=0 and school_id=$d_school_id"; $admiss_inquiry_count = $this->db->query($qur)->row(); echo $admiss_inquiry_count->admiss_inquiry_count; ?>" data-postfix="" data-start="0"></div> 
                    <h3><?php echo get_phrase('admission_inquiries'); ?></h3> 
                    <!--<p>Total Admission Inquiries</p> -->
                </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Total General Inquiries">
                <a href="<?php echo base_url(); ?>Inquiries/general_inquiry_view" target="_blank"> 
                <div class="tile-stats tile-green tile_custom"> 
                    <div class="icon border_green"><i class="fas main_counter_icon fa-question-circle"></i></div> 
                    <div class="num" data-delay="0" data-duration="800"data-end="<?php $qur1 = "select count(s_g_i_id) as general_inquiry_count  from ".get_school_db().".sch_general_inquiries where s_g_i_status=0 and school_id=$d_school_id"; $general_inquiry_count = $this->db->query($qur1)->row(); echo $general_inquiry_count->general_inquiry_count; ?>" data-postfix="" data-start="<?php echo ($general_inquiry_count->general_inquiry_count > 0) ? 0 : -1;?>"></div> 
                    <h3><?php echo get_phrase('general_inquiries'); ?></h3> 
                    <!--<p>Total General Inquiries</p> -->
                </div>
                </a>
            </div>
            
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Total Jobs In School">
                <a href="<?php echo base_url(); ?>Jobs/view_jobs" target="_blank">
                <div class="tile-stats tile-red tile_custom"> 
                    <div class="icon border_orange"><i class="fas main_counter_icon fa-user-tie"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?= $jobs_count ?>" data-postfix="" data-start="0"></div> 
                    <h3><?php echo get_phrase('jobs_posted'); ?></h3> 
                    <!--<p>Total Jobs</p> -->
                </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Total Job Applications">
                <a href="<?php echo base_url(); ?>Jobs/view_job_applications" target="_blank">
                <div class="tile-stats tile-blue tile_custom"> 
                    <div class="icon border_blue"><i class="fas main_counter_icon fa-file-alt"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?= $total_job_applications ?>" data-postfix="" data-start="0"></div> 
                    <h3><?php echo get_phrase('job_applications'); ?></h3> 
                    <!--<p>Total Jobs Applications</p> -->
                </div>
                </a>
            </div>
        </div>
        
        <br>
        <!--Fourth Row-->
        <div class="row stats">
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Students Birthday today">
                <a href="<?=base_url()?>c_student/students_birthday" target="_blank">
                <div class="tile-stats tile-blue tile_custom" > 
                    <div class="icon border_blue"><i class="fas main_counter_icon fa-birthday-cake"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $students_birthday_count?>" data-postfix="" data-start="<?php echo ($students_birthday_count > 0) ? 0 : -1;?>"></div> 
                    <h3><?php echo get_phrase('students_birthday'); ?><sup>Today</sup></h3> 
                    <!--<p>Total Students Birthday</p> -->
                </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Staff Birthday today"> 
                <a href="<?=base_url()?>dashboard/staff_birthday" target="_blank">
                <div class="tile-stats tile-red tile_custom"> 
                    <div class="icon border_orange"><i class="fas main_counter_icon fa-birthday-cake"></i></div> 
                    <div class="num" data-delay="0"data-duration="800"data-end="<?= $staff_birthday_count ?>" data-postfix="" data-start="<?php echo ($staff_birthday_count > 0) ? 0 : -1;?>"></div> 
                    <h3><?php echo get_phrase('staff_birthday'); ?><sup>Today</sup></h3> 
                    <!--<p>Total Staff Birthday</p> -->
                </div>
                </a>
            </div>
            
            
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Students on Leave today">
                <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
                <div class="tile-stats tile-green tile_custom"> 
                    <div class="icon border_green"><i class="fas main_counter_icon fa-user-minus"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $student_attendance[3]; ?>" data-postfix="" data-start="<?php echo ($student_attendance[3] > 0) ? 0 : -1;?>"></div> 
                    <h3><?php echo get_phrase('leave_students'); ?><sup>Today</sup></h3> 
                    <!--<p>Total Leave Students</p> -->
                </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Staff on Leave today"> 
                <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
                <div class="tile-stats tile-purple tile_custom"> 
                    <div class="icon border_purple"><i class="fas main_counter_icon fa-user-minus"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $staff_attendance[3]; ?>" data-postfix="" data-start="<?php echo ($staff_attendance[3] > 0) ? 0 : -1;?>"></div>
                    <h3><?php echo get_phrase('leave_staff'); ?><sup>Today</sup></h3> 
                    <!--<p>Total Leave Staff</p> -->
                </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Present Students today">
                <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
                <div class="tile-stats tile-red tile_custom"> 
                    <div class="icon border_orange"><i class="fas main_counter_icon fa-user-check"></i></div>
                    <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $student_attendance[1]; ?>" data-postfix="" data-start="<?php echo ($student_attendance[1] > 0) ? 0 : -1;?>"></div> 
                    <h3><?php echo get_phrase('present_students'); ?><sup>Today</sup></h3> 
                    <!--<p>Total Present Students</p> -->
                </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Absent Students today"> 
                <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
                <div class="tile-stats tile-purple tile_custom"> 
                    <div class="icon border_purple"><i class="fas main_counter_icon fa-user"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $student_attendance[2]; ?>" data-postfix="" data-start="<?php echo ($student_attendance[2] > 0) ? 0 : -1;?>"></div>
                    <h3><?php echo get_phrase('absent_students'); ?><sup>Today</sup></h3> 
                    <!--<p>Total Absent Students</p> -->
                </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Pesent Staff today">
                <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
                <div class="tile-stats tile-blue tile_custom"> 
                    <div class="icon border_blue"><i class="fas main_counter_icon fa-user-check"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $staff_attendance[1]; ?>" data-postfix="" data-start="<?php echo ($staff_attendance[1] > 0) ? 0 : -1;?>"></div> 
                    <h3><?php echo get_phrase('present_staff'); ?><sup>Today</sup></h3> 
                    <!--<p>Total Present Students</p> -->
                </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12" data-toggle="tooltip" data-placement="top" title="Absent Staff today"> 
                <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
                <div class="tile-stats tile-green tile_custom"> 
                    <div class="icon border_green"><i class="fas main_counter_icon fa-user"></i></div> 
                    <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $staff_attendance[2]; ?>" data-postfix="" data-start="<?php echo ($staff_attendance[2] > 0) ? 0 : -1;?>"></div>
                    <h3><?php echo get_phrase('absent_staff'); ?><sup>Today</sup></h3> 
                    <!--<p>Total Absent Students</p> -->
                </div>
                </a>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if($_SESSION['login_type']==1 || $_SESSION['login_type']==2){ ?> 
    <!--Second Row-->
    <?php /* 
    <div class="row">
        <!--Panel Body Quick Menu--> 
        <?php if(right_granted('departments_view')){ ?>
            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
              <a href="<?=base_url()?>departments/departments_listing">
                <div class="text-center quick_links gradientone">
                  <i class="fas fa-building">
                  </i> 
                  <span class="quick_link_txt">Departments
                  </span>
                </div>
              </a>
            </div>
        <?php } ?>
        <?php if(right_granted('classes_view')){ ?>
            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
              <a href="<?=base_url()?>departments/classes">
                <div class="text-center quick_links gradienttwo">
                  <i class="fas fa-users"></i> 
                  <span class="quick_link_txt">Classes
                  </span>
                </div>
              </a>
            </div>
        <?php } ?>
        <?php if(right_granted('sections_view')){ ?>
            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
              <a href="<?=base_url()?>departments/section_listing">
                <div class="text-center quick_links gradientone">
                  <i class="fas fa-chalkboard-teacher"></i> 
                  <span class="quick_link_txt">Sections
                  </span>
                </div>
              </a>
            </div>
        <?php } ?>
        <?php if(right_granted('managetimetable_view')){ ?>
            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
              <a href="<?=base_url()?>time_table/class_routine">
                <div class="text-center quick_links gradienttwo">
                  <i class="fas fa-table"></i> 
                  <span class="quick_link_txt">Timetables
                  </span>
                </div>
              </a>
            </div>
        <?php } ?>
        <?php if(right_granted('managechallanform_view')){ ?>
            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
              <a href="<?=base_url()?>class_chalan_form/class_chalan_f">
                <div class="text-center quick_links gradienttwo">
                  <i class="fas fa-print"> </i> 
                  <span class="quick_link_txt">Challan Forms
                  </span>
                </div>
              </a>
            </div>
        <?php } ?>
        <?php if(right_granted('newadmission_manage')){ ?>
            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
              <a href="<?=base_url()?>c_student/student_add">
                <div class="text-center quick_links gradientone">
                  <i class="fas fa-user-plus">
                  </i> 
                  <span class="quick_link_txt">New Admissions
                  </span>
                </div>
              </a>
            </div>
        <?php } ?>    
        <?php if(right_granted('noticeboard_view')){ ?>
            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
              <a href="<?=base_url()?>noticeboards/noticeboard">
                <div class="text-center quick_links gradienttwo">
                  <i class="fas fa-clipboard-list">
                  </i> 
                  <span class="quick_link_txt">Noticeboard
                  </span>
                </div>
              </a>
            </div>
        <?php } ?>  
        <?php if(right_granted('manage_virtual_class')){ ?>
            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 mt-4">
              <a href="<?=base_url()?>virtualclass/vc_current_list">
                <div class="text-center quick_links gradientone">
                  <i class="fas fa-vr-cardboard">
                  </i> 
                  <span class="quick_link_txt">Virtual Classes
                  </span>
                </div>
              </a>
            </div>
        <?php } ?>    
        <!--End Panel Body Quick Menu-->
    </div>
    */ ?>
    <?php } ?>
    <br>
    <!--Third Row-->
    <?php /* 
    <div class="row stats">
        <div class="col-sm-3 col-xs-12">
            <a href="<?php echo base_url(); ?>Inquiries/admission_inquiry_view" target="_blank"> 
            <div class="tile-stats tile-purple tile_custom" > 
                <div class="icon border_purple"><i class="fas main_counter_icon fa-question-circle"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?php $qur = "select count(s_a_i_id) as admiss_inquiry_count from ".get_school_db().".sch_admission_inquiries where s_a_i_status=0 and school_id=$d_school_id"; $admiss_inquiry_count = $this->db->query($qur)->row(); echo $admiss_inquiry_count->admiss_inquiry_count; ?>" data-postfix="" data-start="0"></div> 
                <h3><?php echo get_phrase('admission_inquiries'); ?></h3> 
                <p>Total Admission Inquiries</p> 
            </div>
            </a>
        </div>
        <div class="col-sm-3 col-xs-12">
            <a href="<?php echo base_url(); ?>Inquiries/general_inquiry_view" target="_blank"> 
            <div class="tile-stats tile-green tile_custom"> 
                <div class="icon border_green"><i class="fas main_counter_icon fa-question-circle"></i></div> 
                <div class="num" data-delay="0"data-duration="800"data-end="<?php $qur1 = "select count(s_g_i_id) as general_inquiry_count  from ".get_school_db().".sch_general_inquiries where s_g_i_status=0 and school_id=$d_school_id"; $general_inquiry_count = $this->db->query($qur1)->row(); echo $general_inquiry_count->general_inquiry_count; ?>"data-postfix="" data-start="0"></div> 
                <h3><?php echo get_phrase('general_inquiries'); ?></h3> 
                <p>Total General Inquiries</p> 
            </div>
            </a>
        </div>
        
        <div class="col-sm-3 col-xs-12">
            <a href="<?php echo base_url(); ?>Jobs/view_jobs" target="_blank">
            <div class="tile-stats tile-red tile_custom"> 
                <div class="icon border_orange"><i class="fas main_counter_icon fa-user-tie"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?= $jobs_count ?>" data-postfix="" data-start="0"></div> 
                <h3><?php echo get_phrase('jobs_posted'); ?></h3> 
                <p>Total Jobs</p> 
            </div>
            </a>
        </div>
        <div class="col-sm-3 col-xs-12">
            <a href="<?php echo base_url(); ?>Jobs/view_job_applications" target="_blank">
            <div class="tile-stats tile-blue tile_custom"> 
                <div class="icon border_blue"><i class="fas main_counter_icon fa-file-alt"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?= $total_job_applications ?>" data-postfix="" data-start="0"></div> 
                <h3><?php echo get_phrase('job_applications'); ?></h3> 
                <p>Total Jobs Applications</p> 
            </div>
            </a>
        </div>
    </div>
    */ ?>
    <!--<br>-->
    <!--birthdays and attendance stats have been commented instructed by sir zeeshan-->
    <?php
    /*
    <!--Fourth Row-->
    <div class="row stats">
        <div class="col-sm-3 col-xs-12">
            <a href="<?=base_url()?>c_student/students_birthday" target="_blank">
            <div class="tile-stats tile-blue tile_custom" > 
                <div class="icon border_blue"><i class="fas main_counter_icon fa-birthday-cake"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $students_birthday_count?>" data-postfix="" data-start="<?php echo ($students_birthday_count > 0) ? 0 : -1;?>"></div> 
                <h3><?php echo get_phrase('students_birthday'); ?><sup>Today</sup></h3> 
                <p>Total Students Birthday</p> 
            </div>
            </a>
        </div>
        <div class="col-sm-3 col-xs-12"> 
            <a href="<?=base_url()?>dashboard/staff_birthday" target="_blank">
            <div class="tile-stats tile-red tile_custom"> 
                <div class="icon border_orange"><i class="fas main_counter_icon fa-birthday-cake"></i></div> 
                <div class="num" data-delay="0"data-duration="800"data-end="<?= $staff_birthday_count ?>" data-postfix="" data-start="<?php echo ($staff_birthday_count > 0) ? 0 : -1;?>"></div> 
                <h3><?php echo get_phrase('staff_birthday'); ?><sup>Today</sup></h3> 
                <p>Total Staff Birthday</p> 
            </div>
            </a>
        </div>
        
        <div class="col-sm-3 col-xs-12">
            <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
            <div class="tile-stats tile-green tile_custom"> 
                <div class="icon border_green"><i class="fas main_counter_icon fa-user-minus"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?php echo @$student_attendance[3]; ?>" data-postfix="" data-start="<?php echo @($student_attendance[3] > 0) ? 0 : -1;?>"></div> 
                <h3><?php echo get_phrase('leave_students'); ?><sup>Today</sup></h3> 
                <p>Total Leave Students</p> 
            </div>
            </a>
        </div>
        <div class="col-sm-3 col-xs-12"> 
            <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
            <div class="tile-stats tile-purple tile_custom"> 
                <div class="icon border_purple"><i class="fas main_counter_icon fa-user-minus"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?php echo @$staff_attendance[3]; ?>" data-postfix="" data-start="<?php echo @($staff_attendance[3] > 0) ? 0 : -1;?>"></div>
                <h3><?php echo get_phrase('leave_staff'); ?><sup>Today</sup></h3> 
                <p>Total Leave Staff</p> 
            </div>
            </a>
        </div>
        <div class="col-sm-3 col-xs-12">
            <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
            <div class="tile-stats tile-red tile_custom"> 
                <div class="icon border_orange"><i class="fas main_counter_icon fa-user-check"></i></div>
                <div class="num" data-delay="0" data-duration="800" data-end="<?php echo @$student_attendance[1]; ?>" data-postfix="" data-start="<?php echo @($student_attendance[1] > 0) ? 0 : -1;?>"></div> 
                <h3><?php echo get_phrase('present_students'); ?><sup>Today</sup></h3> 
                <p>Total Present Students</p> 
            </div>
            </a>
        </div>
        <div class="col-sm-3 col-xs-12"> 
            <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
            <div class="tile-stats tile-purple tile_custom"> 
                <div class="icon border_purple"><i class="fas main_counter_icon fa-user"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?php echo @$student_attendance[2]; ?>" data-postfix="" data-start="<?php echo @($student_attendance[2] > 0) ? 0 : -1;?>"></div>
                <h3><?php echo get_phrase('absent_students'); ?><sup>Today</sup></h3> 
                <p>Total Absent Students</p> 
            </div>
            </a>
        </div>
        <div class="col-sm-3 col-xs-12">
            <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
            <div class="tile-stats tile-blue tile_custom"> 
                <div class="icon border_blue"><i class="fas main_counter_icon fa-user-check"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?php echo @$staff_attendance[1]; ?>" data-postfix="" data-start="<?php echo @($staff_attendance[1] > 0) ? 0 : -1;?>"></div> 
                <h3><?php echo get_phrase('present_staff'); ?><sup>Today</sup></h3> 
                <p>Total Present Students</p> 
            </div>
            </a>
        </div>
        <div class="col-sm-3 col-xs-12"> 
            <a href="<?php echo base_url(); ?>admin/dashboard" target="_blank">
            <div class="tile-stats tile-green tile_custom"> 
                <div class="icon border_green"><i class="fas main_counter_icon fa-user"></i></div> 
                <div class="num" data-delay="0" data-duration="800" data-end="<?php echo @$staff_attendance[2]; ?>" data-postfix="" data-start="<?php echo @($staff_attendance[2] > 0) ? 0 : -1;?>"></div>
                <h3><?php echo get_phrase('absent_staff'); ?><sup>Today</sup></h3> 
                <p>Total Absent Students</p> 
            </div>
            </a>
        </div>
    </div>
    */
    ?>
    <div class="row attndnce-dashbrd mt-1">
    <?php if(right_granted('staffattendance_manage')){ ?>
        <div class="col-sm-6 col-lg-6 ">
            <div class="well mainStats">
                <div>
                    <div class="myttl">
                      <h4>
                        <?php echo get_phrase('staff_daily_attendance'); ?>:
                      </h4>
                    </div>
                </div>
                <div class="col-lg-12  px-0">
                    <lable>Select Date</lable>
                    <input class="form-control datepicker"id="staff_name_chart_date"onchange="staff_name_chart()"value="<?php echo date('d/m/Y'); ?>"data-format="dd/mm/yyyy">
                </div>
            </div>
            <div id="staff_name_chart" class="mainStats" style="text-align: center;"></div>
            <a href="<?php echo base_url(); ?>attendance_staff/view_staff_attendance"class="more pt-2 pb-2">
              <?php echo get_phrase('go_to_staff_daily_attendace_page'); ?>>>
            </a>
        </div>
    <?php } ?>
    <?php if(right_granted('managestudentattendance_manage')){ ?>  
      <div class="col-sm-6 col-lg-6"> 
      <!--start student attendance-->
            <div class="well mainStats">
              <div>
                <h4><?php echo get_phrase('student_daily_attendance'); ?></h4>
              </div>
              <div class="col-sm-12 col-lg-6" style="padding-left: 0px;">
                <?php $stdquery="select * from ".get_school_db().".class c inner join  ".get_school_db().".class_section  cs on cs.class_id=c.class_id where c.school_id=$d_school_id order by c.name, cs.title";$class_list=$this->db->query($stdquery)->result_array(); ?>
                <lable>Select Class</lable>
                <select class="form-control" id="class_list_id"onchange="dash_std_daily_attan()">
                  <option value="">
                    <?php echo get_phrase('select_class'); ?>
                  </option>
                  <?php foreach($class_list as $class_row){ ?>
                  <option value="<?php echo $class_row['section_id'] ?>">
                    <?php echo $class_row['name']." - ".$class_row['title']; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-sm-12 col-lg-6  px-0">
                <lable>Select Date</lable>
                <input class="form-control datepicker"id="student_name_chart_date"onchange="dash_std_daily_attan()" value="<?php echo date('d/m/Y'); ?>"data-format="dd/mm/yyyy">
              </div>
            </div>
            <div id="dash_std_daily_attan" class="mainStats" style="text-align: center;"></div> 
            <a href="<?php echo base_url(); ?>attendance/view_stud_attendance" class="more pt-2 pb-2">
                <?php echo get_phrase('go_to_student_daily_attendance_page'); ?>>> 
            </a>
      </div>
    <?php } ?>  
    </div>
  </div>
  <div class="row tab-pane"id="staff">
    <?php if(right_granted('staff_view')){ ?>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow" data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('staff_count'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal" class="bg" data-toggle="modal" data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload" onclick="staff_count()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body"id="staff_count">
        </div>
      </div>
      <div class="col-sm-12 col-lg-12 col-md-12">
        <a href="<?php echo base_url(); ?>user/staff_listing" class="more">
          <?php echo get_phrase('go_to_staff_count_page'); ?>>>
        </a>
      </div>
    </div>
    <?php }if(right_granted('staffattendance_view')){ ?>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('staff_monthly_attendance'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="dash_single_staff_monthly()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row well">
            <div class="col-sm-2"style="width:100px">
              <h4>
                <?php echo get_phrase('staff_list'); ?>
              </h4>
            </div>
            <div class="col-lg-4">
              <select class="form-control"id="staff_del"onchange="dash_single_staff_monthly()">
                <option value="">
                  <?php echo get_phrase('all'); ?>
                </option>
                <?php $staff_list=$this->db->query("select * from ".get_school_db().".staff where school_id=$d_school_id")->result_array();foreach($staff_list as $stf_row){ ?>
                <option value="<?php echo $stf_row['staff_id'] ?>">
                  <?php echo $stf_row['name']; ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-2"style="width:120px">
              <h4>
                <?php echo get_phrase('status_list'); ?>
              </h4>
            </div>
            <div class="col-lg-4"id="status_list_val">
              <select class="form-control"id="status_val"onchange="monthly_attandance_chart()">
                <option value="">
                  <?php echo get_phrase('all'); ?>
                </option>
                <?php $status_ary=array(1=>'Present',2=>'Absent',3=>'Leave');foreach($status_ary as $key=>$value){ ?>
                <option value="<?php echo $key; ?>">
                  <?php echo $value; ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div id="dash_single_staff_monthly">
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-lg-12 col-md-12">
        <a href="<?php echo base_url(); ?>attendance_staff/view_staff_attendance"class="more">
          <?php echo get_phrase('go_to_staff_monthly_attendance'); ?>>>
        </a>
      </div>
    </div>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('staff_daily_attendance_count'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="dash_tech_attn_count()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row well">
            <div class="col-sm-2">
              <div class="panel-title">
                <h4>
                  <?php echo get_phrase('select_date'); ?>
                </h4>
              </div>
            </div>
            <div class="col-lg-4">
              <input class="form-control datepicker"id="date_daily_picker"onchange="dash_tech_attn_count()"value="<?php echo date('d/m/Y'); ?>"data-format="dd/mm/yyyy">
            </div>
          </div>
          <div id="dash_tech_attn_count">
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-lg-12 col-md-12">
        <a href="<?php echo base_url(); ?>attendance_staff/view_staff_attendance"class="more">
          <?php echo get_phrase('go_to_staff_daily_attendance_count'); ?>>>
        </a>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="row tab-pane"id="student">
    <?php if(right_granted(array('classes_view','sections_view'))){ ?>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('class'); ?>
              <?php echo get_phrase('section'); ?>
              <?php echo get_phrase('count'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="dash_std_daily_attan()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body"id="class_section_count">
        </div>
      </div>
      <div class="col-sm-12 col-lg-12 col-md-12">
        <a href="<?php echo base_url(); ?>departments/classes"class="more">
          <?php echo get_phrase('go_to_class_section_count_page'); ?>>>
        </a>
      </div>
    </div>
    <?php }if(right_granted('viewstudentattendance_view')){ ?>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('student_monthly_attendance'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="dash_std_monthly_count()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row well">
            <div class="col-sm-2"style="width:130px">
              <h4>
                <?php echo get_phrase('class_list'); ?>
              </h4>
            </div>
            <div class="col-lg-4">
              <?php $stdquery="select * from ".get_school_db().".class c inner join  ".get_school_db().".class_section  cs on cs.class_id=c.class_id where c.school_id=$d_school_id order by c.name, cs.title";$class_list=$this->db->query($stdquery)->result_array(); ?>
              <select class="form-control"id="class_list"onchange="dash_std_monthly_count()">
                <option value="">
                  <?php echo get_phrase('all'); ?>
                </option>
                <?php foreach($class_list as $class_row){ ?>
                <option value="<?php echo $class_row['section_id'] ?>">
                  <?php echo $class_row['name']." - ".$class_row['title']; ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <div style="display:none"id="student_class_section">
              <div class="col-sm-6">
                <h4>
                  <?php echo get_phrase('status_list'); ?>
                </h4>
              </div>
              <div class="col-lg-6">
                <select class="form-control"id="status_val_student"onchange="student_monthly_status()">
                  <option value="">
                    <?php echo get_phrase('all'); ?>
                  </option>
                  <?php $status_ary=array(1=>'Present',2=>'Absent',3=>'Leave');foreach($status_ary as $key=>$value){ ?>
                  <option value="<?php echo $key; ?>">
                    <?php echo $value; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div id="dash_std_monthly_count">
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-lg-12 col-md-12">
        <a href="<?php echo base_url(); ?>attendance_staff/view_staff_attendance"class="more">
          <?php echo get_phrase('go_student_monthly_attendance_page'); ?>>>
        </a>
      </div>
    </div>
    <?php }if(right_granted('examresult_viewmarksheet')){ ?>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('class_section_exam_results'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="student_exam_result()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row well">
            <div class="col-sm-2"style="width:120px">
              <h4>
                <?php echo get_phrase('class_list'); ?>
              </h4>
            </div>
            <div class="col-lg-4">
              <?php $stdquery = "select * from ".get_school_db().".class c inner join  ".get_school_db().".class_section  cs on cs.class_id=c.class_id where c.school_id=$d_school_id order by c.name, cs.title";$class_list=$this->db->query($stdquery)->result_array(); ?>
              <select class="form-control"id="class_list_examle"onchange="student_exam_result()">
                <option value="">
                  <?php echo get_phrase('select_class'); ?>
                </option>
                <?php foreach($class_list as $class_row){ ?>
                <option value="<?php echo $class_row['section_id'] ?>">
                  <?php echo $class_row['name']." - ".$class_row['title']; ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-6">
            </div>
          </div>
          <div id="student_exam_result">
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-lg-12 col-md-12">
        <a href="<?php echo base_url(); ?>exams/exam"class="more">
          <?php echo get_phrase('go_to_student_exams_page'); ?>>>
        </a>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="row tab-pane"id="fees">
    <?php if(right_granted('feetype_view')){ ?>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('student_monthly_fee'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="monthly_fee()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row well">
            <div class="col-sm-2"style="width:120px">
              <h4>
                <?php echo get_phrase('class_list'); ?>
              </h4>
            </div>
            <div class="col-lg-4">
              <?php $stdquery="select * from ".get_school_db().".class c inner join  ".get_school_db().".class_section  cs on cs.class_id=c.class_id where c.school_id=$d_school_id order by c.name, cs.title";$class_list=$this->db->query($stdquery)->result_array(); ?>
              <select class="form-control"id="class_list_fee"onchange="monthly_fee()">
                <option value="">
                  <?php echo get_phrase('all'); ?>
                </option>
                <?php foreach($class_list as $class_row){ ?>
                <option value="<?php echo $class_row['section_id'] ?>">
                  <?php echo $class_row['name']." - ".$class_row['title']; ?>
                </option>
                <?php } ?>
              </select>
              <?php  ?>
            </div>
            <div class="col-lg-6">
            </div>
          </div>
          <div id="monthly_fee">
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-lg-12 col-md-12">
        <a href="<?php echo base_url(); ?>reports/unpaid_students"class="more">
          <?php echo get_phrase('go_to_reports'); ?>>>
        </a>
      </div>
    </div>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('revenue_details'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="monthly_fee_rev()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row well">
            <div class="col-sm-2"style="width:120px">
              <h4>
                <?php echo get_phrase('class_list'); ?>
              </h4>
            </div>
            <div class="col-lg-4">
              <?php $stdquery="select * from ".get_school_db().".class c inner join  ".get_school_db().".class_section  cs on cs.class_id=c.class_id where c.school_id=$d_school_id order by c.name, cs.title";$class_list=$this->db->query($stdquery)->result_array(); ?>
              <select class="form-control"id="monthly_fee_rev_opn"onchange="monthly_fee_rev()">
                <option value="0">
                  <?php echo get_phrase('all'); ?>
                </option>
                <?php foreach($class_list as $class_row){ ?>
                <option value="<?php echo $class_row['section_id'] ?>">
                  <?php echo $class_row['name']." - ".$class_row['title']; ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-2"style="width:120px">
              <h4>
              </h4>
            </div>
            <div class="col-lg-4">
              <select class="form-control"id="chalan_fee_type"onchange="monthly_fee_rev()">
                <?php $reg_ary=array(0=>'All',1=>'New Admission',2=>'Monthly Fee',3=>'Class Upgrade',4=>'Class Degrade',5=>'Student Transfer',6=>'School Withdrawal',7=>'Receiving Student Transfer');foreach($reg_ary as $key=>$value){echo "<option value='".$key."'>".$value."</option>";} ?>
              </select>
            </div>
          </div>
          <div id="monthly_fee_rev">
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
  <?php
  //wrong action called
  //if(right_granted('staff_view'))
  {  
  ?>
  <div class="row tab-pane"id="calendar_view_container">
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('event_calendar'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="calendar()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body table-responsive">
          <div class="row" id="calendar_view_container_progress">
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php
  //wrong action called
  //if(right_granted('staff_view'))
  {
  ?>
  <div class="row tab-pane"id="exame" >
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('teacher_exam_graph'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="teacher_exam_progress()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row well">
            <div class="col-sm-2"style="width:120px">
              <h4>
                <?php echo get_phrase('teacher_list'); ?>
              </h4>
            </div>
            <div class="col-lg-4">
              <?php $teacher_listing=$this->db->query("select * from ".get_school_db().".designation d inner join ".get_school_db().".staff s on s.designation_id=d.designation_id where d.is_teacher=1 and s.school_id=$d_school_id ")->result_array(); ?>
              <select class="form-control"id="teacher_id_v"onchange="teacher_exam_progress()">
                <option value="">
                  <?php echo get_phrase('select_teacher'); ?>
                </option>
                <?php foreach($teacher_listing as $row_tec){ ?>
                <option value="<?php echo $row_tec['staff_id']; ?>">
                  <?php echo $row_tec['name']; ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-2"style="width:120px">
              <h4>
              </h4>
            </div>
            <div class="col-lg-4">
            </div>
          </div>
          <div class="row"id="teacher_exam_progress">
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('teacher_rating_graph'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="teacher_exam_progress()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row" id="teacher_rating_graph">
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
  <div class="row tab-pane"id="branches">
    <div class="col-sm-12 col-lg-12">
        <div class="panel panel-default panel-shadow"data-collapsed="0">
            <div class="panel-heading">
              <div class="panel-title">
                <h4>
                  <?php echo get_phrase('branch_staff_attandence'); ?>
                </h4>
              </div>
              <div class="panel-options">
                <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
                  <i class="entypo-cog">
                  </i>
                </a> 
                <a href="#"data-rel="collapse">
                  <i class="entypo-down-open">
                  </i>
                </a> 
                <a href="#"data-rel="reload"onclick="staff_name_chart()">
                  <i class="entypo-arrows-ccw">
                  </i>
                </a> 
                <a href="#"data-rel="close">
                  <i class="entypo-cancel">
                  </i>
                </a>
              </div>
            </div>
            <div class="panel-body">
              <div class="row well">
                <div class="col-sm-2">
                  <div class="myttl">
                    <h4>
                      <?php echo get_phrase('select_branch'); ?>:
                    </h4>
                  </div>
                </div>
                <div class="col-lg-4">
                  <select class="form-control"id="branch_school_id"name="branch_school_id">
                    <?php echo branches_option_list(); ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <div class="myttl">
                    <h4>
                      <?php echo get_phrase('select_date'); ?>:
                    </h4>
                  </div>
                </div>
                <div class="col-lg-4">
                  <input class="form-control datepicker"id="branch_staff_name_chart_date"onchange="branch_staff_name_chart()"value="<?php echo date('m/d/Y'); ?>">
                </div>
              </div>
              <div id="branch_staff_name_chart">
              </div>
            </div>
            <div class="col-sm-12 col-lg-12 col-md-12">
                <a href="<?php echo base_url(); ?>user/staff_listing"class="more">
                  <?php echo get_phrase('go_to_branch staff_attendance_page'); ?>>>
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-lg-12">
      <div class="panel panel-default panel-shadow"data-collapsed="0">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo get_phrase('revenue_details'); ?>
            </h4>
          </div>
          <div class="panel-options">
            <a href="#sample-modal"class="bg"data-toggle="modal"data-target="#sample-modal-dialog-3">
              <i class="entypo-cog">
              </i>
            </a> 
            <a href="#"data-rel="collapse">
              <i class="entypo-down-open">
              </i>
            </a> 
            <a href="#"data-rel="reload"onclick="branch_monthly_fee_rev()">
              <i class="entypo-arrows-ccw">
              </i>
            </a> 
            <a href="#"data-rel="close">
              <i class="entypo-cancel">
              </i>
            </a>
          </div>
        </div>
        <div class="panel-body">
          <div class="row well">
            <div class="col-sm-2"style="width:120px">
              <h4>
                <?php echo get_phrase('class_list'); ?>
              </h4>
            </div>
            <div class="col-lg-4">
              <select class="form-control"id="branch_m_school_id"onchange="branch_monthly_fee_rev()">
                <?php echo branches_option_list(); ?>
              </select>
            </div>
          </div>
          <div id="branch_monthly_fee_rev">
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>


<script>var $ = $.noConflict();
  function staff_count(){
    $("#staff_count").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/get_staff_count/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#staff_count").html(a)}
    }
                                                                                    )}
  function dash_tech_attn_count(){
    var a=$("#date_daily_picker").val();
    date_array=a.split("/"),a=date_array[2]+"-"+date_array[1]+"-"+date_array[0];
    $("#dash_tech_attn_count").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/dash_tech_attn_count/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#dash_tech_attn_count").html(a)}
    }
                                                                                             )}
  function date_slash(a){
    var t=a.split("/");
    return t[2]+"-"+t[0]+"-"+t[1]}
  function dash_stf_daily_chart(){
    $("#dash_single_staff_monthly").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/dash_stf_daily_chart/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#dash_single_staff_monthly").html(a)}
    }
                                                                                                  )}
  function dash_std_monthly_count(){
        var a=$("#class_list").val();
        ""==a&&(a=0);
        $("#status_val_student").val(""),""==a?$("#student_class_section").hide():$("#student_class_section").show(),$("#dash_std_monthly_count").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
          type:"GET",url:"<?php echo base_url(); ?>dashboard/dash_std_monthly_count/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
            $("#load").remove(),$("#dash_std_monthly_count").html(a)}
        }
    )}
  dash_std_daily_attan();    
  function dash_std_daily_attan(){
    var a=$("#class_list_id").val();
    ""==a&&(a=0);
    var t=$("#student_name_chart_date").val();
    date_array=t.split("/"),t=date_array[2]+"-"+date_array[1]+"-"+date_array[0],$("#dash_std_daily_attan").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/dash_std_daily_attan/"+a+"/"+t+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#dash_std_daily_attan").html(a)}
    }
)}
  function staff_daily_home(){
    $("#staff_daily_home").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/staff_daily_home//<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#staff_daily_home").html(a)}
    }
                                                                                         )}
  function student_daily_home(){
    $("#student_daily_home").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/student_daily_home/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#student_daily_home").html(a)}
    }
                                                                                           )}
  function dash_single_staff_monthly(){
    var a=$("#staff_del").val();
    $("#status_val").val(""),""!=a?($("#status_list_val").hide(),$("#dash_single_staff_monthly").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/dash_single_staff_monthly/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#dash_single_staff_monthly").html(a)}
    }
)):($("#status_list_val").show(),dash_stf_daily_chart())}
  function monthly_attandance_chart(){
    var a=$("#status_val").val();
    ""!=a?($("#dash_single_staff_monthly").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/monthly_attandance_chart/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#dash_single_staff_monthly").html(a)}
    }
                                                                                                         )):dash_stf_daily_chart()}
  function student_monthly_status(){
    var a=$("#status_val_student").val(),t=$("#class_list").val();
    ""!=a?($("#dash_std_monthly_count").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/student_monthly_status/"+t+"/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#dash_std_monthly_count").html(a)}
    }
                                                                                                      )):dash_std_monthly_count()}
  function class_section_count(){
    $("#class_section_count").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/class_section_count/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#class_section_count").html(a)}
    }
                                                                                            )}
  function student_exam_result(){
    var a=$("#class_list_examle").val();
    $("#student_exam_result").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/student_exam_result/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#student_exam_result").html(a)}
    }
                                                                                            )}
  function monthly_fee(){
    var a=$("#class_list_fee").val();
    ""==a&&(a=0),$("#monthly_fee").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/monthly_fee/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#monthly_fee").html(a)}
    }
                                                                                                 )}
  function monthly_fee_rev(){
    var a=$("#monthly_fee_rev_opn").val(),t=$("#chalan_fee_type").val();
    $("#monthly_fee_rev").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/monthly_fee_rev/"+a+"/"+t+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#monthly_fee_rev").html(a)}
    }
                                                                                        )}
  function branch_monthly_fee_rev(){
    var a=$("#branch_m_school_id").val(),t=$("#branch_chalan_fee_type").val();
    $("#monthly_fee_rev").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/branch_monthly_fee_rev/"+t+"/"+a,dataType:"html",success:function(a){
        $("#load").remove(),$("#branch_monthly_fee_rev").html(a)}
    }
                                                                                        )}
  function staff_name_chart(){
    var a=$("#staff_name_chart_date").val();
    date_array=a.split("/"),a=date_array[2]+"-"+date_array[1]+"-"+date_array[0],$("#staff_name_chart").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/staff_name_chart/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#staff_name_chart").html(a)}
    }
)}
  function branch_staff_name_chart(){
    var a=$("#branch_school_id").val(),t=date_slash($("#branch_staff_name_chart_date").val());
    $("#branch_staff_name_chart").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/branch_staff_name_chart/"+t+"/"+a,dataType:"html",success:function(a){
        $("#load").remove(),$("#branch_staff_name_chart").html(a)}
    }
                                                                                                )}
  function teacher_exam_progress(){
    var a=$("#teacher_id_v").val();
    ""==a?$("#teacher_exam_progress").html("<div style='color:red;'>Please Select Teacher</div>"):($("#teacher_exam_progress").html("<div id='load' class='loader panel-body'></div>"),$.ajax({
      type:"GET",url:"<?php echo base_url(); ?>dashboard/teacher_exam_progress/"+a+"/<?php echo $d_school_id; ?>",dataType:"html",success:function(a){
        $("#load").remove(),$("#teacher_exam_progress").html(a)}
    }
))}
  $(document).ready(function(){
    staff_daily_home(),student_daily_home(),staff_name_chart(),$("#staff_tab").click(function(){
      staff_count(),dash_tech_attn_count(),dash_stf_daily_chart(),staff_name_chart(),dash_single_staff_monthly()}
                                                                                    ),$("#panel_student").click(function(){
      dash_std_monthly_count(),dash_std_daily_attan(),class_section_count()}
                                                                                                               ),$("#fee_student").click(function(){
      monthly_fee(),monthly_fee_rev()}
                                                                                                                                        ),$("#teacher_exam").click(function(){
    }
),$("#calendar_view").click(function(){
      calendar()}
                           )}
                   );
  function calendar()
  {
    <?php $current_date=explode("-",date("Y-m-d"));
    $year=$current_date[0];
    $month=$current_date[1];
    ?>var current_year =<?php echo $year;
    ?>///current_date.getFullYear();
      var current_month =<?php echo $month;
    ?>//current_date.getMonth()+1;
      $('#calendar_view_container_progress').html("<div id='load' class='loader panel-body'></div>");
    $.ajax({
      type: 'GET',
      url: "<?php echo base_url(); ?>dashboard/calendar_view/"+current_year+"/"+current_month+"",
      dataType: "html",
      success: function(response) {
        //alert(response);
        $('#load').remove();
        $('#calendar_view_container_progress').html(response);
      }
    }
          );
  }
  function next_prev(next_url="")
  {
    $('#calendar_view_container_progress').html("<div id='load' class='loader panel-body'></div>");
    $.ajax({
      type: 'GET',url: next_url,dataType: "html",success: function(response) {
        $('#load').remove();
        $('#calendar_view_container_progress').html(response);
      }
    }
          );
  }
</script>
<style>
.loader{
  border:16px solid #f3f3f3;
  border-top:16px solid #63b7e7;
  border-radius:50%;
  width:120px;
  height:120px;
  animation:spin 2s linear infinite;
  margin-right:auto;
  margin-left:auto}
  .loader_small{
    border:7px solid #f3f3f3;
    border-top:7px solid #63b7e7;
    border-radius:50%;
    width:30px;
    height:30px;
    animation:spin 1s linear infinite;
    margin-right:auto;
    margin-left:auto}
  @keyframes spin{
    0%{
      transform:rotate(0)}
    100%{
      transform:rotate(360deg)}
  }
</style>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js">
</script>
<script>
  var OneSignal = OneSignal || [];
  OneSignal.push(["init", {
    appId: "c0c462a2-13b1-4dc9-a93c-d45b65c10b55"
  }
                 ]);
  OneSignal.push(function() {
    var isPushSupported = OneSignal.isPushNotificationsSupported();
    if (isPushSupported) {
      getuserID();
    }
  }
                );
  function getuserID()
  {
    var user_id   = '<?php echo $_SESSION['user_id']; ?>';
    var user_type = '<?php echo $_SESSION['login_type'] ?>';
    OneSignal.isPushNotificationsEnabled(function(isEnabled) {
      if (isEnabled){
        console.log("Push notifications are enabled!");
        OneSignal.getUserId(function(userId) {
          $.ajax({
            url:"<?php echo base_url(); ?>notifications/set_firebase_data",
            type:"post",
            data:{
              device_id:userId,user_id:user_id,user_type:user_type,platform:'WEB'}
            ,
            success:function (response) {
              console.log(response);
            }
          });
        });
      }
      else{
        console.log("Push notifications are not enabled yet.");
        OneSignal.push(function() {
          OneSignal.showHttpPrompt();
        });
      }
    });
  }

</script>
<script type="text/javascript">function getRandomInt(o,a){
                return Math.floor(Math.random()*(a-o+1))+o}
              jQuery(document).ready(function(o){
                o(".top-apps").sparkline("html",{
                  type:"line",width:"50px",height:"15px",lineColor:"#ff4e50",fillColor:"",lineWidth:2,spotColor:"#a9282a",minSpotColor:"#a9282a",maxSpotColor:"#a9282a",highlightSpotColor:"#a9282a",highlightLineColor:"#f4c3c4",spotRadius:2,drawNormalOnTop:!0}
                                        ),o(".monthly-sales").sparkline([1,5,6,7,10,12,16,11,9,8.9,8.7,7,8,7,6,5.6,5,7,5,4,5,6,7,8,6,7,6,3,2],{
                  type:"bar",barColor:"#ff4e50",height:"55px",width:"100%",barWidth:8,barSpacing:1}
                                                                       ),o(".pie-chart").sparkline([2.5,3,2],{
                  type:"pie",width:"95",height:"95",sliceColors:["#ff4e50","#db3739","#a9282a"]}
                                                                                                  ),o(".daily-visitors").sparkline([1,5,5.5,5.4,5.8,6,8,9,13,12,10,11.5,9,8,5,8,9],{
                  type:"line",width:"100%",height:"55",lineColor:"#ff4e50",fillColor:"#ffd2d3",lineWidth:2,spotColor:"#a9282a",minSpotColor:"#a9282a",maxSpotColor:"#a9282a",highlightSpotColor:"#a9282a",highlightLineColor:"#f4c3c4",spotRadius:2,drawNormalOnTop:!0}
                                                                                                                                  ),o(".stock-market").sparkline([1,5,6,7,10,12,16,11,9,8.9,8.7,7,8,7,6,5.6,5,7,5],{
                  type:"line",width:"100%",height:"55",lineColor:"#ff4e50",fillColor:"",lineWidth:2,spotColor:"#a9282a",minSpotColor:"#a9282a",maxSpotColor:"#a9282a",highlightSpotColor:"#a9282a",highlightLineColor:"#f4c3c4",spotRadius:2,drawNormalOnTop:!0}),o("#calendar").fullCalendar({
                  header:{
                    left:"",right:""}
                  ,firstDay:1,height:200}
                             )})


</script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

Highcharts.chart('teacher_rating_graph', {
  chart: {
    type: 'column'
  },
  title: {
    text: 'Teachers Average Rating Graph'
  },
  subtitle: {
    text: 'Rating given by Students'
  },
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    type: 'category'
  },
  yAxis: {
    title: {
      text: 'Total Rating'
    }

  },
  legend: {
    enabled: true
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: '{point.y:.1f}'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> out of 5<br/>'
  },

  series: [
    {
      name: "Teachers",
      colorByPoint: true,
      data: [
          <?php
        foreach($rating_arr as $row){
    ?>
        {
          name: "<?php echo $row->name ?>",
          y: <?php echo $row->average_rating ?>
        },
        <?php } ?>
        
      ]
    }
  ]
});
</script>
<?php
    $this->benchmark->mark('code_end');
?>
<!--<h1>-->
<?php
    // echo $this->benchmark->elapsed_time('code_start', 'code_end');
?>

<?php //echo $this->benchmark->memory_usage();?>
<!--</h1>-->