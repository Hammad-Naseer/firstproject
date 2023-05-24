<div class="row">
<?php 
$q="SELECT sb.subject_id, sb.name AS subject, sc.title AS section, c.name as class,d.title as deptartment,sb.teacher_id as teacher,sc.section_id FROM ".get_school_db().".subject sb INNER JOIN ".get_school_db().".class_section sc ON sc.section_id = sb.section_id
INNER JOIN ".get_school_db().".class c ON sc.class_id = c.class_id INNER JOIN ".get_school_db().".departments d ON d.departments_id = c.departments_id where sc.section_id=".$section_id." AND sc.school_id=".$_SESSION['school_id']."";
$subjects=$this->db->query($q)->result_array();
?>
	<div class="col-md-12">
    
		<div class="tab-content">            
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane box active" id="list">
			<div id="error"></div>
                <table class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                		<th><div><?php echo get_phrase('subject');?></div></th>
                		<th><div><?php echo get_phrase('department');?></div></th>
                		<th><div><?php echo get_phrase('class');?></div></th>
                		<th><div><?php echo get_phrase('section');?></div></th>
                		
                		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php
                        $count = 1;
						foreach($subjects as $row):?>
                        <tr id="<?php echo $row['subject_id']?>">
                        <td><?php echo $row['subject'];?></td>
                        <td><?php echo $row['deptartment']?></td>
                        <td><?php echo $row['class'];?></td>
                        <td><?php echo $row['section']?></td>
                       	
							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?php echo get_phrase('action');?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                     <!-- Assign Teacher LINK -->
                                     <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_subject_teacher/<?php echo $row['section_id'].'-'.$row['subject_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('assign_teacher');?>
                                            </a>
                                                    </li>
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_subject/<?php echo $row['subject_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                                    </li>


                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" id="delete<?php echo $row['subject_id'];?>" >
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                                    </li>
                                </ul>
                            </div>
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			
            
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
	
$("a[id^='delete']").on('click',function(){
	str=$(this).attr('id');
	subject_id=str.replace('delete','');

		$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>subject/check_delete_request/"+subject_id,

					data: ({subject_id:subject_id}),
					
					success: function(response) {
						
						if(response=='')
						{
							$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>subject/subjects/delete/"+subject_id,

					data: ({subject_id:subject_id}),
					
					success: function(response) {
						$('tr#'+subject_id).remove();						
					}


				});
						}
						else{
							$('#error').text('<?php echo get_phrase("please_unassign_teacher_to_delete_this_record");?>');
						}
									
					}


				});
	/**/
			
			
});	
	});
</script>