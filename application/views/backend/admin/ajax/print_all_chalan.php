
<?php 



$s_c_f_id=$query_ary[0]['s_c_f_id'];


$query_a=$this->db->query("select * from ".get_school_db().".student_chalan_detail where s_c_f_id=$s_c_f_id and school_id=".$_SESSION['school_id'])->result_array();

if($query_ary[0]['status']>=4 ){
	



for($i=1; $i<=3; $i++){
	



?>
<div class="border_div" style="width: 33.33%; float: left;">
<table  width="100%" >
<tr>
<td class="text_style border_bottom " colspan="2" >
	<table>
		<tr>
		<td class="text_style" ><img height="70" width="70" src="<?php echo display_link($query_ary[0]['school_logo'],'') ?>" /></td>

<td class="text_style " class="heading_s" ><strong><?php  
 echo $query_ary[0]['school_name'];
 

 
  ?></strong></td>
		</tr>
	</table>
</td>
</tr>
<tr>
<td class="text_style border_bottom border_right " ><?php echo
get_phrase('copy_detail'); 
?></td>
<td class="text_style border_bottom " ><?php echo get_phrase('srn_no');?>: <?php echo $query_ary[0]['chalan_form_number'];  ?></td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"><?php  
 echo $query_ary[0]['school_bank_detail']; ?></td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"> <?php echo get_phrase('department');?> - <?php echo get_phrase('class');?> -  <?php echo get_phrase('section');?></td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"> 
<?php echo $query_ary[0]['section'].'-'.$query_ary[0]['class'].'-'.$query_ary[0]['department']; ?> 
</td>
</tr>
<tr>
<td class="text_style border_bottom  border_right" colspan="2">

<?php


$student_name=$query_ary[0]['student_name'];
$father_name=$query_ary[0]['father_name'];

echo get_phrase("father_name")." :".$student_name.' '.get_phrase('student_name').' '.$parent_name;

?></td>

</tr>
<tr>
<td class="text_style border_bottom" colspan="2"><?php echo get_phrase('issue_date');?> : <?php echo $issue_date= $query_ary[0]['issue_date']; 
?>
	/
	<?php echo get_phrase('due_date');?> : 
	
	<?php echo $due_date= $query_ary[0]['due_date']; 
	?>
	
</td>

</tr>
<table width="100%" >

<tr id="chalan" >
<td class="text_style  border_bottom border_right" width="7%"><?php echo get_phrase('s_n');?></td>
<td class="text_style  border_bottom border_right" width="72%"><?php echo get_phrase('particulars');?></td>
<td class="text_style  border_bottom" width="20px"><?php echo get_phrase('amount');?></td>
</tr>
     
<?php 


$count_num=1;


$s_c_f_id=$query_ary[0]['s_c_f_id'];


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




echo '<tr class="s_chalan"><td class="text_style border_bottom  border_right" width="7%" colspan="3" >'.get_phrase("chalan").'</td></tr>';

echo $chalan;
echo '<tr class="s_chalan"><td class="text_style border_bottom  border_right" width="7%" colspan="3" >'.get_phrase("discount").'</td></tr>';
echo $discount;


  echo '<tr class="s_chalan">

<td class="text_style border_bottom  border_right"></td>

<td class="text_style border_bottom border_right">'.get_phrase("total_amount").'</td>

<td class="text_style border_bottom"> '.$totle.'</td>	
</tr>' ;
             
$this->load->helper("num_word");

echo '<tr class="s_chalan">
	
<td class="text_style " colspan="3"><strong>'.get_phrase("rupees_in_words").' : </strong>'.convert_number_to_words($totle).get_phrase("rupees").' 
		<br />
<strong>'.get_phrase("note") .': </strong>'.get_phrase('after_validity_date_rupees_200_fine_will_be_charged_per_day').'
		
		
	</td>
	

</tr>';
	

?>

</table>
</table>
<br />

<?php echo get_phrase('principal_signature');?> :__________________________
<br />
<br />

<?php  $admin_req1=get_user_info($query_ary[0]['issued_by']);  ?>


 <span><?php echo get_phrase('issued_by');?>: <?php echo  $admin_req1[0]['name']; ?></span>

<br />

<img style="padding-left: 5px; " src="<?php echo  display_link($query_ary[0]['bar_code'],'student'); ?>">

</div>
<?php } 

}

else{
	
	
	
	$this->session->set_flashdata('club_updated',get_phrase('chalan_is_not_approved'));	
	
	redirect(base_url() . 'c_student/student_pending');
	
	
	
}
?>





