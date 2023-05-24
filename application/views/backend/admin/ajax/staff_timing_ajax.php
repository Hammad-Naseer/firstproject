<style>


.top-marg{
	
	margin-top: 5px;
	
}	
	
	
</style>

<?php

if(count($date_ary)>0){
foreach($date_ary as $dis_key=>$dis_val)
{ ?>
<div class='col-md-6'><strong>
<?php
echo convert_date($dis_key);
?>
</strong></div> 
<div class='col-md-6 top-marg'>
<?php
/*


echo $final_date[$dis_key]['time'];

*/
?>
&nbsp;
</div>
	<div class='col-md-4'><?php echo get_phrase('time_in');?></div>
 	<div class='col-md-4'><?php echo get_phrase('time_out');?></div>
 	<div class='col-md-4'><?php echo get_phrase('time_count');?></div>

<?php
$i=0;
foreach($time_out[$dis_val] as $key1=>$val1)
{
	//echo "<br>";
	//$total_time_dis+= strtotime($time_out[$dis_val][$i])-strtotime($time_in[$dis_val][$i]);
	 
	$total_time_cur= strtotime($time_out[$dis_val][$i])-strtotime($time_in[$dis_val][$i]);
	?>
	<div class='col-md-4'>
	  <?php echo date('H:i:s',strtotime($time_in[$dis_val][$i]));?>
	</div> 
	<div class='col-md-4'>
	 <?php
	 echo date('H:i:s',strtotime($time_out[$dis_val][$i]));
	 ?>
	 </div>
	 <div class='col-md-4'>
	 <?php
	 //echo $total_time_cur;
	 echo gmdate("H:i:s", $total_time_cur);
	 ?>
	</div>
	<?php
	$i++;
}
?>
<div class='col-md-4'><?php //echo get_phrase('invalid_in_time'); ?><?php
//echo "ONLY IN TIME : ".date('H:i:s',$final_date[$dis_key]['extra_in']); 
	echo date('H:i:s',strtotime($final_date[$dis_key]['extra_in'])); 
?></div>
<div class='col-md-4'><?php echo get_phrase('n_a'); ?></div>
<div class='col-md-4'><?php echo get_phrase('n_a'); ?></div>

 <div class='col-md-12'> <?php echo get_phrase('total_working_time'); ?> :
 <?php 
 //echo  gmdate("H:i:s", $total_time_dis);
 echo $final_date[$dis_key]['time'];
 //echo $total_time_dis;
 ?>	
 </div>



<?php
}
}
else{
 echo get_phrase("no_record_found");
  }
//echo gmdate("H:i:s", $total_time_dis);

?>





