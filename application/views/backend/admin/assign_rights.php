<style>
    .modules-listing {
        text-align: left;
		padding-left:0px;
    }

    .modules-listing li ul {
        padding-left: 15px;
    }

    .modules-listing .mode {
        background: #eeeeee;
        display: block;
        padding: 5px 10px;
        margin: 15px 0px;
    }

    .modules-listing .mode_top {
        background: #8c8989;
        display: block;
        color:#f9f7f7;
        padding: 5px 10px;
        margin: 15px 0px;
    }

    .modules-listing .mode b {
        padding-right: 20px;
    }

    .modules-listing li ul li {
        display: inline-block;
        margin-right: 40px;
        text-transform: capitalize;
    }

    .modules-listing li ul li input {
        margin-right: 10px;
    }

    .modules-listing input[type="checkbox"] {
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        border: 1px solid #c8c6c6;
        width: 12px;
        height: 12px;
        border-radius: 2px;
        margin: 0 5px 0 0;
        cursor: pointer;
    }

    .modules-listing input[type="checkbox"]:focus {
        outline: none;
    }

    .modules-listing input[type="checkbox"]:checked {
        background: #5f5353;
        background-size: 20%;
    }

    .content {
        min-height: 0px;
        padding: 0px !important;
    }
	#assign-rights .box-body{
		padding:0px;
	}
	.assign_count{
	    float: right;
        padding: 15px;
        background: #3c8dbc;
        margin: 5px;
        border: 1px solid;
        color: white;
        margin-right: 115px;
	}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="<?=base_url()?>user/user_groups" id="chalan_tour" onclick="javascript:introJs().start();" href="<?php echo base_url();?>/user/user_groups" class="btn btn-primary">
            <?php echo get_phrase('back');?>
        </a>
        <h3 class="system_name inline">
            <?php $arr = $this->db->query("select * from ".get_school_db().".user_group where user_group_id = $user_group_id ")->result_array();?>
            <?php echo get_phrase('assign_right_to'); ?> (<?php echo $arr[0]['title']; ?>)
        </h3>
    </div>
</div>

