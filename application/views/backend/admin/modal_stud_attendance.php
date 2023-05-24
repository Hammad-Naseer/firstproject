<div class="panel panel-primary" data-collapsed="0">
<?php

    $student_id=$param2;
    $query="SELECT * FROM ".get_school_db().".student WHERE student_id=$student_id AND school_id=".$_SESSION['school_id']." AND student_status IN (".student_query_status().") ORDER BY roll desc";
    $students=$this->db->query($query)->result_array();
  //  print_r($query);
    foreach($students as $student)
    {
        $details=section_hierarchy($student['section_id']);
        echo '<input type="hidden" name="student_id" id="student_id" value="'.$student_id.'">';            
    }
?>

	<div class="panel-heading">
	    <div class="panel-title">
			<?php echo $student['name']; ?><span style="font-size:12px;"> (<?php echo get_phrase('roll');?>#<?php echo $student['roll']; ?>)</span>
			<ul class="breadcrumb" style="display: inline;background: none;font-size: 12px;">
				<li> <?php echo $details['c']; ?>  </li>
				<li> <?php echo $details['s']; ?>  </li>
			</ul>
		</div>
	</div>
	<div class="panel-body mt-4">
        <div class="form-group" id="class">
            <select id="acad_year_add" class="form-control" name="acad_year_add">
                <?php echo academic_year_option_list('',3);?>
            </select>     
        </div>     
        <div id="attend_list"></div>
            <div class="row " id="month_div1" style="display: none;">
            	<div class="col-sm-6">
                	<select id="month_list" name="month_list" class="form-control">
                		<option value=""><?php echo get_phrase('select_month'); ?></option>
                	</select>	
                </div>
                <div class="col-sm-4"><span class="month" id="month_div"></span>
                    
                </div>
            </div>
                <div class="float-right pb-2">
                    
                    <button type="submit" class="modal_save_btn" id="btn_view"><?php echo get_phrase('view');?></button>
                    
                </div>
            <div id="attend_show"></div> 
    </div>    
</div>    
<script>
$(document).ready(function(){
$("#btn_view").click(function(){
	var acad_year=$('#acad_year_add').val();
	var yearly_terms=$('#yearly_terms1').val();
	
	
$.ajax({
      type: 'POST',
       data: {acad_year:acad_year,yearly_terms:yearly_terms},
url: "<?php echo base_url();?>attendance/month_create",
 dataType: "html",
 success: function(response) 
 { 
 $('#month_div1').show();
					var obj = jQuery.parseJSON(response);
					var month_year=obj.month_current;
 
					var str="";
					var date_index="";
					str+='<option value=""><?php echo get_phrase('select_month'); ?></option>';
					for(var i=0;i<obj.month.length;i++)
					{
						if(obj.month[i] == month_year)
						{
							date_index=[i+1];
						}
						
	 					str+='<option value="'+obj.month[i]+'">'+obj.month[i]+'</option>';	 
					}
					$("#month_list").html(str);
					var month_year=obj.month_current;
					var month_array=month_year.split("-");
					var month_val=month_array[0];
					var year_val=month_array[1];
					$("#month_list").prop("selectedIndex", date_index);
					$('#month_div').html(month_year);
					var stud_id='<?php echo $student_id;?>'; 
					attendance(month_val,year_val,stud_id);
}
});	
	});
	
$('#acad_year_add').change(function(){
$('#attend_list').html('');
$('#attend_show').html('');	
var acad_year=$(this).val();
$('#yearly_terms1').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
if(acad_year!=''){
	$('#acad_year_add').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	get_year_term1(acad_year);
	
}


function get_year_term1(){
var acad_year=$('#acad_year_add').val();


	
$.ajax({
      type: 'POST',
       data: {acad_year:acad_year},
url: "<?php echo base_url();?>attendance/get_year_term2",
 dataType: "html",
 success: function(response) { 
    //alert(response);  
    
$('#message').remove();
$('#yearly_terms1').html(response);
//alert(response);
}
});	
	
	
	
	
}
});	

$('#month_list').change(function(){
	var month_list=$('#month_list').val();
	$('#month_div').html(month_list);
	var str=month_list.split("-");
	var month=str[0];
	var year=str[1];
	var stud_id='<?php echo $student_id;?>'; 
	
	
	$('#get_planner2').html('<div id="message" class="loader"></div>');
$.ajax({
      type: 'POST',
       data: {month:month,year:year,stud_id:stud_id},
url: "<?php echo base_url();?>attendance/attendance_generator",

 dataType: "html",
 success: function(response) { 
      //alert(response);

$('#attend_show').html(response);



}
});	
	
});

$('#yearly_terms1').change(function(){
$('#attend_list').html('');
$('#attend_show').html('');		
});		
	});

function attendance(month,year,stud_id){
	
    $.ajax({
        type: 'POST',
        data: {month:month,year:year,stud_id:stud_id},
        url: "<?php echo base_url();?>attendance/attendance_generator",
        dataType: "html",
        success: function(response) { 
            $('#attend_show').html(response);
        }
    });	
	
}	
</script>