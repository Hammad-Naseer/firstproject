<?php
if (right_granted('manage_teacher_swapping'))
{?>
<style>
.validate-has-error {
    color: red;
}
</style>
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
 <?php
if($edit=='edit')
{
	$page_title="Edit Teacher Swapping";
	
	$teacher_id="";
	$from_c_rout_id="";
	$swap_date="";
	$title="";
	$comments="";
	$to_period_no="";
	$from_period_no="";
	$to_teacher_id="";
	$to_c_rout_id="";
	$swap_id="";
	foreach($edit_arr as $edit_arr1)
	{
		if($edit_arr1['swap_type']==1)
		{
			$teacher_id=$edit_arr1['teacher_id'];
			$from_c_rout_id=$edit_arr1['c_rout_id'];
			$from_period_no=$edit_arr1['period_no'];
		}
		if($edit_arr1['swap_type']==2)
		{
			$to_teacher_id=$edit_arr1['teacher_id'];
			$to_c_rout_id=$edit_arr1['c_rout_id'];
			$to_period_no=$edit_arr1['period_no'];
			
		}
		$swap_date=$edit_arr1['swap_date'];
		$title=$edit_arr1['title'];
		$comments=$edit_arr1['comments'];
		$swap_id=$edit_arr1['swap_id'];
	}
	$link='swap/teacher_swapping/do_update/'.$swap_id;
	$teacher_id=$edit_arr[0]['teacher_id'];
	$c_rout_id_edit=$edit_arr[0]['c_rout_id'];
}
else
{
	$page_title=" Add Teacher Swapping";
	$link='swap/teacher_swapping/create';
}
?>       

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <h3 class="system_name inline capitalize">
                 <?php echo $page_title;?>
            </h3>
            <a href="<?php echo base_url();?>swap/swapping" class="btn btn-primary float-right">
                <?php echo get_phrase('back');?>
            </a>
     	</div>
    </div>



<?php echo form_open(base_url().$link , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top','id'=>'disable_submit_btn'));?>
        <div>
            <div class="row filterContainer">
                 <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <select name="from_teacher_id" id="from_teacher_id"  class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        	<?php echo teacher_designation_option_list($teacher_id);?>
                        </select>
                    </div> 
                </div>
            </div>    
           
        </div>
   
      <div id="list">
      
                <?php
if(isset($teacher_id) && ($teacher_id > 0))
{       	
		$result = $this->db->query("select s.subject_id, s.name, s.code, cr.day, cr.period_no, d.title as department, class.name as class, cs.title as class_section,cr.duration, crs.period_duration,date_format(cr.period_start_time,'%H:%i')as period_start_time,date_format(cr.period_end_time,'%H:%i')as period_end_time,staff.name as staff_name,cr.class_routine_id,crs.no_of_periods,cs.section_id 
	from ".get_school_db().".subject s
	inner join ".get_school_db().".subject_teacher st on s.subject_id =  st.subject_id
	inner join ".get_school_db().".staff on staff.staff_id =  st.teacher_id
	inner join ".get_school_db().".time_table_subject_teacher ttst on st.subject_teacher_id =  ttst.subject_teacher_id
	inner join ".get_school_db().".class_routine cr on cr.class_routine_id =  ttst.class_routine_id
	inner join ".get_school_db().".class_routine_settings crs on (crs.c_rout_sett_id =  cr.c_rout_sett_id and crs.is_active = 1)
	inner join ".get_school_db().".subject_section ss on ss.subject_id = st.subject_id
	inner join ".get_school_db().".class_section cs on cs.section_id = crs.section_id
	inner join ".get_school_db().".class on class.class_id = cs.class_id
	inner join ".get_school_db().".departments d on d.departments_id = class.departments_id
	where staff.staff_id=$teacher_id
	AND crs.school_id = ".$_SESSION['school_id']." 
	AND crs.is_active=1
	group by day, period_no
	order by day, period_no
	")->result_array();
	//echo $this->db->last_query();
$list_array=array();
	if(sizeof($result)>0)
	{
		?>
	
<?php
		$period_start_time="";
		$period_end_time="";
		$no_of_periods=0;
		foreach ($result as $row) 
		{
			$list_array[$row['day']][$row['period_no']] = 
			array(
			'subject_name'=>$row['name'].' - '.$row['code'],
			'class'=>$row['class'].' - '.$row['class_section'],
			'duration'=>$row['duration'],
			'default_duration'=>$row['period_duration'],
			
			'period_start_endtime'=>$row['period_start_time'].' / '.$row['period_end_time'],
			'class_routine_id'=>$row['class_routine_id'],
			'subject_id'=>$row['subject_id'],
			'section_id'=>$row['section_id']
			);
			$no_of_periods=$row['no_of_periods'];
		}
	
	?>
	
	<div class="col-lg-12 col-md-12">
	 
	   <h3><?php echo get_phrase('time_table_details'); ?></h3>
	   <table class="table table-striped table-bordered table_export" cellspacing="0">
      <thead>
            <tr>
            <th>
                    <div></div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('day_/_period_no');?>
                    </div>
                </th>
                
                <th>
                    <div>
                        <?php echo get_phrase('subject');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('class_section');?>
                    </div>
                </th>
                <th>
                    <div>
                     <?php echo get_phrase('start/end_time(duration)');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody> 
        <?php
	if(count($list_array) > 0)
	{
		$count = 1;
		
		foreach($list_array as $key=>$value)
		{
			$duration=0;
			foreach($list_array[$key] as $key1=>$val)
			{
				$radio_selected="";
				if($from_c_rout_id==$val['class_routine_id'])
				{
					$radio_selected='checked';
				}
				?>
			<tr>
				<td><input type="radio" name="frm_class_rout_id" id="frm_class_rout_id" <?php echo $radio_selected;?> value="<?php echo $val['class_routine_id'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"></td>
                
				<td><?php echo ucfirst($key)." (".$key1.")";?>
				
				
				</td>
				
				
				
				<td><?php echo $val['subject_name'];?>
				
				</td>
				
				<td><?php echo $val['class'];?>
				
				</td>
				
				<td>
				<?php
				echo $val['period_start_endtime'];
				$period_start_endtime=$val['period_start_endtime'];
				$start_end_arr=explode("/",$period_start_endtime);
				$start_time=$start_end_arr[0];
				$end_time=$start_end_arr[1];
				$duration=$val['duration'];
				$duration_val=0;
				$duration=$val['duration'];
				$duration_val=0;
				if(isset($duration) && ($duration >0))
				{
					$duration_val=$val['duration'];
					echo " (".$duration_val." mins)";
				}
				else
				{
					$duration_val=$val['default_duration'];
					echo " (".$duration_val." mins)";
				}
				?>
				
				
				</td>
				</tr>
			<?php	
			}
		}
		?>
				
				</tbody>
				</table>
    
    </div>
	<?php
	}?>
	
	
	 <div>
        <div class="row filterContainer">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="form-group">
                    <label><?php echo get_phrase('swap_date');?></label>
                    <input type="text" class="datepicker form-control" name="swap_date" id="swap_date" value="<?php echo date_dash($swap_date); ?>" 
                           data-format="dd/mm/yyyy" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" required placeholder="date">
                </div> 
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6" style="padding-left: 30px;">
                <div class="form-group">
                    <label><?php echo get_phrase('swap_period_no');?></label>
                    <?php
                	    echo period_option_list('to_period_no','form-control','to_period_no',$no_of_periods,$to_period_no);
                	?>
                </div> 
            </div>
             <div class="col-lg-4 col-md-4 col-sm-6" style="padding-top: 30px;padding-left: 30px;">
                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="btn_submit"><?php echo get_phrase('get_details'); ?></button>
                </div> 
            </div>
        </div>    
           
    </div>
	
        
        <div id="result_div">
        	
        </div>
        
       
        
        
	<?php
	}	
	else
	{ ?>
		<strong>
        <?php echo get_phrase('no_record_found'); ?>
        </strong>
	<?php
    }
}
				?>
                </div>
<?php echo form_close();?>
      
    
<script> 
$(document).ready(function(){
var date = $('#swap_date').val();
var period_no= $('#to_period_no').val();
var teacher_id=$('#from_teacher_id').val();
var to_c_rout_id='<?php echo $to_c_rout_id;?>';
var to_teacher_id='<?php echo $to_teacher_id;?>';
var title='<?php echo $title;?>';
var comments='<?php echo $comments;?>';
if (date != "" && period_no!='' && to_c_rout_id!='') {
                $('#btn_remove').show();
                $('#result_div').html('<div id="message" class="loader"></div>');

                $.ajax({
                    type: 'POST',
                    data: {
                        date: date,
                        period_no:period_no,
                        teacher_id:teacher_id,
                        to_c_rout_id:to_c_rout_id,
                        title:title,
                        comments:comments,
                        to_teacher_id:to_teacher_id
                    },
                    url: "<?php echo base_url();?>swap/from_teacher_result",
                    dataType: "html",
                    success: function(response) {
						//alert(response);
                        $('#message').remove();
                        $('#result_div').html(response);
                    }
                    
                });

            } else {
                //$("#result_div").html("<?php echo get_phrase('please_select_any_filter_to_proceed'); ?>");

            }	
	
	
  $('#from_teacher_id').change(function()
  {
 	var teacher_id=$('#from_teacher_id').val();
 	if(teacher_id!='')
 	{
		window.location.href = "<?php echo base_url();?>swap/teacher_swapping/" + teacher_id;
 	}
 	else
 	{
		$('#list').html("");
	}
	});   

	$('#btn_submit').click(function(){
		var date = $('#swap_date').val();
		var period_no= $('#to_period_no').val();
		var teacher_id=$('#from_teacher_id').val();
		var title='<?php echo $title;?>';
		var comments='<?php echo $comments;?>';
		
		if (date != "" && period_no!='') {
                $('#btn_remove').show();
                $('#result_div').html('<div id="message" class="loader"></div>');

                $.ajax({
                    type: 'POST',
                    data: {
                        date: date,
                        period_no:period_no,
                        teacher_id:teacher_id,
                        title:title,
                        comments:comments
                    },
                    url: "<?php echo base_url();?>swap/from_teacher_result",
                    dataType: "html",
                    success: function(response) {
						//alert(response);
                        $('#message').remove();
                        $('#result_div').html(response);
                    }
                    
                });

            } else {
                $("#result_div").html("<?php echo get_phrase('please_select_any_filter_to_proceed'); ?>");

            }

			});	

$('#to_day').change(function(){
	$('#result_div').html("");
});

$('#to_period_no').change(function(){
	$('#result_div').html("");
});
	          
});
</script> 

 <style>
    .loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #63b7e7;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    </style>   
<?php
}
?>    
    
    
    
    
