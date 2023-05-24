<style>
    #nprogress .bar{display:none}#nprogress .spinner{display:none}#nprogress .spinner-icon{display:none}.fa-bell-o{color:#fff}.morecontent span{display:none}.morelink{font-weight:700;color:#0f76a0;font-size:12px}.panel-default>.panel-heading>.panel-title>a{color:#5593c3}.fa-long-arrow-right{color:#000;padding-left:8px}.fa-clock-o{color:#4a8cbb}.panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#00a1de;border-top:2px solid #006f9c}.box{box-shadow:0 0 0 rgba(0,0,0,.1)}.teacher_box{float:left}#nc .panel-danger .panel-heading{color:#fff;background-color:#29638d;border-color:#2092d0}#nc .panel-heading.month{background:#e9eaea;border-bottom:1px solid #eee}#nc .panel-body{padding:0}#nc .month .panel-title{color:#000!important}#nc .panel.date{margin:0;width:60px;text-align:center;border:1px solid #eee!important}#nc .media-heading{margin:0 0 5px;text-transform:capitalize;font-size:14px!important;line-height:20px;font-weight:700;color:#414141}#nc li.media{border:1px solid #e4e4e4;padding:10px 15px 10px 15px}#nc .panel-danger{border-color:#fff!important;box-shadow:none}.present{border:2px solid #b2daa2}.present td{background:#ecf5e9}.absent{border:2px solid #f1abab}.absent td{background:#fbeeee}.leave{border:2px solid #efdd7e}.leave td{background:#fcf8e3}span.absent,span.leave,span.present{border:none}.weekend{border:2px solid #bae4f9}.mg #attendance table tr td{border:1px solid #e4e4e4}.mg #attendance table tbody tr:hover td{background:0 0}#payment .nds2{border-left:3px solid #cc2424;background:rgba(255,36,36,.08);padding:5px 0 5px 15px}#payment .nds2 h3{width:24%;display:inline-block}#exam_routine table tr{background:#29638d}#exam_routine table tr th{color:#fff}#exam_routine table tr.gradeA{background:#eee}#exam_routine table tr td{border:1px solid #ccc}.assign-diary{background:#29638d;padding:5px 10px;display:inline-block;width:100%;margin-bottom:10px}.assign-diary span{color:#fff;font-size:14px}.assign-diary i{color:#fff}.task-detail{display:inline-block;border-bottom:1px solid #ccc;padding-bottom:10px;width:100%;margin-bottom:10px}#exam_routine .mg{margin-bottom:0}@-webkit-keyframes blink{0%{opacity:1}50%{opacity:0}100%{opacity:1}}@-moz-keyframes blink{0%{opacity:1}50%{opacity:0}100%{opacity:1}}@-o-keyframes blink{0%{opacity:1}50%{opacity:0}100%{opacity:1}}#img{-webkit-animation:blink 1s;-webkit-animation-iteration-count:infinite;-moz-animation:blink 1s;-moz-animation-iteration-count:infinite;-o-animation:blink 1s;-o-animation-iteration-count:infinite}.Myalert{padding:20px;background-color:#f44336;color:#fff;opacity:1;transition:opacity .6s;margin-bottom:15px;font-weight:600;font-family:sans-serif;font-size:14px}.Myalert.success{background-color:#4caf50}.Myalert.info{background-color:#2196f3}.Myalert.warning{background-color:#ff9800}.closebtn{margin-left:15px;color:#fff;font-weight:700;float:right;font-size:22px;line-height:20px;cursor:pointer;transition:.3s}.closebtn:hover{color:#000}.nav-tabs{background:0 0!important;padding:0;margin-top:0;margin-bottom:0;font-size:12px}.nav-tabs>li>a{margin-right:4px;line-height:1.42857143;border-radius:3px 3px 0 0;background-color:#ebebeb!important}.nav.nav-tabs a{padding:5px 10px 5px 10px!important;margin-bottom:6px}.nav-tabs>li.active>a,.nav-tabs>li.active>a:focus,.nav-tabs>li.active>a:hover{background-color:#0073b7;}
</style>

<?php

$_SESSION['academic_year_id'] = isset($_SESSION['academic_year_id']) &&  $_SESSION['academic_year_id'] != '' ?  $_SESSION['academic_year_id'] : 0;

if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-danger alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<?php
    $section_id=$_SESSION['section_id'];
    $school_id=$_SESSION['school_id'];
