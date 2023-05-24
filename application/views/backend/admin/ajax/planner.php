<?php
if($_POST['month'] && $_POST['year']){	
$date_month=$_POST['month'];
$date_year=$_POST['year'];

$sub_id=$_POST['subject_id'];
$sec_id=$_POST['section_id'];
$class_id=$_POST['class_id'];
$department_id=$_POST['department_id'];

$subj_query="";
if(isset($sub_id) && ($sub_id>0))
{
	$subj_query=" AND a.subject_id=$sub_id";
}
elseif((!isset($sub_id) || ($sub_id==0)) && (isset($sec_id) && ($sec_id>0)))
{
	
	$subj_query=" AND a.subject_id in(SELECT distinct subject_id FROM ".get_school_db().".subject_section WHERE section_id=$sec_id && school_id=".$_SESSION['school_id'].")";
}
elseif((!isset($sec_id) || ($sec_id==0))  && (isset($class_id) && ($class_id>0)) )
{
	$subj_query=" AND a.subject_id in(SELECT distinct subject_id FROM ".get_school_db().".subject_section ss
INNER JOIN ".get_school_db().".class_section cs ON ss.section_id=cs.section_id
INNER JOIN ".get_school_db().".class c ON c.class_id=cs.class_id
 WHERE c.class_id=$class_id && c.school_id=".$_SESSION['school_id'].")";
}
elseif((!isset($class_id) || ($class_id==0))  && (isset($department_id) && ($department_id>0)) )
{
	$subj_query=" AND a.subject_id in(SELECT distinct subject_id FROM ".get_school_db().".subject_section ss
INNER JOIN ".get_school_db().".class_section cs ON ss.section_id=cs.section_id
INNER JOIN ".get_school_db().".class c ON c.class_id=cs.class_id
INNER JOIN ".get_school_db().".departments d ON d.departments_id=c.departments_id
 WHERE d.departments_id=$department_id && d.school_id=".$_SESSION['school_id'].")";
}
else
{
	$subj_query=" AND a.subject_id in(SELECT distinct subject_id FROM ".get_school_db().".subject_section ss
INNER JOIN ".get_school_db().".class_section cs ON ss.section_id=cs.section_id
INNER JOIN ".get_school_db().".class c ON c.class_id=cs.class_id
INNER JOIN ".get_school_db().".departments d ON d.departments_id=c.departments_id
 WHERE d.school_id=".$_SESSION['school_id'].")";
}

$query="SELECT a.planner_id, a.title, a.start,a.objective,a.assesment,a.requirements,a.required_time, a.attachment, s.name as sub_name, s.subject_id as subject_id, s.code as code,a.detail as detail FROM ".get_school_db().".academic_planner a
INNER JOIN ".get_school_db().".yearly_terms y
ON a.yearly_terms_id=y.yearly_terms_id
INNER JOIN ".get_school_db().".acadmic_year ay
ON y.academic_year_id=ay.academic_year_id 
INNER JOIN ".get_school_db().".subject s
ON a.subject_id = s.subject_id
Where MONTH(start) =$date_month  and year(start)=$date_year AND a.school_id=".$_SESSION['school_id']." $subj_query";



$qur_red=$this->db->query($query)->result_array();

$plan=array();

foreach($qur_red as $red){
$plan[$red['start']][$red['subject_id']][]=array('planner_id'=>$red['planner_id'], 'title'=>$red['title'],'detail'=>$red['detail'],'objective'=>$red['objective'],'assesment'=>$red['assesment'],'requirements'=>$red['requirements'],'required_time'=>$red['required_time'],'attachment'=>$red['attachment'],'sub_name'=>$red['sub_name'],'subject_id'=>$red['subject_id'],'code'=>$red['code']

);

}
/*echo "<pre>";
print_r($plan);*/
//exit;
//print_r($rec_array)
?>

 <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable dataTable" id="table_export" aria-describedby="table_export_info">
  <thead>
<tr>
<th style="width:100px;"><?php echo get_phrase('date');?></th>
<th style="width:70px;"><?php echo get_phrase('day');?></th>
<th style=""><?php echo get_phrase('detail');?></th>
</tr>
 </thead>
 
 
   <tbody style="   /* border: 3px solid #c0dcff;*/">
