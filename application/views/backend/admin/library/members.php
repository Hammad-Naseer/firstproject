
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
                <?php echo get_phrase('members'); ?> 
            </h3>
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i>Interactive tutorial</b>
            </a>
        </div>
    </div>
    
    <form action="<?php echo base_url(); ?>library/members" method="post" data-step="2" data-position='top' data-intro="select this button to get record specific classes">
        <div class="row filterContainer" style="padding-top: 14px;margin:0px;">
            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                <select id="section_id1" class="selectpicker form-control wizard_validate" name="section_id" required="required" class="form-control" required="">
                            <?php echo section_selector();?>
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
    <div class="row">
        <div class="col-md-12">
            <?php if(isset($students_query)): ?>
                <table class="table table-bordered table_export" data-step="2" data-position='top' data-intro="students record">
                    <thead>
                        <tr>
                            <th style="width:30px;">#</th>
                            <th style="width:30px;"><?php echo get_phrase('picture');?></th>
                            <th><?php echo get_phrase('student_information');?></th>
                            <th style="width:94px;"><?php echo get_phrase('action');?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $j=$start_limit;
                        foreach($students_query as $row)
                        {
                        $j++;
                    ?>
                        <tr>
                            <td class="td_middle">
                                <?php  echo $j; ?>
                            </td>
                            <td class="td_middle">
                                <div>
                                    <img src="<?php if($row['image']==''){ echo  base_url().'/uploads/default.png'; }else{echo  display_link($row['image'],'student');} ?>" class="img-circle" width="30" />
                                </div>
                            </td>
                            <td>
                                <div class="myttl">
                                    <?php echo $row['name'];?><span style="display: none;"><?php echo  $row['system_id']; ?></span></div>
                                <div>
                                    <strong>   <?php echo get_phrase('roll_no');?>: </strong>
                                    <?php echo $row['roll'];?>
                                </div>
                                <div><strong><?php echo get_phrase('');?><?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>
                                    <ul class="breadcrumb breadcrumb2" style="padding:2px;">
                                        <li>
                                            <?php echo $row['department_name'];?> </li>
                                        <li>
                                            <?php echo $row['class_name'];?> </li>
                                        <li>
                                            <?php echo $row['section_name'];?> </li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="ptag">
                                        <strong><?php echo get_phrase('mobile_no');?>: </strong>
                                        <?php echo $row['mob_num'];?>
                                    </p>
                                </div>

                            </td>
                            <td class="td_middle">
                                <?php
                                    $check_user_exist = $this->db->where("user_id",$row['student_id'])->get(get_school_db().".library_members");
                                    if($check_user_exist->num_rows() > 0):
                                        echo "<b>Member</b>";
                                    else:    
                                ?>
                                    <buuton type="button" onclick='showAjaxModal("<?php echo base_url();?>modal/popup/add_member_modal/<?php echo $row['student_id'];?>")' class="btn btn-primary btn-sm">Add</buuton>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            <?php else: ?>
            <table class="table table-bordered cursor table-hover table_export" data-step="3" data-position='top' data-intro="classes records">
                <thead>
                    <tr>
                        <th style="width:34px;">
                            <div>#</div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('library_membership_id');?>
                            </div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('member');?>
                            </div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('status');?>
                            </div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('added_date');?>
                            </div>
                        </th>
                        <th>
                            <div>
                                <?php echo get_phrase('action');?>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $j=0;
                        foreach($members as $row):
                        $j++;
                    ?>
                    <tr>
                        <td class="td_middle">
                            <?php echo $j; ?>
                        </td>
                        <td>
                            <?php
                                echo $row['library_membership_id'];
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $std = student_name_section($row['user_id']);
                            ?>
                        </td>
                        <td>
                            <?php 
                                if($row['status'] == '1')
                                {
                                    echo "<span class='badge badge-success'>Active</span>";
                                }else if($row['book_type'] == '2')
                                {
                                    echo "<span class='badge badge-danger'>Inactive</span>";
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                echo convert_date(date('d-m-Y', strtotime($row['inserted_at'])));
                            ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" onclick='showAjaxModal("<?php echo base_url();?>modal/popup/add_member_modal/<?php echo str_encode($row['user_id']);?>/edit/<?php echo str_encode($row['library_member_id']);?>")'>Edit</button>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
