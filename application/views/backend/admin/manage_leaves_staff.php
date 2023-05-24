<?php
if (right_granted('staffleave_view'))
{?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('manage_staff_leaves'); ?>
        </h3>
    </div>
</div>

<form action="<?php echo base_url(); ?>leave_staff/manage_leaves_staff" method="post" class="validate" id="filter" data-step="2" data-position='top' data-intro="use this filter for specific staff record">
     
    <div class="row filterContainer" style="padding-top: 14px;margin:0px;margin-right: 30px !important;margin-left: 30px !important;">
        <div class="col-lg-3 col-md-3 col-sm-3 form-group">
               <label for="start_date"><b>Start Date</b></label>
               <input type="text" name="start_date" autocomplete="off"  data-format="dd/mm/yyyy" id="start_date" placeholder="Select Starting Date" class="form-control datepicker" value="<?php echo $start_date ?>">
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 form-group">
               <label for="end_date"><b>End Date</b></label>
               <input type="text" name="end_date" autocomplete="off" data-format="dd/mm/yyyy" id="end_date" placeholder="Select Ending Date" class="form-control datepicker" value="<?php echo $end_date ?>">
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 form-group">
              <label for="leave_category_id"><b>Leave Category</b></label>
              <select id="leave_category_id" name="leave_category_id" class="form-control">
                    <?php echo get_leave_category($leave_category_id); ?> 
              </select>
        </div>
         <div class="col-lg-3 col-md-3 col-sm-3 form-group">
              <label for="staff_select"><b>Staff</b></label>
              <select id="staff_select" class="form-control" name="staff_select">
                <?php echo staff_list($staff_id); ?>
              </select>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 form-group">
                <input type="hidden" name="apply_filter" value="1" />
                <input type="submit" class="btn btn-primary" id="btn_submit" style="padding: 5px 5px !important; " value="<?php echo get_phrase('filter'); ?>">
            
                <?php
                if($apply_filter == 1){
                ?>
                        <a href="<?php echo base_url(); ?>leave_staff/manage_leaves_staff" class="btn btn-danger" id="btn_remove"> <i class="fa fa-remove"></i>
                                 <?php echo get_phrase('remove_filter');?>
                        </a>
                <?php
                }
                ?>
            
        </div>
    </div>
        
</form>


<div class="row">
    <div class="col-md-12">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable table_export">
            <thead>
                <tr>
                    <th style="width:34px;">
                        <div><?php echo get_phrase('s_no');?></div>
                    </th>
                    <th>
                        <div>
                            <?php echo get_phrase('leave_detail');?>
                        </div>
                    </th>
                    <th style="width: 94px;">
                        <div>
                            <?php echo get_phrase('action');?>
                        </div>
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
                        <div class="myttl">
                            <?php echo $row['staff_name'].'<span style="font-size:12px;"> ('.$row['designation'].') - Emp-Code: '.$row['employee_code'];?></span>
                        </div>
                        <div><strong><?php echo get_phrase('leave_type');?>: </strong>
                            <?php
                                echo $row['leave_categ_name'];?>
                        </div>
                        <div><strong><?php echo get_phrase('from');?>: </strong>
                            
                                <?php $start_date= $row['start_date'];
                                    $d=explode("-",$start_date);
                                    echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                                ?>
                                <br>
                                <strong> <?php echo get_phrase('to');?>: </strong>
                                
                                <?php 
                                    $end_date=$row['end_date'];
                                    $d=explode("-",$end_date);
                                    echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                                ?>
                        </div>
                        <div><strong> <?php echo get_phrase('request_date');?>:</strong>
                            <?php $request_date=$row['request_date'];
                                $d=explode("-",$request_date);
                                echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                                 ?>
                        </div>
                        <div>
                            <strong><?php echo get_phrase('process_date');?>: </strong>
                            <?php if($row['process_date'] > 0){ $process_date=$row['process_date'];
                            $d=explode("-",$process_date);
                            echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));                     
                                 } ?>
                        </div>
                        <div><strong><?php echo get_phrase('reason');?>: </strong>
                            <?php echo $row['reason'];
                                if($row['proof_doc']!=""){ ?> <a href="<?php echo display_link($row['proof_doc'],'leaves_staff');?>" target="_blank"><span class="glyphicon glyphicon-download-alt"></span></a>
                            <?php }
                                ?>
                        </div>
                    </td>
                    <td class="td_middle">
                    <?php
                    if (right_granted(array('staffleave_manage', 'staffleave_delete')))
                    {
                    ?>
                        <div class="btn-group">
                            <?php if($row['status']==0){ ?>
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <?php echo get_phrase('action');?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <?php 
                                if (right_granted(array('staffleave_manage', 'staffleave_delete')))
                                {?>
                                    <li>
                                        <a href="#" onclick="confirm_modal_approve('<?php echo base_url();?>leave_staff/manage_leaves_staff/approve/<?php echo $row['leave_staff_id'];?>');">
                                            <i class="entypo-check"></i>
                                            <?php echo get_phrase('approve');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <!-- DENY LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal_deny('<?php echo base_url();?>leave_staff/manage_leaves_staff/reject/<?php echo $row['leave_staff_id'];?>');">
                                            <i class="fas fa-ban"></i>
                                            <?php echo get_phrase('reject');?>
                                        </a>
                                    </li>
                                    
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_leave_staff/<?php echo $row['leave_staff_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                <?php 
                                }
                                if (right_granted('staffleave_delete'))
                                { 
                                ?>
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>leave_staff/manage_leaves_staff/delete/<?php echo $row['leave_staff_id'];?>/<?php echo $row['proof_doc'];?>');">
                                            <i class="entypo-trash"></i>
                                            <?php echo get_phrase('delete');?>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                            <?php
                                    } elseif($row['status']==1) { ?>
                                <div style="color:#19b62a !important;"><?php echo get_phrase('approved');?></div>
                                <?php }
                                   elseif($row['status']==2) { ?>
                                <div style="color:#D30003;"><?php echo get_phrase('rejected');?></div>
                                <?php }
                                    
                                    
                                     ?>
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
$(document).ready(function() {
    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);
    });
});

    <!--Datatables Add Button Script-->
    <?php if(right_granted('staffleave_manage')){ ?>
   
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_leave_staff/")';
        var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add staff leave' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_leave');?></a>";    
    <?php } ?>

</script>
<?php
}
?>

<!--//***********************Date filter validation***********************-->
<script>
    $("#start_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("start_date").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#end_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("end_date").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->