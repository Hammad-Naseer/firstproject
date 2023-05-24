<div class="col-sm-12 col-md-12">
    <div class="box">
        <div class="panel panel-primary">
            <div class="panel-body with-table">
                <div id="container">
                </div>
                <?php

                    $yearly_term_id=$_SESSION['yearly_term_id'];
                    $sub_in = 0;
                    if (@count($time_table_t_sub) > 0)
                    $sub_in = implode(',', $time_table_t_sub);
                    $sec_in = 0;
                    if (@count($time_table_t_sec)>0)
                    $sec_in = implode(',', $time_table_t_sec);
    
                    $exam_arr = array();
                    $subject_arr = array();
                    $exam_result_arr = array();
                    $exam_routine_section_arr = array();
                    $sec_sub_assoc = ' case';
                    /*s.subject_id, group_concat(distinct section_id  SEPARATOR ',') as section_id*/
                    $section_subjects_arr = $this->db->query("select section_id , group_concat(distinct s.subject_id SEPARATOR ',') as subject_id FROM 
                    ".get_school_db().".class_routine cr 
                    inner join ".get_school_db().".class_routine_settings crs on (cr.c_rout_sett_id=crs.c_rout_sett_id and crs.is_active=1)
                    inner join ".get_school_db().".time_table_subject_teacher ttst on ttst.class_routine_id=cr.class_routine_id
                    inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id
                    inner join ".get_school_db().".subject s on st.subject_id=s.subject_id
                    inner join ".get_school_db().".staff on st.teacher_id=staff.staff_id
                    where staff.staff_id = $teacher_id
                    and cr.school_id=$d_school_id
                    group by section_id
                    ")->result_array();
    
                    foreach ($section_subjects_arr as $ssa)
                    {
                        $exam_result = $this->db->query("select e.exam_id, e.name as exam_name, er.section_id, er.subject_id, s.name as subject_name, s.code as subject_code, yt.title as term,c.name as class, cs.title as section_name
                                from ".get_school_db().".exam e 
                                inner join ".get_school_db().".exam_routine er on e.exam_id =  er.exam_id
                                inner join ".get_school_db().".yearly_terms yt on e.yearly_terms_id = yt.yearly_terms_id  
                                inner join ".get_school_db().".subject s on s.subject_id = er.subject_id 
                                inner join ".get_school_db().".class_section cs on cs.section_id = er.section_id
                                inner join ".get_school_db().".class c on c.class_id = cs.class_id  
                                where 
                                er.section_id in (".$ssa['section_id'].")
                                and er.subject_id in (".$ssa['subject_id'].")
                                and yt.academic_year_id = ".$_SESSION['academic_year_id']." 
                                and e.school_id=$d_school_id
                                and er.is_approved=1
                                
                            ")->result_array();
                        if (count($exam_result) > 0)
                        {
                            foreach ($exam_result as $value)
                            {
                                @$exam_result_arr[$value['exam_id']] = $exam_id['exam_name'];
    
                                $exam_routine_section_arr[$value['exam_id']][] = $value['section_id'];
                                $compArr=$this->db->query("select subject_component_id,sc.subject_id,percentage 
                                            from ".get_school_db().".subject_components sc
                                            where 
                                            sc.subject_id=".$value['subject_id']." 
                                            and sc.school_id=$d_school_id
                                            ")->result_array();
                                if(sizeof($compArr)>0)
                                {
                                    $sum=0;
                                    $avg = 0;
                                    foreach($compArr as $comp)
                                    {
                                        $graph_arr = $this->db->query("select marks_obtained 
                                        from ".get_school_db().".marks m 
                                        inner join ".get_school_db().".marks_components mc on m.marks_id = mc.marks_id
                                        inner join ".get_school_db().".student stdnt on stdnt.student_id = m.student_id
                                        where 
                                        mc.subject_component_id=".$comp['subject_component_id']."
                                        and m.subject_id = ".$value['subject_id']."
                                        and m.exam_id = ".$value['exam_id']."
                                        and m.school_id = $d_school_id
                                        and stdnt.section_id=".$value['section_id']." 
                                        and stdnt.academic_year_id=".$_SESSION['academic_year_id']." 
                                        and stdnt.student_status in (".student_query_status().")
                                        ")->result_array();
    
                                        $count = 0;
                                        $sum = 0;
                                        foreach ($graph_arr as $com_inner)
                                        {
                                            $count++;
                                            $sum+=$com_inner['marks_obtained'];
                                        }
                                        $avg += $sum/$count;
                                        /*echo $sum.'/'.$count.'<br>';*/
                                    }
    
                                    /*echo '<br>'.$value['exam_id'].': '.get_section_name($value['section_id']).'-'.get_subject_name($value['subject_id']).'-'.$avg.'<br>';
                                    $exam_arr[$value['exam_id']] = $value['term'].' - '.$value['exam_name'];*/
    
                                    $subject_arr[$value['section_id']][$value['subject_id']]['title'] = $value['subject_name'].' - '.$value['subject_code'].' '.$value['class'].' '.$value['section_name'];
    
                                    $subject_arr[$value['section_id']][$value['subject_id']]['marks'][$value['exam_id']] = $avg;
                                }
                                else
                                {
                                    $graph_arr = $this->db->query("select avg(marks_obtained) as avg_marks
                                        from ".get_school_db().".marks m 
                                        inner join ".get_school_db().".marks_components mc on m.marks_id = mc.marks_id
                                        inner join ".get_school_db().".student stdnt on stdnt.student_id = m.student_id
                                        where 
                                        m.subject_id = ".$value['subject_id']."
                                        and m.exam_id = ".$value['exam_id']."
                                        and m.school_id = $d_school_id
                                        and stdnt.section_id=".$value['section_id']." 
                                        and stdnt.academic_year_id=".$_SESSION['academic_year_id']." 
                                        and stdnt.student_status in (".student_query_status().")
                                        
                                        ")->result_array();
    
                                    foreach ($graph_arr as $key => $inner)
                                    {
                                        $exam_arr[$value['exam_id']] = $value['term'].' - '.$value['exam_name'];
    
                                        $subject_arr[$value['section_id']][$value['subject_id']]['title'] = $value['subject_name'].' - '.$value['subject_code'].' '.$value['class'].' '.$value['section_name'];
    
                                        $subject_arr[$value['section_id']][$value['subject_id']]['marks'][$value['exam_id']] = $inner['avg_marks'];
                                    }
                                }
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </div>
<?php
if(count($subject_arr) > 0)
{?>
    <script>
    $(function() {
        $('#container').highcharts({
            title: {
                text: '',
                x: -20 //center
            },
            subtitle: {
                text: "",
                x: -20
            },
            xAxis: {
                categories: [<?php echo "'".implode("','", $exam_arr)."'"; ?>] //, 'Final Term'
            },
            yAxis: {
                title: {
                    text: "<?php echo get_phrase('marks_obtained');?>"
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: "<?php echo get_phrase('marks');?>"
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 0
            },
            series: [
                <?php
                              foreach ($subject_arr as $sub_outer)
                                {
                                    foreach ($sub_outer as $sub_inner)
                                    {?> {
                    name: "<?php echo $sub_inner['title']; ?>",
                    data: [<?php echo implode(',',$sub_inner['marks']); ?>]
                },
                <?php
                                    }
                                } ?>
            ]
        });
    });
    </script>
    <script src="<?php echo base_url(); ?>assets/js/highcharts.js"></script>
</div>
<?php
}
else
{
	echo get_phrase("no_record_found");
}