<style>
    .fas{
        font-size: 16px;
        text-shadow: 0px 0px 1px #ccc;
    }
    .myerror {
        color: red !important;
    }
</style>
<?php if (right_granted('students_view')){?>
<?php
    if ( $this->session->flashdata( 'club_updated' ) ) 
    {
        echo '<div align="center">
    	<div class="alert alert-success alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    	' . $this->session->flashdata( 'club_updated' ) . '
    	</div> 
    	</div>';
    }
    if ( $this->session->flashdata( 'error_msg' ) ) {
    	echo '<div align="center">
    	<div class="alert alert-danger alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    	' . $this->session->flashdata( 'error_msg' ) . '
    	</div> 
    	</div>';
    }
    if($this->session->flashdata('delete_dis')){
        echo '<div align="center">
         <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          '.$this->session->flashdata('delete_dis').'
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
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('bulk_status_change');?>
            </h3>
        </div>
    </div>
    <form action="<?php echo base_url(); ?>c_student/bulk_status_change" method="post" name="student_form" data-step="1" data-position='top' data-intro="Use these filter to get specific departments / class / section students">
        <div class="row filterContainer">
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label>Student Search</label>
                <input type="text" name="std_search" class="form-control" value="<?php echo $std_search;?>">
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label for="dept_id"><b>Select Department</b></label>
                <select id="departments_id" class="selectpicker form-control" name="dept_id">
                    <option value="">Select Department</option>
                    <?php
                        $qd = "select departments_id , title from ".get_school_db().".departments where school_id=".$_SESSION['school_id']."";
                        $dept = $this->db->query($qd)->result_array();
                        foreach($dept as $d):
                    ?>
                          <option value="<?php echo $d['departments_id'];?>"><?php echo $d['title'];?></option>
                    <?php endforeach; ?> 
                </select> 
            </div>
            
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label for="class_id"><b>Select Class</b></label> 
                <select class="form-control" id="class_id" name="class_id"></select>
            </div>
            
            <div class="col-md-6 col-lg-6 col-sm-6">
                <label for="section_id_filter_selection">Select Section</label>
                <select id="section_id" class="selectpicker form-control" name="section_id">
                        <?php echo section_selector($section_id);?>
                </select> 
            </div>
            <div class="col-md-8 col-lg-8 col-sm-12" style="margin-top:10px">
                  <input type="hidden" name="apply_filter" value="1">
                  <input type="submit" value="Filter" class="modal_save_btn">
                    <?php if ($apply_filter == 1){?>
                        <a id="btn_show" href="<?php echo base_url(); ?>c_student/bulk_status_change" class="modal_cancel_btn" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                    <?php } ?>
            </div>
        </div>

    </form>
    <div class="row mt-2">
        <div style="width:100%;">
            
            
            
<?php 
  if(isset($students)){  
?>

    <div class="col-md-12" style="width:100%;display:none;" id="send_class_leave_form">
        <!--<div class="col-md-4">-->
        <!--</div> -->
        <!--<div class="col-md-8">-->
            <div class="card">
                  <div class="card-body">
                       <h3 style="margin-bottom:-25px;">Bulk Change Student Status</h3>
                  </div>       
                  <div class="card-body">
                    <?php echo form_open(base_url().'c_student/admission_status_bulk');?>
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('status');?>
                                <span class="star">*</span>
                            </label>
                            <select name="student_admission_status" class="form-control" id="status" required>
                                <option  value=""><?php echo get_phrase('select_student_status'); ?></option>
                                <option value="10">Active</option>
                                <option value="51">Cancel</option>
                                <option value="52">Left</option>
                                <option value="53">Passout</option>
                            </select>
                            <div id="d5" class="d"></div>
                        </div>
                        <div class="form-group">
                			<div class="float-right">
                				<button type="submit" class="modal_save_btn">
                					<?php echo get_phrase('update_student_status');?>
                				</button>
                			</div>
                		</div>
                        
                        
                        <input type="hidden" class="form-control" id="student_ids_class_leave" name="student_ids_class_leave">
                    
                    <?php echo form_close();?> 
                  </div>
            </div>
        <!--</div> -->
    </div>


    
<table class="table table-bordered" data-step="2" data-position='top' data-intro="students record">
    
    <thead>
        <tr>
            <th style="width:90px;"><input class="select-all-credentials" type="checkbox" id="select-all-credentials" name="select-all-credentials" >Select All</th> 
            <th><?php echo get_phrase('student_information');?></th>
        </tr>
    </thead>
    <tbody>
    <?php 
        foreach($students as $row)
        {
    ?>
        <tr>
            <td class="td_middle">
                <input type="checkbox" class="std_checkbox_class_leave" value="<?php echo $row['student_id'] ?> " >
            </td> 
            <td>
                <div class="myttl">
                    <?php echo $row['name'];?><span style="display: none;"><?php echo  $row['system_id']; ?></span></div>
                <div>
                    <strong>   <?php echo get_phrase('roll_no');?>: </strong>
                    <?php echo $row['roll'];?>
                </div>
                <div><strong><?php echo get_phrase('');?><?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>
                    <ul class="breadcrumb breadcrumb2" style="padding:2px;">
                        <li>
                            <?php echo $row['department_name'];?> </li>
                        <li>
                            <?php echo $row['class_name'];?> </li>
                        <li>
                            <?php echo $row['section_name'];?> </li>
                    </ul>
                </div>
                <div>
                    <style>
                    .ptag {
                        margin: 0px;
                        padding: 0px;
                    }
                    
                    .fa-mobile {
                        font-size: 24px;
                    }
                    
                    .emer {
                        color: red;
                    }
                    
                    .emer_green {
                        color: green;
                    }
                    
                    .emer_blue {
                        color: blue;
                    }
                    </style>
                    <p class="ptag">
                        <strong><?php echo get_phrase('mobile_no');?>: </strong>
                        <?php echo $row['mob_num'];?>
                    </p>
                </div>
            </td>
        </tr>
        <?php }
    
    ?>
    </tbody>
</table>


<?php
}
?>



        </div>
    </div>
   
    <!-- DATA TABLE EXPORT CONFIGURATIONS -->
    <script type="text/javascript">
    $(document).ready(function() {
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');

            var selected = $('#' + id + ' :selected');

            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });
	
		
    });

    </script>
    <style>
    .loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #63b7e7;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    </style>
