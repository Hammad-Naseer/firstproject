<style>
    #nprogress .bar{display:none}#nprogress .spinner{display:none}#nprogress .spinner-icon{display:none}.fa-long-arrow-right{color:#000}.crth{color:#000;background-color:rgba(3,58,98,.19)!important;text-align:center}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar" data-step="1" data-position='top' data-intro="Please use this filter to get specific circulars record">
        <!--<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" onclick="javascript:introJs().start();">-->
        <!--    <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>-->
        <!--</a>-->
        <h3 class="system_name inline">
            <?php echo get_phrase('Time Table');?>        
        </h3> 
    </div>
</div>

<?php

    if ($this->session->flashdata('club_updated'))
    {
        echo '<div align="center">
         <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          ' . $this
            ->session
            ->flashdata('club_updated') . '
         </div> 
        </div>';
    }

    $custom_css   = array(
        1 => 'current-day',
        2 => 'holiday'
    );
    
    $statuslist   = array(
        1 => 'present',
        2 => 'absent',
        3 => 'leave',
        4 => 'weekend'
    );
    
    $current_date = date("l");
    
    $custom_color = array(
        1 => '#29638d',
        2 => '#6eb6ea'
    );

    $routine1     =  array();
    
    $q2     = "  SELECT cr.*,cs.*,date_format(cr.period_start_time,'%H:%i')as period_start_time,date_format(cr.period_end_time,'%H:%i')as period_end_time FROM " . get_school_db() . ".class_routine 
                 cr right join " . get_school_db() . ".class_routine_settings cs on (cs.c_rout_sett_id=cr.c_rout_sett_id and cs.is_active = 1)
  		         where cs.school_id=" . $_SESSION['school_id'] . " and cs.section_id=" . $_SESSION['section_id'] . " ";

    $result =    $this->db->query($q2)->result_array();
    
    if (sizeof($result) > 0)
    {
        
?>

<div class="row timtble-set" data-step="1" data-position='top' data-intro="Weekly timetable">
    <div class="panel panel-default panel-shadow" data-collapsed="0">

		<div class="panel-body">
        <?php
        
            $custom_css = array(
                1 => 'current-day',
                2 => 'holiday'
            );
            
            $statuslist = array(
                1 => 'present',
                2 => 'absent',
                3 => 'leave',
                4 => 'weekend'
            );
            
            $current_date = date("l");
            
            $custom_color = array(
                1 => '#29638d',
                2 => '#6eb6ea'
            );  
            
            $routine1 = array();
        
            $q2 = " SELECT cr.*,cs.* FROM " . get_school_db() . ".class_routine cr right join " . get_school_db() . ".class_routine_settings cs 
                    on (cs.c_rout_sett_id=cr.c_rout_sett_id and cs.is_active = 1)
                    where cs.school_id=" . $_SESSION['school_id'] . " and cs.section_id=" . $_SESSION['section_id'] . " ";
  
            $section_id = $_SESSION['section_id'];
            
            $result     = $this->db->query($q2)->result_array();
            
            if (sizeof($result) > 0)
            {
                

                foreach ($result as $row)
                {
                    
                    $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['subject_id']          =  $row['subject_id'];
                    $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['class_routine_id']    =  $row['class_routine_id'];
                    $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['duration']            =  $row['duration'];
                    $routine1[$row['c_rout_sett_id']][$row['section_id']]['default_period_duration']                             =  $row['period_duration'];
                    $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['period_start_time']   =  $row['period_start_time'];
                    $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['period_end_time']     =  $row['period_end_time'];
                    $routine1[$row['c_rout_sett_id']][$row['section_id']][$row['day']][$row['period_no']]['period_after_period'] =  ($row['break_duration_after_every_period'] > 0) ? $row['break_duration_after_every_period'] : 0;
                    
                }
        

                $toggle      =  true;
                $settings    =  " select cs.* from " . get_school_db() . ".class_routine_settings cs where cs.school_id=" . $_SESSION['school_id'] . " 
                                  and cs.is_active = 1 and cs.section_id=" . $_SESSION['section_id'] . " ";
                $settingsRes =  $this->db->query($settings)->result_array();
                $cnt         =  0;

                foreach ($settingsRes as $row)
                {
                    $cnt++;
                    $no_of_periods      = $row['no_of_periods'];
                    $period_duration    = $row['period_duration'];
                    $start_time         = $row['start_time'];
                    $end_time           = $row['end_time'];
                    $assembly_duration  = $row['assembly_duration'];
                    $break_duration     = $row['break_duration'];
                    $break_after_period = $row['break_after_period'];
                    $c_rout_setting_id  = $row['c_rout_sett_id'];
        
                    $period_array       = array();
                    $hierarchy          = section_hierarchy($row['section_id']);
        ?>
        
                        <div class="box-header with-border mgbb">
                            <h3 class="box-title"> <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <?php echo get_phrase('timetable'); ?> 
                                <span style="font-size:12px;">
                                   <?php echo ' (' . convert_date($row['start_date']) . ' to ' . convert_date($row['end_date']) . ') '; ?>
                                </span>
                            </h3>
                        </div>

                        <div id="collapse<?php echo $cnt . '-' . $row['section_id']; ?>" class="table-responsive panel-collapse collapse <?php if ($toggle){ echo 'in';$toggle = false; } ?> ">
                                                
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                <thead>
                                    <tr style="text-align: center; font-weight:bold;">
                                        <!--background-color: #c7d2da !important;-->
                                        
                                        <th>
                                                <strong><?php echo get_phrase('period'); ?></strong>  
                                                <!--style="background-color: #c7d2da !important;"-->
                                                <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                        </th>
                                        
                                    	<?php
                                    	
                                            if ($assembly_duration > 0)
                                            {
                                                echo '<td style="width:20px;background-color:' . $custom_color['1'] . '"></td>';
                                            }
                                            for ($i = 1;$i <= $no_of_periods;$i++)
                                            {
                                                // style=" background-color: rgb(199, 210, 218) !important;"
                                                echo '<td>' . $i . '</td>';
                                
                                                if (($break_after_period > 0) && ($break_after_period == $i))
                                                {
                                                    echo '<th style="width:20px;background-color:' . $custom_color['2'] . '"></th>';
                                                }
                                            }
                
                                        ?>
                                    </tr>
                                    <tr class="text-center">
                                        <?php
                                            $count              = 1;
                                            $custom_color_count = 1;
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php
                                   
                                    for ($d = 1;$d <= 7;$d++)
                                    {
                                        $current            = "";
                                        $current1           = "";
                                        $custom_color_count = 1;
                                        if ($d == 1)          $day = 'monday';
                                        else if ($d == 2)     $day = 'tuesday';
                                        else if ($d == 3)     $day = 'wednesday';
                                        else if ($d == 4)     $day = 'thursday';
                                        else if ($d == 5)     $day = 'friday';
                                        else if ($d == 6)     $day = 'saturday';
                                        else if ($d == 7)     $day = 'sunday';
                        
                                        if (ucfirst($day) == $current_date)
                                        {
                                            $current = $custom_css[1];
                                        }
                                        $statuslist_css = "";
                                        if ($day == "saturday" or $day == "sunday")
                                        {
                                            $statuslist_css = $statuslist[4];
                                        }
                                        $dd    = date("Y-m-d", strtotime($day . ' this week'));
                                        $q1    = "select * from " . get_school_db() . ".holiday where start_date<='$dd' and end_date>='$dd' AND school_id=" . $_SESSION['school_id'] . " ";
                                        $qurrr = $this->db->query($q1)->result_array();
                                        
                                        if (count($qurrr) > 0)
                                        {
                                            $current1 = $custom_css[2];
                                        }
                                        echo '<tr class="gradeA' . ' ' . $current . ' ' . $current1 . ' ' . $statuslist_css . '">';
                                        
                                        ?>
                                        
                                        <th width="80">
                                            <?php 
                                               echo ucfirst($day);
                                               echo '<br>';
                                               echo convert_date(date("Y-m-d", strtotime($day . ' this week')));
                                            ?>
                                        </th>
                                        
                                        <?php
                                        
                                        if ($assembly_duration > 0)
                                        {
                                            echo '<td style="background-color:';
                                            echo $custom_color['1'];
                                            echo '" ></td>';
                                            $period = strtotime($start_time) + strtotime(minutes_to_hh_mm($assembly_duration)) - strtotime('00:00');
                                            $period_new = date('H:i', $period);
                                        }
                                        
                                        for ($i = 1;$i <= $no_of_periods;$i++)
                                        {
                                            $start = 0;
                                            $end = 0;
                        
                                            echo '<td style="vertical-align:top;"> ';
                        
                                            $val        = $i;
                                            $day        = strtolower($day);
                                            
                                            
                                            $subject_id = $routine1[$c_rout_setting_id][$section_id][$day][$val]['subject_id'];
                        
                                            if (isset($routine1[$c_rout_setting_id][$section_id][$day][$val]['duration']) && $routine1[$c_rout_setting_id][$section_id][$day][$val]['duration'] > 0)
                                            {
                                                
                                                $duration                          =  $routine1[$c_rout_setting_id][$section_id][$day][$val]['duration'];
                                                $break_duration_after_every_period =  $routine1[$c_rout_setting_id][$section_id][$day][$val]['period_after_period'];
                                                $start                             =  $period_new;
                                                
                                                if( $break_duration_after_every_period > 0)
                                                {
                                                    if($i > 1){
                                                        $start = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration_after_every_period)) - strtotime('00:00');
                                                        $start = date('H:i', $start);
                                                    }
                                                    else
                                                    {
                                                        $start = $period_new;
                                                    }
                                                }
                                                
                        
                                                $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($duration)) - strtotime('00:00');
                                                $period_new = date('H:i', $period_new);
                                                $end        = $period_new;
                        
                                                echo $start . ' - ' . $end;
                                                echo "<br>";
                                                echo " (" . $duration . " min) ";
                                            }
                                            else
                                            {
                                                
                                                $start                             = $period_new;
                                                $break_duration_after_every_period = $routine1[$c_rout_setting_id][$section_id][$day][$val]['period_after_period'];
                                                
                                                $start = $period_new;
                                                if( $break_duration_after_every_period > 0)
                                                {
                                                    if($i > 1){
                                                        $start = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration_after_every_period)) - strtotime('00:00');
                                                        $start = date('H:i', $start);
                                                    }
                                                    else
                                                    {
                                                        $start = $period_new;
                                                    }
                                                }
                                                
                                                $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($routine1[$c_rout_setting_id][$section_id]['default_period_duration'])) - strtotime('00:00');
                                                $period_new = date('H:i', $period_new);
                                                $end        = $period_new;
                        
                                                echo $start . ' - ' . $end;
                                                echo "<br>";
                                                echo " (" . $routine1[$c_rout_setting_id][$section_id]['default_period_duration'] . " min) ";
                                                echo "<br>";
                                            }
                        
                        
                                            if(isset($subject_id) && $subject_id > 0)
                                            {

                                                $get_cr_id = $this->db->query("select cr.*,cs.school_id,cs.c_rout_sett_id FROM ".get_school_db().".class_routine cr 
                                            	                               RIGHT JOIN ".get_school_db().".class_routine_settings cs on cs.c_rout_sett_id=cr.c_rout_sett_id 
                                            	                               WHERE cs.school_id=".$_SESSION['school_id']." AND cr.day = '$day' AND cr.period_no = ".$val."
                                                	                           AND cr.c_rout_sett_id = ".$c_rout_setting_id." ")->result_array();
                                                
                                                $class_routine_id = $routine1[$c_rout_setting_id][$section_id][$day][$val]['class_routine_id'];
                                                $compQuery        = " select subject_components from ".get_school_db().".class_routine where class_routine_id=".$class_routine_id."";
                                                $compRes          = $this->db->query($compQuery)->result_array();
                                                $comps            = $compRes[0]['subject_components'];
                                                foreach($get_cr_id as $get_cr_id_result){
                                                    $query3           = " select ttst.subject_teacher_id as teacher_id, sta.name AS teacher_name from ".get_school_db().".time_table_subject_teacher ttst inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id inner join ".get_school_db().".staff sta on sta.staff_id=st.teacher_id inner join ".get_school_db().".subject s on s.subject_id=st.subject_id where ttst.school_id=".$_SESSION['school_id']."  and st.subject_id=".$get_cr_id_result['subject_id']." and class_routine_id=".$get_cr_id_result['class_routine_id']."";
                                                    $res              = $this->db->query($query3)->result_array();
                                        
                                            ?>
                                            
                                            <div class="" id="cr<?php echo $get_cr_id_result['class_routine_id'];?>">
                                                <div>
                                                    <?php echo get_subject_name($get_cr_id_result['subject_id']);echo '<br/>'.subject_components($comps);?>
                                                </div>
                                                <div class="blue">
                                                    <?php 
                                                        $teachers=array();
                                                        if(sizeof($res)>0)
                                                        {
                                                            
                                                                    
                                                            foreach($res as $rows)
                                                            {
                                                                $teachers[]=$rows['teacher_name'];
                                                                $teacher_id = $rows['teacher_id'];
                                                            }
                        
                                                            echo implode('<br/>',$teachers);
                                                            
                                                            if($day == strtolower(date("l")))
                                                            {
                                                                        
                                                                $current_t = date('H:i', strtotime("now"));
                                                                        
                                                                if( $break_duration_after_every_period > 0)
                                                                {
                                                                    if($i > 1){
                                                                        $start_t   = strtotime($start) + strtotime(minutes_to_hh_mm($break_duration_after_every_period)) - strtotime('00:05');
                                                                        $start_t   = date('H:i', $start_t);
                                                                    }
                                                                    else
                                                                    {
                                                                        $start_t   = date('H:i', (strtotime($start) - (5 * 60)));
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                         $start_t  = date('H:i', (strtotime($start) - (5 * 60)));
                                                                }
                                                                        
                                                                $end_t = date('H:i', (strtotime($end)-(5 * 60)));
                                                                        
                                                                //$current_t = date('H:i', strtotime("14:15")); // added for demo
     
                                                                $start_t   = date('H:i', (strtotime($current_t)-(5 * 60)));
                                                                $end_t     = date('H:i', (strtotime($end)-(5 * 60)));
                                                                
                                                                if($current_t >= $end_t){
                                                                    // echo "<br><strong><span style='color:#9acc9e;'>Completed</span></strong>";
                                                                }
                                                                else
                                                                {
                                                                    
                                                                    if($current_t < $start){
                                                                        $color = "color:#FFE338;";
                                                                    }
                                                                    if($current_t >= $start_t And $current_t <= $end_t)
                                                                    { 
                                                                        $color = "color:#f56954;";
                                                                        echo '<br><strong><a class="create_vc_btn" id="showhide_'.$get_cr_id_result['class_routine_id'].'_'.$section_id.'_'.$teacher_id.'"  target="_blank"  href="'.base_url().'student_p/join_virtual_class/'.$get_cr_id_result['class_routine_id'].'/'.$section_id.'">Join VC</a></strong><br><br>';    
                                                                    }
                                                                }

                                                            }   
                                            
                                                            }
                                                        ?>
                                                      </div>
                                            </div>
                                      <?php 
                                      } }
                                            
                                            if (($break_after_period > 0) && ($break_after_period == $i) && ($break_duration > 0))
                                            {
                                                echo '<td style="background-color:';
                                                echo $custom_color['2'];
                                                echo '" ></td>';
                        
                                                $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration)) - strtotime('00:00');
                                                $period_new = date('H:i', $period_new);
                                            }
                                        }
                        
                                        ?>
                                    
                                        </tr>
                                    
                                    <?php
                                    } 
                                    
                                   ?>
                                                        
                                </tbody>
                            </table>
                                                
                        </div>
                                        
                                        <?php
                }
                
                
            }
            else
            {
            ?>
                <div class="box-header with-border mgbb">
                    <h3 class="box-title"> <i class="fa fa-clock-o" aria-hidden="true"></i><?php echo get_phrase('timetable'); ?>  </h3>
                </div>
                
            <?php
            }
            ?>

            <div class="subject_detail" style="margin-top: 10px;">
                <div class="row">
                	<div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="assembly">
                        <span>
                            <strong><?php echo get_phrase('start_time'); ?></strong>
                        </span>
                        <span>
                        	<?php echo $start_time; ?>
                        </span>	
                        </div>
                        
                        <div class="assembly">
                        <span>
                             <strong><?php echo get_phrase('default_period_duration'); ?></strong>
                        </span>
                        <span>
                        	<?php echo $period_duration; ?> <?php echo get_phrase('minutes'); ?>
                        </span>	
                        </div>                      
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                    	<div class="assembly">
                        	<div></div>
                            <span><strong><?php echo get_phrase('assembly'); ?></strong></span>
                            <span><?php echo $assembly_duration; ?> <?php echo get_phrase('minutes'); ?></span>
                        </div>
                        <div class="break">
                        	<div></div>
                            <span><strong><?php echo get_phrase('break'); ?></strong></span>
                            <span><?php echo $break_duration; ?> <?php echo get_phrase('minutes'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row" style="margin-bottom: 38px; clear:both;">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <a href="<?php echo base_url(); ?>student_p/class_routine" class="more"> <?php echo get_phrase('view_more'); ?>>> </a>
                </div>
            </div>
                                    
        </div>
    </div>
</div>

<?php 
     } 
     else 
     { 
?>
        <div class="text-center">
            <i class="fas fa-table" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
            <h2><b>Time Table is Not Created Yet</b></h2>
        </div>    
<?php 
   } 
