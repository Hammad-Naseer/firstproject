<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
	//session_start();
    if ($_SESSION['user_login'] == 1)
        redirect(base_url() . ''.get_login_type_controller($_SESSION['login_type']).'/dashboard');
	?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Admin Panel" />
    <meta name="author" content="" />
    <title><?php echo get_phrase('GSIMS'); ?>|<?php echo get_phrase('school_information_management_system');?></title>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/neon-core.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/neon-theme.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/neon-forms.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/custom.css">
    <script src="<?php echo base_url();?>assets/js/jquery-1.11.0.min.js"></script>
   
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png">
    <script>
    function move() {
        var elem = document.getElementById("myBar");
        var width = 0;
        var id = setInterval(frame, 15);

        function frame() {
            if (width >= 100) {
                clearInterval(id);
            } else {
                width++;
                elem.style.width = width + '%';
                document.getElementById("label").innerHTML = width + '%';
            }
        }
    }
    </script>
    <script>
    $(document).ready(function() {
        $('#progress-btn').click(function(e) {
            e.preventDefault();
            move();
            setTimeout(myFunction, 1500)
        });
    });

    function myFunction() {
        $('#my_form').submit();
    }
    </script>
<style>
.mgt {
margin-top: 71px;
}

.mgt2 {
margin-top: 92px;
/*background: rgba(0, 0, 0, 0.29);*/
/*-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#40000000, endColorstr=#40000000)";*/
}

.bgc {
background-color: rgba(0, 0, 0, 0.29);
box-shadow: 0px 0px 4px 1px #CCC;
cursor: pointer;
padding: 5px;
color: #FFF;
padding-left: 15px;
min-height: 118px;
height: auto;
}

.mybg {
background: url("<?php echo base_url();?>assets/images/307.jpg?<?php echo time();?> ") top center no-repeat !important;
background-size: cover !important;
}

@media(max-width:500px) {
.mybg {
    background: url("<?php echo base_url();?>assets/images/bg.jpg?<?php echo time();?> ") no-repeat;
    background-size: 289% 110%;
}
.mgt {
    margin-top: 0px;
}
.mgt2 {
    /*background-color: rgba(0, 0, 0, 0.53);*/
    margin-top: 0px;
    width: 100%;
}
.mgt2.blcdiv {
    width: 100%;
}
.log {
    float: none !important;
}
.login_detail {
	   width: 65%;
   float: none !important;
}
}


.mgt h4 {
color: #FFF;
}

.white {
color: #FFF;
}

.pd {
padding: 0 15px;
}

.hg {
min-height: 75px;
}

.black {
color: #000;
font-weight: bold;
}

.left {
text-align: left;
}

.pst {
position: relative;
top: 70px;
}

.log {
background-color: #ffffff;
/*-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCFFFFFF, endColorstr=#CCFFFFFF)";*/
padding: 10px;
border-radius: 8px;
width: 140px;
float: left;
margin-right: 12px;
}

#btnadmin,
#btnprincipal,
#btnteacher,
#btnparent,
#btnaccountant,
#btnstudent {
background: rgba(255, 255, 255, 0.92);
color: #000;
border: 1px solid transparent;
    padding: 11px;
border: 1px solid #eeeeee;
}

#btnadmin:hover,
#btnprincipal:hover,
#btnteacher:hover,
#btnparent:hover,
#btnaccountant:hover,
#btnstudent:hover {
background: #4a8ab9;
color: #ffffff;
}

.form-control {
height: 41px !important;
border-radius: 5px;
}

.mw {
width: 100px;
font-weight: Bold;
Color: #d21000;
}

.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary.active,
.open .dropdown-toggle.btn-primary {
color: #000;
background-color: #ded8d8;
border-color: #c0c0c0;
}

.btn-primary {
color: #6C5858;
background-color: #e2dcda;
border-color: #E0E0E0;
}

.fea {}

.blcdiv {
width: 470px;
height: auto;
padding-top: 12px;
padding-bottom: 12px;
display: block;
position: relative;
-moz-border-radius: 20px;
-webkit-border-radius: 20px;
-o-border-radius: 20px;
border-radius: 20px;
}

.co-info {
padding: 30px 0px 0px 0px;
border-top: 1px solid #ccc;
}

.co-info p {
font-size: 16px;

text-align: justify;
word-spacing: -1px;
padding-top: 20px;
}

.company-info a {
padding: 0px 10px;
color: #000099;
font-size: 16px;
}

.company-info a:first-child {
border-right: 1px solid #8d8d8d;
}

.company-info p a {
padding: 0px;
}

.company-info p a:first-child {
border-right: none;
}

#footer {
padding: 20px 0px 10px 0px;
/* background: #f6f6f6; */
}
/*.w354{
width: 354px;
}	*/
/* If you want the label inside the progress bar */

#myProgress {
position: relative;
width: 100%;
height: 5px;
/* background-color: #ddd;*/
}

