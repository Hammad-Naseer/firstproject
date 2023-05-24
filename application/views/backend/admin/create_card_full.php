
<?php
$schoo_ary=$this->db->query("select name as school_name,logo as school_logo from ".get_school_db().".school where school_id=".$_SESSION['school_id'])->result_array();
$std_ary=$this->db->query("select 
s.name as student_name,
sp.p_name as father_name,
st.title as section_name,
cs.name as class_name,
s.reg_num,
s.roll,
s.birthday,
s.barcode_image,
s.image
from ".get_school_db().".student s 
inner join ".get_school_db().".class_section st on s.section_id=st.section_id
inner join ".get_school_db().".class cs on cs.class_id=st.section_id 
inner join ".get_school_db().".departments dt on dt.departments_id=cs.departments_id
inner join ".get_school_db().".student_relation sr on s.student_id=sr.student_id
inner join ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id where s.school_id=".$_SESSION['school_id']." and s.student_id=$student_id ")->result_array();
?>

<div id="print_form" class="card land full ">
 
 <style>
.barc{
	   width: 52%;
    height: 54px;
    margin-top: 185px;
}
@page { margin: 0; }

@media print {
    .printbtn {
        display:none !important;
        
    }
    
    button{
		      display:none !important;
	}
    }	
	
	.size{
		width:1024;
		height:720px;
		border:1px solid #CCC;
		padding-top:50px;
	}
	
	/*.mypd{
		padding-left:24px;
	}*/
	.mgt40{
		margin-top:80px;
	}
.h2,	h2{
	    font-size: 20px !important;	
	}
	td{
		padding-left:10px;
		padding-right:10px;
	}	
	
	table{
		margin-top:10px;
	}
	
</style>
 
 
 <div class="size">

 	
 	<table style="margin-top:60px;">
 	
 	<tr>
 		
 		<td style="width:5%;"><h2><?php echo get_phrase('name'); ?> :</h2></td>
 		<td style="width:55%;"><h2><?php echo $std_ary[0]['student_name'];  ?></td>     
 		
 		
 		
 		<td style="width:5%;"><h2><?php echo get_phrase('father_name'); ?>:</h2></td>
 		<td style="width:35%;"><h2  ><?php echo $std_ary[0]['father_name'];  ?></h2></td>
 		
 	</tr>
 	
 		
 	<tr>
 		
 		<td><h2><?php echo get_phrase('class'); ?>:</h2></td>
 		<td><h2 ><?php echo $std_ary[0]['class_name'];  ?></h2></td>    
 		 <td><h2><?php echo get_phrase('section'); ?>:</h2></td>
 		<td><h2 ><?php echo $std_ary[0]['section_name'];  ?></h2></td>
 	</tr>
 	
 		
 	<tr>
 		
 		<td><h2><?php echo get_phrase('reg'); ?> #:</h2></td>
 		<td><h2 class="mypd"><?php echo $std_ary[0]['reg_num'];  ?></h2></td>     
 		<td><h2><?php echo get_phrase('roll'); ?>#:</h2></td>
 		<td><h2  class="mypd"><?php echo $std_ary[0]['roll'];  ?></h2></td>
 	</tr>
 	
 	
 		
 	<tr>
 		
 		<td> <h2><?php echo get_phrase('date_of_birth'); ?>:</h2></td>
 		<td><h2 class="mypd"><?php echo $std_ary[0]['birthday'];  ?></h2></td>     
 		<td><h2><?php echo get_phrase('expiry'); ?>:</h2></td>
 		<td><h2  class="mypd"><?php echo $std_ary[0]['birthday'];  ?></h2></td>
 	</tr>
 	<tr>
 		<td colspan="4">
 <center>

 <img 
src="<?php echo  display_link($std_ary[0]['barcode_image'],'student');
 ?>"class="barc">
 
 </center> 	
 		</td>
 	</tr>
 </table>
 </div>
 
 
 
<div  class="printbtn">
<button id="btnPrint" style="color:#000; font-weight:bold;"><?php echo get_phrase('print'); ?></button>
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