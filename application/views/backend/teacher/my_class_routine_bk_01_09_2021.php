<?php
    $custom_css      =  array(1=>'current-day',2=>'holiday');
    $current_date    =  date("l");
    $yearly_term_id  =  $_SESSION['yearly_term_id'];
    $login_detail_id =  $_SESSION['login_detail_id'];
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('my_timetable'); ?>
        </h3>
    </div>
</div>

<?php

 $routine1 = array();
 $days_arr = array(get_phrase('monday'), get_phrase('tuesday'), get_phrase('wednesday'), get_phrase('thursday'), get_phrase('friday'), get_phrase('saturday'), get_phrase('sunday'));

 $result   = $this->db->query("select crs.assembly_duration , crs.break_duration , crs.break_after_period , crs.c_rout_sett_id , crs.break_duration_after_every_period  , s.subject_id, s.name, s.code, cr.class_routine_id, cr.day, cr.period_no, d.title as department, class.name as class, cs.title as class_section,cr.duration, crs.period_duration,cr.period_start_time,cr.period_end_time
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
                        	   where staff.user_login_detail_id = $login_detail_id and crs.school_id = ".$_SESSION['school_id']." 
                        	   group by day, period_no order by day, period_no")->result_array();
                        	
 
$timetable_arr = array(
    'monday'    => array(), 
    'tuesday'   => array(), 
    'wednesday' => array(), 
    'thursday'  => array(), 
    'friday'    => array(), 
    'saturday'  => array(), 
    'sunday'    => array()
);

$dcs_arr = array(
    'monday'   => array(), 
    'tuesday'  => array(), 
    'wednesday'=> array(), 
    'thursday' => array(), 
    'friday'   => array(), 
    'saturday' => array(), 
    'sunday'   => array()
);


$custom_color = array(
    1 => '#29638d',
    2 => '#6eb6ea'
);

$week_arr = array();
$week_day = array();
$week_arr = array(get_phrase("monday"),get_phrase("tuesday"),get_phrase("wednesday"),get_phrase("thursday"),get_phrase("friday"),get_phrase("saturday"),get_phrase("sunday"));	

for($i=0;$i<6;$i++)
{
	$week_arr[$i];
	$week_day[]  = date("Y-m-d",strtotime($week_arr[$i].' this week'));
}


$pp=" SELECT st.*,st.date as date, s.name as teacher_name,s.staff_id as teacher_id,subj.name as subject_name,subj.code as code,cs.class_id as class_id, 
      cs.title as section_name,class.name as class_name,d.title as dept_name FROM ".get_school_db().".substitute_teacher st 
	  INNER JOIN ".get_school_db().".staff s ON st.staff_id=s.staff_id INNER JOIN ".get_school_db().".subject subj ON st.subject_id=subj.subject_id
	  INNER JOIN ".get_school_db().".class_section cs ON st.section_id=cs.section_id INNER JOIN ".get_school_db().".class 
      ON class.class_id = cs.class_id INNER JOIN ".get_school_db().".departments d on d.departments_id = class.departments_id
	  WHERE st.school_id=".$_SESSION['school_id']."  AND s.user_login_detail_id	=".$login_detail_id." ";
	  
$subs_array  = $this->db->query($pp)->result_array();

$asign_array = array();
if(count($subs_array) > 0)
{
	foreach($subs_array as $asign)
	{
		$asign_array[$asign['date']][$asign['period_no']] = array( 'subject_name'=>$asign['subject_name'].' - '.$asign['code'], 'class'=>$asign['class_name'].' - '.$asign['section_name']);
		$period_no_asign[$asign['period_no']]             = $asign['period_no'];
	}
}
	
				
$period_no      =  array();
$duration_arr   =  array();
$period_max_new =  array();
$virtual_id     =  array();

if(sizeof($result)>0)
{
    $class_routine_id_arr = array();
    
	foreach ($result as $row) 
	{
	    
	    $c_rout_sett_id                                                                      =  $row['c_rout_sett_id'];
	    $class_routine_id_arr[] = $c_rout_sett_id;
	    
	    $virtual_id[$row['day']][$row['period_no']]                                          =  $row['subject_id'].'/'.$row['class_routine_id'];
		$timetable_arr[$row['day']][$row['period_no']]                                       =  $row['name'].' - '.$row['code'];	
		$dcs_arr[$row['day']][$row['period_no']]                                             =  $row['class'].' - '.$row['class_section'];	
		$period_no[$row['period_no']]                                                        =  $row['period_no'];
		$duration_arr[$row['day']][$row['period_no']]['duration']                            =  $row['duration'];
		$duration_arr[$row['day']][$row['period_no']]['default_period_duration']             =  $row['period_duration'];
		$period_start_time                                                                   =  $row['period_start_time'];
		$period_start_time                                                                   =  substr($period_start_time, 0, -3);
		$period_end_time                                                                     =  $row['period_end_time'];
		$period_end_time                                                                     =  substr($period_end_time, 0, -3);
		$duration_arr[$row['day']][$row['period_no']]['period_start_time']                   =  $period_start_time;
		$duration_arr[$row['day']][$row['period_no']]['period_end_time']                     =  $period_end_time;
		$duration_arr[$row['day']][$row['period_no']]['break_duration_after_every_period']   =  ($row['break_duration_after_every_period'] > 0) ? $row['break_duration_after_every_period'] : 0;
		

		$assembly_duration                                                                   =   $row['assembly_duration'];
        $break_duration                                                                      =   $row['break_duration'];
        $break_after_period                                                                  =   $row['break_after_period'];
        
	
	}
	
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
				
    				<table cellpadding="0" cellspacing="0" border="0"  class="table table-bordered">
    					<thead>
                        	<tr>
    	                    	<th style="background:#29638d; color:#ffffff;"><?php echo get_phrase('days');?></th>
    		                    <?php	
    		                    
    		                    $period_max       = max($period_no);	
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
                        		{
                        		?>
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
    	                       
    	                    	
    	                    	$current   = "";
    	                    	$current1  = "";
    	                    	$day_class = (strtolower($key) == strtolower(date('l'))) ? 'highlight_bg' : '';
    	                    	if(get_phrase($key)==$current_date)
    							{
    								$current=$custom_css[1];
    							} 
    							
    							$dd     = date("Y-m-d",strtotime($key.' this week'));
    							$q1     = "select * from ".get_school_db().".holiday where start_date<='$dd' and end_date>='$dd' AND school_id=".$_SESSION['school_id']." ";    
    							$qurrr  = $this->db->query($q1)->result_array();
    							
    							if(count($qurrr)>0){
    							    $current1=$custom_css[2];
    							} 
                
    							echo '<tr class="'.$current.' '.$current1.'">';
                        		echo "<td>".ucfirst($key).'<br>'.convert_date(date("Y-m-d",strtotime($key.' this week')))."</td>";
                        		
                        		
                        		
                        		for ($i = 1; $i<=$period_max_new; $i++ )
                        		{
                        		    
                            ?>
    	                    	<td>
    	                    	    
    	                    <?php 
    	                   // echo "<pre>";
    	                   // print_r($class_routine_id_arr);
    	                   // exit;
    	                    for($j = 0 ; $j < count($class_routine_id_arr) ; $j++){
    	                        
    	                    
    	                   
    	                    	$get_cr_id = $this->db->query("select cr.*,cs.school_id,cs.c_rout_sett_id FROM ".get_school_db().".class_routine cr 
                                                        	    RIGHT JOIN ".get_school_db().".class_routine_settings cs on cs.c_rout_sett_id=cr.c_rout_sett_id 
                                                        	    inner join ".get_school_db().".subject_teacher st on st.subject_id =  cr.subject_id
                                                                inner join ".get_school_db().".time_table_subject_teacher ttst on st.subject_teacher_id =  ttst.subject_teacher_id
                                                                inner join ".get_school_db().".subject_section SS on SS.subject_id = st.subject_id
                                                        	    WHERE  
                                                            	cs.school_id=".$_SESSION['school_id']." 
                                                            	AND cr.day = '$key'
                                                            	AND cr.period_no = ".$i."
                                                            	AND cr.c_rout_sett_id = ".$class_routine_id_arr[$j]."
                                                            	AND st.teacher_id = ".$_SESSION['user_id']." GROUP BY cr.subject_id ")->result_array();
                                                            	
                                  }                          // 	echo $this->db->last_query();
                                            
                                foreach($get_cr_id as $get_cr_id_result)
                                {
                                    echo get_subject_name($get_cr_id_result['subject_id']); 
                                    echo "<br>".$dcs_arr[$key][$i] . '<br>'; 
                                }
    	                    
    	                   
    	            
    	
                	            if(isset($duration_arr[$key][$i]['period_start_time']) && ($duration_arr[$key][$i]['period_start_time'])>0)
                				{
                				    
                				    $break_afer_period = $duration_arr[$key][$i]['break_duration_after_every_period'];
                				    
                				    if( $break_afer_period > 0)
                                    {
                                        if($i > 1){
                                            $start = strtotime($duration_arr[$key][$i]['period_start_time']) + strtotime(minutes_to_hh_mm($break_afer_period)) - strtotime('00:00');
                                            $start = date('h:i', $start);
                                            echo "<br>". $start . " - ";
                                        }
                                        else
                                        {
                                             $start = strtotime($duration_arr[$key][$i]['period_start_time']) ;
                                            $start = date('h:i', $start);
                                            echo "<br>". $start ." - ";
                                        }
                                    }
                                    else
                                    {
                                      $start = strtotime($duration_arr[$key][$i]['period_start_time']) ;
                                            $start = date('h:i', $start);
                                            echo "<br>". $start ." - ";
                                    }
                                    $end_vtime = strtotime( $duration_arr[$key][$i]['period_end_time']);
                                    $end_vtime = date('h:i', $end_vtime);
                                    echo $end_vtime;
                					
                				}
                				
                				if(isset($duration_arr[$key][$i]['duration']) && ($duration_arr[$key][$i]['duration'])>0)
                				{
                					echo " (".$duration_arr[$key][$i]['duration']." ".get_phrase('mins').")";
                					
                				}
                				
                				elseif(isset($duration_arr[$key][$i]['default_period_duration']) && ($duration_arr[$key][$i]['default_period_duration'])>0)
                				{
                					echo " (".$duration_arr[$key][$i]['default_period_duration']." ".get_phrase('mins').")";
                									
                				} 
                
                				if( $break_afer_period > 0)
                                {
                                    if($i > 1){
                                        $start_t   = strtotime($duration_arr[$key][$i]['period_start_time']) + strtotime(minutes_to_hh_mm($break_afer_period)) - strtotime('00:05');
                                        $start_t   = date('H:i', $start_t);
                                    }
                                    else
                                    {
                                        $start_t   = date("H:i", (strtotime($duration_arr[$key][$i]["period_start_time"])-(15 * 60)));
                                    }
                                }
                                
                                else
                				{
                				    $start_t   = date("H:i", (strtotime($duration_arr[$key][$i]["period_start_time"])-(15 * 60)));
                				}
    				
                				$end_t      =   date("H:i", (strtotime($duration_arr[$key][$i]["period_end_time"])-(15 * 60)));
                				$duration   =   $duration_arr[$key][$i]['default_period_duration'];
                				$current_t  =   date("H:i", strtotime("now")); //13:00

                				if(isset($virtual_id[$key][$i]) And $key == strtolower(date("l")) And ($current_t >= $start_t And $current_t <= $end_t)){
                				    $subject_name = str_replace ('&', 'and', $value[$i]);

                				    echo '<br><strong><a href="#" onclick="show_modal('.$i.')" style="color:#29638d;">Create VC</a></strong>';
                				    
                				    echo '<div class="modal fade in" id="'.$i.'" aria-hidden="false" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content" style="margin-top:100px;">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" style="text-align:center;">You want to record this session?</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                                    <a  target="_blank" href="'.base_url().'teacher/create_virtual_class/'.$virtual_id[$key][$i].'/'.$duration.'/true/'.$subject_name.'/'.$dcs_arr[$key][$i].'/'.$login_detail_id.'" class="btn btn-info" onclick="goAndClose('.$i.')">Yes</a>
                                                    <a  target="_blank" href="'.base_url().'teacher/create_virtual_class/'.$virtual_id[$key][$i].'/'.$duration.'/false/'.$subject_name.'/'.$dcs_arr[$key][$i].'/'.$login_detail_id.'" class="btn btn-danger" onclick="goAndClose('.$i.')">No</a>
                                                    
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
	</div>
</div>

<?php }else{ echo get_phrase("no_data_to_show");}?>

<script>

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


