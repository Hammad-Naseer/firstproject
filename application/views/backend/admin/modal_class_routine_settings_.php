	





<div class="panel-heading">
				<div class="panel-title" >
					<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_time_table_settings');?>
				</div>
			</div>






		<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url().'time_table/class_routine_settings/create' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
							
                               
                               
                                
                        <div class="form-group" style="border:0px solid #FFF;" >
                                <label class="col-sm-4 control-label"><?php echo get_phrase('academic_year');?></label>
                                <div class="col-sm-8">
                                 <label id="term_id1_selection" class="ipt2"></label>	
                               
                                
                                 
<select id="term_id1" name="term_id1" class="selectpicker form-control"  class="form-control" required >
 <?php echo yearly_term_selector();?>
 								</select>
                                </div>
                                
                                

                                </div>
                              
                              
                              
                              
                              
                              
                              
                              
							<div class="form-group"  style="border:0px solid #FFF;">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('department');?></label>
                                <div class="col-sm-8">
                                <label id="section_id1_selection" class="ipt2"></label>
                                <select id="section_id1" class="form-control selectpicker" name="section_id1" required>
                               	
                               
                               <?php echo section_selector();?>
                               
 								</select>
 								<div id="section-err" ></div>
                                </div>

                                </div>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                          

<div id="other">
			
			
			
			
			
			
			
			
			
			
			                 
					<div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('school_starting_time');?></label>
                                <div class="col-sm-8">
                            <!-- id="single-input1"-->
                                <input type="text" class="form-control" id="cc"  name="start_time"  placeholder="HH:MM" style="width:100%;" required> 
                                     
                       <!--       <input type="text" id="cc" placeholder="HH:MM">  
                              
                              <input type="text" class="form-control" data-mask="date">-->

                                </div>
                            </div>
			
			
			
			
				       <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('assembly_duration_mins');?></label>
                                <div class="col-sm-8">
 <input type="number" name="assembly_duration" id="assembly_duration" class="form-control" min="0" max="100" data-validate="required" data-message-required="Value Required"/>
                                </div>
                            </div>
				
				
				
				
					<div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('daily_no_of_periods');?></label>
                                <div class="col-sm-8">
<input type="number" name="no_of_periods" id="no_of_periods" class="form-control" min="1" max="10" data-validate="required" data-message-required="Value Required"/>
                                </div>
                            </div>
                            
                            
                            
                     

                            
					<div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('period_duration_mins');?></label>
                                <div class="col-sm-8">
 <input type="number" name="period_duration" id="period_duration" class="form-control" max="100" min="1"  required>
                                </div>
                            </div>
                            
                            
                            
                            
           
                            
                            
                       <?php /*     
					<div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('school_ending_time');?></label>
                                <div class="col-sm-8">
                                <input type="text" class="form-control" id="single-input2" name="end_time" placeholder="End Time" style="width:100%;" data-validate="required" data-message-required="Value Required">
                                </div>
                            </div>
                            */ ?>
                            
                            
                       <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('break_after_period_no');?></label>
                                <div class="col-sm-8">
 <input type="number" name="break_after_period" id="break_after_period" class="form-control" min="1" max="10" data-validate="required" data-message-required="Value Required" />
 
 
 
 
 
                                </div>
                            </div>     
					
					<div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo get_phrase('lunch_break_duration_mins');?></label>
                                <div class="col-sm-8">
 <input type="number" name="break_duration" id="break_duration" class="form-control" min="0" max="100"  data-validate="required" data-message-required="Value Required"/>
                                </div>
                            </div>
											
				

<div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_time_table_settings');?></button>
                              </div>
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              

                              
                              
                          
                              
                  
                              
                         
                       <!--       <input type="text" id="cc" placeholder="HH:MM">  
                              
                              <input type="text" class="form-control" data-mask="date">-->
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
    oncomplete: function(){ console.log('complete'); },
  })
  .on('cut', function(evt) {
    console.log(evt);
  })
  .on('paste', function(evt) {
    console.log(evt);
  });

</script>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<!--script type="text/javascript" src="assets/includes/jquery.min.js"></script-->




<script type="text/javascript">

$(document).ready(function(){
	//$('.selectpicker').selectpicker();
	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });
	
	
			$('#section_id1').change(function(){
				$('.btn-info').removeAttr('disabled');
				var section_id=$(this).val();
				
				var term_id=$('#term_id1').val();
				$('#section-err').text('');
				if(section_id!='')
				{
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>time_table/check_section_routine_settings",

					data: ({section_id:section_id,term_id:term_id}),
					dataType : "html",
					success: function(html) {
						$("#icon").remove();
						if(html =='exists'){
						$('#section-err').text('<?php echo get_phrase('settings_already_added_for_this_section'); ?>');
						$('.btn-info').attr('disabled',true);
						}
						
					}


				});	
				}
			});
			$('#term_id').change(function(){
				var term_id=$(this).val();
				$('#section-err').text('');
				$('#section_id1 option').attr("selected",false);
				$('.btn-info').removeAttr('disabled');
				
			});
			$('#no_of_periods').change(function(){
				$('#break_after_period').attr('max',$(this).val()-1);
				
			});
});
</script>
<script type="text/javascript">
$('.clockpicker').clockpicker()
	.find('input').change(function(){
		console.log(this.value);
	});
$('#single-input1').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	//'z-index','2000',
	'default': '09:00'
});
$('#single-input2').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	//'z-index','2000',
	'default': '09:00'
});
</script>






<script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/jquery-clockpicker.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/datepicker/bootstrap-clockpicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/datepicker/bootstrap-clockpicker.min.css">	

<script type="text/javascript" src="asset"></script>
<script type="text/javascript">
$('.clockpicker').clockpicker()
	.find('input').change(function(){
		console.log(this.value);
	});
var input = $('#single-input').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
});

$('.clockpicker-with-callbacks').clockpicker({
		donetext: 'Done',
		init: function() { 
			console.log("colorpicker initiated");
		},
		beforeShow: function() {
			console.log("before show");
		},
		afterShow: function() {
			console.log("after show");
		},
		beforeHide: function() {
			console.log("before hide");
		},
		afterHide: function() {
			console.log("after hide");
		},
		beforeHourSelect: function() {
			console.log("before hour selected");
		},
		afterHourSelect: function() {
			console.log("after hour selected");
		},
		beforeDone: function() {
			console.log("before done");
		},
		afterDone: function() {
			console.log("after done");
		}
	})
	.find('input').change(function(){
		console.log(this.value);
	});

// Manually toggle to the minutes view
$('#check-minutes').click(function(e){
	// Have to stop propagation here
	e.stopPropagation();
	input.clockpicker('show')
			.clockpicker('toggleView', 'minutes');
});
if (/mobile/i.test(navigator.userAgent)) {
	$('input').prop('readOnly', true);
}
</script>





