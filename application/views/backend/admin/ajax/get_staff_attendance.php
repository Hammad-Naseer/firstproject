<?php 

$date_month=$_POST['month'];
$date_month = date("m", strtotime($date_month));
$date_year=$_POST['year'];
$staff_id=$_POST['staff_id'];
$q="SELECT attendance_staff.status,attendance_staff.date,attendance_staff_timing.check_in,attendance_staff_timing.check_out
    FROM ".get_school_db().".attendance_staff
    LEFT JOIN ".get_school_db()." .attendance_staff_timing ON attendance_staff_timing.attend_staff_id = attendance_staff.attend_staff_id
    WHERE staff_id=$staff_id
    AND month(date)=$date_month
    AND YEAR(date)=$date_year
    AND school_id=".$_SESSION['school_id']."";

$qur_red=$this->db->query($q)->result_array();
//print_r($qur_red);
$plan=array();
foreach($qur_red as $red){
	
$plan[$red['date']]=array('status'=>$red['status'],'check_in'=> $red['check_in'],'check_out'=> $red['check_out']);

}

?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered " id="table_export" aria-describedby="table_export_info">
  <thead>
<tr>
<th style="width:100px;"><?php echo get_phrase('date');?></th>
<th style="width:100px;"><?php echo get_phrase('day');?></th>
<th style="width:150px;"><?php echo get_phrase('attendance');?></th>
<th><?php echo get_phrase('comments');?></th>
</tr>
 </thead>
 
 
   <tbody>
<?php
$custom_css=array(1=>'current-day',2=>'holiday');      
$current_date=date('d-M-Y');                           
 
$d=cal_days_in_month(CAL_GREGORIAN,$date_month,$date_year);
for($i=01; $i<=$d; $i++){
	$current = "";
	$current1="";
$s=mktime(0,0,0,$date_month, $i, $date_year);
$today_date= date('Y-m-d',$s);
//to convert in days
$dw = date( "l", strtotime($today_date));
 $d1 = date( "d-M-Y", strtotime($today_date));
if($d1==$current_date)
{
	$current=$custom_css[1];
}  
 $date1 = "$date_year-$date_month-$i";

$q1="select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ";
	
$qurrr=$this->db->query($q1)->result_array();
$current1="";

if(count($qurrr)>0){
		$current1=$custom_css[2];
		//echo " style='background-color:#FFF;'";
			}	
 
//echo "<tr class='".$current." ".$current1."'";



$date_num = date('N',strtotime($date1));
$statuslist=array(1=>get_phrase('present'), 2=>get_phrase('absent'), 3=>get_phrase('leave'),4=>get_phrase('weekend'));
$statuslist_css="";
if($dw=='Saturday' or $dw=='Sunday'){
$statuslist_css=$statuslist[4];  
//echo " style='background-color:#EEE'";	
}

if(isset($plan[$today_date]['status']))
{
	$statuslist_css = $statuslist[$plan[$today_date]['status']]; 
	//$statuslist_css=$statuslist[4];
}
echo "<tr class='".$statuslist_css." ".$current." ".$current1."'";
echo ">";

//echo ">";
echo "<td style='        
   '><span style='!important;'>$d1</span> </td>";
   
   
   
   echo "<td style='        
   '>$dw</td>";


//print_r($plan[$today_date]);
	//foreach($plan[$today_date] as $std )
//{
	echo "<td>";
	if($plan[$today_date]['status']==1)
	{
		?> 
		
		<?php echo get_phrase("present");?>
		 <?php echo '<br>'; ?>
           <?php if(empty($plan[$today_date]['check_in']) && empty($plan[$today_date]['check_out'])){?>
               <?php echo "<strong>".get_phrase("check_in")."</strong>:"?> <?php echo '00:00:00';?>
               <?php echo '<br>';echo "<strong>".get_phrase("check_out")."</strong>:"?> <?php echo '00:00:00';?>
           <?php }
           else{ ?>
              <?php echo "<strong>".get_phrase("check_in")."</strong>:".$plan[$today_date]['check_in'];?>
    	      <?php echo '<br>';echo "<strong>".get_phrase("check_out")."</strong>:".$plan[$today_date]['check_out'];?>
           <?php     } ?>

		    
		<?php
	}
	elseif($plan[$today_date]['status']==2)
	{ ?>
		
	  <?php
		echo get_phrase("absent");?> 
			
		 
		   <?php
	}
	elseif($plan[$today_date]['status']==3)
	 {
	 ?>
	 	
	  
	    <?php	echo get_phrase("leave");?> 
	    	</span>
	    
	    <?php
	}
	echo "</td>";
	
	echo "<td>";
	echo $qurrr[0]['title'];
	if($plan[$today_date]['status']==3)
	{
            $leave_date = date('Y-m-d', strtotime($d1));
            $leave_qry = "select ls.*,lc.name as leave_categ from ".get_school_db().".leave_staff ls
            INNER JOIN ".get_school_db().".leave_category lc 
            ON lc.leave_category_id=ls.leave_category_id
                where ls.staff_id='$staff_id' 
                and (DATE('".$leave_date."') between ls.start_date and ls.end_date)
                and ls.school_id=".$_SESSION['school_id']." ";
                //exit;
           
            $leave_arr = $this->db->query($leave_qry)->result_array();
            
            echo "<strong>".get_phrase('category').": </strong>".$leave_arr[0]['leave_categ'];
           
			
						
			echo '<br>';
			
			echo "<strong>".get_phrase('status').": </strong>";
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
                echo '<span class="red">'.get_phrase('rejected').'</span>';
            }
			

            echo '<br>';
            echo "<strong>".get_phrase('start_date')."</strong>:";
            $start_date= $leave_arr[0]['start_date'];
             
            echo date( "d-M-Y", strtotime($start_date));
            echo '<br>';
            echo "<strong>".get_phrase('end_date')."</strong>:";
            $end_date= $leave_arr[0]['end_date'];
            echo date( "d-M-Y", strtotime($end_date));
            echo '<br>';
            echo "<strong>".get_phrase('process_date')."</strong>:";
                if($leave_arr[0]['process_date']!="")
                    { 
                    $process_date=$leave_arr[0]['process_date']; 
                    echo date( "d-M-Y", strtotime($process_date));
                    }
                    else
                    {
                    	 echo get_phrase("n")."/".get_phrase("a"); 
                   	};
			
			echo '<br>';
            echo "<strong>".get_phrase('description').": </strong>".$leave_arr[0]['reason']; 
            if ($leave_arr[0]['proof_doc']!="")
            { ?> 
                <a href=" <?php echo display_link($leave_arr[0]['proof_doc'],'leaves_staff'); ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a> 
            <?php 
            }  
            
        }  
        
   echo "</td>";       
//}
 

echo "</tr>"; 
}
 
 ?>  

 </tbody>
</table>

        
         
<div class="row p-0"> 
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

<?php 
?>	