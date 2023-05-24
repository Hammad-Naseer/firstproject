<?php
$school_id=$_SESSION['school_id'];
$title = get_phrase('student_fee_setting_edit');
$student_id = $param3;
$fee_settings_id = $param2;
$query=$this->db->get_where(get_school_db().'.student_fee_settings' , array('school_id' =>$school_id,'fee_settings_id'=>$fee_settings_id))->result_array();
$fee_type_id = $query[0]['fee_type'];

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
                <form action="<?php echo base_url(); ?>c_student/month_fee_edit/" method="post" accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top" novalidate enctype="multipart/form-data">
                    <div class="form-group type_discount">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('fee_listing');?>  <span class="star">*</span>   </label>
                        <div class="col-sm-8">
                            <select name="fee_type_id_discount" class="form-control" disabled="">
                                <?php echo fee_student_installment($query[0]['fee_type_id']); ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('title'); ?> </label>
                        <div class="col-sm-8">
                            <input maxlength="200" type="text" class="form-control" name="title" value="<?php echo $query[0]['title']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('amount'); ?> </label>
                        <div class="col-sm-8">
                            <input maxlength="200" type="text" class="form-control" name="amount" value="<?php echo $query[0]['amount']; ?>">

                        </div>
                    </div>
                  <?php /*  <div class="form-group type_discount">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('amount');?>  <span class="star">*</span>   </label>
                        <div class="col-sm-8">
                            <select name="amount" class="form-control" data-validate="required" data-message-required="Value Required">
                                <?php  echo get_percentage_range($query[0]['amount']); ?>
                            </select>

                        </div>
                    </div> */ ?>



                    <div class="form-group">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('academic_months'); ?><span class="star">*</span>   </label>
                        <div class="col-sm-8">
                            <?php
                            $academic_year = get_student_academic_year($student_id);
                            $start_date = date("Y-m-d"); // 2017-04-01
                            $month_list = get_month_list($start_date,$academic_year['end_date']);
                            $month_year =  $query[0]['month']."_".$query[0]['year'];
                            ?>
                            <select name="month_year" class="form-control">
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
                        </div>
                    </div>
                    <!--<div class="form-group">-->
                    <!--    <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('setting_type'); ?>  <span class="star">*</span>   </label>-->
                    <!--    <div class="col-sm-8">-->
                    <!--        <select name="settings_type" class="form-control" data-validate="required" data-message-required="Value Required">-->
                    <!--            <option value=""><?php //echo get_phrase('select'); ?></option>-->
                    <!--            <option value="1" <?php //if($query[0]['settings_type'] == 1){ echo "selected";} ?>><?php //echo get_phrase('individual'); ?></option>-->
                    <!--            <option value="2" <?php //if($query[0]['settings_type'] == 2){ echo "selected";} ?>><?php //echo get_phrase('combined'); ?></option>-->
                    <!--        </select>-->
                    <!--    </div>-->
                    <!--</div>-->
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
        var fee_type = <?php echo $fee_type_id; ?>;
        $("#month_datepiker").datepicker({
            format: "mm/yyyy",
            startView: "months",
            minViewMode: "months"
        });
    });


</script>
