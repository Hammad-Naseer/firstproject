<style>
.modal-content{position:relative;top:500px}.table-condensed>tbody>tr>td,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>td,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>thead>tr>th{padding:3px}.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:10px;border-bottom:1px solid #ccc;border-left:1px solid #ccc;text-align:center}.form-group{margin-bottom:0}.hw{height:30px;width:30px;border:1px solid #ccc}.red{background-color:#f2dede}.green{background-color:#dff0d8}.blue{background-color:#d9edf7}.grey{background-color:#efefef}.orange{background-color:#fcf8e3}.ml{padding-left:5px;padding-right:23px;padding-top:6px}.bl{border:1px solid #000!important}	
</style>

<?php
    if (right_granted('managestudentattendance_manage')){
    $this->session->flashdata('flash_message');
    if($this->session->flashdata('flash_message')){
?>
<div class="
<?php if($this->session->flashdata('flash_message')=='value_missing'){ 
 echo 'alert alert-danger';  }else{
echo 'alert  alert-success';

} ?>" id="flash_danger">
        <center>
            <?php echo $this->session->flashdata('flash_message'); ?>
        </center>
    </div>
    <?php 
} ?>
    <?php
if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
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
					<?php echo get_phrase('subjectwise_attendance'); ?>
                </h3>
            </div>
        </div>
        <form id="student_attendance" method="post" action="<?php echo base_url();?>attendance/subjectwise_attendance" class="form validate">
            <div>        
                <div class="row filterContainer">
                    <!--<div class="col-lg-6 col-md-6 col-sm-12 mgt10">-->
                    <!--     <span class="text-center" style="font-size:0.8em;color:red;font-weight:bold:padding:5px" id="subjectwiseattendancemsgspan"></span>-->
                    <!--</div>-->
                    <div class="col-lg-6 col-md-6 col-sm-6" data-step="1" data-position="top" data-intro="Step 1: select date">
                        <input class="form-control datepicker1" type="text" name="date" id="date" value="<?php echo $date."/".$month."/".$year; ?>" style="background-color:#FFF !important;" readonly="readonly" required data-format="dd/mm/yyyy" >
                        <div id="d3"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6" data-step="2" data-position="top" data-intro="Step 2: class - section">
                        <select id="section_id" class="selectpicker form-control" name="section_id">
                            <?php echo section_selector($section_id);?>
                        </select>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3" data-step="3" data-position="top" data-intro="press this button get subject wise attendance sheet">
                        <input type="submit" id="subjectwiseattendancebtn" value="<?php echo get_phrase('filter');?>" class="modal_save_btn">
                        <?php if($filter > 0){?>
                        <a href="<?php echo base_url(); ?>attendance/subjectwise_attendance/<?php echo date('d/m/Y');?>" class="modal_cancel_btn" id="btn_remove">
                            <i class="fa fa-remove"></i>
                            <?php echo get_phrase('remove_filter'); ?>
                        </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
<?php
    if($date!='' && $month!='' && $year!='' && $section_id!=''){
?>
        <center>
            <div class="row">
                <div class="text-center ">
                    <?php
                    $full_date  =   $year.'-'.$month.'-'.$date;
                    $timestamp = strtotime($full_date);
                    $day = strtolower(date('l', $timestamp));
                    $details=section_hierarchy($section_id); 
                    $s=mktime(0,0,0,$month, $date, $year);
                    $d2=date('d-M-Y',$s);
                    $date_curr= date("t",$s);
                    
                 ?>
                        <ul class="breadcrumb" style="    color: #000000;
                         font-weight: bold;">
                            <li>
                                <?php echo $details['c'];?>
                            </li>
                            <li>
                                <?php echo $details['s'];?>
                            </li>
                            <li>
                                <?php echo ucwords($day);?>
                            </li>
                            <li>
                                <?php echo $d2;?>
                            </li>
                        </ul>
                </div>
            </div>
        </center>
        
		<script>
			$(document).ready(function() {
				$("#fixTable").tableHeadFixer({"left" : 2});
			});
		</script>
		
        <div class="row">
            <div style="overflow-y:scroll;width: 100%;">           
                   <style>
                      .table_header_attendance{
                       padding: 8px;
                        line-height: 1.42857143;
                        vertical-align: middle;
                       }
                       th.table_header_rol_number {
                           width: 7%;color:white;
                            background-color: #012b3c!important;
                        }
                        th.table_header_student_name { color:white;
                            background-color: #012b3c!important;
                        }
                        th.table_header_subject_name {
                            background-color: #012b3c !important;
                            color:white;
                        }
                        th.startTime {
                            background-color: #012b3c !important;
                            padding: 3px;
                            text-align: center;
                            color: white;
                            top: -8px;
                        }
                        th.endTime {
                        background-color: #012b3c !important;
                        padding: 3px;
                        text-align: center;
                        color: white;
                        top: -8px;
                    }
                   </style>
                   
                    <table id="fixTable" class="table table-strped table-condensed" >
                        <thead>
                            <tr class="table_header_attendance">
                                
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('Roll No.');?>
                                </th>
                                <th class="table_header_student_name">
                                    <?php echo get_phrase('student_name');?>
                                </th>
                                
                                <?php 
                                
                                $total_coumns = 2;
                                
                                $date_object = $date.'/'.$month.'/'.$year;
                                
                                $q5 ="SELECT * FROM ".get_school_db().".subject LEFT JOIN  ".get_school_db().".subject_section ON subject.subject_id = subject_section.subject_id
                                       JOIN ".get_school_db().".class_routine ON subject.subject_id = class_routine.subject_id
                                       JOIN ".get_school_db().".virtual_class ON virtual_class.class_routine_id = class_routine.class_routine_id
                                       JOIN ".get_school_db().".class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id
                                       JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                                       WHERE class_section.section_id ='".$section_id."' and 
                                       DATE_FORMAT(virtual_class.vc_start_time, '%d/%m/%Y') = '".$date_object."'
                                       GROUP BY subject.name";    
            	                $subjects=$this->db->query($q5)->result_array();
				                                               
				                foreach($subjects as $subj)
				                {
				                    $total_coumns = $total_coumns + 1;
                                ?>
                                <th class="table_header_subject_name"> <?php echo $subj['name'];?> </th>
                                    
                                <?php 
                                }
                                ?>                              
                            </tr>
                        </thead>
                        <tbody id="attendancetable">
                            <?php 
                    //STUDENTS ATTENDANCE
                        $attendance='select student.roll as roll_number , virtual_class_student.student_id , subject.name as subject_name , virtual_class_student.student_name , 
                         virtual_class_student.vc_start_time as time_started_at , virtual_class_student.vc_end_time as time_ended_at
                         from '.get_school_db().'.class_routine 
                         JOIN '.get_school_db().'.virtual_class_student ON 
                         class_routine.class_routine_id = virtual_class_student.class_routine_id 
                         JOIN '.get_school_db().'.student ON student.student_id = virtual_class_student.student_id
                         JOIN '.get_school_db().'.class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id 
                         JOIN  '.get_school_db().'.time_table_subject_teacher ON 
                         time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                         JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id 
                         JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
                         JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id 
                         JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id 
                         JOIN '.get_school_db().'.subject ON class_routine.subject_id = subject.subject_id
                         WHERE  ((DATE_FORMAT(virtual_class_student.vc_start_time, "%Y") = '. $year .') AND 
                         (DATE_FORMAT(virtual_class_student.vc_start_time, "%c") = '. $month .' )   AND 
                         (DATE_FORMAT(virtual_class_student.vc_start_time, "%e") = '.$date .'))   AND
                         class_section.section_id =' . $section_id;
                    
                    $attend=$this->db->query($attendance)->result_array();
            
                    $stud_ids_array =array();
                    $stud_names_array =array();
                    
                    $startedtime_array =array();
                    $endedtime_array =array();
                    $sub_names_array =array();
                    
                    $stud_rollnumber_array = array();
                    
                    
                    $stud_ids_unique = array();
                    $stud_names_unique = array();
                    $stud_rollnumber_unique = array();
                    
                

            foreach($attend as $row_stud)
            {
                
                // unique identifiers so should only be added in array once
                if (!in_array($row_stud['student_id'], $stud_ids_unique))
                {
                    array_push($stud_ids_unique,$row_stud['student_id']);
                    array_push($stud_names_unique,$row_stud['student_name']);
                    array_push($stud_rollnumber_unique,$row_stud['roll_number']);
                }
                
                array_push($stud_ids_array,$row_stud['student_id']);
                array_push($stud_names_array,$row_stud['student_name']);
                array_push($sub_names_array,$row_stud['subject_name']);
                array_push($startedtime_array,$row_stud['time_started_at']);
                array_push($endedtime_array,$row_stud['time_ended_at']);
                array_push($stud_rollnumber_array,$row_stud['roll_number']);
            }
            

            
            if(count($stud_ids_unique) > 0){
                
            
            for($stud_counter=0; $stud_counter < count($stud_ids_unique); $stud_counter++){
            ?>
            <tr class="gradeA attendence">
                    <td><?php echo $stud_rollnumber_unique[$stud_counter];  ?></td>
                    <td><?php echo $stud_names_unique[$stud_counter];  ?></td>
                    <?php 
                        foreach($subjects as $subj_row){
                        
                           $cellString = '';
                           $start_time = '';
                           $end_time = '';
                           for($counter=0; $counter < count($stud_ids_array); $counter++){
                                if($stud_ids_array[$counter] == $stud_ids_unique[$stud_counter] && $sub_names_array[$counter] == $subj_row['name']){
                                       $cellString = $startedtime_array[$counter] . "<br>" . $endedtime_array[$counter];
                                       $start_time = $startedtime_array[$counter];
                                       $end_time   = $endedtime_array[$counter]; 
                                }
                           } // end of for
                        
                            if(!empty($cellString)){
                               
                        ?>
                                <td>
                                    <table style="width: 100%;">
                                     <tr style="font-size:0.8em">
                                        <th class="startTime" style="border-right:1px solid black">Start Time</th>
                                        <th class="endTime" style="padding-left:10px">End Time</th>
                                      </tr>
                                      <tr style="font-size:0.7em">
                                        <td style="border-right:1px solid black;padding-top:4px"><?php echo date("h:i A" , strtotime($start_time))  ?></td>
                                        <?php
                                          if(trim($end_time) != ''){
                                        ?>
                                           <td style="padding-left:10px;padding-top:4px"><?php echo date("h:i A" , strtotime($end_time))  ?></td>
                                        <?php
                                          }
                                          else
                                          {
                                        ?>
                                            <td style="padding-left:10px;padding-top:4px">Improper Ending</td>
                                        <?php
                                          }
                                        ?>
                                      </tr>
                                    </table>
                                </td>
                        <?php
                               //echo "<td>".$cellString."</td>";
                            }
                            else
                            {
                               echo "<td>Absent</td>";
                            }
                            
                        } // end of foreach
                    ?>
            </tr>
            <?php    
            }
            
            
            }
            else
            {
            
            ?>
            <tr class="gradeA attendence">
                <td colspan="<?php echo $total_coumns ?>"><h3 class="text-center" style="color: #cc2424 !important">No Record Found</h3></td>
            </tr>    
            <?php
                
            }
            
            ?>
                            
                        </tbody>
                    </table>
            </div>
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
            
            
            
            $('#subjectwiseattendancebtn').click(function(){
                    var isvalid = true;
                    var errors = '';
                    if ( $('#section_id').val() == "" ){
                      isvalid = false;
                      errors += 'Please select the section <br>';
                    }
              
                    if(isvalid){
                       $('#subjectwiseattendancemsgspan').html('');    
                       return true;
                    }
                    else
                    {
                      $('#subjectwiseattendancemsgspan').html(errors);    
                      return false;
                     }
              
                });
            
            
            
            
       $('.selectpicker').on('change', function() {
var id = $(this).attr('id');
var selected = $('#' + id + ' :selected');
var group = selected.parent().attr('label');
$('#' + id + '_selection').text(group);
});
<?php if($section_id=="")
{?>
/*$("#section_id").html('<select><option value="">Select Section</option></select>');*/
            <?php }?>
            
$(document).ready(function() {
$('#btn_attan').click(function(){
var date=$("#date_val").val();
$.ajax({ 
type: 'POST',
data: {date:date},
url: "<?php echo base_url();?>attendance/mark_absent_student/",
dataType: "html",
success: function(response){
	
location.reload();
}
});
});

	
 $("#selecctall").change(function() {
$(".checkbox1").prop('checked', $(this).prop("checked"));
});


                $(".checkbox1").change(function() {


                    $("#selecctall").prop("checked", false);

                });
            });



        });
        	$('#fixTable').on('focus', 'td', function() {
    		$this = $(this);
    		$this.closest('#fixTable').scrollLeft($this.index() * $this.outerWidth());
			}).on('keydown', 'td', function(e) {
			}).find('td #selecctall').focus();
        </script>
<?php
}
?>


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
		