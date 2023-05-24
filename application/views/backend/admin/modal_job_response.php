<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('job_application_response');?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'jobs/job_application_response' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                        <label class="control-label">Type Response</label>
                        <textarea class="form-control" name="response" style="height:100px">Your job application has been received successfully by the HR dept of <?php echo $_SESSION['school_name'];?>. It is being reviewed at the moment and will be informed accordingly.</textarea>
                        <input type="hidden" name="job_application_id" value="<?= $this->uri->segment('4'); ?>">
                    </div>
                    <div class="form-group text-right">
                        <div <?= check_sms_preference(7,"style","sms") ?>>
                            Send SMS &nbsp;&nbsp;&nbsp;<input type="checkbox" name="sms">
                            <br>
                        </div>
                        <div <?= check_sms_preference(7,"style","email") ?>>
                            Send Email &nbsp;&nbsp;<input type="checkbox" name="email">
                        </div>    
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn"><?php echo get_phrase('send_response');?></button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        </div>
    </div>
</div>