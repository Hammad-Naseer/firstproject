<div class="box-content">
    <?php 
    if($param2 != '')
    {
    	echo form_open(base_url().'module/save/edit/'.$param2 , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'save_module'));
		$q="select * from ".get_system_db().".module where module_id=".$param2;
		//$CI = &get_instance();
		//$this->db2=$CI->load->database('system',TRUE);
		$module=$this->db->query($q)->result_array();
	}
	else 
	{ 
		echo form_open(base_url().'module/save/create/' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'save_module'));
		$module='';
	}?>
    <div class="form-group">
        <div class="form-group">
            <label class="col-sm-4 control-label">
                <?php echo get_phrase('title');?>
            </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $module[0]['title']?>" data-validate="required" data-message-required="value required>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">
                <?php echo get_phrase('parent_module');?>
            </label>
            <div class="col-sm-8">
                <select name="parent_module" id="parent_module" class="form-control" data-validate="required" data-message-required="value required">
                    <?php echo module_option_list($module[0]['parent_module_id'],$module[0]['module_id'])?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">
                <?php echo get_phrase('Status');?>
            </label>
            <div class="col-sm-8">
                <select name="status" id="status" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <option value="0" <?php if($module[0][ 'status']==0){echo "selected=selected";}?>><?php echo get_phrase('inactive');?></option>
                    <option value="1" selected="selected" <?php if($module[0][ 'status']==1){echo "selected=selected";}?>><?php echo get_phrase('active');?></option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                <button type="submit" class="btn btn-info">
                    <?php echo get_phrase('save');?>
                </button>
            </div>
        </div>
        </form>
    </div>
