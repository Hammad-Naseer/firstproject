<style>
.title{border:1px solid #eae7e7;min-height:34px;padding-top:10px;background-color:rgba(242,242,242,.35);color:#8c8c8c;height:auto}.adv{width:50px}.tt{background-color:rgba(242,242,242,.35);border:1px solid #eae7e7;min-height:26px;padding-top:4px}.panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#21a9e1;border-top:2px solid #00a651}.panel-body{padding:9px 22px}.myfsize{font-size:11px!important}.panel-group .panel>.panel-heading>.panel-title>a{display:inline}.fa-file-o{padding-right:0!important}.panel{margin-bottom:20px;background-color:#fff;border:1px solid rgba(0,0,0,.08);border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(43,43,43,.15)}.panel-title{width:100%}.panel-group.joined>.panel>.panel-heading+.panel-collapse{background-color:#fff}.difl{display:inline;float:left}.bt{margin-bottom:-28px;padding-top:15px;padding-right:31px}.panel-heading>.panel-title{float:left!important;padding:10px 15px!important;color:#fff!important;background-color:#fff}.fa-mobile{font-size:24px}.emer{color:red}.emer_green{color:green}.emer_blue{color:#00f}.teacher-listing li{list-style:none;padding-left:20px}.ulli{margin-bottom:0}.ulli li{list-style:none;display:inline;padding-right:20px}
.assigned_subjects{
    background: aliceblue;
    width: 500px;
    padding: 5px;
    border: 1px solid #0a73b7;
}
.memeber_entry_buttons
{
    margin-left: -38px;
}
.memeber_entry_buttons > li
{
    display:inline;
}
.btn
{
    padding:2px 12px !important;
}
</style>
<?php  
//echo $filter_id;
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
<script>
$(document).ready(function() {
    $('#filter_submit').on('click', function() {
        var designation_id = $('#designation_id').val();
        if (designation_id != "") {
            window.location.href = "<?php echo base_url();?>user/teacher/filter/" + designation_id;
        } else {

            $("#mydiv").html("<?php echo get_phrase('value_required'); ?>");
        }
    });
});
</script>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('teachers'); ?>
        </h3>
    </div>
</div>


<form action="<?php echo base_url();?>user/teacher" method="post">
        
    <div class="row filterContainer" style="padding-top: 14px;margin:0px;">
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <select name="designation_id" id="designation_id" class="form-control" required >
                    <option value=""><?php echo get_phrase('select_designation');?></option>
                    <?php echo designation_list_h($parent_id=0,$designation_id); ?>
            </select>
            <div id="mydiv" class="red"></div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group" style="margin-top:-4px;">
                <input type="hidden" name="apply_filter" value="1" />
                <button type="submit" class="modal_save_btn" id="filter_submit"><?php echo get_phrase('filter');?></button>
                <?php if($apply_filter){ ?>
                    <a href="<?php echo base_url();?>user/teacher" class="modal_cancel_btn" style="padding:5px 8px !important; ">
                        <i class="fa fa-remove"></i>
                        <?php echo get_phrase('remove_filters');?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
        
</form>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;">
        <!--table_export-->
        <table class="table table-bordered datatable table_export" data-step="3" data-position='top' data-intro="teachers records">
            <thead>
                <tr>
                    <th>
                        <?php echo get_phrase('teacher_listing');?>
                    </th>
                </tr>
            </thead>
            <tbody>
        <?php
		$n=0;
		foreach($data as $row):
		$n++;
		?>
            <tr>
                <td>
                    <div class="member-entry"> 
                        <a href="<?php echo base_url();?>user/add_edit_staff/<?php echo str_encode($row['staff_id']);?>" class="member-img"> 
                            <img src="<?php  if($row['staff_image']!=''){
                                echo base_url() . "uploads/profile_pic/" . $row['staff_image'];
                            }else{
                                echo base_url().'/uploads/default.png';
                            } ?>" class="img-rounded">
                            <i class="entypo-forward"></i> 
                        </a> 
                        <div class="member-details"> 
                            <h4> 
                                <a href="#"><?php echo $row['name'];?> </a> 
                            </h4> 
                            <div class="row info-list"> 
                                <div class="col-sm-4"> 
                                    <strong>Designation:</strong>
                                    <?php echo ucfirst($row['designation']);?>
                                    <br>
                                    <strong>ID No:</strong>
                                    <?php echo $row['id_no'];?>
                                    <br>
                                    <strong>Employee Code:</strong>
                                    <?php echo $row['employee_code']; ?>
                                    <br>
                                    <strong>System ID:</strong>
                                    <?php echo $row['system_id'];?>
                                </div> 
                                <div class="col-sm-4"> 
                                    <strong>Date of Birth:</strong>
                                    <?php echo convert_date(date('d-m-Y', strtotime($row['dob']))); ?>
                                    <br>
                                    <strong>Religion:</strong>
                                    <?php echo ucfirst($row['religion']);?>
                                    <br>
                                    <strong>Gender:</strong>
                                    <?php echo ucfirst($row['gender']);?>
                                    <br>
                                    <strong>Blood Group:</strong>
                                    <?php echo ucfirst($row['blood_group']);?>
                                </div> 
                                <div class="col-sm-4"> 
                                    <strong>Phone Number:</strong>
                                    <?php echo $row['phone_no']; ?>
                                    <br>
                                    <strong>Mobile Number:</strong>
                                    <?php echo $row['mobile_no']; ?>
                                    <br>
                                    <strong>Emergency Number:</strong>
                                    <?php echo $row['emergency_no']; ?>
                                    <br>
                                </div> 
                                <div class="clear"></div> 
                                <div class="col-sm-12">
                                    <?php
                                        $teachers=get_teacher_subjects($row['staff_id']);
                                        if(count($teachers) > 0){
                                    ?>
                                            <ul class="teacher-listing" style="padding-left:0px;">
                                                    <strong data-step="4" data-position='top' data-intro="assign subjects this teacher"><?php echo get_phrase('assigned_subjects');?>: </strong>
                                                    <div class="assigned_subjects">
                                                        <?php
                                        				foreach($teachers as $all){
                                        				?>
                                                            <li>
                                                                <i class="fa fa-book" aria-hidden="true" style="padding-right:0px;"></i>
                                                                <?php echo $all['subject'];?>
                                                                <?php echo $all['code'];?>
                                                            </li>
                                                        <?php }?>
                                                    </div>
                                            </ul>
                                        <?php } ?>
                                </div>
                                <div class="clear"></div> 
                                <div class="col-sm-12 col-xs-12"> 
                                    <ul class="memeber_entry_buttons">
                                        <?php 
                                        if (right_granted('staff_manage'))
                                        {?>
                                            <li>
                                                <a href="<?php echo base_url();?>user/add_edit_staff/<?php echo str_encode($row['staff_id']);?>" class="btn btn-warning btn-sm">
                                                    <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                                </a>
                                            </li>
                                        <?php 
                                        }
                                        if (right_granted('staff_delete'))
                                        {?>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>user/staff_listing/delete/<?php echo $row['staff_id'];?>/<?php echo $row['staff_image'];?>/<?php echo $row['id_file'];?>');" class="btn btn-danger btn-sm">
                                                <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
    
                                        <?php }?>
    
                                        <li>
                                            <a href="<?php echo base_url(); ?>user/create_staff_card/staff/<?php echo str_encode($row['staff_id']); ?>" class="btn btn-primary btn-sm">
                                                <i class="entypo-user"></i>
                                                <?php echo get_phrase('create_card');?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/staff_timing/<?php echo $row['staff_id'];?>');" class="btn btn-primary btn-sm">
                                            <i class="fas fa-clock"></i>
                                            <?php echo get_phrase('staff_timing');?>
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/leave_settings/<?php echo $row['staff_id'];?>');" class="btn btn-primary btn-sm">
                                            <i class="fab fa-wpforms"></i>
                                            <?php echo get_phrase('leave_settings');?>
                                            </a>
                                        </li>  
                                        <?php if (right_granted('teacher_manage')) {?>
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_teacher_subject/<?php echo $row['staff_id'];?>');" class="btn btn-primary btn-sm">
                                                <?php echo get_phrase('assign_subjects_to_teacher');?>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul> 
                                </div> 
                            </div> 
                        </div> 
                    </div>
                </td>
            </tr>
    <?php endforeach;?>
    </tbody>
    </table>
    </div>
</div>

<!--Datatables Add Button Script-->
<script>
    var datatable_btn_url = '<?php echo base_url();?>user/add_edit_staff';
    var datatable_btn     = "<a href="+datatable_btn_url+" data-step='1' data-position='left' data-intro='press this button to go to next page and fill a form to add new teacher' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_teacher');?></a>";    
</script>


