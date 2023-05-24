            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_librarian_add/');" 
            	class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
            	<?php echo get_phrase('add_new_librarian');?>
                </a> 
                <br><br>
               <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $library	=	$this->db->get('library')->result_array();
                                foreach($library as $row):?>
                        <tr>
                            <td><img src="<?php echo $this->crud_model->get_image_url('librarian',$row['library_id']);?>" class="img-circle" width="30" /></td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['email'];?></td>
                            <td>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <?php echo get_phrase('action'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                        <!-- teacher EDITING LINK -->
                                        <li>
                                        	<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_librarian_edit/<?php echo $row['library_id'];?>');">
                                            	<i class="entypo-pencil"></i>
													<?php echo get_phrase('edit');?>
                                               	</a>
                                        				</li>
                                        <li class="divider"></li>
                                        
                                        <!-- teacher DELETION LINK -->
                                        <li>
                                        	<a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/librarian/delete/<?php echo $row['library_id'];?>');">
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
		 <div>
                    <h3 class="warning">
                    <?php echo get_phrase('disclaimer_note'); ?>

                    
                    :</h3>
                    <span>
                    
          <?php echo get_phrase('fine_per_day_on_returning_the_book_after_the_due_date_is_rs'); ?>

                    .30</span>
                </div>


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
<script>

$('#email').change(function(){
	
	get_email();
	
});


function get_email(){
	
	$('#email').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
	
	var email=$('#email').val();
		$.ajax({
      type: 'POST',
       data: {email:email},
      url: "<?php echo base_url();?>admin/call_function",
      dataType: "html",
      success: function(response) { 
if(response=='yes'){

$("#btn1").attr('disabled','true');
$("#email").css('border','1px solid red');
$("#icon").remove();

if($('#message').html()==undefined){
$("#email").before('<p id="message" style="color:red;"><?php echo get_phrase('email_address_is_already_exist'); ?></p>');	
}
}else{
	
$("#btn1").removeAttr('disabled');
$("#email").css('border','1px solid green');
$("#icon").remove();	
$("#message").remove();	
}      
}
});
}
</script>
<style>
	.glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -webkit-animation: spin2 .7s infinite linear;
}

@-webkit-keyframes spin2 {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
</style>