<?php 
$array=explode('-',$param3);
$urlArr=explode('/',$_SERVER['REQUEST_URI']);
$subject_id=end($urlArr);
?>
   
<div class="tab-pane box " id="add" style="padding: 5px">
    <div class="box-content subj-clr">
        <form name="add_subject_component" id="add_subject_component" method="post" class="form-horizontal form-groups-bordered validate">
        	
	        <div class="panel panel-primary" data-collapsed="0">
	            <div class="panel-heading">
	                <div class="panel-title black2">
	                    <i class="entypo-plus-circled">
				</i>
	                    
                        <?php echo get_phrase('manage_components_of');?>
                       
			            <span class="themecolor">			
						<?php echo $array[0]."(".$array[1].".".$array[2].")"; ?>
							</span>
	                </div>
	            </div>
	            <div class="panel-body">
	                <div class="form-group">
	                    <label for="field-2" class="control-label">
	                        <?php echo get_phrase('title');?><span class="star">*</span>
	                    </label>
                        <input oninput="myfunction()" maxlength="250" id="title_add" name="title_add" type="text" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
	                </div>     
	                <div class="form-group">   
	                	<label class="control-label">
		                        <?php echo get_phrase('marks');?><span class="star">*</span>
		                </label>
	                    <input min="0" oninput="myfunction()" maxlength="250" id="percentage" type="number" class="form-control" name="percentage" data-validate="required" required="" data-message-required="<?php echo get_phrase('value_required');?>">
	                    <div id="err" style="color:red"></div>
	               </div>  
				   <div class="form-group">	
				        <div class="float-right">
                            <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $param2?>">
	                        <input type="hidden" name="hidden_component_id" id="hidden_component_id" >
                            <button type="submit" id="btn_submit1" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
	               </div> 
	            </div>
	        </div>
        </form>
    </div>
</div>

<div id="list_new"></div>
<div class="tab-pane box active" id="list1">
</div>

<script type="text/javascript">
$(document).ready(function() {
    document.getElementById('add_subject_component').onsubmit = function() {
        return false;
    };
    var subject_id = $('#subject_id').val();
    $('#list1').load("<?php echo base_url(); ?>subject/get_components/"+subject_id);

    $('#btn_submit1').click(function(e) 
    {
        var title = $('#title_add').val();
        var percentage = $('#percentage').val();
        var subject_id = $('#subject_id').val();
        var hidden_component_id = $('#hidden_component_id').val();
       
        if ((title != '') && ( percentage != '')) 
        {
            $('#list_new').html('<div id="message" class="loader"></div>');
            if (hidden_component_id == "")//add
            {
	            $.ajax({
	                type: 'POST',
	                data: {
	                    title: title,
	                    percentage: percentage,
	                    subject_id: subject_id
	                },
	                url: "<?php echo base_url();?>subject/components/create",
	                dataType: "html",
	                success: function(response) 
	                {
	                    $('#title_add').val('');
	                    $('#percentage').val('');
	                    $('#list_new').html(response);
	                    $('#list1').load("<?php echo base_url(); ?>subject/get_components/"+subject_id);
	                }
	            });
	        }
	        else //update
	        {
	        	$.ajax({
	                type: 'POST',
	                data: {
	                    title: title,
	                    percentage: percentage,
	                    subject_id: subject_id,
	                    component_id: hidden_component_id,
	                },
	                url: "<?php echo base_url();?>subject/components/do_update",
	                dataType: "html",
	                success: function(response) 
	                {
	                    $('#title_add').val('');
	                    $('#percentage').val('');
	                    $('#hidden_component_id').val('');
	                    $('#list_new').html(response);
	                    $('#list1').load("<?php echo base_url(); ?>subject/get_components/"+subject_id);
	                }
	            });
	        }
        }
    });
});
</script>
