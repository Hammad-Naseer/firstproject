<div class="col-lg-12 col-md-12 col-sm-12">
                		    <script>
    $(window).load(function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/challan-form-icon.png">Email Templates</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php
            $count = 0;
            foreach($email_temp_arr as $value)
            {
                $count++;
            ?>
                <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne<?php echo $count;?>">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count;?>" aria-expanded="true" aria-controls="collapse<?php echo $count;?>">
                      <i style="color:white;" class="entypo-mail"></i><?php echo strtoupper($value['email_title']);?>
                      <?php
                        if($value['email_template_status']==1)
                        {
                            echo '<span style="color:green;font-size: 15px;">(Active)</span>';
                        }else{
                            echo '<span style="color:red;font-size: 15px;">(InActive)</span>';
                        }
                        ?>
                    </a>
                  </h4>
                </div>
                <?php
                $in = "";
                if($count == 1)
                {
                    $in = "in";
                }
                ?>
                <div id="collapse<?php echo $count;?>" class="panel-collapse collapse <?php echo $in;?>" role="tabpanel" aria-labelledby="headingOne<?php echo $count;?>">
                  <div class="panel-body">
                        <div class="col-lg-8">
                            <strong><?php echo get_phrase('Email_Subject'); ?></strong> : <?php echo $value['email_subject'];?>
                            <br>
                            <strong><?php echo get_phrase('Email_content'); ?></strong> : <?php echo $value['email_content'];?>
                        </div>
                        <div class="col-lg-4" style="text-align:right;">
                            <a class="btn btn-sm" href="<?php echo base_url();?>templates/edit_email_temp/<?php echo $value['email_temp_id']?>">
                                <?php echo get_phrase('edit');?>
                            </a>
                            <a class="btn btn-sm" href="<?php echo base_url();?>templates/delete_email_temp/<?php echo $value['email_temp_id']?>">
                                <?php echo get_phrase('delete');?>
                            </a>
                        </div>
                  </div>
                </div>
              </div>
            
            <?php
            }
            ?>
            </div>
        </div>
    </div>
    
</div>
    
    <!-- notify alert -->
    <script src="<?php echo base_url();?>assets/notify/notify.min.js"></script>
    <!-- page script -->
    <?php
      include('includes/bottom.php');
    ?>
    <script>
      $(document).ready(function(){
        <?php
        if ($this->session->flashdata()){
        ?>
          $.notify("<?php echo $this->session->flashdata('info');?>","success");
        <?php
        }
        ?>
      });
    </script>