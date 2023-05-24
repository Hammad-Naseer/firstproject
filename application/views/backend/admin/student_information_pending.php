<?php
if ( $this->session->flashdata( 'candidate_delete' ) ) {
    echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'candidate_delete' ) . '
	</div> 
	</div>';
}

if ( $this->session->flashdata( 'delete_challan_form' ) ) {
    echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'delete_challan_form' ) . '
	</div> 
	</div>';
}

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('candidates_list');?>
        </h3>
    </div>
</div>
<style>
    .fas{
        font-size: 16px;
        text-shadow: 0px 0px 1px #ccc;
    }
    .ptag {
        margin: 0px;
        padding: 0px;
    }
    
    .fa-mobile {
        font-size: 24px;
    }
</style>

<div class="row filterContainer" data-step="1" data-position='top' data-intro="Please select filters and press filter button to get specific candidates">
    <form action="<?php echo base_url(); ?>c_student/get_student_pending_report" class="col-md-12 col-lg-12 col-sm-12" method="post" name="student_form">
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
        <div class="col-md-6 col-lg-6 col-sm-6 mt-2">
              <input type="hidden" name="apply_filter" value="1">
              <input type="submit" value="Filter" class="btn btn-primary">
              <?php if ($apply_filter == 1){?>
                <a id="btn_show" href="<?php echo base_url(); ?>c_student/student_pending" class="btn btn-danger" style="padding:5px 8px !important;"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?></a>
              <?php } ?>
        </div>
    </form>
</div>
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
<script>
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<div class="row mt-4">
    <div data-step="2" data-position='top' data-intro="candidates record" style="width:100%">
    <table class="table table-bordered table_export">
        <thead>
        <tr>
            <th style="width:34px;">
                <div>
                    <?php echo get_phrase('s_no');?>
                </div>
            </th>
            <th style="width:34px;">
                <div>
                    <?php echo get_phrase('picture');?>
                </div>
            </th>
            <th>
                <div>
                    <?php echo get_phrase('candidates_detail');?>
                </div>
            </th>
            <th style="width:94px;">
                <div>
                    <?php echo get_phrase('options');?>
                </div>
            </th>
            <!--<th><div><?php echo get_phrase('status');?></div></th>-->
        </tr>
    </thead>
    <tbody>
        <?php 
    

