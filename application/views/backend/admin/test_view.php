<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


<form id="data" method="post" enctype="multipart/form-data">
    <input type="text" name="first" value="Bob" />
    <input type="text" name="middle" value="James" />
    <input type="text" name="last" value="Smith" />
    <input name="image" type="file" />
    <button>Submit</button>
</form>

<script>
	$(document).ready(function(){		
	 $("form#data").submit(function(){


$.getJSON( "<?php echo base_url(); ?>testing/sheraz_ajax", { id:"123", name:"234"})
  .done( function(resp){
alert("yes");
}).fail(function(){
   alert('Oooops');
});


});
		
		
	});
		
	

	
</script>