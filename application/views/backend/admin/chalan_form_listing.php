<?php 

$school_id=$_SESSION['school_id'];

$fee_type = $this->uri->segment(4);
$section_id = $this->uri->segment(5);


// if($param2=="add"){
// 	$title = 'Add Chalan Form';
// }else{

$edit_data=$this->db->get_where(get_school_db().'.class_chalan_form' , array('type' => $fee_type,'school_id'=>$school_id,'section_id'=>$section_id))->result_array();
//echo $this->db->last_query();
// echo "<pre>";
// print_r($edit_data);
// echo "</pre>";

// $title = 'Add Chalan Form';
// if($param3>0){
// 	$title = 'Edit Chalan Form';
// }

// }
$title = get_class_chalan_type($fee_type) ." Archive";
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title black2" style="width:100%">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase($title); ?>
            	</div>
            </div>
			<div class="panel-body" style="padding:5px 0px !important;">
                <div class="form-group well">
					<label class="control-label">
						<?php echo get_phrase('department');?>
						/
						<?php echo get_phrase('class');?>
                        /
                        <?php echo get_phrase('section');?>
					</label>
					<?php
					$section_hierarchy = section_hierarchy($section_id);?>
						<ul class="breadcrumb breadcrumb2">
							<li>
								<?php echo $section_hierarchy['d'];?>
							</li>
							<li><?php echo $section_hierarchy['c'];?></li>
							<li><?php echo $section_hierarchy['s'];?></li>


						</ul>
					</div>
				</div>
                <?php
                if (count($edit_data)>0)
                {
                ?>
                <table class="table table-bordered table-condensed table-hover" id="table">
                <thead>
                	<tr>
                	<th><?php echo get_phrase('title');?></th>
                	<th><?php echo get_phrase('version');?></th>
                	<th><?php echo get_phrase('archive_date');?></th>
                    <th><?php echo get_phrase('fee_details');?></th>
                    <th><?php echo get_phrase('discount_details');?></th>
                	</tr>
                </thead>
                <tbody>
                	<?php
                	foreach ($edit_data as $key => $value)
                	{
                	?>
                	<tr>
                		<td><?php echo $value['title'];?></td>
                		<td><?php echo $value['version'];?></td>
                		<td><?php echo date_view($value['archive_date']);?></td>
                        <td>
                        <?php
                        $fee_details = get_fee_type($value['c_c_f_id']);
                        foreach ($fee_details as $key1 => $value1)
                        {
                        ?>
                        <ul>
                            <li><?php echo $value1['title']." (".$value1['value'].")"?></li>
                        </ul>

                        <?php
                        }
                        ?>
                        </td>
                        <td>
                        <?php
                        $discount_details = get_discount_type($value['c_c_f_id']);
                        foreach ($discount_details as $key2 => $val2)
                        {
                        ?>
                        <ul>
                            <li><?php echo $val2['title']." (".$val2['value']."%)"?></li>
                        </ul>

                        <?php
                        }
                        ?>
                        </td>
                	</tr>
                	<?php
                	}
                	?>
                </tbody>
                </table>
                <?php
                }
                else
                {
                    echo "<p class='text-center'>".get_phrase('no_archive_found')."</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var table = $('#table').DataTable();
    });
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js"></script>