<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('leave_requests_list');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------->
        
	
		<div class="tab-content">
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane box active" id="list">
					
                <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('leave_category');?></div></th>
                            <th><div><?php echo get_phrase('teacher_name');?></div></th>
                            <th><div><?php echo get_phrase('start_date');?></div></th>
                            <th><div><?php echo get_phrase('end_date');?></div></th>
                            <th><div><?php echo get_phrase('description');?></div></th>
                    		<th><div><?php echo get_phrase('request_date');?></div></th>
                    		<th><div><?php echo get_phrase('approval_date');?></div></th>
                            <th><div><?php echo get_phrase('proof_doc');?></div></th>
                    		<th><div><?php echo get_phrase('status');?></div></th>
                            <th><div><?php echo get_phrase('action');?></div></th>
                        </tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($leaves as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $this->crud_model->get_type_name_by_id('leave_category',$row['leave_id']);?></td>
                            <td><?php echo $this->crud_model->get_type_name_by_id('teacher',$row['teacher_id']);?></td>
							<td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td><?php echo $row['reason']; ?></td>
                            <td><?php echo $row['request_date']; ?></td>
                            <td><?php if($row['approval_date']!=""){ echo $row['approval_date']; }else{ echo "N/A"; } ?></td>
                            <td><?php if($row['proof_doc']!=""){ ?> <a href="uploads/teacher_image/<?php echo $row['proof_doc']; ?>" target="_blank"> 
                             <?php echo get_phrase('view_upload_file');?>
                            </a> <?php }else{ echo "No File Found"; }; ?></td>
							<td>
							<?php
                            if($row['status']==0){
								echo '<p class="btn btn-primary">Pending</p>';
							} 
							if($row['status']==1){
								echo '<p class="btn btn-success">Approved</p>';
							}
							if($row['status']==2){
								echo '<p class="btn btn-danger">Denied</p>';
							}
							?>
                            </td>
							<td>
                            <div class="btn-group">
                                <?php if($row['status']==0){ ?>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" >
                                     <?php echo get_phrase('action');?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                   
                                   <!-- APPROVE LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal_approve('<?php echo base_url();?>admin/manage_leaves_teacher/approve/<?php echo $row['request_id'];?>');">
                                            <i class="entypo-check"></i>
                                                <?php echo get_phrase('approve');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DENY LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal_deny('<?php echo base_url();?>admin/manage_leaves_teacher/reject/<?php echo $row['request_id'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('reject');?>
                                            </a>
                                                    </li>
                                </ul>
                                <?php
                                } else { ?>
									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" > <?php echo get_phrase('done');?></button>
                                <?php } ?>
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