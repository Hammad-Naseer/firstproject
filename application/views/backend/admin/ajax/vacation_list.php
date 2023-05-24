<?php $status='';
$date_query="";
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
	$date_query=" AND start_date >= '".$start_date."'";
}
if($end_date!='')
{
	$date_query=" AND start_date <= '".$end_date."'";
}
if($start_date!='' && $end_date!='')
{
	$date_query=" AND start_date >= '".$start_date."' AND start_date <= '".$end_date."' ";
}

 
  $qur_val="select * from ".get_school_db().".holiday
    WHERE school_id=".$_SESSION['school_id']." $date_query Order By start_date DESC";
  
  
  $qr_arry=$this->db->query($qur_val)->result_array();
?>
<table class="table table-bordered table-responsive" id="admin_ajax_get_vacation_list" data-step="5" data-position='top' data-intro="Filter Record">
    <thead>
        <tr>
            <th style="width:34px;"><?php echo get_phrase('s_no');?></th>
            <th><?php echo get_phrase('title');?></th>
            <th style="width:100px !important;"><?php echo get_phrase('options');?></th>
        </tr>
    </thead>
    <tbody>
     <?php
if(sizeof($qr_arry)>0)
{
  $i=1;
  
  foreach($qr_arry as $rr)
  {
  	$status=$rr['status'];
  ?>
            <tr>
                <td>
                    <?php echo $i++;  ?>
                </td>
                <td>
                    <div class="myttl">
                        <?php echo $rr['title']; ?>
                    </div>
 
                    <div><strong><?php echo get_phrase('start_date');?>: </strong>
                        <?php echo convert_date($rr['start_date']); ?>
                    </div>
                    <div><strong> <?php echo get_phrase('end_date');?>: </strong>
                        <?php echo convert_date($rr['end_date']); ?>
                    </div>
                </td>
                <td>
                    <?php   
 //$date_range=get_term_date_range();
if(($status!=1)){
?>
                    <?php 
                    if (right_granted(array('vacationsettings_manage', 'vacationsettings_delete')))
                    {?>
                    <div class="btn-group" data-step="6" data-position='left' data-intro="vacation data edit/delete">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <?php echo get_phrase('action');?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <!-- EDITING LINK -->
                            <?php 
                            if (right_granted('vacationsettings_manage'))
                            {
                            ?>
                            <li>
                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_vacation/<?php echo $rr['holiday_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('edit');?>
                                </a>
                            </li>
                            <?php 
                            }?>
                            <?php 
                            if (right_granted('vacationsettings_delete'))
                            {?>
                            <li class="divider"></li>
                            <!-- DELETION LINK -->
                            <li>
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>vacation/vacation_settings/delete/<?php echo $rr['holiday_id'];?>');">
                                    <i class="entypo-trash"></i>
                                    <?php echo get_phrase('delete');?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php }}?>
                </td>
            </tr>
            <?php } ?>
    </tbody>
</table>
<?php }?>
<script>
$(document).ready(function() {
    $('#admin_ajax_get_vacation_list').DataTable({

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
