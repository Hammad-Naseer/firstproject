<?php $this->load->helper('report'); ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('assign_event_announcement');?>
            	</div>
            </div>
			<div class="panel-body">    	          
               	     <div class="box-content">
                	<?php echo form_open(base_url().'event_annoucments/events_program/assign/'.$param2 , array('id'=>'exam_weightage_add','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                         <div class="form-group" id="departments_id1">
                             <h3>Select Department</h3>
                             <table class="table">
                             <?php
                                $qd = "select departments_id , title from ".get_school_db().".departments where school_id=".$_SESSION['school_id']."";
                                $dept = $this->db->query($qd)->result_array();
                                foreach($dept as $d){
                             ?>
                             <tr>
                            
                             <td><strong><?php echo $d['title'];?></strong></td>
                             <td><input type="checkbox" name="depts[]" class="depts" value="<?php echo $d['departments_id'];?>"></td>
                             </tr>
                             <?php } ?>
                             </table>
                             <button type="button" class="modal_save_btn fetch_classes">Fetch Classes</button>
                         </div>
                         <div class="form-group">
                             <h3 id="classs_id1">Select Class</h3>
                             <br>
                             <div class="col-lg-12 col-md-12 classes_data">
                                 
                             </div>
                         </div>
                         <div class="form-group">
                            <div class="float-right">
                                <button type="submit" class="modal_save_btn"><?php echo get_phrase('announce_event');?></button>
                                <button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
                                    <?php echo get_phrase('cancel');?>
                                </button>
                            </div>
                         </div>
                    </form>                
                </div>      
            </div>      
        </div>      
    </div>      
</div>
  <script src="<?php echo base_url(); ?>assets/js/common.js"></script>
  
  <script>
$(document).ready(function(){
    
    $(".fetch_classes").click(function(){
        var test = new Array();
        $(".depts:checked").each(function() {
            test.push($(this).val());
        });
        $('#departments_id1').after('<div id="loader" class="loader_small"></div>');
        $.ajax({
            type: 'POST',
            data: {departments_id:test},
            url: "<?php echo base_url();?>departments/get_class_for_event",
            success: function(response)
            {
                $(".classes_data").html(response);
                // $(".classes_data").after('<button type="button" class="btn btn-primary btn-sm fetch_sections" style="font-size: 12px;border-radius: 0px;padding: 5px !important;">Fetch Sections</button>');
                $('#loader').remove();
            }
        });
    });
    
    // get_dt();
    // $("#departments_id1").change(function(){
    //     get_dt();
    // });
    
/*    $("#class_id1").change(function(){
        get_sec();
    });*/
});

// function get_dt()
// {
//     var departments_id=$("#departments_id1").val();
//     var RemoveFirstCharSub = departments_id.substring(1);
//     var clscomp_id="<?php echo $clr_id;  ?>";
//     if(RemoveFirstCharSub !="")
//     {
//     $('#departments_id1').after('<div id="loader" class="loader_small"></div>');
//         $.ajax({
//             type: 'POST',
//             data: {departments_id:RemoveFirstCharSub,clscomp_id:clscomp_id},
//             url: "<?php echo base_url();?>departments/get_class",
//             dataType: "html",
//             success: function(response)
//             {
//                 $("#class_id1").html(response);
//                 $('#loader').remove();
//             }
//         });
//     }

//     else
//     {
//         $("#class_id1").html("<option value=''><?php echo get_phrase('select'); ?></option>");
//         $("#section_id1").html("<option value=''><?php echo get_phrase('select'); ?></option>");
//     }
// }

// function get_sec()
// {
//     var class_id = $("#class_id1").val();
//     if(class_id !="")
//     {
//         $('#class_id1').after('<div id="loader" class="loader_small"></div>');
//         $.ajax({
//             type: 'POST',
//             data: {class_id:class_id},
//             url: "<?php echo base_url();?>departments/get_sections",
//             dataType: "html",
//             success: function(response)
//             {
//                 $("#section_id1").html(response);
//                 $('#loader').remove();
//             }
//         });
//     }

//     else
//     {
//         $("#section_id1").html("<option value=''><?php echo get_phrase('select'); ?></option>");
//     }
// }
</script>
