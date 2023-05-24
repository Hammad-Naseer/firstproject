<?php 

$school_id=$_SESSION['school_id'];
$section_id = $this->uri->segment(6);


if($param2=="add"){
	$title = 'Add Chalan Form';
}else{

$edit_data=$this->db->get_where(get_school_db().'.class_chalan_form' , array('c_c_f_id' => $param3,'school_id'=>$school_id))->result_array();

$title = 'Add Chalan Form';
if($param3>0){
	$title = 'Edit Chalan Form';
}

}
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" style="width:100%">
					<?php echo get_phrase($title); ?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'class_chalan_form/class_chalan_f/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate','id'=>'btn_disable', 'enctype' => 'multipart/form-data'));?>
                <input type="hidden" name="section_id" value="<?php echo $section_id;?>">  
                <div class="form-group">
					<label for="field-1" class="control-label">
						<?php echo get_phrase('class');?>
                        /
                        <?php echo get_phrase('section');?>
					</label>
					<?php $section_hierarchy = section_hierarchy($section_id);?>
					<ul class="breadcrumb breadcrumb2">
						<li>
							<?php echo $section_hierarchy['d'];?>
						</li>
						<li><?php echo $section_hierarchy['c'];?></li>
						<li><?php echo $section_hierarchy['s'];?></li>
					</ul>
				</div>  
				<div class="form-group">
					<label for="field-2" class="control-label"><?php echo get_phrase('challan_form_type ');?> <span class="star">*</span></label>
					<h5><?php echo get_class_chalan_type($edit_data[0]['type']); ?></h5>
				</div>             
                <div class="form-group">
					<label for="field-1" class="control-label"><?php echo get_phrase('challan_form_title');?> <span class="star">*</span></label> 
					<input required maxlength="300" type="text" class="form-control" name="title" id="title"  value="<?php echo $edit_data[0]['title'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
					<input name="c_c_f_id" id="c_c_f_id" type="hidden" value="<?php echo $edit_data[0]['c_c_f_id'];  ?>">
				</div>
                <div class="form-group">
				    <label for="field-1" class="control-label"><?php echo get_phrase('due_days');?> <span class="star">*</span></label>
					<input required maxlength="2" type="number" class="form-control" name="due_days" id="due_days"  value="<?php echo $edit_data[0]['due_days'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
				</div>
				<div class="form-group row" style="margin-left: -30px;margin-left: -30px;">
					<div class="col-md-8">
					    <label for="field-1" class="control-label">
    						<?php echo get_phrase('late_fee_fine');?> 
    					</label>
    					<input required maxlength="4" type="number" class="form-control" name="late_fee_fine" id="late_fee_fine" value="<?php echo $edit_data[0]['late_fee_fine'];?>" data-validate="required">
					</div>
					<div class="col-md-4">
					    <label for="field-1" class="control-label">
    						<?php echo get_phrase('fine_type');?>
    					</label>
    					<select class="form-control" name="late_fee_type" id="late_fee_type">
    					    <option value="1">Percentage</option>
    					    <option value="2">Amount</option>
    					</select>
    					<script>
    					    $('#late_fee_type').val('<?= $edit_data[0]['late_fee_type'] ?>');
    					</script>
					</div>
				</div>
				<div class="form-group">
					<label for="field-2" class="control-label">
					<?php echo get_phrase('version');?></label>
					<input maxlength="300" type="text" class="form-control" name="version" id="version" value="<?php echo $edit_data[0]['version'];?>">
				</div>

					<?php
					/*
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('status');?> <span class="star">*</span></label>
                        
						<div class="col-sm-8">
						<?php
						echo status("status","form-control",$edit_data[0]['status'],"status");
						 ?>
						</div> 
					</div>
					*/
					?>
					<div class="form-group">
						<label for="field-2" class="control-label"><?php echo get_phrase('detail');?></label>
						<textarea maxlength="1000" id="detail" oninput="count_value('detail','detail_count','1000')" name="detail" class="form-control"><?php echo $edit_data[0]['detail'];  ?></textarea>
						<div class="col-lg-12" id="detail_count"></div>
					</div>
					<div class="form-group">
						<div class="float-right">
        					<button type="submit" class="modal_save_btn" id="btn_sss">
        						<?php echo get_phrase('save');?>
        					</button>
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

$(document).ready(function(){
	//$('.selectpicker').selectpicker();
	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    });

	
$("#departments_id_add").change(function(){
	
	
	var dep_id=$(this).val();
	$("#icon").remove();
$(this).after('<div id="icon" class="loader_small"></div>');

$.ajax({
			type: 'POST',
			data: {department_id:dep_id},
	url: "<?php echo base_url();?>class_chalan_form/get_class",
			dataType: "html",
			success: function(response) {			
				$("#icon").remove();	
			$("#class_id_add").html(response);
			
			
			
			
			$("#section_id_add").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');		
				 }
		});
});	

$("#class_id_add").change(function(){
	var class_id=$(this).val();
	$("#icon").remove();
	$(this).after('<div id="icon" class="loader_small"></div>');
	$.ajax({
			type: 'POST',
			data: {class_id:class_id},
	url: "<?php echo base_url();?>class_chalan_form/get_class_section",
			dataType: "html",
			success: function(response) {
				$("#icon").remove();
			$("#section_id_add").html(response);
			 }
		});
	
	
	
});	

/*$('#btn_sss').click(function(e){
	
	e.preventDefault();
	var c_c_f_id=$('#c_c_f_id').val();
	var title=$('#title').val();
	var section_id=$('#section_id_add').val();
	var type=$('#type').val();
	var due_days=$('#due_days').val();
	var status=$('#status').val();
	var detail=$('#detail').val();
	
	if(c_c_f_id!="" && c_c_f_id > 0)
	{
	 $.ajax({
                    type: 'POST',
                    data: {
                        c_c_f_id: c_c_f_id,
                        title:title,
                        section_id:section_id,
                        type:type,
                        due_days:due_days,
                        status:status,
                        detail:detail
                    },
                    url: "<?php echo base_url(); ?>class_chalan_form/class_chalan_f/add_edit/",

                    dataType: "html",
                    success: function(response) {
                        //alert(response);
                    $('#modal_ajax').modal('hide');
                       //$('#collapse' + c_c_f_id).html(response);
                    }
                });
	}
	else
	{
		$.ajax({
                    type: 'POST',
                    data: {
                        title:title,
                        section_id:section_id,
                        type:type,
                        due_days:due_days,
                        status:status,
                        detail:detail
                    },
                    url: "<?php echo base_url(); ?>class_chalan_form/class_chalan_f/add_edit/",

                    dataType: "html",
                    success: function(response) {
                        alert(response);
                    //$('#modal_ajax').modal('hide');
                       //$('#collapse' + c_c_f_id).html(response);
                    }
                });
	}
	});*/


	});

</script>


<style>
.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #63b7e7; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

.loader_small {
       border: 7px solid #f3f3f3;
    border-top: 7px solid #63b7e7;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin-right: auto;
    margin-left: auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>