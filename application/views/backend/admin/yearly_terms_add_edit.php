<?php 
    $school_id=$_SESSION['school_id'];
    $edit_data=$this->db->get_where(get_school_db().'.yearly_terms' , array('yearly_terms_id' => $param3,'school_id'=>$school_id))->result_array();
    $title = 'Add Yearly Term';
    if($param3 >0){
    	$title = 'Edit Yearly Term';
    }
?>
<style>
    button:disabled,
    button[disabled]{
        background-color: #09537f80 !important;
        color: white !important;
    }
</style>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2"  style="color:#000" >
            		<i class="entypo-plus-circled"></i>
            		
					<?php echo get_phrase($title);?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url().'academic_year/yearly_terms/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate','id'=>'form_sub', 'enctype' => 'multipart/form-data'));?>
                
                <div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('academic_year');?></label>
                    <input type="hidden" name="red_id" value="<?php echo $param3; ?>"/>
                    <input type="hidden" name="yearly_terms_id" value="<?php echo $param3;   ?>">
                    <input value="<?php echo  $param2; ?>" name="academic_year_id" type="hidden">		
                    <?php 
                        $academic_year_id= $edit_data[0]['academic_year_id'];
                        $qur_r="select * from ".get_school_db().".acadmic_year where academic_year_id=$param2";
                        $query=$this->db->query($qur_r)->result_array();
                    	foreach($query as $rows){
                    		$end_date=convert_date($rows['end_date']);
                    		$start_date=convert_date($rows['start_date']);
                    		$end_date_check=$rows['end_date'];
                    		$start_date_check=$rows['start_date'];
                    ?>   
                     <?php echo  $rows['title'];    ?>		
                    		<span style="font-size: 11px;">	
                    		( <?php echo $start_date; ?> to <?php echo $end_date; ?>)
                    				
                    			</span>
                    <?php	}
                    ?>			
                    <span id="message" style="color: red;"></span> 
                </div>
				<div class="form-group">
					<label for="field-1" class="control-label"><?php echo get_phrase('title');?><span class="star">*</span></label>
					<input maxlength="500" type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'];?>">
				</div>
				<div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('start_date');?><span class="star">*</span></label>
 		            <input id="StartDate" type="date" class="form-control" name="start_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['start_date']; ?>" data-format="dd/mm/yyyy">
                    <div id="sd" style="color:#ff0000;" ></div> 
				</div>	
				<div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('end_date');?><span class="star">*</span></label>
		            <input  id="EndDate" type="date" class="form-control" name="end_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['end_date']; ?>"  data-format="dd/mm/yyyy">
		            <div id="ed" style="color:#ff0000;"></div> 
				</div>
				<div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('detail');?></label>
                    <textarea class="form-control"  id="detail" oninput="count_value('detail','detail_count','1000')" maxlength="1000" name="detail" ><?php echo $edit_data[0]['detail']; ?></textarea>
                    <div class="col-lg-12" id="detail_count"></div>
                </div>
		        <div class="form-group">
					<label for="field-1" class="control-label"><?php echo get_phrase('status');?></label>
					<select name="status" id="status" class="form-control">
					    <?php echo term_status_option_list($edit_data[0]['status']);?>
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
					<input type="checkbox" name="is_closed" id="is_closed" <?php echo $checked;?> value="<?php echo $edit_data[0]['is_closed'];?>" style="margin-top:10px;">
				</div>
				<div class="form-group">
					<div class="float-right">
                        <button type="submit" class="modal_save_btn"><?php echo get_phrase('save');?></button>
                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                            <?php echo get_phrase('cancel');?>
                        </button>
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
    var endDate   = s_d($("#EndDate").val());
    
    $.ajax({
        type: 'POST',
        data: {
            startDate: $("#StartDate").val(),
            AcadYearID: '<?php echo $this->uri->segment(4); ?>',
        },
        url: "<?php echo base_url();?>academic_planner/check_term_prev_date",
        dataType: "JSON",
        success: function(response)
        {
            $(".modal_save_btn").removeAttr('disabled');
            if(response.err != '' || empty(response.err)){
                document.getElementById("sd").innerHTML = response.err;
                $(".modal_save_btn").attr('disabled','disabled');
            }
        }
    });
    
    var start_date_db = "<?php echo $start_date_check; ?>";

    if ( $("#EndDate").val() != '' &&  $("#StartDate").val() != '' && $("#EndDate").val() < $("#StartDate").val())
    {
        document.getElementById("sd").innerHTML = "<?php echo get_phrase('start_date_should_be_less_then_end_date');?>";
        document.getElementById("StartDate").value = "";
    }
    else if( $("#StartDate").val() <  start_date_db )
    {
       document.getElementById("sd").innerHTML = "<?php echo get_phrase('please_select_start_date_within_academic_session');?>";
       document.getElementById("StartDate").value = "";        
    }
    /*
    if ((Date.parse(endDate) < Date.parse(startDate)))
    {
        document.getElementById("sd").innerHTML = "<?php echo get_phrase('start_date_should_be_less_then_end_date');?>";
        document.getElementById("StartDate").value = "";
    }
    else if ((Date.parse(startDate) < Date.parse("<?php echo $start_date_check; ?>"))) 
    {
        document.getElementById("sd").innerHTML = "<?php echo get_phrase('please_select_start_date_within_academic_session');?>";
        document.getElementById("StartDate").value = "";      
    }
    */
    
});
    
$("#EndDate").change(function () {
	document.getElementById("ed").innerHTML = "";
    var startDate = s_d($("#StartDate").val());
    var endDate   = s_d($("#EndDate").val());
    
    
    
    if ((Date.parse(startDate) > Date.parse(endDate))) {
        document.getElementById("ed").innerHTML = "<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>";
        document.getElementById("EndDate").value = "";      
    }
   else if ((Date.parse(endDate) > Date.parse("<?php echo $end_date_check; ?>"))) {
    	
    document.getElementById("ed").innerHTML = "<?php echo get_phrase('please_select_end_date_within_academic_session');?>";
        document.getElementById("EndDate").value = "";    
    }
});
function s_d(date){
var date_ary=date.split("/");
return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0];	
}
</script>
 











