<script>
	function validateholiday(){
        alert('hi
	   var endDate=$('#end-date').val();
		var a=endDate.split('/');
		var startDate=$('#start-date').val();
		var b=startDate.split('/');
		var d1=new Date(b[2],b[0]-1,b[1]);
		var d2=new Date(a[2],a[0]-1,a[1]);
		if(d2<=d1){
			if($('#vacation p').empty()){
				$('#vacation').append('<p style="color:#80003B">Invalid Date!</p>');
			}
           ');
			// $('#val0').attr('disabled','disabled');
			return false;
		}
	}
</script>
    <div class="panel panel-primary" data-collapsed="0">
    	<div class="panel-heading">
        	<div class="panel-title black2">
        		<i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_vacation');?>
            </div>
        </div>
	    <div class="panel-body">
            <form  method="post" id="disable_submit_btn" action="<?php echo base_url();?>vacation/add_vacation" class="form-horizontal form-groups-bordered validate" target="_top" novalidate>
    			<!--<div class="padded">-->
    				<div class="form-group">
    					<label class="control-label"><?php echo get_phrase('title');?>
    					<span class="star">*</span></label>
    					<input maxlength="100" type="text" id="val0" name="vacation-name" required class="form-control"/>
    				</div>
    				<div class="form-group">
    				    <label class="control-label"><?php echo get_phrase('starts_on');?><span class="star">*</span></label>
    					<input class="form-control readonly"  id="start_date" type="date" name="start-date" required data-format="dd/mm/yyyy">
    				    <div id="error_start1"></div>
    				</div>
    				<div class="form-group">
    					<label class="control-label"><?php echo get_phrase('ends_on');?><span class="star">*</span></label>
    					<input  id="end_date" class="form-control" type="date" name="end-date" required data-format="dd/mm/yyyy"/>
    					<div id="error_end1" style="color: red;"></div>
    				</div>
    				<div class="form-group" <?= check_sms_preference(15,"style","sms") ?>>
    					<label class="control-label"><?php echo get_phrase('send_sms');?><span class="star">*</span></label>
    			        <input id="" class="" type="checkbox" name="send_message" />
    					<div id="error_end1"></div>
    				</div>
                	<div class="form-group" <?= check_sms_preference(15,"style","email") ?>>
    					<label class="control-label"><?php echo get_phrase('send_email');?><span class="star">*</span></label>
    			        <input id="" class=""  type="checkbox" name="send_email" />
    					<div id="error_end1"></div>
    				</div>
                 <!--</div>	-->
    			<div class="form-group">
    				<div class="float-right">
                        <button type="submit" id="btnn_edit" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                            <?php echo get_phrase('cancel');?>
                        </button>
                    </div>
    			</div>
    		</form>
	    </div>
    </div>

<script>
$(document).ready(function() {
    $('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });

    $("#end_date").change(function () {
    	$('#btnn_edit').removeAttr('disabled','true');
    var startDate =  s_d(document.getElementById("start_date").value);
    var endDate =  s_d(document.getElementById("end_date").value);
 
    if ((Date.parse(startDate) > Date.parse(endDate))) {
        $('#error_end1').text("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>");
        $('#btnn_edit').attr('disabled','true');	
       // document.getElementById("end_date").value = "";
  
        
    }
    

});
    $("#start_date").change(function () {
	$('#btnn_edit').removeAttr('disabled','true');
    var startDate =  s_d(document.getElementById("start_date").value);
    var endDate =  s_d(document.getElementById("end_date").value);
 
    if ((Date.parse(endDate) < Date.parse(startDate))){
         $('#error_start1').text("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>");
       $('#btnn_edit').attr('disabled','true');	
        //document.getElementById("start_date").value = "";

    }
});
});


    function s_d(date){
        var date_ary=date.split("/");
        return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0];	
    }
</script>
