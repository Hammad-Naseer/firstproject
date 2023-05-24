<div style="border-top:10px solid #21a9e1;border-bottom:10px solid #21a9e1;border-left:1px solid #f3f3f3;border-right:1px solid #f3f3f3;height:560px; padding:60px 0px 30px 0px;">
    <div id="header" style="height: 100px; width: 100%;">
        <div style="float: left; margin-top: -15px; height: 100px; width: 150px;"> 
            <?php
                $d_school_id = $_SESSION['school_id'];
                $logo = system_path($_SESSION['school_logo']);
                if($_SESSION['school_logo'] == "" || !is_file($logo))
                {
            ?>
                <a href="">
                    <img style="width: 150px; height: 100px; margin-top: -15px;" src="<?php //echo base_url();?>assets/images/gsims_logo.png">
                </a>
            <?php
                }else{
                    $img_size = getimagesize("uploads/".$_SESSION['folder_name']."/".$_SESSION['school_logo']."");
                    $img_width = $img_size[0];
                    $img_height = $img_size[1];
                ?>
                <a href="">
                    <img style="margin-top: -15px;width:<?php if ($img_width>200) {$img_width = 200;}echo $img_width."px;"; ?>
                    height:<?php if ($img_height>200) {$img_height = 200;}echo $img_height."px;"; ?>" 
                    src="<?php //echo base_url();?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>">
                </a>
            <?php } ?>
        </div> 
        <div style="float: right; margin-left: 100px;"> 
            <img src="<?php echo "assets/certificate_ribbons/".$ribbon ;?>" style="width:510px;margin-top:30px;margin-right:30px;">
        </div>
    </div>
    <div id="table_data" style="margin: 50px;">
        <p style="text-align: justify;font-size:26px !important;">
           <?= $certificate_data; ?>
        </p>
    </div>
    <div id="foter" style="height: 100px; width: 100%; margin: 40px;">
        <div style="float: left; margin-top: -15px; height: 100px; width: 50%;text-align:center;"> 
            <p style="font-size:26px !important;color:#21a9e1"><span><?php echo date('d-M-Y'); ?></span></p>
            <p style="font-size:26px !important;">Issue Date</p>
        </div>
        <div style="float: right; margin-top: 5px; margin-left: 100px;width: 50%;text-align:center;"> 
            <p style="font-size:26px !important;color:#21a9e1"><span>Head Department</span></p>
            <p style="font-size:26px !important;">Signature</p>
        </div>
    </div>
</div>
