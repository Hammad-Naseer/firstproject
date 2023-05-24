<script>
$("myselect").change(function(){
	
    if ($("myselect").val()=="landscape" || "landscapefull"){
        $(".t1").css("display","inline");
        $(".t2").css("display","none");
    
    }else{
        $(".t2").css("display","inline");
        $(".t1").css("display","none");
    }	
	
});
</script>

<style>
	.mgt35{margin-top:10px !important;}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 topbar">
        <h3 class="system_name inline"><?php echo get_phrase('staff'); ?> </h3>
        <a style="float: right;" href="<?php echo base_url();?>user/staff_listing/" class="btn btn-primary"><?php echo get_phrase('back'); ?></a>
    </div>
</div>


<?php
if(isset($staff_id) && $staff_id!==""){
    $condition=" and s.staff_id=$staff_id limit 1";
}else{
    $condition="   ";
}

    $schoo_ary=$this->db->query("select name as school_name,logo as school_logo from ".get_school_db().".school where school_id=".$_SESSION['school_id'])->result_array();
    
    $std_ary=$this->db->query("select * from ".get_school_db().".staff s inner join ".get_school_db().".designation d on d.designation_id=s.designation_id
    where s.school_id=".$_SESSION['school_id']."  $condition")->result_array();

?>



<!----------------------------select box-------------------->

<div class="thisrow mgt35" style="padding: 10px 10px 0px 10px;">
<div class="row">

<div class="col-sm-3">
    <h3 style="font-size: 18px;"><?php echo get_phrase('please_select_card_size'); ?></h3>
</div>
<div class="col-sm-4">
    
<?php $abc=array($_POST['myselect']=>'selected'); ?>


<form method="post" id="myform">
    <select class="form-control" name="myselect" id="myselect">
    	<option ><?php echo get_phrase('select_card_size'); ?></option>
    	<option <?php echo $abc['landscape']; ?>  value="landscape" ><?php echo get_phrase('landscape'); ?> (CR80 -  3.375? x 2.125? )</option>
    	<option <?php echo $abc['portrate'];  ?> value="portrate"><?php echo get_phrase('portrate'); ?> (CR80 -  2.125? x 3.375? )</option>
    	
    	<option <?php echo $abc['landscapefull'];  ?> value="landscapefull"><?php echo get_phrase('card_printer_printing_size_landscape'); ?></option>
    		<option <?php echo $abc['portratefull'];  ?> value="portratefull"><?php echo get_phrase('card_printer_printing_size_portrate'); ?></option>
    </select>

</form>

</div>
</div>

</div>
<!---------------------------------------------------------->

<div id="print_form" class="card land full " style=" overflow-x:auto; " >
<!--------------universal styles---------------------------->

<style>
	
	@page { margin: 0; }

    @media print {
        .printbtn {
            display:none !important;
        }
        button{
		    display:none !important;
	    }
    }
	
	.mgt40{
		margin-top:80px;
	}

	.barc{
	    width: 52%;
        height: 20px;
    }
	.size{
		width:333px;
		height:210px;
	}
	
    .h2,h2{
	    font-size: 12px !important;	
	    margin: 2px 0px;
	}

	 .mylogo{ height:60px; width:60px;}
	 .school_name{ font-size: 15px; font-weight: bold;}
	table {
		margin-top: 10px !important;
	 }
	
	td{
		padding-left:10px;
		padding-right:10px;
		vertical-align: top !important;
	}

	.mylogo2{ height:90px; width:90px;}
	.f15b{
	    font-size: 13px !important;
        font-weight: bold;
        margin: 10px 0px;
       
        
	}
	
	
</style>

<!----------------------------------------->


<?php 
    if (isset($_POST["myselect"])){
	    $a= $_POST['myselect'];
?>

<!-------------------1-------------333*210-------------------------------------------->
 <?php if($a=="landscape"){ ?>
 <style>
 .row.staf-row-setlandscp {
    margin: 14px 7px;
}
.staff-id-cardlandscp {
    width: 333px;
    height: 210px; 
    margin: 0px;
    padding: 0px;
    position: relative;
    display: flex;
    align-items: center;
    box-shadow: 0px 0px 15px -13px #000;
    background: white; 
    border: solid 0.1px #0ca1d2;
}
.staff-id-cardlandscp h1 {
    color: white !important;
    text-align: center !important;
}

.staff-id-cardlandscp .pos-stf-card {
    position: absolute; 
    bottom: 0;
}
.pos-stfbtm-card {
    position: absolute;
    bottom: 0;
    background: #0ca1d2;
    padding: 3px 0px;
    align-items: center;
    display: flex;
    width:100%;
}
.pos-stfinfo-card {
    position: absolute;
    align-items: center;
    width: 100%;
    display: flex;
}
.pos-stftop-card {
    position: absolute;
    top: 0;
    background: #0ca1d2;
    margin: 0;
    color: #fff;
    padding: 7px 0px;
    width:100%;
    background-image: url(https://dev.indiciedu.com.pk/uploads/bg-secure.png);"
}
.pos-stfschl-card {
    position: absolute;
    top: 0;
    right: 0;
}
.pos-stfbtm-card h2 {
    color: #fff !important;
    font-weight: 600;
}
img.staf-img {
    width: 108px;
}
img.bar-code {
    max-width: 103px;
}
.col {
    padding: 0px 15px ;
} 
body {
          -webkit-print-color-adjust: exact !important;
} 

	.f15b{
	   margin-left:147px;
	}
	


</style>
<?php }?>

<!---------------------------------------------------------------------------->

<!-------------------1-------------333*210-------------------------------------------->
 <?php if($a=="portrate"){ ?>
 <style>
	
.staff-id-cardpotrate {
    width: 210px;
    height: 333px;
    margin: 0px;
    padding: 0px;
    position: relative;
    display: flex;
    align-items: center;
    box-shadow: 0px 0px 15px -13px #000;
    background: white;
    justify-content: center;
    border: solid 0.1px #0ca1d2;
}
img.staf-img {
    width: 86px;
}
.pos-stftoppotst-card {
    position: absolute;
}
.pos-stfbtmpotst-card {
    position: absolute;
    bottom:0;
}
.pos-stftoppotst-card {
    position: absolute;
    top: 0;
    width: 100%;
    background: #02658d;
    display: flex;
    justify-content: center;
}
.pos-stfimgpotst-card {
    position: absolute;
    top: 24%;
    align-items: center;
    display: flex;
    justify-content: center;
}
.pos-stfimgpotst-card .col {
    padding: 0;
}
.staf-row-setpotrate {
    margin: 14px 7px;
}
.pos-stfinfopotst-card {
    position: absolute;
    top: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.pos-stfbtmpotst-card {
    position: absolute;
    bottom: 0;
    display: flex !important;
    align-items: center;
    justify-content: center;
    padding: 3px 0px;
    width: 100%;
}
.line-hr {
    top: 104%;
    height: 5px;
    background: #012b3c;
    width: 100%;
    position: absolute;
}

body {
          -webkit-print-color-adjust: exact !important;
} 

</style>
<?php }?>
<!---------------------------------------------------------------------------->

<!-----------------6---------------full -1024*720------------------------------------------->

 <?php if($a=="landscapefull"){ ?>
 <style>
 .row.staf-row-setpotrate.portrate.portratefull {
    display: none;
}
 
 .row.staf-row-setlandscp {
    margin: 14px 7px;
}
.staff-id-cardlandscp {
    width: 333px;
    height: 210px; 
    margin: 0px;
    padding: 0px;
    position: relative;
    display: flex;
    align-items: center;
    box-shadow: 0px 0px 15px -13px #000;
    background: white; 
    border: solid 0.1px #0ca1d2;
}
.staff-id-cardlandscp h1 {
    color: white !important;
    text-align: center !important;
} 
.staff-id-cardlandscp .pos-stf-card {
    position: absolute; 
    bottom: 0;
}
.pos-stfbtm-card {
    position: absolute;
    bottom: 0;
    background: #0ca1d2;
    padding: 3px 0px;
    align-items: center;
    display: flex;
    width:100%;
}
.pos-stfinfo-card {
    position: absolute;
    align-items: center;
    width: 100%;
    display: flex;
}
.pos-stftop-card {
    position: absolute;
    top: 0;
    background: #0ca1d2;
    margin: 0;
    color: #fff;
    padding: 7px 0px;
    width:100%;
    background-image: url(https://dev.indiciedu.com.pk/uploads/bg-secure.png);"
}
.pos-stfschl-card {
    position: absolute;
    top: 0;
    right: 0;
}
.pos-stfbtm-card h2 {
    color: #fff !important;
    font-weight: 600;
}
img.staf-img {
    width: 108px;
}
img.bar-code {
    max-width: 103px;
}
.col {
    padding: 0px 15px ;
} 
body {
          -webkit-print-color-adjust: exact !important;
} 
	.f15b{
	   margin-left:147px;
	}



 /*   .b{*/
 /*   	width: 73%;*/
 /*       margin-top: 100px;*/
 /*   }	*/
	/*.size{*/
	/*	width:1000px;*/
	/*	height:700px;*/
	/*	border:1px solid #CCC;*/
	/*	padding-top:10px;*/
	/*	text-align: left;*/
	/*}*/
	

 /*   .h2,h2{*/
	/*    font-size:48px !important;	*/
	/*    margin:18px 0px !important;*/
	/*}*/
	/* .mylogo{ */
	/*     height:150px; */
	/*     width:150px;*/
	/* }*/
	 
	/* .mylogo2{ */
 /*       height: 360px;*/
 /*       width: 360px;*/
	/* }*/
	/*.school_name{ */
	/*    font-size: 40px; */
	/*    font-weight: bold;*/
	/*}*/
	/*.f15b{    */
	/*    font-size: 40px !important;*/
 /*       font-weight: bold;*/
	/*}*/
</style>
<?php }?>
<!---------------------------------------------------------------------------->

<!-----------------6---------------portratefull------------------------------------------->
 <?php if($a=="portratefull"){ ?>

 <style>
 
 
 
    .staff-id-cardpotrate {
    width: 210px;
    height: 333px;
    margin: 0px;
    padding: 0px;
    position: relative;
    display: flex;
    align-items: center;
    box-shadow: 0px 0px 15px -13px #000;
    background: white;
    justify-content: center;
    border: solid 0.1px #0ca1d2;
}
img.staf-img {
    width: 86px;
}
.pos-stftoppotst-card {
    position: absolute;
}
.pos-stfbtmpotst-card {
    position: absolute;
    bottom:0;
}
.pos-stftoppotst-card {
    position: absolute;
    top: 0;
    width: 100%;
    background: #02658d;
    display: flex;
    justify-content: center;
}
.pos-stfimgpotst-card {
    position: absolute;
    top: 24%;
    align-items: center;
    display: flex;
    justify-content: center;
}
.pos-stfimgpotst-card .col {
    padding: 0;
}
.staf-row-setpotrate {
    margin: 14px 7px;
}
.pos-stfinfopotst-card {
    position: absolute;
    top: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.pos-stfbtmpotst-card {
    position: absolute;
    bottom: 0;
    display: flex !important;
    align-items: center;
    justify-content: center;
    padding: 3px 0px;
    width: 100%;
}
.line-hr {
    top: 104%;
    height: 5px;
    background: #012b3c;
    width: 100%;
    position: absolute;
}

body {
          -webkit-print-color-adjust: exact !important;
} 


	/* .c{*/
	/*   width: 50%;*/
 /*   }*/
	/* table tr td{margin:10px; padding:7px;}*/
	/*.size{*/
	/*	height:920px;*/
	/*	width:650px;*/
	/*	border:1px solid #CCC;*/
	/*	padding-top:60px;*/
	/*	text-align: left;*/
	/*}*/
	
 /*   .h2,h2{*/
	/*    font-size:37px !important; */
	/*    font-weight:bold !important;	*/
	/*}*/
	/*.mylogo{ */
	/*    height:200px; */
	/*    width:200px;*/
	/*}*/
	/*.mylogo2{ */
	/*    height:225px; */
	/*    width:225px;*/
	/*}*/
	/*.school_name{ */
	/*    font-size: 42px !important; */
	/*    font-weight: bold;*/
	/*}*/
	/*.mymgt60{*/
	/*    margin-top:30px;     */
	/*    margin-left: 111px;*/
	/*}*/
	/*.f15b{    */
	/*    font-size: 50px !important;*/
 /*       font-weight: bold;*/
 /*       margin: 10px 0px;*/
	/*}*/
	 
</style>
 
<?php }?>
<!---------------------------------------------------------------------------->


<?php } ?>

<script>

$(document).ready(function(){
	$("#myselect").change(function(){
		$("#myform").submit();
	});
	
	if($("#myselect").val()=="landscape"){
		$(".portrate").css("display","none");
		$(".landscape").css("display","block");
	}
	else if ($("#myselect").val()=="portrate"){
		$(".portrate").css("display","block");
		$(".landscape").css("display","none");
	}else if ($("#myselect").val()=="portratefull"){	
		$(".portrate").css("display","block");
		$(".landscape").css("display","none");
	}
});
</script>
 
<?php foreach($std_ary as $school_ary){ 
//  echo "<pre";
//  print_r($school_ary);
 ?>
<!--LANDSCAPE CARD FORMAT-->
<div class="row staf-row-setlandscp landscape landscapefull">
    <div class="staff-id-cardlandscp"> 
    
            <h1 align="center" class="school_name text-center pos-stftop-card w-100" style="background: #02658d; color:white;"><?php echo $schoo_ary[0]['school_name'] ?></h1> 
            <img  src="<?php echo  display_link($schoo_ary[0]['school_logo'],'') ?>" class="mylogo pos-stfschl-card">
            <div class="d-flex pos-stfinfo-card  w-100">
                <div class="col col-sm-12 col-md-5  ">
                    <img  class="staf-img" src="<?php
                                if($school_ary['staff_image']==""){
                                    echo  base_url().'uploads/default.png';
                                }else{
                                    echo  display_link($school_ary['staff_image'],'staff');
                                }
                    ?>">
                </div>
                  </div>
                <div clas="col col-sm-12 col-md-7  ">
                    
                    <p class="f15b">Name: <?php echo $school_ary['name']; ?></p>
                    <h2 class="f15b my-3">Role: <?php echo get_designation_name($school_ary['designation_id']); ?></h2>
                    <h2 class="f15b my-3">ID No: <?php echo $school_ary['id_no']; ?></h2>
                </div> 
          
            <div class="d-flex pos-stfbtm-card w-100" style="background: #02658d; color:white;">
                <div class="col col-sm-12 col-md-5 "> 
                    	
                    	 <img src="<?php echo  display_link($school_ary['barcode_image'],'staff'); ?>"class="bar-code">
                    	  
                </div>
                <!--<div class="col col-sm-12 col-md-7  ">-->
                <!--    <h2> Issue Date: 10-18-2021</h2>-->
                <!--</div> -->
            </div>
    </div>
</div>






<!--POTRAIT CARD FORMAT--> 


<div class="row staf-row-setpotrate portrate portratefull">
    <div class="staff-id-cardpotrate"> 
        <div class="pos-stftoppotst-card ">
            <img  src="<?php echo  display_link($schoo_ary[0]['school_logo'],'') ?>" class="mylogo pos-stfschlpotst-card">
            <div class="line-hr"></div>
        </div> 
        <div class="d-flex pos-stfimgpotst-card  w-100">
            <div class="col col-sm-12 col-md-5  ">
                    <img  class="staf-img" src="<?php
                                if($school_ary['staff_image']==""){
                                    echo  base_url().'uploads/default.png';
                                }else{
                                    echo  display_link($school_ary['staff_image'],'staff');
                                }
                    ?>">
                </div>
        </div>
        <div class="d-flex pos-stfinfopotst-card  w-100">
            <div clas="col col-sm-12 col-md-7  ">
                    
                    <p class="f15b">Name: <?php echo $school_ary['name']; ?></p>
                    <h2 class="f15b my-3">Role: <?php echo get_designation_name($school_ary['designation_id']); ?></h2>
                    <h2 class="f15b my-3">ID No: <?php echo $school_ary['id_no']; ?></h2>
                    <!--<h2 class="f15b my-3">Issue Date: 10-18-2021</h2>-->
                    
            </div> 
        </div>
        <div class="d-flex pos-stfbtmpotst-card w-100" style="background: #02658d; color:white;">
                     	
                    <img src="<?php echo  display_link($school_ary['barcode_image'],'staff'); ?>"class="bar-code">
                    	 
        </div>
     
    </div>    
</div>



<?php } ?>
 
    <div  class=" p-5 printbtn" style=" border:none;">
        <button id="btnPrint" class="btn btn-danger text-white" style="color:#000; font-weight:bold;"><?php echo get_phrase('print'); ?></button>
    </div>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
$(function () {
    $("#btnPrint").click(function () {
        var contents = $("#print_form").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title><?php echo get_phrase('DIV_contents'); ?></title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.
   //     frameDoc.document.write('<link href="style.css" rel="stylesheet" type="text/css" />');
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    });
});

// var doc = new jsPDF();
// var specialElementHandlers = {
//     '#editor': function (element, renderer) {
//         return true;
//     }
// };
</script>