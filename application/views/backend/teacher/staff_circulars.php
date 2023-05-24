<style>	
	.mycolor{color:#00859a;     font-weight: bold;}	
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="staff_circular_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('staff_circulars'); ?>
        </h3>
    </div>
</div>

<form method="post" action="<?php echo base_url();?>teacher/staff_circular" class="form">
    
    <div class="row filterContainer" data-step="1" data-position='top' data-intro="Please select the filters and press Filter button to get specific records">
        
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="text" name="starting" id="starting" autocomplete="off"  placeholder="Select Starting Date" value="<?php echo date_dash($start_date);?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="sd"></label>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="text" name="ending" id="ending" autocomplete="off"  placeholder="Select Ending Date" value="<?php echo date_dash($end_date);?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                <label style="color: red;" id="ed"></label>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<select id="circular_select" name="circular_select" class="form-control" >
				       <?php
				       $selected="";
				       ?>
				       <option value=""><?php echo get_phrase('select_circular_type');?></option>
                        <option value="all_circulars" <?php 
                        if($circular_val=='all_circulars')
				       {
					   	echo $selected='selected';
					   }
                        ?>
                        ><?php echo get_phrase('all_circulars');?></option>
                        <option value="my_circulars" <?php 
                        if($circular_val=='my_circulars')
				       {
					   	echo $selected='selected';
					   }
                        ?>><?php echo get_phrase('my_circulars');?></option>
                        <option value="general_circulars" <?php 
                        if($circular_val=='general_circulars')
				       {
					   	echo $selected='selected';
					   }
                        ?>
					   ><?php echo get_phrase('general_circulars');?></option>
                </select>
    		</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
    			<input type="submit" name="submit" id="submit_filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"/>
                <?php
                if($filter)
                {
                ?>
                    <a href="<?php echo base_url();?>teacher/staff_circular" class="btn btn-danger">
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

<div class="col-md-12" data-step="2" data-position='top' data-intro="circulars records">
    <table cellpadding="0" cellspacing="0" style="width:100%;" border="0" class="table table-bordered table_export">
                	<thead>
                		<tr>
                            <td style="width:30px;"><div><?php echo get_phrase('s_no');?></div></td>
                            <td></td>
						</tr>
					</thead>
                    <tbody>
                    <?php $count = 1;foreach($query as $row)
                    { ?>
                        <tr>
                            <td class="td_middle"><?php echo $count++;?></td>
                            <td style="line-height:22px;">
							<div style="color:#00859a; font-size:14px; font-weight:bold; text-transform:capitalize;"><?php echo $row['circular_title'];
							
                            
                           if($row['attachment']!="")
						   {
						   ?>
							  <a href="<?php echo display_link($row['attachment'],'circular_staff');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
						   <?php	
                            }
						   ?>
							</div>
							<div class="span5 item">
							<?php 
							if($row['staff_id']!=''){
								echo "<strong>".get_phrase('staff').": </strong>".$row['staff_name'];
								echo "<br/>";
                            }
							?>
                            </div>
                            <div><strong><?php echo get_phrase('detail');?>:</strong>
							<?php echo $row['circular']; ?></div>
                            <div style="float:right; font-weight:bold;"><?php echo convert_date($row['circular_date']);?></div>
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