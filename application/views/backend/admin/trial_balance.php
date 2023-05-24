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
	
$( window ).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
    });
	
</script>

<!-- filter -->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
	    <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
		<h3 class="system_name inline">
                <?php echo get_phrase('trial_balance');?> 
        </h3>
	</div>
</div>

<span id="message" style="color: red;"></span>


<div>
        <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
            
            <div class="col-md-4">
               <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input id="start_date" autocomplete="off"  class="form-control datepicker" placeholder="Select Start Date" required data-format="dd/mm/yyyy" />
                    <span style="color: red;" id="sd"></span>
                </div>
    
            </div>
            
            <div class="col-md-4">
               <div class="form-group">
                      <label for="end_date">End Date</label>
                      <input id="end_date" autocomplete="off"  class="form-control datepicker" placeholder="Select End Date"  required data-format="dd/mm/yyyy"/>
                      <span style="color: red;" id="ed"></span>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group" style="margin-top: 20px;">
                    <input type="hidden" id="apply_filter" name="apply_filter" value="1">
                    <button id="select" class="modal_save_btn"> <?php echo get_phrase('filter');?></button>
                    <a id="btn_show" href="<?php echo base_url(); ?>transection_account/trial_balance" class="modal_cancel_btn" style="display:none;padding:5px 8px !important;">
                       <i class="fa fa-remove"></i> <?php echo get_phrase('remove_filter');?> 
                    </a>
                </div>
            </div>
            
        </div>
    </div>


<div class="row" id="table">
</div>


<script>

	$(document).ready(function(){		
	
    	$('#select').click(function(){
            var start_date = $( "#start_date" ).val();
            var end_date = $( "#end_date" ).val();
            if(start_date != "" || end_date != "")
            {
                $("#btn_show").show();
            }
            
            $('#select').attr('disabled',true);
            get_all_rec();
            $('#select').attr('disabled',false);
            $('#message').html('');
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
        $.ajax({
                type: 'POST',
                data: {
                    coa_id: coa_id,
                    end_date: end_date,
                    start_date: start_date,
                    apply_filter: apply_filter
                },
                url: "<?php echo base_url();?>transection_account/get_trial_balance",
                dataType: "html",
                success: function ( response )
                {
                    $("#table").html(response);
                }
        });
    }
    
</script>

<script>
$('#is_closed').change(function(){
   this.value = this.checked ? 1 : 0;
});

</script>

<!--//***********************Date filter validation***********************-->
<script>
    $("#start_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("start_date").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#end_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("end_date").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->