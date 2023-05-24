<?php if (right_granted(array('systemadministration_manage'))){?>
<style>
    .title{border:1px solid #eae7e7;min-height:34px;padding-top:10px;background-color:rgba(242,242,242,.35);color:#8c8c8c;height:auto}
    .adv{width:50px}.tt{background-color:rgba(242,242,242,.35);border:1px solid #eae7e7;min-height:26px;padding-top:4px}
    .panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#21a9e1;border-top:2px solid #00a651}
    .panel-body{padding:9px 22px}.myfsize{font-size:11px!important}.panel-group .panel>.panel-heading>.panel-title>a{display:inline}
    .fa-file-o{padding-right:0!important}.panel{margin-bottom:20px;background-color:#fff;border:1px solid rgba(0,0,0,.08);border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(43,43,43,.15)}
    .panel-title{width:100%}.panel-group.joined>.panel>.panel-heading+.panel-collapse{background-color:#fff}.difl{display:inline;float:left}
    .bt{margin-bottom:-28px;padding-top:15px;padding-right:31px}.panel-heading>.panel-title{float:left!important;padding:10px 15px!important;color:#fff!important;background-color:#fff}
    .fa-mobile{font-size:24px}.emer{color:red}.emer_green{color:green}.emer_blue{color:#00f}
</style>
<?php  
//echo $this->session->flashdata('club_updated').'sdfsdfsdf';
if($this->session->flashdata('club_updated')){
    echo '<div align="center">
    <div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    '.$this->session->flashdata('club_updated').'
    </div> 
    </div>';
}

if($this->session->flashdata('error_msg')){
    echo '<div align="center">
    <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    '.$this->session->flashdata('error_msg').'
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
    $(window).on("load",function() {
        setTimeout(function() {
            $('.mydiv').fadeOut();
        }, 3000);
    });
</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('users'); ?>
        </h3>
        
    </div>
</div>


<form action="" method="post">
    <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
        <div class="col-lg-8 col-md-8 col-sm-12 m-3">
            <select name="designation_id" id="designation_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                <option value=""><?php echo get_phrase('select_designation'); ?></option>
                <?php
                $pol_id = '';
                if ($filter)
                    $pol_id = $data[0]['staff_id'];
                echo designation_list(0);?>
            </select>
            <div id="mydiv" class="red"></div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 m-3">
            <div class="btn btn-primary" id="filter_submit">
                <?php echo get_phrase('filter'); ?>
            </div>
            <?php if($filter){?>
                <a href="<?php echo base_url();?>user/admin_staff_list" class="btn btn-danger" style="padding:5px 8px !important; ">
                    <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters'); ?>
                </a>
            <?php } ?>
        </div>
    </div>
</form>

    <div class="col-lg-12 col-md-12 col-sm-12">
        <table class="table table-bordered table_export" width="100%" data-step="2" data-position="top" data-intro="teacers record">
            <thead>
                <tr>
                    <td width="40">
                        <?php echo get_phrase('#');?>
                    </td>
                    <td>
                        <?php echo get_phrase('picture');?>
                    </td>
                    <td>
                        <?php echo get_phrase('detail');?>
                    </td>
                    <td width="94">
                        <?php echo get_phrase('options');?>
                    </td>
                </tr>
            </thead>
            <tbody>
            <?php
            $a=0;
            foreach($data as $row)
            {
            ?>
                    <?php   $a++; ?>
                    <tr>
                        <td class="td_middle">
                            <?php echo $a; ?>
                        </td>
                        <td class="td_middle">
                            <?php $pic = display_link($row['staff_image'],'staff')?>
                            <img src="<?php  if($row['staff_image']!=''){ 
                                echo $pic;
                            }else{
                                echo base_url().'uploads/default.png';
                            } ?>" class="img-responsive" style="width:65px; height:65px;">
                        </td>
                        <td>
                            
                            <div class="col-sm-11" style="padding:0px;">
                                <div class="col-sm-12">
                                    <div class="myttl">
                                        <?php   echo $row['name'];?>
                                        <span style="font-size:12px;">  (<?php  echo $row['designation'];?>)
                                        <?php if($row['employee_code'] != ""){ ?> -
                                        <?php echo get_phrase('emp_code'); ?>: <?php  echo $row['employee_code'];?>
                                        <?php } ?>
                                        </span>
                                    </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div><?php echo '<strong>'.get_phrase('id_no').':</strong> '.$row['id_no']; ?></div>
                                    </div>
                                    <?php if($row['dob'] != ""){ ?>
                                    <div class="col-sm-6">
                                        <div><?php echo '<strong>'.get_phrase('date_of_birth').':</strong> '.convert_date(date('d-m-Y', strtotime($row['dob']))); ?></div>
                                    </div>
                                    <?php } if($row['gender'] != ""){ ?>
                                    <div class="col-sm-6">
                                        <div>
                                            <?php echo '<strong>'.get_phrase('gender').':</strong> '.ucfirst($row['gender']);?></div>
                                    </div>
                                    <?php } if($row['religion'] != ""){ ?>
                                    <div class="col-sm-6">
                                        <div>
                                            <?php echo '<strong>'.get_phrase('religion').':</strong> '.religion($row['religion']);?></div>
                                    </div>
                                    <?php } if($row['blood_group'] != ""){ ?>
                                    <div class="col-sm-6">
                                        <div><?php echo '<strong>'.get_phrase('blood_group').':</strong> '.$row['blood_group'];?></div>
                                    </div>
                                    <?php } if($row['phone_no'] != ""){ ?>
                                    <div class="col-sm-6">
                                        <div><?php echo '<strong>'.get_phrase('phone_no#').': </strong>'.$row['phone_no'];?></div>
                                    </div>
                                    <?php } if($row['mobile_no'] != ""){ ?>
                                    <div class="col-sm-6">
                                        <div>
                                            <?php echo '<strong>'.get_phrase('mobile_no#').': </strong> '.$row['mobile_no'];?></div>
                                    </div>
                                    <?php } if($row['emergency_no'] != ""){ ?>
                                    <div class="col-sm-6">
                                        <div><?php echo '<strong>'.get_phrase('emergency_no').': </strong>'.$row['emergency_no'];?></div>
                                    </div>
                                    <?php } if($row['postal_address'] != ""){ ?>
                                    <div class="col-sm-12">
                                        <div><?php echo '<strong>'.get_phrase('postal_address').':</strong> '.$row['postal_address'];?></div>
                                    </div>
                                    <?php } if($row['permanent_address'] != ""){ ?>
                                    <div class="col-sm-12">
                                        <div><?php echo '<strong>'.get_phrase('permanent_address').':</strong> '.$row['permanent_address']; ?></div>
                                    </div>
                                    <?php } ?>
                                </div>
                        </td>
                        <td class="td_middle">
                            
                            <div class="btn-group" data-step="3" data-position="left" data-intro="teachers more options: assign group / manage teacher account">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <?php
                                    if ($row['is_teacher'] == 1)
                                    {?>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url() ?>modal/popup/modal_manage_teacher_account/<?php echo $row['staff_id'] ?>');">
                                            <i class="entypo-pencil"></i> <?php echo get_phrase('manage_teacher_account'); ?>
                                        </a>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                    
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url() ?>modal/popup/modal_assign_group/<?php echo $row['staff_id'] ?>');">
                                            <i class="fas fa-clipboard-check" style="font-size:15px;"></i> <?php echo get_phrase('manage_staff_account'); ?>
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
    <script>
        $(document).ready(function() 
        {
            $('#admin_staff_tbl').DataTable({
                "aLengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "bStateSave": true
            });

            $(".dataTables_wrapper select").select2({
                minimumResultsForSearch: -1
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        $('#filter_submit').on('click', function() 
        {
            var designation_id = $('#designation_id').val();
            if (designation_id != "") {
                window.location.href = "<?php echo base_url();?>user/admin_staff_list/filter/" + designation_id;
            } else {

                $("#mydiv").html("<?php echo get_phrase('value_required'); ?>");
            }
        });
    });
    </script>
<?php
}
?>