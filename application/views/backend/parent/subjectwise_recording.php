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
					<?php echo get_phrase('subjectwise_attendance'); ?>
                </h3>
            </div>
        </div>
        <form method="post" action="<?php echo base_url();?>c_student/subject_recording" class="form validate">
            <div class=" thisrow pd10 ">
                
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 mgt10">
                         <span class="text-center" style="font-size:0.8em;color:red;font-weight:bold:padding:5px" id="subjectwiserecordingmsgspan"></span>
                    </div>
                </div>
                
                <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input class="form-control datepicker1" type="text" name="date" id="date" value="<?php echo $date."/".$month."/".$year; ?>" style="background-color:#FFF !important;" readonly="readonly" required data-format="dd/mm/yyyy" >
                        <div id="d3"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <select id="section_id" class="selectpicker form-control" name="section_id">
                            <?php echo section_selector($section_id);?>
                        </select>
                    </div>
                </div>
                
                
                <div class="row">
                                    

                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                         <label>Date</label>
                                         <input class="form-control datepicker1" type="text" name="date_recording" id="date_recording" style="background-color:#FFF !important;cursor: pointer !important;" value="<?php echo $date ?>" readonly="readonly" required data-format="dd/mm/yyyy" >
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
                                    
                                    <div class="col-lg-4 col-md-4 col-sm-6">
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
                                    <div class="col-lg-4 col-md-4 col-sm-6" style="margin-top:1.4%">
                                         <input type="submit" id="subjectwiserecordingbtn" value="<?php echo get_phrase('filter_recordings');?>" class="btn btn-info">
                                    </div>
                                 
                                  
                </div>
                
                
                
                

            </div>
        </form>
        <?php

if($date !='' && $student_id !='' && $section_id !='' && $subject_id !=''){

?>

		<script>
			$(document).ready(function() {
				$("#fixTable").tableHeadFixer({"left" : 2});
				
			});
		</script>
        
        
         
        <div class="row">
            <div class="col-md-12" style="overflow-y:scroll;">
               
                    <table id="fixTable" class="table table-responsive table-condensed" >
                        <thead>
                            <tr class="table_header_attendance">
                                
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('Meeting ID');?>
                                </th>
                                <th class="table_header_rol_number">
                                    <?php echo get_phrase('View Recording');?>
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody>

                            <tr class="table_header_attendance">
                                
                                <td>
                                    Shoaib
                                </td>
                                <td>
                                    View
                                </td>
                            </tr>
                            
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
		