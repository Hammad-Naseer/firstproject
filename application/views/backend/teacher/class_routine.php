<style>
    .panel-group.joined>.panel>.panel-heading {
        background-color: #012b3c ;
    }
    .myttl{
        color:white !important;
    }
</style>

<?php
    $custom_css=array(1=>'current-day',2=>'holiday',3=>'weekend');
    $current_date=date("l");
    $yearly_term_id = $_SESSION['yearly_term_id'];
    $academic_id = $_SESSION['academic_year_id'];
    $login_detail_id = $_SESSION['login_detail_id'];
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('timetable'); ?>
        </h3>
    </div>
</div>

    <form method="post" action="<?php echo base_url();?>teacher/class_routine" class="form" >
        <div class="row filterContainer">
            <div class="col-lg-4 col-md-4 col-sm-4" data-step="1" data-position='top' data-intro="Select Section">
                <div class="form-group">
                    <label id="select_selection"></label>  
                    <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" required>
                        <?php echo get_teacher_dep_class_section_list($teacher_section, $section_id);?>
                    </select>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-4" data-step="2" data-position='top' data-intro="Press Filter button to get specific records">
                <div class="form-group" style="padding-top: 10px;">
                    <input type="submit" name="submit" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"/>
                    <?php
                    if($filter)
                    {
                    ?>
                        <a href="<?php echo base_url();?>teacher/class_routine" class="btn btn-danger">
                                <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        
    </form>
    <script>
        jQuery(document).ready(function () 
        {
            jQuery('.dcs_list').on('change', function (){
                var id=this.id;
                var selected = jQuery('#'+ id +' :selected');
                var group = selected.parent().attr('label');
                jQuery(this).siblings('label').text(group);
            });
        });
    </script>
<?php
$section_ids = 0;
if (!$filter)
{
	if (count($teacher_section)>0)
		$section_ids = implode(',', $teacher_section);
}
else
	$section_ids = $section_id;
$custom_color=array(1=>'#29638d',2=>'#6eb6ea');
$routine1=array();
$q2 = "select cr.*,cs.*,d.title,d.departments_id,cls.name,cls.class_id,sec.title,sec.section_id FROM ".get_school_db().".class_routine cr 
    RIGHT JOIN ".get_school_db().".class_routine_settings cs on (cs.c_rout_sett_id=cr.c_rout_sett_id and cs.is_active = 1)
    INNER JOIN ".get_school_db().".class_section sec on sec.section_id=cs.section_id 
    INNER JOIN ".get_school_db().".class  cls on sec.class_id=cls.class_id 
    INNER JOIN ".get_school_db().".departments  d on cls.departments_id=d.departments_id 
    WHERE 
    cs.school_id=".$_SESSION['school_id']." 
    AND sec.section_id in (".$section_ids.")
    ";

$result=$this->db->query($q2)->result_array();
if(sizeof($result)>0)
{
?>
<style>
	.panel-default > .panel-heading + .panel-collapse .panel-body 
	{
	    border-top-color: #00a1de;
	    border-top: 2px solid #006f9c;
	}
</style>
<div class="col-md-12" data-step="3" data-position='bottom' data-intro="Class Timetable">
		<div class="tab-content">   
            <div class="tab-pane active" id="list">
				<div class="panel-group joined" id="accordion-test-2">
				
				<?php
				foreach($result as $row)
				{
					$routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['subject_id']=$row['subject_id'];
					$routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['class_routine_id']=$row['class_routine_id'];
					$routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['duration']=$row['duration'];
	                $routine1[$row['c_rout_sett_id']][$row['section_id']]['default_period_duration']=$row['period_duration'];
				}

				$toggle = true;
				$settingsRes=$this->db->query(" select cs.*,d.title as department_name,cls.name as class_name, sec.title as section_name
			 		from ".get_school_db().".class_routine_settings cs 
			 		inner join ".get_school_db().".class_section  sec on sec.section_id=cs.section_id 
			 		inner join ".get_school_db().".class  cls on sec.class_id=cls.class_id 
			 		inner join ".get_school_db().".departments  d on cls.departments_id=d.departments_id 
			 		WHERE cs.school_id=".$_SESSION['school_id']." 
			 		and sec.section_id in (".$section_ids.")
			 		and cs.is_active = 1
			 		ORDER BY department_name,class_name,section_name ")->result_array();

				$cnt = 0;
				
				foreach($settingsRes as $row)
				{
					$subject_arr=array();
					$cnt++;
					$no_of_periods=$row['no_of_periods'];
					$period_duration=$row['period_duration'];
					$start_time=strpos($row['start_time'],':')?$row['start_time']:($row['start_time'].':00');
					$end_time=strpos($row['end_time'],':')?$row['end_time']:($row['end_time'].':00');
					$assembly_duration=$row['assembly_duration'];
					$break_duration=$row['break_duration'];
					$break_after_period=$row['break_after_period'];
					$c_rout_setting_id=$row['c_rout_sett_id'];
					$period_duration_type=$row['period_duration_type'];
					$section_id=$row['section_id'];
					$department_id=$row['departments_id'];
					$class_id=$row['class_id'];
					$period_array=array();
					//$hierarchy = section_hierarchy($row['section_id']);
					$department_class=$row['department_name'].' - '.$row['class_name'].' - '.$row['section_name'];
					?>
                   <div class="panel panel-default">
                        <div class="panel-heading">
                        	<h4 class="panel-title">
                                <a data-toggle="collapse"  href="#collapse<?php echo $cnt.'-'.$row['section_id'];?>"> <!-- data-parent="#accordion-test-2" -->
                                    <i class="fa fa-table" style="color: white !important;" aria-expanded="true"></i>
                                    <span class="myttl"><?php echo $department_class; ?></span>
                                    <span style="color:white;font-size:12px;">              
                                        <?php echo $row['title'].' ('.date('d-m-Y',strtotime($row['start_date'])).' to '.date('d-m-Y',strtotime($row['end_date'])).') ';?>
                                    </span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?php echo $cnt.'-'.$row['section_id'];?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <table cellpadding="0" cellspacing="0" border="0"  class="table table-bordered  table-responsive">
                                <tr style="text-align: center; font-weight:bold;">
                                    <th class="crth" style=" background-color: #c7d2da !important;"><strong><?php echo get_phrase('period');?></strong>
                                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                    </th>
                                     <?php 
                        				if($assembly_duration > 0)
                        				{
                        					echo '<td style="width:20px;background-color:'.$custom_color['1'].'"></td>';
                        				}
                        				for($i=1;$i<=$no_of_periods;$i++)
                        				{
                        					echo '<td style=" background-color: rgb(199, 210, 218) !important;">'.$i.'</td>';
                        					
                        					if(($break_after_period > 0) 
                        					&& ($break_after_period==$i))
                        					{
                        						echo '<th style="width:20px;background-color:'.$custom_color['2'].'"></th>';
                        					}
                        				}	
    								?>
    							</tr>
    				            <?php
    				            	$count=1;
    		                       	$custom_color_count=1;
                            		$time_arr = array();
    		                    ?>
    		                    <tbody>
    		                    <?php 
                                    for($d=1;$d<=7;$d++)
                                    {
                                    	$current="";
                                    	$current1="";
                                    	$period = strtotime($start_time);
        				                $period_new = date('H:i', $period);
                                    	$custom_color_count=1;
        		                       
        	                            if($d==1)$day='monday';
        	                            else if($d==2)$day='tuesday';
        	                            else if($d==3)$day='wednesday';
        	                            else if($d==4)$day='thursday';
        	                            else if($d==5)$day='friday';
        	                            else if($d==6)$day='saturday';
        	                            else if($d==7)$day='sunday';
        	                            if(ucfirst($day)==$current_date)
        								{
        									$current=$custom_css[1];
        								}
        								if($day == 'saturday' || $day == 'sunday')
        								{
        								    $current=$custom_css[3];
        								}
        								$dd=date("Y-m-d",strtotime($day.' this week'));
         $q1="select * from ".get_school_db().".holiday where start_date<='$dd' and end_date>='$dd' AND school_id=".$_SESSION['school_id']." ";    
        $qurrr=$this->db->query($q1)->result_array();
            if(count($qurrr)>0){
                $current1=$custom_css[2];
                    }
        								echo '<tr class="'.$current.' '.$current1.'">';   
        		                        ?>
        		                       
        		                            <td width="80"><?php echo ucfirst($day).'<br>'.convert_date(date("Y-m-d",strtotime($day.' this week')));?></td>
        		                            <?php 
        		        if($assembly_duration > 0)
        				{
        					echo '<td style="background-color:';
        					echo $custom_color['1'];
        					echo'" ></td>';
        					$period = strtotime($start_time) + 
        		    		strtotime(minutes_to_hh_mm($assembly_duration)) -
        		    		strtotime('00:00');
        		    		$period_new = date('H:i', $period);
        				}
        				
        				for($i=1;$i<=$no_of_periods;$i++)
        		        {
        		            $start=0;
        		            $end=0;
        					
        					echo '<td style="vertical-align:top;"> ';
        							
        					$val=$i;
        					$day = strtolower($day);
        				
        					$subject_id=$routine1[$c_rout_setting_id][$section_id][$day][$val]['subject_id'];
        						
        					if(isset($routine1[$c_rout_setting_id][$section_id][$day][$val]['duration']) 
        						&& $routine1[$c_rout_setting_id][$section_id][$day][$val]['duration']>0)
        					{
        						
        						$duration=$routine1[$c_rout_setting_id][$section_id][$day][$val]['duration'];
        						$start=$period_new;
        						
        						$period_new = strtotime($period_new) + 
        						strtotime(minutes_to_hh_mm($duration)) - 
        						strtotime('00:00');
        								
        						$period_new = date('h:i', $period_new);
        						
        						$end=$period_new;
        								
        						echo $start .' - '.$end;
        						echo "<br>";
        						echo " (".$duration." min) ";
        					}
        					else
        					{
        						$start=$period_new;
        													
        					$period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($routine1[$c_rout_setting_id][$section_id]['default_period_duration'])) - strtotime('00:00');
        					
        					$period_new = date('h:i', $period_new);
        					$end=$period_new;
        							
        							echo $start .' - '.$end;
        							echo "<br>";
        							echo " (".$routine1[$c_rout_setting_id][$section_id]['default_period_duration']." min) ";
        							echo "<br>";
        							}
        ////////////////////////////////////////
        							
        							if(isset($subject_id) && $subject_id > 0)
        							{
        								$class_routine_id=$routine1[$c_rout_setting_id][$section_id][$day][$val]['class_routine_id'];
        
        							  	$compQuery=" select subject_components from ".get_school_db().".class_routine where class_routine_id=".$class_routine_id."";
        								$compRes = $this->db->query($compQuery)->result_array();
        							 	$comps=$compRes[0]['subject_components'];
        
        							   	$query3=" select sta.name AS teacher_name from ".get_school_db().".time_table_subject_teacher ttst inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join ".get_school_db().".staff sta on sta.staff_id=st.teacher_id inner join ".get_school_db().".subject s on s.subject_id=st.subject_id where ttst.school_id=".$_SESSION['school_id']."  and st.subject_id=".$subject_id." and class_routine_id=".$class_routine_id."";
        								$res = $this->db->query($query3)->result_array();
        								?>
        		                        <div class="btn-group" id="cr<?php echo $class_routine_id;?>">
        		                           
        		                            
        		                            
        		                           
        		                            
        		                            <div style="float:left">
        		                                <?php //if(sizeof($res)>0){?>
        		                               
        		                                <?php //}?>
        		                                <?php echo get_subject_name($subject_id);echo '<br/>'.subject_components($comps);?>
        		                            </div>
        		                            <div  style="float:left">
        		                                <?php 
        										$teachers=array();
        										if(sizeof($res)>0)
        										{
        											echo "-";
        											
        											foreach($res as $rows)
        											{
        												$teachers[]=$link.$rows['teacher_name'];
        											}
        
        											echo implode('<br/>',$teachers);
        										}
        										else{
        												
        										}
        										?>
        		                            </div>
        		                        </div>
        		                    <?php 
        		                	}
        ///////////////////////////////////////////////////////////
        		               // }
        		               
        		               if(($break_after_period > 0) 
        		               	&& ($break_after_period==$i))
        		               	{
        						echo '<td style="background-color:';
        						echo $custom_color['2'];
        						echo'" ></td>';
        						
        						$period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration)) - strtotime('00:00');
        						$period_new = date('h:i', $period_new);
        					}
        					}
        		                           /* foreach($period_array as $key=>$value)			
        		                            {
        									if ($value['i'] ==0)
        									{
        										
        										echo '<td style="background-color:';
        															if($value[ 'i']=='0' ) { echo $custom_color[$custom_color_count]; $custom_color_count++; } else { echo '#c7d2da'; }
        															echo'" ></td>';
        									}
        									elseif ($value['i'] >0)
        									{
        										
        										echo '<td style="">';
        										$section_id=$row['section_id'];
        							 			$val=$value['i'];
        										$subject_id=$routine1[$c_rout_setting_id][$section_id][$day][$val]['subject_id'];
        										
        										if(isset($subject_id) && $subject_id > 0)
        										{
        
        									 		$class_routine_id=$routine1[$c_rout_setting_id][$section_id][$day][$val]['class_routine_id'];
        
        									   		$query3=" select sta.staff_id, sta.name AS teacher_name,s.name as name from ".get_school_db().".time_table_subject_teacher ttst inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join ".get_school_db().".staff sta on sta.staff_id=st.teacher_id inner join ".get_school_db().".subject s on s.subject_id=st.subject_id where ttst.school_id=".$_SESSION['school_id']."  and st.subject_id=".$subject_id." and class_routine_id=".$class_routine_id."";
        									   		
        											$res = $this->db->query($query3)->result_array();
        
        										 	if($row['status'] !=1)
        											{ ?>
        												  <div class="btn-group" id="cr<?php echo $class_routine_id;?>">
                                                            <div>
                                                                <?php $subject = get_subject_name($subject_id,1);
        																$subject_arr[$subject_id] = $subject;
        																$subject_title=$subject['name']."-";
        																$subject_code=$subject['code'];
        																
        																echo $subject_code;
                                                                echo "<br/>";?>
                                                            </div>
                                                            <div class="teacher_box">
                                                                <?php 
        	$teachers=array();
        	foreach($res as $rows)
        	{
        		
        		$teachers[]=$rows['teacher_name'];
        	}
        	echo implode('<br/>',$teachers);
        	
        	
        	?>
                                                            </div>
                                                        </div>
                                                        <?php
        											}
        										}
        									}
        									}*/
        									
        									?>
        									</tr>
                                        <?php 
                                        } 
                                ?>
                                </tbody>
                            </table>
                             <div class="subject_detail">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                    	<ul>
                                            <?php
                                                /*
                                                foreach($subject_arr as $key=>$val)
    											{
    											?>
                                                    <li>
                                                    	<i class="fa fa-book" aria-hidden="true"></i>
                                                        <p><?php echo $val['code'];?></p>
                                                        <span><strong><?php echo $val['name'];?></strong></span>
                                                    </li>
                                                <?php
    											}
    											*/
    										?>
                                        </ul>
                                    <div class="assembly">
                                        <span><strong><?php echo get_phrase('start_time');?></strong></span>
                                        <span>
                                        	<?php echo $start_time;?>
                                        </span>	
                                    </div>

                                    <div class="assembly">
                                        <span><strong><?php echo get_phrase('period_duration');?></strong></span>
                                        <span>
                                        	<?php echo $period_duration;?> <?php echo get_phrase('minutes');?>
                                        </span>	
                                    </div>           
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="assembly">
                                        <div></div>
                                                    <span><strong><?php echo get_phrase('assembly');?></strong></span>
                                                    <span><?php echo $assembly_duration;?> <?php echo get_phrase('mins');?></span>
                                                </div>
                                                <div class="break">
                                                	<div></div>
                                                    <span><strong><?php echo get_phrase('break');?></strong></span>
                                                    <span><?php echo $break_duration;?> <?php echo get_phrase('mins');?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                            </div>
                        </div>
                    </div>
					<?php
					}
					?>
  				</div>
			</div>
                       
		</div>
	</div>

<?php }else
									 {
										 ?>
                                    <div class="box-header with-border mgbb">
                                        <h3 class="box-title"> <i class="fa fa-clock-o" aria-hidden="true"></i><?php echo get_phrase('timetable'); ?>  </h3>
                                    </div>
                                    <?php
									}
									?>
									
									

