

<style>
	.detail alert-success{font-size:13px !important;}

</style>



<?php
include('header.php');
?>
      

       
       
       
      
       
       
       <div class="container">
       	<div class="row">
       		<div class="col-sm-4"></div>
       		<div class="col-sm-4 well" style="margin-top:100px;">
       		                   
              
            <div style="    background-color: #4a8ab9;
    padding: 10px;
    margin: -19px -19px 50px -19px;
    text-align: center;
    font-size: 16px !important;
    color: #FFF;
              font-weight:bold;">
               
                
                        <?php
                        if ($this->session->flashdata('code_sent_success') == '')
                        {?>
                            <div class="detail">
                                <p style="font-size:16px; "><?php echo get_phrase('enter_registered_email_for_password_recovery'); ?>.</p>
                            </div>
                        <?php
                        }
				
				
				else { ?>
					
					
					  <p style="font-size:16px; "><?php echo get_phrase('password_recovery'); ?></p>
					<?php
					
				}?>
                  
          
            </div>
        
                	
       			
       			        <div class="detail" style="font-size:13px !important;">
                        <?php
                        if ($this->session->flashdata('code_sent_failed') != '')
                        {?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('code_sent_failed'); ?>
                            </div>
                        <?php
                        }
                        if ($this->session->flashdata('code_sent_success') != '')
                        {?>
                            <div>
                                <?php echo $this->session->flashdata('code_sent_success'); ?>
                            </div>
                        <?php
                        }
                        else
                        {?>
                            <form action="<?php echo base_url('recover_password/submit_email'); ?>" method="post" class="form-horizontal form-groups-bordered validate" >    

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo get_phrase('email'); ?>            </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="email" data-validate="required" data-rule-email="true" data-message-required="Value Required" data-message-email="Please Enter a valid email." >
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-5">
                                        <button type="submit" class="btn btn-info">
                                            <?php echo get_phrase('submit'); ?>                
                                        </button>
                                    </div>
                                </div>
                           
                            </form>
                        <?php
                        }
                        ?>
                
                    </div>
                    
     
            
         
            <div class="pull-right">
                <a class="black black2"  href="<?php echo base_url('login') ?>" style="text-decoration: underline;"><?php echo get_phrase('Click_here'); ?> </a><?php echo get_phrase('to_login'); ?>.
            </div>
   
                    
       		</div>
      
       	</div>
    
       </div>
       
  
       
       
       
       
       
       
       
       

<?php
include('footer.php');
?>   
  