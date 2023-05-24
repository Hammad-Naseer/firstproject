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
               <?php echo get_phrase('discounts_type'); ?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <table class="table table-bordered table_export" data-step="2" data-position='top' data-intro="discount types records">
    <thead>
        <tr>
            <th style="width:34px;">
                <div>
                    <?php echo get_phrase('#');?>
                </div>
            </th>
            <th>
                <div>
                    <?php echo get_phrase('discount_type_detail');?>
                </div>
            </th>
            <th style="width:95px;">
                <div>
                    <?php echo get_phrase('options');?>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php

        $school_id=$_SESSION['school_id'];
        $login_type = $_SESSION['login_type'];

       $dis_qur = "SELECT dl.*, s.name as school_name FROM ".get_school_db().".discount_list dl
        INNER join ".get_school_db().".school_discount_list sdl on sdl.discount_id = dl.discount_id
        INNER join ".get_school_db().".school s on 
            dl.school_id = s.school_id
        WHERE sdl.school_id = ".$school_id." 
        ORDER BY dl.status desc, dl.title ASC";
        $discount = $this->db->query($dis_qur)->result_array();
               
    $i= 1 ;
    foreach($discount as $row):?>
        <tr>
            <td class="td_middle">
                <?php echo $i++ ; ?> </td>
            <td>
                <div class="myttl">
                    <?php echo $row['title'];?>  
                </div>
                <?php 
        
        $status_class= '';
        if($row['status']==0){
          $status_class = "red";
        }
        elseif($row['status']==1){  
        $status_class = "green";
        }
        
        ?>
                <div>
                    <strong><?php echo get_phrase('status'); ?>: </strong>
                    <span class="<?php echo $status_class; ?>">
                        <?php echo status_active($row['status']);  ?>
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
            if (right_granted(array('discounttype_delete', 'discounttype_manage')))

            {
                if (($school_id == $row['school_id']) || ($login_type == 1)) {?>
                <div class="btn-group" data-step="3" data-position='left' data-intro="fee type edit / delete options">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                       
                       <?php echo get_phrase('action'); ?>
                         <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        <?php
                        if (right_granted('discounttype_manage'))
                        {?>
                            <li>
                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/discount_add_edit/<?php echo $row['discount_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('edit');?>
                                </a>
                            </li>
                        <?php
                        }
                        if (right_granted('discounttype_delete'))
                        {?>
                            <li class="divider"></li>
                            <!-- STUDENT DELETION LINK -->
                            <li>
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>discount/discount_list/delete/<?php echo $row['discount_id'];?>');">
                                    <i class="entypo-trash"></i>
                                    <?php echo get_phrase('delete');?>
                                </a>
                            </li>
                        <?php
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
        <?php endforeach;?>
    </tbody>
    </table>
</div>

<script>
    <?php if(right_granted('discounttype_manage')){ ?>
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/discount_add_edit/")';
        var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new discount type' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('Add_discount_type');?></a>";    
    <?php } ?>
</script>
