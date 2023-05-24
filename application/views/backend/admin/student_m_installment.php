<?php  
    if($this->session->flashdata('club_updated')){
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
    $(window).on("load",function() {
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
        <!-- 
        <a href="<?php echo base_url(); ?>c_student/student_information/<?php echo $section_id;?>" class="btn btn-primary" style="float:right"> 
        <?php echo get_phrase('back');?></a>
        -->
        <h3 class="system_name inline">
            <?php echo get_phrase('monthly_fee_settings');?>
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
    $section_id = $str_qur[0]['section_id']; /* <div class="row">

    $str_qur=$this->db->query("select * from ".get_school_db().".student where student_id=$student_id and school_id=".$_SESSION['school_id']."")->result_array();
    $section_id = $str_qur[0]['section_id']; ?>

    <form action="<?php echo base_url(); ?>c_student/installment_activate/<?php echo $student_id; ?>" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">

        <input type="hidden" name="is_installment" value="<?php echo $str_qur[0]['is_installment']; ?>"/>
        <input type="hidden" name="section" value="<?php echo $section_id; ?>"/>
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
<div class="col-lg-12 col-md-12 col-sm-12 myt topbar btn-setng">
    <?php 
        /*<h3 class="system_name inline">
        <!--  <i class="entypo-right-circled carrow"></i>-->
        <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/fee-type-ixon.png"><?php echo get_phrase('fee_types'); ?>
        </h3> */ ?>
    <?php
    if (right_granted('feetype_manage')){
    ?>
        <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/month_fee_add/<?php echo $student_id."/".$section_id; ?>');" class="btn btn-primary">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('add_custom_fee');?>
        </a>

        <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/month_discount_add/<?php echo $student_id."/".$section_id;; ?>');" class="btn btn-primary">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('add_custom_disocunt');?>
        </a>

        <a class="btn btn-primary" href="<?php echo base_url();?>c_student/student_monthly_fee_setting/<?php echo $student_id."/".$section_id; ?>">
            <?php echo get_phrase('student_monthly_fee_setting');?>
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
        <th width="120"><div><?php echo get_phrase('title');?></div></th>
        <th width="120"><div><?php echo get_phrase('chalan_status');?></div></th>

        <th width="200"><?php echo get_phrase('month');?>-<?php echo get_phrase('year');?></th>


        <th width="120"><div><?php echo get_phrase('type');?></div></th>
        <!--<th width="120"><div><?php //echo get_phrase('record_type');?></div></th>-->
        <th width="120"><div><?php echo get_phrase('type_title');?></div></th>
        <th width="50"><div><?php echo get_phrase('value');?></div></th>

        <th width="50"><div><?php echo get_phrase('status');?></div></th>

        <th width="50"><div><?php echo get_phrase('options');?></div></th>
        <!--<th><div><?php echo get_phrase('status');?></div></th>-->
    </tr>
    </thead>
    <tbody>
    <?php


    //$query11=$this->db->get_where(get_school_db().'.student_fee_settings' , array('school_id' =>$_SESSION['school_id'],'student_id'=>$student_id))->result_array();


        $query11_str = "select * from ".get_school_db().".student_fee_settings 
                            where student_id=$student_id and school_id=".$_SESSION['school_id']."
                             $str
                             AND is_bulk = 0
                             ORDER BY month,year asc";
    $query11 =$this->db->query($query11_str)->result_array();
    foreach($query11 as $row){

        //echo $row['fee_settings_id'];
        $month = $row['month'];
        $year = $row['year'];
        $student_id = $row['student_id'];
        $fee_type_id = $row['fee_type_id'];
        $chalan_generated = 0;
        $chalan_generated_msg = get_phrase('not_generated');
        $chalan_generated = assigned_month_year_add($student_id,$month , $year , $fee_type_id);
        if($chalan_generated == 1)
        {
            $chalan_generated_msg = "<span class='red'>".get_phrase('generated')."</span>";
        }
        else if($chalan_generated == 2)
        {
            $chalan_generated_msg = "<span class='red'>".get_phrase('Approved')."</span>";
        }

        ?>
        <tr>
            <?php
            // echo $this->crud_model->get_image_url('student',$row['image']); ?>
            <td><?php echo $row['title'];?></td>
            <td><?php echo $chalan_generated_msg; ?></td>
            <td><?php echo   month_of_year($row['month'])."-".$row['year']; ?></td>

            <td>
                <?php

                if($row['fee_type'] == 1)
                {
                    echo get_phrase('Fee');
                }
                else
                {
                    echo get_phrase('Discount');
                }
                ?>

            </td>


            <!--<td width="300">-->
                <?php

                // if(($row['is_bulk']) == 0 && ($row['settings_type'] == 1 || $row['settings_type'] == 3))
                // {
                //     echo get_phrase('individual');
                // }
                // else if(($row['is_bulk']) == 0 && ($row['settings_type'] == 2))
                // {
                //     echo get_phrase('combined');
                // }

                ?>

            <!--</td>-->
            <td width="300"><?php

                if($row['fee_type'] == 1)
                {
                    echo get_title_fee($row['fee_type_id']);
                }
                else
                {
                    $title_arr =  get_title_discount($row['fee_type_id']);

                    echo nl2br( $title_arr['discount_title']."\n"."(".$title_arr['fee_title'].")");
                }
                ?></td>
            <td><?php echo $row['amount'];?></td>
            <td><?php

                if($row['status'] == 1)
                {
                    echo get_phrase('active');
                }
                else
                {
                    echo get_phrase('inactive');
                }
                // echo "<br>";
                //echo "test".$row['fee_type'] . $row['is_bulk'];

                ?></td>


            <td>

                <?php
                
                $academic_year = get_student_academic_year($student_id);
                $start_date    = explode("-",date("Y-m-d"));
                $start_date    = $start_date[0]."-".$start_date[1];
                $end_date      = explode("-",$academic_year['end_date']);
                $end_date      = $end_date[0]."-".$end_date[1];
                $current_date  = $row['year']."-".$row['month'];

                if(($current_date>=$start_date))
                {
                ?>

                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"> <?php echo get_phrase('action');?><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                            
                            <li>
                                <?php

                                if(($row['fee_type'] == 1))
                                {?>
                                <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/month_fee_add_edit/<?php echo $row['fee_settings_id']."/".$student_id; ?>');" >
                                    <i class="entypo-trash"></i>
                                    <?php echo get_phrase('edit');?>
                                    <?php
                                } else if(($row['fee_type'] == 2))
                                {
                                        ?>
                                        <a href="javascript:;"
                                           onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/month_discount_edit/<?php echo $row['fee_settings_id'] . "/" . $student_id; ?>');">
                                            <i class="entypo-trash"></i>
                                            <?php echo get_phrase('edit'); ?>
                                        </a>
                                        <?php
                                } ?>
                            </li>
                            
                            <li class="divider"></li>


                            <?php
                            if($chalan_generated == 1) {
                                ?>
                                <li>
                                    <a href="<?php echo base_url(); ?>c_student/delete_installment/<?php echo $row['fee_settings_id']; ?>/<?php echo $student_id; ?>/<?php echo $month . "/" . $year . "/" . $fee_type_id; ?>" class="delete_test">

                                        <i class="entypo-trash"></i>
                                        <?php echo get_phrase('delete'); ?>

                                    </a>
                                </li>
                                <?php
                            }
                            else
                            {
                                ?>

                            <li>
                                    <a href="#"
                                       onclick="confirm_modal('<?php echo base_url(); ?>c_student/delete_installment/<?php echo $row['fee_settings_id']; ?>/<?php echo $student_id; ?>/<?php echo $month . "/" . $year . "/" . $fee_type_id; ?>')">
                                        <i class="entypo-trash"></i>
                                        <?php echo get_phrase('delete'); ?>

                            </a>
                            </li>
                           <?php }
                            ?>
                        </ul>
                    </div>
                <?php }
                else
                {?>
                   <p style="text-align: center"> - </p>
                <?php }?>
            </td>




        </tr>
    <?php }?>
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

<script>
    $(document).ready(function()
    {
        $('.delete_test').click(function (){
            var answer = confirm("<?php echo get_phrase('are_you_sure_to_delete_this_discount_becouse_it_is_assigned') ?>");
            if (answer) {
                return true;
            }else{
                return false;
            }
        });
    });
    function valadation(x,y){
        //alert(x);
        //alert(y);
        var count=$('#'+y).val().length;

        if(count>x || count==0){
            $('#error_text').remove();
            $('#'+y).css('border','1px solid red');
            $('#'+y).before('<p id="error_text" style="color:red;"><?php echo get_phrase('charactor_must_be_less_then'); ?>' + x+'</p>');
            $('#main_btn').prop('disabled', true);


        }

        else{
            $('#'+y).css('border','1px solid green');
            $('#error_text').remove();


            $('#main_btn').prop('disabled', false);
        }

        var flag=$("#error_text").html();


        if(flag==undefined){

            $('#main_btn').prop('disabled',false);

        }else{
            $('#main_btn').prop('disabled', true);
        }
    }
</script>


