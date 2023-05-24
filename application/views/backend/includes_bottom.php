
<!--Select 2 CSS -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>assets/js/select2/select2-bootstrap.css">-->
<!--<link rel="stylesheet" href="<?php echo base_url();?>assets/js/select2/select2.css">-->
<!--<script src="<?=base_url()?>assets/js/selectboxit/jquery.selectBoxIt.css"></script>-->

<!--Gsap & Resizeable JS-->
<script src="<?php echo base_url();?>assets/js/gsap/main-gsap.js"></script>
<script src="<?php echo base_url();?>assets/js/resizeable.js"></script>

<!--Custom JS-->
<script src="<?php echo base_url();?>assets/js/neon-custom.js"></script>

<!--SParkline JS-->
<script src="<?php echo base_url();?>assets/js/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>	

<!--jQuery Validation-->
<script src="<?php echo base_url();?>assets/js/jquery.validate.min.js"></script>

<!--Moment & Joinable JS-->
<script src="<?php echo base_url();?>assets/js/moment.js"></script>
<script src="<?php echo base_url();?>assets/js/joinable.js"></script>

<!--Calendar JS-->
<script src="<?php echo base_url();?>assets/js/fullcalendar/fullcalendar.min.js"></script>

<!--Datepicker JS-->
<script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>

<!-- FileInput JS-->
<script src="<?php echo base_url();?>assets/js/fileinput.js"></script>


<!--Neon JS-->
<script src="<?php echo base_url();?>assets/js/neon-calendar.js"></script>
<script src="<?php echo base_url();?>assets/js/neon-chat.js"></script>
<script src="<?php echo base_url();?>assets/js/neon-demo.js"></script>
<script src="<?php echo base_url();?>assets/js/neon-api.js"></script>
<script src="<?php echo base_url();?>assets/js/neon-notes.js"></script>

<!--Select2 JS-->
<!--<script src="<?php echo base_url();?>assets/js/select2/select2.min.js"></script>-->

<!--Tooltip JS-->
<script src="<?php echo base_url();?>assets/js/tooltip.js"></script>

<script src="<?php echo base_url();?>assets/optimization/bottom/nprogress.js"></script>
<link   rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/nprogress.css"> <!-- No-Error -->

<!-- datevalidation -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/js/fullcalendar/fullcalendar.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/tooltip.css">


<!--Datatables CSS-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/datatables.min.css">

<!--Datatables JS-->

<!--<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>-->
	
<script src="<?php echo base_url();?>assets/optimization/bottom/jquery.dataTables.min.js"></script>

<!--Datatable Buttons Comment By Zeeshan Arain-->
<!--<script src="<?php echo base_url();?>assets/optimization/bottom/dataTables.buttons.min.js"></script>-->
<!--<script src="<?php echo base_url();?>assets/optimization/bottom/buttons.html5.min.js"></script>-->
<!--<script src="<?php echo base_url();?>assets/optimization/bottom/pdfmake.min.js"></script>-->
<!--<script src="<?php echo base_url();?>assets/optimization/bottom/vfs_fonts.js"></script>-->
<!--<script src="<?php echo base_url();?>assets/optimization/bottom/jszip.min.js"></script>-->

<!--Articulate JS-->
<script src="<?php echo base_url();?>assets/audio/articulate.js"></script>
<script src="<?php echo base_url();?>assets/audio/articulate.min.js"></script>

<!--Snow Falling JS-->
<script src="<?php echo base_url();?>assets/snow_falling/let-it-snow.min.js"></script>
<script>
    // $.letItSnow('.sidebar-menu', {
    //     stickyFlakes: 'lis-flake--js',
    //     makeFlakes: true,
    //     sticky: true
    // });
 
</script>