?>
    <?php if(count($assigned_access_rights) > 1 && !$action_blocked) { ?>
    
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 my-4">
            <!--<h3 class="system_name inline">-->
            <!--    <?php //echo get_phrase('dashboard');?> -->
            <!--</h3>-->
            
            <div style="display:flex;">
                <?php if($res[0]['profile_pic']==""){ ?>
                <img alt=""alt=""  src="<?php echo get_default_pic() ?>" style="height: 50px;border-radius: 8px;">
                <?php }else{ ?>
                <img alt=""alt=""alt=""  src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1) ?>" style="height: 50px;border-radius: 8px;">
                <?php } ?>
                <span style="margin-top: -10px;margin-left: 5px;">
                    <h3 style="">Welcome, <?php echo $res[0]['name']; ?> 	<p><?= date("l")." ". ordinal(date("d"))." ".date("M, Y")  ?> </p>
                    </h3>
                </span>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 d-lg-flex justify-content-lg-end " >
            <?php if(count($assigned_access_rights) > 1 && !$action_blocked) { ?>
            <ul class="nav nav-tabs float-sm-right my-4">
            <li class="active"><a data-toggle="tab" href="#home"><?php echo get_phrase('home');?></a></li>
            <li>
                <a data-toggle="tab" href="#event_announcment"><?php echo get_phrase('events');?>
                    <span class="badge badge-danger">
                    <?php echo $count_events; ?>
                    </span>
                </a>
            </li>
            <?php if (in_array("s_class_diary", $assigned_access_rights)){ ?>
            <li>
                <a data-toggle="tab" href="#diary"><?php echo get_phrase('diary');?>
                    <span class="badge badge-danger"> <?php echo $count_dairy; ?>  </span>
                </a>
            </li>
            <?php } if (in_array("s_noticeboard", $assigned_access_rights)){ ?>
            <!--<li><a data-toggle="tab" href="#nc"><?php //echo get_phrase('notices_&_circulars');?></a></li>-->
            <?php } if (in_array("s_attendance", $assigned_access_rights)){ ?>
            <li><a data-toggle="tab" href="#attendance"><?php echo get_phrase('attendance');?></a></li>
            <!--<li><a data-toggle="tab" href="#routine"><?php //echo get_phrase('timetable');?></a></li>-->
            <!--<li><a data-toggle="tab" href="#message"><?php echo get_phrase('messages');?></a></li>-->
            <!--<li><a data-toggle="tab" href="#payment"><?php echo get_phrase('payments');?></a></li>-->
            <?php } if (in_array("s_datesheet", $assigned_access_rights)){ ?>
            <li><a data-toggle="tab" href="#exam_routine"><?php echo get_phrase('datesheet');?></a></li>
            <?php } if (in_array("s_examination_result", $assigned_access_rights)){ ?>
            <li><a data-toggle="tab" href="#exam_result"><?php echo get_phrase('results');?></a></li>
            <?php } if (in_array("s_virtual_class_recordings", $assigned_access_rights)){ ?>
            <!--<li><a data-toggle="tab" href="#virtual_class_recordings"><?php //echo get_phrase('virtual_class');?></a></li>-->
            <?php } if (in_array("s_view_assessments", $assigned_access_rights)){ ?>
            <li>
                <a data-toggle="tab" href="#assessments">
                <?php
                    echo get_phrase('assessments');
                    if(count($assessment) > 0){
                        echo '<span style="margin-left:2px" class="badge badge-danger">'. count($assessment) . '</span>';
                    }
                ?>
                </a>
            </li>
            <?php } ?>
            <li>
                <a href="<?php echo base_url();?>notes_student/view_notes">
                <?php
                    $current_date = date("Y-m-d");
                    $q = "select ln.notes_id from ".get_school_db().".lecture_notes ln
                    inner join ".get_school_db().".lecture_notes_audience lnt on lnt.notes_id = ln.notes_id
                    WHERE lnt.student_id = ".$_SESSION['student_id']." and ln.school_id = ".$_SESSION['school_id']." AND DATE_FORMAT(ln.inserted_at, '%Y-%m-%d') = '$current_date'";
                    $lecture_notes = $this->db->query($q)->result_array();
                    if(count($lecture_notes) > 0){
                        echo '<span class="badge badge-primary">'.count($lecture_notes).' new </span>';
                    }
                    echo get_phrase('lecture_notes');
                ?>
                </a>
            </li>
        </ul>
        <?php } ?>
        </div>
            
    </div>
    <div class="row mg">
        <script>
            var close = document.getElementsByClassName("closebtn");
            var i;
            for (i = 0; i < close.length; i++) {
              close[i].onclick = function(){
                var div = this.parentElement;
                div.style.opacity = "0";
                setTimeout(function(){ div.style.display = "none"; }, 600);
              }
            }
        </script>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-content">
                <div id="home" class="tab-pane active">
                        <?php
                            $birthday = check_student_birthdat($_SESSION['student_id']);
                            
                         
                            if($birthday != "")
                            {
                        ?>
                        <a href="#" id="modal_buddy" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_birthday/');"></a>
                        <script>
                            $("document").ready(function(){
                               $("#modal_buddy").trigger("click"); 
                            });
                        </script>
                        <?php } ?>
                    <div class="row">
                      <!--Panel body Start Stats-->
                      
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="tile-stats tile-green tile_custom">
                              <div class="icon border_green">
                                <i class="fas main_counter_icon fa-user-check">
                                </i>
                              </div>
                              <div class="num"data-delay="0"data-duration="800" data-end="<?php echo $attendance_arr->present; ?>" data-postfix=""data-start="<?php echo ($attendance_arr->present > 0) ? 0 : -1;?>">
                                <?php echo $attendance_arr->present; ?>
                              </div>
                              <h3 class="white2">
                                <?php echo get_phrase('present'); ?><sup>This month</sup>
                              </h3>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="tile-stats tile-blue tile_custom">
                              <div class="icon border_blue">
                                <i class="fas main_counter_icon fa-user">
                                </i>
                              </div>
                              <div class="num"data-delay="0" data-duration="800"data-end="<?php echo $attendance_arr->absent; ?>"
                              data-postfix=""data-start="<?php echo ($attendance_arr->absent > 0) ? 0 : -1;?>">
                                <?php echo $attendance_arr->absent; ?>
                              </div>
                              <h3 class="white2">
                                <?php echo get_phrase('absent'); ?><sup>This month</sup>
                              </h3>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="tile-stats tile-red tile_custom">
                              <div class="icon border_orange">
                                <i class="fas main_counter_icon fa-user-minus ">
                                </i>
                              </div>
                              <div class="num" data-delay="0" data-duration="800" data-end="<?php echo $attendance_arr->leaves; ?>"  data-postfix=""  data-start="<?php echo ($attendance_arr->leaves > 0) ? 0 : -1;?>">
                                <?php echo $attendance_arr->leaves; ?>
                              </div>
                              <h3 class="white2">
                                <?php echo get_phrase('leaves'); ?><sup>This month</sup>
                              </h3>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="tile-stats tile-purple tile_custom">
                              <div class="icon border_purple">
                                <i class="fas main_counter_icon fa-book">
                                </i>
                              </div>
                              <div class="num"data-delay="0"data-duration="800" data-end="<?php echo $total_subjects; ?>"data-postfix=""data-start="<?php echo ($total_subjects > 0) ? 0 : -1;?>">
                                <?php echo $total_subjects; ?>
                            </div>
                            <h3 class="white2">
                              <?php echo get_phrase('subjects'); ?>
                            </h3>
                            </div>
                      </div>
                    
                        <!--End Panel Body Stats-->
                    </div>
                    <?php if (in_array("s_quick_links", $assigned_access_rights)){ ?>
                    <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2">
                                <a href="<?=base_url()?>student_p/manage_attendance">
                                <div class="quick_links text-center gradientone">
                                    <i class="fas fa-table"></i>
                                    <span class="quick_link_txt">Attendance Detail</span>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2">
                                <a href="<?=base_url()?>assessment/assessment_result">
                                <div class="quick_links text-center gradienttwo" >
                                    <i class="fas fa-poll-h"></i>
                                    <span class="quick_link_txt">Assessment Result</span>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2">
                                <a href="<?=base_url()?>student_p/policies_listing">
                                <div class="quick_links text-center gradientone">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="quick_link_txt">School Policies</span>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2">
                                <a href="<?=base_url()?>student/c_student/subject_recording">
                                <div class="quick_links text-center gradienttwo">
                                    <i class="fas fa-vr-cardboard"></i>
                                    <span class="quick_link_txt">Virtual Classes</span>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 my-2">
                                <a href="<?=base_url()?>student_p/manage_profile">
                                <div class="quick_links text-center gradientone" >
                                    <i class="fas fa-user"></i>
                                    <span class="quick_link_txt">Profile</span>
                                </div>
                                </a>
                            </div>
                    </div>
                    <?php } 
                    if (in_array("s_dashboard_timetable", $assigned_access_rights)){ ?>
                    <?php $this->load->view('backend/student/class_routine'); ?>
                    <?php } ?>
                    
                    <!--load student attendance graph view-->
                    <?php $this->load->view('backend/student/student_attendance_monthly' , $attendance_arr); ?>
                   
                </div>
                <!--Event Announcement-->
                <div id="event_announcment" class="tab-pane">
                        <div class="box">
                            <div class="box-header with-border p-3">
                                <h3 class="box-title">
                                <i class="fas fa-table" aria-hidden="true"></i><?php echo get_phrase('latest_event_announcement');?></h3>
                            </div>
                        </div>
                        <div class="panel-default panel-shadow mt-4 pb-4">
                            <div class="panel-heading">
                                    <h3 class="panel-title text-white">
                                        <i class="fas fa-bell" aria-hidden="true"></i><?php echo get_phrase('event_announcement');?>
                                    </h3>
                                </div>
                            <style>
                                    .panel_date-Heading{text-transform:initial}.inputGroup{background-color:#0073b7;display:block;margin:10px 0;position:relative}
                                    .inputGroup label{padding:12px 30px;width:100%;display:block;text-align:left;color:#fff;cursor:pointer;position:relative;z-index:2;-webkit-transition:color 
                                    .2s ease-in;transition:color .2s ease-in;overflow:hidden}
                                    .inputGroup label:before{width:10px;height:10px;border-radius:50%;content:'';background-color:#5562eb;position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%,-50%) scale3d(1,1,1);transform:translate(-50%,-50%) scale3d(1,1,1);-webkit-transition:all 
                                    .3s cubic-bezier(.4,0,.2,1);transition:all .3s cubic-bezier(.4,0,.2,1);opacity:0;z-index:-1}.inputGroup label:after{width:32px;height:32px;content:'';
                                    border:2px solid #d1d7dc;background-color:#fff;background-image:url("data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5.414 11L4 12.414l5.414 5.414L20.828 6.414 19.414 5l-10 10z' fill='%23fff' fill-rule='nonzero'/%3E%3C/svg%3E");background-repeat:no-repeat;
                                    background-position:2px 3px;border-radius:50%;z-index:2;position:absolute;right:30px;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);cursor:pointer;-webkit-transition:all .2s ease-in;transition:all .2s ease-in}.inputGroup input:checked~label{color:#fff}.inputGroup input:checked~label:before{-webkit-transform:translate(-50%,-50%) scale3d(56,56,1);transform:translate(-50%,-50%) scale3d(56,56,1);opacity:1}
                                    .inputGroup input:checked~label:after{background-color:#54e0c7;border-color:#54e0c7}.inputGroup input{width:32px;height:32px;-webkit-box-ordinal-group:2;order:1;z-index:2;position:absolute;right:30px;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);cursor:pointer;visibility:hidden}.response_btn{height:40px;position:relative;top:6px}
                            </style>
                            <div class="panel-body" style="background:white">
                                    <ul class="media-list">
                                        <?php
                                            $ev_date = date("Y-m-d");
                                            $event_announcement = $this->db->query("SELECT ed.*,ea.* FROM ".get_school_db().".events_annoucments_details ed LEFT JOIN ".get_school_db().".events_annoucments ea ON ea.event_id = ed.event_id WHERE ed.student_id = '".$_SESSION['student_id']."' AND ea.active_inactive = '1' AND ea.event_status = '1' AND '$ev_date' <= ea.event_end_date")->result_array();
                                            if(count($event_announcement) > 0){
                                            foreach($event_announcement as $rt){
                                                
                                        ?>
                                        <form id="event_response_submit<?=$rt['event_detail_id']?>">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <li class="media">
                                                        <div class="media-left">
                                                            <div class="panel panel-danger text-center date" style="width:170px !important">
                                                                <div class="panel-body day" style="background:#29638d;color:white;font-size:12px !important;font-weight:400 !important;font-family:cursive;">
                                                                    <span class="panel_date-Heading">Start Date</span>
                                                                    <br>
                                                                    <?= date_view($rt['event_start_date']);?>
                                                                </div>
                                                                <div class="panel-body day " style="font-weight:400 !important;font-family:cursive;">
                                                                    <span class="panel_date-Heading">End Date</span>
                                                                    <br>
                                                                    <?= date_view($rt['event_end_date']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media-body" style="width:100%">
                                                            <h4 class="media-heading">
                                                                <b><?php echo $rt['event_title'];?></b>
                                                            </h4>
                                                            <p>
                                                                <b>Event Detail:</b>
                                                                <span class="moren"><?php echo $rt['event_details'];?> </span>
                                                            </p>
                                                        </div>
                                                    </li>
                                                </div>
                                                <div class="col-md-6" id="after_submit_response_hide<?=$rt['event_detail_id']?>">
                                                    <?php
                                                        if($rt['response_status'] == '1' || $rt['response_status'] == '2'){
                                                            if($rt['response_status'] == "1")
                                                            {
                                                                $sub_response = "Yes";
                                                            }else if($rt['response_status'] == "2")
                                                            {
                                                                $sub_response = "No";
                                                            }
                                                    ?>
                                                    <h3 class="text-center" style="position:relative;top:35px;"><b>Your Response is <?=$sub_response?></b></h3>
                                                    <?php }else{ ?>
                                                    <!--<h4>Are You Intrested ?</h4>-->
                                                    <p>Please Submit Your Response Status</p>
                                                    <div class="row">
                                                        <input type="hidden" value="<?=$rt['event_detail_id']?>" name="event_did">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <div class="inputGroup">
                                                                <input id="radio1" name="eve_status" type="radio" value="1" checked=""/>
                                                                <label for="radio1"><b>Yes</b></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <div class="inputGroup">
                                                                <input id="radio2" name="eve_status" type="radio" value="2"/>
                                                                <label for="radio2"><b>No</b></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <label>Write a message</label>
                                                            <textarea class="form-control" name="response_text"></textarea>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                                            <button class="btn btn-primary response_btn response__btn<?=$rt['event_detail_id']?>">Submit Response</button>
                                                        </div>
                                                        <div class="resp_msg<?=$rt['event_detail_id']?> col-lg-12"></div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-md-6 text-center" id="after_submit_response_show<?=$rt['event_detail_id']?>" style="display:none;">
                                                    <h3><b>Your Response is <span id="reponse_status_return<?=$rt['event_detail_id']?>"></span></b></h3>
                                                </div>
                                            </div>
                                        </form>
                                        <hr>
                                        <script>
                                            $("#event_response_submit<?=$rt['event_detail_id']?>").on('submit',function(e){
                                                e.preventDefault();
                                                   
                                                var r = $(".inputGroup").text();
                                                    $.ajax({    
                                                        url: '<?=base_url()?>event_annoucments/event_response',
                                                        method: 'POST',
                                                        data:new FormData(this),  
                                                        contentType: false,  
                                                        cache: false,  
                                                        processData:false,
                                                        beforeSend:function(){
                                                          
                                                            $('.response__btn<?=$rt['event_detail_id']?>').attr('disabled','disabled');
                                                            $('.response__btn<?=$rt['event_detail_id']?>').val('Submiting....');
                                                        },
                                                        success:function(msg)
                                                        {
                                                            
                                                            if(msg == "1" || msg == "2"){
                                                                if(msg == '1'){st = "Yes";}else if(msg == '2'){st = "No";}
                                                                $('.response__btn<?=$rt['event_detail_id']?>').val('Submitted');
                                                                $(".resp_msg<?=$rt['event_detail_id']?>").html('<div class="alert alert-success">Event Response Submitted Successfully</div>');
                                                                setInterval(function(){ 
                                                                    $("#after_submit_response_hide<?=$rt['event_detail_id']?>").hide();
                                                                    $("#after_submit_response_show<?=$rt['event_detail_id']?>").css("display","block");
                                                                    $("#reponse_status_return<?=$rt['event_detail_id']?>").text(st);
                                                                }, 2000);
                                                                
                                                            }
                                                        }
                                                    });
                                            });
                                        </script>
                                        <?php //} ?>
                                        <?php } ?>
                                    </ul>
                                    <?php }else{ ?>
                                    <div class="text-center">
                                        <i class="fas fa-bell" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                        <h2><b>Event Announcement is Empty</b></h2>
                                        <!--<a href="<?php echo base_url();?>student_p/diary" style="color:black;"> <b><?php //echo get_phrase('go_to_event_announcement_page');?> <i class="fas fa-long-arrow-alt-right"></i></b></a>-->
                                    </div>
                                    <?php } ?>
                                </div>
                        </div>
                </div>    
                <div id="diary" class="tab-pane">
                    <?php
                        $section_id   = $_SESSION['section_id'];
                        $student_id   = $_SESSION['student_id'];
                        $q="select dr.diary_id as diary_id,dr.teacher_id as teacher_id,dr.subject_id as subject_id,dr.section_id as section_id,
                        dr.assign_date as assign_date,dr.due_date as due_date,dr.task as task,
                        dr.title as title,dr.attachment as attachment,ds.student_id as student_id,
                        dr.school_id, d.title as department, c.name as class_name,cs.title as class_section,s.name as subject_name,s.code as code
                        FROM ".get_school_db().".diary dr
                        
                        INNER JOIN ".get_school_db().".class_section cs
                        ON cs.section_id = dr.section_id
                        
                        INNER JOIN ".get_school_db().".class c
                        ON c.class_id = cs.class_id
                        
                        INNER JOIN ".get_school_db().".departments d
                        ON d.departments_id = c.departments_id
                        
                        INNER JOIN ".get_school_db().".diary_student ds
                        ON ds.diary_id = dr.diary_id
                        
                        INNER JOIN ".get_school_db().".subject s
                        ON dr.subject_id = s.subject_id
                        
                        WHERE 
                        
                        dr.section_id=$section_id AND 
                        ds.student_id=$student_id AND 
                        ds.is_submitted = 0 AND
                        dr.due_date >= CURDATE() AND 
                        dr.school_id=".$_SESSION['school_id']."  
                        
                        ORDER BY dr.assign_date desc ";
                        
                        //exit;
                        $check_num_r =$this->db->query($q);
                        $dairy=$this->db->query($q)->result_array(); 
                        $subject=array();
                        $diary=array();
                        $array_subj=array();
                        $array_diary=array();
                        foreach($dairy as $red)
                        {
                            $array_subj=array('subject_name'=>$red['subject_name'],'code'=>$red['code'],'teacher_name'=>get_teacher_name($red['teacher_id']));
                            $array_diary=array('title'=>$red['title'],'attachment'=>$red['attachment'],'task'=>$red['task'],'assign_date'=>$red['assign_date'],'due_date'=>$red['due_date']);
                            
                            $subject[$red['subject_id']]=$array_subj;
                            $diary[$red['subject_id']][$red['diary_id']]=$array_diary;
                        }
                    
                    ?>
                    <div id="diary">
                        <div>
                            <div class="text" id="text">
                                <div class="box p-3">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-book" aria-hidden="true"></i><?php echo get_phrase('current_diary');?></h3>
                                    </div>
                                    <?php if($check_num_r->num_rows() > 0){ foreach($subject as $key=>$val ) { ?>
                                                <!--<div class="row">-->
                                                    <!--<div class="col-lg-12">-->
                                                    <div class="nds nshov" style="border-left:none; padding-left:0px; display:inline-block; width:100%; margin-bottom:15px; cursor:inherit;">
                                                        <div class="assign-diary">
                                                            <div class="myh3" style="float:left;"><i class="fa fa-book" aria-hidden="true"></i>
                                                                <span>
                                                                
                                                                    <?php
                                                                    echo($val['subject_name']) ;
                                                                     //echo get_subject_name($dr['subject_id']); ?>
                                                                </span>
                                                            </div>
                                                            <div class="myh3" style="float:right;"><i class="fa fa-user" aria-hidden="true"></i>
                                                                <span>
                                                                    <?php
                                                      echo $val['teacher_name'];
                                                                    //echo get_teacher_name($dr['teacher_id']); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <?php foreach($diary[$key] as $key1=>$val1) {?>  
                                                        <div class="task-detail">
                                                            <a href="<?php echo base_url(); ?>student_p/diary" target="_blank">
                                                                <span style=" color:#4a8cbb; font-weight:bold; font-size:14px;">
                                                                <?php echo($val1['title']);             
                                                                    // echo $dr['title']; ?>
                                                                    <?php if($val1['attachment']=="") { }
                                                                    else { ?>
                                                                    <a target="_blank" href="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/diary/".$val1['attachment']; ?>"><span class="glyphicon glyphicon-download-alt" style="padding:0px 10px;"></span></a>
                                                                    <?php }?>
                                                                </span>
                                                                <?php
                                                                    if($array_diary['assign_date'] == date("Y-m-d"))
                                                                    {
                                                                        $path = base_url()."assets/images/new-icon.jpg";
                                                                        echo '<img style="height: 30px;" id="img" src="'.$path.'" />';
                                                                    }
                                                                ?>
                                                            </a>
                                                            <p>
                                                                <span>
                                                                  <?php echo $val1['task'];?>     
                                                                </span>
                                                            </p>
                                                            <div class="diary-dates">
                                                                <p style="float:left;"><strong><?php echo get_phrase('assign_date:');?></strong>
                                                                    <span>
                                                                        <?php  echo convert_date($val1['assign_date']); ?>
                                                                    </span>
                                                                </p>
                                                                <p style="float:right;"><strong><?php echo get_phrase('due_date:');?></strong>
                                                                    <span>
                                                                        <?php echo convert_date($val1['due_date']); ?>  
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                       <?php } ?> 
                                                    </div>
                                                    
                                                    
                                                    
                                                    
                                                <a href="<?php echo base_url();?>student_p/diary" style="color:#0e88e0;"> <b><?php echo get_phrase('go_to_diary_page');?> 
                                                        <i class="fas fa-long-arrow-alt-right"></i></b>
                                                </a>                                                
                                                <!--</div>-->
                                                <!--</div>-->
                                    <?php } }else{ ?>
                                    <div class="text-center">
                                        <i class="fas fa-book-open" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                        <h2><b>Diary is Empty</b></h2>
                                        <a href="<?php echo base_url();?>student_p/diary" style="color:black;"> <b><?php echo get_phrase('go_to_diary_page');?> <i class="fas fa-long-arrow-alt-right"></i></b></a>
                                    </div>
                                    <?php } ?>
                                </div>
                                <!-----------text  height ------------>
                            </div>
                            <!--<div class="link">
                                <a id="readmore" class="more" onclick="changeheight(this)">View more Diary Item</a>
                            </div>-->
                        </div>
                    </div>
                </div>
                <!-- Notices and circular-->
                <div id="nc" class="tab-pane" style="height:100vh;background:white !important;">
                    <?php 
                        $q="select n.notice_id as notice_id,
                        n.notice_title as notice_title,n.notice as notice,
                        n.create_timestamp as create_timestamp
                        FROM ".get_school_db().".noticeboard n
                        WHERE 
                        n.school_id=".$_SESSION['school_id']." 
                        LIMIT 3";
                        $noticeboard=$this->db->query($q)->result_array();
                    ?>
                    <div>
                        <div class="box">
                            <div class="box-header with-border p-3">
                                <h3 class="box-title">
                                <i class="fas fa-table" aria-hidden="true"></i><?php echo get_phrase('latest_notices_and_circulars');?></h3>
                            </div>
                        <div class=" col-lg-6 col-md-6 col-sm-6 ">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-white">
                                        <i class="fa fa-bell-o" aria-hidden="true"></i><?php echo get_phrase(' notice_board');?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <ul class="media-list">
                                        <?php foreach($noticeboard as $rt){?>
                                        <li class="media">
                                            <div class="media-left">
                                                <div class="panel panel-danger text-center date">
                                                    <div class="panel-heading month">
                                                        <span class="panel-title strong nc_month_heading text-white">
                                                            <?php maketime($rt['create_timestamp'],'m'); ?>
                                                        </span>
                                                    </div>
                                                    <div class="panel-body day ">
                                                        <?php maketime($rt['create_timestamp'],'d'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">
                                                    <?php echo $rt['notice_title'];?>
                                                </h4>
                                                <p>
                                                    <span class="moren">    <?php echo $rt['notice'];?> </span>
                                                </p>
                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                    <a href="<?php echo base_url();?>student_p/noticeboard" class="more p-4"> <?php echo get_phrase('view_more');?>>> </a>
                                </div>
                            </div>
                            <!-- End fluid width widget -->
                        </div>
                            <?php
                                $q="select cl.circular_id as circular_id,cl.circular_title as circular_title,cl.circular as circular,cl.section_id as section_id,cl.student_id as student_id,cl.create_timestamp as create_timestamp,cl.attachment as attachment, d.title as department, c.name as class_name,
                                cs.title as class_section
                                FROM ".get_school_db().".circular cl
                                
                                INNER JOIN ".get_school_db().".class_section cs
                                ON cs.section_id = cl.section_id
                    
                                INNER JOIN ".get_school_db().".class c
                                ON c.class_id = cs.class_id
                    
                                INNER JOIN ".get_school_db().".departments d
                                ON d.departments_id = c.departments_id
                    
                                WHERE 
                                ((cl.student_id='' OR cl.student_id=0 OR cl.student_id=$student_id ) AND  cl.section_id=$section_id) 
                                AND cl.school_id=".$_SESSION['school_id']." 
                                ORDER BY cl.create_timestamp desc LIMIT 3";           
                                $circular=$this->db->query($q)->result_array();
                                            
                            ?>          
                        <div class=" col-lg-6 col-md-6 col-sm-6">
                            <!-- Fluid width widget -->
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-white">
                                        <span class="glyphicon glyphicon-calendar"></span><?php echo get_phrase(' circular');?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <ul class="media-list">
                                        <?php foreach($circular as $rt){?>
                                        <li class="media">
                                            <div class="media-left">
                                                <div class="panel panel-danger text-center date">
                                                    <div class="panel-heading month">
                                                        <span class="panel-title strong nc_month_heading text-white">
                                                        <?php
                                                   
                                                         echo maketime($rt['create_timestamp'],'m');?>
                                                    </span>
                                                    </div>
                                                    <div class="panel-body day">
                                                        <?php echo maketime($rt['create_timestamp'],'d');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">
                                                    <?php echo $rt['circular_title'];
                                                    if($rt['attachment']=="")
                                                    {
                                                        
                                                    }
                                                    else{
                                                    ?>
                                                        <a target="_blank" href="<?php echo display_link($row['attachment'],'circular');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
                                                    <?php
                                                    }
                                                    ?>
                                                </h4>
                                                <p>
                                                    <span class="moren"> <?php echo $rt['circular'];?></span>
                                                </p>
                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                    <a href="<?php echo base_url();?>student_p/circulars" class="more p-4"> <?php echo get_phrase('view_more');?>>> </a>
                                </div>
                            </div>
                            <!-- End fluid width widget -->
                        </div>
                    </div>
                    </div>
                </div>
                
                <!--------------------Attendance------------------------------>
                <div id="attendance" class="tab-pane">
                    <div class="">
                        <div class="">
                            <div class="box">
                                <div class="box-header with-border p-3">
                                    <h3 class="box-title"><i class="fa fa-check-square-o" aria-hidden="true"></i><?php echo get_phrase(' attendance');?></h3>
                                </div>
                                <div id="no-more-tables table" class="p-3">
                                    <table id="example" class="table" style="font-size:12px; cursor:pointer;    ">
                                        <thead>
                                            <tr>
                                                <th style="width:100px; font-weight: bold; background:#29638d; color: #ffffff;"><?php echo get_phrase('date');?></th>
                                                <th style="width:100px;font-weight: bold; background:#29638d; color: #ffffff;"><?php echo get_phrase('day');?></th>
                                                <th style="width:300px;font-weight: bold; background:#29638d; color: #ffffff;"><?php echo get_phrase('attendance');?></th>
                                                <th style="font-weight: bold; background:#29638d; color: #ffffff;"><?php echo get_phrase('comments');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            
                                            
                                                $custom_css=array(1=>'current-day',2=>'holiday');                   
                                                $current_date=date('d-M-Y');                                       
                                                                                            
                                                $month=date('n');
                                                $year=date('Y');
                                                $date_month= $month;//date();// $_POST['month'];
                                                $date_year=$year;//$_POST['year'];
                                                $stud_id=$_SESSION['student_id'];//$_POST['section_id'];
                                                $q = "select a.status,a.date,attendance_timing.check_in,attendance_timing.check_out
                                                FROM ".get_school_db().".attendance a
                                                LEFT JOIN ".get_school_db()." .attendance_timing ON attendance_timing.attendance_id=a.attendance_id
                                                WHERE  a.student_id=$stud_id  AND month(a.date)=$date_month  AND YEAR(a.date)=$date_year  AND a.school_id=".$_SESSION['school_id']." ";
                                                $qur_red=$this->db->query($q)->result_array();
                                               // print_r($qur_red);exit;
                                                $plan=array();
                                                foreach($qur_red as $red){
                                                    $plan[$red['date']]=array('status'=>$red['status'],'check_in'=>$red['check_in'],'check_out'=>$red['check_out']);
                                                }

                                                $d=cal_days_in_month(CAL_GREGORIAN,$date_month,$date_year);
                                                for($i=01; $i<=$d; $i++)
                                                {
                                                    $current = "";  
                                                    $current1 = ""; 
                                                    $s=mktime(0,0,0,$date_month, $i, $date_year);
                                                    $today_date= date('Y-m-d',$s);
                                                    //to convert in days
                                                    $dw = date( "l", strtotime($today_date));
                                                    $d1 = convert_date($today_date);
                                                    $date1 = "$date_year-$date_month-$i";
                                                    $q1="select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ";  
                                                  
                                                    $qurrr=$this->db->query($q1)->result_array();
                                                    if(count($qurrr)>0){
                                                        $current1=$custom_css[2];
                                                        //echo " style='background-color:orange;'";
                                                    }   
                                                
                                                    $statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
                                                
                                                    if($d1==$current_date)
                                                    {
                                                        $current=$custom_css[1];
                                                    } 
                                                
                                                    $statuslist_css="";
                                                
                                                    if($dw=='Saturday' || $dw=='Sunday'){
                                                        $statuslist_css=$statuslist[4];
                                                    }
                                                
                                                    if(isset($plan[$today_date]['status']))
                                                    {
                                                        $statuslist_css = $statuslist[$plan[$today_date]['status']]; 
                                                        //$statuslist_css=$statuslist[4];
                                                    }
                                                 
                                                
                                                    //echo " style='background-color:#EEE; color:#CCC; font-weight:bold;'";  
                                                    echo "<tr class='".$statuslist_css." ".$current." ".$current1."'";
                                                    echo ">";
                                                
                                                    $date_num = date('N',strtotime($date1));
                                                
                                                    echo "<td style='        
                                                       '><span style='!important;'>$d1</span> </td>";
                                                       echo "<td style='        
                                                       '>$dw</td>";
                                                    echo "<td>";
                                                    if (is_array($plan[$today_date]) || is_object($plan[$today_date])){
                                                        // foreach($plan[$today_date] as $std )
                                                        // {
                                                            if($plan[$today_date]['status']==1)
                                                            {
                                                                ?>
                                                                <span class="pr"><?php echo get_phrase(' present ');?></span>
                                                                       
                                                        		<?php echo '<br>';  echo "<strong>".get_phrase("check_in")."</strong>:";
                                                        	    echo $plan[$today_date]['check_in'];
                                                        	    echo '<br>';
                                                        	   	echo "<strong>".get_phrase("check_out")."</strong>:";
                                                        	    echo $plan[$today_date]['check_out'];?>
                                                                <?php
                                                            }
                                                            elseif($plan[$today_date]['status']==2)
                                                            {
                                                                ?>
                                                                <span class="absent"><?php echo get_phrase(' absent ');?></span>
                                                            <?php
                                                            }
                                                            elseif($plan[$today_date]['status']==3)
                                                            {
                                                                ?>
                                                                <span class="leave"><?php echo get_phrase(' leave ');?>  </span>
                                                                <?php
                                                            }
                                                        // }
                                                    }
                                                
                                                    echo "</td>";
                                                    
                                                    
                                                    echo "<td>";
                                                    echo $qurrr[0]['title'];
                                                    if (is_array($plan[$today_date]) || is_object($plan[$today_date])){
                                                        foreach($plan[$today_date] as $std )
                                                        {
                                                            if($std['status']==3)
                                                            {
                                                                
                                                                $leave_date = date('Y-m-d', strtotime($d1));
                                                                $leave_qry = "select * from ".get_school_db().".leave_student 
                                                                    where student_id='$stud_id' 
                                                                    and (DATE('".$leave_date."') between start_date and end_date)
                                                                    and school_id=".$_SESSION['school_id']." ";
                                                               
                                                                $leave_arr = $this->db->query($leave_qry)->result_array();
                                                                
                                                                $get_leave_catogory = $this->Crud_model->get_type_name_by_id('leave_category',$leave_arr[0]['leave_category_id']);
                                                                echo "<strong>Category: </strong>".$get_leave_catogory->name;
                                                                echo '<br>'; 
                                                               
                                                                echo "<strong>".get_phrase('status:')."</strong>";
                                                                if($leave_arr[0]['status']==0)
                                                                {
                                                                    echo '<span class="orange">'.get_phrase('pending').'</span>';
                                                                } 
                                                                if($leave_arr[0]['status']==1)
                                                                {
                                                                    echo '<span class="green">'.get_phrase('approved').'</span>';
                                                                }
                                                                if($leave_arr[0]['status']==2)
                                                                {
                                                                    echo '<span class="orange">'.get_phrase('rejected').'</span>';
                                                                }
                                                                
                                                                echo '<br>';
                                                                echo "<strong>".get_phrase('approval_date').":</strong>";
                                                                    if($leave_arr[0]['approval_date']!="")
                                                                        { echo $leave_arr[0]['approval_date']; }else{ echo "N/A"; };
                                                                
                                                                echo '<br>';
                                                                echo "<strong>".get_phrase('description:')."</strong>".$leave_arr[0]['reason']; 
                                                                if ($leave_arr[0]['proof_doc']!="")
                                                                { ?>
                                                                    <a href=" <?php echo display_link($leave_arr[0]['proof_doc'],'leaves_student'); ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                                <?php 
                                                                }  
                                                                  
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";
                                                    echo "</tr>";
                                                } ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="row mgt10"> 
                                  <div class="col-sm-12 py-4">
                                    
                                    <div class="present-legend legend-attendance pull-left"> </div>
                                	<div class="ml pull-left"> <?php echo get_phrase('present');?></div>
                                	
                                	<div class="absent-legend legend-attendance pull-left"> </div>
                                    <div class="ml pull-left"> <?php echo get_phrase('absent');?></div>
                                	 
                                    <div class="leave-legend legend-attendance pull-left"> </div>
                                    <div class="ml pull-left"><?php echo get_phrase('leave'); ?></div>
                                    
                                	<div class="weekend-legend legend-attendance pull-left"> </div>
                                	<div class="ml pull-left"> <?php echo get_phrase('weekend');?></div>
                                
                                	<div class="holiday-legend legend-attendance pull-left"> </div>
                                	<div class="ml pull-left"> <?php echo get_phrase('holiday');?></div>
                                 
                                	<div class="today-legend legend-attendance pull-left "></div>
                                	<div class="ml pull-left"> <?php echo get_phrase('today');?></div>
                                	
                                  </div>   
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!------------------------------------------------------------>
                <!------------------------Time Table---------------------------->
                
                <!--<div id="routine" class="tab-pane fade classtimetable">-->
                    
                <!--</div>-->
                <!--  </div>-->
                <!------------------------------------------------------------>
                <div id="message" class="tab-pane">
                    <div class="">
                        <div class="">
                            <div class="">
                                <div class="box-header with-border p-3">
                                    <h3 class="box-title"> <i class="fa fa-comments-o" aria-hidden="true"></i><?php echo get_phrase('latest_messages');?></h3>
                                </div>
                                <?php
                                    $subject_arr = get_section_subject($_SESSION['section_id']);
                                    foreach($subject_arr as $sub)
                                    {
                                        $teacher_arr = get_subject_time_table_teacher($_SESSION['section_id'],$sub['subject_id'],$_SESSION['yearly_term_id']);
                            
                                        $messages=$this->db->query("select m.*, st.* from ".get_school_db().".messages m
                                            inner join ".get_school_db().".staff st on m.teacher_id=st.staff_id 
                                            inner join ".get_school_db().".student s on s.student_id=m.student_id
                                            where 
                                            m.student_id=".$_SESSION['student_id']." 
                                            and m.teacher_id=".intval($teacher_arr[0]['teacher_id'])." 
                                            and  m.subject_id=".$sub['subject_id']." 
                                            and s.section_id=".$_SESSION['section_id']." 
                                            and m.school_id=".$_SESSION['school_id']." 
                                            and 
                                            s.academic_year_id = ".$_SESSION['academic_year_id']."
                                            ORDER BY m.message_time desc LIMIT 3")->result_array();
                            
                                            if (count($messages) > 0)
                                            {
                                                ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                                                        <div class="lb">
                                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                                <div class="teacher-image">
                                                                    <?php $pic = display_link($messages[0]['staff_image'],'staff')?>
                                                                    <img src="<?php  if($messages[0]['staff_image']!=''){
                                                                        echo $pic;
                                                                    }
                                                                    else
                                                                    {
                                                                        echo base_url().'uploads/default.png';
                                                                    } ?>" style="width:65px; height:100;">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                                <strong><!--Subject:-->
                                                                    <p><?php   echo $sub['name'].' - '.$sub['code']; ?></p>
                                                                </strong>
                                                                <p><!--<strong> Message:</strong>-->
                                                                    <?php    echo $messages[0]['messages']; ?>
                                                                </p>
                                                                <strong>
                                                                    <p style="color:#6495ed;"><!--<strong>Teacher:</strong>-->
                                                                        <?php   echo $messages[0]['name']; ?>
                                                                        <!--<strong>Time:</strong>-->
                                                                        <span style="padding-left:40px; color:#008080;"><?php   echo date('d-M-Y h:i A',strtotime($messages[0]['message_time'])); ?></span>
                                                                    </p>
                                                                </strong> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php  
                                            }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <a href="<?php echo base_url();?>student_p/teacher_list" class="more more2"> <?php echo get_phrase('view_more');?>>> </a>
                        </div>
                    </div>
                </div>
                <!------------------------------------------------------------>
                <!---->
                <!----------------------Payments---------------------------->
                <div id="payment" class="tab-pane">
                    <?php 
                        $year_arr = $this->db->query("select start_date,end_date from ".get_school_db().".acadmic_year 
                         where academic_year_id = ".$_SESSION['academic_year_id']." ")->result_array();
                         $payment= $this->db->query(
                                    "select scf.* 
                                    from ".get_school_db().".student_chalan_form scf
                                    inner join ".get_school_db().".class_chalan_form ccf 
                                    on ccf.c_c_f_id=scf.c_c_f_id 
                                    and ccf.section_id =".$_SESSION['section_id']."
                                    and ccf.type = 2
                                    where scf.student_id= ".$_SESSION['student_id']." 
                                    and scf.school_id=".$_SESSION['school_id']."
                                    and scf.status = 4
                                    and 
                                    (
                                        DATE(scf.issue_date)
                                        between 
                                        '".$year_arr[0]['start_date']."' 
                                        and 
                                        '".$year_arr[0]['end_date']."'
                                    )
                                    ")->result_array();
                        //  $year_arr
                    ?>
                    <div class="row" style="margin-left: 0px; margin-right: 0px;" id="payment">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-credit-card" aria-hidden="true"></i><?php echo get_phrase('pending_payment');?></h3>
                            </div>
                            <?php foreach($payment as $rt){?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="box-body2 nds2">
                                    <h3><strong><?php echo get_phrase('chalan_number');?>:</strong>
                                        <span>
                                            <?php echo $rt['chalan_form_number'];?>
                                       </span>
                                    </h3>
                                    <h3><strong><?php echo get_phrase('month');?>/<?php echo get_phrase('year');?>:</strong>
                                        <span>
                                            <?php echo date('M',strtotime($rt['fee_month_year']));?> / <?php echo date('Y', strtotime($rt['fee_month_year']));?>
                                        </span>
                                    </h3>
                                    <h3><strong><?php echo get_phrase('due_date');?>:</strong>
                                        <span>
                                            <?php echo convert_date($rt['due_date']);?>
                                        
                                        </span>
                                    </h3>
                                    <h3><strong><?php echo get_phrase('total_amount');?>:</strong>
                                        <span>
                                            <?php echo $rt['actual_amount'];  ?>
                                        
                                        </span>
                                    </h3>
                                </div>
                            </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <a href="<?php echo base_url();?>student_p/invoice" class="more more3"> <?php echo get_phrase('view_more');?>>> </a>

                </div>
                <!-- -->
                <!-- Exams Routine -->
                <div id="exam_routine" class="tab-pane">
                    <div class="g" id="exams">
                        <div class="">
                            <div class="panel-group joined" id="accordion-test-2">

                                <?php 
                                $toggle = true;
                                $q="select e.*, y.title as yearly_term
                                from ".get_school_db().".exam e 
                                inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id 
                                inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id=y.academic_year_id 
                                where e.school_id=".$_SESSION['school_id']."
                                and ay.academic_year_id=".$_SESSION['academic_year_id']." AND DATE_FORMAT(y.start_date, '%Y') = '".date('Y')."'
                                order by e.start_date DESC";
                                $exams = $this->db->query($q)->result_array();
                                if(count($exams) > 0){
                                    foreach($exams as $row){
                                ?>
                                              
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed text-white" style="color:white" data-toggle="collapse" data-parent="#accordion-<?php echo $row['exam_id'];?>" href="#collapse<?php echo $row['exam_id'];?>">
                                                <i class="fa fa-wpforms" aria-hidden="true"></i><?php echo $row['yearly_term'].' - '.$row['name'];?>
                                                   <span style="font-size:12px;">
                                                     (<?php echo convert_date($row['start_date']).' to '.convert_date($row['end_date']);?>)
                                                 </span>
                                            </a>
                                        </h4>
                                    </div>    
                                    <div id="collapse<?php echo $row['exam_id'];?>" class="panel-collapse collapse <?php if($toggle){echo 'in';$toggle=false;}?>">
                                        <div class="panel-body">
                                            <table cellpadding="0" cellspacing="0" border="0"  class="table table-bordered">
                                                <tr><th style="width:120px;"><strong><?php echo get_phrase('day');?></strong></th>      <th style="width:120px;"><strong><?php echo get_phrase('date');?></strong></th>       <th><strong><?php echo get_phrase('subject');?></strong></th>     </tr>
                                                <tbody>
                                                <?php 
                                                    $statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
                                                    $custom_css=array(1=>'current-day',2=>'holiday');      
                                                    $current_date=date('d-M-Y');   
                                                    $date_from = strtotime($row['start_date']);
                                                    $date_to = strtotime($row['end_date']);
                                                    
                                                    $oneDay = 60*60*24;
                                                    
                                                    for($i=$date_from; $i<=$date_to; $i=$i+$oneDay)
                                                    {
                                                        $current = "";
                                                        $current1="";
                                                        /*date(" F j, Y", $i)*/
                                                        $day= convert_date(date('Y-m-d',$i));
                                                        $date1= date('Y-m-d',$i);
                                                        $dd=date("l", $i);
                                                        
                                                        if($day==$current_date)
                                                        {
                                                            $current=$custom_css[1];
                                                        } 
                                                        $statuslist_css="";
                                                        
                                                        
                                                        if($dd=="Saturday" or $dd=="Sunday")
                                                        {
                                                            $statuslist_css=$statuslist[4]; 
                                                        }
                                                        $qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ")->result_array();
                                                        if(count($qurrr)>0){
                                                            $current1=$custom_css[2];
                                                                }   
                                                                
                                                                
                                                    echo '<tr class="gradeA '.$current.' '.$current1.' '.$statuslist_css.'">'; 
                                                    ?>
                                                    
                                                    <td ><?php echo $dd;?></td>
                                                    
                                                    <td ><?php echo $day;?></td>
                                                    <td>
                                                     <?php
                                                        
                                                        $q="select er.* from ".get_school_db().".exam_routine er 
                                                        where er.school_id=".$_SESSION['school_id']." 
                                                        and er.exam_id=".$row['exam_id']." 
                                                        and section_id=".$_SESSION['section_id']."";
                                                        $routines=$this->db->query($q)->result_array();
                                                    
                                                        foreach($routines as $row2){?>
                                                           
                                                            <div class="btn-group" id="er<?php echo $row2['exam_routine_id'];?>">              
                                                               <?php if(strtotime($row2['exam_date'])==$i){
                                                            echo get_subject_name($row2['subject_id']);
                                                            echo '('.$row2['time_start'].'-'.$row2['time_end'].')';                         
                                                            ?>
                                                            
                                                            <?php if(right_granted('managedatesheet_edit')){?>
                                                            <a onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_exam_routine/<?php echo $row2['exam_routine_id'].'-'.$department_id.'-'.$class_id.'-'.$section_id.'-'.$yearly_term.'-'.$academic_year;?>');" href="#">
                                                            <i class="entypo-pencil"></i>
                                                            
                                                            </a>
                                                            <?php }?>
                                                            
                                                            <?php if(right_granted('managedatesheet_delete')){?>
                                                            <a id="delete<?php echo $row2['exam_routine_id'];?>">
                                                            <i class="entypo-trash"></i>
                                                            
                                                            </a>
                                                            <?php }?>
                                                            
                                                            <?php }?>
                                                            
                                                            </div>
                                                        <?php }?>
                                                    
                                                    </td>
                                                </tr>
                                                <?php 
                                                    }
                                                ?>
                                                
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            <?php }else{ ?>
                                <div class="text-center">
                                    <i class="fas fa-table" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                    <h2><b>Datesheet is Empty</b></h2>
                                    <a href="<?php echo base_url();?>student_p/exam_routine" style="color:black;"> <b><?php echo get_phrase('go_to_datesheet_page');?> <i class="fas fa-long-arrow-alt-right"></i></b></a>
                                </div>
                            <?php } ?>
                            </div>
            </div>
        </div>
                </div>
                <!----------------------------------------------------------->
                <!-------------EXAms Results---------------------------------->
                <div id="exam_result" class="tab-pane">
                    <div class="" id="results">
                        <div class="connectedSortable">
                            <div class="box box-success p-4">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-bar-chart" aria-hidden="true"></i>
                                        <?php echo get_phrase('examination_results');?>
                                    </h3>
                                </div>
                                <div id="container"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="virtual_class_recordings" class="tab-pane">
                    <div class="">
                        <div class="connectedSortable">
                            <div class="box box-success p-4">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-bar-chart" aria-hidden="true"></i><?php echo get_phrase('virtual_class_recordings');?>
                                    </h3>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 mgt10">
                                        <span class="text-center" style="font-size:0.8em;color:red;font-weight:bold:padding:5px" id="subjectwiserecordingmsgspan"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <form method="post" action="<?php echo base_url();?>c_student/subject_recording" class="form validate">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                         <label>Date</label>
                                         <input class="form-control datepicker1" type="text" name="date_recording" id="date_recording" style="background-color:#FFF !important;cursor: pointer !important;" readonly="readonly" required data-format="dd/mm/yyyy" >
                                         <div id="d3"></div>
                                    </div>
                                    <?php    
                                       $q_subjects ="SELECT distinct subject.subject_id , subject.name FROM ".get_school_db().".class_routine 
                                       JOIN  ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                                       JOIN ".get_school_db().".class_section ON class_routine_settings.section_id = class_section.section_id
                                       JOIN ".get_school_db().".subject ON class_routine.subject_id = subject.subject_id
                                       WHERE class_section.section_id ='".$section_id."'
                                       GROUP BY subject.name";           
                                       
                                       $subjects=$this->db->query($q_subjects)->result_array();                                    
                                    ?> 
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <label>Subject</label>
                                        <select id="student_subject_id" class="selectpicker form-control" name="student_subject_id">
                                           <option value="">Select Subject</option>
                                           <?php
                                              foreach($subjects as $subj){
                                           ?>
                                                <option value="<?php echo $subj['subject_id'] ?>"><?php echo $subj['name'] ?></option>
                                           <?php
                                              }
                                           ?>
                                           
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6" style="margin-top:1.4%">
                                         <input type="submit" id="subjectwiserecordingbtn" value="<?php echo get_phrase('filter_recordings');?>" class="btn btn-info">
                                    </div>
                                 
                                    </form>                                
                                  
                                 </div>
                               
                               
                               
                               
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="assessments" class="tab-pane">
                    <div class="">
                        <div class="connectedSortable">
                            <div class="box box-success p-4">
                                <?php if(count($assessment) > 0){ ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="alert alert-info" role="alert">
                                                <p style="font-size:0.8em">Dear Student ! Please Note if you click on solve assessment button your attempt will be counted and your time will start.</p>
                                            </div>
                                        </div> 
                                    </div>
                                <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                            <div class="tab-content">            
                                <div class="tab-pane box active" id="list">
                                    <?php 
                                        $j=$start_limit;
                                        $count = 1;
                                    ?>
                                    <style>
                                        .min-space{
                                            display: block;
                                            margin: 5px 0px 1px 0px;
                                        }
                                        .no_q{
                                            position: absolute;
                                            margin-top:-20px;
                                            left: 50%;
                                        }
                                    </style>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-responsive" id="assessment_table">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;"><div><?php echo get_phrase('s_no');?></div></th>
                                                <th class="assessment_th_mobile"><div><?php echo get_phrase('Assessment Details');?></div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            
                                            if(count($assessment) > 0)
                                            {
                                                foreach($assessment as $row):
                                                $j++;
                                                
                                                ?>
                                                <tr>
                                                    <td class="td_middle"><?= $j; ?></td>
                                                    <td style="padding: 0px 0px;">
                                                        <div class="member-entry" style="margin-top:0px;margin-bottom:0px;"> 
                                                        <a> 
                                                           <i class="fas fa-file-alt" style="font-size:90px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                                        </a> 
                                                        <div class="member-details"> 
                                                            <h4> 
                                                                <a href="#"><?php echo $row['name'];?> </a> 
                                                            </h4> 
                                                            <div class="row info-list p-0" style="color: black;"> 
                                                                <div class="col-sm-4 col-xs-4"> 
                                                                    <strong>Title:</strong>
                                                                    <?php echo ucfirst($row['assessment_title']);?>
                                                                    <br>
                                                                    <strong>Teacher Name:</strong>
                                                                    <?php echo $row['teacher_name'];?>
                                                                    <br>
                                                                    <strong>Subject:</strong>
                                                                    <?php echo get_subject_name($row['subject_id']); ?>
                                                                    <br>
                                                                    <strong>Yearly Terms:</strong>
                                                                    <?php
                                                                        $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $row['yearly_term_id'] );
                                                                        echo $yearly_terms[0]['title'];
                                                                    ?>
                                                                    <br>
                                                                    <strong>Total Marks:</strong>
                                                                    <?php echo $row['total_marks']; ?>
                                                                    <br>
                                                                    <strong>Allowed Attempts:</strong>
                                                                    <?php echo $row['total_attempts']; ?>
                                                                </div> 
                                                                <div class="col-sm-4 col-xs-4"> 
                                                                    <strong>Date:</strong>
                                                                    <?php echo date_view($row['assessment_date']); ?>
                                                                    <br>
                                                                    <strong>Start Time:</strong>
                                                                    <?php echo $row['start_time'];?>
                                                                    <br>
                                                                    <strong>End Time:</strong>
                                                                    <?php echo $row['end_time'];?>
                                                                </div> 
                                                                <div class="col-sm-4 col-xs-4 col-md-4 col-lg-3"> 
                                                                    <?php 
                                                                        if($row['mcq_questions'] > 0 ){
                                                                            echo "<div class='mcq_info'><strong class='min-space'>MCQ's : </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['mcq_questions']."</span></div>";
                                                                        }
                                                                        if($row['true_false_questions'] > 0 ){
                                                                            echo "<div class='mcq_info'><strong class='min-space'>True/False : </strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['true_false_questions']."</span></div>";
                                                                        }
                                                                        if($row['fill_in_the_blanks_questions'] > 0 ){ 
                                                                            echo "<div class='mcq_info'><strong class='min-space'>Blanks : </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['fill_in_the_blanks_questions']."</span></div>";
                                                                        }
                                                                        if($row['short_questions'] > 0 ){   
                                                                            echo "<div class='mcq_info'><strong class='min-space'>Short Q's : </strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['short_questions']."</span></div>";
                                                                        }
                                                                        if($row['long_questions'] > 0 ){   
                                                                            echo "<div class='mcq_info'><strong class='min-space'>Long Q's </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['long_questions']."</span></div>";
                                                                        }
                                                                        if($row['pictorial_questions'] > 0 ){   
                                                                            echo "<div class='mcq_info'><strong class='min-space'>Pictorial Q's</strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['pictorial_questions']."</span></div>";
                                                                        }
                                                                        if($row['match_questions'] > 0 ){   
                                                                            echo "<div class='mcq_info'><strong class='min-space'>Column Matching Q's</strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['match_questions']."</span></div>";
                                                                        }
                                                                        if($row['drawing_questions'] > 0 ){   
                                                                            echo "<div class='mcq_info'><strong class='min-space'>Drawing Q's</strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['drawing_questions']."</span></div>";
                                                                        }
                                                                    ?>
                                                                </div> 
                                                                <div class="clear"></div> 
                                                                <div class="col-sm-12 col-xs-12 ststs-asesmnt"> 
                                                                            <b>Assessment Status :</b> 
                                                                    <ul class="memeber_entry_buttons"> 
                                                                        <?php
                                                                        if(date('Y-m-d',strtotime($row['assessment_date'])) == date('Y-m-d'))
                                                                        {
                                                                            $start_t = date("H:i", (strtotime($row['start_time'])-(60)));
                                                                            $end_t = date("H:i", (strtotime($row['end_time'])-(60)));
                                                                            $current_t = date("H:i", strtotime("now"));
                                                                            
                                                                            if($current_t >= $start_t And $current_t <= $end_t)
                                                                            {
                                                                                if($row['number_of_attempts'] < $row['total_attempts'])
                                                                                {
                                                                         ?>     <li>
                                                                                    <a class="modal_save_btn" id="assessment_id_<?php echo $row['assessment_id'];?>" href="javascript:void(0)" onclick="check_validation(this)">
                                                                                        Solve Assessment
                                                                                    </a>
                                                                                </li>    
                                                                         <?php
                                                                                }else{
                                        	                                        echo "<li><b class='badge badge-danger'>You Have No More Attempts</b></li>";
                                        	                                    }
                                                                            }
                                                                            elseif($current_t > $end_t){
                                                                                echo "<td><span>Time Over</span></td>";
                                                                            }elseif($current_t < $start_t){
                                                                                echo "<li><span class='badge badge-info'>Time Not Started</span></li>";
                                                                            }
                                                                            
                                                                        }elseif(date('Y-m-d',strtotime($row['assessment_date'])) < date('Y-m-d'))
                                                                        {
                                                                            echo "<li><span class='badge badge-info'>Date Over</span></li>";
                                                                        }else{
                                                                            echo "<li><span class='badge badge-info'>Date Not Started</span></li>";
                                                                        }
                                                                    ?>  
                                                                    </ul> 
                                                                </div>
                                                            </div> 
                                                        </div> 
                                                    </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach;
                                            }
                                            else
                                            {
                                            ?>
                                                <tr>
                                                    <td colspan="2" style="text-align: center;"> 
                                                        <div class="text-center">
                                                            <i class="fas fa-poll-h" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                                            <h2><b>No Assessment</b></h2>
                                                            <a href="<?php echo base_url();?>assessment_student/view_assessment" style="color:black;"> <b><?php echo get_phrase('go_to_assessment_page');?> <i class="fas fa-long-arrow-alt-right"></i></b></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                     </tbody>
                                    </table> 
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="modal fade eduModal" id="modal-4" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content" style="margin-top:100px;">
                                <div class="modal-header">
                                    <h4 class="modal-title" style="text-align:center;">Are you sure to Continue ?</h4>
                                    <button type="button" class="close" data-dismiss="modal"aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                    <a href="#" class="btn btn-danger" data-id="1" id="delete_link">Yes</a>
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="background-color:#F44336!important;border:#f34336 1px solid!important">Cancel</button>
                                </div>
                            </div>
                        </div>
                        </div>
    
                            <script>
                                function check_validation(a)
                                {
                                    var id = a.id;
                                    var res = id.split("_");
                                    var assessment_id = res[2];
                                    $('#modal-4').modal('show',{backdrop: 'static', keyboard: false}); //Added By tm
                                
                                    document.getElementById('delete_link').onclick = function(){
                                       var url = "<?php echo base_url().'assessment_student/assessment_details/';?>"+assessment_id;
                                        window.location.replace(url);
                                    }
                                }
                            </script>
                            </div>
                        </div>
                        
                <?php 
                
                
                    $q="select sum(`marks_obtained`) as total_marks,s.student_id, m.exam_id ,e.name from ".get_school_db().".marks m 
                    inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id 
                    inner join ".get_school_db().".student s on m.student_id=s.student_id 
                    inner join ".get_school_db().".exam e on m.exam_id=e.exam_id
                    inner join ".get_school_db().".yearly_terms yt on e.yearly_terms_id = yt.yearly_terms_id
                    inner join ".get_school_db().".acadmic_year a on a.academic_year_id = yt.academic_year_id
                    where s.section_id =$section_id
                    AND m.student_id=$student_id 
                    and m.school_id=".$_SESSION['school_id']." 
                    and a.academic_year_id=".$_SESSION['academic_year_id']."
                    group by exam_id";

                    $qu=$this->db->query($q);
                    $exame = array();
                    
                    foreach($qu->result() as $quq)
                    {
                        $total_marks=$quq->total_marks;
                        $name=$quq->name;
                        $exam_name[]=$quq->name;
                        $exam_id=$quq->exam_id;
                        $exame[$exam_id]=$total_marks;
                    }


                    $qr2 = "select sum(`marks_obtained`) as total_marks,m.student_id, m.exam_id ,e.name from ".get_school_db().".marks m 
                    inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id 
                    inner join ".get_school_db().".student s on m.student_id=s.student_id 
                    inner join ".get_school_db().".exam e on m.exam_id=e.exam_id
                    inner join ".get_school_db().".yearly_terms yt on e.yearly_terms_id = yt.yearly_terms_id
                    inner join ".get_school_db().".acadmic_year a on a.academic_year_id = yt.academic_year_id
                    inner join ".get_school_db().".exam_routine er
                    on e.exam_id=er.exam_id
                    where s.section_id =$section_id
                    and m.school_id=".$_SESSION['school_id']." 
                    and a.academic_year_id=".$_SESSION['academic_year_id']."
                    and er.is_approved=1
                    group by exam_id, m.student_id";
                    
                    $qur2=$this->db->query($qr2);
                     $exam2=array();
                     foreach($qur2->result() as $qurr2){
                      $marks_obtained=$qurr2->total_marks;
                      $name=$qurr2->name;
                      $exam_id=$qurr2->exam_id;
                      $exam2[$exam_id][]=$marks_obtained;
                     }
                     
                    $exam_max=array();
                    $exam_min=array();
                    $exam_avg=array();
                    
                    foreach($exam2 as $key=>$value){
                        $exam_max[$key]=max($value);
                        $exam_min[$key]=min($value);
                        $exam_avg[$key]=round(array_sum($value)/count($value));
                    }
                ?>
            </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?> 
<script>
$(document).ready(function() {
        
   $('#subjectwiserecordingbtn').click(function(){
        var isvalid = true;
        var errors = '';
        if ( $('#section_id').val() == "" ){
          isvalid = false;
          errors += 'Please select the subject <br>';
        }
        if ( $('#student_subject_id').val() == "" ){
          isvalid = false;
          errors += 'Please select the date <br>';
        }
  
        if(isvalid){
           $('#subjectwiserecordingmsgspan').html('');    
           return true;
        }
        else
        {
          $('#subjectwiserecordingmsgspan').html(errors);    
          return false;
         }
  
    });
    
    // Configure/customize these variables.
    var showChar = 100; // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "Show more >";
    var lesstext = "Show less";
    $('.moren').each(function() {
        var content = $(this).html();

        if (content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }
    });

    $(".morelink").click(function() {
        if ($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
}); 
</script>
<script>
    $(document).ready(function() {
        $('.text').each(function(element, index) {
            if ($(this)[0].scrollHeight > $(this).height()) {
                $(this).next().show()
            } else {
                $(this).next().hide()
            }
        })
    });
</script>
    <!-- Footer -->
<script>
    $(document).ready(function() {
        $('.demo').on('click', function(e) {
            e.preventDefault();
            var target = this.hash;
            var $target = $(target);

            $('html, body').stop().animate({
                'scrollTop': $target.offset().top
            }, 900, 'swing', function() {
                window.location.hash = target;
            });
        });
    });
</script>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js"></script>
    <script>
        var OneSignal = OneSignal || [];
        
        OneSignal.push(["init", {
          appId: "c0c462a2-13b1-4dc9-a93c-d45b65c10b55"
        }]);
        
        
        OneSignal.push(function() {
            var isPushSupported = OneSignal.isPushNotificationsSupported();
            if (isPushSupported) {
                getuserID();
            }
            
        });
    
        function getuserID()
        {
            var user_id   = '<?php echo $_SESSION['student_id']; ?>';
            OneSignal.isPushNotificationsEnabled(function(isEnabled) {
                if (isEnabled){
                    console.log("Push notifications are enabled!");
                    OneSignal.getUserId(function(userId) {
                        $.ajax({
                            url:"<?php echo base_url(); ?>notifications/set_firebase_data",
                            type:"post",
                            data:{device_id:userId,user_id:user_id,platform:'WEB'},
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
