	<div class="box-content">
    <?php if($param2!=''){
//$CI = &get_instance();
//$this->db2=$CI->load->database('system',TRUE);
$q="select * from ".get_system_db().".action where action_id=".$param2;
$action=$this->db->query($q)->result_array();
?>
    <?php }else {$action='';}?>


    <?php 
    if($param2 != '')
    {
    	echo form_open(base_url().'module/saveaction/edit/'.$param2 , array('class' => 'form-horizontal form-groups-bordered validate', 'method'=>'post' , 'target'=>'_top','id'=>'save_action'));
		$q="select * from ".get_system_db().".action where action_id=".$param2;
		$action=$this->db->query($q)->result_array();
	}
	else 
	{ 
		echo form_open(base_url().'module/saveaction/create' , array('id'=>'saveaction_create_form','class' => 'form-horizontal form-groups-bordered validate', 'method'=>'post' , 'target'=>'_top','id'=>'save_action'));
		$action='';
	}?>
        <div class="form-group">
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('title');?>
                </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="title" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $action[0]['title']?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('code');?>
                </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="code" name="code" value="<?php echo $action[0]['code']?>" />
                    <div id="err" style="color:#ff0000"></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('module');?>
                </label>
                <div class="col-sm-8">
                    <select name="module_id" id="module_id" class="form-control">
                        <?php echo module_option_list($action[0]['module_id'])?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('status');?>
                </label>
                <div class="col-sm-8">
                    <select name="status" id="status" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        <option value="1" <?php if($action[0][ 'status']==1){echo "selected=selected";}?>>
                        
                        <?php echo get_phrase('active');?>
                        </option>
                        <option value="0" <?php if($action[0][ 'status']==0){echo "selected=selected";}?>><?php echo get_phrase('inactive');?></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    <button id="submit-btn" type="submit" class="btn btn-info">
  <?php if($param2!=''){echo get_phrase('edit_action');}
  else echo get_phrase('add_action');
  ?>
                    </button>
                </div>
            </div>
    </form>
    </div>
    <script type="text/javascript">
    $(document).ready(function()
    {
    	$('#code').on('input', function()
		{
			var code = $('#code').val();
			var action_id = <?php echo intval($param2) ?>;
			$.ajax({
				url: "<?php echo base_url();?>module/validate_action_code", 
				data: {code:code, action_id:action_id},
				method: "post",
				success: function(result)
				{
			        if (result == 'success') 
			        {
			        	$('#submit-btn').removeAttr('disabled');
			        	$('#err').html('');//remove();
			        } 
			        else 
			        {
			            $('#submit-btn').attr('disabled','disabled');
			            $('#err').html('Code already exists.');
			        }
			    }
			});
		});
    		
    });

  
    </script>
