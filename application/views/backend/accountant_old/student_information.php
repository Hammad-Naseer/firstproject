<style>
	.boarder{
		
		  
    border: 1px solid #f2f2f2;
  height: 34px;
		
	}
.modal-backdrop {
   
   z-index: 0 !important;
}
</style>

<?php  
        if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
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

<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/student_add/');" 
    class="btn btn-primary pull-right">
        <i class="entypo-plus-circled"></i>
        <?php echo get_phrase('add_new_student');?>
    </a> 
    
   <a style="margin-right: 10px;" href="<?php echo base_url(); ?>accountant/batch_form/<?php echo $page_data['chalan1']= $this->uri->segment(3); ?>"
    class="btn btn-primary pull-right">
        <i class="entypo-plus-circled"></i>
        <?php echo get_phrase('Monthly_chalan');?>
    </a> 

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
          <h4 class="modal-title">Bulk Print</h4>
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
  
     
     
     
     
     
     
     
     
     
    
<br><br>
<table class="table table-bordered datatable" id="table_export">
    <thead>
        <tr>
            <th width="80"><div><?php echo get_phrase('roll');?></div></th>
            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
            <th><div><?php echo get_phrase('name');?></div></th>
            <th class="span3"><div><?php echo get_phrase('address');?></div></th>
            <th><div><?php echo get_phrase('email');?></div></th>
            <th><div><?php echo get_phrase('options');?></div></th>
        </tr>
    </thead>
    <tbody>
        <?php 
                $students	=	$this->db->get_where('student' , array('class_id'=>$class_id))->result_array();
                foreach($students as $row):?>
        <tr>
            <td><?php echo $row['roll'];?></td>
            <td><img src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" class="img-circle" width="30" /></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['address'];?></td>
            <td><?php echo $row['email'];?></td>
            <td>
                
                <div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        
                        <!-- STUDENT PROFILE LINK -->
                        <li>
                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_student_profile/<?php echo $row['student_id'];?>');">
                                <i class="entypo-user"></i>
                                    <?php echo get_phrase('profile');?>
                                </a>
                                        </li>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                        
                        <!-- STUDENT EDITING LINK -->
                        <li>
                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_student_edit/<?php echo $row['student_id'];?>');">
                                <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('edit');?>
                                </a>
                                        </li>
                                        
                                        
                                         <li>
                            <a href="<?php echo base_url(); ?>accountant/chalan/<?php echo $row['student_id'];?>" >
                                <i class="entypo-pencil"></i>
<?php echo get_phrase('Print Chalan');?>
                                </a>
                                        </li>
                                        
                        <li class="divider"></li>
                        
                        <!-- STUDENT DELETION LINK -->
                        <li>
                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>accountant/student/<?php echo $class_id;?>/delete/<?php echo $row['student_id'];?>');">
                                <i class="entypo-trash"></i>
                                    <?php echo get_phrase('delete');?>
                                </a>
                                        </li>
                    </ul>
                </div>
                
            </td>
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
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
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