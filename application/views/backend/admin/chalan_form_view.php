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

.back-link{
	clear:both;
	margin-top:15px;
	padding-left:0;
}


</style>

<div class="row">
<?php 

$copy_ary=array(1=>'Bank Copy',2=>'College Copy',3=>'Student Copy');
for($i=1; $i<=1; $i++ ){
	
 ?>

<div class="col-md-4 col-lg-4 col-sm-4 border_div">

<?php 
$school_id=$_SESSION['school_id'];
$chalan_setting=$this->db->query("select * from ".get_school_db().".chalan_settings where school_id=$school_id")->result_array();

?>





<table  width="100%" >








<tr>
<td class="text_style border_bottom " colspan="2" >
	<table>
		<tr>
		<td class="text_style" ><img height="70" width="70" src="<?php echo display_link($chalan_setting[0]['logo'],'') ?>" /></td>

<td class="text_style " class="heading_s" ><strong><?php  
 echo $chalan_setting[0]['school_name']; ?></strong></td>
		</tr>
	</table>	
</td>
</tr>



<tr>
<td class="text_style border_bottom" colspan="2"><?php  
 echo $chalan_setting[0]['bank_details']; ?></td>
</tr>





<tr>

<td class="text_style border_bottom border_right " ><?php echo get_phrase('chalan'); ?>#</td>
<td class="text_style border_bottom border_right " ><?php echo $copy_ary[$i]; ?></td>
</tr>






<tr>

<td class="text_style border_bottom border_right " ><?php echo get_phrase('roll'); ?>#</td>
<td class="text_style border_bottom border_right " ></td>
</tr>



<tr>

<td class="text_style border_bottom border_right " ><?php echo get_phrase('name'); ?></td>
<td class="text_style border_bottom border_right " ></td>
</tr>






<tr>

<td class="text_style border_bottom border_right " ><?php echo get_phrase('issue_date'); ?></td>
<td class="text_style border_bottom border_right " ></td>
</tr>




<tr>

<td class="text_style border_bottom border_right " ><?php echo get_phrase('due_date'); ?></td>
<td class="text_style border_bottom border_right " ></td>
</tr>







<table width="100%" >

<tr>
<td class="text_style  border_bottom border_right" width="7%"><?php echo get_phrase('#'); ?></td>
<td class="text_style  border_bottom border_right" width="72%"><?php echo get_phrase('particulars'); ?></td>
<td class="text_style  border_bottom" width="20px"><?php echo get_phrase('amount'); ?></td>
</tr>
     
            
<?php
	$this->load->helper("num_word");  
$query_rec=$this->db->query("SELECT ft.title, ccfe.order_num,ccfe.value
FROM ".get_school_db().".fee_types
 ft inner join ".get_school_db().".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id where ccfe.c_c_f_id=$c_c_f_id ORDER BY ccfe.order_num")->result_array();
			$totle=0;
			$count_num=1;
			foreach($query_rec as $rec_row){
				
			echo "<tr>";
		
			 ?>	
			 <td class="text_style border_bottom  border_right" width="7%" ><?php echo $count_num; $count_num++; ?></td> 
			 
             <td class="text_style border_bottom  border_right" width="72%"><?php echo $rec_row['title']; ?></td> 
             <td class="text_style border_bottom" width="20%"><?php echo  $rec_row['value']; 
             
           $totle=$rec_row['value']+$totle;
             
             ?></td> 
             
             
            <?php
             echo "</tr>";
             } 
            ?>        
                       
<?php
$query_rec=$this->db->query("SELECT 
dl.title,ccd.value,ccd.order_num
FROM ".get_school_db().".discount_list dl inner join ".get_school_db().".class_chalan_discount ccd on ccd.discount_id=dl.discount_id  where ccd.c_c_f_id=$c_c_f_id ORDER BY ccd.order_num")->result_array();
			
			foreach($query_rec as $rec_row){
			echo "<tr>";	
			
		
			 ?>	
             <td class="text_style border_bottom border_right" ><?php echo $count_num; $count_num++; ?></td> 
             <td class="text_style border_bottom border_right " ><?php echo $rec_row['title']; ?></td> 
             <td class="text_style border_bottom"  ><?php echo $rec_row['value']; 
             
             $totle=$totle-$rec_row['value'];
             
             ?></td> 
             
<?php
echo "</tr>";

 } ?> 
            
<tr>
	<td class="text_style border_bottom  border_right"></td>
	<td class="text_style border_bottom  border_right"><?php echo get_phrase('total_ammount'); ?></td>
	<td class="text_style border_bottom"> <?php echo $totle; ?></td>
	
	
</tr>

<tr>
	
	<td class="text_style " colspan="3"><strong><?php echo get_phrase('in_words'); ?> : </strong> <?php echo convert_number_to_words($totle); ?>
	

		
		
	</td>
	

</tr>


</table>
</table>



<?php 

	
echo $chalan_setting[0]['terms']; echo "<br>";
	?>


 <span><?php echo get_phrase('issued_by'); ?>: </span>

<br />
		<p style="font-size: 8px !important; font-weight:bold; border-top:1px solid #000; margin-top:10px;">
		<?php echo get_phrase('note'); ?>: <?php echo get_phrase('this_challan_form_is_computer_generated_and_does_not_require_any_signature'); ?>. </p>

</div>

<?php } ?>
<a class="col-md-4 col-lg-4 col-sm-4 back-link"  href="<?php echo base_url();?>class_chalan_form/class_chalan_f"><?php echo get_phrase('back_to_list'); ?></a>
</div>