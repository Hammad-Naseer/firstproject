<?php
$this->load->helper('jobs');
$job_id = $this->uri->segment(4);
if($job_id > 0){
    $q=  "select * from ".get_school_db().".jobs where school_id=".$_SESSION['school_id']." and job_id = ".$job_id." ";
    $job_details =   $this->db->query($q)->row();
}else{
    $job_details = array();
}

if(count($job_details) > 0 ){
    $action = base_url()."jobs/update_job" ;
    $btn_text = "Update";
    $title = "Edit Job";
}else{
    $action = base_url()."jobs/post_new_job" ;
    $btn_text = "Save";
    $title = "Post New Job";
}
?>
 <div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
	    <div class="panel-title black2">
			<i class="entypo-plus-circled"></i>
                <?php echo $title;?>
		</div>
	</div>
	<div class="panel-body">
        
        <form action="<?php echo $action ; ?>" method="POST">
            <input type="hidden" name="job_id" value="<?php echo $job_details->job_id; ?>"/>
            <div class="">
                <label class="control-label"><?php echo get_phrase('job_title');?><span class="star">*</span></label>
                <input type="text" class="form-control" name="job_title" value="<?php echo $job_details->job_title; ?>"  required>
            </div>
            <div class="">
                    <label for="field-2" class="control-label"><?php echo get_phrase('carrer_level');?><span class="star">*</span></label>
                    <input type="text" class="form-control" name="carrer_level" value="<?php echo $job_details->carrer_level; ?>" required>
            </div>
            <div class="">
                <label for="field-2" class="control-label"><?php echo get_phrase('qualifications');?><span class="star">*</span></label>
                <input type="text" class="form-control" name="qualifications" value="<?php echo $job_details->qualifications; ?>" required>
            </div>
            <div class="">
                <label for="field-2" class="control-label"><?php echo get_phrase('experience');?><span class="star">*</span></label>
                <input type="text" class="form-control" name="experience" value="<?php echo $job_details->experience; ?>" required>     
            </div>
            <div class="">
                <label for="field-2" class="control-label"><?php echo get_phrase('job_type');?><span class="star">*</span></label>
                <select class="form-control" name="job_type" required>
                    <?php echo jobs_option_list($job_details->job_type);?>
                </select>
            </div>
            <div class="">
                <label for="field-2" class="control-label"><?php echo get_phrase('job_location');?><span class="star">*</span></label>
                <input type="text" class="form-control" name="job_location" value="<?php echo $job_details->job_location; ?>" required>     
            </div>
            <div class="">
                <label for="field-2" class="control-label"><?php echo get_phrase('posting_date');?><span class="star">*</span></label>
                <input type="date" class="form-control" name="job_posting_date" value="<?php echo $job_details->job_posting_date; ?>" required>     
            </div>
            <div class="">
                <label for="job_end_date" class="control-label"><?php echo get_phrase('job_end_date');?><span class="star">*</span></label>
                <input type="date" class="form-control" name="job_end_date" value="<?php echo $job_details->job_end_date; ?>" required>     
            </div>
            <div class="form-group">
                <label for="job_description" class="control-label">
                    <?php echo get_phrase('job_description');?><span class="star">*</span>
                </label>
                <textarea maxlength="2500" id="job_description" class="form-control" name="job_description" oninput="count_value('job_description','detail_count','2500')" rows="5" required><?php echo $job_details->job_description; ?></textarea>
                <div id="detail_count"></div>
            </div>
            <div class="">
                <?php echo status("job_status", "form-control", $job_details->job_status, "") ?>
            </div>
            <span style="color: red;" id="img_f_msg"></span>
            <div class="">
                <div class="float-right">
            		<button type="submit" class="modal_save_btn">
            			<?php echo $btn_text;?>
            		</button>
            		<button type="button" class="modal_cancel_btn" onclick="location.reload()">
            			<?php echo get_phrase('cancel');?>
            		</button>
            	</div>
            </div>
            
        </form>
    </div>    
</div>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>



