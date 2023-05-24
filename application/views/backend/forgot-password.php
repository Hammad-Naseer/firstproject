<?php 
    if ($_SESSION['user_login'] == 1)
    redirect(base_url() . ''.get_login_type_controller($_SESSION['login_type']).'/dashboard');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Indici-Edu Forget Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/loginpage/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script> 
    <script>
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if(response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">This Field Is Required</span>';
                return false;
            }
            return true;
        }
         
        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }
    </script>
    
    <style type="text/css">
        p#image_captcha > img {
            width: 275px;
            height: 60px;
            position: relative;
            top: -30px;
        }
    </style>
    <style type="text/css">

    body { 
     height: 100vh;
    }
    .bg-drk-blu {
        background-color: #1f396f;
    }
    img.dwnld-img {
        width: 26px;
        margin-right: 10px;
    }.dwnld-row p {
        line-height: 1;
        font-size: 12px;
        font-weight: 600;
    }.dwnld-row a {
        background: white;
        border-radius: 50px;
        padding: 10px 20px;
        align-items: center;text-decoration: none;
        color: #000;margin: 0px 10px;
    }.dwnld-row a:hover {
        background: black;
        color: #fff;
        transition: 0.2s;
    }p.m-0.txt-plystore {
        font-size: 12px;
        margin-top: 4px !important;
        font-weight: 700;
    }.input-group { 
        height: 60px; 
    }.input-group-text,.form-control {
        border-radius: 2.25rem;
    }.btn {
        background: transparent; 
        width: 100%;
        border: solid 2px #fff;
        border-radius: 50px;
        line-height: 39px;margin: 0px 3px;
    }.btn-primary:hover {
        color: #000;
        background-color: #ffffff;
        border-color: #ffffff;
    }.intro-indiciedu {
        background-image: url(<?php echo base_url(); ?>assets/loginpage/curevss.png);
        background-repeat: no-repeat;
        background-size: cover;
    }.set-size {
        width: 76%;
        margin-left: 13% !important;
    }.intro-indiciedu video.intoro-lgin-vido {
        box-shadow: 0px 0px 51px 0px #0000003d;
        border-radius: 13px;
    }a.signup-txt {
        font-size: 20px;
        font-weight: 400;
        color: #1ee0ff;
        text-decoration: none; 
    }p {
        line-height: 21px;
    }a.signup-txt:hover {
        color: #3dc9e3;
    }h4 { 
        color: #00bce0;
    }.login-colone {
        padding-left: 3.5rem !important;
        padding-right: 3.5rem !important;
    }.account-subtitle hr.set-hrleft {
        border-top: 6px solid #27a7cb;
        width: 50px;
        margin-right: 0px;
    }.account-subtitle hr.set-hrright {
        border-top: 6px solid #27a7cb;
        width: 50px;
        margin-left: 0px;
    }.account-subtitle span {
        margin-right: 9px !important;
        margin-left: 9px;
        color: #fff;
    }.account-subtitle {
        color: #fff;
        font-size: 18px;
        margin-bottom: 22px;
        text-align: center;
        display: flex;
        align-items: center;
    }hr { 
        opacity: 1;
    }
    .lgin-fthrs-info p {
        font-size: 14px;
    }
    img.indici-feature {
        width: 76px;
    }
    .account-subtitle span { 
    font-size: 14px;
}
    /* img.indici-feature {
        width: 150px;
    } */
    .btn {
    background: #229dce; 
}.input-group.mb-3 {
    display: flex;
    justify-content: center;
}
/*.input-group.mb-3 {*/
/*    display: flex;*/
/*    justify-content: center;*/
/*    background: #f9f9f9;*/
/*    border-radius: 50px;*/
/*    height: 76px;*/
/*}.rc-anchor {*/
/*    border-radius: 3px;*/
/*    box-shadow: none !important;*/
/*    -webkit-box-shadow: 0 0 4px 1px rgb(0 0 0 / 8%);*/
/*    -moz-box-shadow: 0 0 4px 1px rgba(0,0,0,0.08);*/
/*}.rc-anchor-light.rc-anchor-normal {*/
/*    border: none !important;*/
/*}*/
    @media only screen and (max-width: 768px)  {
        .set-size {
        width: 80%;
        margin-left: 7% !important;
    }.intro-indiciedu {
        padding: 0px !important;
    } .login-colone .input-group {
        height: 44px;
    }.btn { 
        line-height: 26px; 
    }.dwnld-row a { 
        padding: 5px 15px; margin: 0px 5px;
    }
    .col-sm-6{
        width: 50%;
    }body {
        height: auto;
    }.set-size {
        padding: 40px 0px;
    }
    }
    
    @media only screen and (max-width: 475px)  {
    .set-size {
        width: 88%;
        margin-left: 7% !important;
    }
    }
    
    
    @media only screen and (min-width: 475px)  {
    
/*        .rc-anchor-light.rc-anchor-normal {*/
/*            border: 1px solid #d3d3d3;*/
/*            border-radius: 110px !important; */
/*            width: 386px !important; */
/*        }.rc-anchor-logo-portrait {*/
/*            margin: 10px 0 0 102px !important; */
/*            width: 58px !important; */
/*        }iframe {*/
/*            border: 0;*/
/*            width: 393px !important; */
/*        }.rc-anchor-normal {*/
/*    height: 74px !important;*/
/*    width: 389px !important;*/
/*    border-radius: 50px !important;*/
/*}*/
    }
    
    </style>
  </head>
  <body>
    <div class="container-fluid  h-100 d-flex align-items-center bg-drk-blu">
      <div class="w-100 h-100">
        <div class="row h-100 justify-content-between">
            <div class="col-sm-12 col-md-12 col-lg-4 login-colone h-100   py-3 d-flex flex-column justify-content-center" id="logindiv">
              <img src="<?php echo base_url(); ?>assets/loginpage/indici-whitelogo.svg" class="w-75 mx-auto pt-5">
              
                <div class="account-subtitle m-0 text-center py-3 text-white mx-auto"><hr class="set-hrleft"><span>Enter the email address you used when joined and we'll send you instrustions to reset your password</span><hr class="set-hrright"><p></p></div>
                <?php if($this->session->flashdata('club_updated')){echo '
                        <div align="center">
                            <div class="alert alert-success alert-dismissable" role="alert">
                                '.$this->session->flashdata('club_updated').'
                            </div>
                        </div>
                ';} ?>
                <?php if($this->session->flashdata('error_login')){echo '
                        <div align="center">
                            <div class="alert alert-danger alert-dismissable" role="alert">
                                '.$this->session->flashdata('error_login').'
                            </div>
                        </div>
                ';} ?>
                <?php if(isset($_SESSION['success_Parent_account']) && $_SESSION['success_Parent_account']==1){ ?>
                <div class="form-login-error" style="display: block;"><p style="color: #fff;">Your Account Successfully Created Please Login.</p></div>
                <?php }unset($_SESSION['success_Parent_account']);if(isset($_SESSION['error']) && $_SESSION['error']=='1'){ ?>
                <div class="alert alert-danger" style="background: #f44336; padding: 10px; color: #fff !important;">
                    <div class="form-login-error" style="display: block;"><h5>Invalid login</h5></div>
                </div>
                <?php }unset($_SESSION['error']);if(isset($_SESSION['error']) && $_SESSION['errors']=='1'){ ?>
                <div class="form-login-error" style="display: block;"><h5 style="color: red;">Only Student Allowed CNIC login</h5></div>
                <?php }unset($_SESSION['errors']);if(isset($_SESSION['error_restrict_login']) && $_SESSION['error_restrict_login']!=''){ ?>
                <div class="form-login-error" style="display: block;"><h5 style="color: red;">Account Access Blocked</h5></div>
                <?php }unset($_SESSION['error_restrict_login']); ?>
                <form id="my_form" name="myform" class="signin-form pb-3" method="post" action="#">
                    <div class="row my-4">
                        <div class="input-group">
                         <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                         <input type="text" class="form-control" id="email" name="email" placeholder="Email" required aria-label="Email" aria-describedby="basic-addon1">
                        </div> 
                    </div>
                    <div class="row mb-3">
                        <div class="input-group mb-3">
     	            	  <div class="g-recaptcha" data-sitekey="6LdyrA0bAAAAAKOCZ4qv6EzmrwEtFdYIzMgrJ4Qz" data-callback="verifyCaptcha"></div>
    	            	  <div id="g-recaptcha-error"></div>
        	            </div>
                    </div>
                    <!--Email Verification-->
                    <button type="button" id="forgot" class="btn btn-primary">Verify Email</button>
                    <!--Verify Email OTP-->
                    <p id="code_section" style="margin-top: 40px;display:none">
                        <input type="text" class="form-control" name="code" id="code" placeholder="Enter Code" aria-label="Enter Code">
                        <p id="error1" style="color:red;"></p>
                        <button type="submit" id="verify_code" style="display:none;" class="button btn btn-primary">Verify Code</button>
                    </p>
                </form>
                <!--Update Pass Form-->
                <form id="update_password" name="myform" method="post" role="form" action="<?php echo base_url(); ?>login/update_password">
                    <input type="password" class="form-control edu_password_validation" name="password" id="password" placeholder="New Password" minlength="8" style="margin-bottom: 10px;">
                    <span id="password_span" class="errorspan"></span>
                    <span class="text-danger edu_password_validation_msg"></span><br>
                    
                    <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password" minlength="8" style="margin-bottom: 10px;">
                    <span id="cpassword_span" class="errorspan"></span>
                    
                    <input type="hidden" name="secret_key" id="secret_key">
                    <input type="hidden" name="email" id="em"> 
                    <button type="submit" class="button btn btn-primary">Update Password</button>
                </form>
                
                <p class="m-0 text-center pt-3 text-white">For 30-Day Free Trial,  <a href="<?=base_url()?>system_admin/school_registration" target="_blank" class="signup-txt">Signup</a> </p>

                <div class="row dwnld-row pt-4 d-flex justify-content-center"> 
                    <a href="https://play.google.com/store/apps/details?id=com.edutv.wah2ed.indici_edu" target="_blank" rel="noopener noreferrer" class="col-sm-12 col-lg-5  my-1 d-flex">
                        <div class="dwnld-img"> 
                                <img class="dwnld-img" src="https://indiciedu.com.pk/frontend/wp-content/uploads/2021/12/g-1-e1639647696179.png" alt="" data-no-retina="">
                        </div>
                        <div class="btn-infor">
                            <p class="m-0">GET IT ON</p>
                            <p class="m-0 txt-plystore">Google Play Store</p>
                        </div>
                    </a>  
                    <a href="https://apps.apple.com/us/app/indiciedu/id1599039293" target="_blank" rel="noopener noreferrer" class=" col-sm-12 col-lg-5 d-flex my-1">
                        <div class="dwnld-img"> 
                                <img class="dwnld-img" src="https://indiciedu.com.pk/frontend/wp-content/uploads/2021/12/apple.png" alt="" data-no-retina="">
                        </div>
                        <div class="btn-infor">
                            <p class="m-0">Download On The</p>
                            <p class="m-0 txt-plystore">App Store</p>
                        </div>
                    </a>  
                </div>

            </div>
            <div class="col-sm-12 col-md-12 col-lg-8 intro-indiciedu ps-5 py-3  h-100 d-flex flex-column justify-content-center">
                <div class="set-size">
                
                    <h4 class="m-0">ONE STOP SOLUTION FOR ALL YOUR SCHOOL NEEDS!</h4>
                    <p class="m-0 pt-2 pb-3">A cloud-based Online Institute Management System that automates all the processes and provides a smooth virtual learning experience.</p>
                    <video width="100%" controls class="intoro-lgin-vido">
                        <source src="<?php echo base_url(); ?>assets/loginpage/Importance-of-Technology-in-Education.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    
                    <h4 class="m-0 py-3 pt-2">Highlighted Features</h4>
                    <div class="row lgin-fthrs-info">
                        <div class="col text-center">
                            
                            <img class="indici-feature" src="<?php echo base_url(); ?>assets/loginpage/online-campus.png" alt="Online Campus Feature Indici" data-no-retina="">
                            <p class="m-0 ">Online Campus</p>

                        </div>
                        <div class="col text-center">
                            
                            <img class="indici-feature" src="<?php echo base_url(); ?>assets/loginpage/virtual.png" alt="HR Management Feature Indici" data-no-retina="">
                            <p class="m-0 ">HR Management</p>

                        </div>
                        <div class="col text-center">
                            
                            <img class="indici-feature" src="<?php echo base_url(); ?>assets/loginpage/financial.png" alt="Financial Management Feature Indici" data-no-retina="">
                            <p class="m-0">Financial Management</p>

                        </div>
                        <div class="col text-center">
                            
                            <img class="indici-feature" src="<?php echo base_url(); ?>assets/loginpage/assignment.png" alt="Assignments and Assessments Feature Indici" data-no-retina="">
                            <p class="m-0">Assignments and Assessments</p>

                        </div>
                        <div class="col text-center">
                            
                            <img class="indici-feature" src="<?php echo base_url(); ?>assets/loginpage/fee.png" alt="Online Fee Collection Feature Indici" data-no-retina="">
                            <p class="m-0">Online Fee Collection</p>

                        </div>
                            
                    </div>

                </div>
            </div>
            <footer class="footer-main bg-dark"> 
                <div class="footer-top py-3">
                    <div class="container ">
                      <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-5  text-sm-start text-md-center text-lg-end d-none ">
                            
                            <img src="https://indiciedu.com.pk/frontend/wp-content/uploads/2021/01/indici-edu-logo-SVG.svg" class="indici-edu-ftr-logo">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12  text-sm-start  text-md-center text-lg-start  text-white text-center">
                            
                            
                            <p class="m-0 text-center">INDICI-EDU IS A FLAGSHIP PRODUCT OF CAMPUSNETIC SOLUTIONS (AN ICT & EDUTECH STARTUP) </p>
                            <p class="m-0 text-center">WHICH IS SUPPORTED BY F3 GROUP OF TECHNOLOGY COMPANIES. © 2017-<?= date("Y") ?> ALL RIGHTS RESERVED</p>
                        
                        </div>
                     </div>
                    </div>
                </div>
                <div class="container-fluid footer-bottom d-none">
                    <div class="container"> 
                        <div class="row align-items-center">
                            <div class="col-md-12 text-sm-start text-md-center">
                                <ul class="footr-li-list d-md-inline-block d-lg-inline-flex pt-2">
                                  <li><a href="https://indiciedu.com.pk"><i class="fas fa-globe"></i>www.indiciedu.com.pk</a></li>
                                  <li><a href="https://web.whatsapp.com/send?phone=923155172825&text=Hey!!!%20i%20am%20interested%20in%20indici-edu%20services."><i class="fab fa-whatsapp"></i>+92 315 5172825</a></li>
                                  <li><a href="mailto:info@indiciedu.com.pk"><i class="far fa-envelope"></i>info@indiciedu.com.pk</li>
                                  <li><a href="https://www.facebook.com/Indici.edu"><i class="fab fa-facebook-f"></i>www.facebook.com/Indici.edu</a></li>
                                </ul>
                            </div> 
                        </div>
                    </div>
                </div>
            </footer>
        </div>
      </div>
    </div>  
    
    <!--Old-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src='<?php echo base_url();?>assets/login/js/jquery.slideunlock.js'></script>
    <script>

 $('#forgot').click(function(){
    alert("welcome");
    var email   = $.trim($('#email').val());
    var response = grecaptcha.getResponse();
    
    if(response.length == 0 && email == "") {
        $("#error").html("<div class='alert alert-danger'> Email And Captcha Are Required Fields.</div>");
        setTimeout(function(){
            $("#error").text("");
            $("#error").removeClass("alert-danger");
        }, 3000);
    }else{
        $.ajax({
            method: "POST",
            url: "<?php echo base_url();?>login/verify_email/",
            data: { email:email },
            dataType: "JSON",
            success:function(response_ajax) {
                console.log(response_ajax);
                if(response_ajax.check == true){
                    $('#email').attr('disabled', 'disabled');
                    $('#forgot').css('cursor', 'not-allowed');
                    $("#error").html("<div class='alert alert-success'>" + response_ajax.message + "</div>");
                    setTimeout(function(){
                        $("#error").text("");
                        $("#error").removeClass("alert-success");
                    }, 3000);
                    $("#code_section").css('display','block');
                    $("#verify_code").css('display','block');
                    $("#forgot").text("Resend Code (60)");
                    $("#forgot").prop("disabled", true);
                    $("#captcha-div").hide();
                    $("#slider").hide();
                     var i = 59;
                        var interval = setInterval(function () {
                        $("#forgot").text("Resend Code (" + i + ")");
                            i--;
                            if(i == -1)
                            {
                                $("#forgot").text("Resend Code");
                                $("#forgot").prop("disabled", false);
                                clearInterval(interval);
                                $('#forgot').css('cursor', 'pointer');
                            }
                        }, 1000);
                }else{
                    $("#error").html("<div class='alert alert-danger'>" + response_ajax.message + "</div>");
                    setTimeout(function(){
                        $("#error").text("");
                        $("#error").removeClass("alert-danger");
                    }, 3000);
                }
            }
        });
    }
 });

$("#update_password").hide();

// $(function () {
//     var slider = new SliderUnlock("#slider", {}, function () {
//         $("#forgot").prop("disabled", false);
//         $("#captcha-div").show();
//     });
//     slider.init();
// });

$('#image_captcha').on('click', '.captcha-refresh', function(){
    $.get('<?php echo base_url().'login/refresh'; ?>', function(data){
       $('#image_captcha').html(data + "<span class='captcha-refresh' ><img height='20' src='<?php echo base_url().'assets/images/refresh.png'; ?>'/></span>");
    });
});

$("#password").blur(function(){
    var password = $.trim($("#password").val());
    if(password != ''){
        //   var $regexpassword= /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8}$/;
        //   if (password.match($regexpassword)) {
        //       $("#password_span").html('');
        //   } 
        //   else{
            //   $("#password").val(password.slice(0,-1));
            //   $("#password").val("");
            //   $("#password_span").html('Incorrect format');
        //   }       
    }

});
            
$("#cpassword").blur(function(){
    var cpassword = $.trim($("#cpassword").val());
    var password = $.trim($("#password").val());
    if(cpassword != '' && password != ''){
        if( cpassword != password ){
              $("#cpassword").val(cpassword.slice(0,-1));
              $("#cpassword").val("");
              $("#cpassword_span").html('Password & confirm password don\'t match');
        } 
        else
        {
            $("#cpassword_span").html('');  
        }
    }

});
	

 $("#verify_code").click(function(e){

    var code = $("#code").val();
    var email = $("#email").val();
    $.ajax({
        type: "POST",
        async: false,
        data: {
            code:code,
            email:email
        },
        url: "<?php echo base_url();?>login/verify_code/",
        dataType: "json",
        success: function(response) {
            if(response.check == false){
                $('#error').html("");
                $('#error1').html("<span style='color:red;'>"+ response.message +"</span>");
            }else{
                $("#error1").html("<div class='alert alert-success'>Email Verification Confirmed</div>");
                setTimeout(function(){
                    $("#error1").text("");
                    $("#error1").removeClass("alert-success");
                    $("#my_form").hide();
                    $("#update_password").show();
                }, 3000);
                $("#secret_key").val(response.secret_key);
                $("#em").val(email);
            }
        }
    });
    e.preventDefault();
});
$(function() {
    $('#slideshow > div:gt(0)').hide();
    setInterval(function() {
        $('#slideshow > div:first')
        .fadeOut(2000)
        .next()
        .fadeIn(2200)
        .end()
        .appendTo('#slideshow');
    }, 8850);
});
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
  </body>
</html>