<script type="text/javascript">
var responsiveHelper;
var breakpointDefinition = {
    tablet: 1024,
    phone : 480
};
var tableContainer;
	jQuery(document).ready(function($)
	{
    	$("#table_export").DataTable( {
            dom: 'Bfrtip',
            "order": [[ 0, "asc" ]],
        });
        
        $(".table_export").DataTable( {
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            "paging": true,
            "order": [[ 0, "asc" ]],
            // "dom": '<"toolbar">frtip',
            buttons: [

            ]
        });
        if (window.datatable_btn !== undefined) {
            datatables_add_btn_with_search_bar(datatable_btn);    
        }
        
        function datatables_add_btn_with_search_bar(param)
        {
            $(".dataTables_filter label").after(param);
        }
        if (window.datatable_btn_2 !== undefined) {
            $("#remarks .dataTables_filter a").remove(); 
            $("#remarks .dataTables_filter label").after(datatable_btn_2);    
        }
        
		window.onbeforeunload = function()
		{
		    //-------added by hammad ali start -----------
            // $.LoadingOverlaySetup({
            //     debugger;
            //     background      : "rgba(0, 0, 0, 0.5)",
            //     image           : "<?php echo base_url();?>assets/images/loader.gif",
            //     imageAnimation  : "1.50s fadein",
            //     imageColor      : "#ffcc00",
            //     imageAutoResize : false,
            //     imageResizeFactor: 300,
            //     imageClass      : "my_custom_loader" 
            // });
            
            // $(".main-content").LoadingOverlay("show");
            //-------added by hammad ali End------------
          
			NProgress.configure({
			  showSpinner: false
			});
			NProgress.start();
		    // Increase randomly
		    var interval = setInterval(function() { NProgress.inc(); }, 1000); 
		    // Trigger finish when page fully loaded
		    //$(window).load(function () {
		    $(window).on("load",function () {
		        clearInterval(interval);
		        NProgress.done();
		    });
		    
            // 		    $(".aa").LoadingOverlay("hide", true);
            // 			$('#preloader').fadeOut(4000,function(){
            // 				$(this).remove();
            // 			});
		};

		//____________ajax calls loader____________
		$(document).ajaxStart(function(){
			NProgress.start();
			NProgress.done();
		});
		
		//____________submit btn loader____________
		$(document).on('submit',function(){
			NProgress.start();
			NProgress.done();
		});
		
// 		$('form').on('submit',function(){
// 			$(this).find('button[type=submit]').prop('disabled', true);
// 		});
	});
</script>

<script>
    function count_value(str_c,sendto,totallength){
    	text_length=document.getElementById(str_c).value; 
    	$('#'+sendto).html('<span>Total Length: '+totallength+'</span> <span> Remaining length:'+(parseInt(totallength)-parseInt(text_length.length))+'</span>');
    }
    function notificationTrigger(id , url){
        $.ajax({
            url:"<?php echo base_url(); ?>notifications/update_is_viewed",
            type:"post",
            data:{not_id:id},
            success:function (response) {
                location.replace(url);
            }
        });
    }
    
</script>
<!-- -------------------------input masking---------------------------------- -->
<script src="<?php echo base_url();?>assets/js/jquery.inputmask.bundle.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<!-- ----------------------------------datatables---------------------------- -->
<script src="<?php echo base_url();?>assets/js/datatables/responsive/js/datatables.responsive.js"></script>

<script>
    $(".cnic_mask").inputmask({"mask": "99999-9999999-9"});
</script>

<!-- Language Translator -->

