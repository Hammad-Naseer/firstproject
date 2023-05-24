<?php 
    // echo $_SESSION['login_type'];exit;
 ?>
<style>
.bstdown{position:absolute;overflow:hidden;background-color:#000;color:#fff;font-size:15px;padding:10px;cursor:pointer;visibility:hidden;width:57%;left:38px}.bst{cursor:pointer}@media (max-width:768px){.school-logo{display:none}.user-info{margin-top:14px}}.notifications.dropdown .dropdown-menu>li>ul>li .image .img-circle{background-color:#fff;padding:3px}
@media only screen and (max-width:468px){.no-visible-xs{display:none}}@media only screen and (min-width:468px){#show_sidebar_menu{display:none}}
.progress-bar{
    background-color: #0992c9 !important;
}
.select2-drop.select2-display-none.select2-with-searchbox.select2-drop-active
{
    z-index:99999;
    box-shadow: 0px 0px 5px 2px #ccc;
}

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

$res = $this
    ->db
    ->query("select * from " . get_system_db() . ".user_login where  user_login_id=" . $_SESSION['user_login_id'])->result_array();
$device_id = get_user_device_id($_SESSION['login_type'], $_SESSION['user_id']); 

?>


<!doctype html>
<html>
  <head>
    <meta content="text/html; charset=utf-8"http-equiv="Content-Type">
    <title>
      <?php echo get_phrase('Indici-Edu'); ?>
      <?php echo get_phrase(''); ?>
    </title>
    <meta content="IE=edge"http-equiv="X-UA-Compatible">
    <meta content="width=device-width,initial-scale=1"name="viewport">
    <meta content=""name="description">
    <meta content="Creativeitem"name="author">
    <?php include 'top.php'; ?>
  </head>
  <body class="page-body">
    <input id="hide_notification_box"type="hidden"value="<?php echo $hide_notification_box  ?>"> 
    <input id="assigned_access_rights"type="hidden"value="<?php echo count($assigned_access_rights) ?>">
    <header class="topbar_header">
      <div class="container-fluid dashboard">
        <?php  ?>
        <?php  ?>
      </div>
    </header>
    <div class="page-container<?php if($text_align=='right-to-left')echo 'right-sidebar'; ?>">
      <?php if(!$action_blocked){ ?>
      <div class="sidebar-menu desktop-sidebar">
        <?php include $account_type.'/navigation.php'; ?>
      </div>
      <?php } ?>
      <div class="main-content">
        <div class="mb-0 row top_navbar schools-topbr"style="position:relative;top:-3px">
            <div class="clearfix col-md-6 col-sm-6 col-xs-4">
            <ul class="pull-left user-info">
              <li class="profile-info" style="line-height: 5;">
                <div class="no-visible-xs"data-collapse-sidebar="1"data-toggle="chat">
                  <a href="#"class="sidebar-collapse-icon">
                    <!--<i class="entypo-menu side_entypo_menu"></i>-->
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                  </a>
                  &nbsp;&nbsp;&nbsp;
                  <h4 class="text-white school_title"><b><?= $_SESSION['school_name'] ?></b></h4>
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
            <ul class="pull-left user-info pull-none-xsm pull-right-xs">
              
            </ul>
          </div>
            <!--<div class="clearfix col-md-4 col-sm-4 hidden-xs text-center">-->
              
            <!--</div>-->
            <div class="clearfix col-md-6 col-sm-6 col-xs-8">
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
              <?php $num_of_notifications=get_user_notifications($_SESSION['user_id'],get_login_type_name($_SESSION['login_type'])); ?>
              <li class="sep"></li>
              <li class="dropdown notifications">
                <a href="#"class="dropdown-toggle"data-toggle="dropdown"data-close-others="true"data-hover="dropdown">
                  <i class="entypo-bell">
                  </i> 
                  <span class="badge badge-info">
                    <?php echo count($num_of_notifications) ?>
                  </span>
                </a>
                <ul class="dropdown-menu notify_dropdown"style="box-shadow:0 0 11px 1px #012b3c8c;position:relative;left:-80px !important;">
                  <h4 class="notify_heading">Notifications
                  </h4>
                  <?php if(count($num_of_notifications)>0){ ?>
                  <li>
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
                  <?php }else{ ?>
                  <li class="notification-success unread"style="padding:20px;color:#fff">
                    <span class="line">
                      <strong>No Notifications Found
                      </strong>
                    </span>
                  </li>
                  <?php } ?>
                </ul>
              </li>
              <?php if(get_login_type_name($_SESSION['login_type'])=="parent"){ ?>
              <!--<li class="sep"></li>-->
              <li class="dropdown profile-info">
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
                  <?php if(get_login_type_name($_SESSION['login_type'])=="parent"){$query=$this->db->query("SELECT sr.*, c.name as class_name, sp.*,s.*,cs.title as section_name, dep.title as department_name\n                                        \tFROM ".get_school_db().".student_parent sp\n                                        \tINNER JOIN ".get_school_db().".student_relation sr ON sr.s_p_id = sp.s_p_id\n                                        \tINNER JOIN ".get_school_db().".student s ON s.student_id=sr.student_id\n                                        \tINNER JOIN ".get_school_db().".class_section cs ON cs.section_id=s.section_id\n                                        \tINNER JOIN ".get_school_db().".class c ON c.class_id=cs.class_id\n                                        \tINNER JOIN ".get_school_db().".departments dep ON dep.departments_id=c.departments_id \n                                        \tWHERE  \n                                        \ts.student_status IN (".student_query_status().")\n                                        \tand sp.school_id=".$_SESSION['school_id']." \n                                        \tAND sp.user_login_detail_id=".$_SESSION['login_detail_id']);if($query->num_rows()>0){foreach($query->result()as $rows){$_SESSION[$rows->student_id]=$rows->id_no;$studentNICNumber=$rows->id_no;$parent_exist=$this->db->query("select * from ".get_system_db().". user_login \n                                                    where  id_no = '".$studentNICNumber."' ")->result_array();if(count($parent_exist)==0){ ?>
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
              <?php }else{ ?>
              <li class="p-3">
                <p class="text-white">
                  <?php echo $rows->name; ?>
                  <br>
                  <?php echo $rows->department_name; ?>/
                  <?php echo $rows->class_name; ?>/
                  <?php echo $rows->section_name; ?>
                </p>
              </li>
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
          <li class="dropdown profile-info">
                <a href="#"class="dropdown-toggle"data-toggle="dropdown">
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
    <?php include $account_type.'/'.$page_name.'.php'; ?>
    </div>
  </div>
<?php include 'footer.php'; ?>
</body>
<?php include 'includes_bottom.php'; ?>
<?php include 'modal.php'; ?>
<script>function notificationTrigger(i,s){
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
      $(".mystd2").css("visibility","visible"),$(".mystd2").slideToggle("slow")}
                                                                                                                                            ),$(".bstdown").hide(),$(".bst").click(function(){
      $(".bstdown").css("visibility","visible"),$(".bstdown").slideToggle("slow")}
)}
                   )
</script>

</html>
