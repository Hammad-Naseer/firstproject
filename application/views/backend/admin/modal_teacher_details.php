<div class="panel-heading" style="font-weight: bold;">
     <div class="panel-title black2">
        <?php echo get_phrase('view_teacher_detail_for');?>
        <span> <?php  echo get_subject_name($param2);?> </span>	
    </div>
</div>
<table class="table table-bordered text-center" id="vteacher<?php echo $row['subject_id'];?>">
    <tr>
		<td colspan="6">
			<p style="font-weight:bold;">
				<input type="hidden" name="subject_id" id="subject_id" value="<?php echo $param2; ?>">
			</p>
		</td>
	</tr>
    <tr style="font-weight:bold;text-align:center;">               		         	
        <td colspan="2" style="text-align:left;"></td>	
        <td colspan="2">  <?php echo get_phrase('periods_per_week');?> </td><td colspan="2"><?php echo get_phrase('periods_per_day');?></td>             
    </tr>
    <tr style="font-weight:bold;text-align:center;">  		         	    		         	
        <td>#</td>	
        <td  style="text-align:left;">  <?php echo get_phrase('teacher_name');?></td> <td>  <?php echo get_phrase('total');?></td>
        <td>  <?php echo get_phrase('assigned');?></td><td>  <?php echo get_phrase('total');?></td><td>  <?php echo get_phrase('assigned');?></td>
    </tr>               		         	
  	<?php 
  		$teachers=subject_teacher($param2);
		if(sizeof($teachers)>0){ 
	?>
	<?php
		$i = 1;
		foreach($teachers as $all){
    				$week="select count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
                	inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
                	inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
                	where st.teacher_id=".$all['teacher_id']." and st.school_id=".$_SESSION['school_id']."";
                	
                	$periods_per_week=$this->db->query($week)->result_array();
                	
                     $day1="select max(count) from (SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
                	inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
                	inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
                	where st.teacher_id=".$all['teacher_id']." and st.school_id=".$_SESSION['school_id']." group by cr.day ORDER BY cr.day ASC) as counts";
                	
                	$perids_per_day=$this->db->query($day1)->result_array();
                	$count=$perids_per_day[0]['max(count)']?$perids_per_day[0]['max(count)']:0;
                    // echo $row['periods_per_day'].' ( '.$count.' )';
                    $q2="select s.*,d.title as designation from ".get_school_db().".staff s inner join ".get_school_db().".designation d on s.designation_id=d.designation_id where s.school_id=".$_SESSION['school_id']." and d.is_teacher=1 and s.staff_id=".$all['teacher_id']."";
                    $teachers=$this->db->query($q2)->result_array();
            ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td  style="text-align:left;">
				<div>
				<?php echo $all['teacher_name'] ; ?>
				<span style="font-size:11px;">(<?php echo $all['designation'] ; ?>)</span>
				
				</div>
				</td>
				
				<td > <?php echo $teachers[0]['periods_per_week'] ; ?> </td>
    			<td ><?php echo $periods_per_week[0]['count'] ; ?></td>
    			<td ><?php echo $teachers[0]['periods_per_day'] ; ?></td>
    			<td ><?php echo $count ; ?></td>					
			</tr>
			<?php $i++; ?>				
			<?php }?>			
	<?php } ?>            																
</table>      