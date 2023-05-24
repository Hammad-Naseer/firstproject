<?php
$school_id=$_SESSION['school_id'];
$title = get_phrase('student_discount_setting_edit');
$student_id = $param3;
$fee_settings_id = $param2;
$query=$this->db->get_where(get_school_db().'.student_fee_settings' , array('school_id' =>$school_id,'fee_settings_id'=>$fee_settings_id))->result_array();
$fee_type_id = $query[0]['fee_type'];
$month = $query[0]['month'];
$year = $query[0]['year'];
$disable_input = "";
$fee_type_id = $query[0]['fee_type_id'];
$assigned_months =  assigned_month_year($student_id,$month , $year,$fee_type_id);
if($assigned_months == 1){
    $disable_input = "disabled";
}
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" >
            		<!--<i class="entypo-plus-circled"></i>-->
					<?php echo $title ;
                    if($assigned_months == 1){

                        $msg = get_phrase("monthly_form_have_generated");
                        echo " - <span class='red'>(".$msg.")".'</span>';
                    }
                    ?>
            	</div>
            </div>
			<div class="panel-body">
                <form action="<?php echo base_url(); ?>c_student/month_discount_edit/" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">
                    <div class="form-group type_discount">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('Discount_listing');?>  <span class="star">*</span>   </label>
                        <div class="col-sm-8">
                            <select name="fee_type_id_discount" id="fee_type_id_discount_<?php echo $fee_type_id; ?>" class="form-control" data-validate="required" data-message-required="Value Required" <?php //echo $disable_input; ?>>
                                <?php echo discount_student_installment($query[0]['fee_type_id']); ?>
                          </select>
                            <?php
                                if($assigned_months == 1){    ?>
                                    <script>
                                        $('#fee_type_id_discount_<?php echo $fee_type_id; ?>').bind('mousedown', function (event){
                                            event.preventDefault();
                                            event.stopImmediatePropagation();
                                            // alert('hello');
                                        });
                            </script>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('title'); ?> </label>
                        <div class="col-sm-8">
                            <input maxlength="200" type="text" class="form-control" name="title" value="<?php echo $query[0]['title']; ?>">
                        </div>
                    </div>
                    <div class="form-group type_discount text-center">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                Percentage
                                <input type="radio" name="choose_discount_type" class="choose_discount_type" value="0" <?php if($query[0]['discount_amount_type'] == '0'){echo "checked";} ?>>        
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                Value
                                <input type="radio" name="choose_discount_type" class="choose_discount_type" value="1" <?php if($query[0]['discount_amount_type'] == '1'){echo "checked";} ?>>
                            </div>
                        </div>
                    </div>
                    <?php if($query[0]['discount_amount_type'] == '0'){ ?>
                        <div class="form-group hide_percent_type_discount" style="display:block;">
                            <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('percentage');?>  <span class="star">*</span>   </label>
                            <div class="col-sm-8">
                                <select name="" class="form-control attr1" data-validate="required" data-message-required="Value Required">
                                    <?php  echo get_percentage_range($query[0]['amount']); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group hide_value_type_discount" style="display:none;">
                            <label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('value');?>  <span class="star">*</span>   </label>
                            <div class="col-sm-8">
                                <input type"number" name="" class="form-control attr2" data-validate="required" data-message-required="Value Required">
                            </div>
                        </div>
                        <?php }else if($query[0]['discount_amount_type'] == '1'){ ?>
                            <div class="form-group hide_value_type_discount" style="display:block;">
                                <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('value');?>  <span class="star">*</span>   </label>
                                <div class="col-sm-8">
                                    <input type"number" name="" class="form-control attr3" data-validate="required" data-message-required="Value Required" value="<?=$query[0]['amount']?>">
                                </div>
                            </div>
                            
                            <div class="form-group hide_percent_type_discount" style="display:none;">
                                <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('percentage');?>  <span class="star">*</span>   </label>
                                <div class="col-sm-8">
                                    <select name="" class="form-control attr4" data-validate="required" data-message-required="Value Required">
                                        <?php echo get_percentage_range(); ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('academic_months'); ?><span class="star">*</span>   </label>
                            <div class="col-sm-8">
                        <?php
                            $academic_year = get_student_academic_year($student_id);
                            $start_date = date("Y-m-d"); // 2017-04-01
                            $month_list = get_month_list($start_date,$academic_year['end_date']);
                            $month_year =  $query[0]['month']."_".$query[0]['year'];
                        ?>
                       <select id="month_year_<?php echo $fee_type_id; ?>" name="month_year" class="form-control" <?php //echo $disable_input; ?>>
                                    <?php
                                        foreach ($month_list as $key_month_list=>$value_month_list)
                                        {
                                            $selected = "";
                                            if($key_month_list == $month_year)
                                            {
                                                $selected = "selected";
                                            }
                                    ?>
                            <option value="<?php echo $key_month_list; ?>" <?php echo $selected; ?>>
                                <?php echo $value_month_list; ?>
                            </option>
                            <?php } ?>
                       </select>
                        <?php if($assigned_months == 1){    ?>
                            <script>
                                $('#month_year_<?php echo $fee_type_id; ?>').bind('mousedown', function (event){
                                    event.preventDefault();
                                    event.stopImmediatePropagation();
                                    //alert('hello');
                                });
                            </script>
                        <?php } ?>
                     </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('status');?>  <span class="star">*</span>   </label>
                    <div class="col-sm-8">
                        <select name="status" class="form-control" data-validate="required" data-message-required="Value Required">
                            <option value=""><?php echo get_phrase('select'); ?></option>
                            <option value="1" <?php if($query[0]['status'] == 1){ echo "selected";} ?>><?php echo get_phrase('active'); ?></option>
                            <option value="2" <?php if($query[0]['status'] == 2){ echo "selected";} ?>><?php echo get_phrase('inactive'); ?></option>
                        </select>

                    </div>
                </div>


                    <input maxlength="50" type="hidden" class="form-control"  id="aa"  name="fee_settings_id" value="<?php echo  $query[0]['fee_settings_id']; ?>">
                    <input maxlength="50" type="hidden" class="form-control"  id="aaaa"  name="student_id" value="<?php echo $student_id; ?>">

					<div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"></label>
						<div class="col-sm-8">
				<button type="submit" id="btnSubmit" class="btn btn-default"><?php echo get_phrase('save'); ?></button>
						</div>
					</div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        
        if ($(".choose_discount_type").is(":checked")) {
            var chk_val = $(".choose_discount_type:checked").val();
            if(chk_val == "0")
            {
                // alert("0");
                $('.attr1').attr("name","amount");
            }
            else if(chk_val == "1")
            {
                // alert("1");
                $('.attr3').attr("name","amount");
            }
        }
        
        $(".choose_discount_type").on("change",function(){
            var v = $(this).val();
            if(v == "0")
            {
                $('.attr3').attr("name","");
                $('.attr2').attr("name","");
                $('.attr4').attr("name","amount");
                $('.attr1').attr("name","amount");
                $(".hide_value_type_discount").css("display","none");
                $(".hide_percent_type_discount").css("display","block");
            }else if(v == "1")
            {
                $('.attr4').attr("name","");
                $('.attr1').attr("name","");
                $('.attr3').attr("name","amount");
                $('.attr2').attr("name","amount");
                $(".hide_value_type_discount").css("display","block");
                $(".hide_percent_type_discount").css("display","none");
            }
        });

        var fee_type = <?php echo $fee_type_id; ?>;

       /* if(fee_type == 1)
        {
            //alert(fee_type);
            $('.type_fee').show();
            $('.type_discount').hide();
        }
        else if(fee_type == 2)
        {

            $('.type_discount').show();
            $('.type_fee').hide();
        }
        else
        {
            $('.type_discount').hide();
            $('.type_fee').hide();
        }*/


        $("#month_datepiker").datepicker({
            format: "mm/yyyy",
            startView: "months",
            minViewMode: "months"
        });


        /*$('#fee_type').change(function(){
         var fee_type = $('#fee_type').val();

         if(fee_type == 1)
         {

             $('.type_fee').show();
             $('.type_discount').hide();
         }
         else if(fee_type == 2)
         {

            $('.type_discount').show();
             $('.type_fee').hide();
         }
         else
         {
             $('.type_discount').hide();
             $('.type_fee').hide();
         }

        });*/

    });


</script>
