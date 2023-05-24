<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('datesheet'); ?>
        </h3>
    </div>
</div>

<form method="post" action="<?php echo base_url();?>teacher/exam_routine" class="form">
    
    <div class="row filterContainer pb-1 px-3" data-step="1" data-position='top' data-intro="Please select the filter and press Show Date Sheet button to get specific records">
        
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" required>
                    <?php echo get_teacher_dep_class_section_list($teacher_section, $section_id);?>
                </select>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <input type="submit" name="submit" value="<?php echo get_phrase('show_datesheet');?>" class="btn btn-primary"/>
                    <?php if($filter) {?>
                        <a href="<?php echo base_url();?>teacher/exam_routine" class="btn btn-danger " >
                            <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                        </a>
                    <?php } ?>
            </div>
        </div>
    
    </div>
</form>
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

<?php
if ($section_id > 0)
{
?>
<div class="col-lg-12 col-md-12 student-panel" data-step="2" data-position='top' data-intro="Collapse this to view the datesheet">
        <div class="panel-group joined" id="accordion-test-2">
            <?php 
            $toggle = false;
            $exams = $this->db->query("select e.* 
                    from ".get_school_db().".exam e 
                    inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id 
                    where e.school_id=".$_SESSION['school_id']." 
                    and y.yearly_terms_id=".$_SESSION['yearly_term_id']." 
                    and y.academic_year_id=".$_SESSION['academic_year_id']."
                    order by e.start_date DESC
                ")->result_array();
            foreach($exams as $row)
            {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse<?php echo $row['exam_id'];?>">
                            <i class="entypo-rss"></i> <?php echo $row['name'];?> (<b><?php echo date('d M Y',strtotime($row['start_date']));?></b> - <b><?php echo date('d M Y',strtotime($row['end_date']));?></b>)
                            <?php ?>
                        </a>
                    </h4>
                </div>
                
                <div id="collapse<?php echo $row['exam_id'];?>" class="panel-collapse collapse <?php if($toggle){echo 'in';$toggle=false;}?>">
                    <div class="panel-body">
                        <table cellpadding="0" cellspacing="0" border="0"  class="table table-bordered">
                        <thead>
                            <tr>
                                <td><?php echo get_phrase('date');?></td>
                                <td><?php echo get_phrase('day');?></td>
                                <td><?php echo get_phrase('subject');?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $custom_css=array(1=>get_phrase('current-day'),2=>get_phrase('holiday'));  
                            $statuslist=array(1=>get_phrase('present'), 2=>get_phrase('absent'), 3=>get_phrase('leave'),4=>get_phrase('weekend'));    
                            $current_date=date('d-M-Y');   
                            
                            $date_from = strtotime($row['start_date']);
                            $date_to = strtotime($row['end_date']);
                            $oneDay = 60*60*24;
                            for($i=$date_from; $i<=$date_to; $i=$i+$oneDay)
                            {
                            	$current = "";
                            	$current1 = "";
                                $day=date("l", $i);
                                //echo date("m j, Y", $i);
                                //$date3=convert_date(date("F j, Y", $i));
                                $date1=convert_date(date("l F j, Y", $i));
                                $date3=date('Y-m-d',strtotime($date1));
                                if($date1==$current_date)
								{
									$current=$custom_css[1];
								} 
								$qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date3' and end_date>='$date3' AND school_id=".$_SESSION['school_id']." ")->result_array();

                            	$statuslist_css="";
                            	
                            	if(count($qurrr)>0)
                                {
                                	$current1=$custom_css[2];
                            			
                            	}
                            	elseif($day=='Saturday' or $day=='Sunday')
                                {
                                	
                                $statuslist_css=$statuslist[4];
                                    //echo "style='background-color:green;'"; 
                                }	
                                  echo '<tr class="gradeA '.$current.' '.$current1.' '.$statuslist_css.'">'; 
                                            ?>
                                
                                    <td width="100">
                                        <?php echo $date1;?>
                                    </td>
                                    <td width="100">
                                        <?php echo $day;?>
                                    </td>
                                    <td>
                                    <?php
                                    $q="select er.* from ".get_school_db().".exam_routine er where er.school_id=".$_SESSION['school_id']." and er.exam_id=".$row['exam_id']." and section_id=".$section_id." and exam_date = '".$date3."' ";
                                    $routines=$this->db->query($q)->result_array();
                                    
                                    // echo "<pre>";
                                    // print_r($routines);
                                    // echo implode(',', $teacher_subject);
                                    
                                    foreach($routines as $row2)
                                    {?>
                                        <div class="btn-group" id="er<?php echo $row2['exam_routine_id'];?>">
                                        <?php 
                                        if(strtotime($row2['exam_date'])==$i)
                                        { 
                                            $my_sub = "";
                                            $aa = "";
                                            // echo $row2['subject_id'].'<br>';
                                            if (in_array($row2['subject_id'], $teacher_subject)){ 
                                                $my_sub = "green";
                                                $aa = '<span class="'.$my_sub.'"> (My Subject)</span>';
                                            }
                                            
                                            echo '<span class="'.$my_sub.'">'.get_subject_name($row2['subject_id']);
                                            echo ' ('.$row2['time_start'].'-'.$row2['time_end'].')';
                                            echo '</span>';
                                            echo $aa;
                                            
                                        }
                                        
                                        ?>
                                        
                                        </div>
                                    <?php 
                                    }?>
                                    </td>
                                </tr>
                            <?php 
                            }?>
                        </tbody>
                     </table>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
<?php
}//end of if

?>