<style>
</style>

<?php 
    $yearCheck='';
    $termCheck='';
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('datesheet');?>  
        </h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel-group joined class_routine_panel" id="collapse" data-step="1" data-position='top' data-intro="exam datesheet term wise">
            <?php 
                $toggle = true;
                $q="select e.*, y.title as yearly_term
                from ".get_school_db().".exam e 
                inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id 
                inner join ".get_school_db().".acadmic_year ay on ay.academic_year_id=y.academic_year_id 
                where e.school_id=".$_SESSION['school_id']."
                and ay.academic_year_id=".$_SESSION['academic_year_id']."
                order by e.start_date DESC";
                $exams = $this->db->query($q)->result_array();
                foreach($exams as $row){
            ?>    
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" href="#collapse<?php echo $row['exam_id'];?>">
                            <i class="fa fa-wpforms" aria-hidden="true"></i><?php echo $row['yearly_term'].' - '.$row['name'];?>
                            <span style="font-size:12px;">
                                 (<?php echo convert_date($row['start_date']).' to '.convert_date($row['end_date']);?>)
                        	</span>
                        </a>
                    </h4>
                </div>      
                <div id="collapse<?php echo $row['exam_id'];?>" class="panel-collapse collapse show">
                    <div class="">
                        <table class="table table-bordered">
                            <tr>  
                                <th style="width:120px;"><strong><?php echo get_phrase('day');?></strong></th>      
                                <th style="width:120px;"><strong><?php echo get_phrase('date');?></strong></th>       
                                <th><strong><?php echo get_phrase('subject');?></strong></th>     
                            </tr>
                            <tbody>
                                <?php 
                                $statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend');
                                $custom_css=array(1=>'current-day',2=>'holiday');      
                                $current_date=date('d-M-Y');   
                                $date_from = strtotime($row['start_date']);
                                $date_to = strtotime($row['end_date']);
                                $oneDay = 60*60*24;
                                for($i=$date_from; $i<=$date_to; $i=$i+$oneDay)
                                {
                                	$current = "";
                                	$current1="";
                                    /*date(" F j, Y", $i)*/
                                    $day= convert_date(date('Y-m-d',$i));
                                    $date1= date('Y-m-d',$i);
                                    $dd=date("l", $i);
                                    
                                    if($day==$current_date)
                                	{
                                		$current=$custom_css[1];
                                	} 
                                	$statuslist_css="";
                                	
                                	if($dd=="Saturday" or $dd=="Sunday")
                                	{
                                		$statuslist_css=$statuslist[4]; 
                                	}
                                	
                                	$qurrr=$this->db->query("select * from ".get_school_db().".holiday where start_date<='$date1' and end_date>='$date1' AND school_id=".$_SESSION['school_id']." ")->result_array();
                                	if(count($qurrr)>0){
                                		$current1=$custom_css[2];
                                	}
                                	
                                    echo '<tr class="gradeA '.$current.' '.$current1.' '.$statuslist_css.'">'; 
                                ?>
                                    <td ><?php echo $dd;?></td>
                                    <td ><?php echo $day;?></td>
                                    <td>
                                     <?php
                                        $q="select er.* from ".get_school_db().".exam_routine er 
                                        where er.school_id=".$_SESSION['school_id']." 
                                        and er.exam_id=".$row['exam_id']." 
                                        and section_id=".$_SESSION['section_id']."";
                                        $routines=$this->db->query($q)->result_array();
                                        foreach($routines as $row2){?>
                                                           
                                                            <div class="btn-group" id="er<?php echo $row2['exam_routine_id'];?>">              
                                                               <?php if(strtotime($row2['exam_date'])==$i){
                                                            echo get_subject_name($row2['subject_id']);
                                                            echo '('.$row2['time_start'].'-'.$row2['time_end'].')';                         
                                                            ?>
                                                            
                                                            <?php if(right_granted('managedatesheet_edit')){?>
                                                            <a onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_exam_routine/<?php echo $row2['exam_routine_id'].'-'.$department_id.'-'.$class_id.'-'.$section_id.'-'.$yearly_term.'-'.$academic_year;?>');" href="#">
                                                            <i class="entypo-pencil"></i>
                                                            
                                                            </a>
                                                            <?php }?>
                                                            
                                                            <?php if(right_granted('managedatesheet_delete')){?>
                                                            <a id="delete<?php echo $row2['exam_routine_id'];?>">
                                                            <i class="entypo-trash"></i>
                                                            
                                                            </a>
                                                            <?php }?>
                                                            
                                                            <?php }?>
                                                            
                                                            </div>
                                                        <?php }?>
                                    </td>
                                </tr>
                                <?php }
                                ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    
    <div class="row mgt10"> 
      <div class="col-sm-12 py-4">
        
        <div class="present-legend legend-attendance pull-left"> </div>
    	<div class="ml pull-left"> Present</div>
    	
    	<div class="absent-legend legend-attendance pull-left"> </div>
        <div class="ml pull-left"> Absent</div>
    	 
        <div class="leave-legend legend-attendance pull-left"> </div>
        <div class="ml pull-left">Leave</div>
        
    	<div class="weekend-legend legend-attendance pull-left"> </div>
    	<div class="ml pull-left"> Weekend</div>
    
    	<div class="holiday-legend legend-attendance pull-left"> </div>
    	<div class="ml pull-left"> Holiday</div>
     
    	<div class="today-legend legend-attendance pull-left "></div>
    	<div class="ml pull-left"> Today</div>
    	
      </div>   
    </div>
                        
</div>

