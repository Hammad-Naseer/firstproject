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
                    $label = "breakeven_report";
                }
                echo get_phrase($label);
                ?>
            </h4>
        </div>
    </div>
    <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
    <?php
        if ($start_date) {
        ?>
            <div>
            <strong>
            <?php echo get_phrase('from');?> :
            <?php
            if ($start_date) {
            
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
        if ($end_date) {
        ?>
            <div>
            <strong>
            <?php echo get_phrase('to');?> :
            <?php
            if ($end_date)
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
        <div id="table_data" style="margin-top: 20px;">
            <table border="1px">
            
             <tbody>
            <tr>
                <td><strong><?php echo get_phrase('total_revenue');?></strong></td>
                <td><?php echo number_format($revenue); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo get_phrase('total_expenses');?></strong></td>
                <td><?php echo number_format($expanse); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo get_phrase('net_profit');?></strong></td>
                <td><?php echo number_format($net_profit); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo get_phrase('current_students_strengths');?></strong></td>
                <td><?php echo total_school_studnets(); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo get_phrase('per_student_expenses');?></strong></td>
                <td><?php echo $per_student_expense;?></td>
            </tr>
            <tr>
                <td><strong><?php echo get_phrase('breakeven_strength');?></strong></td>
                <td><?php 
                    
                    if($per_student_expense > 0){
                        $break_even_strngth = $net_profit / $per_student_expense;
                        if(is_nan($break_even_strngth)){
                            echo "";
                        }else{
                             echo number_format($break_even_strngth);
                        }
                    }
                    
                    ?></td>
            </tr>
            
            <tr>
                <td><strong><?php echo get_phrase('capacity');?></strong></td>
                <td><?php $school_capacity = school_capacity($_SESSION['school_id']);
                        echo number_format($school_capacity);?></td>
            </tr>
            <tr>
                <td><strong><?php echo get_phrase('capacity_utilization');?></strong></td>
                <td><?php $total_std = total_school_studnets(); 
                $school_capacity = school_capacity($_SESSION['school_id']);
                        
                echo $total_std /$school_capacity; ?></td>
            </tr>
           

            
            </tbody>
    </table>
</div>