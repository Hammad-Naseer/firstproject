<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title black2"  style="color:#000" >
					<i class="entypo-plus-circled"></i>
            		
					<?php echo get_phrase('add_yearly_term');?>
				</div>
			</div>
			<div class="panel-body">
				
				<?php echo form_open(base_url().'academic_year/yearly_terms/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate','id'=>'form_sub', 'enctype' => 'multipart/form-data'));?>
                
			
	
				<div class="form-group">
					<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('title');?></label>
                        
					<div class="col-sm-8">
						<input type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="">
							
							
<input type="text" name="yearly_terms_id" value="<?php echo $param3; ?>">



</div>
				</div>
					
				<div class="form-group">
					<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('start_date');?></label>
                        
					<div class="col-sm-8">
						
						<input id="StartDate" type="text" class="form-control datepicker" name="start_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" 
						value="">
 
						<div id="sd" style="color:#ff0000;"></div>

					</div> 
				</div>
					
				<div class="form-group">
					<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('end_date');?></label>
                        
					<div class="col-sm-8">
						<input  id="EndDate" type="text" class="form-control datepicker" name="end_date" value="<?php echo date_dash($edit_data[0]['end_date']); ?>" >
						<div id="ed" style="color:#ff0000;"></div>
					</div> 
				</div>
					
				<div class="form-group">
					<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('detail');?></label>
                        
					<div class="col-sm-8">
						<textarea name="detail" ><?php echo $edit_data[0]['detail']; ?></textarea>		
					</div> 
				</div>
		
				<!---div class="form-group">
					<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('order_number');?></label>
                        
					<div class="col-sm-8">
						<input type="text" class="form-control" name="order_num" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['order_num'];?>">
	
							
					</div>
				</div-->
		
		<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('status');?></label>
                        
						<div class="col-sm-8">
						<select name="status" id="status" class="form-control">
						<?php echo term_status_option_list($edit_data[0]['status']);?>
						</select>
						</div>
					</div>
				<div class="form-group">
					<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('');?></label>
                        
					<div class="col-sm-8">
<input type="hidden" name="academic_year_id" value="<?php echo $param2;?>"/>					
						<button type="submit" class="btn btn-default"> <?php echo get_phrase('save');?></button>		
					
					</div> 
				</div>
				
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>
<script>
	$("#StartDate").change(function () {
			document.getElementById("sd").innerHTML = "";
			var startDate = s_d($("#StartDate").val());
			var endDate = s_d($("#EndDate").val());
			if ((Date.parse(endDate) <= Date.parse(startDate)))
			{
				document.getElementById("sd").innerHTML = "<?php echo get_phrase('start_date_should_be_less_then_end_date');?>";
				document.getElementById("StartDate").value = "";
			}
			else if ((Date.parse(startDate) <  Date.parse("<?php echo $start_date; ?>"))) 
			{
				document.getElementById("sd").innerHTML = "<?php echo get_phrase('please_select_start_date_with_in_accademic_session');?>";
				document.getElementById("StartDate").value = "";      
			}
		}
	);
	$("#EndDate").change(function () {
			document.getElementById("ed").innerHTML = "";
			var startDate = s_d($("#StartDate").val());
			var endDate = s_d($("#EndDate").val());
			if ((Date.parse(startDate) >= Date.parse(endDate))) {
				document.getElementById("ed").innerHTML = "<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>";
				document.getElementById("EndDate").value = "";      
			}
			else if ((Date.parse(endDate) > Date.parse("<?php echo $end_date; ?>"))) {
    	
				document.getElementById("ed").innerHTML = "<?php echo get_phrase('please_select_end_date_with_in_accademic_session');?>";
				document.getElementById("EndDate").value = "";    
			}
		});
	function s_d(date){
		var date_ary=date.split("/");
		return date_ary[2]+"-"+date_ary[0]+'-'+date_ary[1];	
	}
</script>
 











