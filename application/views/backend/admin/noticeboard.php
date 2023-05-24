<?php if (right_granted('noticeboard_view' )){?>
<style>
.validate-has-error {
    color: red;
}
</style>
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
    $(window).on("load",function(){
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
                <?php echo get_phrase('noticeboard');?>
            </h3>
        </div>
    </div>
    
    <form action="" method="post" id="filter" class="form-horizontal form-groups-bordered validate" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific noticeboard record" style="background-color:rgba(0, 0, 0, 0) !important; margin-top: -14px; ">
        <div class="row filterContainer">
            <div class="col-lg-6 col-md-6 col-sm-6">
                    <input type="text" name="starting" autocomplete="off"  id="starting" placeholder="Select Starting Date" class="form-control datepicker" value="<?php
					if($start_date > 0)
					 {
					 	echo date_dash($start_date);
					 }
					 ?>" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="sd"></label>
                </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                    <input type="text" name="ending" autocomplete="off"  id="ending" placeholder="Select Ending Date" class="form-control datepicker" value="<?php
					if($end_date > 0)
					 {
					 	echo date_dash($end_date);
					 }
					 ?>" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="ed"></label>
                </div>
            <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                   <select class="form-control" name="type_category" id="type_category">
                   <option value=""><?php echo get_phrase('select_notice_type');?></option>
                  <option value="all" <?php if($type_category=="all"){echo 'selected';};?>><?php echo get_phrase('all');?></option>
                   <option value="1" <?php if($type_category=="1"){echo 'selected';};?>><?php echo get_phrase('private');?></option>
                   <option value="2" <?php if($type_category=="2"){echo 'selected';};?>><?php echo get_phrase('public');?></option>
                   </select>
                </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                   <select class="form-control" name="is_active" id="is_active">
                   <option value=""><?php echo get_phrase('select_notice_status');?></option>
                   <option value="all" <?php if($is_active=="all"){echo 'selected';};?>><?php echo get_phrase('all');?></option>
                   <option value="1" <?php if($is_active=="1"){echo 'selected';};?>><?php echo get_phrase('active');?></option>
                   <option value="0" <?php if($is_active=="0"){echo 'selected';};?>><?php echo get_phrase('in_active');?></option>
                   	
                   </select>
                </div>
         	<div class="col-lg-6 col-md-6 col-sm-6">
             	    <input type="text" name="std_search" class="form-control" placeholder="Search Here" value="<?php 
                    if(!empty($std_search))
                    {
    					echo $std_search;
    				}
                    ?>">
         	    </div>
            <div class="col-lg-12 col-md-12 col-sm-12 mgt10">
                    <input type="submit" value="<?php echo get_phrase('filter');?>" class="btn btn-primary" id="btn_submit">
                    <?php if($filter){?>
                        <a href="<?php echo base_url();?>noticeboards/noticeboard" class="btn btn-danger" >
                                <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                            </a>
                    <?php } ?>
                </div>
        </div>
    </form>
    <div class="col-lg-12 col-sm-12">
    <table class="table table-striped table-bordered table_export" data-step="3" data-position="top" data-intro="noticeboard records">
                	<thead>
                		<tr>
                    		<th style=" width:54px !important;"><?php echo get_phrase('s_no');?></th>
                    		<th><?php echo get_phrase('noticeboard_details');?></th>
                    		<th><?php echo get_phrase('options');?></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $j=$start_limit;foreach($notices as $row){
                    		$j++;
						
                    	?>
                        <tr>
                            <td class="td_middle"><?php echo $j;?></td>
							<td><?php echo "<span class='myttl'>".$row['notice_title']; ?>
	</span>
							
							<span class="myttl" style="font-size:12px;">
							<?php
							if($row['type']==1)
							{
								echo " (".get_phrase('private').")";
							}
							elseif($row['type']==2)
							{
								echo " (".get_phrase('public').")";
							}
							
							if($row['is_active']==0)
							{
								echo " (".get_phrase('in_active').")";
							}
							elseif($row['is_active']==1)
							{
								echo " (".get_phrase('active').") ";
							}
							
							
							echo "</span><br/>";
							$date=$row['create_timestamp'];
							echo "<strong>".get_phrase('date').":</strong> ".date_view($date);
							echo "<br/>";
							?>
								<?php
							echo "<em><span style='text-align:justified;'>".$row['notice'];
							echo "</span></em>"
							?>
							</td>
							
							
							<td class="td_middle">
							<?php 
							
								if (right_granted(array('noticeboard_manage', 'noticeboard_delete'))){?>
                            <div class="btn-group" data-step="4" data-position="left" data-intro="noticeboard options: edit / delete">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                   <?php echo get_phrase('action');?>  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <?php if (right_granted('noticeboard_manage')){?>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_notice/<?php echo str_encode($row['notice_id']);?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                     </li>
                                     <?php }?>
                                     <?php if (right_granted('noticeboard_delete')){?>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>noticeboards/noticeboard/delete/<?php echo str_encode($row['notice_id']);?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                   </li>
                                    <?php }?>
                                </ul>
                            </div>
                            <?php } ?>
        					</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
<?php
/*
  echo $pagination;
  echo "<br>";
  echo "Total Records: ".$total_records;
*/
?>  
    <script type="text/javascript">
    $(document).ready(function() {
        
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });

      /*  $(document).on('click', "#btn_submit", function() {

            var start_date = $('#starting').val();
            var end_date=$('#ending').val();
            var type_category=$('#type_category').val();
            var is_active=$('#is_active').val();

            if (start_date != "" || end_date!='' || type_category !='' || is_active!='') {
                $('#btn_remove').show();
                $('#list').html('<div id="message" class="loader"></div>');

                $.ajax({
                    type: 'POST',
                    data: {
                        start_date: start_date,
                        end_date:end_date,
                        type_category:type_category,
                        is_active:is_active
                    },
                    url: "<?php echo base_url();?>noticeboards/notice_generator",
                    dataType: "html",
                    success: function(response) {
                       // $("#btn_submit").attr('disabled','disabled');
                        $('#message').remove();
                        $('#list').html(response);
                    }
                });

            } else {
                $("#list").html("<?php echo get_phrase('please_select_any_filter_to_proceed'); ?>");

            }

        });
*/
    });

    $('#is_closed').change(function(){
          this.value = this.checked ? 1 : 0;
    });

     $("#starting").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("starting").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#ending").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("ending").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("ending").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
    
    <?php if(right_granted('noticeboard_manage')){ ?>
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_noticeboard/")';
        var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='2' data-position='left' data-intro='Press this button to add noticeboard' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_noticeboard');?></a>";    
    <?php } ?>
    
</script>
    <style>
    .loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #63b7e7;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    </style>
<?php
}
?>