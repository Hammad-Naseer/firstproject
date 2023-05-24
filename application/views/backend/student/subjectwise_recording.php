
<style>
.modal-content{position:relative;top:500px}.table-condensed>tbody>tr>td,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>td,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>thead>tr>th{padding:3px}.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:10px;border-bottom:1px solid #ccc;border-left:1px solid #ccc;text-align:center}.form-group{margin-bottom:0}.hw{height:30px;width:30px;border:1px solid #ccc}.red{background-color:#f2dede}.green{background-color:#dff0d8}.blue{background-color:#d9edf7}.grey{background-color:#efefef}.orange{background-color:#fcf8e3}.ml{padding-left:5px;padding-right:23px;padding-top:6px}.bl{border:1px solid #000!important}
</style>

<script>
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
                <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                        <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
                    </a>
                <h3 class="system_name inline">
					<?php echo get_phrase('subjectwise_recording'); ?>
                </h3>
            </div>
        </div>
        <form method="post" action="<?php echo base_url();?>student_p/subject_recording" class="form validate" data-step="1" data-position='top' data-intro="Please use this filter to get specific recordings">
            <div class="">
                <div class="row filterContainer pb-4">
                    <div class="col-lg-12 col-md-12 col-sm-12 mgt10">
                         <span class="text-center" style="font-size:0.8em;color:red;font-weight:bold:padding:5px" id="subjectwiserecordingmsgspan"></span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4" data-step="2" data-position='top' data-intro="Select start date">
                         <label>Start Date</label>
                         <input class="form-control start_date" type="date" name="start_date_recording" id="date_recording" style="background-color:#FFF !important;cursor: pointer !important;" value="<?php echo $sdate ?>" required>
                         <div id="d3"></div>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 col-sm-4" data-step="3" data-position='top' data-intro="Select end date">
                         <label>End Date</label>
                         <input class="form-control end_date" type="date" name="end_date_recording" id="date_recording" style="background-color:#FFF !important;cursor: pointer !important;" value="<?php echo $edate ?>" required >
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
                                    
                    <div class="col-lg-4 col-md-4 col-sm-4" data-step="4" data-position='top' data-intro="Select subject">
                        <label>Subject</label>
                        <select id="student_subject_id" class="selectpicker form-control" name="student_subject_id">
                           <option value="">Select Subject</option>
                           <?php
                              foreach($subjects as $subj){
                                  $isSelected = '';
                                  $isSelected = $subj['subject_id'] == $subject_id ? 'selected' : '';
                           ?>
                                <option value="<?php echo $subj['subject_id'] ?>"  <?php echo $isSelected  ?>><?php echo $subj['name'] ?></option>
                           <?php
                              }
                           ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6" style="margin-top:1.4%" data-step="5" data-position='top' data-intro="Press this button to get results">
                         <input type="submit" id="subjectwiserecordingbtn" value="<?php echo get_phrase('filter_recordings');?>" class="btn btn-info">
                    </div>        
                </div>
            </div>
        </form>
        <?php if($sdate !='' && $edate !='' && $student_id !='' && $section_id !='' && $subject_id !=''){ ?>
        <?php
            $query_class="select virtual_class_student.virtual_class_id, virtual_class_student.student_name,
             virtual_class.platform_id,
             virtual_class_student.student_name , 
             staff.name as teacher_name,
             virtual_class_student.virtual_class_join,
             DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') as date ,
             virtual_class_student.virtual_class_name
             from ".get_school_db().".class_routine 
             JOIN ".get_school_db().".virtual_class_student ON class_routine.class_routine_id = virtual_class_student.class_routine_id 
             JOIN ".get_school_db().".virtual_class ON virtual_class.virtual_class_id = virtual_class_student.virtual_class_id 
             JOIN ".get_school_db().".student ON student.student_id = virtual_class_student.student_id
             JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id 
             JOIN  ".get_school_db().".time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
             JOIN ".get_school_db().".subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id 
             JOIN ".get_school_db().".staff ON subject_teacher.teacher_id = staff.staff_id 
             JOIN ".get_school_db().".class_section ON class_routine_settings.section_id = class_section.section_id 
             JOIN ".get_school_db().".class ON class.class_id = class_section.class_id 
             JOIN ".get_school_db().".subject ON class_routine.subject_id = subject.subject_id
             WHERE DATE_FORMAT(virtual_class_student.vc_start_time, '%Y-%m-%d') BETWEEN '".$sdate."' AND '".$edate."' AND
             class_section.section_id =".$section_id." AND subject.subject_id = " . $subject_id;
             
            $classes=$this->db->query($query_class)->result_array(); 
        ?>
        <div class="col-md-12" style="overflow-y:scroll;">
            <table id="" class="table table-bordered table_export" style="margin-top:10px !important;">
                <thead>
                            <tr class="table_header_attendance">
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('date');?>
                                </th>
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('teacher');?>
                                </th>
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('action');?>
                                </th>
                            </tr>
                        </thead>
                <tbody>
                    <?php
                      if(count($classes) > 0) {
                        foreach($classes as $class){
                    ?>
                    <tr class="table_header_attendance">
                        
                        <td>
                            <?php echo $class['date'] ?>
                        </td>
                        <td>
                            <?php echo $class['teacher_name'] ?>
                        </td>
                        <td>
                            <?php 
                                if(empty($class['platform_id']) || $class['platform_id'] == 1){
                                        $meeting_id = explode("-",$class['virtual_class_id']); 
                                        $meetingId = $class['virtual_class_id']; 
                                        $recordingUrl = WEBRTC_LINK."api/getRecordings?";
                		                $params = '&meetingID='.urlencode($meetingId);
                                        $url_recording = $recordingUrl.$params.'&checksum='.sha1("getRecordings".$params.WEBRTC_SECRET);
                                        $xmldata = simplexml_load_file($url_recording) or die("Failed to load");
                                        if($xmldata->returncode == 'SUCCESS' And $xmldata->messageKey != 'noRecordings'){
                            ?>
                                            <a target="_blank" href="<?php echo base_url(); ?>virtualclass/view_recording/<?php echo $meeting_id[1]."/".$class['virtual_class_name']; ?>">
                                                  <i class="entypo-eye"></i>
                                                  <?php echo get_phrase('view_recording'); ?>
                                            </a>
                            <?php
                                        }
                                        else
                                        {
                                              echo "<span class='text-danger text-center'>No Recording Found</span>";
                                        }
                                }
                                else if(empty($class['platform_id']) || $class['platform_id'] == 3){
                                        $meeting_id   = explode("-",$class['virtual_class_id']); 
                                        $meetingId    = $class['virtual_class_id']; 
                                        $recordingUrl = ICWEBRTC_LINK."api/getRecordings?";
                		                $params = '&meetingID='.urlencode($meetingId);
                                        $url_recording = $recordingUrl.$params.'&checksum='.sha1("getRecordings".$params.ICWEBRTC_SECRET);
                                        $xmldata = simplexml_load_file($url_recording) or die("Failed to load");
                                        if($xmldata->returncode == 'SUCCESS' And $xmldata->messageKey != 'noRecordings'){
                                ?>
                                            <a target="_blank" href="<?php echo base_url(); ?>virtualclass/view_recording/<?php echo $meeting_id[1]."/".$class['virtual_class_name']; ?>">
                                                  <i class="entypo-eye"></i>
                                                  <?php echo get_phrase('view_recording'); ?>
                                            </a>
                                <?php
                                        }
                                }
                             ?>
                        </td>
                        </tr>
                    <?php
                      }
                      }else{
                    ?>
                    <tr class="table_header_attendance">
                           <td colspan="3"><span style="color:red;text-align:center">No Record Found</span></td>
                    </tr>
                    <?php } ?>
                </tbody>   
            </table>
        </div>
<?php } ?>
<script>
        $(document).ready(function() {
           var today = new Date();
           var startDate = new Date( today.getFullYear() , today.getMonth ,1);
           $('.datepicker1').datepicker({
              format: 'dd/mm/yyyy',
              keyboardNavigation:false,
              forceParse: false,
              autoclose:true,
              endDate: '+0d',
              defaultDate : new Date(),
              startDate: '-3d'
           }).datepicker('setStartDate' , startDate);    
            
            
            $('#subjectwiserecordingbtn').click(function(){
                    var isvalid = true;
                    var errors = '';
                    if ( $('#date_recording').val() == "" ){
                      isvalid = false;
                      errors += 'Please select the date <br>';
                    }
                    if ( $('#student_subject_id').val() == "" ){
                      isvalid = false;
                      errors += 'Please select the subject <br>';
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

            

        });
    </script>
        
<script>
	$('#fixTable').on('focus', 'td', function() {
	$this = $(this);
	$this.closest('#fixTable').scrollLeft($this.index() * $this.outerWidth());
	}).on('keydown', 'td', function(e) {
	}).find('td #selecctall').focus();
</script>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo get_phrase('confirm'); ?></h4>
        </div>
        <div class="modal-body">
          <p><?php echo get_phrase('are_you_sure'); ?> </p>
          
<a href="" id="btn_attan" class="btn btn-primary" data-dismiss="modal"><?php echo get_phrase('yes'); ?></a>

<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('no'); ?></button>
          
          
        </div>
    
    
      </div>
      
    </div>
  </div>
  
  <!--//***********************Date filter validation***********************-->
<script>
    $(".start_date").change(function () {
        var startDate = s_d($(".start_date").val());
        var endDate = s_d($(".end_date").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementsByClassName("start_date").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $(".end_date").change(function () {
        var startDate = s_d($(".start_date").val());
        var endDate = s_d($(".end_date").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementsByClassName("end_date").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementsByClassName("end_date").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->
		