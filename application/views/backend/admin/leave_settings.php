<?php
    $staff_id=$this->uri->segment('4');
    $q="select * from ".get_school_db().".leave_category where school_id=".$_SESSION['school_id']."";
    $query=$this->db->query($q)->result_array();
    
    $p="select * from ".get_school_db().".staff_leave_settings where staff_id=$staff_id";
    $edit_quer=$this->db->query($p)->result_array();
    //echo $this->db->last_query();
    /*echo "<pre>";
    print_r($edit_quer);*/
    $edit_rec_arr=array();
    foreach($edit_quer as $edit_rec)
    {
    	$edit_rec_arr[$edit_rec['leave_category_id']]['monthly_limit']=$edit_rec['monthly_limit'];
    	$edit_rec_arr[$edit_rec['leave_category_id']]['yearly_limit']=$edit_rec['yearly_limit'];
    }
?>

<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <h4 style="color:white;padding-left:5px">
        <?php echo get_phrase('leave_settings');?>
        </h4>
    </div>
    <div class="panel-body">	
        <?php echo form_open(base_url().'user/leave_settings/create/'.$staff_id , array('id'=>'noticeboards_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
        <div class="row">
            <div class="col-lg-4">
                <label><strong><?php echo get_phrase('leave_category');?></strong></label>
            </div>
            <div class="col-lg-4">
                <label><strong><?php echo get_phrase('monthly_limit');?></strong></label>
            </div>
            <div class="col-lg-4">
                <label><strong><?php echo get_phrase('yearly_limit');?></strong></label>
            </div>
        </div>
        <div class="row text-center">
        <?php foreach($query as $leave_arr) {?>
        <div class="col-lg-3">
	        <?php echo $leave_arr['name']; ?>
        </div>
        <div class="col-lg-4">
	        <input type="hidden" name="leave_category_id[<?php echo $leave_arr['leave_category_id'];?>]" value="<?php echo $leave_arr['leave_category_id'];?>">
	        <input name="monthly_limit[<?php echo $leave_arr['leave_category_id'];?>]" type="number" min="0" max="12" class="form-control" value="<?php 
	        if(isset($edit_rec_arr[$leave_arr['leave_category_id']]['monthly_limit']))
	        {
	            echo $edit_rec_arr[$leave_arr['leave_category_id']]['monthly_limit'];
	        }?>">
        </div>
        <div class="col-lg-4">
        	<input name="yearly_limit[<?php echo $leave_arr['leave_category_id'];?>]" type="number" min="0" max="12" class="form-control" value="<?php 
        	if(isset($edit_rec_arr[$leave_arr['leave_category_id']]['yearly_limit']))
        	{
        	    echo $edit_rec_arr[$leave_arr['leave_category_id']]['yearly_limit'];
        	}?>">
        	<br>
        </div>
    <?php } ?>
    <div class="col-lg-12">
    	<div class="float-right">
    		<button type="submit" class="modal_save_btn">
    			<?php echo get_phrase('save');?>
    		</button>
    		<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    			<?php echo get_phrase('cancel');?>
    		</button>
    	</div>
	</div>
</div>
</div>
</div>