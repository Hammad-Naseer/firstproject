<!--jQuery-->
<script src="<?php echo base_url();?>assets/js/jquery-3.5.1.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap"
    rel="stylesheet">
<!--Intro JS Minified Version-->
<link href="<?=base_url()?>assets/intro.js/minified/introjs.min.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url()?>assets/intro.js/minified/intro.min.js"></script>

<!--Popper & Bootstrap JS Minify Version-->
<script type="text/javascript" src="<?=base_url()?>assets/optimization/popper.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/optimization/bootstrap.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">

<!--Entypo CSS-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-icons/entypo/css/entypo.css">

<!--Font Family-->
<link href='https://fonts.googleapis.com/css?family=ABeeZee' rel='stylesheet'>

<!--BS CSS-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/optimization/bootstrap.min.css"> <!-- 4.5 Version -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css"> <!-- 3.0 Version -->

<script src="<?php echo base_url(); ?>assets/js/html5shiv.min.js"></script>
<script src="<?php echo base_url();?>assets/js/respond.min.js"></script>

<!--Custom JS Minify Version -->
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>

<!--NEON CSS Minify Version -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/neon-core.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/neon-theme.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/neon-forms.css"/>

<!--Theme Icons Files -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">

<!--Custom CSS Minified Version (Zeeshan Arain)-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom2.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css"/>

<!--Link / Script For Select2  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<!--themses CSS-->
<?php
        $text_align = '';
        $user_login_id = $_SESSION['user_login_id'];
        $school_id = $_SESSION['school_id'];
        $thm_qur = $this->db->query("select theme from " . get_school_db() . ".user_themes where user_login_id = $user_login_id and school_id = $school_id");
        $theme_query = $thm_qur->row();
        $selected1 ="";
        $selected2 ="";
        $selected3 ="";
        $selected4 = "";
        if($thm_qur->num_rows() > 0){
            $theme_val = $theme_query->theme;
            
            if($theme_val == "1"){
                $selected1 = "checked disabled";
                echo '<link rel="stylesheet" href="'.base_url()."assets/css/themes/theme1.css".'">';
            }else if($theme_val == "2"){
                $selected2 = "checked disabled";
                echo '<link rel="stylesheet" href="'.base_url()."assets/css/themes/theme2.css".'">';
            }else if($theme_val == "3" || $theme_val == "" ){
                $selected3 = "checked disabled";
                echo '<link rel="stylesheet" href="'.base_url()."assets/css/themes/defaulttheme.css".'">';
            }else if($theme_val == "4" ){
                $selected4 = "checked disabled";
                echo '<link rel="stylesheet" href="'.base_url()."assets/css/themes/theme4.css".'">'; 
            }else{
                $selected3 = "checked disabled";
                echo '<link rel="stylesheet" href="'.base_url()."assets/css/themes/defaulttheme.css".'">';
            }
        }else{
            $selected3 = "checked disabled";
            echo '<link rel="stylesheet" href="'.base_url()."assets/css/themes/defaulttheme.css".'">';
        }
?>

<!--Fevicon-->
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.png">

<!--Fontawesome-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-icons/font-awesome/css/font-awesome.css">

<!--Component CSS Minified Version -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vertical-timeline/css/component.css">

<!--Right To Left CSS-->
<?php if ($text_align == 'right-to-left') : ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/neon-rtl.css">
<?php endif;?>

<!--Style CSS Minify Version -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/styles.css"/>

<!--Table Fixer JS-->
<script src="<?php echo base_url(); ?>assets/js/tableHeadFixer.js"></script>

<!--Wizard CSS / JS Minify Version -->
<link rel="stylesheet" href="<?=base_url()?>assets/wizard/wizard.css">
<script src="<?=base_url()?>assets/wizard/wizard.js"></script>

<!-- Bootstrap Progress-Bar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-progressbar/0.9.0/bootstrap-progressbar.min.js" integrity="sha512-gBaNrdEUsGGVww431pfOqMlf+h1PaBMV3/ahRuVdIdeeUPbewu6e3gOoHNK1zv4pFaW5Q534Y8DKLOsDKe39Ug==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-progressbar/0.9.0/bootstrap-progressbar.js" integrity="sha512-7SVW/i3nyhJT8eNqe4YGDX3GOLKtHfcBsYfoSEklk2FsK6PY8KmccreVKqG+XZhYDwxcPoeLjzk18pdvSOZt2A==" crossorigin="anonymous"></script>


<!--Select 2 CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/js/select2/select2-bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/js/select2/select2.css">
<!--Select2 JS-->
<script src="<?php echo base_url();?>assets/js/select2/select2.min.js"></script>

<!--toastr-->
<link type="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!--sweetalert2-->
<!--<script src="sweetalert2.all.min.js"></script>-->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!--Snow Falling CSS-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/snow_falling/let-it-snow.css">


<!-- Select Theme -->
<div id="indiciedu-themes" class="indiciedu-themes">
    <div id="toggle">
      <!--<i class="fas fa-th-large" aria-hidden="true"></i>-->
      <img src="<?=base_url()?>assets/images/color-palette.png" width="35">
    </div>
  <div class="box">
     <div class="custom-control custom-switch switch-green ">
      <input type="checkbox" class="custom-control-input theme" id="customSwitch1" value="1" <?php echo $selected1; ?> >
      <label class="custom-control-label" for="customSwitch1">Green</label>
     </div>
     <div class="custom-control custom-switch switch-orange ">
      <input type="checkbox" class="custom-control-input theme" id="customSwitch2" value="2" <?php echo $selected2; ?> >
      <label class="custom-control-label" for="customSwitch2">Orange</label>
     </div>
     <div class="custom-control custom-switch switch-default ">
      <input type="checkbox" class="custom-control-input theme" id="customSwitch3" value="3" <?php echo $selected3; ?> >
      <label class="custom-control-label" for="customSwitch3">Default</label>
     </div>
     <div class="custom-control custom-switch switch-white ">
      <input type="checkbox" class="custom-control-input theme" id="customSwitch4" value="4" <?php echo $selected4; ?> >
      <label class="custom-control-label" for="customSwitch4" style="color:black !important;">White</label>
     </div>
                                      
  </div>
</div>

<script>
    $('input:checkbox').change(function(){
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to change the theme ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, change it!'
        }).then((result) => {
          if (result.isConfirmed) {
            if($(this).is(':checked')){
                $(this).addClass('selected');
                var theme = $(this).val();
                var url = "<?php echo base_url();?>admin/save_user_theme";
                $.ajax({
                    type: "POST",
                    url: url,
                    data : {theme:theme},
                    success: function(response){
                        // Command: toastr["warning"](response, "Alert")
                        // toastr.options.positionClass = 'toast-bottom-right';
                        // location.reload();
                        Swal.fire(
                          'Theme Changed!',
                          'Your theme has been changed.',
                          'success'
                        )
                        location.reload();
                    }
                });
            }
          } else{
              $(this).removeClass('selected');
          }
        })
    });
    
    // Sweet ALert Display Message
    function sweet_message(title,icon,timer = 1500,position_point = 'top-end')
    {
        Swal.fire({
          position: position_point,
          icon: icon,
          title: title,
          showConfirmButton: false,
          timer: timer
        })
    }
    // Sweet Msg Custom Function
    function sweet_confirmation_alert(title,text,icon,confirm_text,form_id)
    {
        Swal.fire({
          title: title,
          text: text,
          icon: icon,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: confirm_text
        }).then((result) => {
            if (result.isConfirmed) {
                if(form_id != ""){
                    $("#"+form_id).submit();
                }
            }
        });
    }
</script>


