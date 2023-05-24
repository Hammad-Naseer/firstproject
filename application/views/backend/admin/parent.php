<div class="row">
<div class="col-md-3 col-lg-3 col-sm-3">
	<select id="departments_id" name="departments_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
 <option value=""><?php echo get_phrase('select');?></option>
    <?php
   $this->db->where('school_id',$_SESSION['school_id']); 
$classes = $this->db->get(get_school_db().'.departments')->result_array();

foreach($classes as $row):
	?>
<option value="<?php echo $row['departments_id'];?>">
<?php echo $row['title'];?>
                                                    </option>
                                        <?php
										endforeach;
								  ?>
                          </select>





</div>

<div class="col-md-3 col-lg-3 col-sm-3">
<select id="class_id" name="class_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                         
                          </select>
</div>

<div class="col-md-3 col-lg-3 col-sm-3">
<select id="section_id" name="section_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                         
                          </select>
</div>
	
<div class="col-md-3 col-lg-3 col-sm-3">
<div id="select" class="btn btn-primary" ><?php echo get_phrase('select');?></div>
</div>
	
</div>

<div  class="row">
<br />
<div id="table" class="col-md-12 col-lg-12 col-sm-12">

</div>


	
	
</div>
                     
<script type="text/javascript">
jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(8, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(8, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});



	});
	
		
	function get_cnic(){
	var cnic=$('#cnic').val();
	//alert(cnic);
	//return false;
		$.ajax({
      type: 'POST',
       data: {cnic:cnic},
url: "<?php echo base_url();?>admin/get_cnic",
      dataType: "json",
      success: function(response) { 
//var obj = jQuery.parseJSON(response);
//var parsed = JSON.parse(response);
if($.trim(response.value)=='no'){
$('#name').removeAttr("disabled").val('');
$('#parent_id').removeAttr("disabled").val('');
     $('#email').removeAttr("disabled").val('');
$('#relation_with_student').removeAttr("disabled").val('');
$('#phone').removeAttr("disabled").val('');
$('#address').removeAttr("disabled").val('');
$('#profession').removeAttr("disabled").val('');

}
else{
	
$('#name').val(response[0].name).attr("disabled","true");

$('#parent_id').val(response[0].parent_id);

$('#email').val(response[0].email).attr("disabled","true");

$('#relation_with_student').val(response[0].relation_with_student).attr("disabled","true");

$('#phone').val(response[0].phone).attr("disabled","true");

$('#address').val(response[0].address).attr("disabled","true");

$('#profession').val(response[0].profession).attr("disabled","true");	
}      
}
});
}

			
		
			
</script>



<script>

$('#email').change(function(){
	
	get_email();
	
});


function get_email(){
	
	$('#email').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	var email=$('#email').val();
		$.ajax({
      type: 'POST',
       data: {email:email},
      url: "<?php echo base_url();?>admin/call_function",
      dataType: "html",
      success: function(response) { 
if($.trim(response)=='yes'){

$("#btn1").attr('disabled','true');
$("#email").css('border','1px solid red');
$("#icon").remove();

if($('#message').html()==undefined){
$("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_already_exist'); ?></p>');	
}
}else{
	
$("#btn1").removeAttr('disabled');
$("#email").css('border','1px solid green');
$("#icon").remove();	
$("#message").remove();	
}      
}
});
}



$("#departments_id").change(function(){
	var dep_id=$(this).val();
	
	$(".loader_small").remove();
	$(this).after('<div class="loader_small"></div>');
	
	$.ajax({
			type: 'POST',
			data: {dep_id:dep_id},
	url: "<?php echo base_url();?>c_student/get_class",
			dataType: "html",
			success: function(response) {
			$(".loader_small").remove();
$("#section_id").html("<option><?php echo get_phrase('select_class'); ?></option>");
$("#class_id").html(response);		
}
		});
	
	
	
});

$("#class_id").change(function(){
	var class_id=$(this).val();
		
	$(".loader_small").remove();
	$(this).after('<div class="loader_small"></div>');
	
	$.ajax({
			type: 'POST',
			data: {class_id:class_id},
	url: "<?php echo base_url();?>c_student/get_section",
			dataType: "html",
			success: function(response) {
			$(".loader_small").remove();

			
			$("#section_id").html(response);
			
			
				
				 }
		});
	
	
	
});

$("#select").click(function(){
	
get_data();

});

function get_data(){
	
	
	var section_id=$("#section_id").val();
$("#loading").remove();
$("#table").html("<div id='loading' class='loader'></div>");


$.ajax({
		type: 'POST',
		data: {section_id:section_id},
		 url: "<?php echo base_url();?>admin/get_parent_info",
		dataType: "html",
		success: function(response) { 
	
	$("#table").html(response);
		
		}
	});
	
	
}



	
	


</script>
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  border-left: 16px solid pink;
  width: 100px;
  height: 100px;
      margin-left: auto;
    margin-right: auto;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 1s linear infinite;
}


.loader_small {
  border: 5px solid #f3f3f3;
  border-radius: 50%;
  border-top: 5px solid blue;
  border-right: 5px solid green;
  border-bottom: 5px solid red;
  border-left: 5px solid pink;
  width: 20px;
  height: 20px;
      margin-left: auto;
    margin-right: auto;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 1s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>