#myBar {
position: absolute;
width: 0%;
height: 100%;
background-color: #4CAF50;
/*background-color:#4a8ab9;*/
}

#label {
text-align: center;
line-height: 30px;
color: white;
}

.login_detail {
width: 65%;
float: right;
}

.input-group-addon {
font-size: 13px;
}

.btn-default.dropdown-toggle {
border-left-color: #dedee0;
width: 155px;
}
.mylog{   
background: rgba(0, 0, 0, 0.4);
margin:0px auto 320px;
border-radius: 10px;
padding: 45px 30px 16px 30px;
width: 50%;
}
.heading-content span{
display:block;
font-size:28px;
}
.heading-content h2{
height:7em;
font-size:36px;

}
.company-info {
color: #000099;
font-weight: bold;
font-size: 16px;
}
@media only screen and (max-width: 768px) {
.mylog {
    width: 100% !important;
}
.heading-content h2 {
    height: 10em !important;
    font-size: 36px;
}
}
</style>
</head>

<body class="page-body login-page login-form-fall mybg">
    <div class="container">
    	<div class="heading-content text-center">
    		<h2></h2>
        </div>
        <?php
        /*
        <p style="text-align:center; background:rgba(255, 255, 255, 0.6); padding:10px 0px; font-size:22px; text-transform:uppercase; color:#000;"><a href="http://gsims.com.pk/#demo" target="_blank" style="text-decoration:underline;">Click here</a> for demo</p>	
        */
        ?>
        <div class="login mylog">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="login_error text-center">
                        <?php 
                        echo $_SESSION['user_login'];
                        if(isset($_SESSION['msg']))
                        { 
                            if ( $_SESSION['msg']=='invalid' )
                            {?>
                                <div class="form-login-error" style="display:block">
                                    <h3>Invalid login</h3>
                                    <p>Please enter correct email and password!</p>
                                </div>
                            <?php 
                            }
                            elseif( $_SESSION['msg']=='inactive' )
                            { ?>
                                <div class="form-login-error" style="display:block">
                                    <h3>Sorry!</h3>
                                    <p>Your account is inactive. Please contact admin.</p>
                                </div>
                            <?php 
                            } 
                            elseif ($_SESSION['msg']=='error')
                            {?>
                                <div class="form-login-error" style="display:block">
                                    <h3>System Error!</h3>
                                    <p>Please contact system administrator.</p>
                                </div>
                            <?php
                            }
                        }

                        ?>
                    </div>
                    <center>
                        <a href="http://gsims.com.pk/" target="_blank" class="logo">
                    <img class="img-responsive log" src="<?php echo base_url();?>uploads/new_logo.png"  alt="" >
                </a>
                        <div class="login_detail">
                            <h2 style="color:#FFF; font-weight:100;">
                                <?php/* echo $system_name;*/?>
                            </h2>
                            <form id="my_form" name="myform" method="post" role="form" action="<?php echo base_url(); ?>login/ajax_login">
                                <div class="form-group">
                                    <div class="input-group" style="background-color:white;    border-radius: 10px;">
                                        <div class="input-group-addon" style="background:#4a8ab9; color:#ffffff; border:none;">
                                            <i class="entypo-user"></i>
                                        </div>
                                        <input style="color:black" type="text" class="form-control" name="email" id="email" placeholder="Email" data-mask="email" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group" style="background-color:white;    border-radius: 10px;">
                                        <div class="input-group-addon" style="background:#4a8ab9; color:#ffffff; border:none;">
                                            <i class="entypo-key"></i>
                                        </div>
                                        <input style="color:black" type="password" class="form-control" name="password" id="password" placeholder="Password" />
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 " style="padding:0px;">
                                   
                  
                         <select class="form-control" id="menu1" onchange="putvalue()">
                        <option value="" >Select User</option>
                        <option value="admin">Admin</option>
                  
                        <option  value="teacher">Teacher</option>
                         <option  value="parent">Parent</option>
                   <!--      <option   value="student">Student</option>
                         <option  value="principal">Principal</option>-->
                                   
                          </select>
                          
								</div>
                                           <div class="form-group col-sm-6 "> 
                                    
                                    <button id="progress-btn" style="background-color:#4a8ab9; border:1px solid transparent;    padding: 11px 34px;" type="submit" class="btn btn-success"><i class="entypo-login"></i> Sign In</button>
                                </div>
                            <?php /*?>    <div style="color:#FFF; margin-top:70px; ">
                                    Forgot Password? <a class="white" href="<?php echo base_url('recover_password') ?>">Click here</a>
                                </div>
                                <div class="col-sm-12">
                                	<a style="color: #FFF;
    float: right;
    margin-top: 31px;" href="https://www.youtube.com/embed/fvjCNXd9xGA" target="_blank">Gsims Features</a>
                                </div>  <?php */?> 
                            </form>
                        </div>
                    </center>
                    </br>
                    </br>
                </div>
  
            </div>
            
            
            
            
            
            
            
            
            
            <div id="myProgress">
                <div id="myBar">
                    <div id="label"></div>
                </div>
            </div>
            <div onclick="move()"></div>
