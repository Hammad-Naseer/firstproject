
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


    <div class="row">
    
                <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
                    <h3 class="system_name inline">
                      <!--  <i class="entypo-right-circled carrow">
                        </i>-->
                       
                        <?php echo get_phrase('policy_categories');?>
                       
                    </h3>
                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_policy_category/');" 
    class="btn btn-primary pull-right">
    <i class="entypo-plus-circled"></i>
    <?php echo get_phrase('add_policy_category');?>
</a>
                </div>
        <div class="col-md-12">


		    


	<div class="tab-pane box active" >
		
<table class="table table-bordered datatable  table-hover  cursor" id="table_export">
 
        	<thead>
        		<tr>
            		<th style="width:20px"><div>#</div></th>
            		<th><div><?php echo get_phrase('title');?></div></th>
                    <th  style="width:80px"><div><?php echo get_phrase('options');?></div></th>
				</tr>
			</thead>
            <tbody>
            	<?php $count = 1;foreach($policy_categories as $row):?>
                <tr>
                    <td><?php echo $count++;?></td>
					<td><?php echo $row['title'];?></td>
					<td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                             <?php echo get_phrase('action');?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            
                            <!-- EDITING LINK -->
                            <li>
                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_policy_category/<?php echo $row['policy_category_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('edit');?>
                                    </a>
                            </li>
                            <li class="divider"></li>
                            
                            <!-- DELETION LINK -->
                            <li>
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>policies/policy_categories/delete/<?php echo $row['policy_category_id'];?>');">
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
	</div>
	</div></div>
	

<!--	<script>
	
$(document).ready(function() {
    $('#table_export2').DataTable();
} );	
</script>-->