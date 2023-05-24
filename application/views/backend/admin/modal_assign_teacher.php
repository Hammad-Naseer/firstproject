<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
    	<div class="panel-title" style="padding:10px 0px !important;">
    		<i class="entypo-plus-circled"></i>
    		<?php echo get_phrase('assign_teacher');?>
    	</div>
    </div>
    <?php
        
        $staff_id=$this->uri->segment(4);
        $period_no=$this->uri->segment(5);
        $subject_id=$this->uri->segment(6);
        $day=$this->uri->segment(7);
        $section_id=$this->uri->segment(8);
        $date=$this->uri->segment(9);
        $attend_id=$this->uri->segment(10);
    
        //for checkbox checked
    	$pp="SELECT s.name as teacher_name,s.staff_id as teacher_id FROM ".get_school_db().".substitute_teacher st 
    	INNER JOIN ".get_school_db().".staff s
    	ON st.staff_id=s.staff_id
    	WHERE st.school_id=".$_SESSION['school_id']." AND st.subject_id=".$subject_id." AND st.section_id=".$section_id." AND st.period_no=".$period_no." AND st.date='".$date."' AND st.substitute_of=".$staff_id." "; 	
    	$subs_array=$this->db->query($pp)->result_array();
        $teacher_id2=array();
    
    	if(count($subs_array) > 0)
    	{
    		foreach($subs_array as $subs_teacher)
    		{
    			$teacher_id2[]=$subs_teacher['teacher_id'];	
    		}
    	}
    	
        $query = "select s.name as teacher_name,s.staff_id as teacher_id,d.title as designation, st.subject_teacher_id as subject_teacher_id
    		FROM 
    		".get_school_db().".staff s
    		INNER JOIN ".get_school_db().".subject_teacher st
    		ON st.teacher_id=s.staff_id
    		INNER JOIN ".get_school_db().".designation d 
    		ON (s.designation_id = d.designation_id AND d.is_teacher=1)
    		WHERE 
    		st.subject_id=$subject_id and st.school_id=".$_SESSION['school_id']." and s.staff_id!=".$staff_id."
    		";
        $list=$this->db->query($query)->result_array();
        $list_array=array();
        $teacher_id=array();
        if(count($list) > 0)
        {
            foreach($list as $teacher)
            {
            	$teacher_id[]=$teacher['teacher_id'];
            	$list_array[$teacher['teacher_id']]['teacher_name']=$teacher['teacher_name'];
            }
    
            $t_id=implode(',',$teacher_id);
    
            $q2="select distinct staff.staff_id,s.subject_id, s.name, s.code, cr.day, cr.period_no, d.title as department, class.name as class, cs.title as class_section,cs.section_id as section_id
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
            	and cr.period_no=$period_no
            	and staff.staff_id!=".$staff_id."
            	and crs.school_id = ".$_SESSION['school_id']." 
            	and cr.day='".$day."'
            	";
            $query2=$this->db->query($q2)->result_array();
            foreach($query2 as $res)
            {
        		$list_array[$res['staff_id']]['section']= $res['department'].'/'.$res['class'].'/'.$res['class_section'];
                $list_array[$res['staff_id']]['subject']= $res['name'].'-'.$res['code'];
    	    }
    
    ?>
    <div class="panel-body">
        <form>
            <table class="table table-bordered table-hover">
            	<tr>
            		<td>S#</td>
            		<td><?php echo get_phrase('assign');?></td>
            		<td><?php echo get_phrase('teacher_name');?>e</td>
            		<td><?php echo get_phrase('section');?></td>
            		<td><?php echo get_phrase('subject');?></td>
            		<td><?php echo get_phrase('reserve');?> / <?php echo get_phrase('available');?></td>
            	</tr>
            	
            	<?php
            	$count=1;
            	foreach($list_array as $key=>$value)
            	{
            		?>
            
            		<tr>
            		<?php 
                		$status="";
                		if($list_array[$key]['section']=="")
                		{
                			$status="Available";
                		} 
                		else
                		{
                			$status="Reserve";
                		}
            		?>
                		<td>
                    		<?php 
                    		    echo $count;
                        		$checked="";
                        		if(in_array($key,$teacher_id2))
                        		{
                        			$checked="checked";
                        		}
                    		?>
                		</td>
                		
                		<td><input type="checkbox" name="staff_id" id="staff_id" class="staff" value="<?php echo $key;?>" <?php echo $checked;?>></td>
                		<td><?php echo $list_array[$key]['teacher_name'];?></td>
                		<td><?php echo $list_array[$key]['section'];?></td>
                		<td><?php echo $list_array[$key]['subject'];?></td>
                		<td><?php echo $status;?></td>
            		</tr>
            	<?php $count++; } ?>
            </table>
            <div class="form-group">
              <div class="float-right">
                  <button type="button" class="modal_save_btn" id="btn_add"><?php echo get_phrase('save');?></button>
              </div>
            </div>
            <?php 
                } else{
            	echo get_phrase('no_record_found');
                }
            ?>
        </form>
    </div>
</div>    
<script>
$(document).ready(function(){
$('#btn_add').click(function() {
	var section_id='<?php echo $section_id;?>' 
	var subject_id='<?php echo $subject_id;?>'
	var period_no='<?php echo $period_no;?>';
	var substitute_of='<?php echo $staff_id;?>';
	var date='<?php echo $date;?>';
	var day='<?php echo $day;?>';
	var staff_id=$('.staff:checked').serializeArray();
	
	
	$.ajax({	
      type: 'POST',
       data: {staff_id:staff_id,
       section_id:section_id,
       subject_id:subject_id,
       period_no:period_no,
       substitute_of:substitute_of,
       date:date
       },
url: "<?php echo base_url();?>asign_substitute/asign_save",
 dataType: "html",
 success: function(response) 
 	{ 
 	var attend_id='<?php echo $attend_id;?>';
 	var staff='<?php echo $staff_id;?>';
    	$('#modal_ajax').modal('hide'); 
    	
    	
    	window.location="<?php echo base_url() ?>asign_substitute/listing_asign/"+date+"/"+attend_id+"/"+staff+"/"+day;
    	
    	
	}
});	

});
	});	
</script>