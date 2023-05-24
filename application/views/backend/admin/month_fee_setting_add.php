<?php
$school_id=$_SESSION['school_id'];
$title = get_phrase('month_fee_settings_add');
$student_id = $param2;
$section_id = $param3;
$academic_month =  $param4;//"04_2017";//
$academic_month_temp = explode("_",$academic_month);
$month = $academic_month_temp[0];
$year = $academic_month_temp[1];

$fee_type_amount = array('amount'=>0,'title'=>'');
$total_amount_fee = 0;
//$total_amount_discount = 0;
$comments = "";
$status = 0;



//$query=$this->db->get_where(get_school_db().'.student_fee_settings' , array('school_id' =>$_SESSION['school_id'],'fee_settings_id'=>$s_m_i_id))->result_array();

?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title black2" >
                    <!--<i class="entypo-plus-circled"></i>-->
                    <?php echo $title ;?>
                    <?php //echo convert_date("2017-05");

                    echo "( ".date("F Y", mktime(0,0 , 0 , $month , 1 , $year )) . " )";

                    ?>
                </div>
            </div>
            <?php
            $query_individual_sfs_str = "select sfs.* from ".get_school_db().".student_fee_settings as sfs
                    INNER JOIN ".get_school_db().".fee_types as ft on ft.fee_type_id = sfs.fee_type_id
                    WHERE sfs.school_id=$school_id
                    AND sfs.student_id= $student_id
                    AND sfs.month = $month
                    AND sfs.year = $year
                    AND sfs.academic_year_id = $section_id
                    AND sfs.settings_type = 1
                    AND sfs.fee_type = 1;
            ";
            //$query_delete_msfs =$this->db->query($query_select_msfs_str);
            $query_individual_sfs  = $this->db->query($query_individual_sfs_str)->result_array();


            if(count($query_individual_sfs)>0)
            {
            //  echo "individalual settings";

            ?>
            <div class="panel-body">

                <form action="<?php echo base_url(); ?>c_student/single_month_fee_add/" method="post"
                      accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top"
                      novalidate enctype="multipart/form-data">
                    <fieldset class="custom_legend text_month">
                        <legend class="custom_legend"><?php echo get_phrase('fee_list'); ?>:</legend>
                        <div class="form-group type_discount">

                            <div class="col-sm-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><?php echo get_phrase('sno'); ?></th>
                                        <th><?php echo get_phrase('fee'); ?></th>
                                        <th><?php echo get_phrase('amount'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    $total_individual_amount_fee = 0;
                                    $i=1;
                                    foreach ($query_individual_sfs as $key_fee => $val_fee)
                                    {
                                        $fee_settings_id = $val_fee['fee_settings_id'];
                                        $total_individual_amount_fee += $val_fee['amount'];
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $val_fee['fee_settings_id'] . " - " . $val_fee['title']; ?></td>
                                            <td><?php echo $val_fee['amount']; ?></td>
                                            <input type="hidden" name="fee_settings_id[]" value="<?php echo $fee_settings_id; ?>">
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    </tbody>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><strong><?php echo get_phrase('total_fee_amount'); ?></strong></td>
                                        <td><strong><?php echo $total_individual_amount_fee; ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </fieldset>



                    <div class="form-group type_discount">
                        <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('status');?> <span
                                    class="star">*</span> </label>
                        <div class="col-sm-8">
                            <select name="status" id="fee_type_id_discount" class="form-control"
                                    data-validate="required" data-message-required="Value Required">
                                <?php
                                ?>
                                <option value=""> <?php echo get_phrase('select'); ?> </option>
                                <option value="1" <?php if ($status == 1) {
                                    echo "selected";
                                } ?>><?php echo get_phrase('generate'); ?></option>
                                <option value="2" <?php if ($status == 2) {
                                    echo "selected";
                                } ?>><?php echo get_phrase('approve'); ?> </option>

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="comment"><?php echo get_phrase('comments'); ?></label>
                            <textarea class="form-control" rows="7" name="comments"
                                      id="comments"><?php //echo $comments; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-2" class="col-sm-4  control-label"></label>
                        <div class="col-sm-8">
                            <button type="submit" id="btnSubmit"
                                    class="btn btn-default"><?php echo get_phrase('save'); ?></button>
                        </div>
                    </div>


                    <input type="hidden" name="month_date" value="<?php echo $academic_month; ?>">
                    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">



                </form>
                <?php
                }
                else
                {
                    ?>
                    <div class="panel-body">

                        <form action="<?php echo base_url(); ?>c_student/bulk_month_fee_add/" method="post"
                              accept-charset="utf-8" class="form-horizontal form-groups-bordered validate" target="_top"
                              novalidate enctype="multipart/form-data">


                            <fieldset class="custom_legend text_month">
                                <legend class="custom_legend"><?php echo get_phrase('fee_list'); ?>:</legend>
                                <div class="form-group type_discount">


                                    <div class="col-sm-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th><?php echo get_phrase('sno'); ?></th>
                                                <th><?php echo get_phrase('fee'); ?></th>
                                                <th><?php echo get_phrase('amount'); ?></th>
                                            </tr>
                                            </thead>
                                            <?php


                                            $id_not_allowed_fee = array();
                                            $fee_id_array = array();
                                            $query_sfs_fee_str = "
                            SELECT sfs.is_bulk , sfs.fee_settings_id , sfs.title as fee_title , ft.fee_type_id as custom_fee_type_id, sfs.amount as amount 
                            FROM " . get_school_db() . ".fee_types  as ft
                            INNER JOIN " . get_school_db() . ".student_fee_settings as sfs 
                            on ((sfs.fee_type_id = ft.fee_type_id) AND (sfs.month = $month and sfs.year = $year) AND (sfs.is_bulk=0))
                            WHERE sfs.status = 1 AND sfs.fee_type = 1 AND sfs.student_id = $student_id AND sfs.school_id = $school_id AND sfs.settings_type = 2";
                                            $query_sfs_fee = $this->db->query($query_sfs_fee_str)->result_array();
                                            $i = 1;

                                            if ((count($query_sfs_fee) > 0))
                                            {
                                                ?>
                                                <tbody>
                                                <?php

                                                $total_amount_fee = 0;

                                                foreach ($query_sfs_fee as $key_c_fee => $val_c_fee)
                                                {
                                                    $fee_id_array[] = $val_c_fee['custom_fee_type_id'];
                                                    $fee_type_amount[$val_c_fee['custom_fee_type_id']]['amount'] = $val_c_fee['amount'];
                                                    $fee_type_amount[$val_c_fee['custom_fee_type_id']]['title'] = $val_c_fee['fee_title'];

                                                    $total_amount_fee = $total_amount_fee + $val_c_fee['amount'];

                                                    $id_not_allowed_fee[] = $val_c_fee['custom_fee_type_id'];

                                                    $is_bulk = $val_c_fee['is_bulk'];
                                                    $fee_settings_id = $val_c_fee['fee_settings_id'];
                                                    $custom_fee = "";

                                                    if ($is_bulk == 0)
                                                    {
                                                        $custom_fee = "<span class='red'> - ".get_phrase('custom_fee')."</span>";
                                                        ?>
                                                        <input type="hidden" name="assign_std_m_fee_settings_id[]"
                                                               value="<?php echo $fee_settings_id; ?>">
                                                        <?php
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php echo $val_c_fee['custom_fee_type_id'] . " - " . $val_c_fee['fee_title'] . $custom_fee; ?></td>
                                                        <td><?php echo $val_c_fee['amount']; ?></td>
                                                    </tr>
                                                    <?php
                                                    $i++;
                                                    ?>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                                <?php
                                            } ?>
                                            <tbody>
                                            <?php

                                            $id_not_allowed_fee_str = "";
                                            if (count($id_not_allowed_fee) > 0) {
                                                $id_not_allowed_discount_str = implode(",", $id_not_allowed_fee);
                                                $id_not_allowed_discount_str = "AND ft.fee_type_id not in($id_not_allowed_discount_str)";
                                            }

                                            $query_c_c_f_fee_str = "SELECT ft.fee_type_id as fee_fee_type_id, ft.title as fee_title , ccf_fee.value as amount  
                                        FROM " . get_school_db() . ".class_chalan_form as ccf_form
                                            INNER JOIN " . get_school_db() . ".class_chalan_fee as ccf_fee on ccf_fee.c_c_f_id = ccf_form.c_c_f_id
                                            INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = ccf_fee.fee_type_id
                                            WHERE ccf_form.status = 1 AND ccf_form.type = 2 AND ccf_form.section_id = $section_id
                                            AND ccf_form.school_id = $school_id
                                           $id_not_allowed_discount_str";

                                            $query_c_c_f_fee = $this->db->query($query_c_c_f_fee_str)->result_array();

                                            if ((count($query_c_c_f_fee) > 0))
                                            {
                                            ?>
                                            <?php
                                            foreach ($query_c_c_f_fee as $key_fee => $val_fee)
                                            {

                                                $fee_id_array[] = $val_fee['fee_fee_type_id'];
                                                $fee_type_amount[$val_fee['fee_fee_type_id']]['amount'] = $val_fee['amount'];
                                                $fee_type_amount[$val_fee['fee_fee_type_id']]['title'] = $val_fee['fee_title'];
                                                $total_amount_fee = $total_amount_fee + $val_fee['amount'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $val_fee['fee_fee_type_id'] . " - " . $val_fee['fee_title']; ?></td>
                                                    <td><?php echo $val_fee['amount']; ?></td>
                                                </tr>
                                                <?php
                                                $i++;

                                                /* fee posting data start */
                                                ?>
                                                <input type="hidden" name="fee_title[]"
                                                       value="<?php echo $val_fee['fee_title']; ?>">
                                                <input type="hidden" name="fee_fee_type_id[]"
                                                       value="<?php echo $val_fee['fee_fee_type_id']; ?>">
                                                <input type="hidden" name="fee_amount[]"
                                                       value="<?php echo $val_fee['amount']; ?>">
                                                <?php
                                                /* fee posting data end */
                                                ?>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                            </tbody>
                                        <?php
                                        }
                                        ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><strong><?php echo get_phrase('total_fee_amount'); ?></strong></td>
                                                <td><strong><?php echo $total_amount_fee; ?></strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="custom_legend text_month">
                                <legend class="custom_legend"><?php echo get_phrase('discounts_list'); ?>:</legend>
                                <div class="form-group type_discount">
                                    <div class="col-sm-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th><?php echo get_phrase('sno'); ?></th>
                                                <th><?php echo get_phrase('discount'); ?></th>
                                                <th><?php echo get_phrase('amount'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $j = 1;
                                            $id_not_allowed_discount = array();
                                            $total_amount_discount = 0;
                                            $fee_id_array_str = "";
                                            if (count($fee_id_array) > 0)
                                            {
                                                $fee_id_array_str = implode(",", $fee_id_array);
                                                $fee_id_array_str = "AND dl.fee_type_id in($fee_id_array_str)";
                                            }

                                            $query_sfs_discount_str = "
                                            SELECT sfs.is_bulk ,  sfs.fee_settings_id, sfs.title as discount_title , dl.fee_type_id as discount_sfs_id ,
                                            dl.discount_id as discount_sfs_d_id , sfs.amount as amount FROM " . get_school_db() . ".discount_list as dl 
                                            INNER JOIN " . get_school_db() . ".student_fee_settings AS sfs 
                                            ON (sfs.fee_type_id = dl.discount_id)
                                            $fee_id_array_str 
                                            AND (sfs.month = $month AND sfs.year = $year)
                                            AND (sfs.fee_type = 2) and (is_bulk = 0)
                                            WHERE
                                            sfs.school_id = $school_id
                                            AND sfs.student_id = $student_id
                                            AND sfs.settings_type = 2";

                                            $query_sfs_discount = $this->db->query($query_sfs_discount_str)->result_array();
                                            if ((count($query_sfs_discount) > 0))
                                            {
                                                //$fee_amount = 0;
                                                $discount_amount = 0;
                                                //$custom_discount = "<span class='red'> - ".get_phrase('custom_discount')."</span>";
                                                foreach ($query_sfs_discount as $key_fee => $val_fee)
                                                {
                                                    //$discount_type_amount[$val_fee['discount_sfs_id']] = $val_fee['amount'];
                                                    $fee_amount = $fee_type_amount[$val_fee['discount_sfs_id']]['amount'];
                                                    $id_not_allowed_discount[] = $val_fee['discount_sfs_d_id'];
                                                    $actual_amount = $fee_amount;
                                                    $fee_title = $fee_type_amount[$val_fee['discount_sfs_id']]['title'];
                                                    $discount_amount = $val_fee['amount'];
                                                    $discount_amount_str = " ".$discount_amount . "%" ." (" . $fee_title . " " . $actual_amount . ")";
                                                    $fee_amount = round(($fee_amount) * ($discount_amount / 100));
                                                    $total_amount_discount = $total_amount_discount + $fee_amount;

                                                    $is_bulk = $val_fee['is_bulk'];
                                                    $fee_settings_id = $val_fee['fee_settings_id'];
                                                    $custom_discount = "";

                                                    if ($is_bulk == 0)
                                                    {
                                                        // $custom_discount = " - " .get_phrase('custom_discount');
                                                        $custom_discount = "<span class='red'> - ".get_phrase('custom_discount')."</span>";

                                                        ?>
                                                        <input type="hidden" name="assign_std_m_fee_settings_id[]"
                                                               value="<?php echo $fee_settings_id; ?>">
                                                        <?php
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $j; ?></td>
                                                        <td><?php echo $val_fee['fee_settings_id'] . " - " . $val_fee['discount_sfs_d_id'] . " - " . $val_fee['discount_sfs_id'] . " - " . $val_fee['discount_title'] . $discount_amount_str . $custom_discount; ?></td>
                                                        <td><?php echo $fee_amount; ?></td>

                                                        <?php /*  <input type="hidden" name="discount_title[]" value="<?php echo $val_fee['discount_title']; ?>">
                                                <input type="hidden" name="discount_fee_type_id[]" value="<?php echo $val_fee['discount_sfs_d_id']; ?>">
                                                <input type="hidden" name="discount_amount[]" value="<?php echo $val_fee['amount']; ?>"> */ ?>
                                                    </tr>
                                                    <?php
                                                    $j++;
                                                }
                                                ?>
                                                <?php
                                            }
                                            ?>

                                            <?php

                                            $id_not_allowed_discount_str = "";
                                            if (count($id_not_allowed_discount) > 0)
                                            {
                                                $id_not_allowed_discount_str = implode(",", $id_not_allowed_discount);
                                                $id_not_allowed_discount_str = "AND dl.discount_id not in($id_not_allowed_discount_str)";
                                            }

                                            $query_c_c_f_discount_str = "
                                                 SELECT dl.discount_id as dl_discount__id, 
                                                        dl.fee_type_id as discount_fee_type_id, 
                                                        ccf_form.title as month_title, 
                                                        dl.title as discount_title, 
                                                        ccf_discount.value as amount  
                                                 FROM " . get_school_db() . ".class_chalan_form as ccf_form
                                                 INNER JOIN " . get_school_db() . ".class_chalan_discount as ccf_discount on ccf_discount.c_c_f_id = ccf_form.c_c_f_id
                                                 INNER JOIN " . get_school_db() . ".discount_list as dl on dl.discount_id = ccf_discount.discount_id
                                                 WHERE ccf_form.status = 1 AND ccf_form.type = 2 AND ccf_form.section_id = $section_id
                                                 AND ccf_form.school_id = $school_id
                                                 $id_not_allowed_discount_str";

                                            $query_c_c_f_discount = $this->db->query($query_c_c_f_discount_str)->result_array();
                                            if ((count($query_c_c_f_discount) > 0))
                                            {
                                                foreach ($query_c_c_f_discount as $key_fee => $val_fee) {
                                                    //  $discount_type_amount[$val_fee['discount_fee_type_id']] = $val_fee['amount'];
                                                    $fee_amount = $fee_type_amount[$val_fee['discount_fee_type_id']]['amount'];
                                                    $actual_amount = $fee_amount;
                                                    $fee_title = $fee_type_amount[$val_fee['discount_fee_type_id']]['title'];
                                                    $discount_amount = $val_fee['amount'];
                                                    $discount_amount_str = " " . $discount_amount . "% ( " . $fee_title ." ". $actual_amount ." )";
                                                    $fee_amount = round(($fee_amount) * ($discount_amount / 100));
                                                    $total_amount_discount = $total_amount_discount + $fee_amount;

                                                    ?>
                                                    <tr>
                                                        <td><?php echo $j; ?></td>
                                                        <td><?php echo $val_fee['discount_title'] . $discount_amount_str; ?></td>
                                                        <td><?php echo $fee_amount; ?></td>

                                                        <input type="hidden" name="discount_title[]"
                                                               value="<?php echo $val_fee['discount_title']; ?>">
                                                        <input type="hidden" name="discount_fee_type_id[]"
                                                               value="<?php echo $val_fee['dl_discount__id']; ?>">
                                                        <input type="hidden" name="discount_amount[]"
                                                               value="<?php echo $val_fee['amount']; ?>">


                                                    </tr>
                                                    <?php
                                                    $j++;
                                                }
                                                /* discount posting data start */
                                                ?>


                                                <?php

                                                /* discount posting data end */


                                                ?>

                                                <?php
                                            }
                                            ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><strong><?php echo get_phrase('total_discount_amount'); ?></strong></td>
                                                <td><strong><?php echo $total_amount_discount; ?></strong></td>
                                            </tr>

                                            <tr>
                                                <td colspan="3">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><strong><?php echo get_phrase('Net amount'); ?></strong></td>
                                                <td>
                                                    <strong><?php echo $total_amount_fee - $total_amount_discount; ?></strong>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>


                            <input type="hidden" name="month_date" value="<?php echo $academic_month; ?>">
                            <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">


                            <div class="form-group type_discount">
                                <label for="field-1" class="col-sm-4  control-label"><?php echo get_phrase('status');?> <span
                                            class="star">*</span> </label>
                                <div class="col-sm-8">
                                    <select name="status" id="fee_type_id_discount" class="form-control"
                                            data-validate="required" data-message-required="Value Required">

                                        <?php

                                        ?>
                                        <option value="" selected> <?php echo get_phrase('select'); ?> </option>
                                        <option value="1">
                                            <?php echo get_phrase('generate'); ?></option>
                                        <option value="2"><?php echo get_phrase('approve'); ?> </option>

                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">

                                    <label for="comment"><?php echo get_phrase('comments'); ?></label>
                                    <textarea class="form-control" rows="7" name="comments"
                                              id="comments"><?php //echo $comments; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" id="btnSubmit"
                                            class="btn btn-default"><?php echo get_phrase('save'); ?></button>
                                </div>
                            </div>

                        </form>


                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
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