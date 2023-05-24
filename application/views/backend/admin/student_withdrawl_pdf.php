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

        td, th { /* No more visible border height: 25px; */
        
        transition: all 0.3s;  /* Simple transition for hover effect 
        */
        padding-left: 3px;
        }

        th {  
        background: #DFDFDF;  /* Darken header a bit */
        font-weight: bold;
        }

        td {  
        background: #FAFAFA;
        }

        /* Cells in even rows (2,4,6...) are one color */        
        tr:nth-child(even) td { background: #F1F1F1; }   

        /* Cells in odd rows (1,3,5...) are another (excludes header cells)  */        
        tr:nth-child(odd) td { background: #FEFEFE; }  

        tr td:hover { background: #666; color: #FFF; }  
        /* Hover cell effect! */
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

            // get school details
            // $scl_name=$this->db->query("select name from ".get_school_db().".school where school_id=$d_school_id")->result_array();
            // if(count($scl_name)>0)
            // {
            //   $branch_name =  $scl_name[0]['name'];
            //   $branch_logo =  $scl_name[0]['logo'];
            //   $branch_folder =  $scl_name[0]['folder_name'];
            // }

            $school_details = get_school_details($d_school_id);
            $branch_name =  $school_details['name'];
            $branch_logo =  $school_details['logo'];
            $branch_folder =  $school_details['folder_name'];
            // get school details

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
                // echo "<pre>";
                // print_r($scl_name);
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
        <div style="float: left; margin-top: -25px; margin-left: 150px;"> 
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
                <?php echo get_phrase('student_withdrawl_summary');?>
            </h4>
        </div>
    </div>
        <?php
        if ($apply_filter==2)
        {
        ?>
        <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
        <?php
        if (isset($std_search) && !empty($std_search)) {
        ?>
            <div>
                <strong>
                <?php echo get_phrase('keywords');?> :
                <?php echo $std_search;?>
                </strong>
            </div>
        <?php
        }
        ?>
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
            <?php echo get_phrase($label);?> :
            <?php 
                echo $dep_cls_sec;
            ?>
            </strong>
        </div>
        <?php
        }
        ?>

        <?php
        if (isset($student_id) && !empty($student_id))
        {
        ?>
        <div>
            <strong>
            <?php echo get_phrase('student');?> :
            <?php 
                echo get_student_name($student_id,$d_school_id);
            ?>
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
            <?php echo get_phrase('from');?> :
            <?php
            if (isset($start_date) && !empty($start_date))
            {
                $start_date = date('d-m-Y',strtotime($start_date));
                echo $start_date;
            }
                
            ?>
            </strong>
            </div>
        <?php
        }
        ?>

        <?php
        if (isset($end_date) && !empty($end_date)) {
        ?>
            <div>
            <strong>
            <?php echo get_phrase('to');?> :
            <?php
            if (isset($end_date) && !empty($end_date))
            {
                $end_date = date('d-m-Y',strtotime($end_date));
                echo $end_date;
            }
            ?>
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
                    <th style="width:20px;"><?php echo get_phrase('#');?></th>
                    <th><?php echo get_phrase('student_details');?></th>
                    <th style="width:100px;"><?php echo get_phrase('Admission_date');?></th>
                    <th style="width:100px;"><?php echo get_phrase('request_date');?></th>
                    <th style="width:100px;"><?php echo get_phrase('withdraw_date');?></th>
                    <th style="width:100px;"><?php echo get_phrase('Reason');?></th>
                </tr>
            </thead>
            <tbody>
                <?php
            		$j=0;
                    foreach($student_withdrawl as $row)
            		{
            		    
                        $j++;
            		?>
            		<tr>
            		    <td class="td_middle"><?php echo $j; ?></td>
            		    <td>
            		        <strong>Name : </strong><?php echo $row['name']; ?><br>
            		        <strong>Address : </strong><?php echo $row['address']; ?><br>
            		        <strong>Roll # : </strong><?php echo $row['roll']; ?><br>
            		        <strong>Mobile # : </strong><?php echo $row['mob_num']; ?><br>
            		        
            		    </td>
            		    <td class="td_middle"><?php echo date_view($row['adm_date']); ?></td>
            		    <td class="td_middle"><?php echo date_view($row['request_date']); ?></td>
            		    <td class="td_middle"><?php echo date_view($row['confirm_date']); ?></td>
            		    <td class="td_middle">&nbsp;</td>
            		</tr>
            		
                    <?php
                    }
                ?>
            </tbody>
            </table>
            <?php if (empty($student_withdrawl)) { ?>
                <div style="text-align: center;">
                    <h2><?php echo get_phrase('no_data_available');?></h2>
                </div>
                <?php } ?>
        </div>