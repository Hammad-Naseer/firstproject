<style>
    .mgt40{
        margin-top:40px !important
    }
    div#table_export11_paginate {
        margin-top: 40px;
    }
    div#table_export11_info {
        margin-top: 30px;
    }
</style>
<?php
$school_id = $_SESSION['school_id'];
if($this->uri->segment(4) == "bulkpaid"){
    echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
      Bulk Challan Paid Successfully
     </div> 
    </div>';
}
if($this->session->flashdata('club_updated'))
{
    echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
}


if($this->session->flashdata('delete_challan_form'))
{
    echo '<div align="center">
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
        '.$this->session->flashdata('delete_challan_form').'
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


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('monthly_challan_listing');?>
        </h3>
    </div>
</div>



<form action="<?=base_url()?>monthly_fee/view_detail_listing/<?=$this->uri->segment(3)?>" method="post">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <label for="paid_unpaid_records">Select Fee Status</label>
                <select name="paid_unpaid_records" id="paid_unpaid_records" class="form-control" data-validate="required" data-message-required="Value Required" required>
                    <option value="">Select Fee Status</option>
                    <option value="5">Paid</option>
                    <option value="4">Unpaid</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <button type="submit" class="btn btn-primary" style="position:relative;top:27px;"> Search Filter</button>
        </div>
	</div>
</form>


<form action="<?php echo base_url() ?>class_chalan_form/monthly_bulk_challan_paid" method="POST">
    <input type="hidden" name="url_challan_id" value="<?= $this->uri->segment('3'); ?>">
    <?php
        $q="SELECT bmc.fee_month,bmc.fee_year,bmc.activity,
            COUNT(scf.status) as status_count,scf.status,scf.bulk_req_id 
            FROM ".get_school_db().".bulk_monthly_chalan bmc
            INNER JOIN ".get_school_db().".student_chalan_form scf
            ON scf.bulk_req_id=bmc.b_m_c_id
            WHERE scf.school_id=".$_SESSION['school_id']." 
            and scf.bulk_req_id=$b_m_c_id 
            GROUP By scf.status,scf.bulk_req_id
            ORDER BY scf.bulk_req_id";
        $res = $this->db->query($q)->result_array();
        $array_status=array();
        
        foreach($res as $result)
        {
        
            $array_status[$result['bulk_req_id']][$result['status']]=$result['status_count'];
            $month = $result['fee_month'];
            $year = $result['fee_year'];
        
        }
    
        $qqrr_r = "select 
                        br.b_m_c_id,
                        br.date_time,
                        br.activity,
                        br.fee_month,
                        br.fee_year,
                        br.section_id,
                        cs.title as section_name,
                        c.name as class_name,
                        d.title as department_name
                        from " . get_school_db() . ".bulk_monthly_chalan br 
                        inner join " . get_school_db() . ".class_section cs on cs.section_id=br.section_id 
                        inner join " . get_school_db() . ".class c on c.class_id=cs.class_id
                        inner join " . get_school_db() . ".departments d on d.departments_id=c.departments_id
                        
                        where br.school_id=$school_id and br.status=1 and br.b_m_c_id=$b_m_c_id  $sear order by br.date_time";
        $query = $this->db->query($qqrr_r)->result_array();
    ?>
    <div class="col-md-12 col-lg-12 col-sm-12">
        <?php 
            if($this->session->flashdata('paid_bulk'))
            { 
                echo $this->session->flashdata('paid_bulk');
            }
        ?>
        <div class="col-lg-7 myttl">
            <strong><?php echo get_phrase('fee_month'); ?> : </strong>
            <?php echo month_of_year($query[0]['fee_month']).' - '.$query[0]['fee_year'];?>
        </div>
                
        <div class="col-lg-7">
            <strong><?php echo get_phrase('department'); ?>/<?php echo get_phrase('class'); ?>/<?php echo get_phrase('section'); ?> :</strong> <?php echo $query[0]['department_name'].' / '.$query[0]['class_name'].' / '.$query[0]['section_name']; ?>
        </div>

        <div class="col-lg-7">

            <?php
            $Total=0;
            if(isset($array_status[$query[0]['b_m_c_id']]))
            {
                $Total=array_sum($array_status[$query[0]['b_m_c_id']]);
                echo "<b>Total Challan Forms : ".$Total;
                echo "</b><br>";
                $paid=0;
                if(isset($array_status[$query[0]['b_m_c_id']][5])){
                    echo "<b class='text-success'>Paid: ".$paid=$array_status[$query[0]['b_m_c_id']][5];
                    echo "</b><br>";
                }
                echo "<b class='text-danger'>Un-Paid: ".($Total-$array_status[$query[0]['b_m_c_id']][5]);
                echo "</b>";
            }
            if(($query[0]['activity']<5) && ($paid==$Total) )
            {
                $activity['activity']=5;
                $this->db->where("b_m_c_id",$query[0]['b_m_c_id']);
                $this->db->update(get_school_db().".bulk_monthly_chalan",$activity);


            }
            ?>
            <br>
            <strong><?php echo get_phrase('status'); ?> :</strong> <?php echo monthly_class_status( $query[0]['activity']);
            ?>

        </div>


    </div>
    <table class="table table-bordered table-hover datatable cursor mt-4" id="table_export11" style="position: relative;top: 30px;">
    <thead>
    <tr>
        <th class="select-checkbox" style="width:8%" data-step="1" data-intro="If you have recieved all challans then click here">
            <input type="checkbox" id="select-all" name="select-all" >
            <input  type='hidden' name='student_ids[]' id='student_ids' />
        </th>
        <th  style="width:34px; "><div><?php echo get_phrase('#');?></div></th>
        <th  ><div><?php echo get_phrase('Details');?></div></th>

        <th  style="width:94px; "><div><?php echo get_phrase('options');?></div></th>
    </tr>
    </thead>
    <tbody>
    <?php
        $j=1;
        $school_id = $_SESSION['school_id'];
        if($check_fee_status){ $add_where = "AND scf.status =".$check_fee_status;}else{$add_where = "";}
        $query_smfs_str = "SELECT scf.is_cancelled, scf.form_type , scf.s_c_f_id, st.name as student_name , st.roll as roll_nmber , 
                        scf.father_name, scf.status,
                        scf.c_c_f_id,
                        scf.s_c_f_month,
                        scf.s_c_f_year,
                        scf.student_id,
                        scf.bulk_req_id,
                        scf.due_date,
                        scf.received_date,
                        scf.chalan_form_number FROM ".get_school_db().".student_monthly_fee_settings as smfs 
                          INNER JOIN ".get_school_db().".bulk_monthly_chalan as bmc ON smfs.b_m_c_id = bmc.b_m_c_id 
                          INNER JOIN ".get_school_db().".student as st on st.student_id = smfs.student_id
                          INNER JOIN ".get_school_db().".student_chalan_form as scf on ((scf.student_id = st.student_id) AND (scf.bulk_req_id = $b_m_c_id))  
                          WHERE bmc.b_m_c_id = $b_m_c_id 
                              AND bmc.school_id = $school_id $add_where";

    $query_smfs = $this->db->query($query_smfs_str)->result_array();

    foreach($query_smfs as $row)
    {
        $c_c_f_id = $row['c_c_f_id'];
        $date_month = $row['s_c_f_year'].'-'.$row['s_c_f_month'];
        $student_id = $row['student_id'];
        $form_type = $row['form_type'];
        $bulk_req_id = $row['bulk_req_id'];
    ?>
    <tr>
        <td class="td_middle">
            <?php 
                if ($row['status'] == 5)
                {
                    echo "<b class='text-success'>Paid</b>";
            ?>
            <?php }else{ ?>
            <input type="checkbox" class="std_checkbox" name="s_c_f_id[]" value="<?php echo $row['s_c_f_id']; ?>">
            
            <?php } ?>
        </td>
        <td><?php echo $j; ?> </td>
        <td>

            <div class="myttl"><?php echo $row['student_name'];
                ?><span
                        style="font-size:11px;">(<?php echo get_phrase('roll'); ?>#:<?php echo $row['roll_nmber'];
                    ?>)</span></div>
            <span style="display: none;">
                    <?php
                    //echo $row['bar_code'];
                    echo str_replace('_', '|', (substr($row['bar_code'], 0, (strlen($row['bar_code']) - strlen(strrchr($row['bar_code'], '.'))))));
                    ?>
                </span>


            <div><strong><?php echo get_phrase('challan_form_number'); ?>#:</strong><?php echo $row['chalan_form_number'];
                ?>
            </div>


            <div><strong><?php echo get_phrase('father_name'); ?>: </strong><?php echo $row['father_name']; ?></div>
            <div><strong><?php echo get_phrase('status'); ?>: </strong>
                <?php
                    $color = "";
                    if ($row['status'] == 5)
                    {
                        $color = "green";
                    } else
                    {
                        $color = "red";
                    }
                ?>
                <?php if ($row['is_cancelled'] == 0) { ?>
                <span class="<?php echo $color; ?>"><?php echo monthly_class_status($row['status']); ?></span>
                <?php }else{ ?>
                <span class="<?php echo $color; ?>"><?php echo monthly_class_status(6); ?></span>
                <?php } ?>
            </div>
            <div><strong><?php echo get_phrase('challan_due_date'); ?>: </strong><?php echo date_view($row['due_date']); ?></div>
            <div><strong><?php echo get_phrase('challan_received_date'); ?>: </strong>
            <?php 
                if ($row['status'] == 5)
                {
                    echo date_view($row['received_date']); 
                }
            ?>
           </div>
        </td>
        <td class="td_middle">
            <div class="btn-group" data-step="3" data-intro="If you have recieved only single challan then select this option">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <?php echo get_phrase('action'); ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                    <!-- STUDENT EDITING LINK -->
                    <?php if ($row['is_cancelled'] == 0) { ?>
                    <li>
                        <a href="<?php echo base_url(); ?>class_chalan_form/edit_chalan_form/<?php echo $row['s_c_f_id']; ?>/3/2/<?php echo $this->uri->segment(3); ?>">
                            <i class="entypo-pencil"></i>
                            <?php
                                if ($row['status'] == 4) {
                                    echo get_phrase('receive_chalan');
                                } else {
                                    echo get_phrase('view_chalan');
                                }

                            ?>
                            </a>
                        </li>
                        <li class="divider"></li>    
                        <?php if($row['status']==4)
                        {?>
                            <li>
                                <a href="<?php echo base_url(); ?>class_chalan_form/view_print_chalan/<?php echo  $row['s_c_f_id']; ?>/<?php echo $row['form_type'];  ?>"  >

                                    <i class="fa fa-print" style="color:white !important"></i>
                                    <?php echo get_phrase('print_chalan'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <?php
                        }
                        ?>

                        <?php if($row['status']<5){ ?>
                            <!-- STUDENT DELETION LINK -->


                            <li>

                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>promotion/delete_chalan/<?php echo  $row['s_c_f_id']; ?>/1/<?php echo  $row['form_type']; ?>');">

                                    <i class="entypo-trash"></i>
                               <?php echo get_phrase('cancel_chalan'); ?>
                                </a>
                            </li>

                        <?php } ?>
                    <?php }
                    else
                    {

                        ?>
                        <li>
                                <a href="<?php echo base_url(); ?>monthly_fee/re_generate_chalan_form/<?php echo  $student_id.'/'.$row['s_c_f_id'].'/'.$bulk_req_id.'/'.$c_c_f_id.'/'.$date_month.'/'.$student.$form_type; ?>">
                                <i class="entypo-trash"></i>
                                <?php echo get_phrase('re_generate'); ?>
                            </a>
                        </li>

                    <?php

                    }?>

                    </ul>
                </div>

            </td>
        </tr>
    <?php
    $j++;
    }?>
    </tbody>
</table>
    <div class="col-md-12 mt-4">
        <div class="form-group">
            <lable><b>Payment Date</b></lable>
            <input type="date" name="payment_date" class="form-control" required="">
        </div>
        <div class="form-group">
            <button class="modal_save_btn float-right" style="margin-top: 15px !important;" data-step="2" data-intro="press this button to receieve all students challans">Bulk Chalan Recieved</button>
        </div>    
    </div>
</form>
<script>
$(document).ready(function() {
$("#paid_unpaid_records").val('<?php echo $check_fee_status ?>');

let example = $('#table_export11').dataTable({
            stateSave: true,
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                // targets: 1
            }],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            "lengthMenu": [100, 250, 500, 10000],
            order: [
                [1, 'asc']
            ]
        });
   var ads = [];
        var allPages = example.fnGetNodes();
        example.on("click", "th.select-checkbox", function() {     
            // alert("Ok");
            if ($("th.select-checkbox").hasClass("selected")) {
                $('input[type="checkbox"]', allPages).prop('checked', false);
                $("th.select-checkbox").removeClass("selected");
                ads = [];
            } else {
                $('input[type="checkbox"]', allPages).prop('checked', true);
                $("th.select-checkbox").addClass("selected");
                
                example.$('input[type="checkbox"]').each(function(){
                   if(this.checked){
                        ads.push(this.value);
                    }
                });
            }
            $('#student_ids').val(ads); 
        });
        
        
        $('.std_checkbox').on("click", function() {
            ads = [];
            example.$('input[type="checkbox"]').each(function(){
                if(this.checked){
                    ads.push(this.value);
                }
            });
            $('#student_ids').val(ads); 
        });
});
</script>