<style type="text/css">
<!--
a.gflag {vertical-align:middle;font-size:16px;padding:1px 0;background-repeat:no-repeat;background-image:url(//gtranslate.net/flags/16.png);}
a.gflag img {border:0;}
a.gflag:hover {background-image:url(//gtranslate.net/flags/16a.png);}
#goog-gt-tt {display:none !important;}
.goog-te-banner-frame {display:none !important;}
.goog-te-menu-value:hover {text-decoration:none !important;}
body {top:0 !important;}
#google_translate_element2 {display:none!important;}
i {    font-family: 'Font Awesome 5 Free' !important;}
-->
</style>
<!--<select onchange="doGTranslate(this);">-->
<!--    <option value="">Select Language</option>-->
<!--    <option value="en|ar">Arabic</option>-->
<!--    <option value="en|en">English</option>-->
<!--    <option value="en|ur">Urdu</option>-->
<!--</select>-->
<!--<div id="google_translate_element2"></div>-->

<script type="text/javascript">
function googleTranslateElementInit2() {new google.translate.TranslateElement({pageLanguage: 'en',autoDisplay: false}, 'google_translate_element2');}
</script><script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>


<script type="text/javascript">
/* <![CDATA[ */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('6 7(a,b){n{4(2.9){3 c=2.9("o");c.p(b,f,f);a.q(c)}g{3 c=2.r();a.s(\'t\'+b,c)}}u(e){}}6 h(a){4(a.8)a=a.8;4(a==\'\')v;3 b=a.w(\'|\')[1];3 c;3 d=2.x(\'y\');z(3 i=0;i<d.5;i++)4(d[i].A==\'B-C-D\')c=d[i];4(2.j(\'k\')==E||2.j(\'k\').l.5==0||c.5==0||c.l.5==0){F(6(){h(a)},G)}g{c.8=b;7(c,\'m\');7(c,\'m\')}}',43,43,'||document|var|if|length|function|GTranslateFireEvent|value|createEvent||||||true|else|doGTranslate||getElementById|google_translate_element2|innerHTML|change|try|HTMLEvents|initEvent|dispatchEvent|createEventObject|fireEvent|on|catch|return|split|getElementsByTagName|select|for|className|goog|te|combo|null|setTimeout|500'.split('|'),0,{}))
/* ]]> */
</script>


<script>

    $(".lang_style").on("click",function(){
        var lang = $(this).attr('data-lang');
        if (lang == "en|ar") 
        {
            $('*').css("font-family","Noto Naskh Arabic, system-ui");
        }else if(lang == "en|ur")
        {
            $('head').append('<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">');
            $('body').css("font-family","Noto Nastaliq Urdu Draft, serif");
            
        }else{
            $('*').css("font-family","");
        }
    });
    
    $(document).ready(function(){
        //doGTranslate('en|ur');
        // var lang = $(".lang_style").attr('data-lang');
        // if(lang == "en|ur")
        // {
        //     $('head').append('<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">');
        //     $('body').css("font-family","Noto Nastaliq Urdu Draft, serif");
        // }
    });
    
    $(document).keydown(function(e) {
        if(e.key == "l" && e.altKey) {
            var current_url = window.location.pathname;
            var remove_slash = current_url.replace(/\//g, ":");
            location.href = '<?=base_url()?>Admin/lock_screen/'+remove_slash;
        }
    });

    var inactivityTime = function () {
        var time;
        window.onload = resetTimer;
        // DOM Events
        document.onmousemove = resetTimer;
        document.onkeydown = resetTimer;
    
        function logout_system() {
            // alert("You are now logged out.")
            var current_url = window.location.pathname;
            var remove_slash = current_url.replace(/\//g, ":");
            location.href = '<?=base_url()?>Admin/lock_screen/'+remove_slash;
        }
    
        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(logout_system, 600000)
            // 1000 milliseconds = 1 second
        }
    };
    
    window.onload = function() {
      inactivityTime();
    }
    
    $(".edu_password_validation").on("keyup",function(){
        var inputtxt = $(this).val();
        var decimal=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;

        if(inputtxt.match(decimal)) 
        { 
            $('input[type="submit"]').removeAttr("disabled");
            $('button[type="submit"]').removeAttr("disabled");
            $(".edu_password_validation_msg").text("");
            // return true;
        }else{
            $(".edu_password_validation_msg").text("Input Password and Submit [8 to 15 characters which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character]");
            $('input[type="submit"]').attr("disabled",true);
            $('button[type="submit"]').attr("disabled",true);
            // return false;
        }
    });
</script>
<?php $_SESSION['last_url'] = current_url(); ?>


