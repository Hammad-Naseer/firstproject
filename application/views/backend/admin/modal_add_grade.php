<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_grade');?>
            	</div>
            </div>
			<div class="panel-body">    	          
               	     <div class="box-content">
                	    <?php echo form_open(base_url().'exams/grade/create' , array('id'=>'grade_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('name');?><span class="red"> * </span></label>
                                <input type="text" class="form-control" name="name" maxlength="20"  required/>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('grade_point');?><span class="red"> * </span></label>
                                <input type="text" class="form-control" name="grade_point" maxlength="20" required/>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('mark_from');?><span class="red"> * </span></label>
                                <input type="text" class="form-control" pattern="\d*"  max="100" min="0" name="mark_from"  required/>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('mark_upto');?><span class="red"> * </span></label>
                                <input type="text" class="form-control" pattern="\d*"  required  max="100" min="0" name="mark_upto"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('order_by');?><span class="red"> * </span></label>
                                <input type="number"  pattern="\d*"  max="100" min="0" class="form-control" name="order_by"  required/>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('comment');?></label>
                                <input type="text" class="form-control" name="comment" maxlength="100"/>
                            </div>
                            <div class="form-group">
                                <div class="float-right">
                					<button type="submit" class="modal_save_btn">
                						<?php echo get_phrase('add_grade');?>
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
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>