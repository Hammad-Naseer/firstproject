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
    /*
	.page-body .datatable.table tbody td,
	.page-body .datatable.table tbody th {
	    vertical-align: top !important;
	}
	*/
</style>

<?php

$total_records;
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
	
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
$(window).on("load",function() {
    setTimeout(function() {
        $('.mydiv').fadeOut();
    }, 3000);
});

</script>

<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
    <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
</a>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/stafffinal.png"><?php echo get_phrase('depositor'); ?>
        </h3>
    </div>
</div>

<form action="<?php echo base_url();?>depositor/depositor_listing" method="post" >
    <div class="row filterContainer" data-step="2" data-position='top' data-intro="type keyword Then press filter button to get specific records">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <lable class='text-white'><b>Enter Search Keyword</b></lable>
            <input type="text" name="keyword" id="keyword" value="<?php echo $search_get; ?>" class="form-control" placeholder="search keyword">
            <?php /*<select name="designation_id" id="designation_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  >
                <option value="" >Select Designation</option>
                <?php
                echo designation_list_h($parent_id=0);
				// echo designation_list(0,$filter_id);?>
            </select> */ ?>
            <div id="mydiv" class="red"></div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <button class="btn btn-primary" name="search" value="filter" style="position:relative;top:15px;"><?php echo get_phrase('filter'); ?></button>
            <?php if($search_get != "") { ?>
                <a href="<?php echo base_url();?>depositor/depositor_listing" class="modal_cancel_btn" style="padding:5px 8px !important; ">
                    <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
                </a>
            <?php } ?>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;" data-step="3" data-position='top' data-intro="depositer records">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
	<thead>
		<tr>
			<th style="width:34px !important;"><?php echo get_phrase('s_no');?>		
		</th>
		<th>
			<?php echo get_phrase('detail');?>
		</th>
		<th style="width:94px;">
			<?php echo get_phrase('options');?>	
		</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($data)>0) { ?>
        <?php 
            $a=0;
            foreach ($data as $row){
        ?>
        <?php $a++; ?>
		<tr>
                    <td class="td_middle">
                        <?php echo $a; ?>
                    </td>
                    <td>
                        <div class="col-sm-1" style="padding:0px;">
                            <?php $pic = display_link($row['attachment'], 'depositor') ?>
                            <img src="<?php if ($row['attachment'] != '') {
                                echo $pic;
                            }else {
                                echo base_url().'uploads/default.png';
                            } 
                            ?>" class="img-responsive img-rounded" style="width:65px; height:65px;">
                        </div>
                        <div class="col-sm-11" style="padding:0px;">
                            <div class="col-sm-12">
                                <div class="myttl"> <?php echo $row['name']; ?> </div>
                                <span style="font-size:12px;"> <strong> <?php echo get_phrase('id_number'); ?>: </strong> <?php echo $row['id_no']; ?> </span> </br>
                                <span style="font-size:12px;">
                                    <strong>
                                        <?php echo get_phrase('status'); ?>:
                                    </strong>
                                </span>
                                <?php
                                $color = "";
                                if ($row['status']==0)
                                {
                                    $color = "red";
                                }else{
                                    $color = "green";
                                }
                                ?>
                                <span style="color: <?php echo $color;?>"> <?php echo status_active($row['status']); ?> </span>
                                
                            </div>
                            <div class="col-sm-6">
                                   <div><?php echo '<strong>'.get_phrase('email') .':</strong> ' . $row['email']; ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div><?php echo '<strong>'.get_phrase('contact_no') .':</strong> ' . $row['contact_no']; ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div><?php echo '<strong>'.get_phrase('address') .':</strong> ' . $row['address']; ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div><?php echo '<strong>'.get_phrase('description') .':</strong> ' . $row['description']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="td_middle">
                        <?php
                        if (right_granted(array('staff_delete', 'staff_manage'))) {
                            ?>
                            <div class="btn-group" data-step="4" data-position='left' data-intro="depositer edit / delete options">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                        data-toggle="dropdown">
                                    <?php echo get_phrase('actioin'); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <?php
                                    if (right_granted('staff_manage')) {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>depositor/add_edit_depositor/<?php echo str_encode($row['depositor_id']); ?>">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit'); ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    if (right_granted('staff_delete')) {
                                        ?>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#"
                                               onclick="confirm_modal('<?php echo base_url(); ?>depositor/depositor_listing/delete/<?php echo $row['depositor_id']; ?>/<?php echo $row['staff_image']; ?>');">
                                                <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete'); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
		<?php } } ?>
	</tbody>
</table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#filter_submit').on('click', function() {
        var designation_id = $('#designation_id').val();
        if (designation_id != "") {
            window.location.href = "<?php echo base_url();?>depositor/depositor_listing/filter/" + designation_id;
        } 
        else{
            $("#mydiv").html("<?php echo get_phrase('value_required'); ?>");
        }
    });
    
});


<?php if(right_granted('staff_manage')){ ?>
    var datatable_btn_url = '<?php echo base_url();?>depositor/add_edit_depositor';
    var datatable_btn     = "<a href="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new depositer' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_depositor');?></a>";    
<?php } ?>
</script>
