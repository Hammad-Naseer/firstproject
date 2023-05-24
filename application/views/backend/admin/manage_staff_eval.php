<?php
if (right_granted('staffevaluationsettings_view'))
{


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
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('staff_evaluation'); ?>
            </h3>
        </div>
   </div>
    
    
    
<form id="filter" action="<?php echo base_url(); ?>staff_evaluation/evaluation"  method="post" data-step="2" data-position='top' data-intro="use this filter for specific staff evalution record">
     
    <div class="row filterContainer" style="padding-top: 14px;margin:0px;margin-right: 30px !important;margin-left: 30px !important;">
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label class=" control-label"><?php echo get_phrase('select_staff');?><span class="red"> * </span>  </label>
            <select name="staff_id" id="staff_id" class="form-control" required>
                <option value="">
                 <?php echo get_phrase('select_staff');?>
                </option>
                <?php 
                $qry = "SELECT * FROM ".get_school_db().".staff WHERE
                school_id=".$_SESSION['school_id']." ";
                $queryddl=$this->db->query($qry)->result_array();
                foreach($queryddl as $teacher)
                {
                	$opt_selected="";
                		if($teacher['staff_id'] == $staff_id){
                			$opt_selected="selected";
                		}
                ?>
                <option value="<?php echo $teacher['staff_id'];?>" <?php echo $opt_selected;?>>
                    <?php echo $teacher['name'];?>
                </option>
                <?php
                }
                ?>
            </select>
            <div id="mydiv" class="red"></div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label class=" control-label"><?php echo get_phrase('start_date');?> </label>
             <input type="text" name="start_date" autocomplete="off"  id="start_date" placeholder="Select Starting Date" class="form-control datepicker" data-format="dd/mm/yyyy" value="<?php echo $start_date;?>">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 form-group">
            <label class=" control-label"><?php echo get_phrase('end_date');?></label>
              <input type="text" name="end_date" autocomplete="off"  id="end_date" placeholder="Select Ending Date" class="form-control datepicker" data-format="dd/mm/yyyy" value="<?php echo $end_date;?>">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                <input type="hidden" name="apply_filter" value="1" />
                <button type="submit" class="modal_save_btn" id="btn_submit"><?php echo get_phrase('filter');?></button>
                <?php
                if($apply_filter == 1){
                ?>
                        <a href="<?php echo base_url(); ?>staff_evaluation/evaluation" class="modal_cancel_btn" id="btn_remove"> 
                            <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?>
                        </a>
                <?php
                }
                ?>
            
        </div>
    </div>
        
</form>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
          
        <table class="table table-striped table-bordered table_export" data-step="3" data-position='top' data-intro="staff evalution record">
            <thead>
                <tr>
                    <th style=" width:54px !important;"><?php echo get_phrase('s_no');?></th>
                    <th>
                        <?php echo get_phrase('details');?>
                    </th>
                    <th>
                         <?php echo get_phrase('student\'s_ratings');?>
                    </th>
                    <th style="width:94px;">
                        <?php echo get_phrase('options');?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1;
                foreach($staff_evaluations as $row){
                            ?>
                <tr>
                    <td class="td_middle">
                        <?php echo $count++;?>
                    </td>
                    <td>
                    
                    <div class="myttl"><?php echo $row['staff_name'];?></div> 
                    <div><strong><?php echo get_phrase('rating');?>:</strong> <?php echo get_evaluation_rating_by_id($row['answers'])->detail;?></div>  
                    <div><strong><?php echo get_phrase('remarks');?>:</strong>  <?php echo $row['remarks'];?></div>           
                    <div><strong><?php echo get_phrase('evaluation_date');?>:</strong> <?php echo convert_date($row['evaluation_date']);?></div>       
                    <?php
                        	$attachment=$row['attachment'];	
                            $val_im=display_link($attachment,'staff_evaluation',0,0); 
                            if($val_im!=""){
                        ?>	
                        <div><strong><?php echo get_phrase('attachment');?>:</strong>
                        <a target="_blank" href="<?php echo $val_im; ?>" class="img-responsive img-thumbnail"><span class="glyphicon glyphicon-download-alt"></span></a>
                        <?php  } ?>
                    </div> 
                    
                    
                    
                    
                    
                    </td>
                    <td>
                         <?php
                         
                            $is_teacher = $this->db->query("select is_teacher from " . get_school_db() . ".designation WHERE designation_id = ".$row['designation_id']." AND school_id =" . $_SESSION['school_id']."")->row();
                            if($is_teacher->is_teacher){
                         
                                $my_rating = $this->db->query("select AVG(rating) as average_rating from " . get_school_db() . ".teacher_rating WHERE teacher_id = ".$row['staff_id']." AND school_id =" . $_SESSION['school_id']."")->row();
                                if(count($my_rating)>0){
                                ?>
                                    
                                    <div class="avg">
                                        <div class='rating-stars text-center'>
                                            <?php
                                                $style_avg = 0;
                                                if($my_rating->average_rating > 0 And $my_rating->average_rating < 1)
                                                    $style_avg = $my_rating->average_rating*100;
                                                if($my_rating->average_rating > 1 And $my_rating->average_rating < 2)
                                                    $style_avg = ($my_rating->average_rating - 1)*100;
                                                if($my_rating->average_rating > 2 And $my_rating->average_rating < 3)
                                                    $style_avg = ($my_rating->average_rating - 2)*100;
                                                if($my_rating->average_rating > 3 And $my_rating->average_rating < 4)
                                                    $style_avg = ($my_rating->average_rating - 3)*100;
                                                if($my_rating->average_rating > 4 And $my_rating->average_rating < 5)
                                                    $style_avg = ($my_rating->average_rating - 4)*100;
                                                $style_avg = 100 - $style_avg;
                                                $style = 'background: -webkit-linear-gradient(180deg, #ccc '.$style_avg.'%, #FF912C 0%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;';
                                            ?>
                                            <ul id='stars' style="font-size: 8px;">
                                                <li class='star-item <?php echo ($my_rating->average_rating>0)?'selected':'';?>' title='Poor'>
                                                    <i class='fa fa-star fa-fw' style="<?php echo ($my_rating->average_rating>0 And $my_rating->average_rating < 1)?$style:'';?>padding-right:0px;"></i>
                                                </li>
                                                <li class='star-item <?php echo ($my_rating->average_rating>1)?'selected':'';?>' title='Fair'>
                                                    <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>1 And $my_rating->average_rating < 2)?$style:'';?> padding-right:0px;"></i>
                                                </li>
                                                <li class='star-item <?php echo ($my_rating->average_rating>2)?'selected':'';?>' title='Good'>
                                                    <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>2 And $my_rating->average_rating < 3)?$style:'';?>padding-right:0px;"></i>
                                                </li>
                                                <li class='star-item <?php echo ($my_rating->average_rating>3)?'selected':'';?>' title='Excellent'>
                                                    <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>3 And $my_rating->average_rating < 4)?$style:'';?>padding-right:0px;"></i>
                                                </li>
                                                <li class='star-item <?php echo ($my_rating->average_rating>4)?'selected':'';?>' title='Awesome'>
                                                    <i class='fa fa-star fa-fw'  style="<?php echo ($my_rating->average_rating>4 And $my_rating->average_rating < 5)?$style:'';?>padding-right:0px;"></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <center>Average Rating:  <strong><?php echo round($my_rating->average_rating, 2)?></strong></center>
                                    </div>
                            <?php }
                        }?>
                    </td>
                    <td class="td_middle">
                        <?php 
                        if (right_granted(array('staffevaluationsettings_delete', 'staffevaluationsettings_manage')))
                        {?>
                        <div class="btn-group" data-step="4" data-position='left' data-intro="staff evalution view  / edit / delete options">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <?php echo get_phrase('action');?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <!-- EDITING LINK -->
                                <?php 
                                if (right_granted('staffevaluationsettings_view'))
                                {?>
                                    <li>
                                        <a href="<?php echo base_url();?>staff_evaluation/view_evaluation_answers/<?php echo str_encode($row['staff_eval_id']);?>/<?php echo str_encode($row['evaluation_date']);?>/<?php echo str_encode($row['staff_eval_id']);?>/<?php echo str_encode($row['staff_id']);?>/<?php echo str_encode($start_date);?>/<?php echo str_encode($end_date);?>">
                                            <i class="fa fa-eye"></i>
                                            <?php echo get_phrase('view');?>
                                        </a>
                                    </li>
                                <?php
                                }
                                if (right_granted('staffevaluationsettings_manage'))
                                {
                                ?>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo base_url();?>staff_evaluation/view_staff_add/<?php echo str_encode($row['staff_eval_id']);?>/<?php echo str_encode($row['evaluation_date']);?>/<?php echo str_encode($row['staff_eval_id']);?>/<?php echo str_encode($row['staff_id']);?>/<?php echo str_encode($start_date);?>/<?php echo str_encode($end_date);?>">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                <?php 
                                }
                                if (right_granted('staffevaluationsettings_delete'))
                                {?>
                                    <li class="divider"></li>
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>staff_evaluation/evaluation/delete/<?php echo $row['staff_eval_id'];?>/<?php echo $row['attachment'];?>');">
                                            <i class="entypo-trash"></i>
                                            <?php echo get_phrase('delete');?>
                                        </a>
                                    </li>
                                <?php 
                                }?>
                            </ul>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
          
    </div>    
</div>
    
    
    <script>
        <!--Datatables Add Button Script-->
        <?php if(right_granted('staffevaluationsettings_manage')){ ?>
            var datatable_btn = "<a data-step='1' data-position='left' data-intro='Press this button to add staff evalution' class='modal_open_btn' href='<?php echo base_url();?>staff_evaluation/view_staff_add'><?php echo get_phrase('add_staff_evaluation');?></a>";    
        <?php } ?>
    </script>
<?php
}
?>

<!--//***********************Date filter validation***********************-->
<script>
    $("#start_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("start_date").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#end_date").change(function () {
        var startDate = s_d($("#start_date").val());
        var endDate = s_d($("#end_date").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("end_date").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->
