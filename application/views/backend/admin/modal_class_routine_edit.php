<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/includes/jquery-clockpicker.min.css" />

<script type="text/javascript" src="<?php echo base_url();?>assets/includes/jquery-clockpicker.min.js"></script>
<?php 

    $id = $this->uri->segment(4);
    $depart_id=$this->uri->segment(5);
    $sect_id=$this->uri->segment(6);
    
    $q="SELECT cr. * , c.class_id, c.departments_id
    FROM ".get_school_db().".class_routine_settings cr
    INNER JOIN ".get_school_db().".class_section s ON s.section_id = cr.section_id
    INNER JOIN ".get_school_db().".class c ON c.class_id = s.class_id
    where 
    cr.c_rout_sett_id=".$id." 
    AND cr.school_id=".$_SESSION['school_id'];
    
    $rec_rou=$this->db->query($q)->result_array();

?>
<div class="tab-pane box" id="add" style="padding: 5px">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_time_table_settings');?>
        </div>
    </div>
    <div class="box-content">
        <?php echo form_open(base_url().'time_table/class_routine_settings/edit/'.$id , array('class' => 'form-horizontal form-groups-bordered validate','id'=>'edit_time_table','target'=>'_top'));?>
        <div class="form-group" id="section">
            <label class="col-sm-4 control-label">
                <?php echo get_phrase('section');?>
            </label>
            <div class="col-sm-8">
                <input type="hidden" name="section_id" id="section_id" value="<?php echo $rec_rou[0]['section_id']; ?>">
                <input type="text" id="section_id2" class="form-control" value="<?php $section_hierarchy=section_hierarchy($rec_rou[0]['section_id']);
                	echo $section_hierarchy['d'].' - '.$section_hierarchy['c'].' - '. $section_hierarchy['s'];?>
                " disabled >
                <div id="section-err"></div>
            </div>
        </div>

        <div class="form-group" style="border:0px solid #FFF;">
            <label class="col-sm-4 control-label">
                
                <?php echo get_phrase('start_date');?>
                         <span class="star">*</span>   </label>
            <div class="col-sm-8">
                <input type="date" class="form-control" name="start_date" id="start_date1" value="<?php   
                 echo $rec_rou[0]['start_date']; ?>" required>
            </div>
        </div>

        <div class="form-group" style="border:0px solid #FFF;">
            <label class="col-sm-4 control-label">
                <?php echo get_phrase('end_date');?>       <span class="star">*</span>     </label>
            <div class="col-sm-8">
                <input type="date" class="form-control" name="end_date" id="end_date1" value="<?php echo $rec_rou[0]['end_date']; ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">
                <?php echo get_phrase('school_starting_time');?><span class="star">*</span>
            </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="cc1" name="start_time" id="start_time1" placeholder="Start Time" style="width:100%;" value="<?php echo $rec_rou[0]['start_time']; ?>" placeholder="HH:MM" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">
                <?php echo get_phrase('assembly_duration_mins');?><span class="star">*</span>
            </label>
            <div class="col-sm-8">
                <input type="number" name="assembly_duration" id="assembly_duration1" class="form-control" min="0" max="100" value="<?php echo $rec_rou[0]['assembly_duration']; ?>" required>
            </div>
        </div>
        <div id="other">
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('daily_no_of_periods');?><span class="star">*</span>
                </label>
                <div class="col-sm-8">
                    <input type="number" name="no_of_periods" id="no_of_periods1" class="form-control" min="1" max="10" value="<?php echo $rec_rou[0]['no_of_periods']; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('period_duration_mins');?><span class="star">*</span>
                </label>
               
                <div class="col-sm-8">
                 <input type="text" name="period_duration" id="period_duration1" class="form-control" value="<?php echo $rec_rou[0]['period_duration']; ?>" required>
                  <?php /*
                 <input type="radio" name="period_duration_type" value="1" class="period_duration_type1" id="same_duration1" <?php if($rec_rou[0]['period_duration_type'] == 0 || $rec_rou[0]['period_duration_type'] ==1) echo 'checked="checked"'; ?>>Same Duration
            <input type="radio" name="period_duration_type" value="2" class="period_duration_type1" id="different_duration1" <?php if($rec_rou[0]['period_duration_type'] == 2) echo 'checked="checked"'; ?>>Different Durations
                   
            <div id="duration_msg1"></div>
            */
                ?>
                </div>
                
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('break_after_period_no');?>
                </label>
                <div class="col-sm-8">
                    <input type="number" name="break_after_period" id="break_after_period1" class="form-control" min="1" max="10" 
                    value="<?php echo ($rec_rou[0]['break_after_period'] > 0) ? $rec_rou[0]['break_after_period']: ''; ?>">
                    <input type="hidden" name="c_rout_sett_id" id="c_rout_sett_id" value="<?php echo $rec_rou[0]['c_rout_sett_id']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('lunch_break_duration _mins');?>
                </label>
                <div class="col-sm-8">
                    <input type="number" name="break_duration" id="break_duration1" class="form-control" min="0" max="100" 
                    value="<?php echo ($rec_rou[0]['break_duration'] > 0) ? $rec_rou[0]['break_duration']: ''; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                   Break Duration In Mins After Every Period(If Any)
                </label>
                <div class="col-sm-8">
                    <input type="number" name="break_duration_after_every_period" id="break_duration_after_every_period" class="form-control" min="0" max="100" 
                    value="<?php echo ($rec_rou[0]['break_duration_after_every_period'] > 0) ? $rec_rou[0]['break_duration_after_every_period']: ''; ?>" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <?php echo get_phrase('is active');?><span class="star">*</span>
                </label>
                <div class="col-sm-8">
                    <label class="radio-inline"><input type="radio" name="is_active" class="is_active1" value="0" <?php if($rec_rou[0]['is_active'] == 0) echo 'checked="checked"'; ?> > <?php echo get_phrase('no');?></label>
                    <label class="radio-inline"><input type="radio" name="is_active" class="is_active1" value="1" <?php if($rec_rou[0]['is_active'] == 1) echo 'checked="checked"'; ?>> <?php echo get_phrase('yes');?></label>
                </div>
            </div>

        </div>
        <div id="msg_div1"></div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                <button type="submit" class="btn btn-info" id="btn_info">
                    <?php echo get_phrase('save');?>
                </button>
            </div>
        </div>
        <?php echo form_close();?>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
