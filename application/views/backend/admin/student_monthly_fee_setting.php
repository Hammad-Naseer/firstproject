<style>
    .btn_space{
        margin-left: 10px !important;
    }
</style>
<?php  if($this->session->flashdata('club_updated')){
    echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
}

if($this->session->flashdata('installment_not_allowed')){
    echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
      '.$this->session->flashdata('installment_not_allowed').'
     </div> 
    </div>';
}

?>

<script>
    $( window ).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>
<?php

$flag="do_insert";
//echo $student_id;
$month_temp = 0;
$year_temp = 0;
$search_month = "";
$display_remove = 'style="display:none;"';
$str = "";

if(isset($_POST['search_month']))
{
    $search_month = $_POST['year'];
    $month_year_temp = explode("_",$search_month);
    $month_temp = $month_year_temp[0];
    $year_temp = $month_year_temp[1];
    $str = "AND month = $month_temp AND year = $year_temp";
    $display_remove = 'style="display:inline;"';
}

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a href="<?php echo base_url(); ?>c_student/student_information/<?php echo $section_id;?>" style="float:right" class="btn btn-primary"> <?php echo get_phrase('back');?></a>
        <h3 class="system_name inline">
            <?php echo get_phrase($page_title);?>
        </h3>
    </div>
</div>

<?php
$ret_val=student_details($student_id);
$section_ary=section_hierarchy($ret_val[0]['section_id']);
if($ret_val[0]['image']!=""){
    $img_dis=display_link($ret_val[0]['image'],'student');
}else{
    $img_dis=base_url().'/uploads/default.png';
}
$academic_year_id = $ret_val[0]['academic_year_id'];
?>
<div class="col-lg-12 col-sm-12">
    <div class="profile-env">
		<header class="row">
				<div class="col-sm-2">
					<a href="#" class="profile-picture">
        				<img src="<?php echo $img_dis ; ?>" class="img-responsive img-circle" />
					</a>
				</div>
				<div class="col-sm-7">
					<ul class="profile-info-sections">
						<li>
							<div class="profile-name">
								<strong>
									<a href="#"><?php echo $ret_val[0]['name']; ?></a>
									<a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Online"></a>
								</strong>
								<!--<span><a href="#">Powered By Indici-Edu</a></span>-->
							</div>
						</li>
						<!--<li>-->
						<!--	<div class="profile-stat">-->
						<!--		<h3> ----- </h3>-->
						<!--		<span><a href="#">Class - Section</a></span>-->
						<!--	</div>-->
						<!--</li>-->
						
						<li>
							<div class="profile-stat">
								<span><a href="#">Name: <b><?php echo $ret_val[0]['name']; ?></b></a></span>
								<br>
								<span><a href="#">Department: <b><?php echo $section_ary['d']; ?></b></a></span>
								<br>
								<span><a href="#">Class: <b><?php echo $section_ary['c']; ?></b></a></span>
								<br>
								<span><a href="#">Section: <b><?php echo $section_ary['s']; ?></b></a></span>
								<br>
								<span><a href="#">Roll No: <b><?php echo $ret_val[0]['roll']; ?></b></a></span>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-sm-3">
					<div class="profile-buttons">
						<!--<a href="#" class="btn btn-default">-->
						<!--	<i class="entypo-mail"></i>-->
						<!--</a>-->
					</div>
				</div>
			</header>
	</div>
</div>
<?php
$str_qur=$this->db->query("select * from ".get_school_db().".student where student_id=$student_id and school_id=".$_SESSION['school_id']."")->result_array();
$section_id = $str_qur[0]['section_id'];
/* <div class="row">
    <?php
    $str_qur=$this->db->query("select * from ".get_school_db().".student where student_id=$student_id and school_id=".$_SESSION['school_id']."")->result_array();
    $section_id = $str_qur[0]['section_id']; ?>


    <form action="<?php echo base_url(); ?>c_student/installment_activate/<?php echo $student_id; ?>" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">

        <input type="hidden" name="is_installment" value="<?php echo $str_qur[0]['is_installment']; ?>"/>
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>"/>
        <div class="col-lg-6">

            <?php echo get_phrase('current_monthly_installment_status');?>
            : <strong>
                <?php

                if($str_qur[0]['is_installment']==1){
                    echo "Active";
                }else{

                    echo "Inactive";
                }

                ?></strong></div>
        <div class="col-lg-6"><button type="submit" class="btn btn-primary"><?php

                if($str_qur[0]['is_installment']==1)
                {
                    echo "Deactivate";
                }else
                {

                    echo "Activate";
                }

                ?></button></div>

    </form>
</div> */ ?>



<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <?php /*<h3 class="system_name inline">
        <!--  <i class="entypo-right-circled carrow">
                    </i>-->
        <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/fee-type-ixon.png"><?php echo get_phrase('fee_types'); ?>
    </h3> */ ?>
        <?php

        if (right_granted('feetype_manage'))
        {
            ?>
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/month_fee_add/<?php echo $student_id."/".$section_id; ?>');" class="btn btn-primary pull-right btn_space">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_custom_fee');?>
            </a>

            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/month_discount_add/<?php echo $student_id."/".$section_id; ?>');" class="btn btn-primary pull-right btn_space">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_custom_disocunt');?>
            </a>

            <a class="btn btn-primary pull-right btn_space" href="<?php echo base_url();?>c_student/student_m_installment/<?php echo str_encode($student_id)."/".$section_id; ?>">
                <?php echo get_phrase('student_fee_setting');?>
            </a>

            <?php
        }
        ?>

    </div>
