<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('circulars');?>        
        </h3> 
    </div>
</div>

<div class="thisrown pd102">
    <form method="post" action="<?php echo base_url();?>parents/circulars" class="form">
	    <div class="row filterContainer px-2 py-5" data-step="1" data-position='top' data-intro="Please use this filter to get specific circulars record">
				<div class="col-sm-6" data-step="2" data-position='top' data-intro="Select start date">
					<input type="text" name="starting" autocomplete="off"  id="starting" placeholder="Select Start Date" value="<?php echo date_dash($start_date);?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="sd"></label>
				</div>
				<div class="col-sm-6" data-step="3" data-position='top' data-intro="Select end date">
				    <input type="text" name="ending" autocomplete="off"  id="ending" placeholder="Select End Date" value="<?php echo date_dash($end_date);?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="ed"></label>
				</div>
				<div class="col-sm-12" data-step="4" data-position='top' data-intro="Press filter button to get records">
				    <input type="submit" name="submit" id="submit_filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"/>
                    <?php if($filter) { ?>
                        <a href="<?php echo base_url();?>teacher/staff_circular" class="btn btn-danger" >
                            <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                        </a>
                    <?php } ?>
                    <div id="error_end1" style="color:red"></div>
				</div>
			</div>
    </form>	
</div>
	<div class="col-md-12 p-3">
                <table class="table table-bordered table_export" data-step="5" data-position='top' data-intro="Circulars records">
                	<thead>
                		<tr>
                    		<th style="width: 30px;"><div>S #</div></th>
                    		<th><div><?php echo get_phrase('circular');?></div></th>
                        </tr>
					</thead>
                    <tbody>
					<?php
					 $count = 1;
					 foreach($circulars as $row):?>
                        <tr>
                            <td class="td_middle"><?php echo $count++;?></td>
							<td>
								<span class="mytt">
							<?php echo $row['circular_title'];?>
								</span>
								<?php
							
							if($row['attachment']!="")
						    {?>
								<a target="_blank" href="<?php echo display_link($row['attachment'],'circular');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
							
							<br>
							<span class="due">
							<?php echo convert_date($row['create_timestamp']);?>
                           </span>
                           
                             <br>
                           
                          <span class="moren">
                         
							<?php echo $row['circular']; ?>
							
					</span>
						<?php	} ?>
							</td>
					    </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>

	</div>
</div>
<script>


$(document).ready(function() {
    var showChar = 300;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "Show more >>";
    var lesstext = "<< Show less";
    

    $('.moren').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
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
