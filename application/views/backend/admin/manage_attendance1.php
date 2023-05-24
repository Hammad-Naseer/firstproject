<style>
	   .modal-content{position:relative;top:500px;}

</style>     



<style>
/*	td{ border:1px solid #ccc;}*/

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
.ml{    padding-left: 5px;
    padding-right: 23px;
    padding-top: 6px;}
.bl{border:1px solid #000 !important;}	
</style>
<style>
#parent {
/*	height: 300px;*/
}			
#fixTable {
/*		width: 100% !important;*/
			}
		</style>
<?php
if (right_granted('managestudentattendance_manage'))
{

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
$(window).load(function() {
setTimeout(function() {
$('.alert').fadeOut();
}, 3000);
        });
        </script>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
                <h3 class="system_name inline">
                    <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                    <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/student-attendance.png">
					<?php echo get_phrase('WebRTC_attendance'); ?>
                </h3>
            </div>
        </div>
        <form id="student_attendance" method="post" action="<?php echo base_url();?>virtualclass/vc_attendance_list" class="form validate">
            <div class=" thisrow pd10 ">
                <div class="row">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <label id="section_id_selection"></label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input class="form-control datepicker" type="text" name="date" id="date" value="<?php echo $date."/".$month."/".$year; ?>" style="background-color:#FFF !important;" required data-format="dd/mm/yyyy" >
                        <div id="d3"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <select id="section_id" class="selectpicker form-control" name="section_id">
                            <?php echo section_selector($section_id);?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 mgt10">
                        <input type="submit" value="<?php echo get_phrase('filter');?>" class="btn btn-info">
                        <?php    
        if($filter > 0)
            {?>
                        <a href="<?php echo base_url(); ?>virtualclass/vc_attendance_list/<?php echo date('d/m/Y');?>" class="btn btn-danger" id="btn_remove">
                            <i class="fa fa-remove"></i>
                            
                            <?php echo get_phrase('remove_filter'); ?>
                            </a>
                        <?php
            }
            ?>
                    </div>
                </div>
            </div>
        </form>
        <?php
//echo $section_id;
if($date!='' && $month!='' && $year!='' && $section_id!=''){

?>
        <center>
            <div class="row">
                <div class="col-sm-12 text-center ">
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
         <form method="post" id="save_attendance" action="<?php echo base_url();?>attendance/apply_attendence/<?php echo $date.'/'.$month.'/'.$year;?>">
        <div class="row">
            <div class="col-md-12" style="overflow-y:scroll;">
               
                    <table id="fixTable" class="table table-responsive table-condensed" >
                        <thead>
                            <tr>
                                
                                <td style="width:7%">
                                    <?php echo get_phrase('Roll No.');?>
                                </td>
                                <td style="width:10%">
                                    <?php echo get_phrase('student_name');?>
                                </td>
                                <!--<td>-->
                                    <?php 
                                    $dateValu = $year .'-'.$month.'-'.$date;
                                    $q1 ="SELECT * FROM ".get_school_db().". `subject` LEFT JOIN  ".get_school_db().". subject_section ON subject.subject_id = subject_section.subject_id
                                            LEFT JOIN ".get_school_db().". class_routine ON subject.subject_id = class_routine.subject_id
                                            LEFT JOIN ".get_school_db().".class_routine_settings ON subject.subject_id = class_routine_settings.section_id
                                            LEFT JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                                            
                                            WHERE class_section.section_id ='".$section_id."' GROUP BY subject.name";
            	                $attend1=$this->db->query($q1)->result_array();
            	                foreach($attend1 as $resultSubject)
                                    {  ?>
                                     <td><?php echo $resultSubject['name']; ?></td>   
                                   <?php  } ?>
                                <!--</td>-->
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
            //STUDENTS ATTENDANCE
            $attendance="select a.date as date,s.student_id as student_id,a.status as status FROM ".get_school_db().".attendance a
INNER JOIN ".get_school_db().".student s 
ON a.student_id=s.student_id
WHERE  a.school_id = ".$_SESSION['school_id']." AND s.section_id=".$section_id." and s.student_status IN (".student_query_status().")";
            $attend=$this->db->query($attendance)->result_array();
            /*echo "<pre>";
            print_r($attend);*/
            $attend_array=array();
            foreach($attend as $res)
            {
                //echo "status".$res['status'];
                $stud_id=$res['student_id'];
                $date2=$res['date'];
                $status=$res['status'];
                $attend_array[$stud_id][$date2]=$status;
            }
            /*echo "<pre>";
            print_r($page_data);*/
            $query="select * FROM ".get_school_db().".student
            WHERE section_id=$section_id AND school_id=".$_SESSION['school_id']." AND student_status IN (".student_query_status().") ORDER BY roll desc";
            $students=$this->db->query($query)->result_array();
            
            
            //echo $this->db->last_query();
            $j=0;  
		
            foreach($students as $row)
				
            { //print_r($row['student_id']);  
                ?>
                            <tr class="gradeA attendence">
                               
                                <td>
                                    <?php echo $row['roll'];?>
                                </td>
                                <td>
                                    <?php echo $row['name'];?>
                                </td>
                                <!--<td>-->
                                    <?php
                                    $q5 ="SELECT * FROM ".get_school_db().". `subject` LEFT JOIN  ".get_school_db().". subject_section ON subject.subject_id = subject_section.subject_id
                                            LEFT JOIN ".get_school_db().". class_routine ON subject.subject_id = class_routine.subject_id
                                            LEFT JOIN ".get_school_db().".class_routine_settings ON subject.subject_id = class_routine_settings.section_id
                                            LEFT JOIN ".get_school_db().".class_section ON class_section.section_id = subject_section.section_id 
                                            WHERE class_section.section_id ='".$section_id."' GROUP BY subject.name";
            	                $attend5=$this->db->query($q5)->result_array();
            	                foreach($attend5 as $result5)
                                    {
                                    // print_r($result5['name']);   
                                    
                                     $q2 ="SELECT * FROM ".get_school_db().". `virtual_class_student`
                                            WHERE student_id ='".$row['student_id']."'  AND Current_Day ='".$dateValu."'";
                                     $attend2=$this->db->query($q2)->result_array();
                                     if(count($attend2) > 0){
                                         echo '<td>';
            	                foreach($attend2 as $row)
				                        { ?>
				                            <div>
				                            <span><?php echo $row['vc_start_time']; ?></span><br>
				                            <span><?php echo $row['vc_end_time']; ?></span>
				                            </div>
				                            <?php
				                          }
                                       echo '</td>';  
                                     }else{
                                         echo '<td>';
                                         
                                         echo '</td>'; 
                                     }
            }

            ?>
                            </tr>
                            <?php 
                    $j++;
                        }?>
                            
                        </tbody>
                    </table>
              
            </div>
            
            
            
<!--<div class="col-sm-12 mgt10">-->
	
<!--	 <input name="submit1" type="submit" class="btn btn-default" value="<?php echo get_phrase('save_attendence'); ?>" style="float:middle; margin:0px 10px;padding:6px 50px;">-->
	
	
<!--</div>  -->
          
        </div> 
         </form>
         
         
         
         
  <div class="row mgt10">    
         
 
   </div>       
       
  <br>
<br>
 <!--<div class="col-sm-12">-->
 	
 <!--		<h3><?php echo get_phrase('mark_bulk_absent'); ?></h3>-->
	<!--  	<p><i class="fa fa-info-circle" aria-hidden="true"></i><?php echo get_phrase('all_students_who_didn_not_mark_their_atendance_through_automated_system'); ?>,-->
	<!--	<?php echo get_phrase('can_be_marked_absent_by_mark_absent_button_ below_for_selected_date'); ?>.</p>-->
	<!--  </div>-->
 	
 	
 </div>
 
 
<!-- <div class="col-sm-12">-->
 
 
 
<!--<div class="thisrow mgt35 pd10">-->



	  


<!--<div class="row">-->
	
	
		
	
	
<!--	<div class="col-sm-4">-->
<!--<input type="text" class="form-control datepicker" data-format="dd/mm/yyyy" id="date_val" value="<?php echo date('d/m/Y'); ?>">-->
<!--	</div>-->
<!--	<div class="col-sm-4">-->
<!--<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"><?php echo get_phrase('mark_bulk_absent'); ?></button>-->
<!--</div>-->

<!--	</div>-->
	
<!--</div>  </div>    -->
<?php } ?>
		
        
        
        <script>
        $(document).ready(function() {        	
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
        </script>
        
        <script>
        	$('#fixTable').on('focus', 'td', function() {
    		$this = $(this);
    		$this.closest('#fixTable').scrollLeft($this.index() * $this.outerWidth());
			}).on('keydown', 'td', function(e) {
			}).find('td #selecctall').focus();
        </script>
        
        <style>
        .loader {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #63b7e7;
            /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            margin-right: auto;
            margin-left: auto;
        }
        
        .loader_small {
            border: 7px solid #f3f3f3;
            border-top: 7px solid #63b7e7;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin-right: auto;
            margin-left: auto;
        }
        
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        </style>
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
		