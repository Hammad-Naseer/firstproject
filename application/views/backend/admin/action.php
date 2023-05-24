<?php

if($this->session->flashdata('club_updated'))
{
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
}
?>

<script>  
$( window ).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
    
</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline capitalize">
            <!--  <i class="entypo-right-circled carrow">
                        </i>-->
            <?php echo get_phrase('actions'); ?>
        </h3>
        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_edit_action');" class="btn btn-primary pull-right"><i class="entypo-plus-circled"></i>
            <?php echo get_phrase('add_action');?>
        </a>
    </div>
</div>
<table class="table table-striped table-bordered datatable" id="table_action">
    <thead>
    <tr>
        <th style="width:50px">
            <div>
                <?php echo get_phrase('title ');?>
            </div>
        </th>
        <th style="width:50px">
            <div>
                <?php echo get_phrase('code ');?>
            </div>
        </th>
        <th style="width:50px">
            <div>
                <?php echo get_phrase('module ');?>
            </div>
        </th>
        <th style="width:70px">
            <div>
                <?php echo get_phrase('status');?>
            </div>
        </th>
        <th style="width:70px">
            <div>
                <?php echo get_phrase('options');?>
            </div>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($action as $row)
    {
//$status=$row['status'];?>
    <tr>
        <td>
            <?php echo $row['title'];?>
        </td>
        <td>
            <?php echo $row['code'];?>
        </td>
        <td>
            <?php echo module_name($row['module_id']);?>
        </td>
        <td>
            <?php if($row['status']==1){echo "Active";}else{echo "Inactive";};?>
        </td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"> <?php echo get_phrase('action'); ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                    <li>
                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_edit_action/<?php echo $row['action_id'];?>');">
                            <i class="entypo-pencil"></i>
                            <?php echo get_phrase('edit');?>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>module/saveaction/delete/<?php echo $row['action_id'];?>');">
                            <i class="entypo-trash"></i>
                            <?php echo get_phrase('delete');?>
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    
    <?php 
    }
    ?>
    </tbody>
</table>


<script>
$(document).ready(function() {
    $('#table_action').DataTable({

        "aLengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "bStateSave": true
    });
});
</script>