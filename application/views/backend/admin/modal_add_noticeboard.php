<style type="text/css">
   #noticeboards_add.form-control {
         .form-control { border-radius: 10px !important; }
   }
</style>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title black2">
    	    <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_notice');?>
    	</div>
    </div>	
	<div class="panel-body">
        <?php echo form_open(base_url().'noticeboards/noticeboard/create' , array('id'=>'noticeboards_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>  
            <div class="form-group">
            <label class="control-label"><?php echo get_phrase('title');?><span class="red"> * </span></label>
            <input type="text" class="form-control" name="notice_title" maxlength="100" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
        </div>
		    <div class="form-group">
		    <label class="control-label"><?php echo get_phrase('detail');?><span class="red"> * </span></label>
		    <textarea name="notice" maxlength="1000" id="notice" rows="5" oninput="count_value('notice','notice_count','1000')" placeholder="<?php echo get_phrase('add_notice');?>" class="form-control" required></textarea>
            <div id="notice_count" class="col-sm-12 " style="font-size: 10px;padding: 0px;"></div>
		</div>
            <div class="form-group">
            <label class="control-label"><?php echo get_phrase('type');?><span class="red"> * </span></label>
			<select id="notice_type" name="notice_type" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
    			<option value=""><?php echo get_phrase('select_type');?></option>
    			<option value="1"><?php echo get_phrase('private');?></option>
    			<option value="2"><?php echo get_phrase('public');?></option>
			</select>
        </div>                 
            <div class="form-group">
            <label class="control-label"><?php echo get_phrase('is_active');?><span class="red"> * </span></label>
            <select name="is_active" class="form-control">
        		<option value="1"><?php echo get_phrase('yes');?></option>
        		<option value="0"><?php echo get_phrase('no');?></option>
        	</select>
        </div>              
            <div class="form-group">
            <label class="control-label"><?php echo get_phrase('date');?><span class="red"> * </span></label>
            <input type="date" class="form-control" name="create_timestamp" id="date1" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
	        <div id="error_end"></div>
        </div>
	    	<div class="form-group">
			<label class="control-label"><?php echo get_phrase('send_sms');?><span class="star">*</span></label>
	        <input id="" class=""  type="checkbox" name="send_message" />
			<div id="error_end1"></div>
		</div>
	        <div class="form-group">
		    <label class="control-label"><?php echo get_phrase('send_email');?><span class="star">*</span></label>
            <input id="" class=""  type="checkbox" name="send_email" />
			<div id="error_end1"></div>
	    </div>	
            <div class="form-group">
                <div class="float-right">
					<button type="submit" class="modal_save_btn" id="btnn_edit">
						<?php echo get_phrase('add_notice');?>
					</button>
					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
						<?php echo get_phrase('cancel');?>
					</button>
				</div>
    		</div>
        </form>                
    </div> 

<script>
$(document).ready(function(){
    $('#is_active').change(function(){
        this.value = this.checked ? 1 : 0;
	});

	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });
    $('#acad_year').change(function(){
    var acad_year=$(this).val();
    get_year_term(acad_year);
    function get_year_term(){
    	$('#acad_year').after('<span id="message" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    /*var yearly_terms="<?php echo $_POST['yearly_terms'] ?>";*/
    var acad_year=$('#acad_year').val();
    
    $.ajax({
        type: 'POST',
        data: {acad_year:acad_year},
        url: "<?php echo base_url();?>noticeboards/get_year_term2",
        dataType: "html",
        success: function(response) 
        { 
            $('#message').remove();
            if($.trim(response)!="")
         	{ 
        	    $('#yearly_terms').html(response);
        	}
        	if($.trim(response)=="")
        	{
        		$('#yearly_terms').html('<select><option value=""><?php echo get_phrase('select_term'); ?></option></select>');
        	}  
        }
    });		
}
});		

$('#date1').on('change',function(){
		$('#btnn_edit').removeAttr('disabled','true');
			 $('#error_end').text('');
			var date1=$(this).val();
			var term_id=$('#yearly_terms').val();
			if(date1!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>noticeboards/term_date_range",

					data: ({date1:date1,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');		
						 $('#btnn_edit').attr('disabled','true');	
						}					
					}
				});
				}
		
	});

$("#yearly_terms").change(function(){
var date1=$("#date1").val();
var term_id=$(this).val();
			if(date1!='')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>noticeboards/term_date_range",

					data: ({date1:date1,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						if(html==0){
						 $('#error_end').text('<?php echo get_phrase('date_should_be_between_term_dates'); ?>');		
						 $('#btnn_edit').attr('disabled','true');	
						}					
					}
				});
				}
});
$("#acad_year").change(function(){
$("#date1").val('');
});

});
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>