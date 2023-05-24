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
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                 <?php echo get_phrase('student_category'); ?>
            </h3>
        </div>
    </div>
    
 
    <div class="row">
        <div class="col-md-12">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export" data-step="2" data-position='top' data-intro="student category records">
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
                    <?php $count = 1;foreach($stud_category as $row):?>
                    <tr>
                        <td class="td_middle">
                            <?php echo $count++;?>
                        </td>
                        <td>
                            <?php echo $row['title'];?>
                        </td>
                        <td class="td_middle">
                            <div class="btn-group" data-step="3" data-position='left' data-intro="student category delete / edit option">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_edit_student_category/<?php echo $row['student_category_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>student_category/manage_stud_category/delete/<?php echo $row['student_category_id'];?>');">
                                            <i class="entypo-trash"></i>
                                            <?php echo get_phrase('delete');?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        <div class="tab-pane box" id="add" style="padding: 5px">
        </div>
        </div>
    </div>
    
    <!--Datatables Add Button Script-->
<?php if(right_granted('locations_manage')){ ?>
<script>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_edit_student_category/add")';
    var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new student category' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_category');?></a>";    
</script>
<?php } ?>

