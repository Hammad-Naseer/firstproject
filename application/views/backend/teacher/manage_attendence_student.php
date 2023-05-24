<?php
    if($this->session->flashdata('club_updated')){
    	echo '<div align="center">
    	<div class="alert alert-success alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        '.$this->session->flashdata('club_updated').'
    	</div> 
    	</div>';
    }
?>
<script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    });
</script>

<?php
    $login_detail_id = $_SESSION['login_detail_id'];
    if (!$month_filter)
    {
        $date = get_phrase(date('d'));
        $month = get_phrase(date('m'));
        $year = get_phrase(date('Y'));
    }
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('attendance'); ?>
        </h3>
    </div>
</div>

<form method="post" action="<?php echo base_url();?>teacher/manage_attendance_student" class="form"   style="overflow-x:auto !important;">
    <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filter and press Filter button to get specific records">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
    		<select id="month_year" name="month_year" class="form-control" required>
                <?php
                $acadmic_year_start = $this->db->query("select start_date  from ".get_school_db().".acadmic_year  where  academic_year_id =".$_SESSION['academic_year_id']."  and school_id=".$_SESSION['school_id']."  ")->result_array();
                $selected = $month.'-'.$year;
                echo month_year_option($acadmic_year_start[0]['start_date'], date('Y-m-d'), $selected);
                ?>
            </select>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    		<input type="submit" value="<?php echo get_phrase('filter');?>" class="modal_save_btn" />
            <?php if($filter){?>
                <a href="<?php echo base_url();?>teacher/manage_attendance_student" class="modal_cancel_btn" >
                    <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                </a>
            <?php } ?>
    		</div>
		</div>
	</div>
</form>

