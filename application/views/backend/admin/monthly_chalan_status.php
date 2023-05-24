<?php 
    $q = "SELECT count(scf.status) as status_count,bmc.activity,scf.status,scf.bulk_req_id FROM ".get_school_db().".bulk_monthly_chalan bmc INNER JOIN ".get_school_db().".student_chalan_form scf ON scf.bulk_req_id=bmc.b_m_c_id WHERE scf.is_bulk=2 AND scf.school_id=".$_SESSION['school_id']." And bmc.b_m_c_id=$param2 AND scf.is_cancelled = 0 GROUP By scf.status,scf.bulk_req_id ORDER BY scf.bulk_req_id";
    $query=$this->db->query($q)->result_array();
    $array_status=array();
    foreach($query as $res)
    {
        $array_status[$res['bulk_req_id']][$res['status']]=$res['status_count'];
    }
    $school_id=$_SESSION['school_id'];
    $qur_val=$this->db->query("select * from ".get_school_db().".bulk_monthly_chalan where b_m_c_id=$param2 and school_id=$school_id and status=1")->result_array();
    $qur=$this->db->query("select s_c_f_id , due_days, DATE_ADD(CURDATE(), INTERVAL due_days DAY) as due_date from ".get_school_db().".student_chalan_form where school_id=$school_id and bulk_req_id=$param2 and is_cancelled = 0 limit 1")->result_array();
    $qur_array=$this->db->query("select s_c_f_id , due_days, DATE_ADD(CURDATE(), INTERVAL due_days DAY) as due_date from ".get_school_db().".student_chalan_form where school_id=$school_id  and bulk_req_id=$param2 and is_cancelled = 0")->result_array();
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('monthly_challan_status');?>
            	</div>
            </div>
            <div class="myttl" style="padding-left:10px;">
                <?php echo "Fee month :".month_of_year($qur_val[0]['fee_month']).' - '.$qur_val[0]['fee_year'];?>
            </div>
            <div style="padding-left:10px;color: black;">
                <strong><?php echo get_phrase('department');?> / <?php echo get_phrase('class');?> / <?php echo get_phrase('section');?>: </strong>
                <?php $dept_array=section_hierarchy($qur_val[0]['section_id']); echo $dept_array['d']." - ".$dept_array['c']." - ".$dept_array['s'];?>
                <br>
                <?php
                    $Total=0;
                    if(isset($array_status[$qur_val[0]['b_m_c_id']])){
                        $Total=array_sum($array_status[$qur_val[0]['b_m_c_id']]);
                        echo "<b>Total forms: </b>".$Total;
                        $paid=0;
                        if(isset($array_status[$qur_val[0]['b_m_c_id']][5])){
                            echo " Paid: ".$paid=$array_status[$qur_val[0]['b_m_c_id']][5];	
                        }
                        echo "<br>";
                        echo "<b> Un-Paid:</b> ".($Total-$array_status[$qur_val[0]['b_m_c_id']][5]);
                    }
                    echo "<br>";
                    echo "<b>Status:</b>".monthly_class_status($qur_val[0]['activity']);
                ?>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'monthly_fee/bulk_condition/', array('class' => 'form-horizontal form-groups-bordered validate','id' => 'monthly_form_issue' ,'enctype' => 'multipart/form-data'));?>
                <div class="form-group">
                  	<label class="control-label"><?php echo get_phrase('monthly_challan_form_status');?></label>
                    <select id="acad_year" name="promotion_status" class="form-control" required="">
                        <?php echo class_bulk_status();   //$qur_val[0]['activity'] ?>
                    </select> 
                    <input id="due_days" name="due_days" type="hidden" value="<?php echo $due_days=$qur[0]['due_days'];   ?>"/>
                    <input id="b_m_c_id" name="b_m_c_id" type="hidden" value="<?php echo $param2;   ?>"/>
                </div>
                <div class="form-group" id="fin">
                    <label class="control-label"><?php echo get_phrase('Due Date');?></label>
                    <input value="<?php echo date("Y-m-d") ?>" id="due_days1" name="due_date" class="form-control" type="date" /> 
                    <script>
                    	$(document).ready(function(){
                    		$("#fin").hide();
                    		var get_val =$('#acad_year').val();
                    
                            for(get_val=parseInt($('#acad_year').val()); get_val>=0; get_val--){
                                $("#acad_year option[value='"+get_val+"']").attr('disabled','true');
                            }		
                    		$("#acad_year").change(function(){
                    			var acd=$(this).val();	
                    			if(acd=='4'){
                                    $("#due_days1").attr('name','due_date');
                                    $("#fin").fadeIn();
                    			}else{
                        			$("#due_days1").attr('name','');	
                        			$("#fin").fadeOut();
                    			}
                    		});
                    	});
                    </script>
                </div>
                <div class="form-group">
					<div class="float-right">
    					<button type="submit" id="btn1" class="modal_save_btn">
    						<?php echo get_phrase('save');?>
    					</button>
    					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    						<?php echo get_phrase('cancel');?>
    					</button>
    				</div>
				</div>
                <?php echo form_close();?>
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
    <script src="<?php echo base_url(); ?>assets/js/common.js"></script>