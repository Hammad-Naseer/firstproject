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
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>
    
<style>
    .teacher-listing {
        padding-left: 0px;
    }
    
    .teacher-listing li {
        display: inline-block;
        padding: 10px 20px 0px 0px;
    }
    
    .teacher-listing li i.fa {
        color: #404040;
        font-weight: 100;
        font-family: "Helvetica Neue", Helvetica, "Noto Sans", sans-serif, Arial, sans-serif, FontAwesome;
    }
    
    .teacher-listing li i.fa.fa-dot-circle-o:before {
        padding-right: 5px;
    }
    
    .subject-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    
    .subject-list1 {
        list-style-type: none;
        padding-left: 5px;
        margin-left: 5px;
    }
    
    .subject-list1 {
        list-style-type: none;
        padding-left: 10px;
        margin-left: 10px;
    }
    
    .subject-list li {
        padding: 0;
        margin: 0;
    }
    
    .sect {
        color: #737373;
    }
    
    .teac {
        color: #737373;
        padding-right: 32px;
    }
    
    .comp {
        color: #737373;
        padding-right: 6px;
    }
    
    .mytbl {
        color: #a1a1a1;
    }
    
    .fs11 a {
        color: #507895 !important;
        font-size: 11px;
    }
    
    .page-body .datatable.table tbody td,
    .page-body .datatable.table tbody th {
        vertical-align: top;
    }
    .themecolor{
    color: #ffffff !important;
}

</style>
    <?php


$subj_categ=$this->input->post('subj_categ_select'); 
if(isset($subj_categ) && $subj_categ > 0)
{
	$subj_query=" AND sc.subj_categ_id = $subj_categ";
}
$q="SELECT s.*,sc.title as subj_categ_title FROM ".get_school_db().".subject s
		LEFT JOIN ".get_school_db().".subject_category sc
		ON s.subj_categ_id=sc.subj_categ_id
		 WHERE s.school_id=".$_SESSION['school_id']. $subj_query;
$subjects=$this->db->query($q)->result_array();
?>




<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('subject'); ?>
        </h3>
    </div>

</div>

<form action="" method="post" id="filter" class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;" data-step="3" data-position='top' 
                             data-intro="select subject category press filter button get record specific subjects">
    <div class="row filterContainer">
        <div class="col-lg-6 col-md-6 col-sm-4">
        	<div class="form-group">
        	        <label for="subj_categ_select"><b>Select Category</b></label>
        	        <select id="subj_categ_select" class="form-control" name="subj_categ_select" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <?php echo get_subj_category($this->input->post('subj_categ_select'));?>
                    </select>
        	</div>	
        </div>
        <div class="col-lg-6 col-md-6 col-sm-4" style="margin-top: 28px;">
            <div class="form-group">
        	   <label for="btn_sub"><b>&nbsp;</label>
                <input type="submit" value="Filter" class="btn btn-primary" id="btn_sub">
                <a href="<?php echo base_url(); ?>subject/subjects/" 
                    <?php $subj_cat=$this->input->post('subj_categ_select');
                    if(isset($subj_cat) && ($subj_cat > 0)) { } else {
	 	            ?>  
	 	            style="display:none;"
	                <?php }  ?>
                   class="btn btn-danger" id="btn_remove" style="padding:5px 5px !important; "><i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?>
                </a>
            </div>
        </div>    
    </div>
</form>    

       
<div id="table"></div>