<?php
$d=cal_days_in_month(CAL_GREGORIAN,$date_month,$date_year);
for($i=01; $i<=$d; $i++){

$s=mktime(0,0,0,$date_month, $i, $date_year);
$today_date= date('Y-m-d',$s);
 $dw = date( "l", strtotime($today_date));
 $d1 = date( "d-M-Y", strtotime($today_date));
 
echo "<tr>";
echo "<td style='        
   '><span style='!important;'>$d1</span> </td>";
   
   
   
   echo "<td style='        
   '>$dw</td>";
   
echo "<td >";

if(isset($plan[$today_date]))
{
	foreach($plan[$today_date] as $val)
	{
	
	
		
		if(count($val) >0)
		{
			
		echo "<br/>";
		echo $val[0]['sub_name']."-";
		echo $val[0]['code'];
		
		
		foreach($val as $row)
		{
			
echo "<br/>";	
echo get_phrase("title")." :".$row['title'] ;

		?>
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_view_planner/<?php echo $row['planner_id'];?>');" 
            	class="btn btn-primary">
            	<?php echo get_phrase('view_details');?>
                </a>
    <?php
	/*
	<!--<div class="">
		<?php
		echo "Title: ".$pln['title'] ;
		echo "<br/>";
		echo "Detail: ".$pln['detail'];
		echo "<br/>";
		echo "Objective: ".$pln['objective'];
		echo "<br/>";
		echo "Assesment: ".$pln['assesment'];
		echo "<br/>";
		echo "Requirements: ".$pln['requirements'];
		echo "<br/>";
		echo "Required time: ".$pln['required_time'];
		echo "<br/>";
?>

<?php
if($pln['attachment']!=""){
$file_name=$pln['attachment'];
$folder_name='academic_planner';
$display_link=display_link($file_name,$folder_name);	
		?>
		
<a target="_blank" href="<?php echo $display_link;?>">Attachment <i class="fa fa-download" style="color:#41c824; padding-left:5px;" aria-hidden="true"></i></a>

<?php echo "<br/>"; 
 } 
 echo "Subject: ".$pln['sub_name']."- ".$pln['code'];
$section_id= subject_section_list($pln['subject_id']);
$section_array= subject_section_detail($pln['subject_id']);



foreach($section_array as $row)
{
	echo "<br/>";
	echo "Class: ".$row['c'].' -> ';
	echo "Section: ".$row['s'];
}



echo "<br/>";
$teacher_list=subject_teacher($pln['subject_id']);
echo "Teacher name: ";
foreach($teacher_list as $teacher)
{
	echo $teacher['teacher_name'].",";
}




?>					
	
	<hr style="margin: 2px;">



</div>	-->
		
		<?php
		
		*/
	}
	}
	}	
}
echo "</td>";
echo "</tr>";
}
?>
  </tbody>
</table>
<?php }	
?>	

<script>

</script>
<!--<script>

$(function(){ /* to make sure the script runs after page load */

	$('.item').each(function(event){ /* select all divs with the item class */
	
		var max_length = 130; /* set the max content length before a read more link will be added */
		
		if($(this).html().length > max_length){ /* check for content length */
			
			var short_content 	= $(this).html().substr(0,max_length); /* split the content in two parts */
			var long_content	= $(this).html().substr(max_length);
			
			$(this).html(short_content+
						 '<a href="#" class="read_more" style="color:#03bf5f;font-weight:bold;"><br/>Read More</a>'+
						 '<span class="more_text" style="display:none;">'+long_content+'</span>'); /* Alter the html to allow the read more functionality */
						 
			$(this).find('a.read_more').click(function(event){ /* find the a.read_more element within the new html and bind the following code to it */
 
				event.preventDefault(); /* prevent the a from changing the url */
				$(this).hide(); /* hide the read more button */
				$(this).parents('.item').find('.more_text').show(); /* show the .more_text span */
		 
			});
			
		}
		
	});
 
 
});


</script>-->