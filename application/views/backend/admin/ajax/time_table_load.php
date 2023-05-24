<style>
    .tt_weekends .btn-group > .teacher_box {
        color: #22ce19;
    }
    i.fa.fa-trash {
        color: red !important;
        font-weight: 600;
    }
    i.fa.fa-pencil {
        color: orange !important;
        font-weight: 600;
    }
    .teacher_box {
    width: 100%;
    }
    .teacher_box i.fa.fa-user {
    float: right !important;
    } 
</style>

<?php

        $custom_css=array(1=>'current-day',2=>'holiday');
        $current_date=date("l");
        
        $settings = "select distinct cs.*, cls.class_id from ".get_school_db().".class_routine_settings cs  
        INNER JOIN ".get_school_db().".class_section  sec on sec.section_id = cs.section_id 
        INNER JOIN ".get_school_db().".class  cls on sec.class_id = cls.class_id 
        INNER JOIN ".get_school_db().".departments d on cls.departments_id = d.departments_id 
        where cs.school_id=".$_SESSION['school_id']." and sec.section_id = ".$section_id." and cs.c_rout_sett_id = $c_rout_sett_id order by cs.start_date desc";
        
        $CRS_arr = $this->db->query("select cr.*,cs.*,d.title,d.departments_id,cls.name,cls.class_id,sec.title,
        sec.section_id,d.departments_id,date_format(cr.period_start_time,'%H:%i')as period_start_time,
        date_format(cr.period_end_time,'%H:%i')as period_end_time FROM ".get_school_db().".class_routine cr 
        RIGHT JOIN ".get_school_db().".class_routine_settings cs on cs.c_rout_sett_id=cr.c_rout_sett_id 
        INNER JOIN ".get_school_db().".class_section  sec on sec.section_id=cs.section_id 
        INNER JOIN ".get_school_db().".class  cls on sec.class_id=cls.class_id 
        INNER JOIN ".get_school_db().".departments  d on cls.departments_id=d.departments_id 
        WHERE 
        cs.school_id=".$_SESSION['school_id']." 
        AND sec.section_id=".$section_id." ")->result_array();
        	
        $routine_arr = array();
        
        $timing=array();
        $custom_color=array(1=>'#29638d',2=>'#6eb6ea');
        
        foreach($CRS_arr as $crs_row)
        {
            // the first line over writes the existing subject id
            $routine_arr[$crs_row['c_rout_sett_id']][$crs_row['section_id']][$crs_row['day']][$crs_row['period_no']]['subject_id'] = $crs_row['subject_id'];
            $routine_arr[$crs_row['c_rout_sett_id']][$crs_row['section_id']][$crs_row['day']][$crs_row['period_no']]['class_routine_id']=$crs_row['class_routine_id'];
            $routine_arr[$crs_row['c_rout_sett_id']][$crs_row['section_id']][$crs_row['day']][$crs_row['period_no']]['duration']=$crs_row['duration'];
            $routine_arr[$crs_row['c_rout_sett_id']][$crs_row['section_id']]['default_period_duration']=$crs_row['period_duration'];
        
            $routine_arr[$crs_row['c_rout_sett_id']][$crs_row['section_id']][$crs_row['day']][$crs_row['period_no']]['period_start_time']=$crs_row['period_start_time'];
            $routine_arr[$crs_row['c_rout_sett_id']][$crs_row['section_id']][$crs_row['day']][$crs_row['period_no']]['period_end_time']=$crs_row['period_end_time'];
            
        }
        $settingsRes = $this->db->query($settings)->result_array();
        

        if (count($settingsRes) > 0)
        {
            foreach($settingsRes as $row)
            {
                
                $no_of_periods                     = $row['no_of_periods'];
                $period_duration                   = $row['period_duration'];
                $start_time                        = strpos($row['start_time'],':')?$row['start_time']:($row['start_time'].':00');
                $end_time                          = strpos($row['end_time'],':')?$row['end_time']:($row['end_time'].':00');
                $assembly_duration                 = $row['assembly_duration'];
                $break_duration                    = $row['break_duration'];
                $break_after_period                = $row['break_after_period'];
                $c_rout_setting_id                 = $row['c_rout_sett_id'];
                $start_date                        = $row['start_date'];
                $end_date                          = $row['end_date'];
                $is_active                         = $row['is_active'];
                $break_duration_after_every_period = ($row['break_duration_after_every_period'] > 0) ? $row['break_duration_after_every_period'] : 0;
                
                $hierarchy                         = section_hierarchy($row['section_id']);
        
                ?>
                <table  class="table table-bordered table-responsive table-mobile">
                    <tr>
                        <td style="background-color: rgb(199, 210, 218) !important;"><strong><?php echo get_phrase('period');?></strong></td>
                        <?php
                            if($assembly_duration > 0)
                            {
                                echo '<td style="width:20px;background-color:'.$custom_color['1'].'"></td>';
                            }
            
                            for($i=1;$i<=$no_of_periods;$i++)
                            {
                                echo '<td style=" background-color: rgb(199, 210, 218) !important;">'.$i.'</td>';
            
                                if(($break_after_period > 0) && ($break_after_period==$i))
                                {
                                    echo '<th style="width:20px;background-color:'.$custom_color['2'].'"></th>';
                                }
                            }
                        ?>
                    </tr>
                    <tbody>
                        <?php
                            for($d=1;$d<=7;$d++)
                            {
                                $current = "";
                                $weekend = "";
                                $period = strtotime($start_time);
                                $period_new = date('H:i', $period);
                
                                // if($d==1)$day=get_phrase('sunday');
                                // elseif($d==2)$day=get_phrase('monday');
                                // elseif($d==3)$day=get_phrase('tuesday');
                                // elseif($d==4)$day=get_phrase('wednesday');
                                // elseif($d==5)$day=get_phrase('thursday');
                                // elseif($d==6)$day=get_phrase('friday');
                                // elseif($d==7)$day=get_phrase('saturday');
                                
                                if($d==1)$day=get_phrase('monday');
                                elseif($d==2)$day=get_phrase('tuesday');
                                elseif($d==3)$day=get_phrase('wednesday');
                                elseif($d==4)$day=get_phrase('thursday');
                                elseif($d==5)$day=get_phrase('friday');
                                elseif($d==6)$day=get_phrase('saturday');
                                elseif($d==7)$day=get_phrase('sunday');
                                
                                
                
                                $day = ucfirst($day);
                                if($day== 'Saturday' || $day== 'Sunday' )
                                {
                                    $weekend_class = 'weekend';
                                    //$weekend = "style='background-color:#02658d !important; color:white !important; '";
                                }
                
                                if($day == $current_date)
                                {
                                    $weekend_class = '';
                                    $current = $custom_css[1];
                                }
                                ?>
                                <tr class="<?php echo $current . " " .$weekend_class; ?>" >
                                    <td width="100" ><?php echo $day;?></td>
                                    <?php
                                    if($assembly_duration > 0)
                                    {
                                        echo '<td style="background-color:';
                                        echo $custom_color['1'];
                                        echo'" ></td>';
                                        
                                        $period = strtotime($start_time) + strtotime(minutes_to_hh_mm($assembly_duration)) - strtotime('00:00');
                                        $period_new = date('H:i', $period);
                                    }
                
                                    for($i=1;$i<=$no_of_periods;$i++)
                                    {
                                        $start=0;
                                        $end=0;
                
                                        echo '<td style="vertical-align:top;"> ';
                                        
                                        $val=$i;
                                        $day = strtolower($day);
                
                                        $subject_id = $routine_arr[$c_rout_setting_id][$section_id][$day][$val]['subject_id'];
                                        
                                        $rout_dep = $CRS_arr[0]['departments_id'];
                                            //$rout_dep = $out_key;
                                            $rout_class = 0;
                                            $rout_sec = $section_id;
                                            $start1=1;
                                        ?>
                                            
                                            <!-- plus button was here -->
                                            
                                        <?php
                                        if(isset($routine_arr[$c_rout_setting_id][$section_id][$day][$val]['duration']) && $routine_arr[$c_rout_setting_id][$section_id][$day][$val]['duration']>0)
                                        {
                
                                            $duration   = $routine_arr[$c_rout_setting_id][$section_id][$day][$val]['duration'];
                                            $start      = $period_new;
                                            $period_new = strtotime($period_new) + strtotime(minutes_to_hh_mm($duration)) - strtotime('00:00');
                                            $period_new = date('h:i', $period_new);
                                            $end        = $period_new;
        
                                            echo "<span>" .  $start .' - '.$end . "</span>";
                                            echo "<span class=\"float-md-right float-sm-none\">" . " (" . $duration." ".get_phrase('min') . ") " . "</span>";
                                            echo "<br>";
                                            
                                        }
                                        else
                                        {
                                            $start = $period_new;
                                            if( $break_duration_after_every_period > 0)
                                            {
                                                if($i > 1){
                                                    $start = strtotime($period_new) + strtotime(minutes_to_hh_mm($break_duration_after_every_period)) - strtotime('00:00');
                                                    $start = date('H:i', $start);
                                                }
                                            }
                                            $period_new = strtotime($start) + strtotime(minutes_to_hh_mm($routine_arr[$c_rout_setting_id][$section_id]['default_period_duration']))  - strtotime('00:00');
                                            $period_new = date('h:i', $period_new);
                                            $end        = $period_new ;
                                            
                                            echo "<span>" .  $start .' - '.$end . "</span>";
                                            // echo "<br>";
                                            echo "<span class=\"float-md-right float-sm-none\">" . " (".$routine_arr[$c_rout_setting_id][$section_id]['default_period_duration']." ".get_phrase('min')." ) " . "</span>";
                                            echo "<br>";
                                        }
                                        ?>
                                        <span>
                                            <a href="javascript:;" id="add-link" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_time_table/<?php echo $day.'-'.$val.'-'.$c_rout_setting_id.'-'.$rout_dep.'-'.$rout_class.'-'.$rout_sec.'-'.$start; ?>');" class=" pull-right">
                                                <i class="fas fa-plus" aria-hidden="true" style="    font-size: 18px;"></i>
                                            </a>
                                        </span>
                                        <br>
                                        
                                        <?php
                                        /*if(isset($routine_arr[$c_rout_setting_id][$section_id][$day][$val]['period_start_time']) && ($routine_arr[$c_rout_setting_id][$section_id][$day][$val]['period_start_time'] > 0))
                                        {
                                            $period_start_time=$routine_arr[$c_rout_setting_id][$section_id][$day][$val]['period_start_time'];
                                            $period_end_time=$routine_arr[$c_rout_setting_id][$section_id][$day][$val]['period_end_time'];
                                            $start=$period_start_time;
                                            $end = $period_end_time;
                
                                            echo "start".$start.'--'.$end;
                                        }*/
                                        ////////////////////////////////////////
                                        
                                        //if(!(isset($subject_id)) ) // comment by Hammad
                                        // {
                                        // }
                                        if(isset($subject_id) && $subject_id > 0)
                                        {
                                            $class_routine_id = $routine_arr[$c_rout_setting_id][$section_id][$day][$val]['class_routine_id'];
                
                                            $compQuery=" select subject_components from ".get_school_db().".class_routine where class_routine_id=".$class_routine_id."";
                                            $compRes = $this->db->query($compQuery)->result_array();
                                            $comps=$compRes[0]['subject_components'];
                
                                                
                                            // Get Multiple Id's Class Routine
                                            $get_cr_id = $this->db->query("select cr.*,cs.school_id,cs.c_rout_sett_id FROM ".get_school_db().".class_routine cr 
                                        	    RIGHT JOIN ".get_school_db().".class_routine_settings cs on cs.c_rout_sett_id=cr.c_rout_sett_id 
                                        	    WHERE  
                                            	cs.school_id=".$_SESSION['school_id']." 
                                            	AND cr.day = '$day'
                                            	AND cr.period_no = ".$val."
                                            	AND cr.c_rout_sett_id = ".$c_rout_setting_id." ")->result_array();
                                                foreach($get_cr_id as $get_cr_id_result){
                                                    
                                                    // Get Teacher Name
                                                    $query3=" select sta.name AS teacher_name from ".get_school_db().".time_table_subject_teacher ttst 
                                                    inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
                                                    inner join ".get_school_db().".staff sta on sta.staff_id=st.teacher_id 
                                                    inner join ".get_school_db().".subject s on s.subject_id=st.subject_id 
                                                    where ttst.school_id=".$_SESSION['school_id']."  and st.subject_id=".$get_cr_id_result['subject_id']." and class_routine_id=".$get_cr_id_result['class_routine_id']."";
                                                    $res = $this->db->query($query3)->result_array();
                                            ?>
                                                    <style>
                                                    #cr<?php echo $get_cr_id_result['class_routine_id'];?> { width:100%;}
                                                    </style>
                                                    <div class="btn-group" id="cr<?php echo $class_routine_id;?>">
                                                        <input type="hidden" id="academic_year" value="<?php echo $academic_id?>" />
                                                        <input type="hidden" id="yearly_term" value="<?php echo $term_id?>" />
                                                        <input type="hidden" id="department_id" value="<?php echo $hierarchy['d_id']?>" />
                                                        <input type="hidden" id="class_select_id" value="<?php echo $hierarchy['c_id']?>" />
                                                        <input type="hidden" id="section_select_id" value="<?php echo $hierarchy['s_id']?>" />
                                                        <div style="float:left">
                                                            
                                                            <?php 
                                                                echo get_subject_name($get_cr_id_result['subject_id']);echo '<br/>'.subject_components($comps);
                                                            ?>
                                                           
                                                        </div>
                                                        
                                                        <div style="float:right">
                                                         <?php 
                                                                $link='<a href="#" onclick="showAjaxModal(\''.base_url().'modal/popup/modal_edit_class_routine_teacher/'.$get_cr_id_result['subject_id'].'-'.$get_cr_id_result['class_routine_id'].'-'.$section_id.'-'.$day.'-'.$val.'-'.$c_rout_setting_id.'\');"><b style="cursor:pointer;"> <i class="fa fa-pencil"></i></b></a>'; 
                                                                echo $link;
                                                         ?>
                                                         <?php //if(sizeof($res)>0){?>
                                                            <a class="routine_del" href="#" data-sec_id="<?php echo $section_id; ?>" data-cr_sett_id="<?php echo $c_rout_setting_id; ?>" data_period_no="<?php echo $val;?>" id="delete_<?php echo $get_cr_id_result['class_routine_id'];?>">
                                                                <i class="fa fa-trash" style="color: #949494; "></i>
                                                            </a>
                                                         <?php //} ?>
                                                        </div>
                                                         
                                                        <div class="teacher_box" style="float:left">
                                                            <?php
                                                                $teachers=array();
                                                                if(sizeof($res)>0)
                                                                {
                                                                    foreach($res as $rows)
                                                                    {
                                                                        $teachers[] = $rows['teacher_name'] .   "<i class='fa fa-user' style='color: #22ce19;'></i>" ;
                                                                    }
                                                                    echo implode('<br/>',$teachers);
                                                                }else{
                                                                    $link='<a href="javascript:;" onclick="showAjaxModal(\''.base_url().'modal/popup/modal_edit_class_routine_teacher/'.$subject_id.'-'.$class_routine_id.'-'.$section_id.'-'.$day.'-'.$val.'-'.$c_rout_setting_id.'\');" class=" pull-right"> <i class="entypo-plus">Add Teacher</i></a>';
                                                                    echo $link;
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
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
                                <?php
                            } 
                        ?>
                    </tbody>
                </table>
        
                <?php
            }//end foreach
        }
        else
            echo get_phrase('no_timetable_added');
        ?>
        <div id="msg_div"></div>
        <script>
            $(document).ready(function()
            {
                $("a[class='routine_del']").on('click', function(e)
                {
                    $(this).attr('href', '#');
                    str = $(this).attr('id');
                    var period = $('#period' + str).val();
                    /*data_period_no*/
                    //var day = $('#day' + str).val();
                    var setting_id = $('#setting_id' + str).val();
                    var c_rout_setting_id = $(this).attr('data-cr_sett_id');
                    var sec_id = $(this).attr('data-sec_id');
                    cr_id = str.replace('delete_', '');
                    delete_url = '#';
                    Swal.fire({
                      title: 'Are you sure?',
                      text: "You want to delete this!",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>time_table/class_routine/delete/" + cr_id,
                            data: ({
                                class_routine_id: cr_id
                            }),
                            success: function(response)
                            {
                                // alert(response);
                                // alert($.trim(response));
                                $('#msg_div').html(response);
                                load_table(c_rout_setting_id, sec_id,true);
                            }
        
                        });
                      }
                    })
                  
                });
            });
        </script>
        
        
        <div class="row tt2">
            <div class="col-sm-12" style="font-size:12px;">
        
        
        
        
        
        
                <div class="col-sm-6">
                    <strong><?php echo get_phrase('start_time');?>:</strong>
                    <?php echo $start_time?>
                </div>
        
        
                <div class="col-sm-6">
        
                    <div style="width:14px; height:14px;background-color:#29638d;float: left;display: inline; "></div>
                    <div style="float: left;display: inline; padding-left:7px;"><?php echo get_phrase('assembly');?>	<?php echo": ".$assembly_duration;?> <?php echo get_phrase('minutes');?></div>
        
                </div>
        
        
        
        
                <div class="col-sm-6">
                    <strong><?php echo get_phrase('default_period_duration');?>:</strong>
                    <?php echo $routine_arr[$c_rout_setting_id][$section_id]['default_period_duration'];?> <?php echo get_phrase('minutes');?>
                </div>
        
        
        
        
                <div class="col-sm-6">
        
                    <div style="width:14px; height:14px;  background-color:#0ea9e8;float: left; display: inline; "></div>
        
                    <div style="float:left;display: inline; padding-left:7px !important;"><?php echo get_phrase('break');?>	<?php echo": ".$break_duration;?> <?php echo get_phrase('minutes');?></div>
        
                </div>
                
                
                <div class="col-sm-6">
        
                    <div style="float:left;display: inline;"><b><?php echo get_phrase('break_duration_after_every_period');?></b>	<?php echo": ".$break_duration_after_every_period;?> <?php echo get_phrase('minutes');?></div>
        
                </div>
        
        
        
        
        
        
        
        
        
        
        
        
        
            </div>
        </div>