<?php 
if($date!='' && $month!='' && $year!='')
{

?>
    <div class="col-md-12 student-panel">
        <div class="panel-group joined" id="accordion-test-2">
        <?php
        $toggle = false;
        $section_arr = $this->db->query("select cs.section_id, cs.title as section, c.name as class, d.title as department  
                from ".get_school_db().".class_section cs
                inner join ".get_school_db().".staff staff on staff.staff_id = cs.teacher_id
                inner join ".get_school_db().".class c on c.class_id = cs.class_id
                inner join ".get_school_db().".departments d on d.departments_id = c.departments_id
                where 
                staff.user_login_detail_id = $login_detail_id
                and cs.school_id=".$_SESSION['school_id']."
                order by d.title, c.name, cs.title
                ")->result_array();
        if(count($section_arr) ==0)
        {
			echo "<strong>Only class teacher can manage student attendance</strong>";
		}
        foreach ($section_arr as $row_outer) 
        {
            $section_id = $row_outer['section_id'];
        ?>
            <div class="panel panel-default" data-step="2" data-position='top' data-intro="Collapse this heading to view student attendance">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse<?php echo $row_outer['section_id'];?>">
                <?php
                 echo $row_outer['department'].'/'.$row_outer['class'].'/'.$row_outer['section'];?> (<b><?php echo $month.'-'.$year;?></b> )
                        <?php ?>
                    </a>
                </h4>
            </div>

            <div style="background:none;" id="collapse<?php echo $row_outer['section_id'];?>" class="panel-collapse collapse <?php if($toggle){echo 'in';$toggle=false;}?>">
                    
                <div class="panel-body">
                <?php
                if(date('m')==$month && date('Y')==$year )
                {
                ?>
                    <center>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <!-- <div class="icon"><i class="entypo-suitcase"></i></div> -->
                                <?php
                                    $full_date  =   $year.'-'.$month.'-'.$date;
                                    $timestamp = strtotime($full_date);
                                    $day = strtolower(date('l', $timestamp));
                                 ?>
                                <h2><?php echo get_phrase(ucwords($day));?></h2>
                                <p><?php echo get_phrase(convert_date($date.'-'.$month.'-'.$year));?></p>
                                
                            </div>
                        </div>
                    </center>
                <?php
                }?>
                <script>
                    $(document).ready(function() {
                        $("#fixTable").tableHeadFixer({"left" : 2}); 
                    });
                </script>  
                    <form method="post" id="attendance_form" action="<?php echo base_url();?>teacher/apply_attendence/<?php echo $section_id;?>" >
                        <div class=""  style="overflow-y:scroll;" class="w-100">
                        	<div class="col-md-12">
                                <table  class="table table-bordered" id="fixTable">
                        		<thead>
                                    <tr>
                                        <td  style="background:#f5f5f6; color:#000;"><?php echo get_phrase('roll_no.');?></td>
                                        <td  style="background:#f5f5f6; color:#000"><?php echo get_phrase('student_name');?></td>
                                        <?php
                                            $custom_css=array(1=>'current-day',2=>'holiday'); 
                                            $statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');     
                                            $current_date=date('Y-m-d');                                       
                                        $date_curr= date("t", strtotime("$year-$month-01"));
                                        $date_day= date("d");
                                         //$date_week=date( "w");
                                         //$month=date('m');
                                         //$year=date('Y');
                                        
                                        for($i=1;$i<=$date_curr;$i++)
                                        { 
                                            $current = "";  
                            				$current1 = ""; 
                                        ?>
                                        
                                            <?php 
                                            $date1 = "$year-$month-$i";
                                            $date_num = date('N',strtotime($date1));
                                            $s=mktime(0,0,0,$month, $i, $year);
                            				$date2=date('Y-m-d',$s);
                            				
                                            if($date2==$current_date)
                            				{
                            					$current = $custom_css[1];
                            				}
                            				$statuslist_css="";
                            				$dw = date( "D", strtotime($date2));
                                            if($dw=="Sat" or $dw=="Sun")
                                            {
                                            	$statuslist_css=$statuslist[4];
                                                //echo "style='background-color:green;'";	
                                            }
                                            else
                                            {
                                            	$qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ")->result_array();
                                            	//echo $this->db->last_query();
                                            	if(count($qurrr)>0)
                                                {
                                                	$current1=$custom_css[2];
                                            		
                                            		
                                            	}	
                                            }
                                            echo "<td class='".$current." ".$current1." ".$statuslist_css."'>";
                                            ?>
                            
                                            <?php 
                            
                                            // echo $this->db->last_query();	
                                            echo get_phrase($i);
                                            echo "<br>";
                                            echo get_phrase($dw);
                                            if($date_day==$i && date('m')==$month && date('Y')==$year )
                                            {
                                                echo "<input type='checkbox' id='selecctall' />";	
                                            }?></td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                         	        <?php 
                        			//STUDENTS ATTENDANCE
                        			$students	=	$this->db->query("select * 
                                            from ".get_school_db().".student
                                            where 
                                            section_id=$section_id
                                            and student_status in (".student_query_status().") 
                                            and school_id=".$_SESSION['school_id']."
                                            ")->result_array();
                        			//echo $this->db->last_query();
                                    $j=0;	
                        			foreach($students as $row)
                        			{
                        				?>
                        				<tr class="gradeA attendence">
                        					<td><?php echo $row['student_id'];?></td>
                        					<td><?php echo $row['name'];?></td>
                                            <?php 
                                            for($i=1;$i<=$date_curr;$i++)
                                            { 
                                                if($i<10)	
                                                    {$dayt	=	$year.'-'.$month.'-'."0".$i;}
                                                else	
                                                    {$dayt	=	$year.'-'.$month.'-'.$i;}
                                                                                    
                                            	$verify_data =	array('student_id' => $row['student_id'], 'date' => $dayt);
                                                $attendance = $this->db->get_where(get_school_db().'.attendance' , $verify_data);
                                                
                                                if($attendance->num_rows() != 0)
                                                {
                                                    $a=$attendance->row()->status;
                                                    $status= $a;
                                                }
                                                else
                                                {
                                                    $status=4;
                                                }
                                             
                                                ?>
                                                
                                                <?php 
                                                
                                                $str_checkbox="";
                                                $current="";
                                                $current1="";
                                                $statuslist_css="";
                                                $statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
                                                $date1 = "$year-$month-$i"; "$year-$month-$i";
                                                $date_num = date('N',strtotime($date1));
                                				$qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ")->result_array();
                                                if(count($qurrr)>0)
                                                {
                                                   $current1=$custom_css[2]; 
                                                }elseif($date_num==6 or $date_num==7)
                                                {
                                                    $statuslist_css=$statuslist[4];
                                                    //echo "style='background-color:green;'"; 
                                                }
                                                $s=mktime(0,0,0,$month, $i, $year);
                                                $date2=date('Y-m-d',$s);
                                                
                                                if($date2==$current_date){
                                                    $current=$custom_css[1];
                                                
                                                }
                                                if($i==$date){
                                                    $str_checkbox.=' class="form-control checkbox1" value="1"';
                                                    $str_checkbox.= ' name="status-'.$j.'" id="status_absent"';
                                                }else
                                                {
                                                	$str_checkbox="disabled";
                                                }
                                
                                                if($status == 1){$str_checkbox .= ' checked ';$statuslist_css=$statuslist[1];}
                                                if($status == 2)
                                                {
                                                	$statuslist_css=$statuslist[2];
                                                }
                                                if($status==3)
                                                {
                                                	$statuslist_css=$statuslist[3];	
                                                }
                                                    
                                                ?>
                                                <td class="<?php echo $current.' '.$current1.' '.$statuslist_css;?>">
                                                    <div class="form-group">
                                                       <?php if($status!=3){
                                                       	?>
                                                    <input type="checkbox" <?php echo $str_checkbox;?> style="width:15px !important;"/>
                                                    <?php 
                                                    }elseif($status==3)
                                                    {
                                                    echo get_phrase("L");
                                                    $statuslist_css=$statuslist[3];	
                                                    }
                                                     
                                                    	                     
                                                     ?>
                                                    <?php 
                                                    if($i==$date){?>
                                                    <input  type="hidden" name="student_id[]"  value="<?php echo $row['student_id'];?>"/>
                                                    <input type="hidden" name="class_id" value="<?php echo $section_id;?>"/>
                                                    <?php }?>
                                                     
                                                    </div> 
                                                </td>
                                                                
                                            				
                                			<?php
                                			}
                                			?>
                                        </tr>
                                            <?php 
                                            $j++;
                                    }
        
                                    if(date('m')==$month && date('Y')==$year )
                                    {
                                    ?>
                                   
                                    <?php
                                    }?>
                                </tbody>
                                                
                           	</table>
                        	</div>
                        	
                        	<div <?= check_sms_preference(5,"style","sms") ?>>
                        	    <label>Send SMS</label>
                        	    <input type="checkbox" name="send_sms" value="1" />
                        	</div>    
                        	<div class="col-sm-12 mgt10 text-right">
                        		<button name="submit1" type="submit" class="modal_save_btn">Save Attendence</button>
                        	</div>
                        	
                            <div class="row p-0"> 
                              <div class="col-sm-12 py-4">
                                
                                <div class="present-legend legend-attendance pull-left"> </div>
                            	<div class="pull-left"> <?php echo get_phrase('present');?></div>
                            	
                            	<div class="absent-legend legend-attendance pull-left"> </div>
                                <div class="pull-left"> <?php echo get_phrase('absent');?></div>
                            	 
                                <div class="leave-legend legend-attendance pull-left"> </div>
                                <div class="pull-left"><?php echo get_phrase('leave'); ?></div>
                                
                            	<div class="weekend-legend legend-attendance pull-left"> </div>
                            	<div class="pull-left"> <?php echo get_phrase('weekend');?></div>
                            
                            	<div class="holiday-legend legend-attendance pull-left"> </div>
                            	<div class="pull-left"> <?php echo get_phrase('holiday');?></div>
                             
                            	<div class="today-legend legend-attendance pull-left "></div>
                            	<div class="pull-left"> <?php echo get_phrase('today');?></div>
                            	
                              </div>   
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            </div>
        <?php 
        }//end foreach
        ?>
        </div>
    </div>
<?php        
}//end if
?>
<script>

    
</script>
<script>
    $(document).ready(function(){
	    $(document).on('change',"#selecctall",function(){
	        $(".checkbox1").prop('checked',$(this).prop("checked"));
        });
	});
	
	$(document).ready(function(){
        $(".page-container").addClass("sidebar-collapsed");
    });
	
// 	$('#save_attendance').on('click' , function(e){
// 	    e.preventDefault();
// 	    Swal.fire({
//           title: 'Are you sure?',
//           text: "You want to save the attendance ?",
//           icon: 'warning',
//           showCancelButton: true,
//           confirmButtonColor: '#3085d6',
//           cancelButtonColor: '#d33',
//           confirmButtonText: 'Yes, save the attendance!'
//         }).then((result) => {
//           if (result.isConfirmed) {
//             $("#attendance_form").submit();
//           }
//         })
// 	});
</script>

<script>
    jQuery(document).ready(function () 
    {
        jQuery('.dcs_list').on('change', function (){
            var id=this.id;
            var selected = jQuery('#'+ id +' :selected');
            var group = selected.parent().attr('label');
            jQuery(this).siblings('label').text(group);
        });
    });
</script>