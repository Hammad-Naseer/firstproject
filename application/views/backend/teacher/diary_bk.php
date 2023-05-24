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
$( window ).load(function() 
{
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});

</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
          <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/diary.png">  <?php echo get_phrase('diary');?>
        </h3>   
        <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_diary/');" class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_diary');?>                
        </a> 
    </div> 
</div>


 <form method="post" action="<?php echo base_url();?>teacher/diary" class="form">
<div class="thisrow">
<div class="row">
<div class="col-sm-12">
<div class="col-sm-6">
<input type="text" name="std_search" class="form-control" value="<?php 
                if(!empty($std_search))
                {
					echo $std_search;
				}
                ?>">
</div>

<div class="col-sm-6">
				
				       <select id="month_year" name="month_year" class="form-control" >
                        <?php
                        $academic_year_id= intval($_SESSION['academic_year_id']);
                        $qur_rr=$this->db->query("select * 
                            from ".get_school_db().".acadmic_year 
                            where school_id=".$_SESSION['school_id']." 
                            and academic_year_id=$academic_year_id
                            ")->result_array();
                        $start_date=$qur_rr[0]['start_date'];
                        $end_date=$qur_rr[0]['end_date'];
                        echo month_year_option($start_date,$end_date,$subject_month_year);
                        ?>
                    </select>
				
				
			</div>
</div>
</div>
<br />
	<div class="row">
		
		<div class="col-sm-12">
			
			
			
			<div class="col-sm-6">
				
				  <label id="select_selection"></label>  
                    <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" >
                        <?php echo get_teacher_dep_class_section_list($teacher_section, $section);
                        ?>
                    </select>
				
				
			</div>
			
			<div class="col-sm-6">
				
				         <select name="subject" id="subject_list" class="form-control" >
                        <option value=""><?php echo get_phrase('select_subject');?></option>
                    </select>
				
				
			</div>
					
			</div>
			</div>
			<div class="row pd10">
			
				<div class="col-sm-12">
				
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
			
			</form>
			</div>
			
			
			
		
	</div>
	










        
        <script>
            jQuery(document).ready(function () 
            {
                jQuery('.dcs_list').on('change', function (){
                    var id=this.id;
                    var selected = jQuery('#'+ id +' :selected');
                    var group = selected.parent().attr('label');
                    jQuery(this).siblings('label').text(group);
                });
            });
        </script>
            

<script>
$(document).ready(function()
{
    $("#dep_c_s_id").change(function()
    {
        var section_id=$(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {section_id:section_id},
            url: "<?php echo base_url();?>teacher/get_time_table_subject_list",/*get_diary_subject_list*/
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#subject_list").html(response);      
            }
        });
    });

    var section_id=$('#dep_c_s_id').val();
    $.ajax({
        type: 'POST',
        data: {section_id:section_id},
        url: "<?php echo base_url();?>teacher/get_time_table_subject_list/<?php echo $subject_id_selected ?>",
        dataType: "html",
        success: function(response) {
            $(".loader_small").remove();
            $("#subject_list").html(response);      
        }
    });
});
</script>

