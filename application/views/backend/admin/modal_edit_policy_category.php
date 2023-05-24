<?php

$school_id = $_SESSION['school_id'];

$q="SELECT * FROM ".get_school_db().".policy_category 
		WHERE school_id=".$_SESSION['school_id']." 
		AND policy_category_id= $param2 
    ";      
$res = $this->db->query($q)->result_array();
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title" style="color:#818da1" >
					<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('update_policy_category');?>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open(base_url().'policies/policy_categories/edit' , array('id'=>'disable_submit_btn','class'  => 'form-horizontal form-groups-bordered validate','enctype'=> 'multipart/form-data'));?>
				<div class="form-group">
					<label for="field-2" class="control-label">
						<?php echo get_phrase('title');?><span class="star">*</span>
					</label>
					<input maxlength="250" type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $res[0]['title']; ?>" >
				</div>
				
				<div class="form-group">
					<div class="float-right">
						<input type="hidden" name="pol_cat_id" value="<?php echo $res[0]['policy_category_id'];?>" >
						<button type="submit" class="modal_save_btn">
							<?php echo get_phrase('update');?>
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