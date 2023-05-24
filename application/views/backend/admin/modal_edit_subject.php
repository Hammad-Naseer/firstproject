<div id="code_div" class="alert alert-danger" style="display:none;"><?php echo get_phrase('subject_code_already_exists'); ?></div>
<?php 
    $q="select * from ".get_school_db().".subject WHERE subject_id=".$param2." AND school_id =".$_SESSION['school_id']."";
    $edit_data =	$this->db->query($q)->result_array();
    foreach ( $edit_data as $row){
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_subject');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'subject/subjects/do_update/'.$row['subject_id'] , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('subject_category');?><span class="star">*</span></label>
                    <select name="subj_categ_id" id="subj_categ_id" class="form-control" data-validate="required" required data-message-required="<?php echo get_phrase('value_required');?>">
                        <?php echo get_subj_category($row['subj_categ_id']);?>
                    </select>  
                </div>
                    <div class="form-group">
                    <label class="control-label"><?php echo get_phrase('name');?><span class="star">*</span></label>
                    <input maxlength="100" type="text" class="form-control" name="name_edit" id="name_edit"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['name'];?>"/>
                </div>
			        <div class="form-group">
					<label for="field-1" class="control-label"><?php echo get_phrase('code');?><span class="star">*</span></label>
                    <input maxlength="25" type="text" class="form-control" id="code_edit" name="code_edit"   data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['code'];?>" >
                    <div id="err" style="color:red"></div>
				</div>
                    <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $row['subject_id'];?>"/>
                    <div class="form-group">
                    <div class="float-right">
                        <button type="submit" id="submit-btn" class="modal_save_btn"><?php echo get_phrase('save');?></button>
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

<?php
}
?>
<script>
$(document).ready(function()
{
	$("#code_edit").change(function()
	{	
		var subj_id=$('#subject_id').val();
		var code=$(this).val();
		
		if(code!="")
		{
			$.ajax({
				type: 'POST',
				data: {code:code,subj_id:subj_id},
				url: "<?php echo base_url();?>subject/edit_code",
				dataType: "html",
				success: function(response)
				{
					//alert(response);
					var obj = jQuery.parseJSON(response);
					//$("#icon").remove();
					if(obj.exists==1)
					{
						//alert('if');
						$("#code_div").show();
						$('#code_div').delay(2000).fadeOut('slow');
						$('#submit-btn').attr('disabled',true);
						//$("#code").focus();
					}
					else	
					{
						//alert('else');
						$("#code_div").hide();
						$('#submit-btn').attr('disabled',false);
					}
				}
			});
		}
	});	
});
</script>
