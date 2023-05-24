<?php
    if($this->session->flashdata('club_updated')){
        echo '<div align="center">
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$this->session->flashdata('club_updated').'
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
                   <?php echo get_phrase('staff_evaluation_questions'); ?>
            </h3>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="tab-pane box active" id="list">
                    <table data-step="2" data-position='top' data-intro="evaultion question record" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
                        <thead>
                            <tr>
                                <th style="width:34px;">
                                    #
                                </th>
                                <th>
                                    <?php echo get_phrase('title');?>
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
                                    $status_val="";
                                    if($row['status']==0)
                                    {
										$status_val="In-Active";
									}
									elseif($row['status']==1)
                                    {
										$status_val="Active";
									}
                                     echo $row['title']." (".$status_val.")";?>
                                </td>
                                <td>
                                    <?php
                                        echo get_evaluation_factor_by_id($row['factor']);
                                    ?>
                                </td>
                                
                                <td class="td_middle">
                                    <?php 
                                    if (right_granted(array('staffevaluationsettings_delete', 'staffevaluationsettings_manage')))
                                    {?>
                                    <div class="btn-group" data-step="3" data-position='left' data-intro="evalution question edit / delete options">
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                             <?php echo get_phrase('action');?> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                            <?php 
                                            if(right_granted('staffevaluationsettings_manage')){?>
                                            <li>
                                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_staff_evaluation/<?php echo $row['staff_eval_form_id'];?>');">
                                                    <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                                </a>
                                            </li>
                                            <?php }
                                            if(right_granted('staffevaluationsettings_delete'))
                                                {?>
                                            <li class="divider"></li>

                                            <li>
                                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>staff_evaluation/staff_eval/delete/<?php echo $row['staff_eval_form_id'];?>');">
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
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                   <?php echo get_phrase('staff_evaluation_rating');?>
            </h3>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12">
            <div>           
                <div class="tab-pane box active" id="remarks">
                    <table data-step="2" data-position='top' data-intro="evaultion question record" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export" id="">
                        <thead>
                            <tr>
                                <th style="width:34px;">
                                    #
                                </th>
                                <th>
                                    <?php echo get_phrase('staff_evaluation_responses');?>
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
                            <?php $count = 1;foreach($misc_staff as $row):?>
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
                                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_staff_evaluation_rating/<?php echo $row['misc_id'];?>');">
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
                                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>staff_evaluation/evaluation_rating/delete/<?php echo $row['misc_id'];?>');">
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
            </div>
        
        </div>
    </div>
        
    <script>
    $(document).ready(function() {
        
        $('#staff_btn').click(function(){
			var detail=$('#detail_staff').val();
			var status=$('#status1').val();
			var type='<?php echo "staff_eval";?>';
			var misc_id=$('#misc_id_staff').val();
			

            $('#list_new1').html('<div id="message1" class="loader"></div>');
            	
            $.ajax({
                    type: 'POST',
                    data: {detail:detail,status:status,type:type,misc_id:misc_id},
                    url: "<?php echo base_url(); ?>staff_evaluation/add_staff_misc_settings",
                    dataType: "html",
                    success: function(response) {
                     	$('#message1').remove();
                     	var obj = jQuery.parseJSON(response);
                    	var msg=obj.msg;
                    	
                    	var status=obj.status;
                    	var misc_id=obj.misc_id;
                    	var detail=obj.detail;
                    	$('.alert').show();
                    	$('.alert').html(msg);
                    	setTimeout(function() {
                            $('.alert').fadeOut();
                        }, 3000);
                        
                        $('#detail_staff').html(detail);
                        
                        $("#status1").prop("selectedIndex", status);
                        $('#misc_id_staff').val(misc_id);
                        
                     
                    }
            });
			
		});
    });
    
    <!--Datatables Add Button Script-->
    <?php if(right_granted('staffevaluationsettings_manage')){ ?>
    
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_staff_evaluation/")';
        var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add evaultion question' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_staff_evaluation_questions');?></a>";    
    
        
        var datatable_btn_url_2 = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_staff_evaluation_rating/")';
        var datatable_btn_2 = "<a href='javascript:;' onclick="+datatable_btn_url_2+" data-step='1' data-position='left' data-intro='press this button to add evaultion question' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_staff_evaluation_rating');?></a>";    
        
    
    <?php } ?>
    </script>
