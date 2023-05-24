<?php

if($this->session->flashdata('club_updated'))
{
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
}

?>



<style>
    .holiday {
         opacity: 1 !important; 
        background-color: #0992c9 !important;
    }
    :root {
  --white: #ffffff;
  --light: #f0eff3;
  --black: #000000;
  --dark-blue: #1f2029;
  --dark-light: #012b3c;
  --red: #da2c4d;
  --yellow: #f8ab37;
  --grey: #ecedf3;
}

::selection {
  color: var(--white);
  background-color: var(--black);
}
::-moz-selection {
  color: var(--white);
  background-color: var(--black);
}
label.for-checkbox-budget {
    font-family:'ABeeZee' !important;
}
mark {
  color: var(--white);
  background-color: var(--black);
}
.section {
  position: relative;
  width: 100%;
  display: block;
  text-align: center;
  margin: 0 auto;
}
.over-hide {
  overflow: hidden;
}
.z-bigger {
  z-index: 100 !important;
}

.background-color {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: var(--dark-blue);
  z-index: 1;
  -webkit-transition: all 300ms linear;
  transition: all 300ms linear;
}
.checkbox:checked ~ .background-color {
  background-color: var(--white);
}

[type="checkbox"]:checked,
[type="checkbox"]:not(:checked),
[type="radio"]:checked,
[type="radio"]:not(:checked) {
  position: absolute;
  left: -9999px;
  width: 0;
  height: 0;
  visibility: hidden;
}
.checkbox:checked + label,
.checkbox:not(:checked) + label {
  position: relative;
  width: 70px;
  display: inline-block;
  padding: 0;
  margin: 0 auto;
  text-align: center;
  margin: 17px 0;
  margin-top: 100px;
  height: 6px;
  border-radius: 4px;
  background-image: linear-gradient(298deg, var(--red), var(--yellow));
  z-index: 100 !important;
}
.checkbox:checked + label:before,
.checkbox:not(:checked) + label:before {
  position: absolute;
  font-family: "unicons";
  cursor: pointer;
  top: -17px;
  z-index: 2;
  font-size: 20px;
  line-height: 40px;
  text-align: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  -webkit-transition: all 300ms linear;
  transition: all 300ms linear;
}
.checkbox:not(:checked) + label:before {
  content: "\eac1";
  left: 0;
  color: var(--grey);
  background-color: var(--dark-light);
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(26, 53, 71, 0.07);
}
.checkbox:checked + label:before {
  content: "\eb8f";
  left: 30px;
  color: var(--yellow);
  background-color: var(--dark-blue);
  box-shadow: 0 4px 4px rgba(26, 53, 71, 0.25), 0 0 0 1px rgba(26, 53, 71, 0.07);
}

.checkbox:checked ~ .section .container .row .col-12 p {
  color: var(--dark-blue);
}

