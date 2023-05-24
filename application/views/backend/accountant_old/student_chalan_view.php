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

<div class="row">
<?php 

$query_res =$this->db->query("SELECT 
s.name as student_name,
s.barcode_image,
s.sex as gender,
s.section_id,
sp.p_name as parent_name,
cs.title as section_nme,
cc.name as class_name,
dd.title as department_name

FROM student s 
inner join student_relation sr on sr.student_id=s.student_id 
inner join student_parent sp on sp.s_p_id=sr.s_p_id 
inner join class_section cs on s.section_id=cs.section_id  
inner join class cc on cc.class_id=cs.class_id
 inner join departments dd on dd.departments_id=cc.departments_id
where s.student_id=$student_id and sr.relation='f'")->result_array();


$student_name=$query_res[0]['student_name'];
$barcode_image=$query_res[0]['barcode_image'];
$parent_name=$query_res[0]['parent_name'];
$section_nme=$query_res[0]['section_nme'];
$class_name=$query_res[0]['class_name'];
$gender=$query_res[0]['gender'];
$section_id=$query_res[0]['section_id'];

$department_name=$query_res[0]['department_name'];
$copy_ary=array(1=>'Bank Bopy',2=>'College Copy',3=>'Student Copy');

for($i=1; $i<=3; $i++ ){
	




 ?>

<div class="col-md-4 col-lg-4 col-sm-4 border_div">

<?php 
$school_id=$_SESSION['school_id'];
$chalan_setting=$this->db->query("select * from chalan_settings where school_id=$school_id")->result_array();
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
<td class="text_style border_bottom border_right " ><?php echo $copy_ary[$i]; ?></td>
<td class="text_style border_bottom " >srn no</td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"><?php  
 echo $chalan_setting[0]['bank_details']; ?></td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"> Department - class -  Section</td>
</tr>
<tr>
<td class="text_style border_bottom" colspan="2"> 
<?php echo $department_name.'-'.$class_name.'-'.$section_nme; ?> 
</td>
</tr>
<tr>
<td class="text_style border_bottom  border_right" colspan="2">

<?php

if($gender="male"){
	
	$relation="S/O";
	
}else{
	$relation="D/O";
}
echo $student_name.' '.$relation.' '.$parent_name;

?></td>

</tr>
<tr>
<td class="text_style border_bottom" colspan="2">Issue Date : <?php echo $cur_date=date("Y-m-d") ; ?>
	/
	Due Date : 
	
	<?php echo date('Y-m-d', strtotime($cur_date.'+10 days')); ?>
	
</td>

</tr>
<table width="100%" >

<tr>
<td class="text_style  border_bottom border_right" width="7%">s.n</td>
<td class="text_style  border_bottom border_right" width="72%">particulars</td>
<td class="text_style  border_bottom" width="20px">Amount</td>
</tr>
     
            
<?php
	$this->load->helper("num_word");  
$query_rec=$this->db->query("SELECT ft.title, ccfe.order_num,ccfe.value
FROM  fee_types
 ft inner join class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id inner join class_chalan_form ccf on
 ccf.c_c_f_id= ccfe.c_c_f_id
 
 
 where ccf.section_id=$section_id and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id     ORDER BY ccfe.order_num")->result_array();
 
 
			$totle=0;
			$count_num=1;
			foreach($query_rec as $rec_row){
				
			echo "<tr>";
		
			 ?>	
			    <td class="text_style border_bottom  border_right" width="7%" ><?php echo $count_num; $count_num++; ?></td> 
			 
             <td class="text_style border_bottom  border_right" width="72%"><?php echo $rec_row['title']; ?></td> 
             <td class="text_style border_bottom" width="20%"><?php echo $rec_row['value']; 
             
           $totle=$rec_row['value']+$totle;
             
             ?></td> 
             
             
            <?php
             echo "</tr>";
             } 
            ?>        
            
            
            
            
            
            
            
            
            
            
            
                   
<?php
$query_rec=$this->db->query("SELECT 
dl.title,ccd.value,ccd.order_num
FROM discount_list dl inner join class_chalan_discount ccd on ccd.discount_id=dl.discount_id

inner join class_chalan_form ccf on
 ccf.c_c_f_id= ccd.c_c_f_id
 
 where ccf.section_id=$section_id and ccf.type=$form_type and ccf.status=1 and ccf.school_id=$school_id     ORDER BY ccd.order_num





")->result_array();
			
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
	<td class="text_style border_bottom  border_right">Total Amount</td>
	<td class="text_style border_bottom"> <?php echo $totle; ?></td>
	
	
</tr>

<tr>
	
	<td class="text_style " colspan="3"><strong> Rupees in Words : </strong> <?php echo convert_number_to_words($totle); ?>
		<br />
<strong>Note : </strong> After validity date rupees 200fine will be charged per day.
		
		
	</td>
	

</tr>


</table>
</table>
<br />


Principal Signature :__________________________
<br />
<br />


<img style="padding-left: 5px; " src="<?php echo  display_link($barcode_image,'student'); ?>">


</div>

<?php } ?>
</div>