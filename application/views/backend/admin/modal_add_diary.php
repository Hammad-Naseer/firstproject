<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-title black2">
		<i class="entypo-plus-circled"></i>
		<?php echo get_phrase('');?>
        <?php echo get_phrase('add_diary');?>
    </div>
    <div class="panel-body">
	    <form id="data" method="post" enctype="multipart/form-data" class="form-horizontal form-groups-bordered validate">
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('Class/Section');?><span class="red"> * </span></label>
                <select id="section_id1" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">                   
                    <?php echo section_selector();?>           
     			</select>
     			<div id="section-err"></div>
            </div>
            <div class="form-group" id="students" >
                <label class="control-label"><?php echo get_phrase('select_subject');?><span class="red"> * </span></label>
                <select name="subject_id" id="subject_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                    <option value=""><?php echo get_phrase('select_subject');?></option>
                </select>
            </div>            
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('assign_date');?></label>
                <input type="date" class="form-control" name="assign_date" id="assign_date" value="<?php echo $date=Date('Y-m-d'); ?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"   data-format="dd/mm/yyyy"/>
                <div id="error_start" class="col-sm-8 col-sm-offset-4"></div>    
            </div>
            <div class="form-group"> 
                <label class="control-label"><?php echo get_phrase('academic_planner_task');?></label>
                <div id="item_list" class="col-sm-5"></div> 
            </div>                
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('title');?></label>
                <input type="text" class="form-control" name="title"/>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('task');?></label>
                <textarea name="task" id="task" rows="5"  maxlength="500" oninput="count_value('task','abcd','500')" placeholder="<?php echo get_phrase('add_task');?>" class="form-control"></textarea>                      
            </div>
            <div id="abcd" class="col-sm-12"></div>
            <div class="form-group">
        <label class="control-label"><?php echo get_phrase('due_date');?><span class="red"> * </span></label>
        <input type="date" class="form-control" name="due_date" id="due_date" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-format="dd/mm/yyyy"/>
        <div id="error_end" class="col-sm-8 col-sm-offset-4"></div>    
    </div>         
            <div class="form-group">
        <label class="control-label"><?php echo get_phrase('attachment');?></label>
        <input value="" type="file" class="form-control" name="image1" id="avatar" onchange="file_validate('avatar','doc','img_f_msg')">
        <span style="color: green;"><?php echo get_phrase('allowed_file_size');?>:  2 MB(2048 kbs), <?php echo get_phrase('allowed_file_types');?>: png, jpeg, jpg, pdf, doc, docx</span>
        <br />
        <span style="color: red;" id="img_f_msg"></span>
    </div>
            <div class="form-group">
        <div class="float-right">
			<button type="submit" id="save_btn" class="modal_save_btn">
				<?php echo get_phrase('save');?>
			</button>
			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
				<?php echo get_phrase('cancel');?>
			</button>
		</div>
	</div>
        </form>                
    </div>  
    <div id="testing"></div>
</div>    
<script>
$(document).ready(function(){
 $("form#data").submit(function(){

    var formData = new FormData($(this)[0]);

    $.ajax({
        url: "<?php echo base_url();?>diary/diarys/create",
        type: 'POST',
        data: formData,
        async: false,
        success: function (response) {
        	//alert(response);
            //alert('hi');
            $("#save_btn").attr('disabled','disabled');
        	var obj = jQuery.parseJSON(response);
        	var section_id= obj.section_id;
        	var diary_id=obj.diary_id;
        	var subject_id=obj.subject_id;
            showAjaxModal('<?php echo base_url();?>modal/popup/modal_diary_student/'+section_id+'/'+diary_id+'/'+subject_id);
            
        },
        cache: false,
        contentType: false,
        processData: false
    }); 

return false;

   
});

	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });
$("#section_id").html('<select><option value=""><?php echo get_phrase('select_section'); ?></option></select>');	
$('#acad_year1').change(function(){
	
var acad_year=$(this).val();
get_year_term1(acad_year);
function get_year_term1(){
	$('#acad_year1').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
var acad_year=$('#acad_year1').val();


	
$.ajax({
      type: 'POST',
       data: {acad_year:acad_year},
        url: "<?php echo base_url();?>diary/get_year_term2",
        dataType: "html",
        success: function(response) 
     	{ 
    		$('#message').remove();
    		if($.trim(response)!="")
    		{
    		$('#yearly_terms1').html(response);
    		}
    		if($.trim(response)=="")
    		{
    			$('#yearly_terms1').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
    		}
    	}
});	
	
	
	
	
}
});	

$("#departments_id").change(function(){
	var dep_id=$(this).val();
	$("#icon").remove();
	
	$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	$.ajax({
			type: 'POST',
			data: {department_id:dep_id},
	url: "<?php echo base_url();?>circular/get_class",
			dataType: "html",
			success: function(response) {			
				$("#icon").remove();	
			$("#class_id").html(response);
			$("#section_id1").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');
			$("#student_id").html('<select><option><?php echo get_phrase('select_student'); ?></option></select>');
			$("#subject_id").html('<select><option><?php echo get_phrase('select_subject'); ?></option></select>');
					
				 }
		});
});	

