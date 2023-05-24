
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_event_announcement');?>
            	</div>
            </div>
			<div class="panel-body">    	          
               	     <div class="box-content">
                	<?php echo form_open(base_url().'event_annoucments/events_program/create' , array('id'=>'exam_weightage_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                           <div class="form-group">
                              <label for="yearly_term_id">Event Title</label>
                              <span style="color:red;" id="yearly_term_id_span">*</span>
                              <input type="text" class="form-control" name="title" required> 
                            </div>
                           <div class="form-group">
                                <label>Event Details</label>
                                <span style="color:red;" id="section_id_span"></span>
                                <textarea class="form-control" name="details">  
                                </textarea>
                            </div>
                            <div class="form-group">
                                <label>Event Start Date</label>
                                <span style="color:red;" id="section_id_span">*</span>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="form-group">
                                <label>Event End Date</label>
                                <span style="color:red;" id="section_id_span">*</span>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                            <div class="form-group">
                                <label>Event Status</label>
                                <span style="color:red;" id="section_id_span">*</span>
                                <select class="form-control" name="status" required>
                                    <option>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                              <div class="float-right">
                                    <button type="submit" class="modal_save_btn"><?php echo get_phrase('add_event');?></button>
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
  <script src="<?php echo base_url(); ?>assets/js/common.js"></script>
