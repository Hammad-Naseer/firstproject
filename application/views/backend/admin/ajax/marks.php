<?php
    $p="SELECT * from ".get_school_db().".exam_routine WHERE exam_id=$exam_id AND section_id=$section_id AND subject_id=$subject_id AND school_id=".$_SESSION['school_id']." AND (is_submitted=1 OR is_approved=1)";
    $query=$this->db->query($p)->result_array(); 
    $q="select s.name,s.student_id,s.roll from ".get_school_db().".student s 
        WHERE s.school_id =".$_SESSION['school_id']." and s.section_id=".$section_id." and student_status in (".student_query_status().")";
    $studentArr=$this->db->query($q)->result_array();
    if(sizeof($studentArr)>0){
?>
<div id="session" style="display:none"><?php
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      Record Saved
     </div> 
    </div>';

  ?></div>
	<div id="error"></div>
	<form name="marks" id="marks" method="post" >
		<table class="table table-bordered " id="marks_list_tbl">			
						<tr style="background:#2092d0;">
							<th class="td_middle" style="width:50px; color:#ffffff;">
								<div><?php echo get_phrase('s_no');?></div>
							</th>
							<th class="td_middle" style="width:50px; color:#ffffff;">
								<div><?php echo get_phrase('roll');?></div>
							</th>
							<th class="td_middle" style="width:200px; color:#ffffff;"><div><?php echo get_phrase('student');?></div></th>              		
							<th style="color:#ffffff;"><div><?php echo get_phrase('component_wise_marks');?></div></th>
						</tr>					
						<tr>
							<td colspan="3"></td>
							<td>
								
							</td>
						</tr>					
						<?php
                        	$r =0;
                        	foreach($studentArr as $st){
                        	    $r++ ; 
					    ?>
						<tr>
							<td class="td_middle"><?php echo $r; ?></td>
							<td class="td_middle"><?php echo $st['roll'];?></td>
							<td class="td_middle">
							    <img class="userpic" src="<?php echo base_url();?>uploads/default_pic.png" alt="..." width="50" style="margin-bottom:10px;"><br>
							    <?php echo $st['name'];?>
							</td>
							<td>
							    <table class="table table-bordered ">
                                    <tr style="background:#dcdcdc; font-weight:bold;">
                                        <td style="width:200px"><?php echo get_phrase('subject');?> / <?php echo get_phrase('component');?></td>
                                        <td style="width:100px"><?php echo get_phrase('total_marks');?></td>
                                        <td style="width:150px"><?php echo get_phrase('marks_obtained');?></td>
                                        <td><?php echo get_phrase('comment');?></td>
                                    </tr>
                                    <?php  
                                    $q2="select subject_component_id,subject_id,title,school_id,percentage  from ".get_school_db().".subject_components  where  subject_id=".$subject_id."  and school_id=".$_SESSION['school_id']."";
                                    $compArr=$this->db->query($q2)->result_array();
                                    if(sizeof($compArr)>0)
                                    {
                                        $total=0;
                                        $sum=0;
                                        foreach($compArr as $comp)
                                        {
                                            $q3="SELECT m.*,marks_obtained FROM ".get_school_db().".marks m inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id where m.subject_id=".$subject_id." AND m.student_id=".$st['student_id']." AND m.exam_id=".$exam_id." and m.school_id=".$_SESSION['school_id']." AND mc.subject_component_id=".$comp['subject_component_id']."";
                                            $marksArr=$this->db->query($q3)->result_array();
                                            $total+=$comp['percentage'];
                                            $sum+=$marksArr[0]['marks_obtained'];
                                            $comments[$st['student_id']][]=$marksArr[0]['comment'];?>
                                            <tr>
                                                <td>
                                                    <?php echo $comp['title']?>
                                                </td>
                                                <td class="marks_tot_<?php echo $st['student_id']?>" id="<?php echo $comp['subject_component_id']?>">
                                                    <?php echo $comp['percentage']?>
                                                </td>
                                                <td id="">
                                                    <input id="comp_<?php echo $comp['subject_component_id']?>" type="number" min="0" max="<?php echo $comp['percentage']?>" value="<?php echo $marksArr[0]['marks_obtained']?>" name="marks_obt" class="form-control marks_obt_<?php echo $st['student_id']?>" <?php echo $disable_val;?>>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <?php 
                                        }
                                    }
                                    else
                                    {
                                        $q3="SELECT m.*,marks_obtained FROM ".get_school_db().".marks m inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id where m.subject_id=".$subject_id." AND m.student_id=".$st['student_id']." 
                                            AND m.exam_id=".$exam_id." 
                                            and m.school_id=".$_SESSION['school_id']." 
                                            AND mc.subject_component_id=0";
                                        $marksArr=$this->db->query($q3)->result_array();
                                        ?>
                                        <tr>
                                            <td><?php echo $subject_title; ?></td>
                                            <td>
                                                <input type="number" disabled="" class="form-control" value="<?php echo get_total_marks($exam_id,$section_id,$subject_id)?>" id="total_marks<?php echo $st['student_id']?>" placeholder="" name="total_marks">
                                            </td>
                                            <td>
                                                <input type="number" max="<?php echo get_total_marks($exam_id,$section_id,$subject_id)?>" value="<?php echo $marksArr[0]['marks_obtained']?>" name="exam_marks<?php echo $st['student_id']?>" class="form-control exam_marks" id="exam_marks<?php echo $st['student_id']?>">
                                                <div style="width: 75px;margin-top: 10px;" name="grade" id="grade<?php echo $st['student_id']?>"><?php echo get_phrase('grade');?>:
                                                    <?php echo get_grade($marksArr[0]['marks_obtained']);?>
                                                </div>
                                                <input type="hidden" name="student_id" class="student_id" id="student_id" value="<?php echo $st['student_id']?>">
                                            </td>
                                            <td>
                                                <textarea name="comments" rows="3" class="form-control" cols="40" id="comment<?php echo $st['student_id']?>"><?php echo $marksArr[0]['comment']?></textarea>
                                            </td>
                                        </tr>
                                        <?php 
                                    }
                                    
                                    if(sizeof($compArr)>0)
                                    {?>
                                        <tr>
                                            <td><?php echo get_phrase('total_marks');?>:</td>
                                            <td>
                                                <input type="text" disabled="" class="form-control" value="<?php echo $total;?>" id="total_marks<?php echo $st['student_id']?>" placeholder="" name="marks_obtained">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" value="<?php echo $sum;?>" placeholder="<?php echo get_phrase('total_obtained');?>" name="total_obtained" id="total_obtained<?php echo $st['student_id']?>" <?php echo $disable_val;?>>
                                                <input type="hidden" name="student_id" class="student_id" id="student_id" value="<?php echo $st['student_id']?>">
                                                <div style="width: 75px;margin-top: 10px;" name="grade" id="grade<?php echo $st['student_id']?>"><?php echo get_phrase('grade');?>:
                                                    <?php echo get_grade($sum);?>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea name="comments" class="form-control" rows="3" cols="40" id="comment<?php echo $st['student_id']?>" <?php echo $disable_val;?>><?php echo $comments[$st['student_id']][0];?></textarea>
                                            </td>
                                        </tr>
                                        <?php 
                                    }
                                    ?>

                                </table>
							</td>
						</tr>
		                        <?php } if(right_granted('managemarks_save')) { ?>
					<tr>
					    <div id="grade"></div>
					</tr>
				</table>
				<div class="row">
    				<div class="col-sm-4">	
    					<input type="button" id="reset-btn" value="Reset" class="btn " style="float:right">
    				</div>
    				<div class="col-sm-4">
        				<?php
            				$approved_selected="";
            				$submit_selected="";
            				$saved_selected="";
            				
            				if(isset($query[0]['is_approved']) && ($query[0]['is_approved'] > 0))
            				{
            					$approved_selected='selected';
            				}
            				elseif(isset($query[0]['is_submitted']) && ($query[0]['is_submitted'] > 0))
            				{
            					$submit_selected='selected';
            				}
            				elseif($query[0]['is_submitted']==0)
            				{
            					$saved_selected='selected';
            				}
        				?>
    					<select id="submit_val" class="form-control" style="float:right">
    						<option value="">Select option</option>
    						<option value="save" <?php echo $saved_selected;?>>Save</option>
    						<option value="submit" <?php echo $submit_selected;?>>Submit</option>
    						<option value="approve" <?php echo $approved_selected;?>>Approve</option>
    					</select>
    					
    				</div>
    				<div class="col-sm-4">
    					<input type="submit" id="save_btn" value="save" class="btn ">
    				</div>
			    </div>
				</form>
				<?php }?>
			</div>
			
            
            
			
            
		</div>
	</div>
</div>
<?php 
if(count($query) > 0)
{
	if(isset($query[0]['is_submitted']) && $query[0]['is_submitted']>0)
	{
		$submitted_by=$query[0]['submitted_by'];
	$submitted_by_arr=get_user_info($submitted_by);
	echo "<strong>Last Submitted by :</strong>".$submitted_by_arr[0]['name'];
	echo "<br>";
	echo "<strong>Submitted date :</strong>".convert_date($query[0]['date_submitted']);
	}
	echo "<br>";
	if(isset($query[0]['is_approved']) && $query[0]['is_approved']>0)
	{
		$approved_by=$query[0]['approved_by'];
	$approved_by_arr=get_user_info($approved_by);
	echo "<strong>Last Approved by :</strong>".$approved_by_arr[0]['name'];
	echo "<br>";
	echo "<strong>Approved date :</strong>".convert_date($query[0]['date_approved']);
	}
	
}

}

