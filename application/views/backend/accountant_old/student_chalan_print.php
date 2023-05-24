<!--<?php print_r($rows); ?>-->
<?php

$query=$this->db->query("select * from chalan_settings");
if($query->num_rows()>0){
	
foreach($query->result() as $settings){
 $school_name=$settings->school_name;
$address=$settings->address;
$logo=$settings->logo;
$bank_details=$settings->bank_details;
$terms=$settings->	terms;
	
	
}	
	
	
	
}


 ?>


<div class="row" >

<div id="printableArea">
      <style>
	
	@page {
    margin: 10px;
}
	
	
</style>

   	
   	<div class="col-lg-4 col-md-4 col-sm-4" style="    border-right: 1px solid #CCC;" >
   	
   <table  class="table-responsive">
   <tr>
   	<td><img height="100" width="100" src="<?php echo base_url();?>uploads/<?php echo $logo; ?>"/></td>
   	<td>&nbsp;
   	<br />
   	
   	</td>
   	<td valign="top">	<h4><strong><center><?php echo $school_name; ?></center></strong></h4>
   	
   	<center><?php echo $address; ?></center>
   	
 
   	</td>
   	
   </tr>
   
   </table>	
   	
   	
   
   	
   	
   
   	
   	
   	
   
<table  class="table-responsive">

<tr > 
<td style="border:1px solid #CCC; text-align:center;"><?php echo nl2br($bank_details);?>
 
 <td>
<center><strong> Due Date:

</strong><?php foreach($rows as $rr){	
   echo 	$rr->due_date;	
} ?>
	
</center>

</td>
 
 </tr>
 
<tr style="text-align:center"><td>
Payable at any SCB Branch


</td></tr>


<tr><td>Chalan #</td><td><?php foreach($rows as $rr){
	
echo 	$rr->chalan_num;
	
} ?></td></tr>
<tr><td>Date</td><td>
	<?php foreach($rows as $rr){
	
	 	$timestamp=$rr->date;
$timestamp1=strtotime($timestamp);
echo date('d/m/Y',$timestamp1);
	
} ?>
	
	
</td></tr>
<tr><td>Billing Month</td><td>
<?php foreach($rows as $rr){
	
echo 	$rr->chalan_month.' ';
echo 	$rr->chalan_year;
	
} ?>
</td></tr>

 </tr>
<tr  style="text-align:center"><td>
TCS DHA campus RWp


</td></tr>

<tr><td>Roll #</td><td><?php foreach($rows as $rr){
	
echo 	$rr->roll;
	
} ?></td></tr>
<tr><td>Name</td><td><?php foreach($rows as $rr){
	
echo 	$rr->name;
	
} ?></td></tr>
<tr><td>Class</td><td><?php foreach($rows as $rr){
	
echo 	$rr->class_name;
	
} ?></td></tr>

<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>amount</th>


<tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan1;
	
} ?></td><td>
	<?php foreach($rows as $rr){
	
echo 	$rr->amount1;
	
} ?>
	
</td>
</tr><tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan2;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount2;
	
} ?></td>
</tr><tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan3;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount3;
	
} ?></td>
</tr>


<tr>
<td ><?php foreach($rows as $rr){
	
echo 	$rr->chalan4;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount4;
	
} ?></td>
</tr>
<tr>
<td ><?php foreach($rows as $rr){
	
echo 	$rr->chalan5;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount5;
	
} ?></td>
</tr>
<tr>
<td >fee Concession</td><td><?php foreach($rows as $rr){
	
echo 	$rr->discount;
	
} ?></td>
</tr>
<tr>
<th > Total</th><th><?php foreach($rows as $rr){
	
echo 	$rr->total_amount;
	
} ?></th>
</tr>





<tr><td> late payment surcharde @ Rs 15. </td></tr>

<tr><td> total payment after due date </td> <td><?php foreach($rows as $rr){
	
echo 	$rr->total_amount+15;
	
} ?></td></tr>




<tr><th>payment terms</th></tr>


<tr style="font-size:18; font-weight:bold; text-align:center; "><td style="border: 1px solid #CCC;"> Office Copy </td></tr>


</table>


