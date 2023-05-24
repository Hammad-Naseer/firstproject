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
                   <?php echo get_phrase('leave_category'); ?>
            </h3>
        </div>
    </div>

        <div class="row">
            <div class="col-md-12">
                <table data-step="2" data-position='top' data-intro="leave category records" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
                            <thead>
                                <tr>
                                    <th style="width:34px;">
                                        #
                                    </th>
                                    <th>
                                      
                                            <?php echo get_phrase('title');?>
                                      
                                    </th>
                                  <th style="width:94px;">
                                      <?php echo get_phrase('options');?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1;foreach($leaves as $row):?>
                                <tr>
                                    <td class="td_middle">
                                        <?php echo $count++;?>
                                    </td>
                                    <td>
                                        <?php echo $row['name'];?>
                                    </td>
                                    <td class="td_middle">
                                        <?php 
                                        if (right_granted(array('manageleavecategory_manage', 'manageleavecategory_delete')))
                                        {?>
                                        <div class="btn-group" data-step="3" data-position='left' data-intro="category delete / edit option">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                                <?php if (right_granted('manageleavecategory_manage'))
                                                {?>
                                                <!-- EDITING LINK -->
                                                <li>
                                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_leave_category/<?php echo $row['leave_category_id'];?>');">
                                                        <i class="entypo-pencil"></i>
                                                        <?php echo get_phrase('edit');?>
                                                    </a>
                                                </li>
                                                <?php }?>
                                                <?php if (right_granted('manageleavecategory_delete'))
                                                {?>
                                                <li class="divider"></li>
                                                <!-- DELETION LINK -->
                                                <li>
                                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>leave/manage_leaves/delete/<?php echo $row['leave_category_id'];?>');">
                                                        <i class="entypo-trash"></i>
                                                        <?php echo get_phrase('delete');?>
                                                    </a>
                                                </li>
                                                <?php }?>
                                            </ul>
                                        </div>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
            </div>
            <div class="tab-pane box" id="add" style="padding: 5px">
            </div>
            <!----CREATION FORM ENDS--->
        </div>
    </div>
</div>

<!--Datatables Add Button Script-->
<?php if(right_granted('manageleavecategory_manage')){ ?>
<script>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_category/")';
    var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new category' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_category');?></a>";    
</script>
<?php } ?>
