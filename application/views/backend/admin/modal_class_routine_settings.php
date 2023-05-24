<?php
//$section_id = substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
$section_id = $this->uri->segment(4);
$sect_id=$section_id;
$depart_id=$this->uri->segment(5);
?>
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('add_time_table_settings');?>
        </div>
    </div>
    <div class="panel-body">
        <?php echo form_open(base_url().'time_table/class_routine_settings/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'add_time_table'));?>
        <div class="form-group" style="border:0px solid #FFF;">
            <label class="control-label">
                <?php echo get_phrase('department');?>
            </label>
                <label id="section_id1_selection" class="ipt2"></label>
                <input type="hidden" id="section_id1" class="form-control" name="section_id1" value="<?php echo $section_id; ?>" >
                <input type="text" value="<?php 
                    /*echo section_selector();*/ 
                    $section_hierarchy = section_hierarchy($section_id);
                    echo $section_hierarchy['d'].' - '.$section_hierarchy['c'].' - '.$section_hierarchy['s'];
                    ?>" class="form-control"  disabled="">    
                <div id="section-err"></div>
        </div>
        <div class="form-group" style="border:0px solid #FFF;">
            <label class="control-label">
                <?php echo get_phrase('start_date');?><span class="star">*</span>
            </label>
            <input type="date" class="form-control" name="start_date" id="start_date" data-format="dd/mm/yyyy" required>
        </div>
        <div class="form-group" style="border:0px solid #FFF;">
            <label class="control-label">
                <?php echo get_phrase('end_date');?><span class="star">*</span>
            </label>
            <input type="date" class="form-control" name="end_date" id="end_date" required data-format="dd/mm/yyyy">
        </div>
        <div id="other">
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('school_starting_time');?><span class="star">*</span>
                </label>
                <input type="text" class="form-control" id="cc" name="start_time" id="start_time" placeholder="HH:MM" style="width:100%;" required>  
            </div>
            
              <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('school_end_time');?><span class="star">*</span>
                </label>
                <input type="text" class="form-control" id="cs" name="end_time" id="end_time" placeholder="HH:MM" style="width:100%;" required>  
            </div>
            
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('assembly_duration_mins');?><span class="star">*</span>
                </label>
                <input type="number" name="assembly_duration" id="assembly_duration" class="form-control" min="0" max="100" data-validate="required" data-message-required="Value Required" />
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('daily_no_of_periods');?><span class="star">*</span>
                </label>
                <input type="number" name="no_of_periods" id="no_of_periods" class="form-control" min="1" max="10" data-validate="required" data-message-required="Value Required" />
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('period_duration_mins');?><span class="star">*</span>
                </label>
                <input type="text" name="period_duration" id="period_duration" class="form-control" required >
                 <?php /*<div id="duration_msg"></div>*/?>
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('break_after_period_no');?>
                </label>
                <input type="number" name="break_after_period" id="break_after_period" class="form-control" min="0" max="10" />
            </div>
            <div class="form-group">
                <label class="control-label">
					 Lunch Break Duration In Mins (If Any)
                </label>
                <input type="number" name="break_duration" id="break_duration" class="form-control" min="0" max="100" />
            </div>
            <div class="form-group">
                <label class="control-label">
                   Break Duration In Mins After Every Period(If Any)
                </label>
                <input type="number" name="break_duration_after_every_period" id="break_duration_after_every_period" class="form-control" min="0" max="100" />
            </div>
            <div class="form-group">
                <label class="control-label">
                    <?php echo get_phrase('is_active');?><span class="star">*</span>
                </label>
                <label><input type="radio" name="is_active" class="is_active"  value="0" checked><?php echo get_phrase('no'); ?> </label>
                <label><input type="radio" name="is_active" class="is_active" value="1"><?php echo get_phrase('yes'); ?></label>
            </div>
			<div id="msg_div"></div>
            <div class="form-group">
                <div class="float-right">
					<button type="submit" class="modal_save_btn" id="btn_submit">
						<?php echo get_phrase('add_time_table_settings');?>
					</button>
					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                        <?php echo get_phrase('cancel');?>
                    </button>
				</div>
            </div>

        </div>
        <?php echo form_close();?>
    </div>
</div>
<script>
$('#cc')
    
    .inputmask({
        alias: 'hh:mm',
        showMaskOnHover: false,
        showMaskOnFocus: false,
        oncomplete: function() {
            console.log('complete');
        },
    })
    .on('cut', function(evt) {
        console.log(evt);
    })
    .on('paste', function(evt) {
        console.log(evt);
    });
    
</script>
<script type="text/javascript">
$(document).ready(function() {
    document.getElementById('add_time_table').onsubmit = function() {
        return false;
    };

    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');
        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);
    });
    
    $('#no_of_periods').change(function() {
        $('#break_after_period').attr('max', $(this).val() - 1);

    });
			
    $('#btn_submit').click(function() 
        {
        var start_date         = $('#start_date').val();
        var end_date           = $('#end_date').val();
    	var start_time         = $('#cc').val();
    	var end_time         = $('#cs').val();
        var assembly_duration  = $('#assembly_duration').val();
    	var no_of_periods      = $('#no_of_periods').val();
        var period_duration    = $('#period_duration').val();
        var break_after_period = $('#break_after_period').val(); 
        var break_duration     = $('#break_duration').val();
        var is_active          = $('input[name=is_active]:checked').val(); 
        var section_id1        = $('#section_id1').val();
        var break_duration_after_every_period     = $('#break_duration_after_every_period').val();
            if (no_of_periods != "" && period_duration!="" && start_time!="" && end_time!=="" && assembly_duration!="" && break_duration!="" && break_after_period!="" && is_active!="" && start_date!="" && end_date!="")
            {
                
                $.ajax({
                    type: 'POST',
                    data: {
                    no_of_periods: no_of_periods,
                    period_duration: period_duration,
                    start_time:start_time,
                    end_time:end_time,
                    assembly_duration:assembly_duration,
                    break_duration:break_duration,
                    break_after_period:break_after_period,
                    section_id1:section_id1,
                    is_active:is_active,
                    start_date:start_date,
                    end_date:end_date,
                    break_duration_after_every_period:break_duration_after_every_period
                    },
                    url: "<?php echo base_url();?>time_table/class_routine_settings/create",
                    dataType: "html",
                    success: function(response) {
                        $('#msg_div').html("");
                         $('#modal_ajax').modal('hide');
                         
                       
                        window.location="<?php echo base_url();?>time_table/class_routine/<?php echo $depart_id;?>/<?php echo $sect_id;?>";
                       
                    }
                });
            } 
            else
            {
				$('#msg_div').html("<span class='red'><?php echo get_phrase('please_fill_all_required_fields'); ?></span>");
			}
        });
        
        	
	$('#period_duration').click(function(){
		$('#duration_msg').html("");
	});		
});
</script>


