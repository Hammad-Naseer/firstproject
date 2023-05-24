<style>
.ulli {
    margin-bottom: 0px;
}

.ulli li {
    list-style: none;
    display: inline;
    padding-right: 20px;
}
</style>
<?php
$leave_categ_id = $_POST['leave_categ_id'];
$staff_id = $_POST['staff_select'];
$section_query="";
$class_query="";

$date_query="";
$start_date='';
$end_date='';
$start_date=$_POST['start_date'];
$end_date=$_POST['end_date'];
if($start_date!='')
{
	$start_date_arr=explode("/",$start_date);
	$start_date=$start_date_arr[2].'-'.$start_date_arr[0].'-'.$start_date_arr[1];
}
if($end_date!='')
{
	$end_date_arr=explode("/",$end_date);
	$end_date=$end_date_arr[2].'-'.$end_date_arr[0].'-'.$end_date_arr[1];
}
if($start_date!='')
{
	$date_query=" AND ls.start_date >= '".$start_date."'";
}
if($end_date!='')
{
	$date_query=" AND ls.end_date <= '".$end_date."'";
}
if($start_date!='' && $end_date!='')
{
	$date_query=" AND ls.start_date >= '".$start_date."' AND ls.end_date <= '".$end_date."' ";
}

if(isset($leave_categ_id) && $leave_categ_id > 0)
    {
        $leave_query = " AND lc.leave_category_id = $leave_categ_id";   
    }
if(isset($staff_id) && $staff_id > 0)
    {
        $staff_query = " AND s.staff_id = $staff_id";   
    }

$q="SELECT ls.*,lc.name as leave_categ_name, s.name as staff_name,d.title as designation, s.employee_code as employee_code
 FROM ".get_school_db().".leave_staff ls
 INNER join ".get_school_db().".staff s
 ON ls.staff_id=s.staff_id
INNER join ".get_school_db().".leave_category lc
On ls.leave_category_id=lc.leave_category_id
INNER JOIN ".get_school_db().".designation d 
ON s.designation_id=d.designation_id
WHERE ls.school_id=".$_SESSION['school_id']. $date_query . $staff_query . $leave_query . " 
 ORDER BY ls.leave_staff_id asc ";
$leaves=$this->db->query($q)->result_array();

?>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable" id="admin_ajax_get_leave_staff">
        <thead>
            <tr>
                <th style="width:34px;">
                    <div><?php echo get_phrase('s_no');?></div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('leave_detail');?>
                    </div>
                </th>
                <th style="width: 94px;">
                    <div>
                        <?php echo get_phrase('action');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1;foreach($leaves as $row):?>
            <tr>
                <td>
                    <?php echo $count++;?>
                </td>
                <td>
                    <div class="myttl">
                        <?php echo $row['staff_name'].'<span style="font-size:12px;"> ('.$row['designation'].') - Emp-Code: '.$row['employee_code'];?></span>
                    </div>
                    <div><strong><?php echo get_phrase('leave_type');?>: </strong>
                        <?php
                            echo $row['leave_categ_name'];?>
                    </div>
                    <div><strong><?php echo get_phrase('from');?>: </strong>
                        <ul class="ulli" style="    display: inline; padding-left: 0px;">
                            <li>
                                <?php $start_date= $row['start_date'];
                            $d=explode("-",$start_date);
                            echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                             ?>
                            </li>
                            <strong> <?php echo get_phrase('to');?>: </strong>
                            <li>
                                <?php 
                            $end_date=$row['end_date'];
                            $d=explode("-",$end_date);
                            echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                             ?>
                            </li>
                        </ul>
                    </div>
                    <div><strong> <?php echo get_phrase('request_date');?>:</strong>
                        <?php $request_date=$row['request_date'];
                            $d=explode("-",$request_date);
                            echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                             ?>
                    </div>
                    <div>
                        <strong><?php echo get_phrase('process_date');?>: </strong>
                        <?php if($row['process_date'] > 0){ $process_date=$row['process_date'];
       $d=explode("-",$process_date);
echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));                     
                             } ?>
                    </div>
                    <div><strong><?php echo get_phrase('reason');?>: </strong>
                        <?php echo $row['reason'];
                            if($row['proof_doc']!=""){ ?> <a href="<?php echo display_link($row['proof_doc'],'leaves_staff');?>" target="_blank"><span class="glyphicon glyphicon-download-alt"></span></a>
                        <?php }
                            ?>
                    </div>
                </td>
                <td>
                <?php
                if (right_granted(array('staffleave_manage', 'staffleave_delete')))
                {
                ?>
                    <div class="btn-group">
                        <?php if($row['status']==0){ ?>
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <?php echo get_phrase('action');?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <?php 
                            if (right_granted(array('staffleave_manage', 'staffleave_delete')))
                            {?>
                                <li>
                                    <a href="#" onclick="confirm_modal_approve('<?php echo base_url();?>leave_staff/manage_leaves_staff/approve/<?php echo $row['leave_staff_id'];?>');">
                                        <i class="entypo-check"></i>
                                        <?php echo get_phrase('approve');?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <!-- DENY LINK -->
                                <li>
                                    <a href="#" onclick="confirm_modal_deny('<?php echo base_url();?>leave_staff/manage_leaves_staff/reject/<?php echo $row['leave_staff_id'];?>');">
                                        <i class="fas fa-ban"></i>
                                        <?php echo get_phrase('reject');?>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_leave_staff/<?php echo $row['leave_staff_id'];?>');">
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('edit');?>
                                    </a>
                                </li>
                            <?php 
                            }
                            if (right_granted('staffleave_delete'))
                            { 
                            ?>
                                <li>
                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>leave_staff/manage_leaves_staff/delete/<?php echo $row['leave_staff_id'];?>/<?php echo $row['proof_doc'];?>');">
                                        <i class="entypo-trash"></i>
                                        <?php echo get_phrase('delete');?>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                        <?php
                                } elseif($row['status']==1) { ?>
                            <div style="color:#19b62a !important;"><?php echo get_phrase('approved');?></div>
                            <?php }
                               elseif($row['status']==2) { ?>
                            <div style="color:#D30003;"><?php echo get_phrase('rejected');?></div>
                            <?php }
                                
                                
                                 ?>
                    </div>
                <?php
                }
                ?>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <script>
    $(document).ready(function() {
        $('#admin_ajax_get_leave_staff').DataTable({

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
