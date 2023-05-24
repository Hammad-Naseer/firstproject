<?php 
    $school_id=$_SESSION['school_id'];
    $p="SELECT count(scf.status) as status_count,scf.status,scf.bulk_req_id FROM ".get_school_db().".bulk_request br
        INNER JOIN ".get_school_db().".student_chalan_form scf ON br.bulk_req_id=scf.bulk_req_id
        WHERE br.school_id=$school_id AND scf.is_bulk=1 GROUP By scf.status,scf.bulk_req_id ORDER BY scf.bulk_req_id ";
     $res=$this->db->query($p)->result_array();
     $array_status=array();
    foreach($res as $result)
    {
    	$array_status[$result['bulk_req_id']][$result['status']]=$result['status_count'];
    }
    $academic_year_id=$this->input->post('academic_year_id');
    $section_id=$this->input->post('section_id');
 
    $sear="";
    
    if(isset($academic_year_id) && $academic_year_id!=""){
        $sear=" and d.academic_year_id=$academic_year_id ";	
    }

    if(isset($section_id) && $section_id!=""){
        $sear=" and cs.section_id=$section_id ";	
    }
    
    $query_promotion =$this->db->query("select 
    br.bulk_req_id,
    br.activity,
    br.date_time,
    cs.title as section_name, 
    css.title as pro_section_name,
    ay.title as acadmic_year_name,
    ayy.title as pro_acadmic_year_name,
    cs.section_id as section_id,
    css.section_id as pro_section_id
    from ".get_school_db().".bulk_request br 
    inner join ".get_school_db().".class_section cs on cs.section_id=br.section_id 
    inner join ".get_school_db().".class_section css on css.section_id=br.pro_section_id 
    INNER JOIN ".get_school_db().".acadmic_year ay on ay.academic_year_id=br.academic_year_id
    INNER join ".get_school_db().".acadmic_year ayy ON ayy.academic_year_id=br.pro_academic_year_id
    where br.school_id=$school_id   $sear ORDER BY br.activity asc ")->result_array();
?>
<table class="table table-bordered table_export mt-4" id="table_export" data-step="3" data-position='top' data-intro="promotion record">
    <thead>
        <tr>
            <th Style="width:34px;"><div><?php echo get_phrase('s_no');?></div></th>
            <th ><div><?php echo get_phrase('details');?></div></th>
            <th style="width:94px;"><div><?php echo get_phrase('options');?></div></th>
        </tr>
    </thead>
        <tbody>
        <?php 
         $n=0;   
            foreach($query_promotion as $row):
            $n++;
        ?>
        <tr>
        <td class="td_middle"><?php echo $n; ?></td>
        <td>   
            <div class="myttl"><?php
            $dept_array=section_hierarchy($row['section_id']);
            echo $dept_array['d']." - ".$dept_array['c']." - ".$dept_array['s'];?></div>
             <div><strong><?php echo get_phrase('academic_year');?>: </strong><?php echo $row['acadmic_year_name'];?></div>
              <div><strong><?php echo get_phrase('class_to_be_promoted_in');?>: </strong><?php 
              $dept_array2=section_hierarchy($row['pro_section_id']);
                echo $dept_array2['d']." - ".$dept_array2['c']." - ".$dept_array2['s'];?></div>
              <div><strong><?php echo get_phrase('academic_year_to_be_promoted_in');?>:</strong><?php echo $row['pro_acadmic_year_name'];?> </div>
              <?php
                if(isset($array_status[$row['bulk_req_id']])){
                    $Total=array_sum($array_status[$row['bulk_req_id']]);
                    echo get_phrase("total_forms").": ".$Total;
                    $paid=0;
                    if(isset($array_status[$row['bulk_req_id']][5])){
                    echo get_phrase("paid").": ".$paid=$array_status[$row['bulk_req_id']][5];	
                    }
                    echo get_phrase("un_paid").": ".($Total-$array_status[$row['bulk_req_id']][5]);
                    }
                    
                    if(($row['activity']<5) && ($paid==$Total) )
                    {
                    $activity['activity']=5;
                    $this->db->where("bulk_req_id",$row['bulk_req_id']);
                    $this->db->update(get_school_db().".bulk_request",$activity);	
                    }
                    echo "<br>";
                    //echo $row['activity'];
                    $color="";
                    if($row['activity']==8)
                    {
                    	$color="green";
                    }
                    ?>

                        <div ><strong><?php echo get_phrase('status');?>: </strong><span class="<?php echo $color;?>"><?php echo promotion_class_status($row['activity']);
                                      ?></span></div>
                        <div><strong><?php echo get_phrase('submit_date');?>: </strong><?php
                        //echo $row['date_time'];
                        $date=explode(' ',$row['date_time']);
                        echo convert_date($date[0]);
                        ?></div>
                    </td>
                    <td class="td_middle">
                        <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                         
                         <!-- STUDENT promote LINK -->
                         <?php if($row['activity']<8){?>
                        <li>
                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/promote_class/<?php echo $row['bulk_req_id']; ?>');">
                                <i class="entypo-pencil"></i>
                                <?php echo get_phrase('manage_promotion');?>
                            </a>
                        </li>
                        <?php } ?>
                        <!--<li>-->
                            <a href="<?php //echo base_url(); ?>promotion/view_detail_listing/<?php //echo $row['bulk_req_id']; ?>" class="">
                        <!--        <i class="entypo-pencil"></i>-->
                                <?php //echo get_phrase('view_detail_listing');?>
                        <!--    </a> -->
                        <!--</li>-->
                        <?php if($row['activity']==4 && $paid!=$Total){?>
                        <li>
                            <a href="<?php echo base_url(); ?>promotion/view_print_chalan_class/<?php echo $row['bulk_req_id']; ?>" class="">
                                <i class="entypo-pencil"></i>
                                <?php echo get_phrase('print_chalan_form');?>
                            </a> 
                        </li>
                            <?php } ?>
                            <?php
                                if($row['activity']>=5 || $paid>0)
                                {
                                }
                                else
                                {
                            ?>
                            <li>
                                <a href="#" class="" onclick="confirm_modal('<?php echo base_url(); ?>promotion/cancel_request/<?php echo $row['bulk_req_id']; ?>');" >
                                <i class="entypo-pencil"></i>
                                <?php echo get_phrase('cancel_promotion_request');?>
                                </a> 
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </td>

            <?php 
                if($row['account_status']==0){
                	$account_status=1;
                }else{
                	$account_status=0;
                }
            ?>
        </tr>
        <?php endforeach;?>
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
    
    // var datatable_btn  = "<a id='select' class='modal_open_btn'><?php echo get_phrase('promote');?></a>";
    // $(".dataTables_filter label").after(datatable_btn);
    
</script>


