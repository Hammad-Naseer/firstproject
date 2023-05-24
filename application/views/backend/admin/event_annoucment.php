<?php
  if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
?>
<script>
$(window).on("load",function() {
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
            <h3 class="system_name inline">
                <?php echo get_phrase('events_announcement'); ?>
            </h3>
        </div>
    </div>
        <div class="col-lg-12 col-sm-12">
            <table class="table table-bordered table_export" data-step="2" data-position="top" data-intro="events announcement record">
                <thead>
                    <tr>
                        <th style="width:34px">
                            <div>#</div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('details');?>
                            </div>
                        </th>
                        <th style="width:94px">
                            <div>
                                <?php echo get_phrase('options');?>
                            </div>
                        </th>
                    </tr>
                </thead>
                    <tbody>
                    <?php 
                        $r=0;   
                        foreach($event_annoucement as $row)
                        {
                            $r++;
                    ?>
                        <tr>
                            <td class="td_middle">
                                <?php echo $r;?>
                            </td>
                            <td>
                                <div class="myttl">
                                    <?php echo $row['event_title'];?>
                                </div>
                                <div><strong>
                                
                                <?php echo get_phrase('details'); ?>
                                : </strong>
                                    <?php echo $row['event_details'];?>
                                </div>
                                <div><strong>                
                                <?php echo get_phrase('start_date'); ?>
                                : </strong>
                                    <?php echo date_view($row['event_start_date']);?>
                                </div>
                                <div><strong>                
                                <?php echo get_phrase('end_date'); ?>
                                : </strong>
                                    <?php echo date_view($row['event_end_date']);?>
                                </div>
                                <div><strong>
								<?php echo get_phrase('status'); ?>
                                 : </strong>
                                    <?php 
                                        if($row['active_inactive'] == '1')
                                        {
                                            echo "<b class='text-success'>Active</b>";
                                        }else{
                                            echo "<b class='text-danger'>Inactive</b>";
                                        }
                                    ?>
                                </div>
                                <div><strong>
								<?php echo get_phrase('event_status'); ?>
                                 : </strong>
                                    <?php 
                                        if($row['event_status'] == '1')
                                        {
                                            echo "<b class='text-success'>Event Assigned</b>";
                                        }else if($row['event_status'] == '2')
                                        {
                                            echo "<b class='text-danger'>Event Expired</b>";
                                        }else{
                                            echo "<b class='text-danger'>Not Assigned</b>";
                                        }
                                    ?>
                                </div>
                            </td>
                            <td class="td_middle">
                                
                                <div class="btn-group" data-step="3" data-position="left" data-intro="Events Assign / edit / delete">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        <li>
                                            <?php 
                                                if($row['event_status'] == '1' || $row['event_status'] == '2'){
                                            ?>
                                            <a href="<?=base_url().'event_annoucments/event_announcement_detail/'.str_encode($row['event_id'])?>">
                                                <i class="entypo-eye"></i>
                                                <?php echo get_phrase('view_assign_report');?>
                                            </a>
                                            <?php }else{ ?>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_assign_event_annouc/<?php echo $row['event_id'];?>');">
                                                <i class="entypo-user"></i>
                                                <?php echo get_phrase('assign');?>
                                            </a>
                                            <?php } ?>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_event_annouc/<?php echo $row['event_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <!-- DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>event_annoucments/events_program/delete/<?php echo $row['event_id'];?>');">
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
    </div>
    <!--Datatables Add Button Script-->
    <script>
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_event_annouc/")';
        var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='press this button to add new event' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_event_announcement');?></a>";    
    </script>

