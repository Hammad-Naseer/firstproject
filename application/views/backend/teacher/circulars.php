<style>
    .mycolor{color:#00859a;     font-weight: bold;}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('circulars'); ?>
        </h3>
    </div>
</div>
<?php
    $login_detail_id = $_SESSION['login_detail_id'];
    $yearly_term_id = $_SESSION['yearly_term_id'];
    $department_arr = get_teacher_department($login_detail_id);
    $class_arr = get_teacher_class($login_detail_id);
    $section_arr = get_teacher_section($login_detail_id);
    $time_table_arr = get_time_table_teacher_section($login_detail_id, $yearly_term_id);
?>
<form method="post" action="<?php echo base_url();?>teacher/circulars" class="form" data-step="1" data-position='top' data-intro="Please select the filters and press Filter button to get specific records">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="text" name="starting" autocomplete="off"  id="starting" value="<?php if($start_date > 0)
				{
					 echo date_dash($start_date);
				}
				?>" placeholder="Select Starting Date" class="form-control datepicker" data-format="dd/mm/yyyy">
                <label style="color: red;" id="sd"></label>
    		</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="text" name="ending" autocomplete="off" id="ending" value="<?php 
				if($end_date > 0)
				{
					echo date_dash($end_date);
				}
				?>" placeholder="Select Ending Date" class="form-control datepicker" data-format="dd/mm/yyyy">
                <label style="color: red;" id="ed"></label>
    		</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" required>
                    <?php echo get_teacher_dep_class_section_list($teacher_section, $section);?>
                </select>
    		</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<select id="student_select" name="student_select" class="form-control">
                    <option value=""><?php echo get_phrase('select_student'); ?></option>
                    <?php echo get_subject_teacher_option_list($_SESSION['login_detail_id'], $student_id); ?>
                </select>
    		</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="submit" name="submit" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"/>
                <?php if($filter){ ?>
                    <a href="<?php echo base_url();?>teacher/circulars" class="btn btn-danger">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                    </a>
                <?php } ?>
    		</div>
		</div>
	</div>
  </form>


<div class="col-lg-12 col-md-12">
        <table cellpadding="0" style="width:100%;" cellspacing="0" border="0" class="table table-bordered table_export" data-step="2" data-position='top' data-intro="circulars records">
                	<thead>
                		<tr>
                            <td style="width:30px;"><div><?php echo get_phrase('s_no');?></div></td>
                            <td></td>
						</tr>
					</thead>
                    <tbody>
                    <?php $count = 1;foreach($circulars as $row){?>
                        <tr>
                            <td class="td_middle"><?php echo $count++;?></td>
                            <td style="line-height:22px;">
							<div style="color:#00859a; font-size:14px; font-weight:bold; text-transform:capitalize;"><?php echo $row['circular_title'];
							
                            
                           if($row['attachment']!="")
							{?>
							<a href="<?php echo display_link($row['attachment'],'circular');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
								
						<?php	
                            }
						?>
							</div>
							<div class="span5 item">
							<?php 
							if($row['student_id']!=''){
								echo "<strong>".get_phrase('student').": </strong>".$this->Crud_model->get_student_name($row['student_id']);
								echo "<br/>";
                            }
							?>
                            </div>
                            
                            <div class="span5">
							<?php 

                            echo "<strong>".get_phrase('department')." / </strong><strong>".get_phrase('class')." / </strong><strong>".get_phrase('section').":</strong>"?>
                                
                                <ul class="breadcrumb breadcrumb2"> 
                                    <li><?php echo $row['department']; ?></li>
                                    <li><?php echo $row['class_name']; ?></li>
                                    <li><?php echo $row['class_section']; ?></li>
                                </ul>
						
                            </div>
                            
                            <div><strong><?php echo get_phrase('detail');?>:</strong>
							<?php
							    echo $row['circular'];?>
							</div>
                            
                            
                            <div style="float:right; font-weight:bold;"><?php echo convert_date($row['create_timestamp']);?></div>
                            </td>
							
                        </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
</div>
<script>
$(document).ready(function(){
	
	var student_id='<?php echo $student_id;?>';
	if(student_id!='')
	{
		student_list='<?php echo section_student($section,$student_id);?>';
		$('#student_select').html(student_list);
	}
	
	
	$('#class_id').change(function(){
		
var	class_id=$(this).val();
		
		
		
		$.ajax({
     type: "POST",
  url: "<?php echo base_url(); ?>teacher/get_student",
   
   data: ({class_id:class_id}),
   dataType : "html",
    success: function(html) {
    	//alert(html);
    	$('#student_id').html(html);
 }
    
 
 });
		
		
		
		
		
		
		
		
	});
	

$("#dep_c_s_id").change(function() {
            var section_id = $(this).val();
            $("#icon").remove();
            $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>teacher/get_section_student",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    if (response != "") {
                        $("#student_select").html(response);
                    }
                    if (response == "") {
                        $("#student_select").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
                    }
                }
            });



        });	
	
	
	
