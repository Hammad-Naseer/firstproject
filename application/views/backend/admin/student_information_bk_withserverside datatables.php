<style>
    .fas{font-size:16px;text-shadow:0 0 1px #ccc}.myerror{color:red!important}.ptag{margin:0;padding:0}.fa-mobile{font-size:24px}.emer{color:red}.emer_green{color:green}.emer_blue{color:blue}
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
    if( $this->session->flashdata( 'journal_entry' ) ) {
    	echo '<div align="center">
    	<div class="alert alert-success alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    	' . $this->session->flashdata( 'journal_entry' ) . '
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
    if($this->session->flashdata('delete_challan_form')){
        echo '<div align="center">
        <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          '.$this->session->flashdata('delete_challan_form').'
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
                <?php echo get_phrase('student_information');?>
            </h3>
        </div>
    </div>
    <form action="<?php echo base_url(); ?>c_student/get_student_information" method="post" name="student_form" data-step="1" data-position='top' data-intro="Use these filter to get specific departments / class / section students">
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
                        <a id="btn_show" href="<?php echo base_url(); ?>c_student/get_student_information" class="modal_cancel_btn" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
                        <a style="" href="<?php echo base_url(); ?>c_student/create_card/section/<?php echo $section_id;?>" class='btn btn-primary' id='card_create'><?php echo get_phrase('create_section_cards');?></a>
                    <?php } ?>
            </div>
        </div>

    </form>
    <div class="row mt-4">
        <div class="col-md-12">
            <table class="table table-bordered" id="student_data" data-step="2" data-position='top' data-intro="students record" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width:30px;">#</th>
                        <th style="width:30px;"><?php echo get_phrase('picture');?></th>
                        <th><?php echo get_phrase('student_information');?></th>
                        <th style="width:94px;"><?php echo get_phrase('option');?></th>
                    </tr>
                </thead>
            </table>
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
 
    $(document).ready(function(){  
      var dataTable = $('#student_data').DataTable({  
          "processing":true,  
          "serverSide":true,  
          "order":[],  
          "ajax":{  
                url:"<?php echo base_url() . 'c_student/fetch_students'; ?>",  
                type:"POST"  
          },  
          "columnDefs":[  
                {  
                     "targets":[0, 1, 2, 3],  
                     "orderable":false,  
                },  
          ],  
      });  
    });

</script>

