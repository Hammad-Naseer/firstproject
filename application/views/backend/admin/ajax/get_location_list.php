<?php
$loc_country = $this->input->post('loc_country');
$loc_province =$this->input->post('loc_province');
$loc_city =$this->input->post('loc_city');

$filter="";
if(isset($loc_country) && $loc_country!="")
{
	$filter .= " AND c.country_id = $loc_country";
}

if(isset($loc_province) && $loc_province!="")
{
	$filter .= " AND p.province_id = $loc_province";
}

if(isset($loc_city) && $loc_city!="")
{
	$filter .= " AND cty.city_id = $loc_city";
}
	$q="SELECT cl.location_id, cl.title,  cl.status, c.title as country, 
        	p.title as province, cty.title as city 
        	FROM ".get_school_db().".city_location cl 
        	INNER JOIN 
        	".get_system_db().".city cty
        	ON cl.city_id = cty.city_id
        	INNER JOIN ".get_system_db().".province p
        	ON cty.province_id = p.province_id
        	INNER JOIN ".get_system_db().".country c
        	ON p.country_id = c.country_id
        	WHERE cl.school_id=".$_SESSION['school_id'].$filter." 
        	
        "; // ORDER BY cl.location_id DESC 
	$notices=$this->db->query($q)->result_array();
?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable" id="admin_ajax_get_location_list">
	<thead>
		<tr>
			<th style="width:34px !important;"><?php echo get_phrase('s_no');?>		
		</th>
		<th>
			<?php echo get_phrase('location_detail');?>
		</th>
		<th style="width:94px;">
			<?php echo get_phrase('options');?>	
		</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1;
		foreach($notices as $row):
		?>
		<tr>
			<td>
				<?php echo $count++;?>
			</td>
			<td>
				<div class="myttl"> <?php echo $row['title'];?></div>
			
				<div> <strong><?php echo get_phrase('city');?>: </strong><?php echo $row['city'];?></div>
			<div>
				<strong><?php echo get_phrase('province');?>: </strong><?php echo $row['province'];?>
			</div>
			<div>
				<strong><?php echo get_phrase('country');?>: </strong><?php echo $row['country'];?>
			</div>
			<div><strong><?php echo get_phrase('status');?>: </strong>
			<?php 
				if ($row['status'] == 1)
					echo "<span style='color:green'>Active</span>";
				else
					echo "<span style='color:red'>Inactive</span>";
			?>
			</div>
			</td>
			<td>
			<?php 
			if (right_granted(array('locations_manage', 'locations_delete')))
			{
				?>
				<div class="btn-group" data-step="6" data-position='left' data-intro="if you want location record edit or delete then press this button you have the option edit/delete">
					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
					<?php echo get_phrase('action');?>
						
						<span class="caret">
						</span>
					</button>
					<ul class="dropdown-menu dropdown-default pull-right" role="menu">
						<?php 
                        if (right_granted('locations_manage'))
                        {
                   		 ?>
					
						<!-- EDITING LINK -->
						<li>
							<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_location/<?php echo $row['location_id'];?>');">
								<i class="entypo-pencil">
								</i>
								<?php echo get_phrase('edit');?>
							</a>
						</li>
						<?php 
						}
						
						if (right_granted('locations_delete'))
						{
							?>
						<li class="divider">
						</li>

						<!-- DELETION LINK -->
						<li>
							<a href="#" onclick="confirm_modal('<?php echo base_url();?>location/delete/<?php echo $row['location_id'];?>');">
								<i class="entypo-trash">
								</i>
								<?php echo get_phrase('delete');?>

							</a>
						</li>
						<?php 
						}
						?>
					</ul>
				</div>
				<?php 
				}
				?>	
			</td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>


<style>
	select
	{
		padding: 7px;
		border: 1px solid #ccc;
		border-radius: 5px;
	}
</style>





                      <script>
    	$(document).ready(function() {
    $('#admin_ajax_get_location_list').DataTable(
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
