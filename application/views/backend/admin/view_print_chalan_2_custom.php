<div class="row">
<?php

$url_r=$_SERVER['HTTP_REFERER'];
//$vertical_url = base_url()."monthly_fee/view_print_chalan_class/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);
$horizantal_url = base_url()."class_chalan_form/view_print_chalan/".$this->uri->segment(3)."/".$this->uri->segment(4)."/".$this->uri->segment(5);

$this->uri->segment(2);
$flag_h_v = false;
$disable_h_v = "";

if($this->uri->segment(2) == "view_print_chalan_2") {
    $flag_h_v = true;
    // $disable_h_v = "class = disable_h__v";
}

?>

	<div id="print_btn" class="btn btn-primary">
    <?php echo get_phrase('custom_print');?>
    </div>
    <a href="<?php echo $url_r; ?>" class="btn btn-primary"><?php echo get_phrase('back_to_listing');?></a>





    <a href="<?php echo $horizantal_url; ?>"> <i class="fa fa-bar-chart"aria-hidden="true"></i> </a>
    <?php if($flag_h_v)
    { ?>
        <i class="fa fa-bars disable_h__v" aria-hidden="true"></i>
        <?php
    }else
    { ?>
        <a href="<?php echo $horizantal_url; ?>" class="disable_h__v">  <i class="fa fa-bar-chart" aria-hidden="true"></i></i></a>
        <?php
    }
    ?>



</div>

<div class="row" id="print_form">


	<style>

        .disable_h__v
        {
            border: 1px solid #ff3a26;
            font-size:20px;

        }

			td {
			vertical-align: middle;
			padding: 2px 5px 2px 5px;
			;
		}
		/*tr:hover {background-color: #f5f5f5; cursor: pointer;}
	tr:nth-child(even) { background-color: #F1F1F1;}*/

		.h4,
		h4 {
			font-size: 12px !important;
			font-weight: bold;
		}

		table {
			/*    border:solid #000 !important;
    border-width:1px 0 0 1px !important;*/
		}

		th,
		td {
			/* border:solid #000 !important;
    border-width:0 1px 1px 0 !important;*/
		}

		@media print {
			a {
				display: none !important;
			}
			table {
				/*    border:0px solid #000 !important;
    border-width:1px 0 0 1px !important;*/
				border-collapse: collapse;
				border-spacing: 0px;
			}
			th,
			td {
				border: 1px solid #c3c3c3 !important;
				/*   border-width:0 1px 1px 0 !important;*/
				padding: 5px;
				font-size: 10px;
				font-family: arial;
				border-style: dotted;
			}
			@page {
				size: auto;
				/* auto is the initial value */
				margin: 0;
				/* this affects the margin in the printer settings */
			}
		}
	</style>



	<?php



