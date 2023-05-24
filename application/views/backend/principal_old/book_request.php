<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('book_requests_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('request_a_book');?>
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
                            <th><div><?php echo get_phrase('request_from');?></div></th>
                            <th><div><?php echo get_phrase('request_date');?></div></th>
                    		<th><div><?php echo get_phrase('status');?></div></th>
                        </tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($book as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $this->crud_model->get_type_name_by_id('book',$row['book_id']);?></td>
							<td><?php if($row['principal_id']!=0) { echo $this->crud_model->get_type_name_by_id('principal',$row['principal_id']); } else { echo "N/A"; }?></td>
                            <td><?php echo $row['request_date']; ?></td>
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
					    </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url().'principal/book_request/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('please_select_book');?></label>
                                <div class="col-sm-5">
                                    <select name="book_id" class="form-control" style="width:100%;" required>
                                    	<option value="">Please Select</option>
                                    	<?php 
										$book = $this->db->get('book')->result_array();
										foreach($book as $row):
										?>
                                    		<option value="<?php echo $row['book_id'];?>"><?php echo $row['name'];?></option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('request_book');?></button>
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