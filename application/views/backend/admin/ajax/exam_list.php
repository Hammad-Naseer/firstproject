<?php
if (right_granted(array('examlist_manage')))
{
$status = '';
if ( $term_id) {
	$status = get_term_status( $term_id );
	$query = "SELECT e.* ,y.title as term, y.status FROM " . get_school_db() . ".exam e INNER JOIN " . get_school_db() . ".yearly_terms y ON y.yearly_terms_id = e.yearly_terms_id where e.school_id=" . $_SESSION[ 'school_id' ] . " and y.yearly_terms_id=" . $term_id . "";
}

else {
	$query = "select e.*,y.title as term,a.title as year, y.status from " . get_school_db() . ".exam e inner join " . get_school_db() . ".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id inner join " . get_school_db() . ".acadmic_year a on a.academic_year_id=y.academic_year_id where e.school_id=" . $_SESSION[ 'school_id' ] . "";
}

$exams = $this->db->query( $query )->result_array();

?>
    <table class="table table-bordered table_export" data-step="3" data-position="top" data-intro="exam record">
        <thead>
            <tr>
                <th style="width:44px;">
                    <div>
                        <?php echo get_phrase('s_no');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('exams_detail');?>
                    </div>
                </th>
                <th style="width:94;">
                    <div>
                        <?php echo get_phrase('options');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php 
			$a=0;			
	foreach($exams as $row):
				$a++;		
						
						
$status=$row['status'];?>
            <tr>
                <td class="td_middle">
                    <?Php echo $a;?>
                </td>
                <td>
                    <div class="myttl">
                        <?php echo $row['name'];?>
                    </div>
                    <div><strong><?php echo get_phrase('start');?>/<?php echo get_phrase('end_date');?>: </strong>
                        <?php echo convert_date($row['start_date']);?> /
                        <?php echo convert_date($row['end_date']);?>
                    </div>
                    <div><strong><?php echo get_phrase('term');?>: </strong>
                        <?php echo $row['term'];?> </div>
                    <div><strong><?php echo get_phrase('comment');?>: </strong>
                        <?php echo $row['comment'];?>
                    </div>
                </td>
                <td class="td_middle">
                    <?php if(($status!=1)){?>
                    
                    <div class="btn-group" data-step="4" data-position="left" data-intro="exam options: edit / delete">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <?php echo get_phrase('action');?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <!-- EDITING LINK -->
                            
                            <li>
                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_exam/<?php echo $row['exam_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('edit');?>
                                </a>
                            </li>
                            
                            
                            <li class="divider"></li>
                            <!-- DELETION LINK -->
                            <li>
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>exams/exam/delete/<?php echo $row['exam_id'];?>');">
                                    <i class="entypo-trash"></i>
                                    <?php echo get_phrase('delete');?>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                    <?php }?>
                </td>
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
    
    
    <?php if (right_granted('examlist_manage')){?>
        var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_edit_exams")';
        var datatable_btn     = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='2' data-position='left' data-intro='Press this button to add new exam' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_exam');?></a>"; 
        $(".dataTables_filter label").after(datatable_btn);
    <?php } ?>
    
    
    
</script>
<?php
}
?>