<?php /*?>            <div class="co-info">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p style="color:#ffffff;">G-SIMS (GMIINS- School Information Management System) is a cloud based online management information system, which offers much more than a world-class management information system - it helps educational institutions inspire with efficiency & effectiveness. We’ve worked closely and dedicatedly alongside schools and other educational institutions to grasp the on ground educational specific processes knowledge prior automating it into G-SIMS. It is primarily for the betterment of the educational houses, helping them to drive improvement and to support every child to achieve goals.</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="fea">

                        
<!--              
                      
<iframe width="100%" height="260" src="https://www.youtube.com/embed/i6mjhFqzU28?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>                      
                      -->
                        
<!--<iframe width="100%" height="260" src="https://www.youtube.com/embed/kQfi_p11xQ8" frameborder="0" allowfullscreen></iframe>-->
       
	<!--	<iframe width="100%" height="260" src="https://www.youtube.com/embed/dKRJrxMW8mY" frameborder="0" allowfullscreen></iframe>	-->			
             	<iframe width="100%" height="260" src="https://www.youtube.com/embed/fvjCNXd9xGA" frameborder="0" allowfullscreen></iframe>				
                                                                                                                                                                                            
                        </div>
                    </div>
                </div>
            </div><?php */?>
        </div>
        
    </div>
    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <div class="company-info">
                        <a href="#"><i class="fa fa-phone" aria-hidden="true"></i>+92-51-8430813 Ext: 202</a>
                        <a href="mailto:info@gsims.com.pk"><i class="fa fa-envelope" aria-hidden="true"></i>
                    info@gsims.com.pk</a>
                        <p style="font-size:14px;">Copyright &copy; GMINNS 2016. All rights reserved. <strong><a href="http://gsims.com.pk/" target="_blank" style="color:#000;">G-SIMS</a></strong> is powered by <strong><a href="http://gminns.com/" target="_blank" style="color:#000;">GMINNS</a></strong>.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    


    
    
    
    
    
    
    
    
    
    
    
    
    
<script>
function putvalue() {
    if ($("#menu1").val()=='admin')
    {
        $("#email").val("admin@gminns.com");
        $("#password").val("gminns");
    }		
    else if ($("#menu1").val()=='principal')
    {
        $("#email").val("principal@gminns.com");
        $("#password").val("gminns");
    }		
    else if ($("#menu1").val()=='teacher')
    {
        $("#email").val("teacher@gminns.com");
        $("#password").val("gminns");
    }		
    else if ($("#menu1").val()=='parent')
    {
        $("#email").val("parent@gminns.com");
        $("#password").val("gminns");
    }		
    else if ($("#menu1").val()=='student')
    {
        $("#email").val("student@gminns.com");
        $("#password").val("gminns");
    }		
    else{
        $("#email").css("border","2px solid red");
        $("#password").css("border","2px solid red");
        $("#email").val("");
        $("#password").val("");
    }
}
</script>  
									
									
									
									
									
									
									
									
    <!-- This is needed when you send requests via Ajax -->
    <script type="text/javascript">
    var baseurl = '<?php echo base_url();?>';
    </script>
    <script>
		
		
		
		
    $("#btnadmin").click(function() {
        $("#email").val("admin@gminns.com");
        $("#password").val("gminns");

    });
    $("#btnprincipal").click(function() {
        $("#email").val("principal@gminns.com");
        $("#password").val("gminns");

    });
    $("#btnteacher").click(function() {
        $("#email").val("teacher@gminns.com");
        $("#password").val("gminns");

    });
    $("#btnparent").click(function() {
        $("#email").val("parent@gminns.com");
        $("#password").val("gminns");

    });
    $("#btnstudent").click(function() {
        $("#email").val("student@gminns.com");
        $("#password").val("gminns");

    });

    $("#btnaccountant").click(function() {
        $("#email").val("accountant@gminns.com ");
        $("#password").val("gminns");

    });
    </script>
    <!-- Bottom Scripts -->
    <script src="<?php echo base_url();?>assets/js/gsap/main-gsap.js"></script>
    <script src="<?php echo base_url();?>assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
    <script src="<?php echo base_url();?>assets/js/joinable.js"></script>
    <script src="<?php echo base_url();?>assets/js/resizeable.js"></script>
    <script src="<?php echo base_url();?>assets/js/neon-api.js"></script>
    <script src="<?php echo base_url();?>assets/js/jquery.validate.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/neon-login.js"></script>
    <script src="<?php echo base_url();?>assets/js/neon-custom.js"></script>
    <script src="<?php echo base_url();?>assets/js/neon-demo.js"></script>
</body>

</html>
<?php
if(isset($_SESSION['msg']))
session_destroy();
?>
