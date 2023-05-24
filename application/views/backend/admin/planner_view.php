<style>
	.mgt10{
		margin-top:10px;
	}
	.myap{
		padding-top: 13px;
padding-bottom: 10px;
border-top: 1px solid #b75959;
border-bottom: 1px solid #b75959;
margin-bottom: 22px;
	}
	/*select{
		    border: 1px solid #131313!important;
	}*/
	.lab{
		padding-bottom: ;
color: #771C04;
font-weight: bold;
padding-left: 20px;
	}
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




<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
                    <h3 class="system_name inline">
                      <!--  <i class="entypo-right-circled carrow">
                        </i>-->
               <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/planner-view-2.png">   
               
               <?php echo get_phrase('planner_view');?>
                
                    </h3>
                    

                    
                    
                    </div> </div>
<div class="row thisrow">
<div class="col-lg-12 col-md-12 col-sm-12 ">

<form action="" method="post" id="filter" name="filter" class="validate">
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6">	
<select id="acad_year" class="form-control" name="acad_year" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
<?php echo academic_year_option_list();?>
</select>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 ">
<select id="yearly_terms" class="form-control">
<option value=""><?php echo get_phrase('select_yearly_term');?></option>

		
</select>

</div>
</div>

<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 mgt10">

<select id="departments_id" class="form-control" name="departments_id">
<?php 
echo department_option_list();
?>		
</select>
</div>

<div class="col-lg-3 col-md-3 col-sm-3 mgt10">

<select id="class_id" class="form-control" name="class_id">
<?php 
echo class_option_list();
?>		
</select>		
</div>

<div class="col-lg-3 col-md-3 col-sm-3 mgt10">

<select id="section_id" name="section_id" class="form-control">
                         
                          </select>
</div>

<div class="col-lg-3 col-md-3 col-sm-3 mgt10">

<select id="subject_id" name="subject_id" class="form-control">
                         
                          </select>
</div>
</div>











<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 mgt10">

<input type="submit"  class="btn btn-primary" 

id="btn_submit" style="padding: 5px 52px !important;" value="<?php echo get_phrase('submit');?>">	<a href=" <?php echo base_url(); ?>academic_planner/planner_view" style="display: none;" class="btn btn-danger" id="btn_remove">			<i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?></a>
</div>

</div>




</form>
<!--here-->




</div>
</div>













<div id="get_planner" class="col-lg-12 col-md-12 col-sm-12">

</div>
<div id="get_planner2" class="col-lg-12 col-md-12 col-sm-12">

</div>
<script>
$(document).ready(function(){	
document.getElementById('filter').onsubmit = function() {
    return false;
};
$("#section_id").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');
$("#class_id").html('<select><option><?php echo get_phrase('select_class'); ?></option></select>');	
$("#subject_id").html('<select><option><?php echo get_phrase('select_subject'); ?></option></select>');

if($('#acad_year').val()!=""){
	

	
get_year_term();
}	
});
$('#acad_year').change(function(){
	
get_year_term();


});

function get_year_term(){

$('#acad_year').after('<div id="message" class="loader_small"></div>');
var yearly_terms="<?php echo $_POST['yearly_terms'] ?>";
var acad_year=$('#acad_year').val();


	
$.ajax({
      type: 'POST',
       data: {acad_year:acad_year,yearly_terms:yearly_terms},
url: "<?php echo base_url();?>academic_planner/get_year_term",
 dataType: "html",
 success: function(response) { 
    //alert(response);  
    
$('#message').remove();
if($.trim(response)!="")
 	{  
		$('#yearly_terms').html(response);
	}
if($.trim(response)=="")
	{
		$('#yearly_terms').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
	}  

//alert(response);
}
});	
	
	
	
	
}	


 $(document).on('click',"#btn_submit",function(){
	var acad_year=$('#acad_year').val();
	var yearly_terms=$('#yearly_terms').val();
	
	
	$('#get_planner2').html('');
	
if(acad_year!="")
{
$('#get_planner').html('<div id="message" class="loader"></div>');	
$("#btn_remove").show();	
$.ajax({
      type: 'POST',
       data: {acad_year:acad_year,yearly_terms:yearly_terms},
url: "<?php echo base_url();?>academic_planner/get_planner",
 dataType: "html",
 success: function(response) { 
 
 //alert(response);
$('#get_planner').html(response);
//alert(response);
//to load first one result by default
month_first = $('#btn_new1').val();
var month=month_first.split(",");
planner(month[0],month[1]);
}
});

}
else
{
	
}	

	
	
		
});

