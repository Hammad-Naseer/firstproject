<?php
    $value=$this->uri->segment('4');
    $circular_staff_id=$this->uri->segment('5');
    $title="";
    if($value=='create')
    {
    	$title=get_phrase('add_staff_circular');
    }
    else
    {
    	$title=get_phrase('edit_staff_circular');
    	$p="select * from ".get_school_db().".circular_staff where circular_staff_id= ".str_decode($circular_staff_id)." and school_id=".$_SESSION['school_id']." ";
    	$query=$this->db->query($p)->result_array();
    }
?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title black2">
            <i class="entypo-plus-circled"></i><?php echo $title;?>
        </div>
    </div>    
    <div class="panel-body">
        <?php echo form_open_multipart(base_url().'circular_staff/circulars_staff/'.$value.'/'.$circular_staff_id , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'circular_add'));?>
        <div class="form-group" id="students">
            <label class="control-label">
                <?php echo get_phrase('select_staff');?>
            </label>
            <select name="staff_id" id="staff_id" class="form-control">
                <?php echo staff_list('',$query[0]['staff_id']); ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo get_phrase('title');?><span class="red"> * </span></label>
                <input type="text" class="form-control" name="circular_title" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $query[0]['circular_title'];?>" />
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo get_phrase('date');?><span class="red"> * </span></label>
            <input type="date" class="form-control" name="circular_date" id="circular_date" value="<?php echo $query[0]['circular_date'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
            <div id="error_end"></div>
        </div>
        <div class="form-group">
            <label class="control-label">
                <?php echo get_phrase('detail');?> <span class="red"> * </span> </label>
                <textarea name="circular" id="circular" rows="5" placeholder="<?php echo get_phrase('add_circular');?>" class="form-control" maxlength="1000" oninput="count_value('circular','area_count','1000')" required><?php echo $query[0]['circular'];?></textarea>
                <div id="area_count" class="col-sm-12 "></div>
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo get_phrase('is_active');?><span class="red"> * </span></label>
        	<select name="is_active" class="form-control">
        		<option value="1" <?php if($query[0]['is_active']==1){ echo "selected";} ?>><?php echo get_phrase('yes');?></option>
        		<option value="0" <?php if($query[0]['is_active']==0){ echo "selected";} ?>><?php echo get_phrase('no');?></option>
        	</select>
        </div>
        <div class="form-group">
            <label class="control-label">
                <?php echo get_phrase('attachment');?>
            </label>
                <input value="" type="file" class="form-control" name="image2" id="image2" onchange="file_validate('image2','doc','img_f_msg')">
                <span style="color: green;">
                <?php echo get_phrase('allowed_file_size');?>
                :  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
                <br />
                <span style="color: red;" id="img_f_msg"></span>
                <span id="id_file">				
    		    <?php	
                    $val_im=display_link($query[0]['attachment'],'circular_staff',0,0); 
                    if($val_im!=""){
                ?>	
                <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment');?></a>
                <a onclick="delete_files('<?php echo $query[0]['attachment']; ?>','circular_staff','circular_staff_id','<?php echo $query[0]['circular_staff_id']; ?>','attachment','circular_staff','id_file',2)" href="#" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment');?></a>
                <?php } ?>					
    			</span>
        </div>
        <div class="form-group">
            <div <?= check_sms_preference(11,"style","sms") ?>>
                <label class="control-label"><?php echo get_phrase('send_sms');?><span class="red"> * </span></label>
                <input  id="send_message" class=""  type="checkbox" name="send_message" />
                <div id="error_date1"></div>
            </div>    
        </div>
        <div class="form-group">
            <div <?= check_sms_preference(11,"style","email") ?>>
                <label class="control-label"><?php echo get_phrase('send_email');?><span class="red"> * </span></label>
                <input  id="" class=""  type="checkbox" name="send_email" />
                <div id="error_date1"></div>
            </div>    
        </div>
        <div class="form-group">
            <div class="float-right">
    			<button type="submit" class="modal_save_btn" id="btn_add">
    				<?php echo get_phrase('save');?>
    			</button>
    			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    				<?php echo get_phrase('cancel');?>
    			</button>
    		</div>
        </div>
        </form>
    </div>
</div>    
<script>
$(document).ready(function() {
});
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>