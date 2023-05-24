<?php if (right_granted('manage_teacher_swapping','view_teacher_swapping')){ ?>

<style>
.validate-has-error {
    color: red;
}
.mange-tchr-swap {
    padding: 0;
}
</style>
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
    <?php
     if (right_granted('manage_teacher_swapping'))
    {
    ?>
    
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('manage_teacher_swapping'); ?>
            </h3>
        </div>
    </div>
            

    
    <?php
    }
    ?>

<br/><br/>


<!--  -->
<div class="col-lg-12 col-sm-12 mange-tchr-swap">
    <form data-step="2" data-position='top' data-intro="filter teacher swapping records" action="<?php echo base_url(); ?>swap/swapping" method="post" id="filter" class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;">
        <div class="row filterContainer" style="padding-top: 14px;margin:0px;">
            
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="form-group">
                     <input type="text" name="starting" autocomplete="off"  id="starting" value="<?php if($start_date!=0){ echo date_dash($start_date); }?>" placeholder="Select Starting Date" class="form-control datepicker" data-format="dd/mm/yyyy"> 
                </div>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="form-group">
                     <input type="text" name="ending" autocomplete="off"  id="ending" value="<?php if($end_date!=0){echo date_dash($end_date);}?>" placeholder="Select Ending Date" class="form-control datepicker" data-format="dd/mm/yyyy">
                </div> 
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="form-group">
                    <select name="teacher_select" id="teacher_select" class="form-control">
                      <?php echo teacher_designation_option_list($teacher_id);?>
                    </select>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="form-group" style="margin-top: -3px;">
                    <input type="hidden" name="apply_filter" value="1">
                    <input type="submit" value=" <?php echo get_phrase('filter');?>" class="btn btn-primary" id="btn_submit">
                    <a href="<?php echo base_url(); ?>swap/swapping" <?php
                        $style="";
                        if( ( isset($teacher_id) && ($teacher_id>0) ) || ( isset($start_date ) && ($start_date > 0) ) || ( isset($end_date) && ($end_date > 0)) ) { } else{ $style='style="display:none;"'; } ?> 
                        class="btn btn-danger" <?php echo $style;?> id="btn_remove"> 
                            <i class="fa fa-remove"></i> <?php echo get_phrase('remove_filter');?>
                    </a>  
                </div>
            </div>
        </div> 
    </form>
</div>



            
 <div class="row">
    <div class="col-lg-12 col-md-12">
         <table class="table table-striped table-bordered table_export table-responsive" data-step="3" data-position='top' data-intro="swapping teachers record" cellspacing="0" width="100%">
	        <thead>
            <tr>
                <th>
                    <div>
                        <?php echo get_phrase('title');?>
                    </div>
                </th>
                <th style="width:100px;">
                    <div>
                        <?php echo get_phrase('swap_date');?>
                    </div>
                </th>
                <th style="width:230px;">
                	<div>
                		 <?php echo get_phrase('from_teacher');?>
                	</div>
                </th>
                <th style="width:230px;">
                	<div>
                		 <?php echo get_phrase('to_teacher');?>
                	</div>
                </th>
                <th>
                	<div>
                		 <?php echo get_phrase('options');?>
                	</div>
                </th>
               
                
            </tr>
        </thead>
            <tbody>
                <?php 
                	if(count($swap_quer) > 0)
                	{
                    
                    foreach($swap_quer as $swap_quer1)
            		{
            	?>
                    <tr>
                    	
                    	<td><?php echo $swap_quer1['title'].'<br>'.'<strong>Comments: </strong>'.$swap_quer1['comments'];?></td>
                    	<td><?php echo convert_date($swap_quer1['swap_date']);?></td>
                    	
                    	<td>
                    	<?php
                        	    $swap1 = get_from_swapping($swap_quer1['swap_id'],1);
                        	    $class1 = section_hierarchy($swap1[0]['section_id']);
                				echo "<strong>Teacher: </strong>".get_teacher_name($swap1[0]['teacher_id']);
                				echo "<br>";
                				echo "<strong>Subject: </strong>".get_subject_name($swap1[0]['subject_id']);
                				echo "<br>";
                				echo "<strong>Class-Section: </strong>". $class1[c] . ' - '. $class1[s];
                				echo "<br>";
                				echo "<strong>Timings: </strong>".$swap1[0]['start_time'].' - '. $swap1[0]['end_time'];
                				echo "<br>";
                				echo "<strong>Day/Period: </strong>".ucfirst($swap1[0]['day']).' - '.$swap1[0]['period_no'];
                    	?>
                    	</td>
                    	<td>
                    		<?php 
                    		$swap2 = get_from_swapping($swap_quer1['swap_id'],2);
                    		$class2 = section_hierarchy($swap2[0]['section_id']);
            				echo "<strong>Teacher: </strong>".get_teacher_name($swap2[0]['teacher_id']);
            				echo "<br>";
            				echo "<strong>Subject: </strong>".get_subject_name($swap2[0]['subject_id']);
            				echo "<br>";
            				echo "<strong>Class-Section: </strong>".$class1[c] . ' - '. $class1[s];
            				echo "<br>";
            				echo "<strong>Timings: </strong>".$swap2[0]['start_time'].' - '. $swap2[0]['end_time'];
            				echo "<br>";
            				echo "<strong>Day/Period: </strong>".ucfirst($swap2[0]['day']).' - '.$swap2[0]['period_no'];
            			?>
                    	</td>
                    	
                    	<td>
                    		  
                        <?php
                            $swap_quer1['swap_date'];
                            $current_date=date('Y-m-d');
                            if($swap_quer1['swap_date'] >= $current_date)
                            {
                            	if (right_granted('manage_teacher_swapping')){
                        ?>
                            <div class="btn-group" data-step="4" data-position='left' data-intro="swapping teacher edit / delete options">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?php echo get_phrase('action');?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
            					    <li>
                                        <a href="<?php echo base_url();?>swap/teacher_swapping/edit/<?php echo $swap_quer1['swap_id'];?>">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                          
                                           
                                            <li class="divider"></li>
                                            <!-- DELETION LINK -->
                                            <li>
                                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>swap/teacher_swapping/delete/<?php echo $swap_quer1['swap_id'];?>');">
                                                    <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('delete');?>
                                                </a>
                                            </li>
                                     </ul>
                                    </div>        
            				<?php
            				}
            			}  
                            ?> 
                                           
                                           
                                       
                    	</td>
                    	
                    </tr>
            			
            		<?php } } ?>
            </tbody>
        </table>
    </div>
</div> 


    
 <?php
 }
 ?>  
 
 <!--Datatables Add Button Script-->
<?php if(right_granted('manage_teacher_swapping')){ ?>
<script>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_location/")';
    var datatable_btn = "<a data-step='1' data-position='left' data-intro='press this button you redirect next page then select teacher & period & date for swapping' href='<?php echo base_url();?>swap/teacher_swapping' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_teacher_swapping');?></a>";    
</script>
<?php } ?>


<!--//***********************Date filter validation***********************-->
<script>
    $("#starting").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("starting").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php //echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php //echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        //toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("starting").value = "";      
        // }
    });
    
    $("#ending").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
        
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("ending").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("ending").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->