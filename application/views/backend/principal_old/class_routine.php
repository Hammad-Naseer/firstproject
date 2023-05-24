<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/includes/jquery-clockpicker.min.css">

<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('class_routine_list');?>
                    	</a></li>
			<!--<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_class_routine');?>
                    	</a></li>-->
		</ul>
    	<!------CONTROL TABS END------->
        
	
		<div class="tab-content">
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane active" id="list">
				<div class="panel-group joined" id="accordion-test-2">
                	<?php 
                	
                	
$routine1=array();


$q2="SELECT * FROM class_routine WHERE school_id=".$_SESSION['school_id']." AND class_id in (select class_id FROM class WHERE school_id=".$_SESSION['school_id'].")";

$result=$this->db->query($q2)->result_array();
foreach($result as $row)
{
	$routine1[$row['class_id']][$row['day']][$row['period_no']]=$row['subject_id'];
}
                	
					$toggle = true;
					$q1="SELECT * FROM class WHERE school_id=".$_SESSION['school_id']."";
					$classes=$this->db->query($q1)->result_array();
					//$this->db->where('school_id',$_SESSION['school_id']);
					//$classes = $this->db->get('class')->result_array();
					foreach($classes as $row){
						?>
                        
                
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                		<h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse<?php echo $row['class_id'];?>">
                                        <i class="entypo-rss"></i> Class <?php echo $row['name'];?>
                                    </a>
                                    </h4>
                                </div>
                
                                <div id="collapse<?php echo $row['class_id'];?>" class="panel-collapse collapse <?php if($toggle){echo 'in';$toggle=false;}?>">
                                    <div class="panel-body">
                                        <table cellpadding="0" cellspacing="0" border="0"  class="table table-bordered">
                                        <tbody>
                                            <tr>
                                            <td><strong>Period</strong></td>
<?php foreach($routine as $row1)
		{
			$no_of_periods=$row1['no_of_periods'];
			$period_duration=$row1['period_duration'];
			$start_time=$row1['start_time'];
			$end_time=$row1['end_time'];
			$assembly_duration=$row1['assembly_duration'];
			$break_duration=$row1['break_duration'];
			$break_after_period=$row1['break_after_period'];
			
			$period_array=array();
			
				
						$count=1;
						if($assembly_duration > 0)
						{
							
							echo '<td style="background-color:#DCDCDC;">Assembly</td>';
							$period_array[$count]['i']=0;
							$period_array[$count]['s']=$start_time;
			$period = strtotime($start_time) + strtotime('00:'.$assembly_duration) - strtotime('00:00');
 			$period_new = date('H:i', $period);
							$period_array[$count]['e']=$period_new;
							
						}
						for($i=1;$i<=$no_of_periods;$i++)
						{
							echo '<td>'.$i.'</td>';	
							$count = $count+1;
							$period_array[$count]['i']=$i;
							$period_array[$count]['s']=$period_new;
							
	$period_new = strtotime($period_new) + strtotime('00:'.$period_duration) - strtotime('00:00');
	
	$period_new = date('H:i', $period_new);
							$period_array[$count]['e']=$period_new;
							
							
							if(($break_after_period > 0) && ($break_after_period==$i))
							{
								echo '<td style="background-color:#DCDCDC;">Break</td>';
								$count = $count+1;
								$period_array[$count]['i']=0;
								$period_array[$count]['s']=$period_new;
								$period_new = strtotime($period_new) + strtotime('00:'.$break_duration) - strtotime('00:00');
								$period_new = date('H:i', $period_new);
								$period_array[$count]['e']=$period_new;
							}
						}
					
						
										}
										
										
										
?>     
											</tr>
                                            
                                            <tr>
                                            <td><strong>Time</strong></td>
                                            
                                            <?php foreach($period_array as $key=>$value)									
                                            { ?>
                                            <td 
                                            <?php 
                                            	if($value['i']=='0')
                                            	{
													echo ' style="background-color:#DCDCDC;" ';
												}
                                            	?> >
                                          	
										<?php echo $value['s'] .'-'.$value['e'];?></td>
										<?php } ?>
										
                                            </tr>
                                            
                                            <tbody>
                                                <?php 
                                                for($d=1;$d<=7;$d++){
                                                
                                                if($d==1)$day='sunday';
                                                else if($d==2)$day='monday';
                                                else if($d==3)$day='tuesday';
                                                else if($d==4)$day='wednesday';
                                                else if($d==5)$day='thursday';
                                                else if($d==6)$day='friday';
                                                else if($d==7)$day='saturday';
                                                ?>
                                                <tr class="gradeA">
                                                    <td width="100"><?php echo strtoupper($day);?></td>
                                                   
														 <?php foreach($period_array as $key=>$value)									
                                                    {?>
														<?php 
										if ($value['i'] ==0)
										{
											echo "<td style=background-color:#DCDCDC;></td>";
										}
										if ($value['i'] >0)
										{
		echo '<td>';
$class_id=$row['class_id'];
$val=$value['i'];


										//echo $this->crud_model->get_subject_name_by_id($routine1[$class_id][$day][$val]);
$subject_id=$routine1[$class_id][$day][$val];
if(isset($subject_id) && $subject_id > 0)
{
	

$query3="SELECT s.name AS subject_name, t.name AS teacher_name
FROM subject s
INNER JOIN teacher t ON s.teacher_id = t.teacher_id
WHERE s.subject_id =".$subject_id."
AND s.school_id =".$_SESSION['school_id']." ";
$res = $this->db->query($query3)->result_array();
foreach($res as $res1)
{
	 echo  $subject=$res1['subject_name'];
	 echo "<br/>";
	 echo "(".$teacher=$res1['teacher_name'].")";
}

}	
										
										
										}
										
?></td>
														<?php } ?>

                                                    
                                                </tr>
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
						<?php
					}
					?>
  				</div>
			</div>
            <!----TABLE LISTING ENDS--->
            
           
            
		</div>
	</div>
</div>

<script type="text/javascript" src="assets/includes/jquery.min.js"></script>
<script type="text/javascript" src="assets/includes/jquery-clockpicker.min.js"></script>
<script type="text/javascript">
$('.clockpicker').clockpicker()
	.find('input').change(function(){
		console.log(this.value);
	});
$('#single-input1').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': '09:00'
});
$('#single-input2').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': '09:00'
});
</script>