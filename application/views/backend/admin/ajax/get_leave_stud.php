<?php
$section_id = $_POST['section_id'];

$leave_categ_id = $_POST['leave_categ_id'];

$section_query="";
$class_query="";

$date_query="";
$start_date='';
$end_date='';
$start_date=date_slash($_POST['start_date']);
$end_date=date_slash($_POST['end_date']);

if($start_date!='')
{
	$date_query=" AND ls.start_date >= '".$start_date."'";
}
if($end_date!='')
{
	$date_query=" AND ls.end_date <= '".$end_date."'";
}

if($start_date!='' && $end_date!='')
{
	$date_query=" AND ls.start_date >= '".$start_date."' AND ls.end_date <= '".$end_date."' ";
}


if(isset($section_id) && ($section_id>0))
{
	$section_query = " AND cs.section_id=$section_id";
}

if(isset($leave_categ_id) && $leave_categ_id > 0)
	{
		$leave_query = " AND lc.leave_category_id = $leave_categ_id";	
	}
$school_id=$_SESSION['school_id'];
$q="SELECT ls.*,d.departments_id as departments_id,c.class_id as class_id, cs.section_id as section_id,lc.name as leave_categ_name, s.name as stud_name,d.title as dept_name,cs.title as section_name,c.name as class_name
    FROM ".get_school_db().".leave_student ls INNER join ".get_school_db().".student s ON ls.student_id=s.student_id INNER join ".get_school_db().".class_section cs
    ON s.section_id=cs.section_id Inner JOIN ".get_school_db().".class c On cs.class_id=c.class_id Inner join ".get_school_db().".departments d
    On d.departments_id=c.departments_id LEFT join ".get_school_db().".leave_category lc On ls.leave_category_id=lc.leave_category_id
    WHERE ls.school_id=".$_SESSION['school_id']. $date_query . $section_query. $leave_query. "  ORDER BY ls.request_id desc ";

$leaves=$this->db->query($q)->result_array();

?>
  <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export" data-step="3" data-position="top" data-intro="leave record">
                	<thead>
                		<tr>
                    		<th style="width:34px;"><div><?php echo get_phrase('s_no');?></div></th>
                    		<th><div><?php echo get_phrase('leave_detail');?></div></th>
                     
                            
                     <th style="width:94px;"><div><?php echo get_phrase('action');?></div></th>
                        </tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($leaves as $row):?>
                        <tr>
                          <td class="td_middle"> <?php echo $count++;?></td>
                  
                          <td> 

                              <div class="myttl"><?php
                            echo $row['stud_name'];?> <span style="font-size:12px;"> ( <?php echo $row['leave_categ_name'];?>)</span> </div>
                        
                            <div><strong><?php echo get_phrase('leave_start');?> / <?php echo get_phrase('end_date');?>:</strong> <?php $start_date= $row['start_date'];
							$d=explode("-",$start_date);
							echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
							 ?> / <?php 
                            $end_date=$row['end_date'];
                            $d=explode("-",$end_date);
							echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                             ?>
                             </div>
                        
                            <?php if($row['approved_upto_date'] != "" && $row['final_end_date'] != ""): ?>
                            <div>
                                <strong class="text-danger">
                                    <?php echo get_phrase('actual_start_date');?> / <?php echo get_phrase('actual_end_date');?>:
                                </strong> 
                            <?php 
                                $start_date= $row['approved_upto_date'];
							    $d=explode("-",$start_date);
							    echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
							 ?> 
							  /  
							 <?php 
                                $end_date=$row['final_end_date'];
                                $d=explode("-",$end_date);
							    echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                             ?>
                            </div>
                            <?php endif; ?> 
                             
                            <div>
                                          
                                   <strong> 	<?php echo get_phrase('department');?>/<?php echo get_phrase('class');?>/<?php echo get_phrase('section');?>: </strong>         
                               <ul class="breadcrumb breadcrumb2">
                            
                               	<li><?php echo $row['dept_name']; ?></li>
                               	<li><?php echo $row['class_name']; ?></li>
                               	<li><?php echo $row['section_name']; ?></li>
                             
                               	
                               </ul>           
                       </div>
                        
                            <div><strong><?php echo get_phrase('reason');?>: </strong><?php echo $row['reason'];
                            if($row['proof_doc']!=""){ ?> <a href="<?php echo display_link($row['proof_doc'],'leaves_student');?>" target="_blank"><span class="glyphicon glyphicon-download-alt"></span></a> <?php }
                  			?></div>
                           
                           
                           
                           
                           
               
                            <div><strong><?php echo get_phrase('request_date');?>:</strong> <?php $request_date=$row['request_date'];
                            $d=explode("-",$request_date);
							echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));
                             ?></div>
                             
                             
                             
                             
                            <div><strong> <?php echo get_phrase('process_date');?>: </strong><?php if($row['process_date']>0){ $process_date=$row['process_date'];
       $d=explode("-",$process_date);
echo date("d-M-Y",mktime(0,0,0,$d[1],$d[2],$d[0]));                     
                             } ?></td></div>

         
                         
        				</td>     
                             
                        <td class="td_middle">     
                           
                            <div class="btn-group" data-step="4" data-position="left" data-intro="leave record approve / reject / edit / delete options">
                                <?php if($row['status']==0){ ?>
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" >
                                    <?php echo get_phrase('action');?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                   
                                   <?php //if(in_array('manageleaves_approve',$package_rights)){?>
                                   <!-- APPROVE LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal_approve('<?php echo base_url();?>leave/manage_leaves_student/approve/<?php echo $row['request_id'];?>');">
                                            <i class="entypo-check"></i>
                                                <?php echo get_phrase('approve');?>
                                            </a>
                                    </li>
                                    <?php //}?>
                                    <?php //if(in_array('manageleaves_reject',$package_rights)){?>
                                    <li class="divider"></li>
                                    
                                    <!-- DENY LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal_deny('<?php echo base_url();?>leave/manage_leaves_student/reject/<?php echo $row['request_id'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('reject');?>
                                            </a>
                                    </li>
                                    <?php //}?>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_leave_student/<?php echo $row['request_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                   </li>
                                   
                                   <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>leave/manage_leaves_student/delete/<?php echo $row['request_id'];?>/<?php echo $row['proof_doc'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                   </li>
                                    
                                </ul>
                                <?php
                                } elseif($row['status']==1) { ?>
									<button type="button" class="btn btn-success" data-toggle="dropdown" ><?php echo get_phrase('approved');?></button>
                                <?php }
                               elseif($row['status']==2) { ?>
									<button type="button" class="btn btn-danger" data-toggle="dropdown" ><?php echo get_phrase('denied');?></button>
                                <?php }
                                
                                
                                 ?>
                            </div>
                            
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                
                      <script>
    	$(document).ready(function() {
    $('#leave_stud_tbl').DataTable(
    {
    	
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"bStateSave": true
	}
    );
    
    
} );

  $(".dataTables_wrapper select").select2({
            
            
            minimumResultsForSearch: -1
            
            
        });

    	
    </script>
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
    
    
    <?php if (right_granted('studentleaves_manage')){?>
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_leave_student/")';
        var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='3' data-position='left' data-intro='Press this button to add leave' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_leave');?></a>"; 
        $(".dataTables_filter label").after(datatable_btn);
    <?php } ?>
    
</script>