<?php
//echo $this->db->count_all('student');
?>
<style>
	.w100{
		width:70%;
	}
	
.nav li a{
	text-transform: capitalize;
}
.tile-stats.tile-red {
    background: #f56954 !important;
}


.tile-stats.tile1 {
    background: #73d7d7;
}	
.tile-stats.tile2 {
    background: #25a505;
}
.tile-stats.tile3 {
    background: #85c5a5;
}
.tile-stats.tile4 {
 background:#83cbff !important }
</style>


<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
  <li><a data-toggle="tab" href="#menu1">Admited Student</a></li>
  <li><a data-toggle="tab" href="#menu2">Teacher's Attendance </a></li>
  
  <li><a data-toggle="tab" href="#menu3">Leave Requests</a></li>
  
  <li><a data-toggle="tab" href="#menu4">Leave Request Graph</a></li>
  
    
  <li><a data-toggle="tab" href="#menu5">Current Exams Routine</a></li>
  
  
  
  
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
  

<div class="row">



<div class="col-lg-3 col-md-3 col-sm-3">
            
                <div class="tile-stats tile-green">
                    <div class="icon"><i class="entypo-users"></i></div>
                    <div class="num" data-start="0" data-end="<?php
                     $this->db->where('school_id',$_SESSION['school_id']);
                    $this->db->from('teacher');
                    echo $this->db->count_all_results();
                    ?>" 
                    		data-postfix="" data-duration="800" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('teacher');?></h3>
                   <p>Total teachers</p>
                </div>
                
            </div>

<div class="col-lg-3 col-md-3 col-sm-3">
            
<div class="tile-stats tile-red">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php
                    
                    $school_id=$_SESSION['school_id'];
                    
  $this->db->where('school_id',$school_id);
                    $this->db->from('student');
       $total_count= $this->db->count_all_results();

echo $total_count;





                  ?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('student');?></h3>
                   <p>Total students</p>
                </div>
                
            </div>
	
<div class="col-lg-3 col-md-3 col-sm-3">
            
<div class="tile-stats tile-orange">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php
                    

 $qur_count=  $this->db->query('select count(*) as total_num from attendance where date=date(now()) and school_id='.$school_id)->result_array();
    
     $total_mark= $qur_count[0]['total_num'];

echo $unmark=$total_count-$total_mark; //unmaked count value




                  ?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('unmark');?></h3>
                   <p>Total students</p>
                </div>
                
            </div>  
      
<div class="col-lg-3 col-md-3 col-sm-3">
            
<div class="tile-stats tile-blue">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php
                    

 $qur_count=  $this->db->query('select count(*) as total_num from attendance where date=date(now()) and status=1 and school_id='.$school_id)->result_array();
    
  echo    $total_mark= $qur_count[0]['total_num'];






                  ?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('present student');?></h3>
                   <p>present student</p>
                </div>
                
            </div>   
     
<div class="col-lg-3 col-md-3 col-sm-3">
            
<div class="tile-stats tile2">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php
                    

 $qur_count=  $this->db->query('select count(*) as total_num from attendance where date=date(now()) and status=2  and school_id='.$school_id)->result_array();
    
  echo    $total_mark= $qur_count[0]['total_num'];






                  ?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('absent student');?></h3>
                   <p>absent student</p>
                </div>
                
            </div>  

<div class="col-lg-3 col-md-3 col-sm-3">
            
<div class="tile-stats tile1">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php
                    

 $qur_count=  $this->db->query('select count(*) as total_num from attendance where date=date(now()) and status=3  and school_id='.$school_id)->result_array();
    
  echo    $total_mark= $qur_count[0]['total_num'];






                  ?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('students on leave');?></h3>
                   <p>students on leave</p>
                </div>
                
            </div>  
	
	
	
	<div class="col-lg-3 col-md-3 col-sm-3">
            
<div class="tile-stats tile4">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php
                    

 $qur_count=  $this->db->query('SELECT count(*) as total_num
FROM  `chalan` 
WHERE MONTH(date) = MONTH(NOW())  and status=0  and school_id='.$school_id)->result_array();
    
  echo    $total_mark= $qur_count[0]['total_num'];






                  ?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('total unpaid');?></h3>
                   <p>total unpaid</p>
                </div>
                
            </div>  
	  
	
	
	<div class="col-lg-3 col-md-3 col-sm-3">
            
<div class="tile-stats tile-green">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php
                    

 $qur_count=  $this->db->query('SELECT count(*) as total_num
FROM  `chalan` 
WHERE MONTH(date) = MONTH(NOW())  and status=1  and school_id='.$school_id)->result_array();
    
  echo    $total_mark= $qur_count[0]['total_num'];






                  ?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('total paid');?></h3>
                   <p>total paid</p>
                </div>
                
            </div>  	
	





	
	
	
	
	
	  
</div>     
</div>
 
 
 
 
 
 <div id="menu1" class="tab-pane fade">
            
            
   <div class="row">       
            
            
            
		
<div class="col-md-12 col-lg-12 col-sm-12">
	<?php 
	$school_id=$_SESSION['school_id'];
	$class_count=$this->db->query("SELECT COUNT( s.class_id ) AS class_count, c.name AS class_name
FROM student s
INNER JOIN class c ON c.class_id = s.class_id
WHERE s.school_id=$school_id
GROUP BY s.class_id
ORDER BY c.order_by"
)->result_array();




print_r($class_count);
?>
	

<div id="container" class="w100">
	
</div>

</div>




</div>


<script>

$(function () {
    // Create the chart
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Students vs Class '
        },
        subtitle: {
            text: 'Total students in class'
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Number Of Students'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    format: '{point.y}'
                }
            }
        },

        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
        },

        series: [{
            
            name: 'class',
            colorByPoint: true,
      data: [
      <?php
     $count_arr = count($class_count);
       foreach($class_count as $rows): ?>
      
            {
           name: "<?php echo $rows['class_name']; ?>",
            y: <?php echo $rows['class_count']; ?>,
                drilldown: null
            }
            <?php if(($count_arr--) > 0){
            	echo ",";
            	
            } ?>
            
            
            <?php endforeach ?>
            
            
            ]
        }],
        drilldown: {
            series: []
        }
    });
});
   
