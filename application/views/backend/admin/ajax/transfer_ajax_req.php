<style>
.ptag{margin:0;padding:0}.fa-mobile{font-size:24px}.emer{color:red}.emer_green{color:green}.emer_blue{color:#00f}
</style>



<?php
$where_val="";
if($from_school!=""){
	$where_val.="  and ts.from_branch=$from_school";
}
if($to_school!=""){
		$where_val.="  and ts.to_branch=$to_school";
}


$quer="select s.*,ts.transfer_id,sc.name as school_name, cs.title as section_name, c.name as class_name,ts.to_branch, d.title as department_name,ts.status

 from ".get_system_db().".system_school ss 
 
 inner join  ".get_school_db().".school sc on sc.sys_sch_id=ss.sys_sch_id 
 
 inner join ".get_school_db().".student s on s.school_id=sc.school_id  
 
 inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
 inner join ".get_school_db().".class c on c.class_id=cs.class_id
 inner join ".get_school_db().".departments d on d.departments_id=c.departments_id 

 
 inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id=s.academic_year_id 

  
 inner join ".get_school_db().".transfer_student ts on s.student_id=ts.student_id
 
 
 
 where s.is_transfered=1 and ts.status=2  and ss.parent_sys_sch_id=".$_SESSION['parent_sys_sch_id']." $quer_where  $acd_id  ";


$students=$this->db->query($quer)->result_array();



$schoo_val=$this->db->query("select * from ".get_school_db().".school")->result_array();


$scl_val=array();

foreach($schoo_val as $rro){
	
	$scl_val[$rro['school_id']]=$rro['name'];

}


?>

<br /><br />
<table class="table table-bordered table_export">
    <thead>
        <tr>
            <th style="width: 34px;"><?php echo get_phrase('s_no');?></th>
            <th width="80"><?php echo get_phrase('photo');?></th>
            <th><?php echo get_phrase('detail');?></th>
            <th width="80"><?php echo get_phrase('options');?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $a=0;
        foreach($students as $row):
            
		$a++;
		
		?>
        <tr>
      
            <td class="td_middle"><?php echo $a;?></td>
            <td>
                <img src="<?php
                if($row['image']==''){
                    echo  base_url().'/uploads/default.png'; 
                }else{
                    echo  display_link($row['image'],'student');
                } ?>" class="img-circle" width="30" />
            </td>     
            <td>
                <div class="myttl">  <?php echo $row['name'];?><span style="display: none;"><?php echo  $row['system_id']; ?></span><span style="font-size:12px;">(<?php echo get_phrase('roll');?>#: <?php echo $row['roll'];?>)</span>
                </div>
                <div>
                    <strong><?php echo get_phrase('school_name');?>:</strong><?php echo $row['school_name'];?>
                </div>
                <div>
                  <strong><?php echo get_phrase('transfer_to_branch');?>: </strong><?php echo $scl_val[$row['to_branch']];?>
                </div>
                <div ><strong><?php echo get_phrase('mobile');?>#:</strong><?php echo $row['mob_num'];?></div>
            </td>
            
            <td class="td_middle">               
                <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                
                <!-- STUDENT promote LINK -->
                                        <li>
                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/transfer_branch/<?php echo $row['student_id'];?>');">
                <i class="entypo-pencil"></i>
                <?php echo get_phrase('transfer'); ?>
                 </a>
                </li>
                </ul>
                </div>
            </td>
            
            <?php
            if($row['account_status']==0){
                $account_status=1;
            }else{
                $account_status=0; 
            }
            ?>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

<script>
    $(".table_export").DataTable();
</script>