<div class="mgt35"></div>
<style>
    #nprogress .bar {
        display:none;
    }
    #nprogress .spinner {
        display:none;
    }
    #nprogress .spinner-icon {
        display:none;
    }
	.panel-default > .panel-heading > .panel-title > a {
	    color: #1a6499;
	}

	.panel-default > .panel-heading + .panel-collapse .panel-body {
	    border-top-color: #00a1de;
	    border-top: 2px solid #006f9c;
	}

	.fa-long-arrow-right {
	    color: #000;
	}

	.crth {
	    color: #000;
	    background-color: rgba(3, 58, 98, 0.19)!important;
	    text-align: center;
	}
</style>
<?php
if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-danger alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<?php 	
$custom_css=array(1=>'current-day',2=>'holiday');
$statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
$current_date=date("l");
$custom_color=array(1=>'#29638d',2=>'#6eb6ea'); 
          	
$routine1=array();

$q2="SELECT cr.*,cs.*,date_format(cr.period_start_time,'%H:%i')as period_start_time,date_format(cr.period_end_time,'%H:%i')as period_end_time FROM 
  		".get_school_db().".class_routine cr 
  		right join ".get_school_db().".class_routine_settings cs on (cs.c_rout_sett_id=cr.c_rout_sett_id and cs.is_active = 1)
  		where 
  		cs.school_id=".$_SESSION['school_id']." 
  		and cs.section_id=".$_SESSION['section_id']."
  		";
  		

$result = $this->db->query($q2)->result_array();

