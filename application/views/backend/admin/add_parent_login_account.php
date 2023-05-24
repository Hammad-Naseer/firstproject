<?php 
//session_start(); 
$student_id = $this->uri->segment(3);
$parent_id = $this->uri->segment(4);
//$user_group_id = $this->uri->segment(5);

$user_group_id = 0;
$school_id     = $_SESSION['school_id'];

$parent_arr = $this->db->query("select * from ".get_school_db().".student_parent where s_p_id = $parent_id ")->result_array();

$user_group_query  = "select user_group_id from ".get_school_db().".user_rights where parent_id = $parent_id and school_id = $school_id";
//echo $user_group_query;exit;
$user_group_query_result = $this->db->query($user_group_query)->row();
if($user_group_query_result != null){
    $user_group_id = $user_group_query_result->user_group_id;
}

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a href="<?=base_url()?>c_student/student_information" class="btn btn-primary pull-right">
            <i class="fa fa-arrow-left"></i>
            <?php echo get_phrase('back');?>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('manage_parent_login_credential'); ?>
        </h3>
    </div>
</div>
<div class="col-md-12"> 
	    <div class="panel panel-primary" data-collapsed="0">
	        <div class="panel-heading" style="background-color:#012b3c;">
	            <div class="panel-title">
	                <i class="entypo-plus-circled"></i>
	                <?php echo get_phrase('add_form');?>
	            </div>
	        </div>
	        <div class="panel-body">
	            <form action="<?php echo base_url() ?>user/manage_parent_login/create_new" method="post" class="validate" novalidate="novalidate">
	            	<div class="form-group col-lg-6 col-sm-6">
	                    <label class="control-label"><?php echo get_phrase('display_name'); ?> <span class="red"> * </span></label>
	                    <input maxlength="50" type="text" class="form-control" name="display_name" value="<?= $parent_arr[0]["p_name"]; ?>" required="" autofocus="">
	                </div>
	                <div class="form-group col-lg-6 col-sm-6">
	                    <label class="control-label"><?php echo get_phrase('email'); ?> <span class="red"> * </span></label>
	                    <input maxlength="50" type="email" class="form-control" name="email" required="" id="create_email" oninput="validate_teacher_login_email(this)" >
	               </div>
	                <div class="form-group col-lg-6 col-sm-6">
	                    <label for="field-2" class="control-label"><?php echo get_phrase('password'); ?> <span class="red"> * </span></label>
	                    <input maxlength="50" type="password" class="form-control edu_password_validation" name="password">
	                    <span class="text-danger edu_password_validation_msg"></span><br>
	                </div>
	                <div class="form-group col-lg-6 col-sm-6" style="height:60px;">
	                    <label class="control-label" style="position:relative;top:20px;"><?php echo get_phrase('status'); ?><span class="red"> * </span></label>
                        <br><br>
                        <label class="radio-inline">
                            <input type="radio" name="status" value="0"> <?php echo get_phrase('inactive'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="status" value="1" checked="checked"> <?php echo get_phrase('active'); ?>
                        </label>
	                </div>
	                <div class="form-group col-lg-6 col-sm-6">
                        <label for="field-1" class="control-label" >Select User Group <span class="red"> * </span></label>
                        <select class="form-control" name="user_group_id" required>
                            <?php echo user_group_option_list($user_group_id); ?>
                        </select>
                    </div>
	                <!--<div class="row">-->
	                    <div class="form-group col-lg-12 col-sm-12" style="    float: right;">
                            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>" >
                            <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" >
                            <input type="hidden" name="cnic" value="<?php echo $parent_arr[0]['id_no'] ?>">
                            <input type="submit" name="submit" id="submit_btn" class="btn btn-info" value="<?php echo get_phrase('save'); ?>">
    	                </div>
	                <!--</div>-->
	            </form>
	        </div>
	    </div>
    </div>

<script>
    function validate_teacher_login_email(obj)
    {
    	$(".loader_small").remove();
        $('#create_email').after('<div class="loader_small"></div>');

        $('#submit_btn').attr("disabled", 'disabled');
        var email = obj.value;
        $.ajax({
            url: "<?php echo base_url();?>user/validate_login_email", 
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
</script>