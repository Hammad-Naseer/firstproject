<?php 

$school_id=$_SESSION['school_id'];

$edit_data=$this->db->get_where(get_school_db().'.acadmic_year' , array('academic_year_id' => $param2,'school_id'=>$school_id))->result_array();

?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" >
            		<i class="entypo-plus-circled"></i>
					<?php 
					if($param2=='')
					echo get_phrase('add_academic_year');
					else
					echo get_phrase('edit_academic_year');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'academic_year/acadmic_year_listing/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data', 'id'=>'disable_submit_btn'));?>
					<div class="form-group">
						<label for="field-1" class="control-label"><?php echo get_phrase('title');?><span class="star">*</span></label>
						<input maxlength="500" type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'];?>">
                        <input type="hidden" name="academic_year_id" value="<?php echo $edit_data[0]['academic_year_id'];   ?>">
					</div>
            		<div class="form-group">
            			<label for="field-2" class="control-label"><?php echo get_phrase('start_date');?><span class="star">*</span></label>
                		<input type="date" id="StartDate" class="form-control" name="start_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['start_date']; ?>"   data-format="dd/mm/yyyy">
                		<div id="sd" style="color:#ff0000;"></div> 
            		</div>
            		<div class="form-group">
            			<label for="field-2" class="control-label"><?php echo get_phrase('end_date');?><span class="star">*</span>
            			</label>
            			<input  id="EndDate" type="date" class="form-control" name="end_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['end_date']; ?>"   data-format="dd/mm/yyyy">
            			<div id="ed" style="color:#ff0000;"></div>
            		</div>
					<div class="form-group">
						<label for="field-2" class="control-label"><?php echo get_phrase('detail');?></label>
                        <textarea id="detail" oninput="count_value('detail','detail_count','1000')" maxlength="1000" class="form-control" name="detail" ><?php echo $edit_data[0]['detail']; ?></textarea>	
                        <p id="detail_count"></p> 	
                    </div>
				    <div class="form-group">
						<label for="field-1" class="control-label"><?php echo get_phrase('status');?></label>
						<select name="status" id="status" class="form-control">
						    <?php echo year_status_option_list($edit_data[0]['status']);?>
						</select>
					</div>
					<div class="form-group">
						<label for="field-1" class="control-label"><?php echo get_phrase('is_closed');?></label>
                        <?php
                        if($edit_data[0]['is_closed']==1)
                        {
							$checked="checked";
						}
						?>
						<input type="checkbox"  name="is_closed" id="is_closed" <?php echo $checked;?> value="<?php echo $edit_data[0]['is_closed'];?>" style="margin-top:9px;">
					</div>
					<div class="form-group">
						<div class="float-right">
                            <button type="submit" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                            <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                <?php echo get_phrase('cancel');?>
                            </button>
                        </div>
					</div>
				</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>






<script>
$('#is_closed').change(function(){
this.value = this.checked ? 1 : 0;
	});
$("#StartDate").change(function () {
    document.getElementById("sd").innerHTML = "";
    var startDate = s_d($("#StartDate").val());
    var endDate = s_d($("#EndDate").val());
    if ((Date.parse(endDate) <= Date.parse(startDate)))
    	{
        document.getElementById("sd").innerHTML = "<?php echo get_phrase('start_date_should_be_less_then_End_date');?>";
        document.getElementById("StartDate").value = "";
    	}
    /* else if ((Date.parse(startDate) <  Date.parse("<?php echo $edit_data[0]['start_date']; ?>"))) 
     	{
        document.getElementById("sd").innerHTML = "please select Start date with in accademic session";
        document.getElementById("StartDate").value = "";      
     	}*/
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
   /* else if ((Date.parse(endDate) > Date.parse("<?php echo $edit_data[0]['end_date']; ?>")))
     {	
    document.getElementById("ed").innerHTML = "please select End date with in accademic session";
        document.getElementById("EndDate").value = "";    
    }*/
});
function s_d(date){
var date_ary=date.split("/");
return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0];	
}
</script>



