<?php
    echo '<div align="center">
        <div class="alert1 style="display:none;"></div>
    </div>';
?>

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
                    <?php echo get_phrase('student_evaluation_questions'); ?>
                </h3>
            </div>
        </div>
        
        
        <div class="row">
            <div class="col-md-12">
                <div>                
                    <div class="tab-pane box active" id="list">
                        <table data-step="2" data-position='top' data-intro="evaultion question record" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export" id="">
                            <thead>
                                <tr>
                                    <th style="width:34px;">
                                        #
                                    </th>
                                    <th>
                                        <?php echo get_phrase('title');?>
                                    </th>
                                    <th>
                                         <?php echo get_phrase('Type');?>
                                    </th>
                                    <th>
                                         <?php echo get_phrase('factor');?>
                                    </th>
                                    <th style="width:94px;">
                                        <?php echo get_phrase('options');?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1;foreach($eval as $row):?>
                                <tr>
                                    <td class="td_middle">
                                        <?php echo $count++;?>
                                    </td>
                                    <td>
                                        <?php
                                    $status_arr="";
                                    if($row['status']==1)
                                    {
                                        $status_arr='Active';
                                    }
                                    if($row['status']==0)
                                    {
                                        $status_arr='In-Active';
                                    }
                                     echo $row['title']." "."(".$status_arr.")" ;?>
                                    </td>
                                    <td>
                                        <?php 
                                            if($row['type'] == '1')
                                                echo 'Exam';
                                            else
                                                echo 'General';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo get_evaluation_factor_by_id($row['factor'])->title;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (right_granted(array('studentevaluationsettings_delete', 'studentevaluationsettings_manage')))
                                        {?>
                                        <div class="btn-group" data-step="3" data-position='left' data-intro="evalution question edit / delete options">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                <?php echo get_phrase('active'); ?> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                                <?php if (right_granted('studentevaluationsettings_manage'))
                                                {?>
                                                <!-- EDITING LINK -->
                                                <li>
                                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_student_evaluation/<?php echo $row['eval_id'];?>');">
                                                        <i class="entypo-pencil"></i>
                                                        <?php echo get_phrase('edit');?>
                                                    </a>
                                                </li>
                                                <?php 
                                                }
                                                if(right_granted('studentevaluationsettings_delete')){?>
                                                <li class="divider"></li>
                                                <!-- DELETION LINK -->
                                                <li>
                                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>evaluation/stud_evaluation/delete/<?php echo $row['eval_id'];?>');">
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
                </div>
            </div>
            <div class="col-lg-12 col-sm-12 box-content">
            <div class="panel-heading">
                    <div class="panel-title black2">
                        <?php echo get_phrase('Student_evaluation_ratings');?>
                    </div>
                </div>
                <div>                
                    <div class="tab-pane box active" id="remarks">
                        <table data-step="2" data-position='top' data-intro="evaultion question record" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export" id="">
                            <thead>
                                <tr>
                                    <th style="width:34px;">
                                        #
                                    </th>
                                    <th>
                                        <?php echo get_phrase('student_evaluation_responses');?>
                                    </th>
                                    <th>
                                         <?php echo get_phrase('status');?>
                                    </th>
                                    <th style="width:94px;">
                                        <?php echo get_phrase('options');?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1;foreach($misc as $row):?>
                                <tr>
                                    <td class="td_middle">
                                        <?php echo $count++;?>
                                    </td>
                                    <td>
                                        <?php
                                            echo $row['detail'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $status_arr="";
                                            if($row['status']==1)
                                            {
                                                $status_arr='Active';
                                            }
                                            if($row['status']==0)
                                            {
                                                $status_arr='In-Active';
                                            }
                                            echo $status_arr;
                                         ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (right_granted(array('studentevaluationsettings_delete', 'studentevaluationsettings_manage')))
                                        {?>
                                        <div class="btn-group" data-step="3" data-position='left' data-intro="evalution question edit / delete options">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                <?php echo get_phrase('active'); ?> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                                <?php if (right_granted('studentevaluationsettings_manage'))
                                                {?>
                                                <!-- EDITING LINK -->
                                                <li>
                                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_evaluation_rating/<?php echo $row['misc_id'];?>');">
                                                        <i class="entypo-pencil"></i>
                                                        <?php echo get_phrase('edit');?>
                                                    </a>
                                                </li>
                                                <?php 
                                                }
                                                if(right_granted('studentevaluationsettings_delete')){?>
                                                <li class="divider"></li>
                                                <!-- DELETION LINK -->
                                                <li>
                                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>evaluation/evaluation_rating/delete/<?php echo $row['misc_id'];?>');">
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
                </div>
        
        </div>
        </div>
        <div id="list_new">
        </div>
        <script>
        $(document).ready(function() {
            $('#stud_btn').click(function() {
                var detail = $('#detail').val();
                var status = $('#status').val();
                var type = '<?php echo "stud_eval";?>';
                var misc_id = $('#misc_id').val();
                $('#list_new').html('<div id="message" class="loader"></div>');
                $.ajax({
                    type: 'POST',
                    data: {
                        detail: detail,
                        status: status,
                        type: type,
                        misc_id: misc_id
                    },
                    url: "<?php echo base_url(); ?>evaluation/add_misc_settings",
                    dataType: "html",
                    success: function(response) {
                        $('#message').remove();

                        var obj = jQuery.parseJSON(response);
                        var status = obj.status;
                        var misc_id = obj.misc_id;
                        var detail = obj.detail;
                        $('.alert1').show();
                        $('.alert1').html(msg1);
                        setTimeout(function() {
                            $('.alert1').fadeOut();
                        }, 3000);

                        $('#detail').html(detail);
                        $("#status").prop("selectedIndex", status);
                        $('#misc_id').val(misc_id);


                    }
                });
            });
        });
        
        <!--Datatables Add Button Script-->
        <?php if(right_granted('studentevaluationsettings_manage')){ ?>
            var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_student_evaluation/")';
            var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='press this button to add evaultion question' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_student_evaluation_questions');?></a>";    
            
            var datatable_btn_url_2 = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_evaluation_rating/")';
            var datatable_btn_2 = "<a href='javascript:;' onclick="+datatable_btn_url_2+" data-step='1' data-position='left' data-intro='press this button to add evaultion question' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_student_evaluation_rating');?></a>";    
        
        <?php } ?>
        
        </script>
