<?php


?>


<div class="btn-btn">btn</div>


<script>
$(document).ready(function(){		
$(".btn-btn").click(function(){
	
	$.ajax({
	type: 'GET',
	url: "<?php echo  $url; ?>",
	dataType: "html",
	success: function(response) { 
	alert(response);
			
			}
		});

	
		
	});		
	
	
	});
			
	
</script>