<?php
}
?>









<script type="text/javascript">

   
    $(document).ready(function(){
       var dept_id    = '<?php  echo $dept_id;    ?>';
       var class_id   = '<?php  echo $class_id;   ?>';
       var section_id = '<?php  echo $section_id; ?>';
       
       
       if(dept_id != undefined && dept_id > 0){
           
            $("#departments_id").val(dept_id);
           
            $.ajax({
                 type: 'POST',
                 data: {departments_id:dept_id,clscomp_id:0},
                 url: "<?php echo base_url();?>departments/get_class",
                 dataType: "html",
                 success: function(response)
                 {
                     $("#class_id").html(response);
                     $('#loader').remove();
                     $("#class_id").val(class_id);
                 }
            });
            
            if(class_id != undefined && class_id > 0){
                 
                    $.ajax({
                        type: 'POST',
                         data: {class_id:class_id},
                         url: "<?php echo base_url();?>departments/get_sections",
                         dataType: "html",
                         success: function(response)
                         {
                             $("#section_id").html(response);
                             $('#loader').remove();
                             $("#section_id").val(section_id);
                         }
                    });
                 
            }
          
          
       }
    });


    $("#departments_id").change(function(){
        get_dt();
    });
     
    $("#class_id").change(function(){
        get_sec();
    });



    function get_dt()
    {
         var departments_id=$("#departments_id").val();
         var clscomp_id="<?php echo $clr_id;  ?>";
         if(departments_id !="")
         {
            $('#departments_id').after('<div id="loader" class="loader_small"></div>');
            $.ajax({
                 type: 'POST',
                 data: {departments_id:departments_id,clscomp_id:clscomp_id},
                 url: "<?php echo base_url();?>departments/get_class",
                 dataType: "html",
                 success: function(response)
                 {
                     $("#class_id").html(response);
                     $('#loader').remove();
                 }
            });
         }
    
         else
         {
             $("#class_id").html("<option value=''><?php echo get_phrase('select'); ?></option>");
             $("#section_id").html("<option value=''><?php echo get_phrase('select'); ?></option>");
         }
     }
    
    function get_sec()
    {
         var class_id = $("#class_id").val();
         if(class_id !="")
         {
             $('#class_id').after('<div id="loader" class="loader_small"></div>');
             $.ajax({
                type: 'POST',
                 data: {class_id:class_id},
                 url: "<?php echo base_url();?>departments/get_sections",
                 dataType: "html",
                 success: function(response)
                 {
                     $("#section_id").html(response);
                     $('#loader').remove();
                 }
             });
         }
    
         else
         {
             $("#section_id").html("<option value=''><?php echo get_phrase('select'); ?></option>");
         }
     }
     
     
     
    var class_leave_ids = [];
    
    $('#select-all-credentials').click(function() {
            
        if( $('#select-all-credentials').is(":checked") ){
            
            $('#send_class_leave_form').css('display','inline');
            
            $('.std_checkbox_class_leave:checkbox').prop('checked', true);
            
            $('.std_checkbox_class_leave:checkbox').each(function(){
                if(this.checked){
                    class_leave_ids.push(this.value);
                }
            });
        }
        else
        {
            $('#send_class_leave_form').css('display','none');
            $('.std_checkbox_class_leave:checkbox').prop('checked', false);
            class_leave_ids = [];
        }
            
        $('#student_ids_class_leave').val(class_leave_ids);
            
    });


    $('.std_checkbox_class_leave').on("click", function() {
        
        class_leave_ids = [];
            
        $('.std_checkbox_class_leave:checkbox').each(function(){
            
            if(this.checked){
                class_leave_ids.push(this.value);
            }
            
        });
            
        if(class_leave_ids.length >= 1){
            $('#send_class_leave_form').css('display','inline');
        }
        else
        {
            $('#send_class_leave_form').css('display','none');
        }
            
        $('#student_ids_class_leave').val(class_leave_ids);
        
    }); 
     
     
     

</script>
