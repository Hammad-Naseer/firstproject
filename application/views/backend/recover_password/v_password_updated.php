

<?php
include('header.php');

if ($this->session->flashdata('password_updated') == '')
{
    redirect(base_url() . 'login');
}
?>
        <div class="container-fluid">
            <div class="row school-admin">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="main-school">
                        <?php
                        if ($this->session->flashdata('password_updated') != '')
                        {?>
                          
                        <?php
                        }?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row school" style="margin-bottom:30px;">
                <div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 well">
                    
                    
                    
                        
                         <div style="    background-color: #4a8ab9;
    padding: 10px;
    margin: -19px -19px 50px -19px;
    text-align: center;
    font-size: 16px !important;
    color: #FFF;
              font-weight:bold;"> 
                </div>    
                    
                    <div class="detail">
                        
                        <?php
                        if ($this->session->flashdata('password_updated') != '')
                        {?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('password_updated'); ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    
                     
            <div class="pull-right">
                <a class="black black2"  href="<?php echo base_url('login') ?>" style="text-decoration: underline;"><?php echo get_phrase('click_here'); ?> </a> <?php echo get_phrase('to_login'); ?>.
            </div>
   
                </div>
            </div>
        </div>
 

<?php
include('footer.php');
?>    