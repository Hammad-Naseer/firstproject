<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('circular_list');?>
                    	</a></li>
			<!--<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_circular');?>
                    	</a></li>-->
		</ul>
    	<!------CONTROL TABS END------->
        
	
		<div class="tab-content">
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane box active" id="list">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('title');?></div></th>
                    		<th><div><?php echo get_phrase('circular');?></div></th>
                    		<th><div><?php echo get_phrase('class_name');?></div></th>
                            <th><div><?php echo get_phrase('date');?></div></th>
                    		<!--<th><div><?php echo get_phrase('options');?></div></th>-->
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($circulars as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $row['circular_title'];
								if($row['attachment']=="")
    						{
							}
							 else
							 {?>
							 	<a target="_blank" href="<?php echo base_url();?>uploads/circular_image/<?php echo $row['attachment'];?>"><span class="glyphicon glyphicon-download-alt"></span></a>
							 <?php
							 }
							?>
								
							</td>
							<td class="span5">
							<?php 
							if($row['student_id']!=''){
								echo "<strong>Student :</strong>".$this->crud_model->get_student_name($row['student_id']);
								echo "<br/>";
								}
							
							?>
							<?php echo $row['circular'];
								?>
							</td>
                            <td class="span5">
							<?php 
							if($row['class_id']!=''){
								echo $this->crud_model->get_class_name($row['class_id']);
								}
							else{
								echo "N/A";
							}
							?>
                            </td>
                            
                            <td><?php echo date('d M,Y', $row['create_timestamp']);?></td>
							<!--<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_circular/<?php echo $row['circular_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/circulars/delete/<?php echo $row['circular_id'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                                    </li>
                                </ul>
                            </div>
        					</td>-->
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----CREATION FORM STARTS---->
			<!--<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url().'admin/circulars/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('title');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="circular_title"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('circular');?></label>
                                <div class="col-sm-5">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                                <textarea name="circular" id="ttt" rows="5" placeholder="<?php echo get_phrase('add_circular');?>" class="form-control"></textarea>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('circular_type');?></label>
                                <div class="col-sm-5">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                                <input type="radio" class="form-control" value="student" name="type" onclick="showstudents()" required="required" style="width:10%; float:left;"/>
                                                <label class="control-label" style="margin-top:1%;"><?php echo get_phrase('student_circular');?></label>
                                                <div style="clear:both"></div>
                                                <input type="radio" class="form-control" value="class" name="type"  onclick="showclasses()" required="required" style="width:10%; float:left;"/>
                                                <label class="control-label" style="margin-top:1%;"><?php echo get_phrase('class_circular');?></label>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group" id="class" style="display:none">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('select_class');?></label>
                                <div class="col-sm-5">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                                <select name="class_id" id="class_id" class="form-control">
                                                    <option value="">Select a class</option>
                                                    <?php 
                                                    $classes	=	$this->db->get('class')->result_array();
                                                    foreach($classes as $row):?>
                                                    <option value="<?php echo $row['class_id'];?>"
                                                        <?php if(isset($class_id) && $class_id==$row['class_id'])echo 'selected="selected"';?>>
                                                            <?php echo $row['name'];?>
                                                                </option>
                                                    <?php endforeach;?>
                                                </select>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="students" style="display:none">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('select_student');?></label>
                                <div class="col-sm-5">
                                    <div class="box closable-chat-box">
                                        <div class="box-content padded">
                                                <div class="chat-message-box">
                                                <select name="student_id" id="student_id" class="form-control">
                                                	<option value="">Select a Student</option>
													<?php 
                                                    $this->db->order_by('class_id','asc');
                                                    $students = $this->db->get('student')->result_array();
                                                    foreach($students as $row):
                                                    ?>
                                                        <option value="<?php echo $row['student_id'];?>">
                                                            class <?php echo $this->crud_model->get_class_name($row['class_id']);?> -
                                                            roll <?php echo $row['roll'];?> -
                                                            <?php echo $row['name'];?>
                                                        </option>
                                                    <?php
                                                    endforeach;
                                                    ?>
                                                </select>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="datepicker form-control" name="create_timestamp"/>
                                </div>
                            </div>

                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_circular');?></button>
                              </div>
							</div>
                    </form>                
                </div>                
			</div>-->
			<!----CREATION FORM ENDS--->
            
		</div>
	</div>
</div>
<script>
// function showstudents(){
//	 document.getElementById('students').style.display='block';
//	 document.getElementById('class').style.display='none';
//	 document.getElementById('student_id').setAttribute('required','required');
//	 document.getElementById('class_id').removeAttribute('required');
//	 document.getElementById('class_id').value="";
// }
//  function showclasses(){
//	 document.getElementById('class').style.display='block';
//	 document.getElementById('students').style.display='none';
//	 document.getElementById('class_id').setAttribute('required','required');
//	 document.getElementById('student_id').removeAttribute('required');
//	 document.getElementById('student_id').value="";
// }
</script>