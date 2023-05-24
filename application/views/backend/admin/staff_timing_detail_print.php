<style>
    .table
    {
        border-top: 1px solid black;
        border-right: 1px solid black;
        width: 100%;
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td 
    {
        border-left: 1px solid black;
        border-bottom: 1px solid black;
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
                <?php echo get_phrase('staff_timing_report');?>
            </h4>
        </div>
    </div>

    <?php
        if ($apply_filter==1)
        {
        ?>
        <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
        <?php
        if (isset($staff_id) && !empty($staff_id))
        {
            ?>
            <div>
            <strong>
            <?php echo get_phrase('name');?> :
            <?php
            if (isset($staff_id) && !empty($staff_id))
            {
                $staff_details_arr = get_staff_detail($staff_id);
                echo $staff_details_arr[0]['name'];
            }
                
            ?>
            </strong>
            </div>
        <?php
        }
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
<div class="row">
&nbsp;&nbsp;
</div>
<div class="row">
<div class="col-md-12" style="overflow: auto; text-align: center;">
<div class="responsive">
<table class="table table-bordered table-hover table-condensed">
<thead>
    <tr>
        <th>Date</th>
        <th>Time In</th>
        <th>Time Out</th>
        <th>Time Count</th>
        <th>Total Time</th>
    </tr>
</thead>
<tbody>
<?php

if(count($date_ary)>0)
{
    $total_monthly_time =0;
    foreach($date_ary as $dis_key=>$dis_val)
    {
        
            $total_monthly_time += $final_date[$dis_key]['time'];
?>
    <tr>
        <td colspan="4">
            <strong><?php echo convert_date($dis_key); ?></strong>
        </td>
        <td>
            <strong>
            <?php echo gmdate("H:i:s", $final_date[$dis_key]['time']);?>
            </strong>
        </td>
    </tr>
<?php
        $i=0;
        foreach($time_out[$dis_val] as $key1=>$val1)
        {
            $total_time_cur= strtotime($time_out[$dis_val][$i])-strtotime($time_in[$dis_val][$i]);
        ?>
        <tr>
            <td>&nbsp;</td>
            <td>
                <?php 
                echo date('H:i:s',strtotime($time_in[$dis_val][$i]));
                ?>  
            </td>
            <td>
                <?php 
                echo date('H:i:s',strtotime($time_out[$dis_val][$i]));
                ?>
            </td>
            <td>
            <?php
                echo gmdate("H:i:s", $total_time_cur);
                ?>  
             </td>
            <td>
                &nbsp;
            </td>
        </tr>
        <?php
        $i++;
        }
        ?>
        <tr>
            <td>&nbsp;</td>
            <td>
                <?php
                echo date('H:i:s',strtotime($final_date[$dis_key]['extra_in']));
                ?>
            </td>
            <td>NA</td>
            <td>NA</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td>Total Monthly Time</td>
        <td><strong>
        <?php
            $monthly_total = seconds_to_hours($total_monthly_time);
            echo $monthly_total['h'].":".$monthly_total['m'].":".$monthly_total['s'];
        ?>
        </strong>
        </td>
    </tr>
<?php
}
?>
</tbody>
</table>
</div>
</div>
</div>