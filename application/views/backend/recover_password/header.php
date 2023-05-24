<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
	//session_start();
	?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Admin Panel" />
    <meta name="author" content="" />
    <title><?php echo get_phrase('GMINNS'); ?>|<?php echo get_phrase('school_information_management_system'); ?></title>
  
    <script src="<?php echo base_url();?>assets/js/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/switch_user.css">
    <script src="https://use.fontawesome.com/06111aeca4.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/common.js"></script>

    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png">
   "
</head> 

<body>
    <header id="header-admin">
        <div class="container">
        	<div class="row">
            	<div class="col-lg-2 col-md-2 col-sm-2">
                	<div class="co-logo"><img src="<?php echo base_url();?>assets/images/g-sims-low-res.png" alt="" width="100%"></div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7 text-center">
                	<h2>G-SIMS (GMINNS School Information Management System)</h2>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <ul class="teacherdetail">
              
                    
                    </ul>
                </div>
            </div>
        </div>
    </header>



    <section>