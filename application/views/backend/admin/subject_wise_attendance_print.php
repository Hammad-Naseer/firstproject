    <style>
        table {  
        color: #333;
        font-family: Helvetica, Arial, sans-serif;
        border-collapse: 
        collapse; border-spacing: 0;
        width: 100%;
        border: 1px solid black;
        font-size: 12px;
        }

        td, th { 
        transition: all 0.3s;  
        padding-left: 3px;
        }

        th {  
        background: #DFDFDF;  
        font-weight: bold;
        }

        td {  
           background: #FAFAFA;
        }

        tr:nth-child(even) td { background: #F1F1F1; }   
        tr:nth-child(odd) td { background: #FEFEFE; }  
        tr td:hover { background: #666; color: #FFF; } 
        
        
        
        
        .table_header_attendance{
                       padding: 8px;
                        line-height: 1.42857143;
                        vertical-align: middle;
                       }
                       th.table_header_rol_number {
                           width: 7%;color:white;
                            background-color: #ffeb9c!important;
                        }
                        th.table_header_student_name { color:green;
                            background-color: #c6efce!important;
                        }
                        th.table_header_subject_name {
                            background-color: #85c3eb !important;
                            color:white;
                        }
                        th.startTime {
                            background-color: #c6efce !important;
                            padding: 3px;
                            text-align: center;
                            color: green;
                            top: -8px;
                        }
                        th.endTime {
                        background-color: #ffc6cb !important;
                        padding: 3px;
                        text-align: center;
                        color: #d06a6a;
                        top: -8px;
                    }
        
        
        
        
        

    </style>
    <div id="header" style="height: 100px; width: 100%;">
        <div style="float: left; height: 100px; width: 150px;"> 
            <?php
            if(!empty($d_school_id))
            {
                $d_school_id = $d_school_id;
            }
            else
            {
                $d_school_id = $_SESSION['school_id'];
            }


            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];


           if($d_school_id == $_SESSION['school_id'])
            {
                $logo=system_path($_SESSION['school_logo']);
                if($_SESSION['school_logo']=="" || !is_file($logo))
                {
                ?>
                <a href=""><img style="width: 150px; height: 100px;" src="<?php //echo base_url();?>assets/images/gsims_logo.png">
                </a>
                <?php
                }
                else
                {
                    $img_size = getimagesize("uploads/".$_SESSION['folder_name']."/".$_SESSION['school_logo']."");
                    $img_width = $img_size[0];
                    $img_height = $img_size[1];

                ?>
                <a href="">
                <img style="
                    width:
                    <?php
                        if ($img_width>150) {
                            $img_width = 150;
                        }
                        echo $img_width."px;";
                    ?>
                    height:
                    <?php
                        if ($img_height>100) {
                            $img_height = 100;
                        }
                        echo $img_height."px;";
                    ?>
                    " src="<?php //echo base_url();?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>">
                </a>
                <?php
                }
            }

            
            else
            {
                $scl_name=$this->db->query("select * from ".get_school_db().".school where school_id=$d_school_id")->result_array();
                if(count($scl_name)>0)
                {
                  $branch_name =  $scl_name[0]['name'];
                  $branch_logo =  $scl_name[0]['logo'];
                  $branch_folder =  $scl_name[0]['folder_name'];
                }

                $logo=system_path($branch_logo,$branch_folder,1);
                if($branch_logo=="" || !is_file($logo))
                {
                ?>
                <a href=""><img style="width: 150px; height: 100px;"  src="<?php //echo base_url();?>assets/images/gsims_logo.png">
                </a>
                <?php
                }
                else
                {
                    $img_size = getimagesize("uploads/".$branch_folder."/".$branch_logo."");
                    $img_width = $img_size[0];
                    $img_height = $img_size[1];

                ?>
                <a href="">
                <img style="
                    width:
                    <?php
                        if ($img_width>150) {
                            $img_width = 150;
                        }
                        echo $img_width."px;";
                    ?>
                    height:
                    <?php
                        if ($img_height>100) {
                            $img_height = 100;
                        }
                        echo $img_height."px;";
                    ?>
                    " src="<?php //echo base_url();?>uploads/<?php echo $branch_folder.'/'.$branch_logo; ?>">
                </a>
                <?php
                }


            }
            
            ?>
        </div>
        <div style="float: left; margin-top: -25px; margin-left: 50px;"> 
            <h2>
                <?php
                if ($d_school_id == $_SESSION['school_id'])
                {
                   echo $_SESSION['school_name'];
                }
                else
                {
                    echo $branch_name;
                }
                
                ?>
            </h2>
            <h4 style="margin-top: -15px;">
                <?php
                $id_arr = remove_prefix($section_id);
                $prefix = $id_arr['prefix'];
                $value = $id_arr['value'];

                echo get_phrase('subjectwise_attendance_report');
                ?>
            </h4>
        </div>
    </div>
        <?php
        if ($apply_filter==1)
        {
        ?>
        <div id="filters" style="margin-top:10px;background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
        <?php
        $id_arr = remove_prefix($section_id);
        $prefix = $id_arr['prefix'];
        $value = $id_arr['value'];

        $dep_cls_sec ="";
        $label = "";
        if (isset($value) && !empty($value))
        {
            if ($prefix=='d') {
                $label = "department";
                $dep_cls_sec = get_department_name($value,$d_school_id);
            }elseif ($prefix=='c') {
                $label = "class";
                $dep_cls_arr = class_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_arr['d']." / ".$dep_cls_arr['c'];
            }elseif ($prefix=='s') {
                $label = "section";
                $dep_cls_sec_arr = section_hierarchy($value,$d_school_id);
                $dep_cls_sec = $dep_cls_sec_arr['d']." / ".$dep_cls_sec_arr['c']." / ".$dep_cls_sec_arr['s'];
            }
        ?>
        <div>
            <strong>
                
            <?php   $details=section_hierarchy($section_id);   ?> 
            <?php   echo $details['c'] .'  /' . $details['s']; ?>
            
            </strong>
        </div>
        <?php
        }
        ?>
        <?php
        if (isset($start_date) && !empty($start_date)) {
        ?>
            <div>
                <strong>
                 <?php echo get_phrase('Date From'); ?> :
                 <?php  echo $start_date . '    Date To : ' . ' ' . $end_date ; ?>
                </strong>
            </div>
        <?php
        }
        ?>

        
        </div>
        <?php
        }
        ?>

        <div id="table_data" style="margin-top: 20px;">
            <table border="1px">
                <thead>
                <tr>
                    <th class="table_header_rol_number">
                        <?php echo get_phrase('Roll No.');?>        
                    </th>
                    <th class="table_header_student_name">
                                <?php echo get_phrase('student_name');?>
                    </th>
                    <?php 
                    $total_coumns = 2;
				    foreach($subjects as $subj)
				    {
				            $total_coumns = $total_coumns + 1;
                    ?>
                    <th class="table_header_subject_name"> <?php echo $subj['name'];?> </th>
                    <?php 
                    }
                    ?>                              
                </tr>
                </thead>
                <tbody>
                    <?php 

                    $stud_ids_array =array();
                    $stud_names_array =array();
                    
                    $startedtime_array =array();
                    $endedtime_array =array();
                    $sub_names_array =array();
                    
                    $stud_rollnumber_array = array();
                    
                    $stud_ids_unique = array();
                    $stud_names_unique = array();
                    $stud_rollnumber_unique = array();
                    
                    
                    foreach($students as $row_stud)
                    {
                        
                        // unique identifiers so should only be added in array once
                        if (!in_array($row_stud['student_id'], $stud_ids_unique))
                        {
                             array_push($stud_ids_unique,$row_stud['student_id']);
                             array_push($stud_names_unique,$row_stud['student_name']);
                             array_push($stud_rollnumber_unique,$row_stud['roll_number']);
                        }
                
                         array_push($stud_ids_array,$row_stud['student_id']);
                         array_push($stud_names_array,$row_stud['student_name']);
                         array_push($sub_names_array,$row_stud['subject_name']);
                         array_push($startedtime_array,$row_stud['time_started_at']);
                         array_push($endedtime_array,$row_stud['time_ended_at']);
                         array_push($stud_rollnumber_array,$row_stud['roll_number']);
                    }
            

            
            
                    if(count($stud_ids_unique) > 0){
                
                    for($stud_counter=0; $stud_counter < count($stud_ids_unique); $stud_counter++){
                    ?>
                    <tr>
                        <td><?php echo $stud_rollnumber_unique[$stud_counter];  ?></td>
                        <td><?php echo $stud_names_unique[$stud_counter];  ?></td>
                        <?php 
                        foreach($subjects as $subj_row){
                        
                           $cellString = '';
                           $start_time = '';
                           $end_time = '';
                           for($counter=0; $counter < count($stud_ids_array); $counter++){
                                if($stud_ids_array[$counter] == $stud_ids_unique[$stud_counter] && $sub_names_array[$counter] == $subj_row['name']){
                                       $cellString = $startedtime_array[$counter] . "<br>" . $endedtime_array[$counter];
                                       $start_time = $startedtime_array[$counter];
                                       $end_time   = $endedtime_array[$counter]; 
                                }
                           } // end of for
                        
                            if(!empty($cellString)){
                               
                        ?>
                        <td>
                            <table style="width: 100%;">
                                <tr style="font-size:0.8em">
                                    <th class="startTime" style="border-right:1px solid black">Start Time</th>
                                    <th class="endTime" style="padding-left:10px">End Time</th>
                                </tr>
                                <tr style="font-size:0.7em">
                                    <td style="border-right:1px solid black;padding-top:4px"><?php echo date("h:i A" , strtotime($start_time))  ?></td>
                                    <?php
                                          if(trim($end_time) != ''){
                                    ?>
                                           <td style="padding-left:10px;padding-top:4px"><?php echo date("h:i A" , strtotime($end_time))  ?></td>
                                    <?php
                                          }
                                          else
                                          {
                                    ?>
                                            <td style="padding-left:10px;padding-top:4px">Improper Ending</td>
                                    <?php
                                          }
                                    ?>
                                    
                                </tr>
                            </table>
                        </td>
                        <?php
                        }
                        else
                        {
                               echo "<td>Absent</td>";
                        }
                            
                        } // end of foreach
                        
                        ?>
                        </tr>
                        <?php   
                        
                        }
                        
                        }
                        else
                        {
            
                        ?>
                        <tr>
                            <td colspan="<?php echo $total_coumns ?>"><h3 style="text-align:center;color: #cc2424 !important">No Record Found</h3></td>
                        </tr> 
                        
                        <?php
                        
                            
                        }
            
                        ?>
                            
                        </tbody>
            
            </table>
            
        </div>