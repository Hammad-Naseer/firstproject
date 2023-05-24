<style>
	.boarder
    {
            border: 1px solid #f2f2f2;
            height: 34px;
	}
    .modal-backdrop
    {
       z-index: 0 !important;
    }

</style>
<?php  
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
	
$( window ).load(function() {
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
<img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/trial.png"> 
 <?php echo get_phrase($page_title);?>

</h3>
	
	</div>
</div>
<span id="message" style="color: red;"></span>
<div class="thisrow" style="padding:12px;">


<div class="row">



<div class="col-md-6 col-lg-6 col-sm-6">
<input id="start_date" class="form-control datepicker" placeholder="Select Start Date" required data-format="dd/mm/yyyy" />
<span style="color: red;" id="sd"></span>
</div>
<div class="col-md-6 col-lg-6 col-sm-6">
<input id="end_date" class="form-control datepicker" placeholder="Select End Date"  required data-format="dd/mm/yyyy"/>
<span style="color: red;" id="ed"></span>
    <input type="hidden" id="apply_filter" name="apply_filter" value="1">
</div>
</div>

<div class="row">

<div class="col-md-12 col-lg-12 col-sm-12">

<div id="select" class="btn btn-primary"> <?php echo get_phrase('filter');?></div>
<a id="btn_show" href="<?php echo base_url(); ?>transection_account/income_statement" class="btn btn-danger" style="display:none;padding:5px 8px !important;">

<i class="fa fa-remove"></i> <?php echo get_phrase('remove_filter');?> </a>
<a style="display: none;"  href="" class='btn btn-primary' id='card_create' > <?php echo get_phrase('create_section_cards');?></a>

</div>
</div>
</div>





<!-- end filter -->


<div class="row" id="table">
	
	
	

</div>


<script>
	$(document).ready(function(){		
	//get_all_rec();
	$('#select').click(function(){
	    //alert('echo');
		
var start_date = $( "#start_date" ).val();
var end_date = $( "#end_date" ).val();
        if(start_date != "" || end_date != "")
        {
            $("#btn_show").show();
        }
//if(start_date!="" && end_date!=""){

        $('#select').attr('disabled',true);
        get_all_rec();
        $('#select').attr('disabled',false);
        $('#message').html('');

        //alert('hi');
//}else{
	
//$('#message').html('All Field Must Be Selected');	
//}
	});
		});
		
function get_all_rec()
    {
                    var coa_id = $( "#coa_id").val();
                    var start_date = $( "#start_date" ).val();
                    var end_date = $( "#end_date" ).val();
                    var apply_filter = $( "#apply_filter" ).val();

                    $( "#loading" ).remove();
                    $( "#table" ).html( "<div id='loading' class='loader'></div>" );
                        $.ajax(
                       {
                            type: 'POST',
                            data: {
                                coa_id: coa_id,
                                end_date: end_date,
                                start_date: start_date,
                                apply_filter: apply_filter
                              },
                           //get_trial_balance
                            url: "<?php echo base_url();?>transection_account/get_income_statement",
                            dataType: "html",
                            success: function ( response )
                            {
                                $("#table").html(response);
                                //alert(response);
                            }
                        } );


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