<?php 
$controller='s';
if (right_granted('students_view'))
{

$school_id=$_SESSION['school_id'];
/*
$acd_id="";

if($student_status!=""){
  
$acd_id=" and s.student_status=$student_status ";   
}
*/

/*if($departments_id!='' AND $class_id==''  AND $section_id==''){
$quer_where=" and  d.departments_id=$departments_id";
}
elseif($departments_id!='' AND $class_id!='' AND $section_id=='')
{
$quer_where=" and  d.departments_id=$departments_id and cc.class_id=$class_id";
}*/


if($section_id!='')
{
$quer_where=" and cs.section_id=$section_id";
}       

$quer="SELECT  s.*, cs.title as section_name, cc.name as class_name, d.title as department_name
            FROM ".get_school_db().".student s 
            INNER JOIN ".get_school_db().".class_section cs on cs.section_id=s.section_id
            INNER JOIN ".get_school_db().".class cc on cc.class_id=cs.class_id 
            INNER JOIN ".get_school_db().".departments d on d.departments_id=cc.departments_id 
                WHERE  s.school_id=".$_SESSION['school_id']." $status $quer_where  
                AND s.student_status in (".student_query_status().") ";
$students=$this->db->query($quer)->result_array();



?>
<br />
<br />

<table class="table table-bordered datatable" id="stud_info_tbl">
    <thead>
        <tr>
            <th style="width:34px;"><?php echo get_phrase('s_no');?></th>
            <th style="width:34px;"><?php echo get_phrase('picture');?></th>
            <th><?php echo get_phrase('student_information');?></th>
            <th style="width:94px;"><?php echo get_phrase('option');?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
$j=0;
foreach($students as $row):
$j++;
//echo "<pre>";
//print_r($row);
?>
        <tr>
            <td>
                <?php  echo $j; ?>
            </td>
            <td>
                <div>
                    <img src="<?php
if($row['image']==''){
 echo  base_url().'/uploads/default.png'; 
}else{
echo  display_link($row['image'],'student');
}
 ?>" class="img-circle" width="30" />
                </div>
            </td>
            <td>
                <div class="myttl">
                    <?php echo $row['name'];?><span style="display: none;"><?php echo  $row['system_id']; ?></span></div>
                <div>
                    <strong>   <?php echo get_phrase('roll');?>#: </strong>
                    <?php echo $row['roll'];?>
                </div>
                <div><strong><?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>
                    <ul class="breadcrumb breadcrumb2" style="padding:2px;">
                        <li>
                            <?php echo $row['department_name'];?> </li>
                        <li>
                            <?php echo $row['class_name'];?> </li>
                        <li>
                            <?php echo $row['section_name'];?> </li>
                    </ul>
                </div>
                <div>
                    <style>
                    .ptag {
                        margin: 0px;
                        padding: 0px;
                    }
                    
                    .fa-mobile {
                        font-size: 24px;
                    }
                    
                    .emer {
                        color: red;
                    }
                    
                    .emer_green {
                        color: green;
                    }
                    
                    .emer_blue {
                        color: blue;
                    }
                    </style>
                    <p class="ptag">
                        <strong><?php echo get_phrase('mobile');?>#: </strong>
                        <?php echo $row['mob_num'];?>
                    </p>
                </div>
            </td>
            <td>
            <?php
            if (right_granted(array('students_manage', 'students_promote', 'students_delete')))
            {
            ?>
                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <?php
  
  if($row['is_transfered']==1){
    ?>
                                <li>
                                    <i class="entypo-user"></i>
                                    <?php echo get_phrase('transfer_requested');?>
                                </li>
                                <?php
    
  }else{
    
     ?>
                                <?php
                                if (right_granted('students_view'))
                                
                                {
                                	
                                ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>c_student/student_detail/<?php echo $controller;?>/<?php echo $row['student_id']; ?>/<?php echo $section_id;?>">
                                            <i class="entypo-user"></i>
                                            <?php echo get_phrase('profile');?>
                                        </a>
                                    </li>
                                <?php
                                }
                                if (right_granted('students_manage'))
                                {
                                ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>c_student/create_card/student/<?php echo $row['student_id']; ?>/<?php echo $section_id; ?>">
                                            <i class="entypo-user"></i>
                                            <?php echo get_phrase('student_card');?>
                                        </a>
                                    </li>
                                    <!-- STUDENT EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_student_edit/<?php echo $row['student_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <!-- STUDENT DELETION LINK -->
                                    <li>
                                        <a href="<?php echo base_url(); ?>admin/update_parent/<?php echo $controller;?>/<?php echo  $row['student_id']; ?>/<?php echo $section_id;?>">
                                            <i class="entypo-trash"></i>
                                            <?php echo get_phrase('update_parent');?>/<?php echo get_phrase('guardian');?>
                                        </a>
                                    </li>
                                    <!-- STUDENT DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_manage_parent_account/<?php echo $row['student_id'];?>');">
                                            <i class="entypo-pencil"></i> <?php echo get_phrase('manage_parent_login');?>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                                    <li class="divider"></li>
                                    
                                    <?php
                        if($row['student_status']==11 || $row['student_status']==15)
                        {
                        	
                        }
                        else
                        {
                          if (right_granted('students_promote'))
                          {
                                    ?>
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/promote_section/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('promote');?>
                                            </a>
                                        </li>
                                       <!-- STUDENT chalan  LINK -->
                                        <?php
                            }

}





if($row['student_status']==11){
  $stu=3;
  $qur_val=$this->db->query("select * from ".get_school_db().".student_chalan_form where student_id=".$row['student_id']." and school_id=".$_SESSION['school_id']." and form_type=$stu")->result_array();
 //echo $this->db->last_query();

                                        if (right_granted('students_promote'))
                                        {
                                        ?>
                                            <li>
                                                <a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo  $row['student_id']; ?>/<?php echo $stu; ?>/2">
                                                    <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('promotion_chalan_form'); ?>
                                                </a>
                                            </li>
      <?php
      if($qur_val[0]['status']==4)
      {?>
	  	
	                                      
                                            <li>
                                                <a href="<?php echo base_url(); ?>class_chalan_form/view_print_chalan/<?php echo  $qur_val[0]['s_c_f_id']; ?>/<?php echo $stu;  ?>">
                                                    <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('print_promotion_chalan'); ?>
                                                </a>
                                            </li>
                                            <?php
 	} 
 	?>
 	 <li>
                                                <a href="<?php echo base_url(); ?>c_student/cancel_promotion/<?php echo $row['student_id']; ?>">
                                                    <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('cancel_promotion_request'); ?>
                                                </a>
                                            </li>
 <?php	
} 
}
elseif($row['student_status']==15)
    $stu=4;
  
$qur_val=$this->db->query("select s_c_f_id from ".get_school_db().".student_chalan_form where student_id=".$row['student_id']." and form_type=$stu")->result_array();

 ?>
                                                <li>
                                                    <a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo  $row['student_id']; ?>/<?php echo $stu; ?>">
                                                        <i class="entypo-trash"></i>
                                                        <?php echo get_phrase('demotion_chalan_form'); ?>
                                                    </a>
                                                </li>
                                                
                                                <li>
                                                    <a href="<?php echo base_url(); ?>class_chalan_form/view_print_chalan/<?php echo  $qur_val[0]['s_c_f_id']; ?>/<?php echo $stu;  ?>">
                                                        <i class="entypo-trash"></i>
                                                        <?php echo get_phrase('print_demotion_chalan'); ?>
                                                    </a>
                                                </li>
                                                <?php 
                                            }//promote student right
//} ?>                                            
                                            <?php
                                            if (right_granted('students_delete'))
                                            {
                                            ?>
                                                    <li>
                                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>c_student/withdraw/<?php echo $row['student_id']; ?>');">
                                                            <i class="entypo-trash"></i>
                                                            <?php echo get_phrase('withdraw');?>
                                                        </a>
                                                    </li>
                                                <?php
                                            }
                                            if (right_granted('students_manage'))
                                            {
                                            ?>
                                                    <li>
                                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>transfer_student/student_transfer_form/<?php echo $row['student_id']; ?>/<?php echo $section_id;?>');">
                                                            <i class="entypo-trash"></i>
                                                            <?php echo get_phrase('transfer_student');?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>payments/payment_listing/<?php echo  $row['student_id']; ?>/<?php echo $section_id;?>">
                                                            <i class="entypo-trash"></i>
                                                            <?php echo get_phrase('student_chalan_detail'); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo  $row['student_id']; ?>/<?php echo 10; ?>/2">
                                                            <i class="entypo-trash"></i>
                                                            <?php echo get_phrase('custom_challan_form'); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>c_student/student_m_discount/<?php echo  $row['student_id']; ?>/<?php echo $section_id;?>">
                                                            <i class="entypo-trash"></i>
                                                            <?php echo get_phrase('student_monthly_discount'); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo base_url(); ?>c_student/student_m_installment/<?php echo  $row['student_id']; ?>/<?php echo $section_id;?>">
                                                            <i class="entypo-trash"></i>
                                                            <?php echo get_phrase('student_installment'); ?>
                                                        </a>
                                                    </li>
                                        <?php
                                        }
                                        ?>
                        </ul>
                    </div>
                </div>
                <?php
                }
                ?>
            </td>
            <?php if($row['account_status']==0){
  
  
  
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
$(document).ready(function() {
    $('#stud_info_tbl').DataTable({

        "aLengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "bStateSave": true
    });


});

$(".dataTables_wrapper select").select2({


    minimumResultsForSearch: -1


});
</script>

<?php
}
?>
