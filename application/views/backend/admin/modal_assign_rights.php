<?php $urlArr=explode('/',$_SERVER['REQUEST_URI']);
$user_type_id=end($urlArr);
/*$subject_id=$resArr;*/
//$CI = &get_instance();
//$this->db2=$CI->load->database('system',TRUE);
$q="SELECT * from ".get_system_db().".module";
$module=$this->db->query($q)->result_array();

?>

		<div class="tab-pane box" id="add" style="padding: 5px">
                
                <table id="subject_teacher_add" cellpadding="0" cellspacing="0" border="0" class="table">
  <form name="subject_teacher" method="post" class="form-horizontal form-groups-bordered validate">
  
  <?php foreach($module as $mod){
  			$matchArr[]=$mod['module_id'];
  			
  	?>
  
                  <?php $q2="select * from ".get_system_db().".action where module_id=".$mod['module_id']."";
                         		$query=$this->db->query($q2);
                         		$num_rows=$query->num_rows();
                         		$actions=$query->result_array();
                         		 $i = 1;
                         		
							echo "<tr>";
						    echo '<td>'.$mod['title'].'</td>';
                         		foreach($actions as $row){
									?>
								<tr><td><i class="fa myarrow fa-arrow-right" aria-hidden="true"></i><input class="teacher" type="checkbox" name="teachers" id="teacher<?php echo $row['action_id']?>"  value="<?php echo $row['action_id']?>">	<?php 
								echo get_phrase('action').$row['title'];
								?>
                                </input> </td>
								 <? if ($i % 3== 0) echo "</tr><tr>";

							    $i++;
							}
							echo "</tr>";
                              } 
                               ?>
   
                <tr><td colspan="4" align="right">
                    
                    <button type="button" id="submit-btn" class="btn btn-info"><?php echo get_phrase('save');?></button>
                    </td></tr>
            
	
		</form>
		
        </table>
                          
			</div>
	
	
<script type="text/javascript">

$(document).ready(function(){

			
	$('#submit-btn').click(function(){

	var subject_id=$('#subject_id').val();
	var teacher_id= $('.teacher:checked').serializeArray();
	$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>subject/assign_subject_teacher",

					data: ({subject_id:subject_id,teacher_id:teacher_id}),					
					success: function(response) {
						
						$('#modal_ajax').modal('hide');
						location.reload();						
					}

				});
	});
	$('a[id^=delete]').click(function()
    {
       // alert('delete');
		str=$(this).attr('id');
		id=str.replace('delete_','');
		
	                $.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>subject/delete_subject_teacher",

					data: ({id:id}),
					
					success: function(response) {
					
					}


				});
	
		
	});
});
</script>