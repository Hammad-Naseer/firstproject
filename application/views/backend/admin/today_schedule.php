<style>
@media (max-width: 800px)
{
.panel-body {
    margin-bottom: 10px !important;
	}
	}
	.validate-has-error {
	    color: red;
	}

	.panel-default > .panel-heading + .panel-collapse .panel-body {
	    border-top-color: #00a1de;
	    border-top: 2px solid #006f9c;
	}
	.myterm{padding:3px;}
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
	}

	.panel-default> .panel-heading+ .panel-collapse .panel-body {
	    border-top-color: #21a9e1;
	    border-top: 2px solid #00a651;
	}

	.panel-body {
	    padding: 9px 22px;
	    /* background-color: #21a9e1; */
	}
	
	.white2 {
		color: #FFF !important;
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
	.loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #63b7e7;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }

</style>
<?php
$year = date("Y");
$month = date("m");
$day = date("d");
?>
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
<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
   

    <h3 class="system_name inline">
          <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/time-table.png">
        <?php echo get_phrase('today_schedule'); ?>
    </h3>
</div>
<div id="table">
    <div class="col-sm-12">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" style="    padding: 0px;">
        <div class="title_collapse" style="    border-left:  4px solid #4a8cbb;font-size: 16px;">
	        <span style="display:block;" onclick="myFunction('<?php echo 'event';?>')" > 	   <i class="fa fa-calendar" aria-hidden="true"  style="padding-right:10px !important;"></i>Events   <i class="fa fa-caret-down" aria-hidden="true" style="float:right;"></i></span>
	    </div>
	    <div class="panel-group collapse child<?php echo 'event'?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo 'event';?>">
           <?php 
                 
                  $date = "";
                  $query_academic_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as date, ay.* FROM ".get_school_db().".acadmic_year ay
                            where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                            AND School_id = ".$_SESSION['school_id']."
                            UNION
                            SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as date, ay.* FROM ".get_school_db().".acadmic_year ay
                            where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month )  AND (DATE_FORMAT(ay.end_date, '%e') = $day ))
                            AND School_id = ".$_SESSION['school_id']."";
                   $query_academic_result = $this->db->query($query_academic_str)->result_array();
                  // print_r($query_academic_result);
                    if(count($query_academic_result)>0)
                    {
                        foreach ($query_academic_result as $key => $value)
                        {
                            $date .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                        }
        
                    }
                   /* Academic year End */
        
                    /* Academic Terms  start */
        
                   $query_academic_terms_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as term_date, ay.* FROM ".get_school_db().".yearly_terms ay
                            WHERE ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                            AND School_id = ".$_SESSION['school_id']."
                            UNION
                            SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as term_date, ay.* FROM ".get_school_db().".yearly_terms ay
                            where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month )  AND (DATE_FORMAT(ay.end_date, '%e') = $day ))
                            AND School_id = ".$_SESSION['school_id']."";
        
                    $query_academic_terms_result = $this->db->query($query_academic_terms_str)->result_array();
                    //print_r($query_academic_result);
                    if(count($query_academic_terms_result)>0)
                    {
                        foreach ($query_academic_terms_result as $key => $value) {
                            $date .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
                        }
        
                    }
                    /* Academic Terms End */ 
                    /* Vacations  start */
        
                    $query_vacation_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as vacation_date, ay.* FROM ".get_school_db().".holiday ay
                            where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                            AND School_id = ".$_SESSION['school_id']."
                            UNION
                            SELECT 'End' as type, DATE_FORMAT(ay.end_date, '%e') as vacation_date, ay.* FROM ".get_school_db().".holiday ay
                            where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month )  AND (DATE_FORMAT(ay.end_date, '%e') = $day ))
                            AND School_id = ".$_SESSION['school_id']."";
        
                    $query_vacation_result = $this->db->query($query_vacation_str)->result_array();
                    //print_r($query_academic_result);
                    if(count($query_vacation_result)>0)
                    {
                        foreach ($query_vacation_result as $key => $value) {
                            $date .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['title'].'">'.substr($value['title'],0,20).'<span>(' . $value['type'] . ')</span></li>';
        
                        }
        
                    }
                    /* Vacations End */
                    /* exam event start */
        
                    $query_exam_str = "SELECT 'Start' as type, DATE_FORMAT(ay.start_date, '%e') as exam_date, ay.* FROM ".get_school_db().".exam ay
                            where ((DATE_FORMAT(ay.start_date, '%Y') = $year) AND (DATE_FORMAT(ay.start_date, '%c') = $month )  AND (DATE_FORMAT(ay.start_date, '%e') = $day ))
                            AND School_id = ".$_SESSION['school_id']."
                            UNION
                            SELECT 'End' as type, DATE_FORMAT(ay.start_date, '%e') as exam_date, ay.* FROM ".get_school_db().".exam ay
                            where ((DATE_FORMAT(ay.end_date, '%Y') = $year) AND (DATE_FORMAT(ay.end_date, '%c') = $month )  AND (DATE_FORMAT(ay.end_date, '%e') = $day ))
                            AND School_id = ".$_SESSION['school_id']."";
        
                    $query_exam_result = $this->db->query($query_exam_str)->result_array();
                    //print_r($query_academic_result);
                    if(count($query_exam_result)>0) {
                        foreach ($query_exam_result as $key => $value)
                        {
                            $date .='<li type="button" data-toggle="tooltip" data-placement="top" title="'.$value['name'].'">'.substr($value['name'],0,20).'<span>(' . $value['type'] . ')</span></li>';
        
        
                        }
                    }
                    /* exam event end End */
                    /* Notices year start */
                    $query_notices_str = "SELECT DATE_FORMAT(create_timestamp, '%e') as notices_date , notice_title FROM ".get_school_db().".noticeboard
                                                 where ((DATE_FORMAT(create_timestamp, '%Y') = $year) AND (DATE_FORMAT(create_timestamp, '%c') = $month )  AND (DATE_FORMAT(create_timestamp, '%e') = $day ))
                                                 AND School_id = ".$_SESSION['school_id']."";
                    $query_notices_result = $this->db->query($query_notices_str)->result_array();
        
                    if(count($query_notices_result)>0) {
                        foreach ($query_notices_result as $key => $value)
                        {
                            $date .='<li id = "example_'.$key .'" type="button" data-toggle="tooltip" data-placement="top" title="'.$value['notice_title'].'">'.substr($value['notice_title'],0,20).'</li>';
                        }
                    }
                    
                if($date == "")
                    echo get_phrase('no_event_today');
                else
                    echo "<div style='font-size:15px;'>".$date."</div>";
           ?>
        </div>
        <div class="title_collapse" style="    border-left:  4px solid #4a8cbb;font-size: 16px;">
	        <span style="display:block;" onclick="myFunction('<?php echo 'vc';?>')" > 	   <i class="fa fa-newspaper-o" aria-hidden="true"  style="padding-right:10px !important;"></i>Class Routines   <i class="fa fa-caret-down" aria-hidden="true" style="float:right;"></i></span>
	    </div>
	    <div class="panel-group collapse child<?php echo 'vc'?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo 'vc';?>">

            <?php 
            $q2_where = '';
			if( isset($section_id_filter) && $section_id_filter > 0)
			{
			    $q2_where=" AND sec.section_id=".$section_id_filter;
				
			}
			
			

            $q2="SELECT d.title as department,d.departments_id,cls.name as class,cls.class_id,sec.title as section,sec.section_id FROM  ".get_school_db().".class_section  sec  
            	INNER JOIN ".get_school_db().".class  cls on sec.class_id=cls.class_id 
            	INNER JOIN ".get_school_db().".departments d on cls.departments_id=d.departments_id 
            	WHERE 
            	sec.school_id=".$_SESSION['school_id']."  $q2_where 
            	order by d.departments_id, cls.class_id, sec.section_id";

            $result=$this->db->query($q2)->result_array();
			$dcs_arr = array();
            $section_arr = array();
			foreach ($result as $key => $value) 
			{
				$dcs_arr[$value['departments_id']]['name'] = $value['department'];
				$section_arr[$value['departments_id']][$value['section_id']] = $value['class'].' - '.$value['section'];
				//$class_ids[$value['section_id']] = $value['class_id'];
			}
			//$dcs_arr[4]['name'] = 'test';
			//echo '<pre>';
		//	print_r($dcs_arr);
			//print_r($class_ids);

			
