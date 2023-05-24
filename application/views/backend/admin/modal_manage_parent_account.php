<?php
    $student_id = $this->uri->segment(4);
    $school_id = $_SESSION['school_id'];
    $sys_sch_id = $_SESSION['sys_sch_id'];
    $login_type_id = get_login_type_id('parent');
    $parent_detail_arr = $this->db->query("select name, section_id, parent_id,roll from ".get_school_db().".student 
        where 
        student_id = $student_id 
        and school_id = $school_id
        and student_status in (".student_query_status().") 
    ")->result_array();
?>
<div class="row">
<div class="col-md-12">
    <div class="panel panel-primary" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('manage_parnet_login');?>
            </div>
        </div>
        <div class="panel-body">
            <h5><strong>
            <?php echo get_phrase('student_name');?> : </strong> <?php echo $parent_detail_arr[0]['name'] ?></h5>
            <h5><strong><?php echo get_phrase('roll_no');?>: </strong> <?php echo $parent_detail_arr[0]['roll'] ?></h5>
            <h5><strong><?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong> 
                   <?php   $dcs = section_hierarchy($parent_detail_arr[0]['section_id']);  ?>
                   <ul class="breadcrumb breadcrumb2" style="padding:2px;">  
                        <li>  <?php echo $dcs['d']; ?> </li>     
                        <li>   <?php echo $dcs['c']; ?>    </li>  
                        <li>   <?php echo $dcs['s'] ;?>   </li>  
                   </ul>         
            </h5>
        <?php
        if ($parent_detail_arr[0]['parent_id'] == 0)//create
        {
            $parents = $this->db->query("select sp.s_p_id, sr.relation, sp.id_no, sp.p_name from ".get_school_db().".student s
                    inner join ".get_school_db().".student_relation sr on s.student_id = sr.student_id
                    inner join ".get_school_db().".student_parent sp on sp.s_p_id = sr.s_p_id
                    where 
                    s.student_id = $student_id 
                    and s.school_id = $school_id   
                    and s.student_status in (".student_query_status().") 
                    order by FIELD(relation,'f','m','g')
                ")->result_array();
            //echo $this->db->last_query();
            if (count($parents) > 0)
            {
            ?>
               <form action="">
                    <?php
                    
                    foreach ($parents as $key => $value) 
                    {
                    	//print_r($value);
                    	
                        echo '<h3>'.str_replace(array('m', 'f', 'g'), array('Mother', 'Father', 'Guardian'), $value['relation']).'</h3>'; 

                        $p_user_login_arr = $this->db->query("select user_login_id, email from ".get_system_db().".user_login
                                where 
                                id_no = '".$value['id_no']."'
                            ")->result_array();
                        ?>
                        <input type="hidden" name="cnic[<?php echo $value['relation']; ?>]" value="<?php echo $value['id_no']; ?>" >

                        <div class="form-group">
                            <label class="control-label"> <?php echo get_phrase('name');?> </label> 
                                <input type="text" class="form-control" disabled="" value="<?php echo $value['p_name']; ?>"> 
                        </div>

                        <div class="form-group">
                            <label class="control-label"> <?php echo get_phrase('cnic');?> </label> 
                                <input type="text" class="form-control" disabled="" value="<?php echo $value['id_no']; ?>"> 
                        </div>

                        <div class="form-group">
                            <label class=" control-label"> <?php echo get_phrase('account_status');?> </label>
                            <?php 
                            
                            if (count($p_user_login_arr) > 0)
                            {
                                $p_user_login_detail_arr = $this->db->query("select user_login_detail_id from ".get_system_db().".user_login_details
                                    where 
                                    user_login_id = '".$p_user_login_arr[0]['user_login_id']."'
                                    and sys_sch_id = $sys_sch_id
                                    and login_type = $login_type_id
                                ")->result_array();
                                ?>
                                <br> <strong><?php echo get_phrase('login_id');?>:</strong> (<?php echo $p_user_login_arr[0]['email']; ?>) 
                                            <?php
                                            if (count($p_user_login_detail_arr) > 0)
                                            {?>
                                                <?php echo get_phrase('parent_account_exists');?>
                                                <div class="form-control" > 
                                                    <div class="float-right" style="margin-top:40px;">
                                                        <a class="modal_save_btn" href="<?php echo base_url() ?>user/manage_parent_login/link_account/<?php echo $student_id.'/'.$value['s_p_id'] ?>" ><?php echo get_phrase('link_parent_account');?></a>
                                                        <a href=""><?php echo get_phrase('deactivate');?></a>
                                                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                    						<?php echo get_phrase('cancel');?>
                                    					</button>
                                    				</div>
                                    			</div>	
                                            <?php
                                            }
                                            else
                                            {?>
                                                <?php echo get_phrase('parent_account_does_not_exists');?>                                               .
                                                <div class="form-control" > 
                                                    <div class="float-right" style="margin-top:40px;">
                                                        <a class="modal_save_btn" href="<?php echo base_url() ?>user/manage_parent_login/create_link_account/<?php echo $student_id.'/'.$value['s_p_id'].'/'.$p_user_login_arr[0]['user_login_id'] ?>" ><?php echo get_phrase('create');?> & <?php echo get_phrase('link_parent_account');?></a>
                                                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                    						<?php echo get_phrase('cancel');?>
                                    					</button>
                                    				</div>
                                    			</div>	
                                            <?php
                                            }
                                            
                                            
                            }
                            else
                            {
                            ?>
                             
                                    <span class="form-control" ><?php echo get_phrase('account_does_not_exists');?>.</span>
                                     
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                            if (count($p_user_login_arr) > 0)
                            {  
                            }else{
                                ?>
                                <div class="form-group">
                                    <div class="float-right">
                                        <!--<label class="col-sm-4 control-label"> <?php //echo get_phrase('select_user_group');?> </label>-->
                                        <!--<div class="col-sm-8">-->
                                        <!--    <select name="user_group_id" id = "user_group_id" class="form-control" required>-->
                                        <!--        <?php //echo user_group_option_list();?>-->
                                        <!--    </select>-->
                                        <!--</div>-->
                                        <!--onclick="update_href();"-->
                                        <a style="padding:7px 20px 9px 20px !important;margin-right:5px;" id="anchor_btn" class="modal_save_btn" href="<?php echo base_url() ?>user/add_parent_account/<?php echo $student_id.'/'.$value['s_p_id'] ?>" ><?php echo get_phrase('create_account');?>
                                        </a>
                                        <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                    						<?php echo get_phrase('cancel');?>
                    					</button>
                					</div>
                                </div>
                        <?php } ?>
                    <?php
                    }
                    ?>
                </form>         
            <?php
            }
            else
            {
                echo '<h3 style="color:red">No parent information added for the selected student.</h3>';
            }
        }
        else // update account
        {
            $user_login_arr = $this->db->query("select ul.* from ".get_school_db().".student_parent sp 
                inner join ".get_system_db().".user_login ul on ul.id_no = sp.id_no 
                where
                sp.s_p_id = ".$parent_detail_arr[0]['parent_id']."
                and sp.school_id = $school_id
                ")->result_array();

            $login_id   = intval($user_login_arr[0]['user_login_id']);
            $login_type = get_login_type_id('parent');

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
                    
                    <?php echo get_phrase('link_this_account_to_parent_login');?>
                    .</h3>
                    <form action="<?php echo base_url();?>user/manage_parent_login/link_account" method="post" class="form-horizontal form-groups-bordered">
                        <div class="form-group">
                            <label class=" control-label"><?php echo get_phrase('display_name');?></label>
                             
                                <input maxlength="50" type="text" class="form-control" name="display_name" disabled="" value="<?php echo $login_arr[0]['name']; ?>">
                             
                        </div>

                        <div class="form-group">
                            <label class=" control-label"><?php echo get_phrase('email');?> <span class="red"> * </span></label>
                             
                                <input maxlength="50" type="email" class="form-control" name="email"  value="<?php echo $login_arr[0]['email']; ?>" >
                             
                        </div>


                        <div class="form-group">
                            <label class=" control-label"><?php echo get_phrase('status');?> </label>
                            
                               <label class="radio-inline">
                                    <input type="radio" name="status" value="0" > <?php echo get_phrase('inactive');?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="1" checked="checked" > <?php echo get_phrase('active');?>
                                </label> 
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>" >
                            <input type="hidden" name="user_login_id" value="<?php echo $login_arr[0]['user_login_id']; ?>" >
                            <input type="submit" name="submit" class="btn btn-info" value="<?php echo get_phrase('link_account');?>" >
                        </div>
                    </form>
                <?php
                }
                else// update record
                {
                
                    $school_id     = $_SESSION['school_id'];
                    $parent_id     = $parent_detail_arr[0]['parent_id'];
                    $user_group_id = 0;
                    
                    $user_group_query        = "select user_group_id from ".get_school_db().".user_rights where parent_id = $parent_id and school_id = $school_id";
                    $user_group_query_result = $this->db->query($user_group_query)->row();
                    if($user_group_query_result != null){
                        $user_group_id = $user_group_query_result->user_group_id;
                    }
                
                
                ?>
                    <form action="<?php echo base_url();?>user/manage_parent_login/update" method="post" class="form-horizontal form-groups-bordered validate" novalidate>
                        
                        <div class="form-group">
                            <label class=" control-label"><?php echo get_phrase('cnic');?> </label>
                            
                                <input type="text" class="form-control" disabled="" value="<?php echo $login_detail_arr[0]['id_no']; ?>">
                            
                        </div>

                        <div class="form-group">
                            <label class=" control-label"><?php echo get_phrase('display_name');?> <span class="red"> * </span></label>
                             
                                <input maxlength="50" type="text" class="form-control" name="display_name" required="" autofocus value="<?php echo $login_detail_arr[0]['name']; ?>">
                            
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('email');?> <span class="red"> * </span></label>
                                <input maxlength="50" type="email" class="form-control" name="email"  value="<?php echo $login_detail_arr[0]['email']; ?>" >
                        </div>

                        <div class="form-group">
                            <label for="field-2" class=" control-label"><?php echo get_phrase('password');?> <span class="red"> * </span></label>
                             
                                <input maxlength="50" type="password" class="form-control edu_password_validation" name="password">
                                <span class="text-danger edu_password_validation_msg"></span><br>
                             
                        </div>
                        
                        <div class="form-group">
                            <label for="field-2" class=" control-label"><?php echo get_phrase('user_group');?> <span class="red"> * </span></label>
                             
                                <select class="form-control" name="user_group_id" required>
                                    <?php echo user_group_option_list($user_group_id); ?>
                                </select>
                             
                        </div>

                        <div class="form-group">
                            <label class=" control-label"><?php echo get_phrase('status');?> <span class="red"> * </span></label>
                             
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="0" <?php if ($login_detail_arr[0]['status'] == '0' ) echo 'checked' ?>> <?php echo get_phrase('inactive');?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="1" <?php if ($login_detail_arr[0]['status'] == '1' ) echo 'checked' ?> > <?php echo get_phrase('active');?>
                                </label> 
                        </div>

                        <div class="form-group">
                            <div class="float-right">
                                <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" >
                                <input type="hidden" name="user_login_id" value="<?php echo $login_detail_arr[0]['user_login_id']; ?>" >
                                <input type="submit" name="submit" class="modal_save_btn" value="<?php echo get_phrase('update');?>" >
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

function update_href(){
   var user_group_id = $('#user_group_id').val();
   if(user_group_id != ""){
       
        var href = $('#anchor_btn').attr('href');
        href+="/"+user_group_id;
        $('#anchor_btn').attr('href');
        $("#anchor_btn").attr("href", href);
   }
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


