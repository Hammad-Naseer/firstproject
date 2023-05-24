
<style>
	.di li {padding:4px;}
</style>

<?php
$today_date   = $_POST['today_date'];
$current_date = date('Y-m-d');
$date_month   = $_POST['month'];
$date_year    = $_POST['year'];  	
$dept_id      = $_POST['dept_id'];
$class_id     = $_POST['class_id'];
$subject_id   = $_POST['subject_id'];

if(isset($subject_id) && ($subject_id>0))
{
	$subj_query=" AND ap.subject_id=$subject_id";
}
elseif(isset($section_id) && ($section_id>0))
{

	$subj_list=array();
	$q="select * from ".get_school_db().".subject s INNER join ".get_school_db().".subject_section ss on s.subject_id=ss.subject_id
	    where ss.section_id='$section_id' and s.school_id=".$_SESSION['school_id']." ";
	$s=$this->db->query($q)->result_array();
	foreach($s as $subj)
	{
	 	$subj_list[]=$subj['subject_id'];
	}
	 
	$subj_array=implode(",",$subj_list);
	$subj_query=" AND ap.subject_id IN(".$subj_array.")";
}

$school_id = $_SESSION['school_id'];
$query     = "SELECT ap.planner_id as planner_id, ap.title as title, ap.start as start, ap.objective as objective,ap.assesment as assesment,ap.requirements as requirements,ap.required_time as required_time, ap.attachment as attachment,ap.is_active as is_active, s.name as subject_name, s.code as code,s.subject_id as subject_id
              FROM ".get_school_db().".academic_planner ap INNER JOIN ".get_school_db().".subject s ON ap.subject_id = s.subject_id
              Where ap.school_id=$school_id AND MONTH(start) =$date_month  and year(start)=$date_year".$yearly_query.$section_query.$subj_query; 
$qur_red  =  $this->db->query($query)->result_array();
$plan     =  array();

foreach($qur_red as $red){
    $plan[$red['start']][$red['subject_id']][]=array('planner_id'=>$red['planner_id'], 'title'=>$red['title'],'detail'=>$red['detail'],'objective'=>$red['objective'],'assesment'=>$red['assesment'],'requirements'=>$red['requirements'],'required_time'=>$red['required_time'],'attachment'=>$red['attachment'], 'subject_name'=>$red['subject_name'],'subject_id'=>$red['subject_id'],'code'=>$red['code'],'is_active'=>$red['is_active']);
}

?>

    <table class="table  table-stripped table-condensed" style="margin-bottom:0px;">
                <tbody>
                    <td>
                        <?php
                            $is_planner_found = 0;
                            if(isset($plan[$today_date])){
                            	foreach($plan[$today_date] as $val ){
                            		if(count($val) >0)
                            		{
                            		    $is_planner_found = 1;
                            		?>
                                        <div class="blue">
                                            <i class="fa fa-book" aria-hidden="true"></i>
                                            <?php echo $val[0]['subject_name']." - ".$val[0]['code']; ?>
                                        </div>
                                        <ol class="di">
                                          <?php
                    	                     foreach($val as $row)
                    		                 {
                    			          ?>
                                                <li>
                                                  <?php echo $row['title'] ;?>  
                                                  <?php      
                                                     if($row['attachment'] !==""){
                                                            $file_name=$row['attachment'];
                                                            $folder_name='academic_planner';
                                                            $display_link=display_link($file_name,$folder_name);	
                    		                      ?>
                                                         <a target="_blank" href="<?php echo $display_link;?>"><i class="fa fa-download" style=" padding-left:5px;" aria-hidden="true"></i></a>
                                                  <?php 
                                                     } 
                                                  ?>
                                                               
                                                    <span style="float:right;">
                    		                            <span class="view">
                    		                                <i class="fa fa-eye" style="padding:0px;color:#94949 !important;"></i>
                                                			<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_view_planner/<?php echo $row['planner_id'];?>/<?php echo $today_date;?>');">
                                                			     <?php echo get_phrase('view');?>
                                                			</a>
                    		                            </span>
                    		                            <span class="view">
                    		                                <i class="fa fa-eye" style="padding:0px;color:#94949 !important;"></i>
                                                			<a href="#" onclick="showAjaxModal('https://dev.indiciedu.com.pk/modal/popup/modal_edit_acad_plan/<?php echo $row['planner_id'];?>');">
                                                			     <?php echo get_phrase('edit');?>
                                                			</a>
                    		                            </span>
                                                            <?php 
                                                                // if($today_date >= $current_date  && $row['is_active']==0)
                                                                // {
                                                            ?>
                                                                 
                                                                 <span class="del">
                                                                        <?php  if (right_granted('academicplanner_delete')){?>
                                                                        	<i class="entypo-trash"></i>
                                                                        	<a style="text-align: right; color:#7e7d7d;"  id="delete_new" delete_id="<?php echo  $row['id']; ?>" 	delete_img="<?php echo  $row['attachment']; ?>" href="#" onclick="confirm_modal('<?php echo base_url();?>academic_planner/delete_planner/<?php echo $row['planner_id'];?>/<?php echo $row['attachment'];?>');"> 
                                                                        	    <?php echo get_phrase('delete');?> 
                                                                        	</a>
                                                                        <?php
                                                                        } 
                                                                        ?>
                                                                 </span>
                                                            <?php
                                                                // }
                                                            ?>
                                                    </span>
                                                                    
                                                 </li>
                                                <div>
                                                    <hr style="margin: 2px;">
                                                </div>
                                                        
                                          <?php
                    	                     }
                    	                  ?>
                                        </ol>
                                        
                                    <?php
                            	    }	
                            	}
                            }
                        	else{
                        		echo "<p>".get_phrase('no_record_found')."</p>";
                        	}
                        ?>
                        <input type="hidden" id="is_planner_found" value="<?php echo $is_planner_found ?>">
                    </td>    
                </tbody>
            </table>
