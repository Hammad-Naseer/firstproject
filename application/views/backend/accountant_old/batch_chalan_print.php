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
<input style="display: inline; margin-top: 20px;" type="button" class="btn btn-info" onclick="printDiv('printableArea')" value="Click To Print" />

<div style="display: inline;"><a href="<?php echo base_url();?>accountant/get_pending_chalan" style="margin-top: 20px;" type="button" class="btn btn-info">back to listing</a></div>
<div id="printableArea"  style="margin: 0px; ">
<style>
	
	@page {
    margin: 10px;
}
	
	
</style>
<?php 
    
    if($rows>0){
	foreach($rows as $rr){	
    ?>  
   	<div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #CCC; margin: 0px;" >
 
 
 
    <table  class="table-responsive">
   <tr>
   	<td><img height="100" width="100" src="<?php echo base_url();?>uploads/<?php echo $logo; ?>"/></td>
   	<td>
  
   	</td>
   	<td valign="top">	<h4><strong><center><?php echo $school_name; ?></center></strong></h4>
   	
   	<center><?php echo $address; ?></center>
   	
 
   	</td>
   	
   </tr>
   
   </table>	
   	
 
<table  class="table-responsive">
<tr > 
<td style="border:1px solid #CCC; text-align:center;">
	
	<?php echo nl2br($bank_details); ?>
	
	
</td> 
 
 <td>
<center><strong> Due Date:

</strong><?php 
   echo 	$rr->due_date;	
    ?>
	
</center>

</td>
 
 </tr>
 
<tr style="text-align:center"><td>&nbsp;</td></tr>


<tr><td>Chalan #</td><td><?php 
	
echo 	$rr->chalan_num;
	
 ?></td></tr>
<tr><td>Date</td><td>
	<?php 
	
	 	$timestamp=$rr->date;
$timestamp1=strtotime($timestamp);
echo date('d/m/Y',$timestamp1);
	 ?>
	
	
</td></tr>
<tr><td>Billing Month</td><td>
<?php 
	
echo 	$rr->chalan_month.' ';
echo 	$rr->chalan_year;
	
 ?>
</td></tr>

 </tr>
<tr  style="text-align:center"><td>&nbsp;



</td></tr>

<tr><td>Roll #</td><td><?php 
	
echo 	$rr->roll;
	
 ?></td></tr>
<tr><td>Name</td><td><?php 
	
echo 	$rr->name;
	
 ?></td></tr>
<tr><td>Class</td><td><?php 
	
echo 	$rr->class_name;
	
 ?></td></tr>

<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>Amount</th>


<tr>
<td><?php 
	
echo 	$rr->chalan1;
	
 ?></td><td>
	<?php 
	
echo 	$rr->amount1;
	
 ?>
	
</td>
</tr><tr>
<td><?php 
	
echo 	$rr->chalan2;
	
 ?></td><td><?php 
	
echo 	$rr->amount2;
	
 ?></td>
</tr><tr>
<td><?php 
	
echo 	$rr->chalan3;
	
 ?></td><td><?php 
	
echo 	$rr->amount3;
	
 ?></td>
</tr>


<tr>
<td ><?php 
	
echo 	$rr->chalan4;
	
 ?></td><td><?php 
	
echo 	$rr->amount4;
	
 ?></td>
</tr>
<tr>
<td ><?php 
	
echo 	$rr->chalan5;
	
 ?></td><td><?php 
	
echo 	$rr->amount5;
	
 ?></td>
</tr>
<tr>
<td >Fee Concession</td><td><?php 
	
echo 	$rr->discount;
	
 ?></td>
</tr>
<tr>
<th > Total</th><th><?php 
	
echo 	$rr->total_amount;
	
 ?></th>
</tr>





<tr><td><?php echo get_phrase('late_payment_surcharde_@_Rs__15'); ?>. </td></tr>

<tr><td><?php echo get_phrase('total_payment_after_due_date'); ?>  </td> <td><?php 
	
echo 	$rr->total_amount+15;
	
 ?></td></tr>




<tr><th>&nbsp;</th></tr>


<tr style="font-size:18; font-weight:bold; text-align:center; "><td style="border: 1px solid #CCC;"> Bank Copy </td></tr>


</table>


<table height="240">
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
		
  






<style>
	
	@page {
    margin: 10px;
}
	
	
</style>
<div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #CCC; margin: 0px;" >
 <table  class="table-responsive">
   <tr>
   	<td><img height="100" width="100" src="<?php echo base_url();?>uploads/<?php echo $logo; ?>"/></td>
   	<td>
   
   	</td>
   	<td valign="top">	<h4><strong><center><?php echo $school_name; ?></center></strong></h4>
   	
   	<center><?php echo $address; ?></center>
   	
 
   	</td>
   	
   </tr>
   
   </table>	
   	
 
 
 
<table  class="table-responsive">


<tr > 
<td style="border:1px solid #CCC; text-align:center;"> <?php echo nl2br($bank_details); ?></td> 
 
 <td>
<center><strong> Due Date:

</strong><?php 
   echo 	$rr->due_date;	
    ?>
	
</center>

</td>
 
 </tr>
 
<tr style="text-align:center"><td>&nbsp;



</td></tr>


<tr><td>Chalan #</td><td><?php 
	
echo 	$rr->chalan_num;
	
 ?></td></tr>
<tr><td>Date</td><td>
	<?php 
	
	 	$timestamp=$rr->date;
$timestamp1=strtotime($timestamp);
echo date('d/m/Y',$timestamp1);
	 ?>
	
	
</td></tr>
<tr><td>Billing Month</td><td>
<?php 
	
echo 	$rr->chalan_month.' ';
echo 	$rr->chalan_year;
	
 ?>
</td></tr>

 </tr>
<tr  style="text-align:center"><td>&nbsp;



</td></tr>

<tr><td>Roll #</td><td><?php 
	
echo 	$rr->roll;
	
 ?></td></tr>
<tr><td>Name</td><td><?php 
	
echo 	$rr->name;
	
 ?></td></tr>
<tr><td>Class</td><td><?php 
	
echo 	$rr->class_name;
	
 ?></td></tr>

<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>Amount</th>


<tr>
<td><?php 
	
echo 	$rr->chalan1;
	
 ?></td><td>
	<?php 
	
echo 	$rr->amount1;
	
 ?>
	
</td>
</tr><tr>
<td><?php 
	
echo 	$rr->chalan2;
	
 ?></td><td><?php 
	
echo 	$rr->amount2;
	
 ?></td>
</tr><tr>
<td><?php 
	
echo 	$rr->chalan3;
	
 ?></td><td><?php 
	
echo 	$rr->amount3;
	
 ?></td>
</tr>


<tr>
<td ><?php 
	
echo 	$rr->chalan4;
	
 ?></td><td><?php 
	
echo 	$rr->amount4;
	
 ?></td>
</tr>
<tr>
<td ><?php 
	
echo 	$rr->chalan5;
	
 ?></td><td><?php 
	
echo 	$rr->amount5;
	
 ?></td>
</tr>
<tr>
<td >Fee Concession</td><td><?php 
	
echo 	$rr->discount;
	
 ?></td>
</tr>
<tr>
<th ><?php echo get_phrase('total'); ?> </th><th><?php 
	
echo 	$rr->total_amount;
	
 ?></th>
</tr>





<tr><td><?php echo get_phrase('late_payment_surcharde_@_Rs_15'); ?> . </td></tr>

<tr><td><?php echo get_phrase('total_payment_after_due_date'); ?>  </td> <td><?php 
	
echo 	$rr->total_amount+15;
	
 ?></td></tr>




<tr><th>&nbsp;</th></tr>


<tr style="font-size:18; font-weight:bold; text-align:center; "><td style="border: 1px solid #CCC;"> Office Copy </td></tr>


</table>


<table height="240">
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
		
  
 	





<style>
	
	@page {
    margin: 10px;
}
	
	
</style>

 
   	
   	<div class="col-lg-4 col-md-4 col-sm-4" style="border-right: 1px solid #CCC; margin: 0px;" >
 
 
 
    <table  class="table-responsive">
   <tr>
   	<td><img height="100" width="100" src="<?php echo base_url();?>uploads/<?php echo $logo; ?>"/></td>
   	<td>
  
   	</td>
   	<td valign="top">	<h4><strong><center><?php echo $school_name; ?></center></strong></h4>
   	<center><?php echo $address; ?></center>
   	</td>
   	
   </tr>
   
   </table>	
   	
 
 
 
<table  class="table-responsive">


<tr > 
<td style="border:1px solid #CCC; text-align:center;"> <?php echo nl2br($bank_details); ?></td> 
 
 <td>
<center><strong> Due Date:

</strong><?php 
   echo 	$rr->due_date;	
    ?>
	
</center>

</td>
 
 </tr>
 
<tr style="text-align:center"><td>&nbsp;
</td></tr>


<tr><td>Chalan #</td><td><?php 
	
echo 	$rr->chalan_num;
	
 ?></td></tr>
<tr><td>Date</td><td>
	<?php 
	
	 	$timestamp=$rr->date;
$timestamp1=strtotime($timestamp);
echo date('d/m/Y',$timestamp1);
	 ?>
	
	
</td></tr>
<tr><td>Billing Month</td><td>
<?php 
	
echo 	$rr->chalan_month.' ';
echo 	$rr->chalan_year;
	
 ?>
</td></tr>

 </tr>
<tr  style="text-align:center"><td>&nbsp;



</td></tr>

<tr><td>Roll #</td><td><?php 
	
echo 	$rr->roll;
	
 ?></td></tr>
<tr><td>Name</td><td><?php 
	
echo 	$rr->name;
	
 ?></td></tr>
<tr><td>Class</td><td><?php 
	
echo 	$rr->class_name;
	
 ?></td></tr>

<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>Amount</th>


<tr>
<td><?php 
	
echo 	$rr->chalan1;
	
 ?></td><td>
	<?php 
	
echo 	$rr->amount1;
	
 ?>
	
</td>
</tr><tr>
<td><?php 
	
echo 	$rr->chalan2;
	
 ?></td><td><?php 
	
echo 	$rr->amount2;
	
 ?></td>
</tr><tr>
<td><?php 
	
echo 	$rr->chalan3;
	
 ?></td><td><?php 
	
echo 	$rr->amount3;
	
 ?></td>
</tr>


<tr>
<td ><?php 
	
echo 	$rr->chalan4;
	
 ?></td><td><?php 
	
echo 	$rr->amount4;
	
 ?></td>
</tr>
<tr>
<td ><?php 
	
echo 	$rr->chalan5;
	
 ?></td><td><?php 
	
echo 	$rr->amount5;
	
 ?></td>
</tr>
<tr>
<td >Fee Concession</td><td><?php 
	
echo 	$rr->discount;
	
 ?></td>
</tr>
<tr>
<th > Total</th><th><?php 
	
echo 	$rr->total_amount;
	
 ?></th>
</tr>





<tr><td> Late Payment surcharde @ Rs 15. </td></tr>

<tr><td> Total Payment after due date </td> <td><?php 
	
echo 	$rr->total_amount+15;
	
 ?></td></tr>




<tr><th>&nbsp;</th></tr>


<tr style="font-size:18; font-weight:bold; text-align:center; "><td style="border: 1px solid #CCC;"> Student Copy </td></tr>


</table>


<table height="240">
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
		
  
 	

<?php }
} ?>
</div>


<!-- end loop here  -->
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