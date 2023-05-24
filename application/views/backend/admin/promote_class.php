<?php 

 $school_id=$_SESSION['school_id'];
$p="SELECT count(scf.status) as status_count,scf.status,scf.bulk_req_id FROM ".get_school_db().".bulk_request br
INNER JOIN ".get_school_db().".student_chalan_form scf
ON br.bulk_req_id=scf.bulk_req_id
WHERE br.school_id=$school_id 
AND scf.is_bulk=1 
AND is_cancelled = 0
AND br.bulk_req_id=$param2 
GROUP By scf.status,scf.bulk_req_id 
ORDER BY scf.bulk_req_id ";
 $res=$this->db->query($p)->result_array();
 $array_status=array();
foreach($res as $result)
{
	$array_status[$result['bulk_req_id']][$result['status']]=$result['status_count'];
}



$qur_val =$this->db->query("select 
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
where br.school_id=$school_id and br.status=1 and br.bulk_req_id=$param2")->result_array();



$qur=$this->db->query("select due_days, DATE_ADD(CURDATE(), INTERVAL due_days DAY) as due_date from ".get_school_db().".student_chalan_form where school_id=$school_id and bulk_req_id=$param2 and is_cancelled = 0 limit 1")->result_array();




?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('promote');?>
					<br>
					<?php $dept_array=section_hierarchy($qur_val[0]['section_id']);
            
            echo $dept_array['d']." - ".$dept_array['c']." - ".$dept_array['s'];?>
            <br>
            <strong>
             <?php echo get_phrase('academic_year');?>
            : </strong><?php echo $qur_val[0]['acadmic_year_name'];?>
            <br>
            <strong> <?php echo get_phrase('promotion_class');?>: </strong>
            <?php $dept_array2=section_hierarchy($qur_val[0]['pro_section_id']);
            
            echo $dept_array2['d']." - ".$dept_array2['c']." - ".$dept_array2['s'];?>  
            <br>
            <strong> <?php echo get_phrase('promotion_academic_year');?>:</strong>
            <?php echo $qur_val[0]['pro_acadmic_year_name'];?>
            <br>
            <strong> <?php echo get_phrase('current_status');?>: </strong>
            <?php echo promotion_class_status($qur_val[0]['activity']);
            if(isset($array_status[$qur_val[0]['bulk_req_id']])){
	
echo "<br>";
$Total=array_sum($array_status[$qur_val[0]['bulk_req_id']]);
echo "Total forms: ".$Total;
$paid=0;
if(isset($array_status[$qur_val[0]['bulk_req_id']][5])){
echo " Paid: ".$paid=$array_status[$qur_val[0]['bulk_req_id']][5];	
}
echo " Un-Paid: ".($Total-$array_status[$qur_val[0]['bulk_req_id']][5]);
}
            
            ?>
            	</div>
            </div>
            
			<div class="panel-body">
<?php echo form_open(base_url().'promotion/bulk_condition/', array('class' => 'form-horizontal form-groups-bordered validate','id' => 'ajax_promotion_form' ,'enctype' => 'multipart/form-data'));?>
                <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo get_phrase('promotion_status');?></label>
                            <div class="col-sm-8">
                                    <?php //echo $qur_val[0]['activity']; ?>
                                  <select onchange="chnage_work()" id="acad_year_days" name="promotion_status" class="form-control" required>
                                    <?php
                                        echo promote_class_status($qur_val[0]['activity']);
                                    ?>
                                 </select>
                                <input id="due_days" name="due_days" type="hidden" value="<?php echo $due_days=$qur[0]['due_days'];   ?>"/>
                                <input id="bulk_req_id" name="bulk_req_id" type="hidden" value="<?php echo $param2;   ?>"/>
                            </div>
                </div>

        
        
        
        
    <div class="form-group" id="fin">
                    <label class="col-sm-4 control-label"><?php echo get_phrase('due_date');?></label>
                    <div class="col-sm-8">
    

<input  id="due_days1" name="" class="form-control datepicker" type="text" value="<?php echo date_dash($due_days=$qur[0]['due_date']); ?>" data-format="dd/mm/yyyy" /> 
<script>
	
	$(document).ready(function(){
		$("#fin").hide();
	var get_val =$('#acad_year_days').val();

for(get_val=parseInt($('#acad_year_days').val()); get_val>=0; get_val--){

//$("#acad_year_days option[value='"+get_val+"']").attr('disabled','true');

}

});
	
	function chnage_work(){
		
		
			var acd=$("#acad_year_days").val();
			//var e=document.getElementById('acad_year');
			
		//	var acdd=e.options[e.selectedIndex].value;
			
			
			
			if(acd=='4'){

$("#due_days1").attr('name','due_date');
$("#fin").fadeIn();
	
			}

else{
		$("#due_days1").attr('name','');	
		$("#fin").fadeOut();
		}
			
		
		
	}
	
	
</script>





</div>
</div>

        
        
        
        
        
        
        
        
 <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button id="btn1" type="submit" class="btn btn-info"><?php echo get_phrase('save');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>


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
</style>