//print_r($result);
if(sizeof($result)>0)
{
?>













<div class="row classtimetable">
    
       
     
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar mgt35">
        <h3 class="system_name inline">
            <!--  <i class="entypo-right-circled carrow">
                        </i>-->
            <?php echo get_phrase('time_table');?>
           
        </h3>
    </div>
    
    
    
    
    
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane active" id="list">
                <div class="panel-group joined" id="accordion-test-2">
                    <?php
		foreach($result as $row)
		{
			$routine_arr[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['subject_id']=$row['subject_id'];
			$routine_arr[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['class_routine_id']=$row['class_routine_id'];
			$routine_arr[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['duration']=$row['duration'];
	$routine_arr[$row['c_rout_sett_id']][$row['section_id']]['default_period_duration']=$row['period_duration'];
	
	
	$routine_arr[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['period_start_time']=$row['period_start_time'];
	$routine_arr[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['period_end_time']=$row['period_end_time'];
		}
		/*echo "<pre>";
		print_r($routine_arr);*/

		$toggle = true;
		 
		$settings="select cs.*
			from ".get_school_db().".class_routine_settings cs 
		   	where  
		   	cs.school_id=".$_SESSION['school_id']." 
		   	and cs.is_active = 1
		   	and cs.section_id=".$_SESSION['section_id'];

		
		
	
		$settingsRes=$this->db->query($settings)->result_array();
		$cnt = 0;
		foreach($settingsRes as $row)
		{
			$cnt++;
			$no_of_periods=$row['no_of_periods'];
			$period_duration=$row['period_duration'];
			$start_time=$row['start_time'];
			$end_time=$row['end_time'];
			$assembly_duration=$row['assembly_duration'];
			$break_duration=$row['break_duration'];
			$break_after_period=$row['break_after_period'];
			$c_rout_setting_id=$row['c_rout_sett_id'];
			

			$hierarchy=section_hierarchy($row['section_id']);
			
			
						?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <div <?php echo $cnt.'-'.$row['section_id'];?>>
                                        <i class="fa fa-table" aria-hidden="true"></i>
                                        <?php 
				echo $row['title'];?>
                                        <span style="font-size:14px; color:#000;">
									<?php echo ''.convert_date($row['start_date']).' to '.convert_date($row['end_date']).'';
										
				?>
                                 </span>
                                    </div>
                                </h4>
                            </div>
                            <div <?php echo $cnt.'-'.$row['section_id'];?> class="panel-collapse collapse <?php if($toggle){echo 'in';$toggle=false;}?>">
                                <div class="panel-body">
                                    <table class="table  table-bordered " style="border:1px solid #000;">
                                        <tr style="text-align: center; font-weight:bold;">
                                        
                                            <th class="crth">
                                                <strong><?php echo get_phrase('period');?></strong>
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
                                        <tr class="text-center">
                                            
                                            <?php
                       $count=1;
                       $custom_color_count=1;
                        $time_arr = array();
                        ?>
                                        </tr>
                                        <tbody>
                                            <?php 
                            for($d=1;$d<=7;$d++)
                            {
                            $current="";
                            $current1="";
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
							//echo $day;	
							$dd=date("Y-m-d",strtotime($day.' this week'));
							$q1="select * from ".get_school_db().".holiday where start_date<='$dd' and end_date>='$dd' AND school_id=".$_SESSION['school_id']." ";    
$qurrr=$this->db->query($q1)->result_array();
    if(count($qurrr)>0){
        $current1=$custom_css[2];
        
            }   
$statuslist_css="";
            if($day=="saturday" or $day=="sunday"){
	
$statuslist_css=$statuslist[4]; 
//echo "style='background-color:#EEE'";	
}	
							
							echo '<tr class="gradeA'.' '.$current.' '.$current1.' '.$statuslist_css.'">'; 
                            ?>
  							<td width="80">
                                 <?php 

				
                                     echo ucfirst($day);
                                     echo '<br>';
                                     echo convert_date(date("Y-m-d",strtotime($day.' this week')));
              
                                     ?>
                                                </td>
      <?php if($assembly_duration > 0)
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
						 	
					$subject_id=$routine_arr[$c_rout_setting_id][$section_id][$day][$val]['subject_id'];
					
					
					
					
					
					
							
					if(isset($routine_arr[$c_rout_setting_id][$section_id][$day][$val]['duration']) 
						&& $routine_arr[$c_rout_setting_id][$section_id][$day][$val]['duration']>0)
					{
						
						$duration=$routine_arr[$c_rout_setting_id][$section_id][$day][$val]['duration'];
						
						
						
						$start=$period_new;
						$period_new = strtotime($period_new) + 
						strtotime(minutes_to_hh_mm($duration)) - 
						strtotime('00:00');
								
						$period_new = date('H:i', $period_new);
						
						$end=$period_new;
								
						echo $start .' - '.$end;
						echo "<br>";
						echo " (".$duration." min) ";
					}
					else
					{
						$start=$period_new;
													
					$period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($routine_arr[$c_rout_setting_id][$section_id]['default_period_duration'])) - strtotime('00:00');
					
					$period_new = date('H:i', $period_new);
					$end=$period_new;
							
							echo $start .' - '.$end;
							echo "<br>";
							echo " (".$routine_arr[$c_rout_setting_id][$section_id]['default_period_duration']." min) ";
							echo "<br>";
					}
					
					
					
					/*if(isset($routine_arr[$c_rout_setting_id][$section_id][$day][$val]['period_start_time']) && ($routine_arr[$c_rout_setting_id][$section_id][$day][$val]['period_start_time'] > 0))
					{
						$period_start_time=$routine_arr[$c_rout_setting_id][$section_id][$day][$val]['period_start_time'];
						$period_end_time=$routine_arr[$c_rout_setting_id][$section_id][$day][$val]['period_end_time'];
						$start=$period_start_time;
						$end = $period_end_time;
						
						echo "start".$start.'--'.$end;
						
					}*/
					
					
////////////////////////////////////////
							
							if(isset($subject_id) && $subject_id > 0)
							{
								$class_routine_id=$routine_arr[$c_rout_setting_id][$section_id][$day][$val]['class_routine_id'];

							  	$compQuery=" select subject_components from ".get_school_db().".class_routine where class_routine_id=".$class_routine_id."";
								$compRes = $this->db->query($compQuery)->result_array();
							 	$comps=$compRes[0]['subject_components'];

							   	$query3=" select ttst.subject_teacher_id as teacher_id, sta.name AS teacher_name from ".get_school_db().".time_table_subject_teacher ttst inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join ".get_school_db().".staff sta on sta.staff_id=st.teacher_id inner join ".get_school_db().".subject s on s.subject_id=st.subject_id where ttst.school_id=".$_SESSION['school_id']."  and st.subject_id=".$subject_id." and class_routine_id=".$class_routine_id."";
								$res = $this->db->query($query3)->result_array();
								?>
		                        <div class="btn-group" id="cr<?php echo $class_routine_id;?>">
		                           
		                            <div style="float:left">
		                                <?php echo get_subject_name($subject_id);echo '<br/>'.subject_components($comps);?>
		                            </div>
		                            <div class="blue" style="float:left">
		                                <?php 
										$teachers=array();
										//$teacher_id = "";
										if(sizeof($res)>0)
										{
											//$link='<a href="#" onclick="showAjaxModal(\''.base_url().'modal/popup/modal_edit_class_routine_teacher/'.$subject_id.'-'.$class_routine_id.'-'.$section_id.'-'.$day.'-'.$val.'-'.$c_rout_setting_id.'\');"><i class="fa fa-pencil" style="    color: #949494 !important;"></i></a>';
											
											foreach($res as $rows)
											{
												$teachers[]=$rows['teacher_name'];
												$teacher_id = $rows['teacher_id'];
											}

											echo implode('<br/>',$teachers);
                            			//print_r($_SESSION);
                            			if($day == strtolower(date("l"))){
											    $current_t = date("H:i", strtotime("now"));
											    $start_t = date("H:i", (strtotime($start)-(5 * 60)));
			                                    $end_t = date("H:i", (strtotime($end)-(5 * 60)));
											    if($current_t >= $end_t){
											        echo "<br><strong><span style='color:#9acc9e;'>Completed</span></strong>";
											    }
											    else{
    											    if($current_t < $start){
    											        $color = "color:#FFE338;";
    											    }
    											    if($current_t >= $start_t And $current_t <= $end_t){
    											        $color = "color:#f56954;";
    											    }
                                    			    echo '<br><strong><a id="showhide_'.$class_routine_id.'_'.$section_id.'_'.$teacher_id.'"  target="_blank" style="'.$color.'" href="'.base_url().'parents/join_virtual_class/'.$class_routine_id.'/'.$section_id.'">Join VC</a></strong>';
											
											        
											    }
											}	
										
										?>
		                            </div>
		                        </div>
		                    <?php 
		                	}
///////////////////////////////////////////////////////////
		               // }
		               
		               if(($break_after_period > 0) 
		               	&& ($break_after_period==$i) && ($break_duration > 0))
		               	{
						echo '<td style="background-color:';
						echo $custom_color['2'];
						echo'" ></td>';
						
						$period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration)) - strtotime('00:00');
						$period_new = date('H:i', $period_new);
					}
					}
				
				?> 
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				                                       

                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="subject_detail">
                                    	<div class="row">
                                        	<div class="col-lg-6 col-md-6 col-sm-6">
                                            
         <div class="assembly">
<span><strong><?php echo get_phrase('start_time');?></strong></span>
<span>
	<?php echo $start_time;?>
</span>	
</div>

<div class="assembly">
<span><strong><?php echo get_phrase('default_period_duration');?></strong></span>
<span>
	<?php echo $period_duration;?><?php echo get_phrase(' minutes');?>
</span>	
</div>                                                              
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                            	<div class="assembly">
                                                	<div></div>
                                                    <span><strong><?php echo get_phrase('assembly');?></strong></span>
                                                    <span><?php echo $assembly_duration;?><?php echo get_phrase(' minutes');?></span>
                                                </div>
                                                <div class="break">
                                                	<div></div>
                                                    <span><strong><?php echo get_phrase('break');?></strong></span>
                                                    <span><?php echo $break_duration;?><?php echo get_phrase(' minutes');?></span>
                                                </div>
                                                <div class="current-item">
                                                	<div></div>
                                                    <span><?php echo get_phrase('current_day');?></span>
                                                </div>
                                                <div class="leave-item">
                                                	<div></div>
                                                    <span><?php echo get_phrase('holiday');?></span>
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
</div>
<?php }else
									 {
										 ?>
                                    <div class="box-header with-border mgbb">
                                        <h3 class="box-title"> <i class="fa fa-clock-o" aria-hidden="true"></i><?php echo get_phrase('timetable'); ?> </h3>
                                    </div>
                                    <?php
									}
									?>
								
								  
<style>
	.validate-has-error {
	    color: red;
	}

	.panel-default > .panel-heading + .panel-collapse .panel-body {
	    border-top-color: #00a1de;
	    border-top: 2px solid #006f9c;
	}

	.fa {
	    padding-right: 1px !important;
	}

	.fa-plus-square {
	    color: #507895;
	}

	.title {
	    border: 1px solid #eae7e7;
	    min-height: 34px;
	    padding-top: 10px;
	    background-color: rgba(242, 242, 242, 0.35);
	    color: rgb(140, 140, 140);
	    height: auto;
	}

	.adv {
	    width: 50px;
	}

	.tt {
	    background-color: rgba(242, 242, 242, 0.35);
	    min-height: 26px;
	    padding-top: 4px;
	}

	.tt2 {
	    max-height: 400px;
	    overflow-y: auto;
	    overflow-x: hidden;
		margin-bottom:20px;
	}
	.tt2 .col-sm-12{
		line-height:22px;
	}

	.panel-default> .panel-heading+ .panel-collapse .panel-body {
	    border-top-color: #21a9e1;
	    border-top: 2px solid #00a651;
	}

	.panel-body {
	    padding: 9px 22px;
	    /* background-color: #21a9e1; */
	}

	.myfsize {
	    font-size: 11px !important;
	}

	.panel-group .panel> .panel-heading> .panel-title> a {
	    display: inline;
	}

	.fa-file-o {
	    padding-right: 0px !important;
	}

	.panel {
	    margin-bottom: 20px;
	    background-color: #fff;
	    border: 1px solid rgba(0, 0, 0, 0.08);
	    border-radius: 4px;
	    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
	    box-shadow: 0 1px 1px rgba(43, 43, 43, 0.15);
	}

	.panel-title {
	    width: 100%;
	}

	.panel-group.joined> .panel> .panel-heading+ .panel-collapse {
	    background-color: #fff;
	}

	.difl {
	    display: inline;
	    float: left;
	}

	.bt {
	    margin-bottom: -28px;
	    padding-top: 15px;
	    padding-right: 31px;
	}

	.panel-heading> .panel-title {
	    float: left !important;
	    padding: 10px 15px !important;
	    background-color: #FFF;
	}

	.pdr43 {
	    padding-right: 43px;
	}

	.title_collapse {
	    padding: 5px;
	    font-size: 18px;
	    font-color: #000;
	    color: #000;
	    padding: 5px 15px !important;
	    cursor: pointer;
	    border: 1px solid rgba(204, 204, 204, 0.64);
	}

	.crud a {
	    color: #b3b3b3;
	    font-size: 12px;
	    padding-top: 5px;
	}
	.entypo-trash
	{
		color:#F1181B !important;
	}
	
</style>

<script>
   $('.showhide').hide();
   //NProgress.done();
    var auto_refresh = setInterval(
        function () {
           // alert("123");
            $.ajax({
                type: 'POST',
                data: {
                },
                url: "<?php echo base_url();?>parents/updated_vc",
                //dataType: "json",
                dataType: "html",
                success: function(response) {
                    $(".showhide").hide();
                    var jsonObject = $.parseJSON(response);
                    for(var i = 0; i < jsonObject.length; i++) {
                        var obj = jsonObject[i];
                        var teacher_id = obj.subject_teacher_id;
                        var section_id = obj.section_id;
                        var class_routine_id = obj.class_routine_id;
                        //alert('#showhide_'+class_routine_id+'_'+section_id+'_'+teacher_id);
                        $('#showhide_'+class_routine_id+'_'+section_id+'_'+teacher_id).show();
                    }
                }
            });
        }, 1000);
</script>