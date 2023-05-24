<?php
/*$acad_year=$this->input->post('acad_year');
$yearly_terms=$this->input->post('yearly_terms');
$yearly_query="";
if(isset($yearly_terms) && $yearly_terms!="")
{
	$yearly_query = " AND y.yearly_terms_id = $yearly_terms";
}elseif(isset($acad_year) && $acad_year!="")
{
	$yearly_query = " AND y.academic_year_id = $acad_year";
}*/
    $q="SELECT * FROM ".get_school_db().".subject_category WHERE school_id=".$_SESSION['school_id'].""; 
    $categ=$this->db->query($q)->result_array();
?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover cursor" id="get_subj_category">
                	<thead>
                		<tr>
                    		<th style="width: 50px !important;"><div><?php echo get_phrase('s_no');?></div></th>
                    		<th><div><?php echo get_phrase('title');?></div></th>
                    		<th style="width: 34px !important;"><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($categ as $row):
                    	?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php 
							echo $row['title'];
							
							?>
							</td>
							
							
							<td>
							
							
                            <div class="btn-group">
                                
                               
                                    
                                    <!-- EDITING LINK -->
                                    
                                    
                                        <a href="#"  onclick="edit_func('<?php echo $row[subj_categ_id];?>','<?php echo $row['title'];?>');">
                                            <i class="entypo-pencil"></i>
                                                
                                            </a>
                                     
                                    
                                     
                                    
                                    
                                    <!-- DELETION LINK -->
                                    
                                   
                                        <a href="#" onclick="delete_func('<?php echo $row[subj_categ_id];?>')">
                                            <i class="entypo-trash"></i>
                                               
                                            </a>
                                   
                                   
                               
                            </div>
                          
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                
<style>
	
select{
	padding: 7px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
</style>