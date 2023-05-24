 <?php
$dept_id=$this->uri->segment(4);
 ?>
  <div class="box-content">
                        <?php echo form_open(base_url().'departments/class_new/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'disable_submit_btn'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('departments');?> <span class="star">*</span></label>
                                <div class="col-sm-8">
                                    <select name="departments_id" class="form-control" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                        <option value=""><?php echo get_phrase('select_department'); ?>
</option>
                                        <?php 

$this->db->where('school_id',$_SESSION['school_id']);               
$teachers = $this->db->get(get_school_db().'.departments')->result_array();

foreach($teachers as $row){
$selected="";	
if($dept_id==$row['departments_id'])
{
	echo $selected='selected';
}	

?>
                                        <option value="<?php echo $row['departments_id']; ?>" <?php echo $selected;?>>
                                            <?php echo $row['title'];?>
                                        </option>
                                        <?php
                                       }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('name');?> <span class="star">*</span></label>
                                <div class="col-sm-8">
                                    <input maxlength="100" type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('Short_name');?>
                                </label>
                                <div class="col-sm-8">
                                    <input maxlength="20" type="text" class="form-control" name="name_numeric" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('teacher');?>
                                </label>
                                <div class="col-sm-8">
                                    <select name="teacher_id" class="form-control" style="width:100%;">
                                        <option><?php echo get_phrase('select_teacher'); ?>
 </option>
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
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('Detail');?>
                                </label>
                                <div class="col-sm-8">
                                    <textarea id="description1" maxlength="1000" oninput="count_value('description1','description_count1','1000')" class="form-control" name="description"></textarea>
                                    <div id="description_count1" class="col-sm-12"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                <input type="submit" class="btn btn-info" value="<?php echo get_phrase('add_class');?>"></input>
                            </div>
                        </div>
                        </form>
                    </div>