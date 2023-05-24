<!DOCTYPE html>
<html lang="zxx">
    <?php if($_SESSION['user_login']==1)redirect(base_url().''.get_login_type_controller($_SESSION['login_type']).'/dashboard'); ?>
    <head>
        <title><?php echo get_phrase('indici-edu'); ?></title>
        <meta content="width=device-width,initial-scale=1" name="viewport" />
        <meta charset="UTF-8" />
        <link href="<?php echo base_url(); ?>assets/images/favicon.png" rel="shortcut icon" />
        <meta content="" name="keywords" />
        <script>
            function hideURLbar() {
                window.scrollTo(0, 1);
            }
            setTimeout(function () {
                $(".alert").fadeOut("slow");
            }, 3e3),
                addEventListener(
                    "load",
                    function () {
                        setTimeout(hideURLbar, 0);
                    },
                    !1
                );
        </script>
        <link href="<?=base_url()?>assets/re_login/style.css" rel="stylesheet" media="all" type="text/css" />
    </head>
    <body>
        <section class="w3l-login-6">
            <div class="login-hny">
                <div class="form-content">
                    <div class="form-right">
                        <div class="overlay">
                            <div class="grid-info-form">
                                <h5>Say hello</h5>
                                <h3>ABOUT indici-edu</h3>
                                <p>indici-edu is a cloud based online campus management system, which offers ONLINE SCHOOLING through VIRTUAL CLASS ROOMS with much more options than any other competitive solution</p>
                                <a href="https://indiciedu.com.pk/" class="btn read-more-1">Go to Website</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-left">
                        <div class="middle">
                            <img alt="indiciedu-logo" class="login-logo" src="<?php echo base_url(); ?>assets/re_login/login-logo.png" style="width: 100%;" />
                            <h4 style="text-transform: none !important;">Welcome to indici-edu</h4>
                            <p>Sign In Your Account.</p>
                            <?php if($this->session->flashdata('club_updated')){echo '
                            <div align="center">
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    '.$this->session->flashdata('club_updated').'
                                </div>
                            </div>
                            ';} ?><?php if($_SESSION['success_Parent_account']==1){ ?>
                            <div class="form-login-error" style="display: block;"><p style="color: #fff;">Your Account Successfully Created Please Login.</p></div>
                            <?php }unset($_SESSION['success_Parent_account']);if($_SESSION['error']=='1'){ ?>
                            <div class="alert alert-danger" style="background: #f44336; padding: 10px; color: #fff !important;">
                                <div class="form-login-error" style="display: block;"><h5>Invalid login</h5></div>
                            </div>
                            <?php }unset($_SESSION['error']);if($_SESSION['errors']=='1'){ ?>
                            <div class="form-login-error" style="display: block;"><h5 style="color: red;">Only Student Allowed CNIC login</h5></div>
                            <?php }unset($_SESSION['errors']);if($_SESSION['error_restrict_login']!=''){ ?>
                            <div class="form-login-error" style="display: block;"><h5 style="color: red;">Account Access Blocked</h5></div>
                            <?php }unset($_SESSION['error_restrict_login']); ?>
                        </div>
                        <form action="<?php echo base_url(); ?>login/ajax_login" class="signin-form" method="post">
                            <div class="form-input"><label>Name</label> <input id="email" name="email" placeholder="Email / CNIC" required /></div>
                            <div class="form-input"><label>Password</label> <input id="password" name="password" placeholder="Password" required type="password" /></div>
                            <div class="form-input"><a href="<?php echo base_url(); ?>forgot-password" style="border-bottom: 0; line-height: 60px;">Forgot Password?</a></div>
                            <button class="btn">Login</button>
                        </form>
                        <div class="copy-right text-center">
                            <p>
                                Copyright © indici-edu
                                <?=date("Y")?>. All rights reserved. <strong><a href="#" style="color: #25255e;" target="_blank">indici-edu</a></strong> is powered by
                                <strong><a href="#" style="color: #25255e;" target="_blank">indici-edu</a></strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
<script src="<?php echo base_url(); ?>assets/login/js/jquery.min.js"></script>
<script>
    function putvalue() {
        "admin" == $("#menu1").val()
            ? ($("#email").val("admin@gminns.com"), $("#password").val("gminns"))
            : "principal" == $("#menu1").val()
            ? ($("#email").val("principal@gminns.com"), $("#password").val("gminns"))
            : "teacher" == $("#menu1").val()
            ? ($("#email").val("teacher@gminns.com"), $("#password").val("gminns"))
            : "parent" == $("#menu1").val()
            ? ($("#email").val("parent@gminns.com"), $("#password").val("gminns"))
            : "student" == $("#menu1").val()
            ? ($("#email").val("student@gminns.com"), $("#password").val("gminns"))
            : ($("#email").css("border", "2px solid red"), $("#password").css("border", "2px solid red"), $("#email").val(""), $("#password").val(""));
    }
    $(function () {
        $("#slideshow > div:gt(0)").hide(),
            setInterval(function () {
                $("#slideshow > div:first").fadeOut(2e3).next().fadeIn(2200).end().appendTo("#slideshow");
            }, 8850);
    });
</script>
