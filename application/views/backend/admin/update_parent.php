<?php //session_start(); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
               <?php echo get_phrase('update_parent'); ?>
        </h3>
        <a href="<?php echo base_url();?><?php echo $controller_val;?>" class="btn btn-primary float-right"><?php echo get_phrase('back');?></a>
    </div>
</div>

<div class="row">
    <!--<div class="col-lg-12 col-md-12 col-sm-12">    -->
            <?php
            $control;
            
            $std_id; //student_id
            $section_id;
            $student_status;
            
            $strd_rec=$this->db->query("select s.*, c.title as class_name from ".get_school_db().".student s inner join ".get_school_db().".class_section c on s.section_id=c.section_id where s.school_id=".$_SESSION['school_id']." AND s.student_id=$std_id")->result_array();
            
            foreach($strd_rec  as $std_rec)
            {
                $section_ary=section_hierarchy($std_rec['section_id']); 
                if($std_rec['image']!=""){
                    $img_dis=display_link($std_rec['image'],'student');	
                }else{
                    $img_dis=base_url().'/uploads/default.png';	
                }
                
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
                <div class="col-lg-12 col-sm-12">
                    <div class="profile-env">
            			<header class="row">
            				<div class="col-sm-3">
            					<a href="#" class="profile-picture">
            					    <?php if($res[0]['profile_pic']=="") { ?>
                					    <img src="<?php echo get_default_pic()?>" class="img-responsive img-circle" width="100"/> 
                					<?php } else {?>
                    					<img src="<?=$img_dis?>" class="img-responsive img-circle" width="100">
                					<?php } ?>
            					</a>
            				</div>
            				<div class="col-sm-9">
            					<ul class="profile-info-sections">
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
            								<p class="std_name"><?php echo get_phrase('name');?> : <?php echo $std_rec['name']; ?></p>
                                            <p class="std_class"><?php echo get_phrase('department');?> : <?php echo $section_ary['d']; ?></p>
                                            <p class="std_class"><?php echo get_phrase('class');?> : <?php echo $section_ary['c']; ?></p>
                                            <p class="std_class"><?php echo get_phrase('section');?> : <?php echo $section_ary['s']; ?></p>
                                            <p class="std_roll"><?php echo get_phrase('roll_no');?> : <?php echo $std_rec['roll']; ?></p>
            							</div>
            						</li>
            					</ul>
            				</div>
            			</header>
            		</div>
            	</div>
            <?php } ?>
    <!--</div>-->
    <hr>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body" >
                <?php echo form_open(base_url().'admin/save_update_parent/'.str_decode($this->uri->segment(4)).'/'.$this->uri->segment(5), array(
                'id'=>'parent_update',
                'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>                 
                <?php
                    $student_id = str_decode($this->uri->segment(4));
                    $f_query=$this->db->query("SELECT * FROM ".get_school_db().".student_relation sr inner join ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id where sr.school_id=".$_SESSION['school_id']." AND sr.student_id=$student_id and relation='f'")->result_array();
                ?>
                    <div class="col-sm-12">
                        <h2><?php echo get_phrase('update_father_record');?></h2>
                        <div class="col-md-6">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('CNIC');?><span class="red"> * </span>
                            </label>
                            <input maxlength="30" minlength="3" id="f_cnic"  type="text" class="form-control" name="f_cnic"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"autofocus value="<?php echo $f_query[0]['id_no']; ?>" onkeyup="nospaces(this)" >
                            <input id="f_cnic_c"  type="hidden"  value="<?php echo $f_query[0]['id_no']; ?>" >
                            <a onclick="get_cnic('f_cnic','rec_1', <?php echo $f_query[0]['s_p_id']; ?>)" style="color:#086a24 !important; cursor:pointer"> <?php echo get_phrase('Validate_cnic');?></a>
                        </div>
                        <div class="col-md-6">
                            <label for="field-1" class="control-label"><?php echo get_phrase('Name');?><span class="red"> * </span></label>
                            <input required="" maxlength="100" id="p_name" type="text" class="form-control" name="f_p_name" value="<?php echo $f_query[0]['p_name'];  ?>">
                        </div>    
                        <div class="col-md-6">
                        	<label for="field-1" class="control-label"><?php echo get_phrase('occupation'); ?></label>
                    		<span style="color: red;" id="message"></span>			
                            <input maxlength="100"   type="text" class="form-control" name="f_occupation" id="occupation" value="<?php echo $f_query[0]['occupation'];  ?>">
                        </div>
                        <div class="col-md-6">
                        	<label for="field-2" class="control-label"><?php echo get_phrase('contact');?></label>
                            <input maxlength="20" type="text" id="contact" class="form-control" name="f_contact" value="<?php echo $f_query[0]['contact'];  ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="field-2" class="control-label"><?php echo get_phrase('Attachment');?></label>
                            <input id="img_file" type="file" class="form-control" name="f_userfile" value="">
                            <input id="s_p_id" type="hidden" class="form-control" name="s_p_id" value="<?php echo $f_query[0]['s_p_id']; ?>">
                            <input id="f_attachment" type="hidden" class="form-control" name="image_file" value="<?php echo $f_query[0]['attachment']; ?>">
                            <span id="id_file">				
                                <?php $val_im = display_link($f_query[0]['attachment'],'student',0,0); 
                                if($val_im!="")
                                {
                                ?>	
                                    <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail">
                                        <?php echo get_phrase('form_attachment');?>
                                    </a>
                                    <a onclick="delete_files('<?php echo $f_query[0]['attachment']; ?>','student_parent','s_p_id','<?php echo $f_query[0]['s_p_id']; ?>','attachment','student','id_file',2)" href="#" class="img-responsive img-thumbnail">
                                     <?php echo get_phrase('delete_attachment');?>
                                    </a>
                                <?php
                                }
                                ?>								
                            </span>
                        </div>
                        <div class="row std_mg">
                        	<h3><?php echo get_phrase('related_children_list');?></h3>
                            <div id="rec_1"></div>
                        </div>
                        <div class="">
                            <div class="panel panel-default panel-shadow panel-collapse" data-collapsed="0">
                                <div class="panel-heading">
                            		<div class="panel-title myttl">
                                        <?php echo get_phrase('edit_more_information');?>
                                    </div>
                            		<div class="panel-options">
                            			<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                            		</div>
                                </div>
                                <div class="panel-body" style="display: block;">
                                    <h2><?php echo get_phrase('update_mothers_record'); ?></h2>
                                    <?php $m_f_query=$this->db->query("SELECT * FROM ".get_school_db().".student_relation sr inner join ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id where sr.school_id=".$_SESSION['school_id']." AND sr.student_id=$student_id and relation='m'")->result_array(); ?>
                                    <div class="col-md-6">
                                        <label for="field-1" class="control-label"><?php echo get_phrase('id_no');?></label>
                                        <input maxlength="30" id="m_cnic" type="text" class="form-control" name="m_cnic" data-message-required="<?php echo get_phrase('value_required');?>"autofocus value="<?php echo $m_f_query[0]['id_no'];  ?>" onkeyup="nospaces(this)">
                                        <input id="m_cnic_c" type="hidden" class="form-control"  value="<?php echo $m_f_query[0]['id_no'];  ?>" >
                                        <a onclick="get_cnic('m_cnic','rec_2', <?php echo $m_f_query[0]['s_p_id']; ?> )"  style="color:#086a24 !important; cursor:pointer"><?php echo get_phrase('validate_id_no'); ?></a>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="field-1" class="control-label"><?php echo get_phrase('Name');?></label>
                                        <input maxlength="100" id="p_name" type="text" class="form-control" name="m_p_name" value="<?php echo $m_f_query[0]['p_name'];  ?>">
                                    </div>
                                    <div class="col-md-6">
                                    	<label for="field-1" class="control-label"><?php echo get_phrase('occupation');?></label>
                                        <span style="color: red;" id="message"></span>			
                                        <input maxlength="100"   type="text" class="form-control" name="m_occupation" id="occupation" value="<?php echo $m_f_query[0]['occupation'];  ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="field-2" class="control-label"><?php echo get_phrase('contact');?></label>
                                        <input maxlength="20" type="text" id="contact" class="form-control" name="m_contact" value="<?php echo $m_f_query[0]['contact'];  ?>">
                                    </div>
                                    <div class="col-md-6">
                                    	<label for="field-2" class="control-label"><?php echo get_phrase('Attachment');?></label>
                                        <input id="img_file" type="file" class="form-control" name="m_userfile" value="">
                                        <input id="m_s_p_id" type="hidden" class="form-control" name="m_s_p_id" value="<?php echo $m_f_query[0]['s_p_id']; ?>">
                                        <input id="f_attachment" type="hidden" class="form-control" name="m_image_file" value="<?php echo $m_f_query[0]['attachment']; ?>">
                                        <span id="form_b_file_m">				
                                    	<?php     
                                            $val_im=display_link($m_f_query[0]['attachment'],'student',0,0); 
                                            if($val_im!="")
                                            {
                                        ?>	
                                            <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment'); ?></a>
                                            <a onclick="delete_files('<?php echo $m_f_query[0]['attachment']; ?>','student_parent','s_p_id','<?php echo $m_f_query[0]['s_p_id']; ?>','attachment','student','form_b_file_m',2)" href="#" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment'); ?></a>
                                        <?php } ?> 				
                                    								
                                    	</span>
                                    </div>
                                    <div class="row std_mg">
                                        <h3><?php echo get_phrase('related_children_list'); ?></h3>
                                        <div id="rec_2"></div>  
                                    </div>  
                                    <?php
                                        $g_f_query=$this->db->query("SELECT * FROM  ".get_school_db().".student_relation sr inner join ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id where sr.school_id=".$_SESSION['school_id']." AND sr.student_id=$student_id and relation='g'")->result_array();
                                    ?>
                                    <h2><?php echo get_phrase('update_guardians_record'); ?></h2>
                                
                                    <div class="col-md-6">
                                        <label for="field-1" class="control-label"><?php echo get_phrase('id_no');?>	</label>
                                        <input maxlength="30" id="g_cnic"  type="text" class="form-control" name="g_cnic"  value="<?php echo $g_f_query[0]['id_no'];  ?>" onkeyup="nospaces(this)">
                                        <input id="g_cnic_c"  type="hidden" class="form-control"   value="<?php echo $g_f_query[0]['id_no'];  ?>" >
                                        <a onclick="get_cnic('g_cnic','rec_3', <?php echo $g_f_query[0]['s_p_id']; ?> )" style="color:#086a24 !important; cursor:pointer"><?php echo get_phrase('validate_id_no'); ?></a>
                                    </div>
                                <div class="col-md-6">
                                    <label for="field-1" class="control-label"><?php echo get_phrase('Name');?></label>
                                    <input maxlength="100" id="p_name" type="text" class="form-control" name="g_p_name" value="<?php echo $g_f_query[0]['p_name'];  ?>">
                                </div>        
                                <div class="col-md-6">
                                    <label for="field-1" class="control-label"><?php echo get_phrase('occupation');?></label>
                                    <span style="color: red;" id="message"></span>			
                                    <input maxlength="100"   type="text" class="form-control" name="g_occupation" id="occupation" value="<?php echo $g_f_query[0]['occupation'];  ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="field-2" class="control-label"><?php echo get_phrase('contact');?></label>
                                    <input maxlength="20" type="text" id="contact" class="form-control" name="g_contact" value="<?php echo $g_f_query[0]['contact'];  ?>">
                                </div>
                                <div class="col-md-6">
                                	<label for="field-2" class="control-label"><?php echo get_phrase('Attachment');?></label>
                                    <input id="img_file" type="file" class="form-control" name="g_userfile" value="">
                                    <input id="g_s_p_id" type="hidden" class="form-control" name="g_s_p_id" value="<?php echo $g_f_query[0]['s_p_id']; ?>">
                                    <input id="f_attachment" type="hidden" class="form-control" name="image_file" value="<?php echo $g_f_query[0]['attachment']; ?>">
                                    <span id="form_b_file_g">				
                                    <?php     
                                     $val_im=display_link($g_f_query[0]['attachment'],'student',0,0); 
                                     if($val_im!=""){
                                    ?>	
                                        <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><?php echo get_phrase('form_attachment'); ?></a>
                                        <a onclick="delete_files('<?php echo $g_f_query[0]['attachment']; ?>','student_parent','s_p_id','<?php echo $g_f_query[0]['s_p_id']; ?>','attachment','student','form_b_file_g',2)" href="#" class="img-responsive img-thumbnail"><?php echo get_phrase('delete_attachment'); ?></a>
                                    <?php
                                    }
                                    ?> 				
                                    								
                                    </span>	
                                </div>
                                <div class="row std_mg">
                                    <h3><?php echo get_phrase('related_children_list'); ?></h3>
                                
                                    <div id="rec_3"></div>
                                </div>				
                                <!-- end guardian --> 
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button id="btn1" type="submit" class="modal_save_btn"><?php echo get_phrase('update_record');?></button>
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
        dataType: "html",
        success: function(response) 
        { 
            if(file_name=='f_cnic'){
                get_student(cnic,detail_field);
    
    
            if($('#f_cnic').val()!=$('#f_cnic_c').val()){
                
                if($.trim(response)=='no'){
                    $('#message').remove();
                    $('#'+file_name).css('border','1px solid red');	
                    $('#'+file_name).before('<p style="color:red;" id="message"><?php echo get_phrase('id_already_exist'); ?>.</p>');
                    
                    flag1=1;
                
                }else{
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
                    	
                    }else{
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
	}
	else{
		$('#btn1').attr('disabled','true');	
	}
}	

</script>