<?php
    $controller=$this->uri->segment(3);
    $section_id=$this->uri->segment(5);
    $student_status=$this->uri->segment(6);
    
    $acad_year = $this->db->query( "SELECT 
    	ay.title as academic_year
    	FROM " . get_school_db() . ".acadmic_year ay
    	INNER JOIN " . get_school_db() . ".student s 
    	ON ay.academic_year_id = s.academic_year_id
    	WHERE ay.school_id=" . $_SESSION[ 'school_id' ] . " 
    	AND s.academic_year_id =" . intval( $rows[ 0 ][ 'academic_year_id' ] ) )->result_array();
    $controller_val="";	
    if($controller=='c')
    {
    	$controller_val="c_student/student_pending/".$section_id."/".$student_status;
    }
    elseif($controller=='s')
    {
    	$controller_val="c_student/student_information/".$section_id;
    }


    $dep_arr = $this->db->query( "SELECT 
	d.title as department 
	FROM " . get_school_db() . ".departments d
	INNER JOIN " . get_school_db() . ".class c 
	ON d.departments_id = c.departments_id
	WHERE c.school_id=" . $_SESSION[ 'school_id' ] . " 
	AND c.class_id =" . intval( $rows[ 0 ][ 'section_id' ] ) )->result_array();

?>



<?php
    $loc_arr = $this->db->query( "select 
	cl.title as location,c.title as city, p.title as province, cntry.title as country 
	FROM " . get_school_db() . ".city_location cl
	INNER JOIN " . get_system_db() . ".city c 
	ON cl.city_id = c.city_id
	INNER JOIN " . get_system_db() . ".province p 
	ON p.province_id = c.province_id
	INNER JOIN " . get_system_db() . ".country cntry 
	ON cntry.country_id = p.country_id
	
	WHERE cl.school_id=" . $_SESSION[ 'school_id' ] . " 
	AND cl.location_id =" . intval( $rows[ 0 ][ 'location_id' ] ) )->result_array();

?>

<style>
    .head{
        font-weight: bold;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <!--<a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">-->
        <!--    <b><i class="fas fa-info-circle"></i> Watch Tutorial</b>-->
        <!--</a>-->
        <h3 class="system_name inline">
               <?php echo get_phrase('view_student_detail'); ?>
        </h3>
    </div>
</div>

<div class="panel" style="margin-top:2%;">
    
	<div class="panel-body">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<ul class="nav nav-tabs">
				<li><a data-toggle="tab" href="#Summery" class="active"> <?php echo get_phrase('basic_info');?></a>
				</li>

				<li><a data-toggle="tab" href="#parent_info" ><?php echo get_phrase('parent_info');?></a>
				</li>

				<li><a data-toggle="tab" href="#Contact"><?php echo get_phrase('contact_info');?></a>
				</li>

				<li><a data-toggle="tab" href="#offical_info"><?php echo get_phrase('offical_info');?></a>
				</li>

			</ul>
			<div class="tab-content">
			    
				<div id="Summery" class="tab-pane active">
					<div class="table-responsive panel">

						<table class="table table-hover ">
							<tbody>

								<tr>
									<th class="head" style="width:25%;"> <?php echo get_phrase('full_name');?></th>
									<td style="width:75%;"> <?php echo $rows[0]['name']; ?></td>
								</tr>


								<tr>
									<td class="head" ><?php echo get_phrase('department');?> / <?php echo get_phrase('class');?> / <?php echo get_phrase('section');?></td>
									<td>
										<?php 
											$har_val= section_hierarchy($rows[0]['section_id']);

											echo $har_val['d'].' / '.$har_val['c'].' / '.$har_val['s'];
										?>
									</td>
								</tr>
								<tr>
									<th class="head"> <?php echo get_phrase('date_of_birth');?></th>
									<td>
										<?php echo convert_date($rows[0]['birthday']);  ?>
									</td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('gender');?>
									</th>
									<td>
										<?php  
										if(($rows[0]['gender']=='f') || ($rows[0]['gender']=='female'))
										{
											echo "Female";
											
										 }else{
											
											echo "Male";
											
										 }  ?>
									</td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('religion');?>
									</th>
									<td>
										<?php echo religion($rows[0]['religion']); ?>
									</td>
								</tr>


								<tr>
									<th class="head">
                                    	<?php echo get_phrase('previous_school');?>
									</th>
									<td>
										<?php  
                                            echo $rows[0]['previou_school'];
                                        ?>
									</td>
								</tr>
								
								<tr>
									<th class="head">
                                    	<?php echo get_phrase('activities');?>
									</th>
									<td>
										<?php  
                                            echo $rows[0]['std_activities'];
                                        ?>
									</td>
								</tr>
								<tr>
									<th class="head">
                                    	<?php echo get_phrase('gr_number');?>
									</th>
									<td>
										<?php  
                                            echo $rows[0]['gr_no'];
                                        ?>
									</td>
								</tr>
								<tr>
									<th class="head">
                                    	<?php echo get_phrase('id_no');?>
									</th>
									<td>
										<?php  
                                            if($rows[0]['id_no']!=0){
                                                echo $rows[0]['id_no'];	
                                            }
                                        ?>
									</td>
								</tr>


								<tr>
									<th class="head">
									    <?php echo get_phrase('id_file');?>
										
									</th>
									<td>

										<?php if($rows[0]['id_file']==""){

                                            echo "No Attachment";
                                            
                                            }else{ ?>
										<a target="_blank" href="<?php echo base_url();?>sms/uploads/student_image/<?php echo $rows[0]['id_file']; ?>"><?php echo get_phrase('attachment');?></a>
										<?php } ?>


									</td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('blood_group');?>
									</th>
									<td>

										<?php echo $rows[0]['bd_group']; ?>


									</td>
								</tr>

								<tr>
									<th class="head">
										<?php echo get_phrase('disability(if any)');?>
									</th>
									<td>

										<?php echo $rows[0]['disability']; ?>


									</td>
								</tr>

								<tr>
									<th class="head">
										<?php echo get_phrase('student_category');?>
									</th>
									<td>

										<?php echo $rows[0]['category_title']; ?>


									</td>
								</tr>


								<tr>
									<th class="head"> <?php echo get_phrase('profile');?></th>
									<td>

											<span class="text-left">
												<?php     
                                                    $val_im=display_link($rows[0]['image'],'student',0,1); 
                                                    if($val_im!=""){
                                                    ?>
												<img src="<?php echo $val_im; ?>" class="img-responsive" style="height:114px; width:114px;">
												<?php
												}
												?>
												<!-- Modal -->

												<!-- /.modal -->
											</span>

									</td>
								</tr>
								
								<tr>
								    <th class="head"> <?php echo get_phrase('barcode');?></th>
								    <td>
								        <span class="text-left">

                                        <?php     
                                        $val_im=display_link($rows[0]['barcode_image'],'student',0,0); 
                                        if($val_im!=""){
                                        ?>
										<img src="<?php echo $val_im; ?>" class="img-responsive" style="height:50px;">
										<?php
										}
										?>
										</span>
								    </td>
								</tr>



							</tbody>
						</table>
					</div>
				</div>
				
				<div id="Contact" class="tab-pane">
					<div class="table-responsive panel">
						<table class="table table-hover ">
								<tr>
									<th class="head" style="width:25%;">
										<?php echo get_phrase('location');?>
									</th>
									<td style="width:75%;">
										<?php 
                                            if($loc_arr[0]['location']!=""){
                                                echo $loc_arr[0]['country'].'->'.$loc_arr[0]['province'].'->'.$loc_arr[0]['city'].'->'.$loc_arr[0]['location']; 
                                                
                                            }
                                        ?>
									</td>
								</tr>


								<tr>
									<th class="head">
										<?php echo get_phrase('postal_address');?>
									</th>
									<td>
										<?php echo $rows[0]['address'];  ?>
									</td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('permanent_address');?>
									</th>
									<td>
										<?php echo $rows[0]['p_address'];  ?> </td>
								</tr>
								<tr>
									<th class="head"><?php echo get_phrase('mobile_no');?> </th>
									<td>
										<?php echo $rows[0]['mob_num'];  ?> </td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('residence_no');?>
									</th>
									<td>
										<?php echo $rows[0]['phone'];  ?>
									</td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('emergency_no');?>
									</th>
									<td>
										<?php echo $rows[0]['emg_num'];  ?>
									</td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('email');?>
									</th>
									<td>
										<?php echo $rows[0]['student_email'];  ?>
									</td>
								</tr>
						</table>
					</div>
				</div>
				
				<div id="parent_info" class="tab-pane">

					<?php
					$parent = $this->db->query( "SELECT * 
					FROM " . get_school_db() . ".student_parent sp 
					INNER JOIN " . get_school_db() . ".student_relation sr  
					ON sp.s_p_id = sr.s_p_id 
					WHERE sr.school_id=" . $_SESSION[ 'school_id' ] . "  
					AND sr.student_id =" . intval( $rows[ 0 ][ 'student_id' ] ) )->result_array();
					
					?>

					<div class="table-responsive panel">
						<table class="table table-hover">
                            <?php 
                                foreach($parent as $parent_arr)
                                {
                                    $relation = ''; 
                            ?>
								<tr>
									<th class="head"  style="width:25%;">
										<h4>
										<?php
										    if($parent_arr['relation']=='m'){
                                            $relation ="Mother";	
                                            }elseif($parent_arr['relation']=='f'){
                                            $relation = "Father";
                                            }elseif($parent_arr['relation']=='g'){
                                            $relation = "Guardian";
                                            } 
                                            echo $relation;
                                        ?>
										</h4>
									</th>
									<td>
									</td>
								</tr>
								<tr>
									<td class="head">
										<?php echo get_phrase($relation.'_name');?>
									</td>
									<td>
										<?php echo $parent_arr['p_name']; ?>
									</td>
								</tr>
								<tr>
									<td class="head">
										<?php echo get_phrase($relation.'_id_no');?>
									</td>
									<td>
										<?php echo $parent_arr['id_no']; ?> </td>
								</tr>
								<tr>
									<td class="head">
										<?php echo get_phrase($relation.'_id_file');?>
									</td>
									<td>
										<?php if ($parent_arr['id_file'] != '')
                        				{
                        				 ?>
										<a target="_blank" href="<?php echo display_link($parent_arr['id_file'],'student');?>"><?php echo get_phrase('id_file');?></a>
										<?php
										} else
											echo 'No id_file';
										?>
									</td>
								</tr>
								<tr>
									<td class="head">
										<?php echo get_phrase($relation.'_contact_number');?>
									</td>
									<td>
										<?php echo $parent_arr['contact']; ?>
									</td>
								</tr>
								<tr>
									<td class="head">
										<?php echo get_phrase($relation.'_occupation');?>
									</td>
									<td>
										<?php echo $parent_arr['occupation']; ?>
									</td>
								</tr>
							<?php
								}
							?>
						</table>
					</div>
				</div>
				
				<div id="offical_info" class="tab-pane">
					<div class="table-responsive panel">
						<table class="table table-hover ">
							<tbody>
								<tr>
									<th style="width:25%;" class="head">
										<?php echo get_phrase('academic_year');?>
									</th>
									<td style="width:75%;">
										<?php echo $acad_year[0]['academic_year'];  ?>
									</td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('roll_no');?>
									</th>
									<td>
										<?php echo $rows[0]['roll'];  ?>
									</td>
								</tr>
								<tr>
									<th class="head">
										<?php echo get_phrase('form_number');?>
									</th>
									<td>
										<?php echo $rows[0]['form_num'];  ?>
									</td>
								</tr>

								<tr>
									<th class="head">
										<?php echo get_phrase('reg_no');?>
									</th>
									<td>
										<?php echo $rows[0]['reg_num'];  ?>
									</td>
								</tr>


								<tr>
									<th class="head">
										<?php echo get_phrase('admission_date');?>
									</th>
									<td>
										<?php echo convert_date($rows[0]['adm_date']);   ?>
									</td>
								</tr>

							</tbody>
						</table>
					</div>
				</div>
				
			</div>
	    </div>

	</div>
</div>