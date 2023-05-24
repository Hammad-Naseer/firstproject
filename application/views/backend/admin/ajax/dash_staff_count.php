<?php

                "select count(s.designation_id) as tec_cout ,s.gender as gender from " . get_school_db() . ".designation d inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id where d.is_teacher=1 and d.school_id=$d_school_id group by s.gender";
				$teacher_count = $this->db->query( "select count(s.designation_id) as tec_cout ,s.gender as gender from " . get_school_db() . ".designation d inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id where d.is_teacher=1 and d.school_id=$d_school_id group by s.gender " )->result_array();

				$nontec_count = $this->db->query( "select count(s.designation_id) as nontec_cout ,s.gender as gender from " . get_school_db() . ".designation d inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id where d.is_teacher=0 and d.school_id=$d_school_id group by s.gender " )->result_array();


				$teaching_ar = array( 'female' => 0, 'male' => 0 );
				$notec_ar = array( 'female' => 0, 'male' => 0 );
				if(count($teacher_count)>0 || count($nontec_count) > 0)
				{
					
    				foreach ( $teacher_count as $tec_row ) {
    					if ( $tec_row[ 'tec_cout' ] != "" || $tec_row[ 'tec_cout' ] != NULL ) {
    
    						$teaching_ar[ $tec_row[ 'gender' ] ] = $tec_row[ 'tec_cout' ];
    					}
    				}

    				foreach ( $nontec_count as $nontec_row ) {
    					if ( $tec_row[ 'tec_cout' ] != "" || $tec_row[ 'nontec_cout' ] != NULL ) {
    						$notec_ar[ $nontec_row[ 'gender' ] ] = $nontec_row[ 'nontec_cout' ];
    					}
    				}
				?>

				<div class="col-lg-12">
					<div id="teacher_count" style="width:100% !important;"></div>
				</div>


	            <script>
		$( function () {

			var colors = Highcharts.getOptions().colors,
				categories = [ '<?php echo get_phrase("male_staff");?>', '<?php echo get_phrase("female_staff");?>' ],
				data = [ {
					y: <?php echo ($teaching_ar['male']+$notec_ar['male']); ?>,
					color: colors[ 0 ],
					drilldown: {
						name: '<?php echo get_phrase("male_staff_details");?>',
						categories: [ '<?php echo get_phrase("male_teaching_staff");?>', '<?php echo get_phrase("male_non_teaching_staff");?>' ],
						data: [ <?php echo $teaching_ar['male']; ?>, <?php echo $notec_ar['male']; ?> ],
						color: colors[ 0 ]
					}
				}, {
					y: <?php echo ($teaching_ar['female']+$notec_ar['female']); ?>,
					color: colors[ 1 ],
					drilldown: {
						name: '<?php echo get_phrase("female_staff_details");?>',
						categories: [ "<?php echo get_phrase('female_teaching_staff');?>", "<?php echo get_phrase('female_non_teaching_staff');?>" ],
						data: [ <?php echo $teaching_ar['female']; ?>, <?php echo $notec_ar['female']; ?> ],
						color: colors[ 1 ]
					}
				} ],
				browserData = [],
				versionsData = [],
				i,
				j,
				dataLen = data.length,
				drillDataLen,
				brightness;


			// Build the data arrays
			for ( i = 0; i < dataLen; i += 1 ) {

				// add browser data
				browserData.push( {
					name: categories[ i ],
					y: data[ i ].y,
					color: data[ i ].color
				} );

				// add version data
				drillDataLen = data[ i ].drilldown.data.length;
				for ( j = 0; j < drillDataLen; j += 1 ) {
					brightness = 0.2 - ( j / drillDataLen ) / 5;
					versionsData.push( {
						name: data[ i ].drilldown.categories[ j ],
						y: data[ i ].drilldown.data[ j ],
						color: Highcharts.Color( data[ i ].color ).brighten( brightness ).get()
					} );
				}
			}

			// Create the chart
			Highcharts.chart( 'teacher_count', {
				chart: {
					type: 'pie'			},
				title: {
					text: ''
				},
				subtitle: {
					text: ''
				},
				yAxis: {
					title: {
						text: "<?php echo get_phrase('total_percent_market_share');?>"
					}
				},
				plotOptions: {
					pie: {
						shadow: false,
						center: [ '50%', '50%' ]
					}
				},
				tooltip: {
					valueSuffix: ''
				},
				series: [ {
					name: "<?php echo get_phrase('total');?>",
					data: browserData,
					size: '60%',
					dataLabels: {
						formatter: function () {
							return this.y > 5 ? this.point.name : null;
						},
						color: '#ffffff',
						distance: -30
					}
				}, {
					name: "<?php echo get_phrase('total');?>",
					data: versionsData,
					size: '80%',
					innerSize: '60%',
					dataLabels: {
						formatter: function () {
							// display only if larger than 1
							return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '' : null;
						}
					}
				} ]
			} );
		} );
	</script>
<?php
    }else{
    	echo get_phrase("no_record_found");
    }
