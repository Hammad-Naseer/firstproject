<?php
$staff_query="";
$date_query="";
if(isset($staff_id)&& ($staff_id) > 0)
{
    $staff_query=" AND se.staff_id=".$staff_id;
}
if($start_date!='')
{
	$date_query=" AND evaluation_date >= '".$start_date."'";
}
if($end_date!='')
{
	$date_query=" AND evaluation_date <= '".$end_date."'";
}
if($start_date!='' && $end_date!='')
{
	$date_query=" AND evaluation_date >= '".$start_date."' AND evaluation_date <= '".$end_date."' ";
}
$q="SELECT se.*,s.name as staff_name FROM ".get_school_db().".staff_evaluation se
INNER JOIN ".get_school_db().".staff s
ON se.staff_id=s.staff_id
 WHERE se.school_id=".$_SESSION['school_id']." ".$staff_query . $date_query."";
$query=$this->db->query($q)->result_array();
?>
    <table class="table table-striped table-bordered" id="get_staff_eval_tbl" data-step="3" data-position='top' data-intro="staff evalution record">
        <thead>
            <tr>
                <th style=" width:54px !important;"><?php echo get_phrase('s_no');?></th>
                <th>
                    <?php echo get_phrase('details');?>
                </th>
             
                <th style="width:94px;">
                    <?php echo get_phrase('options');?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1;foreach($query as $row){
                        ?>
            <tr>
                <td>
                    <?php echo $count++;?>
                </td>
                <td>
                
                <div class="myttl"><?php echo $row['staff_name'];?></div>
                <div><strong><?php echo get_phrase('remarks');?>:</strong>  <?php echo $row['remarks'];?></div>       
                <div><strong><?php echo get_phrase('answer');?>:</strong> <?php echo $row['answers'];?></div>       
                <div><strong><?php echo get_phrase('evaluation_date');?>:</strong> <?php echo convert_date($row['evaluation_date']);?></div>       
                  
                
                
                
                
                
                </td>
             
                <td>
                    <?php 
                    if (right_granted(array('staffevaluationsettings_delete', 'staffevaluationsettings_manage')))
                    {?>
                    <div class="btn-group" data-step="4" data-position='left' data-intro="staff evalution view  / edit / delete options">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <?php echo get_phrase('action');?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <!-- EDITING LINK -->
                            <?php 
                            if (right_granted('staffevaluationsettings_view'))
                            {?>
                                <li>
                                    <a href="<?php echo base_url();?>staff_evaluation/view_evaluation_answers/<?php echo $row['staff_eval_id'];?>/<?php echo $row['evaluation_date'];?>/<?php echo $row['staff_eval_id'];?>/<?php echo $row['staff_id'];?>/<?php echo $start_date;?>/<?php echo $end_date;?>">
                                        <i class="fa fa-eye"></i>
                                        <?php echo get_phrase('view');?>
                                    </a>
                                </li>
                            <?php
                            }
                            if (right_granted('staffevaluationsettings_manage'))
                            {
                            ?>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo base_url();?>staff_evaluation/view_staff_add/<?php echo $row['staff_eval_id'];?>/<?php echo $row['evaluation_date'];?>/<?php echo $row['staff_eval_id'];?>/<?php echo $row['staff_id'];?>/<?php echo $start_date;?>/<?php echo $end_date;?>">
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('edit');?>
                                    </a>
                                </li>
                            <?php 
                            }
                            if (right_granted('staffevaluationsettings_delete'))
                            {?>
                                <li class="divider"></li>
                                <!-- DELETION LINK -->
                                <li>
                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>staff_evaluation/evaluation/delete/<?php echo $row['staff_eval_id'];?>/<?php echo $row['attachment'];?>');">
                                        <i class="entypo-trash"></i>
                                        <?php echo get_phrase('delete');?>
                                    </a>
                                </li>
                            <?php 
                            }?>
                        </ul>
                    </div>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <script>
    $(document).ready(function() {
        $('#get_staff_eval_tbl').DataTable({

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