$('#month_list').change(function(){
	var month_list=$('#month_list').val();
	$('#month_div').html(month_list);
	var str=month_list.split("-");
	var month=str[0];
	var year=str[1];
	var acad_year=$('#acad_year_select').val();
	var yearly_terms=$('#yearly_terms_select').val();
	var dept_id=$('#departments_select').val();
	var class_id=$('#class_select').val();	
	var section_id=$('#section_select').val();
	var subject_id=$('#subject_select').val();
	
	$('#get_planner2').html('<div id="message" class="loader"></div>');
$.ajax({
      type: 'POST',
       data: {acad_year:acad_year,yearly_terms:yearly_terms,month:month,year:year,dept_id:dept_id,class_id:class_id,section_id:section_id,subject_id:subject_id},
url: "<?php echo base_url();?>academic_planner/academic_plan_generator",

 dataType: "html",
 success: function(response) { 
      //alert(response);

$('#get_planner2').html(response);



}
});	
	
});

function planner(month,year){
	
	$('#get_planner2').html('<div id="message" class="loader"></div>');
	var subject_id=$('#subject_id').val();
	var section_id=$('#section_id').val();	
	var class_id=$('#class_id').val();
	var department_id=$('#departments_id').val();
$.ajax({
      type: 'POST',
       data: {month:month,year:year,subject_id:subject_id,section_id:section_id,class_id:class_id,department_id:department_id},
url: "<?php echo base_url();?>academic_planner/planner_generator",

 dataType: "html",
 success: function(response) { 
      //alert(response);

$('#get_planner2').html(response);



}
});	
	
	
	
	
}		
	
function save_data(){
	
	
	  var myform = document.getElementById("save_form");
	
	 $.ajax({
url: "<?php echo base_url();?>admin/save_planner"
,
            type: "POST",
         data:  new FormData(myform),
            mimeType:"multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
         {
         	$('#modal_ajax').hide();
           $(".close").trigger('click'); 
           get_planner();	
    }
             
       });
	
	
	
	
}
	
	
	
function edit_data(){
	
	
	  var myform = document.getElementById("edit_form");
	
	 $.ajax({
url: "<?php echo base_url();?>admin/edit_planner"
,
            type: "POST",
         data:  new FormData(myform),
            mimeType:"multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
         {
          $(".close").trigger('click'); 
           get_planner();	
         
    }
             
       });
	
	
	
	
}	
	
	
	
	
	
		
	$(document).ready(function(){
		$("#delete_link").click(function(e){
			e.preventDefault();
			
var str =$('#delete_link').attr('href');
var res = str.split("-");
delete_planner(res[0],res[1]);
			
			
			
		});
		
	
	$("#departments_id").change(function(){
	$('#get_planner2').html('');
	$('#get_planner').html('');
	var dep_id=$(this).val();
	$("#icon").remove();
	
	$(this).after('<div id="icon" class="loader_small"></div>');
	
	$.ajax({
			type: 'POST',
			data: {department_id:dep_id},
	url: "<?php echo base_url();?>academic_planner/get_class",
			dataType: "html",
			success: function(response) {
				
			
				
				$("#icon").remove();
			
			$("#class_id").html(response);
			$("#section_id").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');
			
				
				 }
		});
	
	
	
});	
	
	$("#class_id").change(function(){
	$('#get_planner2').html('');
	$('#get_planner').html('');
	var class_id=$(this).val();
	
	$("#icon").remove();
	$(this).after('<div id="icon" class="loader_small"></div>');
	
	$.ajax({
			type: 'POST',
			data: {class_id:class_id},
	url: "<?php echo base_url();?>academic_planner/get_class_section",
			dataType: "html",
			success: function(response) {
				$("#icon").remove();
			
			$("#section_id").html(response);
			
				
				 }
		});
	
	
	
});	




	$("#section_id").change(function(){
	$('#get_planner').html('');
	$('#get_planner2').html('');
	var section_id=$(this).val();
	
	$("#icon").remove();
	$(this).after('<div id="icon" class="loader_small"></div>');
	
	$.ajax({
			type: 'POST',
			data: {section_id:section_id},
	url: "<?php echo base_url();?>academic_planner/get_section_subject",
			dataType: "html",
			success: function(response) {
				$("#icon").remove();
			
			$("#subject_id").html(response);
			
				
				 }
		});
	
	
	
});
		
	});
	

	
	
	
	
	
function delete_planner(id,imge){



$.ajax({
      type: 'POST',
       data: {id:id,imge:imge},
url: "<?php echo base_url();?>admin/delete_planner",
 dataType: "html",
 success: function(response) { 

$(".close").trigger('click'); 
           get_planner();

}
});	
	
	
	
	
}	

	
	
	
	
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
