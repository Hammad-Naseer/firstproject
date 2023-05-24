<?php
        $param2=$this->uri->segment('4');
        $value="";
        $btn_value="";
        if($param2=="add"){	
        $title='Add Student category';
        $value='create';
        $btn_value = "Add Category";
    }else{
    	$edit_data=$this->db->get_where(get_school_db().'.student_category' , array('student_category_id' => $param2,'school_id'=>$_SESSION['school_id']))->result_array();
    	$title='Edit Student category';	
    	$value='edit';
    	$btn_value = "Update Category";
    }
?>

<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
		<div class="panel-title black2">
			<i class="entypo-plus-circled"></i><?php echo $title;?>
		</div>
	</div>
    <div class="panel-body">
        <?php echo form_open(base_url().'student_category/manage_stud_category/'.$value , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('title');?><span class="star">*</span></label>
                <input maxlength="255" type="text" class="form-control" name="title" value="<?php echo $edit_data[0]['title'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                <input type="hidden" name="student_category_id" value="<?php echo $edit_data[0]['student_category_id'];   ?>">
            </div>
           	<div class="form-group">
                <div class="float-right">
                    <button type="submit" class="modal_save_btn"><?php echo get_phrase('add_category');?></button>
                    <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                        <?php echo get_phrase('cancel');?>
                    </button>
                </div>
			</div>
        </form>                
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>