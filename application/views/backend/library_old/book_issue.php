<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('books_issued_list');?>
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
                    		<th><div><?php echo get_phrase('book_name');?></div></th>
                            <th><div><?php echo get_phrase('issued_to');?></div></th>
                    		<th><div><?php echo get_phrase('issue_date');?></div></th>
                    		<th><div><?php echo get_phrase('due_date');?></div></th>
                            <th><div><?php echo get_phrase('recollect_date');?></div></th>
                            <th><div><?php echo get_phrase('reffered_by');?></div></th>
                    		<th><div><?php echo get_phrase('status');?></div></th>
                            <th><div><?php echo get_phrase('action');?></div></th>
                            <th><div><?php echo get_phrase('fine');?></div></th>
                        </tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($book as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php 
							$query1	=	$this->db->get_where('book_request' , array('request_id' => $row['request_id']));
							$res1	=	$query1->result_array();
							foreach($res1 as $row1){
								$book_id		=	$row1['book_id'];
							}
							$query10	=	$this->db->get_where('book' , array('book_id' =>$book_id));
							$res10	=	$query10->result_array();
							foreach($res10 as $row10){
								$book_name		=	$row10['name'];
							}
							echo $book_name;
							?></td>
                            <td>
							<?php
							$query1	=	$this->db->get_where('book_request' , array('request_id' => $row['request_id']));
							$res1	=	$query1->result_array();
							foreach($res1 as $row1){
								$teacher_id		=	$row1['teacher_id'];
								$student_id		=	$row1['student_id'];
								$principal_id	=	$row1['principal_id'];
								$referred_id	=	$row1['referred_id'];
							}
							if($teacher_id!=0){
								$query2	=	$this->db->get_where('teacher' , array('teacher_id' => $teacher_id));
								$res2	=	$query2->result_array();
								foreach($res2 as $row2)
									echo $row2['name'];
								}
							if($student_id!=0){
								$query3	=	$this->db->get_where('student' , array('student_id' => $student_id));
								$res3	=	$query3->result_array();
								foreach($res3 as $row3)
									echo $row3['name'];
								}
							if($principal_id!=0){
								$query4	=	$this->db->get_where('principal' , array('principal_id' => $principal_id));
								$res4	=	$query4->result_array();
								foreach($res4 as $row4)
									echo $row4['name'];
								}
							?>
                            </td>
							<td><?php echo $row['issue_date'];?></td>
							<td><?php echo $row['expiry_date'];?></td>
							<td><?php echo $row['collect_date'];?></td>
                            <td>
							<?php
								if($referred_id!=0){
								$query5	=	$this->db->get_where('parent' , array('parent_id' => $referred_id));
								$res5	=	$query5->result_array();
								foreach($res5 as $row5)
									echo $row5['name'];
								}
								else{
									echo "SELF";
								}
							?>
                            </td>
                            <td>
							<?php
                            if($row['status']==0){
								echo '<p class="btn btn-success">Delievered</p>';
							} 
							if($row['status']==1){
								echo '<p class="btn btn-primary">Recollected</p>';
							}
							?>
                            </td>
							<td>
                            <div class="btn-group">
                            <?php if($row['status']==0){ ?>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_collect_book/<?php echo $row['issue_id'];?>/<?php echo $row['request_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('collect_book');?>
                                            </a>
                                                    </li>
                                </ul>
                                 <?php
                                } else { ?>
									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" >Collected</button>
                                <?php } ?>
                            </div>
                            
        					</td>
                                                <td>Rs.<?php echo $row['fine'];?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
        </div>
        		 <div>
                    <h3 class="warning">Disclaimer Note:</h3>
                    <span>Fine Per Day on returning the book after the due date is Rs.30</span>
                </div>
	</div>
</div>