//echo $this->db->last_query();
if(sizeof($result)>0)
{
	foreach($dcs_arr as $out_key => $outer_row)
	{
		$depart_id=$out_key;
		
		//$section_hierarchy=section_hierarchy($row1['section_id']);?>
		<div class="title_collapse" style="    border-left:  4px solid #4a8cbb;
    font-size: 16px;">


	        <span style="display:block;" onclick="myFunction('<?php echo 'dep'.$out_key;?>')" > 	   <i class="fa fa-university" aria-hidden="true"  style="padding-right:10px !important;"></i><?php echo $outer_row['name'];?>   <i class="fa fa-caret-down" aria-hidden="true" style="float:right;"></i></span>
	    	</div>
	    	<div class="panel-group collapse child<?php echo 'dep'.$out_key?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo 'dep'.$out_key;?>">

	    	<?php
	    	foreach ($section_arr[$out_key] as $h_key => $hierarchy_arr) 
	    	{
	    		$sect_id=$h_key;
	    		
	    	?>
               

               
                <div class="title_collapse" style=" border-left:   4px solid #35b443;
    font-size: 15px;">
                <i class="fa fa-puzzle-piece" aria-hidden="true" style="padding-right:10px !important; color: #35b443;"></i>
			      
			        <span style="display:inline;" onclick="myFunction(<?php echo $h_key;?>)" >
			        
			          
			           <?php echo $hierarchy_arr;?>
			           
			            
			              </span><i class="fa fa-caret-down" aria-hidden="true" style="    color: #35b443;"></i>
			        
			    </div>
			    <?php
				$toggle = true;
				$settings = "select distinct cs.*,cls.class_id from ".get_school_db().".class_routine_settings cs  INNER JOIN ".get_school_db().".class_section  sec on sec.section_id=cs.section_id INNER JOIN ".get_school_db().".class  cls on sec.class_id=cls.class_id INNER JOIN ".get_school_db().".departments  d on cls.departments_id=d.departments_id 
					where  
					cs.school_id=".$_SESSION['school_id']." 
					and sec.section_id = ".$h_key." AND is_active = 1
					order by cs.start_date desc
					";
				//echo $settings;
				$settingsRes=$this->db->query($settings)->result_array();
				if (count($settingsRes) > 0)
				{
					foreach($settingsRes as $row)
					{
						$no_of_periods=$row['no_of_periods'];
						$period_duration=$row['period_duration'];
						$start_time=strpos($row['start_time'],':')?$row['start_time']:($row['start_time'].':00');
						$end_time=strpos($row['end_time'],':')?$row['end_time']:($row['end_time'].':00');
						$assembly_duration=$row['assembly_duration'];
						$break_duration=$row['break_duration'];
						$break_after_period=$row['break_after_period'];
						$c_rout_setting_id=$row['c_rout_sett_id'];
						$start_date=$row['start_date'];
						$end_date=$row['end_date'];
						$is_active=$row['is_active'];
						$period_array=array();
						$c_id = $row['class_id'];
						
					//	echo $c_id."-".$is_active;
						//$hierarchy=section_hierarchy($row['section_id']);
						$c_day = date('l');
						//echo $c_day;
						$c_time = date('H:i:s');
						//echo $c_time;
						?>
						
			   <div class="panel-group collapse child<?php echo $h_key?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo $h_key;?>">
			            	<div class="panel panel-default" style="padding:8px;border-radius: 0px;">
			                        <!-- tab1 -->
			                        <a onclick="myFunction1('1', <?php echo $sect_id.$c_id;?>)" style="cursor: pointer;">
			                       <div class="col-lg-3 col-md-3 col-sm-3">
                        					<div class="tile-stats tile-green">
                        				
                        						<div class="num" data-start="0" data-end="<?php
                        						$q1 = 'select class_routine.period_start_time, class_routine.period_end_time, staff.name, staff.employee_code, class_routine_settings.section_id, class_section.title, class.name as class_name, virtual_class.virtual_class_name, virtual_class.virtual_class_id  from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
                                                .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                                                 JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
                                                JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
                                                 WHERE  ((DATE_FORMAT(virtual_class.vc_start_time, "%Y") = '. $year .') AND (DATE_FORMAT(virtual_class.vc_start_time, "%c") = '. $month .' ) AND (DATE_FORMAT(virtual_class.vc_start_time, "%e") = '. $day .')) And period_end_time < "'.$c_time.'" AND  day = "'.$c_day.'" AND vc_end_time <> "0000-00-00 00:00:00" AND class_section.section_id = '.$sect_id .' AND class.class_id = '.$c_id;
                                                $class_count1 = $this->db->query($q1)->num_rows();
                                                echo $class_count1;
                        						 ?>" data-postfix="" data-duration="800" data-delay="0">
                        							<?php  echo $class_count1; ?>
                        						</div>
                        						<h3 class="white2" style="font-size: 14px;">
                        							<?php echo get_phrase('classes_completed');?>
                        						</h3>
                        					</div>
                        				</div>
                        				</a>
                                    <!--- tab 2 -->
                                    <a  onclick="myFunction1('2', <?php echo $sect_id.$c_id;?>)" style="cursor: pointer;">
                    					<div class="col-lg-3 col-md-3 col-sm-3">
                    					<div class="tile-stats tile-blue">
                    						<div class="num" data-start="0" data-end="<?php
                                           $q2 = 'select subject.name as subject, subject.code, class_routine.period_start_time, class_routine.period_end_time, staff.name, staff.employee_code, class_routine_settings.section_id, class_section.title, class.name as class_name  from '.get_school_db().'.class_routine JOIN '.get_school_db().'
                                            .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                                            JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
                                            JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
                                             JOIN '.get_school_db().'.subject ON class_routine.subject_id = subject.subject_id
                                             WHERE period_end_time < "'.$c_time.'" AND day = "'.$c_day.'" AND  class_section.section_id = '.$sect_id.' AND class.class_id = '.$c_id.' AND class_routine.class_routine_id NOT IN (select virtual_class.class_routine_id from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
                                            .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                                             JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
                                             WHERE  period_end_time < "'.$c_time.'" AND  day = "'.$c_day.'" AND vc_end_time <> "0000-00-00 00:00:00" AND class_section.section_id = '.$sect_id .' AND class.class_id = '.$c_id.')';
            
                                           $class_count2 = $this->db->query($q2)->num_rows();
                                                echo $class_count2;
                                             ?>" data-postfix="" data-duration="800" data-delay="0">
                    							<?php echo $class_count2; ?>
                    						</div>
                    						<h3 class="white2" style="font-size: 14px;">
                    							<?php echo get_phrase('classes_not_completed');?>
                    						</h3>
                    					</div>
                    				</div>
                                    </a>
                                    <!--- tab 3 -->
                                    
                                    <a onclick="myFunction1('3', <?php echo $sect_id.$c_id;?>)" style="cursor: pointer;">
                    					<div class="col-lg-3 col-md-3 col-sm-3">
                    					<div style="background: #f56954 !important;" class="tile-stats tile-red">
                    						<div class="num" data-start="0" data-end="<?php
                                                $q3 = 'select staff.name, class_routine.period_start_time, class_routine.period_end_time, virtual_class.virtual_class_name, staff.employee_code, virtual_class.virtual_class_id, virtual_class.class_routine_id, class_routine_settings.section_id, class_section.title, class.name as class_name  from '.get_school_db().'.class_routine JOIN '.get_school_db().'.virtual_class ON class_routine.class_routine_id = virtual_class.class_routine_id JOIN '.get_school_db().'
                                                .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                                                JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
                                                JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
                                                 WHERE ((DATE_FORMAT(vc_start_time, "%Y") = '.$year.') AND (DATE_FORMAT(vc_start_time, "%c") = '.$month.' )  AND (DATE_FORMAT(vc_start_time, "%e") = '.$day.' )) And period_end_time < "'.$c_time.'" AND day = "'.$c_day.'" AND class_section.section_id = '.$sect_id.' AND class.class_id = '.$c_id .' AND vc_end_time IS NULL';
                                                 $class_count3 = $this->db->query($q3)->num_rows();
                                                echo $class_count3;
                                                ?>" data-postfix="" data-duration="800" data-delay="0">
                    							<?php echo $class_count3; ?>
                    						</div>
                    						<h3 class="white2"  style="font-size: 14px;">
                    							<?php echo get_phrase('classes_active');?>
                    						</h3>
                    					</div>
                    				</div>
                                    </a>
                                    <!--- tab 4 -->
                                    
                                    <a onclick="myFunction1('4', <?php echo $sect_id.$c_id;?>)" style="cursor: pointer;">
                    					<div class="col-lg-3 col-md-3 col-sm-3">
                    					<div style="background: #FFE338;" class="tile-stats tile-yellow">
                    						<div class="num" data-start="0" data-end="<?php
                    						$q4 = 'select subject.name as subject, subject.code, class_routine.period_start_time, class_routine.period_end_time, staff.name, staff.employee_code, class_routine_settings.section_id, class_section.title, class.name as class_name  from '.get_school_db().'.class_routine JOIN '.get_school_db().'
                                            .class_routine_settings ON class_routine.c_rout_sett_id = class_routine_settings.c_rout_sett_id JOIN  '.get_school_db().'.time_table_subject_teacher ON time_table_subject_teacher.class_routine_id = class_routine.class_routine_id 
                                            JOIN '.get_school_db().'.subject_teacher ON time_table_subject_teacher.subject_teacher_id = subject_teacher.subject_teacher_id JOIN '.get_school_db().'.staff ON subject_teacher.teacher_id = staff.staff_id 
                                            JOIN '.get_school_db().'.class_section ON class_routine_settings.section_id = class_section.section_id JOIN '.get_school_db().'.class ON class.class_id = class_section.class_id
                                            JOIN '.get_school_db().'.subject ON class_routine.subject_id = subject.subject_id
                    						WHERE period_start_time > "'.$c_time.'" AND day = "'.$c_day.'" AND  class_section.section_id = '.$sect_id.' AND class.class_id = '.$c_id;
                                                $class_count4 = $this->db->query($q4)->num_rows();
                                                echo $class_count4;
                    ?>" data-postfix="" data-duration="800" data-delay="0">
                    							<?php echo $class_count4; ?>
                    						</div>
                    						<h3 class="white2"  style="font-size: 14px;">
                    							<?php echo get_phrase('classes_upcoming');?>
                    						</h3>
                    					</div>
                    				</div>
                                    </a>
                        		<!-- tab1---->	
                            	<div class="panel-group collapse child<?php echo '1'.$sect_id.$c_id;?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo '1'.$sect_id.$c_id;?>">
        			            	<div class="panel panel-default" style="padding:8px;border-radius: 0px;">
        			            	    <?php if($class_count1 > 0){ ?>
        			            	    <table class="table table-bordered datatable" id="stud_info_tbl">
                                            <thead>
                                                <tr>
                                                    <th style="width:34px;">#</th>
                                                    <th style="width:27%;"><?php echo get_phrase('class_name');?></th>
                                                    <th style="width:27%;"><?php echo get_phrase('virtual_class_name'); ?></th>
                                                    <th><?php echo get_phrase('teacher_name');?></th>
                                                    <th><?php echo get_phrase('class_time');?></th>
                                                    <th><?php echo get_phrase('status');?></th>
                                                    <th style="width:94px;"><?php echo get_phrase('option');?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                        $j=0;
                                        $classes = $this->db->query($q1)->result_array();
                                        foreach($classes as $row)
                                        {
                                        $j++;
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php  echo $j; ?>
                                                    </td>
                                                    <td>
                                                         <?php echo $row['class_name']." - ".$row['title']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['virtual_class_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['period_start_time']." - " .$row['period_end_time']; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            echo "<strong><span style='color:#008d4c'>Complete</span></strong>";
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                                                                </button>
                                                                <ul style="position: relative !important;" class="dropdown-menu dropdown-default pull-right" role="menu">
                                                                    <?php $meeting_id = explode("-",$row['virtual_class_id']); ?>
                                                                    <li>
                                                                        <a  target="_blank" href="<?php echo base_url(); ?>virtualclass/view_detail/complete/<?php echo $meeting_id[1]."/".$row['virtual_class_name']; ?>">
                                                                            <i class="entypo-eye"></i>
                                                                            <?php echo get_phrase('view_detail'); ?>
                                                                        </a>
                                                                    </li>
                                                                     <?php 
                                                                        $meetingId = $row['virtual_class_id'];
                                                                        $recordingUrl = WEBRTC_LINK."api/getRecordings?";
                                                                		$params = '&meetingID='.urlencode($meetingId);
                                                                        $url_recording = $recordingUrl.$params.'&checksum='.sha1("getRecordings".$params.WEBRTC_SECRET);
                                                                        $xmldata = simplexml_load_file($url_recording) or die("Failed to load");
                                                                        if($xmldata->returncode == 'SUCCESS' And $xmldata->messageKey != 'noRecordings'){
                                                                    ?>
                                                                    <li>
                                                                        <a target="_blank" href="<?php echo base_url(); ?>virtualclass/view_recording/<?php echo $meeting_id[1]."/".$row['virtual_class_name']; ?>">
                                                                            <i class="entypo-eye"></i>
                                                                            <?php echo get_phrase('view_recording'); ?>
                                                                        </a>
                                                                    </li>
                                                                    
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                        <?php }
                                        else
                                           echo get_phrase('no_record_found'); ?>
        			            	</div>
    			            	</div>
    			            	<!--- tab1 end --->
    			            	<!-- tab2---->	
                            	<div class="panel-group collapse child<?php echo '2'.$sect_id.$c_id;?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo '2'.$sect_id.$c_id;?>">
        			            	<div class="panel panel-default" style="padding:8px;border-radius: 0px;">
        			            	    <?php if($class_count2 > 0){ ?>
        			            	    <table class="table table-bordered datatable" id="stud_info_tbl">
                                            <thead>
                                                <tr>
                                                    <th style="width:34px;">#</th>
                                                    <th style="width:27%;"><?php echo get_phrase('class_name');?></th>
                                                    <th style="width:27%;"><?php echo get_phrase('subject_name'); ?></th>
                                                    <th><?php echo get_phrase('teacher_name');?></th>
                                                    <th><?php echo get_phrase('class_time');?></th>
                                                    <th><?php echo get_phrase('status');?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                        $j=0;
                                        $classes = $this->db->query($q2)->result_array();
                                        foreach($classes as $row)
                                        {
                                        $j++;
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php  echo $j; ?>
                                                    </td>
                                                    <td>
                                                         <?php echo $row['class_name']." - ".$row['title']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['subject']." - ". $row['code'];  ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['period_start_time']." - " .$row['period_end_time']; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            echo "<strong><span style='color:#00639e'>Not Complete</span></strong>";
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                        <?php }
                                        else
                                           echo get_phrase('no_record_found'); ?>
        			            	</div>
    			            	</div>
    			            	<!--- tab2 end --->
    			            	<!-- tab3---->	
                            	<div class="panel-group collapse child<?php echo '3'.$sect_id.$c_id;?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo '3'.$sect_id.$c_id;?>">
        			            	<div class="panel panel-default" style="padding:8px;border-radius: 0px;">
        			            	    <?php if($class_count3 > 0){ ?>
        			            	    <table class="table table-bordered datatable" id="stud_info_tbl">
                                            <thead>
                                                <tr>
                                                    <th style="width:34px;">#</th>
                                                    <th style="width:27%;"><?php echo get_phrase('class_name');?></th>
                                                    <th style="width:27%;"><?php echo get_phrase('virtual_class_name'); ?></th>
                                                    <th><?php echo get_phrase('teacher_name');?></th>
                                                    <th><?php echo get_phrase('class_time');?></th>
                                                    <th><?php echo get_phrase('status');?></th>
                                                    <th style="width:94px;"><?php echo get_phrase('option');?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                        $j=0;
                                        $classes = $this->db->query($q3)->result_array();
                                        foreach($classes as $row)
                                        {
                                        $j++;
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php  echo $j; ?>
                                                    </td>
                                                    <td>
                                                         <?php echo $row['class_name']." - ".$row['title']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['virtual_class_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['period_start_time']." - " .$row['period_end_time']; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            echo "<strong><span style='color:#f56954;'>Active</span></strong>";
                                                        ?>
                                                    </td>
                                                    <td>
                                                    
                                                        <div>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                                                                </button>
                                                                <ul style="position: relative !important;" class="dropdown-menu dropdown-default pull-right" role="menu">
                                                                        <?php $meeting_id = explode("-",$row['virtual_class_id']); ?>
                                                                        <li>
                                                                            <a  target="_blank" href="<?php echo base_url(); ?>virtualclass/view_detail/active/<?php echo $meeting_id[1]."/".$row['virtual_class_name']; ?>">
                                                                                <i class="entypo-eye"></i>
                                                                                <?php echo get_phrase('view_detail'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            
                                                                            <a target="_blank" href="<?php echo base_url(); ?>virtualclass/join/<?php echo  $meeting_id[1] ."/".$_SESSION['name']."/".$_SESSION['user_login_id']; ?>">
                                                                                <i class="entypo-mic"></i>
                                                                                <?php echo get_phrase('join'); ?>
                                                                            </a>
                                                                        </li>
                                                                 </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                        <?php }
                                        else
                                           echo get_phrase('no_record_found'); ?>
        			            	</div>
    			            	</div>
    			            	<!--- tab3 end --->
    			            	
    			            	<!-- tab4---->	
                            	<div class="panel-group collapse child<?php echo '4'.$sect_id.$c_id;?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo '4'.$sect_id.$c_id;?>">
        			            	<div class="panel panel-default" style="padding:8px;border-radius: 0px;">
        			            	    <?php if($class_count4 > 0){ ?>
        			            	    <table class="table table-bordered datatable" id="stud_info_tbl">
                                            <thead>
                                                <tr>
                                                    <th style="width:34px;">#</th>
                                                    <th style="width:27%;"><?php echo get_phrase('class_name');?></th>
                                                    <th style="width:27%;"><?php echo get_phrase('subject_name'); ?></th>
                                                    <th><?php echo get_phrase('teacher_name');?></th>
                                                    <th><?php echo get_phrase('class_time');?></th>
                                                    <th><?php echo get_phrase('status');?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                        $j=0;
                                        $classes = $this->db->query($q4)->result_array();
                                        foreach($classes as $row)
                                        {
                                        $j++;
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php  echo $j; ?>
                                                    </td>
                                                    <td>
                                                         <?php echo $row['class_name']." - ".$row['title']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['subject']." - ". $row['code'];  ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['period_start_time']." - " .$row['period_end_time']; ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            echo "<strong><span style='color:#FFE338'>Upcoming</span></strong>";
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                        <?php }
                                        else
                                           echo get_phrase('no_record_found'); ?>
        			            	</div>
    			            	</div>
    			            	<!--- tab4 end --->
			                </div>
			            </div>
			            <?php
			        }
			    }
			    else
			    {?>
					<div class="panel-group collapse child<?php echo $h_key?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo $h_key;?>">
		                <div class="panel panel-default">
		                    <div class="panel-heading">
		                        <p class="panel-title">
		                            <?php echo get_phrase('no_record_found');?>
		                        </p>
		                    </div>
		                </div>
			        </div>    
			    	
			    <?php
			    }
			}
			?>
		</div>

<?php
    }
}
else
	echo get_phrase('no_data');
            ?>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
function myFunction(a) 
{
    //alert(a);
    $(".child" + a).slideToggle("slow");
   
}
function myFunction1(a, b) 
{
    if(a == "1"){
        var res = "2".concat(b);
        $(".child" + res).slideUp("slow");
        var res = "3".concat(b);
        $(".child" + res).slideUp("slow");
        var res = "4".concat(b);
        $(".child" + res).slideUp("slow");
    }
    if(a == "2"){
        var res = "1".concat(b);
        $(".child" + res).slideUp("slow");
        var res = "3".concat(b);
        $(".child" + res).slideUp("slow");
        var res = "4".concat(b);
        $(".child" + res).slideUp("slow");
    }
    if(a == "3"){
        var res = "1".concat(b);
        $(".child" + res).slideUp("slow");
        var res = "2".concat(b);
        $(".child" + res).slideUp("slow");
        var res = "4".concat(b);
        $(".child" + res).slideUp("slow");
    }
    if(a == "4"){
        var res = "1".concat(b);
        $(".child" + res).slideUp("slow");
        var res = "3".concat(b);
        $(".child" + res).slideUp("slow");
        var res = "2".concat(b);
        $(".child" + res).slideUp("slow");
    }
    var res = a.concat(b);
    $(".child" + res).slideToggle("slow");
   
}
</script>
