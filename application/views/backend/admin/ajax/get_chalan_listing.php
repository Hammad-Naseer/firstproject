<?php 

// $school_id = $_SESSION['school_id'];
// $acd_id    = "";
// if($academic_year!=""){
// 	$acd_id =" and ay.academic_year_id=$academic_year ";	
// }

// if($departments_id!='' AND $class_id==''  AND $section_id=='')
// {
//   $quer_where=" and  d.departments_id=$departments_id";
// }
// elseif($departments_id!='' AND $class_id!='' AND $section_id=='')
// {
//   $quer_where=" and  d.departments_id=$departments_id and cc.class_id=$class_id";
// }
// elseif($departments_id!='' AND $class_id!='' AND $section_id!='')
// {
//   $quer_where=" and  d.departments_id=$departments_id and cc.class_id=$class_id and cs.section_id=$section_id";
// }  

$quer  = "select * from ".get_school_db().".student_chalan_form where school_id=".$_SESSION['school_id']." and student_id=$student_id order by status desc";
$students=$this->db->query($quer)->result_array();
?>

<br /><br />
<table class="table table-bordered datatable" id="table_export">
        <thead>
            <tr>
                <th width="80"><div><?php echo get_phrase('form_no');?></div></th>
                <th width="80"><div><?php echo get_phrase('fee_month');?></div></th>
                <th width="80"><div><?php echo get_phrase('fee_year');?></div></th>
                <th><div><?php echo get_phrase('total_amount');?></div></th>
                <th><div><?php echo get_phrase('received_date');?></div></th>
                <th><div><?php echo get_phrase('paid_amount');?></div></th>
                <th><div><?php echo get_phrase('arrears');?></div></th>
                <th><div><?php echo get_phrase('form_type');?></div></th>
                <th><div><?php echo get_phrase('status');?></div></th>
                <th width="80"><div><?php echo get_phrase('options');?></div></th>
            </tr>
        </thead>
        <tbody>
        <?php 
        
        foreach($students as $row)
        {
            $total_fee = get_student_challan_fee_details_for_parent($row['student_id'],$row['s_c_f_month'], $row['s_c_f_year'], $row['s_c_f_id']); 
        ?>
         <tr>
            <td><?php echo $row['chalan_form_number']; ?></td>
            <td><?php echo $row['s_c_f_month']; ?></td>
            <td><?php echo $row['s_c_f_year'];  ?></td>
            <td><?php echo $total_fee; ?></td>            
            <td>
                <?php 
                if($row['received_date'] == '0000-00-00 00:00:00')
                {
                   echo get_phrase("not_received_yet");
                }
                else
                {
                   echo date('d-M-Y', strtotime($row['received_date'])); 
                }
               ?>
            </td>
            <td><?php echo $row['received_amount'];?></td>
            <td><?php echo $row['arrears'];?></td>
            <td><?php echo display_class_chalan_type($row['form_type']);?></td> 
            <td>
               <?php 
               echo monthly_class_status($row['status']);
               if($row['is_cancelled']==1) 
               { 
                   echo '<span class="red space">(Cancelled)</span>'; 
               }
               ?>
            </td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?><span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                    <li>
                       <a href="<?php echo base_url();?>class_chalan_form/edit_chalan_form/<?php echo $row['s_c_f_id'];?>/8" >
                           <i class="entypo-pencil"></i>
                           <?php echo get_phrase('view_detail');?>
                       </a>
                    </li> 
                    <!--<li class="divider"></li>-->
                    </ul>
                </div>
            </td>
        
        
        
          </tr>
                <?php } 
                
        ?>
        </tbody>
</table>



