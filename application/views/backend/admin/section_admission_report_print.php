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
            <?php echo get_phrase('section_wise_admission_report');?>
        </h4>
    </div>
    </div>
    <div id="table_data" style="margin-top: 20px;">
        <table border="1px">
       <!--  table table-bordered table-hover table-condensed table-responsive -->
        <thead>
        <tr>
        <th style="width: 25px;"><?php echo get_phrase('sr');?>.</th>
        <th><?php echo get_phrase('std_name_with_father_name');?></th>
        <th><?php echo get_phrase('class_section');?></th>
        <th><?php echo get_phrase('dob');?></th>
        <th><?php echo get_phrase('gender');?></th>
        <th><?php echo get_phrase('religion');?></th>
        <th><?php echo get_phrase('address');?></th>
        <th><?php echo get_phrase('nationality');?></th>
        <th><?php echo get_phrase('date_of_admission');?></th>
        <th><?php echo get_phrase('mobile_no');?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $count =0;
        foreach ($student_listing_arr as $key => $value)
        {
            $count++;
        ?>
        <tr>
            <td><?php echo $count;?></td>
            <td valign="top">
                <?php echo $value['std']; ?>
            </td>
            <td valign="top">
                <?php echo $value['class']; ?> / <?php echo $value['section']; ?>
            </td>
            <td valign="top">
                <?php echo $value['birthday']; ?>
            </td>
            <td valign="top">
                <?php echo $value['gender']; ?>
            </td>
            <td valign="top">
                <?php echo $value['religion']; ?>
            </td>
            <td valign="top">
                <?php echo $value['address']; ?>
            </td>
            <td valign="top">
                <?php echo $value['nationality']; ?>
            </td>
            <td valign="top">
                <?php echo $value['adm_date']; ?>
            </td>
            <td valign="top">
                <?php echo $value['phone']; ?>  <?php echo $value['mob_num']; ?>
            </td>
        </tr>

        <?php
        }
        ?>
        </tbody>
        </table>
        <?php
            if (empty($student_listing_arr))
            {
                ?>
                <div style="text-align: center;">
                    <h2><?php echo get_phrase('no_data_available');?></h2>
                </div>
                <?php
            }
        ?>
    </div>