else echo get_phrase("no_record_found");?>



<script type="text/javascript">

jQuery(document).ready(function($)
{
	$('#session').css('display','none');
	document.getElementById('marks').onsubmit = function() 
	{
		return false;
	}

  
/*	tableContainer = $("#marks_list_tbl");
        tableContainer.dataTable({
            "sPaginationType": "bootstrap",
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "bStateSave": true,
            // Responsive Settings
            bAutoWidth     : false,
            fnPreDrawCallback: function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper) {
                    responsiveHelper = new ResponsiveDatatablesHelper(tableContainer, breakpointDefinition);
                }
            },
            fnRowCallback  : function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                responsiveHelper.createExpandIcon(nRow);
            },
            fnDrawCallback : function (oSettings) {
                responsiveHelper.respond();
            }
        });
        
        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });	
        */
        
		var gradeArr={};
		$.get("<?php echo base_url(); ?>exams/get_all_grades/", function(data, status)
		{		
			arr=JSON.parse(data);
			//console.log(arr);
			$('.student_id').each(function() 
			{
				var student_id=$(this).val();
				if ($('.marks_obt_'+student_id).length > 0)
				{
					var sum=0;
					$('.marks_obt_'+student_id).each(function() {				
					 	sum=sum+Number($(this).val());
					});
					
					$('#total_obtained'+student_id).val(sum);
					sum = parseInt(parseInt(sum)/parseInt(($('#total_marks'+student_id)).val()) * 100);
					//alert(sum);
					$.each(arr, function (index, value) 
					{
						mark_from=value.mark_from;
						mark_upto=value.mark_upto;
						grade_point=index;
						if((Number(sum)>=(mark_from)) && (Number(sum)<=(mark_upto)))
						{  
							$('#grade'+student_id).text('Grade: '+grade_point);
						}
				 
					});
				}

				$("input[id^='comp_']").change(function()
				{
					str=$(this).attr('id');
					var sum=0;
					var comp_id=str.replace('comp_','');
					
					$('.marks_obt_'+student_id).each(function() {				
					 	sum=sum+Number($(this).val());
					 	});
				
					$('#total_obtained'+student_id).val(sum);
				
					sum = parseInt(parseInt(sum)/parseInt(($('#total_marks'+student_id)).val()) * 100);
					//alert(sum);
					$.each(arr, function (index, value) {
						mark_from=value.mark_from;
						mark_upto=value.mark_upto;
						grade_point=index;
						if((Number(sum)>=(mark_from)) && (Number(sum)<=(mark_upto)))
						{  
						
							$('#grade'+student_id).text('Grade: '+grade_point);
						}
				 
					});
				});

				$('#exam_marks'+student_id).on('change',function()
				{
					$.each(arr, function (index, value) {
			
						mark_from=value.mark_from;
						mark_upto=value.mark_upto;
						grade_point=index;
					 
				 	 	marks_obt = $('#exam_marks'+student_id).val();

				 	  	perc = parseInt(parseInt(marks_obt)/parseInt(($('#total_marks'+student_id)).val()) * 100);

					    if( (Number(perc) >= (mark_from)) && (Number(perc))<=(mark_upto))
						{  
							$('#grade'+student_id).text('Grade: '+grade_point);
						}
					});
				});
			});
		
		/*$('#submit_button').click(function(){
		$('#session').css('display','none');
			var studentArr={};
			var compArr={};
			var a=0;
			var b=0;
			var is_submitted=1;
			var section_id='<?php echo $section_id;?>';
			var exam_marks=$('.exam_marks').val();
			if(exam_marks!='')
			{
				
				$('.student_id').each(function(){
					//console.log(gradeArr);
		
				var student_id=$(this).attr('id');
				studentArr[a]={};
				studentArr[a]['student_id']=$(this).val();
				studentArr[a]['exam_marks']=$('#exam_marks'+$(this).val()).val();
				studentArr[a]['comment']=$('#comment'+$(this).val()).val();
				a++;
				});
			}
			var count=0;
			$('.student_id').each(function(){
				var student_id=$(this).val();
				
				$('.marks_obt_'+student_id).each(function() {
					$(this).css('border','1px solid #000');
					if($(this).val()=='')
					 marks=0;
					else
					marks=$(this).val();
				compArr[b]={}; 
				compArr[b]['student_id']=student_id;
				compArr[b]['marks']=marks;
				var subject_component_id=$(this).attr('id');
				subject_comp_id=subject_component_id.replace('comp_','');
				compArr[b]['subject_comp_id']=subject_comp_id;
				compArr[b]['total_obtained']=$('#total_obtained'+student_id).val();
				//alert($('.marks_obt_'+student_id).val());
				if($('.marks_obt_'+student_id).val()=="")
				{
					
					$('.marks_obt_'+student_id).after('<span class=red>Please fill this field</span>');
					count=count+1;
				b++;
				studentArr[a]={};
				studentArr[a]['student_id']=student_id;
				studentArr[a]['comment']=$('#comment'+student_id).val();
				a++;
			}
			});
			});
			
		
			if(count > 0)
			{
				
			}
			else
			{
			if(exam_marks!='' && student_id!='' && $('form#marks')[0].checkValidity()){
			
					$.ajax({ 
					type: "POST",
					url: "<?php echo base_url(); ?>exams/save_exam_marks/",
					//dataType: "json",
					data: ({student_arr:(JSON.stringify(studentArr)),comp_arr:(JSON.stringify(compArr)),exam_id:'<?php echo $exam_id;?>',subject_id:'<?php echo $subject_id;?>',exam_marks:exam_marks,is_submitted:is_submitted,section_id:section_id}),
					
					success: function(response) {
						console.log(response);
						$('#session').css('display','block');
						$('#session').fadeOut('3000');
									
					}


				});

}
				}
		
		
		});*/
		
		
		
		
		$('#save_btn').click(function(){
			var exam_id='<?php echo $exam_id;?>';
			var section_id='<?php echo $section_id;?>';
			//alert(section_id);
			subject_id='<?php echo $subject_id;?>';
			
			if($('#submit_val').val()=='save')
			{
			var submit_val="save";
				$('#session').css('display','none');
			var studentArr={};
			var compArr={};
			var a=0;
			var b=0;
			var section_id='<?php echo $section_id;?>';
			var exam_marks=$('.exam_marks').val();
			var is_submitted=0;
			if(exam_marks!='')
			{
				$('.student_id').each(function(){
					//console.log(gradeArr);
					
					
				
				var student_id=$(this).attr('id');
				studentArr[a]={};
				studentArr[a]['student_id']=$(this).val();
				studentArr[a]['exam_marks']=$('#exam_marks'+$(this).val()).val();
				studentArr[a]['comment']=$('#comment'+$(this).val()).val();
				a++;
				});
				
			}
			$('.student_id').each(function(){
				var student_id=$(this).val();
				
				$('.marks_obt_'+student_id).each(function() {
					$(this).css('border','1px solid #000');
					if($(this).val()=='')
					 marks=0;
					else
					marks=$(this).val();
				compArr[b]={}; 
				compArr[b]['student_id']=student_id;
				compArr[b]['marks']=marks;
				var subject_component_id=$(this).attr('id');
				subject_comp_id=subject_component_id.replace('comp_','');
				compArr[b]['subject_comp_id']=subject_comp_id;
				compArr[b]['total_obtained']=$('#total_obtained'+student_id).val();
				b++;
				studentArr[a]={};
				studentArr[a]['student_id']=student_id;
				studentArr[a]['comment']=$('#comment'+student_id).val();
				a++;
				
				
			});
			
			});
			
			if(exam_marks!='' && student_id!='' && $('form#marks')[0].checkValidity()){
				
			
					$.ajax({ 
					type: "POST",
					url: "<?php echo base_url(); ?>exams/save_exam_marks/",
					//dataType: "json",
					data: ({student_arr:(JSON.stringify(studentArr)),comp_arr:(JSON.stringify(compArr)),exam_id:'<?php echo $exam_id;?>',subject_id:'<?php echo $subject_id;?>',exam_marks:exam_marks,submit_val:submit_val,section_id:section_id,is_submitted:is_submitted}),
					
					success: function(response) {
						//alert(response);
						console.log(response);
						$('#session').css('display','block');
						$('#session').fadeOut('3000');
						var msg='msg';
						window.location.href="<?php echo base_url();?>exams/marks/" + msg+ "/" +exam_id + "/" + section_id + "/" + subject_id;
									
					}


				});

}
				
			}
			else if($('#submit_val').val()=='submit')
			{
				$('#session').css('display','none');
			var studentArr={};
			var compArr={};
			var a=0;
			var b=0;
			var submit_val="submit";
			var is_submitted=1;
			var section_id='<?php echo $section_id;?>';
			var exam_marks=$('.exam_marks').val();
			if(exam_marks!='')
			{
				
				$('.student_id').each(function(){
					//console.log(gradeArr);
		
				var student_id=$(this).attr('id');
				studentArr[a]={};
				studentArr[a]['student_id']=$(this).val();
				studentArr[a]['exam_marks']=$('#exam_marks'+$(this).val()).val();
				studentArr[a]['comment']=$('#comment'+$(this).val()).val();
				a++;
				});
			}
			var count=0;
			$('.student_id').each(function(){
				var student_id=$(this).val();
				
				$('.marks_obt_'+student_id).each(function() {
					$(this).css('border','1px solid #000');
					if($(this).val()=='')
					 marks=0;
					else
					marks=$(this).val();
				compArr[b]={}; 
				compArr[b]['student_id']=student_id;
				compArr[b]['marks']=marks;
				var subject_component_id=$(this).attr('id');
				subject_comp_id=subject_component_id.replace('comp_','');
				compArr[b]['subject_comp_id']=subject_comp_id;
				compArr[b]['total_obtained']=$('#total_obtained'+student_id).val();
				//alert($('.marks_obt_'+student_id).val());
				if($('.marks_obt_'+student_id).val()=="")
				{
					
					$('.marks_obt_'+student_id).after('<span class=red>Please fill this field</span>');
					count=count+1;
				
			}
				b++;
				studentArr[a]={};
				studentArr[a]['student_id']=student_id;
				studentArr[a]['comment']=$('#comment'+student_id).val();
				a++;
			});
			});
			
		
			if(count > 0)
			{
				
			}
			else
			{
			if(exam_marks!='' && student_id!='' && $('form#marks')[0].checkValidity()){
			
					$.ajax({ 
					type: "POST",
					url: "<?php echo base_url(); ?>exams/save_exam_marks/",
					//dataType: "json",
					data: ({student_arr:(JSON.stringify(studentArr)),comp_arr:(JSON.stringify(compArr)),exam_id:'<?php echo $exam_id;?>',subject_id:'<?php echo $subject_id;?>',exam_marks:exam_marks,is_submitted:is_submitted,section_id:section_id,submit_val:submit_val}),
					
					success: function(response) {
						//alert(response);
						console.log(response);
						$('#session').css('display','block');
						$('#session').fadeOut('3000');
						var msg='msg';
						window.location.href="<?php echo base_url();?>exams/marks/" + msg+ "/" +exam_id + "/" + section_id + "/" + subject_id;
									
					}


				});

}
				}
		
			}
			else if($('#submit_val').val()=='approve')
			{
				$('#session').css('display','none');
			var studentArr={};
			var compArr={};
			var a=0;
			var b=0;
			var submit_val="approve";
			var is_approve=1;
			var section_id='<?php echo $section_id;?>';
			var exam_marks=$('.exam_marks').val();
			if(exam_marks!='')
			{
				
				$('.student_id').each(function(){
					//console.log(gradeArr);
		
				var student_id=$(this).attr('id');
				studentArr[a]={};
				studentArr[a]['student_id']=$(this).val();
				studentArr[a]['exam_marks']=$('#exam_marks'+$(this).val()).val();
				studentArr[a]['comment']=$('#comment'+$(this).val()).val();
				a++;
				});
			}
			var count=0;
			$('.student_id').each(function(){
				var student_id=$(this).val();
				
				$('.marks_obt_'+student_id).each(function() {
					$(this).css('border','1px solid #000');
					if($(this).val()=='')
					 marks=0;
					else
					marks=$(this).val();
				compArr[b]={}; 
				compArr[b]['student_id']=student_id;
				compArr[b]['marks']=marks;
				var subject_component_id=$(this).attr('id');
				subject_comp_id=subject_component_id.replace('comp_','');
				compArr[b]['subject_comp_id']=subject_comp_id;
				compArr[b]['total_obtained']=$('#total_obtained'+student_id).val();
				//alert($('.marks_obt_'+student_id).val());
				if($('.marks_obt_'+student_id).val()=="")
				{
					
					$('.marks_obt_'+student_id).after('<span class=red>Please fill this field</span>');
					count=count+1;
				
				}
				b++;
				studentArr[a]={};
				studentArr[a]['student_id']=student_id;
				studentArr[a]['comment']=$('#comment'+student_id).val();
				a++;
			});
			});
			
		
			if(count > 0)
			{
				
			}
			else
			{
			if(exam_marks!='' && student_id!='' && $('form#marks')[0].checkValidity()){
			
					$.ajax({ 
					type: "POST",
					url: "<?php echo base_url(); ?>exams/save_exam_marks/",
					//dataType: "json",
					data: ({student_arr:(JSON.stringify(studentArr)),comp_arr:(JSON.stringify(compArr)),exam_id:'<?php echo $exam_id;?>',subject_id:'<?php echo $subject_id;?>',exam_marks:exam_marks,is_approve:is_approve,section_id:section_id,submit_val:submit_val}),
					
					success: function(response) {
						console.log(response);
						$('#session').css('display','block');
						$('#session').fadeOut('3000');
						var msg='msg';
						window.location.href="<?php echo base_url();?>exams/marks/" + msg+ "/" +exam_id + "/" + section_id + "/" + subject_id;
						 
						
									
					}


				});

}
				}
			}
			
		
		});
		
		
		$('#reset-btn').click(function(){
			var section_id='<?php echo $section_id;?>';
			var studentArr={};
			var compArr={};
			var a=0;
			var b=0;
			if(confirm('Are you Sure?'))
			{
				var exam_marks=$('.exam_marks').val();
				if(exam_marks!='')
				{
					$('.student_id').each(function(){
						
					var student_id=$(this).attr('id');
					studentArr[a]={};
					studentArr[a]['student_id']=$(this).val();
					studentArr[a]['exam_marks']=$('#exam_marks'+$(this).val()).val();
					studentArr[a]['comment']=$('#comment'+$(this).val()).val();
					a++;
					});
					
				}
				$('.student_id').each(function(){
					var student_id=$(this).val();
					
					$('.marks_obt_'+student_id).each(function() {
						$(this).css('border','1px solid #000');
						if($(this).val()=='')
						 marks=0;
						else
						marks=$(this).val();
					compArr[b]={}; 
					compArr[b]['student_id']=student_id;
					compArr[b]['marks']=marks;
					var subject_component_id=$(this).attr('id');
					subject_comp_id=subject_component_id.replace('comp_','');
					compArr[b]['subject_comp_id']=subject_comp_id;
					compArr[b]['total_obtained']=$('#total_obtained'+student_id).val();
					b++;
					studentArr[a]={};
					studentArr[a]['student_id']=student_id;
					studentArr[a]['comment']=$('#comment'+student_id).val();
					a++;
					
					
				});
				
				});
				if(student_id!='')
				{
					$.ajax({ 
						type: "POST",
						url: "<?php echo base_url(); ?>exams/reset_exam_marks/",
						//dataType: "json",
						data: ({student_arr:(JSON.stringify(studentArr)),comp_arr:(JSON.stringify(compArr)),exam_id:'<?php echo $exam_id;?>',subject_id:'<?php echo $subject_id;?>',exam_marks:exam_marks,section_id:section_id}),
						
						success: function(response) {
							console.log(response);
							$('#select').trigger('click');
										
						}



					});
				}
			}	
		});
		 });
			
		});	
		
</script>