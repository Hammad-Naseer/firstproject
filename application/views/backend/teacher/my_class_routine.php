<?php
    $custom_css=array(1=>'current-day',2=>'holiday');
    $current_date=date("l");
    $yearly_term_id = $_SESSION['yearly_term_id'];
    $login_detail_id = $_SESSION['login_detail_id'];
?>

<style>
    .close{
        float: right;
        width: 10px;
        position: relative;
        left: 94%;
        top: 10px;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
               <?php echo get_phrase('my_timetable'); ?>
        </h3>
    </div>
</div>

<?php
$routine1=array();
$days_arr = array(get_phrase('monday'), get_phrase('tuesday'), get_phrase('wednesday'), get_phrase('thursday'), get_phrase('friday'), get_phrase('saturday'), get_phrase('sunday'));

$result = $this->db->query("select s.subject_id, s.name, s.code, cr.class_routine_id, cr.day, cr.period_no, d.title as department, class.name as class, cs.title as class_section,cr.duration, crs.period_duration,cr.period_start_time,cr.period_end_time
	from ".get_school_db().".subject s
	inner join ".get_school_db().".subject_teacher st on s.subject_id =  st.subject_id
	inner join ".get_school_db().".staff staff on staff.staff_id =  st.teacher_id
	inner join ".get_school_db().".time_table_subject_teacher ttst on st.subject_teacher_id =  ttst.subject_teacher_id
	inner join ".get_school_db().".class_routine cr on cr.class_routine_id =  ttst.class_routine_id
	inner join ".get_school_db().".class_routine_settings crs on (crs.c_rout_sett_id =  cr.c_rout_sett_id and crs.is_active = 1)

	inner join ".get_school_db().".subject_section ss on ss.subject_id = st.subject_id
	inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
	inner join ".get_school_db().".class on class.class_id = cs.class_id
	inner join ".get_school_db().".departments d on d.departments_id = class.departments_id

	where 
	staff.user_login_detail_id = $login_detail_id
	and crs.school_id = ".$_SESSION['school_id']." 
	group by day, period_no
	order by day, period_no
	")->result_array();
//print_r($result);
$timetable_arr = array(
    'monday'=>array(), 
    'tuesday'=>array(), 
    'wednesday'=>array(), 
    'thursday'=>array(), 
    'friday'=>array(), 
    'saturday'=>array(), 
    'sunday'=>array()
);
$dcs_arr = array(
    'monday'=>array(), 
    'tuesday'=>array(), 
    'wednesday'=>array(), 
    'thursday'=>array(), 
    'friday'=>array(), 
    'saturday'=>array(), 
    'sunday'=>array()
);
$week_arr=array();
$week_day=array();
$week_arr=array(get_phrase("monday"),get_phrase("tuesday"),get_phrase("wednesday"),get_phrase("thursday"),get_phrase("friday"),get_phrase("saturday"),get_phrase("sunday"));	

for($i=0;$i<6;$i++)
{
	$week_arr[$i];
	$week_day[]=date("Y-m-d",strtotime($week_arr[$i].' this week'));
}

//echo get_school_db();			

$pp="SELECT st.*,st.date as date, s.name as teacher_name,s.staff_id as teacher_id,subj.name as subject_name,subj.code as code,cs.class_id as class_id, cs.title as section_name,class.name as class_name,d.title as dept_name FROM ".get_school_db().".substitute_teacher st 
	INNER JOIN ".get_school_db().".staff s
	ON st.staff_id=s.staff_id
	INNER JOIN ".get_school_db().".subject subj
	ON st.subject_id=subj.subject_id
	INNER JOIN ".get_school_db().".class_section cs
	ON st.section_id=cs.section_id
	INNER JOIN ".get_school_db().".class 
	ON class.class_id = cs.class_id
	INNER JOIN ".get_school_db().".departments d on d.departments_id = class.departments_id
	 WHERE st.school_id=".$_SESSION['school_id']."  AND s.user_login_detail_id	=".$login_detail_id." "; 
$subs_array=$this->db->query($pp)->result_array();
$asign_array=array();

if(count($subs_array) > 0)
{
	foreach($subs_array as $asign)
	{
		$asign_array[$asign['date']][$asign['period_no']]=array(
		'subject_name'=>$asign['subject_name'].' - '.$asign['code'],
		'class'=>$asign['class_name'].' - '.$asign['section_name']
		
		);
		$period_no_asign[$asign['period_no']] =$asign['period_no'];
	}
}
	
	  
		
$period_no = array();
$duration_arr=array();
$period_max_new=array();
$virtual_id = array();
if(sizeof($result)>0)
{
    //print_r($result);
	foreach ($result as $row) 
	{
	    $virtual_id[$row['day']][$row['period_no']] = $row['subject_id'].'/'.$row['class_routine_id'];
		$timetable_arr[$row['day']][$row['period_no']] = $row['name'].' - '.$row['code'];	
		$dcs_arr[$row['day']][$row['period_no']] =$row['class'].' - '.$row['class_section'];	
		$period_no[$row['period_no']] =$row['period_no'];
		$duration_arr[$row['day']][$row['period_no']]['duration']=$row['duration'];
		$duration_arr[$row['day']][$row['period_no']]['default_period_duration']=$row['period_duration'];
		$period_start_time=$row['period_start_time'];
		$period_start_time= substr($period_start_time, 0, -3);
		$period_end_time=$row['period_end_time'];
		$period_end_time= substr($period_end_time, 0, -3);
		$duration_arr[$row['day']][$row['period_no']]['period_start_time']=$period_start_time;
		$duration_arr[$row['day']][$row['period_no']]['period_end_time']=$period_end_time;
	}
	//print_r( $_SESSION);
	
?>
<style>
	.highlight_bg 
    {
        background-color: #c1c0c0;
        color:green;
    }
	.panel-default > .panel-heading + .panel-collapse .panel-body 
	{
	    border-top-color: #00a1de;
	    border-top: 2px solid #006f9c;
	}
</style>

<div class="col-md-12" data-step="1" data-position='top' data-intro="Teacher's Time Table">
		<div class="tab-content">   
            <div class="tab-pane active" id="list">
				<div class="panel-group joined" id="accordion-test-2">
				<table cellpadding="0" cellspacing="0" border="0"  class="table table-bordered table-responsive">
					<thead>
                    	<tr>
	                    	<th style="background:#29638d; color:#ffffff;"><?php echo get_phrase('days');?></th>
		                    <?php	
		                    
		                    $period_max = max($period_no);	
		                    $period_max_asign = max($period_no_asign);
		                    if($period_max > $period_max_asign)
		                    {
								$period_max_new=$period_max;
							}
							else
							{
								$period_max_new=$period_max_asign;
							}
							
							
							for ($i = 1; $i<=$period_max_new; $i++ )
                    		{?>
	                    		<th style="background:#29638d; color:#ffffff;"><?php echo $i; ?></th>
                    		<?php
                    		}
                    		?>
	                    </tr>
                    </thead>
                    <tbody>
						
						<?php 
	                    foreach ($timetable_arr as $key => $value) 
	                    {
	                    	$current="";
	                    	$current1="";
	                    	$weekend = "";
	                    	$day_class = (strtolower($key) == strtolower(date('l'))) ? 'highlight_bg' : '';
	                    	if(get_phrase($key)==$current_date)
							{
					
								$current=$custom_css[1];
							} 
							
							if($key == 'saturday' || $key ==  'sunday')
							{
							    $weekend ="weekend";
							}
							
							$dd=date("Y-m-d",strtotime($key.' this week'));
							$q1="select * from ".get_school_db().".holiday where start_date<='$dd' and end_date>='$dd' AND school_id=".$_SESSION['school_id']." ";    
							$qurrr=$this->db->query($q1)->result_array();
							if(count($qurrr)>0){
							    $current1=$custom_css[2];
							    
							} 
            
							echo '<tr class="'.$current.' '.$current1.' '.$weekend.' ">';
                    		echo "<td>".ucfirst($key).'<br>'.convert_date(date("Y-m-d",strtotime($key.' this week')))."</td>";
                    		for ($i = 1; $i<=$period_max_new; $i++ )
                    		{?>
	                    		<td>
	                    		<?php
	                    		if(isset($value[$i])){
	                    		    echo $value[$i]; 
	                    		}
	                    		
	                    		if(isset($dcs_arr[$key][$i])){
	                    		    echo '<br>'.$dcs_arr[$key][$i];
	                    		}
	                    		
	                    		
	                    		
	                    		
	            if(isset($duration_arr[$key][$i]['period_start_time']) && ($duration_arr[$key][$i]['period_start_time'])>0)
				{
				    //date format applied by hammad
					echo "<br>". date("h:i", (strtotime($duration_arr[$key][$i]['period_start_time'])))." - ". date("h:i", (strtotime($duration_arr[$key][$i]['period_end_time'])));
					
				}      		
				if(isset($duration_arr[$key][$i]['duration']) && ($duration_arr[$key][$i]['duration'])>0)
				{
					echo " (".$duration_arr[$key][$i]['duration']." ".get_phrase('mins').")";
					
				}
				elseif(isset($duration_arr[$key][$i]['default_period_duration']) && ($duration_arr[$key][$i]['default_period_duration'])>0)
				{
					echo " (".$duration_arr[$key][$i]['default_period_duration']." ".get_phrase('mins').")";
									
				} 
				
				// comment by hammad
				// $start_t = date("H:i", (strtotime($duration_arr[$key][$i]["period_start_time"])-(15 * 60)));
				// $end_t = date("H:i", (strtotime($duration_arr[$key][$i]["period_end_time"])-(15 * 60)));
				// $duration = $duration_arr[$key][$i]['default_period_duration'];
				// $current_t = date("h:i", strtotime("now")); //13:00
				
			
				// new format by hammad
				if(isset($duration_arr[$key][$i]["period_start_time"])){
				    $start_t_1 = $duration_arr[$key][$i]["period_start_time"];
				}
				
				if(isset($duration_arr[$key][$i]["period_end_time"])){
				    $end_t_1 = $duration_arr[$key][$i]["period_end_time"];
				}
				
				if(isset($duration_arr[$key][$i]['default_period_duration'])){
				    	$duration_1 = $duration_arr[$key][$i]['default_period_duration'];
				}
				
				$current_t_1 = date("H:i", strtotime("now")); //13:00
				
				
				
				
				if(isset($virtual_id[$key][$i]) And $key == strtolower(date("l")) And ($current_t_1 >= $start_t_1 And $current_t_1 <= $end_t_1))
				{
				    $subject_name = str_replace ('&', 'and', $value[$i]);
				    echo '<br><strong><a href="#" onclick="show_modal('.$i.')" class="create_vc_btn">Create VC</a></strong><br><br>';
				    
				    echo '<div class="modal fade in" id="'.$i.'" aria-hidden="false" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content" style="margin-top:100px;">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <div class="modal-header">
                                    <h4 class="modal-title" style="text-align:center;">You want to record this session?</h4>
                                </div>
                                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                    <a  target="_blank" href="'.base_url().'teacher/create_virtual_class/'.$virtual_id[$key][$i].'/'.$duration_1.'/true/'.$subject_name.'/'.$dcs_arr[$key][$i].'/'.$login_detail_id.'" class="modal_save_btn" onclick="goAndClose('.$i.')">Yes</a>
                                    <a  target="_blank" href="'.base_url().'teacher/create_virtual_class/'.$virtual_id[$key][$i].'/'.$duration_1.'/false/'.$subject_name.'/'.$dcs_arr[$key][$i].'/'.$login_detail_id.'" class="modal_cancel_btn" onclick="goAndClose('.$i.')">No</a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>';
				    
				}
        		if(isset($asign_array[$dd][$i]))
        		{
        			echo "<span class='red'>";
        			echo "<br>";
        			echo get_phrase("assigned_subject");
        			echo "<br>";
					echo $asign_array[$dd][$i]['subject_name'];
					echo "<br>";
					echo $asign_array[$dd][$i]['class'];
					echo "</span>";
					
				}
	            ?>
	            </td>
                    		<?php
                    		}
                    		echo '</tr>';
                    		
	                    }
	                    
						?>
	                </tbody>
                </table>
				</div>
			</div>
			
			<div class="row p-0"> 
              <div class="col-sm-12 py-4">
                
                <div class="present-legend legend-attendance pull-left"> </div>
            	<div class="pull-left"> Present</div>
            	
            	<div class="absent-legend legend-attendance pull-left"> </div>
                <div class="pull-left"> Absent</div>
            	 
                <div class="leave-legend legend-attendance pull-left"> </div>
                <div class="pull-left">Leave</div>
                
            	<div class="weekend-legend legend-attendance pull-left"> </div>
            	<div class="pull-left"> Weekend</div>
            
            	<div class="holiday-legend legend-attendance pull-left"> </div>
            	<div class="pull-left"> Holiday</div>
             
            	<div class="today-legend legend-attendance pull-left "></div>
            	<div class="pull-left"> Today</div>
            	
              </div>   
            </div>
	</div>
</div>

<?php }else{ ?>
    <div class="text-center">
        <i class="fas fa-table" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
        <h2><b>Time Table is Not Created Yet</b></h2>
    </div>
<?php } ?>

<script>
   //$('.showhide').hide();
   
    function show_modal(id)
	{
		jQuery('#'+id).modal('show', {backdrop: 'static'});
	}
	
    function goAndClose(id){
		jQuery('#'+id).modal('hide');
	}
    var auto_refresh = setInterval(
        function () {
            location.reload();
        }, 300000);
</script>