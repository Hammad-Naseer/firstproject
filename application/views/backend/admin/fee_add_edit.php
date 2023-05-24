
<?php 

$school_id=$_SESSION['school_id'];
$title = 'Add Fee Type';
if($param2>0){
    $title = 'Edit Fee Type';
}
else
{
     $param2=0;
}


/*$edit_data=$this->db->get_where(get_school_db().'.fee_types' , array('fee_type_id' => $param2,'school_id'=>$school_id))->result_array();*/

$edit_data_str = "select ft.* from ".get_school_db().".fee_types as ft
                          Inner join ".get_school_db().".school_fee_types as s_ft ON ft.fee_type_id = s_ft.fee_type_id
                          WHERE s_ft.school_id= $school_id
                          AND ft.fee_type_id = $param2";
$edit_data = $this->db->query($edit_data_str)->result_array();

$account_type = 1;
//echo "<pre>";
//print_r($_SESSION);
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" >
            		<!--<i class="entypo-plus-circled"></i>-->
					<?php echo $title ;?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url().'fee_types/fee_types_c/add_edit/' ,
                    array('id'=>'fee_form','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group">
						<label for="field-1" class="control-label"><?php echo get_phrase('title');?>  <span class="star">*</span>   </label>
						<input maxlength="200" type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'];?>">
						<input type="hidden" name="fee_type_id" value="<?php echo $edit_data[0]['fee_type_id'];   ?>">
					</div>
					
					<!--<div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Account');?>  <span class="star">*</span>   </label>
                        
						<div class="col-sm-8">
							<select name="coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
<?php
  coa_list_h(0,$edit_data[0]['coa_id'] , 1 , 1,0); ?>




                          </select>
						</div> 
					</div>-->
					
                    
                    
						
                        
						
							
                          
			<!--COA while issuing challan form start-->			
			    <fieldset class="custom_legend">
                    <div class="form-group">
                        <b class="text-dark"><?php echo get_phrase('COA'); ?>
                    <?php echo get_phrase('while_issuing_challan_form'); ?>
                    :</b>
						<label for="field-2" class="control-label"><?php echo get_phrase('debit');?>  <span class="star">*</span>   </label>
						<select name="issue_dr_coa_id" class="form-control select2"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <?php coa_list_h(0,$edit_data[0]['issue_dr_coa_id'] ,0 , 0 , $account_type); ?>
                        </select>
					</div>
                    <div class="form-group">
                        
						<label for="field-2" class="=control-label"><?php echo get_phrase('credit');?>  <span class="star">*</span>   </label>
						<select name="issue_cr_coa_id" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <?php coa_list_h(0,$edit_data[0]['issue_cr_coa_id'] ,0 , 0 , $account_type); ?>
                        </select>
					</div>
                </fieldset>
                <!--COA while issuing challan form End-->
                
                
            <!--COA while Recieving challan form start-->			
			<fieldset class="custom_legend">
                <div class="form-group">
                    <b class="text-dark"><?php echo get_phrase('COA'); ?> 
                    <?php echo get_phrase('while_receiving_challan_form'); ?>:
                    </b>
					<label for="field-2" class="control-label"><?php echo get_phrase('debit');?>  <span class="star">*</span>   </label>
					<select name="receive_dr_coa_id" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select');?></option>
                        <?php coa_list_h(0,$edit_data[0]['receive_dr_coa_id'] ,0 , 0 , $account_type); ?>
                  </select>
				</div>
                <div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('credit');?>  <span class="star">*</span>   </label>
					<select name="receive_cr_coa_id" class="form-control select2" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value=""><?php echo get_phrase('select');?></option>
                        <?php coa_list_h(0,$edit_data[0]['receive_cr_coa_id'] ,0 , 0 , $account_type); ?>
                  </select> 
				</div>
            </fieldset>
            <!--COA while Recieving challan form End-->
                
                
                <!--COA while cancelling challan form start-->			
			<?php /*<fieldset class="custom_legend">
  					<legend class="custom_legend"><?php echo get_phrase('COA'); ?> 
                    <?php echo get_phrase('while_cancelling_challan_form'); ?>
                    :</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('debit');?>  <span class="star">*</span>   </label>
                        
						<div class="col-sm-8">
							<select name="cancel_dr_coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
<?php
  coa_list_h(0,$edit_data[0]['cancel_dr_coa_id'] ,0 , 0 , $account_type); ?>
                          </select>
						</div> 
					</div>
                    
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('credit');?>  <span class="star">*</span>   </label>
						<div class="col-sm-8">
							<select name="cancel_cr_coa_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
<?php
  coa_list_h(0,$edit_data[0]['cancel_cr_coa_id'] ,0 , 0 , $account_type); ?>
                          </select>
						</div> 
					</div>
                </fieldset> */ ?>
               
                <!--COA while cancelling challan form End-->
                

                
                   
                    
			
					
			<div class="form-group">
			    <label for="field-2" class="control-label"><?php echo get_phrase('status');?>  <span class="star">*</span>   </label>
				<select name="status" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
				<option value=""><?php echo get_phrase('select'); ?></option>
					<option value="0"
					<?php
    					if($edit_data[0]['status']==0){
    						echo 'selected';
    					}
					?> ><?php echo get_phrase('inactive'); ?></option>
					<option value="1" <?php
					if($edit_data[0]['status']==1){
						echo 'selected';
					}
					?>><?php echo get_phrase('active'); ?></option>
				</select> 
			</div>	
			<div class="form-group">		
				<div class="float-right">
				    <button type="submit" id="btnSubmit" class="modal_save_btn">
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