?>
								
<style>
.validate-has-error{color:red}.panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#00a1de;border-top:2px solid #006f9c}.fa{padding-right:1px!important}.fa-plus-square{color:#507895}.title{border:1px solid #eae7e7;min-height:34px;padding-top:10px;background-color:rgba(242,242,242,.35);color:#8c8c8c;height:auto}.adv{width:50px}.tt{background-color:rgba(242,242,242,.35);min-height:26px;padding-top:4px}.tt2{max-height:400px;overflow-y:auto;overflow-x:hidden;margin-bottom:20px}.tt2 .col-sm-12{line-height:22px}.panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#21a9e1;border-top:2px solid #00a651}.panel-body{padding:9px 22px}.myfsize{font-size:11px!important}.panel-group .panel>.panel-heading>.panel-title>a{display:inline}.fa-file-o{padding-right:0!important}.panel{margin-bottom:20px;background-color:#fff;border:1px solid rgba(0,0,0,.08);border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(43,43,43,.15)}.panel-title{width:100%}.panel-group.joined>.panel>.panel-heading+.panel-collapse{background-color:#fff}.difl{display:inline;float:left}.bt{margin-bottom:-28px;padding-top:15px;padding-right:31px}.panel-heading>.panel-title{float:left!important;padding:10px 15px!important;background-color:#fff}.pdr43{padding-right:43px}.title_collapse{padding:5px;font-size:18px;font-color:#000;color:#000;padding:5px 15px!important;cursor:pointer;border:1px solid rgba(204,204,204,.64)}.crud a{color:#b3b3b3;font-size:12px;padding-top:5px}.entypo-trash{color:#f1181b!important}
</style>

<script>
   $('.showhide').hide();
    var auto_refresh = setInterval(
        function () {
            $.ajax({
                type: 'POST',
                data: {},
                url: "<?php echo base_url(); ?>student_p/updated_vc",
                dataType: "html",
                success: function(response) {
                    var jsonObject = $.parseJSON(response);
                    $(".showhide").hide();
                    for(var i = 0; i < jsonObject.length; i++) {
                        var obj               = jsonObject[i];
                        var teacher_id        = obj.subject_teacher_id;
                        var section_id        = obj.section_id;
                        var class_routine_id  = obj.class_routine_id;
                        $('#showhide_'+class_routine_id+'_'+section_id+'_'+teacher_id).show();
                    }
                }
            });
        }, 5000);
</script>
