
<style>


	.error{color:red;}
</style>


<?php
include('header.php');
?>
 
        <script>
            $().ready(function() 
            {
                $("#password_form").validate({
                    rules: {
                        
                        password: {
                            required: true,
                        },
                        confirm_password: {
                            required: true,
                            equalTo: "#password"
                        }
                    },
                    messages: {
                        password: {
                            required: "Please provide a password",
                        },
                        confirm_password: {
                            required: "Please provide a password",
                            equalTo: "Please enter the same password as above"
                        }
                    }
                });
            });

        </script>
        <div class="container" style="margin-top:100px;">
            <div class="row school" style="margin-bottom:30px;">
                <div class="well col-lg-4 col-md-4 col-sm-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
                   
                    
                     
                      
                       
                         <div style="    background-color: #4a8ab9;
    padding: 10px;
    margin: -19px -19px 50px -19px;
    text-align: center;
    font-size: 16px !important;
    color: #FFF;
              font-weight:bold;">
               
                   
                                                    <div class="detail">
                                <p style="font-size:16px; ">
                                	
                                	
                                	 <?php
                        if ($this->session->flashdata('invalid_code') == '')
                        {?>
                            <div class="detail">
                                <p style="font-size:16px; "><?php echo get_phrase('enter_new_password'); ?></p>
                            </div>
                        <?php
                        } else{
                        ?>
                                	
                        
                               	
		<p style="font-size:16px; "><?php echo get_phrase('code_verification'); ?></p>	
                               <?php 	}   ?>    	
                                	
                                </p>
                            </div>
                                          
          
            </div>
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                    <div class="detail">
                        <?php
                        if ($this->session->flashdata('invalid_code') != '')
                        {?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('invalid_code'); ?>
                            </div>
                        <?php
                        }
                        else
                        {
                            if ( !isset($email) || $email == '')
                            {
                                redirect(base_url('login'));
                            }
                            ?>
                            <form id="password_form" action="<?php echo base_url('recover_password/submit_new_password'); ?>" method="post" class="form-horizontal form-groups-bordered validate" >    
                                <input type="hidden" name="field1" value="<?php echo $email ?>" >
                                <input type="hidden" name="field2" value="<?php echo $code ?>" >

                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php echo get_phrase('login_ID'); ?>: </label>
                                    <div class="col-sm-8">
                                        <?php echo $user_email; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php echo get_phrase('new_password'); ?>: </label>
                                    <div class="col-sm-8">
                                        <input type="password" name="password" id="password" class="form-control" >
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php echo get_phrase('re_enter_password'); ?>  </label>
                                    <div class="col-sm-8">
                                        <input type="password" name="confirm_password" class="form-control" >
                                    </div>
                                </div>

                               
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-5">
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
                </div>
            </div>
        </div>
        
<?php
include('footer.php');
?>
