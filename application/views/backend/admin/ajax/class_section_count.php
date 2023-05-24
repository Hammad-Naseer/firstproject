<div class="tab-pane " id="">
    
    <div class="row">
        <?php
        $studuent_str = "select count(s.student_id) as section_count, d.title as department_name , cs.title as section_name, cs.section_id ,c.name    as class_name, c.class_id,d.departments_id from ".get_school_db().".departments d 
        inner join ".get_school_db().".class c on c.departments_id=d.departments_id
        inner join ".get_school_db().".class_section  cs on cs.class_id=c.class_id
        inner join ".get_school_db().".student s on s.section_id=cs.section_id
        inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id=s.academic_year_id 
        where s.student_status in (".student_query_status().") and s.school_id=$d_school_id group by cs.section_id";
        $student_count=$this->db->query($studuent_str)->result_array();
        
        $ary_count=array();
        $class_name=array();

        foreach($student_count as $std_rows)
        {
            $ary_count[$std_rows['class_id']][$std_rows['section_id']]['count']=$std_rows['section_count'];
            $ary_count[$std_rows['class_id']][$std_rows['section_id']]['name']=$std_rows['section_name'];
            @$class_name[$std_rows['class_id']]['count'] += $ary_count[$std_rows['class_id']][$std_rows['section_id']]['count'];
            $class_name[$std_rows['class_id']]['name'] = $std_rows['class_name'];
        }
        
       if(count($ary_count) > 0 || count($class_name) > 0)
       {
	   	
	   
        ?>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="class_chart">
            </div>
        </div>	<!-----------------col 12--------------------->

        <script>

            $( function()
            {

                // Create the chart
                Highcharts.chart( 'class_chart', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: ''
                    },
                    subtitle: {
                        text: ''
                    },
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}: {point.y}'
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                    },
                    series: [

                        {
                            name: "<?php echo get_phrase('classes');?>",
                            colorByPoint: true,
                            data: [
                                <?php
                                foreach($class_name  as $key=>$val)
                                {
                                ?>
                                {
                                    name: '<?php echo $val["name"]; ?>',
                                    y: <?php echo $val["count"]; ?>,
                                    drilldown: '<?php echo  $val["name"]; ?>'
                                },
                                <?php
                                }
                                ?>

                            ]
                        }
                    ],
                    drilldown: {
                        series: [

                            <?php

                            foreach($class_name  as $key=>$val){


                            ?>

                            {
                                name: '<?php echo $val["name"]; ?>',
                                id: '<?php echo  $val["name"]; ?>',
                                data: [

                                    <?php
                                    foreach($ary_count[$key] as $key1=>$val1){



                                    ?>

                                    [ '<?php echo $ary_count[$key][$key1]["name"]; ?>', <?php echo $ary_count[$key][$key1]["count"]; ?> ],

                                    <?php } ?>



                                ]
                            },


                            <?php   } ?>



                        ]
                    }
                } );
            } );
        </script>
<?php
}
else
{
	echo get_phrase("no_record_found");
}

?>
    </div>
</div>  