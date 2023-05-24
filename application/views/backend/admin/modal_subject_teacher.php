<?php
    $urlArr=explode('/',$_SERVER['REQUEST_URI']);
    $resArr=end($urlArr);
    $subject_id=$resArr;
    $q="SELECT * from ".get_school_db().".subject_teacher where school_id=".$_SESSION['school_id']." AND subject_id=".$subject_id."";
    
    $subject_teacher=$this->db->query($q)->result_array();
    $check=$this->db->query("select distinct st.teacher_id from ".get_school_db().".subject_teacher st inner join ".get_school_db().".time_table_subject_teacher tst on st.subject_teacher_id=tst.subject_teacher_id where st.school_id=".$_SESSION['school_id']." and st.subject_id=$subject_id")->result_array();
    $chech_teacher=array();
     foreach($check as $result){
     	$chech_teacher[]=$result['teacher_id'];
    }
?>

<div class="panel-heading" style="font-weight: bold;">
    <div class="panel-title black2">		
		<?php  $q3="select name from ".get_school_db().".subject where subject_id=".$subject_id."";       $subject_name=$this->db->query($q3)->result_array();?>
        <?php echo get_phrase('assign_teacher_for');?>
        <span>
      	    <?php echo get_subject_name($subject_id);?>
		 </span>
    </div>
</div>
<div class="panel" id="add" style="padding: 5px">
    <table id="subject_teacher_add" cellpadding="0" cellspacing="0" border="0" class="center table text-center table-bordered table-striped table-responsive">
    <tr class="bold">
        <td></td>
        <td></td>
        <td colspan="2">
        <?php echo get_phrase('periods_per_week');?>
        </td>
        <td colspan="2"><?php echo get_phrase('periods_per_day');?></td>
    </tr>
    <tr class="bold">
        <td  style="text-align: left;"><?php echo get_phrase('name');?></td>
        <td style="text-align: left;"><?php echo get_phrase('designation');?></td>
        <td><?php echo get_phrase('total');?></td><td> <?php echo get_phrase('assigned');?></td>
        <td><?php echo get_phrase('total');?></td><td> <?php echo get_phrase('assigned');?> </td>
    </tr>
        <form name="subject_teacher" method="post" class="form-horizontal form-groups-bordered validate">
            <?php foreach($subject_teacher as $teacher){
  			    $matchArr[]=$teacher['teacher_id'];
  	            }
  	        ?>
            <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $subject_id;?>"/>
            <?php  
                $q2="select s.*,d.title as designation from ".get_school_db().".staff s inner join ".get_school_db().".designation d on s.designation_id=d.designation_id where s.school_id=".$_SESSION['school_id']." and d.is_teacher=1 order by s.name asc";
                $teachers=$this->db->query($q2)->result_array();
                $i = 1;
				echo "<tr>";
                foreach($teachers as $row){
			?>
    <tr>
        <td  style="text-align: left;">
            <input class="teacher" type="checkbox" name="teachers" id="teacher<?php echo $row['staff_id']?>" 
            <?php   
                if(in_array($row['staff_id'],$matchArr)) 
                {
                   echo " checked "; 
                   $val_chk=0;	
                }
                else{
                  $val_chk=1;  
                }
                if(in_array($row['staff_id'],$chech_teacher)){
                	if($val_chk == 0){
                		echo " disabled ";
        	        }
                }
            ?> value="<?php echo $row['staff_id']?>">
           <?php echo $row['name'];?>
        </td>
        <td  style="text-align: left;"> <?php echo $row['designation'];?></td>
        <td> 
            <?php echo $row['periods_per_week'] ;?>
        </td>
        <td>
            <?php 
			 $week="SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
        	inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
        	inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
        	where st.teacher_id=".$row['staff_id']." and st.school_id=".$_SESSION['school_id']."";
	        $perids_per_week=$this->db->query($week)->result_array();
        	echo $perids_per_week[0]['count']  ;?>
        </td>
        <td> 
        <?php echo $row['periods_per_day'];?>
        </td>
        <td>
        <?php 
        	$day1="select max(count) from (SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
        	inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
        	inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
        	where st.teacher_id=".$row['staff_id']." and st.school_id=".$_SESSION['school_id']." group by cr.day ORDER BY cr.day ASC) as counts";
        	$perids_per_day=$this->db->query($day1)->result_array();
        	$count=$perids_per_day[0]['max(count)']?$perids_per_day[0]['max(count)']:0;							
			echo $count  ;?>
		</td>
    </tr>
      <?php }?>
    <tr>
        <td colspan="6" >           
            <p style="color:red;"><?php echo get_phrase('note');?>:            
                <?php echo get_phrase('teachers_assigned_to_time_table_can_not_be_unlinked');?>
            </p>
            <div class="float-right">
                <button type="submit" id="submit-btn" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                    <?php echo get_phrase('cancel');?>
                </button>
            </div>
        </td>
     </tr>
	</form>                           
	</table>                     
</div>                                                    
<script type="text/javascript">
    $(document).ready(function(){		
	$('#submit-btn').click(function(){
	var subject_id=$('#subject_id').val();
	var teacher_id= $('.teacher:checked').serializeArray();
	$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>subject/assign_subject_teacher",

			data: ({subject_id:subject_id,teacher_id:teacher_id}),					
			success: function(response) {
				//console.log(response);
				$('#modal_ajax').modal('hide');
				location.reload();						
			}
		});
	});
	$('a[id^=delete]').click(function(){
		str=$(this).attr('id');
		id=str.replace('delete_','');
	    $.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>subject/delete_subject_teacher",
			data: ({id:id}),
				success: function(response) {
			}
		});
	});
});
</script>