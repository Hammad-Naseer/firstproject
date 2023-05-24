
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title balck black2" style="">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('assign_user_group');?>
             
        </div>
    </div>
    <div class="panel-body">
    <div class="box-content">
    <?php if($param2!=''){ ?>
    <?php 
        echo form_open(base_url().'user/save_group/edit/'.$param2 , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'save_module'));
        $q="select * from ".get_school_db().".user_group where user_group_id=".str_decode($param2);
        $user_type=$this->db->query($q)->result_array();
    ?>
    <?php }else { echo form_open(base_url().'user/save_group/create/' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'save_module'));
$user_type='';}?>
    <div class="form-group  px-4">
        <div class="form-group">
            <label class="control-label">
                <?php echo get_phrase('group_type');?><span class="star">*</span>
            </label>
               <?php echo user_group_type_option_list($user_type[0]['type']); ?>
        </div>
        
        <div class="form-group">
            <label class="control-label">
                <?php echo get_phrase('title');?><span class="star">*</span>
            </label> 
                <input type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $user_type[0]['title']?>" />
        </div>
        
        <div class="form-group">
            <label class="control-label">
                <?php echo get_phrase('description');?>
            </label> 
                <textarea name="description" id="description" class="form-control"><?php echo $user_type[0]['description']?></textarea>
        </div>
        <div class="form-group">
            <label class="control-label">
                <?php echo get_phrase('status');?><span class="star">*</span>
            </label>
                <select name="status" id="status" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <option value="1" <?php if($user_type[0][ 'status']==1){echo "selected=selected";}?>><?php echo get_phrase('active');?></option>
                    <option value="0" <?php if($user_type[0][ 'status']==0){echo "selected=selected";}?>><?php echo get_phrase('inactive');?></option>
                </select>
        </div> 
        <div class="float-right">
    		<button type="submit" class="modal_save_btn">
    			<?php echo get_phrase('save');?>
    		</button>
    		<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    			<?php echo get_phrase('cancel');?>
    		</button>
        </div>
        </form>
    </div>
  
    </div> 
    </div>
    
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>
