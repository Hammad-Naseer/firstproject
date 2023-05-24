<!--<?php print_r($res); ?>-->
<style>
	.boarder{
		
		  
    border: 1px solid #f2f2f2;
  height: 34px;
		
	}
.modal-backdrop {
   
    z-index: 0 !important;
}
</style>

<?php

foreach($res as $rd):

 ?>





<div class="row">



	<div class="col-md-12">
	
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
<?php echo get_phrase('Challan_form');?>
            	</div>
            </div>
			<div class="panel-body">
<?php echo form_open(base_url().'accountant/save_edit_chalan/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
<div class="col-md-12">
	
<div class="col-md-3"><center>Chalan #</center></div>	
<div class="col-md-9"><input class="form-control" name="chalan_number"/> </div>	
</div>


<div class="row">

<!-- labels -->
  <div class="col-md-1 boarder"><h4><strong>Sr.No</strong></h4></div>
  <div class="col-md-6 boarder"><h4><strong>Challan Header</strong></h4></div>
  <div class="col-md-5 boarder"><h4><strong>Challan Amount</strong></h4></div>
  
 <!-- label ends here-->
 
 
 <!-- labels -->
  <div class="col-md-1 boarder">1</div>
  <div class="col-md-6 boarder"> <input type="text" class="form-control" name="chalan1" value="<?php echo $rd->chalan1; ?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" autofocus readonly=""> </div>
  <div class="col-md-5 boarder"><input type="text" onchange="add_count()"  class="form-control value1" name="amount1" data-validate="required"   data-message-required="<?php echo get_phrase('value_required');?>"  value="<?php echo $rd->amount1; ?>" autofocus></div>
  
 <!-- label ends here-->
 <!-- labels -->
  <div class="col-md-1 boarder">2</div>
  <div class="col-md-6 boarder"><input type="text" class="form-control" name="chalan2"     value="<?php echo $rd->chalan2; ?>" autofocus readonly="" ></div>
  <div class="col-md-5 boarder"><input type="text"  onchange="add_count()" class="form-control value2" name="amount2"     value="<?php echo $rd->amount2; ?>" autofocus></div>
  
 <!-- label ends here-->
 <!-- labels -->
  <div class="col-md-1 boarder">3</div>
  <div class="col-md-6 boarder"><input type="text" class="form-control" name="chalan3"     value="<?php echo $rd->chalan3; ?>" autofocus readonly=""></div>
  <div class="col-md-5 boarder"><input type="text"  onchange="add_count()"  class="form-control value3" name="amount3"     value="<?php echo $rd->amount3; ?>" autofocus></div>
  
 <!-- label ends here-->
 <!-- labels -->
  <div class="col-md-1 boarder">4</div>
  <div class="col-md-6 boarder"><input type="text"   class="form-control " name="chalan4"     value="<?php echo $rd->chalan4; ?>" autofocus readonly=""></div>
  <div class="col-md-5 boarder"><input type="text" onchange="add_count()" class="form-control value4" name="amount4"  
     value="<?php echo $rd->amount4; ?>" autofocus></div>
  
 <!-- label ends here-->
 <!-- labels -->
  <div class="col-md-1 boarder">5</div>
  <div class="col-md-6 boarder"><input type="text"  class="form-control " name="chalan5"     value="<?php echo $rd->chalan5; ?>" autofocus></div>
  
  <div class="col-md-5 boarder"><input type="text" class="form-control value5" name="amount5" onchange="add_count()"    value="<?php echo $rd->amount5; ?>" autofocus></div>
  
 <!-- label ends here-->
 <!-- labels -->
  <div class="col-md-1 boarder">6</div>
  <div class="col-md-6 boarder"><center><h4><strong>Chalan Month/year</strong></h4></center></div>
  
  <div class="col-md-5 boarder">
 <div class="col-md-6 boarder">
  <select  name="month" class="form-control">
<option value="<?php echo $rd->chalan_month; ?>"><?php echo $rd->chalan_month; ?></option>
  </select>
</div>
   <div class="col-md-6 boarder">
    <select  name="year" class="form-control">
<option value="<?php echo $rd->chalan_year; ?>"><?php echo $rd->chalan_year; ?></option>
  </select>	
  </div>
  </div>
  
 <!-- label ends here-->
 <!-- labels -->
  <div class="col-md-1 boarder">7</div>
  <div class="col-md-6 boarder"><center><h4><strong>Concession</strong></h4></center></div>
  
  <div class="col-md-5 boarder" style="padding: 0px !important;">
  <div class="col-md-8">
  <input type="text"  class="form-control value6" onchange="add_count()" name="concession"     value="<?php echo $rd->discount; ?>" autofocus>
  </div>
  <div class="col-md-3">
  	<div class="btn btn-info" data-toggle="modal" data-target="#myModal">Add Reason</div>
  	
  	</div>
  </div>
  
 <!-- label ends here-->
 <!-- labels -->
  <div class="col-md-1 boarder">8</div>
  <div class="col-md-6 boarder"><center><h4><strong>Due Date</strong></h4></center></div>
  
  <div class="col-md-5 boarder"><input type="text" class="form-control datepicker" name="end_date"     value="<?php echo $rd->due_date; ?>" autofocus></div>
  
 <!-- label ends her                             e-->
 
  <div class="col-md-1 boarder"></div>
   
   
<input type="hidden" id="chalan_id" name="chalan_id" value="<?php echo $rd->chalan_id; ?>"/>
<input type="hidden" id="class_id" name="class_id" value="<?php echo   $class_id= $this->uri->segment(3); ?>"/>



   
   
   
   
   
   
   
  <div class="col-md-6 boarder"><h4><strong>Total Ammount</strong></h4></div>
  <div class="col-md-5 boarder" ><input type="text" id="a6" class="form-control " name="total_amount"     value="<?php echo $rd->total_amount; ?>" autofocus readonly=""></div>
  
 <!-- label ends here-->
 
 
 
 
 
</div>


<!-- name here--
<div class="form-group">
<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>                     
<div class="col-sm-5">

<input type="text" class="form-control" name="name"     value=" <?php echo $last_insert_id;?>" autofocus>
					
					</div>
					</div>
		
		

		
<!-- name here--		
				
	<div class="form-group">
<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>                     
<div class="col-sm-5">

<input type="text" class="form-control" name="name"     value=" <?php echo $last_insert_id;?>" autofocus>
					</div>
					</div>			
				-->
 <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('Save');?></button>
						</div>
					</div>
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enter the Reason of concession</h4>
        </div>
        <div class="modal-body">

  <textarea style="width: 500px; height: 250px;" id="student_reason" name="reasion" ></textarea>
<div type="submit" id="concessionbtn" class="btn btn-info"><?php echo get_phrase('Save_Reasion');?></div>
  </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<?php echo form_close();?>
            </div>
        </div>
    </div>
</div>



<?php endforeach; ?>
	<script>
				$(document).ready(function(){
					var value1 =$(".value1").val();
						var value2 =$(".value2").val();
						var value3 =$(".value3").val();
						var value4 =$(".value4").val();
						var value5 =$(".value5").val();
//////// counting ends here
$("#concessionbtn").click(function(){
					
					post1();
					
				});
				});
				
			function post1(){
		var student_id=$("#student_id").val();
		var reasion=$("#student_reason").val();
      $('#myModal').hide();
    /*
    $.ajax({
      type: "POST",
      url: '<?php echo site_url();?>/accountant/student/concession',
      data: ({student_id:student_id,reasion:reasion}),
      success: function(data) {
      	
     
      alert("reasion save successfull")
       $('#myModal').hide();
      
      }
    });
 */
	}	
			
			
function add_count(){
	
	
	var value1 =$(".value1").val();
var value2 =$(".value2").val();
var value3 =$(".value3").val();
var value4 =$(".value4").val();
var value5 =$(".value5").val();
var value6=$(".value6").val();

var abc=[value1,value2,value3,value4,value5,value6]
				for(var count = 0; count <6 ; count++){
				if(abc[count]==""){
					abc[count]=0;
					//alert(abc[count]);
				}else{
					//alert(abc[count]);
				}
				//alert(abc[count]);
				}
$("#a6").val((parseInt(abc[0])+parseInt(abc[1])+parseInt(abc[2])+parseInt(abc[3])+parseInt(abc[4]))-parseInt(abc[5]));
	
	
	
}			
				
				
				
			</script>	
				