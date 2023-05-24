<?php
$attend_id;
$staff_id;
$day;

$r="select s.subject_id, s.name, s.code, cr.day, cr.period_no, d.title as department, class.name as class, cs.title as class_section,cs.section_id as section_id 
	from ".get_school_db().".subject s
	inner join ".get_school_db().".subject_teacher st on s.subject_id =  st.subject_id
	inner join ".get_school_db().".staff on staff.staff_id =  st.teacher_id
	inner join ".get_school_db().".time_table_subject_teacher ttst on st.subject_teacher_id =  ttst.subject_teacher_id
	inner join ".get_school_db().".class_routine cr on cr.class_routine_id =  ttst.class_routine_id
	inner join ".get_school_db().".class_routine_settings crs on (crs.c_rout_sett_id =  cr.c_rout_sett_id and crs.is_active = 1)

	inner join ".get_school_db().".subject_section ss on ss.subject_id = st.subject_id
	inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
	inner join ".get_school_db().".class on class.class_id = cs.class_id
	inner join ".get_school_db().".departments d on d.departments_id = class.departments_id

	where 
	staff.staff_id = $staff_id
	and crs.school_id = ".$_SESSION['school_id']." 
	and cr.day='".$day."'
	group by day, period_no
	order by day, period_no
	";
$result = $this->db->query($r)->result_array();


/*$timetable_arr = array(
					$day=>array(), 
					
				);	*/
/*$timetable_arr = array(
					'monday'=>array(), 
					'tuesday'=>array(), 
					'wednesday'=>array(), 
					'thursday'=>array(), 
					'friday'=>array(), 
					'saturday'=>array(), 
					'sunday'=>array()
				);*/
$dcs_arr = array(
					get_phrase('monday')=>array(), 
					get_phrase('tuesday')=>array(), 
					get_phrase('wednesday')=>array(), 
					get_phrase('thursday')=>array(), 
					get_phrase('friday')=>array(), 
					get_phrase('saturday')=>array(), 
					get_phrase('sunday')=>array()
				);
