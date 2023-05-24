<?php
$school_id=$_SESSION['school_id'];
$title = get_phrase('student_discount_setting_add');
$student_id = $param2;
$section_id = $param3;
$query=$this->db->get_where(get_school_db().'.student_fee_settings' , array('school_id' =>$_SESSION['school_id'],'fee_settings_id'=>$s_m_i_id))->result_array();
$query_c_c_f_discount_str = "SELECT * FROM " . get_school_db() . ".student_monthly_fee_settings";
$query_c_c_f_discount = $this->db->query($query_c_c_f_discount_str)->result_array();
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title black2" >
                    <!--<i class="entypo-plus-circled"></i>-->
                    <?php echo $title ;?>
                </div>
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url(); ?>c_student/month_discount_add/" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">
                    <div class="form-group type_discount">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('Discount_listing');?>  <span class="star">*</span>   </label>
                        <div class="col-sm-8">
                            <select name="fee_type_id_discount" id="fee_type_id_discount" class="form-control" data-validate="required" data-message-required="Value Required">
                                <?php echo discount_student_installment(""); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('title'); ?></label>
                        <div class="col-sm-8">
                            <input maxlength="200" type="text" class="form-control" name="title" value="" required>
                        </div>
                    </div>

                    <div class="form-group month_listing" style="display: none;">
                    <fieldset class="custom_legend text_month">
                        <legend class="custom_legend"><?php echo get_phrase('academic_months'); ?>:</legend>
                        <div id="icon" class="loader_small" style=" display:none;text-align: center; position: relative;">&nbsp;</div>
                            <div class="col-sm-12">
                                <?php
                                $academic_year = get_student_academic_year($student_id);
                                //print_r($academic_year);
                                $start_date = date("Y-m-d"); // 2017-04-01
                                $month_list = get_month_list($start_date,$academic_year['end_date']);
                                foreach ($month_list as $key_month_list=>$value_month_list)
                                {
                                ?>
                                    <label for="sort" class="col-sm-4 control-label" style="margin: 5px 0;"> <?php echo $value_month_list; ?></label>
                                    <div class="col-sm-8">
                                        <select class="form-control m_select"  name="amount[]" id="sort">
                                            <?php  echo get_percentage_range($query_edit[0]['amount']); ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="month_date[]" value="<?php echo $key_month_list; ?>">
                                    <?php
                                }
                                ?>
                            </div>
                    
                    </fieldset>
            </div>
                    <?php /*  <label><?php echo $value_month_list; ?></label>
                    <select name="amount[]" class="form-control">

                    </select> */ ?>



                   <?php /* <div class="form-group">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('status');?>  <span class="star">*</span>   </label>
                        <div class="col-sm-8">
                            <select name="status" class="form-control" data-validate="required" data-message-required="Value Required">
                                <option value=""><?php echo get_phrase('select'); ?></option>
                                <option value="1"><?php echo get_phrase('active'); ?></option>
                                <option value="2"><?php echo get_phrase('inactive'); ?></option>
                            </select>

                        </div>
                    </div> */ ?>


                    <input maxlength="50" type="hidden" class="form-control"   id="aa"  name="section_id" value="<?php echo $section_id; ?>">
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
        $(".type_field").hide();
        $(document.body).on('click', "#percentage",function(){
            $(".type_field").hide();
            $(".type_select").show();
            $(".discount_statut_amount_type").val('0');
            $("#percentage_lable").html("Percentage");
        });
        $(document.body).on('click', "#complete",function(){
            $(".type_field").show();
            $(".type_select").hide();
            $("#percentage_lable").html("Value");
            $(".discount_statut_amount_type").val('1');
        });
        $(document.body).on('change', '#sameabove' ,function() {
            if(this.checked) {
                var id = $(this).val();
                var ids = id.split("_");
                var v1 = parseInt(ids[0]);
                var v2 = ids[1];
                var first_percentage = $('#'+id).val();
                var first_value = $('.'+id+"1").val();
                var first_status  = $('.'+id).val();
                var count = $('#month_count').val();
                for(var i = 1; i<count; i++){
                    var year = v2;
                    var sum = v1+i;
                    if(sum > 12){
                        sum = sum - 12;
                        year = parseInt(year)+1;
                    }
                    if(sum<10){
                        var new_id = "#0"+(sum)+"_"+year;
                        var new_class = ".0"+(sum)+"_"+year;
                        var new_class1 = ".0"+(sum)+"_"+year+1;
                    }
                    else{
                        var new_id = "#"+(sum)+"_"+year;
                        var new_class = "."+(sum)+"_"+year;
                        var new_class1 = "."+(sum)+"_"+year+"1";
                    }
                   $(new_id).val(first_percentage);
                   $(new_class).val(first_status);
                   $(new_class1).val(first_value);
                   
                   $("")
                }
            }
            else{
                var id = $(this).val();
                var ids = id.split("_");
                var v1 = parseInt(ids[0]);
                var v2 = ids[1];
                var count = $('#month_count').val();
                for(var i = 1; i<count; i++){
                    var year = v2;
                    var sum = v1+i;
                    if(sum > 12){
                        sum = sum - 12;
                        year = parseInt(year)+1;
                    }
                    if(sum<10){
                        var new_id = "#0"+(sum)+"_"+year;
                        var new_class = ".0"+(sum)+"_"+year;
                        var new_class1 = ".0"+(sum)+"_"+year+"1";
                    }
                    else{
                        var new_id = "#"+(sum)+"_"+year;
                        var new_class = "."+(sum)+"_"+year;
                        var new_class1 = "."+(sum)+"_"+year+"1";
                    }
                   $(new_id).val("");
                   $(new_class).val("");
                   $(new_class1).val("");
                }
            }
        });
        $("#fee_type_id_discount").change(function () {
            var discount_id = this.value;
            if(discount_id == "")
            {
                discount_id = 0;
            }
            var student_id = <?php echo $student_id; ?>;
            $.ajax({
                type: 'POST',
                data: {
                    discount_id: discount_id,
                    student_id: student_id,
                    beforeSend: function() {
                        $('#icon').show();
                        $('.month_listing').hide();
                    }
                },
                url: "<?php echo base_url();?>c_student/get_selected_amount_discount",
                dataType: "html",
                success: function (response)
                {
                    // alert(response);
                    $(".month_listing").html(response);

                },
                
                complete: function()
                {
                    $('#icon').hide(500);
                    $('.month_listing').show(500);

                }
            });



        });

        /*$('#fee_type').change(function(){
         var fee_type = $('#fee_type').val();

         if(fee_type == 1)
         {
             //alert(fee_type);
             $('.type_fee').show();
             $('.type_discount').hide();
         }
         else if(fee_type == 2)
         {
             //alert(fee_type);
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


<script>


</script>
<style type="text/css">

    .m_select
    {
        margin: 5px 0;
    }
    .custom_legend .text_month{
        font-size: 12px;

    }

</style>
