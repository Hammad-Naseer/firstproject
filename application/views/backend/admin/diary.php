<?php
if (right_granted('managediary_manage'))
{
if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('diary'); ?>
            </h3>
        </div>
    </div>
    <script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
    <div id="new_div"></div>
    <form action="<?php echo base_url();?>/diary/diarys" method="post" id="filter" class="validate">
        <div class="row filterContainer" data-step="1" data-position="top" data-intro="use this filter to get specific diary records">
                <div class="col-lg-4 col-md-4 col-sm-4 form-group">
                    <input type="text" name="std_search" class="form-control" value="<?php if(!empty($std_search)){ echo $std_search;}?>">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 form-group">
                    <input type="text" name="starting" id="starting" placeholder="Select Starting Date" class="form-control datepicker" value="<?php if($start_date > 0)
                    {
					   	echo date_dash($start_date);
					}
                ?>" data-format="dd/mm/yyyy">
                    </div>
                <div class="col-lg-4 col-md-4 col-sm-4 form-group">
                   <input type="text" name="ending" id="ending" placeholder="Select Ending Date" class="form-control datepicker"  value="<?php if($end_date > 0)
                   {
				   	echo date_dash($end_date);
				   }
                   ?>" data-format="dd/mm/yyyy">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 form-group">
                        <label id="section_id_filter_selection"></label>
                        <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                            <?php echo section_selector($section_id);?>
                        </select>
                    </div>
                <div class="col-lg-4 col-md-4 col-sm-4 form-group">
                        <select id="subject_select" name="subject_select" class="form-control">
                            <?php
                                if($subject_id > 0)
                                {
    								echo subject_option_list($section_id,$subject_id);
    							}else { 
							?>
							<option value=""><?php echo get_phrase('select_subject'); ?></option>
							<?php } ?>
                        </select>
                    </div>
                <div class="col-lg-4 col-md-4 col-sm-4 form-group">
                        <select id="teacher_select" name="teacher_select" class="form-control">
                            <option value="">
                            <?php echo get_phrase('select_teacher');
                            if($teacher_id > 0)
    						{
								echo subject_teacher_option_list($subject_id,$teacher_id);
							}
							
                            
                             ?>
                            </option>
                        </select>
                    </div>
                <div class="row mt-3">
                    <!--<div class="col-lg-6 col-md-6 col-sm-12">-->
                        <input type="hidden" name="apply_filter" value="1">
                        <input type="submit" class="btn btn-primary" id="btn_submit" value="<?php echo get_phrase('filter'); ?>">
                        <?php if ($filter) { ?>
                        <a id="btn_show" href="<?php echo base_url(); ?>diary/diarys" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                        <?php } ?>
                    <!--</div>-->
                </div>
        </div>
    </form>
    <div class="col-lg-12 col-md-12 mt-4">
        <table class="table table-bordered table_export table-hover cursor" data-step="3" data-position="top" data-intro="diary records">
            <thead>
                <tr>
                    <th><?php echo get_phrase('s_no');?></th>
                    <th><?php echo get_phrase('diary_detail');?></th>
                    <th><?php echo get_phrase('action');?></th>
				</tr>
			</thead>
            <tbody>
                    
                    	<?php $count = 1;
                    	$j=$start_limit;
                    	foreach($diary1 as $row){
                    		$j++;
                    	?>
                        <tr>
                        	<td class="td_middle"><?php echo $j; ?></td>
							<td>
							<div class="myttl"><strong><?php echo get_phrase('diary_title');?> :</strong><?php echo $row['title']; ?> </div>
							<div><strong><?php echo get_phrase('teacher_name');?> :</strong><?php echo  get_teacher_name($row['teacher_id']); ?> </div>
							<div><strong><?php echo get_phrase('assign_date');?> :</strong><?php
								 $date=$row['assign_date'];
								 $d=explode("-",$date);
								 echo  	date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0])); ?> 
						    </div>
							<div class="red"><strong><?php echo get_phrase('due_date');?>:</strong>
							<?php
							    $due_date=$row['due_date'];
                                $dd=explode("-",$due_date);
                                echo  	date("d-M-Y",mktime(0,0,0,$dd[1],$dd[2],$dd[0]));
							?>
							</div>
							<div><strong><?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>:</strong>
							<ul class="breadcrumb breadcrumb2">
							<li><?php echo   $row['dept_name']; ?> </li>
							<li><?php echo   $row['class_name']; ?> </li>
							<li><?php  echo  $row['section_name']; ?> </li>
							
							</ul>
							
							
							
							</div>
					
						
							<div><strong><?php echo get_phrase('detail');?>:</strong><?php  echo  $row['task']; ?> </div>

	 
	 
						
							<div><strong><?php echo get_phrase('submission_date');?>:</strong><?php
							
                            if($row['submission_date']>0)
                            {
                            echo '<br/>';
                            $submission_date=$row['submission_date'];
                            $dd2=explode(" ",$submission_date);
                            $dd1=explode("-",$dd2[0]);
                            $submit_date=date("d-M-Y",mktime(0,0,0,$dd1[1],$dd1[2],$dd1[0],$dd1[3]));
                            echo $submit_date." ".$dd2[1];	?> 
                                   </div>
                             <div>
                             	<strong><?php echo get_phrase('submitted_by');?>:</strong>
                             	<?php echo  $row['user_name']; ?>
                             	
                             </div>
                             <?php } ?>
                             <?php

                                $planner_arr = $this->db->query("select ap.* 
                                    from ".get_school_db().".academic_planner_diary apd
                                    inner join ".get_school_db().".academic_planner ap
                                        on ap.planner_id = apd.planner_id
                                    where apd.diary_id = ".$row['diary_id']." 
                                    and apd.school_id = ".$_SESSION['school_id']."
                                    ")->result_array(); ?>
                                    <div>
                               <strong> <?php echo get_phrase('task_attachment');?>: </strong>	
    
						<?php	if($row['attachment']=="")
								{
					 }
					else
					{
						
						?>
		
		<a target="_blank" href="<?php echo display_link($row['attachment'],'diary');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
		<br/>
	<?php }	 ?></div>
				
				
				<div>
					
					
							 		 		 <span><?php
				 
    	echo "<strong>".get_phrase('academic_planner_tasks').":</strong>";
    	echo "<br/>";
    	$planner_arr=array(); 
    	$planner_arr=diary_planner_task($row['diary_id']);
    	if(count($planner_arr) > 0)
    	{
    	$count=1;
    	foreach($planner_arr as $plan)
    	{
				echo $count.")"."&nbsp;".$plan['title'];
				echo "<br/>";
				$count++;
		}
		
    	}
    	else
    	{
			echo get_phrase("no_task_attached");
		}
    	?></span>
					
				</div>
		</td>
                    <td class="td_middle">
                    <?php
                     	if($row['is_submitted']==0)
                     	{
                     ?>
                     <?php 
 							if (right_granted(array('managediary_manage')))
 							{?>
                            <div class="btn-group" data-step="4" data-position="left" data-intro="diary options: edit / delete / student links">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                   <?php echo get_phrase('action');?>  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_diary_student/<?php echo $row['section_id'].'/'.$row['diary_id'].'/'.$row['subject_id']; ?>');">
                                            <i class="fas fa-link"></i>
                                                <?php echo get_phrase('link_students');?>
                                            </a>
                                    </li>
                                    <li class="divider"></li>
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_diary/<?php echo $row['diary_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                    </li>
                                    
                                    <li class="divider"></li>
                                    
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>diary/diarys/delete/<?php echo $row['diary_id'];?>/<?php echo $row['attachment'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                     </li>
                                     
                                </ul>
                            </div>
							<?php						
							}
							}
							 ?>
        					</td>
                        
                   
                        
                        </tr>
                        <?php
                         }?>
                    </tbody>
        </table>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });

        $("#section_select").html('<select><option value=""><?php echo get_phrase('select_location'); ?></option></select>');


		$("#section_id_filter").change(function() {
            var section_id = $(this).val();

            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>diary/get_section_student_subject",
                dataType: "html",
                success: function(response) {
                	$("#subject_select").html(response);
                }
            });

        });
        $("#subject_select").change(function() {
            var subject_id = $(this).val();
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');
            $.ajax({
                type: 'POST',
                data: {
                    subject_id: subject_id
                },
                url: "<?php echo base_url();?>diary/get_subject_teacher",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $("#teacher_select").html(response);
                }
            });
        });
        $("#assign_date").change(function() {
            var assign_date = $(this).val();
            var subject_id = $('#subject_id').val();
            if (assign_date != "" && subject_id != "") {
                $.ajax({
                    type: 'POST',
                    data: {
                        assign_date: assign_date,
                        subject_id: subject_id
                    },
                    url: "<?php echo base_url();?>diary/get_acad_checkboxes",
                    dataType: "html",
                    success: function(response) {
                        $('#item_list').html(response);
                    }
                });
            }
        });
        $("#subject_id").change(function() {
            var subject_id = $(this).val();
            var assign_date = $('#assign_date').val();
            if (assign_date != "" && subject_id != "") {


                $.ajax({
                    type: 'POST',
                    data: {
                        assign_date: assign_date,
                        subject_id: subject_id
                    },
                    url: "<?php echo base_url();?>diary/get_acad_checkboxes",
                    dataType: "html",
                    success: function(response) {
                        $('#item_list').html(response);
                    }
                });
            }

        });
    });
    
    <!--Datatables Add Button Script-->
    <?php if(right_granted('managediary_manage')){ ?>
    
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_diary/")';
        var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='2' data-position='left' data-intro='Press this button to add diary' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_diary');?></a>";    
    <?php } ?>
    </script>
<?php
}
?>