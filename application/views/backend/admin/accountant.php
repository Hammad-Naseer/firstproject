
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_accountant_add/');" 
            	class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
            	<?php echo get_phrase('add_new_accountant');?>
                </a> 
                <br><br>
               <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
<th width="80"><div><?php echo get_phrase('photo');?></div></th>
<th><div><?php echo get_phrase('name');?></div></th>
<th><div><?php echo get_phrase('cnic');?></div></th>
<th><div><?php echo get_phrase('contact#');?></div></th>
<th><div><?php echo get_phrase('email');?></div></th>
<th><div><?php echo get_phrase('options');?></div></th>
<th><div><?php echo get_phrase('status');?></div></th>
</tr>
</thead>
<tbody>
<?php 
$this->db->where('school_id',$_SESSION['school_id']);
$principal=$this->db->get(get_school_db().'.accountant')->result_array();
foreach($principal as $row):
?>
<tr>
<td><img src="<?php echo base_url().'uploads/principal_image/'.$row['image'];?>" class="img-circle" width="30" /></td>

<td><?php echo $row['name'];?></td>
<td><?php echo $row['cnic'];?></td>
<td><?php echo $row['phone'];?></td>
<td><?php echo $row['email'];?></td>
                            <td>
<div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                        <!-- teacher EDITING LINK -->
                                        <li>
                                        	<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_accountant_edit/	<?php echo $row['accountant_id'];?>');">
                                            	<i class="entypo-pencil"></i>
													<?php echo get_phrase('edit');?>
                                               	</a>
                                        				</li>
                                        <li class="divider"></li>
                                        
                                        <!-- teacher DELETION LINK -->
                                        <li>
                                        	<a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/accountant/delete/<?php echo $row['accountant_id'].'/'.$row['image'];?>');">
                                            	<i class="entypo-trash"></i>
													<?php echo get_phrase('delete');?>
                                               	</a>
                                        				</li>
                                    </ul>
                                </div>
                                
                            </td>


<?php if($row['account_status']==0){
	
	
	
	$account_status=1;
	
}else{
	$account_status=0;
}
?>
<td><a class="
<?php 
if($row['account_status']==0){
	echo 'btn btn-primary';
}else{
	echo 'btn btn-danger';
}

?>" href="<?php echo base_url(); ?>admin/change_status/<?php echo $account_status.'/accountant_id/'.$row['accountant_id'].'/accountant/' ?>"><?php 
if($row['account_status']==0){
	echo get_phrase('active');
}else{
	 echo get_phrase('inactive');
}

?></a></td>


                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ----->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [1,2]
					},
					{
						"sExtends": "pdf",
						"mColumns": [1,2]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(0, false);
							datatable.fnSetColumnVis(3, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(0, true);
									  datatable.fnSetColumnVis(3, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>

