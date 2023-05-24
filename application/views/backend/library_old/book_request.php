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
                            <th><div><?php echo get_phrase('principal_name');?></div></th>
                            <th><div><?php echo get_phrase('teacher_name');?></div></th>
                    		<th><div><?php echo get_phrase('student_name');?></div></th>
                    		<th><div><?php echo get_phrase('reffered_by');?></div></th>
                    		<th><div><?php echo get_phrase('status');?></div></th>
                            <th><div><?php echo get_phrase('action');?></div></th>
                        </tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($book as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $this->crud_model->get_type_name_by_id('book',$row['book_id']);?></td>
							<td><?php if($row['principal_id']!=0) { echo $this->crud_model->get_type_name_by_id('principal',$row['principal_id']); } else { echo "N/A"; }?></td>
							<td><?php if($row['teacher_id']!=0){ echo $this->crud_model->get_type_name_by_id('teacher',$row['teacher_id']);} else { echo "N/A"; }?></td>
							<td><?php if($row['student_id']!=0) { echo $this->crud_model->get_type_name_by_id('student',$row['student_id']); }else { echo "N/A"; }?></td>
							<td><?php if($row['referred_id']!=0) { echo $this->crud_model->get_type_name_by_id('parent',$row['referred_id']); } else { echo "N/A"; } ?></td>
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
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_issue_book/<?php echo $row['request_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('issue_book');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DENY LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal_update('<?php echo base_url();?>library/book_request/deny/<?php echo $row['request_id'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('deny');?>
                                            </a>
                                                    </li>
                                </ul>
                                <?php
                                } else { ?>
									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" >Done</button>
                                <?php } ?>
                            </div>
                            
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url().'library/book_issue/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('author');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="author" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('description');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="description" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('price');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="price" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                                <div class="col-sm-5">
                                    <select name="class_id" class="form-control" style="width:100%;" required>
                                    	<option value="">Please Select</option>
                                    	<?php 
										$classes = $this->db->get('class')->result_array();
										foreach($classes as $row):
										?>
                                    		<option value="<?php echo $row['class_id'];?>"><?php echo $row['name'];?></option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('category');?></label>
                                <div class="col-sm-5">
                                    <select name="book_category_id" class="form-control" style="width:100%;" required>
                                    	<option value="">Please Select</option>
                                    	<?php 
										$categ = $this->db->get('book_category')->result_array();
										foreach($categ as $row):
										?>
                                    		<option value="<?php echo $row['book_category_id'];?>"><?php echo $row['name'];?></option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('number_of_books');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="number_books" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('status');?></label>
                                <div class="col-sm-5">
                                    <select name="status" class="form-control" style="width:100%;">
                                    	<option value="available"><?php echo get_phrase('available');?></option>
                                    	<option value="unavailable"><?php echo get_phrase('unavailable');?></option>
                                    </select>
                                </div>
                            </div>
                        		<div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_book');?></button>
                              </div>
								</div>
                    </form>                
                </div>                
			</div>
			<!----CREATION FORM ENDS--->
            
		</div>
				 <div>
                    <h3 class="warning">Disclaimer Note:</h3>
                    <span>Fine Per Day on returning the book after the due date is Rs.30</span>
                </div>
	</div>
</div>