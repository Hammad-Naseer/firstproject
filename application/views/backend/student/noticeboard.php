<style>
	.mytt{font-size:15px;color:#0a73b7;font-weight:700}.due{color:#972d2d}.mygrey{color:#a6a6a6}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
        <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/notice-board.png"><?php echo get_phrase('noticeboard');?>    
        </h3>
    </div>
</div> 
  
<div class="thisrown pd102">  
<form method="post" action="<?php echo base_url();?>student_p/noticeboard" class="form" data-step="1" data-position='top' data-intro="Please use this filter to get specific noticeboard record">
	<div class="row filterContainer px-0 py-5">
				<div class="col-sm-6 mt-2" data-step="2" data-position='top' data-intro="Select start date">
					<input type="text" name="starting" autocomplete="off"  id="starting" placeholder="Select Start Date" value="<?php
					if($start_date > 0)
					 {
					 	echo date_dash($start_date);
					 }
					 ?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="sd"></label>
				</div>
				<div class="col-sm-6 mt-2" data-step="3" data-position='top' data-intro="Select end date">
				<input type="text" name="ending" autocomplete="off" id="ending" placeholder="Select End Date" value="<?php 
				if($end_date > 0)
				{
					echo date_dash($end_date);
				}
				?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                <label style="color: red;" id="ed"></label>
				</div> 
				
				<div class="col-sm-6 mt-2" data-step="4" data-position='top' data-intro="Type noticeboard title">
				<input type="text" name="std_search" class="form-control" value="<?php 
                if(!empty($std_search))
                {
					echo $std_search;
				}
                ?>">
				</div>
				
				<div class="col-sm-6 mt-2">
				
				 <input type="submit" name="submit" id="submit_filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"/>
                    <?php
                    if($filter)
                    {?>
                        <a href="<?php echo base_url();?>student_p/noticeboard" class="btn btn-danger" >
                                <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                            </a>
                    <?php
                    }
                    ?>
                    <div id="error_end1" style="color:red"></div>
				
				</div>
	</div>
</form>	
</div> 
    
    <div class="col-md-12">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export" data-step="6" data-position='top' data-intro="Noticeboard records">
            <thead>
                <tr>
                    <th style="width:50px;">
                        <div><?php echo get_phrase('s_#');?></div>
                    </th>
                    <th>
                        <div>
                            <?php echo get_phrase('notice');?>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $j=$start_limit;foreach($notices as $row) { 
                $j++;
                ?>
                <tr>
                    <td class="td_middle">
                        <?php echo $j;?>
                    </td>
                    <td><span class="mytt"><?php echo $row['notice_title'];?></span>
                        <br>
                        <span class="due">	
						<?php echo  convert_date($row['create_timestamp']);?>	</span>
                        <br>
                        <?php echo $row['notice'];?>
                    </td>
                    <!--		<td><?php echo  $row['yearly_title'];?></td>
							<td><?php echo  $row['terms_title'];?></td>-->
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
$(function() { /* to make sure the script runs after page load */

    $('.item').each(function(event) { /* select all divs with the item class */

        var max_length = 200; /* set the max content length before a read more link will be added */

        if ($(this).html().length > max_length) { /* check for content length */

            var short_content = $(this).html().substr(0, max_length); /* split the content in two parts */
            var long_content = $(this).html().substr(max_length);

            $(this).html(short_content +
                '<a href="#" class="read_more mycolor"><br/>Read More</a>' +
                '<span class="more_text" style="display:none;">' + long_content + '</span>'); /* Alter the html to allow the read more functionality */

            $(this).find('a.read_more').click(function(event) { /* find the a.read_more element within the new html and bind the following code to it */

                event.preventDefault(); /* prevent the a from changing the url */
                $(this).hide(); /* hide the read more button */
                $(this).parents('.item').find('.more_text').show(); /* show the .more_text span */

            });

        }

    });


});
</script>

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
