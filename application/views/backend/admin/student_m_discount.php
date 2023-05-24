      <?php  if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<script>
	$( window ).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
    });
</script>
<?php
$query=$this->db->get_where(get_school_db().'.student_m_discount' , array('school_id' =>$_SESSION['school_id'],'student_id'=>$student_id))->result_array();

//echo $this->db->last_query();
$flag="do_insert";  
?>
<a href="<?php echo base_url(); ?>c_student/student_information/<?php echo $section_id;?>" class="btn btn-primary"><?php echo get_phrase('back');?></a>








   <div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
                    <h3 class="system_name inline">
                      <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                      <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/challan-form-setting.png">
                      
                      <?php echo get_phrase('student_monthly_discount');?>
                       
                    </h3>
                    </div> </div>


<?php 

$ret_val=student_details($student_id);



$section_ary=section_hierarchy($ret_val[0]['section_id']); 



if($ret_val[0]['image']!=""){
$img_dis=display_link($ret_val[0]['image'],'student');	
}else{
$img_dis=base_url().'/uploads/default.png';	
}
?>


<div class="row">

  <div class="col-sm-4 " style="margin-left:20px;">
 <div class="row std_three" style="padding:8px;">

 <div class=" col-sm-3 std_one">
 <span class="std_img">
<img  class="img-responsive"  src="<?php echo $img_dis ; ?>" style="max-height: 85px;
    max-width: 85px;
    min-height: 85px;
    min-width: 85px;
    padding-right: 10px;">
</span>
</div>
<div class=" col-sm-9  std_two">
<p class="std_name">   <?php echo get_phrase('name');?> : <?php echo $ret_val[0]['name']; ?></p>
<p class="std_class"> <?php echo get_phrase('department');?> : <?php echo $section_ary['d']; ?></p>
<p class="std_class"> <?php echo get_phrase('class');?> : <?php echo $section_ary['c']; ?></p>
<p class="std_class"> <?php echo get_phrase('section');?> : <?php echo $section_ary['s']; ?></p>
<p class="std_roll"> <?php echo get_phrase('roll');?> : <?php echo $ret_val[0]['roll']; ?></p>
</div>

</div>
</div>


</div>





<form action="<?php echo base_url(); ?>c_student/student_m_discount/<?php echo $student_id; ?>/<?php echo $flag; ?>" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">
 
<div class="">
	<div class="row">
      
        <!-- panel preview -->
        <div class="col-sm-12">
        
            <div class="panel panel-default">
    <div class="panel-body form-horizontal payment-form">
                    <div class="form-group">
                        <label for="concept" class="col-sm-3 control-label"> <?php echo get_phrase('title');?><span class="star">*</span></label>
                        <div class="col-sm-9">
<input maxlength="50" type="text" class="form-control"  id="title"  name="title" value="<?php echo  $query[0]['title']; ?>" required>

<input maxlength="50" type="hidden" class="form-control"  id="school_name"  name="s_m_d_id" value="<?php echo  $query[0]['s_m_d_id']; ?>">
<input maxlength="50" type="hidden" class="form-control"  id="school_name"  name="student_id" value="<?php echo  $student_id; ?>">




                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="concept" class="col-sm-3 control-label"> <?php echo get_phrase('amount');?><span class="star">*</span></label>
                        <div class="col-sm-9">
  <input maxlength="50" type="text" class="form-control"  id="amount"  name="amount" value="<?php echo  $query[0]['amount']; ?>" required>

  
  
  <div class="col-lg-12" id="address_count"></div>
  
                        </div>
                    </div>
             
              <!--<div class="form-group">
                        <label for="concept" class="col-sm-3 control-label">Chart Of Account<span class="star">*</span></label>
                        <div class="col-sm-9">
    
    <select name="coa_id" class="form-control" >
<?php
     
echo coa_list_h($parent_id=0,$query[0]['coa_id'],0,0,0);

 ?>                   
</select>


<div class="col-lg-12" id="bank_details_count"></div>
                        </div>
                    </div>-->
                    
                    
                    <div class="form-group">
 <label for="concept" class="col-sm-3 control-label"> <?php echo get_phrase('student_monthly_discount');?><span class="star">*</span></label>
 <div class="col-sm-9">
    	    <?php 
			
			$attributes = 'class="form-control" data-validate="required" data-message-required="Value Required"';
			echo monthly_discount("discount_type" , $query[0]['discount_id'] ,$attributes); ?>
</div>
</div>
                    
                    
            <div class="form-group">
                        <label for="concept" class="col-sm-3 control-label">
                         <?php echo get_phrase('academic_year');?>
                        <span class="star">*</span></label>
                        <div class="col-sm-9">
 

 

    <select name="academic_year_id" class="form-control" id="academic_year_id" >
<?php
     
echo academic_year_option_list($query[0]['academic_year_id'],1);

 ?>                   
</select>
<div id="d"></div>

   

  <div class="col-lg-12" id="terms_count"></div>
                        </div>
                    </div>
                   
            <div class="col-sm-offset-3" style="padding-left: 9px;">
            <button id="main_btn" name="submit" class="btn btn-primary "><?php echo get_phrase('save'); ?></button>
            <a href="#"  onclick="confirm_modal('<?php echo base_url();?>c_student/delete_discount/<?php echo  $query[0]['s_m_d_id']; ?>')"><i class="entypo-trash"></i>
<?php echo get_phrase('remove_discount');?>

            </div> 
 
  </div>
            </div>            
        </div> <!-- / panel preview -->

	</div>
</div>
</form>
<style>

	#d{color:red;}
</style>
<script>
	
	$(document).ready(function(){
		
		if($("#academic_year_id").val()=="")
		{
			
			
			$("#d").html("<?php echo get_phrase('value_required'); ?>");
		}
		
	});
	
	
function valadation(x,y){
	//alert(x);
	//alert(y);
 var count=$('#'+y).val().length;
	
	if(count>x || count==0){
			$('#error_text').remove();
	$('#'+y).css('border','1px solid red');
	$('#'+y).before('<p id="error_text" style="color:red;"><?php echo get_phrase('charactor_must_be_less_then'); ?>' + x+'</p>');
	$('#main_btn').prop('disabled', true);

	
	}

else{
$('#'+y).css('border','1px solid green');
			$('#error_text').remove();
			
			
$('#main_btn').prop('disabled', false);
				}

var flag=$("#error_text").html();


if(flag==undefined){

	$('#main_btn').prop('disabled',false);
	
}else{
	$('#main_btn').prop('disabled', true);
}
















	}

</script>