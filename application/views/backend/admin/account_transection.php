<style>
.boarder {
    border: 1px solid #f2f2f2;
    height: 34px;
}

.modal-backdrop {
    z-index: 0 !important;
}
	.sd{
		
		color:red;
	}
	
	
	.ed{
		
		color:red;
	}	
	
	.ad{
		
		color:red;
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
$(window).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<!-- filter -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
           
           
           
           
           
            <!--  <i class="entypo-right-circled carrow">
                        </i>-->
            <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/transection.png"> <?php echo get_phrase('manage_transactions'); ?>
        </h3>
        
        
        
        <?php
if (right_granted('managetransaction_manage'))
{
?>
	<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/transection_add_edit/');" class="btn btn-primary pull-right">
	    <i class="entypo-plus-circled"></i>
	    <?php echo get_phrase('Add_new_transaction');?>
	</a>
<?php
}
?>
        
        
        
        
    </div>
</div>





<div class="thisrow" style="padding:12px;">
    <div class="row">
        <div class="col-md-4 col-lg-4 col-sm-4">
         <select name="transection_filter" id="ad" class="form-control type_val" data-validate="required" data-message-required="Required" required >
	<option value="">
<?php echo get_phrase('select_transection_type'); ?></option>
          <?php
	echo transection_filter($_POST['transection_filter']);
	?>
			</select>
       <div class="ad"></div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <input id="start_date" class="form-control datepicker" placeholder="Select Start Date" required data-format="dd/mm/yyyy" />
            <div id="sd" class="sd"></div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <input id="end_date" class="form-control datepicker" placeholder="Select End Date"  required data-format="dd/mm/yyyy"/>
            <div id="ed" class="ed"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 mgt10">
            <div id="select" class="btn btn-primary filter"><?php echo get_phrase('filter'); ?></div>
            <a id="btn_show" href="<?php echo base_url(); ?>c_student/student_information" class="btn btn-danger" style="padding:5px 8px !important;  display: none !important;">
            
            <a style="display:none" id="filter_show" href="<?php echo base_url()?>transection_account/account_transection" class="btn btn-danger" style="padding:5px 8px !important; ">
                    <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
                </a> 
        </div>
    </div>
</div>


<div class="col-sm-12">
<!-- end filter -->
<div class="row" id="table">
</div>
</div>
<script>
$(document).ready(function() {
	
	
		$(".ad").change(function(){
			
			if($("#ad").val()=="")
		{
		$(".ad").html("<?php echo get_phrase('value_required'); ?>");
			
		}
			else{
					$(".ad").html("");
				
			}
			
			
			
		});
		
		$(".sd").change(function(){
			
			if($("#sd").val()=="")
		{
		$(".sd").html("<?php echo get_phrase('value_required'); ?>");
			
		}
			else{
					$(".sd").html("");
				
			}
			
			
			
		});
	
	
	
			
		$(".ed").change(function(){
			
			if($("#ed").val()=="")
		{
		$(".ed").html("<?php echo get_phrase('value_required'); ?>");
			
		}
			else{
					$(".ed").html("");
				
			}
			
			
			
		});
	
	
	
	
	
	
	
	
	
    get_all_rec();

    $('#select').click(function() {
	
		
		
		
		$(".sd").html("");
		
		$(".ed").html("");
		
		
		$(".ad").html("");
		
		
		
if($("#ad").val()=="" && $("#start_date").val()=="" && $("#end_date").val()=="")
{
	$(".ad").html("<?php echo get_phrase('please_select_atleast_one_value"'); ?>);
	
}
else
{
	$('#filter_show').show();
	get_all_rec();
}
	
		
		
		
		
		
		
    });





});



function get_all_rec() {

	
	
    var type_val = $(".type_val").val();
	
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    $("#loading").remove();
    $("#table").html("<div id='loading' class='loader'></div>");

    $.ajax({
        type: 'POST',
        data: {
            type_val: type_val,
            end_date: end_date,
            start_date: start_date
        },

        url: "<?php echo base_url();?>transection_account/tranaction_list/",
        dataType: "html",
        success: function(response) {

            $("#table").html(response);

        }
    });


}
</script>


<script>
$('#is_closed').change(function(){
this.value = this.checked ? 1 : 0;
	});

$("#start_date").change(function () {
    document.getElementById("sd").innerHTML = "";
    var startDate = s_d($("#start_date").val());
    var endDate = s_d($("#end_date").val());
   
    if ((Date.parse(endDate) < Date.parse(startDate)))
    	{
        document.getElementById("sd").innerHTML = "<?php echo get_phrase('start_date_should_be_less_then_end_date');?>";
        document.getElementById("start_date").value = "";
    	}
     else if ((Date.parse(startDate) < Date.parse("<?php echo $start_date_check; ?>"))) 
     	{
        document.getElementById("sd").innerHTML = "<?php echo get_phrase('please_select_start_date_within_academic_session');?>";
        document.getElementById("start_date").value = "";      
     	}
    }
    );
$("#end_date").change(function () {
	document.getElementById("ed").innerHTML = "";
    var startDate = s_d($("#start_date").val());
    var endDate = s_d($("#end_date").val());
    if ((Date.parse(startDate) > Date.parse(endDate))) {
        document.getElementById("ed").innerHTML = "<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>";
        document.getElementById("end_date").value = "";      
    }
   else if ((Date.parse(endDate) > Date.parse("<?php echo $end_date_check; ?>"))) {
    	
    document.getElementById("ed").innerHTML = "<?php echo get_phrase('please_select_end_date_within_academic_session');?>";
        document.getElementById("end_date").value = "";    
    }
});
function s_d(date){
var date_ary=date.split("/");
return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0];	
}
</script>