$j=0;
foreach($students as $row)
{
	$j++;
	?>
        
        <tr>
                <td class="td_middle">
                    <?php echo $j; ?>
                </td>
                <td class="td_middle">
                    <div>
                        <img src="<?php
                            if($row['image']==''){
                            echo  base_url().'/uploads/default.png'; 
                            }else{
                            echo  display_link($row['image'],'student');
                            }?>" class="img-circle" width="30" />
                    </div>
                </td>
                <td>
                    <div class="myttl">
                        <?php echo $row['name'];?>
                        <spav style="font-size:12px;">
                            <?php echo ' (Form#: '.$row['form_num'].')';?>
                            </span>
                            <span style="color:#3e9365;">   
    
                                  <?php echo  '('.student_status($row['student_status']).')';  
                            if($row['is_readmission']==1){
                              
                              echo "(".get_phrase("readmission").")";
                              
                            } 
                            ?>
                                 
                              
                            </span>
                            <span style="display: none;"><?php echo  $row['system_id']; ?></span>
                    </div>
                    <div><strong><?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>
                        <ul class="breadcrumb breadcrumb2" style="    display: inline;  padding: 0px;    margin-left: 5px;    color: #428abd;">
                            <li>
                                <?php echo $row['department_name'];?>
                            </li>
                            <li>
                                <?php echo $row['class_name'];?> </li>
                            <li>
                                <?php echo $row['section_name'];?>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="ptag">
                            <strong><?php echo get_phrase('mobile');?>#: </strong>
                            <?php echo $row['mob_num'];?>
                        </p>
                    </div>
                    <?php if($row['p_name'] != ""){ ?>
                    <div>
                        <strong>   <?php echo get_phrase('parent_name');?>: </strong>
                        <?php echo $row['p_name'];?>
                    </div>
                    <?php }if($row['contact'] != ""){ ?>
                    <div>
                        <strong>   <?php echo get_phrase('parent contact');?>: </strong>
                        <?php echo $row['contact'];?>
                    </div>
                    <?php } ?>
                </td>
                <td class="td_middle">
                    <?php
                   // echo "dfdf".$row['student_status'];
                    if (right_granted(array('candidatelist_manage', 'candidatelist_view')))
                    {
                    ?>

                    <div class="btn-group" data-step="6" data-position='left' data-intro="student profile , print chalan , update guardian details , receive chalan , cancel chalan , issue study pack , approve admission">
                    <?php if($row['is_deleted']==0)
                    {?>
						
					
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                        </button>
                        
                    <?php
                    }
                    if($row['is_deleted']==1)
                    {
						echo "Deleted";
					}
                    ?>    
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <?php
                            if (right_granted('candidatelist_view'))
                            {
                            ?>
                            <li>
                            <?php
                            $c_profile="";
                            if($controller=='c')
                            {
                            
                            	$c_profile="c_student/student_detail/".$controller."/".str_encode($row['student_id'])."/".str_encode($section_id)."/".$student_status;
                          }
                            ?>
                            
								 <a href="<?php echo base_url(); ?><?php echo $c_profile;?>">
							
                                    <i class="fas fa-user-friends"></i>
                                    <?php echo get_phrase('profile');?>
                                </a>
                            </li>
                            <?php
                            }
                            if (right_granted('candidatelist_manage'))
                            {
                            ?>
                                <!-- STUDENT EDITING LINK -->
                                <li>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_candidate_edit/<?php echo $row['student_id'].'/'.$row['status']; ?>');">
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('edit');?>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/approve_admission/<?php echo $row['student_id']?>');">
                                        <i class="fas fa-user-check"></i>
                                        <?php echo get_phrase('admission_approve  '); ?> / <?php echo get_phrase('confirm');?>
                                    </a>
                                </li>
                                <!-- STUDENT DELETION LINK -->
                                <li>
                            <?php
                                $c_update="";
                                if($controller=='c')
                                {
                                	$c_update="admin/update_parent/".$controller."/".str_encode($row['student_id'])."/".str_encode($section_id)."/".$student_status;
                                }
                            ?>      
                                    <a href="<?php echo base_url(); ?><?php echo $c_update;?>">
                                        <i class="las la-file-invoice-dollar"></i>
                                        <?php echo get_phrase('update_parent'); ?>/<?php echo get_phrase('guardian');?>
                                    </a>
                                </li>

                            <!-- STUDENT DELETION LINK -->
                            <li class="divider"></li>
                            <!-- STUDENT chalan LINK -->
                            <?php
    if($row['student_status']>6){
  }else{
  ?>
                                <li>
                                    <?php //$frm_tp = 2; ?> 
                                    <a href="<?php echo base_url(); ?>class_chalan_form/student_chalan_form/<?php echo str_encode($row['student_id']); ?>/<?php echo $frm_tp; ?>/1">
                                        <i class="las la-file-alt"></i>
                <?php
                  if($row['status']==4) 
                  {
				  	echo get_phrase('receive_chalan_form');
				  }
				  else
				  {
				  	echo get_phrase('manage_chalan_form'); 
				  }     
                   ?>                      
                                    </a>
                                </li>
                                <?php }
?>
                                <?php
             if($row['student_status']==6){
      ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>class_chalan_form/view_print_chalan/<?php echo  $row['s_c_f_id']; ?>/1">
                                            <i class="fas fa-print"></i>
                                            <?php echo get_phrase('print_chalan_form'); ?>
                                        </a>
                                    </li>
                                    <?php }
                                elseif($row['student_status']==7)
                                {
                                  ?>
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>class_chalan_form/study_pack/<?php echo $row['student_id']?>');">
                                            <i class="fas fa-suitcase-rolling"></i>
                                            <?php echo get_phrase('deliver_study_pack'); ?>
                                        </a>
                                    </li>
                                    <?php }
                                    elseif($row['student_status']==8 || $row['student_status']==9 )
                                    {
?>
                                    <!--<li>-->
                                    <!--    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/approve_admission/<?php echo $row['student_id']?>');">-->
                                    <!--        <i class="fas fa-user-check"></i>-->
                                    <!--        <?php //echo get_phrase('admission_approve  '); ?>/<?php //echo get_phrase('confirm');?>-->
                                    <!--    </a>-->
                                    <!--</li>-->
                                        <?php }
 ?>
                                    <?php if($row['student_status']<7){  ?>
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>c_student/delete_student/<?php echo $row['student_id'].'/'.$row['is_readmission'];?>');">
                                            <i class="entypo-trash"></i>
                                            <?php echo get_phrase('delete');?>
                                        </a>
                                    </li>
                                    <?php }
?>
                                    <?php
                                       // if($row['status']!="")
                                       // {
                                           if($row['student_status']<7 ){
                                             ?>
                                                <li>
                                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>c_student/cancel_chalan_request/<?php echo $row['student_id']; ?>');">
                                                        <i class="fas fa-ban"></i>
                                                        <?php echo get_phrase('cancel_chalan'); ?>
                                                    </a>
                                                </li>
                                                <?php }
                                       // }
                                }
                                        ?>
                        </ul>
                    </div>
                    <?php
                    }
                    ?>
                </td>
                <?php if($row['account_status']==0)
                {
                      $account_status=1;
                }else
                {
                      $account_status=0;
                }
                    ?>
                            </tr>
                            <?php } ?>
                        </tbody>
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

</script>