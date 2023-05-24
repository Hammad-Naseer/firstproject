<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<div id="res">
	
	
	
	
</div>

<div id="btn_val">
	
	 <?php echo get_phrase('wow');?>
	
	
</div>

<script>
	$(document).ready(function(){		
	 $("#btn_val").click(function(){


	
$.ajax({
	type: 'GET',
url: "https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=923458501954&password=03458501954",
		dataType: "html",
		success: function(response) { 
		
				
		$("#res").html(response);
		

		}
	});	




   
});
		
		
	});
		
	

	
</script>