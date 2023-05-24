<style>
    .table-condensed > thead > tr > th, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > tbody > tr > td, .table-condensed > tfoot > tr > td
    {
        padding: 0px;   
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td 
    {
        padding:  0px;
        border-bottom: 1px solid #CCC;
        border-left: 1px solid #CCC;
        text-align:center;
    }
     body {
        font-size:11;
    } 
</style>
<!--     <div id="header" style="height: 100px; width: 100%;">
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
                <?php echo get_phrase('staff_monthly_attendance_report');?>
            </h4>
        </div>
    </div>

    <?php
        if ($apply_filter==1)
        {
        ?>
        <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
        <?php
        if (isset($month_year) && !empty($month_year))
        {
        ?>
            <div>
            <strong>
            <?php echo get_phrase('month');?> :
            <?php
            if (isset($month_year) && !empty($month_year))
            {
                $month_detail = split('/', $month_year);
                $month = intval($month_detail[0]);
                $year = $month_detail[1];
                $month_of_year = date("F-Y", mktime(0, 0, 0, $month+1, 0, $year));
                echo $month_of_year;
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
        ?> -->
<div class="row">
    <div class="col-md-12" style="overflow: auto; text-align: center;">
        <div class="table-responsive">
        <table id="fixTable" class="table table-responsive table-condensed">
        <thead>
        <tr>
        <td>
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
            $scl_name=$this->db->query("select name from ".get_school_db().".school where school_id=$d_school_id")->result_array();
            if(count($scl_name)>0)
            {
              $branch_name =  $scl_name[0]['name'];
              $branch_logo =  $scl_name[0]['logo'];
              $branch_folder =  $scl_name[0]['folder_name'];
            }
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
    </div>
    </td>
    <td colspan="34">
        <div style="float: left; margin-top: -25px;">
            <span style="font-size: 10px; float: right; margin-top: -15px;">
                Generated By: <?php echo $_SESSION['name']." Date: ".date("Y-m-j, g:i a"); ?>
            </span>
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
                <?php echo get_phrase('staff_monthly_attendance_report');?>
            </h4>
        </div>
    </td>
    
    </tr>
    <tr>
    <td colspan="35" style="text-align:center;">
        <?php
        if ($apply_filter==1)
        {
        ?>
        <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
        <?php
        if (isset($month_year) && !empty($month_year))
        {
        ?>
            <div>
            <strong>
            <?php echo get_phrase('month');?> :
            <?php
            if (isset($month_year) && !empty($month_year))
            {
                $month_detail = split('/', $month_year);
                $month = intval($month_detail[0]);
                $year = $month_detail[1];
                $month_of_year = date("F-Y", mktime(0, 0, 0, $month+1, 0, $year));
                echo $month_of_year;
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
    </td>
    </tr>
        
        <tr>
            <td>&nbsp;</td>
            <?php
            $month_detail = split('/', $month_year);
            $month = intval($month_detail[0]);
            $year = $month_detail[1];
            $date_curr= date('t', mktime(0, 0, 0, $month, 1, $year));

            if($month=='' && $year=='')
            { 
                $date_curr= date("t");
                $month=date('m');
                $year=date('Y');
            }
            for($i=1;$i<=$date_curr;$i++)
            {
            echo "<td>";
            echo $i;
            ?>
                </td>
            <?php
            } 
            ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php echo get_phrase('name')." / ".get_phrase('emp_code');?>
            </td>
            <?php
            $month_detail = split('/', $month_year);
            $month = intval($month_detail[0]);
            $year = $month_detail[1];
            $date_curr= date('t', mktime(0, 0, 0, $month, 1, $year));
            for($i=1;$i<=$date_curr;$i++)
            {
                $s=mktime(0,0,0,$month, $i, $year);
                $today_date= date('Y-m-d',$s);
                $dw = date( "D", strtotime($today_date));
            ?>
            <td><?php echo $dw;?></td>
            <?php
            }
            ?>
            <td><?php echo get_phrase('p');?></td>
            <td><?php echo get_phrase('a');?></td>
            <td><?php echo get_phrase('l');?></td>
        </tr>
        </thead>
        <tbody>
        <?php
        $month_detail = split('/', $month_year);
        $month = intval($month_detail[0]);
        $year = $month_detail[1];
        $date_curr= date('t', mktime(0, 0, 0, $month, 1, $year));
        if($month=='' && $year=='')
        { 
            $date_curr= date("t");
            $month=date('m');
            $year=date('Y');
        }
        foreach ($staff_attend_details_arr as $key => $value)
        {
        ?>
        <tr>
            <td><?php echo $value['name']."</br> / ".$value['employee_code'];?></td>
            <?php
            $total_present = 0;
            $total_absent = 0;
            $total_leave = 0;
            for($i=1;$i<=$date_curr;$i++)
            {
            ?>
            <td>
            <?php 
                $attend_status = $value['attend'][$year][$month][$i];
                switch ($attend_status) {
                    case 1:
                        echo "P";
                        $total_present++;
                        break;
                    case 2:
                        echo "A";
                        $total_absent++;
                        break;
                    case 3:
                        echo "L";
                        $total_leave++;
                        break;
                    default:
                        echo "&nbsp;";
                        break;
                }
            ?>
            </td>
            <?php
            }
            ?>
            <td><?php echo $total_present; ?></td>
            <td><?php echo $total_absent; ?></td>
            <td><?php echo $total_leave; ?></td>
        </tr>
        <?php
        }
        ?>
        </tbody>
        </table>
        </div>
    </div>
</div>