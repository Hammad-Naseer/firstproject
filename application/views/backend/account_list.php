<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content="Admin Panel"/>
	<meta name="author" content=""/>
	<title><?php echo get_phrase('Indici-Edu '); ?>|<?php echo get_phrase(' information_management_system'); ?></title>
	<script src="<?php echo base_url();?>assets/js/jquery-1.11.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/switch_user.css">
	<script src="https://use.fontawesome.com/06111aeca4.js"></script>
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png">
</head>
<body>
    <style>
    .fimg{
        width:200px;
    }
        /*footer#footer .company-info {*/
        /*    padding: 10px;*/
        /*    color: #fff;*/
        /*} */
        /*p.adpara_switch_screen {*/
        /*    color: white;*/
        /*    font-size: 15px;*/
        /*    font-weight: 700;*/
        /*}*/
        /*.list-of-schools .container {*/
        /*    background: #02658d;*/
        /*}*/
        /*.footerPara {*/
        /*     text-align:center !important;font-size: 1.1em !important;color:#cac5c5 !important  */
        /*}*/
            
    .foter-bottm{background-color:#121212}ul.footr-li-list{text-decoration:none;list-style:none;margin-bottom:12px;margin-top:12px;padding:0}
    ul.footr-li-list li a i{margin:0 8px 0 15px}ul.footr-li-list li a{color:#fff}.container-fluid.foter-top{background:#333;padding:20px 0}
    img.indici-edu-footr-logo{border-right:solid 3px #0dcaf0;padding-right:24px;max-width:305px}.text-white,.text-white p{color:#fff}
    .d-none{display:none}.m-0{margin:0}@media only screen and (min-width:768px){ul.footr-li-list{display:flex;justify-content:center}
    .foter-logs{text-align:right}}@media only screen and (max-width:768px){img.indici-edu-footr-logo{border-right:solid 0 #0dcaf0;width:100%}}
    
    .list-of-schools .filter-button {
    margin: 4px 0px;
    }
    @media only screen and (max-width:475px){
    .list-of-schools .filter-button {
    width: 100%;
    }
    }

    </style>
	<header id="header-admin">
		<div class="container">
			<div class="row">
				<div class="col-md-2  text-center">
					<div class="co-logo"><img src="<?php echo base_url();?>assets/images/account-list.png" alt="">
					</div>
				</div>	
				<div class="col-md-7 text-center">
					<h2 style="margin-top: 9px;text-transform:lowercase !important;">indici-edu Information Management System</h2>
				</div>
				<div class="col-md-3 text-center">
					<ul class="teacherdetail">
						<?php
						$profile_src = 'uploads/profile_pic/default.png';
						if ( $_SESSION[ 'user_profile_pic' ] != '' ) {
							$profile_src = 'uploads/profile_pic/' . $_SESSION[ 'user_profile_pic' ];
						}
						?>

						<li>
                           <?php if(!empty($profile_src)) { ?>
                            <img src="<?php echo base_url(); ?><?php echo $profile_src; ?>" alt="" width="40" height="40" style=" border-radius: 35px;">

                            <?php }
                            else {
                               ?>
                                <img src="<?php echo base_url(); ?>assets/images/user_01.png" alt="" width="40" height="40" style=" border-radius: 35px;">
                            <?php
                            }
                            ?>
							<span style="color:white;" ><?php echo $_SESSION['user_profile_name']; ?></span>
						</li>
						<li class="logout">
							<a style="color:white;" href="<?php echo base_url() ?>login/logout"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</header>

	<?php
	if ( isset( $_SESSION[ 'user_login_id' ] ) && intval( $_SESSION[ 'user_login_id' ] ) != 0 ) {
		//print_r($_SESSION);
		$system_db = $_SESSION[ 'system_db' ];
		$user_login_id = intval( $_SESSION[ 'user_login_id' ] );

		$login_detail_arr = $this->db->order_by( 'login_type', 'ASC' )->get_where( $system_db . '.user_login_details', array( 'user_login_id' => $user_login_id, 'status' => 1 ) )->result_array();
		$admin_arr = array();
		$teacher_arr = array();
		$parent_arr = array();
		$staff_arr = array();
		if ( count( $login_detail_arr ) > 0 ) {
			//$user_login_arr = $this->db->get_where( $system_db . '.user_login', array( 'user_login_id' => $user_login_id ) )->result_array();

			foreach ( $login_detail_arr as $key => $log_det ) {
				if ( $log_det[ 'login_type' ] == get_login_type_id( 'admin' ) ) {
					$admin_arr[] = array( 'type' => 'admin', 'sys_sch_id' => $log_det[ 'sys_sch_id' ], 'user_login_detail_id' => $log_det[ 'user_login_detail_id' ] );
				} elseif ( $log_det[ 'login_type' ] == get_login_type_id( 'branch_admin' ) ) {
					$admin_arr[] = array( 'type' => 'branch_admin', 'sys_sch_id' => $log_det[ 'sys_sch_id' ], 'user_login_detail_id' => $log_det[ 'user_login_detail_id' ] );
				}
				elseif ( $log_det[ 'login_type' ] == get_login_type_id( 'teacher' ) ) {
					$teacher_arr[] = array( 'sys_sch_id' => $log_det[ 'sys_sch_id' ], 'user_login_detail_id' => $log_det[ 'user_login_detail_id' ] );
				}
				elseif ( $log_det[ 'login_type' ] == get_login_type_id( 'parent' ) ) {
					$parent_arr[] = array( 'sys_sch_id' => $log_det[ 'sys_sch_id' ], 'user_login_detail_id' => $log_det[ 'user_login_detail_id' ] );
				}
				elseif ( $log_det[ 'login_type' ] == get_login_type_id( 'staff' ) ) {
					$staff_arr[] = array( 'sys_sch_id' => $log_det[ 'sys_sch_id' ], 'user_login_detail_id' => $log_det[ 'user_login_detail_id' ] );
				}
			}
	if ( count( $admin_arr ) > 0 ) {
	?>
				
	<section class="wlcm-admints">
		<div class="container">
			<div class="row school" style="margin-bottom:30px;">
				
				<?php
				$city_list = array();
				$school_details = array();
				
				foreach ( $admin_arr as $key => $admin_data )
				{
					$system_school_arr = $this->db->query( "select * from " . $system_db . ".system_school 
                            where 
                            sys_sch_id=" . $admin_data[ 'sys_sch_id' ] . " 
                            and restrict_login=0
                            and status=1
                            " )->result_array();
					if ( count( $system_school_arr ) > 0 ) {
						$school_arr = $this->db->query( "select *  from " . $system_school_arr[ 0 ][ 'school_db' ] . ".school 
                                    WHERE 
                                    sys_sch_id=" . $admin_data[ 'sys_sch_id' ] . " 
                                " )->result_array();
						if ( count( $school_arr ) > 0 )
						{
							if (!empty($school_arr[0]['city_id']))
							{
								// $location_detail = get_country_edit($school_arr[0]['location_id'],$system_school_arr[ 0 ][ 'school_db' ]);
								$city_details = get_city_detail($school_arr[0]['city_id']);

								$city_list[$school_arr[0]['city_id']] = $city_details['title'];
								$city_id = $school_arr[0]['city_id'];
							}
							else
							{
								$city_list["others"] =   get_phrase('others');
								$city_id = "others";
							}

							$school_details[$admin_data[ 'sys_sch_id' ]] = $school_arr[0];
							$school_details[$admin_data[ 'sys_sch_id' ]]['city_id'] = $city_id;
							$school_details[$admin_data[ 'sys_sch_id' ]]['admin_type'] = $admin_data['type'];
							$school_details[$admin_data[ 'sys_sch_id' ]]['user_login_detail_id'] = $admin_data['user_login_detail_id'];

							// echo "<pre>";
							// print_r($school_details);
							/*
						?>
				<div class="col-sm-3 thumbnail" style="margin-right:0px;     min-height: 300px; cursor:pointer;border-bottom: 3px solid #4a8ab9;box-shadow: 4px 2px 20px 0px #CCC;">
					<div class="logo">
						<?php
						$logo = $school_arr[ 0 ][ 'logo' ];
						if ( $logo == "" ) {
							?>
						<a href="<?php echo base_url();?>switch_user/switch_account/<?php echo $admin_data['user_login_detail_id']; ?>"><img src="<?php echo base_url(); ?>assets/images/g-sims-low-res.png" alt="" class="img-responsive ">
                        </a>
						<?php
						} else {
							?>
						<a href="<?php echo base_url();?>switch_user/switch_account/<?php echo $admin_data['user_login_detail_id']; ?>">
                      <img  class="img-responsive fimg" src="<?php echo base_url(); ?>uploads/<?php echo $school_arr[0]['folder_name'].'/'.$logo; ?>" alt="" >
                             </a>                      
						<?php
						}
						?>
					</div>
					<div class="detail">
						<strong>
					
							<p style="font-size:14px; text-align: center;    color: #4a8cbb; padding: 1px;">
								<?php echo $school_arr[0]['name']; ?>
							</p>
						</strong>

						<p style="text-align: center; margin-top:10px;">
							<?php echo $school_arr[0]['address']; ?>
						</p>

						<p  style="text-align: center; margin-top:10px;"><strong>Account Type: </strong>
							<?php echo ucwords(str_replace('_', ' ', ($admin_data['type'])));?>
						</p>

					</div>
					 
				</div>
					
				<?php
				*/
				}
				}
				}
				?>
			</div>
		</div> 
	</section>
	
	
	<section  class="list-of-schools">
	    <div class="container" style="margin-bottom:100px;">
			<div class="row">
				<div class="col-sm-12 text-center">
				    <p class="adpara_switch_screen">
						 <?php echo get_phrase('access_following_schools_as'); ?> <span><?php echo get_phrase('administrator'); ?></span>.
						 <br>
						 <b>Logged in as <?= get_school_name($_SESSION["school_id"])?></b>
					</p>
					<div class="myg"><?php echo get_phrase('list_of_schools'); ?></div>
				</div>	
			</div> 
	 
	<div class="row">
        <div style="margin-left: 15px; margin-bottom: 15px;">
        <?php
        $others = "";
        if(count($city_list)>0)
        {
        ?>
        <button class="btn btn-default filter-button actv" data-filter="all"><?php echo get_phrase('all'); ?></button>
        <?php
        foreach ($city_list as $key => $value)
        {
        	if($key=="others")
        	{
        		$others = '<button class="btn btn-default filter-button" data-filter="'.$key.'">'.$value.'</button>';
        	}
        	if(intval($key))
        	{
        ?>
            <button class="btn btn-default filter-button" data-filter="<?php echo $key;?>">
            <?php 
            echo $value;
            ?></button>
        <?php
    		}
        }
		echo $others;
		}
		?>
        </div>
        <br/>
         <?php
         foreach ($school_details as $key => $value) {
         ?>
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 filter <?php echo $value['city_id']?>">
				<a href="<?php echo base_url();?>switch_user/switch_account/<?php echo $value['user_login_detail_id']; ?>">
				<div class="thumbnail" style="margin-right:0px; padding:15px; min-height: 300px; cursor:pointer;border-bottom: 3px solid #4a8ab9;box-shadow: 4px 2px 20px 0px #CCC;">
					<div align="center">
						<?php
						    $logo = $value[ 'logo' ];
						    if ( $logo == "" ) {
						?>
						<img src="<?php echo base_url();?>assets/images/g-sims-low-res.png" alt="" class="img-responsive ">
						<?php } else { ?>
                        <img class="img-responsive fimg" src="<?php echo base_url();?>uploads/<?php echo $value['folder_name'].'/'.$logo; ?>" alt="" >                     
						<?php
						}
						?>
					</div>
					<br>
					<div class="detail">
						<strong>
					
							<p style="font-size:14px; text-align: center;    color: #00BCD4; padding: 1px;">
								<?php echo $value['name']; ?>
							</p>
						</strong>

						<!--<p style="text-align: center; margin-top:10px;">-->
							<?php //echo $value['address']; ?>
						<!--</p>-->
						
						<p style="text-align: center; margin-top:10px;">
						    <strong>
						        <?php echo get_phrase('account_type'); ?>: 
						    </strong>
							<?php echo ucwords(str_replace('_', ' ', ($value['admin_type'])));?>
						</p>
					</div>
					 
				</div>
                </a>
            </div>
         <?php	
         }
         ?>
            
    </div>
    </div>
	</section>
	<?php
	}

	if ( count( $teacher_arr ) > 0 ) {
		?>
	<section>
		<div class="container-fluid">
			<div class="row teacher-admin">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="main-school">
						<div class="logo">
							<!--<a href="#"><img src="assets/images/g-sims-low-res.png" alt="" width="100%"></a>-->
							<div class="detail">
								<p><?php echo get_phrase('access_following_schools_as'); ?> <span><?php echo get_phrase('teacher'); ?></span>.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row teacher" style="margin-bottom:30px;">
				<?php
				foreach ( $teacher_arr as $key => $teacher_data ) {
					$system_school_arr = $this->db->query( "select * from " . $system_db . ".system_school 
                            where 
                            sys_sch_id=" . $teacher_data[ 'sys_sch_id' ] . " 
                            and status=1 
                            " )->result_array();
					if ( count( $system_school_arr ) > 0 ) {
						$school_arr = $this->db->query( "select * from " . $system_school_arr[ 0 ][ 'school_db' ] . ".school 
                                    WHERE 
                                    sys_sch_id=" . $teacher_data[ 'sys_sch_id' ] . " 
                                " )->result_array();

						if ( count( $school_arr ) > 0 ) {
							?>
							
							
							
							
							<div class="col-sm-3 thumbnail" style="margin-right:0px;     min-height: 300px; cursor:pointer;border-bottom: 3px solid #4a8ab9;box-shadow: 4px 2px 20px 0px #CCC;">
    
					<div class="logo">
						<a href="<?php echo base_url();?>switch_user/switch_account/<?php echo $teacher_data['user_login_detail_id']; ?>">
							<?php
							$logo = $school_arr[ 0 ][ 'logo' ];
							if ( $logo == "" ) {
								?>
							<img src="<?php echo base_url();?>assets/images/g-sims-low-res.png" alt="" class="fimg" >

							<?php
							} else {
								?>
							<img src="<?php echo base_url(); ?>uploads/<?php echo $school_arr[0]['folder_name'].'/'.$logo; ?>" alt="" class="fimg" >

							<?php
							}
							?>
						

					</div>
					<div class="detail">
						<strong>
								
							<p style="font-size:14px; text-align: center;    color: #4a8cbb; padding: 1px;">
								<?php echo $school_arr[0]['name'] ?>
							</p>
						</strong>
						<p style="text-align: center; margin-top:10px;">
							<?php echo $school_arr[0]['address'] ?>
						</p>
					</div>
				</div>
				<?php
				}
				}
				}
				?>
                </a>
			</div>
		</div>
	</section>
	<?php
	}

	if ( count( $staff_arr ) > 0 ) {
		?>
	<section>
		<div class="container-fluid">
			<div class="row teacher-admin">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="main-school">
						<div class="logo">
							<!--<a href="#"><img src="assets/images/g-sims-low-res.png" alt="" width="100%"></a>-->
							<div class="detail">
								<p><?php echo get_phrase('access_following_schools_as'); ?><span><?php echo get_phrase('staff'); ?></span>.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row teacher" style="margin-bottom:30px;">
				<?php
				foreach ( $staff_arr as $key => $staff_data ) {
					$system_school_arr = $this->db->query( "select * from " . $system_db . ".system_school 
                            where 
                            sys_sch_id=" . $staff_data[ 'sys_sch_id' ] . " 
                            and status=1 
                            " )->result_array();
					if ( count( $system_school_arr ) > 0 ) {
						$school_arr = $this->db->query( "select * from " . $system_school_arr[ 0 ][ 'school_db' ] . ".school 
                                    WHERE 
                                    sys_sch_id=" . $staff_data[ 'sys_sch_id' ] . " 
                                " )->result_array();

						if ( count( $school_arr ) > 0 ) {
							?>
										
				<div class="col-sm-3 thumbnail" style="margin-right:0px;     min-height: 300px; cursor:pointer;border-bottom: 3px solid #4a8ab9;box-shadow: 4px 2px 20px 0px #CCC;">
					<div class="logo">
						<a href="<?php echo base_url();?>switch_user/switch_account/<?php echo $staff_data['user_login_detail_id']; ?>">
							<?php
							$logo = $school_arr[ 0 ][ 'logo' ];
							if ( $logo == "" ) {
								?>
							<img src="<?php echo base_url();?>assets/images/g-sims-low-res.png" alt="" class="fimg">

							<?php
							} else {
								?>
							<img src="<?php echo base_url(); ?>uploads/<?php echo $school_arr[0]['folder_name'].'/'.$logo; ?>" alt="" class="fimg">

							<?php
							}
							?>
					</div>
					<div class="detail">
						<strong>
						<p style="font-size:14px; text-align: center;    color: #4a8cbb; padding: 1px;">
								<?php echo $school_arr[0]['name'] ?>
							</p>
						</strong>
						<p style="text-align: center; margin-top:10px;">
							<?php echo $school_arr[0]['address'] ?>
						</p>
					</div>
				</div>
				<?php
				}
				}
				}
				?>
                </a>
			</div>
		</div>
	</section>
	<?php
	}

	if ( count( $parent_arr ) > 0 ) {
		?>
	<section>
		<div class="container-fluid">
			<div class="row parent-admin">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="main-school">
						<div class="logo">
							<!--<a href="#"><img src="assets/images/g-sims-low-res.png" alt="" width="100%"></a>-->
							<div class="detail">
								<p><?php echo get_phrase('access_following_schools_as'); ?><span><?php echo get_phrase('parent'); ?></span>.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row parent" style="margin-bottom:30px;">
				<?php
				foreach ( $parent_arr as $key => $parent_data ) {
					$system_school_arr = $this->db->query( "select * from " . $system_db . ".system_school 
                            where 
                            sys_sch_id=" . $parent_data[ 'sys_sch_id' ] . " 
                            and status=1 
                            " )->result_array();
					if ( count( $system_school_arr ) > 0 ) {
						$school_arr = $this->db->query( "select * from " . $system_school_arr[ 0 ][ 'school_db' ] . ".school 
                                    WHERE 
                                    sys_sch_id=" . $parent_data[ 'sys_sch_id' ] . " 
                                " )->result_array();
						if ( count( $school_arr ) > 0 ) {
							?>
				<div class="col-lg-12 col-md-12 col-sm-12 clearfix" style="margin-bottom:20px;">
					<div class="logo">
						<a href="#"><img src="<?php echo base_url();?>assets/images/g-sims-low-res.png" alt="" width="100" height="auto"></a>
						<div class="detail">
							<strong>
								<p style="font-size:14px;">
									<?php echo $school_arr[0]['name'] ?>
								</p>
							</strong>

							<p>
								<?php echo $school_arr[0]['address'] ?>
							</p>
						</div>
					</div>
					<?php
					$child_query = $this->db->query( "select sr.*, c.name as class_name, sp.*,s.*,cs.title as section_name, dep.title as department_name
                                                from " . $system_school_arr[ 0 ][ 'school_db' ] . ".student_parent sp
                                                INNER JOIN " . $system_school_arr[ 0 ][ 'school_db' ] . ".student_relation sr ON sr.s_p_id = sp.s_p_id
                                                INNER JOIN " . $system_school_arr[ 0 ][ 'school_db' ] . ".student s ON s.student_id=sr.student_id
                                                INNER JOIN " . $system_school_arr[ 0 ][ 'school_db' ] . ".class_section cs ON cs.section_id=s.section_id
                                                INNER JOIN " . $system_school_arr[ 0 ][ 'school_db' ] . ".class c ON c.class_id=cs.class_id
                                                INNER JOIN " . $system_school_arr[ 0 ][ 'school_db' ] . ".departments dep ON dep.departments_id=c.departments_id 
                                                WHERE  
                                                s.student_status IN (" . student_query_status() . ")
                                                and sp.school_id=" . $school_arr[ 0 ][ 'school_id' ] . " 
                                                and sp.user_login_detail_id=" . $parent_data[ 'user_login_detail_id' ] )->result_array();
					//echo $this->db->last_query();
					if ( count( $child_query ) > 0 ) {
						foreach ( $child_query as $rows ) {
							?>
					<a href="<?php echo base_url();?>switch_user/switch_account/<?php echo $parent_data['user_login_detail_id'].'/'.$rows['student_id']; ?>">
						<div class="student-list">
							<div class="student-pic">
								<?php
								if ( $rows[ 'image' ] != "" ) {
									?>
								<img src="<?php echo display_link($rows['image'],'student') ?>" alt="" width="70" height="auto">
								<?php
								} else {
									?>
								<img src="<?php echo get_default_pic();?>" alt="" width="70" height="auto">
								<?php
								}
								?>

							</div>
							<div class="student-detail">
								<p>
									<?php echo $rows['name'];  ?>
								</p>
								<p><strong><?php echo get_phrase('department'); ?>:</strong>
									<?php echo $rows['department_name'];  ?>
								</p>
								<p><strong><?php echo get_phrase('class'); ?>:</strong>
									<?php echo $rows['class_name'];  ?>
								</p>
								<p><strong><?php echo get_phrase('section'); ?>:</strong>
									<?php echo $rows['section_name'];  ?>
								</p>
								<p><strong><?php echo get_phrase('roll'); ?>#:</strong>
									<?php echo $rows['roll'];  ?>
								</p>
							</div>
						</div>
					</a>
					<?php } } else echo 'No child'; ?>
				</div>
				<?php } } } ?>
			</div>
		</div>
	</section>
	<?php } } else { ?>
	<div class="login_error text-center">
		<div class="form-login-error" style="display:block">
			<p><?php echo get_phrase('sorry'); ?>! <?php echo get_phrase('no_record_found'); ?></p>
		</div>
	</div>
	<?php } }else{ redirect( base_url() . 'login/'); } ?>
	</section>
	
	

 <footer class="footer-main"> 

    <div class="container-fluid foter-top">
        <div class="container"> 
         <div class="row align-items-center justify-content-center">
          <div class="col-sm-12 col-md-6 col-lg-6 foter-logs d-none">
              <img src="https://indiciedu.com.pk/frontend/wp-content/uploads/2021/01/indici-edu-logo-SVG.svg" class="indici-edu-footr-logo">
          </div>
         <div class="col-md-12  col-md-12 col-lg-12 text-sm-start text-md-start text-lg-center  text-white text-center">
            <p>INDICI-EDU IS A FLAGSHIP PRODUCT OF CAMPUSNETIC SOLUTIONS (AN ICT & EDUTECH STARTUP) </p>
            <p class="m-0">WHICH IS SUPPORTED BY F3 GROUP OF TECHNOLOGY COMPANIES. © 2017-<?= date("Y") ?> ALL RIGHTS RESERVED</p>
         </div> 
         </div>
        </div>
    </div>

    <div class="container-fluid d-none">    
    <div class="row foter-bottm"> 
        <div class="col-12 text-sm-start text-md-center">
          <ul class="footr-li-list d-md-inline-flex pt-2">
            <li><a href="https://indiciedu.com.pk"><i class="fa fa-globe"></i>www.indiciedu.com.pk</a></li>
            <li><a href="https://web.whatsapp.com/send?phone=923155172825&amp;text=Hey!!!%20i%20am%20interested%20in%20indici-edu%20services."><i class="fa fa-whatsapp"></i>+92 315 5172825</a></li>
            <li><a href="mailto:info@indiciedu.com.pk"><i class="fa fa-envelope"></i>info@indiciedu.com.pk</a></li><a href="mailto:info@indiciedu.com.pk">
            </a><li><a href="mailto:info@indiciedu.com.pk"></a><a href="https://www.facebook.com/Indici.edu"><i class="fa fa-facebook-f"></i>www.facebook.com/Indici.edu</a></li>
          </ul>
        </div>
    </div>
    </div>
  </footer>
	
	
	<!-- 
	
	<footer id="footer" style="position:fixed; bottom:0px; width:100%; background:#000;">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 text-center">
					<div class="company-info">
                     <span>Powered by:</span> <a href="http://campusnetic.com" target="_blank">CAMPUSNETIC SOLUTIONS</a> | <span><?php echo get_phrase('supported_by'); ?> </span> <a href="http://www.fairfactorforce.com" target="_blank">F3 Group of Technology Companies</a>
					</div>
				</div>
			</div>
		</div>
	</footer>
	
	
	-->
	
	
	<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
</body>
</html>
<script>
$(document).ready(function(){ 
    $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
        if(value == "all")
        {
            $('.filter').show('1000');
        }
        else
        {
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
        }
    });
    if ($(".filter-button").removeClass("active")) {
	    $(this).removeClass("active");
	}
    	$(this).addClass("active");
	});
</script>