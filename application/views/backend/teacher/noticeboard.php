<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('noticeboard'); ?>
        </h3>
    </div>
</div>

<style>
	.mycolor{color:#00859a; font-weight: bold;}
</style>


<form method="post" action="<?php echo base_url();?>teacher/noticeboard" class="form" data-step="1" data-position='top' data-intro="Please select the filters and press Filter button to get specific records">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			 <input type="text" name="std_search" class="form-control" value="<?php
                    if(!empty($std_search))
                    {
    					echo $std_search;
    				}
                    ?>">
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="text" name="starting" autocomplete="off"  id="starting" placeholder="Select Starting Date" value="<?php
			    if($start_date > 0)
			    {
				    echo date_dash($start_date);
			    }
			    ?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                <label style="color: red;" id="sd"></label>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="text" name="ending" autocomplete="off"  id="ending" placeholder="Select Ending Date" value="<?php
			    if($end_date > 0)
			    {
				    echo date_dash($end_date);
			    }
			    ?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                <label style="color: red;" id="ed"></label>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="submit" name="submit" id="submit_filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"/>
                <?php
                if($filter)
                {?>
                    <a href="<?php echo base_url();?>teacher/noticeboard" class="btn btn-danger" >
                            <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                        </a>
                <?php
                }
                ?>
                <div id="error_end1" style="color:red"></div>
    		</div>
		</div>
		
	</div>
</form>

 
<div class="col-lg-12 col-md-12">
    <table cellpadding="0" cellspacing="0" border="0" style="width:100%" class="table-bordered table_export" data-step="2" data-position='top' data-intro="noticeboard records">
    	<thead>
    		<tr>
                <td   style="width:30px;"><div><?php echo get_phrase('s_no');?></div></td>
                <td></td>
			</tr>
		</thead>
        <tbody>
        	<?php
        	$j=$start_limit;
        	foreach($notices as $row){
        	$j++;	
        	
        	?>
            <tr>
                <td class="td_middle"><?php echo $j;?></td>
                <td>
				<div style="color:#00859a; font-size:14px; font-weight:bold;"><?php echo $row['notice_title'];?></div>
				<div class="span5 item" style="margin-top:5px; line-height:20px;">
					<?php echo $row['notice'];?>
				</div>
				<div style="float:right; font-weight:bold;">
					<?php echo convert_date($row['create_timestamp']);?>
				</div>
				</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



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
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
        toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
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
