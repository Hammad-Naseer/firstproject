<?php
    if($this->uri->segment(5) == "edit"):
        $id = str_decode($this->uri->segment(6));
        $edit_data = $this->db->where("library_member_id",$id)->get(get_school_db().".library_members")->row();
    endif;
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
            		<?php
            		    if($this->uri->segment(5) == "edit"):
            		        $url = "update_members/".$id;
            		        echo get_phrase('edit_member');
            		        $library_membership_id = $edit_data->library_membership_id;
            		    else:
            		        $url = "members";
            		        echo get_phrase('add_member');
            		        $library_membership_id = get_library_membership_id();
            		    endif;
            		?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'library/'.$url.'/' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label"><?php echo get_phrase('library_membership_id');?> <span class="star">*</span></label>
                        <input type="text" class="form-control" name="library_membership_id" value="<?php echo $library_membership_id; ?>" required readonly/>
                    </div>
                    <div class="form-group" style="padding-bottom:0px !important;">
                        <label class="control-label"><?php echo get_phrase('membership_fee');?> <span class="star">*</span></label>
                        <input type="number" class="form-control" name="membership_fee" value="<?php echo $edit_data->membership_fee ?>" required/>
                        <input type="hidden" name="user_id" value="<?= str_decode($this->uri->segment('4')) ?>">
                    </div>
                    <div class="form-group">
						<label for="status" class="control-label">
						    <?php echo get_phrase('status');?>
						    <span class="star">*</span>
						</label>
						<select name="status" id="status" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
						    <option>Select Status</option>
						    <option value="1">Active</option>
						    <option value="0">Inactive</option>
						</select>
					</div>
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn">
                                <?php
                        		    if($this->uri->segment(5) == "edit"):
                        		        echo get_phrase('update_member');
                        		    else:
                        		        echo get_phrase('add_member');
                        		    endif;
                        		?>
                            </button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
<?php if($this->uri->segment(5) == "edit"): ?>
<script>
    setTimeout(function () {
        $("#status").val('<?php echo $edit_data->status ?>');
    }, 500);
</script>
<?php endif; ?>