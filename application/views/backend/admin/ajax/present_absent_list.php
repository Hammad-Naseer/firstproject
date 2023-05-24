<?php

    $date_val=date('Y-m-d');
    $day=date('l');
    
    if(isset($date) && ($date!=""))
    {
        $date_val=date_slash($date);
        $day=date("l",strtotime($date_val));
    }
?>

<div class="today-date" style="padding:10px 0px; font-size:14px; font-weight:bold; color:#507895;">
	<?php echo convert_date($date_val)." (".$day.")"; ?>
</div>

<?php
    $q="SELECT a.*,s.name as staff_name,s.staff_id as staff_id,d.title as staff_designation,s.employee_code as employee_code FROM ".get_school_db().".attendance_staff a 
    INNER JOIN ".get_school_db().".staff s
    ON a.staff_id = s.staff_id
    INNER JOIN ".get_school_db().".designation d
    ON d.designation_id = s.designation_id 
    WHERE a.school_id=".$_SESSION['school_id']." AND a.status IN(2,3) AND d.is_teacher=1 AND a.date='".$date_val."'";

    $abs=$this->db->query($q)->result_array();
    if(count($abs) > 0)
    {

        $count=1;
        foreach($abs as $abs_list)
        {
            if($abs_list['status']=='2')
            {
            	$status=get_phrase('absent');
            }
            elseif($abs_list['status']=='3')
            {
            	$status=get_phrase('leave');
            }	
            if($abs_list['employee_code']!='')
            {
            	$employee_code=get_phrase("employee_code")." : ".$abs_list['employee_code'];
            }
        
            $attend_id=$abs_list['attend_staff_id'];
            $staff_id=$abs_list['staff_id'];

?>
  <div style="border-left: 4px solid #507895 !important; font-size: 14px; padding: 10px 0px; background-color: #ffffff; border: 1px solid #dddddd; font-weight:bold;">          
      <a onclick="toggle_func('<?php echo $attend_id;?>','<?php echo $staff_id;?>','<?php echo $day;?>','<?php echo $date_val;?>')" data-toggle="collapse" data-parent="#accordion" href="#SubSubMenu1<?php echo $attend_id;?>" aria-expanded="true" style="display:inline-block; width:95%;">
          <div class="col-lg-5 col-md-5 col-sm-5">
            <?php echo $abs_list['staff_name'].($employee_code);?>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3">
            <?php echo $abs_list['staff_designation'];?>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3">
             <?php echo get_phrase("status")." : ".$status;?>
          </div>
      </a>
  </div>
  
  <div id="SubSubMenu1<?php echo $attend_id;?>"  style="display: none; font-size: 12px; margin: 0px 10px; padding:10px;"></div>          
 
<?php
    $count++;
    }
    }else{
	    echo get_phrase("no_absent_teacher_found");
    }
?>

<script>
$(document).ready(function(){
	var attend_id1='<?php echo $attend_id1;?>'; 
	var staff_id1='<?php echo $staff_id1;?>';
	var day1='<?php echo $day1;?>';
	var date='<?php echo $date1;?>';
	
	if(attend_id1!="" && staff_id1!="" && day1!="" && date!="")
	{
		
		toggle_func(attend_id1,staff_id1,day1,date);
	}
});
function toggle_func(attend_id,staff_id,day,date) {
	
    $.ajax({
        type: 'POST',
        data: {
            attend_id: attend_id,
            staff_id:staff_id,
            day:day,
            date:date
            
           
        },
        url: "<?php echo base_url(); ?>asign_substitute/present_absent_generator",

        dataType: "html",
        success: function(response) {
        	
             $('#SubSubMenu1' + attend_id).show();
            $('#SubSubMenu1' + attend_id).html(response);
        }
    });
}
</script>
  