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
    <div style="float: left; margin-top: -15px; height: 100px; width: 150px;"> 
        <?php
        $d_school_id = $_SESSION['school_id'];
        {
            $logo=system_path($_SESSION['school_logo']);
            if($_SESSION['school_logo']=="" || !is_file($logo))
            {
            ?>
            <a href="">
                <img style="width: 150px; height: 100px; margin-top: -15px;" src="<?php //echo base_url();?>assets/images/gsims_logo.png">
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
            <img style="margin-top: -15px;
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
                " src="uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>">
            </a>
            <?php
            }
        }
        
        ?>
    </div>
    <div style="float: left; margin-top: -15px; margin-left: 100px;"> 
        <h2 style="text-transform: uppercase; letter-spacing: 5px;">
            <b><?php echo $_SESSION['school_name']; ?></b>
        </h2>
    </div>
</div>
    
<div style="width:1000px; height:500px; padding:20px; text-align:center; border: 5px solid #787878">
       <span style="font-size:50px; font-weight:bold">
        <?php echo get_phrase('school_leaving_certificate');?>
        </span>
       <br><br>
       <span style="font-size:25px"><i>This is to certify that</i></span>
       <br><br>
       <span style="font-size:30px"><b><?php echo get_phrase($student_data);?></b></span><br/><br/>
       <span style="font-size:25px"><i>has completed the course</i></span> <br/><br/>
       <span style="font-size:30px"><?php echo get_phrase($section_name);?></span> <br/><br/>
       <span style="font-size:25px"><i>dated</i></span><br>
      
      <span style="font-size:30px"><?php echo date("Y/m/d");?></span>

</div>
