<span id="testing"></span>
<table class="table table-bordered datatable" id="table_export">
    <thead>
        <tr>
            <th><div><?php echo get_phrase('roll');?></div></th>
            <th><div><?php echo get_phrase('student');?></div></th>
            <th><div><?php echo get_phrase('parent');?></div></th>
            
            <th><div><?php echo get_phrase('options');?></div></th>
            <th><div><?php echo get_phrase('status');?></div></th>
        </tr>
    </thead>
    <tbody>
    <style>
    	.colred{
			color: red;
		}
    	.colblue{
			color: blue;
		}
		.colgreen{
			color: green;
		}
    	
    </style>
    
    
        <?php 
        foreach($students as $row):

        ?>
        
        <tr>
        <td class="span1">
            <?php echo $row['roll'];?></td>
            <td>
            <?php echo $row['name'];?></td>
			<td><?php 
     $stu_id= $row['student_id'];
 $parent_arr=$this->db->query("select * from ".get_school_db().".student_relation sr inner join ".get_school_db().".student_parent sp on sr.s_p_id=sp.s_p_id
where sr.school_id=".$_SESSION['school_id']."
 AND sr.student_id=$stu_id")->result_array();
    foreach($parent_arr as $rr){
    	?>
    	<i class="fa fa-user colgreen"></i>
<span><?php echo parent_h($rr['relation']); ?></span>
<span><?php echo $rr['p_name']." "; ?></span>
<i class="fa fa-phone colblue"></i>

<span><?php echo $rr['contact']; ?></span>
		
<?php echo "<br />";	}
    
  ?>
  	

  	
  	
  	
  	
  </td>

            <td>
            
                <div class="btn-group">
               

	<a class="
btn btn-primary" href="<?php echo base_url(); ?>admin/add_parent/<?php echo $stu_id.'/'.$row['section_id'];  ?>"><?php echo get_phrase('manage_account');?></a>
</div>
</td>
<?php if($rr['account_status']==0){
$account_status=1;	
}
else{
$account_status=0;
}
?>
<td>
<?php  if($row['parent_id']==0){}else{ ?>

<div id="get_href" class="
<?php 
if($rr['account_status']==0){
	echo 'btn btn-primary';
}else{
	echo 'btn btn-danger';
}

?>" href="<?php echo base_url(); ?>admin/change_status/<?php echo $account_status.'/s_p_id/'.$rr['s_p_id'].'/student_parent/' ?>"
   onclick="change_status()"  ><?php 

if($rr['account_status']==0){
	echo get_phrase('active');
}else{
	echo get_phrase('inactive');
}

?></div>

<?php } ?>

</td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<script>
function change_status(){
var abc=$('#get_href').attr('href');	
	$("#loading").remove();
$("#table").html("<div id='loading' class='loader'></div>");
	
	$.ajax({
			type: 'GET',
			
			 url:abc,
			dataType: "html",
			success: function(response) {
			
			get_data();
			
			}
		});
	
	

}
	
	
	
	
	
</script>            		
<!-----  DATA TABLE EXPORT CONFIGURATIONS -----> 