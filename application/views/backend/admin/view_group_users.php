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
            <?php echo get_phrase('group_users');?>
        </h3>
    </div>
</div>

<div class="col-lg-12 col-sm-12">
    <table class="table table-bordered table_export" data-step="1" data-position="left" data-intro="user group records">
    <thead>
    <tr>
        <th>
            <?php echo get_phrase('Name ');?>
        </th>
        <th style="width:120px !important;">
            <?php echo get_phrase('Designation');?>
        </th>
        <th style="width:120px !important;">
            <?php echo get_phrase('action');?>
        </th>
    </tr>
    </thead>
    <tbody>
        <?php 
            foreach($staff as $staff_member)
            {
        ?>
                <tr>
                    <td>
                       <?php echo $staff_member['name'] ?> 
                    </td>
                    <td>
                        <?php echo $staff_member['title'] ?> 
                    </td>
                    <td>
                        <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_update_user_group/staff/<?php echo $staff_member['staff_id']?>');">
                                                <i class="fas fa-clock"></i>
                                                <?php echo get_phrase('update_group');?>
                                        </a>
                                    </li>   
                                </ul>
                        </div>
                    </td>
                </tr>
        <?php 
            }
            foreach($parents as $parent)
            {
        ?>
                <tr>
                    <td>
                       <?php echo $parent['name'] ?> 
                    </td>
                    <td>
                        Parent
                    </td>
                    <td>
                        <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_update_user_group/parent/<?php echo $parent['s_p_id']?>');">
                                                <i class="fas fa-clock"></i>
                                                <?php echo get_phrase('update_group');?>
                                        </a>
                                    </li>   
                                </ul>
                        </div>
                    </td>
                </tr>
        <?php
            }
            foreach($students as $student)
            {
        ?>
                <tr>
                    <td>
                       <?php echo $student['name'] ?> 
                    </td>
                    <td>
                        Student 
                    </td>
                    <td>
                        <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_update_user_group/student/<?php echo $student['student_id']?>');">
                                                <i class="fas fa-clock"></i>
                                                <?php echo get_phrase('update_group');?>
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
</div>

<?php
}
?>