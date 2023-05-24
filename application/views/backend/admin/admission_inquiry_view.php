<?php  
    if($this->session->flashdata('flash_message')){
        echo '<div align="center">
         <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          '.$this->session->flashdata('flash_message').'
         </div> 
        </div>';
    }  
?>
<script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('Admission_inquiries');?>
        </h3>
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
    </div>
</div>
<div class="col-lg-12 col-sm-12">
    <form action="<?=base_url()?>inquiries/admission_inq_action" method="post">
        <table class="table table-bordered cursor table_export table-responsive" data-step="3" data-position='top' data-intro="Admission Inquiries">
            <thead>
            <tr>
                <th></th>
                <th>Sr.</th>
                <th>Personal Details</th>
                <th>Class</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
            <tbody>
            <?php
                $j=0;
                foreach($admission_inquiries as $row)
                {
                    $j++;
            ?>
                    <tr>
                        <td align="center">
                            <input type="checkbox" class="inquiry_checkbox" name="inquiry_id[]" value="<?php echo $row['s_a_i_id'] ?> ">
                        </td>
                        <td style="width:44px;" class="td_middle">
                            <?php echo $j;?>
                        </td>
                        <td>
                            <strong>Name :</strong><?php echo $row['name'];?>
                            <br>
                            <strong>Father Name :</strong><?php echo $row['father_name'];?>
                            <br>
                            <strong>Mobile # :</strong><?php echo $row['mobile_no'];?>
                            <br>
                            <strong>Address :</strong><?php echo $row['address'];?>
                            <br>
                            <strong>Email :</strong><?php echo $row['email'];?>
                            <br>
                            <strong>Status :</strong><?php echo check_admission_inquiry_status($row['s_a_i_id']);?>
                        </td>
                        <td>
                            <?php
                                $details = section_hierarchy($row['class_id']);  // actually this id is section id from landing page 
                                echo $details[d]."-".$details[c]."-".$details[s];
                            ?>
                        </td>
                        <td>
                            <?php echo $row['description'];?>
                        </td>
                        <td class="td_middle">
                            <?php
                                if($row['s_a_i_status'] == 2){
                                    echo "<span class='text-success'>Candidate Confirmed</span>";
                                }else{
                            ?>
                            <div class="btn-group" data-step="3" data-position='left' data-intro="bank edit / delete options">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/move_to_candidate_modal/<?php echo $row['s_a_i_id'];?>');">
                                        <i class="entypo-user"></i>
                                        <?php echo get_phrase('move_to_candidate');?>
                                        </a>
                                    </li>
                                    <!--<li class="divider"></li>-->
                                </ul>
                            </div>
                            <?php } ?>
                        </td>
                        
                    </tr>
            <?php } ?>
        </tbody>
        </table>
        <div class="panel-footer mt-4">
            <!--<div class="form-group">-->
            <!--  <label for="user_gorup_id">Select Student User Group</label>    -->
            <!--  <select class="form-control" id="user_gorup_id" name="user_gorup_id" required>-->
            <!--        <option value="">Select Student User Group</option>-->
            <!--        <?php echo user_group_option_list();?>-->
            <!--  </select>-->
            <!--</div>-->
            <div class="form-group">
                <label for="inquiry_action">Select Action</label> 
                <select class="form-control" id="inquiry_action" name="inquiry_action" required>
                    <option value="">Select Action</option>
                    <option value="1">Send Email For Data Collection</option>
                    <!--<option value="2">Move to Candidates</option>-->
                </select>
            </div>
            <div class="form-group email_body" style="display:none">
                <label for="email_message">Type Email Message</label> 
                <textarea class="form-control" id="email_message" name="email_message" style="height:150px;" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit Action</button>
            </div>
        </div>
        <input type='hidden' name='inquiry_ids[]' id='inquiry_ids' />
    </form>
</div>


<script>
    $('.inquiry_checkbox').on("click", function() {
        ads = [];
        $('.inquiry_checkbox:checkbox').each(function(){
            
            if(this.checked){
                ads.push("'"+this.value+"'");
            }
        });
        $('#inquiry_ids').val(ads); 
    });
    
    $("#inquiry_action").on('change',function(){
        var option_no = $(this).val();
        if(option_no == 1)
        {
            $('.email_body').css("display","block");   
        }else{
            $('.email_body').css("display","none");
        }
    });
</script>
