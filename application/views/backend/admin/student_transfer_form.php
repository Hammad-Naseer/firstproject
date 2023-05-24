<div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
    <h3 class="system_name inline" style="border-bottom:none;">
        <?php echo get_phrase('transfer_student');?>
    </h3>
</div>
                    
<?php
    $std_id=$this->uri->segment(3);
    $strd_rec=$this->db->query("select * from ".get_school_db().".student where school_id=".$_SESSION['school_id']." AND student_id=$std_id")->result_array();
    if(count($strd_rec)==0)
    {
	    $this->session->set_flashdata('club_updated','No student found');	
    }else{
	    $is_transfered=$strd_rec[0]['is_transfered'];
	if($is_transfered==1)
	{
		$this->session->set_flashdata('club_updated','Request already submitted');	
		redirect(base_url().'c_student/student_information/');
	}
    $section_id="";
    foreach($strd_rec  as $std_rec){
        $section_id	=$std_rec['section_id'];
        $section_ary=section_hierarchy($std_rec['section_id']); 
        if($std_rec['image']!=""){
            $img_dis=display_link($std_rec['image'],'student');	
        }else{
            $img_dis=base_url().'/uploads/default.png';	
        }   
        $section=$this->uri->segment(4);
        $section_id;
?>

<div class="col-lg-12 col-sm-12">
    <a href="<?php echo base_url();?>c_student/student_information/<?php echo $section;?>" class="btn btn-primary"><?php echo get_phrase('back');?></a>
    <div class="profile-env">
		<header class="row">
				<div class="col-sm-2">
					<a href="#" class="profile-picture">
        				<img src="<?php echo $img_dis ; ?>" class="img-responsive img-circle" />
					</a>
				</div>
				<div class="col-sm-7">
					<ul class="profile-info-sections">
						<li>
							<div class="profile-name">
								<strong>
									<a href="#"><?php echo $std_rec['name']; ?></a>
									<a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Online"></a>
								</strong>
								<span><a href="#">Powered By Indici-Edu</a></span>
							</div>
						</li>
						<!--<li>-->
						<!--	<div class="profile-stat">-->
						<!--		<h3> ----- </h3>-->
						<!--		<span><a href="#">Class - Section</a></span>-->
						<!--	</div>-->
						<!--</li>-->
						
						<li>
							<div class="profile-stat">
								<span><a href="#">Name: <b><?php echo $std_rec['name']; ?></b></a></span>
								<br>
								<span><a href="#">Department: <b><?php echo $section_ary['d']; ?></b></a></span>
								<br>
								<span><a href="#">Class: <b><?php echo $section_ary['c']; ?></b></a></span>
								<br>
								<span><a href="#">Section: <b><?php echo $section_ary['s']; ?></b></a></span>
								<br>
								<span><a href="#">Roll No: <b><?php echo $std_rec['roll']; ?></b></a></span>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-sm-3">
					<div class="profile-buttons">
						<!--<a href="#" class="btn btn-default">-->
						<!--	<i class="entypo-mail"></i>-->
						<!--</a>-->
					</div>
				</div>
			</header>
	</div>
</div>
<?php } ?>

<div class="col-md-12 mt-4">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading" style="background:#012b3c;">
            	<div class="panel-title">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('transfer_student');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url().'transfer_student/transfer_req_save/'.$section_id , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
    		        <div class="form-group">
    					<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('branch'); ?></label>
                        <div class="col-sm-8">
    						<select name="school_id" class="form-control">
                                <?php echo branches_option_list(); ?>
                            </select>
    					</div> 
    				</div>
    				<div class="form-group">
    					<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('reason');?></label>   
    					<div class="col-sm-8">
    						<textarea maxlength="1000" type="text" class="form-control"  name="reason" id="reason" oninput="count_value('reason','reason_count','1000')" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus></textarea>
    						<div id="reason_count" class="col-sm-12 "></div>
                            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>"/>
    					</div>
    				</div>
    				<div class="form-group">
    					<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('');?></label>    
    					<div class="col-sm-8">
    						<button type="submit" class="btn btn-primary" > <?php echo get_phrase('submit_request');?></button>
                        </div> 
                    </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
<?php } ?>