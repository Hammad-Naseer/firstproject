
<style>
.myerror {
    color: red !important;
}
.text-success{
    color:green;
}
.text-danger{
    color:red;
}

</style>
    <script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
    <?php    
        if($this->session->flashdata('club_updated')){
            echo '<div align="center">
                <div class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  '.$this->session->flashdata('club_updated').'
                </div> 
            </div>';
        }
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                 <?php echo get_phrase('students_credentials');?>
            </h3>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="panel-group " id="accordion-test-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseOne-2" aria-expanded="false" class="collapsed" style="color:green;">
                        <i class="fas fa-angle-double-down"></i>
                        <?php echo get_phrase('Students_Credentials_Created');?>
                    </a>
                </h4>
            </div>
            <!--<div id="collapseOne-2" class="panel-collapse in show" aria-expanded="false" style="height: 0px;">-->
                <div class="panel-body">
                <form id="get_report" method="post" action="<?php echo base_url();?>reports/student_parent_credentials_pdf" style="display: inline;">  
                    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                    <input class="btn btn-primary" type="submit" id="crediential_pdf" value="<?php echo get_phrase('get_pdf');?>">
                </form>
                <form id="get_report" method="post" action="<?php echo base_url();?>reports/student_parent_credentials_excel" style="display: inline;">    
                    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                    <input class="btn btn-primary" type="submit" id="crediential_excel" value="<?php echo get_phrase('get_excel');?>">
                </form>
                <form id="send_credentials_form" method="post" action="<?php echo base_url();?>reports/student_parent_credentials_send" style="display: none;float: right;">    
                    <span <?= check_sms_preference(9,"style","sms") ?>>
                        <input type="hidden" name="student_ids_credentials" id="student_ids_credentials" value="">
                        <input class="btn btn-primary" type="submit" id="crediential_send_button" value="<?php echo get_phrase('send_credentials');?>">
                    </span>    
                </form>

                    <br><br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:34px;">#</th>
                                <th style="width:34px;"><?php echo get_phrase('picture');?></th>
                                <th style="width:34px;"><?php echo get_phrase('name');?></th>
                                <th><?php echo get_phrase('user_name');?></th>
                                <th style="width:34px;"><?php echo get_phrase('password');?></th>
                                <th style="width:90px;"><?php echo get_phrase('student_status');?></th>
                                <th style="width:90px;"><?php echo get_phrase('parent_status');?></th>
                                <th style="width:90px;"><input class="select-all-credentials" type="checkbox" id="select-all-credentials" name="select-all-credentials" >Select All</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $j=0;
                                foreach($found_credentials as $row)
                                {
                                    $section_details = section_hierarchy($row['section_id'] , $row['school_id']);
                                $j++;
                            ?>
                                        <tr>
                                            <td>
                                                <?php  echo $j; ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <img src="<?php
                                                        if($row['image']==''){
                                                         echo  base_url().'/uploads/default.png'; 
                                                        }else{
                                                        echo  display_link($row['image'],'student');
                                                        }
                                                         ?>" class="img-circle" width="30" />
                                                </div>
                                            </td>
                                            <td>
                                                    <?php
                                                        echo $row['name']."<br>";
                                                        echo "<span class='breadcrumb breadcrumb2'>".$section_details['d']." - ".$section_details['c']." - ".$section_details['s']."</span>";
                                                    ?>
                                            </td>
                                            <td>
                                                <?php  echo $row['id_no']; ?>
                                            </td>
                                            <td>
                                                <input style="border:none;" type="password" id="pass_<?php  echo $j; ?>" value="<?php if($row['is_password_updated'] == 1){ echo "Password Updated"; }else{ echo "123456"; } ?>" onclick="showPassword(this)" readonly>
                                            </td>
                                            <td>
                                                <span class="text-info"><?php echo get_phrase('student_credentials_created');?></span>
                                            </td>
                                            <td>
                                                <?php 
                                                     if( $row['parent_id'] > 0 )  
                                                     {
                                                ?>
                                                       <span class="text-info"><?php echo get_phrase('parent_credentials_created');?></span>
                                                <?php
                                                     }else{
                                                ?>
                                                      <span class="text-danger"><?php echo get_phrase('parent_credentials_not_created');?></span>
                                                <?php         
                                                     }
                                                ?>
                                            </td>
                                            <td>
                                                <div <?= check_sms_preference(9,"style","sms") ?>>
                                                    <input type="checkbox" class="std_checkbox_creds" value="<?php echo $row['student_id'] ?> " >
                                                </div>    
                                            </td>
                                        </tr>
                            <?php 
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            <!--</div>-->
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseTwo-2" class="collapsed" style="color:red;" aria-expanded="false">
                        <i class="fas fa-angle-double-down"></i>
                        <?php echo get_phrase('Students_Credentials_Not_Created');?>
                    </a>
                </h4>
            </div>
            <div id="collapseTwo-2" class="panel-collapse collapse" aria-expanded="false">
                <form action="<?php echo base_url();?>reports/generate_student_credentials" method="POST">
                    <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:34px;">#</th>
                                <th style="width:34px;"><?php echo get_phrase('picture');?></th>
                                <th style="width:34px;"><?php echo get_phrase('name');?></th>
                                <th><input class="select-all" type="checkbox" id="select-all" name="select-all" >Select All</th>
                                <th><input class="parent-all" type="checkbox" id="parent-all" name="parent-all" >Select All(Parent)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i=0;
                                foreach($not_found_credentials as $row)
                                {
                                    $section_details = section_hierarchy($row['section_id'] , $row['school_id']);
                                $i++;
                            ?>
                                        <tr>
                                            <td>
                                                <?php  echo $i; ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <img src="<?php
                                                        if($row['image']==''){
                                                         echo  base_url().'/uploads/default.png'; 
                                                        }else{
                                                        echo  display_link($row['image'],'student');
                                                        }
                                                         ?>" class="img-circle" width="30" />
                                                </div>
                                            </td>
                                            <td>
                                                
                                                <?php
                                                    $id_no_error = trim($row['id_no'] == '') ? '<span style="color:red;margin-left:10px"> ( Id no is empty)</span>' : '<span style="color:green;margin-left:10px">'.$row['id_no'].'</span>'; 
                                                    echo $row['name']."<br>";
                                                    echo "<span class='breadcrumb breadcrumb2'>".$section_details['d']." - ".$section_details['c']." - ".$section_details['s']."</span>" . $id_no_error;
                                                ?>
                                                
                                            </td>
                                            <td>
                                                <input type="checkbox" class="std_checkbox" name="student_id[]" value="<?php echo $row['student_id'] ?> "   >
                                            </td>
                                            <td>
                                                <input type="checkbox" class="parent_checkbox" name="parent_id[]" value="<?php echo $row['student_id'] ?> "   >
                                            </td>
                                        </tr>
                            <?php 
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                    <div class="panel-footer">
                    <div class="form-group">
                      <label for="user_gorup_id">Select Student User Group</label>    
                      <select class="form-control" id="user_gorup_id" name="user_gorup_id" required>
                            <option value="">Select Student User Group</option>
                            <?php echo user_group_option_list();?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="parent_user_gorup_id">Select Parent User Group</label>     
                      <select class="form-control" id="parent_user_gorup_id" name="parent_user_gorup_id" required>
                            <option value="">Select Parent User Group</option>
                            <?php echo user_group_option_list();?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="creds_genration">Select Action</label> 
                      <select class="form-control" id="creds_genration" name="creds_genration" required>
                          <option value="">Select Action</option>
                         <option value="1">Generate Login Credentials</option>
                      </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="return validate_credentials_form()">Create Credentials</button>
                    </div>
                </div>
                <input type='hidden' name='student_ids[]' id='student_ids' />
                <input type='hidden' name='parent_ids[]'  id='parent_ids' />
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- DATA TABLE EXPORT CONFIGURATIONS -->
    <script type="text/javascript">
    $(document).ready(function() {
        
        
        var ads     = [];
        var parents = [];
        
        var credentials_ids = [];
        
        // student check all scenario handled here
        $('#select-all').click(function() {
            
            if( $('#select-all').is(":checked") ){
                $('.std_checkbox:checkbox').prop('checked', true);
                $('.std_checkbox:checkbox').each(function(){
                    if(this.checked){
                        ads.push(this.value);
                    }
                });
            }
            else
            {
                $('.std_checkbox:checkbox').prop('checked', false);
                ads = [];
            }
            
            $('#student_ids').val(ads);
            
        });
        
        
        
        $('#select-all-credentials').click(function() {
            
            if( $('#select-all-credentials').is(":checked") ){
                
                $('#send_credentials_form').css('display','inline');
                
                $('.std_checkbox_creds:checkbox').prop('checked', true);
                $('.std_checkbox_creds:checkbox').each(function(){
                    if(this.checked){
                        credentials_ids.push(this.value);
                    }
                });
            }
            else
            {
                $('#send_credentials_form').css('display','none');
                $('.std_checkbox_creds:checkbox').prop('checked', false);
                credentials_ids = [];
            }
            
            $('#student_ids_credentials').val(credentials_ids);
            
        });
        
        
        // parent check all scenario handled here
        $('#parent-all').click(function() {
            
            if( $('#parent-all').is(":checked") ){
                $('.parent_checkbox:checkbox').prop('checked', true);
                $('.parent_checkbox:checkbox').each(function(){
                    if(this.checked){
                        parents.push(this.value);
                    }
                });
            }
            else
            {
                $('.parent_checkbox:checkbox').prop('checked', false);
                parents = [];
            }
            
            $('#parent_ids').val(parents);
            
        });
        
        
        // student single checkbox event handler
        $('.std_checkbox').on("click", function() {
            ads = [];
            $('.std_checkbox:checkbox').each(function(){
                
                if(this.checked){
                    ads.push(this.value);
                }
            });
            $('#student_ids').val(ads); 
        });
        
        // parent single checkbox event handler
        $('.parent_checkbox').on("click", function() {
            parents = [];
            $('.parent_checkbox:checkbox').each(function(){
                if(this.checked){
                    parents.push(this.value);
                }
            });
            $('#parent_ids').val(parents); 
        });
        
        
        
        
        $('.std_checkbox_creds').on("click", function() {
            credentials_ids = [];
            $('.std_checkbox_creds:checkbox').each(function(){
                
                if(this.checked){
                    credentials_ids.push(this.value);
                }
            });
            
            if(credentials_ids.length >= 1){
                $('#send_credentials_form').css('display','inline');
            }
            else
            {
                $('#send_credentials_form').css('display','none');
            }
            
            $('#student_ids_credentials').val(credentials_ids);
        });
        
        
        
    });

    </script>
    <script>
        function showPassword(input){
            var id = input.id;
              var x = document.getElementById(id);
              if (x.type === "password") {
                x.type = "text";
              } else {
                x.type = "password";
              }
        }
        
        function validate_credentials_form(){
            
            var proceed = true;
            var creds_genration = $('#creds_genration').val();
            var user_gorup_id   = $('#user_gorup_id').val();
            
            if(creds_genration == ""){
                alert("Action is Mendatory");
                proceed = false;
            }
            if(user_gorup_id == ""){
                alert("User Group is Mendatory");
                proceed = false;
            }
            
            var boxes = $('.std_checkbox:checkbox');
            if(boxes.length > 0) {
                if( $('.std_checkbox:checkbox:checked').length < 1) {
                    alert('Please select at least one student');
                    proceed =  false;
                }
            }
            return proceed;
        }
        
        
        $('#crediential_pdf').click(function(){
            //setTimeout(function(){ location.reload(); }, 10000);
        });
    
        $('#crediential_excel').click(function(){
            //setTimeout(function(){ location.reload(); }, 10000);
        });
    </script>