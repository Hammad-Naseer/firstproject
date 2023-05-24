<style>
	
.heading_s{
	
text-align: center; font-size: 16px;    text-decoration: underline;

}
.text_style{
	    font-size: 12px;
    font-weight: bold;
}

.border_right{
 padding: 3px;
border-right: 1px solid black;
}


.border_left{
 padding: 3px;
border-left: 1px solid black;
}



.border_top{
 padding: 3px;
border-top: 1px solid black;
}


.border_bottom{
 padding: 3px;
border-bottom: 1px solid black;
}

.border_div{
 padding: 0px;
   border: 1px solid black;	
}

.back-link{
	clear:both;
	margin-top:15px;
	padding-left:0;
}
	.mgb25{margin-bottom:25px;}
	.mgb10{    margin-bottom: 10px;}
</style>

<?php $c_c_f_id=$_POST['c_c_f_id'];
$section_id=$_POST['section_id'];
$qre_s="select distinct * from  ".get_school_db().".class_chalan_form where school_id=".$_SESSION['school_id']."  and c_c_f_id = ".$c_c_f_id."";
$query=$this->db->query($qre_s)->result_array(); ?>
	
	
	
	

<?php 
    $copy_ary=array(1=>get_phrase('bank_copy'),2=>get_phrase('college_copy'),3=>get_phrase('student_copy'));
    for($i=1; $i<=1; $i++ ){
?>
<div class="panel-body">
    <div class="row ">
        <div class="col-md-4 col-lg-4 col-sm-4 border_div" style="    margin-left: 32px;">
            <?php
                $school_id = $_SESSION['school_id'];
                $chalan_setting = $this->db->query("select * from " . get_school_db() . ".chalan_settings where school_id=$school_id")->result_array();
            ?>
            <table>
                <tr>
                    <td class="text_style border_bottom " colspan="2">
                        <table>
                            <tr>
                                <td class="text_style"><img height="70" width="70"
                                                            src="<?php echo display_link($chalan_setting[0]['logo'], '') ?>"/>
                                </td>

                                <td class="text_style " class="heading_s"><strong><?php
                                        echo $chalan_setting[0]['school_name']; ?></strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="text_style border_bottom" colspan="2"><?php
                        echo $chalan_setting[0]['bank_details']; ?></td>
                </tr>
                <tr>

                    <td class="text_style border_bottom border_right "><?php echo get_phrase('chalan'); ?>#</td>
                    <td class="text_style border_bottom border_right "><?php echo $copy_ary[$i]; ?></td>
                </tr>
                <tr>

                    <td class="text_style border_bottom border_right "><?php echo get_phrase('roll'); ?>#</td>
                    <td class="text_style border_bottom border_right "></td>
                </tr>
                <tr>

                    <td class="text_style border_bottom border_right "><?php echo get_phrase('student_name'); ?></td>
                    <td class="text_style border_bottom border_right "></td>
                </tr>
                <tr>

                    <td class="text_style border_bottom border_right "><?php echo get_phrase('department'); ?></td>
                    <td class="text_style border_bottom border_right "></td>
                </tr>
                <tr>

                    <td class="text_style border_bottom border_right "><?php echo get_phrase('class'); ?>
                        /<?php echo get_phrase('section'); ?></td>
                    <td class="text_style border_bottom border_right "></td>
                </tr>
                <tr>

                    <td class="text_style border_bottom border_right "><?php echo get_phrase('issue_date'); ?></td>
                    <td class="text_style border_bottom border_right "></td>
                </tr>
                <tr>

                    <td class="text_style border_bottom border_right "><?php echo get_phrase('due_date'); ?></td>
                    <td class="text_style border_bottom border_right "></td>
                </tr>
                <table class="ccf">

                    <tr style="font-weight: bold;">
                        <td class="text_style  border_bottom border_right;" width="7%">
                            <strong><?php echo get_phrase('s_no'); ?></strong></td>
                        <td class="text_style  border_bottom border_right" width="72%">
                            <strong><?php echo get_phrase('fee_title'); ?></strong></td>
                        <td class="text_style  border_bottom" width="20px">
                            <strong><?php echo get_phrase('amount'); ?></strong></td>
                    </tr>


                    <?php
                    $this->load->helper("num_word");
                    $query_rec = $this->db->query("SELECT ft.title, ccfe.order_num,ccfe.value
FROM " . get_school_db() . ".fee_types
 ft inner join " . get_school_db() . ".class_chalan_fee ccfe on ccfe.fee_type_id=ft.fee_type_id where ccfe.c_c_f_id=$c_c_f_id ORDER BY ccfe.order_num")->result_array();
                    $totle = 0;
                    $count_num = 1;
                    foreach ($query_rec as $rec_row) {
                        echo "<tr>";
                        ?>
                        <td class="text_style border_bottom  border_right" width="7%"><?php echo $count_num;
                            $count_num++; ?></td>

                        <td class="text_style border_bottom  border_right" width="72%">
                            <?php echo $rec_row['title']; ?>
                        </td>

                        <td class="text_style border_bottom" width="20%"><?php echo $rec_row['value'];

                            $totle = $rec_row['value'] + $totle;

                            ?>
                        </td>


                        <?php
                        echo "</tr>";
                    }
                    ?>


                    <tr>
                        <td class="text_style border_bottom  border_right"></td>
                        <td class="text_style border_bottom  border_right">
                            <strong><?php echo get_phrase('total_fee'); ?></strong></td>
                        <td class="text_style border_bottom"><strong><?php echo $totle; ?></strong></td>


                    </tr>
                    <tr>
                        <td colspan="3" class="text_style  border_bottom border_right" width="7%">&nbsp;</td>
                    </tr>





                    <?php


                    $query_discount_str = "SELECT dl.title as discount_title , 
                              ft.title as fee_title , 
                              ccd.value as discount_value,
                              ccf.value as fee_value FROM " . get_school_db() . ".discount_list as dl 
                        INNER JOIN " . get_school_db() . ".class_chalan_discount as ccd on dl.discount_id = ccd.discount_id
                        INNER JOIN " . get_school_db() . ".class_chalan_fee as ccf on ccf.fee_type_id = dl.fee_type_id
                        INNER JOIN " . get_school_db() . ".fee_types as ft on ft.fee_type_id = ccf.fee_type_id
                        WHERE ccd.c_c_f_id = $c_c_f_id
                            and ccf.c_c_f_id = $c_c_f_id
                            and ccd.school_id = " . $_SESSION['school_id'] . "";

                    $query_discount_query = $this->db->query($query_discount_str)->result_array();
                    $i = 1;
                    $total_discount_amount = 0;
                    if (count($query_discount_query) > 0)
                    {
                        ?>

                        <tr>
                            <td class="text_style  border_bottom border_right" width="7%">
                                <strong><?php echo get_phrase('s_no'); ?></strong></td>
                            <td class="text_style  border_bottom border_right" width="72%">
                                <strong><?php echo get_phrase('discount_title'); ?></strong></td>
                            <td class="text_style  border_bottom" width="20px;">
                                <strong><?php echo get_phrase('amount'); ?></strong></td>
                        </tr>

                        <?php
                        foreach ($query_discount_query as $discount_row)
                        {
                            $discount_single_amount = round((($discount_row['fee_value'] * $discount_row['discount_value']) / 100));
                            $total_discount_amount += $discount_single_amount;
                            //  $totle=round($totle-$discount_single_amount);

                            echo "<tr>";
                            ?>
                            <td class="text_style border_bottom  border_right" width="7%"><?php echo $i; ?></td>

                            <td class="text_style border_bottom  border_right" width="72%">
                                <?php echo $discount_row['discount_title'] . " (" . $discount_row['fee_title'] . ")  " . $discount_row['discount_value'] . "%"; ?>
                            </td>
                            <?php
                            ?>
                            <td class="text_style border_bottom" width="20%"><?php echo $discount_single_amount; ?>
                            </td>


                            <?php
                            echo "</tr>";
                            $i++;
                        }
                        ?>
                        <tr>
                            <td class="text_style  border_bottom border_right" width="7%">&nbsp;</td>
                            <td class="text_style  border_bottom border_right" width="7%"><strong><?php echo get_phrase('total_discounts'); ?></strong></td>
                            <td class="text_style  border_bottom border_right" width="7%"><strong><?php echo $total_discount_amount; ?></strong></td>
                        </tr>
                        <tr>
                        <td colspan="3" class="text_style  border_bottom border_right" width="7%">&nbsp;</td>
                        </tr>
                        <?php
                    }
    ?>






    <tr>
        <td class="text_style  border_bottom border_right" width="7%">&nbsp;</td>
        <td class="text_style  border_bottom border_right" width="7%"><strong><?php echo get_phrase('net_amount'); ?></strong></td>
        <td class="text_style  border_bottom border_right" width="7%"><strong><?php echo $totle-$total_discount_amount; ?></strong></td>
    </tr>

    <tr>
	<td class="text_style " colspan="3"><strong> <?php echo get_phrase('in_words');?> : </strong> <span style="text-transform: capitalize"> <?php echo convert_number_to_words($totle); ?></span>
	</td>
</tr>
</table>
            </table>
            <div class="col-sm-12" style="font-size: 12px;">
                <?php 
                    echo nl2br($chalan_setting[0]['terms']); echo "<br>";
                ?>
                <span><?php echo get_phrase('issued_by');?>: </span>
                <br />
            	<p style="font-size: 8px !important; font-weight:bold; border-top:1px solid #000; margin-top:10px;"><?php echo get_phrase('note');?>: <?php echo get_phrase('this_challan_form_is_computer_generated_and_does_not_require_any_signature');?>. </p>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php foreach($query as $row) { 
    if($row['detail'] !== ""){
?>
<div class="col-sm-12 mgb10" style="font-size:12px;">
    <div><strong><?php echo get_phrase('detail');?>: </strong><span class="item"><?php echo $row['detail'];?></span></div>
</div>
<?php } } ?>

<style type="text/css">
    .ccf strong{#000;}
</style>