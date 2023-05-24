<style>
	
.heading_s{
	
text-align: center; font-size: 16px;    text-decoration: underline;

}
.text_style{
	    font-size: 12px;
    font-weight: bold;
}

.border_right{
 padding: 3px;
border-right: 1px solid black;
}


.border_left{
 padding: 3px;
border-left: 1px solid black;
}



.border_top{
 padding: 3px;
border-top: 1px solid black;
}


.border_bottom{
 padding: 3px;
border-bottom: 1px solid black;
}

.border_div{
 padding: 0px;
   border: 1px solid black;	
}



</style>


<div class="row" id="print_form">

<?php 
if(count($query_ary)==0){
	
	
$this->session->set_flashdata('club_updated','Chalan Form Not Available');	
redirect($_SERVER['HTTP_REFERER']);

exit;

}
foreach($query_ary as $row_data):
	

 $s_c_f_id=$row_data['s_c_f_id'];


$query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();




if($row_data['status']>=4){
	



for($i=1; $i<=3; $i++){
	



?>
<div class="border_div" style="width: 33.33%; float: left;">
<table  width="100%" >
<tr>
<td class="text_style border_bottom " colspan="2" >
	<table>
		<tr>
		<td class="text_style" ><img height="70" width="70" src="<?php echo display_link($row_data['school_logo'],'') ?>" /></td>

<td class="text_style " class="heading_s" ><strong><?php  
 echo $row_data['school_name'];
 

 
  ?></strong></td>
		</tr>
	</table>
</td>
</tr>
<tr>
<td class="text_style border_bottom border_right " ><?php echo 

'Copy Detail'; 

?></td>
<td class="text_style border_bottom " >srn no : <?php echo $row_data['chalan_form_number'];  ?></td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"><?php  
 echo $row_data['school_bank_detail']; ?></td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"> Department - class -  Section</td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"> 
<?php echo $row_data['section'].'-'.$row_data['class'].'-'.$row_data['department']; ?> 
</td>
</tr>
<tr>
<td class="text_style border_bottom  border_right" colspan="2">

<?php


$student_name=$row_data['student_name'];
$father_name=$row_data['father_name'];

echo "Father Name :".$student_name.' '.'student name'.' '.$parent_name;

?></td>

</tr>
<tr>
<td class="text_style border_bottom" colspan="2">Issue Date : <?php echo $issue_date= $row_data['issue_date']; 
?>
	/
	Due Date : 
	
	<?php echo $due_date= $row_data['due_date']; 
	?>
	
</td>

</tr>
<table width="100%" >

<tr id="chalan" >
<td class="text_style  border_bottom border_right" width="7%">s.n</td>
<td class="text_style  border_bottom border_right" width="72%">particulars</td>
<td class="text_style  border_bottom" width="20px">Amount</td>
</tr>
     
<?php 


$count_num=1;


$s_c_f_id=$row_data['s_c_f_id'];


$query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();


$chalan="";
$discount="";
$totle=0;


			foreach($query_a as $rec_row1){

if($rec_row1['type']==1){
	
	

	$chalan=$chalan.'<tr class="s_chalan"><td class="text_style border_bottom  border_right" width="7%" >'.$count_num.'</td><td class="text_style border_bottom  border_right" width="72%">'.$rec_row1['fee_type_title'].'</td> <td class="text_style border_bottom" width="20%">'.$rec_row1['amount'].'</td><tr>';
$totle=$rec_row1['amount']+$totle;

}else{
	
	$discount=$discount.'<tr class="s_chalan"><td class="text_style border_bottom  border_right" width="7%" >'.$count_num.'</td><td class="text_style border_bottom  border_right" width="72%">'.$rec_row1['fee_type_title'].'</td> <td class="text_style border_bottom" width="20%">'.$rec_row1['amount'].' </td></tr>';
	
	$totle=$totle-$rec_row1['amount'];
	
}



$count_num++;
 }




echo '<tr class="s_chalan"><td class="text_style border_bottom  border_right" width="7%" colspan="3" >Chalan </td></tr>';

echo $chalan;
echo '<tr class="s_chalan"><td class="text_style border_bottom  border_right" width="7%" colspan="3" >Discount </td></tr>';
echo $discount;


  echo '<tr class="s_chalan">

<td class="text_style border_bottom  border_right"></td>

<td class="text_style border_bottom border_right">Total Amount</td>

<td class="text_style border_bottom"> '.$totle.'</td>	
</tr>' ;
             
$this->load->helper("num_word");

echo '<tr class="s_chalan">
	
<td class="text_style " colspan="3"><strong> Rupees in Words : </strong>'.convert_number_to_words($totle).' Rupees 
		<br />
<strong>Note : </strong> After validity date rupees 200fine will be charged per day.
		
		
	</td>
	

</tr>';
	

?>

</table>
</table>
<br />

Principal Signature :__________________________
<br />
<br />

<?php  $admin_req1=get_user_info($row_data['issued_by']);  ?>


 <span>Issued By: <?php echo  $admin_req1[0]['name']; ?></span>

<br />

<img style="padding-left: 5px; " src="<?php echo  display_link($row_data['bar_code'],'student'); ?>">

</div>
<?php } 


}

else{
	
	
	
	$this->session->set_flashdata('club_updated','chalan is not approved');	
	
	redirect($_SERVER['HTTP_REFERER']);
	
	
	
}



endforeach;
?>










</div>




<div class="row">


	<div id="btnPrint" class="btn btn-primary">Print</div>
	
</div>
<script>
$(document).ready(function(){
	

$('#print_btn').click(function(){
	
	//$("#print_form").printThis();
var printContents = document.getElementById('print_form').innerHTML;
    var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
	
	});





});
	

	
	
</script>
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  border-left: 16px solid pink;
  width: 100px;
  height: 100px;
      margin-left: auto;
    margin-right: auto;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 1s linear infinite;
}


.loader_small {
  border: 5px solid #f3f3f3;
  border-radius: 50%;
  border-top: 5px solid blue;
  border-right: 5px solid green;
  border-bottom: 5px solid red;
  border-left: 5px solid pink;
  width: 20px;
  height: 20px;
      margin-left: auto;
    margin-right: auto;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 1s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media print {
    a {
        display:none;
    }
}


.label
{
    font-size: 10pt;
    font-weight: bold;
    font-family: Arial;
}
.contents
{
    border: 1px dotted black;
    padding: 5px;
    width: 300px;
}
.name
{
    color: #18B5F0;
}
.left
{
    float: left;
    width: 0px;
    height: 0px;
}
.right
{
    margin-left: 0px;
    line-height:0px;
}
.clear
{
    clear: both;
}
</style>
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
       // frameDoc.document.write('<link href="style.css" rel="stylesheet" type="text/css" />');
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

</script>