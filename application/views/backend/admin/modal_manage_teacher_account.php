<?php
    $teacher_id = $this->uri->segment(4);
    $school_id = $_SESSION['school_id'];
    $sys_sch_id = $_SESSION['sys_sch_id'];
    
    $staff_arr = $this->db->query("select ul.* from ".get_school_db().".staff 
    inner join ".get_system_db().".user_login ul on staff.id_no = ul.id_no where staff.staff_id = $teacher_id")->result_array();
    
    $staff_detail_arr = $this->db->query("select staff.*, d.title as designation from ".get_school_db().".staff 
    inner join ".get_school_db().".designation d on staff.designation_id = d.designation_id where staff.staff_id = $teacher_id")->result_array();
    
    $staff_rights = $this->db->query("select ur.user_group_id from ".get_school_db().".staff 
    inner join ".get_school_db().".user_rights ur on staff.staff_id = ur.staff_id where staff.staff_id = $teacher_id limit 1")->row();
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title black black2" style="color:#fff !important">
                    <i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('manage_teacher_login');?>
                </div>
            </div>
            <div class="col-sm-12">
            <div class="thisrow" style="height:38px;padding-top: 3px;margin-top: 12px !important;margin-bottom: -15px;">
           	    <div class="col-sm-6"> <h5><strong>
                    <?php echo get_phrase('teacher_name');?>
                    : </strong> <?php echo $staff_detail_arr[0]['name'] ?></h5>
                </div>
           	    <div class="col-sm-6">  
           	        <h5><strong><?php echo get_phrase('designation');?>: </strong> 
           	        <?php echo $staff_detail_arr[0]['designation'] ?></h5>
           	    </div>
           	 </div>
        </div> 
            <div class="panel-body">
            
            <div class="panel-title black black2" style="color:white !important;margin-top:20px;background: gray;text-align:center">
            <?php
            if (count($staff_arr) == 0) 
            {
                 echo "Please generate user account credentials"; 
            }
            else
            {
                echo "User account credentials are already generated"; 
            }
            ?>
            </div>

           
           
    <?php
    if (count($staff_arr) == 0) // new entry
    {
        $staff = $this->db->query("select id_no from ".get_school_db().".staff 
            where staff_id = $teacher_id 
            ")->result_array();
        ?>
    
       <form action="<?php echo base_url();?>user/manage_teacher_login/create_new" method="post" class="form-horizontal form-groups-bordered validate" novalidate>

            <input type="hidden" name="cnic" value="<?php echo $staff[0]['id_no']; ?>" >

            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('id_no');?> </label>
                <input type="text" class="form-control" disabled="" value="<?php echo $staff[0]['id_no']; ?>">
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('display_name');?> <span class="red"> * </span></label>
                <input maxlength="50" type="text" class="form-control" name="display_name" required="" autofocus value="<?php echo $staff_detail_arr[0]['name'] ?>" >
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('email');?> <span class="red"> * </span></label>
                <input maxlength="50" type="email" id="create_email" class="form-control" name="email" value="<?php echo $staff_detail_arr[0]['email'] ?>"  required="" oninput="validate_teacher_login_email(this, 'create')" >
            </div>

            <div class="form-group">
                <label for="field-2" class="control-label"><?php echo get_phrase('password');?> <span class="red"> * </span></label>
                <input maxlength="50" type="password" class="form-control edu_password_validation" name="password">
                <span class="text-danger edu_password_validation_msg"></span><br>
            </div>

            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('status');?> <span class="red"> * </span></label>
                <label class="radio-inline">
                    <input type="radio" name="status" value="0"> <?php echo get_phrase('inactive');?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="status" value="1" checked="checked"> <?php echo get_phrase('active');?>
                </label>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo get_phrase('user_group');?><span class="red"> * </span></label>
                <select name="user_group_id" class="form-control" required>
                    <?php echo user_group_option_list(); ?>
                </select>
            </div>
            <div class="form-group">
                <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>" >
                <div class="float-right">
					<button type="submit" class="modal_save_btn" id="submit_btn" name="submit">
						<?php echo get_phrase('save');?>
					</button>
					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
						<?php echo get_phrase('cancel');?>
					</button>
				</div>
            </div>
        </form>         
            
    <?php
    }
    else
    {
        $login_id   = $staff_arr[0]['user_login_id'];
        $login_type = get_login_type_id('teacher');

        $login_detail_arr = $this->db->query("select uld.*, ul.name, ul.email, ul.password, ul.id_no from ".get_system_db().".user_login_details uld 
            inner join ".get_system_db().".user_login ul on uld.user_login_id = ul.user_login_id
            where 
            uld.user_login_id  = $login_id
            and uld.sys_sch_id = $sys_sch_id
            and uld.login_type = $login_type
            ")->result_array();

        if (count($login_detail_arr) > 1)
        {
            echo '<h3 style="color:red">System Error! Please contact system administrator.</h3>';
        }
        else
        {
            if ( count($login_detail_arr) == 0) // link to new account (either different parent account or different branch exists)
            {
                $login_arr = $this->db->query("select * from ".get_system_db().".user_login 
                    where 
                    user_login_id  = $login_id
                    ")->result_array();
                ?>
                <h3>
                
                <?php echo get_phrase('link_this_account_to_teacher_login');?>
                .</h3>
                <form action="<?php echo base_url();?>user/manage_teacher_login/link_account" method="post" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <label class="col-sm-4 control-label"> <?php echo get_phrase('display_name');?></label>
                        <div class="col-sm-8">
                            <input maxlength="50" type="text" class="form-control" name="display_name" disabled="" value="<?php echo $login_arr[0]['name']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"> <?php echo get_phrase('email');?> <span class="red"> * </span></label>
                        <div class="col-sm-8">
                            <input maxlength="50" type="email" class="form-control" name="email" disabled="" value="<?php echo $login_arr[0]['email']; ?>" >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?php echo get_phrase('Status'); ?> </label>
                        <div class="col-sm-8">
                           <label class="radio-inline">
                                <input type="radio" name="status" value="0" ><?php echo get_phrase('inactive'); ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1" checked="checked" ><?php echo get_phrase('active'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>" >
                            <input type="hidden" name="user_login_id" value="<?php echo $login_arr[0]['user_login_id']; ?>" >
                            <input type="submit" name="submit" class="btn btn-info" value="Link account" >
                        </div>
                    </div>
                </form>
            <?php
            }
            else// update record
            {?>
                <form action="<?php echo base_url();?>user/manage_teacher_login/update" id="disable_submit_btn" method="post" class="form-horizontal form-groups-bordered validate" novalidate>
                    
                    <div class="form-group">
                        <label class="control-label"> <?php echo get_phrase('id_no');?> </label> 
                            <input type="text" class="form-control" disabled="" value="<?php echo $login_detail_arr[0]['id_no']; ?>"> 
                    </div>

                    <div class="form-group">
                        <label class="control-label"> <?php echo get_phrase('display_name');?><span class="red"> * </span></label> 
                            <input maxlength="50" type="text" class="form-control" name="display_name" required="" autofocus value="<?php echo $login_detail_arr[0]['name']; ?>"> 
                    </div>
                    <div class="form-group">
                        <label class=" control-label"> <?php echo get_phrase('email');?> <span class="red"> * </span></label>
                        <input maxlength="50" type="email" class="form-control" name="email" disabled="" value="<?php echo $login_detail_arr[0]['email']; ?>" >
                    </div>

                    <div class="form-group">
                        <label for="field-2" class="control-label"> <?php echo get_phrase('password');?> <span class="red"> * </span></label>
                        <input maxlength="50" type="password" class="form-control" name="password">
                    </div>

                    <div class="form-group">
                        <label class="control-label"> <?php echo get_phrase('status');?> <span class="red"> * </span></label> 
                            <label class="radio-inline">
                                <input type="radio" name="status" value="0" <?php if ($login_detail_arr[0]['status'] == '0' ) echo 'checked' ?>>  <?php echo get_phrase('inactive');?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1" <?php if ($login_detail_arr[0]['status'] == '1' ) echo 'checked' ?> >  <?php echo get_phrase('active');?>
                            </label> 
                    </div>
                    
                    <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('user_group');?><span class="red"> * </span></label> 
                                <select name="user_group_id" class="form-control" required>
                                    <?php echo user_group_option_list($staff_rights->user_group_id); ?>
                                </select> 
                    </div>

                    <div class="form-group">
                        <div class="float-right">
    					<button type="submit" class="modal_save_btn">
    						<?php echo get_phrase('update');?>
    					</button>
    					<input type="hidden" name="user_login_id" value="<?php echo $login_detail_arr[0]['user_login_id']; ?>" >
                        <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>" >
                        
    					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    						<?php echo get_phrase('cancel');?>
    					</button>
    				</div>
                    </div>
                    
    				
    				
                </form> 
            <?php
            }
        }
    }
    ?>

        </div>
        </div>
    </div>
</div>

<script>
    function validate_teacher_login_email(obj, action='')
    {
        $('#submit_btn').attr("disabled", 'disabled');
        $(".loader_small").remove();
        $('#create_email').after('<div class="loader_small"></div>');


        var email = obj.value;
        var send_url = "<?php echo base_url();?>user/validate_login_email/";
        if (action == 'create') 
        {
            send_url += "create";
        } 
        else 
        {
            send_url += "update";
        }
        $.ajax({
            url: send_url, 
            data: {email:email},
            method: "post",
            dataType:'json',
            success: function(result)
            {
                $(".loader_small").remove();
                if (result.status == 'success')
                {
                    $('#submit_btn').removeAttr('disabled');
                    $('.email_validate_error').remove();
                }
                else
                {
                    $('#create_email').after('<span class="validate-has-error email_validate_error">'+result.message+'</span>');
                }
            }
        });
            
    }
    
    $(".edu_password_validation").on("keyup",function(){
        var inputtxt = $(this).val();
        var decimal=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;

        if(inputtxt.match(decimal)) 
        { 
            $('input[type="submit"]').removeAttr("disabled");
            $('button[type="submit"]').removeAttr("disabled");
            $(".edu_password_validation_msg").text("");
            // return true;
        }else{
            $(".edu_password_validation_msg").text("Input Password and Submit [8 to 15 characters which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character]");
            $('input[type="submit"]').attr("disabled",true);
            $('button[type="submit"]').attr("disabled",true);
            // return false;
        }
    });
</script>
