<style>
	
	.modal-content{position:relative;top:500px;}
    .table-condensed > thead > tr > th, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > tbody > tr > td, .table-condensed > tfoot > tr > td {
        padding: 3px;
    	
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding:  10px;
        border-bottom: 1px solid #CCC;
        border-left: 1px solid #CCC;
    	text-align:center;
    }
    	.form-group {
        margin-bottom: 0px;
    }

    .hw{height:30px; width:30px; border:1px solid #CCC;}
    .red{background-color: #f2dede;}
    .green{background-color: #dff0d8;}
    .blue{background-color: #d9edf7;}
    .grey{background-color: #efefef;}
    .orange{background-color: #fcf8e3;}
    .ml{
        padding-left: 5px;
        padding-right: 23px;
        padding-top: 6px;
    }
    .bl{border:1px solid #000 !important;}
    
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
                    <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
                </a>
                <h3 class="system_name inline">
                    <?php echo get_phrase('subjectwise_recording'); ?>
                </h3>
            </div>
        </div>
        
        <form method="post" action="<?php echo base_url();?>virtualclass/subject_recording" class="form validate" data-step="1" data-position="top" data-intro="Please select the filters and press Filter Recordings button to get specific records">
            <div>        
                <div class="row filterContainer">
                    <div class="col-lg-12 col-md-12 col-sm-12 mgt10">
                         <span class="text-center" style="color:red;font-weight:bold:padding:5px" id="subjectwiserecordingmsgspan"></span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                         <label>Date</label>
                         <input class="form-control datepicker1" type="text" name="date_recording" id="date_recording" style="background-color:#FFF !important;cursor: pointer !important;" value="<?php echo $date ?>" readonly="readonly" required data-format="dd/mm/yyyy" >
                         <div id="d3"></div>
                    </div>          
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <label>Section</label>
                        <select id="section_id" class="selectpicker form-control" name="section_id">
                            <?php echo section_selector($section_id);?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6" style="margin-top:1.4%">
                         <input type="submit" id="subjectwiserecordingbtn" value="<?php echo get_phrase('filter_recordings');?>" class="btn btn-info">
                    </div>        
                </div>
            </div>
        </form>
        <?php if($date !='' && $section_id !='' ){?>
		<script>
			$(document).ready(function() {
				$("#fixTable").tableHeadFixer({"left" : 2});
			});
		</script>
        <div class="col-md-12" style="overflow-y:scroll;">
                    <table id="fixTable" class="table table-bordered table-condensed table_export" style="margin-top:10px !important;" data-step="2" data-position="top" data-intro="recording class records">
                        <thead>
                            <tr class="table_header_attendance">
                                
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('date');?>
                                </th>
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('subject');?>
                                </th>
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('teacher');?>
                                </th>
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('action');?>
                                </th>
                            </tr>
                        </thead>
                        <?php
                        
                         $query_class="select virtual_class_student.virtual_class_id, subject.name , virtual_class_student.student_name, 
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') as date,
                         virtual_class.platform_id,
                         virtual_class_student.virtual_class_name,
                         virtual_class_student.virtual_class_join,
                         staff.name as teacher_name
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
                         WHERE  
                         DATE_FORMAT(virtual_class_student.vc_start_time, '%d/%m/%Y') = '".$date."' AND
                         class_section.section_id =".$section_id;
                         $classes=$this->db->query($query_class)->result_array();
                        ?>
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
                                    <?php echo $class['name'] ?>
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
                                                    <a target="_blank" data-step="3" data-position="left" data-intro="press this icon then play recording class" href="<?php echo base_url(); ?>virtualclass/view_recording/<?php echo $meeting_id[1]."/".$class['virtual_class_name']; ?>">
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
                                                    <a target="_blank" data-step="3" data-position="left" data-intro="press this icon then play recording class" href="<?php echo base_url(); ?>virtualclass/view_recording/<?php echo $meeting_id[1]."/".$class['virtual_class_name']; ?>">
                                                          <i class="entypo-eye"></i>
                                                          <?php echo get_phrase('view_recording'); ?>
                                                    </a>
                                    <?php
                                              }
                                        }
                                        
                                     ?>
                                </td>
                                </tr>
                            <?php } } else { ?>
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
                
                if ( $('#section_id').val() == "" ){
                  isvalid = false;
                  errors += 'Please select the section <br>';
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
		


