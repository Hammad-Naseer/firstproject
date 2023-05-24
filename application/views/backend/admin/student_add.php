<?php if (right_granted('newadmission_manage')) { ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
              Student Admission Form
        </h3>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3"> 
                <!--style="pointer-events: none !important;cursor: default !important;"-->
                    <a href="#step-1" data-step="1" data-position="top" data-intro="Add Student's Official Details" type="button" class="btn-success btn-circle anchor_wizard">1</a>
                    <p><small><?php echo get_phrase('official_detail');?></small></p>
                </div>
                <div class="stepwizard-step col-xs-3"> 
                    <a href="#step-2" data-step="2" data-position="top" data-intro="Add Student's More Information" type="button" class="btn-default btn-circle anchor_wizard" disabled="disabled" >2</a>
                    <p><small><?php echo get_phrase('student_more_information');?></small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="#step-3" data-step="3" data-position="top" data-intro="Add Student's Parent Information" type="button" class="btn-default btn-circle anchor_wizard" disabled="disabled">3</a>
                    <p><small><?php echo get_phrase('parent_information');?></small></p>
                </div>
                <div class="stepwizard-step col-xs-3"> 
                    <a href="#step-4" data-step="4" data-position="top" data-intro="Add Student's Guardian Information" type="button" class="btn-default btn-circle anchor_wizard" disabled="disabled">4</a>
                    <p><small><?php echo get_phrase('guardian_information');?></small></p>
                </div>
            </div>
        </div>
        <form action="<?=base_url()?>c_student/student/create/" method="post" id="student_add_edit" role="form" enctype="multipart/form-data">
                <div class="panel panel-primary setup-content" id="step-1">
                    <div class="panel-heading">
                         <h3 class="panel-title"><b><?php echo get_phrase('official_details');?></b></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                            <?php echo get_phrase('GR_number');?> <span class="red"> * </span></label>
                            <?php
                                $get_max_gr_no = $this->db->query("SELECT MAX(gr_no) AS GR FROM ".get_school_db().".student WHERE school_id = '".$_SESSION['school_id']."'");
                                $get_gr_row = $get_max_gr_no->row();
                                $get_gr = $get_gr_row->GR;
                            ?>
                            <input type="number" class="form-control" name="gr_number" value="<?=$get_gr+1;?>" readonly="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label class="control-label">
                                <?php echo get_phrase('class-Section');?><span class="red"> * </span></label>
                                <label id="section_id1_selection"></label>
                                <select id="section_id1" class="selectpicker form-control wizard_validate" name="section_id" required="required" class="form-control" required="">
                                    <?php echo section_selector();?>
                                </select>
                                <script>
                                    $(document).ready(function() {
                                        $('.selectpicker').on('change', function() {
                                            var id = $(this).attr('id');
                                            var selected = $('#' + id + ' :selected');
                                            var group = selected.parent().attr('label');
                                            $('#' + id + '_selection').text(group);
                                        });
                                    });
                                </script>
                                <div id="section-err"></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('form_number');?> <span class="red"> * </span></label>
                                <input maxlength="50" type="text" class="form-control" name="form_number" value="" required="required">
                        </div>
                        
                        <h3 class="mt-4"><b><?php echo get_phrase('basic_information');?></b></h3>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('full_name');?><span class="red"> * </span></label>
                                <input maxlength="100" type="text" class="form-control" name="name" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus required="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('date_of_birth');?> <span class="red"> * </span></label>
                                <input type="text" id="mydob" class="form-control datepicker" name="birthday" value="" data-start-view="2"  data-format="dd/mm/yyyy" required="">
                                <!--onfocus="this.blur()"-->
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('Gender');?><span class="red"> * </span></label>
                                <select name="sex" class="form-control wizard_validate" required="required">
                                    <option value="">
                                        <?php echo get_phrase('select');?>
                                    </option>
                                    <option value="male">
                                        <?php echo get_phrase('male');?>
                                    </option>
                                    <option value="female">
                                        <?php echo get_phrase('female');?>
                                    </option>
                                </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('religion');?><span class="red"> * </span></label>
                                <select name="religion" class="form-control wizard_validate" required>
                                    <option value="">
                                        <?php echo get_phrase('select');?>
                                    </option>
                                    <option value="1">
                                        <?php echo get_phrase('muslim');?>
                                    </option>
                                    <option value="2">
                                        <?php echo get_phrase('christian');?>
                                    </option>
                                    <option value="3">
                                        <?php echo get_phrase('Hindu');?>
                                    </option>
                                    <option value="4">
                                        <?php echo get_phrase('sikh');?>
                                    </option>
                                    <option value="5">
                                        <?php echo get_phrase('other');?>
                                    </option>
                                </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('mobile_no');?><span class="red"> * </span></label>
                                <input required="" maxlength="15" minlength="11" type="text" class="form-control" name="mob_num" value="" placeholder="Minimum 11 digits without space or dashes ( - )">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('emergency_no');?><span class="red"> * </span></label>
                                <input maxlength="20" type="text" class="form-control" name="emg_num" value="" required="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('previous_school');?></label>
                                <input type="text" class="form-control" name="prev_school" value="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('student_intrested_activities');?></label>
                                <input type="text" class="form-control" name="activities" value="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('select');?> <span class="red"> * </span></label>
                                <select name="user_group_id" class="form-control wizard_validate" required="">
                                    <?php echo user_group_option_list();?>
                                </select>
                        </div>
                        <div class="row row-m-0 col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('postal_address');?>
                            </label>
                                <textarea rows="4" id="address_cont" class="form-control" name="address" onkeyup="count_value('address_cont','address_count','200')" maxlength="200"></textarea>
                                <div id="address_count"></div>
                        </div>
                        <div class="form-group" style="height: 30px;">
                            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary setup-content" id="step-2">
                    <div class="panel-heading mb-4">
                         <h3 class="panel-title"><b>Student More Information</b></h3>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('student_category');?>
                        </label>
                        <select name="student_category" class="form-control">
                        	<?php echo student_category();?>	
                        </select>	
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('nationality');?>
                        </label>
                        <select name="nationality" class="form-control">
                        	<?php echo country_option_list();?>	
                        </select>	
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('id_type');?><span class="red"> * </span>
                        </label>
                            <?php echo id_type_list('student_id_type','form-control student_id_type  wizard_validate');?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('b_form_id_no');?><span class="red"> * </span>
                        </label>
                            <input type="text" class="form-control cnic_mask" required id="s_cnic" name="form_b" placeholder="National Id Card No" onchange="get_cnic('s_cnic','s','student')" value=""  maxlength="30" onkeyup="nospaces(this)">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('id_file');?>
                        </label>
                            <input type="file" name="form_b_file" value="">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('country');?>
                        </label>
                            <select name="country" class="form-control country">
                                <?php echo country_option_list(); ?>
                            </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('provience');?>
                        </label>
                        <div class="provience_html">
                            <select name="provience" class="form-control provience">
                                <option><?php echo get_phrase('select'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('city');?>
                        </label>
                        <div class="city_html">
                            <select name="city" class="form-control city">
                                <option><?php echo get_phrase('select'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('location');?>
                        </label>
                        <div class="city_html">
                            <select name="location" class="form-control location">
                            </select>
                        </div>
                    </div>
                    <div class="row row-m-0 col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('permanent_address');?>
                        </label>
                            <textarea rows="4" id="address_cont_p" class="form-control" name="p_address" onkeyup="count_value('address_cont_p','address_count_p','200')" maxlength="200"></textarea>
                            <div id="address_count_p"></div>
                    </div>
                    <div class="row row-m-0">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="field-2" class="control-label">
                            <?php echo get_phrase('residence_#');?>
                        </label>
                        <input maxlength="20" type="text" class="form-control" name="phone" value="">
                    </div>   
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('email');?>
                            </label>
                                <input maxlength="50" id="email" type="text" class="form-control" name="email" value="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('blood_group');?>
                            </label>
                                <input maxlength="10" type="text" class="form-control" name="bd_group" value="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('disability(if any)');?>
                            </label>
                                <input maxlength="100" type="text" class="form-control" name="disability" value="">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('photo');?>
                            </label>
                            <br>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                        <img src="<?php echo base_url();?>uploads/default.png" alt="...">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                        <span class="fileinput-new"><?php echo get_phrase('select_image'); ?></span>
                                        <span class="fileinput-exists"><?php echo get_phrase('change'); ?></span>
                                        <input type="file" name="image" value="" accept="image/*"
                                        id="sysem_file"
                                        onchange="file_validate('sysem_file','img','img_f_msg')">
                                        </span>
                                        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><?php echo get_phrase('remove'); ?></a>
                                    </div>
                                </div>
                                <span style="color: red;" id="img_f_msg"></span>
                        </div>
                    </div>
                    <div class="form-group" style="height: 30px;">
                        <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                    </div>
                </div>
                <div class="panel panel-primary setup-content" id="step-3">
                    <div class="panel-heading">
                         <h3 class="panel-title">Parents Information</h3>
                    </div>
                    <div class="panel-body">
                        <h3 class="mt-4"><b><?php echo get_phrase('father_information');?></b></h3>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <label for="field-1" class="control-label">
                                <?php echo get_phrase('father_id_type');?> <span class="red"> * </span> </label>
                                <?php echo id_type_list('id_type_f','form-control wizard_validate');?>
                            </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <label for="field-1" class="control-label">
                                    <?php echo get_phrase('father_id_no');?><span class="red"> * </span></label>
                                    
                                    <!--working-->
                                    <input minlength="3" maxlength="30" type="text" class="form-control cnic_mask fcnic" onkeyup="get_cnic('f_cnic','f','student_parent','rec_1')" id="f_cnic" name="f_cnic" required="" autofocus onkeyup="nospaces(this)">
                            </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <label for="field-1" class="control-label">
                                    <?php echo get_phrase('father_name');?><span class="red"> * </span></label>
                                    <input maxlength="250" required="" type="text" class="form-control fif" id="f_name" name="f_name" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus readonly>
                            </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('father_id_file');?>
                                </label>
                                    <input type="file" id="f_cnic_attach" name="f_cnic_attach" onchange="file_validate('f_cnic_attach','img','img_f_msg')" value="">
                                    <span style="color: green;">
                                    <?php echo get_phrase('allowed_file_size');?>: 200kb, <?php echo get_phrase('allowed_file_types');?>: png, jpg, jpeg </span>
                                                       <br />							
                                    <span style="color: red;" id="img_f_msg"></span>
                                </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('Father_contact_number');?>
                                </label>
                                    <input maxlength="20" type="text" class="form-control fif" id="f_num" name="f_num" value="" data-start-view="2" readonly>
                            </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <label for="field-2" class="control-label">
                                    <?php echo get_phrase('father_occupation');?>
                                </label>
                                    <input maxlength="250" type="text" class="form-control fif" id="f_ocu" name="f_ocu" value="" data-start-view="2" readonly>
                            </div>
                        <div class="row row-m-0 col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('father_nationality');?>
                            </label>
                            <select name="nationality_f" class="form-control">
                            	<?php echo country_option_list();?>
                            </select>
                        </div>
                        <br>  
                        <h3 class="mt-4"><b><?php echo get_phrase('mother_information');?></b></h3>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('mother_id_type');?>
                            </label>
                                <?php echo id_type_list_guardian('id_type_m','form-control');?>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <label for="field-1" class="control-label">
                                            <?php echo get_phrase('mother_id_no');?>
                                        </label>
                                            <input maxlength="30" type="text" class="form-control cnic_mask mcnic" onkeyup="get_cnic('m_cnic','m','student_parent','rec_2')" id="m_cnic" name="m_cnic" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus onkeyup="nospaces(this)">
                                    </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <label for="field-1" class="control-label">
                                            <?php echo get_phrase('mother_name');?>
                                        </label>
                                            <input maxlength="250" type="text" class="form-control mif" id="m_name" name="m_name" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus readonly>
                                    </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <label for="field-2" class="control-label">
                                            <?php echo get_phrase('mother_id_file');?>
                                        </label>
                                            <input type="file" id="m_cnic_attach" name="m_cnic_attach" onchange="file_validate('m_cnic_attach','img','img_m_msg')" value="">
                                            <span style="color: green;"><?php echo get_phrase('allowed_file_size'); ?>: 200kb, <?php echo get_phrase('allowed_file_types'); ?>: png, jpg, jpeg </span>
                                                               <br />
                                            <span style="color: red;" id="img_m_msg"></span>
                                    </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <label for="field-2" class="control-label">
                                            <?php echo get_phrase('mother_contact_number');?>
                                        </label>
                                            <input maxlength="20" maxlength="" type="text" class="form-control mif" id="m_num" name="m_num" value="" data-start-view="2" readonly>
                                    </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <label for="field-2" class="control-label">
                                            <?php echo get_phrase('mother_occupation');?>
                                        </label>
                                            <input maxlength="250" type="text" class="form-control mif" id="m_ocu" name="m_ocu" value="" data-start-view="2" readonly>
                                    </div>
                        <div class="row row-m-0 col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="field-2" class="control-label">
                                            <?php echo get_phrase('nationality_mother');?>
                                        </label>
                                            <select name="nationality_m" class="form-control">
                                            	<?php echo country_option_list();?>
                                            </select>
                                    </div>
                        <div class="form-group" style="height: 30px;">
                            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary setup-content" id="step-4">
                    <div class="panel-heading">
                         <h3 class="panel-title">Guardian Information</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="field-1" class="control-label">
                                <?php echo get_phrase('guardian_id_type');?>
                            </label>
                                <?php echo id_type_list_guardian('id_type_g','form-control');?>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                    <label for="field-1" class="control-label">
                                        <?php echo get_phrase('guardian_id_no');?>
                                    </label>
                                        <input maxlength="30" type="text" class="form-control cnic_mask gcnic" id="g_cnic" onkeyup="get_cnic('g_cnic','g','student_parent','rec_3')" name="g_cnic" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus onkeyup="nospaces(this)">
                                </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                    <label for="field-1" class="control-label">
                                        <?php echo get_phrase('guardian_name');?>
                                    </label>
                                        <input maxlength="250" type="text" class="form-control gif" id="g_name" name="g_name" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus readonly>
                                </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                    <label for="field-2" class="control-label">
                                        <?php echo get_phrase('guardian_id_file');?>
                                    </label>
                                        <input type="file" id="g_cnic_attach" name="g_cnic_attach" onchange="file_validate('g_cnic_attach','img','img_g_msg')" value="">
                                        <span style="color: green;"><?php echo get_phrase('allowed_file_size'); ?>: 200kb, <?php echo get_phrase('allowed_file_types'); ?>: png, jpg, jpeg </span>
                                                       <br />
                                        <span style="color: red;" id="img_g_msg"></span>
                                </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                    <label for="field-2" class="control-label">
                                        <?php echo get_phrase('guardian_contact_number');?>
                                    </label>
                                        <input maxlength="20" type="text" class="form-control gif" id="g_num" name="g_num" value="" data-start-view="2" readonly>
                                </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                    <label for="field-2" class="control-label">
                                        <?php echo get_phrase('guardian_occupation');?>
                                    </label>
                                        <input maxlength="20" type="text" class="form-control gif" id="g_ocu" name="g_ocu" value="" data-start-view="2" readonly>
                                </div>
                        <div class="row row-m-0 col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                                    <label for="field-2" class="control-label">
                                        <?php echo get_phrase('guardian_nationality');?>
                                    </label>
                                      <select name="nationality_g" class="form-control">
                                        	<?php echo country_option_list();?>
                                        </select>  
                                </div>
                        <div class="form-group" style="height: 30px;">
                            <button class="btn btn-success pull-right" id="btn1" type="submit">Finish!</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
<script>
 $('.student_id_type').change(function() {
        var form_value = $(this).find(":selected").val();
        
        if(form_value == "4"){
        var system_generated_form_id = '<?php echo system_generated_form_id();?>'
            $('#s_cnic').val(system_generated_form_id );
            get_cnic("system_generated,"+system_generated_form_id, "s", "student")
            $("input#s_cnic").attr('disabled','disabled');
        }else{
            $('#s_cnic').val("");
            $("input#s_cnic").removeAttr('disabled');
        }
        
    });
    $('#email').change(function() {
        get_email();
    });
    function get_email() {
        $('#email').after('<div id="icon" class="loader_small"></div>');
        var email = $('#email').val();
        $.ajax({
            type: 'POST',
            data: {
                email: email
            },
            url: "<?php echo base_url();?>admin/call_function",
            dataType: "html",
            success: function(response) {
                if ($.trim(response) == 'yes') {

                    $("#btn1").attr('disabled', 'true');
                    $("#email").css('border', '1px solid red');
                    $("#icon").remove();

                    if ($('#message').html() == undefined) {
                        $("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_already_exist'); ?></p>');
                    }
                } else {

                    $("#btn1").removeAttr('disabled');
                    $("#email").css('border', '1px solid green');
                    $("#icon").remove();
                    $("#message").remove();
                }
            }
        });
    }
    function get_cnic(cnic_n, type_n, table_name, detail_field) {
       
		var cinc;
		
		
		if(cnic_n.indexOf('system_generated') != -1 ){
		    cnic_value_split = cnic_n.split(",");
		     cnic  = cnic_value_split[1];
		}else{
		      cnic = $('#' + cnic_n).val();
		}
	
        $("#message_scnic").remove();
        $('#' + cnic_n + type_n).remove();
        $('#' + cnic_n).after('<div id="' + cnic_n + type_n + '" class="loader_small"></div>');
        
        $.ajax({
            type: 'POST',
            data: {
                cnic: cnic,
                type_n: type_n,
                table_name: table_name
            },
           url: "<?php echo base_url();?>admin/get_cnic_stu",
            dataType: "json",
            success: function(response) {
                $('#' + cnic_n + type_n).remove();
                if (type_n == 's') {
                    if ($.trim(response.value) == 'no') {
                    	
                        $("#btn1").removeAttr('disabled');
                        $("#" + cnic_n).css('border', '1px solid green');
                        $("#message_scnic").remove();
                    } else {
                        $("#btn1").attr('disabled', 'true');
                        $("#" + cnic_n).css('border', '1px solid red');

                        if ($('#message_scnic').html() == undefined) {
                            $("#" + cnic_n).before('<p id="message_scnic" style="color:red;"><?php echo get_phrase('id_already_exist'); ?></p>');
                        }
                    }

                    $("#" + cnic_n + type_n).remove();
                } else if (type_n == 'f') {

                    if ($.trim(response.value) == 'no') {
                        $('#f_name').removeAttr("readonly").val('');
                        $('#f_num').removeAttr("readonly").val('');
                        $('#f_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#f_name').val(response[0].p_name).attr("readonly", "true");
                        $('#f_num').val(response[0].contact).attr("readonly", "true");
                        $('#f_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();

                } else if (type_n == 'm') {

                    if ($.trim(response.value) == 'no') {
                        $('#m_name').removeAttr("readonly").val('');
                        $('#m_num').removeAttr("readonly").val('');
                        $('#m_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#m_name').val(response[0].p_name).attr("readonly", "true");
                        $('#m_num').val(response[0].contact).attr("readonly", "true");
                        $('#m_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();

                } else if (type_n == 'g') {

                    if ($.trim(response.value) == 'no') {
                        $('#g_name').removeAttr("readonly").val('');
                        $('#g_num').removeAttr("readonly").val('');
                        $('#g_ocu').removeAttr("readonly").val('');

                    } else {
                        $('#g_name').val(response[0].p_name).attr("readonly", "true");
                        $('#g_num').val(response[0].contact).attr("readonly", "true");
                        $('#g_ocu').val(response[0].occupation).attr("readonly", "true");
                    }

                    $("#" + cnic_n + type_n).remove();
                }
            }

        });


    }
    function get_student(cnic, detail_field) {
        $('#' + detail_field).html(' ');
        $.ajax({
            type: 'POST',
            data: {
                cnic: cnic
            },
            url: "<?php echo base_url();?>admin/get_student_new",
            dataType: "html",
            success: function(response) {
                $('#' + detail_field).html(response);
            }
        });
    }

    $(document).ready(function() {
        $("#departments_id").change(function() {
            var dep_id = $(this).val();

            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    dep_id: dep_id
                },
                url: "<?php echo base_url();?>c_student/get_class",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $("#class_id").html(response);
                    $("#section_id").html("<option><?php echo get_phrase('select_section'); ?></option>");
                }
            });



        });

        $("#class_id").change(function() {
            var class_id = $(this).val();

            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    class_id: class_id
                },
                url: "<?php echo base_url();?>c_student/get_section",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $("#section_id").html(response);
                }
            });



        });

        $(".country").change(function() {
            var loc_id = $(this).val();
            var send_location = 'provience';
            if (loc_id == "") {
            } else {
                get_location(loc_id, send_location);
                $(".provience").html("<option><?php echo get_phrase('select_province'); ?></option>");
                $(".city").html("<option><?php echo get_phrase('select_city'); ?></option>");
                $(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
            }


        });


        $(".provience").change(function() {
            var loc_id = $(this).val();
            var send_location = 'city';
            if (loc_id == "") {
            } else {

                get_location(loc_id, send_location);
                $(".city").html("<option><?php echo get_phrase('select_city'); ?></option>");
                $(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
            }
        });
        $(".city").change(function() {

            var loc_id = $(this).val();
            var send_location = 'location';


            if (loc_id == "") {

            } else {

                get_location(loc_id, send_location);
                $(".location").html("<option><?php echo get_phrase('select_location'); ?></option>");
            }
        });

    });
    function get_location(loc_id, send_location) {
        $("#loading").remove();
        $('.' + send_location).after("<div id='loading' class='loader_small'></div>");

        $.ajax({
            type: 'POST',
            data: {
                loc_id: loc_id,
                send_location: send_location
            },
            url: "<?php echo base_url();?>c_student/get_location",
            dataType: "html",
            success: function(response) {
                $('.' + send_location).html(response);
                $("#loading").remove();
            }
        });
    }
    
    
</script>
<?php } ?>