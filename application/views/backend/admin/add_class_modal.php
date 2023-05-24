<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_class');?>
            	</div>
            </div>
            <div class="panel-body">
            <div class="box-content">
                <?php echo form_open(base_url().'departments/classes/create' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                        <label class="control-label">
                        <?php echo get_phrase('departments');?> <span class="star">*</span></label>
                        <select name="departments_id" class="form-control" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                <option value="">
                                <?php echo get_phrase('select_department'); ?>
                                </option>
                                <?php
                                    $this->db->where('school_id',$_SESSION['school_id']);               
                                    $teachers = $this->db->get(get_school_db().'.departments')->result_array();
                                    foreach($teachers as $row):
                                ?>
                                <option value="<?php echo $row['departments_id']; ?>">
                                    <?php echo $row['title'];?>
                                </option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo get_phrase('name');?> <span class="star">*</span></label>
                            <input maxlength="100" type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('short_name');?></label>
                        <input maxlength="20" type="text" class="form-control" name="name_numeric" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('teacher');?></label>
                        <select name="teacher_id" class="form-control" style="width:100%;">
                            <option><?php echo get_phrase('select_teacher'); ?></option>
                            <?php 
    
                                $this->db->where('school_id',$_SESSION['school_id']);               
                                $teachers = $this->db->get(get_school_db().'.staff')->result_array();
                                foreach($teachers as $row):
                                ?>
                            <option value="<?php echo $row['staff_id']; ?>">
                                <?php echo $row['name'];?>
                            </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('Strength');?></label>
                        <input type="number" class="form-control" name="strength" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo get_phrase('Detail');?></label>
                        <textarea id="description1" maxlength="1000" oninput="count_value('description1','description_count1','1000')" class="form-control" name="description"></textarea>
                        <div id="description_count1" class="col-sm-12"></div>
                    </div>
                <div class="form-group">
                    <div class="float-right">
                        <button type="submit" class="modal_save_btn"><?php echo get_phrase('add_class');?></button>
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