.checkbox-tools:checked + label,
.checkbox-tools:not(:checked) + label {
  position: relative;
  display: inline-block;
  padding: 20px;
  width: 110px;
  font-size: 14px;
  line-height: 20px;
  letter-spacing: 1px;
  margin: 0 auto;
  margin-left: 5px;
  margin-right: 5px;
  margin-bottom: 10px;
  text-align: center;
  border-radius: 4px;
  overflow: hidden;
  cursor: pointer;
  text-transform: uppercase;
  color: var(--white);
  -webkit-transition: all 300ms linear;
  transition: all 300ms linear;
}
.checkbox-tools:not(:checked) + label {
  background-color: var(--dark-light);
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}
.checkbox-tools:checked + label {
  background-color: transparent;
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
.checkbox-tools:not(:checked) + label:hover {
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
.checkbox-tools:checked + label::before,
.checkbox-tools:not(:checked) + label::before {
  position: absolute;
  content: "";
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 4px;
  background-image: linear-gradient(298deg, var(--red), var(--yellow));
  z-index: -1;
}
.checkbox-tools:checked + label .uil,
.checkbox-tools:not(:checked) + label .uil {
  font-size: 24px;
  line-height: 24px;
  display: block;
  padding-bottom: 10px;
}

.checkbox:checked
  ~ .section
  .container
  .row
  .col-12
  .checkbox-tools:not(:checked)
  + label {
  background-color: var(--light);
  color: var(--dark-blue);
  box-shadow: 0 1x 4px 0 rgba(0, 0, 0, 0.05);
}

.checkbox-budget:checked + label,
.checkbox-budget:not(:checked) + label {
  position: relative;
  display: inline-block;
  padding: 0;
  padding-top: 20px;
  padding-bottom: 20px;
  width: 260px;
  font-size: 22px;
  line-height: 10px;
  font-weight: 700;
  letter-spacing: 1px;
  margin: 0 auto;
  margin-left: 5px;
  margin-right: 5px;
  margin-bottom: 10px;
  text-align: center;
  border-radius: 4px;
  overflow: hidden;
  cursor: pointer;
  text-transform: uppercase;
  -webkit-transition: all 300ms linear;
  transition: all 300ms linear;
  -webkit-text-stroke: 1px var(--white);
  text-stroke: 1px var(--white);
  -webkit-text-fill-color: transparent;
  text-fill-color: transparent;
  color: transparent;
}
.checkbox-budget:not(:checked) + label {
  background-color: var(--dark-light);
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}
.checkbox-budget:checked + label {
  background: linear-gradient(40deg,#2096ff,#05ffa3) !important;
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
.checkbox-budget:not(:checked) + label:hover {
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
.checkbox-budget:checked + label::before,
.checkbox-budget:not(:checked) + label::before {
  position: absolute;
  content: "";
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 4px;
  background-image: linear-gradient(138deg, var(--red), var(--yellow));
  z-index: -1;
}
.checkbox-budget:checked + label span,
.checkbox-budget:not(:checked) + label span {
  position: relative;
  display: block;
}
.checkbox-budget:checked + label span::before,
.checkbox-budget:not(:checked) + label span::before {
  position: absolute;
  content: attr(data-hover);
  top: 0;
  left: 0;
  width: 100%;
  overflow: hidden;
  -webkit-text-stroke: transparent;
  text-stroke: transparent;
  -webkit-text-fill-color: var(--white);
  text-fill-color: var(--white);
  color: var(--white);
  -webkit-transition: max-height 0.3s;
  -moz-transition: max-height 0.3s;
  transition: max-height 0.3s;
}
.checkbox-budget:not(:checked) + label span::before {
  max-height: 0;
}
.checkbox-budget:checked + label span::before {
  max-height: 100%;
}

.checkbox:checked
  ~ .section
  .container
  .row
  .col-xl-10
  .checkbox-budget:not(:checked)
  + label {
  background-color: var(--light);
  -webkit-text-stroke: 1px var(--dark-blue);
  text-stroke: 1px var(--dark-blue);
  box-shadow: 0 1x 4px 0 rgba(0, 0, 0, 0.05);
}

.checkbox-booking:checked + label,
.checkbox-booking:not(:checked) + label {
  position: relative;
  display: -webkit-inline-flex;
  display: -ms-inline-flexbox;
  display: inline-flex;
  -webkit-align-items: center;
  -moz-align-items: center;
  -ms-align-items: center;
  align-items: center;
  -webkit-justify-content: center;
  -moz-justify-content: center;
  -ms-justify-content: center;
  justify-content: center;
  -ms-flex-pack: center;
  text-align: center;
  padding: 0;
  padding: 6px 25px;
  font-size: 14px;
  line-height: 30px;
  letter-spacing: 1px;
  margin: 0 auto;
  margin-left: 6px;
  margin-right: 6px;
  margin-bottom: 16px;
  text-align: center;
  border-radius: 4px;
  cursor: pointer;
  color: var(--white);
  text-transform: uppercase;
  background-color: var(--dark-light);
  -webkit-transition: all 300ms linear;
  transition: all 300ms linear;
}
.checkbox-booking:not(:checked) + label::before {
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}
.checkbox-booking:checked + label::before {
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
.checkbox-booking:not(:checked) + label:hover::before {
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
.checkbox-booking:checked + label::before,
.checkbox-booking:not(:checked) + label::before {
  position: absolute;
  content: "";
  top: -2px;
  left: -2px;
  width: calc(100% + 4px);
  height: calc(100% + 4px);
  border-radius: 4px;
  z-index: -2;
  background-image: linear-gradient(138deg, var(--red), var(--yellow));
  -webkit-transition: all 300ms linear;
  transition: all 300ms linear;
}
.checkbox-booking:not(:checked) + label::before {
  top: -1px;
  left: -1px;
  width: calc(100% + 2px);
  height: calc(100% + 2px);
}
.checkbox-booking:checked + label::after,
.checkbox-booking:not(:checked) + label::after {
  position: absolute;
  content: "";
  top: -2px;
  left: -2px;
  width: calc(100% + 4px);
  height: calc(100% + 4px);
  border-radius: 4px;
  z-index: -2;
  background-color: var(--dark-light);
  -webkit-transition: all 300ms linear;
  transition: all 300ms linear;
}
.checkbox-booking:checked + label::after {
  opacity: 0;
}
.checkbox-booking:checked + label .uil,
.checkbox-booking:not(:checked) + label .uil {
  font-size: 20px;
}
.checkbox-booking:checked + label .text,
.checkbox-booking:not(:checked) + label .text {
  position: relative;
  display: inline-block;
  -webkit-transition: opacity 300ms linear;
  transition: opacity 300ms linear;
}
.checkbox-booking:checked + label .text {
  opacity: 0.6;
}
.checkbox-booking:checked + label .text::after,
.checkbox-booking:not(:checked) + label .text::after {
  position: absolute;
  content: "";
  width: 0;
  left: 0;
  top: 50%;
  margin-top: -1px;
  height: 2px;
  background-image: linear-gradient(138deg, var(--red), var(--yellow));
  z-index: 1;
  -webkit-transition: all 300ms linear;
  transition: all 300ms linear;
}
.checkbox-booking:not(:checked) + label .text::after {
  width: 0;
}
.checkbox-booking:checked + label .text::after {
  width: 100%;
}

.checkbox:checked
  ~ .section
  .container
  .row
  .col-12
  .checkbox-booking:not(:checked)
  + label,
.checkbox:checked
  ~ .section
  .container
  .row
  .col-12
  .checkbox-booking:checked
  + label {
  background-color: var(--light);
  color: var(--dark-blue);
}
.checkbox:checked
  ~ .section
  .container
  .row
  .col-12
  .checkbox-booking:checked
  + label::after,
.checkbox:checked
  ~ .section
  .container
  .row
  .col-12
  .checkbox-booking:not(:checked)
  + label::after {
  background-color: var(--light);
}

.link-to-page {
  position: fixed;
  top: 30px;
  right: 30px;
  z-index: 20000;
  cursor: pointer;
  width: 50px;
}
.link-to-page img {
  width: 100%;
  height: auto;
  display: block;
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('academic_planner'); ?>
        </h3>
    </div>
</div>

<form method="post" action="<?php echo base_url();?>teacher/academic_planner" class="form">
    <div class="row filterContainer" data-step="1" data-position='top' data-intro="Please select the filters and press Filter button to get specific records">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
            <select id="month_year" name="month_year" class="form-control" >
                <?php
                    $academic_year_id= intval($_SESSION['academic_year_id']);
                    $qur_rr=$this->db->query("select * from ".get_school_db().".acadmic_year where school_id=".$_SESSION['school_id']." and academic_year_id=$academic_year_id")->result_array();
                    $start_date=$qur_rr[0]['start_date'];
                    $end_date=$qur_rr[0]['end_date'];
                    echo month_year_option($start_date,$end_date,$subject_month_year);
                ?>
            </select>
        </div>    
	    </div>
	    <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <!-- <label id="select_selection"></label>  -->
                <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" >
                    <?php 
                    echo get_teacher_dep_class_section_list($teacher_section, $section);?>
                </select>
            </div>    
    	</div>
    	
	    <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <select name="subject" id="subject_list" class="form-control" >
                    <option value=""><?php echo get_phrase('select_subject');?></option>
                </select>
            </div>    
    	</div>
	    
	    <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
            <input type="submit" name="submit" value="<?php echo get_phrase('Filter');?>" class="btn btn-primary"/>
            <?php
            if($filter)
            {?>
                <a href="<?php echo base_url();?>teacher/academic_planner" class="btn btn-danger pull-right" >
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                    </a>
            <?php
            }
            ?>
            <div id="error_end1" style="color:red"></div>
        </div>    
	    </div>
	</div>
</form>

<div class="col-lg-12 col-md-12  acdmic-planr">
<?php
if (count($planner_arr)>0)
{
    $date_mulit_arr = array();
    
    foreach ($planner_arr as $row) 
    {

        $date_mulit_arr[$row['start']][$row['subject'].' - '.$row['subject_code']][] = array(
							'planner_id'    => $row['planner_id'],
                            'subject'       => $row['subject'].' - '.$row['subject_code'],
                            'title'         => $row['title'],
                            'objective'     => $row['objective'],
                            'requirements'  => $row['requirements'],
                            'required_time' => $row['required_time'],
                            'assesment'     => $row['assesment'],
                            'detail'        => $row['detail'],
                            'attachment'    => $row['attachment'],
                            'start_date'    => $row['start'],
                        );
    }
    $custom_css=array(1=>'current-day',2=>'holiday');  
    $current_date = date('Y-m-d');   
    
    $check_today = $this->db->query("select sub.name as subject,sub.code as subject_code, ap.* 
            from ".get_school_db().".academic_planner ap
            inner join ".get_school_db().".subject sub on sub.subject_id = ap.subject_id
            where ap.subject_id in ($subss) and ap.is_active = 1 and ap.school_id = ".$_SESSION['school_id']." and ap.start = '".date('Y-m-d')."' ");
    if($check_today->num_rows() == 0)
    {
        ?>
        <div class="panel-group" id="accordion" style="margin-bottom:2px;" data-step="2" data-position='top' data-intro="academic planner details">
            <div class="panel panel-default">
                <div class="panel-heading" style="border-bottom: 1px solid #CCC;">
                    <h4 class="panel-title" style="width:100%">
                        <a class="toggle_div" data-toggle="collapse" data-parent="#accordion" href="#collapse-not-date">
                            <span class="myttl">
                                <b class="text-danger">Today</b> <?php echo date("d-M-Y"); ?>
                                <span class="acspan">(<?= date("D")?>)</span>
                            </span>
                        </a>
                    </h4>
                </div>

                <div id="collapse-not-date" class="panel-collapse collapse">
                    <div class="panel-body" style="border-top: 2px solid #00a651;">
                        <h3>There is no academic planner on this selected date</h3>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }        
    
    foreach ($date_mulit_arr as $key => $planner) 
    { 
    	$current="";
    	$current1="";
    	if($key==$current_date)
		{
			$current=$custom_css[1];
		}
		
    	$q1="select * from ".get_school_db().".holiday where start_date<='$key' and end_date>='$key' AND school_id=".$_SESSION['school_id']." ";	
        $qurrr=$this->db->query($q1)->result_array();
	    if(count($qurrr)>0){
		    $current1 = $custom_css[2];
		}
		  $showcount="";
		$bgcolor="";
   if(count($planner)>0)
   {
       $showcount="";
       $bgcolor="#0992c9";
   }
    	?>
        <div class="panel-group" id="accordion" style="margin-bottom:2px;">
            <div class="panel panel-default">
                
                <div class="panel-heading <?php echo $current.' '.$current1;?>" style="background-color:<?= $bgcolor ?>;border-bottom: 1px solid #CCC;">
                    <h4 class="panel-title" style="width:100%">
                        <a class="toggle_div" value="<?php echo convert_date($key);?>" id="<?php echo convert_date($key);?>"  data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo convert_date($key); ?>">
                            <span class="myttl">
                                <?php  echo convert_date($key); ?>
                                <span class="acspan"><?=  $showcount ?> (<?php echo date('l', strtotime($key));  ?>)</span>
                            </span>
                        </a>
                    </h4>
                </div>

                <div id="collapse<?php echo convert_date($key); ?>" class="panel-collapse collapse">
                    <div class="panel-body" style="border-top: 2px solid #00a651;">
                        <?php foreach ($planner as $inner_key => $inner) { ?>
                            <?php 
                              if(convert_date($current_date) == convert_date($key) ){ ?>
                                <div class="col-xl-10 pb-5">
                                <p class="text-danger">choose one option to assign this planner activity response </p>
                                <div class="planner_activity_msg"></div>
                                <?php 
                                    $check_planner_activity = $this->db->query("SELECT planner_id,status FROM ".get_school_db().".teacher_planner_activity WHERE planner_id = '".$inner[0]['planner_id']."' AND teacher_id = '".$_SESSION['user_id']."' AND school_id = '".$_SESSION['school_id']."' ")->result_array();
                                    if(count($check_planner_activity) > 0)
                                    {
                                ?>
                                    <input class="checkbox-budget" type="radio" name="budget<?=$inner[0]['planner_id']?>" id="budget-<?=$inner[0]['planner_id']?>-1" data-id="<?=$inner[0]['planner_id']?>" data-value="FOR CLASS WORK" <?php if($check_planner_activity[0]['status'] == "FOR CLASS WORK"){ echo "checked";} ?> >
            						<label class="for-checkbox-budget" for="budget-<?=$inner[0]['planner_id']?>-1">
            							<span data-hover="For Class Work">For Class Work</span>
            						</label>
            						<input class="checkbox-budget" type="radio" name="budget<?=$inner[0]['planner_id']?>" id="budget-<?=$inner[0]['planner_id']?>-2" data-id="<?=$inner[0]['planner_id']?>" data-value="FOR DIARY" <?php if($check_planner_activity[0]['status'] == "FOR DIARY"){ echo "checked";} ?> >
            						<label class="for-checkbox-budget" for="budget-<?=$inner[0]['planner_id']?>-2">							
            							<span data-hover="For Diary">For Diary</span>
            						</label>
        						<?php }else{ ?>
        						    <input class="checkbox-budget" type="radio" name="budget<?=$inner[0]['planner_id']?>" id="budget-<?=$inner[0]['planner_id']?>-1" data-id="<?=$inner[0]['planner_id']?>" data-value="FOR CLASS WORK">
            						<label class="for-checkbox-budget" for="budget-<?=$inner[0]['planner_id']?>-1">
            							<span data-hover="For Class Work">For Class Work</span>
            						</label>
            						<input class="checkbox-budget" type="radio" name="budget<?=$inner[0]['planner_id']?>" id="budget-<?=$inner[0]['planner_id']?>-2" data-id="<?=$inner[0]['planner_id']?>" data-value="FOR DIARY">
            						<label class="for-checkbox-budget" for="budget-<?=$inner[0]['planner_id']?>-2">							
            							<span data-hover="For Diary">For Diary</span>
            						</label>
        						<?php } ?>
    						</div>
    						<?php 
                              }
                              else if(convert_date($current_date) < date('Y-m-d'))
                              {
                                    $check_planner_activity = $this->db->query("SELECT planner_id,status FROM ".get_school_db().".teacher_planner_activity WHERE planner_id = '".$inner[0]['planner_id']."' AND teacher_id = '".$_SESSION['user_id']."' AND school_id = '".$_SESSION['school_id']."' ")->result_array();
                                    if(count($check_planner_activity) == 0)
                                    {
                            ?>
                                        <button type="button" class="btn btn-primary OpenPlannerPopup" planner-id="<?=$inner[0]['planner_id']?>" >
                                            Mark Planner Activity
                                        </button>
                            <?php
                                    } 
                              }
    						?>
                            <div class="panel-heading">
                                <h4>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo str_replace(' ','-',convert_date($key).'-'.$inner_key); ?>" class="">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i> 
                                        <b><?= $inner_key; ?></b>
                                    </a>
                                </h4>
                            </div>
                            <div id="<?php echo str_replace(' ','-',convert_date($key).'-'.$inner_key); ?>" class="panel-collapse in" style="height: auto;">
                                <?php
                                foreach ($inner as $sub_key => $sub_arr) 
                                {
									?>
                                    <div class="panel-heading" style="border:1px solid #e4e4e4; margin-bottom:5px; background:#f6f6f6;">
                                        <h5>
                                            <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo str_replace(' ','-',convert_date($key).'-'.$inner_key.'-'.$sub_key); ?>" class="">
                                                <?php echo $sub_arr['title']; ?>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="<?php echo str_replace(' ','-',convert_date($key).'-'.$inner_key.'-'.$sub_key); ?>" class="panel-collapse in" style="height: auto;">
                                        <div class=" panel-default">
                                            <div class="panel-body" style="border-bottom:1px solid #e4e4e4; border-top:none; margin-bottom:5px;">
                                                <div class="row tt2">                                        
                                                <div class="col-sm-12">
                                                    <div>
                                                    <strong><?php echo get_phrase('required_time');?>: </strong><?php echo $sub_arr['required_time']; ?> 
                                                    </div>
                                                    <div>
                                                        <strong><?php echo get_phrase('attachement');?>: </strong>
                                                        <?php
                                                        if ($sub_arr['attachment']!= '')
                                                        {?>
                                                            <a target="_blank" href="<?php echo display_link($sub_arr['attachment'],'academic_planner');?>"> <i class="fa fa-download" aria-hidden="true"></i></a>
                                                        <?php
                                                        }
                                                        else
                                                        {
                                                            echo get_phrase('no_attachment'); 
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                    
                                                <div class="inner-tabs">
                                                <ul class="nav nav-tabs" role="tablist" style="width:100% !important;">
                                                    <li role="presentation" ><a href="#assessment<?php echo $sub_arr['planner_id']; ?>" aria-controls="home" role="tab" data-toggle="tab" class="active"><?php echo get_phrase('assesment');?></a></li>
                                                    <li role="presentation"><a href="#objectives<?php echo $sub_arr['planner_id']; ?>" aria-controls="profile" role="tab" data-toggle="tab"><?php echo get_phrase('objectives');?></a></li>
                                                    <li role="presentation"><a href="#requirement<?php echo $sub_arr['planner_id']; ?>" aria-controls="messages" role="tab" data-toggle="tab"><?php echo get_phrase('requirements');?></a></li>
                                                    <li role="presentation"><a href="#detail<?php echo $sub_arr['planner_id']; ?>" aria-controls="settings" role="tab" data-toggle="tab"><?php echo get_phrase('detail');?></a></li>
                                                </ul>

                                                  <!-- Tab panes -->
                                                  <div class="tab-content" style="min-height:165px;">
                                                    <div role="tabpanel" class="tab-pane active" id="assessment<?php echo $sub_arr['planner_id']; ?>"><strong></strong><?php echo $sub_arr['assesment']; ?> </div>
                                                    <div role="tabpanel" class="tab-pane" id="objectives<?php echo $sub_arr['planner_id']; ?>"><strong></strong><?php echo $sub_arr['objective']; ?> </div>
                                                    <div role="tabpanel" class="tab-pane" id="requirement<?php echo $sub_arr['planner_id']; ?>"><strong> </strong><?php echo $sub_arr['requirements']; ?> </div>
                                                    <div role="tabpanel" class="tab-pane" id="detail<?php echo $sub_arr['planner_id']; ?>"><strong></strong><?php echo $sub_arr['detail']; ?> </div>
                                                  </div>
                                                </div>
                                                    
                                                </div>
                                            </div>
                                            </div>
                                    </div>
                                    
                                <?php
                                }
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}
else 
{
    echo get_phrase('no_planner_added');
}
?>
</div>

<script>
    jQuery(document).ready(function () 
    {
        jQuery('.dcs_list').on('change', function (){
            var id=this.id;
            var selected = jQuery('#'+ id +' :selected');
            var group = selected.parent().attr('label');
            jQuery(this).siblings('label').text(group);
        });
        
    });

</script>

<script>
    $(document).ready(function()
    {
        function toggle_func()
        {
            
        }
        $("#dep_c_s_id").change(function()
        {
            var section_id=$(this).val();
            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');
            $.ajax({
                type: 'POST',
                data: {section_id:section_id},
                url: "<?php echo base_url();?>teacher/get_diary_subject_list",
                dataType: "html",
                success: function(response) {
                    $(".loader_small").remove();
                    $("#subject_list").html(response);      
                }
            });
        });
        var section_id=$('#dep_c_s_id').val();
        $.ajax({
            type: 'POST',
            data: {section_id:section_id},
            url: "<?php echo base_url();?>teacher/get_diary_subject_list/<?php echo $subject_id_selected ?>",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#subject_list").html(response);      
            }
        });
        
        $(".checkbox-budget").on("click",function(){
            var planner_id = $(this).data('id');
            var status = $(this).data('value');
            if(confirm('Are you sure')){
                $.ajax({
                    type: 'POST',
                    data: {planner_id:planner_id,status:status},
                    url: "<?php echo base_url();?>academic_planner/save_teacher_planner_activity",
                    dataType: "JSON",
                    success: function(response) {
                        $(".planner_activity_msg").html('<div class="alert alert-success">' + response.message + '</div>');
                    }
                });
            }else{
                return false;
            }
        });
        
        
        $('.OpenPlannerPopup').on('click' , function(){
                   $('#acad_planner_id').val($(this).attr('planner-id'));
                   $('#plannerModel').modal('show'); 
        });
        
        

        

    });
</script>


<!-- Modal -->
<div class="modal fade in" id="plannerModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="font-size:25px;"><b>Activity Reason</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form method="post" action="<?php echo base_url()  ?>academic_planner/request_planner_change/">
      
          <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="field-2">
                            <?php echo get_phrase('reason_for_changing_the_planner_activity');?>
                        </label>
                        <textarea class="form-control" rows="5" name="reason" id="reason" maxlength="1000" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="field-2">
                            <?php echo get_phrase('status');?>
                        </label>
                        <select name="status" id="status" class="form-control" required>
                            <option>Select Status</option>
                            <option value="For Diary">For Diary</option>
                            <option value="FOR CLASS WORK">FOR CLASS WORK</option>
                        </select>
                    </div>    
                </div>     
          </div>
          <div class="modal-footer">
             <button type="submit" class="modal_save_btn">Submit</button>
            <button type="button" class="modal_cancel_btn" data-dismiss="modal">Close</button>
            <input type="hidden" id="acad_planner_id" name="acad_planner_id" value="" />
          </div>
      
      </form>  
      
    </div>
  </div>
</div>