</script>	





<!------------------------Second (2nd) chart------------------------->



  <div class="row">       
            
            
            
		
<div class="col-md-12 col-lg-12 col-sm-12">


<div id="container2"  class="w100"></div>



<script>
	$(function () {
    Highcharts.chart('container2', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Student vs Class'
        },
        subtitle: {
            text: 'Average of Paid/non Paid & Present/Absent'
        },
        xAxis: {
            categories: [
                'Play group',
          
                'Nursery',
                'one',
                'Two',
                'Three',
                'Four',
                'Five',
                'Six',
                'Seven',
                'Eight',
                'Nine',
                'Ten'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number of students'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Paid',
            data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

        }, {
            name: 'Non Paid',
            data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

        }, {
            name: 'Present',
            data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

        }, {
            name: 'Absent',
            data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

        }]
    });
});
	
</script>
<!---->
















</div> <!--col-12-->
</div><!--row-->

  </div><!--for tab-->
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
  <div id="menu2" class="tab-pane fade">
    
<div class="row">	
	
<div class="col-lg-12 col-md-12 col-sm-12">
<h2>Absent Teacher's List</h2>

<?php 

$teacher_attnd=$this->db->query("select t.teacher_id, t.name as teacher_name, c.name as class_name from teacher t 

inner join attendance_teacher at on at.teacher_id=t.teacher_id 

left join class c on c.teacher_id=t.teacher_id

where at.date=date(now()) and at.status=0 and at.school_id=".$_SESSION['school_id']."
")->result_array();






/*

foreach($teacher_attnd as $abs_row){
	
echo "Teacher: ".$abs_row['teacher_name'];	
if($abs_row['class_name']!="")
{
	echo "--Class: ".$abs_row['class_name'];
}

	echo "<br />";
 $teacher_atnid=$abs_row['teacher_id'];	
$teacher_atn=$this->db->query("select 

s.name as subject_name, c.name as class_name


from subject s inner join class c on s.class_id=c.class_id where s.teacher_id=$teacher_atnid")->result_array();

		

foreach($teacher_atn as $tr_attn){

echo "<br>Subject: ".$tr_attn['subject_name']."--Class: ".$tr_attn['class_name'];
	
	
}

echo "<hr>";

}



dont remove that end tag of php
*/

?>
	

 <style>
 	
 	.hgh{
		min-height: 340px;

border: 1px solid #CCCCCC4D;

margin: 13px;

box-shadow: 0px 0px 7px -3px;
padding-top: 3px;
		
	}
	.myhhh{
		font-size: 12px !important;
font-weight: bold;
text-align: center;
color:#8d0303;
	
	
	}
 </style>
 
 
 

 	<?php foreach($teacher_attnd as $abs_row){
 		
 		$teacher_atnid=$abs_row['teacher_id'];	
$teacher_atn=$this->db->query("select 

s.name as subject_name, c.name as class_name


from ".get_school_db().".subject s inner join ".get_school_db().".class c on s.class_id=c.class_id where s.teacher_id=$teacher_atnid and s.school_id=".$_SESSION['school_id']."")->result_array();

 		
 		
 		 ?>	
 
 
 <div class="col-lg-4 col-md-4 col-sm-4 ">
 <div class="hgh ">
		
		<h4 class="myhhh">
		<?php echo "Teacher: ".$abs_row['teacher_name'];	
if($abs_row['class_name']!="")
{
	echo "--Class: ".$abs_row['class_name'];
}   ?>
	
		</h4>
		
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Subject</th>
					<th>Class</th>
				</tr>
			</thead>
			
			<tbody>
				
		
				
				<?php 
				
				$mycount = 1;
				foreach($teacher_atn as $tr_attn){ 
				//$mycount = $mycount+1; ?>
				<tr>
					<td><?php echo $mycount++ ; ?></td>
					<td><?php echo $tr_attn['subject_name']; ?></td>
					<td><?php echo $tr_attn['class_name']; ?></td>
					
				</tr>
				
		
			 	<?php } ?>	
			
			</tbody>
		</table>
		</div>
	</div>
 
 	<?php } ?>
 

 
 
 
 	</div>
	
	</div>
  </div>
   
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
  <div id="menu3" class="tab-pane fade">
   <div class="row">

<div class="col-lg-12 col-md-12 col-sm-12">



<h2>Teacher's leave Request</h2>
<?php 
$teacher_lev=$this->db->query("select t.name as teacher_name, lc.name as cat_name, ls.start_date,ls.end_date,ls.proof_doc
,ls.reason
from ".get_school_db().".leave_staff ls
inner join ".get_school_db().".teacher t on t.teacher_id=ls.teacher_id
inner join ".get_school_db().".leave_category lc on ls.leave_id=lc.leave_category_id
where
ls.school_id=".$_SESSION['school_id']." and
ls.status=0 and 
ls.start_date<=date(now()) and 
ls.end_date>=start_date<date(now())")->result_array();


?>	










<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable dataTable" id="table_export" aria-describedby="table_export_info">
 
 
  <thead>
    <tr>
      <th > Teacher Name</th>
       <th >Type</th>
        <th > Date(From)</th>
         <th >Date(To)</th>
           <th >Days</th>
            <th >Reason</th>
   </tr>
  </thead>
  <tbody>
  
  <?php foreach($teacher_lev as $lev_row){   ?>
  
    <tr>
     
      <td> <?php echo $lev_row['teacher_name'] ?></td>
      <td> <?php echo $lev_row['cat_name'] ?></td>
      <td> <?php echo $lev_row['start_date'] ?></td>
      <td> <?php echo $lev_row['end_date'] ?></td>
      <td> <?php echo $lev_row['proof_doc'] ?></td>
      <td> <?php echo $lev_row['reason'] ?></td>
  
      
   
    </tr>
    
    
     <?php } ?>
  </tbody>
</table>


</div>

</div>

  </div>
  
  
  
  
  
  
  
 
 
   <div id="menu4" class="tab-pane fade">
 
 
 
 
 



 
<h2><?php echo date('F - Y'); ?> leave request</h2>
<?php 
$req_count=$this->db->query("select count('lc.leave_category_id') as req_count, lc.name as cat_name      
from ".get_school_db().".leave_category lc left join ".get_school_db().".leave_staff ls on lc.leave_category_id=ls.leave_id
where 
ls.school_id=".$_SESSION['school_id']." and
year(ls.request_date)=year(now()) and
month(ls.request_date)=month(now())

group by lc.name ")->result_array();


//foreach($req_count as $count_row){

//echo $count_row['req_count']."<br />";
//echo $count_row['cat_name']."<br />";

//}
?>	

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12">
<div id="container_leave" class="w100" >
	
</div>
</div>
</div>

<script>

$(function () {
    // Create the chart
    $('#container_leave').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Number Of request'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
            	
            	 pointWidth: 20,//width of the column bars irrespective of the chart size
            	
            	
            	
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    format: '{point.y}'
                }
            }
        },

        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
        },

        series: [{
            
            name: 'class',
            colorByPoint: true,
      data: [ 
      <?php
     $count_arr = count($class_count);
       foreach($req_count as $count_row): ?>
      
            {
           name: "<?php echo $count_row['cat_name']; ?>",
            y: <?php echo $count_row['req_count']; ?>,
                drilldown: null
            }
            <?php if(($count_arr--) > 0){
            	echo ",";
            	
            } ?>
            
            
            <?php endforeach ?>
            
            
            ]
        }],
        drilldown: {
            series: []
        }
    });
});
   
</script>	





<?php 
$current_exam=$this->db->query("select * from ".get_school_db().".exam
where 
school_id=".$_SESSION['school_id']." and
year(start_date)<=year(now()) and 
month(start_date)<=month(now()) and
year(end_date)>=year(now()) and 
month(end_date)>=month(now())")->result_array();


?>





 
 
 
 
 

 

 

</div>













  <div id="menu5" class="tab-pane fade">
  
  <div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <div class="panel-title">Current Exam Routine</div>
      <div class="panel-options"> 
      <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a> <a href="#" data-rel="close"><i class="entypo-cancel"></i></a> </div>
    </div>
    <div class="panel-body with-table">
      <table class="table table-bordered table-responsive">
        <thead>
          <tr>
            <th>Title</th>
            <th>Starting Date</th>
            <th>Ending date</th>
           
          </tr>
        </thead>
        <tbody>
        
        <?php foreach($current_exam as $exam_row){
        	  ?> 
          <tr>
            <td><?php echo $exam_row['name']  ?></td>
            <td><?php  echo $exam_row['start_date'] ?></td>
            <td><?php  echo $exam_row['end_date'] ?></td>
            
          </tr>
          

          
    <?php   }?>	
          
          
          
           
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>

  </div>
