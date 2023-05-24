<style>
	.mgt35{margin-top:35px !important;}
</style>
<?php
    $stud_section=$this->uri->segment(3);

    if($stud_section=='student'){
        $condition="s.student_id=$student_id limit 1";
    }else{
    	$condition="s.section_id=$section_id";
    }
    $schoo_ary=$this->db->query("select name as school_name,logo as school_logo , address as school_address from ".get_school_db().".school where school_id=".$_SESSION['school_id'])->result_array();

$std_ary=$this->db->query("select 
s.name as student_name, 
s.mob_num as mob_num,
st.title as section_name,
cs.name as class_name,
s.reg_num,
s.roll,
s.birthday,
s.barcode_image,
s.image
from ".get_school_db().".student s 
inner join ".get_school_db().".class_section st on s.section_id=st.section_id
inner join ".get_school_db().".class cs on cs.class_id=st.class_id 
inner join ".get_school_db().".departments dt on dt.departments_id=cs.departments_id
where s.school_id=".$_SESSION['school_id']."  and s.student_status in (".student_query_status().") and $condition")->result_array();


?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <!--<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">-->
        <!--    <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>-->
        <!--</a>-->
        <h3 class="system_name inline">
            <?php echo get_phrase('create_card'); ?>
        </h3>
        
        <a style="float:right;" href="<?php echo base_url();?>c_student/student_information/<?php echo $section;?>" class="btn btn-primary"><?php echo get_phrase('back'); ?></a>
    </div>
    
</div>

<?php $abc=array($_POST['myselect']=>'selected'); ?>
<form method="post" id="myform">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4">
        	<div class="form-group">
        	   <label for="myselect"><b><?php echo get_phrase('please_select_card_size'); ?></b></label>
        	      <select class="form-control" name="myselect" id="myselect">
                	<option ><?php echo get_phrase('select_card_size'); ?></option>
                	<option <?php echo $abc['landscape']; ?>  value="landscape" >Landscape (CR80 -  3.375″ x 2.125″ )</option>
                	<option <?php echo $abc['portrate'];  ?> value="portrate">Portrate (CR80 -  2.125″ x 3.375″ )</option>
                </select>
        	</div>	
        </div>
    </div>
</form>
<div class="col-lg-12 col-sm-12">
    <div id="print_form" class="card land full" style=" overflow-x:auto;padding: 10px;">
    <!-- ------------universal styles-------------------------- -->
    <style>
        @page { margin: 0; }
        @media print
    	{
    	    .printbtn {
    	        display:none !important;   
    	    }
    	    button{
    			display:none !important;
    		}
        }
        
        .printbtn{
            margin-top: 15px;
            padding: 10px;
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
        
        .mylogo{
        	height:70px; 
        	width:140px;
        }
        .school_name{
        	font-size: 15px; font-weight: bold;
        }
        table {
        	margin-top: 10px !important;
        }
        td{
    		/*padding-left:10px;
    		padding-right:10px;*/
    		vertical-align: top !important;
        }
        
        .mylogo2{
    		height:90px;
    		width:90px;
    		margin-left: 10px;
        }
        .f15b{
        	font-size: 13px !important;
            font-weight: bold;
        }
    </style>
    
    <?php 
        if (isset($_POST["myselect"])){
    	   $a = $_POST['myselect'];
    ?>
    
    <!--Land Scape CSS-->
    <?php if($a=="landscape"){ ?>
    <style>
    
    .pos-stdntschl-card {
    width: 52px;
    }  
    .studnt-idcard-box {
    width: 333px;
    height: 219;
    }   
    .stndcard-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #02658d;
    padding: 0px 10px 0px 0px;
    }
    .stndcard-top p {
    color: #fff;
    font-weight: 600;
    margin: 0px;
    } 
    .stdnt-img {
    /*background: #02658d;*/
    /*box-shadow: 0px 0px 4px 0px #61c8f2;*/
    margin-left: 10px;
    height: 100px;
    width: 100px;
    }
    .stdntcard-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 13px 10px 13px 0px;
    }
    .stdntcard-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #02658d;
    padding: 12px;
    }
    .studnt-idcard {
    box-shadow: 0px 0px 7px -6px black;
    border: solid 0.1px #02658d;
    }
    .stdntcard-bottom p {
        margin:0px;
        color:#fff;
        font-weight: 600;
    }
    .stdnt-info p {
    margin: 0px 0px 3px;
    }
    
    body {
              -webkit-print-color-adjust: exact !important;
    } 
    </style>
     
    <?php } ?>

    <!-- ------------1-------------333*210----------- -->
    
     <?php if($a=="portrate"){ ?>
     <style>
    
    .studnt-idcard-potrtbox {
    width: 210px;
    height: 333px;
    }	
    .pos-stdntschl-potrtcard {
    width: 80px;
    }
    .stndcard-potrttop {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #21a9e1;
    }
    .stndcard-potrttop p {
    margin: 0;
    padding-right:8px;
    }
    .stdntcard-potrtinfo {
    display: flex;
    flex-direction: column;
    align-content: space-between;
    justify-content: space-between;
    align-items: center;
    }
    .stdntcard-potrtbottom {
    display: flex;
    justify-content: center;
    background: #21a9e1;
    padding: 4px 5px 4px 5px;
    }
    .stdntcard-potrtbottom p {
    margin: 0; 
    } 
    .studnt-potrtidcard { 
    box-shadow: 0px 0px 7px -6px black;
    border: solid 0.1px #02658d;
    }
    .stdnt-potrtimg {
    /*background: #21a9e1;*/
    margin-top: 4px;
    padding: 0px;
    border-radius: 5px;
    }
    .stndcard-potrttop p {
    color: #fff !important;
    font-weight: 600;
    }
    .stdntcard-potrtbottom p {
    margin: 0;
    color: #fff;
    font-weight: 600;
    }


    body {
              -webkit-print-color-adjust: exact !important;
    } 

    </style>
     
    
    <?php }?>
    
    <!-- ---------------------------------------------- -->
    
    <!-- -------6----------full -1024*720-------------- -->
    
     <?php if($a=="landscapefull"){ ?>
     <style>
    .b{
    	width: 73%;
        margin-top: 100px;
    }	
    .size{
    	width:1000px;
    	height:700px;
    	border:1px solid #CCC;
    	padding-top:10px;
    	text-align: left;
    }
    .h2,h2{
    	font-size:48px !important;	
    	margin:18px 0px !important;
    }
    .mylogo{
    	height:150px; width:150px;
    }
    	 
    .mylogo2{
    	height: 360px;
        width: 360px;
    }
    .school_name{
    	font-size: 40px;
    	font-weight: bold;
    }
    .f15b{
    	font-size: 40px !important;
        font-weight: bold;
    }
    </style>
    <?php } ?>

    <!-- ----6-------portratefull--------------- -->
    
     <?php if($a=="portratefull"){ ?>
    	
     <style>
    .c{
    	width: 50%;
    }
    table tr td{
    	margin:10px;
    	padding:7px;
    }
    	
    .size{
    	height:920px;
    	width:650px;
    	border:1px solid #CCC;
    	padding-top:60px;
    	text-align: left;
    	}
    .h2,h2{
    	    font-size:37px !important;
    	    font-weight:bold !important;	
    	}
    .mylogo{ 
    	height:200px;
     	width:200px;
     }
    .mylogo2{ 
    	height:225px; 
    	width:225px;
    }
    .school_name{ 
    	font-size: 42px !important;
    	font-weight: bold;
    }
    .mymgt60{
    	margin-top:30px;
    	margin-left: 111px;
    }
    .f15b{
    	font-size: 50px !important;
        font-weight: bold;
    }	 
    </style>
     
    <?php }?>
    
    <!-- --------------------------------------- -->
    
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
    <?php
    //for($i=0; $i<=10; $i++){
    foreach($std_ary as $school_ary)
    {
    	if($a=="landscape")
    	{
    
    ?>
    
    <div class="landscape landscapefull studnt-idcard-box" style="margin-bottom:40px;">
       <div class="studnt-idcard">
           <div class="stndcard-top">
               <img src="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/setting_1622775652.png"?>" class="pos-stdntschl-card">
               <p><?php echo $_SESSION['school_name']; ?></p>
           </div>
           <div class="stdntcard-info">
               <div>
                    <?php
                    if($school_ary['image'] != ""){
                    ?>
                        <img src="<?php echo base_url()?>uploads/<?php echo $school_ary['image']; ?>" class="stdnt-img pos-stdntschl-card">
                    <?php
                    }else{
                    ?>
                        <img src="<?php echo base_url()."uploads/"."default.png"?>" class="stdnt-img pos-stdntschl-card">
                    <?php
                    }
                    ?>
               </div>
               <div class="stdnt-info"> 
                    <p class="stdnt-card-name"><b>Name:</b> <?php echo $school_ary['student_name']; ?></p>
                    <p class="stdnt-card-name"><b>Class:</b> <?php echo $school_ary['class_name']." - ".$school_ary['section_name']; ?></p>
                    <p class="stdnt-card-name"><b>Reg#:</b> <?php echo $school_ary['reg_num']; ?></p>
                    <p class="stdnt-card-name"><b>Roll#:</b> <?php echo $school_ary['roll']; ?></p>
                    <p class="stdnt-card-name"><b>Phone#:</b> <?php echo $school_ary['mob_num']; ?></p>
               </div>
           </div>
           <div class="stdntcard-bottom">
               <?php
                $path = "uploads/".$_SESSION['folder_name']."/student/".$school_ary['barcode_image'];
                if(file_exists($path)){
                ?>
                <img src="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/student/".$school_ary['barcode_image']; ?>" class="stdnt-barcode">
                <?php
                }
                ?>
                
           </div>
       </div>
    </div>
    
    <?php
    }
    if($a=="portrate"){
    ?>
    
    
    <div class="portrate portratefull studnt-idcard-potrtbox" style="display: none;">
        <div class="studnt-potrtidcard">
            <div class="stndcard-potrttop">
               <img src="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/setting_1622775652.png" ?>" class="pos-stdntschl-potrtcard">
               <p><?php echo $_SESSION['school_name']; ?></p>
            </div>
            <div class="stdntcard-potrtinfo">
                <div class="stdnt-potrtimg">
                    <!--<img src="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/setting_1622775652.png" ?>" class="pos-stdntschl-potrtcard">-->
                    
                    <?php
                    if($school_ary['image'] != ""){
                    ?>
                        
                        <img src="<?php echo base_url()?>uploads/<?php echo $school_ary['image']; ?>" class="stdnt-img pos-stdntschl-potrtcard">
                    <?php
                    }else{
                    ?>
                        <img src="<?php echo base_url()."uploads/"."default.png"?>" class="stdnt-img pos-stdntschl-potrtcard">
                    <?php
                    }
                    ?>
                </div>
                <div class="stdnt-potrtinfo"> 
                    <p class="stdnt-card-potrtname"><b>Name:</b> <?php echo $school_ary['student_name']; ?></p>
                    <p class="stdnt-card-potrtname"><b>Class:</b> <?php echo $school_ary['class_name']." - ".$school_ary['section_name']; ?></p>
                    <p class="stdnt-card-potrtname"><b>Reg#:</b> <?php echo $school_ary['reg_num']; ?></p>
                    <p class="stdnt-card-potrtname"><b>Roll#:</b> <?php echo $school_ary['roll']; ?></p>
                    <p class="stdnt-card-potrtname"><b>Phone#:</b> <?php echo $school_ary['mob_num']; ?></p>
                </div>
            </div>
            <div class="stdntcard-potrtbottom">
                <?php
                $path = "uploads/".$_SESSION['folder_name']."/student/".$school_ary['barcode_image'];
                if(file_exists($path)){
                ?>
                <img src="<?php echo base_url()."uploads/".$_SESSION['folder_name']."/student/".$school_ary['barcode_image']; ?>" class="stdnt-barcode">
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    
    
     <?php } } ?>
    <div  class="printbtn" style=" border:none;">
        <button id="btnPrint" class="btn btn-danger text-white" style="color:#000; font-weight:bold;">
        <?php echo get_phrase('print'); ?></button>
    </div>
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

var doc = new jsPDF();
var specialElementHandlers = {
    '#editor': function (element, renderer) {
        return true;
    }
};
</script>