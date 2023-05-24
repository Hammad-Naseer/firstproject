<table class="table table-bordered table_export">
    <thead>
        <tr>
            <th width="55" ><div><?php echo get_phrase('#');?></div></th>
            <th><div><?php echo get_phrase('month').' - '.get_phrase('year');?></div></th>
            <th style="width:94px;" ><div><?php echo get_phrase('options');?></div></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $q="SELECT 
            count(scf.status) as status_count,bmc.activity,scf.status,scf.bulk_req_id 
            FROM ".get_school_db().".bulk_monthly_chalan bmc
            INNER JOIN ".get_school_db().".student_chalan_form scf
            ON scf.bulk_req_id=bmc.b_m_c_id
            WHERE scf.is_bulk=2  AND scf.school_id=".$_SESSION['school_id']." GROUP By scf.status,scf.bulk_req_id
            ORDER BY scf.bulk_req_id";

            $query=$this->db->query($q)->result_array();
            $array_status=array();
            foreach($query as $res)
            {
            	$array_status[$res['bulk_req_id']][$res['status']]=$res['status_count'];
            }
            echo "<br>";
            echo "<br>";
            $j=0;
            foreach($query_promotion as $row)
            {
                $j++;
        ?>
        <tr>
            <td class="td_middle">
                  <?php echo $j; ?>
            </td>
            <td>
                <div class="myttl">
                    <?php echo month_of_year($row['fee_month']).' - '.$row['fee_year'];?>
                </div>
                <div>
                    <strong>  <?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>
                    <ul class="breadcrumb" style="padding:2px; display:inline;">
                        <li>  <?php echo  $row['department_name']  ; ?> </li>
                        <li>  <?php echo  $row['class_name']  ; ?> </li>
                        <li>  <?php echo  $row['section_name']  ; ?> </li>
                    </ul>
                </div>
                <div>
                <?php
                    $Total=0;
                    if(isset($array_status[$row['b_m_c_id']]))
                    {
                        $Total=array_sum($array_status[$row['b_m_c_id']]);
                        echo '<b>'.get_phrase("total_challan_forms")."</b> : ".$Total;
                        $paid=0;
                        if(isset($array_status[$row['b_m_c_id']][5]))
                        {
                            echo "<br>";
                            echo '<b>'.get_phrase("paid_challan_forms")."</b>: ".$paid=$array_status[$row['b_m_c_id']][5];
                        }
                        echo "<br>";
                        echo '<b>'.get_phrase("unpaid_challan_forms")."</b>: ".($Total-$array_status[$row['b_m_c_id']][5]);
                    }
                    if(($row['activity']<5) && ($paid==$Total) )
                    {
                    	$activity['activity']=5;
                        $this->db->where("b_m_c_id",$row['b_m_c_id']);
                        $this->db->update(get_school_db().".bulk_monthly_chalan",$activity);
                    }
                    echo "<br>";
                ?>
                <strong><?php echo get_phrase('status');?>: </strong>
                <?php echo monthly_class_status($row['activity']);?>
                </div>
                <div>
                	<strong><?php echo get_phrase('generated_date');?>: </strong>
                	<?php $date=explode(' ',$row['date_time']);echo convert_date($date[0]);?>
                </div>
			</td>
            <td class="td_middle">
            <?php
                if($row['activity']>=5) {
            ?>
                <b class="text-success">Challan Paid</b>
            <?php }else{ ?>
                <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" data-step="5" data-intro="Select This Option & Choose Manage Monthly Chalan Then Issued Chalan & Print Chalan" data-position='right' data-scrollTo='tooltip'><?php echo get_phrase('action');?><span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                     <!-- STUDENT promote LINK -->
                    <?php if($row['activity']==4 && $paid!=$Total){?>
                    <li>
                    <a href="<?php echo base_url(); ?>monthly_fee/view_print_chalan_class/<?php echo $row['section_id']; ?>/<?php echo $row['fee_month']; ?>/<?php echo $row['fee_year']; ?>"
                     class="">
                    <i class="fa fa-print"></i>
                    <?php echo get_phrase('print_chalan_form');?>
                    </a>
                    </li>
                    <li class="divider"></li>
                    <?php } ?>
                    <!-- STUDENT DELETION LINK -->
                    <?php if($row['activity']<4){ ?>
                    <li>
                        <a href="#" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/monthly_chalan_status/<?php echo $row['b_m_c_id'];  ?>');">
                        <i class="entypo-pencil"></i><?php echo get_phrase('manage_monthly_challan');?></a>
                    </li
                    <li class="divider"></li>
                    <?php } ?>
                    <?php if($row['activity']==4 && $paid!=$Total){?>
                    <li>
                        <a href="<?php echo base_url(); ?>monthly_fee/view_detail_listing/<?php echo $row['b_m_c_id']; ?>"
                         class="">
                        <i class="fa fa-user"></i>
                        <?php echo get_phrase('receive_challan');?>
                        </a>
                    </li>
                    
                    <?php } ?>
                    <?php 
                        if($row['activity']>=5 || $paid>0) {}else{
                    ?>
                    <li class="divider"></li>
                    <li>
                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>monthly_fee/cancel_request/<?php echo $row['b_m_c_id'];?>');">
                            <i class="fa fa-trash" style="color:#4a8cbb!important;"></i>
                            <?php echo get_phrase('cancel_chalan');?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
            </td>
            <?php if($row['account_status']==0){
            	$account_status=1;
            }else{
            	$account_status=0;
            } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
<script>
    $(".table_export").DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
</script>