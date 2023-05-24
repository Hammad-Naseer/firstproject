<?php session_start(); ?>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="Neon Admin Panel" />
        <meta name="author" content="Laborator.co" />
        <link rel="icon" href="https://demo.neontheme.com/assets/images/favicon.ico" />
        <script type="text/javascript" async="" src="https://ssl.google-analytics.com/ga.js"></script>
        <script type="text/javascript" async="" src="https://www.google-analytics.com/plugins/ua/linkid.js"></script>
        <script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script>
        <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-141030632-1"></script>
        <title><?= $_SESSION['school_name'] ?> | Lockscreen</title>
        <style>
            .file-input-wrapper {
                overflow: hidden;
                position: relative;
                cursor: pointer;
                z-index: 1;
            }
            .file-input-wrapper input[type="file"],
            .file-input-wrapper input[type="file"]:focus,
            .file-input-wrapper input[type="file"]:hover {
                position: absolute;
                top: 0;
                left: 0;
                cursor: pointer;
                opacity: 0;
                filter: alpha(opacity=0);
                z-index: 99;
                outline: 0;
            }
            .file-input-name {
                margin-left: 8px;
            }
            canvas {
                display: none !important;
            }
        </style>
        <link rel="stylesheet" href="https://demo.neontheme.com/assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css" id="style-resource-1" />
        <link rel="stylesheet" href="http://demo.neontheme.com/assets/css/font-icons/entypo/css/entypo.css" id="style-resource-2" />
        <link rel="stylesheet" href="https://dev.indiciedu.com.pk/assets/css/font-icons/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic" id="style-resource-3" />
        <link rel="stylesheet" href="https://demo.neontheme.com/assets/css/bootstrap.css" id="style-resource-4" />
        <link rel="stylesheet" href="https://demo.neontheme.com/assets/css/neon-core.css" id="style-resource-5" />
        <link rel="stylesheet" href="https://demo.neontheme.com/assets/css/neon-theme.css" id="style-resource-6" />
        <link rel="stylesheet" href="https://demo.neontheme.com/assets/css/neon-forms.css" id="style-resource-7" />
        <link rel="stylesheet" href="https://demo.neontheme.com/assets/css/custom.css" id="style-resource-8" />
        <script src="https://demo.neontheme.com/assets/js/jquery-1.11.3.min.js"></script>

    </head>
    <body class="page-body login-page is-lockscreen login-form-fall loaded login-form-fall-init">
        <?php
        //include $account_type.'/navigation.php'; 
        ?>
        <div class="login-container">
            <div class="login-header">
                <div class="login-content">
                    <h2 style="color: white !important;">
                        <b><?= $_SESSION['school_name'] ?></b>
                    </h2>
                    <p class="description">Dear <?= $_SESSION['name'] ?>, enter your password to unlock the screen!</p>
                    <div class="login-progressbar-indicator">
                        <h3>100%</h3>
                        <span>logging in...</span>
                    </div>
                </div>
            </div>
            <div class="login-form">
                <div class="login-content">
                    <form method="post" id="lock_screen_form_submit" novalidate="novalidate">
                        <?php
                            $res = $this->db->query("select profile_pic from " . get_system_db() . ".user_login where  user_login_id=" . $_SESSION['user_login_id'])->result_array();
                        ?>
                        <div class="form-group lockscreen-input">
                            <div class="lockscreen-thumb">
                                <?php if($res[0]['profile_pic']=="") { ?>
            					    <img src="<?php echo get_default_pic()?>" width="140" class="img-circle" /> 
            					<?php } else {?>
                					<img src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1)?>" width="140" class="img-circle" />
            					<?php } ?>
                                <!--<div class="lockscreen-progress-indicator">0%</div>-->
                                <!--<canvas width="143" height="143" style="top: -1.5px; left: -1.5px;"></canvas>-->
                            </div>
                            <div class="lockscreen-details">
                                <h4><?= $_SESSION['name'] ?></h4>
                                <span data-login-text="logging in...">logged off</span>
                                <input type="hidden" name="login_detail_id" value="<?= $_SESSION['login_detail_id'] ?>">
                                <input type="hidden" name="sys_sch_id" value="<?= $_SESSION['school_id'] ?>">
                                <input type="hidden" name="last_url" value="<?= $last_url ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-key"></i></div>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-login">
                                <i class="fa fa-sign-in"></i>
                                Login In
                            </button>
                        </div>
                    </form>
                    <div class="login-bottom-links">
                        <!--<a href="https://demo.neontheme.com/extra/login/" class="link">Sign in using different account <i class="entypo-right-open"></i></a> <br />-->
                        <a href="https://indiciedu.com.pk">Developed By Indici-Edu Team</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://demo.neontheme.com/assets/js/gsap/TweenMax.min.js" id="script-resource-1"></script>
        <script src="https://demo.neontheme.com/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js" id="script-resource-2"></script>
        <script src="https://demo.neontheme.com/assets/js/bootstrap.js" id="script-resource-3"></script>
        <script src="https://demo.neontheme.com/assets/js/joinable.js" id="script-resource-4"></script>
        <script src="https://demo.neontheme.com/assets/js/resizeable.js" id="script-resource-5"></script>
        <script src="https://demo.neontheme.com/assets/js/neon-api.js" id="script-resource-6"></script>
        <script src="https://demo.neontheme.com/assets/js/cookies.min.js" id="script-resource-7"></script>
        <script src="https://demo.neontheme.com/assets/js/jquery.validate.min.js" id="script-resource-8"></script>
        <script src="https://demo.neontheme.com/assets/js/neon-login.js" id="script-resource-9"></script>
        <script src="https://demo.neontheme.com/assets/js/neon-custom.js" id="script-resource-10"></script>
        <script src="https://demo.neontheme.com/assets/js/neon-demo.js" id="script-resource-11"></script>
        <script src="https://demo.neontheme.com/assets/js/neon-skins.js" id="script-resource-12"></script>
        
        <script>
            $('#lock_screen_form_submit').on("submit",function(e){
                e.preventDefault();
                $.ajax({
                    url: '<?php echo base_url() ?>admin/lock_screen_login',
                    method: 'POST',
                    data:new FormData(this),
                    processData:false,
                    contentType: false,  
                    cache: false,  
                    processData:false,
                    success: function(response) {
                        window.location.href = response;
                    }
                });
            });
        </script>
        
    </body>
</html>
