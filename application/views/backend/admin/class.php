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
            <h3 class="system_name inline">
                <?php echo get_phrase('classes'); ?> 
            </h3>
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i>Interactive tutorial</b>
            </a>
        </div>
    </div>
    <form action="<?php echo base_url(); ?>departments/classes" method="post" data-step="2" data-position='top' data-intro="select this button to get record specific classes">
        <div class="row filterContainer" style="padding-top: 14px;margin:0px;">
            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                <select name="department_id" id="loc_country" class="form-control">
                    <?php echo department_option_list($this->input->post('department_id'));?>
                </select>
                <div class="err_div"></div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" id="btn_submit" style="padding:5px 20px !important; ">
                    <?php echo get_phrase('filter'); ?>
                </button>
                <a href="<?php echo base_url(); ?>departments/classes" <?php $val_val=$this->input->post('department_id');if(isset($val_val) && $val_val!=""){}else{?>style="display:none;"<?php } ?> class="btn btn-danger" id="btn_remove" style="padding:5px 5px !important; "><i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
            </div>
        </div>
    </form>
    <div class="row" style="margin-left: -30px;margin-right: -30px;">
        <div class="col-md-12">
            <table class="table table-bordered cursor table-hover table_export" data-step="3" data-position='top' data-intro="classes records">
                <thead>
                    <tr>
                        <th style="width:34px;">
                            <div>#</div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('class_details');?>
                            </div>
                        </th>
                        <th style="width:93px;">
                            <div>
                                <?php echo get_phrase('options');?>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $j=0;
                        foreach($class as $row):
                       $j++;?>
                    <tr>
                        <td class="td_middle">
                            <?php echo $j; ?>
                        </td>
                        <td>
                            <div class="myttl">
                                <?php echo $row['name'];
                            if($row['name_numeric']!="")
                            {
                                echo ' ('.$row['name_numeric'].')';
                            }?>
                            </div>
                            <div><strong><?php echo get_phrase('teacher'); ?> : </strong>
                                <?php
                            echo $row['staff_name'];
                            if($row['designation']!="")
                            {
                                echo " (".$row['designation'].") ";  
                            }
                            if($row['is_teacher']==1)
                            {
                                echo " (Teaching staff) ";
                            }
                            ?>
                            </div>
                            <div><strong><?php echo get_phrase('department'); ?>: </strong>
                                <?php
                                    $ary_data=array('departments_id'=>$row['departments_id'],'school_id'=>$_SESSION['school_id']);
                                    $rec_data=$this->db->get_where(get_school_db().'.departments',$ary_data)->result_array();
                                    echo $rec_data[0]['title']; 
                                ?>
                            </div>
                            <div>
                                <strong><?php echo get_phrase('detail'); ?> : </strong>
                                <?php echo $row['description'];?> 
                            </div>
                            <div>
                                <strong><?php echo get_phrase('Strength'); ?> : </strong>
                                <?php echo $row['strength'];?> 
                            </div>
                        </td>
                        <td class="td_middle">
                        <?php 
                        if (right_granted(array('classes_manage', 'classes_delete')))
                        {?>
                            <div class="btn-group" data-step="4" data-position='left' data-intro="classes delete / edit option">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?php echo get_phrase('action'); ?><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <!-- EDITING LINK -->
                                    <?php
                                    if (right_granted('classes_manage'))
                                    {
                                    ?>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_class/<?php echo $row['class_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <?php
                                    }
                                    if (right_granted('classes_delete'))
                                    {?>
                                    <li class="divider"></li>
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>departments/classes/delete/<?php echo $row['class_id'];?>');">
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
                        ?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $("#btn_submit").click(function(e) {
            $(".err_div").html("");
            if ($("#loc_country").val() == "") {
                e.preventDefault();
                $(".err_div").html("<?php echo get_phrase('please_select_department'); ?>");
    
            }
        });
        $("#loc_country").change(function() {
            $(".err_div").html("");
        });
        
        <!--Datatables Add Button Script-->
        <?php if(right_granted('classes_manage')){ ?>
        
            var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/add_class_modal/")';
            var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new class' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_class');?></a>";    
        
        <?php } ?>
    </script>