<?php  
if (right_granted('students_withdraw'))
{

    if($this->session->flashdata('club_updated')){
    	echo '<div align="center">
    	<div class="alert alert-success alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
    	'.$this->session->flashdata('club_updated').'
    	</div> 
    	</div>';
    }
?>
<script>
	$(window).on("load",function() {
		setTimeout(function() {
			$('.alert').fadeOut();
		}, 3000);
	});	
</script>

   <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('student_withdraw_listing');?>   
            </h3>
        </div> 
    </div>
    
    <div data-step="1" data-position='top' data-intro="Please select filters and click on filter button to get specific records">
        <div class="row filterContainer">
            <div class="col-md-4 col-lg-4 col-sm-4">
                <input type="text" name="starting" id="starting" autocomplete="off"  placeholder="Select Starting Date" class="form-control datepicker" data-format="dd/mm/yyyy">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4">
                <input type="text" name="ending" id="ending" autocomplete="off"  placeholder="Select Ending Date" class="form-control datepicker" data-format="dd/mm/yyyy">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4">
                <select id="section_id_filter" class="selectpicker form-control" name="section_id">                            
                    <?php echo section_selector();?>                              
 				</select>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-3 pt-3">
                <div id="select" class="btn btn-primary" ><?php echo get_phrase('filter');?></div>
                <a id="btn_show" href="<?php echo base_url(); ?>c_student/withdraw_listing" class="btn btn-danger" 
                style="padding:5px 8px !important;  display: none !important;"><?php echo get_phrase('remove_filter');?></a>
            </div>
	</div>
</div>
    <div class="col-md-12 col-lg-12 col-sm-12">   
        <div id="table" data-step="2" data-position='top' data-intro="withdraw student record">
    </div>
</div>

<!-- DATA TABLE EXPORT CONFIGURATIONS -->                      
<script type="text/javascript">
$(document).ready(function(){
    $('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });		
    get_all_rec();
    $("#departments_id").change(function(){
    	$("#d2").html("");
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
                $("#section_id").html("<option value=''><?php echo get_phrase('select_section'); ?></option>");
                $("#class_id").html(response);		
            }
    	});
    });

    $("#class_id").change(function(){
    	$("#d3").html("");
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
		$("#d1").html("");
		$("#d2").html("");
		$("#d3").html("");
		$("#d4").html("");
    	var section_id=$("#section_id_filter").val();
    	var start_date=$("#starting").val();
    	var end_date=$("#ending").val();
    	if(section_id!="" || start_date!="" || end_date!="")
    	{
    		$("#btn_show").show();	
    	get_all_rec();
    	}
    });
});
	
function get_all_rec(){
	var section_id=$("#section_id_filter").val();
    var start_date=$("#starting").val();
    var end_date=$("#ending").val();
	$("#loading").remove();
    $("#table").html("<div id='loading' class='loader'></div>");
	$.ajax({
		type: 'POST',
		data: {section_id:section_id,start_date:start_date,end_date:end_date},
		url: "<?php echo base_url();?>c_student/get_withdraw_student",
		dataType: "html",
		success: function(response) {
	        $("#table").html(response);
		}
	});
}	
</script>

<?php } ?>


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