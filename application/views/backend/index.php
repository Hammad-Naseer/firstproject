<style>
        html, body {
          min-height: 100%;
          height: 100%;
          background-image: url(http://theartmad.com/wp-content/uploads/Dark-Grey-Texture-Wallpaper-5.jpg);
          background-size: cover;
          background-position: top center;
          font-family: helvetica neue, helvetica, arial, sans-serif;
          font-weight: 200;
        }
        html.modal-active, body.modal-active {
          overflow: hidden;
        }
        #modal-container {
          position: fixed;
          display: table;
          height: 100%;
          width: 100%;
          top: 0;
          left: 0;
          transform: scale(0);
          z-index: 1;
        }
        #modal-container.one {
          transform: scaleY(0.01) scaleX(0);
          animation: unfoldIn 1s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.one .modal-background .modal {
          transform: scale(0);
          animation: zoomIn 0.5s 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.one.out {
          transform: scale(1);
          animation: unfoldOut 1s 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.one.out .modal-background .modal {
          animation: zoomOut 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.two {
          transform: scale(1);
        }
        #modal-container.two .modal-background {
          background: rgba(0, 0, 0, 0);
          animation: fadeIn 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.two .modal-background .modal {
          opacity: 0;
          animation: scaleUp 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.two + .content {
          animation: scaleBack 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.two.out {
          animation: quickScaleDown 0s 0.5s linear forwards;
        }
        #modal-container.two.out .modal-background {
          animation: fadeOut 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.two.out .modal-background .modal {
          animation: scaleDown 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.two.out + .content {
          animation: scaleForward 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.three {
          z-index: 0;
          transform: scale(1);
        }
        #modal-container.three .modal-background {
          background: rgba(0, 0, 0, 0.6);
        }
        #modal-container.three .modal-background .modal {
          animation: moveUp 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.three + .content {
          z-index: 1;
          animation: slideUpLarge 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.three.out .modal-background .modal {
          animation: moveDown 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.three.out + .content {
          animation: slideDownLarge 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.four {
          z-index: 0;
          transform: scale(1);
        }
        #modal-container.four .modal-background {
          background: rgba(0, 0, 0, 0.7);
        }
        #modal-container.four .modal-background .modal {
          animation: blowUpModal 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.four + .content {
          z-index: 1;
          animation: blowUpContent 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.four.out .modal-background .modal {
          animation: blowUpModalTwo 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.four.out + .content {
          animation: blowUpContentTwo 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.five {
          transform: scale(1);
        }
        #modal-container.five .modal-background {
          background: rgba(0, 0, 0, 0);
          animation: fadeIn 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.five .modal-background .modal {
          transform: translateX(-1500px);
          animation: roadRunnerIn 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.five.out {
          animation: quickScaleDown 0s 0.5s linear forwards;
        }
        #modal-container.five.out .modal-background {
          animation: fadeOut 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.five.out .modal-background .modal {
          animation: roadRunnerOut 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.six {
          transform: scale(1);
          z-index:999;
        }
        #modal-container.six .modal-background {
          background: rgba(0, 0, 0, 0);
          animation: fadeIn 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.six .modal-background .modal {
          background-color: transparent;
          animation: modalFadeIn 0.5s 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.six .modal-background .modal h2, #modal-container.six .modal-background .modal p {
          opacity: 0;
          position: relative;
          animation: modalContentFadeIn 0.5s 1s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.six .modal-background .modal .modal-svg rect {
          animation: sketchIn 0.5s 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.six.out {
          animation: quickScaleDown 0s 0.5s linear forwards;
        }
        #modal-container.six.out .modal-background {
          animation: fadeOut 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.six.out .modal-background .modal {
          animation: modalFadeOut 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.six.out .modal-background .modal h2, #modal-container.six.out .modal-background .modal p {
          animation: modalContentFadeOut 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.six.out .modal-background .modal .modal-svg rect {
          animation: sketchOut 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.seven {
          transform: scale(1);
          z-index:5;
        }
        #modal-container.seven .modal-background {
          background: rgba(0, 0, 0, 0);
          animation: fadeIn 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.seven .modal-background .modal {
          height: 75px;
          width: 75px;
          border-radius: 75px;
          overflow: hidden;
          animation: bondJamesBond 1.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.seven .modal-background .modal h2, #modal-container.seven .modal-background .modal p {
          opacity: 0;
          position: relative;
          animation: modalContentFadeIn 0.5s 1.4s linear forwards;
        }
        #modal-container.seven.out {
          animation: slowFade 0.5s 1.5s linear forwards;
        }
        #modal-container.seven.out .modal-background {
          background-color: rgba(0, 0, 0, 0.7);
          animation: fadeToRed 2s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.seven.out .modal-background .modal {
          border-radius: 3px;
          height: 162px;
          width: 227px;
          animation: killShot 1s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container.seven.out .modal-background .modal h2, #modal-container.seven.out .modal-background .modal p {
          animation: modalContentFadeOut 0.5s 0.5 cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        #modal-container .modal-background {
          display: table-cell;
          background: rgba(0, 0, 0, 0.8) !important;
          text-align: center;
          vertical-align: middle;
        }
        #modal-container .modal-background .modal {
            
          background: white;
          padding: 5px;
          width:95%;
          height:100%;
          display: inline-block;
          border-radius: 3px;
          font-weight: 300;
          position: relative;
        }
        #modal-container .modal-background .modal h2 {
          font-size: 25px;
          line-height: 25px;
          margin-bottom: 15px;
        }
        #modal-container .modal-background .modal p {
          font-size: 18px;
          line-height: 22px;
        }
        #modal-container .modal-background .modal .modal-svg {
          position: absolute;
          top: 0;
          left: 0;
          height: 100%;
          width: 100%;
          border-radius: 3px;
        }
        #modal-container .modal-background .modal .modal-svg rect {
            width:100%;
          stroke: #fff;
          stroke-width: 2px;
          stroke-dasharray: 778;
          stroke-dashoffset: 778;
        }
        
        .content {
          min-height: 100%;
          height: 100%;
          background: white;
          position: relative;
          z-index: 0;
        }
        .content h1 {
          padding: 75px 0 30px 0;
          text-align: center;
          font-size: 30px;
          line-height: 30px;
        }
        .content .buttons {
          max-width: 800px;
          margin: 0 auto;
          padding: 0;
          text-align: center;
        }
        .content .buttons .button {
          display: inline-block;
          text-align: center;
          padding: 10px 15px;
          margin: 10px;
          background: red;
          font-size: 18px;
          background-color: #efefef;
          border-radius: 3px;
          box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
          cursor: pointer;
        }
        .content .buttons .button:hover {
          color: white;
          background: #009bd5;
        }
        
        @keyframes unfoldIn {
          0% {
            transform: scaleY(0.005) scaleX(0);
          }
          50% {
            transform: scaleY(0.005) scaleX(1);
          }
          100% {
            transform: scaleY(1) scaleX(1);
          }
        }
        @keyframes unfoldOut {
          0% {
            transform: scaleY(1) scaleX(1);
          }
          50% {
            transform: scaleY(0.005) scaleX(1);
          }
          100% {
            transform: scaleY(0.005) scaleX(0);
          }
        }
        @keyframes zoomIn {
          0% {
            transform: scale(0);
          }
          100% {
            transform: scale(1);
          }
        }
        @keyframes zoomOut {
          0% {
            transform: scale(1);
          }
          100% {
            transform: scale(0);
          }
        }
        @keyframes fadeIn {
          0% {
            background: rgba(0, 0, 0, 0);
          }
          100% {
            background: rgba(0, 0, 0, 0.7);
          }
        }
        @keyframes fadeOut {
          0% {
            background: rgba(0, 0, 0, 0.7);
          }
          100% {
            background: rgba(0, 0, 0, 0);
          }
        }
        @keyframes scaleUp {
          0% {
            transform: scale(0.8) translateY(1000px);
            opacity: 0;
          }
          100% {
            transform: scale(1) translateY(0px);
            opacity: 1;
          }
        }
        @keyframes scaleDown {
          0% {
            transform: scale(1) translateY(0px);
            opacity: 1;
          }
          100% {
            transform: scale(0.8) translateY(1000px);
            opacity: 0;
          }
        }
        @keyframes scaleBack {
          0% {
            transform: scale(1);
          }
          100% {
            transform: scale(0.85);
          }
        }
        @keyframes scaleForward {
          0% {
            transform: scale(0.85);
          }
          100% {
            transform: scale(1);
          }
        }
        @keyframes quickScaleDown {
          0% {
            transform: scale(1);
          }
          99.9% {
            transform: scale(1);
          }
          100% {
            transform: scale(0);
          }
        }
        @keyframes slideUpLarge {
          0% {
            transform: translateY(0%);
          }
          100% {
            transform: translateY(-100%);
          }
        }
        @keyframes slideDownLarge {
          0% {
            transform: translateY(-100%);
          }
          100% {
            transform: translateY(0%);
          }
        }
        @keyframes moveUp {
          0% {
            transform: translateY(150px);
          }
          100% {
            transform: translateY(0);
          }
        }
        @keyframes moveDown {
          0% {
            transform: translateY(0px);
          }
          100% {
            transform: translateY(150px);
          }
        }
        @keyframes blowUpContent {
          0% {
            transform: scale(1);
            opacity: 1;
          }
          99.9% {
            transform: scale(2);
            opacity: 0;
          }
          100% {
            transform: scale(0);
          }
        }
        @keyframes blowUpContentTwo {
          0% {
            transform: scale(2);
            opacity: 0;
          }
          100% {
            transform: scale(1);
            opacity: 1;
          }
        }
        @keyframes blowUpModal {
          0% {
            transform: scale(0);
          }
          100% {
            transform: scale(1);
          }
        }
        @keyframes blowUpModalTwo {
          0% {
            transform: scale(1);
            opacity: 1;
          }
          100% {
            transform: scale(0);
            opacity: 0;
          }
        }
        @keyframes roadRunnerIn {
          0% {
            transform: translateX(-1500px) skewX(30deg) scaleX(1.3);
          }
          70% {
            transform: translateX(30px) skewX(0deg) scaleX(0.9);
          }
          100% {
            transform: translateX(0px) skewX(0deg) scaleX(1);
          }
        }
        @keyframes roadRunnerOut {
          0% {
            transform: translateX(0px) skewX(0deg) scaleX(1);
          }
          30% {
            transform: translateX(-30px) skewX(-5deg) scaleX(0.9);
          }
          100% {
            transform: translateX(1500px) skewX(30deg) scaleX(1.3);
          }
        }
        @keyframes sketchIn {
          0% {
            stroke-dashoffset: 778;
          }
          100% {
            stroke-dashoffset: 0;
          }
        }
        @keyframes sketchOut {
          0% {
            stroke-dashoffset: 0;
          }
          100% {
            stroke-dashoffset: 778;
          }
        }
        @keyframes modalFadeIn {
          0% {
            background-color: transparent;
          }
          100% {
            background-color: white;
          }
        }
        @keyframes modalFadeOut {
          0% {
            background-color: white;
          }
          100% {
            background-color: transparent;
          }
        }
        @keyframes modalContentFadeIn {
          0% {
            opacity: 0;
            top: -20px;
          }
          100% {
            opacity: 1;
            top: 0;
          }
        }
        @keyframes modalContentFadeOut {
          0% {
            opacity: 1;
            top: 0px;
          }
          100% {
            opacity: 0;
            top: -20px;
          }
        }
        @keyframes bondJamesBond {
          0% {
            transform: translateX(1000px);
          }
          80% {
            transform: translateX(0px);
            border-radius: 75px;
            height: 75px;
            width: 75px;
          }
          90% {
            border-radius: 3px;
            height: 182px;
            width: 247px;
          }
          100% {
            border-radius: 3px;
            height: 162px;
            width: 227px;
          }
        }
        @keyframes killShot {
          0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
          }
          100% {
            transform: translateY(300px) rotate(45deg);
            opacity: 0;
          }
        }
        @keyframes fadeToRed {
          0% {
            background-color: rgba(0, 0, 0, 0.6);
          }
          100% {
            background-color: rgba(255, 0, 0, 0.8);
          }
        }
        @keyframes slowFade {
          0% {
            opacity: 1;
          }
          99.9% {
            opacity: 0;
            transform: scale(1);
          }
          100% {
            transform: scale(0);
          }
        }
    </style>
<style>
    .bstdown{position:absolute;overflow:hidden;background-color:#000;color:#fff;font-size:15px;padding:10px;cursor:pointer;visibility:hidden;width:57%;left:38px}.bst{cursor:pointer}@media (max-width:768px){.school-logo{display:none}.user-info{margin-top:14px}}.notifications.dropdown .dropdown-menu>li>ul>li .image .img-circle{background-color:#fff;padding:3px}
    @media only screen and (max-width:468px){.no-visible-xs{display:none}}@media only screen and (min-width:468px){#show_sidebar_menu{display:none}}
    .progress-bar{
        background-color: #0992c9 !important;
    }
    .select2-drop.select2-display-none.select2-with-searchbox.select2-drop-active{z-index:99999;box-shadow:0 0 5px 2px #ccc}
    .search_bar{background:0 0!important;color:#fff!important;border-top:none!important;border-left:none!important;border-right:none!important;border-bottom:1px solid #fff!important;caret-color:#fff;transition:all .58s ease-in-out;overflow:hidden}
    .search_bar::-webkit-input-placeholder{color:#fff!important;font-size:16px}.search_bar~.focus-border:after,
    .search_bar~.focus-border:before{content:"";position:absolute;top:0;right:0;width:0;height:2px;background-color:#39f;transition:.2s;transition-delay:.2s}
    .search_bar~.focus-border:after{top:auto;bottom:0;right:auto;left:0;transition-delay:.6s}.search_bar~.focus-border i:after,
    .search_bar~.focus-border i:before{content:"";position:absolute;top:0;left:0;width:2px;height:0;background-color:#39f;transition:.2s}
    .search_bar~.focus-border i:after{left:auto;right:0;top:auto;bottom:0;transition-delay:.4s}.search_bar:focus~.focus-border:after,
    .search_bar:focus~.focus-border:before{width:100%;transition:.2s;transition-delay:.6s}.search_bar:focus~.focus-border:after{transition-delay:.2s}
    .search_bar:focus~.focus-border i:after,.search_bar:focus~.focus-border i:before{height:100%;transition:.2s}.search_bar:focus~.focus-border i:after{transition-delay:.4s}
</style>

<?php 
    $_SESSION['school_name'] = "";
    $qurr = $this->db->query("select * from " . get_school_db() . ".school where  school_id=" . $_SESSION['school_id'])->result_array();
    $_SESSION['school_name'] = $qurr[0]['name'];
    $_SESSION['school_logo'] = $qurr[0]['logo'];
    $_SESSION['folder_name'] = $qurr[0]['folder_name'];
    $system_name = $_SESSION['school_name'];
    $system_title = $_SESSION['school_name'];
    $account_type = get_login_type_folder($_SESSION['login_type']);
    $this->load->helper('exams_helper');
    $school_package_subscription = get_school_package_subscription_details();
    $today_date = date('Y-m-d');
    $action_blocked = false;
    $login_message = false;
    $subscription_message = false;
    $login_blocked_message = false;
    
   
    if ($school_package_subscription != null)
    {
        if ($school_package_subscription->is_valid == 1)
        {
            if ($school_package_subscription->is_trial == 0)
            {
                $action_blocked = true;
                $login_message = true;
            }
            else
            {
                $school_package_subscription_end_date = date('Y-m-d ', strtotime($school_package_subscription->end_date));
                if ($school_package_subscription_end_date > $today_date)
                {
                    $action_blocked = false;
                }
                else
                {
                    $action_blocked = true;
                    $subscription_message = true;
                }
            }
        }
        else
        {
            $action_blocked = true;
            $login_blocked_message = true;
        }
        $hide_notification_box = 0;
        if ($action_blocked)
        {
            $hide_notification_box = 1;
        }
    }
    if ($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 4)
    {
        if ($_SESSION['user_id'] == 0 && $_SESSION['login_type'] == 1)
        {
            $_SESSION['user_id'] = $_SESSION['login_detail_id'];
        }
        $assigned_access_rights = explode(",", get_user_assigned_rights($_SESSION['login_type']));
    }
    else if ($_SESSION['login_type'] == 3)
    {
        $assigned_access_rights = explode(",", get_user_assigned_rights(2));
    }
    else if ($_SESSION['login_type'] == 6)
    {
      $assigned_access_rights = explode(",",get_user_assigned_rights(3));
    }
    
    $res = $this->db->query("select * from " . get_system_db() . ".user_login where  user_login_id=" . $_SESSION['user_login_id'])->result_array();
    $device_id = get_user_device_id($_SESSION['login_type'], $_SESSION['user_id']); 

?>
<!doctype html>
<html>
    <head>
        <meta content="text/html; charset=utf-8"http-equiv="Content-Type">
        <title>
            <?php echo get_phrase('Indici-Edu'); ?>
        </title>
        <meta content="IE=edge"http-equiv="X-UA-Compatible">
        <meta content="width=device-width,initial-scale=1"name="viewport">
        <meta content=""name="description">
        <meta content="Creativeitem"name="author">
        <?php include 'top.php'; ?>
        
    </head>
    <body class="page-body">
        <!--for ajax loader-->
        <style>
            body{background:#ececec}.lds-dual-ring.hidden{display:none}.lds-dual-ring{display:inline-block;width:80px;height:80px}.lds-dual-ring:after{content:" ";display:block;width:64px;height:64px;margin:25% auto;border-radius:50%;border:6px solid #fff;border-color:#fff transparent #fff transparent;animation:lds-dual-ring 1.2s linear infinite}@keyframes lds-dual-ring{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
            .overlay{position:fixed;top:0;left:0;width:100%;height:100vh;background:rgba(0,0,0,.8);z-index:999;opacity:1;transition:all .5s}
        </style>
        <div id="loader" class="lds-dual-ring hidden overlay"></div>
        <!--for ajax loader-->
    
        <input id="hide_notification_box"type="hidden"value="<?php echo $hide_notification_box  ?>"> 
        <input id="assigned_access_rights"type="hidden"value="<?php echo count($assigned_access_rights) ?>">
        <header class="topbar_header">
          <div class="container-fluid dashboard">
          </div>
        </header>
        <div class="page-container<?php if($text_align=='right-to-left')echo 'right-sidebar'; ?>">
          <?php if(!$action_blocked){ ?>
          <div class="sidebar-menu desktop-sidebar">
            <?php include $account_type.'/navigation.php'; ?>
          </div>
          <?php } ?>
            <div class="main-content">
                <div class="mb-0 row top_navbar schools-topbr mainStats" style="position:relative;top:-3px">
                    <div class="clearfix col-md-4 col-sm-4 col-xs-2">
                        <ul class="pull-left user-info">
                          <li class="profile-info" style="line-height: 5;">
                            <div class="no-visible-xs"data-collapse-sidebar="1" data-toggle="chat">
                              <a href="#"class="sidebar-collapse-icon">
                                <!--<i class="entypo-menu side_entypo_menu"></i>-->
                                <span class="bar-icon">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                              </a>
                              &nbsp;&nbsp;&nbsp;
                              <h4 class="school_title"><?= $_SESSION['school_name'] ?></h4>
                            </div>
                            <div data-sidebar="0"id="show_sidebar_menu">
                              <div class="with-animation">
                                <i class="entypo-menu side_entypo_menu">
                                </i>
                              </div>
                            </div>

                            
                            <script>$("#show_sidebar_menu").on("click",function(){
                                "0"==$(this).attr("data-sidebar")?($(".page-body .page-container .sidebar-menu #main-menu").css("display","block"),$(".sidebar-menu-inner").css("display","block"),$(this).attr("data-sidebar","1")):($(".page-body .page-container .sidebar-menu #main-menu").css("display","none"),$(".sidebar-menu-inner").css("display","none"),$(this).attr("data-sidebar","0"))}
                                                              )</script>
                          </li>
                        </ul>
                        <ul class="pull-left user-info pull-none-xsm pull-right-xs"></ul>
                    </div>
                    <!--<div class="clearfix col-md-4 col-sm-4 hidden-xs text-center">-->
                    <!--</div>-->
                    <div class="clearfix col-md-8 col-sm-8 col-xs-10">
                        <ul class="list-inline pull-right">
                          <?php if(isset($_SESSION['multiple_accounts'])&&$_SESSION['multiple_accounts']==1||get_login_type_name($_SESSION['login_type'])=="parent"){ ?>
                          <!--<li class="sep"></li>-->
                          <!--<li>-->
                          <!--  <a href="<?php echo base_url(); ?>switch_user/account_list">-->
                          <!--    <i class="entypo-chat">-->
                          <!--    </i> Switch Account-->
                          <!--  </a>-->
                          <!--</li>-->
                          <?php } ?>
                          <?php 
                            $num_of_notifications=get_user_notifications($_SESSION['user_id'],get_login_type_name($_SESSION['login_type'])); 
                            $all_notifications = get_user_all_notifications($_SESSION['user_id'],get_login_type_name($_SESSION['login_type'])); 
                          ?>
                          
                          <?php if($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 3){ ?>
                          <?php if($hide_notification_box == 0){ ?>
                          <li class="notifications">
                            <div class="col-md-12">
                                <input type="text" class="form-control search_bar" onkeyup="search_student(this);" placeholder="Search Student Here ... ">
                                <span class="focus-border">
                                	<i></i>
                                </span>
                            </div>
                            <ul class="search_dropdown search_student_data"style="box-shadow: 0 0 11px 1px #012b3c8c;position: absolute;left: 47% !important;">
                                
                            </ul>
                          </li>
                          <?php } ?>
                          <?php } ?>
                          <li class="dropdown notifications">
                              <style>
                                 
                              </style>
                            <a href="#"class="dropdown-toggle"data-toggle="dropdown"data-close-others="true"data-hover="dropdown">
                              <i class="entypo-bell">
                              </i> 
                              <span class="badge badge-info">
                                <?php echo count($num_of_notifications) ?>
                              </span>
                            </a>
                            <ul class="dropdown-menu notify_dropdown "style="box-shadow:0 0 11px 1px #012b3c8c;position:relative;left:-80px !important;">
                                <h4 class="notify_heading">Notifications</h4>
                                <ul class="nav nav-tabs" style="margin-bottom:8px;padding:0px 0px 6px 0px;">
                                    <li class="active all_unread_btn" data-type="unread"><span class="li_read_unread_a active_li_read_unread_a li_unread">Unread</span></li>
                                    <li class="all_unread_btn" data-type="all"><span class="li_read_unread_a li_all">All</span></li>
                                </ul>
                                <?php if(count($all_notifications)>0){ ?>
                                <li id="unread_notifications_tab" class="tab-pane fade in active">
                                    <ul class="dropdown-menu-list scroller">
                                  <?php foreach($num_of_notifications as $notification_row){ ?>
                                  <li class="notification-success unread">
                                    <a onclick='notificationTrigger("<?php echo $notification_row['id'] ?>","<?php echo $notification_row['url'] ?>")'>
                                      <span class="pull-left image">
                                        <img alt=""class="img-circle"src="https://img.icons8.com/nolan/344/google-alerts.png"width="44"> 
                                      </span>
                                      <span class="line">
                                        <strong>
                                          <?php echo $notification_row['text'] ?>
                                        </strong>
                                      </span>
                                    </a>
                                  </li>
                                  <?php } ?>
                                </ul>
                                </li>
                                <li id="all_notifications_tab" class="tab-pane fade in">
                                    <ul class="dropdown-menu-list scroller">
                                      <?php foreach($all_notifications as $notification_row){ ?>
                                      <li class="notification-success unread">
                                        <a onclick='notificationTrigger("<?php echo $notification_row['id'] ?>","<?php echo $notification_row['url'] ?>")'>
                                          <span class="pull-left image">
                                            <img alt=""class="img-circle"src="https://img.icons8.com/nolan/344/google-alerts.png"width="44"> 
                                          </span>
                                          <span class="line">
                                            <strong>
                                              <?php echo $notification_row['text'] ?>
                                            </strong>
                                          </span>
                                        </a>
                                      </li>
                                      <?php } ?>
                                    </ul>
                                </li>
                                <?php }else{ ?>
                                <li class="notification-success unread"style="padding:20px;color:#fff">
                                    <span class="line">
                                        <strong>No Notifications Found</strong>
                                    </span>
                                </li>
                                <?php } ?>
                            </ul>
                            <script>
                                $("#all_notifications_tab").css('display','none');
                                
                                $(".all_unread_btn").on("click",function(e){
                                    e.stopPropagation();
                                    var type = $(this).attr("data-type");
                                    if(type == "all")
                                    {
                                        $("#unread_notifications_tab").css('display','none');
                                        $("#all_notifications_tab").css('display','block');
                                        $(this).addClass('active');;
                                        $(".li_unread").removeClass('active_li_read_unread_a');
                                        $(".li_all").addClass('active_li_read_unread_a');
                                        
                                    }
                                    if(type == "unread")
                                    {
                                        $("#unread_notifications_tab").css('display','block');
                                        $("#all_notifications_tab").css('display','none');
                                        $(".li_all").removeClass('active_li_read_unread_a');
                                        $(".li_unread").addClass('active_li_read_unread_a');
                                    }
                                });
                            </script>
                          </li>
                          <?php if(get_login_type_name($_SESSION['login_type']) == "parent"){ ?>
                          <!--<li class="sep"></li>-->
                          <li class="dropdown profile-info child-desktop">
                            <style>.show{
                              display:unset!important}
                            </style>
                            <a href="#"class="dropdown-toggle"data-toggle="dropdown">
                              <i class="fa fa-street-view"aria-hidden="true">
                              </i> Children List
                            </a>
                            <ul class="dropdown-menu">
                              <li class="caret">
                              </li>
                              <?php 
                              if(get_login_type_name($_SESSION['login_type'])=="parent"){
                              $query=$this->db->query("SELECT sr.*, c.name as class_name, sp.*,s.*,sp.user_login_detail_id as uld_id,cs.title as section_name, dep.title as department_name\n
                              \tFROM ".get_school_db().".student_parent sp\n
                              \tINNER JOIN ".get_school_db().".student_relation sr ON sr.s_p_id = sp.s_p_id\n
                              \tINNER JOIN ".get_school_db().".student s ON s.student_id=sr.student_id\n
                              \tINNER JOIN ".get_school_db().".class_section cs ON cs.section_id=s.section_id\n
                              \tINNER JOIN ".get_school_db().".class c ON c.class_id=cs.class_id\n
                              \tINNER JOIN ".get_school_db().".departments dep ON dep.departments_id=c.departments_id \n
                              \tWHERE  \n                                        \ts.student_status IN (".student_query_status().")\n 
                              \tand sp.school_id=".$_SESSION['school_id']." \n
                              \tAND sp.user_login_detail_id=".$_SESSION['login_detail_id']);
                            //   echo $this->db->last_query();
                              if($query->num_rows()>0){
                              foreach($query->result() as $rows){
                              $_SESSION[$rows->student_id]=$rows->id_no;
                              $studentNICNumber=$rows->id_no;
                              
                              $parent_exist=$this->db->query("select * from ".get_system_db().". user_login \n
                              where  id_no = '".$studentNICNumber."' ")->result_array();
                              
                              if(count($parent_exist)==0){ 
                              ?>
                            <a href="<?php echo base_url();?>switch_user/switch_account/<?php echo $rows->uld_id; ?>/<?php echo $rows->student_id; ?>">  
                                <li class="p-3">
                                    <p class="text-white">
                                      <?php echo $rows->name; ?>
                                      <br>
                                      <?php echo $rows->department_name; ?>/
                                      <?php echo $rows->class_name; ?>/
                                      <?php echo $rows->section_name; ?>
                                    </p>
                                  </p>
                                </li>
                            </a>    
                          <?php }else{ ?>
                          <a href="<?php echo base_url();?>switch_user/switch_account/<?php echo $rows->uld_id; ?>/<?php echo $rows->student_id; ?>">  
                          <li class="p-3">
                            <p class="text-white">
                              <?php echo $rows->name; ?>
                              <br>
                              <?php echo $rows->department_name; ?>/
                              <?php echo $rows->class_name; ?>/
                              <?php echo $rows->section_name; ?>
                            </p>
                          </li>
                          </a>
                          <?php }}}} ?>
                        </ul>
                        </li>
                          <?php } ?>
                          
                      <?php if(isset($_SESSION['student_name'])){ ?>
                      <li>
                        <span class="text-white">
                          <?php echo $_SESSION['student_name']; ?>
                        </span>
                      </li>
                      <?php } ?>
                      <!--<li class="sep"></li>-->
                       <!--Translator-->
                        <li class="dropdown language-selector text-white">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true"><i class="fas fa-language" style="font-size: 28px;color: #f5f2f2;"></i> </a>
                            <ul class="dropdown-menu pull-right">
                                <li class="active">
                                    <a href="#" class="lang_style" onclick="doGTranslate('en|en');" data-lang="en|en"> <img src="https://demo.neontheme.com/assets/images/flags/flag-uk.png" width="16" height="16" /> <span>English</span> </a>
                                </li>
                                <li>
                                    <a href="#" class="lang_style" onclick="doGTranslate('en|ur');" data-lang="en|ur"> <img src="<?=base_url()?>uploads/Pakistan-icon.png" width="16" height="16" /> <span>Urdu</span> </a>
                                </li>
                                <li>
                                    <a href="#" class="lang_style" onclick="doGTranslate('en|ar');" data-lang="en|ar"> <img src="<?=base_url()?>uploads/arabic_flag.png" width="16" height="16" /> <span>Arabic</span> </a>
                                </li>
                            </ul>
                            <div id="google_translate_element2"></div>
                        </li>
                        <li class="dropdown profile-info">
                            <a href="#"class="dropdown-toggle" data-toggle="dropdown" <?php if(@$theme_val == "4"): echo "style='color:#303641 !important;'"; endif;?>>
                              <?php if($res[0]['profile_pic']==""){ ?>
                              <img alt=""alt=""class="img-circle"src="<?php echo get_default_pic() ?>"width="44">
                              <?php }else{ ?>
                              <img alt=""alt=""alt=""class="img-circle"src="<?php echo display_link($res[0]['profile_pic'],'profile_pic',1) ?>"width="44">
                              <?php } ?>
                              <?php echo $res[0]['name']; ?>
                            </a>
                            <ul class="dropdown-menu profile_dropdown" style="left:-100px !important;">
                              <li class="caret">
                              </li>
                              <li>
                                <a href="<?=base_url()?>profile/manage_profile">
                                  <i class="entypo-user">
                                  </i> My Profile
                                </a>
                              </li>
                              <li>
                                <a href="<?php echo base_url(); ?>switch_user/account_list">
                                  <i class="entypo-chat">
                                  </i> Switch Account
                                </a>
                              </li>
                              <li>
                                <a href="<?php echo base_url(); ?>login/logout">
                                  <i class="entypo-lock">
                                  </i> Logout
                                </a>
                              </li>
                            </ul>
                        </li>
                      </ul>
                    </div>
                </div>
                <?php if(!$action_blocked){ ?>
                    <div class="sidebar-menu mobile-sidebar">
                      <?php include $account_type.'/navigation.php'; ?>
                    </div>
                <?php } ?>
                <hr style="margin-top:-5px;margin-bottom:0;box-shadow:0 0 2px 1px #cccccc6b">
                <?php if(!$action_blocked){ ?>
                <?php include $account_type.'/'.$page_name.'.php'; ?>
                <?php }else{ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center"style="padding:20px;font-size:250px;color:#b7004c;text-shadow:2px 11px 8px #ccc">
                          <i class="fas fa-ban"></i>
                        </div>
                        <?php if($login_message){ ?>
                          <h2 class="text-center"style="color:red!important">
                            <b>We hope you had a great time using indici-edu, Unfortunately Your Trail is over.
                              <br>In order to continue with Indici Edu , please contact system Support!
                            </b>
                          </h2>
                        <?php }else if($subscription_message){ ?>
                          <h2 class="text-center"style="color:red!important">
                            <b>We hope you had a great time using indici-edu, Unfortunately Your Subscription has expired.
                              <br>In order to Subscribe again , please contact system Support!
                            </b>
                          </h2>
                        <?php }else{ ?>
                          <h2 class="text-center"style="color:red!important">
                            <b>We hope you had a great time using indici-edu, Unfortunately Your Subscription has expired.
                              <br>All the logins are blocked. In order to Subscribe again , please contact system Support!
                            </b>
                          </h2>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php include 'footer.php'; ?>
        <div class="sticky-button-bar">
          <div class="utility-circle-wrapper">
            <div class="utility utility-circle"></div>
                <div class="utility-icon">
                    <button id="utility_bar">
                        <img src="<?=base_url()?>assets/images/widget.png" width="30">
                        <b>Utilities</b>
                    </button>
                </div>
            </div>
        </div>
        
     </body>
    
<?php include 'includes_bottom.php'; ?>
    
    <!--Utlities Sidebar-->
    <div id="chat" class="fixed" data-current-user="Zeeshan Arain" data-order-by-status="1" data-max-chat-history="25">
            <div class="chat-inner" tabindex="5000" style="overflow: hidden; outline: none;">
                <h2 class="chat-header">
                    <a href="#" class="chat-close"><i class="entypo-cancel"></i></a> <i class="entypo-users"></i>
                    Utilities
                    <!--<span class="badge badge-success">3</span>-->
                </h2>
                <div class="chat-group" id="group-1">
                    <strong>Widget</strong> 
                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_utility_calculator/0/0/0/1');"><span class="user-status is-online"></span> <em>Calculator</em></a>
                    <a href="#" id="six" class="button"><span class="user-status is-online"></span> <em>Periodic Table</em></a>
                </div>
                <div class="chat-group" id="group-2">
                    <strong>Question Bot</strong> 
                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/faq_modal/0/0/0/1');"><span class="user-status is-offline"></span> <em>Ask Question</em></a>
                    <!--<a href="#" data-conversation-history="#sample_history_2" id="ui-id-6"><span class="user-status is-offline"></span> <em>Daniel A. Pena</em><span class="badge badge-info">2</span></a>-->
                    <!--<a href="#" id="ui-id-7"><span class="user-status is-busy"></span> <em>Rodrigo E. Lozano</em><span class="badge badge-info is-hidden">0</span></a>-->
                </div>
                <div class="chat-group" id="group-3">
                    <strong>Google Search</strong> 
                    <a href="#"><span class="user-status is-busy"></span> <em>Open Search Engine</em></a>
                    <!--<a href="#" id="ui-id-9"><span class="user-status is-offline"></span> <em>Margaret R. Dedmon</em><span class="badge badge-info is-hidden">0</span></a>-->
                    <!--<a href="#" id="ui-id-10"><span class="user-status is-online"></span> <em>Kathleen M. Canales</em><span class="badge badge-info is-hidden">0</span></a>-->
                    <!--<a href="#" id="ui-id-11"><span class="user-status is-offline"></span> <em>Tracy J. Rodriguez</em><span class="badge badge-info is-hidden">0</span></a>-->
                </div>
            </div>
            <div class="chat-conversation">
                <div class="conversation-header">
                    <a href="#" class="conversation-close"><i class="entypo-cancel"></i></a> <span class="user-status"></span> <span class="display-name"></span> <small></small>
                </div>
                <ul class="conversation-body"></ul>
                <div class="chat-textarea"><textarea class="form-control autogrow" placeholder="Type your message" style="overflow: hidden; overflow-wrap: break-word; resize: horizontal; height: 32px;"></textarea></div>
            </div>
        </div>
    <div id="modal-container">
      <div class="modal-background">
        <div class="modal">
          <iframe src="<?=base_url()?>assets/widget/periodic-table" width="100%" height="100%"></iframe>
            <svg class="modal-svg" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" preserveAspectRatio="none">
    			<rect x="0" y="0" fill="none" width="226" height="162" rx="3" ry="3"></rect>
    	    </svg>
        </div>
      </div>
    </div>
    <!--<div class="content">-->
    <!--  <h1>Modal Animations</h1>-->
    <!--  <div class="buttons">-->
    <!--    <div id="one" class="button">Unfolding</div>-->
    <!--    <div id="two" class="button">Revealing</div>-->
    <!--    <div id="three" class="button">Uncovering</div>-->
    <!--    <div id="four" class="button">Blow Up</div><br>-->
    <!--    <div id="five" class="button">Meep Meep</div>-->
    <!--    <div id="six" class="button">Sketch</div>-->
    <!--    <div id="seven" class="button">Bond</div>-->
    <!--  </div>-->
    <!--</div>-->
<?php include 'modal.php'; ?>

<script>
    $('.button').click(function(){
  var buttonId = $(this).attr('id');
  $('#modal-container').removeAttr('class').addClass(buttonId);
  $('body').addClass('modal-active');
})

$('#modal-container').click(function(){
  $(this).addClass('out');
  $('body').removeClass('modal-active');
});
    $("#utility_bar").on('click',function(){
        $("#chat").css({
            'display':'block',
            'position':'absolute'
        });
    });
    $(".chat-close").on('click',function(){
        $("#chat").css({
            'display':'none',
            'position':'relative'
        });
    });
    function notificationTrigger(i,s){
        $.ajax({
          url:"<?php echo base_url(); ?>notifications/update_is_viewed",type:"post",data:{
            not_id:i}
          ,success:function(i){
            location.replace(s)}
        }
    )}
    $(document).ready(function(){
        $(".mystd2").hide();
        var i=$("#hide_notification_box").val(),s=$("#assigned_access_rights").val();
        1!=parseInt(s)&&1!=parseInt(i)||($(".notify_icon").css("display","none"),$(".notify_counter").css("display","none")),$(".stdlist").click(function(){
        $(".mystd2").css("visibility","visible"),$(".mystd2").slideToggle("slow")}),
        $(".bstdown").hide(),$(".bst").click(function(){
            $(".bstdown").css("visibility","visible"),$(".bstdown").slideToggle("slow");
        })
    })
    
    function  search_student(stud){
		var phrase = stud.value;

		if(phrase !== "" && typeof(phrase) !== "undefined"){
			$.ajax({
				type: "POST",
				data:{phrase:phrase},
				url: '<?php echo base_url()."dashboard/search_student";?>',
				dataType:"html",
				success: function(response){
					if(response != ""){
					    $(".search_dropdown").css("display","block");
						$('.search_student_data').html(response);
					}else{
					    $(".search_dropdown").css("display","none");
					}
				}
			});
		}else{
		    $(".search_dropdown").css("display","none");
			$('.search_student_data').html("");
		}
	}
</script>

</html>
