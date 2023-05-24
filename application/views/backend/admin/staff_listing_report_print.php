<style>
        table {  
        color: #333;
        font-family: Helvetica, Arial, sans-serif;
        border-collapse: collapse; 
        border-spacing: 0;
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
        <h4 style="margin-top: -30px;">
            <?php echo get_phrase('staff_list');?>
        </h4>
    </div>
    </div>
    <?php
    if ($apply_filter==1)
    {
    ?>
    <div id="filters" style="background-color: #DFDFDF; padding: 5px; border-radius: 5px;">
    <?php
    if (isset($staff_search) && !empty($staff_search))
    {
    ?>
        <div>
            <strong>
            <?php echo get_phrase('keywords');?> :
            <?php echo $staff_search;?>
            </strong>
        </div>
    <?php
    }
    ?>
    <?php
    if (isset($designation_id) && !empty($designation_id))
    {
    ?>
        <div>
            <strong>
            <?php echo get_phrase('designation');?> :
            <?php
            $designation_details = designation_details($designation_id,$d_school_id);

            echo $designation_details[0]['title'];
            ?>
            </strong>
        </div>
    <?php
    }
    ?>
    <?php
    if (isset($staff_type) && !empty($staff_type))
    {
    ?>
        <div>
            <strong>
            <?php echo get_phrase('staff_type');?> :
            <?php echo get_staff_type($staff_type);?>
            </strong>
        </div>
    <?php
    }
    ?>
    <?php
    if (isset($staff_id) && !empty($staff_id))
    {
    ?>
    <div>
        <strong>
        <?php echo get_phrase('name');?> :
            <?php
            $staff_details_arr = get_staff_detail($staff_id);
            echo $staff_details_arr[0]['name'];
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
       <!--  table table-bordered table-hover table-condensed table-responsive -->
        <thead>
        <tr>
        <th style="width: 25px;"><?php echo get_phrase('sr');?>.</th>
        <th><?php echo get_phrase('staff_details');?></th>
        <th><?php echo get_phrase('contact_details');?></th>
        <th><?php echo get_phrase('address');?></th>
        <th><?php echo get_phrase('other_details');?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $count =0;
        foreach ($staff_listing_arr as $key => $value)
        {
            $count++;
        ?>
        <tr>
            <td><?php echo $count;?></td>
            <td valign="top">
                <?php echo string_with_br($value['name']); ?>
                <?php echo string_with_br($value['employee_code']);?>
                <?php echo string_with_br($value['designation']);?> 
                <?php echo get_id_type($value['id_type']); ?>: 
                <?php echo string_with_br($value['id_no']);?>
            </td>
            <td valign="top">
                <?php echo get_phrase('ph');?> #: 
                <?php echo string_with_br($value['phone_no']);?>
                <?php echo get_phrase('mob');?> #: 
                <?php echo string_with_br($value['mobile_no']);?>
                <?php echo get_phrase('emg');?> #: 
                <?php echo string_with_br($value['emergency_no']);?>
                <?php echo string_with_br($value['email']);?>
            </td>
            <td valign="top">
                <?php echo get_phrase('postal');?>: 
                <?php echo string_with_br($value['postal_address']);?>
                <?php echo get_phrase('permanent');?>: 
                <?php echo string_with_br($value['permanent_address']);?>
                <?php
                /*
                $country_detail = get_country_detail($value['country_id']);
                $provience_detail = get_provience_detail($value['province_id']);
                $city_detail = get_city_detail($value['city_id']);
                $location_detail = get_location_detail($value['location_id']);

                echo $country_detail[0]['title']."/".$provience_detail[0]['title']."/".$city_detail['title']."/".$location_detail[0]['title'];
                */
                ?>
            </td>
            <td valign="top">
                <?php
                if (!empty($value['gender']))
                {
                ?>
                    <?php echo get_phrase('Gender');?>: 
                    <?php echo string_with_br($value['gender']);?>
                <?php
                }
                ?>
                <?php
                if (!empty($value['religion']))
                {
                ?>
                <?php echo get_phrase('religion');?>: 
                <?php echo string_with_br(religion($value['religion']));?>
                <?php
                }
                ?>
                 <?php
                if (!empty($value['dob']) && ($value['dob']) != '0000-00-00')
                {
                ?>
                    <?php echo get_phrase('dob');?>: 
                    <?php echo string_with_br(date_view($value['dob']));?>
                <?php
                }
                ?>
                <?php
                $country_detail = get_country_detail($value['nationality']);
                if (!empty($country_detail[0]['title']))
                {
                ?>
                    <?php echo get_phrase('nationality');?>: 
                    <?php
                    echo string_with_br($country_detail[0]['title']);
                    ?>
                <?php
                }
                ?>
                <?php echo get_phrase('status');?>: 
                <?php
                if ($value['status']==1)
                {
                      echo get_phrase('active')."<br>";
                }
                else
                {
                     echo get_phrase('in-active')."<br>";
                }
                ?>
                <?php
                if ($value['is_teacher']==1)
                {
                ?>
                    <?php echo get_phrase('is_teacher');?>: 
                    <?php echo string_with_br($value['is_teacher']);?>
                    <?php echo get_phrase('periods_per_day');?>: 
                    <?php echo string_with_br($value['periods_per_day']);?>
                    <?php echo get_phrase('periods_per_week');?>: 
                    <?php echo string_with_br($value['periods_per_week']);?>
                <?php
                }
                ?>
            </td>
        </tr>

        <?php
        }
        ?>
        </tbody>
        </table>
        <?php
            if (empty($staff_listing_arr))
            {
                ?>
                <div style="text-align: center;">
                    <h2><?php echo get_phrase('no_data_available');?></h2>
                </div>
                <?php
            }
        ?>
    </div>