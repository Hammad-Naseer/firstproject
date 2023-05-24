<style>
    audio, canvas, progress, video {
    height: 28px;
}
</style>
<?php
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
$(window).on("load",function() 
{
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
        <h3 class="system_name inline">
               <?php echo get_phrase('diary'); ?>
        </h3>
    </div>
</div>


 <form method="post" action="<?php echo base_url();?>teacher/diary" class="form" data-step="2" data-position='top' data-intro="Please select the filters and press Filter button to get specific records">
    <div class="row filterContainer">
    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            <input type="text" name="std_search" class="form-control" value="<?php 
            if(!empty($std_search))
            {
    			echo $std_search;
    		}
            ?>">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            <!--<label for="loc_country"><b>Select Country</b></label>-->
            <select id="month_year" name="month_year" class="form-control" >
            <?php
                $academic_year_id= intval($_SESSION['academic_year_id']);
                $qur_rr=$this->db->query("select *  from ".get_school_db().".acadmic_year  where school_id=".$_SESSION['school_id']."  and academic_year_id=$academic_year_id ")->result_array();
                $start_date=$qur_rr[0]['start_date'];
                $end_date=$qur_rr[0]['end_date'];
                echo month_year_option($start_date,$end_date,$subject_month_year);
            ?>
            </select>
        </div>
    </div>

	<div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            <!-- <label id="select_selection"></label>   -->
            <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" >
                <?php echo get_teacher_dep_class_section_list($teacher_section, $section);
                ?>
            </select>
		</div>
	</div>
			
	<div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            <select name="subject" id="subject_list" class="form-control" >
                <option value=""><?php echo get_phrase('select_subject');?></option>
            </select>	
		</div>
					
	</div>
    
	<div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group">
            <input type="submit" name="submit" id="submit_filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"/>
                <?php
                if($filter)
                {?>
                    <a href="<?php echo base_url();?>teacher/diary" class="btn btn-danger" >
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                    </a>
                <?php
                }
                ?>
            <div id="error_end1" style="color:red"></div>
		</div>
	</div>
	
	</div>
</form>

<div class="col-lg-12 col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered table_export" id="teacher_diary_datatable" data-step="3" data-position='top' data-intro="diary records">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('s_no');?></div></th>
                    		<th style="width:140px;"><div><?php echo get_phrase('subject');?></div></th>
                           	<th><div><?php echo get_phrase('details');?></div></th>
                           	<th><div><?php echo get_phrase('submission_details');?></div></th>
                            <th><div><?php echo get_phrase('action');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    <?php 
                    	$j=$start_limit;
                    	$count = 1;
                    	foreach($diary as $row):
                    	$j++;
                    ?>
                        <tr>
                        	<td class="td_middle"><?php echo $j; ?></td>
							<td class="td_middle"><?php echo $row['subject_name'].' - '.$row['subject_code'];?></td>
                            <td style="line-height:22px;">
                            	<div><strong><?php echo get_phrase('assign_date');?>:</strong><?php echo convert_date($row['assign_date']);?></div>
                            	<div style="color:#972d2d;"><strong><?php echo get_phrase('due_date');?>:</strong><?php echo convert_date($row['due_date']);?></div>
                                <div style="font-size:14px; color:#0A73B7;"><strong><?php echo get_phrase('title').':'.$row['title'];?></strong></div>
								
								<div>
								<?php
                                if($row['attachment']!="")
                                {
                                ?>
                                    <strong style="margin-right:10px;"><?php echo get_phrase('attachment'); ?>:</strong><a target="_blank" href="<?php echo display_link($row['attachment'],'diary');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
                                </div>
                                <?php   
                                }
                                    $sec_arr = section_hierarchy($row['section_id']);
                                    echo '<strong>'.get_phrase('section').':</strong> '.$sec_arr['d'].' - '.$sec_arr['c'].' - '.$sec_arr['s']; 					
                                    echo'<span class="item">';
                                ?>
                                <br/><strong><?php echo get_phrase('students');?>:</strong> 
                                <?php
                                if ($row['submission_date'] != '0000-00-00 00:00:00')
                                {
                                    echo '<br/><strong>'.get_phrase("submisstion_date").':</strong> '.convert_date($row['submission_date']).' '.date('h:i:s A', strtotime($row['submission_date']));
									echo '<br/><strong>'.get_phrase("detail").':</strong> '.$row['task'];
                                }
                                

                                $planner_arr = $this->db->query("select ap.* 
                                    from ".get_school_db().".academic_planner_diary apd
                                    inner join ".get_school_db().".academic_planner ap
                                        on ap.planner_id = apd.planner_id
                                    where apd.diary_id = ".$row['diary_id']." 
                                    and apd.school_id = ".$_SESSION['school_id']."
                                    ")->result_array();
                                if (count($planner_arr)>0)
                                {
                                    echo '<br/><strong>'.get_phrase("planner").':</strong>';
                                    $p_count=1;
                                    foreach ($planner_arr as $key => $value) 
                                    {
                                        echo '<br>'.$p_count.')'.get_phrase("title").':' .$planner_arr[0]['title'];
                                    }
                                } 
                                echo'</span>';
                                if($row['is_assigned'] == 0 ){
                                    echo '<br/>'.get_phrase("status").': <strong style="color:blue;">'.get_phrase("draft").'</strong>';
                                }else{
                                    echo '<br/>'.get_phrase("status").': <strong style="color:green;">'.get_phrase("assigned").'</strong>';
                                }
                                
                                ?>
                                
                                <?php if ($row['audio'] != ""){
                                    $audio_path = base_url()."uploads/".$_SESSION['folder_name']."/diary_audios/".$row['audio'];?>
                                    <br><strong><?php echo get_phrase('audio'); ?>:</strong>
                                    <audio controls>
                                      <source src="<?php echo $audio_path;?>" type="audio/ogg">
                                      <source src="<?php echo $audio_path;?>" type="audio/mpeg">
                                    </audio>
                                <?php }?>
                            </td>
                            <td class="td_middle">
                                <?php
                                if ($row['is_assigned'] == 1)
                                {
                                ?>
                                <a href="<?php echo base_url();?>teacher/assignment_details/<?php echo $row['diary_id']; ?>" target="_blank"><?php echo get_phrase('click_to_see_assignment_details');?></a>
                                <?php  
                                }else{
                                ?>
                                <p> <?php echo  get_phrase('not_assigned');?></p>
                                <?php  
                                }
                                ?>
                                
                            </td>
                            <td class="td_middle">
                                <?php
                               /*  if($row['is_submitted_by'] == 0)
                               if ( (date('Y-m-d') < $row['due_date']) && $row['is_submitted'] == 0)
                                {
                                    */
                                ?>
                                <div class="btn-group" data-step="4" data-position='left' data-intro="diary options: view,manage,assign / edit / delete">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase("planner"); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        <!-- EDITING LINK -->
                                        <li>
                                            <a style="" href="#" onclick="showAjaxModal('<?php echo base_url() ?>modal/popup/modal_diary_student/<?php echo $row['section_id'].'/'.$row['diary_id'].'/'.$row['subject_name'].'-'.$row['subject_code']; ?>')">
                                                <i class="entypo-eye"></i>
                                                <?php echo get_phrase('manage');?> / <?php echo get_phrase('assign');?> 
                                            </a>
                                        </li>
                                        
                                        <?php if ($row['is_assigned'] == 0) { ?>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="<?=base_url()?>teacher/edit_diary/<?php echo $row['diary_id'];?>/<?php echo $_SESSION['school_id'];?>">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                        
                                        <?php }else{
                                        ?>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="<?=base_url()?>teacher/edit_diary/<?php echo $row['diary_id'];?>/<?php echo $_SESSION['school_id'];?>">
                                                <i class="entypo-eye"></i>
                                                <?php echo get_phrase('view');?>
                                            </a>
                                        </li>
                                        
                                        <?php } ?>
                                        <?php if ($row['is_assigned'] == 0) { ?>
                                        <li class="divider"></li>
                                        <li>
                                            <a style="" href="#" onclick="showAjaxModal('<?php echo base_url() ?>modal/popup/modal_diary_audio/<?php echo $row['section_id'].'/'.$row['diary_id']; ?>')">
                                                <i class="fas fa-microphone" style="font-size: 15px;"></i>
                                                <?php echo get_phrase('add_audio');?>
                                            </a>
                                        </li>
                                        
                                        <?php } ?>
                                        
                                        <?php if ($row['is_assigned'] == 0) { ?>
                                        <li class="divider"></li>
                                        <!-- DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>teacher/diary/delete/<?php echo $row['diary_id'];?>/<?php echo $row['attachment'];?>');">
                                                <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php 
                                //} 
                                ?>  
                        	</td>
        				</tr>
                    <?php
                        endforeach;
                    ?>
                 </tbody>
                </table>
                <?php
                //echo $pagination;
                //echo "<br>";
                //echo "Total Records: ".$total_records;
                ?>                
			</div>
		
		</div>
	</div>


                
<script type="text/javascript">
	$(document).ready(function()
	{
	    
	    
    	$('#subject_id').change(function(){
    	    
    	    var	subject_id=$(this).val();
    	    var url = '<?php echo base_url(); ?>teacher/get_subject_student';
    	    $.ajax({
                type: 'POST',url: url,
                data:{subject_id:subject_id},
                dataType : "html",
                success: function(html) {
        	        $('#student_id').html(html);
                }
    	    });
    			
    	});	
    	
    	$('.dcs_list').on('change', function (){
            var id=this.id;
            var selected = jQuery('#'+ id +' :selected');
            var group = selected.parent().attr('label');
            jQuery(this).siblings('label').text(group);
        });
        
        
        $("#dep_c_s_id").change(function()
        {
            var section_id=$(this).val();
            var url = '<?php echo base_url();?>teacher/get_time_table_subject_list';
            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');
            $.ajax({
                type: 'POST',
                data: {section_id:section_id},
                url: url,/*get_diary_subject_list*/
                dataType: "html",
                success: function(response) {
                    $(".loader_small").remove();
                    $("#subject_list").html(response);      
                }
            });
        });
        
        var section_id=$('#dep_c_s_id').val();
        var url = '<?php echo base_url();?>teacher/get_time_table_subject_list/<?php echo $subject_id_selected ?>';
        $.ajax({
            type: 'POST',
            data: {section_id:section_id},
            url: url,
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#subject_list").html(response);      
            }
        });
        
        
	});
	
	
    var datatable_btn_url = '<?php echo base_url();?>teacher/add_edit_diary/';
    var datatable_btn     = "<a href="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new diary' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_diary');?></a>";    

	
</script>