if(count($query_ary)==0){
$this->session->set_flashdata('club_updated','Chalan Form Not Available');
redirect($_SERVER['HTTP_REFERER']);
exit;

}
foreach($query_ary as $row_data)
{


$s_c_f_id= 16; $row_data['s_c_f_id'];
$query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();

if($row_data['status']>=4){
$copy = array(1=>"Parent Copy",2=>"Head Office Copy",3=>"School Copy", 4=>"Bank Copy");
for($i=1; $i<=4; $i++){
?>
<div style="
    float: none;
    margin-left: 0px;
    overflow: hidden;
    height: 280px;
    min-height:280px;
  border: 1px solid #eee;
    margin-bottom:5px;">

        <table class="table-bordered" style="width:100%; font-size:10px;">
        	<thead>
            	<tr>
                	<th colspan="10" style="background-color:#ffffff;">
                   <span style="border-right:none !important; float:left;">
                        <img src="<?php echo display_link($row_data['school_logo'],'') ?>" style="height: 40px;
        width:40px;">
                    </span>
                    <span style="border-left:none !important; padding-top:10px;">
                        <h4 align="center">
                            <?php echo $row_data['school_name'];?>
                        </h4>
                    </span>
                    <span style="float:right; margin-top:-20px;">
                        <img src="<?php echo  display_link($row_data['bar_code'],'student'); ?>">
                    </span>
                    </th>
                </tr>
                <tr>
                    <th><?php echo get_phrase('chalan'); ?>#:<?php echo $row_data['chalan_form_number'];?></th>
                    <th><?php echo get_phrase('roll_no');?></th>
                    <th><?php echo get_phrase('name');?></th>
                    <th><?php echo get_phrase('department');?></th>
                    <th><?php echo get_phrase('class');?> - <?php echo get_phrase('section');?></th>
                    <th><?php echo get_phrase('issue_date');?></th>
                    <th><?php echo get_phrase('');?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $copy[$i]; ?> </td>
                    <td><?php $student_roll = $row_data[ 'student_roll' ].'213154';
                        echo $student_roll; ?>
                    </td>
                    <td><?php $student_name = $row_data[ 'student_name' ];
                        echo $student_name;	?>
                    </td>
                    <td><?php echo $row_data['department']; ?></td>
                    <td><?php echo $row_data['class'].' - '.$row_data['section'] ?></td>
                    <td><?php echo $issue_date= $row_data['issue_date'];?></td>
                    <td><?php echo $due_date= $row_data['due_date']; ?></td>
                </tr>
                <tr>
                    <td colspan="8">
<?php


//$count_num=1;


$s_c_f_id=$row_data['s_c_f_id'];


$query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();


$chalan="";
$discount="";
$totle=0;


			foreach($query_a as $rec_row1){

if($rec_row1['type']==1){


/*
	$chalan=$chalan.'<tr><td >'.$count_num.'</td>
	<td>'.$rec_row1['fee_type_title'].'</td>
	<td  style=" text-align:right;" >'.$rec_row1['amount'].'</td>
	<tr>';
	*/
	$chalan=$chalan.'<div style="display:inline-block; border-right:1px solid #eeeeee; padding:0px 5px;">
                            <p class="title" style="font-weight:bold; font-size:10px !important; margin-bottom:5px;">'.$rec_row1['fee_type_title'].'</p>
                            <span>'.$rec_row1['amount'].'</span>
                        </div>';


$totle=$rec_row1['amount']+$totle;

}else{
	/*
	$discount=$discount.'<tr>
	<td >'.$count_num.'</td>
	<td>'.$rec_row1['fee_type_title'].'</td>
	<td  style=" text-align:right;" >'.$rec_row1['amount'].'</td>
	</tr>';
	*/
	$discount=$discount.'<div style="display:inline-block; border-right:1px solid #eeeeee; padding:0px 5px;">
                            <p class="title" style="font-weight:bold; font-size:10px !important; margin-bottom:5px;">'.$rec_row1['fee_type_title'].'</p>
                            <span>'.$rec_row1['amount'].'</span>
                        </div>';

	$totle=$totle-$rec_row1['amount'];

}



//$count_num++;
 }






echo $chalan;
echo "<br>";
echo $discount;


?>

                    </td>
                </tr>
            </tbody>
		</table>

    	<table class="table-bordered" cellpadding="2" border="1" style="border-top: 0px #FFF !important; font-size:10px; width:100%;">

<?php
$this->load->helper("num_word");
  echo '<tr>
<td colspan="2"  ><strong>Total Amount = '.$totle.' Rs ('.convert_number_to_words($totle).' Rupees) </td>
	
</tr>' ;



echo '<tr>
	
<td style="border-bottom: none !important; height:31px; line-height:9px; font-size:8px; display:block; overflow:hidden;  text-transform:capitalize;"  colspan="3">
'.
nl2br($row_data ['school_terms']).
nl2br($row_data ['school_terms']).
'
	

	</td>
</tr>';


?>




			<tr>
				<td colspan="3" style="border-top:none !important; border-bottom:none !important;">



					<?php  $admin_req1=get_user_info($row_data['issued_by']);  ?>


					<p style="margin-bottom:2px; font-size:10px !important; font-weight:bold;"><?php echo get_phrase('issued_by'); ?>: <?php echo  $admin_req1[0]['name']; ?></p>
					<p style="font-size:8px !important; padding-top:2px; font-weight:bold; border-top:1px solid #000; margin:0px;"><?php echo get_phrase('note'); ?>: <?php echo get_phrase('this_challan_form_is_computer_generated_and_does_not_require_any_signature'); ?> . </p>

				</td>
			</tr>
		</table>


		<!--<table class="table-bordered" cellpadding="2" border="1" style=" width:100%; border-bottom:0px solid #FFF; margin-top:10px;">


<tr>
				<td style="border-right:none !important; width: 100px; ">
					<img src="<?php //echo display_link($row_data['school_logo'],'') ?>" style="height: 65px;
    width:70px;">
				</td>
				<td colspan="2" style="border-left:none !important; padding-top:10px; ">
					<h4 align="center">
						<?php //echo $row_data['school_name'];?>
					</h4>
				</td>

			</tr>



		<tr>
				<td colspan="3">
					<?php //echo $row_data['school_bank_detail']; ?>
				</td>

			</tr>




			<tr>

				<td>Chalan#:
					<?php //echo $row_data['chalan_form_number'];  ?>
				</td>
				<td style="border-left:none !important;">
					<span>
						<?php //echo $copy[$i]; ?>
					</span>
				</td>
			</tr>









			<tr>

				<td>Roll#</td>

				<td colspan="2">
					<?php
					//$student_roll = $row_data[ 'student_roll' ];


					//echo $student_roll;

					?>
				</td>

			</tr>





			<tr>

				<td>Name</td>

				<td colspan="2">
					<?php
					//$student_name = $row_data[ 'student_name' ];


					//echo $student_name;

					?>
				</td>

			</tr>







			<tr>
				<td>Department</td>


				<td colspan="3">
					<?php //echo$row_data['department']; ?>
				</td>
			</tr>






			<tr>
				<td>Class - Section</td>


				<td colspan="3">
					<?php //echo $row_data['class'].' - '.$row_data['section'] ?>
				</td>
			</tr>










			<tr>
				<td>Issue Date:
				</td>
				<td colspan="2">
					<?php // echo $issue_date= $row_data['issue_date'];
?>
				</td>

			</tr>

			<tr>
				<td>
					Due Date :
				</td>
				<td colspan="2">


					<?php //echo $due_date= $row_data['due_date'];
	?>

				</td>

			</tr>



		</table>


		<table class="table-bordered" cellpadding="2" border="1" style="border-top: 0px #FFF !important; width:100%;">


			<tr>
				<td>S#</td>
				<td width="65%">Particulars</td>
				<td  style=" text-align:right;">Amount</td>
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



	$chalan=$chalan.'<tr><td >'.$count_num.'</td>
	<td>'.$rec_row1['fee_type_title'].'</td>
	<td  style=" text-align:right;" >'.$rec_row1['amount'].'</td>
	<tr>';



$totle=$rec_row1['amount']+$totle;

}else{

	$discount=$discount.'<tr>
	<td >'.$count_num.'</td>
	<td>'.$rec_row1['fee_type_title'].'</td>
	<td  style=" text-align:right;" >'.$rec_row1['amount'].'</td>
	</tr>';

	$totle=$totle-$rec_row1['amount'];

}



$count_num++;
 }






echo $chalan;

echo $discount;


  echo '<tr>
<td colspan="2"  ><strong>Total Amount</strong></td>

<td  style=" text-align:right;" ><strong> '.$totle.'</strong></td>	
</tr>' ;

$this->load->helper("num_word");

echo '<tr>
	
<td style="border-bottom: none !important; text-transform:capitalize;" colspan="3"><strong>In Words: </strong>'.convert_number_to_words($totle).' Rupees<br />
'.nl2br($row_data ['school_terms']).'
	

	</td>
	

</tr>';


?>




			<tr>
				<td colspan="3" style="border-top:none !important;   border-bottom:none !important;  ">



					<?php  $admin_req1=get_user_info($row_data['issued_by']);  ?>


					<span>Issued By: <?php echo  $admin_req1[0]['name']; ?></span>




					<p style="font-size: 8px !important; font-weight:bold; border-top:1px solid #000; margin-top:10px;">Note: This Challan form is computer generated and does not require any signature. </p>

					<center>
						<img src="<?php echo  display_link($row_data['bar_code'],'student'); ?>">
					</center>






				</td>


			</tr>







		</table>
-->







	</div>


<?php }


}