<div class="col-lg-12 col-sm-12">
    <div class="tab-pane box" id="add">
    <form name="assign-rights" id="assign-rights">
        <div class="box-body">
            <div class="bs-example">
                <div class="panel-group" id="accordion">
                    
                    <?php if($user_group_type == 1){ ?>
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapse_admin">
                              <span class="glyphicon glyphicon-chevron-down"></span> 
                              ADMIN RIGHTS
                          </a>
                        </h4>
                      </div>
                      <div id="collapse_admin" class="panel-collapse">
                        <div class="panel-body">
                            
                            <input type="checkbox" id="admin_rights" class="adminAllCheckBox">
                            <label style="font-weight:normal;">select_all</label>
                            
                            <div class="form-group">
                            <ul class="modules-listing">
                                <?php 
                                $admin_assigned = 0;
                                $admin_notassigned = 0;
                                foreach($package_rights as $mod_key => $action_data)
                                {
                                ?>
                                    <li style="list-style: none">
                                        <div class="mode_top">
                                            <b><?php echo ucfirst($mod_key)?></b>
                                        </div>
                                        <ul class="modules-listing">
                                            <?php
                                            
                                            foreach($action_data as $key => $mod)
                                            {
                                            ?>
                                                <fieldset module="<?php echo $mod[0]['module_id']?>">
                                                <li style="list-style: none">
                                                        <div class="mycbox">
                                                            <div class="mode">
                                                                <b><?php echo ucfirst($key)?></b>
                                                                
                                                                <input type="checkbox" id="selectall<?php echo $mod[0]['module_id']?>" name="module[]" class="parentCheckBox adminMainCheckBox" module="<?php echo $mod[0]['module_id']?>" value="<?php echo $mod[0]['module_id']?>">
                                                                <label for="module[]" style="font-weight:normal;">select_all</label>
                                                            </div>
                                                            <ul>
                                                                <?php
                                                                
                                                                foreach($mod as $act)
                                                                {
                                                                ?>
                                                                    <li class="nested content" style="list-style: none">
                                                                        <input type="checkbox" name="actions[]" <?php $style = ""; if(in_array($act[ 'action_id'],$sel2)){echo "checked=checked"; $admin_assigned++; $style = "color:green;"; }else{$admin_notassigned++; $style = "color:red;";}?> class="childCheckBox adminSubCheckBox" module="<?php echo $mod[0]['module_id']?>" value="<?php echo $act['action_id']?>" id="actions">
                                                                        <span style="<?php echo $style;?>"><?php echo $act['action_title']?></span>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </fieldset>
                                            <?php    
                                            }?>
                                        </ul>
                                    </li>
                                <?php    
                                }
                                ?>
                            </ul>
                            </div>
                            <span class="assign_count">
                                <?php echo "Admin Assigned = ".$admin_assigned."<br>Admin Not Assigned = ".$admin_notassigned; ?>
                            </span>
                        </div>
                      </div>
                    </div>
                    
                    <?php } if($user_group_type == 3){ ?>
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collaspse_teacher">
                              <span class="glyphicon glyphicon-chevron-down"></span> 
                              TEACHER RIGHTS
                           </a>
                        </h4>
                      </div>
                      <div id="collaspse_teacher" class="panel-collapse">
                        <div class="panel-body">
                            
                         <input type="checkbox" id="teacher_rights" class="teacherAllCheckBox">
                         <label style="font-weight:normal;">select_all</label>         
                            
                            
                         <div class="form-group">
                            <ul class="modules-listing">
                                <?php 
                                $teacher_assigned = 0;
                                $teacher_notassigned = 0;
                                foreach($teacher_package_rights as $mod_key => $action_data)
                                {
                                ?>
                                    <li style="list-style: none">
                                        <div class="mode_top">
                                            <b><?php echo ucfirst($mod_key)?></b>
                                        </div>
                                        <ul class="modules-listing">
                                            <?php
                                            
                                            foreach($action_data as $key => $mod)
                                            {
                                            ?>
                                                <fieldset module="<?php echo $mod[0]['module_id']?>">
                                                <li style="list-style: none">
                                                        <div class="mycbox">
                                                            <div class="mode">
                                                                <b><?php echo ucfirst($key)?></b>
                                                                
                                                                <input type="checkbox" id="selectall<?php echo $mod[0]['module_id']?>" name="module[]" class="parentCheckBox teacherMainCheckBox" module="<?php echo $mod[0]['module_id']?>" value="<?php echo $mod[0]['module_id']?>">
                                                                <label for="module[]" style="font-weight:normal;">select_all</label>
                                                            </div>
                                                            <ul>
                                                                <?php
                                                                
                                                                foreach($mod as $act)
                                                                {
                                                                ?>
                                                                    <li class="nested content" style="list-style: none">
                                                                        <input type="checkbox" name="actions[]" <?php $style = ""; if(in_array($act[ 'action_id'],$sel3)){echo "checked=checked"; $teacher_assigned++; $style = "color:green;"; }else{$teacher_notassigned++; $style = "color:red;";}?> class="childCheckBox teacherSubCheckBox" module="<?php echo $mod[0]['module_id']?>" value="<?php echo $act['action_id']?>" id="actions">
                                                                        <span style="<?php echo $style;?>"><?php echo $act['action_title']?></span>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </fieldset>
                                            <?php    
                                            }?>
                                        </ul>
                                    </li>
                                <?php    
                                }
                                ?>
                            </ul>
                            </div>
                            <span class="assign_count">
                                <?php echo "Teacher Assigned = ".$teacher_assigned."<br>Teacher Not Assigned = ".$teacher_notassigned; ?>
                            </span>
                        </div>
                      </div>
                    </div>
                    
                    <?php } if($user_group_type == 6){ ?>
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapse_student">
                              <span class="glyphicon glyphicon-chevron-down"></span> 
                              STUDENT RIGHTS
                          </a>
                        </h4>
                      </div>
                      <div id="collapse_student" class="panel-collapse">
                        <div class="panel-body">
                            
                            
                            <input type="checkbox" id="student_rights" class="studentAllCheckBox">
                            <label style="font-weight:normal;">select_all</label>
                            
                            
                            <div class="form-group">
                            <ul class="modules-listing">
                                <?php 
                                $student_assigned = 0;
                                $student_notassigned = 0;
                                foreach($student_package_rights as $mod_key => $action_data)
                                {
                                ?>
                                    <li style="list-style: none">
                                        <div class="mode_top">
                                            <b><?php echo ucfirst($mod_key)?></b>
                                        </div>
                                        <ul class="modules-listing">
                                            <?php
                                            
                                            foreach($action_data as $key => $mod)
                                            {
                                            ?>
                                                <fieldset module="<?php echo $mod[0]['module_id']?>">
                                                <li style="list-style: none">
                                                        <div class="mycbox">
                                                            <div class="mode">
                                                                <b><?php echo ucfirst($key)?></b>
                                                                
                                                                <input type="checkbox" id="selectall<?php echo $mod[0]['module_id']?>" name="module[]" class="parentCheckBox studentMainCheckBox" module="<?php echo $mod[0]['module_id']?>" value="<?php echo $mod[0]['module_id']?>">
                                                                <label for="module[]" style="font-weight:normal;">select_all</label>
                                                            </div>
                                                            <ul>
                                                                <?php
                                                                
                                                                foreach($mod as $act)
                                                                {
                                                                ?>
                                                                    <li class="nested content" style="list-style: none">
                                                                        <input type="checkbox" name="actions[]" <?php $style = ""; if(in_array($act[ 'action_id'],$sel4)){echo "checked=checked"; $student_assigned++; $style = "color:green;"; }else{$student_notassigned++; $style = "color:red;";}?> class="childCheckBox studentSubCheckBox" module="<?php echo $mod[0]['module_id']?>" value="<?php echo $act['action_id']?>" id="actions">
                                                                        <span style="<?php echo $style;?>"><?php echo $act['action_title']?></span>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </fieldset>
                                            <?php    
                                            }?>
                                        </ul>
                                    </li>
                                <?php    
                                }
                                ?>
                            </ul>
                            </div>
                            <span class="assign_count">
                                <?php echo "Student Assigned = ".$student_assigned."<br>Student Not Assigned = ".$student_notassigned; ?>
                            </span>
                        </div>
                      </div>
                    </div>
                    
                    
                    <?php } if($user_group_type == 4){ ?>
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapse_parent">
                              <span class="glyphicon glyphicon-chevron-down"></span> 
                              PARENT RIGHTS
                          </a>
                        </h4>
                      </div>
                      <div id="collapse_parent" class="panel-collapse">
                        <div class="panel-body">
                            
                        <input type="checkbox" id="parent_rights" class="parentAllCheckBox">
                        <label style="font-weight:normal;">select_all</label>    
                            
                            
                            
                        <div class="form-group">
                            <ul class="modules-listing">
                                <?php 
                                $parent_assigned = 0;
                                $parent_notassigned = 0;
                                foreach($parent_package_rights as $mod_key => $action_data)
                                {
                                ?>
                                    <li style="list-style: none">
                                        <div class="mode_top">
                                            <b><?php echo ucfirst($mod_key)?></b>
                                        </div>
                                        <ul class="modules-listing">
                                            <?php
                                            
                                            foreach($action_data as $key => $mod)
                                            {
                                            ?>
                                                <fieldset module="<?php echo $mod[0]['module_id']?>">
                                                <li style="list-style: none">
                                                        <div class="mycbox">
                                                            <div class="mode">
                                                                <b><?php echo ucfirst($key)?></b>
                                                                
                                                                <input type="checkbox" id="selectall<?php echo $mod[0]['module_id']?>" name="module[]" class="parentCheckBox parentMainCheckBox" module="<?php echo $mod[0]['module_id']?>" value="<?php echo $mod[0]['module_id']?>">
                                                                <label for="module[]" style="font-weight:normal;">select_all</label>
                                                            </div>
                                                            <ul>
                                                                <?php
                                                                
                                                                foreach($mod as $act)
                                                                {
                                                                ?>
                                                                    <li class="nested content" style="list-style: none">
                                                                        <input type="checkbox" name="actions[]" <?php $style = ""; if(in_array($act[ 'action_id'],$sel5)){echo "checked=checked"; $parent_assigned++; $style = "color:green;"; }else{$parent_notassigned++; $style = "color:red;";}?> class="childCheckBox parentSubCheckBox" module="<?php echo $mod[0]['module_id']?>" value="<?php echo $act['action_id']?>" id="actions">
                                                                        <span style="<?php echo $style;?>"><?php echo $act['action_title']?></span>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </fieldset>
                                            <?php    
                                            }?>
                                        </ul>
                                    </li>
                                <?php    
                                }
                                ?>
                            </ul>
                            </div>
                            <span class="assign_count">
                                <?php echo "Parent Assigned = ".$parent_assigned."<br>Parent Not Assigned = ".$parent_notassigned; ?>
                            </span>
                        </div>
                      </div>
                    </div>
                    <?php }?>
                    
                </div>
            </div>
            
        </div>   

        <div class="box-footer text-right">
            <button type="button" id="submit-btn" name="submit"  class="modal_save_btn">
                <?php echo get_phrase('submit'); ?>
            </button>
        </div>

    </form>
</div>
</div>
<script>
$(document).ready(function() {

    $('input[class^="parentCheckBox"]').click(function() {
        var str = $(this).attr('id');
        var m_id = $(this).attr('value');
        $('.act' + m_id).prop('checked', true);
        if ($('.' + m_id).attr('checked') == true) {
            $('.act' + m_id).removeAttr('checked', true);
            $('.' + m_id).removeAttr('checked', false);
        }

    });
    
    
  



  
    $('#admin_rights').click(function() {
        if($('#admin_rights:checked').length == 1 ){
            $('.adminMainCheckBox').each(function(){
                 $(this).prop('checked', true);
            });
            $('.adminSubCheckBox').each(function(){
                 $(this).prop('checked', true);
            });
        }
        else
        {
            $('.adminMainCheckBox').each(function(){
                 $(this).removeAttr('checked', false);
            });
            $('.adminSubCheckBox').each(function(){
                 $(this).removeAttr('checked', false);
            });
        }
        
    });
    
    $('#teacher_rights').click(function() {
        if($('#teacher_rights:checked').length == 1 ){
            $('.teacherMainCheckBox').each(function(){
                 $(this).prop('checked', true);
            });
            $('.teacherSubCheckBox').each(function(){
                 $(this).prop('checked', true);
            });
        }
        else
        {
            $('.teacherMainCheckBox').each(function(){
                 $(this).removeAttr('checked', false);
            });
            $('.teacherSubCheckBox').each(function(){
                 $(this).removeAttr('checked', false);
            });
        }
    });
    
    $('#student_rights').click(function() {
        if($('#student_rights:checked').length == 1 ){
            $('.studentMainCheckBox').each(function(){
                 $(this).prop('checked', true);
            });
            $('.studentSubCheckBox').each(function(){
                 $(this).prop('checked', true);
            });
        }
        else
        {
            $('.studentMainCheckBox').each(function(){
                 $(this).removeAttr('checked', false);
            });
            $('.studentSubCheckBox').each(function(){
                 $(this).removeAttr('checked', false);
            });
        }
    });
    
    $('#parent_rights').click(function() {
        if($('#parent_rights:checked').length == 1 ){
            $('.parentMainCheckBox').each(function(){
                 $(this).prop('checked', true);
            });
            $('.parentSubCheckBox').each(function(){
                 $(this).prop('checked', true);
            });
        }
        else
        {
            $('.parentMainCheckBox').each(function(){
                 $(this).removeAttr('checked', false);
            });
            $('.parentSubCheckBox').each(function(){
                 $(this).removeAttr('checked', false);
            });
        }
    });
    
    
    

    $('#submit-btn').click(function() {

        var user_type_id = '<?php echo $user_group_id?>';
        var module_id = $('.parentCheckBox:checked').serializeArray();
        var action_id = $('#actions:checked').serializeArray();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>user/assign_right",

            data: ({
                user_type_id: user_type_id,
                module_id: module_id,
                action_id: action_id
            }),
            success: function(response) {
                console.log(response);
                window.location = "<?php echo base_url(); ?>user/user_groups";

            }

        });
    });

   $('.mycbox').each(function() {
        var $childCheckboxes = $(this).find('input.childCheckBox'),
            no_checked = $childCheckboxes.filter(':checked').length;
        if ($childCheckboxes.length == no_checked) {
            $(this).find('.parentCheckBox').prop('checked', true);
        }
    });

    /*$('input.childCheckBox').change(function() {
    	
        $(this).closest('.mycbox').find('.parentCheckBox').prop('checked',
            $(this).closest('.content').find('.childCheckBox:checked').length === $(this).closest('.content').find('.childCheckBox').length
        );
    });*/

    //clicking the parent checkbox should check or uncheck all child checkboxes
    // shoaib comment
    /*
    $(".parentCheckBox").click(
        function() {
            $(this).parents('.mycbox:eq(0)').find('.childCheckBox').prop('checked', this.checked);
        }
    );
    */
    
    $(".adminMainCheckBox").click(
        function() {
            $(this).parents('.mycbox:eq(0)').find('.adminSubCheckBox').prop('checked', this.checked);
            
            if($('#admin_rights:checked').length == 1){
                $("#admin_rights").removeAttr('checked', this.checked);
            }
            else
            {
              if ($('.adminSubCheckBox:checked').length == $('.adminSubCheckBox').length) {
                     $("#admin_rights").prop('checked', true);
              }  
            }
        }
    );
    
    $(".teacherMainCheckBox").click(
        function() {
            $(this).parents('.mycbox:eq(0)').find('.teacherSubCheckBox').prop('checked', this.checked);
            
            if($('#teacher_rights:checked').length == 1){
                $("#teacher_rights").removeAttr('checked', this.checked);
            }
            else
            {
                if ($('.teacherSubCheckBox:checked').length == $('.teacherSubCheckBox').length) {
                     $("#teacher_rights").prop('checked', true);
                }
            }
            
        }
    );
    
    $(".parentMainCheckBox").click(
        function() {
            $(this).parents('.mycbox:eq(0)').find('.parentSubCheckBox').prop('checked', this.checked);
            
            if($('#parent_rights:checked').length == 1){
                $("#parent_rights").removeAttr('checked', this.checked);
            }
            else
            {
                if ($('.parentSubCheckBox:checked').length == $('.parentSubCheckBox').length) {
                     $("#parent_rights").prop('checked', true);
                }
            }
        }
    );
    
    $(".studentMainCheckBox").click(
        function() {
            $(this).parents('.mycbox:eq(0)').find('.studentSubCheckBox').prop('checked', this.checked);
            
            if($('#student_rights:checked').length == 1){
                $("#student_rights").removeAttr('checked', this.checked);
            }
            else
            {
                if ($('.studentSubCheckBox:checked').length == $('.studentSubCheckBox').length) {
                     $("#student_rights").prop('checked', true);
                }
            }
            
        }
    );
    
    
    
    
    
    //clicking the last unchecked or checked checkbox should check or uncheck the parent checkbox
    $('.childCheckBox').click(
        function() {
            if ($(this).parents('.mycbox:eq(0)').find('.parentCheckBox').attr('checked') == true && this.checked == false)
                $(this).parents('.mycbox:eq(0)').find('.parentCheckBox').attr('checked', false);
            if (this.checked == true) {
                var flag = true;
                $(this).parents('.mycbox:eq(0)').find('.childCheckBox').each(
                    function() {
                        if (this.checked == false)
                            flag = false;
                    }
                );
                $(this).parents('.mycbox:eq(0)').find('.parentCheckBox').attr('checked', flag);
            }
        }
    );
    
});
</script>
