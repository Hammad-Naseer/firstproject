<?php 

$school_id=$_SESSION['school_id'];
$param2= str_decode($this->uri->segment(3));
$param3=$this->uri->segment(4);

if($param2=="add"){	
$title='Add Policy';
	
	
}else{
	
	$edit_data=$this->db->get_where(get_school_db().'.policies' , array('policies_id' => $param2,'school_id'=>$school_id))->result_array();
	$title='Edit Policy';	
} 
if($this->session->flashdata('club_updated')){
	echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	'.$this->session->flashdata('club_updated').'
	</div> 
	</div>';
}
?>
<script>
$( window ).on('load',function() {
	setTimeout(function() {
			$('.alert').fadeOut();
		}, 3000);
});		
</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
              <?php  echo $title; ?>
        </h3>
    </div> 
</div>

<script src="//cdn.ckeditor.com/4.6.0/basic/ckeditor.js"></script>
<!--<script src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>-->
<div class="row"> 
	<div class="col-md-12">
		
                <?php echo form_open(base_url().'policies/policies_listing/add_edit/' , array('id'=>'disable_submit_btn','class' => 'validate', 'enctype' => 'multipart/form-data'));?>
                
                <div class=" col-md-6 form-group">
						<label for="field-1" class=""><?php echo get_phrase('title');?><span class="star">*</span></label>
						<input maxlength="250" type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'];?>">	
	                    <input type="hidden" name="policies_id" value="<?php echo $edit_data[0]['policies_id'];   ?>">
				</div>	
				<div class="col-md-6 form-group">
					<label for="field-1"><?php echo get_phrase('policy_category');?></label> 
					<select name="pol_cat" id="pol_cat" class="form-control" >
					<?php
						if($param3=='add'){		
							 echo policy_category_option_list($param2);
						}else{
						 echo policy_category_option_list($edit_data[0]['policy_category_id']);	
						}
					?>
					</select>
				</div>	
				<div class="col-md-6 form-group">
				    <label for="field-2"><?php echo get_phrase('document_number');?></label>
 		            <input maxlength="20" type="text" class="form-control" name="document_num" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php
                        echo $edit_data[0]['document_num'];
                    ?>">
				</div>	
				<div class="col-md-6 form-group">
				    <label for="field-2"><?php echo get_phrase('approval_date');?></label>				
                    <input type="text" class="form-control datepicker" name="approval_date" value="<?php echo date_dash($edit_data[0]['approval_date']); ?>"   data-format="dd/mm/yyyy"> 
				</div>	
				<div class="col-md-6 form-group">
				    <label for="field-2"><?php echo get_phrase('version_num');?></label>    
			        <input maxlength="20" type="text" class="form-control" name="version_num" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['version_num'];?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="field-2"><?php echo get_phrase('author');?></label>
        			<input maxlength="10" type="text" class="form-control" name="author"  data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['author'];?>">
                </div>
				<div class="row form-group">
					<label for="field-2"><?php echo get_phrase('approved_by');?></label>
			        <input maxlength="30" type="text" class="form-control" name="approved_by"  data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['approved_by'];?>">
		
                </div>
	    		<div class="col-md-12 form-group">
					<label for="field-2"><?php echo get_phrase('details');?></label>
                    <textarea name="detail" class="form-control"><?php echo $edit_data[0]['detail'];?></textarea>
				</div>			
                <div class="col-md-6 form-group">
					<label for="field-2"><?php echo get_phrase('upload_file');?></label>
                    <input type="file" class="form-control" name="attachment" id="attachment" onchange="file_validate('attachment','doc','img_g_msg')" >
                    <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>: 2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpg, jpeg, txt, pdf </span>
                    <br />
                    <span style="color: red;" id="img_g_msg"></span>
                    <input type="hidden" name="old_attachment" value="<?php echo $edit_data[0]['attachment']; ?>">
                    <?php if($edit_data[0]['attachment']!=""){ ?>
                    <div id="policies_attach">	
                        <a  target="_blank" href="<?php echo display_link($edit_data[0]['attachment'],'policies'); ?>"> <i class="fa fa-download" aria-hidden="true"></i></a>
                        <a class="btn btn-primary" onclick="delete_files('<?php echo $edit_data[0]['attachment']; ?>','policies','policies_id','<?php echo $edit_data[0]['policies_id']; ?>','attachment','policies','policies_attach',2)"><?php echo get_phrase('delete_attached_file');?></a>
                    </div>
                    <?php } ?>
                </div>
	            <div class="col-md-6 form-group">
					<label for="field-2"><?php echo get_phrase('Active');?></label>
                    <input name="is_active" class="" type="checkbox" <?php if($edit_data[0]['is_active']!=0){ echo "checked"; } ?> />
                </div> 
                <div class="col-md-6 form-group">
					<label for="field-2"><?php echo get_phrase('Staff Policy');?></label>
                    <input type="checkbox" name="staff_p" class="" <?php if($edit_data[0]['staff_p']!=0){ echo "checked"; } ?> />
                </div> 
                <div class="col-md-6 form-group">
                    <label for="field-2"><?php echo get_phrase('Student Policy');?></label>
                    <input name="student_p" class="" type="checkbox" <?php if($edit_data[0]['student_p']!=0){ echo "checked"; } ?>/>
                </div>
				<div class="form-group">
				    <div class="float-right">
    					<button type="submit" class="modal_save_btn">
    						<?php echo get_phrase('save');?>
    					</button>
    				</div>		
                </div> 
                <?php echo form_close();?>
            
    </div>
</div>

 <script>
 
// Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
CKEDITOR.replace( 'detail');

            </script>