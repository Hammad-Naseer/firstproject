<style>
	.err_div{position:absolute;color:red;text-align:center}
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
$(window).on("load" , function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('vacations'); ?>
        </h3>
    </div>
</div>

    <form action="<?php echo base_url(); ?>vacation/vacation_settings" method="post" id="filter" class="validate">    
        <div class="row filterContainer">
            <div class="col-lg-6 col-md-6 col-sm-4" data-step="2" data-position='bottom' data-intro="Filter Record. select start date">
            	<div class="form-group">
            	        <label for="start_date_filter"><b>Start Date</b></label>
            	        <input type="text" id="start_date_filter" autocomplete="off"  class="form-control datepicker"  placeholder="Start Date">	     
                        <div id="sd" style="color: red"></div> 
            	</div>	
            </div>        		
            <div class="col-lg-6 col-md-6 col-sm-4" data-step="3" data-position='bottom' data-intro="Filter Record. select end date">
                <div class="form-group">
                        <label for="end_date_filter"><b>End Date</b></label>
                        <input type="text" id="end_date_filter" autocomplete="off"  class="form-control datepicker"  placeholder="End Date"  >	     
                        <div id="ed" style="color: red"></div>                        
                </div>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-4" data-step="4" data-position='bottom' data-intro="submit this button get filter record">
                	<input type="hidden" name="apply_filter" value="1">
                	<input type="submit" id="select" class="btn btn-primary" value="<?php echo get_phrase('filter');?>" style="position:relative;bottom: 2px;">
                	<?php  if ($apply_filter == 1) { ?> 
                        <a href="<?php echo base_url(); ?>vacation/vacation_settings" class="btn btn-danger" id="btn_remove">			
                               <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?>
                        </a>
                    <?php } ?>	
            </div>
        </div>
</form>

    <?php $status=''; ?>
    <div class="col-lg-12 col-md-12">
    <table class="table table-bordered table_export" data-step="5" data-position='top' data-intro="Filter Record">
        <thead>
            <tr>
                <th style="width:10%;" class="td_middle"><?php echo get_phrase('s_no');?></th>
                <th><?php echo get_phrase('title');?></th>
                <th style="width:10%;" class="td_middle"><?php echo get_phrase('options');?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(sizeof($qr_arry)>0)
            {
              $i=1;
              foreach($qr_arry as $rr)
              {
  	              $status=$rr['status'];
              ?>
                <tr>
                    <td class="td_middle">
                        <?php echo $i++;  ?>
                    </td>
                    <td>
                        <div class="myttl">
                            <?php echo $rr['title']; ?>
                        </div>
     
                        <div><strong><?php echo get_phrase('start_date');?>: </strong>
                            <?php echo convert_date($rr['start_date']); ?>
                        </div>
                        <div><strong> <?php echo get_phrase('end_date');?>: </strong>
                            <?php echo convert_date($rr['end_date']); ?>
                        </div>
                    </td>
                    <td class="td_middle">
                    <?php   
                    if(($status!=1)){
                            if (right_granted(array('vacationsettings_manage', 'vacationsettings_delete'))){
                    ?>
                    
                                <div class="btn-group" data-step="6" data-position='left' data-intro="vacation data edit/delete">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase('action');?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        <!-- EDITING LINK -->
                                        <?php if (right_granted('vacationsettings_manage')) { ?>
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_vacation/<?php echo $rr['holiday_id'];?>');">
                                                <i class="entypo-pencil"></i> <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                        <?php } if (right_granted('vacationsettings_delete')){ ?>
                                        <li class="divider"></li>
                                        <!-- DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>vacation/vacation_settings/delete/<?php echo $rr['holiday_id'];?>');"><i class="entypo-trash"></i> Delete</a>
                                        </li>
                                        <?php
                                        } ?>
                                    </ul>
                                </div>
                    <?php 
                            }
                    }
                    ?>
                    </td>
                </tr>
             <?php 
               }  
            }   
            ?>
        </tbody>
    </table>
    </div>

<!--//***********************Date filter validation***********************-->
<script>
    $("#start_date_filter").change(function () {
        var startDate = s_d($("#start_date_filter").val());
        var endDate = s_d($("#end_date_filter").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            
           
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("start_date_filter").value = "";
        }
        
    });
    
    $("#end_date_filter").change(function () {
        var startDate = s_d($("#start_date_filter").val());
        var endDate = s_d($("#end_date_filter").val());
       
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("end_date_filter").value = "";      
        }
        else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("end_date_filter").value = "";    
        }
    });

    function s_d(date){
      var date_ary=date.split("/");
      return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
</script>
<!--//********************************************************************-->
	


<script>
    $(document).ready(function() {
		$("#sd").html("");
		$("#ed").html("");
		$("#start_date_filter").change(function(){
		    if($("#start_date_filter").val()!=='')
			{
	            $("#sd").html("");
		    }
		});
		
		$("#end_date_filter").change(function(){
		    if($("#end_date_filter").val()!=='')
			{
		      $("#ed").html("");
		    }
		});
    });
    
    	
    function edit_data(id){
        
     	$('#span_cross').remove();
        $("#example tr").find("td").each(function(index){
            var total_val=$(this).html();
            var ind=index;
            if(ind==1 || ind==2){
        	   var arr = total_val.split('-');
               total_val=arr[1]+'/'+arr[2]+'/'+arr[0]; 	
            }
            $('#val'+ind).val(total_val);
        });	
     	
     	$('#holiday_id').val(id);
     	$('#btnn_edit').after('<span onclick="cross_edit()" id="span_cross" class="glyphicon glyphicon-remove"></span>');
        $('#btnn_edit').val("Edit Vacation");
 	
   } // edit_data  	
  
   function cross_edit(){
        $('#span_cross').remove();
        $('#val0').val("");
        $('#val1').val("");
        $('#val2').val("");
        $('#holiday_id').val("");
        $('#btnn_edit').val("Add Vacation");	
 	
  }   // cross_edit	
</script>
<!--Datatables Add Button Script-->
<?php if(right_granted('vacationsettings_manage')){ ?>
<script>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_vacation_settings_add/")';
    var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add vacation' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_vacation');?></a>";    
</script>
<?php } ?>



