
           <!-- <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_teacher_add/');" 
            
            	class="btn btn-primary pull-right">
              
            	<!--<?php echo get_phrase('add_new_teacher');?>-->
                </a> 
                <br><br>
               <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('Sr #');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th><div><?php echo get_phrase('Date');?></div></th>
                                 <th><div><?php echo get_phrase('Chalan Status');?></div></th>
                            <th><div><?php echo get_phrase('Student status');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $this->db->select('*');
$this->db->from('chalan');
$this->db->join('student', 'student.student_id = chalan.student_id');
$this->db->where(array('chalan.status' => 2));
$this->db->or_where(array('chalan.status' => 4));
 
$this->db->where(array('student.student_status'=> 0));
 $teachers	=	$this->db->get()->result_array();


$count=1;
                                foreach($teachers as $row): ?>
                        <tr>
                            <td><?php echo $count; $count++; ?> </td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['date'];?></td>
                               <td><?php 
                               
                               
                               
                               
                               
                               
                               
                               
                               if($row['status']==2){
		echo '<button type="button" class="btn glyphicon-envelope" onclick(showAjaxModal('. echo base_url(); .'))></button>Pending</button>';
							  
							  
							  
							  
							   }else if($row['status']==3){
							   	
							   	
							   	echo '<button type="button" onclick="" class="btn btn-default">Approved</button>';
							   }
                               ?></td>
                                <td><?php 
                               
                               if($row['status']==2){
							   	echo '<button type="button" class="btn glyphicon-envelope">Pending</button>';
							   }else if($row['status']==3){
		echo '<button type="button" class="btn btn-default">Click  To Approved</button>';
							   }
                               
                               
                               
                               
                               
                               ?></td>
                            <td>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                        <!-- teacher EDITING LINK -->
                                        <li>
                                        	<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_teacher_edit/<?php echo $row['chalan_id'];?>');">
                                            	<i class="entypo-pencil"></i>
													<?php echo get_phrase('edit');?>
                                               	</a>
                                        				</li>
                                        <li class="divider"></li>
                                        
                                        <!-- teacher DELETION LINK -->
                                        <li>
                                        	<a href="#" onclick="confirm_modal('<?php echo base_url();?>accountant/get_pending_chalan/delete/<?php echo $row['chalan_id'];?>');">
                                            	<i class="entypo-trash"></i>
													<?php echo get_phrase('delete');?>
                                               	</a>
                                        				</li>
                                    </ul>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
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

