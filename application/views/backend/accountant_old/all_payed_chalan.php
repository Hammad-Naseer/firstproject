
       
                
                
                
                
                <br><br>
                
                
             
             
             
           <a style="margin-right: 10px;" href=""
    class="btn btn-primary pull-right"
    data-toggle="modal" data-target="#print_chalan"
     >
        <i class="entypo-plus-circled"></i>
        <?php echo get_phrase('Bulk_Print');?>
    </a> 
     
     
     
     
     
  <!-- Modal -->
  <div class="modal fade" id="print_chalan" role="dialog">
<div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        
        
   
        
        
        
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
       
       <form action="<?php echo base_url(); ?>accountant/batch_print" method="post">
       
       <div class="col-md-12">
       <input type="hidden" name="class_id" value="<?php echo $page_data['chalan1']= $this->uri->segment(3); ?>">
       
       	<div class="col-md-4">
<select  name="month" class="form-control">
<option value="">Select Month</option>
<option value="January">January</option>
<option value="February">February</option>
<option value="March">March</option>
<option value="April">April </option>
<option value="May">May</option>
<option value="June">June</option>
<option value="July">July</option>
<option value="August">August </option>
<option value="September">September</option>
<option value="October">October </option>
<option value="November">November</option>
<option value="December">December</option>
  </select>
 </div>
       	<div class="col-md-4">
       		<select name="year"  class="form-control">
       		<option >Select Year</option>
       			<option value="2016">2016</option>
       			<option value="2017">2017</option>
       			<option value="2018">2018</option>
       			<option value="2019">2019</option>
       			<option value="2020">2020</option>
       			<option value="2021">2021</option>
       			<option value="2022">2022</option>
       		</select>
       	</div>
       	<div class="col-md-4">
       		<button type="submit" class="btn btn-default">Print</button>
       </div>
       </div>
       </form>
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
     </div>
  </div>     

<table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
<th width="80"><div><?php echo get_phrase('Sr #');?></div></th>
<th><div><?php echo get_phrase('name');?></div></th>
<th><div><?php echo get_phrase('Roll #');?></div></th>
<th><div><?php echo get_phrase('Date');?></div></th>

<th><div><?php echo get_phrase('Status');?></div></th><th><div><?php echo get_phrase('options');?></div></th>
</tr>
</thead>
<tbody>
<?php 
$this->db->select('*');
$this->db->from('chalan');
$this->db->join('student', 'student.student_id = chalan.student_id');
$this->db->where(array('chalan.status' => 1));
$teachers	=	$this->db->get()->result_array();

$count=1;
foreach($teachers as $row): ?>
                        <tr>
<td><?php echo $count; $count++; ?> </td>
<td><?php echo $row['name']; ?> <?php echo $row['chalan_id'];?> </td>
<td><?php echo $row['roll']; ?></td>
<td><?php echo $row['chalan_month']; ?> / <?php echo $row['chalan_year']; ?></td>

<td style="color: green;"><?php $row['email'];
                               
if($row['status']==0){
echo '<button type="button" class="btn glyphicon-envelope">Pending</button>';
}else if($row['status']==1){
echo 'Paid';
}
else if($row['status']==2){
echo '<button type="button" class="btn btn-default">Rejected</button>';
}

?></td>
<td>
                                
<div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action<span class="caret"></span>
</button>
<ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
<!-- teacher EDITING LINK -->
<li>

<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_teacher_edit/<?php echo $row['chalan_id'];?>');">
                                            	
<i class="entypo-pencil"></i>
													<?php echo get_phrase('edit');?></a>
        </li>
<li>

<a href="<?php echo base_url(); ?>accountant/student_chalan/<?php echo $row['chalan_id'];?>">
                                            	
<i class="entypo-pencil">
	
</i>

<?php echo get_phrase('Print Chalan');?>
											
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
		

		var datatable = $("#table_export").dataTable({"sPaginationType": "bootstrap","sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
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