else{



	$this->session->set_flashdata('club_updated','chalan is not approved');

	redirect($_SERVER['HTTP_REFERER']);



}



}
?>







</div>




	<!--<div id="print_btn2" class="btn btn-primary">Print</div>-->


<script>
	$( document ).ready( function () {
			$( '#print_btn' ).click( function () {
			var printContents = document.getElementById( 'print_form' ).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		} );
	} );
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
		0% {
			-webkit-transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
		}
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}

	@media print {
		a {
			display: none;
		}
	}

	.label {
		font-size: 10pt;
		font-weight: bold;
		font-family: Arial;
	}

	.contents {
		border: 1px dotted black;
		padding: 5px;
		width: 300px;
	}

	.name {
		color: #18B5F0;
	}

	.left {
		float: left;
		width: 0px;
		height: 0px;
	}

	.right {
		margin-left: 0px;
		line-height: 0px;
	}

	.clear {
		clear: both;
	}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
	$( function () {
		$( "#btnPrint" ).click( function () {
			var contents = $( "#print_form" ).html();
			var frame1 = $( '<iframe />');
			frame1[ 0 ].name = "frame1";
			frame1.css( {
				"position": "absolute",
				"top": "-1000000px"
			} );
			$( "body" ).append( frame1 );
			var frameDoc = frame1[ 0 ].contentWindow ? frame1[ 0 ].contentWindow : frame1[ 0 ].contentDocument.document ? frame1[ 0 ].contentDocument.document : frame1[ 0 ].contentDocument;
			frameDoc.document.open();
			//Create a new HTML document.
			frameDoc.document.write( '<html><head><title><?php echo get_phrase('DIV_contents'); ?></title>');
			frameDoc.document.write( '</head><body>');
			//Append the external CSS file.
			// frameDoc.document.write('<link href="style.css" rel="stylesheet" type="text/css" />');
			//Append the DIV contents.
			frameDoc.document.write( contents );
			frameDoc.document.write( '</body></html>');
			frameDoc.document.close();
			setTimeout( function () {
				window.frames[ "frame1" ].focus();
				window.frames[ "frame1" ].print();
				frame1.remove();
			}, 500 );
		} );
	} );
</script>