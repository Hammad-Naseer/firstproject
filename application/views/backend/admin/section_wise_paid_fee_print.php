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
                <?php
                $id_arr = remove_prefix($section_id);
                $prefix = $id_arr['prefix'];
                $value = $id_arr['value'];

                $label="";
                if ($prefix=='d') {
                    $label = "department_wise_payments_report";
                }elseif ($prefix=='c') {
                    $label = "class_wise_payments_report";
                }else{
                    $label = "section_wise_payments_report";
                }
                echo get_phrase($label);
                ?>
            </h4>
        </div>
    </div>
        <?php
        if ($apply_filter==1)
        {
        ?>
        <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
        <?php

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
        if (isset($start_date) && !empty($start_date)) {
        ?>
            <div>
            <strong>
            <?php echo get_phrase('from');?> :
            <?php
                if (isset($start_date) && !empty($start_date))
                {
                    $start_date = date('d/m/Y',strtotime($start_date));
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
                $end_date = date('d/m/Y',strtotime($end_date));
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
            <th style="width: 25px;"><?php echo get_phrase('sr');?>.</th>
            <th><?php echo get_phrase('department');?></th>
            <th><?php echo get_phrase('class');?></th>
            <th><?php echo get_phrase('section');?></th>
            <th style="width: 65px;"><?php echo get_phrase('total_amount');?></th>
            <th style="width: 52px;"><?php echo get_phrase('received_amount');?></th>
            <th style="width: 52px;"><?php echo get_phrase('outstanding');?></th>
            <th style="width: 52px;"><?php echo get_phrase('recovery');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_actual = 0;
            $total_received = 0;
            $total_unpaid = 0;
            $total_arrears = 0;
            $total_percentage = 0;
            $count =0;
            $sec_ary = department_class_section_hierarchy($section_id,$d_school_id);
            foreach ($sec_ary as $key => $value) {
                $count++;
            ?>
            <tr>
                <td><?php echo $count;?></td>
                <td><?php echo $value['d'];?></td>
                <td><?php echo $value['c'];?></td>
                <td><?php echo $value['s'];?></td>
                <td><?php $fee = $total_details_arr[$value['s_id']]['actual_amount']; echo number_format($fee); $total_actual += $fee; ?></td>
                <td><?php $recovery = $payment_details_arr[$value['s_id']]['received_amount']; echo number_format($recovery); $total_received += $recovery; ?></td>
                <td><?php $outstanding = $fee-$recovery; echo number_format($outstanding); $total_unpaid += $outstanding;  ?></td>
                <td>
                    <?php
                        $rec_amount = $payment_details_arr[$value['s_id']]['received_amount'];
                        if($rec_amount > 0){
                            $total_class_amount = $total_details_arr[$value['s_id']]['actual_amount'];
                            $percentage = ($rec_amount / $total_class_amount) * 100;
                            if(is_nan($percentage) == 1){
                               echo "";
                            }else{
                               echo number_format($percentage,2)."%";
                            }
                        }
                    ?>
                </td>

            </tr>

            <?php } ?>
            <tr>
                <td colspan="4" style="text-align: right;">
                <?php echo get_phrase('total');?></td>
                <td><?php echo number_format($total_actual);?></td>
                <td><?php echo number_format($total_received);?></td>
                <td><?php echo number_format($total_unpaid);?></td>
                <td><?php echo number_format($total_percentage);?></td>
            </tr>
            <?php
            //}
            ?>
            </tbody>
            </table>
            <?php
            
                if (empty($sec_ary)) {
                ?>
                <div style="text-align: center;">
                    <h2><?php echo get_phrase('no_data_available');?></h2>
                </div>
                <?php
                }
                
            ?>
        </div>