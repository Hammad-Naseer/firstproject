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
$( window ).on("load",function() 
{
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});

</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('notes'); ?>
        </h3>
    </div>
</div>

<div class="col-md-12">
	<table class="table table-bordered table_export table-responsive" data-step="2" data-position='top' data-intro="notes records">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('s_no');?></div></th>
                    		<th style="width:250px;"><div><?php echo get_phrase('description');?></div></th>
                           	<th><div><?php echo get_phrase('details');?></div></th>
                           	<th><div><?php echo get_phrase('attachments');?></div></th>
                            <th><div><?php echo get_phrase('action');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php 
                    	$j=$start_limit;
                    	$count = 1;foreach($notes as $row):
                    	$j++;
                    	?>
                        <tr>
                        	<td class="td_middle"><?php echo $j; ?></td>
                        	<td>
                        	    <strong>Title :</strong>
                        	    <?php echo $row['notes_title'];?>
                        	    <br>
                        	    <strong>Teacher Name :</strong>
                        	    <?php echo get_teacher_name($row['teacher_id']);?>
                        	    <br>
                        	   
                        	</td>
                        	<td><?php //echo $row['remarks'];?>
                        	 <strong>Created Date :</strong>
                        	    <?php echo date_view($row['inserted_at']);?>
                        	    
                        	    <?php
                                	if($row['is_assigned'] == 1)
                                	{
                                	    echo get_notes_assigned_section($row['notes_id']);
                                	}else{
                                	    echo "<br><strong>Status :</strong><span style='color:red;'>Not Assigned</span>";
                                	}
                            	?>
                        	
                        	</td> 
                        	<td> 
                        	
                        	    <?php 
                        	   
                        	        $docs = explode(',', $row['urls']);
                        	       
                        	        foreach($docs as $doc){
                        	            
                        	   ?>         
                        	             <a target="_blank" download="" title="<?php echo $doc; ?>" href="<?php echo base_url().$doc; ?>"><span class="glyphicon glyphicon-download-alt" data-step="3" data-position='top' data-intro="download attachement file"></span></a>
                        	   <?
                        	        }
                        	    ?> 
                        	</td> 
							<td class="td_middle">
							    <div class="btn-group" data-step="4" data-position="left" data-intro="Assign notes to students">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        <li>
                                            <?php 
                                                if($row['is_status'] == '1'){
                                            ?>
                                            <a href="<?=base_url().'event_annoucments/event_announcement_detail/'.$row['event_id']?>">
                                                <i class="entypo-user"></i>
                                                <?php echo get_phrase('view_assign_report');?>
                                            </a>
                                            <?php }else{ ?>
                                            <a href="<?php echo base_url(); ?>notes/assign_notes/<?php echo $row['notes_id'];?>">
                                                <i class="entypo-user"></i>
                                                <?php echo get_phrase('assign');?>
                                            </a>
                                            <?php } ?>
                                            
                                        </li>
                                         
                                        <li class="divider"></li>
                                        <li>
                                            <a href="<?php echo base_url(); ?>notes/notes_details/<?php echo $row['notes_id'];?>">
                                                <i class="entypo-eye"></i>
                                                <?php echo get_phrase('view');?>
                                            </a>
                                            
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>notes/notes_delete/<?php echo $row['notes_id'];?>/<?php echo str_encode($row['urls']);?>');">
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


<script>
    
    $("#section_id").change(function() {
    	$('#item_list').html('');
        var section_id = $(this).val();
        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
        $.ajax({
            type: 'POST',
            data: { section_id: section_id },
            url: "<?php echo base_url();?>teacher/get_section_student_subject",
            dataType: "html",
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                $("#icon").remove();
                $("#subject_id").html(obj.subject);
            }
        });
    });
        
    var datatable_btn_url = '<?php echo base_url();?>notes/create_notes';
    var datatable_btn     = "<a href="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new notes' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('create_notes');?></a>";    

</script>