<script type="text/javascript">
// $('.clockpicker').clockpicker()
//     .find('input').change(function() {
//         console.log(this.value);
//     });
// $('#single-input1').clockpicker({
//     placement: 'bottom',
//     align: 'left',
//     autoclose: true,
//     //'z-index','2000',
//     'default': '09:00'
// });
// $('#single-input2').clockpicker({
//     placement: 'bottom',
//     align: 'left',
//     autoclose: true,
//     //'z-index','2000',
//     'default': '09:00'
// });
</script>
<!--<script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/jquery-clockpicker.min.js"></script>-->
<!--<script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/bootstrap-clockpicker.min.js"></script>-->
<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/datepicker/bootstrap-clockpicker.min.css">-->
<!--<script type="text/javascript" src="asset"></script>-->
<script type="text/javascript">
// $('.clockpicker').clockpicker()
//     .find('input').change(function() {
//         console.log(this.value);
//     });
// var input = $('#single-input').clockpicker({
//     placement: 'bottom',
//     align: 'left',
//     autoclose: true,
//     'default': 'now'
// });

// $('.clockpicker-with-callbacks').clockpicker({
//         donetext: 'Done',
//         init: function() {
//             console.log("colorpicker initiated");
//         },
//         beforeShow: function() {
//             console.log("before show");
//         },
//         afterShow: function() {
//             console.log("after show");
//         },
//         beforeHide: function() {
//             console.log("before hide");
//         },
//         afterHide: function() {
//             console.log("after hide");
//         },
//         beforeHourSelect: function() {
//             console.log("before hour selected");
//         },
//         afterHourSelect: function() {
//             console.log("after hour selected");
//         },
//         beforeDone: function() {
//             console.log("before done");
//         },
//         afterDone: function() {
//             console.log("after done");
//         }
//     })
//     .find('input').change(function() {
//         console.log(this.value);
//     });

// Manually toggle to the minutes view
// $('#check-minutes').click(function(e) {
//     // Have to stop propagation here
//     e.stopPropagation();
//     input.clockpicker('show')
//         .clockpicker('toggleView', 'minutes');
// });
// if (/mobile/i.test(navigator.userAgent)) {
//     $('input').prop('readOnly', true);
// }
</script>
