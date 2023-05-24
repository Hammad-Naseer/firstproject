<?php 
    $school_id=$_SESSION['school_id'];
    $title = 'Add Discount Type';
    if($param2>0){
        $title = 'Edit Discount Type';
    }
    else
    {
        $param2=0;
    }
    
    $edit_data_str = "select ft.* from ".get_school_db().".discount_list as ft Inner join ".get_school_db().".school_discount_list as s_ft ON ft.discount_id = s_ft.discount_id WHERE s_ft.school_id= $school_id AND ft.discount_id = $param2";
    $edit_data = $this->db->query($edit_data_str)->result_array();
    $account_type = 2;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" >
            		<i class="entypo-plus-circled"></i>
					<?php echo $title;?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'discount/discount_list/add_edit/' , array('id'=>'discont_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <div class="form-group">
                    <label for="field-1" class="control-label"><?php echo get_phrase('fee_type');?>  <span class="star">*</span>   </label>
                    <?php echo fee_type_list('fee_type' , $edit_data[0]['fee_type_id']); ?>
                </div>
				<div class="form-group">
					<label for="field-1" class="control-label"><?php echo get_phrase('title');?>  <span class="star">*</span>   </label>
					<input maxlength="100" type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'];?>">
					<input type="hidden" name="discount_id" value="<?php echo $edit_data[0]['discount_id'];   ?>">
				</div>
					
					<!--<div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Account');?>  <span class="star">*</span>   </label>
                        
						<div class="col-sm-8">
							<select name="coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select');?></option>
                                <?php coa_list_h(0,$edit_data[0]['coa_id'],0,0,0); ?>
                          </select>
						</div> 
					</div>-->

                <!--COA while issuing challan form start-->
                <fieldset class="custom_legend">
                    <?php /*  <div class="form-group">
                        <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('debit');?>  <span class="star">*</span>   </label>


                         <div class="col-sm-8">
                            <select name="issue_dr_coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php
                                coa_list_h(0,$edit_data[0]['issue_dr_coa_id'],0 ,0,$account_type); ?>
                            </select>
                        </div>

                    </div>*/ ?>

                    <div class="form-group">
                        <b class="text-dark">COA 
                        <?php echo get_phrase('while_issuing_challan_form'); ?>
                        :</b>
                        <label for="field-2" class="control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
                        <select name="issue_cr_coa_id" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <?php
                            coa_list_h(0,$edit_data[0]['issue_dr_coa_id'],0 , 0 , $account_type); ?>
                        </select>
                    </div>
                </fieldset>
                <!--COA while issuing challan form End-->
                    
                    
                                <!--COA while Recieving challan form start-->			
		<?php /*	<fieldset class="custom_legend">
  					<legend class="custom_legend"><?php echo get_phrase('COA');?> 
                    <?php echo get_phrase('while_receiving_challan_form'); ?>
                    :</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('debit');?>  <span class="star">*</span>   </label>
                        
						<div class="col-sm-8">
							<select name="receive_dr_coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
<?php
  coa_list_h(0,$edit_data[0]['receive_dr_coa_id'],0 , 0 , $account_type); ?>
                          </select>
						</div> 
					</div>

                <?php /*
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('credit');?>  <span class="star">*</span>   </label>
						<div class="col-sm-8">
							<select name="receive_cr_coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
<?php
  coa_list_h(0,$edit_data[0]['receive_cr_coa_id'],0 , 0 , $account_type); ?>
                          </select>
						</div> 
					</div>
                */ ?>
               <?php /* </fieldset> */ ?>
                <!--COA while Recieving challan form End-->
                
                
                <!--COA while cancelling challan form start-->			
			<?php /*<fieldset class="custom_legend">
  					<legend class="custom_legend">COA 
                    <?php echo get_phrase('while_cancelling_challan_form'); ?>
                    :</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('debit');?>  <span class="star">*</span>   </label>
                        
						<div class="col-sm-8">
							<select name="cancel_dr_coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
<?php
  coa_list_h(0,$edit_data[0]['cancel_dr_coa_id'],0 , 0 , $account_type); ?>
                          </select>
						</div> 
					</div>
                    
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label>
						<div class="col-sm-8">
							<select name="cancel_cr_coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
<?php
  coa_list_h(0,$edit_data[0]['cancel_cr_coa_id'],0 , 0 , $account_type); ?>
                          </select>
						</div> 
					</div>
                </fieldset> */ ?>
               
                <!--COA while cancelling challan form End-->




                    
                    
					<?php /*
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Amount');?></label>
                        
						<div class="col-sm-8">
							<input type="text" class="form-control" name="percent" value="<?php echo $edit_data[0]['percent']; ?>" >
						</div> 
					</div>
					*/ ?>
					<div class="form-group">
						<label for="field-2" class="control-label"><?php echo get_phrase('status');?>  <span class="star">*</span>   </label>
				        <select name="status" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
				            <option value=""><?php echo get_phrase('select'); ?></option>
                            <option value="0"
        					<?php
            					if($edit_data[0]['status']==0){
            						echo 'selected';
            					}
        					?>><?php echo get_phrase('inactive');?></option>
        					<option value="1" <?php
        					if($edit_data[0]['status']==1){
        						echo 'selected';
        					}?>><?php echo get_phrase('active');?></option>
				        </select> 
					</div>
			<?php /*		
				
					<div class="form-group">
						<label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('Is_percentage');?></label>
                        
						<div class="col-sm-8">
<select class="form-control" name="is_percentage">
	
<option value="">Select</option>
	
	<?php
	$ary=array('no','yes');
	if($edit_data[0]['is_percentage']==1){
		
		$select1="selected";
	}elseif($edit_data[0]['is_percentage']==0){
		
		$select0="selected";
		
	}
	
	
	?>
	
	
<option <?php echo  $select0; ?> value="0"><?php echo $ary[0]; ?></option>
<option <?php echo  $select1; ?>    value="1"><?php echo $ary[1]; ?></option>	
	
</select>
							
							
				
							
							
						</div>
					</div>	
				
				
				*/
			?>	
				
				
				
					<div class="form-group">
					    <div class="float-right">
        					<button type="submit" class="modal_save_btn">
        						<?php echo get_phrase('save');?>
        					</button>
        					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        						<?php echo get_phrase('cancel');?>
        					</button>
        				</div>
					</div>
				
				
				
				
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>