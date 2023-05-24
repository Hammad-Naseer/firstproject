<?php 
$edit_data=	$this->db->get_where(get_school_db().'.class_routine' , array('class_routine_id' => $param2) )->result_array();
?>
<div class="tab-pane box active" id="edit" style="padding: 5px">
    <div class="box-content">
        <?php foreach($edit_data as $row):?>
        <?php echo form_open(base_url().'admin/class_routine/do_update/'.$row['class_routine_id'] , array('id'=>'disable_submit_btn','class' => 'form-horizontal validatable','target'=>'_top'));?>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('class');?></label>
                    <div class="col-sm-8">
                        <select name="class_id" class="form-control">
                            <?php 
                            $classes = $this->db->get(get_school_db().'.class')->result_array();
                            foreach($classes as $row2):
                            ?>
                                <option value="<?php echo $row2['class_id'];?>" <?php if($row['class_id']==$row2['class_id'])echo 'selected';?>>
                                    <?php echo $row2['name'];?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('subject');?></label>
                    <div class="col-sm-8">
                        <select name="subject_id" class="form-control">
                            <?php 
                            $subjects = $this->db->get(get_school_db().'.subject')->result_array();
                            foreach($subjects as $row2):
                            ?>
                                <option value="<?php echo $row2['subject_id'];?>" <?php if($row['subject_id']==$row2['subject_id'])echo 'selected';?>>
                                    <?php echo $row2['name'];?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('day');?></label>
                    <div class="col-sm-8">
                        <select name="day" class="form-control">
                            <option value="saturday" 	<?php if($row['day']=='saturday')echo 'selected="selected"';?>>
                            
                            <?php echo get_phrase('saturday');?>
                            </option>
                            <option value="sunday" 		<?php if($row['day']=='sunday')echo 'selected="selected"';?>><?php echo get_phrase('sunday');?></option>
                            <option value="monday" 		<?php if($row['day']=='monday')echo 'selected="selected"';?>><?php echo get_phrase('monday');?></option>
                            <option value="tuesday" 	<?php if($row['day']=='tuesday')echo 'selected="selected"';?>><?php echo get_phrase('tuesday');?></option>
                            <option value="wednesday" 	<?php if($row['day']=='wednesday')echo 'selected="selected"';?>><?php echo get_phrase('wednesday');?></option>
                            <option value="thursday" 	<?php if($row['day']=='thursday')echo 'selected="selected"';?>><?php echo get_phrase('thurday');?></option>
                            <option value="friday" 		<?php if($row['day']=='friday')echo 'selected="selected"';?>><?php echo get_phrase('friday');?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('starting_time');?></label>
                    <div class="col-sm-8">
                        <?php 
                            if($row['time_start'] < 13)
                            {
                                $time_start		=	$row['time_start'];
                                $starting_ampm	=	1;
                            }
                            else if($row['time_start'] > 12)
                            {
                                $time_start		=	$row['time_start'] - 12;
                                $starting_ampm	=	2;
                            }
                            
                        ?>
                        <select name="time_start" class="form-control">
                            <?php for($i = 0; $i <= 12 ; $i++):?>
                                <option value="<?php echo $i;?>" <?php if($i ==$time_start)echo 'selected="selected"';?>>
                                    <?php echo $i;?></option>
                            <?php endfor;?>
                        </select>
                        <select name="starting_ampm" class="form-control">
                            <option value="1" <?php if($starting_ampm	==	'1')echo 'selected="selected"';?>><?php echo get_phrase('am');?></option>
                            <option value="2" <?php if($starting_ampm	==	'2')echo 'selected="selected"';?>><?php echo get_phrase('pm');?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('ending_time');?></label>
                    <div class="col-sm-8">
                        
                        
                        <?php 
                            if($row['time_end'] < 13)
                            {
                                $time_end		=	$row['time_end'];
                                $ending_ampm	=	1;
                            }
                            else if($row['time_end'] > 12)
                            {
                                $time_end		=	$row['time_end'] - 12;
                                $ending_ampm	=	2;
                            }
                            
                        ?>
                        <select name="time_end" class="form-control">
                            <?php for($i = 0; $i <= 12 ; $i++):?>
                                <option value="<?php echo $i;?>" <?php if($i ==$time_end)echo 'selected="selected"';?>>
                                    <?php echo $i;?></option>
                            <?php endfor;?>
                        </select>
                        <select name="ending_ampm" class="form-control">
                            <option value="1" <?php if($ending_ampm	==	'1')echo 'selected="selected"';?>><?php echo get_phrase('am');?></option>
                            <option value="2" <?php if($ending_ampm	==	'2')echo 'selected="selected"';?>><?php echo get_phrase('pm');?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-5">
                      <button type="submit" class="btn btn-info"><?php echo get_phrase('edit_class_routine');?></button>
                  </div>
                </div>
        </form>
        <?php endforeach;?>
    </div>
</div>