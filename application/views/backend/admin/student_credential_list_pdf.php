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
                <?php echo get_phrase('student_credential_list');?>
            </h4>
        </div>
    </div>
        <?php
        if ($apply_filter==1)
        {
        ?>
        <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
        <?php
        if (isset($start_date) && !empty($start_date)) {
        ?>
            <div>
            <strong>
            <?php echo get_phrase('from'); ?> :
            <?php
            if (isset($start_date) && !empty($start_date))
            {
                $start_date = date_view($start_date);
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
                // $date=$end_date;
                // $d=explode("-",$date);
                // echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                $end_date = date_view($end_date);
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
                        <th><?php echo get_phrase('sr');?>.</th>
                        <th><?php echo get_phrase('student_name');?></th>
                        <th><?php echo get_phrase('class-section');?> #</th>
                        <th><?php echo get_phrase('std_username');?></th>
                        <th><?php echo get_phrase('std_password');?></th>
                        <th><?php echo get_phrase('parent_username');?></th>
                        <th><?php echo get_phrase('parent_password');?></th>
                    </tr>
                </thead>
            <tbody>
            <?php
                $total = 0;
                $discount = 0;
                $count =0;
                foreach ($result as $key => $value) {
                    $section_details = section_hierarchy($value['section_id'] , $value['school_id']);
                    $count++;
            ?>
            <tr>
                <td><?php echo $count;?></td>
                <td><?php echo $value['name'];?></td>
                <td><?php echo $section_details['d']." - ".$section_details['c']." - ".$section_details['s'];?></td>
                <td><?php echo $value['id_no'];?></td>
                <td>
                <?php
                    if($value['is_password_updated'] == 1)
                    {
                        echo "Password Updated";
                    }else{
                        echo "123456";
                    }                
                ?>
                </td>
                <td><?php 
                        if($value['parent_id'] > 0)
                        {
                            echo $value['parent_email'];
                        }else{
                            echo "";
                        }
                        
                        ?>
                </td>
                <td>
                    <?php
                        if($value['parent_id'] > 0)
                        {
                            if($value['is_parent_password_updated'] == 1)
                            {
                                echo "Password Updated";
                            }else{
                                echo "123456";
                            }
                        }
                    ?></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
            </table>
            <?php
                if (empty($result)) {
                ?>
                <div style="text-align: center;">
                    <h2><?php echo get_phrase('no_data_available');?></h2>
                </div>
                <?php
                }
            ?>
        </div>