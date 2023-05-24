
<script>
	$(document).ready(function() {
		$("#fixTable").tableHeadFixer({"left" : 2}); 
	});
</script>
<?php
if (right_granted('staffattendance_manage'))
{
    $this->session->flashdata('flash_message');
    if($this->session->flashdata('flash_message')){
?>
    <div class="<?php if($this->session->flashdata('flash_message')=='value_missing'){ 
        echo 'alert alert-danger'; 
    }else{
        echo 'alert  alert-success';
    } ?>" id="flash_danger">
        <center>
            <?php echo $this->session->flashdata('flash_message'); ?>
        </center>
    </div>
    <?php 
    }
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
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                 <?php echo get_phrase('Check_out_staff_attendance');?>
            </h3>
        </div>
    </div>
        <div class="row filterContainer"  data-step="1" data-position='top' data-intro="Please select a date and press filter button to get record">
            <form  method="post" action="<?php echo base_url();?>attendance_staff/check_out_staff_attendance" class="form validate">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input class="form-control datepicker" type="text" required name="date" id="date" value="<?php echo $date."/".$month."/".$year; ?>" style="background-color:#FFF !important;" data-format="dd/mm/yyyy" />
                    </div>
              
                    <div class="col-lg-3 col-md-3 col-sm-3 " style="display:flex;">
                        <input type="submit" value="<?php echo get_phrase('filter');?>" class="btn btn-info" id="btn_submit">
                        <?php
				 		if($filter > 0)
						{?> 
                            <a href="<?php echo base_url(); ?>attendance_staff/check_out_staff_attendance/<?php echo date('d/m/Y');?>" class="btn btn-danger" id="btn_remove" style="margin: 0px 0px 0px 5px !important;">
                                <i class="fa fa-remove"></i>
                                 <?php echo get_phrase('remove_filter');?>
                                </a>
                        <?php } ?>
                    </div>
               <div class="col-lg-6 col-md-6 col-sm-3">
              <?php
                    $full_date	=	$year.'-'.$month.'-'.$date;
                    $timestamp = strtotime($full_date);
                    $day = strtolower(date('l', $timestamp));
                ?>
                  <span class="myttl" style="padding-top:10px; display:block;">
                <?php echo ucwords($day);?>
                          
                <?php
                $s=mktime(0,0,0,$month, $date, $year);
                
                $d2=date('d-M-Y',$s);
                 ?>
                <?php echo '<strong>'.$d2.'</strong>';
                //echo $date.'-'.$month.'-'.$year;?>
                </span>
              
            </div>

            </div>
            </form>
        </div>
        <?php
        	$current_date=date('Y-m-d'); 
			$query="SELECT attendance_staff.attend_staff_id,attendance_staff.status,attendance_staff.date,attendance_staff_timing.check_in,attendance_staff_timing.check_out,
		          	s.name as name,d.title as designation,s.staff_id as staff_id FROM ".get_school_db().".staff s
		   	        LEFT JOIN ".get_school_db().".designation d ON s.designation_id=d.designation_id
			        LEFT JOIN ".get_school_db()." .attendance_staff ON attendance_staff.staff_id = s.staff_id
			        LEFT JOIN ".get_school_db()." .attendance_staff_timing ON attendance_staff_timing.attend_staff_id = attendance_staff.attend_staff_id
			        WHERE s.school_id=".$_SESSION['school_id']."
			        AND attendance_staff.date= '$current_date'
			        order by s.name asc   ";
			$students=$this->db->query($query)->result_array(); 
			foreach($students as $row){
			    $staff_id= $row['attend_staff_id'];
			}
		
			?>
			
        <?php if($date!='' && $month!='' && $year!='')
		{
			$date_curr= date("t",$s);
    	?>
    	<!--<div class="row">-->
            <form method="post" id="attendance_form" action="" style="overflow-y: scroll;">
            <div class="col-md-12" style="overflow-y:scroll;" ></br>
    	        <!--<h2 data-step="2" data-position='top' data-intro="Monthly staff attendance sheet">Individual Check Out Sheet</h2>-->
                <table id="fixTable"  class="table  table-responsive table-condensed" >
                    <thead>
                        <tr style="background-color: #f1f1f1;" >
                            <td>
                                <?php echo get_phrase('#');?>
                            </td>
                             <td style="min-width: 220px !important;">
                                <?php echo get_phrase('staff_name');?>
                            </td>
                            
                             <td>
                                <?php echo get_phrase('check_in_time');?>
                            </td>
                            
                             <td>
                            <input type='checkbox' name="selct_check_box[]" value="<?php echo $row['attend_staff_id'] ?>" id="selecctall" class="selct_check_box"/><?php echo get_phrase('check out select box');?>
                          
                            </td>
                            
                            
                        </tr>
                    </thead>
                    <tbody>
                            <?php 
			$attendance="select a.date as date,s.staff_id as staff_id,a.status as status,s.name as staff_name FROM ".get_school_db().".attendance_staff a
			INNER JOIN ".get_school_db().".staff s ON a.staff_id=s.staff_id WHERE  a.school_id = ".$_SESSION['school_id']."" ;

			$attend=$this->db->query($attendance)->result_array();
			$attend_array=array();
			foreach($attend as $res)
			{
				$staff_id=$res['staff_id'];
				
				$date2=$res['date'];
				$status=$res['status'];
				$attend_array[$staff_id][$date2]=$status;
			}
			
		
			
	//	print_r($students);
			$j=0;	
			$count=1;
			 if(!$students ){ 
                           ?> <div class="text-center">
                            <h3 class="text-center"> Not Staff Check-out Mark Attendance</h3></div>
                            <?php } 
			 
			foreach($students as $row)
			{
		?>
                <tr class="gradeA attendence">
                    <td>
                        <?php echo $count;?>
                    </td>
                    <td>
                        <?php echo $row['name'].' <br>('.$row['designation'].')'; ?>
                    </td>
                    
                    <td>
                        <?php if($row['status'] == 1){ ?>
                                <?php if(empty($row['check_in'])){ ?>
                                 <?php echo "<strong>".get_phrase("check_in")."</strong>:"?> <?php echo '00:00:00';?>
                                <?php } else { ?>
                                 <?php echo $row['check_in'] ?>
                                <?php }?>
                       <?php  }
                       else if($row['status'] == 2) {?>
                         <span style="color: red;"><?php echo get_phrase("absent");?>  </span> 	
                       <?php } else if ($row['status'] == 3) {?>
                        <span style="color: blue;"><?php echo get_phrase("leave");?> </span>
                       <?php }?>
                       
                    </td>
                    
                    <td> 
                    <?php if($row['status'] == 1){?>
                    
                    <?php if(!empty($row['check_in']) && empty($row['check_out'])){?>
                    <input type="checkbox" class="selct_check_box"  id="ve" name="selct_check_box[]" value="<?php echo $row['attend_staff_id'] ?>" ></td>
                    
                    <?php }else if(!empty($row['check_out'])){?>
                    <?php echo $row['check_out'] ?>
                    <?php }?>
                    
                    
                    <?php }?>
                    
    <?php 
       
        ?>
        </tr>
        <?php 
        $j++;
        $count++;
        }?>
 

        </tbody>
    </table>
</div>
            <div class="form-group" style="margin-left: 16px;">
                <div <?= check_sms_preference(14,"style","sms") ?>>
                    <label for="send_sms">SEND SMS</label>
                    <input type="checkbox" id="send_sms" name="send_sms" value="1">
                </div>    
            </div>
            <div class="col-sm-12 mt-3 text-right">
                <input name="submit1" id="save_attendance" data-step="3" data-position='top' date-section-id="<?php echo $staff_id ?>"   data-intro="Press save button to save attendance for today" type="submit" class="modal_save_btn" value=" <?php echo get_phrase('check_out_attendance');?>" >	
            </div>
        </form>
        <!--</div>-->
        
            
         
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

  <?php } } ?>

    <!--<br>-->
    <!--<br>-->
    <!--<div class="col-sm-12" data-step="5" data-position='top' data-intro="Mark absent in bulk">-->
    <!--    <h3> <?php echo get_phrase('mark_bulk_absend');?></h3>-->
    <!--    <p><i class="fa fa-info-circle" aria-hidden="true"></i>-->
    <!--    <?php echo get_phrase('all_staff_who_did not_mark_their_atendance_through_automated_system');?>-->
    <!--    , <?php echo get_phrase('can_be_marked_absent_by_mark_absent_button_below_for_selected_date');?>.-->
    <!--    </p>-->
    <!--</div>-->
    <!--<div class="thisrow mgt35 pd10">-->
    <!--    <div class="row ">-->
    <!--      <div class="col-sm-4"><input data-format="dd/mm/yyyy" id="date_val" type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control datepicker"></div>-->
    <!--      <div class="col-sm-4"><button type="button" class="btn btn-info btn-lg nvd" data-toggle="modal" data-target="#myModal"> <?php echo get_phrase('mark_absent');?></button></div>-->
    <!--    </div>-->
    <!--</div>-->


  <!--<div class="col-sm-12">-->
  <!-- Modal -->
  <!--<div class="modal fade" id="myModal" role="dialog">-->
  <!--  <div class="modal-dialog">-->
    
      <!-- Modal content-->
  <!--    <div class="modal-content">-->
  <!--      <div class="modal-header">-->
  <!--        <button type="button" class="close" data-dismiss="modal">&times;</button>-->
  <!--        <h4 class="modal-title"> <?php echo get_phrase('confirm');?></h4>-->
  <!--      </div>-->
  <!--      <div class="modal-body">-->
  <!--        <p> <?php echo get_phrase('are_you_sure');?> </p>-->
  <!--        <a href="" id="btn_attan" class="btn btn-primary" data-dismiss="modal"> <?php echo get_phrase('yes');?>-->
  <!--        </a>-->
  <!--        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('no');?></button>-->
  <!--      </div>-->
  <!--    </div>-->
      
  <!--  </div>-->
  <!--</div>-->
  <!--</div>-->
  </div>
  
  <style>
	   /*.modal-content{position:relative;top:450px;}*/
  </style>     



        <script>
        $(document).ready(function() {
          $('#btn_attan').click(function(){
            var date=$("#date_val").val();
            $.ajax({
              type: 'POST',
              data: {date:date},
              url: "<?php echo base_url();?>attendance/mark_absent_teacher/",
              dataType: "html",
              success: function(response) {
                location.reload();
              }
            });
          });

       

            //////////////////////////////////////////////////////////////

      
        </script>
        <script>
        	$('#fixTable').on('focus', 'td', function() {
            $this = $(this);
            $this.closest('#fixTable').scrollLeft($this.index() * $this.outerWidth());
          }).on('keydown', 'td', function(e) {

          }).find('td #selecctall').focus();
          $(document).ready(function(){
              $(".page-container").addClass("sidebar-collapsed");
          });
          
          
          
        $('#attendance_form').on('submit' , function(e){
      
    	    e.preventDefault();
    	    Swal.fire({
              title: 'Are you sure?',
              text: "You want to check-out the attendance ?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, check out the attendance!'
            }).then((result) => {
              if (result.isConfirmed) {
                    $.ajax({
            			type: "POST",
                		 data:new FormData(this),
                        contentType: false,  
                        cache: false,  
                        processData:false,
            			url: '<?php echo base_url()."attendance/specific_staff_check_out";?>',
            			dataType:"html",
            			success: function(response){
            			    location.reload();
            			}
            		});
                  
               // $("#attendance_form").submit();
              }
            })
    	});
        </script>
        <script>
         $("#selecctall").change(function() {
            $(".selct_check_box").prop('checked', $(this).prop("checked"));
          });
        </script>
