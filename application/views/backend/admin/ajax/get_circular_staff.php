<?php
$date_query="";
$staff_id = $_POST['staff_id'];
$start_date='';
$end_date='';
$start_date=$_POST['start_date'];
$end_date=$_POST['end_date'];
$staff_general=$_POST['staff_general'];
if($start_date!='')
{
	$start_date_arr=explode("/",$start_date);
	$start_date=$start_date_arr[2].'-'.$start_date_arr[1].'-'.		$start_date_arr[0];
}
if($end_date!='')
{
	$end_date_arr=explode("/",$end_date);
	$end_date=$end_date_arr[2].'-'.$end_date_arr[1].'-'.$end_date_arr[0];
}
if($start_date!='')
{
	$date_query=" AND circular_date >= '".$start_date."'";
}
if($end_date!='')
{
	$date_query=" AND circular_date <= '".$end_date."'";
}
if($start_date!='' && $end_date!='')
{
	$date_query=" AND circular_date >= '".$start_date."' AND circular_date <= '".$end_date."' ";
}


$staff_query="";
if(isset($staff_id) && ($staff_id>0))
{
	$staff_query=" AND st.staff_id=$staff_id";
}

if(isset($staff_general) && ($staff_general=="show"))
{
	$staff_query=" AND cs.staff_id=0";
}

if(isset($staff_general) && ($staff_general=='hide'))
{
	$staff_query=" AND cs.staff_id != 0";
}

if((isset($staff_id) && ($staff_id > 0)) && (isset($staff_general) && ($staff_general== "hide")))
{
	$staff_query = " AND (st.staff_id=$staff_id OR cs.staff_id != 0)";
}

if((isset($staff_id) && ($staff_id > 0)) && (isset($staff_general) && ($staff_general== "show")))	
{
	$staff_query = " AND (st.staff_id=$staff_id OR cs.staff_id=0)";
}
 
$q="SELECT cs.*,st.name as staff_name,st.employee_code as employee_code,d.title as designation
 FROM ".get_school_db().".circular_staff cs
left JOIN ".get_school_db().".staff st
ON cs.staff_id=st.staff_id
left JOIN ".get_school_db().".designation d
On st.designation_id=d.designation_id
  WHERE cs.school_id=".$_SESSION['school_id'] . $date_query.$staff_query." order by cs.circular_date desc ";

$query= $this->db->query($q)->result_array();
?>
    <table class="table table-striped table-bordered table_export" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>
                    <div><?php echo get_phrase('s_no');?></div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('circular_detail');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('options');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1;foreach($query as $row)
            {?>
            <tr>
                <td class="td_middle">
                    <?php echo $count++;?>
                </td>
                <td>
                    <?php echo "<span class='myttl'>".$row['circular_title'];
							echo "</span> <br/>"; 
							?>
                    <?php $date=$row['circular_date'];
				echo "<strong>".get_phrase('date').":</strong> ".date_view($date);		
						?>
                    
                    <br>
                    
                    <?php			
                if($row['staff_id']!="" && $row['staff_id'] > 0)
					{
						echo "<strong>".get_phrase('staff_name').":</strong> ".$row['staff_name'].' ('.$row['designation'].') '.'Emp Code:'.$row['employee_code'];
			            echo "<br/>";
					}
                        echo "<strong>".get_phrase('detail').": </strong> <br><em>".$row['circular'];
                
                if($row['attachment']=="")
    {?>
                        <?php }
	else
	{
		 echo "</em><br/> <strong>".get_phrase('attachment').":</strong> ";
		
		?>
                        <a target="_blank" href="<?php echo display_link($row['attachment'],'circular_staff');?>"><span class="glyphicon glyphicon-download-alt"></span></a>
    <?php 
    }	
    ?>
                </td>
                <td class="td_middle">
                    
                        <?php if (right_granted(array('circulars_delete', 'circulars_manage'))){?>
                        <div class="btn-group" data-step="4" data-position="left" data-intro="staff circulars options: edit / delete">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <?php echo get_phrase('action');?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <!-- EDITING LINK -->
                                <?php if (right_granted('circulars_manage'))
                                {?>
                                <li>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_staff_circular/edit/<?php echo str_encode($row['circular_staff_id']);?>');">
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('edit');?>
                                    </a>
                                </li>
                                <?php }?>
                                <?php if (right_granted('circulars_delete')){?>
                                <li class="divider"></li>
                                <!-- DELETION LINK -->
                                <li>
                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>circular_staff/circulars_staff/delete/<?php echo str_encode($row['circular_staff_id']);?>/<?php echo $row['attachment'];?>');">
                                        <i class="entypo-trash"></i>
                                        <?php echo get_phrase('delete');?>
                                    </a>
                                </li>
                                <?php }?>
                            </ul>
                        </div>
                        <?php }?>
                </td>
            </tr>
            <?php }?>
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
    
    <?php if (right_granted('circulars_manage')){?>
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_staff_circular/create")';
        var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='2' data-position='left' data-intro='Press this button to add staff circulars' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('staff_circular');?></a>"; 
        $(".dataTables_filter label").after(datatable_btn);
    <?php } ?>
    
</script>