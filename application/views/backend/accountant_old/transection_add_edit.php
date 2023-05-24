<?php 

$school_id=$_SESSION['school_id'];

$edit_data=$this->db->get_where('account_transection' , array('transection_id' => $param2,'school_id'=>$school_id))->result_array();

?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_student');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url().'transection_account/account_transection/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                
                	
                	
                	<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('type');?></label>
                        
						<div class="col-sm-5">
							<?php
	
		echo type("type","form-control", $edit_data[0]['type'])
						
							?>
						</div> 
					</div>	
                	
	
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('title');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'];?>">
							
							
							
							
								<input type="hidden" name="transection_id" value="<?php echo $edit_data[0]['transection_id'];   ?>">
					
							
							
							
						</div>
					</div>
					
				<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Chart_of_account');?></label>
                        
						<div class="col-sm-5">
							<select name="coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
 
<?php

coa_list_h(0,$edit_data[0]['coa_id']); ?>

 </select>
</div> 
</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('voucher_num_#');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="voucher_num" value="<?php echo $edit_data[0]['voucher_num']; ?>" >
						</div> 
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('method');?></label>
                        
						<div class="col-sm-5">
						<?php
		
echo Method("method","form-control",$edit_data[0]['method']);
						
						?>
						
						</div> 
					</div>
					
				
			<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('amount');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="amount" value="<?php echo $edit_data[0]['amount']; ?>" >
						</div> 
					</div>		
				<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control datepicker" name="date" value="<?php echo date_dash($edit_data[0]['date']); ?>" >
						</div> 
					</div>			
				
				<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('detail');?></label>
                        
						<div class="col-sm-5">
						
						<textarea name="detail" ><?php echo $edit_data[0]['detail']; ?></textarea>
						</div> 
					</div>	
				
				
				<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('receipt_num');?></label>
                       <div class="col-sm-5">
						
							<input type="text" class="form-control" name="receipt_num" value="<?php echo $edit_data[0]['receipt_num']; ?>" >
						</div> 
					</div>	



	<div class="form-group">
<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Is processed');?></label>
<div class="col-sm-5">
<?php	
				
echo isprocessed('isprocessed',"form-control",$edit_data[0]['isprocessed'])

?>		
					
					
					
						</div> 
					</div>	
			
			
			
			
				<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('');?></label>
                        
						<div class="col-sm-5">
						
			
						
				<button type="submit" class="btn btn-default">Save/Edit</button>		
						
						
						</div> 
					</div>
				
				
				
				
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>