<?php
    $baseurl         =   base_url();
    $custom_assets   =   "assets/landing_pages/".$sub_domain.'/';
    $custom_base_url =   $baseurl."".$custom_assets;
    $logo_name       =   "campusnetic-schoolRE.png";
    $data_row        =   $data_row;
    $logo_path       =   $baseurl.$custom_assets.$data_row['logo'];
    $banner_path     =   $baseurl.$custom_assets.$data_row['banner_image'];
    $gallery_images  =   $gallery_images;
    $lat             =   $data_row['latitude'];
    $lng             =   $data_row['longitude'];
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1">
    <link                  href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $custom_base_url; ?>style.css">
    <link rel="stylesheet" href="<?php echo  base_url() ; ?>assets/js/calendar/fullcalendar.min.css">
    <link                  href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .navbar-collapse{flex-grow:inherit}#calendar_view{background-color:#cccccc38;padding:10px;color:#374d7d}.fc-scroller.fc-day-grid-container{height:auto!important}
        .fc-row.fc-week.fc-widget-content.fc-rigid{height:60px!important}button.fc-next-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right,button
        .fc-prev-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right{background:#374d7d!important;color:#fff!important;box-shadow:2px 4px 10px 1px #ccc}
        .fc-today-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right.fc-state-disabled,button.fc-month-button.fc-button.fc-state-default.fc-corner-left
        .fc-corner-right.fc-state-active{background:#374d7d!important;color:#fff!important;box-shadow:2px 4px 10px 1px #ccc}.fc-head,td.fc-widget-content{background:#fff!important}
        .fc-widget-content{font-weight:600}td.fc-day.fc-widget-content.fc-fri.fc-today.fc-state-highlight{border-style:double;background:#374d7d!important;color:#fff!important;font-weight:600}td
        .fc-day-top.fc-fri.fc-today.fc-state-highlight{color:#fff!important}.job-border{border-bottom:solid 1px #374d7d}.jobs-container{box-shadow:0 0 17px -9px #374d7d;padding:0!important}
        .flex-direction-column{flex-direction:column}.jobs-hdr{background:#374d7d}.jobs-hdr h6{color:#fff}.modal-header{background:#374d7d;color:#fff}@media (min-width:576px){
            .modal-dialog{max-width:700px;margin:1.75rem auto}}.fc-calendar-seting span.fc-day-number{float:none!important;display:flex;justify-content:center;align-items:center;font-size:22px}
            .fc-calendar-seting .fc-content-skeleton{top:9px!important;padding:0!important}.fc-calendar-seting .fc-row.fc-week.fc-widget-content.fc-rigid{height:46px!important}.fc-calendar-seting 
            .fc-basic-view .fc-body .fc-row{min-height:40px}.fc-calendar-seting .fc-day{border:none!important}.fc-calendar-seting td.fc-widget-content{border:none!important}.fc-calendar-seting 
            .fc-today span.fc-day-number{background:#0992c9!important;color:#fff;border-radius:50px}.fc-calendar-seting thead.fc-head{background:#374d7d!important;color:#fff}.fc-calendar-seting 
            .timeline{position:relative;max-width:1200px;margin:0 auto}.fc-calendar-seting .timeline::after{content:'';position:absolute;width:6px;background-color:#374d7d;top:0;bottom:0;left:6%;margin-left:-3px}
            .fc-calendar-seting .container-riht{padding:10px 40px;position:relative;background-color:inherit}
            .fc-calendar-seting .container-riht::after{content:'';position:absolute;width:25px;height:25px;right:-17px;background-color:#fff;border:4px solid #ff9f55;top:15px;border-radius:50%;z-index:1}
            .fc-calendar-seting .right{left:6%}.fc-calendar-seting .right::before{content:" ";height:0;position:absolute;top:22px;width:0;z-index:1;left:30px;border:medium solid #fff;border-width:10px 10px 10px 0;border-color:transparent #fff transparent transparent}
            .fc-calendar-seting .right::after{left:-13px}.fc-calendar-seting .content{padding:20px 30px;background-color:#fff;position:relative;border-radius:6px}.col-md-6.timeline-colum{background-color:#efeff0;height:400px;max-height:450px;overflow:hidden;overflow-y:scroll}.fc-calendar-seting .timeline span.date{color:#ee9324}.fc-calendar-seting .content h5{color:#374d7d}
    </style>

    <title><?= ucfirst($sub_domain); ?></title>
  </head>
  <body>
    
    
    
<header>
<div class="container-fluid">   
    <div class="container top-bar text-white">
        <div class="row">
            <div class="col-auto me-auto"><a href="#"><i class="fas fa-phone-volume"></i><?=$data_row['mobile_num'];?></a><a href="#"><i class="fab fa-whatsapp"></i><?= $data_row['whatsapp_num']; ?></a><a href="#"><i class="fas fa-envelope"></i><?=$data_row['email'];?></a></div>
            <div class="col-auto"><a href="<?=$data_row['facebook'];?>"><i class="fab fa-facebook-f"></i></a><a href="<?=$data_row['twitter'];?>"><i class="fab fa-twitter"></i></a><a href="<?=$data_row['linkedin'];?>"><i class="fab fa-linkedin-in"></i></a></div>
        </div> 
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-light  ">
  <div class="container">
    <a class="navbar-brand" href="#">
        <?php if($data_row['logo'] != ""){ ?>
        <img src="<?=$logo_path?>" class="rounded" alt="campusnetic-school Logo">
        <?php }else{ ?>
            <img src="<?=$custom_base_url?>img/indiciedulogo.png" class="rounded" alt="campusnetic-school Logo">
        <?php 
        }
        ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ">
        <li class="nav-item">
          <a href="#About-Us">
              <!--<img src="<?=$custom_base_url?>img/information1.png" class=" mx-auto d-block" alt="account1 Menu">-->
          <span class="nav-link active" aria-current="page">About Us</span></a>
        </li>
        <li class="nav-item">
          <a href="#LMS-Login">
              <!--<img src="<?=$custom_base_url?>img/account1.png" class=" mx-auto d-block" alt="account1 Menu">-->
          <span class="nav-link">LMS Login</span></a>
        </li>
        <li class="nav-item">
          <a href="#Online-Admissions">
              <!--<img src="<?=$custom_base_url?>img/admission1.png" class=" mx-auto d-block" alt="account1 Menu">-->
          <span class="nav-link">Online Admissions</span></a>
        </li>
        <li class="nav-item">
          <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <!--<img src="<?=$custom_base_url?>img/inquiry1.png" class=" mx-auto d-block" alt="account1 Menu">-->
          <span class="nav-link">General Inquiry</span></a>
        </li>
        <li class="nav-item">
          <a href="#News-Events">
              <!--<img src="<?=$custom_base_url?>img/file1.png" class=" mx-auto d-block" alt="account1 Menu"> -->
          <span class="nav-link">News & Events</span></a>
        </li>
        <li class="nav-item">
          <a href="#lms-jobs">
              <!--<img src="<?=$custom_base_url?>img/job-icon.png" class=" mx-auto d-block" alt="account1 Menu"> -->
          <span class="nav-link">Careers</span></a>
        </li>
        <li class="nav-item">
          <a href="#Contact-Us">
              <!--<img src="<?=$custom_base_url?>img/contact1.png" class=" mx-auto d-block" alt="account1 Menu">-->
          <span class="nav-link">Contact Us</span></a>
        </li>
        <!--<li class="nav-item dropdown">-->
        <!--  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">-->
        <!--    Dropdown-->
        <!--  </a>-->
        <!--  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">-->
        <!--    <li><a class="dropdown-item" href="#">Action</a></li>-->
        <!--    <li><a class="dropdown-item" href="#">Another action</a></li>-->
        <!--    <li><hr class="dropdown-divider"></li>-->
        <!--    <li><a class="dropdown-item" href="#">Something else here</a></li>-->
        <!--  </ul>-->
        <!--</li>--> 
      </ul> 
    </div>
  </div>
</nav>
</header>


<div class="container-fluid welcometo  py-5" id="About-Us">
    <div class="container">
        <div class=" row">
            <div class="col-md-6">
                <h2 class="text-purple  pb-4">Welcome to <?=ucfirst($school_name)?></h2>
                <p class="wlcmtxt">
                    <?=$data_row['about_us'];?>   
                </p>
            </div>
            <div class="col-md-6">
                <?php  if($data_row['banner_image'] != ""){?>
                <img src="<?=$banner_path?>" class="mx-auto d-block img-fluid" alt="account1 Menu">
                <?php  }else{ ?>
                    <img src="<?= $custom_base_url?>/img/welcometo1.png" class="mx-auto d-block img-fluid" alt="account1 Menu">
                <?php
                }?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid facilitacie-galry py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h2 class="text-white pb-4">Facitilies We Provide</h2>
                <div class="row text-center gx-3 gy-3 g-lg-3">

                    <?php
                    foreach($school_facilities as $row)
                    {
                    ?>
                    
                        <div class="col-md-6">
                            <div class="col-inner">
                                <a href="<?php echo $row['url'] ?>"><img src="<?=$custom_base_url?>img/Icon awesome-building.png" class="mx-auto d-block img-fluid" alt="account1 Menu"></a>
                                <a class="nav-link" target="_blank" href="<?php echo $row['url'] ?>"><?php echo $row['title'] ?></a>
                            </div>
                        </div>
                    
                    <?php
                    }
                    ?>

                     
                    <!--
                    <div class="col-md-6">
                     <div class="col-inner">
                        <a href="#"><img src="<?=$custom_base_url?>img/Icon awesome-building.png " class=" mx-auto  d-block img-fluid" alt="account1 Menu"></a>
                        <a class="nav-link" href="#">Quality Education</a>
                     </div>
                    </div>
                    <div class="col-md-6">
                     <div class="col-inner">
                        <a href="#"><img src="<?=$custom_base_url?>img/Nfacilities.png" class=" mx-auto d-block img-fluid" alt="account1 Menu"></a>
                        <a class="nav-link" href="#">ICT Facilities</a>
                     </div>
                    </div>
                    <div class="col-md-6">
                     <div class="col-inner">
                        <a href="#"><img src="<?=$custom_base_url?>img/Icon awesome-building.png" class=" mx-auto d-block img-fluid" alt="account1 Menu"></a>
                        <a class="nav-link" href="#">Modren Library</a>
                     </div>
                    </div>
                    <div class="col-md-6">
                     <div class="col-inner">
                        <a href="#"><img src="<?=$custom_base_url?>img/counselling.png" class=" mx-auto d-block img-fluid" alt="account1 Menu"></a>
                        <a class="nav-link" href="#">Counselling</a>
                     </div>
                    </div>
                    <div class="col-md-6">
                     <div class="col-inner">
                        <a href="#"><img src="<?=$custom_base_url?>img/Icon awesome-clinic-medical.png" class=" mx-auto d-block img-fluid" alt="account1 Menu"></a>
                        <a class="nav-link" href="#">Medical Room</a>
                     </div>
                    </div>
                    <div class="col-md-6">
                     <div class="col-inner">
                        <a href="#"><img src="<?=$custom_base_url?>img/Icon material-location-city.png" class=" mx-auto d-block img-fluid" alt="account1 Menu"></a>
                        <a class="nav-link" href="#">Study Areas</a>
                     </div>
                    </div>
                    -->
                </div>
            </div>
            <div class="col-md-7">
                <h2 class="text-white  pb-4">Gallery</h2>
                    <div class="row text-center gx-3 gy-3  g-lg-3">
                        <?php
                        foreach($gallery_rows as $row)
                        {
                        ?>
                            <div class="col-md-4">
                                <a href="#"><img src="<?php echo base_url().'assets/landing_pages/'.$landing_page_row->sub_domain.'/'.$row['image']?>" 
                                                 class="mx-auto d-block img-fluid" alt="account1 Menu"></a>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                 
            </div>
        </div>
    </div>
</div>

<div class="container-fluid   py-5"  id="LMS-Login" style="border-bottom:solid 2px #EDEDED">
    <div class="container">
        <div class=" row">
            <div class="col-md-6    py-1">
                <img src="<?=$custom_base_url?>img/Lms-portal.jpg" class=" mx-auto d-block img-fluid" alt="account1 Menu">
            </div>
            <div class="col-md-6   py-1">
                <h2 class="text-purple  pb-4">LMS E-Learning Portal</h2>
                <p>LMS isn’t just a simple way to say Learning Management System. It is a way to simplify
                Teaching and Learning by connecting all the digital tools Teachers and Learners use in one
                simple place. indici-edu streamlines the management and support of Classroom Teaching
                and Learning Practices for our Learners, Teachers and Parents nationwide.</p>
                <?php
                     $this->load->helper('url');
                     $url_parts = parse_url(current_url());
                     $login_url =  $url_parts['scheme']."://".$url_parts['host']."/login";
                 ?>
                <a class="btn btn-primary  btn-purple" href="<?php echo $login_url; ?>" target="_blank" role="button">LMS Login</a> 
            </div>
        </div>
    </div>
</div>

<div class="container-fluid news-events py-5 bg-light" id="News-Events">
        <div class="container" > 
                    <h2 class="text-purple text-center">News & Events</h2><br>
                    <div class="row pt-4 gx-3 gy-3  g-lg-3 fc-calendar-seting bg-light p-3">
                        <div class="col-md-6 colone" id="calendar_view" class="fc-calendar">
                          
                         
                        </div> 
                        
                        <div class="col-md-6 timeline-colum bg-light" >
                            <?php 
                            foreach($notice_array as $nd)
								{
							?>
									
                                <div class="timeline">
                               
                                  <div class="container container-riht right">
                                    <div class="content">
                                      <h5> <?= $nd['notice_title'] ?> <span class="date">(<?= date("d-m-Y",strtotime($nd['create_timestamp'])) ?>)</span> </h5>
                                      <p><?= $nd['notice'] ?></p>
                                    </div>
                                  </div>
                                  
                                </div>
                                <?php
						    }
						    ?> 
						    
                        </div>
                    </div>  
        </div>
</div>
<!--<div class="container-fluid news-events py-5" id="News-Events">-->
<!--        <div class="container" > -->
<!--                    <h2 class="text-purple text-center">News & Events</h2><br>-->
<!--                    <div class="row pt-4 gx-3 gy-3  g-lg-3 fc-calendar-seting">-->
<!--                        <div class="col-md-6 colone" id="calendar_view" class="fc-calendar">-->
                          
                         
<!--                        </div> -->
<!--                        <div class="col-md-6" >-->
<!--                            <marquee direction="up" height="430px" width="100%" onmouseover="this.stop();" onmouseout="this.start();">-->
<!--                                 -->
										 <!--										}-->
<!--										?>-->
<!--                                </marquee>-->
<!--                        </div>-->
<!--                    </div>  -->
<!--        </div>-->
<!--</div>-->

    <div class="container-fluid news-events  py-5" id="News-Events" style="display:none">
        <div class="container"> 
                    <h2 class="text-purple">News & Events</h2>
                    <div class="row pt-4 gx-3 gy-3  g-lg-3">
                        <div class="col-md-4 colone">
                          <div class="col-inner">
                            <a href="#"><img src="<?=$custom_base_url?>img/Event Army.jpg" class=" mx-auto  d-block img-fluid" alt="Event one"></a>
                            <a class="nav-link text-white pt-4" href="#"><h4>23rd March</h4></a>
                          </div>
                        </div>
                        <div class="col-md-4 coltwo">
                          <div class="col-inner">
                            <a href="#"><img src="<?=$custom_base_url?>img/Defenceday.jpg" class=" mx-auto d-block img-fluid" alt="Event one"></a>
                            <a class="nav-link text-white pt-4" href="#"><h4>Independence day pakistan</h4></a>
                          </div>
                        </div>
                        <div class="col-md-4 colthree">
                          <div class="col-inner">
                            <a href="#"><img src="<?=$custom_base_url?>img/booksss.PNG" class=" mx-auto d-block img-fluid" alt="Event one"></a>
                            <a class="nav-link text-white pt-4" href="#"><h4>Books Festivals</h4></a>
                          </div>
                        </div>
                    </div>  
        </div>
    </div>
    
    
    <div class="container-fluid py-5" id="lms-jobs">
        <div class="container">
            
            <div class=" row"> 
                <div class="col-md-12 py-1 text-center">
                    <h2 class="text-purple  pb-4">Looking For Talented Potential Employees</h2> 
                </div>
            </div>
            
          <div class="jobs-container">
            
            <div class="row py-4 jobs-hdr job-border"> 
                <div class="col-md-6">
                    <h6 class="m-0">All Jobs</h6>
                </div> 
                <div class="col-md-6">
                    <h6 class="m-0 text-end">You're currently browsing: All Jobs </h6>
                </div> 
            </div>
            
             <?php
                $this->load->helper('jobs');
                foreach($jobs_details as $row){
            ?>
                    <div class="row py-2 bg-light job-border align-items-center"> 
                        
                        <div class="col-md-4">
                            <h6 class="job-title"><?php echo $row['job_title'];?></h6>
                            <p class="m-0"><?php echo $row['carrer_level'];?></p>
                        </div> 
                        <div class="col-md-4">
                            <h6 class="job-map-icon"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $row['job_location'];?></h6>
                            <p class="m-0 job-type ps-3"> <?php echo get_job_type($row['job_type']);?></p>
                            
                        </div> 
                        <div class="col-md-4 d-flex justify-content-center align-items-center flex-direction-column">
                            <h6 class="job-post-date"><?php echo date_view($row['job_end_date']);?></h6>
                            <button type="button" class="btn btn-primary btn-purple mymodal" data-bs-toggle="modal" data-jobid="<?php echo $row['job_id'];?>" data-bs-target="#staticBackdrop"> Apply Now  </button>
                        </div> 
                    </div>
            <?php
                }
            ?>
            
          </div>
             
        </div>
    </div>
    
    <div class="container-fluid py-5" id="lms-jobs">
        <div class="container">
    
        
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Fill And Submit Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <ul class="list-group py-3">
                 <li class="list-group-item d-flex justify-content-between align-items-center">
                    Job Title
                    <span id="job_title"></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Qualifications
                    <span id="qualifications"></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Career Level
                    <span id="carrer_level"></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Experience
                    <span id="experience"></span>
                  </li>
                </ul>
                <h5 class="text-purple  pb-0">Description</h5> 
                <p id="job_description"></p>
                <form class="row g-3 " id="jobs_application_form" >  
                      <input type="hidden"  name="job_id" id="job_id">
                      <div class="col-md-6"> 
                        <input type="text" class="form-control" name="name" id="inputCandidateName" placeholder="Candidate Name" required>
                      </div>
                      <div class="col-md-6"> 
                        <input type="number" class="form-control" name="mob_num" id="inputPhonenumber" placeholder="Mobile Number" required>
                      </div>
                      <div class="col-md-6"> 
                        <input type="email" class="form-control" name="email" id="inputEmail4" placeholder="Email" required>
                      </div>
                      <div class="col-md-12"> 
                        <input type="text" class="form-control" name="address" id="inpuAddress" placeholder="Address" required>
                      </div> 
                      <div class="col-md-12"> 
                        <input type="file" class="form-control" name="attachment" id="inpuattachment" required>
                      </div>  
                      <div class="modal-footer pt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-purple submit_application">Submit Application</button>
                      </div>
                </form>
              </div>
            </div>
          </div>
        </div>

    
       </div>
    </div>
    
    
    
    

    <div class="container-fluid py-5  register-interest bg-purple" id="Online-Admissions">
        <div class="container">
            <div class="row">
                <div class="col-md-6 recolone">
                    <h2 class="text-white pb-4">Register your Interest</h2>
                    <form class="row g-3" id="adm_inquiry_form" method="post">  
                      <div class="col-md-12"> 
                        <select id="inputState" class="form-select" name="class" required>
                          <?php //echo classes_option_list_landingpage($_SESSION['school_db'], $_SESSION['sys_sch_id']); ?>
                          <?php echo section_selector_landing_page($school_db ,$school_id); ?>
                        </select>
                      </div>
                      <div class="col-md-6"> 
                        <input type="text" class="form-control" name="student_name" id="inputCandidateName" placeholder="Candidate Name" required>
                      </div>
                      <div class="col-md-6"> 
                        <input type="text" class="form-control" name="f_name" id="inputFathername" placeholder="Father Name" required>
                      </div>
                      <div class="col-md-6"> 
                        <input type="number" class="form-control" name="mob_num" id="inputPhonenumber" placeholder="Mobile Number" required>
                      </div>
                      <div class="col-md-6"> 
                        <input type="email" class="form-control" name="email" id="inputEmail4" placeholder="Email" required>
                      </div>
                      <div class="col-md-12"> 
                        <input type="text" class="form-control" name="address" id="inpuAddress" placeholder="Address" required>
                      </div> 
                      <div class="col-md-12"> 
                        <textarea class="form-control" name="decsription" id="textarea" placeholder="Description Optional"></textarea>
                      </div> 
                      <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-ornage admission_btn">Send Message</button>
                      </div>
                    </form>
                </div>
                <div class="col-md-5 recoltwo offset-md-1 text-center text-white fw-bold" id="Contact-Us">
                    <h2 class="text-purple">Virtual<br>Counselling<hr class="hr-senter-set"></h2>
                    <p>Speak to Admission Advisory Today</p>
                    <p>Registrations now open for<br>Acadmic Sessions<br>2020-2021</p>
                    <p>Mon-Sat (08:30-16:30)</p>
                    <a  href="javascript:void(Tawk_API.toggle())" class="btn btn-primary btn-purple">Chat Now</a>
                    <a href="javascript:void(Tawk_API.toggle())">
                        <img src="<?=$custom_base_url?>img/Icon ionic-ios-call.png" class="img-fluid" alt="chatnow video">
                    </a>
                    <a href="javascript:void(Tawk_API.toggle())">
                        <img src="<?=$custom_base_url?>img/Icon awesome-video.png" class="img-fluid" alt="chatnow video">
                    </a>
                    <a href="javascript:void(Tawk_API.toggle())">
                        <img src="<?=$custom_base_url?>img/Icon ionic-ios-chatboxes.png" class="img-fluid" alt="chatnow msg">
                    </a>
      
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid map px-0 pt-5" style="margin-bottom:-5px;" >
       <h2 class="text-purple pb-4 text-center">Our Location</h2>
       <iframe src="https://maps.google.com/maps?q=<?= $lat ?>,<?= $lng ?>&hl=es&z=14&amp;output=embed" width="1519" height="315" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
       
    </div>

    <?php $this->load->view("backend/custom_login/footer"); ?>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="<?php echo $custom_base_url; ?>jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
   <script src="<?php echo  base_url() ; ?>assets/js/calendar/moment.js"></script>
    <script src="<?php echo  base_url() ; ?>assets/js/calendar/fullcalendar.min.js"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    -->
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/609126fd185beb22b309da8c/1f4rg0ken';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        
        $(document).ready(function(){
            
            // $("#chooseForm").on('change',function(){
            //     if($(this).val() == '1')
            //     {
            //         $("#general_inquiry").css("display","block");
            //         $("#general_inquiry").animate({height: '500px'}, "slow");
            //     }else{
            //         $("#general_inquiry").css("display","none");
            //         $("#general_inquiry").css("height","0px");
            //     }
                
            //     if($(this).val() == '2')
            //     {
            //         $("#adm_form").css("display","block");
            //         $("#adm_form").animate({height: '550px'}, "slow");
            //     }else{
            //         $("#adm_form").css("display","none");
            //         $("#adm_form").css("height","0px");
            //     }
            // });
            
            // General Inquiry Submit
            $("#general_inquiry_form").on('submit',function(e){
                e.preventDefault();
                $.ajax({
                    url: '<?= base_url() ?>inquiries/general_inquiry',
                    method: 'POST',
                    data:new FormData(this),  
                    contentType: false,  
                    cache: false,  
                    processData:false,
                    success:function(msg)
                    {
                        toastr["success"](msg);
                        $("#general_inquiry_form")[0].reset();
                        // $(".modal").toggle();
                          $(".btn-close").trigger("click");
                    }
                    
                });
            });
            
            
            // Admission Inquiry Submit
            $("#adm_inquiry_form").on('submit',function(e){
                e.preventDefault();
                $('.admission_btn').after('<div id="loaders"><img src="<?=base_url()?>assets/ajax-loader (1).gif"></div>');
                $.ajax({
                    url: '<?= base_url() ?>inquiries/admission_inquiry',
                    method: 'POST',
                    data:new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    success:function(msg)
                    {
                        toastr["success"](msg);
                        $("#adm_inquiry_form")[0].reset(); 
                        setInterval(function(){ $('#loaders').remove(); }, 1500);
                    }
                    
                });
            });
            
            // Job Application Submit
            $("#jobs_application_form").on('submit',function(e){
                e.preventDefault();
                $('.submit_application').after('<div id="loaders"><img src="<?=base_url()?>assets/ajax-loader (1).gif"></div>');
                $.ajax({
                    url: '<?= base_url() ?>Login/save_application',
                    method: 'POST',
                    data:new FormData(this),
                    contentType: false,  
                    cache: false,  
                    processData:false,
                    success:function(msg)
                    {
                        toastr["success"](msg);
                        $("#jobs_application_form")[0].reset(); 
                        setInterval(function(){ $('#loaders').remove(); }, 1500);
                        $(".btn-close").trigger("click");
                    }
                    
                });
            });
            
        });
        
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "100",
            "hideDuration": "2000",
            "timeOut": "6000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
       <?php
       	
		
			
       $earray=array();
       if(!empty($event_array))
       {
           foreach($event_array as $row)
           {
               $array=array();
               $array['title'] = $row['event_title'];
               $array['start'] = $row['event_start_date'];
               $array['end'] = $row['event_end_date'];
           
               $array['className']='purple-bg';
                $earray[]=$array;
           }
       }
       if(!empty($notice_array))
       {
           foreach($notice_array as $row)
           {
               $array=array();
               $array['title'] = $row['notice_title'];
               $array['start'] = $row['create_timestamp'];
              $array['end'] = $row['create_timestamp'];
               
               $array['className']='purple-bg';
                $earray[]=$array;
           }
       }
       ?>
        window.onload=function()
        {
            var date = new Date();
        	var d = date.getDate();
        	var m = date.getMonth();
        	var y = date.getFullYear();
        	$('#calendar_view').fullCalendar({
        		header: {
        			left: 'prev, next',
        			center: 'title',
        			right: 'today, month'
        		},
        		//Add Events
        		events: <?= json_encode($earray) ?>,
        
        	     eventClick: function(info) 
        	     {
                    alert('Event: ' + info.event.title);
                   
                },
        		eventLimit: true,
        	});
        }
        
    </script>
    <!--End of Tawk.to Script-->
    
    <!--jobs section script-->
    <script>
        // using latest bootstrap so, show.bs.modal
        $('#staticBackdrop').on('show.bs.modal', function(e) {
          var job_id = $(e.relatedTarget).data('jobid');
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>login/get_job_details",
            data: ({
                    job_id:job_id,
                    school_id: <?php echo $school_id; ?>,
                    school_db: '<?php echo $school_db; ?>',
                
            }),
            dataType : "html",
            success: function(respose)
            {
              var json = JSON.parse(respose);
              var length = json.length;
              if(length > 0){
                var json = JSON.parse(respose);
                
                var job_title = json[0]['job_title'];
                var carrer_level = json[0]['carrer_level'];
                var experience = json[0]['experience'];
                var job_description = json[0]['job_description'];
                var qualifications = json[0]['qualifications'];
                
                $("#job_id").val(job_id);
                $("#job_title").text(job_title);
                $("#carrer_level").text(carrer_level);
                $("#experience").text(experience);
                $("#job_description").text(job_description);
                $("#qualifications").text(qualifications);
                
              }
            }
          });
          
        });
    </script>
    <!--jobs section script-->
  </body>
</html>
    