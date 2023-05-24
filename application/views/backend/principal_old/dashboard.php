<style>
	.w100{
		width:70%;
	}
	
.nav li a{
	text-transform: capitalize;
}	
</style>


<ul class="nav nav-tabs">
  <!--<li class="active"><a data-toggle="tab" href="#home">Home</a></li>-->
  <li class="active"><a data-toggle="tab" href="#menu1">Admited Student</a></li>
  <li><a data-toggle="tab" href="#menu2">Teacher's Attendance </a></li>
  
  <li><a data-toggle="tab" href="#circular_notice">Circulars and Notices</a></li>
  
  <!--<li><a data-toggle="tab" href="#menu4">Leave Request Graph</a></li>-->
  
    
  <li><a data-toggle="tab" href="#menu5">Current Exams Routine</a></li>
  
</ul>
<div class="tab-content">
<div id="menu1" class="tab-pane fade">          
   <div class="row">       
            		
<div class="col-md-12 col-lg-12 col-sm-12">
	<?php 
	$class_count=$this->db->query("SELECT COUNT( s.class_id ) AS class_count, c.name AS class_name
FROM student s
INNER JOIN class c ON c.class_id = s.class_id
WHERE c.school_id=".$_SESSION['school_id']."
GROUP BY s.class_id
ORDER BY c.order_by")->result_array();

?>
	


<div id="container" class="w100">
	
</div>



</div></div>  






























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

<?php 
$current_exam=$this->db->query("select * from exam
where 
school_id=".$_SESSION['school_id']." and
year(start_date)<=year(now()) and 
month(start_date)<=month(now()) and
year(end_date)>=year(now()) and 
month(end_date)>=month(now())")->result_array();




?>	

  </div>
  

 
  <div id="menu2" class="tab-pane fade">
    
<div class="row">	
	
<div class="col-lg-12 col-md-12 col-sm-12">
<h2>Absent Teacher's List</h2>

<?php 

$teacher_attnd=$this->db->query("select t.teacher_id, t.name as teacher_name, c.name as class_name from teacher t 

inner join attendance_teacher at on at.teacher_id=t.teacher_id 

left join class c on c.teacher_id=t.teacher_id

where at.date=date(now()) and at.status=0")->result_array();





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


from subject s inner join class c on s.class_id=c.class_id where s.teacher_id=$teacher_atnid")->result_array();

 		
 		
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
        
        <?php foreach($current_exam as $exam_row){  ?> 
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

 
 <div id="circular_notice" class="tab-pane fade">
 <?php 

 $noticeboard=$this->db->query("select * from noticeboard where school_id=".$_SESSION['school_id']." LIMIT 3")->result_array();

?>
<div class="row" style="    margin-top: 41px;     margin-left: 0px;" >
<div class="box">
<div class="box-header with-border">
          <h3 class="box-title">
           <i class="fa fa-table" aria-hidden="true"></i>Latest Notices</h3>
          
 </div></div>
<div class=" col-lg-6 col-md-6 col-sm-6  ">
        
            <!-- Fluid width widget -->        
    	    <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">
                      
                        <i class="fa fa-bell-o" aria-hidden="true"></i>
                        Notice Board
                    </h3>
                </div>
                <div class="panel-body">
                
                    <ul class="media-list">
 <?php foreach($noticeboard as $rt){?>                    
                        <li class="media">
                            <div class="media-left">
                                <div class="panel panel-danger text-center date">
                                    <div class="panel-heading month">
                                        <span class="panel-title strong">
<?php $date_array=explode('-',$rt['create_timestamp']);                                         
                                           
echo date('M',mktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]));
                                            
                                            ?>
                                        </span>
                                    </div>
                                    <div class="panel-body day text-danger">
<?php 
                                        
                                           
echo date('d',mktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]));
  
   ?>
                                    </div>
                                </div>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <?php echo $rt['notice_title'];?>
                                </h4>
                                <p>
                                   <?php echo $rt['notice'];?>
                                </p>
                            </div>
                        </li>
                        
<?php } ?>                        
                    </ul>
                    <a href="<?php echo base_url();?>index.php?principal/noticeboard" class="more">More »</a>
                </div>
            </div>
            <!-- End fluid width widget --> 
            
		</div>
		
		<?php 
$circular=$this->db->query("SELECT c.circular_id, c.circular_title, c.circular, c.class_id, c.student_id, c.create_timestamp, c.attachment
FROM circular c where c.school_id=".$_SESSION['school_id']." LIMIT 3")->result_array();
 ?>
<div class=" col-lg-6 col-md-6 col-sm-6  " id="circulars">
        
            <!-- Fluid width widget -->        
    	    <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-calendar"></span> 
                        Circular
                    </h3>
                </div>
                <div class="panel-body">
                
                    <ul class="media-list">
 <?php foreach($circular as $rt){?>                    
                        <li class="media">
                            <div class="media-left">
                                <div class="panel panel-danger text-center date">
                                    <div class="panel-heading month">
                                        <span class="panel-title strong">
                                            <?php 
$date_array=explode('-',$rt['create_timestamp']);     
echo date('M',mktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]));


//echo $rt['create_timestamp']
                                            
                                            ?>
                                        </span>
                                    </div>
                                    <div class="panel-body day text-danger">
<?php echo date('d',mktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]));?>
                                    </div>
                                </div>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <?php echo $rt['circular_title'];
              						if($rt['attachment']=="")
    								{
									}
									else
									{?>
									<a target="_blank" href="<?php echo base_url();?>uploads/diary_image/<?php echo $rt['attachment'];?>"><span class="glyphicon glyphicon-download-alt"></span></a>
										
									<?php } 
									?>
                                    
                                   
                                </h4>
                                <?php if($rt['student_id']>0){ 
         $str_qur=$this->db->query("select name,school_id from student where school_id=".$_SESSION['school_id']." and student_id=".$rt['student_id'])->result_array();



                                
                                ?>
                                 <p>
                                   <?php echo $str_qur[0]['name'];?>
                                </p>
        <?php } ?>                        
                                
                                <p>
                                   <?php echo $rt['circular'];?>
                                </p>
                            </div>
                        </li>
                        
<?php } ?>                        
                    </ul>
                    <a href="<?php echo base_url();?>index.php?principal/circulars" class="more">More »</a>
                </div>
            </div>
            <!-- End fluid width widget --> 
            
		</div>		

</div>
</div>
  