<div class="col-md-12">
    <div class="tab-content">
        
        <div class="tab-pane box active" id="list">
            <div id="error"></div>   <!-- id="admin_subject2" -->
            <table class="table table-bordered datatable table-striped table-responsive table_export" id="" style="vertical-align: top;" data-step="4" data-position='top' data-intro="subjects record">
                <thead>
                    <tr>
                        <th style="width:32px;">#</th>
                        <th style="width: 300px !important;">
                            <div>
                                <?php echo get_phrase('subject');?>
                            </div>
                        </th>
                        <th style="width: 300px !important;">
                            <div>
                                <?php echo get_phrase('teacher');?>
                            </div>
                        </th>
                        <th style="width: 300px !important;">
                            <div>
                                <?php echo get_phrase('class');?>-<?php echo get_phrase('section');?>
                            </div>
                        </th>
                        <th style="width: 90px !important;">
                            <div>
                                <?php echo get_phrase('options');?>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
        			$count = 1;
        			$c = 1;
			
			        foreach($subjects as $row):?>
                    <tr id="<?php echo $row['subject_id']; ?>">
                        <td class="td_middle"><?php echo $c++;?></td>
                        <td class="td_middle">
                            <div>
                                <span style="font-size: 14px;font-weight: bold;">
                                    <?php echo $row['name'];if($row['code']){echo ' ('.$row['code'].')';  }?>
                                </span>
                                <div>
                                    <?php echo "<strong>".get_phrase('category').":</strong> ".$row['subj_categ_title']; ?>
                                </div>
                                
                                <strong>   <?php echo get_phrase('components');?>:</strong>
                                <div style="margin-left: 20px;">
                                <?php
                                $compArr=$this->db->query("select sc.subject_component_id, sc.title AS component, sc.percentage,sc.subject_id from ".get_school_db().".subject_components sc where sc.subject_id=".$row['subject_id']." AND sc.school_id=".$_SESSION['school_id']." 
                                    order by sc.subject_component_id asc
                                    ")->result_array();
                                if (count($compArr) > 0)
                                {
                                    foreach ($compArr as $key => $comp) 
                                    {
                                        echo '<i class="fa fa-dot-circle-o" aria-hidden="true"></i>';
                                        
                                        echo $comp['component'].' - '.$comp['percentage'].' Marks<br>';
                                        
                                    }
                                }
                                ?>
                                </div>
                                <br>
                                <?php if (right_granted('subject_manage')){ ?>
                                <span class="fs11">
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_subject_component/<?php echo $row['subject_id'];?>/<?php echo $row['name'].'-'.$row['code'];?>');"  style="color:#507895 !important;">
                                        <i class="fa fa-plus-square" aria-hidden="true" style="padding-right:0px;"></i>
                                        <?php echo get_phrase('manage_components');?>
                                    </a>
                                    <a style=" color:#507895 !important;cursor: pointer;" href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_all_components/<?php echo $row['subject_id'];?>');">
                                        <i class="fa fa-eye" aria-hidden="true" style="padding-right:0px;  padding-left:11px;"></i>    <?php echo get_phrase('View_components');?>
                                    </a>
                                    <br>
                                    <a style=" color:#507895 !important;cursor: pointer;" href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/upload_sallybus_modal/<?php echo $row['subject_id'];?>/<?php echo $row['name'].'-'.$row['code'];?>');">
                                        <i class="fa fa-file" aria-hidden="true" style="padding-right:0px;  padding-left:11px;"></i>    <?php echo get_phrase('add_syllabus');?>
                                    </a>
                                </span>
                                <?php
                                    $get_sallybus = $this->db->where("subject_id",$row['subject_id'])->where("school_id",$_SESSION['school_id'])->get(get_school_db().".subject_sallybus");
                                    if($get_sallybus->num_rows() > 0) :
                                ?>
                                <br>
                                <h5 style="text-align:left;"><b>View Syllabus</b></h5>
                                <ul style="text-align:left;">
                                    <?php 
                                        foreach($get_sallybus->result() as $data):
                                            $year_title = academic_year_title($data->academic_year_id);
                                    ?>        
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?=base_url()?>modal/popup/view_sallybus_modal/<?=$data->subject_sallybus_id?>/<?php echo $row['name'].'-'.$row['code'];?>');">Syllabus - <?= $year_title[0]['title']?></a>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>subject/sallybus_delete/<?php echo $data->subject_sallybus_id;?>');" class="text-danger float-right" ><i style="color:tomato !important;" class="fas fa-trash"></i></a>
                                        </li>
                                    <?php
                                        endforeach;    
                                    ?>
                                </ul>
                                <?php endif; } ?>
                            </div>
                            
                        </td>
                        <td>
                            <?php
                                $teachers=subject_teacher($row['subject_id']);
                                if(sizeof($teachers)>0)
                                {
            						$i = 1;
            						foreach($teachers as $all)
            						{
            							$week="select count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
            							inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
            							inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
            							where st.teacher_id=".$all['teacher_id']." and st.school_id=".$_SESSION['school_id']."";
            							
            							$periods_per_week=$this->db->query($week)->result_array();
            							
            							$day1="select max(count) from (SELECT count(st.teacher_id) as count FROM ".get_school_db().".time_table_subject_teacher ttst 
            							inner join ".get_school_db().".class_routine cr on cr.class_routine_id=ttst.class_routine_id 
            							inner join ".get_school_db().".subject_teacher st on st.subject_teacher_id=ttst.subject_teacher_id 
            							where st.teacher_id=".$all['teacher_id']." and st.school_id=".$_SESSION['school_id']." group by cr.day ORDER BY cr.day ASC) as counts";
            							
            							$perids_per_day=$this->db->query($day1)->result_array();
            							$count=$perids_per_day[0]['max(count)']?$perids_per_day[0]['max(count)']:0;
            							// echo $row['periods_per_day'].' ( '.$count.' )';
            							$q2="select s.*,d.title as designation from ".get_school_db().".staff s inner join ".get_school_db().".designation d on s.designation_id=d.designation_id where s.school_id=".$_SESSION['school_id']." and d.is_teacher=1 and s.staff_id=".$all['teacher_id']."";
            							$teachers=$this->db->query($q2)->result_array();
            							?>
                                            <div>
                                                <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                                <?php echo $all['teacher_name'] ; ?>
                                                <span style="font-size:11px;">(<?php echo $all['designation'] ; ?>)</span>
                                            </div>
                                    <?php
                                    }
                                }
                            ?>
                                <div>
                                    <span class="fs11">
           		         
           		                    <?php if (right_granted('subject_manage'))
           		                    {?>
           		                    <br>
                                      <span> <a href="#" data-step="5" data-position='top' data-intro="assign a subject of teacher" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_subject_teacher/<?php echo $row['subject_id'];?>');">
                                              <i class="fas fa-user-plus" aria-hidden="true" style="padding-right:0px;"></i>
                                                 <?php echo get_phrase('assign_subject_to_teacher');?>
                                                </a>
                                       </span>
                                    <?php }?>
                                    <a href="#" data-step="6" data-position='top' data-intro="view assigned teachers" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_teacher_details/<?php echo $row['subject_id'];?>');">
                                        <i class="fa fa-eye" aria-hidden="true" style="padding-right:0px;  padding-left:11px;"></i>
                                        <?php echo get_phrase('view_subject_teachers');?>
                                    </a>
                                    </span>
                                </div>
                        </td>
                        <td>
                            <?php
                                $ret_value= get_subject_section($row['subject_id']);  

                                $new = array();
	
                            	foreach ($ret_value as $a){
                            	    $new[$a['department_name']][$a['section_id']] = $a['class_name'].' - '.$a['section_name'];
                            	}
                                $i =1;
                                foreach($new as $dep=>$secArry)
                                {
                                    foreach($secArry as $k=>$section)
                                    {
                                        $q2="select subject_id,section_id,periods_per_week,periods_per_day from ".get_school_db().".subject_section where school_id=".$_SESSION['school_id']." AND section_id=".$k." AND subject_id=".$row['subject_id']."";
                                        $selected=$this->db->query($q2)->result_array();
                                        
                                        foreach($selected as $sel){
                                            $selArr['subject_id'][]=$row['subject_id'];
                                            $selArr[$sel['subject_id']]['periods_per_day']=$sel['periods_per_day'];
                                            $selArr[$sel['subject_id']]['periods_per_week']=$sel['periods_per_week'];
                                        }
                                        echo '<i class="fa fa-dot-circle-o" aria-hidden="true"></i>';
                                        echo $section ;
                                        echo "<br>";
                                    }
                                }
                                ?>
                                <div>
                                    <span class="fs11">
                                        <?php
                                        if (right_granted('subject_manage'))
                                        {
                                        ?>
                                        <br>
                                          <a href="#" data-step="7" data-position='top' data-intro="assign a subject of class-section" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_assign_section/<?php echo $row['subject_id'];?>');">
                                        <i class="fa fa-plus-square-o" aria-hidden="true" style=" padding-right:0px;"></i>
                                        <?php echo get_phrase('assign_subject_to_section');?> 
                                        </a>	
                                        <?php
                                        }
                                        ?>
                                         <a href="#" data-step="8" data-position='top' data-intro="view assigned class-section" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_section_detail/<?php echo $row['subject_id'];?>');">
                                        <i class="fa fa-eye" aria-hidden="true" style="padding-right:0px;  padding-left:11px;"></i>  
                                        <?php echo get_phrase('view_subject_sections');?> 
                                        </a>	
                                    </span>
                                </div>
                                <div id="mysection<?php echo $row['subject_id'];?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">
                                                
                                                <?php echo get_phrase('veiw_section_for');?>
                                                
                                                    <?php echo $row['name'];if($row['code']){echo ' ('.$row['code'].')';  }?>
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                                                                <!--   <table role="dialog" class=" mytbl table table-bordered table-striped table-responsive text-center collapse mytbl " id="vsection<?php //echo $row['subject_id'];?>" >
                                                <tr style="font-weight:bold;text-align:center;">   
                                                <td></td>    <td></td>	<td colspan="2">Periods per Week</td><td colspan="2">Periods per Day</td>
                                                </tr>
                                                
                                                <tr style="font-weight:bold;text-align:center;">   
                                                <td>#</td>	<td style="text-align: left;"> Class - Section</td><td>Assigned</td><td>Total</td><td>Assigned</td><td>Total</td>		
                                                </tr> 								         								         								         				<?php						
                                                				
                                                $ret_value= get_subject_section($row['subject_id']); 
                                                if(count($ret_value)==0)
                                                {
                                                    echo "<strong>No Section assigned</strong>";
                                                }
                                                //print_r($ret_value);
                                                
                                                $new = array();
                                                	
                                                foreach ($ret_value as $a){
                                                $new[$a['department_name']][$a['section_id']] = $a['class_name'].' - '.$a['section_name'];
                                                }
                                                $i =1;
                                                foreach($new as $dep=>$secArry)
                                                {
                                                    foreach($secArry as $k=>$section)
                                                    {
                                                        $q2="select subject_id,section_id,periods_per_week,periods_per_day from ".get_school_db().".subject_section where school_id=".$_SESSION['school_id']." AND section_id=".$k." AND subject_id=".$row['subject_id']."";
                                                        $selected=$this->db->query($q2)->result_array();
                                                        
                                                        foreach($selected as $sel){
                                                            $selArr['subject_id'][]=$row['subject_id'];
                                                            $selArr[$sel['subject_id']]['periods_per_day']=$sel['periods_per_day'];
                                                            $selArr[$sel['subject_id']]['periods_per_week']=$sel['periods_per_week'];
                                                        }
                                                        echo '<tr>';
                                                        echo '<td>'.$i++.'</td>';
                                                        //echo '<td>'.$dep.'</td>';
                                                        echo '<td style="text-align:left;">'.$section.'</td>';
                                                        echo '<td>'.(subject_count_class_routine_week($row['subject_id'],$k)?subject_count_class_routine_week($row['subject_id'],$k):0).'</td>';
                                                        echo '<td>'.$selArr[$row['subject_id']]['periods_per_week'].'</td>';		
                                                        echo '<td>'.(subject_count_class_routine_day($row['subject_id'],$k)?subject_count_class_routine_day($row['subject_id'],$k):0).'</td>';
                                                        echo '<td>'.$selArr[$row['subject_id']]['periods_per_day'].'</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>  
                                                </table>   	-->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                 <?php echo get_phrase('close');?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </td>
                        <td class="td_middle">
                    	<?php 
			            if (right_granted(array('subject_manage', 'subject_delete')))
			            {?>
                            <div class="btn-group" data-step="9" data-position='left' data-intro="subject delete / edit option">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action'); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <!-- Assign Teacher LINK -->
                                    <?php 
                                    if (right_granted('subject_manage'))
                                    {?>
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_subject/<?php echo $row['subject_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                    <?php 
                                	} 
                                    if (right_granted('subject_delete')){?>
                                        <li class="divider"></li>
                                        <!-- DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>subject/subjects/delete/<?php echo $row['subject_id'];?>/<?php echo $row['attachment'];?>');">
                                                <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
                                    <?php 
                                    }  ?>
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
        </div>
    </div>
</div>

<!--Datatables Add Button Script-->

<?php if(right_granted(array('subject_manage', 'subject_delete'))){ ?>
<script>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_manage_subj_category/")';
    var datatable_btn_url2 = 'showAjaxModal("<?php echo base_url();?>modal/popup/modal_add_subject/")';
    var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='1st Step: press this button add new subject category' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('manage_subject_category');?></a> <a href='javascript:;' onclick="+datatable_btn_url2+" data-step='2' data-position='left' data-intro='2nd Step: press this button add new subject' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_new_subject');?></a>";    
</script>
<?php } ?>