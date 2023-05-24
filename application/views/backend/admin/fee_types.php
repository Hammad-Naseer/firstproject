<?php  
 if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<script>
$(window).on("load" , function() {
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
               <?php echo get_phrase('fee_types'); ?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12">
<table class="table table-bordered table_export" data-step="2" data-position='top' data-intro="fee types records" width="100%">
    <thead>
        <tr>
            <td style="width:34px;">#</td>
            <td><?php echo get_phrase('title'); ?></td>
            <td style="width:95px;"><?php echo get_phrase('options'); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    
        $school_id  = $_SESSION['school_id'];
        $login_type = $_SESSION['login_type'];
        $n=0;
        foreach($fee_query as $row)
        { 
            
        $n++;
    ?>
        <tr>
            <td class="td_middle">
                <div>
                    <?php echo $n; ?>
                </div>
            </td>
            <td>
                <div class="myttl">
                    <?php echo $row['title'];?>
                </div>
                
                <div> <strong><?php echo get_phrase('status'); ?>: </strong>
                    <?php 
                        $stclass = '' ;   
                        if($row['status'] == 1)
                        {
                            $stclass = 'green';
                        }   
                        elseif($row['status'] == 0)
                        {
                            $stclass = 'red';
                        }  
                    ?>
                    <span class="<?php echo $stclass;?>">         
                    <?php echo status_active($row['status']);?>
                    </span>
                </div>
                
                <?php
                    $login_type = $_SESSION['login_type'];
                    if($login_type == 1)
                    {
                        if($row['school_id']!= $_SESSION['school_id'])
                        {
                            echo "<div><strong>" .get_phrase('added_by').":</strong> ".$row['school_name']."</div>";
                        }
                    }
                    ?>
                
                    
            </td>
            <td class="td_middle">
                <?php
                if (right_granted(array('feetype_delete', 'feetype_manage'))) {
                    if (($school_id == $row['school_id']) || ($login_type == 1)) {

                        ?>
                        <div class="btn-group" data-step="3" data-position='left' data-intro="fee type edit / delete options">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <?php echo get_phrase('action'); ?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <?php
                                if (right_granted('feetype_manage')) {
                                    if (($school_id == $row['school_id']) || ($login_type == 1)) {
                                        ?>
                                        <li>
                                            <a href="#"
                                               onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/fee_add_edit/<?php echo $row['fee_type_id']; ?>');">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit'); ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                if (right_granted('feetype_delete')) {
                                    if (($school_id == $row['school_id']) || ($login_type == 1)) {
                                        ?>
                                        <li class="divider"></li>
                                        <!-- STUDENT DELETION LINK -->
                                        <li>
                                            <a href="#"
                                               onclick="confirm_modal('<?php echo base_url(); ?>fee_types/fee_types_c/delete/<?php echo $row['fee_type_id']; ?>');">
                                                <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete'); ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <?php
                    }
                }
                ?>
            </td>
        </tr>
        
    <?php 
            
        } 
    
    ?>
        
    </tbody>
</table>
</div>

<script>
    <?php if(right_granted('feetype_manage')){ ?>
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/fee_add_edit/")';
        var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new fee type' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_fee_type');?></a>";    
    <?php } ?>
</script>

