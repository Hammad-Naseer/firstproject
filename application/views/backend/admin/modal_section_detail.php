
<div class="panel-title black2">
    <i class="entypo-plus-circled"></i>
    <?php echo get_phrase('class_and_section_details_for');?>
    <span style="color:#fff"><?php  echo get_subject_name($param2);?></span></div> 
<?php						
    $ret_value= get_subject_section($param2); 
    if(count($ret_value)==0)
    {
    	echo "<p class='red text-center mgt10'><strong>No Section assigned</strong><p>";
    } else {
?>

<table role="dialog" class="table table-bordered text-center" id="vsection<?php echo $row['subject_id'];?>" >
    <tr>
        <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $param2; ?>">	
	</tr>
    <tr style="font-weight:bold;text-align:center;">   
        <td></td>    
        <td></td>	
        <td colspan="2">
        <?php echo get_phrase('periods_per_week');?></td>
        <td colspan="2"><?php echo get_phrase('periods_per_day');?></td>
    </tr>
    <tr style="font-weight:bold;text-align:center;">   
        <td>#</td>	
        <td style="text-align: left;"> <?php echo get_phrase('class');?> - <?php echo get_phrase('section');?></td><td><?php echo get_phrase('assigned');?></td><td><?php echo get_phrase('total');?></td><td><?php echo get_phrase('assigned');?></td><td><?php echo get_phrase('total');?></td>		
    </tr>
        <?php
        $new = array();
        foreach ($ret_value as $a){
            $new[$a['department_name']][$a['section_id']] = $a['class_name'].' - '.$a['section_name'];
        }
        $i =1;
        foreach($new as $dep=>$secArry)
        {
        	foreach($secArry as $k=>$section)
        	{
        		$q2="select subject_id,section_id,periods_per_week,periods_per_day from ".get_school_db().".subject_section where school_id=".$_SESSION['school_id']." AND section_id=".$k." AND subject_id=".$param2."";
        		$selected=$this->db->query($q2)->result_array();
        		//print_r($selected);
        		foreach($selected as $sel){
        			$selArr['subject_id'][]=$param2;
        			$selArr[$sel['subject_id']]['periods_per_day']=$sel['periods_per_day'];
        			$selArr[$sel['subject_id']]['periods_per_week']=$sel['periods_per_week'];
        		}
        		echo '<tr>';
        		echo '<td>'.$i++.'</td>';
        		//echo '<td>'.$dep.'</td>';
        		echo '<td style="text-align:left;">'.$section.'</td>';
        		echo '<td>'.(subject_count_class_routine_week($param2,$k)?subject_count_class_routine_week($param2,$k):0).'</td>';
        		echo '<td>'.$selArr[$param2]['periods_per_week'].'</td>';		
        		echo '<td>'.(subject_count_class_routine_day($param2,$k)?subject_count_class_routine_day($param2,$k):0).'</td>';
        		echo '<td>'.$selArr[$param2]['periods_per_day'].'</td>';
        		echo '</tr>';
        	}
        } }
        ?>             
</table>   	