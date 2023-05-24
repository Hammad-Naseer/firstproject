<?php
$driver_info	=	$this->crud_model->get_driver_info($param2);
foreach($driver_info as $row):?>

<div class="profile-env">
	
	<header class="row">
		
		<div class="col-sm-3">
			
			<a href="#" class="profile-picture">
            	<img src="<?php echo $this->crud_model->get_image_url('driver' , $row['driver_id']);?>" 
                	class="img-responsive img-circle" />
			</a>
			
		</div>
		
       
		<div class="col-sm-9">
			
			<ul class="profile-info-sections">
				<li style="padding:0px; margin:0px;">
					<div class="profile-name">
							<h3><?php echo $row['name'];?></h3>
					</div>
				</li>
			</ul>
			
		</div>
		
		
	</header>
	
	<section class="profile-info-tabs">
		
		<div class="row">
			
			<div class="">
            		<br>
                <table class="table table-bordered">
                    <?php if($row['driver_id'] != ''):?>
                    <tr>
                        <td>Driver ID</td>
                        <td><b><?php echo $row['driver_id'];?></b></td>
                    </tr>
                    <?php endif;?>
                <tr>
                        <td>Date of Joining</td>
                        <td><b><?php echo $row['join_date'];?></b></td>
                    </tr>
                    <?php if($row['birthday'] != ''):?>
                    <tr>
                        <td>Birthday</td>
                        <td><b><?php echo $row['birthday'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['gender'] != ''):?>
                    <tr>
                        <td>Gender</td>
                        <td><b><?php echo $row['gender'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                
                    <?php if($row['phone'] != ''):?>
                    <tr>
                        <td>Phone</td>
                        <td><b><?php echo $row['phone'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['email'] != ''):?>
                    <tr>
                        <td>Email</td>
                        <td><b><?php echo $row['email'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['address'] != ''):?>
                    <tr>
                        <td>Address</td>
                        <td><b><?php echo $row['address'];?></b>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>I.D. Card Front</td>
                        <td><img src="<?php echo $this->crud_model->get_id_front_image_url('driver' , $row['driver_id']);?>" alt="<?php echo $row['name'].' I.D. Card Front';?>" class="img-responsive img-thumbnail" style="min-width:80%; max-width:95%; min-height:200px; max-height:230px;">
                        </td>
                    </tr>
                    
                    <tr>
                        <td>I.D. Card Back</td>
                        <td><img src="<?php echo $this->crud_model->get_id_back_image_url('driver' , $row['driver_id']);?>" alt="<?php echo $row['name'].' I.D. Card Back';?>" class="img-responsive img-thumbnail" style="min-width:80%; max-width:95%; min-height:200px; max-height:230px;">
                        </td>
                    </tr>
                    
                    <?php endif;?>
                    
                </table>
			</div>
		</div>		
	</section>
		
	                            <div>
                    <h1 class="warning">Disclaimer Note:</h1>
                    <span>School is not responsible for this driver. We are just a facilitator. The parents need to reckie on their own</span>
                </div>
	
	
</div>


<?php endforeach;?>