</div>

<div class="col-md-12 col-sm-12">
    <table class="table table-bordered bg-white">
    <thead>
    <tr>


        <th width="120"><div><?php echo get_phrase('Sno');?></div></th>
        <th width="120"><div><?php echo get_phrase('academic_month');?></div></th>

        <th width="50"><div><?php echo get_phrase('status');?></div></th>
        <th width="50"><div><?php echo get_phrase('options');?></div></th>

    </tr>
    </thead>
    <tbody>
    <?php


    //$query11=$this->db->get_where(get_school_db().'.student_fee_settings' , array('school_id' =>$_SESSION['school_id'],'student_id'=>$student_id))->result_array();


    $academic_year = get_student_academic_year($student_id);
    $start_date = $academic_year['start_date'];
    $end_date = $academic_year['end_date'];
    $date_range = get_month_list($start_date,$end_date);
    $selected_month = "";
    $i = 1;
    foreach ($date_range as $key_month_list=>$value_month_list)
    {
    $month_temp1 = explode("_", $key_month_list);
    $month1 = $month_temp1[0];
    $year1 = $month_temp1[1]

    ?>
    <tr>
        <?php
        // echo $this->crud_model->get_image_url('student',$row['image']);
        ?>
        <td><?php echo $i; ?></td>
        <td><?php

            //  echo $key_month_list;

            $academic_month_temp = explode("_", $key_month_list);
            $month = $academic_month_temp[0];
            $year = $academic_month_temp[1];
            echo date("F Y", mktime(0, 0, 0, $month, 1, $year));


            ?></td>

        <td>
            <?php $status_temp = month_fee_setting_status($student_id, $month1, $year1);
            if ($status_temp == 1)
            {
                echo "Generated";
            } else if ($status_temp == 2)
            {
                echo "Approved";
            } else if ($status_temp == 3)
            {
                echo "issued";
            } else
            {
                echo "Not generated yet";
            }
            ?>
        </td>
        <td width="100" style="text-align: center;">
            <?php
            $monthyear = date("Y-m");
            $key_month_list_temp = $year . "-" . $month;
            if ($key_month_list_temp >= $monthyear)
            {
            ?>

            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                        data-toggle="dropdown"> <?php echo get_phrase('action'); ?><span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-default pull-right" role="menu">


                    <?php


                    if (!is_created($student_id, $month1, $year1)) { ?>


                        <li>

                                       <a href = "javascript:;"
                                          onclick = "showAjaxModal('<?php echo base_url(); ?>modal/popup/month_fee_setting_add/<?php echo $student_id ."/".$section_id."/".$key_month_list ?>');" >
                                           <i class="entypo-trash" ></i >
                                           <?php echo get_phrase('Add'); ?>
                        </a>
                        </li>
                        <?php
                               } else
                                   { ?>

                        <li>
                            <a href="javascript:;"
                               onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/month_fee_setting_view/<?php echo $student_id . "/" . $section_id . "/" . $key_month_list ?>');">
                                <i class="entypo-trash"></i>
                                <?php echo get_phrase('view'); ?>
                            </a>
                        </li>
                    <li class="divider"></li>
                        <li>
                            <a href="javascript:;"
                               onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/month_fee_setting_edit/<?php echo $student_id . "/" . $section_id . "/" . $key_month_list ?>');">
                                <i class="entypo-trash"></i>
                                <?php echo get_phrase('Edit'); ?>
                            </a>
                        </li>
                    <li class="divider"></li>
                        <li>
                            <a href="#"
                               onclick="confirm_modal('<?php echo base_url(); ?>c_student/student_fee_settings_delete/<?php echo $student_id . "/" . $section_id . "/" . $key_month_list ?>')">
                                <i class="entypo-trash"></i>
                                <?php echo get_phrase('delete'); ?>
                            </a>
                        </li>
                    <li>
                        <?php

                        ?>
                    </li>
                </ul>
                <?php }
                ?>
                    </div>
                <?php
            }
            else
            {
                if (is_created($student_id, $month1, $year1))
                {
                    ?>
                    <p style="text-align: center">
                    <a style="text-align: center" href="javascript:;"
                       onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/month_fee_setting_view/<?php echo $student_id . "/" . $section_id . "/" . $key_month_list ?>');">
                        <i class="entypo-trash"></i>
                        <?php echo get_phrase('view'); ?>
                    </a>
                    </p>
                    <?php
                }
                else
                {
                    ?>
                    <p style="text-align: center"> - </p>
                    <?php
                }
            }
            ?>
            </td>
        </tr>
        <?php
        $i++;
    } ?>
    </tbody>
</table>
</div>
<script>
    $(document).ready(function()
    {
        if ($("#mymonth").val()=="")
        {
        }

        tableContainer = $("#transfer_ajax_tbl");
        tableContainer.dataTable({
            "sPaginationType": "bootstrap",
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "bStateSave": true,
            // Responsive Settings
            bAutoWidth     : false,
            fnPreDrawCallback: function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper) {
                    responsiveHelper = new ResponsiveDatatablesHelper(tableContainer, breakpointDefinition);
                }
            },
            fnRowCallback  : function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                responsiveHelper.createExpandIcon(nRow);
            },
            fnDrawCallback : function (oSettings) {
                responsiveHelper.respond();
            }
        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });
</script>