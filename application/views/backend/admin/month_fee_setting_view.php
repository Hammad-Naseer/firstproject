<?php
$school_id=$_SESSION['school_id'];
$title = get_phrase('month_fee_settings_edit');
$student_id = $param2;
$section_id = $param3;
$academic_month =  $param4;//"04_2017";//
$academic_month_temp = explode("_",$academic_month);
$month = $academic_month_temp[0];
$year = $academic_month_temp[1];
$fee_type_amount = array('amount'=>0,'title'=>'');
$total_amount_fee = 0;
//$total_amount_discount = 0;

?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title black2" >
                    <!--<i class="entypo-plus-circled"></i>-->
                    <?php echo $title ;
                    echo "( ".date("F Y", mktime(0,0 , 0 , $month , 1 , $year )) . " )";
                    ?>
                </div>
            </div>


            <?php
            $query_individual_sfs_str = "select sfs.* from ".get_school_db().".student_fee_settings as sfs
                    INNER JOIN ".get_school_db().".fee_types as ft on ft.fee_type_id = sfs.fee_type_id
                    where sfs.school_id=$school_id
                    AND sfs.student_id= $student_id
                    AND sfs.month = $month
                    AND sfs.year = $year
                    AND sfs.academic_year_id = $section_id
                    AND sfs.settings_type = 1
                    AND sfs.fee_type = 1
                    ORDER BY sfs.is_bulk asc;
            ";
            //$query_delete_msfs =$this->db->query($query_select_msfs_str);
            $query_individual_sfs  = $this->db->query($query_individual_sfs_str)->result_array();


            if(count($query_individual_sfs)>0)
            {

            ?>
            <div class="panel-body">

                <form action="<?php echo base_url(); ?>c_student/individual_month_fee_edit/" method="post"
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
                                            <td><?php echo $val_fee['title']; ?></td>
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
                </form>
                <?php
                }
                else
                {?>



            <div class="panel-body">



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
                                $fee_id_array = array();
                                $query_sfs_fee_str = "SELECT sfs.is_bulk ,  ft.title as ft_title , ft.fee_type_id as ft_fee_type_id , sfs.amount as sfs_amount FROM ".get_school_db().".student_fee_settings  as sfs
                                        INNER JOIN ".get_school_db().".fee_types as ft on ft.fee_type_id = sfs.fee_type_id
                                        WHERE sfs.fee_type = 1 AND sfs.student_id = $student_id AND sfs.school_id = $school_id
                                        AND (sfs.month = $month and sfs.year = $year)
                                        AND sfs.settings_type = 2
                                        ORDER BY sfs.is_bulk asc";
                                $query_sfs_fee = $this->db->query($query_sfs_fee_str)->result_array();
                                $i = 1;
                                $id_not_allowed_fee = array();
                                if((count($query_sfs_fee)>0))
                                {
                                    ?>
                                    <tbody>
                                    <?php

                                    $total_amount_fee = 0;

                                    foreach ($query_sfs_fee as $key_c_fee => $val_c_fee)
                                    {

                                        $fee_id_array[] =  $val_c_fee['ft_fee_type_id'];
                                        $fee_type_amount[$val_c_fee['ft_fee_type_id']]['amount'] = $val_c_fee['sfs_amount'];
                                        $fee_type_amount[$val_c_fee['ft_fee_type_id']]['title'] = $val_c_fee['ft_title'];
                                        $total_amount_fee =$total_amount_fee + $val_c_fee['sfs_amount'];

                                        $is_bulk = $val_c_fee['is_bulk'];
                                        $custom_fee = "";
                                        if($is_bulk == 0)
                                        {
                                          //  $custom_fee = "<span class='red'>" - " . get_phrase('custom_fee') . </span>";
                                            $custom_fee = "<span class='red'> - ".get_phrase('custom_fee')."</span>";

                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $val_c_fee['ft_title'].$custom_fee; ?></td>
                                            <td><?php echo $val_c_fee['sfs_amount']; ?></td>
                                        </tr>
                                        <?php

                                        $i++;
                                    }
                                    ?>

                                    </tbody>

                                    <?php
                                } ?>


                                <tbody>

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
                                $fee_id_array_str = "";
                                if(count($fee_id_array)>0)
                                {
                                    $fee_id_array_str = implode(",",$fee_id_array);
                                    $fee_id_array_str =  "AND dl.fee_type_id in($fee_id_array_str)";
                                }
                                $query_sfs_discount_str  = "SELECT sfs.is_bulk, dl.title as dl_title , dl.fee_type_id as dl_fee_type_id , sfs.amount as sfs_amount FROM ".get_school_db().".student_fee_settings  as sfs
                              INNER JOIN ".get_school_db().".discount_list as dl on (dl.discount_id = sfs.fee_type_id)
                              $fee_id_array_str
                                            WHERE sfs.fee_type = 2 AND sfs.student_id = $student_id AND sfs.school_id = $school_id
                                            AND (sfs.month = $month and sfs.year = $year)
                                            AND sfs.settings_type = 2
                                            ORDER BY sfs.is_bulk asc";
                                $query_sfs_discount = $this->db->query($query_sfs_discount_str)->result_array();

                                if((count($query_sfs_discount)>0))
                                {

                                    //$fee_amount = 0;
                                    $discount_amount = 0;


                                    foreach($query_sfs_discount as $key_fee=> $val_fee)
                                    {
                                        $discount_type_amount[$val_fee['dl_fee_type_id']] = $val_fee['amount'];
                                        $fee_amount = $fee_type_amount[$val_fee['dl_fee_type_id']]['amount'];

                                        $actual_amount = $fee_amount;
                                        $fee_title =  $fee_type_amount[$val_fee['dl_fee_type_id']]['title'];
                                        $discount_amount = $val_fee['sfs_amount'];
                                        $discount_amount_str = "( Discount given by ".$fee_title."(".$actual_amount.")"." - ".$discount_amount."% )";
                                        $fee_amount = round(($fee_amount) * ($discount_amount / 100));
                                        $total_amount_discount = $total_amount_discount+$fee_amount;
                                        $is_bulk = $val_fee['is_bulk'];
                                        $custom_fee = "";
                                        $custom_discount = "";
                                        if($is_bulk == 0)
                                        {
                                            //$custom_discount = "<span class='red'> - ".get_phrase('custom_discount')."</span>";
                                          //  $custom_discount = "<span class='red'>" - " . get_phrase('custom_discount') . </span>";
                                            $custom_discount = "<span class='red'> - ".get_phrase('custom_discount')."</span>";
                                        }

                                        // if()



                                        ?>
                                        <tr>
                                            <td><?php echo $j; ?></td>
                                            <td><?php echo $val_fee['dl_title'].$discount_amount_str.$custom_discount; ?></td>
                                            <td><?php echo $fee_amount; ?></td>


                                            <input type="hidden" name="month_date" value="<?php echo $academic_month; ?>">


                                        </tr>
                                        <?php
                                        $j++;
                                    }
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
                                    <td><strong><?php echo $total_amount_fee-$total_amount_discount; ?></strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
                <input maxlength="50" type="hidden" class="form-control"  id="aaaa"  name="student_id" value="<?php echo $student_id; ?>">
            </div>
                <?php } ?>
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