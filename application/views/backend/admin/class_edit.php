<?php 
$edit_data		=	$this->db->get_where(get_school_db().'.class' , array(
									'class_id' => $param2,
									'school_id' =>$_SESSION['school_id']
									) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_class');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url().'departments/class_new/do_update/'.$row['class_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'disable_submit_btn'));?>
                 
                   <div class="form-group">
                        <label class="col-sm-4 control-label"><?php echo get_phrase('department');?> <span class="star">*</span></label>
                        <div class="col-sm-8">
  <select name="departments_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
<option value=""><?php echo get_phrase('select_department'); ?></option>
                                <?php 
   
   $this->db->where('school_id',$_SESSION['school_id']);
  
  $teachers = $this->db->get(get_school_db().'.departments')->result_array();
                                foreach($teachers as $row2):
                                ?>
                <option value="<?php echo $row2['departments_id'];?>"
  <?php if($row['departments_id'] == $row2['departments_id'])echo 'selected';?>>
                                            <?php echo $row2['title'];?>
                                                </option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                 
                 
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php echo get_phrase('name');?> <span class="star">*</span></label>
                        <div class="col-sm-8">
                            <input maxlength="100" type="text" class="form-control" name="name" value="<?php echo $row['name'];?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php echo get_phrase('short_name');?></label>
                        <div class="col-sm-8">
                            <input maxlength="20" type="text" class="form-control" name="name_numeric" value="<?php echo $row['name_numeric'];?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php echo get_phrase('teacher');?></label>
                        <div class="col-sm-8">
  <select name="teacher_id" class="form-control"  >
<option value=""><?php echo get_phrase('select_teacher'); ?></option>
                                <?php 
                                $this->db->where('school_id',$_SESSION['school_id']);
                                $teachers = $this->db->get(get_school_db().'.staff')->result_array();
                                foreach($teachers as $row2):
                                ?>
                                    <option value="<?php echo $row2['staff_id'];?>"
                                        <?php if($row['teacher_id'] == $row2['staff_id'])echo 'selected';?>>
                                            <?php echo $row2['name'];?>
                                                </option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>

   <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('detail');?></label>
                                <div class="col-sm-8">
     
<textarea id="description" maxlength="1000" oninput="count_value('description','description_count','1000')" class="form-control" name="description" ><?php echo $row['description'];?></textarea>  

<div id="description_count" class="col-sm-12"></div>
                                </div>
                            </div>



            		<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('save');?></button>
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


