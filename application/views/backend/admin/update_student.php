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

<?php //session_start(); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline" style="border-bottom:none;">
            <?php echo get_phrase('student information');?>
        </h3>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        <div class="panel-body" >
            <div class="row">
                <?php
                    $control;
                    $std_id; //student_id
                    $section_id;
                    $student_status;
                    $studentID = str_decode($this->uri->segment(3));
                    $sectionID = str_decode($this->uri->segment(4));
                    
                    $user_group_id = 0;
                    $school_id     = $_SESSION['school_id'];
                    
                    $user_group_query        = "select user_group_id from ".get_school_db().".user_rights where student_id = $studentID and school_id = $school_id";
                    $user_group_query_result = $this->db->query($user_group_query)->row();
                    if($user_group_query_result != null){
                        $user_group_id = $user_group_query_result->user_group_id;
                    }
                    
                    $strd_rec=$this->db->query("select s.*, c.title as class_name from ".get_school_db().".student s inner join ".get_school_db().".class_section c on s.section_id=c.section_id  
                                                where s.school_id=".$_SESSION['school_id']." AND s.student_id=$studentID")->result_array();
                    //print_r($strd_rec);
                    foreach($strd_rec  as $std_rec){
                    $section_ary=section_hierarchy($std_rec['section_id']); 
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

<div class="">
    <div class="profile-env mb-4">
    			<header class="row">
    				<div class="col-sm-2">
    					<a href="#" class="profile-picture">
        			        <img src="<?php echo $img_dis ; ?>" class="img-responsive img-circle" />
    					</a>
    				</div>
    				<div class="col-sm-10">
    					<ul class="profile-info-sections d-flex">
    						<li>
    							<div class="profile-name">
    								<strong>
    									<a href="#"><?php echo $res[0]['name'];?></a>
    									<a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Online"></a>
    									<!-- User statuses available classes "is-online", "is-offline", "is-idle", "is-busy" -->						</strong>
    								<span><a href="#">Powered By Indici-Edu</a></span>
    							</div>
    						</li>
    						<li>
    							<div class="profile-stat">
    								<span><a href="#" class="text-dark"><b><?php echo get_phrase('department');?>: </b> <?php echo $section_ary['d']; ?></a></span>
    								<br>
    								<span><a href="#" class="text-dark"><b><?php echo get_phrase('class');?> : </b><?php echo $section_ary['c']; ?></a></span>
    								<br>
    								<span><a href="#" class="text-dark"><b><?php echo get_phrase('section');?> : </b><?php echo $section_ary['s']; ?></a></span>
    								<br>
    								<span><a href="#" class="text-dark"><b><?php echo get_phrase('roll');?> : </b><?php echo $std_rec['roll']; ?></a></span>
    							</div>
    						</li>
    					</ul>
    				</div>
			</header>
		</div>
</div>
<?php  } ?>
</div>
<hr>

<?php echo form_open(base_url().'user/manage_student_login/'.str_decode($this->uri->segment(3)).'/'.str_decode($this->uri->segment(4)), array(
'id'=>'student_update',
'class' => 'form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>                 
<?php
    $student_id=str_decode($this->uri->segment(3));
    // $f_query=$this->db->query("SELECT * FROM ".get_school_db().".student_relation sr inner join ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id where sr.school_id=".$_SESSION['school_id']." AND sr.student_id = $student_id and relation='f'")->result_array();
    $f_query=$this->db->query("SELECT * FROM ".get_school_db().".student_relation sr inner join ".get_school_db().".student std on std.student_id = sr.student_id where sr.school_id=".$_SESSION['school_id']." AND std.student_id = $student_id")->result_array();
?>
<!-- father -->
                <div class="col-lg-12 form-group">
                    <h2><b>
                        <?php
                            if($strd_rec[0]['is_login_created'] == 1){
                                echo get_phrase('update_student_credentials');
                            }else{
                                echo get_phrase('create_student_credentials'); 
                            }
                        ?>
                    </b></h2>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="field-1" class="control-label">
                            <?php echo get_phrase('CNIC');?>	<span class="red"> * </span>
                        </label>              
                        <input maxlength="30" minlength="3" id="f_cnic" readonly  type="text" class="form-control" name="f_cnic"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"autofocus value="<?php echo $std_rec["id_no"]; ?>" onkeyup="nospaces(this)" >
                        <input id="student_id" name="student_id"  type="hidden"  value="<?php echo $studentID; ?>" >
                        <a onclick="get_cnic('f_cnic','rec_1', <?php echo $f_query[0]['s_p_id']; ?>)" style="color:#086a24 !important; cursor:pointer"> <?php echo get_phrase('Validate_cnic');?></a>
                    </div>
                    <?php
                        
                        $nicId = $std_rec["id_no"];
                        $isQuery = $this->db->query("SELECT * FROM ".get_system_db().".`user_login` JOIN ".get_system_db().". user_login_details ON user_login.user_login_id = user_login_details.user_login_id WHERE user_login.id_no= '".$nicId."'")->result_array();
                    ?>
                    <div class="col-lg-6 form-group">
                        <label for="field-1" class="control-label"><?php echo get_phrase('Display Name');?><span class="red"> * </span></label>
                        <input required="" maxlength="100" id="p_name" type="text" class="form-control" name="f_p_name" value="<?php echo $f_query[0]['name']; ?>">
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="field-1" class="control-label"><?php echo get_phrase('Password'); ?></label>
                        <span style="color: red;" id="message"></span>
                        <input maxlength="50" type="password" class="form-control edu_password_validation" name="password">
                        <span class="text-danger edu_password_validation_msg"></span><br>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="field-1" class="control-label">Status <span class="red"> * </span></label>
                        <br>
                        <label class="radio-inline">
                            <input type="radio" name="status" value="0"> Inactive
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="status" value="1" checked=""> Active
                        </label>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="field-1" class="control-label" >Select User Group <span class="red"> * </span></label>
                        <select class="form-control" name="user_group_id" required>
                            <?php echo user_group_option_list($user_group_id); ?>
                        </select>
                    </div>
                    <div class="col-lg-6 form-group"></div>
                    <div class="col-lg-6 form-group">
                        <div>
                            <button id="btn1" type="submit" class="btn btn-default">        
                                <?php
                                    if($strd_rec[0]['is_login_created'] == 1){
                                        echo get_phrase('update_login_credentials');
                                    }else
                                    {
                                        echo get_phrase('generate_login_credentials'); 
                                    }
                                ?>
                            </button>
                        </div>
                    </div>
                <?php echo form_close();?>
            </div>
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