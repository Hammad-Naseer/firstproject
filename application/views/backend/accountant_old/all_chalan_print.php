<?php  
        if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<script>
	
	
	$( window ).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
    });
	
	
	
	
</script>
       
       <form action="<?php echo base_url(); ?>accountant/batch_print" method="post" >
       	
       	     	<input type="hidden" value="<?php echo $this->uri->segment(3); ?>" name="class_id"/>
       	     	
       	<input type="hidden" value="<?php echo $this->uri->segment(4); ?>" name="month"/>
       	<input type="hidden" value="<?php echo $this->uri->segment(5); ?>"  name="year"/>
       	
       	<button class="entypo-plus-circled btn btn-primary pull-right">print</button>
       </form>         
                
         <form action="<?php echo base_url(); ?>accountant/batch_delete" method="post" >
       	
       	     	<input type="hidden" value="<?php echo $this->uri->segment(3); ?>" name="class_id"/>
       	     	
       	<input type="hidden" value="<?php echo $this->uri->segment(4); ?>" name="month"/>
       	<input type="hidden" value="<?php echo $this->uri->segment(5); ?>"  name="year"/>
       	
       	<button class="entypo-plus-circled btn btn-primary pull-right">Delete all</button>
       </form>             
                
                <br /><br />
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

        
      $class_id= $this->uri->segment(3);
	  $month= $this->uri->segment(4);
	   $year= $this->uri->segment(5);

$this->db->select('*');
$this->db->from('chalan');
$this->db->join('student', 'student.student_id = chalan.student_id');

$this->db->where(array('student.class_id' => $class_id));
$this->db->where(array('chalan.chalan_month' => $month));
$this->db->where(array('chalan.chalan_year' => $year));



$teachers	=	$this->db->get()->result_array();

$count=1;
foreach($teachers as $row): ?>
                        <tr>
<td><?php echo $count; $count++; ?> </td>
<td><?php echo $row['name']; ?> <?php echo $row['chalan_id'];?> </td>
<td><?php echo $row['roll']; ?></td>
<td><?php echo $row['date']; ?></td>

<td style="color: green;"><?php $row['email'];
                               
if($row['status']==0){
echo '<button type="button" class="btn glyphicon-envelope">Pending</button>';
}else if($row['status']==1){
echo 'Payed';
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
<a href="<?php echo base_url(); ?>accountant/edit_chalan/<?php echo $class_id.'/'.$month.'/'.$year.'/'.$row['chalan_id']; ?>">
                                            	
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

