<?php 
    $edit_data	=	$this->db->get_where(get_school_db().'.grade' , array(
			'grade_id' => $param2,
			'school_id' =>$_SESSION['school_id']
	) )->result_array();
    foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_grade');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'exams/grade/do_update/'.$row['grade_id'] , array('id'=>'grade_edit','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('name');?><span class="red"> * </span></label>
                        <input maxlength="50" type="text" class="form-control" name="name" value="<?php echo $row['name'];?>" required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('grade_point');?><span class="red"> * </span></label>
                        <input maxlength="20" type="text" class="form-control" name="grade_point" value="<?php echo $row['grade_point'];?>" required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('mark_from');?><span class="red"> * </span></label>
                        <input pattern="\d*"  max="100" min="0" type="text" class="form-control" name="mark_from" value="<?php echo $row['mark_from'];?>"  required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('mark_upto');?><span class="red"> * </span></label>
                        <input type="text" class="form-control" name="mark_upto" pattern="\d*"  max="100" min="0" value="<?php echo $row['mark_upto'];?>" required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('order_by');?><span class="red"> * </span></label>
                        <input type="text" class="form-control" pattern="\d*"  max="100" min="0" name="order_by" value="<?php echo $row['order_by'];?>"  required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('comment');?></label>
                        <input maxlength="100" type="text" class="form-control" name="comment" value="<?php echo $row['comment'];?>" />
                    </div>
                    <div class="form-group">
    					<div class="float-right">
        					<button type="submit" class="modal_save_btn">
        						<?php echo get_phrase('edit_grade');?>
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

<?php
endforeach;
?>
    <script src="<?php echo base_url(); ?>assets/js/common.js"></script>


