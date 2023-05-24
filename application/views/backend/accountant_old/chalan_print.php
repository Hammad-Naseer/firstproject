<!--<?php print_r($page_data); ?>-->
<!--<?php print_r($back_data); ?>-->

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



<style>
	.boarder{
	border: 1px solid #f2f2f2;
  height: 34px;
	
	}
	
	.table th, .table td { 
     border-top: none !important; 
 }
.modal-backdrop {
   
    z-index: 0 !important;
}
.centertxt{
	text-align: center;
}
</style>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Challan');?>
            	</div>
            </div>
			<div class="panel-body">

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
<td style="border:1px solid #CCC; text-align:center;"> 
<?php echo nl2br($bank_details); ?>

</td> 

<td>
<center><strong> Due Date:

</strong>
 <?php echo  $page_data['due_date'];  ?>	

	
</center>

</td>


 </tr>
 
<tr style="text-align:center"><td>&nbsp;</td></tr>


<tr><td>Chalan#</td><td><?php echo $page_data['chalan_num'] ?></td></tr>
<tr><td>Date</td><td><?php echo $dateee = date('d/m/Y'); ?></td></tr>
<tr><td>Billing Month</td><td><?php echo  $page_data['chalan_month'];  ?> <?php echo  $page_data['chalan_year'];  ?></td></tr>

 </tr>
<tr  style="text-align:center"><td>



</td></tr>

<tr><td>Roll #</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->roll;
	
} ?></td></tr>
<tr><td>Name</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->name;
	
} ?></td></tr>
<tr><td>Class</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->class_name;
	
} ?></td></tr>










<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>Amount</th>


<tr>
<td><?php echo $page_data['chalan1']; ?></td><td><?php echo $page_data['amount1']; ?></td>
</tr><tr>
<td><?php echo $page_data['chalan2']; ?></td><td><?php echo $page_data['amount2']; ?></td>
</tr><tr>
<td><?php echo $page_data['chalan3']; ?></td><td><?php echo $page_data['amount3']; ?></td>
</tr>


<tr>
<td ><?php echo $page_data['chalan4']; ?></td><td><?php echo $page_data['amount4']; ?></td>
</tr>
<tr>
<td ><?php echo $page_data['chalan5']; ?></td><td><?php echo $page_data['amount5']; ?></td>
</tr>
<tr>
<td >Fee Concession</td><td><?php echo $page_data['discount']; ?></td>
</tr>
<tr>
<th > Total</th><th><?php echo $page_data['total_amount']; ?></th>
</tr>





<tr><td>Late Payment surcharde @ Rs 15. </td></tr>

<tr><td>Total Payment after due date </td> <td><?php echo $page_data['total_amount']+15; ?></td></tr>




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
<td style="border:1px solid #CCC; text-align:center;"> <?php echo nl2br($bank_details); ?></td> 

<td>
<center><strong> Due Date:

</strong>
 <?php echo  $page_data['due_date'];  ?>	

	
</center>

</td>


 </tr>
 
<tr style="text-align:center"><td>&nbsp;</td></tr>


<tr><td>Chalan#</td><td><?php echo $page_data['chalan_num'] ?></td></tr>
<tr><td>Date</td><td><?php echo $dateee = date('d/m/Y'); ?></td></tr>
<tr><td>Billing Month</td><td><?php echo  $page_data['chalan_month'];  ?> <?php echo  $page_data['chalan_year'];  ?></td></tr>

 </tr>
<tr  style="text-align:center"><td>



</td></tr>

<tr><td>Roll #</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->roll;
	
} ?></td></tr>
<tr><td>Name</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->name;
	
} ?></td></tr>
<tr><td>Class</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->class_name;
	
} ?></td></tr>










<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>Amount</th>


<tr>
<td><?php echo $page_data['chalan1']; ?></td><td><?php echo $page_data['amount1']; ?></td>
</tr><tr>
<td><?php echo $page_data['chalan2']; ?></td><td><?php echo $page_data['amount2']; ?></td>
</tr><tr>
<td><?php echo $page_data['chalan3']; ?></td><td><?php echo $page_data['amount3']; ?></td>
</tr>


<tr>
<td ><?php echo $page_data['chalan4']; ?></td><td><?php echo $page_data['amount4']; ?></td>
</tr>
<tr>
<td ><?php echo $page_data['chalan5']; ?></td><td><?php echo $page_data['amount5']; ?></td>
</tr>
<tr>
<td >Fee Concession</td><td><?php echo $page_data['discount']; ?></td>
</tr>
<tr>
<th > Total</th><th><?php echo $page_data['total_amount']; ?></th>
</tr>





<tr><td>Late Payment surcharde @ Rs 15. </td></tr>

<tr><td>Total Payment after due date </td> <td><?php echo $page_data['total_amount']+15; ?></td></tr>




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
<td style="border:1px solid #CCC; text-align:center;"> <?php echo nl2br($bank_details); ?></td> 

<td>
<center><strong> Due Date:

</strong>
 <?php echo  $page_data['due_date'];  ?>	

	
</center>

</td>


 </tr>
 
<tr style="text-align:center"><td>&nbsp;</td></tr>


<tr><td>Chalan#</td><td><?php echo $page_data['chalan_num'] ?></td></tr>
<tr><td>Date</td><td><?php echo $dateee = date('d/m/Y'); ?></td></tr>
<tr><td>Billing Month</td><td><?php echo  $page_data['chalan_month'];  ?> <?php echo  $page_data['chalan_year'];  ?></td></tr>

 </tr>
<tr  style="text-align:center"><td>



</td></tr>

<tr><td>Roll #</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->roll;
	
} ?></td></tr>
<tr><td>Name</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->name;
	
} ?></td></tr>
<tr><td>Class</td><td><?php foreach($back_data as $rr){
	
echo 	$rr->class_name;
	
} ?></td></tr>










<br>
<tr><td><br></td></tr>

<tr style="border-bottom:1px solid #ccc;">

<th>Description</th><th>Amount</th>


<tr>
<td><?php echo $page_data['chalan1']; ?></td><td><?php echo $page_data['amount1']; ?></td>
</tr><tr>
<td><?php echo $page_data['chalan2']; ?></td><td><?php echo $page_data['amount2']; ?></td>
</tr><tr>
<td><?php echo $page_data['chalan3']; ?></td><td><?php echo $page_data['amount3']; ?></td>
</tr>


<tr>
<td ><?php echo $page_data['chalan4']; ?></td><td><?php echo $page_data['amount4']; ?></td>
</tr>
<tr>
<td ><?php echo $page_data['chalan5']; ?></td><td><?php echo $page_data['amount5']; ?></td>
</tr>
<tr>
<td >Fee Concession</td><td><?php echo $page_data['discount']; ?></td>
</tr>
<tr>
<th > Total</th><th><?php echo $page_data['total_amount']; ?></th>
</tr>





<tr><td>Late Payment surcharde @ Rs 15. </td></tr>

<tr><td>Total Payment after due date </td> <td><?php echo $page_data['total_amount']+15; ?></td></tr>




<tr><th>&nbsp;</th></tr>


<tr style="font-size:18; font-weight:bold; text-align:center; "><td style="border: 1px solid #CCC;">Student Copy </td></tr>


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
</div>


<input type="button" class="btn btn-info" onclick="printDiv('printableArea')" value="Click To Print" />

        </div>
    </div>
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