<table height="100">
<tr>
		
		<td><h4><strong>Payment Terms:</strong></h4></td>
	</tr>
	<tr>
	<td>
		<?php 
		
		echo nl2br($terms);
		?>
</td>		
	</tr>

	
</table>

</div>	

  	<div class="col-lg-4 col-md-4 col-sm-4" style="    border-right: 1px solid #CCC;" >
   	
   <table  class="table-responsive">
   <tr>
   	<td><img height="100" width="100" src="<?php echo base_url();?>uploads/<?php echo $logo; ?>"/></td>
   	<td>&nbsp;
   	<br />
   	
   	</td>
   	<td valign="top">	<h4><strong><center><?php echo $school_name; ?></center></strong></h4>
   	
   	<center><?php echo $address; ?></center>
   	
 
   	</td>
   	
   </tr>
   
   </table>	
   	
   	
   
   	
   	
   
   	
   	
   	
   
<table  class="table-responsive">

<tr > 
<td style="border:1px solid #CCC; text-align:center;"><?php echo nl2br($bank_details);?>
 
 <td>
<center><strong> Due Date:

</strong><?php foreach($rows as $rr){	
   echo 	$rr->due_date;	
} ?>
	
</center>

</td>
 
 </tr>
 
<tr style="text-align:center"><td>
Payable at any SCB Branch


</td></tr>


<tr><td>Chalan #</td><td><?php foreach($rows as $rr){
	
echo 	$rr->chalan_num;
	
} ?></td></tr>
<tr><td>Date</td><td>
	<?php foreach($rows as $rr){
	
	 	$timestamp=$rr->date;
$timestamp1=strtotime($timestamp);
echo date('d/m/Y',$timestamp1);
	
} ?>
	
	
</td></tr>
<tr><td>Billing Month</td><td>
<?php foreach($rows as $rr){
	
echo 	$rr->chalan_month.' ';
echo 	$rr->chalan_year;
	
} ?>
</td></tr>

 </tr>
<tr  style="text-align:center"><td>
TCS DHA campus RWp


</td></tr>

<tr><td>Roll #</td><td><?php foreach($rows as $rr){
	
echo 	$rr->roll;
	
} ?></td></tr>
<tr><td>Name</td><td><?php foreach($rows as $rr){
	
echo 	$rr->name;
	
} ?></td></tr>
<tr><td>Class</td><td><?php foreach($rows as $rr){
	
echo 	$rr->class_name;
	
} ?></td></tr>

<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>amount</th>


<tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan1;
	
} ?></td><td>
	<?php foreach($rows as $rr){
	
echo 	$rr->amount1;
	
} ?>
	
</td>
</tr><tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan2;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount2;
	
} ?></td>
</tr><tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan3;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount3;
	
} ?></td>
</tr>


<tr>
<td ><?php foreach($rows as $rr){
	
echo 	$rr->chalan4;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount4;
	
} ?></td>
</tr>
<tr>
<td ><?php foreach($rows as $rr){
	
echo 	$rr->chalan5;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount5;
	
} ?></td>
</tr>
<tr>
<td >fee Concession</td><td><?php foreach($rows as $rr){
	
echo 	$rr->discount;
	
} ?></td>
</tr>
<tr>
<th > Total</th><th><?php foreach($rows as $rr){
	
echo 	$rr->total_amount;
	
} ?></th>
</tr>





<tr><td> late payment surcharde @ Rs 15. </td></tr>

<tr><td> total payment after due date </td> <td><?php foreach($rows as $rr){
	
echo 	$rr->total_amount+15;
	
} ?></td></tr>




<tr><th>payment terms</th></tr>


<tr style="font-size:18; font-weight:bold; text-align:center; "><td style="border: 1px solid #CCC;"> Bank Copy </td></tr>


</table>
<table height="100">
<tr>
		
		<td><h4><strong>Payment Terms:</strong></h4></td>
	</tr>
	<tr>
	<td>
		<?php 
		
		echo nl2br($terms);
		?>
</td>		
	</tr>

	
</table>

