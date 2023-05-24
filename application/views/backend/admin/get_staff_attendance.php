<?php 
$date_month=$_POST['month'];
$date_year=$_POST['year'];
$staff_id=$_POST['staff_id'];
$q="SELECT status,date FROM ".get_school_db().".attendance_staff WHERE staff_id=$staff_id AND month(date)=$date_month AND YEAR(date)=$date_year AND school_id=".$_SESSION['school_id']."";
$qur_red=$this->db->query($q)->result_array();
//print_r($qur_red);
$plan=array();
foreach($qur_red as $red){
	
$plan[$red['date']]=array('status'=>$red['status']);

}
//print_r($plan);
?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable dataTable" id="table_export" aria-describedby="table_export_info">
  <thead>
<tr>
<th><?php echo get_phrase('date'); ?></th>
<th><?php echo get_phrase('day'); ?></th>
<th><?php echo get_phrase('attendance'); ?></th>
</tr>
 </thead>
 
 
   <tbody>
<?php
$d=cal_days_in_month(CAL_GREGORIAN,$date_month,$date_year);
for($i=01; $i<=$d; $i++){

$s=mktime(0,0,0,$date_month, $i, $date_year);
$today_date= date('Y-m-d',$s);
//to convert in days
$dw = date( "l", strtotime($today_date));
 $d1 = date( "d-M-Y", strtotime($today_date));
 
echo "<tr";

$date1 = "$date_year-$date_month-$i";
$date_num = date('N',strtotime($date1));
if($dw=='Saturday' or $dw=='Sunday'){
echo " style='background-color:#EEE'";	
}
else{
	

$q1="select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ";	
$qurrr=$this->db->query($q1)->result_array();
	if(count($qurrr)>0){
		
		echo " style='background-color:orange;'";
			}	
	
}

echo ">";
echo "<td style='        
   '><span style='!important;'>$d1</span> </td>";
   
   
   
   echo "<td style='        
   '>$dw</td>";
echo "<td>";


	foreach($plan[$today_date] as $std )
{
	if($std['status']==1)
	{
		echo "Present";
	}
	elseif($std['status']==2)
	{
		echo "Absent";
	}
	elseif($std['status']==3)
	 {
            $leave_date = date('Y-m-d', strtotime($d1));
            $leave_qry = "select * from ".get_school_db().".leave_staff 
                where staff_id='$staff_id' 
                and (DATE('".$leave_date."') between start_date and end_date)
                and school_id=".$_SESSION['school_id']." ";
           
            $leave_arr = $this->db->query($leave_qry)->result_array();
            
            echo "<strong>Category: </strong>".$this->crud_model->get_type_name_by_id('leave_category',$leave_arr[0]['leave_category_id']);
           
			
						
			echo '<br>';
			
			
						
			echo "<strong>Status: </strong>";
            if($leave_arr[0]['status']==0)
			{
                echo '<span class="orange">Pending</span>';
            } 
            if($leave_arr[0]['status']==1)
			{
                echo '<span class="green">Approved</span>';
            }
            if($leave_arr[0]['status']==2)
			{
                echo '<span class="orange">Rejected</span>';
            }
			

            echo '<br>';
            
            echo "<strong>Approval Date</strong>:";
                if($leave_arr[0]['process_date']!="")
                    { echo $leave_arr[0]['process_date']; }else{ echo "N/A"; };
			
			echo '<br>';
            echo "<strong>Description: </strong>".$leave_arr[0]['reason']; 
            if ($leave_arr[0]['proof_doc']!="")
            { ?> 
                <a href=" <?php echo display_link($leave_arr[0]['proof_doc'],'leaves_staff'); ?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a> 
            <?php 
            }  
			
			
			
			

         
           
			
			

            
        }
}

echo "</td>";
echo "</tr>";
} ?>  

 </tbody>
</table>
<?php 
?>	