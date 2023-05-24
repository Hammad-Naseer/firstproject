<div class="panel panel-primary" data-collapsed="0">
	<div class="panel-heading">
		<div class="panel-title">
			<i class="entypo-plus-circled"></i>
			<?php echo get_phrase('add_exam');?>
		</div>
	</div>
	<div class="panel-body">
	    <?php echo form_open(base_url().'exams/exam/create' , array('id'=>'exam_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
	        <div class="form-group">
		<label class="control-label">
			<?php echo get_phrase('academic_year');?><span class="red"> * </span>
		</label>
		<label id="term_id1_selection" style="font-size:11px;"></label>
		<select id="term_id1" name="term_id" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
			<?php
			    $status_year=array(1,3);
   			    $status_term=array(1);  
			    echo yearly_term_selector('',$status_year,$status_term);
			?>
		</select>
	</div>
        	<div class="form-group">
        		<label class="control-label">
        			<?php echo get_phrase('name');?><span class="red"> * </span>
        		</label>
        		<input type="text" class="form-control" maxlength="100" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
        	</div>
        	<div class="form-group">
        		<label class="control-label">
        			<?php echo get_phrase('start_date');?><span class="red"> * </span>
        		</label>
        		<input type="date" class="form-control" name="start_date" id="start_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  data-format="dd/mm/yyyy">
        		<div id="error_start"></div>
        	</div>
        	<div class="form-group">
        		<label class="control-label">
        			<?php echo get_phrase('end_date');?><span class="red"> * </span>
        		</label>
        			<input type="date" class="form-control" name="end_date" id="end_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"   data-format="dd/mm/yyyy">
        			<div id="error_end"></div>
        	</div>
        	<div class="form-group">
        		<label class="control-label">
        			<?php echo get_phrase('comment');?>
        		</label>
        		<input type="text" class="form-control" name="comment" maxlength="100"/>
        	</div>
        	<div class="form-group">
        		<div class="float-right">
        			<button type="submit" class="modal_save_btn">
        				<?php echo get_phrase('save');?>
        			</button>
        			<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
        				<?php echo get_phrase('cancel');?>
        			</button>
        		</div>
        	</div>
	    </form>
    </div>    
</div>

<script>
	$( document ).ready( function () {
		//$('#example').DataTable();

    	//$('.selectpicker').selectpicker();
		$( '.selectpicker' ).on( 'change', function () {
			var id = $( this ).attr( 'id');
			var selected = $( '#' + id + ' :selected');
			var group = selected.parent().attr( 'label');
			$( '#' + id + '_selection' ).text( group );
		} );
		$( '#vacation-list' ).load( "<?php echo base_url(); ?>vacation/get_vacation_list" );
		$( '#academic_id1' ).on( 'change', function () {

			var academic_year = $( this ).val();
			//alert(academic_year)
			if ( academic_year == '' ) {
				$( '#term_id1' ).html( '<select><option><?php echo get_phrase('select_term'); ?></option></select>');
			}
			$.ajax( {
				type: "POST",
				url: "<?php echo base_url(); ?>exams/get_terms",

				data: ( {
					academic_year: academic_year,
					status: 1
				} ),
				dataType: "html",
				success: function ( html ) {
					//console.log(html);
					if ( html != '' ) {
						$( '#term_id1' ).html( html );


					}

				}


			} );

		} );
		$( '#start_date' ).on( 'change', function () {
			//$( '#submit' ).removeAttr( 'disabled', 'true');
			$( '#error_start' ).text( '');
			var start_date = $( this ).val();
			var term_id = $( '#term_id1' ).val();
			if ( start_date != '' ) {
				$.ajax( {
					type: "POST",
					url: "<?php echo base_url(); ?>exams/term_date_range",

					data: ( {
						start_date: start_date,
						term_id: term_id
					} ),
					dataType: "html",
					success: function ( html ) {
						if ( html == 0 ) {
							$( '#error_start' ).text( '<?php echo get_phrase('start_date_should_be_between_term_dates'); ?>');
							//$( '#submit' ).attr( 'disabled', 'true');
						}
					}
				} );
			}

		} );
		$( '#end_date' ).on( 'change', function () {
			$( '#submit' ).removeAttr( 'disabled', 'true');
			$( '#error_end' ).text( '');
			var end_date = $( this ).val();
			var term_id = $( '#term_id1' ).val();
			if ( end_date != '' ) {
				$.ajax( {
					type: "POST",
					url: "<?php echo base_url(); ?>exams/term_date_range",

					data: ( {
						end_date: end_date,
						term_id: term_id
					} ),
					dataType: "html",
					success: function ( html ) {
						if ( html == 0 ) {
							$( '#error_end' ).text( '<?php echo get_phrase('end_date_should_be_between_term_dates'); ?>');
							$( '#submit' ).attr( 'disabled', 'true');
						}
					}
				} );
			}

		} );

		$( '#select' ).click( function () {
			var academic_id = $( '#academic_id' ).val();
			var term_id = $( '#term_id1' ).val();

			if ( academic_id != '' ) {


				$.ajax( {
					type: "POST",
					url: "<?php echo base_url(); ?>vacation/get_vacation_list",

					data: ( {
						academic_year: academic_id,
						term_id: term_id
					} ),
					dataType: "html",
					success: function ( html ) {
						console.log( html );
						if ( html != '' ) {
							$( '#vacation-list' ).html( html );


						} else {
							$( '#vacation-list' ).text( 'No records found');
						}

					}


				} );

			}
		} );
		$( '#term_id1' ).change( function () {


			var term_id = $( this ).val();
			var start_date = $( '#start_date' ).val();
			var end_date = $( '#end_date' ).val();
			if ( end_date != '' ) {
				$.ajax( {
					type: "POST",
					url: "<?php echo base_url(); ?>exams/term_date_range",

					data: ( {
						end_date: end_date,
						term_id: term_id
					} ),
					dataType: "html",
					success: function ( html ) {
						if ( html == 0 ) {
							$( '#error_end' ).text( '<?php echo get_phrase('end_date_should_be_between_term_dates'); ?>');
							$( '#submit' ).attr( 'disabled', 'true');
						} else {
							$( '#submit' ).removeAttr( 'disabled');
						}
					}
				} );
			}
			if ( start_date != '' ) {
				$.ajax( {
					type: "POST",
					url: "<?php echo base_url(); ?>exams/term_date_range",

					data: ( {
						start_date: start_date,
						term_id: term_id
					} ),
					dataType: "html",
					success: function ( html ) {
						if ( html == 0 ) {
							$( '#error_start' ).text( '<?php echo get_phrase('start_date_should_be_between_term_range'); ?>');
							$( '#submit' ).attr( 'disabled', 'true');
						} else {
							$( '#submit' ).removeAttr( 'disabled');
						}
					}
				} );
			}

		} );

		$( "#end_date" ).change( function () {
			$( '#submit' ).removeAttr( 'disabled', 'true');
			var startDate = document.getElementById( "start_date" ).value;
			var endDate = document.getElementById( "end_date" ).value;

			if ( ( Date.parse( startDate ) > Date.parse( endDate ) ) ) {
				$( '#error_end' ).text( "<?php echo get_phrase('end_date_should_be_between_term_dates'); ?>" );
				$( '#submit' ).attr( 'disabled', 'true');
				// document.getElementById("end_date").value = "";


			}
		} );


		$( "#end_date" ).change( function () {
			$( '#submit' ).removeAttr( 'disabled', 'true');
			var startDate = document.getElementById( "start_date" ).value;
			var endDate = document.getElementById( "end_date" ).value;

			if ( ( Date.parse( endDate ) < Date.parse( startDate ) ) ) {
				$( '#error_end' ).text( "<?php echo get_phrase('end_date_should_be_greater_than_start_date'); ?>" );
				$( '#submit' ).attr( 'disabled', 'true');
				// document.getElementById("end_date").value = "";


			}
		} );




		$( "#start_date" ).change( function () {
			$( '#submit' ).removeAttr( 'disabled', 'true');
			var startDate = document.getElementById( "start_date" ).value;
			var endDate = document.getElementById( "end_date" ).value;

			if ( ( Date.parse( endDate ) < Date.parse( startDate ) ) ) {
				$( '#error_start' ).text("<?php echo get_phrase('start_date_should_be_less_then_end_ date'); ?> " );
				$( '#submit' ).attr( 'disabled', 'true');
				//document.getElementById("start_date").value = "";

			}
		} );


		$( "#start_date" ).change( function () {
			$( '#submit' ).removeAttr( 'disabled', 'true');
			var startDate = document.getElementById( "start_date" ).value;
			var endDate = document.getElementById( "end_date" ).value;

			if ( ( Date.parse( startDate ) > Date.parse( endDate ) ) ) {
				$( '#error_start' ).text("<?php echo get_phrase('start_date_should_be_less_then_end_date'); ?>");
				$( '#submit' ).attr( 'disabled', 'true');
				//document.getElementById("start_date").value = "";

			}
		} );








	} );


	function edit_data( id )
    {
		$( '#span_cross' ).remove();
		$( "#example tr" ).find( "td" ).each( function ( index ) {
			var total_val = $( this ).html();
			var ind = index;


			if ( ind == 1 || ind == 2 )
			{
				var arr = total_val.split( '-');
				//alert(arr); 	
				total_val = arr[ 1 ] + '/' + arr[ 2 ] + '/' + arr[ 0 ];
			}

			$( '#val' + ind ).val( total_val );


		} );

		$( '#holiday_id' ).val( id );

		$( '#btnn_edit' ).after( '<span onclick="cross_edit()" id="span_cross" class="glyphicon glyphicon-remove"></span>');
		$( '#btnn_edit' ).val( "Edit Vacation" );



	}

	function cross_edit() {

		$( '#span_cross' ).remove();
		$( '#val0' ).val( "" );
		$( '#val1' ).val( "" );
		$( '#val2' ).val( "" );
		$( '#holiday_id' ).val( "" );
		$( '#btnn_edit' ).val( "Add Vacation" );

	}
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>