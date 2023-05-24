<style>
	.title {
	    border: 1px solid #eae7e7;
	    min-height: 34px;
	    padding-top: 10px;
	    background-color: rgba(242, 242, 242, 0.35);
	    color: rgb(140, 140, 140);
	    height: auto;
	}

	.adv {
	    width: 50px;
	}

	.tt {
	    background-color: rgba(242, 242, 242, 0.35);
	    border: 1px solid #eae7e7;
	    min-height: 26px;
	    padding-top: 4px;
	}

	.panel-default > .panel-heading + .panel-collapse .panel-body {
	    border-top-color: #21a9e1;
	    border-top: 2px solid #00a651;
	}

	.panel-body {
	    padding: 9px 22px;
	    /* background-color: #21a9e1; */
	}

	.myfsize {
	    font-size: 11px !important;
	}

	.panel-group .panel > .panel-heading > .panel-title > a {
	    display: inline;
	}

	.fa-file-o {
	    padding-right: 0px !important;
	}

	.panel {
	    margin-bottom: 20px;
	    background-color: #fff;
	    border: 1px solid rgba(0, 0, 0, 0.08);
	    border-radius: 4px;
	    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
	    box-shadow: 0 1px 1px rgba(43, 43, 43, 0.15);
	}

	.panel-title {
	    width: 100%;
	}

	.panel-group.joined > .panel > .panel-heading + .panel-collapse {
	    background-color: #fff;
	}

	.difl {
	    display: inline;
	    float: left;
	}

	.bt {
	    margin-bottom: -28px;
	    padding-top: 15px;
	    padding-right: 31px;
	}

	.panel-heading > .panel-title {
	    float: left !important;
	    padding: 10px 15px !important;
	    color: #FFF !important;
	    background-color: #FFF;
	}

	.fa-mobile {
	    font-size: 24px;
	}

	.emer {
	    color: red;
	}

	.emer_green {
	    color: green;
	}

	.emer_blue {
	    color: blue;
	}

	.page-body .datatable.table tbody td,
	.page-body .datatable.table tbody th {
	    vertical-align: top !important;
	}
</style>

<?php

//echo "echo".$this->uri->segment(3);


if($this->session->flashdata('club_updated')){
	echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
	'.$this->session->flashdata('club_updated').'
	</div> 
	</div>';
}

if($this->session->flashdata('error_msg')){
	echo '<div align="center">
	<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
	'.$this->session->flashdata('error_msg').'
	</div> 
	</div>';
}
?>
<script>
	
$(window).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
    });
	
</script>
<script>
$(window).load(function() {
    setTimeout(function() {
        $('.mydiv').fadeOut();
    }, 3000);
});
</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php echo get_phrase('deposit'); ?> 
        </h3>
        <?php 
        if (right_granted('staff_manage'))
        { ?>
            <a href="<?php echo base_url(); ?>deposit/add_edit_deposit" class="btn btn-primary pull-right">
               <i class="entypo-plus-circled"></i>
               <?php echo get_phrase('add_deposit');?>
            </a>
        <?php } ?>
    </div>
