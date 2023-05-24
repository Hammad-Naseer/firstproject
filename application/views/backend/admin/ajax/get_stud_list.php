<?php
//     $section_id = $_POST['section_id'];
//     $query="SELECT * FROM ".get_school_db().".student WHERE section_id=$section_id AND school_id=".$_SESSION['school_id']." AND  student_status IN (".student_query_status().") ORDER BY roll desc";
// 	$students=$this->db->query($query)->result_array();
?>	

<!--<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">-->
<!--<thead>-->
<!--    <tr>-->
<!--        <th style="width:34px;"><?php echo get_phrase('picture');?></th>-->
<!--        <th><?php echo get_phrase('roll_no');?></th>-->
<!--        <th><?php echo get_phrase('student_name');?></th>-->
<!--        <th style="width:150px;"><?php echo get_phrase('view_attendance');?></th>-->
<!--    </tr>-->
<!--</thead>-->
<?php
/*
foreach($students as $row)
{?>
    <tr>
        <td class="td_middle">
        		<img src="<?php
        		if($row['image']==''){
        		 echo  base_url().'/uploads/default.png'; 
        		}else{
        		echo  display_link($row['image'],'student');
        		}
        		 ?>" class="img-circle" width="30" />
        </td>	
        <td><?php echo $row['roll'];?></td>	
        <td><?php echo $row['name'];?></td>
        <td class="td_middle">
        <?php
         if (right_granted('viewstudentattendance_view')){?>
        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_stud_attendance/<?php echo $row['student_id'];?>');" class="btn btn-primary"><?php echo get_phrase('view_student_attendance');?></a>
        <?php }?>
        </td>
    </tr>
<?php
}
*/
?>
<!--</table>-->
		
// <script>
//     $(".table_export").DataTable( {
//     });
// </script>