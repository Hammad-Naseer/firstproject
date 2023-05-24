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
            $logo=system_path($_SESSION['school_logo']);
            if($_SESSION['school_logo']=="" || !is_file($logo))
            {
            ?>
            <a href=""><img src="<?php //echo base_url();?>assets/images/gsims_logo.png">
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
            ?>
        </div>
        <div style="float: left; margin-top: -25px; margin-left: 150px;"> 
            <h2>
                <?php echo $_SESSION['school_name']; ?>
            </h2>
            <h4 style="margin-top: -15px;">
                <?php echo get_phrase('branches_students_attendance_report');?>
            </h4>
        </div>
    </div>
    <?php
        if ($apply_filter==1)
        {
        ?>
        <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
        <?php
        if (isset($attend_date) && !empty($attend_date))
        {
        ?>
            <div>
                <strong>
                <?php echo get_phrase('date');?> :
                <?php echo $attend_date;?>
                
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
            <th style="width: 25px;"><?php echo get_phrase('sr');?>.</th>
            <th><?php echo get_phrase('logo');?></th>
            <th><?php echo get_phrase('name');?></th>
            <th><?php echo get_phrase('students_count');?></th>
            <th><?php echo get_phrase('present');?></th>
            <th><?php echo get_phrase('absent');?></th>
            <th><?php echo get_phrase('leave');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_schools =0;
            $total_students =0;
            $total_present =0;
            $total_absent =0;
            $total_leave =0;
            $count =0;

            foreach ($branch_arr as $key => $value) {
                $count++;
            ?>
            <tr>
                <td><?php echo $count;?></td>
                <td style="width: 80px;">
                    <?php
                    $folder_name=$value['folder_name'];
                    if($value['logo']!="")
                    {
                    ?>
                    <img class="img-responsive" style="max-width:80px; max-height: 50px;" src="<?php echo 'uploads/'.$folder_name.'/'.$value['logo']?>">
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <?php $total_schools++;?>
                    <?php echo $value['name'];?><br>
                    <?php echo $value['address'];?>
                </td>
                <td>
                    <?php
                    if (($student_count[$value['school_id']]) != 0)
                    {
                        $total_students += $student_count[$value['school_id']];
                        echo $student_count[$value['school_id']];
                    }
                    else
                    {
                        echo "0";
                    }
                    ?>
                </td>
                <td>
                    <?php
                        $total_present += $br_students_attend[$value['school_id']][1];
                        echo $br_students_attend[$value['school_id']][1];
                    ?>
                </td>
                <td>
                    <?php
                        $total_absent += $br_students_attend[$value['school_id']][2];
                        echo $br_students_attend[$value['school_id']][2];
                    ?>
                </td>
                <td>
                    <?php
                    $total_leave += $br_students_attend[$value['school_id']][3];
                        echo $br_students_attend[$value['school_id']][3];
                    ?>
                </td>
            </tr>

            <?php
            }
            if ($total_schools>0) {
            ?>
            <tr>
                <td colspan="2" style="text-align: right;"><?php echo get_phrase('total');?> :</td>
                <td><?php echo $total_schools;?></td>
                <td><?php echo $total_students;?></td>
                <td><?php echo $total_present;?></td>
                <td><?php echo $total_absent;?></td>
                <td><?php echo $total_leave;?></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
            </table>
            <?php
                if (empty($branch_arr))
                {
            ?>
                <div style="text-align: center;">
                    <h2><?php echo get_phrase('no_data_available');?></h2>
                </div>
            <?php
                }
            ?>
        </div>