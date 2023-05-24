			<div class="col-lg-12">
					<div id="teacher_attan" style="width:100% !important;"></div>

</div>

<?php


$teaching_ps = array( get_phrase('female') => 0, get_phrase('male') => 0 );
$teaching_ab = array( get_phrase('female') => 0, get_phrase('male') => 0 );
$teaching_le = array( get_phrase('female') => 0, get_phrase('male') => 0 );

$tea_pre_count = $this->db->query( "select
  s.gender as gender,
   count(s.designation_id) as present from " . get_school_db() . ".designation d 


inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id 


inner join " . get_school_db() . ".attendance_staff at on s.staff_id=at.staff_id 


where d.is_teacher=1 and d.school_id=$d_school_id and at.date='$date_value' and at.status=1 group by s.gender
 " )->result_array();


				//echo $this->db->last_query();
				foreach ( $tea_pre_count as $tea_pre__row ) {

					if ( $tea_pre__row[ 'present' ] != "" || $tea_pre__row[ 'present' ] != NULL ) {

						$teaching_ps[ $tea_pre__row[ 'gender' ] ] = $tea_pre__row[ 'present' ];

					}

				}



	
	
	$tea_le_count = $this->db->query( "select
  s.gender as gender,
   count(s.designation_id) as leav from " . get_school_db() . ".designation d 


inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id 


inner join " . get_school_db() . ".attendance_staff at on s.staff_id=at.staff_id 


where d.is_teacher=1 and d.school_id=$d_school_id and at.date='$date_value' and at.status=3 group by s.gender
 " )->result_array();


				//echo $this->db->last_query();
				foreach ( $tea_le_count as $tea_le__row ) {

					if ( $tea_le__row[ 'leav' ] != "" || $tea_le__row[ 'leav' ] != NULL ) {

						$teaching_le[ $tea_le__row[ 'gender' ] ] = $tea_le__row[ 'leav' ];

					}

				}

	
	
	
	
		


$tea_abs_count = $this->db->query( "select s.gender as gender, count(s.designation_id) as absent from " . get_school_db() . ".designation d 
inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id 
inner join " . get_school_db() . ".attendance_staff at on s.staff_id=at.staff_id 
where d.is_teacher=1 and d.school_id=$d_school_id and at.date='$date_value' and at.status=2 group by s.gender
 " )->result_array();

foreach ( $tea_abs_count as $tea_abs_row ) {
if ( $tea_abs_row[ 'absent' ] != "" || $tea_abs_row[ 'absent' ] != NULL ) {
						$teaching_ab[ $tea_abs_row[ 'gender' ] ] = $tea_abs_row[ 'absent' ];
					}
					//echo "Total female Staff :".$teaching_ar['female']."<br>";
					//echo "Total male Staff :".$teaching_ar['male']."<br>";
					//echo "Total Staff: ".($teaching_ar['female']+$teaching_ar['male']);
				}


$nonteaching_ab = array( get_phrase('female') => 0, get_phrase('male') => 0 );
$nonteaching_ps = array( get_phrase('female') => 0, get_phrase('male') => 0 );
$nonteaching_le = array( get_phrase('female') => 0, get_phrase('male') => 0 );


$nont_pre_count = $this->db->query( " select
  s.gender as gender,
   count(s.designation_id) as absent from " . get_school_db() . ".designation d 


inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id 


inner join " . get_school_db() . ".attendance_staff at on s.staff_id=at.staff_id 


where d.is_teacher=0 and d.school_id=$d_school_id and at.date='$date_value' and at.status=2 group by s.gender
 " )->result_array();




				foreach ( $nont_pre_count as $tea_abs_row ) {

					if ( $tea_abs_row[ 'absent' ] != "" || $tea_abs_row[ 'absent' ] != NULL ) {

						$nonteaching_ab[ $tea_abs_row[ 'gender' ] ] = $tea_abs_row[ 'absent' ];

					}

				}
$nont_le_count = $this->db->query( " select
  s.gender as gender,
   count(s.designation_id) as leav from " . get_school_db() . ".designation d 


inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id 


inner join " . get_school_db() . ".attendance_staff at on s.staff_id=at.staff_id 


where d.is_teacher=0 and d.school_id=$d_school_id and at.date='$date_value' and at.status=3 group by s.gender
 " )->result_array();




				foreach ( $nont_le_count as $tea_le_row ) {

					if ( $tea_le_row[ 'leav' ] != "" || $tea_le_row[ 'leav' ] != NULL ) {

						$nonteaching_le[ $tea_le_row[ 'gender' ] ] = $tea_le_row[ 'leav' ];

					}

				}



$non_pre_count = $this->db->query( "select
  s.gender as gender,
   count(s.designation_id) as present from " . get_school_db() . ".designation d 


inner join " . get_school_db() . ".staff s on s.designation_id=d.designation_id 


inner join " . get_school_db() . ".attendance_staff at on s.staff_id=at.staff_id 


where d.is_teacher=0 and d.school_id=$d_school_id and at.date='$date_value' and at.status=1 group by s.gender
 " )->result_array();




				foreach ( $non_pre_count as $te_abs_row ) {

					if ( $te_abs_row[ 'present' ] != "" || $te_abs_row[ 'present' ] != NULL ) {

$nonteaching_ps[ $te_abs_row[ 'gender' ] ] = $te_abs_row[ 'present' ];

					}
				}


if(count($tea_pre_count) > 0 || count($tea_le_count) > 0 || count($tea_abs_count) > 0 || count($nont_pre_count) > 0 || count($nont_le_count) > 0 || count($non_pre_count) > 0)
{
 ?>





	<script>
		$( function () {

			var colors = Highcharts.getOptions().colors,
				categories = [ "<?php echo get_phrase('present_staff');?>", "<?php echo get_phrase('absent_staff');?>","<?php echo get_phrase('leave_staff');?>" ],
				data = [ {
					y: <?php echo ($teaching_ps['male']+$teaching_ps['female']+$nonteaching_ps['male']+$nonteaching_ps['female']); ?>,
					color: colors[ 0 ],
					drilldown: {
						name: "<?php echo get_phrase('present_staff');?>",
						categories: [ "<?php echo get_phrase('present_teaching_staff');?>", "<?php echo get_phrase('present_non_teaching_staff');?>"],
						data: [ <?php echo ($teaching_ps['male']+$teaching_ps['female']); ?>, 
						
						<?php echo ($nonteaching_ps['male']+$nonteaching_ps['female']); ?>
						],
						color: colors[ 0 ]
					}
				}, {
					y: <?php echo ($teaching_ab['male']+$teaching_ab['female']+$nonteaching_ab['male']+$nonteaching_ab['female']); ?>,
					color: colors[ 1 ],
					drilldown: {
						name: "<?php echo get_phrase('absent_staff');?>",
						categories: [ "<?php echo get_phrase('absent_teaching_staff');?>", "<?php echo get_phrase('absent_non_teaching_staff');?>" ],
						data: [ 
<?php echo ($teaching_ab['male']+$teaching_ab['female']); ?>, <?php echo ($nonteaching_ab['male']+$nonteaching_ab['female']);?>
],

						color: colors[ 1 ]
					}
				}, {
					y: <?php echo ($teaching_le['female']+$teaching_le['male']+$nonteaching_le['female']+$nonteaching_le['male']); ?>,
					color: colors[ 2 ],
					drilldown: {
						name: "<?php echo get_phrase('staff_leave');?>",
						categories: [ "<?php echo get_phrase('teaching_staff_leave');?>", "<?php echo get_phrase('non_teaching_staff_leave');?>"],
						data: [ 
<?php echo ($teaching_le['male']+$teaching_le['female']); ?>, <?php echo ($nonteaching_le['male']+$nonteaching_le['female']);?>
],
						color: colors[ 2 ]
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
			Highcharts.chart( 'teacher_attan', {
				chart: {
					type: 'pie'
				},
				title: {
					text: '<?php  echo date("d-M-Y",strtotime($date_value)); ?>'
				},
				subtitle: {
					text: ''
				},
				yAxis: {
					title: {
						text: ''
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
}
else
{
	echo get_phrase("no_record_found");
}


			
			
		
