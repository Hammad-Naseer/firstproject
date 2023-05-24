<style>
	
.mgt40{
	margin-top:40px !important
}
</style>
<?php  
        if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<script>
	$( window ).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>
<h3>
 <?php echo get_phrase('promotion_details');?>
</h3>
<a class="btn btn-primary" href="<?php echo base_url();?>promotion/promotion_listing"> <?php echo get_phrase('back_to_listing');?></a>

<?php
$p="SELECT count(scf.status) as status_count,scf.status,scf.bulk_req_id FROM ".get_school_db().".bulk_request br
INNER JOIN ".get_school_db().".student_chalan_form scf
ON br.bulk_req_id=scf.bulk_req_id
WHERE br.school_id=".$_SESSION['school_id']." 
AND scf.is_bulk=1 
and scf.is_cancelled = 0
AND br.bulk_req_id=$bulk_req_id 
GROUP By scf.status,scf.bulk_req_id 
ORDER BY scf.bulk_req_id ";
 $res=$this->db->query($p)->result_array();
 $array_status=array();
foreach($res as $result)
{
	$array_status[$result['bulk_req_id']][$result['status']]=$result['status_count'];
}


$query_promotion =$this->db->query("select 
br.bulk_req_id,
br.activity,
cs.title as section_name, 
css.title as pro_section_name,
ay.title as acadmic_year_name,
ayy.title as pro_acadmic_year_name,
cs.section_id as section_id,
css.section_id as pro_section_id

from ".get_school_db().".bulk_request br 
inner join ".get_school_db().".class_section cs on cs.section_id=br.section_id 
inner join ".get_school_db().".class_section css on css.section_id=br.pro_section_id 


INNER JOIN ".get_school_db().".acadmic_year ay on ay.academic_year_id=br.academic_year_id
INNER join ".get_school_db().".acadmic_year ayy ON ayy.academic_year_id=br.pro_academic_year_id
where br.school_id=".$_SESSION['school_id']." and br.status=1 and br.bulk_req_id=$bulk_req_id")->result_array();

$abc="";
if(count($query)>0){
	


$qur=$this->db->query("select d.title as department_name, c.name as class_name , cs.title as section_name from ".get_school_db().".student s 
inner join ".get_school_db().".class_section cs on cs.section_id=s.section_id
inner join ".get_school_db().".class c on c.class_id=cs.class_id
inner join ".get_school_db().".departments d on d.departments_id=d.departments_id
where s.section_id=(select pro_section_id from ".get_school_db().".student where student_id=".$query[0]['student_id'].")")->result_array();

 
 }else{
 	
 $abc = "No Record Found"; 	
 echo "<h3>$abc</h3>";	
 
 }
 
 ?>

            <div class="myttl"><?php
            $dept_array=section_hierarchy($query_promotion[0]['section_id']);
            
            echo $dept_array['d']." - ".$dept_array['c']." - ".$dept_array['s'];?></div>
             <div><strong>
              <?php echo get_phrase('academic_year');?>
             : </strong><?php echo $query_promotion[0]['acadmic_year_name'];?></div>
            
              <div><strong> <?php echo get_phrase('promotion_class');?>: </strong><?php 
              $dept_array2=section_hierarchy($query_promotion[0]['pro_section_id']);
            
            echo $dept_array2['d']." - ".$dept_array2['c']." - ".$dept_array2['s'];?></div>
              <div><strong> <?php echo get_phrase('promotion_academic_year');?>:</strong><?php echo $query_promotion[0]['pro_acadmic_year_name'];?> </div>
            
              <?php
if(isset($array_status[$query_promotion[0]['bulk_req_id']]))
{
	
	$Total=array_sum($array_status[$query_promotion[0]['bulk_req_id']]);
	echo "Total forms: ".$Total;
	$paid=0;
	if(isset($array_status[$query_promotion[0]['bulk_req_id']][5]))
	{
	echo " Paid: ".$paid=$array_status[$query_promotion[0]['bulk_req_id']][5];	
	}
	echo " Un-Paid: ".($Total-$array_status[$query_promotion[0]['bulk_req_id']][5]);
}
echo "<br>";

if(($query_promotion[0]['activity']<5) && ($paid==$Total) )
{
$activity['activity']=5;
$this->db->where("bulk_req_id",$query_promotion[0]['bulk_req_id']);
$this->db->update(get_school_db().".bulk_request",$activity);	
}
?>
<div><strong> <?php echo get_phrase('status');?>: </strong><?php echo promotion_class_status($query_promotion[0]['activity']);?>
              </div>





<table class="table table-bordered datatable  table-hover cursor" id="table_export">
    <thead>
        <tr>
              <th style="width:34px;" ><div><?php echo get_phrase('#');?></div></th>
    
            
                     <th><div><?php echo get_phrase('Detail');?></div></th>
  
                <th style="width:94px;" ><div><?php echo get_phrase('options');?></div></th>
        </tr>
    </thead>
    <tbody>
        <?php 
     
		$j=0;
                foreach($query as $row):
		
		
		$j++;
		
		
		
		
		?>
        <tr>
            <td><?php echo $j; ?></td>
            
            
            
          <td>
             
             <div class="myttl"><?php echo $row['student_name'];?><span style="font-size:12px;"> ( <?php echo get_phrase('roll');?>#:<?php  echo $row['roll'];?> )</span></div>
             <div><strong> <?php echo get_phrase('form');?>#:</strong> <?php  echo $row['chalan_form_number']
              ?></div>
             <div><?php echo $row['father_name'];
             $status=$row['student_challan_status'];
             $color="";
             
			 if($status==5)
			 {
			   	$color="green";
			  }
			  else
             {
			 	$color="red";
			 }
			  
             ?><span class="<?php echo $color;?>"><?php echo monthly_class_status($row['student_challan_status']);?></span></div>

             
             
             
             
             
             
             
             
             
           </td>  
           
            <td>   
                <div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                         <?php echo get_phrase('action');?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        <!-- STUDENT EDITING LINK -->
                        <li>
                            <a href="<?php echo base_url(); ?>class_chalan_form/edit_chalan_form/<?php echo $row['s_c_f_id'];  ?>/4/2/<?php echo $this->uri->segment(3); ?>">
                                <i class="entypo-pencil"></i>
                <?php
                 if($status==4)
                 {
				 	 echo get_phrase('Receive Chalan');
				 } 
				 else
				 {
				 	 echo get_phrase('View Chalan');
				 }                  
                                    ?>
                                </a>
                                        </li>            
                 
                        
                        
                        <?php if($row['status']<5){ ?>
                        <!-- STUDENT DELETION LINK -->
                        
                               <li class="divider"></li>
                        <li>
                        
                     
                            <a href="#" 
                            
                            onclick="confirm_modal('<?php echo base_url();?>promotion/cancel_monthly_chalan/<?php echo  $row['s_c_f_id']; ?>');">
                            
                            
                            
                            
                                <i class="entypo-trash"></i>
                              <?php echo get_phrase('cancel_chalan'); ?>
                                </a>
                        </li>
                    
                    <?php } ?>
                    
                    </ul>
                </div>
                
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