</div>

  	<div class="col-lg-4 col-md-4 col-sm-4" style="    border-right: 1px solid #CCC;" >
   	
   <table  class="table-responsive">
   <tr>
   	<td><img height="100" width="100" src="<?php echo base_url();?>uploads/<?php echo $logo; ?>"/></td>
   	<td>&nbsp;
   	<br />
   	
   	</td>
   	<td valign="top">	<h4><strong><center><?php echo $school_name; ?></center></strong></h4>
   	
   	<center><?php echo $address; ?></center>
   	
 
   	</td>
   	
   </tr>
   
   </table>	
   	
   	
   
   	
   	
   
   	
   	
   	
   
<table  class="table-responsive">

<tr > 
<td style="border:1px solid #CCC; text-align:center;"><?php echo nl2br($bank_details);?>
 
 <td>
<center><strong> Due Date:

</strong><?php foreach($rows as $rr){	
   echo 	$rr->due_date;	
} ?>
	
</center>

</td>
 
 </tr>
 
<tr style="text-align:center"><td>
Payable at any SCB Branch


</td></tr>


<tr><td>Chalan #</td><td><?php foreach($rows as $rr){
	
echo 	$rr->chalan_num;
	
} ?></td></tr>
<tr><td>Date</td><td>
	<?php foreach($rows as $rr){
	
	 	$timestamp=$rr->date;
$timestamp1=strtotime($timestamp);
echo date('d/m/Y',$timestamp1);
	
} ?>
	
	
</td></tr>
<tr><td>Billing Month</td><td>
<?php foreach($rows as $rr){
	
echo 	$rr->chalan_month.' ';
echo 	$rr->chalan_year;
	
} ?>
</td></tr>

 </tr>
<tr  style="text-align:center"><td>
TCS DHA campus RWp


</td></tr>

<tr><td>Roll #</td><td><?php foreach($rows as $rr){
	
echo 	$rr->roll;
	
} ?></td></tr>
<tr><td>Name</td><td><?php foreach($rows as $rr){
	
echo 	$rr->name;
	
} ?></td></tr>
<tr><td>Class</td><td><?php foreach($rows as $rr){
	
echo 	$rr->class_name;
	
} ?></td></tr>

<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>amount</th>


<tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan1;
	
} ?></td><td>
	<?php foreach($rows as $rr){
	
echo 	$rr->amount1;
	
} ?>
	
</td>
</tr><tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan2;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount2;
	
} ?></td>
</tr><tr>
<td><?php foreach($rows as $rr){
	
echo 	$rr->chalan3;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount3;
	
} ?></td>
</tr>


<tr>
<td ><?php foreach($rows as $rr){
	
echo 	$rr->chalan4;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount4;
	
} ?></td>
</tr>
<tr>
<td ><?php foreach($rows as $rr){
	
echo 	$rr->chalan5;
	
} ?></td><td><?php foreach($rows as $rr){
	
echo 	$rr->amount5;
	
} ?></td>
</tr>
<tr>
<td >fee Concession</td><td><?php foreach($rows as $rr){
	
echo 	$rr->discount;
	
} ?></td>
</tr>
<tr>
<th > Total</th><th><?php foreach($rows as $rr){
	
echo 	$rr->total_amount;
	
} ?></th>
</tr>





<tr><td> late payment surcharde @ Rs 15. </td></tr>

<tr><td> total payment after due date </td> <td><?php foreach($rows as $rr){
	
echo 	$rr->total_amount+15;
	
} ?></td></tr>




<tr><th>payment terms</th></tr>


<tr style="font-size:18; font-weight:bold; text-align:center; "><td style="border: 1px solid #CCC;"> Student Copy </td></tr>


</table>
<table height="100">
<tr>
		
		<td><h4><strong>Payment Terms:</strong></h4></td>
	</tr>
	<tr>
	<td>
		<?php 
		
		echo nl2br($terms);
		?>
</td>		
	</tr>

	
</table>

</div>
</div>
<br />

<input style="display: inline; margin-top: 20px;" type="button" class="btn btn-info" onclick="printDiv('printableArea')" value="Click To Print" />

<div style="display: inline;"><a href="<?php echo base_url();?>accountant/get_pending_chalan" style="margin-top: 20px;" type="button" class="btn btn-info">back to listing</a></div>



        </div>
        
        <script>
	
	
	$(document).ready(function() {

    var strNewString = $('div').html().replace(/\%20/g,'');

    $('div').html(strNewString);

});

	function myFunction() {
    window.print();
}
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}	
	
	
	
</script>