<?php
    $school_id = $_SESSION[ 'school_id' ];
    $section_id = $this->uri->segment( 5 );
    if ( $param2 == "add" ) {
    	$title = 'Add Chalan Form';
    }
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title black2" style="width:100%;">
					<?php echo $title;?>
				</div>
			</div>
			<div class="panel-body">
				<!--<form class="form-horizontal form-groups-bordered validate" id="sub_formmmmm">-->
				<?php echo form_open(base_url().'class_chalan_form/class_chalan_f/add/' , array('class' => 'form-horizontal form-groups-bordered validate','id'=>'btn_disable', 'enctype' => 'multipart/form-data'));?>
                <input type="hidden" name="section_id" value="<?php echo $section_id;?>">
				<div class="form-group">
					<label for="field-1" class="control-label">
						<?php echo get_phrase('class');?>
                        /
                        <?php echo get_phrase('section');?>
					</label>
                    <?php $section_hierarchy = section_hierarchy($section_id); ?>
					<ul class="breadcrumb breadcrumb2">
						<li>
							<?php echo $section_hierarchy['d'];?>
						</li>
						<li><?php echo $section_hierarchy['c'];?></li>
						<li><?php echo $section_hierarchy['s'];?></li>
					</ul>
				</div>
				<div class="form-group">
					<label for="field-2" class="control-label">
						<?php echo get_phrase('challan_form_type');?> <span class="star">*</span>
					</label>
						<?php echo class_chalan_type("type",$sel_class="form-control","",$sel_id="challan_form_type"); ?>
				</div>
				<div class="form-group">
					<label for="field-1" class="control-label">
						<?php echo get_phrase('challan_form_title');?> <span class="star">*</span>
					</label>
					<input required maxlength="300" type="text" class="form-control" name="title" id="title" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
				</div>
				<div class="form-group">
					<label for="field-1" class="control-label">
						<?php echo get_phrase('due_days');?> <span class="star">*</span>
					</label>
					<input required maxlength="2" type="number" class="form-control" name="due_days" id="due_days" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>">
				</div>
				<div class="form-group row" style="margin-left: -30px;margin-left: -30px;">
					<div class="col-md-8">
					    <label for="field-1" class="control-label">
    						<?php echo get_phrase('late_fee_fine');?> 
    					</label>
    					<input required maxlength="4" type="number" class="form-control" name="late_fee_fine" id="late_fee_fine" value="" data-validate="required">
					</div>
					<div class="col-md-4">
					    <label for="field-1" class="control-label">
    						<?php echo get_phrase('fine_type');?>
    					</label>
    					<select class="form-control" name="late_fee_type">
    					    <option value="1">Percentage</option>
    					    <option value="2">Amount</option>
    					</select>
					</div>
				</div>
				<?php
				/*

				<div class="form-group">
					<label for="field-2" class="col-sm-3 control-label">
						<?php echo get_phrase('status');?> <span class="star">*</span>
					</label>

					<div class="col-sm-8">
						<?php echo status("status","form-control","","status"); ?>
					</div>
				</div>
				*/
				?>
				<div class="form-group">
					<label for="field-2" class="control-label">
						<?php echo get_phrase('version');?>
					</label>
					<input maxlength="300" type="text" class="form-control" name="version" id="version" value="">
				</div>
				<input type="hidden" class="form-control" name="parent_c_c_f_id" id="parent_c_c_f_id" value="">
				<input type="hidden" class="form-control" name="previous_c_c_f_id" id="previous_c_c_f_id" value="">
				<div class="form-group">
					<label for="field-2" class="control-label">
						<?php echo get_phrase('detail');?>
					</label>
					<textarea maxlength="1000" id="detail" oninput="count_value('detail','detail_count','1000')" name="detail" class="form-control"></textarea>
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
				<div class="form-group text-center">
				    <p id="notes" style="color: red;"></p>
				</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>

<script>
	$( document ).ready( function () {
		//$('.selectpicker').selectpicker();
		$( '.selectpicker' ).on( 'change', function () {
			var id = $( this ).attr( 'id');
			var selected = $( '#' + id + ' :selected');
			var group = selected.parent().attr( 'label');
			$( '#' + id + '_selection' ).text( group );
		} );


		$( "#departments_id_add" ).change( function () {


			var dep_id = $( this ).val();
			$( "#icon" ).remove();
			$( this ).after( '<div id="icon" class="loader_small"></div>');

			$.ajax( {
				type: 'POST',
				data: {
					department_id: dep_id
				},
				url: "<?php echo base_url();?>class_chalan_form/get_class",
				dataType: "html",
				success: function ( response ) {
					$( "#icon" ).remove();
					$( "#class_id_add" ).html( response );




					$( "#section_id_add" ).html( '<select><option><?php echo get_phrase('class_section'); ?></option></select>');
				}
			} );
		} );

		$( "#class_id_add" ).change( function () {
			var class_id = $( this ).val();
			$( "#icon" ).remove();
			$( this ).after( '<div id="icon" class="loader_small"></div>');
			$.ajax( {
				type: 'POST',
				data: {
					class_id: class_id
				},
				url: "<?php echo base_url();?>class_chalan_form/get_class_section",
				dataType: "html",
				success: function ( response ) {
					$( "#icon" ).remove();
					$( "#section_id_add" ).html( response );
				}
			} );



		} );

		$('#challan_form_type').change(function(event){
			var chalan_form_id = $(this).val();
			event.preventDefault();
			$.ajax( {
				type: 'POST',
				data: {
					chalan_form_id : chalan_form_id,
					section_id : <?php echo $section_id;?>
				},
				url: "<?php echo base_url();?>class_chalan_form/get_challan_form_details",
				dataType: "json",
				success: function (response) {
					//alert(jQuery.type(response));
					//alert(jQuery.isEmptyObject({}));

					if (response.length==0)
					{
						$("#notes").html("");
					}
					else
					{
						 $( "#title" ).val(response.title);
						 $( "#due_days" ).val(response.due_days);
						 // $( "#status" ).val(response.status);
						 $( "#detail" ).val(response.detail);
						 $( "#version" ).val(response.version);
						 $( "#parent_c_c_f_id" ).val(response.parent_c_c_f_id);
						 $( "#previous_c_c_f_id" ).val(response.c_c_f_id);
						 $("#notes").html('<?php echo get_phrase("a_new_record_will_be_added_and_prevoius_record_will_be_archived");?>');
					}
					if($.isArray(response))
					{
						$( "#title" ).val("");
						$( "#due_days" ).val("");
						$( "#detail" ).val("");
						$( "#version" ).val("");
						$( "#parent_c_c_f_id" ).val("");
						$( "#previous_c_c_f_id" ).val("");
					}
				}
			});
		});
	} );
</script>


<style>
	.loader {
		border: 16px solid #f3f3f3;
		/* Light grey */
		border-top: 16px solid #63b7e7;
		/* Blue */
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
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
</style>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>