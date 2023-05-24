
<style>
.validate-has-error{color:red;}

</style>

<?php
if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<script>
	
$( window ).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
    });
	
</script>



 
 <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
 
 
                    <h3 class="system_name inline">
                      
                      <?php echo get_phrase('time_table_settings');?>
                      
                    </h3>
                  
                  <a style="float:right" href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_class_routine_settings');" 
            	class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
            	<?php echo get_phrase('add_time_table_settings');?>
                </a> 	
         
                  
                </div>
                
                
                
                
                
<div class="thisrow">

<div class="row ">
<form id="filter" name="filter" method="post" class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;       
    padding-bottom: 0px;">

<div class="col-md-6 col-lg-6 col-sm-6" id="academic">
	<select id="academic_id" name="academic_id" class="form-control" required>
                             <?php echo academic_year_option_list($year);?>
                          </select>
                          <div id="academic-error" style="display:none"><?php echo get_phrase('value_required'); ?></div>
</div>










<div class="col-md-6 col-lg-6 col-sm-6 ">
	<select id="term_id" name="term_id" class="form-control" >
                            <option><?php echo get_phrase('select_term');?></option>
                          </select>
</div>
	
	


</div>
	
	
	
	
	
	
	
	
	
<div class="col-md-4 col-lg-4 col-sm-4 mgt10">
	<select id="departments_id" name="departments_id" class="form-control" >
                             <?php echo department_option_list();?>
                          </select>
</div>

<div class="col-md-4 col-lg-4 col-sm-4 mgt10">
<select id="class_id" name="class_id" class="form-control" >
                         
                          </select>
</div>

<div class="col-md-4 col-lg-4 col-sm-4 mgt10">
<select id="section_id" name="section_id" class="form-control" >
                         
                          </select>
</div>
	
<div class="col-sm-4 mgt10 "></div>




 <div class="col-md-12 col-lg-12 col-sm-12 clearboth " style="margin-top:55px;">
<input type="submit" id="select" class="btn btn-primary" value="<?php echo get_phrase('filter');?>"></input>
<a href="<?php echo base_url(); ?>time_table/time_table_list" style="display: none;" class="btn btn-danger" id="btn_remove">			<i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
</div>

</div>


</form>
</div>
<?php if(in_array('time_table_settings_add',$package_rights)){ ?>
 <div class="col-md-12 col-lg-12 col-sm-12">

</div>
<?php }?>
<br/><br/>
              <div id="table"></div>



<!--  DATA TABLE EXPORT CONFIGURATIONS -->                      
<script type="text/javascript">
	$(document).ready(function()
	{
		document.getElementById('filter').onsubmit = function() {
    return false;
};
		$('#table').load("<?php echo base_url();?>time_table/get_table");
		$("#section_id").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');
		$("#class_id").html('<select><option><?php echo get_phrase('select_class'); ?></option></select>');

		var datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
								
  	$("#departments_id").change(function(){
  		
  			var dep_id=$(this).val();
  			$("#icon").remove();

$(this).after('<div id="icon" class="loader_small"></div>');
  			$.ajax({
  					type: 'POST',
  					data: {department_id:dep_id},
  					url: "<?php echo base_url();?>time_table/get_class",
  					dataType: "html",
  					success: function(response) {
  						$("#icon").remove();
  						$("#class_id").html(response);
  						$("#section_id").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');
  					}
  				});

  		});

	$("#class_id").change(function(){
	  			var class_id=$(this).val();
	  			$("#icon").remove();
	  			$(this).after('<div id="icon" class="loader_small"></div>');
	  			$.ajax({
	  					type: 'POST',
	  					data: {class_id:class_id},
	  					url: "<?php echo base_url();?>time_table/get_class_section",
	  					dataType: "html",
	  					success: function(response) {
	  						$("#icon").remove();
	  						$("#section_id").html(response);
	  					}
	  				});
	  		});
$('#academic_id').on('change',function(){
			//$("#table").html('');
			var academic_year=$(this).val();
			
			if(academic_year=='')
			{
				$('#term_id').html('<select><option><?php echo get_phrase('select_term'); ?></option></select>');
			}
			else{
				$('#btn_remove').show();
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>time_table/get_term",

					data: ({academic_year:academic_year}),
					dataType : "html",
					success: function(html) {
						if(html !=''){
						$('#term_id').html(html);
						
						
						}
						
					}


				});
				}
		
	});
	$("#select").click(function(){
		  			var section_id=$("#section_id").val();
		  			
		  			if($('#academic_id').val()!=''){
						
					$("#table").html("<div id='loading' class='loader'></div>");
		  			$.ajax({
		  					type: 'POST',
		  					data: {department_id:$('#departments_id').val(),section_id:section_id,class_id:$('#class_id').val(),academic_id:$('#academic_id').val(),term_id:$('#term_id').val()},
		  					url: "<?php echo base_url();?>time_table/get_table",
		  					dataType: "html",
		  					success: function(response) {
		  						//console.log(response);
		  						if($.trim(response)!='')
		  						{
		  						$("#loading").remove();
		  						$("#table").html(response);
								}
								
		  						 
		  					}
		  				});
		  				}
		  		});				 
	});
		
</script>
<style>
.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #63b7e7; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

.loader_small {
       border: 7px solid #f3f3f3;
    border-top: 7px solid #63b7e7;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>