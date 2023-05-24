<div id="container" style="width:100%;">
 </div> 
<?php 
$academic_year_d=0;
$academic=$this->db->query("select * from ".get_school_db().".acadmic_year where school_id=$d_school_id and status=2 and is_closed=0 limit 1")->result_array();

 $academic_year_d=$academic[0]['academic_year_id'];


$q="select sum(`marks_obtained`) as total_marks,s.student_id, m.exam_id ,e.name from ".get_school_db().".marks m 
inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id 
inner join ".get_school_db().".student s on m.student_id=s.student_id 
inner join ".get_school_db().".exam e on m.exam_id=e.exam_id
inner join ".get_school_db().".yearly_terms yt on e.yearly_terms_id = yt.yearly_terms_id
inner join ".get_school_db().".acadmic_year a on a.academic_year_id = yt.academic_year_id
inner join ".get_school_db().".exam_routine er
on e.exam_id=er.exam_id
where s.section_id =$section_id
and m.school_id=$d_school_id 
and a.academic_year_id=$academic_year_d
and er.is_approved=1
group by exam_id";

$qu=$this->db->query($q);

 $exame=array();

foreach($qu->result() as $quq)
{
    $total_marks=$quq->total_marks;
    $name=$quq->name;
    $exam_name[]=$quq->name;
    $exam_id=$quq->exam_id;
    $exame[$exam_id]=$total_marks;
}

$qr2 = "select sum(`marks_obtained`) as total_marks,m.student_id, m.exam_id ,e.name from ".get_school_db().".marks m 
inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id 
inner join ".get_school_db().".student s on m.student_id=s.student_id 
inner join ".get_school_db().".exam e on m.exam_id=e.exam_id
inner join ".get_school_db().".yearly_terms yt on e.yearly_terms_id = yt.yearly_terms_id
inner join ".get_school_db().".acadmic_year a on a.academic_year_id = yt.academic_year_id
where s.section_id =$section_id
and m.school_id=$d_school_id 
and a.academic_year_id=".$academic_year_d."
group by exam_id, m.student_id";

$qur2=$this->db->query($qr2);
 $exam2=array();
 foreach($qur2->result() as $qurr2){
  $marks_obtained=$qurr2->total_marks;
  $name=$qurr2->name;
  $exam_id=$qurr2->exam_id;
  $exam2[$exam_id][]=$marks_obtained;
 }
 
$exam_max=array();
$exam_min=array();
$exam_avg=array();

 foreach($exam2 as $key=>$value){
  $exam_max[$key]=max($value);
  $exam_min[$key]=min($value);
  $exam_avg[$key]=round(array_sum($value)/count($value));

 }

 //echo "<pre>";
 //print_r($exam3);
if(count($exam_max) > 0 || count($exam_min) > 0 || count($exam_avg) > 0)
{
	


?>	
			
			
			
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
                    categories: [<?php echo "'".implode("','", $exam_name)."'"; ?>]//, 'Final Term'
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
                
                
           
                    
                    {
                        name: "<?php echo get_phrase('class_highest');?>",
                        data: [<?php echo implode(',',$exam_max); ?>]
                    }, {
                        name: "<?php echo get_phrase('class_average');?>",
                        data: [<?php echo implode(',',$exam_avg); ?>]
                    }, {
                        name: "<?php echo get_phrase('class_lowest');?>",
                        data: [<?php echo implode(',',$exam_min); ?>]
                    }
                ]
            });
        });
			
        </script>		
<?php
}
else
{
	echo get_phrase("no_record_found");
}		
		
		
		
		
		
		
		
		
		
		
		
			