$period_no = array();
$subject_id = array();
$timetable_arr=array();
if(sizeof($result)>0)
{
	foreach ($result as $row) 
	{
		$timetable_arr[$row['day']][$row['period_no']] = $row['name'].' - '.$row['code'];	
		
		$dcs_arr[$row['day']][$row['period_no']] =$row['class'].' - '.$row['class_section'];	
		$period_no[$row['period_no']] =$row['period_no'];
		$subject[$row['day']][$row['period_no']]['subject_id']=$row['subject_id'];
		$section[$row['day']][$row['period_no']]['section_id']=$row['section_id'];
	}
	
	
	
	
	
?>	
	
	
				<table cellpadding="0" cellspacing="0" border="0"  class="table table-bordered">
							<thead>
                    	<tr >
	                    	<th width="50" style="background:#2092d0; color:#ffffff;"><?php echo get_phrase('period');?></th>
		                    <th style="background:#2092d0; color:#ffffff;"><?php echo get_phrase('subject');?> / <?php echo get_phrase('section');?></th>
		                    <th width="100" style="background:#2092d0; color:#ffffff;"><?php echo get_phrase('assign');?></th>
	                    </tr>
                    </thead>
                    <tbody>
						
						<?php 
						
	                    foreach ($timetable_arr as $key => $value) 
	                    {
		                    $period_max = max($period_no);	
							for ($i = 1; $i<=$period_max; $i++ )
                    		{?>
	                    		<tr><th ><?php echo $i; ?></th>
	                    		
	                    			<th>
                                <div class="col-lg-3 col-md-3 col-sm-3">    
									<?php echo $value[$i]; ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                	<?php echo $dcs_arr[$key][$i];?>
                                </div>
								<?php
	                    		$subject_id=$subject[$key][$i]['subject_id'];
	                    		$section_id=$section[$key][$i]['section_id'];
	                    	if($dcs_arr[$key][$i]!="" && isset($dcs_arr[$key][$i]))
{	

	   $pp="SELECT s.name as teacher_name,s.staff_id as teacher_id FROM ".get_school_db().".substitute_teacher st 
	INNER JOIN ".get_school_db().".staff s
	ON st.staff_id=s.staff_id
	 WHERE st.school_id=".$_SESSION['school_id']." AND st.subject_id=".$subject_id." AND st.section_id=".$section_id." AND st.period_no=".$i." AND st.date='".$date."' AND st.substitute_of=".$staff_id." "; 	
	$subs_array=$this->db->query($pp)->result_array();
$teacher_id=array();
$list_array=array();
	if(count($subs_array) > 0)
	{
		foreach($subs_array as $subs_teacher)
		{
			$teacher_id[]=$subs_teacher['teacher_id'];
			$list_array[$subs_teacher['teacher_id']]['teacher_name']=$subs_teacher['teacher_name'];
			$t_id=implode(',',$teacher_id);
		}
		$t="select distinct staff.staff_id,s.subject_id, s.name, s.code, cr.day, cr.period_no, d.title as department, class.name as class, cs.title as class_section,cs.section_id as section_id
	from ".get_school_db().".subject s
	inner join ".get_school_db().".subject_teacher st on s.subject_id =  st.subject_id
	inner join ".get_school_db().".staff on staff.staff_id =  st.teacher_id
	inner join ".get_school_db().".time_table_subject_teacher ttst on st.subject_teacher_id =  ttst.subject_teacher_id
	inner join ".get_school_db().".class_routine cr on cr.class_routine_id =  ttst.class_routine_id
	inner join ".get_school_db().".class_routine_settings crs on (crs.c_rout_sett_id =  cr.c_rout_sett_id and crs.is_active = 1)

	inner join ".get_school_db().".subject_section ss on ss.subject_id = st.subject_id
	inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
	inner join ".get_school_db().".class on class.class_id = cs.class_id
	inner join ".get_school_db().".departments d on d.departments_id = class.departments_id

	where 
	staff.staff_id IN (".$t_id.")
	and cr.period_no=$i
	
	and crs.school_id = ".$_SESSION['school_id']." 
	and cr.day='".$day."'";
$t_detail=$this->db->query($t)->result_array();	
foreach($t_detail as $res)
	{
		
		$list_array[$res['staff_id']]['section']= $res['department'].'/'.$res['class'].'/'.$res['class_section'];
$list_array[$res['staff_id']]['subject']= $res['name'].'-'.$res['code'];


	}
	foreach($list_array as $key1=>$value1)
	{
		echo "<br>";
		echo get_phrase("teacher_assigned")." : ".$list_array[$key1]['teacher_name'];
		echo "<br>";
		if($list_array[$key1]['section']!="")
		{
			echo get_phrase("department")." : ".$list_array[$key1]['section'];
		echo "<br>";
		echo get_phrase("subject")." : ".$list_array[$key1]['subject'];
		}		
	}
		
	}
	


             		
	                    		
}                    		?> 
	                    		
	                    		 </th>
	                    		<th id="new"><?php
	
if($dcs_arr[$key][$i]!="" && isset($dcs_arr[$key][$i]))
{
	
	?>
	<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_assign_teacher/<?php echo $staff_id;?>/<?php echo $i;?>/<?php echo $subject_id;?>/<?php echo $day;?>/<?php echo $section_id;?>/<?php echo $date;?>/<?php echo $attend_id;?>');" class="btn btn-primary">
                    <i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('assign');?>
                </a>
<?php }
	                    		?> 
	                    		</th>
	                    		</tr>
                    		<?php
                    		}
                    		
                    		
	                    }
                    	
						
	?>
	</table>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<?php	
}
else
{
	echo get_phrase("no_class_today");
}
?>