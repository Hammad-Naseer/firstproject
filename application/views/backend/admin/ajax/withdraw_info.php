<style>
    .ptag 	{
        margin: 0px;
        padding: 0px;
    }
    .fa-mobile{
        font-size:24px;
    }
    .emer{
        color:red;
    }
    .emer_green{
        color:green;
    }
    .emer_blue{
        color:blue;
    }
</style>
<?php
$school_id=$_SESSION['school_id'];
$date_query="";
if($start_date!='')
{
    $date_query=" AND sw.request_date >= '".$start_date."'";
}
if($end_date!='')
{
    $date_query=" AND sw.request_date <= '".$end_date."'";
}
if($start_date!='' && $end_date!='')
{
    $date_query=" AND sw.request_date >= '".$start_date."' AND sw.request_date <= '".$end_date."' ";
}

if($section_id!=''){
    $quer_where=" AND cs.section_id=$section_id";
}

$quer="select  s.* , cs.title as section_name, cc.name as class_name, d.title as department_name,sw.status as status,sw.s_c_f_id as s_c_f_id,chf.status as challan_status,sw.confirm_by,sw.confirm_date,sw.std_withdraw_id,sw.requested_by,sw.request_date
       from ".get_school_db().".student s inner join ".get_school_db().".student_withdrawal sw ON s.student_id=sw.student_id inner join ".get_school_db().".student_chalan_form chf
       ON sw.s_c_f_id=chf.s_c_f_id inner join ".get_school_db().".class_section cs on cs.section_id=sw.section_id inner join ".get_school_db().".class cc on cc.class_id=cs.class_id 
       inner join ".get_school_db().".departments d on d.departments_id=cc.departments_id  where  s.school_id=".$_SESSION['school_id']." AND chf.is_cancelled = 0 $date_query $quer_where ORDER BY sw.request_date desc $status $acd_id ";
$students=$this->db->query($quer)->result_array();
?>

<br /><br />
<table class="table table-bordered table_export">
    <thead>
    <tr>
        <th style="width:34px;"><div><?php echo get_phrase('s_no');?></div></th>
        <th  style="width:100px;"><div><?php echo get_phrase('photo');?></div></th>
        <th><div><?php echo get_phrase('details');?></div></th>
        <th style="width:94px;"><div><?php echo get_phrase('options');?></div></th>
    </tr>
    </thead>
    <tbody>

    <?php  $r=0;
    foreach($students as $row)
    {
        $r++;
    ?>
        <tr>
            <td class="td_middle"><?php echo $r; ?></td>
            <td>
                <img src="
                <?php
                if($row['image']=='')
                {
                    echo  base_url().'/uploads/default.png';
                }else
                {
                    echo  display_link($row['image'],'student');
                }
                ?>" 
                class="img-circle" width="30" />
            </td>
            <td>

                <div class="myttl"><?php echo $row['name'];?><span style="display: none;"><?php echo  $row['system_id']; ?></span><span style="font-size:12px;"> (<?php echo get_phrase('roll');?>#:<?php echo $row['roll'];?> )</span></div>

                <div>
                    <strong> <?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>
                    <ul class="breadcrumb breadcrumb2">
                        <li><?php echo $row['department_name'];?></li>
                        <li><?php echo $row['class_name'];?></li>
                        <li><?php echo $row['section_name'];?></li>
                    </ul>
                </div>

                <div>
                    <strong><?php echo get_phrase('withdraw_status');?>: </strong><?php
                    echo approve_withdraw_val($row['status']);
                    ?>
                </div>
                <div>
                    <strong><?php echo get_phrase('challan_status');?>: </strong><?php
                    $status_val=$row['challan_status'];
                    echo withdraw_challan_status($status_val);
                    ?>
                </div>

                <div>
                    <?php if($row['requested_by']>0)
                    {?>
                        <strong><?php echo get_phrase('requested_by');?>:</strong>
                        <?php

                        $user_req=get_user_info($row['requested_by']);
                        echo  $user_req[0]['name'];
                        ?>
                        <br>
                        <strong><?php echo get_phrase('request_date');?>:</strong>
                        <?php
                        echo convert_date($row['request_date']);
                    }
                    ?>
                </div>
                <?php if($row['confirm_by'] > 0) { ?>
                <div>
                        <strong><?php echo get_phrase('confirm_by');?>:</strong>
                        <?php

                        $user_req=get_user_info($row['confirm_by']);
                        echo  $user_req[0]['name'];
                        ?>
                        <br>
                        <strong><?php echo get_phrase('confirm_date');?>:</strong>
                        <?php echo date_slash($row['confirm_date']); ?>
                </div>
                <div>
                    <strong><?php echo get_phrase('withdarawl_reason');?>:</strong>
                    <?php echo $row['std_withdarwal_reason'];?>
                </div>
                <?php } ?>
            </td>

            <td class="td_middle">

                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        <?php if($row['status']==25){ ?>
                            <li style="color:white !important">
                                <i class="entypo-user"></i>
                                <?php echo get_phrase('withdrawal_completed');?>
                            </li>
                        <?php } if($row['student_status']==25){ ?>
                            <li>
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>c_student/re_admission/<?php echo $row['student_id'].'/'.$row['s_c_f_id'];?>');">
                                    <i class="entypo-pencil"></i><?php echo get_phrase('readmission');?>
                                </a>
                            </li>
                        <?php } ?>

                        <?php
                            $stu=6;
                            if($row['challan_status']==4)
                            {
                        ?>
                            <li>
                                <a href="<?php echo base_url(); ?>class_chalan_form/view_print_chalan/<?php echo  $row['s_c_f_id']; ?>/<?php echo $stu;  ?>"  >
                                    <i class="entypo-trash"></i>
                                    <?php echo get_phrase('print_promotion_chalan'); ?>
                                </a>
                            </li>
                        <?php }if($row['challan_status']<5){ ?>
                            <li>
                                <a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo  str_encode($row['student_id']); ?>/<?php echo $stu;  ?>/5"  >

                                    <i class="entypo-trash"></i>
                                    <?php
                                    if($status_val==4)
                                    {
                                        echo get_phrase('receive_chalan');
                                    }
                                    else
                                    {
                                        echo get_phrase('manage_chalan');
                                    }
                                    ?>
                                </a>
                            </li>
                            <li>
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>c_student/withdraw_cancel/<?php echo $row['student_id'].'/'.$row['s_c_f_id'];?>/<?php echo $row['std_withdraw_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('cancel_withdraw');?>
                                </a>
                            </li>
                        <?php } if($row['student_status']>21 && $row['student_status']<25 && $row['challan_status']==5){ ?>
                            <li>
                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/approve_withdraw/<?php echo $row['std_withdraw_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('withdraw_status');?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </td>


            <?php if($row['account_status']==0){

                $account_status=1;

            }else{
                $account_status=0;
            }
            ?>
        </tr>
    <?php }?>
    </tbody>
</table>



<!-- DATA TABLE EXPORT CONFIGURATIONS -->
<script>
    $(".table_export").DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
</script>