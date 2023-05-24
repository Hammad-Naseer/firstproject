<style>
.ptag{margin:0;padding:0}.fa-mobile{font-size:24px}.emer{color:red}.emer_green{color:green}.emer_blue{color:#00f}
</style>
<?php 
$school_id=$_SESSION['school_id'];

$quer="select s.*,ts.transfer_id,ts.s_c_f_id, cs.title as section_name, c.name as class_name, d.title as department_name,ts.status,ts.completed_by,ts.completed_date

 from ".get_system_db().".system_school ss 
 
 inner join  ".get_school_db().".school sc on sc.sys_sch_id=ss.sys_sch_id 
 
 inner join ".get_school_db().".student s on s.school_id=sc.school_id  
 
 left join ".get_school_db().".class_section cs on cs.section_id=s.section_id
 left join ".get_school_db().".class c on c.class_id=cs.class_id
 left join ".get_school_db().".departments d on d.departments_id=c.departments_id 

 
 inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id=s.academic_year_id 

  
 inner join ".get_school_db().".transfer_student ts on s.student_id=ts.student_id
 
 
 
 where s.is_transfered=2 and ss.parent_sys_sch_id=".$_SESSION['parent_sys_sch_id']." and ts.status in (7,8,9,10) and   s.school_id=".$_SESSION['school_id']." $quer_where  $acd_id  ";

$students=$this->db->query($quer)->result_array();
?>

<br /><br />
<table class="table table-bordered table_export">
    <thead>
        <tr>
            <th style="width: 34px;"><div><?php echo get_phrase('s_no');?></div></th>
            <th style="width: 84px;"><div><?php echo get_phrase('photo');?></div></th>
            <th style="width: 94px;"><div><?php echo get_phrase('options');?></div></th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($students as $row):?>
        <tr>      
            <td class="td_middle"> <img src="<?php if($row['image']==''){ echo  base_url().'/uploads/default.png'; }else{ echo  display_link($row['image'],'student');}?>" class="img-circle" width="30" /></td>
            <td>
               <div class="myttl"><?php echo $row['name'];?><span style="display: none;"><?php echo  $row['system_id']; ?></span><span style="font-size:12px;"> (<?php echo get_phrase('roll');?>#: <?php echo $row['roll'];?> )</span></div>
               <div>
               	<?php if($row['department_name']!=""){?>
               	<ul class="breadcrumb breadcrumb2">
               		<li><?php echo $row['department_name'];?></li>
               		<li><?php echo $row['class_name'];?></li>
               		<li><?php echo $row['section_name'];?></li>
               	</ul>
               	<?php } ?>
           </div>
          <div>
              <strong><?php echo get_phrase('transfer_status');?>:</strong>
          	<?php
          	$transfer_status=$row['status'];
          	$transfer_val="";
          	if($transfer_status==7)
          	{
				$transfer_val=get_phrase("received_transfer_request");
			}
			elseif($transfer_status==8)
			{
				$transfer_val=get_phrase("section_assigned");
			}
			elseif($transfer_status==9)
			{
				$transfer_val=get_phrase("challan_form_issued");
			}
			elseif($transfer_status==10)
			{
				$transfer_val=get_phrase("fee_received");
			}
			echo $transfer_val;
          	?>
          </div>
          <?php if($row['completed_by']>0)
          {?>
          <div>
          	<strong><?php echo get_phrase('completed_by');?>:</strong>
          	<?php 
          	$user_req=get_user_info($row['completed_by']);
            	echo $user_req[0]['name'];
          	?>
          </div>
          
          <div>
          	<strong><?php echo get_phrase('completed_date');?>:</strong>
          	<?php echo convert_date($row['completed_date']);?>
          </div>
           <?php
           }
		  ?>
      
           
           </td>
           
           





<td class="td_middle">
                
<div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
</button>
<ul class="dropdown-menu dropdown-default pull-right" role="menu">
<?php

if($row['status']==1 || $row['status']==3){
?>


<li>
<a href="#" onclick="confirm_modal('<?php echo base_url();?>transfer_student/farward_admin/<?php echo $row['transfer_id']; ?>');">
<i class="entypo-trash"></i>

<?php echo get_phrase('forward_to_admin');?>
</a>
</li>

	
<?php	
}
 ?>




<?php

if($row['status']<5){
	
	
	
?>


<li>
<a href="#" onclick="confirm_modal('<?php echo base_url();?>transfer_student/cancel_transfer/<?php echo $row['student_id']; ?>');">
<i class="entypo-trash"></i>

<?php echo get_phrase('cancel_transfer');?>
</a>
</li>

	
<?php	
}
 ?>



<?php 

if($row['status']>5 && $row['status']<8 ){


?>

    <!-- STUDENT promote LINK -->
                        <li>
<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/rec_section_assign/<?php echo $row['student_id'];?>');">
<i class="entypo-pencil"></i>
<?php echo get_phrase('section_assign');?>
 </a>
</li>





<?php	
}

?>



<?php

if(($row['status']==8) || ($row['status']==9) ){


?>

<!-- STUDENT chalan  LINK -->
<li>
<a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo  $row['student_id']; ?>/7/7/<?php echo $row['transfer_id'];?>"  >

<i class="entypo-trash"></i>
<?php
if($transfer_status==9)
{
	echo get_phrase('receive_chalan_form');
}
else
{
	echo get_phrase('manage_chalan_form');
}
  ?>
</a>
</li>
    <?php } ?>

                        <?php
                       $s_c_f_id = $row['s_c_f_id'];
                        if(($row['status']==8) || ($row['status']==9) ){
                            //$student_id = $row['student_id'];
                           // $transfer_id = $row['transfer_id'];
                            ?>
                            <!-- STUDENT chalan  LINK -->
                            <li>
                                <a onclick="confirm_modal('<?php echo base_url(); ?>transfer_student/cancel_recieve_transfer/<?php echo $row['student_id']; ?>/<?php echo $row['transfer_id']; ?>');">

                                    <i class="entypo-trash"></i>
                                    <?php
                                    if($transfer_status==9)
                                    {
										//$row["status"]		
                                        echo get_phrase('receive_chalan_form');
                                    }
                                    else
                                    {
                                       // echo "Transefer status==========".$row['s_c_f_id'];
                                        echo get_phrase('cancel_challan_form');
                                    }
                                    ?>
                                </a>
                            </li>
                    <?php } ?>
                    <?php if($row['status']==10 ){ ?>
                    <li>
                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>transfer_student/complete_transfer/<?php echo $row['student_id']; ?>');">
                    <i class="entypo-trash"></i>
                    <?php echo get_phrase('complete_transfer');?>
                    </a>
                    </li>
                    <?php } ?>
                    </ul>
                </div>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

<script>
    $(".table_export").DataTable( {
        
    });
</script>