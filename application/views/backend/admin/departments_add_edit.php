<?php 
    $school_id=$_SESSION['school_id'];
    $edit_data=$this->db->get_where(get_school_db().'.departments' , array('departments_id' => $param2,'school_id'=>$school_id))->result_array();
    $title = 'Add Department';
    if($param2>0){
    	$title = 'Edit Department';
    }
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" >
            		<i class="entypo-plus-circled"></i>
					<?php echo $title; ?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'departments/departments_listing/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data','id'=>'disable_submit_btn'));?>
                    <div class="form-group">
						<label for="field-1" class="control-label"><?php echo get_phrase('title');?>   <span class="star">*</span>   </label>
						<input maxlength="500" type="text" class="form-control" name="title"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'];?>">
	                    <input type="hidden" name="departments_id" value="<?php echo $edit_data[0]['departments_id'];   ?>">
					</div>
					<div class="form-group">
						<label for="field-2" class="control-label"><?php echo get_phrase('short_name');?></label>
		                <input maxlength="20" type="text" class="form-control" name="short_name"  value="<?php echo $edit_data[0]['short_name'];?>">
					</div>
					<div class="form-group">
						<label for="field-2" class="control-label"><?php echo get_phrase('description');?></label>
                        <textarea maxlength="1000" id="discription" oninput="count_value('discription','discription_count','1000')" class="form-control" name="discription"><?php echo $edit_data[0]['discription'];?></textarea>
                        <div id="discription_count" class="col-sm-12"></div>
                    </div>
    				<div class="form-group">
						<label for="field-2" class="control-label"><?php echo get_phrase('department_head');?>    </label>
                		<select class="form-control"  name="department_head" >
                	        <option><?php echo get_phrase('select'); ?></option>
                        	<?php
                        	    $dep_id= $edit_data[0]['department_head'];   
                                $re_dep=$this->db->query("select * from ".get_school_db().".staff WHERE school_id=".$_SESSION['school_id']." ")->result_array();
                        	    foreach($re_dep as $recx){
                            ?>
                                <option <?php if($recx['staff_id']==$dep_id){ echo "selected";}?> value="<?php echo $recx['staff_id']; ?>"><?php echo $recx['name']; ?></option>
                            <?php } ?>
                	    </select>		
                    </div>
	    			<div class="form-group">
                        <div class="float-right">
                            <button type="submit" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>