//get_student();
	
	
});

function get_student(class_id){

		
}

</script>


















<script>

$(function(){ /* to make sure the script runs after page load */

	$('.item').each(function(event){ /* select all divs with the item class */
	
		var max_length = 150; /* set the max content length before a read more link will be added */
		
		if($(this).html().length > max_length){ /* check for content length */
			
			var short_content 	= $(this).html().substr(0,max_length); /* split the content in two parts */
			var long_content	= $(this).html().substr(max_length);
			
			$(this).html(short_content+
						 '<a href="#" class="read_more mycolor"><br/>Read More</a>'+
						 '<span class="more_text" style="display:none;">'+long_content+'</span>'); /* Alter the html to allow the read more functionality */
						 
			$(this).find('a.read_more').click(function(event){ /* find the a.read_more element within the new html and bind the following code to it */
 
				event.preventDefault(); /* prevent the a from changing the url */
				$(this).hide(); /* hide the read more button */
				$(this).parents('.item').find('.more_text').show(); /* show the .more_text span */
		 
			});
			
		}
		
	});
 
 
});


</script>

<script>
    jQuery(document).ready(function () 
    {
        jQuery('.dcs_list').on('change', function (){
            var id=this.id;
            var selected = jQuery('#'+ id +' :selected');
            var group = selected.parent().attr('label');
            jQuery(this).siblings('label').text(group);
        });
    });
</script>
  
<script>

$(document).ready(function()
{
    $("#department1").change(function()
    {
        var department_id=$(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {department_id:department_id},
            url: "<?php echo base_url();?>teacher/get_department_class",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#class1").html(response);      
            }
        });
    });

    $("#class1").change(function()
    {
        var class_id=$(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {class_id:class_id},
            url: "<?php echo base_url();?>teacher/get_class_section",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#section1").html(response);      
            }
        });
    });

    $("#academic_year").change(function()
    {
        var academic_year_id=$(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {academic_year_id:academic_year_id},
            url: "<?php echo base_url();?>teacher/get_yearly_term",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#yearly_term").html(response);      
            }
        });
    });

    $("#yearly_term").change(function()
    {
        var yearly_term_id=$(this).val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {yearly_term_id:yearly_term_id},
            url: "<?php echo base_url();?>teacher/get_section_list",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#section_list2").html(response);      
            }
        });
    });

    $("#section_list2").change(function()
    {
        var section_id=$(this).val();
        var yearly_term = $("#yearly_term").val();
        $(".loader_small").remove();
        $(this).after('<div class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {section_id:section_id, yearly_term:yearly_term},
            url: "<?php echo base_url();?>teacher/get_subject_list",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#subject_list").html(response);      
            }
        });
    });
});

</script>


<!--//***********************Date filter validation***********************-->
<script>
    $("#starting").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("starting").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#ending").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("ending").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("ending").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->