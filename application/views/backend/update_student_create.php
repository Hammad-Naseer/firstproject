<style>

	h3{
	font-size: 17px !important;
    color: #6d8bc6 !important;
    border-bottom: 1px solid rgba(204, 204, 204, 0.38);
    margin-bottom: 20px;
    margin-top: 11px;
    padding-bottom: 6px;
	}
	
	h2{
	font-size: 17px !important;
    color: #6d8bc6 !important;
    border-bottom: 1px solid rgba(204, 204, 204, 0.38);
    margin-bottom: 20px;
    margin-top: 11px;
    padding-bottom: 6px;
	}
	.mgt50{
		margin-top:35px;
	}
</style>

<?php //session_start();  ?>
<div class="row">



<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
                    <h3 class="system_name inline" style="border-bottom:none;">
                      <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                    
                      <?php echo get_phrase('student information');?>
                    </h3>
</div>


<div class="panel panel-primary" data-collapsed="0">

<div class="panel-body" >	


<div class="row">
<?php
$control;

$std_id; //student_id
$section_id;
$student_status;
            $studentID = $this->uri->segment(3);
            $sectionID = $this->uri->segment(4);
//print_r($section_id1);

$strd_rec=$this->db->query("select s.*, c.title as class_name from ".get_school_db().".student s inner join ".get_school_db().".class_section c on s.section_id=c.section_id  


where s.school_id=".$_SESSION['school_id']." AND s.student_id=$studentID")->result_array();
//print_r($strd_rec);
foreach($strd_rec  as $std_rec){
$section_ary=section_hierarchy($std_rec['section_id']); 
//echo "<pre>";print_r($std_rec["id_no"]);
if($std_rec['image']!=""){
$img_dis=display_link($std_rec['image'],'student');	
}else{
$img_dis=base_url().'/uploads/default.png';	
}



?>
<?php
$controller_val="";
if($control=='c')
{
	
	$controller_val="c_student/student_pending/".$section_id."/".$student_status;
}
elseif($control=='s')
{
	
	$controller_val="c_student/student_information/".$section_id;
}
?>
<!--<a href="<?php echo base_url();?><?php echo $controller_val;?>" class="btn btn-primary">
<?php echo get_phrase('back');?></a>-->

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
<p class="std_name"><?php echo get_phrase('name');?> : <?php echo $std_rec['name']; ?></p>
<p class="std_class"><?php echo get_phrase('department');?> : <?php echo $section_ary['d']; ?></p>
<p class="std_class"><?php echo get_phrase('class');?> : <?php echo $section_ary['c']; ?></p>
<p class="std_class"><?php echo get_phrase('section');?> : <?php echo $section_ary['s']; ?></p>
<p class="std_roll"><?php echo get_phrase('roll_no');?> : <?php echo $std_rec['roll']; ?></p>
</div>

</div>
</div>
<?php  } ?>

</div>
<hr>


<?php echo form_open(base_url().'user/manage_student_login/'.$this->uri->segment(3).'/'.$this->uri->segment(4), array(
'id'=>'student_update',
'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>                 
<?php
$student_id=$this->uri->segment(4);
 $f_query=$this->db->query("SELECT * FROM ".get_school_db().".student_relation sr inner join ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id where sr.school_id=".$_SESSION['school_id']." AND sr.student_id=$student_id and relation='f'")->result_array();

  ?>
<!-- father -->

<div class="col-sm-12">

<h2>
<?php echo get_phrase('login_student_record');?>
</h2>
 
<div class="form-group">
<label for="field-1" class="col-sm-3 control-label">
<?php echo get_phrase('CNIC');?>	<span class="red"> * </span>
</label>
                        
						<div class="col-sm-5">
<input maxlength="30" minlength="3" id="f_cnic" readonly  type="text" class="form-control" name="f_cnic"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"autofocus value="<?php echo $std_rec["id_no"]; ?>" onkeyup="nospaces(this)" >
 
 
 <input id="student_id" name="student_id"  type="hidden"  value="<?php echo $studentID; ?>" >
 

 
<a onclick="get_cnic('f_cnic','rec_1', <?php echo $f_query[0]['s_p_id']; ?>)" style="color:#086a24 !important; cursor:pointer"> <?php echo get_phrase('Validate_cnic');?></a>
 
 
 
 </div>

</div>
<?php
 $nicId = $std_rec["id_no"];

    $isQuery = $this->db->query("SELECT * FROM ".get_system_db().".`user_login` JOIN ".get_system_db().". user_login_details ON user_login.user_login_id = user_login_details.user_login_id 
    WHERE user_login.id_no= '".$nicId."'")->result_array();
    //print_r($isQuery[0]['name']);
?>
 
<div class="form-group">

<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Display Name');?><span class="red"> * </span></label>
<div class="col-sm-5">
<input required="" maxlength="100" id="p_name" type="text" class="form-control" name="f_p_name" value="<?php echo $isQuery[0]['name'];  ?>">
</div>
</div>
                    
<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Password'); ?></label>
						<div class="col-sm-5">
			<span style="color: red;" id="message"></span>
			<input maxlength="50" type="password" class="form-control" name="password">
						</div>
					</div>
				<div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Status <span class="red"> * </span></label>
                            <div class="col-sm-5">
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="0"> Inactive                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="1" checked=""> Active                                </label>
                            </div>
                        </div>	

	</div>
<div class="form-group">
<div class="col-sm-offset-3 col-sm-5">
							<button id="btn1" type="submit" class="btn btn-default"><?php echo get_phrase('Login');?></button>
						</div>
					</div>

                <?php echo form_close();?>
            </div>
        </div>
  


</div>
<script>

$(document).ready(function(){
	
var cnic1=$('#f_cnic').val();
var cnic2=$('#m_cnic').val();
var cnic3=$('#g_cnic').val();
 var detail_field1='rec_1';
 var detail_field2='rec_2';
 var detail_field3='rec_3';
 
 var sub_flag1= 0; //f_cnic
 
 
 
get_student(cnic1,detail_field1);
get_student(cnic2,detail_field2);
get_student(cnic3,detail_field3);

	
})




var  flag1= 0; //f_cnic
var  flag2= 0; //m_cnic
var  flag3= 0; //g_cnic	

var sub_flag1= 0; //f_cnic
var sub_flag2= 0; //m_cnic
var  sub_flag3= 0; //g_cnic	
	
function get_cnic(file_name,detail_field, s_p_id){	
var cnic=$('#'+file_name).val();
	//alert('yes');
	
$.ajax({
      type: 'POST',
      data: {cnic:cnic, pid:s_p_id},
url: "<?php echo base_url();?>admin/parent_cnic",
// dataType: "json",
 dataType: "html",
 success: function(response) 
 { 
      
//var obj = jQuery.parseJSON(response);
//var parsed = JSON.parse(response);
if(file_name=='f_cnic'){

get_student(cnic,detail_field);


if($('#f_cnic').val()!=$('#f_cnic_c').val()){
	
	//alert("in iff");
	
if($.trim(response)=='no'){
$('#message').remove();	
	
$('#'+file_name).css('border','1px solid red');	
$('#'+file_name).before('<p style="color:red;" id="message"><?php echo get_phrase('id_already_exist'); ?>.</p>');

flag1=1;

}

else{
	
	
$('#'+file_name).css('border','1px solid green');	
$('#message').remove();
flag1=0;
	
}

}








}

if(file_name=='m_cnic'){
	
get_student(cnic,detail_field);



if($('#m_cnic').val()!=$('#m_cnic_c').val()){
	
if($.trim(response)=='no'){
$('#message').remove();	
	
$('#'+file_name).css('border','1px solid red');	
$('#'+file_name).before('<p style="color:red;" id="message"><?php echo get_phrase('id_already_exist'); ?>.</p>');

flag2=1;
	
}

else{

$('#'+file_name).css('border','1px solid green');	
$('#message').remove();
flag2=0;	
}
}



	
}

if(file_name=='g_cnic'){
	get_student(cnic,detail_field);
if($('#g_cnic').val() != $('#g_cnic_c').val()){
	
if($.trim(response)=='no'){
$('#message').remove();	
	
$('#'+file_name).css('border','1px solid red');	
$('#'+file_name).before('<p style="color:red;" id="message"><?php echo get_phrase('id_already_exist'); ?></p>');

flag3=1;
	
}

else{

$('#'+file_name).css('border','1px solid green');	
$('#message').remove();
flag3=0;	
}

}
}


disb_cnic();

}

});

}

function get_student(cnic,detail_field){
$('#'+detail_field).html(' ');

$.ajax({
      type: 'POST',
       data: {cnic:cnic},
url: "<?php echo base_url();?>admin/get_student_new",
 //dataType: "json",
 dataType: "html",
 success: function(response) { 
      
//var obj = jQuery.parseJSON(response);
//var parsed = JSON.parse(response);


$('#'+detail_field).html(response);

//alert(response);
//alert(detail_field);

}
});

}

function disb_cnic(){
	
	
	
	
	if(flag1==0 && flag2==0 && flag3==0){
	

	$('#btn1').removeAttr('disabled');	
	
	
	
	//alert(flag1+'='+flag2+'='+flag3+'if');
		
	}
	else{
		
		$('#btn1').attr('disabled','true');	
	//alert(flag1+'='+flag2+'='+flag3+'else');
		
	}
	
}	

	

	
</script>