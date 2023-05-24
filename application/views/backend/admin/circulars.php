
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
    <div class="row ">
       <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
           <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline capitalize">
                 <?php echo get_phrase('circulars'); ?>
            </h3>
        </div>
    </div>
        <form id="filter" name="filter" method="post" class="form-horizontal form-groups-bordered validate" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific circular records" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <input type="text" name="starting" autocomplete="off"  id="starting" placeholder="Select Starting Date" value=
                    "<?php if($start_date > 0)
					 {
					 	echo date_dash($start_date);
					 }
					 ?>" class="form-control datepicker" data-format="dd/mm/yyyy">
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
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label id="section_id_filter_selection"></label>
                    <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                        <?php echo section_selector($section_id);?>
                    </select>
                </div>          
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <select id="student_select" name="student_id" class="form-control">
                    <?php
                    if(isset($student_id))
                    {
						echo section_student($section_id , $student_id);
					}
					else
					{?>
						<option value=""><?php echo get_phrase('select_student'); ?></option>
					<?php
					}  
                    ?>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <select class="form-control" name="is_active" id="is_active">
                   <option value=""><?php echo get_phrase('select_circular_status');?></option>
                   <option value="all" <?php if($is_active=='all'){echo 'selected';};?>><?php echo get_phrase('all');?></option>
                   <option value="1" <?php if($is_active=='1'){echo 'selected';};?>><?php echo get_phrase('active');?></option>
                   <option value="0" <?php if($is_active=='0'){echo 'selected';};?>><?php echo get_phrase('in_active');?></option>
                   </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 mgt10">
                    <input type="submit" value="<?php echo get_phrase('filter'); ?>" class="btn btn-primary" id="btn_submit">
                    <?php
                    if($filter)
                    {?>
                    <a href="<?php echo base_url(); ?>circular/circulars" class="btn btn-danger" id="btn_remove">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </form>
    <div id="div_msg"></div>
    <div id="get_planner" class="col-lg-12 col-md-12 col-sm-12">
    </div>
    <div class="col-lg-12 col-sm-12">
   <table id="" class="table table-striped table-bordered table_export  table-responsive" data-step="3" data-position="top" data-intro="circulars records" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>
                    <div><?php echo get_phrase('s_no');?></div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('circular_detail');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('options');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $j = $start_limit;
                foreach($query as $row){
            	$j++;
            ?>
            <tr>
                <td class="td_middle">
                    <?php echo $j;?>
                </td>
                <td>
                    <?php echo "<span class='myttl'>".$row['circular_title'];
						  echo "</span>"; 		
					?>
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
                     $date = $row['create_timestamp'];
				    echo "<strong>".get_phrase('date').":</strong> ".date_view($date);
						
				echo "<br/>"; 
				echo "<strong>".get_phrase('department'). "/".get_phrase('class') . "/".get_phrase('section')." :</strong> ";
								
						?>
                    <ul class="breadcrumb" style="    display: inline;  padding: 2px;    margin-left: 5px;    color: #428abd;">
                        <li>
                            <?php echo $row['dept_name']; ?>
                        </li>
                        <li>
                            <?php echo $row['class_name']; ?>
                        </li>
                        <li>
                            <?php echo $row['section_name']; ?>
                        </li>
                    </ul>
                    <br>
                    
                    <?php
				
								
								
                if($row['student_id']!="")
					{
						echo "<strong>".get_phrase('student_name').":</strong> ".$row['student_name'];
						//$student_name=get_student_name($row['student_id']);
			echo "<br/>";
					}
                echo "<strong>".get_phrase('detail').": </strong> <br><em>".$row['circular'];
                
                
                if($row['attachment']=="")
    {?>
                        <?php }
	else
	{
	    echo "</em><br/> <strong>".get_phrase('attachment').":</strong> ";
	?>
                        <a target="_blank" href="<?php echo display_link($row['attachment'],'circular');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
                        <?php }	
                ?>
                </td>
                <td class="td_middle">
                    
                        <?php if (right_granted(array('circulars_delete', 'circulars_manage'))){?>
                        <div class="btn-group" data-step="4" data-position="left" data-intro="circulars options: edit / delete">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <?php echo get_phrase('action');?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <!-- EDITING LINK -->
                                <?php if (right_granted('circulars_manage'))
                                {?>
                                <li>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_circular/<?php echo str_encode($row['circular_id']);?>');">
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('edit');?>
                                    </a>
                                </li>
                                <?php }?>
                                <?php if (right_granted('circulars_delete')){?>
                                <li class="divider"></li>
                                <!-- DELETION LINK -->
                                <li>
                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>circular/circulars/delete/<?php echo str_encode($row['circular_id']);?>');">
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
            <?php } ?>
        </tbody>
    </table> 
    </div>
   
    <script>
    $(document).ready(function() {
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });

        $("#section_id_filter").change(function() {
            $('#get_planner').html('');
            var section_id = $(this).val();

            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>circular/get_section_student",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    if (response != "") {
                        $("#student_select").html(response);
                    }
                    if (response == "") {
                        $("#student_select").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
                    }
                }
            });

        });

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
    

    <?php if(right_granted('circulars_manage')){ ?>
       var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_circular/")';
       var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='2' data-position='left' data-intro='Press this button to add circular' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_circular');?></a>";    
    <?php } ?>
    
</script>
