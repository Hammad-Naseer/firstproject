<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('response_inquiry');?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'inquiries/general_inq_action' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                        <label class="control-label">Type Response</label>
                        <textarea class="form-control" name="response" style="height:100px"></textarea>
                        <input type="hidden" name="s_g_i_id" value="<?= $this->uri->segment('4'); ?>">
                    </div>
                    <div class="form-group">
                        <div <?= check_sms_preference(6,"style","sms") ?>>
                            Send SMS &nbsp;&nbsp;&nbsp;<input type="checkbox" name="sms">
                            <br>
                        </div>
                        <div <?= check_sms_preference(6,"style","email") ?>>
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