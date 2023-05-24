<style>
 .ptag{margin:0;padding:0}.fa-mobile{font-size:24px}.emer{color:red}.emer_green{color:green}.emer_blue{color:#00f}
</style>
<?php 
$school_id=$_SESSION['school_id'];

$acd_id="";
if($section_id!='')
{
$quer_where=" and cs.section_id=$section_id";
}  

$quer="select s.*,ts.*,ts.transfer_id, cs.title as section_name, c.name as class_name, d.title as department_name,ts.status
 from ".get_system_db().".system_school ss 
 inner join  ".get_school_db().".school sc on sc.sys_sch_id=ss.sys_sch_id  
 inner join ".get_school_db().".student s on s.school_id=sc.school_id
 inner join ".get_school_db().".transfer_student ts on s.student_id=ts.student_id
 inner join ".get_school_db().".class_section cs on cs.section_id=ts.from_section
 inner join ".get_school_db().".class c on c.class_id=cs.class_id
 inner join ".get_school_db().".departments d on d.departments_id=c.departments_id 
 
 where s.is_transfered=1 and ts.status<7 and s.school_id=".$_SESSION['school_id']."   $quer_where ORDER BY ts.request_date desc";

$students=$this->db->query($quer)->result_array();

?>

<br /><br />
<table class="table table-bordered table_export">
    <thead>
        <tr>
            <th style="width:34px;"><?php echo get_phrase('s_no');?></th>
            <th width="80"><?php echo get_phrase('photo');?></th>
            <th><?php echo get_phrase('details');?></th>
            <th width="80"><?php echo get_phrase('options');?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $a=0;
        foreach($students as $row):
        $a++;?>
        <tr>
            <td class="td_middle"><?php echo $a; ?></td>
            <td class="td_middle">
                <img src="<?php if($row['image']==''){ echo  base_url().'/uploads/default.png';  }else{ echo  display_link($row['image'],'student'); }?>" class="img-circle" width="30" />
            </td>
            <td>
                
            <div class="myttl"><?php echo $row['name'];?><span style="display: none;"><?php echo  $row['system_id']; ?></span><span style="font-size:12px;">(<?php echo get_phrase('roll');?>#: <?php echo $row['roll'];?>)</span></div>
            
              <div>
              	<strong><?php echo get_phrase('class');?> / <?php echo get_phrase('section');?>: </strong>
              	<ul class="breadcrumb breadcrumb2">
              		<li><?php echo $row['department_name'] ; ?></li>
              		<li><?php echo $row['class_name'] ; ?></li>
              		<li><?php echo $row['section_name'] ; ?></li>
              	</ul>
              </div>
            
            <div>
           
            	<strong><?php echo get_phrase('requested_by');?>:</strong>
            	<?php $user_req=get_user_info($row['requested_by']);
            	    echo  $user_req[0]['name'];
            	?>
            </div>
            <div>
            	<strong><?php echo get_phrase('requested_date');?>:</strong>
            	<?php echo convert_date($row['request_date']);
            	?>
            </div>
            <div>
            	<strong><?php echo get_phrase('transferring_to');?>:</strong>
            	<?php echo branches_name($row['to_branch']);
            	?>
            </div>
            <div>
            	<strong><?php echo get_phrase('reason');?>:</strong>
            	<?php echo $row['reason'];?>
            </div>
            
            <div>
            	<strong><?php echo get_phrase('status');?>:</strong>
            	<?php //echo "transfer status".$status=$row['status']."<br/>";
            	$status=$row['status'];
            	$status_val="";
            	if($status==1)
            	{
					$status_val=get_phrase("transfer_requested");
				}
				elseif($status==2)
				{
					$status_val=get_phrase("request_forwarded_to_admin");
				}
				elseif($status==3)
				{
					$status_val=get_phrase("request_canceled_by_admin");
				}
				elseif($status==4)
				{
					$status_val=get_phrase("request_approved_by_admin");
				}
				elseif($status==5)
				{
					$status_val=get_phrase("challan_form_issued");
				}
				elseif($status==6)
				{
					$status_val=get_phrase("challan_form_received");
				}
				
				echo $status_val;
				?>
            	
            </div>
            </td>
            
            <td class="td_middle">
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span></button>
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
                    if($row['status']<6){
                    ?>


                    <li>
                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>transfer_student/cancel_transfer/<?php echo $row['student_id']; ?>/<?php echo $row['transfer_id'];?>');">
                        <i class="entypo-trash"></i>
                        
                        <?php echo get_phrase('cancel_transfer');?>
                        </a>
                    </li>

	
                    <?php	
                    }
                    ?>
                    
                    
                    <?php 

                    if(($row['status']==4) || ($row['status']==5)){
                    
                    
                    ?>
                        <li>
                        <a href="<?php echo base_url(); ?>transfer_student/check_chalan/<?php echo str_encode($row['student_id']); ?>/6/5/<?php echo $row['transfer_id'];?>"  >
                        
                        <i class="entypo-trash"></i>
                        <?php
                        if($status==5)
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
                    
                    
                    <?php	
                    }
                    
                    ?>


                    <?php 
                        if($row['status']==6){
                    ?>
                     
                    <li>
                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>transfer_student/confirm_transfer/<?php echo $row['student_id'].'/'.$row['transfer_id']; ?>');">
                    <i class="entypo-trash"></i>
                    
                    <?php echo get_phrase('confirm_transfer');?>
                    </a>
                    </li>
                    
                    <?php	
                    }
                    ?>

</ul>
</div>
</td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>


<script>
    $(".table_export").DataTable();
</script>