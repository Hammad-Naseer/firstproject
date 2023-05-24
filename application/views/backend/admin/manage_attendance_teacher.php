<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('select_date');?></th>
            	<th><?php echo get_phrase('action');?></th>
           </tr>
       </thead>
		<tbody>
        	<form method="post" action="<?php echo base_url();?>admin/attendance_selector_teacher" class="form">
            	<tr class="gradeA">
                    <td>
                    <input class="form-control datepicker" type="text" name="date" id="date" value="<?php echo $month."/".$date."/".$year; ?>" readonly/>
                    </td>
                    <td align="center"><input type="submit" value="<?php echo get_phrase('manage_attendance');?>" class="btn btn-info"/></td>
                </tr>
            </form>
		</tbody>
	</table>

<?php if($date!='' && $month!='' && $year!=''):?>

<center>
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4">
        
            <div class="tile-stats tile-white-gray">
                <div class="icon"><i class="entypo-suitcase"></i></div>
                <?php
         $full_date	=	$year.'-'.$month.'-'.$date;
      $timestamp = strtotime($full_date);
  $day = strtolower(date('l', $timestamp));
  
  
                 ?>
                <h2><?php echo ucwords($day);?></h2>
                
                <h3><?php echo get_phrase('attendance_of_teacher'); ?></h3>
                <p><?php echo $date.'-'.$month.'-'.$year;?></p>
            </div>
        </div>
    </div>
</center>


<div class="row">
	<div class="col-md-12" style="    overflow: scroll;">
<form method="post" action="<?php echo base_url();?>admin/apply_attendence_teacher/<?php echo $date.'/'.$month.'/'.$year;?>">
    <table  class="table table-bordered">
		<thead>
            <tr>
                <td><?php echo get_phrase('teacher_id');?></td>
                <td><?php echo get_phrase('teacher_name');?></td>
                <?php
$date_curr= date("t");
$date_day= date("d");
$date_week=date( "w");
$month=date('m');
$year=date('Y');

for($i=1;$i<=$date_curr;$i++){ ?>
                <td  
                <?php 
$date1 = "$year-$month-$i";
$date_num = date('N',strtotime($date1));

if($date_num==6 or $date_num==7){

echo "style='background-color:#EEE;'";
	
}
else
{
	
$qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ")->result_array();
	if(count($qurrr)>0){
		echo "style='background-color:orange;'";
		///echo "style='background-color:blue;'";
			}	
	
}

?>
                ><?php
             
                  
                echo $i; 
                
                 if($date_day==$i){
                 	
echo "<input type='checkbox' id='selecctall' />";	
				}
                
                
                ?>
                
                
                
               </td>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
  	          <?php 
			//STUDENTS ATTENDANCE
		$dayt=date('Y-m-d');
		//$dayt=$year.'-'.$month.'-'.$i;

$verify_data=array(	'date' => $dayt,
'school_id' =>$_SESSION['school_id']
);
 $attendance_teacher = $this->db->get_where(get_school_db().'.attendance_teacher' , $verify_data);
$att_array=array();
if($attendance_teacher->num_rows() != 0){
foreach($attendance_teacher->result() as $attn){
$teacher_id=$attn->teacher_id;
$att_array[$teacher_id]=$attn->status;
} 
$stru	= 1;
}
else{
$stru=0;
}
$verify_data1=array(
'school_id' =>$_SESSION['school_id']
);
$teachers=$this->db->get_where(get_school_db().'.teacher',$verify_data1)->result_array();
			$j=0;	
			foreach($teachers as $row)
			{
				/*
				if($stru==1){
					$tre=$row['teacher_id'];
				
				echo $status=$att_array[$tre];
				}else{
					//$status=1;
				}
				*/ ?>
				<tr class="gradeA attendence">
					<td><?php echo $row['teacher_id'];?></td>
					<td><?php echo $row['name'];?></td>
                    <?php 
for($i=1;$i<=$date_curr;$i++){ 
if($i<10)	{
	
	$dayt	=	$year.'-'.$month.'-'."0".$i;}
 else	{$dayt	=	$year.'-'.$month.'-'.$i;}
     
     $verify_data=array(	'teacher_id' => $row['teacher_id'], 'date' => $dayt);
 $attendance_teacher = $this->db->get_where(get_school_db().'.attendance_teacher' , $verify_data);

if($attendance_teacher->num_rows() != 0){
 $a=$attendance_teacher->row()->status;
$status	= $a;
}
else{
$status=0;
}

?>
<td <?php


$date1 = "$year-$month-$i";
$date_num = date('N',strtotime($date1));


$qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ")->result_array();
	if(count($qurrr)>0){
		echo "style='background-color:orange;'";
		///echo "style='background-color:blue;'";
			}	
elseif($date_num==6 or $date_num==7){

echo "style='background-color:#CCC;'";
	
}	




 if(isset($attendance_teacher->row()->marked_by)){?> data-ot="Marked by: <?php echo $attendance_teacher->row()->marked_by;}?>" data-ot-delay="0.2" id="tooltip">
<div class="form-group">
     <?php 
    if($status!=3){?>
                            
<input type="checkbox"
<?php if($i==$date){
		if($stru==0){
			echo "checked";
			}  
			?>
			class="form-control checkbox1"   
 value="1" name="status-<?php echo $j; ?>" id="status_absent"
                       
                       <?php }else echo' disabled';?>  <?php if($status == 1){echo 'checked';}?> style="width:15px !important;"/>
                        <?php 
                            }
                        elseif($status==3) 
						{ 
						echo "L";	
						}?>
                        <?php if($i==$date){?>
                        <input type="hidden" name="teacher_id[]"  value="<?php echo $row['teacher_id'];?>"/>
                        <input type="hidden" name="class_id" value="<?php echo $class_id;?>"/>
                        <?php }?>
</div>
</td>
<?php
}
?>
</tr>
<?php 
$j++;
}?>
<tr>
            	<td colspan="33" style="text-align:center;">
                <input name="submit1" type="submit" class="btn btn-default" value="<?php echo get_phrase('save_attendance'); ?>" style="float:middle; margin:0px 10px;padding:6px 50px;">
                </td>
            </tr>
        </tbody>
   	</table>  
</form>
	</div>
</div>
<?php endif;?>

<script>
	
	$(document).ready(function(){
	

$(document).on('change',"#selecctall",function(){
	
$(".checkbox1").prop('checked',$(this).prop("checked"));
          
          });


	
		
	});
	
	
	
</script>