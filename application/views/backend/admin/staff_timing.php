<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <h4 style="color:white;padding:5px;margin-bottom:10px">
            <?php echo get_phrase('staff_timing_details');?>
        </h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <?php echo get_phrase('start_date');?> : 
            <input type="date" id="start_date" class="form-control" data-format="dd/mm/yyyy"/>
        </div>
    	<div class="form-group">
    	    <?php echo get_phrase('end_date');?> : 
    	    <input type="date" id="end_date" class="form-control" data-format="dd/mm/yyyy" />
    	</div>
    	<div class="form-group">
    	    <div class="float-right">
				<button type="submit" id="btn_date" class="modal_save_btn">
					<?php echo get_phrase('select');?>
				</button>
				<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
					<?php echo get_phrase('cancel');?>
				</button>
			</div>
    	</div>
        <div id="return_val" class="row"></div>
    </div>
</div>
<script>
	$(document).ready(function(){
        $("#btn_date").click(function(){
	        staff_timing();
        });
	});
	
    function staff_timing(){	
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var staff_id="<?php echo $param2 ?>";
        if(start_date!="" && end_date!=""){
        	$.ajax({
        		type: 'POST',
        		data: {start_date:start_date,end_date:end_date,staff_id:staff_id},
                url:"<?php echo base_url(); ?>user/staff_timing",
        		dataType: "html",
        		success: function(response){ 
        		    $("#return_val").html(response);
        		}
        	});	
    	}else{}	
    }
</script>

