<?php
$result = $this->db->query("SELECT cr.day,cr.period_no,staff.name as staff_name,sub.name as subject_name,sub.code,date_format(cr.period_start_time,'%H:%i')as period_start_time,date_format(cr.period_end_time,'%H:%i')as period_end_time,d.title as department, class.name as class, cs.title as class_section,cr.duration, crs.period_duration,cr.class_routine_id,staff.staff_id,sub.subject_id,cs.section_id  FROM ".get_school_db().".class_routine cr
inner join ".get_school_db().".class_routine_settings crs
on cr.c_rout_sett_id=crs.c_rout_sett_id
inner join ".get_school_db().".time_table_subject_teacher ttst on cr.class_routine_id = ttst.class_routine_id
inner join ".get_school_db().".subject_teacher st on ttst.subject_teacher_id = st.subject_teacher_id
inner join ".get_school_db().".staff staff on st.teacher_id = staff.staff_id
inner join ".get_school_db().".subject sub on cr.subject_id=sub.subject_id
inner join ".get_school_db().".class_section cs
on crs.section_id=cs.section_id
inner join ".get_school_db().".class on class.class_id = cs.class_id
	inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
WHERE cr.day='".$day."' AND cr.period_no = $period_no AND cr.school_id=".$_SESSION['school_id']." AND staff.staff_id!=$teacher_id ")->result_array();
	//echo $this->db->last_query();
	
	$list_array=array();
	if(sizeof($result)>0)
	{
		?>
	
<?php
		$period_start_time="";
		$period_end_time="";
		$no_of_periods=0;
		foreach ($result as $row) 
		{
			$list_array[] = 
			array(
			'subject_name'=>$row['subject_name'].' - '.$row['code'],
			'class'=>$row['class'].' - '.$row['class_section'],
			'duration'=>$row['duration'],
			'default_duration'=>$row['period_duration'],
			
			'period_start_endtime'=>$row['period_start_time'].' / '.$row['period_end_time'],
			'staff_name'=>$row['staff_name'],
			'class_routine_id'=>$row['class_routine_id'],
			'staff_id'=>$row['staff_id'],
			'subject_id'=>$row['subject_id'],
			'section_id'=>$row['section_id']
			);
		}
		/*echo "<pre>";
		print_r($list_array);*/
	
	?>
	<div class="row">
	    <h3><?php echo get_phrase('timetable_details_to_swap_with');?></h3>
	    <table class="table table-striped table-bordered " cellspacing="0" width="100%">
            <thead>
            <tr>
            <th>
                    <div></div>
                </th>
                
                <th>
                    <div>
                        <?php echo get_phrase('subject');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('day');?>/<?php echo get_phrase('period_no');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('class_section');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('start');?>/<?php echo get_phrase('end_time');?>(<?php echo get_phrase('duration');?>)
                    </div>
                </th>
            </tr>
        </thead>
            <tbody> 
                <?php
                	if(count($list_array) > 0)
                	{
                		$count = 1;
                		
                		foreach($list_array as $val)
                		{
                			$duration=0;
                			$start_time="";
                			$end_time="";
                			$val_selected="";
                			if($to_c_rout_id==$val['class_routine_id'])
                			{
                				if($to_teacher_id==$val['staff_id'])
                				{
                					$val_selected='checked';
                				}
                				
                			}
                			?>
                			<tr>
                				<td><input type="radio" name="to_class_rout_id" id="to_class_rout_id" value="<?php echo $val['class_routine_id'];?>/<?php echo $val['staff_id'];?>" <?php echo $val_selected;?> data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" required>
                				
                				</td>
                                
                				
                				
                				<td><?php echo $val['subject_name']." (".$val['staff_name'].")";?>	
                				</td>
                				
                				<td><?php echo ucfirst($day)." (".$period_no.")";?></td>
                				
                				
                				<td><?php echo $val['class'];?>
                					
                				</td>
                				<td>
                				<?php
                				echo $val['period_start_endtime'];
                				$period_start_endtime=$val['period_start_endtime'];
                				$start_end_arr=explode("/",$period_start_endtime);
                				$start_time=$start_end_arr[0];
                				$end_time=$start_end_arr[1];
                				$duration=$val['duration'];
                				$duration_val=0;
                				if(isset($duration) && ($duration >0))
                				{
                					$duration_val=$val['duration'];
                					echo " (".$duration_val." ".get_phrase('mins').")";
                				}
                				else
                				{
                					$duration_val=$val['default_duration'];
                					echo " (".$duration_val." ".get_phrase('mins').")";
                				}
                				?>
                				
                			</tr>
                		<?php
                		}
                	}
                ?>
	        </tbody>
	    </table>
	    <div class="thisrow" id="data_div" style="width: 100%;padding-left: 20px;">
    		<div class="form-group col-sm-12">
            	<label class="control-label"><?php echo get_phrase('title');?></label>
                <input type="text" class="form-control" name="title" value="<?php echo $title;?>" maxlength="200" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" required>
            </div>
            <div class="form-group col-sm-12">
                <label class="control-label"><?php echo get_phrase('comments');?></label>
                <textarea name="comments" maxlength="1000" id="comments" rows="5" oninput="count_value('comments','comments_count','1000')"  class="form-control"><?php echo $comments;?></textarea>
            </div>
            <div id="comments_count" class="col-sm-4 col-sm-offset-4 " style="font-size: 10px;padding: 0px;"></div>
			<div class="form-group">
                <div class="float-right">
                	<input type="submit" class="modal_save_btn" name="btn_info" style="position: relative;left: -45px;">
            	</div>
            </div>
        </div>
    </div>
<?php
	}
	else
	{
		echo get_phrase("no_record_found");
	}

?>

<script>
  $(function() {
    $(".datepicker").datepicker();
  });
  </script>