document.getElementById('edit_time_table').onsubmit = function() {
            return false;
};

    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);
    });
  /*  $('#section_id2').change(function() {
        $('.btn-info').removeAttr('disabled');
        var section_id = $(this).val();
        var term_id = $('#term_id1').val();
        $('#section-err').text('');
        if (section_id != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>time_table/check_section_routine_settings",

                data: ({
                    section_id: section_id,
                    term_id: term_id
                }),
                dataType: "html",
                success: function(html) {
                    console.log(html);
                    $("#icon").remove();
                    if (html == 'exists') {
                        $('#section-err').text('Settings already added for this section');
                        $('.btn-info').attr('disabled', true);
                    }

                }


            });
        }
    });*/
    
    $('#no_of_periods').change(function() {
        $('#break_after_period').attr('max', $(this).val() - 1);

    });

  $('#btn_info').click(function() 
        {
        var start_date  = $('#start_date1').val();
        var end_date  = $('#end_date1').val();
    	var start_time = $('#cc1').val();
        var assembly_duration = $('#assembly_duration1').val();
    	var no_of_periods = $('#no_of_periods1').val();
        var period_duration = $('#period_duration1').val();
        /*var regex = /^[0-9,]+$/;
        if(regex.test(period_duration))
        {
			
		} 
		else 
		{
			$('#duration_msg1').html("<span class='red'>Please enter durations seperated with comma</span>");
			return false;
		}*/
        
        var break_after_period = $('#break_after_period1').val(); 
        var break_duration = $('#break_duration1').val();
        /*var period_duration_type = $('input[name=period_duration_type]:checked').val(); */
        var is_active = $('input[name=is_active]:checked').val();     
        var section_id1 = $('#section_id').val();
        var c_rout_sett_id=$('#c_rout_sett_id').val();
        var break_duration_after_every_period     = $('#break_duration_after_every_period').val();
            /*if($('#same_duration1').is(':checked')) 
    	 	{
    			
    	 		var words = $('#period_duration1').val().split(',');
    	 	 	var words_length=words.length;
    			
    			if(words_length==1)
    			{
					
				}
				else
				{
					$('#duration_msg1').html("<span class='red'>Please enter one value only</span>");
					return false;
				}
    	 	}*/
    	/* else if($('#different_duration1').is(':checked')) 
    	 	{
    	 	 	var words = $('#period_duration1').val().split(',');
    	 	 	var words_length=words.length;
    			
    			var no_of_periods=$('#no_of_periods1').val();
    			if(no_of_periods==words_length)
    			{
					
				}
				if(no_of_periods!=words_length)
    			{
					$('#duration_msg1').html("<span class='red'>Please enter values equal to Daily No Of Periods</span>");
					return false;
				}
    	 	}	 */ 
			//&& period_duration_type!=""     -- commented for testing && break_duration!="" && break_after_period!="" 		
            if (no_of_periods != "" && period_duration!="" && start_time!="" && assembly_duration!="" && is_active!="" && start_date!="" && end_date!="")
            {
                 //period_duration_type:period_duration_type,
                $.ajax({
                    type: 'POST',
                    data: {
                    no_of_periods: no_of_periods,
                    period_duration: period_duration,
                    start_time:start_time,
                    assembly_duration:assembly_duration,
                    break_duration:break_duration,
                    break_after_period:break_after_period,
                    section_id1:section_id1,
                    is_active:is_active,
                    start_date:start_date,
                    end_date:end_date,
                    c_rout_sett_id:c_rout_sett_id,
                    break_duration_after_every_period:break_duration_after_every_period
                    },
                    url: "<?php echo base_url();?>time_table/class_routine_settings/edit",
                    dataType: "html",
                    success: function(response) {
                    	//$('#msg_div1').html(response);
                        $('#msg_div1').html("");
                       $('#modal_ajax').modal('hide');
                      
                       window.location="<?php echo base_url(); ?>time_table/class_routine/<?php echo $depart_id;?>/<?php echo $sect_id;?>/<?php echo $id;?>";
                      
                      
                    }
                });
            } 
            else
            {
				$('#msg_div').html("<span class='red'><?php echo get_phrase('please_fill_all_required_fields'); ?>");
			}
         
        });	
        
$('#period_duration1').click(function()
{
	$('#duration_msg1').html("");
});		

});
</script>
<script type="text/javascript">
$('.clockpicker').clockpicker()
    .find('input').change(function() {
        console.log(this.value);
    });
$('#single-input1').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true,

    'default': '09:00'
});
$('#single-input2').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true,

    'default': '09:00'
});
</script>