</div>
<div class="row thisrow">
    <form action="<?php echo base_url()?>deposit/deposit_listing" method="post">
        <div class="col-lg-4 col-md-4 col-sm-4">
            
            <input type="text" name="keyword" id="keyword" value="<?php echo $search_get; ?>" class="form-control" placeholder="Search keyword" >
            <div id="mydiv" class="red"></div>
        </div>
        <div class="col-sm-4">
            <?php depositors('depositor_id' ,$depositor_id_post); ?>
        </div>
        <div class="clearfix" style="margin: 8px 0;"></div>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <input id="start_date" name="start_date" class="form-control datepicker" value="<?php echo date_dash($start_date);?>" placeholder="Select Start Date" <?php /*required*/ ?> data-format="dd/mm/yyyy" />
            <span style="color: red;" id="sd"></span>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4">
            <input id="end_date" name="end_date" class="form-control datepicker"  value="<?php echo date_dash($end_date);?>" placeholder="Select End Date" <?php /*required*/ ?> data-format="dd/mm/yyyy" />
            <span style="color: red;" id="ed"></span>
        </div>




        <div class="col-lg-6 col-md-6 col-sm-6">


            <button class="btn btn-primary" name="search" value="filter"><?php echo get_phrase('filter'); ?></button>
            
            <?php
    		if($search_get != "" || $depositor_id != "" || $start_date != "" || $end_date != "")
    		{?>
                <a href="<?php echo base_url();?>deposit/deposit_listing" class="btn btn-danger" style="padding:5px 8px !important; ">
                    <i class="fa fa-remove"></i>
                    <?php echo get_phrase('remove_filter'); ?>
                   
                </a>
                <?php
    		}
    		?>
        </div>




           <!--<a class="btn btn-primary" href="<?php echo base_url(); ?>user/create_staff_card/all/val">
                    <i class="entypo-user"></i>
            <?php //echo get_phrase('Create_All_Card');?>
                                                    </a>  -->
    

    </form>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" style="    padding: 0px;">

        <table class="table table-bordered datatable" id="staff">
            <thead>
                <tr>
                    <td>
                        <?php echo get_phrase('#');?>
                    </td>
                    <td>
                        <?php echo get_phrase('date');?>
                    </td>
                    <td>
                        <?php echo get_phrase('title');?>
                    </td>
                    <td>
                        <?php echo get_phrase('amount');?>
                    </td>
                    <td>
                        <?php echo get_phrase('depositor');?>
                    </td>
                    <td>
                        <?php echo get_phrase('option');?>
                    </td>
                </tr>
            </thead>


            <tbody>
            <?php if(count($data)>0)
            { ?>
            <?php
            $a = 0;
            foreach ($data as $row) : ?>
            <?php $a++; ?>
            <tr>
                <td>
                    <?php echo $a; ?>
                </td>
                <td>
                    <?php echo convert_date($row['deposit_date']); ?>

                </td>
                <td width="350">
                  <strong> <?php echo $row['title']; ?></strong>
                    <div style="clear:both;">
                        <?php
                        if($row['description'] != "")
                        {
                            ?>
                            <strong>
                            
                            <?php echo get_phrase('description'); ?>
                            
                            :-</strong>
                            <?php
                            echo $row['description'];
                            ?>
                            <?php
                        }
                        ?>
                    </div>
                </td>
                <td>
                    <?php echo $row['amount']; ?>
                </td>
                <td>
                    <?php echo $row['name']; ?>
                </td>
                <td width="25">
                    <?php
                    if ($row['status'] == 0) {

                        if (right_granted(array('staff_delete', 'staff_manage'))) {
                            ?>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                        data-toggle="dropdown">
                                    <?php echo get_phrase('action'); ?><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <?php
                                    if (right_granted('staff_manage'))
                                    {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>deposit/add_edit_deposit/<?php echo $row['deposit_id']; ?>">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit'); ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    if (right_granted('staff_delete'))
                                    {
                                        ?>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#"
                                               onclick="confirm_modal('<?php echo base_url(); ?>deposit/deposit_listing/delete/<?php echo $row['deposit_id']; ?>/<?php echo $row['staff_image']; ?>');">
                                                <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete'); ?>
                                            </a>
                                        </li>

                                    <?php
                                    } ?>


                                </ul>
                            </div>
                        <?php }
                    }
                    else
                    {
                        ?>
                        <strong><span class='green space'>(<?php echo get_phrase('submitted'); ?>)</span></strong>
                        <?php
                    }
                    ?>
                    </td>

                </tr>
            <?php endforeach; ?>


                <tr>
                    <td colspan="6"><?php
                        echo $this->pagination->create_links();
                        ?></td>
                </tr>

                <tr>
                    <td colspan="6"><?php
                        echo "<strong>".get_phrase('total_records').":</strong>$total_records";


                        ?></td>
                </tr>
                <?php }

                else {
                    ?>
                   <tr>

                       <td colspan="3">

                          <?php echo get_phrase('no_records_found'); ?>..
                       </td>
                   </tr>


            <?php }?>
            </tbody>

        </table>


    </div>
</div>

<script>
$(document).ready(function() {
    $('#filter_submit').on('click', function() {
        var keyword = $('#keyword').val();
       alert(keyword);
        if (keyword != "") {
			//var txt=$('#designation_id option:selected').text();
			//alert(txt);
			
            window.location.href = "<?php echo base_url();?>deposit/deposit_listing/filter/" + designation_id;
        } else {

            $("#mydiv").html("<?php echo get_phrase('value_required');?>");
        }
    });
});
</script>

<!-- Date check script -->
<script>
$('#is_closed').change(function(){
this.value = this.checked ? 1 : 0;
    });

$("#start_date").change(function () {
    document.getElementById("sd").innerHTML = "";
    var startDate = s_d($("#start_date").val());
    var endDate = s_d($("#end_date").val());
   
    if ((Date.parse(endDate) < Date.parse(startDate)))
        {
        document.getElementById("sd").innerHTML = "<?php echo get_phrase('start_date_should_be_less_then_end_date');?>";
        document.getElementById("start_date").value = "";
        }
     else if ((Date.parse(startDate) < Date.parse("<?php echo $start_date_check; ?>"))) 
        {
        document.getElementById("sd").innerHTML = "<?php echo get_phrase('please_select_start_date_within_academic_session');?>";
        document.getElementById("start_date").value = "";      
        }
    }
    );
$("#end_date").change(function () {
    document.getElementById("ed").innerHTML = "";
    var startDate = s_d($("#start_date").val());
    var endDate = s_d($("#end_date").val());
    if ((Date.parse(startDate) > Date.parse(endDate))) {
        document.getElementById("ed").innerHTML = "<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>";
        document.getElementById("end_date").value = "";      
    }
   else if ((Date.parse(endDate) > Date.parse("<?php echo $end_date_check; ?>"))) {
        
    document.getElementById("ed").innerHTML = "<?php echo get_phrase('please_select_end_date_within_academic_session');?>";
        document.getElementById("end_date").value = "";    
    }
});
function s_d(date){
var date_ary=date.split("/");
return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
}
</script>
