<?php 
    if($param2!='')
    {
    	$url='user/user_designation/update/'.$param2;
    	$edit_data=$this->db->get_where(get_school_db().'.designation' , array('designation_id' => $param2) )->result_array();
    	$mytitle = get_phrase('edit_designation');
    }
    else
    {
    	$url='user/user_designation/add/';
    	$mytitle = get_phrase('add_designation');
    }
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title" >
					<i class="entypo-plus-circled"></i>
				    <span><?php echo $mytitle; ?></span>
				</div>
			</div>
			<div class="panel-body">	
				<?php echo form_open(base_url().$url , array('id'=>'designation_add_edit','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
				<input type="hidden"  name="designation_id" value="<?php echo $edit_data[0]['designation_id']?>" >
				<div class="form-group">
					<label for="field-1" class="control-label"><?php echo get_phrase('title');?><span class="star">*</span></label>
					<input maxlength="100" type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php if($edit_data[0]['title'])echo $edit_data[0]['title']?>" autofocus>
				</div>
					
				<div class="form-group">
					<label for="field-1" class="control-label"><?php echo get_phrase('parent');?></label>
					<select name="parent_id" class="form-control">
						<option value="0"><?php echo get_phrase('top_lavel');?></option>
				        <?php echo designation_list_h(0,$edit_data[0]['parent_id']); ?>	
					</select>
				</div>
				<div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('Status');?><span class="star">*</span></label>
					<select name="status"  class="form-control">
						<option value="1" <?php if($edit_data[0]['status']==1)echo 'selected' ?>> <?php echo get_phrase('active');?></option>
						<option value="0" <?php if($edit_data[0]['status']==0)echo 'selected' ?> > <?php echo get_phrase('inactive');?></option>
					</select>
				</div>
               	<div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('is_teacher');?><span class="star">*</span></label>
					<select name="is_teacher"  class="form-control">
                        <option value="1" <?php if($edit_data[0]['is_teacher']==1)echo 'selected' ?> > <?php echo get_phrase('yes');?></option>
						<option value="0" <?php if($edit_data[0]['is_teacher']==0) echo 'selected' ?> > <?php echo get_phrase('no');?></option>
					</select> 
				</div>
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