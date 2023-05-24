<style>
.modal-content{position:relative;top:500px}.table-condensed>tbody>tr>td,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>td,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>thead>tr>th{padding:3px}.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:10px;border-bottom:1px solid #ccc;border-left:1px solid #ccc;text-align:center}.form-group{margin-bottom:0}.hw{height:30px;width:30px;border:1px solid #ccc}.red{background-color:#f2dede}.green{background-color:#dff0d8}.blue{background-color:#d9edf7}.grey{background-color:#efefef}.orange{background-color:#fcf8e3}.ml{padding-left:5px;padding-right:23px;}.bl{border:1px solid #000!important}
button[disabled], html input[disabled] {
    cursor: no-drop;
    background: #30364173 !important;
}
</style>
<?php
if (right_granted('managestudentattendance_manage'))
{
    $this->session->flashdata('flash_message');
    if($this->session->flashdata('flash_message')){
?>
<div class="<?php if($this->session->flashdata('flash_message')=='value_missing'){echo 'alert alert-danger';  }else{echo 'alert  alert-success';} ?>" id="flash_danger">
    <center>
        <?php echo $this->session->flashdata('flash_message'); ?>
    </center>
</div>
<?php } ?>
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
					<?php echo get_phrase('manage_attendance'); ?>
                </h3>
            </div>
        </div>
        <form id="student_attendance" method="post" action="<?php echo base_url();?>attendance/manage_attendance" class="form validate">
            <div class="">
                <div class="row filterContainer">
                    <div class="col-lg-6 col-md-6 col-sm-6" data-step="1" data-position="top" data-intro="Step 1: select date">
                        <input class="form-control datepicker" type="text" name="date" id="date" value="<?php echo $date."/".$month."/".$year; ?>" style="background-color:#FFF !important;" required data-format="dd/mm/yyyy" >
                        <div id="d3"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6" data-step="2" data-position="top" data-intro="Step 2: class - section">
                        <select id="section_id" class="selectpicker form-control" name="section_id">
                            <?php echo section_selector($section_id);?>
                        </select>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3" data-step="3" data-position="top" data-intro="press this button get attendance sheet">
                        <input type="submit" value="<?php echo get_phrase('filter');?>" class="modal_save_btn">
                        <?php if($filter > 0){?>
                        <a href="<?php echo base_url(); ?>attendance/manage_attendance/<?php echo date('d/m/Y');?>" class="modal_cancel_btn" id="btn_remove">
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
                <div class="text-center">
                    <?php
                    $full_date  =   $year.'-'.$month.'-'.$date;
                    $timestamp = strtotime($full_date);
                    $day = strtolower(date('l', $timestamp));
                    $details=section_hierarchy($section_id); 
                    $s=mktime(0,0,0,$month, $date, $year);
                    $d2=date('d-M-Y',$s);
                    $date_curr= date("t",$s);
                 ?>
                    <ul class="breadcrumb" style="color: #000000;font-weight: bold;">
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
         <form method="post" id="attendance_form" action="<?php echo base_url();?>attendance/apply_attendence/<?php echo $date.'/'.$month.'/'.$year;?>">
            <div class="row">
                <div style="overflow-y:scroll;" class="w-100">
                    <table id="fixTable" class="table table-striped table-condensed" >
                        <thead>
                            <tr>                
                                <td>
                                    <?php echo get_phrase('Roll No.');?>
                                </td>
                                <td>
                                    <?php echo get_phrase('student_name');?>
                                </td>
                                <?php 
                                    if($date=='' && $month=='' && $year==''){ 
                                    	$date_curr= date("t");
                                        $month=date('m');
                                        $year=date('Y');
                                    }                
                                    
                                    $custom_css=array(1=>'current-day',2=>'holiday');
                                    
                                    $current_date=date('Y-m-d');              
                                    for($i=1;$i<=$date_curr;$i++){ 
                                    $current="";
                                    $current1=""; 
                                    $s=mktime(0,0,0,$month, $i, $year);
                                    $date2=date('Y-m-d',$s);
                                    if($date2==$current_date)
                                    {
                                    $current=$custom_css[1];
                                    }  
                                ?>
                                <?php 
                                    $statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
                                    $date1 = "$year-$month-$i";
                                    $date_num = date('N',strtotime($date1));
                                    $statuslist_css="";
                                    //sat and sunday
                                    $s=mktime(0,0,0,$month, $i, $year);
                                    $today_date= date('Y-m-d',$s);
                                    $dw = date( "D", strtotime($today_date));
                                    
                                    
                                    if($dw=="Sat" or $dw=="Sun"){
                                        $statuslist_css=$statuslist[4]; 
                                    }
                                    else
                                    {
                                        $qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ")->result_array();
                                        
                                        if(count($qurrr)>0){
                                            $current1=$custom_css[2];
                                        }   
                                    }
                                    
                                    echo "<td class='".$current." ".$current1." ".$statuslist_css."'>";
                                    echo $i;
                                    echo "<br/>";
                                    echo $dw;
                                    
                                     
                                         if($date==$i){
                                    echo "<input type='checkbox' id='selecctall' />";   
                                    }
                                    ?>
                                </td>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            //STUDENTS ATTENDANCE
                            $attendance="select a.date as date,s.student_id as student_id,a.status as status FROM ".get_school_db().".attendance a INNER JOIN ".get_school_db().".student s  ON a.student_id=s.student_id WHERE  a.school_id = ".$_SESSION['school_id']." AND s.section_id=".$section_id." and s.student_status IN (".student_query_status().")";
                            
                            $attend=$this->db->query($attendance)->result_array();
                            //print_r($attend);
                            $attend_array=array();
                            foreach($attend as $res)
                            {
                                //echo "status".$res['status'];
                                $stud_id=$res['student_id'];
                                $date2=$res['date'];
                                $status=$res['status'];
                                $attend_array[$stud_id][$date2]=$status;
                            }
                            $query="select * FROM ".get_school_db().".student WHERE section_id=$section_id AND school_id=".$_SESSION['school_id']." AND student_status IN (".student_query_status().") ORDER BY roll desc";
                            $students=$this->db->query($query)->result_array();
                            $j=0;  
                            $button_check_out = false;
                            foreach($students as $row)
                            {
                                ?>
                                <tr class="gradeA attendence">
                                   
                                    <td>
                                        <?php echo $row['roll'];?>
                                    </td>
                                    <td>
                                        <?php echo $row['name'];?>
                                    </td>
                                    <?php 
                                    for($i=1;$i<=$date_curr;$i++){ 
                                        if($i<10){
                                            $date1 =   $year.'-'.$month.'-'."0".$i;
                                        }else{
                                            $date1 =   $year.'-'.$month.'-'.$i; 
                                        }
                                        $student_id=$row['student_id'];
                                        if(isset($attend_array[$student_id][$date1])){
                                            $status=$attend_array[$student_id][$date1];
                                        }else{
                                            $status=4;
                                        }
                                        
                                        ?>
                                        <?php
                                        $current_date1=date('d'); 
                                        $date_num = date('N',strtotime($date1));
                                        $str_checkbox="";
                                        $str_check_out_time="";
                                        $current="";
                                        $current1="";
                                        $statuslist_css="";
                                        $statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
                                        
                                        $qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ")->result_array();
                                      
                                            if(count($qurrr)>0){
                                                $current1=$custom_css[2];
                                                    }   
                                        elseif($date_num==6 or $date_num==7){
                                        $statuslist_css=$statuslist[4]; 
                                            
                                        }   
                                        
                                        
                                        $s=mktime(0,0,0,$month, $i, $year);
                                        $date2=date('Y-m-d',$s);
                                        
                                        
                                        if($date2==$current_date){
                                        $current=$custom_css[1];
                                        
                                        }
                                        if($i==$date){
                                            
                                        $str_checkbox.=' class="form-control checkbox1" value="1"';
                                      
                                        $str_checkbox.= ' name="status-'.$j.'" id="status_absent"';
                                        
                                        }
                                        else
                                        {
                                            $str_checkbox="disabled";
                                             
                                        }
                                        
                                        
                                        if($status == 1){$str_checkbox .= ' checked ';$statuslist_css=$statuslist[1];   $button_check_out = true;}
                                        
                                        if($status == 2)
                                        {
                                            $statuslist_css=$statuslist[2];
                                             
                                        }
                                        if($status==3)
                                        {
                                            $statuslist_css=$statuslist[3]; 
                                        }
                                        ?>
                                        <td tabindex="1" class="<?php echo $current.' '.$current1.' '.$statuslist_css;?>">
                                            <div class="form-group">
                                                <?php
                                                if($status!=3){
                                                ?>  
                                                    <input type="checkbox" <?php echo $str_checkbox;?> style="width:15px !important;"/>
                                                    <?php 
                                                    
                                                }elseif($status==3)
                                                {
                                                echo "L";
                                                $statuslist_css=$statuslist[3]; 
                                                }
                                                ?>
                                                    <?php if($i==$date){?>
                                                    <input type="hidden" name="student_id[]" value="<?php echo $row['student_id'];?>" />
                                                   
                                                    <input type="hidden" name="section_id" value="<?php echo $section_id;?>" />
                                                    <?php }?>
                                            </div>
                                        </td>
                                        <?php  } ?>
                                </tr>
                                <?php 
                                $j++;
                            }
                         
                            ?>
                            
                            
                            
                        </tbody>
                    </table>
                </div>
                
                <!--<input id="send_sms" type="checkbox" name="send_sms" value="1">-->
                <div <?= check_sms_preference(5,"style","sms") ?>>
                    <label for="send_sms">SEND SMS</label>
                    <input type="checkbox" id="send_sms" name="send_sms" value="1">
                </div>
                
  
                
            
                <div class="col-sm-12 mgt10 text-right">
                	 <input name="submit1" type="button" id="save_attendance" class="modal_save_btn" value="<?php echo get_phrase('save_attendence'); ?>" onclick='mark_checked_value()' >
                	    <?php      
                                   $current_date = date("Y-m-d");
                                   $data = $this->db->query("SELECT attendance_timing.check_in,attendance_timing.check_out  FROM  ".get_school_db().".student
                                   LEFT JOIN ".get_school_db().".attendance ON attendance.student_id = student.student_id
                                   LEFT JOIN ".get_school_db().".attendance_timing ON attendance_timing.attendance_id = attendance.attendance_id
                                   WHERE section_id = $section_id And attendance.date= '$current_date'
				                   AND attendance.school_id=".$_SESSION['school_id']." ")->row();
				                     
                        ?>
                    <input name="submit2" id="check_out_attendance" type="button" class="modal_save_btn" 
                    <?php
                    if(empty($data->check_in))
                    {
                        echo "disabled";
                    }
                    if(!empty($data->check_in) && !empty($data->check_out))
                    {
                    echo "style='display:none;'";
                    } ?> value="<?php echo get_phrase('Check_out_attendence'); ?>" onclick='attendance_mark_check_out(<?php echo $section_id ?>)' date-section-id="<?php echo $section_id ?>" > 

                	        
                </div>  
            </div> 
         </form>
         
         
         
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
        
        <div class="col-sm-12">
        
            <h3><?php echo get_phrase('mark_bulk_absent'); ?></h3>
            <p><i class="fa fa-info-circle" aria-hidden="true"></i><?php echo get_phrase('all_students_who_didn_not_mark_their_atendance_through_automated_system'); ?>,
            <?php echo get_phrase('can_be_marked_absent_by_mark_absent_button_ below_for_selected_date'); ?>.</p>
        </div>
 	
 	
 </div>
 
 
        <div class="col-sm-12">
            <div class="thisrow mgt35 pd10">
                <div class="row">
                    <div class="col-sm-4">
                        <input type="text" class="form-control datepicker" data-format="dd/mm/yyyy" id="date_val" value="<?php echo date('d/m/Y'); ?>">
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"><?php echo get_phrase('mark_bulk_absent'); ?></button>
                    </div>
                </div>
            </div>
        </div>    
<?php } ?>
		
        
        
        <script>
        $(document).ready(function(){
            $(".page-container").addClass("sidebar-collapsed");
        });
        $(document).ready(function() {
            $('#save_attendance').on('click' , function(e){
        	    e.preventDefault();
        	    Swal.fire({
                  title: 'Are you sure?',
                  text: "You want to save the attendance ?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, save the attendance!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    $("#attendance_form").submit();
                  }
                })
                
        	});
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
         
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });
        <?php if($section_id=="")
        {?>
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
        
        ///Check-out  Button Function// Multiple Case (bulk)
    //     function mark_checked_value(){
    //           var checked_id  = $('.checkbox1').val();
    //               console.log(checked_id); exit;
    //           if ($('.checkbox1').is(":checked"))
    //                 {
    //                  var checked_id = $('.checkbox1').val();
    //                   console.log(checked_id);
    //                 }
                  
                    
    //         $.ajax({
    // 			type: "POST",
    // 			data:{checked_out_std_id:checked_out_std_id},
    // 			url: '<?php //echo base_url()."attendance/mark_student_check_out";?>',
    // 			dataType:"html",
    // 			success: function(response){
    			  
    // 			}
    // 		});
    		
    	
               
    //         debugger;
    //     }



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
            <input name='hiddeninputname[]' id='checked_out_id' type='hidden' value= ''/>;
        </div>
      </div>
      
    </div>
  </div>
          <script>
                function attendance_mark_check_out(data){
                     var hidden_data_check_out_id  = data;
                     Swal.fire({
                      title: 'Are you sure?',
                      text: "You want to check-out the attendance!",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, Check-out it!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                                $.ajax({
                			type: "POST",
                			data:{'hidden_data_check_out_id':hidden_data_check_out_id},
                			url: '<?php echo base_url()."attendance/mark_student_check_out";?>',
                			dataType:"html",
                			success: function(response){
                			    location.reload();
                			}
                		});
                        Swal.fire(
                          'Check-out Attendance!',
                          'Your Attendance has been Check-out.',
                          'success'
                        )
                      }
                    })
               
                  
                }
         </script>