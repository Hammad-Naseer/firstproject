
	<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('select_month');?></th>
            	<th><?php echo get_phrase('select_year');?></th>
            	<th><?php echo get_phrase('select_class');?></th>
            	<th><?php echo get_phrase('select_date');?></th>
           </tr>
       </thead>
		<tbody>
        	<form method="post" action="<?php echo base_url();?>index.php?principal/manage_attendance" class="form">
            	<tr class="gradeA">
                    <td>
                    <?php
                     /*
                    <input class="form-control datepicker" type="text" name="date" id="date" value="<?php echo $month."/".$date."/".$year; ?>" readonly/>
                    */
                    ?>
                    <?php
                   
                    $month=date('n');
					$year=date('Y');
					$class_id = 0;

					if(isset($_POST['submit']))
					{
						$class_id=$this->input->post('class_id');
		 				$year=$this->input->post('year');
		 				$month=$this->input->post('month');
		   			}
                    	$a=month_generate("month","form-control",$month);
						echo $a;
					
					?>
                    </td>
                    <td>
                     <?php
           			$y=date('Y');
            		$b= year_generate("year","form-control",($y-1),($y+1),$year);
            		echo $b;
            		?>
                    </td>
                    
                    <td>
                    	<select name="class_id" class="form-control">
                        	<option value="">Select a class</option>
                        	<?php 
                        	$this->db->where('school_id',$_SESSION['school_id']);
							$classes	=	$this->db->get('class')->result_array();
							foreach($classes as $row):?>
                        	<option value="<?php echo $row['class_id'];?>"
                            	<?php if(isset($class_id) && $class_id==$row['class_id'])echo 'selected="selected"';?>>
									<?php echo $row['name'];?>
                              			</option>
                            <?php endforeach;?>
                        </select>

                    </td>
                    <td align="center"><input type="submit" value="<?php echo get_phrase('view_attendance');?>" class="btn btn-info" name="submit"/></td>
                </tr>
            </form>
		</tbody>
	</table>


  
<?php

if($class_id > 0)
{
	

$num_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
?> 
       
        <center>
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4">
        <div class="tile-stats tile-white-gray">
        <div class="icon"></div>
<h3><?php
echo $_SESSION['student_name'] ?></h3>
                <h2><?php
                
            echo     $monthName = date('F', mktime(0, 0, 0, $month, 10));
                 ?> <?php echo $year;  ?></h2>
            </div>
        </div>
    </div>
</center>
<div class="row">
	<div class="col-md-12">

    <table  class="table table-bordered">
		<thead >
		
             <tr >
             <td>Student name</td>
<?php for($i=1;$i<=$num_days;$i++){
$date1 = "$year-$month-$i";
$date_num = date('N',strtotime($date1));
$qurrr=$this->db->query("select * from holiday where start_date<='$date1' and end_date>='$date1' and school_id=".$_SESSION['school_id']."")->result_array();
if(count($qurrr)>0){
	echo "<td style='background-color:orange;'>$i</td>";
	?>
	
	<?php
	}
	elseif($date_num==6 or $date_num==7){
	?>
	<td style='background-color:green;'><?php echo $i; ?></td>
	<?php
	}
			else {
				?>
                <td><?php echo $i; ?></td>
                <?php
			}	
              
               } ?>
           </tr> 
    </thead>
                <?php
        $q1="Select student_id,name from student where class_id=$class_id AND school_id=".$_SESSION['school_id']." ";
  $res=$this->db->query($q1)->result_array();
  foreach($res as $row1)
  {
  	$stud_id=$row1['student_id'];
  	?>
  	<tr>
  	
  	<td><?php echo $row1['name'];?></td>
  	<?php
                
 $qu="SELECT * FROM  attendance
Where student_id='$stud_id' AND year(`date`) = '$year' and month(`date`) = '$month' AND school_id=".$_SESSION['school_id']." ";
       
       $st=$this->db->query($qu);
       $std_attendance = array();
      
         foreach($st->result() as $rows){         	
         	$date= $rows->date;
         	$day=explode('-',$date);
         	$str = ltrim($day['2'], '0');
        	$std_attendance[$str]=$rows->status;       
       }
                 
                 for($i=1;$i<=$num_days;$i++){ 
                $date = "$year-$month-$i";
                 $date_num = date('N',strtotime($date));
                 $qurrr=$this->db->query("select * from holiday where start_date<='$date' and end_date>='$date' AND school_id=".$_SESSION['school_id']." ")->result_array();
                 if(count($qurrr)>0){
		echo "<td style='background-color:orange;'>";
			}	
                  elseif($date_num==6 or $date_num==7){
				 	
				 	?>
				 	<td style='background-color:green;'></td><?php }
				 	
				 	
				 	
				 		
				 		
				 else {
				 	echo "<td>";
				 }
            
  
                if (array_key_exists($i,$std_attendance))
                {?>
				 
				 	<?php
					//echo $std_attendance[$i];
					if($std_attendance[$i]==0){
						echo "<span class='red'>A</span>";
						
						
					}else if($std_attendance[$i]==1){
							echo "<span  class='green'>P</span>";
						
					}else if($std_attendance[$i]==2){
						echo "<span  class='red'>A</span>";
					}else if($std_attendance[$i]==3){
						echo "<span  class='orange'>L</span>";
						
					}else{
						
					}
				}
                ?></td>
                <?php } ?>
  	</tr>
  <?php }
  ?>
            </tr>
        
   	</table>  
	</div>
</div>
<?php
}
?>
             