<?php
    if($this->uri->segment(5) == "edit"){
        
        $b_c_b_id = $this->uri->segment(4);
        $qur = "select * from ".get_school_db().".bank_cheque_books where b_c_b_id = ".$b_c_b_id." ";
        $check_book_detail = $this->db->query($qur)->result_array();
        
        $title = "Edit Cheque Book";
        $url = "bank_detail/edit_cheque_book";
        $btn_text = "Update";
        $hidden_field = "<input type='hidden' name='b_c_b_id' value='$b_c_b_id'>";
    ?>
    
    <div class="panel panel-primary" data-collapsed="0">
    <div class="panel-title black2">
		<i class="entypo-plus-circled"></i>
        <?php echo $title;?>
    </div>
    <div class="panel-body">
	    <form action="<?=base_url().$url;?>" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered validate">
	        <?php echo $hidden_field; ?>
            <div class="form-group" id="batch_number" >
                <label class="control-label"><?php echo get_phrase('batch_number');?><span class="red"> * </span></label>
                <input type="text" class="form-control" name="batch_number" id="batch_number" value="<?php echo $check_book_detail[0]['batch_number'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>                
            </div>            
            <div class="form-group">
                <label class="control-label" for="cheque_start_number"><?php echo get_phrase('cheque_start_leaf');?><span class="red"> * </span></label>
                <input type="text" class="form-control" name="cheque_start_number" id="cheque_start_number"  value="<?php echo $check_book_detail[0]['start_cheque_number'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="cheque_end_number"><?php echo get_phrase('cheque_end_leaf');?><span class="red"> * </span></label>
                <input type="text" class="form-control" name="cheque_end_number" id="cheque_end_number" value="<?php echo $check_book_detail[0]['end_cheque_number'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="status"><?php echo get_phrase('status');?><span class="red"> * </span></label>
                <?php echo status("status", "form-control", $check_book_detail[0]['status'] , "status") ?>
            </div>
            <div class="form-group">
                <div class="float-right">
        			<button type="submit" id="save_btn" class="modal_save_btn">
        				<?php echo $btn_text;?>
        			</button>
        			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        				<?php echo get_phrase('cancel');?>
        			</button>
        		</div>
        	</div>
        </form>                
    </div>  
</div>    
    <?php
    
    }else{
        
        $title = "Add Cheque Book";
        $url = "bank_detail/add_cheque_book";
        $btn_text = "Save";
        $hidden_field = "<input type='hidden' name='bank_id' value='".$this->uri->segment(4)."'>";
        
    ?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-title black2">
		<i class="entypo-plus-circled"></i>
        <?php echo $title;?>
    </div>
    <div class="panel-body">
	    <form action="<?=base_url().$url;?>" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered validate">
	        <?php echo $hidden_field; ?>
            <div class="form-group" id="batch_number" >
                <label class="control-label"><?php echo get_phrase('batch_number');?><span class="red"> * </span></label>
                <input type="text" class="form-control" name="batch_number" id="batch_number" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>                
            </div>            
            <div class="form-group">
                <label class="control-label" for="cheque_start_number"><?php echo get_phrase('cheque_start_leaf');?><span class="red"> * </span></label>
                <input type="text" class="form-control" name="cheque_start_number" id="cheque_start_number"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="cheque_end_number"><?php echo get_phrase('cheque_end_leaf');?><span class="red"> * </span></label>
                <input type="text" class="form-control" name="cheque_end_number" id="cheque_end_number" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="status"><?php echo get_phrase('status');?><span class="red"> * </span></label>
                <?php echo status("status", "form-control", "" , "status") ?>
            </div>
            <div class="form-group">
                <div class="float-right">
        			<button type="submit" id="save_btn" class="modal_save_btn">
        				<?php echo $btn_text;?>
        			</button>
        			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        				<?php echo get_phrase('cancel');?>
        			</button>
        		</div>
        	</div>
        </form>                
    </div>  
</div>  
<?php
}
?>