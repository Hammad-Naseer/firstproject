
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title balck black2" style="">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('school_facility');?>
            <?php echo $param2; ?> 
        </div>
    </div>
    <div class="panel-body">
    <div class="box-content">
    <?php if($param2!=''){ ?>
    <?php 
        echo form_open(base_url().'landing_page/edit_facility/'.$param2 , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'save_module'));
        $q   = "select * from ".get_system_db().".school_facilities where id=".$param2 . " limit 1";
        $row = $this->db->query($q)->row();
    ?>
    <?php }else { echo form_open(base_url().'landing_page/save_facility' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'save_module'));
$user_type='';}?>
    <div class="form-group  px-4">
        <div class="form-group">
            <label class="control-label">
                <?php echo get_phrase('title');?><span class="star">*</span>
            </label> 
            <input type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row->title; ?>" />
        </div>
        <div class="form-group">
            <label class="control-label">
                <?php echo get_phrase('url');?>
            </label> 
            <input type="text" class="form-control" name="url" value="<?php echo $row->url; ?>" />
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
