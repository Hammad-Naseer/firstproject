<style>
.table-sortable tbody tr {
/*cursor: move;*/
}
.table > tbody > tr > td {
     vertical-align: top;
}

button.modal_cancel_btn {
    position: relative;
    top: -5px;
    padding: 5px 4px 4px 6px;
    left: 6px;
}

.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    cursor: not-allowed;
    background-color: #fff !important;
}
.text_white{
     color:white !important;
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('reset_users_password');?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
    
    <ul class="nav nav-tabs" role="tablist">
    	<li class="nav-item">
    		<a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Reset Student Password</a>
    	</li>
    	<li class="nav-item">
    		<a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Reset Staff Password</a>
    	</li>
    </ul><!-- Tab panes -->
    <div class="tab-content">
    	<div class="tab-pane active" id="tabs-1" role="tabpanel">
    		<?php echo form_open(base_url().'user/get_user_login_details' , array('id'=>'get_user_login_details','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <div class="thisrow" style="padding:12px;">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                            <?php echo get_phrase('class-Section');?></label>
                            <select id="section_filter" class="form-control" name="section_id" class="form-control" required="">
                                <?php echo section_selector($section_id);?>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                            <?php echo get_phrase('student');?></label>
                            <select name="student_id" id="student_id" class="form-control" required>
                               <?php 
                                if(isset($student_select) && isset($student_select)){
            				   	    echo section_student($section_id,$student_select);
            				    }else{
            				    ?>
            				   	<option value=""><?php echo get_phrase("select_student");?></option>
            				  <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <button class='modal_save_btn' type="submit">Get Detail</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <!--<input type="submit" name="submit" value="submit" class="btn btn-primary pull-right">-->
                    </div>
                </div>
                <br>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="user_table_detail"></div>
                </div>
            <?php echo form_close();?>
    	</div>
    	<div class="tab-pane" id="tabs-2" role="tabpanel">
            <?php echo form_open(base_url().'user/get_user_login_details' , array('id'=>'get_staff_user_login_details','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <div class="thisrow" style="padding:12px;">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">
                            <?php echo get_phrase('select_staff');?></label>
                            <select id="staff_id" class="form-control" name="staff_id" class="form-control" required="">
                                <?php echo staff_list('',$staff_id); ?>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <button class='modal_save_btn' type="submit">Get Detail</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <!--<input type="submit" name="submit" value="submit" class="btn btn-primary pull-right">-->
                    </div>
                </div>
                <br>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="user_table_detail_staff"></div>
                </div>
            <?php echo form_close();?>
    	</div>
    </div>
</div>
<script>
    $("#section_filter").change(function() {
        var section_id = $(this).val();
        debugger;
        $("#icon").remove();
        $(this).after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id
            },
            url: "<?php echo base_url();?>transection_account/get_section_student",
            dataType: "html",
            success: function(response) {
                        debugger;
                $("#icon").remove();
                if (response != "") {
                    $("#student_id").html(response);
                }
                if (response == "") {
                    $("#student_id").html('<select><option value=""><?php echo get_phrase('select_student'); ?></option></select>');
                }
            }
        });
    });
    
    // Get Student Details
    $("#get_user_login_details").on('submit',function(e){
       e.preventDefault();
       $.ajax({
            type: 'POST',
            url: "<?php echo base_url();?>user/get_user_login_details/",
            dataType: "html",
            data:new FormData(this),  
            contentType: false,  
            cache: false,  
            processData:false,
            success: function(response) {
                if (response != "") {
                    $("#user_table_detail").html(response);
                    $(".modal_save_btn").removeAttr("disabled");
                }
            }
        });
    });
    
    // Get Staff Details
    $("#get_staff_user_login_details").on('submit',function(e){
       e.preventDefault();
       $.ajax({
            type: 'POST',
            url: "<?php echo base_url();?>user/get_staff_user_login_details/",
            dataType: "html",
            data:new FormData(this),  
            contentType: false,  
            cache: false,  
            processData:false,
            success: function(response) {
                if (response != "") {
                    $("#user_table_detail_staff").html(response);
                    $(".modal_save_btn").removeAttr("disabled");
                }
            }
        });
    });
    
</script>
