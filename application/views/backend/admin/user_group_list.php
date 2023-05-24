<?php
if (right_granted(array('systemadministration_manage')))
{

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
$( window ).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
    
</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline capitalize">
            <?php echo get_phrase('user_group');?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-sm-12">
    <table class="table table-striped table-bordered table_export" data-step="2" data-position="left" data-intro="user group records">
    <thead>
    <tr>
        <th>
            <?php echo get_phrase('Title ');?>
        </th>
        <th>
            <?php echo get_phrase('Type ');?>
        </th>
        <th style="width:120px !important;">
            <?php echo get_phrase('status ');?>
        </th>
        <th style="width:120px !important;">
            <?php echo get_phrase('Options');?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($user_group as $row){?>
    <tr>
        <td>
            <?php echo $row['title'];?>
        </td>
        <td>
            <?php echo get_user_group_type($row['type']);?>
        </td>
        <td>
            <?php
            if($row['status']==1)
            {
            ?>
                <span style="color:green"> <?php echo get_phrase('active');?> </span>
	
            <?php	
            }else{
            ?>
                <span style="color:red"> <?php echo get_phrase('inactive');?> </span>
            	
            <?php	
            };
            ?>
        </td>
        <td class="td_middle">
            <div class="btn-group" data-step="3" data-position="left" data-intro="user group options: edit / delete / assign rights">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"> <?php echo get_phrase('action');?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                    
                    <li>
                        <a href="<?php echo base_url();?>user/assign_rights/<?php echo str_encode($row['user_group_id']);?>/<?php echo str_encode($row['type']);?>">
                        <i class="fas fa-clipboard-check" style="font-size:15px;"></i>
                            <?php echo get_phrase('assign_rights');?>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo base_url();?>user/view_group_users/<?php echo str_encode($row['user_group_id']);?>">
                            <i class="fas fa-eye" style="font-size:15px;"></i>
                                <?php echo get_phrase('view_group_users');?>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_edit_user_type/<?php echo str_encode($row['user_group_id']);?>');">
                            <i class="entypo-pencil"></i>
                            <?php echo get_phrase('edit');?>
                        </a>
                    </li>
                    
                    <li class="divider"></li>
                   
                    <li>
                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>user/save_group/delete/<?php echo str_encode($row['user_group_id']);?>');">
                            <i class="entypo-trash"></i>
                            <?php echo get_phrase('delete');?>
                        </a>
                    </li>
                    
                </ul>
            </div>
        </td>
    </tr>
    <?php }?>
    </tbody>
</table>
</div>

<?php
}
?>


<script>
       var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_edit_user_type")';
       var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new user group' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_user_group');?></a>";    
</script>