<div class="row">
	<div class="col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered" id="teacher_diary_datatable">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('s_no');?></div></th>
                    		<th style="width:140px;"><div><?php echo get_phrase('subject');?></div></th>
                    		<?php /*?><th><div><?php echo get_phrase('assign_date');?></div></th>
                           	<th><div><?php echo get_phrase('due_date');?></div></th><?php */?>
                           	<th><div><?php echo get_phrase('details');?></div></th>
                           	<th><div><?php echo get_phrase('submission_details');?></div></th>
                            <th><div><?php echo get_phrase('action');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php 
                    	$j=$start_limit;
                        // 	echo "<pre>";
                        // 	print_r($diary);
                    	$count = 1;foreach($diary as $row):
                    	$j++;
                    	?>
                        <tr>
                        	<td><?php echo $j; ?></td>
							<td><?php echo $row['subject_name'].' - '.$row['subject_code'];?></td>
                            
                            <td style="line-height:22px;">
                            	<div><strong><?php echo get_phrase('assign_date');?>:</strong><?php echo convert_date($row['assign_date']);?></div>
                                <div style="font-size:14px; color:#0A73B7;"><strong><?php echo get_phrase('title').':'.$row['title'];?></strong></div>
								<div>
								<?php
                                if($row['attachment']!="")
                                {?>
                                    <strong style="margin-right:10px;"><?php echo get_phrase('attachment'); ?>:</strong><a href="<?php echo display_link($row['attachment'],'diary');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
                                </div>
                                    
								<div style="color:#972d2d;"><strong><?php echo get_phrase('due_date');?>:</strong><?php echo convert_date($row['due_date']);?></div>
                                <?php   
                                }
                                 /*if($row['diary_type']==1){
                            		echo "<strong><br/>Type:</strong> Class Diary";
                            	}else if($row['diary_type']==2){
                            		echo "<br/>Type: Subject Diary";		
                            	}*/
                            	
                                $sec_arr = section_hierarchy($row['section_id']);
								
                                echo '<strong>'.get_phrase('section').':</strong> '.$sec_arr['d'].' - '.$sec_arr['c'].' - '.$sec_arr['s']; 					
                                echo'<span class="item">';
                                
                                ?>
                                <br/><strong><?php echo get_phrase('students');?>:</strong> 
                                    <a style="color:#0A73B7;" href="#" onclick="showAjaxModal('<?php echo base_url() ?>modal/popup/modal_diary_student/<?php echo $row['section_id'].'/'.$row['diary_id'].'/'.$row['subject_name'].'-'.$row['subject_code']; ?>')">
                                    
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('view');?>/<?php echo get_phrase('manage');?>/<?php echo get_phrase('assign');?>
                                       
                                    </a>
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
                            </td>
                            <td>
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
                            <td>
                                <?php
                                if($row['is_submitted_by'] == 0)
                               /* if ( (date('Y-m-d') < $row['due_date']) && $row['is_submitted'] == 0)*/
                                {
                                ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase("planner"); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                        <!-- EDITING LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_diary/<?php echo $row['diary_id'];?>/<?php echo $_SESSION['school_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        
                                        <!-- DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>teacher/diary/delete/<?php echo $row['diary_id'];?>/<?php echo $row['attachment'];?>');">
                                                <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <?php
                                }
                                ?>  
                        	</td>
        				</tr>
                 <?php endforeach;?>
                 </tbody>
                </table>
<?php
echo $pagination;
echo "<br>";
echo "Total Records: ".$total_records;
?>                
			</div>
            
			<?php 
			/*
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open_multipart(base_url().'teacher/diary/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('class_name');?></label>
                                <div class="col-sm-5">
                                <?php $class_name		=	$this->db->get_where('class', array('class_id'=>$row['class_id']))->row()->name;	?>
                                     <input type="hidden" class="form-control" name="class_id" value="<?php echo $row['class_id']; ?>" readonly/>
                                     <input type="text" class="form-control" value="<?php echo $class_name; ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('teacher');?></label>
                                <div class="col-sm-5">
                                	<?php $teacher_id		=	$this->db->get_where('class', array('class_id'=>$class_id))->row()->teacher_id;	?>
                                	<?php $teacher_name		=	$this->db->get_where('teacher', array('teacher_id'=>$teacher_id))->row()->name;	?>
                                    <input type="hidden" class="form-control" name="teacher_id" value="<?php echo $teacher_id; ?>" readonly/>
                                    <input type="hidden" class="form-control" name="class_id" value="<?php echo $class_id; ?>" readonly/>
									<input type="text" class="form-control" name="teacher_name" value="<?php echo $teacher_name; ?>" readonly/>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('subject');?></label>
                                <div class="col-sm-5">
<select name="subject_id" class="form-control" style="width:100%;" required>
   <option value="">Please Select</option>
	<?php 
$subjects = $this->db->get_where('subject', array('class_id'=>$class_id))->result_array();
foreach($subjects as $row):
?>
                                    		<option value="<?php echo $row['subject_id'];?>"><?php echo $row['name'];?></option>
<?php
endforeach;
?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('due_date');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control datepicker" name="due_date" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('title');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="title" />
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('detail');?></label>
                                <div class="col-sm-5">
                                    <textarea name="task" class="form-control"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('attachment');?></label>
                            <div class="col-sm-5">
                            <input type="file" id="info" name="attach_file" class="form-control">
                            </div>
                             </div>
                            
                            
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('assign_task');?></button>
                              </div>
						   </div>
						   
                    </form>                
                </div>                
			</div>
			*/
?>
			
		
		</div>
	</div>
</div>


                
<script type="text/javascript">
	jQuery(document).ready(function()
	{
		/*var datatable = $("#teacher_diary_datatable").dataTable();*/
		
		/*$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});*/
	$('#subject_id').change(function(){
var	subject_id=$(this).val();
		
		
		
		$.ajax({
     type: "POST",
  url: "<?php echo base_url(); ?>teacher/get_subject_student",
   
   data: ({subject_id:subject_id}),
   dataType : "html",
    success: function(html) {
    	//alert(html);
    	$('#student_id').html(html);
 }
    
 
 });
			
	});	
		
		
	});
</script>


<!--<script>

$(function(){ /* to make sure the script runs after page load */

	$('.item').each(function(event){ /* select all divs with the item class */
	
		var max_length = 0; /* set the max content length before a read more link will be added */
		
		if($(this).html().length > max_length){ /* check for content length */
			
			var short_content 	= $(this).html().substr(0,max_length); /* split the content in two parts */
			var long_content	= $(this).html().substr(max_length);
			
			$(this).html(short_content+
						 '<a href="#" class="read_more mycolor"><br/>Read More</a>'+
						 '<span class="more_text" style="display:none;">'+long_content+'</span>'); /* Alter the html to allow the read more functionality */
						 
			$(this).find('a.read_more').click(function(event){ /* find the a.read_more element within the new html and bind the following code to it */
 
				event.preventDefault(); /* prevent the a from changing the url */
				$(this).hide(); /* hide the read more button */
				$(this).parents('.item').find('.more_text').show(); /* show the .more_text span */
		 
			});
			
		}
		
	});
 
 
});


</script>-->

<style>


.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #63b7e7; /* Blue */
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
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}



</style>
