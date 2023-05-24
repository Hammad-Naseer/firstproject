<table id="rights" cellpadding="0" cellspacing="0" border="0" class="table">
  <form name="assign_rights" method="post" class="form-horizontal form-groups-bordered validate">
 
	<?php
	foreach($res as $mod){
	?>
	<tr><td>			
   <?php echo module_actions($mod['module_id']);?>
   	
   </td></tr>
   <tr>
   	<td>
   		 <?php echo child_modules($mod['module_id']);?>
   	</td>
   	
   </tr>
   <?php }?>
                <tr><td colspan="4" align="right">
                    
                    <button type="button" id="submit-btn" class="btn btn-info"><?php echo get_phrase('save');?></button>
                    </td></tr>
            
	
		</form>
		
        </table>
<script>
$(document).ready(function(){
	
$('input[id^="modules"]').click(function(){
		var str=$(this).attr('id');

		var m_id=$(this).attr('value');
		if($('.'+m_id).find('input[type=checkbox]').prop('checked')==false)
		$('.'+m_id).find('input[type=checkbox]').prop('checked',true);
		else if($('.'+m_id).find('input[type=checkbox]').prop('checked')==true)
		$('.'+m_id).find('input[type=checkbox]').prop('checked',false);
	
});	
	$('#submit-btn').click(function(){
		
	var user_type_id='<?php echo $user_type_id?>';
	var module_id= $('.modules:checked').serializeArray();
	var action_id= $('.rights:checked').serializeArray();
	$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>user/assign_right",

					data: ({user_type_id:user_type_id,module_id:module_id,action_id:action_id}),					
					success: function(response) {
						window.location = "<?php echo base_url(); ?>user/user_type";
											
					}

				});
	});
});
</script>