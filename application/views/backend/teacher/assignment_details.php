<style>
    .myfst{font-size:15px;color:#0a73b7;font-weight:700}.due{color:#972d2d}.mygrey{color:#a6a6a6}.fa-remove{color:#fff!important}h1{margin:0;padding-bottom:5pt;border-bottom:1px solid #ddd}
    input[type=text]{height:20pt;width:100%;padding:0;border:0;outline:0;font-size:14px;margin-top:5pt}.options{width:100%;height:30pt;margin-top:5pt;border-top:1px solid #ddd}iframe{border:0;width:100%;margin-bottom:5pt;height:250pt}
    .seperator{display:inline;border-left:1px solid #ddd;height:30pt}button{margin:0;padding:0;height:30pt;width:30pt;background-color:#fff;border:0;cursor:pointer;color:#333}button:active 
    .{color:#333}select{height:30pt;-webkit-appearance:none;border:0;padding-left:5pt;padding-right:5pt;outline:0}input[type=number]{height:30pt;border:0;padding:0;padding-left:5pt;padding-right:5pt;outline:0}.text-heading{font-size:14px; color:#0A73B7;}
    
</style>
<div class="col-sm-12">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline">
                <?php echo get_phrase('assignment_details'); ?>  
            </h3>
        </div>
    </div>
</div>

<div style="line-height: 22px;border: 1px solid #CFCFDE;margin-left: 30px;margin-bottom: 10px;border-radius: 5px;padding: 5px 0px 5px 10px;">
<div class="text-heading"><strong><?php echo get_phrase('subject')?> : <?php echo get_subject_name($stud_diary[0]['subject_id']);?></strong></div>
<div class="text-heading"><strong><?php echo get_phrase('assign_date');?> : </strong><?php echo convert_date($stud_diary[0]['assign_date']);?></div>
<div class="text-heading"><strong><?php echo get_phrase('title').' : '.$stud_diary[0]['title'];?></strong></div>

    <?php if($stud_diary[0]['attachment']!="") {?>
        
    <div style="color:#972d2d;"><strong><?php echo get_phrase('due_date');?>:</strong><?php echo convert_date($stud_diary[0]['due_date']);?></div>
    <?php   
    }
    
    $sec_arr = section_hierarchy($stud_diary[0]['section_id']);
    
    echo '<strong class="text-heading">'.get_phrase('section')." : ".$sec_arr['d'].' - '.$sec_arr['c'].' - '.$sec_arr['s'].' </strong> '; 					
    echo'<span class="item">';
    
    ?>
    <?php
    if ($stud_diary[0]['submission_date'] != '0000-00-00 00:00:00')
    {
        echo '<br/><strong class="text-heading">'.get_phrase("submisstion_date").':</strong> '.convert_date($stud_diary[0]['submission_date']).' '.date('h:i:s A', strtotime($row['submission_date']));
    	echo '<br/><strong class="text-heading">'.get_phrase("detail").':</strong> '.$stud_diary[0]['task'];
    }
    
    $planner_arr = $this->db->query("select ap.* 
        from ".get_school_db().".academic_planner_diary apd
        inner join ".get_school_db().".academic_planner ap
            on ap.planner_id = apd.planner_id
        where apd.diary_id = ".$stud_diary[0]['diary_id']." 
        and apd.school_id = ".$_SESSION['school_id']."
        ")->result_array();
    if (count($planner_arr)>0)
    {
        echo '<br/><strong class="text-heading">'.get_phrase("planner").':</strong>';
        $p_count=1;
        foreach ($planner_arr as $key => $value) 
        {
            echo '<br>'.$p_count.')'.get_phrase("title").' : ' .$planner_arr[0]['title'];
        }
    } 
    echo'</span>';
    // if($stud_diary[0]['is_assigned'] == 0 ){
    //     echo '<br/><strong style="color:blue;">'.get_phrase("status").': '.get_phrase("draft").'</strong>';
    // }else{
    //     echo '<br/><strong style="color:green;">'.get_phrase("status").': '.get_phrase("assigned").'</strong>';
    // }
    
    ?>
</div>

<!--<form action="<?php echo base_url(); ?>parents/submit_assignment" method="POST" enctype="multipart/form-data">-->
<!--    <input type="text" name="diary_id" value="<?php echo $diary_id;?>">-->

				
<!--<button class="btn btn-primary" style="float:right;" type="submit">submit</button>-->
<!--</form>-->

<div class="row">
	<div class="col-md-12">
    	<div class="tab-content">            
            <div class="tab-pane box active" id="list">
			    <table class="table table-bordered" id="teacher_diary_datatable">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('s_no');?></div></th>
                    		<th style="width:140px;"><div><?php echo get_phrase('student_name');?></div></th>
                           	<th><div><?php echo get_phrase('submission_status');?></div></th>
                           	<th><div><?php echo get_phrase('is_viewed');?></div></th>
                           	<th><div><?php echo get_phrase('submission_details');?></div></th>
                            <!--<th><div><?php echo get_phrase('action');?></div></th>-->
						</tr>
					</thead>
                    <tbody>
                    	<?php
                    	$j=$start_limit;
                    	$total_records = 0;
                    	$total_submitted = 0;
                    	$total_notsubmitted = 0;
                    	$total_viewed = 0;
                    	$total_notviewd = 0;
                    	$count = 1;foreach($stud_diary as $row):
                    	$j++;
                    	$total_records++;
                    	?>
                        <tr>
                        	<td><?php echo $j; ?></td>
							<td><?php echo get_student_name($row['student_id']);?></td>
							<td>
    							<?php
    							    if($row['is_submitted_by'] == 1)
    							    {
    							        $total_submitted++;
    							 ?>
    							    <strong style="color:green;"><?php echo get_phrase('submitted'); ?></strong>
    							 <?php
    							    }
    							    else
    							    {
    							        $total_notsubmitted++;
    							 ?>
    							     <strong style="color:red;"><?php echo get_phrase('not_submitted'); ?></strong>
    							<?php     
    							    }
    							?>
							</td>
							<td>
    							<?php
    							    if($row['is_viewed'] == '1')
    							    {
    							        $total_viewed++;
    							 ?>
    							    <strong style="color:green;"><?php echo get_phrase('viewed'); ?></strong>
    							 <?php
    							    }
    							    else
    							    {
    							        $total_notviewd++;
    							 ?>
    							     <strong style="color:red;"><?php echo get_phrase('not_view'); ?></strong>
    							<?php     
    							    }
    							?>
							</td>
							<td>
							    <?php
							    
							    if(get_diary_student_attachments_count($row['diary_student_id']) > 0){
							         $attachments = get_diary_student_attachments($row['diary_student_id']);
							    ?>
							    
							       <button class="btn btn-sm btn-primary glyphicon glyphicon-download-alt" type="button" 
							               style="color:white !important;width: 170px;height: 30px;font-size: 1.1em;" 
                        	                onclick="jsfunction('<?php echo $attachments; ?>')">
                        	                Download Files
                        	       </button>
                        	       <!--<input type="hidden" id="hf_<?php echo $row['diary_student_id']; ?>" value="<?php echo $attachments; ?>">-->
							    <?php
							    }else{
							        echo "No Attachments";
							    }
							    ?>
                                    <?php
    							    if($row['answer_text'] != "")
    							    {
    							        $aa = $row['answer_text'];
    							        
    							    ?>
                                        <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_show_text/<?php echo $row['diary_id']."/".$row['student_id']; ?>');" class="btn btn-primary pull-right">
                                        <i class="entypo-plus-circled"></i>
                                        <?php echo get_phrase('view_text');?>                
                                        </a> 
							        <?php
    							    }
    							    ?>
							</td>
							<!--<td>&nbsp;</td>-->
        				</tr>
                 <?php endforeach;?>
                 </tbody>
                </table>
                <?php
                echo "<span class='text-success'>Total Records: ".$total_records."</span>";
                echo "<br>";
                echo "<span class='text-success'>Total Submitted: ".$total_submitted."</span>";
                echo "<br>";
                echo "<span class='text-success'>Total Viewed: ".$total_viewed."</span>";
                echo "<br>";
                echo "<span class='text-success'>Total Remaining: ".$total_notsubmitted."</span>";
                ?>                
			</div>
		</div>
	</div>
</div>


<script>

// Added By JS Cheif For Downloading Multiple Attachments On Single Click  
function jsfunction(url){

    // var url = $('#hf_'+idvalue).val();
    var attachment_urls = "";
    var params_array = [];
    var global = '<?php echo base_url(); ?>uploads/<?=$_SESSION["folder_name"]?>/docs/';
    var links = url.split(',');
    
    for(var i=0; i < links.length; i++){
        var obj = {};
        obj["download"] = global+links[i];
        obj["filename"] = links[i];
        params_array.push(obj);
    }
    
    console.log(params_array);
    download_files(params_array);
  
} 


function download_files(files) {
 
  function download_next(i) {
      
    if (i >= files.length) {
      return;
    }
    
    var a = document.createElement('a');
    a.href = files[i].download;
    a.target = '_parent';
    
    if ('download' in a) {
      a.download = files[i].filename;
    }
    (document.body || document.documentElement).appendChild(a);
    
    if (a.click) 
    {
      a.click(); 
    } 
    else 
    {
      $(a).click(); 
    }

    a.parentNode.removeChild(a);
    setTimeout(function() {
      download_next(i + 1);
    }, 500);
  }
  download_next(0);
  
}


</script>