$("#class_id").change(function(){
	var class_id=$(this).val();
	$("#icon").remove();
	$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	$.ajax({
			type: 'POST',
			data: {class_id:class_id},
	url: "<?php echo base_url();?>circular/get_class_section",
			dataType: "html",
			success: function(response) {
				$("#icon").remove();
			$("#section_id1").html(response);
			$("#student_id").html('<select><option><?php echo get_phrase('select_student'); ?></option></select>');
			$("#subject_id").html('<select><option><?php echo get_phrase('select_subject'); ?></option></select>');
			
			 }
		});
	
	
	
});	

  $("#section_id1").change(function() 
    {
    	$('#item_list').html('');
        var section_id = $(this).val();

        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id
            },
            url: "<?php echo base_url();?>diary/section_student_subject",
            dataType: "html",
            success: function(response) {
            	//alert(response);
            	
                var obj = jQuery.parseJSON(response);
                $("#icon").remove();
                $("#subject_id").html(obj.subject);

            }
        });

    });
		

/*$("#section_id1").change(function(){
	var section_id=$(this).val();
	
	$("#icon").remove();
	$(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	$.ajax({
			type: 'POST',
			data: {section_id:section_id},
	url: "<?php echo base_url();?>diary/get_section_student_subject",
			dataType: "html",
			success: function(response) {
			var obj = jQuery.parseJSON(response);
				$("#icon").remove();		
			$("#student_id").html(obj.student);
			$("#subject_id").html(obj.subject);	
			$("#teacher_id").html('<select><option>Select Teacher</option></select>');	
				 }
		});
		
});	*/



$("#assign_date").change(function(){
	var assign_date=$(this).val();
	var subject_id= $('#subject_id').val();
    if(assign_date!="" && subject_id!="")
    {
    	$.ajax({
            type: 'POST',
            data: {assign_date:assign_date,subject_id:subject_id},
            url: "<?php echo base_url();?>diary/get_acad_checkboxes",
            dataType: "html",
            success: function(response) {
                $('#item_list').html(response);
            }
        });
    }
});


$("#subject_id").change(function(){
	var subject_id=$(this).val();
	var assign_date= $('#assign_date').val();
    if(assign_date!="" && subject_id!="")
    {
    	$.ajax({
            type: 'POST',
            data: {assign_date:assign_date,subject_id:subject_id},
            url: "<?php echo base_url();?>diary/get_acad_checkboxes",
            dataType: "html",
            success: function(response) {
                $('#item_list').html(response);
            }
        });
    }
	
});

$('#assign_date').on('change',function(){
			$('#btn_add').removeAttr('disabled','true');
			$('#error_start').text('');
			var start_date=$(this).val();
			var term_id=$('#yearly_terms1').val();
			if(start_date!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>diary/term_date_range",

					data: ({start_date:start_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						//alert(response);
						if(html==0){
						 $('#error_start').text('<?php echo get_phrase('assign_date_should_be_between_term_dates'); ?>');
						 $('#btn_add').attr('disabled','true');			
						}					
					}
				});
				}
		
	});

$('#due_date').on('change',function(){
		$('#btn_add').removeAttr('disabled','true');
			 $('#error_end').text('');
			var end_date=$(this).val();
			var term_id=$('#yearly_terms1').val();
			if(end_date!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>diary/term_date_range",

					data: ({end_date:end_date,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end').text('<?php echo get_phrase('due_date_should_be_between_term_dates'); ?>');		
						 $('#btn_add').attr('disabled','true');	
						}					
					}
				});
				}
		
	});

$("#assign_date").change(function () {
	$('#btn_add').removeAttr('disabled','true');
    var startDate = document.getElementById("assign_date").value;
    var endDate = document.getElementById("due_date").value;
 
    if ((Date.parse(endDate) < Date.parse(startDate))){
         $('#error_start').text("<?php echo get_phrase('assign_date_should_be_less_then_due_date'); ?> ");
       $('#btn_add').attr('disabled','true');	
        //document.getElementById("start_date").value = "";

    }
});

$("#due_date").change(function () {
    	$('#btn_add').removeAttr('disabled','true');
    var startDate = document.getElementById("assign_date").value;
    var endDate = document.getElementById("due_date").value;
 
    if ((Date.parse(startDate) > Date.parse(endDate))) {
        $('#error_end').text("<?php echo get_phrase('due_date_should_be_greater_than_assign_date'); ?>");
        $('#btn_add').attr('disabled','true');	
       // document.getElementById("end_date").value = "";
        
        
    }
});

$("#yearly_terms1").change(function(){
        //$("#assign_date").val('');
        
        //$("#due_date").val('');
        var date2=$("#assign_date").val();
        
        //$("#due_date1").val('');	
        var date1=$("#due_date").val();
        
        var term_id=$(this).val();
		if(date1!='')
		{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>noticeboards/term_date_range",

					data: ({date1:date1,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						alert(html);
						if(html==0){
						 $('#error_end').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');		
						 $('#btn_edit').attr('disabled','true');	
						}
						else{
						 $('#btn_edit').removeAttr('disabled');
						 $('#error_end').text('');		
						}					
					}
				});
		}
		if(date2!='')
		{
			
		$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>noticeboards/term_date_range",

				data: ({date1:date2,term_id:term_id}),
				dataType : "html",
				success: function(html) {
					alert(response);
					if(html==0){
					 $('#error_start').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');		
					 $('#btn_edit').attr('disabled','true');	
					}
					else{
					 $('#btn_edit').removeAttr('disabled');
					 $('#error_start').text('');		
					}					
				}
			});
		}	
});	

$("#acad_year1").change(function(){
    $("#assign_date").val('');
    $("#due_date").val('');	
});
});
